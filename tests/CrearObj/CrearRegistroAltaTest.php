<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\ClienteVerifactu;
use tests\LogInCredentials;
use verifactuPHP\Models\Destinatario;
use verifactuPHP\Models\Desglose;
use verifactuPHP\Models\Tercero;

class CrearRegistroAltaTest extends LogInCredentials
{
    private ClienteVerifactu $cliente;

    // Configuración inicial antes de cada prueba
    public function setUp(): void
    {
        $this->cliente = new ClienteVerifactu(parent::email, parent::password);
    }

    // Prueba para crear un registro de alta
    public function testCrearRegistroAlta(): void
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
            $emisor = $this->cliente->nuevoEmisor($dataEmisor);
        
            // Inicialización de objetos vacíos
            $destinatario = Destinatario::empty();
            $desglose = Desglose::empty();
            $tercero = Tercero::empty();
        
            // Datos para el registro de alta
            $dataRegistroAlta = [
                'numSerieFactura' => "AX/202412-009", 
                'fechaExpedicionFactura' => "2024-11-27", 
                'refExterna' => "Test Ref Externa", 
                'nombreRazonEmisor' => "", 
                'subsanacion' => 0, 
                'rechazoPrevio' => 0, 
                'tipoFactura' => 1, 
                'tipoRectificativa' => 0, 
                'baseRectificada' => 0.0, 
                'cuotaRectificada' => 0.0, 
                'cuotaRecargoRectificado' => 0.0, 
                'fechaOperacion' => "", 
                'descripcionOperacion' => "", 
                'facturaSimplificadaArt7273' => 0,
                'facturaSinIdentifDestinatarioArt61d' => 0,
                'macrodato' => 0,
                'emitidaPorTercODestinatario' => 0,
                'cupon' => 0,
                'cuotaTotal' => 21.0,
                'importeTotal' => 121.0,
            ];
        
            // Crear el registro de alta
            $registroAlta = $this->cliente->nuevoRegistroAlta($emisor, $destinatario, $desglose, $tercero, $dataRegistroAlta, 0);
        
            // Array esperado para la comparación
            $expectedArray = [
                'IDEmisorFactura' => "S2819426D",
                'NumSerieFactura' => "AX/202412-009",
                'FechaExpedicionFactura' => "2024-11-27",
                'RefExterna' => "Test Ref Externa",
                //'NombreRazonEmisor' => "",
                //'Subsanacion' => 0,
                //'RechazoPrevio' => 0,
                'TipoFactura' => 1,
                //'TipoRectificativa' => 0,
                //'BaseRectificada' => 0.0,
                //'CuotaRectificada' => 0.0,
                //'CuotaRecargoRectificado' => 0.0,
                //'FechaOperacion' => "",
                //'DescripcionOperacion' => "",
                //'FacturaSimplificadaArt7273' => 0,
                //'FacturaSinIdentifDestinatarioArt61d' => 0,
                //'Macrodato' => 0,
                //'EmitidaPorTercODestinatario' => 0,
                //'Cupon' => 0,
                'CuotaTotal' => 21.0,
                'ImporteTotal' => 121.0,
                'webhook_id' => 10,
            ];
        
            // Verificar que el array generado coincide con el esperado
            $this->assertEquals($expectedArray, $registroAlta->getArrayData(), "El array generado no coincide con el esperado.");
        
        } catch (Exception $e) {
            // Manejo de errores en la creación del registro de alta
            $this->fail("Error al crear registro alta: " . $e->getMessage());
        }
    }
}