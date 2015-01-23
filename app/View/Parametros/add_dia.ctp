<div class = " grid_16 ">
<div class = "grid_4">&nbsp;</div>
<div class = "row login grid_8 ">
	<fieldset>
		<legend> Agregar Dia y hora para generar solicitudes</legend>
		<?php 
			echo $this->Form->create('Dia', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
			if (!empty($id)) {
				echo $this->Form->input('id',array(
					'label' => false,
					'class' => 'form-control',
					'type' => 'hidden',
					'value' => $id
				));
			}
		?>
			
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1 ">
				  <?php
					echo $this->Form->input('dia',array(
						'label' => 'Dia *',
						'class' => 'form-control',
						'type' => 'select',
						'options' => $options
					));
				  ?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('hora_inicio',array(
							'label' => 'Hora de inicio *',
							'class' => 'form-control',
							// 'type' => 'text',
						));
					?>
				</div>
			  </div>
			<div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('hora_fin',array(
							'label' => 'Hora final *',
							'class' => 'form-control',
							// 'type' => 'text',
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-1 col-sm-10 text-center">
				 <?php 
					echo $this->Form->submit(__('Guardar'), array('class' => 'btn btn-success btn-block'));
					echo $this->Form->end;
				 ?>
				</div>
			  </div>
		</fieldset>
	</div>
</div>