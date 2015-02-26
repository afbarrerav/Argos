<?php
//ini_set('max_execution_time', '-1'); 
//ini_set('mysql.connect_timeout', '-1'); 
//ini_set('max_execution_time', 300);
error_reporting(0);
//echo "*". get_cfg_var('safe_mode');
//set_time_limit(0);

$db_database = "argos_bd2plataforma";
//$db_username = "root";
//$db_password = "m3gum100w0lk3";
$db_server="localhost";
$datos_enviados = obtener_dato("datos_enviados");
if (!$datos_enviados){
	$datos_enviados="";
}
$mensaje="";
$mostrar_formulario = true;
$hay_error_validacion = false;

function db_existe_registro($query,$db_username,$db_password){
if (strtolower(substr($query,0,6)) == "select"){
		global $db_server;
		global $db_database;
		
		echo $query;
		echo $db_username;
		echo $db_password;
		
		$db=mysql_connect($db_server, $db_username, $db_password);
		mysql_select_db($db_database,$db);
		$resultado = mysql_query($query,$db);
		if ($resultado){
			if (mysql_num_rows($resultado)){
				return true;
			}else{
				return false;
			}
			mysql_free_result($resultado);
		}else{
			db_error(mysql_error());
		}
		mysql_close($db);
	}
}


function db_ejecutar_instruccion($query){
	if (strtolower(substr($query,0,6)) != "select"){
		$query = str_replace("\"","&quot;",$query);
		global $db_server;
		global $db_database;
		$db=mysql_connect($db_server, $db_username, $db_password);
		mysql_select_db($db_database,$db);
		$resultado = mysql_query($query,$db);
		if ($resultado){
			return mysql_affected_rows();
			mysql_free_result($resultado);
		}else{
			db_error(mysql_error());
		}
		mysql_close($db);
	}
}

function db_ejecutar_consulta($query){
	if (strtolower(substr($query,0,6)) == "select"){
		global $db_server;
		global $db_database;
		global $db_cantidad_registros;
		$db=mysql_connect($db_server, $db_username, $db_password);
		mysql_select_db($db_database,$db);
		$resultado = mysql_query($query,$db);
		if ($resultado){
			if (mysql_num_rows($resultado)>0){
				while ($fila = mysql_fetch_row($resultado)){
					$retorno[] = $fila;
				}
				return $retorno;
			} else {
				return false;
			}
			mysql_free_result($resultado);
		}else{
			db_error(mysql_error());
		}
		mysql_close($db);
	}

}
function db_buscar_valor($query){
	if (strtolower(substr($query,0,6)) == "select"){
		global $db_server;
		global $db_database;
		$db=mysql_connect($db_server, $db_username, $db_password);
		mysql_select_db($db_database,$db);
		$resultado = mysql_query($query,$db);
		if ($resultado){
			if (mysql_num_rows($resultado) == 1 and mysql_num_fields($resultado) == 1){
				$retorno= mysql_fetch_row($resultado);
				return $retorno[0];
			} else {
				return false;
			}
			mysql_free_result($resultado);
		}else{
			db_error(mysql_error());
		}
		mysql_close($db);
	}
}

function db_obtener_vector($query){
	$matriz_datos = db_ejecutar_consulta($query);
	if ($matriz_datos){
		for ($loop_vector=0;$loop_vector<sizeof($matriz_datos);$loop_vector++){
			$vector_retorno[] = $matriz_datos[$loop_vector][0];
		}
		return $vector_retorno;
	} else {
		return false;
	}
}

function db_obtener_vector_horizontal($query){
	$matriz_datos = db_ejecutar_consulta($query);
	for ($loop_vector=0;$loop_vector<sizeof($matriz_datos[0]);$loop_vector++){
		$vector_retorno[] = $matriz_datos[0][$loop_vector];
	}
	return $vector_retorno;
}

function db_error($mensaje_error){
	if ($mensaje_error){
		echo "<br><font color=red><b>Error:&nbsp;$mensaje_error</b></font><br>";
	}
}

################################## FUNCIONES BÁSICAS DE VALIDACÍÓN ##############################

function es_entero($dato){
	$hay_error = false;
	if (strval($dato) == strval(doubleval($dato))){
		if (substr_count($dato,".") > 0){
			return False;
		} else {
			return True;
		}
	}else{
		return False;
	}
}

function es_decimal($dato){
	$hay_error = false;
	if (strval($dato) == strval(doubleval($dato))){
		return True;
	}else{
		return False;
	}
}

function es_correo($dato){
	$hay_error = false;
	if ((strlen($dato)==0)or(strpos($dato,"@")<2)or((strpos($dato,"@"))>((strlen($dato))-7))or(strchr ($dato,"@")===false)or(strlen((strrchr($dato,".")))<3)or(strchr ($dato,".")===false)or(strchr($dato,"/"))or(strchr($dato,"*"))or(strchr($dato,"+"))or(strchr($dato,";"))){
		return False;
	}else{
		return True;
	}
}

function es_fecha($dato){
	$hay_error = false;
	$fecha = split("/",$dato);
	if (sizeof($fecha)==3){
		if (!checkdate($fecha[1],$fecha[2],$fecha[0])){
			return False;
		} else {
			return True;
		}
	}else{
		return False;
	}
}

####################### FUNCIONES DE OBTENCIÓN VALIDACIÓN DE DATOS  #############################


function verificar_seguridad(){
	
	$info=pathinfo($_SERVER['SCRIPT_NAME']);
	$pagina=$info['basename'];
	if ($_COOKIE['usuario_id']){
		$query = "select archivo_funcion_id from seg_archivos_funciones where upper(nombre_archivo) = upper('" . $pagina . "')";
//		$archivo_id = db_buscar_valor($query);
		$archivos = db_ejecutar_consulta($query);
//		if ($archivo_id){
//	print_r($archivos);
//	exit;
		$cadena_permisos='';
		if (is_array($archivos)){
			for ($loop_vector=0;$loop_vector<sizeof($archivos);$loop_vector++){
				$archivo_id = $archivos[$loop_vector][0];
				if (tiene_permisos_seguridad($archivo_id)){
					$cadena_permisos=$cadena_permisos.=$archivo_id;
				}
			}
			
//			echo $cadena_permisos;
//			exit;
			if ($cadena_permisos===''){
				echo "<script language=javascript>top.location='index.php?error=S'</script>";
			}
		} else {
				echo "<script language=javascript>top.location='index.php?error=S'</script>";
		}
	} else {

				echo "<script language=javascript>top.location='index.php?error=S'</script>";
	}

}

/*function verificar_seguridad(){
	global $usuario_id;
	if ($usuario_id!="9510608"){
		redireccionar_pagina("login.php");
	}
}
*/

function tiene_permisos_seguridad($archivo_funcion_id){
		if ($archivo_funcion_id){
		 $query = "select a.usu_id from seg_perfiles_usuarios a join seg_funciones_perfiles b on a.per_id=b.per_id join seg_archivos_funciones c  on b.fun_id=c.funcion_id where a.usu_id = " . $_COOKIE['usuario_id']. " and c.archivo_funcion_id = " . $archivo_funcion_id;
		if (db_existe_registro($query)){
			return True;
		} else {
			return False;
		}
	}
}


function obtener_dato($nombre_variable,$mayusculas="N"){
	//global $HTTP_POST_VARS;
	//global $HTTP_GET_VARS;
	$variable = "";
	if (isset($_POST[$nombre_variable])){
		$variable = $_POST[$nombre_variable];
	} else {
		if (isset($_GET[$nombre_variable])){
			$variable = $_GET[$nombre_variable];
		}
	}
	if ($mayusculas=="N"){
		return $variable;
	} else {
		return strtoupper($variable);
	}
}

function error_validacion($campo,$mensaje_error){
	global $matriz_errores;
	global $hay_error_validacion;
	if ($mensaje_error){
		$matriz_errores[$campo] = $mensaje_error;
		$hay_error_validacion = true;
	} else {
		$matriz_errores[$campo] = "ok";
	}
}

function es_administrador($usuario_id){
	$query="select per_id from seg_perfiles_usuarios where	usu_id='$usuario_id'";
	$matriz=db_ejecutar_consulta($query);
	for($i=0;$i<sizeof($matriz);$i++){
		if ($matriz[$i][0]=='1'){
			return true;
		}
	}
}
function es_administrador_gh($usuario_id){
	$query="select per_id from seg_perfiles_usuarios where	usu_id='$usuario_id'";
	$matriz=db_ejecutar_consulta($query);
	for($i=0;$i<sizeof($matriz);$i++){
		if ($matriz[$i][0]=='35'){
			return true;
		}
	}
}

function es_jefe_gh($usuario_id){
	$query="select per_id from seg_perfiles_usuarios where	usu_id='$usuario_id'";
	$matriz=db_ejecutar_consulta($query);
	for($i=0;$i<sizeof($matriz);$i++){
		if ($matriz[$i][0]=='38'){
			return true;
		}
	}
}


function es_coordinador($usuario_id){
	$query="select per_id from seg_perfiles_usuarios where	usu_id='$usuario_id'";
	$matriz=db_ejecutar_consulta($query);
	for($i=0;$i<sizeof($matriz);$i++){
		if ($matriz[$i][0]=='20'){
			return true;
		}
	}
}

function es_adm_inventario($usuario_id){
	$query="select per_id from seg_perfiles_usuarios where	usu_id='$usuario_id'";
	$matriz=db_ejecutar_consulta($query);
	for($i=0;$i<sizeof($matriz);$i++){
		if ($matriz[$i][0]=='21'){
			return true;
		}
	}
}

function es_pim($usuario_id){
	$query="select per_id from seg_perfiles_usuarios where	usu_id='$usuario_id'";
	$matriz=db_ejecutar_consulta($query);
	for($i=0;$i<sizeof($matriz);$i++){
		if ($matriz[$i][0]=='19'){
			return true;
		}
	}
}


function visita_vencida($visita_id){
	$query="select fecha_fin  from ot_visitas where	visitas_id='$visita_id'";
	$matriz=db_ejecutar_consulta($query);
	$fecha_fin=$matriz[0][0];
	$hoy=date('Y-m-d H:i:s');
	$fecha_fin=strtotime($fecha_fin);	
	$hoy=strtotime($hoy);
	$delta=$hoy-$fecha_fin;
	$final=$delta/172800;
	if($final>1){
		return true;
	}else{
		return false;
	}
}
function validar_texto($campo,$mensaje_error,$long_minima,$long_maxima = 0,$mayusculas="S"){
	$dato = obtener_dato($campo,$mayusculas);
	$hay_error = false;
	if ($long_minima){
		if (strlen($dato)<$long_minima){
			$hay_error = true;
		}
	}
	if ($long_maxima){
		if (strlen($dato)>$long_maxima){
			$hay_error = true;
		}
	}
	if ($hay_error){
		error_validacion($campo,$mensaje_error);
	} else {
		error_validacion($campo,"");
	}
	return $dato;
}

function validar_correo($campo,$mensaje_error){
	$dato = obtener_dato($campo);
	$hay_error = false;
	if (!(es_correo($dato))){
		$hay_error = true;
	}
	if ($hay_error){
		error_validacion($campo,$mensaje_error);
	} else {
		error_validacion($campo,"");
	}
	return $dato;
}


function validar_numero($campo,$mensaje_error,$val_minimo="",$val_maximo=""){
	$dato = obtener_dato($campo);
	$hay_error = false;
	if (es_decimal($dato)){
			if (es_entero($val_minimo)){
				if ($dato < $val_minimo){
					$hay_error=true;
				}
			}
			if (es_entero($val_maximo)){
				if ($dato > $val_maximo){
					$hay_error=true;
				}
			}
	} else {
		$hay_error = true;
	}
	if ($hay_error){
		error_validacion($campo,$mensaje_error);
	} else {
		error_validacion($campo,"");
	}
	return $dato;
}

function validar_entero($campo,$mensaje_error,$val_minimo="",$val_maximo=""){
	$dato = obtener_dato($campo);
	$hay_error = false;
	if (es_entero($dato)){
			if (es_entero($val_minimo)){
				if ($dato < $val_minimo){
					$hay_error=true;
				}
			}
			if (es_entero($val_maximo)){
				if ($dato > $val_maximo){
					$hay_error=true;
				}
			}
	} else {
		$hay_error = true;
	}
	if ($hay_error){
		error_validacion($campo,$mensaje_error);
	} else {
		error_validacion($campo,"");
	}
	return $dato;
}


function validar_fecha($campo,$mensaje_error,$fecha_min="",$fecha_max=""){
	$dato = obtener_dato($campo);
	$hay_error = false;
	if (es_fecha($dato)){
		$fecha = split("/",$dato);
		if (es_fecha($fecha_min)){
			$fecha_min = split("/",$fecha_min);
			if (gmmktime(0,0,0,$fecha[1],$fecha[2],$fecha[0]) < gmmktime(0,0,0,$fecha_min[1],$fecha_min[2],$fecha_min[0])){
				$hay_error = true;
			}
		}
		if (es_fecha($fecha_max)){
			$fecha_max = split("/",$fecha_max);
			if (gmmktime(0,0,0,$fecha[1],$fecha[2],$fecha[0]) > gmmktime(0,0,0,$fecha_max[1],$fecha_max[2],$fecha_max[0])){
				$hay_error = true;
			}
		}
	} else {
		$hay_error = true;
	}
	if ($hay_error){
		error_validacion($campo,$mensaje_error);
	} else {
		error_validacion($campo,"");
	}
	return $dato;
}

function obtener_casillas_de_chequeo($campo){
	$cantidad = $campo . "_cantidad";
	global $$cantidad;
	$cantidad = $$cantidad;
	global $$campo;
	$vector_respuesta = Array();
	for ($loop_casillas=0;$loop_casillas<$cantidad;$loop_casillas++){
		$casilla = $campo . "_" . $loop_casillas;
		global $$casilla;
		if ($$casilla){
			$vector_respuesta[] = $$casilla;
		}
	}
	return $vector_respuesta;
}
#################################### CAMPOS DE FORMULARIO #######################################

function campo_de_texto_noedit($nombre,$tamano=10,$tamano_maximo=15){
	global $$nombre;
	$valor = $$nombre;
	if (!es_entero($tamano_maximo)){
		$tamano_maximo = "";
	} else {
		$tamano_maximo = " maxlength=\"" . $tamano_maximo . "\"";
	}
	echo "<input type=\"text\" id=\"" . $nombre . "\" class=\"cuadrotexto\" size=" . $tamano . $tamano_maximo . " name=\"" . $nombre . "\" value=\"" . $valor . "\" disabled>";
}


function campo_de_texto($nombre,$tamano=10,$tamano_maximo=15){
	global $$nombre;
	$valor = $$nombre;
	if (!es_entero($tamano_maximo)){
		$tamano_maximo = "";
	} else {
		$tamano_maximo = " maxlength=\"" . $tamano_maximo . "\"";
	}
	echo "<input type=\"text\" id=\"" . $nombre . "\" class=\"cuadrotexto\" size=" . $tamano . $tamano_maximo . " name=\"" . $nombre . "\" value=\"" . $valor . "\">";
}

function campo_de_password($nombre,$tamano=10,$tamano_maximo=15){
	echo "<input type = \"password\" size=\"" . $tamano . "\" maxlength=\"" . $tamano_maximo . "\" class=\"cuadrotexto\" name =\"" . $nombre . "\">";
}

function campo_de_fecha($nombre){
	global $$nombre;
	$valor = $$nombre;
	echo "<input type = \"text\" size=10 class=\"cuadrotexto\" name =\"" . $nombre . "\" value=\"" . $valor . "\"> <small>(aaaa/mm/dd)</small>";
}

function area_de_texto($nombre,$columnas=20,$filas=3,$wrap="S"){
	global $$nombre;
	$valor = $$nombre;
	if ($wrap=="H"){
		$wrap = "hard";
	} else {
		$wrap = "soft";
	}
	echo "<textarea class=\"cuadrotexto\" cols=" . $columnas . " rows=" . $filas . " name =\"" . $nombre . "\" wrap=" . $wrap . ">" . $valor . "</textarea>";
}

function lista_desplegable($nombre,$matriz_valores,$onaction=""){
	if (is_array($matriz_valores)){
		global $$nombre;
		$seleccionado = $$nombre;
		echo "<select name=\"$nombre\" $onaction size=1>";
		if (!$seleccionado){
			echo "<option value=\"\" selected>[--Seleccione--]</option>";
		} else {
			echo "<option value=\"\">[--Seleccione--]</option>";
		}
		for ($loop_array = 0;$loop_array<sizeof($matriz_valores);$loop_array++){
			if ($matriz_valores[$loop_array][0]==$seleccionado){
				$str_seleccionado = "selected";
			}else{
				$str_seleccionado = "";
			}
			echo "<option value=\"" . $matriz_valores[$loop_array][0] ."\" $str_seleccionado>" . $matriz_valores[$loop_array][1];
		}
		echo "</select>";
	}
}
function lista_desplegable2($nombre,$matriz_valores,$onaction=""){
	if (is_array($matriz_valores)){
		global $$nombre;
		$seleccionado = $$nombre;
		echo "<select name=\"$nombre\" $onaction size=1>";
		if (!$seleccionado){
			echo "<option value=\"\" selected>[--Principal--]</option>";
		} else {
			echo "<option value=\"\">[--Principal--]</option>";
		}
		for ($loop_array = 0;$loop_array<sizeof($matriz_valores);$loop_array++){
			if ($matriz_valores[$loop_array][0]==$seleccionado){
				$str_seleccionado = "selected";
			}else{
				$str_seleccionado = "";
			}
			echo "<option value=\"" . $matriz_valores[$loop_array][0] ."\" $str_seleccionado>" . $matriz_valores[$loop_array][1];
		}
		echo "</select>";
	}
}
function db_lista_desplegable($nombre,$query,$onaction=""){
	$matriz_valores = db_ejecutar_consulta($query);
	lista_desplegable($nombre,$matriz_valores,$onaction);
}

function botones_de_radio($nombre,$matriz_valores,$formato="",$onaction=""){
	if (is_array($matriz_valores)){
		global $$nombre;
		$seleccionado = $$nombre;
		for ($loop_array = 0;$loop_array < sizeof($matriz_valores);$loop_array++){
			if ($matriz_valores[$loop_array][0]==$seleccionado){
				$str_seleccionado = "checked";
			}else{
				$str_seleccionado = "";
			}
			echo $formato . "<input type=radio name=\"" . $nombre . "\" value=\"" . $matriz_valores[$loop_array][0] ."\" " . $str_seleccionado . " " . $onaction . ">" . $matriz_valores[$loop_array][1];
		}
	}
}

function db_botones_de_radio($nombre,$query,$formato="",$onaction=""){
	$matriz_valores = db_ejecutar_consulta($query);
	botones_de_radio($nombre,$matriz_valores,$formato,$onaction);
}

function casillas_de_chequeo($nombre,$matriz_valores,$formato="",$onaction=""){
	if (is_array($matriz_valores)){
		global $$nombre;
		$seleccionados = $$nombre;
		for ($loop_array = 0;$loop_array < sizeof($matriz_valores);$loop_array++){
			$str_seleccionado = "";
			for ($loop_seleccionados=0;$loop_seleccionados<sizeof($seleccionados);$loop_seleccionados++){
				if (strval($matriz_valores[$loop_array][0])==strval($seleccionados[$loop_seleccionados])){
					$str_seleccionado = "checked";
				}
			}
			echo $formato . "<input type=checkbox name=\"" . $nombre . "_" . $loop_array . "\" value=\"" . $matriz_valores[$loop_array][0] ."\" " . $str_seleccionado . " " . $onaction . ">" . $matriz_valores[$loop_array][1];
		}
		campo_oculto($nombre . "_cantidad",sizeof($matriz_valores));
	}
}


function db_casillas_de_chequeo($nombre,$query,$formato="",$onaction=""){
	$matriz_valores = db_ejecutar_consulta($query);
	casillas_de_chequeo($nombre,$matriz_valores,$formato,$onaction);
}

function campo_oculto($nombre,$valor){
	echo "<input type=hidden name=\"" . $nombre . "\" value=\"" . $valor . "\">";

}

function campo_archivo($nombre,$tamano=20){
	echo "<input type=file size=" . $tamano . " name =\"" . $nombre . "\">";
}

function boton_enviar($etiqueta,$onclick=""){
	if($onclick){
		$onclick = "onclick=\"javascript:" . $onclick . "\"";
	}
	echo "<input type=submit class =\"botonenviar\" value=\"" . $etiqueta . "\" " . $onclick . ">";
}



function tabla_formulario($matriz_tags,$propiedades){
	global $mostrar_formulario;
	if ($mostrar_formulario){
		if (isset($propiedades["titulo_formulario"])){
			$titulo_formulario = $propiedades["titulo_formulario"];
		} else {
			$titulo_formulario = "";
		}
		if (isset($propiedades["texto_adicional"])){
			$texto_adicional = "<span class=textoadicionaltitulo><br>" . $propiedades["texto_adicional"] . "</span>";
		} else {
			$texto_adicional = "";
		}
		if (isset($propiedades["ancho_tabla"])){
			$ancho_tabla = $propiedades["ancho_tabla"];
			if ($ancho_tabla < 35 or $ancho_tabla > 100){
				$ancho_tabla = 100;
			}
		} else {
			$ancho_tabla = 100;
		}
		if (isset($propiedades["cantidad_celdas"])){
			$cantidad_celdas = $propiedades["cantidad_celdas"];
			if ($cantidad_celdas < 1 or $cantidad_celdas > 7){
				$cantidad_celdas = 3;
			}
		} else {
			$cantidad_celdas = cantidad_celdas;
		}
		if (isset($propiedades["alineacion_tabla"])){
			$alineacion_tabla = $propiedades["alineacion_tabla"];
		} else {
			$alineacion_tabla = "center";
		}
		global $matriz_errores;
		echo "<br><table align=\"" . $alineacion_tabla . "\" width=\"" . $ancho_tabla . "%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td bgcolor=\"#F7EFF7\"><table width=100% cellSpacing=\"1\" cellPadding=\"3\" align=center border=0><td colspan=" . $cantidad_celdas . " class=\"mensajetitulo\" bgcolor=\"#F7EFF7\">" . $titulo_formulario . $texto_adicional . "</td><tr bgcolor=\"#AFCAE4\">";
		$color_fondo[] = "#F4F4F5";
		$color_fondo[] = "#F4F4F5";
		$loop_color_fondo = 0;
		$loop_tr = -1;
		$hay_ventana = false;
		for ($loop_valores=0;$loop_valores<sizeof($matriz_tags);$loop_valores++){
			$loop_tr++;
			if (isset($matriz_tags[$loop_valores]["colspan"])){
				$colspan = $matriz_tags[$loop_valores]["colspan"];
				if ($colspan > 1 and $colspan <= $cantidad_celdas){
					$loop_tr += $colspan;
					$texto_colspan = "colspan=" . $colspan . " ";
				} else {
					$texto_colspan = " ";
				}
			} else {
				$texto_colspan = "";
			}
			if (isset($matriz_tags[$loop_valores]["rowspan"])){
				$rowspan = $matriz_tags[$loop_valores]["rowspan"];
				$texto_rowspan = "rowspan=" . $rowspan . " ";
			} else {
				$texto_rowspan = "";
			}
			if (isset($matriz_tags[$loop_valores]["obligatorio"])){
				if ($matriz_tags[$loop_valores]["obligatorio"]=="S"){
							$imagen_obligatorio = "<span class=mensajeerror>*</span>&nbsp;";
				} else {
					$imagen_obligatorio = "";
				}
			} else {
				$imagen_obligatorio = "";
			}
			if (!isset($matriz_tags[$loop_valores]["etiqueta"])){
				$matriz_tags[$loop_valores]["etiqueta"] = "";
			} else {
				if ($matriz_tags[$loop_valores]["etiqueta"]){
					$matriz_tags[$loop_valores]["etiqueta"] .= "&nbsp;<br>";
				} else {
					$matriz_tags[$loop_valores]["etiqueta"] .= "";
				}
			}
			if (!isset($matriz_tags[$loop_valores]["alineacion"])){
				$alineacion = "left";
			} else {
				$alineacion = $matriz_tags[$loop_valores]["alineacion"];
			}
			if (isset($matriz_tags[$loop_valores]["nueva_linea"])){
				echo "<tr bgcolor=\"" . $color_fondo[$loop_color_fondo] . "\">";
				$loop_color_fondo++;
				if ($loop_color_fondo>1){
					$loop_color_fondo = 0;
				}
			}
			echo "</td><td " . $texto_colspan . $texto_rowspan . " align=\"" . $alineacion , "\" class=etiquetaformulario bgcolor=\"" . $color_fondo[$loop_color_fondo] . "\">&nbsp;" . $imagen_obligatorio . $matriz_tags[$loop_valores]["etiqueta"] ;
			$tipo_tag = $matriz_tags[$loop_valores]["tipo_tag"];
			switch ($tipo_tag){
					case "t": //campo de texto
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							if (isset($matriz_tags[$loop_valores]["tamano"])){
								$tamano = $matriz_tags[$loop_valores]["tamano"];
							} else {
								$tamano = 15;
							}
							if (isset($matriz_tags[$loop_valores]["tamano_maximo"])){
								$tamano_maximo = $matriz_tags[$loop_valores]["tamano_maximo"];
							} else {
								$tamano_maximo = 15;
							}
							campo_de_texto($nombre,$tamano,$tamano_maximo);
							if (isset($matriz_tags[$loop_valores]["boton_ventana"])){
								$query = "select lower(nombre_archivo) from web_seg_archivos where archivo_id = " . $matriz_tags[$loop_valores]["boton_ventana"]["archivo_id"];
								$nombre_archivo = db_buscar_valor($query);
								echo "<a href=\"javascript:abrir_ventana('" . $nombre_archivo . "','document.form1." . $nombre . ".value','" , $matriz_tags[$loop_valores]["boton_ventana"]["variables_adicionales"] . "='+document.form1." . $nombre . ".value," . $matriz_tags[$loop_valores]["boton_ventana"]["ancho"] . "," . $matriz_tags[$loop_valores]["boton_ventana"]["alto"] . ")\">Buscar</a>";
								$hay_ventana = true;
							}
							break;
					case "p": //campo de password
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$tamano = $matriz_tags[$loop_valores]["tamano"];
							$tamano_maximo = $matriz_tags[$loop_valores]["tamano_maximo"];
							campo_de_password($nombre,$tamano,$tamano_maximo);
							break;
					case "fe":
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							campo_de_fecha($nombre);

							// Adicionado // /////////////////////////////////////////////////////////////////////////////////////////////////
							if (isset($matriz_tags[$loop_valores]["boton_ventana"])){
								$query = "select lower(nombre_archivo) from web_seg_archivos where archivo_id = " . $matriz_tags[$loop_valores]["boton_ventana"]["archivo_id"];
								$nombre_archivo = db_buscar_valor($query);
								echo "<a href=\"javascript:abrir_ventana('" . $nombre_archivo . "','document.form1." . $nombre . ".value','" , $matriz_tags[$loop_valores]["boton_ventana"]["variables_adicionales"] . "='+document.form1." . $nombre . ".value," . $matriz_tags[$loop_valores]["boton_ventana"]["ancho"] . "," . $matriz_tags[$loop_valores]["boton_ventana"]["alto"] . ")\">Buscar</a>";
								$hay_ventana = true;
							}
							// //////////////////////////////////////////////////////////////////////////////////////////////////////////////

							break;
					case "ta": //area de texto
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$columnas = $matriz_tags[$loop_valores]["columnas"];
							$filas = $matriz_tags[$loop_valores]["filas"];
							$wrap = $matriz_tags[$loop_valores]["wrap"];
							area_de_texto($nombre,$columnas,$filas,$wrap);
							break;
					case "l": //lista desplegable
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$onaction = $matriz_tags[$loop_valores]["onaction"];
							$matriz_valores = $matriz_tags[$loop_valores]["matriz_valores"];
							lista_desplegable($nombre,$matriz_valores,$onaction);
							break;
					case "r": //boton de radio
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$formato = $matriz_tags[$loop_valores]["formato"];
							$onaction = $matriz_tags[$loop_valores]["onaction"];
							$matriz_valores = $matriz_tags[$loop_valores]["matriz_valores"];
							botones_de_radio($nombre,$matriz_valores,$formato,$onaction);
							break;
					case "c": //casilla de chequeo
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$formato = $matriz_tags[$loop_valores]["formato"];
							$onaction = $matriz_tags[$loop_valores]["onaction"];
							$matriz_valores = $matriz_tags[$loop_valores]["matriz_valores"];
							casillas_de_chequeo($nombre,$matriz_valores,$formato,$onaction);
							break;
					case "c2":
						$nombre = $matriz_tags[$loop_valores]["nombre"];
						$formato = $matriz_tags[$loop_valores]["formato"];
						$onaction = $matriz_tags[$loop_valores]["onaction"];
						$matriz_valores = $matriz_tags[$loop_valores]["matriz_valores"];
						$seleccionados = $matriz_tags[$loop_valores]["seleccionados"];
						check_boxes($matriz_valores,$nombre,$seleccionados,$formato,$onaction);
						break;

					case "s": //boton submit
							$mensaje = $matriz_tags[$loop_valores]["mensaje"];
							if (isset($matriz_tags[$loop_valores]["onclick"])){
								$onclick = $matriz_tags[$loop_valores]["onclick"];
							} else {
								$onclick = "";
							}
							boton_enviar($mensaje,$onclick);
							$matriz_errores[$nombre] = "";
							break;
					case "d": //ejecutar sentencia
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$valor = "<span class=datoformulario>" . $matriz_tags[$loop_valores]["valor"] . "</span>";
							echo $valor;

							break;
					case "h": //campo oculto
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$valor = $matriz_tags[$loop_valores]["valor"];
							echo $valor;
							campo_oculto($nombre,$valor);
							break;
					case "f": //archivo
							$nombre= $matriz_tags[$loop_valores]["nombre"];
							$tamano = $matriz_tags[$loop_valores]["tamano"];
							campo_archivo($nombre,$tamano);
							break;
					}
				if ($tipo_tag!="s" and $tipo_tag!="d" and $tipo_tag!="h" and $tipo_tag!=""){
					if (isset($matriz_errores[$nombre])){
						if ($matriz_errores[$nombre]=="ok"){
							$matriz_errores[$nombre] = "";
						}
						if ($matriz_errores[$nombre]){
							echo "<br><span class=etiquetaerror>" . $matriz_errores[$nombre] . "</span>";
						} else {
							echo " &nbsp;";
						}
					} else {
						echo "<br>&nbsp;";
					}
				}
			}
			echo "</table></td></tr></table></right>";
			if ($hay_ventana){
				echo 	"<script language=javascript>" . chr(10) .
					"	function abrir_ventana(url,objeto_retorno,variables,ancho,alto){" . chr(10) .
					"		var popUp=window.open(url + '?objeto_retorno='+objeto_retorno+'&'+variables,'new','resizable=no,menubar=no,location=no,toolbar=no,status=no,scrollbars=1,directories=no,width=' + ancho + ',height=' + alto +',left=' + ((screen.width - ancho)/2) + ',top=' + ((screen.height - alto)/2) + ',')" . chr(10) .
					"	}</script>";
			}
		}
}

function tabla_formulario2($matriz_tags,$propiedades){
	global $mostrar_formulario;
	if ($mostrar_formulario){
		if (isset($propiedades["titulo_formulario"])){
			$titulo_formulario = $propiedades["titulo_formulario"];
		} else {
			$titulo_formulario = "";
		}
		if (isset($propiedades["texto_adicional"])){
			$texto_adicional = "<span class=textoadicionaltitulo><br>" . $propiedades["texto_adicional"] . "</span>";
		} else {
			$texto_adicional = "";
		}
		if (isset($propiedades["ancho_tabla"])){
			$ancho_tabla = $propiedades["ancho_tabla"];
			if ($ancho_tabla < 35 or $ancho_tabla > 100){
				$ancho_tabla = 100;
			}
		} else {
			$ancho_tabla = 100;
		}
		if (isset($propiedades["cantidad_celdas"])){
			$cantidad_celdas = $propiedades["cantidad_celdas"];
			if ($cantidad_celdas < 1 or $cantidad_celdas > 7){
				$cantidad_celdas = 3;
			}
		} else {
			$cantidad_celdas = cantidad_celdas;
		}
		if (isset($propiedades["alineacion_tabla"])){
			$alineacion_tabla = $propiedades["alineacion_tabla"];
		} else {
			$alineacion_tabla = "center";
		}
		global $matriz_errores;
		echo "<br><table align=\"" . $alineacion_tabla . "\" width=\"" . $ancho_tabla . "%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td bgcolor=\"#F7EFF7\"><table width=100% cellSpacing=\"1\" cellPadding=\"3\" align=center border=0><td colspan=" . $cantidad_celdas . " class=\"mensajetitulo\" bgcolor=\"#F7EFF7\">" . $titulo_formulario . $texto_adicional . "</td><tr bgcolor=\"#AFCAE4\">";
		$color_fondo[] = "#F4F4F5";
		$color_fondo[] = "#FFFFFF";
		$loop_color_fondo = 0;
		$loop_tr = -1;
		$hay_ventana = false;
		for ($loop_valores=0;$loop_valores<sizeof($matriz_tags);$loop_valores++){
			$loop_tr++;
			if (isset($matriz_tags[$loop_valores]["colspan"])){
				$colspan = $matriz_tags[$loop_valores]["colspan"];
				if ($colspan > 1 and $colspan <= $cantidad_celdas){
					$loop_tr += $colspan;
					$texto_colspan = "colspan=" . $colspan . " ";
				} else {
					$texto_colspan = " ";
				}
			} else {
				$texto_colspan = "";
			}
			if (isset($matriz_tags[$loop_valores]["rowspan"])){
				$rowspan = $matriz_tags[$loop_valores]["rowspan"];
				$texto_rowspan = "rowspan=" . $rowspan . " ";
			} else {
				$texto_rowspan = "";
			}
			if (isset($matriz_tags[$loop_valores]["obligatorio"])){
				if ($matriz_tags[$loop_valores]["obligatorio"]=="S"){
							$imagen_obligatorio = "<span class=mensajeerror>*</span>&nbsp;";
				} else {
					$imagen_obligatorio = "";
				}
			} else {
				$imagen_obligatorio = "";
			}
			if (!isset($matriz_tags[$loop_valores]["etiqueta"])){
				$matriz_tags[$loop_valores]["etiqueta"] = "";
			} else {
				if ($matriz_tags[$loop_valores]["etiqueta"]){
					$matriz_tags[$loop_valores]["etiqueta"] .= "&nbsp;<br>";
				} else {
					$matriz_tags[$loop_valores]["etiqueta"] .= "";
				}
			}
			if (!isset($matriz_tags[$loop_valores]["alineacion"])){
				$alineacion = "left";
			} else {
				$alineacion = $matriz_tags[$loop_valores]["alineacion"];
			}
			if (isset($matriz_tags[$loop_valores]["nueva_linea"])){
				echo "<tr bgcolor=\"" . $color_fondo[$loop_color_fondo] . "\">";
				$loop_color_fondo++;
				if ($loop_color_fondo>1){
					$loop_color_fondo = 0;
				}
			}
			echo "</td><td " . $texto_colspan . $texto_rowspan . " align=\"" . $alineacion , "\" class=etiquetaformulario bgcolor=\"" . $color_fondo[$loop_color_fondo] . "\">&nbsp;" . $imagen_obligatorio . $matriz_tags[$loop_valores]["etiqueta"] ;
			$tipo_tag = $matriz_tags[$loop_valores]["tipo_tag"];
			switch ($tipo_tag){
					case "t": //campo de texto
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							if (isset($matriz_tags[$loop_valores]["tamano"])){
								$tamano = $matriz_tags[$loop_valores]["tamano"];
							} else {
								$tamano = 15;
							}
							if (isset($matriz_tags[$loop_valores]["tamano_maximo"])){
								$tamano_maximo = $matriz_tags[$loop_valores]["tamano_maximo"];
							} else {
								$tamano_maximo = 15;
							}
							campo_de_texto($nombre,$tamano,$tamano_maximo);
							if (isset($matriz_tags[$loop_valores]["boton_ventana"])){
								$query = "select lower(nombre_archivo) from web_seg_archivos where archivo_id = " . $matriz_tags[$loop_valores]["boton_ventana"]["archivo_id"];
								$nombre_archivo = db_buscar_valor($query);
								echo "<a href=\"javascript:abrir_ventana('" . $nombre_archivo . "','document.form1." . $nombre . ".value','" , $matriz_tags[$loop_valores]["boton_ventana"]["variables_adicionales"] . "='+document.form1." . $nombre . ".value," . $matriz_tags[$loop_valores]["boton_ventana"]["ancho"] . "," . $matriz_tags[$loop_valores]["boton_ventana"]["alto"] . ")\">Buscar</a>";
								$hay_ventana = true;
							}
							break;
					case "p": //campo de password
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$tamano = $matriz_tags[$loop_valores]["tamano"];
							$tamano_maximo = $matriz_tags[$loop_valores]["tamano_maximo"];
							campo_de_password($nombre,$tamano,$tamano_maximo);
							break;
					case "fe":
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							campo_de_fecha($nombre);

							// Adicionado // /////////////////////////////////////////////////////////////////////////////////////////////////
							if (isset($matriz_tags[$loop_valores]["boton_ventana"])){
								$query = "select lower(nombre_archivo) from web_seg_archivos where archivo_id = " . $matriz_tags[$loop_valores]["boton_ventana"]["archivo_id"];
								$nombre_archivo = db_buscar_valor($query);
								echo "<a href=\"javascript:abrir_ventana('" . $nombre_archivo . "','document.form1." . $nombre . ".value','" , $matriz_tags[$loop_valores]["boton_ventana"]["variables_adicionales"] . "='+document.form1." . $nombre . ".value," . $matriz_tags[$loop_valores]["boton_ventana"]["ancho"] . "," . $matriz_tags[$loop_valores]["boton_ventana"]["alto"] . ")\">Buscar</a>";
								$hay_ventana = true;
							}
							// //////////////////////////////////////////////////////////////////////////////////////////////////////////////

							break;
					case "ta": //area de texto
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$columnas = $matriz_tags[$loop_valores]["columnas"];
							$filas = $matriz_tags[$loop_valores]["filas"];
							$wrap = $matriz_tags[$loop_valores]["wrap"];
							area_de_texto($nombre,$columnas,$filas,$wrap);
							break;
					case "l": //lista desplegable
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$onaction = $matriz_tags[$loop_valores]["onaction"];
							$matriz_valores = $matriz_tags[$loop_valores]["matriz_valores"];
							lista_desplegable2($nombre,$matriz_valores,$onaction);
							break;
					case "r": //boton de radio
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$formato = $matriz_tags[$loop_valores]["formato"];
							$onaction = $matriz_tags[$loop_valores]["onaction"];
							$matriz_valores = $matriz_tags[$loop_valores]["matriz_valores"];
							botones_de_radio($nombre,$matriz_valores,$formato,$onaction);
							break;
					case "c": //casilla de chequeo
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$formato = $matriz_tags[$loop_valores]["formato"];
							$onaction = $matriz_tags[$loop_valores]["onaction"];
							$matriz_valores = $matriz_tags[$loop_valores]["matriz_valores"];
							casillas_de_chequeo($nombre,$matriz_valores,$formato,$onaction);
							break;
					case "c2":
						$nombre = $matriz_tags[$loop_valores]["nombre"];
						$formato = $matriz_tags[$loop_valores]["formato"];
						$onaction = $matriz_tags[$loop_valores]["onaction"];
						$matriz_valores = $matriz_tags[$loop_valores]["matriz_valores"];
						$seleccionados = $matriz_tags[$loop_valores]["seleccionados"];
						check_boxes($matriz_valores,$nombre,$seleccionados,$formato,$onaction);
						break;

					case "s": //boton submit
							$mensaje = $matriz_tags[$loop_valores]["mensaje"];
							if (isset($matriz_tags[$loop_valores]["onclick"])){
								$onclick = $matriz_tags[$loop_valores]["onclick"];
							} else {
								$onclick = "";
							}
							boton_enviar($mensaje,$onclick);
							$matriz_errores[$nombre] = "";
							break;
					case "d": //ejecutar sentencia
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$valor = "<span class=datoformulario>" . $matriz_tags[$loop_valores]["valor"] . "</span>";
							echo $valor;

							break;
					case "h": //campo oculto
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$valor = $matriz_tags[$loop_valores]["valor"];
							echo $valor;
							campo_oculto($nombre,$valor);
							break;
					case "f": //archivo
							$nombre= $matriz_tags[$loop_valores]["nombre"];
							$tamano = $matriz_tags[$loop_valores]["tamano"];
							campo_archivo($nombre,$tamano);
							break;
					}
				if ($tipo_tag!="s" and $tipo_tag!="d" and $tipo_tag!="h" and $tipo_tag!=""){
					if (isset($matriz_errores[$nombre])){
						if ($matriz_errores[$nombre]=="ok"){
							$matriz_errores[$nombre] = "";
						}
						if ($matriz_errores[$nombre]){
							echo "<br><span class=etiquetaerror>" . $matriz_errores[$nombre] . "</span>";
						} else {
							echo " &nbsp;";
						}
					} else {
						echo "<br>&nbsp;";
					}
				}
			}
			echo "</table></td></tr></table></right>";
			if ($hay_ventana){
				echo 	"<script language=javascript>" . chr(10) .
					"	function abrir_ventana(url,objeto_retorno,variables,ancho,alto){" . chr(10) .
					"		var popUp=window.open(url + '?objeto_retorno='+objeto_retorno+'&'+variables,'new','resizable=no,menubar=no,location=no,toolbar=no,status=no,scrollbars=1,directories=no,width=' + ancho + ',height=' + alto +',left=' + ((screen.width - ancho)/2) + ',top=' + ((screen.height - alto)/2) + ',')" . chr(10) .
					"	}</script>";
			}
		}
}
////////////////////////////////////////////////////////////////////////////////////////////

function tabla_formulario3($matriz_tags,$propiedades){
	global $mostrar_formulario;
	if ($mostrar_formulario){
		if (isset($propiedades["titulo_formulario"])){
			$titulo_formulario = $propiedades["titulo_formulario"];
		} else {
			$titulo_formulario = "";
		}
		if (isset($propiedades["texto_adicional"])){
			$texto_adicional = "<span class=textoadicionaltitulo><br>" . $propiedades["texto_adicional"] . "</span>";
		} else {
			$texto_adicional = "";
		}
		if (isset($propiedades["ancho_tabla"])){
			$ancho_tabla = $propiedades["ancho_tabla"];
			if ($ancho_tabla < 35 or $ancho_tabla > 100){
				$ancho_tabla = 100;
			}
		} else {
			$ancho_tabla = 100;
		}
		if (isset($propiedades["cantidad_celdas"])){
			$cantidad_celdas = $propiedades["cantidad_celdas"];
			if ($cantidad_celdas < 1 or $cantidad_celdas > 7){
				$cantidad_celdas = 3;
			}
		} else {
			$cantidad_celdas = cantidad_celdas;
		}
		if (isset($propiedades["alineacion_tabla"])){
			$alineacion_tabla = $propiedades["alineacion_tabla"];
		} else {
			$alineacion_tabla = "center";
		}
		global $matriz_errores;
		echo "<br><table align=\"" . $alineacion_tabla . "\" width=\"" . $ancho_tabla . "%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td bgcolor=\"#F7EFF7\"><table width=100% cellSpacing=\"1\" cellPadding=\"3\" align=center border=0><td colspan=" . $cantidad_celdas . " class=\"mensajetitulo\" bgcolor=\"#F7EFF7\">" . $titulo_formulario . $texto_adicional . "</td><tr bgcolor=\"#AFCAE4\">";
		$color_fondo[] = "#F4F4F5";
		$color_fondo[] = "#F4F4F5";
		$loop_color_fondo = 0;
		$loop_tr = -1;
		$hay_ventana = false;
		for ($loop_valores=0;$loop_valores<sizeof($matriz_tags);$loop_valores++){
			$loop_tr++;
			if (isset($matriz_tags[$loop_valores]["colspan"])){
				$colspan = $matriz_tags[$loop_valores]["colspan"];
				if ($colspan > 1 and $colspan <= $cantidad_celdas){
					$loop_tr += $colspan;
					$texto_colspan = "colspan=" . $colspan . " ";
				} else {
					$texto_colspan = " ";
				}
			} else {
				$texto_colspan = "";
			}
			if (isset($matriz_tags[$loop_valores]["rowspan"])){
				$rowspan = $matriz_tags[$loop_valores]["rowspan"];
				$texto_rowspan = "rowspan=" . $rowspan . " ";
			} else {
				$texto_rowspan = "";
			}
			if (isset($matriz_tags[$loop_valores]["obligatorio"])){
				if ($matriz_tags[$loop_valores]["obligatorio"]=="S"){
							$imagen_obligatorio = "<span class=mensajeerror>*</span>&nbsp;";
				} else {
					$imagen_obligatorio = "";
				}
			} else {
				$imagen_obligatorio = "";
			}
			if (!isset($matriz_tags[$loop_valores]["etiqueta"])){
				$matriz_tags[$loop_valores]["etiqueta"] = "";
			} else {
				if ($matriz_tags[$loop_valores]["etiqueta"]){
					$matriz_tags[$loop_valores]["etiqueta"] .= "&nbsp;<br>";
				} else {
					$matriz_tags[$loop_valores]["etiqueta"] .= "";
				}
			}
			if (!isset($matriz_tags[$loop_valores]["alineacion"])){
				$alineacion = "left";
			} else {
				$alineacion = $matriz_tags[$loop_valores]["alineacion"];
			}
			if (isset($matriz_tags[$loop_valores]["nueva_linea"])){
				echo "<tr bgcolor=\"" . $color_fondo[$loop_color_fondo] . "\">";
				$loop_color_fondo++;
				if ($loop_color_fondo>1){
					$loop_color_fondo = 0;
				}
			}
			echo "</td><td " . $texto_colspan . $texto_rowspan . " align=\"" . $alineacion , "\" class=etiquetaformulario bgcolor=\"" . $color_fondo[$loop_color_fondo] . "\">&nbsp;" . $imagen_obligatorio . $matriz_tags[$loop_valores]["etiqueta"] ;
			$tipo_tag = $matriz_tags[$loop_valores]["tipo_tag"];
			switch ($tipo_tag){
					case "t": //campo de texto
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							if (isset($matriz_tags[$loop_valores]["tamano"])){
								$tamano = $matriz_tags[$loop_valores]["tamano"];
							} else {
								$tamano = 15;
							}
							if (isset($matriz_tags[$loop_valores]["tamano_maximo"])){
								$tamano_maximo = $matriz_tags[$loop_valores]["tamano_maximo"];
							} else {
								$tamano_maximo = 15;
							}
							campo_de_texto_noedit($nombre,$tamano,$tamano_maximo);
							if (isset($matriz_tags[$loop_valores]["boton_ventana"])){
								$query = "select lower(nombre_archivo) from web_seg_archivos where archivo_id = " . $matriz_tags[$loop_valores]["boton_ventana"]["archivo_id"];
								$nombre_archivo = db_buscar_valor($query);
								echo "<a href=\"javascript:abrir_ventana('" . $nombre_archivo . "','document.form1." . $nombre . ".value','" , $matriz_tags[$loop_valores]["boton_ventana"]["variables_adicionales"] . "='+document.form1." . $nombre . ".value," . $matriz_tags[$loop_valores]["boton_ventana"]["ancho"] . "," . $matriz_tags[$loop_valores]["boton_ventana"]["alto"] . ")\">Buscar</a>";
								$hay_ventana = true;
							}
							break;
					case "p": //campo de password
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$tamano = $matriz_tags[$loop_valores]["tamano"];
							$tamano_maximo = $matriz_tags[$loop_valores]["tamano_maximo"];
							campo_de_password($nombre,$tamano,$tamano_maximo);
							break;
					case "fe":
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							campo_de_fecha($nombre);

							// Adicionado // /////////////////////////////////////////////////////////////////////////////////////////////////
							if (isset($matriz_tags[$loop_valores]["boton_ventana"])){
								$query = "select lower(nombre_archivo) from web_seg_archivos where archivo_id = " . $matriz_tags[$loop_valores]["boton_ventana"]["archivo_id"];
								$nombre_archivo = db_buscar_valor($query);
								echo "<a href=\"javascript:abrir_ventana('" . $nombre_archivo . "','document.form1." . $nombre . ".value','" , $matriz_tags[$loop_valores]["boton_ventana"]["variables_adicionales"] . "='+document.form1." . $nombre . ".value," . $matriz_tags[$loop_valores]["boton_ventana"]["ancho"] . "," . $matriz_tags[$loop_valores]["boton_ventana"]["alto"] . ")\">Buscar</a>";
								$hay_ventana = true;
							}
							// //////////////////////////////////////////////////////////////////////////////////////////////////////////////

							break;
					case "ta": //area de texto
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$columnas = $matriz_tags[$loop_valores]["columnas"];
							$filas = $matriz_tags[$loop_valores]["filas"];
							$wrap = $matriz_tags[$loop_valores]["wrap"];
							area_de_texto($nombre,$columnas,$filas,$wrap);
							break;
					case "l": //lista desplegable
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$onaction = $matriz_tags[$loop_valores]["onaction"];
							$matriz_valores = $matriz_tags[$loop_valores]["matriz_valores"];
							lista_desplegable($nombre,$matriz_valores,$onaction);
							break;
					case "r": //boton de radio
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$formato = $matriz_tags[$loop_valores]["formato"];
							$onaction = $matriz_tags[$loop_valores]["onaction"];
							$matriz_valores = $matriz_tags[$loop_valores]["matriz_valores"];
							botones_de_radio($nombre,$matriz_valores,$formato,$onaction);
							break;
					case "c": //casilla de chequeo
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$formato = $matriz_tags[$loop_valores]["formato"];
							$onaction = $matriz_tags[$loop_valores]["onaction"];
							$matriz_valores = $matriz_tags[$loop_valores]["matriz_valores"];
							casillas_de_chequeo($nombre,$matriz_valores,$formato,$onaction);
							break;
					case "c2":
						$nombre = $matriz_tags[$loop_valores]["nombre"];
						$formato = $matriz_tags[$loop_valores]["formato"];
						$onaction = $matriz_tags[$loop_valores]["onaction"];
						$matriz_valores = $matriz_tags[$loop_valores]["matriz_valores"];
						$seleccionados = $matriz_tags[$loop_valores]["seleccionados"];
						check_boxes($matriz_valores,$nombre,$seleccionados,$formato,$onaction);
						break;

					case "s": //boton submit
							$mensaje = $matriz_tags[$loop_valores]["mensaje"];
							if (isset($matriz_tags[$loop_valores]["onclick"])){
								$onclick = $matriz_tags[$loop_valores]["onclick"];
							} else {
								$onclick = "";
							}
							boton_enviar($mensaje,$onclick);
							$matriz_errores[$nombre] = "";
							break;
					case "d": //ejecutar sentencia
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$valor = "<span class=datoformulario>" . $matriz_tags[$loop_valores]["valor"] . "</span>";
							echo $valor;

							break;
					case "h": //campo oculto
							$nombre = $matriz_tags[$loop_valores]["nombre"];
							$valor = $matriz_tags[$loop_valores]["valor"];
							echo $valor;
							campo_oculto($nombre,$valor);
							break;
					case "f": //archivo
							$nombre= $matriz_tags[$loop_valores]["nombre"];
							$tamano = $matriz_tags[$loop_valores]["tamano"];
							campo_archivo($nombre,$tamano);
							break;
					}
				if ($tipo_tag!="s" and $tipo_tag!="d" and $tipo_tag!="h" and $tipo_tag!=""){
					if (isset($matriz_errores[$nombre])){
						if ($matriz_errores[$nombre]=="ok"){
							$matriz_errores[$nombre] = "";
						}
						if ($matriz_errores[$nombre]){
							echo "<br><span class=etiquetaerror>" . $matriz_errores[$nombre] . "</span>";
						} else {
							echo " &nbsp;";
						}
					} else {
						echo "<br>&nbsp;";
					}
				}
			}
			echo "</table></td></tr></table></right>";
			if ($hay_ventana){
				echo 	"<script language=javascript>" . chr(10) .
					"	function abrir_ventana(url,objeto_retorno,variables,ancho,alto){" . chr(10) .
					"		var popUp=window.open(url + '?objeto_retorno='+objeto_retorno+'&'+variables,'new','resizable=no,menubar=no,location=no,toolbar=no,status=no,scrollbars=1,directories=no,width=' + ancho + ',height=' + alto +',left=' + ((screen.width - ancho)/2) + ',top=' + ((screen.height - alto)/2) + ',')" . chr(10) .
					"	}</script>";
			}
		}
}

#############################3 FUNCIONES DE PRESENTACIÓN ####################################
function iniciar_pagina($formulario_archivo = "",$tipo_pagina=""){
	global $titulo_aplicacion;
	global $PHP_SELF;
	if ($formulario_archivo) {
		$formulario_archivo = "ENCTYPE=\"multipart/form-data\" ";
	}
	echo "<html><head><title>" . $titulo_aplicacion . "</title><style>";
	echo 	"A {color:#333366; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; font-weight:normal;};" . chr(10) .
		".mensajetitulo {color:#000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; font-weight:bold; text-align:center};" . chr(10) .
		".mensajesalida {background:#E0E0E0; color:#000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; font-weight:normal; text-align:center};" . chr(10) .
		".mensajeerror {color:#FF0000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; font-weight:normal; text-align:center};" . chr(10) .
		".textoadicionaltitulo {color:#333388; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; font-weight:normal; text-align:center};" . chr(10) .
		".thtablapaginada {background:#AFCAE4; color:#000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; font-weight:bold; text-align:center};" . chr(10) .
		".tdtablapaginada1 {background:#C6D9EC; color:#000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8.5pt; font-weight:normal;};" . chr(10) .
		".tdtablapaginada2 {background:#AFCAE4; color:#000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8.5pt; font-weight:normal;};" . chr(10) .
		".atablapaginada {color:#333366; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; font-weight:normal;};" . chr(10) .
		".etiquetaformulario {font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 10px;font-weight: bold;color: #3C3C77;};" . chr(10) .
		".etiquetaerror {font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 9.5px;font-weight: normal;font-style : italic;;color: #CC1111;};" . chr(10) .
		".datoformulario {color:#000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; font-weight:normal;};" . chr(10) .
		".indicadorobligatorio {color:#ff0000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; font-weight:bold;};" . chr(10) .
		".cuadrotexto {font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 10px;color: #2D2D5B;text-decoration: none;background-color: #FFFFFF;border:1px dashed #8585DC;};" . chr(10) .
		".botonenviar {font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 10px;color: #2D2D5B;font-weight: normal;text-decoration: none;background-color: #FFFFFF;border:1px dashed #8585DC;};" . chr(10) .
		".numerospaginas {background:#FFFFFF; color:#000000; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; font-weight:normal};" . chr(10) ;
	if ($tipo_pagina=="V"){
		$cadena_onload = "onload=\"this.focus()\"";
	} else {
		$cadena_onload = "";
	}
	echo "</style></head><body " . $cadena_onload . " leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\"><form method=post " . $formulario_archivo . " action=\"" . $PHP_SELF . "\" name=form1><input type=hidden name=\"datos_enviados\" value = \"S\">";
	echo "<table width=\"100%\" border=\"0\" cellspacing=\"8\" cellpadding=\"0\" background=\"/images/back_general.jpg\" >
		<tr><td valign=\"top\">
			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		<tr><td width=100% align=center><img src=images/encabezado.gif></td></tr>
		<tr>
			<td bgcolor=\"#C0C0D7\">
			<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
				<tr><td bgcolor=\"#FFFFFF\">
					<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">

						<tr>
						<td width=\"15\">&nbsp;</td>
						<td>";
}

function finalizar_pagina(){
	echo "					&nbsp;</td>
						<td width=\"15\">&nbsp;</td>
					</tr><tr>
						<td><img src=\"/images/transparente.gif\" width=\"15\" height=\"10\"></td>
						<td><img src=\"/images/transparente.gif\" width=\"15\" height=\"10\"></td>
						<td><img src=\"/images/transparente.gif\" width=\"15\" height=\"10\"></td>
					</tr>
					</table>
				</tr>
				</table></td>
			</table></td>
		</tr></table>";

}


function mensaje($texto_mensaje,$tipo_mensaje=""){
	switch ($tipo_mensaje) {
		case "T":
			echo "<br><center><table width=70% border=0 cellspacing=1 cellpadding=1 align=center><tr>";
			$estilo = "mensajetitulo";
			$hay_imagen = False;
			break;
		case "S":
			echo "<br><center><table width=70% border=0 cellspacing=1 cellpadding=1 align=center><tr>";
			$estilo = "mensajesalida";
			$hay_imagen = False;
			break;
		case "E":
			echo "<center><table width=40% border=0 cellspacing=1 cellpadding=1 align=center><tr>";
			$estilo = "mensajeerror";
			$hay_imagen = False;
			break;
		default:
			echo "<table width=100% border=0 cellspacing=0 cellpadding=0><tr>";
			$estilo = "mensaje";
			$imagen = "";
			$hay_imagen = False;
	}
	if ($hay_imagen){
		echo "<td><img src=\"$imagen". "\" border=0></td>";
	}
	echo "<td class=\"" . $estilo . "\">$texto_mensaje </td></tr></table></center>";
}



function _gh_tabla_paginada_empleados($propiedades){
	$matriz_valores = $propiedades["matriz_valores"];
	$vector_encabezados = $propiedades["vector_encabezados"];
	$matriz_links = $propiedades["matriz_links"];
	$registros_por_pagina = $propiedades["registros_por_pagina"];
	$vector_alineaciones = $propiedades["vector_alineaciones"];
	$abrir=$propiedades["abrir"];
	if (isset($propiedades["ancho_tabla"])){
		$ancho_tabla = $propiedades["ancho_tabla"];
		if ($ancho_tabla < 35 or $ancho_tabla > 100){
			$ancho_tabla = 98;
		}
	} else {
		$ancho_tabla = 98;
	}
	$pagina_actual = obtener_dato("pagina_actual");
	if (!$pagina_actual){
		$pagina_actual=1;
	}
	$cantidad_registros = sizeof($matriz_valores);
	if (sizeof($matriz_valores[0])>0){
		$resto = $cantidad_registros % $registros_por_pagina;
		$cantidad_paginas = ($cantidad_registros-$resto) / $registros_por_pagina;
		if ($resto==0){
			$cantidad_paginas -= 1;
		}
		$cantidad_paginas++;
		if ($pagina_actual > $cantidad_paginas){
			$pagina_actual = $cantidad_paginas;
		}
		echo "<br><center><table border=0 cellSpacing=0 cellPadding=4 bgcolor=#FFFFFF width=" . $ancho_tabla . "% align=center ><tr bordercolor=#DBDAE8  bgcolor=#DBDAE8>";
		$registro_inicial = ($pagina_actual-1) * $registros_por_pagina;
		$registro_final = $registro_inicial + $registros_por_pagina;
		if ($registro_final > sizeof($matriz_valores)){
			$registro_final = sizeof($matriz_valores);
		}
		for ($loop_encabezados=0;$loop_encabezados<sizeof($vector_encabezados);$loop_encabezados++){
			echo "<th class=\"thtabla\" bgcolor=#DBDAE8 >" . $vector_encabezados[$loop_encabezados] . "&nbsp;</th>";
			if (!isset($vector_alineaciones[$loop_encabezados - 1])){
				$vector_alineaciones[$loop_encabezados - 1] = "left";
			}
		}
		if ($matriz_links){
			echo "<th class=\"thtablapaginada\" bgcolor=#DBDAE8>&nbsp;</th>";
			$cadena_links = "&nbsp;";
			for($loop_links=0;$loop_links<sizeof($matriz_links);$loop_links++){
				$cadena_links.=generar_link($matriz_links[$loop_links]["archivo_id"],$matriz_links[$loop_links]["etiqueta"],"abrir=$abrir&id=||**||") . " &nbsp;&nbsp;";
			}
		}
		echo "</tr><tr bordercolor=#DBDAE8>";
		$estilo = "tdtablapaginada2";
		for ($loop_filas=$registro_inicial;$loop_filas<$registro_final;$loop_filas++){
			if ($estilo == "tdtablapaginada2"){
				$estilo = "tdtablapaginada1";
			} else {
				$estilo = "tdtablapaginada2";
			}
			if ($cadena_links){
				$cadena_links_fila = str_replace("||**||",$matriz_valores[$loop_filas][0],$cadena_links);
			}
			 $query_color="select fecha_retiro from empleados where empleados_id=".$matriz_valores[$loop_filas][0];
			$retirado=_gh_db_buscar_valor($query_color);
			if($retirado!="0000-00-00"){
				$color_fuente="<font color=009900>";
			}else{
				$color_fuente=" ";
			}
			for ($loop_columnas=1;$loop_columnas<sizeof($matriz_valores[0]);$loop_columnas++){
				echo "<td   class=\"" . $estilo . "\" align=\"" . $vector_alineaciones[$loop_columnas - 1] . "\">&nbsp;". $color_fuente . $matriz_valores[$loop_filas][$loop_columnas] . "&nbsp;</td>";
			}
			if ($cadena_links){
				echo "<td class=\"" . $estilo . "\" align=\"left\">&nbsp;" . $cadena_links_fila . "&nbsp;</td>";
			}
			echo "</tr><tr bordercolor=#DBDAE8>";
		}
		campo_oculto("pagina_actual",$pagina_actual);
		echo "</table></center><script language=javascript>function cambiar_pagina(nueva_pagina){\n";
		echo "document.form1.pagina_actual.value=nueva_pagina;\n";
		echo "document.form1.submit();\n";
		echo "}</script>";
		echo "<table width=" . $ancho_pagina . "%><td class=numerospaginas align=right> Páginas: ";
		for ($loop_paginas=1;$loop_paginas<=$cantidad_paginas;$loop_paginas++){
			if ($loop_paginas == $pagina_actual){
				echo " [<b>". $pagina_actual . "</b>]";
			} else {
				echo "  <a href=\"javascript:cambiar_pagina('" . $loop_paginas . "')\" class=\"numerospaginas\">" . $loop_paginas . "</a>";
			}
		}
		echo "  </td></table></center>";

	} else {
		mensaje("No se encontraron Registros en la base de datos","E");
	}
}

function tabla_paginada($propiedades){
	$matriz_valores = $propiedades["matriz_valores"];
	$vector_encabezados = $propiedades["vector_encabezados"];
	$matriz_links = $propiedades["matriz_links"];
	$registros_por_pagina = $propiedades["registros_por_pagina"];
	$vector_alineaciones = $propiedades["vector_alineaciones"];
	$abrir=$propiedades["abrir"];
	if (isset($propiedades["ancho_tabla"])){
		$ancho_tabla = $propiedades["ancho_tabla"];
		if ($ancho_tabla < 35 or $ancho_tabla > 100){
			$ancho_tabla = 98;
		}
	} else {
		$ancho_tabla = 98;
	}
	$pagina_actual = obtener_dato("pagina_actual");
	if (!$pagina_actual){
		$pagina_actual=1;
	}
	$cantidad_registros = sizeof($matriz_valores);
	if (sizeof($matriz_valores[0])>0){
		$resto = $cantidad_registros % $registros_por_pagina;
		$cantidad_paginas = ($cantidad_registros-$resto) / $registros_por_pagina;
		if ($resto==0){
			$cantidad_paginas -= 1;
		}
		$cantidad_paginas++;
		if ($pagina_actual > $cantidad_paginas){
			$pagina_actual = $cantidad_paginas;
		}
		echo "<br><center><table border=0 cellSpacing=0 cellPadding=4 bgcolor=#FFFFFF width=" . $ancho_tabla . "% align=center ><tr bordercolor=#DBDAE8  bgcolor=#DBDAE8>";
		$registro_inicial = ($pagina_actual-1) * $registros_por_pagina;
		$registro_final = $registro_inicial + $registros_por_pagina;
		if ($registro_final > sizeof($matriz_valores)){
			$registro_final = sizeof($matriz_valores);
		}
		for ($loop_encabezados=0;$loop_encabezados<sizeof($vector_encabezados);$loop_encabezados++){
			echo "<th class=\"thtablapaginada\" bgcolor=#DBDAE8 >" . $vector_encabezados[$loop_encabezados] . "&nbsp;</th>";
			if (!isset($vector_alineaciones[$loop_encabezados - 1])){
				$vector_alineaciones[$loop_encabezados - 1] = "left";
			}
		}
		if ($matriz_links){
			echo "<th class=\"thtablapaginada\" bgcolor=#DBDAE8>&nbsp;</th>";
			$cadena_links = "&nbsp;";
			for($loop_links=0;$loop_links<sizeof($matriz_links);$loop_links++){
				$cadena_links.=generar_link($matriz_links[$loop_links]["archivo_id"],$matriz_links[$loop_links]["etiqueta"],"abrir=$abrir&id=||**||") . " &nbsp;&nbsp;";
			}
		}
		echo "</tr><tr bordercolor=#DBDAE8>";
		$estilo = "tdtablapaginada2";
		for ($loop_filas=$registro_inicial;$loop_filas<$registro_final;$loop_filas++){
			if ($estilo == "tdtablapaginada2"){
				$estilo = "tdtablapaginada1";
			} else {
				$estilo = "tdtablapaginada2";
			}
			if ($cadena_links){
				$cadena_links_fila = str_replace("||**||",$matriz_valores[$loop_filas][0],$cadena_links);
			}
			for ($loop_columnas=1;$loop_columnas<sizeof($matriz_valores[0]);$loop_columnas++){
				echo "<td  class=\"" . $estilo . "\" align=\"" . $vector_alineaciones[$loop_columnas - 1] . "\">&nbsp;" . $matriz_valores[$loop_filas][$loop_columnas] . "&nbsp;</td>";
			}
			if ($cadena_links){
				echo "<td class=\"" . $estilo . "\" align=\"left\">&nbsp;" . $cadena_links_fila . "&nbsp;</td>";
			}
			echo "</tr><tr bordercolor=#DBDAE8>";
		}
		campo_oculto("pagina_actual",$pagina_actual);
		echo "</table></center><script language=javascript>function cambiar_pagina(nueva_pagina){\n";
		echo "document.form1.pagina_actual.value=nueva_pagina;\n";
		echo "document.form1.submit();\n";
		echo "}</script>";
		echo "<table width=" . $ancho_pagina . "%><td class=numerospaginas align=right> Páginas: ";
		for ($loop_paginas=1;$loop_paginas<=$cantidad_paginas;$loop_paginas++){
			if ($loop_paginas == $pagina_actual){
				echo " [<b>". $pagina_actual . "</b>]";
			} else {
				echo "  <a href=\"javascript:cambiar_pagina('" . $loop_paginas . "')\" class=\"numerospaginas\">" . $loop_paginas . "</a>";
			}
		}
		echo "  </td></table></center>";

	} else {
		mensaje("No se encontraron Registros en la base de datos","E");
	}
}


function tabla_paginada2($propiedades){
	$matriz_valores = $propiedades["matriz_valores"];
	$vector_encabezados = $propiedades["vector_encabezados"];
	$matriz_links = $propiedades["matriz_links"];
	$registros_por_pagina = $propiedades["registros_por_pagina"];
	$vector_alineaciones = $propiedades["vector_alineaciones"];
	$abrir=$propiedades["abrir"];
	if (isset($propiedades["ancho_tabla"])){
		$ancho_tabla = $propiedades["ancho_tabla"];
		if ($ancho_tabla < 35 or $ancho_tabla > 100){
			$ancho_tabla = 98;
		}
	} else {
		$ancho_tabla = 98;
	}
	$pagina_actual = obtener_dato("pagina_actual");
	if (!$pagina_actual){
		$pagina_actual=1;
	}
	$cantidad_registros = sizeof($matriz_valores);
	if (sizeof($matriz_valores[0])>0){
		$resto = $cantidad_registros % $registros_por_pagina;
		$cantidad_paginas = ($cantidad_registros-$resto) / $registros_por_pagina;
		if ($resto==0){
			$cantidad_paginas -= 1;
		}
		$cantidad_paginas++;
		if ($pagina_actual > $cantidad_paginas){
			$pagina_actual = $cantidad_paginas;
		}
		echo "<br><center><table border=0 cellSpacing=0 cellPadding=4 bgcolor=#FFFFFF width=" . $ancho_tabla . "% align=center ><tr bordercolor=#DBDAE8  bgcolor=#DBDAE8>";
		$registro_inicial = ($pagina_actual-1) * $registros_por_pagina;
		$registro_final = $registro_inicial + $registros_por_pagina;
		if ($registro_final > sizeof($matriz_valores)){
			$registro_final = sizeof($matriz_valores);
		}
		for ($loop_encabezados=0;$loop_encabezados<sizeof($vector_encabezados);$loop_encabezados++){
			echo "<th class=\"thtablapaginada\" bgcolor=#DBDAE8 >" . $vector_encabezados[$loop_encabezados] . "&nbsp;</th>";
			if (!isset($vector_alineaciones[$loop_encabezados - 1])){
				$vector_alineaciones[$loop_encabezados - 1] = "left";
			}
		}
		if ($matriz_links){
			echo "<th class=\"thtablapaginada\" bgcolor=#DBDAE8>&nbsp;</th>";
			$cadena_links = "&nbsp;";
			for($loop_links=0;$loop_links<sizeof($matriz_links);$loop_links++){
				$cadena_links.=generar_link($matriz_links[$loop_links]["archivo_id"],$matriz_links[$loop_links]["etiqueta"],"abrir=$abrir&id=||**||") . " &nbsp;&nbsp;";
			}
		}
		echo "</tr><tr bordercolor=#DBDAE8>";
		$estilo = "tdtablapaginada2";
		for ($loop_filas=$registro_inicial;$loop_filas<$registro_final;$loop_filas++){
			if ($estilo == "tdtablapaginada2"){
				$estilo = "tdtablapaginada1";
			} else {
				$estilo = "tdtablapaginada2";
			}
			if ($cadena_links){
				$cadena_links_fila = str_replace("||**||",$matriz_valores[$loop_filas][0],$cadena_links);
			}
			for ($loop_columnas=1;$loop_columnas<sizeof($matriz_valores[0]);$loop_columnas++){
				echo "<td  class=\"" . $estilo . "\" align=\"" . $vector_alineaciones[$loop_columnas - 1] . "\">&nbsp;" . $matriz_valores[$loop_filas][$loop_columnas] . "&nbsp;</td>";
			}
			if ($cadena_links){
				echo "<td class=\"" . $estilo . "\" align=\"left\">&nbsp;" . $cadena_links_fila . "&nbsp;</td>";
			}
			echo "</tr><tr bordercolor=#DBDAE8>";
		}
		campo_oculto("pagina_actual",$pagina_actual);
		echo "</table></center><script language=javascript>function cambiar_pagina(nueva_pagina){\n";
		echo "document.form2.pagina_actual.value=nueva_pagina;\n";
		echo "document.form1.guardar.value='S';\n";
		echo "document.form2.submit();\n";
		echo "}</script>";
		echo "<table width=" . $ancho_pagina . "%><td class=numerospaginas align=right> Páginas: ";
		for ($loop_paginas=1;$loop_paginas<=$cantidad_paginas;$loop_paginas++){
			if ($loop_paginas == $pagina_actual){
				echo " [<b>". $pagina_actual . "</b>]";
			} else {
				echo "  <a href=\"javascript:cambiar_pagina('" . $loop_paginas . "')\" class=\"numerospaginas\">" . $loop_paginas . "</a>";
			}
		}
		echo "  </td></table></center>";

	} else {
		mensaje("No se encontraron Registros en la base de datos","E");
	}
}

function tabla_paginada_reporte($propiedades){
	$matriz_valores = $propiedades["matriz_valores"];
	$vector_encabezados = $propiedades["vector_encabezados"];
	$matriz_links = $propiedades["matriz_links"];
	$registros_por_pagina = $propiedades["registros_por_pagina"];
	$vector_alineaciones = $propiedades["vector_alineaciones"];
	$abrir=$propiedades["abrir"];
	if (isset($propiedades["ancho_tabla"])){
		$ancho_tabla = $propiedades["ancho_tabla"];
		if ($ancho_tabla < 35 or $ancho_tabla > 100){
			$ancho_tabla = 98;
		}
	} else {
		$ancho_tabla = 98;
	}
	$pagina_actual = obtener_dato("pagina_actual");
	if (!$pagina_actual){
		$pagina_actual=1;
	}
	$cantidad_registros = sizeof($matriz_valores);
	if (sizeof($matriz_valores[0])>0){
		$resto = $cantidad_registros % $registros_por_pagina;
		$cantidad_paginas = ($cantidad_registros-$resto) / $registros_por_pagina;
		if ($resto==0){
			$cantidad_paginas -= 1;
		}
		$cantidad_paginas++;
		if ($pagina_actual > $cantidad_paginas){
			$pagina_actual = $cantidad_paginas;
		}
		$cadena= "<br><center><table border=0 cellSpacing=0 cellPadding=4 bgcolor=#FFFFFF width=" . $ancho_tabla . "% align=center ><tr><td img src=images/logosti.JPG border=0></tr><tr bordercolor=#DBDAE8  bgcolor=#DBDAE8>";
		$registro_inicial = ($pagina_actual-1) * $registros_por_pagina;
		$registro_final = $registro_inicial + $registros_por_pagina;
		if ($registro_final > sizeof($matriz_valores)){
			$registro_final = sizeof($matriz_valores);
		}
		for ($loop_encabezados=0;$loop_encabezados<sizeof($vector_encabezados);$loop_encabezados++){
			$cadena.= "<th class=\"thtablapaginada\" bgcolor=#DBDAE8 >" . $vector_encabezados[$loop_encabezados] . "&nbsp;</th>";
			if (!isset($vector_alineaciones[$loop_encabezados - 1])){
				$vector_alineaciones[$loop_encabezados - 1] = "left";
			}
		}
		if ($matriz_links){
			$cadena.= "<th class=\"thtablapaginada\" bgcolor=#DBDAE8>&nbsp;</th>";
			$cadena_links = "&nbsp;";
			for($loop_links=0;$loop_links<sizeof($matriz_links);$loop_links++){
				$cadena_links.=generar_link($matriz_links[$loop_links]["archivo_id"],$matriz_links[$loop_links]["etiqueta"],"abrir=$abrir&id=||**||") . " &nbsp;&nbsp;";
			}
		}
		$cadena.= "</tr><tr>";
		$estilo = "tdtablapaginada2";
		for ($loop_filas=$registro_inicial;$loop_filas<$registro_final;$loop_filas++){
			if ($estilo == ""){
				$estilo = "";
			} else {
				$estilo = "";
			}
			if ($cadena_links){
				$cadena_links_fila = str_replace("||**||",$matriz_valores[$loop_filas][0],$cadena_links);
			}
			for ($loop_columnas=1;$loop_columnas<sizeof($matriz_valores[0]);$loop_columnas++){
				$cadena.= "<td  class=\"" . $estilo . "\" align=\"" . $vector_alineaciones[$loop_columnas - 1] . "\">" . $matriz_valores[$loop_filas][$loop_columnas] . "</td>";
			}
			if ($cadena_links){
				$cadena.= "<td class=\"" . $estilo . "\" align=\"left\">&nbsp;" . $cadena_links_fila . "&nbsp;</td>";
			}
			$cadena.= "</tr>";
		}
		$cadena.= "</table>";

	} else {
//		mensaje("No se encontraron Registros en la base de datos","E");
	}
	return $cadena;
}


function tabla_paginada3($propiedades){
	$matriz_valores = $propiedades["matriz_valores"];
	$vector_encabezados = $propiedades["vector_encabezados"];
	$matriz_links = $propiedades["matriz_links"];
	$registros_por_pagina = $propiedades["registros_por_pagina"];
	$vector_alineaciones = $propiedades["vector_alineaciones"];
	$abrir=$propiedades["abrir"];
	if (isset($propiedades["ancho_tabla"])){
		$ancho_tabla = $propiedades["ancho_tabla"];
		if ($ancho_tabla < 35 or $ancho_tabla > 100){
			$ancho_tabla = 98;
		}
	} else {
		$ancho_tabla = 98;
	}
	$pagina_actual = obtener_dato("pagina_actual");
	if (!$pagina_actual){
		$pagina_actual=1;
	}
	$cantidad_registros = sizeof($matriz_valores);
	if (sizeof($matriz_valores[0])>0){
		$resto = $cantidad_registros % $registros_por_pagina;
		$cantidad_paginas = ($cantidad_registros-$resto) / $registros_por_pagina;
		if ($resto==0){
			$cantidad_paginas -= 1;
		}
		$cantidad_paginas++;
		if ($pagina_actual > $cantidad_paginas){
			$pagina_actual = $cantidad_paginas;
		}
		echo "<br><center><table border=0 cellSpacing=0 cellPadding=4 bgcolor=#FFFFFF width=" . $ancho_tabla . "% align=center ><tr bordercolor=#DBDAE8  bgcolor=#DBDAE8>";
		$registro_inicial = ($pagina_actual-1) * $registros_por_pagina;
		$registro_final = $registro_inicial + $registros_por_pagina;
		if ($registro_final > sizeof($matriz_valores)){
			$registro_final = sizeof($matriz_valores);
		}
		for ($loop_encabezados=0;$loop_encabezados<sizeof($vector_encabezados);$loop_encabezados++){
			echo "<th class=\"thtablapaginada\" bgcolor=#DBDAE8 >" . $vector_encabezados[$loop_encabezados] . "&nbsp;</th>";
			if (!isset($vector_alineaciones[$loop_encabezados - 1])){
				$vector_alineaciones[$loop_encabezados - 1] = "left";
			}
		}
		if ($matriz_links){
			echo "<th class=\"thtablapaginada\" bgcolor=#DBDAE8>&nbsp;</th>";
			$cadena_links = "&nbsp;";
			for($loop_links=0;$loop_links<sizeof($matriz_links);$loop_links++){
				$cadena_links.=generar_link($matriz_links[$loop_links]["archivo_id"],$matriz_links[$loop_links]["etiqueta"],"abrir=$abrir&id=||**||") . " &nbsp;&nbsp;";
			}
		}
		echo "</tr><tr bordercolor=#DBDAE8>";
		$estilo = "tdtablapaginada2";
		for ($loop_filas=$registro_inicial;$loop_filas<$registro_final;$loop_filas++){
			if ($estilo == "tdtablapaginada2"){
				$estilo = "tdtablapaginada1";
			} else {
				$estilo = "tdtablapaginada2";
			}
			if ($cadena_links){
				$cadena_links_fila = str_replace("||**||",$matriz_valores[$loop_filas][0],$cadena_links);
			}
			for ($loop_columnas=1;$loop_columnas<sizeof($matriz_valores[0]);$loop_columnas++){
				echo "<td  class=\"" . $estilo . "\" align=\"" . $vector_alineaciones[$loop_columnas - 1] . "\">&nbsp;" . $matriz_valores[$loop_filas][$loop_columnas] . "&nbsp;</td>";
			}
			if ($cadena_links){
				echo "<td class=\"" . $estilo . "\" align=\"left\">&nbsp;" . $cadena_links_fila . "&nbsp;</td>";
			}
			echo "</tr><tr bordercolor=#DBDAE8>";
		}
		campo_oculto("pagina_actual",$pagina_actual);
		echo "</table></center><script language=javascript>function cambiar_pagina(nueva_pagina){\n";
		echo "document.form2.pagina_actual.value=nueva_pagina;\n";
                echo "document.form1.guardar.value='S';\n";
		echo "document.form2.submit();\n";
		echo "}</script>";
		echo "<table width=" . $ancho_pagina . "%><td class=numerospaginas align=right> Páginas: ";
		for ($loop_paginas=1;$loop_paginas<=$cantidad_paginas;$loop_paginas++){
			if ($loop_paginas == $pagina_actual){
				echo " [<b>". $pagina_actual . "</b>]";
			} else {
				echo "  <a href=\"javascript:cambiar_pagina('" . $loop_paginas . "')\" class=\"numerospaginas\">" . $loop_paginas . "</a>";
			}
		}
		echo "  </td></table></center>";

	} else {
		mensaje("No se encontraron Registros en la base de datos","E");
	}
}
function tabla_paginada_order($propiedades){
	$matriz_valores = $propiedades["matriz_valores"];
	$vector_encabezados = $propiedades["vector_encabezados"];
	$matriz_links = $propiedades["matriz_links"];
	$registros_por_pagina = $propiedades["registros_por_pagina"];
	$vector_alineaciones = $propiedades["vector_alineaciones"];
	$abrir=$propiedades["abrir"];
	if (isset($propiedades["ancho_tabla"])){
		$ancho_tabla = $propiedades["ancho_tabla"];
		if ($ancho_tabla < 35 or $ancho_tabla > 100){
			$ancho_tabla = 98;
		}
	} else {
		$ancho_tabla = 98;
	}
	$pagina_actual = obtener_dato("pagina_actual");
	if (!$pagina_actual){
		$pagina_actual=1;
	}
	$cantidad_registros = sizeof($matriz_valores);
	if (sizeof($matriz_valores[0])>0){
		$resto = $cantidad_registros % $registros_por_pagina;
		$cantidad_paginas = ($cantidad_registros-$resto) / $registros_por_pagina;
		if ($resto==0){
			$cantidad_paginas -= 1;
		}
		$cantidad_paginas++;
		if ($pagina_actual > $cantidad_paginas){
			$pagina_actual = $cantidad_paginas;
		}
		echo "<br><center><table border=0 cellSpacing=0 cellPadding=4 bgcolor=#FFFFFF width=" . $ancho_tabla . "% align=center ><tr bordercolor=#DBDAE8  bgcolor=#DBDAE8>";
		$registro_inicial = ($pagina_actual-1) * $registros_por_pagina;
		$registro_final = $registro_inicial + $registros_por_pagina;
		if ($registro_final > sizeof($matriz_valores)){
			$registro_final = sizeof($matriz_valores);
		}
		$cadena_variables=$propiedades["cadena_variables"];
		for ($loop_encabezados=0;$loop_encabezados<sizeof($vector_encabezados);$loop_encabezados++){
			echo "<th class=\"thtablapaginada\" bgcolor=#DBDAE8 ><a href=".$_SERVER['PHP_SELF']."?ordenar=$loop_encabezados&guardar=S&$cadena_variables>" . $vector_encabezados[$loop_encabezados] . "&nbsp;</a></th>";
			if (!isset($vector_alineaciones[$loop_encabezados - 1])){
				$vector_alineaciones[$loop_encabezados - 1] = "left";
			}
		}
		if ($matriz_links){
			echo "<th class=\"thtablapaginada\" bgcolor=#DBDAE8>&nbsp;</th>";
			$cadena_links = "&nbsp;";
			for($loop_links=0;$loop_links<sizeof($matriz_links);$loop_links++){
				$cadena_links.=generar_link($matriz_links[$loop_links]["archivo_id"],$matriz_links[$loop_links]["etiqueta"],"abrir=$abrir&id=||**||") . " &nbsp;&nbsp;";
			}
		}
		echo "</tr><tr bordercolor=#DBDAE8>";
		$estilo = "tdtablapaginada2";
		for ($loop_filas=$registro_inicial;$loop_filas<$registro_final;$loop_filas++){
			if ($estilo == "tdtablapaginada2"){
				$estilo = "tdtablapaginada1";
			} else {
				$estilo = "tdtablapaginada2";
			}
			if ($cadena_links){
				$cadena_links_fila = str_replace("||**||",$matriz_valores[$loop_filas][0],$cadena_links);
			}
			for ($loop_columnas=1;$loop_columnas<sizeof($matriz_valores[0]);$loop_columnas++){
				echo "<td  class=\"" . $estilo . "\" align=\"" . $vector_alineaciones[$loop_columnas - 1] . "\">&nbsp;" . $matriz_valores[$loop_filas][$loop_columnas] . "&nbsp;</td>";
			}
			if ($cadena_links){
				echo "<td class=\"" . $estilo . "\" align=\"left\">&nbsp;" . $cadena_links_fila . "&nbsp;</td>";
			}
			echo "</tr><tr bordercolor=#DBDAE8>";
		}
		campo_oculto("pagina_actual",$pagina_actual);
		echo "</table></center><script language=javascript>function cambiar_pagina(nueva_pagina){\n";
		echo "document.form2.pagina_actual.value=nueva_pagina;\n";
		echo "document.form1.guardar.value='S';\n";
		echo "document.form2.submit();\n";
		echo "}</script>";
		echo "<table width=" . $ancho_pagina . "%><td class=numerospaginas align=right> Páginas: ";
		for ($loop_paginas=1;$loop_paginas<=$cantidad_paginas;$loop_paginas++){
			if ($loop_paginas == $pagina_actual){
				echo " [<b>". $pagina_actual . "</b>]";
			} else {
				echo "  <a href=\"javascript:cambiar_pagina('" . $loop_paginas . "')\" class=\"numerospaginas\">" . $loop_paginas . "</a>";
			}
		}
		echo "  </td></table></center>";

	} else {
		mensaje("No se encontraron Registros en la base de datos","E");
	}
}




function db_tabla_paginada($query,$propiedades){
	$propiedades["matriz_valores"] = db_ejecutar_consulta($query);
	tabla_paginada($propiedades);
}


function generar_menu($vector_ids,$vector_etiquetas){
	if (sizeof($vector_ids) == sizeof($vector_etiquetas)){
		echo "<br><br><center><table border=0><td align=\"center\">";
		for ($loop_vector=0;$loop_vector<sizeof($vector_ids);$loop_vector++){
			echo generar_link($vector_ids[$loop_vector],"<b>".$vector_etiquetas[$loop_vector]. "</b><br>");
		}
		echo "</td></table></td></table></center>";
	}
}

function generar_link($archivo_id,$etiqueta,$variables_a_pasar="",$estilo="linkdefault",$mostrar_siempre="",$target=""){
	if ($archivo_id){
		$query = "select  lower(nombre_archivo) from seg_archivos_funciones where archivo_funcion_id = " . $archivo_id;
				$nombre_archivo = db_buscar_valor($query);
		if ($nombre_archivo){
			if ($variables_a_pasar) {
				$variables_a_pasar = "?" . $variables_a_pasar;
			}
			if ($target) {
				$target = " target=\"" . $target . "\"";
			}
			return "<a href=\"" . $nombre_archivo . $variables_a_pasar . "\"" . $target . " class=\"" . $estilo . "\">" . $etiqueta . "</a>";
		}
	}
}

function redireccionar_pagina($pagina,$ruta="tampa/intranet/desarrollo/admin/"){
	global $dominio_aplicacion;
	if ($pagina){
		echo "<script language=javascript>top.location='http://" . $dominio_aplicacion . "/" . $ruta . $pagina . "'</script>";
		exit;
	}
}
function campo_hidden($nombre,$valor){
	echo "<input type=hidden name=\"$nombre\" value =\"$valor\">";

}

function mostrar_menu_categorias($cadena_menu,$abrir){
//	$vector_datos =explode(",",$cadena_menu);
	if($cadena_menu!=''){
		$query="select id,nombre,cat_padre_id from con_categoria_galeria where id in($cadena_menu)";	
	}
	

//	$query="select b.id,a.id from con_categoria_galeria a join con_categoria_galeria b on a.cat_padre_id=b.id order by b.id,a.id";
	$vector_datos = db_ejecutar_consulta($query);
	for($i=0;$i<sizeof($vector_datos );$i++){
		$cat_id=$vector_datos [$i][0];
		$nombre_cat=$vector_datos [$i][1];
		$cat_padre_id=$vector_datos [$i][2];
		$cadena_link=$cadena_link." / <a href=galeria_listar.php?cat_padre_id=$cat_padre_id&abrir=$abrir>$nombre_cat</a>";
	}
//	$papa=$vector_nevo[$id];
//	$query="select nombre,cat_padre_id from con_categoria_galeria where id='$papa'";
//	$vector_datos = db_ejecutar_consulta($query);
//	$nombres= $vector_datos[0][0] . " > ";
//	$padres= $vector_datos[0][1];
//	if($padres==0){
//		$nombres="Raiz > ";
//	}
//	if(	$cat_padre_id==""){
//		$nombres=" ";
//	}
//	$cadena= "<a href='galeria_listar.php?cat_padre_id=$padres'>$nombres</a>";
//	$abuelo=$vector_nevo[$papa];
//	if ($abuelo!=''){
	
//		$query="select nombre,cat_padre_id from con_categoria_galeria where id='$abuelo'";
//		$vector_datos = db_ejecutar_consulta($query);
//		$nombres= $vector_datos[0][0] . " > ";
//		$padres= $vector_datos[0][1];
//		if($padres==0){
//			$nombres="Raiz > ";
//		}
//		if(	$cat_padre_id==""){
//			$nombres=" ";
//		}
//		$cadena= "<a href='galeria_listar.php?cat_padre_id=$padres'>$nombres</a>".$cadena;

//	}
	if($cadena_link!=' / <a href=galeria_listar.php?cat_padre_id=&abrir=></a>'){
		echo $cadena_link;
	}
	
}

function obtener_matriz_checkboxes($nombre_lista,$mensaje_error=""){
	$nombre_item = $nombre_lista;
	$nombre_lista .= "_lista";
	global $$nombre_lista;
	$vector_lista = split(",",${$nombre_lista});
	for ($loop_vector=0;$loop_vector<sizeof($vector_lista);$loop_vector++){
		$nombre_este_item = $nombre_item . "_" . $vector_lista[$loop_vector];
		global $$nombre_este_item;
		if (str_replace(" ", "",${$nombre_este_item})=="on"){;
			$matriz_retorno[] = $vector_lista[$loop_vector];
		}
	}
	if (!isset($matriz_retorno)) {
		if ($mensaje_error){
			error_validacion($mensaje_error);
		} else {
			return false;
		}
	} else {
		return $matriz_retorno;
	}
}


function check_boxes($matriz_valores,$nombre,$seleccionados="",$formato="",$onaction=""){
	if (is_array($matriz_valores)){
		$str_lista = "";
		for ($loop_array = 0;$loop_array < sizeof($matriz_valores);$loop_array++){
			$str_seleccionado = "";
			for ($loop_seleccionados=0;$loop_seleccionados<sizeof($seleccionados);$loop_seleccionados++){
				if ($matriz_valores[$loop_array][0]==$seleccionados[$loop_seleccionados]){
					$str_seleccionado = "checked";
				}
			}
			if ($loop_array>0) {
				echo $formato;
			}
			echo "<input type=checkbox name=\"$nombre" . "_" . $matriz_valores[$loop_array][0] ."\" $str_seleccionado $onaction>" . $matriz_valores[$loop_array][1];
			$str_lista .= "," . $matriz_valores[$loop_array][0];
		}
		$str_lista = substr($str_lista,1);
		echo "<input type=hidden name=\"$nombre" . "_lista\" value=\"$str_lista\">";
	}
}

function calendario($pagina,$paginadestino,$ano,$mes){
	if ($ano==""){
		$ano=date("Y");	
	}
	if ($mes==""){
		$mes=date("n");
	}
	if ($mes==1){
		$anoanterior=$ano-1;
		$mesanterior=12;
	}else{
		$anoanterior=$ano;
		$mesanterior=$mes-1;
	}
	if ($mes==12){
		$anosiguiente=$ano+1;
		$messiguiente=1;
	}else{
		$anosiguiente=$ano;
		$messiguiente=$mes+1;
	}

?>							
	<table width="700" height="100%" border="0" cellpadding="0" cellspacing="2">
	  <tr> 
		<td><span class="hd-mensaje"> 
<?php
		$meses=Array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		$mesmostrar=$meses[$mes];
	?>
</span></td>
	  </tr>
	  <tr bgcolor=F4F4F5> 
		<td bgcolor=F4F4F5><div align="center"> 
			<table width="700" border="0" cellspacing="0" cellpadding="0" >
			  <tr> 
				<td><table width="700%"  border="0" cellspacing="0" cellpadding="0" >

					
					<tr> 
					  <td width="18%" height="19"><a href="<?php echo $pagina?>?mes=<?php echo $mesanterior?>&ano=<?php echo $anoanterior?>" class="hd-mensaje"></a></td>
					  <td width="64%" class="TVerd10VerdeBold"><div align="center" class="hd-mensaje">																			<?php echo$mesmostrar?></div></td>
					  <td width="18%"><a href="<?php echo$pagina?>?mes=<?php echo$messiguiente?>&ano=<?php echo$anosiguiente?>" class="hd-mensaje"></a></td>
					</tr>

					<?php
					echo "<tr class=\"datoformulario\"><td colspan=4 align=center><font size=-2><b><a href='$pagina?mes=$mesanterior&ano=$anoanterior&zonas_id=$zonas_id'><<</a>&nbsp;&nbsp;<a href='agenda_nueva_listar.php'>Semana</a>&nbsp;&nbsp;<a href='#'>Mes</a>&nbsp;&nbsp;<a href='$pagina?mes=$messiguiente&ano=$anosiguiente&zonas_id=$zonas_id'>>></a></b></td></tr>";	
					
					?>
				  </table></td>
			  </tr>
			  <tr > 
				<td><table width="694"  border="0" cellspacing="2" cellpadding="2" >
					<tr > 
	<?php
	
		?>

		<td width=100><div align="center"><b>Domingo</div></td>
		<td width=100><div align="center"><b>Lunes</div></td>
		<td width=100><div align="center"><b>Martes</div></td>
		<td width=100><div align="center"><b>Miercoles</div></td>
		<td width=100><div align="center"><b>Jueves</div></td>
		<td width=100><div align="center"><b>Viernes</div></td>
		<td width=100><div align="center"><b>Sabado</div></td>

		<?php

	echo "</tr>";

//	$diaactual=date("d");
//	$diaactualno=date("w");
	$diasdelmes=date("t",mktime(0, 0, 0, $mes, date("d"),$ano));
	$primerdelmes=date("w",mktime(0, 0, 0, $mes,1,$ano));
	switch($primerdelmes){
		case 0:
			$semana[1][0]=1;
			$semana[1][1]=2;
			$semana[1][2]=3;
			$semana[1][3]=4;
			$semana[1][4]=5;
			$semana[1][5]=6;
			$semana[1][6]=7;
		break;
		case 1:
			$semana[1][0]=0;
			$semana[1][1]=1;
			$semana[1][2]=2;
			$semana[1][3]=3;
			$semana[1][4]=4;
			$semana[1][5]=5;
			$semana[1][6]=6;
		break;
		case 2:
			$semana[1][0]=-1;
			$semana[1][1]=0;
			$semana[1][2]=1;
			$semana[1][3]=2;
			$semana[1][4]=3;
			$semana[1][5]=4;
			$semana[1][6]=5;
		break;
		case 3:
			$semana[1][0]=-2;
			$semana[1][1]=-1;
			$semana[1][2]=0;
			$semana[1][3]=1;
			$semana[1][4]=2;
			$semana[1][5]=3;
			$semana[1][6]=4;
		break;
		case 4:
			$semana[1][0]=-3;
			$semana[1][1]=-2;
			$semana[1][2]=-1;
			$semana[1][3]=0;
			$semana[1][4]=1;
			$semana[1][5]=2;
			$semana[1][6]=3;
		break;
		case 5:
			$semana[1][0]=-4;
			$semana[1][1]=-3;
			$semana[1][2]=-2;
			$semana[1][3]=-1;
			$semana[1][4]=0;
			$semana[1][5]=1;
			$semana[1][6]=2;
		break;
		case 6:
			$semana[1][0]=-5;
			$semana[1][1]=-4;
			$semana[1][2]=-3;
			$semana[1][3]=-2;
			$semana[1][4]=-1;
			$semana[1][5]=0;
			$semana[1][6]=1;
		break;
	}

	
	for($k=1;$k<6;$k++){
	
		echo "<tr bgcolor=FFFFFF>";
		
//echo "		<td><a href=\"#\" onclick=\"abrir_detalle_semana($ano,$mes,". $semana[$k][0].")\">Semana</td>";
		
		for($j=0;$j<7;$j++){
				$semana[2][$j]=$semana[1][$j]+7;
				$semana[3][$j]=$semana[1][$j]+14;
				$semana[4][$j]=$semana[1][$j]+21;
				$semana[5][$j]=$semana[1][$j]+28;
				$semana[6][$j]=$semana[1][$j]+35;
		?>
		
			  <td  width=150 height=100 align="right" valign="top"><div align="right" valign="top"><a href="#" onclick="abrir_detalle(<?php echo $ano?>,<?php echo $mes?>,<?php echo $semana[$k][$j]?>)">
			  <?php 
			  if(($semana[$k][$j]>0)and($semana[$k][$j]<$diasdelmes+1)){
					if($semana[$k][$j]==date('d')){
						echo "<b><font color=990000>".$semana[$k][$j]."</b>";				
					}else{
						echo $semana[$k][$j];					
					}

			  }
				?></a></div><br><div align=justify>
			<?php
					
				//$query="select id,nombre,concat(hour(fecha),':',minute(fecha)) from agenda where DAYOFMONTH(fecha)='".$semana[$k][$j]."' and month(fecha)='".$mes."'";

				//$query="select codigo,concat(hour(fecha_programada),':',minute(fecha_programada)) from ot_ordenes where DAYOFMONTH(fecha_programada)='".$semana[$k][$j]."' and month(fecha_programada)='".$mes."' and year(fecha_programada)=$ano order by fecha_programada limit 0,5";
				
				$query="select distinct  hour(a.fecha_programada),minute(a.fecha_programada),c.nombre,a.codigo,a.orden_padre_id  from ot_ordenes a left join  ot_visitas b on a.ordenes_id=b.ordenes_id left join seg_usuarios c on b.asignada_id=c.id where DAYOFMONTH(fecha_programada)='".$semana[$k][$j]."' and month(fecha_programada)='".$mes."' and year(fecha_programada)=$ano order by fecha_programada limit 0,5";
				
				$matrizx=db_ejecutar_consulta($query);
				echo "<table width=150><tr><td>";
				for($x=0;$x<sizeof($matrizx);$x++){
//					$eve_id= $matrizx[$x][0];
//					$eve_nombre= $matrizx[$x][1];
//					$eve_hora= $matrizx[$x][1];

					//						echo "<b>$eve_hora-<a href=evento_editar.php?id=$eve_id>$eve_nombre</a><br><br>";			$eve_hora= $matrizx[$x][0];
					$eve_hora= $matrizx[$x][0];
					$eve_minuto= $matrizx[$x][1];
					$eve_nombre= $matrizx[$x][2];
					if($eve_minuto=="0"){
						$eve_minuto="00";
					}
					$oth= $matrizx[$x][3];
					$otp= $matrizx[$x][4];
					//echo "<font size=-2><b>$eve_hora</b>  $eve_id<br>";		
					if($otp!=""){
						echo "<tr class=\"etiquetaformulario\"><td width=40><font size=-2><b> $eve_hora : $eve_minuto</b></td><td> $eve_nombre</td></tr>";	
					}
				}
				echo "</td></tr></table>";
			?>

				</div></td>
	<?php
		}	
			echo "</tr>";
	}		
	?>

				  </table></td>
			  </tr>
			</table>
		  </div></td>
	  </tr>
	  <tr> 
		<td><div align="center"></div></td>
	  </tr>
	</table>
<?php
}

function daysInMonth($month = 0, $year = ''){
 $days_in_month    = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
 $d = array("Jan" => 31, "Feb" => 28, "Mar" => 31, "Apr" => 30, "May" => 31, "Jun" => 30, "Jul" => 31, "Aug" => 31, "Sept" => 30, "Oct" => 31, "Nov" => 30, "Dec" => 31);
 if(!is_numeric($year) || strlen($year) != 4) $year = date('Y');
 if($month == 2 || $month == 'Feb'){
  if(leapYear($year)) return 29;
 }
 if(is_numeric($month)){
  if($month < 1 || $month > 12) return 0;
  else return $days_in_month[$month - 1];
 }
 else{
  if(in_array($month, array_keys($d))) return $d[$month];
  else return 0;
 }
} 

function calendario_semana($ano,$mes,$dia){
	global $zonas_id;
	global $ciudades_id;
	global $proyectos_id;

	if($zonas_id==""){
		$zonas_id=0;
	}
	if($ciudades_id==""){
		$ciudades_id=0;
	}
	if($proyectos_id==""){
		$proyectos_id=0;
	}

	//echo $zonas_id;
	echo "<script language='JavaScript'>".chr(10).chr(13);
	echo "function refrescar_combo(){".chr(10).chr(13);
	echo "document.form1.guardar.value='N';".chr(10).chr(13);
	echo "document.form1.submit()".chr(10).chr(13);
	echo "}".chr(10).chr(13);
	echo "function mostrar_ot(id){".chr(10).chr(13);
	echo "window.opener.location=('visitas_ver.php?id='+id);";
	echo "}".chr(10).chr(13);

	echo "</script>".chr(10).chr(13);



	// $query="select codigo,concat(hour(fecha_programada),':',minute(fecha_programada)) from ot_ordenes where DAYOFMONTH(fecha_programada)='".$dia."' and month(fecha_programada)='".$mes."' and year(fecha_programada)=$ano order by fecha_programada ";


	echo "<form name=form1 method=POST><input type='hidden' name='guardar' value='S'>";
	echo "<input type='hidden' name='dia' value='$dia'>";
	echo "<input type='hidden' name='mes' value='$mes'>";
	echo "<input type='hidden' name='ano' value='$ano'>";
	echo "<table border=0 width=90%>";
//	echo $dia;
	$dia_numero_actual=date("N",mktime(0, 0, 0, $mes, $dia, $ano));
	$fecha_dia_actual=date("j");
	$primer_dia=date("j",mktime(0, 0, 0, $mes, $dia-$dia_numero_actual+1, $ano));
	$dia2=$primer_dia+6;
	if($primer_dia<0){
		$dia_mostrar=1;
	}else{
		$dia_mostrar=$primer_dia;
	}
	$dia_semana_anterior=date("j",mktime(0, 0, 0, $mes, $primer_dia-7, $ano));
	if($dia_semana_anterior+7>30){
		$mes_semana_anterior=$mes-1;
	}else{
		$mes_semana_anterior=$mes;
	}

	$dia_semana_proxima=date("j",mktime(0, 0, 0, $mes, $primer_dia+7, $ano));
	if($dia_semana_proxima<7){
		$mes_semana_proximo=$mes+1;
	}else{
		$mes_semana_proximo=$mes;
	}



	echo "<tr class=\"datoformulario\"><td colspan=4><font size=-2><b>Agenda para la semana ";
	//echo " entre $ano-$mes-$dia_mostrar y $ano-$mes-".$dia2."</b>"
	echo 	"</td></tr>";	
	echo "<tr class=\"datoformulario\"><td colspan=2><font size=-2><b>Zona</b></td><td colspan=2>";
	echo "<select name=zonas_id class=etiquetaformulario  onchange=refrescar_combo()>";

		echo "<option  value=''>Seleccione</option>";
		$query_cats="select zonas_id,nombre from ot_zonas order by nombre";
		$matriz_cats=db_ejecutar_consulta($query_cats);
		for($i=0;$i<sizeof($matriz_cats);$i++){
			$permiso_id=$matriz_cats[$i][0];
			$permiso_nombre=$matriz_cats[$i][1];
			if($permiso_id==$zonas_id){
				$selected=" selected ";
			}else{
				$selected="   ";	
			}
			echo "<option ".$selected." value=$permiso_id>".$permiso_nombre."</option>";
		}
	echo "</td></tr>";	
	echo "<tr class=\"datoformulario\"><td colspan=2><font size=-2><b>Ciudad</b></td><td colspan=2>";
	echo "<select name='ciudades_id' class='etiquetaformulario' >";

		
		$query_cats="select ciudades_id,nombre from ot_ciudades where zonas_id='$zonas_id' order by nombre";
		$matriz_cats=db_ejecutar_consulta($query_cats);
		for($i=0;$i<sizeof($matriz_cats);$i++){
			$permiso_id=$matriz_cats[$i][0];
			$permiso_nombre=$matriz_cats[$i][1];
			if($permiso_id==$ciudades_id){
				$selected=" selected ";
			}else{
				$selected="   ";	
			}
			echo "<option ".$selected." value=$permiso_id>".$permiso_nombre."</option>";
		}

	echo "</td></tr>";	
	echo "<tr class=\"datoformulario\"><td colspan=2><font size=-2><b>Servicio</b></td><td colspan=2>";
	echo "<select name='proyectos_id' class='etiquetaformulario' >";

		
		$query_cats="select proyectos_id,nombre from ot_proyectos where proyectos_id in(1,3) order by 1";
		$matriz_cats=db_ejecutar_consulta($query_cats);
		echo "<option  value=0>Instalacion y Mantenimiento</option>";
		for($i=0;$i<sizeof($matriz_cats);$i++){
			$permiso_id=$matriz_cats[$i][0];
			$permiso_nombre=$matriz_cats[$i][1];
			if($permiso_id==$proyectos_id){
				$selected=" selected ";
			}else{
				$selected="   ";	
			}
			echo "<option ".$selected." value=$permiso_id>".$permiso_nombre."</option>";
		}
	echo "</td></tr>";

echo "<tr class=\"datoformulario\"><td colspan=2><font size=-2><b>PIM</b></td><td colspan=2>";
	echo "<select name='pim' class='etiquetaformulario' >";
	echo "<option  value=\"\">Todos</option>";
		if($pim==""){
			if(es_pim($_COOKIE['usuario_id'])){
				$pim=$_COOKIE['usuario_id'];				
			}else{
				$pim=$_POST['pim'];					
			}

		}

		$query_cats="select id,nombre from seg_usuarios order by 2";
		$matriz_cats=db_ejecutar_consulta($query_cats);
		for($i=0;$i<sizeof($matriz_cats);$i++){
			$permiso_id=$matriz_cats[$i][0];
			$permiso_nombre=$matriz_cats[$i][1];
			if($permiso_id==$pim){
				$selected=" selected ";
			}else{
				$selected="   ";	
			}
			echo "<option ".$selected." value=$permiso_id>".$permiso_nombre."</option>";
		}
	echo "</td></tr>";
	echo "<tr class=\"datoformulario\"><td colspan=8><font size=-2 color=000000><b>  - Instalacion</b><font size=-2 color=FFFFFF><b> -  Mantenimiento</b><font size=-2 color=AAAAAA><b>  - Infraestructura</b></td><td colspan=2>";
	
	echo "</td></tr>";
	echo "<tr class=\"datoformulario\"><td colspan=4><font size=-2><b><input type=submit value='Buscar' name='buscar'></b></td></tr>";
	echo "<tr class=\"datoformulario\"><td colspan=4 align=center><font size=-2><b><a href='agenda_nueva_listar.php?dia=$dia_semana_anterior&mes=$mes_semana_anterior&zonas_id=$zonas_id'><<</a>&nbsp;&nbsp;<a href=#>Semana</a>&nbsp;&nbsp;<a href='agenda_nueva_mes.php'>Mes</a>&nbsp;&nbsp;<a href='agenda_nueva_listar.php?dia=$dia_semana_proxima&mes=$mes_semana_proximo&zonas_id=$zonas_id'>>></a></b></td></tr>";

	echo "<tr class=\"datoformulario\"><td colspan=4 ><table border=0 cellspacing=1 cellpadding=1>		<tr class=\"datoformulario\">";
	echo "<td class=\"datoformulario\">Intervalo</td>";
	$dias[1]= "Lunes";
	$dias[2]= "Martes";
	$dias[3]= "Miercoles";
	$dias[4]= "Jueves";
	$dias[5]= "Viernes";
	$dias[6]= "Sabado";
	$dias[7]= "Domingo";
	$num_dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
	$dias_numero=$primer_dia;
	for($i=1;$i<8;$i++){
		if($dias_numero==$num_dias_mes+1){
			$dias_numero=1;
		}
		?>

		<td width=200 bgcolor=FFFFFF
		
		
		><div align="center"><b> 

		<?php

		if($fecha_dia_actual==$dias_numero){
			echo "<table border=1 width=100%><tr><td align=center>";
		
		}
			
		?>
		<a href="#" onclick="abrir_detalle(<?php echo $ano?>,<?php echo $mes?>,<?php echo $dias_numero?>,<?php echo $zonas_id?>,<?php echo $ciudades_id?>,<?php echo $proyectos_id?>)"><?php echo $dias[$i]?>-<?php echo $dias_numero?>
		<?php

		if($fecha_dia_actual==$dias_numero){
			echo "</td></tr></table>";
			$borde=" border=1";
		
		}else{
			$borde=" border=0";
		}
			
		?>



		</div></td>
	<?
		$dias_numero++;
		

	}		
	?>
	</tr>
	

	<?php

//	for($k=0;$k<24;$k++){
	for($k=6;$k<20;$k++){
		echo "<tr valign=top class=\"datoformulario\"><td class=\"datoformulario\">$k:00 - $k:59</td>";
		$dias_numero=$primer_dia;
		for($j=1;$j<8;$j++){
			if($dias_numero==$num_dias_mes+1){
				$dias_numero=1;
				$mes++;
			}
			if($fecha_dia_actual==$dias_numero){
				$borde=" border=1";
			
			}else{
				$borde=" border=0";
			}

			echo "<td><table $borde >";
		$query="select distinct  hour(a.fecha_programada),minute(a.fecha_programada),c.nombre,a.codigo,a.orden_padre_id,a.proyectos_id,b.visitas_id,d.nombre  from ot_ordenes a left join  ot_visitas b on a.ordenes_id=b.ordenes_id left join seg_usuarios c on b.asignada_id=c.id join ot_ciudades d on a.ciudades_id=d.ciudades_id where DAYOFMONTH(fecha_programada)='".$dias_numero."' and month(fecha_programada)='".$mes."' and year(fecha_programada)=$ano and hour(a.fecha_programada) between '$k:00' and '$k:59' ";


				if($zonas_id!=""){
					if($zonas_id!=1000){
						$query.=" and a.zonas_id='$zonas_id' ";			
					}else{
						$query.=" and a.zonas_id in (6,7) ";
					}

				}
				if($ciudades_id!=""){
					$query.=" and a.ciudades_id='$ciudades_id' ";
				}
				if($proyectos_id!=""){
					if($proyectos_id!=0){
						$query.=" and a.proyectos_id='$proyectos_id' ";
					}else{
						$query.=" and a.proyectos_id in (1,3)";
					}


				}
				
//				echo "**$pim***";
				if($pim!=""){
					$query.=" and b.asignada_id='$pim' ";
				}



				$query.=" order by fecha_programada ";

//			echo $query;
				$matrizx=db_ejecutar_consulta($query);



			if($matrizx[0][0]!=""){
				echo "<tr class=\"datoformulario\"><td width=45><font size=-2><b>HORA</b></td><td><font		size=-2><b>PIM</td><td><font size=-2><b>OTP</td><td><font size=-2> <b>CIUDAD</td></tr>";
			
			}

			echo "<tr><td colspan=4>";

				echo "<table heigth=200>";
				
			//	if(sizeof($matrizx)==1){
				//	echo "<tr class=\"etiquetaformulario\"><td colspan=4><font size=-2><b>NO HAY ACTIVIDADES PROGRAMADAS</td></tr>";	
			//	}else{
					for($x=0;$x<sizeof($matrizx);$x++){
						$eve_hora= $matrizx[$x][0];
						$eve_minuto= $matrizx[$x][1];
						$eve_nombre= $matrizx[$x][2];
						$tipo_servicio= $matrizx[$x][5];
						$visitas_id= $matrizx[$x][6];
						$ciudad=$matrizx[$x][7];

						if($eve_minuto=="0"){
							$eve_minuto="00";
						}
						$oth= $matrizx[$x][3];
						$otp= $matrizx[$x][4];
						if($eve_hora<12){
							$fondo="8888BB";
						}
						if(($eve_hora>11)and($eve_hora<15)){
							$fondo="BB8888";
						}

						if($eve_hora>14){
							$fondo="88BB88";
						}
						switch($tipo_servicio){
							case 1:
								$color="FFFFFF";
							break;
							case 3:
								$color="000000";
							break;
							default:
								$color="AAAAAA";
							break;

						}

						if($oth==""){
							echo "<tr class=\"etiquetaformulario\"><td colspan=4></td></tr>";		
					
						}else{
							echo "<tr class=\"etiquetaformulario\"><td width=40 bgcolor=\"$fondo\"><font size=-2 color=$color><b> $eve_hora:$eve_minuto</b></td><td bgcolor=\"$fondo\"> <font size=-2 color=$color>$eve_nombre</td><td bgcolor=\"$fondo\"><a href='ordenes_listar.php?buscar_por=codigo_padre&campo_buscar=$otp&guardar=S'><font size=-2 color=$color> $otp</a></td><td bgcolor=\"$fondo\"> <font size=-2 color=$color>";
		//					echo "<a href=\"#\" onclick=\"mostrar_ot('$visitas_id')\">";
							echo $ciudad. "</a></td></tr>";		
						
						}	
					}	
				echo "</td></tr></table>";
			//}
			
			
			

			echo "</table></td>";
			$dias_numero++;
		}
		echo "</tr>";
	}
	
	
	echo "</table></td></tr>";	
	echo "</table>";
}

?>
