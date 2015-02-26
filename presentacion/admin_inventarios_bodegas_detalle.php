<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
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
<script type='text/javascript' src='../js/admin_inventarios_bodegas.js'></script>
<br>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td class="titulo_tabla" height= "20" colspan ="6">Inventario</td>
	</tr>
	<tr>
		<td  colspan ="6">
			<img src="imagenes/iconos/add_a.png" alt="Agregar" title="Agregar"
				style="cursor: pointer; border: 1px solid #CCC;"
				onmouseover="this.src = 'imagenes/iconos/add.png'"
				onmouseout="this.src = 'imagenes/iconos/add_a.png'"
				onclick="AjaxConsulta( '../logica/admin_inventarios_bodegas.php', {ACCION:'frm_agregar_producto_inventario'}, 'inventarioDiv' );"/>
		</td>
	</tr>
	<tr>
		<td>					
			<table id="Usuarios" width="100%" class="tablesorter" style="background: #FFF; border: 1px solid #CCC;"> 
			<thead> 
				<tr height="20">
					<th width="5%" class="titulo_tabla" style="cursor: pointer;">C&oacute;digo</th>
					<th width="30%" class="titulo_tabla" style="cursor: pointer;">Bodega</th>
					<th width="40%" class="titulo_tabla" style="cursor: pointer;">Producto</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Cantidad</th>
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
					<td class="texto_tabla"><div id="bod_codigoDiv<?php echo $i?>"><script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'admin_bodegas', CAMPO:'descripcion', NOMBRE:this.id, SIZE:'20', CONDICION:'<?php echo $resultados['bod_codigo'][$i]?>', DIV:'bod_codigoDiv<?php echo $i?>', ACCION:'consultar_campo_bloqueado'}, 'bod_codigoDiv<?php echo $i?>' );</script></div></td>
					<td class="texto_tabla"><div id="pro_codigoDiv<?php echo $i?>"><script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'param_productos_jva', CAMPO:'nombre', NOMBRE:this.id, SIZE:'20', CONDICION:'<?php echo $resultados['pro_codigo'][$i]?>', DIV:'pro_codigoDiv<?php echo $i?>', ACCION:'consultar_campo_bloqueado'}, 'pro_codigoDiv<?php echo $i?>');</script></div></td>
					<td class="texto_tabla"><?php echo $resultados['cantidad'][$i]?></td>
					<td class="texto_tabla"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_estados', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'<?php echo $resultados['est_codigo'][$i]?>', CODIGO_REGISTRO:'<?php echo $resultados['codigo'][$i]?>',  DIV:'estDiv<?php echo $i?>', NOMBRE_SELECT:'sest<?php echo $i?>', ESTADO:'disabled', ACCION:'consultar_campo'}, 'estDiv<?php echo $i?>' );</script><div id="estDiv<?php echo $i?>"></div></td>
					<td class="texto_tabla">
						<img src="imagenes/iconos/edit_a.png" alt="Cambiar estado" title="Cambiar estado"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/edit.png'"
							onmouseout="this.src = 'imagenes/iconos/edit_a.png'"
							width="20"
							align="absbottom"
							onclick=""/>
					</td>
				</tr>
				<?php 
					}
				?>
				</tbody>
				<tr>
					<td colspan="6" class="titulo_tabla">Total Registros: <?php echo $RowCount?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>			
<br>