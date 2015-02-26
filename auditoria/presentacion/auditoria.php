<?php include '../logica/variables_session.php'?>
<script type="text/javascript" src="../js/jquery-1.8.2.js"></script>
<div id="boton" style="cursor:pointer;" onclick="AjaxConsulta('../logica/auditoria.php',{BASE_DE_DATOS:'<?php echo $DATABASE_NAME ?>', ACCION:'construir_auditoria'},'mostrar_auditoria');"><strong>Crear auditoria...</strong></div>
<div id="mostrar_auditoria"></div>
