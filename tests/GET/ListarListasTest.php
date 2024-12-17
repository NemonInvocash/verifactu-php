<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\ClienteVerifactu;
use tests\LogInCredentials;

class ListarListasTest extends LogInCredentials
{
    private ClienteVerifactu $cliente; // Cliente para interactuar con la API

    public function setUp(): void
    {
        // Inicializa el cliente con las credenciales de inicio de sesión
        $this->cliente = new ClienteVerifactu(parent::email, parent::password);
    }

    public function testListarListasVacias(): void
    {
        try {
            // Intenta listar listas vacías
            $resultadoLista = $this->cliente->listarListas('', '');
            $this->assertIsArray($resultadoLista, "Error: El resultado no es un array"); // Verifica que el resultado sea un array
            $this->assertArrayHasKey('success', $resultadoLista, "Error: No existe la clave 'success' en la respuesta"); // Verifica la clave 'success'
            $this->assertTrue($resultadoLista['success'], "Error: La operación no fue exitosa"); // Verifica que la operación fue exitosa
        } catch (Exception $e) {
            // Captura cualquier excepción y lanza un nuevo error con un mensaje específico
            throw new Exception("Error al listar listas vacías: " . $e->getMessage());
        }
    }

    public function testListarListaEspecifica(): void
    {
        try {
            // Intenta listar una lista específica
            $resultadoLista = $this->cliente->listarListas('l1', '');
            $this->assertIsArray($resultadoLista, "Error: El resultado no es un array"); // Verifica que el resultado sea un array
            $this->assertArrayHasKey('success', $resultadoLista, "Error: No existe la clave 'success' en la respuesta"); // Verifica la clave 'success'
            $this->assertTrue($resultadoLista['success'], "Error: La operación no fue exitosa"); // Verifica que la operación fue exitosa
        } catch (Exception $e) {
            // Captura cualquier excepción y lanza un nuevo error con un mensaje específico
            throw new Exception("Error al listar una lista específica: " . $e->getMessage());
        }
    }

    public function testListarValorEspecifico(): void
    {
        try {
            // Probar listar un valor específico de una lista
            $resultadoValor = $this->cliente->listarListas('l1', '02');

            $this->assertIsArray($resultadoValor, "Error: El resultado no es un array"); // Verifica que el resultado sea un array
            $this->assertArrayHasKey('success', $resultadoValor, "Error: No existe la clave 'success' en la respuesta"); // Verifica la clave 'success'
            $this->assertTrue($resultadoValor['success'], "Error: La operación no fue exitosa"); // Verifica que la operación fue exitosa
        } catch (Exception $e) {
            // Captura cualquier excepción y lanza un nuevo error con un mensaje específico
            throw new Exception("Error al listar un valor específico: " . $e->getMessage());
        }
    }

    public function testListarListasConError(): void
    {
        // Espera una excepción de tipo TypeError
        $this->expectException(TypeError::class);
        // Intenta listar listas pasando un int en lugar de un string
        $this->cliente->listarListas(123, ''); 
    }
}?>
