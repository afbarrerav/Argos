<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
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
		$query_jva = 	"SELECT jva_codigo 
						FROM admin_jva_usuarios 
						WHERE usu_codigo =:usu_codigo and rol_codigo in (2, 3)";
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
		include '../presentacion/dashboard_rpt_recaudos.php';
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