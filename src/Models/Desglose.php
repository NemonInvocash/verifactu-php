<?php
namespace verifactuPHP\Models;

/**
 * Clase que representa el desglose de una factura
 * @package: verifactuPHP\Models
 * @see: https://verifactuapi.es/ 
 */
class Desglose
{
    /**
     * @var int|string $impuesto Impuesto aplicado en el desglose ->lista L1
     * @var int|string $claveRegimen Clave del régimen fiscal ->lista L8A
     * @var int|string $claveRegimenIGIC Clave del régimen IGIC ->lista L8B
     * @var int|string $calificacionOperacion Calificación de la operación ->lista L9
     * @var int|string $operacionExenta Indica si la operación está exenta ->lista L10
     * @var float $tipoImpositivo Tipo impositivo aplicado
     * @var float $baseImponibleOImporteNoSujeto Base imponible o importe no sujeto
     * @var float $baseImponibleACoste Base imponible a coste
     * @var float $cuotaRepercutida Cuota repercutida
     * @var float $tipoRecargoEquivalencia Tipo de recargo de equivalencia
     * @var float $cuotaRecargoEquivalencia Cuota de recargo de equivalencia
     */
    private int|string $impuesto;
    private int|string $claveRegimen;
    private int|string $claveRegimenIGIC;
    private int|string $calificacionOperacion;
    private int|string $operacionExenta;
    private float $tipoImpositivo;
    private float $baseImponibleOImporteNoSujeto;
    private float $baseImponibleACoste;
    private float $cuotaRepercutida;
    private float $tipoRecargoEquivalencia;
    private float $cuotaRecargoEquivalencia;

    /** @var array Mapeo de propiedades a IDs de API */
    private static array $APIids = [
        'impuesto' => 'Impuesto',
        'claveRegimen' => 'ClaveRegimen',
        'claveRegimenIGIC' =>'ClaveRegimenIGIC', 
        'calificacionOperacion' => 'CalificacionOperacion',
        'operacionExenta' => 'OperacionExenta',
        'tipoImpositivo' => 'TipoImpositivo',
        'baseImponibleOImporteNoSujeto' => 'BaseImponibleOImporteNoSujeto',
        'baseImponibleACoste' => 'BaseImponibleACoste',
        'cuotaRepercutida' => 'CuotaRepercutida',
        'tipoRecargoEquivalencia' => 'TipoRecargoEquivalencia',
        'cuotaRecargoEquivalencia' => 'CuotaRecargoEquivalencia'
    ];

    /** @var array Propiedades que son enteros */
    private static array $integerProperties = [
        'impuesto',
        'claveRegimen',
        'calificacionOperacion',
        'operacionExenta',
        'claveRegimenIGIC'
    ];

    /**
     * Constructor de la clase Desglose
     * @param array $data Array con los datos del desglose
     */
    public function __construct(array $data){
        foreach(self::$APIids as $prop => $apiId) {
            $value = $data[$prop] ?? (in_array($prop, self::$integerProperties) ? 0 : '');
            if (in_array($prop, self::$integerProperties)) {
                $this->$prop = !empty($value) ? (int)$value : 0;
            } else {
                $this->$prop = !empty($value) ? (float)$value : 0.0;
            }
        }
    }

    /** @return int|string GET Impuesto */
    public function getImpuesto(): int|string { return $this->impuesto; }
    /** @param int|string $newValue SET Nuevo impuesto */
    public function setImpuesto(int|string $newValue): void { $this->impuesto = $newValue; }

    /** @return int|string GET Clave del régimen fiscal */
    public function getClaveRegimen(): int|string { return $this->claveRegimen; }
    /** @param int|string $newValue SET Nuevo clave del régimen fiscal */
    public function setClaveRegimen(int|string $newValue): void { $this->claveRegimen = $newValue; }

    /** @return int|string GET Clave del régimen IGIC */
    public function getClaveRegimenIGIC(): int|string { return $this->claveRegimenIGIC; }
    /** @param int|string $newValue SET Nuevo clave del régimen IGIC */
    public function setClaveRegimenIGIC(int|string $newValue): void { $this->claveRegimenIGIC = $newValue; }

    /** @return int|string GET Calificación de la operación */
    public function getCalificacionOperacion(): int|string { return $this->calificacionOperacion; }
    /** @param int|string $newValue SET Nuevo calificación de la operación */
    public function setCalificacionOperacion(int|string $newValue): void { $this->calificacionOperacion = $newValue; }

    /** @return int|string GET Operación exenta */
    public function getOperacionExenta(): int|string { return $this->operacionExenta; }
    /** @param int|string $newValue SET Nuevo operación exenta */
    public function setOperacionExenta(int|string $newValue): void { $this->operacionExenta = $newValue; }

    /** @return float GET Tipo impositivo */
    public function getTipoImpositivo(): float { return $this->tipoImpositivo; }
    /** @param float $newValue SET Nuevo tipo impositivo */
    public function setTipoImpositivo(float $newValue): void { $this->tipoImpositivo = $newValue; }

    /** @return float GET Base imponible o importe no sujeto */
    public function getBaseImponibleOImporteNoSujeto(): float { return $this->baseImponibleOImporteNoSujeto; }
    /** @param float $newValue SET Nuevo base imponible o importe no sujeto */
    public function setBaseImponibleOImporteNoSujeto(float $newValue): void { $this->baseImponibleOImporteNoSujeto = $newValue; }

    /** @return float GET Base imponible a coste */
    public function getBaseImponibleACoste(): float { return $this->baseImponibleACoste; }
    /** @param float $newValue SET Nuevo base imponible a coste */
    public function setBaseImponibleACoste(float $newValue): void { $this->baseImponibleACoste = $newValue; }

    /** @return float GET Cuota repercutida */
    public function getCuotaRepercutida(): float { return $this->cuotaRepercutida; }
    /** @param float $newValue SET Nuevo cuota repercutida */
    public function setCuotaRepercutida(float $newValue): void { $this->cuotaRepercutida = $newValue; }

    /** @return float GET Tipo de recargo de equivalencia */
    public function getTipoRecargoEquivalencia(): float { return $this->tipoRecargoEquivalencia; }
    /** @param float $newValue SET Nuevo tipo de recargo de equivalencia */
    public function setTipoRecargoEquivalencia(float $newValue): void { $this->tipoRecargoEquivalencia = $newValue; }

    /** @return float GET Cuota de recargo de equivalencia */
    public function getCuotaRecargoEquivalencia(): float { return $this->cuotaRecargoEquivalencia; }
    /** @param float $newValue SET Nuevo cuota de recargo de equivalencia */
    public function setCuotaRecargoEquivalencia(float $newValue): void { $this->cuotaRecargoEquivalencia = $newValue; }

    /**
     * Retorna las variables en formato array
     * @return array Array con los datos del desglose
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
     * Crea y retorna un objeto Desglose vacío
     * @return Desglose Objeto Desglose con valores por defecto
     */
    public static function empty(): Desglose {
        return new Desglose([
            'impuesto' => 0,
            'claveRegimen' => 0,
            'claveRegimenIGIC' => 0,
            'calificacionOperacion' => 0,
            'operacionExenta' => 0,
            'tipoImpositivo' => 0.0,
            'baseImponibleOImporteNoSujeto' => 0.0,
            'baseImponibleACoste' => 0.0,
            'cuotaRepercutida' => 0.0,
            'tipoRecargoEquivalencia' => 0.0,
            'cuotaRecargoEquivalencia' => 0.0
        ]);
    }
}
?>