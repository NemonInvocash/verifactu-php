<?php

namespace VerifactuPHP;

use VerifactuPHP\Client\ApiClient;

final class TbaiClient extends ApiClient
{
    // Alta Registro Facturacion

    // Registra un nuevo registro de facturación TBAI (alta)
    public function altaRegistroTbai(array $factura): array
    {
        return $this->peticion(true, 'POST', self::API_URL . '/alta-registro-tbai', $factura);
    }

    // Valida el formato de una factura TBAI sin llegar a registrarla
    public function validarRegistroTbai(array $factura): array
    {
        return $this->peticion(true, 'POST', self::API_URL . '/alta-registro-tbai/validar', $factura);
    }

    // Anulacion Registro Facturacion

    // Anula una factura TBAI por sus datos identificadores (serie, número, fecha...)
    public function anularRegistroTbaiPorDatos(array $datos): array
    {
        return $this->peticion(true, 'POST', self::API_URL . '/anulacion-registro-tbai', $datos);
    }

    // Anula un registro de facturación TBAI existente por su id
    public function anularRegistroTbaiPorId(string $id): array
    {
        return $this->peticion(true, 'POST', self::API_URL . '/anulacion-registro-tbai/' . rawurlencode($id));
    }

    // Listado Registro Facturacion

    // Lista los registros de facturación TBAI de la cuenta ($query se concatena tal cual a la URL, p.ej. '?fecha=2026-01-01')
    public function listarRegistrosTbai(string $query = ''): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/alta-registro-tbai' . $query);
    }

    // Obtiene un registro de facturación TBAI por su id
    public function obtenerRegistroTbai(string $id): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/alta-registro-tbai/' . rawurlencode($id));
    }

    // Envios

    // Lista los envíos TBAI de la cuenta ($query se concatena tal cual a la URL)
    public function listarEnviosTbai(string $query = ''): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/envios-tbai' . $query);
    }

    // Obtiene un envío TBAI por su id
    public function obtenerEnvioTbai(string $id): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/envios-tbai/' . rawurlencode($id));
    }

    // Consultas

    // Consulta registros de facturación TBAI ya presentados
    public function consultarRegistrosTbai(array $datos): array
    {
        return $this->peticion(true, 'POST', self::API_URL . '/consultar-registros-tbai', $datos);
    }

    // Obtiene una consulta de registros TBAI por su id
    public function obtenerConsultaTbai(string $id): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/consultar-registros-tbai/' . rawurlencode($id));
    }

    // Listas de Codigos

    // Lista todas las listas de códigos TBAI disponibles
    public function listarListasTbai(): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/listas-tbai');
    }

    // Obtiene los valores de una lista de códigos TBAI concreta
    public function obtenerListaTbai(string $lista): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/listas-tbai/' . rawurlencode($lista));
    }

    // Obtiene una línea concreta de una lista de códigos TBAI
    public function obtenerLineaListaTbai(string $lista, string $valor): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/listas-tbai/' . rawurlencode($lista) . '/' . rawurlencode($valor));
    }

    // Exclusivos TBAI

    // Obtiene el QR de una factura TBAI por su id
    public function obtenerQrTbai(string $id): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/tbai/QR/' . rawurlencode($id));
    }

    // Zuzendu: modifica una factura TBAI por su id
    public function zuzenduModificarTbai(string $id, array $datos = []): array
    {
        return $this->peticion(true, 'POST', self::API_URL . '/tbai/modificar/' . rawurlencode($id), $datos);
    }

    // Zuzendu: subsana una factura TBAI por su id
    public function zuzenduSubsanarTbai(string $id, array $datos = []): array
    {
        return $this->peticion(true, 'POST', self::API_URL . '/tbai/subsanar/' . rawurlencode($id), $datos);
    }

    // Software

    // Obtiene los datos del software TBAI por su tipo
    public function obtenerSoftwareTbai(string $type): array
    {
        return $this->peticion(true, 'GET', self::API_URL . '/software-tbai/' . rawurlencode($type));
    }
}
