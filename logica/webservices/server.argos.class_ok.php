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
                $retorno = "NOBRES|APELLIDOS|";
                $retorno=$retorno. "\n";

                 $array [] = $result->fetch(PDO::FETCH_NUM);
                 //print_r($array);
                 
		for($i=0;$i<sizeof($array);$i++)
		{
			for($k=0; $k<=sizeof($array); $k++)
			{
            $retorno=$retorno.$array[$i][$k]."|";	
			}
		}
        return $retorno;
	}
	
	function Consulta_Cliente($Idcliente, $Idfuncionario, $usuario, $pwd)
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
                 //print_r($contar);
                 //$arreglo2 = split("|", $string);
                 
		for($i=0;$i<sizeof($array);$i++)
		{
			for($k=0; $k<=5; $k++)
			{
            $retorno=$retorno.$array[$i][$k]."|";	
			}
		}
        return $retorno;
	}
	

	
	
}
?>
