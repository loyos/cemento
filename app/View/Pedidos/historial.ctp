<div class="page-header" style = "text-align: center;">
	<h1>Consulta del historial de solicitudes<br></h1>
</div>
<div class="printer tooltip" style="float:left;margin-top:208px;margin-left:13px;margin-bottom:5px" title="Imprimir Solicitudes">
	<?php echo $this->Html->image('printer.png',array('width' => '25px'));?>
</div>
<div class = "search_box" style= "margin: 1%;">
	<?php echo $this->element('pedidos_search_historial'); ?>
</div>
	<div style="float:left; color:blue; width:169px; margin-left:707px">
		<div style="float:left">Total de sacos solicitados:</div>
		<div id="cantidad_total" style="color:blue;">
			<?php echo $count_bolsas ?>
		</div>
	</div>
	<div style="float:right; color:blue; width:169px;margin-right:144px">
		<div style="float:left">Total de sacos asignados:</div> 
		<div id="cantidad_total" style="color:blue">
			<?php echo $count_bolsas_asignadas ?>
		</div>
	</div>
<table class="table table-hover user_table" style="width: 98%;border: 1px solid #bbb;margin-bottom: 10px;margin: 1%;">
		<tr>
			<th style="border-top: 1px solid #bbb;background: #eee;">
			<?php echo 'Cédula'; ?>
			</th>
			<th style="border-top: 1px solid #bbb;background: #eee;">
			<?php echo 'Nombre'; ?>
			</th>
			<th style="border-top: 1px solid #bbb;background: #eee;max-width:152px">
			<?php echo 'Dirección'; ?>
			</th>
			<th style="border-top: 1px solid #bbb;background: #eee;">
			<?php echo 'Fecha de solicitud'; ?>
			</th>
			<th style="border-top: 1px solid #bbb;background: #eee;">
			<?php echo 'Fecha de entrega'; ?>
			</th>
			<th style="border-top: 1px solid #bbb;background: #eee;">Cantidad solicitada</th>
			<th style="border-top: 1px solid #bbb;background: #eee;">Cantidad asignada</th>
			<th style = "text-align: center;border-top: 1px solid #bbb;background: #eee;">
				<?php echo 'Acciones'; ?>
			</th>
		</tr>
<?php
	foreach($pedidos as $p){ ?>
		
		<tr>
			<td style="border-top: 1px solid #bbb;background: #eee;">
				<?php echo $p['Usuario']['cedula']; ?>
			</td>
			<td style="border-top: 1px solid #bbb;background: #eee;">
				<?php echo $p['Usuario']['nombre']; ?>
			</td>
			<td style="max-width:102px">
				<?php echo $p['Usuario']['parroquia'].' '.$p['Usuario']['direccion']; ?>
			</td>
			<td style="border-top: 1px solid #bbb;background: #eee;">
				<?php 
				$fecha = explode(' ',$p['Pedido']['fecha']);
				$date = date_create($fecha[0]);
				echo date_format($date, 'd-m-Y'); ?>
			</td>
			<td style="border-top: 1px solid #bbb;background: #eee;">
				<?php 
				if ($p['Pedido']['fecha_entrega'] == '0000-00-00 00:00:00' && $p['Pedido']['abierto'] == 1) {
					$p['Pedido']['fecha_entrega'] = 'Sin fecha definida';
				} elseif ($p['Pedido']['fecha_entrega'] == '0000-00-00 00:00:00' && $p['Pedido']['abierto'] == 0) {
					$p['Pedido']['fecha_entrega'] = 'Rechazada';
					$rechaza = true;
				} 
				if ($p['Pedido']['fecha_entrega'] == 'Rechazada') {
					echo '<span style="color:red">'.$p['Pedido']['fecha_entrega'].'</span>';
				} else {
					echo $p['Pedido']['fecha_entrega'];
				}
				?>
			</td>
			<td style="border-top: 1px solid #bbb;background: #eee;">
				<?php echo $p['Pedido']['cantidad']; ?>
			</td>
			<td style="border-top: 1px solid #bbb;background: #eee;">
				<?php 
				if (!empty($rechazada)) {
					echo '0';
				}else{
					echo $p['Pedido']['num_bolsas_asignadas'];
				}
				?>
			</td>
			<td style = "text-align: center;border-top: 1px solid #bbb;background: #eee;">
				<?php
					if ($p['Pedido']['abierto'] == 0) {
						echo $this->Html->link(
							$this->Html->image('delete-num16x16.jpg', array('width' => '15px', 'class' => 'tooltip', 'title' => 'Eliminar Solicitud')) . '  ',
							array(
							'controller' => 'pedidos',
							'action' => 'delete',
							$p['Pedido']['id']
							),array('escape'=>false)
						);
					}
				?>
			</td>
		</tr>
		
<?php
	}
?>

</table>
<div class="imprimir_oculto" style="display:none">
	<div class="imprimir">	
		<?php echo $this->element('historial')?>
	</div>
</div>
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
	
<script>
	var options = { mode : 'iframe', popClose : true }; // keep this settings in order to avoid pop up window
	
    $("div.printer").click(function(){ 
		$("div.imprimir").printArea( [options] ); // just put the area to be printed and that's it! 
	});												// also with this settings, user can save the file as pdf instead of print it.
</script>