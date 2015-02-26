<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
?>
<div id="DetalladoClienteDiv"></div>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height:75%;">
	<tr>
		<td class="titulo_tabla" width="100%" colspan="8">Detalle Gastos Ruta <?php echo $ruta?> - <?php echo $fecha?></td>
	</tr>
	<tr>
		<td class="titulo_tabla" width="5%">Codigo</td>
		<td class="titulo_tabla" width="20%">Fecha Gasto</td>
		<td class="titulo_tabla" width="20%">Fecha Transacc&oacute;n</td>
		<td class="titulo_tabla" width="30%">Vendedor</td>
		<td class="titulo_tabla" width="25%">Valor</td>
	</tr>
	<?php
	if ($RowCount>0)
	{
		$Total = 0;
		for ($i=0;$i<$RowCount;$i++)
		{
	?>
	<tr class="fila">
		<td class="texto_tabla"><?php echo $detalle['codigo'][$i]?></td>
		<td class="texto_tabla"><?php echo $detalle['fecha_gasto'][$i]?></td>
		<td class="texto_tabla"><?php echo $detalle['fecha_trans'][$i]?></td>
		<td class="texto_tabla"><?php echo $detalle['ven_nombre'][$i]?></td>
		<td class="texto_tabla" style="text-align: right;">$<?php echo number_format($detalle['valor'][$i],2);?></td>
	</tr>
	<?php
		$Total = $Total + $detalle['valor'][$i];
		}
	?>
	<tr>
		<td class="titulo_tabla" colspan="4" style="text-align:right;">Total:</td>
		<td class="titulo_tabla" style="text-align: right;">$<?php echo number_format($Total,2);?></td>
	</tr>
	<?php 
	}
	else
	{
	?>
	<tr>
		<td style="text-align:center;" colspan="10"><?php echo "No hay datos.";?></td>
	</tr>
	<?php
	}
	?>
	<tr>
		<td class="titulo_tabla" colspan="10" style="text-align:center;">Total Registro <?php echo $RowCount?></td>
	</tr>
</table>