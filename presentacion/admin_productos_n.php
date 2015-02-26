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
<script type='text/javascript' src='../js/admin_productos.js'></script>
<form>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2">
			<table border="0" width="100%" align="center" style="background: #FFF; border: 0px solid #CCC;">
				<tr height="30">
					<td class="titulo_tabla">Crear nuevo Producto</td>
				</tr>
				<tr>
					<td colspan="4" style="background: #FFF; border: 1px solid #CCC;">
						<img src="imagenes/iconos/save_a.png" alt="Crear nuevo usuario" title="Crear nuevo usuario"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/save.png'"
							onmouseout="this.src = 'imagenes/iconos/save_a.png'"
							onclick="guardar(this.form);"/>
							<img src="imagenes/iconos/cancel_a.png" alt="Cancelar" title="Cancelar"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/cancel.png'"
							onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
							onclick="AjaxConsulta( '../logica/admin_productos.php', {ACCION:'listar'}, 'area_trabajo' );"/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width = "50%" class="subtitulo_tabla">Nombre</td>
		<td width = "50%" align="left"><input type="text" id="nombre" class="campo">*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Descripci&oacute;n</td>
		<td align="left"><input type="text" id="descripcion" class="campo"></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Unidad de medida</td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_unidades_medidas', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'umDiv', NOMBRE_SELECT:'sum', ESTADO:'', ACCION:'consultar_campo'}, 'umDiv' );</script><div id="umDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Valor</td>
		<td align="left"><input type="text" id="direccion" class="campo"></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">JVA</td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'admin_jva', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'', CODIGO_REGISTRO:'<?php echo $usu_codigo?>',  DIV:'jvaDiv', NOMBRE_SELECT:'sjva', ESTADO:'', ACCION:'consultar_campo_usuario'}, 'jvaDiv' );</script><div id="jvaDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Tipos producto</td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'param_tipos_productos_jva', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'ptjDiv', NOMBRE_SELECT:'sptj', ESTADO:'', ACCION:'consultar_campo_hijo'}, 'ptjDiv' );</script><div id="ptjDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Categoria</td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'param_categorias_productos', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'', CODIGO_REGISTRO:'',  DIV:'catDiv', NOMBRE_SELECT:'scat', ESTADO:'', ACCION:'consultar_campo_hijo'}, 'catDiv' );</script><div id="catDiv"></div></td>
	</tr>
</table>
</form>
<br>