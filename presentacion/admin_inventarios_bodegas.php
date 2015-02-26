<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 *
 * */
?>
<script type='text/javascript' src='../js/admin_inventarios_bodegas.js'></script>
<br>
<form>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td class="titulo_tabla">Informaci&oacute;n</td>
	</tr>
	<tr>
		<td class="texto_tabla">Esta opci&oacute;n permite conocer y administrar la cantidad de existencias por producto en cada bodega. Para consultar todas las bodegas por favor no seleccione ninguna bodega y haga clic en administrar<br><br></td>
	</tr>
	<tr>
		<td colspan="4" class="titulo_tabla">Seleccione Bodega</td>
	</tr>
	<tr>
		<td align="center">
			<script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'bodegaDiv', NOMBRE_SELECT:'sbodega', ESTADO:'', ACCION:'consultar_campo_bod'}, 'bodegaDiv' );</script><div id="bodegaDiv"></div>
		</td>
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