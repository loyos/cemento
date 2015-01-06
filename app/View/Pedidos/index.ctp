<div class="page-header" style = "text-align: center;">
	<h1>Consulta de solicitudes<br></h1>
</div>
<div>
<div class = "search_box" style= "margin: 1%;">
	<?php echo $this->element('pedidos_search'); ?>
</div>

<table class="table table-hover user_table" style="border-top:none;border-bottom:none;border-left:none;border-right:1px solid #bbb">
		<tr>
			<th style = "border-left:1px solid #bbb;">
			<?php 
			echo $this->Form->create('Pedido',array('url' => array(
				'controller' => 'pedidos',
				'action' => 'entregar'
				)
			));
			echo $this->Form->input('paso',array(
				'name' => 'paso',
				'type' => 'hidden',
				'label' => false,
				'value' => '1'
			));
			echo $this->Form->input('seleccionar_todos',array(
				'name' => 'seleccionar_todos',
				'type' => 'checkbox',
				'label' => false,
				'style' => 'float:left',
				'id' => 'seleccionar_todos'
			));
			echo $this->Paginator->sort('Usuario.nombre','Nombre'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('Usuario.correo','Correo'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('Usuario.direccion','Dirección'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('Pedido.fecha','Fecha de solicitud'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('Entrega.fecha','Fecha de entrega'); ?>
			</th>
			<th>Cantidad solicitada</th>
			<th>Cantidad restante</th>
		</tr>
<?php
	foreach($pedidos as $p){ ?>
		
		<tr>
			<td  style = "border-left:1px solid #bbb;">
				<?php 
				echo $this->Form->input('seleccionar',array(
					'name' => 'solicitud['.$p['Pedido']['id'].']',
					'type' => 'checkbox',
					'label' => false,
					'style' => 'float:left',
					'class' => 'solicitud',
					'id' => $p['Pedido']['cantidad']
				));
				echo $p['Usuario']['nombre']; ?>
			</td>
			<td>
				<?php echo $p['Usuario']['correo']; ?>
			</td>
			<td>
				<?php echo $p['Usuario']['direccion']; ?>
			</td>
			<td>
				<?php 
				$fecha = explode(' ',$p['Pedido']['fecha']);
				$date = date_create($fecha[0]);
				echo date_format($date, 'd-m-Y'); ?>
			</td>
			<td>
				<?php 
				if ($p['Pedido']['fecha_entrega'] == '0000-00-00 00:00:00') {
					$p['Pedido']['fecha_entrega'] = 'Sin fecha definida';
				} else {
					$fecha = explode(' ',$p['Pedido']['fecha_entrega']);
					$date = date_create($fecha[0]);
					$p['Pedido']['fecha_entrega'] = date_format($date, 'd-m-Y');
				}
				echo $p['Pedido']['fecha_entrega'];
				?>
			</td>
			<td>
				<?php echo $p['Pedido']['cantidad']; ?>
			</td>
			<td>
				<?php echo $cantidad_restante[$p['Usuario']['id']]; ?>
			</td>
		</tr>
<?php
	}
?>
	<tr>
		<td style="background:none;border-top:none;border-bottom:none"></td><td style="background:none;border-top:none;border-bottom:none"></td><td style="background:none;border-top:none;border-bottom:none"></td><td style="background:none;border-top:none;border-bottom:none"></td><td style="background:none;border-top:none;border-bottom:none"></td>
		<td style = "border-left:1px solid #bbb;border-right:1px solid #bbb">
			<div id="cantidad_total">
				0
			</div>
			<?php 
			echo $this->Form->input('bolsas_total',array(
				'type' => 'hidden',
				'value' => 0
			));
			?>
		</td>
	</tr>
</table>
<div class="listas index grid_12" style="width:68%">
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página {:page} de {:pages}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('Anterior'), array(), null, array('class' => 'prev disabled'));
		echo  ' ';
		echo $this->Paginator->numbers(array('separator' => ' '));
		echo  ' ';
		echo $this->Paginator->next(__('Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
	</div>
<?php 
echo $this->Form->submit('Aceptar Solicitudes');
echo $this->Form->end();
 ?>
<script>
	$(document).ready(function () {
		var cantidad_total = 0;
		$("#seleccionar_todos").change(function () {
			var cantidad_total = 0;
			if ($(this).is(':checked')) {
				$("input[type=checkbox].solicitud").prop('checked', true); //todos los check
				$('input[type=checkbox].solicitud').each(function () {
					if (this.checked) {
						cantidad_total = parseInt(cantidad_total) + parseInt($(this).attr('id'));
						$('#cantidad_total').html(cantidad_total);
						$('#PedidoBolsasTotal').val(cantidad_total);
					}
				});
				cantidad_total = 0;
			} else {
				$("input[type=checkbox].solicitud").prop('checked', false); //todos los check
				$('#cantidad_total').html(cantidad_total);
				$('#PedidoBolsasTotal').val(cantidad_total);
				cantidad_total = 0;
			}
		});
		
		$("input[type=checkbox].solicitud").change(function () {
			cantidad_total = 0;
			$('input[type=checkbox].solicitud').each(function () {
				if (this.checked) {
					cantidad_total = parseInt(cantidad_total) + parseInt($(this).attr('id'));
					$('#cantidad_total').html(cantidad_total);
					$('#PedidoBolsasTotal').val(cantidad_total);
				}
			});
		});
	});
</script>