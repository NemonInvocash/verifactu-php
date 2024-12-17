<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\ClienteVerifactu;
use tests\LogInCredentials;

class ListarRegistrosAltaTest extends LogInCredentials
{
    private ClienteVerifactu $cliente;

    public function setUp(): void
    {
        // Inicializa el cliente con las credenciales
        $this->cliente = new ClienteVerifactu(parent::email, parent::password);
    }

    public function testListarTodosLosRegistrosAlta(): void
    {
        try {
            // Listar todos los registros
            $resultadoTodos = $this->cliente->listarRegistrosAlta(null);
            
            $this->assertIsArray($resultadoTodos, "Error: El resultado no es un array"); // Verifica que el resultado sea un array
            $this->assertArrayHasKey('success', $resultadoTodos, "Error: No existe la clave 'success' en la respuesta"); // Verifica la clave 'success'
            $this->assertTrue($resultadoTodos['success'], "Error: La operación no fue exitosa"); // Verifica que la operación fue exitosa
        } catch (Exception $e) {
            // Manejar excepción al listar registros
            throw new Exception("Error al listar registros de alta: " . $e->getMessage());
        }
    }

    public function testListarRegistroEspecifico(): void
    {
        try {
            // Listar un registro específico
            $resultadoUnico = $this->cliente->listarRegistrosAlta(2);
            
            $this->assertIsArray($resultadoUnico, "Error: El resultado no es un array"); // Verifica que el resultado sea un array  
            $this->assertArrayHasKey('success', $resultadoUnico, "Error: No existe la clave 'success' en la respuesta"); // Verifica la clave 'success'
            $this->assertTrue($resultadoUnico['success'], "Error: La operación no fue exitosa"); // Verifica que la operación fue exitosa
        } catch (Exception $e) {
            // Manejar excepción al listar registro específico
            throw new Exception("Error al listar un registro específico: " . $e->getMessage());
        }
    }

    public function testListarRegistrosAltaConError(): void
    {
        // Esperar una excepción de tipo TypeError
        $this->expectException(TypeError::class);
        
        // Probar con un string en lugar de un int
        $this->cliente->listarRegistrosAlta("string");
    }
}
