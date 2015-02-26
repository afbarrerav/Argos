<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 *
 * */
?>
<script type='text/javascript' src='../js/admin_ic.js'></script>
<br>
<form>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td class="titulo_tabla">Informaci&oacute;n</td>
	</tr>
	<?php 
	if($accion == "mostrar_front_tipos")
	{
	?>	
	<tr>
		<td class="texto_tabla">Esta opci&oacute;n permite administrar el contenido de las tablas maestras TIPOS para la plataforma ARGOS. Por favor seleccione la tabla maestra que desea administrar y haga clic en el boton administrar.<br><br></td>
	</tr>
	<?php 
	}
	if($accion == "mostrar_front_param")
	{
	?>
	<tr>
		<td class="texto_tabla">Esta opci&oacute;n permite administrar el contenido de las tablas maestras PARAM para la plataforma ARGOS. Por favor seleccione la tabla maestra que desea administrar y haga clic en el boton administrar.<br><br></td>
	</tr>
	<?php 
	}
	?>		
	<tr>
		<td colspan="4" class="titulo_tabla">Seleccione Tabla</td>
	</tr>
	<tr>
		<td align="center">
			<select id="sadministarIC" class="campo_desplegable">
				<option value="0">-SELECCIONE-</option>
			<?php 
			if($accion == "mostrar_front_tipos")
			{
			?>	
				<option value="tipos_ciudades">CIUDADES</option>
				<option value="tipos_departamentos">DEPARTAMENTOS</option>
				<option value="tipos_estados">ESTADOS</option>
				<option value="tipos_gastos">GASTOS</option>
				<option value="tipos_generos">GENEROS</option>
				<option value="tipos_paises">PAISES</option>				
				<option value="admin_roles">ROLES</option>
				<option value="trans_rutas_jva">RUTAS</option>
				<option value="tipos_ventas">TIPOS DE VENTA</option>
				<option value="tipos_autenticaciones">TIPOS DE AUTENTICACIONES</option>
				<option value="tipos_dispositivos">TIPOS DE DISPOSITIVOS</option>
				<option value="tipos_identificaciones">TIPOS DE IDENTIFICACION</option>
				<option value="tipos_unidades_medida">TIPOS DE UNIDADES DE MEDIDA</option>
				<option value="tipos_negocios">TIPOS DE NEGOCIOS</option>
			<?php 
			}
			if($accion == "mostrar_front_param")
			{
				?>
				<option value="param_categorias_productos_jva">CATEGORIAS</option>
				<option value="param_tipos_tran_inventarios_jva">INVENTARIOS JVA</option>
				<option value="param_tipos_productos_jva">TIPOS DE PRODUCTOS</option>
				<option value="param_estados_tipos_ventas">TIPOS DE VENTA</option>	
				<option value="param_tipos_gastos_jva">GASTOS JVA</option>
				<option value="param_tipos_pagos_jva">PAGOS JVA</option>
				<option value="param_tipos_productos_jva">PRODUCTOS JVA</option>		
				<?php 
			}
			?>				
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="button" name="bt1" value='Administrar'
			class="boton" title="Haga clic para administrar el contenido de la tabla seleccionada"
			onclick='administrarIC();'>
		</td>
	</tr>
	
</table>
</form>
<div id="administarICDiv"></div>
<br>