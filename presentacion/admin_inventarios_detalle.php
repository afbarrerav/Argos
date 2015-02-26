<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2012
 *
 * */
?>
<script>
$(document).ready(function() 
	    { 
	        $("#ic_tabla").tablesorter(); 
	    } 
	); 
</script>
<script type='text/javascript' src='../js/admin_inventarios.js'></script>
<br>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td class="titulo_tabla" height= "20" colspan ="6">Inventario</td>
	</tr>
	<!--<tr>
		<td  colspan ="6">
			<img src="imagenes/iconos/add_a.png" alt="Agregar" title="Agregar"
				style="cursor: pointer; border: 1px solid #CCC;"
				onmouseover="this.src = 'imagenes/iconos/add.png'"
				onmouseout="this.src = 'imagenes/iconos/add_a.png'"
				onclick="AjaxConsulta( '../logica/admin_inventarios_bodegas.php', {ACCION:'frm_agregar_producto_inventario'}, 'inventarioDiv' );"/>
		</td>
	</tr>!-->
	<tr>
		<td>					
			<table id="Usuarios" width="100%" class="tablesorter" style="background: #FFF; border: 1px solid #CCC;"> 
			<thead> 
				<tr height="20">
					<th width="5%" class="titulo_tabla" style="cursor: pointer;">C&oacute;digo</th>
					<th width="45%" class="titulo_tabla" style="cursor: pointer;">Usuario</th>
					<th width="20%" class="titulo_tabla" style="cursor: pointer;">Producto</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Cantidad</th>
					<th width="20%" class="titulo_tabla" style="cursor: pointer;">Ultima actualizaci&oacute;n</th>
				</tr>
				</thead>
				<tbody>
				<?php
					$total_inventario=0;
					for($i=0;$i<$RowCount;$i++)
					{
						$total_inventario = $total_inventario + $resultado[$i]['cantidad'];
						?>
				<tr class="fila">
					<td class="texto_tabla"><?php echo $resultado[$i]['codigo']?></td>
					<td class="texto_tabla"><?php echo $resultado[$i]['aju_nombre']?></td>
					<td class="texto_tabla"><?php echo $resultado[$i]['pro_codigo']?></td>
					<td class="numero_tabla">$ <?php echo number_format($resultado[$i]['cantidad'])?></td>
					<td class="numero_tabla"><?php echo $resultado[$i]['fecha_transaccion']?></td>
				</tr>
				<?php 
					}
				?>
				</tbody>
				<tr>
					<td colspan="3" class="titulo_tabla">Total Registros: <?php echo $RowCount?></td>
					<td class="titulo_tabla">$ <?php echo number_format($total_inventario)?></td>
					<td class="titulo_tabla">--</td>
				</tr>
			</table>
		</td>
	</tr>
</table>			
<br>