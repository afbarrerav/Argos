<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 */
?>
<table id="detalle_saldos_recaudos" border="0" width="100%" align="center" style="margin-bottom: 5px;background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td class="titulo_tabla" colspan="2">Reporte balance de ruta</td>
	</tr>
	<tr>
		<td>
			<table border="0" width="100%" align="center" style="margin-bottom: 5px;background: #FFF; border: 1px solid #CCC;">
				<tr>
					<td class="titulo_tabla" colspan=2>INICIO</td>
				</tr>
				<tr>
					<td class="subtitulo_tabla" WIDTH="50%">HORA</td>
					<td class="texto_tabla" WIDTH="50%"><?php echo $login?></td>
				</tr>
				<tr>	
					<td class="subtitulo_tabla">PRIMER RECAUDO</td>
					<td class="texto_tabla"><?php echo $hora_primer_recaudo?></td>
				</tr>
				<tr>	
					<td class="subtitulo_tabla">PRIMER VENTA</td>
					<td class="texto_tabla"><?php echo $hora_primer_venta?></td>
				</tr>
				<tr>	
					<td class="subtitulo_tabla">CARTERA</td>
					<td class="texto_tabla">$<?php echo number_format($SaldoInicial)?></td>
				</tr>
				<tr>
					<td class="subtitulo_tabla">CLIENTES</td>
					<td class="texto_tabla"><?php echo $nro_clientes_inicial?></td>
				</tr>
				<tr>	
					<td class="subtitulo_tabla">CARGUE</td>
					<td class="texto_tabla">$<?php echo number_format($cargue_inicial)?></td>
				</tr>
				<tr>	
					<td class="subtitulo_tabla">&nbsp;</td>
					<td class="texto_tabla">&nbsp;</td>
				</tr>
				<tr>
					<td class="subtitulo_tabla">VALOR A RECAUDAR</td>
					<td class="texto_tabla">$<?php echo number_format($Recaudar)?></td>
				</tr>
			</table>
		</td>
		<td>
			<table border="0" width="100%" align="center" style="margin-bottom: 5px;background: #FFF; border: 1px solid #CCC;">
				<tr colspan="">
					<td class="titulo_tabla" colspan=2>CIERRE</td>
				</tr>
				<tr>
					<td class="subtitulo_tabla" WIDTH="50%">HORA</td>
					<td class="texto_tabla" WIDTH="50%"><?php echo $logout?></td>
				</tr>
				<tr>	
					<td class="subtitulo_tabla">ULTIMO RECAUDO</td>
					<td class="texto_tabla"><?php echo $hora_ultimo_recaudo?></td>
				</tr>
				<tr>	
					<td class="subtitulo_tabla">ULTIMO VENTA</td>
					<td class="texto_tabla"><?php echo $hora_ultimo_venta?></td>
				</tr>
				<tr>	
					<td class="subtitulo_tabla">CARTERA</td>
					<td class="texto_tabla">$<?php echo number_format($SaldoFinal2)?></td>
				</tr>
				<tr>
					<td class="subtitulo_tabla">CLIENTES</td>
					<td class="texto_tabla"><?php echo $nro_clientes_final?></td>
				</tr>
				<tr>	
					<td class="subtitulo_tabla">GASTOS</td>
					<td class="texto_tabla">$ <?php echo number_format($gasto_total)?></td>
				</tr>
				<tr>	
					<td class="subtitulo_tabla">VALOR VENTAS</td>
					<td class="texto_tabla">$ <?php echo number_format($ventas_total)?></td>
				</tr>
				<tr>
					<td class="subtitulo_tabla">VALOR RECAUDADO</td>
					<td class="texto_tabla">$<?php echo number_format($valor_recaudado)?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="titulo_tabla" colspan="2">EFECTIVO A ENTREGAR: $ <?php echo number_format($valor_efectivo_entregar)?></td>
	</tr>
</table>