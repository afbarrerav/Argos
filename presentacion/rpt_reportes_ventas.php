<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Octubre de 2012
 *
 * */
if($accion == "consultar_ventas_socio" || $accion == "consultar_ventas_socio_jva" || $accion == "consultar_ventas_socio_jva_vendedor")
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
				<th class="titulo_tabla" colspan="4">Detallado Ventas</th>
			</tr>
	
			<tr>
				<th class="titulo_tabla" width="40%">JVA</th>
				<th class="titulo_tabla" width="40%">Ruta</th>
				<th class="titulo_tabla" width="10%">Cantidad de Ventas</th>
				<th class="titulo_tabla" width="10%">Total Ventas</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$TotalVentasJVA = 0;
		for($i=0;$i<$RowCount;$i++)
		{
			$TotalVentasJVA = $TotalVentasJVA + $detalle['TotalVentas'][$i];
			?>
			<tr class="fila" style="cursor: pointer" onclick="AjaxConsulta('../logica/rpt_ventas.php', {VEN_CODIGO:'<?php echo $detalle['ven_codigo'][$i]?>',  RUTA:'<?php echo $detalle['nombre'][$i]?>', ACCION:'reporte_detalle_ventas_ruta'}, 'detalle_venta');">
				<td class="texto_tabla"><?php echo $detalle['jva_nombre'][$i]?></td>
				<td class="texto_tabla"><?php echo $detalle['nombre'][$i]?></td>
				<td class="texto_tabla"><?php echo $detalle['Ventas'][$i]?></td>
				<td class="texto_tabla" style="text-align: right">$<?php echo number_format($detalle['TotalVentas'][$i]);?></td>
			</tr>
			<?php
		}
		?>
		</tbody>
		<tr>
			<td class="titulo_tabla" colspan="3" style="text-align:right">Total:</td>
			<td class="titulo_tabla" style="text-align: right">$<?php echo number_format($TotalVentasJVA,2);?></td>
		</tr>
		<tr>
			<td class="titulo_tabla" colspan="4">Total de registros <?php echo $RowCount?></td>
		</tr>
	</table>
	<br>
	<div id="detalle_venta"></div>
	<?php
}
if($accion == "consultar_ventas_socio_jva_fechaInicio_fechaFin" || $accion == "reporte_detalle_ventas_ruta" || $accion == "consultar_ventas_socio_fechaInicio_fechaFin" || $accion == "consultar_ventas_socio_jva_vendedor_fechaInicio_fechaFin" || $accion == "consultar_ventas_cliente" || $accion == "consultar_ventas_socio_consolidado" || $accion == "consultar_ventas_cliente_fecha_inicio_fecha_fin")
{
	?>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#rpt_reporte_ventas').tablesorter();
	});
	</script>
	<div id="DetalladoClienteDiv"></div>
	<table id="rpt_reporte_ventas" border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height:75%;">
		<thead>	
			<tr>
				<th class="titulo_tabla" width="100%" colspan="9">Detalle ventas ruta</th>
			</tr>
			<tr>
				<th class="titulo_tabla" width="7%">Id. producto</th>
				<th class="titulo_tabla" width="20%">vendedor</th>
				<th class="titulo_tabla" width="10%">c.c cliente</th>
				<th class="titulo_tabla" width="7%">codigo</th>
				<th class="titulo_tabla" width="20%">nombre y apellido</th>
				<th class="titulo_tabla" width="10%">fecha solicitud</th>
				<th class="titulo_tabla" width="10%">fecha entrega</th>
				<th class="titulo_tabla" width="7%">prestamo</th>
				<th class="titulo_tabla" width="7%">total</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if ($RowCount>0)
			{
				$Total = 0;
				$valor_total = 0;
				for ($i=0;$i<$RowCount;$i++)
				{
			?>
			<tr class="fila" style="cursor: pointer" onclick="AjaxConsulta('../logica/rpt_dialog_cliente.php', {TV_CODIGO:'<?php echo $detalle['codigo'][$i]?>', CLI_CODIGO:'<?php echo $detalle['cli_codigo'][$i]?>', VENDEDOR:'<?php echo $detalle['ven_nombre'][$i]?>', ACCION:'consultar_detallado_cliente'}, 'DetalladoClienteDiv');">
				<td class="texto_tabla"><?php echo $detalle['codigo'][$i]?></td>
				<td class="texto_tabla"><?php echo $detalle['ven_nombre'][$i]?></td>
				<td class="texto_tabla"><?php echo $detalle['nroidentificacion'][$i]?></td>
				<td class="texto_tabla"><?php echo $detalle['referencia'][$i]?></td>
				<td class="texto_tabla"><?php echo $detalle['nom_cliente'][$i]?></td>
				<td class="texto_tabla"><?php echo date('Y-m-d', strtotime($detalle['fecha_solicitud'][$i]))?></td>
				<td class="texto_tabla"><?php echo date('Y-m-d', strtotime($detalle['fecha_entrega'][$i]))?></td>
				<td class="texto_tabla" style="text-align: right">$<?php echo number_format($detalle['valor_producto'][$i],2);?></td>
				<td class="texto_tabla" style="text-align: right">$<?php echo number_format($detalle['valor_total'][$i],2);?></td>
			</tr>
			<?php
				$Total = $Total + $detalle['valor_producto'][$i];
				$valor_total = $valor_total + $detalle['valor_total'][$i];
				}
			?>
		</tbody>
		<tr>
			<td class="titulo_tabla" colspan="7" style="text-align:right;">Total:</td>
			<td class="titulo_tabla" style="text-align: right">$<?php echo number_format($Total,2);?></td>
			<td class="titulo_tabla" style="text-align: right">$<?php echo number_format($valor_total,2);?></td>
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
	<?php
}
if($accion == "consultar_ventas_socio_fechaInicio_fechaFin_consolidado" || $accion == "consultar_ventas_socio_jva_fechaInicio_fechaFin_consolidado" || $accion=="consultar_ventas_socio_jva_vendedor_fechaInicio_fechaFin_consolidado")
{
	?>
		<script type="text/javascript">
	$(document).ready(function(){
		$('#rpt_reporte_ventas').tablesorter();
	});
	</script>
	<div id="DetalladoClienteDiv"></div>
	<table id="rpt_reporte_ventas" border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height:75%;">
		<thead>	
			<tr>
				<th class="titulo_tabla" width="100%" colspan="9">Detalle ventas ruta</th>
			</tr>
			<tr>
				<th class="titulo_tabla" width="20%">vendedor</th>
				<th class="titulo_tabla" width="7%">prestamo</th>
				<th class="titulo_tabla" width="7%">total</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if ($RowCount>0)
			{
				$Total = 0;
				$valor_total = 0;
				for ($i=0;$i<$RowCount;$i++)
				{
			?>
			<tr class="fila">
				<td class="texto_tabla"><?php echo $detalle['ven_nombre'][$i]?></td>
				<td class="texto_tabla" style="text-align: right">$<?php echo number_format($detalle['valor_producto'][$i],2);?></td>
				<td class="texto_tabla" style="text-align: right">$<?php echo number_format($detalle['valor_total'][$i],2);?></td>
			</tr>
			<?php
				$Total = $Total + $detalle['valor_producto'][$i];
				$valor_total = $valor_total + $detalle['valor_total'][$i];
				}
			?>
		</tbody>
		<tr>
			<td class="titulo_tabla" style="text-align:right;">Total:</td>
			<td class="titulo_tabla" style="text-align: right">$<?php echo number_format($Total,2);?></td>
			<td class="titulo_tabla" style="text-align: right">$<?php echo number_format($valor_total,2);?></td>
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
	<?php
}
?>