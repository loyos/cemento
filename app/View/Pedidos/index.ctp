<div class="page-header" style = "text-align: center;">
	<h1>Consulta de solicitudes<br></h1>
</div>
<div>
<div class = "search_box" style= "margin: 1%;">
	<?php echo $this->element('pedidos_search'); ?>
</div>

<table class="table table-hover user_table">
		<tr>
			<th>
			<?php echo $this->Paginator->sort('Usuario.nombre','Nombre'); ?>
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
			<th>Cantidad restante</th>
			<th>Cantidad solicitada</th>
			<th style = "text-align: center;">
				<?php echo 'Acciones'; ?>
			</th>
		</tr>
<?php
	foreach($pedidos as $p){ ?>
		
		<tr>
			<td>
				<?php echo $p['Usuario']['nombre']; ?>
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
				<?php echo $cantidad_restante[$p['Usuario']['id']]; ?>
			</td>
			<td>
				<?php echo $p['Pedido']['cantidad']; ?>
			</td>
			<td style = "text-align: center;">
				<?php
					if ($p['Pedido']['abierto'] == 1) {
						echo $this->Html->link(
							$this->Html->image('activate.png', array('width' => '15px', 'class' => 'tooltip', 'title' => 'Aceptar solicitud')) . '  ',
							array(
							 'controller' => 'pedidos',
							 'action' => 'entregar',
							 $p['Pedido']['id']
							),array('escape'=>false)
						);
					}
				// echo ' ';
						// echo $this->Html->link(
							// $this->Html->image('delete-num16x16.jpg', array('width' => '15px', 'class' => 'tooltip', 'title' => 'Eliminar Solicitud')) . '  ',
							// array(
							// 'controller' => 'dias',
							// 'action' => 'delete',
							// $p['Pedido']['id']
							// ),array('escape'=>false)
						// );
				?>
			</td>
		</tr>
		
<?php
	}
?>

</table>

<div class="listas index grid_12">
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