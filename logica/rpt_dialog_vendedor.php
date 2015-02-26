<?php
/*
 * @author:	MIGUEL ANGEL POSADA
 * 			miguelrodriguezpo@hotmail.com
 * @version:2.0.0
 * @fecha:	enero de 2013
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
	
	if($accion == "consultar_informacion_vendedor")
	{
		$vendedor	= $_REQUEST['VENDEDOR'];
		
		$query = "SELECT codigo, ti_codigo, nro_identificacion, CONCAT(nombres, ' ' ,apellidos) NOMBRES, fecha_nacimiento, gen_codigo, ciu_codigo, direccion, telefono, email, username, est_codigo
					FROM admin_usuarios 
					WHERE CONCAT(nombres, ' ', apellidos) LIKE '%$vendedor%'";
		
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
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$codigo				= $row['codigo'];
			$ti_codigo 			= $row['ti_codigo'];
			$nro_identificacion = $row['nro_identificacion'];
			$NOMBRES 			= $row['NOMBRES'];
			$fecha_nacimiento	= $row['fecha_nacimiento'];
			$gen_codigo			= $row['gen_codigo'];
			$ciu_codigo 		= $row['ciu_codigo'];
			$direccion 			= $row['direccion'];
			$telefono 			= $row['telefono'];
			$email				= $row['email'];
			$username 			= $row['username'];
			$est_codigo 		= $row['est_codigo'];
		}
		else
		{
			$db_link->rollBack();
			?>
				<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include ('../presentacion/rpt_dialog_vendedor.php');
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
