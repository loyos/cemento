	<div class = "grid_4">&nbsp;</div>
	<div class = "row login grid_8 ">
	
		<fieldset>
		<legend> Editar Producto </legend>
	<?php 
		echo $this->Form->create('Producto', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
	?>   
			(*) Campos obligatorios <br><br>
		  <div class="form-group">
			<div class="col-sm-10 col-md-offset-1 ">
			  <?php
				echo $this->Form->input('producto',array(
					'label' => ' Producto *',
					'placeholder' => 'Producto',
					'class' => 'form-control'
				));
			  ?>
			</div>
		  </div>
		  
		  <div class="form-group">
			<div class="col-sm-10 col-md-offset-1">
				<?php
					echo $this->Form->input('descripcion',array(
						'label' => 'Descripcion *',
						'placeholder' => 'Descripcion',
						'class' => 'form-control'
					));
				?>
			</div>
		  </div>
		  
		  
		  <div class="form-group">
			<div class="col-sm-10 col-md-offset-1">
				<?php
					 echo $this->Form->input('status', array(
							'options' => array('activo' => 'Activo', 'inactivo' => 'Inactivo'),
							'class' => 'form-control'
						));
				?>
			</div>
		  </div>
		  
		
		  <div class="form-group">
			<div class="col-sm-offset-1 col-sm-10 text-center">
			 <?php 
				echo $this->Form->submit(__('Guardar producto'), array('class' => 'btn btn-success btn-block'));
				echo $this->Form->end;
			 ?>
			</div>
		  </div>
		  </fieldset>
</div>