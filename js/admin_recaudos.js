/*
 * @author:	MIGUEL ANGEL POSADA
 * 			miguelrodriguezpo@hotmail.com	
 * @version:2.0.0
 * @fecha:	Enero de 2013
 * 
 * */
 function buscar_recaudos()
 {
 	var campo = document.getElementById('sclientes').value;
 	var valor = document.getElementById('valor').value;

 	if(campo =! 0)
 	{
 		if(valor.length > 0)
 		{
 			AjaxConsulta('../logica/admin_recaudos.php', {}. '');
 		}
 		else
 		{
 			alert('Indique un criterio para la busqueda');
 		}
 	}
 	else
 	{
 		if(confirm('Desea consultar todos los recaudos del dia?'))
 		{
 			AjaxConsulta('../logica/admin_recaudos.php', {}. '');
 		}
 	}
 }