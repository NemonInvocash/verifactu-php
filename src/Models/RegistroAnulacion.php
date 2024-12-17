<?php
namespace verifactuPHP\Models;

/**
 * Clase que representa el registro de anulacion de una factura
 * @package: verifactuPHP\Models
 * @see: https://verifactuapi.es/ 
 */
class RegistroAnulacion
{

    /**
     * @var string $IDEmisorFactura ID del emisor de la factura
     * @var string $numSerieFactura Número de serie de la factura
     * @var string $fechaExpedicionFactura Fecha de expedición de la factura
     * @var string $refExterna Referencia externa de la factura
     * @var int|string $sinRegistroPrevio Indica si no hay registro previo ->lista L4
     * @var int|string $rechazoPrevioA Indica si hay rechazo previo ->lista L4
     * @var int|string $generadoPor Indica quién generó la factura ->lista L16
     * @var string $generadorNombreRazon Nombre de la razón del generador 
     * @var string $generadorNIF NIF del generador
     * @var string $generadorOtroCodPais Código de país del generador
     * @var int|string $generadorOtroIDType Tipo de ID del generador ->lista L7
     * @var string $generadorOtroID ID del generador
     */
    private string $IDEmisorFactura;
    private string $numSerieFactura;
    private string $fechaExpedicionFactura;
    private string $refExterna;
    private int|string $sinRegistroPrevio;
    private int|string $rechazoPrevioA;
    private int|string $generadoPor;
    private string $generadorNombreRazon;
    private string $generadorNIF;
    private string $generadorOtroCodPais;
    private int|string $generadorOtroIDType;
    private string $generadorOtroID;

     /** @var array Mapeo de propiedades a IDs de API */
     private static array $APIids = [
        'IDEmisorFactura' => 'IDEmisorFactura',
        'numSerieFactura' => 'NumSerieFactura',
        'fechaExpedicionFactura' => 'FechaExpedicionFactura',
        'refExterna' => 'RefExterna',
        'sinRegistroPrevio' => 'SinRegistroPrevio',
        'rechazoPrevioA' => 'RechazoPrevioA',
        'generadoPor' => 'GeneradoPor',
        'generadorNombreRazon' =>  'Generador_NombreRazon',
        'generadorNIF' => 'Generador_NIF',
        'generadorOtroCodPais' => 'Generador_OtroCodPais',
        'generadorOtroIDType' => 'Generador_OtroIDType',
        'generadorOtroID' => 'Generador_OtroID',
    ];

    /** @var array Propiedades que son enteros */
    private static array $integerProperties = [
        'sinRegistroPrevio',
        'rechazoPrevioA',
        'generadoPor',
        'generadorOtroIDType'
    ];

    /**
     * Constructor de la clase RegistroAnulacion
     * @param array $data Array con los datos del registro de anulacion
     */
    public function __construct(array $data){ 

        foreach(self::$APIids as $prop => $apiId) {
            $value = $data[$prop] ?? '';
            if (in_array($prop, self::$integerProperties)) {
                $this->$prop = !empty($value) ? (int)$value : 0;
            }  else {
                $this->$prop = !empty($value) ? (string)$value : '';
            }
        }
    }

    /** @return string GET ID del emisor de la factura */
    public function getIDEmisorFactura(): string { return $this->IDEmisorFactura; }
    /** @param string $newValue SET Nuevo ID del emisor de la factura */
    public function setIDEmisorFactura(string $newValue): void { $this->IDEmisorFactura = $newValue; }

    /** @return string GET Número de serie de la factura */
    public function getNumSerieFactura(): string { return $this->numSerieFactura; }
    /** @param string $newValue SET Nuevo número de serie de la factura */
    public function setNumSerieFactura(string $newValue): void { $this->numSerieFactura = $newValue; }

    /** @return string GET Fecha de expedición de la factura */
    public function getFechaExpedicionFactura(): string { return $this->fechaExpedicionFactura; }
    /** @param string $newValue SET Nueva fecha de expedición de la factura */
    public function setFechaExpedicionFactura(string $newValue): void { $this->fechaExpedicionFactura = $newValue; }

    /** @return string GET Referencia externa de la factura */
    public function getRefExterna(): string { return $this->refExterna; }
    /** @param string $newValue SET Nueva referencia externa de la factura */
    public function setRefExterna(string $newValue): void { $this->refExterna = $newValue; }

    /** @return int|string GET Indica si no hay registro previo */
    public function getSinRegistroPrevio(): int|string { return $this->sinRegistroPrevio; }
    /** @param int|string $newValue SET Nuevo valor de sin registro previo */
    public function setSinRegistroPrevio(int|string $newValue): void { $this->sinRegistroPrevio = $newValue; }

    /** @return int|string GET Indica si hay rechazo previo */
    public function getRechazoPrevioA(): int|string { return $this->rechazoPrevioA; }
    /** @param int|string $newValue SET Nuevo valor de rechazo previo */
    public function setRechazoPrevioA(int|string $newValue): void { $this->rechazoPrevioA = $newValue; }

    /** @return int|string GET Indica quién generó la factura */
    public function getGeneradoPor(): int|string { return $this->generadoPor; }
    /** @param int|string $newValue SET Nuevo valor de generado por */
    public function setGeneradoPor(int|string $newValue): void { $this->generadoPor = $newValue; }

    /** @return string GET Nombre de la razón del generador */
    public function getGeneradorNombreRazon(): string { return $this->generadorNombreRazon; }
    /** @param string $newValue SET Nuevo nombre de la razón del generador */
    public function setGeneradorNombreRazon(string $newValue): void { $this->generadorNombreRazon = $newValue; }

    /** @return string GET NIF del generador */
    public function getGeneradorNIF(): string { return $this->generadorNIF; }
    /** @param string $newValue SET Nuevo NIF del generador */
    public function setGeneradorNIF(string $newValue): void { $this->generadorNIF = $newValue; }

    /** @return string GET Código de país del generador */
    public function getGeneradorOtroCodPais(): string { return $this->generadorOtroCodPais; }
    /** @param string $newValue SET Nuevo código de país del generador */
    public function setGeneradorOtroCodPais(string $newValue): void { $this->generadorOtroCodPais = $newValue; }

    /** @return int|string GET Tipo de ID del generador */
    public function getGeneradorOtroIDType(): int|string { return $this->generadorOtroIDType; }
    /** @param int|string $newValue SET Nuevo tipo de ID del generador */
    public function setGeneradorOtroIDType(int|string $newValue): void { $this->generadorOtroIDType = $newValue; }

    /** @return string GET ID del generador */
    public function getGeneradorOtroID(): string { return $this->generadorOtroID; }
    /** @param string $newValue SET Nuevo ID del generador */
    public function setGeneradorOtroID(string $newValue): void { $this->generadorOtroID = $newValue; }
    
     /**
     * Retorna las variables en formato array
     * @return array Array con los datos del registro de anulacion
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
     * Retorna un registro de anulacion vacío
     * @return RegistroAnulacion Registro de anulacion vacío
     */
    public static function empty(): RegistroAnulacion {
        return new RegistroAnulacion([
            'IDEmisorFactura' => '',
            'numSerieFactura' => '',
            'fechaExpedicionFactura' => '',
            'refExterna' => '',
            'sinRegistroPrevio' => 0,
            'rechazoPrevioA' => 0,
            'generadoPor' => 0,
            'generadorNombreRazon' => '',
            'generadorNIF' => '',
            'generadorOtroCodPais' => '',
            'generadorOtroIDType' => 0,
            'generadorOtroID' => ''
        ]);
    }
}
?>
