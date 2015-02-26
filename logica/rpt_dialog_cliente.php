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
	
	if($accion == "consultar_detallado_cliente")
	{
		$tv_codigo = $_REQUEST['TV_CODIGO'];
		$cli_codigo = $_REQUEST['CLI_CODIGO'];
		$vendedor	= $_REQUEST['VENDEDOR'];
		
		$query = "SELECT tdrvj.fecha_pago, tdrvj.valor_pago, tdrvj.fecha_recaudo, tdrvj.valor_recaudo, tdrvj.cuota_nro
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
				$detalle['cuota_nro'][$RowCount] 			= $row['cuota_nro'];
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
		include ('../presentacion/rpt_dialog_cliente.php');
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