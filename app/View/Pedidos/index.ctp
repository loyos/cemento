<div class="page-header" style = "text-align: center;">
	<h1>Consulta de solicitudes en curso<br></h1>
</div>
<div class = "search_box" style= "margin: 1%;">
	<?php echo $this->element('pedidos_search'); ?>
</div>
<?php 
echo $this->Form->create('Pedido',array('url' => array(
				'controller' => 'pedidos',
				'action' => 'entregar'
				),
				'id' => 'FormPedidos'
			));
?>
<div style = "float: left;margin-left:611px;width: 236px;">
	<b><div style="float:left; color:blue">Cantidad de sacos solicitados: </div>
	<div id="cantidad_total_solicitada" style="color:blue">
		0
	</div>
	</b>
</div>
<div style = "float: right;margin-right: 149px;width: 236px;">
	<b><div style="float:left; color:blue">Cantidad de sacos asignados: </div>
	<div id="cantidad_total_asignada" style="color:blue">
		0
	</div>
	</b>
</div>
<br>
<?php 
echo $this->Form->input('bolsas_total',array(
	'type' => 'hidden',
	'value' => 0
));
?>
<table class="table table-hover user_table">
		<tr>
			<th>
			<?php 
			echo $this->Form->create('Pedido',array('url' => array(
				'controller' => 'pedidos',
				'action' => 'entregar'
				)
			));
			echo $this->Form->input('paso',array(
				'name' => 'paso',
				'type' => 'hidden',
				'label' => false,
				'value' => '1'
			));
			echo $this->Form->input('boton',array(
				'type' => 'hidden',
				'label' => false,
				'value' => 'aceptado',
				'id' => 'boton'
			));
			echo $this->Form->input('seleccionar_todos',array(
				'name' => 'seleccionar_todos',
				'type' => 'checkbox',
				'label' => false,
				'style' => 'float:left',
				'id' => 'seleccionar_todos'
			));
			echo $this->Paginator->sort('Usuario.nombre','Nombre'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('Usuario.cedula','Cédula'); ?>
			</th>
			<th>
			Edad
			</th>
			<th>
			<?php echo $this->Paginator->sort('Usuario.correo','Correo'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('Usuario.direccion','Dirección'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('Pedido.fecha','Fecha de solicitud'); ?>
			</th>
			<th>Cant. Solicitada</th>
			<th>Cant. Aprobada</th>
			<th>Núm. de Compras</th>
		</tr>
<?php
	foreach($pedidos as $p){ ?>
		
		<tr>
			<?php if ($num_compras[$p['Usuario']['id']] > 0) {
				$color = 'red';
			} else {
				$color = '#333';
			} ?>
			<td style="color:<?php echo $color ?>">
				<?php 
				$checked = false;
				if (!empty($solicitudes_seleccionadas)) {
					if (in_array($p['Pedido']['id'], $solicitudes_seleccionadas)) {
						$checked = 'checked';
					}
				}
				echo $this->Form->input('seleccionar',array(
					'name' => 'solicitud['.$p['Pedido']['id'].']',
					'type' => 'checkbox',
					'label' => false,
					'style' => 'float:left',
					'class' => 'solicitud',
					'id' => $p['Pedido']['id'],
					'checked' => $checked
				));
				echo $p['Usuario']['nombre']; ?>
			</td>
			<td>
				<?php echo $p['Usuario']['cedula'] ?>
			</td>
			<td> 
				<?php 
				$dias = explode("-",$p['Usuario']['fecha_nacimiento'], 3);
				$dias = mktime(0,0,0,$dias[1],$dias[2],$dias[0]);
				$edad = (int)((time()-$dias)/31556926 );
				if ($edad < 18) {
					echo '<span style="color:red">';
					echo $edad;
					echo '</span>';
				} else {
					echo $edad; 
				}
				?>
			</td>
			<td>
				<?php echo $p['Usuario']['correo']; ?>
			</td>
			<td style="max-width:102px">
				<?php echo $p['Usuario']['parroquia'].' '.$p['Usuario']['direccion']; ?>
			</td>
			<td>
				<?php 
				$fecha = explode(' ',$p['Pedido']['fecha']);
				$date = date_create($fecha[0]);
				echo date_format($date, 'd-m-Y'); ?>
			</td>
			<td style="text-align:center">
				<div id="cantidad_solicitada_cantidad_aceptada_<?php echo $p['Pedido']['id']?>" name="<?php echo $p['Pedido']['cantidad']; ?>">
				<?php echo $p['Pedido']['cantidad'];?>
				</div>
				<?php
				echo $this->Form->input('cantidad_solicitada_cantidad_aceptada',array(
					'type' => 'hidden',
					'label' => false,
					'id' => 'input_cantidad_solicitada_cantidad_aceptada_'.$p['Pedido']['id'],
					'value' => $p['Pedido']['cantidad']
				));
				?>
			</td>
			<td>
				<?php echo $this->Form->input('num_bolsas',array(
					'label' => false,
					'name' => 'cantidad_aprobada['.$p['Pedido']['id'].']',
					'value' => $p['Pedido']['cantidad'],
					'id' => 'cantidad_aceptada_'.$p['Pedido']['id'],
					'class' => 'input_cantidad_aprobada',
					'style' => 'width:35px'
				));
				echo '<div style="color:red;font-size:10px;display:none" id="error_cantidad_aceptada_'.$p['Pedido']['id'].'">';
				echo '*La cantidad tiene que ser mayor a 0';
				echo '</div>';
				echo '<div style="color:red;font-size:10px;display:none" id="error_2_cantidad_aceptada_'.$p['Pedido']['id'].'">';
				echo '*La cantidad no puede ser mayor a la cantidad solicitada';
				echo '</div>';
				?>
			</td>
			<td style="text-align:center;color:<?php echo $color?>">
				<?php echo $num_compras[$p['Usuario']['id']];?>
			</td>
		</tr>
<?php
	}
?>
</table>
<div class="listas index grid_12" style="width:63%">
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
<div id="ajustar_solicitudes" style="display:none">
	<?php echo $this->element('ajustar_solicitudes') ?>
</div>
<?php
echo $this->Form->button('Ajustar Solicitudes',array('style'=>'float:left;margin-right:10px', 'id'=> "abrir_ventana",'type' => 'button')); 
echo $this->Form->submit('Rechazar Solicitudes',array('style'=>'float:left;margin-right:10px', 'onClick'=> "document.getElementById('boton').value='rechazado'"));
echo $this->Form->submit('Aceptar Solicitudes', array('style'=>'float:right;margin-right:20px', 'name' => 'aceptar_form'));
echo $this->Form->end();
 ?>
<script>
	//Ventana emergente para ajustar solicutudes
	$('#abrir_ventana').click( function(){
		//busco la cantidad de solicitud menor
		i = 0;
		$('input[type=checkbox].solicitud').each(function () {
			if (this.checked) {
				if (i == 0) {
					menor_cantidad = $('#input_cantidad_solicitada_cantidad_aceptada_'+$(this).attr('id')).val();
				} else {
					if (parseInt($('#input_cantidad_solicitada_cantidad_aceptada_'+$(this).attr('id')).val()) < parseInt(menor_cantidad)) {
						menor_cantidad = $('#input_cantidad_solicitada_cantidad_aceptada_'+$(this).attr('id')).val();
					} else {
						menor_cantidad = menor_cantidad;
					}
				}
				//console.debug(menor_cantidad);
				i = i+1;
			}
		
		});
		
		$('#input_cantidad_ajuste').val(menor_cantidad);
		
		 $("#ajustar_solicitudes").dialog({ <!--  ------> muestra la ventana  -->
			width: 414,  <!-- -------------> ancho de la ventana -->
			show: "scale", <!-- -----------> animación de la ventana al aparecer -->
			hide: "scale", <!-- -----------> animación al cerrar la ventana -->
			resizable: "false", <!-- ------> fija o redimensionable si ponemos este valor a "true" -->
			position: "center",<!--  ------> posicion de la ventana en la pantalla (left, top, right...) -->
			modal: "false", <!-- ------------> si esta en true bloquea el contenido de la web mientras la ventana esta activa 
			title: 'Ajustar Solicitudes'
		});	
	});
	$(document).ready(function () {
		//Validando los campos del formulario 
		$("#FormPedidos").submit(function (){
			if ($('#boton').val() == 'aceptado') {
				var hay_error = 0;
				//Verifico si hay algun error, si lo hay bloqueo el boton aceptar
				$('input[type=checkbox].solicitud').each(function () {
					if (this.checked) {
						if ($('#cantidad_aceptada_'+$(this).attr('id')).val() <= 0 || $('#cantidad_aceptada_'+$(this).attr('id')).val() > parseInt($('#cantidad_solicitada_cantidad_aceptada_'+$(this).attr('id')).attr('name'))) {
							hay_error = hay_error+1;
						}
					}
				});
				if (hay_error > 0) {
					alert("Corrija los errores");
					return false;
				} else {
					return true;
				}
			}
			return true;
		});
		
		//Si selecciono todos hay que actualizar el número de sacos
		var cantidad_total_asignada = 0;
		var cantidad_total_solicitada = 0;
		$("#seleccionar_todos").change(function () {
			var cantidad_total_asignada = 0;
			var cantidad_total_solicitada = 0;
			if ($(this).is(':checked')) {
				$("input[type=checkbox].solicitud").prop('checked', true); //todos los check
				$('input[type=checkbox].solicitud').each(function () {
					if (this.checked) {
						cantidad_total_asignada = parseInt(cantidad_total_asignada) + parseInt($('#cantidad_aceptada_'+$(this).attr('id')).val());
						cantidad_total_solicitada = parseInt(cantidad_total_solicitada) + parseInt($('#input_cantidad_solicitada_cantidad_aceptada_'+$(this).attr('id')).val());
						
					}
				});
				$('#PedidoBolsasTotal').val(cantidad_total_asignada);
				$('#cantidad_total_solicitada').html(cantidad_total_solicitada);
				$('#cantidad_total_asignada').html(cantidad_total_asignada);
				cantidad_total_asignada = 0;
				cantidad_total_solicitada = 0;
			} else {
				$("input[type=checkbox].solicitud").prop('checked', false); //todos los check
				$('#cantidad_total_asignada').html(cantidad_total_asignada);
				$('#PedidoBolsasTotal').val(cantidad_total_asignada);
				cantidad_total_asignada = 0;
				
				$('#cantidad_total_solicitada').html(cantidad_total_solicitada);
				cantidad_total_solicitada = 0;
			}
		});
		
		$("input[type=checkbox].solicitud").change(function () {
			actualizar_cantidad_bolsas();
		});
	});
	
	function actualizar_cantidad_bolsas() { 
		cantidad_total_asignada = 0;
		cantidad_total_solicitada = 0;
		$('input[type=checkbox].solicitud').each(function () {
			if (this.checked) {
				if ($('#cantidad_aceptada_'+$(this).attr('id')).val().length < 1 ) {
					cantidad_total_asignada = parseInt(cantidad_total_asignada);
				} else {
					cantidad_total_asignada = parseInt(cantidad_total_asignada) + parseInt($('#cantidad_aceptada_'+$(this).attr('id')).val());
				}		
				if ($('#input_cantidad_solicitada_cantidad_aceptada_'+$(this).attr('id')).val().length < 1 ) {
					cantidad_total_solicitada = parseInt(cantidad_total_solicitada);
				} else {
					cantidad_total_solicitada = parseInt(cantidad_total_solicitada) + parseInt($('#input_cantidad_solicitada_cantidad_aceptada_'+$(this).attr('id')).val());
				}
			}
		});
		$('#cantidad_total_solicitada').html(cantidad_total_solicitada);
		$('#cantidad_total_asignada').html(cantidad_total_asignada);
		$('#PedidoBolsasTotal').val(cantidad_total_asignada);
	}
	//Valido las cantidades aceptadas
	$(".input_cantidad_aprobada").keyup(function(){
		actualizar_cantidad_bolsas();
		if ($(this).val() <= 0) {
			$('#error_'+$(this).attr('id')).css('display','block');
			
		} else {
			$('#error_'+$(this).attr('id')).css('display','none');
		}
		if ($(this).val() >  parseInt($('#cantidad_solicitada_'+$(this).attr('id')).attr('name'))) {
			$('#error_2_'+$(this).attr('id')).css('display','block');
		} else {
			$('#error_2_'+$(this).attr('id')).css('display','none');
		}
	});
	
</script>