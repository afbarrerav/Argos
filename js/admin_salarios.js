/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:2.0.0
 * @fecha:	ENERO de 2012
 */

/*
 * INVOCA EL SCRIPT QUE CONSULTA SI EXISTE O NO UNA LIQUIDACION PARA EL VENDEDOR
 * EN EL RANGO DE FECHAS INDICADAS
 */

function cosultar_liquidacion()
{
	/*
	 * OBTIENE LAS VARIABLES REQUERIDAS PARA CONSULTAR LA LIQUIDACION
	 * */
	var jva_codigo 	= document.getElementById('sjva').value;
	var usu_codigo 	= document.getElementById('svededoresjva').value;
	var vendedor 	= document.getElementById('svededoresjva').options[document.getElementById('svededoresjva').selectedIndex].text;
	var fecha_inicio= document.getElementById('fecha_inicio').value;
	var fecha_fin 	= document.getElementById('fecha_fin').value;
	/*
	 * VALIDA QUE SE HAYA SELECCIONADO UN JVA PARA CONSULTAR EL JVA
	 * */
	if(jva_codigo != 0)
	{
		/*
		 * VALIDA QUE SE HAYA SELECCIONADO UN VENDEDOR PARA CONSULTAR LA LIQUIDACION
		 * */	
		if(usu_codigo!=0)
		{
			/*
			 * FALTA VALIDAR FECHA DE INICIO Y FECHA DE FIN
			 * */
			AjaxConsulta('../logica/admin_salarios.php', {USU_CODIGO:usu_codigo, FECHA_INICIO:fecha_inicio, FECHA_FIN:fecha_fin, NOMBRE_VENDEDOR:vendedor, ACCION:'cosultar_liquidacion'}, 'rpt_LiquidacionDiv');
		}	
		else
		{
			alert ("Debe seleccionar un vendedor e ingresar la fecha de inicio y fin para poder consultar la liquidacion");
		}
	}
	else
	{
		alert ("Debe seleccionar el jva, el vendedor e ingresar la fecha de inicio y fin para poder consultar la liquidacion");	
	}
}

function validar_fechas()
{
	/*
	 * OBTIENE LAS VARIABLES REQUERICAS PARA VALIDAR LAS FECHAS
	 * */
}