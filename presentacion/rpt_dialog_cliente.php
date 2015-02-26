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
	$('#Detalle_Cliente').dialog({width:700, height:500, modal:true, title:'Detalle Cliente', close:function(ev, ui){$(this).remove();}});
});
</script>
<div id="Detalle_Cliente">
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px;">
		<tr>
			<td class="titulo_tabla" colspan="12">Detallado cliente</td>
		</tr>
		<tr>
			<td class="titulo_tabla" width="10%">c.c cliente</td>
			<td class="titulo_tabla" width="7%">codigo</td>
			<td class="titulo_tabla" colspan="3" width="20%">Nombres y apellidos</td>
			<td class="titulo_tabla" width="10%">Telefonos</td>
			<td class="titulo_tabla" width="10%">Celulares</td>
			<td class="titulo_tabla" colspan="2" width="10%">Direccion</td>
		</tr>
		<tr>
			<td class="texto_tabla">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'admin_clientes', CAMPO:"nroidentificacion", CAMPO_CONDICION:'codigo', CONDICION:'<?php echo $cli_codigo?>', ACCION:'consultar_valor_campo2'}, 'Div_ccCliente<?php echo $cli_codigo?>');</script>
    			<div id="Div_ccCliente<?php echo $cli_codigo?>" style="width: 160px;"></div>
			</td>
			<td class="texto_tabla">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'admin_clientes', CAMPO:"referencia", CAMPO_CONDICION:'codigo', CONDICION:'<?php echo $cli_codigo?>', ACCION:'consultar_valor_campo2'}, 'codArgos<?php echo $cli_codigo?>');</script>
				<div id="codArgos<?php echo $cli_codigo?>" style="width: 160px;"></div>	
			</td>
			<td class="texto_tabla" colspan="3">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'admin_clientes', CAMPO:"CONCAT(nombre1_contacto, ' ', apellido1_contacto)", CAMPO_CONDICION:'codigo', CONDICION:'<?php echo $cli_codigo?>', ACCION:'consultar_valor_campo2'}, 'Nombres_Cliente<?php echo $cli_codigo?>');</script>
				<div id="Nombres_Cliente<?php echo $cli_codigo?>" style="width: 160px;"></div>	
			</td>
			<td class="texto_tabla">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'admin_clientes', CAMPO:"telefono1", CAMPO_CONDICION:'codigo', CONDICION:'<?php echo $cli_codigo?>', ACCION:'consultar_valor_campo2'}, 'Telefono_Cliente<?php echo $cli_codigo?>');</script>
				<div id="Telefono_Cliente<?php echo $cli_codigo?>"></div>
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'admin_clientes', CAMPO:"telefono2", CAMPO_CONDICION:'codigo', CONDICION:'<?php echo $cli_codigo?>', ACCION:'consultar_valor_campo2'}, 'Telefono_Cliente2<?php echo $cli_codigo?>');</script>
				<div id="Telefono_Cliente2<?php echo $cli_codigo?>"></div>
			</td>
			<td class="texto_tabla">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'admin_clientes', CAMPO:"celular1", CAMPO_CONDICION:'codigo', CONDICION:'<?php echo $cli_codigo?>', ACCION:'consultar_valor_campo2'}, 'Celular_Cliente<?php echo $cli_codigo?>');</script>
				<div id="Celular_Cliente<?php echo $cli_codigo?>"></div>
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'admin_clientes', CAMPO:"celular2", CAMPO_CONDICION:'codigo', CONDICION:'<?php echo $cli_codigo?>', ACCION:'consultar_valor_campo2'}, 'Celular_Cliente2<?php echo $cli_codigo?>');</script>
				<div id="Celular_Cliente2<?php echo $cli_codigo?>"></div>
			</td>
			<td class="texto_tabla" colspan="2">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'admin_clientes', CAMPO:"direccion", CAMPO_CONDICION:'codigo', CONDICION:'<?php echo $cli_codigo?>', ACCION:'consultar_valor_campo2'}, 'Direccion_Cliente<?php echo $cli_codigo?>');</script>
				<div id="Direccion_Cliente<?php echo $cli_codigo?>"></div>
			</td>
		</tr>
		<tr>
			<td class="titulo_tabla">ID venta</td>
			<td class="titulo_tabla">Vendedor</td>
			<td class="titulo_tabla">Valor prestado</td>
			<td class="titulo_tabla">Valor total</td>
			<td class="titulo_tabla">Fecha Entrega</td>
			<td class="titulo_tabla">nro pendientes</td>
			<td class="titulo_tabla">nro canceladas</td>
			<td class="titulo_tabla">Saldo</td>
		</tr>
		<tr>
			<td class="texto_tabla">
				<?php echo $tv_codigo?>
			</td>
			<td class="texto_tabla">
				<?php echo $vendedor?>	
			</td>
			<td class="texto_tabla" style="text-align: right">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'trans_ventas', CAMPO:"CONCAT('$', valor_producto)", CAMPO_CONDICION:'codigo', CONDICION:'<?php echo $tv_codigo?>', ACCION:'consultar_valor_campo2'}, 'Valor_prestado<?php echo $cli_codigo?>');</script>
				<div id="Valor_prestado<?php echo $cli_codigo?>"></div>
			</td>
			<td class="texto_tabla" style="text-align: right">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'trans_ventas', CAMPO:"CONCAT('$', valor_total)", CAMPO_CONDICION:'codigo', CONDICION:'<?php echo $tv_codigo?>', ACCION:'consultar_valor_campo2'}, 'Valor_total<?php echo $cli_codigo?>');</script>
				<div id="Valor_total<?php echo $cli_codigo?>"></div>
			</td>
			<td class="texto_tabla">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'trans_ventas', CAMPO:"max(fecha_entrega)", CAMPO_CONDICION:'cli_codigo', CONDICION:'<?php echo $cli_codigo?>', ACCION:'consultar_valor_campo2'}, 'Fecha_entrega<?php echo $cli_codigo?>');</script>
				<div id="Fecha_entrega<?php echo $cli_codigo?>"></div>
			</td>
			<td class="texto_tabla" style="text-align: center;">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {FUNCION:'fun_nrocuotas_pendientes_recaudo', PARAMETROS:'<?php echo $tv_codigo?>', ACCION:'consultar_valor_funcion'}, 'cuotas_pendientes<?php echo $cli_codigo?>');</script>
				<div id="cuotas_pendientes<?php echo $cli_codigo?>"></div>
			</td>
			<td class="texto_tabla" style="text-align: center;">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {FUNCION:'fun_nrocuotas_recaudadas', PARAMETROS:'<?php echo $tv_codigo?>', ACCION:'consultar_valor_funcion'}, 'cuotas_canceladas<?php echo $cli_codigo?>');</script>
				<div id="cuotas_canceladas<?php echo $cli_codigo?>"></div>
			</td>
			<td class="texto_tabla" style="text-align: right">
				<script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'trans_rutas_detalles', CAMPO:"CONCAT('$', saldo)", CAMPO_CONDICION:'tv_codigo', CONDICION:'<?php echo $tv_codigo?>', ACCION:'consultar_valor_campo2'}, 'Saldo<?php echo $cli_codigo?>');</script>
				<div id="Saldo<?php echo $cli_codigo?>"></div>
			</td>
		</tr>
</table>
<table id="detalle_saldos_recaudos" border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px;">
	<thead>	
			<tr>
				<td class="titulo_tabla" colspan="5">
					Detallado Recaudo
				</td>
			</tr>
		
			<tr>
				<th class="titulo_tabla" width="10%">Cuota nro</th>
				<th class="titulo_tabla" width="20%">Fecha Pago</th>
				<th class="titulo_tabla" width="15%">Valor pago</th>
				<th class="titulo_tabla" width="20%">Fecha recaudo</th>
				<th class="titulo_tabla" width="15%">Valor recaudo</th>
			</tr>
	</thead>
	<tbody>
		<?php
		$total_valor_pago = 0;
		$total_valor_recaudado = 0;
		for($i=0;$i<$RowCount;$i++)
		{
			$total_valor_pago = $total_valor_pago + $detalle['valor_pago'][$i];
			$total_valor_recaudado = $total_valor_recaudado + $detalle['valor_recaudo'][$i];
			?>
			<tr class="fila">
				<td class="texto_tabla"><?php echo $detalle['cuota_nro'][$i]?></td>
				<td class="texto_tabla"><?php echo $detalle['fecha_pago'][$i]?></td>
				<td class="texto_tabla">$<?php echo number_format($detalle['valor_pago'][$i])?></td>
				<td class="texto_tabla"><?php echo $detalle['fecha_recaudo'][$i]?></td>
				<td class="texto_tabla">$<?php echo number_format($detalle['valor_recaudo'][$i])?></td>
			</tr>
			<?php
		} 
		?>
	</tbody>
	<tr>
		<td class="titulo_tabla" colspan="3">Total valor a pagar: $<?php echo number_format($total_valor_pago)?></td>
		<td class="titulo_tabla" colspan="2">Total valor recaudado: $<?php echo number_format($total_valor_recaudado)?></td>
	</tr>
	<tr>
		<td class="titulo_tabla" colspan="5">Cantidad de registros: <?php echo $RowCount?></td>
	</tr>
</table>
</div>
