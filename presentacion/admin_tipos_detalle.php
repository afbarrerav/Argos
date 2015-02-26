<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 *
 * */

?>
<script>
$(document).ready(function() 
	    { 
	        $("#ic_tabla").tablesorter(); 
	    } 
	); 
</script>
<script type='text/javascript' src='../js/admin_tipos.js'></script>
<br>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td class="titulo_tabla" height= "20" colspan ="<?php echo $i+1;?>">Detalle</td>
	</tr>
	<tr>
		<td  colspan ="<?php echo $i+1;?>">
			<img src="imagenes/iconos/add_a.png" alt="Agregar" title="Agregar"
				style="cursor: pointer; border: 1px solid #CCC;"
				onmouseover="this.src = 'imagenes/iconos/add.png'"
				onmouseout="this.src = 'imagenes/iconos/add_a.png'"
				onclick="AjaxConsulta( '../logica/admin_tipos.php', {TABLA:'<?php echo $tabla?>', ACCION:'form_nuevo_tipos'}, 'administarICDiv' );"/>
		</td>
	</tr>
	<tr>
		<td  colspan ="<?php echo $i+1;?>">
			<table id="ic_tabla" class="tablesorter" width="100%" style="background: #FFF; border: 1px solid #CCC;"> 
				<thead> 
				<tr>		
					<?php 
					for($l=0;$l<$i;$l++)
					{?>
					<th class="titulo_tabla"><?php echo $atributos[$l]?></th>
					<?php 
					}
					?>
					<td class="titulo_tabla" width="6%">Acci&oacute;n</td>
				</tr>
				</thead>
				<tbody>
				<?php 
				for($m=0;$m<$j;$m++)
				{
				?>
				<tr class="fila">
					<?php 
					for($l=0;$l<$i;$l++)
					{?>
					<td class="texto_tabla"><?php 
						if(($atributos[$l]!="est_codigo")&&($atributos[$l]!="ciu_codigo")&&($atributos[$l]!="dep_codigo")&&($atributos[$l]!="pai_codigo")&&($atributos[$l]!="tp_codigo")&&($atributos[$l]!="to_codigo")&&($atributos[$l]!="ts_codigo")&&($atributos[$l]!="estr_codigo")&&($atributos[$l]!="ser_codigo")&&($atributos[$l]!="pro_codigo")&&($atributos[$l]!="tep_codigo")&&($atributos[$l]!="prod_codigo"))
						{
							echo $registros[$atributos[$l]][$m];
						}
						else 
						{
							if($atributos[$l]=="est_codigo")
							{
								$tabla_c = "tipos_estados";
							}	
							if($atributos[$l]=="ciu_codigo")
							{
								$tabla_c = "tipos_ciudades";
							}
							if($atributos[$l]=="dep_codigo")
							{
								$tabla_c = "tipos_departamentos";
							}
							if($atributos[$l]=="pai_codigo")
							{
								$tabla_c = "tipos_paises";
							}
							if($atributos[$l]=="tp_codigo")
							{
								$tabla_c = "tipos_productos";
							}
							if($atributos[$l]=="to_codigo")
							{
								$tabla_c = "tipos_operacion";
							}
							if($atributos[$l]=="ts_codigo")
							{
								$tabla_c = "tipos_servicios";
							}
							if($atributos[$l]=="estr_codigo")
							{
								$tabla_c = "tipos_estratos";
							}
							if($atributos[$l]=="ser_codigo")
							{
								$tabla_c = "servicios";
							}
							if($atributos[$l]=="tep_codigo")
							{
								$tabla_c = "tipos_estados_procesos";
							}
							if($atributos[$l]=="prod_codigo")
							{
								$tabla_c = "productos";
							}
							?> <script>AjaxConsulta( '../logica/proc_campo_tabla.php', {TABLA:'<?php echo $tabla_c?>', CAMPO:'nombre', CONDICION:'<?php echo $registros[$atributos[$l]][$m]?>', ACCION:'consultar_valor_campo'}, '<?php echo $atributos[$l]?>Div<?php echo $registros['codigo'][$m]?>' );</script>
								<div id="<?php echo $atributos[$l]?>Div<?php echo $registros['codigo'][$m]?>"></div>
							<?php
						}?>
					</td>
					<?php 
					}
					?>
					<td class="texto_tabla">
						<img src="imagenes/iconos/edit_a.png" alt="Editar" title="Editar"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/edit.png'"
							onmouseout="this.src = 'imagenes/iconos/edit_a.png'"
							width="20"
							align="absbottom"
							onclick="AjaxConsulta( '../logica/admin_tipos.php', {TABLA:'<?php echo $tabla?>', CODIGO:'<?php echo $registros['codigo'][$m]?>', ACCION:'form_editar_tipos'}, 'administarICDiv' );"/>
						<?php 
						if($registros['est_codigo'][$m]==1)
						{	
						?>
						<img src="imagenes/iconos/round_remove_a.png" alt="Desactivar" title="Desactivar"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/round_remove.png'"
							onmouseout="this.src = 'imagenes/iconos/round_remove_a.png'"
							width="20"
							align="absbottom"
							onclick="AjaxConsulta( '../logica/admin_tipos.php', {TABLA:'<?php echo $tabla?>', CODIGO:'<?php echo $registros['codigo'][$m]?>', EST_CODIGO:'2', ACCION:'actualizar_estado'}, 'administarICDiv' );"/>
						<?php 
						}
						if($registros['est_codigo'][$m]==2)
						{	
						?>
						<img src="imagenes/iconos/round_ok_a.png" alt="Activar" title="Activar"
							style="cursor: pointer; border: 1px solid #CCC;"
							onmouseover="this.src = 'imagenes/iconos/round_ok.png'"
							onmouseout="this.src = 'imagenes/iconos/round_ok_a.png'"
							width="20"
							align="absbottom"
							onclick="AjaxConsulta( '../logica/admin_tipos.php', {TABLA:'<?php echo $tabla?>', CODIGO:'<?php echo $registros['codigo'][$m]?>', EST_CODIGO:'1', ACCION:'actualizar_estado'}, 'administarICDiv' );"/>
						<?php 
						}
						?>
					</td>
				</tr>	
				<?php 	
				}
				?>
				</tbody>
				<tr>
					<td colspan="<?php echo $i+1;?>" class="titulo_tabla">Total Registros: <?php echo $j?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>			
<br>