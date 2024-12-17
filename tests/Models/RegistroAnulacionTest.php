<?php

use PHPUnit\Framework\TestCase;
use verifactuPHP\Models\RegistroAnulacion;

class RegistroAnulacionTest extends TestCase
{
    public function testRegistroAnulacionInitialization(): void
    {
        // Datos de entrada para la inicialización
        $data = [
            'IDEmisorFactura' => '12345678A',
            'numSerieFactura' => 'ABC123',
            'fechaExpedicionFactura' => '2023-10-01',
            'refExterna' => 'REF123',
            'sinRegistroPrevio' => 1,
            'rechazoPrevioA' => 0,
            'generadoPor' => 1
        ];

        // Crear una nueva instancia de RegistroAnulacion
        $registroAnulacion = new RegistroAnulacion($data);

        // Verificaciones de los valores iniciales
        $this->assertSame('12345678A', $registroAnulacion->getIDEmisorFactura(), "Error: El ID del emisor de la factura no coincide");
        $this->assertSame('ABC123', $registroAnulacion->getNumSerieFactura(), "Error: El número de serie de la factura no coincide");
        $this->assertSame('2023-10-01', $registroAnulacion->getFechaExpedicionFactura(), "Error: La fecha de expedición de la factura no coincide");
        $this->assertSame('REF123', $registroAnulacion->getRefExterna(), "Error: La referencia externa no coincide");
        $this->assertSame(1, $registroAnulacion->getSinRegistroPrevio(), "Error: El valor de sinRegistroPrevio no coincide");
        $this->assertSame(0, $registroAnulacion->getRechazoPrevioA(), "Error: El valor de rechazoPrevioA no coincide");
        $this->assertSame(1, $registroAnulacion->getGeneradoPor(), "Error: El valor de generadoPor no coincide");
    }

    public function testSettersAndGetters(): void
    {
        // Datos de entrada para la prueba de setters y getters
        $data = [
            'IDEmisorFactura' => '12345678A',
            'numSerieFactura' => 'ABC123',
            'fechaExpedicionFactura' => '2023-10-01',
            'refExterna' => 'REF123',
            'sinRegistroPrevio' => 1,
            'rechazoPrevioA' => 0,
            'generadoPor' => 1
        ];
        
        // Crear una nueva instancia de RegistroAnulacion
        $registroAnulacion = new RegistroAnulacion($data);

        // Actualizar valores usando setters
        $registroAnulacion->setIDEmisorFactura('87654321B');
        $registroAnulacion->setNumSerieFactura('XYZ789');
        $registroAnulacion->setFechaExpedicionFactura('2023-10-02');
        $registroAnulacion->setRefExterna('REF456');
        $registroAnulacion->setSinRegistroPrevio(0);
        $registroAnulacion->setRechazoPrevioA(1);
        $registroAnulacion->setGeneradoPor(2);

        // Verificaciones de los nuevos valores
        $this->assertSame('87654321B', $registroAnulacion->getIDEmisorFactura(), "Error: El nuevo ID del emisor de la factura no se actualizó correctamente");
        $this->assertSame('XYZ789', $registroAnulacion->getNumSerieFactura(), "Error: El nuevo número de serie de la factura no se actualizó correctamente");
        $this->assertSame('2023-10-02', $registroAnulacion->getFechaExpedicionFactura(), "Error: La nueva fecha de expedición de la factura no se actualizó correctamente");
        $this->assertSame('REF456', $registroAnulacion->getRefExterna(), "Error: La nueva referencia externa no se actualizó correctamente");
        $this->assertSame(0, $registroAnulacion->getSinRegistroPrevio(), "Error: El nuevo valor de sinRegistroPrevio no se actualizó correctamente");
        $this->assertSame(1, $registroAnulacion->getRechazoPrevioA(), "Error: El nuevo valor de rechazoPrevioA no se actualizó correctamente");
        $this->assertSame(2, $registroAnulacion->getGeneradoPor(), "Error: El nuevo valor de generadoPor no se actualizó correctamente");
    }

    public function testGetArrayData(): void
    {
        // Datos de entrada para la prueba de getArrayData
        $data = [
            'IDEmisorFactura' => '12345678A',
            'numSerieFactura' => 'ABC123',
            'fechaExpedicionFactura' => '2023-10-01',
            'refExterna' => 'REF123',
            'sinRegistroPrevio' => 1,
            'rechazoPrevioA' => 0,
            'generadoPor' => 1
        ];

        // Crear una nueva instancia de RegistroAnulacion
        $registroAnulacion = new RegistroAnulacion($data);
        $arrayData = $registroAnulacion->getArrayData();

        // Array esperado para la comparación
        $expectedArray = [
            'IDEmisorFactura' => '12345678A',
            'NumSerieFactura' => 'ABC123',
            'FechaExpedicionFactura' => '2023-10-01',
            'RefExterna' => 'REF123',
            'SinRegistroPrevio' => 1,
            //'RechazoPrevioA' => 0,
            'GeneradoPor' => 1,
        ];
        
        // Verificación de que los datos del array coinciden con los esperados
        $this->assertSame($expectedArray, $arrayData, "Error: Los datos del array no coinciden con los esperados");
    }

    public function testEmpty(): void
    {
        // Crear una nueva instancia vacía de RegistroAnulacion
        $registroAnulacion = RegistroAnulacion::empty();

        // Verificaciones de que los valores están vacíos
        $this->assertSame('', $registroAnulacion->getIDEmisorFactura(), "Error: El ID del emisor de la factura no está vacío");
        $this->assertSame('', $registroAnulacion->getNumSerieFactura(), "Error: El número de serie de la factura no está vacío");
        $this->assertSame('', $registroAnulacion->getFechaExpedicionFactura(), "Error: La fecha de expedición de la factura no está vacía");
        $this->assertSame('', $registroAnulacion->getRefExterna(), "Error: La referencia externa no está vacía");
        $this->assertSame(0, $registroAnulacion->getSinRegistroPrevio(), "Error: El valor de sinRegistroPrevio no es 0");
        $this->assertSame(0, $registroAnulacion->getRechazoPrevioA(), "Error: El valor de rechazoPrevioA no es 0");
        $this->assertSame(0, $registroAnulacion->getGeneradoPor(), "Error: El valor de generadoPor no es 0");

        // Obtener datos del array y verificar que están vacíos
        $arrayData = $registroAnulacion->getArrayData();
        $this->assertEmpty($arrayData['IDEmisorFactura'] ?? '', "Error: El IDEmisorFactuara no está vacío");
        $this->assertEmpty($arrayData['numSerieFactura'] ?? '', "Error: El numSerieFactura no está vacío");
        $this->assertEmpty($arrayData['fechaExpedicionFactura'] ?? '', "Error: La fechaExpedicionFactura no está vacía");
        $this->assertEmpty($arrayData['refExterna'] ?? '', "Error: La refExterna no está vacía");
        $this->assertEmpty($arrayData['sinRegistroPrevio'] ?? '', "Error: El sinRegistroPrevio no está vacío");
        $this->assertEmpty($arrayData['rechazoPrevioA'] ?? '', "Error: El rechazoPrevioA no está vacío");
        $this->assertEmpty($arrayData['generadoPor'] ?? '', "Error: El generadoPor no está vacío");
    }
}