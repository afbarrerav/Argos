/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 * 
 * */
function actualizar_estado(estado, usuario, username)
{
	if(confirm("Haga clic en ACEPTAR si desea cambiar el estado del usuario"))
	{
		AjaxConsulta('../logica/usuarios_admin.php', {est_codigo:estado, usu_codigo:usuario, usu_username:username, ACCION:'actualizar_estado'}, 'estado_'+usuario );
	}
}
function actualizar_password(usuario, username)
{
	if(confirm("Haga clic en ACEPTAR si desea reiniciar la clave del usuario"))
	{
		AjaxConsulta('../logica/usuarios_admin.php', {usu_codigo:usuario, usu_username:username, ACCION:'actualizar_password'}, 'pass');
	}
}
function actualizar_coordinador(coordinador, usuario)
{
	if(confirm("Haga clic en ACEPTAR si desea cambiar el coordinador asignado al usuario"))
	{
		AjaxConsulta('../logica/usuarios_admin.php', {coo_codigo:coordinador, usu_codigo:usuario, ACCION:'actualizar_coordinador'}, 'coordinador_'+usuario );
	}
}
function actualizar_rol(rol, usuario, username)
{
	if(confirm("Haga clic en ACEPTAR si desea cambiar el rol del usuario"))
	{
		AjaxConsulta('../logica/usuarios_admin.php', {rol_codigo:rol, usu_codigo:usuario, usu_username:username, ACCION:'actualizar_rol'}, 'rol_'+usuario );
		if(rol == 1)
		{
			AjaxConsulta( '../logica/proc_coordinadores.php', {ACCION:'listar', COORDINADOR:'', USUARIO:usuario}, 'coordinador_'+usuario );
		}
		else
		{
			$("#coordinador_"+usuario).html("- N/A -");
		}
	}
}
function validar_username(username)
{
	AjaxConsulta_1('../logica/proc_username.php', {USERNAME:username, ACCION:'consultar'}, 'validar_username');
}

function guardar(form)
{
	// OBTIENE LA INFORMACION INGRESADA PARA EL NUEVO USUARIO
	var identificacion 	= document.getElementById('identificacion').value;
	var nombres 		= document.getElementById('nombres').value;
	var apellidos		= document.getElementById('apellidos').value;
	var mail 			= document.getElementById('mail').value;
	var direccion 		= document.getElementById('direccion').value;
	var telefono 		= document.getElementById('telefono').value;
	var username 		= document.getElementById('username').value;
	var username_valido	= document.getElementById('username_valido').value;
	var srol 			= document.getElementById('srol').value;
	var scoordinador 	= document.getElementById('scoordinador').value;
	var extension 		= document.getElementById('extension').value;
	// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO IDENTIFICACION
	if((identificacion.length > 0) && (!isNaN(identificacion)))
	{
		// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO NOMBRES
		if(nombres.length > 0)
		{
			// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO APELLIDOS
			if(apellidos.length > 0)
			{
				// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO E_MAIL
				if(mail.length > 0)
				{
					// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO USERNAME
					if((username.length > 0) && (username_valido=="SI"))
					{
						
						// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO EXTENSION
						if((extension.length > 0) && (!isNaN(extension)))
						{
							// VERIFICA QUE SE HAYA SELECCIONADO UN COORDINADOR SI EL ROL ES 1						
							if(srol == 1)
							{
								if(scoordinador == 0)
								{
									alert("Debe seleccionar un coordinador para este usuario en el campo coordinador");
								}
								else
								{
									if(confirm("Haga clic en ACEPTAR para crear el usuario ingresado"))
									{
										AjaxConsulta('../logica/usuarios_admin.php', {identificacion:identificacion, nombres:nombres, apellidos:apellidos, mail:mail, direccion:direccion, telefono:telefono, username:username, srol:srol, scoordinador:scoordinador, extension:extension, ACCION:'crear_usuario'}, 'area_trabajo');
									}
								}
							}
							else
							{
								if(confirm("Haga clic en ACEPTAR para crear el usuario ingresado"))
								{
									AjaxConsulta('../logica/usuarios_admin.php', {identificacion:identificacion, nombres:nombres, apellidos:apellidos, mail:mail, direccion:direccion, telefono:telefono, username:username, srol:srol, scoordinador:scoordinador, extension:extension, ACCION:'crear_usuario'}, 'area_trabajo');
								}
							}
						}
						else
						{
							alert("Debe ingresar la extension del usuario en el campo extension");
						}
					}
					else
					{
						alert("Debe ingresar un username valido en el campo username");
					}
				}
				else
				{
					alert("Debe ingresar el mail del usuario en el campo e-mail");
				}
			}
			else
			{
				alert("Debe ingresar los apellidos del usuario en el campo apellidos");
			}
		}
		else
		{
			alert("Debe ingresar los nombres del usuario en el campo nombres");
		}
	}
	else
	{
		alert("Debe ingresar el numero de cedula del usuario sin puntos o comas en el campo identificacion");
	}
}