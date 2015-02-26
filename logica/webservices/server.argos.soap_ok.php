<?php
/*
* @Descripcion : SCRIPT DEL SERVIDOR PARA EL MANEJO DE WEBSERVICES EN argos
*
* @copyright : 2011
* @Acceso Private
*
* @Version : server.argos.soap.php v1.0
* @Autor : Ricardo Umbarila,  BOLCOLOMBIA.
* 
*/
// INCLUSION DE NuSOAP


error_reporting(0);
require_once('nusoap.php');
// INCLUSION DE LA CLASE DE argos


require_once('server.argos.class.php');
// INCLUSION DE LAS LIBRERIAS DE CONEXION A LA BASE DE DATOS



set_time_limit(0);
ini_set ('memory_limit',20);
ini_set ('max_execution_time',18000);
//error_reporting(0);

$namespace = "urn:argos";
$server = new soap_server();
$server->configureWSDL('argos', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;



//////////////////////////////////////////////////////////////////////////
//			REGISTRO DE LAS ESTRUCTURAS USADAS POR EL SERVICIO
//////////////////////////////////////////////////////////////////////////

// REGISTRO DE LA FUNCION Autenticar

//AUTENTICAR
$server->register
// nombre del metodo
('Autenticar',
		//parametros de entrada
	array('usuario' => 'xsd:string','pwd' => 'xsd:string'),	
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Autenticar',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


// REGISTRO DE LA FUNCION Autenticar

//AUTENTICAR
$server->register
// nombre del metodo
('Consulta_Cliente',
		//parametros de entrada
            array('Idcliente' => 'xsd:string','Idfuncionario' => 'xsd:string','usuario' => 'xsd:string','pwd' => 'xsd:string'),	
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Consulta_Cliente',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


//////////////////////////////////////////////////////////////////////////
//			FUNCIONES DEL SERVICIO
//////////////////////////////////////////////////////////////////////////

function Autenticar($usuario,$pwd) 
{
        
	$consulta= new Consultas();
		$login = $consulta->logon($usuario,$pwd);
		if($login!="")
		{
			return $login;      
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}


function Consulta_Cliente($Idcliente,$Idfuncionario,$usuario,$pwd) 
{
        
	$consulta= new Consultas();
		$login = $consulta->Consulta_Cliente($Idcliente,$Idfuncionario,$usuario,$pwd);
		if($login!="")
		{
			return $login;      
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}


// USO DE LA PETICION PARA INVOCAR EL SERVICIO
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>



