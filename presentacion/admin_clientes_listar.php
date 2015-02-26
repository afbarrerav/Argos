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
<br>

<script>
$(document).ready(function() 
	    { 
	        $("#clientes").tablesorter(); 
	    } 
	); 
</script>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td colspan="2" class="titulo_tabla">
			<table width="100%" align="center">
				<tr>
					<td width="50%" class="titulo_tabla" colspan="6" style="text-align:center">CLIENTES</td>	
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id="clientes" class="tablesorter" width="100%" style="background: #FFF; border: 1px solid #CCC;"> 
			<thead> 
				<tr>
					<th width="5%" class="titulo_tabla" style="cursor: pointer;">Codigo</th>
					<th width="15%" class="titulo_tabla" style="cursor: pointer;">Tipo Identificaci&oacute;n</th>
					<th width="12%" class="titulo_tabla" style="cursor: pointer;">Identificaci&oacute;n</th>
					<th width="40%" class="titulo_tabla" style="cursor: pointer;">Nombre y Apellidos</th>
					<th width="20%" class="titulo_tabla" style="cursor: pointer;">Direcci&oacute;n</th>
					<td width="8%" class="titulo_tabla" style="cursor: pointer;" colspan="2">Acci&oacute;n</td>
				</tr>
			</thead>
				<tbody>
				<?php
					for($i=0;$i<$RowCount;$i++)
					{
				?>
				<tr class="fila">
					<td class="texto_tabla"><?php echo $resultados['codigo'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['tipo_identificacion'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['nroidentificacion'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['nombre'][$i]?></td>
					<td class="texto_tabla"><?php echo $resultados['direccion'][$i]?></td>
					<td class="texto_tabla">
						<img src="imagenes/iconos/edit_a.png" alt="Editar" title="Editar"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/edit.png'"
							onmouseout="this.src = 'imagenes/iconos/edit_a.png'"
							width="20"
							align="absbottom"
							onclick="AjaxConsulta('../logica/admin_clientes.php', {CODIGO:'<?php echo $resultados['codigo'][$i]?>', ACCION:'editar'}, 'listar_clientes_Div');"/>
					</td>
					<td class="texto_tabla">
						<div id="estado<?php echo $resultados['codigo'][$i]?>">
							<script>AjaxConsulta('../logica/admin_clientes.php', {CODIGO:'<?php echo $resultados['codigo'][$i]?>',  ACCION:'estado'}, 'estado<?php echo $resultados['codigo'][$i]?>');</script>
						</div>
					</td>
				</tr>
				<?php 
					}
				?>
				</tbody>
				<tr>
					<td colspan="8" class="titulo_tabla">Total Registros: <?php echo $RowCount?></td>
				</tr>
			</table>		
		</td>
	</tr>
</table>	
<br>