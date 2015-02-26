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
	if ($accion =="mostrar_front_mover")
	{
		$trd_codigo 		= $_REQUEST['TRD_CODIGO'];
		$tv_codigo			= $_REQUEST['TV_CODIGO'];
		$cli_codigo			= $_REQUEST['CLI_CODIGO'];
		$cliente			= $_REQUEST['CLIENTE'];
		$arg_codigo 		= $_REQUEST['ARG_CODIGO'];
		
		include '../presentacion/admin_rutas_mover.php';
	}
	if ($accion =="cambiar_ruta")
	{
		$trd_codigo 		= $_REQUEST['TRD_CODIGO'];
		$tv_codigo			= $_REQUEST['TV_CODIGO'];
		$cli_codigo			= $_REQUEST['CLI_CODIGO'];
		$cliente			= $_REQUEST['CLIENTE'];
		$arg_codigo 		= $_REQUEST['ARG_CODIGO'];
		$trj_codigo 		= $_REQUEST['TRJ_CODIGO'];
		/*
		 * EDITAMOS LA RUTA DEL JVA
		 * */
		$query = "UPDATE trans_rutas_detalles
					set trj_codigo = '$trj_codigo'
					where codigo = $trd_codigo";
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
				<script>AjaxConsulta( '../logica/admin_rutas.php', {ACCION:'listar_rutas'}, 'area_trabajo' );</script>
			<?php
		}
		else
		{
			$msg="Ha ocurrido un error al intentar obtener la informacion $errorMessage";	
		}
	}
	if($accion =="consultar_vendedor_jva")
	{
		/*
		 * ACCION SACADA DE PROC_SELECT_TABLA PARA MOSTRAR LOS VENDEDORES DE UN JVA
		 * */
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$tabla_actualizar 	= $_REQUEST['TABLA_ACTUALIZAR'];
		$condicion 			= $_REQUEST['CONDICION'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO'];
		$codigo_registro	= $_REQUEST['CODIGO_REGISTRO'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		
		$query = "SELECT aju.codigo, CONCAT(au.nombres,' ',au.apellidos) as nombre
					FROM admin_jva_usuarios aju, admin_usuarios au
					WHERE jva_codigo = '$condicion'
					AND rol_codigo = '6'
					AND aju.usu_codigo = au.codigo";
			
		$result = $db_link->query($query);
		if ($tabla_actualizar=="0")
		{
			$select = "<select id='$select_cargar' class='lista_desplegable' $estado>";
		}
		else 
		{
			$select = "<select id='$select_cargar' class='lista_desplegable' onchange=".'"'."AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'$tabla_consultar', TABLA_ACTUALIZAR:'$tabla_actualizar', VALOR_REGISTRO:this.value, CODIGO_REGISTRO:'$codigo_registro', NOMBRE_CAMPO:'$nombre_campo', DIV:'$div_cargar', NOMBRE_SELECT:this.id, VALOR:this.value, ACCION:'actualizar_campo'}, '$div_cargar' );".'"'." $estado>";
		}
		$select.= "<option value = '0' selected>NO INDICADA</option>";
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$codigo = $row['codigo'];
			$nombre = $row["$nombre_campo"];
			if ($codigo==$valor_registro)
			{
				$select.="<option value = '$codigo' selected>$nombre</option>";
			}
			else
			{
				$select.="<option value = '$codigo'>$nombre</option>";
			}
		}
		$select .= "</select>";
		echo $select;
	}
	if($accion =="consultar_rutas")
	{
		/*
		 * ACCION SACADA DE PROC_SELECT_TABLA PARA MOSTRAR LOS VENDEDORES DE UN JVA
		 * */
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$tabla_actualizar 	= $_REQUEST['TABLA_ACTUALIZAR'];
		$condicion 			= $_REQUEST['CONDICION'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO'];
		$codigo_registro	= $_REQUEST['CODIGO_REGISTRO'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		
		$query = "SELECT codigo, nombre
					FROM trans_rutas_jva 
					WHERE jva_codigo IN (select jva_codigo from admin_jva_usuarios where rol_codigo = 2 and usu_codigo = $usu_codigo)";
			
		$result = $db_link->query($query);
		if ($tabla_actualizar=="0")
		{
			$select = "<select id='$select_cargar' class='lista_desplegable' $estado>";
		}
		else 
		{
			$select = "<select id='$select_cargar' class='lista_desplegable' onchange=".'"'."AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'$tabla_consultar', TABLA_ACTUALIZAR:'$tabla_actualizar', VALOR_REGISTRO:this.value, CODIGO_REGISTRO:'$codigo_registro', NOMBRE_CAMPO:'$nombre_campo', DIV:'$div_cargar', NOMBRE_SELECT:this.id, VALOR:this.value, ACCION:'actualizar_campo'}, '$div_cargar' );".'"'." $estado>";
		}
		$select.= "<option value = '0' selected>NO INDICADA</option>";
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$codigo = $row['codigo'];
			$nombre = $row["$nombre_campo"];
			if ($codigo==$valor_registro)
			{
				$select.="<option value = '$codigo' selected>$nombre</option>";
			}
			else
			{
				$select.="<option value = '$codigo'>$nombre</option>";
			}
		}
		$select .= "</select>";
		echo $select;
	}
	
	if ($accion=="listar_rutas")
	{
		/*
		 * 1. CONSULTAMOS LAS RUTAS DEL USUARIO POR JVA, VENDEDOR 
		 * 2. CONSULTA LOS CLIENTES DE LA RUTA
		 * */
		$query = "select trj.codigo as trj_codigo, trj.nombre as ruta, aj.nombre as jva, CONCAT(au.nombres,' ',au.apellidos) as vendedor
					from trans_rutas_jva trj INNER JOIN admin_jva aj ON trj.jva_codigo = aj.codigo
					INNER JOIN admin_jva_usuarios aju ON trj.aju_codigo = aju.codigo
					INNER JOIN admin_usuarios au ON aju.usu_codigo = au.codigo
					where trj.jva_codigo IN (select jva_codigo from admin_jva_usuarios where rol_codigo = 2 and usu_codigo = $usu_codigo)";
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
				$resultados['trj_codigo'][$RowCount]	= $row['trj_codigo'];
				$resultados['ruta'][$RowCount]			= $row['ruta'];
				$resultados['jva'][$RowCount]			= $row['jva'];
				$resultados['vendedor'][$RowCount]		= $row['vendedor'];
				$trj_codigo = $resultados['trj_codigo'][$RowCount];
				/*
				 * 2. CONSULTA LOS CLIENTES DE LA RUTA
				 * */
				$query_2 = "SELECT count(trd.codigo) as cant_clientes
							FROM trans_rutas_detalles trd, trans_ventas tv, admin_clientes ac
							WHERE trd.trj_codigo = $trj_codigo
							AND trd.tv_codigo = tv.codigo
							AND tv.cli_codigo = ac.codigo
							AND trd.est_codigo = 1";
				/*
				 * SE PREPARA EL QUERY
				 * */
				$result_2 = $db_link->prepare($query_2);
				$result_2->execute(); //SE EJECUTA EL QUERY
				$arr_2 = $result_2->errorInfo(); // SE OBTIENE EL ERROR
				$error_2 = $arr_2[0];
				$errorMessage_2 = str_replace("'", "", $arr_2[2]);
				/*
				 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
				 * */
				if($error_2=="00000")
				{
					$row_2 = $result_2->fetch(PDO::FETCH_ASSOC);
					/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
					$cant_clientes	= $row_2['cant_clientes'];
				}
				else
				{
					?>
					<script>
						alert("Error al consultar los usuarios: <?php echo $errorMessage?>");
					</script>
					<?php
				}
				$resultados['cant_clientes'][$RowCount]		= $cant_clientes;
				/*
				 * 
				 * */
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
		include('../presentacion/admin_rutas.php');
	}
	if ($accion=="listar_ruta")
	{
		 $trj_codigo = $_REQUEST['TRJ_CODIGO'];
		/*
		 * CONSULTAMOS LA RUTA PARA ADMINISTRARLA
		 * */
		$query = "SELECT codigo, nombre, jva_codigo, aju_codigo, est_codigo
					FROM `trans_rutas_jva`
					where codigo = $trj_codigo";
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
				$codigo		= $row['codigo'];
				$nombre		= $row['nombre'];
				$jva_codigo	= $row['jva_codigo'];
				$aju_codigo	= $row['aju_codigo'];
				$est_codigo	= $row['est_codigo'];
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
		include('../presentacion/admin_rutas_listar.php');
	}
	if ($accion=="editar_ruta")
	{
		$codigo 		= $_REQUEST['CODIGO'];
		$nombre 		= $_REQUEST['NOMBRE'];
		$aju_codigo 	= $_REQUEST['AJU_CODIGO'];
		/*
		 * EDITAMOS LA RUTA DEL JVA
		 * */
		$query = "UPDATE trans_rutas_jva
					set nombre = '$nombre', aju_codigo = $aju_codigo
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
		//echo $error;
		if($error=="00000")
		{
			?>
				<script>AjaxConsulta( '../logica/admin_rutas.php', {ACCION:'listar_rutas'}, 'area_trabajo' );</script>
			<?php
		}
		else
		{
			?>
				<script>alert('Debe Seleccionar un Vendedor que no tenga rutas asignadas, intente nuevamente.');</script>
			<?php	
		}
	}
	if ($accion=="listar_clientes")
	{
		 $trj_codigo = $_REQUEST['TRJ_CODIGO'];
		/*
		 * CONSULTAMOS LOS CLIENTES DE LA RUTA
		 * */
		$query = "SELECT trd.codigo, trd.tv_codigo, trd.aju_codigo, trd.secuencia, tv.fecha_solicitud, tv.cli_codigo, trd.saldo, ac.referencia AS cod_argos, CONCAT( ac.nombre1_contacto,  ' ', ac.nombre2_contacto,  ' ', ac.apellido1_contacto,  ' ', ac.apellido2_contacto ) AS cliente, trd.est_codigo
					FROM trans_rutas_detalles trd, trans_ventas tv, admin_clientes ac
					WHERE trd.trj_codigo =$trj_codigo
					AND trd.tv_codigo = tv.codigo
					AND tv.cli_codigo = ac.codigo
					AND tv.codigo = trd.tv_codigo";
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
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
				$resultados['codigo'][$RowCount]			 = $row['codigo'];
				$resultados['tv_codigo'][$RowCount]		     = $row['tv_codigo'];
				$resultados['aju_codigo'][$RowCount] 		 = $row['aju_codigo'];
				$resultados['secuencia'][$RowCount]			 = $row['secuencia'];
				$resultados['fecha_solicitud'][$RowCount] 	 = $row['fecha_solicitud'];
				$resultados['cli_codigo'][$RowCount]		 = $row['cli_codigo'];
				$resultados['saldo'][$RowCount]				 = $row['saldo'];
				$resultados['cod_argos'][$RowCount]			 = $row['cod_argos'];
				$resultados['cliente'][$RowCount]			 = $row['cliente'];
				$resultados['est_codigo'][$RowCount]		 = $row['est_codigo'];
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
		include('../presentacion/admin_rutas_clientes.php');
	}

	if ($accion=="actualizar_estado")
	{
		$codigo				= $_REQUEST['CODIGO'];
		$est_codigo			= $_REQUEST['EST_CODIGO'];
		$est_codigo_v		= $_REQUEST['EST_CODIGO_V'];
		/*
		 * CONSULTAMOS LOS CLIENTES QUE HAN TENIDO CONTACTO CON VENDEDORES O USUARIOS DEL JVA
		 * */
		$query = "UPDATE trans_rutas_detalles
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
				     FROM trans_rutas_detalles
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
							onclick=".'"'."AjaxConsulta( '../logica/admin_rutas.php', {CODIGO:'$codigo', EST_CODIGO:'2', EST_CODIGO_V:'9', ACCION:'actualizar_estado'}, 'estado$codigo');".'"'."/>";
			}
			else
			{
				echo "<img src='imagenes/iconos/round_ok_a.png' alt='Activar' title='Activar'
							style='cursor: pointer; border: 1px solid #CCC;'
							onmouseover=".'"'."this.src = 'imagenes/iconos/round_ok.png'".'"'."
							onmouseout=".'"'."this.src = 'imagenes/iconos/round_ok_a.png'".'"'."
							width='20'
							align='absbottom'
							onclick=".'"'."AjaxConsulta( '../logica/admin_rutas.php', {CODIGO:'$codigo', EST_CODIGO:'1', EST_CODIGO_V:'1', ACCION:'actualizar_estado'}, 'estado$codigo');".'"'."/>";
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
	if ($accion=="crear_ruta")
	{
		$nombre 	= $_REQUEST['NOMBRE'];
		$descipcion = $_REQUEST['DESCRIPCION'];
		$jva_codigo = $_REQUEST['JVA_CODIGO'];
		$usu_codigo = $_REQUEST['USU_CODIGO'];
		/*
		 * SE CONSTRUYE EL QUERY QUE CREAR EL LA RUTA
		 * */
		$query =	"insert into trans_rutas_jva (nombre, descripcion, jva_codigo, aju_codigo, pbj_codigo, saldo, est_codigo)
					values (:nombre, :descripcion, :jva_codigo, (select codigo from admin_jva_usuarios where usu_codigo = $usu_codigo), '1', '0', '1')";
		/*
		 * SE PREPARA EL QUERY
		 * */
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':nombre',$nombre);
		$result->bindParam(':descripcion',$descipcion);
		$result->bindParam(':jva_codigo',$jva_codigo);
		$result->execute(); //SE EJECUTA EL QUERY
		$arr 			= $result->errorInfo(); // SE OBTIENE EL ERROR
		$error			= $arr[0];
		$errorMessage	= str_replace("'", "", $arr[2]);
		/*
		 * SI EL CODIGO DEL ERROR ES 00000 TODO ESTA BIEN CONSULTA CORRECTA
		 * */
		if($error=="00000")
		{
			$db_link->commit();
			?>
			<script>
				alert("Ruta creada Satisfactoriamente!");
			</script>
			<?php
		}
		else
		{
			$db_link->rollBack();
			?>
			<script>
				alert("Error al intentar crear la ruta: <?php echo $errorMessage?>");
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