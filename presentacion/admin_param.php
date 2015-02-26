<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 *
 * */
?>
<script type='text/javascript' src='../js/admin_param.js'></script>
<br>
<form>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td class="titulo_tabla">Parametrizar operaci&oacute;n</td>
	</tr>
	<tr>
		<td class="texto_tabla">Esta opci&oacute;n le permite Parametrizar el contenido de las tablas maestras, estas tablas son la que definen la operaci&oacute;n para cada JVA. Por favor seleccione la tabla maestra que desea administrar y haga clic en el boton Parametrizar.<br><br></td>
	</tr>
	<tr>
		<td colspan="4" class="titulo_tabla">Selecci&oacute;n</td>
	</tr>
	<tr>
		<td align="center">
			<select id="sadministarIC" class="campo_desplegable">
				<option value="0">-SELECCIONE-</option>
				<!-- <option value="param_barrios_localidades">BARRIOS </option> -->
				<option value="param_categorias_productos_jva">CATEGORIAS</option>
				<option value="param_localidades_jva">LOCALIDADES</option>
				<option value="param_numero_cuotas_jva">NUMERO DE CUOTAS DE RECAUDO</option>
				<option value="param_periodicidad_jva">PERIODICIDAD DEL RECUADO</option>
				<option value="param_productos_jva">PRODUCTOS</option>
				<option value="param_gastos_jva">TIPOS DE GASTOS</option>
				<option value="param_tipos_productos_jva">TIPOS DE PRODUCTOS</option>
				<option value="param_traslados_inventarios_jva">TRASLADOS DE INVENTARIO</option>	
				<option value="param_salarios_jva">SALARIOS JVA</option>						
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="button" name="bt1" value='Parametrizar'
			class="boton" title="Haga clic para Parametrizar el contenido de la tabla seleccionada"
			onclick='administrarIC();'>
		</td>
	</tr>
	
</table>
</form>
<div id="administarICDiv"></div>
<br>