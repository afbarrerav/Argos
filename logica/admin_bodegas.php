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
	if($accion == "actualizar_estado")
	{
		$est_codigo	= 	$_REQUEST['est_codigo'];
		$codigo 	= 	$_REQUEST['codigo'];
		/*
		 * SE CONSTRUYE EL QUERY QUE ACTUALIZA EL ESTADO DEL USUARIO 
		 * */
		$query =	"update admin_bodegas set est_codigo = :est_codigo where codigo = :codigo";
		
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':codigo',$codigo);
		$result->bindParam(':est_codigo',$est_codigo);
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
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			echo $msg;
		}		
	}
	
	if($accion == "crear")
	{
		$descripcion	= $_REQUEST['descripcion'];
		$aju_codigo		= $_REQUEST['saju'];
	
		/*
		 * SE CONSTRUYE EL QUERY QUE CREAR EL JVA
		 * */
		$query =	"insert into admin_bodegas (aju_codigo, descripcion, est_codigo)
					values (:aju_codigo, :descripcion, '1')";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':aju_codigo',$aju_codigo);
		$result->bindParam(':descripcion',$descripcion);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr 			= $result->errorInfo(); // SE OBTIENE EL ERROR
		$error			= $arr[0];
		$errorMessage	= str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$db_link->commit();
			
		}
		else
		{
			$db_link->rollBack();
			?>
			<script>
				alert("Error al intentar crear la bodega: <?php echo $errorMessage?>");
			</script>
			<?php
		}			
		$accion = "listar";
	}
	
	if($accion == "listar")
	{
		/*
		 * SE CONSTRUYE EL QUERY
		 * */
		$query =	"SELECT codigo, aju_codigo, descripcion, est_codigo  
					FROM admin_bodegas
					where aju_codigo in (select codigo 
										from admin_jva_usuarios 
										where jva_codigo in (select jva_codigo 
															from admin_jva_usuarios 
															where usu_codigo = '$usu_codigo'
															)
										)";
		/*
		 * SE PREPARA EL QUERY
		 * */
		//echo $query;
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
				$resultados['aju_codigo'][$RowCount]	= $row['aju_codigo'];
				$resultados['descripcion'][$RowCount]	= $row['descripcion'];
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
		include('../presentacion/admin_bodegas.php');
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