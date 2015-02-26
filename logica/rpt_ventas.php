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
	
	if($accion == "mostrar_front_ventas")
	{
		/*
		 * OBTIENE LOS JVA DEL USUARIO
		 * */
		$query_jva = "SELECT aju.jva_codigo, aj.nombre
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
				$cantidadProdutos = 0;
				$valor_recaudo = 0;
				$nro_ventas_ruta = 0;
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
		 				//echo $query_ventas;
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
								$tv_codigo 	= $row_ventas['tv_codigo'];
								/*
								 * OBTIENE EL VALOR VENDIDO POR CADA PRODUCTO
								 * */
								$query_recaudo = 	"SELECT valor_producto
													FROM trans_ventas
													WHERE codigo = $tv_codigo
													AND est_codigo = 1
													AND fecha_entrega like '$fecha%'
													";
								//echo $query_recaudo;
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
									$valor_recaudo = $valor_recaudo + $row_recaudo['valor_producto'];
									$existe_venta = $result_recaudo->rowCount();
									if ($existe_venta>0)
									{
										$nro_ventas_ruta++;
										//echo $nro_ventas_ruta;
									}
								}
								else
								{
									$db_link->rollBack();
									?>
										<script>alert("Error al consultar los recaudos <?php echo $error_recaudo." ".$errorMessage_recaudo?>");</script>
									<?php
								}
							}
							$resultado[$RowCount]['valor_producto'] = $valor_recaudo;
							$resultado[$RowCount]['nro_ventas'] 	= $nro_ventas_ruta;
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
		include '../presentacion/rpt_ventas.php';
	}
	
	if($accion == "detalle_venta")
	{
		$div = $_REQUEST['DIV'];
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * 2. OBTENER LA CANTIDAD DE VENTAS REALIZADAS EN EL DIA
		 * 3. OBTENER EL TOTAL DE LAS VENTAS REALIZADAS EN EL DIA
		 * */
		
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * */
		$query = "SELECT trj.codigo rut_codigo, trj.jva_codigo, trj.nombre, trj.aju_codigo ven_codigo, aj.nombre jva_nombre
					FROM trans_rutas_jva trj, admin_jva aj
					WHERE jva_codigo IN (SELECT jva_codigo
											FROM admin_jva_usuarios
											WHERE rol_codigo in (2, 3)
											AND usu_codigo = $usu_codigo)
					AND aj.codigo = jva_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['rut_codigo'][$RowCount] 		= $row['rut_codigo'];
				$detalle['jva_codigo'][$RowCount] 		= $row['jva_codigo'];
				$detalle['jva_nombre'][$RowCount] 		= $row['jva_nombre'];
				$detalle['nombre'][$RowCount] 			= $row['nombre'];
				$detalle['ven_codigo'][$RowCount] 		= $row['ven_codigo'];
				$ven_codigo = $detalle['ven_codigo'][$RowCount];
				$jva_nombre = $detalle['jva_nombre'][$RowCount];
				/*
				 * 2. OBTENER LA CANTIDAD DE VENTAS REALIZADAS EN EL DIA
				 * */
				$query_cantidad_recaudos = "SELECT COUNT(tv.codigo) AS Ventas, SUM(tv.valor_total) AS TotalVentas, SUM(tv.valor_producto) AS TotalProducto
											FROM trans_ventas tv, trans_rutas_detalles trd
											WHERE tv.aju_codigo ='$ven_codigo'
											AND trd.aju_codigo = tv.aju_codigo
											AND tv.codigo = trd.tv_codigo
											AND trd.est_codigo = 1 
											AND tv.fecha_entrega like '$fecha%'";
				/*
				 * SE PREPARA EL QUERY
				 * */
				$result_cantidad_recaudos = $db_link->prepare($query_cantidad_recaudos);
				$result_cantidad_recaudos->execute(); //SE EJECUTA EL QUERY
				$arr_cantidad_recaudos = $result_cantidad_recaudos->errorInfo(); // SE OBTIENE EL ERROR
				$error_cantidad_recaudos = $arr_cantidad_recaudos[0];
				$errorMessage_cantidad_recaudos = str_replace("'", "", $arr_cantidad_recaudos[2]);
				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error_cantidad_recaudos == "00000")
				{
					$RowCount_cantidad_recaudos = 0;
					$SumaVentas = 0;
					while($row_cantidad_recaudos = $result_cantidad_recaudos->fetch(PDO::FETCH_ASSOC))
					{
						$detalle_cantidad_recaudos['Ventas'][$RowCount_cantidad_recaudos] 			= $row_cantidad_recaudos['Ventas'];
						$detalle_cantidad_recaudos['TotalVentas'][$RowCount_cantidad_recaudos] 		= $row_cantidad_recaudos['TotalVentas'];
						$detalle_cantidad_recaudos['TotalProducto'][$RowCount_cantidad_recaudos] 	= $row_cantidad_recaudos['TotalProducto'];
						/*
						 * SE CREAN VARIABLES PARA ASIGNAR AL ARREGLO PRINCIPAL CADA VEZ QUE AUMENTE EL ROWCOUNT
						 * */
						
						$ventas 		= $detalle_cantidad_recaudos['Ventas'][$RowCount_cantidad_recaudos];
						$totalventas 	= $detalle_cantidad_recaudos['TotalVentas'][$RowCount_cantidad_recaudos];
						$totalproducto  = $detalle_cantidad_recaudos['TotalProducto'][$RowCount_cantidad_recaudos];
						$SumaVentas 	= $SumaVentas +  $detalle_cantidad_recaudos['TotalVentas'][$RowCount_cantidad_recaudos];
						
						$RowCount_cantidad_recaudos++;
					}
				}
				else
				{
					?>
					<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
					<?php
				}
				/*
				 * ASIGNAMOS LOS VALORES DEL RESULTADO DEL SEGUNDO QUERY AL ARREGLO DEL PRIMER QUERY
				 * */
				$detalle['TotalProducto'][$RowCount]= $totalproducto;
				$detalle['Ventas'][$RowCount] 		= $ventas;
				$detalle['TotalVentas'][$RowCount] 	= $totalventas;
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include ('../presentacion/rpt_ventas.php');
	}
	
	if ($accion=="detalle_ventas_ruta")
	{
		/*
		 * CODIGO DEL VENDEDOR Y NOMBRE DE LA RUTA PARA MONSTRAR EN PRESENTACION DETALLE
		 * */
		$ven_codigo = $_REQUEST['VEN_CODIGO'];
		$ruta		= $_REQUEST['RUTA'];
		/*
		 * CONSULTAMOS EL DETALLE DE LAS VENTAS REALIZADAS POR EL VENDEDOR DE LA RUTA 
		 * */
		$query = "SELECT ac.nroidentificacion, ac.referencia, tv.codigo, tv.fecha_solicitud, tv.cli_codigo, CONCAT(ac.nombre1_contacto,' ',ac.nombre2_contacto,'',ac.apellido1_contacto,' ',ac.apellido2_contacto) as nom_cliente, tv.aju_codigo, CONCAT(au.nombres,' ',apellidos) as ven_nombre,  tv.valor_producto, tv.valor_comision_servicio, tv.valor_total, tv.fecha_entrega
					FROM trans_ventas tv, admin_clientes ac, admin_usuarios au, admin_jva_usuarios aju, trans_rutas_detalles trd
					WHERE tv.aju_codigo = $ven_codigo
					AND aju.codigo = tv.aju_codigo
					AND tv.aju_codigo = trd.aju_codigo
					AND tv.codigo = trd.tv_codigo
					AND trd.est_codigo = 1
					AND tv.fecha_entrega like '%$fecha%'
					AND tv.cli_codigo = ac.codigo
					AND aju.usu_codigo = au.codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
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
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['nroidentificacion'][$RowCount] 			= $row['nroidentificacion'];
				$detalle['referencia'][$RowCount] 					= $row['referencia'];
				
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_solicitud'][$RowCount] 				= $row['fecha_solicitud'];
				$detalle['cli_codigo'][$RowCount] 					= $row['cli_codigo'];
				$detalle['nom_cliente'][$RowCount] 					= $row['nom_cliente'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor_producto'][$RowCount] 				= $row['valor_producto'];
				$detalle['valor_comision_servicio'][$RowCount] 		= $row['valor_comision_servicio'];
				$detalle['valor_total'][$RowCount] 					= $row['valor_total'];
				$detalle['fecha_entrega'][$RowCount] 				= $row['fecha_entrega'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_ventas_detalle.php';
	}
	
	if($accion == "consultar_ventas_socio")
	{
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * 2. OBTENER LA CANTIDAD DE VENTAS REALIZADAS EN EL DIA
		 * 3. OBTENER EL TOTAL DE LAS VENTAS REALIZADAS EN EL DIA
		 * */
		
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * */
		$query = "SELECT trj.codigo rut_codigo, trj.jva_codigo, trj.nombre, trj.aju_codigo ven_codigo, aj.nombre jva_nombre
					FROM trans_rutas_jva trj, admin_jva aj
					WHERE jva_codigo IN (SELECT jva_codigo
											FROM admin_jva_usuarios
											WHERE rol_codigo in (2, 3)
											AND usu_codigo = $usu_codigo)
					AND aj.codigo = jva_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['rut_codigo'][$RowCount] 		= $row['rut_codigo'];
				$detalle['jva_codigo'][$RowCount] 		= $row['jva_codigo'];
				$detalle['jva_nombre'][$RowCount] 		= $row['jva_nombre'];
				$detalle['nombre'][$RowCount] 			= $row['nombre'];
				$detalle['ven_codigo'][$RowCount] 		= $row['ven_codigo'];
				$ven_codigo = $detalle['ven_codigo'][$RowCount];
				$jva_nombre = $detalle['jva_nombre'][$RowCount];
				/*
				 * 2. OBTENER LA CANTIDAD DE VENTAS REALIZADAS EN EL DIA
				 * */
				$query_cantidad_recaudos = "SELECT COUNT( tv.codigo ) AS Ventas, SUM( tv.valor_total ) AS TotalVentas
											FROM trans_ventas tv, trans_rutas_detalles trd
											WHERE tv.aju_codigo ='$ven_codigo'
											AND trd.aju_codigo = tv.aju_codigo
											AND tv.codigo = trd.tv_codigo
											AND trd.est_codigo =1 ";
				/*
				 * SE PREPARA EL QUERY
				 * */
				$result_cantidad_recaudos = $db_link->prepare($query_cantidad_recaudos);
				$result_cantidad_recaudos->execute(); //SE EJECUTA EL QUERY
				$arr_cantidad_recaudos = $result_cantidad_recaudos->errorInfo(); // SE OBTIENE EL ERROR
				$error_cantidad_recaudos = $arr_cantidad_recaudos[0];
				$errorMessage_cantidad_recaudos = str_replace("'", "", $arr_cantidad_recaudos[2]);
				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error_cantidad_recaudos == "00000")
				{
					$RowCount_cantidad_recaudos = 0;
					$SumaVentas = 0;
					while($row_cantidad_recaudos = $result_cantidad_recaudos->fetch(PDO::FETCH_ASSOC))
					{
						$detalle_cantidad_recaudos['Ventas'][$RowCount_cantidad_recaudos] 			= $row_cantidad_recaudos['Ventas'];
						$detalle_cantidad_recaudos['TotalVentas'][$RowCount_cantidad_recaudos] 		= $row_cantidad_recaudos['TotalVentas'];
						$ventas = $detalle_cantidad_recaudos['Ventas'][$RowCount_cantidad_recaudos];
						$totalventas = $detalle_cantidad_recaudos['TotalVentas'][$RowCount_cantidad_recaudos];
						$SumaVentas = $SumaVentas +  $detalle_cantidad_recaudos['TotalVentas'][$RowCount_cantidad_recaudos];
						$RowCount_cantidad_recaudos++;
					}
				}
				else
				{
					?>
					<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
					<?php
				}
				$detalle['Ventas'][$RowCount] = $ventas;
				$detalle['TotalVentas'][$RowCount] = $totalventas;
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_ventas.php';
	}
	
	if($accion == "consultar_ventas_socio_fechaInicio_fechaFin")
	{
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTAMOS EL DETALLE DE LAS VENTAS REALIZADAS POR FECHA
		 * */
		$query = "SELECT ac.nroidentificacion, ac.referencia, tv.codigo, tv.fecha_solicitud, tv.cli_codigo, CONCAT( ac.nombre1_contacto,  ' ', ac.nombre2_contacto,  '', ac.apellido1_contacto,  ' ', ac.apellido2_contacto ) AS nom_cliente, tv.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, tv.valor_producto, tv.valor_comision_servicio, tv.valor_total, tv.fecha_entrega
					FROM trans_ventas tv, admin_clientes ac, admin_usuarios au, admin_jva_usuarios aju, trans_rutas_detalles trd
					WHERE tv.aju_codigo = aju.codigo
					AND tv.aju_codigo = trd.aju_codigo
					AND tv.codigo = trd.tv_codigo
					AND trd.est_codigo = 1
					AND aju.usu_codigo = au.codigo
					AND tv.cli_codigo = ac.codigo
					AND tv.fecha_entrega BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59'
					GROUP BY tv.cli_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
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
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['nroidentificacion'][$RowCount] 			= $row['nroidentificacion'];
				$detalle['referencia'][$RowCount] 					= $row['referencia'];
				
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_solicitud'][$RowCount] 				= $row['fecha_solicitud'];
				$detalle['cli_codigo'][$RowCount] 					= $row['cli_codigo'];
				$detalle['nom_cliente'][$RowCount] 					= $row['nom_cliente'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor_producto'][$RowCount] 				= $row['valor_producto'];
				$detalle['valor_comision_servicio'][$RowCount] 		= $row['valor_comision_servicio'];
				$detalle['valor_total'][$RowCount] 					= $row['valor_total'];
				$detalle['fecha_entrega'][$RowCount] 				= $row['fecha_entrega'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_ventas.php';
	}
	
	if($accion == "consultar_ventas_socio_jva_fechaInicio_fechaFin")
	{
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin	  = $_REQUEST['Fecha_fin'];
		$jva		  = $_REQUEST['sjva'];
		/*
		 * CONSULTAMOS EL DETALLE DE LAS VENTAS REALIZADAS POR FECHA
		 * */
		$query = "SELECT ac.nroidentificacion, ac.referencia, tv.codigo, tv.fecha_solicitud, tv.cli_codigo, CONCAT( ac.nombre1_contacto,  ' ', ac.nombre2_contacto,  '', ac.apellido1_contacto,  ' ', ac.apellido2_contacto ) AS nom_cliente, tv.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, tv.valor_producto, tv.valor_comision_servicio, tv.valor_total, tv.fecha_entrega
					FROM trans_ventas tv, admin_clientes ac, admin_usuarios au, admin_jva_usuarios aju, trans_rutas_detalles trd
					WHERE tv.aju_codigo = aju.codigo
					AND aju.jva_codigo = $jva
					AND tv.aju_codigo = trd.aju_codigo
					AND tv.codigo = trd.tv_codigo
					AND trd.est_codigo = 1
					AND aju.usu_codigo = au.codigo
					AND tv.cli_codigo = ac.codigo
					AND tv.fecha_entrega BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59'
					GROUP BY tv.cli_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
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
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['nroidentificacion'][$RowCount] 			= $row['nroidentificacion'];
				$detalle['referencia'][$RowCount] 					= $row['referencia'];
				
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_solicitud'][$RowCount] 				= $row['fecha_solicitud'];
				$detalle['cli_codigo'][$RowCount] 					= $row['cli_codigo'];
				$detalle['nom_cliente'][$RowCount] 					= $row['nom_cliente'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor_producto'][$RowCount] 				= $row['valor_producto'];
				$detalle['valor_comision_servicio'][$RowCount] 		= $row['valor_comision_servicio'];
				$detalle['valor_total'][$RowCount] 					= $row['valor_total'];
				$detalle['fecha_entrega'][$RowCount] 				= $row['fecha_entrega'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_ventas.php';
	}
	
	if($accion=="consultar_ventas_socio_jva")
	{
		$jva = $_REQUEST['sjva'];
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * 2. OBTENER LA CANTIDAD DE VENTAS REALIZADAS EN EL DIA
		 * 3. OBTENER EL TOTAL DE LAS VENTAS REALIZADAS EN EL DIA
		 * */
		
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * */
		$query = "SELECT trj.codigo rut_codigo, trj.jva_codigo, trj.nombre, trj.aju_codigo ven_codigo, aj.nombre jva_nombre
					FROM trans_rutas_jva trj, admin_jva aj
					WHERE jva_codigo =  '$jva'
					AND aj.codigo = jva_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['rut_codigo'][$RowCount] 		= $row['rut_codigo'];
				$detalle['jva_codigo'][$RowCount] 		= $row['jva_codigo'];
				$detalle['jva_nombre'][$RowCount] 		= $row['jva_nombre'];
				$detalle['nombre'][$RowCount] 			= $row['nombre'];
				$detalle['ven_codigo'][$RowCount] 		= $row['ven_codigo'];
				$ven_codigo = $detalle['ven_codigo'][$RowCount];
				$jva_nombre = $detalle['jva_nombre'][$RowCount];
				/*
				 * 2. OBTENER LA CANTIDAD DE VENTAS REALIZADAS EN EL DIA
				 * */
				$query_cantidad_recaudos = "SELECT COUNT( tv.codigo ) AS Ventas, SUM( tv.valor_total ) AS TotalVentas
											FROM trans_ventas tv, trans_rutas_detalles trd
											WHERE tv.aju_codigo ='$ven_codigo'
											AND trd.aju_codigo = tv.aju_codigo
											AND tv.codigo = trd.tv_codigo
											AND trd.est_codigo =1";
				/*
				 * SE PREPARA EL QUERY
				 * */
				$result_cantidad_recaudos = $db_link->prepare($query_cantidad_recaudos);
				$result_cantidad_recaudos->execute(); //SE EJECUTA EL QUERY
				$arr_cantidad_recaudos = $result_cantidad_recaudos->errorInfo(); // SE OBTIENE EL ERROR
				$error_cantidad_recaudos = $arr_cantidad_recaudos[0];
				$errorMessage_cantidad_recaudos = str_replace("'", "", $arr_cantidad_recaudos[2]);
				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error_cantidad_recaudos == "00000")
				{
					$RowCount_cantidad_recaudos = 0;
					$SumaVentas = 0;
					while($row_cantidad_recaudos = $result_cantidad_recaudos->fetch(PDO::FETCH_ASSOC))
					{
						$detalle_cantidad_recaudos['Ventas'][$RowCount_cantidad_recaudos] 			= $row_cantidad_recaudos['Ventas'];
						$detalle_cantidad_recaudos['TotalVentas'][$RowCount_cantidad_recaudos] 		= $row_cantidad_recaudos['TotalVentas'];
						$ventas = $detalle_cantidad_recaudos['Ventas'][$RowCount_cantidad_recaudos];
						$totalventas = $detalle_cantidad_recaudos['TotalVentas'][$RowCount_cantidad_recaudos];
						$SumaVentas = $SumaVentas +  $detalle_cantidad_recaudos['TotalVentas'][$RowCount_cantidad_recaudos];
						$RowCount_cantidad_recaudos++;
					}
				}
				else
				{
					?>
					<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
					<?php
				}
				$detalle['Ventas'][$RowCount] = $ventas;
				$detalle['TotalVentas'][$RowCount] = $totalventas;
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_ventas.php';
	}
	
	if($accion == "consultar_ventas_socio_jva_vendedor")
	{
		$vendedor = $_REQUEST['svededoresjva'];
		$jva = $_REQUEST['sjva'];
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * 2. OBTENER LA CANTIDAD DE VENTAS REALIZADAS EN EL DIA
		 * 3. OBTENER EL TOTAL DE LAS VENTAS REALIZADAS EN EL DIA
		 * */
		
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * */
		$query = "SELECT trj.codigo rut_codigo, trj.jva_codigo, trj.nombre, trj.aju_codigo ven_codigo, aj.nombre jva_nombre
					FROM trans_rutas_jva trj, admin_jva aj, admin_jva_usuarios aju
					WHERE trj.jva_codigo =  $jva
					AND trj.aju_codigo = aju.codigo
					AND trj.aju_codigo in 
					(select codigo from admin_jva_usuarios where usu_codigo = $vendedor)
					AND aj.codigo = trj.jva_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['rut_codigo'][$RowCount] 		= $row['rut_codigo'];
				$detalle['jva_codigo'][$RowCount] 		= $row['jva_codigo'];
				$detalle['jva_nombre'][$RowCount] 		= $row['jva_nombre'];
				$detalle['nombre'][$RowCount] 			= $row['nombre'];
				$detalle['ven_codigo'][$RowCount] 		= $row['ven_codigo'];
				$ven_codigo = $detalle['ven_codigo'][$RowCount];
				$jva_nombre = $detalle['jva_nombre'][$RowCount];
				/*
				 * 2. OBTENER LA CANTIDAD DE VENTAS REALIZADAS EN EL DIA
				 * */
				$query_cantidad_recaudos = "SELECT COUNT(tv.codigo) as Ventas, sum(tv.valor_total) as TotalVentas
											FROM `trans_ventas` tv, admin_jva_usuarios aju, trans_rutas_detalles trd
											WHERE tv.aju_codigo = aju.codigo
											AND trd.aju_codigo = tv.aju_codigo
											AND tv.codigo = trd.tv_codigo
											AND trd.est_codigo =1
											AND tv.aju_codigo in 
											(select codigo from admin_jva_usuarios where usu_codigo = $vendedor)";
				/*
				 * SE PREPARA EL QUERY
				 * */
				$result_cantidad_recaudos = $db_link->prepare($query_cantidad_recaudos);
				$result_cantidad_recaudos->execute(); //SE EJECUTA EL QUERY
				$arr_cantidad_recaudos = $result_cantidad_recaudos->errorInfo(); // SE OBTIENE EL ERROR
				$error_cantidad_recaudos = $arr_cantidad_recaudos[0];
				$errorMessage_cantidad_recaudos = str_replace("'", "", $arr_cantidad_recaudos[2]);
				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error_cantidad_recaudos == "00000")
				{
					$RowCount_cantidad_recaudos = 0;
					$SumaVentas = 0;
					while($row_cantidad_recaudos = $result_cantidad_recaudos->fetch(PDO::FETCH_ASSOC))
					{
						$detalle_cantidad_recaudos['Ventas'][$RowCount_cantidad_recaudos] 			= $row_cantidad_recaudos['Ventas'];
						$detalle_cantidad_recaudos['TotalVentas'][$RowCount_cantidad_recaudos] 		= $row_cantidad_recaudos['TotalVentas'];
						$ventas = $detalle_cantidad_recaudos['Ventas'][$RowCount_cantidad_recaudos];
						$totalventas = $detalle_cantidad_recaudos['TotalVentas'][$RowCount_cantidad_recaudos];
						$SumaVentas = $SumaVentas +  $detalle_cantidad_recaudos['TotalVentas'][$RowCount_cantidad_recaudos];
						$RowCount_cantidad_recaudos++;
					}
				}
				else
				{
					?>
					<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
					<?php
				}
				$detalle['Ventas'][$RowCount] = $ventas;
				$detalle['TotalVentas'][$RowCount] = $totalventas;
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_ventas.php';
	}
	
	if($accion == "consultar_ventas_socio_jva_vendedor_fechaInicio_fechaFin")
	{
		$vendedor = $_REQUEST['svededoresjva'];
		$jva = $_REQUEST['sjva'];
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTAMOS EL DETALLE DE LAS VENTAS REALIZADAS POR FECHA JVA Y VENDEDOR
		 * */
		$query = "SELECT ac.nroidentificacion, ac.referencia, tv.codigo, tv.fecha_solicitud, tv.cli_codigo, CONCAT( ac.nombre1_contacto,  ' ', ac.nombre2_contacto,  '', ac.apellido1_contacto,  ' ', ac.apellido2_contacto ) AS nom_cliente, tv.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, tv.valor_producto, tv.valor_comision_servicio, tv.valor_total, tv.fecha_entrega
					FROM trans_ventas tv, admin_clientes ac, admin_usuarios au, admin_jva_usuarios aju, trans_rutas_detalles trd
					WHERE tv.aju_codigo = aju.codigo
					AND au.codigo = $vendedor
					AND tv.aju_codigo = trd.aju_codigo
					AND tv.codigo = trd.tv_codigo
					AND trd.est_codigo = 1
					AND aju.usu_codigo = au.codigo
					AND tv.cli_codigo = ac.codigo
					AND tv.fecha_entrega
					BETWEEN  '$fecha_inicio 00:00:00'
					AND  '$fecha_fin 23:59:59'
					GROUP BY tv.cli_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
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
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['nroidentificacion'][$RowCount] 			= $row['nroidentificacion'];
				$detalle['referencia'][$RowCount] 					= $row['referencia'];
				
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_solicitud'][$RowCount] 				= $row['fecha_solicitud'];
				$detalle['cli_codigo'][$RowCount] 					= $row['cli_codigo'];
				$detalle['nom_cliente'][$RowCount] 					= $row['nom_cliente'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor_producto'][$RowCount] 				= $row['valor_producto'];
				$detalle['valor_comision_servicio'][$RowCount] 		= $row['valor_comision_servicio'];
				$detalle['valor_total'][$RowCount] 					= $row['valor_total'];
				$detalle['fecha_entrega'][$RowCount] 				= $row['fecha_entrega'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_ventas.php';
	}
	
	if($accion == "consultar_ventas_cliente")
	{
		$cliente = $_REQUEST['cliente'];
		/*
		 * CONSULTA LAS VENTAS POR CLIENTE
		 * */
		$query = "SELECT ac.nroidentificacion, ac.referencia, tv.codigo, tv.fecha_solicitud, tv.cli_codigo, CONCAT( ac.nombre1_contacto,  ' ', ac.nombre2_contacto,  '', ac.apellido1_contacto,  ' ', ac.apellido2_contacto ) AS nom_cliente, tv.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, tv.valor_producto, tv.valor_comision_servicio, tv.valor_total, tv.fecha_entrega
					FROM trans_ventas tv, admin_clientes ac, admin_usuarios au, admin_jva_usuarios aju, trans_rutas_detalles trd
					WHERE tv.aju_codigo = aju.codigo
					AND aju.usu_codigo = au.codigo
					AND tv.aju_codigo = trd.aju_codigo
					AND tv.codigo = trd.tv_codigo
					AND trd.est_codigo = 1
					AND aju.jva_codigo IN (select jva_codigo from admin_jva_usuarios where rol_codigo in (2, 3) and usu_codigo =  $usu_codigo)
					AND tv.cli_codigo = ac.codigo
					AND CONCAT(ac.nombre1_contacto, ' ',ac.apellido1_contacto) like '%$cliente%' 
					GROUP BY tv.cli_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
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
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['nroidentificacion'][$RowCount] 			= $row['nroidentificacion'];
				$detalle['referencia'][$RowCount] 					= $row['referencia'];
				
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_solicitud'][$RowCount] 				= $row['fecha_solicitud'];
				$detalle['cli_codigo'][$RowCount] 					= $row['cli_codigo'];
				$detalle['nom_cliente'][$RowCount] 					= $row['nom_cliente'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor_producto'][$RowCount] 				= $row['valor_producto'];
				$detalle['valor_comision_servicio'][$RowCount] 		= $row['valor_comision_servicio'];
				$detalle['valor_total'][$RowCount] 					= $row['valor_total'];
				$detalle['fecha_entrega'][$RowCount] 				= $row['fecha_entrega'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_ventas.php';
	}
	
	if($accion == "consultar_ventas_cliente_fecha_inicio_fecha_fin")
	{
		$cliente = $_REQUEST['cliente'];
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTA LAS VENTAS POR CLIENTE
		 * */
		$query = "SELECT ac.nroidentificacion, ac.referencia, tv.codigo, tv.fecha_solicitud, tv.cli_codigo, CONCAT( ac.nombre1_contacto,  ' ', ac.nombre2_contacto,  '', ac.apellido1_contacto,  ' ', ac.apellido2_contacto ) AS nom_cliente, tv.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, tv.valor_producto, tv.valor_comision_servicio, tv.valor_total, tv.fecha_entrega
					FROM trans_ventas tv, admin_clientes ac, admin_usuarios au, admin_jva_usuarios aju, trans_rutas_detalles trd
					WHERE tv.aju_codigo = aju.codigo
					AND aju.usu_codigo = au.codigo
					AND tv.aju_codigo = trd.aju_codigo
					AND tv.codigo = trd.tv_codigo
					AND trd.est_codigo = 1
					AND aju.jva_codigo IN (select jva_codigo from admin_jva_usuarios where rol_codigo in (2, 3) and usu_codigo =  $usu_codigo)
					AND tv.cli_codigo = ac.codigo
					AND CONCAT(ac.nombre1_contacto, ' ',ac.apellido1_contacto) like '%$cliente%'
					AND tv.fecha_entrega
					BETWEEN  '$fecha_inicio 00:00:00'
					AND  '$fecha_fin 23:59:59' 
					GROUP BY tv.cli_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
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
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['nroidentificacion'][$RowCount] 			= $row['nroidentificacion'];
				$detalle['referencia'][$RowCount] 					= $row['referencia'];
				
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_solicitud'][$RowCount] 				= $row['fecha_solicitud'];
				$detalle['cli_codigo'][$RowCount] 					= $row['cli_codigo'];
				$detalle['nom_cliente'][$RowCount] 					= $row['nom_cliente'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor_producto'][$RowCount] 				= $row['valor_producto'];
				$detalle['valor_comision_servicio'][$RowCount] 		= $row['valor_comision_servicio'];
				$detalle['valor_total'][$RowCount] 					= $row['valor_total'];
				$detalle['fecha_entrega'][$RowCount] 				= $row['fecha_entrega'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_ventas.php';
	}
	
	if ($accion=="reporte_detalle_ventas_ruta")
	{
		/*
		 * CODIGO DEL VENDEDOR Y NOMBRE DE LA RUTA PARA MONSTRAR EN PRESENTACION DETALLE
		 * */
		$ven_codigo = $_REQUEST['VEN_CODIGO'];
		$ruta		= $_REQUEST['RUTA'];
		/*
		 * CONSULTAMOS EL DETALLE DE LAS VENTAS REALIZADAS POR EL VENDEDOR DE LA RUTA 
		 * */
		$query = "SELECT ac.nroidentificacion, ac.referencia, tv.codigo, tv.fecha_solicitud, tv.cli_codigo, CONCAT( ac.nombre1_contacto,  ' ', ac.nombre2_contacto,  '', ac.apellido1_contacto,  ' ', ac.apellido2_contacto ) AS nom_cliente, tv.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, tv.valor_producto, tv.valor_comision_servicio, tv.valor_total, tv.fecha_entrega
					FROM trans_ventas tv, admin_clientes ac, admin_usuarios au, admin_jva_usuarios aju, trans_rutas_detalles trd
					WHERE tv.aju_codigo = $ven_codigo
					AND aju.usu_codigo = au.codigo
					AND tv.aju_codigo = trd.aju_codigo
					AND tv.codigo = trd.tv_codigo
					AND trd.est_codigo = 1
					AND tv.aju_codigo = aju.codigo
					AND tv.cli_codigo = ac.codigo
					GROUP BY tv.cli_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
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
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['nroidentificacion'][$RowCount] 			= $row['nroidentificacion'];
				$detalle['referencia'][$RowCount] 					= $row['referencia'];
				
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_solicitud'][$RowCount] 				= $row['fecha_solicitud'];
				$detalle['cli_codigo'][$RowCount] 					= $row['cli_codigo'];
				$detalle['nom_cliente'][$RowCount] 					= $row['nom_cliente'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor_producto'][$RowCount] 				= $row['valor_producto'];
				$detalle['valor_comision_servicio'][$RowCount] 		= $row['valor_comision_servicio'];
				$detalle['valor_total'][$RowCount] 					= $row['valor_total'];
				$detalle['fecha_entrega'][$RowCount] 				= $row['fecha_entrega'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_ventas.php';
	}
	
	if($accion == "consultar_ventas_socio_fechaInicio_fechaFin_consolidado")
	{
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTAMOS EL DETALLE DE LAS VENTAS REALIZADAS POR FECHA
		 * */
		$query = "SELECT ac.nroidentificacion, ac.referencia, tv.codigo, tv.fecha_solicitud, tv.cli_codigo, CONCAT( ac.nombre1_contacto,  ' ', ac.nombre2_contacto,  '', ac.apellido1_contacto,  ' ', ac.apellido2_contacto ) AS nom_cliente, tv.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, sum(tv.valor_producto) valor_producto, sum(tv.valor_comision_servicio) valor_comision_servicio, sum(tv.valor_total) valor_total, tv.fecha_entrega
					FROM trans_ventas tv, admin_clientes ac, admin_usuarios au, admin_jva_usuarios aju, trans_rutas_detalles trd
					WHERE tv.aju_codigo = aju.codigo
					AND tv.aju_codigo = trd.aju_codigo
					AND tv.codigo = trd.tv_codigo
					AND trd.est_codigo = 1
					AND aju.usu_codigo = au.codigo
					AND tv.cli_codigo = ac.codigo
					AND tv.fecha_entrega BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59'
					GROUP BY au.codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
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
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['nroidentificacion'][$RowCount] 			= $row['nroidentificacion'];
				$detalle['referencia'][$RowCount] 					= $row['referencia'];
				
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_solicitud'][$RowCount] 				= $row['fecha_solicitud'];
				$detalle['cli_codigo'][$RowCount] 					= $row['cli_codigo'];
				$detalle['nom_cliente'][$RowCount] 					= $row['nom_cliente'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor_producto'][$RowCount] 				= $row['valor_producto'];
				$detalle['valor_comision_servicio'][$RowCount] 		= $row['valor_comision_servicio'];
				$detalle['valor_total'][$RowCount] 					= $row['valor_total'];
				$detalle['fecha_entrega'][$RowCount] 				= $row['fecha_entrega'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_ventas.php';
	}
	
	if($accion == "consultar_ventas_socio_jva_fechaInicio_fechaFin_consolidado")
	{
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin	  = $_REQUEST['Fecha_fin'];
		$jva		  = $_REQUEST['sjva'];
		/*
		 * CONSULTAMOS EL DETALLE DE LAS VENTAS REALIZADAS POR FECHA
		 * */
		$query = "SELECT ac.nroidentificacion, ac.referencia, tv.codigo, tv.fecha_solicitud, tv.cli_codigo, CONCAT( ac.nombre1_contacto,  ' ', ac.nombre2_contacto,  '', ac.apellido1_contacto,  ' ', ac.apellido2_contacto ) AS nom_cliente, tv.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, SUM(tv.valor_producto) valor_producto, SUM(tv.valor_comision_servicio) valor_comision_servicio, SUM(tv.valor_total) valor_total, tv.fecha_entrega
					FROM trans_ventas tv, admin_clientes ac, admin_usuarios au, admin_jva_usuarios aju, trans_rutas_detalles trd
					WHERE tv.aju_codigo = aju.codigo
					AND aju.jva_codigo = $jva
					AND tv.aju_codigo = trd.aju_codigo
					AND tv.codigo = trd.tv_codigo
					AND trd.est_codigo = 1
					AND aju.usu_codigo = au.codigo
					AND tv.cli_codigo = ac.codigo
					AND tv.fecha_entrega BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59'
					GROUP BY au.codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
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
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['nroidentificacion'][$RowCount] 			= $row['nroidentificacion'];
				$detalle['referencia'][$RowCount] 					= $row['referencia'];
				
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_solicitud'][$RowCount] 				= $row['fecha_solicitud'];
				$detalle['cli_codigo'][$RowCount] 					= $row['cli_codigo'];
				$detalle['nom_cliente'][$RowCount] 					= $row['nom_cliente'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor_producto'][$RowCount] 				= $row['valor_producto'];
				$detalle['valor_comision_servicio'][$RowCount] 		= $row['valor_comision_servicio'];
				$detalle['valor_total'][$RowCount] 					= $row['valor_total'];
				$detalle['fecha_entrega'][$RowCount] 				= $row['fecha_entrega'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_ventas.php';
	}
	
	if($accion == "consultar_ventas_socio_jva_vendedor_fechaInicio_fechaFin_consolidado")
	{
		$vendedor = $_REQUEST['svededoresjva'];
		$jva = $_REQUEST['sjva'];
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTAMOS EL DETALLE DE LAS VENTAS REALIZADAS POR FECHA JVA Y VENDEDOR
		 * */
		$query = "SELECT DISTINCT ac.nroidentificacion, ac.referencia, tv.codigo, tv.fecha_solicitud, tv.cli_codigo, CONCAT( ac.nombre1_contacto,  ' ', ac.nombre2_contacto,  '', ac.apellido1_contacto,  ' ', ac.apellido2_contacto ) AS nom_cliente, tv.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, SUM(tv.valor_producto) valor_producto, SUM(tv.valor_comision_servicio) valor_comision_servicio, SUM(tv.valor_total) valor_total, tv.fecha_entrega
					FROM trans_ventas tv, admin_clientes ac, admin_usuarios au, admin_jva_usuarios aju, trans_rutas_detalles trd
					WHERE tv.aju_codigo = aju.codigo
					AND au.codigo = $vendedor
					AND tv.aju_codigo = trd.aju_codigo
					AND tv.codigo = trd.tv_codigo
					AND trd.est_codigo = 1
					AND aju.usu_codigo = au.codigo
					AND tv.cli_codigo = ac.codigo
					AND tv.fecha_entrega
					BETWEEN  '$fecha_inicio 00:00:00'
					AND  '$fecha_fin 23:59:59'
					GROUP BY ac.codigo, aju.usu_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
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
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['nroidentificacion'][$RowCount] 			= $row['nroidentificacion'];
				$detalle['referencia'][$RowCount] 					= $row['referencia'];
				
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_solicitud'][$RowCount] 				= $row['fecha_solicitud'];
				$detalle['cli_codigo'][$RowCount] 					= $row['cli_codigo'];
				$detalle['nom_cliente'][$RowCount] 					= $row['nom_cliente'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor_producto'][$RowCount] 				= $row['valor_producto'];
				$detalle['valor_comision_servicio'][$RowCount] 		= $row['valor_comision_servicio'];
				$detalle['valor_total'][$RowCount] 					= $row['valor_total'];
				$detalle['fecha_entrega'][$RowCount] 				= $row['fecha_entrega'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_ventas.php';
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