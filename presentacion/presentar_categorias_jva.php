<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Octubre de 2012
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
<ul id="navmenu-op">	
<?php
for($i=0;$i<$RowCount;$i++)
{
?>
	<li><a href="#" onclick="$('#txt_buscar_productos').attr('value','');AjaxConsulta( '../logica/presentar_productos_jva.php', {CAT_CODIGO:<?php echo $resultados['codigo'][$i]?>, ACCION:'buscar_productos_categoria'}, 'resultado_busqueda_productos' );">
			<?php echo $resultados['nombre'][$i]?>
		</a>
	</li>	
<?php 
}
?>
</ul>