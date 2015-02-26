<?php
include_once("variables_session.php");
/*
 * ESTABLECE LA CONEXION CON LA BASE DE DATOS
 * */
$db_link = new PDO($dsn, $username, $passwd);
$valor 			= utf8_encode($_REQUEST['term']);
$jva_codigo 	= $_REQUEST['jva_codigo'];
/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
$query = 	"SELECT codigo, nroidentificacion, razon_social
					from admin_clientes
					where est_codigo = 1
					and nroidentificacion like '%$valor%'
					or razon_social like '%$valor%'
					or nombre_contacto like '%$valor%'";
//echo $query;
$result = $db_link->query($query);
$resultado = array();
while($row = $result->fetch(PDO::FETCH_ASSOC))
{
	array_push($resultado, array(
								'id' 							=> $row['codigo'],
								'value'							=> utf8_decode($row['razon_social']),
								'label' 						=> utf8_decode($row['razon_social']) ." Identificacion:".$row['nroidentificacion'],
								'cliente_codigo' 				=> $row['codigo'],
								'cliente_razonsocial'			=> utf8_decode($row['razon_social']),
								'servicio_nroidentificacion'	=> utf8_decode($row['nroidentificacion']),
								)
				);
}
echo json_encode($resultado);
?>