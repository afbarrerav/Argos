/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com	
 * @version:2.0.0
 * @fecha:	Enero de 2013
 * 
 * */
function editar_ruta(form)
{	
	var codigo		= document.getElementById('codigo_ruta').value;
	var nombre		= document.getElementById('nombre').value;
	var aju_codigo	= $('#sVendedores').val();
	if(nombre.length > 0)
	{
		if (aju_codigo > 0)
		{
			AjaxConsulta( '../logica/admin_rutas.php', {CODIGO:codigo, NOMBRE:nombre, AJU_CODIGO:aju_codigo, ACCION:'editar_ruta'}, 'Param_rutasDiv');
		}	
		else
		{
			alert("Seleccione un vededor para la ruta");
		}
	}
	else
	{
		alert('Debe dale un nombre a la ruta');
	}	
}
function guardar(form)
{	
	var nombre		= document.getElementById('nombre').value;
	var descripcion	= document.getElementById('descripcion').value;
	var jva_codigo	= $('#sjva').val();
	if(nombre.length > 0)
	{
		if (jva_codigo > 0)
		{
			var usu_codigo = $('#svededoresjva').val();
			if (usu_codigo > 0)
			{
				AjaxConsulta( '../logica/admin_rutas.php', {NOMBRE:nombre, DESCRIPCION:descripcion, JVA_CODIGO:jva_codigo, USU_CODIGO:usu_codigo, ACCION:'crear_ruta'}, 'area_trabajo');
			}
			else
			{
				alert("Seleccione un vededor para la ruta");
			}
		}
		else
		{
			alert("Seleccione un JVA y un vendedor para crear la ruta.");
		}
	}
	else
	{
		alert('Debe dale un nombre a la ruta');
	}	
}
function mover(form)
{	
	var trd_codigo	= document.getElementById('trd_codigo').value;
	var tv_codigo	= document.getElementById('tv_codigo').value;
	var cli_codigo	= document.getElementById('cli_codigo').value;
	var arg_codigo	= document.getElementById('arg_codigo').value;
	var cliente		= document.getElementById('cliente').value;
	var srutas		= $('#srutas').val();
	if (srutas > 0)
	{
		AjaxConsulta( '../logica/admin_rutas.php', {TRD_CODIGO:trd_codigo, TV_CODIGO:tv_codigo, CLI_CODIGO:cli_codigo, CLIENTE:cliente, ARG_CODIGO:arg_codigo, TRJ_CODIGO:srutas, ACCION:'cambiar_ruta'}, 'Param_rutasDiv');
	}
	else
	{
		alert("Seleccione la ruta a donde va a enviar el producto el Cliente.");
	}	
}