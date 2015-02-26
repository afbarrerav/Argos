<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Octubre de 2012
 *
 * */
if($accion == "consultar_gastos_socio" || $accion == "consultar_gastos_socio_jva" || $accion == "consultar_gastos_socio_jva_vendedor")
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
				<th class="titulo_tabla" colspan="4">Detallado Gastos</th>
			</tr>
	
			<tr>
				<th class="titulo_tabla" width="40%">JVA</th>
				<th class="titulo_tabla" width="40%">Ruta</th>
				<th class="titulo_tabla" width="10%">Cantidad de Gastos</th>
				<th class="titulo_tabla" width="10%">Total Gastos</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$TotalGastosJVA = 0;
		for($i=0;$i<$RowCount;$i++)
		{
			$TotalGastosJVA = $TotalGastosJVA + $detalle['TotalGastos'][$i];
			?>
			<tr class="fila" style="text-decoration:none; cursor:pointer;" onclick="AjaxConsulta('../logica/rpt_gastos.php', {VEN_CODIGO:'<?php echo $detalle['ven_codigo'][$i]?>',  RUTA:'<?php echo $detalle['nombre'][$i]?>', ACCION:'reportes_detalle_gastos_ruta'}, 'detalle_gastos_ruta_Div');">
				<td class="texto_tabla"><?php echo $detalle['jva_nombre'][$i]?></td>
				<td class="texto_tabla"><?php echo $detalle['nombre'][$i]?></td>
				<td class="texto_tabla"><?php echo $detalle['Gastos'][$i]?></td>
				<td class="texto_tabla" style="text-align: right;">$<?php echo number_format($detalle['TotalGastos'][$i]);?></td>
			</tr>
			<?php
		}
		?>
		</tbody>
		<tr>
			<td class="titulo_tabla" colspan="3" style="text-align:right">Total:</td>
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
if($accion=="consultar_gastos_socio_vendedor" || $accion == "reportes_detalle_gastos_ruta" || $accion=="consultar_gastos_socio_fechaInicio_fechaFin" || $accion=="consultar_gastos_socio_jva_fechaInicio_fechaFin" ||$accion == "consultar_gastos_socio_jva_vendedor_fechaInicio_fechaFin" || $accion=="consultar_gastos_socio_consolidado" || $accion=="consultar_gastos_socio_jva_consolidado" || $accion == "consultar_gastos_socio_jva_vendedor_consolidado" || $accion == "consultar_gastos_socio_jva_fechaInicio_fechaFin_consolidado" || $accion=="consultar_gastos_socio_fechaInicio_fechaFin_consolidado" || $accion == "consultar_gastos_socio_jva_vendedor_fechaInicio_fechaFin_consolidado")
{
	?>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#rpt_reporte_gastos').tablesorter();
	});
	</script>
	<div id="DetalladoClienteDiv"></div>
	<table id="rpt_reporte_gastos" border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height:75%;">
		<thead>
			<tr>
				<th class="titulo_tabla" width="100%" colspan="8">Detalle Gastos</th>
			</tr>
			<tr>
				<th class="titulo_tabla" width="5%">Codigo</th>
				<th class="titulo_tabla" width="20%">Fecha Gasto</th>
				<th class="titulo_tabla" width="20%">Fecha Transacc&oacute;n</th>
				<th class="titulo_tabla" width="30%">Vendedor</th>
				<th class="titulo_tabla" width="25%">Valor</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if ($RowCount>0)
			{
				$Total = 0;
				for ($i=0;$i<$RowCount;$i++)
				{
			?>
			<tr class="fila">
				<td class="texto_tabla"><?php echo $detalle['codigo'][$i]?></td>
				<td class="texto_tabla"><?php echo date('Y-m-d', strtotime($detalle['fecha_gasto'][$i]))?></td>
				<td class="texto_tabla"><?php echo date('Y-m-d', strtotime($detalle['fecha_trans'][$i]))?></td>
				<td class="texto_tabla"><?php echo $detalle['ven_nombre'][$i]?></td>
				<td class="texto_tabla" style="text-align: right;">$<?php echo number_format($detalle['valor'][$i],2);?></td>
			</tr>
			<?php
				$Total = $Total + $detalle['valor'][$i];
				}
			?>
		</tbody>
		<tr>
			<td class="titulo_tabla" colspan="4" style="text-align:right;">Total:</td>
			<td class="titulo_tabla" style="text-align: right;">$<?php echo number_format($Total,2);?></td>
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