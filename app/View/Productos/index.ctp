
<div class="page-header" style = "text-align: center;">
	<h2><?php echo __('Productos Registrados'); ?></h2>
	<?php if(empty($productos)): ?>	
	<p>Usted no posee Productos Asociados. Puede agregar un producto haciendo click <?php echo $this->Html->link('aquí', array('action' => 'add')); ?></p>	
<?php else: ?>
</div>

<div>
<div>
	<?php
	echo $this->Html->link(
		 $this->Html->image('listAddContacts.png' , array('width' => '15px')).'   Agregar producto nuevo',
		 array(
			 'controller' => 'productos',
			 'action' => 'add'
		 ),array('title'=>'Agregar producto',
			'escape'=>false,
			'style' => 'margin-left: 15px; font-size: 12px;'
		)
	). '<br><br>';
?>
	<table table table-hover user_table">

		<tr>
			<th><?php echo $this->Paginator->sort('producto','Producto'); ?></th>
			<th><?php echo $this->Paginator->sort('descripcion','Descripcion'); ?></th>
			<th><?php echo $this->Paginator->sort('status','Estado'); ?></th>
			<th class="actions"><?php echo __('Herramientas'); ?></th>
	</tr>
	<?php foreach ($productos as $producto): ?>
	<tr>
	
		<td><?php echo $producto['Producto']['producto']; ?>&nbsp;</td>
		<td><?php echo $producto['Producto']['descripcion']; ?>&nbsp;</td>
		<td><?php echo $producto['Producto']['status']; ?>&nbsp;</td>
		
		<td class="actions">
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->Html->link('<img src="'.$this->webroot.'img/listEdit.png" />', array('action' => 'edit', $producto['Producto']['id']),array('escape'=>false,'class' => 'tooltip','title'=>'Editar')); ?>&nbsp;&nbsp;
				<?php 
						$img = ($producto['Producto']['status']!='activo')?$this->webroot.'img/activate.png':$this->webroot.'img/deactivate.png';
						$msg = ($producto['Producto']['status']=='activo')?"Desea desactivar producti?":"Desea activar este producto";
						$title = ($producto['Producto']['status']!='activo')?'Activar':'Desactivar';
						echo $this->Form->postLink('<img src="'.$img.'" />', array('action' => 'activar', $producto['Producto']['id']),array('escape'=>false,'class' => 'tooltip','title'=>$title), __($msg));
				?>
			</td>
			
	</tr>
	<?php endforeach; ?>
	</table>
	<p>
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
		echo $this->Paginator->prev('< ' . __('Antetior'), array(), null, array('class' => 'prev disabled'));
		echo  ' ';
		echo $this->Paginator->numbers(array('separator' => ' '));
		echo  ' ';
		echo $this->Paginator->next(__('Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
<?php endif; ?>
</div>



