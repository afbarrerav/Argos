<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Junio de 2011
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
<br>
<table border="0" width="100%" align="center"
	style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2" class="titulo_tabla">Informaci&oacute;n Personal</td>
	</tr>
	<tr>
		<td colspan="2" style="background: #FFF; border: 1px solid #CCC;"><img
			src="imagenes/iconos/edit_a.png" alt="editar" title="Editar"
			style="cursor: pointer; border: 1px solid #CCC;"
			onmouseover="this.src = 'imagenes/iconos/edit.png'"
			onmouseout="this.src = 'imagenes/iconos/edit_a.png'"
			onclick="AjaxConsulta( '../logica/datos_basicos.php', {ACCION:'editar'}, 'area_trabajo' );" /></td>
	</tr>
	<tr>
		<td width="25%" class="subtitulo_tabla">Nombres:</td>
		<td width="25%" class="texto_tabla"><?php echo $nom_usuario;?></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Apellidos:</td>
		<td class="texto_tabla"><?php echo $ape_usuario;?></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">e-mail:</td>
		<td class="texto_tabla"><?php echo $mail;?></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Direci&oacute;n:</td>
		<td class="texto_tabla"><?php echo $direccion;?></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Telefono:</td>
		<td class="texto_tabla"><?php echo $telefono;?></td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Usuario:</td>
		<td class="texto_tabla"><?php echo $usuario;?></td>
	</tr>	
</table>
<br>

