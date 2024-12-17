<?php

include_once __DIR__ ."/../vendor/autoload.php";
include_once __DIR__ ."/../src/ClienteVerifactu.php";

//ACTIVAR LAS OPCIONES QUE SE QUIEREN PROBAR
$webhooks = false;
$emisores = true;
$destinatarios = true;
$terceros = true;
$facturaRectificada = true;
$facturaSustituida = true;
$desgloses = true;
$registrosAlta = true;
$registrosAnulacion = false;

// Las siguientes funciones permiten crear diferentes recursos en el cliente de Verifactu:
// - nuevoWebhook: Crea un nuevo webhook.
// - nuevoEmisor: Crea un nuevo emisor.
// - nuevoDestinatario: Crea un nuevo destinatario.
// - nuevoTercero: Crea un nuevo tercero.
// - nuevoDesglose: Crea un nuevo desglose.
// - nuevoRegistroAlta: Crea un nuevo registro de alta.
// - nuevoRegistroAnulacion: Crea un nuevo registro de anulacion.

//NOTA 1:
//No hay campos obligatorios a la hora de crear objetos, pero la API de Verifactu puede requerir que se proporcionen ciertos datos para crear un registro.
//Los valores que no se asignen a traves del array de datos, se dejan como empty.
//Los valores empty de un objeto no se envian a la API de Verifactu.

//NOTA 2:
//Para los valores que correspondan a alguna de las Listas (l1,l2...) se puede introducir tanto el ID del valor dentro la lista como el valor en si.
//Para ello puedes usar la funcion listarListas() que devuelve un array con los valores de las listas.

//NOTA 3:
//Puedes crear objetos vacios a traves de los metodos crearXVacio() del cliente Verifactu.
//Estos objetos vacios se pueden usar para crear registros, pero no se guardan en la base de datos de Verifactu.

try {
	//Iniciamos el cliente 
	$obj = new verifactuPHP\ClienteVerifactu( "ejemplo@verifactuAPI.com" , '#9e2q$v@bG');
	$obj->setDebug(true); //debug activado
	
    if($webhooks){
        //WEBHOOK - Cambiar por la URL de tu webhook
        $WH = $obj->createWebhook("https://webhook.site/766f4435-f4f9-414b-b26f-47dbd10339ce/{id}");
    }

    if($emisores){
        //EMISOR
        //Todos los valores que admite un emisor
        $dataEmisor = [
            'nif' => "S2819426D", // string
            'nombre' => "EMPRESA TEST", // string
            'representanteRazonSocial' => '', // string
            'representanteNif' => '', // string
            'webhookID' => 10, // int
            'enviarAeat' => true, // bool
            'enviarAeatPaused' => false // bool
        ];
        //Crear el emisor a traves del cliente
        $emisor = $obj->nuevoEmisor($dataEmisor);  
    }

    if($destinatarios){
        //DESTINATARIO
        //Todos los valores que admite un destinatario
        $dataDestinatario = [
            'nombreRazon' => "NOMBRE EJEMPLO", // string
            'nif' => "39707287H", // string
            'otroCodPais' => "ES", // string
            'otroIDType' => 1, // int/string -> Lista L7
            'otroID' => "" // string
        ];
        //Crear el destinatario a traves del cliente
        $destinatario = $obj->nuevoDestinatario($dataDestinatario);	
    }
    
    if($terceros){
        //TERCERO
        //Todos los valores que admite un tercero
        $dataTercero = [
            'tercNombreRazon' => "NOMBRE EJEMPLO", // string
            'tercNIF' => "39707287H", // string
            'tercOtroCodPais' => "ES", // string
            'tercOtroIDType' => 1, // int/string -> Lista L7
            'tercOtroID' => "" // string
        ];
        //Crear el tercero a traves del cliente
        $tercero = $obj->nuevoTercero($dataTercero);
    }

    if($facturaRectificada){
        //FACTURA RECTIFICADA
        //Todos los valores que admite una factura rectificada
        $dataFacturaRectificada = [
            'IDEmisorFactura' => "S2819426D", // string
            'numSerieFactura' => "AX/202412-009", // string
            'fechaExpedicionFactura' => "2024-11-27", // string
        ];
        //Crear la factura rectificada a traves del cliente
        $facturaRectificada = $obj->nuevaFacturaRectificada($dataFacturaRectificada);
    }

    if($facturaSustituida){
        //FACTURA SUSTITUIDA
        //Todos los valores que admite una factura sustituida
        $dataFacturaSustituida = [
            'IDEmisorFactura' => "S2819426D", // string
            'numSerieFactura' => "AX/202412-009", // string
            'fechaExpedicionFactura' => "2024-11-27", // string
        ];
        //Crear la factura sustituida a traves del cliente
        $facturaSustituida = $obj->nuevaFacturaSustituida($dataFacturaSustituida);
    }

    if($desgloses){
        //DESGLOSE
        //Todos los valores que admite un desglose
        $dataDesglose = [
            'impuesto' => 1, // int/string -> Lista L1
            'claveRegimen' => 1, // int/string -> Lista L8A
            'claveRegimenIGIC' => 1, // int/string -> Lista L8
            'calificacionOperacion' => 1, // int/string -> Lista L9
            'operacionExenta' => 0, // int/string -> Lista L10
            'tipoImpositivo' => 21, // float
            'baseImponibleOImporteNoSujeto' => 100, // float
            'baseImponibleACoste' => 100, // float
            'cuotaRepercutida' => 21, // float
            'tipoRecargoEquivalencia' => 0, // float
            'cuotaRecargoEquivalencia' => 0 // float
        ];
        //Crear el desglose a traves del cliente
        $desglose = $obj->nuevoDesglose($dataDesglose);
    }

    if($registrosAlta){
        //REGISTRO ALTA
        //Array con los objetos que admite un registro de alta
        $objetosRegistroAlta = [
            'emisor' => $emisor,
            'destinatario' => $destinatario,
            'desglose' => $desglose,
            'tercero' => $tercero,
            'facturaRectificada' => $facturaRectificada,
            'facturaSustituida' => $facturaSustituida
        ];
        //Todos los valores que admite un registro de alta
        $dataRegistroAlta = [
            'numSerieFactura' => "AX/202412-009", // string
            'fechaExpedicionFactura' => "2024-11-27", // string
            'refExterna' => "Test Ref Externa", // string
            'subsanacion' => 0, // int/string -> Lista L4
            'rechazoPrevio' => 0, // int/string -> Lista L17
            'tipoFactura' => 1, // int/string -> Lista L2
            'tipoRectificativa' => 0, // int/string -> Lista L3
            'baseRectificada' => 0.0, // float
            'cuotaRectificada' => 0.0, // float
            'cuotaRecargoRectificado' => 0.0, // float
            'fechaOperacion' => "", // string
            'descripcionOperacion' => "", // string
            'facturaSimplificadaArt7273' => 0, // int/string -> Lista L4
            'facturaSinIdentifDestinatarioArt61d' => 0, // int/string -> Lista L5
            'macrodato' => 0, // int/string -> Lista L14
            'emitidaPorTercODestinatario' => 0, // int/string -> Lista L6
            'cupon' => 0, // int/string -> Lista L4
            'cuotaTotal' => 21.0, // float
            'importeTotal' => 121.0 // float
        ];
        //El array de objetos es obligatorio, Emisor es obligatorio, pero los otros objetos son opcionales.
        //Si no se proporcionan, se establecen como empty.
        //El webhookID es opcional, si no se proporciona, se establece el webhookID del emisor.
        //Crear el registro de alta a traves del cliente
        $registroAlta = $obj->nuevoRegistroAlta($objetosRegistroAlta, $dataRegistroAlta, 10);

        //Ejemplo de registro de alta sin algunos datos.
        $objetosRegistroAlta2 = [
            'emisor' => $emisor
        ];
        $dataRegistroAlta2 = [
            'numSerieFactura' => "AX/202412-009", // string
            'fechaExpedicionFactura' => "2024-11-27", // string
            'refExterna' => "Test Ref Externa", // string
            'tipoFactura' => 1, // int/string -> Lista L2
            'cuotaTotal' => 21.0, // float
            'importeTotal' => 121.0 // float
        ];
        $registroAlta2 = $obj->nuevoRegistroAlta($objetosRegistroAlta2, $dataRegistroAlta2);
    }

    if($registrosAnulacion){
        //REGISTRO ANULACION
        //Todos los valores que admite un registro de anulacion
        $dataRegistroAnulacion = [
            'IDEmisorFactura' => "S2819426D", // string
            'numSerieFactura' => "AX/202412-009", // string
            'fechaExpedicionFactura' => "2024-11-27", // string
            'refExterna' => "Test Ref Externa", // string
            'sinRegistroPrevio' => 0, // int/string -> Lista L4
            'rechazoPrevioA' => 1, // int/string -> Lista L4
            'generadoPor' => 0, // int/string -> Lista L16
            'generadorNombreRazon' => "NOMBRE EJEMPLO", // string
            'generadorNIF' => "39707287H", // string
            'generadorOtroCodPais' => "ES", // string
            'generadorOtroIDType' => 1, // int/string -> Lista L7
            'generadorOtroID' => "" // string
        ];
        //Crear el registro de anulacion a traves del cliente
        $registroAnulacion = $obj->nuevoRegistroAnulacion($dataRegistroAnulacion);
    }	
} catch (Exception $e) {
	echo $e->getMessage();
}

