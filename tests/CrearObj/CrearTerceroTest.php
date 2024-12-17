<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\ClienteVerifactu;
use tests\LogInCredentials;

class CrearTerceroTest extends LogInCredentials
{
    private ClienteVerifactu $cliente;

    // Configuración inicial antes de cada prueba
    public function setUp(): void
    {
        $this->cliente = new ClienteVerifactu(parent::email, parent::password);
    }

    // Prueba para crear un tercero
    public function testCrearTercero(): void
    {
        try {
            // Datos para el tercero
            $dataTercero = [
                'tercNombreRazon' => "NOMBRE EJEMPLO",
                'tercNIF' => "39707287H",
                'tercOtroCodPais' => "ES",
                'tercOtroIDType' => 1,
                'tercOtroID' => ""
            ];

            // Crear el tercero
            $tercero = $this->cliente->nuevoTercero($dataTercero);

            // Array esperado para la comparación
            $expectedArray = [
                'TercNombreRazon' => "NOMBRE EJEMPLO",
                'TercNIF' => "39707287H",
                'TercOtroCodPais' => "ES",
                'TercOtroIDType' => 1,
                //'TercOtroID' => ""
            ];

            // Verificar que el array generado coincide con el esperado
            $this->assertEquals($expectedArray, $tercero->getArrayData(), "El array generado no coincide con el esperado.");

        } catch (Exception $e) {
            // Manejo de errores en la creación del tercero
            $this->fail("Error al crear tercero: " . $e->getMessage());
        }
    }
}