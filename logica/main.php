<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Junio de 2011
 * 
 * */
include_once ("../logica/variables_session.php");
?>
<div id="area_trabajo">
	<script>
		AjaxConsulta( '../presentacion/opciones.php', '', 'OpcionesUsuario' );
	</script>
	<?php 
	if($rol_codigo == 1)
	{
	?>
	<script>
		AjaxConsulta( '../logica/admin_usuarios.php', {ACCION:'listar'}, 'area_trabajo' );
	</script>
	<?php 
	} 
	if($rol_codigo == 2 || $rol_codigo == 3)
	{
	?>
	<script>
		AjaxConsulta( '../presentacion/dashboard_adm_pla.php', '', 'area_trabajo' );
	</script>
	<?php 
	}
	if($rol_codigo == 6)
	{
	?>
	<script>
		AjaxConsulta( '../presentacion/admin_transacciones_vendedor.php', '', 'area_trabajo' );
	</script>
	<?php 
	}
	?>
</div>