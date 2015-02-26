/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com	
 * @version:2.0.0
 * @fecha:	Enero de 2013
 * 
 * */
function generar_mapa()
{
	var sVendedor		= document.getElementById('sVendedores').value;
	var fecha_ruta		= document.getElementById('fecha_ruta').value;
	var stipoMapa		= document.getElementById('stipoMapa').value;
	if (sVendedor > 0)
	{
		if(stipoMapa.length > 0)
		{
			if(fecha_ruta.length >0)
			{
				AjaxConsulta('../logica/dashboard_mapa.php', {VEN_CODIGO:sVendedor, TIPO:stipoMapa, FECHA:fecha_ruta, ACCION:'mostrar_ruta'}, 'rpt_mapa' );
			}
			else
			{
				alert("Debe seleccionar una fecha para mostrar la ruta");
			}
		}
		else
		{
			alert("Debe seleccionar un tipo de Gestion");
		}
	}
	else
	{
		alert("Debe seleccionar un Vendedor para mostrar la ruta");
	}
}