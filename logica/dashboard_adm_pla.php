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
		$query_jva = 	"SELECT jva_codigo 
						FROM admin_jva_usuarios 
						WHERE usu_codigo =:usu_codigo and rol_codigo=2";
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
		 		$resultado[$RowCount]['jva_codigo'] = $jva_codigo;	 			
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
		include '../presentacion/dashboard_mostrar_front.php';
	}
	
	
	
	if($accion == "mostrar_front_ventas")
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
					WHERE jva_codigo  IN (select jva_codigo from admin_jva_usuarios where rol_codigo = 2 and usu_codigo =  $usu_codigo)
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
				$query_cantidad_recaudos = "SELECT COUNT(codigo) as Ventas, sum(valor_producto) as TotalVentas
											FROM `trans_ventas` 
											WHERE aju_codigo = '$ven_codigo'
											AND fecha_entrega like '$fecha%'";
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
		include ('../presentacion/dashboard_ventas.php');
	}
	
	if($accion == "mostrar_front_gastos")
	{
		$div = $_REQUEST['DIV'];
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * 2. OBTENER LA CANTIDAD DE GASTOS REALIZADOS EN EL DIA
		 * 3. OBTENER EL TOTAL DE LOS GASTOS REALIZADOS EN EL DIA
		 * */
		
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * */
		$query = "SELECT trj.codigo rut_codigo, trj.jva_codigo, trj.nombre, trj.aju_codigo ven_codigo, aj.nombre jva_nombre
					FROM trans_rutas_jva trj, admin_jva aj
					WHERE jva_codigo  IN (select jva_codigo from admin_jva_usuarios where rol_codigo = 2 and usu_codigo =  $usu_codigo)
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
				$query_cantidad_gastos = "SELECT COUNT(codigo) as Gastos, sum(valor) as TotalGastos
											FROM `trans_gastos_jva` 
											WHERE aju_codigo = '$ven_codigo'
											AND fecha_trans like '$fecha%'";
				/*
				 * SE PREPARA EL QUERY
				 * */
				//echo $query_cantidad_gastos;
				//echo $ven_codigo;
				$result_cantidad_gastos = $db_link->prepare($query_cantidad_gastos);
				$result_cantidad_gastos->execute(); //SE EJECUTA EL QUERY
				$arr_cantidad_gastos = $result_cantidad_gastos->errorInfo(); // SE OBTIENE EL ERROR
				$error_cantidad_gastos = $arr_cantidad_gastos[0];
				$errorMessage_cantidad_gastos = str_replace("'", "", $arr_cantidad_gastos[2]);
				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error_cantidad_gastos == "00000")
				{
					$RowCount_cantidad_gastos = 0;
					$SumaGastos = 0;
					while($row_cantidad_gastos = $result_cantidad_gastos->fetch(PDO::FETCH_ASSOC))
					{
						$detalle_cantidad_gastos['Gastos'][$RowCount_cantidad_gastos] 			= $row_cantidad_gastos['Gastos'];
						$detalle_cantidad_gastos['TotalGastos'][$RowCount_cantidad_gastos] 		= $row_cantidad_gastos['TotalGastos'];
						$Gastos = $detalle_cantidad_gastos['Gastos'][$RowCount_cantidad_gastos];
						$totalgastos = $detalle_cantidad_gastos['TotalGastos'][$RowCount_cantidad_gastos];
						$SumaGastos = $SumaGastos +  $detalle_cantidad_gastos['TotalGastos'][$RowCount_cantidad_gastos];
						$RowCount_cantidad_gastos++;
						//echo $SumaGastos;
					}
				}
				else
				{
					?>
					<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
					<?php
				}
				$detalle['Gastos'][$RowCount] = $Gastos;
				$detalle['TotalGastos'][$RowCount] = $totalgastos;
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include ('../presentacion/dashboard_gastos.php');
	}
	
	if($accion == "mostrar_front_cantidad_jva")
	{
		$query_jvas = "select count(codigo) as count_jvas from admin_jva";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result_2 = $db_link->prepare($query_jvas);
		$db_link->beginTransaction();
		$result_2->execute(); //SE EJECUTA EL QUERY
		$arr_2 = $result_2->errorInfo(); // SE OBTIENE EL ERROR
		$error_2 = $arr_2[0];
		$errorMessage_2 = str_replace("'", "", $arr_2[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error_2 == "00000")
		{
			$db_link->commit();
			$row = $result_2->fetch(PDO::FETCH_ASSOC);
			$count_jvas = $row['count_jvas'];
		}
		else
		{
			$db_link->rollBack();
			?>
				<script>alert("Error al consultar los JVA <?php echo $error_2." ".$errorMessage_2?>");</script>
			<?php
		}
		include '../presentacion/dashboard_mostrar_front.php';
	}
	
	if($accion == "consultar_detallado_cliente")
	{
		$tv_codigo = $_REQUEST['TV_CODIGO'];
		$cli_codigo = $_REQUEST['CLI_CODIGO'];
		$vendedor	= $_REQUEST['VENDEDOR'];
		
		$query = "SELECT tdrvj.fecha_pago, tdrvj.valor_pago, tdrvj.fecha_recaudo, tdrvj.valor_recaudo
					FROM trans_detalle_recaudo_ventas_jva tdrvj, trans_ventas tv
					WHERE tdrvj.tv_codigo = $tv_codigo
					AND tv.codigo = $tv_codigo";
		
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
				$detalle['fecha_pago'][$RowCount]			= $row['fecha_pago'];
				$detalle['valor_pago'][$RowCount] 			= $row['valor_pago'];
				$detalle['fecha_recaudo'][$RowCount] 		= $row['fecha_recaudo'];
				$detalle['valor_recaudo'][$RowCount] 		= $row['valor_recaudo'];
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
		include ('../presentacion/dashboard_detallado_cliente.php');
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
?>