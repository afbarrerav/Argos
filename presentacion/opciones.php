<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 *
 * */
include_once ("../logica/variables_session.php");
?>
<table width="100%" class="tablaOpciones">
	<tr>
		<td width="62%" align="left" style="padding: 0 10px">Bienvenido: <a href="#" style="cursor:pointer; color:#FFFFFF;" onclick="AjaxConsulta( '../logica/datos_basicos.php', {ACCION:'listar'}, 'area_trabajo' );"><?php echo $nom_usuario?></a></td>
		<td width="24%" align="right" title="ULTIMO INGRESO"><?php echo $ultimo_acceso?></td>
		<td width="2%" align="right" title="ULTIMO INGRESO">&nbsp;|&nbsp;</td>
		<td width="12%" align="right" title="ROL"><?php echo $rol?></td>
	</tr>
	<tr height="64px">
		<td colspan="4">
			<div id="menuAplicacion">
				<?php include('menu_aplicacion.php');?>
			</div>
		</td>
	</tr>
</table>
