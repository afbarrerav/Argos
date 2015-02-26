/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 * 
 * */

$(function()
{
	var btnUpload=$('#upload');
	var status=$('#status');		
	new AjaxUpload(btnUpload, 
	{
		action: '../logica/transferir_archivo.php',
		name: 'uploadfile',
		onSubmit: function(file, ext)
		{
			if (! (ext && /^(csv)$/.test(ext)))
			{ 
				// extension is not allowed 
				status.text('Unicamente se permiten csv separados por coma');
				return false;
			}
			else
			{				
				status.text('Cargando...');
			}
		},
		onComplete: function(file, response)
		{
			//On completion clear the status
			$('#status').text('Listo');
			//Add uploaded file to list
			if(response=="success")
			{
				alert(file);
				AjaxConsulta('../logica/admin_deudores.php', {FILE:file, TABLA:$('#scargarDatos').val(), CAMPANA:$('#scampanac').val(), ACCION:'cargar_datos'}, 'files' );
				$('<li></li>').appendTo('#files').text(file).addClass('success');
			} 
			else
			{
				$('<li></li>').appendTo('#files').text(file).addClass('error');
			}
		}
	});
});