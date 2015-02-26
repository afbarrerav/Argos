<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Diciembre de 2011
 * 
 * */
//session_start();
include_once ("../logica/variables_session.php");

try
{
	/*SE CREA LA INSTANCIA DEL OBJETO, SE REALIZA LA CONEXION A LA BD*/
	$db_link = new PDO($dsn, $username, $passwd);
	$accion = $_REQUEST['ACCION'];
	$msg="";
	/*REALIZAR EL PROCESO PARA GUARDAR LA INFORMACION*/
	if($accion == "guardar")
	{
		/*OBTIENE LOS DATOS A GUARDAR EN LA TABLA USUARIOS*/
		$nom_usuario 	= $_REQUEST['nom_usuario'];
		$ape_usuario	= $_REQUEST['ape1_usuario'];
		$mail			= $_REQUEST['mail'];
		$direccion		= $_REQUEST['direccion'];
		$telefono		= $_REQUEST['telefono'];
		
		/*REALIZAMOS LA ACTUALIZACION DE LOS DATOS EN LA TABLA USUARIOS*/
		$query = 	"update admin_usuarios 
					set nombres = :nom_usuario, apellidos = :ape1_usuario, 
						email=:mail, direccion=:direccion, telefono=:telefono  
					where codigo = :cod_usuario";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':cod_usuario',$usu_codigo);
		$result->bindParam(':nom_usuario',$nom_usuario);
		$result->bindParam(':ape1_usuario',$ape_usuario);
		$result->bindParam(':mail',$mail);
		$result->bindParam(':direccion',$direccion);
		$result->bindParam(':telefono',$telefono);
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
			$msg="Los cambios fueron guardados con exito!";
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar guardar la informacion $errorMessage";
		}
		$accion = "listar";		
	}
	/*REALIZAR EL PROCESO PARA CAMBIAR LA CLAVE Y GUARDAR LA INFORMACION*/
	if($accion == "cambiar_guardar")
	{
		/*OBTIENE LOS DATOS A GUARDAR EN LA TABLA USUARIOS*/
		$nom_usuario 	= $_REQUEST['nom_usuario'];
		$ape_usuario	= $_REQUEST['ape1_usuario'];
		$mail			= $_REQUEST['mail'];
		$direccion		= $_REQUEST['direccion'];
		$telefono		= $_REQUEST['telefono'];
		$clave_actual	= $_REQUEST['clave_actual'];
		$clave_nueva	= $_REQUEST['clave_nueva'];
		
		/* REALIZA LA VERIFICACION DE LA CLAVE INGRESADA */
		if($passwd==$clave_actual)
		{
		
			/*REALIZAMOS LA ACTUALIZACION DE LOS DATOS EN LA TABLA USUARIOS*/
			$query = 	"update admin_usuarios 
						set nombres = :nom_usuario, apellidos = :ape1_usuario, 
							email=:mail, direccion=:direccion, telefono=:telefono  
						where codigo = :cod_usuario";
			/*
			 * SE PREPARA EL QUERY
			 * */
			$result = $db_link->prepare($query);
			$db_link->beginTransaction();
			$result->bindParam(':cod_usuario',$usu_codigo);
			$result->bindParam(':nom_usuario',$nom_usuario);
			$result->bindParam(':ape1_usuario',$ape_usuario);
			$result->bindParam(':mail',$mail);
			$result->bindParam(':direccion',$direccion);
			$result->bindParam(':telefono',$telefono);
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
				$msg="Los cambios fueron guardados con exito!";
				/*REALIZA EL CAMBIO DE CLAVE*/
				$query = "SET PASSWORD = PASSWORD('$clave_nueva')";
				$result = $db_link->query($query);			
				$_SESSION['PASSWD']=$clave_nueva;		
			}
			else
			{
				$db_link->rollBack();
				$msg="Ha ocurrido un error al intentar guardar la informacion $errorMessage";
			}
			$accion = "salir";		
		}
		else
		{
			$msg = "La clave ingresada ingresada no coincide, el cambio no se realizo";
			$accion = "listar";
		} 
	}
	/*
	 * SE CONSTRUYE EL QUERY
	 * */
	$query = 	"select codigo, nombres, apellidos, email, direccion, telefono, username  
				from admin_usuarios 
				where codigo = :cod_usuario";
	/*
	 * SE PREPARA EL QUERY
	 * */
	$result = $db_link->prepare($query);
	$db_link->beginTransaction();
	$result->bindParam(':cod_usuario',$usu_codigo);
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
		$row = $result->fetch(PDO::FETCH_ASSOC);	
		/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
		$usu_codigo 	= $row['codigo'];
		$nom_usuario 	= $row['nombres'];
		$ape_usuario	= $row['apellidos'];
		$mail			= $row['email'];
		$direccion		= $row['direccion'];
		$telefono		= $row['telefono'];
		$usuario		= $row['username'];
	}
	else
	{
		$db_link->rollBack();
		$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
	}	
	/*ESTABLECE EL PROCESO DE ACUERDO A LA ACCION*/
	if($accion == "listar")
	{
		/*HACE EL LLAMADO AL ARCHIVO DE PRESENTACION*/
		include('../presentacion/datos_basicos.php');
	}
	if($accion == "editar")
	{
		/*HACE EL LLAMADO AL ARCHIVO DE PRESENTACION*/
		include('../presentacion/datos_basicos_e.php');
	}
	if($accion == "salir")
	{		
		?>
		<script>
			alert('La clave fue cambiada con exito, debera ingresar nuevamente a la aplicacion digitando la nueva clave');
			AjaxConsulta( '../logica/salir.php', '', 'mainContent' );
		</script>
		<?php 
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
