<?php
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
	if($accion == "ajustar_saldos")
	{
		
		/*
		 * AJUSTAR SALDO VENTA
		 * */
		$query = 	"SELECT tv.codigo, tv.valor_total, valor_recaudo
					FROM trans_ventas tv, (SELECT tv_codigo, SUM( valor_recaudo ) valor_recaudo
											FROM  `trans_detalle_recaudo_ventas_jva` 
											GROUP BY tv_codigo
											)tv_recaudo
					WHERE tv.codigo = tv_recaudo.tv_codigo";
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
				$saldo		= $row['valor_total'] - $row['valor_recaudo'];
	
				/*
				 * CONSTRUYE EL QUERY QUE ACTUALIZA EL RECAUDO
				 * */
				$i=1;
				
					$query_update =	"UPDATE trans_rutas_detalles 
									set saldo = $saldo
									where  tv_codigo = :tv_codigo";
					/*
					 * SE PREPARA EL QUERY
					 * */
					$result_update = $db_link->prepare($query_update);
					$db_link->beginTransaction();
					$result_update->bindParam(':tv_codigo',$codigo);
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
						$msg="Se actualizo el saldo para la venta codigo $codigo</BR>";
						echo $msg;
					}
					else
					{
						$db_link->rollBack();
						$msg="$query_update ::: Ha ocurrido un error al intentar actualizar la venta $codigo :: $errorMessage_update</BR>";
						echo $msg;
					}		
				
			}
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar guardar la informacion $errorMessage";
			echo $msg;
		}	
		/*
		 * AJUSTAR SALDO RUTA
		 * */
		$query = 	"SELECT trj.codigo, valor_saldos
					FROM trans_rutas_jva trj, (	SELECT trj_codigo, SUM( saldo ) valor_saldos
												FROM  `trans_rutas_detalles` 
												GROUP BY trj_codigo
												)trj_saldo
					WHERE trj.codigo = trj_saldo.trj_codigo";
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
				$saldo		= $row['valor_saldos'];
	
				/*
				 * CONSTRUYE EL QUERY QUE ACTUALIZA EL RECAUDO
				 * */
					$query_update =	"UPDATE trans_rutas_jva 
									set saldo = $saldo
									where  codigo = :trj_codigo";
					/*
					 * SE PREPARA EL QUERY
					 * */
					$result_update = $db_link->prepare($query_update);
					$db_link->beginTransaction();
					$result_update->bindParam(':trj_codigo',$codigo);
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
						$msg="Se actualizo el saldo para la ruta codigo $codigo</BR>";
						echo $msg;
					}
					else
					{
						$db_link->rollBack();
						$msg="$query_update ::: Ha ocurrido un error al intentar actualizar la ruta $codigo :: $errorMessage_update</BR>";
						echo $msg;
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
?>
