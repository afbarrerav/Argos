<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
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
$fecha_gasto = date('Y-m-d');
?>
<script type='text/javascript' src='../js/admin_rutas.js'></script>
<form id="form">
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2" class="titulo_tabla">PARAMETRIZAR RUTA - <?php echo $nombre?></td>
	</tr>
	<tr>
		<td colspan="2" style="background: #FFF; border: 1px solid #CCC;"><img
			src="imagenes/iconos/save_a.png" alt="guardar" title="Guardar"
			class="img_boton" onmouseover="this.src = 'imagenes/iconos/save.png'"
			onmouseout="this.src = 'imagenes/iconos/save_a.png'"
			onclick="editar_ruta(this);"/></td>
	</tr>
	<tr>
		<td width="25%" class="subtitulo_tabla">nombre:</td>
		<td width="25%" class="texto_tabla"><input type="hidden" id="codigo_ruta" value="<?php echo $codigo?>"><input type="text" id="nombre" class="campo" size="30" value="<?php echo $nombre?>"/>*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">JVA:</td>
		<td class="texto_tabla"><script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'admin_jva', CAMPO:"nombre", CAMPO_CONDICION:'codigo', CONDICION:'<?php echo $jva_codigo?>', ACCION:'consultar_valor_campo2'}, 'jvaDiv');</script><div id="jvaDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Vendedor:</td>
		<td class="texto_tabla"><script>AjaxConsulta( '../logica/admin_rutas.php', {TABLA_CONSULTAR:'admin_jva_usuarios', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'<?php echo $aju_codigo?>', CODIGO_REGISTRO:'0',  DIV:'Vendedores_JVA', NOMBRE_SELECT:'sVendedores', CONDICION:'<?php echo $jva_codigo?>', ESTADO:'', ACCION:'consultar_vendedor_jva'}, 'Vendedores_JVA');</script><div id="Vendedores_JVA"></div></td>
	</tr>
	</table>
<br>
</form>