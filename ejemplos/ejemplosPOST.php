<?php

include_once __DIR__ ."/../vendor/autoload.php";
include_once __DIR__ ."/../src/ClienteVerifactu.php";

//ACTIVAR LAS OPCIONES QUE SE QUIEREN PROBAR
$altaEmisor = false;
$registrosAlta = true;
$registrosAnulacion = false;

// Las siguientes funciones permiten 
// - dar de alta un emisor en la API de Verifactu.
// - registrar un nuevo registro de alta.
// - registrar una anulación de un registro existente.

try {
	//Iniciamos el cliente 
	$obj = new verifactuPHP\ClienteVerifactu( "ejemplo@verifactuAPI.com" , '#9e2q$v@bG');
	$obj->setDebug(true); //debug activado

    
    //ALTA DE EMISOR
    //Creamos un emisor:
    $dataEmisor = [
        'nif' => "A39200019", // string  El nif tiene que ser un nif valido.
        'nombre' => "EMPRESA TEST", // string
        'representanteRazonSocial' => '', // string
        'representanteNif' => '', // string
        'webhookID' => 10, // int
        'enviarAeat' => true, // bool
        'enviarAeatPaused' => false // bool
    ];
    //Creamos el emisor a traves del cliente.
    $emisor = $obj->nuevoEmisor($dataEmisor);
    //Damos de alta el emisor en la API de Verifactu.
    if($altaEmisor){
        $obj->altaEmisor($emisor);
    }

    //REGISTRO ALTA
    if($registrosAlta){
        //Reutilizaremos el emisor creado anteriormente
        //Y creamos los objetos auxiliares que necesitamos:
        $dataDestinatario = [
            'nombreRazon' => "NOMBRE EJEMPLO", // string
            'nif' => "39707287H", // string
            'otroCodPais' => "ES", // string
            'otroIDType' => 1, // int string
            'otroID' => "" // string
        ];
        $destinatario = $obj->nuevoDestinatario($dataDestinatario);	

        $dataTercero = [
            'tercNombreRazon' => "NOMBRE EJEMPLO", // string
            'tercNIF' => "39707287H", // string
            'tercOtroCodPais' => "ES", // string
            'tercOtroIDType' => 1, // int string
            'tercOtroID' => "" // string
        ];
        $tercero = $obj->nuevoTercero($dataTercero);

        $dataDesglose = [
            'impuesto' => "01", // int
            'claveRegimen' => 1, // int string  
            'claveRegimenIGIC' => 1, // int string
            'calificacionOperacion' => 1, // int string
            'operacionExenta' => 0, // int string
            'tipoImpositivo' => 21, // float
            'baseImponibleOImporteNoSujeto' => 100, // float
            'baseImponibleACoste' => 100, // float
            'cuotaRepercutida' => 21, // float
            'tipoRecargoEquivalencia' => 0, // float
            'cuotaRecargoEquivalencia' => 0 // float
        ];
        $desglose = $obj->nuevoDesglose($dataDesglose);

        //Una vez creados los objetos auxiliares, creamos el registro de alta.

        //Ejemplo registro de alta (FACTURA NORMAL):
        //Array con los objetos necesarios para crear un registro de alta
        $objetosRegistroAlta = [
            'emisor' => $emisor,
            'destinatario' => $destinatario,
            'desglose' => $desglose,
            'tercero' => $tercero,
            //'facturaRectificada' => $facturaRectificada,
            //'facturaSustituida' => $facturaSustituida
        ];
        //Array con los datos para crear nuestro registro de alta
        $dataRegistroAlta = [
            'numSerieFactura' => "AX/202412-118", // string
            'fechaExpedicionFactura' => "2024-12-16", // string
            'refExterna' => "Test Ref Externa", // string
            'tipoFactura' => "F1", // int string
            'cuotaTotal' => 21.0, // float
            'importeTotal' => 121.0 // float    
        ];
        //Creamos el registro de alta a traves del cliente
        $registroAlta = $obj->nuevoRegistroAlta($objetosRegistroAlta, $dataRegistroAlta);
        //Damos de alta el registro en la API de Verifactu
        $obj->altaRegistroAlta($registroAlta);

        //Ejemplo registro de alta (FACTURA SIMPLE):
        //Array con los objetos para crear nuestro registro de alta
        $objetosRegistroAlta = [
            'emisor' => $emisor,
            'desglose' => $desglose,
        ];
        //Array con los datos para crear nuestro registro de alta
        $dataRegistroAlta = [
            'numSerieFactura' => "AX/202412-119", // string
            'fechaExpedicionFactura' => "2024-12-16", // string
            'refExterna' => "Test Ref Externa", // string
            'tipoFactura' => "F2", // int string
            'facturaSinIdentifDestinatarioArt61d' => "S", // int string
            'cuotaTotal' => 21.0, // float
            'importeTotal' => 121.0 // float    
        ];
        //Creamos el registro de alta a traves del cliente
        $registroAltaSimple = $obj->nuevoRegistroAlta($objetosRegistroAlta, $dataRegistroAlta);
        //Damos de alta el registro en la API de Verifactu
        $obj->altaRegistroAlta($registroAltaSimple);
        
        //Ejemplo registro de alta (FACTURA RECTIFICATIVA):
        
        //Creamos una factura rectificada
        $dataFacturaRectificada = [
            'IDEmisorFactura' => $registroAltaSimple->getIDEmisorFactuara(),
            'numSerieFactura' => $registroAltaSimple->getNumSerieFactura(),
            'fechaExpedicionFactura' => $registroAltaSimple->getFechaExpedicionFactura(),  
        ];
        $facturaRectificada = $obj->nuevaFacturaRectificada($dataFacturaRectificada);
        
        //Creamos el registro de alta rectificativo:
        //Array con los objetos para crear nuestro registro de alta
        $objetosRegistroAlta = [
            'emisor' => $emisor,
            'desglose' => $desglose,
            'facturaRectificada' => $facturaRectificada
        ];
        //Array con los datos para crear nuestro registro de alta
        $dataRegistroAlta = [
            'numSerieFactura' => "AX/202412-120", // string
            'fechaExpedicionFactura' => "2024-12-16", // string
            'refExterna' => "Test Ref Externa", // string
            'tipoFactura' => "R5", // int string
            'tipoRectificativa' => 2, // int string
            'cuotaTotal' => 21, // float
            'importeTotal' => 121 // float    
        ];  
        //Creamos el registro de alta a traves del cliente
        $registroAltaRectificativa = $obj->nuevoRegistroAlta($objetosRegistroAlta, $dataRegistroAlta);
        //Damos de alta el registro en la API de Verifactu
        $obj->altaRegistroAlta($registroAltaRectificativa);
    }
    
    //REGISTRO ANULACION
    if($registrosAnulacion){
        //Ejemplo dar de baja un registro usando el id.
        $obj->bajaRegistro(39);

        //Ejemplo dar de baja un registro usando sus datos.
        $dataRegistroAnulacion = [
            'IDEmisorFactura' => "A39200019", // string
            'numSerieFactura' => "AX/202412-075", // string
            'fechaExpedicionFactura' => "2024-12-16", // string
            'refExterna' => "Test Ref Externa", // string
        ];
        //Creamos el registro de anulacion a traves del cliente
        $registroAnulacion = $obj->nuevoRegistroAnulacion($dataRegistroAnulacion);
        //Damos de alta el registro de anulacion en la API de Verifactu.
        $obj->altaRegistroAnulacion($registroAnulacion);
    }   
} catch (Exception $e) {
	echo $e->getMessage();
} 