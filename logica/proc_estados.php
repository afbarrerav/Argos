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
	$est_codigo 	= $_REQUEST['ESTADO'];
	$usu_codigo		= $_REQUEST['USUARIO'];
	$usu_username	= $_REQUEST['USERNAME'];
	/*REALIZA EL PROCESO PARA LA ACCION LISTAR*/
	if($accion =="listar")
	{
		/*
		 * ESTABLECE LA CONEXION CON LA BASE DE DATOS
		 * */
		$db_link = new PDO($dsn, $username, $passwd);
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select codigo, nombre
					from tipos_estados";
		$result = $db_link->query($query);
		$select = "<select name = 'sestado_$usu_codigo' id='sestado_$usu_codigo' class='lista_desplegable' onchange=".'"'."actualizar_estado(this.value, '$usu_codigo','$usu_username')".'"'.">";
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$codigo = $row['codigo'];
			$nombre = $row['nombre'];
			if ($codigo==$est_codigo)
			{
				$select.="<option value = '$codigo' selected>$nombre</option>";
			}
			else
			{
				$select.="<option value = '$codigo'>$nombre</option>";
			}
		}
		$select .= "</select>";
		echo $select;
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