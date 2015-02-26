<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Octubre de 2012
 *
 * */
if($accion == "consultar_recaudos_socio" || $accion == "consultar_recaudos_socio_jva" ||$accion == "consultar_recaudos_socio_jva_fechaInicio_fechaFin" || $accion == "consultar_recaudos_socio_jva_vendedor")
{
	?>
	<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
		<tr>
			<td class="titulo_tabla" colspan="4">Reporte recaudos</td>
		</tr>
		<tr>
			<td class="titulo_tabla">Ruta</td>
			<td class="titulo_tabla">Vendedor</td>
			<td class="titulo_tabla"># Clientes</td>
			<td class="titulo_tabla">Valor Cartera</td>
		</tr>
		<?php
			if($RowCount != 0)
			{
						$total_clientes = '';
						$total_saldos = '';
						for($i=0;$i<$RowCount;$i++)
						{
							$total_clientes = $total_clientes + $detalle['nro_clientes'][$i];
							$total_saldos = $total_saldos + $detalle['saldo'][$i];
							?>
							<tr class="fila" style="cursor: pointer;" onclick="AjaxConsulta('../logica/rpt_recaudos.php', {TRJ_CODIGO:'<?php echo $detalle['codigo'][$i]?>', ACCION:'consultar_ruta_jva'}, 'DivDetalleRecaudo');">
								<td class="texto_tabla"><?php echo $detalle['nombre'][$i]?></td>
								<td class="texto_tabla"><?php echo $detalle['aju_nombre'][$i]?></td>
								<td class="texto_tabla"><?php echo $detalle['nro_clientes'][$i]?></td>
								<td class="texto_tabla" style="text-align: right;">$ <?php echo number_format($detalle['saldo'][$i])?></td>
							</tr>
							<?php
						}
					?>
					<tr>
						<td class="titulo_tabla" colspan="2" style="text-align: right;">Total:</td>
						<td class="titulo_tabla" style="text-align: left;"><?php echo $total_clientes?></td>
						<td class="titulo_tabla" style="text-align: right;">$ <?php echo number_format($total_saldos)?></td>
					</tr>
				<?php
				}
				else
				{
					echo "<tr><td class='texto_tabla' colspan='20' style='text-align:center;'><strong>NO EXISTEN RESULTADOS PARA ESA BUSQUEDA</strong></td></tr>";
				}
				?>
		<tr>
			<td class="titulo_tabla" colspan="4">Total de registros <?php echo $RowCount?></td>
		</tr>
	</table>
	<div id="DivDetalleRecaudo"></div>
	<?php
}
if($accion == "consultar_recaudos_socio_fechaInicio_fechaFin" || $accion == "consultar_recaudos_socio_jva_vendedor_fechaInicio_fechaFin" || $accion == "consultar_recaudos_socio_cliente" || $accion == "consultar_ruta_jva" || $accion == "consultar_recaudos_socio_cliente_fecha_inicio_fecha_fin")
{
	?>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#rpt_recaudado').tablesorter();
	});
	</script>
	<div id="DivDetalladoCliente"></div>
	<table id="rpt_recaudado" border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height: 75%;">
		<thead>	
			<tr>
				<th class="titulo_tabla" colspan="10">Detalle Recaudado</th>
			</tr>
		
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
				<th class="titulo_tabla">tipo de cliente</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if($RowCount != 0)
			{
				$total_producto = 0;
				$total_valor = 0;
				$valor_pago_total = 0;
				for($i=0;$i<$RowCount;$i++)
				{
					$total_producto = $total_producto + $detalle['valor_producto'][$i];
					$total_valor = $total_valor + $detalle['valor_total'][$i];
					$valor_pago_total = $valor_pago_total + $detalle['valor_pago'][$i];
					?>
					<tr class="fila" style="cursor: pointer;" onclick="AjaxConsulta('../logica/rpt_dialog_cliente.php', {TV_CODIGO:'<?php echo $detalle['tv_codigo'][$i]?>', CLI_CODIGO:'<?php echo $detalle['cli_codigo'][$i]?>', VENDEDOR:'<?php echo $detalle['nombre_vendedor'][$i]?>', ACCION:'consultar_detallado_cliente'}, 'DivDetalladoCliente');">
						<td class="texto_tabla"><?php echo $detalle['tv_codigo'][$i]?></td>
						<td class="texto_tabla"><?php echo $detalle['nroidentificacion'][$i]?></td>
						<td class="texto_tabla"><?php echo $detalle['referencia'][$i]?></td>
						<td class="texto_tabla"><?php echo $detalle['cliente'][$i]?></td>
						<td class="texto_tabla"><?php echo date('Y-m-d', strtotime($detalle['fecha_recaudo'][$i]))?></td>
						<td class="texto_tabla" style="text-align:right">$<?php echo number_format($detalle['valor_producto'][$i])?></td>
						<td class="texto_tabla" style="text-align:right">$<?php echo number_format($detalle['valor_total'][$i])?></td>
						<td class="texto_tabla" style="text-align:right">$<?php echo number_format($detalle['valor_pago'][$i])?></td>
						<th class="texto_tabla" width="10%" style="text-align:right;font-weight:normal;">
							<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'trans_rutas_detalles', CAMPO:"CONCAT('$', saldo)", CAMPO_CONDICION:'tv_codigo', CONDICION:'<?php echo $detalle['tv_codigo'][$i]?>', ACCION:'consultar_valor_campo2'}, 'DivSaldo<?php echo $i?>');</script>
							<div id="DivSaldo<?php echo $i?>"></div>
						</th>
						<td class="texto_tabla"><?php echo $detalle['est_codigo'][$i]?></td>
					</tr>
					<?php
				}
				?>
				</tbody>
				<tr>
					<td class="titulo_tabla" style="text-align: right" colspan="5">Total: </td>
					<td class="titulo_tabla" style="text-align:right">$<?php echo number_format($total_producto)?></td>
					<td class="titulo_tabla" style="text-align:right">$<?php echo number_format($total_valor)?></td>
					<td class="titulo_tabla" style="text-align:right">$<?php echo number_format($valor_pago_total)?></td>
					<td class="titulo_tabla" colspan="2"></td>
				</tr>
			<?php
			}
			else
			{
				echo "<tr><td class='texto_tabla' colspan='20' style='text-align:center;'><strong>NO EXISTEN RESULTADOS PARA ESA BUSQUEDA</strong></td></tr>";
			}
			?>
			<tr>
				<td class="titulo_tabla" colspan="10">Total de registros <?php echo $RowCount?></td>
			</tr>
	</table>
	<?php
}
if($accion=="consultar_recaudos_socio_fechaInicio_fechaFin_consolidado" || $accion=="consultar_recaudos_socio_jva_vendedor_fechaInicio_fechaFin")
{
	?>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#rpt_recaudado').tablesorter();
	});
	</script>
	<div id="DivDetalladoCliente"></div>
	<table id="rpt_recaudado" border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height: 75%;">
		<thead>	
			<tr>
				<th class="titulo_tabla" colspan="11">Detalle Recaudado</th>
			</tr>
		
			<tr>
				<th class="titulo_tabla">Id. producto</th>
				<th class="titulo_tabla">c.c cliente</th>
				<th class="titulo_tabla">Codigo</th>
				<th class="titulo_tabla">vendedor</th>
				<th class="titulo_tabla">nombre y apellido</th>
				<th class="titulo_tabla">fecha</th>
				<th class="titulo_tabla">prestamo</th>
				<th class="titulo_tabla">total</th>
				<th class="titulo_tabla">pagado</th>
				<th class="titulo_tabla">tipo de cliente</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if($RowCount != 0)
			{
				$total_producto = 0;
				$total_valor = 0;
				$valor_pago_total = 0;
				for($i=0;$i<$RowCount;$i++)
				{
					$total_producto = $total_producto + $detalle['valor_producto'][$i];
					$total_valor = $total_valor + $detalle['valor_total'][$i];
					$valor_pago_total = $valor_pago_total + $detalle['valor_pago'][$i];
					?>
					<tr class="fila" style="cursor: pointer;" onclick="AjaxConsulta('../logica/rpt_dialog_cliente.php', {TV_CODIGO:'<?php echo $detalle['tv_codigo'][$i]?>', CLI_CODIGO:'<?php echo $detalle['cli_codigo'][$i]?>', VENDEDOR:'<?php echo $detalle['nombre_vendedor'][$i]?>', ACCION:'consultar_detallado_cliente'}, 'DivDetalladoCliente');">
						<td class="texto_tabla"><?php echo $detalle['tv_codigo'][$i]?></td>
						<td class="texto_tabla"><?php echo $detalle['nroidentificacion'][$i]?></td>
						<td class="texto_tabla"><?php echo $detalle['referencia'][$i]?></td>
						<td class="texto_tabla"><?php echo $detalle['nombre_vendedor'][$i]." ".$detalle['apellido_vendedor'][$i]?></td>
						<td class="texto_tabla"><?php echo $detalle['cliente'][$i]?></td>
						<td class="texto_tabla"><?php echo date('Y-m-d', strtotime($detalle['fecha_recaudo'][$i]))?></td>
						<td class="texto_tabla" style="text-align:right">$<?php echo number_format($detalle['valor_producto'][$i])?></td>
						<td class="texto_tabla" style="text-align:right">$<?php echo number_format($detalle['valor_total'][$i])?></td>
						<td class="texto_tabla" style="text-align:right">$<?php echo number_format($detalle['valor_pago'][$i])?></td>
						<td class="texto_tabla"><?php echo $detalle['est_codigo'][$i]?></td>
					</tr>
					<?php
				}
				?>
				</tbody>
				<tr>
					<td class="titulo_tabla" style="text-align: right" colspan="6">Total: </td>
					<td class="titulo_tabla" style="text-align:right">$<?php echo number_format($total_producto)?></td>
					<td class="titulo_tabla" style="text-align:right">$<?php echo number_format($total_valor)?></td>
					<td class="titulo_tabla" style="text-align:right">$<?php echo number_format($valor_pago_total)?></td>
					<td class="titulo_tabla" colspan="2"></td>
				</tr>
			<?php
			}
			else
			{
				echo "<tr><td class='texto_tabla' colspan='20' style='text-align:center;'><strong>NO EXISTEN RESULTADOS PARA ESA BUSQUEDA</strong></td></tr>";
			}
			?>
			<tr>
				<td class="titulo_tabla" colspan="11">Total de registros <?php echo $RowCount?></td>
			</tr>
	</table>
	<?php
}
?>
