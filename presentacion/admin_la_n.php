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
for($l=0;$l<$i;$l++)
{
	if(($atributos['Field'][$l]!="codigo")&&($atributos['Field'][$l]!="est_codigo"))
	{
		$parametros .=$atributos['Field'][$l].":$('#".$atributos['Field'][$l]."').val(), ";
	} 
}
?>
<script type='text/javascript' src='../js/admin_ic.js'></script>
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
				onclick="AjaxConsulta( '../logica/admin_ic.php', {TABLA:'<?php echo $tabla?>', <?php echo $parametros?> ACCION:'guardar_nuevo_ic'}, 'administarICDiv' );"/>
			<img src="imagenes/iconos/cancel_a.png" alt="Cancelar" title="Cancelar"
				style="cursor: pointer; border: 1px solid #CCC;"
				onmouseover="this.src = 'imagenes/iconos/cancel.png'"
				onmouseout="this.src = 'imagenes/iconos/cancel_a.png'"
				onclick="AjaxConsulta('../logica/admin_ic.php', {TABLA:'<?php echo $tabla?>', ACCION:'sadministarIC'}, 'administarICDiv');"/>		
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
				if(($atributos['Field'][$l]!="est_codigo")&&($atributos['Field'][$l]!="dep_codigo")&&($atributos['Field'][$l]!="pai_codigo"))
				{?>
					<input type="text" id = "<?php echo $atributos['Field'][$l]?>" value="" class="<?php if($atributos['Field'][$l]=="codigo"){echo "campo_bloqueado";}else{ echo "campo";}?>" <?php if($atributos['Field'][$l]=="codigo"){echo 'readonly="readonly"';}?>>
				<?php 
				}
				else 
				{
					if($atributos['Field'][$l]=="est_codigo")
					{
					?>
						<script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'tipos_estados', TABLA_ACTUALIZAR:'0', VALOR_REGISTRO:'', CODIGO_REGISTRO:'', NOMBRE_CAMPO:'nombre', DIV:'estadoDiv', NOMBRE_SELECT:'<?php echo $atributos['Field'][$l];?>', ACCION:'consultar_campo_estado'}, 'estadoDiv' );</script><div id="estadoDiv"></div>
					<?php 
					}	
					else 
					{
						if($atributos['Field'][$l]=="dep_codigo")
						{
							?>
								<script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'departamentos', TABLA_ACTUALIZAR:'0', VALOR_REGISTRO:'<?php echo $registros[$atributos['Field'][$l]][$m]?>', CODIGO_REGISTRO:'', NOMBRE_CAMPO:'nombre', DIV:'departamentoDiv', NOMBRE_SELECT:'<?php echo $atributos['Field'][$l];?>', ACCION:'consultar_campo'}, 'departamentoDiv' );</script><div id="departamentoDiv"></div>
							<?php 
						}	
						else 
						{
							if($atributos['Field'][$l]=="pai_codigo")
							{
								?>
									<script>AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'paises', TABLA_ACTUALIZAR:'0', VALOR_REGISTRO:'<?php echo $registros[$atributos['Field'][$l]][$m]?>', CODIGO_REGISTRO:'', NOMBRE_CAMPO:'nombre', DIV:'paisDiv', NOMBRE_SELECT:'<?php echo $atributos['Field'][$l];?>', ACCION:'consultar_campo'}, 'paisDiv' );</script><div id="paisDiv"></div>
								<?php 
							}
						}
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