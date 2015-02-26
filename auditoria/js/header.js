/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Junio de 2011
 * 
 * */
function AjaxConsulta( pagDestino, parametros, htmlObjetivo )
{
    htmlObjetivo = "#"+htmlObjetivo;
    $(htmlObjetivo).html("<table align='center'><tr><td align='center'>Cargando...<br><img src='imagenes/cargando.gif' alt='Cargando...'></td></tr></table>");
    $(htmlObjetivo).load(pagDestino, parametros, 				function()
                                                                {
        
                                                                }
                        );
}

function AjaxConsulta_1( pagDestino, parametros, htmlObjetivo )
{
    htmlObjetivo = "#"+htmlObjetivo;
    $(htmlObjetivo).load(pagDestino, parametros, 				function()
                                                                {
        
                                                                }
                        );
}
jQuery(function($){
	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		changeYear: true,
		yearRange: '2001:2021',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		weekHeader: 'Sm',
		dateFormat: 'yy-mm-dd',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});