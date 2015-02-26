function buscar_deudor(like)
{
	var sdeudores = $('#sdeudores').val();
	if(sdeudores!="0")
	{
		AjaxConsulta('../logica/asignar_cliente.php',{sdeudores:sdeudores, like:like, ACCION:'buscar_deudor'},'resultado_deudores');
	}
	else
	{
		alert('Seleccione un criterio de busqueda!');
	}
}