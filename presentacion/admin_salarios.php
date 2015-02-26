<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Diciembre de 2012
 *
 * */
?>
<script type='text/javascript' src='../js/admin_salarios.js'></script>
<script type='text/javascript'>
$(document).ready(function() {
	   $("#fecha_inicio").datepicker();
	   $("#fecha_fin").datepicker();
});
</script>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="12">
			<table border="0" width="100%" align="center" style="background: #FFF; border: 0px solid #CCC;">
				<tr height="30">
					<td class="titulo_tabla">Informaci&oacute;n Importante.</td>
				</tr>
				<tr height="30">
					<td class="texto_tabla">Seleccione el JVA, la fecha de inicio y la fecha fin y de click en el boton Generar Liquidacion para liquidar el salario de todos, si no Seleccion un vendedor y se hara solamente a este.</td>
				</tr>
				<tr height="30">
					<td class="titulo_tabla">Generar Reportes</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">JVA:</td>
		<td><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_PADRE:'admin_jva', VALOR_REGISTRO_PADRE:'0', NOMBRE_CAMPO_PADRE:'nombre', DIV_PADRE:'jvaDiv', NOMBRE_SELECT_PADRE:'sjva', DIV_HIJO:'vendedorJVA', NOMBRE_SELECT_HIJO:'svededoresjva', ACCION:'consultar_campo_padre_JVA'}, 'jvaDiv');</script><div id="jvaDiv"></div></td>
		<td class="subtitulo_tabla">Usuario:</td>
		<td class="subtitulo_tabla" style="text-align: left"><div id="vendedorJVA"></div></td>
		<td class="subtitulo_tabla">Inicio:</td>
		<td align="left"><input type="text" size="25" id="fecha_inicio" class="campo"></td>
		<td class="subtitulo_tabla">Fin:</td>
		<td align="left"><input type="text" size="25" id="fecha_fin" class="campo"></td>
	</tr>
	<tr>
		<td colspan="10" align="center"><input type="button" name="bt1" value='Consultar Liquidaci&oacute;n'
			class="boton" title="Haga clic para consultar la liquidacion del salario"
			onclick="cosultar_liquidacion();"></td>
	</tr>
</table>
<br>
<div id="rpt_LiquidacionDiv"></div>