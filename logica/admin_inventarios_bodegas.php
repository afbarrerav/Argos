<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Junio de 2011
 *
 * */
//session_start();
include_once ("../logica/variables_session.php");
try
{

	$db_link = new PDO($dsn, $username, $passwd);
	$accion = $_REQUEST['ACCION'];
	if($accion == "mostrar_front")
	{
		include ('../presentacion/admin_inventarios_bodegas.php');
	}
	
	if($accion == "actualizar_estado")
	{
		$est_codigo	= 	$_REQUEST['est_codigo'];
		$codigo 	= 	$_REQUEST['codigo'];
		/*
		 * SE CONSTRUYE EL QUERY QUE ACTUALIZA EL ESTADO DEL USUARIO 
		 * */
		$query =	"update admin_inventarios_bodegas set est_codigo = :est_codigo where codigo = :codigo";
		
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
	
	if($accion == "frm_agregar_producto_inventario")
	{
		include('../presentacion/admin_inventarios_bodegas_n.php');
	}
	if($accion == "agregar_producto_inventario")
	{
		$bod_codigo1	= $_REQUEST['sbodega1'];
		$pro_codigo		= $_REQUEST['sproducto'];
		$cantidad		= $_REQUEST['cantidad'];
	
		/*
		 * SE CONSTRUYE EL QUERY QUE CREAR EL JVA
		 * */
		$query =	"insert into admin_inventarios_bodegas (bod_codigo, pro_codigo, cantidad, est_codigo)
					values (:bod_codigo, :pro_codigo, :cantidad, '1')";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':bod_codigo',$bod_codigo1);
		$result->bindParam(':pro_codigo',$pro_codigo);
		$result->bindParam(':cantidad',$cantidad);
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
		$bod_codigo		= $_REQUEST['sbodega'];
		/*
		 * SE CONSTRUYE EL QUERY
		 * */
		if($bod_codigo == "0")
		{
			$query =	"SELECT '0' codigo, '0' bod_codigo, pro_codigo, sum(cantidad) cantidad, est_codigo  
						FROM admin_inventarios_bodegas
						where bod_codigo in (select codigo 
											from admin_bodegas 
											where aju_codigo in (select codigo 
																from admin_jva_usuarios 
																where jva_codigo in (select jva_codigo from admin_jva_usuarios where usu_codigo = $usu_codigo)
																)
											)
						group by pro_codigo";
		}
		else
		{
			$query =	"SELECT codigo, bod_codigo, pro_codigo, cantidad, est_codigo  
						FROM admin_inventarios_bodegas
						where bod_codigo = $bod_codigo";
		} 
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
				$resultados['bod_codigo'][$RowCount]	= $row['bod_codigo'];
				$resultados['pro_codigo'][$RowCount]	= $row['pro_codigo'];
				$resultados['cantidad'][$RowCount]		= $row['cantidad'];
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
		include('../presentacion/admin_inventarios_bodegas_detalle.php');
	}
	
	if($accion == "listar_bodega_usuario")
	{
		$query =	"SELECT '0' codigo, '0' bod_codigo, pro_codigo, sum(cantidad) cantidad, est_codigo  
						FROM admin_inventarios_bodegas
						where bod_codigo in (select codigo 
											from admin_bodegas 
											where aju_codigo in (select codigo 
																from admin_jva_usuarios 
																where usu_codigo  = $usu_codigo
																)
											)
						group by pro_codigo";
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
				$resultados['bod_codigo'][$RowCount]	= $row['bod_codigo'];
				$resultados['pro_codigo'][$RowCount]	= $row['pro_codigo'];
				$resultados['cantidad'][$RowCount]		= $row['cantidad'];
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
		include('../presentacion/admin_inventarios_bodegas_detalle.php');
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