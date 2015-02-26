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
	$accion 	= $_REQUEST['ACCION'];
	/*
	 * ESTABLECE LA CONEXION CON LA BASE DE DATOS
	 * */
	
	$db_link = new PDO($dsn, $username, $passwd);
	/*REALIZA EL PROCESO PARA LA ACCION LISTAR*/
	if($accion =="consultar_atributos")
	{
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];

		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"describe $tabla_consultar";
		$result = $db_link->query($query);
	
		$select = "<select id='$select_cargar' class='lista_desplegable' $estado>";
		$select.= "<option value ='0' selected>NO INDICADA</option>";
		while($row = $result->fetch(PDO::FETCH_NUM))
		{
			$codigo = $row[0];
			$nombre = $row[0];
			if ($codigo=='')
			{
				$select.="<option value = '$codigo' selected>$nombre</option>";
			}
			else
			{
				$select.="<option value ='$codigo'>$nombre</option>";
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