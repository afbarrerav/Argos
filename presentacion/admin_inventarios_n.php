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
<script type='text/javascript' src='../js/admin_inventarios_bodegas.js'></script>
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
					<td class="titulo_tabla">Crear nuevo JVA</td>
				</tr>
				<tr>
					<td colspan="4" style="background: #FFF; border: 1px solid #CCC;">
						<img src="imagenes/iconos/save_a.png" alt="Agregar nuevo producto a bodega" title="Agregar nuevo producto a bodega"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/save.png'"
							onmouseout="this.src = 'imagenes/iconos/save_a.png'"
							onclick="guardar(this.form);"/>
							<img src="imagenes/iconos/cancel_a.png" alt="Cancelar" title="Cancelar"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/cancel.png'"
							onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
							onclick="AjaxConsulta( '../logica/admin_inventarios_bodegas.php', {ACCION:'mostrar_front'}, 'area_trabajo' );"/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width = "50%" class="subtitulo_tabla">Bodega</td>
		<td width = "50%" align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'admin_usuarios', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'bodegasDiv', NOMBRE_SELECT:'sbodega1', ESTADO:'', ACCION:'consultar_campo_bodegas_jva'}, 'bodegasDiv' );</script><div id="bodegasDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Producto</td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'param_productos_jva', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'productosDiv', NOMBRE_SELECT:'sproducto', ESTADO:'', ACCION:'consultar_campo'}, 'productosDiv' );</script><div id="productosDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Cantidad</td>
		<td align="left"><input type="text" id="cantidad" class="campo"></td>
	</tr>
</table>
</form>
<br>