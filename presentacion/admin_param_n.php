<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
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

$parametros ="";
?>
<script>
function f_validar_campos()
{
	var formulario_valido = 0
<?php
	for($l=0;$l<$i;$l++)
	{
		if(($atributos['Field'][$l]!="codigo")&&($atributos['Field'][$l]!="est_codigo"))
		{
			/*
			 * IDENTIFICA LOS PARAMETROS QUE DEBEN SER ENVIADOS EN EL AJAXCONSULTA
			 * */
			$parametros .=$atributos['Field'][$l].":$('#".$atributos['Field'][$l]."').val(), ";
			
			/*
			 * CONSTRUYE LAS FUNCIONES QUE VALIDAN LOS CAMPOS
			 * */
			?>
			var campo_nulo_<?php echo $atributos['Field'][$l]?> = "<?php echo $atributos['Null'][$l]?>";
			
			if(campo_nulo_<?php echo $atributos['Field'][$l]?> == "NO")
			{
				var longitud = $('#<?php echo $atributos['Field'][$l]?>').val();
				if (longitud.length > 0)
				{
					var tipo_campo_<?php echo $atributos['Field'][$l]?> = "<?php echo $atributos['Type'][$l]?>";
					if (tipo_campo_<?php echo $atributos['Field'][$l]?>.substring(0,3) == "int")
					{
						var valor = $('#<?php echo $atributos['Field'][$l]?>').val();
						if(isNaN(valor))
						{
							formulario_valido = 2;													
						}
					}
				}
				else
				{
					formulario_valido = 1;
				}		
			}	
			<?php 
		} 
	}
	?>
	if (formulario_valido == 1)
	{
		alert("No es posible guardar el registro, los campos marcados con * son obligatorios");
	}
	if (formulario_valido == 2)
	{
		alert("No es posible guardar el registro, los campos marcados con ** deben ser un numero");
	}
	if (formulario_valido == 0) 
	{
		AjaxConsulta( '../logica/admin_param.php', {TABLA:'<?php echo $tabla?>', <?php echo $parametros?> ACCION:'guardar_nuevo_param'}, 'administarICDiv' ); 
	}
}
</script>
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
				onclick="f_validar_campos();"/>
			<img src="imagenes/iconos/cancel_a.png" alt="Cancelar" title="Cancelar"
				style="cursor: pointer; border: 1px solid #CCC;"
				onmouseover="this.src = 'imagenes/iconos/cancel.png'"
				onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
				onclick="AjaxConsulta('../logica/admin_param.php', {TABLA:'<?php echo $tabla?>', ACCION:'sadministarIC'}, 'administarICDiv');"/>		
		</td>
	</tr>		
	<?php 
	for($l=0;$l<$i;$l++)
	{
		if(($atributos['Field'][$l]!="codigo")&&($atributos['Field'][$l]!="est_codigo"))
		{
		?>
		<tr>
			<td class="subtitulo_tabla" width="50%"><?php echo $atributos['Field'][$l]?></td>
			<td class="texto_tabla" width="50%">
				<?php 
				if(($atributos['Field'][$l]!="tcg_codigo")&&($atributos['Field'][$l]!="est_codigo")&&($atributos['Field'][$l]!="jva_codigo")&&($atributos['Field'][$l]!="aju_codigo")&&($atributos['Field'][$l]!="um_codigo")&&($atributos['Field'][$l]!="tpj_codigo")&&($atributos['Field'][$l]!="cat_codigo")&&($atributos['Field'][$l]!="tg_codigo")&&($atributos['Field'][$l]!="rol_codigo")&&($atributos['Field'][$l]!="rol_codigo_desde")&&($atributos['Field'][$l]!="rol_codigo_hasta")&&($atributos['Field'][$l]!="plj_codigo")&&($atributos['Field'][$l]!="ppj_codigo")&&($atributos['Field'][$l]!="tti_codigo")&&($atributos['Field'][$l]!="ciu_codigo"))
				{?>
					<input type="text" id = "<?php echo $atributos['Field'][$l]?>" value="" class="<?php if($atributos['Field'][$l]=="codigo"){echo "campo_bloqueado";}else{ echo "campo";}?>" <?php if($atributos['Field'][$l]=="codigo"){echo 'readonly="readonly"';}?>><?php  if(substr($atributos['Type'][$l],0,3)=="int"){echo "**";}else{if($atributos['Null'][$l]=="NO"){echo "*";}}?>
				<?php 
				}
				else 
				{
					if($atributos['Field'][$l]=="tcg_codigo")
					{
						$tabla_c = "tipos_causacion_gastos";
						$accion_realizar = "consultar_campo";	
					}
					if($atributos['Field'][$l]=="jva_codigo")
					{
						$tabla_c = "admin_jva";
						$accion_realizar = "consultar_campo_jva";	
					}
					if($atributos['Field'][$l]=="aju_codigo")
					{
						$tabla_c = "admin_jva_usuarios";
						$accion_realizar='consultar_campo';
					}
					if($atributos['Field'][$l]=="um_codigo")
					{
						$tabla_c = "tipos_unidades_medidas";
						$accion_realizar='consultar_campo';
					}
					if($atributos['Field'][$l]=="tpj_codigo")
					{
						$tabla_c = "param_tipos_productos_jva";
						$accion_realizar='consultar_campo';
					}
					if($atributos['Field'][$l]=="cat_codigo")
					{
						$tabla_c = "param_categorias_productos_jva";
						$accion_realizar='consultar_campo';
					}
					if($atributos['Field'][$l]=="tg_codigo")
					{
						$tabla_c = "tipos_gastos";
						$accion_realizar='consultar_campo';
					}
					if($atributos['Field'][$l]=="rol_codigo")
					{
						$tabla_c = "admin_roles";
						$accion_realizar='consultar_campo';
					}
					if($atributos['Field'][$l]=="rol_codigo_desde")
					{
						$tabla_c = "admin_roles";
						$accion_realizar='consultar_campo';
					}
					if($atributos['Field'][$l]=="rol_codigo_hasta")
					{
						$tabla_c = "admin_roles";
						$accion_realizar='consultar_campo';
					}
					if($atributos['Field'][$l]=="plj_codigo")
					{
						$tabla_c = "param_localidades_jva";
						$accion_realizar='consultar_campo';
					}
					if($atributos['Field'][$l]=="ppj_codigo")
					{
						$tabla_c = "param_productos_jva";
						$accion_realizar='consultar_campo';
					}
					if($atributos['Field'][$l]=="tti_codigo")
					{
						$tabla_c = "tipos_traslados_inventarios";
						$accion_realizar='consultar_campo';
					}
					if($atributos['Field'][$l]=="ciu_codigo")
					{
						$tabla_c = "tipos_ciudades";
						$accion_realizar='consultar_campo';
					}
					?> <script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'<?php echo $tabla_c?>', TABLA_ACTUALIZAR:'0', VALOR_REGISTRO:'<?php echo $atributos['Field'][$l]?>', CODIGO_REGISTRO:'', NOMBRE_CAMPO:'nombre', DIV:'<?php echo $atributos['Field'][$l];?>Div', NOMBRE_SELECT:'<?php echo $atributos['Field'][$l];?>',  ESTADO:'', ACCION:'<?php echo $accion_realizar?>'}, '<?php echo $atributos['Field'][$l];?>Div' );</script>
							<div id="<?php echo $atributos['Field'][$l];?>Div"></div>
					<?php
				}
				?>
			</td>
		</tr>
		<?php
		} 
	}
	?>
</table>
<br>