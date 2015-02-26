/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 * 
 * */

function guardar(form)
{
	// OBTIENE LA INFORMACION INGRESADA PARA EL NUEVO USUARIO
	var nombre	 		= document.getElementById('nombre').value;
	var descripcion		= document.getElementById('descripcion').value;
	var sdis		 	= document.getElementById('sdis').value;
	var sciu		 	= document.getElementById('sciu').value;
	var direccion 		= document.getElementById('direccion').value;
	var fecha_creacion	= document.getElementById('fecha_creacion').value;

	// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO NOMBRES
	if(nombre.length > 0)
	{
		// VERIFICA QUE SE HAYA SELECCIONADO UNA CIUDAD
		if(sciu > 0)
		{
			if(confirm("Haga clic en ACEPTAR para crear el JVA ingresado"))
			{
				AjaxConsulta('../logica/admin_jva.php', {nombre:nombre, descripcion:descripcion, sdis:sdis, sciu:sciu, direccion:direccion, fecha_creacion:fecha_creacion, ACCION:'crear'}, 'area_trabajo');
			}
		}
		else
		{
			alert("Debe seleccionar la ciudad");
		}

	}
	else
	{
		alert("Debe ingresar los nombres del usuario en el campo nombres");
	}
}

function guardar_e(form)
{
	// OBTIENE LA INFORMACION INGRESADA PARA EL NUEVO USUARIO
	var codigo			= document.getElementById('codigo').value;
	var descripcion	 	= document.getElementById('descripcion').value;
	var nombre	 		= document.getElementById('nombre').value;
	var sdis		 	= document.getElementById('sdis').value;
	var sciu		 	= document.getElementById('sciu').value;
	var direccion 		= document.getElementById('direccion').value;
	var fecha_creacion	= document.getElementById('fecha_creacion').value;

	// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO NOMBRES
	if(nombre.length > 0)
	{
		// VERIFICA QUE SE HAYA SELECCIONADO UNA CIUDAD
		if(sciu > 0)
		{
			if(confirm("Haga clic en ACEPTAR para editar el JVA."))
			{
				AjaxConsulta('../logica/admin_jva.php', {codigo:codigo, nombre:nombre, descripcion:descripcion, sdis:sdis, sciu:sciu, direccion:direccion, fecha_creacion:fecha_creacion, ACCION:'guardar_editar'}, 'area_trabajo');
			}
		}
		else
		{
			alert("Debe seleccionar la ciudad");
		}

	}
	else
	{
		alert("Debe ingresar los nombres del usuario en el campo nombres");
	}
}