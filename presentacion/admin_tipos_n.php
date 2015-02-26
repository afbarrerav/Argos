<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 *
 * */

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
	
		AjaxConsulta( '../logica/admin_tipos.php', {TABLA:'<?php echo $tabla?>', <?php echo $parametros?> ACCION:'guardar_nuevo_tipos'}, 'administarICDiv' ); 
	}
}
</script>
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
				onclick="f_validar_campos();"/>
			<img src="imagenes/iconos/cancel_a.png" alt="Cancelar" title="Cancelar"
				style="cursor: pointer; border: 1px solid #CCC;"
				onmouseover="this.src = 'imagenes/iconos/cancel.png'"
				onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
				onclick="AjaxConsulta('../logica/admin_tipos.php', {TABLA:'<?php echo $tabla?>', ACCION:'sadministarIC'}, 'administarICDiv');"/>		
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
				if(($atributos['Field'][$l]!="est_codigo")&&($atributos['Field'][$l]!="ciu_codigo")&&($atributos['Field'][$l]!="dep_codigo")&&($atributos['Field'][$l]!="pai_codigo")&&($atributos['Field'][$l]!="tp_codigo")&&($atributos['Field'][$l]!="to_codigo")&&($atributos['Field'][$l]!="ts_codigo")&&($atributos['Field'][$l]!="estr_codigo")&&($atributos['Field'][$l]!="ser_codigo")&&($atributos['Field'][$l]!="pro_codigo")&&($atributos['Field'][$l]!="tep_codigo")&&($atributos['Field'][$l]!="prod_codigo"))
				{?>
					<input type="text" id = "<?php echo $atributos['Field'][$l]?>" value="" class="<?php if($atributos['Field'][$l]=="codigo"){echo "campo_bloqueado";}else{ echo "campo";}?>" <?php if($atributos['Field'][$l]=="codigo"){echo 'readonly="readonly"';}?>><?php  if(substr($atributos['Type'][$l],0,3)=="int"){echo "**";}else{if($atributos['Null'][$l]=="NO"){echo "*";}}?>
				<?php 
				}
				else 
				{
					if($atributos['Field'][$l]=="est_codigo")
					{
						?> <script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_estados', TABLA_ACTUALIZAR:'0', VALOR_REGISTRO:'', CODIGO_REGISTRO:'', NOMBRE_CAMPO:'nombre', DIV:'estadoDiv', NOMBRE_SELECT:'<?php echo $atributos['Field'][$l];?>', ACCION:'consultar_campo_estado'}, 'estadoDiv' );</script>
							<div id="estadoDiv"></div>
						<?php
					}
					else
					{
						if($atributos['Field'][$l]=="ciu_codigo")
						{
							$tabla_c = "tipos_ciudades";
						}
						if($atributos['Field'][$l]=="dep_codigo")
						{
							$tabla_c = "tipos_departamentos";
						}
						if($atributos['Field'][$l]=="pai_codigo")
						{
							$tabla_c = "tipos_paises";
						}
						if($atributos['Field'][$l]=="tp_codigo")
						{
							$tabla_c = "tipos_productos";
						}
						if($atributos['Field'][$l]=="to_codigo")
						{
							$tabla_c = "tipos_operacion";
						}
						if($atributos['Field'][$l]=="ts_codigo")
						{
							$tabla_c = "tipos_servicios";
						}
						if($atributos['Field'][$l]=="estr_codigo")
						{
							$tabla_c = "tipos_estratos";
						}
						if($atributos['Field'][$l]=="ser_codigo")
						{
							$tabla_c = "servicios";
						}
						if($atributos['Field'][$l]=="tep_codigo")
						{
							$tabla_c = "tipos_estados_procesos";
						}
						if($atributos['Field'][$l]=="pro_codigo")
						{
							$tabla_c = "productos";
						}
						if($atributos['Field'][$l]=="prod_codigo")
						{
							$tabla_c = "productos";
						}
						?> <script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'<?php echo $tabla_c?>', TABLA_ACTUALIZAR:'0', VALOR_REGISTRO:'<?php echo $registros[$atributos['Field'][$l]][$m]?>', CODIGO_REGISTRO:'', NOMBRE_CAMPO:'nombre', DIV:'<?php echo $atributos['Field'][$l];?>Div', NOMBRE_SELECT:'<?php echo $atributos['Field'][$l];?>', ACCION:'consultar_campo'}, '<?php echo $atributos['Field'][$l];?>Div' );</script>
								<div id="<?php echo $atributos['Field'][$l];?>Div"></div>
						<?php
					}
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