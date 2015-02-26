<?php
require_once("conexion_bd.php");
Class Consultas
{
	function logon($ip,$longitud,$latitud,$usuario,$pwd)
	{
		/*
		 * CONECCION A LA DB
		 * */
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn,$username,$passwd);

			$query = "select nombres, apellidos
				  from admin_usuarios
				  where username='$usuario' and est_codigo=1";

			$result = $db_link->prepare($query);
			$retorno = "NOMBRES|APELLIDOS";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				
				/*
				 * QUERY QUE REGISTRA EL LOG DE INGRESO
				 * */
				$query2 = "INSERT INTO logs_accesos_usuarios(aju_codigo, fecha_trans, accion, ip, longitud, latitud)
					VALUES ((select fun_obtener_aju_codigo('$usuario')),now(),'login','$ip','$longitud','$latitud')";

				/*
				 * SE PREPARA EL QUERY
				 * */
				$result2 = $db_link->prepare($query2);
				$db_link->beginTransaction();
				$result2->execute(); //SE EJECUTA EL QUERY
				$arr2 = $result2->errorInfo(); // SE OBTIENE EL ERROR
				$error2 = $arr2[0];
				$errorMessage = str_replace("'", "", $arr2[2]);

				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */

				if($error2=="00000")
				{
					$db_link->commit();
					//$retorno2 = '1'; //CORRECTO
				}
				else
				{
					$db_link->rollBack();
					echo $msg2="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
					//$retorno2 = '0'; //INCORRECTO
				}

				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();

				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'logon';

				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
				//echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function Logout($aju_codigo,$ip,$longitud,$latitud,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = "INSERT INTO logs_accesos_usuarios(aju_codigo, fecha_trans, accion, ip, longitud, latitud)
		VALUES ('$aju_codigo',now(),'logout','$ip','$longitud','$latitud')";
			/*
			 * SE PREPARA EL QUERY
			 * */
			$result = $db_link->prepare($query);
			$db_link->beginTransaction();
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

				$error_tipo = mysql;
				$error_archivo = 'server.argos.class.php';
				$error_accion = insert;

				/*
				 * INGRESA LOS DATOS DEL ERROR
				 * */
				$query3 = "INSERT INTO logs_errores_usuarios( aju_codigo, fecha_trans, error_accion, error_archivo,
			 error_tipo,error_codigo,error_descripcion, error_query, ip, longitud, latitud) 
			 VALUES('$aju_codigo',now(),'$error_accion','$error_archivo','$error_tipo','$error','$errorMessage',
			 '$query','$ip','$longitud','$latitud')";

				/*
				 * SE PREPARA EL QUERY
				 * */
				$result3 = $db_link->prepare($query3);
				$db_link->beginTransaction();
				$result3->execute(); //SE EJECUTA EL QUERY
				$arr3 = $result3->errorInfo(); // SE OBTIENE EL ERROR
				$error3 = $arr3[0];
				$errorMessage = str_replace("'", "", $arr3[2]);

				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error3=="00000")
				{
					$db_link->commit();
				}
				else
				{
					$db_link->rollBack();
				}
				$retorno = '0'; //INCORRECTO
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function ConsultaCliente($Idcliente, $Idfuncionario, $usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

function Consulta_Cargue_Inicial($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);


			$query = "SELECT cantidad FROM trans_inventarios_jva WHERE aju_codigo = $aju_codigo";
			
			$result = $db_link->prepare($query);
			$retorno = "TOTAL";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	
function consulta_Gastos($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			 $fecha = date('Y-m-d');
			 $fecha_desde = $fecha." 00:00:00";
			 $fecha_hasta = $fecha." 23:59:59";
			 
			 
			$query = "SELECT SUM(valor) FROM trans_gastos_jva WHERE fecha_trans BETWEEN '$fecha_desde'
			AND '$fecha_hasta' AND aju_codigo = $aju_codigo ";
			
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
function cons_Trans_Ingresa($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			 $fecha = date('Y-m-d');
			 $fecha_desde = $fecha." 00:00:00";
			 $fecha_hasta = $fecha." 23:59:59";
			 
			$query = "SELECT cantidad FROM trans_traslados_inventarios_jva WHERE aju_codigo_hasta = $aju_codigo
			AND fecha_aprovacion BETWEEN '$fecha_desde' AND '$fecha_hasta'";
			
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
function cons_Trans_Envia($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			 $fecha = date('Y-m-d');
			 $fecha_desde = $fecha." 00:00:00";
			 $fecha_hasta = $fecha." 23:59:59";
			 
			$query = "SELECT cantidad FROM trans_traslados_inventarios_jva WHERE aju_codigo_desde = $aju_codigo
			AND fecha_aprovacion BETWEEN '$fecha_desde' AND '$fecha_hasta'";
			
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	
function consulta_Cobro_Diario($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			 $fecha = date('Y-m-d');
			 $fecha_desde = $fecha." 00:00:00";
			 $fecha_hasta = $fecha." 23:59:59";
			 
			$query = "SELECT valor_pago FROM trans_detalle_recaudo_ventas_jva WHERE aju_codigo = $aju_codigo
			AND valor_pago BETWEEN '$fecha_desde' AND '$fecha_hasta'";
			
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	function ConsultaClienteRenovacion($nro_identificacion, $usuario, $pwd)
	{

		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
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

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
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

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function Consulta_Recaudo_Diario($aju_codigo, $usuario, $pwd)
	{

		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			 $fecha = date('Y-m-d');
			 $fecha_desde = $fecha." 00:00:00";
			 $fecha_hasta = $fecha." 23:59:59";
			
			$query = "SELECT SUM(valor_recaudo)
			FROM trans_detalle_recaudo_ventas_jva
			WHERE aju_codigo =$aju_codigo
			AND fecha_trans 
			BETWEEN  '$fecha_desde' AND  '$fecha_hasta'";

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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function ConsultaVentaDiarias($aju_codigo, $usuario, $pwd)
	{

		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			 $fecha = date('Y-m-d');
			 $fecha_desde = $fecha." 00:00:00";
			 $fecha_hasta = $fecha." 23:59:59";
			
			$query = "SELECT SUM( valor_total ) 
			FROM trans_ventas
			WHERE aju_codigo = $aju_codigo
			AND fecha_entrega
			BETWEEN  '$fecha_desde'
			AND  '$fecha_hasta' AND est_codigo = 1";

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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function ConsultaDebidoCobrar($codigo, $usuario, $pwd)
	{

		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function ClientesVisitado($codigo, $usuario, $pwd)
	{

		global $DATABASE_NAME;
		global $dsn;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function ConsultaTrasladoEnviados($codigo, $fecha_traslado, $usuario, $pwd)
	{

		global $DATABASE_NAME;
		global $dsn;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function ConsultaTrasladoRecibidos($codigo, $fecha_traslado, $usuario, $pwd)
	{

		global $DATABASE_NAME;
		global $dsn;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function ConsultaGastos($usu_codigo, $fecha_gasto, $usuario, $pwd)
	{

		global $DATABASE_NAME;
		global $dsn;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
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

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function IngresarGastos($fecha_gasto, $aju_codigo, $tgj_codigo, $valor, $est_codigo, $usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function IngresarVentasDetalles($tv_codigo, $ppj_codigo, $cantidad, $valor_unitario, $valor_total, $est_codigo, $usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);
				
			 $query = "INSERT INTO trans_ventas_detalles
				(tv_codigo, ppj_codigo, cantidad, valor_unitario,valor_total,est_codigo) 
				VALUES ('$tv_codigo', '$ppj_codigo', '$cantidad', '$valor_unitario', '$valor_total', '$est_codigo')";

		
			/*
			 * SE PREPARA EL QUERY
			 * */
			$result3 = $db_link->prepare($query);
			$db_link->beginTransaction();
			$result3->execute(); //SE EJECUTA EL QUERY
			$arr3 = $result3->errorInfo(); // SE OBTIENE EL ERROR
			$error3 = $arr3[0];
			$errorMessage3 = str_replace("'", "", $arr3[2]);
			
			/*
			 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
			 * */
			if($error3=="00000")
			{
				$db_link->commit();
				$retorno = '1';
			}
			else
			{
				$db_link->rollBack();
				echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage3";
				$retorno = '0';
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	
	}

	function IngresarTransaccionRutaDetalles($trj_codigo,$tv_codigo,$secuencia,$longitud,$latitud,$saldo,$est_codigo, $usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function ObtenerInfoUsuario($usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function increso_loggs_accesos_usuarios($aju_codigo,$fecha_trans,$accion,$ip,$longitud,$latitud,$usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function consulta_Perfil($codigo, $usuario, $pwd)
	{

		global $DATABASE_NAME;
		global $dsn;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function consulta_Vendedores($codigo, $usuario, $pwd)
	{

		global $DATABASE_NAME;
		global $dsn;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function consulta_Rutas($vendedor, $fecha_venta, $usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function ingreso_Clientes_Offline($codigo,$ti_codigo,$nroidentificacion,$referencia,$razon_social,$tn_codigo,$nombre1_contacto,$nombre2_contacto,$apellido1_contacto,$apellido2_contacto,
	$telefono1,$ext1,$telefono2,$ext2,$celular1,$celular2,$email,$barrio,$direccion,$comentario,$ciu_codigo,$est_codigo,$usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = "INSERT INTO admin_clientes(codigo, ti_codigo, nroidentificacion, referencia, razon_social, tn_codigo,
		nombre1_contacto, nombre2_contacto,apellido1_contacto, apellido2_contacto, telefono1, ext1, telefono2, ext2, 
		celular1, celular2, email, barrio, direccion, comentario,calificacion, ciu_codigo, est_codigo)
		VALUES ('$codigo','$ti_codigo','$nroidentificacion','$referencia','$razon_social','$tn_codigo','$nombre1_contacto',
		'$nombre2_contacto','$apellido1_contacto','$apellido2_contacto','$telefono1','$ext1','$telefono2','$ext2',
		'$celular1','$celular2','$email','$barrio','$direccion','$comentario','100','$ciu_codigo','$est_codigo')";
			/*
			 * SE PREPARA EL QUERY
			 * */
			$result = $db_link->prepare($query);
			$db_link->beginTransaction();
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function ingreso_Venta_Offline($codigo, $codigo_barras,$referencia,$fecha_solicitud,$cli_codigo,$aju_codigo,$ppj_codigo,
	$tp_codigo,$pnc_codigo,$valor_producto,$valor_comision_servicio,$valor_impuesto,$valor_total,$fecha_entrega,
	$longitud,$latitud, $est_codigo,$usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			  $query = "INSERT INTO trans_ventas(codigo, codigo_barras, referencia, fecha_solicitud, cli_codigo, aju_codigo,
		ppj_codigo,tp_codigo, pnc_codigo,valor_producto, valor_comision_servicio, valor_impuesto, valor_total, 
		fecha_entrega,longitud, latitud, est_codigo) 
		VALUES ($codigo, '$codigo_barras', '$referencia', '$fecha_solicitud', '$cli_codigo', '$aju_codigo', '$ppj_codigo', 
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function Consulta_Producto_Sincroniza($funcionario_id, $id_cliente, $fecha_venta, $usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
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

		Try
		{
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}


	function consulta_Trans_Ventas($aju_codigo,$usuario,$pwd)
	{
		/*
		 * CONECCION A LA DB
		 * */
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = "SELECT  codigo, codigo_barras,  referencia ,  fecha_solicitud ,  cli_codigo ,  aju_codigo ,  ppj_codigo ,   tp_codigo ,  pnc_codigo , valor_producto ,  valor_comision_servicio , valor_impuesto ,  valor_total ,
		fecha_entrega ,  longitud ,  latitud ,  est_codigo 
		FROM  trans_ventas WHERE aju_codigo = $aju_codigo ";

			$result = $db_link->query($query);
			$retorno = "CODIGO|CODIGO_BARRAS|REFERENCIA|FECHA_SOLICITUD|CLI_CODIGO|AJU_CODIGO|TTP_CODIGO|TP_CODIGO|TNC_CODIGO|VALOR_PRODUCTO|VALOR_COMISION_SERVICIO|VALOR_IMPUESTO|VALOR_TOTAL|FECHA_ENTREGA|LONGITUD|LATITUD|ESTADO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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

				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);
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
			}
			else
			{
				$db_link->rollBack();

				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'consulta_Trans_Ventas';

				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
				//echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}


	function admin_clientes($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
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
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();

				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'logon';

				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
				//echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
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

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);
			
			$query = "SELECT codigo, nombre, descripcion, jva_codigo, aju_codigo, pbj_codigo, saldo, est_codigo
			FROM trans_rutas_jva WHERE aju_codigo = $aju_codigo";
			
			$result = $db_link->query($query);
			$retorno = "CODIGO|NOMBRE|DESCRIPCION|JVA_CODIGO|AJU_CODIGO|PBJ_CODIGO|SALDO|EST_CODIGO";
		$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();

				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'logon';

				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
				//echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}

	/*
	 *METODO DE SINCRONIZACION
	 * */
	function trans_rutas_detalles($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = "SELECT codigo, trj_codigo, tv_codigo, aju_codigo, secuencia, saldo, est_codigo
			FROM trans_rutas_detalles WHERE aju_codigo = $aju_codigo";

			$result = $db_link->query($query);
			$retorno = "CODIGO|TRJ_CODIGO|TV_CODIGO|AJU_CODIGO|SECUENCIA|SALDO|EST_CODIGO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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

				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
					
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);
					
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
			}
			else
			{
				$db_link->rollBack();

				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'logon';

				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
				//echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function contar_trans_detalle_recaudo_ventas_jva($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = "SELECT COUNT(*) FROM trans_detalle_recaudo_ventas_jva
			WHERE aju_codigo = $aju_codigo";

			$result = $db_link->query($query);
			$retorno = "CODIGO";

			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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

				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();

				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'logon';

				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
				//echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			}
		}		
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}

	function Consulta_trans_detalle_recaudo_ventas_jva($aju_codigo,$inicio,$cantidad,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = "SELECT codigo, tv_codigo, aju_codigo, cuotas_totales, cuota_nro, fecha_pago, valor_pago,
			fecha_recaudo, valor_recaudo, fecha_trans, longitud, latitud, est_codigo FROM trans_detalle_recaudo_ventas_jva 
			WHERE aju_codigo = $aju_codigo AND est_codigo in (3,5,10) order by tv_codigo, cuota_nro limit $inicio,$cantidad";

			$result = $db_link->query($query);
			$retorno = "CODIGO|TV_CODIGO|AJU_CODIGO|CUOTAS_TOTALES|CUOTA_NRO|FECHA_PAGO|VALOR_PAGO|FECHA_RECAUDO|VALOR_RECAUDO|FECHA_TRANS|LONGITUD|LATITUD|EST_CODIGO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
					
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();

				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'logon';

				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
				//echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}

	function ingreso_Transaccion_Recaudo($fecha_recaudo,$valor_recaudo,$longitud,
	$latitud,$est_codigo,$tv_codigo,$aju_codigo,$cuota_numero,$usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;
		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = "UPDATE trans_detalle_recaudo_ventas_jva SET fecha_recaudo='$fecha_recaudo',
			valor_recaudo='$valor_recaudo',fecha_trans = now(),longitud='$longitud',
			latitud='$latitud',est_codigo='$est_codigo'
			WHERE tv_codigo = '$tv_codigo'
			AND aju_codigo = '$aju_codigo'
			AND cuota_nro = '$cuota_numero'";

			$result2 = $db_link->prepare($query);
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
				$retorno = '1'; //CORRECTO
			}
			else
			{
				$db_link->rollBack();
				echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage2";

				$error_tipo = mysql;
				$error_archivo = 'server.argos.class.php';
				$error_accion = update;

				/*
				 * INGRESA LOS DATOS DEL ERROR
				 * */
				$query3 = "INSERT INTO logs_errores_usuarios( aju_codigo, fecha_trans, error_accion, error_archivo,
			 error_tipo,error_codigo,error_descripcion, error_query, ip, longitud, latitud) 
			 VALUES('$aju_codigo',now(),'$error_accion','$error_archivo','$error_tipo','$error','$errorMessage',
			 '$query','$ip','$longitud','$latitud')";

				/*
				 * SE PREPARA EL QUERY
				 * */
				$result3 = $db_link->prepare($query3);
				$db_link->beginTransaction();
				$result3->execute(); //SE EJECUTA EL QUERY
				$arr3 = $result3->errorInfo(); // SE OBTIENE EL ERROR
				$error3 = $arr3[0];
				$errorMessage = str_replace("'", "", $arr3[2]);

				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error3=="00000")
				{
					$db_link->commit();
				}
				else
				{
					$db_link->rollBack();
				}
				$retorno = '0'; //INCORRECTO
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}

	function set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn,$username,$passwd);
			/*
			 * INGRESA LOS DATOS DEL ERROR
			 * */
			$query3 = "INSERT INTO logs_errores_usuarios( aju_codigo, fecha_trans, error_accion, error_archivo,
			 error_tipo,error_codigo,error_descripcion, error_query, ip, longitud, latitud) 
			 VALUES((select fun_obtener_aju_codigo('$usuario')),now(),:error_accion,:error_archivo,:error_tipo,
			 :error,:errorMessage,:query,:ip,:longitud,:latitud)";


			/*
			 * SE PREPARA EL QUERY
			 * */
			$result3 = $db_link->prepare($query3);
			$db_link->beginTransaction();
			$result3->bindParam(':error_accion',$error_accion);
			$result3->bindParam(':error_archivo',$error_archivo);
			$result3->bindParam(':error_tipo',$error_tipo);
			$result3->bindParam(':error',$error);
			$result3->bindParam(':errorMessage',$errorMessage);
			$result3->bindParam(':query',$query);
			$result3->bindParam(':ip',$ip);
			$result3->bindParam(':longitud',$longitud);
			$result3->bindParam(':latitud',$latitud);
			$result3->execute(); //SE EJECUTA EL QUERY
			$arr3 = $result3->errorInfo(); // SE OBTIENE EL ERROR
			$error3 = $arr3[0];
			$errorMessage3 = str_replace("'", "", $arr3[2]);

			/*
			 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
			 * */
			if($error3=="00000")
			{
				$db_link->commit();
			}
			else
			{
				$db_link->rollBack();
				echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage3";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
	}

	function logon2($ip,$longitud,$latitud,$usuario,$pwd)
	{
		/*
		 * CONECCION A LA DB
		 * */
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn,$username,$passwd);


			$query = "select nombres, apellidos
				  from admin_usuarios
				  where username='$usuario' and est_codigo=1";

			$result = $db_link->prepare($query);
			$retorno = "NOMBRES|APELLIDOS";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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

				/*
				 * QUERY QUE REGISTRA EL LOG DE INGRESO
				 * */
				$query2 = "INSERT INTO logs_accesos_usuarios(aju_codigo, fecha_trans, accion, ip, longitud, latitud)
					VALUES ((select fun_obtener_aju_codigo('$usuario')),now(),'login','$ip','$longitud','$latitud')";

				/*
				 * SE PREPARA EL QUERY
				 * */
				$result2 = $db_link->prepare($query2);
				$db_link->beginTransaction();
				$result2->execute(); //SE EJECUTA EL QUERY
				$arr2 = $result2->errorInfo(); // SE OBTIENE EL ERROR
				$error2 = $arr2[0];
				$errorMessage = str_replace("'", "", $arr2[2]);

				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */

				if($error2=="00000")
				{
					$db_link->commit();
					//$retorno2 = '1'; //CORRECTO
				}
				else
				{
					$db_link->rollBack();
					echo $msg2="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
					//$retorno2 = '0'; //INCORRECTO
				}

				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);
					
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
			}
			else
			{
				$db_link->rollBack();
				$retorno = '0';
					
				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'logon';
					
				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
				//echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	
	function contar_trans_detalle_recaudo_ventas_id($tv_codigo,$aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = "SELECT codigo, tv_codigo, aju_codigo, cuotas_totales, cuota_nro, fecha_pago, valor_pago,
		fecha_recaudo, valor_recaudo, fecha_trans, longitud, latitud, est_codigo FROM trans_detalle_recaudo_ventas_jva 
		WHERE aju_codigo = $aju_codigo AND tv_codigo = $tv_codigo AND est_codigo in (3,5,10) ";

			$result = $db_link->query($query);
			$retorno = "CODIGO|TV_CODIGO|AJU_CODIGO|CUOTAS_TOTALES|CUOTA_NRO|FECHA_PAGO|VALOR_PAGO|FECHA_RECAUDO|VALOR_RECAUDO|FECHA_TRANS|LONGITUD|LATITUD|EST_CODIGO";

			$array_enc=explode("|",$retorno);
			$u=sizeof($array_enc);
			//$u = 13;

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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}
	
function Consulta_Trans_Rutas_Detalles_id($tv_codigo,$aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = "SELECT codigo, trj_codigo, tv_codigo, aju_codigo, secuencia, saldo, est_codigo 
			FROM trans_rutas_detalles 
			WHERE aju_codigo = $aju_codigo AND tv_codigo = $tv_codigo AND est_codigo = 1";

			$result = $db_link->query($query);
			$retorno = "CODIGO|TRJ_CODIGO|TV_CODIGO|AJU_CODIGO|SECUENCIA|SALDO|EST_CODIGO";

			$array_enc=explode("|",$retorno);
			$u=sizeof($array_enc);
			//$u = 13;

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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}
	
	
	function consulta_param_periodicidad_jva($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = " SELECT codigo, nombre, descripcion, jva_codigo, secuencia, est_codigo 
			FROM param_periodicidad_jva 
			WHERE jva_codigo in 
			(SELECT jva_codigo FROM admin_jva_usuarios WHERE codigo = $aju_codigo) ";

			$result = $db_link->query($query);
			$retorno = "CODIGO|NOMBRE|DESCRIPCION|CODIGO_JVA|SECUENCIA|EST_CODIGO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();

				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'consulta_param_periodicidad_jva';

				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}
	
	function consulta_param_numero_cuotas_jva($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = " SELECT codigo, nombre, descripcion, jva_codigo, secuencia, est_codigo 
			FROM param_numero_cuotas_jva
			WHERE jva_codigo in 
			(SELECT jva_codigo FROM admin_jva_usuarios WHERE codigo = $aju_codigo) ";
			
			$result = $db_link->query($query);
			$retorno = "CODIGO|NOMBRE|DESCRIPCION|CODIGO_JVA|SECUENCIA|EST_CODIGO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();

				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'consulta_param_numero_cuotas_jva';

				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
				//echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}
	
	function consulta_tipos_motivos_no_pago($usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = "SELECT codigo, nombre, descripcion, est_codigo FROM tipos_motivos_no_pago ";

			$result = $db_link->query($query);
			$retorno = "CODIGO|NOMBRE|DESCRIPCION|EST_CODIGO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();

				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'consulta_tipos_motivos_no_pago';

				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
				//echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}
	
	function ingreso_trans_detalle_recaudo_no_pago($tdrvj_codigo,$tmnp_codigo,$est_codigo,$usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{

			$db_link = new PDO($dsn,$username,$passwd);

			$query = "INSERT INTO trans_detalle_recaudo_no_pago(tdrvj_codigo, tmnp_codigo, fecha_trans, est_codigo) 
			VALUES ($tdrvj_codigo,$tmnp_codigo,now(),$est_codigo)";

			/*
			 * SE PREPARA EL QUERY
			 * */
			$result3 = $db_link->prepare($query);
			$db_link->beginTransaction();
			$result3->bindParam(':tdrvj_codigo',$tdrvj_codigo);
			$result3->bindParam(':tmnp_codigo',$tmnp_codigo);
			$result3->bindParam(':est_codigo',$est_codigo);
			$result3->execute(); //SE EJECUTA EL QUERY
			$arr3 = $result3->errorInfo(); // SE OBTIENE EL ERROR
			$error3 = $arr3[0];
			$errorMessage3 = str_replace("'", "", $arr3[2]);
			
			/*
			 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
			 * */
			if($error3=="00000")
			{
				$db_link->commit();
				$retorno = '1';
			}
			else
			{
				$db_link->rollBack();
				echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage3";
				$retorno = '0';
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	function consulta_param_tipo_productos_jva($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = " SELECT codigo, nombre, descripcion, jva_codigo, porcentaje_utilidad_cliente, 
			porcentaje_utilidad_jva, porcentaje_utilidad_distribuidor, est_codigo FROM param_tipos_productos_jva 
			WHERE jva_codigo IN (SELECT jva_codigo FROM admin_jva_usuarios WHERE codigo =$aju_codigo) ";

			$result = $db_link->query($query);
			$retorno = "CODIGO|NOMBRE|DESCRIPCION|CODIGO_JVA|PORCENTJE_UTI_CLI|PORCENTJE_UTI_JVA|PORCENTJE_UTI_DISTRI|EST_CODIGO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();

				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'consulta_param_tipo_productos_jva';

				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
				//echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}
	
	function consulta_Visitas_V2($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);
			
			 $fecha = date('Y-m-d');
			 $fecha_desde = $fecha." 00:00:00";
			 $fecha_hasta = $fecha." 23:59:59";
			
			$query = " SELECT COUNT( DISTINCT (tv_codigo)) 
			FROM  trans_detalle_recaudo_ventas_jva
			WHERE aju_codigo =$aju_codigo
			AND fecha_recaudo 
			BETWEEN  '$fecha_desde' AND  '$fecha_hasta'  and est_codigo=4 ";

			$result = $db_link->query($query);
			$retorno = "CANTIDAD";

			$array_enc=explode("|",$retorno);
			$u=sizeof($array_enc);
			//$u = 13;

			$retorno=$retorno. "\n";
			$j=0;
			while($row = $result->fetch(PDO::FETCH_NUM))
			{
				for($k=0; $k<$u; $k++)
				{
					$registros[$j][$k]= $row[$k];
					$retorno.=$registros[$j][$k];

					if($k!=$u-1)
					{
						$retorno.="|";
					}
				}
				$j++;
				$retorno.="\n";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}
	
	function consulta_Recaudos($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			 $fecha = date('Y-m-d');
			 $fecha_desde = $fecha." 00:00:00";
			 $fecha_hasta = $fecha." 23:59:59";
			
			$query = "SELECT SUM( valor_recaudo ) FROM trans_detalle_recaudo_ventas_jva
					WHERE aju_codigo = $aju_codigo
					AND fecha_trans BETWEEN '$fecha_desde' AND '$fecha_hasta'
					AND est_codigo =4 ";

			$result = $db_link->query($query);
			$retorno = "CANTIDAD";

			$array_enc=explode("|",$retorno);
			$u=sizeof($array_enc);
			//$u = 13;

			$retorno=$retorno. "\n";
			$j=0;
			while($row = $result->fetch(PDO::FETCH_NUM))
			{
				for($k=0; $k<$u; $k++)
				{
					$registros[$j][$k]= $row[$k];
					$retorno.=$registros[$j][$k];

					if($k!=$u-1)
					{
						$retorno.="|";
					}
				}
				$j++;
				$retorno.="\n";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}
	
	function consulta_Colocaciones($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = "select  sum(valor_producto) from  trans_ventas where aju_codigo= $aju_codigo 
			and fecha_entrega = DATE(  'Y-m-d' ) ";

			$result = $db_link->query($query);
			$retorno = "CANTIDAD";

			$array_enc=explode("|",$retorno);
			$u=sizeof($array_enc);
			//$u = 13;

			$retorno=$retorno. "\n";
			$j=0;
			while($row = $result->fetch(PDO::FETCH_NUM))
			{
				for($k=0; $k<$u; $k++)
				{
					$registros[$j][$k]= $row[$k];
					$retorno.=$registros[$j][$k];

					if($k!=$u-1)
					{
						$retorno.="|";
					}
				}
				$j++;
				$retorno.="\n";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}
	
	function consulta_Recaudos_Diarios($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = "SELECT clientes.referencia AS ARGOS, clientes.nombre1_contacto AS NOMBRE, 
			clientes.apellido1_contacto AS PELLIDO, trans.valor_recaudo AS RECAUDO 
			FROM trans_detalle_recaudo_ventas_jva AS trans INNER 
			JOIN trans_ventas AS ventas ON ventas.codigo = trans.tv_codigo 
			INNER JOIN admin_clientes AS clientes ON clientes.codigo = ventas.cli_codigo 
			WHERE (trans.aju_codigo = $aju_codigo) 
			AND trans.est_codigo = 4
			AND trans.fecha_trans = DATE(  'Y-m-d' ) ";

			$result = $db_link->query($query);
			$retorno = "CANTIDAD";

			$array_enc=explode("|",$retorno);
			$u=sizeof($array_enc);
			//$u = 13;

			$retorno=$retorno. "\n";
			$j=0;
			while($row = $result->fetch(PDO::FETCH_NUM))
			{
				for($k=0; $k<$u; $k++)
				{
					$registros[$j][$k]= $row[$k];
					$retorno.=$registros[$j][$k];

					if($k!=$u-1)
					{
						$retorno.="|";
					}
				}
				$j++;
				$retorno.="\n";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}
	
	function consulta_tipos_pago($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = " SELECT codigo, nombre, descripcion, jva_codigo, porcentaje_utilidad_cliente, 
			porcentaje_utilidad_jva, porcentaje_utilidad_distribuidor, est_codigo FROM param_tipos_productos_jva 
			WHERE jva_codigo IN (SELECT jva_codigo FROM admin_jva_usuarios WHERE codigo =$aju_codigo) ";

			$result = $db_link->query($query);
			$retorno = "CODIGO|NOMBRE|DESCRIPCION|CODIGO_JVA|PORCENTJE_UTI_CLI|PORCENTJE_UTI_JVA|PORCENTJE_UTI_DISTRI|EST_CODIGO";

			$array_enc=explode("|",$retorno);
			$u=sizeof($array_enc);
			//$u = 13;

			$retorno=$retorno. "\n";
			$j=0;
			while($row = $result->fetch(PDO::FETCH_NUM))
			{
				for($k=0; $k<$u; $k++)
				{
					$registros[$j][$k]= $row[$k];
					$retorno.=$registros[$j][$k];

					if($k!=$u-1)
					{
						$retorno.="|";
					}
				}
				$j++;
				$retorno.="\n";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}
	
	
	
	
	function trans_historico_recaudo_ventas_jva($tv_codigo,$aju_codigo,$tdrvj_codigo,$cuota_nro,$fecha_recaudo,
	$valor_recaudo,$longitud,$latitud,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{

			$db_link = new PDO($dsn,$username,$passwd);

			$query = "INSERT INTO trans_historico_recaudo_ventas_jva
( tv_codigo, aju_codigo, tdrvj_codigo, cuota_nro, fecha_recaudo, valor_recaudo, fecha_trans, longitud, latitud, est_codigo) 
VALUES ('$tv_codigo','$aju_codigo','$tdrvj_codigo','$cuota_nro','$fecha_recaudo','$valor_recaudo',now(),'$longitud','$latitud','1')";

			/*
			 * SE PREPARA EL QUERY
			 * */
			$result3 = $db_link->prepare($query);
			$db_link->beginTransaction();
			$result3->execute(); //SE EJECUTA EL QUERY
			$arr3 = $result3->errorInfo(); // SE OBTIENE EL ERROR
			$error3 = $arr3[0];
			$errorMessage3 = str_replace("'", "", $arr3[2]);
			
			/*
			 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
			 * */
			if($error3=="00000")
			{
				$db_link->commit();
				$retorno = '1';
			}
			else
			{
				$db_link->rollBack();
				echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage3";
				$retorno = '0';
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
			//echo $e->getMessage();
			//echo $username." - ".$passwd;
			
			
		}
		return $retorno;
	}
	
function Consulta_trans_historico_recaudo_ventas_jva($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = " SELECT tv_codigo, aju_codigo, tdrvj_codigo, cuota_nro, fecha_recaudo, valor_recaudo, fecha_trans, longitud, latitud, est_codigo 
			FROM trans_historico_recaudo_ventas_jva WHERE aju_codigo = $aju_codigo ";

			$result = $db_link->query($query);
			$retorno = "TV_CODIGO|AJU_CODIGO|TDRVJ_COD|CUOTA_NRO|FECHA_REC|VALOR_REC|FECHA_TRANS|LONGITUD|LATITUD|EST_CODIGO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();

				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'Consulta_trans_historico_recaudo_ventas_jva';

				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
				//echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}
	
	function Ingresar_trans_traslados_inventarios_jva($pti_codigo,$aju_codigo_desde,$aju_codigo_hasta,$ppj_codigo,
	$cantidad,$fecha_traslado,$fecha_verificacion, $latitud,$longitud,$est_codigo,
	$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{

			$db_link = new PDO($dsn,$username,$passwd);

			$query = "INSERT INTO trans_traslados_inventarios_jva( ptij_codigo, aju_codigo_desde, aju_codigo_hasta, 
			ppj_codigo, cantidad,fecha_traslado, fecha_aprovacion, fecha_verificacion, fecha_trans,latitud, longitud, 
			est_codigo) 
			VALUES ('$pti_codigo','$aju_codigo_desde','$aju_codigo_hasta','$ppj_codigo','$cantidad',
			'$fecha_traslado',now(),'$fecha_verificacion',now(),'$latitud','$longitud','$est_codigo')";

			/*
			 * SE PREPARA EL QUERY
			 * */
			$result3 = $db_link->prepare($query);
			$db_link->beginTransaction();
			$result3->execute(); //SE EJECUTA EL QUERY
			$arr3 = $result3->errorInfo(); // SE OBTIENE EL ERROR
			$error3 = $arr3[0];
			$errorMessage3 = str_replace("'", "", $arr3[2]);
			
			/*
			 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
			 * */
			if($error3=="00000")
			{
				$db_link->commit();
				$retorno = '1';
			}
			else
			{
				$db_link->rollBack();
				echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage3";
				$retorno = '0';
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	function Consulta_trans_traslados_inventarios_jva($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = " SELECT pti_codigo, aju_codigo_desde, aju_codigo_hasta, 
			ppj_codigo, pttij_codigo, cantidad, fecha_traslado, fecha_aprovacion, 
			fecha_verificacion, latitud, longitud, est_codigo 
			FROM trans_traslados_inventarios_jva WHERE aju_codigo_desde = $aju_codigo 
			OR aju_codigo_hasta = $aju_codigo ";

			$result = $db_link->query($query);
			$retorno = "PTI_CODIGO|AJU_CODIGO_DESDE|AJU_CODIGO_HASTA|PPJ_CODIGO|PTTIJ_CODIGO|CANTIDAD|FECHA_TRANSLADO|FECHA_APROV|FECHA_VERI|LATITUD|LONGITUD|EST_CODIGO";

			$array_enc=explode("|",$retorno);
			$u=sizeof($array_enc);
			//$u = 13;

			$retorno=$retorno. "\n";
			$j=0;
			while($row = $result->fetch(PDO::FETCH_NUM))
			{
				for($k=0; $k<$u; $k++)
				{
					$registros[$j][$k]= $row[$k];
					$retorno.=$registros[$j][$k];

					if($k!=$u-1)
					{
						$retorno.="|";
					}
				}
				$j++;
				$retorno.="\n";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
		//echo $retorno;
	}
	
	function Consulta_trans_inventarios_jva($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = " SELECT codigo, aju_codigo, pro_codigo, cantidad, fecha_inventario, 
			fecha_transaccion, est_codigo FROM trans_inventarios_jva 
			WHERE aju_codigo = $aju_codigo ";

			$result = $db_link->query($query);
			$retorno = "CODIGO|AJU_CODIGO|PRO_CODIGO|CANTIDAD|FECHA_INVENTARIO|FECHA_TRANSACCION|EST_CODIGO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();

				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'logon';

				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
				//echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
function Consulta_Visitas($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$fecha = date('Y-m-d');
			 $fecha_desde = $fecha." 00:00:00";
			 $fecha_hasta = $fecha." 23:59:59";
			
			$query = "SELECT COUNT( DISTINCT (tv_codigo)) 
			FROM  trans_detalle_recaudo_ventas_jva
			WHERE aju_codigo =$aju_codigo
			AND fecha_recaudo 
			BETWEEN  '$fecha_desde' AND  '$fecha_hasta'";

			$result = $db_link->query($query);
			$retorno = "CANTIDAD";

			$array_enc=explode("|",$retorno);
			$u=sizeof($array_enc);
			//$u = 13;

			$retorno=$retorno. "\n";
			$j=0;
			while($row = $result->fetch(PDO::FETCH_NUM))
			{
				for($k=0; $k<$u; $k++)
				{
					$registros[$j][$k]= $row[$k];
					$retorno.=$registros[$j][$k];

					if($k!=$u-1)
					{
						$retorno.="|";
					}
				}
				$j++;
				$retorno.="\n";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
function Consulta_param_traslados_inventarios_jva($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);
			
			$query = "SELECT codigo, nombre, jva_codigo, tti_codigo, rol_codigo_desde, rol_codigo_hasta, ppj_codigo, 
			cantidad_minima, cantidad_maxima, requiere_autorizacion, requiere_verificacion, est_codigo
			FROM param_traslados_inventarios_jva WHERE jva_codigo
			IN ( SELECT jva_codigo FROM admin_jva_usuarios WHERE codigo =$aju_codigo) AND rol_codigo_desde =6 
			AND rol_codigo_hasta in (6,3)";

			$result = $db_link->query($query);
			$retorno = "CODIGO|NOMBRE|JVA_CODIGO|ROL_CODIGO_DESDE|ROL_CODIGO_HASTA|PPJ_CODIGO|CANT_MIN|CANT_MAX|AUTORIZACION|VERIFICACION|ESTADO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();

				$error_tipo = 'mysql';
				$error_archivo = 'server.argos.class.php';
				$error_accion = 'Consulta_param_traslados_inventarios_jva';

				$consulta= new Consultas();
				$consulta->set_logs_errors($usuario,$pwd,$error_accion,$error_archivo,$error_tipo,$error,$errorMessage,$query,$ip,$longitud,$latitud);
				//echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	function Consulta_Total_Diarios($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		//Try
		
			$db_link = new PDO($dsn, $username, $passwd);
			
			$query = "  ";

			$result = $db_link->query($query);
			$retorno = "CODIGO|JVA_CODIGO|ROL_CODIGO_DESDE|ROL_CODIGO_HASTA|PPJ_CODIGO|CANT_MIN|CANT_MAX|AUTORIZACION|VERIFICACION|ESTADO";

			$array_enc=explode("|",$retorno);
			$u=sizeof($array_enc);
			//$u = 13;

			$retorno=$retorno. "\n";
			$j=0;
			while($row = $result->fetch(PDO::FETCH_NUM))
			{
				for($k=0; $k<$u; $k++)
				{
					$registros[$j][$k]= $row[$k];
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
	}
	
	function conslta_param_gastos_jva($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;
		
		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);
			
			$query = " SELECT codigo, nombre, descripcion, tg_codigo, valor_min, valor_max, jva_codigo, rol_codigo, 
			est_codigo FROM param_gastos_jva WHERE jva_codigo 
			IN (SELECT jva_codigo FROM admin_jva_usuarios WHERE codigo =$aju_codigo) ";

			$result = $db_link->query($query);
			$retorno = "CODIGO|NOMBRE|DESCRIPCION|TG_CODIGO|VALOR_MIN|VALOR_MAX|JVA_CODIGO|ROL_CODIGO|EST_CODIGO";

			$array_enc=explode("|",$retorno);
			$u=sizeof($array_enc);
			//$u = 13;

			$retorno=$retorno. "\n";
			$j=0;
			while($row = $result->fetch(PDO::FETCH_NUM))
			{
				for($k=0; $k<$u; $k++)
				{
					$registros[$j][$k]= $row[$k];
					$retorno.=$registros[$j][$k];

					if($k!=$u-1)
					{
						$retorno.="|";
					}
				}
				$j++;
				$retorno.="\n";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	function conslta_trans_gastos_jva($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;
		
		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);
			
			$query = " SELECT codigo, fecha_gasto, aju_codigo, pgj_codigo, valor, fecha_trans, longitud, latitud, est_codigo 
			FROM trans_gastos_jva 
			WHERE aju_codigo = $aju_codigo ";

			$result = $db_link->query($query);
			$retorno = "CODIGO|FECHA_GASTO|AJU_CODGIO|PGJ_CODIGO|VALOR|FECHA_TRANS|LONGITUD|LATITUD|EST_CODIGO";

			$array_enc=explode("|",$retorno);
			$u=sizeof($array_enc);
			//$u = 13;

			$retorno=$retorno. "\n";
			$j=0;
			while($row = $result->fetch(PDO::FETCH_NUM))
			{
				for($k=0; $k<$u; $k++)
				{
					$registros[$j][$k]= $row[$k];
					$retorno.=$registros[$j][$k];

					if($k!=$u-1)
					{
						$retorno.="|";
					}
				}
				$j++;
				$retorno.="\n";
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
function insertar_trans_gastos_jva($fecha_gasto,$aju_codigo,$pgj_codigo,$valor,$longitud,$latitud,$est_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{

			$db_link = new PDO($dsn,$username,$passwd);

			$query = "INSERT INTO trans_gastos_jva( fecha_gasto, aju_codigo, pgj_codigo, valor, fecha_trans, longitud, latitud, est_codigo) 
			VALUES ('$fecha_gasto','$aju_codigo','$pgj_codigo','$valor',now(),'$longitud','$latitud',$est_codigo)";

			/*
			 * SE PREPARA EL QUERY
			 * */
			$result3 = $db_link->prepare($query);
			$db_link->beginTransaction();
			$result3->execute(); //SE EJECUTA EL QUERY
			$arr3 = $result3->errorInfo(); // SE OBTIENE EL ERROR
			$error3 = $arr3[0];
			$errorMessage3 = str_replace("'", "", $arr3[2]);
			
			/*
			 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
			 * */
			if($error3=="00000")
			{
				$db_link->commit();
				$retorno = '1';
			}
			else
			{
				$db_link->rollBack();
				echo $msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage3";
				$retorno = '0';
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	
	function Modificar_Cli_Actualizado($codigo,$ti_codigo,$nroidentificacion,$referencia,$razon_social,$tn_codigo,$nombre1_contacto,$nombre2_contacto,$apellido1_contacto,$apellido2_contacto,
	$telefono1,$ext1,$telefono2,$ext2,$celular1,$celular2,$email,$barrio,$direccion,$comentario,$ciu_codigo,$est_codigo,$usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = "UPDATE admin_clientes SET codigo='$codigo',ti_codigo='$ti_codigo',nroidentificacion='$nroidentificacion',
			referencia='$referencia',razon_social='$razon_social',tn_codigo='$tn_codigo',nombre1_contacto='$nombre1_contacto',
			nombre2_contacto='$nombre2_contacto',apellido1_contacto='$apellido1_contacto',
			apellido2_contacto='$apellido2_contacto',telefono1='$telefono1',ext1='$ext1',telefono2='$telefono2',ext2='$ext2',
			celular1='$celular1',celular2='$celular2',email='$email',barrio='$barrio',direccion='$direccion',comentario='$comentario',
			calificacion='$calificacion',ciu_codigo='$ciu_codigo',est_codigo='$est_codigo' WHERE codigo='$codigo'";
			/*
			 * SE PREPARA EL QUERY
			 * */
			$result = $db_link->prepare($query);
			$db_link->beginTransaction();
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
function Modificar_Sticker_Actualizado($codigo, $codigo_barras,$usuario, $pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

			$query = "UPDATE trans_ventas SET codigo_barras = '$codigo_barras' WHERE codigo='$codigo'";
			/*
			 * SE PREPARA EL QUERY
			 * */
			$result = $db_link->prepare($query);
			$db_link->beginTransaction();
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
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	
	
	function Consulta_param_productos_jva($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);


			$query = "SELECT codigo, nombre, descripcion, um_codigo, jva_codigo, tpj_codigo, cat_codigo, peso, 
			alto, ancho, profundo, volumen, ruta_img, est_codigo FROM param_productos_jva WHERE jva_codigo IN 
			(SELECT jva_codigo FROM admin_jva_usuarios WHERE codigo =$aju_codigo)";
			
			$result = $db_link->prepare($query);
			$retorno = "CODIGO|NOMBRE|DESCRIPCION|UM_CODIGO|JVA_CODIGO|TPJ_CODIGO|CAT_CODIGO|PESO|ALTO|ANCHO|PROFUNDO|VOLUMEN|RUTA_IMG|EST_CODIGO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	function Consultaparam_tipos_tran_inventarios_jva($aju_codigo,$usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);


			$query = "SELECT codigo, nombre, descripcion, jva_codigo, est_codigo FROM param_tipos_tran_inventarios_jva WHERE jva_codigo IN 
			(SELECT jva_codigo FROM admin_jva_usuarios WHERE codigo =$aju_codigo)";
			
			$result = $db_link->prepare($query);
			$retorno = "CODIGO|NOMBRE|DESCRIPCION|JVA_CODIGO|EST_CODIGO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	
	function Consulta_tipos_negocios($usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);


			$query = "SELECT codigo, nombre, descripcion, est_codigo FROM tipos_negocios";
			
			$result = $db_link->prepare($query);
			$retorno = "CODIGO|NOMBRE|DESCRIPCION|EST_CODIGO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	
function Consulta_Trans_Usuario_Jva($aju_codigo, $usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);


			$query = "SELECT aju.codigo,admin.nombres, admin.apellidos
			FROM admin_usuarios admin, admin_jva_usuarios aju
			WHERE admin.codigo = aju.usu_codigo
			AND rol_codigo = 6
			AND jva_codigo IN ( SELECT jva_codigo 
			FROM admin_jva_usuarios WHERE codigo ='$aju_codigo')
			AND aju.codigo != '$aju_codigo'";
			
			$result = $db_link->prepare($query);
			$retorno = "CODIGO|NOMBRE|APELLIDO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	
	function Consulta_Trans_Ingresa_jva($aju_codigo, $usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

 			 $fecha = date('Y-m-d');
			 $fecha_desde = $fecha." 00:00:00";
			 $fecha_hasta = $fecha." 23:59:59";
			 
			$query = "SELECT trans.cantidad AS CANTIDAD, param.nombre AS NOMBRE
					FROM trans_traslados_inventarios_jva trans,
					param_traslados_inventarios_jva param
					WHERE param.codigo = trans.ptij_codigo
					AND aju_codigo_hasta = $aju_codigo 
					AND fecha_trans 
					BETWEEN '$fecha_desde' 
					AND '$fecha_hasta'";
			
			$result = $db_link->prepare($query);
			$retorno = "VALOR|TIPO";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	
	
	function Trans_Recibe_Vendedor($aju_codigo, $usuario,$pwd)
	{
		global $DATABASE_NAME;
		global $dsn ;
		$username=$usuario;
		$passwd=$pwd;

		Try
		{
			$db_link = new PDO($dsn, $username, $passwd);

 			 $fecha = date('Y-m-d');
			 $fecha_desde = $fecha." 00:00:00";
			 $fecha_hasta = $fecha." 23:59:59";
			 
			$query = "SELECT roles.nombre AS ROLL, usuarios.nombres AS NOMBRES, usuarios.apellidos AS APELLIDOS, 
			SUM(cantidad) AS CANTIDAD 
			FROM trans_traslados_inventarios_jva AS traslados, 
			admin_jva_usuarios AS aju, admin_usuarios AS usuarios, 
			admin_roles AS roles 
			WHERE usuarios.codigo = aju.usu_codigo 
			AND aju.codigo = traslados.aju_codigo_desde 
			AND aju.rol_codigo = roles.codigo   
			AND aju_codigo_hasta = $aju_codigo 
			AND fecha_traslado BETWEEN '$fecha_desde' 
			AND '$fecha_hasta'";
			
			$result = $db_link->prepare($query);
			$retorno = "ROLL|NOMBRES|APELLIDOS|VALOR";
			$retorno=$retorno. "\n";
			$db_link->beginTransaction();
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
				/*
				 * PREPARA Y GENERA LA INFORMACION PARA MOSTRAR
				 * */
				$array_enc=explode("|",$retorno);
				$u=sizeof($array_enc);

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
			}
			else
			{
				$db_link->rollBack();
			}
		}
		catch(PDOException $e)
		{
			$retorno = '0';
		}
		return $retorno;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>