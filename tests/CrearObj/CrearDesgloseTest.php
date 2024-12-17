<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\ClienteVerifactu;
use tests\LogInCredentials;

class CrearDesgloseTest extends LogInCredentials
{
    private ClienteVerifactu $cliente;

    // Configuración inicial antes de cada prueba
    public function setUp(): void
    {
        $this->cliente = new ClienteVerifactu(parent::email, parent::password);
    }

    // Prueba para crear un desglose
    public function testCrearDesglose(): void
    {
        try {
            // Datos para el desglose
            $dataDesglose = [
                'impuesto' => 1,
                'claveRegimen' => 1,
                'calificacionOperacion' => 1,
                'operacionExenta' => 0,
                'tipoImpositivo' => 21.0,
                'baseImponibleOImporteNoSujeto' => 100.0,
                'baseImponibleACoste' => 100.0,
                'cuotaRepercutida' => 21.0,
                'tipoRecargoEquivalencia' => 0.0,
                'cuotaRecargoEquivalencia' => 0.0
            ];

            // Crear el desglose
            $desglose = $this->cliente->nuevoDesglose($dataDesglose);

            // Array esperado para la comparación
            $expectedArray = [
                'Impuesto' => 1,
                'ClaveRegimen' => 1,
                'CalificacionOperacion' => 1,
                //'OperacionExenta' => 0,
                'TipoImpositivo' => 21.0,
                'BaseImponibleOImporteNoSujeto' => 100.0,
                'BaseImponibleACoste' => 100.0,
                'CuotaRepercutida' => 21.0,
                //'TipoRecargoEquivalencia' => 0.0,
                //'CuotaRecargoEquivalencia' => 0.0
            ];

            // Verificar que el array generado coincide con el esperado
            $this->assertEquals($expectedArray, $desglose->getArrayData(), "El array generado no coincide con el esperado.");

        } catch (Exception $e) {
            // Manejo de errores en la creación del desglose
            $this->fail("Error al crear desglose: " . $e->getMessage());
        }
    }
}