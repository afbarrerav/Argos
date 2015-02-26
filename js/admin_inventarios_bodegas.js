function ConsultarInventario()
{
	var sbodega = document.getElementById('sbodega').value;
	if (sbodega!="0")
	{
		AjaxConsulta('../logica/admin_inventarios_bodegas.php', {sbodega:sbodega, ACCION:'listar'}, 'inventarioDiv');		
	}
	else
	{
		if(confirm("Haga clic en ACEPTAR si desea consulta el inventario total"))
		{
			AjaxConsulta('../logica/admin_inventarios_bodegas.php', {sbodega:'0', ACCION:'listar'}, 'inventarioDiv');
		}
	}	
}

function guardar(form)
{
	var sbodega1 	= document.getElementById('sbodega1').value;
	var sproducto 	= document.getElementById('sproducto').value;
	var cantidad 	= document.getElementById('cantidad').value;	
	if (sbodega1 > 0)
	{
		if (sproducto > 0)
		{
			if ((!isNaN(cantidad)) && (cantidad > 0))
			{
				AjaxConsulta('../logica/admin_inventarios_bodegas.php', {sbodega1:sbodega1, sproducto:sproducto, cantidad:cantidad, ACCION:'agregar_producto_inventario'}, 'inventarioDiv');
			}
			else
			{
				alert("El valor introducido en el campo cantidad debe ser un numero mayor a 0");
			}
		}
		else
		{
			alert("Debe seleccionar un producto");
		}
	}
	else
	{
		alert("Debe seleccionar una bodega");
	}
}