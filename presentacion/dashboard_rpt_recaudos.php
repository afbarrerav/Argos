<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:3.0.0
 * @fecha:	Enero de 2013
 * */
?>
<table border="0" width="99%" align="center"
	style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px;">
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
		$total_rutas = $total_rutas + $resultado[$i]['total_rutas'];
		$total_clientes = $total_clientes + $resultado[$i]['total_clientes'];
		$total_saldo = $total_saldo + $resultado[$i]['valor_saldo'];
		?>
	<tr>
		<td class="texto_tabla" align="right">
		<div id="admin_jvaDivSaldos<?php echo $resultado[$i]['jva_codigo']?>"><script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'admin_jva', CAMPO:'nombre', CAMPO_CONDICION:'codigo', CONDICION:'<?php echo $resultado[$i]['jva_codigo']?>', DIV:'admin_jvaDivSaldos<?php echo $resultado[$i]['jva_codigo']?>', ACCION:'consultar_valor_campo'}, 'admin_jvaDivSaldos<?php echo $resultado[$i]['jva_codigo']?>');</script></div>
		</td>
		<td class="numero_tabla"><?php echo $resultado[$i]['total_rutas'] ?></td>
		<td class="numero_tabla"><?php echo $resultado[$i]['total_clientes'] ?></td>
		<td class="numero_tabla">$ <?php echo number_format($resultado[$i]['valor_saldo']) ?></td>
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
