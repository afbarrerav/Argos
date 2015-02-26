<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Junio de 2011
 *
 * */
$DATABASE_NAME 	= "argos_bd2plataforma";
$dsn 			= "mysql:host=localhost;dbname=$DATABASE_NAME";
try
{
	$db_link = new PDO($dsn, 'fmojica', '!)FA84ma0616');
	$ruta ="http://50.63.181.150:8813/baco/archivos_csv/";
	//$ruta ="C:/AppServ/www/BackOffice/archivos_cvs/";
	$archivo = "rutas.csv";
	//Coloca el nombre de tu archivo .csv que contiene los datos
	$handle = fopen($ruta.$archivo, "r");
	$row=0;
	$clientes=0;
	//Lee toda una linea completa, e ingresa los datos en el array 'data'
	while (($data = fgetcsv($handle, 0, ";")) !== FALSE)
	{
		//Cuenta cuantos campos contiene la linea (el array 'data')
		/*
		 * INSERTA EL CLIENTE EN LA TABLA ADMIN_CLIENTES
		 * */
		$query_clientes = "insert into admin_clientes values ('$data[0]','$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$data[9]', '$data[10]', '$data[11]', '$data[12]', '$data[13]', '$data[14]', '$data[15]', '$data[16]', '$data[17]', '$data[18]', '$data[19]', '$data[20]', '$data[21]', '$data[22]')";
		//echo "QUERY_CLIENTES:".$query_clientes."<BR>";
		$result_clientes = $db_link->prepare($query_clientes);
		$db_link->beginTransaction();
		$result_clientes->execute(); //SE EJECUTA EL QUERY
		$arr_clientes = $result_clientes->errorInfo(); // SE OBTIENE EL ERROR
		$error_clientes = $arr_clientes[0];
		$errorMessage_clientes = str_replace("'", "", $arr_clientes[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error_clientes=="00000")
		{						
			$msg="El cliente $data[0] fue creado con exito"."<br>";
			echo $msg;
			$db_link->commit();
		}
		else
		{
			$msg="Ha ocurrido un error al intentar guardar El cliente $errorMessage_clientes"."<br>";
			echo $msg;
			$db_link->rollBack();
		} 	
		/*
		 * INSERTA LA VENTA PARA EL CLIENTE
		 * */
		$query_ventas = "insert into trans_ventas values ('$data[23]','$data[24]', '$data[25]', '$data[26]', '$data[27]', '$data[28]', '$data[29]', '$data[30]', '$data[31]', '$data[32]', '$data[33]', '$data[34]', '$data[35]', '$data[36]', '$data[37]', '$data[38]', '$data[39]')";
		//echo "QUERY_VENTAS:".$query_ventas."<br>";
		$result_ventas = $db_link->prepare($query_ventas);
		$db_link->beginTransaction();
		$result_ventas->execute(); //SE EJECUTA EL QUERY
		$arr_ventas = $result_ventas->errorInfo(); // SE OBTIENE EL ERROR
		$error_ventas = $arr_ventas[0];
		$errorMessage_ventas = str_replace("'", "", $arr_ventas[2]);
		echo $error_ventas ." ".$errorMessage_ventas;
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error_ventas=="00000")
		{			
			/*
			 * INSERTA LA VENTA EN LA RUTA
			 * */
			$db_link->commit();
			$query_rutas = "update trans_rutas_detalles set saldo = '$data[44]' where tv_codigo = '$data[41]'";
			//echo "QUERY_RUTAS:".$query_rutas."<BR>";
			$result_rutas = $db_link->prepare($query_rutas);
			$db_link->beginTransaction();
			$result_rutas->execute(); //SE EJECUTA EL QUERY
			$arr_rutas = $result_rutas->errorInfo(); // SE OBTIENE EL ERROR
			$error_rutas = $arr_rutas[0];
			$errorMessage_rutas = str_replace("'", "", $arr_rutas[2]);
			/*
			 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
			 * */
			if($error_rutas=="00000")
			{			
				$db_link->commit();
				/*
				 * SIMULA EL RECAUDO PARA QUE COINCIDA CON EL SALDO
				 * */
				$msg = "La venta $data[23] fue creada con exito"."<br>";	
				echo $msg;			
				$query = 	"SELECT trd.codigo, trd.trj_codigo, trd.tv_codigo, trd.secuencia, fun_nro_cuotas_recaudo(trd.tv_codigo) nro_cuotas, fun_valor_pago_cuota_recaudo(trd.tv_codigo) valor_cuota, 
							trd.saldo, tv.valor_total, trd.est_codigo 
							FROM trans_rutas_detalles trd, trans_ventas tv
							WHERE trd.tv_codigo = tv.codigo
							and trd.tv_codigo in ('$data[23]')";
				//echo "1.QUERY_RECAUDO:".$query."<BR>";
				/*
				 * SE PREPARA EL QUERY
				 * */
				$result = $db_link->query($query);
				$result->execute(); //SE EJECUTA EL QUERY
				$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
				$error = $arr[0];
				$errorMessage = str_replace("'", "", $arr[2]);
				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error=="00000")
				{
					//echo "2. QUERY_RECAUDO:".$query."<BR>";			
					while($row = $result->fetch(PDO::FETCH_ASSOC))
					{
						//echo "3. QUERY_RECAUDO:".$query."<BR>";
						/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
						$codigo		= $row['codigo'];
						$trj_codigo	= $row['trj_codigo'];
						$tv_codigo	= $row['tv_codigo'];
						$secuencia	= $row['secuencia'];
						$saldo		= $row['saldo'];
						$valor_total= $row['valor_total'];
						$valor_cuota= $row['valor_cuota'];
						$nro_cuotas	= $row['nro_cuotas'];
						$est_codigo	= $row['est_codigo'];
						
						$valor_recaudado = $valor_total - $saldo;
						$valor_recaudo = $valor_cuota;
						/*
						 * CONSTRUYE EL QUERY QUE ACTUALIZA EL RECAUDO
						 * */
						$i=1;
						//echo $valor_recaudado."<br>";
						while ($valor_recaudado > 0)
						{
							//echo "4. QUERY_RECAUDO:".$query."<BR>";								
							if ($valor_recaudo <= $valor_recaudado)
							{
								$query_update =	"UPDATE trans_detalle_recaudo_ventas_jva 
												set fecha_recaudo = '2012-01-20 00:00:01', valor_recaudo = $valor_recaudo, fecha_trans = now(), est_codigo = 4
												where tv_codigo = :tv_codigo and cuota_nro=:cuota_nro";
							}
							else 
							{
								if (($valor_recaudo > $valor_recaudado) && ($valor_recaudado > 0))
								{
									$query_update =	"UPDATE trans_detalle_recaudo_ventas_jva 
													set fecha_recaudo = '2012-01-20 00:00:01', valor_recaudo = $valor_recaudado, fecha_trans = now(), est_codigo = 5
													where tv_codigo = :tv_codigo and cuota_nro=:cuota_nro";
								}
								else 
								{
									$query_update =	"UPDATE trans_detalle_recaudo_ventas_jva 
													set fecha_recaudo = '0000-00-00 00:00:00', valor_recaudo = 0, fecha_trans = '0000-00-00 00:00:00', est_codigo = 3
													where tv_codigo = :tv_codigo and cuota_nro=:cuota_nro";
								}
							}
							/*
							 * SE PREPARA EL QUERY
							 * */
							$result_update = $db_link->prepare($query_update);
							$db_link->beginTransaction();
							$result_update->bindParam(':tv_codigo',$tv_codigo);
							$result_update->bindParam(':cuota_nro',$i);
							$result_update->execute(); //SE EJECUTA EL QUERY
							$arr_update = $result_update->errorInfo(); // SE OBTIENE EL ERROR
							$error_update = $arr_update[0];
							$errorMessage_update = str_replace("'", "", $arr_update[2]);
							/*
							 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
							 * */
							if($error_update=="00000")
							{								
								$valor_recaudado = $valor_recaudado - $valor_recaudo;
								$msg = "Se actualizo el recaudo nro $i de la venta $tv_codigo </BR>";
								echo $msg;
								$db_link->commit();
							}
							else
							{
								$msg="$query_update ::: Ha ocurrido un error al intentar actualizar el recaudo nro $i de la venta $tv_codigo :: $errorMessage_update </BR>";
								echo $msg;
								$db_link->rollBack();
							}				
							$i++;						
						}
					}
				}
				else
				{						
					$msg="Ha ocurrido un error al intentar consultar el saldo para la venta $errorMessage <br>";
					echo $msg;
				}
				//echo "1.AAAAAAA<br>";
			}
			else
			{
				$msg="Ha ocurrido un error al intentar guardar la ruta $errorMessage_rutas <br>";
				echo $msg;
				$db_link->rollBack();
			}
			//echo "2.AAAAAAA<br>";
		}
		else 
		{
			$msg="Ha ocurrido un error al intentar guardar la venta $errorMessage_ventas <br>";
			echo $msg;
			$db_link->rollBack();
		}				
	}
	fclose($handle);
	echo "Se crearon $clientes de $row clientes <br>";
}
catch (PDOException $e)
{
	$msg = $e->getMessage();
	echo $msg;
}
?>