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
	//echo $accion;
	if($accion == "construir_auditoria")
	{
		/*OBTIENE LA BASE DE DATOS*/
		$base_de_datos 		= $_REQUEST['BASE_DE_DATOS'];
		//echo $base_de_datos;
		/*CONSULTA SOBRE LA BASE DE DATOS LOS TIPOS DE IDENTIFICACION*/
		$query = 	"SHOW TABLE STATUS FROM $base_de_datos";
		$result = $db_link->prepare($query);
		$db_link->beginTransaction();
		$result->execute(); //SE EJECUTA EL QUERY
		$arr = $result->errorInfo(); // SE OBTIENE EL ERROR
		$error = $arr[0];
		$errorMessage = str_replace("'", "", $arr[2]);
		//echo $base_de_datos;
		if($error == "00000")
		{
			$db_link->commit();
			$RowCount = 0;
			while($row = $result->fetch(PDO::FETCH_NUM))
			{
				$nombre[0][$RowCount]	= $row[0];
				$tabla = $nombre[0][$RowCount];
		
				// OBTIENE LOS CAMPOS DE LA TABLA
				$query2 = 	"describe $tabla";

				$result2 = $db_link->prepare($query2);
				$db_link->beginTransaction();
				$result2->execute(); //SE EJECUTA EL QUERY
				$arr2 = $result2->errorInfo(); // SE OBTIENE EL ERROR
				$error2 = $arr2[0];
				$errorMessage2 = str_replace("'", "", $arr2[2]);
				//echo $error2;
				if($error2 == "00000")
				{
					$db_link->commit();
					$RowCount2 = 0;
					while($row = $result2->fetch(PDO::FETCH_NUM))
					{
						$nombre_campo[0][$RowCount2] = $row[0];
						$nombre_campo[1][$RowCount2] = $row[1];
						$nombre_campo[2][$RowCount2] = $row[2];
						$nombre_campo[3][$RowCount2] = $row[3];
						
						$campo 		= $nombre_campo[0][$RowCount2];
						$tipo_dato  = $nombre_campo[1][$RowCount2];
						$campo_null = $nombre_campo[2][$RowCount2];
						$campo_key	= $nombre_campo[3][$RowCount2];
						
						if($campo_null == "NO")
						{
							$campo_null = "NOT NULL";
						}
						else
						{
							$campo_null = "NULL";
						}
						//$campos_bd = "";
						$campos_bd .= " <br> ".$campo." ".$tipo_dato." ".$campo_null.",";
						
						//$campos_trigger = "";
						$campos_trigger .= $campo.", ";
						
						//$campos_trigger_i = "";
						//$campos_trigger_u = "";
						//$campos_trigger_d = "";
						
						$campos_trigger_i .= "NEW.".$campo.", ";
						$campos_trigger_u .= "OLD.".$campo.", ";
						$campos_trigger_d .= "OLD.".$campo.", ";
					
						$RowCount2++;
					}
				}
				else
				{
					$db_link->rollBack();
				}
				$campos_bd .= "<br>usuario VARCHAR( 30 ) COLLATE utf8_spanish_ci NOT NULL ,<br> fecha_transaccion DATETIME NOT NULL ,<br> accion VARCHAR( 6 ) COLLATE utf8_spanish_ci NOT NULL <br>)ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;<br>"; 
				$QUERY_BD = "<br><br>CREATE TABLE IF NOT EXISTS ".$tabla."( $campos_bd";
				//echo $QUERY_BD;
				$campos_bd = "";
				
				$campos_trigger_i = substr($campos_trigger_i,0,strlen($campos_trigger_i)-2) . ", USER(), NOW(), 'INSERT'";
				$campos_trigger_u = substr($campos_trigger_u,0,strlen($campos_trigger_u)-2) . ", USER(), NOW(), 'UPDATE'";
				$campos_trigger_d = substr($campos_trigger_d,0,strlen($campos_trigger_d)-2) . ", USER(), NOW(), 'DELETE'";
				$campos_trigger = substr($campos_trigger,0,strlen($campos_trigger)-2) . ", usuario, fecha_transaccion, accion"; 
				$trigger_i_1 = "<br><br>CREATE TRIGGER trigger_".$tabla."_i AFTER INSERT ON $base_de_datos.$tabla<br> FOR EACH ROW <br> INSERT INTO au_$base_de_datos.$tabla($campos_trigger) values ($campos_trigger_i);";
				$trigger_u_1 = "<br><br>CREATE TRIGGER trigger_".$tabla."_u AFTER UPDATE ON $base_de_datos.$tabla<br> FOR EACH ROW <br> INSERT INTO au_$base_de_datos.$tabla($campos_trigger) values ($campos_trigger_u);";
				$trigger_d_1 = "<br><br>CREATE TRIGGER trigger_".$tabla."_d AFTER DELETE ON $base_de_datos.$tabla<br> FOR EACH ROW <br> INSERT INTO au_$base_de_datos.$tabla($campos_trigger) values ($campos_trigger_d);";
				$campos_trigger_i = "";	
				$campos_trigger_u = "";
				$campos_trigger_d = "";	
				$campos_trigger = "";		
				echo $trigger_i_1;
				echo $trigger_u_1;
				echo $trigger_d_1;
				$RowCount++;
			}
		}
		else
		{
			$db_link->rollBack();
		}
	include ('../presentacion/auditoria_detalle.php');
	}
	
	$db_link = null;
}
catch (PDOException $e)
{
	$msg = "Error!: " . $e->getMessage();
	die();
}
?>