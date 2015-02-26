<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 *
 * */
?>
<script type='text/javascript' src='../js/admin_inventarios.js'></script>
<br>
<form>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2" class="titulo_tabla">Informaci&oacute;n</td>
	</tr>
	<tr>
		<td colspan="2"  class="texto_tabla">Esta opci&oacute;n permite conocer la cantidad de existencias por producto en cada bodega. Para consultar todas las bodegas por favor no seleccione ninguna bodega y haga clic en administrar<br><br></td>
	</tr>
	<tr>
		<td colspan="2" class="titulo_tabla">Seleccione Bodega</td>
	</tr>
	<tr>
		<td width="50%" class="subtitulo_tabla">JVA:</td>
		<td width="50%"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_PADRE:'admin_jva', VALOR_REGISTRO_PADRE:'0', NOMBRE_CAMPO_PADRE:'nombre', DIV_PADRE:'jvaDiv', NOMBRE_SELECT_PADRE:'sjva', DIV_HIJO:'vendedorJVA', NOMBRE_SELECT_HIJO:'svededoresjva', ACCION:'consultar_campo_padre_JVA'}, 'jvaDiv');</script><div id="jvaDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Vendedor:</td>
		<td class="subtitulo_tabla" style="text-align: left"><div id="vendedorJVA"></div></td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="button" name="bt1" value='Consultar'
			class="boton" title="Haga clic para Consultar el inventario de la bodega seleccionada"
			onclick='ConsultarInventario();'>
		</td>
	</tr>
	
</table>
</form>
<div id="inventarioDiv"></div>
<br>