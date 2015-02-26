<?php
if($accion == "mostrar_front_recaudos")
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
		include '../presentacion/rpt_reporte_recaudos.php';
	}
	
	if($accion == "mostrar_front_recaudos")
	{
		/*
		 * OBTIENE LOS JVA DEL USUARIO
		 * */
		$query_jva = 	"SELECT aju.jva_codigo, aj.nombre
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
		 		echo "JVA: $jva_nombre ".date('Y-m-d H:i:s')."<br>";		 			
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
				$nro_clientes_recaudados = 0;
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
		 				echo "Ruta $trj_codigo: ".date('Y-m-d H:i:s')."<br>";
						/*
						 * OBTIENE LOS PRODUCTOS DE LA RUTA
						 * */
		 				$query_ventas = "SELECT count(tv_codigo) cantidad_productos 
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
							$row_ventas = $result_ventas->fetch(PDO::FETCH_ASSOC);
							$cantidadProdutos = $cantidadProdutos + $row_ventas['cantidad_productos'];
							echo "Cantidad de productos: $cantidadProdutos ".date('Y-m-d H:i:s')."<br>";
							/*
							 * OBTIENE EL VALOR QUE SE DEBE RECAUDAR POR CADA PRODUCTO
							 * */
							$query_recaudo = 	"SELECT sum(valor_pago) valor_pago
												FROM (SELECT distinct tv_codigo, valor_pago
													FROM trans_detalle_recaudo_ventas_jva
													WHERE tv_codigo in (SELECT tv_codigo FROM trans_rutas_detalles WHERE trj_codigo = $trj_codigo and est_codigo = 1)
													AND est_codigo in (3, 5, 10)
													AND fecha_pago <= '$fecha_hasta'
													ORDER BY cuota_nro) valor_pago
												";
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
								echo "Valor a recaudar: $valor_recaudo ".date('Y-m-d H:i:s')."<br>";								
					
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
							$query_recaudado = "select count(tv_codigo) nro_clientes_recaudados, sum(valor_recaudo) valor_recaudo
												from (SELECT tv_codigo, fecha_recaudo, sum(valor_recaudo) valor_recaudo
													FROM trans_historico_recaudo_ventas_jva
													WHERE tv_codigo in (select tv_codigo from trans_rutas_detalles where trj_codigo = $trj_codigo and est_codigo = 1)
													AND fecha_recaudo BETWEEN '$fecha_desde' AND '$fecha_hasta'
													group by tv_codigo, fecha_recaudo) valor_recaudo";
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
								$row_recaudado 				= $result_recaudado->fetch(PDO::FETCH_ASSOC);
								$valor_recaudado 			= $valor_recaudado + $row_recaudado['valor_recaudo'];
								$nro_clientes_recaudados 	= $nro_clientes_recaudados + $row_recaudado['nro_clientes_recaudados'];
								echo "Valor/clientes recaudados: $valor_recaudado /  $nro_clientes_recaudados ".date('Y-m-d H:i:s')."<br>";					
					
							}
							else
							{
								$db_link->rollBack();
								?>
									<script>alert("Error al consultar los valores recaudados <?php echo $error_recaudado." ".$errorMessage_recaudado?>");</script>
								<?php
							}																					
						}
						else
						{
							$db_link->rollBack();
							?>
								<script>alert("Error al consultar los recaudos <?php echo $error_ventas." ".$errorMessage_ventas?>");</script>
							<?php
						}						
		 			}
		 			$resultado[$RowCount]['nro_rutas'] 				= $cantidadRutas;
		 			$resultado[$RowCount]['nro_clientes'] 			= $cantidadProdutos;
		 			$resultado[$RowCount]['valor_recaudo'] 			= $valor_recaudo;
					$resultado[$RowCount]['valor_recaudado'] 		= $valor_recaudado;	
					$resultado[$RowCount]['nro_clientes_recaudados']= $nro_clientes_recaudados;
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
?>
