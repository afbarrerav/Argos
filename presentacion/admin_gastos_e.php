<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
if($msg !="")
{
	?>
	<script>
		alert('<?php echo $msg;?>');
	</script>
	<?php
}
?>
<script type='text/javascript' src='../js/admin_gastos.js'></script>
<script type='text/javascript'>
$(document).ready(function() {
	   $("#fecha_gasto").datepicker();
	   $("#fecha_trans").datepicker();
});
</script>
<br>
<form id="form">
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2" class="titulo_tabla">EDITAR GASTO</td>
	</tr>
	<tr>
		<td colspan="2" style="background: #FFF; border: 1px solid #CCC;"><img
			src="imagenes/iconos/save_a.png" alt="guardar" title="Guardar"
			class="img_boton" onmouseover="this.src = 'imagenes/iconos/save.png'"
			onmouseout="this.src = 'imagenes/iconos/save_a.png'"
			onclick="editar_guardar(this);"/>&nbsp;<img
			src="imagenes/iconos/cancel_a.png" alt="cancelar" title="Cancelar"
			class="img_boton"
			onmouseover="this.src = 'imagenes/iconos/cancel.png'"
			onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
			onclick="AjaxConsulta( '../logica/admin_gastos.php', {ACCION:'mostrar_front'}, 'area_trabajo');" /></td>
	</tr>
	<tr>
		<td width="25%" class="subtitulo_tabla"><input type="hidden" id="codigo" value="<?php echo $codigo?>" />Codigo:</td>
		<td width="25%" class="texto_tabla"><strong><?php echo $codigo?></strong></td>	
	</tr>
	<tr>
		<td class="subtitulo_tabla">Fecha Gasto:</td>
		<td class="texto_tabla"><input type="text" id="fecha_gasto" class="campo" size="15" value="<?php echo $fecha_gasto?>"/>*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Vendedor:</td>
		<!-- SE CREA UNA NUEVA OPCION EN PROC SELECT TABLA PARA OBTENER EL AJU CODIGO Y EL NOMBRE DEL USUARIO DEL JVA LLAMADA admin_jva_usuarios_nombres -->
		<td class="texto_tabla"><script>AjaxConsulta('../logica/proc_select_tabla.php', {VALOR_CONDICION:'<?php echo $jva?>', DIV:'admin_usuario_jva_Div', NOMBRE_SELECT:'sVendedores2', VALOR_REGISTRO:'<?php echo $usu_codigo?>', ACCION:'consultar_campo_hijo_JVA'}, 'admin_usuario_jva_Div');</script><div id="admin_usuario_jva_Div"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Tipo Gasto:</td>
		<td class="texto_tabla"><script>AjaxConsulta('../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'param_gastos_jva', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'<?php echo $pgj_codigo?>', CODIGO_REGISTRO:'<?php echo $pgj_codigo?>',  DIV:'pgjDiv', NOMBRE_SELECT:'spgj', ESTADO:'', ACCION:'consultar_campo'}, 'pgjDiv');</script><div id="pgjDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Valor:$</td>
		<td class="texto_tabla"><input type="text" id="valor" class = "campo" size="20"  value="<?php echo $valor?>"/>*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Fecha Transacci&oacute;n:</td>
		<td class="texto_tabla"><input type="text" id="fecha_trans" class="campo" size="15" value="<?php echo $fecha_trans?>"/>*</td>
	</tr>
	</table>
<br>
</form>