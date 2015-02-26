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
	if ($accion=="traslado_productos_bodegas")
	{
		$bod_codigo = $_REQUEST['BOD_CODIGO'];
		$pro_codigo = $_REQUEST['PRO_CODIGO'];
		/*
		 * VERIFICA SI EL PRODUCTO EXISTE EN LA BODEGA DEL VENDEDOR
		 * */
		$query = 	"select * 
					from admin_inventarios_bodegas  
					where pro_codigo = :pro_codigo 
					and bod_codigo = :bod_codigo";
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':bod_codigo',$bod_codigo);
		$result->bindParam(':pro_codigo',$pro_codigo);
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
			$producto_existe = $result->rowCount();
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			echo $msg;
		}

		/*
		 * ENVIA EL PRODUCTO A LA BODEGA
		 * */
		if($producto_existe == 0)
		{
			$query = 	"insert into admin_inventarios_bodegas(bod_codigo, pro_codigo, cantidad, est_codigo) 
						values(:bod_codigo, :pro_codigo, 1, 1)";
		}
		else 
		{
			$query = "update admin_inventarios_bodegas 
						set cantidad = cantidad + 1 
						where pro_codigo = :pro_codigo 
						and bod_codigo = :bod_codigo";
		}
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':bod_codigo',$bod_codigo);
		$result->bindParam(':pro_codigo',$pro_codigo);
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
			?>
			<script>
				AjaxConsulta('../logica/presentar_traslado_bodega_detalle.php', {BOD_CODIGO:'<?php echo $bod_codigo?>', ACCION:'listar'}, 'detalle_traslado_productos');
			</script>
			<?php
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			echo $msg;
		}		
	}
	
	if ($accion=="actualizar_cantidad_productos_bodegas")
	{
		$bod_codigo 	= $_REQUEST['BOD_CODIGO'];
		$pro_codigo 	= $_REQUEST['PRO_CODIGO'];
		$pro_cantidad 	= $_REQUEST['PRO_CANTIDAD'];
		/*
		 * ENVIA EL PRODUCTO A LA BODEGA
		 * */
		$query = "update admin_inventarios_bodegas 
					set cantidad = :pro_cantidad 
					where pro_codigo = :pro_codigo 
					and bod_codigo = :bod_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':bod_codigo',$bod_codigo);
		$result->bindParam(':pro_codigo',$pro_codigo);
		$result->bindParam(':pro_cantidad',$pro_cantidad);
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
			?>
			<script>
				AjaxConsulta('../logica/presentar_traslado_bodega_detalle.php', {BOD_CODIGO:'<?php echo $bod_codigo?>', ACCION:'listar'}, 'detalle_traslado_productos');
			</script>
			<?php
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			echo $msg;
		}		
	}
	if ($accion=="listar")
	{
		$bod_codigo = $_REQUEST['BOD_CODIGO'];
		/*
		 * OBTIENE LOS PRODUCTOS EXISTENTES EN LA BODEGA
		 * */
		$query = 	"select aib.codigo, aib.pro_codigo, ppj.nombre, aib.cantidad, ppj.valor  
					from admin_inventarios_bodegas aib, param_productos_jva ppj 
					where aib.pro_codigo = ppj.codigo 
					and bod_codigo = :bod_codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':bod_codigo',$bod_codigo);
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
			$RowCountPB = 0;
			$valor_total_bodega = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
				$resultados['codigo'][$RowCountPB]			= $row['codigo'];
				$resultados['pro_codigo'][$RowCountPB]		= $row['pro_codigo'];
				$resultados['pro_nombre'][$RowCountPB]		= $row['nombre'];
				$resultados['pro_cantidad'][$RowCountPB]	= $row['cantidad'];
				$resultados['pro_valor'][$RowCountPB]		= $row['valor'];
				$resultados['pro_valor_total'][$RowCountPB] = $resultados['pro_valor'][$RowCountPB]*$resultados['pro_cantidad'][$RowCountPB];
				$valor_total_bodega = $valor_total_bodega + $resultados['pro_valor_total'][$RowCountPB];
				$RowCountPB++;
			}
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			echo $msg;
		}		
		include '../presentacion/presentar_traslado_bodega_detalle.php';		
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