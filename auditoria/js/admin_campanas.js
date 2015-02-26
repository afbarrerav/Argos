/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Marzo de 2011
 * 
 * */

// HACE LA VALIDACION DEL FORMULARIO PARA GUARDAR CAMPANA
function guardar_campana()
{
	var nombre		= document.getElementById('nombre').value;
	var cli_codigo	= document.getElementById('scliente').value;
	var tc_codigo	= document.getElementById('stipos_campanas').value;
	var fecha_inicio= document.getElementById('inicio').value;
	var fecha_fin	= document.getElementById('fin').value;
	
	if(confirm("Haga clic en ACEPTAR si desea crear la nueva campana"))
	{
		AjaxConsulta('../logica/admin_campanas.php', {nombre:nombre, cli_codigo:cli_codigo, tc_codigo:tc_codigo, fecha_inicio:fecha_inicio, fecha_fin:fecha_fin, ACCION:'crear_campana'}, 'area_trabajo' );
	}
}

function actualizar_campana(codigo)
{
	var nombre		= document.getElementById('nombre').value;
	var cli_codigo	= document.getElementById('scliente').value;
	var tc_codigo	= document.getElementById('stipos_campanas').value;
	var fecha_inicio= document.getElementById('inicio').value;
	var fecha_fin	= document.getElementById('fin').value;
	
	if(confirm("Haga clic en ACEPTAR si desea actualizar la campana"))
	{
		AjaxConsulta('../logica/admin_campanas.php', {codigo:codigo, nombre:nombre, cli_codigo:cli_codigo, tc_codigo:tc_codigo, fecha_inicio:fecha_inicio, fecha_fin:fecha_fin, ACCION:'actualizar_campana'}, 'area_trabajo' );
	}
}