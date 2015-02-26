<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Octubre de 2012
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
<table border = "0" width="100%">
<?php
$j=1;
for($i=0;$i<$RowCount;$i++)
{
	if($j==1)
	{
		echo "<tr>";
	}
?>
	<td width="150px" heigth="100px" valign="top" style="cursor:pointer; font-family: tahoma; font-size: 10px; color: #0071BC; text-align: center; border: 1px solid #CCC;" onclick="AjaxConsulta( '../logica/admin_mercancias.php', {BOD_CODIGO:$('#sbodega').val(), PRO_CODIGO:<?php echo $resultados['codigo'][$i]?>, ACCION:'traslado_productos_bodegas'}, 'detalle_traslado_productos' );">
			<?php echo $resultados['nombre'][$i]?><br>
			<img src="../presentacion/<?php echo $resultados['ruta_img'][$i]?>" alt="<?php echo $resultados['nombre'][$i]?>" title="<?php echo $resultados['descripcion'][$i]?>" width="96px" border="0"/><br>
			<b>$ <?php echo number_format($resultados['valor'][$i]);?></b>
	</td>
	<td></td>	
<?php 
	$j++;
	if($j== 4)
	{
		echo "</tr><tr><td colspan='6'>&nbsp;</td></tr>";
		$j=1;
	}
}
?>
</table>