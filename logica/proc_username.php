<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Junio de 2011
 * 
 * */
include_once("variables_session.php");
try
{
	//CAPTURA LA ACCION A REALIZAR
	$accion 		= $_REQUEST['ACCION'];
	/*REALIZA EL PROCESO PARA LA ACCION LISTAR*/
	if($accion =="consultar")
	{
		$usu_username	= $_REQUEST['USERNAME'];
		/*
		 * ESTABLECE LA CONEXION CON LA BASE DE DATOS
		 * */
		$db_link = new PDO($dsn, $username, $passwd);
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select *
					from admin_usuarios
					where username = '$usu_username'";
		$result = $db_link->query($query);
		$rowCount = $result->rowCount();
		if($rowCount==0)
		{
			?>
			<input type="hidden" id="username_valido" value="SI">
			<img src="imagenes/iconos/check_mark.png" alt="Nombre disponible" title="Nombre disponible" width="20" align="absbottom">
			<?php 
		}
		else
		{
			?>
			<input type="hidden" id="username_valido" value="NO">
			<img src="imagenes/iconos/lock.png" alt="Nombre disponible" title="Nombre disponible" width="20" align="absbottom">
			<?php 
		}
	}
	$db_link = null;
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