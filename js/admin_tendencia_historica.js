/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com	
 * @version:2.0.0
 * @fecha:	Enero de 2013
 * 
 * */
function generar_grafica()
{	
	var jva_codigo		= document.getElementById('sjva').value;
	var tipo_reporte	= document.getElementById('stiposReporte').value;

	AjaxConsulta('../logica/admin_tendencia_historica.php', {JVA_CODIGO:jva_codigo, ACCION:tipo_reporte}, 'infoTH');
	
}