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
	$accion = $_REQUEST['ACCION'];
	$msg="";
	$fecha = date('Y-m-d');
	$fecha_desde = $fecha." 00:00:00";
	$fecha_hasta = $fecha." 23:59:59";
	//echo "Inicio: ".date('Y-m-d H:i:s')."<br>";

	if($accion == "mostrar_front_admin_recaudos")
	{
		include '../presentacion/admin_recaudos.php';
	}

	if($accion == "consultar_total_recaudos")
	{
		/*
		 * CONSULTA LOS RECAUDOS DEL VENDEDOR
		 * */
		$query = "SELECT ar.codigo, ar.nombre, ar.descripcion, ar.jva_codigo, ar.aju_codigo, fun_obtener_aju_nombre(ar.aju_codigo)aju_nombre, 
					ar.pbj_codigo, fun_nro_clientes_ruta(ar.codigo)nro_clientes, ar.saldo, ar.est_codigo
				FROM trans_rutas_jva ar, admin_usuarios au, admin_jva_usuarios aju
				WHERE aju.codigo = $aju_codigo
				AND aju.usu_codigo = au.codigo
				AND ar.aju_codigo = aju.codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		//echo $query;
		if($error == "00000")
		{
			$db_link->commit();
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['codigo'][$RowCount] 		= $row['codigo'];
				$detalle['nombre'][$RowCount] 		= $row['nombre'];
				$detalle['descripcion'][$RowCount] 	= $row['descripcion'];
				$detalle['aju_codigo'][$RowCount] 	= $row['aju_codigo'];
				$detalle['aju_nombre'][$RowCount] 	= $row['aju_nombre'];
				$detalle['pbj_codigo'][$RowCount] 	= $row['pbj_codigo'];
				$detalle['nro_clientes'][$RowCount] = $row['nro_clientes'];
				$detalle['saldo'][$RowCount] 		= $row['saldo'];
				$RowCount++;
			}
			
		}
		else
		{
			$db_link->rollBack();
			?>
				<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include ('../presentacion/admin_recaudos.php');
	}
	
	if($accion == "consultar_recaudos_vendedor")
	{
		/*
		 * CONSULTA EL RECAUDO TENIENDO EN VUENTA TODO EL FILTRO
		 * */
		$query = "SELECT trj.codigo, trj.jva_codigo, trj.aju_codigo, tv.valor_total AS valor_total, tv.valor_producto AS valor_producto, au.codigo AS codigo_vendedor, au.nombres AS nombre_vendedor, au.apellidos AS apellido_vendedor, tv.codigo AS tv_codigo, ac.codigo cli_codigo, ac.nroidentificacion, tv.referencia, fun_tipo_cliente(tv.codigo) est_codigo, CONCAT(ac.nombre1_contacto, ' ', apellido1_contacto) as cliente, tdrvj.fecha_recaudo, SUM(tdrvj.valor_recaudo) as valor_pago
						FROM trans_rutas_jva trj, admin_jva_usuarios aju, admin_usuarios au, admin_clientes ac, trans_ventas tv, trans_rutas_detalles trd, trans_historico_recaudo_ventas_jva tdrvj
						WHERE trj.aju_codigo = aju.codigo
						AND aju.usu_codigo = au.codigo
						AND trj.codigo = trd.trj_codigo
						AND trd.tv_codigo = tv.codigo
						AND trd.tv_codigo = tdrvj.tv_codigo
						AND tv.cli_codigo = ac.codigo
						AND aju.codigo = $aju_codigo
						AND tdrvj.fecha_recaudo
						BETWEEN  '$fecha_desde'
						AND  '$fecha_hasta'
						GROUP BY tv.cli_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		//echo $query;
		if($error == "00000")
		{
			$db_link->commit();
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['tv_codigo'][$RowCount] 			= $row['tv_codigo'];
				$detalle['cli_codigo'][$RowCount] 		= $row['cli_codigo'];
				$detalle['nroidentificacion'][$RowCount] 	= $row['nroidentificacion'];
				$detalle['referencia'][$RowCount] 		= $row['referencia'];
				$detalle['cliente'][$RowCount] 			= $row['cliente'];
				$detalle['fecha_recaudo'][$RowCount] 		= $row['fecha_recaudo'];
				$detalle['valor_producto'][$RowCount] 	= $row['valor_producto'];
				$detalle['valor_total'][$RowCount] 		= $row['valor_total'];
				$detalle['valor_pago'][$RowCount]			= $row['valor_pago'];
				$detalle['est_codigo'][$RowCount]			= $row['est_codigo'];
				/*---*/
				$detalle['aju_codigo'][$RowCount] 		= $row['aju_codigo'];
				$detalle['codigo_vendedor'][$RowCount] 	= $row['codigo_vendedor'];
				$detalle['nombre_vendedor'][$RowCount] 	= $row['nombre_vendedor'];
				$detalle['apellido_vendedor'][$RowCount] 	= $row['apellido_vendedor'];
				$RowCount++;
			}
			
		}
		else
		{
			$db_link->rollBack();
			?>
				<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include ('../presentacion/admin_recaudos.php');
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