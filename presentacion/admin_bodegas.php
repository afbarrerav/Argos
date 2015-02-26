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
<script type='text/javascript' src='../js/admin_bodegas.js'></script>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td>
			<table border="0" width="100%" align="center" style="background: #FFF; border: 0px solid #CCC;">
				<tr height="30">
					<td class="titulo_tabla">Administrar Bodegas</td>
				</tr>
				<tr>
					<td colspan="4" style="background: #FFF; border: 1px solid #CCC;">
						<img src="imagenes/iconos/add_a.png" alt="Crear nueva bodega" title="Crear nueva bodega"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/add.png'"
							onmouseout="this.src = 'imagenes/iconos/add_a.png'"
							onclick="AjaxConsulta( '../presentacion/admin_bodegas_n.php', {ACCION:'listar'}, 'area_trabajo' );"/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>					
			<table id="Usuarios" width="100%" class="tablesorter" style="background: #FFF; border: 1px solid #CCC;"> 
			<thead> 
				<tr height="20">
					<th width="5%" class="titulo_tabla" style="cursor: pointer;">C&oacute;digo</th>
					<th width="20%" class="titulo_tabla" style="cursor: pointer;">Descripcion</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Usuario</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Estado</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Accion</th>
				</tr>
				</thead>
				<tbody>
				<?php
					for($i=0;$i<$RowCount;$i++)
					{
						?>
				<tr class="fila">
					<td class="texto_tabla"><?php echo $resultados['codigo'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['descripcion'][$i]?></td>
					<td class="texto_tabla"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'admin_jva_usuarios', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'<?php echo $resultados['aju_codigo'][$i]?>', CODIGO_REGISTRO:'<?php echo $resultados['codigo'][$i]?>',  DIV:'ajuDiv<?php echo $resultados['codigo'][$i]?>', NOMBRE_SELECT:'saju<?php echo $resultados['codigo'][$i]?>', ESTADO:'disabled', ACCION:'consultar_campo_aju'}, 'ajuDiv<?php echo $resultados['codigo'][$i]?>' );</script><div id="ajuDiv<?php echo $resultados['codigo'][$i]?>"></div></td>
					<td class="texto_tabla"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_estados', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'<?php echo $resultados['est_codigo'][$i]?>', CODIGO_REGISTRO:'<?php echo $resultados['codigo'][$i]?>',  DIV:'estDiv<?php echo $resultados['codigo'][$i]?>', NOMBRE_SELECT:'sest<?php echo $resultados['codigo'][$i]?>', ESTADO:'disabled', ACCION:'consultar_campo'}, 'estDiv<?php echo $resultados['codigo'][$i]?>' );</script><div id="estDiv<?php echo $resultados['codigo'][$i]?>"></div></td>
					<td class="texto_tabla">
						<img src="imagenes/cash-register2.png" alt="Administrar mercancias" title="Administrar mercancias"
							onmouseover="this.src = 'imagenes/cash-register2_a.png'"
							onmouseout="this.src = 'imagenes/cash-register2.png'"
							width="28px"
							onclick="AjaxConsulta( '../logica/admin_mercancias.php', {BOD_CODIGO:<?php echo $resultados['codigo'][$i]?>, ACCION:'mostrar_front'}, 'area_trabajo' );"
							border="0"/> &nbsp;
						<img src="imagenes/money5.png" alt="Mis inventarios" title="Mis inventarios"
							onmouseover="this.src = 'imagenes/money5_a.png'"
							onmouseout="this.src = 'imagenes/money5.png'"
							width="28px"
							onclick="AjaxConsulta( '../logica/admin_inventarios_bodegas.php', {sbodega:<?php echo $resultados['codigo'][$i]?>, ACCION:'listar'}, 'area_trabajo' );"
							border="0"/>
				</li>
					</td>
				</tr>
				<?php 
					}
				?>
				</tbody>
				<tr>
					<td colspan="5" class="titulo_tabla">Total Registros: <?php echo $RowCount?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div id="pass"></div>
<br>