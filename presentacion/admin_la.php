<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 *
 * */
?>
<script type='text/javascript' src='../js/admin_la.js'></script>
<br>
<form>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="5" class="titulo_tabla">Informaci&oacute;n</td>
	</tr>
	<tr>
		<td colspan="5" class="texto_tabla">La auditoria le permite ver las acciones o modificaciones hechas a la aplicacion.<br><br></td>
	</tr>
	<tr>
		<td colspan="5" class="titulo_tabla">Seleccione Tabla</td>
	</tr>
	<tr>
		<td align="center" class="texto_tabla">
		Logs / Auditoria
		<div>
			<select class='lista_desplegable' id="logs_auditorias" onchange="mostrar(this.value);" >
				<option value="0" >No indica</option>
				<option value="logs" >Logs</option>
				<option value="auditorias" >Auditorias</option>
			</select>
		</div>
		</td>
		<td align="center" class="texto_tabla">Tabla<div id="tablas_bd"></div></td>
		<td align="center" class="texto_tabla">Campo<div id="atributos"></div></td>
		<td valign="bottom" class="texto_tabla">Criterio de Busqueda<br><input id="parametros" class="campo" type="text"/></td>
		<td valign="bottom" class="texto_tabla">
			<input type="button" id="btn" class="boton" value="Buscar" onclick="consulta_like_atributos();" />
		</td>
	</tr>
</table>
</form>
<div id="consultarLADiv"></div>
<br>