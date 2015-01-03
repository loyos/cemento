<div class = " grid_16 ">
<div class = "grid_4">&nbsp;</div>
<div class = "row login grid_8 ">
	<fieldset>
		<legend> Agregar </legend>
		(*) Campos obligatorios  <br><br>
		<?php 
			echo $this->Form->create('User', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
		?>
			
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1 ">
				  <?php
					echo $this->Form->input('username',array(
						'label' => 'Nombre de Usuario *',
						'placeholder' => 'Username',
						'class' => 'form-control'
					));
				  ?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('password',array(
							'label' => 'Contraseña *',
							'placeholder' => 'Password',
							'class' => 'form-control',
							'type' => 'text',
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('nombre_completo',array(
							'label' => 'Nombre Completo',
							'placeholder' => 'Nombre',
							'class' => 'form-control'
						));
					?>
				</div>
			  </div>
			   <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('correo',array(
							'label' => 'Correo',
							'placeholder' => 'Correo',
							'class' => 'form-control'
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						// echo $this->Form->input('telefono',array(
							// 'label' => 'Teléfono',
							// 'placeholder' => 'Teléfono',
							// 'class' => 'form-control'
						// ));
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

