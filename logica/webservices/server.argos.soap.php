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
array('ip' => 'xsd:string',
'longitud' => 'xsd:string',
'latitud' => 'xsd:string',
'usuario' => 'xsd:string',
'pwd' => 'xsd:string'),
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#Autenticar',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//AUTENTICAR 2
$server->register
// nombre del metodo
('Autenticar2',
//parametros de entrada
array('ip' => 'xsd:string',
'longitud' => 'xsd:string',
'latitud' => 'xsd:string',
'usuario' => 'xsd:string',
'pwd' => 'xsd:string'),
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#Autenticar2',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//CIERRE
$server->register
// nombre del metodo
('Cerrar',
//parametros de entrada
array('aju_codigo' => 'xsd:string',
'ip' => 'xsd:string',
'longitud' => 'xsd:string',
'latitud' => 'xsd:string',
'usuario' => 'xsd:string',
'pwd' => 'xsd:string'),
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#Cerrar',
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

//CONSULTA CARGUE INICIAL PARA ARQUEO
$server->register
// nombre del metodo
('consultaCargueInicial',
//parametros de entrada
array('aju_codigo' => 'xsd:string',
	'usuario' => 'xsd:string',
	'pwd' => 'xsd:string'),	
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#consultaCargueInicial',
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



//CONSULTA VENTAS DIARIAS
$server->register
// nombre del metodo
('Consulta_Venta_Diarias',
		//parametros de entrada
	array('aju_codigo'=>'xsd:string',
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
	array('tv_codigo'=>'xsd:string',
	'ppj_codigo'=>'xsd:string',
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
	array('codigo'=>'xsd:string',
	'ti_codigo'=>'xsd:string',
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
('ingresoVentaOffline',
		//parametros de entrada
	array('codigo'=>'xsd:string',
	'codigo_barras'=>'xsd:string',
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
	$namespace . '#ingresoVentaOffline',
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



//CONSULTA CARGUE TABLA ADMIN_CLINETES 
$server->register
// nombre del metodo
('ContarTransDetalleRecaudoVentasJva',
  //parametros de entrada
            array('aju_codigo' => 'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ContarTransDetalleRecaudoVentasJva',
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
            'inicio' => 'xsd:string',
            'cantidad' => 'xsd:string',
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

//INGRESAR TRANSACCION RECAUDO
$server->register
// nombre del metodo 
('IngresoTransaccionRecaudo',
		//parametros de entrada
	array('fecha_recaudo'=>'xsd:string',
	'valor_recaudo'=>'xsd:string',
	'longitud'=>'xsd:string',
	'latitud'=>'xsd:string',
	'est_codigo'=>'xsd:string',
	'tv_codigo'=>'xsd:string',
	'aju_codigo'=>'xsd:string',
	'cuota_numero'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#IngresoTransaccionRecaudo',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//CONSULTA DETALLE DE TRANSACCION A UNA VENTA
$server->register
// nombre del metodo
('ConsultaTransDetalleRecaudoVentasId',
  //parametros de entrada
            array('tv_codigo' => 'xsd:string',
            'aju_codigo' => 'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaTransDetalleRecaudoVentasId',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


//CONSULTA DETALLE DE TRANSACCION A UNA VENTA
$server->register
// nombre del metodo
('ConsultaTransRutasDetallesId',
  //parametros de entrada
            array('tv_codigo' => 'xsd:string',
            'aju_codigo' => 'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaTransRutasDetallesId',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


//CONSULTA PARAM PERIODICIDAD JVA
$server->register
// nombre del metodo
('ConsultaParamPeriodicidadJva',
  //parametros de entrada
            array('aju_codigo' => 'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaParamPeriodicidadJva',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);

//CONSULTA PARAM NUMERO CUOTAS
$server->register
// nombre del metodo
('ConsultaParamNumeroCuotasJva',
  //parametros de entrada
            array('aju_codigo' => 'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaParamNumeroCuotasJva',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);

//CONSULTA MOTIVOS DE NO PAGO
$server->register
// nombre del metodo
('ConsultaTiposMotivosNoPago',
  //parametros de entrada
            array('usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaTiposMotivosNoPago',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


//CONSULTA PARAM NUMERO CUOTAS
$server->register
// nombre del metodo
('IngresoTransDetalleRecaudoNoPago',
  //parametros de entrada
            array('tdrvj_codigo'=>'xsd:string',
            'tmnp_codigo'=>'xsd:string',
            'est_codigo'=>'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#IngresoTransDetalleRecaudoNoPago',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


//CONSULTA TIPOS DE PRODUCTOS SEGUN JVA
$server->register
// nombre del metodo
('ConsultaParamTipoProductosJva',
  //parametros de entrada
            array('aju_codigo'=>'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaParamTipoProductosJva',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


//CONSULTA VISITAS ARQUEO
$server->register
// nombre del metodo
('consultaVisitasV2',
  //parametros de entrada
            array('aju_codigo'=>'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#consultaVisitasV2',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


//CONSULTA VISITAS ARQUEO
$server->register
// nombre del metodo
('consultaRecaudos',
  //parametros de entrada
            array('aju_codigo'=>'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#consultaRecaudos',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);

//CONSULTA COLOCACIONES
$server->register
// nombre del metodo
('consultaColocaciones',
  //parametros de entrada
            array('aju_codigo'=>'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#consultaColocaciones',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


//CONSULTA COLOCACIONES
$server->register
// nombre del metodo
('consultaRecaudosDiarios',
  //parametros de entrada
            array('aju_codigo'=>'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#consultaRecaudosDiarios',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


//INGRESAR EL HISTORICO DEL INVENTARIO
$server->register
// nombre del metodo
('TransHistoricoRecaudoVentasJva',
  //parametros de entrada
            array('tv_codigo'=>'xsd:string',
            'aju_codigo'=>'xsd:string',
            'tdrvj_codigo'=>'xsd:string',
            'cuota_nro'=>'xsd:string',
            'fecha_recaudo'=>'xsd:string',
            'valor_recaudo'=>'xsd:string',
            'longitud'=>'xsd:string',
            'latitud'=>'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#TransHistoricoRecaudoVentasJva',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


//
$server->register
// nombre del metodo
('IngresarTransTrasladosInventariosJva',
  //parametros de entrada
            array('ptij_codigo'=>'xsd:string',
            'aju_codigo_desde'=>'xsd:string',
            'aju_codigo_hasta'=>'xsd:string',
            'ppj_codigo'=>'xsd:string',
            'cantidad'=>'xsd:string',
            'fecha_traslado'=>'xsd:string',
            'fecha_aprovacion'=>'xsd:string',
            'latitud'=>'xsd:string',
            'longitud'=>'xsd:string',
            'est_codigo'=>'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#IngresarTransTrasladosInventariosJva',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);



//
$server->register
// nombre del metodo
('ConsultaTransTrasladosInventariosJva',
  //parametros de entrada
            array('aju_codigo'=>'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaTransTrasladosInventariosJva',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);

//
$server->register
// nombre del metodo
('ConsultaTransInventariosJva',
  //parametros de entrada
            array('aju_codigo'=>'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaTransInventariosJva',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


//INGRESO HISTORICO DE RECAUDOS
$server->register
// nombre del metodo
('ConsultaTransHistoricoRecaudoVentasJva',
  //parametros de entrada
            array('aju_codigo'=>'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaTransHistoricoRecaudoVentasJva',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


	//CONSULTA RECAUDOS DIARIOS
$server->register
// nombre del metodo
('ConsultaRecaudoDiario',
//parametros de entrada
array('aju_codigo' => 'xsd:string',
'usuario' => 'xsd:string',
'pwd' => 'xsd:string'),
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#ConsultaRecaudoDiario',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


	//CONSULTA VISITAS DIARIAS 
$server->register
// nombre del metodo
('ConsultaVisitas',
//parametros de entrada
array('aju_codigo' => 'xsd:string',
'usuario' => 'xsd:string',
'pwd' => 'xsd:string'),
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#ConsultaVisitas',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


	//CONSULTA TABLA PARAMETROS DE TRASLADOS
$server->register
// nombre del metodo
('ConsultaParamTrasladosInventariosJva',
//parametros de entrada
array('aju_codigo' => 'xsd:string',
'usuario' => 'xsd:string',
'pwd' => 'xsd:string'),
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#ConsultaParamTrasladosInventariosJva',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


	//CONSULTA TOTAL PARA EL CIERRE
$server->register
// nombre del metodo
('ConsultaTotalDiarios',
//parametros de entrada
array('aju_codigo' => 'xsd:string',
'usuario' => 'xsd:string',
'pwd' => 'xsd:string'),
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#ConsultaTotalDiarios',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


	//CONSULTA TOTAL PARA EL CIERRE
$server->register
// nombre del metodo
('ConsltaParamGastosJva',
//parametros de entrada
array('aju_codigo' => 'xsd:string',
'usuario' => 'xsd:string',
'pwd' => 'xsd:string'),
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#ConsltaParamGastosJva',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);



	//CONSULTA GASTOS POR JVA
$server->register
// nombre del metodo
('ConsltaTransGastosJva',
//parametros de entrada
array('aju_codigo' => 'xsd:string',
'usuario' => 'xsd:string',
'pwd' => 'xsd:string'),
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#ConsltaTransGastosJva',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


//INSERTAR GASTOS POR JVA
$server->register
// nombre del metodo
('insertarTransGastosJva',
//parametros de entrada
array('fecha_gasto' => 'xsd:string',
'aju_codigo' => 'xsd:string',
'pgj_codigo' => 'xsd:string',
'valor' => 'xsd:string',
'longitud' => 'xsd:string',
'latitud' => 'xsd:string',
'est_codigo' => 'xsd:string',
'usuario' => 'xsd:string',
'pwd' => 'xsd:string'),
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#insertarTransGastosJva',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


	//CONSULTA GASTOS 
$server->register
// nombre del metodo
('consultaGastos',
//parametros de entrada
array('aju_codigo' => 'xsd:string',
'usuario' => 'xsd:string',
'pwd' => 'xsd:string'),
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#consultaGastos',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


	//CONSULTA TRANS INGRESA
$server->register
// nombre del metodo
('consTransIngresa',
//parametros de entrada
array('aju_codigo' => 'xsd:string',
'usuario' => 'xsd:string',
'pwd' => 'xsd:string'),
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#consTransIngresa',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


	//CONSULTA TRANS ENVIA
$server->register
// nombre del metodo
('consTransEnvia',
//parametros de entrada
array('aju_codigo' => 'xsd:string',
'usuario' => 'xsd:string',
'pwd' => 'xsd:string'),
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#consTransEnvia',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


	//CONSULTA GASTOS POR JVA
$server->register
// nombre del metodo
('consultaCobroDiario',
//parametros de entrada
array('aju_codigo' => 'xsd:string',
'usuario' => 'xsd:string',
'pwd' => 'xsd:string'),
//parametros de salida
array('return' => 'xsd:string'),
$namespace,
$namespace . '#consultaCobroDiario',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


//MODIFICAR CLIENTE ACTUALIZADO
$server->register
// nombre del metodo 
('ModificarCliActualizado',
		//parametros de entrada
	array('codigo'=>'xsd:string',
	'ti_codigo'=>'xsd:string',
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
	'ciu_codigo'=>'xsd:string',
	'est_codigo'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#ModificarCliActualizado',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//INGRESAR VENTAS
$server->register
// nombre del metodo
('ModificarStickerActualizado',
		//parametros de entrada
	array('codigo'=>'xsd:string',
	'codigo_barras'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#ModificarStickerActualizado',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//CONSULTA TABLA PARAM PRODUCTOS JVA
$server->register
// nombre del metodo
('ConsultaParamProductosJva',
		//parametros de entrada
	array('aju_codigo'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#ConsultaParamProductosJva',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);

//CONSULTA TABLA PARAM TIPOS TRANSALADOS INVENTARIOS
$server->register
// nombre del metodo
('ConsultaparamTiposTranInventariosJva',
		//parametros de entrada
	array('aju_codigo'=>'xsd:string',
	'usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#ConsultaparamTiposTranInventariosJva',
	'rpc',// estilo de transmision
	'encoded',// uso
	'RDA'// documentacion
);


//CONSULTA TABLA PARA LOS TIPOS DE NEGOCIOS
$server->register
// nombre del metodo
('ConsultaTiposNegocios',
		//parametros de entrada
	array('usuario'=>'xsd:string',
	'pwd'=>'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
	$namespace,
	$namespace . '#ConsultaTiposNegocios',
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

//CONSULTA GASTOS
$server->register
// nombre del metodo
('ConsultaGastos',
  //parametros de entrada
            array('usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaGastos',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


//CONSULTA GASTOS
$server->register
// nombre del metodo
('ConsultaTransUsuarioJva',
  //parametros de entrada
            array('aju_codigo'=>'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaTransUsuarioJva',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);



//CONSULTA TRANSACCIONES QUE INGRESAN AL VENDEDOR
$server->register
// nombre del metodo
('ConsultaTransIngresajva',
  //parametros de entrada
            array('aju_codigo'=>'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#ConsultaTransIngresajva',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);


//CONSULTA QUE EL VENDEDOR RECIBE DE UN ADMINISTRADOR
$server->register
// nombre del metodo
('transRecibeVendedor',
  //parametros de entrada
            array('aju_codigo'=>'xsd:string',
            'usuario'=>'xsd:string',
            'pwd' => 'xsd:string'),
        //parametros de salida
    array('return' => 'xsd:string'),
 $namespace,
 $namespace . '#transRecibeVendedor',
 'rpc',// estilo de transmision
 'encoded',// uso
 'RDA'// documentacion
);




//////////////////////////////////////////////////////////////////////////
//			FUNCIONES DEL SERVICIO
//////////////////////////////////////////////////////////////////////////

function Autenticar($ip,$longitud,$latitud,$usuario,$pwd)
{
	$consulta= new Consultas();
	$login = $consulta->logon($ip,$longitud,$latitud,$usuario,$pwd);
	if($login!="")
	{
		return $login;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

function Autenticar2($ip,$longitud,$latitud,$usuario,$pwd)
{
	$consulta= new Consultas();
	$login = $consulta->logon2($ip,$longitud,$latitud,$usuario,$pwd);
	if($login!="")
	{
		return $login;
	}
	else
	{
		return "0";
	}
}

function Cerrar($aju_codigo,$ip,$longitud,$latitud,$usuario,$pwd)
{
	$consulta= new Consultas();
	$logout1 = $consulta->Logout($aju_codigo,$ip,$longitud,$latitud,$usuario,$pwd);
	if($logout1!="")
	{
		return $logout1;
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

function consultaCargueInicial($fecha_transaccion,$usu_codigo,$usuario,$pwd)
{
	$consulta= new Consultas();
	$cargueinicial = $consulta->Consulta_Cargue_Inicial($fecha_transaccion,$usu_codigo,$usuario,$pwd);
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



function Consulta_Venta_Diarias($aju_codigo, $usuario, $pwd)
{
		$consulta= new Consultas();
		$cons_ven_diar = $consulta->ConsultaVentaDiarias($aju_codigo, $usuario, $pwd);
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

Function Ingresar_Ventas_Detalles($tv_codigo, $ppj_codigo, $cantidad, $valor_unitario, $valor_total, $est_codigo, $usuario, $pwd)
{
		$consulta= new Consultas();
		$IngresarVentasDetalles1 = $consulta->IngresarVentasDetalles($tv_codigo, $ppj_codigo, $cantidad, $valor_unitario, $valor_total, $est_codigo, $usuario, $pwd);
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

	Function ingresoClientesOffline($codigo,$ti_codigo,$nroidentificacion,$referencia,$razon_social,$tn_codigo,$nombre1_contacto,$nombre2_contacto,$apellido1_contacto,$apellido2_contacto,
$telefono1,$ext1,$telefono2,$ext2,$celular1,$celular2,$email,$barrio,$direccion,$comentario,$ciu_codigo,$est_codigo,$usuario, $pwd)
{
		$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->ingreso_Clientes_Offline($codigo,$ti_codigo,$nroidentificacion,$referencia,$razon_social,$tn_codigo,$nombre1_contacto,$nombre2_contacto,$apellido1_contacto,$apellido2_contacto,
$telefono1,$ext1,$telefono2,$ext2,$celular1,$celular2,$email,$barrio,$direccion,$comentario,$ciu_codigo,$est_codigo,$usuario, $pwd);

		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

	Function ModificarCliActualizado($codigo,$ti_codigo,$nroidentificacion,$referencia,$razon_social,$tn_codigo,$nombre1_contacto,$nombre2_contacto,$apellido1_contacto,$apellido2_contacto,
$telefono1,$ext1,$telefono2,$ext2,$celular1,$celular2,$email,$barrio,$direccion,$comentario,$ciu_codigo,$est_codigo,$usuario, $pwd)
{
		$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->Modificar_Cli_Actualizado($codigo,$ti_codigo,$nroidentificacion,$referencia,$razon_social,$tn_codigo,$nombre1_contacto,$nombre2_contacto,$apellido1_contacto,$apellido2_contacto,
$telefono1,$ext1,$telefono2,$ext2,$celular1,$celular2,$email,$barrio,$direccion,$comentario,$ciu_codigo,$est_codigo,$usuario, $pwd);

		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}



Function ingresoVentaOffline($codigo, $codigo_barras,$referencia,$fecha_solicitud,$cli_codigo,$aju_codigo,$ppj_codigo,
	$tp_codigo,$pnc_codigo,$valor_producto,$valor_comision_servicio,$valor_impuesto,$valor_total,$fecha_entrega,
	$longitud,$latitud, $est_codigo,$usuario, $pwd)
{
		$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->ingreso_Venta_Offline($codigo, $codigo_barras,$referencia,$fecha_solicitud,
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

function ContarTransDetalleRecaudoVentasJva($aju_codigo,$usuario,$pwd)
{
	$consulta= new Consultas();
	$consulta1 = $consulta->contar_trans_detalle_recaudo_ventas_jva($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

function ConsultaTransDetalleRecaudoVentasJva($aju_codigo,$inicio,$cantidad,$usuario,$pwd)
{
	$consulta= new Consultas();
	$consulta1 = $consulta->Consulta_trans_detalle_recaudo_ventas_jva($aju_codigo,$inicio,$cantidad,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

Function IngresoTransaccionRecaudo($fecha_recaudo,$valor_recaudo,$longitud,
$latitud,$est_codigo,$tv_codigo,$aju_codigo,$cuota_numero,$usuario, $pwd)
{
	$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->ingreso_Transaccion_Recaudo($fecha_recaudo,$valor_recaudo,$longitud,
$latitud,$est_codigo,$tv_codigo,$aju_codigo,$cuota_numero,$usuario, $pwd);
		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

function ConsultaTransDetalleRecaudoVentasId($tv_codigo,$aju_codigo,$usuario,$pwd)
{
	$consulta= new Consultas();
	$consulta1 = $consulta->contar_trans_detalle_recaudo_ventas_id($tv_codigo, $aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

function ConsultaTransRutasDetallesId($tv_codigo,$aju_codigo,$usuario,$pwd)
{
	$consulta= new Consultas();
	$consulta1 = $consulta->Consulta_Trans_Rutas_Detalles_id($tv_codigo, $aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

function ConsultaParamPeriodicidadJva($aju_codigo,$usuario,$pwd)
{
	$consulta= new Consultas();
	$consulta1 = $consulta->consulta_param_periodicidad_jva($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

function ConsultaParamNumeroCuotasJva($aju_codigo,$usuario,$pwd)
{
	$consulta= new Consultas();
	$consulta1 = $consulta->consulta_param_numero_cuotas_jva($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

function ConsultaTiposMotivosNoPago($usuario,$pwd)
{
	$consulta= new Consultas();
	$consulta1 = $consulta->consulta_tipos_motivos_no_pago($usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

function IngresoTransDetalleRecaudoNoPago($tdrvj_codigo,$tmnp_codigo,$est_codigo,$usuario, $pwd)
{
	$consulta= new Consultas();
	$retorno = $consulta->ingreso_trans_detalle_recaudo_no_pago($tdrvj_codigo,$tmnp_codigo,$est_codigo,$usuario, $pwd);
	if($retorno!="")
	{
		return $retorno;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

function ConsultaParamTipoProductosJva($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->consulta_param_tipo_productos_jva($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	
	else
	{
		return "NO HAY CONEXION";
	}
}

	function consultaVisitasV2($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->consulta_Visitas_V2($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	
	else
	{
		return "NO HAY CONEXION";
	}
}

	function consultaRecaudos($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->consulta_Recaudos($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	
	else
	{
		return "NO HAY CONEXION";
	}
}

	function consultaColocaciones($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->consulta_Colocaciones($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	
	else
	{
		return "NO HAY CONEXION";
	}
}

	function consultaRecaudosDiarios($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->consulta_Recaudos_Diarios($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	
	else
	{
		return "NO HAY CONEXION";
	}
}

	function TransHistoricoRecaudoVentasJva($tv_codigo,$aju_codigo,$tdrvj_codigo,$cuota_nro,$fecha_recaudo,
	$valor_recaudo,$longitud,$latitud,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->trans_historico_recaudo_ventas_jva($tv_codigo,$aju_codigo,$tdrvj_codigo,$cuota_nro,$fecha_recaudo,
	$valor_recaudo,$longitud,$latitud,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	
	else
	{
		return "NO HAY CONEXION";
	}
}

	function IngresarTransTrasladosInventariosJva($ptij_codigo,$aju_codigo_desde,$aju_codigo_hasta,$ppj_codigo,
	$cantidad,$fecha_traslado,$fecha_verificacion, $latitud,$longitud,$est_codigo,
	$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->Ingresar_trans_traslados_inventarios_jva($ptij_codigo,$aju_codigo_desde,$aju_codigo_hasta,
	$ppj_codigo,$cantidad,$fecha_traslado,$fecha_verificacion,$latitud,$longitud,$est_codigo,
	$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	
	else
	{
		return "NO HAY CONEXION";
	}
}

	function ConsultaTransTrasladosInventariosJva($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->Consulta_trans_traslados_inventarios_jva($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	
	else
	{
		return "NO HAY CONEXION";
	}
}


	function ConsultaTransInventariosJva($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->Consulta_trans_inventarios_jva($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	
	else
	{
		return "NO HAY CONEXION";
	}
}


	function ConsultaTransHistoricoRecaudoVentasJva($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->Consulta_trans_historico_recaudo_ventas_jva($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	
	else
	{
		return "NO HAY CONEXION";
	}
}


function ConsultaRecaudoDiario($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->Consulta_Recaudo_Diario($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}

	else
	{
		return "NO HAY CONEXION";
	}
}
	
function ConsultaVisitas($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->Consulta_Visitas($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}

	else
	{
		return "NO HAY CONEXION";
	}
}

function ConsultaParamTrasladosInventariosJva($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->Consulta_param_traslados_inventarios_jva($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}

	else
	{
		return "NO HAY CONEXION";
	}
}

function ConsultaTotalDiarios($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->Consulta_Total_Diarios($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}

	else
	{
		return "NO HAY CONEXION";
	}
}

function ConsltaParamGastosJva($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->conslta_param_gastos_jva($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}

	else
	{
		return "NO HAY CONEXION";
	}
}

function ConsltaTransGastosJva($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->conslta_trans_gastos_jva($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}

	else
	{
		return "NO HAY CONEXION";
	}
}




function consultaGastos($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->consulta_Gastos($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}

	else
	{
		return "NO HAY CONEXION";
	}
}


function consTransIngresa($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->cons_Trans_Ingresa($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

function consTransEnvia($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->cons_Trans_Envia($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}

function consultaCobroDiario($aju_codigo,$usuario,$pwd)
{

	$consulta= new Consultas();
	$consulta1 = $consulta->consulta_Cobro_Diario($aju_codigo,$usuario,$pwd);
	if($consulta1!="")
	{
		return $consulta1;
	}
	else
	{
		return "NO HAY CONEXION";
	}
}


	Function ModificarStickerActualizado($codigo, $codigo_barras,$usuario, $pwd)
{
		$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->Modificar_Sticker_Actualizado($codigo, $codigo_barras,$usuario, $pwd);

		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}


	Function ConsultaParamProductosJva($aju_codigo,$usuario,$pwd)
{
		$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->Consulta_param_productos_jva($aju_codigo,$usuario,$pwd);

		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

	Function ConsultaparamTiposTranInventariosJva($aju_codigo,$usuario,$pwd)
{
		$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->Consultaparam_tipos_tran_inventarios_jva($aju_codigo,$usuario,$pwd);

		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

	Function ConsultaTiposNegocios($usuario,$pwd)
{
		$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->Consulta_tipos_negocios($usuario,$pwd);

		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}


	Function insertarTransGastosJva($fecha_gasto,$aju_codigo,$pgj_codigo,$valor,$longitud,$latitud,$est_codigo,$usuario,$pwd)
{
		$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->insertar_trans_gastos_jva($fecha_gasto,$aju_codigo,$pgj_codigo,$valor,$longitud,$latitud,$est_codigo,$usuario,$pwd);

		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}



	Function ConsultaTransUsuarioJva($aju_codigo, $usuario,$pwd)
{
		$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->Consulta_Trans_Usuario_Jva($aju_codigo, $usuario,$pwd);

		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}


	Function ConsultaTransIngresajva($aju_codigo, $usuario,$pwd)
{
		$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->Consulta_Trans_Ingresa_jva($aju_codigo, $usuario,$pwd);

		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
		}
		else 
		{
			return "NO HAY CONEXION";
		}
}

	Function transRecibeVendedor($aju_codigo, $usuario,$pwd)
{
		$consulta= new Consultas();
		$ingresoTransaccion1 = $consulta->Trans_Recibe_Vendedor($aju_codigo, $usuario,$pwd);

		if($ingresoTransaccion1!="")
		{
			return $ingresoTransaccion1;
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