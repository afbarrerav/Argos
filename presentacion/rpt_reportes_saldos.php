<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Octubre de 2012
 *
 * */
if($accion == "consultar_saldo_socio" || $accion == "consultar_saldos_socio_jva" || $accion == "consultar_saldos_socio_jva_vendedor")
{
	?>
	<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height: 75%;">
		<thead>	
			<tr>
				<th class="titulo_tabla" colspan="4">Detalle saldos</th>
			</tr>
	
			<tr>
				<th class="titulo_tabla" width="40%">Nombre ruta</th>
				<th class="titulo_tabla" width="40%">Vendedor</th>
				<th class="titulo_tabla" width="10%">Cantidad de clientes</th>
				<th class="titulo_tabla" width="10%">Valor cartera</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$sum_clientes = 0;
			$sum_valor = 0;
			for($i=0;$i<$RowCount;$i++)
			{
				$sum_clientes = $sum_clientes + $detalle['cant_clientes'][$i];
				$sum_valor	  = $sum_valor + $detalle['valor_cartera'][$i];
				?>
				<tr class="fila" style="cursor: pointer" onclick="AjaxConsulta('../logica/rpt_saldos.php', {TRJ_CODIGO:'<?php echo $detalle['trj_codigo'][$i]?>', VENDEDOR:'<?php echo $detalle['nombres'][$i]?>', RUTA:'<?php echo $detalle['descripcion'][$i]?>', ACCION:'detalle_saldos_ruta'}, 'DivSaldosRuta');">
					<td class="texto_tabla"><?php echo $detalle['descripcion'][$i]?></td>
					<td class="texto_tabla"><?php echo $detalle['nombres'][$i]?></td>
					<td class="texto_tabla"><?php echo $detalle['cant_clientes'][$i]?></td>
					<td class="texto_tabla" style="text-align: right;">$<?php echo number_format($detalle['valor_cartera'][$i])?></td>
				</tr>
				<?php
			}
			?>
			<tr>
				<td class="titulo_tabla" colspan="2" style="text-align: right;">Total: </td>
				<td class="titulo_tabla"><?php echo $sum_clientes?></td>
				<td class="titulo_tabla" style="text-align: right;">$<?php echo number_format($sum_valor)?></td>
			</tr>
			<tr>
				<td class="titulo_tabla" colspan="4">Total de registros <?php echo $RowCount?></td>
			</tr>
		</tbody>
	</table>
	<div id="DivSaldosRuta"></div>
	<?php
}
if($accion == "consultar_saldos_socio_jva_fechaInicio_fechaFin" ||$accion == "consultar_saldo_socio_fechaInicio_fechaFin" || $accion == "consultar_saldo_socio_jva_vendedor_fechaInicio_fechaFin" || $accion == "consultar_saldo_cliente" || $accion == "consultar_saldos_socio_consolidado")
{
	?>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#detalle_saldos_recaudos').tablesorter();
	});
	</script>
	<div id="DivDetalladoCliente"></div>
	<table id="detalle_saldos_recaudos" border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height: 75%;">
		<thead>	
			<tr>
				<th class="titulo_tabla" colspan="13">DETALLE RUTA</th>
			</tr>
			<!-- <tr>
				<td colspan="13">
					<table id="detalle_saldos_recaudos" border="0" width="100%" align="center" style="margin-bottom: 5px;background: #FFF; border: 1px solid #CCC;">
						<tr>
							<td class="titulo_tabla">Codigo</td>
							<td class="titulo_tabla">Vendedor</td>
							<td class="titulo_tabla">Activo</td>
							<td class="titulo_tabla">Moroso</td>
							<td class="titulo_tabla">Moroso 120 dias</td>
							<td class="titulo_tabla">Moroso 180 dias</td>
							<td class="titulo_tabla">Moroso + 180 dias</td>
						</tr>
						<tr>
							<td class="texto_tabla">Codigo</td>
							<td class="texto_tabla">Vendedor</td>
							<td class="texto_tabla">Activo</td>
							<td class="texto_tabla">Moroso</td>
							<td class="texto_tabla">Moroso 120 dias</td>
							<td class="texto_tabla">Moroso 180 dias</td>
							<td class="texto_tabla">Moroso + 180 dias</td>
						</tr>
					</table>
				</td>
			</tr> -->
			<tr>
				<th class="titulo_tabla">Id. producto</th>
				<th class="titulo_tabla">c.c cliente</th>
				<th class="titulo_tabla">Codigo</th>
				<th class="titulo_tabla">nombre y apellido</th>
				<th class="titulo_tabla">fecha</th>
				<th class="titulo_tabla">prestamo</th>
				<th class="titulo_tabla">total</th>
				<th class="titulo_tabla">pagado</th>
				<th class="titulo_tabla">saldo</th>
				<th class="titulo_tabla">cuotas</th>
				<th class="titulo_tabla">pagadas</th>
				<th class="titulo_tabla">pendiente</th>
				<th class="titulo_tabla">tipo de cliente</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$total_valor_total = '';
			$total_valor_producto = '';
			$total_valor_cuotas = '';
			$total_nro_cuotas = '';
			$total_cuotas_recaudadas = '';
			$total_cuotas_pendientes = '';
			$valor_total = '';
			if($RowCount > 0)
			{
			for($i=0;$i<$RowCount;$i++)
			{
				$total_valor_producto = $total_valor_producto + $detalle['valor_producto'][$i];
				$total_valor_total = $total_valor_total + $detalle['valor_total'][$i];
				$total_valor_cuotas = $total_valor_cuotas + $detalle['valor_cuota'][$i];
				$total_nro_cuotas = $total_nro_cuotas + $detalle['nro_cuotas'][$i];
				$total_cuotas_recaudadas = $total_cuotas_recaudadas + $detalle['cuotas_recaudadas'][$i];
				$total_cuotas_pendientes = $total_cuotas_pendientes + $detalle['cuotas_pendientes'][$i];
				$valor_total = $valor_total + $detalle['saldo'][$i];
				?>
				<tr class="fila" style="cursor: pointer;" onclick="AjaxConsulta('../logica/rpt_dialog_cliente.php', {TV_CODIGO:'<?php echo $detalle['tv_codigo'][$i]?>', CLI_CODIGO:'<?php echo $detalle['cli_codigo'][$i]?>', VENDEDOR:'<?php echo $vendedor?>', ACCION:'consultar_detallado_cliente'}, 'DivDetalladoCliente');">
					<td class="texto_tabla"><?php echo $detalle['tv_codigo'][$i]?></td>
					<td class="texto_tabla"><?php echo $detalle['nroidentificacion'][$i]?></td>
					<td class="texto_tabla"><?php echo $detalle['referencia'][$i]?></td>
					<td class="texto_tabla"><?php echo $detalle['CLIENTE'][$i]?></td>
					<td class="texto_tabla"><?php echo date('Y-m-d', strtotime($detalle['fecha_entrega'][$i]))?></td>
					<td class="texto_tabla" style="text-align: right">$ <?php echo number_format($detalle['valor_producto'][$i])?></td>
					<td class="texto_tabla" style="text-align: right">$ <?php echo number_format($detalle['valor_total'][$i])?></td>
					<td class="texto_tabla" style="text-align: right">$ <?php echo number_format($detalle['valor_cuota'][$i])?></td>
					<td class="texto_tabla" style="text-align: right">$ <?php echo number_format($detalle['saldo'][$i])?></td>
					<td class="texto_tabla" style="text-align: center"><?php echo $detalle['nro_cuotas'][$i]?></td>
					<td class="texto_tabla" style="text-align: center"><?php echo $detalle['cuotas_recaudadas'][$i]?></td>
					<td class="texto_tabla" style="text-align: center"><?php echo $detalle['cuotas_pendientes'][$i]?></td>
					<td class="texto_tabla"><?php echo $detalle['est_codigo'][$i]?></td>
				</tr>
				<?php
			}
			$prom_total_nro_cuotas = $total_nro_cuotas / $RowCount; 
			$prom_total_cuotas_recaudadas = $total_cuotas_recaudadas / $RowCount;
			$prom_total_cuotas_pendientes = $total_cuotas_pendientes / $RowCount;
			?>
			</tbody>
			<tr>
				<td class="titulo_tabla" colspan="5" style="text-align: right;">Total: </td>
				<td class="titulo_tabla" style="text-align: right">$ <?php echo number_format($total_valor_producto)?></td>
				<td class="titulo_tabla" style="text-align: right">$ <?php echo number_format($total_valor_total)?></td>
				<td class="titulo_tabla" style="text-align: right">$ <?php echo number_format($total_valor_cuotas)?></td>
				<td class="titulo_tabla" style="text-align: right">$ <?php echo number_format($valor_total)?></td>
				<td class="titulo_tabla" style="text-align: center"><?php echo round($prom_total_nro_cuotas)?></td>
				<td class="titulo_tabla" style="text-align: center"><?php echo round($prom_total_cuotas_recaudadas)?></td>
				<td class="titulo_tabla" style="text-align: center"><?php echo round($prom_total_cuotas_pendientes)?></td>
				<td class="titulo_tabla" colspan="2"></td>
			</tr>
			<tr>
				<td class="titulo_tabla" colspan="13">Total de registros: <?php echo $RowCount?></td>
			</tr>
	</table>
	<br>
	<?php
			}
			else
			{
				echo "<tr><td class='texto_tabla' colspan='15' style='text-align:center;'><strong>NO HAY RESULTADOS PARA EL CRITERIO DE BUSQUEDA</strong></td></tr>";
			}
}
?>