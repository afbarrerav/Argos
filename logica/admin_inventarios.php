<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2012
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
		include ('../presentacion/admin_inventarios.php');
	}
	/*
	 * Lista el inventario para cada uno de los usuarios de los JVA de los cuales es socio
	 * */
	if($accion == "listar_inventario")
	{
		/*
		 * SE CONSTRUYE EL QUERY QUE ACTUALIZA EL ESTADO DEL USUARIO 
		 * */
		$query =	"select tij.codigo, tij.aju_codigo, fun_obtener_aju_nombre(tij.aju_codigo) aju_nombre, tij.pro_codigo, tij.cantidad, tij.fecha_inventario, tij.fecha_transaccion
					from trans_inventarios_jva tij 
					where tij.aju_codigo in (select codigo from admin_jva_usuarios where jva_codigo in (select jva_codigo from admin_jva_usuarios where usu_codigo = :usu_codigo))";
		
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':usu_codigo',$usu_codigo);
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
			$RowCount = 0;
			while ($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$resultado[$RowCount]['codigo'] 			= $row['codigo'];
				$resultado[$RowCount]['aju_codigo'] 		= $row['aju_codigo'];
				$resultado[$RowCount]['aju_nombre'] 		= $row['aju_nombre'];
				$resultado[$RowCount]['pro_codigo'] 		= "EFECTIVO";
				$resultado[$RowCount]['cantidad'] 			= $row['cantidad'];
				$resultado[$RowCount]['fecha_inventario'] 	= $row['fecha_inventario'];
				$resultado[$RowCount]['fecha_transaccion'] 	= $row['fecha_transaccion'];
				$RowCount++;
			}
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			echo $msg;
		}		
		include_once('../presentacion/admin_inventarios_detalle.php');
	}
	
	if($accion == "listar_inventario_jva")
	{
		$jva_codigo = $_REQUEST['JVA_CODIGO'];
		/*
		 * SE CONSTRUYE EL QUERY QUE ACTUALIZA EL ESTADO DEL USUARIO 
		 * */
		$query =	"select tij.codigo, tij.aju_codigo, fun_obtener_aju_nombre(tij.aju_codigo) aju_nombre, tij.pro_codigo, tij.cantidad, tij.fecha_inventario, tij.fecha_transaccion
					from trans_inventarios_jva tij 
					where tij.aju_codigo in (select codigo from admin_jva_usuarios where jva_codigo = :jva_codigo)";
		
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':jva_codigo',$jva_codigo);
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
			$RowCount = 0;
			while ($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$resultado[$RowCount]['codigo'] 			= $row['codigo'];
				$resultado[$RowCount]['aju_codigo'] 		= $row['aju_codigo'];
				$resultado[$RowCount]['aju_nombre'] 		= $row['aju_nombre'];
				$resultado[$RowCount]['pro_codigo'] 		= "EFECTIVO";
				$resultado[$RowCount]['cantidad'] 			= $row['cantidad'];
				$resultado[$RowCount]['fecha_inventario'] 	= $row['fecha_inventario'];
				$resultado[$RowCount]['fecha_transaccion'] 	= $row['fecha_transaccion'];
				$RowCount++;
			}
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			echo $msg;
		}		
		include_once('../presentacion/admin_inventarios_detalle.php');
	}
	if($accion == "listar_inventario_usuario")
	{
		$usu_codigo = $_REQUEST['USU_CODIGO'];
		/*
		 * SE CONSTRUYE EL QUERY QUE ACTUALIZA EL ESTADO DEL USUARIO 
		 * */
		$query =	"select tij.codigo, tij.aju_codigo, fun_obtener_aju_nombre(tij.aju_codigo) aju_nombre, tij.pro_codigo, tij.cantidad, tij.fecha_inventario, tij.fecha_transaccion
					from trans_inventarios_jva tij 
					where tij.aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo = :usu_codigo)";
		
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':usu_codigo',$usu_codigo);
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
			$RowCount = 0;
			while ($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$resultado[$RowCount]['codigo'] 			= $row['codigo'];
				$resultado[$RowCount]['aju_codigo'] 		= $row['aju_codigo'];
				$resultado[$RowCount]['aju_nombre'] 		= $row['aju_nombre'];
				$resultado[$RowCount]['pro_codigo'] 		= "EFECTIVO";
				$resultado[$RowCount]['cantidad'] 			= $row['cantidad'];
				$resultado[$RowCount]['fecha_inventario'] 	= $row['fecha_inventario'];
				$resultado[$RowCount]['fecha_transaccion'] 	= $row['fecha_transaccion'];
				$RowCount++;
			}
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			echo $msg;
		}		
		include_once('../presentacion/admin_inventarios_detalle.php');
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