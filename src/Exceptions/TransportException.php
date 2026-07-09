<?php

namespace VerifactuPHP\Exceptions;

use Exception;

class TransportException extends Exception
{
    public function __construct(
        string $message,
        private readonly string $reason = 'T-0',
    ) {
        parent::__construct($message);
    }

    /**
     * Código de motivo del fallo de transporte, para diferenciar la subcausa sin parsear el
     * mensaje. Ver los códigos T-* en el README.
     */
    public function getReason(): string
    {
        return $this->reason;
    }
}
