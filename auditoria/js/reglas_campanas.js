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
	var agente 		= document.getElementById('sUsuario').value;
	var campana 	= document.getElementById('sCampana').value;
	var campo		= document.getElementById('sCampo').value;
	var operador	= document.getElementById('sOperador').value;
	var valor		= document.getElementById('valor').value;
	
	// VALIDA QUE SE HAYA SELECCIONADO LA CAMPANA
	if(campana > 0)
	{
		// VALIDA QUE SE HAYA SELECCIONADO EL CAMPO
		if(campo != 0)
		{
			// VALIDA QUE SE HAYA SELECCIONADO EL OPERADOR
			if (operador > 0)
			{
				// VALIDA QUE SE HAYA INGRESADO EL VALOR
				if(valor.length > 0)
				{
					// VALIDA QUE SE HAYA SELECCIONADO EL AGENTE
					if(agente>0)
					{
						if (confirm("Haga clic en aceptar para aplicar la regla al agente seleccionado"))
						{
							AjaxConsulta('../logica/reglas_campanas.php', {agente:agente, campana:campana, campo:campo, operador:operador, valor:valor, ACCION:'guardar'}, 'area_trabajo');
						}
					}	
					else
					{
						if (confirm("Haga clic en aceptar para aplicar la regla a todos los agentes que tiene asociados"))
						{
							AjaxConsulta('../logica/reglas_campanas.php', {agente:agente, campana:campana, campo:campo, operador:operador, valor:valor, ACCION:'guardar'}, 'area_trabajo');
						}
					}	
				}
				else
				{
					alert('Debe ingresar una valor para la regla');
				}	
			}
			else
			{
				alert('Debe indicar el operador que utilizara en la regla');
			}	
		}
		else
		{
			alert('Debe seleccionar el campo para el cual desea establecer la regla');
		}	
	}
	else
	{
		alert('Debe seleccionar la campana para la cual desea establecer la regla');
	}	
}