<?php
/*
 * @author:	MIGUEL ANGEL POSADA
 * 			miguelrodriguezpo@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
//session_start();
include_once ("../logica/variables_session.php");
try
{
	/*SE CREA LA INSTANCIA DEL OBJETO, SE REALIZA LA CONEXION A LA BD*/
	$db_link = new PDO($dsn, $username, $passwd);
	$accion = $_REQUEST['ACCION'];
	$msg="";

	if ($accion=="mostrar_front")
	{
		include '../presentacion/admin_ventas.php';		
	}
	
	if ($accion=="agregar_producto_venta")
	{
		$pro_codigo = $_REQUEST['PRO_CODIGO'];
		include '../presentacion/admin_ventas.php';		
	}

	if($accion == "consulta_like")
	{
		$campo = $_REQUEST['CAMPO'];
		$valor = $_REQUEST['VALOR'];
		$query = "SELECT * FROM trans_ventas WHERE $campo = '$valor'";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*SI EL ERROR ES 00000 TODO BIEN CONSULTA CORRECTA*/
		if($error == "00000")
		{
			//$db_link->commit();
			$RowCount = 0;
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
			{
				$detalle['codigo'][$RowCount]					=$row['codigo'];
				$detalle['codigo_barras'][$RowCount]				=$row['codigo_barras'];
				$detalle['referencia'][$RowCount]				=$row['referencia'];
				$detalle['fecha_solicitud'][$RowCount]			=$row['fecha_solicitud'];
				$detalle['cli_codigo'][$RowCount]				=$row['cli_codigo'];
				$detalle['aju_codigo'][$RowCount]				=$row['aju_codigo'];
				$detalle['ppj_codigo'][$RowCount]				=$row['ppj_codigo'];
				$detalle['tp_codigo'][$RowCount]					=$row['tp_codigo'];
				$detalle['pnc_codigo'][$RowCount]				=$row['pnc_codigo'];
				$detalle['valor_producto'][$RowCount]			=$row['valor_producto'];
				$detalle['valor_comision_servicio'][$RowCount]	=$row['valor_comision_servicio'];
				$detalle['valor_impuesto'][$RowCount]			=$row['valor_impuesto'];
				$detalle['valor_total'][$RowCount]				=$row['valor_total'];
				$detalle['fecha_entrega'][$RowCount]				=$row['fecha_entrega'];
				$detalle['longitud'][$RowCount]					=$row['longitud'];
				$detalle['latitud'][$RowCount]					=$row['latitud'];
				$detalle['est_codigo'][$RowCount]				=$row['est_codigo'];
				$RowCount++;
			}
		}
		else
		{
			//$db_link->rollBack();
			?>
			<script>alert("Error al consultar la informacion "+<?php echo $error?>);</script>
			<?php
		}
		include ('../presentacion/admin_ventas_listar.php');
	}

	if($accion == "listar")
	{
		/*CONSULTA TODAS LAS VENTAS*/
		$query = "SELECT * FROM trans_ventas";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*SI EL ERROR ES 00000 TODO BIEN CONSULTA CORRECTA*/
		if($error == "00000")
		{
			//$db_link->commit();
			$RowCount = 0;
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
			{
				$detalle['codigo'][$RowCount]					=$row['codigo'];
				$detalle['codigo_barras'][$RowCount]				=$row['codigo_barras'];
				$detalle['referencia'][$RowCount]				=$row['referencia'];
				$detalle['fecha_solicitud'][$RowCount]			=$row['fecha_solicitud'];
				$detalle['cli_codigo'][$RowCount]				=$row['cli_codigo'];
				$detalle['aju_codigo'][$RowCount]				=$row['aju_codigo'];
				$detalle['ppj_codigo'][$RowCount]				=$row['ppj_codigo'];
				$detalle['tp_codigo'][$RowCount]					=$row['tp_codigo'];
				$detalle['pnc_codigo'][$RowCount]				=$row['pnc_codigo'];
				$detalle['valor_producto'][$RowCount]			=$row['valor_producto'];
				$detalle['valor_comision_servicio'][$RowCount]	=$row['valor_comision_servicio'];
				$detalle['valor_impuesto'][$RowCount]			=$row['valor_impuesto'];
				$detalle['valor_total'][$RowCount]				=$row['valor_total'];
				$detalle['fecha_entrega'][$RowCount]				=$row['fecha_entrega'];
				$detalle['longitud'][$RowCount]					=$row['longitud'];
				$detalle['latitud'][$RowCount]					=$row['latitud'];
				$detalle['est_codigo'][$RowCount]				=$row['est_codigo'];
				$RowCount++;
			}
		}
		else
		{
			//$db_link->rollBack();
			?>
			<script>alert("Error al consultar la informacion "+<?php echo $error?>);</script>
			<?php
		}
		include ('../presentacion/admin_ventas_listar.php');
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