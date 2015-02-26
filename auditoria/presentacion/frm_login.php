<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Junio de 2011
 * 
 * */
?>
<script type="text/javascript" src="../js/validar_usuario.js"></script>
<table width="90%" align="center">
	<tr>
		<td align="center">
		<fieldset><legend>Instrucciones</legend>Bienvenido a Bilden<br><br>Por favor digite su usuario y clave para ingresar a la aplicaci&oacute;n.<br>
		</fieldset>
		</td>
	</tr>
</table>
<br>
<table width="90%" align="center">
	<tr>
		<td>
		<fieldset><legend>Autenticaci&oacute;n de usuarios</legend>
		<table  align="center">
			<tr>
				<td align="right">Usuario:</td>  
				<td align="left"><input type="text" id="txt_login" value="" onKeyUP="EnterLogin();" size="20"></td>
			</tr>
			<tr>
				<td align="right">Clave:</td>
				<td align="left"><input type="password" id="txt_pass" value="" onKeyUP="EnterPass();" size="20"></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="button" name="bt1" value='Entrar'
					class="boton" title="Haga clic para ingresar al administrador del sistema"
					onclick='validausuario();'></td>
			</tr>
		</table>
		</fieldset>	
	</tr>
</table>
<br>
