/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com	
 * @version:2.0.0
 * @fecha:	Enero de 2013
 * 
 * */

function buscar()
{
	var jva_codigo		= $('#sjva').val();
	if (jva_codigo>0)
	{
		var ven_codigo		= $('#svededoresjva').val();
		var inicio			= document.getElementById('fecha_inicio').value;
		var fin				= document.getElementById('fecha_fin').value;
		if(ven_codigo>0)
		{
			if(inicio.length > 0)
			{
				if(fin.length > 0)
				{
					//alert("jva "+jva_codigo+" vendedor "+ven_codigo);
					AjaxConsulta('../logica/admin_gastos.php', {JVA_CODIGO:jva_codigo, VEN_CODIGO:ven_codigo, INICIO:inicio, FIN:fin, ACCION:'listar'}, 'adm_gastos_listar')
				}
				else
				{
					alert("Seleccione Fecha fin.");
				}
			}
			else
			{
				alert("Seleccione Fecha inicio.");
			}
		}
		else
		{
			alert("Seleccione un Vendedor del jva.");
		}
	}
	else
	{
		alert("Seleccione un JVA, Vendedor, Fecha Inicio y Fecha fin para ver la informacion.");
	}
}
function editar_guardar(form)
{
	var codigo 		= document.getElementById('codigo').value;
	var fecha_gasto = document.getElementById('fecha_gasto').value;
	var aju_codigo 	= $('#sVendedores2').val();
	var pgj_codigo 	= $('#spgj').val();
	var valor		 = document.getElementById('valor').value;
	var fecha_trans = document.getElementById('fecha_trans').value;
	//alert("-"+fecha_gasto);
	//VERIFICA QUE FECHA GASTO ESTE COMPLETO
	if(fecha_gasto.length > 0)
	{
		//VERIFICA QUE SE HAYA SELECCIONADO UN USUARIO DEL JVA
		if(aju_codigo> 0)
		{	
			//VERIFICA QUE TENGA TIPO DE GASTO
			if(pgj_codigo> 0)
			{
				// VERIFICA QUE SE HAYA INGRESADO UN VALOR CORRECTAMENTE PARA EL GASTO
				if((valor.length > 0) && (!isNaN(valor)))
				{
					// VERIFICA QUE SE HAYA SELECCIONADO UNA FECHA TRANSACCION
					if(fecha_trans.length > 0)
					{									
						if(confirm("Haga clic en ACEPTAR para actualizar el gasto"))
						{
							AjaxConsulta('../logica/admin_gastos.php', {CODIGO:codigo, FECHA_GASTO:fecha_gasto, AJU_CODIGO:aju_codigo, PGJ_CODIGO:pgj_codigo, VALOR:valor, FECHA_TRANS:fecha_trans, ACCION:'actualizar'}, 'adm_gastos_listar');
						}	
					}
					else
					{
						alert("Seleccione la fecha de la transaccion");
					}
				}
				else
				{
					alert("Ingrese el valor del gasto correctamente solo valores numericos");
				}
			}
			else
			{
				alert("Seleccion un tipo de gasto");
			}
		}
		else
		{
			alert("Seleccione un usuario del jva");
		}
	}
	else
	{
		alert("Seleccione la fecha del gasto");
	}
}
function crear_gasto()
{
	var jva_codigo		= $('#sjva').val();
	var aju_codigo		= $('#svededoresjva').val();
	if (jva_codigo>0)
	{
		if (aju_codigo>0)
		{
			AjaxConsulta('../logica/admin_gastos.php', {JVA_CODIGO:jva_codigo, AJU_CODIGO:aju_codigo, ACCION:'front_crear_gasto'}, 'adm_gastos_listar');
		}
		else
		{
			alert("Seleccione el Usuario para poder crear el gasto");
		}
	}
	else
	{
		alert("Seleccione el JVA y el usuario a lo que va a crear el gasto.");
	}
}
function guardar_gasto(form)
{
	var jva_codigo		= document.getElementById('jva_codigo').value;
	var usu_codigo 		= document.getElementById('usu_codigo').value;
	var fecha_gasto 	= document.getElementById('fecha_gasto').value;
	var pgj_codigo		= $('#spgj').val();
	var valor 			= document.getElementById('valor').value;
	if(fecha_gasto.length > 0)
	{
		if(pgj_codigo > 0)
		{
			if((valor.length > 0) && (!isNaN(valor)))
			{
				AjaxConsulta('../logica/admin_gastos.php', {JVA_CODIGO:jva_codigo, USU_CODIGO:usu_codigo, FECHA_GASTO:fecha_gasto, PGJ_CODIGO:pgj_codigo, VALOR:valor, ACCION:'crear_gasto'}, 'adm_gastos_listar');
			}
			else
			{
				alert("Digite solo valores numericos en el campo Valor");
			}
		}
		else
		{
			alert("Seleccione un tipo de gasto para el usuario");
		}		
	}
	else
	{
		alert("Seleccione una fecha de Gasto valida");
	}
}