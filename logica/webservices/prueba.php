<?php
require_once('server.argos.class.php');


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


echo "<pre>";			
$respuesta = insertarTransGastosJva("2013-01-30","10033","8","100","0","0","1","dpruebas","12345");
print_r($respuesta);

?>






