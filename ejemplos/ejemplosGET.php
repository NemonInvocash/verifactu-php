<?php
include_once __DIR__ ."/../vendor/autoload.php";
include_once __DIR__ ."/../src/ClienteVerifactu.php";

//ACTIVAR LAS OPCIONES QUE SE QUIEREN PROBAR
$emisores = true;
$enviosAEAT = true;
$registrosAlta = true;
$listas = true;
$webhooks = true;

// Las siguientes funciones permiten listar diferentes recursos en la API de Verifactu:
// - listarEmisores: Recupera todos los emisores o un emisor específico según el ID proporcionado.
// - listarEnviosAEAT: Recupera todos los envíos a la AEAT o un envío específico según el ID proporcionado.
// - listarRegistrosAlta: Recupera todos los registros de alta o un registro específico según el ID proporcionado.
// - listarListas: Recupera todas las listas o una lista específica según el nombre y valor proporcionados.
// - listarWebhooks: Recupera todos los webhooks o un webhook específico según el ID proporcionado.

try {
	//Iniciamos el cliente 
	$obj = new verifactuPHP\ClienteVerifactu( "ejemplo@verifactuAPI.com" , '#9e2q$v@bG');
	$obj->setDebug(true); //debug activado
	
	//Listar emisores
	//si no se pasa el parametro se assigna como null
    //null -> todos los emisores
    //int -> emisor especifico
	if($emisores){
		$obj->listarEmisores();
		$obj->listarEmisores(1);
	}
	
    //Listar envios a AEAT
	//si no se pasa el parametro se assigna como null
	//null -> todos los envios
    //int -> envio especifico
	if($enviosAEAT){
		$obj->listarEnviosAEAT();
		$obj->listarEnviosAEAT(44);
	}


	//Listar registros de alta
	//si no se pasa el parametro se assigna como null
	//null -> todos los registros
    //int -> registro especifico
	if($registrosAlta){
		$obj->listarRegistrosAlta();
		$obj->listarRegistrosAlta(44);
	}

	//Listar listas
	//si no se pasa el parametro se assigna como ""
    //"", "" -> todos las listas
    //"nombreLista", "" -> lista en especifico
    //"nombreLista", "valorLista" -> valor de una lista en especifico
	if($listas){
		$obj->listarListas();
		$obj->listarListas("l1");
		$obj->listarListas("l1", "02");
	}
	
	//Listar webhooks
	//si no se pasa el parametro se assigna como null
    //null -> todos los webhooks
    //int -> webhook especifico
	if($webhooks){
		$obj->listarWebhooks();
		$obj->listarWebhooks(1);
	}	
} catch (Exception $e) {
	echo $e->getMessage();
}