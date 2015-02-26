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
<script type='text/javascript' src='../js/admin_la.js'></script>
<br>
<table border="0" width="100%" align="center" style="background: #FFF; border: 1px solid #CCC;">
	<tr>
		<td class="titulo_tabla" height= "20" colspan ="<?php echo $i+1;?>">Logs y Auditorias</td>
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
					<?php if($atributos[$l]=="est_codigo")
					{
						if($registros[$atributos[$l]][$m]==1)
						{
							echo 'Activo';	
						}
						else
						{
							echo 'Inactivo';							
						}
					}
					else 
					{
						echo $registros[$atributos[$l]][$m];
					} 
					?>
					</td>
					<?php 
					}
					?>
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