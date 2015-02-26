<?php
require_once("conexion_bd.php");
Class Consultas
{
	function logon($usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;
		$db_link = new PDO($dsn, $username, $passwd);

		$query = "select nombres, apellidos
				  from admin_usuarios
				  where username='$usuario' and est_codigo=1";

		$result = $db_link->query($query);
		$retorno = "NOMBRES|APELLIDOS";
		$retorno=$retorno. "\n";

		$array [] = $result->fetch(PDO::FETCH_NUM);
		$filas = sizeof($array); 
		
		for($i=0;$i<$filas;$i++)
		{
		$u = 2;
			for($k=0; $k<$u; $k++)
			{
				$retorno=$retorno.$array[$i][$k];
					if($k!=$u-1)
					{
						$retorno=$retorno."|";
					}
			}
		}
		return $retorno;
	}

	function ConsultaCliente($Idcliente, $Idfuncionario, $usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;
		$db_link = new PDO($dsn, $username, $passwd);

		$query = "select clientes.nroidentificacion nroidentificacion, rutas.saldo saldo,
				clientes.nombre_contacto nombre_contacto, recaudo.valor_pago valor_pago, ventas.codigo codigo, 
				ventas.cli_codigo cli_codigo 
				from trans_ventas ventas join admin_clientes clientes on ventas.cli_codigo=clientes.codigo 
				join trans_rutas_detalles rutas on ventas.codigo=rutas.tv_codigo 
				join trans_detalle_recaudo_ventas_jva recaudo on ventas.codigo=recaudo.tv_codigo 
				join admin_jva_usuarios vendedor on vendedor.codigo=ventas.aju_codigo 
				where ventas.cli_codigo = '$Idcliente' and ventas.aju_codigo = '$Idfuncionario'";

		$result = $db_link->query($query);
		$retorno = "NRO_IDENTIFICACION|SALDO|NOMBRE_CONTACTO|VALOR_PAGO|CODIGO|CLI_CODIGO|";
		$retorno=$retorno. "\n";

		$array [] = $result->fetch(PDO::FETCH_NUM);
		//print_r($array);
		$filas = sizeof($array);
		for($i=0;$i<$filas;$i++)
		{
			for($k=0; $k<5; $k++)
			{
				$retorno=$retorno.$array[$i][$k]."|";
			}
		}
		return $retorno;
	}

	function CargueInicial($fecha_transaccion,$usu_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;
		$db_link = new PDO($dsn, $username, $passwd);

		$query = " SELECT sum( a.cantidad )
				  FROM admin_inventarios_bodegas a
				  JOIN admin_bodegas b ON a.bod_codigo = b.codigo
				  JOIN admin_jva_usuarios c ON b.aju_codigo = c.codigo
				  JOIN admin_usuarios d ON c.usu_codigo = d.codigo
				  WHERE a.fecha_transaccion = '$fecha_transaccion'
				  AND c.usu_codigo = '$usu_codigo'";
		$result = $db_link->query($query);
		$retorno = "TOTAL|";
		$retorno=$retorno. "\n";
		$array [] = $result->fetch(PDO::FETCH_NUM);
		$filas = sizeof($array);
		for($i=0;$i<$filas;$i++)
		{
			for($k=0; $k < 1; $k++)
			{
				$retorno = $retorno.$array[$i][$k]."|";
			}
		}
		return $retorno;
	}
	
function ConsultaClienteRenovacion($nro_identificacion, $usuario, $pwd)
	{

                global $DATABASE_NAME;
                global $dsn ;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = "SELECT clientes.nroidentificacion CEDULA, clientes.nombre_contacto CLIENTE, clientes.telefono1 TELEFONO, clientes.celular1 CELULAR, clientes.tn_codigo NEGOCIO, ventas.codigo CODIGO, clientes.direccion DIRECCION
FROM admin_clientes clientes
JOIN trans_ventas ventas ON ventas.cli_codigo = clientes.codigo
JOIN admin_jva_usuarios vendedor ON vendedor.codigo = ventas.aju_codigo
JOIN admin_usuarios usuarios ON usuarios.codigo = vendedor.usu_codigo
WHERE usuarios.nro_identificacion =  '$nro_identificacion'";
				
				$result = $db_link->query($query);
                $retorno = "CEDULA|CLIENTE|TELEFONO|CELULAR|NEGOCIO|CODIGO|DIRECCION|";
                $retorno=$retorno. "\n";

                 $array [] = $result->fetch(PDO::FETCH_NUM);
                 //print_r($contar);
                 //$arreglo2 = split("|", $string);
                 $filas = sizeof($array);
		for($i=0;$i<$filas;$i++)
		{
			for($k=0; $k<=6; $k++)
			{
            $retorno=$retorno.$array[$i][$k]."|";	
			}
		}
        return $retorno;
	}
	
	function IngresarVenta($codigo_barras,$referencia,$fecha_solicitud,$cli_codigo,$aju_codigo,$ttp_codigo,$tp_codigo,
		$tnc_codigo,$valor_producto,$valor_comision_servicio,$valor_impuesto,$valor_total,$fecha_entrega,
		$longitud,$latitud,$est_codigo, $usuario, $pwd)
	{
		//SE CONECTA A LA BASE DE DATOS
                global $DATABASE_NAME;
                global $dsn ;
                $username=$usuario; // USA EL NOMBRE DE USUARIO DEL VENDEDOR
                $passwd=$pwd; //USA EL PASSWORD PARA LA CONECCION
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = "INSERT INTO trans_ventas(codigo_barras, referencia, fecha_solicitud, cli_codigo, aju_codigo, 
		ttp_codigo, tp_codigo, tnc_codigo, valor_producto, valor_comision_servicio, valor_impuesto, valor_total, 
		fecha_entrega, longitud, latitud, est_codigo) 
		VALUES ('$codigo_barras',$referencia,'$fecha_solicitud',$cli_codigo,$aju_codigo,$ttp_codigo,$tp_codigo,
		$tnc_codigo,$valor_producto,$valor_comision_servicio,$valor_impuesto,$valor_total,'$fecha_entrega',
		'$longitud','$latitud',$est_codigo)";
				 
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':codigo',$codigo);
		$result->bindParam(':est_codigo',$est_codigo);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		
		if($error=="00000")
		{
			$db_link->commit();
			$retorno = '1'; //CORRECTO
		}
		else
		{
			$db_link->rollBack();
			echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			$retorno = '0'; //INCORRECTO
		}		
		return $retorno;
	}
	
	function IngresarCliente($ti_codigo,$nroidentificacion,$razon_social,$tn_codigo,$nombre_contacto,$telefono1,$ext1,
	$telefono2,$ext2,$celular1,$celular2,$email,$barrio,$direccion,$comentario,$ciu_codigo,$est_codigo, $usuario, $pwd)
	{
                global $DATABASE_NAME;
                global $dsn ;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = "INSERT INTO admin_clientes
				  (ti_codigo,nroidentificacion,razon_social,tn_codigo,nombre_contacto,telefono1,ext1,telefono2,ext2,celular1,
				  celular2,email,barrio,direccion,comentario,ciu_codigo,est_codigo)
				  VALUES ($ti_codigo,$nroidentificacion,$razon_social,$tn_codigo,$nombre_contacto,$telefono1,$ext1,$telefono2,
				  $ext2,$celular1,$celular2,$email,$barrio,$direccion,$comentario,$ciu_codigo,
				  $est_codigo)";
				
				 $result = $db_link->prepare($query);
				 $db_link->beginTransaction();
				 $result->execute(); //SE EJECUTA EL QUERY
				 //print_r($result);
	
		if($result==true)
			{
			$retorno="1"; //insertado correctamente
			}
		else
			{	
			$retorno="0"; //no insertado
			}
		return $retorno;
	}	
	
	function ConsultaRecaudoDiario($usu_codigo, $fecha_recaudo, $usuario, $pwd)
	{

                global $DATABASE_NAME;
                global $dsn ;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = "SELECT sum( a.valor_pago )
					FROM trans_detalle_recaudo_ventas_jva a
					join trans_ventas b on b.codigo=a.tv_codigo
					join admin_jva_usuarios c on c.codigo=b.aju_codigo
					join admin_usuarios d on d.codigo=c.usu_codigo
					where c.usu_codigo = '$usu_codigo'
					and a.fecha_recaudo = '$fecha_recaudo'";
				
				$result = $db_link->query($query);
                $retorno = "RECAUDO|";
                $retorno=$retorno. "\n";

                 $array [] = $result->fetch(PDO::FETCH_NUM);
                 //print_r($contar);
                 //$arreglo2 = split("|", $string);
                 $filas = sizeof($array);
		for($i=0;$i<$filas;$i++)
		{
			for($k=0; $k<1; $k++)
			{
            $retorno=$retorno.$array[$i][$k]."|";	
			}
		}
        return $retorno;
	}
	
	function ConsultaVentaDiarias($usu_codigo, $fecha_entrega, $usuario, $pwd)
	{

                global $DATABASE_NAME;
                global $dsn ;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = " SELECT SUM( a.valor_producto ) 
					FROM trans_ventas a
					JOIN admin_jva_usuarios b ON b.codigo = a.aju_codigo
					JOIN admin_usuarios c ON c.codigo = b.usu_codigo
					WHERE b.usu_codigo =  '$usu_codigo'
					AND a.fecha_entrega =  '$fecha_entrega'";
				
				$result = $db_link->query($query);
                $retorno = "VENTAS|";
                $retorno=$retorno. "\n";

                 $array [] = $result->fetch(PDO::FETCH_NUM);
                 //print_r($contar);
                 //$arreglo2 = split("|", $string);
                 $filas = sizeof($array);
		for($i=0;$i<$filas;$i++)
		{
			for($k=0; $k<1; $k++)
			{
            $retorno=$retorno.$array[$i][$k]."|";	
			}
		}
        return $retorno;
	}
	
	function ConsultaDebidoCobrar($codigo, $usuario, $pwd)
	{

                global $DATABASE_NAME;
                global $dsn ;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = " select sum(a.valor_pago)
					from trans_detalle_recaudo_ventas_jva a 
					join trans_ventas b on b.codigo=a.tv_codigo
					join admin_jva_usuarios c on c.codigo=b.aju_codigo
					join admin_usuarios d on d.codigo=c.usu_codigo
					join trans_rutas_detalles e on e.tv_codigo=b.codigo
					where d.codigo = '$codigo'
					and e.saldo>0";
				
				$result = $db_link->query($query);
                $retorno = "COBRO|";
                $retorno=$retorno. "\n";

                 $array [] = $result->fetch(PDO::FETCH_NUM);
                 //print_r($contar);
                 //$arreglo2 = split("|", $string);
                 $filas = sizeof($array);
		for($i=0;$i<$filas;$i++)
		{
			for($k=0; $k<1; $k++)
			{
            $retorno=$retorno.$array[$i][$k]."|";	
			}
		}
        return $retorno;
	}
	
	function ClientesVisitado($codigo, $usuario, $pwd)
	{

                global $DATABASE_NAME;
                global $dsn;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = "SELECT count(*) FROM trans_detalle_recaudo_ventas_jva a
	join trans_ventas b on a.tv_codigo=b.codigo
	join admin_jva_usuarios c on c.codigo=b.aju_codigo
	join admin_usuarios d on d.codigo=c.usu_codigo
	where a.est_codigo = 4 and d.codigo = '$codigo'";
				
				$result = $db_link->query($query);
                $retorno = "VISITAS|";
                $retorno=$retorno. "\n";

                 $array [] = $result->fetch(PDO::FETCH_NUM);
                 //print_r($contar);
                 //$arreglo2 = split("|", $string);
                 $filas = sizeof($array);
		for($i=0;$i<$filas;$i++)
		{
			for($k=0; $k<1; $k++)
			{
            $retorno=$retorno.$array[$i][$k]."|";	
			}
		}
        return $retorno;
	}
	
	function ConsultaTrasladoEnviados($codigo, $fecha_traslado, $usuario, $pwd)
	{

                global $DATABASE_NAME;
                global $dsn;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = "select sum(a.cantidad)
	 from trans_traslados_inventarios_jva a 
	 join admin_jva_usuarios b on a.aju_codigo_desde=b.codigo
	 join admin_usuarios c on c.codigo=b.usu_codigo
	 where c.codigo = '$codigo'
	 and a.fecha_traslado = '$fecha_traslado'";
				
				$result = $db_link->query($query);
                $retorno = "VALOR_ENVIA|";
                $retorno=$retorno. "\n";

                 $array [] = $result->fetch(PDO::FETCH_NUM);
                 //print_r($contar);
                 //$arreglo2 = split("|", $string);
                 $filas = sizeof($array);
		for($i=0;$i<$filas;$i++)
		{
			for($k=0; $k<1; $k++)
			{
            $retorno=$retorno.$array[$i][$k]."|";	
			}
		}
        return $retorno;
	}
	
	function ConsultaTrasladoRecibidos($codigo, $fecha_traslado, $usuario, $pwd)
	{

                global $DATABASE_NAME;
                global $dsn;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = "select sum(a.cantidad)
	 from trans_traslados_inventarios_jva a 
	 join admin_jva_usuarios b on a.aju_codigo_hasta=b.codigo
	 join admin_usuarios c on c.codigo=b.usu_codigo
	 where c.codigo = '$codigo'
	 and a.fecha_traslado = '$fecha_traslado'";
				
				$result = $db_link->query($query);
                $retorno = "VALOR_RECIBE|";
                $retorno=$retorno. "\n";

                 $array [] = $result->fetch(PDO::FETCH_NUM);
                 //print_r($contar);
                 //$arreglo2 = split("|", $string);
                 $filas = sizeof($array);
		for($i=0;$i<$filas;$i++)
		{
			for($k=0; $k<1; $k++)
			{
            $retorno=$retorno.$array[$i][$k]."|";	
			}
		}
        return $retorno;
	}
	
	function ConsultaGastos($usu_codigo, $fecha_gasto, $usuario, $pwd)
	{

                global $DATABASE_NAME;
                global $dsn;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = "select sum(a.valor)
				from trans_gastos_jva a 
				join admin_jva_usuarios b on b.codigo=a.aju_codigo
				where b.usu_codigo = '$usu_codigo'
				and a.fecha_gasto = '$fecha_gasto'";
				
				$result = $db_link->query($query);
                $retorno = "GASTO|";
                $retorno=$retorno. "\n";

                 $array [] = $result->fetch(PDO::FETCH_NUM);
                 //print_r($contar);
                 //$arreglo2 = split("|", $string);
                 $filas = sizeof($array);
		for($i=0;$i<$filas;$i++)
		{
			for($k=0; $k<1; $k++)
			{
            $retorno=$retorno.$array[$i][$k]."|";	
			}
		}
        return $retorno;
	}
	
	function IngresarDetalleRecaudoVenta($tv_codigo,$aju_codigo,$cuotas_totales,$cuota_nro,$fecha_pago,$valor_pago,
	$fecha_recaudo,$valor_recaudo,$est_codigo, $usuario, $pwd)
	{
                global $DATABASE_NAME;
                global $dsn ;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = "INSERT INTO trans_detalle_recaudo_ventas_jva
				  (tv_codigo,aju_codigo,cuotas_totales,cuota_nro,fecha_pago,valor_pago,fecha_recaudo,
				  valor_recaudo,est_codigo)
				  VALUES ('$tv_codigo','$aju_codigo','$cuotas_totales','$cuota_nro','$fecha_pago','$valor_pago','$fecha_recaudo',
					'$valor_recaudo','$est_codigo')";
				
				 $result = $db_link->prepare($query);
				 $db_link->beginTransaction();
				 $result->execute(); //SE EJECUTA EL QUERY
				 //print_r($result);
	
		if($result==true)
			{
			$retorno="1"; //insertado correctamente
			}
		else
			{	
			$retorno="0"; //no insertado
			}
		return $retorno;
	}

	function IngresarGastos($fecha_gasto, $aju_codigo, $tgj_codigo, $valor, $est_codigo, $usuario, $pwd)
	{
                global $DATABASE_NAME;
                global $dsn ;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = "INSERT INTO trans_gastos_jva 
		(fecha_gasto, aju_codigo, tgj_codigo, valor, est_codigo) 
		VALUES ('$fecha_gasto', '$aju_codigo', '$tgj_codigo', '$valor', '$est_codigo')";
				
				 $result = $db_link->prepare($query);
				 $db_link->beginTransaction();
				 $result->execute(); //SE EJECUTA EL QUERY
				 //print_r($result);
	
		if($result==true)
			{
			$retorno="1"; //insertado correctamente
			}
		else
			{	
			$retorno="0"; //no insertado
			}
		return $retorno;
	}
	
	function IngresarVentasDetalles($ven_codigo, $pro_codigo, $cantidad, $valor_unitario, $valor_total, $est_codigo, $usuario, $pwd)
	{
                global $DATABASE_NAME;
                global $dsn ;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
               
		$query = "INSERT INTO trans_ventas_detalles
				(ven_codigo, pro_codigo, cantidad, valor_unitario,valor_total,est_codigo) 
				VALUES ('$ven_codigo', '$pro_codigo', '$cantidad', '$valor_unitario', '$valor_total', '$est_codigo')";
		
				 $result = $db_link->prepare($query);
				 $db_link->beginTransaction();
				 $result->execute(); //SE EJECUTA EL QUERY
				 //print_r($result);
	
		if($result==true)
			{
			$retorno="1"; //insertado correctamente
			}
		else
			{	
			$retorno="0"; //no insertado
			}
		return $retorno;
	}
	
	function IngresarTransaccionRutaDetalles($trj_codigo,$tv_codigo,$secuencia,$longitud,$latitud,$saldo,$est_codigo, $usuario, $pwd)
	{
                global $DATABASE_NAME;
                global $dsn ;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = "INSERT INTO trans_rutas_detalles(trj_codigo,tv_codigo,secuencia,longitud,latitud,saldo,est_codigo)
				  VALUES$($trj_codigo,$tv_codigo,$secuencia,$longitud,$latitud,$saldo,$est_codigo)";
				
				 $result = $db_link->prepare($query);
				 $db_link->beginTransaction();
				 $result->execute(); //SE EJECUTA EL QUERY
				 //print_r($result);
	
		if($result==true)
			{
			$retorno="1"; //insertado correctamente
			}
		else
			{	
			$retorno="0"; //no insertado
			}
		return $retorno;
	}
	
	function ObtenerInfoUsuario($usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;
		$db_link = new PDO($dsn, $username, $passwd);

		$query = "select b.codigo,b.jva_codigo,b.rol_codigo 
		from admin_usuarios a join admin_jva_usuarios b on a.codigo=b.usu_codigo  
		where a.username='$usuario' and b.est_codigo=1";
		$result = $db_link->query($query);
		$retorno = "CODIGO|JVA_CODIGO|ROL_CODIGO";
		
		$array_enc=explode("|",$retorno);
		$u=sizeof($array_enc);
		//echo $u;
		$retorno=$retorno. "\n";
		$array [] = $result->fetch(PDO::FETCH_NUM);
		
		for($i=0;$i<sizeof($array);$i++)
		{
			for($k=0; $k<$u; $k++)
			{
				$retorno=$retorno.$array[$i][$k];
					if($k!=$u-1)
					{
						$retorno=$retorno."|";
					}
			}
		}
		return $retorno;
	}
	
	function increso_loggs_accesos_usuarios($aju_codigo,$fecha_trans,$accion,$ip,$longitud,$latitud,$usuario, $pwd)
	{
                global $DATABASE_NAME;
                global $dsn ;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
				$query = "INSERT INTO logs_accesos_usuarios(aju_codigo, fecha_trans, accion, ip, longitud, latitud) 
				VALUES ('$aju_codigo','$fecha_trans','$accion','$ip','$longitud','$latitud')";
				
				 $result = $db_link->prepare($query);
				 $db_link->beginTransaction();
				 $result->execute(); //SE EJECUTA EL QUERY
				 //print_r($query);
	
		if($result==true)
			{
			$retorno="1"; //insertado correctamente
			}
		else
			{	
			$retorno="0"; //no insertado
			}
		return $retorno;
	}
	
	function consulta_Perfil($codigo, $usuario, $pwd)
	{

                global $DATABASE_NAME;
                global $dsn;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = "SELECT jva_codigo, rol_codigo FROM admin_jva_usuarios WHERE codigo = '$codigo'";
				
				$result = $db_link->query($query);
                $retorno = "JVA CODIGO|ROL CODIGO|";
                $retorno=$retorno. "\n";

                 $array [] = $result->fetch(PDO::FETCH_NUM);
                 //print_r($contar);
                 //$arreglo2 = split("|", $string);
                 $filas = sizeof($array);
		for($i=0;$i<$filas;$i++)
		{
			for($k=0; $k<2; $k++)
			{
            $retorno=$retorno.$array[$i][$k]."|";	
			}
		}
        return $retorno;
	}
	
	function consulta_Vendedores($codigo, $usuario, $pwd)
	{

                global $DATABASE_NAME;
                global $dsn;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = "SELECT admin_usuarios.nro_identificacion AS CEDULA, CONCAT(nombres,' ', apellidos ) AS NOMBRE
FROM admin_usuarios, admin_jva_usuarios
WHERE admin_usuarios.codigo = admin_jva_usuarios.usu_codigo
AND admin_jva_usuarios.codigo =$codigo";
				
				$result = $db_link->query($query);
                $retorno = "CEDULA|NOMBRE|";
                $retorno=$retorno. "\n";

                 $array [] = $result->fetch(PDO::FETCH_NUM);
                 //print_r($contar);
                 //$arreglo2 = split("|", $string);
                 $filas = sizeof($array);
		for($i=0;$i<$filas;$i++)
		{
			$u = 2;
			for($k=0; $k<$u; $k++)
			{
            $retorno=$retorno.$array[$i][$k]."|";	
			}
		}
        return $retorno;
	}
	
	function consulta_Rutas($vendedor, $fecha_venta, $usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;
		$db_link = new PDO($dsn, $username, $passwd);

		$query = "SELECT SECUENCIA ,IDENTIFICACION ,NOMBRE ,APELLIDO ,DIRECCION ,SALDO ,VALOR_PAGO ,COD_CLIENTE 
		,COD_VENTA , COD_BARRAS ,VISITAS ,CT_PENDIENTES ,CT_PAGADAS ,CT_ATRASADAS ,FECHA_ENTREGA ,SOLICITUD ,CELULAR ,
		TELEFONO ,ID_NEGOCIO 
		FROM v_rutas 
		WHERE aju_codigo = '$vendedor'
		AND fecha_pago ='$fecha_venta'";
		
		$result = $db_link->query($query);
		$retorno = "SECUENCIA|IDENTIFICACION|NOMBRE|APELLIDO|DIRECCION|SALDO|VALOR_PAGO|COD_ARGOS|COD_VENTA|COD_BARRAS|
		VISITAS|CT_PENDIENTES|CT_PAGAS|CT_ATRASADAS|FECHA_ENTREGA|SOLICITUD_ID|CELULAR|TELEFONO|TIPO_NEGOCIO";
		
		$array_enc=explode("|",$retorno);
		$u=sizeof($array_enc);
		
		$retorno=$retorno. "\n";
		//$array= $result->fetch(PDO::FETCH_NUM);
		$j=0;
		while($row = $result->fetch(PDO::FETCH_NUM))
		{
			for($k=0; $k<$u; $k++)
			{
				$registros[$j][$k]= ($row[$k]);
				$retorno.=$registros[$j][$k];
				if($k!=$u-1)
				{
					$retorno=$retorno."|";
				}
			}
			$j++;
			$retorno=$retorno. "\n";
		}

		//print_r($registros);
		//exit;
		return $retorno;
	}

   	function ingreso_Clientes_Offline($ti_codigo,$nroidentificacion,$referencia,$razon_social,$tn_codigo,$nombre1_contacto,$nombre2_contacto,$apellido1_contacto,$apellido2_contacto,
$telefono1,$ext1,$telefono2,$ext2,$celular1,$celular2,$email,$barrio,$direccion,$comentario,$calificacion,$ciu_codigo,$est_codigo,$usuario, $pwd)
	{
                global $DATABASE_NAME;
                global $dsn ;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
		$query = "INSERT INTO admin_clientes(ti_codigo, nroidentificacion, referencia, razon_social, tn_codigo, 
		nombre1_contacto, nombre2_contacto,apellido1_contacto, apellido2_contacto, telefono1, ext1, telefono2, ext2, 
		celular1, celular2, email, barrio, direccion, comentario,calificacion, ciu_codigo, est_codigo)
		VALUES ('$ti_codigo','$nroidentificacion','$referencia','$razon_social',$tn_codigo,'$nombre1_contacto',
		'$nombre2_contacto','$apellido1_contacto','$apellido2_contacto','$telefono1','$ext1','$telefono2','$ext2',
		'$celular1','$celular2','$email','$barrio','$direccion','$comentario',$calificacion,'$ciu_codigo','$est_codigo')";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':codigo',$codigo);
		$result->bindParam(':est_codigo',$est_codigo);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		
		if($error=="00000")
		{
			$db_link->commit();
			$retorno = '1'; //CORRECTO
		}
		else
		{
			$db_link->rollBack();
			echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			$retorno = '0'; //INCORRECTO
		}		
		return $retorno;
	}
	
	function ingreso_Solicitud_Offline($codigo_barras,$referencia,$fecha_solicitud,$cli_codigo,$aju_codigo,$ppj_codigo,
	$tp_codigo,$pnc_codigo,$valor_producto,$valor_comision_servicio,$valor_impuesto,$valor_total,$fecha_entrega,
	$longitud,$latitud, $est_codigo,$usuario, $pwd)
	{
                global $DATABASE_NAME;
                global $dsn ;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
        
		$query = "INSERT INTO trans_ventas(codigo_barras, referencia, fecha_solicitud, cli_codigo, aju_codigo, 
		ppj_codigo,tp_codigo, pnc_codigo,valor_producto, valor_comision_servicio, valor_impuesto, valor_total, 
		fecha_entrega,longitud, latitud, est_codigo) 
		VALUES ('$codigo_barras', '$referencia', '$fecha_solicitud', '$cli_codigo', '$aju_codigo', '$ppj_codigo', 
		'$tp_codigo','$pnc_codigo','$valor_producto', '$valor_comision_servicio', '$valor_impuesto', '$valor_total', 
		'$fecha_entrega', '$longitud', '$latitud', '$est_codigo')";
				
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':codigo',$codigo);
		$result->bindParam(':est_codigo',$est_codigo);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		
		if($error=="00000")
		{
			$db_link->commit();
			$retorno = '1'; //CORRECTO
		}
		else
		{
			$db_link->rollBack();
			echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			$retorno = '0'; //INCORRECTO
		}		
		return $retorno;
	}
	
	function Consulta_Producto_Sincroniza($funcionario_id, $id_cliente, $fecha_venta, $usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;
		$db_link = new PDO($dsn, $username, $passwd);

		$query = "SELECT COUNT (*) FROM trans_ventas 
				WHERE aju_codigo = $funcionario_id
				AND cli_codigo = $id_cliente
				AND fecha_solicitud = $fecha_venta";
		
		$result = $db_link->query($query);
		$retorno = "RETORNO";
		
		$array_enc=explode("|",$retorno);
		$u=sizeof($array_enc);
		
		$retorno=$retorno. "\n";
		$array []= $result->fetch(PDO::FETCH_NUM);
		
		for($i=0;$i<sizeof($array);$i++)
		{
			for($k=0; $k<$u; $k++)
			{
				$retorno=$retorno.$array[$i][$k];
				if($k!=$u-1)
				{
					$retorno=$retorno."|";
				};
			}
		}
		return $retorno;
	}
	
	function ingreso_Transaccion_Offline($fecha_recaudo,$valor_recaudo,$fecha_trans,$longitud,$latitud,$est_codigo,$est_codigo_venta, 
	$tv_codigo,$aju_codigo,$cuota_numero,$usuario, $pwd)
	{
                global $DATABASE_NAME;
                global $dsn ;
                $username=$usuario;
                $passwd=$pwd;
                $db_link = new PDO($dsn, $username, $passwd);
                
                $query2 = "UPDATE trans_ventas SET est_codigo = $est_codigo_venta WHERE codigo = $tv_codigo ";
                
        $result2 = $db_link->prepare($query2);
		$db_link->beginTransaction();
		$result2->execute(); //SE EJECUTA EL QUERY
		$arr2 = $result2->errorInfo(); // SE OBTIENE EL ERROR
		$error2 = $arr2[0];
		$errorMessage2 = str_replace("'", "", $arr2[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		//echo $error2;
		if($error2=="00000")
		{
			$db_link->commit();
			$retornoquery1 = '1'; //CORRECTO
			
			echo $query = "UPDATE trans_detalle_recaudo_ventas_jva SET fecha_recaudo='$fecha_recaudo',
			valor_recaudo='$valor_recaudo',fecha_trans='$fecha_trans',longitud='$longitud',latitud='$latitud',
			est_codigo='$est_codigo'
			WHERE tv_codigo = '$tv_codigo'
			AND aju_codigo = '$aju_codigo'
			AND cuota_nro = '$cuota_numero'";
			
			$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		echo $error;
		if($error=="00000")
		{
			$db_link->commit();
			$retorno = '1'; //CORRECTO
		}
		else
		{
			$db_link->rollBack();
			echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			$retorno = '0'; //INCORRECTO
		}		
	}
		else
		{
			$db_link->rollBack();
			echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage2";
			$retornoquery1 = '0'; //INCORRECTO
		}		
		return $retorno;
	}
	
	
	function consulta_Trans_Ventas($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;
		$db_link = new PDO($dsn, $username, $passwd);

		$query = "SELECT  codigo, codigo_barras,  referencia ,  fecha_solicitud ,  cli_codigo ,  aju_codigo ,  ppj_codigo ,   tp_codigo ,  pnc_codigo , valor_producto ,  valor_comision_servicio , valor_impuesto ,  valor_total ,  
		fecha_entrega ,  longitud ,  latitud ,  est_codigo 
		FROM  trans_ventas WHERE aju_codigo = $aju_codigo ";
		
		$result = $db_link->query($query);
		$retorno = "CODIGO|CODIGO_BARRAS|REFERENCIA|FECHA_SOLICITUD|CLI_CODIGO|AJU_CODIGO|TTP_CODIGO|TP_CODIGO|TNC_CODIGO|VALOR_PRODUCTO|VALOR_COMISION_SERVICIO|VALOR_IMPUESTO|VALOR_TOTAL|FECHA_ENTREGA|LONGITUD|LATITUD|ESTADO";
		
		$array_enc=explode("|",$retorno);
		$u=sizeof($array_enc);
		
		$retorno=$retorno. "\n";
		//$array= $result->fetch(PDO::FETCH_NUM);
		$j=0;
		while($row = $result->fetch(PDO::FETCH_NUM))
		{
			for($k=0; $k<$u; $k++)
			{
				$registros[$j][$k]= ($row[$k]);
				$retorno.=$registros[$j][$k];
				if($k!=$u-1)
				{
					$retorno=$retorno."|";
				}
			}
			$j++;
			$retorno=$retorno. "\n";
		}
		return $retorno;
	}
	
	
	function admin_clientes($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;
		$db_link = new PDO($dsn, $username, $passwd);

		$query = "select clientes.codigo, clientes.ti_codigo, clientes.nroidentificacion, 
		clientes.referencia, clientes.razon_social, clientes.tn_codigo, clientes.nombre1_contacto, 
		clientes.nombre2_contacto, clientes.apellido1_contacto, clientes.apellido2_contacto, 
		clientes.telefono1, clientes.ext1, clientes.telefono2, clientes.ext2, clientes.celular1, 
		clientes.celular2, clientes.email, clientes.barrio, clientes.direccion, clientes.comentario, 
		clientes.ciu_codigo, clientes.est_codigo
		from admin_clientes clientes join trans_ventas ventas on clientes.codigo = ventas.cli_codigo
		where ventas.aju_codigo  = $aju_codigo";
		
		$result = $db_link->query($query);
		$retorno = "CODIGO|TI_CODIGO|NROIDENTIFICACION|REFERENCIA|RAZON_SOCIAL|TN_CODIGO|NOMBRE1_CONTACTO|NOMBRE2_CONTACTO|APELLIDO1_CONTACTO|APELLIDO2_CONTACTO|TELEFONO1|EXT1|TELEFONO2|EXT2|CELULAR1|CELULAR2|EMAIL|BARRIO|DIRECCION|COMENTARIO|CIU_CODIGO|EST_CODIGO";
		
		$array_enc=explode("|",$retorno);
		$u=sizeof($array_enc);
		
		$retorno=$retorno. "\n";
		//$array= $result->fetch(PDO::FETCH_NUM);
		$j=0;
		while($row = $result->fetch(PDO::FETCH_NUM))
		{
			for($k=0; $k<$u; $k++)
			{
				$registros[$j][$k]= ($row[$k]);
				$retorno.=$registros[$j][$k];
				
				if($k!=$u-1)
				{
					$retorno.="|";
				}
			}
			$j++;
			$retorno.="\n";
		}
		return $retorno;
		//echo $retorno;
	}
	
	function trans_rutas_jva($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;
		$db_link = new PDO($dsn, $username, $passwd);

		$query = "SELECT codigo, nombre, descripcion, jva_codigo, aju_codigo, pbj_codigo, saldo, est_codigo 
		FROM trans_rutas_jva WHERE aju_codigo = $aju_codigo";
		
		$result = $db_link->query($query);
		$retorno = "CODIGO|NOMBRE|DESCRIPCION|JVA_CODIGO|AJU_CODIGO|PBJ_CODIGO|SALDO|EST_CODIGO";
		
		$array_enc=explode("|",$retorno);
		$u=sizeof($array_enc);
		
		$retorno=$retorno. "\n";
		//$array= $result->fetch(PDO::FETCH_NUM);
		$j=0;
		while($row = $result->fetch(PDO::FETCH_NUM))
		{
			for($k=0; $k<$u; $k++)
			{
				$registros[$j][$k]= ($row[$k]);
				$retorno.=$registros[$j][$k];
				
				if($k!=$u-1)
				{
					$retorno.="|";
				}
			}
			$j++;
			$retorno.="\n";
		}
		return $retorno;
		//echo $retorno;
	}
	
	function trans_rutas_detalles($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;
		$db_link = new PDO($dsn, $username, $passwd);

		$query = "SELECT codigo, trj_codigo, tv_codigo, aju_codigo, secuencia, saldo, est_codigo 
		FROM trans_rutas_detalles WHERE aju_codigo = $aju_codigo";
		
		$result = $db_link->query($query);
		$retorno = "CODIGO|TRJ_CODIGO|TV_CODIGO|AJU_CODIGO|SECUENCIA|SALDO|EST_CODIGO";
		
		$array_enc=explode("|",$retorno);
		$u=sizeof($array_enc);
		
		$retorno=$retorno. "\n";
		//$array= $result->fetch(PDO::FETCH_NUM);
		$j=0;
		while($row = $result->fetch(PDO::FETCH_NUM))
		{
			for($k=0; $k<$u; $k++)
			{
				$registros[$j][$k]= ($row[$k]);
				$retorno.=$registros[$j][$k];
				
				if($k!=$u-1)
				{
					$retorno.="|";
				}
			}
			$j++;
			$retorno.="\n";
		}
		return $retorno;
		//echo $retorno;
	}
	
	function trans_detalle_recaudo_ventas_jva($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;
		$db_link = new PDO($dsn, $username, $passwd);

		$query = "SELECT codigo, tv_codigo, aju_codigo, cuotas_totales, cuota_nro, fecha_pago, valor_pago, 
		fecha_recaudo, valor_recaudo, fecha_trans, longitud, latitud, est_codigo FROM trans_detalle_recaudo_ventas_jva WHERE aju_codigo = $aju_codigo limit 0,100";
		
		$result = $db_link->query($query);
		$retorno = "CODIGO|TV_CODIGO|AJU_CODIGO|CUOTAS_TOTALES|CUOTA_NRO|FECHA_PAGO|VALOR_PAGO|FECHA_RECAUDO|VALOR_RECAUDO|FECHA_TRANS|LONGITUD|LATITUD|EST_CODIGO";
		
		$array_enc=explode("|",$retorno);
		$u=sizeof($array_enc);
		
		$retorno=$retorno. "\n";
		//$array= $result->fetch(PDO::FETCH_NUM);
		$j=0;
		while($row = $result->fetch(PDO::FETCH_NUM))
		{
			for($k=0; $k<$u; $k++)
			{
				$registros[$j][$k]= ($row[$k]);
				$retorno.=$registros[$j][$k];
				
				if($k!=$u-1)
				{
					$retorno.="|";
				}
			}
			$j++;
			$retorno.="\n";
		}
		return $retorno;
		//echo $retorno;
	}
	
	
	
	
}
?>