<?php
include_once ("../variables_session.php");
Class Consultas {
	
	
	function logon($usuario,$pwd){


                $tabla = "admin_usuarios";
		/*
		 * OBTIENE LOS ATRIBUTOS DE LA TABLA
		 * */
		echo $query ="describe $tabla";
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
					where username='$usuario' and est_codigo=1";
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
                print_r($registros);
                return $registros;
                
	}
	
	function ingresar_agente($id,$per_ape1,$per_ape2,$per_nom1,$per_nom2,$fun_placa,$agt_paswd){
		include_once("../admin/includes/clase_operaciones.php");
		$operaciones = new Operaciones();
		$insercion=$operaciones ->insertar_usuario_bta($id,$per_ape1,$per_ape2,$per_nom1,$per_nom2,$fun_placa,sha1($agt_paswd));
		if($insercion){
			$retorno="1";		
		}else{
			$retorno="0";		
		}
		return $retorno;
	}
	
	function obtener_rango($parametros){
	
		$consultas = new Consultas();
		
		$query = "SELECT FIRST 1 c.cco_nro_inicial ||','||c.cco_nro_final FROM vis_ccomp_6044 c, vis_coleg_6086 a  where c.cco_tipo_formato = 4 and  a.cco_tipo_formato = 4  and  a.fun_placa=" .$consultas -> parserParametro($parametros, "placa"). " AND a.col_estado_legaliz is null and  c.cco_tipo_formato = a.cco_tipo_formato and c.cco_tipo_formato = a.cco_tipo_formato and  c.fun_placa = a.fun_placa  and  a.com_numero >= c.cco_nro_inicial and  a.com_numero <= c.cco_nro_final";
		
	        $vector_vehiculo = db_vector_horizontal($query);
			if ($vector_vehiculo[0]&& $vector_vehiculo[0] != 0 ){
				$partes = split(",", $vector_vehiculo[0]);
				$query = "UPDATE vis_coleg_6086 SET (col_hora_legaliz,col_estado_legaliz, 
				col_fecha_legaliz) = (CURRENT HOUR TO MINUTE,10,today)  
				WHERE cco_tipo_formato = 4  AND fun_placa=" .$consultas -> parserParametro($parametros, "placa")." 
				AND  com_numero >=" . trim($partes[0]). " AND  com_numero <= " . trim($partes[1]);
			}
			db_ejecutar_instruccion($query);
			$agente =  $consultas -> parserParametro($parametros, "placa");
			/*if($agente){
				$query = "INSERT INTO audmv_8624 VALUES(".$agente.",'obtener_rango','" + parametros.trim() +
	                                     "', TODAY,CURRENT HOUR TO MINUTE)";
			}*/
			return $vector_vehiculo[0];        
	}

	function parserParametro($parametros,  $parametro){
		
		$parser = split(",",$parametros);
		$tam_parcer = sizeof($parser);
		for ($i = 0; $i < $tam_parcer;$i++){
			$parser2 = split("=",$parser[$i]);
			if ($parser2[0] == $parametro){
				return trim($parser2[1]);
			}
		}
	}
	
	function comparendo_documentos($placa){
		$query="SELECT  c.car_nro_factura||'|'||c.par_tipo_doc||'|'||c.per_nro_doc||'|'||TRIM(c.env_placa) ||'|'||c.car_fecha_docum||'|'||d.cot_origen||'|'||d.cot_codigo||'|'||c.car_saldo_docum||'|'|| TRIM(sp_nombre(c.par_tipo_doc,c.per_nro_doc,'0')) FROM vis_carte_7007 c,vis_detco_6041 d  WHERE c.env_placa='" .$placa. "' and  c.doc_tipo_car=1 and d.com_estado in (1)  and c.car_saldo_docum>0 and c.car_nro_factura=d.com_numero and c.ort_codigo=d.ort_codigo";
		$vector_vehiculo = db_vector_horizontal($query);
		return $vector_vehiculo[0];
		
	}

	function obtenerRecordset($consulta,$parametros){
		$consultas = new Consultas();
		switch ($consulta){

			case "ocn":
				/*
				-1 en caso de que el agente no tenga rango disponible
				-2 en caso de que el organismo de transito no tenga rango asignado por el RUNT
				numero del ocn que sigue, en caso de exito
				 */

				$retorno = "OCN";
				 $query="select (select  min(com_numero)  from coleg_6086 where ort_codigo=25175000 and fun_placa=".$consultas ->parserParametro($parametros, "placa_agente") ."   and col_estado_legaliz is null),cco_nro_inicial,cco_nro_final from ccomp_6044  where fun_placa=".$consultas ->parserParametro($parametros, "placa_agente") ;

				$vector = db_ejecutar_consulta($query);
                                $fecha=date('Y-m-d H:i:s:u');
                                $fp = fopen("log.txt", "a");
                                $write = fputs($fp, "\n");
                                $write = fputs($fp, $fecha ." : ".$query." parametros:".$parametros);
                                fclose($fp);
				//print_r($vector);
//				exit;
				$comparendo_sigue=$vector[0][0];
				$inferior=$vector[0][1];
				$superior=$vector[0][2];
				if(($comparendo_sigue ==null)or($comparendo_sigue =="")){
					$comparendo_sigue =$inferior;
				}
                                $retorno=$retorno . "\n" . str_replace(".0","",$comparendo_sigue);
				//echo "--".$comparendo_sigue."--".$inferior;
				//exit;
/*
				if(($comparendo_sigue>0) AND($comparendo_sigue>=$inferior ) and ($comparendo_sigue<=$superior) ){
					$query_validar="select count(*) from rancp_8558 where $comparendo_sigue between cco_nro_inicial and cco_nro_final ";
					//$vector_validar = db_ejecutar_consulta($query_validar);
					$vector_validar[0][0]=1;
					if($vector_validar[0][0]>0){
						$retorno=$retorno . "\n" . str_replace(".0","",$comparendo_sigue);	
					}else{
						$retorno=$retorno . "\n" ."-2";
					}
				}else{
					$retorno=$retorno . "\n" ."-1";
				}
 
 */
				//$retorno=$query;
				return $retorno;
			break;
			case "vehiculos_cartagena":
				$retorno = "PLACA|MODELO|NRO MOTOR|MOTOR REG|CHASIS|REG CHASIS|SERIE|REG SERIE|NRO IMPORTACION|ESTADO|FECHA MATRICULA";
				/*
				$query="select s.san_resolucion,s.san_fecha_ini,s.san_fecha_fin,s.per_nro_doc,TRIM(sp_nombre(s.par_tipo_doc,s.per_nro_doc,'0')),decode (s.san_tipo_sancion,'T','TEMPORAL','D','DEFINITIVO'),TRIM(p.par_descripcion),s.san_documento  from vis_sanco_8005 s,vis_param_0031 p where today between s.san_fecha_ini and s.san_fecha_fin and s.san_origen=p.par_codigo and p.cla_clase=507 and s.par_tipo_doc=".$consultas ->parserParametro($parametros, "par_tipo_doc") ." and s.per_nro_doc=".$consultas ->parserParametro($parametros, "per_nro_doc");
				
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . str_replace(".0","",$vector[$i][0]) ."|".$vector[$i][1]."|".$vector[$i][2]."|".$vector[$i][3]."|".$vector[$i][4]."|".$vector[$i][5]."|".$vector[$i][6]."|".$vector[$i][7];
				}
				*/

				return $retorno;

			break;
			case "tarjetas_operacion":
				$retorno = "NRO TARJETA|FECHA EXPEDICION|FECHA VENCIMIENTO|AÑO|ESTADO";
				/*
				$query="select s.san_resolucion,s.san_fecha_ini,s.san_fecha_fin,s.per_nro_doc,TRIM(sp_nombre(s.par_tipo_doc,s.per_nro_doc,'0')),decode (s.san_tipo_sancion,'T','TEMPORAL','D','DEFINITIVO'),TRIM(p.par_descripcion),s.san_documento  from vis_sanco_8005 s,vis_param_0031 p where today between s.san_fecha_ini and s.san_fecha_fin and s.san_origen=p.par_codigo and p.cla_clase=507 and s.par_tipo_doc=".$consultas ->parserParametro($parametros, "par_tipo_doc") ." and s.per_nro_doc=".$consultas ->parserParametro($parametros, "per_nro_doc");
				
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . str_replace(".0","",$vector[$i][0]) ."|".$vector[$i][1]."|".$vector[$i][2]."|".$vector[$i][3]."|".$vector[$i][4]."|".$vector[$i][5]."|".$vector[$i][6]."|".$vector[$i][7];
				}
				*/

				return $retorno;

			break;

			case "empresa_afiliacion":
				$retorno = "CODIGO|NOMBRE|PLACA|TIPO DOC|NRO DOCUMENTO";
				/*
				$query="select s.san_resolucion,s.san_fecha_ini,s.san_fecha_fin,s.per_nro_doc,TRIM(sp_nombre(s.par_tipo_doc,s.per_nro_doc,'0')),decode (s.san_tipo_sancion,'T','TEMPORAL','D','DEFINITIVO'),TRIM(p.par_descripcion),s.san_documento  from vis_sanco_8005 s,vis_param_0031 p where today between s.san_fecha_ini and s.san_fecha_fin and s.san_origen=p.par_codigo and p.cla_clase=507 and s.par_tipo_doc=".$consultas ->parserParametro($parametros, "par_tipo_doc") ." and s.per_nro_doc=".$consultas ->parserParametro($parametros, "per_nro_doc");
				
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . str_replace(".0","",$vector[$i][0]) ."|".$vector[$i][1]."|".$vector[$i][2]."|".$vector[$i][3]."|".$vector[$i][4]."|".$vector[$i][5]."|".$vector[$i][6]."|".$vector[$i][7];
				}
				*/

				return $retorno;

			break;
			case "persona":
				$retorno = "PRIMER NOMBRE|SEGUNDO NOMBRE|PRIMER APELLIDO|SEGUNDO APELLIDO|DIRECCION|MAIL|TELEFONO|PAIS|DEPARTAMENTO|MUNICIPIO|NUMERO LICENCIA|CATEGORIA LICENCIA|FECHA EXP|FECHA VENC|ORG TRANSITO|ESTADO";
				/*
				$query="select s.san_resolucion,s.san_fecha_ini,s.san_fecha_fin,s.per_nro_doc,TRIM(sp_nombre(s.par_tipo_doc,s.per_nro_doc,'0')),decode (s.san_tipo_sancion,'T','TEMPORAL','D','DEFINITIVO'),TRIM(p.par_descripcion),s.san_documento  from vis_sanco_8005 s,vis_param_0031 p where today between s.san_fecha_ini and s.san_fecha_fin and s.san_origen=p.par_codigo and p.cla_clase=507 and s.par_tipo_doc=".$consultas ->parserParametro($parametros, "par_tipo_doc") ." and s.per_nro_doc=".$consultas ->parserParametro($parametros, "per_nro_doc");
				
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . str_replace(".0","",$vector[$i][0]) ."|".$vector[$i][1]."|".$vector[$i][2]."|".$vector[$i][3]."|".$vector[$i][4]."|".$vector[$i][5]."|".$vector[$i][6]."|".$vector[$i][7];
				}
				*/

				return $retorno;

			break;
			case "licencias_nro":
				$retorno = "NRO LICENCIA|FECHA EXP|FECHA VEN|CATEGORIA|NRO DOCUMENTO|NOMBRE";
				/*
				$query="select s.san_resolucion,s.san_fecha_ini,s.san_fecha_fin,s.per_nro_doc,TRIM(sp_nombre(s.par_tipo_doc,s.per_nro_doc,'0')),decode (s.san_tipo_sancion,'T','TEMPORAL','D','DEFINITIVO'),TRIM(p.par_descripcion),s.san_documento  from vis_sanco_8005 s,vis_param_0031 p where today between s.san_fecha_ini and s.san_fecha_fin and s.san_origen=p.par_codigo and p.cla_clase=507 and s.par_tipo_doc=".$consultas ->parserParametro($parametros, "par_tipo_doc") ." and s.per_nro_doc=".$consultas ->parserParametro($parametros, "per_nro_doc");
				
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . str_replace(".0","",$vector[$i][0]) ."|".$vector[$i][1]."|".$vector[$i][2]."|".$vector[$i][3]."|".$vector[$i][4]."|".$vector[$i][5]."|".$vector[$i][6]."|".$vector[$i][7];
				}
				*/

				return $retorno;

			break;

			case "licencias_documento":
				$retorno = "NRO LICENCIA|FECHA EXP|FECHA VEN|CATEGORIA|NRO DOCUMENTO|NOMBRE";
				/*
				$query="select s.san_resolucion,s.san_fecha_ini,s.san_fecha_fin,s.per_nro_doc,TRIM(sp_nombre(s.par_tipo_doc,s.per_nro_doc,'0')),decode (s.san_tipo_sancion,'T','TEMPORAL','D','DEFINITIVO'),TRIM(p.par_descripcion),s.san_documento  from vis_sanco_8005 s,vis_param_0031 p where today between s.san_fecha_ini and s.san_fecha_fin and s.san_origen=p.par_codigo and p.cla_clase=507 and s.par_tipo_doc=".$consultas ->parserParametro($parametros, "par_tipo_doc") ." and s.per_nro_doc=".$consultas ->parserParametro($parametros, "per_nro_doc");
				
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . str_replace(".0","",$vector[$i][0]) ."|".$vector[$i][1]."|".$vector[$i][2]."|".$vector[$i][3]."|".$vector[$i][4]."|".$vector[$i][5]."|".$vector[$i][6]."|".$vector[$i][7];
				}
				*/

				return $retorno;

			break;

			case "licencias_suspendidas":
				$retorno = "RESOLUCION|FECHA INICIAL|FECHA FINAL|NRO DOCUMENTO|NOMBRE|TIPO|DESCRIPCION|COMPARENDO";
				$query="select s.san_resolucion,s.san_fecha_ini,s.san_fecha_fin,s.per_nro_doc,TRIM(sp_nombre(s.par_tipo_doc,s.per_nro_doc,'0')),decode (s.san_tipo_sancion,'T','TEMPORAL','D','DEFINITIVO'),TRIM(p.par_descripcion),s.san_documento  from vis_sanco_8005 s,vis_param_0031 p where today between s.san_fecha_ini and s.san_fecha_fin and s.san_origen=p.par_codigo and p.cla_clase=507 and s.par_tipo_doc=".$consultas ->parserParametro($parametros, "par_tipo_doc") ." and s.per_nro_doc=".$consultas ->parserParametro($parametros, "per_nro_doc");
				
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . str_replace(".0","",$vector[$i][0]) ."|".$vector[$i][1]."|".$vector[$i][2]."|".$vector[$i][3]."|".$vector[$i][4]."|".$vector[$i][5]."|".$vector[$i][6]."|".$vector[$i][7];
				}
				return $retorno;

			break;
			case "rev_tecnomecanica":
				$retorno = "PLACA|NRO SERVITECA|FECHA REVISION|FECHA HASTA";
				$query="SELECT first 1 env_placa,rev_numero_servite,rev_fecha_revi,rev_fecha_hasta FROM vis_revte_7017  WHERE env_placa = '".$consultas -> parserParametro($parametros, "env_placa") ."' ORDER BY REV_FECHA_HASTA DESC";

				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . str_replace(".0","",$vector[$i][0]) ."|".$vector[$i][1]."|".$vector[$i][2]."|".$vector[$i][3];
				}
				return $retorno;

			break;
			case "datos_agente":
				$retorno = "NumDoc|Apellido1|Apellido2|Nombre1|Nombre2|Perfil";
				$query="select a.per_nro_doc,a.per_ape1,a.per_ape2,a.per_nom1,a.per_nom2,(select perf_id from agper_1706 where agt_placa='".$consultas -> parserParametro($parametros, "placa_agente") ."') from vis_perso_0019 a join vis_agtra_6029 b on a.per_nro_doc=b.per_nro_doc and a.par_tipo_doc =b.par_tipo_doc where b.par_tipo_doc=1 and b.fun_placa='".$consultas -> parserParametro($parametros, "placa_agente") ."'";
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . str_replace(".0","",$vector[$i][0]) ."|".trim($vector[$i][1])."|".trim($vector[$i][2])."|".trim($vector[$i][3])."|".trim($vector[$i][4])."|".$vector[$i][5];
				}
				return $retorno;

			break;
			case "log_actividades":
				$retorno = "FECHA|X|Y|PLACA";
				$query="select lgp_fecha,lgp_coo_x,lgp_coo_y,pdu_usr_id  from lgp_lggps_1702 where pdu_usr_id='".$consultas -> parserParametro($parametros, "agente") ."' order by lgp_fecha desc";
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . $vector[$i][0] ."|".$vector[$i][1]."|".$vector[$i][2]."|".$vector[$i][3];
				}
				return $retorno;

			break;

			case "comparendos_documento":
				$retorno = "NRO COMPARENDO|TIPO DOC|NRO DOCUMENTO|PLACA|FECHA COMPARENDO|ORIGEN|CODIGO|VALOR|NOMBRE";
				$query="SELECT first 10  c.car_nro_factura||'|'||c.par_tipo_doc||'|'||c.per_nro_doc||'|'||TRIM(c.env_placa)||'|'||c.car_fecha_docum||'|'||d.cot_origen||'|'||d.cot_codigo||'|'||c.car_saldo_docum||'|'|| TRIM(sp_nombre(c.par_tipo_doc,c.per_nro_doc,'0')) FROM vis_carte_7007 c,vis_detco_6041 d  WHERE c.par_tipo_doc=" .$consultas ->parserParametro($parametros, "par_tipo_doc") .                                    " and c.per_nro_doc=" .$consultas ->parserParametro($parametros, "per_nro_doc") .                                    " and  c.doc_tipo_car=1  and d.com_estado in (1) and c.car_saldo_docum>0 and c.car_nro_factura=d.com_numero and c.ort_codigo=d.ort_codigo";
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . $vector[$i][0];
				}
				return $retorno;

			break;
			case "cartera_placa":
				$retorno = "TIPO CARTERA|PLACA|VALOR";
				$query="SELECT TRIM(p.par_descripcion) ,trim(c.env_placa), sum(c.car_saldo_docum) FROM vis_carte_7007 c , vis_param_0031 p  WHERE env_placa = '" .$consultas -> parserParametro($parametros, "env_placa") ."' AND c.doc_tipo_car = p.par_codigo AND p.cla_clase = 125  AND c.doc_tipo_car IN (1,2,3,4,5,6,7) AND c.car_saldo_docum>0 group by c.env_placa,p.par_descripcion";
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . $vector[$i][0] ."|".$vector[$i][1]."|".str_replace(".0","",$vector[$i][2]);
				}
				return $retorno;

			break;
			case "cartera_persona":
				$retorno = "TIPO CARTERA|TIPO DOC|NRO DOCUMENTO|NOMBRE|VALOR";
				$query="SELECT trim(p.par_descripcion),c.par_tipo_doc,c.per_nro_doc,TRIM(sp_nombre(c.par_tipo_doc,c.per_nro_doc,'0')),sum(car_saldo_docum) FROM vis_carte_7007 c,  vis_param_0031 p WHERE c.par_tipo_doc=" .$consultas ->parserParametro($parametros, "par_tipo_doc") . " and c.per_nro_doc=" .$consultas ->parserParametro($parametros, "per_nro_doc") . " AND c.doc_tipo_car = p.par_codigo AND p.cla_clase = 125 AND c.doc_tipo_car IN (1,2,3,4,5,6,7)  AND c.car_saldo_docum>0 group by  p.par_descripcion ,c.par_tipo_doc,c.per_nro_doc ";

				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . $vector[$i][0] ."|".$vector[$i][1]."|".str_replace(".0","",$vector[$i][2])."|".str_replace(".0","",$vector[$i][3])."|".str_replace(".0","",$vector[$i][4]);
				}
				return $retorno;

			break;
			case "comparendo_placa":
				$retorno = "NRO COMPARENDO|TIPO DOC|NRO DOCUMENTO|PLACA|FECHA COMPARENDO|ORIGEN|CODIGO|VALOR|NOMBRE";
				$query="SELECT first 10 c.car_nro_factura||'|'||c.par_tipo_doc||'|'||c.per_nro_doc||'|'||TRIM(c.env_placa) ||'|'||c.car_fecha_docum||'|'||d.cot_origen||'|'||d.cot_codigo||'|'||c.car_saldo_docum||'|'|| TRIM(sp_nombre(c.par_tipo_doc,c.per_nro_doc,'0')) FROM vis_carte_7007 c,vis_detco_6041 d  WHERE c.env_placa='".$consultas -> parserParametro($parametros, "env_placa") ."' and  c.doc_tipo_car=1 and d.com_estado in (1)  and c.car_saldo_docum>0 and c.car_nro_factura=d.com_numero and c.ort_codigo=d.ort_codigo";
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . $vector[$i][0];
				}
				return $retorno;

			break;
			case "comparendo_numero":
				$retorno = "NRO COMPARENDO|TIPO DOC|NRO DOCUMENTO|PLACA|FECHA COMPARENDO|ORIGEN|CODIGO|VALOR|NOMBRE";
				$query="SELECT first 10 c.car_nro_factura,c.par_tipo_doc,c.per_nro_doc,TRIM(c.env_placa) ,c.car_fecha_docum,d.cot_origen,d.cot_codigo,c.car_saldo_docum,TRIM(sp_nombre(c.par_tipo_doc,c.per_nro_doc,'0')) FROM vis_carte_7007 c,vis_detco_6041 d  WHERE c.car_nro_factura='".$consultas -> parserParametro($parametros, "env_numero")."' and  c.doc_tipo_car=1 and d.com_estado =1 and c.car_saldo_docum>0 and c.car_nro_factura=d.com_numero and c.ort_codigo=d.ort_codigo";
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . $vector[$i][0] ."|".$vector[$i][1]."|".str_replace(".0000000000000000","",$vector[$i][2])."|".str_replace(".0","",$vector[$i][3])."|".str_replace(".0","",$vector[$i][4].$vector[$i][5]."|".$vector[$i][6]."|".str_replace(".0000000000000000","",$vector[$i][7])."|".$vector[$i][8]);
				}
				return $retorno;

			break;
			case "notificaciones":
				$retorno = "NRO PROCESO|FECHA RADICACION|NRO COMPARENDO|PLACA";
				 $query="select p.arp_nro_proceso||'|'||p.rpr_fecha_rad||'|'|| r.comparendo||'|'||r.env_placa from vis_radtp_8171 r, vis_parte_8143 p where r.par_tipo_proceso = p.par_tipo_proceso and r.arp_nro_proceso = p.arp_nro_proceso and r.fecha = p.rpr_fecha_rad and r.par_tipo_proceso in (19,34)  and p.par_tipo_generador = 1 and p.par_tipo_doc ='1'  and p.per_nro_doc ='".$consultas -> parserParametro($parametros, "per_nro_doc")."'";
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . $vector[$i][0];
				}
				return $retorno;

			break;
			case "procesos_coactivos":
				$retorno ="NRO PROCESO|NOMBRE|TIPO DOC|NRO DOC|FECHA PROC|NRO COMPARENDO|FECHA COMP"; 
				$query = "select b.arp_nro_proceso,trim(f.per_ape1)||' '||trim(f.per_ape2) ||' '||trim(f.per_nom1)||' '|| trim(f.per_nom2),b.par_tipo_doc,b.per_nro_doc ,b.rpr_fecha_rad,a.comparendo,a.com_fecha_contra from vis_parte_8143 b,vis_radtp_8171 a,vis_perso_0019 f  where  a.par_tipo_proceso=34 and b.per_nro_doc=f.per_nro_doc  and a.par_tipo_proceso=b.par_tipo_proceso and a.arp_nro_proceso=b.arp_nro_proceso and a.fecha=b.rpr_fecha_rad and b.per_nro_doc=' ".$consultas -> parserParametro($parametros, "per_nro_doc") ."' and b.par_tipo_doc=1
and b.par_tipo_doc=f.par_tipo_doc";
				$vector = db_ejecutar_consulta($query);
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . $vector[$i][0]."|".$vector[$i][1]."|".str_replace(".0","",$vector[$i][2])."|".str_replace(".0","",$vector[$i][3])."|".$vector[$i][4]."|".$vector[$i][5]."|".$vector[$i][6];
				}
				return $retorno;
				
				/*if (consulta.equalsIgnoreCase("vehiculos_nuevo")) { //env_placa
      retorno = "CLASE|TIPO|PASAJEROS|MODALIDAD|RADIO|NIT|RSOCIAL|TOPERACION|LTRANSITO|ORGANISMO|IDENTIFICACION|TIDENTIFICACION|NOMBRES";
      resul = web.EjecutarConsulta("select distinct c.par_clase_veh||'|'||b.par_servicio||'|'||i.hes_valor||'|'||''||'|'||''||'|'||g.per_nro_doc||'|'||trim (both from (nit_nombre))||'|'||top_nro_tarj||'|'||h.ltr_nro_tarjeta ||'|'|| h.ort_codigo||'|'||a.per_nro_doc||'|'|| a.par_tipo_doc||'|'||trim (both from (f.per_ape1))||' '|| trim (both from (case f.per_ape2 when null then '' else f.per_ape2 end))||' '||trim (both from (f.per_nom1))||' '|| trim (both from (case f.per_nom2 when null then '' else f.per_nom2 end)) from vis_vehpr_6015 a   join vis_perso_0019 f on a.per_nro_doc=f.per_nro_doc   join vis_enveh_6031 d on a.env_placa=d.env_placa  join vis_hserv_6062 b on a.env_placa=b.env_placa                 join vis_hclas_7036 c on a.env_placa=c.env_placa                 join vis_toper_6005 e on a.env_placa=e.env_placa                 join vis_nitst_0305 g on e.nit_codigo_empresa=g.nit_codigo_empresa                 join vis_ltran_6003 h on a.env_placa=h.env_placa join vis_hespe_6032 i on a.env_placa=i.env_placa where b.hca_fecha_desde <= today and (b.hca_fecha_hasta >= today or b.hca_fecha_hasta is null)  and  c.hcl_fecha_desde <= today and (c.hcl_fecha_hasta >= today or c.hcl_fecha_hasta is null) and top_fecha_exped <= today and (top_venc_tarjeta >= today or top_venc_tarjeta is null) and a.par_tipo_doc=f.par_tipo_doc and top_estado = 'V' and ltr_estado = 'V' and par_codigo_espe = 1 AND hes_fecha_hasta IS NULL and a.env_placa='"
                                   + parserParametro(parametros, "env_placa") +
                                   "'");

    }*/
				break;
			case "vehiculos_nuevo":
				$retorno = "CLASE|TIPO|PASAJEROS|MODALIDAD|RADIO|NIT|RSOCIAL|TOPERACION|LTRANSITO|ORGANISMO|IDENTIFICACION|TIDENTIFICACION|NOMBRES";
				$query = "select distinct c.par_clase_veh as a,b.par_servicio as b,i.hes_valor as c,'' as uno,'' as dos,g.per_nro_doc as d,trim (both from (nit_nombre)),top_nro_tarj as e,h.ltr_nro_tarjeta as f, h.ort_codigo as g,a.per_nro_doc as h, a.par_tipo_doc as i,trim (both from (f.per_ape1))||' '|| trim (both from (case f.per_ape2 when null then '' else f.per_ape2 end))||' '||trim (both from (f.per_nom1))||' '|| trim (both from (case f.per_nom2 when null then '' else f.per_nom2 end)) from vis_vehpr_6015 a   join vis_perso_0019 f on a.per_nro_doc=f.per_nro_doc   join vis_enveh_6031 d on a.env_placa=d.env_placa  join vis_hserv_6062 b on a.env_placa=b.env_placa                 
				join vis_hclas_7036 c on a.env_placa=c.env_placa                 
				join vis_toper_6005 e on a.env_placa=e.env_placa                 
				join vis_nitst_0305 g on e.nit_codigo_empresa=g.nit_codigo_empresa                 
				join vis_ltran_6003 h on a.env_placa=h.env_placa join vis_hespe_6032 i on a.env_placa=i.env_placa where b.hca_fecha_desde <= today and (b.hca_fecha_hasta >= today or b.hca_fecha_hasta is null)  and  c.hcl_fecha_desde <= today and (c.hcl_fecha_hasta >= today or c.hcl_fecha_hasta is null) and top_fecha_exped <= today and (top_venc_tarjeta >= today or top_venc_tarjeta is null) and a.par_tipo_doc=f.par_tipo_doc and top_estado = 'V' and ltr_estado = 'V' and par_codigo_espe = 1 AND hes_fecha_hasta IS NULL 
				and a.env_placa='".$consultas -> parserParametro($parametros, "env_placa") ."'";
				$vector = db_ejecutar_consulta($query);
				if (is_array($vector)){
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . $vector[$i][0]."|".$vector[$i][1]."|".$vector[$i][2]."|".$vector[$i][3]."|".$vector[$i][4]."|".$vector[$i][5]."|".$vector[$i][6]."|".$vector[$i][7]."|".$vector[$i][8]."|".$vector[$i][9]."|".$vector[$i][10]."|".$vector[$i][11]."|".$vector[$i][12];
				}
				
		}else{$retorno = $retorno . "\n" ."||||||||||||";
		}
		 return $retorno;

			break;
			case "persona_sdm":
		 /*if (consulta.equalsIgnoreCase("persona_sdm")) { //par_tipo_doc y per_nro_doc
     retorno = "PRIMER NOMBRE|SEGUNDO NOMBRE|PRIMER APELLIDO|SEGUNDO APELLIDO|DIRECCION|MAIL|TELEFONO";
           resul = web.EjecutarConsulta("SELECT TRIM(a.per_nom1)||'|'||TRIM(a.per_nom2)||'|'||TRIM(a.per_ape1)||'|'||TRIM(a.per_ape2)||'|'|| TRIM(a.per_direc)||'|'||TRIM(a.per_email)||'|'||a.per_telefono  from  vis_perso_0019 a "
                                  + " WHERE " +
                                  " a.par_tipo_doc=" +
                                  parserParametro(parametros, "par_tipo_doc") +
                                  " and a.per_nro_doc=" +
                                  parserParametro(parametros, "per_nro_doc"));
   }*/ 
	   error_reporting(0);
	$retorno = "PRIMER NOMBRE|SEGUNDO NOMBRE|PRIMER APELLIDO|SEGUNDO APELLIDO|DIRECCION|MAIL|TELEFONO|CATEGORIALIC|ESTADOLIC|ORGANISMOLIC|NROLIC";	
		//$wsdl="http://172.16.40.19:8080/serviciosWeb/ConsultaServiciosService?wsdl";
		//$wsdl="http://192.168.100.68:8080/serviciosWeb/ConsultaServiciosService?wsdl";
	
//		$wsdl="http://192.168.201.30:8080/serviciosWeb/ConsultaServiciosService?wsdl";
//		$client=new soapclient($wsdl, 'wsdl');
//		$param=array('arg0'=>$consultas -> parserParametro($parametros, "par_tipo_doc"),'arg1'=>$consultas -> parserParametro($parametros, "per_nro_doc")); 

		// consumo del web service a traves de nusoap , el retorno se almacena en el vector $param
//		$vector= $client->call('ConsultaPersonaNatural', $param);
			
//		if (is_array($vector)and($vector["return"]["personaNatural"]["nombreUno"]!="")){
//			print_r($retorno);
//			$indices=array_keys($retorno);
			//print_r(array_keys($retorno));
			/*	
			  $retorno = $retorno . "\n" . $vector["return"]["personaNatural"]["nombreUno"]."|".$vector["return"]["personaNatural"]["nombreDos"]."|".$vector["return"]["personaNatural"]["apellidoUno"]."|".$vector["return"]["personaNatural"]["apellidoDos"]."|".$vector["return"]["personaNatural"]["direccion"]."|".$vector["return"]["personaNatural"]["email"]."|".str_replace(" - ","",$vector["return"]["personaNatural"]["telefono"]);

			 $vector2= $client->call('ConsultaCunductorLicencia', $param);
			 //print_r($vector2);
			if (is_array($vector2)){
				
				if(array_key_exists("categoria",$vector2["return"]["conductor"]["licencias"])){
					  $retorno = $retorno . "|".$vector2["return"]["conductor"]["licencias"]["categoria"]."|".$vector2["return"]["conductor"]["licencias"]["estadoLicencia"]."|".$vector2["return"]["conductor"]["licencias"]["nombreOrganismo"]."|".$vector2["return"]["conductor"]["licencias"]["numeroLicencia"];
				
				}else{
					//print_r($vector2["return"]["conductor"]["licencias"]);
					for ($i = 0; $i<sizeof($vector2["return"]["conductor"]["licencias"]);$i++ ) {
						if($vector2["return"]["conductor"]["licencias"][$i]["estadoLicencia"]=="ACTIVA"){
						  $retorno = $retorno . "\n" . $vector2["return"]["conductor"]["licencias"][$i]["categoria"]."|".$vector2["return"]["conductor"]["licencias"][$i]["estadoLicencia"]."|".$vector2["return"]["conductor"]["licencias"][$i]["nombreOrganismo"]."|". $vector2["return"]["conductor"]["licencias"][$i]["numeroLicencia"];
						  $sw=1;
						
						}
						//else{
						//	$retorno = $retorno . "||||";
						//}
					}
					if($sw!=1){
						$retorno = $retorno . "||||";
					}
				}

				  
			}else{
				  $retorno = $retorno . "||||";
			}


		}else{


*/
			$query = "SELECT TRIM(a.per_nom1),TRIM(a.per_nom2),TRIM(a.per_ape1),TRIM(a.per_ape2),TRIM(a.per_direc),TRIM(a.per_email),a.per_telefono  from  vis_perso_0019 a 
                                   WHERE  a.par_tipo_doc= ".$consultas -> parserParametro($parametros, "par_tipo_doc") ."
                                   and a.per_nro_doc=".$consultas -> parserParametro($parametros, "per_nro_doc");
             $vector = db_ejecutar_consulta($query);  
             if (is_array($vector)){
				for ($i = 0; $i<sizeof($vector);$i++ ) {
					  $retorno = $retorno . "\n" . $vector[$i][0]."|".$vector[$i][1]."|".$vector[$i][2]."|".$vector[$i][3]."|".$vector[$i][4]."|".$vector[$i][5]."|".$vector[$i][6];
				}
					  $retorno = $retorno . "||||";
				
			}else{$retorno = $retorno . "\n" ."||||||". "||||";}


		//}
		 	return $retorno; 			
			break;
		}

	
	}


   //valida que el usuario pueda autenticarse en caso de no tener sesiones simultaneas
	function validar_sesiones_simultaneas($mac,$usuario){
		$consultas = new Consultas();
		$query="select count(*) from pda_tmses_1710 where pdu_usr_id=$usuario and ses_fecha_fin is null";
		$pda_id=db_buscar_valor($query);
		if($pda_id>0){
			return false;
		
		}else{
			return true;
		}
			
		
		
	}
	

	/* version hasta 2011-07-25
	function poner_fecha($foto,$fecha,$nombre){
			//echo $foto;
			$archivo = $nombre.".jpg";
			$fp = fopen($archivo, "a");
			$string = base64_decode($foto);
			$write = fputs($fp, $string);
			fclose($fp);
			//$archivo = "1_20110608_154744_RGR467.jpg";
			$image = imagecreatefromstring(file_get_contents($archivo));
			$black= imagecolorallocate($image, 0, 0, 0);
			$white = imagecolorallocate($image, 255, 255, 255);
			imagefilledrectangle($image, 0, 0, 160, 20, $black);
			//$image = base64_decode($foto);

			imagettftext($image, 12, 0, 10, 15, $white, "arialbd.ttf", $fecha);
			imagejpeg($image,"fotos/$archivo");
			if(is_file("fotos/$archivo")){
				unlink($archivo);
			}
	}
*/

	function poner_fecha($foto,$fecha,$nombre,$direccion){
			//echo $foto;
			$archivo = $nombre.".jpg";
			$fp = fopen($archivo, "a");
			$string = base64_decode($foto);
			$write = fputs($fp, $string);
			fclose($fp);
			//$archivo = "1_20110608_154744_RGR467.jpg";
			$image = imagecreatefromstring(file_get_contents($archivo));
			$black= imagecolorallocate($image, 0, 0, 0);
			$white = imagecolorallocate($image, 255, 255, 255);
			imagefilledrectangle($image, 0, 0,500, 20, $black);
			//$image = base64_decode($foto);

			imagettftext($image, 12, 0, 10, 15, $white, "arialbd.ttf", $fecha. " " . $direccion);
			imagejpeg($image,"fotos/$archivo");
			if(is_file("fotos/$archivo")){
				unlink($archivo);
			}
	}





// OK?.................................................................
	function autenticar_usuario($mac,$ip,$usuario,$pass,$proyecto){
		/*
		 * mac: 1C:4B:D6:DA:3C:6B
		 * ip: 172.16.251.26
		 * */

		$consultas = new Consultas();
		//este es con validacion de IP
		//$query="select pda_pda_id from pda_dpdas_1701 where pda_mac='$mac' and pda_ip='$ip' and par_estado = 1";
		//Aca se validan las sesiones simultaneas
	//	if($consultas->validar_sesiones_simultaneas($mac,$usuario)==true){
			//este es sin validacion de IP
			$query="select pda_pda_id from pda_dpdas_1701 where pda_mac='$mac' and par_estado = 1";
			$pda_id=db_buscar_valor($query);
			if($pda_id!=""){
				$query="select agt_placa from  pdagt_1705   
						where agt_placa = '$usuario' and agt_paswd = '$pass'";
				$nro_doc=db_buscar_valor($query);
				if($nro_doc!=""){
					$retorno=1;
					$query="insert into pda_tmses_1710 (ses_fecha_ini,pdu_usr_id,pda_mac)values(CURRENT,$usuario,'$mac')";
					//exit;
				//	db_ejecutar_instruccion($query);
				}else{
					$retorno=2;//el usuario no se encuentra
				}
				
			}else{
				$retorno=3;//la ip o mac no se enceuntran
			}	
		
	//	}else{
	//		$retorno=4; //ya existe una sesion abierta para el usuario
//
//		}
	
		return $retorno;
	}

	function generarSqlUsuarios($mac,$ip){

		$consultas = new Consultas();
		$query="select pda_pda_id from pda_dpdas_1701 where pda_mac='$mac' and pda_ip='$ip'";
		$pda_id=db_buscar_valor($query);
		if($pda_id!=""){
			$query="select 'insert into RegAgente values('''||b.par_tipo_doc||''','''||b.per_nro_doc||''',''1'','''','''||trim (both from b.per_ape1)||''','''||trim (both from (case b.per_ape2 when null then ' ' else b.per_ape2 end)) ||''','''||trim (both from b.per_nom1)||''','''||trim (both from (case b.per_nom2 when null then ' ' else b.per_nom2 end)) ||''','''','''','''||c.uex_clave||''',''MASTER'',''1'','''','''||c.uex_login||''')'  from vis_perso_0019 b join vis_usuex_1304 c on b.per_nro_doc=c.uex_nro_doc  where c.uex_tipo_usuario='PD'";
			$vector_vehiculo = db_ejecutar_consulta($query);
			for ($i = 0; $i<sizeof($vector_vehiculo);$i++ ) {
				  $retorno = $retorno . "\n" . $vector_vehiculo[$i][0];
			}
		}else{
			$retorno=-1;
		}
		return $retorno;
	}

	function generarSqlMarcas($parametro){
		$query="select 'insert into TIPO_MARCA(IdTipoMarca,Descripcion)values('''||par_codigo||''','''|| trim(both from par_descripcion) ||''')' from vis_param_0031 WHERE cla_clase=29 order by par_codigo";
		$vector_vehiculo = db_ejecutar_consulta($query);
		for ($i = 0; $i<sizeof($vector_vehiculo);$i++ ) {
			  $retorno = $retorno . "\n" . $vector_vehiculo[$i][0];
		}
		return $retorno;
	}
	function generarSqlTarifas($parametro){
		$query="select 'insert into TIPO_TARIFAS values('''|| cot_origen||''','''||cot_codigo||''','''||tar_valor||''','''||tar_fecha_inicial||''','''||case tar_fecha_final when null then 'NULL' else CAST (tar_fecha_final AS varchar(15))end ||''','''||der_concepto||''')' from vis_tarco_8169 where tar_fecha_inicial<=TODAY and (tar_fecha_final is null or tar_fecha_final>=TODAY)  order by tar_fecha_final ,cot_origen,cot_codigo";
		$vector_vehiculo = db_ejecutar_consulta($query);
		for ($i = 0; $i<sizeof($vector_vehiculo);$i++ ) {
			  $retorno = $retorno . "\n" . $vector_vehiculo[$i][0];
		}
		return $retorno;
	}
	function generarSqlModelos($parametro){
		$query="select ''''||par_marca_veh ||''','''||mar_linea||''','''||trim(both from mar_descripcion)||'''' from vis_marli_6084 ";
		$vector_vehiculo = db_ejecutar_consulta($query);
		for ($i = 0; $i<sizeof($vector_vehiculo);$i++ ) {
			  $retorno = $retorno . "\n" . $vector_vehiculo[$i][0];
		}
		return $retorno;
	}
	function generarSqlEmpresas($parametro){
		$query="select 'insert into TIPO_EMPRESA values('''|| b.emp_codigo_empresa||''','''||trim(both from replace(a.per_nombre_comer,\"\'\",\"`\")) ||''')' from vis_perso_0019 a join vis_empre_6011 b on a.per_nro_doc=b.per_nro_doc where a.par_tipo_doc=2";
		$vector_vehiculo = db_ejecutar_consulta($query);
		for ($i = 0; $i<sizeof($vector_vehiculo);$i++ ) {
			  $retorno = $retorno . "\n" . $vector_vehiculo[$i][0];
		}
		return $retorno;
	}

	function generarSqlPatios($parametro){
		$query="select 'insert into Patios(CodPatio,DescripPatio,DirecPatio,CapVehiculos,CapMotos) values('''|| pat_codigo||''','''||trim(both from pat_nombre)||''','''||trim(both from pat_direccion)||''',''1000'',''1000'')'  from vis_patio_6027";
		$vector_vehiculo = db_ejecutar_consulta($query);
		for ($i = 0; $i<sizeof($vector_vehiculo);$i++ ) {
			  $retorno = $retorno . "\n" . $vector_vehiculo[$i][0];
		}
		return $retorno;
	}
	function ingresarLogGps($pda_id,$coordenada_x,$coordenada_y,$usuario,$mac){
		$query="insert into lgp_lggps_1702 (pda_pda_id,lgp_fecha,lgp_coo_x,lgp_coo_y,pdu_usr_id,pda_mac)values('".$pda_id."',CURRENT,'".str_replace(",",".",$coordenada_x)."','".str_replace(",",".",$coordenada_y)."','".$usuario."','".$mac."')";
		$insert = db_ejecutar_instruccion($query);
		if($insert==true){
			$retorno="1";
		}else{
			$retorno="0";
		}
		return $retorno;
	}

	function ingresarLogGeneral($mac,$usuario,$accion,$detalle,$ip,$exito,$ws){
		$query="insert into pda_logen_1711 (pda_mac,pdu_usr_id,par_accion,lgp_fecha,log_detalle,pda_ip,par_exito,par_websr)values('".$mac."','".$usuario."','".$accion."',CURRENT,'".$detalle."','$ip','$exito','$ws')";
		$insert = db_ejecutar_instruccion($query);
	/*
		if($insert==true){
			$retorno="1";
		}else{
			$retorno="0";
		}
		return $retorno;
	*/
	}


	function ingresarLogFotoComparendo($mac,$usuario,$accion,$detalle,$ip,$exito,$ws,$coordenada_x,$coordenada_y){
		$query="insert into pda_logen_1711 (pda_mac,pdu_usr_id,par_accion,lgp_fecha,log_detalle,pda_ip,par_exito,par_websr,lgp_coo_x,lgp_coo_y)values('".$mac."','".$usuario."','".$accion."',CURRENT,'".$detalle."','$ip','$exito','$ws','".str_replace(",",".",$coordenada_x)."','".str_replace(",",".",$coordenada_y)."')";
		$insert = db_ejecutar_instruccion($query);
	}

	function ingresarImagenComparendo($id,$content){
	$archivo = $id.'.JPEG';
	$fp = fopen("fotos/".$archivo, "a");
	$string = base64_decode($content);
	$write = fputs($fp, $string);
	fclose($fp);
	//return $content;
	 //se usa de la siguiente manera subir(fuente de la imagen,nombre de la imagen,ancho del thumbnail, ancho de la imagen detalle,carpeta para la imagen detalle,carpeta para el thumbnail) 
	//subir($HTTP_POST_FILES['imagen']['tmp_name'],$HTTP_POST_FILES['imagen']['name'],350,541."carpeta/imagenes","carpeta/thumbnail"); 
	
	//las carpetas deben tener permisos 777 
	//no pases los $HTTP_POST_FILES asi, valida el tipo, su tamaño, etc... 
	//si quieres pasar a base de datos la ubicacion de tu imagen 
	//el VALUE seria "img/usuarios/$HTTP_POST_FILES['imagen']['name']" 
		
		
		if($write==true){
			$retorno="1";
		}else{
			$retorno="0";
		}
		return $retorno;
	}
function ingresar_comparendo_nuevo($ncomp,
	$fecinfra,
	$horainfra,
	$luginfra,
	$placa,
	$codinfr,
	$tipodoc,
	$nrodoc,
	$nombre,
	$agente,
	$entidad,
	$inmoviliza,
	$factor,
	$tipovehiculo,
	$claseserv,
	$tipoinfractor,
	$observaciones,
	$pasajeros,
	$modalidad,
	$radio,
	$nit,
	$razonsocial,
	$toperacion,
	$lictransito,
	$propnombre,
	$proptipoid,
	$propnumeroid,
	$opcion,
	$nrofilaprod,
	$pedagogico,
	$fuga,
	$cod_patio,
	$cod_grua){
		
/*
		$query="INSERT INTO vis_cargue_iq (nro_comparendo,fecha_infraccion  ,hora_infr ,lugar_infr,placa ,codigo_infr   ,tipo_doc  ,nro_dcto  ,nombre,dire_infractor,telefono_infractor,agente,inmoviliza,factor,imagen,fecha_proceso ,estado_liq,fecha_cargue  ,tipo_vehiculo ,clase_servicio,tipo_infractor,observaciones ,polca ,com_numero_total)VALUES ('" . $ncomp .
        "',TODAY,'" . $horainfra . "','" .
        $luginfra . "','" . $placa . "','" . $codinfr . "','" . $tipodoc . "'," .
        $nrodoc . ",'" . $nombre . "','direccion','123456','" . $agente . "','" . $inmoviliza . "','" .
        $factor . "','','',0,TODAY," . $tipovehiculo . "," . $claseserv . "," .
        $tipoinfractor . ",'"
        . $observaciones . "','N','110010000000000".$ncomp."')";

		*/
    
                 $query_infra_alfa="select cot_codigo_alfa from contr_6030 where cot_origen=115 and cot_codigo=".$codinfr;

				$fecha=date('Y-m-d H:i:s');
				$fp = fopen("log.txt", "a");
				$write = fputs($fp, "\n");
				$write = fputs($fp, $fecha ." : ".$query_infra_alfa);
				fclose($fp);

				$vector = db_ejecutar_consulta($query_infra_alfa);
                $codinfr_alfa=$vector[0][0];
                $cod_grua=str_replace("NULL","",$cod_grua);
                $observaciones=str_replace("NULL","",$observaciones);
                
                
                $nombre_matriz=explode(" ",$nombre);
                $inf_per_nom1=$nombre_matriz[0];
                $inf_per_nom2=$nombre_matriz[1];
                $inf_per_ape1=$nombre_matriz[2];
                $inf_per_ape2=$nombre_matriz[3];
                 
                
		$query="INSERT INTO cargue_iq (nro_comparendo,fecha_infraccion  ,hora_infr ,lugar_infr,placa ,codigo_infr   ,tipo_doc  ,nro_dcto  ,nombre,inf_per_direc,inf_per_telefono,agente,inmoviliza,factor,imagen,fecha_proceso ,estado_liq,fecha_cargue  ,tipo_vehiculo ,clase_servicio,tipo_infractor,observaciones ,polca ,com_numero_total,com_origen,placa_agente,cod_patio,cod_grua,fuga,pedagogico,inf_per_nom1, inf_per_nom2,inf_per_ape1, inf_per_ape2)VALUES ('" . $ncomp .
        "',TODAY,'" . $horainfra . "','" .
        $luginfra . "','" . $placa . "','" . trim($codinfr_alfa). "','" . $tipodoc . "'," .
        $nrodoc . ",'" . $nombre . "','direccion','123456','" . $agente . "','" . $inmoviliza . "','1','','',0,TODAY," . $tipovehiculo . "," . $claseserv . "," .
        $tipoinfractor . ",'"
        . $observaciones . "','N','".$ncomp."',3,'" . $agente . "','" . $cod_patio . "','" . $cod_grua . "','" . $fuga . "','" . $pedagogico . "','" . $inf_per_nom1 . "','" . $inf_per_nom2 . "','" . $inf_per_ape1 . "','" . $inf_per_ape2  . "')";
                
                //exit;
		$fecha=date('Y-m-d H:i:s');

		$fp = fopen("log.txt", "a");
		$write = fputs($fp, "\n");
		$write = fputs($fp, $fecha ." : ".$query);
		fclose($fp);
		$insert = db_ejecutar_instruccion($query);
		if($insert==true){
			$hora_insert=$fecha=date('H:i');
			$query_lega="update  vis_coleg_6086 set col_hora_legaliz='".$hora_insert."',col_estado_legaliz=1,col_fecha_legaliz=TODAY where cco_tipo_formato= 1 and  com_numero=".$ncomp;
                        $fp = fopen("log.txt", "a");
                        $write = fputs($fp, "\n");
                        $write = fputs($fp, $fecha ." : ".$query_lega);
                        fclose($fp);
                        db_ejecutar_instruccion($query_lega);
			$retorno="1";

		}else{
			$retorno="0";
		}
		return $retorno;
		//return $query;
	}

	function ingresar_foto_comparendo( 
		$descripcionViaPpal,
		$descripcionViaSec,
		$fechaHoraInfraccion,
		$foto1Url,
		$foto2Url,
		$idDeap,
		$latitud,
		$longitud,
		$lvCodigoInfraccionIdNum,
		$lvLocalidadId,
		$lvTipoViaId,
		$lvTipoViaId1,
		$numeroComparendo,
		$placa,
		$placaAgente,
		$fecha_foto1,
		$fecha_foto2

	){
		$consultas = new Consultas();

		$query  = "select par_descripcion from vis_param_0031 where cla_clase=75 and par_codigo=$lvTipoViaId";
		$dir1 =db_buscar_valor($query);
		$query2  = "select par_descripcion from vis_param_0031 where cla_clase=75 and par_codigo=$lvTipoViaId1";
		$dir2 =db_buscar_valor($query2);
		$direccion=trim($dir1). " ". trim($descripcionViaPpal).  " ". trim($dir2). " " .trim($descripcionViaSec);

		$date=date('Ymd');
		$time=date('His');
		$nombreFoto1="1_".$date."_".$time."_".$placa;
		$date=date('Ymd');
		$time=date('His');
		$nombreFoto2="2_".$date."_".$time."_".$placa;
//		$fecha=$date." ".$time;
		$consultas->poner_fecha($foto1Url,$fecha_foto1,$nombreFoto1,$direccion);
		$consultas->poner_fecha($foto2Url,$fecha_foto2,$nombreFoto2,$direccion);
		
		$foto3="fotos/".$nombreFoto1.".jpg";;
		$foto = fread(fopen($foto3, "r"), filesize($foto3));
		$foto1Url= base64_encode($foto);

		$foto3="fotos/".$nombreFoto2.".jpg";;
		$foto = fread(fopen($foto3, "r"), filesize($foto3));
		$foto2Url= base64_encode($foto);


//		$foto2Url=;
		//aca se llama el metodo de simur como cliente
//		$wsdl="http://172.16.7.180:7001/ServiciosSicon/ServiciosPort?WSDL";
//		$wsdl="http://172.16.7.185:7001/ServiciosSicon/ServiciosPort?wsdl";
		$wsdl="http://172.16.7.185:7001/Fotocomparendo/ServiciosPort?wsdl";
		
		$lvCodigoInfraccionIdNum="35";
		$client=new soapclient($wsdl, 'wsdl');

		$arg=array(

			'descripcionViaPpal'=>$descripcionViaPpal,                
			'descripcionViaSec'=>$descripcionViaSec,                 
			'fechaHoraInfraccion'=>$fechaHoraInfraccion,               
			'foto1Url'=>	$foto1Url,                          
			'foto2Url'=>	$foto2Url,                          
			'idDeap'=>	$idDeap,                           
			'latitud'=>	$latitud,                           
			'longitud'=>	$longitud,                          
			'lvCodigoInfraccionIdNum'=>$lvCodigoInfraccionIdNum,           
			'lvLocalidadId'=>	$lvLocalidadId,                     
			'lvTipoViaId'=>	$lvTipoViaId,                       
			'lvTipoViaId1'=>	$lvTipoViaId1,                      
			'nombreFoto1'=>	$nombreFoto1,                       
			'nombreFoto2'=>	$nombreFoto2,                      
			'numeroComparendo'=>$numeroComparendo,                  
			'placa'=>		$placa,                             
			'placaAgente'=>	$placaAgente                       
		
		); 
		$param=array('arg0'=>$arg);
		//print_r($param);
		// consumo del web service a traves de nusoap , el retorno se almacena en el vector $param
		//$retorno= $client->call('envioDEAP', $param);
		
		//print_r($retorno);
		/*
		$fp = fopen("log/".$date."_".$time.".txt", "a");
		$write = fputs($fp, $retorno['return']['foto1Url']);
		$write = fputs($fp, "\n");
		$write = fputs($fp, $retorno['return']['foto2Url']);
		fclose($fp);
		*/
		if($retorno['return']['foto1Url']!=""){
			$retornar="A";
		}else{
			$retornar="B";
		}


//		print_r($retorno);
//		return $retorno['return']['codigoRespuesta']."|".$retorno['return']['descripcionRespuesta'];

		//$retornar="A";
		return $retornar;


	}
	function consultar_RDA($placa){
		
		  error_reporting(0);
//		$wsdl="http://172.16.40.19:8080/serviciosWeb/ConsultaServiciosService?wsdl";
//		$wsdl="http://192.168.100.68:8080/serviciosWeb/ConsultaServiciosService?wsdl";
/*		
		$wsdl="http://192.168.201.30:8080/serviciosWeb/ConsultaServiciosService?wsdl";
		$client=new soapclient($wsdl, 'wsdl');
		$consultas = new Consultas();

		$param=array('arg0'=>$placa); 
//print_r($param);
		// consumo del web service a traves de nusoap , el retorno se almacena en el vector $param





*/
            $query_reporte="execute procedure  detalleVehiculo_placa('$placa')";
			$matriz_consulta = db_ejecutar_consulta($query_reporte);


			//print_r($matriz_consulta);

			$resultado="apellidosPropietario|categoriaLicenciaConducir|celularPropietario|ciudadDirResidenciaPropietario|clase|color|deptoDirResidenciaPropietario|dirResidenciaPropietario|emailPropietario|empresa|estadoTO|fechaLicenciaConducir|fechaLicenciaTransito|fechaNacePropietario|fechaTO|fechaVeceLicenciaConducir|fechaVenceTO|licenciaTransito|nombresPropietario|numLicenciaConducir|numeroDocPropietario|numeroTO|paisDirResidenciaPropietario|pasajeros|placa|radioAccion|runtModalidad|rutaServicioPub|secretariaAfiliacionVehiculo|servicio|statusResponse|telefonoPropietario|tipoDocPropietario|tonelada|zonaOperacion\n";




		if (is_array($matriz_consulta )){	

			//$datosVeh=print_r( $matriz_consulta ,true);
			//print_r($matriz_consulta);
			//cedula propietario         $matriz_consulta[0][3] $resi_prop $telefono_prop
			
			$nombre=$matriz_consulta[0][32];
			$nombre=explode(" ",$nombre);
			$nombre_propietario=$nombre[0]." ".$nombre[1];
			$apellido_propietario=$nombre[2]." ".$nombre[3];

			$resultado.=$apellido_propietario."||".trim($matriz_consulta[0][3])."||";
			$clase=$matriz_consulta[0][12];
			$clase_vector=explode("-",$clase);
			$color=$matriz_consulta[0][19];
			$color_vector=explode("-",$color);

			$resultado.=trim($clase_vector[0])."|".trim(str_replace("/","",$color_vector[0]))."||".$resi_prop."| |";

			$resultado.=$matriz_consulta[0][33]."|V| |".$matriz_consulta[0][8]."| |". $matriz_consulta[0][5]."|";

			$resultado.="|". $matriz_consulta[0][6]."|".trim($matriz_consulta[0][7])."|".$nombre_propietario."| |";

			$resultado.=  trim($matriz_consulta[0][3])."|". $matriz_consulta[0][4]."| |". $matriz_consulta[0][24]."|".$Placa_veh;

			$tipo_documento=$matriz_consulta[0][2];
			$tipo_documento_vector=explode("-",$tipo_documento);
			
			$tipo_servicio=trim($matriz_consulta[0][35]);
			$tipo_servicio_vector=explode("-",$tipo_servicio);
			
			$resultado.="| | | ||".$tipo_servicio_vector[0]."| |".$telefono_prop."|".$tipo_documento_vector[0] ."|".
						   $matriz_consulta[0][27]."|";
					//return "apellidosPropietario|categoriaLicenciaConducir|celularPropietario|ciudadDirResidenciaPropietario|clase|color|deptoDirResidenciaPropietario|dirResidenciaPropietario|emailPropietario|empresa|estadoTO|fechaLicenciaConducir|fechaLicenciaTransito|fechaNacePropietario|fechaTO|fechaVeceLicenciaConducir|fechaVenceTO|licenciaTransito|nombresPropietario|numLicenciaConducir|numeroDocPropietario|numeroTO|paisDirResidenciaPropietario|pasajeros|placa|radioAccion|runtModalidad|rutaServicioPub|secretariaAfiliacionVehiculo|servicio|statusResponse|telefonoPropietario|tipoDocPropietario|tonelada|zonaOperacion\nBARCENA FONTALVO|3|3008004573|BOGOTÁ D.C.|AUTOMOVIL|GRIS ALUMINIO MET|BOGOTÁ D.C.|CLL 159 # 20A-06||||04|06|00||00||10000987136|MARIA DEL PILAR|7050175|22669458||COLOMBIA|5|BWF629|No aplica|||Consorcio SIM-SDM|Particular|OK|6692175|Cédula Ciudadanía|0|";



		}
		else{
		
					$resultado.="||||||||||||||||||||||||||||||||||";

		}
		/*
		else{


			$vector= $client->call('consultarVehiculo', $param);
			//$vector="";
			  $resultado = $resultado . $vector["return"]["vehiculoRNA"]["propietario"]["apellidoUno"]. " ".$vector["return"]["vehiculoRNA"]["propietario"]["apellidoDos"]."||".$vector["return"]["vehiculoRNA"]["propietario"]["celular"]."|".$vector["return"]["vehiculoRNA"]["propietario"]["ciudad"]."|".$vector["return"]["vehiculoRNA"]["clase_veh"]."|".$vector["return"]["vehiculoRNA"]["color"]."|".$vector["return"]["vehiculoRNA"]["propietario"]["depto"]."|".$vector["return"]["vehiculoRNA"]["propietario"]["direccion"]."|".$vector["return"]["vehiculoRNA"]["propietario"]["email"]."|".$vector["return"]["vehiculoRNA"]["empresa"]."|".$vector["return"]["vehiculoRNA"]["estadoto"]."|".$vector["return"]["vehiculoRNA"]["propietario"]["fecha_licencia"]."|".$vector["return"]["vehiculoRNA"]["fecha_matricula"]."|".$vector["return"]["vehiculoRNA"]["propietario"]["fecha_nace"]."|".$vector["return"]["vehiculoRNA"]["fecha_to"]."|".$vector["return"]["vehiculoRNA"]["propietario"]["fecha_vence_licencia"]."|".$vector["return"]["vehiculoRNA"]["fecha_vence_to"]."|".$vector["return"]["vehiculoRNA"]["licenciatr"]."|".$vector["return"]["vehiculoRNA"]["propietario"]["nombreUno"]." ".$vector["return"]["vehiculoRNA"]["propietario"]["nombreDos"]."|".$vector["return"]["vehiculoRNA"]["propietario"]["numlicencia"]."|".$vector["return"]["vehiculoRNA"]["propietario"]["numeroDocumento"]."|".$vector["return"]["vehiculoRNA"]["numeroto"]."|".$vector["return"]["vehiculoRNA"]["propietario"]["pais"]."|".$vector["return"]["vehiculoRNA"]["pasajeros_sentados"]."|".$vector["return"]["vehiculoRNA"]["placa"]."|".$vector["return"]["vehiculoRNA"]["radio_accion"]."|".$vector["return"]["vehiculoRNA"]["runt_modalidad"]."|".$vector["return"]["vehiculoRNA"]["ruta"]."|".$vector["return"]["vehiculoRNA"]["secafiliacion"]."|".$vector["return"]["vehiculoRNA"]["tipo_servicio"]."||".str_replace("-","",$vector["return"]["vehiculoRNA"]["propietario"]["telefono"])."|".$vector["return"]["vehiculoRNA"]["propietario"]["tipoDocumento"]."|".$vector["return"]["vehiculoRNA"]["capacidad_carga"]."|".$vector["return"]["vehiculoRNA"]["zona_operacion"];


		
			$query_reporte="execute procedure bas_bogota@serlab_tcp:detalleVehiculo_placa('$placa')";
			$matriz_consulta = db_ejecutar_consulta($query_reporte);
			//$datosVeh=print_r( $matriz_consulta ,true);
			//print_r($matriz_consulta);
			//cedula propietario         $matriz_consulta[0][3] $resi_prop $telefono_prop
			
			$nombre=$matriz_consulta[0][32];
			$nombre=explode(" ",$nombre);
			$nombre_propietario=$nombre[0]." ".$nombre[1];
			$apellido_propietario=$nombre[2]." ".$nombre[3];

			$resultado.=$apellido_propietario."||".trim($matriz_consulta[0][3])."||";
			$clase=$matriz_consulta[0][12];
			$clase_vector=explode("-",$clase);
			$color=$matriz_consulta[0][19];
			$color_vector=explode("-",$color);

			$resultado.=trim($clase_vector[0])."|".trim(str_replace("/","",$color_vector[0]))."||".$resi_prop."| |";

			$resultado.=$matriz_consulta[0][33]."|V| |".$matriz_consulta[0][8]."| |". $matriz_consulta[0][5]."|";

			$resultado.="|". $matriz_consulta[0][6]."|".trim($matriz_consulta[0][7])."|".$nombre_propietario."| |";

			$resultado.=  trim($matriz_consulta[0][3])."|". $matriz_consulta[0][4]."| |". $matriz_consulta[0][24]."|".$Placa_veh;

			$tipo_documento=$matriz_consulta[0][2];
			$tipo_documento_vector=explode("-",$tipo_documento);
			
			$tipo_servicio=trim($matriz_consulta[0][35]);
			$tipo_servicio_vector=explode("-",$tipo_servicio);
			
			$resultado.="| | | |11001|".$tipo_servicio_vector[0]."| |".$telefono_prop."|".$tipo_documento_vector[0] ."|".
						   $matriz_consulta[0][27]."|";
				
					
		
		}
	*/	
		
		
		//
  			return $resultado;
	}
}

?>
