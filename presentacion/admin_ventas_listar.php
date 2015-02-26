<?php
/*
 * @author:	MIGUEL ABGEL POSADA
 * 			miguelrodriguezpo@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
?>
<br>
<script type="text/javascript">
$(document).ready(function(){
		$('#admin_ventas_listar').tablesorter();
	});
</script>
<table id="admin_ventas_listar" border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<thead>
		<tr>
			<th class="titulo_tabla" colspan="9">Ventas</th>
		</tr>
	
		<tr>
			<th class="titulo_tabla">Codigo</th>
			<th class="titulo_tabla">Cod Barras</th>
			<th class="titulo_tabla">Cod Argos</th>
			<th class="titulo_tabla">Fecha solicitud</th>
			<th class="titulo_tabla">Valor producto</th>
			<th class="titulo_tabla">Comision</th>
			<th class="titulo_tabla">Impuesto</th>
			<th class="titulo_tabla">Total</th>
			<th class="titulo_tabla">Fecha entrega</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$valor_producto = 0;
		$valor_comision_servicio = 0;
		$valor_impuesto = 0;
		$valor_total = 0;
		for($i=0;$i<$RowCount;$i++)
		{
			$valor_producto = $valor_producto + $detalle['valor_producto'][$i];
			$valor_comision_servicio = $valor_comision_servicio + $detalle['valor_comision_servicio'][$i];
			$valor_impuesto = $valor_impuesto + $detalle['valor_impuesto'][$i];
			$valor_total = $valor_total + $detalle['valor_total'][$i];
			?>
			<tr class="fila">
				<td class="texto_tabla"><?php echo $detalle['codigo'][$i] ?></td>
				<td class="texto_tabla"><?php echo $detalle['codigo_barras'][$i] ?></td>
				<td class="texto_tabla"><?php echo $detalle['referencia'][$i] ?></td>
				<td class="texto_tabla"><?php echo $detalle['fecha_solicitud'][$i] ?></td>
				<td class="texto_tabla" style="text-align:right;">$ <?php echo number_format($detalle['valor_producto'][$i]) ?></td>
				<td class="texto_tabla" style="text-align:right;">$ <?php echo number_format($detalle['valor_comision_servicio'][$i]) ?></td>
				<td class="texto_tabla" style="text-align:right;">$ <?php echo number_format($detalle['valor_impuesto'][$i]) ?></td>
				<td class="texto_tabla">$ <?php echo number_format($detalle['valor_total'][$i]) ?></td>
				<td class="texto_tabla"><?php echo $detalle['fecha_entrega'][$i] ?></td>
			</tr>
			<?php
		} 
		?>
	</tbody>
	<tr>
		<td class="titulo_tabla" colspan="4" style="text-align:right;">Total:</td>
		<td class="titulo_tabla" style="text-align:right;">$ <?php echo number_format($valor_producto) ?></td>
		<td class="titulo_tabla" style="text-align:right;">$ <?php echo number_format($valor_comision_servicio) ?></td>
		<td class="titulo_tabla" style="text-align:right;">$ <?php echo number_format($valor_impuesto) ?></td>
		<td class="titulo_tabla" style="text-align:right;">$ <?php echo number_format($valor_total) ?></td>
		<td class="titulo_tabla"></td>
	</tr>
	<tr>
		<td class="titulo_tabla" colspan="9">Cantidad de registros <?php echo $RowCount ?></td>
	</tr>
</table>