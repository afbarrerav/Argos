<?php
/*
 * @author: MIGUEL POSADA
 * 			miguelrodriguezpo@hotmail.com
 * @version:2.0.0
 * @fecha:	Diciembre de 2012
 *
 * */
?>
<script type="text/javascript">
$(document).ready(function(){
	$('#detalle_saldos_recaudos').tablesorter();
});
</script>
<div id="DivDetalladoCliente"></div>
<div id="Dialog_vendedor"></div>
<br>
<table id="detalle_saldos_recaudos" border="0" width="100%" align="center" style="margin-bottom: 5px;background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td class="titulo_tabla" colspan="13">DETALLE SALDOS DE RUTA <strong style="cursor:pointer;" onclick="AjaxConsulta('../logica/rpt_dialog_vendedor.php', {VENDEDOR:'<?php echo $vendedor?>', ACCION:'consultar_informacion_vendedor'}, 'Dialog_vendedor');"><?php echo $vendedor?></strong>-<?php echo $ruta?></td>
	</tr>
	<tr>
		<td class="titulo_tabla">Clientes activos</td>
		<td class="titulo_tabla">Clientes Morosos</td>
		<td class="titulo_tabla">Clientes Morosos 120 dias</td>
		<td class="titulo_tabla">Clientes Morosos 180 dias</td>
		<td class="titulo_tabla">Clientes Morosos + 180 dias</td>
		<td class="titulo_tabla">total clientes</td>
	</tr>
	<tr style="font-weight:bold;font-size:30px;cursor:pointer;">
		<td class="texto_tabla" title="Clientes activos hasta 40 dias de mora" style="text-align: center" onclick="AjaxConsulta('../logica/rpt_saldos.php', { VENDEDOR:'<?php echo $vendedor?>', RUTA:'<?php echo $ruta?>', TIPO_CLIENTE:'Activo', TRJ_CODIGO:'<?php echo $trj_codigo?>', ACCION:'consulta_saldos_tipo_cliente'}, 'DivSaldo_de_ruta');"><?php echo $tipos_clientes['Activo']?></td>
		<td class="texto_tabla" title="Clientes con 40 a 60 dias de mora" style="text-align: center" onclick="AjaxConsulta('../logica/rpt_saldos.php', {VENDEDOR:'<?php echo $vendedor?>', RUTA:'<?php echo $ruta?>', TIPO_CLIENTE:'Moroso', TRJ_CODIGO:'<?php echo $trj_codigo?>', ACCION:'consulta_saldos_tipo_cliente'}, 'DivSaldo_de_ruta');"><?php echo $tipos_clientes['Moroso']?></td>
		<td class="texto_tabla" title="Clientes con 61 a 120 dias de mora" style="text-align: center" onclick="AjaxConsulta('../logica/rpt_saldos.php', {VENDEDOR:'<?php echo $vendedor?>', RUTA:'<?php echo $ruta?>', TIPO_CLIENTE:'Moroso 120', TRJ_CODIGO:'<?php echo $trj_codigo?>', ACCION:'consulta_saldos_tipo_cliente'}, 'DivSaldo_de_ruta');"><?php echo $tipos_clientes['Moroso 120']?></td>
		<td class="texto_tabla" title="Clientes con 120 a 180 dias de mora" style="text-align: center" onclick="AjaxConsulta('../logica/rpt_saldos.php', {VENDEDOR:'<?php echo $vendedor?>', RUTA:'<?php echo $ruta?>', TIPO_CLIENTE:'Moroso 180', TRJ_CODIGO:'<?php echo $trj_codigo?>', ACCION:'consulta_saldos_tipo_cliente'}, 'DivSaldo_de_ruta');"><?php echo $tipos_clientes['Moroso 180']?></td>
		<td class="texto_tabla" title="Clientes con con mas de 120 dias de mora" style="text-align: center" onclick="AjaxConsulta('../logica/rpt_saldos.php', {VENDEDOR:'<?php echo $vendedor?>', RUTA:'<?php echo $ruta?>', TIPO_CLIENTE:'Moroso 180 +', TRJ_CODIGO:'<?php echo $trj_codigo?>', ACCION:'consulta_saldos_tipo_cliente'}, 'DivSaldo_de_ruta');"><?php echo $tipos_clientes['Moroso 180 +']?></td>
		<td class="texto_tabla" title="Clientes con con mas de 120 dias de mora" style="text-align: center" onclick="AjaxConsulta('../logica/rpt_saldos.php', {VENDEDOR:'<?php echo $vendedor?>', RUTA:'<?php echo $ruta?>', TIPO_CLIENTE:'TODOS', TRJ_CODIGO:'<?php echo $trj_codigo?>', ACCION:'consulta_saldos_tipo_cliente'}, 'DivSaldo_de_ruta');"><?php echo $total_clientes =  $tipos_clientes['Activo']+$tipos_clientes['Moroso']+$tipos_clientes['Moroso 120']+$tipos_clientes['Moroso 180']+$tipos_clientes['Moroso 180 +'];?></td>
	</tr>
	</table>
	<table id="detalle_saldos_recaudos" border="0" width="100%" align="center" style="margin-bottom: 5px;background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td class="titulo_tabla">prestado</td>
		<td class="titulo_tabla">total</td>
		<td class="titulo_tabla">pagado</td>
		<td class="titulo_tabla">saldo</td>
	</tr>
	<tr style="font-weight:bold;font-size:30px;">
		<td class="texto_tabla" style="text-align: center;">$<?php echo number_format($total_valor_producto)?></td>
		<td class="texto_tabla" style="text-align: center;">$<?php echo number_format($total_valor_total)?></td>
		<td class="texto_tabla" style="text-align: center;">$<?php echo number_format($total_valor_total - $valor_total)?></td>
		<td class="texto_tabla" style="text-align: center;">$<?php echo number_format($valor_total)?></td>
	</tr>
</table>
<div id="DivSaldo_de_ruta">
<table id="detalle_saldos_recaudos" border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height: 75%;">
	<thead>	
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
			<tr class="fila" title="Haga clic para ver el detallado del cliente" style="cursor: pointer;" onclick="AjaxConsulta('../logica/rpt_dialog_cliente.php', {TV_CODIGO:'<?php echo $detalle['tv_codigo'][$i]?>', CLI_CODIGO:'<?php echo $detalle['cli_codigo'][$i]?>', VENDEDOR:'<?php echo $vendedor?>', ACCION:'consultar_detallado_cliente'}, 'DivDetalladoCliente');">
				<td class="texto_tabla"><?php echo $detalle['tv_codigo'][$i]?></td>
				<td class="texto_tabla"><?php echo $detalle['nroidentificacion'][$i]?></td>
				<td class="texto_tabla"><?php echo $detalle['referencia'][$i]?></td>
				<td class="texto_tabla"><?php echo $detalle['CLIENTE'][$i]?></td>
				<td class="texto_tabla"><?php echo date('Y-m-d', strtotime($detalle['fecha_entrega'][$i]))?></td>
				<td class="texto_tabla" style="text-align: right">$ <?php echo number_format($detalle['valor_producto'][$i])?></td>
				<td class="texto_tabla" style="text-align: right">$ <?php echo number_format($detalle['valor_total'][$i])?></td>
				<td class="texto_tabla" style="text-align: right">$ <?php echo number_format($detalle['valor_total'][$i] - $detalle['saldo'][$i])?></td>
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
</div>
