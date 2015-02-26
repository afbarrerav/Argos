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
		$prod_codigo 	= 	$_REQUEST['prod_codigo'];
		/*
		 * SE CONSTRUYE EL QUERY QUE ACTUALIZA EL ESTADO DEL USUARIO 
		 * */
		$query =	"update param_productos_jva set est_codigo = :est_codigo where codigo = :prod_codigo";
		
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':prod_codigo',$prod_codigo);
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
	
	if($accion == "crear")
	{
		$nombre 		= $_REQUEST['nombre'];
		$descripcion	= $_REQUEST['descripcion'];
		$um_codigo		= $_REQUEST['um_codigo'];
		$valor			= $_REQUEST['valor'];
		$jva_codigo		= $_REQUEST['jva_codigo'];
		$tpj_codigo		= $_REQUEST['tpj_codigo'];
		$cat_codigo		= $_REQUEST['cat_codigo'];
	
		/*
		 * SE CONSTRUYE EL QUERY QUE CREAR EL JVA
		 * */
		$query =	"insert into param_productos_jva (nombre, descripcion, um_codigo, valor, jva_codigo, tpj_codigo, cat_codigo, est_codigo)
					values (:nombre, :descripcion, :um_codigo, :valor, :jva_codigo, :tpj_codigo, :cat_codigo, '1')";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':nombre',$nombre);
		$result->bindParam(':descripcion',$descripcion);
		$result->bindParam(':um_codigo',$um_codigo);
		$result->bindParam(':valor',$valor);
		$result->bindParam(':jva_codigo',$jva_codigo);
		$result->bindParam(':tpj_codigo',$tpj_codigo);
		$result->bindParam(':cat_codigo',$cat_codigo);
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
		$query =	"SELECT codigo, nombre, descripcion, um_codigo, valor, tpj_codigo, cat_codigo, est_codigo  
					FROM param_productos_jva
					where jva_codigo = (select jva_codigo from admin_jva_usuarios where usu_codigo ='$usu_codigo')";
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
				$resultados['um_codigo'][$RowCount]		= $row['um_codigo'];
				$resultados['valor'][$RowCount]			= $row['valor'];
				$resultados['tpj_codigo'][$RowCount]	= $row['tpj_codigo'];
				$resultados['cat_codigo'][$RowCount]	= $row['cat_codigo'];
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
		include('../presentacion/admin_productos.php');
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