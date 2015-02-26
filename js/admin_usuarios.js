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
		AjaxConsulta('../logica/admin_usuarios.php', {est_codigo:estado, usu_codigo:usuario, usu_username:username, ACCION:'actualizar_estado'}, 'estado_'+usuario );
	}
}
function actualizar_password(usuario, username)
{
	if(confirm("Haga clic en ACEPTAR si desea reiniciar la clave del usuario"))
	{
		AjaxConsulta('../logica/admin_usuarios.php', {usu_codigo:usuario, usu_username:username, ACCION:'actualizar_password'}, 'pass');
	}
}

function validar_username(username)
{
	AjaxConsulta_1('../logica/proc_username.php', {USERNAME:username, ACCION:'consultar'}, 'validar_username');
}

function guardar(form)
{
	// OBTIENE LA INFORMACION INGRESADA PARA EL NUEVO USUARIO
	var sti			 	= document.getElementById('sti').value;
	var identificacion 	= document.getElementById('identificacion').value;
	var nombres 		= document.getElementById('nombres').value;
	var apellidos		= document.getElementById('apellidos').value;
	var fecha_nacimiento= document.getElementById('fecha_nacimiento').value;
	var sgen		 	= document.getElementById('sgen').value;
	var sciu		 	= document.getElementById('sciu').value;
	var direccion 		= document.getElementById('direccion').value;
	var telefono 		= document.getElementById('telefono').value;
	var mail 			= document.getElementById('mail').value;
	var username 		= document.getElementById('username').value;
	var username_valido	= document.getElementById('username_valido').value;
	var srol 			= document.getElementById('srol').value;
	var sjva		 	= document.getElementById('sjva').value;
	var participacion	= document.getElementById('participacion').value;
	// VERIFICA QUE SE HAYA SELECCIONADO EL TIPO DE IDENTIFICACION
	if(sti > 0)
	{			
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
							// VERIFICA QUE SE HAYA SELECCIONADO UN COORDINADOR SI EL ROL ES 1						
							if(sjva > 0)
							{
								if(srol > 0)
								{
									if(confirm("Haga clic en ACEPTAR para crear el usuario ingresado"))
									{
										AjaxConsulta('../logica/admin_usuarios.php', {sti:sti, identificacion:identificacion, nombres:nombres, apellidos:apellidos, fecha_nacimiento:fecha_nacimiento, sgen:sgen, sciu:sciu, direccion:direccion, telefono:telefono, mail:mail, username:username, sjva:sjva,  srol:srol, participacion:participacion, ACCION:'crear_usuario'}, 'area_trabajo');
									}
								}
								else
								{
									alert("Debe seleccionar el Rol que desea asignar al usuario");
								}
							}
							else
							{
								alert("Debe seleccionar el JVA al cual estara asociado el usuario");
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
	else
	{
		alert("Debe seleccionar el tipo de identificacion");
	}
}