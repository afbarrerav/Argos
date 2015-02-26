<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Diciembre de 2012
 *
 * */

if($accion == "mostrar_front_saldos")
{
	?>
	<table border="0" width="99%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px;">
		<tr>
			<td class="titulo_tabla">JVA</td>
			<td class="titulo_tabla">Rutas</td>
			<td class="titulo_tabla">Clientes</td>
			<td class="titulo_tabla">Saldo</td>
		</tr>
		<?php 
		$total_rutas = 0;
		$total_clientes = 0;
		$total_saldo = 0;
		for($i=0;$i<$RowCount;$i++)
		{
			$total_rutas = $total_rutas + $resultado[$i]['nro_rutas'];
			$total_clientes = $total_clientes + $resultado[$i]['nro_clientes'];
			$total_saldo = $total_saldo + $resultado[$i]['saldo_rutas'];
			?>
		<tr>
			<td class="texto_tabla" align="right"><?php echo $resultado[$i]['jva_nombre']?></td>
			<td class="numero_tabla" ><?php echo $resultado[$i]['nro_rutas'] ?></td>
			<td class="numero_tabla" ><?php echo $resultado[$i]['nro_clientes'] ?></td>
			<td class="numero_tabla" >$ <?php echo number_format($resultado[$i]['saldo_rutas']) ?></td>
		</tr>
		<?php 
		}
		?>
		<tr>
			<td class="subtitulo_tabla">TOTAL:</td>
			<td class="subtitulo_tabla"><?php echo $total_rutas?></td>
			<td class="subtitulo_tabla"><?php echo $total_clientes?></td>
			<td class="subtitulo_tabla">$ <?php echo number_format($total_saldo) ?></td>
		</tr>
	</table>
	<?php
}
else
{
?>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height: 75%;">
	<thead>	
		<tr>
			<th class="titulo_tabla" colspan="4">Saldos de ruta</th>
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
			$sum_clientes = $sum_clientes + $detalle_saldos['cant_clientes'][$i];
			$sum_valor	  = $sum_valor + $detalle_saldos['valor_cartera'][$i];
			?>
			<tr class="fila" title="Haga clic para conocer el saldo de la ruta" style="text-decoration:none; cursor:pointer;" onclick="AjaxConsulta('../logica/rpt_saldos.php', {TRJ_CODIGO:'<?php echo $detalle_saldos['trj_codigo'][$i]?>', VENDEDOR:'<?php echo $detalle_saldos['nombres'][$i]." ".$detalle_saldos['apellidos'][$i]?>', RUTA:'<?php echo $detalle_saldos['descripcion'][$i]?>', VALOR_TOTAL:'<?php echo $detalle_saldos['valor_cartera'][$i]?>', ACCION:'detalle_saldos_ruta'}, 'DivSaldosRuta');">
				<td class="texto_tabla"><?php echo $detalle_saldos['descripcion'][$i]?></td>
				<td class="texto_tabla"><?php echo $detalle_saldos['nombres'][$i]." ".$detalle_saldos['apellidos'][$i]?></td>
				<td class="texto_tabla"><?php echo $detalle_saldos['cant_clientes'][$i]?></td>
				<td class="texto_tabla" style="text-align: right;">$<?php echo number_format($detalle_saldos['valor_cartera'][$i])?></td>
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
?>