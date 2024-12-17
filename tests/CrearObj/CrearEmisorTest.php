<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\ClienteVerifactu;
use tests\LogInCredentials;

class CrearEmisorTest extends LogInCredentials
{
    private ClienteVerifactu $cliente;

    // Configuración inicial antes de cada prueba
    public function setUp(): void
    {
        $this->cliente = new ClienteVerifactu(parent::email, parent::password);
    }

    // Prueba para crear un emisor
    public function testCrearEmisor(): void
    {
        try {
            // Datos para el emisor
            $dataEmisor = [
                'nif' => "S2819426D",
                'nombre' => "EMPRESA TEST",
                'enviarAeat' => true,
                'enviarAeatPaused' => false,
                'webhookID' => 10
            ];

            // Crear el emisor
            $emisor = $this->cliente->nuevoEmisor($dataEmisor);

            // Array esperado para la comparación
            $expectedArray = [
                'nif' => "S2819426D",
                'nombre' => "EMPRESA TEST",
                'enviar_aeat' => true,
                'enviar_aeat_paused' => false,
                'default_webhook_id' => 10
            ];

            // Verificar que el array generado coincide con el esperado
            $this->assertEquals($expectedArray, $emisor->getArrayData(), "El array generado no coincide con el esperado.");

        } catch (Exception $e) {
            // Manejo de errores en la creación del emisor
            $this->fail("Error al crear emisor: " . $e->getMessage());
        }
    }
}