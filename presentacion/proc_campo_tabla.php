<?php
/*
 * @author:	FABIAN ANDRES MOJICA ALFONSO
 * 			ingmojica@hotmail.com	
 * @version:1.0.0
 * @fecha:	Junio de 2011
 * 
 * */
include_once("variables_session.php");
try
{
	//CAPTURA LA ACCION A REALIZAR
	$accion 	= $_REQUEST['ACCION'];
	/*REALIZA EL PROCESO PARA LA ACCION LISTAR*/
	if($accion =="actualizar_campo")
	{
		$tabla_actualizar 	= $_REQUEST['TABLA'];
		$campo_actualizar	= $_REQUEST['CAMPO'];
		$valor_actualizar	= $_REQUEST['VALOR'];
		$condicion_cumplir 	= $_REQUEST['CONDICION'];
		$div_cargar			= $_REQUEST['DIV'];
		$input_cargar		= $_REQUEST['NOMBRE'];
		$size				= $_REQUEST['SIZE'];
		$class				= $_REQUEST['CLASS'];
		/*
		 * ESTABLECE LA CONEXION CON LA BASE DE DATOS
		 * */
		$db_link = new PDO($dsn, $username, $passwd);
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"update $tabla_actualizar set $campo_actualizar = :valor_actualizar
					where codigo = :condicion_cumplir";
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':valor_actualizar',$valor_actualizar);
		$result->bindParam(':condicion_cumplir',$condicion_cumplir);
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
			if($class=="bloqueado")
			{
				?>
				<script>
					AjaxConsulta( '../logica/proc_campo_tabla.php', {TABLA:'<?php echo $tabla_actualizar?>', CAMPO:'<?php echo $campo_actualizar?>', VALOR:'<?php echo $valor_actualizar?>', NOMBRE:'<?php echo $input_cargar?>', SIZE:'<?php echo $size?>', CONDICION:'<?php echo $condicion_cumplir?>', DIV:'<?php echo $div_cargar?>', ACCION:'consultar_campo_bloqueado'}, '<?php echo $div_cargar?>' );
				</script>
				<?php
			}
			else 
			{
				?>
				<script>
					AjaxConsulta( '../logica/proc_campo_tabla.php', {TABLA:'<?php echo $tabla_actualizar?>', CAMPO:'<?php echo $campo_actualizar?>', VALOR:'<?php echo $valor_actualizar?>', NOMBRE:'<?php echo $input_cargar?>', SIZE:'<?php echo $size?>', CONDICION:'<?php echo $condicion_cumplir?>', DIV:'<?php echo $div_cargar?>', cantidad:'no', valor_unitario:'0', ACCION:'consultar_campo'}, '<?php echo $div_cargar?>' );
				</script>
				<?php
			}
		}
		else
		{
			$db_link->rollBack();
			?>
			<script>
				alert("Error al intentar actualizar el campo: <?php echo $errorMessage?>");
			</script>
			<?php
		}
	}
	
	if($accion =="actualizar_campo_cantidad")
	{
		$tabla_actualizar 	= "pedidos_detalles";
		$campo_actualizar	= $_REQUEST['CAMPO'];
		$valor_actualizar	= $_REQUEST['VALOR'];
		$condicion_cumplir 	= $_REQUEST['CONDICION'];
		$div_cargar			= $_REQUEST['DIV'];
		$div_cargar2		= $_REQUEST['DIV2'];
		$input_cargar		= $_REQUEST['NOMBRE'];
		$input_cargar2		= $_REQUEST['NOMBRE2'];
		$size				= $_REQUEST['SIZE'];
		$class				= $_REQUEST['CLASS'];
		$valor				= $_REQUEST['valor_total'];
		/*
		 * ESTABLECE LA CONEXION CON LA BASE DE DATOS
		 * */
		$db_link = new PDO($dsn, $username, $passwd);
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"update $tabla_actualizar set $campo_actualizar = :valor_actualizar
					where codigo = :condicion_cumplir";
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':valor_actualizar',$valor_actualizar);
		$result->bindParam(':condicion_cumplir',$condicion_cumplir);
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
			 * MULTIPLICA LA CANTIDAD POR EL VALOR TOTAL Y ACTUALIZA
			 * */
			$valor_total = $valor_actualizar * $valor;
			echo "Valor= ".$valor;
			$query_valor_total = "update $tabla_actualizar set valor_total = :valor_total
								  where codigo = :condicion_cumplir";
			
			$result2 = $db_link->prepare($query_valor_total);
			$db_link->beginTransaction();
			$result2->bindParam(':valor_total',$valor_total);
			$result2->bindParam(':condicion_cumplir',$condicion_cumplir);
			$result2->execute(); //SE EJECUTA EL QUERY
			$arr2 = $result2->errorInfo(); // SE OBTIENE EL ERROR
			$error2 = $arr2[0];
			$errorMessage2 = str_replace("'", "", $arr2[2]);
			
			if($error2 == "00000")
			{
				$db_link->commit();
				if($class=="bloqueado")
				{
					?>
					<script>
						AjaxConsulta( '../logica/proc_campo_tabla.php', {TABLA:'<?php echo $tabla_actualizar?>', CAMPO:'<?php echo $campo_actualizar?>', VALOR:'<?php echo $valor_actualizar?>', NOMBRE:'<?php echo $input_cargar?>', SIZE:'<?php echo $size?>', CONDICION:'<?php echo $condicion_cumplir?>', DIV:'<?php echo $div_cargar?>', ACCION:'consultar_campo_bloqueado'}, '<?php echo $div_cargar?>' );
					</script>
					<?php
				}
				else 
				{
					?>
					<script>
						AjaxConsulta( '../logica/proc_campo_tabla.php', {TABLA:'<?php echo $tabla_actualizar?>', CAMPO:'<?php echo $campo_actualizar?>', VALOR:'<?php echo $valor_actualizar?>', NOMBRE:'<?php echo $input_cargar?>', SIZE:'<?php echo $size?>', CONDICION:'<?php echo $condicion_cumplir?>', DIV:'<?php echo $div_cargar?>', cantidad:'si', valor_unitario:'<?php echo $valor?>', ACCION:'consultar_campo'}, '<?php echo $div_cargar?>' );
						AjaxConsulta( '../logica/proc_campo_tabla.php', {TABLA:'<?php echo $tabla_actualizar?>', CAMPO:'valor_total', VALOR:'<?php echo $valor_total?>', NOMBRE:'<?php echo $input_cargar2?>', SIZE:'<?php echo $size?>', CONDICION:'<?php echo $condicion_cumplir?>', DIV:'<?php echo $div_cargar2?>', ACCION:'consultar_campo_bloqueado'}, '<?php echo $div_cargar2?>' );
					</script>
					<?php
				}
			}
			else
			{
				$db_link->rollBack();
			}
		}
		else
		{
			$db_link->rollBack();
			?>
			<script>
				alert("Error al intentar actualizar el campo: <?php echo $errorMessage?>");
			</script>
			<?php
		}
	}
	
	if($accion =="consultar_campo")
	{
		$tabla_consultar 	= $_REQUEST['TABLA'];
		$campo_consultar	= $_REQUEST['CAMPO'];
		$condicion_cumplir 	= $_REQUEST['CONDICION'];
		$div_cargar			= $_REQUEST['DIV'];
		$input_cargar		= $_REQUEST['NOMBRE'];
		$size				= $_REQUEST['SIZE'];
		$cantidad			= $_REQUEST['cantidad'];
		$valor_unitario		= $_REQUEST['valor_unitario'];
		/*
		 * ESTABLECE LA CONEXION CON LA BASE DE DATOS
		 * */
		$db_link = new PDO($dsn, $username, $passwd);
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select $campo_consultar
					from $tabla_consultar
					where codigo = '$condicion_cumplir'";
		$result = $db_link->query($query);
		$row = $result->fetch(PDO::FETCH_NUM);
		$valor_campo = $row[0];
		
		if($cantidad == "si")
		{
			$input = "<input type='text' id='$input_cargar' class='campo' value='$valor_campo' size='$size' onchange = ".'"'."AjaxConsulta( '../logica/proc_campo_tabla.php', {TABLA:'$tabla_consultar', CAMPO:'$campo_consultar', VALOR:this.value, NOMBRE:this.id, SIZE:'$size', CONDICION:'$condicion_cumplir', DIV:'$div_cargar', valor_total:'$valor_unitario', DIV2:'valor_totalDiv$condicion_cumplir', NOMBRE2:'valor_total$condicion_cumplir', ACCION:'actualizar_campo_cantidad'}, '$div_cargar' );".'"'."/>";
		}
		else
		{
			$input = "<input type='text' id='$input_cargar' class='campo' value='$valor_campo' size='$size' onchange = ".'"'."AjaxConsulta( '../logica/proc_campo_tabla.php', {TABLA:'$tabla_consultar', CAMPO:'$campo_consultar', VALOR:this.value, NOMBRE:this.id, SIZE:'$size', CONDICION:'$condicion_cumplir', DIV:'$div_cargar', ACCION:'actualizar_campo'}, '$div_cargar' );".'"'."/>";
		}
		echo $input;
	}
	
	if($accion =="consultar_campo_bloqueado")
	{
		$tabla_consultar 	= $_REQUEST['TABLA'];
		$campo_consultar	= $_REQUEST['CAMPO'];
		$condicion_cumplir 	= $_REQUEST['CONDICION'];
		$div_cargar			= $_REQUEST['DIV'];
		$input_cargar		= $_REQUEST['NOMBRE'];
		$size				= $_REQUEST['SIZE'];
		/*
		 * ESTABLECE LA CONEXION CON LA BASE DE DATOS
		 * */
		$db_link = new PDO($dsn, $username, $passwd);
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select $campo_consultar
					from $tabla_consultar
					where codigo = '$condicion_cumplir'";
		//echo $query;
		$result = $db_link->query($query);
		$row = $result->fetch(PDO::FETCH_NUM);
		$valor_campo = $row[0];
		$input = "<input type='text' id='$input_cargar' class='campo_bloqueado' value='$valor_campo' size='$size' readonly='readonly'>";
		echo $input;
	}
	
	if($accion =="consultar_valor_campo")
	{
		$tabla_consultar 	= $_REQUEST['TABLA'];
		$campo_consultar	= $_REQUEST['CAMPO'];
		$condicion_cumplir 	= $_REQUEST['CONDICION'];
		/*
		 * ESTABLECE LA CONEXION CON LA BASE DE DATOS
		 * */
		$db_link = new PDO($dsn, $username, $passwd);
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select $campo_consultar
					from $tabla_consultar
					where codigo = '$condicion_cumplir'";
		$result = $db_link->query($query);
		$row = $result->fetch(PDO::FETCH_NUM);
		$valor_campo = $row[0];
		echo $valor_campo;
	}
	
	if($accion == "consultar_valor_campo2")
	{
		$tabla_consultar 	= $_REQUEST['TABLA'];
		$campo_consultar	= $_REQUEST['CAMPO'];
		$condicion_cumplir 	= $_REQUEST['CONDICION'];
		$campo_codicion		= $_REQUEST['CAMPO_CONDICION'];
		/*
		 * ESTABLECE LA CONEXION CON LA BASE DE DATOS
		 * */
		$db_link = new PDO($dsn, $username, $passwd);
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select $campo_consultar
					from $tabla_consultar
					where $campo_codicion = '$condicion_cumplir'";
		$result = $db_link->query($query);
		$row = $result->fetch(PDO::FETCH_NUM);
		$valor_campo = $row[0];
		//echo $query;
		echo $valor_campo;
	}
	
	if($accion =="consultar_valor_campo_aju")
	{
		$tabla_consultar 	= $_REQUEST['TABLA'];
		$campo_consultar	= $_REQUEST['CAMPO'];
		$condicion_cumplir 	= $_REQUEST['CONDICION'];
		/*
		 * ESTABLECE LA CONEXION CON LA BASE DE DATOS
		 * */
		$db_link = new PDO($dsn, $username, $passwd);
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"SELECT CONCAT (nombres, ' ', apellidos) 
					FROM admin_usuarios 
					WHERE codigo = (select $campo_consultar
									from $tabla_consultar
									where codigo = '$condicion_cumplir')";
		$result = $db_link->query($query);
		$row = $result->fetch(PDO::FETCH_NUM);
		$valor_campo = $row[0];
		echo $valor_campo;
	}
	if($accion =="consultar_valor_funcion")
	{
		$funcion_consultar 	= $_REQUEST['FUNCION'];
		$funcion_parametros	= $_REQUEST['PARAMETROS'];
		/*
		 * ESTABLECE LA CONEXION CON LA BASE DE DATOS
		 * */
		$db_link = new PDO($dsn, $username, $passwd);
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select $funcion_consultar($funcion_parametros)";
		$result = $db_link->query($query);
		$row = $result->fetch(PDO::FETCH_NUM);
		$valor_campo = $row[0];
		if ($valor_campo=="")
		{
			$valor_campo=0;
		}
		echo $valor_campo;		
	}
	$db_link = null;
}
catch (PDOException $e)
{
	$msg = "Error!: " . $e->getMessage();
	die();
}
?>