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
	
	if($accion == "mostrar_front_recaudos")
	{
		/*
		 * OBTIENE LOS JVA DEL USUARIO
		 * */
		$query_jva = "SELECT aju.jva_codigo, aj.nombre
						FROM admin_jva_usuarios aju, admin_jva aj
						WHERE usu_codigo =:usu_codigo 
						and rol_codigo=2
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
				$cantidadProdutos = 0;
				$valor_recaudo = 0;
				$valor_recaudado = 0;
				$resultado[$RowCount]['nro_clientes_recaudados'] = 0;
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
		 				$query_ventas = "SELECT tv_codigo 
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
							while($row_ventas = $result_ventas->fetch(PDO::FETCH_ASSOC))
							{
								$cantidadProdutos++;
								//echo $cantidadProdutos."<br>";
								$tv_codigo = $row_ventas['tv_codigo'];
								/*
								 * OBTIENE EL VALOR QUE SE DEBE RECAUDAR POR CADA PRODUCTO
								 * */
								$query_recaudo = 	"SELECT valor_pago
													FROM trans_detalle_recaudo_ventas_jva
													WHERE tv_codigo = $tv_codigo
													AND est_codigo in (3, 5, 10)
													AND fecha_pago <= '$fecha_hasta'
													ORDER BY cuota_nro
													LIMIT 0, 1";
								$result_recaudo = $db_link->prepare($query_recaudo);
								$db_link->beginTransaction();
								$result_recaudo->execute(); //SE EJECUTA EL QUERY
								$arr_recaudo = $result_recaudo->errorInfo(); // SE OBTIENE EL ERROR
								$error_recaudo = $arr_recaudo[0];
								$errorMessage_recaudo = str_replace("'", "", $arr_recaudo[2]);
								/*
								 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
								 * */
								if($error_recaudo == "00000")
								{								
									$db_link->commit();	
									$row_recaudo = $result_recaudo->fetch(PDO::FETCH_ASSOC);
									$valor_recaudo = $valor_recaudo + $row_recaudo['valor_pago'];								
						
								}
								else
								{
									$db_link->rollBack();
									?>
										<script>alert("Error al consultar los recaudos <?php echo $error_recaudo." ".$errorMessage_recaudo?>");</script>
									<?php
								}
								/*
								 * OBTIENE EL VALOR RECAUDADO EL DIA POR CADA PRODUCTO
								 * */
								$query_recaudado = "SELECT sum(valor_recaudo) valor_recaudo
													FROM trans_historico_recaudo_ventas_jva
													WHERE tv_codigo = $tv_codigo
													AND fecha_recaudo BETWEEN '$fecha_desde' AND '$fecha_hasta'";
								$result_recaudado = $db_link->prepare($query_recaudado);
								$db_link->beginTransaction();
								$result_recaudado->execute(); //SE EJECUTA EL QUERY
								$arr_recaudado = $result_recaudado->errorInfo(); // SE OBTIENE EL ERROR
								$error_recaudado = $arr_recaudado[0];
								$errorMessage_recaudado = str_replace("'", "", $arr_recaudado[2]);
								/*
								 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
								 * */
								if($error_recaudado == "00000")
								{								
									
									$db_link->commit();			
									$row_recaudado = $result_recaudado->fetch(PDO::FETCH_ASSOC);
									$valor_recaudado = $valor_recaudado + $row_recaudado['valor_recaudo'];
									if($row_recaudado['valor_recaudo']>0)
									{
										$resultado[$RowCount]['nro_clientes_recaudados']++;
									}						
						
								}
								else
								{
									$db_link->rollBack();
									?>
										<script>alert("Error al consultar los valores recaudados <?php echo $error_recaudado." ".$errorMessage_recaudado?>");</script>
									<?php
								}
							}
							$resultado[$RowCount]['valor_recaudo'] = $valor_recaudo;
							$resultado[$RowCount]['valor_recaudado'] = $valor_recaudado;																
						}
						else
						{
							$db_link->rollBack();
							?>
								<script>alert("Error al consultar los recaudos <?php echo $error_ventas." ".$errorMessage_ventas?>");</script>
							<?php
						}
						$resultado[$RowCount]['nro_clientes'] = $cantidadProdutos;
		 			}
		 			$resultado[$RowCount]['nro_rutas'] = $cantidadRutas;
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
		include '../presentacion/rpt_reporte_recaudos.php';
	}
	
	if($accion == "consultar_ruta_recaudos")
	{
		/*
		 * CONSULTA TODOS LOS RECAUDOS DEL SOCIO
		 * */
		$query = "select codigo trj_codigo, nombre ruta, aju_codigo, nombre_vendedor, count(cli_codigo) clientes_recaudados, sum(valor_pago) valor_recaudado
					from (SELECT trj.codigo, trj.nombre, trj.jva_codigo, trj.aju_codigo, tv.valor_producto AS valor_total, au.codigo AS codigo_vendedor, au.nombres AS nombre_vendedor, au.apellidos AS apellido_vendedor, tv.codigo AS tv_codigo, ac.codigo cli_codigo, CONCAT(ac.nombre1_contacto, ' ', apellido1_contacto) as razon_social, tdrvj.fecha_recaudo, SUM(tdrvj.valor_recaudo) as valor_pago
					         FROM trans_rutas_jva trj, admin_jva_usuarios aju, admin_usuarios au, admin_clientes ac, trans_ventas tv, trans_rutas_detalles trd, trans_historico_recaudo_ventas_jva tdrvj
					         WHERE trj.jva_codigo IN (select jva_codigo from admin_jva_usuarios where rol_codigo = 2 and usu_codigo =  $usu_codigo)
					         AND trj.aju_codigo = aju.codigo
					         AND aju.usu_codigo = au.codigo
					         AND trj.codigo = trd.trj_codigo
					         AND trd.tv_codigo = tv.codigo
					         AND trd.tv_codigo = tdrvj.tv_codigo
					         AND tv.cli_codigo = ac.codigo
					         AND tdrvj.fecha_recaudo
							BETWEEN  '$fecha_desde'
							AND  '$fecha_hasta'
					      GROUP BY 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12
					      order by 11) recaudos
					group by 1, 2";
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
				$detalle['trj_codigo'][$RowCount] 			= $row['trj_codigo'];
				$detalle['ruta'][$RowCount] 				= $row['ruta'];
				$detalle['aju_codigo'][$RowCount] 			= $row['aju_codigo'];
				$detalle['nombre_vendedor'][$RowCount] 		= $row['nombre_vendedor'];
				$detalle['clientes_recaudados'][$RowCount] 	= $row['clientes_recaudados'];
				$detalle['valor_recaudado'][$RowCount] 		= $row['valor_recaudado'];
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
		include ('../presentacion/rpt_recaudado.php');
	}
	
	if($accion == "consultar_recaudos")
	{
		$aju_codigo = $_REQUEST['AJU_CODIGO'];
		
		$query = "SELECT fun_tipo_cliente(tv.codigo) est_codigo, trj.codigo, trj.jva_codigo, trj.aju_codigo, tv.valor_total AS valor_total, tv.valor_producto AS valor_producto, au.codigo AS codigo_vendedor, au.nombres AS nombre_vendedor, au.apellidos AS apellido_vendedor, tv.codigo AS tv_codigo, ac.codigo cli_codigo, ac.nroidentificacion, tv.referencia, CONCAT(ac.nombre1_contacto, ' ', apellido1_contacto) as cliente, tdrvj.fecha_recaudo, SUM(tdrvj.valor_recaudo) as valor_pago
			      FROM trans_rutas_jva trj, admin_jva_usuarios aju, admin_usuarios au, admin_clientes ac, trans_ventas tv, trans_rutas_detalles trd, trans_historico_recaudo_ventas_jva tdrvj
			      WHERE trj.jva_codigo IN (select jva_codigo from admin_jva_usuarios where rol_codigo = 2 and usu_codigo =  $usu_codigo)
			      AND aju.codigo = $aju_codigo
			      AND trj.aju_codigo = aju.codigo
			      AND aju.usu_codigo = au.codigo
			      AND trj.codigo = trd.trj_codigo
			      AND trd.tv_codigo = tv.codigo
			      AND trd.tv_codigo = tdrvj.tv_codigo
			      AND tv.cli_codigo = ac.codigo
			      AND tdrvj.fecha_recaudo
					BETWEEN  '$fecha_desde'
					AND  '$fecha_hasta'
				  GROUP BY 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15
				  order by 11";
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
		include ('../presentacion/rpt_recaudado.php');
	}
	
	if($accion == "consultar_recaudos_socio")
	{
		/*
		 * CONSULTA TODOS LOS RECAUDOS DEL SOCIO
		 * */
		$query = "select ar.codigo, ar.nombre, ar.descripcion, ar.jva_codigo, ar.aju_codigo, fun_obtener_aju_nombre(ar.aju_codigo) aju_nombre, ar.pbj_codigo, fun_nro_clientes_ruta(ar.codigo) nro_clientes, ar.saldo, ar.est_codigo
					from trans_rutas_jva ar, trans_rutas_detalles trd
					where trd.est_codigo = 1
					and ar.codigo = trd.trj_codigo  
					and ar.jva_codigo IN (select jva_codigo from admin_jva_usuarios where rol_codigo = 2 and usu_codigo =  $usu_codigo)
					and ar.aju_codigo = trd.aju_codigo
					group by trd.aju_codigo";
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
		include ('../presentacion/rpt_reporte_recaudos.php');
	}
	
	if($accion == "consultar_recaudos_socio_fechaInicio_fechaFin")
	{
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTA LOS RECAUDOS SEGUN LA FECHA SELECCIONADA 
		 * */
		$query = "SELECT trj.codigo, trj.jva_codigo, trj.aju_codigo, tv.valor_total AS valor_total, tv.valor_producto AS valor_producto, au.codigo AS codigo_vendedor, au.nombres AS nombre_vendedor, au.apellidos AS apellido_vendedor, tv.codigo AS tv_codigo, ac.codigo cli_codigo, ac.nroidentificacion, tv.referencia, fun_tipo_cliente(tv.codigo) est_codigo, CONCAT(ac.nombre1_contacto, ' ', apellido1_contacto) as cliente, tdrvj.fecha_recaudo, SUM(tdrvj.valor_recaudo) as valor_pago
						FROM trans_rutas_jva trj, admin_jva_usuarios aju, admin_usuarios au, admin_clientes ac, trans_ventas tv, trans_rutas_detalles trd, trans_historico_recaudo_ventas_jva tdrvj
						WHERE trj.jva_codigo IN (select jva_codigo from admin_jva_usuarios where rol_codigo = 2 and usu_codigo =  $usu_codigo)
						AND trj.aju_codigo = aju.codigo
						AND aju.usu_codigo = au.codigo
						AND trj.codigo = trd.trj_codigo
						AND trd.tv_codigo = tv.codigo
						AND trd.tv_codigo = tdrvj.tv_codigo
						AND tv.cli_codigo = ac.codigo
						AND tdrvj.fecha_recaudo
						BETWEEN  '$fecha_inicio 00:00:00'
						AND  '$fecha_fin 23:59:59'
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
		include ('../presentacion/rpt_reporte_recaudos.php');
	}
	
	if($accion == "consultar_recaudos_socio_jva")
	{
		$jva = $_REQUEST['sjva'];
		/*
		 * CONSULTA LAS RUTAS DEL JVA SELECCIONADO
		 * */
		$query = "select codigo, nombre, descripcion, jva_codigo, aju_codigo,  fun_obtener_aju_nombre(aju_codigo) aju_nombre, pbj_codigo, fun_nro_clientes_ruta(codigo) nro_clientes, saldo, est_codigo
					from trans_rutas_jva ar
					where ar.jva_codigo = $jva";
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
		include ('../presentacion/rpt_reporte_recaudos.php');
	}
	
	if($accion == "consultar_recaudos_socio_jva_fechaInicio_fechaFin")
	{
		$jva = $_REQUEST['sjva'];
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTA LAS RUTAS DEL JVA SELECCIONADO
		 * */
		$query = "select codigo, nombre, aju_codigo, nombre_vendedor aju_nombre, count(cli_codigo) nro_clientes, sum(valor_pago) saldo
					from (SELECT trj.codigo, trj.nombre, trj.jva_codigo, trj.aju_codigo, tv.valor_producto AS valor_total, au.codigo AS codigo_vendedor, au.nombres AS nombre_vendedor, au.apellidos AS apellido_vendedor, tv.codigo AS tv_codigo, ac.codigo cli_codigo, CONCAT(ac.nombre1_contacto, ' ', apellido1_contacto) as razon_social, tdrvj.fecha_recaudo, SUM(tdrvj.valor_recaudo) as valor_pago
					         FROM trans_rutas_jva trj, admin_jva_usuarios aju, admin_usuarios au, admin_clientes ac, trans_ventas tv, trans_rutas_detalles trd, trans_historico_recaudo_ventas_jva tdrvj
					         WHERE trj.jva_codigo = $jva
					         AND trj.aju_codigo = aju.codigo
					         AND aju.usu_codigo = au.codigo
					         AND trj.codigo = trd.trj_codigo
					         AND trd.tv_codigo = tv.codigo
					         AND trd.tv_codigo = tdrvj.tv_codigo
					         AND tv.cli_codigo = ac.codigo
					         AND tdrvj.fecha_recaudo
							BETWEEN  '$fecha_inicio 00:00:00'
							AND  '$fecha_fin 23:59:59'
					      GROUP BY 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11
					      order by 11) recaudos
					group by 1, 2";
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
				$detalle['codigo'][$RowCount] 				= $row['codigo'];
				$detalle['nombre'][$RowCount] 				= $row['nombre'];
				$detalle['aju_codigo'][$RowCount] 			= $row['aju_codigo'];
				$detalle['aju_nombre'][$RowCount] 			= $row['aju_nombre'];
				$detalle['nro_clientes'][$RowCount] 		= $row['nro_clientes'];
				$detalle['saldo'][$RowCount] 				= $row['saldo'];
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
		include ('../presentacion/rpt_reporte_recaudos.php');
	}
	
	if($accion == "consultar_recaudos_socio_jva_vendedor")
	{
		$vendedor = $_REQUEST['svededoresjva'];
		$jva	  = $_REQUEST['sjva'];
		/*
		 * CONSULTA LOS RECAUDOS DEL VENDEDOR
		 * */
		$query = "SELECT ar.codigo, ar.nombre, ar.descripcion, ar.jva_codigo, ar.aju_codigo, fun_obtener_aju_nombre(
				ar.aju_codigo
				)aju_nombre, ar.pbj_codigo, fun_nro_clientes_ruta(
				ar.codigo
				)nro_clientes, ar.saldo, ar.est_codigo
				FROM trans_rutas_jva ar, admin_usuarios au, admin_jva_usuarios aju
				WHERE ar.jva_codigo = $jva
				AND au.codigo = $vendedor
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
		include ('../presentacion/rpt_reporte_recaudos.php');
	}
	
	if($accion == "consultar_recaudos_socio_jva_vendedor_fechaInicio_fechaFin")
	{
		$vendedor = $_REQUEST['svededoresjva'];
		$jva = $_REQUEST['sjva'];
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTA EL RECAUDO TENIENDO EN VUENTA TODO EL FILTRO
		 * */
		$query = "SELECT trj.codigo, trj.jva_codigo, trj.aju_codigo, tv.valor_total AS valor_total, tv.valor_producto AS valor_producto, au.codigo AS codigo_vendedor, au.nombres AS nombre_vendedor, au.apellidos AS apellido_vendedor, tv.codigo AS tv_codigo, ac.codigo cli_codigo, ac.nroidentificacion, tv.referencia, fun_tipo_cliente(tv.codigo) est_codigo, CONCAT(ac.nombre1_contacto, ' ', apellido1_contacto) as cliente, tdrvj.fecha_recaudo, SUM(tdrvj.valor_recaudo) as valor_pago
						FROM trans_rutas_jva trj, admin_jva_usuarios aju, admin_usuarios au, admin_clientes ac, trans_ventas tv, trans_rutas_detalles trd, trans_historico_recaudo_ventas_jva tdrvj
						WHERE trj.jva_codigo = $jva
						AND trj.aju_codigo = aju.codigo
						AND au.codigo = $vendedor
						AND aju.usu_codigo = au.codigo
						AND trj.codigo = trd.trj_codigo
						AND trd.tv_codigo = tv.codigo
						AND trd.tv_codigo = tdrvj.tv_codigo
						AND tv.cli_codigo = ac.codigo
						AND tdrvj.fecha_recaudo
						BETWEEN  '$fecha_inicio 00:00:00'
						AND  '$fecha_fin 23:59:59'
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
		include ('../presentacion/rpt_reporte_recaudos.php');
	}
	if($accion == "consultar_recaudos_socio_cliente")
	{
		$cliente = $_REQUEST['cliente'];
		/*
		 * CONSULTA LOS RECAUDOS DEL CLIENTE INDICADO
		 * */
		$query = "SELECT trj.codigo, trj.jva_codigo, trj.aju_codigo, tv.valor_total AS valor_total, tv.valor_producto AS valor_producto, au.codigo AS codigo_vendedor, au.nombres AS nombre_vendedor, au.apellidos AS apellido_vendedor, tv.codigo AS tv_codigo, ac.codigo cli_codigo, ac.nroidentificacion, tv.referencia, fun_tipo_cliente(tv.codigo) est_codigo, CONCAT(ac.nombre1_contacto, ' ', apellido1_contacto) as cliente, tdrvj.fecha_recaudo, SUM(tdrvj.valor_recaudo) as valor_pago
						FROM trans_rutas_jva trj, admin_jva_usuarios aju, admin_usuarios au, admin_clientes ac, trans_ventas tv, trans_rutas_detalles trd, trans_historico_recaudo_ventas_jva tdrvj
						WHERE trj.jva_codigo in (select jva_codigo from admin_jva_usuarios where rol_codigo = 2 and usu_codigo =  $usu_codigo)
						AND trj.aju_codigo = aju.codigo
						AND aju.usu_codigo = au.codigo
						AND CONCAT(ac.nombre1_contacto, ' ', ac.apellido1_contacto) like '%$cliente%'
						AND trj.codigo = trd.trj_codigo
						AND trd.tv_codigo = tv.codigo
						AND trd.tv_codigo = tdrvj.tv_codigo
						AND tv.cli_codigo = ac.codigo
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
		include ('../presentacion/rpt_reporte_recaudos.php');
	}
	
	if($accion == "consultar_recaudos_socio_cliente_fecha_inicio_fecha_fin")
	{
		$cliente = $_REQUEST['cliente'];
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTA LOS RECAUDOS DEL CLIENTE INDICADO
		 * */
		$query = "SELECT trj.codigo, trj.jva_codigo, trj.aju_codigo, tv.valor_total AS valor_total, tv.valor_producto AS valor_producto, au.codigo AS codigo_vendedor, au.nombres AS nombre_vendedor, au.apellidos AS apellido_vendedor, tv.codigo AS tv_codigo, ac.codigo cli_codigo, ac.nroidentificacion, tv.referencia, fun_tipo_cliente(tv.codigo) est_codigo, CONCAT(ac.nombre1_contacto, ' ', apellido1_contacto) as cliente, tdrvj.fecha_recaudo, SUM(tdrvj.valor_recaudo) as valor_pago
						FROM trans_rutas_jva trj, admin_jva_usuarios aju, admin_usuarios au, admin_clientes ac, trans_ventas tv, trans_rutas_detalles trd, trans_historico_recaudo_ventas_jva tdrvj
						WHERE trj.jva_codigo in (select jva_codigo from admin_jva_usuarios where rol_codigo = 2 and usu_codigo =  $usu_codigo)
						AND trj.aju_codigo = aju.codigo
						AND aju.usu_codigo = au.codigo
						AND CONCAT(ac.nombre1_contacto, ' ', ac.apellido1_contacto) like '%$cliente%'
						AND trj.codigo = trd.trj_codigo
						AND trd.tv_codigo = tv.codigo
						AND trd.tv_codigo = tdrvj.tv_codigo
						AND tv.cli_codigo = ac.codigo
						AND tdrvj.fecha_recaudo BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59'
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
		include ('../presentacion/rpt_reporte_recaudos.php');
	}
	
	if($accion == "consultar_ruta_jva")
	{
		$trj_codigo = $_REQUEST['TRJ_CODIGO'];
		/*
		 * CONSULTA EL RECAUDO DE LA RUTA SELECCIONADA
		 * */
		$query = "SELECT trj.codigo, trj.jva_codigo, trj.aju_codigo, tv.valor_total AS valor_total, tv.valor_producto AS valor_producto, au.codigo AS codigo_vendedor, au.nombres AS nombre_vendedor, au.apellidos AS apellido_vendedor, tv.codigo AS tv_codigo, ac.codigo cli_codigo, ac.nroidentificacion, tv.referencia, fun_tipo_cliente(tv.codigo) est_codigo, CONCAT(ac.nombre1_contacto, ' ', apellido1_contacto) as cliente, tdrvj.fecha_recaudo, SUM(tdrvj.valor_recaudo) as valor_pago
						FROM trans_rutas_jva trj, admin_jva_usuarios aju, admin_usuarios au, admin_clientes ac, trans_ventas tv, trans_rutas_detalles trd, trans_historico_recaudo_ventas_jva tdrvj
						WHERE trj.jva_codigo in (select jva_codigo from admin_jva_usuarios where rol_codigo = 2 and usu_codigo =  $usu_codigo)
						AND trj.codigo = $trj_codigo
						AND trj.aju_codigo = aju.codigo
						AND aju.usu_codigo = au.codigo
						AND trj.codigo = trd.trj_codigo
						AND trd.tv_codigo = tv.codigo
						AND trd.tv_codigo = tdrvj.tv_codigo
						AND tv.cli_codigo = ac.codigo
					    GROUP BY cli_codigo";
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
		include ('../presentacion/rpt_recaudado.php');
	}
	
	if($accion == "consultar_recaudos_socio_fechaInicio_fechaFin_consolidado")
	{
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTA EL RECAUDO TENIENDO EN VUENTA TODO EL FILTRO
		 * */
		$query = "SELECT trj.codigo, trj.jva_codigo, trj.aju_codigo, sum(tv.valor_total) AS valor_total, sum(tv.valor_producto) AS valor_producto, au.codigo AS codigo_vendedor, au.nombres AS nombre_vendedor, au.apellidos AS apellido_vendedor, tv.codigo AS tv_codigo, ac.codigo cli_codigo, ac.nroidentificacion, tv.referencia, fun_tipo_cliente(tv.codigo) est_codigo, CONCAT(ac.nombre1_contacto, ' ', apellido1_contacto) as cliente, tdrvj.fecha_recaudo, SUM(tdrvj.valor_recaudo) as valor_pago
						FROM trans_rutas_jva trj, admin_jva_usuarios aju, admin_usuarios au, admin_clientes ac, trans_ventas tv, trans_rutas_detalles trd, trans_historico_recaudo_ventas_jva tdrvj
						WHERE trj.jva_codigo IN (select jva_codigo from admin_jva_usuarios where rol_codigo = 2 and usu_codigo =  $usu_codigo)
						AND trj.aju_codigo = aju.codigo
						AND aju.usu_codigo = au.codigo
						AND trj.codigo = trd.trj_codigo
						AND trd.tv_codigo = tv.codigo
						AND trd.tv_codigo = tdrvj.tv_codigo
						AND tv.cli_codigo = ac.codigo
						AND tdrvj.fecha_recaudo
						BETWEEN  '$fecha_inicio 00:00:00'
						AND  '$fecha_fin 23:59:59'
						GROUP BY au.codigo";
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
		include ('../presentacion/rpt_reporte_recaudos.php');
	}
	
	if($accion == "consultar_recaudos_socio_jva_vendedor_fechaInicio_fechaFin_consolidado")
	{
		$vendedor = $_REQUEST['svededoresjva'];
		$jva = $_REQUEST['sjva'];
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTA EL RECAUDO TENIENDO EN VUENTA TODO EL FILTRO
		 * */
		$query = "SELECT trj.codigo, trj.jva_codigo, trj.aju_codigo, sum(tv.valor_total) AS valor_total, sum(tv.valor_producto) AS valor_producto, au.codigo AS codigo_vendedor, au.nombres AS nombre_vendedor, au.apellidos AS apellido_vendedor, tv.codigo AS tv_codigo, ac.codigo cli_codigo, ac.nroidentificacion, tv.referencia, fun_tipo_cliente(tv.codigo) est_codigo, CONCAT(ac.nombre1_contacto, ' ', apellido1_contacto) as cliente, tdrvj.fecha_recaudo, SUM(tdrvj.valor_recaudo) as valor_pago
						FROM trans_rutas_jva trj, admin_jva_usuarios aju, admin_usuarios au, admin_clientes ac, trans_ventas tv, trans_rutas_detalles trd, trans_historico_recaudo_ventas_jva tdrvj
						WHERE trj.jva_codigo = $jva
						AND trj.aju_codigo = aju.codigo
						AND au.codigo = $vendedor
						AND aju.usu_codigo = au.codigo
						AND trj.codigo = trd.trj_codigo
						AND trd.tv_codigo = tv.codigo
						AND trd.tv_codigo = tdrvj.tv_codigo
						AND tv.cli_codigo = ac.codigo
						AND tdrvj.fecha_recaudo
						BETWEEN  '$fecha_inicio 00:00:00'
						AND  '$fecha_fin 23:59:59'
						GROUP BY au.codigo";
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
		include ('../presentacion/rpt_reporte_recaudos.php');
	}
	
}
catch (PDOException $e)
{
	$msg = $e->getMessage();
	?>
	<script>
		alert("Excepcion controlada: <?php echo $msg?>");
	</script>
	<?php
}
?>