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
		$est_codigo		= 	$_REQUEST['est_codigo'];
		$jva_codigo 	= 	$_REQUEST['jva_codigo'];
		/*
		 * SE CONSTRUYE EL QUERY QUE ACTUALIZA EL ESTADO DEL USUARIO 
		 * */
		$query =	"update admin_jva set est_codigo = :est_codigo where codigo = :jva_codigo";
		
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':jva_codigo',$jva_codigo);
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
			/*
			 * RETIRA O PONE LOS PERMISOS AL USUARIO A NIVEL DE BD DE ACUERDO AL ESTADO 
			 * QUE HA SIDO ACTUALIZADO
			 * */	
			?>
			<script>AjaxConsulta( '../logica/proc_estados.php', {ACCION:'listar', ESTADO:'<?php echo $est_codigo?>', USUARIO:'<?php echo $usu_codigo?>', USERNAME:'<?php echo $usu_username?>'}, 'estado_<?php echo $usu_codigo?>' );</script>
			<?php
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			echo $msg;
		}		
	}
	
	if($accion == "guardar_editar")
	{
		$codigo				= $_REQUEST['codigo'];
		$nombre 			= $_REQUEST['nombre'];
		$descripcion		= $_REQUEST['descripcion'];
		$dis_codigo			= $_REQUEST['sdis'];
		$ciu_codigo			= $_REQUEST['sciu'];
		$direccion 			= $_REQUEST['direccion'];
		$fecha_creacion		= $_REQUEST['fecha_creacion'];
	
		/*
		 * SE CONSTRUYE EL QUERY QUE CREAR EL JVA
		 * */
		$query =	"update admin_jva set nombre = :nombre, descripcion = :descripcion, dis_codigo = :dis_codigo, ciu_codigo = :ciu_codigo, direccion = :direccion, fecha_creacion = :fecha_creacion where codigo = $codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':nombre',$nombre);
		$result->bindParam(':descripcion',$descripcion);
		$result->bindParam(':dis_codigo',$dis_codigo);
		$result->bindParam(':ciu_codigo',$ciu_codigo);
		$result->bindParam(':direccion',$direccion);
		$result->bindParam(':fecha_creacion',$fecha_creacion);
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
				alert("Error al intentar crear el JVA: <?php echo $errorMessage?>");
			</script>
			<?php
		}			
		$accion = "listar";
	}
	
	if($accion == "crear")
	{
		$nombre 			= $_REQUEST['nombre'];
		$descripcion		= $_REQUEST['descripcion'];
		$dis_codigo			= $_REQUEST['sdis'];
		$ciu_codigo			= $_REQUEST['sciu'];
		$direccion 			= $_REQUEST['direccion'];
		$fecha_creacion		= $_REQUEST['fecha_creacion'];
	
		/*
		 * SE CONSTRUYE EL QUERY QUE CREAR EL JVA
		 * */
		$query =	"insert into admin_jva (nombre, descripcion, dis_codigo, ciu_codigo, direccion, fecha_creacion, est_codigo)
					values (:nombre, :descripcion, :dis_codigo, :ciu_codigo, :direccion, :fecha_creacion, '1')";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':nombre',$nombre);
		$result->bindParam(':descripcion',$nombre);
		$result->bindParam(':dis_codigo',$dis_codigo);
		$result->bindParam(':ciu_codigo',$ciu_codigo);
		$result->bindParam(':direccion',$direccion);
		$result->bindParam(':fecha_creacion',$fecha_creacion);
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
				alert("Error al intentar crear el JVA: <?php echo $errorMessage?>");
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
		$query =	"SELECT codigo, nombre, descripcion, ciu_codigo, direccion, fecha_creacion, est_codigo  
					FROM admin_jva";
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
				$resultados['ciu_codigo'][$RowCount]	= $row['ciu_codigo'];
				$resultados['direccion'][$RowCount]		= $row['direccion'];
				$resultados['fecha_creacion'][$RowCount]= $row['fecha_creacion'];
				$resultados['est_codigo'][$RowCount]	= $row['est_codigo'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $error?>");
			</script>
			<?php
		}
		
		/*HACE EL LLAMADO AL ARCHIVO DE PRESENTACION*/
		include('../presentacion/admin_jva.php');
	}
	
	if($accion == "listar_editar")
	{
		$condicion = $_REQUEST['CODIGO'];
		/*
		 * SE CONSTRUYE EL QUERY
		 * */
		$query =	"SELECT codigo, nombre, descripcion, dis_codigo, ciu_codigo, direccion, fecha_creacion, est_codigo  
					FROM admin_jva WHERE codigo = $condicion";
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
			$row = $result->fetch(PDO::FETCH_ASSOC);
			{
				/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
				$codigo		= $row['codigo'];
				$nombre		= $row['nombre'];
				$descripcion= $row['descripcion'];
				$dis_codigo	= $row['dis_codigo'];
				$ciu_codigo	= $row['ciu_codigo'];
				$direccion	= $row['direccion'];
				$fecha_creacion = $row['fecha_creacion'];
				$est_codigo	= $row['est_codigo'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $error?>");
			</script>
			<?php
		}
		/*HACE EL LLAMADO AL ARCHIVO DE PRESENTACION*/
		include('../presentacion/admin_jva_e.php');
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