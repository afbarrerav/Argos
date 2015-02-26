<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 *
 * */

?>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="7" class="titulo_tabla">ADMINISTRAR OPERACI&Oacute;N</td>
	</tr>
	<tr>
		<td colspan="7">&nbsp;</td>
	</tr>
	<tr>		
		<td align="center"><a href="#" onclick="AjaxConsulta( '../logica/admin_clientes.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/adm_clientes.png" alt="Administrar Clientes" title="Administrar Clientes"
							onmouseover="this.src = 'imagenes/adm_clientes_a.png'"
							onmouseout="this.src = 'imagenes/adm_clientes.png'"
							width="150px"
							border="0"/>
					</a></td>
		<td width="1%">	</td>
		<td align="center"><a href="#" onclick="AjaxConsulta( '../logica/admin_gastos.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/adm_gastos.png" alt="Administrar Gastos" title="Administrar Gastos"
							onmouseover="this.src = 'imagenes/adm_gastos_a.png'"
							onmouseout="this.src = 'imagenes/adm_gastos.png'"
							width="150px"
							border="0"/>
					</a></td>
		<td width="1%"></td>
		<td align="center"><a href="#" onclick="AjaxConsulta( '../presentacion/admin_inventarios.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/adm_inventarios.png" alt="Administrar Inventarios" title="Administrar Inventarios"
							onmouseover="this.src = 'imagenes/adm_inventarios_a.png'"
							onmouseout="this.src = 'imagenes/adm_inventarios.png'"
							width="150px"
							border="0"/>
					</a>
		</td>
		<td align="center"><a href="#" onclick="AjaxConsulta( '../logica/admin_rutas.php', {ACCION:'listar_rutas'}, 'area_trabajo' );">
					<img src="imagenes/adm_rutas.png" alt="Administrar Rutas" title="Administrar Rutas"
							onmouseover="this.src = 'imagenes/adm_rutas_a.png'"
							onmouseout="this.src = 'imagenes/adm_rutas.png'"
							width="150px"
							border="0"/>
					</a></td>
		<td width="1%">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="7" height="50px">&nbsp;</td>
	</tr>
	<tr>
		
		<td align="center"><a href="#" onclick="AjaxConsulta( '../logica/admin_salarios.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/adm_salarios.png" alt="Liquidar Salarios y Ruta" title="Liquidar Salarios y Ruta"
							onmouseover="this.src = 'imagenes/adm_salarios_a.png'"
							onmouseout="this.src = 'imagenes/adm_salarios.png'"
							width="150px"
							border="0"/>
					</a></td>
		<td width="1%">	</td>
		<td align="center"><a href="#" onclick="AjaxConsulta( '../presentacion/admin_ventas.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/adm_ventas.png" alt="Administrar Ventas" title="Administrar Ventas"
							onmouseover="this.src = 'imagenes/adm_ventas_a.png'"
							onmouseout="this.src = 'imagenes/adm_ventas.png'"
							width="150px"
							border="0"/>
					</a></td>
		<td width="1%"></td>
		<td align="center"><a href="#" onclick="AjaxConsulta( '../presentacion/admin_traslados.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/adm_tras_inventarios.png" alt="Administrar Trasladados de Inventario" title="Administrar Trasladados de Inventario"
							onmouseover="this.src = 'imagenes/adm_tras_inventarios_a.png'"
							onmouseout="this.src = 'imagenes/adm_tras_inventarios.png'"
							width="150px"
							border="0"/>
					</a>
		</td>
		<td align="center"><a href="#" onclick="AjaxConsulta( '../logica/admin_reportes.php', {ACCION:'mostrar_front'}, 'area_trabajo');">
					<img src="imagenes/report7.png" alt="Reportes" title="Reportes"
							onmouseover="this.src = 'imagenes/report7_a.png'"
							onmouseout="this.src = 'imagenes/report7.png'"
							width="150px"
							border="0"/>
					</a></td>
		<td width="1%">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="7">&nbsp;</td>
	</tr>
</table>