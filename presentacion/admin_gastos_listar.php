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
<br>

<script>
$(document).ready(function() 
	    { 
	        $("#gastos").tablesorter(); 
	    } 
	); 
</script>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2" class="titulo_tabla">
			<table width="100%" align="center">
				<tr>
					<td width="50%" class="titulo_tabla" colspan="6" style="text-align:center">GASTOS DESDE <?php echo $inicio?> HASTA <?php echo $fin?></td>	
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id="gastos" class="tablesorter" width="100%" style="background: #FFF; border: 1px solid #CCC;"> 
			<thead> 
				<tr>
					<th width="5%" class="titulo_tabla" style="cursor: pointer;">Codigo</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Fecha Gasto</th>
					<th width="25%" class="titulo_tabla" style="cursor: pointer;">Vendedor</th>
					<th width="15%" class="titulo_tabla" style="cursor: pointer;">Tipo Gasto</th>
					<th width="17%" class="titulo_tabla" style="cursor: pointer;">Valor</th>
					<th width="20%" class="titulo_tabla" style="cursor: pointer;">Fecha Transacci&oacute;n</th>
					<td width="8%" class="titulo_tabla" style="cursor: pointer;" colspan="2">Acci&oacute;n</td>
				</tr>
			</thead>
				<tbody>
				<?php
				$valortotal = 0;
				if ($RowCount>0)
				{
					for($i=0;$i<$RowCount;$i++)
					{
						$valortotal = $valortotal + $resultados['valor'][$i];
				?>
				<tr class="fila">
					<td class="texto_tabla"><?php echo $resultados['codigo'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['fecha_gasto'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['ven_nombre'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['nombre'][$i]?></td>
					<td class="texto_tabla">$<?php echo number_format($resultados['valor'][$i],2);?></td>
					<td class="texto_tabla"><?php echo $resultados['fecha_trans'][$i]?></td>
					<td class="texto_tabla">
						<img src="imagenes/iconos/edit_a.png" alt="Editar" title="Editar"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/edit.png'"
							onmouseout="this.src = 'imagenes/iconos/edit_a.png'"
							width="20"
							align="absbottom"
							onclick="AjaxConsulta('../logica/admin_gastos.php', {CODIGO:'<?php echo $resultados['codigo'][$i]?>', JVA_CODIGO:'<?php echo $jva?>', USU_CODIGO:'<?php echo $ven_codigo?>', ACCION:'editar'}, 'adm_gastos_listar');"/>
					</td>
					<td class="texto_tabla">
						<div id="estado<?php echo $resultados['codigo'][$i]?>">
							<script>AjaxConsulta('../logica/admin_gastos.php', {CODIGO:'<?php echo $resultados['codigo'][$i]?>',  ACCION:'estado'}, 'estado<?php echo $resultados['codigo'][$i]?>');</script>
						</div>
					</td>
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
					<td class="titulo_tabla">$<?php echo number_format($valortotal,2);?></td>
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