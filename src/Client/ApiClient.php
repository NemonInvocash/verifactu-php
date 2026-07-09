<?php

namespace VerifactuPHP\Client;

/**
 * Cliente con los endpoints comunes de la API (no atados a Verifactu ni TBAI):
 * estado del servidor, censo AEAT, gestión de emisores y webhooks.
 *
 * VerifactuClient y TbaiClient heredan de esta clase.
 */
abstract class ApiClient extends BaseClient
{
    // Estado del Servidor

    // Comprueba que el servidor está operativo (no requiere autenticación)
    public function status(): array
    {
        return $this->peticion(false, 'GET', self::API_URL . '/status');
    }

    // Consultas Externas

    // Valida uno o varios destinatarios contra el censo de AEAT
    public function censoAeat(array $destinatarios): array
    {
        return $this->peticion(true, 'POST', self::API_URL . '/censo-aeat', ['Destinatarios' => $destinatarios]);
    }

    // Emisores

    // Lista los emisores de la cuenta ($query se concatena tal cual a la URL, p.ej. '?enabled=1')
    public function listarEmisores(string $query = ''): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/emisor' . $query);
    }

    // Obtiene un emisor por su id
    public function obtenerEmisor(string $id): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/emisor/' . rawurlencode($id));
    }

    // Crea un nuevo emisor (requiere login de usuario)
    public function crearEmisor(array $datos): array
    {
        $this->soloUsuario();
        return $this->peticion(true, 'POST', self::API_URL . '/emisor', $datos);
    }

    // Actualiza un emisor existente por su id (requiere login de usuario)
    public function actualizarEmisor(string $id, array $datos): array
    {
        $this->soloUsuario();
        return $this->peticion(true, 'PUT', self::API_URL . '/emisor/' . rawurlencode($id), $datos);
    }

    // Genera (y devuelve) la API key de un emisor por su id (requiere login de usuario)
    public function generarApiKey(string $id): array
    {
        $this->soloUsuario();
        return $this->peticion(true, 'GET', self::API_URL . '/emisor/' . rawurlencode($id) . '/api-key');
    }

    // Webhooks

    // Lista los webhooks configurados ($query se concatena tal cual a la URL, p.ej. '?activo=1') (requiere login de usuario)
    public function listarWebhooks(string $query = ''): array
    {
        $this->soloUsuario();
        return $this->peticion(true, 'GET', self::API_URL . '/webhook' . $query);
    }

    // Obtiene un webhook por su id (requiere login de usuario)
    public function obtenerWebhook(string $id): array
    {
        $this->soloUsuario();
        return $this->peticion(true, 'GET', self::API_URL . '/webhook/' . rawurlencode($id));
    }

    // Obtiene los logs de un webhook por su id (requiere login de usuario)
    public function logsWebhook(string $id): array
    {
        $this->soloUsuario();
        return $this->peticion(true, 'GET', self::API_URL . '/webhook/' . rawurlencode($id) . '/logs');
    }

    // Crea un nuevo webhook (requiere login de usuario)
    public function crearWebhook(array $datos): array
    {
        $this->soloUsuario();
        return $this->peticion(true, 'POST', self::API_URL . '/webhook', $datos);
    }

    // Actualiza un webhook existente por su id (requiere login de usuario)
    public function actualizarWebhook(string $id, array $datos): array
    {
        $this->soloUsuario();
        return $this->peticion(true, 'PUT', self::API_URL . '/webhook/' . rawurlencode($id), $datos);
    }

    // Elimina un webhook por su id (requiere login de usuario)
    public function eliminarWebhook(string $id): array
    {
        $this->soloUsuario();
        return $this->peticion(true, 'DELETE', self::API_URL . '/webhook/' . rawurlencode($id));
    }
}
