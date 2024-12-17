<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\Models\Tercero;

class TerceroTest extends TestCase
{
    private Tercero $tercero;
    private array $dataPrueba;

    protected function setUp(): void
    {
        // Inicialización de datos de prueba
        $this->dataPrueba = [
            'tercNombreRazon' => 'Empresa Ejemplo S.L.',
            'tercNIF' => 'B12345678',
            'tercOtroCodPais' => 'ES',
            'tercOtroIDType' => 1,
            'tercOtroID' => 'ID123456'
        ];
        $this->tercero = new Tercero($this->dataPrueba);
    }

    public function testConstructorYGetters(): void
    {
        // Verificación de los getters
        $this->assertEquals($this->dataPrueba['tercNombreRazon'], $this->tercero->getTercNombreRazon(), "Error: El nombre/razón social no coincide");  
        $this->assertEquals($this->dataPrueba['tercNIF'], $this->tercero->getTercNIF(), "Error: El NIF no coincide");   
        $this->assertEquals($this->dataPrueba['tercOtroCodPais'], $this->tercero->getTercOtroCodPais(), "Error: El código de país no coincide"); 
        $this->assertEquals($this->dataPrueba['tercOtroIDType'], $this->tercero->getTercOtroIDType(), "Error: El tipo de ID no coincide");
        $this->assertEquals($this->dataPrueba['tercOtroID'], $this->tercero->getTercOtroID(), "Error: El otro ID no coincide");
    }

    public function testSetters(): void
    {
        // Prueba de los setters
        $this->tercero->setTercNombreRazon('Nueva Empresa S.A.');
        $this->tercero->setTercNIF('A87654321');
        $this->tercero->setTercOtroCodPais('FR');
        $this->tercero->setTercOtroIDType(2);
        $this->tercero->setTercOtroID('NEWID789');

        $this->assertEquals('Nueva Empresa S.A.', $this->tercero->getTercNombreRazon(), "Error: El nombre/razón social no se actualizó correctamente");
        $this->assertEquals('A87654321', $this->tercero->getTercNIF(), "Error: El NIF no se actualizó correctamente");
        $this->assertEquals('FR', $this->tercero->getTercOtroCodPais(), "Error: El código de país no se actualizó correctamente");
        $this->assertEquals(2, $this->tercero->getTercOtroIDType(), "Error: El tipo de ID no se actualizó correctamente");
        $this->assertEquals('NEWID789', $this->tercero->getTercOtroID(), "Error: El otro ID no se actualizó correctamente");
    }

    public function testConstructorConDatosVacios(): void
    {
        // Prueba del constructor con datos vacíos
        $terceroVacio = new Tercero([]);
        
        $this->assertEquals('', $terceroVacio->getTercNombreRazon(), "Error: El nombre/razón social no está vacío");
        $this->assertEquals('', $terceroVacio->getTercNIF(), "Error: El NIF no está vacío");
        $this->assertEquals('', $terceroVacio->getTercOtroCodPais(), "Error: El código de país no está vacío");
        $this->assertEquals(0, $terceroVacio->getTercOtroIDType(), "Error: El tipo de ID no está vacío");
        $this->assertEquals('', $terceroVacio->getTercOtroID(), "Error: El otro ID no está vacío");
    }

    public function testGetArrayData(): void
    {
        // Prueba de obtención de datos en formato array
        $arrayDatos = $this->tercero->getArrayData();
        
        $this->assertIsArray($arrayDatos, "Error: El resultado no es un array");
        $this->assertEquals($this->dataPrueba['tercNombreRazon'], $arrayDatos['TercNombreRazon'], "Error: El nombre/razón social no coincide en el array");
        $this->assertEquals($this->dataPrueba['tercNIF'], $arrayDatos['TercNIF'], "Error: El NIF no coincide en el array");
        $this->assertEquals($this->dataPrueba['tercOtroCodPais'], $arrayDatos['TercOtroCodPais'], "Error: El código de país no coincide en el array");
        $this->assertEquals($this->dataPrueba['tercOtroIDType'], $arrayDatos['TercOtroIDType'], "Error: El tipo de ID no coincide en el array");
        $this->assertEquals($this->dataPrueba['tercOtroID'], $arrayDatos['TercOtroID'], "Error: El otro ID no coincide en el array");
    }

    public function testGetArrayDataConDatosVacios(): void
    {
        // Prueba de obtención de datos en formato array con datos vacíos
        $terceroVacio = new Tercero([]);
        $arrayDatos = $terceroVacio->getArrayData();
        
        $this->assertIsArray($arrayDatos, "Error: El resultado no es un array");
        $this->assertEmpty($arrayDatos, "Error: El array no está vacío");
    }  

    public function testEmpty(): void
    {
        // Prueba de la función empty
        $tercero = Tercero::empty();

        $this->assertSame("", $tercero->getTercNombreRazon(), "Error: El nombre/razón social no está vacío");
        $this->assertSame("", $tercero->getTercNIF(), "Error: El NIF no está vacío");
        $this->assertSame("", $tercero->getTercOtroCodPais(), "Error: El código de país no está vacío");
        $this->assertSame(0, $tercero->getTercOtroIDType(), "Error: El tipo de ID no está vacío");
        $this->assertSame("", $tercero->getTercOtroID(), "Error: El otro ID no está vacío");

        $arrayData = $tercero->getArrayData();
        $this->assertEmpty($arrayData, "Error: El array de datos no está vacío");
    }
}
?>