/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com	
 * @version:2.0.0
 * @fecha:	Enero de 2013
 * 
 * */
function Buscar()
{	
	var campo		= document.getElementById('satributos').value;
	var valor		= document.getElementById('parametro').value;
	if(campo>0)
	{
		if (valor!="")
		{
			AjaxConsulta( '../logica/admin_clientes.php', {CAMPO:campo, VALOR:valor, ACCION:'consulta_like'}, 'listar_clientes_Div');
		}	
		else
		{
			alert("Digite un valor para buscar");
		}
	}
	else
	{
		if(campo == 0)
		{
			if(confirm("Desea consultar todos los clientes?"))
			{
				AjaxConsulta( '../logica/admin_clientes.php', {ACCION:'listar'}, 'listar_clientes_Div');
			}
			else
			{
				alert("Seleccione un campo y un digite un valor para buscar el cliente.");
			}
		}
	}	
}
function consulta_like_atributos()
{
	var campo		= document.getElementById('satributos').value;
	var valor		= document.getElementById('parametro').value;
	if(campo.length > 0)
	{
		AjaxConsulta('../logica/admin_clientes.php', {CAMPO:campo, VALOR:valor, ACCION:'consulta_like'}, 'listar_clientes_Div' );	
	}
	else
	{
		alert("Seleccione un campo y un digite un valor para buscar el cliente.");
	}
}
function editar(form)
{
	var codigo				= document.getElementById('codigo').value;
	var ti_codigo			= $('#stipoIdentificacion').val();
	var nroidentificacion	= document.getElementById('nroidentificacion').value;
	var referencia			= document.getElementById('referencia').value;
	var razon_social		= document.getElementById('razon_social').value;
	var tn_codigo			= $('#stipoNegocio').val(); 
	var primer_nombre		= document.getElementById('primer_nombre').value;
	var segundo_nombre		= document.getElementById('segundo_nombre').value;
	var primer_apellido		= document.getElementById('primer_apellido').value;
	var segundo_apellido	= document.getElementById('segundo_apellido').value;
	var telefono1			= document.getElementById('telefono1').value;
	var ext1				= document.getElementById('ext1').value;
	var telefono2			= document.getElementById('telefono2').value;
	var ext2				= document.getElementById('ext2').value;
	var celular1			= document.getElementById('celular1').value;
	var celular2			= document.getElementById('celular2').value;
	var email				= document.getElementById('email').value;
	var barrio				= document.getElementById('barrio').value;
	var direccion			= document.getElementById('direccion').value;
	var ciu_codigo			= $('#stiposCiudades').val();
	var calificacion		= document.getElementById('calificacion').value;
	var comentario			= document.getElementById('comentario').value;
	// VERIFICA QUE SE HAYA INGRESADO EL NUMERO DE IDENTIFICACION CORRECTAMENTE
	if((nroidentificacion.length > 0) && (!isNaN(nroidentificacion)))
	{
		if(primer_nombre.length > 0)
		{
			if(primer_apellido.length > 0)
			{
				if(razon_social.length > 0)
				{
					// VERIFICA QUE SE HAYA INGRESADO UN VALOR VALIDO PARA EL CAMPO TELEFONO1
					if((telefono1.length > 0) && (!isNaN(telefono1)))
					{
						// VERIFICA QUE SE HAYA SELECCIONADO UN DEPARTAMENTO
						if(direccion.length > 0)
						{	// VERIFICA QUE SE HAYA SELECCIONADO UNA CIUDAD
							if(ciu_codigo > 0)
							{								
								if(confirm("Haga clic en ACEPTAR para actualizar el cliente"))
								{
									AjaxConsulta('../logica/admin_clientes.php', {codigo:codigo, ti_codigo:ti_codigo, nroidentificacion:nroidentificacion, referencia:referencia, razon_social:razon_social, tn_codigo:tn_codigo, primer_nombre:primer_nombre, segundo_nombre:segundo_nombre, primer_apellido:primer_apellido, segundo_apellido:segundo_apellido, telefono1:telefono1, ext1:ext1, telefono2:telefono2, ext2:ext2, celular1:celular1, celular2:celular2, email:email, barrio:barrio, direccion:direccion, ciu_codigo:ciu_codigo, calificacion:calificacion, comentario:comentario, ACCION:'actualizar'}, 'listar_clientes_Div');
								}	
							}
							else
							{
								alert("Debe seleccionar la ciudad");
							}	
						}
						else
						{
							alert("Debe seleccionar el departamento");
						}	
					}
					else
					{
						alert("Debe ingresar un numero de telefono valido en el campo telefono 1");
					}
				}
				else
				{
					alert("Debe ingresar primer apellido");
				}
			}
			else
			{
				alert("Debe ingresar la razon social del cliente");
			}
		}
		else
		{
			alert("Debe ingresar primer nombre");
		}
	}
	else
	{
		alert("Debe ingresar el numero de identificacion correctamente");
	}
}