<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Octubre de 2012
 *
 * */
//session_start();
include_once ("../logica/variables_session.php");
$RowCount = 0;
try
{
	/*SE CREA LA INSTANCIA DEL OBJETO, SE REALIZA LA CONEXION A LA BD*/
	$db_link = new PDO($dsn, $username, $passwd);
	$msg="";
	$fecha = date('Y-m-d');
	$fecha_desde = $fecha." 00:00:00";
	$fecha_hasta = $fecha." 23:59:59";
	$latitud = 0;
	$longitud = 0;
	
	/*
	 * 1-OBTENER VENDEDOR DEL JVA
	 * 2-OBTENER EL CODIGO Y FECHA DEL ULTIMO RECAUDO DEL VENDEDOR
	 * 3-OBTENER LAS COORDENADAS DEL RECAUDO
	 * */
	$query = "SELECT aju.codigo codigo, CONCAT(au.nombres, ' ',au.apellidos) as nombre
				FROM `admin_jva_usuarios` aju, admin_usuarios au
				WHERE jva_codigo = $jva_codigo
				AND rol_codigo = 6
				AND aju.usu_codigo = au.codigo
				";
	//echo $query;
	$result = $db_link->prepare($query);
	$db_link->beginTransaction();
	$result->execute(); //SE EJECUTA EL QUERY
	$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
	$error = $arr[0];
	$errorMessage = str_replace("'", "", $arr[2]);
	/*
	 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
	 * */
	if($error == "00000")
	{
		$db_link->commit();
		$RowCount = 0;
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$detalle['codigo'][$RowCount] 		= $row['codigo'];
			$detalle['nombre'][$RowCount] 		= $row['nombre'];
			$ven_codigo = $detalle['codigo'][$RowCount];
			$ven_nombre = $detalle['nombre'][$RowCount];
			/*
			 * 2-OBTENER EL CODIGO Y FECHA DEL ULTIMO RECAUDO DEL VENDEDOR
			 * */
			$query_R = "SELECT codigo, fecha_recaudo, longitud, latitud
				        FROM trans_detalle_recaudo_ventas_jva
				        WHERE aju_codigo =$ven_codigo
				        AND LONGITUD != 0
						AND LATITUD != 0
				        ORDER BY fecha_recaudo DESC 
				        LIMIT 1";
			//echo $query_R;
			$result_R = $db_link->prepare($query_R);
			$db_link->beginTransaction();
			$result_R->execute(); //SE EJECUTA EL QUERY
			$arr_R = $result_R->errorInfo(); // SE OBTIENE EL ERROR
			$error_R = $arr_R[0];
			$errorMessage_R = str_replace("'", "", $arr_R[2]);
			/*
			 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
			 * */
			//echo $error_R;
			if($error_R == "00000")
			{
				$db_link->commit();
				
				$row_R = $result_R->fetch(PDO::FETCH_ASSOC);
				$codigo 			= $row_R['codigo'];
				$fecha_recaudo	 	= $row_R['fecha_recaudo'];
				$latitud		 	= $row_R['latitud'];
				$longitud 			= $row_R['longitud'];
				if ($latitud=="")
				{
					$latitud 	=0;
					$longitud	=0;
				}
			}
			else
			{
				$db_link->rollBack();
				
			}
			$detalle['codigo'][$RowCount]			= $codigo;
			$detalle['fecha_recaudo'][$RowCount]	= $fecha_recaudo;
			$detalle['latitud'][$RowCount] 			= $latitud;
			$detalle['longitud'][$RowCount] 		= $longitud;
			if ($latitud=="0")
			{
				
			}
			else
			{
				$RowCount++;
			}
		}
		include '../presentacion/dashboard_mapa_socio.php';
	}
	else
	{
		$db_link->rollBack();
		
	}
}
catch (PDOException $e)
{
	$msg = $e->getMessage();
	?>
	<script>
		alert("Excepcion controlada: <?php echo $msg?>");
		location.reload(true);
	</script>
	<?php 
}
?>