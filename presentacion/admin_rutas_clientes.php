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
<script>
$(document).ready(function() 
	    { 
	        $("#cliente_rutas").tablesorter(); 
	    } 
	); 
</script>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2" class="titulo_tabla">
			<table width="100%" align="center">
				<tr>
					<td width="50%" class="titulo_tabla" colspan="6" style="text-align:center">ADMINISTRAR RUTAS CLIENTES - <script>AjaxConsulta('../logica/proc_campo_tabla.php', {TABLA:'trans_rutas_jva', CAMPO:"nombre", CAMPO_CONDICION:'codigo', CONDICION:'<?php echo $trj_codigo?>', ACCION:'consultar_valor_campo2'}, 'rutaDiv');</script><div id="rutaDiv"></div></td>	
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id="cliente_rutas" class="tablesorter" width="100%" style="background: #FFF; border: 1px solid #CCC;"> 
			<thead> 
				<tr>
					<th width="12%" class="titulo_tabla" style="cursor: pointer;">Secuencia</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Cod. Cliente</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Cod. Argos</th>
					<th width="30%" class="titulo_tabla" style="cursor: pointer;">Cliente</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Cod. Producto</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Saldo</th>
					<th width="10%" class="titulo_tabla" style="cursor: pointer;">Fecha Solicitud</th>
					<th width="8%" class="titulo_tabla" style="cursor: pointer;" colspan="2">Acci&oacute;n</th>
				</tr>
			</thead>
				<tbody>
				<?php
					for($i=0;$i<$RowCount;$i++)
					{
				?>
				<tr class="fila">
					<td><div id="secuenciaDiv<?php echo $resultados['codigo'][$i]?>"><input type="text" id="secuencia<?php echo $resultados['codigo'][$i]?>" class="campo" value="<?php echo $resultados['secuencia'][$i]?>" size="10" onchange="AjaxConsulta( '../logica/proc_campo_tabla.php', {TABLA:'trans_rutas_detalles', CAMPO:'secuencia', VALOR:this.value, NOMBRE:this.id, SIZE:'10', CONDICION:'<?php echo $resultados['codigo'][$i]?>', DIV:'secuenciaDiv<?php echo $resultados['codigo'][$i]?>', CLASS:'ninguna', ACCION:'actualizar_campo'}, 'secuenciaDiv<?php echo $resultados['codigo'][$i]?>');"></div></td>
					<td class="texto_tabla"><?php echo $resultados['cli_codigo'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['cod_argos'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['cliente'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['tv_codigo'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['saldo'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['fecha_solicitud'][$i]?></td>
					<td class="texto_tabla">
						<img src="imagenes/iconos/move_a.png" alt="Cambiar de Ruta" title="Cambiar de Ruta"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/move.png'"
							onmouseout="this.src = 'imagenes/iconos/move_a.png'"
							width="20"
							align="absbottom"
							onclick="AjaxConsulta('../logica/admin_rutas.php', {TRD_CODIGO:'<?php echo $resultados['codigo'][$i]?>', TV_CODIGO:'<?php echo $resultados['tv_codigo'][$i]?>', CLI_CODIGO:'<?php echo $resultados['cli_codigo'][$i]?>', CLIENTE:'<?php echo $resultados['cliente'][$i]?>', ARG_CODIGO:'<?php echo $resultados['cod_argos'][$i]?>', ACCION:'mostrar_front_mover'}, 'Param_rutasDiv');"/>
					</td>
					<td class="texto_tabla">
						<div id="estado<?php echo $resultados['codigo'][$i]?>">
							<script>AjaxConsulta('../logica/admin_rutas.php', {CODIGO:'<?php echo $resultados['codigo'][$i]?>',  ACCION:'estado'}, 'estado<?php echo $resultados['codigo'][$i]?>');</script>
						</div>
					</td>
				</tr>
				<?php 
					}
				?>
				</tbody>
				<tr>
					<td colspan="10" class="titulo_tabla">Total Registros: <?php echo $RowCount?></td>
				</tr>
			</table>		
		</td>
	</tr>
</table>	
<br>