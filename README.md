# verifactuPHP

Cliente PHP para la API de Verifactu, desarrollado por **Invocash**.

---

## Descripción

**verifactuPHP** integra **Verifactu** —el sistema de facturación electrónica de la AEAT— en tu aplicación PHP, sin necesidad de construir a mano las llamadas a la API. Permite validar y emitir facturas electrónicas conformes a la normativa española vigente.

Para construir el JSON de una factura puedes usar el [generador de JSON](https://verifactuapi.es/generador-json).

Más información en [verifactuapi.es](https://verifactuapi.es/) e [invocash.es](https://invocash.es).

---

## Instalación

### Con Composer (recomendado)

```
composer require invocash/verifactu-php
```

### Sin Composer

Si no usas Composer, descarga el repositorio y colócalo en tu proyecto (p.ej. en `libs/verifactu-php/`). Incluye el autoloader con `require_once` antes de usar cualquier clase, donde te convenga: en el bootstrap de la app o en el módulo o rama que se encargue de la facturación. Al usar `require_once` puedes incluirlo desde varios sitios sin riesgo de cargarlo dos veces.

```php
require_once 'libs/verifactu-php/autoload.php';

use VerifactuPHP\VerifactuClient;

$verifactu = VerifactuClient::usuario($email, $password);
```

El autoloader registra el namespace `VerifactuPHP\` (PSR-4, equivalente al de Composer): a partir de ese `require`, todas las clases —`VerifactuClient`, `TbaiClient`, excepciones…— se cargan automáticamente al usarlas, sin incluir ningún otro fichero. La librería no tiene dependencias externas: solo requiere PHP 8.1+ y la extensión cURL.

---

# Guía de uso

La librería expone dos clientes según el tipo de factura: `VerifactuClient` (Verifactu / territorio común AEAT) y `TbaiClient` (TicketBAI / País Vasco). Ambos comparten los mismos endpoints comunes (estado, censo, emisores y webhooks).

---

## 1. Inicialización del cliente

Crea el cliente adecuado según tus credenciales, con `usuario()` o `emisor()`:

```php
require 'vendor/autoload.php';

use VerifactuPHP\VerifactuClient;
use VerifactuPHP\TbaiClient;

// Autenticación como usuario (email + contraseña): acceso completo, incluida la gestión de emisores y webhooks.
$verifactu = VerifactuClient::usuario($email, $password);

// Autenticación como emisor (username + API key): limitado al NIF del emisor.
$verifactu = VerifactuClient::emisor($username, $apiKey);

// El cliente TBAI se inicializa igual.
$tbai = TbaiClient::usuario($email, $password);
$tbai = TbaiClient::emisor($username, $apiKey);
```

El cliente gestiona el login y la renovación del token automáticamente: lo obtiene en la primera petición y, si expira, lo renueva y reintenta la petición de forma transparente.

Todos los métodos devuelven la respuesta de la API decodificada como `array` asociativo. Si la petición falla, lanzan una excepción (ver la sección Excepciones), por lo que un valor de retorno siempre implica éxito.

> Los métodos marcados con 👤 requieren autenticación **como usuario**; un cliente de emisor lanza `ClientException` antes de enviar la petición.

Regístrate en [https://verifactuapi.es/](https://verifactuapi.es/) para obtener tus credenciales de acceso.

---

## 2. Excepciones

Ante un error, los métodos lanzan una de estas excepciones. `ApiRequestRefusedException` extiende `ApiException`, por lo que capturar `ApiException` cubre ambos casos.

### ClientException

Error local, detectado **antes** de enviar la petición.

| Código | Descripción | Mensaje de error |
|---|---|---|
| `C-0` | (valor por defecto) | — |
| `C-1` | El body no se pudo serializar a JSON. | `No se pudo convertir el body a JSON: <detalle>` |
| `C-2` | Operación reservada a usuario (👤) invocada desde un cliente de emisor. | `Esta operación requiere autenticación como usuario (login con email y contraseña); no está disponible para un cliente de emisor.` |

### TransportException

Fallo de red: la petición no llegó a la API. Todas comparten el mensaje `Error de transporte al llamar a <url>: <detalle cURL>` (salvo el fallo al inicializar cURL, siempre `T-0`).

| Código | Descripción | Mensaje de error |
|---|---|---|
| `T-0` | Otro fallo de transporte, o error al inicializar cURL. | `Error de transporte al llamar a <url>: <detalle cURL>` · `No se pudo inicializar la petición cURL para <url>` |
| `T-1` | Se agotó el tiempo de espera (timeout). | `Error de transporte al llamar a <url>: <detalle cURL>` |
| `T-2` | Fallo de verificación o negociación SSL. | `Error de transporte al llamar a <url>: <detalle cURL>` |
| `T-3` | No se pudo conectar o resolver el host. | `Error de transporte al llamar a <url>: <detalle cURL>` |

### ApiException

La API respondió, pero con un fallo **no estructurado**. Expone `getStatusCode()`.

| Código | Descripción | Mensaje de error |
|---|---|---|
| `A-0` | (valor por defecto) | — |
| `A-1` | Autenticación fallida (token inválido o no renovable). | `Error de autenticación` · `La respuesta de login no contiene un token válido` |
| `A-2` | La API respondió, pero el cuerpo no es JSON legible (respuesta vacía o malformada). | `Operación aceptada/rechazada (HTTP <código>) pero la respuesta es ilegible.` |
| `A-3` | Error de servidor. | `Error del servidor (HTTP <código>).` |

`<código>` = código de estado HTTP devuelto por la API (p.ej. 500).

### ApiRequestRefusedException

Extiende `ApiException`. La API procesó la petición y la **rechazó** con un error estructurado (`success: false`). Expone `getStatusCode()` y `getData()`, que devuelve el body de error completo con el detalle del rechazo (validación, factura duplicada, etc.).

| Código | Descripción | Mensaje de error | Respuesta API |
|---|---|---|---|
| `A-4` | La API rechazó la petición (`success: false`). | Mensaje devuelto por la API (o `Error desconocido de la API`) | ✓ `getData()` |

### Métodos

Además de los de `Exception` (`getMessage()`, etc.), estas excepciones añaden accesores propios:

| Método | Devuelve | Disponible en |
|---|---|---|
| `getReason()` | Código de subcausa para diferenciar el motivo sin parsear el mensaje (ver la tabla de códigos de cada excepción). | Todas |
| `getStatusCode()` | Código de estado HTTP asociado al error (`int`). | `ApiException` y `ApiRequestRefusedException` |
| `getData()` | Cuerpo completo de la respuesta de error de la API (`array`), con el detalle del rechazo. | `ApiRequestRefusedException` |

`getCode()` (nativo de PHP) no se utiliza; para el status HTTP usa `getStatusCode()`.

### Gestión de excepciones

Encadena los `catch` de la **más específica a la más general**. En la práctica solo hay una regla que importa: `ApiRequestRefusedException` extiende `ApiException`, así que debe capturarse **antes**; de lo contrario `ApiException` la interceptaría primero y perderías el `getData()`.

`ClientException` y `TransportException` son independientes entre sí y de `ApiException`, así que su orden relativo da igual. Para atrapar cualquier error de la librería de golpe, basta con capturar esas dos más `ApiException`.

```php
use VerifactuPHP\Exceptions\ApiRequestRefusedException;
use VerifactuPHP\Exceptions\ApiException;
use VerifactuPHP\Exceptions\TransportException;
use VerifactuPHP\Exceptions\ClientException;

try {
    $respuesta = $verifactu->altaRegistroFacturacion($factura);
} catch (ApiRequestRefusedException $e) {
    // Rechazo de negocio. Métodos: getData(), getStatusCode(), getReason(), getMessage()
    $detalle = $e->getData();       // body de error de la API
    $http    = $e->getStatusCode(); // código HTTP
    $codigo  = $e->getReason();     // A-4
} catch (ApiException $e) {
    // Error de servidor, auth o respuesta ilegible. Métodos: getStatusCode(), getReason(), getMessage()
    $http   = $e->getStatusCode(); // código HTTP
    $codigo = $e->getReason();     // A-1, A-2, A-3
} catch (TransportException $e) {
    // Fallo de red. Métodos: getReason(), getMessage()
    $codigo = $e->getReason();     // T-0, T-1, T-2, T-3
} catch (ClientException $e) {
    // Error de uso del cliente. Métodos: getReason(), getMessage()
    $codigo = $e->getReason();     // C-1, C-2
}
```

---

## 3. Funcionalidades

Para usar cualquier funcionalidad, invoca el método sobre el cliente ya inicializado:

```php
$respuesta = $cliente->metodo($args);
```

Las tablas siguientes agrupan los métodos por bloque. Para cada uno se indica su firma, qué hace y el enlace a su documentación.

**👤** = requiere login de usuario.

Los listados reciben una *query string* ya formada (p.ej. `'?enabled=1&limit=50'`) que se concatena a la URL.

### 3.1 Comunes

Disponibles tanto en `VerifactuClient` como en `TbaiClient`.

**Estado**

| Método | Descripción | Docs |
|---|---|---|
| `status()` | Comprueba que el servidor está operativo (no requiere autenticación). | https://app.verifactuapi.es/docs/#utilidades-GETapi-status |

**Censo AEAT**

| Método | Descripción | Docs |
|---|---|---|
| `censoAeat(array $destinatarios)` | Valida uno o varios destinatarios contra el censo de la AEAT. | https://app.verifactuapi.es/docs/#consultas-externas-POSTapi-censo-aeat |

**Emisores**

| Método | Descripción | Docs |
|---|---|---|
| `listarEmisores(string $query = '')` | Lista los emisores de la cuenta. | https://app.verifactuapi.es/docs/#emisores-GETapi-emisor |
| `obtenerEmisor(string $id)` | Obtiene un emisor por su id. | https://app.verifactuapi.es/docs/#emisores-GETapi-emisor--id- |
| `crearEmisor(array $datos)` 👤 | Crea un nuevo emisor. | https://app.verifactuapi.es/docs/#emisores-POSTapi-emisor |
| `actualizarEmisor(string $id, array $datos)` 👤 | Actualiza un emisor existente. | https://app.verifactuapi.es/docs/#emisores-PUTapi-emisor--id- |
| `generarApiKey(string $id)` 👤 | Genera y devuelve la API key de un emisor. | https://app.verifactuapi.es/docs/#emisores-GETapi-emisor--id--api-key |

**Webhooks**

| Método | Descripción | Docs |
|---|---|---|
| `listarWebhooks(string $query = '')` 👤 | Lista los webhooks configurados. | https://app.verifactuapi.es/docs/#webhook-GETapi-webhook |
| `obtenerWebhook(string $id)` 👤 | Obtiene un webhook por su id. | https://app.verifactuapi.es/docs/#webhook-GETapi-webhook--id- |
| `logsWebhook(string $id)` 👤 | Obtiene los logs de un webhook. | https://app.verifactuapi.es/docs/#webhook-GETapi-webhook--id--logs |
| `crearWebhook(array $datos)` 👤 | Crea un nuevo webhook. | https://app.verifactuapi.es/docs/#webhook-POSTapi-webhook |
| `actualizarWebhook(string $id, array $datos)` 👤 | Actualiza un webhook existente. | https://app.verifactuapi.es/docs/#webhook-PUTapi-webhook--id- |
| `eliminarWebhook(string $id)` 👤 | Elimina un webhook. | — |

### 3.2 Verifactu

Métodos de `VerifactuClient`.

**Alta**

| Método | Descripción | Docs |
|---|---|---|
| `altaRegistroFacturacion(array $factura)` | Registra (da de alta) una factura Verifactu. | https://app.verifactuapi.es/docs/#verifactu-POSTapi-alta-registro-facturacion |
| `validarRegistroFacturacion(array $factura)` | Valida el formato de una factura sin registrarla. | https://app.verifactuapi.es/docs/#verifactu-POSTapi-alta-registro-facturacion-validar |

**Anulación**

| Método | Descripción | Docs |
|---|---|---|
| `anularRegistroFacturacionPorDatos(array $datos)` | Anula una factura por sus datos identificadores. | https://app.verifactuapi.es/docs/#verifactu-POSTapi-anulacion-registro-facturacion |
| `anularRegistroFacturacionPorId(string $id)` | Anula un registro de facturación existente por su id. | https://app.verifactuapi.es/docs/#verifactu-POSTapi-anulacion-registro-facturacion--id- |

**Listado**

| Método | Descripción | Docs |
|---|---|---|
| `listarRegistrosFacturacion(string $query = '')` | Lista los registros de facturación. | https://app.verifactuapi.es/docs/#verifactu-GETapi-alta-registro-facturacion |
| `obtenerRegistroFacturacion(string $id)` | Obtiene un registro de facturación por su id. | https://app.verifactuapi.es/docs/#verifactu-GETapi-alta-registro-facturacion--id- |

**Envíos AEAT**

| Método | Descripción | Docs |
|---|---|---|
| `listarEnviosAeat(string $query = '')` | Lista los envíos a la AEAT. | https://app.verifactuapi.es/docs/#verifactu-GETapi-envios-aeat |
| `obtenerEnvioAeat(string $id)` | Obtiene un envío a la AEAT por su id. | https://app.verifactuapi.es/docs/#verifactu-GETapi-envios-aeat--id- |
| `obtenerLineaEnvioAeat(string $lineaId)` | Obtiene una línea de envío a la AEAT por su id. | https://app.verifactuapi.es/docs/#verifactu-GETapi-envios-aeat-linea--linea_id- |

**Consultas**

| Método | Descripción | Docs |
|---|---|---|
| `consultarRegistrosAeat(array $datos)` | Consulta registros de facturación ya presentados en la AEAT. | https://app.verifactuapi.es/docs/#verifactu-POSTapi-consultar-registros-aeat |

**Listas de códigos**

| Método | Descripción | Docs |
|---|---|---|
| `listarListas()` | Lista todas las listas de códigos disponibles. | https://app.verifactuapi.es/docs/#verifactu-GETapi-listas |
| `obtenerLista(string $lista)` | Obtiene los valores de una lista de códigos. | https://app.verifactuapi.es/docs/#verifactu-GETapi-listas--lista- |
| `obtenerLineaLista(string $lista, string $valor)` | Obtiene una línea concreta de una lista de códigos. | https://app.verifactuapi.es/docs/#verifactu-GETapi-listas--lista---valor- |

### 3.3 TBAI

Métodos de `TbaiClient`.

**Alta**

| Método | Descripción | Docs |
|---|---|---|
| `altaRegistroTbai(array $factura)` | Registra (da de alta) una factura TBAI. | https://app.verifactuapi.es/docs/#tbai-POSTapi-alta-registro-tbai |
| `validarRegistroTbai(array $factura)` | Valida el formato de una factura TBAI sin registrarla. | https://app.verifactuapi.es/docs/#tbai-POSTapi-alta-registro-tbai-validar |
| `obtenerQrTbai(string $id)` | Obtiene el QR de una factura TBAI por su id. | https://app.verifactuapi.es/docs/#tbai-GETapi-tbai-QR--id- |
| `zuzenduModificarTbai(string $id, array $datos = [])` | Zuzendu: modifica una factura TBAI por su id. | https://app.verifactuapi.es/docs/#tbai-POSTapi-tbai-modificar--id- |
| `zuzenduSubsanarTbai(string $id, array $datos = [])` | Zuzendu: subsana una factura TBAI por su id. | https://app.verifactuapi.es/docs/#tbai-POSTapi-tbai-subsanar--id- |

**Anulación**

| Método | Descripción | Docs |
|---|---|---|
| `anularRegistroTbaiPorDatos(array $datos)` | Anula una factura TBAI por sus datos identificadores. | https://app.verifactuapi.es/docs/#tbai-POSTapi-anulacion-registro-tbai |
| `anularRegistroTbaiPorId(string $id)` | Anula un registro de facturación TBAI existente por su id. | https://app.verifactuapi.es/docs/#tbai-POSTapi-anulacion-registro-tbai--id- |

**Listado**

| Método | Descripción | Docs |
|---|---|---|
| `listarRegistrosTbai(string $query = '')` | Lista los registros de facturación TBAI. | https://app.verifactuapi.es/docs/#tbai-GETapi-alta-registro-tbai |
| `obtenerRegistroTbai(string $id)` | Obtiene un registro de facturación TBAI por su id. | https://app.verifactuapi.es/docs/#tbai-GETapi-alta-registro-tbai--id- |

**Envíos**

| Método | Descripción | Docs |
|---|---|---|
| `listarEnviosTbai(string $query = '')` | Lista los envíos TBAI. | https://app.verifactuapi.es/docs/#tbai-GETapi-envios-tbai |
| `obtenerEnvioTbai(string $id)` | Obtiene un envío TBAI por su id. | https://app.verifactuapi.es/docs/#tbai-GETapi-envios-tbai--id- |

**Consultas**

| Método | Descripción | Docs |
|---|---|---|
| `consultarRegistrosTbai(array $datos)` | Consulta registros de facturación TBAI ya presentados. | — |
| `obtenerConsultaTbai(string $id)` | Obtiene una consulta de registros TBAI por su id. | — |

**Listas de códigos**

| Método | Descripción | Docs |
|---|---|---|
| `listarListasTbai()` | Lista todas las listas de códigos TBAI. | https://app.verifactuapi.es/docs/#tbai-GETapi-listas-tbai |
| `obtenerListaTbai(string $lista)` | Obtiene los valores de una lista de códigos TBAI. | https://app.verifactuapi.es/docs/#tbai-GETapi-listas-tbai--lista- |
| `obtenerLineaListaTbai(string $lista, string $valor)` | Obtiene una línea concreta de una lista de códigos TBAI. | https://app.verifactuapi.es/docs/#tbai-GETapi-listas-tbai--lista---valor- |

**Software TBAI**

| Método | Descripción | Docs |
|---|---|---|
| `obtenerSoftwareTbai(string $type)` | Obtiene los datos del software TBAI por su tipo. | https://app.verifactuapi.es/docs/#tbai-GETapi-software-tbai--type- |

### 3.4 Peticiones personalizadas

Si necesitas llamar a un endpoint que la librería todavía no envuelve, usa el método `request()`, disponible en ambos clientes. Construye la petición a partir de la ruta que le pases y reutiliza la misma lógica de autenticación, renovación de token y manejo de errores que el resto de métodos.

```php
public function request(
    string $method,        // GET, POST, PUT, PATCH, DELETE
    string $path,          // ruta relativa a la base de la API, p.ej. '/mi-endpoint?x=1'
    ?array $body = null,   // body que se enviará como JSON
    bool $conAuth = true,  // si la petición requiere token (por defecto, sí)
): array
```

```php
$verifactu->request('POST', '/endpoint-nuevo', ['campo' => 'valor']);
$verifactu->request('GET', '/algo?x=1');
```

Devuelve el `array` de la respuesta y lanza las mismas excepciones que el resto de la librería. A diferencia de los métodos con nombre, no valida el tipo de login: si llamas a una operación reservada a usuarios con un cliente de emisor, el error lo devolverá la API en vez de cortarse en local.

---

## Licencia

Este proyecto está bajo la Licencia MIT, lo que permite su uso y modificación bajo ciertas condiciones.

---

## Contacto

Para más información, contacta con INVOCASH SOLUTIONS SL:

- Soporte técnico: [support@invocash.es](mailto:support@invocash.es)
- Consultas comerciales: [sales@invocash.es](mailto:sales@invocash.es)

---
