<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
$fecha_traslado = date('Y-m-d');
?>
<script type='text/javascript' src='../js/admin_traslados.js'></script>
<script>
$(document).ready(function() {
	   $("#fecha_traslado").datepicker();
});
</script>

<br>
<form id="form">
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2" class="titulo_tabla">CREAR TRASLADO</td>
	</tr>
	<tr>
		<td colspan="2" style="background: #FFF; border: 1px solid #CCC;"><img
			src="imagenes/iconos/save_a.png" alt="guardar" title="Guardar"
			class="img_boton" onmouseover="this.src = 'imagenes/iconos/save.png'"
			onmouseout="this.src = 'imagenes/iconos/save_a.png'"
			onclick="Guardar(this)"/>&nbsp;<img
			src="imagenes/iconos/cancel_a.png" alt="cancelar" title="Cancelar"
			class="img_boton"
			onmouseover="this.src = 'imagenes/iconos/cancel.png'"
			onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
			onclick="AjaxConsulta( '../logica/admin_gastos.php', {ACCION:'mostrar_front'}, 'area_trabajo');" /></td>
	</tr>
	<tr>
		<td  width="50%" class="subtitulo_tabla">JVA:</td>
		<td width="50%"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_PADRE:'admin_jva', VALOR_REGISTRO_PADRE:'0', NOMBRE_CAMPO_PADRE:'nombre', DIV_PADRE:'jvaDiv_n', NOMBRE_SELECT_PADRE:'sjva', TABLA_HIJO:'param_traslados_inventarios_jva', NOMBRE_CAMPO_HIJO:'nombre', DIV_HIJO:'ptijDiv', NOMBRE_SELECT_HIJO:'sptij', NOMBRE_CONDICION_HIJO:'0', VALOR_CONDICION_HIJO:'this.value', ACCION:'consultar_campo_padre'}, 'jvaDiv_n');</script><div id="jvaDiv_n"></div></td>

	</tr>
	<tr>
		<td  width="50%" class="subtitulo_tabla">Tipo Traslado:</td>
		<td width="50%" ><div id="ptijDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Desde:</td>
		<td><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'usuarios_traslados', TABLA_ACTUALIZAR:'0', VALOR_REGISTRO:'0', CODIGO_REGISTRO:'0', NOMBRE_CAMPO:'nombre', DIV:'DesdeUsuarioDiv', NOMBRE_SELECT:'sDesdeUsuario', ESTADO:'', ACCION:'consultar_campo'}, 'DesdeUsuarioDiv');</script><div id="DesdeUsuarioDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Hasta:</td>
		<td><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'usuarios_traslados', TABLA_ACTUALIZAR:'0', VALOR_REGISTRO:'0', CODIGO_REGISTRO:'0', NOMBRE_CAMPO:'nombre', DIV:'HastaUsuarioDiv', NOMBRE_SELECT:'sHastaUsuario', ESTADO:'', ACCION:'consultar_campo'}, 'HastaUsuarioDiv');</script><div id="HastaUsuarioDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Fecha Traslado:</td>
		<td><input type="text" id="fecha_traslado" readonly="readonly" value="<?php echo $fecha_traslado?>"></td>

	</tr>
	<tr>
		<td class="subtitulo_tabla">Cantidad: $</td>
		<td class="texto_tabla"><input type="text" id="cantidad" class="campo" size="20"  value="" required/>*</td>
	</tr>
	</table>
<br>
</form>