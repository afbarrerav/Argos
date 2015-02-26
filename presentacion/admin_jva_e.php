<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 *
 * */

?>
<br>
<script type='text/javascript' src='../js/admin_jva.js'></script>
<script type='text/javascript'>
$(document).ready(function() {
	   $("#fecha_creacion").datepicker();
});
</script>
<form>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2">
			<table border="0" width="100%" align="center" style="background: #FFF; border: 0px solid #CCC;">
				<tr height="30">
					<td class="titulo_tabla">Editar JVA</td>
				</tr>
				<tr>
					<td colspan="4" style="background: #FFF; border: 1px solid #CCC;">
						<img src="imagenes/iconos/save_a.png" alt="Crear nuevo usuario" title="Crear nuevo usuario"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/save.png'"
							onmouseout="this.src = 'imagenes/iconos/save_a.png'"
							onclick="guardar_e(this.form);"/>
							<img src="imagenes/iconos/cancel_a.png" alt="Cancelar" title="Cancelar"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/cancel.png'"
							onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
							onclick="AjaxConsulta( '../logica/admin_jva.php', {ACCION:'listar'}, 'area_trabajo' );"/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width = "50%" class="subtitulo_tabla">Codigo: </td>
		<td width = "50%" align="left"><input type="hidden" id="codigo" value="<?php echo $codigo?>"><strong><?php echo $codigo?></strong></td>
	</tr>
	<tr>
		<td width = "50%" class="subtitulo_tabla">Nombre: </td>
		<td width = "50%" align="left"><input type="text" id="nombre" class="campo" value="<?php echo $nombre ?>">*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Descripci&oacute;n: </td>
		<td align="left"><input type="text" id="descripcion" class="campo" value="<?php echo $descripcion ?>"></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Distribuidores: </td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'admin_distribuidores', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'<?php echo $dis_codigo?>', CODIGO_REGISTRO:'<?php echo $dis_codigo?>',  DIV:'disDiv', NOMBRE_SELECT:'sdis', ESTADO:'', ACCION:'consultar_campo'}, 'disDiv' );</script><div id="disDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Ciudad: </td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_ciudades', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'<?php echo $ciu_codigo?>', CODIGO_REGISTRO:'<?php echo $ciu_codigo?>',  DIV:'ciuDiv', NOMBRE_SELECT:'sciu', ESTADO:'', ACCION:'consultar_campo'}, 'ciuDiv' );</script><div id="ciuDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Direcci&oacute;n: </td>
		<td align="left"><input type="text" id="direccion" class="campo" value="<?php echo $direccion?>"></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Fecha de creaci&oacute;n: </td>
		<td align="left"><input type="text" id="fecha_creacion" class="campo" value="<?php echo $fecha_creacion?>"></td>
	</tr>
</table>
</form>
<br>