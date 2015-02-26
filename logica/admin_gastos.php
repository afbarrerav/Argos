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
	$paso = 0;
	if ($accion=="mostrar_front")
	{
		include '../presentacion/admin_gastos.php';	
	}
	if ($accion=="crear_gasto")
	{
		$jva_codigo			= $_REQUEST['JVA_CODIGO'];
		$usu_codigo			= $_REQUEST['USU_CODIGO'];
		$fecha_gasto		= $_REQUEST['FECHA_GASTO'];
		$pgj_codigo			= $_REQUEST['PGJ_CODIGO'];
		$valor				= $_REQUEST['VALOR'];
		/*
		 * 1. CONSULTAMOS EL VALOR MAXIMO Y MINIMO DEL PARAMETRO DE GASTO DEL JVA
		 * 2. VALIDAMOS SI EL VALOR INGRESADO ES VALIDO PARA LA INSERCION
		 * 3. INSERTAMOS LOS DATOS EN TRANS_GASTOS_JVA
		 * */
		
		/*
		 * 1. CONSULTAMOS EL VALOR MAXIMO Y MINIMO DEL PARAMETRO DE GASTO DEL JVA
		 * */
		$query = "SELECT pgj.valor_min, pgj.valor_max, aju.codigo AS aju_codigo
					FROM param_gastos_jva pgj, admin_jva_usuarios aju
					WHERE pgj.codigo = '$pgj_codigo'
					AND pgj.est_codigo = 1
					AND aju.usu_codigo = '$usu_codigo'
					AND aju.jva_codigo = '$jva_codigo'
					AND pgj.jva_codigo = aju.jva_codigo";
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
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$valor_min = $row['valor_min'];
			$valor_max = $row['valor_max'];
			$aju_codigo= $row['aju_codigo'];
			/*
			 * 2. VALIDAMOS SI EL VALOR INGRESADO ES VALIDO PARA LA INSERCION
			 * */
			if ($valor>$valor_min)
			{
				if ($valor<=$valor_max)
				{
					/*
					 * 3. INSERTAMOS LOS DATOS EN TRANS_GASTOS_JVA
		 			 * */
					/*
					 * SE CONSTRUYE EL QUERY QUE CREAR EL JVA
					 * */
					$query =	"insert into trans_gastos_jva (fecha_gasto, aju_codigo, pgj_codigo, valor, fecha_trans, longitud, latitud, est_codigo)
								values (:fecha_gasto, :aju_codigo, :pgj_codigo, :valor, now(), '', '', '1')";
					/*
					 * SE PREPARA EL QUERY
					 * */
					$result = $db_link->prepare($query);
					$db_link->beginTransaction();
					$result->bindParam(':fecha_gasto',$fecha_gasto);
					$result->bindParam(':aju_codigo',$aju_codigo);
					$result->bindParam(':pgj_codigo',$pgj_codigo);
					$result->bindParam(':valor',$valor);
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
							alert("Gasto creado Satisfactoriamente!");
						</script>
						<?php
						$accion="listar";
						$paso=1;
					}
					else
					{
						$db_link->rollBack();
						?>
						<script>
							alert("Error al intentar crear el gasto: <?php echo $errorMessage?>");
						</script>
						<?php
					}
				}
				else
				{
					?>
					<script>
						alert("El valor ingresado es excede el valor minimo permitido por el JVA: <?php echo $errorMessage?>");
					</script>
					<?php
				}
			}
			else
			{
				?>
				<script>
					alert("El valor ingresado es excede el valor minimo permitido por el JVA: <?php echo $errorMessage?>");
				</script>
				<?php
			}
		}
		else
		{
			?>
			<script>
				alert("Problemas con los parametros del gasto del jva: <?php echo $errorMessage?>");
			</script>
			<?php
		}
	}
	if ($accion=="front_crear_gasto")
	{
		$jva_codigo			= $_REQUEST['JVA_CODIGO'];
		$usu_codigo			= $_REQUEST['AJU_CODIGO'];
		/*
		 * SE CONSTRUYE EL QUERY
		 * CONSULTAMOS LOS CLIENTES QUE HAN TENIDO CONTACTO CON VENDEDORES O USUARIOS DEL JVA
		 * */
		$query = "SELECT aju.rol_codigo, CONCAT(au.nombres,' ',au.apellidos) AS usu_nombre, aj.nombre as jva_nombre
					FROM admin_jva_usuarios aju, admin_usuarios au, admin_jva aj
					WHERE aju.jva_codigo = '$jva_codigo'
					AND aju.usu_codigo = '$usu_codigo'
					AND aju.est_codigo = 1
					AND au.codigo = aju.usu_codigo
					AND aj.codigo = aju.jva_codigo";
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
			$row = $result->fetch(PDO::FETCH_ASSOC);
			
			/*ASIGNA LOS RESULTADO DE LA CONSULTA A VARIABLES*/
			$rol_codigo = $row['rol_codigo'];
			$usu_nombre = $row['usu_nombre'];
			$jva_nombre = $row['jva_nombre'];
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
		include '../presentacion/admin_gastos_n.php';	
	}
	/*
	 * CREAMOS ACCION DE SELECT PARA VER LOS TIPOS DE GASTOS PARA EL USUARIO DEL JVA
	 * */
	if($accion =="consultar_tipos_gastos_jva")
	{
		$tabla_actualizar 	= $_REQUEST['TABLA_ACTUALIZAR'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO'];
		$codigo_registro	= $_REQUEST['CODIGO_REGISTRO'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];
		$jva				= $_REQUEST['JVA'];
		$rol_codigo 		= $_REQUEST['ROL_CODIGO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = "SELECT codigo, nombre 
					FROM `param_gastos_jva` 
					WHERE jva_codigo = '$jva'
					AND rol_codigo = '$rol_codigo'
					AND est_codigo = 1";
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
	if ($accion=="actualizar")
	{
		$codigo			= $_REQUEST['CODIGO'];
		$fecha_gasto	= $_REQUEST['FECHA_GASTO'];
		$aju_codigo		= $_REQUEST['AJU_CODIGO'];
		$pgj_codigo		= $_REQUEST['PGJ_CODIGO'];
		$valor			= $_REQUEST['VALOR'];
		$fecha_trans	= $_REQUEST['FECHA_TRANS'];
		//echo $fecha_gasto;
		/*
		 * SE CONSTRUYE EL QUERY
		 * CONSULTAMOS LOS CLIENTES QUE HAN TENIDO CONTACTO CON VENDEDORES O USUARIOS DEL JVA
		 * */
		$query = "UPDATE trans_gastos_jva
					set fecha_gasto='$fecha_gasto', aju_codigo=(select codigo from admin_jva_usuarios where usu_codigo = $aju_codigo), pgj_codigo='$pgj_codigo', valor='$valor', fecha_trans='$fecha_trans'  
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
				<script>
					alert("Actualizacion realizada con exito!!");
					buscar();
				</script>
			<?php
		}
		else
		{
			?>
				<script>
					alert("Ha ocurrido un error al actualizar el registro <?php echo $errorMessage?>");
				</script>
			<?php
		}			
	}
	if ($accion=="listar")
	{
		if ($paso==0) 
		{
			/*SE CREA LA VALIDACION PARA PODER LISTAR DESPUES DE INSERTAR
			*/
			$jva		= $_REQUEST['JVA_CODIGO'];
			$ven_codigo = $_REQUEST['VEN_CODIGO'];
			$inicio		= $_REQUEST['INICIO'];
			$fin		= $_REQUEST['FIN'];	
		}
		else
		{
			/*IGUALAMOS LAS VARIABLES DE INSERCION CON LAS DE CONSULTA
			*/
			$jva 		= $jva_codigo;
			$ven_codigo = $usu_codigo;
			$inicio 	= $fecha_gasto;
			$fin 		= $fecha_gasto;
		}
		
		/*
		 * SE CONSTRUYE EL QUERY
		 * CONSULTAMOS LOS CLIENTES QUE HAN TENIDO CONTACTO CON VENDEDORES O USUARIOS DEL JVA
		 * */
		$query = "SELECT tgj.codigo, tgj.fecha_gasto, CONCAT(au.nombres,' ',au.apellidos) AS ven_nombre, pgj.nombre, tgj.valor, tgj.fecha_trans
					FROM `trans_gastos_jva` tgj, admin_usuarios au, admin_jva_usuarios aju, param_gastos_jva pgj
					WHERE aju.usu_codigo = '$ven_codigo'
					AND tgj.fecha_gasto between '$inicio' and '$fin'
					AND tgj.aju_codigo = aju.codigo
					AND aju.usu_codigo = au.codigo
					AND tgj.pgj_codigo = pgj.codigo";
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
				$resultados['codigo'][$RowCount]				= $row['codigo'];
				$resultados['fecha_gasto'][$RowCount]			= $row['fecha_gasto'];
				$resultados['ven_nombre'][$RowCount]			= $row['ven_nombre'];
				$resultados['nombre'][$RowCount]				= $row['nombre'];
				$resultados['valor'][$RowCount]					= $row['valor'];
				$resultados['fecha_trans'][$RowCount]			= $row['fecha_trans'];
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
		include('../presentacion/admin_gastos_listar.php');
	}	
	if ($accion=="editar")
	{
		 $codigo 		= $_REQUEST['CODIGO'];
		 $jva		 	= $_REQUEST['JVA_CODIGO'];
		 /*
		  * CODIGO DEL VENDEDOR
		  * */
		 $usu_codigo	= $_REQUEST['USU_CODIGO'];
		 //echo $jva_codigo;
		/*
		 * SE CONSTRUYE EL QUERY
		 * CONSULTAMOS LOS CLIENTES QUE HAN TENIDO CONTACTO CON VENDEDORES O USUARIOS DEL JVA
		 * */
		$query = "SELECT *
				     FROM trans_gastos_jva
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
			$fecha_gasto		= $row['fecha_gasto'];
			$aju_codigo			= $row['aju_codigo'];
			$pgj_codigo			= $row['pgj_codigo'];
			$valor				= $row['valor'];
			$fecha_trans			= $row['fecha_trans'];
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
		include('../presentacion/admin_gastos_e.php');
	}
	if ($accion=="actualizar_estado")
	{
		$codigo			= $_REQUEST['CODIGO'];
		$est_codigo		= $_REQUEST['EST_CODIGO'];
		/*
		 * SE CONSTRUYE EL QUERY
		 * CONSULTAMOS LOS CLIENTES QUE HAN TENIDO CONTACTO CON VENDEDORES O USUARIOS DEL JVA
		 * */
		$query = "UPDATE trans_gastos_jva
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
				     FROM trans_gastos_jva
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
							onclick=".'"'."AjaxConsulta( '../logica/admin_gastos.php', {CODIGO:'$codigo', EST_CODIGO:'2', ACCION:'actualizar_estado'}, 'estado$codigo');".'"'."/>";
			}
			else
			{
				echo "<img src='imagenes/iconos/round_ok_a.png' alt='Activar' title='Activar'
							style='cursor: pointer; border: 1px solid #CCC;'
							onmouseover=".'"'."this.src = 'imagenes/iconos/round_ok.png'".'"'."
							onmouseout=".'"'."this.src = 'imagenes/iconos/round_ok_a.png'".'"'."
							width='20'
							align='absbottom'
							onclick=".'"'."AjaxConsulta( '../logica/admin_gastos.php', {CODIGO:'$codigo', EST_CODIGO:'1', ACCION:'actualizar_estado'}, 'estado$codigo');".'"'."/>";
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