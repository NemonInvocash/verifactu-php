<?php
namespace verifactuPHP;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Exception;
use verifactuPHP\Models\Emisor;
use verifactuPHP\Models\Destinatario;
use verifactuPHP\Models\Desglose;
use verifactuPHP\Models\Tercero;
use verifactuPHP\Models\FacturaRectificada;
use verifactuPHP\Models\FacturaSustituida;
use verifactuPHP\Models\RegistroAlta;
use verifactuPHP\Models\RegistroAnulacion;

/**
 * Clase que representa el cliente de la API de Verifactu
 * @package: verifactuPHP
 * @see: https://verifactuapi.es/ 
 */
class ClienteVerifactu
{

    /**
     * @var HttpClient $httpClient Cliente HTTP para realizar solicitudes a la API
     * @var string $apiURL URL de la API de Verifactu
     * @var string $email Email del usuario
     * @var string $password Contraseña del usuario
     * @var string $token Token de autenticación
     * @var bool $debug Indica si el modo de depuración está activado
     */
    private HttpClient $httpClient;
    private string $apiURL="https://app.verifactuapi.es/api";
    private string $email;
    private string $password;
    private string $token="";
    private bool $debug=false;

    /**
     * Constructor de la clase ClienteVerifactu
     * @param string $email Email del usuario
     * @param string $password Password del usuario
     */
    public function __construct(string $email, string $password){
        $this->email = $email;
        $this->password = $password;
        $this->httpClient = new HttpClient();
        $this->logIn();
    }

    /** @return string GET URL de la API de Verifactu */
    public function getApiURL(): string { return $this->apiURL;}
    
    /** @return string GET Email del usuario */
    public function getEmail(): string { return $this->email;}
    /** @param string $newValue SET Nuevo Email del usuario */
    public function setEmail(string $newValue): void { $this->email = $newValue;}

    /** @return string GET Password del usuario */
    public function getPassword(): string { return $this->password;}
    /** @param string $newValue SET Nuevo Password del usuario */
    public function setPassword(string $newValue): void { $this->password = $newValue;}

    /** @return string GET Token de autenticación */
    public function getToken(): string { return $this->token;}

    /** @return bool GET Modo de depuración */
    public function getDebug(): bool { return $this->debug;}
    /** @param bool $newValue SET Nuevo Modo de depuración */
    public function setDebug(bool $newValue): void { $this->debug = $newValue;}

    /**
     * Método para iniciar sesión en la API
     */
    private function logIn(){
        $url = $this->apiURL . "/login";
        try {
            $response = $this->httpClient->post($url, [
                'json' => [
                    'email' => $this->email,
                    'password' => $this->password
                ],
                'verify' => false // Desactivar la verificación SSL
            ]);
            $body_content = $response->getBody()->getContents();
            $data = json_decode($body_content, true);
            if (isset($data['token'])) {
                $this->mostrarDebug("Token recibido: " . $data['token']);
                $this->token = $data['token'];
            } else {
                $this->mostrarDebug("No se ha recibido token");
                throw new Exception("No se ha recibido token");
            }
        } 
        catch (RequestException $e) {
            $this->mostrarDebug("Error en la solicitud: " . $e->getMessage());
            throw new Exception("Error en la solicitud: " . $e->getMessage());
        }
        catch (Exception $e) {
            $this->mostrarDebug("Error inesperado: " . $e->getMessage());
            throw new Exception("Error inesperado: " . $e->getMessage());
        }
    }

    /**
     * Método para mostrar mensajes de debug en consola si es true
     * @param string $mensaje Mensaje a mostrar
     */
    private function mostrarDebug(string $mensaje): void {
        if($this->debug) {
            echo $mensaje;
        }
    }

    /**
     * Método para obtener el ID de un valor de una lista
     * @param string $nombreLista Nombre de la lista
     * @param string $valorLista Valor de la lista
     * @return int ID del valor de la lista
     */
    public function idValorLista($nombreLista, $valorLista) : int{
        try {
            $lista = $this->listarListas($nombreLista, $valorLista);
            if (!isset($lista['data']['items']['id'])) {
                $this->mostrarDebug("Error: La respuesta no contiene el ID de la lista esperado");
                throw new Exception("La respuesta no contiene el ID de la lista esperado");
            }
            $this->mostrarDebug($lista['data']['items']['id']);
            return (int)$lista['data']['items']['id'];
        } catch (Exception $e) {
            $this->mostrarDebug("Error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Método para realizar una petición a la API
     * @param string $metodo Método de la petición (get, post, put, delete)
     * @param string $url URL de la petición
     * @param array $datos Datos de la petición
     * @param bool $requiereRetorno Indica si se requiere retornar la respuesta
     * @return array|null Respuesta de la API
     */
    private function realizarPeticion(string $metodo, string $url, array $datos = [], bool $requiereRetorno = true) {
        try {
            $opciones = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getToken(),
                    'Content-Type' => 'application/json'
                ],
                'verify' => false
            ];
            if (!empty($datos)) {
                $opciones['json'] = $datos;
            }

            $response = $this->httpClient->$metodo($url, $opciones);
            $data = json_decode($response->getBody()->getContents(), true);

            if ($data['success']) {
                $this->mostrarDebug("Petición realizada correctamente");
                if($requiereRetorno) {
                    $this->mostrarDebug(print_r($data, true));
                }
                return $requiereRetorno ? $data : null;
            } else {
                $this->mostrarDebug("Error: " . $data['message']);
                throw new Exception("Error: " . $data['message']);
            }
        } catch (RequestException $e) {
            $this->mostrarDebug("Error en la solicitud: " . $e->getMessage());
            throw new Exception("Error en la solicitud: " . $e->getMessage());
        } catch (Exception $e) {
            $this->mostrarDebug("Error inesperado: " . $e->getMessage());
            throw new Exception("Error inesperado: " . $e->getMessage());
        }
    }
    
    /**
     * Método para crear un webhook
     * @param string $webhookUrl URL del webhook
     * @return int ID del webhook
     */
    public function createWebhook($webhookUrl) :int {
        $url = $this->apiURL . "/webhook";
        $webhookData = [
            'url' => $webhookUrl,
            'http_method' => 'POST',
            'secret_key' => 'm7&A,$\'(6qYjg+BZ#su_3!'
        ];
        $response = $this->realizarPeticion('post', $url, $webhookData);
        if (!isset($response['data']['items'][0]['id'])) {
            $this->mostrarDebug("Error: La respuesta no contiene el ID del webhook esperado");
            throw new Exception("La respuesta no contiene el ID del webhook esperado");
        }
        $this->mostrarDebug($response['data']['items'][0]['id']);
        return (int)$response['data']['items'][0]['id'];
    }

    /**
     * Método para crear un emisor
     * @param array $dataEmisor Datos del emisor
     * @return Emisor Objeto Emisor
     */
    public function nuevoEmisor(array $dataEmisor) :Emisor {
        try {
            $emisor = new Emisor($dataEmisor);
            $this->mostrarDebug("Emisor creado con éxito!");
            $this->mostrarDebug(print_r($emisor->getArrayData(), true));
            return $emisor;
        } catch (Exception $e) {
            $this->mostrarDebug("Error al crear emisor: " . $e->getMessage());
            throw new Exception("Error al crear emisor: " . $e->getMessage());
        }
    }

    /**
     * Método para crear un destinatario
     * @param array $dataDestinatario Datos del destinatario
     * @return Destinatario Objeto Destinatario
     */
    public function nuevoDestinatario(array $dataDestinatario) :Destinatario{
        try {
            $destinatario = new Destinatario($dataDestinatario);
            $this->mostrarDebug("Destinatario creado con éxito!");
            $this->mostrarDebug(print_r($destinatario->getArrayData(), true));
            return $destinatario;
        } catch (Exception $e) {
            $this->mostrarDebug("Error al crear destinatario: " . $e->getMessage());
            throw new Exception("Error al crear destinatario: " . $e->getMessage());
        }
    }

    /**
     * Método para crear un tercero
     * @param array $dataTercero Datos del tercero
     * @return Tercero Objeto Tercero
     */
    public function nuevoTercero(array $dataTercero) :Tercero{
        try {
            $tercero = new Tercero($dataTercero);
            $this->mostrarDebug("Tercero creado con éxito!");
            $this->mostrarDebug(print_r($tercero->getArrayData(), true));
            return $tercero;
        }catch (Exception $e) {
            $this->mostrarDebug("Error al crear tercero: " . $e->getMessage());
            throw new Exception("Error al crear tercero: " . $e->getMessage());
        }
    }

    /**
     * Método para crear un desglose
     * @param array $dataDesglose Datos del desglose
     * @return Desglose Objeto Desglose
     */
    public function nuevoDesglose( array $dataDesglose ) :Desglose{
        try {
            $desglose = new Desglose($dataDesglose);
            $this->mostrarDebug("Desglose creado con éxito!");
            $this->mostrarDebug(print_r($desglose->getArrayData(), true));
            return $desglose;
        } catch (Exception $e) {
            $this->mostrarDebug("Error al crear desglose: " . $e->getMessage());
            throw new Exception("Error al crear desglose: " . $e->getMessage());
        }
    }

    /**
     * Método para crear una factura rectificada
     * @param array $dataFacturaRectificada Datos de la factura rectificada
     * @return FacturaRectificada Objeto FacturaRectificada
     */
    public function nuevaFacturaRectificada(array $dataFacturaRectificada) :FacturaRectificada{
        try {
            $facturaRectificada = new FacturaRectificada($dataFacturaRectificada);
            $this->mostrarDebug("Factura Rectificada creada con éxito!");
            $this->mostrarDebug(print_r($facturaRectificada->getArrayData(), true));
            return $facturaRectificada;
        } catch (Exception $e) {
            $this->mostrarDebug("Error al crear factura rectificada: " . $e->getMessage());
            throw new Exception("Error al crear factura rectificada: " . $e->getMessage());
        }
    }

    /**
     * Método para crear una factura sustituida
     * @param array $dataFacturaSustituida Datos de la factura sustituida
     * @return FacturaSustituida Objeto FacturaSustituida
     */
    public function nuevaFacturaSustituida(array $dataFacturaSustituida) :FacturaSustituida{
        try {
            $facturaSustituida = new FacturaSustituida($dataFacturaSustituida);
            $this->mostrarDebug("Factura Sustituida creada con éxito!");
            $this->mostrarDebug(print_r($facturaSustituida->getArrayData(), true));
            return $facturaSustituida;
        } catch (Exception $e) {
            $this->mostrarDebug("Error al crear factura sustituida: " . $e->getMessage());
            throw new Exception("Error al crear factura sustituida: " . $e->getMessage());
        }
    }

    /**
     * Método para crear un registro de alta
     * @param array $objetos Objetos del registro de alta
     * @param array $dataRegistroAlta Datos del registro de alta
     * @param int $webhookID ID del webhook
     * @return RegistroAlta Objeto RegistroAlta
     */
    public function nuevoRegistroAlta(array $objetos, array $dataRegistroAlta, int $webhookID = 0) :RegistroAlta{
              
        try {
            $registroAlta = new RegistroAlta($objetos, $dataRegistroAlta, $webhookID);
            $this->mostrarDebug("Registro Alta creado con éxito!");
            $this->mostrarDebug(print_r($registroAlta->getArrayData(), true));
            return $registroAlta;
        } catch (Exception $e) {
            $this->mostrarDebug("Error al crear registro alta: " . $e->getMessage());
            throw new Exception("Error al crear registro alta: " . $e->getMessage());
        }
    }

    /**
     * Método para crear un registro de anulacion
     * @param array $dataRegistroAnulacion Datos del registro de anulacion
     * @return RegistroAnulacion Objeto RegistroAnulacion
     */
    public function nuevoRegistroAnulacion(array $dataRegistroAnulacion) :RegistroAnulacion{
        try {
            $registroAnulacion = new RegistroAnulacion($dataRegistroAnulacion);
            $this->mostrarDebug("Registro de anulacion creado con éxito!");
            $this->mostrarDebug(print_r($registroAnulacion->getArrayData(), true));
            return $registroAnulacion;
        } catch (Exception $e) {
            $this->mostrarDebug("Error al crear registro de anulacion: " . $e->getMessage());
            throw new Exception("Error al crear registro de anulacion: " . $e->getMessage());
        }
    }   

    /**
     * Método para listar emisores dados de alta
     * @param int|null $id ID del emisor
     * @return array Lista de emisores
     */
    public function listarEmisores(?int $id = null){
        $url = $this->apiURL . "/emisor" . ($id !== null ? "/$id" : "");
        return $this->realizarPeticion('get', $url);
    }

    /**
     * Método para listar envios a AEAT
     * @param int|null $id ID del envio a AEAT
     * @return array Lista de envios a AEAT
     */
    public function listarEnviosAEAT(?int $id = null){
        $url = $this->apiURL . "/envios-aeat" . ($id !== null ? "/$id" : "");
        return $this->realizarPeticion('get', $url);
    }

    /**
     * Método para listar registros de alta
     * @param int|null $id ID del registro de alta
     * @return array Lista de registros de alta
     */
    public function listarRegistrosAlta(?int $id = null){
        $url = $this->apiURL . "/alta-registro-facturacion" . ($id !== null ? "/$id" : "");
        return $this->realizarPeticion('get', $url);
    }
    
    /**
     * Método para listar listas
     * @param string $nombre Nombre de la lista
     * @param string $valor Valor de la lista
     * @return array Lista de listas
     */
    public function listarListas(string $nombre = "", string $valor = ""){
        $url = $this->apiURL . "/listas" . ($nombre !== "" ? "/$nombre" . ($valor !== "" ? "/$valor" : "") : "");
        return $this->realizarPeticion('get', $url);
    }

    /**
     * Método para listar webhooks
     * @param int|null $id ID del webhook
     * @return array Lista de webhooks
     */
    public function listarWebhooks(?int $id = null){
        $url = $this->apiURL . "/webhook" . ($id !== null ? "/$id" : "");
        return $this->realizarPeticion('get', $url);
    }

    /**
     * Método para dar de alta un emisor
     * @param Emisor $emisor Objeto Emisor
     */
    public function altaEmisor(Emisor $emisor){
        $url = $this->apiURL . "/emisor";
        $this->realizarPeticion('post', $url, $emisor->getArrayData(), false);
        $this->mostrarDebug("Emisor dado de alta con éxito!");
    }

    /**
     * Método para dar de alta un registro de alta
     * @param RegistroAlta $registroAlta Objeto RegistroAlta
     */
    public function altaRegistroAlta(RegistroAlta $registroAlta){
        $url = $this->apiURL . "/alta-registro-facturacion";
        $this->realizarPeticion('post', $url, $registroAlta->getArrayData(), false);
        $this->mostrarDebug("Registro Alta dado de alta con éxito!");
    }

    /**
     * Método para dar de alta un registro de anulacion
     * @param RegistroAnulacion $registroAnulacion Objeto RegistroAnulacion
     */
    public function altaRegistroAnulacion(RegistroAnulacion $registroAnulacion){
        $url = $this->apiURL . "/anulacion-registro-facturacion";
        $this->realizarPeticion('post', $url, $registroAnulacion->getArrayData(), false);
        $this->mostrarDebug("Registro de anulacion dado de alta con éxito!");
    }
    
    /**
     * Método para dar de baja un registro usando el ID
     * @param int $id ID del registro
     */
    public function bajaRegistro(int $id){
        $url = $this->apiURL . "/anulacion-registro-facturacion/$id";
        $this->realizarPeticion('post', $url);
        $this->mostrarDebug("Registro dado de baja con éxito!");
    }   
}
?>