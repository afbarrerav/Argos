<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
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
<br>
<script type='text/javascript' src='../js/admin_bodegas.js'></script>
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
					<td class="titulo_tabla">Crear Nueva Bodega</td>
				</tr>
				<tr>
					<td colspan="4" style="background: #FFF; border: 1px solid #CCC;">
						<img src="imagenes/iconos/save_a.png" alt="Crear nueva bodega" title="Crear nueva bodega"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/save.png'"
							onmouseout="this.src = 'imagenes/iconos/save_a.png'"
							onclick="guardar(this.form);"/>
							<img src="imagenes/iconos/cancel_a.png" alt="Cancelar" title="Cancelar"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/cancel.png'"
							onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
							onclick="AjaxConsulta( '../logica/admin_bodegas.php', {ACCION:'listar'}, 'area_trabajo' );"/>
					</td>
				</tr>
			</table>
		</td>
	</tr>	
	<tr>
		<td width="50%" class="subtitulo_tabla">Descripci&oacute;n</td>
		<td width="50%" align="left"><input type="text" id="descripcion" class="campo"></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Usuario</td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'admin_usuarios', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'ajuDiv', NOMBRE_SELECT:'saju', ESTADO:'', ACCION:'consultar_campo_usuarios_jva'}, 'ajuDiv' );</script><div id="ajuDiv"></div></td>
	</tr>
</table>
</form>
<br>