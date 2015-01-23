<div id="bloquea" style="width: 20%;height: 80px;overflow: hidden;z-index: 10000;position: relative;text-align: center;background: #FFFFFF;margin-left: auto;margin-right: auto;display:none">
	<?php echo $this->Html->image('enviando.gif',array('style' => 'margin-top:20px')) ?>
</div>
<div class = " grid_16 ">
<div class = "grid_4">&nbsp;</div>
<div class = "row login grid_8 ">
	<fieldset>
		<legend> Entrega de Pedidos </legend>
		<?php 
			echo $this->Form->create('Pedido', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
		?>
			
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1 ">
				  Cantidad total de bolsas
				  <?php
					echo $cantidad_bolsas;
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
						foreach ($pedidos as $p) {
							echo $this->Form->input('solicitudes',array(
								'name' => 'solicitudes[]', 
								'value' => $p,
								'type' => 'hidden',
							));
							echo $this->Form->input('cantidades',array(
								'name' => 'cantidades_aprobadas[]', 
								'value' => $cantidades[$p],
								'type' => 'hidden',
							));
						}
						echo $this->Form->input('paso',array(
							'name' => 'paso',
							'value' => '2',
							'type' => 'hidden',
							'id' => 'paso'
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-1 col-sm-10 text-center">
				 <?php
					echo $this->Form->submit(__('Regresar'), array('class' => 'btn btn-success btn-block', 'style' => 'float:left;margin-right:10px','onClick' => "document.getElementById('paso').value='regresar'"));
					echo $this->Form->submit(__('Guardar'), array('class' => 'btn btn-success btn-block' ,'onClick' => "activar_pantalla()"));
					echo $this->Form->end;
				 ?>
				</div>
			  </div>
		</fieldset>
	</div>
</div>
<script>
	function activar_pantalla() {
		document.getElementById('total_wrap').style.display='block';
		document.getElementById('bloquea').style.display='block';
	}
</script>