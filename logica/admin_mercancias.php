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
	if ($accion=="mostrar_front")
	{
		$bod_codigo = $_REQUEST['BOD_CODIGO'];
		include '../presentacion/admin_mercancias.php';		
	}
	
	if ($accion=="traslado_productos_bodegas")
	{
		$bod_codigo = $_REQUEST['BOD_CODIGO'];
		$pro_codigo = $_REQUEST['PRO_CODIGO'];
		/*
		 * REALIZA EL LLAMADO A LA FUNCION ENCARGADA DE REALIZAR LA TRANSFERENCIA DEL PRODUCTO
		 * */
		?>
		<script>
			AjaxConsulta('../logica/presentar_traslado_bodega_detalle.php', {BOD_CODIGO:'<?php echo $bod_codigo?>', PRO_CODIGO:'<?php echo $pro_codigo?>', ACCION:'traslado_productos_bodegas'}, 'detalle_traslado_productos');
		</script>
		<?php		
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