<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
?>
<script type='text/javascript' src='../js/admin_rutas.js'></script>
<br>
<form id="form">
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2" class="titulo_tabla">CREAR RUTA</td>
	</tr>
	<tr>
		<td colspan="2" style="background: #FFF; border: 1px solid #CCC;"><img
			src="imagenes/iconos/save_a.png" alt="guardar" title="Guardar"
			class="img_boton" onmouseover="this.src = 'imagenes/iconos/save.png'"
			onmouseout="this.src = 'imagenes/iconos/save_a.png'"
			onclick="guardar(this.form)"/>&nbsp;<img
			src="imagenes/iconos/cancel_a.png" alt="cancelar" title="Cancelar"
			class="img_boton"
			onmouseover="this.src = 'imagenes/iconos/cancel.png'"
			onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
			onclick="AjaxConsulta('../presentacion/admin_transacciones.php', '', 'area_trabajo');" /></td>
	</tr>
	<tr>
		<td width="25%" class="subtitulo_tabla">Nombre:</td>
		<td width="25%" class="texto_tabla"><input type="text" id="nombre" class = "campo" size="30" value=""/>*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Descripci&oacute;n:</td>
		<td class="texto_tabla"><input type="text" id="descripcion" class="campo" size="30" value=""/></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">JVA:</td>
		<td><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_PADRE:'admin_jva', VALOR_REGISTRO_PADRE:'0', NOMBRE_CAMPO_PADRE:'nombre', DIV_PADRE:'jvaDiv', NOMBRE_SELECT_PADRE:'sjva', DIV_HIJO:'vendedorJVA', NOMBRE_SELECT_HIJO:'svededoresjva', ACCION:'consultar_campo_padre_JVA'}, 'jvaDiv');</script><div id="jvaDiv"></div></td>	
	</tr>
	<tr>
		<td class="subtitulo_tabla">Vendedor:</td>
		<td class="subtitulo_tabla" style="text-align: left"><div id="vendedorJVA"></div></td>
	</tr>
	</table>
<br>
</form>