<?php

namespace VerifactuPHP\Client;

use VerifactuPHP\Http\HttpClient;
use VerifactuPHP\Exceptions\ApiException;
use VerifactuPHP\Exceptions\ApiRequestRefusedException;
use VerifactuPHP\Exceptions\ClientException;

abstract class BaseClient
{
    protected const API_URL = 'https://app.verifactuapi.es/api';
    private ?string $token = null;

    protected function __construct(
        protected readonly HttpClient $httpClient,
        protected readonly bool $esEmisor,
        protected readonly string $user,
        #[\SensitiveParameter]
        protected readonly ?string $password = null,
        #[\SensitiveParameter]
        protected readonly ?string $apiKey = null,
    ) {
    }

    /**
     * Crea un cliente autenticado como usuario (login con email + contraseña), con permisos de lectura y escritura.
     */
    public static function usuario(string $email, #[\SensitiveParameter] string $password): static
    {
        return new static(new HttpClient(), false, $email, $password);
    }

    /**
     * Crea un cliente autenticado como emisor (login con username + API key), limitado al NIF del emisor.
     */
    public static function emisor(string $username, #[\SensitiveParameter] string $apiKey): static
    {
        return new static(new HttpClient(), true, $username, apiKey: $apiKey);
    }

    /**
     * Escape hatch para llamar a un endpoint que la librería no envuelve. Construye la URL a partir
     * de la base de la API y delega en la lógica estándar de autenticación, reintento y errores.
     *
     * @param string     $method  Método HTTP (GET, POST, PUT, PATCH, DELETE).
     * @param string     $path    Ruta relativa a la base de la API, empezando por '/', p.ej. '/mi-endpoint?x=1'.
     * @param array|null $body    Body que se enviará como JSON, o null si no hay.
     * @param bool       $conAuth Si la petición requiere token de autenticación (por defecto, sí).
     */
    public function request(string $method, string $path, ?array $body = null, bool $conAuth = true): array
    {
        return $this->peticion($conAuth, $method, self::API_URL . $path, $body);
    }

    /**
     * Ejecuta una petición contra la API: autentica si hace falta y devuelve la respuesta decodificada.
     * Ante un 401 de la capa de auth (token caducado/inválido) renueva el token y reintenta una sola
     * vez (seguro: el 401 se corta antes de ejecutar la acción, no duplica); si el 401 persiste, lanza
     * ApiException. Si la API rechaza la petición (success:false), lanza ApiRequestRefusedException.
     */
    protected function peticion(bool $conAuth, string $method, string $url, ?array $body = null, bool $reintentar = true): array
    {
        $jsonBody = $this->serializarBody($body);

        if ($conAuth && $this->token === null) {
            $this->login();
        }

        $headers = $this->httpClient->buildHeaders($conAuth ? $this->token : null);

        [$statusCode, $data] = $this->httpClient->request($method, $url, $headers, $jsonBody);

        if ($conAuth && $statusCode === 401 && !isset($data['success'])) {
            if ($reintentar) {
                $this->token = null;
                return $this->peticion($conAuth, $method, $url, $body, false);
            }

            throw new ApiException($data['message'] ?? 'Error de autenticación', $statusCode, 'A-1');
        }

        if (!($data['success'] ?? false)) {
            throw new ApiRequestRefusedException($data['message'] ?? 'Error desconocido de la API', $statusCode, $data);
        }

        return $data;
    }

    /**
     * Verifica que el cliente esté autenticado como usuario. Las operaciones de gestión (emisores,
     * webhooks) requieren permiso de superuser, del que carece el login de emisor: si el cliente es
     * un emisor se corta aquí con ClientException, sin llegar a lanzar la petición.
     */
    protected function soloUsuario(): void
    {
        if ($this->esEmisor) {
            throw new ClientException('Esta operación requiere autenticación como usuario (login con email y contraseña); no está disponible para un cliente de emisor.', 'C-2');
        }
    }

    /**
     * Serializa el body a JSON, o null si no hay body. Lanza ClientException si la codificación falla.
     */
    private function serializarBody(?array $body): ?string
    {
        if ($body === null) {
            return null;
        }

        $jsonBody = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($jsonBody === false) {
            throw new ClientException('No se pudo convertir el body a JSON: ' . json_last_error_msg(), 'C-1');
        }

        return $jsonBody;
    }

    /**
     * Autentica contra la API (usuario o emisor) y guarda el token para las siguientes peticiones.
     */
    private function login(): void
    {
        if ($this->esEmisor) {
            $url = self::API_URL . '/loginEmisor';
            $body = [
                'username' => $this->user,
                'api_key' => $this->apiKey,
            ];
        } else {
            $url = self::API_URL . '/login';
            $body = [
                'email' => $this->user,
                'password' => $this->password,
            ];
        }

        $data = $this->peticion(false, 'POST', $url, $body);

        if (!isset($data['token']) || !is_string($data['token']) || $data['token'] === '') {
            throw new ApiException('La respuesta de login no contiene un token válido', 500, 'A-1');
        }

        $this->token = $data['token'];
    }
}
