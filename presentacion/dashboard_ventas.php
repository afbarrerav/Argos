<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
$conteoVentas = 0;
$totalVentas = 0;
for ($i=0;$i<$RowCount;$i++)
{
	$conteoVentas 	= $conteoVentas + $detalle['Ventas'][$i];
	$totalVentas	= $totalVentas + $detalle['TotalVentas'][$i];
}
 
?>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; ">
	<tr>
		<td class="titulo_tabla" width="33%">JVA</td>
		<td class="titulo_tabla" width="12%">Rutas</td>
		<td class="titulo_tabla" width="25%"># Ventas</td>
		<td class="titulo_tabla" width="30%">Total Ventas</td>
	</tr>
	<tr class="fila">
		<td class="texto_tabla"><?php echo $jva_nombre?></td>
		<td class="texto_tabla"><?php echo $RowCount?></td>
		<td class="texto_tabla"><?php echo $conteoVentas?></td>
		<td class="texto_tabla">$<?php echo number_format($totalVentas,2);?></td>
	</tr>
	<tr>
		<td class="titulo_tabla" colspan="3" style="text-align:right;">Total:</td>
		<td class="titulo_tabla" colspan="3">$<?php echo number_format($totalVentas,2);?></td>
	</tr>
</table>