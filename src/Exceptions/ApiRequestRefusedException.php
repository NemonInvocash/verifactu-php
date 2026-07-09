<?php

namespace VerifactuPHP\Exceptions;

/**
 * La API procesó la petición y la rechazó con un error estructurado (success:false).
 * A diferencia de ApiException (fallo de servidor/respuesta malformada), aquí el body de
 * error trae detalle aprovechable por el caller: id de factura duplicada, index/responses
 * de un envío masivo, validation_code, etc.
 */
class ApiRequestRefusedException extends ApiException
{
    /**
     * @param array<string, mixed> $data Cuerpo completo de la respuesta de error de la API.
     */
    public function __construct(
        string $message,
        int $statusCode,
        private readonly array $data = [],
    ) {
        parent::__construct($message, $statusCode, 'A-4');
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }
}
