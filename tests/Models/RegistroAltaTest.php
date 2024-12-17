<?php

use PHPUnit\Framework\TestCase;
use verifactuPHP\Models\RegistroAlta;
use verifactuPHP\Models\Emisor;
use verifactuPHP\Models\Destinatario;
use verifactuPHP\Models\Desglose;
use verifactuPHP\Models\Tercero;
use verifactuPHP\Models\FacturaRectificada;
use verifactuPHP\Models\FacturaSustituida;

class RegistroAltaTest extends TestCase
{
    public function testRegistroAltaInitialization()
    {
        try {
            // Simulación de datos de entrada para el emisor
            $emisorData = [
                'nif' => 'B12345678',
                'nombre' => 'Emisor Test',
                'webhookID' => 1,
                'enviarAeat' => true,
                'enviarAeatPaused' => false
            ];

            // Simulación de datos de entrada para el destinatario
            $destinatarioData = [
                'nombreRazon' => 'Destinatario Test',
                'nif' => 'A87654321',
                'otroCodPais' => 'ES',
                'otroIDType' => 1,
                'otroID' => ''
            ];

            // Simulación de datos de entrada para el desglose
            $desgloseData = [
                'impuesto' => 1,
                'claveRegimen' => 1,
                'calificacionOperacion' => 1,
                'operacionExenta' => 0,
                'tipoImpositivo' => 21,
                'baseImponibleOImporteNoSujeto' => 100,
                'baseImponibleACoste' => 100,
                'cuotaRepercutida' => 21,
                'tipoRecargoEquivalencia' => 0,
                'cuotaRecargoEquivalencia' => 0
            ];

            // Simulación de datos de entrada para el tercero
            $terceroData = [
                'tercNombreRazon' => 'Tercero Test',
                'tercNIF' => 'C11111111',
                'tercOtroCodPais' => 'ES',
                'tercOtroIDType' => 1,
                'tercOtroID' => ''
            ];

            // Simulación de datos de entrada para el registro de alta
            $registroAltaData = [
                'numSerieFactura' => 'FACT001',
                'fechaExpedicionFactura' => '2024-01-01',
                'refExterna' => 'REF001',
                'tipoFactura' => 1,
                'cuotaTotal' => 21.0,
                'importeTotal' => 121.0
            ];

            // Crear instancias de los modelos
            $emisor = new Emisor($emisorData);
            $destinatario = new Destinatario($destinatarioData);
            $desglose = new Desglose($desgloseData);
            $tercero = new Tercero($terceroData);

            // Crear una nueva instancia de RegistroAlta
            $registroAlta = new RegistroAlta([
                'emisor' => $emisor,
                'destinatario' => $destinatario,
                'desglose' => $desglose,
                'tercero' => $tercero,
            ], $registroAltaData, 1);

            // Verificar valores iniciales
            $this->assertEquals('B12345678', $registroAlta->getIDEmisorFactuara(), "Error: El ID del emisor no coincide");
            $this->assertEquals('FACT001', $registroAlta->getNumSerieFactura(), "Error: El número de serie de factura no coincide");
            $this->assertEquals('2024-01-01', $registroAlta->getFechaExpedicionFactura(), "Error: La fecha de expedición no coincide");
            $this->assertEquals('REF001', $registroAlta->getRefExterna(), "Error: La referencia externa no coincide");
            $this->assertEquals(1, $registroAlta->getTipoFactura(), "Error: El tipo de factura no coincide");
            $this->assertEquals(21.0, $registroAlta->getCuotaTotal(), "Error: La cuota total no coincide");
            $this->assertEquals(121.0, $registroAlta->getImporteTotal(), "Error: El importe total no coincide");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function testSettersAndGetters()
    {
        try {
            // Crear instancias necesarias con datos mínimos
            $emisor = new Emisor(['nif' => 'TEST123', 'nombre' => 'Test']);
            $destinatario = new Destinatario([]);
            $desglose = new Desglose([]);
            $tercero = new Tercero([]);
            
            // Crear registroAlta con datos mínimos
            $registroAlta = new RegistroAlta([
                'emisor' => $emisor,
                'destinatario' => $destinatario,
                'desglose' => $desglose,
                'tercero' => $tercero,
            ], [], 1);

            // Probar setters
            $registroAlta->setNumSerieFactura('NEWFACT002');
            $registroAlta->setRefExterna('NEWREF002');
            $registroAlta->setTipoFactura(2);
            $registroAlta->setCuotaTotal(42.0);
            $registroAlta->setImporteTotal(242.0);

            // Verificar getters
            $this->assertEquals('NEWFACT002', $registroAlta->getNumSerieFactura(), "Error: El número de serie de factura no se actualizó correctamente");
            $this->assertEquals('NEWREF002', $registroAlta->getRefExterna(), "Error: La referencia externa no se actualizó correctamente");
            $this->assertEquals(2, $registroAlta->getTipoFactura(), "Error: El tipo de factura no se actualizó correctamente");
            $this->assertEquals(42.0, $registroAlta->getCuotaTotal(), "Error: La cuota total no se actualizó correctamente");
            $this->assertEquals(242.0, $registroAlta->getImporteTotal(), "Error: El importe total no se actualizó correctamente");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function testGetArrayData()
    {
        try {
            // Crear instancias mínimas necesarias
            $emisor = new Emisor(['nif' => 'TEST123', 'nombre' => 'Test']);
            $destinatario = new Destinatario(['nombreRazon' => 'Test Dest', 'nif' => 'DEST123']);
            $desglose = new Desglose(['impuesto' => 1, 'baseImponibleOImporteNoSujeto' => 100]);
            $tercero = new Tercero(['tercNombreRazon' => 'Test Terc', 'tercNIF' => 'TERC123']);

            // Datos para el registro de alta
            $registroAltaData = [
                'numSerieFactura' => 'TEST001',
                'fechaExpedicionFactura' => '2024-01-01',
                'cuotaTotal' => 21.0,
                'importeTotal' => 121.0
            ];

            // Crear una nueva instancia de RegistroAlta
            $registroAlta = new RegistroAlta([
                'emisor' => $emisor,
                'destinatario' => $destinatario,
                'desglose' => $desglose,
                'tercero' => $tercero,
            ], $registroAltaData, 1);

            // Verificar array de datos
            $arrayData = $registroAlta->getArrayData();
            
            // Comprobar que las claves existen en el array
            $this->assertArrayHasKey('NumSerieFactura', $arrayData, "Error: Falta la clave NumSerieFactura en el array");
            $this->assertArrayHasKey('FechaExpedicionFactura', $arrayData, "Error: Falta la clave FechaExpedicionFactura en el array");
            $this->assertArrayHasKey('CuotaTotal', $arrayData, "Error: Falta la clave CuotaTotal en el array");
            $this->assertArrayHasKey('ImporteTotal', $arrayData, "Error: Falta la clave ImporteTotal en el array");
            $this->assertArrayHasKey('Destinatarios', $arrayData, "Error: Falta la clave Destinatarios en el array");
            $this->assertArrayHasKey('Desglose', $arrayData, "Error: Falta la clave Desglose en el array");
            $this->assertArrayHasKey('Tercero', $arrayData, "Error: Falta la clave Tercero en el array");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function testEmpty(): void
    {
        try {
            // Crear una nueva instancia vacía de RegistroAlta
            $registroAlta = RegistroAlta::empty();
            $this->assertSame('', $registroAlta->getNumSerieFactura(), "Error: El número de serie de factura no está vacío");
            $this->assertSame('', $registroAlta->getFechaExpedicionFactura(), "Error: La fecha de expedición no está vacía");
            $this->assertSame(0.0, $registroAlta->getCuotaTotal(), "Error: La cuota total no está vacía");
            $this->assertSame(0.0, $registroAlta->getImporteTotal(), "Error: El importe total no está vacío");

            // Verificar que el array de datos está vacío
            $arrayData = $registroAlta->getArrayData();
            $this->assertEmpty($arrayData, "Error: El array de datos no está vacío");
        } catch (Exception $e) {
            throw $e;
        }
    }       
}