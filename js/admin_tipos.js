function administrarIC()
{
	var sadministarIC = document.getElementById('sadministarIC').value;
	if (sadministarIC!="0")
	{
		AjaxConsulta('../logica/admin_tipos.php', {TABLA:sadministarIC, ACCION:'sadministarIC'}, 'administarICDiv');		
	}
	else
	{
		alert("Por favor seleccione la tabla maestra que desea administrar");
	}	
}