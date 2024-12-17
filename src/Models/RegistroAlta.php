<?php
namespace verifactuPHP\Models;

/**
 * Clase que representa el registro de alta de una factura
 * @package: verifactuPHP\Models
 * @see: https://verifactuapi.es/ 
 */
class RegistroAlta
{

    /**
     * @var Emisor $emisor Emisor de la factura
     * @var Destinatario $destinatario Destinatario de la factura
     * @var Desglose $desglose Desglose de la factura
     * @var Tercero $tercero Tercero de la factura
     * @var FacturaRectificada $facturaRectificada Factura rectificada
     * @var FacturaSustituida $facturaSustituida Factura sustituida
     * @var string $IDEmisorFactuara ID del emisor de la factura
     * @var string $numSerieFactura Número de serie de la factura
     * @var string $fechaExpedicionFactura Fecha de expedición de la factura
     * @var string $refExterna Referencia externa de la factura
     * @var string $nombreRazonEmisor Nombre de la razón del emisor
     * @var int|string $subsanacion Indica si hay subsanación ->lista L4
     * @var int|string $rechazoPrevio Indica si hay rechazo previo ->lista L17
     * @var int|string $tipoFactura Tipo de factura ->lista L2
     * @var int|string $tipoRectificativa Tipo de rectificativa ->lista L3
     * @var float $baseRectificada Base rectificada
     * @var float $cuotaRectificada Cuota rectificada
     * @var float $cuotaRecargoRectificado Cuota de recargo rectificado
     * @var string $fechaOperacion Fecha de la operación
     * @var string $descripcionOperacion Descripción de la operación
     * @var int|string $facturaSimplificadaArt7273 Indica si es factura simplificada art 7273 ->lista L4
     * @var int|string $facturaSinIdentifDestinatarioArt61d Indica si es factura sin identif destinatario art 61d ->lista L5
     * @var int|string $macrodato Indica si es macrodato ->lista L14
     * @var int|string $emitidaPorTercODestinatario Indica si es emitida por tercero o destinatario ->lista L6
     * @var int|string $cupon Indica si es cupón ->lista L4
     * @var float $cuotaTotal Cuota total
     * @var float $importeTotal Importe total
     * @var int $webhookID ID del webhook
     */
    private Emisor $emisor;
    private Destinatario $destinatario;
    private Desglose $desglose;
    private Tercero $tercero;
    private FacturaRectificada $facturaRectificada;
    private FacturaSustituida $facturaSustituida;
    private string $IDEmisorFactuara;
    private string $numSerieFactura;
    private string $fechaExpedicionFactura;
    private string $refExterna;
    private string $nombreRazonEmisor;
    private int|string $subsanacion;
    private int|string $rechazoPrevio;
    private int|string $tipoFactura;
    private int|string $tipoRectificativa;
    private float $baseRectificada;
    private float $cuotaRectificada;
    private float $cuotaRecargoRectificado;
    private string $fechaOperacion;
    private string $descripcionOperacion;
    private int|string $facturaSimplificadaArt7273;
    private int|string $facturaSinIdentifDestinatarioArt61d;
    private int|string $macrodato;
    private int|string $emitidaPorTercODestinatario;
    private int|string $cupon;
    private float $cuotaTotal;
    private float $importeTotal;
    private int $webhookID;

     /** @var array Mapeo de propiedades a IDs de API */
     private static array $APIids = [
        'IDEmisorFactuara' => 'IDEmisorFactura',
        'numSerieFactura' => 'NumSerieFactura',
        'fechaExpedicionFactura' => 'FechaExpedicionFactura',
        'refExterna' => 'RefExterna',
        'nombreRazonEmisor' => 'NombreRazonEmisor',
        'subsanacion' => 'Subsanacion',
        'rechazoPrevio' => 'RechazoPrevio',
        'tipoFactura' => 'TipoFactura',
        'tipoRectificativa' => 'TipoRectificativa',
        'baseRectificada' => 'BaseRectificada',
        'cuotaRectificada' => 'CuotaRectificada',
        'cuotaRecargoRectificado' => 'CuotaRecargoRectificado',
        'fechaOperacion' => 'FechaOperacion',
        'descripcionOperacion' => 'DescripcionOperacion',
        'facturaSimplificadaArt7273' => 'FacturaSimplificadaArt7273',
        'facturaSinIdentifDestinatarioArt61d' => 'FacturaSinIdentifDestArt61d',
        'macrodato' => 'Macrodato',
        'emitidaPorTercODestinatario' => 'EmitidaPorTercODesti',
        'cupon' => 'Cupon',
        'cuotaTotal' => 'CuotaTotal',
        'importeTotal' => 'ImporteTotal',
        'webhookID' => 'webhook_id'
    ];

    /** @var array Propiedades que son enteros */
    private static array $integerProperties = [
        'webhookID'
    ];
    
    /** @var array Propiedades que son flotantes */
    private static array $floatProperties = [
        'baseRectificada',
        'cuotaRectificada',
        'cuotaRecargoRectificado',
        'cuotaTotal',
        'importeTotal'
    ];

    /**
     * Constructor de la clase RegistroAlta
     * @param array $objetos Array con los objetos del registro de alta
     * @param array $data Array con los datos del registro de alta
     * @param int $webhookID ID del webhook
     */
    public function __construct(array $objetos, array $data, int $webhookID){ 
        $this->emisor = $objetos['emisor'] ?? Emisor::empty();
        $this->destinatario = $objetos['destinatario'] ?? Destinatario::empty();
        $this->desglose = $objetos['desglose'] ?? Desglose::empty();
        $this->tercero = $objetos['tercero'] ?? Tercero::empty();
        $this->facturaRectificada = $objetos['facturaRectificada'] ?? FacturaRectificada::empty();
        $this->facturaSustituida = $objetos['facturaSustituida'] ?? FacturaSustituida::empty();

        foreach(self::$APIids as $prop => $apiId) {
            $value = $data[$prop] ?? '';
            if (in_array($prop, self::$integerProperties)) {
                $this->$prop = !empty($value) ? (int)$value : 0;
            } elseif (in_array($prop, self::$floatProperties)) {
                $this->$prop = !empty($value) ? (float)$value : 0.0;
            } else {
                if (is_string($value)) {
                    $this->$prop = !empty($value) ? (string)$value : '';
                } else {
                    $this->$prop = !empty($value) ? (int)$value : 0;
                }
            }
        }

        $this->webhookID = $webhookID ?: $this->emisor->getWebhookID();
        $this->IDEmisorFactuara = $this->emisor->getNif();
        $this->nombreRazonEmisor = $this->emisor->getNombre();
    }

    /** @return Emisor GET Emisor de la factura */  
    public function getEmisor(): Emisor { return $this->emisor; }
    /** @param Emisor $newValue SET Nuevo emisor de la factura */
    public function setEmisor(Emisor $newValue): void { 
        $this->emisor = $newValue;
        $this->IDEmisorFactuara = $this->emisor->getNif();
        $this->nombreRazonEmisor = $this->emisor->getNombre();
    }   

    /** @return Destinatario GET Destinatario de la factura */
    public function getDestinatario(): Destinatario { return $this->destinatario; }
    /** @param Destinatario $newValue SET Nuevo destinatario de la factura */
    public function setDestinatario(Destinatario $newValue): void { $this->destinatario = $newValue; }

    /** @return Desglose GET Desglose de la factura */
    public function getDesglose(): Desglose { return $this->desglose; }
    /** @param Desglose $newValue SET Nuevo desglose de la factura */
    public function setDesglose(Desglose $newValue): void { $this->desglose = $newValue; }

    /** @return Tercero GET Tercero de la factura */
    public function getTercero(): Tercero { return $this->tercero; }
    /** @param Tercero $newValue SET Nuevo tercero de la factura */
    public function setTercero(Tercero $newValue): void { $this->tercero = $newValue; }

    /** @return FacturaRectificada GET Factura rectificada */
    public function getFacturaRectificada(): FacturaRectificada { return $this->facturaRectificada; }
    /** @param FacturaRectificada $newValue SET Nueva factura rectificada */
    public function setFacturaRectificada(FacturaRectificada $newValue): void { $this->facturaRectificada = $newValue; }

    /** @return FacturaSustituida GET Factura sustituida */
    public function getFacturaSustituida(): FacturaSustituida { return $this->facturaSustituida; }
    /** @param FacturaSustituida $newValue SET Nueva factura sustituida */
    public function setFacturaSustituida(FacturaSustituida $newValue): void { $this->facturaSustituida = $newValue; }

    /** @return int GET ID del webhook */
    public function getWebhookID(): int { return $this->webhookID; }
    /** @param int $newValue SET Nuevo ID del webhook */
    public function setWebhookID(int $newValue): void { $this->webhookID = $newValue; }

    /** @return string GET ID del emisor de la factura */
    public function getIDEmisorFactuara(): string { return $this->IDEmisorFactuara; }
    /** @param string $newValue SET Nuevo ID del emisor de la factura */
    public function setIDEmisorFactuara(string $newValue): void { $this->IDEmisorFactuara = $newValue; }

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

    /** @return int|string GET Tipo de factura */
    public function getTipoFactura(): int|string     { return $this->tipoFactura; }
    /** @param int|string $newValue SET Nuevo tipo de factura */
    public function setTipoFactura(int|string $newValue): void { $this->tipoFactura = $newValue; }

    /** @return int|string GET Subsanación */
    public function getSubsanacion(): int|string { return $this->subsanacion; }
    /** @param int|string $newValue SET Nueva subsanación */
    public function setSubsanacion(int|string $newValue): void { $this->subsanacion = $newValue; }

    /** @return int|string GET Rechazo previo */
    public function getRechazoPrevio(): int|string { return $this->rechazoPrevio; }
    /** @param int|string $newValue SET Nuevo rechazo previo */
    public function setRechazoPrevio(int|string $newValue): void { $this->rechazoPrevio = $newValue; }

    /** @return int|string GET Tipo de rectificativa */
    public function getTipoRectificativa(): int|string { return $this->tipoRectificativa; }
    /** @param int|string $newValue SET Nuevo tipo de rectificativa */
    public function setTipoRectificativa(int|string $newValue): void { $this->tipoRectificativa = $newValue; }

    /** @return int|string GET Factura simplificada art 7273 */
    public function getFacturaSimplificadaArt7273(): int|string { return $this->facturaSimplificadaArt7273; }
    /** @param int|string $newValue SET Nueva factura simplificada art 7273 */
    public function setFacturaSimplificadaArt7273(int|string $newValue): void { $this->facturaSimplificadaArt7273 = $newValue; }

    /** @return int|string GET Factura sin identif destinatario art 61d */
    public function getFacturaSinIdentifDestinatarioArt61d(): int|string { return $this->facturaSinIdentifDestinatarioArt61d; }
    /** @param int|string $newValue SET Nueva factura sin identif destinatario art 61d */
    public function setFacturaSinIdentifDestinatarioArt61d(int|string $newValue): void { $this->facturaSinIdentifDestinatarioArt61d = $newValue; }

    /** @return int|string GET Macrodato */
    public function getMacrodato(): int|string { return $this->macrodato; }
    /** @param int|string $newValue SET Nuevo macrodato */
    public function setMacrodato(int|string $newValue): void { $this->macrodato = $newValue; }

    /** @return int|string GET Emitida por tercero o destinatario */
    public function getEmitidaPorTercODestinatario(): int|string { return $this->emitidaPorTercODestinatario; }
    /** @param int|string $newValue SET Nueva emitida por tercero o destinatario */
    public function setEmitidaPorTercODestinatario(int|string $newValue): void { $this->emitidaPorTercODestinatario = $newValue; }

    /** @return int|string GET Cupon */
    public function getCupon(): int|string { return $this->cupon; }
    /** @param int|string $newValue SET Nuevo cupon */
    public function setCupon(int|string $newValue): void { $this->cupon = $newValue; }

    /** @return float GET Base rectificada */
    public function getBaseRectificada(): float { return $this->baseRectificada; }
    /** @param float $newValue SET Nueva base rectificada */
    public function setBaseRectificada(float $newValue): void { $this->baseRectificada = $newValue; }

    /** @return float GET Cuota rectificada */
    public function getCuotaRectificada(): float { return $this->cuotaRectificada; }
    /** @param float $newValue SET Nueva cuota rectificada */
    public function setCuotaRectificada(float $newValue): void { $this->cuotaRectificada = $newValue; }

    /** @return float GET Cuota de recargo rectificado */
    public function getCuotaRecargoRectificado(): float { return $this->cuotaRecargoRectificado; }
    /** @param float $newValue SET Nueva cuota de recargo rectificado */
    public function setCuotaRecargoRectificado(float $newValue): void { $this->cuotaRecargoRectificado = $newValue; }

    /** @return float GET Cuota total */
    public function getCuotaTotal(): float { return $this->cuotaTotal; }
    /** @param float $newValue SET Nueva cuota total */
    public function setCuotaTotal(float $newValue): void { $this->cuotaTotal = $newValue; }

    /** @return float GET Importe total */
    public function getImporteTotal(): float { return $this->importeTotal; }
    /** @param float $newValue SET Nuevo importe total */
    public function setImporteTotal(float $newValue): void { $this->importeTotal = $newValue; }

    /**
     * Retorna las variables en formato array
     * @return array Array con los datos del registro de alta
     */
    public function getArrayData(): array {
        $data = [];
        
        foreach(self::$APIids as $prop => $apiId) {
            if (!empty($this->$prop) ) {
                $data[$apiId] = $this->$prop;
            }
        }
        if (!empty($this->tercero) && !empty($this->tercero->getArrayData())) { 
            $data['Tercero'] = [$this->tercero->getArrayData()]; 
        }
        if (!empty($this->destinatario) && !empty($this->destinatario->getArrayData())) { 
            $data['Destinatarios'] = [$this->destinatario->getArrayData()]; 
        }
        if (!empty($this->desglose) && !empty($this->desglose->getArrayData())) { 
            $data['Desglose'] = [$this->desglose->getArrayData()]; 
        }
        if (!empty($this->facturaRectificada) && !empty($this->facturaRectificada->getArrayData())) { 
            $data['FacturasRectificadas'] = [$this->facturaRectificada->getArrayData()]; 
        }   
        if (!empty($this->facturaSustituida) && !empty($this->facturaSustituida->getArrayData())) { 
            $data['FacturasSustituidas'] = [$this->facturaSustituida->getArrayData()]; 
        }

        return $data;
    }

    /**
     * Crea y retorna un objeto RegistroAlta vacío
     * @return RegistroAlta Objeto RegistroAlta con valores por defecto
     */
    public static function empty(): RegistroAlta {
        return new RegistroAlta(
            [
                'emisor' => Emisor::empty(),
                'destinatario' => Destinatario::empty(),
                'desglose' => Desglose::empty(),
                'tercero' => Tercero::empty(),
                'facturaRectificada' => FacturaRectificada::empty(),
                'facturaSustituida' => FacturaSustituida::empty(),
            ],
            [
                'numSerieFactura' => '',
                'fechaExpedicionFactura' => '',
                'refExterna' => '',
                'nombreRazonEmisor' => '',
                'subsanacion' => 0,
                'rechazoPrevio' => 0,
                'tipoFactura' => 0,
                'tipoRectificativa' => 0,
                'baseRectificada' => 0.0,
                'cuotaRectificada' => 0.0,
                'cuotaRecargoRectificado' => 0.0,
                'fechaOperacion' => '',
                'descripcionOperacion' => '',
                'facturaSimplificadaArt7273' => 0,
                'facturaSinIdentifDestinatarioArt61d' => 0,
                'macrodato' => 0,
                'emitidaPorTercODestinatario' => 0,
                'cupon' => 0,
                'cuotaTotal' => 0.0,
                'importeTotal' => 0.0       
            ],
            0
        ); 
    }
}
?>
