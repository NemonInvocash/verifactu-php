<?php

/**
 * Autoloader para usar verifactuPHP sin Composer.
 *
 * Uso:
 *   require_once 'ruta/a/verifactu-php/autoload.php';
 *
 * Registra un autoloader PSR-4 que mapea el namespace VerifactuPHP\ al directorio src/,
 * equivalente al que generaría Composer. No requiere dependencias externas.
 */

spl_autoload_register(function (string $class): void {
    $prefix = 'VerifactuPHP\\';
    $baseDir = __DIR__ . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // La clase no pertenece a este namespace; deja que actúen otros autoloaders.
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});
