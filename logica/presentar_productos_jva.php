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

	if($accion == "listar")
	{
		/*
		 * SE CONSTRUYE EL QUERY
		 * */
		$query =	"SELECT codigo, nombre, descripcion, um_codigo, valor, jva_codigo, tpj_codigo, cat_codigo, ruta_img, est_codigo  
					FROM param_productos_jva
					where jva_codigo = (select jva_codigo from admin_jva_usuarios where usu_codigo ='$usu_codigo')
					and est_codigo = 1";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
				$resultados['codigo'][$RowCount]		= $row['codigo'];
				$resultados['nombre'][$RowCount]		= $row['nombre'];
				$resultados['descripcion'][$RowCount]	= $row['descripcion'];
				$resultados['valor'][$RowCount]			= $row['valor'];
				$resultados['um_codigo'][$RowCount]		= $row['um_codigo'];
				$resultados['jva_codigo'][$RowCount]	= $row['jva_codigo'];
				$resultados['tpj_codigo'][$RowCount]	= $row['tpj_codigo'];
				$resultados['cat_codigo'][$RowCount]	= $row['cat_codigo'];
				$resultados['ruta_img'][$RowCount]		= $row['ruta_img'];
				$resultados['est_codigo'][$RowCount]	= $row['est_codigo'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $errorMessage?>");
			</script>
			<?php
		}		
		/*HACE EL LLAMADO AL ARCHIVO DE PRESENTACION*/
		include('../presentacion/presentar_productos_jva.php');
	}
	
	if($accion == "buscar_productos_categoria")
	{
		$cat_codigo = $_REQUEST['CAT_CODIGO'];
		/*
		 * SE CONSTRUYE EL QUERY
		 * */
		$query =	"SELECT codigo, nombre, descripcion, um_codigo, valor, jva_codigo, tpj_codigo, cat_codigo, ruta_img, est_codigo  
					FROM param_productos_jva
					where jva_codigo = (select jva_codigo from admin_jva_usuarios where usu_codigo ='$usu_codigo')
					and cat_codigo = '$cat_codigo'
					and est_codigo = 1";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
				$resultados['codigo'][$RowCount]		= $row['codigo'];
				$resultados['nombre'][$RowCount]		= $row['nombre'];
				$resultados['descripcion'][$RowCount]	= $row['descripcion'];
				$resultados['valor'][$RowCount]			= $row['valor'];
				$resultados['um_codigo'][$RowCount]		= $row['um_codigo'];
				$resultados['jva_codigo'][$RowCount]	= $row['jva_codigo'];
				$resultados['tpj_codigo'][$RowCount]	= $row['tpj_codigo'];
				$resultados['cat_codigo'][$RowCount]	= $row['cat_codigo'];
				$resultados['ruta_img'][$RowCount]		= $row['ruta_img'];
				$resultados['est_codigo'][$RowCount]	= $row['est_codigo'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $errorMessage?>");
			</script>
			<?php
		}		
		/*HACE EL LLAMADO AL ARCHIVO DE PRESENTACION*/
		include('../presentacion/presentar_productos_jva.php');
	}
	
	if($accion == "buscar_productos")
	{
		$valor_busqueda = $_REQUEST['VALOR_BUSQUEDA'];
		/*
		 * SE CONSTRUYE EL QUERY
		 * */
		$query =	"SELECT codigo, nombre, descripcion, um_codigo, valor, jva_codigo, tpj_codigo, cat_codigo, ruta_img, est_codigo  
					FROM param_productos_jva
					where jva_codigo = (select jva_codigo from admin_jva_usuarios where usu_codigo ='$usu_codigo')
					and (codigo like '%$valor_busqueda%'
							or nombre like '%$valor_busqueda%'
							or descripcion like '%$valor_busqueda%')
					and est_codigo = 1";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
				$resultados['codigo'][$RowCount]		= $row['codigo'];
				$resultados['nombre'][$RowCount]		= $row['nombre'];
				$resultados['descripcion'][$RowCount]	= $row['descripcion'];
				$resultados['valor'][$RowCount]			= $row['valor'];
				$resultados['um_codigo'][$RowCount]		= $row['um_codigo'];
				$resultados['jva_codigo'][$RowCount]	= $row['jva_codigo'];
				$resultados['tpj_codigo'][$RowCount]	= $row['tpj_codigo'];
				$resultados['cat_codigo'][$RowCount]	= $row['cat_codigo'];
				$resultados['ruta_img'][$RowCount]		= $row['ruta_img'];
				$resultados['est_codigo'][$RowCount]	= $row['est_codigo'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $errorMessage?>");
			</script>
			<?php
		}		
		/*HACE EL LLAMADO AL ARCHIVO DE PRESENTACION*/
		include('../presentacion/presentar_productos_jva.php');
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