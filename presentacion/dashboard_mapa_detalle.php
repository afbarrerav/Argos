<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarreav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
?>
<script type='text/javascript' src='../js/dashboard_mapa.js'></script>
<script type='text/javascript'>
$(document).ready(function() {
	   $("#fecha_ruta").datepicker();
});
</script>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="7">
			<table border="0" width="100%" align="center" style="background: #FFF; border: 0px solid #CCC;">
				<tr height="30">
					<td class="titulo_tabla">Informaci&oacute;n Importante.</td>
				</tr>
				<tr height="30">
					<td class="texto_tabla">Seleccione el vendedor y la fecha para graficar la ruta de recaudo.</td>
				</tr>
				<tr height="30">
					<td class="titulo_tabla">Generar Mapa de Recaudos</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="subtitulo_tabla">Vendedor:</td>
		<td align="left"><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'admin_jva_usuarios', TABLA_ACTUALIZAR:'0', NOMBRE_CAMPO:'nombre', VALOR_REGISTRO:'0', CODIGO_REGISTRO:'0',  DIV:'Vendedores_JVA', NOMBRE_SELECT:'sVendedores', ESTADO:'', ACCION:'consultar_campo'}, 'Vendedores_JVA');</script><div id="Vendedores_JVA"></div></td>
		<td class="subtitulo_tabla">Tipo Gestion:</td>
		<td><select id="stipoMapa" class="lista_desplegable">
				<option value="">NO INDICA</option>
				<option value="Recaudos">Recaudos</option>
				<option value="Ventas">Ventas</option>
			</select></td>
		<td class="subtitulo_tabla">Fecha:</td>
		<td align="left"><input type="text" id="fecha_ruta" class="campo"></td>
		<td colspan="2" align="center"><input type="button" name="bt1" value='Generar Mapa'
			class="boton" title="Haga clic para generar el mapa de recaudo"
			onclick="generar_mapa();"></td>
	</tr>
</table>
<br>
<div id="rpt_mapa">
	<iframe src="http://50.63.181.150:8813/baco/logica/dashboard_mapa_socio.php" width="100%" height="391" marginheight="0" marginwidth="0" scrolling="No" frameborder="0">Si ves este mensaje, significa que tu navegador no tiene soporte para mapas en googlemaps actualiza tu navegador!.</iframe>
</div>