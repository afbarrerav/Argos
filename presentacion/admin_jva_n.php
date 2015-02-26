<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 *
 * */

?>
<br>
<script type='text/javascript' src='../js/admin_jva.js'></script>
<script type='text/javascript'>
$(document).ready(function() {
	   $("#fecha_creacion").datepicker();
});
</script>
<form>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2">
			<table border="0" width="100%" align="center" style="background: #FFF; border: 0px solid #CCC;">
				<tr height="30">
					<td class="titulo_tabla">Crear nuevo JVA</td>
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
							onclick="AjaxConsulta( '../logica/admin_jva.php', {ACCION:'listar'}, 'area_trabajo' );"/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width = "50%" class="subtitulo_tabla">Nombre</td>
		<td width = "50%" align="left"><input type="text" id="nombre" class="campo">*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Descripci&oacute;n</td>
		<td align="left"><input type="text" id="descripcion" class="campo"></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Distribuidor</td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'admin_distribuidores', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'Dis_codigoDiv', NOMBRE_SELECT:'sdis', ESTADO:'', ACCION:'consultar_campo'}, 'Dis_codigoDiv' );</script><div id="Dis_codigoDiv"></div></td>
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
		<td class="subtitulo_tabla">Fecha de creaci&oacute;n</td>
		<td align="left"><input type="text" id="fecha_creacion" class="campo"></td>
	</tr>
</table>
</form>
<br>