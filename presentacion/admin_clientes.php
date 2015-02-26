<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
?>
<script type='text/javascript' src='../js/admin_clientes.js'></script>
<br>
<form>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="5" class="titulo_tabla">Administrar Clientes</td>
	</tr>
	<tr>
		<td colspan="5" style="background: #FFF; border: 1px solid #CCC;">
			<img src="imagenes/iconos/undo_a.png" alt="Regresar" title="Regresar a pantalla principal"
				style="cursor: pointer; border: 1px solid #CCC;"
				onmouseover="this.src = 'imagenes/iconos/undo.png'"
				onmouseout="this.src = 'imagenes/iconos/undo_a.png'"
				onclick="AjaxConsulta( '../presentacion/admin_transacciones.php', '', 'area_trabajo' );"/>
		</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Seleccione Campo de Busqueda:</td>
		<td><div id="atributos"></div><script>AjaxConsulta('../logica/proc_atributos_tabla.php', {TABLA_CONSULTAR:'admin_clientes', DIV:'atributos', NOMBRE_SELECT:'satributos', ESTADO:'0', ACCION:'consultar_atributos'},'atributos');</script></td>
		<td align="center" class="subtitulo_tabla">Valor:</td>
		<td><input id="parametro" class="campo" type="text" onkeyup="consulta_like_atributos();"/></td>
		<td valign="bottom" class="texto_tabla">
			<input type="button" id="btn" class="boton" value="Buscar" onclick="Buscar();"/>
		</td>
	</tr>
</table>
</form>
<div id="listar_clientes_Div"></div>
<br>