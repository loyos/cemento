<div class = " grid_16 ">
<div class = "grid_4">&nbsp;</div>
<div class = "row login grid_8 ">
	<fieldset>
		<legend> Solicitud de cemento </legend>
		(*) Campos obligatorios  <br><br>
		<?php 
			echo $this->Form->create('Usuario', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
		?>
			
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1 ">
				  <?php
					echo $this->Form->input('nombre',array(
						'label' => 'Nombre completo *',
						'class' => 'form-control'
					));
				  ?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('cedula',array(
							'label' => 'Cédula o RIF *',
							'placeholder' => 'Ejm: V-12345678, J-12345678-9',
							'class' => 'form-control',
							'type' => 'text',
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('telefono',array(
							'label' => 'Teléfono Celular *',
							'placeholder' => 'Ejm: 0424-1234578',
							'class' => 'form-control'
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						 echo $this->Form->input('telefono_opcional', array(
							'label' => 'Teléfono local',
							'placeholder' => 'Ejm: 0212-1234578',
							'class' => 'form-control'
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('correo', array(
							'label' => 'Correo *',
							'class' => 'form-control'
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('pais', array(
							'label' => 'País *',
							'class' => 'form-control'
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('estado', array(
							'label' => 'Estado/Región *',
							'class' => 'form-control'
						));
					?>
				</div>
			  </div>
			   <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('ciudad', array(
							'label' => 'Ciudad *',
							'class' => 'form-control'
						));
					?>
				</div>
			  </div>
			   <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('direccion', array(
							'label' => 'Dirección *',
							'class' => 'form-control',
							'style' => 'width:248px'
						));
					?>
				</div>
			  </div>
			   <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('num_bolsas', array(
							'label' => 'Bolsas de cemento solicitadas *',
							'class' => 'form-control',
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

<script>

var keylist="abcdefghijklmnopqrstuvwxyz123456789"
var temp=''

function generatepass(plength){
temp=''
for (i=0;i<plength;i++)
temp+=keylist.charAt(Math.floor(Math.random()*keylist.length))
return temp
}

function populateform(enterlength){
$('#UserPassword').val(generatepass(enterlength));
}
</script>
