function VerAuditoria()
{
	var saVerAuditoria = document.getElementById('saVerAuditoria').value;
	if (VerAuditoria!="0")
	{
		AjaxConsulta('../logica/admin_la.php', {TABLA:saVerAuditoria, ACCION:'consultar'}, 'consultarLADiv');		
	}
	else
	{
		alert("Por favor seleccione la tabla maestra que desea consultar");
	}	
}
function consultar_atributos_tabla()
{	
	var tabla 		= document.getElementById('stabla').value;
	
	if(tabla != 0 )
	{
		if (tabla.length > 0)
		{
			AjaxConsulta('../logica/proc_atributos_tabla.php', {TABLA_CONSULTAR:tabla, DIV:'atributos', NOMBRE_SELECT:'satributos', ESTADO:'0', ACCION:'consultar_atributos'},'atributos');
		}	
		else
		{
			alert("Seleccione la tabla para realizar la consulta");
		}
	}
	else
	{
		alert("Seleccione la tabla para mostrar las opciones de busqueda avanzada");
	}
	
}
function consulta_like_atributos()
{
	var seleccion 	= document.getElementById('logs_auditorias').value;
	
	if(seleccion == "auditorias")
	{
		var tabla		= document.getElementById('stabla').value;
		
		if(tabla != 0 )
		{	
			var atributos 	= document.getElementById('satributos').value;
			
			if(atributos != 0)
			{
				var parametros 	= document.getElementById('parametros').value;
					
						AjaxConsulta('../logica/admin_la.php', {TABLA:tabla, ATRIBUTO:atributos, PARAMETRO:parametros, ACCION:'consultar'}, 'consultarLADiv' );	
			}
			else
			{
				var parametros 	= document.getElementById('parametros').value;
				
				AjaxConsulta('../logica/admin_la.php', {TABLA:tabla, ATRIBUTO:atributos, PARAMETRO:'', ACCION:'consultar'}, 'consultarLADiv' );
			}
		}
		else
		{
			alert("Seleccione una tabla para hacer la busqueda");
		}
	}
	else if(seleccion == "logs")
	{
		var tabla		= document.getElementById('stabla').value;
		
		if(tabla != 0 )
		{
			var atributos 	= document.getElementById('satributos').value;
			
			if(atributos != 0)
			{
				var parametros 	= document.getElementById('parametros').value;
				
						AjaxConsulta('../logica/admin_la.php', {TABLA:tabla, ATRIBUTO:atributos, PARAMETRO:parametros, ACCION:'consultar_logs'}, 'consultarLADiv' );
			}
			else
			{
				alert("Seleccione una campo para hacer la busqueda");
			}
		}
		else
		{
			alert("Seleccione una tabla para hacer la busqueda");
		}
	}
	else
	{
		alert("Seleccione logs o auditorias para hacer la busqueda");
	}

}
function mostrar(valor)
{
	if(valor === "logs")
	{
		AjaxConsulta( '../logica/proc_mostrar_tablas.php', {BASE_DE_DATOS:'argos_bd2plataforma', DIV:'tablas_bd', NOMBRE_SELECT:'stabla', ESTADO:'', ACCION:'consultar_tablas_logs'}, 'tablas_bd');
		//AjaxConsulta( '../logica/admin_la.php', '', 'atributos');
	}
	else if(valor === "auditorias")
	{
		AjaxConsulta( '../logica/proc_mostrar_tablas.php', {BASE_DE_DATOS:'au_argos_bd2plataforma', DIV:'tablas_bd', NOMBRE_SELECT:'stabla', ESTADO:'', ACCION:'consultar_tablas'}, 'tablas_bd');
		//AjaxConsulta( '../logica/admin_la.php', '', 'atributos');
	}
	else
	{
		alert("Seleccione la accion que desee realizar!");
	}
}




