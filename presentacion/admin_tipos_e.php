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
<script type='text/javascript' src='../js/admin_tipos.js'></script>
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
				onclick="AjaxConsulta( '../logica/admin_tipos.php', {TABLA:'<?php echo $tabla?>', <?php echo $parametros?> ACCION:'editar_tipos'}, 'administarICDiv' );"/>
			<img src="imagenes/iconos/cancel_a.png" alt="Cancelar" title="Cancelar"
				style="cursor: pointer; border: 1px solid #CCC;"
				onmouseover="this.src = 'imagenes/iconos/cancel.png'"
				onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
				onclick="AjaxConsulta('../logica/admin_tipos.php', {TABLA:'<?php echo $tabla?>', ACCION:'sadministarIC'}, 'administarICDiv');"/>		
		</td>
	</tr>		
	<?php 
	for($l=0;$l<$i;$l++)
	{?>
	<tr>
		<td class="subtitulo_tabla" width="50%"><?php echo $atributos[$l]?></td>
		<td class="texto_tabla" width="50%">
			<?php 
			if(($atributos[$l]!="est_codigo")&&($atributos[$l]!="ciu_codigo")&&($atributos[$l]!="dep_codigo")&&($atributos[$l]!="pai_codigo")&&($atributos[$l]!="tp_codigo")&&($atributos[$l]!="to_codigo")&&($atributos[$l]!="ts_codigo")&&($atributos[$l]!="estr_codigo")&&($atributos[$l]!="ser_codigo")&&($atributos[$l]!="pro_codigo")&&($atributos[$l]!="tep_codigo")&&($atributos[$l]!="prod_codigo"))
			{?>
				<input type="text" id = "<?php echo $atributos[$l]?>" value="<?php echo $registros[$atributos[$l]]?>" class="<?php if($atributos[$l]=="codigo"){echo "campo_bloqueado";}else{ echo "campo";}?>" <?php if($atributos[$l]=="codigo"){echo 'readonly="readonly"';}?>>
			<?php 
			}
			else 
			{
				if($atributos[$l]=="est_codigo")
				{
				?>
					<script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_estados', TABLA_ACTUALIZAR:'0', VALOR_REGISTRO:'<?php echo $registros[$atributos[$l]][$m]?>', CODIGO_REGISTRO:'<?php echo $registros['codigo'][$m]?>', NOMBRE_CAMPO:'nombre', DIV:'estadoDiv', NOMBRE_SELECT:'<?php echo $atributos[$l];?>', ACCION:'consultar_campo_estado'}, 'estadoDiv' );</script><div id="estadoDiv"></div>
				<?php 
				}	
				else 
				{
					if($atributos[$l]=="est_codigo")
					{
						$tabla_c = "estados";
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
					if($atributos[$l]=="pro_codigo")
					{
						$tabla_c = "productos";
					}
					if($atributos[$l]=="prod_codigo")
					{
						$tabla_c = "productos";
					}
					?>
					<script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'<?php echo $tabla_c?>', TABLA_ACTUALIZAR:'0', VALOR_REGISTRO:'<?php echo $registros[$atributos[$l]]?>', CODIGO_REGISTRO:'<?php echo $registros['codigo']?>', NOMBRE_CAMPO:'nombre', DIV:'<?php echo $atributos[$l]?>Div', NOMBRE_SELECT:'<?php echo $atributos[$l];?>', ACCION:'consultar_campo'}, '<?php echo $atributos[$l]?>Div' );</script>
					<div id="<?php echo $atributos[$l]?>Div"></div>
					<?php
				}
			}
			?>
		</td>
	</tr>
	<?php 
	}
	?>
</table>
<br>