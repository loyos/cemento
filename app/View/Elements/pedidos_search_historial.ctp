<fieldset>
	<legend> Búsqueda </legend>
<?php
		echo $this->Form->create('Pedido', array(
			'url' => array_merge(array('controller'=>'pedidos','action' => 'historial'), $this->params['pass'])
		));
		
		echo $this->Form->input('status', array('label' => 'Estatus de las solicitudes ','required' => false,
			'options' => array(
				'0' => 'Todas',
				'1' => 'Solicitudes pendientes',
				'2' => 'Solicitudes asignadas',
			    '3' => 'Solicitudes rechazadas'
			)
		));
		echo $this->Form->input('ced', array('label' => 'Cédula ','required' => false));
		echo $this->Form->input('nombre_completo', array('label' => 'Nombre ','required' => false));
		echo $this->Form->input('fecha_inicio', array('label' => 'Rango de fecha del:  ', 'empty' => 'Todas', 'type' => 'date', 'maxYear' => '2021'));
		echo $this->Form->input('fecha_fin', array('label' => 'al:  ', 'empty' => 'Todas' , 'type' => 'date', 'maxYear' => '2021'));		
		echo $this->Form->submit(__('Buscar'), array('div' => 'search_button', 'class' => 'boton_busqueda'));
		echo $this->Form->end();
?>
</fieldset>
<script>
//reglas para el masked input
	    $.mask.definitions['O'] = "[VEve]";
		$("#PedidoCed").mask("O-999999?99");
</script>