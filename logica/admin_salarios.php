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
	if ($accion=="mostrar_front")
	{
		include '../presentacion/admin_salarios.php';	
	}
	if ($accion=="cosultar_liquidacion")
	{
		$usu_codigo_vendedor = $_REQUEST['USU_CODIGO'];
		$fecha_inicio = $_REQUEST['FECHA_INICIO']; 
		$fecha_fin = $_REQUEST['FECHA_FIN'];
		$usu_nombre_vendedor = $_REQUEST['NOMBRE_VENDEDOR'];
		/*
		 * OBTIENE EL AJU_CODIGO Y LOS DATOS DEL VENDEDOR BASADO EN EL USU_CODIGO 
		 * */	
		$query = 	"SELECT aju.codigo, nro_identificacion, nombres, apellidos, fecha_nacimiento, direccion, telefono, email 
					FROM admin_jva_usuarios aju, admin_usuarios au
					WHERE aju.usu_codigo = au.codigo
					and usu_codigo =  $usu_codigo_vendedor";
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
		if($error=="00000")
		{			
			$db_link->commit();
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$aju_codigo_vendedor 		= $row['codigo'];
			$cc_vendedor 				= $row['nro_identificacion'];
			$nombres_vendedor 			= $row['nombres'];
			$apellidos_vendedor 		= $row['apellidos'];
			$fecha_nacimiento_vendedor 	= $row['fecha_nacimiento'];
			$direccion_vendedor 		= $row['direccion'];
			$telefono_vendedor 			= $row['telefono'];
			$mail_vendedor 				= $row['email'];
			/*
			 * CONSULTA SI EL VENDEDOR TIENE UNA LIQUIDACION EN ESE RANGO DE FECHAS
			 * */
			$query_liquidacion = 	"SELECT trl.cartera_inicial, trl.cartera_final,  trl.nro_clientes, trl.promedio_recaudo, trl.valor_recaudo, trl.valor_salario, trl.valor_cargues, trl.valor_retiros,
											trl.valor_gastos, trl.valor_ventas, trl.valor_salario, psj.valor_meta_recaudo, psj.valor_salario_basico, psj.porcentaje_comision
									FROM trans_salarios_jva tsj, trans_rutas_liquidacion trl, param_salarios_jva psj
									WHERE tsj.codigo = trl.tsj_codigo 
									AND psj.codigo = tsj.psj_codigo
									AND tsj.aju_codigo =  $aju_codigo_vendedor
									AND ((tsj.fecha_inicio BETWEEN  '$fecha_inicio' AND '$fecha_fin' OR tsj.fecha_fin BETWEEN  '$fecha_inicio' AND '$fecha_fin') OR ('$fecha_inicio' BETWEEN  tsj.fecha_inicio AND tsj.fecha_fin OR '$fecha_fin' BETWEEN  tsj.fecha_inicio AND tsj.fecha_fin))";
			//echo $query_liquidacion;
			/*
			 * SE PREPARA EL QUERY
			 * */
			$result_liquidacion = $db_link->prepare($query_liquidacion );
			$db_link->beginTransaction();
			$result_liquidacion->execute(); //SE EJECUTA EL QUERY
			$arr_liquidacion = $result_liquidacion->errorInfo(); // SE OBTIENE EL ERROR
			$error_liquidacion = $arr_liquidacion[0];
			$errorMessage_liquidacion = str_replace("'", "", $arr_liquidacion[2]);
			/*
			 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
			 * */
			if($error_liquidacion =="00000")
			{			
				
				$existe_liquidacion = $result_liquidacion->rowCount();				
				$db_link->commit(); 
				if($existe_liquidacion > 0)
				{
					/*
					 * SI EL VENDEDOR TIENE LIQUIDACION EN ESE RANGO DE FECHAS LA PRESENTA E INFORMA QUE NO ES POSIBLE
					 * LIQUIDAR NUEVAMENTE
					 * */

					while($row_liquidacion = $result_liquidacion->fetch(PDO::FETCH_ASSOC))
					{
						$saldo_incial 			= $row_liquidacion['cartera_inicial'];
						$saldo_final 			= $row_liquidacion['cartera_final'];
						$clientes_final 		= $row_liquidacion['nro_clientes'];
						$promedio_recaudo 		= $row_liquidacion['promedio_recaudo'];
						$valor_recaudo 			= $row_liquidacion['valor_recaudo'];
						$valor_meta_recaudo 	= $row_liquidacion['valor_meta_recaudo'];
						$valor_salario_basico 	= $row_liquidacion['valor_salario_basico'];
						$porcentaje_comision 	= $row_liquidacion['porcentaje_comision'];
						$valor_salario_apagar 	= $row_liquidacion['valor_salario'];
						$valor_cargue 			= $row_liquidacion['valor_cargues'];
						$valor_retiros 			= $row_liquidacion['valor_retiros'];
						$valor_gastos 			= $row_liquidacion['valor_gastos'];
						$valor_ventas 			= $row_liquidacion['valor_ventas'];
						$valor_salario_apagar 	= $row_liquidacion['valor_salario'];

					}
						
				}
				else
				{					
					/*
					 * SI EL VENDEDOR NO TIENE LIQUIDACION EN ESE RANGO DE FECHAS LA CONSTRUYE
					 * */
					/************************* LIQUIDAR VENDEDOR ******************************/
					/*
					 * COSULTA EL TOTAL DEL RECAUDO DEL VENDEDOR EN EL RANGO DE FECHAS Y CALCULA LA COMISION
					 * */
					$query_recaudo = 	"SELECT sum(valor_recaudo) recaudo 
										FROM trans_detalle_recaudo_ventas_jva tdrvj
										WHERE aju_codigo =  $aju_codigo_vendedor
										AND (tdrvj.fecha_recaudo BETWEEN  '$fecha_inicio' AND '$fecha_fin')";
					/*
					 * SE PREPARA EL QUERY
					 * */
					$result_recaudo = $db_link->prepare($query_recaudo );
					$db_link->beginTransaction();
					$result_recaudo->execute(); //SE EJECUTA EL QUERY
					$arr_recaudo = $result_recaudo->errorInfo(); // SE OBTIENE EL ERROR
					$error_recaudo = $arr_recaudo[0];
					$errorMessage_recaudo = str_replace("'", "", $arr_recaudo[2]);
					/*
					 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
					 * */
					if($error_recaudo =="00000")
					{									
						$row_recaudo = $result_recaudo->fetch(PDO::FETCH_ASSOC);
						$db_link->commit();
						$valor_recaudo = $row_recaudo['recaudo'];
					}
					else 
					{
						$db_link->rollBack();
						$msg="Ha ocurrido un error al intentar obtener el recaudo del vendedor $errorMessage_recaudo ";
						echo $msg;
					} 
					/*
					 * CONSULTA LAS DIFERENCIAS EN CONTRA DEL VENDEDOR
					 * */
					
					/*
					 * CALCULA EL SALARIO DEL VENDEDOR
					 * */
					$query_salario = 	"SELECT * 
										FROM param_salarios_jva 
										WHERE aju_codigo =  $aju_codigo_vendedor";
					/*
					 * SE PREPARA EL QUERY
					 * */
					$result_salario = $db_link->prepare($query_salario);
					$db_link->beginTransaction();
					$result_salario->execute(); //SE EJECUTA EL QUERY
					$arr_salario = $result_salario->errorInfo(); // SE OBTIENE EL ERROR
					$error_salario = $arr_salario[0];
					$errorMessage_salario = str_replace("'", "", $arr_salario[2]);
					/*
					 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
					 * */
					if($error_salario =="00000")
					{									
						$row_salario = $result_salario->fetch(PDO::FETCH_ASSOC);
						$db_link->commit();
						$valor_salario_basico  	= $row_salario['valor_salario_basico'];
						$valor_meta_recaudo 	= $row_salario['valor_meta_recaudo'];
						$porcentaje_comision 	= $row_salario['porcentaje_comision'];
						if($valor_meta_recaudo <= $valor_recaudo)
						{
							$valor_salario_apagar	= ($valor_recaudo * $porcentaje_comision)/100;
						}
						else 
						{
							$valor_salario_apagar	= $valor_salario_basico;
						}
					}
					else 
					{
						$db_link->rollBack();
						$msg="Ha ocurrido un error al intentar obtener el salario del vendedor $errorMessage_salario ";
						echo $msg;
					} 
					/************************* LIQUIDAR RUTA *********************************/
					/*
					 * MUESTRA EL SALDO DE LA RUTA AL INICIAR
					 * */
					$saldo_incial = 0;
					$query_saldo_inicial = 	"select sum(saldo_producto) valor
											from (select tv.codigo, sum(tv.valor_total-vr.valor_recaudo) saldo_producto
													from trans_ventas tv, (SELECT TV_CODIGO, SUM( valor_recaudo ) valor_recaudo
																			FROM  trans_detalle_recaudo_ventas_jva
																			WHERE FECHA_RECAUDO <=  '$fecha_inicio 00:00:00'
																			and aju_codigo = $aju_codigo_vendedor
																			GROUP BY tv_codigo) vr
													where tv.codigo = vr.tv_codigo
													group by tv.codigo) sd";
					/*
					 * SE PREPARA EL QUERY
					 * */
					$result_saldo_inicial = $db_link->prepare($query_saldo_inicial);
					$db_link->beginTransaction();
					$result_saldo_inicial->execute(); //SE EJECUTA EL QUERY
					$arr_saldo_inicial = $result_saldo_inicial->errorInfo(); // SE OBTIENE EL ERROR
					$error_saldo_inicial = $arr_saldo_inicial[0];
					$errorMessage_saldo_inicial = str_replace("'", "", $arr_saldo_inicial[2]);
					/*
					 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
					 * */
					if($error_saldo_inicial == "00000")
					{									
						$row_saldo_inicial = $result_saldo_inicial->fetch(PDO::FETCH_ASSOC);
						$db_link->commit();
						$saldo_incial = $row_saldo_inicial['valor'];
						//$saldo_incial = 48457;
						
					}
					else 
					{
						$db_link->rollBack();
						$msg="Ha ocurrido un error al intentar obtener el saldo inicial de la ruta $errorMessage_saldo_inicial ";
						echo $msg;
					} 
					/*
					 * MUESTRA EL SALDO DE LA RUTA AL FINALIZAR
					 * */
					$saldo_final = 0;
					$query_saldo_final = 	"select sum(saldo_producto) valor
											from (select tv.codigo, sum(tv.valor_total-vr.valor_recaudo) saldo_producto
													from trans_ventas tv, (SELECT TV_CODIGO, SUM( valor_recaudo ) valor_recaudo
																			FROM  trans_detalle_recaudo_ventas_jva
																			WHERE FECHA_RECAUDO <=  '$fecha_fin 23:59:59'
																			and aju_codigo  = $aju_codigo_vendedor
																			GROUP BY tv_codigo) vr
													where tv.codigo = vr.tv_codigo
													group by tv.codigo) sd";
					/*
					 * SE PREPARA EL QUERY
					 * */
					$result_saldo_final = $db_link->prepare($query_saldo_final);
					$db_link->beginTransaction();
					$result_saldo_final->execute(); //SE EJECUTA EL QUERY
					$arr_saldo_final = $result_saldo_final->errorInfo(); // SE OBTIENE EL ERROR
					$error_saldo_final = $arr_saldo_final[0];
					$errorMessage_saldo_final = str_replace("'", "", $arr_saldo_final[2]);
					/*
					 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
					 * */
					if($error_saldo_final =="00000")
					{									
						$row_saldo_final = $result_saldo_final->fetch(PDO::FETCH_ASSOC);
						$db_link->commit();
						$saldo_final = $row_saldo_inicial['valor'];
						//$saldo_final = 49571;
						
					}
					else 
					{
						$db_link->rollBack();
						$msg="Ha ocurrido un error al intentar obtener el saldo final de la ruta $errorMessage_saldo_final ";
						echo $msg;
					} 
					/*
					 * MUESTRA EL TOTAL DE CLIENTES DE LA RUTA AL FINALIZAR
					 * */
					$clientes_final = 0;
					$query_clientes_final = "SELECT count(codigo) valor 
											FROM trans_ventas 
											WHERE aju_codigo =  $aju_codigo_vendedor
											AND fecha_entrega <= '$fecha_fin'
											/*AND est_codigo = 1*/";
					/*
					 * SE PREPARA EL QUERY
					 * */
					$result_clientes_final = $db_link->prepare($query_clientes_final);
					$db_link->beginTransaction();
					$result_clientes_final->execute(); //SE EJECUTA EL QUERY
					$arr_clientes_final = $result_clientes_final->errorInfo(); // SE OBTIENE EL ERROR
					$error_clientes_final = $arr_clientes_final[0];
					$errorMessage_clientes_final = str_replace("'", "", $arr_clientes_final[2]);
					/*
					 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
					 * */
					if($error_clientes_final =="00000")
					{									
						$row_clientes_final = $result_clientes_final->fetch(PDO::FETCH_ASSOC);
						$db_link->commit();
						$clientes_final = $row_clientes_final['valor'];
						
					}
					else 
					{
						$db_link->rollBack();
						$msg="Ha ocurrido un error al intentar obtener el numero de clientes al finalizar la ruta $errorMessage_clientes_final ";
						echo $msg;
					} 
					/*
					 * MUESTRA EL PROMEDIO DE RECAUDO DE LA RUTA
					 * */
					$promedio_recaudo = ($valor_recaudo/6);

					/*
					 * MUESTRA EL RECAUDO DE LA RUTA
					 * */
					
					/*
					 * CONSULTA LOS CARGUES REALIZADOS A LA RUTA
					 * */
					$query_cargues = 	"SELECT sum(cantidad) valor_cargue 
										FROM trans_traslados_inventarios_jva 
										WHERE aju_codigo_hasta =  $aju_codigo_vendedor
										AND fecha_traslado BETWEEN  '$fecha_inicio' AND '$fecha_fin'";
					/*
					 * SE PREPARA EL QUERY
					 * */
					$result_cargues = $db_link->prepare($query_cargues);
					$db_link->beginTransaction();
					$result_cargues->execute(); //SE EJECUTA EL QUERY
					$arr_cargues = $result_cargues->errorInfo(); // SE OBTIENE EL ERROR
					$error_cargues = $arr_cargues[0];
					$errorMessage_cargues = str_replace("'", "", $arr_cargues[2]);
					/*
					 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
					 * */
					if($error_cargues =="00000")
					{									
						$row_cargues = $result_cargues->fetch(PDO::FETCH_ASSOC);
						$db_link->commit();
						$valor_cargue = $row_cargues['valor_cargue'];
						
					}
					else 
					{
						$db_link->rollBack();
						$msg="Ha ocurrido un error al intentar obtener el valor cargado a la ruta $errorMessage_cargues ";
						echo $msg;
					} 
					/*
					 * CONSULTA LOS RETIROS REALIZADOS A LA RUTA
					 * */
					$query_retiros = 	"SELECT sum(cantidad) valor_retiros 
										FROM trans_traslados_inventarios_jva 
										WHERE aju_codigo_desde =  $aju_codigo_vendedor
										AND fecha_traslado BETWEEN  '$fecha_inicio' AND '$fecha_fin'";
					/*
					 * SE PREPARA EL QUERY
					 * */
					$result_retiros = $db_link->prepare($query_retiros);
					$db_link->beginTransaction();
					$result_retiros->execute(); //SE EJECUTA EL QUERY
					$arr_retiros = $result_retiros->errorInfo(); // SE OBTIENE EL ERROR
					$error_retiros = $arr_retiros[0];
					$errorMessage_retiros = str_replace("'", "", $arr_retiros[2]);
					/*
					 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
					 * */
					if($error_retiros =="00000")
					{									
						$row_retiros = $result_retiros->fetch(PDO::FETCH_ASSOC);
						$db_link->commit();
						$valor_retiros = $row_retiros['valor_retiros'];
						
					}
					else 
					{
						$db_link->rollBack();
						$msg="Ha ocurrido un error al intentar obtener el valor total de los retiros realizados a la ruta $errorMessage_cargues ";
						echo $msg;
					} 
					/*
					 * CONSULTA LOS GASTOS CARGADOS A LA RUTA
					 * */
					$query_gastos = 	"SELECT sum(valor) valor_gastos 
										FROM trans_gastos_jva 
										WHERE aju_codigo =  $aju_codigo_vendedor
										AND fecha_gasto BETWEEN  '$fecha_inicio' AND '$fecha_fin'";
					/*
					 * SE PREPARA EL QUERY
					 * */
					$result_gastos = $db_link->prepare($query_gastos);
					$db_link->beginTransaction();
					$result_gastos->execute(); //SE EJECUTA EL QUERY
					$arr_gastos = $result_gastos->errorInfo(); // SE OBTIENE EL ERROR
					$error_gastos = $arr_gastos[0];
					$errorMessage_gastos = str_replace("'", "", $arr_gastos[2]);
					/*
					 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
					 * */
					if($error_gastos =="00000")
					{									
						$row_gastos = $result_gastos->fetch(PDO::FETCH_ASSOC);
						$db_link->commit();
						$valor_gastos = $row_gastos['valor_gastos'];
						
					}
					else 
					{
						$db_link->rollBack();
						$msg="Ha ocurrido un error al intentar obtener el valor total de los gastos para la ruta $errorMessage_gastos ";
						echo $msg;
					} 
					/*
					 * CONSULTA LAS VENTAS DE LA RUTA
					 * */
					$query_ventas = 	"SELECT sum(valor_producto) valor_ventas 
										FROM trans_ventas 
										WHERE aju_codigo =  $aju_codigo_vendedor
										AND fecha_entrega BETWEEN  '$fecha_inicio' AND '$fecha_fin'";
					/*
					 * SE PREPARA EL QUERY
					 * */
					$result_ventas = $db_link->prepare($query_ventas);
					$db_link->beginTransaction();
					$result_ventas->execute(); //SE EJECUTA EL QUERY
					$arr_ventas = $result_ventas->errorInfo(); // SE OBTIENE EL ERROR
					$error_ventas = $arr_ventas[0];
					$errorMessage_ventas = str_replace("'", "", $arr_ventas[2]);
					/*
					 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
					 * */
					if($error_ventas =="00000")
					{									
						$row_ventas = $result_ventas->fetch(PDO::FETCH_ASSOC);
						$db_link->commit();
						$valor_ventas = $row_ventas['valor_ventas'];
						
					}
					else 
					{
						$db_link->rollBack();
						$msg="Ha ocurrido un error al intentar obtener el valor total de las ventas de la ruta $errorMessage_ventas";
						echo $msg;
					} 
					/*
					 * MUESTRA EL SALARIO DEL VENDENDOR QUE REALIZA LA RUTA
					 * */										
				}
			}
			else 
			{
				$db_link->rollBack();
				$msg="Ha ocurrido un error al intentar obtener la liquidacion del vendedor $errorMessage_liquidacion ";
				echo $msg;
			}
		}
		else 
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener el aju_codigo del vendedor $errorMessage";
			echo $msg;
		}
		/*
		 * OBTIENE EL VALOR DEL AJUSTE DE LA RUTA PARA QUE FINALICE EN 0
		 * */
		$valor_ajuste_ruta = ($valor_recaudo + $valor_cargue)-($valor_retiros + $valor_gastos + $valor_ventas + $valor_salario_apagar);
		/*
		 * INVOCA EL ARCHIVO DE PRESENTACION 
		 * */
		include_once('../presentacion/rpt_salarios_liquidacion.php');
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