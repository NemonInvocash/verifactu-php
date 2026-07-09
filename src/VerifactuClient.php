<?php

namespace VerifactuPHP;

use VerifactuPHP\Client\ApiClient;

final class VerifactuClient extends ApiClient
{
    // Alta Registro Facturacion

    // Registra un nuevo registro de facturación Verifactu (alta)
    public function altaRegistroFacturacion(array $factura): array
    {
        return $this->peticion(true, 'POST', self::API_URL . '/alta-registro-facturacion', $factura);
    }

    // Valida el formato de una factura Verifactu sin llegar a registrarla
    public function validarRegistroFacturacion(array $factura): array
    {
        return $this->peticion(true, 'POST', self::API_URL . '/alta-registro-facturacion/validar', $factura);
    }

    // Anulacion Registro Facturacion

    // Anula una factura Verifactu por sus datos identificadores (serie, número, fecha...)
    public function anularRegistroFacturacionPorDatos(array $datos): array
    {
        return $this->peticion(true, 'POST', self::API_URL . '/anulacion-registro-facturacion', $datos);
    }

    // Anula un registro de facturación Verifactu existente por su id
    public function anularRegistroFacturacionPorId(string $id): array
    {
        return $this->peticion(true, 'POST', self::API_URL . '/anulacion-registro-facturacion/' . rawurlencode($id));
    }

    // Listado Registro Facturacion

    // Lista los registros de facturación Verifactu de la cuenta ($query se concatena tal cual a la URL, p.ej. '?fecha=2026-01-01')
    public function listarRegistrosFacturacion(string $query = ''): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/alta-registro-facturacion' . $query);
    }

    // Obtiene un registro de facturación Verifactu por su id
    public function obtenerRegistroFacturacion(string $id): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/alta-registro-facturacion/' . rawurlencode($id));
    }

    // Envios AEAT

    // Lista los envíos a AEAT de la cuenta ($query se concatena tal cual a la URL)
    public function listarEnviosAeat(string $query = ''): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/envios-aeat' . $query);
    }

    // Obtiene un envío a AEAT por su id
    public function obtenerEnvioAeat(string $id): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/envios-aeat/' . rawurlencode($id));
    }

    // Obtiene una línea de envío a AEAT por su id
    public function obtenerLineaEnvioAeat(string $lineaId): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/envios-aeat/linea/' . rawurlencode($lineaId));
    }

    // Consultas AEAT

    // Consulta registros de facturación ya presentados en AEAT
    public function consultarRegistrosAeat(array $datos): array
    {
        return $this->peticion(true, 'POST', self::API_URL . '/consultar-registros-aeat', $datos);
    }

    // Listas de Codigos

    // Lista todas las listas de códigos Verifactu disponibles
    public function listarListas(): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/listas');
    }

    // Obtiene los valores de una lista de códigos concreta
    public function obtenerLista(string $lista): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/listas/' . rawurlencode($lista));
    }

    // Obtiene una línea concreta de una lista de códigos
    public function obtenerLineaLista(string $lista, string $valor): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/listas/' . rawurlencode($lista) . '/' . rawurlencode($valor));
    }
}
