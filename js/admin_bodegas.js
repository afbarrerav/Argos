/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 * 
 * */

function guardar(form)
{
	// OBTIENE LA INFORMACION INGRESADA PARA LA NUEVA BODEGA
	var descripcion		= document.getElementById('descripcion').value;
	var saju		 	= document.getElementById('saju').value;

	// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO NOMBRES
	if(descripcion.length > 0)
	{
		// VERIFICA QUE SE HAYA SELECCIONADO UNA CIUDAD
		if(saju > 0)
		{
			if(confirm("Haga clic en ACEPTAR para crear el JVA ingresado"))
			{
				AjaxConsulta('../logica/admin_bodegas.php', {descripcion:descripcion, saju:saju, ACCION:'crear'}, 'area_trabajo');
			}
		}
		else
		{
			alert("Debe seleccionar el usuario al cual sera asignada la bodega");
		}

	}
	else
	{
		alert("Debe ingresar la descripcion de la bodega");
	}
}