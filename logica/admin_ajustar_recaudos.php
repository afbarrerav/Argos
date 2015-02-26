0<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Diciembre de 2012
 * 
 * */
$DATABASE_NAME 	= "argos_bd2plataforma";
$dsn 			= "mysql:host=localhost;dbname=$DATABASE_NAME";
try
{
	/*SE CREA LA INSTANCIA DEL OBJETO, SE REALIZA LA CONEXION A LA BD*/
	$db_link = new PDO($dsn, 'fmojica', '!)FA84ma0616');
	$accion = $_REQUEST['ACCION'];
	/*REALIZAR EL PROCESO PARA GUARDAR LA INFORMACION*/
	if($accion == "ajustar_recaudos")
	{
		
		/*REALIZAMOS LA CONSULTA SOBRE EL RECAUDO*/
		$query = 	"SELECT trd.codigo, trd.trj_codigo, trd.tv_codigo, trd.secuencia, fun_nro_cuotas_recaudo(trd.tv_codigo) nro_cuotas, fun_valor_pago_cuota_recaudo(trd.tv_codigo) valor_cuota, 
					trd.saldo, tv.valor_total, trd.est_codigo 
					FROM trans_rutas_detalles trd, trans_ventas tv
					WHERE trd.tv_codigo = tv.codigo
					and trd.aju_codigo in (10001, 10002, 10003)";
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
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
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
				for ($i=1;$i<=$nro_cuotas;$i++)
				{
					if ($valor_recaudo <= $valor_recaudado)
					{
						$query_update =	"UPDATE trans_detalle_recaudo_ventas_jva 
										set valor_recaudo = $valor_recaudo, fecha_trans = now(), est_codigo = 4
										where  tv_codigo = :tv_codigo and cuota_nro=:cuota_nro";
					}
					else 
					{
						if (($valor_recaudo > $valor_recaudado) && ($valor_recaudado > 0))
						{
							$query_update =	"UPDATE trans_detalle_recaudo_ventas_jva 
											set valor_recaudo = $valor_recaudado, fecha_trans = now(), est_codigo = 5
											where tv_codigo = :tv_codigo and cuota_nro=:cuota_nro";
						}
						else 
						{
							$query_update =	"UPDATE trans_detalle_recaudo_ventas_jva 
											set fecha_recaudo = '0000-00-00 00:00:00', valor_recaudo = 0, fecha_trans = now(), est_codigo = 3
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
						$db_link->commit();
						$msg="Se actualizo el recaudo nro $i de la venta $tv_codigo</BR>";
						echo $msg;
					}
					else
					{
						$db_link->rollBack();
						$msg="$query_update ::: Ha ocurrido un error al intentar actualizar el recaudo nro $i de la venta $tv_codigo :: $errorMessage_update</BR>";
						echo $msg;
					}		
					$valor_recaudado = $valor_recaudado - $valor_recaudo;
				}
			}
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar guardar la informacion $errorMessage";
			echo $msg;
		}		
	}
}
catch (PDOException $e)
{
	$msg = $e->getMessage();
	?>
	<script>
		alert("<?php echo $msg?>");
	</script>
	<?php 
}
?>
