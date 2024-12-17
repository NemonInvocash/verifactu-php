<?php
namespace verifactuPHP\Models;

/**
 * Clase que representa el destinatario de una factura
 * @package: verifactuPHP\Models
 * @see: https://verifactuapi.es/ 
 */
class Destinatario
{

    /**
     * @var string $nombreRazon  Nombre-razón social del destinatario
     * @var string $nif  Identificador del NIF del destinatario
     * @var string $otroCodPais Código del país del destinatario
     * @var int|string $otroIDType Clave para establecer el tipo de identificación del país de residencia del destinatario ->lista L7 
     * @var string $otroID Número de identificación en el país de residencia del destinatario
     */
    private string $nombreRazon;
    private string $nif;
    private string $otroCodPais;
    private int|string $otroIDType;
    private string $otroID;

    /** @var array Mapeo de propiedades a IDs de API */
    private static array $APIids = [
        'nombreRazon' => 'NombreRazon',
        'nif' => 'NIF',
        'otroCodPais' => 'OtroCodPais',
        'otroIDType' => 'OtroIDType',
        'otroID' => 'OtroID'
    ];

    /** @var array Propiedades que son enteros */
    private static array $integerProperties = [
        'otroIDType'
    ];

    /**
     * Constructor de la clase Destinatario
     * @param array $data Array con los datos del destinatario
     */
    public function __construct(array $data){
        foreach(self::$APIids as $prop => $apiId) {
            $value = $data[$prop]  ?? (in_array($prop, self::$integerProperties) ? 0 : '');
            if (in_array($prop, self::$integerProperties)) {
                $this->$prop = !empty($value) ? (int)$value : 0;
            } else {
                $this->$prop = !empty($value) ? (string)$value : '';
            }
        }
    }

    /** @return string GET Nombre-razón social del destinatario */
    public function getNombreRazon(): string { return $this->nombreRazon; }
    /** @param string $newValue SET Nuevo Nombre-razón social del destinatario */
    public function setNombreRazon(string $newValue): void { $this->nombreRazon = $newValue; }

    /** @return string GET Identificador del NIF del destinatario */
    public function getNif(): string { return $this->nif; }
    /** @param string $newValue SET Nuevo Identificador del NIF del destinatario */
    public function setNif(string $newValue): void { $this->nif = $newValue; }

    /** @return string GET Código del país del destinatario. */
    public function getOtroCodPais(): string { return $this->otroCodPais; }
    /** @param string $newValue SET Nuevo código del país del destinatario. */
    public function setOtroCodPais(string $newValue): void { $this->otroCodPais = $newValue; }

    /** @return int|string GET Clave para establecer el tipo de identificación del país de residencia del destinatario */
    public function getOtroIDType(): int|string { return $this->otroIDType; }
    /** @param int|string $newValue SET Nuevo Clave para establecer el tipo de identificación del país de residencia del destinatario */
    public function setOtroIDType(int|string $newValue): void { $this->otroIDType = $newValue; }

    /** @return string GET Número de identificación en el país de residencia del destinatario */
    public function getOtroID(): string { return $this->otroID; }
    /** @param string $newValue SET Nuevo Número de identificación en el país de residencia del destinatario */
    public function setOtroID(string $newValue): void { $this->otroID = $newValue; }

    /**
     * Retorna las variables en formato array
     * @return array Array con los datos del destinatario
     */
    public function getArrayData(): array {
        $data = [];
        foreach(self::$APIids as $prop => $apiId) {
            if (!empty($this->$prop) ) {
                $data[$apiId] = $this->$prop;
            }
        }
        return $data;
    }

    /**
     * Crea y retorna un objeto Destinatario vacío
     * @return Destinatario Objeto Destinatario con valores por defecto
     */
    public static function empty(): Destinatario {
        return new Destinatario([
            'nombreRazon' => '',
            'nif' => '',
            'otroCodPais' => '',
            'otroIDType' => 0,
            'otroID' => ''
        ]);
    }
}
?>