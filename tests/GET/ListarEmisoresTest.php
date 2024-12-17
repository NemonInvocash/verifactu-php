<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\ClienteVerifactu;
use tests\LogInCredentials;

class ListarEmisoresTest extends LogInCredentials
{
    private ClienteVerifactu $cliente;

    public function setUp(): void
    {
        // Inicializa el cliente con las credenciales de inicio de sesión
        $this->cliente = new ClienteVerifactu(parent::email, parent::password);
    }

    public function testListarTodosLosEmisores(): void
    {
        try {
            // Probar listar todos los emisores
            $resultadoTodos = $this->cliente->listarEmisores(null);
            
            $this->assertIsArray($resultadoTodos, "Error: El resultado no es un array"); // Verifica que el resultado sea un array
            $this->assertArrayHasKey('success', $resultadoTodos, "Error: No existe la clave 'success' en la respuesta"); // Verifica la clave 'success'
            $this->assertTrue($resultadoTodos['success'], "Error: La operación no fue exitosa"); // Verifica que la operación fue exitosa
        } catch (Exception $e) {
            throw new Exception("Error al listar todos los emisores: " . $e->getMessage());
        }
    }

    public function testListarUnEmisorEspecifico(): void
    {
        try {
            // Probar listar un emisor específico
            $resultadoUnico = $this->cliente->listarEmisores(1);
            
            $this->assertIsArray($resultadoUnico, "Error: El resultado no es un array"); // Verifica que el resultado sea un array
            $this->assertArrayHasKey('success', $resultadoUnico, "Error: No existe la clave 'success' en la respuesta"); // Verifica la clave 'success'
            $this->assertTrue($resultadoUnico['success'], "Error: La operación no fue exitosa"); // Verifica que la operación fue exitosa
        } catch (Exception $e) {
            throw new Exception("Error al listar un emisor específico: " . $e->getMessage());
        }
    }

    public function testListarEmisoresConParametroInvalido(): void
    {
        // Esperar una excepción de tipo TypeError
        $this->expectException(TypeError::class);
        // Pasar un string en lugar de un int
        $this->cliente->listarEmisores("string");
    }
}