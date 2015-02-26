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
?>
<script type='text/javascript' src='../js/admin_clientes.js'></script>
<br>
<form id="form">
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2" class="titulo_tabla">INFORMACI&Oacute;N DEL CLIENTE</td>
	</tr>
	<tr>
		<td colspan="2" style="background: #FFF; border: 1px solid #CCC;"><img
			src="imagenes/iconos/save_a.png" alt="guardar" title="Guardar"
			class="img_boton" onmouseover="this.src = 'imagenes/iconos/save.png'"
			onmouseout="this.src = 'imagenes/iconos/save_a.png'"
			onclick="editar(this);"/>&nbsp;<img
			src="imagenes/iconos/cancel_a.png" alt="cancelar" title="Cancelar"
			class="img_boton"
			onmouseover="this.src = 'imagenes/iconos/cancel.png'"
			onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
			onclick="AjaxConsulta( '../logica/admin_clientes.php', {ACCION:'listar'}, 'listar_clientes_Div');" /></td>
	</tr>
	<tr>
		<td width="25%" class="subtitulo_tabla"><input type="hidden" id="codigo" value="<?php echo $codigo?>" />Tipo de identificaci&oacute;n:</td>
		<td width="25%" class="texto_tabla"><script>AjaxConsulta('../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_identificaciones', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'<?php echo $ti_codigo?>', CODIGO_REGISTRO:'<?php echo $ti_codigo?>',  DIV:'tipoIdentificacionDiv', NOMBRE_SELECT:'stipoIdentificacion', ESTADO:'', ACCION:'consultar_campo'}, 'tipoIdentificacionDiv' );</script><div id="tipoIdentificacionDiv"></div></td>	
	</tr>
	<tr>
		<td class="subtitulo_tabla">N&uacute;mero de identificaci&oacute;n:</td>
		<td class="texto_tabla"><input type="text" id="nroidentificacion" class="campo" size="15" maxlength="12" value="<?php echo $nroidentificacion?>"/>*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Referencia:</td>
		<td class="texto_tabla"><input type="text" id="referencia" class="campo" size="50" maxlength="100" value="<?php echo $referencia?>"/></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Raz&oacute; Social:</td>
		<td class="texto_tabla"><input type="text" id="razon_social" class="campo" size="50" maxlength="100" value="<?php echo $razon_social?>"/></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Tipo Negocio:</td>
		<td class="texto_tabla"><script>AjaxConsulta('../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_negocios', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'<?php echo $tn_codigo?>', CODIGO_REGISTRO:'<?php echo $tn_codigo?>',  DIV:'tipoNegocioDiv', NOMBRE_SELECT:'stipoNegocio', ESTADO:'', ACCION:'consultar_campo'}, 'tipoNegocioDiv');</script><div id="tipoNegocioDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Primer nombre:</td>
		<td class="texto_tabla"><input type="text" id="primer_nombre" class="campo" size="20" value="<?php echo $nombre1_contacto?>"/>*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Segundo nombre:</td>
		<td class="texto_tabla"><input type="text" id="segundo_nombre" class="campo" size="20" value="<?php echo $nombre2_contacto?>"/></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Primer apellido:</td>
		<td class="texto_tabla"><input type="text" id="primer_apellido" class="campo" size="20" value="<?php echo $apellido1_contacto?>"/>*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Segundo apellido:</td>
		<td class="texto_tabla"><input type="text" id="segundo_apellido" class="campo" size="20" value="<?php echo $apellido2_contacto?>"/></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Telefono 1:</td>
		<td class="texto_tabla"><input type="text" id="telefono1" class="campo" size="15" maxlength="7" value="<?php echo $telefono1?>"/>* Ext. <input type="text" id="ext1" class="campo" size="7" maxlength="7" value="<?php echo $ext1?>"/></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Telefono 2:</td>
		<td class="texto_tabla"><input type="text" id="telefono2" class="campo" size="15" maxlength="7" value="<?php echo $telefono2?>"/> Ext. <input type="text" id="ext2" class="campo" size="7" maxlength="7" value="<?php echo $ext2?>"/></td>
	</tr>

	<tr>
		<td class="subtitulo_tabla">Celular 1:</td>
		<td class="texto_tabla"><input type="text" id="celular1" class="campo" size="15" maxlength="10" value="<?php echo $celular1?>"/></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Celular 2:</td>
		<td class="texto_tabla"><input type="text" id="celular2" class="campo" size="15" maxlength="10" value="<?php echo $celular2?>"/></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">E - mail:</td>
		<td class="texto_tabla"><input type="text" id="email" class = "campo" size="50"  maxlength="50" value="<?php echo $email?>"/></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Barrio:</td>
		<td class="texto_tabla"><input type="text" id="barrio" class = "campo" size="50"  maxlength="50" value="<?php echo $barrio?>"/></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Direcci&oacute;n:</td>
		<td class="texto_tabla"><input type="text" id="direccion" class = "campo" size="50"  maxlength="50" value="<?php echo $direccion?>"/>*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Ciudad:</td>
		<td class="texto_tabla"><script>AjaxConsulta('../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_ciudades', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'<?php echo $ciu_codigo?>', CODIGO_REGISTRO:'<?php echo $ciu_codigo?>',  DIV:'tiposCiudadesDiv', NOMBRE_SELECT:'stiposCiudades', ESTADO:'', ACCION:'consultar_campo'}, 'tiposCiudadesDiv' );</script><div id="tiposCiudadesDiv"></div></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Calificaci&oacute;n:</td>
		<td class="texto_tabla"><input type="text" id="calificacion" class = "campo" size="20"  value="<?php echo $comentario?>"/></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Comentario:</td>
		<td class="texto_tabla"><input type="text" id="comentario" class = "campo" size="50"  value="<?php echo $comentario?>"/></td>
	</tr>
	</table>
<br>
</form>
