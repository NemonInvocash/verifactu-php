<?php

namespace VerifactuPHP\Exceptions;

use Exception;

class ClientException extends Exception
{
    public function __construct(
        string $message,
        private readonly string $reason = 'C-0',
    ) {
        parent::__construct($message);
    }

    /**
     * Código de motivo del error de cliente, para diferenciar la subcausa sin parsear el
     * mensaje. Ver los códigos C-* en el README.
     */
    public function getReason(): string
    {
        return $this->reason;
    }
}
