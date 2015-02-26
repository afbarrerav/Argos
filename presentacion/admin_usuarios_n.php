<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 *
 * */
include_once ("../logica/variables_session.php");

?>
<br>
<script type='text/javascript' src='../js/admin_usuarios.js'></script>
<script type='text/javascript'>
$(document).ready(function() {
	   $("#fecha_nacimiento").datepicker();
});
</script>
<form>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2">
			<table border="0" width="100%" align="center" style="background: #FFF; border: 0px solid #CCC;">
				<tr height="30">
					<td class="titulo_tabla">Crear nuevo usuario</td>
				</tr>
				<tr>
					<td colspan="4" style="background: #FFF; border: 1px solid #CCC;">
						<img src="imagenes/iconos/save_a.png" alt="Crear nuevo usuario" title="Crear nuevo usuario"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/save.png'"
							onmouseout="this.src = 'imagenes/iconos/save_a.png'"
							onclick="guardar(this.form);"/>
							<img src="imagenes/iconos/cancel_a.png" alt="Cancelar" title="Cancelar"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/cancel.png'"
							onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
							onclick="AjaxConsulta( '../logica/admin_usuarios.php', {ACCION:'listar'}, 'area_trabajo' );"/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Tipo documento de identidad</td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_identificaciones', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'descripcion', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'tiDiv', NOMBRE_SELECT:'sti', ESTADO:'', ACCION:'consultar_campo'}, 'tiDiv' );</script><div id="tiDiv"></div></td>
	</tr>
	<tr>
		<td width = "50%" class="subtitulo_tabla"># Documento de identidad</td>
		<td width = "50%" align="left"><input type="text" id="identificacion" class="campo">*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Nombres</td>
		<td align="left"><input type="text" id="nombres" class="campo">*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Apellidos</td>
		<td align="left"><input type="text" id="apellidos" class="campo">*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Fecha de nacimiento</td>
		<td align="left"><input type="text" id="fecha_nacimiento" class = "campo"  size="12"  value=""/>*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Genero</td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_generos', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'descripcion', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'genDiv', NOMBRE_SELECT:'sgen', ESTADO:'', ACCION:'consultar_campo'}, 'genDiv' );</script><div id="genDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Ciudad</td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_ciudades', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'ciuDiv', NOMBRE_SELECT:'sciu', ESTADO:'', ACCION:'consultar_campo'}, 'ciuDiv' );</script><div id="ciuDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Direcci&oacute;n</td>
		<td align="left"><input type="text" id="direccion" class="campo"></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Telefono</td>
		<td align="left"><input type="text" id="telefono" class="campo"></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">e-mail</td>
		<td align="left"><input type="text" id="mail" class="campo">*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Username</td>
		<td align="left"><span id="validar_username"><input type="hidden" id="username_valido" value="NO"></span><input type="text" id="username" class="campo" onKeyUp="validar_username(this.value)">*</td>
	</tr>
	<?php 
	if ($rol_codigo == 1)
	{	
	?>
	<tr>
		<td class="subtitulo_tabla">JVA</td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'admin_jva', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'jvaDiv', NOMBRE_SELECT:'sjva', ESTADO:'', ACCION:'consultar_campo'}, 'jvaDiv' );</script><div id="jvaDiv"></div></td>
	</tr>
	<?php 
	}
	else
	{
	?>
	<tr>
		<td class="subtitulo_tabla">JVA</td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'admin_jva', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'jvaDiv', NOMBRE_SELECT:'sjva', ESTADO:'', ACCION:'consultar_campo_jva'}, 'jvaDiv' );</script><div id="jvaDiv"></div></td>
	</tr>
	<?php 
	}
	?>
	<tr>
		<td class="subtitulo_tabla">Rol</td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'admin_roles', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'descripcion', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'rolDiv', NOMBRE_SELECT:'srol', ESTADO:'', ACCION:'consultar_campo'}, 'rolDiv' );</script><div id="rolDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Partipipaci&oacute;n</td>
		<td align="left"><input type="text" id="participacion" class="campo" value="0"></td>
	</tr>
</table>
</form>
<br>