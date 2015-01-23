<?php 
echo $this->Form->input('cantidad_ajustar',array(
	'label' => 'Cantidad a asignar',
	'id' => 'input_cantidad_ajuste'
));
echo $this->Form->button('Guardar',array(
	'type' => 'button',
	'id' => 'boton_ajuste'
));
?> 
<script>
	$('#boton_ajuste').click(function(){	
		cantidad = $('#input_cantidad_ajuste').val();
		if (parseInt(menor_cantidad) < parseInt(cantidad)) {
			alert('El valor a asignar debe ser menor o igual a '+ menor_cantidad);
		} else {
			var cantidad_total_asignada = 0;
			$('input[type=checkbox].solicitud').each(function () {
				if (this.checked) {
					$('#cantidad_aceptada_'+$(this).attr('id')).val(cantidad);
					cantidad_total_asignada = parseInt(cantidad_total_asignada) + parseInt(cantidad);
				}
			});
			$('#PedidoBolsasTotal').val(cantidad_total_asignada);
			$('#cantidad_total_asignada').html(cantidad_total_asignada);
			cantidad_total_asignada = 0;
			 $('#ajustar_solicitudes').dialog( "close" );
		}
	});
</script>