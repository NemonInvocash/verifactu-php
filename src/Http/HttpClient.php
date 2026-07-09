<?php

namespace VerifactuPHP\Http;

use VerifactuPHP\Exceptions\ApiException;
use VerifactuPHP\Exceptions\TransportException;

/**
 * Cliente HTTP basado en cURL para las llamadas a la API de Verifactu.
 */
final class HttpClient
{
    private const METODOS_PERMITIDOS = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    public function __construct(
        private readonly int $timeoutSeconds = 30,
        private readonly bool $verifySsl = true,
    ) {
    }

    /**
     * Construye las cabeceras estándar de las peticiones, añadiendo el Bearer token si se indica.
     */
    public function buildHeaders(?string $token = null): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        if ($token !== null) {
            $headers['Authorization'] = "Bearer $token";
        }

        return $headers;
    }

    /**
     * Ejecuta una petición HTTP y devuelve el código de estado y el cuerpo decodificado de la respuesta en formato array.
     */
    public function request(string $method, string $url, array $headers = [], ?string $body = null): array
    {
        $method = strtoupper($method);
        if (!in_array($method, self::METODOS_PERMITIDOS, true)) {
            throw new \InvalidArgumentException("Método HTTP no soportado: $method");
        }

        $handle = curl_init($url);
        if ($handle === false) {
            throw new TransportException("No se pudo inicializar la petición cURL para $url");
        }

        $curlHeaders = [];
        foreach ($headers as $name => $value) {
            $curlHeaders[] = "$name: $value";
        }

        curl_setopt_array($handle, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $curlHeaders,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeoutSeconds,
            CURLOPT_SSL_VERIFYPEER => $this->verifySsl,
            CURLOPT_SSL_VERIFYHOST => $this->verifySsl ? 2 : 0,
        ]);

        $responseBody = curl_exec($handle);

        if ($responseBody === false) {
            $error = curl_error($handle);
            $reason = $this->motivoTransporte(curl_errno($handle));
            curl_close($handle);
            throw new TransportException("Error de transporte al llamar a $url: $error", $reason);
        }

        $statusCode = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        curl_close($handle);

        $data = json_decode($responseBody, true);

        if (is_array($data) && $statusCode >= 200 && $statusCode <= 499) {
            return [$statusCode, $data];
        }

        throw new ApiException($this->mensajeError($statusCode, $responseBody), $statusCode, $this->motivoApi($statusCode));
    }

    /**
     * Determina el código de motivo A-* de ApiException según el status HTTP:
     * fuera del rango normal o 5xx => error de servidor (A-3); en 2xx/3xx => respuesta ilegible (A-2).
     */
    private function motivoApi(int $statusCode): string
    {
        return ($statusCode < 200 || $statusCode >= 500) ? 'A-3' : 'A-2';
    }

    /**
     * Traduce el código de error de cURL al código de motivo T-* de TransportException.
     *
     * Se usan los números de error de libcurl (estables) en lugar de las constantes CURLE_*,
     * porque no todas están definidas en todos los builds de PHP (p.ej. CURLE_PEER_FAILED_VERIFICATION = 51).
     */
    private function motivoTransporte(int $curlErrno): string
    {
        return match ($curlErrno) {
            28 => 'T-1',                             // CURLE_OPERATION_TIMEDOUT
            35, 51, 58, 59, 60, 66, 77, 83 => 'T-2', // errores SSL (conexión, verificación de certificado, cifrado...)
            5, 6, 7 => 'T-3',                        // no se pudo resolver el proxy/host o conectar
            default => 'T-0',
        };
    }

    /**
     * Construye el mensaje de error según el rango del código HTTP.
     */
    private function mensajeError(int $statusCode, string $body): string
    {
        $extracto = substr($body, 0, 200);

        if ($statusCode < 200 || $statusCode >= 500) {
            return "Error del servidor (HTTP $statusCode).";
        }

        if ($statusCode < 300) {
            return "Operación aceptada (HTTP $statusCode) pero la respuesta es ilegible; verifica el estado antes de reintentar. Response: $extracto";
        }

        return "Operación rechazada (HTTP $statusCode) pero la respuesta es ilegible. Response: $extracto";
    }
}
