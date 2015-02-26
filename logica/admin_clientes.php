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
	if ($accion=="mostrar_front")
	{
		include '../presentacion/admin_clientes.php';	
	}
	if ($accion=="actualizar")
	{
		$codigo				= $_REQUEST['codigo'];
		$ti_codigo			= $_REQUEST['ti_codigo'];
		$nroidentificacion	= $_REQUEST['nroidentificacion'];
		$referencia			= $_REQUEST['referencia'];
		$razon_social		= $_REQUEST['razon_social'];
		$tn_codigo			= $_REQUEST['tn_codigo'];
		$primer_nombre		= $_REQUEST['primer_nombre'];
		$segundo_nombre		= $_REQUEST['segundo_nombre'];
		$primer_apellido	= $_REQUEST['primer_apellido'];
		$segundo_apellido	= $_REQUEST['segundo_apellido'];
		$telefono1			= $_REQUEST['telefono1'];
		$ext1				= $_REQUEST['ext1'];
		$telefono2			= $_REQUEST['telefono2'];
		$ext2				= $_REQUEST['ext2'];
		$celular1			= $_REQUEST['celular1'];
		$celular2			= $_REQUEST['celular2'];
		$email				= $_REQUEST['email'];
		$barrio				= $_REQUEST['barrio'];
		$direccion			= $_REQUEST['direccion'];
		$ciu_codigo			= $_REQUEST['ciu_codigo'];
		$calificacion		= $_REQUEST['calificacion'];
		$comentario			= $_REQUEST['comentario'];
		/*
		 * SE CONSTRUYE EL QUERY
		 * CONSULTAMOS LOS CLIENTES QUE HAN TENIDO CONTACTO CON VENDEDORES O USUARIOS DEL JVA
		 * */
		$query = "UPDATE admin_clientes
					set codigo=$codigo, ti_codigo=$ti_codigo, nroidentificacion=$nroidentificacion, referencia='$referencia', 
						razon_social='$razon_social', tn_codigo='$tn_codigo', nombre1_contacto='$primer_nombre', nombre2_contacto='$segundo_nombre', 
						apellido1_contacto='$primer_apellido', apellido2_contacto='$segundo_apellido', telefono1='$telefono1', ext1='$ext1', 
						telefono2='$telefono2', ext2='$ext2', celular1='$celular1', celular2='$celular2', email='$email', barrio='$barrio', 
						direccion='$direccion', ciu_codigo='$ciu_codigo', calificacion='$calificacion', comentario='$comentario'  
					where codigo = $codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		//echo $query;
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
			?>
				<script>alert("Actualizacion realizada con exito!!");</script>
			<?php
			$accion = "listar";
		}
		else
		{
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";	
		}			
	}
	if ($accion=="listar")
	{
		/*
		 * SE CONSTRUYE EL QUERY
		 * CONSULTAMOS LOS CLIENTES QUE HAN TENIDO CONTACTO CON VENDEDORES O USUARIOS DEL JVA
		 * */
		$query = "SELECT DISTINCT ac.codigo, ti.nombre tipo_identificacion, ac.nroidentificacion, CONCAT( ac.nombre1_contacto,  ' ', ac.nombre2_contacto,  ' ', ac.apellido1_contacto,  ' ', ac.apellido2_contacto ) AS nombre, ac.direccion, ac.est_codigo
				     FROM admin_clientes ac, trans_ventas tv, tipos_identificaciones ti
				     WHERE tv.cli_codigo = ac.codigo
				     AND ac.ti_codigo = ti.codigo
				     AND tv.aju_codigo
				     IN (SELECT codigo
				      	FROM admin_jva_usuarios
				      	WHERE jva_codigo =  '$jva_codigo')";
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
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
				$resultados['codigo'][$RowCount]				= $row['codigo'];
				$resultados['tipo_identificacion'][$RowCount]	= $row['tipo_identificacion'];
				$resultados['nroidentificacion'][$RowCount]		= $row['nroidentificacion'];
				$resultados['nombre'][$RowCount]				= $row['nombre'];
				$resultados['direccion'][$RowCount]				= $row['direccion'];
				$resultados['est_codigo'][$RowCount]			= $row['est_codigo'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $errorMessage?>");
			</script>
			<?php
		}		
		/*HACE EL LLAMADO AL ARCHIVO DE PRESENTACION*/
		include('../presentacion/admin_clientes_listar.php');
	}
	
	if ($accion=="consulta_like")
	{
		 $campo = $_REQUEST['CAMPO'];
		 $valor = $_REQUEST['VALOR'];
		/*
		 * SE CONSTRUYE EL QUERY
		 * CONSULTAMOS LOS CLIENTES QUE HAN TENIDO CONTACTO CON VENDEDORES O USUARIOS DEL JVA
		 * */
		$query = "SELECT DISTINCT ac.codigo, ti.nombre tipo_identificacion, ac.nroidentificacion, CONCAT( ac.nombre1_contacto,  ' ', ac.nombre2_contacto,  ' ', ac.apellido1_contacto,  ' ', ac.apellido2_contacto ) AS nombre, ac.direccion, ac.est_codigo
				     FROM admin_clientes ac, trans_ventas tv, tipos_identificaciones ti
				     WHERE ac.$campo like '%$valor%' 
				     AND tv.cli_codigo = ac.codigo
				     AND ac.ti_codigo = ti.codigo
				     AND tv.aju_codigo
				     IN (SELECT codigo
				      	FROM admin_jva_usuarios
				      	WHERE jva_codigo =  '$jva_codigo')";
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
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
				$resultados['codigo'][$RowCount]				= $row['codigo'];
				$resultados['tipo_identificacion'][$RowCount]	= $row['tipo_identificacion'];
				$resultados['nroidentificacion'][$RowCount]		= $row['nroidentificacion'];
				$resultados['nombre'][$RowCount]				= $row['nombre'];
				$resultados['direccion'][$RowCount]				= $row['direccion'];
				$resultados['est_codigo'][$RowCount]			= $row['est_codigo'];
				$RowCount++;
			}
		}
		else
		{
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $errorMessage?>");
			</script>
			<?php
		}		
		/*HACE EL LLAMADO AL ARCHIVO DE PRESENTACION*/
		include('../presentacion/admin_clientes_listar.php');
	}
	if ($accion=="editar")
	{
		 $codigo = $_REQUEST['CODIGO'];
		/*
		 * SE CONSTRUYE EL QUERY
		 * CONSULTAMOS LOS CLIENTES QUE HAN TENIDO CONTACTO CON VENDEDORES O USUARIOS DEL JVA
		 * */
		$query = "SELECT *
				     FROM admin_clientes ac
				     WHERE codigo = '$codigo'";
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
			$codigo				= $row['codigo'];
			$ti_codigo			= $row['ti_codigo'];
			$nroidentificacion	= $row['nroidentificacion'];
			$referencia			= $row['referencia'];
			$razon_social		= $row['razon_social'];
			$tn_codigo			= $row['tn_codigo'];
			$nombre1_contacto	= $row['nombre1_contacto'];
			$nombre2_contacto	= $row['nombre2_contacto'];
			$apellido1_contacto	= $row['apellido1_contacto'];
			$apellido2_contacto	= $row['apellido2_contacto'];
			$telefono1			= $row['telefono1'];
			$ext1				= $row['ext1'];
			$telefono2			= $row['telefono2'];
			$ext2				= $row['ext2'];
			$celular1			= $row['celular1'];
			$celular2			= $row['celular2'];
			$email				= $row['email'];
			$barrio				= $row['barrio'];
			$direccion			= $row['direccion'];
			$comentario			= $row['comentario'];
			$calificacion		= $row['calificacion'];
			$ciu_codigo			= $row['ciu_codigo'];
			$est_codigo			= $row['est_codigo'];
		}
		else
		{
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $errorMessage?>");
			</script>
			<?php
		}		
		/*HACE EL LLAMADO AL ARCHIVO DE PRESENTACION*/
		include('../presentacion/admin_clientes_e.php');
	}
	if ($accion=="actualizar_estado")
	{
		$codigo				= $_REQUEST['CODIGO'];
		$est_codigo				= $_REQUEST['EST_CODIGO'];
		/*
		 * SE CONSTRUYE EL QUERY
		 * CONSULTAMOS LOS CLIENTES QUE HAN TENIDO CONTACTO CON VENDEDORES O USUARIOS DEL JVA
		 * */
		$query = "UPDATE admin_clientes
					set est_codigo=$est_codigo  
					where codigo = $codigo";
		/*
		 * SE PREPARA EL QUERY
		 * */
		//echo $query;
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
			$accion = "estado";
		}
		else
		{
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";	
		}			
	}
	if ($accion=="estado")
	{
		 $codigo = $_REQUEST['CODIGO'];
		/*
		 * SE CONSTRUYE EL QUERY
		 * CONSULTAMOS LOS CLIENTES QUE HAN TENIDO CONTACTO CON VENDEDORES O USUARIOS DEL JVA
		 * */
		$query = "SELECT est_codigo
				     FROM admin_clientes
				     WHERE codigo = '$codigo'";
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
			$est_codigo			= $row['est_codigo'];
			if ($est_codigo=="1")
			{
				echo "<img src='imagenes/iconos/round_remove_a.png' alt='Desactivar' title='Desactivar'
							style='cursor: pointer; border: 1px solid #CCC;'
							onmouseover=".'"'."this.src = 'imagenes/iconos/round_remove.png'".'"'."
							onmouseout=".'"'."this.src = 'imagenes/iconos/round_remove_a.png'".'"'."
							width='20'
							align='absbottom'
							onclick=".'"'."AjaxConsulta( '../logica/admin_clientes.php', {CODIGO:'$codigo', EST_CODIGO:'2', ACCION:'actualizar_estado'}, 'estado$codigo');".'"'."/>";
			}
			else
			{
				echo "<img src='imagenes/iconos/round_ok_a.png' alt='Activar' title='Activar'
							style='cursor: pointer; border: 1px solid #CCC;'
							onmouseover=".'"'."this.src = 'imagenes/iconos/round_ok.png'".'"'."
							onmouseout=".'"'."this.src = 'imagenes/iconos/round_ok_a.png'".'"'."
							width='20'
							align='absbottom'
							onclick=".'"'."AjaxConsulta( '../logica/admin_clientes.php', {CODIGO:'$codigo', EST_CODIGO:'1', ACCION:'actualizar_estado'}, 'estado$codigo');".'"'."/>";
			}
		}
		else
		{
			?>
			<script>
				alert("Error al consultar los usuarios: <?php echo $errorMessage?>");
			</script>
			<?php
		}
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