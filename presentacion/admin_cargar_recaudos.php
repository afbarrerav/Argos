<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Junio de 2011
 *
 * */
$ACCION="";
?>
<script>
	var accion;
	if (confirm("Esta Seguro de Cargar los recaudos")) 
	{
		<?php
		$DATABASE_NAME 	= "argos_bd2plataforma";
		$dsn 			= "mysql:host=localhost;dbname=$DATABASE_NAME";
		try
		{
			$db_link = new PDO($dsn, 'fmojica', 'FA84ma0616');
			$ruta ="http://50.63.181.150:8813/baco/archivos_csv/";
			//$ruta ="C:/AppServ/www/BackOffice/archivos_cvs/";
			$archivo = "recaudos.csv";
			//Coloca el nombre de tu archivo .csv que contiene los datos
			$handle = fopen($ruta.$archivo, "r");
			$cantidad_recaudos=0;
			$cantidad_recaudos_totales=0;
			$msg_recaudos = "";
			$ventas_finalizadas = "";
			//Lee toda una linea completa, e ingresa los datos en el array 'data'
			while (($data = fgetcsv($handle, 0, ";")) !== FALSE)
			{
				/*
				 * OBTENER EL VALOR RECAUDADO
				 * */
				$valor_recaudo_pagado = $data[2];
				$msg_recaudos .= "Valor Recaudo".$valor_recaudo_pagado." - ";
				/*
				 * OBTENER EL TV_CODIGO SOBRE EL CUAL SE VA HA REALIZAR EL RECAUDO
				 * */
				$tv_codigo = $data[0];
				$msg_recaudos .= "Codigo Venta: ".$tv_codigo." ::: ";
				/*
				 * OBTENER LA PRIMERA CUOTA A LA QUE SE DEBE INGRESAR EL RECAUDO
				 * */
				$query_cuota_recaudo = "select codigo, tv_codigo, aju_codigo, cuotas_totales, cuota_nro, fecha_pago, valor_pago, fecha_recaudo, valor_recaudo, fecha_trans, longitud, latitud, est_codigo 
										from trans_detalle_recaudo_ventas_jva 
										where tv_codigo='$tv_codigo' 
										and est_codigo in (3, 5, 10)
										order by cuota_nro
										limit 0, 1";
				//echo $query_cuota_recaudo;
				$result_cuota_recaudo = $db_link->prepare($query_cuota_recaudo);
				$result_cuota_recaudo->execute(); //SE EJECUTA EL QUERY
				$arr_cuota_recaudo = $result_cuota_recaudo->errorInfo(); // SE OBTIENE EL ERROR
				$error_cuota_recaudo = $arr_cuota_recaudo[0];
				$errorMessage_cuota_recaudo = str_replace("'", "", $arr_cuota_recaudo[2]);
				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */		
				if($error_cuota_recaudo=="00000")
				{
					$cantidad_recaudos_totales++;
					if($result_cuota_recaudo->rowCount()>0)
					{
						$cantidad_recaudos++;						
						$row_cuota_recaudo 	= $result_cuota_recaudo->fetch(PDO::FETCH_ASSOC);
						$tdrvj_codigo 		= $row_cuota_recaudo['codigo'];
						$tv_codigo 			= $row_cuota_recaudo['tv_codigo'];
						$aju_codigo 		= $row_cuota_recaudo['aju_codigo'];
						$cuotas_totales 	= $row_cuota_recaudo['cuotas_totales'];
						$cuota_nro 			= $row_cuota_recaudo['cuota_nro'];
						$fecha_pago 		= $row_cuota_recaudo['fecha_pago'];
						$valor_pago 		= $row_cuota_recaudo['valor_pago'];
						$fecha_recaudo 		= $row_cuota_recaudo['fecha_recaudo'];
						$valor_recaudo 		= $row_cuota_recaudo['valor_recaudo'];
						$fecha_trans 		= $row_cuota_recaudo['fecha_trans'];
						$longitud 			= $row_cuota_recaudo['longitud'];
						$latitud 			= $row_cuota_recaudo['latitud'];
						$est_codigo 		= $row_cuota_recaudo['est_codigo'];
									
						/*
						 * DISTRIBUYE EL RECAUDO EN EL DETALLE Y EN EL HISTORICO
						 * */
						// 1. obtiene el valor de la primera cuota
						while($valor_recaudo_pagado > 0)
						{
							/*
							 * LOGICA PARA OBTENER VALOR RECAUDO RELA Y NUEVO VALOR RECAUDO
							 * */
							if ($valor_recaudo > 0)
							{
								if(($valor_recaudo+$valor_recaudo_pagado)>= $valor_pago)
								{
									$valor_recaudo_real = $valor_pago - $valor_recaudo;
									$nuevo_valor_recaudo = $valor_pago;
									$estado_recaudo = 4;
								}
								else 
								{
									$valor_recaudo_real = $valor_recaudo_pagado;
									$nuevo_valor_recaudo = $valor_recaudo + $valor_recaudo_pagado;
									$estado_recaudo = 5;
								}					
							}
							else 
							{
								if($valor_recaudo_pagado >= $valor_pago)
								{
									$valor_recaudo_real = $valor_pago;
									$nuevo_valor_recaudo = $valor_recaudo_real;
									$estado_recaudo = 4;
								}
								else 
								{
									$valor_recaudo_real = $valor_recaudo_pagado;
									$nuevo_valor_recaudo = $valor_recaudo_real;
									$estado_recaudo = 5;
								}
							}				
							$valor_recaudo = 0;
							/*
							 * INSERTAR LOS RECAUDOS
							 * */
							$query_update =	"UPDATE trans_detalle_recaudo_ventas_jva 
											set fecha_recaudo = '$data[1]', valor_recaudo = $nuevo_valor_recaudo, fecha_trans = now(), 
											longitud='$data[3]', latitud='$data[4]', est_codigo = $estado_recaudo
											where tv_codigo = :tv_codigo and cuota_nro=:cuota_nro";				
							//echo $query_update."<br>";
							//$error_update="00000";
							/*
							 * SE PREPARA EL QUERY
							 * */
							$result_update = $db_link->prepare($query_update);
							$db_link->beginTransaction();
							$result_update->bindParam(':tv_codigo',$tv_codigo);
							$result_update->bindParam(':cuota_nro',$cuota_nro);
							$result_update->execute(); //SE EJECUTA EL QUERY
							$arr_update = $result_update->errorInfo(); // SE OBTIENE EL ERROR
							$error_update = $arr_update[0];
							$errorMessage_update = str_replace("'", "", $arr_update[2]);
							/*
							 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
							 * */
							if($error_update=="00000")
							{								
								$msg_recaudos .="Se actualizo el recaudo nro $cuota_nro de la venta $tv_codigo con fecha de recaudo $data[1]</BR>";
								$db_link->commit();
								/*
								 * INSERTAR EL HISTORICO DE RECAUDO
								 * */			
								$query_historico = "INSERT INTO trans_historico_recaudo_ventas_jva VALUES ('$tv_codigo', '$aju_codigo', (select codigo from trans_detalle_recaudo_ventas_jva where tv_codigo = '$tv_codigo' and cuota_nro = '$cuota_nro'), '$cuota_nro', '$data[1]', '$valor_recaudo_real', NOW(), '$data[3]', '$data[4]', 1)";
								//echo $query_historico."<br>";
								//$error_historico="00000";
								$result_historico = $db_link->prepare($query_historico);
								$result_historico->execute();
								$arr_historico = $result_historico->errorInfo(); // SE OBTIENE EL ERROR
								$error_historico = $arr_historico[0];
								$errorMessage_historico = str_replace("'", "", $arr_historico[2]);
								/*
								 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
								 * */
								if($error_historico=="00000")
								{
									$msg_recaudos .="Se ingreso el historico del recaudo codigo $tdrvj_codigo para la cuota nro $cuota_nro de la venta $tv_codigo con fecha de recaudo $data[1]</BR>";

								}	
								else 
								{
									$msg="$query_historico ::: Ha ocurrido un error al intentar ingresar el historico del recaudo codigo $tdrvj_codigo para la cuota nro $cuota_nro de la venta $tv_codigo con fecha de recaudo $data[1]:: $errorMessage_historico</BR>";
									echo $msg;
								}						
							}
							else
							{
								$msg="$query_update ::: Ha ocurrido un error al intentar actualizar el recaudo nro $cuota_nro de la venta $tv_codigo :: $errorMessage_update</BR>";
								echo $msg;
								$db_link->rollBack();
							}
							$cuota_nro++;
							$valor_recaudo_pagado = $valor_recaudo_pagado-$valor_recaudo_real;
						}
					}
					else 
					{
						$ventas_finalizadas .= "La venta Codigo  $tv_codigo no posee cuotas pendientes <br>";				
					}			
				}
				else
				{				
					$msg="Ha ocurrido un error al intentar obtener el numero de la cuota del recaudo para la venta $tv_codigo $errorMessage_cuota_recaudo";
					echo $msg;
				}
			}
			fclose($handle);
			echo "Se crearon $cantidad_recaudos de $cantidad_recaudos_totales recaudos <br>";
			echo $ventas_finalizadas."<br>".$msg_recaudos;
		}
		catch (PDOException $e)
		{
			$msg = $e->getMessage();
			echo $msg;
		}
		?>	
	}
</script>