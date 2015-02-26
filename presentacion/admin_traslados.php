<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
?>
<script type='text/javascript' src='../js/admin_traslados.js'></script>
<script type='text/javascript'>
$(document).ready(function() {
	   $("#fecha_inicio").datepicker();
	   $("#fecha_fin").datepicker();
});
</script>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="12">
			<table border="0" width="100%" align="center" style="background: #FFF; border: 0px solid #CCC;">
				<tr height="30">
					<td class="titulo_tabla">Informaci&oacute;n Importante.</td>
				</tr>
				<tr>
					<td colspan="4" style="background: #FFF; border: 1px solid #CCC;">
						<img src="imagenes/iconos/add_a.png" alt="Crear Traslado" title="Crear Traslado"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/add.png'"
							onmouseout="this.src = 'imagenes/iconos/add_a.png'"
							onclick="AjaxConsulta( '../presentacion/admin_traslados_n.php', '', 'listar_traslados');"/>
						<img src="imagenes/iconos/undo_a.png" alt="Regresar" title="Regresar a pantalla principal"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/undo.png'"
							onmouseout="this.src = 'imagenes/iconos/undo_a.png'"
							onclick="AjaxConsulta( '../presentacion/admin_transacciones.php', '', 'area_trabajo');"/>
					</td>
				</tr>
				<tr height="30">
					<td class="texto_tabla">Seleccione informacion requerida para la busqueda.</td>
				</tr>
				<tr height="30">
					<td class="titulo_tabla">Administraci&oacute;n Traslados</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">JVA:</td>
		<td><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_PADRE:'admin_jva', VALOR_REGISTRO_PADRE:'0', NOMBRE_CAMPO_PADRE:'nombre', DIV_PADRE:'jvaDiv', NOMBRE_SELECT_PADRE:'sjva', DIV_HIJO:'vendedorJVA', NOMBRE_SELECT_HIJO:'svededoresjva', ACCION:'consultar_campo_padre_JVA'}, 'jvaDiv');</script><div id="jvaDiv"></div></td>
		<td class="subtitulo_tabla">Vendedor:</td>
		<td class="subtitulo_tabla" style="text-align: left"><div id="vendedorJVA"></div></td>
		<td class="subtitulo_tabla">Inicio:</td>
		<td align="left"><input type="text" size="25" id="fecha_inicio" class="campo"></td>
		<td class="subtitulo_tabla">Fin:</td>
		<td align="left"><input type="text" size="25" id="fecha_fin" class="campo"></td>
		<td colspan="10" align="center"><input type="button" name="bt1" value='Buscar' class="boton" title="Haga clic para buscar" onclick="listar_traslados();"></td>
	</tr>
</table>
<br>
<div id="listar_traslados"></div>