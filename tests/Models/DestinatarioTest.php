<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\Models\Destinatario;

class DestinatarioTest extends TestCase
{
    public function testDestinatarioInitialization(): void
    {
        try {
            // Datos de entrada para la inicialización del destinatario
            $data = [
                'nombreRazon' => "NOMBRE EJEMPLO",
                'nif' => "39707287H", 
                'otroCodPais' => "ES",
                'otroIDType' => 1,
                'otroID' => ""
            ];

            // Crear una nueva instancia de Destinatario
            $dest = new Destinatario($data);

            // Verificaciones de los valores iniciales
            $this->assertSame("NOMBRE EJEMPLO", $dest->getNombreRazon(), "Error: El nombre/razón social no coincide");
            $this->assertSame("39707287H", $dest->getNIF(), "Error: El NIF no coincide");
            $this->assertSame("ES", $dest->getOtroCodPais(), "Error: El código de país no coincide");
            $this->assertSame(1, $dest->getOtroIDType(), "Error: El tipo de ID no coincide");
            $this->assertSame("", $dest->getOtroID(), "Error: El otro ID no coincide");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function testSettersAndGetters(): void
    {
        try {
            // Datos de entrada para la prueba de setters y getters
            $data = [
                'nombreRazon' => "NOMBRE EJEMPLO",
                'nif' => "39707287H",
                'otroCodPais' => "ES",
                'otroIDType' => 1,
                'otroID' => ""
            ];
            $dest = new Destinatario($data);

            // Actualizar valores usando setters
            $dest->setNombreRazon("MARIA LOPEZ");
            $dest->setNIF("12345678A");
            $dest->setOtroCodPais("FR");
            $dest->setOtroIDType(2);
            $dest->setOtroID("ID123");

            // Verificaciones de los nuevos valores
            $this->assertSame("MARIA LOPEZ", $dest->getNombreRazon(), "Error: El nuevo nombre/razón social no se actualizó correctamente");
            $this->assertSame("12345678A", $dest->getNIF(), "Error: El nuevo NIF no se actualizó correctamente");
            $this->assertSame("FR", $dest->getOtroCodPais(), "Error: El nuevo código de país no se actualizó correctamente");
            $this->assertSame(2, $dest->getOtroIDType(), "Error: El nuevo tipo de ID no se actualizó correctamente");
            $this->assertSame("ID123", $dest->getOtroID(), "Error: El nuevo otro ID no se actualizó correctamente");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function testGetArrayData(): void
    {
        try {
            // Datos de entrada para la prueba de getArrayData
            $data = [
                'nombreRazon' => "NOMBRE EJEMPLO",
                'nif' => "39707287H",
                'otroCodPais' => "ES",
                'otroIDType' => 1,
                'otroID' => "TEST123"
            ];
            $dest = new Destinatario($data);

            // Obtener el array de datos
            $arrayData = $dest->getArrayData();

            // Verificaciones de los datos del array
            $this->assertSame("NOMBRE EJEMPLO", $arrayData['NombreRazon'], "Error: Nombre/razón social incorrecto en el array");
            $this->assertSame("39707287H", $arrayData['NIF'], "Error: NIF incorrecto en el array");
            $this->assertSame("ES", $arrayData['OtroCodPais'], "Error: Código de país incorrecto en el array");
            $this->assertSame(1, $arrayData['OtroIDType'], "Error: Tipo de ID incorrecto en el array");
            $this->assertSame("TEST123", $arrayData['OtroID'], "Error: Otro ID incorrecto en el array");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function testEmpty(): void
    {
        try {
            // Crear una nueva instancia vacía de Destinatario
            $dest = Destinatario::empty();

            // Verificaciones de que los valores están vacíos
            $this->assertSame("", $dest->getNombreRazon(), "Error: El nombre/razón social no está vacío");
            $this->assertSame("", $dest->getNif(), "Error: El NIF no está vacío");
            $this->assertSame("", $dest->getOtroCodPais(), "Error: El código de país no está vacío");
            $this->assertSame(0, $dest->getOtroIDType(), "Error: El tipo de ID no es 0");
            $this->assertSame("", $dest->getOtroID(), "Error: El otro ID no está vacío");

            // Obtener datos del array y verificar que están vacíos
            $arrayData = $dest->getArrayData();
            $this->assertEmpty($arrayData, "Error: El array de datos no está vacío");
        } catch (Exception $e) {
            throw $e;
        }
    }
}