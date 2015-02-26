/*
 * @author:	MIGUEL ANGEL POSADA RODRIGUEZ
 * 			miguelrodriguezpo@hotmail.com	
 * @version:2.0.0
 * @fecha:	Diciembre de 2012
 * 
 * */
function GenerarReporte()
{
	/*
	 * VARIABLES QUE SE NECESITAN PARA HACER LOS REPORTES
	 * */
	
	// TIPO DE REPORTE GASTO, VENTA, RECAUDOS, SALDO
	var sTipoReporte 	= document.getElementById('sTipoReporte').value;
	
	// REPORTE DETALLADO O CONSOLIDADO
	var scon_deta		= document.getElementById('scon_deta').value;
	
	// JVA SELECCIONADO
	var sjva 			= document.getElementById('sjva').value;
	
	// SI EL JVA SE SELECCIONA OBTIENE EL VENDEDOR
	if(sjva > 0)
	{
		// OBTIENE EL VENDEDOR
		var svededoresjva	= document.getElementById('svededoresjva').value;
	}
	
	// OBTIENE EL NOMBRE DEL CLIENTE PARA CONSULTAR LA INFORMACION DEL MISMO
	var cliente			= document.getElementById('cliente').value;
	
	// FECHA INICIO
	var Fecha_inicio	= document.getElementById('fecha_inicio').value;
	
	// FECHA FIN
	var Fecha_fin 		= document.getElementById('fecha_fin').value;
	
	//SI EL REPORTE ES MENSUAL GENERAL
	if(sTipoReporte == "mensual_general")
	{
		// SI EL JVA ES DIFERENTE 0
		if(sjva != 0)
		{
			if(Fecha_inicio != 0)
			{
				if(svededoresjva != 0)
				{
					// CONSULTA EL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
					AjaxConsulta('../logica/rpt_mensual_general.php', {svededoresjva:svededoresjva, sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_mensual_general_socio_jva_vendedor_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
				}
				else
				{
					// CONSULTA EL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
					AjaxConsulta('../logica/rpt_mensual_general.php', {sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_mensual_general_socio_jva_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
				}
			}
			else
			{
				if(svededoresjva != 0)
				{
					// CONSULTA EL JAVA SELECCIONADO Y EL VENDEDOR SELECCIONADO
					AjaxConsulta('../logica/rpt_mensual_general.php', {svededoresjva:svededoresjva, sjva:sjva, ACCION:'consultar_mensual_general_socio_jva_vendedor'}, 'rpt_reporteDiv');
				}
				else
				{
					// CONSULTA EL JVA SELECCIONADO
					AjaxConsulta('../logica/rpt_mensual_general.php', {sjva:sjva, ACCION:'consultar_mensual_general_socio_jva'}, 'rpt_reporteDiv');
				}
			}
		}
		else
		{
			// SI NO SE SELECCIONA UN JVA PUEDE CONSULTAR TODOS LOS JVA CON FECHA INICIO - FIN
			if(Fecha_inicio != 0)
			{
				// CONSULTA TODOS LOS JVA CON FECHA INICIO - FIN
				AjaxConsulta('../logica/rpt_mensual_general.php', {Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_mensual_general_socio_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
			}
			else
			{
				// SI NO SE SELECCIONA UN JVA PUEDE CONSULTAR TODOS LOS JVA DEL SOCIO
				AjaxConsulta('../logica/rpt_mensual_general.php', {ACCION:'consultar_mensual_general_socio'}, 'rpt_reporteDiv');
			}
		}
	}

	//SI EL REPORTE ES BALANCE DE RUTA
	if(sTipoReporte == 'balance_ruta')
	{
		//DEBE SELECCIONAR JVA Y VENDEDOR PARA PODER REALIZAR LA CONSULTA
		if(sjva != 0)
		{
			if(svededoresjva != 0)
			{
				if(Fecha_inicio != 0)
				{
					// CONSULTA LOS SALDOS DEL JAVA SELECCIONADO Y EL VENDEDOR SELECCIONADO
					AjaxConsulta('../logica/rpt_balance_ruta.php', {svededoresjva:svededoresjva, sjva:sjva, Fecha_inicio:Fecha_inicio, ACCION:'balance_ruta'}, 'rpt_reporteDiv');
				}
				else
				{
					alert('Debe seleccionar fecha inicio');
				}
			}
			else
			{
				alert('Debe seleccionar un vendedor');
			}
		}
		else
		{
			alert('Debe seleccionar un jva');
		}
	}
	//SI EL REPORTE ES SALDOS DE RUTAS
	else if(sTipoReporte == 'saldos_ruta')
	{
		//DEBE SELECCIONAR JVA Y VENDEDOR PARA PODER REALIZAR LA CONSULTA
		if(sjva != 0)
		{
			if(svededoresjva != 0)
			{
				// CONSULTA LOS SALDOS DEL JAVA SELECCIONADO Y EL VENDEDOR SELECCIONADO
				AjaxConsulta('../logica/rpt_saldos.php', {svededoresjva:svededoresjva, sjva:sjva, ACCION:'consultar_saldos_socio_jva_vendedor'}, 'rpt_reporteDiv');
			}
			else
			{
				alert('Debe seleccionar un vendedor');
			}
		}
		else
		{
			alert('Debe seleccionar un jva');
		}
	}
	// SI EL SELECT CONSOLIDADO DETALLADO ES DIFERENTE DE 0
	else if(scon_deta != 0)
	{
		// SI EL SELECT CONSOLIDADO - DETALLADO ES DETALLADO ->
		if(scon_deta == "detallado")
		{
			// SI EL REPORTE A REALIZAR ES DIFERENTE DE 0
			if(sTipoReporte != 0)
			{
				// SI EL REPORTE A REALIZAR ES GASTOS ->
				if(sTipoReporte == "gastos")
				{
					/* SI EL CAMPO CLIENTE CONTIENE CARACTERES HACE LA CONSULTA SOLO A ESE CLIENTE */
					if(cliente.length > 0)
					{
						// CONSULTA LOS GASTOS DEL VENDEDOR INDICADO 
						AjaxConsulta('../logica/rpt_gastos.php', {cliente:cliente, ACCION:'consultar_gastos_socio_vendedor'}, 'rpt_reporteDiv');
					}
					else
					{
						// SI EL JVA ES DIFERENTE 0
						if(sjva != 0)
						{
							if(Fecha_inicio != 0)
							{
								// SI FECHA FIN ES DIFERENTE DE 0
								if(Fecha_fin != 0)
								{
									if(svededoresjva != 0)
									{
										// CONSULTA LOS GASTOS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
										AjaxConsulta('../logica/rpt_gastos.php', {svededoresjva:svededoresjva, sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_gastos_socio_jva_vendedor_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
									}
									else
									{
										// CONSULTA LOS GASTOS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
										AjaxConsulta('../logica/rpt_gastos.php', {sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_gastos_socio_jva_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
									}
								}
								else
								{
									alert('Seleccione fecha fin para realizar la busqueda');
								}
							}
							else
							{
								if(svededoresjva != 0)
								{
									// CONSULTA LOS GASTOS DEL JAVA SELECCIONADO Y EL VENDEDOR SELECCIONADO
									AjaxConsulta('../logica/rpt_gastos.php', {svededoresjva:svededoresjva, sjva:sjva, ACCION:'consultar_gastos_socio_jva_vendedor'}, 'rpt_reporteDiv');
								}
								else
								{
									// CONSULTA LOS RECAUDOS DEL JVA SELECCIONADO
									AjaxConsulta('../logica/rpt_gastos.php', {sjva:sjva, ACCION:'consultar_gastos_socio_jva'}, 'rpt_reporteDiv');
								}
							}
						}
						else
						{
							// SI NO SE SELECCIONA UN JVA PUEDE CONSULTAR TODOS LOS JVA CON FECHA INICIO - FIN
							if(Fecha_inicio != 0)
							{
								if(Fecha_fin != 0)
								{
									// CONSULTA TODOS LOS JVA CON FECHA INICIO - FIN
									AjaxConsulta('../logica/rpt_gastos.php', {Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_gastos_socio_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
								}
								else
								{
									alert('Seleccione fecha fin para realizar la busqueda');
								}
							}
							else
							{
								// SI NO SE SELECCIONA UN JVA PUEDE CONSULTAR TODOS LOS JVA DEL SOCIO
								AjaxConsulta('../logica/rpt_gastos.php', {ACCION:'consultar_gastos_socio'}, 'rpt_reporteDiv');
							}
						}
					}
				}
				// SI EL REPORTE A REALIZAR ES mensual_general ->
				if(sTipoReporte == "mensual_general_otro")
				{
					AjaxConsulta('', {}, '');
				}
				// SI EL REPORTE A REALIZAR ES RECAUDOS ->
				if(sTipoReporte == "recaudos")
				{
					/* SI EL CAMPO CLIENTE CONTIENE CARACTERES HACE LA CONSULTA SOLO A ESE CLIENTE */
					if(cliente.length > 0)
					{
						if(Fecha_inicio != 0)
						{
							if(Fecha_fin != 0)
							{
								// CONSULTA LOS RECAUDOS DEL VENDEDOR INDICADO
								AjaxConsulta('../logica/rpt_recaudos.php', {cliente:cliente, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_recaudos_socio_cliente_fecha_inicio_fecha_fin'}, 'rpt_reporteDiv');
							}
							else
							{
								alert('Seleccione fecha fin para realizar la busqueda');
							}
						}
						else
						{
							// CONSULTA LOS RECAUDOS DEL VENDEDOR INDICADO
							AjaxConsulta('../logica/rpt_recaudos.php', {cliente:cliente, ACCION:'consultar_recaudos_socio_cliente'}, 'rpt_reporteDiv');
						}
					}
					else
					{
						// SI EL JVA ES DIFERENTE 0
						if(sjva != 0)
						{
							if(Fecha_inicio != 0)
							{
								// SI FECHA FIN ES DIFERENTE DE 0
								if(Fecha_fin != 0)
								{
									if(svededoresjva != 0)
									{
										// CONSULTA LOS RECAUDOS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
										AjaxConsulta('../logica/rpt_recaudos.php', {svededoresjva:svededoresjva, sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_recaudos_socio_jva_vendedor_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
									}
									else
									{
										// CONSULTA LOS RECAUDOS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
										AjaxConsulta('../logica/rpt_recaudos.php', {sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_recaudos_socio_jva_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
									}
								}
								else
								{
									alert('Seleccione fecha fin para realizar la busqueda');
								}
							}
							else
							{
								if(svededoresjva != 0)
								{
									// CONSULTA LOS RECAUDOS DEL JAVA SELECCIONADO Y EL VENDEDOR SELECCIONADO
									AjaxConsulta('../logica/rpt_recaudos.php', {svededoresjva:svededoresjva, sjva:sjva, ACCION:'consultar_recaudos_socio_jva_vendedor'}, 'rpt_reporteDiv');
								}
								else
								{
									// CONSULTA LOS RECAUDOS DEL JVA SELECCIONADO
									AjaxConsulta('../logica/rpt_recaudos.php', {sjva:sjva, ACCION:'consultar_recaudos_socio_jva'}, 'rpt_reporteDiv');
								}
							}
						}
						else
						{
							// SI NO SE SELECCIONA UN JVA PUEDE CONSULTAR TODOS LOS JVA CON FECHA INICIO - FIN
							if(Fecha_inicio != 0)
							{
								if(Fecha_fin != 0)
								{
									// CONSULTA TODOS LOS JVA CON FECHA INICIO - FIN
									AjaxConsulta('../logica/rpt_recaudos.php', {Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_recaudos_socio_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
								}
								else
								{
									alert('Seleccione fecha fin para realizar la busqueda');
								}
							}
							else
							{
								// SI NO SE SELECCIONA UN JVA PUEDE CONSULTAR TODOS LOS JVA DEL SOCIO
								AjaxConsulta('../logica/rpt_recaudos.php', {ACCION:'consultar_recaudos_socio'}, 'rpt_reporteDiv');
							}
						}
					}
				}
				// SI EL REPORTE A REALIZAR ES salarios ->
				if(sTipoReporte == "salarios")
				{
					AjaxConsulta('', {}, '');
				}
				// SI EL REPORTE A REALIZAR ES SALDOS ->
				if(sTipoReporte == "saldos")
				{
					/* SI EL CAMPO CLIENTE CONTIENE CARACTERES HACE LA CONSULTA SOLO A ESE CLIENTE */ 
					if(cliente.length > 0)
					{
						// CONSULTA LOS SALDOS DEL VENDEDOR INDICADO
						AjaxConsulta('../logica/rpt_saldos.php', {cliente:cliente, ACCION:'consultar_saldo_cliente'}, 'rpt_reporteDiv');
					}
					else
					{
						// SI EL JVA ES DIFERENTE 0
						if(sjva != 0)
						{
							if(Fecha_inicio != 0)
							{
								// SI FECHA FIN ES DIFERENTE DE 0
								if(Fecha_fin != 0)
								{
									if(svededoresjva != 0)
									{
										// CONSULTA LOS SALDOS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
										AjaxConsulta('../logica/rpt_saldos.php', {svededoresjva:svededoresjva, sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_saldos_socio_jva_vendedor_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
									}
									else
									{
										// CONSULTA LOS SALDOS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
										AjaxConsulta('../logica/rpt_saldos.php', {sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_saldos_socio_jva_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
									}
								}
								else
								{
									alert('Seleccione fecha fin para realizar la busqueda');
								}
							}
							else
							{
								if(svededoresjva != 0)
								{
									// CONSULTA LOS SALDOS DEL JAVA SELECCIONADO Y EL VENDEDOR SELECCIONADO
									AjaxConsulta('../logica/rpt_saldos.php', {svededoresjva:svededoresjva, sjva:sjva, ACCION:'consultar_saldos_socio_jva_vendedor'}, 'rpt_reporteDiv');
								}
								else
								{
									// CONSULTA LOS SALDOS DEL JVA SELECCIONADO
									AjaxConsulta('../logica/rpt_saldos.php', {sjva:sjva, ACCION:'consultar_saldos_socio_jva'}, 'rpt_reporteDiv');
								}
							}
						}
						else
						{
							// SI NO SE SELECCIONA UN JVA PUEDE CONSULTAR TODOS LOS JVA CON FECHA INICIO - FIN
							if(Fecha_inicio != 0)
							{
								if(Fecha_fin != 0)
								{
									// CONSULTA TODOS LOS JVA CON FECHA INICIO - FIN
									AjaxConsulta('../logica/rpt_saldos.php', {Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_saldo_socio_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
								}
								else
								{
									alert('Seleccione fecha fin para realizar la busqueda');
								}
							}
							else
							{
								// CONSULTA LOS SALDOS DEL JVA SELECCIONADO
								AjaxConsulta('../logica/rpt_saldos.php', {ACCION:'consultar_saldo_socio'}, 'rpt_reporteDiv');
							}
						}
					}
				}
				// SI EL REPORTE A REALIZAR ES VENTAS ->
				if(sTipoReporte == "ventas")
				{
					/* SI EL CAMPO CLIENTE CONTIENE CARACTERES HACE LA CONSULTA SOLO A ESE CLIENTE */ 
					if(cliente.length > 0)
					{
						if(Fecha_inicio != 0)
						{
							if(Fecha_fin != 0)
							{
								// CONSULTA LAS VENTAS DEL VENDEDOR INDICADO
								AjaxConsulta('../logica/rpt_ventas.php', {cliente:cliente, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_ventas_cliente_fecha_inicio_fecha_fin'}, 'rpt_reporteDiv');
							}
							else
							{
								alert('Seleccione fecha fin para realizar la busqueda');
							}
						}
						else
						{
							// CONSULTA LAS VENTAS DEL VENDEDOR INDICADO
							AjaxConsulta('../logica/rpt_ventas.php', {cliente:cliente, ACCION:'consultar_ventas_cliente'}, 'rpt_reporteDiv');
						}
					}
					else
					{
						// SI EL JVA ES DIFERENTE 0
						if(sjva != 0)
						{
							if(Fecha_inicio != 0)
							{
								// SI FECHA FIN ES DIFERENTE DE 0
								if(Fecha_fin != 0)
								{
									if(svededoresjva != 0)
									{
										// CONSULTA LOS VENTAS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
										AjaxConsulta('../logica/rpt_ventas.php', {svededoresjva:svededoresjva, sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_ventas_socio_jva_vendedor_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
									}
									else
									{
										// CONSULTA LOS VENTAS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
										AjaxConsulta('../logica/rpt_ventas.php', {sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_ventas_socio_jva_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
									}
								}
								else
								{
									alert('Seleccione fecha fin para realizar la busqueda');
								}
							}
							else
							{
								if(svededoresjva != 0)
								{
									// CONSULTA LOS VENTAS DEL JAVA SELECCIONADO Y EL VENDEDOR SELECCIONADO
									AjaxConsulta('../logica/rpt_ventas.php', {svededoresjva:svededoresjva, sjva:sjva, ACCION:'consultar_ventas_socio_jva_vendedor'}, 'rpt_reporteDiv');
								}
								else
								{
									// CONSULTA LOS VENTAS DEL JVA SELECCIONADO
									AjaxConsulta('../logica/rpt_ventas.php', {sjva:sjva, ACCION:'consultar_ventas_socio_jva'}, 'rpt_reporteDiv');
								}
							}
						}
						else
						{
							// SI NO SE SELECCIONA UN JVA PUEDE CONSULTAR TODOS LOS JVA CON FECHA INICIO - FIN
							if(Fecha_inicio != 0)
							{
								if(Fecha_fin != 0)
								{
									// CONSULTA TODOS LOS JVA CON FECHA INICIO - FIN
									AjaxConsulta('../logica/rpt_ventas.php', {Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_ventas_socio_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
								}
								else
								{
									alert('Seleccione fecha fin para realizar la busqueda');
								}
							}
							else
							{
								// CONSULTA LAS VENTAS DEL JVA SELECCIONADO
								AjaxConsulta('../logica/rpt_ventas.php', {ACCION:'consultar_ventas_socio'}, 'rpt_reporteDiv');
							}
						}
					}
				}
			}
			else
			{
				alert("Seleccione el reporte a generar.");
			}

		}
		/* REPORTES CONSOLIDADOS */
		if(scon_deta == "consolidado")
		{
			// SI EL REPORTE A REALIZAR ES DIFERENTE DE 0
			if(sTipoReporte != 0)
			{
				/* SI EL REPORTE ES GASTOS */
				if(sTipoReporte == "gastos")
				{
					// SI EL JVA ES DIFERENTE 0
					if(sjva != 0)
					{
						// SI FECHA FIN ES DIFERENTE DE 0
						if(Fecha_inicio != 0)
						{
							// SI FECHA FIN ES DIFERENTE DE 0
							if(Fecha_fin != 0)
							{
								if(svededoresjva != 0)
								{
									// CONSULTA LOS GASTOS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
									AjaxConsulta('../logica/rpt_gastos.php', {svededoresjva:svededoresjva, sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_gastos_socio_jva_vendedor_fechaInicio_fechaFin_consolidado'}, 'rpt_reporteDiv');
								}
								else
								{
									// CONSULTA LOS GASTOS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
									AjaxConsulta('../logica/rpt_gastos.php', {sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_gastos_socio_jva_fechaInicio_fechaFin_consolidado'}, 'rpt_reporteDiv');
								}
							}
							else
							{
								alert('Seleccione fecha fin para realizar la busqueda');
							}
						}
						else
						{
							// SI NO SE SELECCIONA UN JVA PUEDE CONSULTAR TODOS LOS JVA CON FECHA INICIO - FIN
							if(svededoresjva != 0)
							{
								// CONSULTA LOS GASTOS DEL JAVA SELECCIONADO Y EL VENDEDOR SELECCIONADO
								AjaxConsulta('../logica/rpt_gastos.php', {svededoresjva:svededoresjva, sjva:sjva, ACCION:'consultar_gastos_socio_jva_vendedor_consolidado'}, 'rpt_reporteDiv');
							}
							else
							{
								// CONSULTA LOS GASTOS DEL JVA SELECCIONADO
								AjaxConsulta('../logica/rpt_gastos.php', {sjva:sjva, ACCION:'consultar_gastos_socio_jva_consolidado'}, 'rpt_reporteDiv');
							}
						}
					}
					else
					{
						//SI LA FECHA DE INICIO ES DISTINTA DE 0 ENTRA
						if(Fecha_inicio != 0)
						{
							if(Fecha_fin != 0)
							{
								AjaxConsulta('../logica/rpt_gastos.php', {Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_gastos_socio_fechaInicio_fechaFin_consolidado'}, 'rpt_reporteDiv');
							}
						}
						else
						{
							// CONSULTA TODOS LOS GASTOS DE LOS VENDEDORES DE ESE SOCIO
							AjaxConsulta('../logica/rpt_gastos.php', {ACCION:'consultar_gastos_socio_consolidado'}, 'rpt_reporteDiv');
						}
					}
				}
				/* SI EL REPORTE ES RECAUDOS */
				if(sTipoReporte == "recaudos")
				{
					/* SI EL CAMPO CLIENTE CONTIENE CARACTERES HACE LA CONSULTA SOLO A ESE CLIENTE */
					if(cliente.length > 0)
					{
						// CONSULTA LOS RECAUDOS DEL VENDEDOR INDICADO
						AjaxConsulta('../logica/rpt_recaudos.php', {cliente:cliente, ACCION:'consultar_recaudos_socio_cliente'}, 'rpt_reporteDiv');
					}
					else
					{
						// SI EL JVA ES DIFERENTE 0
						if(sjva != 0)
						{
							if(Fecha_inicio != 0)
							{
								// SI FECHA FIN ES DIFERENTE DE 0
								if(Fecha_fin != 0)
								{
									if(svededoresjva != 0)
									{
										// CONSULTA LOS RECAUDOS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
										AjaxConsulta('../logica/rpt_recaudos.php', {svededoresjva:svededoresjva, sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_recaudos_socio_jva_vendedor_fechaInicio_fechaFin_consolidado'}, 'rpt_reporteDiv');
									}
									else
									{
										// CONSULTA LOS RECAUDOS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
										AjaxConsulta('../logica/rpt_recaudos.php', {sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_recaudos_socio_jva_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
									}
								}
								else
								{
									alert('Seleccione fecha fin para realizar la busqueda');
								}
							}
							else
							{
								if(svededoresjva != 0)
								{
									// CONSULTA LOS RECAUDOS DEL JAVA SELECCIONADO Y EL VENDEDOR SELECCIONADO
									AjaxConsulta('../logica/rpt_recaudos.php', {svededoresjva:svededoresjva, sjva:sjva, ACCION:'consultar_recaudos_socio_jva_vendedor'}, 'rpt_reporteDiv');
								}
								else
								{
									// CONSULTA LOS RECAUDOS DEL JVA SELECCIONADO
									AjaxConsulta('../logica/rpt_recaudos.php', {sjva:sjva, ACCION:'consultar_recaudos_socio_jva'}, 'rpt_reporteDiv');
								}
							}
						}
						else
						{
							// SI NO SE SELECCIONA UN JVA PUEDE CONSULTAR TODOS LOS JVA CON FECHA INICIO - FIN
							if(Fecha_inicio != 0)
							{
								if(Fecha_fin != 0)
								{
									// CONSULTA TODOS LOS JVA CON FECHA INICIO - FIN
									AjaxConsulta('../logica/rpt_recaudos.php', {Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_recaudos_socio_fechaInicio_fechaFin_consolidado'}, 'rpt_reporteDiv');
								}
								else
								{
									alert('Seleccione fecha fin para realizar la busqueda');
								}
							}
							else
							{
								// SI NO SE SELECCIONA UN JVA PUEDE CONSULTAR TODOS LOS JVA DEL SOCIO
								AjaxConsulta('../logica/rpt_recaudos.php', {ACCION:'consultar_recaudos_socio'}, 'rpt_reporteDiv');
							}
						}
					}
				}
				/* SI EL REPORTE ES SALDOS */
				if(sTipoReporte == "saldos")
				{
					/* SI EL CAMPO CLIENTE CONTIENE CARACTERES HACE LA CONSULTA SOLO A ESE CLIENTE */ 
					if(cliente.length > 0)
					{
						// CONSULTA LOS SALDOS DEL VENDEDOR INDICADO
						AjaxConsulta('../logica/rpt_saldos.php', {cliente:cliente, ACCION:'consultar_saldo_cliente'}, 'rpt_reporteDiv');
					}
					else
					{
						// SI EL JVA ES DIFERENTE 0
						if(sjva != 0)
						{
							if(Fecha_inicio != 0)
							{
								// SI FECHA FIN ES DIFERENTE DE 0
								if(Fecha_fin != 0)
								{
									if(svededoresjva != 0)
									{
										// CONSULTA LOS SALDOS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
										AjaxConsulta('../logica/rpt_saldos.php', {svededoresjva:svededoresjva, sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_saldos_socio_jva_vendedor_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
									}
									else
									{
										// CONSULTA LOS SALDOS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
										AjaxConsulta('../logica/rpt_saldos.php', {sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_saldos_socio_jva_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
									}
								}
								else
								{
									alert('Seleccione fecha fin para realizar la busqueda');
								}
							}
							else
							{
								if(svededoresjva != 0)
								{
									// CONSULTA LOS SALDOS DEL JAVA SELECCIONADO Y EL VENDEDOR SELECCIONADO
									AjaxConsulta('../logica/rpt_saldos.php', {svededoresjva:svededoresjva, sjva:sjva, ACCION:'consultar_saldos_socio_jva_vendedor'}, 'rpt_reporteDiv');
								}
								else
								{
									// CONSULTA LOS SALDOS DEL JVA SELECCIONADO
									AjaxConsulta('../logica/rpt_saldos.php', {sjva:sjva, ACCION:'consultar_saldos_socio_jva'}, 'rpt_reporteDiv');
								}
							}
						}
						else
						{
							// SI NO SE SELECCIONA UN JVA PUEDE CONSULTAR TODOS LOS JVA CON FECHA INICIO - FIN
							if(Fecha_inicio != 0)
							{
								if(Fecha_fin != 0)
								{
									// CONSULTA TODOS LOS JVA CON FECHA INICIO - FIN
									AjaxConsulta('../logica/rpt_saldos.php', {Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_saldo_socio_fechaInicio_fechaFin'}, 'rpt_reporteDiv');
								}
								else
								{
									alert('Seleccione fecha fin para realizar la busqueda');
								}
							}
							else
							{
								// CONSULTA LOS SALDOS DEL JVA SELECCIONADO
								AjaxConsulta('../logica/rpt_saldos.php', {ACCION:'consultar_saldo_socio'}, 'rpt_reporteDiv');
							}
						}
					}
				}
				/* SI EL REPORTE ES VENTAS */
				if(sTipoReporte == "ventas")
				{
					/* SI EL CAMPO CLIENTE CONTIENE CARACTERES HACE LA CONSULTA SOLO A ESE CLIENTE */ 
					if(cliente.length > 0)
					{
						// CONSULTA LAS VENTAS DEL VENDEDOR INDICADO
						AjaxConsulta('../logica/rpt_ventas.php', {cliente:cliente, ACCION:'consultar_ventas_cliente'}, 'rpt_reporteDiv');
					}
					else
					{
						// SI EL JVA ES DIFERENTE 0
						if(sjva != 0)
						{
							if(Fecha_inicio != 0)
							{
								// SI FECHA FIN ES DIFERENTE DE 0
								if(Fecha_fin != 0)
								{
									if(svededoresjva != 0)
									{
										// CONSULTA LOS VENTAS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
										AjaxConsulta('../logica/rpt_ventas.php', {svededoresjva:svededoresjva, sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_ventas_socio_jva_vendedor_fechaInicio_fechaFin_consolidado'}, 'rpt_reporteDiv');
									}
									else
									{
										// CONSULTA LOS VENTAS DEL JVA SELECCIONADO CON EL VENDEDOR SELECCIONADO Y LA FECHA INICIO Y FECHA FIN DEL GASTO
										AjaxConsulta('../logica/rpt_ventas.php', {sjva:sjva, Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_ventas_socio_jva_fechaInicio_fechaFin_consolidado'}, 'rpt_reporteDiv');
									}
								}
								else
								{
									alert('Seleccione fecha fin para realizar la busqueda');
								}
							}
							else
							{
								if(svededoresjva != 0)
								{
									// CONSULTA LOS VENTAS DEL JAVA SELECCIONADO Y EL VENDEDOR SELECCIONADO
									AjaxConsulta('../logica/rpt_ventas.php', {svededoresjva:svededoresjva, sjva:sjva, ACCION:'consultar_ventas_socio_jva_vendedor'}, 'rpt_reporteDiv');
								}
								else
								{
									// CONSULTA LOS VENTAS DEL JVA SELECCIONADO
									AjaxConsulta('../logica/rpt_ventas.php', {sjva:sjva, ACCION:'consultar_ventas_socio_jva'}, 'rpt_reporteDiv');
								}
							}
						}
						else
						{
							// SI NO SE SELECCIONA UN JVA PUEDE CONSULTAR TODOS LOS JVA CON FECHA INICIO - FIN
							if(Fecha_inicio != 0)
							{
								if(Fecha_fin != 0)
								{
									// CONSULTA TODOS LOS JVA CON FECHA INICIO - FIN
									AjaxConsulta('../logica/rpt_ventas.php', {Fecha_inicio:Fecha_inicio, Fecha_fin:Fecha_fin, ACCION:'consultar_ventas_socio_fechaInicio_fechaFin_consolidado'}, 'rpt_reporteDiv');
								}
								else
								{
									alert('Seleccione fecha fin para realizar la busqueda');
								}
							}
							else
							{
								// CONSULTA LAS VENTAS DEL JVA SELECCIONADO
								AjaxConsulta('../logica/rpt_ventas.php', {ACCION:'consultar_ventas_socio'}, 'rpt_reporteDiv');
							}
						}
					}
				}
			}
			else
			{
				alert('Seleccione el reporte a generar');
			}
		}
	}
	else
	{
		alert('Seleccione reporte consolidado o detallado');
	}
	
}

function reportes(valor)
{
	if(valor == "gastos")
	{
		$('#cliente').attr('readonly', true);
		$('#fecha_inicio').prop('disabled', false);
		$('#fecha_fin').prop('disabled', false);
	}
	if(valor == "saldos")
	{
		$('#cliente').attr('readonly', false);
		$('#fecha_inicio').prop('disabled', true);
		$('#fecha_fin').prop('disabled', true);
	}
	if(valor == "recaudos")
	{
		$('#cliente').attr('readonly', false);
		$('#fecha_inicio').prop('disabled', false);
		$('#fecha_fin').prop('disabled', false);
	}
	if(valor == "ventas")
	{
		$('#cliente').attr('readonly', false);
		$('#fecha_inicio').prop('disabled', false);
		$('#fecha_fin').prop('disabled', false);
	}
	if(valor == "saldos_ruta")
	{
		$('#cliente').attr('readonly', true);
		$('#fecha_inicio').prop('disabled', true);
		$('#fecha_fin').prop('disabled', true);
		$("#scon_deta option[value=consolidado]").attr("selected",true);
	}
	if(valor == "balance_ruta")
	{
		$('#cliente').attr('disabled', true);
		$('#fecha_fin').prop('disabled', true);
		$("#scon_deta option[value=consolidado]").attr("selected",true);
	}
	if (valor == "mensual_general")
	{
		$('#cliente').attr('readonly', true);
		$('#fecha_fin').prop('disabled', true);
		$("#scon_deta option[value=consolidado]").attr("selected",true);
	};
}
