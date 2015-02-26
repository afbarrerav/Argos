<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Octubre de 2012
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
	
	if($accion == "mostrar_front_gastos")
	{
		/*
		 * OBTIENE LOS JVA DEL USUARIO
		 * */
		$query_jva = "SELECT aju.jva_codigo, aj.nombre
						FROM admin_jva_usuarios aju, admin_jva aj
						WHERE usu_codigo =:usu_codigo 
						and rol_codigo in (2, 3)
						and aju.jva_codigo = aj.codigo";
		$result_jva = $db_link->prepare($query_jva);
		$db_link->beginTransaction();
		$result_jva->bindParam(':usu_codigo',$usu_codigo);
		$result_jva->execute(); //SE EJECUTA EL QUERY
		$arr_jva = $result_jva->errorInfo(); // SE OBTIENE EL ERROR
		$error_jva = $arr_jva[0];
		$errorMessage_jva = str_replace("'", "", $arr_jva[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		$RowCount=0;
		if($error_jva == "00000")
		{
			
			$db_link->commit();			
		 	while($row_jva = $result_jva->fetch(PDO::FETCH_ASSOC))
		 	{
		 		$jva_codigo = $row_jva['jva_codigo'];
		 		$jva_nombre = $row_jva['nombre'];
		 		$resultado[$RowCount]['jva_codigo'] = $jva_codigo;
		 		$resultado[$RowCount]['jva_nombre'] = $jva_nombre;	 			
				/*
				 * OBTIENE LOS GASTOS DEL JVA
				 * */
				$query_gastos = "select count(codigo) as cant_gastos, sum(valor) as valor
								from trans_gastos_jva
								WHERE aju_codigo IN (SELECT codigo FROM admin_jva_usuarios where jva_codigo = $jva_codigo) 
								AND fecha_gasto = '$fecha'
								AND est_codigo = 1";
				$result_gastos = $db_link->prepare($query_gastos);
				$db_link->beginTransaction();
				$result_gastos->execute(); //SE EJECUTA EL QUERY
				$arr_gastos = $result_gastos->errorInfo(); // SE OBTIENE EL ERROR
				$error_gastos = $arr_gastos[0];
				$errorMessage_gastos = str_replace("'", "", $arr_gastos[2]);
				$cantidadGastos = 0;
				$valor_gastos = 0;
				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error_gastos == "00000")
				{					
					$db_link->commit();
					$row_gastos = $result_gastos->fetch(PDO::FETCH_ASSOC);
					$cantidadGastos 	=$row_gastos['cant_gastos'];
					$valor_gastos		=$row_gastos['valor'];
					$resultado[$RowCount]['cant_gastos']  	= $cantidadGastos;
					$resultado[$RowCount]['valor'] 		  	= $valor_gastos;
				}
				else
				{
					$db_link->rollBack();
					?>
						<script>alert("Error al consultar los gastos del JVA <?php echo $error_gastos." ".$errorMessage_gastos?>");</script>
					<?php
				}
				$RowCount++;		
		 	}
		}
		else
		{
			$db_link->rollBack();
			?>
				<script>alert("Error al consultar los JVA's <?php echo $error_jva." ".$errorMessage_jva?>");</script>
			<?php
		}		
		include '../presentacion/rpt_gastos.php';
	}

	if($accion == "detalle_gastos")
	{
		$div = $_REQUEST['DIV'];
		/*
		 * OBTIENE LOS JVA DEL USUARIO
		 * */
		$query_jva = "SELECT aju.jva_codigo, aj.nombre
						FROM admin_jva_usuarios aju, admin_jva aj
						WHERE usu_codigo =:usu_codigo 
						and rol_codigo=2
						and aju.jva_codigo = aj.codigo";
		$result_jva = $db_link->prepare($query_jva);
		$db_link->beginTransaction();
		$result_jva->bindParam(':usu_codigo',$usu_codigo);
		$result_jva->execute(); //SE EJECUTA EL QUERY
		$arr_jva = $result_jva->errorInfo(); // SE OBTIENE EL ERROR
		$error_jva = $arr_jva[0];
		$errorMessage_jva = str_replace("'", "", $arr_jva[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		$RowCount=0;
		if($error_jva == "00000")
		{
			
			$db_link->commit();			
		 	while($row_jva = $result_jva->fetch(PDO::FETCH_ASSOC))
		 	{
		 		$jva_codigo = $row_jva['jva_codigo'];
		 		$jva_nombre = $row_jva['nombre'];
				/*
				 * 1. OBTENER LOS GASTOS POR VENDEDOR
				 * */
				$query = "SELECT aju_codigo, fun_obtener_aju_nombre(aju_codigo) aju_nombre, count(codigo) cant_gastos, sum(valor) valor
							FROM trans_gastos_jva trj
							WHERE aju_codigo in (SELECT aju_codigo FROM admin_jva_usuarios WHERE jva_codigo = '$jva_codigo') 
							AND fecha_gasto = '$fecha'
							GROUP BY 1, 2";
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
				if($error == "00000")
				{
					$RowCount = 0;
					while($row = $result->fetch(PDO::FETCH_ASSOC))
					{
						$detalle[$RowCount]['jva_codigo'] 	= $jva_codigo;
		 				$detalle[$RowCount]['jva_nombre'] 	= $jva_nombre;
						$detalle[$RowCount]['aju_codigo'] 	= $row['aju_codigo'];
			 			$detalle[$RowCount]['aju_nombre'] 	= $row['aju_nombre'];
						$detalle[$RowCount]['cant_gastos']	= $row['cant_gastos'];
			 			$detalle[$RowCount]['valor'] 		= $row['valor'];
						$RowCount++;
					}
				}
				else
				{
					?>
					<script>alert('Error los gastos del vendedor <?php echo $error." ".$errorMessage?>');</script>
					<?php
				}
			}
		}
		else
		{
			$db_link->rollBack();
			?>
				<script>alert("Error al consultar los JVA's <?php echo $error_jva." ".$errorMessage_jva?>");</script>
			<?php
		}	
		include ('../presentacion/rpt_gastos.php');	
	}
	
	
	if ($accion=="detalle_gastos_ruta")
	{
		/*
		 * CODIGO DEL VENDEDOR Y NOMBRE DE LA RUTA PARA MONSTRAR EN PRESENTACION DETALLE
		 * */
		$ven_codigo = $_REQUEST['VEN_CODIGO'];
		$ruta		= $_REQUEST['RUTA'];
		/*
		 * CONSULTAMOS EL DETALLE DE LAS VENTAS REALIZADAS POR EL VENDEDOR DE LA RUTA 
		 * */
		$query = "SELECT tgj.codigo, tgj.fecha_gasto, tgj.fecha_trans, tgj.aju_codigo, CONCAT(au.nombres,' ',au.apellidos) as ven_nombre, tgj.valor
					FROM trans_gastos_jva tgj, admin_usuarios au, admin_jva_usuarios aju
					WHERE tgj.aju_codigo = '$ven_codigo'
					AND aju.codigo = tgj.aju_codigo
					AND tgj.fecha_trans like '%$fecha%'
					AND aju.usu_codigo = au.codigo";
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
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_gasto'][$RowCount] 					= $row['fecha_gasto'];
				$detalle['fecha_trans'][$RowCount] 					= $row['fecha_trans'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor'][$RowCount] 						= $row['valor'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_gastos_detalle.php';
	}
	
	if($accion == "consultar_gastos_socio")
	{
		/*
		 * CONSULTA TODOS LOS GASTOS DEL SOCIO SEGUN EL JVA EN EL QUE SE ENCUENTRE
		 * */
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * 2. OBTENER LA CANTIDAD DE GASTOS REALIZADOS EN EL DIA
		 * 3. OBTENER EL TOTAL DE LOS GASTOS REALIZADOS EN EL DIA
		 * */
		
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * */
		$query = "SELECT trj.codigo rut_codigo, trj.jva_codigo, trj.nombre, trj.aju_codigo ven_codigo, aj.nombre jva_nombre
					FROM trans_rutas_jva trj, admin_jva aj
					WHERE jva_codigo IN (SELECT jva_codigo
											FROM admin_jva_usuarios
											WHERE rol_codigo in (2, 3)
											AND usu_codigo = $usu_codigo)
					AND aj.codigo = jva_codigo";
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
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['rut_codigo'][$RowCount] 		= $row['rut_codigo'];
				$detalle['jva_codigo'][$RowCount] 		= $row['jva_codigo'];
				$detalle['jva_nombre'][$RowCount] 		= $row['jva_nombre'];
				$detalle['nombre'][$RowCount] 			= $row['nombre'];
				$detalle['ven_codigo'][$RowCount] 		= $row['ven_codigo'];
				$ven_codigo = $detalle['ven_codigo'][$RowCount];
				$jva_nombre = $detalle['jva_nombre'][$RowCount];
				/*
				 * 2. OBTENER LA CANTIDAD DE VENTAS REALIZADAS EN EL DIA
				 * */
				$query_cantidad_gastos = "SELECT COUNT(codigo) as Gastos, sum(valor) as TotalGastos
											FROM `trans_gastos_jva` 
											WHERE aju_codigo = '$ven_codigo'";
				/*
				 * SE PREPARA EL QUERY
				 * */
				//echo $query_cantidad_gastos;
				//echo $ven_codigo;
				$result_cantidad_gastos = $db_link->prepare($query_cantidad_gastos);
				$result_cantidad_gastos->execute(); //SE EJECUTA EL QUERY
				$arr_cantidad_gastos = $result_cantidad_gastos->errorInfo(); // SE OBTIENE EL ERROR
				$error_cantidad_gastos = $arr_cantidad_gastos[0];
				$errorMessage_cantidad_gastos = str_replace("'", "", $arr_cantidad_gastos[2]);
				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error_cantidad_gastos == "00000")
				{
					$RowCount_cantidad_gastos = 0;
					$SumaGastos = 0;
					while($row_cantidad_gastos = $result_cantidad_gastos->fetch(PDO::FETCH_ASSOC))
					{
						$detalle_cantidad_gastos['Gastos'][$RowCount_cantidad_gastos] 			= $row_cantidad_gastos['Gastos'];
						$detalle_cantidad_gastos['TotalGastos'][$RowCount_cantidad_gastos] 		= $row_cantidad_gastos['TotalGastos'];
						$Gastos = $detalle_cantidad_gastos['Gastos'][$RowCount_cantidad_gastos];
						$totalgastos = $detalle_cantidad_gastos['TotalGastos'][$RowCount_cantidad_gastos];
						$SumaGastos = $SumaGastos +  $detalle_cantidad_gastos['TotalGastos'][$RowCount_cantidad_gastos];
						$RowCount_cantidad_gastos++;
						//echo $SumaGastos;
					}
				}
				else
				{
					?>
					<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
					<?php
				}
				$detalle['Gastos'][$RowCount] = $Gastos;
				$detalle['TotalGastos'][$RowCount] = $totalgastos;
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_gastos.php';
	}
	
	if($accion == "consultar_gastos_socio_fechaInicio_fechaFin")
	{
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTA LOS GASTOS SEGUN LAS FECHAS SELECCIONADAS
		 * */
		$query = "SELECT tgj.codigo, tgj.fecha_gasto, tgj.fecha_trans, tgj.aju_codigo, CONCAT(au.nombres,' ',au.apellidos) as ven_nombre, tgj.valor 
					FROM trans_gastos_jva tgj, admin_usuarios au, admin_jva_usuarios aju 
					WHERE aju.codigo = tgj.aju_codigo
					AND tgj.fecha_gasto BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59' 
					AND aju.usu_codigo = au.codigo";
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
		//echo $query;
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_gasto'][$RowCount] 					= $row['fecha_gasto'];
				$detalle['fecha_trans'][$RowCount] 					= $row['fecha_trans'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor'][$RowCount] 						= $row['valor'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_gastos.php';
	}
	
	if($accion == "consultar_gastos_socio_jva")
	{
		$jva = $_REQUEST['sjva'];
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * 2. OBTENER LA CANTIDAD DE GASTOS REALIZADOS EN EL DIA
		 * 3. OBTENER EL TOTAL DE LOS GASTOS REALIZADOS EN EL DIA
		 * */
		
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * */
		$query = "SELECT trj.codigo rut_codigo, trj.jva_codigo, trj.nombre, trj.aju_codigo ven_codigo, aj.nombre jva_nombre
					FROM trans_rutas_jva trj, admin_jva aj
					WHERE jva_codigo =  '$jva'
					AND aj.codigo = jva_codigo";
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
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['rut_codigo'][$RowCount] 		= $row['rut_codigo'];
				$detalle['jva_codigo'][$RowCount] 		= $row['jva_codigo'];
				$detalle['jva_nombre'][$RowCount] 		= $row['jva_nombre'];
				$detalle['nombre'][$RowCount] 			= $row['nombre'];
				$detalle['ven_codigo'][$RowCount] 		= $row['ven_codigo'];
				$ven_codigo = $detalle['ven_codigo'][$RowCount];
				$jva_nombre = $detalle['jva_nombre'][$RowCount];
				/*
				 * 2. OBTENER LA CANTIDAD DE VENTAS REALIZADAS EN EL DIA
				 * */
				$query_cantidad_gastos = "SELECT COUNT(codigo) as Gastos, sum(valor) as TotalGastos
											FROM `trans_gastos_jva` 
											WHERE aju_codigo = '$ven_codigo'";
				/*
				 * SE PREPARA EL QUERY
				 * */
				//echo $query_cantidad_gastos;
				//echo $ven_codigo;
				$result_cantidad_gastos = $db_link->prepare($query_cantidad_gastos);
				$result_cantidad_gastos->execute(); //SE EJECUTA EL QUERY
				$arr_cantidad_gastos = $result_cantidad_gastos->errorInfo(); // SE OBTIENE EL ERROR
				$error_cantidad_gastos = $arr_cantidad_gastos[0];
				$errorMessage_cantidad_gastos = str_replace("'", "", $arr_cantidad_gastos[2]);
				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error_cantidad_gastos == "00000")
				{
					$RowCount_cantidad_gastos = 0;
					$SumaGastos = 0;
					while($row_cantidad_gastos = $result_cantidad_gastos->fetch(PDO::FETCH_ASSOC))
					{
						$detalle_cantidad_gastos['Gastos'][$RowCount_cantidad_gastos] 			= $row_cantidad_gastos['Gastos'];
						$detalle_cantidad_gastos['TotalGastos'][$RowCount_cantidad_gastos] 		= $row_cantidad_gastos['TotalGastos'];
						$Gastos = $detalle_cantidad_gastos['Gastos'][$RowCount_cantidad_gastos];
						$totalgastos = $detalle_cantidad_gastos['TotalGastos'][$RowCount_cantidad_gastos];
						$SumaGastos = $SumaGastos +  $detalle_cantidad_gastos['TotalGastos'][$RowCount_cantidad_gastos];
						$RowCount_cantidad_gastos++;
						//echo $SumaGastos;
					}
				}
				else
				{
					?>
					<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
					<?php
				}
				$detalle['Gastos'][$RowCount] = $Gastos;
				$detalle['TotalGastos'][$RowCount] = $totalgastos;
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_gastos.php';
	}
	
	if($accion == "consultar_gastos_socio_jva_fechaInicio_fechaFin")
	{
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin	  = $_REQUEST['Fecha_fin'];
		$jva 		  = $_REQUEST['sjva'];
		/*
		 * CONSULTA LOS GASTOS SEGUN LAS FECHAS SELECCIONADAS
		 * */
		$query = "SELECT tgj.codigo, tgj.fecha_gasto, tgj.fecha_trans, tgj.aju_codigo, CONCAT(au.nombres,' ',au.apellidos) as ven_nombre, tgj.valor 
					FROM trans_gastos_jva tgj, admin_usuarios au, admin_jva_usuarios aju 
					WHERE aju.codigo = tgj.aju_codigo
					AND aju.jva_codigo = $jva
					AND tgj.fecha_gasto BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59' 
					AND aju.usu_codigo = au.codigo";
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
		//echo $query;
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_gasto'][$RowCount] 					= $row['fecha_gasto'];
				$detalle['fecha_trans'][$RowCount] 					= $row['fecha_trans'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor'][$RowCount] 						= $row['valor'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_gastos.php';
	}
	
	if($accion == "consultar_gastos_socio_jva_vendedor")
	{
		$jva = $_REQUEST['sjva'];
		$vendedor = $_REQUEST['svededoresjva'];
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * 2. OBTENER LA CANTIDAD DE GASTOS REALIZADOS EN EL DIA
		 * 3. OBTENER EL TOTAL DE LOS GASTOS REALIZADOS EN EL DIA
		 * */
		
		/*
		 * 1. OBTENER LA RUTA Y EL VENDEDOR
		 * */
		$query = "SELECT trj.codigo rut_codigo, trj.jva_codigo, trj.nombre, trj.aju_codigo ven_codigo, aj.nombre jva_nombre
					FROM trans_rutas_jva trj, admin_jva aj, admin_jva_usuarios aju
					WHERE trj.jva_codigo =  '$jva'
					AND trj.aju_codigo = aju.codigo
					AND trj.aju_codigo in (select codigo from admin_jva_usuarios where usu_codigo = $vendedor)
					AND aj.codigo = trj.jva_codigo";
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
		//echo $query;
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['rut_codigo'][$RowCount] 		= $row['rut_codigo'];
				$detalle['jva_codigo'][$RowCount] 		= $row['jva_codigo'];
				$detalle['jva_nombre'][$RowCount] 		= $row['jva_nombre'];
				$detalle['nombre'][$RowCount] 			= $row['nombre'];
				$detalle['ven_codigo'][$RowCount] 		= $row['ven_codigo'];
				$ven_codigo = $detalle['ven_codigo'][$RowCount];
				$jva_nombre = $detalle['jva_nombre'][$RowCount];
				/*
				 * 2. OBTENER LA CANTIDAD DE VENTAS REALIZADAS EN EL DIA
				 * */
				$query_cantidad_gastos = "SELECT COUNT(codigo) as Gastos, sum(valor) as TotalGastos
											FROM `trans_gastos_jva` 
											WHERE aju_codigo = '$ven_codigo'";
				/*
				 * SE PREPARA EL QUERY
				 * */
				//echo $query_cantidad_gastos;
				//echo $ven_codigo;
				$result_cantidad_gastos = $db_link->prepare($query_cantidad_gastos);
				$result_cantidad_gastos->execute(); //SE EJECUTA EL QUERY
				$arr_cantidad_gastos = $result_cantidad_gastos->errorInfo(); // SE OBTIENE EL ERROR
				$error_cantidad_gastos = $arr_cantidad_gastos[0];
				$errorMessage_cantidad_gastos = str_replace("'", "", $arr_cantidad_gastos[2]);
				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error_cantidad_gastos == "00000")
				{
					$RowCount_cantidad_gastos = 0;
					$SumaGastos = 0;
					while($row_cantidad_gastos = $result_cantidad_gastos->fetch(PDO::FETCH_ASSOC))
					{
						$detalle_cantidad_gastos['Gastos'][$RowCount_cantidad_gastos] 			= $row_cantidad_gastos['Gastos'];
						$detalle_cantidad_gastos['TotalGastos'][$RowCount_cantidad_gastos] 		= $row_cantidad_gastos['TotalGastos'];
						$Gastos = $detalle_cantidad_gastos['Gastos'][$RowCount_cantidad_gastos];
						$totalgastos = $detalle_cantidad_gastos['TotalGastos'][$RowCount_cantidad_gastos];
						$SumaGastos = $SumaGastos +  $detalle_cantidad_gastos['TotalGastos'][$RowCount_cantidad_gastos];
						$RowCount_cantidad_gastos++;
						//echo $SumaGastos;
					}
				}
				else
				{
					?>
					<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
					<?php
				}
				$detalle['Gastos'][$RowCount] = $Gastos;
				$detalle['TotalGastos'][$RowCount] = $totalgastos;
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_gastos.php';
	}
	
	if($accion == "consultar_gastos_socio_jva_vendedor_fechaInicio_fechaFin")
	{
		$jva			= $_REQUEST['sjva'];
		$vendedor		= $_REQUEST['svededoresjva'];
		$fecha_inicio	= $_REQUEST['Fecha_inicio'];
		$fecha_fin		= $_REQUEST['Fecha_fin'];
		/*
		 * CONSULTA LOS GASTOS SEGUN EL FILTRO
		 * */
		$query = "SELECT tgj.codigo, tgj.fecha_gasto, tgj.fecha_trans, tgj.aju_codigo, CONCAT(au.nombres,' ',au.apellidos) as ven_nombre, tgj.valor 
					FROM trans_gastos_jva tgj, admin_usuarios au, admin_jva_usuarios aju 
					WHERE aju.codigo = tgj.aju_codigo
					AND tgj.aju_codigo in
					(select codigo from admin_jva_usuarios where usu_codigo = $vendedor) 
					AND fecha_gasto BETWEEN '$fecha_inicio' AND '$fecha_fin'
					AND aju.usu_codigo = au.codigo";
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
		//echo $query;
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_gasto'][$RowCount] 					= $row['fecha_gasto'];
				$detalle['fecha_trans'][$RowCount] 					= $row['fecha_trans'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor'][$RowCount] 						= $row['valor'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_gastos.php';
	}
	
	if($accion == "reportes_detalle_gastos_ruta")
	{
		/*
		 * CODIGO DEL VENDEDOR Y NOMBRE DE LA RUTA PARA MONSTRAR EN PRESENTACION DETALLE
		 * */
		$ven_codigo = $_REQUEST['VEN_CODIGO'];
		/*
		 * CONSULTAMOS EL DETALLE DE LAS VENTAS REALIZADAS POR EL VENDEDOR DE LA RUTA 
		 * */
		$query = "SELECT tgj.codigo, tgj.fecha_gasto, tgj.fecha_trans, tgj.aju_codigo, CONCAT(au.nombres,' ',au.apellidos) as ven_nombre, tgj.valor 
					FROM trans_gastos_jva tgj, admin_usuarios au, admin_jva_usuarios aju 
					WHERE tgj.aju_codigo = $ven_codigo
					AND aju.codigo = tgj.aju_codigo 
					AND aju.usu_codigo = au.codigo";
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
		//echo $query;
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_gasto'][$RowCount] 					= $row['fecha_gasto'];
				$detalle['fecha_trans'][$RowCount] 					= $row['fecha_trans'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor'][$RowCount] 						= $row['valor'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_gastos.php';
	}
	
	if($accion == "consultar_gastos_socio_consolidado")
	{
		/*
		 * CONSULTA LOS GASTOS DEL TODOS LOS VENDEDORES DEL JVA
		 * */
		$query = "SELECT tgj.codigo, tgj.fecha_gasto, tgj.fecha_trans, tgj.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, SUM( tgj.valor ) as valor 
					FROM trans_gastos_jva tgj, admin_usuarios au, admin_jva_usuarios aju
					WHERE aju.codigo = tgj.aju_codigo
					AND aju.jva_codigo IN (SELECT jva_codigo
											FROM admin_jva_usuarios
											WHERE rol_codigo in (2, 3)
											AND usu_codigo = $usu_codigo) 
					AND aju.usu_codigo = au.codigo
					GROUP BY au.codigo"; 
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
		//echo $query;
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_gasto'][$RowCount] 					= $row['fecha_gasto'];
				$detalle['fecha_trans'][$RowCount] 					= $row['fecha_trans'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor'][$RowCount] 						= $row['valor'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_gastos.php';
	}
	
	if($accion == "consultar_gastos_socio_fechaInicio_fechaFin_consolidado")
	{
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin']; 
		
		/*
		 * CONSULTA LOS GASTOS DEL TODOS LOS VENDEDORES DEL JVA
		 * */
		$query = "SELECT tgj.codigo, tgj.fecha_gasto, tgj.fecha_trans, tgj.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, SUM( tgj.valor ) as valor 
					FROM trans_gastos_jva tgj, admin_usuarios au, admin_jva_usuarios aju
					WHERE aju.codigo = tgj.aju_codigo
					AND aju.jva_codigo IN (SELECT jva_codigo
											FROM admin_jva_usuarios
											WHERE rol_codigo in (2, 3)
											AND usu_codigo = $usu_codigo) 
					AND aju.usu_codigo = au.codigo
					AND aju.usu_codigo = au.codigo
					AND tgj.fecha_gasto BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59'
					GROUP BY au.codigo"; 
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
		//echo $query;
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_gasto'][$RowCount] 					= $row['fecha_gasto'];
				$detalle['fecha_trans'][$RowCount] 					= $row['fecha_trans'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor'][$RowCount] 						= $row['valor'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_gastos.php';
	}
	
	if($accion == "consultar_gastos_socio_jva_consolidado")
	{
		$jva = $_REQUEST['sjva'];
		/*
		 * CONSULTA LOS GASTOS DEL TODOS LOS VENDEDORES DEL JVA
		 * */
		$query = "SELECT tgj.codigo, tgj.fecha_gasto, tgj.fecha_trans, tgj.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, SUM( tgj.valor ) as valor 
					FROM trans_gastos_jva tgj, admin_usuarios au, admin_jva_usuarios aju
					WHERE aju.codigo = tgj.aju_codigo
					AND aju.jva_codigo = $jva
					AND aju.usu_codigo = au.codigo
					GROUP BY au.codigo"; 
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
		//echo $query;
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_gasto'][$RowCount] 					= $row['fecha_gasto'];
				$detalle['fecha_trans'][$RowCount] 					= $row['fecha_trans'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor'][$RowCount] 						= $row['valor'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_gastos.php';
	}
	
	if($accion == "consultar_gastos_socio_jva_vendedor_consolidado")
	{
		$jva 		= $_REQUEST['sjva'];
		$vendedor 	= $_REQUEST['svededoresjva'];
		/*
		 * CONSULTA LOS GASTOS DEL TODOS LOS VENDEDORES DEL JVA
		 * */
		$query = "SELECT tgj.codigo, tgj.fecha_gasto, tgj.fecha_trans, tgj.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, SUM( tgj.valor ) as valor 
					FROM trans_gastos_jva tgj, admin_usuarios au, admin_jva_usuarios aju
					WHERE aju.codigo = tgj.aju_codigo
					AND aju.jva_codigo = $jva
					AND aju.usu_codigo = au.codigo
					AND au.codigo = $vendedor
					GROUP BY au.codigo"; 
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
		//echo $query;
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_gasto'][$RowCount] 					= $row['fecha_gasto'];
				$detalle['fecha_trans'][$RowCount] 					= $row['fecha_trans'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor'][$RowCount] 						= $row['valor'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_gastos.php';
	}
	
	if($accion == "consultar_gastos_socio_jva_fechaInicio_fechaFin_consolidado")
	{
		$jva = $_REQUEST['sjva'];
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin']; 
		
		/*
		 * CONSULTA LOS GASTOS DEL TODOS LOS VENDEDORES DEL JVA
		 * */
		$query = "SELECT tgj.codigo, tgj.fecha_gasto, tgj.fecha_trans, tgj.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, SUM( tgj.valor ) as valor 
					FROM trans_gastos_jva tgj, admin_usuarios au, admin_jva_usuarios aju
					WHERE aju.codigo = tgj.aju_codigo
					AND aju.jva_codigo = $jva
					AND aju.usu_codigo = au.codigo
					AND aju.usu_codigo = au.codigo
					AND tgj.fecha_gasto BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59'
					GROUP BY au.codigo"; 
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
		//echo $query;
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_gasto'][$RowCount] 					= $row['fecha_gasto'];
				$detalle['fecha_trans'][$RowCount] 					= $row['fecha_trans'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor'][$RowCount] 						= $row['valor'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_gastos.php';
	}
	
	if($accion == "consultar_gastos_socio_jva_vendedor_fechaInicio_fechaFin_consolidado")
	{
		$jva = $_REQUEST['sjva'];
		$vendedor = $_REQUEST['svededoresjva'];
		$fecha_inicio = $_REQUEST['Fecha_inicio'];
		$fecha_fin = $_REQUEST['Fecha_fin']; 
		
		/*
		 * CONSULTA LOS GASTOS DEL TODOS LOS VENDEDORES DEL JVA
		 * */
		$query = "SELECT tgj.codigo, tgj.fecha_gasto, tgj.fecha_trans, tgj.aju_codigo, CONCAT( au.nombres,  ' ', au.apellidos ) AS ven_nombre, SUM( tgj.valor ) as valor 
					FROM trans_gastos_jva tgj, admin_usuarios au, admin_jva_usuarios aju
					WHERE aju.codigo = tgj.aju_codigo
					AND aju.jva_codigo = $jva
					AND au.codigo = $vendedor
					AND aju.usu_codigo = au.codigo
					AND aju.usu_codigo = au.codigo
					AND tgj.fecha_gasto BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59'
					GROUP BY au.codigo"; 
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
		//echo $query;
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_gasto'][$RowCount] 					= $row['fecha_gasto'];
				$detalle['fecha_trans'][$RowCount] 					= $row['fecha_trans'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor'][$RowCount] 						= $row['valor'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_gastos.php';
	}
	
	if($accion == "consultar_gastos_socio_vendedor")
	{
		$vendedor = $_REQUEST['cliente'];
		/*
		 * CONSULTAMOS EL DETALLE DE LAS VENTAS REALIZADAS POR EL VENDEDOR DE LA RUTA 
		 * */
		$query = "SELECT tgj.codigo, tgj.fecha_gasto, tgj.fecha_trans, tgj.aju_codigo, CONCAT(au.nombres,' ',au.apellidos) as ven_nombre, tgj.valor 
					FROM trans_gastos_jva tgj, admin_usuarios au, admin_jva_usuarios aju 
					WHERE CONCAT(au.nombres, ' ', au.apellidos) LIKE '%$vendedor%'
					AND aju.codigo = tgj.aju_codigo 
					AND aju.usu_codigo = au.codigo";
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
		//echo $query;
		if($error == "00000")
		{
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$detalle['codigo'][$RowCount] 						= $row['codigo'];
				$detalle['fecha_gasto'][$RowCount] 					= $row['fecha_gasto'];
				$detalle['fecha_trans'][$RowCount] 					= $row['fecha_trans'];
				$detalle['aju_codigo'][$RowCount] 					= $row['aju_codigo'];
				$detalle['ven_nombre'][$RowCount] 					= $row['ven_nombre'];
				$detalle['valor'][$RowCount] 						= $row['valor'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>alert('Error al consultar <?php echo $error." ".$errorMessage?>');</script>
			<?php
		}
		include '../presentacion/rpt_reportes_gastos.php';
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