<?php
/*
 * @author:	ANDRES FELIPE BARRERA VELASQUEZ
 * 			afbarrerav@hotmail.com
 * @version:2.0.0
 * @fecha:	Enero de 2013
 *
 * */
//session_start();
include_once ("../logica/variables_session.php");
$RowCount = 0;
try
{
	/*SE CREA LA INSTANCIA DEL OBJETO, SE REALIZA LA CONEXION A LA BD*/
	$db_link = new PDO($dsn, $username, $passwd);
	$accion = $_REQUEST['ACCION'];
	$msg="";
	$fecha = date('Y-m-d');
	$fecha_desde = $fecha." 00:00:00";
	$fecha_hasta = $fecha." 23:59:59";
	
	if($accion == "balance_ruta")
	{
		$vendedor 		= $_REQUEST['svededoresjva'];
		$jva 			= $_REQUEST['sjva'];
		$fecha_inicio 	= $_REQUEST['Fecha_inicio'];
		$fechaHora1		= $fecha_inicio." 00:00:00";
		$fechaHora2		= $fecha_inicio." 23:59:59";
		
		/*
		 *CONSULTAMOS FECHA Y HORA DE LOGIN
		 */
		$query_login = "SELECT min(fecha_trans) as login
						FROM logs_accesos_usuarios
						WHERE aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo = $vendedor)
						AND fecha_trans like '$fecha_inicio%'
						AND accion = 'login'";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result_login = $db_link->prepare($query_login);
		$result_login->execute(); //SE EJECUTA EL QUERY
		$arr_login = $result_login->errorInfo(); // SE OBTIENE EL ERROR
		$error_login = $arr_login[0];
		$errorMessage_login = str_replace("'", "", $arr_login[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error_login=="00000")
		{
			$row_login = $result_login->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$login = substr($row_login['login'],11, 8);
		}
		else
		{
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $errorMessage_login?>");
			</script>
			<?php
		}
		/*CONSULTAMOS FECHA Y HORA DE LOGOUT
		*/
		$query_logout = "SELECT max(fecha_trans) as logout
						FROM logs_accesos_usuarios
						WHERE aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo =$vendedor)
						AND fecha_trans like '$fecha_inicio%'
						AND accion = 'logout'";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result_logout = $db_link->prepare($query_logout);
		$result_logout->execute(); //SE EJECUTA EL QUERY
		$arr_logout = $result_logout->errorInfo(); // SE OBTIENE EL ERROR
		$error_logout = $arr_logout[0];
		$errorMessage_logout = str_replace("'", "", $arr_logout[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error_logout=="00000")
		{
			$row_logout = $result_logout->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$logout = substr($row_logout['logout'],11, 8);
		}
		else
		{
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $errorMessage_logout?>");
			</script>
			<?php
		}
		/*
		 *CONSULTAMOS SALDO INICIAL
		 */
		$query_SaldoInicial = "select sum(saldo_producto) saldo_producto
								from (select tv.codigo, sum(tv.valor_total-vr.valor_recaudo) saldo_producto
								from trans_ventas tv, (SELECT TV_CODIGO, SUM( valor_recaudo ) valor_recaudo
								FROM  trans_detalle_recaudo_ventas_jva
								WHERE FECHA_RECAUDO <=  '$fechaHora1'
								and aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo = $vendedor)
								GROUP BY tv_codigo) vr
								where tv.codigo = vr.tv_codigo
								group by tv.codigo) sd";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result_SaldoInicial = $db_link->prepare($query_SaldoInicial);
		$result_SaldoInicial->execute(); //SE EJECUTA EL QUERY
		$arr_SaldoInicial = $result_SaldoInicial->errorInfo(); // SE OBTIENE EL ERROR
		$error_SaldoInicial = $arr_SaldoInicial[0];
		$errorMessage_SaldoInicial = str_replace("'", "", $arr_SaldoInicial[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error_SaldoInicial=="00000")
		{
			$row_SaldoInicial = $result_SaldoInicial->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$SaldoInicial = $row_SaldoInicial['saldo_producto'];
		}
		else
		{
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $errorMessage_SaldoInicial?>");
			</script>
			<?php
		}
		/*CONSULTAMOS SALDO FINAL
		*/
		$query_SaldoFinal = "select sum(saldo_producto) saldo_producto
								from (select tv.codigo, sum(tv.valor_total-vr.valor_recaudo) saldo_producto
								from trans_ventas tv, (SELECT TV_CODIGO, SUM( valor_recaudo ) valor_recaudo
								FROM  trans_detalle_recaudo_ventas_jva
								WHERE FECHA_RECAUDO <=  '$fechaHora2'
								and aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo = $vendedor)
								GROUP BY tv_codigo) vr
								where tv.codigo = vr.tv_codigo
								group by tv.codigo) sd";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result_SaldoFinal = $db_link->prepare($query_SaldoFinal);
		$result_SaldoFinal->execute(); //SE EJECUTA EL QUERY
		$arr_SaldoFinal = $result_SaldoFinal->errorInfo(); // SE OBTIENE EL ERROR
		$error_SaldoFinal = $arr_SaldoFinal[0];
		$errorMessage_SaldoFinal = str_replace("'", "", $arr_SaldoFinal[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error_SaldoFinal=="00000")
		{
			$row_SaldoFinal = $result_SaldoFinal->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$SaldoFinal = $row_SaldoFinal['saldo_producto'];
		}
		else
		{
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $errorMessage_SaldoFinal?>");
			</script>
			<?php
		}
		/*VALOR A RECAUDAR EN EL DIA
		*/
		$query_Recaudar = "SELECT sum(valor_pago) valor_pago
								FROM (SELECT distinct tv_codigo, valor_pago
										FROM trans_detalle_recaudo_ventas_jva
										WHERE tv_codigo in (SELECT tv_codigo 
															FROM trans_rutas_detalles 
															WHERE aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo =  $vendedor) and est_codigo = 1)
										AND est_codigo in (3, 5, 10)
										AND fecha_pago <= '$fechaHora2'
										ORDER BY cuota_nro) valor_pago";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result_Recaudar = $db_link->prepare($query_Recaudar);
		$result_Recaudar->execute(); //SE EJECUTA EL QUERY
		$arr_Recaudar = $result_Recaudar->errorInfo(); // SE OBTIENE EL ERROR
		$error_Recaudar = $arr_Recaudar[0];
		$errorMessage_Recaudar = str_replace("'", "", $arr_Recaudar[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error_Recaudar=="00000")
		{
			$row_Recaudar = $result_Recaudar->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$Recaudar = $row_Recaudar['valor_pago'];
		}
		else
		{
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $errorMessage_Recaudar?>");
			</script>
			<?php
		}
		/*CONSULTAMOS EL VALOR RECAUDADO 
		*/
		$valor_recaudado = 0;
		$nro_clientes_recaudados = 0;
		$query_recaudado = "select count(tv_codigo) nro_clientes_recaudados, sum(valor_recaudo) valor_recaudado
							from (SELECT tv_codigo, fecha_recaudo, sum(valor_recaudo) valor_recaudo
								FROM trans_historico_recaudo_ventas_jva
								WHERE aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo =  $vendedor)
								AND fecha_recaudo BETWEEN '$fechaHora1' AND '$fechaHora2'
								group by tv_codigo, fecha_recaudo) valor_recaudo";
		//echo $query_recaudado;
		$result_recaudado = $db_link->prepare($query_recaudado);
		$db_link->beginTransaction();
		$result_recaudado->execute(); //SE EJECUTA EL QUERY
		$arr_recaudado = $result_recaudado->errorInfo(); // SE OBTIENE EL ERROR
		$error_recaudado = $arr_recaudado[0];
		$errorMessage_recaudado = str_replace("'", "", $arr_recaudado[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error_recaudado == "00000")
		{								
			
			$db_link->commit();		
			$row_recaudado 				= $result_recaudado->fetch(PDO::FETCH_ASSOC);
			$valor_recaudado 			= $valor_recaudado + $row_recaudado['valor_recaudado'];
			$nro_clientes_recaudados 	= $nro_clientes_recaudados + $row_recaudado['nro_clientes_recaudados'];
			//echo "Valor/clientes recaudados: $valor_recaudado /  $nro_clientes_recaudados ".date('Y-m-d H:i:s')."<br>";
		}
		else
		{
			$db_link->rollBack();
			?>
				<script>alert("Error al consultar los valores recaudados <?php echo $error_recaudado." ".$errorMessage_recaudado?>");</script>
			<?php
		}
		$SaldoFinal2 = $SaldoInicial - $valor_recaudado;
		$hora_primer_recaudo	= "";
		/*
		 * OBTENEMOS LA HORA DEL PRIMER RECAUDO
		 */
		$query = "SELECT min(fecha_recaudo) as valor
						FROM trans_historico_recaudo_ventas_jva
						WHERE aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo = $vendedor)
						AND fecha_recaudo like '$fecha_inicio%'";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$hora_primer_recaudo = substr($row['valor'],11, 8);
		}
		else
		{
			?>
			<script>
				alert("Error al consultar la hora del primer recaudo: <?php echo $errorMessage?>");
			</script>
			<?php
		}
		$hora_ultimo_recaudo	= "";
		
		/*
		 * OBTENEMOS LA HORA DEL ULTIMO RECAUDO
		 */
		$query = "SELECT max(fecha_recaudo) as valor
						FROM trans_historico_recaudo_ventas_jva
						WHERE aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo = $vendedor)
						AND fecha_recaudo like '$fecha_inicio%'";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$hora_ultimo_recaudo = substr($row['valor'],11, 8);
		}
		else
		{
			?>
			<script>
				alert("Error al consultar la hora del ultimo recaudo: <?php echo $errorMessage?>");
			</script>
			<?php
		}
		$hora_primer_venta		= "";		
		/*
		 * OBTENEMOS LA HORA DE LA PRIMER VENTA
		 */
		$query = "SELECT min(fecha_entrega) as valor
						FROM trans_ventas
						WHERE aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo = $vendedor)
						AND fecha_entrega like '$fecha_inicio%'";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$hora_primer_venta = substr($row['valor'],11, 8);
		}
		else
		{
			?>
			<script>
				alert("Error al consultar la hora de la primer venta: <?php echo $errorMessage?>");
			</script>
			<?php
		}
		$hora_ultimo_venta		= "";
		/*
		 * OBTENEMOS LA HORA DE LA ULTIMA VENTA
		 */
		$query = "SELECT max(fecha_entrega) as valor
						FROM trans_ventas
						WHERE aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo = $vendedor)
						AND fecha_entrega like '$fecha_inicio%'";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$hora_ultimo_venta = substr($row['valor'],11, 8);
		}
		else
		{
			?>
			<script>
				alert("Error al consultar la hora de la ultima venta: <?php echo $errorMessage?>");
			</script>
			<?php
		}
		$nro_clientes_inicial	= "";
		/*
		 * OBTENEMOS EL NUMERO DE CLIENTES AL INICIAR
		 */
		$query = "SELECT count(tv_codigo) valor
					FROM (SELECT distinct tv_codigo, valor_pago
							FROM trans_detalle_recaudo_ventas_jva
							WHERE tv_codigo in (SELECT tv_codigo 
												FROM trans_rutas_detalles 
												WHERE aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo =  $vendedor) and est_codigo = 1)
							AND est_codigo in (3, 4, 5, 10)
							AND fecha_pago <= '$fechaHora1'
							ORDER BY cuota_nro) valor_pago";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$nro_clientes_inicial = $row['valor'];
		}
		else
		{
			?>
			<script>
				alert("Error al consultar la cantidad de clientes al iniciar el dia: <?php echo $errorMessage?>");
			</script>
			<?php
		}
		$nro_clientes_final		= "";
		/*
		 * OBTENEMOS EL NUMERO DE CLIENTES AL FINALIZAR
		 */
		$query = "SELECT count(tv_codigo) valor
					FROM (SELECT distinct tv_codigo, valor_pago
							FROM trans_detalle_recaudo_ventas_jva
							WHERE tv_codigo in (SELECT tv_codigo 
												FROM trans_rutas_detalles 
												WHERE aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo =  $vendedor) and est_codigo = 1)
							AND est_codigo in (3, 4, 5, 10)
							AND fecha_pago <= '$fechaHora2'
							ORDER BY cuota_nro) valor_pago";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$nro_clientes_final = $row['valor'];
		}
		else
		{
			?>
			<script>
				alert("Error al consultar la cantidad de clientes al finalizar el dia: <?php echo $errorMessage?>");
			</script>
			<?php
		}
		$cargue_inicial			= "0";		
		/*
		 * OBTENEMOS EL CARGUE DEL DIA
		 */
		$query = "SELECT sum(cantidad) as valor
						FROM trans_traslados_inventarios_jva
						WHERE aju_codigo_hasta in (select codigo from admin_jva_usuarios where usu_codigo = $vendedor)
						AND  fecha_traslado like '$fecha_inicio%'
						and ptij_codigo = 2";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$cargue_inicial = $row['valor'];
		}
		else
		{
			?>
			<script>
				alert("Error al consultar el cargue inicial: <?php echo $errorMessage?>");
			</script>
			<?php
		}
		$gasto_total			= "0";
		/*
		 * OBTENEMOS EL VALOR DE LOS GASTOS
		 */
		$query = "SELECT sum(valor) as valor
						FROM trans_gastos_jva
						WHERE aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo = $vendedor)
						AND fecha_gasto like '$fecha_inicio%'";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$gasto_total = $row['valor'];
		}
		else
		{
			?>
			<script>
				alert("Error al consultar el total de gastos del dia: <?php echo $errorMessage?>");
			</script>
			<?php
		}
		$ventas_total = "0";
		/*
		 * OBTENEMOS EL TOTAL DE LAS VENTAS DEL DIA
		 */
		$query = "SELECT sum(valor_producto) as valor
						FROM trans_ventas
						WHERE aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo = $vendedor)
						AND fecha_entrega between '$fechaHora1' and '$fechaHora2'";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$ventas_total = $row['valor'];
		}
		else
		{
			?>
			<script>
				alert("Error al consultar el valor de las ventas del dia: <?php echo $errorMessage?>");
			</script>
			<?php
		}
		/*
		 * OBTENEMOS EL EFECTIVO A ENTREGAR
		 */

		$valor_efectivo_entregar = ($valor_recaudado+$cargue_inicial) - ($ventas_total + $gasto_total);
		include '../presentacion/rpt_balance_ruta.php';
	}
}
catch (PDOException $e)
{
	$msg = $e->getMessage();
	?>
	<script>
		alert("Excepcion controlada: <?php echo $msg?>");
		location.reload(true);
	</script>
	<?php 
}
?>