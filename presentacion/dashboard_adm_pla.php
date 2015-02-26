<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Diciembre de 2012
 *
 * */
?>
<script type='text/javascript' src='../js/admin_tendencia_historica.js'></script>
<div id="reportes_dashboard" widhth="100%" style=" height: 610px;">
	<div id="Saldos">
		<div id="nombreS"><strong>Saldos de ruta</strong></div>
		<div id="infoS">
		</div>
		<div id="masS" onclick="AjaxConsulta( '../logica/rpt_saldos.php', {ACCION:'consultar_saldos'}, 'area_trabajo' );">m&aacute;s</div>
	</div>
	<div id="Cantidad_jva">
		<div id="nombreR"><strong>Recaudos del d&iacute;a</strong></div>
		<div id="infoR">			
		</div>
		<div id="masR" onclick="AjaxConsulta( '../logica/rpt_recaudos.php', {ACCION:'consultar_ruta_recaudos'}, 'area_trabajo' );">m&aacute;s</div>
	</div>
	<div id="Tendencia_Historica" style=" width: 614px; height: 413px; float: left; border-radius: 30px 30px 30px 30px; margin-right: 10px; text-align: center; margin-bottom: 10px; overflow: hidden; border: 2px solid;">
		<div id="nombreTH">
			<table width="100%">
				<tr>
					<td rowspan="2" width="40%"><strong>Tendencia Historica</strong></td>
					<td class="texto_tabla"	width="30%" style="text-align:center">JVA:</td>
					<td class="texto_tabla"	width="30%" style="text-align:center">Tipo Reporte:</td>
				</tr>
				<tr>
					<td><script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'admin_jva_dashboard', TABLA_ACTUALIZAR:'0', VALOR_REGISTRO:'0', CODIGO_REGISTRO:'0', NOMBRE_CAMPO:'nombre', DIV:'jvaDiv', NOMBRE_SELECT:'sjva', ESTADO:'', ACCION:'consultar_campo'}, 'jvaDiv');</script><div id="jvaDiv"></div></td>
					<td>
						<select id="stiposReporte" class="lista_desplegable" onchange="generar_grafica();">
							<option value="recaudos">Recaudos</option>
							<option value="recaudos_ventas">Recuados y Ventas</option>
							<option value="gastos">Gastos</option>
							<option value="saldos">Saldos</option>
							<option value="ventas">Ventas</option>
						</select>
					</td>
				</tr>
			</table>
		</div>
		<div id="infoTH">
		</div>
		<div id="masTH" onclick="">m&aacute;s</div>
	</div>
	<div id="Ventas">
		<div id="nombreVV"><strong>Ventas</strong></div>
		<div id="infoVV">
			
		</div>
		<div id="masVV" onclick="AjaxConsulta('../logica/rpt_ventas.php', {ACCION:'detalle_venta', DIV:'area_trabajo'}, 'area_trabajo');">m&aacute;s</div>
	</div>
	<div id="Gastos">
		<div id="nombreG"><strong>Gastos JVA</strong></div>
		<div id="infoG">
			
		</div>
		<div id="masG" onclick="AjaxConsulta('../logica/rpt_gastos.php', {ACCION:'detalle_gastos', DIV:'area_trabajo'}, 'area_trabajo');">m&aacute;s</div>
	</div>
	<!-- -------------------------------------------------------------------- FIN REPORTE RECAUDOS ------------------------------------------------------------------------------------ -->
</div>
<br>
<script>AjaxConsulta('../logica/rpt_recaudos.php', {ACCION:'mostrar_front_recaudos'}, 'infoR');</script>
<script>AjaxConsulta('../logica/rpt_saldos.php', {ACCION:'mostrar_front_saldos'}, 'infoS');</script>
<script>AjaxConsulta('../logica/rpt_ventas.php', {ACCION:'mostrar_front_ventas', DIV:'infoVV'}, 'infoVV');</script>
<script>AjaxConsulta('../logica/rpt_gastos.php', {ACCION:'mostrar_front_gastos', DIV:'infoG'}, 'infoG');</script>
<script>AjaxConsulta('../logica/admin_tendencia_historica.php', {JVA_CODIGO:'0',ACCION:'recaudos'}, 'infoTH');</script>
