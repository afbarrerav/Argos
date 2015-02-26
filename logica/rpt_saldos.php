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
	
	if($accion == "mostrar_front_saldos")
	{		
		/*
		 * OBTIENE LOS JVA DEL USUARIO
		 * */
		$query_jva = 	"SELECT aju.jva_codigo, aj.nombre
							FROM admin_jva_usuarios aju, admin_jva aj
							WHERE usu_codigo =:usu_codigo 
							and rol_codigo in (2, 3)
							and aju.jva_codigo = aj.codigo";
		$result_jva = $db_link->prepare($query_jva);
		$db_link->beginTransaction();
		$result_jva->bindParam(':usu_codigo',$usu_codigo);
		$result_jva->execute(); //SE EJECUTA EL QUERY
		$arr_jva = $result_jva->errorInfo(); // SE OBTIENE EL ERROR
		$error_jva = $arr_jva[0];
		$errorMessage_jva = str_replace("'", "", $arr_jva[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		$RowCount=0;
		if($error_jva == "00000")
		{
			
			$db_link->commit();			
		 	while($row_jva = $result_jva->fetch(PDO::FETCH_ASSOC))
		 	{	
		 		$jva_codigo = $row_jva['jva_codigo'];
		 		$jva_nombre = $row_jva['nombre'];
		 		$resultado[$RowCount]['jva_codigo'] = $jva_codigo;
		 		$resultado[$RowCount]['jva_nombre'] = $jva_nombre;	 			
				/*
				 * OBTIENE LAS RUTAS DEL JVA
				 * */
				$query_rutas = "SELECT codigo
								FROM trans_rutas_jva
								where jva_codigo = $jva_codigo";
				$result_rutas = $db_link->prepare($query_rutas);
				$db_link->beginTransaction();
				$result_rutas->execute(); //SE EJECUTA EL QUERY
				$arr_rutas = $result_rutas->errorInfo(); // SE OBTIENE EL ERROR
				$error_rutas = $arr_rutas[0];
				$errorMessage_rutas = str_replace("'", "", $arr_rutas[2]);
				$cantidadRutas = 0;
				$clientesRuta = 0;
				$saldoRuta = 0;
				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error_rutas == "00000")
				{					
					$db_link->commit();
					while($row_rutas = $result_rutas->fetch(PDO::FETCH_ASSOC))
		 			{
		 				$cantidadRutas++;
		 				$trj_codigo = $row_rutas['codigo'];
						/*
						 * OBTIENE LOS PRODUCTOS DE LA RUTA
						 * */
		 				$query_ventas = "SELECT count(tv_codigo) clientes_ruta, sum(saldo) saldo_ruta 
		 								FROM trans_rutas_detalles 
		 								WHERE trj_codigo = $trj_codigo 
		 								AND est_codigo = 1";
						$result_ventas = $db_link->prepare($query_ventas);
						$db_link->beginTransaction();
						$result_ventas->execute(); //SE EJECUTA EL QUERY
						$arr_ventas = $result_ventas->errorInfo(); // SE OBTIENE EL ERROR
						$error_ventas = $arr_ventas[0];
						$errorMessage_ventas = str_replace("'", "", $arr_ventas[2]);
						/*
						 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
						 * */
						
						if($error_ventas == "00000")
						{
							$db_link->commit();														
							$row_ventas 	= $result_ventas->fetch(PDO::FETCH_ASSOC);
							$saldoRuta 		= $saldoRuta+$row_ventas['saldo_ruta'];
							$clientesRuta 	= $clientesRuta+$row_ventas['clientes_ruta'];
																							
						}
						else
						{
							$db_link->rollBack();
							?>
								<script>alert("Error al consultar los saldos <?php echo $error_ventas." ".$errorMessage_ventas?>");</script>
							<?php
						}						
		 			}
		 			$resultado[$RowCount]['nro_clientes'] 	= $clientesRuta;
		 			$resultado[$RowCount]['nro_rutas'] 		= $cantidadRutas;
		 			$resultado[$RowCount]['saldo_rutas'] 	= $saldoRuta;
				}
				else
				{
					$db_link->rollBack();
					?>
						<script>alert("Error al consultar las rutas <?php echo $error_rutas." ".$errorMessage_rutas?>");</script>
					<?php
				}
				$RowCount++;		
		 	}
		 	
		}
		else
		{
			$db_link->rollBack();
			?>
				<script>alert("Error al consultar los JVA's <?php echo $error_jva." ".$errorMessage_jva?>");</script>
			<?php
		}		
		include '../presentacion/rpt_saldos.php';
	}
	
	if($accion == "consultar_saldos")
	{
		/*
		 * QUERY QUE CONSULTA EL DETALLE DE SALDOS
		 * */
		$query = "SELECT trj.jva_codigo, trd.trj_codigo, trj.nombre as descripcion, COUNT(trd.tv_codigo) as cant_clientes, SUM(trd.saldo) as valor_cartera, au.nombres, au.apellidos
					FROM trans_rutas_jva trj, trans_rutas_detalles trd, admin_usuarios au, admin_jva_usuarios aju
					WHERE trj.codigo = trd.trj_codigo
					AND aju.usu_codigo = au.codigo
					AND trj.aju_codigo = aju.codigo
					AND trd.est_codigo = 1
					AND trj.jva_codigo
					IN (select jva_codigo from admin_jva_usuarios where rol_codigo in (2, 3) and usu_codigo =  $usu_codigo)
					GROUP BY jva_codigo, trj_codigo, nombre";
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
		if($error == "00000")
		{
			$db_link->commit();
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle_saldos['trj_codigo'][$RowCount] 		= $row['trj_codigo'];
				$detalle_saldos['descripcion'][$RowCount] 		= $row['descripcion'];
				$detalle_saldos['nombres'][$RowCount] 			= $row['nombres'];
				$detalle_saldos['apellidos'][$RowCount] 		= $row['apellidos'];
				$detalle_saldos['cant_clientes'][$RowCount] 	= $row['cant_clientes'];
				$detalle_saldos['valor_cartera'][$RowCount] 	= $row['valor_cartera'];
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
		include ('../presentacion/rpt_saldos.php');
	}
	
	if($accion == "consulta_saldos_tipo_cliente")
	{
		$trj_codigo = $_REQUEST['TRJ_CODIGO'];
		$tipo_cliente = $_REQUEST['TIPO_CLIENTE'];
		$vendedor	= $_REQUEST['VENDEDOR'];
		$ruta		= $_REQUEST['RUTA'];
		if($tipo_cliente=="TODOS")
		{
			$condicion = "";
		}
		else 
		{
			$condicion = "where est_codigo = '$tipo_cliente'";
		}
		$query=	"SELECT *
				FROM (
					SELECT fun_tipo_cliente(tv.codigo)est_codigo, tv.referencia, trd.tv_codigo, tv.cli_codigo, tv.fecha_entrega, cli.nroidentificacion, 
						UCASE(LTRIM(CONCAT(cli.nombre1_contacto,  ' ', cli.nombre2_contacto,  ' ', cli.apellido1_contacto,  ' ', cli.apellido2_contacto ))) CLIENTE, 
						tv.valor_total, tv.valor_producto, fun_valor_pago_cuota_recaudo(trd.tv_codigo)valor_cuota, fun_nro_cuotas_recaudo(trd.tv_codigo)nro_cuotas, 
						fun_nrocuotas_recaudadas(trd.tv_codigo)cuotas_recaudadas, fun_nrocuotas_pendientes_recaudo(trd.tv_codigo)cuotas_pendientes, trd.saldo
					FROM trans_rutas_detalles trd, trans_ventas tv, admin_clientes cli
					WHERE trd.trj_codigo = $trj_codigo
					AND trd.tv_codigo = tv.codigo
					AND tv.cli_codigo = cli.codigo
					AND trd.est_codigo =1) saldos_rutas
				$condicion";
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
		if($error == "00000")
		{
			$db_link->commit();
			$RowCount = 0;
			$tipos_clientes['Activo'] = 0;
			$tipos_clientes['Moroso'] = 0;
			$tipos_clientes['Moroso 120'] = 0;
			$tipos_clientes['Moroso 180'] = 0;
			$tipos_clientes['Moroso 180 +'] = 0;
			$total_valor_total = '';
			$total_valor_producto = '';
			$valor_total = '';
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['tv_codigo'][$RowCount] 			= $row['tv_codigo'];
				$detalle['cli_codigo'][$RowCount] 			= $row['cli_codigo'];
				$detalle['referencia'][$RowCount] 			= $row['referencia'];
				$detalle['nroidentificacion'][$RowCount] 	= $row['nroidentificacion'];
				$detalle['CLIENTE'][$RowCount] 				= $row['CLIENTE'];
				$detalle['fecha_entrega'][$RowCount] 		= $row['fecha_entrega'];
				$detalle['valor_total'][$RowCount] 			= $row['valor_total'];
				$detalle['valor_producto'][$RowCount] 		= $row['valor_producto'];
				$detalle['valor_cuota'][$RowCount] 			= $row['valor_cuota'];
				$detalle['nro_cuotas'][$RowCount] 			= $row['nro_cuotas'];
				$detalle['cuotas_recaudadas'][$RowCount] 	= $row['cuotas_recaudadas'];
				$detalle['cuotas_pendientes'][$RowCount] 	= $row['cuotas_pendientes'];
				$detalle['saldo'][$RowCount] 				= $row['saldo'];
				$detalle['est_codigo'][$RowCount] 			= $row['est_codigo'];
				$nombre = $row['est_codigo'];
				$tipos_clientes[$nombre]= $tipos_clientes[$nombre]+1;
				
				$total_valor_producto = $total_valor_producto + $detalle['valor_producto'][$RowCount];
				$total_valor_total = $total_valor_total + $detalle['valor_total'][$RowCount];
				$valor_total = $valor_total + $detalle['saldo'][$RowCount];
				
				$RowCount++;
			}
			//echo var_dump($tipos_clientes);
		}
		else
		{
			$db_link->rollBack();
			?>
				<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_saldos_tipo_cliente.php';
	}
	
	if($accion == "detalle_saldos_ruta")
	{
		$trj_codigo = $_REQUEST['TRJ_CODIGO'];
		$vendedor	= $_REQUEST['VENDEDOR'];
		$ruta		= $_REQUEST['RUTA'];
		//$valor_total = $_REQUEST['VALOR_TOTAL'];
		/*
		 * QUERY QUE CONSULTA EL DETALLE DEL RECAUDO PARA EL CLIENTE INDICADO
		 * */
		$query = "SELECT fun_tipo_cliente(tv.codigo) est_codigo, tv.referencia, trd.tv_codigo, tv.cli_codigo, tv.fecha_entrega, cli.nroidentificacion, UCASE( LTRIM( CONCAT( cli.nombre1_contacto,  ' ', cli.nombre2_contacto,  ' ', cli.apellido1_contacto,  ' ', cli.apellido2_contacto ) ) ) CLIENTE, tv.valor_total, tv.valor_producto, fun_valor_pago_cuota_recaudo(trd.tv_codigo)valor_cuota, fun_nro_cuotas_recaudo(trd.tv_codigo)nro_cuotas, fun_nrocuotas_recaudadas(trd.tv_codigo)cuotas_recaudadas, fun_nrocuotas_pendientes_recaudo(trd.tv_codigo)cuotas_pendientes, trd.saldo
					FROM trans_rutas_detalles trd, trans_ventas tv, admin_clientes cli
					WHERE trd.trj_codigo = $trj_codigo
					AND trd.tv_codigo = tv.codigo
					AND tv.cli_codigo = cli.codigo
					AND trd.est_codigo = 1";
		
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
		if($error == "00000")
		{
			$db_link->commit();
			$RowCount = 0;
			$tipos_clientes['Activo'] = 0;
			$tipos_clientes['Moroso'] = 0;
			$tipos_clientes['Moroso 120'] = 0;
			$tipos_clientes['Moroso 180'] = 0;
			$tipos_clientes['Moroso 180 +'] = 0;
			$total_valor_total = '';
			$total_valor_producto = '';
			$valor_total = '';
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['tv_codigo'][$RowCount] 			= $row['tv_codigo'];
				$detalle['cli_codigo'][$RowCount] 			= $row['cli_codigo'];
				$detalle['referencia'][$RowCount] 			= $row['referencia'];
				$detalle['nroidentificacion'][$RowCount] 	= $row['nroidentificacion'];
				$detalle['CLIENTE'][$RowCount] 				= $row['CLIENTE'];
				$detalle['fecha_entrega'][$RowCount] 		= $row['fecha_entrega'];
				$detalle['valor_total'][$RowCount] 			= $row['valor_total'];
				$detalle['valor_producto'][$RowCount] 		= $row['valor_producto'];
				$detalle['valor_cuota'][$RowCount] 			= $row['valor_cuota'];
				$detalle['nro_cuotas'][$RowCount] 			= $row['nro_cuotas'];
				$detalle['cuotas_recaudadas'][$RowCount] 	= $row['cuotas_recaudadas'];
				$detalle['cuotas_pendientes'][$RowCount] 	= $row['cuotas_pendientes'];
				$detalle['saldo'][$RowCount] 				= $row['saldo'];
				$detalle['est_codigo'][$RowCount] 			= $row['est_codigo'];
				$nombre = $row['est_codigo'];
				$tipos_clientes[$nombre]= $tipos_clientes[$nombre]+1;
				
				$total_valor_producto = $total_valor_producto + $detalle['valor_producto'][$RowCount];
				$total_valor_total = $total_valor_total + $detalle['valor_total'][$RowCount];
				$valor_total = $valor_total + $detalle['saldo'][$RowCount];
				
				$RowCount++;
			}
			//echo var_dump($tipos_clientes);
		}
		else
		{
			$db_link->rollBack();
			?>
				<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_saldos_detalle.php';
	}
	
	if($accion == "consultar_saldo_socio")
	{
		/*
		 * CONSULTA TODOS LOS JVA DEL REPORTE SELECCIONADO
		 * */
	$query = "SELECT trj.jva_codigo, trd.trj_codigo, trj.nombre as descripcion, COUNT(trd.tv_codigo) as cant_clientes, SUM(trd.saldo) as valor_cartera, CONCAT(au.nombres, ' ', au.apellidos) AS nombres
					FROM trans_rutas_jva trj, trans_rutas_detalles trd, admin_usuarios au, admin_jva_usuarios aju
					WHERE trj.codigo = trd.trj_codigo
					AND aju.usu_codigo = au.codigo
					AND trj.aju_codigo = aju.codigo
					AND trd.est_codigo = 1
					AND trj.jva_codigo
					IN (select jva_codigo from admin_jva_usuarios where rol_codigo in (2, 3) and usu_codigo =  $usu_codigo)
					GROUP BY jva_codigo, trj_codigo, nombre";
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
		if($error == "00000")
		{
			$db_link->commit();
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['trj_codigo'][$RowCount] 		= $row['trj_codigo'];
				$detalle['descripcion'][$RowCount] 		= $row['descripcion'];
				$detalle['nombres'][$RowCount] 			= $row['nombres'];
				$detalle['cant_clientes'][$RowCount] 	= $row['cant_clientes'];
				$detalle['valor_cartera'][$RowCount] 	= $row['valor_cartera'];
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
		 include '../presentacion/rpt_reportes_saldos.php';
	}
	if($accion == "consultar_saldo_socio_fechaInicio_fechaFin")
	{
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTA TODOS LOS JVA CON LAS FECHAS SELECCIONADAS 
		 * */
		$query = "SELECT fun_tipo_cliente(tv.codigo) est_codigo, tv.referencia, trd.tv_codigo, tv.cli_codigo, tv.fecha_entrega, cli.nroidentificacion, UCASE( LTRIM( CONCAT( cli.nombre1_contacto,  ' ', cli.nombre2_contacto,  ' ', cli.apellido1_contacto,  ' ', cli.apellido2_contacto ) ) ) CLIENTE, tv.valor_total, tv.valor_producto, fun_valor_pago_cuota_recaudo(trd.tv_codigo)valor_cuota, fun_nro_cuotas_recaudo(trd.tv_codigo)nro_cuotas, fun_nrocuotas_recaudadas(trd.tv_codigo)cuotas_recaudadas, fun_nrocuotas_pendientes_recaudo(trd.tv_codigo)cuotas_pendientes, trd.saldo, CONCAT(au.nombres, ' ',au.apellidos) vendedor
					FROM trans_rutas_detalles trd, trans_ventas tv, admin_clientes cli, trans_rutas_jva trj, admin_jva_usuarios aju, admin_usuarios au
					WHERE trj.codigo = trd.trj_codigo
					AND trd.est_codigo = 1
					AND trj.jva_codigo IN (select jva_codigo from admin_jva_usuarios where rol_codigo in (2, 3) and usu_codigo =  $usu_codigo)
					AND trd.tv_codigo = tv.codigo
					AND tv.cli_codigo = cli.codigo
					AND tv.fecha_entrega BETWEEN '$fecha_inicio' AND '$fecha_fin'
					AND trj.aju_codigo = aju.codigo
					AND aju.usu_codigo = au.codigo";
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
				$detalle['cli_codigo'][$RowCount] 			= $row['cli_codigo'];
				$detalle['referencia'][$RowCount] 			= $row['referencia'];
				$detalle['nroidentificacion'][$RowCount] 	= $row['nroidentificacion'];
				$detalle['CLIENTE'][$RowCount] 				= $row['CLIENTE'];
				$detalle['fecha_entrega'][$RowCount] 		= $row['fecha_entrega'];
				$detalle['valor_total'][$RowCount] 			= $row['valor_total'];
				$detalle['valor_producto'][$RowCount] 		= $row['valor_producto'];
				$detalle['valor_cuota'][$RowCount] 			= $row['valor_cuota'];
				$detalle['nro_cuotas'][$RowCount] 			= $row['nro_cuotas'];
				$detalle['cuotas_recaudadas'][$RowCount] 	= $row['cuotas_recaudadas'];
				$detalle['cuotas_pendientes'][$RowCount] 	= $row['cuotas_pendientes'];
				$detalle['saldo'][$RowCount] 				= $row['saldo'];
				$detalle['est_codigo'][$RowCount] 			= $row['est_codigo'];
				$detalle['vendedor'][$RowCount] 			= $row['vendedor'];
		 		$RowCount++;
		 	}
		 }
		 else
		 {
		 	$db_link->rollBack();
		 	?>
		 	<script>alert('Error al consultar la informacion <?php echo $error." ".$errorMessage?>');</script>
		 	<?php
		 }
		 include '../presentacion/rpt_reportes_saldos.php';
	}
	if($accion == "consultar_saldos_socio_jva")
	{
		$jva = $_REQUEST['sjva'];
		/*
		 * CONSULTA LA TABLA TRANS_RUTAS_DETALLE CON EL JVA SELECCIONADO
		 * */
		$query = "SELECT trj.jva_codigo, trd.trj_codigo, trj.nombre as descripcion, COUNT(trd.tv_codigo) as cant_clientes, SUM(trd.saldo) as valor_cartera, CONCAT(au.nombres, ' ', au.apellidos) as nombres
					FROM trans_rutas_jva trj, trans_rutas_detalles trd, admin_usuarios au, admin_jva_usuarios aju
					WHERE trj.codigo = trd.trj_codigo
					AND aju.usu_codigo = au.codigo
					AND trj.aju_codigo = aju.codigo
					AND trd.est_codigo = 1
					AND trj.jva_codigo
					IN (
					SELECT jva_codigo
					FROM admin_jva_usuarios
					WHERE jva_codigo =  '$jva'
					)
					GROUP BY jva_codigo, trj_codigo, nombre";
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
		if($error == "00000")
		{
			$db_link->commit();
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['trj_codigo'][$RowCount] 		= $row['trj_codigo'];
				$detalle['descripcion'][$RowCount] 		= $row['descripcion'];
				$detalle['nombres'][$RowCount] 			= $row['nombres'];
				$detalle['cant_clientes'][$RowCount] 	= $row['cant_clientes'];
				$detalle['valor_cartera'][$RowCount] 	= $row['valor_cartera'];
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
		include '../presentacion/rpt_reportes_saldos.php';
	}
	
	if($accion == "consultar_saldos_socio_jva_fechaInicio_fechaFin")
	{
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		$jva = $_REQUEST['sjva'];
		/*
		 * CONSULTA TODOS LOS JVA CON LAS FECHAS SELECCIONADAS 
		 * */
		$query = "SELECT fun_tipo_cliente(tv.codigo) est_codigo, tv.referencia, trd.tv_codigo, tv.cli_codigo, tv.fecha_entrega, cli.nroidentificacion, UCASE( LTRIM( CONCAT( cli.nombre1_contacto,  ' ', cli.nombre2_contacto,  ' ', cli.apellido1_contacto,  ' ', cli.apellido2_contacto ) ) ) CLIENTE, tv.valor_total, tv.valor_producto, fun_valor_pago_cuota_recaudo(trd.tv_codigo)valor_cuota, fun_nro_cuotas_recaudo(trd.tv_codigo)nro_cuotas, fun_nrocuotas_recaudadas(trd.tv_codigo)cuotas_recaudadas, fun_nrocuotas_pendientes_recaudo(trd.tv_codigo)cuotas_pendientes, trd.saldo, CONCAT(au.nombres, ' ',au.apellidos) vendedor
					FROM trans_rutas_detalles trd, trans_ventas tv, admin_clientes cli, trans_rutas_jva trj, admin_jva_usuarios aju, admin_usuarios au
					WHERE trj.codigo = trd.trj_codigo
					AND trd.est_codigo = 1
					AND trj.jva_codigo IN (select jva_codigo from admin_jva_usuarios where rol_codigo in (2, 3) and usu_codigo =  $usu_codigo)
					AND trd.tv_codigo = tv.codigo
					AND tv.cli_codigo = cli.codigo
					AND tv.fecha_entrega BETWEEN '$fecha_inicio' AND '$fecha_fin'
					AND trj.aju_codigo = aju.codigo
					AND aju.usu_codigo = au.codigo";
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
				$detalle['cli_codigo'][$RowCount] 			= $row['cli_codigo'];
				$detalle['referencia'][$RowCount] 			= $row['referencia'];
				$detalle['nroidentificacion'][$RowCount] 	= $row['nroidentificacion'];
				$detalle['CLIENTE'][$RowCount] 				= $row['CLIENTE'];
				$detalle['fecha_entrega'][$RowCount] 		= $row['fecha_entrega'];
				$detalle['valor_total'][$RowCount] 			= $row['valor_total'];
				$detalle['valor_producto'][$RowCount] 		= $row['valor_producto'];
				$detalle['valor_cuota'][$RowCount] 			= $row['valor_cuota'];
				$detalle['nro_cuotas'][$RowCount] 			= $row['nro_cuotas'];
				$detalle['cuotas_recaudadas'][$RowCount] 	= $row['cuotas_recaudadas'];
				$detalle['cuotas_pendientes'][$RowCount] 	= $row['cuotas_pendientes'];
				$detalle['saldo'][$RowCount] 				= $row['saldo'];
				$detalle['est_codigo'][$RowCount] 			= $row['est_codigo'];
				$detalle['vendedor'][$RowCount] 			= $row['vendedor'];
				$vendedor = $detalle['vendedor'][$RowCount];
		 		$RowCount++;
		 	}
		 }
		 else
		 {
		 	$db_link->rollBack();
		 	?>
		 	<script>alert('Error al consultar la informacion <?php echo $error." ".$errorMessage?>');</script>
		 	<?php
		 }
		 include '../presentacion/rpt_reportes_saldos.php';
	}
	
	if($accion == "consultar_saldos_socio_jva_vendedor")
	{
		$vendedor = $_REQUEST['svededoresjva'];
		$jva = $_REQUEST['sjva'];
		
		/*
		 *CONSULTA LA INFORMACION SEGUN EL JAVA Y VENDEDOR SELECCIONADO
		 * */
		$query = "SELECT trj.jva_codigo, trd.trj_codigo, trj.nombre as descripcion, COUNT(trd.tv_codigo) as cant_clientes, SUM(trd.saldo) as valor_cartera, CONCAT(au.nombres, ' ', au.apellidos) as nombres
					FROM trans_rutas_jva trj, trans_rutas_detalles trd, admin_usuarios au, admin_jva_usuarios aju
					WHERE trj.codigo = trd.trj_codigo
					AND au.codigo = $vendedor
					AND aju.usu_codigo = au.codigo
					AND trj.aju_codigo = aju.codigo
					AND trd.est_codigo = 1
					AND trj.jva_codigo
					IN (
					SELECT jva_codigo
					FROM admin_jva_usuarios
					WHERE jva_codigo =  '$jva'
					)
					GROUP BY jva_codigo, trj_codigo, nombre";
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
		if($error == "00000")
		{
			$db_link->commit();
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['trj_codigo'][$RowCount] 		= $row['trj_codigo'];
				$detalle['descripcion'][$RowCount] 		= $row['descripcion'];
				$detalle['nombres'][$RowCount] 			= $row['nombres'];
				$detalle['cant_clientes'][$RowCount] 	= $row['cant_clientes'];
				$detalle['valor_cartera'][$RowCount] 	= $row['valor_cartera'];
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
		include '../presentacion/rpt_reportes_saldos.php';
	}
	
	if($accion == "consultar_saldo_socio_jva_vendedor_fechaInicio_fechaFin")
	{
		$vendedor = $_REQUEST['svededoresjva'];
		$jva = $_REQUEST['sjva'];
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTA TODOS LOS JVA CON LAS FECHAS SELECCIONADAS 
		 * */
		$query = "SELECT fun_tipo_cliente(tv.codigo) est_codigo, tv.referencia, trd.tv_codigo, tv.cli_codigo, tv.fecha_entrega, cli.nroidentificacion, UCASE( LTRIM( CONCAT( cli.nombre1_contacto,  ' ', cli.nombre2_contacto,  ' ', cli.apellido1_contacto,  ' ', cli.apellido2_contacto ) ) ) CLIENTE, tv.valor_total, tv.valor_producto, fun_valor_pago_cuota_recaudo(trd.tv_codigo)valor_cuota, fun_nro_cuotas_recaudo(trd.tv_codigo)nro_cuotas, fun_nrocuotas_recaudadas(trd.tv_codigo)cuotas_recaudadas, fun_nrocuotas_pendientes_recaudo(trd.tv_codigo)cuotas_pendientes, trd.saldo, CONCAT(au.nombres, ' ',au.apellidos) vendedor
					FROM trans_rutas_detalles trd, trans_ventas tv, admin_clientes cli, trans_rutas_jva trj, admin_usuarios au, admin_jva_usuarios aju
					WHERE trj.codigo = trd.trj_codigo
					AND trd.est_codigo = 1
					AND trj.jva_codigo = $jva
					AND au.codigo = $vendedor
					AND aju.usu_codigo = au.codigo
					AND trj.aju_codigo = aju.codigo
					AND trd.tv_codigo = tv.codigo
					AND tv.cli_codigo = cli.codigo
					AND tv.fecha_entrega BETWEEN '$fecha_inicio 00:00:00' and '$fecha_fin 23:59:59'";
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
				$detalle['cli_codigo'][$RowCount] 			= $row['cli_codigo'];
				$detalle['referencia'][$RowCount] 			= $row['referencia'];
				$detalle['nroidentificacion'][$RowCount] 	= $row['nroidentificacion'];
				$detalle['CLIENTE'][$RowCount] 				= $row['CLIENTE'];
				$detalle['fecha_entrega'][$RowCount] 		= $row['fecha_entrega'];
				$detalle['valor_total'][$RowCount] 			= $row['valor_total'];
				$detalle['valor_producto'][$RowCount] 		= $row['valor_producto'];
				$detalle['valor_cuota'][$RowCount] 			= $row['valor_cuota'];
				$detalle['nro_cuotas'][$RowCount] 			= $row['nro_cuotas'];
				$detalle['cuotas_recaudadas'][$RowCount] 	= $row['cuotas_recaudadas'];
				$detalle['cuotas_pendientes'][$RowCount] 	= $row['cuotas_pendientes'];
				$detalle['saldo'][$RowCount] 				= $row['saldo'];
				$detalle['est_codigo'][$RowCount] 			= $row['est_codigo'];
				$detalle['vendedor'][$RowCount] 			= $row['vendedor'];
				$vendedor = $detalle['vendedor'][$RowCount];
		 		$RowCount++;
		 	}
		 }
		 else
		 {
		 	$db_link->rollBack();
		 	?>
		 	<script>alert('Error al consultar la informacion <?php echo $error." ".$errorMessage?>');</script>
		 	<?php
		 }
		 include '../presentacion/rpt_reportes_saldos.php';
	}
	
	if($accion == "consultar_saldo_cliente")
	{
		$cliente = $_REQUEST['cliente'];
		/*
		 * CONSULTA LA INFORMACION DEL CLIENTE INGRESADO
		 * */
		$query = "SELECT fun_tipo_cliente(tv.codigo) est_codigo, tv.referencia, trd.tv_codigo, tv.cli_codigo, tv.fecha_entrega, cli.nroidentificacion, UCASE( LTRIM( CONCAT( cli.nombre1_contacto,  ' ', cli.nombre2_contacto,  ' ', cli.apellido1_contacto,  ' ', cli.apellido2_contacto ) ) ) CLIENTE, tv.valor_total, tv.valor_producto, fun_valor_pago_cuota_recaudo(trd.tv_codigo)valor_cuota, fun_nro_cuotas_recaudo(trd.tv_codigo)nro_cuotas, fun_nrocuotas_recaudadas(trd.tv_codigo)cuotas_recaudadas, fun_nrocuotas_pendientes_recaudo(trd.tv_codigo)cuotas_pendientes, trd.saldo, CONCAT(au.nombres, ' ',au.apellidos) vendedor
					FROM trans_rutas_detalles trd, trans_ventas tv, admin_clientes cli, trans_rutas_jva trj, admin_jva_usuarios aju, admin_usuarios au
					WHERE CONCAT( cli.nombre1_contacto,  ' ', cli.apellido1_contacto ) LIKE  '%$cliente%'
					AND trd.trj_codigo = trj.codigo
					AND trd.est_codigo = 1
					AND trj.jva_codigo IN (select jva_codigo from admin_jva_usuarios where rol_codigo in (2, 3) and usu_codigo =  $usu_codigo)
					AND trd.tv_codigo = tv.codigo
					AND tv.cli_codigo = cli.codigo
					AND trj.aju_codigo = aju.codigo
					AND aju.usu_codigo = au.codigo";
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
				$detalle['cli_codigo'][$RowCount] 			= $row['cli_codigo'];
				$detalle['referencia'][$RowCount] 			= $row['referencia'];
				$detalle['nroidentificacion'][$RowCount] 	= $row['nroidentificacion'];
				$detalle['CLIENTE'][$RowCount] 				= $row['CLIENTE'];
				$detalle['fecha_entrega'][$RowCount] 		= $row['fecha_entrega'];
				$detalle['valor_total'][$RowCount] 			= $row['valor_total'];
				$detalle['valor_producto'][$RowCount] 		= $row['valor_producto'];
				$detalle['valor_cuota'][$RowCount] 			= $row['valor_cuota'];
				$detalle['nro_cuotas'][$RowCount] 			= $row['nro_cuotas'];
				$detalle['cuotas_recaudadas'][$RowCount] 	= $row['cuotas_recaudadas'];
				$detalle['cuotas_pendientes'][$RowCount] 	= $row['cuotas_pendientes'];
				$detalle['saldo'][$RowCount] 				= $row['saldo'];
				$detalle['est_codigo'][$RowCount] 			= $row['est_codigo'];
				$detalle['vendedor'][$RowCount] 			= $row['vendedor'];
				$vendedor = $detalle['vendedor'][$RowCount];
		 		$RowCount++;
		 	}
		 }
		 else
		 {
		 	$db_link->rollBack();
		 	?>
		 	<script>alert('Error al consultar la informacion <?php echo $error." ".$errorMessage?>');</script>
		 	<?php
		 }
		 include '../presentacion/rpt_reportes_saldos.php';
	}
	
	if($accion == "consultar_saldos_socio_consolidado")
	{
		/*
		 * CONSULTA LOS SALDOS DEL JVA
		 * */
		$query = "SELECT trj.nombre as nombre_ruta, trd.tv_codigo, tv.cli_codigo, UCASE( LTRIM( CONCAT( cli.nombre1_contacto,  ' ', cli.nombre2_contacto,  ' ', cli.apellido1_contacto,  ' ', cli.apellido2_contacto ) ) ) CLIENTE, tv.valor_total, fun_valor_pago_cuota_recaudo(trd.tv_codigo)valor_cuota, fun_nro_cuotas_recaudo(trd.tv_codigo)nro_cuotas, fun_nrocuotas_recaudadas(trd.tv_codigo)cuotas_recaudadas, fun_nrocuotas_pendientes_recaudo(trd.tv_codigo)cuotas_pendientes, trd.saldo
					FROM trans_rutas_detalles trd, trans_ventas tv, admin_clientes cli, trans_rutas_jva trj
					WHERE trd.trj_codigo = trj.codigo
					AND trd.est_codigo = 1
					AND trj.jva_codigo = $jva_codigo
					AND trd.tv_codigo = tv.codigo
					AND tv.cli_codigo = cli.codigo";
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
		 		$detalle['nombre_ruta'][$RowCount] 		= $row['nombre_ruta'];
		 		$detalle['tv_codigo'][$RowCount] 		= $row['tv_codigo'];
		 		$detalle['cli_codigo'][$RowCount] 		= $row['cli_codigo'];
		 		$detalle['CLIENTE'][$RowCount] 			= $row['CLIENTE'];
	 			$detalle['valor_total'][$RowCount] 		= $row['valor_total'];
		 		$detalle['valor_cuota'][$RowCount] 		= $row['valor_cuota'];
		 		$detalle['nro_cuotas'][$RowCount] 		= $row['nro_cuotas'];
		 		$detalle['cuotas_recaudadas'][$RowCount]= $row['cuotas_recaudadas'];
		 		$detalle['cuotas_pendientes'][$RowCount]= $row['cuotas_pendientes'];
		 		$detalle['saldo'][$RowCount]			= $row['saldo'];
		 		$RowCount++;
		 	}
		 }
		 else
		 {
		 	$db_link->rollBack();
		 	?>
		 	<script>alert('Error al consultar la informacion <?php echo $error." ".$errorMessage?>');</script>
		 	<?php
		 }
		 include '../presentacion/rpt_reportes_saldos.php';
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
