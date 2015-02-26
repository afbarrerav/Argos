/*
 * @author:	MIGUEL ANGEL POSADA
 * 			miguelrodriguezpo@hotmail.com	
 * @version:2.0.0
 * @fecha:	Enero de 2013
 * 
 * */
function Buscar()
{	
	var campo		= document.getElementById('satributos').value;
	var valor		= document.getElementById('parametro').value;

	if(campo.length > 0)
	{
		AjaxConsulta('../logica/admin_ventas.php', {CAMPO:campo, VALOR:valor, ACCION:'consulta_like'}, 'listar_ventas_Div' );	
	}
	else
	{
		if(confirm("Desea consultar TODAS las ventas?"))
		{
			AjaxConsulta( '../logica/admin_ventas.php', {ACCION:'listar'}, 'listar_ventas_Div');
		}
	}
}

function consulta_like_atributos()
{
	var campo		= document.getElementById('satributos').value;
	var valor		= document.getElementById('parametro').value;
	if(campo.length > 0)
	{
		AjaxConsulta('../logica/admin_ventas.php', {CAMPO:campo, VALOR:valor, ACCION:'consulta_like'}, 'listar_ventas_Div' );	
	}
	else
	{
		alert("Seleccione un campo y un digite un valor para buscar la venta.");
	}
}