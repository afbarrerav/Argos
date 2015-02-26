<?php
if($msg !="")
{
	?>
<script>
		alert('<?php echo $msg;?>');
	</script>
	<?php
}
?>
<script type='text/javascript' src='../js/datos_basicos.js'></script>
<br>
<form id="form">
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2" class="titulo_tabla">Informaci&oacute;n Personal</td>
	</tr>
	<tr>
		<td colspan="2" style="background: #FFF; border: 1px solid #CCC;"><img
			src="imagenes/iconos/save_a.png" alt="guardar" title="Guardar"
			class="img_boton" onmouseover="this.src = 'imagenes/iconos/save.png'"
			onmouseout="this.src = 'imagenes/iconos/save_a.png'"
			onclick="guardar(this)" />&nbsp;<img
			src="imagenes/iconos/cancel_a.png" alt="cancelar" title="Cancelar"
			class="img_boton"
			onmouseover="this.src = 'imagenes/iconos/cancel.png'"
			onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
			onclick="AjaxConsulta( '../logica/datos_basicos.php', {ACCION:'listar'}, 'area_trabajo' );" /></td>
	</tr>
	<tr>
		<td width="25%" class="subtitulo_tabla">Nombres:</td>
		<td width="25%" class="texto_tabla"><input type="text" id="nom_usuario" name="nom_usuario" class="campo" size="45" value="<?php echo $nom_usuario;?>" />*</td>	</tr>
	<tr>
		<td class="subtitulo_tabla">Apellidos:</td>
		<td class="texto_tabla"><input type="text" id="ape1_usuario" name="ape1_usuario" class="campo" size="45" value="<?php echo $ape_usuario;?>" />*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">e-mail:</td>
		<td class="texto_tabla"><input type="text" id="mail" name="mail" class="campo" size="45" value="<?php echo $mail;?>" />*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Direcci&oacute;n:</td>
		<td class="texto_tabla"><input type="text" id="direccion" name="direccion" class="campo" size="45" value="<?php echo $direccion;?>" /></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Telefono:</td>
		<td class="texto_tabla"><input type="text" id="telefono" name="telefono" class="campo" value="<?php echo $telefono;?>" /></td>
	</tr>
</table>
<br>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2" class="titulo_tabla">Informaci&oacute;n de la cuenta</td>
	</tr>
	<tr>
		<td width="25%" class="subtitulo_tabla">Nombre de usuario:</td>
		<td width="25%" class="texto_tabla"><?php echo $username?></tr>
	<tr>
		<td class="subtitulo_tabla">Clave:</td>
		<td class="texto_tabla"><input type="password" id="clave_actual" class="campo" size="45" value=""/>*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Nueva Clave:</td>
		<td class="texto_tabla"><input type="password" id="clave_nueva" class="campo" size="45" value="" />*</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Repita nueva clave:</td>
		<td class="texto_tabla"><input type="password" id="clave_nueva_r" class="campo" size="45" value="" />*</td>
	</tr>
</table>
<br>
</form>
