<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 * 
 * */
session_start();
$username		= $_SESSION['USERNAME'];
$aju_codigo		= $_SESSION['AJU_CODIGO'];
$jva_codigo		= $_SESSION['JVA_CODIGO']; 
$usu_codigo		= $_SESSION['USU_CODIGO'];
$ide_usuario	= $_SESSION['IDE_USUARIO'];
$nom_usuario	= $_SESSION['NOM_USUARIO'];
$rol 			= $_SESSION['ROL_USUARIO'];
$rol_codigo		= $_SESSION['ROL_CODIGO'];
$ultimo_acceso	= $_SESSION['ULTIMO_ACCESO'];
$dsn 			= $_SESSION['DSN'];
$passwd			= $_SESSION['PASSWD'];
$lg				= $_SESSION['LG'];
$DATABASE_NAME 	= "argos_bd2plataforma";

?>
