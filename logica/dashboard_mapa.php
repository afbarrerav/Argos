<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
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
		include '../presentacion/dashboard_mapa_detalle.php';
	}
	if($accion == "mostrar_ruta")
	{
		$ven_codigo = $_REQUEST['VEN_CODIGO'];
		$date 		= $_REQUEST['FECHA'];
		$tipo_mapa	= $_REQUEST['TIPO'];
		$fecha 		= date('Ymd', strtotime($date)); 
		//echo $fecha;
		//$ven_codigo = 1014240070;
		/*
		 * VALIDAMOS EL TIPO DE MAPA A MOSTRAR VENTAS O RECAUDOS
		 * */
		if ($tipo_mapa=="Ventas")
		{
			/*
			 * CONSULTAMOS LA VENTAS REALIZADAS POR EL  VENDEDOR DEL JVA
			 * */
			$query =	"SELECT tv.codigo, tv.latitud, tv.longitud, CONCAT(au.nombres,' ',au.apellidos) as nombre
							FROM trans_ventas tv, admin_usuarios au, admin_jva_usuarios aju
							WHERE tv.aju_codigo = '$ven_codigo'
							AND tv.latitud <> 'NULL'
							AND tv.longitud <> 'NULL'
							AND tv.aju_codigo = aju.codigo
							AND aju.usu_codigo = au.codigo
							AND tv.fecha_entrega like '%$date%'";
			/*
			 * SE PREPARA EL QUERY
			 * */
			//echo $query;
			$result = $db_link->prepare($query);
			$db_link->beginTransaction();
			//$result->bindParam(':ven_codigo',$ven_codigo);
			$result->execute(); //SE EJECUTA EL QUERY
			$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
			$error = $arr[0];
			$errorMessage = str_replace("'", "", $arr[2]);
			/*
			 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
			 * */
			if($error=="00000")
			{
				/*HACE EL LLAMADO A LA OPCION obtener_solicitud*/
				$RowCount=0;
				while($row = $result->fetch(PDO::FETCH_ASSOC))
				{
					$detalle['codigo'][$RowCount]		= $row['codigo'];
					$detalle['latitud'][$RowCount]		= $row['latitud'];
					$detalle['longitud'][$RowCount]		= $row['longitud'];
					$detalle['nombre'][$RowCount]		= $row['nombre'];
					$RowCount++; 
				}
				$db_link->commit();	
			}
			else
			{
				?>
				<script>
					alert("Error al realizar la consulta: <?php echo $errorMessage?>");
				</script>
				<?php
				$db_link->rollBack();
			}
			/*HACE EL LLAMADO AL ARCHIVO DE PRESENTACION*/
			if ($RowCount>0)
			{
				include('../presentacion/dashboard_mapa.php');
			}
			else
			{
				echo "No hay datos suficientes para graficar";
			}
			
		}
		else
		{
			/*
			 * CONSULTAMOS LOS RECAUDOS HECHOS POR EL VENDEDOR DEL JVA
			 * */
			$query =	"SELECT tv.codigo, tv.latitud, tv.longitud, CONCAT(au.nombres,' ',au.apellidos) as nombre
							FROM trans_detalle_recaudo_ventas_jva tv, admin_usuarios au, admin_jva_usuarios aju
							WHERE tv.aju_codigo = '$ven_codigo'
							AND tv.latitud <> 'NULL'
							AND tv.longitud <> 'NULL'
							AND tv.aju_codigo = aju.codigo
							AND aju.usu_codigo = au.codigo
							AND tv.fecha_trans like '%$date%'";
			/*
			 * SE PREPARA EL QUERY
			 * */
			//echo $query;
			$result = $db_link->prepare($query);
			$db_link->beginTransaction();
			//$result->bindParam(':ven_codigo',$ven_codigo);
			$result->execute(); //SE EJECUTA EL QUERY
			$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
			$error = $arr[0];
			$errorMessage = str_replace("'", "", $arr[2]);
			/*
			 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
			 * */
			if($error=="00000")
			{
				/*HACE EL LLAMADO A LA OPCION obtener_solicitud*/
				$RowCount=0;
				while($row = $result->fetch(PDO::FETCH_ASSOC))
				{
					$detalle['codigo'][$RowCount]		= $row['codigo'];
					$detalle['latitud'][$RowCount]		= $row['latitud'];
					$detalle['longitud'][$RowCount]		= $row['longitud'];
					$detalle['nombre'][$RowCount]		= $row['nombre'];
					$RowCount++; 
				}
				$db_link->commit();	
			}
			else
			{
				?>
				<script>
					alert("Error al realizar la consulta: <?php echo $errorMessage?>");
				</script>
				<?php
				$db_link->rollBack();
			}
			/*HACE EL LLAMADO AL ARCHIVO DE PRESENTACION*/
			if ($RowCount>0)
			{
				include('../presentacion/dashboard_mapa.php');
			}
			else
			{
				echo "No hay datos suficientes para graficar";
			}
		}
		
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