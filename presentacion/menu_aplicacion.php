<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Mayo de 2012
 * 
 * */
include_once ("../logica/variables_session.php");
?>
<table border ="0" width="100%">
	<tr>
		<td width="90%">
			<ul id="navmenu-h">	
				<?php 
				if ($rol_codigo == 1)
				{
				?>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_jva.php', {ACCION:'listar'}, 'area_trabajo' );">
						<img src="imagenes/user15.png" alt="JVA´s" title="JVA's"
							onmouseover="this.src = 'imagenes/user15_a.png'"
							onmouseout="this.src = 'imagenes/user15.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_usuarios.php', {ACCION:'listar'}, 'area_trabajo' );">
						<img src="imagenes/usuarios.png" alt="Usuarios" title="Usuarios"
							onmouseover="this.src = 'imagenes/usuarios_a.png'"
							onmouseout="this.src = 'imagenes/usuarios.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_tipos.php', {ACCION:'mostrar_front_tipos'}, 'area_trabajo' );">
					<img src="imagenes/benchmarking-icon.png" alt="Informacion Complementaria" title="Informacion Complementaria"
							onmouseover="this.src = 'imagenes/benchmarking-icon_a.png'"
							onmouseout="this.src = 'imagenes/benchmarking-icon.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/dashboard_adm_pla.php', {ACCION:'listar'}, 'area_trabajo' );">
					<img src="imagenes/report7.png" alt="Reportes" title="Reportes"
							onmouseover="this.src = 'imagenes/report7_a.png'"
							onmouseout="this.src = 'imagenes/report7.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_la.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/security1.png" alt="Logs y Auditoria" title="Logs y Auditoria"
							onmouseover="this.src = 'imagenes/security1_a.png'"
							onmouseout="this.src = 'imagenes/security1.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<?php 
				}  
				if ($rol_codigo == 2)
				{
				?>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_jva.php', {ACCION:'listar'}, 'area_trabajo' );">
						<img src="imagenes/user15.png" alt="JVA´s" title="JVA's"
							onmouseover="this.src = 'imagenes/user15_a.png'"
							onmouseout="this.src = 'imagenes/user15.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_usuarios.php', {ACCION:'listar'}, 'area_trabajo' );">
						<img src="imagenes/usuarios.png" alt="Usuarios" title="Usuarios"
							onmouseover="this.src = 'imagenes/usuarios_a.png'"
							onmouseout="this.src = 'imagenes/usuarios.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_param.php', {ACCION:'mostrar_front_param'}, 'area_trabajo' );">
					<img src="imagenes/ParametrizarOperacion.png" alt="Parametrizar Operaci&oacute;n" title="Parametrizar Operaci&oacute;n"
							onmouseover="this.src = 'imagenes/ParametrizarOperacion_a.png'"
							onmouseout="this.src = 'imagenes/ParametrizarOperacion.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../presentacion/admin_transacciones.php', '', 'area_trabajo' );">
					<img src="imagenes/AdministrarOperacion.png" alt="Administrar Operaci&oacute;n" title="Administrar Operaci&oacute;n"
							onmouseover="this.src = 'imagenes/AdministrarOperacion_a.png'"
							onmouseout="this.src = 'imagenes/AdministrarOperacion.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/dashboard_mapa.php', {ACCION:'mostrar_front'}, 'area_trabajo');">
					<img src="imagenes/rutas_mapas.png" alt="Mapa de Rutas" title="Mapa de Rutas"
							onmouseover="this.src = 'imagenes/rutas_mapas_a.png'"
							onmouseout="this.src = 'imagenes/rutas_mapas.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../presentacion/dashboard_adm_pla.php', '', 'area_trabajo' );">
					<img src="imagenes/dashboard.png" alt="Dashboard" title="Dashboard"
							onmouseover="this.src = 'imagenes/dashboard_a.png'"
							onmouseout="this.src = 'imagenes/dashboard.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_reportes.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/report7.png" alt="Reportes" title="Reportes"
							onmouseover="this.src = 'imagenes/report7_a.png'"
							onmouseout="this.src = 'imagenes/report7.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_la.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/security1.png" alt="Logs y Auditoria" title="Logs y Auditoria"
							onmouseover="this.src = 'imagenes/security1_a.png'"
							onmouseout="this.src = 'imagenes/security1.png'"
							width="48px"
							border="0"/>
					</a>
				</li>	
				<?php 
				} 
				if ($rol_codigo == 3)
				{
				?>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_jva.php', {ACCION:'listar'}, 'area_trabajo' );">
						<img src="imagenes/user15.png" alt="JVA´s" title="JVA's"
							onmouseover="this.src = 'imagenes/user15_a.png'"
							onmouseout="this.src = 'imagenes/user15.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_usuarios.php', {ACCION:'listar'}, 'area_trabajo' );">
						<img src="imagenes/usuarios.png" alt="Usuarios" title="Usuarios"
							onmouseover="this.src = 'imagenes/usuarios_a.png'"
							onmouseout="this.src = 'imagenes/usuarios.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_param.php', {ACCION:'mostrar_front_param'}, 'area_trabajo' );">
					<img src="imagenes/ParametrizarOperacion.png" alt="Parametrizar Operaci&oacute;n" title="Parametrizar Operaci&oacute;n"
							onmouseover="this.src = 'imagenes/ParametrizarOperacion_a.png'"
							onmouseout="this.src = 'imagenes/ParametrizarOperacion.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../presentacion/admin_transacciones.php', '', 'area_trabajo' );">
					<img src="imagenes/AdministrarOperacion.png" alt="Administrar Operaci&oacute;n" title="Administrar Operaci&oacute;n"
							onmouseover="this.src = 'imagenes/AdministrarOperacion_a.png'"
							onmouseout="this.src = 'imagenes/AdministrarOperacion.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/dashboard_mapa.php', {ACCION:'mostrar_front'}, 'area_trabajo');">
					<img src="imagenes/rutas_mapas.png" alt="Mapa de Rutas" title="Mapa de Rutas"
							onmouseover="this.src = 'imagenes/rutas_mapas_a.png'"
							onmouseout="this.src = 'imagenes/rutas_mapas.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../presentacion/dashboard_adm_pla.php', '', 'area_trabajo' );">
					<img src="imagenes/dashboard.png" alt="Dashboard" title="Dashboard"
							onmouseover="this.src = 'imagenes/dashboard_a.png'"
							onmouseout="this.src = 'imagenes/dashboard.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_reportes.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/report7.png" alt="Reportes" title="Reportes"
							onmouseover="this.src = 'imagenes/report7_a.png'"
							onmouseout="this.src = 'imagenes/report7.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_la.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/security1.png" alt="Logs y Auditoria" title="Logs y Auditoria"
							onmouseover="this.src = 'imagenes/security1_a.png'"
							onmouseout="this.src = 'imagenes/security1.png'"
							width="48px"
							border="0"/>
					</a>
				</li>	
				<?php 
				} 
				if ($rol_codigo == 4)
				{
				?>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_ic.php', {ACCION:'mostrar_front_tipos'}, 'area_trabajo' );">
					<img src="imagenes/benchmarking-icon.png" alt="Informacion Complementaria" title="Informacion Complementaria"
							onmouseover="this.src = 'imagenes/benchmarking-icon_a.png'"
							onmouseout="this.src = 'imagenes/benchmarking-icon.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_campanas.php', {ACCION:'listar'}, 'area_trabajo' );">
					<img src="imagenes/wallet-icon.png" alt="Campa&ntilde;as" title="Campa&ntilde;as"
							onmouseover="this.src = 'imagenes/wallet-icon_a.png'"
							onmouseout="this.src = 'imagenes/wallet-icon.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_deudores.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/data-management3.png" alt="Informacion Deudores" title="Informacion Deudores"
							onmouseover="this.src = 'imagenes/data-management3_a.png'"
							onmouseout="this.src = 'imagenes/data-management3.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/gestion_cartera.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/office3.png" alt="Gestion Cartera" title="Gestion Cartera"
							onmouseover="this.src = 'imagenes/office3_a.png'"
							onmouseout="this.src = 'imagenes/office3.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/reportes.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/report7.png" alt="Reportes" title="Reportes"
							onmouseover="this.src = 'imagenes/report7_a.png'"
							onmouseout="this.src = 'imagenes/report7.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				
				<?php 
				}  
				if ($rol_codigo == 5)
				{
				?>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_ic.php', {ACCION:'mostrar_front_tipos'}, 'area_trabajo' );">
					<img src="imagenes/benchmarking-icon.png" alt="Informacion Complementaria" title="Informacion Complementaria"
							onmouseover="this.src = 'imagenes/benchmarking-icon_a.png'"
							onmouseout="this.src = 'imagenes/benchmarking-icon.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/admin_deudores.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/data-management3.png" alt="Informacion Deudores" title="Informacion Deudores"
							onmouseover="this.src = 'imagenes/data-management3_a.png'"
							onmouseout="this.src = 'imagenes/data-management3.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/gestion_cartera.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/briefcase5.png" alt="Gestion Cartera" title="Gestion Cartera"
							onmouseover="this.src = 'imagenes/briefcase5_a.png'"
							onmouseout="this.src = 'imagenes/briefcase5.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/gestion_procesos.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/user15.png" alt="Procesos" title="Procesos"
							onmouseover="this.src = 'imagenes/user15_a.png'"
							onmouseout="this.src = 'imagenes/user15.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<li><a href="#" onclick="AjaxConsulta( '../logica/reportes.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/report7.png" alt="Reportes" title="Reportes"
							onmouseover="this.src = 'imagenes/report7_a.png'"
							onmouseout="this.src = 'imagenes/report7.png'"
							width="48px"
							border="0"/>
					</a>
				</li>				
				<?php 
				}  
				
				if ($rol_codigo == 6)
				{
				?>
				<li><a href="#" onclick="AjaxConsulta( '../presentacion/admin_transacciones_vendedor.php', '', 'area_trabajo' );">
					<img src="imagenes/AdministrarOperacion.png" alt="Administrar Operaci&oacute;n" title="Administrar Operaci&oacute;n"
							onmouseover="this.src = 'imagenes/AdministrarOperacion_a.png'"
							onmouseout="this.src = 'imagenes/AdministrarOperacion.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
				<?php 
				} 
				?>
			</ul>
		</td>
		<td width="10%">
			<ul id="navmenu-h">
				<li style="float: right;">
					<a href="#" onclick="if(confirm('Esta seguro que desea salir?')){AjaxConsulta( '../logica/salir.php', '', 'mainContent' );}">
						<img src="imagenes/SignOut-icon.png" alt="Salir" title="Salir"
							onmouseover="this.src = 'imagenes/SignOut-icon_a.png'"
							onmouseout="this.src = 'imagenes/SignOut-icon.png'"
							width="48px"
							border="0"/>
					</a>
				</li>
			</ul>
		</td>
	</tr>
</table>