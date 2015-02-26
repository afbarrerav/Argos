<script type="text/javascript">
	$(function() {
		$("#cli_codigo").autocomplete({
			source: "../logica/buscar_clientes.php",
			minLength: 2,
			
			select: function( event, ui ) {
				if(confirm("Desea agregar este cliente a la venta actual?"))
				{
					
				}
			}
		});
	});
</script>
<table width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr height="30">
		<td class="titulo_tabla">TRASLADO DE MERCANCIAS</td>
	</tr>
	<tr>
		<td>
			<table width="100%" align="center" style="background: #FFF; border: 0px solid #CCC;">
				<tr height="25">
					<td width="60%" valign = "top">
						<table width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
							<tr>
								<td><div id="categorias_productos"></div></td>
							</tr>
							<tr height="25">
								<td colspan="7" align="left" class="texto_tabla">
									Buscar Producto: <input id="txt_buscar_productos" type="text" onKeyUp="AjaxConsulta( '../logica/presentar_productos_jva.php', {VALOR_BUSQUEDA:$('#txt_buscar_productos').val(), ACCION:'buscar_productos'}, 'resultado_busqueda_productos' );" size="60"/>
								</td>
							</tr>
							<tr>
								<td><hr></td>
							</tr>
							<tr>
								<td><div id="resultado_busqueda_productos"></div></td>
							</tr>
						</table>	
					</td>
					<td width="1%">&nbsp;</td>
					<td width="39%" valign = "top">
						<div id="detalle_traslado_productos">
								
						</div>
					</td>
				</tr>				
			</table>
		</td>
	</tr>
</table>
<br/>
<script>
	AjaxConsulta('../logica/presentar_categorias_jva.php', {ACCION:'listar'}, 'categorias_productos');
	AjaxConsulta('../logica/presentar_productos_jva.php', {ACCION:'listar'}, 'resultado_busqueda_productos');
	AjaxConsulta('../logica/presentar_traslado_bodega_detalle.php', {BOD_CODIGO:'<?php echo $bod_codigo?>', ACCION:'listar'}, 'detalle_traslado_productos');
</script>