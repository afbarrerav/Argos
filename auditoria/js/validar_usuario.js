function EnterLogin()
{
	$(document).keydown(function(tecla){
		if (tecla.keyCode == 13) 
		{
			$("#txt_pass").focus();
		}
	});
}
function EnterPass()
{
	$(document).keydown(function(tecla){
		if (tecla.keyCode == 13) 
		{
			validausuario();
		}
	});
}	
function validausuario() 
{

	login = $("#txt_login").val();
	pass = $("#txt_pass").val();
	if ((login.length==0) || (pass.length==0)) 
	{ 
		alert('Debe diligenciar el usuario y clave para poder acceder.'); 
	}
	else 
	{
		AjaxConsulta( '../logica/validar_usuario.php', {us:login,pa:pass}, 'mainContent' );
		$("#txt_login").attr('value', ''); 
		$("#txt_pass").attr('value', '');
	}
}