<?php
namespace verifactuPHP\Models;

/**
 * Clase que representa la factura rectificada
 * @package: verifactuPHP\Models
 * @see: https://verifactuapi.es/ 
 */
class FacturaRectificada
{

    /**
     * @var string $IDEmisorFactura ID del emisor de la factura
     * @var string $numSerieFactura Número de serie de la factura
     * @var string $fechaExpedicionFactura Fecha de expedición de la factura
     */
    private string $IDEmisorFactura;
    private string $numSerieFactura;
    private string $fechaExpedicionFactura;
    
    /** @var array Mapeo de propiedades a IDs de API */
    private static array $APIids = [
        'IDEmisorFactura' => 'IDEmisorFactura',
        'numSerieFactura' => 'NumSerieFactura',
        'fechaExpedicionFactura' => 'FechaExpedicionFactura',
    ];

    /**
     * Constructor de la clase FacturaRectificada
     * @param array $data Array con los datos de la factura rectificada
     */
    public function __construct(array $data){
        foreach(self::$APIids as $prop => $apiId) {
            $value = $data[$prop] ?? '';
            $this->$prop = !empty($value) ? (string)$value : '';
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

     /**
     * Retorna las variables en formato array
     * @return array Array con los datos de la factura rectificada
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
     * Crea y retorna un objeto FacturaRectificada vacío
     * @return FacturaRectificada Objeto FacturaRectificada con valores por defecto
     */
    public static function empty(): FacturaRectificada {
        return new FacturaRectificada([
            'IDEmisorFactura' => '',
            'numSerieFactura' => '',
            'fechaExpedicionFactura' => '',
        ]);
    }
}
?>