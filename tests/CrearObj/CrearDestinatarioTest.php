<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\ClienteVerifactu;
use tests\LogInCredentials;

class CrearDestinatarioTest extends LogInCredentials
{
    private ClienteVerifactu $cliente;

    // Configuración inicial antes de cada prueba
    public function setUp(): void
    {
        $this->cliente = new ClienteVerifactu(parent::email, parent::password);
    }

    // Prueba para crear un nuevo destinatario
    public function testCrearDestinatario(): void 
    {
        try {   
            // Datos del destinatario a crear
            $dataDestinatario = [
                'nombreRazon' => "NOMBRE EJEMPLO",
                'nif' => "39707287H",
                'otroCodPais' => "ES",
                'otroIDType' => 1,
                'otroID' => ""
            ];

            // Creación del destinatario
            $destinatario = $this->cliente->nuevoDestinatario($dataDestinatario);

            // Array esperado para la comparación
            $expectedArray = [
                'NombreRazon' => "NOMBRE EJEMPLO",
                'NIF' => "39707287H",
                'OtroCodPais' => "ES",
                'OtroIDType' => 1,
                //'OtroID' => ""
            ];

            // Verificación de que el array generado coincide con el esperado
            $this->assertEquals($expectedArray, $destinatario->getArrayData(), "El array generado no coincide con el esperado.");
    
        } catch (Exception $e) {
            // Manejo de errores en la creación del destinatario
            $this->fail("Error al crear destinatario: " . $e->getMessage());
        }
    }
}