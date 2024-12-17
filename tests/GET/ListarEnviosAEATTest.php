<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\ClienteVerifactu;
use tests\LogInCredentials;

class ListarEnviosAEATTest extends LogInCredentials
{
    private ClienteVerifactu $cliente;

    public function setUp(): void
    {
        // Inicializa el cliente con las credenciales de inicio de sesión
        $this->cliente = new ClienteVerifactu(parent::email, parent::password);
    }

    public function testListarTodosLosEnviosAEAT(): void
    {
        try {
            // Probar listar todos los envíos
            $resultadoTodos = $this->cliente->listarEnviosAEAT(null);
            
            $this->assertIsArray($resultadoTodos, "Error: El resultado no es un array"); // Verifica que el resultado sea un array  
            $this->assertArrayHasKey('success', $resultadoTodos, "Error: No existe la clave 'success' en la respuesta"); // Verifica la clave 'success'
            $this->assertTrue($resultadoTodos['success'], "Error: La operación no fue exitosa"); // Verifica que la operación fue exitosa
        } catch (Exception $e) {
            // Captura cualquier excepción y lanza un nuevo error con un mensaje específico
            throw new Exception("Error al listar envíos AEAT: " . $e->getMessage());
        }
    }

    public function testListarEnvioEspecifico(): void
    {
        try {
            // Probar listar un envío específico
            $resultadoUnico = $this->cliente->listarEnviosAEAT(1);
            
            $this->assertIsArray($resultadoUnico, "Error: El resultado no es un array"); // Verifica que el resultado sea un array      
            $this->assertArrayHasKey('success', $resultadoUnico, "Error: No existe la clave 'success' en la respuesta"); // Verifica la clave 'success'
            $this->assertTrue($resultadoUnico['success'], "Error: La operación no fue exitosa"); // Verifica que la operación fue exitosa
        } catch (Exception $e) {
            // Captura cualquier excepción y lanza un nuevo error con un mensaje específico
            throw new Exception("Error al listar envíos AEAT: " . $e->getMessage());
        }
    }

    public function testListarEnviosAEATConError(): void
    {
        // Esperar una excepción de tipo TypeError
        $this->expectException(TypeError::class);
        
        // Probar con un string en lugar de un int
        $this->cliente->listarEnviosAEAT("string");
    }
}
?>