<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Junio de 2011
 * 
 * */
session_start();
$language = $_SESSION['LG'];
include('../logica/diccionario.php');
?> 
<script type="text/javascript" src="../js/validar_usuario.js"></script>
<table width="90%" align="center">
	<tr>
		<td align="center"> 
		<fieldset><legend><?php echo $diccionario[1][$language]?></legend><?php echo $diccionario[2][$language]?><br><br><?php echo $diccionario[3][$language]?><br>
		</fieldset>
		</td>
	</tr>
</table>
<br>
<table width="90%" align="center">
	<tr>
		<td>
		<fieldset><legend><?php echo $diccionario[4][$language]?></legend>
		<table  align="center">
			<tr>
				<td align="right"><?php echo $diccionario[5][$language]?></td>  
				<td align="left"><input type="text" id="txt_login" value="" onKeyUP="EnterLogin();" size="20"></td>
			</tr>
			<tr>
				<td align="right"><?php echo $diccionario[6][$language]?></td>
				<td align="left"><input type="password" id="txt_pass" value="" onKeyUP="EnterPass();" size="20"></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="button" name="bt1" value='<?php echo $diccionario[7][$language]?>'
					class="boton" title="Haga clic para ingresar al administrador del sistema"
					onclick='validausuario();'></td>
			</tr>
		</table>
		</fieldset>	
	</tr>
</table>
<br>
