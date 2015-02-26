/* * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com	
 * @version:2.0.0
 * @fecha:	Enero de 2013
 * 
 * */
function Guardar(form)
{
	var jva_codigo 	= $('#sjva').val();
	var ptij_codigo	= $('#sptij').val();
	var aju_codigo_d= $('#sDesdeUsuario').val();
	var aju_codigo_h= $('#sHastaUsuario').val();
	var fecha_traslado = document.getElementById('fecha_traslado').value;
	var cantidad 	= document.getElementById('cantidad').value;
	//VALIDAMOS QUE EL TIPO DE REPORTE SEA SELECCIONADO
	if (ptij_codigo>0)
	{
		//VALIDAMOS LA SELECCION DEL USUARIO DESDE
		if (aju_codigo_d>0)
		{
			//VALIDAMOS LA SELECCION DEL USUARIO HASTA
			if (aju_codigo_h>0)
			{
				//VALIDAMOS LA SELECCION DEL USUARIO HASTA
				if (fecha_traslado.length>0)
				{
					//VALIDAMOS LA SELECCION DEL USUARIO HASTA
					if ((cantidad.length > 0) && (!isNaN(cantidad)))
					{
						//VALIDAMOS QUE EL USUARIO DESDE Y HASTA SEAN DIFERENTES
						if (aju_codigo_d != aju_codigo_h)
						{
							AjaxConsulta('../logica/admin_traslados.php', {JVA_CODIGO:jva_codigo, PTIJ_CODIGO:ptij_codigo, AJU_CODIGO_DESDE:aju_codigo_d, AJU_CODIGO_HASTA:aju_codigo_h, FECHA_TRASLADO:fecha_traslado, CANTIDAD:cantidad, ACCION:'crear_traslado'}, 'listar_traslados');
						}
						else
						{
							alert('No puede seleccionar el usuario desde igual que hasta!');
						}
					}
					else
					{
						alert('Digite una Cantidad numerica en el campo cantidad.');
					}
				}
				else
				{
					alert('Seleccione la fecha del traslado');
				}
			}
			else
			{
				alert('Seleccione un usuario Hasta.');
			}
		}
		else
		{
			alert('Seleccione un usuario Desde.');
		}
	}
	else
	{
		alert('Seleccione un tipo de traslado');
	}
}
function listar_traslados()
{
	var jva_codigo = $('#sjva').val();
	if (jva_codigo>0)
	{
		var usu_codigo = $('svededoresjva').val;
	}
	var inicio 	= document.getElementById('fecha_inicio').value;
	var fin 	= document.getElementById('fecha_fin').value;
	if(jva_codigo>0)
	{
		AjaxConsulta('../logica/admin_traslados.php', {JVA_CODIGO:jva_codigo, ACCION:'listar_traslados_d'}, 'listar_traslados');
	}
	else
	{
		AjaxConsulta('../logica/admin_traslados.php', {JVA_CODIGO:jva_codigo, ACCION:'listar_traslados_d'}, 'listar_traslados');
	}
}