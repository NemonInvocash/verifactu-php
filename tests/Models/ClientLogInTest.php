<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\ClienteVerifactu;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use tests\LogInCredentials;

class ClientLogInTest extends LogInCredentials
{
    private string $email;
    private string $password;

    public function setUp(): void
    {
        $this->email = parent::email;
        $this->password = parent::password;
    }

    public function testLoginExitoso(): void
    {
        try {
            // Simular respuesta exitosa
            $mock = new MockHandler([
                new Response(200, [], json_encode([
                    'success' => true,
                    'token' => 'token_prueba_123'
                ]))
            ]);

            $handlerStack = HandlerStack::create($mock);
            $clienteHttp = new HttpClient(['handler' => $handlerStack]);

            $cliente = new ClienteVerifactu($this->email, $this->password);
            $reflector = new ReflectionClass($cliente);
            
            // Acceder a la propiedad privada httpClient
            $httpClientProperty = $reflector->getProperty('httpClient');
            $httpClientProperty->setAccessible(true);
            $httpClientProperty->setValue($cliente, $clienteHttp);

            // Acceder a la propiedad privada token
            $tokenProperty = $reflector->getProperty('token');
            $tokenProperty->setAccessible(true);

            // Ejecutar login
            $loginMethod = $reflector->getMethod('logIn');
            $loginMethod->setAccessible(true);
            $loginMethod->invoke($cliente);

            // Verificar que el token se haya establecido correctamente
            $this->assertEquals('token_prueba_123', $tokenProperty->getValue($cliente));
        } catch (Exception $e) {
            throw new Exception("Error al probar login exitoso: " . $e->getMessage());
        }
    }

    public function testLoginCredencialesInvalidas(): void
    {
        try {
            // Simular respuesta de error
            $mock = new MockHandler([
                new Response(401, [], json_encode([
                    'success' => false,
                    'message' => 'Credenciales inválidas'
                ]))
            ]);

            $handlerStack = HandlerStack::create($mock);
            $clienteHttp = new HttpClient(['handler' => $handlerStack]);

            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Error en la solicitud');

            $cliente = new ClienteVerifactu($this->email, 'contraseña_incorrecta');
            $reflector = new ReflectionClass($cliente);
            
            // Acceder a la propiedad privada httpClient
            $httpClientProperty = $reflector->getProperty('httpClient');
            $httpClientProperty->setAccessible(true);
            $httpClientProperty->setValue($cliente, $clienteHttp);

            // Ejecutar login
            $loginMethod = $reflector->getMethod('logIn');
            $loginMethod->setAccessible(true);
            $loginMethod->invoke($cliente);
        } catch (Exception $e) {
            throw new Exception("Error al probar credenciales inválidas: " . $e->getMessage());
        }
    }

    public function testLoginErrorConexion(): void
    {
        try {
            // Simular error de conexión
            $mock = new MockHandler([
                new RequestException('Error de conexión', new \GuzzleHttp\Psr7\Request('POST', 'test'))
            ]);

            $handlerStack = HandlerStack::create($mock);
            $clienteHttp = new HttpClient(['handler' => $handlerStack]);

            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Error en la solicitud');

            $cliente = new ClienteVerifactu($this->email, $this->password);
            $reflector = new ReflectionClass($cliente);
            
            // Acceder a la propiedad privada httpClient
            $httpClientProperty = $reflector->getProperty('httpClient');
            $httpClientProperty->setAccessible(true);
            $httpClientProperty->setValue($cliente, $clienteHttp);

            // Ejecutar login
            $loginMethod = $reflector->getMethod('logIn');
            $loginMethod->setAccessible(true);
            $loginMethod->invoke($cliente);
        } catch (Exception $e) {
            throw new Exception("Error al probar error de conexión: " . $e->getMessage());
        }
    }

    public function testGettersYSetters(): void
    {
        try {
            $cliente = new ClienteVerifactu($this->email, $this->password);

            // Probar getters
            $this->assertEquals($this->email, $cliente->getEmail(), "Error: El email no coincide");
            $this->assertEquals($this->password, $cliente->getPassword(), "Error: La contraseña no coincide");
            $this->assertNotEmpty($cliente->getToken(), "Error: El token está vacío");
            $this->assertFalse($cliente->getDebug(), "Error: El modo debug no está en false");

            // Probar setters
            $nuevoEmail = 'nuevo@email.com';
            $nuevaPassword = 'nueva_password';
            
            $cliente->setEmail($nuevoEmail);
            $cliente->setPassword($nuevaPassword);
            $cliente->setDebug(true);

            $this->assertEquals($nuevoEmail, $cliente->getEmail(), "Error: El nuevo email no se actualizó correctamente");
            $this->assertEquals($nuevaPassword, $cliente->getPassword(), "Error: La nueva contraseña no se actualizó correctamente");
            $this->assertTrue($cliente->getDebug(), "Error: El modo debug no se actualizó correctamente");
        } catch (Exception $e) {
            throw new Exception("Error al probar getters y setters: " . $e->getMessage());
        }
    }
}
