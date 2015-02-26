/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Marzo de 2011
 * 
 * */

// HACE LA VALIDACION DEL CONTACTO CON LA EMPRESA Y MUESTRA EL FORMULARIO ADEACUADO SEGUN EL TIPO DE CAMPANA
function contactoCliente(contacto, emp_codigo, cam_codigo, tip_codigo, ntm)
{
	var tipoCampana = document.getElementById('tipoCampana').value;
	//CAMPANA ACTUALIZACION DE DATOS EMPRESA Y CONTACTOS O CAMPAÑA RECUPERACION DE CARTERA
	if(tipoCampana == 1||tipoCampana == 3)
	{	
		if (contacto=="2")
		{
			document.getElementById('identificacion').className 	= "campo";
			document.getElementById('identificacion').readOnly		= false;
			$('#sti').attr('disabled',false);
			document.getElementById('primerNombre').className		= "campo";
			document.getElementById('primerNombre').readOnly		= false;
			document.getElementById('segundoNombre').className		= "campo";
			document.getElementById('segundoNombre').readOnly 		= false;
			document.getElementById('primerApellido').className		= "campo";
			document.getElementById('primerApellido').readOnly 		= false;
			document.getElementById('segundoApellido').className	= "campo";
			document.getElementById('segundoApellido').readOnly 	= false;
			$('#sgenero').attr('disabled',false);
			$('#sestadoCivil').attr('disabled',false);
			document.getElementById('sestadoCivil').disabled		= false;
			document.getElementById('barrio').className				= "campo";
			document.getElementById('barrio').readOnly 				= false;
			document.getElementById('direccionVivienda').className	= "campo";
			document.getElementById('direccionVivienda').readOnly 	= false;
			$('#sestrato').attr('disabled',false);
			document.getElementById('direccionContacto').className	= "campo";
			document.getElementById('direccionContacto').readOnly 	= false;
			document.getElementById('email').className				= "campo";
			document.getElementById('email').readOnly 				= false;
			document.getElementById('telefono1').className			= "campo";
			document.getElementById('telefono1').readOnly 			= false;
			document.getElementById('telefono2').className			= "campo";
			document.getElementById('telefono2').readOnly 			= false;
			document.getElementById('celular1').className			= "campo";
			document.getElementById('celular1').readOnly 			= false;
			document.getElementById('celular2').className			= "campo";
			document.getElementById('celular2').readOnly 			= false;
			document.getElementById('comentario').className			= "campo";
			document.getElementById('comentario').readOnly	 		= false;
			AjaxConsulta( '../logica/proc_tipificacion_llamada.php', {DEPENDENCIA:contacto, DIV:'', NOMBRE_SELECT:'stipificacionGestion', CAMPANA:'', CLIENTE:'', NTM:'', OBSERVACIONES:'', ACCION:'listar'}, 'tipificacionGestionDiv' );
			$("#DivTLL_Contacto_Cliente").css("display", "block");
			$("#DivTLL_Contacto_Factura").css("display", "block");
			$("#DivTLL_NoContacto_Cliente").css("display", "none");
			$("#DivTLL_NoContacto_Factura").css("display", "none");
		}
		if (contacto=="3")
		{
			document.getElementById('identificacion').className 	= "campo_bloqueado";
			document.getElementById('identificacion').readOnly		= true;
			$('#sti').attr('disabled',true);
			document.getElementById('primerNombre').className		= "campo_bloqueado";
			document.getElementById('primerNombre').readOnly		= true;
			document.getElementById('segundoNombre').className		= "campo_bloqueado";
			document.getElementById('segundoNombre').readOnly 		= true;
			document.getElementById('primerApellido').className		= "campo_bloqueado";
			document.getElementById('primerApellido').readOnly 		= true;
			document.getElementById('segundoApellido').className	= "campo_bloqueado";
			document.getElementById('segundoApellido').readOnly 	= true;
			$('#sgenero').attr('disabled',true);
			$('#sestadoCivil').attr('disabled',true);
			document.getElementById('barrio').className			= "campo_bloqueado";
			document.getElementById('barrio').readOnly 			= true;
			document.getElementById('direccionVivienda').className	= "campo_bloqueado";
			document.getElementById('direccionVivienda').readOnly 	= true;
			$('#sestrato').attr('disabled',true);
			document.getElementById('direccionContacto').className	= "campo_bloqueado";
			document.getElementById('direccionContacto').readOnly 	= true;
			document.getElementById('email').className				= "campo_bloqueado";
			document.getElementById('email').readOnly 				= true;
			document.getElementById('telefono1').className			= "campo_bloqueado";
			document.getElementById('telefono1').readOnly 			= true;
			document.getElementById('telefono2').className			= "campo_bloqueado";
			document.getElementById('telefono2').readOnly 			= true;
			document.getElementById('celular1').className			= "campo_bloqueado";
			document.getElementById('celular1').readOnly 			= true;
			document.getElementById('celular2').className			= "campo_bloqueado";
			document.getElementById('celular2').readOnly 			= true;
			document.getElementById('comentario').className			= "campo_bloqueado";
			document.getElementById('comentario').readOnly	 		= true;		
			AjaxConsulta( '../logica/proc_tipificacion_llamada.php', {DEPENDENCIA:contacto, DIV:'', NOMBRE_SELECT:'stipificacionGestion', CAMPANA:'', CLIENTE:'', NTM:'', OBSERVACIONES:'', ACCION:'listar'}, 'tipificacionGestionDiv' );
			$("#DivTLL_Contacto_Cliente").css("display", "none");
			$("#DivTLL_Contacto_Factura").css("display", "none");
			$("#DivTLL_NoContacto_Cliente").css("display", "block");
			$("#DivTLL_NoContacto_Factura").css("display", "block");
		}	
	}
	// CAMPANA ENCUESTA
	if(tipoCampana == 2)
	{
		
	}

	if($("#stipificacionLlamada option:selected").val() == 2 || $("#stipificacionLlamada option:selected").val() == 3)
	{
		if($("#stipificacionLlamada option:selected").val() == 3)
			{
				if(confirm("Desea agregar agendamiento para el deudor?"))
				{
					$("#DivTLL_NoContacto_Cliente").css("display", "block");
					f_agregar_agendamiento_empresa(emp_codigo, cam_codigo, tip_codigo, ntm);
				}
				else
				{
					AjaxConsulta( '../logica/asignar_cliente.php', {ACCION:'asignar'}, 'area_trabajo');
				}
			}
		$("#DivAccionesTipificacionCliente").css("display", "block");
		$("#DivAccionesTipificacionFactura").css("display", "block");
	}
	else
	{
		$("#DivAccionesTipificacionCliente").css("display", "none");
		$("#DivAccionesTipificacionFactura").css("display", "none");
	}	
}

function realizar_llamada(prefijo, numerotelefonico, cam_codigo, tic_codigo, cli_codigo, tip_codigo)
{
	//alert(prefijo+' '+numerotelefonico+' '+cam_codigo+' '+tic_codigo+' '+cli_codigo+' '+tip_codigo);
	if (prefijo==157)
	{
		ntm = prefijo+numerotelefonico;
	}
	else
	{
		ntm = prefijo+numerotelefonico+"#";
	}
	if(confirm('haga clic en ACEPTAR si desea realizar la marcacion al telefono '+numerotelefonico))
	{
		//INVOCA AL ARCHIVO QUE REALIZA LA MARCACION
		//AjaxConsulta( '../logica/proc_realizar_llamada_agi.php', {NRO_TELEFONO:ntm, ACCION:'llamar_telefono'}, 'AGI_php' );
		//INVOCA AL ARCHIVO QUE REALIZA LA TIPIFICACION
		AjaxConsulta( '../logica/proc_gestion_campana.php', {CAMPANA:cam_codigo, CLIENTE:cli_codigo, TIPIFICACION:tip_codigo, TIPO_CAMPANA:tic_codigo, NTM:ntm, OBSERVACIONES:'Tipificacion generada automaticamente por la aplicacion', ACCION:'guardar_tipificacion'}, 'gestion_campana' );
		//RETORNA EL SELECT DE TIPIFICACION LLAMADA
		$('#stipificacionLlamada option[value=0]').attr('selected',true);
		//ACTIVA EL SELECT DE TIPIFICACION LLAMADA
		$('#stipificacionLlamada').attr('disabled',false);
		//ESTABLECE EL VALOR NTM DEL CAMPO HIDDE
		$("#ntm").attr('value', ntm);	
		//REALIZA EL LLAMADO DE LA FUNCION contactoCliente() 
		if(tip_codigo!=16)
		{
			contactoCliente(3);
		}	
	}
}

/*
 * FUNCION QUE CARGA EN EL DIALOG EL CONTENIDO NECESARIO PARA EL FORMULARIO DE BUSCAR NEGOCIO
 * */
function f_agregar_informacion_laboral(cli_codigo, cam_codigo, ntm)
{
	$('#agregar_informacion_laboral').html('<p class="validateTips">Por favor ingrese el nombre del negocio y luego seleccionel&aacute; de la lista</p><form id="form_p"><fieldset><legend>Negocio</legend><input type="hidden" id="cli_codigo" value="'+cli_codigo+'" class="campo"/><input type="hidden" id="cam_codigo" value="'+cam_codigo+'" class="campo"/><input type="hidden" id="ntm" value="'+ntm+'" class="campo"/><input type="text" name="buscar_negocio" id="buscar_negocio" class="text ui-widget-content ui-corner-all" onKeyUp="f_buscar_negocio(this.form)"/><div id= "ResultadoBusqueda_p"></div></fieldset></form>');
	$('#agregar_informacion_laboral').dialog('open');
}
/*
 * FUNCION QUE REALIZA LA SOLICITUD DE BUSQUEDA DEL PROGRAMA
 * */
function f_buscar_negocio(form)
{
	//Obtiene las variables que deben ser enviadas
	var negocio		= form.buscar_negocio.value;
	var cli_codigo 	= form.cli_codigo.value;
	var cam_codigo 	= form.cam_codigo.value;
	var ntm 		= form.ntm.value;
	AjaxConsulta_1( '../logica/proc_tabla.php', {TABLA:'negocios', TEXTO:negocio, CLIENTE:cli_codigo, CAMPANA:cam_codigo, NTM:ntm, ACCION:'buscar'}, 'ResultadoBusqueda_p' );	
}
/*
 * FUNCION QUE SELECCIONA LA INSTITUCION Y ASIGNA LOS VALORES A LOS CAMPOS CORRESPONDIENTES
 * */
function SeleccionarNegocio(codigo, nombre, cliente, cam_codigo, ntm)
{
	AjaxConsulta_1( '../logica/proc_tabla.php', {TABLA:'clientes', CAMPO:'id_negocio', VALOR:codigo, CONDICION:cliente, CAMPANA:cam_codigo, NTM:ntm, ACCION:'actualizar'}, 'gestion_campana' );
	$("#agregar_informacion_laboral").dialog("close");
	AjaxConsulta('../logica/informacion_laboral.php', {codigoEmpresa:cliente, tipoCampana:'1', codigoCampana:'1', ACCION:'listar'}, 'informacion_laboral' );
}
function f_agregar_negocio(emp_codigo, cam_codigo, ntm)
{
	var razon_social = $('#primerNombre').get(0).value +" "+ $('#segundoNombre').get(0).value+" "+ $('#primerApellido').get(0).value+" "+$('#segundoApellido').get(0).value;
	var codigo_ciudad =$('#sciudad option:selected').val();
	$('#agregar_informacion_laboral').html('<p class="validateTips">Por favor ingrese la informacion solicitada</p><form id="form_contacto"><fieldset><table border="0"><tr><td class="subtitulo_tabla"># de identificaci&oacute;n: </td><td class="texto_tabla"><input type="hidden" id="codigoEmpresaNC" value="'+emp_codigo+'" class="campo"/><input type="hidden" id="cam_codigo" value="'+cam_codigo+'" class="campo"/><input type="hidden" id="ntm" value="'+ntm+'" class="campo"/><input type="text" id="nroIdentificacionNC" class="campo" size="25"/><img src="imagenes/iconos/magic_wand_a.png" alt="Obtener informacion" title="Obtener informacion" style="cursor: pointer; border: 1px solid #CCC;" onmouseover="this.src = '+"'imagenes/iconos/magic_wand.png'"+'" onmouseout='+'"'+"this.src = 'imagenes/iconos/magic_wand_a.png'"+'" width="18" align="absbottom" onclick="$('+"'#nroIdentificacionNC').attr('value', $('#identificacion').get(0).value);"+'"/></td></tr><tr><td class="subtitulo_tabla">Raz&oacute;n social: </td><td class="texto_tabla"><input type="text" id="nombresNC" class="campo" size="40"/><img src="imagenes/iconos/magic_wand_a.png" alt="Obtener informacion" title="Obtener informacion" style="cursor: pointer; border: 1px solid #CCC;" onmouseover="this.src = '+"'imagenes/iconos/magic_wand.png'"+'" onmouseout='+'"'+"this.src = 'imagenes/iconos/magic_wand_a.png'"+'" width="18" align="absbottom" onclick="$('+"'#nombresNC').attr('value', '"+razon_social+"');"+'"/>*</td></tr><tr><td class="subtitulo_tabla">Barrio: </td><td class="texto_tabla"><input type="text" id="barrioNC" class="campo" size="40"/><img src="imagenes/iconos/magic_wand_a.png" alt="Obtener informacion" title="Obtener informacion" style="cursor: pointer; border: 1px solid #CCC;" onmouseover="this.src = '+"'imagenes/iconos/magic_wand.png'"+'" onmouseout='+'"'+"this.src = 'imagenes/iconos/magic_wand_a.png'"+'" width="18" align="absbottom" onclick="$('+"'#barrioNC').attr('value', $('#barrio').get(0).value);"+'"/>*</td></tr><tr><td class="subtitulo_tabla">Direcci&oacute;n: </td><td class="texto_tabla"><input type="text" id="direccionNC" class="campo" size="40"/><img src="imagenes/iconos/magic_wand_a.png" alt="Obtener informacion" title="Obtener informacion" style="cursor: pointer; border: 1px solid #CCC;" onmouseover="this.src = '+"'imagenes/iconos/magic_wand.png'"+'" onmouseout='+'"'+"this.src = 'imagenes/iconos/magic_wand_a.png'"+'" width="18" align="absbottom" onclick="$('+"'#direccionNC').attr('value', $('#direccionContacto').get(0).value);"+'"/>*</td></tr><tr><td class="subtitulo_tabla">Telefono:</td><td class="texto_tabla"><input type="text" id="telefono1NC" class="campo" size="15"/><img src="imagenes/iconos/magic_wand_a.png" alt="Obtener informacion" title="Obtener informacion" style="cursor: pointer; border: 1px solid #CCC;" onmouseover="this.src = '+"'imagenes/iconos/magic_wand.png'"+'" onmouseout='+'"'+"this.src = 'imagenes/iconos/magic_wand_a.png'"+'" width="18" align="absbottom" onclick="$('+"'#telefono1NC').attr('value', $('#telefono1').get(0).value);"+'"/>*</td></tr><tr><td class="subtitulo_tabla">Telefono Celular: </td><td class="texto_tabla"><input type="text" id="celularNC" class="campo" size="15"/><img src="imagenes/iconos/magic_wand_a.png" alt="Obtener informacion" title="Obtener informacion" style="cursor: pointer; border: 1px solid #CCC;" onmouseover="this.src = '+"'imagenes/iconos/magic_wand.png'"+'" onmouseout='+'"'+"this.src = 'imagenes/iconos/magic_wand_a.png'"+'" width="18" align="absbottom" onclick="$('+"'#celularNC').attr('value', $('#celular1').get(0).value);"+'"/></td></tr><tr><td class="subtitulo_tabla">Ciudad: </td><td class="texto_tabla"><script>AjaxConsulta( "../logica/proc_select_tabla.php", {TABLA_CONSULTAR:"ciudades", TABLA_ACTUALIZAR:"0", NOMBRE_CAMPO:"0", VALOR_REGISTRO:"'+codigo_ciudad+'", CODIGO_REGISTRO:"0",  DIV:"ciudadNCDiv", NOMBRE_SELECT:"ciudadNC", ESTADO:"", ACCION:"consultar_campo"}, "ciudadNCDiv" );</script><span id="ciudadNCDiv"></span>*</td></tr><tr><td class="subtitulo_tabla">Vendedor: </td><td class="texto_tabla"><input type="text" id="vendedorNC" class="campo" size="50"/></td></tr><tr><td class="subtitulo_tabla">Comentario: </td><td class="texto_tabla"><input type="text" id="comentarioNC" class="campo" size="50"/></td></tr><tr><td class="subtitulo_tabla">Categoria negocio: </td><td class="texto_tabla"><script>AjaxConsulta( "../logica/proc_select_tabla.php", {TABLA_CONSULTAR:"tipos_negocios", TABLA_ACTUALIZAR:"0", NOMBRE_CAMPO:"0", VALOR_REGISTRO:"0", CODIGO_REGISTRO:"0",  DIV:"categoriaNCDiv", NOMBRE_SELECT:"categoriaNC", ESTADO:"", ACCION:"consultar_campo"}, "categoriaNCDiv" );</script><span id="categoriaNCDiv"></span>*</td></tr><tr><td colspan="2"><input type="button" name="bt1" value="Guardar informacion laboral" class="boton" title="Haga clic para guardar el negocio" onclick="guardar_informacion_laboral(this.form);"></td></tr></table><div id= "ResultadoBusqueda_m"></div></fieldset></form>');
	$('#agregar_informacion_laboral').dialog('open');
}

function guardar_informacion_laboral(form)
{
	var codigoEmpresaNC 	= document.getElementById('codigoEmpresaNC').value;
	var nroIdentificacionNC = document.getElementById('nroIdentificacionNC').value;
	var nombresNC 			= document.getElementById('nombresNC').value;
	var barrioNC 			= document.getElementById('barrioNC').value;
	var direccionNC 		= document.getElementById('direccionNC').value;
	var telefono1NC 		= document.getElementById('telefono1NC').value;
	var celularNC 			= document.getElementById('celularNC').value;
	var ciudadNC 			= document.getElementById('ciudadNC').value;
	var vendedorNC 			= document.getElementById('vendedorNC').value;
	var comentarioNC		= document.getElementById('comentarioNC').value;
	var categoriaNC 		= document.getElementById('categoriaNC').value;
	var cam_codigo			= document.getElementById('cam_codigo').value;
	var ntm			 		= document.getElementById('ntm').value;
	//VALIDAR QUE SE HAYA INGRESADO NOMBRE
	if(nombresNC.length>0)
	{
		if(telefono1NC.length>0)
		{
			if(ciudadNC>0)
			{
				if (confirm ("Haga clic en ACEPTAR para agregar el Negocio"))
				{	
					AjaxConsulta('../logica/proc_gestion_campana.php', {codigoEmpresaNC:codigoEmpresaNC, nroIdentificacionNC:nroIdentificacionNC, nombresNC:nombresNC, barrioNC:barrioNC, direccionNC:direccionNC,  telefono1NC:telefono1NC, celularNC:celularNC, ciudadNC:ciudadNC, vendedorNC:vendedorNC, comentarioNC:comentarioNC, categoriaNC:categoriaNC, CAMPANA:cam_codigo, NTM:ntm, ACCION:'guardar_empresa'}, 'ResultadoBusqueda_m' );
				}
			}
			else
			{
				alert("Debe seleccionar la ciudad");
			}
		}
		else
		{
			alert("Debe ingresar el telefono");
		}
	}
	else
	{
		alert("Debe ingresar la razon social");
	}
}

function f_calcular_liquidacion(cli_codigo, cam_codigo, ntm, tc_codigo)
{
	$('#calcular_liquidacion_facturas').html('<script type="text/javascript">$(function() {$("#fecha_agendamiento").datepicker();});</script><p class="validateTips">Por favor ingrese la informaci&oacute;n solicitada</p><form id="form_contacto"><input type="hidden" id="cli_codigo" value="'+cli_codigo+'" class="campo"/><input type="hidden" id="cam_codigo" value="'+cam_codigo+'" class="campo"/><input type="hidden" id="ntm" value="'+ntm+'" class="campo"/><input type="hidden" id="tc_codigo" value="'+tc_codigo+'" class="campo"/><fieldset><table border="0" width="100%"><tr><td class="subtitulo_tabla">Cantidad de pagos: </td><td class="texto_tabla"><input type="text" id="cantPagosFacturas" class="campo" size="5"/>*</td></tr><tr><td class="subtitulo_tabla">Fecha: </td><td class="texto_tabla" width="50%"><input type="text"  id="fecha_agendamiento" readonly="readonly" size="12" value="" class="campo"/></td></tr><tr><td class="subtitulo_tabla">% Honorarios: </td><td class="texto_tabla"><input type="text" id="porcHonorarios" class="campo" size="3" value="20"/>%</td></tr><tr><td colspan="2"><input type="button" name="bt1" value="Calcular Liquidacion" class="boton" title="Haga clic para calcular la liquidacion de la factura al dia" onclick="calcular_liquidacion(this.form);"></td></tr></table><div id= "ResultadoBusqueda_m"></div></fieldset></form>');
	$('#calcular_liquidacion_facturas').dialog('open');
}

function calcular_liquidacion(form)
{
	var deu_codigo			= document.getElementById('cli_codigo').value;
	var cam_codigo			= document.getElementById('cam_codigo').value;
	var tc_codigo			= document.getElementById('tc_codigo').value;
	var ntm			 		= document.getElementById('ntm').value;
	var cantPagosFacturas 	= document.getElementById('cantPagosFacturas').value;
	var fecha_agendamiento	= document.getElementById('fecha_agendamiento').value;
	var porcHonorarios 		= document.getElementById('porcHonorarios').value;
	//VALIDAR QUE SE HAYA INGRESADO NOMBRE
	if(cantPagosFacturas.length>0)
	{
		if(fecha_agendamiento.length>0)
		{
			if(porcHonorarios.length>0)
			{	
				AjaxConsulta('../logica/informacion_facturas.php', {deu_codigo:deu_codigo, cam_codigo:cam_codigo, tc_codigo:tc_codigo, ntm:ntm, cantPagosFacturas:cantPagosFacturas, fecha_agendamiento:fecha_agendamiento, porcHonorarios:porcHonorarios, ACCION:'Generar_Liquidacion'}, 'acuerdo_Pagos' );
				$('#calcular_liquidacion_facturas').dialog('close');
			}
			else
			{
				alert("Debe ingresar un porcentaje valido 0 - 100");
			}
		}
		else
		{
			alert("Debe seleccionar la fecha en la cual el deudor realizara el primer pago");
		}
	}
	else
	{
		alert("Debe ingresar la cantidad de pagos que realizara el deudor para ponerse al dia con la obligacion");
	}
}
function f_agregar_agendamiento_empresa(emp_codigo, cam_codigo, tip_codigo, ntm)
{
	$('#agregar_agendamiento_empresa').html('<script type="text/javascript">$(function() {$("#fecha_agendamiento").datepicker();});</script><p class="validateTips">Por favor ingrese la informacion solicitada</p><form id="form_agendamiento"><fieldset><table border="0" width="100%"><tr><td class="subtitulo_tabla" width="70%"><input type="hidden" id="emp_codigo" value="'+emp_codigo+'" class="campo"/><input type="hidden" id="cam_codigo" value="'+cam_codigo+'" class="campo"/><input type="hidden" id="tip_codigo" value="'+tip_codigo+'" class="campo"/><input type="hidden" id="ntm" value="'+ntm+'" class="campo"/>Fecha: </td><td class="texto_tabla" width="50%"><input type="text"  id="fecha_agendamiento" readonly="readonly" size="12" value="" class="campo"/></td></tr><tr><td class="subtitulo_tabla">Hora: </td><td class="texto_tabla"><select id="hh"><option>08</option><option>09</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option><option>19</option><option>20</option><option>21</option></select>:<select id="mm"><option>00</option><option>05</option><option>10</option><option>15</option><option>20</option><option>25</option><option>30</option><option>35</option><option>40</option><option>45</option><option>50</option><option>55</option></select>*</td></tr><tr><td colspan="2" align="center"><input type="button" name="bt1" value="Guardar Agendamiento" class="boton" title="Haga clic para guardar el agendamiento" onclick="guardar_agendamiento_empresa(this.form);"></td></tr></table><div id= "ResultadoAgendamiento_m"></div></fieldset></form>');
	$('#agregar_agendamiento_empresa').dialog('open');
}

function guardar_agendamiento_empresa(form)
{
	var fecha_agendamiento 	= document.getElementById('fecha_agendamiento').value;
	var hh				 	= document.getElementById('hh').value;
	var mm 					= document.getElementById('mm').value;
	var emp_codigo			= document.getElementById('emp_codigo').value;
	var cam_codigo			= document.getElementById('cam_codigo').value;
	var tip_codigo			= document.getElementById('tip_codigo').value;
	var ntm					= document.getElementById('ntm').value;
	//VALIDAR QUE SE HAYA INGRESADO NOMBRE
	if (fecha_agendamiento.length > 0 )
	{
		if (confirm ("Haga clic en ACEPTAR para guardar el agendamiendo de la llamada"))
		{	
			AjaxConsulta('../logica/proc_gestion_campana.php', {emp_codigo:emp_codigo, cam_codigo:cam_codigo, fecha_agendamiento:fecha_agendamiento, hh:hh, mm:mm, tip_codigo:tip_codigo, ntm:ntm, ACCION:'guardar_agendamiento_empresa'}, 'ResultadoAgendamiento_m' );
		}
	}
	else
	{
		alert("Debe seleccionar una fecha");
	}
}

function f_guardar_tipificacion_gestion(cli_codigo, cam_codigo, tip_codigo, ntm, tc_codigo)
{
	if(tip_codigo !=0)
	{
		if (confirm ("Haga clic en ACEPTAR para guardar la gestion"))
		{
			AjaxConsulta( '../logica/proc_gestion_campana.php', {CAMPANA:cam_codigo, CLIENTE:cli_codigo, TIPIFICACION:tip_codigo, NTM:ntm, TIPO_CAMPANA:tc_codigo, OBSERVACIONES:'Tipificacion generada por el agente', ACCION:'guardar_tipificacion'}, 'gestion_campana' );
		}
	}	
	else
	{
		alert("Debe seleccionar la tipificacion para la gestion");
	}
}

function f_enviar_juridico(cli_codigo, cam_codigo, tip_codigo, ntm)
{
	if(tip_codigo !=0)
	{
		if (confirm ("Haga clic en ACEPTAR para enviar el deudor a Juridica"))
		{
			AjaxConsulta( '../logica/proc_gestion_campana.php', {CAMPANA:cam_codigo, CLIENTE:cli_codigo, TIPIFICACION:tip_codigo, NTM:ntm, OBSERVACIONES:'Tipificacion generada por el agente', ACCION:'guardar_tipificacion'}, 'gestion_campana' );
			
		}
	}	
	else
	{
		alert("Debe seleccionar la tipificacion para la gestion");
	}
}
function f_borrar_liquidacion(deu_codigo)
{
	if(deu_codigo !=0)
	{
		if (confirm ("Haga clic en ACEPTAR para eliminar el acuerdo del deudor"))
		{
			AjaxConsulta( '../logica/informacion_facturas.php', {deu_codigo:deu_codigo, ACCION:'Eliminar_Acuerdo'}, 'gestion_campana' );
			
		}
	}	
	else
	{
		alert("Debe seleccionar la tipificacion para la gestion");
	}
}