function f_guardar_gestion_proceso_juridico(deu_codigo)
{
	var juz_codigo 					= $('#sjuzgadosDiv').val();
	var tpr_codigo 					= $('#stprCodigoDiv').val();
	var deu_abogado_id				= document.getElementById('deu_abogado_id').value;
	var deu_abogado 				= document.getElementById('nombre_abogado').value;
	var valor_pep 					= document.getElementById('valor_pep').value;
	var fecha_presentacion 			= document.getElementById('fecha_presentacion').value;
	var dpro_codigo					= document.getElementById('hiddendocProcesoDiv').value;
	var dpod_codigo					= document.getElementById('hiddenpoderDiv').value;
	
	if(juz_codigo!=0)
	{
		if(tpr_codigo!=0)
		{
				if(deu_abogado_id!=0)
				{
					if(deu_abogado!=0)
					{
						if(valor_pep!=0)
						{
							if(fecha_presentacion!=0)
							{
								AjaxConsulta('../logica/gestion_proceso_juridico.php',{juz_codigo:juz_codigo, tpr_codigo:tpr_codigo, deu_codigo:deu_codigo, deu_abogado_id:deu_abogado_id, deu_abogado:deu_abogado, valor_pep:valor_pep, fecha_presentacion:fecha_presentacion, dpro_codigo:dpro_codigo, dpod_codigo:dpod_codigo, ACCION:'guardar'},'proceso');													
							}
							else
							{
								alert("Debe ingresar fecha de presentacion.");
							}
											
						}
						else
						{
							alert("Debe ingresar el valor de la demanda.");
						}				
					}
					else
					{
						alert("Debe ingresar el nombre completo del abogado del deudor.");
					}
				}
				else
				{
					alert("Debe ingresar la identificacion del abogado del deudor.");
				}
			
		}
		else
		{
			alert("Debe seleccionar el tipo de proceso.");
		}
	}	
	else
	{
		alert("Debe seleccionar un Juzgado.");
	}
}
function validar_datos_documento(codigo)
{
	$('#cargar_documentos').css('display', 'block');
	var gestion 			= $('#gestion').val();
	if(gestion=="actualizacion")
	{
		var tabla					= $('#tabla'+codigo).val();
	 	var campo					= $('#campo'+codigo).val();
	 	var td_codigo				= $('#td_codigo').val();
		var id						= $('#id_registro'+codigo).val();
		var fecha_emision 			= $('#fecha_emision'+codigo).val();
	 	var fecha_inicio_terminos 	= $('#fecha_inicio'+codigo).val();
	 	var fecha_fin_terminos 		= $('#fecha_fin'+codigo).val();
	 	var div				 		= $('#div').val();
	}
	if(gestion == "consulta")
	{
		var tabla					= $('#tabla').val();
	 	var campo					= $('#campo').val();
	 	var td_codigo				= $('#td_codigo').val();
		var id						= $('#id_registro').val();
		var fecha_emision 			= $('#fecha_emision').val();
	 	var fecha_inicio_terminos 	= $('#fecha_inicio').val();
	 	var fecha_fin_terminos 		= $('#fecha_fin').val();
	 	var div				 		= $('#div').val();
	}
 	
	if((id != 0)&&(fecha_emision != 0))
	{
		$('#mainbody'+codigo).css('display', 'block');
		$('#mainbody').css('display', 'block');
		$(function()
				{
					if(gestion=="actualizacion")
					{
						var btnUpload=$('#upload'+codigo);
						var status=$('#status'+codigo);
					}
					if(gestion == "consulta")
					{
						var btnUpload=$('#upload');
						var status=$('#status');
					}
					
					new AjaxUpload(btnUpload,
					{
						action: '../logica/transferir_documentos.php?codigo='+codigo+'&div='+div+'&gestion='+gestion+'&campo='+campo+'&td_codigo='+td_codigo+'&tabla='+tabla+'&id='+id+'&fecha_emision='+fecha_emision+'&fecha_inicio_terminos='+fecha_inicio_terminos+'&fecha_fin_terminos='+fecha_fin_terminos,
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
							AjaxConsulta('../logica/gestion_proceso_juridico.php', {div:div, ACCION:'buscar_d_codigo'}, ''+div+'');
							$('#cargar_documentos').css('display', 'none');
						}
					});
				});
	}
	else
	{
		//$('#mainbody').css('display', 'none');
	}
}