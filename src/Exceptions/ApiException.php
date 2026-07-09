<?php

namespace VerifactuPHP\Exceptions;

use Exception;

class ApiException extends Exception
{
    public function __construct(
        string $message,
        private readonly int $statusCode,
        private readonly string $reason = 'A-0',
    ) {
        parent::__construct($message);
    }

    /**
     * Código de estado HTTP asociado al error.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Código de motivo del error de API, para diferenciar la subcausa sin parsear el mensaje.
     * Ver los códigos A-* en el README (incluye el A-4 de ApiRequestRefusedException).
     */
    public function getReason(): string
    {
        return $this->reason;
    }
}
