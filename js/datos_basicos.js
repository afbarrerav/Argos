/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 * 
 * */
function guardar(form)
{
	//Obtiene las variables que deben ser enviadas
	var nom_usuario		= document.getElementById('nom_usuario').value;
	var ape1_usuario	= document.getElementById('ape1_usuario').value;
	var mail			= document.getElementById('mail').value;
	var direccion		= document.getElementById('direccion').value;
	var telefono		= document.getElementById('telefono').value;
	var clave_actual	= document.getElementById('clave_actual').value;
	var clave_nueva		= document.getElementById('clave_nueva').value;
	var clave_nueva_r	= document.getElementById('clave_nueva_r').value;
	/*
	 * SE VALIDA SI EL USUARIO DESEA CAMBIAR SU CLAVE DE ACCESO O SOLO SU INFORMACION PERSONAL
	 * */
	if(clave_actual.length > 0)
	{
		if(confirm("Haga clic en aceptar para cambiar la clave de acceso a su cuenta o haga clic en Cancelar para guardar los cambios sin cambiar la clave de acceso a su cuenta"))
		{
			if(clave_nueva == clave_nueva_r)
			{
				/*VALIDAMOS QUE EL CAMPO NOMBRE CONTENGA VALORES*/
				if(nom_usuario.length > 0)
				{
					/*VALIDAMOS QUE EL CAMPO PRIMER APELLIDO CONTENGA VALORES*/
					if(ape1_usuario.length > 0)
					{
						/*VALIDAMOS QUE EL CAMPO MAIL CONTENGA VALORES*/
						if(mail.length >0)
						{
							AjaxConsulta('../logica/datos_basicos.php', {nom_usuario:nom_usuario, ape1_usuario:ape1_usuario, mail:mail, direccion:direccion, telefono:telefono, clave_actual:clave_actual, clave_nueva:clave_nueva, ACCION:'cambiar_guardar'}, 'area_trabajo');
						}
						else
						{
							alert('Debe introducir un valor para el campo Pregunta Secreta');
						}					
					}
					else
					{
						alert('Debe introducir un valor para el campo Apellidos');
					}
				}
				else
				{
					alert('Debe introducir un valor para el campo Nombre');
				}	
			}
			else
			{
				alert("La nueva clave indicada no coincide, por favor ingresela nuevamente");
				document.getElementById('clave_nueva').value="";
				document.getElementById('clave_nueva_r').value="";
			}
		}
		else
		{
			/*VALIDAMOS QUE EL CAMPO NOMBRE CONTENGA VALORES*/
			if(nom_usuario.length > 0)
			{
				/*VALIDAMOS QUE EL CAMPO PRIMER APELLIDO CONTENGA VALORES*/
				if(ape1_usuario.length > 0)
				{
					/*VALIDAMOS QUE EL CAMPO MAIL CONTENGA VALORES*/
					if(mail.length >0)
					{
						AjaxConsulta('../logica/datos_basicos.php', {nom_usuario:nom_usuario, ape1_usuario:ape1_usuario, mail:mail, direccion:direccion, telefono:telefono, ACCION:'guardar'}, 'area_trabajo' );
					}
					else
					{
						alert('Debe introducir un valor para el campo Pregunta Secreta');
					}					
				}
				else
				{
					alert('Debe introducir un valor para el campo Apellidos');
				}
			}
			else
			{
				alert('Debe introducir un valor para el campo Nombre');
			}	
		}
	}
	else
	{
		/*VALIDAMOS QUE EL CAMPO NOMBRE CONTENGA VALORES*/
		if(nom_usuario.length > 0)
		{
			/*VALIDAMOS QUE EL CAMPO PRIMER APELLIDO CONTENGA VALORES*/
			if(ape1_usuario.length > 0)
			{
				/*VALIDAMOS QUE EL CAMPO MAIL CONTENGA VALORES*/
				if(mail.length >0)
				{
					AjaxConsulta('../logica/datos_basicos.php', {nom_usuario:nom_usuario, ape1_usuario:ape1_usuario, mail:mail, direccion:direccion, telefono:telefono, ACCION:'guardar'}, 'area_trabajo' );
				}
				else
				{
					alert('Debe introducir un valor para el campo Pregunta Secreta');
				}					
			}
			else
			{
				alert('Debe introducir un valor para el campo Apellidos');
			}
		}
		else
		{
			alert('Debe introducir un valor para el campo Nombre');
		}	
	}
}