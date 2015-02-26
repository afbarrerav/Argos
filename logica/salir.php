<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 * 
 * */
//session_start();
include ("../logica/variables_session.php");
try
{
	/*SE CREA LA INSTANCIA DEL OBJETO, SE REALIZA LA CONEXION A LA BD*/
	$db_link = new PDO($dsn, $username, $passwd);
	/*
	 * SE CONSTRUYE EL QUERY
	 * */
	$query_ra = 	"insert into logs_accesos_usuarios (aju_codigo, fecha_trans, accion, ip)
							values (:usu_codigo, sysdate(), 'logout', :ip)";
	/*
	 * SE PREPARA EL QUERY
	 * */
	$result_ra = $db_link->prepare($query_ra);
	$db_link->beginTransaction();
	$result_ra->bindParam(':usu_codigo',$aju_codigo);
	$result_ra->bindParam(':ip',$_SERVER['REMOTE_ADDR']);
	$result_ra->execute(); //SE EJECUTA EL QUERY
	$arr = $result_ra->errorInfo(); // SE OBTIENE EL ERROR
	$error = $arr[0];
	$errorMessage = str_replace("'", "", $arr[2]);
	/*
	 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
	 * */
	if($error == "00000")
	{
		$db_link->commit();
		session_unset();
		session_destroy();		
			?>
			<script>
				location.reload(true);
			</script>
			<?php
	}
	else
	{
		$db_link->rollBack();
		$msg="Ha ocurrido un error al intentar guardar la informacion $errorMessage";
		?>
		<script>
			alert("<?php echo $msg?>");
		</script>
	<?php 
	}
}
catch (PDOException $e)
{
	$msg = $e->getMessage();
	?>
	<script>
		alert("<?php echo $msg?>");
		location.reload(true);
	</script>
	<?php 
}

?>
