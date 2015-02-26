<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
?>
<script>
$(document).ready(function() 
	    { 
	        $("#traslados").tablesorter(); 
	    } 
	); 
</script>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2" class="titulo_tabla">
			<table width="100%" align="center">
				<tr>
					<td width="50%" class="titulo_tabla" colspan="6" style="text-align:center">Lista de Traslados</td>	
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id="traslados" class="tablesorter" width="100%" style="background: #FFF; border: 1px solid #CCC;"> 
			<thead> 
				<tr>
					<th width="5%" class="titulo_tabla" style="cursor: pointer;">Codigo</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Tipo traslado</th>
					<th width="25%" class="titulo_tabla" style="cursor: pointer;">Desde</th>
					<th width="15%" class="titulo_tabla" style="cursor: pointer;">Hasta</th>
					<th width="17%" class="titulo_tabla" style="cursor: pointer;">Cantidad</th>
					<th width="20%" class="titulo_tabla" style="cursor: pointer;">Fecha</th>
					<td width="8%" class="titulo_tabla" style="cursor: pointer;" colspan="2">Acci&oacute;n</td>
				</tr>
			</thead>
				<tbody>
				<?php
				$cantidadTraslados = 0;
				if ($RowCount>0)
				{
					for($i=0;$i<$RowCount;$i++)
					{
						$cantidadTraslados = $cantidadTraslados + $resultado['cantidad'][$i];
				?>
				<tr class="fila">
					<td class="texto_tabla"><?php echo $resultado['codigo'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultado['ptij_codigo'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultado['aju_codigo_desde'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultado['aju_codigo_hasta'][$i]?></td>
					<td class="texto_tabla">$<?php echo number_format($resultado['cantidad'][$i],2);?></td>
					<td class="texto_tabla"><?php echo $resultado['fecha_trans'][$i]?></td>
				</tr>
				<?php 
					}
				}
				else
				{
					echo "No hay infomaci&oacute;n para mostrar.";
				}
				?>
				</tbody>
				<tr>
					<td colspan="4" class="subtitulo_tabla" style="background: #CCD6CD;">Valor Total:</td>
					<td class="titulo_tabla">$<?php echo number_format($cantidadTraslados,2);?></td>
					<td colspan="3" class="titulo_tabla"></td>
				</tr>
				<tr>
					<td colspan="8" class="titulo_tabla">Total Registros: <?php echo $RowCount?></td>
				</tr>
			</table>		
		</td>
	</tr>
</table>	
<br>