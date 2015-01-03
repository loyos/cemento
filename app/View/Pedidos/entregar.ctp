<div class = " grid_16 ">
<div class = "grid_4">&nbsp;</div>
<div class = "row login grid_8 ">
	<fieldset>
		<legend> Entrega de Pedido </legend>
		(*) Campos obligatorios  <br><br>
		<?php 
			echo $this->Form->create('Pedido', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
		?>
			
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1 ">
				  Cantidad solicitada faltante
				  <?php
					echo $pedido['Pedido']['num_bolsas'];
				  ?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('cantidad_aprobada',array(
							'label' => 'Cantidad aprobada para la entrega *',
							'class' => 'form-control',
							'value' => $pedido['Pedido']['num_bolsas']
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('fecha_entrega',array(
							'label' => 'Fecha y Hora aproximada de la entrega *',
							'class' => 'form-control',
							'type' => 'datetime'
						));
						echo $this->Form->input('pedido_id',array(
							'value' => $pedido['Pedido']['id'],
							'type' => 'hidden'
						));
						echo $this->Form->input('cantidad_solicitada',array(
							'value' => $pedido['Pedido']['num_bolsas'],
							'type' => 'hidden'
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
