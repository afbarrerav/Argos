<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
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
<script>
$(document).ready(function() 
	    { 
	        $("#rutas").tablesorter(); 
	    } 
	); 
</script>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2">
			<table border="0" width="100%" align="center" style="background: #FFF; border: 0px solid #CCC;">
				<tr height="30">
					<td class="titulo_tabla">ADMINISTRAR RUTAS</td>
				</tr>
				<tr>
					<td colspan="4" style="background: #FFF; border: 1px solid #CCC; text-align:left;">
						<img src="imagenes/iconos/add_a.png" alt="Crear Ruta" title="Crear Ruta"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/add.png'"
							onmouseout="this.src = 'imagenes/iconos/add_a.png'"
							onclick="AjaxConsulta('../presentacion/admin_rutas_n.php', '', 'area_trabajo');"/>
						<img src="imagenes/iconos/undo_a.png" alt="Regresar" title="Regresar a pantalla principal"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/undo.png'"
							onmouseout="this.src = 'imagenes/iconos/undo_a.png'"
							onclick="AjaxConsulta( '../presentacion/admin_transacciones.php', '', 'area_trabajo' );"/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id="rutas" class="tablesorter" width="100%" style="background: #FFF; border: 1px solid #CCC;"> 
			<thead> 
				<tr>
					<th width="30%" class="titulo_tabla" style="cursor: pointer;">JVA</th>
					<th width="30%" class="titulo_tabla" style="cursor: pointer;">Ruta</th>
					<th width="30%" class="titulo_tabla" style="cursor: pointer;">Vendedor</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Cant. Clientes</th>
				</tr>
			</thead>
				<tbody>
				<?php
					for($i=0;$i<$RowCount;$i++)
					{
				?>
				<tr class="fila">
					<td class="texto_tabla"><?php echo $resultados['jva'][$i]?></td>
					<td class="texto_tabla"><a href="#" style="text-decoration:none" onclick="AjaxConsulta('../logica/admin_rutas.php', {TRJ_CODIGO:'<?php echo $resultados['trj_codigo'][$i]?>', ACCION:'listar_ruta'}, 'Param_rutasDiv');"><strong><?php echo $resultados['ruta'][$i]?></strong></a></td>
					<td class="texto_tabla"><?php echo $resultados['vendedor'][$i]?></td>
					<td class="texto_tabla"><a href="#" style="text-decoration:none" onclick="AjaxConsulta('../logica/admin_rutas.php', {TRJ_CODIGO:'<?php echo $resultados['trj_codigo'][$i]?>', ACCION:'listar_clientes'}, 'Param_rutasDiv');"><strong><?php echo $resultados['cant_clientes'][$i]?></strong></a></td>
				</tr>
				<?php 
					}
				?>
				</tbody>
				<tr>
					<td colspan="4" class="titulo_tabla">Total Registros: <?php echo $RowCount?></td>
				</tr>
			</table>		
		</td>
	</tr>
</table>	
<br>
<div id="Param_rutasDiv"></div>