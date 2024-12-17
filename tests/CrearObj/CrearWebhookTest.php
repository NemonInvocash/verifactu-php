<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\ClienteVerifactu;
use tests\LogInCredentials;

/**
 * @covers \verifactuPHP\ClienteVerifactu
 */
class CrearWebhookTest extends LogInCredentials
{
    private ClienteVerifactu $cliente;

    // Configuración inicial antes de cada prueba
    public function setUp(): void
    {
        $this->cliente = new ClienteVerifactu(parent::email, parent::password);
    }

    // Prueba para crear un webhook
    public function testCrearWebhook(): void
    {
        try {
            // URL del webhook a crear
            $urlWebhook = 'https://midominio.com/webhook';
            
            // Creación del webhook
            $webhookId = $this->cliente->createWebhook($urlWebhook);
            
            // Verificación de que el ID del webhook es un número entero
            $this->assertIsInt($webhookId, "El ID del webhook no es un número entero");
            
            // Verificación de que el ID del webhook es mayor que 0
            $this->assertGreaterThan(0, $webhookId, "El ID del webhook debe ser mayor que 0");
        } catch (Exception $e) {
            // Manejo de errores en la creación del webhook
            $this->fail("Error al crear webhook: " . $e->getMessage());
        }
    }
}