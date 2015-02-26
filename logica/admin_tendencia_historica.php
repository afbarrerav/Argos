<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
//session_start();
include_once ("../logica/variables_session.php");
// archivos incluidos. Librerías PHP para poder graficar.
include "FusionCharts.php";
include "Functions.php";
$RowCount = 0;
try
{
	/*SE CREA LA INSTANCIA DEL OBJETO, SE REALIZA LA CONEXION A LA BD*/
	$db_link = new PDO($dsn, $username, $passwd);
	$accion = $_REQUEST['ACCION'];
	$msg="";
	setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
	$fecha_actual = date ('Y-m-d');
	$color = array("EA1000","6D8D16","FFBA00","0000FF","D05858","AFB9F4","838343","EA1000","6D8D16","FFBA00","0000FF","D05858","AFB9F4","838343","EA1000","6D8D16","FFBA00","0000FF","D05858","AFB9F4","838343");
	$cantidad_dias = 7;
	/*
	 * CONSTRUYE EL ARREGLO QUE ALMACENA LA INFORMACION NECESARIA CONSTRUIR LA GRAFICA
	 */
	for($i=0;$i<=$cantidad_dias;$i++)
	{
		$arreglo_grafica['nombre'][$i]	= htmlentities(strftime("%A %d %b", strtotime("$fecha_actual -$i day")));
		$arreglo_grafica['fecha'][$i]	= date("Y-m-d", strtotime("$fecha_actual -$i day"));
		$arreglo_grafica['valor'][$i]	= 0;
	}	
	/*
	 * OBTIENE LA INFORMACION PARA GENERAR EL REPORTE DE RECAUDOS DE LOS ULTIMOS 7 DIAS
	 */
	if ($accion=="recaudos")
	{
		$jva_codigo_reporte = $_REQUEST['JVA_CODIGO'];
		if($jva_codigo_reporte != 0)
		{
			$condicion = "WHERE aju_codigo in (select codigo from admin_jva_usuarios where jva_codigo = $jva_codigo_reporte)";
		}
		else
		{
			$condicion = "WHERE aju_codigo in (select codigo from admin_jva_usuarios where jva_codigo in (select jva_codigo from admin_jva_usuarios where  usu_codigo = $usu_codigo))";
		}	
		/*
		 * CONTRUYE EL QUERY QUE OBTIENE EL VALOR DEL RECAUDO PARA CADA DIA
		 */
		for($i=0;$i<=$cantidad_dias;$i++)
		{
			$query = "SELECT IFNULL((select sum(valor_recaudo)
									from trans_historico_recaudo_ventas_jva 
									where tv_codigo in (
														SELECT tv_codigo
														FROM trans_rutas_detalles
														$condicion)
									AND fecha_recaudo between '". $arreglo_grafica['fecha'][$i] ." 00:00:00' and '". $arreglo_grafica['fecha'][$i]. " 23:59:59'
									), 0) valor";
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
				$row = $result->fetch(PDO::FETCH_ASSOC);
				
				/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
				$arreglo_grafica['valor'][$i] = $row['valor'];
			}
			else
			{
				?>
				<script>
					alert("Error al consultar el valor: <?php echo $errorMessage?>");
				</script>
				<?php
			}
		}
	}
	/*
	 * OBTIENE LA INFORMACION PARA GENERAR EL REPORTE DE SALDOS DE LOS ULTIMOS 7 DIAS
	 */
	if ($accion=="recaudos_ventas")
	{
				   		
		
	}
	/*
	 * OBTIENE LA INFORMACION PARA GENERAR EL REPORTE DE VENTAS DE LOS ULTIMOS 7 DIAS
	 */
	if ($accion=="gastos")
	{
		$jva_codigo_reporte = $_REQUEST['JVA_CODIGO'];
		if($jva_codigo_reporte != 0)
		{
			$condicion = "WHERE jva_codigo = $jva_codigo_reporte";
		}
		else
		{
			$condicion = "WHERE jva_codigo in (select jva_codigo from admin_jva_usuarios where  usu_codigo = $usu_codigo)";
		}	
		/*
		 * CONTRUYE EL QUERY QUE OBTIENE EL VALOR DEL RECAUDO PARA CADA DIA
		 */
		for($i=0;$i<=$cantidad_dias;$i++)
		{
			$query = "SELECT IFNULL((select sum(valor)
									from trans_gastos_jva 
									where aju_codigo in (
														SELECT codigo
														FROM admin_jva_usuarios
														$condicion)
									AND fecha_gasto = '". $arreglo_grafica['fecha'][$i] ."'
									), 0) valor";
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
				$row = $result->fetch(PDO::FETCH_ASSOC);
				
				/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
				$arreglo_grafica['valor'][$i] = $row['valor'];
			}
			else
			{
				?>
				<script>
					alert("Error al consultar el valor: <?php echo $errorMessage?>");
				</script>
				<?php
			}
		}			   				
	}
	/*
	 * OBTIENE LA INFORMACION PARA GENERAR EL REPORTE DE GASTOS DE LOS ULTIMOS 7 DIAS
	 */
	if ($accion=="saldos")
	{
				   		
		
	}

	/*
	 * OBTIENE LA INFORMACION PARA GENERAR EL REPORTE DE GASTOS DE LOS ULTIMOS 7 DIAS
	 */
	if ($accion=="ventas")
	{
		$jva_codigo_reporte = $_REQUEST['JVA_CODIGO'];
		if($jva_codigo_reporte != 0)
		{
			$condicion = "WHERE aju_codigo in (select codigo from admin_jva_usuarios where jva_codigo = $jva_codigo_reporte)";
		}
		else
		{
			$condicion = "WHERE aju_codigo in (select codigo from admin_jva_usuarios where jva_codigo in (select jva_codigo from admin_jva_usuarios where  usu_codigo = $usu_codigo))";
		}	
		/*
		 * CONTRUYE EL QUERY QUE OBTIENE EL VALOR DEL RECAUDO PARA CADA DIA
		 */
		for($i=0;$i<=$cantidad_dias;$i++)
		{
			$query = "SELECT IFNULL((select sum(valor_producto)
									from trans_ventas 
									where codigo in (
														SELECT tv_codigo
														FROM trans_rutas_detalles
														$condicion)
									AND fecha_entrega between '". $arreglo_grafica['fecha'][$i] ." 00:00:00' and '". $arreglo_grafica['fecha'][$i]. " 23:59:59'
									), 0) valor";
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
				$row = $result->fetch(PDO::FETCH_ASSOC);
				
				/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
				$arreglo_grafica['valor'][$i] = $row['valor'];
			}
			else
			{
				?>
				<script>
					alert("Error al consultar el valor: <?php echo $errorMessage?>");
				</script>
				<?php
			}
		}		   				
	}
	/*
	 * IMPRIME EL GRAFICO
	 */
	// $strXML: Para concatenar los parámetros finales para el gráfico.
	$strXML = "";
	// Armo los parámetros para el gráfico. Todos estos datos se concatenan en una variable.
	// Encabezado de la variable XML. Comienza con la etiqueta "Chart".
	// caption: define el título del gráfico.
	// bgColor: define el color de fondo que tendrá el gráfico.
	// baseFontSize: Tamaño de la fuente que se usará en el gráfico.
	// showValues: = 1 indica que se mostrarán los valores de cada barra. = 0 No mostrará los valores en el gráfico.
	// xAxisName: define el texto que irá sobre el eje X. Abajo del gráfico. También está xAxisName.
	$strXML = "<chart caption = '' bgColor='#FFFFFF' baseFontSize='12' showValues='1' xAxisName='' align='center'>";
	// Armado de cada barra.
	// set label: asigno el nombre de cada barra.
	// value: asigno el valor para cada barra.
	// color: color que tendrá cada barra. Si no lo defino, tomará colores por defecto.
	for($i=$cantidad_dias;$i>=0;$i--)
	{
		$strXML .= "<set label = '".$arreglo_grafica['nombre'][$i]."' value ='".$arreglo_grafica['valor'][$i]."' color = '".$color[$i]."' />";
	}		
	//echo $intTotalAnio1." - ".$intTotalAnio2." - ".$intTotalAnio3." - ".$intTotalAnio4;
	//Cerramos la etiqueta "chart".
	$strXML .= "</chart>";
	// Por último imprimo el gráfico.
	// renderChartHTML: función que se encuentra en el archivo FusionCharts.php
	// Envía varios parámetros.
	// 1er parámetro: indica la ruta y nombre del archivo "swf" que contiene el gráfico. En este caso Columnas ( barras) 3D
	// 2do parámetro: indica el archivo "xml" a usarse para graficar. En este caso queda vacío "", ya que los parámetros lo pasamos por PHP.
	// 3er parámetro: $strXML, es el archivo parámetro para el gráfico. 
	// 4to parámetro: "ejemplo". Es el identificador del gráfico. Puede ser cualquier nombre.
	// 5to y 6to parámetro: indica ancho y alto que tendrá el gráfico.
	// 7mo parámetro: "false". Trata del "modo debug". No es im,portante en nuestro caso, pero pueden ponerlo a true ara probarlo.
	/*
	 * CONSULTA
	 * */	 
	/*HACE EL LLAMADO AL ARCHIVO DE PRESENTACION*/
	//include('../presentacion/reportes.php');
	echo renderChartHTML("Column3D.swf", "",$strXML, "Vendido y proyectado.", 600, 300, false);
	//echo "<br>";
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