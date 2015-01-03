<div class="page-header" style = "text-align: center;">
	<h1>Usuarios<br></h1>
</div>
<div>
<?php
	echo $this->Html->link(
		 $this->Html->image('listAddContacts.png' , array('width' => '15px')).'   Agregar Nuevo Usuario',
		 array(
			 'controller' => 'usuarios',
			 'action' => 'add'
		 ),array(
			'escape'=>false,
			'style' => 'margin-left: 12px; font-size: 15px;'
		)
	). '<br><br>';
?>

<table class="table table-hover user_table">
		<tr>
			<th>
			Nombre de usuario
			</th>
			<th>
			Nombre y Apellido
			</th>
			<th>
			Correo
			</th>
			<th style = "text-align: center;">
				<?php echo 'Acciones'; ?>
			</th>
		</tr>
<?php
	foreach($users as $u){ ?>
		
		<tr>
			<td>
				<?php echo $u['User']['username']; ?>
			</td>
			<td>
				<?php echo $u['User']['nombre_completo']; ?>
			</td>
			<td>
				<?php echo $u['User']['correo']; ?>
			</td>
			<td style = "text-align: center;">
				<?php
					echo $this->Html->link(
						$this->Html->image('listEdit.png', array('width' => '15px', 'class' => 'tooltip', 'title' => 'Editar Fecha')) . '  ',
						array(
						 'controller' => 'usuarios',
						 'action' => 'edit',
						 $u['User']['id']
						),array('escape'=>false)
					);
				echo ' ';
					if($id != $u['User']['id']) {
						echo $this->Html->link(
							$this->Html->image('delete-num16x16.jpg', array('width' => '15px', 'class' => 'tooltip', 'title' => 'Eliminar Fecha')) . '  ',
							array(
							'controller' => 'usuarios',
							'action' => 'delete',
							$u['User']['id']
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