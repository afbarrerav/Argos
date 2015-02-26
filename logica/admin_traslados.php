<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2012
 *
 * */
//session_start();
include_once ("../logica/variables_session.php");
try
{

	$db_link = new PDO($dsn, $username, $passwd);
	$accion = $_REQUEST['ACCION'];
	$traslado = 0;
	if($accion == "mostrar_front")
	{
		include ('../presentacion/admin_traslados.php');
	}
	/*
	 * creamos traslado
	 * 1- consultamos la parametrizacion del traslado
	 * 2- 
	 * */
	if($accion == "crear_traslado")
	{
		$jva_codigo 		= $_REQUEST['JVA_CODIGO'];
		$ptij_codigo 		= $_REQUEST['PTIJ_CODIGO'];
		$aju_codigo_desde 	= $_REQUEST['AJU_CODIGO_DESDE'];
		$aju_codigo_hasta 	= $_REQUEST['AJU_CODIGO_HASTA'];
		$fecha_traslado		= $_REQUEST['FECHA_TRASLADO'];
		$cantidad 			= $_REQUEST['CANTIDAD'];

		/*VALIDAMOS LA PARAMETRIZACION DE LOS TRASLADOS PARA LA INSERCION
		* 1 - Consultamos el rol del usuario con el aju_codigo
		* 2 - Consultamos la parametrizacion para el jva y el tipo de traslado
		* 3 - Validamos si la informacion recibida es valida para el traslado y si lo es creamos el traslado
		*/

		/* 1 - Consultamos el rol del usuario con el aju_codigo
		*/
		$query_rol = "select fun_obtener_rol_codigo($aju_codigo_desde) as rol_desde, fun_obtener_rol_codigo($aju_codigo_hasta) as rol_hasta";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result_rol = $db_link->prepare($query_rol);
		$db_link_rol->beginTransaction();
		$result_rol->execute(); //SE EJECUTA EL QUERY
		$arr_rol = $result_rol->errorInfo(); // SE OBTIENE EL ERROR
		$error_rol = $arr_rol[0];
		$errorMessage_rol = str_replace("'", "", $arr_rol[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error_rol=="00000")
		{
			$db_link->commit();
			
			$row_rol = $result_rol->fetch(PDO::FETCH_ASSOC);
			{
				$rol_desde	= $row_rol['rol_desde'];
				$rol_hasta	= $row_rol['rol_hasta'];
			}
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener el rol del usuario $errorMessage_rol";
			echo $msg;
		}

		/* 2 - Consultamos la parametrizacion para el jva y el tipo de traslado
		*/
		$query_ptij = "SELECT tti_codigo, rol_codigo_desde, rol_codigo_hasta, ppj_codigo, cantidad_minima, cantidad_maxima, requiere_autorizacion, requiere_verificacion
						FROM param_traslados_inventarios_jva
						WHERE codigo = $ptij_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result_ptij = $db_link->prepare($query_ptij);
		$db_link_ptij->beginTransaction();
		$result_ptij->execute(); //SE EJECUTA EL QUERY
		$arr_ptij = $result_ptij->errorInfo(); // SE OBTIENE EL ERROR
		$error_ptij = $arr_rol[0];
		$errorMessage_ptij = str_replace("'", "", $arr_ptij[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error_ptij=="00000")
		{
			$db_link->commit();
			
			$row_ptij = $result_ptij->fetch(PDO::FETCH_ASSOC);
			{
				$tti_codigo				= $row_ptij['tti_codigo'];
				$rol_codigo_desde		= $row_ptij['rol_codigo_desde'];
				$rol_codigo_hasta		= $row_ptij['rol_codigo_hasta'];
				$ppj_codigo				= $row_ptij['ppj_codigo'];
				$cantidad_minima		= $row_ptij['cantidad_minima'];
				$cantidad_maxima		= $row_ptij['cantidad_maxima'];
				$requiere_autorizacion	= $row_ptij['requiere_autorizacion'];
				$requiere_verificacion	= $row_ptij['requiere_verificacion'];
			}
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener el rol del usuario $errorMessage_ptij";
			echo $msg;
		}

		/* 3 - Validamos si la informacion recibida es valida para el traslado y si lo es creamos el traslado
		*/
		if ($aju_codigo_desde==$rol_codigo_desde) 
		{
			if ($aju_codigo_hasta==$rol_codigo_hasta) 
			{
				if ($cantidad>$cantidad_minima)
				{
					if ($cantidad<$cantidad_maxima) 
					{
						
					}
					
				}
			}
		}

		








		$query =	"insert into trans_traslados_inventarios_jva (ptij_codigo, aju_codigo_desde, aju_codigo_hasta, ppj_codigo, cantidad, fecha_traslado, fecha_aprovacion, fecha_verificacion, fecha_trans, latitud, longitud, est_codigo)
					values (:ptij_codigo, :aju_codigo_desde, :aju_codigo_hasta, '0', :cantidad, :fecha_traslado, now(), now(), now(), '0', '0', '1')";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':ptij_codigo',$ptij_codigo);
		$result->bindParam(':aju_codigo_desde',$aju_codigo_desde);
		$result->bindParam(':aju_codigo_hasta',$aju_codigo_hasta);
		$result->bindParam(':fecha_traslado',$fecha_traslado);
		$result->bindParam(':cantidad',$cantidad);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr 			= $result->errorInfo(); // SE OBTIENE EL ERROR
		$error			= $arr[0];
		$errorMessage	= str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$db_link->commit();
			?>
			<script>
				alert("Traslado creado satisfactoriamente!");		
			</script>
			<?php
			$accion = "listar_traslados_d";
			$traslado = "1";
		}
		else
		{
			$db_link->rollBack();
			?>
			<script>
				alert("Error al intentar crear el gasto: <?php echo $errorMessage?>");
			</script>
			<?php
		}
	}
	if($accion == "listar_traslados_d")
	{
		if ($traslado=="0") 
		{
			$jva_codigo = $_REQUEST['JVA_CODIGO'];
		}
		/*
		 * SE CONSTRUYE EL QUERY CONSULTA LAS TRANSACCIONES
		 * VALIDMOS SI EL JVA FUE SELECCIONADO O CONSULTAMOS POR EL USUARIO LOGEADO
		 * */
		if ($jva_codigo=="0") 
		{
			$query = "SELECT codigo, ptij_codigo, fun_obtener_aju_nombre(aju_codigo_desde), aju_codigo_hasta, cantidad, fecha_trans, est_codigo
						FROM trans_traslados_inventarios_jva
						WHERE aju_codigo_desde in (select codigo from admin_jva_usuarios where usu_codigo = $usu_codigo)
						AND aju_codigo_hasta in (select codigo from admin_jva_usuarios where usu_codigo = $usu_codigo)
						AND est_codigo = 1";
		}
		else
		{
			$query = "SELECT codigo, ptij_codigo, fun_obtener_aju_nombre(aju_codigo_desde) as aju_codigo_desde, fun_obtener_aju_nombre(aju_codigo_hasta) as aju_codigo_hasta, cantidad, fecha_traslado, est_codigo
						FROM trans_traslados_inventarios_jva
						WHERE aju_codigo_desde in (select codigo from admin_jva_usuarios where jva_codigo = $jva_codigo)
						AND aju_codigo_hasta in (select codigo from admin_jva_usuarios where jva_codigo = $jva_codigo)
						AND est_codigo = 1";
		}
		/*
		 * SE PREPARA EL QUERY
		 * */
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
		
		if($error=="00000")
		{
			$db_link->commit();
			$RowCount = 0;
			while ($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$resultado['codigo'][$RowCount] 			= $row['codigo'];
				$resultado['ptij_codigo'][$RowCount] 		= $row['ptij_codigo'];
				$resultado['aju_codigo_desde'][$RowCount]	= $row['aju_codigo_desde'];
				$resultado['aju_codigo_hasta'][$RowCount]	= $row['aju_codigo_hasta'];
				$resultado['cantidad'][$RowCount] 			= $row['cantidad'];
				$resultado['fecha_trans'][$RowCount] 		= $row['fecha_traslado'];
				$resultado['est_codigo'][$RowCount] 		= $row['est_codigo'];
				$RowCount++;
			}
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			echo $msg;
		}
		include_once('../presentacion/admin_traslados_l.php');
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