<?php

use PHPUnit\Framework\TestCase;
use verifactuPHP\Models\Emisor;

class EmisorTest extends TestCase
{
    public function testEmisorInitialization()
    {
        // Datos de entrada simulados
        $data = [
            'nif' => 'S2819426D',
            'nombre' => 'EMPRESA TEST',
            'representanteRazonSocial' => 'REPRESENTANTE TEST', 
            'representanteNif' => 'B12345678',
            'enviarAeat' => true,
            'enviarAeatPaused' => false,
            'webhookID' => 1
        ];

        try {
            // Crear una nueva instancia de Emisor
            $emisor = new Emisor($data);

            // Asegurarse de que los valores se asignaron correctamente
            $this->assertEquals('S2819426D', $emisor->getNif(), "Error: El NIF no coincide");
            $this->assertEquals('EMPRESA TEST', $emisor->getNombre(), "Error: El nombre no coincide");
            $this->assertEquals('REPRESENTANTE TEST', $emisor->getRepresentanteRazonSocial(), "Error: La razón social del representante no coincide");
            $this->assertEquals('B12345678', $emisor->getRepresentanteNif(), "Error: El NIF del representante no coincide");
            $this->assertTrue($emisor->getEnviarAeat(), "Error: enviarAeat debería ser true");
            $this->assertFalse($emisor->getEnviarAeatPaused(), "Error: enviarAeatPaused debería ser false");
            $this->assertEquals(1, $emisor->getWebhookID(), "Error: El webhookID no coincide");

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function testSettersAndGetters()
    {
        try {
            // Datos de entrada para inicializar el Emisor
            $data = [
                'nif' => 'S2819426D',
                'nombre' => 'EMPRESA TEST',
                'representanteRazonSocial' => 'REPRESENTANTE TEST',
                'representanteNif' => 'B12345678', 
                'enviarAeat' => true,
                'enviarAeatPaused' => false,
                'webhookID' => 123
            ];

            // Crear una nueva instancia de Emisor
            $emisor = new Emisor($data);

            // Cambiar los valores utilizando los setters
            $emisor->setNif('B87654321');
            $emisor->setNombre('NUEVA EMPRESA');
            $emisor->setRepresentanteRazonSocial('NUEVO REPRESENTANTE');
            $emisor->setRepresentanteNif('X1234567Y');
            $emisor->setEnviarAeat(false);
            $emisor->setEnviarAeatPaused(true);
            $emisor->setWebhookID(456);

            // Verificar que los valores han sido actualizados correctamente
            $this->assertEquals('B87654321', $emisor->getNif(), "Error: El nuevo NIF no se actualizó correctamente");
            $this->assertEquals('NUEVA EMPRESA', $emisor->getNombre(), "Error: El nuevo nombre no se actualizó correctamente");
            $this->assertEquals('NUEVO REPRESENTANTE', $emisor->getRepresentanteRazonSocial(), "Error: La nueva razón social no se actualizó correctamente");
            $this->assertEquals('X1234567Y', $emisor->getRepresentanteNif(), "Error: El nuevo NIF del representante no se actualizó correctamente");
            $this->assertFalse($emisor->getEnviarAeat(), "Error: enviarAeat debería ser false");
            $this->assertTrue($emisor->getEnviarAeatPaused(), "Error: enviarAeatPaused debería ser true");
            $this->assertEquals(456, $emisor->getWebhookID(), "Error: El nuevo webhookID no se actualizó correctamente");

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function testGetArrayData()
    {
        try {
            // Datos de entrada simulados
            $data = [
                'nif' => 'S2819426D',
                'nombre' => 'EMPRESA TEST',
                'representanteRazonSocial' => 'REPRESENTANTE TEST',
                'representanteNif' => 'B12345678',
                'enviarAeat' => true,
                'enviarAeatPaused' => false,
                'webhookID' => 1
            ];

            // Crear una nueva instancia de Emisor
            $emisor = new Emisor($data);

            // Obtener el array de datos
            $arrayData = $emisor->getArrayData();

            // Verificar que el array contiene las claves y valores correctos
            $this->assertEquals('S2819426D', $arrayData['nif'], "Error: NIF incorrecto en el array");
            $this->assertEquals('EMPRESA TEST', $arrayData['nombre'], "Error: Nombre incorrecto en el array");
            $this->assertEquals('REPRESENTANTE TEST', $arrayData['representante_razon_social'], "Error: Razón social incorrecta en el array");
            $this->assertEquals('B12345678', $arrayData['representante_nif'], "Error: NIF del representante incorrecto en el array");
            $this->assertTrue($arrayData['enviar_aeat'], "Error: enviar_aeat debería ser true en el array");
            $this->assertFalse($arrayData['enviar_aeat_paused'], "Error: enviar_aeat_paused debería ser false en el array");
            $this->assertEquals(1, $arrayData['default_webhook_id'], "Error: webhook_id incorrecto en el array");

        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function testEmpty(): void
    {
        try {
            // Crear una instancia vacía de Emisor
            $emisor = Emisor::empty();

            // Verificar que los valores están vacíos
            $this->assertSame('', $emisor->getNif(), "Error: El NIF no está vacío");
            $this->assertSame('', $emisor->getNombre(), "Error: El nombre no está vacío");
            $this->assertSame('', $emisor->getRepresentanteRazonSocial(), "Error: La razón social no está vacía");
            $this->assertSame('', $emisor->getRepresentanteNif(), "Error: El NIF del representante no está vacío");
            $this->assertSame(0, $emisor->getWebhookID(), "Error: El webhookID no es 0");
            $this->assertFalse($emisor->getEnviarAeat(), "Error: enviarAeat no es false");
            $this->assertFalse($emisor->getEnviarAeatPaused(), "Error: enviarAeatPaused no es false");

            // Obtener datos del array y verificar que están vacíos
            $arrayData = $emisor->getArrayData();
            $this->assertFalse($arrayData['enviar_aeat'] ?? false, "Error: enviar_aeat no es false");
            $this->assertFalse($arrayData['enviar_aeat_paused'] ?? false, "Error: enviar_aeat_paused no es false");
            $this->assertEmpty($arrayData['nif'] ?? '', "Error: El NIF no está vacío");
            $this->assertEmpty($arrayData['nombre'] ?? '', "Error: El nombre no está vacío"); 
            $this->assertEmpty($arrayData['representante_razon_social'] ?? '', "Error: La razón social no está vacía");
            $this->assertEmpty($arrayData['representante_nif'] ?? '', "Error: El NIF del representante no está vacío");
            $this->assertEmpty($arrayData['default_webhook_id'] ?? '', "Error: El webhook_id no está vacío");

        } catch (Exception $e) {
            throw $e;
        }
    }
}