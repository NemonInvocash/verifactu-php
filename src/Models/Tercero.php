<?php
namespace verifactuPHP\Models;

/**
 * Clase que representa el tercero de una factura
 * @package: verifactuPHP\Models
 * @see: https://verifactuapi.es/ 
 */
class Tercero
{
    /**
     * @var string $tercNombreRazon Nombre-razón social del tercero que expida la factura
     * @var string $tercNIF  Identificador del NIF del tercero que expida la factura
     * @var string $tercOtroCodPais Código del país del tercero que expida la factura
     * @var int|string $tercOtroIDType Clave para establecer el tipo de identificación del tercero en el país de residencia ->lista L7
     * @var string $tercOtroID Número de identificación del tercero en el país de residencia
     */
    private string $tercNombreRazon;
    private string $tercNIF;
    private string $tercOtroCodPais;
    private int|string $tercOtroIDType;
    private string $tercOtroID;

    /** @var array Mapeo de propiedades a IDs de API */
    private static array $APIids = [
        'tercNombreRazon' => 'TercNombreRazon',
        'tercNIF' => 'TercNIF',
        'tercOtroCodPais' => 'TercOtroCodPais',
        'tercOtroIDType' => 'TercOtroIDType',
        'tercOtroID' => 'TercOtroID'
    ];

    /** @var array Propiedades que son enteros */
    private static array $integerProperties = [
        'tercOtroIDType'
    ];  

    /**
     * Constructor de la clase Tercero
     * @param array $data Array con los datos del tercero
     */
    public function __construct(array $data){
        foreach(self::$APIids as $prop => $apiId) {
            $value = $data[$prop] ?? (in_array($prop, self::$integerProperties) ? 0 : '');
            if (in_array($prop, self::$integerProperties)) {
                $this->$prop = !empty($value) ? (int)$value : 0;
            } else {
                $this->$prop = !empty($value) ? (string)$value : '';
            }
        }
    }

    /** @return string GET Nombre-razón social del tercero que expida la factura */
    public function getTercNombreRazon(): string { return $this->tercNombreRazon; }
    /** @param string $newValue SET Nuevo nombre-razón social del tercero que expida la factura */
    public function setTercNombreRazon(string $newValue): void { $this->tercNombreRazon = $newValue; }

    /** @return string GET Identificador del NIF del tercero que expida la factura */
    public function getTercNIF(): string { return $this->tercNIF; }
    /** @param string $newValue SET Nuevo identificador del NIF del tercero que expida la factura */
    public function setTercNIF(string $newValue): void { $this->tercNIF = $newValue; }

    /** @return string GET Código del país del tercero que expida la factura */
    public function getTercOtroCodPais(): string { return $this->tercOtroCodPais; }
    /** @param string $newValue SET Nuevo código del país del tercero que expida la factura */
    public function setTercOtroCodPais(string $newValue): void { $this->tercOtroCodPais = $newValue; }

    /** @return int|string GET Clave para establecer el tipo de identificación del tercero en el país de residencia ->lista L7 */
    public function getTercOtroIDType(): int|string { return $this->tercOtroIDType; }
    /** @param int|string $newValue SET Nuevo Clave para establecer el tipo de identificación del tercero en el país de residencia ->lista L7 */
    public function setTercOtroIDType(int|string $newValue): void { $this->tercOtroIDType = $newValue; }

    /** @return string GET Número de identificación del tercero en el país de residencia */ 
    public function getTercOtroID(): string { return $this->tercOtroID; }
    /** @param string $newValue SET Nuevo Número de identificación del tercero en el país de residencia */
    public function setTercOtroID(string $newValue): void { $this->tercOtroID = $newValue; }

    /**
     * Retorna las variables en formato array
     * @return array Array con los datos del tercero
     */
    public function getArrayData(): array {
        $data = [];
        foreach(self::$APIids as $prop => $apiId) {
            if (!empty($this->$prop)) {
                $data[$apiId] = $this->$prop;
            }
        }
        return $data;
    }

    /**
     * Crea y retorna un objeto Tercero vacío
     * @return Tercero Objeto Tercero con valores por defecto
     */
    public static function empty(): Tercero {
        return new Tercero([
            'tercNombreRazon' => '',
            'tercNIF' => '',
            'tercOtroCodPais' => '',
            'tercOtroIDType' => 0,
            'tercOtroID' => ''
        ]);
    }
}