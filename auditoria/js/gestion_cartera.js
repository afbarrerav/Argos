/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 * 
 * */

function buscar()
{
	var gestion_cartera = document.getElementById('gestion_cartera').value;
	var tipo_seleccion	= document.getElementById('stipo_seleccion').value;
	var valor 			= document.getElementById('valor').value;

	if( (tipo_seleccion == 0)&&(valor.length == 0) )
	{
		AjaxConsulta('../logica/gestion_cartera.php',{gestion_cartera:gestion_cartera, tipo_seleccion:tipo_seleccion, ACCION:'consultar'},'deudores');
	}
	else
	{
		AjaxConsulta('../logica/gestion_cartera.php',{gestion_cartera:gestion_cartera, tipo_seleccion:tipo_seleccion, valor:valor, ACCION:'consultar'},'deudores');
	}
		
}

function frm_cargarImagen(codigo, d_codigo)
{
	$('#guardar_documento_acuerdo_pago').css('display', 'block');
	$('#seguimiento').css('display', 'none');
	
	AjaxConsulta('../presentacion/frm_transferir_documentos.php',{GESTION:'actualizacion', OPCION:'ACTUALIZAR', ARCHIVO:'gestion_campana', CODIGO:codigo, TABLA:'acuerdos_pagos', CAMPO:'dap_codigo', TD_CODIGO:'2', d_codigo:d_codigo},'guardar_documento_acuerdo_pago');
}

function frm_cargarDocumento_seguimiento(codigo,d_codigo)
{
	$('#guardar_cpp').css('display', 'block');
	AjaxConsulta('../presentacion/frm_transferir_documentos.php',{GESTION:'actualizacion', OPCION:'ACTUALIZAR', ARCHIVO:'gestion_campana', CODIGO:codigo, TABLA:'acuerdos_pagos_seguimiento', CAMPO:'cpp_codigo', TD_CODIGO:'2', d_codigo:d_codigo},'guardar_cpp');
}

function validar_datos_documento(codigo)
{	
 	var tabla					= $('#tabla'+codigo).val();
 	var campo					= $('#campo'+codigo).val();
 	var gestion					= $('#gestion').val();
	var id						= $('#id_registro'+codigo).val();
	var fecha_emision 			= $('#fecha_emision'+codigo).val();
 	var fecha_inicio_terminos 	= $('#fecha_inicio'+codigo).val();
 	var fecha_fin_terminos 		= $('#fecha_fin'+codigo).val();
 	var td_codigo				= $('#td_codigo').val();
 	var d_codigo				= $('#d_codigo'+codigo).val();
 	
	if(fecha_emision != 0)
	{
		$('#mainbody'+codigo).css('display', 'block');
		
		$(function()
				{
					var btnUpload=$('#upload'+codigo);
					var status=$('#status'+codigo);	
					new AjaxUpload(btnUpload,
					{
						action: '../logica/transferir_documentos.php?gestion='+gestion+'&campo='+campo+'&tabla='+tabla+'&codigo='+codigo+'&id='+id+'&fecha_emision='+fecha_emision+'&fecha_inicio_terminos='+fecha_inicio_terminos+'&fecha_fin_terminos='+fecha_fin_terminos+'&td_codigo='+td_codigo+'&d_codigo='+d_codigo,
						name: 'uploadfile',
						onSubmit: function(file, ext)
						{
							if (!(ext && /^(pdf)$/.test(ext))&&(ext && /^(jpeg)$/.test(ext)))
							{ 
								// extension is not allowed 
								status.text('Unicamente se permiten archivos jpg y pdf');
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
								$('<li></li>').appendTo('#files').text(file).addClass('success');
								AjaxConsulta('../logica/listar_archivos.php', '', 'listarImagenesDiv');
							} 
							else	
							{
								$('<li></li>').appendTo('#files').text(file).addClass('error');
							}
							id						= "";
							fecha_emision			= "";
							fecha_inicio_terminos	= "";
							fecha_fin_terminos		= "";
							$('#guardar_cpp').css('display', 'none');
							$('#cargar_documentos').css('display', 'none');
							$('#guardar_documento_acuerdo_pago').css('display', 'none');
							alert('ya hay imagen en el registro codigo='+d_codigo);
							if(d_codigo == 0)
							{
								AjaxConsulta('../logica/gestion_cartera.php',{codigo:codigo, ACCION:'buscar_d_codigo'},'ver_documento'+codigo);
							}
							else
							{
								AjaxConsulta('../logica/gestion_cartera.php',{d_codigo:d_codigo, ACCION:'mostrar_documento'},'ver_documento'+codigo);
							}
							
							
						}
					});
				});
	}
	else
	{
		//$('#mainbody').css('display', 'none');
	}
}













