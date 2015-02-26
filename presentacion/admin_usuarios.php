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

<script>
$(document).ready(function() 
	    { 
	        $("#Usuarios").tablesorter(); 
	    } 
	); 
</script>
<script type='text/javascript' src='../js/admin_usuarios.js'></script>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td>
			<table border="0" width="100%" align="center" style="background: #FFF; border: 0px solid #CCC;">
				<tr height="30">
					<td class="titulo_tabla">Administrar Usuarios</td>
				</tr>
				<tr>
					<td colspan="4" style="background: #FFF; border: 1px solid #CCC;">
						<img src="imagenes/iconos/add_a.png" alt="Crear nuevo usuario" title="Crear nuevo usuario"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/add.png'"
							onmouseout="this.src = 'imagenes/iconos/add_a.png'"
							onclick="AjaxConsulta( '../presentacion/admin_usuarios_n.php', {ACCION:'listar'}, 'area_trabajo' );"/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>					
			<table id="Usuarios" class="tablesorter" style="background: #FFF; border: 1px solid #CCC;"> 
			<thead> 
				<tr height="20">
					<th width="5%" class="titulo_tabla" style="cursor: pointer;">Identificaci&oacute;n</th>
					<th width="15%" class="titulo_tabla" style="cursor: pointer;">Nombres</th>
					<th width="20%" class="titulo_tabla" style="cursor: pointer;">Apellidos</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Username</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">JVA</th>
					<th width="20%" class="titulo_tabla" style="cursor: pointer;">Rol</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Estado</th>
					<th width="5%" class="titulo_tabla">Acci&oacute;n</th>
				</tr>
				</thead>
				<tbody>
				<?php
					for($i=0;$i<$RowCount;$i++)
					{
						?>
				<tr class="fila">
					<td class="texto_tabla"><?php echo $usuarios['codigo'][$i]?></td>
					<td class="texto_tabla"><?php echo $usuarios['nombres'][$i]?></td>
					<td class="texto_tabla"><?php echo $usuarios['apellidos'][$i]?></td>
					<td class="texto_tabla"><?php echo $usuarios['username'][$i]?></td>
					<td class="texto_tabla"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'admin_jva', TABLA_ACTUALIZAR:'admin_jva_usuarios', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'<?php echo $usuarios['jva_codigo'][$i]?>', CODIGO_REGISTRO:'<?php echo $usuarios['codigo'][$i]?>',  DIV:'jvaDiv<?php echo $usuarios['codigo'][$i]?>', NOMBRE_SELECT:'sjva<?php echo $usuarios['codigo'][$i]?>', ESTADO:'disabled', ACCION:'consultar_campo'}, 'jvaDiv<?php echo $usuarios['codigo'][$i]?>' );</script><div id="jvaDiv<?php echo $usuarios['codigo'][$i]?>"></div></td>
					<td class="texto_tabla"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'admin_roles', TABLA_ACTUALIZAR:'admin_jva_usuarios', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'<?php echo $usuarios['rol_codigo'][$i]?>', CODIGO_REGISTRO:'<?php echo $usuarios['codigo'][$i]?>',  DIV:'rolDiv<?php echo $usuarios['codigo'][$i]?>', NOMBRE_SELECT:'srol<?php echo $usuarios['codigo'][$i]?>', ESTADO:'disabled', ACCION:'consultar_campo'}, 'rolDiv<?php echo $usuarios['codigo'][$i]?>' );</script><div id="rolDiv<?php echo $usuarios['codigo'][$i]?>"></div></td>
					<td class="texto_tabla"><script>AjaxConsulta( '../logica/proc_estados.php', {ACCION:'listar', ESTADO:'<?php echo $usuarios['est_codigo'][$i]?>', USUARIO:'<?php echo $usuarios['codigo'][$i]?>', USERNAME:'<?php echo $usuarios['username'][$i]?>'}, 'estado_<?php echo $usuarios['codigo'][$i]?>' );</script><span id="estado_<?php echo $usuarios['codigo'][$i]?>"></span></td>
					<td class="texto_tabla">
						<img src="imagenes/iconos/key_a.png" alt="Reiniciar clave" title="Reiniciar clave"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/key.png'"
							onmouseout="this.src = 'imagenes/iconos/key_a.png'"
							width="20"
							align="absbottom"
							onclick="actualizar_password('<?php echo $usuarios['codigo'][$i]?>', '<?php echo $usuarios['username'][$i]?>')"/>
					</td>
				</tr>
				<?php 
					}
				?>
				</tbody>
				<tr>
					<td colspan="9" class="titulo_tabla">Total Registros: <?php echo $RowCount?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div id="pass"></div>
<br>