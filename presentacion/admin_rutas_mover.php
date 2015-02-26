<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
?>
<script type='text/javascript' src='../js/admin_rutas.js'></script>
<form id="form">
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2" class="titulo_tabla">MOVER CLIENTE DE RUTA</td>
	</tr>
	<tr>
		<td colspan="2" style="background: #FFF; border: 1px solid #CCC;"><img
			src="imagenes/iconos/save_a.png" alt="guardar" title="Guardar"
			class="img_boton" onmouseover="this.src = 'imagenes/iconos/save.png'"
			onmouseout="this.src = 'imagenes/iconos/save_a.png'"
			onclick="mover(this.form)"/>&nbsp;<img
			src="imagenes/iconos/cancel_a.png" alt="cancelar" title="Cancelar"
			class="img_boton"
			onmouseover="this.src = 'imagenes/iconos/cancel.png'"
			onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
			onclick="AjaxConsulta('../presentacion/admin_transacciones.php', '', 'area_trabajo');" /></td>
	</tr>
	<tr>
		<td width="25%" class="subtitulo_tabla">Codigo Producto:</td>
		<td width="25%" class="texto_tabla">
			<input type="hidden" id="tv_codigo" value="<?php echo $tv_codigo?>"/>
			<input type="hidden" id="trd_codigo" value="<?php echo $trd_codigo?>"/>
			<?php echo $tv_codigo?>
		</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Codigo Cliente:</td>
		<td class="texto_tabla"><input type="hidden" id="cli_codigo" value="<?php echo $cli_codigo?>"/><?php echo $cli_codigo?></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Codigo Argos:</td>
		<td class="texto_tabla"><input type="hidden" id="arg_codigo" value="<?php echo $arg_codigo?>"/><?php echo $arg_codigo?></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Vendedor:</td>
		<td class="texto_tabla"><input type="hidden" id="cliente" value="<?php echo $cliente?>"/><?php echo $cliente?></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Rutas:</td>
		<td><script>AjaxConsulta( '../logica/admin_rutas.php', {TABLA_CONSULTAR:'trans_rutas_jva', TABLA_ACTUALIZAR:'0', CONDICION:'0', VALOR_REGISTRO:'0', CODIGO_REGISTRO:'0', NOMBRE_CAMPO:'nombre', DIV:'rutaDiv', NOMBRE_SELECT:'srutas', ESTADO:'enable', ACCION:'consultar_rutas'}, 'rutaDiv');</script><div id="rutaDiv"></div></td>	
	</tr>
	</table>
<br>
</form>