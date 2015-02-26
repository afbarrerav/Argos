<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Junio de 2011
 *
 * */
session_start();
include_once ("../logica/variables_session.php");
try
{

	$db_link = new PDO($dsn, $username, $passwd);
	$accion = $_REQUEST['ACCION'];
	if($accion == "mostrar_front")
	{
		include ('../presentacion/auditoria.php');
	}
}
catch (PDOException $e)
{
	$msg = $e->getMessage();
	?>
	<script>
		alert("Excepcion controlada: <?php echo $msg?>");
	</script>
	<?php
}
?>