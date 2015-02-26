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
	if($accion == "actualizar_estado")
	{
		$est_codigo		= 	$_REQUEST['est_codigo'];
		$usu_codigo 	= 	$_REQUEST['usu_codigo'];
		$usu_username 	=	$_REQUEST['usu_username'];
		/*
		 * SE CONSTRUYE EL QUERY QUE ACTUALIZA EL ESTADO DEL USUARIO 
		 * */
		$query =	"update admin_usuarios set est_codigo = :est_codigo where codigo = :usu_codigo";
		
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':usu_codigo',$usu_codigo);
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
			/*
			 * RETIRA O PONE LOS PERMISOS AL USUARIO A NIVEL DE BD DE ACUERDO AL ESTADO 
			 * QUE HA SIDO ACTUALIZADO
			 * */	
			//USUARIO ACTIVO
			if($est_codigo == 1)
			{
				$query 	= "grant insert, select, update, delete, show view, execute on $DATABASE_NAME.* to '$usu_username'@'localhost'";
				$result = $db_link->query($query);
			}
			//USUARIO INACTIVO
			if($est_codigo == 2)
			{
				$query 	= "revoke insert, select, update, delete, show view, execute on $DATABASE_NAME.* from '$usu_username'@'localhost'";
				$result = $db_link->query($query);
			}
			?>
			<script>AjaxConsulta( '../logica/proc_estados.php', {ACCION:'listar', ESTADO:'<?php echo $est_codigo?>', USUARIO:'<?php echo $usu_codigo?>', USERNAME:'<?php echo $usu_username?>'}, 'estado_<?php echo $usu_codigo?>' );</script>
			<?php
		}
		else
		{
			$db_link->rollBack();
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";
			echo $msg;
		}		
	}
	
	if($accion == "actualizar_password")
	{
		$usu_codigo 	= 	$_REQUEST['usu_codigo'];
		$usu_username 	=	$_REQUEST['usu_username'];
		$query 	= "set password for '$usu_username'@'localhost' = password('$usu_codigo')";
		$result = $db_link->prepare($query);
		$result->execute();
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			?>
			<script> alert("la clave ha sido cambiada");</script>
			<?php 
		}
		else 
		{
			?>
			<script> alert("Error : <?php echo $errorMessage?>");</script>
			<?php
		}
	}
	

	
	if($accion == "actualizar_rol")
	{
		$rol_codigo		= 	$_REQUEST['rol_codigo'];
		$usu_codigo 	= 	$_REQUEST['usu_codigo'];
		$usu_username 	=	$_REQUEST['usu_username'];
		/*
		 * SE CONSTRUYE EL QUERY QUE ACTUALIZA EL ESTADO DEL USUARIO 
		 * */
		$query =	"update usuarios set rol_codigo = :rol_codigo where codigo = :usu_codigo";
		
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':usu_codigo',$usu_codigo);
		$result->bindParam(':rol_codigo',$rol_codigo);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		print_r("Error2: ".$error);
		if($error=="00000")
		{
			$db_link->commit();
			/*
			 * RETIRA O PONE LOS PERMISOS AL USUARIO A NIVEL DE BD DE ACUERDO AL ROL 
			 * QUE HA SIDO ACTUALIZADO
			 * */	
			//ROL 1 y 2
			if($rol_codigo > 3)
			{
				$query 	= "REVOKE ALL PRIVILEGES, GRANT OPTION FROM '$usu_username'@'localhost'; grant insert, select, update, delete, show view, execute on $DATABASE_NAME.* to '$usu_username'@'localhost';";
				$result = $db_link->prepare($query);
			}
			//ROL 3
			if($rol_codigo <= 3)
			{
				$query 	= "grant all privileges on *.* to '$usu_username'@'localhost' with grant option";
				$result = $db_link->prepare($query);
			}
			$result->execute(); //SE EJECUTA EL QUERY
			$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
			$error = $arr[0];
			$errorMessage = str_replace("'", "", $arr[2]);
			/*
			 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
			 * */
			print_r("Error: ".$error);
			if($error=="00000")
			{
				/*
				 * RETIRA O PONE LOS PERMISOS AL USUARIO A NIVEL DE BD DE ACUERDO AL ESTADO 
				 * QUE HA SIDO ACTUALIZADO
				 * */	
				?>
				<script>AjaxConsulta( '../logica/proc_roles.php', {ACCION:'listar', ROL:'<?php echo $rol_codigo?>', USUARIO:'<?php echo $usu_codigo?>', USERNAME:'<?php echo $usu_username?>'}, 'rol_<?php echo $usu_codigo?>' );</script>
				<?php
				
			}
			else
			{
				$db_link->rollBack();
				?>
				<script>
					alert("Error: <?php echo $errorMessage?>");
				</script>
				<?php 
			}			
		}
		else
		{
			$db_link->rollBack();
			?>
			<script>
					alert("Error: <?php echo $errorMessage?>");
			</script>
			<?php
		}		
	}
	
	if($accion == "crear_usuario")
	{
		$ti_codigo			= $_REQUEST['sti'];
		$identificacion		= $_REQUEST['identificacion'];
		$nombres 			= $_REQUEST['nombres'];
		$apellidos			= $_REQUEST['apellidos'];
		$fecha_nacimiento	= $_REQUEST['fecha_nacimiento'];
		$gen_codigo			= $_REQUEST['sgen'];
		$ciu_codigo			= $_REQUEST['sciu'];
		$direccion 			= $_REQUEST['direccion'];
		$telefono 			= $_REQUEST['telefono'];
		$mail 				= $_REQUEST['mail'];
		$usu_username		= $_REQUEST['username'];
		$sjva		 		= $_REQUEST['sjva'];
		$srol 				= $_REQUEST['srol'];
		$participacion		= $_REQUEST['participacion'];
		/*
		 * SE CONSTRUYE EL QUERY QUE ACTUALIZA EL ESTADO DEL USUARIO 
		 * */
		$query =	"insert into admin_usuarios (ti_codigo, nro_identificacion, nombres, apellidos, fecha_nacimiento, gen_codigo, ciu_codigo, direccion, telefono, email, username, est_codigo)
					values (:ti_codigo, :nro_identificacion, :nombres, :apellidos, :fecha_nacimiento, :gen_codigo, :ciu_codigo, :direccion, :telefono, :mail, :username, '1')";
		
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':ti_codigo',$ti_codigo);
		$result->bindParam(':nro_identificacion',$identificacion);
		$result->bindParam(':nombres',$nombres);
		$result->bindParam(':apellidos',$apellidos);
		$result->bindParam(':fecha_nacimiento',$fecha_nacimiento);
		$result->bindParam(':gen_codigo',$gen_codigo);
		$result->bindParam(':ciu_codigo',$ciu_codigo);
		$result->bindParam(':direccion',$direccion);
		$result->bindParam(':telefono',$telefono);
		$result->bindParam(':mail',$mail);
		$result->bindParam(':username',$usu_username);
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
			 * SE CONSTRUYE EL QUERY QUE ASIGNA EL USUARIO AL JVA
			 * */
			$query_jva_usuario =	"insert into admin_jva_usuarios (usu_codigo, jva_codigo, participacion, fecha_ingreso, rol_codigo, est_codigo)
									values ((select distinct codigo from admin_usuarios where nro_identificacion='$identificacion' and ti_codigo = '$ti_codigo' limit 1), :jva_codigo, :participacion, SYSDATE(), :rol_codigo, '1')";
			/*
			 * SE PREPARA EL QUERY
			 * */
			$result_jva_usuario = $db_link->prepare($query_jva_usuario);
			$db_link->beginTransaction();
			$result_jva_usuario->bindParam(':jva_codigo',$sjva);
			$result_jva_usuario->bindParam(':participacion',$participacion);
			$result_jva_usuario->bindParam(':rol_codigo',$srol);
			$result_jva_usuario->execute(); //SE EJECUTA EL QUERY
			$arr_jva_usuario 			= $result_jva_usuario->errorInfo(); // SE OBTIENE EL ERROR
			$error_jva_usuario 			= $arr_jva_usuario[0];
			$errorMessage_jva_usuario	= str_replace("'", "", $arr_jva_usuario[2]);
			/*
			 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
			 * */
			//echo $error_jva_usuario." - ".$errorMessage_jva_usuario;
			if($error_jva_usuario=="00000")
			{
				$db_link->commit();
				/*
				 * RETIRA O PONE LOS PERMISOS AL USUARIO A NIVEL DE BD DE ACUERDO AL ROL 
				 * QUE HA SIDO ACTUALIZADO
				 * */	
				//ROL 3
				if($srol <= 3)
				{
					$query2  = "grant all privileges on *.* to '$usu_username'@'localhost' identified by '$identificacion' with grant option";
				}
				else
				{
					$query2  = "grant insert, select, update, delete, show view, execute on $DATABASE_NAME.* to '$usu_username'@'localhost' identified by '$identificacion'";
				}
				$result2 = $db_link->query($query2);
				/*$result2 = $db_link->prepare($query2);
				$result2->execute();*/			 //SE EJECUTA EL QUERY
				$arr2 = $result2->errorInfo(); // SE OBTIENE EL ERROR
				$error2 = $arr2[0];
				$errorMessage2 = str_replace("'", "", $arr2[2]);
				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error2=="00000")
				{
					?>
					<script>
						alert("Usuario creado con exito!!!");
					</script>
					<?php
				}
				else
				{
					?>
					<script>
						alert("Error asignando permisos al usuario: <?php echo $errorMessage2?>");
					</script>
					<?php
				}	
			}
			else
			{
				$db_link->rollBack();
				/*
				 * SE DEBE BORRAR EL USUARIO DE LA TABLA ADMIN_USUARIOS
				 * */
				
				
				?>
				<script>
					alert("Error asignando el usuario al JVA: <?php echo $errorMessage_jva_usuario?>");
				</script>
				<?php
			}			
		}
		else
		{
			$db_link->rollBack();
			?>
			<script>
				alert("Error al crear el usuario: <?php echo $errorMessage?>");
			</script>
			<?php
		}		
		$accion = "listar";
	}
	
	if($accion == "listar")
	{
		/*
		 * SE CONSTRUYE EL QUERY
		 * */
		$query =	"SELECT au.codigo, au.nombres, au.apellidos, au.username, aju.jva_codigo, aju.rol_codigo, au.est_codigo  
					FROM admin_usuarios au, admin_jva_usuarios aju
					where au.codigo = aju.usu_codigo";
		if($rol_codigo !="1")
		{
			$query .= " and aju.jva_codigo in (select jva_codigo from admin_jva_usuarios where usu_codigo = '$usu_codigo')";
		}
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':usu_codigo',$cod_usuario);
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
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
				$usuarios['codigo'][$RowCount]		= $row['codigo'];
				$usuarios['nombres'][$RowCount]		= $row['nombres'];
				$usuarios['apellidos'][$RowCount]	= $row['apellidos'];
				$usuarios['username'][$RowCount]	= $row['username'];
				$usuarios['jva_codigo'][$RowCount]	= $row['jva_codigo'];
				$usuarios['rol_codigo'][$RowCount]	= $row['rol_codigo'];
				$usuarios['est_codigo'][$RowCount]	= $row['est_codigo'];
				$RowCount++;
			}
		}
		else
		{
			$db_link->rollBack();
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $errorMessage?>");
			</script>
			<?php
		}		
		/*HACE EL LLAMADO AL ARCHIVO DE PRESENTACION*/
		include('../presentacion/admin_usuarios.php');
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