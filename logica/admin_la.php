<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com
 * @version:1.0.0
 * @fecha:	Junio de 2011
 *
 * */
//session_start();
include_once ("../logica/variables_session.php");
try
{
	$db_link = new PDO($dsn, $username, $passwd);
	$accion = $_REQUEST['ACCION'];
	if($accion == "mostrar_front")
	{
		include ('../presentacion/admin_la.php');
	}

	if($accion == "consultar")
	{
		
		$tabla 		= $_REQUEST['TABLA'];
		$atributo	= $_REQUEST['ATRIBUTO'];
		$parametro	= $_REQUEST['PARAMETRO'];
		
		/*
		 * OBTIENE LOS ATRIBUTOS DE LA TABLA EN LA BASE DE DATOS au_$DATABASE_NAME
		 * */ 
		
		$query ="describe au_$DATABASE_NAME.$tabla";
		$result = $db_link->query($query);
		$i=0;
		$variable_atributos = "";
		while($row = $result->fetch(PDO::FETCH_NUM))
		{
			$atributos[$i] = $row[0];
			$variable_atributos .= "au.".$atributos[$i].", ";
			$i++;
		}	
		
		$variable_atributos = substr($variable_atributos, 0, strlen($variable_atributos)-2);
		
		/*
		 * OBTIENE LOS ATRIBUTOS DE LA TABLA EN LA BASE DE DATOS $DATABASE_NAME
		 * */ 
		$query2 ="describe $DATABASE_NAME.$tabla";
		$result2 = $db_link->query($query2);
		$i2=0;
		$variable_atributos2 = "";
		while($row2 = $result2->fetch(PDO::FETCH_NUM))
		{
			$atributos2[$i2] = $row2[0];
			$variable_atributos2 .= "coo.".$atributos2[$i2].", ";
			$i2++;
		}
			
		$variable_atributos2 = substr($variable_atributos2, 0, strlen($variable_atributos2)-2);
		/*
		 * CONSULTA LOS REGISTROS DE LA TABLA
		 * */
		//echo $tabla;
		if($tabla == "logs_accesos")
		{
			$query = 	"SELECT *
						FROM au_$DATABASE_NAME.logs_accesos au
						WHERE au.$atributo LIKE '%$parametro%'
						UNION (
						
						SELECT * , '', '', 'ACTUAL'
						FROM $DATABASE_NAME.logs_accesos coo
						WHERE coo.fecha_ingreso = (
						SELECT max( coo.fecha_ingreso )
						FROM $DATABASE_NAME.logs_accesos coo
						WHERE coo.$atributo LIKE '%$parametro%' )
						)";
			
		}
		else 
		{
			$query = "SELECT $variable_atributos
						FROM au_$DATABASE_NAME.$tabla au
						where $atributo LIKE '%$parametro%'
						UNION 
						SELECT $variable_atributos2, '', '', 'ACTUAL'
						FROM $DATABASE_NAME.$tabla coo
						where $atributo LIKE '%$parametro%'";
		}
		$result = $db_link->query($query);
		//echo $query;
		$j=0;
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			for($k=0; $k<$i; $k++)
			{
				$registros[$atributos[$k]][$j]= ($row[$atributos[$k]]);
			}
			$j++;
			
		}
		/*
		 * REALIZA EL LLAMADO AL ARCHIVO DE PRESENTACION
		 * */		
		include ('../presentacion/admin_la_detalle.php');
	}

	if($accion == "consultar_logs")
	{
		$tabla 		= $_REQUEST['TABLA'];
		$atributo	= $_REQUEST['ATRIBUTO'];
		$parametro	= $_REQUEST['PARAMETRO'];
		
		/*
		 * OBTIENE LOS ATRIBUTOS DE LA TABLA EN LA BASE DE DATOS $DATABASE_NAME
		 * */ 
		$query ="describe $DATABASE_NAME.$tabla";
		$result = $db_link->query($query);
		$i=0;
		$variable_atributos = "";
		while($row = $result->fetch(PDO::FETCH_NUM))
		{
			$atributos[$i] = $row[0];
			$variable_atributos .= $atributos[$i].", ";
			$i++;
		}
		//print_r($variable_atributos);	
		$variable_atributos = substr($variable_atributos, 0, strlen($variable_atributos)-2);
		/*
		 * CONSULTA LOS REGISTROS DE LA TABLA
		 * */
		$query = "SELECT $variable_atributos
					FROM $DATABASE_NAME.$tabla
					where $atributo LIKE '%$parametro%'";
		
		$result = $db_link->query($query);
		$j=0;
		//print_r($query);
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			for($k=0; $k<$i; $k++)
			{
				$registros[$atributos[$k]][$j]= ($row[$atributos[$k]]);
			}
			$j++;
			
		}
		/*
		 * REALIZA EL LLAMADO AL ARCHIVO DE PRESENTACION
		 * */		
		include ('../presentacion/admin_la_detalle.php');
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