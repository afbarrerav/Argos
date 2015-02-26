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
	$msg="";
	if($accion == "mostrar_front_param")
	{
		include ('../presentacion/admin_param.php');
	}
	
	if($accion == "form_editar_param")
	{
		$tabla 	= $_REQUEST['TABLA'];
		$codigo = $_REQUEST['CODIGO'];
		
		/*
		 * OBTIENE LOS ATRIBUTOS DE LA TABLA
		 * */
		$query ="describe $tabla";
		$result = $db_link->query($query);
		$i=0;
		$variable_atributos = "";
		while($row = $result->fetch(PDO::FETCH_NUM))
		{
			$atributos[$i] = $row[0];
			$variable_atributos .= $atributos[$i].", ";
			$i++;
		}		
		$variable_atributos = substr($variable_atributos, 0, strlen($variable_atributos)-2);
		
		/*
		 * CONSULTA LOS REGISTROS DE LA TABLA
		 * */
		$query = "select $variable_atributos 
					from $tabla
					where codigo = $codigo";
		$result = $db_link->query($query);
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			for($k=0; $k<$i; $k++)
			{
				$registros[$atributos[$k]]= ($row[$atributos[$k]]);
			}
		}
		include ('../presentacion/admin_param_e.php');
	}
	
	if($accion == "form_nuevo_param")
	{
		$tabla 	= $_REQUEST['TABLA'];
		
		/*
		 * OBTIENE LOS ATRIBUTOS DE LA TABLA
		 * */
		$query ="describe $tabla";
		$result = $db_link->query($query);
		$i=0;
		$variable_atributos = "";
		while($row = $result->fetch(PDO::FETCH_NUM))
		{
			$atributos['Field'][$i] = $row[0];
			$atributos['Type'][$i] = $row[1];
			$atributos['Null'][$i] = $row[2];
			$i++;
		}		
		include ('../presentacion/admin_param_n.php');
	}
	
	if($accion == "editar_param")
	{
		$tabla 	= $_REQUEST['TABLA'];
		
		/*
		 * OBTIENE LOS ATRIBUTOS DE LA TABLA
		 * */
		$query ="describe $tabla";
		$result = $db_link->query($query);
		$i=0;
		$variable_atributos = "";
		while($row = $result->fetch(PDO::FETCH_NUM))
		{
			$atributos[$i] = $row[0];
			$i++;
		}		
		
		/*
		 * CONSULTA LOS REGISTROS DE LA TABLA
		 * */
		$query = "update $tabla set ";
		for ($j=0;$j<$i;$j++)
		{
			$query .= $atributos[$j]."='".$_REQUEST[$atributos[$j]]."', ";
		}
		$query = substr($query, 0, strlen($query)-2);
		$query .=" where codigo = ".$_REQUEST['codigo'];
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
			/*
			 * RETIRA O PONE LOS PERMISOS AL USUARIO A NIVEL DE BD DE ACUERDO AL ESTADO 
			 * QUE HA SIDO ACTUALIZADO
			 * */	
			$accion = "sadministarIC";
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";	
		}		
		$accion = "sadministarIC";
	}
	
	if($accion == "guardar_nuevo_param")
	{
		$tabla 	= $_REQUEST['TABLA'];
		/*
		 * OBTIENE LOS ATRIBUTOS DE LA TABLA
		 * */
		$query ="describe $tabla";
		$result = $db_link->query($query);
		$i=0;
		$variable_atributos = "";
		while($row = $result->fetch(PDO::FETCH_NUM))
		{
			$atributos[$i] = $row[0];
			$i++;
		}		
		//echo $atributos[$i];
		/*
		 * CONSULTA LOS REGISTROS DE LA TABLA
		 * */
		$query = "insert into $tabla (";
		for ($j=0;$j<$i;$j++)
		{
			if($atributos[$j]!="codigo" && $atributos[$j]!="est_codigo")
			{
				$query .= $atributos[$j].", ";
			}
		}
		$query .= "est_codigo) values(";
		for ($j=0;$j<$i;$j++)
		{
			if($atributos[$j]!="codigo" && $atributos[$j]!="est_codigo")
			{
				$query .= "'".$_REQUEST[$atributos[$j]]."', ";
			}
		}
		$query .="1);";
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
			/*
			 * RETIRA O PONE LOS PERMISOS AL USUARIO A NIVEL DE BD DE ACUERDO AL ESTADO 
			 * QUE HA SIDO ACTUALIZADO
			 * */	
			$accion = "sadministarIC";
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";	
		}		
		$accion = "sadministarIC";
	}
	
	if($accion == "actualizar_estado")
	{
		$tabla 		= $_REQUEST['TABLA'];
		$codigo 	= $_REQUEST['CODIGO'];
		$est_codigo = $_REQUEST['EST_CODIGO'];		
		/*
		 * OBTIENE LOS ATRIBUTOS DE LA TABLA
		 * */
		$query ="UPDATE $tabla set est_codigo = $est_codigo where codigo = $codigo";
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
			/*
			 * RETIRA O PONE LOS PERMISOS AL USUARIO A NIVEL DE BD DE ACUERDO AL ESTADO 
			 * QUE HA SIDO ACTUALIZADO
			 * */	
			$accion = "sadministarIC";
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";	
		}			
		$accion = "sadministarIC";
	}
	
	if($accion == "sadministarIC")
	{
		$tabla = $_REQUEST['TABLA'];
		/*
		 * OBTIENE LOS ATRIBUTOS DE LA TABLA
		 * */
		$query ="describe $tabla";
		$result = $db_link->query($query);
		$i=0;
		$variable_atributos = "";
		//echo $query;
		while($row = $result->fetch(PDO::FETCH_NUM))
		{
			$atributos[$i] = $row[0];
			$variable_atributos .= $atributos[$i].", ";
			$i++;
		}		
		$variable_atributos = substr($variable_atributos, 0, strlen($variable_atributos)-2);
		/*
		 * CONSULTA LOS REGISTROS DE LA TABLA
		 * */
		$query = "select $variable_atributos 
					from $tabla
					where jva_codigo in (select jva_codigo from admin_jva_usuarios where usu_codigo = '$usu_codigo')
					order by jva_codigo";
		//echo $query;
		$result = $db_link->query($query);
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
		include ('../presentacion/admin_param_detalle.php');
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