<?php

include_once("variables_session.php");
try
{
	//CAPTURA LA ACCION A REALIZAR
	$accion 	= $_REQUEST['ACCION'];
	/*REALIZA EL PROCESO PARA LA ACCION LISTAR*/
	$db_link = new PDO($dsn, $username, $passwd);
	if($accion =="consultar_tablas")
	{
		$base_de_datos 		= $_REQUEST['BASE_DE_DATOS'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"SHOW TABLE STATUS FROM $base_de_datos";
		$result = $db_link->query($query);
		$select = "<select id='$select_cargar' class='lista_desplegable' $estado onChange='consultar_atributos_tabla()'>";
		$select.= "<option value = '0' selected>NO INDICADA</option>";
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
				$select.="<option value = '$codigo'>$nombre</option>";
			}
		}
		$select .= "</select>";
		echo $select;
	}
	
	if($accion =="consultar_tablas_logs")
	{
		$base_de_datos 		= $_REQUEST['BASE_DE_DATOS'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];

		$select = "<select id='$select_cargar' class='lista_desplegable' $estado onChange='consultar_atributos_tabla()'>";
		$select.= "<option value = '0' selected>NO INDICADA</option>";
				$select.="<option value='logs_accesos_usuarios'>logs accesos</option>";
				$select.="<option value='logs_errores_usuarios'>logs errores usuarios</option>";
				$select.="<option value='logs_sincronizaciones_usuarios'>logs sincronizaciones usuarios</option>";
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