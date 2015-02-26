<?php
/*
 * ANDRES FELIPE BARRERA VELASQUEZ
 * PRUEBAS CON GOOGLE MAPS 1
 * */
if($msg !="")
{
	?>
	<script>
		alert('<?php echo $msg;?>');
	</script>
	<?php
}
?>
<style type="text/css">
  html { height: 100% }
  body { height: 100%; margin: 0px; padding: 0px }
  #mostrar_mapa { 
  	width:60%; 
  	height:80%;
  	align:center; 
  }
</style>
<script type="text/javascript">
	//POSICIONES QUEMADA DE LATITUD X Y LONGITUD Y COORDENADAS EN BOGOTA - COLOMBIA
	//RUTA - ARREGLO QUE CONTIENE LAS POSICIONES DE LO RECAUDOS LATITU, LONGITUD
	var ruta = [
	<?php
	 	for($i=0;$i<$RowCount;$i++)
	 	{
	 	?>	          
	   		new google.maps.LatLng(<?php echo $detalle['latitud'][$i]?>, <?php echo $detalle['longitud'][$i]?>), 
		<?php
		}
		?>            
	];
	//FABRICA LAS POSICIONES EN EL MAPA ARREGLO
	var fabricante = [];
	// ITERATOR CONTADOR PARA PONER UNO POR UNO LOS PUNTOS EN EL MAPA SEGUN EL ORDEN
	var contador = 0;
	var vendedor = "<?php echo $RowCount?>";
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
	    map = new google.maps.Map(document.getElementById("mostrar_mapa"), myOptions);
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
	  function addMarker() {
		  fabricante.push(new google.maps.Marker({
	        position: ruta[contador],
	        map: map,
	        draggable: false,
	        animation: google.maps.Animation.DROP,
	        title: ""+vendedor+""
	      }));
		  contador++;
	    }
	  initialize();
	  drop();
	  $("#mostrar_mapa").css("display", "block");
</script>
<br>
