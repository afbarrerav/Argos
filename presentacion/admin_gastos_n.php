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
$fecha_gasto = date('Y-m-d');
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
		<td colspan="2" class="titulo_tabla"><?php echo $jva_nombre?> - CREAR GASTO PARA <?php echo $usu_nombre?></td>
	</tr>
	<tr>
		<td colspan="2" style="background: #FFF; border: 1px solid #CCC;"><img
			src="imagenes/iconos/save_a.png" alt="guardar" title="Guardar"
			class="img_boton" onmouseover="this.src = 'imagenes/iconos/save.png'"
			onmouseout="this.src = 'imagenes/iconos/save_a.png'"
			onclick="guardar_gasto(this);"/>&nbsp;<img
			src="imagenes/iconos/cancel_a.png" alt="cancelar" title="Cancelar"
			class="img_boton"
			onmouseover="this.src = 'imagenes/iconos/cancel.png'"
			onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
			onclick="AjaxConsulta( '../logica/admin_gastos.php', {ACCION:'mostrar_front'}, 'area_trabajo');" /></td>
	</tr>
	<tr>
		<td width="25%" class="subtitulo_tabla">Fecha Gasto:</td>
		<td width="25%" class="texto_tabla"><input type="hidden" id="jva_codigo" value="<?php echo $jva_codigo?>"><input type="hidden" id="usu_codigo" value="<?php echo $usu_codigo?>"><input type="text" id="fecha_gasto" class="campo" size="15" readonly="readonly" value="<?php echo $fecha_gasto?>"/>*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Tipo Gasto:</td>
		<td class="texto_tabla"><script>AjaxConsulta('../logica/admin_gastos.php', {JVA:'<?php echo $jva_codigo?>', ROL_CODIGO:'<?php echo $rol_codigo?>', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'pgjDiv', NOMBRE_SELECT:'spgj', ESTADO:'', ACCION:'consultar_tipos_gastos_jva'}, 'pgjDiv');</script><div id="pgjDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Valor:$</td>
		<td class="texto_tabla"><input type="text" id="valor" class = "campo" size="20"  value=""/>*</td>
	</tr>
	</table>
<br>
</form>