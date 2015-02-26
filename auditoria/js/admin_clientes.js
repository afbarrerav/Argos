function guardar(form)
{
	// OBTIENE LA INFORMACION INGRESADA PARA EL NUEVO USUARIO
	var codigo 				= document.getElementById('codigo').value;
	var stipoIdentificacion = document.getElementById('stipoIdentificacion').value;
	var nroidentificacion	= document.getElementById('nroidentificacion').value;
	var razon_social 		= document.getElementById('razon_social').value;
	var telefono1 			= document.getElementById('telefono1').value;
	var ext1 				= document.getElementById('ext1').value;
	var telefono2 			= document.getElementById('telefono2').value;
	var ext2				= document.getElementById('ext2').value;
	var celular1 			= document.getElementById('celular1').value;
	var celular2 			= document.getElementById('celular2').value;
	var email 				= document.getElementById('email').value;
	var sdepartamento		= $('#sdepartamento').val();
	var sciudad 			= $('#sciudad').val();
	var direccion 			= document.getElementById('direccion').value;
	var barrio 				= document.getElementById('barrio').value;
	var comentario 			= document.getElementById('comentario').value;
	
	// VERIFICA QUE SE HAYA INGRESADO RAZON SOCIAL
	if(razon_social.length > 0)
	{
		// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO TELEFONO1
		if((telefono1.length > 0) && (!isNaN(telefono1)))
		{
			// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO E_MAIL
			if(email.length > 0)
			{
				// VERIFICA QUE SE HAYA SELECCIONADO UN DEPARTAMENTO
				if(sdepartamento > 0)
				{	// VERIFICA QUE SE HAYA SELECCIONADO UNA CIUDAD
					if(sciudad > 0)
					{	
						// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO DIRECCION
						if(direccion.length > 0)
						{
		
							// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO COMENTARIO
							if(comentario.length > 0)
							{
								if(confirm("Haga clic en ACEPTAR para actualizar el cliente"))
								{
									AjaxConsulta('../logica/admin_clientes.php', {codigo:codigo, stipoIdentificacion:stipoIdentificacion, nroidentificacion:nroidentificacion, razon_social:razon_social, telefono1:telefono1, ext1:ext1, telefono2:telefono2, ext2:ext2, celular1:celular1, celular2:celular2, email:email, sdepartamento:sdepartamento, sciudad:sciudad, direccion:direccion, barrio:barrio, comentario:comentario, ACCION:'actualizar'}, 'area_trabajo');
								}
							}
							else
							{
								alert("Debe ingresar el nombre del contacto para el cliente");
							}
						}
						else
						{
							alert("Debe ingresar la direccion del cliente");
						}
					}
					else
					{
						alert("Debe seleccionar la ciudad");
					}	
				}
				else
				{
					alert("Debe seleccionar el departamento");
				}	
			}
			else
			{
				alert("Debe ingresar el e-mail del cliente");
			}
		}
		else
		{
			alert("Debe ingresar un numero de telefono valido en el campo telefono 1");
		}
	}
	else
	{
		alert("Debe ingresar la razon social del cliente");
	}
}
function guardar2(form)
{
	// OBTIENE LA INFORMACION INGRESADA PARA EL NUEVO USUARIO
	var codigo 				= document.getElementById('codigo').value;
	var stipoIdentificacion = document.getElementById('stipoIdentificacion').value;
	var nroidentificacion	= document.getElementById('nroidentificacion').value;
	var razon_social 		= document.getElementById('razon_social').value;
	var telefono1 			= document.getElementById('telefono1').value;
	var ext1 				= document.getElementById('ext1').value;
	var telefono2 			= document.getElementById('telefono2').value;
	var ext2				= document.getElementById('ext2').value;
	var celular1 			= document.getElementById('celular1').value;
	var celular2 			= document.getElementById('celular2').value;
	var email 				= document.getElementById('email').value;
	var sdepartamento		= $('#sdepartamento').val();
	var sciudad 			= $('#sciudad').val();
	var direccion 			= document.getElementById('direccion').value;
	var barrio 				= document.getElementById('barrio').value;
	var comentario 			= document.getElementById('comentario').value;
	
	// VERIFICA QUE SE HAYA INGRESADO RAZON SOCIAL
	if(razon_social.length > 0)
	{
		// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO TELEFONO1
		if((telefono1.length > 0) && (!isNaN(telefono1)))
		{
			// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO E_MAIL
			if(email.length > 0)
			{
				// VERIFICA QUE SE HAYA SELECCIONADO UN DEPARTAMENTO
				if(sdepartamento > 0)
				{	// VERIFICA QUE SE HAYA SELECCIONADO UNA CIUDAD
					if(sciudad > 0)
					{	
						// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO DIRECCION
						if(direccion.length > 0)
						{
		
							// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO COMENTARIO
							if(comentario.length > 0)
							{
								if(confirm("Haga clic en ACEPTAR para crear el cliente"))
								{
									AjaxConsulta('../logica/admin_clientes.php', {codigo:codigo, stipoIdentificacion:stipoIdentificacion, nroidentificacion:nroidentificacion, razon_social:razon_social, telefono1:telefono1, ext1:ext1, telefono2:telefono2, ext2:ext2, celular1:celular1, celular2:celular2, email:email, sdepartamento:sdepartamento, sciudad:sciudad, direccion:direccion, barrio:barrio, comentario:comentario, ACCION:'guardar'}, 'area_trabajo');
								}
							}
							else
							{
								alert("Debe ingresar el nombre del contacto para el cliente");
							}
						}
						else
						{
							alert("Debe ingresar la direccion del cliente");
						}
					}
					else
					{
						alert("Debe seleccionar la ciudad");
					}	
				}
				else
				{
					alert("Debe seleccionar el departamento");
				}	
			}
			else
			{
				alert("Debe ingresar el e-mail del cliente");
			}
		}
		else
		{
			alert("Debe ingresar un numero de telefono valido en el campo telefono 1");
		}
	}
	else
	{
		alert("Debe ingresar la razon social del cliente");
	}
}