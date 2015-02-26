<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
?>
<div id="DetalladoClienteDiv"></div>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height:75%;">
	<tr>
		<td class="titulo_tabla" width="100%" colspan="10">Detalle ventas ruta <?php echo $ruta?> - <?php echo $fecha?></td>
	</tr>
	<tr>
		<td class="titulo_tabla" width="7%">Id. producto</td>
		<td class="titulo_tabla" width="10%">Vendedor</td>
		<td class="titulo_tabla" width="10%">c.c cliente</td>
		<td class="titulo_tabla" width="7%">codigo</td>
		<td class="titulo_tabla" width="20%">Nombre y apellido</td>
		<td class="titulo_tabla" width="10%">Fecha solicitud</td>
		<td class="titulo_tabla" width="10%">Fecha entrega</td>
		<td class="titulo_tabla" width="12%">Valor Producto</td>
		<td class="titulo_tabla" width="5%">Comisi&oacute;n</td>
		<td class="titulo_tabla" width="7%">Total</td>
	</tr>
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
					<td class="texto_tabla" style="text-align: right">$<?php echo number_format($detalle['valor_producto'][$i]);?></td>
					<td class="texto_tabla" style="text-align: right">$<?php echo number_format($detalle['valor_comision_servicio'][$i]);?></td>
					<td class="texto_tabla" style="text-align: right">$<?php echo number_format($detalle['valor_total'][$i]);?></td>
				</tr>
				<?php
				$Total = $Total + $detalle['valor_producto'][$i];
				$valor_total = $valor_total + $detalle['valor_total'][$i];
			}
			?>
			<tr>
				<td class="titulo_tabla" colspan="7" style="text-align:right;">Total:</td>
				<td class="titulo_tabla" style="text-align: right">$<?php echo number_format($Total);?></td>
				<td class="titulo_tabla" style="text-align:right;"></td>
				<td class="titulo_tabla" style="text-align: right">$<?php echo number_format($valor_total);?></td>
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