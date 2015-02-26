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
				if ($rol_codigo == 3)
				{
				?>
				<li><a href="#" onclick="AjaxConsulta( '../logica/reportes.php', {ACCION:'mostrar_front'}, 'area_trabajo' );">
					<img src="imagenes/report7_a.png" alt="Reportes" title="Reportes"
							onmouseover="this.src = 'imagenes/report7.png'"
							onmouseout="this.src = 'imagenes/report7_a.png'"
							width="32px"
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
						<img src="imagenes/SignOut-icon_a.png" alt="Salir" title="Salir"
							onmouseover="this.src = 'imagenes/SignOut-icon.png'"
							onmouseout="this.src = 'imagenes/SignOut-icon_a.png'"
							width="32px"
							border="0"/>
					</a>
				</li>
			</ul>
		</td>
	</tr>
</table>