<?php
namespace verifactuPHP\Models;

/**
 * Clase que representa el emisor de una factura
 * @package: verifactuPHP\Models
 * @see: https://verifactuapi.es/ 
 */
class Emisor
{

    /**
     * @var string $nif NIF del emisor
     * @var string $nombre Nombre de la razón social
     * @var string $representanteRazonSocial Representante de la razón social
     * @var string $representanteNif Representante del NIF
     * @var int $webhookID ID del webhook
     * @var bool $enviarAeat Indica si se debe enviar a AEAT
     * @var bool $enviarAeatPaused Indica si se debe enviar a AEAT
     */
    private string $nif;
    private string $nombre;
    private string $representanteRazonSocial;
    private string $representanteNif;
    private int $webhookID;
    private bool $enviarAeat;
    private bool $enviarAeatPaused;

    /** @var array Mapeo de propiedades a IDs de API */
    private static array $APIids = [
        'nif' => 'nif',
        'nombre' => 'nombre',
        'representanteRazonSocial' => 'representante_razon_social',
        'representanteNif' => 'representante_nif',
        'webhookID' => 'default_webhook_id',
        'enviarAeat' => 'enviar_aeat',
        'enviarAeatPaused' => 'enviar_aeat_paused'
    ];

    /** @var array Propiedades que son enteros */
    private static array $integerProperties = [
        'webhookID'
    ];
    
    /** @var array Propiedades que son booleanos */
    private static array $booleanProperties = [
        'enviarAeat',
        'enviarAeatPaused'
    ];

    /**
     * Constructor de la clase Emisor
     * @param array $data Array con los datos del emisor
     */
    public function __construct(array $data){
        foreach(self::$APIids as $prop => $apiId) {
            $value = $data[$prop] ?? (in_array($prop, self::$integerProperties) ? 0 : '');
            if (in_array($prop, self::$integerProperties)) {
                $this->$prop = !empty($value) ? (int)$value : 0;
            } elseif (in_array($prop, self::$booleanProperties)) {
                $this->$prop = !empty($value) ? (bool)$value : false;
            } else {
                $this->$prop = !empty($value) ? (string)$value : '';
            }
        }
    }

    /** @return string GET NIF del emisor */
    public function getNif(): string { return $this->nif;}
    /** @param string $newValue SET Nuevo NIF del emisor */
    public function setNif(string $newValue): void { $this->nif = $newValue;}

    /** @return string GET Nombre de la razón social del emisor */
    public function getNombre(): string { return $this->nombre;}
    /** @param string $newValue SET Nuevo nombre de la razón social del emisor */
    public function setNombre(string $newValue): void { $this->nombre = $newValue;}

    /** @return string GET Representante de la razón social del emisor */
    public function getRepresentanteRazonSocial(): string { return $this->representanteRazonSocial;}
    /** @param string $newValue SET Nuevo representante de la razón social del emisor */
    public function setRepresentanteRazonSocial(string $newValue): void { $this->representanteRazonSocial = $newValue;}

    /** @return string GET Representante del NIF del emisor */
    public function getRepresentanteNif(): string { return $this->representanteNif;}
    /** @param string $newValue SET Nuevo representante del NIF del emisor */
    public function setRepresentanteNif(string $newValue): void { $this->representanteNif = $newValue;}

    /** @return int GET ID del webhook del emisor */
    public function getWebhookID(): int { return $this->webhookID;}
    /** @param int $newValue SET Nuevo ID del webhook del emisor */
    public function setWebhookID(int $newValue): void { $this->webhookID = $newValue;}

    /** @return bool GET Enviar a AEAT */
    public function getEnviarAeat(): bool { return $this->enviarAeat;}
    /** @param bool $newValue SET Enviar a AEAT */
    public function setEnviarAeat(bool $newValue): void { $this->enviarAeat = $newValue;}

    /** @return bool GET Enviar a AEAT Pausado */
    public function getEnviarAeatPaused(): bool { return $this->enviarAeatPaused;}
    /** @param bool $newValue SET Enviar a AEAT Pausado */
    public function setEnviarAeatPaused(bool $newValue): void { $this->enviarAeatPaused = $newValue;}

    /**
     * Retorna las variables en formato array
     * @return array Array con los datos del emisor
     */ 
    public function getArrayData(): array {
        $data = [];
        foreach(self::$APIids as $prop => $apiId) {
            if ($this->$prop !== '' && $this->$prop !== 0) {
                $data[$apiId] = $this->$prop;
            }
        }
        return $data;
    }

    /**
     * Crea y retorna un objeto Emisor vacío
     * @return Emisor Objeto Emisor con valores por defecto
     */
    public static function empty(): Emisor {
        return new Emisor([
            'nif' => '',
            'nombre' => '',
            'representanteRazonSocial' => '',
            'representanteNif' => '',
            'webhookID' => 0,
            'enviarAeat' => false,
            'enviarAeatPaused' => false
        ]);
    }
}
?>