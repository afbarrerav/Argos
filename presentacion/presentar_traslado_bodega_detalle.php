<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 *
 * */
if($msg !="")
{
	?>
	<script>
		alert('<?php echo $msg;?>');
	</script>
	<?php
}
?>
<table width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr height="25">
		<td width="70%" colspan="2" class="titulo_tabla">INFORMACI&Oacute;N GENERAL</td>
	</tr>
	<tr>
		<td colspan="2" ></td>
	</tr>
	<tr>
		<td width="30%" class="subtitulo_tabla">Vendedor: </td>
		<td width="70%"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'admin_bodegas', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'descripcion', VALOR_REGISTRO:'<?php echo $bod_codigo?>', CODIGO_REGISTRO:'<?php echo $bod_codigo?>',  DIV:'bodDiv<?php echo $bod_codigo?>', NOMBRE_SELECT:'sbodega', ESTADO:'disabled', ACCION:'consultar_campo'}, 'bodDiv<?php echo $bod_codigo?>' );</script><div id="bodDiv<?php echo $bod_codigo?>"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Valor Total:</td>
		<td>$ <?php echo number_format($valor_total_bodega);?></td>
	</tr>
	<tr>
		<td  class="titulo_tabla" colspan="2">DETALLE</td>
	</tr>
	<tr>
		<td colspan="2" class="texto_tabla" >
			<table width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
				<tr height="25">
					<td width="5%" class="titulo_tabla">Codigo</td>
					<td width="50%" class="titulo_tabla">Producto</td>
					<td width="5%" class="titulo_tabla">Cantidad</td>
					<td width="20%" class="titulo_tabla">Val Unit</td>
					<td width="20%" class="titulo_tabla">Val Tot</td>
				</tr>
				<?php
					for($i=0;$i<$RowCountPB;$i++)
					{
						?>
				<tr class="fila">
					<td class="texto_tabla"><?php echo $resultados['codigo'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['pro_nombre'][$i]?></td>
					<td class="texto_tabla"><input type = "text" id="txtCantidad<?php echo $resultados['pro_codigo'][$i]?>" value = "<?php echo $resultados['pro_cantidad'][$i]?>" size="5" class="campo" onchange="AjaxConsulta('../logica/presentar_traslado_bodega_detalle.php', {BOD_CODIGO:$('#sbodega').val(), PRO_CODIGO:<?php echo $resultados['pro_codigo'][$i]?>, PRO_CANTIDAD: $('#txtCantidad<?php echo $resultados['pro_codigo'][$i]?>').val(), ACCION:'actualizar_cantidad_productos_bodegas'}, 'detalle_traslado_productos');"></td>
					<td class="texto_tabla">$ <?php echo number_format($resultados['pro_valor'][$i]);?></td>
					<td class="texto_tabla">$ <?php echo number_format($resultados['pro_valor_total'][$i]);?></td>
				</tr>
				<?php 
					}
				?>
			</table>
		</td>								
	</tr>
	<tr>
		<td colspan="2" style="background: #FFF; border: 0px solid #CCC;">
			<div id="confirmar_pedido" style="display:none">
					<img src="imagenes/iconos/check_mark_a.png" alt="Confirmar Pedido" title="Confirmar Pedido"
					class="img_boton" onmouseover="this.src = 'imagenes/iconos/check_mark.png'"
					onmouseout="this.src = 'imagenes/iconos/check_mark_a.png'"
					onclick="confirmar_pedido();"/>&nbsp;
					<img src="imagenes/iconos/cancel_a.png" alt="Eliminar Pedido" title="Eliminar Pedido"
					class="img_boton"
					onmouseover="this.src = 'imagenes/iconos/cancel.png'"
					onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
					onclick="cancelar_pedido();"/>
			</div>
		</td>
	</tr>
</table>
