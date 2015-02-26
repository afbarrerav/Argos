function ConsultarInventario()
{
	var jva_codigo = document.getElementById('sjva').value;
	if (jva_codigo!="0")
	{
		var usu_codigo = document.getElementById('svededoresjva').value;
		if(usu_codigo!="0")
		{
			AjaxConsulta('../logica/admin_inventarios.php', {USU_CODIGO:usu_codigo, ACCION:'listar_inventario_usuario'}, 'inventarioDiv');
		}
		else
		{
			AjaxConsulta('../logica/admin_inventarios.php', {JVA_CODIGO:jva_codigo, ACCION:'listar_inventario_jva'}, 'inventarioDiv');
		}
	}
	else
	{
		if(confirm("Haga clic en ACEPTAR si desea consulta el inventario total"))
		{
			AjaxConsulta('../logica/admin_inventarios.php', {ACCION:'listar_inventario'}, 'inventarioDiv');
		}
	}	
}