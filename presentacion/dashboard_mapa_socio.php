<?php
/*
 * ANDRES FELIPE BARRERA VELASQUEZ
 * PRUEBAS CON GOOGLE MAPS 1
 * */
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Google Maps Example</title>
    <script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="../js/jquery.tablesorter.min.js"></script>
	<script type="text/javascript" src="../js/header.js"></script>
</head>
<body>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&amp;language=es"></script>
<script type="text/javascript">
	//POSICIONES QUEMADA DE LATITUD X Y LONGITUD Y COORDENADAS EN BOGOTA - COLOMBIA
	//RUTA - ARREGLO QUE CONTIENE LAS POSICIONES DE LO RECAUDOS LATITU, LONGITUD
	var ruta = [
	<?php
		if ($RowCount=="0")
		{
			$detalle['latitud'][0] = 4.5980556;
			$detalle['longitud'][0] = -74.0758333;
	?>
			new google.maps.LatLng(<?php echo $detalle['latitud'][0]?>, <?php echo $detalle['longitud'][0]?>),
	<?php
		}
		else
		{
			for($i=0;$i<$RowCount;$i++)
	 		{
	 		?>	          
	   		new google.maps.LatLng(<?php echo $detalle['latitud'][$i]?>, <?php echo $detalle['longitud'][$i]?>), 
			<?php
			}
		}
		?>        
	];
	//FABRICA LAS POSICIONES EN EL MAPA ARREGLO
	var fabricante = [];
	// ITERATOR CONTADOR PARA PONER UNO POR UNO LOS PUNTOS EN EL MAPA SEGUN EL ORDEN
	var contador = 0;
	//RECORREMOS EL ARREGLO Y ASIGNAMOS LOS NOMBRES A CADA VALOR
	var vendedor = "Algo";
	<?php
	if($RowCount=="0")
	{
		$RowCount++;
		?>
		var vendedor0 = "No hay datos para graficar";
		<?php
	}
	else
	{
		for($i=0;$i<$RowCount;$i++)
		{
			/*
			 * ASIGNAMOS A LA VARIABLE VENDEDOR EL NOMBRE DEL REGISTRO A GRAFICAR
			 * */
		?>
			var vendedor<?php echo $i?> = "<?php echo $detalle['nombre'][$i];?>";
		<?php
		}
	}
	?>
						
	//SE CREA LA VARIABLE QUE VA A ALMACENAR EL MAPA Y SUS PROPIEDADES
	var map;
	//PARAMETROS QUE INICIAN CON EL BODY
	function initialize()
	{
	   //UBICACION Y PARAMETROS DE LA VISTA GENERAL DEL MAPA
	    var myOptions = {
	      center: new google.maps.LatLng(<?php echo $detalle['latitud'][$i/2]?>, <?php echo $detalle['longitud'][$i/2]?>),
	      zoom: 12,
	      mapTypeId: google.maps.MapTypeId.TERRAIN,
	      mapTypeControl: true,
	      mapTypeControlOptions: {
	      style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
	      },
	    };
	    //SE LE ASIGNAN LAS OPCIONES AL MAPA Y SE ENVIA A UN DIV EL MAPA ELABORADO
	    map = new google.maps.Map(document.getElementById("mostrar_mapa_solo"), myOptions);
	  //VARIABLE PARA EL TRAZADO DE LA LINEA DE LA RUTA  
		  var lineas = new google.maps.Polyline({
		       path: ruta,
		       map: map,
		       strokeColor: '#E86100',
		       strokeWeight: 4,
		       strokeOpacity: 0.75,
		       clickable: true
		  });
	}
	
	//FUNCION QUE MANEJA LO TIEMPO DE INSTRODUCCION DE PUNTO DE RECAUDO POR ORDEN
	  function drop() {
	      for (var i = 0; i < ruta.length; i++) {
	        setTimeout(function() {
	          addMarker();
	        }, i * 600);
	      }
	    }
	//FUNCION QUE ELABORA LOS PUNTOS DE RECAUDO EN EL MAPA
	<?php
	for ($j=0;$j<$RowCount;$j++)
	{ 
	?>
	  function addMarker() {
		  fabricante.push(new google.maps.Marker({
	        position: ruta[contador],
	        map: map,
	        draggable: false,
	        animation: google.maps.Animation.DROP,
	        title: ""+vendedor<?php echo $j;?>+""
	      }));
		  contador++;
	    }
	 <?php 
	 }
	 ?>
	
</script>
<div id="mostrar_mapa_solo" style="width: 100%; height: 100%;">
	<script>
		initialize();
		drop();
	</script>
</div>
</body>
</html>