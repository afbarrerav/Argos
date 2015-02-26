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
	var codigo		= document.getElementById('codigo').value;
	var titulo		= document.getElementById('titulo').value;
	var descripcion	= document.getElementById('descripcion').value;
	var desde		= document.getElementById('desde').value;
	var hasta		= document.getElementById('hasta').value;
	if (codigo.length>0)
	{
		accion = "actualizar";
	}
	else
	{
		accion = "guardar";
	}
	/*
	 * VALIDA SI SE INGRESO UN TITULO PARA LA NOTA DEL DIA
	 * */
	if(titulo.length > 0)
	{
		/*VALIDA SI SE INGRESO DESCRIPCION PARA LA NOTA DEL DIA*/
		if(descripcion.length > 0)
		{
			/*VALIDAMOS QUE SE HAYA INGRESADO FECHA DE INICIO*/
			if(desde.length > 0)
			{
				/*VALIDAMOS QUE SI SE INGRESO FECHA DE FIN ESTA SEA MAYOR A LA FECHA DE INICIO*/
				if((hasta.length >0)&&(hasta > desde))
				{
							AjaxConsulta('../logica/notas_dia_admin.php', {codigo:codigo, titulo:titulo, descripcion:descripcion, desde:desde, hasta:hasta, ACCION:accion}, 'area_trabajo' );
				}
				else
				{
					alert('Debe introducir una fecha de fin mayor a la fecha de inicio de la publicacion');
				}	
			}
			else
			{
				alert("Debe introducir una fecha de inicio para la publicacion");
			}
		}
		else
		{
			alert('Debe introducir un valor para el campo descripcion');
		}	
	}
	else
	{
		alert('Debe introducir un valor para el campo titulo');
	}
}