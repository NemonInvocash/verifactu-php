<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\ClienteVerifactu;
use tests\LogInCredentials;

class ListarWebhooksTest extends LogInCredentials
{
    private ClienteVerifactu $cliente;

    public function setUp(): void
    {
        // Inicializa el cliente de Verifactu con las credenciales de inicio de sesión
        $this->cliente = new ClienteVerifactu(parent::email, parent::password);
    }

    public function testListarTodosLosWebhooks(): void
    {
        try {
            // Probar listar todos los webhooks
            $resultadoTodos = $this->cliente->listarWebhooks(null);
            
            $this->assertIsArray($resultadoTodos, "Error: El resultado no es un array"); // Verifica que el resultado sea un array  
            $this->assertArrayHasKey('success', $resultadoTodos, "Error: No existe la clave 'success' en la respuesta"); // Verifica la clave 'success'
            $this->assertTrue($resultadoTodos['success'], "Error: La operación no fue exitosa"); // Verifica que la operación fue exitosa
        } catch (Exception $e) {
            // Manejar la excepción y lanzar un nuevo error con un mensaje específico
            throw new Exception("Error al listar webhooks: " . $e->getMessage());
        }
    }

    public function testListarUnWebhooks(): void
    {
        try {
            // Probar listar un webhook específico
            $resultadoTodos = $this->cliente->listarWebhooks(1);
            
            $this->assertIsArray($resultadoTodos, "Error: El resultado no es un array"); // Verifica que el resultado sea un array  
            $this->assertArrayHasKey('success', $resultadoTodos, "Error: No existe la clave 'success' en la respuesta"); // Verifica la clave 'success'
            $this->assertTrue($resultadoTodos['success'], "Error: La operación no fue exitosa"); // Verifica que la operación fue exitosa
        } catch (Exception $e) {
            // Manejar la excepción y lanzar un nuevo error con un mensaje específico
            throw new Exception("Error al listar webhooks: " . $e->getMessage());
        }
    }

    public function testListarWebhooksConError(): void
    {
        // Esperar una excepción de tipo TypeError
        $this->expectException(TypeError::class);
        
        // Pasar un string en lugar de un int para provocar un error
        $this->cliente->listarWebhooks("string");
    }
}