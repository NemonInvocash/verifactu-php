<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\ClienteVerifactu;
use tests\LogInCredentials;

class CrearRegistroAnulacionTest extends LogInCredentials
{
    private ClienteVerifactu $cliente;

    // Configuración inicial antes de cada prueba
    public function setUp(): void
    {
        $this->cliente = new ClienteVerifactu(parent::email, parent::password);
    }

    // Prueba para crear un registro de anulación
    public function testCrearRegistroAnulacion(): void
    { 
        try {
            // Datos para el registro de anulación
            $dataRegistroAnulacion = [
                'IDEmisorFactura' => "S2819426A",
                'numSerieFactura' => "AX/202412-009",
                'fechaExpedicionFactura' => "2024-11-27",
                'refExterna' => "Test Ref Externa",
                'sinRegistroPrevio' => 0,
                'rechazoPrevioA' => 1,
                'generadoPor' => 0
            ];

            // Crear el registro de anulación
            $registroAnulacion = $this->cliente->nuevoRegistroAnulacion($dataRegistroAnulacion);
            
            // Array esperado para la comparación
            $expectedArray = [
                'IDEmisorFactura' => "S2819426A",
                'NumSerieFactura' => "AX/202412-009", 
                'FechaExpedicionFactura' => "2024-11-27",
                'RefExterna' => "Test Ref Externa",
                //'SinRegistroPrevio' => 0,
                'RechazoPrevioA' => 1,
                //'GeneradoPor' => 0
            ];

            // Verificar que el array generado coincide con el esperado
            $this->assertEquals($expectedArray, $registroAnulacion->getArrayData(), "El array generado no coincide con el esperado.");

        } catch (Exception $e) {
            // Manejo de errores en la creación del registro de anulación
            $this->fail("Error al crear registro de anulacion: " . $e->getMessage());
        }
    }
}