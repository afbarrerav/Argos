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
<script type='text/javascript' src='../js/admin_param.js'></script>
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
				onclick="AjaxConsulta( '../logica/admin_param.php', {TABLA:'<?php echo $tabla?>', ACCION:'form_nuevo_param'}, 'administarICDiv' );"/>
		</td>
	</tr>
	<tr>
		<td  colspan ="<?php echo $i+1;?>">
			<table id="ic_tabla" class="tablesorter" width="100%" style="background: #FFF; border: 1px solid #CCC;"> 
				<thead> 
				<tr>		
					<?php
					/*
					 * CONSTRUYE LA CABECERA DE LA TABLA DE ACUERDO A LOS NOMBRES DE LAS COLUMNAS
					 * */ 
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
					<td class="texto_tabla">
					<?php
						/*
						 * IDENTIFICA SI EL REGISTRO ES UNA LLAVE FORANEA DE OTRA TABLA
						 * */ 
						if(($atributos[$l]!="est_codigo")&&($atributos[$l]!="jva_codigo")&&($atributos[$l]!="aju_codigo")&&($atributos[$l]!="um_codigo")&&($atributos[$l]!="tpj_codigo")&&($atributos[$l]!="cat_codigo")&&($atributos[$l]!="tg_codigo")&&($atributos[$l]!="rol_codigo")&&($atributos[$l]!="rol_codigo_desde")&&($atributos[$l]!="rol_codigo_hasta")&&($atributos[$l]!="plj_codigo")&&($atributos[$l]!="ppj_codigo")&&($atributos[$l]!="tti_codigo")&&($atributos[$l]!="ciu_codigo"))
						{
							/*
							 * EL REGISTRO NO ES LLAVE FORANEA --> IMPRIME EL REGISTRO
							 * */
							echo $registros[$atributos[$l]][$m];
						}
						else 
						{
							/*
							 * EL REGISTRO ES LLAVE FORANE --> 	ASIGNA EL NOMBRE DE LA TABLA A LA VARIABLE PARA 
							 * 									POSTERIOR CONSULTA DEL NOMBRE ASIGNADO AL REGISTRO
							 * */
							if($atributos[$l]=="est_codigo")
							{
								$tabla_c = "tipos_estados";
								$nombre_c = "nombre";
								$accion_realizar='consultar_valor_campo';
							}
							if($atributos[$l]=="jva_codigo")
							{
								$tabla_c = "admin_jva";
								$nombre_c = "nombre";
								$accion_realizar='consultar_valor_campo';
							}
							if($atributos[$l]=="aju_codigo")
							{
								$tabla_c = "admin_jva_usuarios";
								$nombre_c = "usu_codigo";
								$accion_realizar='consultar_valor_campo_aju';
							}	
							if($atributos[$l]=="um_codigo")
							{
								$tabla_c = "tipos_unidades_medidas";
								$nombre_c = "nombre";
								$accion_realizar='consultar_valor_campo';
							}
							if($atributos[$l]=="tpj_codigo")
							{
								$tabla_c = "param_tipos_productos_jva";
								$nombre_c = "nombre";
								$accion_realizar='consultar_valor_campo';
							}
							if($atributos[$l]=="cat_codigo")
							{
								$tabla_c = "param_categorias_productos_jva";
								$nombre_c = "nombre";
								$accion_realizar='consultar_valor_campo';
							}
							if($atributos[$l]=="tg_codigo")
							{
								$tabla_c = "tipos_gastos";
								$nombre_c = "nombre";
								$accion_realizar='consultar_valor_campo';
							}
							if($atributos[$l]=="rol_codigo")
							{
								$tabla_c = "admin_roles";
								$nombre_c = "nombre";
								$accion_realizar='consultar_valor_campo';
							}
							if($atributos[$l]=="rol_codigo_desde")
							{
								$tabla_c = "admin_roles";
								$nombre_c = "nombre";
								$accion_realizar='consultar_valor_campo';
							}
							if($atributos[$l]=="rol_codigo_hasta")
							{
								$tabla_c = "admin_roles";
								$nombre_c = "nombre";
								$accion_realizar='consultar_valor_campo';
							}
							if($atributos[$l]=="plj_codigo")
							{
								$tabla_c = "param_localidades_jva";
								$nombre_c = "nombre";
								$accion_realizar='consultar_valor_campo';
							}
							if($atributos[$l]=="ppj_codigo")
							{
								$tabla_c = "param_productos_jva";
								$nombre_c = "nombre";
								$accion_realizar='consultar_valor_campo';
							}
							if($atributos[$l]=="tti_codigo")
							{
								$tabla_c = "tipos_traslados_inventarios";
								$nombre_c = "nombre";
								$accion_realizar='consultar_valor_campo';
							}
							if($atributos[$l]=="ciu_codigo")
							{
								$tabla_c = "tipos_ciudades";
								$nombre_c = "nombre";
								$accion_realizar='consultar_valor_campo';
							}
							/*
							 * UTILIZA PROC_CAMPO_TABLA PARA CONSULTAR EL NOMBRE ASIGNADO AL REGISTRO
							 * */
							?> <script>AjaxConsulta( '../logica/proc_campo_tabla.php', {TABLA:'<?php echo $tabla_c?>', CAMPO:'<?php echo $nombre_c?>', CONDICION:'<?php echo $registros[$atributos[$l]][$m]?>', ACCION:'<?php echo $accion_realizar?>'}, '<?php echo $atributos[$l]?>Div<?php echo $registros['codigo'][$m]?>' );</script>
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
							onclick="AjaxConsulta( '../logica/admin_param.php', {TABLA:'<?php echo $tabla?>', CODIGO:'<?php echo $registros['codigo'][$m]?>', ACCION:'form_editar_param'}, 'administarICDiv' );"/>
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
							onclick="AjaxConsulta( '../logica/admin_param.php', {TABLA:'<?php echo $tabla?>', CODIGO:'<?php echo $registros['codigo'][$m]?>', EST_CODIGO:'2', ACCION:'actualizar_estado'}, 'administarICDiv' );"/>
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
							onclick="AjaxConsulta( '../logica/admin_param.php', {TABLA:'<?php echo $tabla?>', CODIGO:'<?php echo $registros['codigo'][$m]?>', EST_CODIGO:'1', ACCION:'actualizar_estado'}, 'administarICDiv' );"/>
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