<?php
/*
 * @author: MIGUEL POSADA
 * 			miguelrodriguezpo@hotmail.com
 * @version:2.0.0
 * @fecha:	Diciembre de 2012
 *
 * */
?>
<script type="text/javascript">
$(document).ready(function(){
	$('#Detalle_Vendedor').dialog({width:700, height:250, modal:true, title:'Detalle Vendedor', close:function(ev, ui){$(this).remove();}});
});
</script>
<div id="Detalle_Vendedor">
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px;">
		<tr>
			<td class="titulo_tabla" colspan="12">Detallado Vendedor</td>
		</tr>
		<tr>
			<td class="titulo_tabla">Codigo</td>
			<td class="titulo_tabla">t identificaci&oacute;n</td>
			<td class="titulo_tabla">identificaci&oacute;n</td>
			<td class="titulo_tabla">nombre</td>
			<td class="titulo_tabla">fecha nacimiento</td>
			<td class="titulo_tabla">genero</td>
		</tr>
		<tr>
			<td class="texto_tabla"><?php echo $codigo?></td>
			<td class="texto_tabla">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'tipos_identificaciones', CAMPO:"descripcion", CONDICION:'<?php echo $ti_codigo?>', ACCION:'consultar_valor_campo'}, 'Div_ti_codigo');</script>
				<div id="Div_ti_codigo" style="width: 160px;"></div>	
			</td>
			<td class="texto_tabla"><?php echo $nro_identificacion?></td>
			<td class="texto_tabla"><?php echo $NOMBRES?></td>
			<td class="texto_tabla"><?php echo $fecha_nacimiento?></td>
			<td class="texto_tabla">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'tipos_generos', CAMPO:"descripcion", CONDICION:'<?php echo $gen_codigo?>', ACCION:'consultar_valor_campo'}, 'DivGenero');</script>
				<div id="DivGenero" style="width: 160px;"></div>	
			</td>
		</tr>
</table>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px;">
	<tr>
		<td class="titulo_tabla">Ciudad</td>
		<td class="titulo_tabla">Direcci&oacute;n</td>
		<td class="titulo_tabla">Telefono</td>
		<td class="titulo_tabla">Email</td>
		<td class="titulo_tabla">Username</td>
		<td class="titulo_tabla">Estado</td>
	</tr>
	<tr>
		<td class="texto_tabla">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'tipos_ciudades', CAMPO:"nombre", CONDICION:'<?php echo $ciu_codigo?>', ACCION:'consultar_valor_campo'}, 'DivCiudad');</script>
				<div id="DivCiudad"></div>
		</td>
		<td class="texto_tabla"><?php echo $direccion?></td>
		<td class="texto_tabla"><?php echo $telefono?></td>
		<td class="texto_tabla"><?php echo $email?></td>
		<td class="texto_tabla"><?php echo $username?></td>
		<td class="texto_tabla">
			<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'tipos_estados', CAMPO:"nombre", CONDICION:'<?php echo $est_codigo?>', ACCION:'consultar_valor_campo'}, 'DivEstado');</script>
			<div id="DivEstado"></div>
		</td>
	</tr>
</table>
</div>