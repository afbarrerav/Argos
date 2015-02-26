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
	/*
	 * ESTABLECE LA CONEXION CON LA BASE DE DATOS
	 * */
	$db_link = new PDO($dsn, $username, $passwd);
	/*REALIZA EL PROCESO PARA LA ACCION LISTAR*/
	if($accion =="actualizar_campo")
	{
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$tabla_actualizar 	= $_REQUEST['TABLA_ACTUALIZAR'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO'];
		$codigo_registro	= $_REQUEST['CODIGO_REGISTRO'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"update $tabla_actualizar set $nombre_campo = :valor_actualizar
					where codigo = :condicion_cumplir";
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':valor_actualizar',$valor_registro);
		$result->bindParam(':condicion_cumplir',$codigo_registro);
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
			?>
			<script>
				AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'<?php echo $tabla_consultar?>', TABLA_ACTUALIZAR:'<?php echo $tabla_actualizar?>', NOMBRE_CAMPO:'<?php echo $nombre_campo?>', VALOR_REGISTRO:'<?php echo $valor_registro?>', CODIGO_REGISTRO:'<?php echo $codigo_registro?>',  DIV:'<?php echo $div_cargar?>', NOMBRE_SELECT:'<?php echo $select_cargar?>', ESTADO:'', ACCION:'consultar_campo'}, '<?php echo $div_cargar?>' );
			</script>
			<?php
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
	
	if($accion =="actualizar_campo_estado")
	{
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$tabla_actualizar 	= $_REQUEST['TABLA_ACTUALIZAR'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO'];
		$codigo_registro	= $_REQUEST['CODIGO_REGISTRO'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];

		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"update $tabla_actualizar set $nombre_campo = :valor_actualizar
					where codigo = :condicion_cumplir";
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->bindParam(':valor_actualizar',$valor_registro);
		$result->bindParam(':condicion_cumplir',$codigo_registro);
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
			?>
			<script>
				AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'<?php echo $tabla_consultar?>', TABLA_ACTUALIZAR:'<?php echo $tabla_actualizar?>', NOMBRE_CAMPO:'<?php echo $nombre_campo?>', VALOR_REGISTRO:'<?php echo $valor_registro?>', CODIGO_REGISTRO:'<?php echo $codigo_registro?>',  DIV:'<?php echo $div_cargar?>', NOMBRE_SELECT:'<?php echo $select_cargar?>', ESTADO:'', ACCION:'consultar_campo_estado'}, '<?php echo $div_cargar?>' );
			</script>
			<?php
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
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$tabla_actualizar 	= $_REQUEST['TABLA_ACTUALIZAR'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO'];
		$codigo_registro	= $_REQUEST['CODIGO_REGISTRO'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		if ($tabla_consultar=="admin_usuarios")
		{
			$query = 	"select codigo, concat(nombres,' ', apellidos) nombre
						from $tabla_consultar
						where est_codigo = 1
						and rol_codigo = 1";
		}
		else 	
		{
			if ($tabla_consultar=="clientes")
			{
			$query = 	"select codigo, razon_social nombre
						from $tabla_consultar
						where est_codigo = 1";
			}
			elseif ($tabla_consultar=="admin_jva_usuarios") 
			{
				$query = "SELECT aju.codigo, CONCAT(au.nombres,' ',au.apellidos) as nombre
							FROM admin_jva_usuarios aju, admin_usuarios au
							WHERE jva_codigo in (select jva_codigo from admin_jva_usuarios where rol_codigo in (2,3) and usu_codigo =  $usu_codigo)
							AND rol_codigo = '6'
							AND aju.usu_codigo = au.codigo";
			}
			elseif ($tabla_consultar=="admin_jva_dashboard") 
			{
				$query = "select codigo, nombre
						from admin_jva
						where est_codigo = 1
						and admin_jva.codigo in (select jva_codigo from admin_jva_usuarios where rol_codigo = 2 and usu_codigo =  $usu_codigo)";
			}
			elseif ($tabla_consultar=="admin_jva_usuarios_nombres") 
			{
				$query = "SELECT aju.codigo, fun_obtener_aju_nombre(codigo) as nombre
							FROM admin_jva_usuarios aju
							WHERE aju.jva_codigo in (select jva_codigo from admin_jva_usuarios where usu_codigo=$usu_codigo)
							AND aju.usu_codigo not in (select codigo from admin_usuarios where codigo=$usu_codigo)";
			}
			elseif ($tabla_consultar=="usuarios_traslados") 
			{
				$query = "SELECT aju.codigo, fun_obtener_aju_nombre(codigo) as nombre
							FROM admin_jva_usuarios aju
							WHERE aju.rol_codigo in (select rol_codigo_desde from param_traslados_inventarios_jva where jva_codigo in (select jva_codigo from admin_jva_usuarios where usu_codigo=$usu_codigo))";
			}
			else 	
			{
				$query = 	"select codigo, $nombre_campo
							from $tabla_consultar
							where est_codigo = 1";
			}
		}
		//echo $query;
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
	
	if($accion =="consultar_campo_padre")
	{
		$tabla_consultar 	= $_REQUEST['TABLA_PADRE'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO_PADRE'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO_PADRE'];
		$div_cargar			= $_REQUEST['DIV_PADRE'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT_PADRE'];
		$tabla_consultar_h	= $_REQUEST['TABLA_HIJO'];
		$nombre_campo_h		= $_REQUEST['NOMBRE_CAMPO_HIJO'];		
		$div_cargar_h		= $_REQUEST['DIV_HIJO'];
		$select_cargar_h	= $_REQUEST['NOMBRE_SELECT_HIJO'];
		$condicion_h		= $_REQUEST['NOMBRE_CONDICION_HIJO'];
		$valor_condicion_h	= $_REQUEST['VALOR_CONDICION_HIJO'];

		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		if ($tabla_consultar=="admin_jva") 
		{
			$query = 	"select codigo, nombre
						from $tabla_consultar
						where est_codigo = 1
						and $tabla_consultar.codigo in (select jva_codigo from admin_jva_usuarios where rol_codigo in (2, 3) and usu_codigo =  $usu_codigo)";
		}
		else
		{
			$query = 	"select codigo, nombre
						from $tabla_consultar
						where est_codigo = 1";
		}
		$result = $db_link->query($query);
		$select = "<select id='$select_cargar' class='lista_desplegable' onchange=".'"'."AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'$tabla_consultar_h', CAMPO_CONDICION:'$condicion_h', VALOR_CONDICION:this.value, NOMBRE_CAMPO:'nombre', DIV:'$div_cargar_h', NOMBRE_SELECT:'$select_cargar_h', ACCION:'consultar_campo_hijo'}, '$div_cargar_h' );".'"'.">";
		$select.= "<option value = '0' selected>NO INDICADA</option>";
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$codigo = $row['codigo'];
			$nombre = htmlentities($row['nombre']);
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
	
	if($accion =="consultar_campo_hijo")
	{
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$campo_condicion 	= $_REQUEST['CAMPO_CONDICION'];
		$valor_condicion	= $_REQUEST['VALOR_CONDICION'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$valor_registro 	= "";
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		/*CREAMOS UNA CONDICION PARA PODER LLEVAR LOS TIPOS DE TRASLADOS DEL JVA
		*/
		if($tabla_consultar=="param_traslados_inventarios_jva")
		{
			$query = 	"SELECT distinctrow ptij.codigo, ptij.nombre
						FROM param_traslados_inventarios_jva ptij, tipos_traslados_inventarios tti
						WHERE ptij.jva_codigo = $valor_condicion
						AND ptij.tti_codigo = tti.codigo";
		}
		else
		{
			$query = 	"select codigo, $nombre_campo
						from $tabla_consultar
						where $campo_condicion = '$valor_condicion'
						and est_codigo = 1";
		}
		$result = $db_link->query($query);
		$select = "<select id='$select_cargar' class='lista_desplegable'>";
		$select.= "<option value = '0' selected>NO INDICADA</option>";
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$codigo = $row['codigo'];
			$nombre = htmlentities($row['nombre']);
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
	/*
	 *	ACCIONES PARA ARMAR EL SELECT DEL JVA Y LOS VENDEDORES RELACIONADOS  - ANDRES BARRERA  
	 */
	if($accion =="consultar_campo_padre_JVA")
	{
		$tabla_consultar 	= $_REQUEST['TABLA_PADRE'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO_PADRE'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO_PADRE'];
		$div_cargar			= $_REQUEST['DIV_PADRE'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT_PADRE'];
		//$nombre_campo_h		= $_REQUEST['NOMBRE_CAMPO_HIJO'];	
		$div_cargar_h		= $_REQUEST['DIV_HIJO'];
		$select_cargar_h	= $_REQUEST['NOMBRE_SELECT_HIJO'];
		//$valor_condicion_h	= $_REQUEST['VALOR_CONDICION_HIJO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select codigo, nombre
						from $tabla_consultar
						where est_codigo = 1
						and $tabla_consultar.codigo in (select jva_codigo from admin_jva_usuarios where rol_codigo in (2, 3) and usu_codigo =  $usu_codigo)";
		//echo $query;
		$result = $db_link->query($query);
		$select = "<select id='$select_cargar' class='lista_desplegable' onchange=".'"'."AjaxConsulta( '../logica/proc_select_tabla.php', {VALOR_CONDICION:this.value, NOMBRE_CAMPO:'nombre', DIV:'$div_cargar_h', NOMBRE_SELECT:'$select_cargar_h', VALOR_REGISTRO:'0', ACCION:'consultar_campo_hijo_JVA'}, '$div_cargar_h' );".'"'.">";
		$select.= "<option value = '0' selected>NO INDICADA</option>";
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$codigo = $row['codigo'];
			$nombre = htmlentities($row['nombre']);
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
	
	if($accion =="consultar_campo_hijo_JVA")
	{
		$valor_condicion	= $_REQUEST['VALOR_CONDICION'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$valor_registro 	= $_REQUEST['VALOR_REGISTRO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = "SELECT au.codigo codigo, CONCAT(au.nombres, '  ',au.apellidos) AS nombre
					FROM admin_jva_usuarios aju, admin_usuarios au
					WHERE aju.jva_codigo = $valor_condicion
					AND aju.rol_codigo = 6
					AND aju.usu_codigo = au.codigo
					AND au.est_codigo = 1";
		//echo $query;
		$result = $db_link->query($query);
		$select = "<select id='$select_cargar' class='lista_desplegable'>";
		$select.= "<option value = '0' selected>NO INDICADA</option>";
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$codigo = $row['codigo'];
			$nombre = htmlentities($row['nombre']);
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
	/*
	 * FIN ACCIONES NUEVAS - ANDRES BARRERA
	 * */
	if($accion =="consultar_campo_estado")
	{
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$tabla_actualizar 	= $_REQUEST['TABLA_ACTUALIZAR'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO'];
		$codigo_registro	= $_REQUEST['CODIGO_REGISTRO'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select codigo, nombre
					from $tabla_consultar";
		$result = $db_link->query($query);
		if ($tabla_actualizar=="0")
		{
			$select = "<select id='$select_cargar' class='lista_desplegable' $estado>";
		}
		else 
		{
			$select = "<select id='$select_cargar' class='lista_desplegable' onchange=".'"'."AjaxConsulta( '../logica/proc_select_tabla.php', {TABLA_CONSULTAR:'$tabla_consultar', TABLA_ACTUALIZAR:'$tabla_actualizar', VALOR_REGISTRO:this.value, CODIGO_REGISTRO:'$codigo_registro', NOMBRE_CAMPO:'$nombre_campo', DIV:'$div_cargar', NOMBRE_SELECT:this.id, VALOR:this.value, ACCION:'actualizar_campo_estado'}, '$div_cargar' );".'"'." $estado>";
		}
		$select.= "<option value = '0' selected>NO INDICADA</option>";
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$codigo = $row['codigo'];
			$nombre = htmlentities($row['nombre']);
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
	
	if($accion =="consultar_campo_jva")
	{
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$tabla_actualizar 	= $_REQUEST['TABLA_ACTUALIZAR'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO'];
		$codigo_registro	= $_REQUEST['CODIGO_REGISTRO'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select codigo, $nombre_campo
					from $tabla_consultar
					where codigo in (select jva_codigo from admin_jva_usuarios where usu_codigo = '$usu_codigo')
					order by $nombre_campo";
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
			$nombre = htmlentities($row['nombre']);
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
	
	if($accion =="consultar_param_jva")
	{
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$tabla_actualizar 	= $_REQUEST['TABLA_ACTUALIZAR'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO'];
		$codigo_registro	= $_REQUEST['CODIGO_REGISTRO'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select codigo, $nombre_campo
					from $tabla_consultar
					where jva_codigo in (select jva_codigo from admin_jva_usuarios where usu_codigo = '$usu_codigo')
					order by $nombre_campo";
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
			$nombre = htmlentities($row['nombre']);
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
	
	if($accion =="consultar_campo_usuarios_jva")
	{
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$tabla_actualizar 	= $_REQUEST['TABLA_ACTUALIZAR'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO'];
		$codigo_registro	= $_REQUEST['CODIGO_REGISTRO'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select aju.codigo, concat(au.nombres,' ' ,au.apellidos) nombre
					from admin_jva_usuarios aju, admin_usuarios au
					where aju.usu_codigo = au.codigo
					and aju.jva_codigo in (select jva_codigo from admin_jva_usuarios where usu_codigo = '$usu_codigo')
					and aju.rol_codigo in ('3', '6') 
					and aju.est_codigo = '1'
					and aju.codigo not in (select aju_codigo from admin_bodegas where est_codigo = 1)
					order by 2";
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
			$nombre = htmlentities($row['nombre']);
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
	
	if($accion =="consultar_campo_bodegas_jva")
	{
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$tabla_actualizar 	= $_REQUEST['TABLA_ACTUALIZAR'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO'];
		$codigo_registro	= $_REQUEST['CODIGO_REGISTRO'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select ab.codigo, ab.descripcion nombre
					from admin_bodegas ab, admin_jva_usuarios aju, admin_usuarios au
					where ab.aju_codigo = aju.codigo
					and aju.usu_codigo = au.codigo
					and aju.jva_codigo in (select jva_codigo from admin_jva_usuarios where usu_codigo = '$usu_codigo')
					and aju.rol_codigo in ('3', '6') 
					and aju.est_codigo = '1'
					order by 2";
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
			$nombre = htmlentities($row['nombre']);
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
	
	if($accion =="consultar_campo_aju")
	{
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$tabla_actualizar 	= $_REQUEST['TABLA_ACTUALIZAR'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO'];
		$codigo_registro	= $_REQUEST['CODIGO_REGISTRO'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select aju.codigo, concat(au.nombres,' ' ,au.apellidos) nombre
					from admin_jva_usuarios aju, admin_usuarios au
					where aju.usu_codigo = au.codigo
					order by 2";
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
			$nombre = htmlentities($row['nombre']);
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
	
	if($accion =="consultar_campo_bod")
	{
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$tabla_actualizar 	= $_REQUEST['TABLA_ACTUALIZAR'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO'];
		$codigo_registro	= $_REQUEST['CODIGO_REGISTRO'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select bod.codigo, concat(au.nombres,' ' ,au.apellidos) nombre
					from admin_bodegas bod, admin_jva_usuarios aju, admin_usuarios au
					where aju.usu_codigo = au.codigo
					and bod.aju_codigo = aju.codigo
					order by 2";
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
			$nombre = htmlentities($row['nombre']);
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
	if($accion =="consultar_param_jva")
	{
		$tabla_consultar 	= $_REQUEST['TABLA_CONSULTAR'];
		$tabla_actualizar 	= $_REQUEST['TABLA_ACTUALIZAR'];
		$valor_registro		= $_REQUEST['VALOR_REGISTRO'];
		$codigo_registro	= $_REQUEST['CODIGO_REGISTRO'];
		$nombre_campo		= $_REQUEST['NOMBRE_CAMPO'];
		$div_cargar			= $_REQUEST['DIV'];
		$select_cargar		= $_REQUEST['NOMBRE_SELECT'];
		$estado 			= $_REQUEST['ESTADO'];
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"select codigo, $nombre_campo
					from $tabla_consultar
					where jva_codigo in (select jva_codigo from admin_jva_usuarios where usu_codigo = '$usu_codigo')
					order by $nombre_campo";
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
			$nombre = htmlentities($row['nombre']);
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
	$db_link = null;
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