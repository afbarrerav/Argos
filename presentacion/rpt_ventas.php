<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
if($accion == "mostrar_front_ventas")
{
?>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; ">
	<tr>
		<td class="titulo_tabla" width="48%">JVA</td>
		<td class="titulo_tabla" width="12%">Rutas</td>
		<td class="titulo_tabla" width="25%"># Ventas</td>
		<td class="titulo_tabla" width="15%">Total</td>
	</tr>
		<?php 
		$total_rutas = 0;
		$total_clientes = 0;
		$total_saldo = 0;
		$totalVentas = 0;
		for($i=0;$i<$RowCount;$i++)
		{
			$total_rutas = $total_rutas + $resultado[$i]['nro_rutas'];
			$total_clientes = $total_clientes + $resultado[$i]['nro_clientes'];
			$total_saldo = $total_saldo + $resultado[$i]['valor_producto'];
			$totalVentas = $totalVentas + $resultado[$i]['nro_ventas'];
		?>
		<tr>
			<td class="texto_tabla" align="right"><?php echo $resultado[$i]['jva_nombre']?></td>
			<td class="numero_tabla" ><?php echo $resultado[$i]['nro_rutas'] ?></td>
			<td class="numero_tabla" ><?php echo $resultado[$i]['nro_ventas'] ?></td>
			<td class="numero_tabla" >$ <?php echo number_format($resultado[$i]['valor_producto']) ?></td>
		</tr>
		<?php 
		}
		?>
		<tr>
			<td class="subtitulo_tabla">TOTAL:</td>
			<td class="subtitulo_tabla"><?php echo $total_rutas?></td>
			<td class="subtitulo_tabla"><?php echo $totalVentas?></td>
			<td class="subtitulo_tabla">$ <?php echo number_format($total_saldo) ?></td>
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
	        $("#detalle_ventas").tablesorter(); 
	    } 
	); 
</script>
<table id="detalle_ventas" border="0" width="100%" align="center"
	style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height: 75%;">
	<thead>
		<tr>
			<th class="titulo_tabla" colspan="5">Detallado Ventas Dashboard - <?php echo $fecha?></th>
		</tr>

		<tr>
			<th class="titulo_tabla" width="30%">JVA</th>
			<th class="titulo_tabla" width="40%">Ruta</th>
			<th class="titulo_tabla" width="10%">Cantidad de Ventas</th>
			<th class="titulo_tabla" width="10%">Valor Producto</th>
			<th class="titulo_tabla" width="10%">Total</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$TotalVentasJVA = 0;
	$TotalProductoJVA = 0;
	for($i=0;$i<$RowCount;$i++)
	{
		$TotalVentasJVA = $TotalVentasJVA + $detalle['TotalVentas'][$i];
		$TotalProductoJVA = $TotalProductoJVA + $detalle['TotalProducto'][$i]; 
		?>
		<tr class="fila" style="text-decoration: none; cursor: pinter;"
			onclick="AjaxConsulta('../logica/rpt_ventas.php', {VEN_CODIGO:'<?php echo $detalle['ven_codigo'][$i]?>',  RUTA:'<?php echo $detalle['nombre'][$i]?>', ACCION:'detalle_ventas_ruta'}, 'detalle_venta');">
			<td class="texto_tabla"><?php echo $detalle['jva_nombre'][$i]?></td>
			<td class="texto_tabla"><?php echo $detalle['nombre'][$i]?></td>
			<td class="texto_tabla"><?php echo $detalle['Ventas'][$i]?></td>
			<td class="texto_tabla" style="text-align: right">$<?php echo number_format($detalle['TotalProducto'][$i]);?></td>
			<td class="texto_tabla" style="text-align: right">$<?php echo number_format($detalle['TotalVentas'][$i]);?></td>
		</tr>
		<?php
	}
	?>
	</tbody>
	<tr>
		<td class="titulo_tabla" colspan="3" style="text-align: right">Total:</td>
		<td class="titulo_tabla" style="text-align: right">$<?php echo number_format($TotalProductoJVA);?></td>
		<td class="titulo_tabla" style="text-align: right">$<?php echo number_format($TotalVentasJVA);?></td>
	</tr>
	<tr>
		<td class="titulo_tabla" colspan="5">Total de registros <?php echo $RowCount?></td>
	</tr>
</table>
<br>
<div id="detalle_venta"></div>
	<?php
}
?>