<?php
declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use verifactuPHP\ClienteVerifactu;

/**
 * Clase que gestiona las credenciales de inicio de sesión para las pruebas.
 */
class LogInCredentials extends TestCase
{
    // Correo electrónico utilizado para las pruebas de inicio de sesión
    const email = 'ejemplo@verifactuAPI.com';

    // Contraseña utilizada para las pruebas de inicio de sesión
    const password = '#9e2q$v@bG';

    // URL de la API de Verifactu
    const url = 'https://app.verifactuapi.es/api';
}