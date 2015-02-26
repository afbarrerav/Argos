<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Diciembre de 2012
 *
 * */
?>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height: 75%;">
		<tr>
			<td class="titulo_tabla" colspan="2">LIQUIDACI&Oacute;N SALARIAL <BR> <?php echo $fecha_inicio ." - ". $fecha_fin?></td>
		</tr>
		<tr>
			<td class="texto_tabla" colspan="2">
				<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height: 75%;">
					<tr>
						<td width="33%" align="left">IDENTIFICACION:</td>
						<td width="33%" align="left">NOMBRES Y APELLIDOS</td>	
						<td width="33%" align="left">FECHA DE NACIMINETO</td>	
					</tr>
					<tr>
						<td align="left"><b><?php echo number_format($cc_vendedor);?></b></td>
						<td align="left"><b><?php echo $nombres_vendedor." ".$apellidos_vendedor;?></b></td>	
						<td align="left"><b><?php echo $fecha_nacimiento_vendedor;?></b></td>	
					</tr>
					<tr>
						<td align="left">DIRECCION:</td>
						<td align="left">TELEFONO</td>	
						<td align="left">E - MAIL</td>	
					</tr>
					<tr>
						<td align="left"><b><?php echo $direccion_vendedor;?></b></td>
						<td align="left"><b><?php echo $telefono_vendedor;?></b></td>	
						<td align="left"><b><?php echo $mail_vendedor;?></b></td>	
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="texto_tabla" colspan="2">
				<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height: 75%;">
					<tr>
						<td width="25%" class="titulo_tabla" align="center">CARTERA INICIAL</td>
						<td width="25%" class="titulo_tabla" align="center">CARTERA FINAL</td>	
						<td width="25%" class="titulo_tabla" align="center">CLIENTES FINAL</td>
						<td width="25%" class="titulo_tabla" align="center">PROMEDIO DE RECAUDO</td>	
					</tr>
					<tr>
						<td class="numero_tabla"><b>$ <?php echo number_format($saldo_incial);?></b></td>
						<td class="numero_tabla"><b>$ <?php echo number_format($saldo_final);?></b></td>	
						<td class="numero_tabla"><b><?php echo number_format($clientes_final);?></b></td>
						<td class="numero_tabla"><b>$ <?php echo number_format($promedio_recaudo);?></b></td>		
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="titulo_tabla" width="50%">Liquidacion Vendedor</td>
			<td class="titulo_tabla" width="50%">Liquidacion Ruta</td>
		</tr>
		<tr>
			<td valign="top">
				<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height: 75%;">
					<tr>
						<td class="titulo_tabla" width="50%">VALOR RECAUDADO:</td>
						<td class="numero_tabla" width="50%">$ <?php echo number_format($valor_recaudo);?></td>	
					</tr>
					<tr>
						<td class="titulo_tabla" width="50%">VALOR META:</td>
						<td class="numero_tabla" width="50%">$ <?php echo number_format($valor_meta_recaudo);?></td>	
					</tr>
					<tr>
						<td class="titulo_tabla" width="50%">SALARIO BASICO:</td>
						<td class="numero_tabla" width="50%">$ <?php echo number_format($valor_salario_basico);?></td>	
					</tr>
					<tr>
						<td class="titulo_tabla" width="50%">PROCENTAJE COMISION SOBRE META DE RECAUDADO:</td>
						<td class="numero_tabla" width="50%">$ <?php echo number_format($porcentaje_comision,1);?></td>	
					</tr>
					<tr>
						<td class="titulo_tabla" width="50%">VALOR DIFERENCIAS EN CONTRA:</td>
						<td class="numero_tabla" width="50%">$ <?php echo number_format(0);?></td>	
					</tr>
					<tr>
						<td class="titulo_tabla" width="50%">VALOR SALARIO A PAGAR:</td>
						<td class="numero_tabla" width="50%">$ <?php echo number_format($valor_salario_apagar, 2);?></td>	
					</tr>
				</table>
			</td>
			<td valign="top">
				<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC; margin: 14px 0px 0px 0px; height: 75%;">
					<tr>
						<td class="titulo_tabla" width="50%">VALOR RECAUDADO:</td>
						<td class="numero_tabla" width="50%">$ <?php echo number_format($valor_recaudo);?></td>	
					</tr>
					<tr>
						<td class="titulo_tabla" width="50%">VALOR CARGUES A LA RUTA:</td>
						<td class="numero_tabla" width="50%">$ <?php echo number_format($valor_cargue);?></td>	
					</tr>
					<tr>
						<td class="titulo_tabla" width="50%">VALOR RETIROS A LA RUTA:</td>
						<td class="numero_tabla" width="50%">$ <?php echo number_format($valor_retiros);?></td>	
					</tr>
					<tr>
						<td class="titulo_tabla" width="50%">VALOR GASTOS DE LA RUTA:</td>
						<td class="numero_tabla" width="50%">$ <?php echo number_format($valor_gastos,2);?></td>	
					</tr>
					<tr>
						<td class="titulo_tabla" width="50%">VALOR VENTAS DE LA RUTA</td>
						<td class="numero_tabla" width="50%">$ <?php echo number_format($valor_ventas);?></td>	
					</tr>
					<tr>
						<td class="titulo_tabla" width="50%">VALOR SALARIO VENDEDOR:</td>
						<td class="numero_tabla" width="50%">$ <?php echo number_format($valor_salario_apagar, 2);?></td>	
					</tr>
					<tr>
						<td class="titulo_tabla" width="50%">VALOR AJUSTE A LA RUTA:</td>
						<td class="numero_tabla" width="50%">$ <?php echo number_format($valor_ajuste_ruta, 2);?></td>	
					</tr>
				</table>
			</td>
		</tr>
		<?php
		if ($existe_liquidacion <= 0)
		{?>	
		<tr>
			<td align="left" class="texto_tabla2" colspan="2">
				<font color="red">&#42; Haga clic en el bot&oacute;n Guardar Liquidaci&oacute;n si la liquidaci&oacute;n presentada incluye todos los valores correspondientes a cargues, retiros y gastos de la ruta. <br>
				&#42; Al hacer clic en el bot&oacute;n Guardar Liquidaci&oacute;n automaticamente se genera la transacci&oacute;n de ajuste necesaria para que la ruta sea cerrada con valor 0.</font> 
			</td>
		</tr>
		<tr>	
			<td colspan="10" align="center" colspan="2">	
				<input type="button" name="bt1" value='Guardar Liquidaci&oacute;n'
				class="boton" title="Haga clic para guardar la liquidacion del salario"
				onclick="cosultar_liquidacion();"></td>
		</tr>
		<?php
		}
		?>
</table>
<br>
<div id="detalle_gastos_ruta_Div"></div>