<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Marzo de 2011
 * 
 * */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>..:: Bilden ::..</title>
<link rel="shortcut icon" href="imagenes/bbilden.png" /> 
<link href="../css/estilo.css" rel="stylesheet" type="text/css"/>
<link href="../css/nav-h.css" rel="stylesheet" type="text/css"/>
<link type="text/css" href="../css/start/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="../js/header.js"></script>
<!--[if gte IE 5.5]>
<script type="text/javascript" src="../js/nav-h.js"></script>
<![endif]-->
</head>


<body class="thrColElsHdr">
	<div id="header">
		<table width="960px" align="center">
			<tr>				
				<td width = "20%" align="left"></td>
				<td width = "80%" align="right" rowspan="2"><div id="OpcionesUsuario"></div></td>
			</tr>
			<tr>				
				<td style="Color: white; text-align: left; font-family: tahoma; font-weight: bold; font-size:10px;">Auditoria automatica</td>
			</tr>
		</table>	
	</div>
	<div id="container">
		<div id="mainContent">
		</div>
		<script>
		AjaxConsulta( '../presentacion/auditoria.php', '', 'mainContent' );
		</script>
		<div id="footer" align="center">
		<table width="960 px">
			<tr>
				<td width = "30%" align="center">Todos los derechos reservados Bogot&aacute; - Colombia<br/><a href="http://www.erc.com.co" target="_blank">Developed by ERC Colombia S.A.S</a></td>
			</tr>
		</table> 
	</div>
	</div>		
</body>
</html>
