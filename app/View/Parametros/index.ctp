<div class="page-header" style = "text-align: center;">
	<h1>Parametrización <br></h1>
</div>
<div>
<?php
	echo $this->Html->link(
		 $this->Html->image('listAddContacts.png' , array('width' => '15px')).'   Agregar Nuevo Día para solicitudes',
		 array(
			 'controller' => 'parametros',
			 'action' => 'add_dia'
		 ),array(
			'escape'=>false,
			'style' => 'margin-left: 12px; font-size: 15px;'
		)
	). '<br><br>';
?>

<table class="table table-hover user_table">
		<tr>
			<th>
			Día
			</th>
			<th>
			Hora inicio
			</th>
			<th>
			Hora fin
			</th>
			<th style = "text-align: center;">
				<?php echo 'Acciones'; ?>
			</th>
		</tr>	
<?php foreach ($dias as $d) { ?>
		<tr>
			<td>
				<?php 
				if ($d['Dia']['dia'] == 'Mon') {
					$d['Dia']['dia'] = 'Lunes';
				}
				if ($d['Dia']['dia'] == 'Tue') {
					$d['Dia']['dia'] = 'Martes';
				}
				if ($d['Dia']['dia'] == 'Wed') {
					$d['Dia']['dia'] = 'Miercoles';
				}
				if ($d['Dia']['dia'] == 'Thu') {
					$d['Dia']['dia'] = 'Jueves';
				}
				if ($d['Dia']['dia'] == 'Fri') {
					$d['Dia']['dia'] = 'Viernes';
				}
				if ($d['Dia']['dia'] == 'Sat') {
					$d['Dia']['dia'] = 'Sabado';
				}
				if ($d['Dia']['dia'] == 'Sun') {
					$d['Dia']['dia'] = 'Domingo';
				}
				echo $d['Dia']['dia']; ?>
			</td>
			<td>
				<?php echo $d['Dia']['hora_inicio']; ?>
			</td>
			<td>
				<?php echo $d['Dia']['hora_fin']; ?>
			</td>
			<td style = "text-align: center;">
				<?php
					echo $this->Html->link(
							$this->Html->image('delete-num16x16.jpg', array('width' => '15px', 'class' => 'tooltip', 'title' => 'Eliminar Día')) . '  ',
							array(
							'controller' => 'parametros',
							'action' => 'delete_dia',
							$d['Dia']['id']
							),array('escape'=>false)
						);
				?>
			</td>
		</tr>
<?php } ?>
</table>
<div class = "row login grid_8 ">
	<fieldset>
		<legend> Parámetros </legend>
	<br><br>
		<?php 
			echo $this->Form->create('Parametro', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
		?>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('max_bolsas',array(
							'label' => 'Cantidad máxima de bolsas por persona',
							'class' => 'form-control',
							'value' => $parametros['Parametro']['max_bolsas']
						));
					?>
				</div>
			  </div>
				<div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('dias',array(
							'label' => 'Cantidad de días entre pedidos',
							'class' => 'form-control',
							'value' => $parametros['Parametro']['dias'],
							'type' => 'text'
						));
						echo $this->Form->input('id',array(
							'label' => false,
							'class' => 'form-control',
							'value' => $parametros['Parametro']['id'],
							'type' => 'hidden'
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-1 col-sm-10 text-center">
				 <?php 
					echo $this->Form->submit(__('Cambiar parámetros'), array('class' => 'btn btn-success btn-block'));
					echo $this->Form->end;
				 ?>
				</div>
			  </div>
		</fieldset>
	</div>
