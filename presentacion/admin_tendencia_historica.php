<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Diciembre de 2012
 * 
 * */
$DATABASE_NAME 	= "argos_bd2plataforma";
$dsn 			= "mysql:host=localhost;dbname=$DATABASE_NAME";
try
{
	/*SE CREA LA INSTANCIA DEL OBJETO, SE REALIZA LA CONEXION A LA BD*/
	$db_link = new PDO($dsn, 'fmojica', 'FA84ma0616');
	$accion = $_REQUEST['ACCION'];
	/*REALIZAR EL PROCESO PARA GUARDAR LA INFORMACION*/
	if ($accion=="tendencia_historica")
	{
   		// archivos incluidos. Librerías PHP para poder graficar.
		include "FusionCharts.php";
		include "Functions.php";
		// Gráfico de Barras. 4 Variables, 4 barras.
		// Estas variables serán usadas para representar los valores de cada unas de las 4 barras.
		// Inicializo las variables a utilizar.
		$intTotalAnio1 = 100;
		$intTotalAnio2 = 150;
		$intTotalAnio3 = 30;
		$intTotalAnio4 = 500;
		$intTotalAnio5 = 250;
		$intTotalAnio6 = 5;
		$intTotalAnio7 = 3;
		//echo $intTotalAnio1." - ".$intTotalAnio2." - ".$intTotalAnio3." - ".$intTotalAnio4	
		//echo $grafica['dia'][0]." - ".$grafica['dia'][1]." - ".$grafica['dia'][2]." - ".$grafica['dia'][3];
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
		$strXML .= "<set label = 'Lunes' value ='".$intTotalAnio1."' color = 'EA1000' />";
		$strXML .= "<set label = 'Martes' value ='".$intTotalAnio2."' color = '6D8D16' />";
		$strXML .= "<set label = 'Miercoles' value ='".$intTotalAnio3."' color = 'FFBA00' />";
		$strXML .= "<set label = 'Jueves' value ='".$intTotalAnio4."' color = '0000FF' />";
		$strXML .= "<set label = 'Viernes' value ='".$intTotalAnio5."' color = 'D05858' />";
		$strXML .= "<set label = 'Sabado' value ='".$intTotalAnio6."' color = 'AFB9F4' />";
		$strXML .= "<set label = 'Domingo' value ='".$intTotalAnio7."' color = '838343' />";
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
    	echo renderChartHTML("Column3D.swf", "",$strXML, "Vendido y proyectado.", 300, 151, false);
    	echo "<br>";
	}
}
catch (PDOException $e)
{
	$msg = $e->getMessage();
	echo $msg;
}
?>




