# verifactuPHP
**Librería y ejemplos prácticos para integrar la API Verifactu en proyectos PHP. Facilita la automatización de facturación y gestión de documentos electrónicos.**

**verifactuPHP** es una **biblioteca PHP** que facilita la gestión de la **API de Verifactu** (https://verifactuapi.es/), desarrollada por **Invocash**. 
Esta **API** ofrece una solución efectiva para la **gestión de facturas electrónicas** en España, cumpliendo con las **nuevas normativas fiscales**.
Es la opción ideal para **integrar la API de Verifactu** en tu **aplicación PHP**, especialmente antes de la entrada en vigor de la **nueva ley de facturación electrónica** (https://www.boe.es/boe/dias/2024/07/05/pdfs/BOE-A-2024-7445.pdf). 
Las recientes **leyes de facturación electrónica** exigen que los **software de facturación** sean capaces de **validar** y **emitir** facturas electrónicas utilizando **Verifactu**.
Con **verifactuPHP**, puedes **validar** y **emitir** facturas electrónicas de manera sencilla en tu **aplicación PHP**. 
Para más información, visita **https://www.nemon.io/invocash/verifactu-api/**.

## Instalación

Para instalar verifactuPHP, puedes usar estos dos métodos:

### Método 1:
1. Clona el repositorio:

```
git clone https://github.com/.....
```

2. Accede al directorio del proyecto e instala las dependencias:

```
cd verifactuPHP
composer install
```

### Método 2:
1. Edita tu composer.json:

```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/...."
    }
]
```

2. Requiere el paquete:

```
composer require invocash/verifactu-php
```

3. Puedes actualizar el paquete usando:

```
composer update invocash/verifactu-php
```

## Uso

Utiliza la clase `ClienteVerifactu` para verificar facturas electrónicas.

```
require 'vendor/autoload.php';

use verifactuPHP\ClienteVerifactu;

$verifactu = new ClienteVerifactu($email, $password);

En el apartado "ejemplos" puedes ver cómo usar la API de Verifactu.
```

## Contribuciones

Estamos abiertos a contribuciones que mejoren verifactuPHP. Si deseas colaborar, sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una nueva rama (`git checkout -b feature/nueva-funcionalidad`).
3. Realiza tus cambios y haz un commit (`git commit -m 'Añadir nueva funcionalidad'`).
4. Envía un pull request.

## Licencia

Este proyecto está bajo la Licencia MIT, lo que permite su uso y modificación bajo ciertas condiciones.

## Contacto

Para más información, contacta a:

Ivan Sole Martinez - sales@nemon.io


---

**Palabras clave**: verifactu, verifactuPHP, API de Verifactu, biblioteca PHP, gestión de facturas electrónicas, validación de facturas, integración de API, cumplimiento normativo, facturación electrónica, instalación de paquetes PHP, contribuciones a proyectos open source.
