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
<script type='text/javascript' src='../js/admin_jva.js'></script>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td>
			<table border="0" width="100%" align="center" style="background: #FFF; border: 0px solid #CCC;">
				<tr height="30">
					<td class="titulo_tabla">Administrar JVA's</td>
				</tr>
				<tr>
					<td colspan="4" style="background: #FFF; border: 1px solid #CCC;">
						<img src="imagenes/iconos/add_a.png" alt="Crear nuevo usuario" title="Crear nuevo usuario"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/add.png'"
							onmouseout="this.src = 'imagenes/iconos/add_a.png'"
							onclick="AjaxConsulta( '../presentacion/admin_jva_n.php', {ACCION:'listar'}, 'area_trabajo' );"/>
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
					<th width="5%" class="titulo_tabla" style="cursor: pointer;">C&oacute;digo</th>
					<th width="15%" class="titulo_tabla" style="cursor: pointer;">Nombre</th>
					<th width="20%" class="titulo_tabla" style="cursor: pointer;">Descripcion</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Ciudad</th>
					<th width="20%" class="titulo_tabla" style="cursor: pointer;">Direcci&oacute;n</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Fecha creaci&oacute;n</th>
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
					<td class="texto_tabla"><?php echo $resultados['codigo'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['nombre'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['descripcion'][$i]?></td>
					<td class="texto_tabla"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_ciudades', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'<?php echo $resultados['ciu_codigo'][$i]?>', CODIGO_REGISTRO:'<?php echo $resultados['codigo'][$i]?>',  DIV:'ciuDiv<?php echo $resultados['codigo'][$i]?>', NOMBRE_SELECT:'sciu<?php echo $resultados['codigo'][$i]?>', ESTADO:'disabled', ACCION:'consultar_campo'}, 'ciuDiv<?php echo $resultados['codigo'][$i]?>' );</script><div id="ciuDiv<?php echo $resultados['codigo'][$i]?>"></div></td>
					<td class="texto_tabla"><?php echo $resultados['direccion'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['fecha_creacion'][$i]?></td>
					<td class="texto_tabla"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_estados', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'<?php echo $resultados['est_codigo'][$i]?>', CODIGO_REGISTRO:'<?php echo $resultados['codigo'][$i]?>',  DIV:'estDiv<?php echo $resultados['codigo'][$i]?>', NOMBRE_SELECT:'sest<?php echo $resultados['codigo'][$i]?>', ESTADO:'disabled', ACCION:'consultar_campo'}, 'estDiv<?php echo $resultados['codigo'][$i]?>' );</script><div id="estDiv<?php echo $resultados['codigo'][$i]?>"></div></td>
					<td class="texto_tabla">
						<img src="imagenes/iconos/edit_a.png" alt="Reiniciar clave" title="Reiniciar clave"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/edit.png'"
							onmouseout="this.src = 'imagenes/iconos/edit_a.png'"
							width="20"
							align="absbottom"
							onclick="AjaxConsulta('../logica/admin_jva.php', {CODIGO:'<?php echo $resultados['codigo'][$i]?>', ACCION:'listar_editar'}, 'area_trabajo');"/>
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