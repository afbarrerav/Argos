<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 *
 * */

$parametros ="";
for($l=0;$l<$i;$l++)
{
	$parametros .=$atributos[$l].":$('#$atributos[$l]').val(), "; 
}
?>
<script type='text/javascript' src='../js/admin_param.js'></script>
<br>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td class="titulo_tabla" height= "20" colspan ="2">Detalle</td>
	</tr>
	<tr>
		<td  colspan ="2">
			<img src="imagenes/iconos/save_a.png" alt="Guardar" title="Guardar"
				style="cursor: pointer; border: 1px solid #CCC;"
				onmouseover="this.src = 'imagenes/iconos/save.png'"
				onmouseout="this.src = 'imagenes/iconos/save_a.png'"
				onclick="AjaxConsulta( '../logica/admin_param.php', {TABLA:'<?php echo $tabla?>', <?php echo $parametros?> ACCION:'editar_param'}, 'administarICDiv' );"/>
			<img src="imagenes/iconos/cancel_a.png" alt="Cancelar" title="Cancelar"
				style="cursor: pointer; border: 1px solid #CCC;"
				onmouseover="this.src = 'imagenes/iconos/cancel.png'"
				onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
				onclick="AjaxConsulta('../logica/admin_param.php', {TABLA:'<?php echo $tabla?>', ACCION:'sadministarIC'}, 'administarICDiv');"/>		
		</td>
	</tr>		
	<?php 
	for($l=0;$l<$i;$l++)
	{?>
	<tr>
		<td class="subtitulo_tabla" width="50%"><?php echo $atributos[$l]?></td>
		<td class="texto_tabla" width="50%">
			<?php 
			if(($atributos[$l]!="tcg_codigo")&&($atributos[$l]!="est_codigo")&&($atributos[$l]!="jva_codigo")&&($atributos[$l]!="aju_codigo")&&($atributos[$l]!="um_codigo")&&($atributos[$l]!="tpj_codigo")&&($atributos[$l]!="cat_codigo")&&($atributos[$l]!="tg_codigo")&&($atributos[$l]!="rol_codigo")&&($atributos[$l]!="rol_codigo_desde")&&($atributos[$l]!="rol_codigo_hasta")&&($atributos[$l]!="plj_codigo")&&($atributos[$l]!="ppj_codigo")&&($atributos[$l]!="tti_codigo")&&($atributos[$l]!="ciu_codigo"))
			{?>
				<input type="text" id = "<?php echo $atributos[$l]?>" value="<?php echo $registros[$atributos[$l]]?>" class="<?php if($atributos[$l]=="codigo"){echo "campo_bloqueado";}else{ echo "campo";}?>" <?php if($atributos[$l]=="codigo"){echo 'readonly="readonly"';}?>>
			<?php 
			}
			else 
			{
				if($atributos[$l]=="tcg_codigo")
				{
					$tabla_c = "tipos_causacion_gastos";
					$accion_realizar='consultar_campo';
				}
				if($atributos[$l]=="jva_codigo")
				{
					$tabla_c = "admin_jva";
					$accion_realizar='consultar_campo_jva';
				}	
	
				if($atributos[$l]=="est_codigo")
				{
					$tabla_c = "tipos_estados";
					$accion_realizar='consultar_campo';
				}							
				if($atributos[$l]=="aju_codigo")
				{
					$tabla_c = "admin_jva_usuarios";
					$accion_realizar='consultar_campo';
				}
				if($atributos[$l]=="um_codigo")
				{
					$tabla_c = "tipos_unidades_medidas";
					$accion_realizar='consultar_campo';
				}
				if($atributos[$l]=="tpj_codigo")
				{
					$tabla_c = "param_tipos_productos_jva";
					$accion_realizar='consultar_campo';
				}
				if($atributos[$l]=="cat_codigo")
				{
					$tabla_c = "param_categorias_productos_jva";
					$accion_realizar='consultar_campo';
				}
				if($atributos[$l]=="tg_codigo")
				{
					$tabla_c = "tipos_gastos";
					$accion_realizar='consultar_campo';
				}
				if($atributos[$l]=="rol_codigo")
				{
					$tabla_c = "admin_roles";
					$accion_realizar='consultar_campo';
				}
				if($atributos[$l]=="rol_codigo_desde")
				{
					$tabla_c = "admin_roles";
					$accion_realizar='consultar_campo';
				}
				if($atributos[$l]=="rol_codigo_hasta")
				{
					$tabla_c = "admin_roles";
					$accion_realizar='consultar_campo';
				}
				if($atributos[$l]=="plj_codigo")
				{
					$tabla_c = "param_localidades_jva";
					$accion_realizar='consultar_campo';
				}
				if($atributos[$l]=="ppj_codigo")
				{
					$tabla_c = "param_productos_jva";
					$accion_realizar='consultar_campo';
				}
				if($atributos[$l]=="tti_codigo")
				{
					$tabla_c = "tipos_traslados_inventarios";
					$accion_realizar='consultar_campo';
				}
				if($atributos[$l]=="ciu_codigo")
				{
					$tabla_c = "tipos_ciudades";
					$accion_realizar='consultar_campo';
				}
				?>
				<script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'<?php echo $tabla_c?>', TABLA_ACTUALIZAR:'0', VALOR_REGISTRO:'<?php echo $registros[$atributos[$l]]?>', CODIGO_REGISTRO:'<?php echo $registros['codigo']?>', NOMBRE_CAMPO:'nombre', DIV:'<?php echo $atributos[$l]?>Div', NOMBRE_SELECT:'<?php echo $atributos[$l];?>', ESTADO:'', ACCION:'<?php echo $accion_realizar?>'}, '<?php echo $atributos[$l]?>Div' );</script>
				<div id="<?php echo $atributos[$l]?>Div"></div>
				<?php
			}
			?>
		</td>
	</tr>
	<?php 
	}
	?>
</table>
<br>