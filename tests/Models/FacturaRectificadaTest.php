<?php

use PHPUnit\Framework\TestCase;
use verifactuPHP\Models\FacturaRectificada;

class FacturaRectificadaTest extends TestCase
{
    public function testFacturaRectificadaInitialization()
    {
        // Datos de entrada simulados
        $data = [
            'IDEmisorFactura' => '123456',
            'numSerieFactura' => 'ABC123',
            'fechaExpedicionFactura' => '2023-10-01',
        ];

        try {
            // Crear una nueva instancia de FacturaRectificada
            $factura = new FacturaRectificada($data);

            // Asegurarse de que los valores se asignaron correctamente
            $this->assertEquals('123456', $factura->getIDEmisorFactura(), "Error: El IDEmisorFactura no coincide");
            $this->assertEquals('ABC123', $factura->getNumSerieFactura(), "Error: El numSerieFactura no coincide");
            $this->assertEquals('2023-10-01', $factura->getFechaExpedicionFactura(), "Error: La fecha de expedición no coincide");

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function testSettersAndGetters()
    {
        try {
            // Datos de entrada para inicializar la FacturaRectificada
            $data = [
                'IDEmisorFactura' => '123456',
                'numSerieFactura' => 'ABC123',
                'fechaExpedicionFactura' => '2023-10-01',
            ];

            // Crear una nueva instancia de FacturaRectificada
            $factura = new FacturaRectificada($data);

            // Cambiar los valores utilizando los setters
            $factura->setIDEmisorFactura('654321');
            $factura->setNumSerieFactura('XYZ789');
            $factura->setFechaExpedicionFactura('2023-10-02');

            // Verificar que los valores han sido actualizados correctamente
            $this->assertEquals('654321', $factura->getIDEmisorFactura(), "Error: El nuevo IDEmisorFactura no se actualizó correctamente");
            $this->assertEquals('XYZ789', $factura->getNumSerieFactura(), "Error: El nuevo numSerieFactura no se actualizó correctamente");
            $this->assertEquals('2023-10-02', $factura->getFechaExpedicionFactura(), "Error: La nueva fecha de expedición no se actualizó correctamente");

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function testGetArrayData()
    {
        try {
            // Datos de entrada simulados
            $data = [
                'IDEmisorFactura' => '123456',
                'numSerieFactura' => 'ABC123',
                'fechaExpedicionFactura' => '2023-10-01',
            ];

            // Crear una nueva instancia de FacturaRectificada
            $factura = new FacturaRectificada($data);

            // Obtener el array de datos
            $arrayData = $factura->getArrayData();

            // Verificar que el array contiene las claves y valores correctos
            $this->assertEquals('123456', $arrayData['IDEmisorFactura'], "Error: IDEmisorFactura incorrecto en el array");
            $this->assertEquals('ABC123', $arrayData['NumSerieFactura'], "Error: numSerieFactura incorrecto en el array");
            $this->assertEquals('2023-10-01', $arrayData['FechaExpedicionFactura'], "Error: FechaExpedicionFactura incorrecta en el array");

        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function testEmpty(): void
    {
        try {
            // Crear una instancia vacía de FacturaRectificada
            $factura = FacturaRectificada::empty();

            // Verificar que los valores están vacíos
            $this->assertSame('', $factura->getIDEmisorFactura(), "Error: El IDEmisorFactura no está vacío");
            $this->assertSame('', $factura->getNumSerieFactura(), "Error: El numSerieFactura no está vacío");
            $this->assertSame('', $factura->getFechaExpedicionFactura(), "Error: La fecha de expedición no está vacía");

            // Obtener datos del array y verificar que están vacíos
            $arrayData = $factura->getArrayData();
            $this->assertEmpty($arrayData['IDEmisorFactura'] ?? '', "Error: El IDEmisorFactura no está vacío");
            $this->assertEmpty($arrayData['NumSerieFactura'] ?? '', "Error: El numSerieFactura no está vacío");
            $this->assertEmpty($arrayData['FechaExpedicionFactura'] ?? '', "Error: La fecha de expedición no está vacía");

        } catch (Exception $e) {
            throw $e;
        }
    }
}
?> 