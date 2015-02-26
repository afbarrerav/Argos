<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	octubre de 2012
 * 
 * */

/*
 * PROCESO DE AUTENTICACION -VALIDACIONES A REALIZAR-
 * 1. VALIDAR SI EL USUARIO TIENE PERMISOS DE LOGUEO SOBRE LA BASE DE DATOS
 * 2. VALIDAR SI EL USUARIO EXISTE Y/O ESTA ACTIVO EN LA APLICACION
 * 3. VALIDAR SI EL USUARIO EXISTE Y/O ESTA ASIGNADO A UN JVA
 * 4. OBTENER EL ROL ASIGNADO AL USUARIO
 * 5. VALIDAR SI EL DISPOSITIVO UTILIZADO PARA LA CONEXION ESTA AUTORIZADO
 * 6. VALIDAR EL TIPO DE AUTENTICACION ADICIONAL QUE DEBE UTILIZARSE
 * */
session_start();
$usu = $_REQUEST['us'];
$cla = $_REQUEST['pa'];
include_once ("../logica/conexion_bd.php");
/*
 * REALIZA LA VALIDACION No. 1
 * */
try
{
	/*SE CREA LA INSTANCIA DEL OBJETO, SE REALIZA LA CONEXION A LA BD*/
	$db_link = new PDO($dsn, $username, $passwd);
	/*
	 * SE CONSTRUYE EL QUERY PARA REALIZAR LA VALIDACION No. 2
	 * */
	$query2 = 	"select codigo, ti_codigo, nro_identificacion, nombres, apellidos, fecha_nacimiento, gen_codigo,
						direccion, telefono, email, username
				from admin_usuarios u
				where u.username = '$usu' 
				and u.est_codigo = '1'";
	/*
	 * SE PREPARA EL QUERY
	 * */
	$result2 = $db_link->prepare($query2);
	//$result2->bindParam(':usu',$usu);
	$result2->execute(); //SE EJECUTA EL QUERY
	$arr2 = $result2->errorInfo(); // SE OBTIENE EL ERROR
	$error2 = $arr2[0];
	$errorMessage2 = str_replace("'", "", $arr2[2]);
	/*
	 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
	 * */
	if($error2=="00000")
	{
		/*
		 * OBTENER LA INFORMACION DEL USUARIO PARA PROCEDER CON LA VALIDACION No. 3
		 * */
		$RowCount2 = 0;
		while($row2 = $result2->fetch(PDO::FETCH_ASSOC))
		{
			$usu_codigo			= $row2['codigo'];
			$ti_codigo			= $row2['ti_codigo'];
			$ide_usuario		= $row2['nro_identificacion'];
			$nom_usuario		= $row2['nombres'] ." ".$row2['apellidos'];
			$fecha_nacimiento	= $row2['fecha_nacimiento'];
			$gen_codigo			= $row2['gen_codigo'];
			$direccion			= $row2['direccion'];
			$telefono			= $row2['telefono'];
			$email				= $row2['email'];
			$usuario 			= $row2['username'];
			$RowCount2++;
		}
		if ($RowCount2>0)
		{
			
			/*
			 * SE CONSTRUYE EL QUERY PARA REALIZAR LA VALIDACION No. 3
			 * */
			$query3 =	"select aju.codigo, aju.jva_codigo, aju.participacion, aju.fecha_ingreso, aju.rol_codigo, 
								ar.nombre rol_nombre
						from admin_jva_usuarios aju, admin_roles ar
						where aju.rol_codigo = ar.codigo 
						and aju.usu_codigo = $usu_codigo
						and aju.est_codigo = '1'";
			/*
			 * SE PREPARA EL QUERY
			 * */
			$result3 = $db_link->prepare($query3);
			//$result3->bindParam(':usu_codigo',$usu_codigo);
			$result3->execute(); //SE EJECUTA EL QUERY
			$arr3 = $result3->errorInfo(); // SE OBTIENE EL ERROR
			$error3 = $arr3[0];
			$errorMessage3 = str_replace("'", "", $arr3[2]);
			/*
			 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
			 * */
			if($error3=="00000")
			{
				/*
				 * OBTENER LA INFORMACION DEL ADMIN_JVA_USUARIOS PARA PROCEDER CON LA VALIDACION No. 4
				 * */
				$RowCount3 = 0;
				while($row3 = $result3->fetch(PDO::FETCH_ASSOC))
				{
					$aju_codigo			= $row3['codigo'];
					$jva_codigo			= $row3['jva_codigo'];
					$participacion		= $row3['participacion'];
					$fecha_ingreso		= $row3['fecha_ingreso'];
					$rol_codigo			= $row3['rol_codigo'];
					$rol_nombre			= $row3['rol_nombre'];
					$RowCount3++;
				}
				echo $query3;
				if ($RowCount3>0)
				{
					/*
					 * QUERY PARA OBTENER LA FECHA DEL ULTIMO INGRESO A LA APLICACIÓN
					 * */
					$query = 	"SELECT fecha_trans FROM logs_accesos_usuarios WHERE aju_codigo = $aju_codigo ORDER BY fecha_trans desc";
					$result = $db_link->query($query);
					$row = $result->fetch(PDO::FETCH_ASSOC);
					$ultimo_acceso = $row['fecha_trans'];
					/*
					 * DEFINE LAS VARIABLES DE SESION PARA EL USUARIO
					 * */
					$_SESSION['AJU_CODIGO']		= $aju_codigo;
					$_SESSION['JVA_CODIGO']		= $jva_codigo;
					$_SESSION['USU_CODIGO']		= $usu_codigo;
					$_SESSION['IDE_USUARIO']	= $ide_usuario;
					$_SESSION['NOM_USUARIO']	= $nom_usuario;
					$_SESSION['USERNAME']		= $usuario;
					$_SESSION['ROL_USUARIO']	= $rol_nombre;
					$_SESSION['ROL_CODIGO']		= $rol_codigo;
					$_SESSION['ULTIMO_ACCESO']	= $ultimo_acceso;
					$_SESSION['DSN']			= $dsn;
					$_SESSION['PASSWD']			= $passwd;
					/*
					 * INVOCA AL ARCHIVO QUE REALIZA LA INSERCCION EN LA TABLA REGSITRO_ACCESO
					 * */
					include('../logica/logs_accesos.php');
					?>
					<script>
						AjaxConsulta( '../logica/main.php', '', 'mainContent' );
					</script>
					<?php
				}
				else 
				{
					/*
					 * LOGIN INCORRECTO
					 * EL USUARIO NO ESTA ASOCIADO A UN JVA O ESTA DESACTIVADO, VUELVE AL LOGIN
					 * */
					?>
					<script>
						alert("Error de autenticacion: USUARIO INACTIVO O SIN JVA");
						AjaxConsulta('../presentacion/frm_login.php', '', 'mainContent' );
					</script>
					<?php
				}
			}
			else
			{
				?>
				<script>
					alert("Error <?php echo $error3.": ".$errorMessage3?>");
					location.reload(true);
				</script>
				<?php
				session_unset();
				session_destroy();  
			}
		}
		else
		{
			/*
			 * LOGIN INCORRECTO
			 * EL USUARIO NO EXISTE, VUELVE AL LOGIN
			 * */
			?>
			<script>
				alert("Error de autenticacion: USUARIO INACTIVO O SIN PRIVILEGIOS DE ACCESO");
				AjaxConsulta('../presentacion/frm_login.php', '', 'mainContent' );
			</script>
			<?php
		}
	}
	else
	{
		?>
		<script>
		alert("Error <?php echo $error2.": ".$errorMessage2?>");
			location.reload(true);
		</script>
		<?php 
		session_unset();
		session_destroy();  
	}
}
catch (PDOException $e)
{
	$msg = $e->getMessage();
	?>
	<script>
		alert("USUARIOS Y/O CLAVE INCORRECTOS.");
		location.reload(true);
	</script>
	<?php
	session_unset();
	session_destroy();
}
?>


