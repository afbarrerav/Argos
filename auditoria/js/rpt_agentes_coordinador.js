/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 * 
 * */

function generar_reporte()
{
	var agente 			= document.getElementById('agente').value;
	var identificacion 	= document.getElementById('identificacion').value;
	var extension	 	= document.getElementById('extension').value;
	var desde 			= document.getElementById('desde').value;
	var hasta 			= document.getElementById('hasta').value;
	if(desde.length > 0)
	{
		/*VALIDAMOS QUE SI SE INGRESO FECHA DE FIN ESTA SEA MAYOR A LA FECHA DE INICIO*/
		if(((desde.length >0)&&(hasta >= desde)))
		{
			AjaxConsulta('../logica/rpt_agentes_coordinador.php', {NOMBRES:agente, IDENTIFICACION:identificacion, EXTENSION:extension, desde:desde, hasta:hasta, ACCION:'generar_reporte_consolidado'}, 'area_trabajo' );
		}
		else
		{
			alert('La fecha de fin debe ser mayor a la fecha de inicio');
		}
	}
	else
	{
		alert('Debe introducir la fecha de inicio');
	}	
}

function det_cant_llamadas()
{
	var agente 			= document.getElementById('agente').value;
	var identificacion 	= document.getElementById('identificacion').value;
	var extension	 	= document.getElementById('extension').value;
	var desde 			= document.getElementById('desde').value;
	var hasta 			= document.getElementById('hasta').value;
	if(desde.length > 0)
	{
		/*VALIDAMOS QUE SI SE INGRESO FECHA DE FIN ESTA SEA MAYOR A LA FECHA DE INICIO*/
		if(((desde.length >0)&&(hasta >= desde)))
		{
			AjaxConsulta('../logica/rpt_agentes_coordinador.php', {NOMBRES:agente, IDENTIFICACION:identificacion, EXTENSION:extension, desde:desde, hasta:hasta, ACCION:'det_cant_llamadas'}, 'detalle_reporte' );
		}
		else
		{
			alert('La fecha de fin debe ser mayor a la fecha de inicio');
		}
	}
	else
	{
		alert('Debe introducir la fecha de inicio');
	}	
}

function det_cant_ce()
{
	var agente 			= document.getElementById('agente').value;
	var identificacion 	= document.getElementById('identificacion').value;
	var extension	 	= document.getElementById('extension').value;
	var desde 			= document.getElementById('desde').value;
	var hasta 			= document.getElementById('hasta').value;
	if(desde.length > 0)
	{
		/*VALIDAMOS QUE SI SE INGRESO FECHA DE FIN ESTA SEA MAYOR A LA FECHA DE INICIO*/
		if(((desde.length >0)&&(hasta >= desde)))
		{
			AjaxConsulta('../logica/rpt_agentes_coordinador.php', {NOMBRES:agente, IDENTIFICACION:identificacion, EXTENSION:extension, desde:desde, hasta:hasta, ACCION:'det_cant_ce'}, 'detalle_reporte' );
		}
		else
		{
			alert('La fecha de fin debe ser mayor a la fecha de inicio');
		}
	}
	else
	{
		alert('Debe introducir la fecha de inicio');
	}	
}

function det_cant_ea()
{
	var agente 			= document.getElementById('agente').value;
	var identificacion 	= document.getElementById('identificacion').value;
	var extension	 	= document.getElementById('extension').value;
	var desde 			= document.getElementById('desde').value;
	var hasta 			= document.getElementById('hasta').value;
	if(desde.length > 0)
	{
		/*VALIDAMOS QUE SI SE INGRESO FECHA DE FIN ESTA SEA MAYOR A LA FECHA DE INICIO*/
		if(((desde.length >0)&&(hasta >= desde)))
		{
			AjaxConsulta('../logica/rpt_agentes_coordinador.php', {NOMBRES:agente, IDENTIFICACION:identificacion, EXTENSION:extension, desde:desde, hasta:hasta, ACCION:'det_cant_ea'}, 'detalle_reporte');
		}
		else
		{
			alert('La fecha de fin debe ser mayor a la fecha de inicio');
		}
	}
	else
	{
		alert('Debe introducir la fecha de inicio');
	}	
}

function det_cant_ca()
{
	var agente 			= document.getElementById('agente').value;
	var identificacion 	= document.getElementById('identificacion').value;
	var extension	 	= document.getElementById('extension').value;
	var desde 			= document.getElementById('desde').value;
	var hasta 			= document.getElementById('hasta').value;
	if(desde.length > 0)
	{
		/*VALIDAMOS QUE SI SE INGRESO FECHA DE FIN ESTA SEA MAYOR A LA FECHA DE INICIO*/
		if(((desde.length >0)&&(hasta >= desde)))
		{
			AjaxConsulta('../logica/rpt_agentes_coordinador.php', {NOMBRES:agente, IDENTIFICACION:identificacion, EXTENSION:extension, desde:desde, hasta:hasta, ACCION:'det_cant_ce'}, 'detalle_reporte');
		}
		else
		{
			alert('La fecha de fin debe ser mayor a la fecha de inicio');
		}
	}
	else
	{
		alert('Debe introducir la fecha de inicio');
	}	
}

function det_cant_ed()
{
	var agente 			= document.getElementById('agente').value;
	var identificacion 	= document.getElementById('identificacion').value;
	var extension	 	= document.getElementById('extension').value;
	var desde 			= document.getElementById('desde').value;
	var hasta 			= document.getElementById('hasta').value;
	if(desde.length > 0)
	{
		/*VALIDAMOS QUE SI SE INGRESO FECHA DE FIN ESTA SEA MAYOR A LA FECHA DE INICIO*/
		if(((desde.length >0)&&(hasta >= desde)))
		{
			AjaxConsulta('../logica/rpt_agentes_coordinador.php', {NOMBRES:agente, IDENTIFICACION:identificacion, EXTENSION:extension, desde:desde, hasta:hasta, ACCION:'det_cant_ed'}, 'detalle_reporte');
		}
		else
		{
			alert('La fecha de fin debe ser mayor a la fecha de inicio');
		}
	}
	else
	{
		alert('Debe introducir la fecha de inicio');
	}	
}

function det_cant_cd()
{
	var agente 			= document.getElementById('agente').value;
	var identificacion 	= document.getElementById('identificacion').value;
	var extension	 	= document.getElementById('extension').value;
	var desde 			= document.getElementById('desde').value;
	var hasta 			= document.getElementById('hasta').value;
	if(desde.length > 0)
	{
		/*VALIDAMOS QUE SI SE INGRESO FECHA DE FIN ESTA SEA MAYOR A LA FECHA DE INICIO*/
		if(((desde.length >0)&&(hasta >= desde)))
		{
			AjaxConsulta('../logica/rpt_agentes_coordinador.php', {NOMBRES:agente, IDENTIFICACION:identificacion, EXTENSION:extension, desde:desde, hasta:hasta, ACCION:'det_cant_cd'}, 'detalle_reporte' );
		}
		else
		{
			alert('La fecha de fin debe ser mayor a la fecha de inicio');
		}
	}
	else
	{
		alert('Debe introducir la fecha de inicio');
	}	
}

function det_cant_cag()
{
	var agente 			= document.getElementById('agente').value;
	var identificacion 	= document.getElementById('identificacion').value;
	var extension	 	= document.getElementById('extension').value;
	var desde 			= document.getElementById('desde').value;
	var hasta 			= document.getElementById('hasta').value;
	if(desde.length > 0)
	{
		/*VALIDAMOS QUE SI SE INGRESO FECHA DE FIN ESTA SEA MAYOR A LA FECHA DE INICIO*/
		if(((desde.length >0)&&(hasta >= desde)))
		{
			AjaxConsulta('../logica/rpt_agentes_coordinador.php', {NOMBRES:agente, IDENTIFICACION:identificacion, EXTENSION:extension, desde:desde, hasta:hasta, ACCION:'det_cant_cag'}, 'detalle_reporte' );
		}
		else
		{
			alert('La fecha de fin debe ser mayor a la fecha de inicio');
		}
	}
	else
	{
		alert('Debe introducir la fecha de inicio');
	}	
}