<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Diciembre de 2012
 *
 * */
if($accion == "mostrar_front_gastos")
{
	?>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; ">
	<tr>
		<td class="titulo_tabla" width="48%">JVA</td>
		<td class="titulo_tabla" width="20%"># Gastos</td>
		<td class="titulo_tabla" width="20%">Total</td>
	</tr>
	<?php
	$totalGastosValor	= 0;
	$totalGastos 		= 0;
	for($i=0;$i<$RowCount;$i++)
	{
		$totalGastosValor 	= $totalGastosValor + $resultado[$i]['valor'];
		$totalGastos 		= $totalGastos + $resultado[$i]['cant_gastos'];
		?>
	<tr>
		<td class="texto_tabla" align="right"><?php echo $resultado[$i]['jva_nombre']?></td>
		<td class="numero_tabla"><?php echo $resultado[$i]['cant_gastos']?></td>
		<td class="numero_tabla">$ <?php echo number_format($resultado[$i]['valor']) ?></td>
	</tr>
	<?php
	}
	?>
	<tr>
		<td class="subtitulo_tabla">TOTAL:</td>
		<td class="subtitulo_tabla"><?php echo $totalGastos?></td>
		<td class="subtitulo_tabla">$ <?php echo number_format($totalGastosValor) ?></td>
	</tr>
</table>

	<?php
}
else
{
	?>
<script>
$(document).ready(function() 
	    { 
	        $("#detalle_gastos").tablesorter(); 
	    } 
	); 
</script>
<table id="detalle_gastos" border="0" width="100%" align="center"
	style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height: 75%;">
	<thead>
		<tr>
			<th class="titulo_tabla" colspan="4">Detallado Gastos Dashboard - <?php echo $fecha?></th>
		</tr>

		<tr>
			<th class="titulo_tabla" width="35%">JVA</th>
			<th class="titulo_tabla" width="35%">Vendedor</th>
			<th class="titulo_tabla" width="10%">Cantidad de Gastos</th>
			<th class="titulo_tabla" width="20%">Total</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$TotalGastosJVA = 0;
	for($i=0;$i<$RowCount;$i++)
	{
		$TotalGastosJVA = $TotalGastosJVA + $detalle[$i]['valor'];
		?>
		<tr class="fila" style="text-decoration: none;"
			onclick="AjaxConsulta('../logica/rpt_gastos.php', {VEN_CODIGO:'<?php echo $detalle[$i]['aju_codigo']?>',  RUTA:'<?php echo $detalle[$i]['aju_nombre']?>', ACCION:'detalle_gastos_ruta'}, 'detalle_gastos_ruta_Div');">
			<td class="texto_tabla"><?php echo $detalle[$i]['jva_nombre']?></td>
			<td class="texto_tabla"><?php echo $detalle[$i]['aju_nombre']?></td>
			<td class="texto_tabla"><?php echo $detalle[$i]['cant_gastos']?></td>
			<td class="texto_tabla" style="text-align: right;">$<?php echo number_format($detalle[$i]['valor']);?></td>
		</tr>
		<?php
	}
	?>
	</tbody>
	<tr>
		<td class="titulo_tabla" colspan="3" style="text-align: right">Total:</td>
		<td class="titulo_tabla" style="text-align: right;">$<?php echo number_format($TotalGastosJVA,2);?></td>
	</tr>
	<tr>
		<td class="titulo_tabla" colspan="4">Total de registros <?php echo $RowCount?></td>
	</tr>
</table>
<br>
<div id="detalle_gastos_ruta_Div"></div>
	<?php
}
?>