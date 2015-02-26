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
ini_set ('memory_limit', '16M'); //
ini_set ('max_execution_time', 1200);
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

//CONSULTA CLIENTE
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

//CARGUE INICIAL
$server->register
// nombre del metodo
('Cargue_Inicial',
//parametros de entrada
array('fecha_transaccion' => 'xsd:string',
	'usu_codigo' => 'xsd:string',
	'usuario' => 'xsd:string',
	'pwd' => 'xsd:string'),	
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#Cargue_Inicial',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//CONSULTA_CLIENTE_RENOVACION
$server->register
// nombre del metodo
('Consulta_Cliente_Renovacion',
		//parametros de entrada
	array('nro_identificacion'=>'xsd:string','usuario'=>'xsd:string','pwd'=>'xsd:string'),	
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Consulta_Cliente_Renovacion',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//INGRESAR VENTAS
$server->register
// nombre del metodo
('Ingresar_Venta',
		//parametros de entrada
	array('codigo_barras'=>'xsd:string',
	'referencia'=>'xsd:string',
	'fecha_solicitud'=>'xsd:string',
	'cli_codigo'=>'xsd:string',
	'aju_codigo'=>'xsd:string',
	'ttp_codigo'=>'xsd:string',
	'tp_codigo'=>'xsd:string',
	'tnc_codigo'=>'xsd:string',
	'valor_producto'=>'xsd:string',
	'valor_comision_servicio'=>'xsd:string',
	'valor_impuesto'=>'xsd:string',
	'valor_total'=>'xsd:string',
	'fecha_entrega'=>'xsd:string',
	'longitud'=>'xsd:string',
	'latitud'=>'xsd:string',
	'est_codigo'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Ingresar_Venta',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//INGRESAR CLIENTES
$server->register
// nombre del metodo
('Ingresar_Cliente',
		//parametros de entrada
	array('ti_codigo'=>'xsd:string',
	'nroidentificacion'=>'xsd:string',
	'razon_social'=>'xsd:string',
	'tn_codigo'=>'xsd:string',
	'nombre_contacto'=>'xsd:string',
	'telefono1'=>'xsd:string',
	'ext1'=>'xsd:string',
	'telefono2'=>'xsd:string',
	'ext2'=>'xsd:string',
	'celular1'=>'xsd:string',
	'celular2'=>'xsd:string',
	'email'=>'xsd:string',
	'barrio'=>'xsd:string',
	'direccion'=>'xsd:string',
	'comentario'=>'xsd:string',
	'ciu_codigo'=>'xsd:string',
	'est_codigo'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Ingresar_Cliente',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//CONSULTA RECAUDO DIARIO
$server->register
// nombre del metodo
('Consulta_Recaudo_Diario',
		//parametros de entrada
	array('usu_codigo'=>'xsd:string',
	'fecha_recaudo'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),	
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Consulta_Recaudo_Diario',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//CONSULTA VENTAS DIARIAS
$server->register
// nombre del metodo
('Consulta_Venta_Diarias',
		//parametros de entrada
	array('usu_codigo'=>'xsd:string',
	'fecha_entrega'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),	
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Consulta_Venta_Diarias',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//CONSULTA DEBIDO COBRAR
$server->register
// nombre del metodo
('Consulta_Debido_Cobrar',
		//parametros de entrada
	array('codigo'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),	
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Consulta_Debido_Cobrar',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//CONSULTA VISITAS
$server->register
// nombre del metodo
('Clientes_Visitado',
		//parametros de entrada
	array('codigo'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),	
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Clientes_Visitado',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


//CONSULTA TRANSALADO ENVIADO
$server->register
// nombre del metodo
('Consulta_Traslado_Enviados',
		//parametros de entrada
	array('codigo'=>'xsd:string',
	'fecha_traslado'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),	
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Consulta_Traslado_Enviados',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


//CONSULTA TRANSALADO RECIBIDO
$server->register
// nombre del metodo
('Consulta_Traslado_Recibidos',
		//parametros de entrada
	array('codigo'=>'xsd:string',
	'fecha_traslado'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),	
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Consulta_Traslado_Recibidos',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


//CONSULTA GASTOS
$server->register
// nombre del metodo
('Consulta_Gastos',
		//parametros de entrada
	array('usu_codigo'=>'xsd:string',
	'fecha_gasto'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),	
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Consulta_Gastos',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//INGRESAR DETALLE RECAUDO VENTA
$server->register
// nombre del metodo
('Ingresar_Detalle_Recaudo_Venta',
		//parametros de entrada
	array('tv_codigo'=>'xsd:string',
	'aju_codigo'=>'xsd:string',
	'cuotas_totales'=>'xsd:string',
	'cuota_nro'=>'xsd:string',
	'fecha_pago'=>'xsd:string',
	'valor_pago'=>'xsd:string',
	'fecha_recaudo'=>'xsd:string',
	'valor_recaudo'=>'xsd:string',
	'est_codigo'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Ingresar_Detalle_Recaudo_Venta',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


//INGRESAR GASTOS
$server->register
// nombre del metodo fecha_gasto, aju_codigo, tgj_codigo, valor, est_codigo
('Ingresar_Gastos',
		//parametros de entrada
	array('fecha_gasto'=>'xsd:string',
	'aju_codigo'=>'xsd:string',
	'tgj_codigo'=>'xsd:string',
	'valor'=>'xsd:string',
	'est_codigo'=>'xsd:string',	
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Ingresar_Gastos',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


	//INGRESAR VENTA DETALLE
$server->register
// nombre del metodo $ven_codigo, $pro_codigo, $cantidad, $valor_unitario, $valor_total, $est_codigo
('Ingresar_Ventas_Detalles',
		//parametros de entrada
	array('ven_codigo'=>'xsd:string',
	'pro_codigo'=>'xsd:string',
	'cantidad'=>'xsd:string',
	'valor_unitario'=>'xsd:string',
	'valor_total'=>'xsd:string',
	'est_codigo'=>'xsd:string',	
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Ingresar_Ventas_Detalles',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


//INGRESAR TRANSACCION RUTA DETALLE
$server->register
// nombre del metodo 
('Ingresar_Tran_Ruta_Det',
		//parametros de entrada
	array('trj_codigo'=>'xsd:string',
	'tv_codigo'=>'xsd:string',
	'secuencia'=>'xsd:string',
	'longitud'=>'xsd:string',
	'saldo'=>'xsd:string',
	'est_codigo'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#Ingresar_Tran_Ruta_Det',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//OBTENER INFORMACION USUARIOS
$server->register
// nombre del metodo
('ObtenerInfo_Usuario',
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

//CONSULTAR RUTAS
$server->register
// nombre del metodo
('consultaRutas',
  //parametros de entrada
            array('vendedor' => 'xsd:string',
            'fecha_venta' => 'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#consultaRutas',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


//INGRESAR TRANSACCION INICIAL
$server->register
// nombre del metodo 
('IncresoLoggsAccesosUsuarios',
		//parametros de entrada
	array('aju_codigo'=>'xsd:string',
	'fecha_trans'=>'xsd:string',
	'accion'=>'xsd:string',
	'ip'=>'xsd:string',
	'longitud'=>'xsd:string',
	'latitud'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#IncresoLoggsAccesosUsuarios',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//CONSULTA PERFIL
$server->register
// nombre del metodo 
('consultaPerfil',
		//parametros de entrada
	array('codigo'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#consultaPerfil',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//CONSULTA VENDEDORES
$server->register
// nombre del metodo 
('consultaVendedores',
		//parametros de entrada
	array('codigo'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#consultaVendedores',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//INGRESAR CLIENTE OFFLINE
$server->register
// nombre del metodo 
('ingresoClientesOffline',
		//parametros de entrada
	array('ti_codigo'=>'xsd:string',
	'nroidentificacion'=>'xsd:string',
	'referencia'=>'xsd:string',
	'razon_social'=>'xsd:string',
	'tn_codigo'=>'xsd:string',
	'nombre1_contacto'=>'xsd:string',
	'nombre2_contacto'=>'xsd:string',
	'apellido1_contacto'=>'xsd:string',
	'apellido2_contacto'=>'xsd:string',
	'telefono1'=>'xsd:string',
	'ext1'=>'xsd:string',
	'telefono2'=>'xsd:string',
	'ext2'=>'xsd:string',
	'celular1'=>'xsd:string',
	'celular2'=>'xsd:string',
	'email'=>'xsd:string',
	'barrio'=>'xsd:string',
	'direccion'=>'xsd:string',
	'comentario'=>'xsd:string',
	'calificacion'=>'xsd:string',
	'ciu_codigo'=>'xsd:string',
	'est_codigo'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#ingresoClientesOffline',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


//INGRESAR SOLICITUD OFFLINE
$server->register
// nombre del metodo 
('ingresoSolicitudOffline',
		//parametros de entrada
	array('codigo_barras'=>'xsd:string',
	'referencia'=>'xsd:string',
	'fecha_solicitud'=>'xsd:string',
	'cli_codigo'=>'xsd:string',
	'aju_codigo'=>'xsd:string',
	'ppj_codigo'=>'xsd:string',
	'tp_codigo'=>'xsd:string',
	'pnc_codigo'=>'xsd:string',
	'valor_producto'=>'xsd:string',
	'valor_comision_servicio'=>'xsd:string',
	'valor_impuesto'=>'xsd:string',
	'valor_total'=>'xsd:string',
	'fecha_entrega'=>'xsd:string',
	'longitud'=>'xsd:string',
	'latitud'=>'xsd:string',
	'est_codigo'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#ingresoSolicitudOffline',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);
//CONSULTA PRODUCTOS SINCRONIZA
$server->register
// nombre del metodo 
('ConsultaProductoSincroniza',
		//parametros de entrada
	array('funcionario_id'=>'xsd:string',
	'id_cliente'=>'xsd:string',
	'fecha_venta'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#ConsultaProductoSincroniza',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//INGRESAR TRANSACCION OFFLINE
$server->register
// nombre del metodo 
('ingresoTransaccionOffline',
		//parametros de entrada
	array('fecha_recaudo'=>'xsd:string',
	'valor_recaudo'=>'xsd:string',
	'fecha_trans'=>'xsd:string',
	'longitud'=>'xsd:string',
	'latitud'=>'xsd:string',
	'est_codigo'=>'xsd:string',
	'est_codigo_venta'=>'xsd:string',
	'tv_codigo'=>'xsd:string',
	'aju_codigo'=>'xsd:string',
	'cuota_numero'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#ingresoTransaccionOffline',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


//CONSULTA CARGUE TRANSACCION DE VENTA 
$server->register
// nombre del metodo
('ConsultaTransVentas',
  //parametros de entrada
            array('aju_codigo' => 'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaTransVentas',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);

//CONSULTA CARGUE TABLA ADMIN_CLINETES 
$server->register
// nombre del metodo
('ConsultaAdminClientes',
  //parametros de entrada
            array('aju_codigo' => 'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaAdminClientes',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


//CONSULTA TRANS RUTAS JVA
$server->register
// nombre del metodo
('ConsultaTransRutasJva',
  //parametros de entrada
            array('aju_codigo' => 'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaTransRutasJva',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);

//CONSULTA TRANS RUTAS JVA
$server->register
// nombre del metodo
('ConsultaTransRutasDetalles',
  //parametros de entrada
            array('aju_codigo' => 'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaTransRutasDetalles',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);

//CONSULTA CARGUE TABLA ADMIN_CLINETES 
$server->register
// nombre del metodo
('ConsultaTransDetalleRecaudoVentasJva',
  //parametros de entrada
            array('aju_codigo' => 'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaTransDetalleRecaudoVentasJva',
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
	$concliente = $consulta->ConsultaCliente($Idcliente,$Idfuncionario,$usuario,$pwd);
	if($concliente!="")
	{
		return $concliente;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

function Cargue_Inicial($fecha_transaccion,$usu_codigo,$usuario,$pwd)
{
	$consulta= new Consultas();
	$cargueinicial = $consulta->CargueInicial($fecha_transaccion,$usu_codigo,$usuario,$pwd);
	if($cargueinicial!="")
	{
		return $cargueinicial;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

function Consulta_Cliente_Renovacion($nro_identificacion, $usuario, $pwd)
{
		$consulta= new Consultas();
		$Consulta_cli_Reno = $consulta->ConsultaClienteRenovacion($nro_identificacion, $usuario, $pwd);
		if($Consulta_cli_Reno!="")
		{
			return $Consulta_cli_Reno;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function Ingresar_Venta($codigo_barras,$referencia,$fecha_solicitud,$cli_codigo,$aju_codigo,$ttp_codigo,$tp_codigo,
		$tnc_codigo,$valor_producto,$valor_comision_servicio,$valor_impuesto,$valor_total,$fecha_entrega,
		$longitud,$latitud,$est_codigo, $usuario, $pwd) 
{
		$consulta= new Consultas();
		$Ingresa_venta1 = $consulta->IngresarVenta($codigo_barras,$referencia,$fecha_solicitud,$cli_codigo,$aju_codigo,
		$ttp_codigo,$tp_codigo,$tnc_codigo,$valor_producto,$valor_comision_servicio,$valor_impuesto,$valor_total,
		$fecha_entrega,$longitud,$latitud,$est_codigo, $usuario, $pwd);
		if($Ingresa_venta1!="")
		{
			return $Ingresa_venta1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

Function Ingresar_Cliente($ti_codigo,$nroidentificacion,$razon_social,$tn_codigo,$nombre_contacto,$telefono1,$ext1,
		$telefono2,$ext2,$celular1,$celular2,$email,$barrio,$direccion,$comentario,$ciu_codigo,$est_codigo, $usuario, $pwd) 
{
		$consulta= new Consultas();
		$Ingresar_Cliente1 = $consulta->IngresarCliente($ti_codigo,$nroidentificacion,$razon_social,$tn_codigo,$nombre_contacto,
		$telefono1,$ext1,$telefono2,$ext2,$celular1,$celular2,$email,$barrio,$direccion,$comentario,$ciu_codigo,$est_codigo, 
		$usuario, $pwd);
		if($Ingresar_Cliente1!="")
		{
			return $Ingresar_Cliente1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function Consulta_Recaudo_Diario($usu_codigo, $fecha_recaudo, $usuario, $pwd)
{
		$consulta= new Consultas();
		$cons_rec_nuev = $consulta->ConsultaRecaudoDiario($usu_codigo, $fecha_recaudo, $usuario, $pwd);
		if($cons_rec_nuev!="")
		{
			return $cons_rec_nuev;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function Consulta_Venta_Diarias($usu_codigo, $fecha_entrega, $usuario, $pwd)
{
		$consulta= new Consultas();
		$cons_ven_diar = $consulta->ConsultaVentaDiarias($usu_codigo, $fecha_entrega, $usuario, $pwd);
		if($cons_ven_diar!="")
		{
			return $cons_ven_diar;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function Consulta_Debido_Cobrar($codigo, $usuario, $pwd)
{
		$consulta= new Consultas();
		$cons_debido_cobrar = $consulta->ConsultaDebidoCobrar($codigo, $usuario, $pwd);
		if($cons_debido_cobrar!="")
		{
			return $cons_debido_cobrar;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function Clientes_Visitado($codigo, $usuario, $pwd)
{
		$consulta= new Consultas();
		$cons_debido_cobrar = $consulta->ClientesVisitado($codigo, $usuario, $pwd);
		if($cons_debido_cobrar!="")
		{
			return $cons_debido_cobrar;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function Consulta_Traslado_Enviados($codigo, $fecha_traslado, $usuario, $pwd)
{
		$consulta= new Consultas();
		$cons_trans_enviado = $consulta->ConsultaTrasladoEnviados($codigo, $fecha_traslado, $usuario, $pwd);
		if($cons_trans_enviado!="")
		{
			return $cons_trans_enviado;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function Consulta_Traslado_Recibidos($codigo, $fecha_traslado, $usuario, $pwd)
{
		$consulta= new Consultas();
		$cons_trans_recibido = $consulta->ConsultaTrasladoRecibidos($codigo, $fecha_traslado, $usuario, $pwd);
		if($cons_trans_recibido!="")
		{
			return $cons_trans_recibido;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function Consulta_Gastos($usu_codigo, $fecha_gasto, $usuario, $pwd)
{
		$consulta= new Consultas();
		$cons_gasto = $consulta->ConsultaGastos($usu_codigo, $fecha_gasto, $usuario, $pwd);
		if($cons_gasto!="")
		{
			return $cons_gasto;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

Function Ingresar_Detalle_Recaudo_Venta($tv_codigo,$aju_codigo,$cuotas_totales,$cuota_nro,$fecha_pago,$valor_pago,
	$fecha_recaudo,$valor_recaudo,$est_codigo, $usuario, $pwd)
{
		$consulta= new Consultas();
		$DetalleRecaudoVenta1 = $consulta->IngresarDetalleRecaudoVenta($tv_codigo,$aju_codigo,$cuotas_totales,$cuota_nro,$fecha_pago,$valor_pago,
		$fecha_recaudo,$valor_recaudo,$est_codigo, $usuario, $pwd);
		if($DetalleRecaudoVenta1!="")
		{
			return $DetalleRecaudoVenta1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

Function Ingresar_Gastos($fecha_gasto, $aju_codigo, $tgj_codigo, $valor, $est_codigo, $usuario, $pwd)
{
		$consulta= new Consultas();
		$IngresarGastos1 = $consulta->IngresarGastos($fecha_gasto, $aju_codigo, $tgj_codigo, $valor, $est_codigo, $usuario, $pwd);
		if($IngresarGastos1!="")
		{
			return $IngresarGastos1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

Function Ingresar_Ventas_Detalles($ven_codigo, $pro_codigo, $cantidad, $valor_unitario, $valor_total, $est_codigo, $usuario, $pwd)
{
		$consulta= new Consultas();
		$IngresarVentasDetalles1 = $consulta->IngresarVentasDetalles($ven_codigo, $pro_codigo, $cantidad, $valor_unitario, $valor_total, $est_codigo, $usuario, $pwd);
		if($IngresarVentasDetalles1!="")
		{
			return $IngresarVentasDetalles1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

Function Ingresar_Tran_Ruta_Det($trj_codigo,$tv_codigo,$secuencia,$longitud,$latitud,$saldo,$est_codigo, $usuario, $pwd)
{
		$consulta= new Consultas();
		$IngresarTranRutDet = $consulta->IngresarTransaccionRutaDetalles($trj_codigo,$tv_codigo,$secuencia,$longitud,$latitud,$saldo,$est_codigo, $usuario, $pwd);
		if($IngresarTranRutDet!="")
		{
			return $IngresarTranRutDet;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function ObtenerInfo_Usuario($usuario,$pwd)
{

	$consulta= new Consultas();
	$login = $consulta->ObtenerInfoUsuario($usuario,$pwd);
	if($login!="")
	{
		return $login;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

Function IncresoLoggsAccesosUsuarios($aju_codigo,$fecha_trans,$accion,$ip,$longitud,$latitud,$usuario, $pwd)
{
		$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->increso_loggs_accesos_usuarios($aju_codigo,$fecha_trans,$accion,$ip,$longitud,$latitud,$usuario, $pwd);
		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function consultaPerfil($codigo, $usuario, $pwd)
{
		$consulta= new Consultas();
		$cons_perfil = $consulta->consulta_Perfil($codigo, $usuario, $pwd);
		if($cons_perfil!="")
		{
			return $cons_perfil;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function consultaVendedores($codigo, $usuario, $pwd)
{
		$consulta= new Consultas();
		$cons_vendedor = $consulta->consulta_Vendedores($codigo, $usuario, $pwd);
		if($cons_vendedor!="")
		{
			return $cons_vendedor;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function consultaRutas($vendedor, $fecha_venta, $usuario, $pwd)
{

	$consulta= new Consultas();
	$cnsulta_rutas1 = $consulta->consulta_Rutas($vendedor, $fecha_venta, $usuario, $pwd);
	if($cnsulta_rutas1!="")
	{
		return $cnsulta_rutas1;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

	Function ingresoClientesOffline($ti_codigo,$nroidentificacion,$referencia,$razon_social,$tn_codigo,$nombre1_contacto,$nombre2_contacto,$apellido1_contacto,$apellido2_contacto,
$telefono1,$ext1,$telefono2,$ext2,$celular1,$celular2,$email,$barrio,$direccion,$comentario,$calificacion,$ciu_codigo,$est_codigo,$usuario, $pwd)
{
		$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->ingreso_Clientes_Offline($ti_codigo,$nroidentificacion,$referencia,$razon_social,$tn_codigo,$nombre1_contacto,$nombre2_contacto,$apellido1_contacto,$apellido2_contacto,
$telefono1,$ext1,$telefono2,$ext2,$celular1,$celular2,$email,$barrio,$direccion,$comentario,$calificacion,$ciu_codigo,$est_codigo,$usuario, $pwd);

		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

Function IngresoSolicitudOffline($codigo_barras,$referencia,$fecha_solicitud,$cli_codigo,$aju_codigo,$ppj_codigo,
	$tp_codigo,$pnc_codigo,$valor_producto,$valor_comision_servicio,$valor_impuesto,$valor_total,$fecha_entrega,
	$longitud,$latitud, $est_codigo,$usuario, $pwd)
{
		$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->ingreso_Solicitud_Offline($codigo_barras,$referencia,$fecha_solicitud,
		$cli_codigo,$aju_codigo,$ppj_codigo,$tp_codigo,$pnc_codigo,$valor_producto,$valor_comision_servicio,
		$valor_impuesto,$valor_total,$fecha_entrega,$longitud,$latitud, $est_codigo,$usuario, $pwd);

		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function ConsultaProductoSincroniza($funcionario_id, $id_cliente, $fecha_venta, $usuario, $pwd)
{
		$consulta= new Consultas();
		$consulta1 = $consulta->Consulta_Producto_Sincroniza($funcionario_id, $id_cliente, $fecha_venta, $usuario, $pwd);
		if($consulta1!="")
		{
			return $consulta1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

Function ingresoTransaccionOffline($fecha_recaudo,$valor_recaudo,$fecha_trans,$longitud,$latitud,$est_codigo,$est_codigo_venta, 
	$tv_codigo,$aju_codigo,$cuota_numero,$usuario, $pwd)
{
	$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->ingreso_Transaccion_Offline($fecha_recaudo,$valor_recaudo,$fecha_trans,$longitud,$latitud,$est_codigo,$est_codigo_venta, 
	$tv_codigo,$aju_codigo,$cuota_numero,$usuario, $pwd);
		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function ConsultaTransVentas($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->consulta_Trans_Ventas($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	
	else
	{
		return "NO HAY CONEXION";
	}
}


function ConsultaAdminClientes($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->admin_clientes($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	
	else
	{
		return "NO HAY CONEXION";
	}
}

function ConsultaTransRutasJva($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->trans_rutas_jva($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	
	else
	{
		return "NO HAY CONEXION";
	}
}


function ConsultaTransRutasDetalles($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->trans_rutas_detalles($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	
	else
	{
		return "NO HAY CONEXION";
	}
}

function ConsultaTransDetalleRecaudoVentasJva($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->trans_detalle_recaudo_ventas_jva($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
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