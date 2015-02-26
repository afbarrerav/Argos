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
	$fecha = date('Y-m-d');
	$fecha_desde = $fecha." 00:00:00";
	$fecha_hasta = $fecha." 23:59:59";
	
	if($accion == "consultar_mensual_general_socio")
	{
		include '../presentacion/rpt_mensual_general.php';
	}

	if($accion == "consultar_mensual_general_socio_fechaInicio_fechaFin")
	{
		include '../presentacion/rpt_mensual_general.php';
	}

	if($accion == "consultar_mensual_general_socio_jva")
	{
		include '../presentacion/rpt_mensual_general.php';
	}

	if($accion == "consultar_mensual_general_socio_jva_fechaInicio_fechaFin")
	{
		include '../presentacion/rpt_mensual_general.php';
	}

	if($accion == "consultar_mensual_general_socio_jva_vendedor")
	{
		include '../presentacion/rpt_mensual_general.php';
	}

	if($accion == "consultar_mensual_general_socio_jva_vendedor_fechaInicio_fechaFin")
	{
		include '../presentacion/rpt_mensual_general.php';
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
