<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Marzo de 2011
 * 
 * */
//COMIENZA UNA NUEVA SESION
session_start();

/* 
 * session_register(mixed$name[mixed$...])
 * REGISTRA UNA O MAS VARIABLES GLOBALES CON LA SESION ACTUAL
 * La variable de sesin 'idioma_s' es la encargada de almacenar el idioma en el que estamos.
*/
if($_GET['lg'])
{
 /*
  *  ARRAY ASOCIATIVO QUE CONTIENE VARIABLES DE SESION $HTTP_SESSION_VARS['...']
  *  SE LE ASIGNA EL VALOR DE $_GET['lg'] (EL IDIOMA) A LA VARIABLE DE SESION 
 */
     $_SESSION['LG'] = $_GET['lg'];
}

// AHORA $language CONTIENE EL IDIOMA
$lg = $_SESSION['LG'];

//HACE LA TRADUCCION DEPENDIENDO DEL IDIOMA SELECCIONADO
if($lg == "ES" || $lg == "pt_BR")
{
	include('../logica/diccionario.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>..:: Argos - POS ::..</title>
<link rel="shortcut icon" href="imagenes/bbilden.png" /> 
<link href="../css/estilo.css" rel="stylesheet" type="text/css"/>
<link href="../css/nav-h.css" rel="stylesheet" type="text/css"/>
<link type="text/css" href="../css/start/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="../js/header.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&amp;language=es"></script>  
<!--[if gte IE 5.5]>
<script type="text/javascript" src="../js/nav-h.js"></script>
<![endif]-->
</head>


<body class="thrColElsHdr">
	<div id="header">
		<table width="960px" align="center">
			<tr>				
				<td width = "20%" align="left"><img vspace="15" src="imagenes/logo.png" width="125"/></td>
			  	<td width = "80%" align="right" valign="top">
			  		<div id="OpcionesUsuario">
			  			<a href="index.php?lg=ES"><img border="0" src="imagenes/esp.gif"/></a>&nbsp;
						<a href="index.php?lg=pt_BR"><img border="0" src="imagenes/bra.gif"/></a>
					</div>
				</td>
			</tr>
		</table>	
	</div><br />
	<div id="container">
		<div id="mainContent">
		</div>
		<script>
		AjaxConsulta( '../presentacion/frm_login.php', '', 'mainContent' );
		</script>
	</div>
	<div id="mostrar_mapa" class="mapa" style="display:none"></div></br>
    <div id="footer" align="center">
		 <br /><br /><br />
			    <span class="footertexto"> Todos los derechos reservados Bogot&aacute; - Colombia</span><br/><a href="http://www.bolcolombia.com" target="_blank">Developed by BOL Colombia S.A.S</a><br />
		      <a href="http://www.bolcolombia.com" target="_blank"><img src="imagenes/logobol.png" width="100" hspace="5" vspace="10" border="0"/></a> <br /><br />
		</div>
</body>
</html>
