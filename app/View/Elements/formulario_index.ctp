<?php
if ($activado) { ?>
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
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('cedula',array(
							'label' => 'Cédula',
							'placeholder' => 'Ejm: V-12345678, E-12345678',
							'class' => 'form-control',
							'type' => 'text',
							'style' => 'float:left;margin-bottom:4px'
						));
						echo $this->Html->image('listLook.png',array(
							'width' => '15px',
							'style' => 'margin-left:4px;cursor:pointer',
							'title'=>'Autocompletar datos',
							'class' => 'tooltip',
							'id' => 'buscar_cedula'
						));
					?>
				</div>
			 </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1 " style="clear:both" id="input_nombre">
				  <?php
					echo $this->Form->input('nombre',array(
						'label' => 'Nombre completo *',
						'class' => 'form-control',
						'style' => 'clear:both'
					));
				  ?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1 ">
				  <?php
					echo $this->Form->input('fecha_nacimiento',array(
						'label' => 'Fecha de nacimiento *',
						'class' => 'form-control',
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
						echo $this->Form->input('estado_id', array(
							'label' => 'Estado *',
							'class' => 'form-control',
							'id' => 'estados',
							'empty' => '------ Selecciona un estado ------'
						));
					?>
				</div>
			  </div>
			   <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('municipio_id', array(
							'label' => 'Municipio *',
							'class' => 'form-control',
							'empty' => '----- Selecciona un estado -----'
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('parroquia', array(
							'label' => 'Parroquia/Urbanización *',
							'class' => 'form-control',
						));
					?>
				</div>
			  </div>
			   <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('direccion', array(
							'label' => 'Dirección Completa *',
							'placeholder' => 'Avenida/Calle/Vereda/Casa nombre o número/Edificio nombre,piso,apartamento',
							'class' => 'form-control',
						));
					?>
				</div>
			  </div>
			  <div id="inputs_opcionales">
			   <div class="form-group">
				<div class="col-sm-10 col-md-offset-1" id = "bolsas_solicitadas">
					<?php
						echo $this->Form->input('num_bolsas', array(
							'label' => 'Sacos de cemento solicitados *',
							'class' => 'form-control',
							'type' => 'select',
							'options' => $options_bolsas,
							'id' => 'input_bolsas'
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-1 col-sm-10 text-center">
				 <?php 
					echo $this->Form->submit(__('Enviar Solicitud'), array('class' => 'btn btn-success btn-block','id' => 'boton_solicitud'));
					echo $this->Form->end;
				 ?>
				</div>
			  </div>
			  </div>
			  <div id="mensaje_opcional" style="display:none"> </div>
		</fieldset>
	</div>
</div>
<?php } else { ?>
<div class = " grid_16 ">
<div class = "grid_4">&nbsp;</div>
<div class = "row login grid_8 ">
	<fieldset>	
		En estos momentos no se pueden realizar solicitudes. El sistema estará habilitado:<br>
		<?php
		foreach ($dias as $d) {
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
			echo $d['Dia']['dia'].' de '.$d['Dia']['hora_inicio'].' a '.$d['Dia']['hora_fin'].'<br>';
		}
		?>
	</fieldset>
	</div>
</div>
<?php } ?>
<script>
	//máscaras para los campos de cédula/rif y teléfonos
		$("#UsuarioTelefono").mask("0999-9999999");	
		$("#UsuarioTelefonoOpcional").mask("0299-9999999");
		
	//reglas para el masked input
	    $.mask.definitions['O'] = "[VEve]";
		$("#UsuarioCedula").mask("O-999999?99");
		
	//cargar los municipios cuando se seleccione el estado
		$("#estados").change(function(){
			options = "<option value = ''>----- Seleccione un municipio -----</option>";
			if(!isNaN($(this).val())){
				$.ajax({
					type:'POST',
					dataType:'JSON',
					data:{id:$("#estados").val()},
					url:"<?php echo Router::url(array('action'=>'municipios')); ?>",
					success:function(data){
						if(data.result){
							var options = "";
							options += "<option value = ''>------ Seleccione ------</option>";
							$.each(data.datos,function(i,v){
								options +="<option value='"+v.Municipio.id+"'>"+v.Municipio.nombre+"</option>";
							});
							$("#UsuarioMunicipioId").html(options);
						}
					},
					beforeSend:function(){
						$("#estados").attr('disabled',true);
					},
					complete:function(){
						$("#estados").removeAttr('disabled');
					}

				});
			}
		});
	
	//Buscar datos por cédula
	$( "#UsuarioCedula" ).keyup(function() {
		cedula = $("#UsuarioCedula").val().split('_');
		cedula = cedula[0];
		if(isNaN(cedula)){
			$.ajax({
				type:'POST',
				dataType:'JSON',
				data:{cedula:cedula},
				url:"<?php echo Router::url(array('action'=>'datos_usuario')); ?>",
				success:function(data){
					if(data.result){
						$("#UsuarioNombre").val(data.datos.Usuario.nombre);
						$("#UsuarioTelefono").val(data.datos.Usuario.telefono);
						$("#UsuarioTelefonoOpcional").val(data.datos.Usuario.telefono_opcional);
						$("#UsuarioCorreo").val(data.datos.Usuario.correo);
						fecha_nacimiento = data.datos.Usuario.fecha_nacimiento.split('-');
						$('#UsuarioFechaNacimientoMonth').val(fecha_nacimiento[1]);
						$('#UsuarioFechaNacimientoYear').val(fecha_nacimiento[0]);
						$('#UsuarioFechaNacimientoDay').val(fecha_nacimiento[2]);
						$("#estados").val(data.estado_id);
						$("#UsuarioParroquia").val(data.datos.Usuario.parroquia);
						$("#UsuarioDireccion").val(data.datos.Usuario.direccion);
						var options = "";
						$.each(data.municipios,function(i,v){
							options +="<option value='"+v.Municipio.id+"'>"+v.Municipio.nombre+"</option>";
						});
						$("#UsuarioMunicipioId").html(options);
						$("#UsuarioMunicipioId").val(data.municipio_id);
						
						
						$("#UsuarioCalle").val(data.datos.Usuario.calle);
						$("#UsuarioCasa").val(data.datos.Usuario.casa);
						
						if (!data.puede_comprar) { //No puede hacer compra
							$('#inputs_opcionales').css('display','none');
							$("#boton_solicitud").attr('disabled',true);
							$('#mensaje_opcional').html('<div style="font-size:14px;color:red;width:370px;margin-left:auto;margin-right:auto;text-align:center"><b>Usted ya realizo una solicitud.</br> Debe esperar '+data.dias_faltantes+' dias para hacer una nueva solicitud.</b></div>');
							$('#mensaje_opcional').css('display','block');
						} else {
							$("#boton_solicitud").removeAttr('disabled');
							$('#inputs_opcionales').css('display','block');
							$('#mensaje_opcional').css('display','none');
						}
						
					} else {
						$("#boton_solicitud").removeAttr('disabled');
						$("#UsuarioNombre").val('');
						$("#UsuarioTelefono").val('');
						$("#UsuarioTelefonoOpcional").val('');
						$("#UsuarioCorreo").val('');
						$("#UsuarioDireccion").val('');
						$("#estados").val(null);
						$("#UsuarioMunicipioId").html("<option value=''>----- Seleccione el estado -----</option>");
						$("#UsuarioParroquia").val("");
						$("#UsuarioCalle").val("");
						$("#UsuarioCasa").val("");
						$('#inputs_opcionales').css('display','block');
						$('#mensaje_opcional').css('display','none');
					}
				},error: function() {
					$("#boton_solicitud").removeAttr('disabled');
					$("#UsuarioNombre").val('');
					$("#UsuarioTelefono").val('');
					$("#UsuarioTelefonoOpcional").val('');
					$("#UsuarioDireccion").val('');
					$("#UsuarioCorreo").val('');
					$("#UsuarioCalle").val("");
					$("#UsuarioCasa").val("");
					$("#estados").val(null);
					$("#UsuarioMunicipioId").html("<option value='0'>----- Seleccione el estado -----</option>");
					$("#UsuarioParroquia").val("");$("#UsuarioDireccion").val('');
					$('#inputs_opcionales').css('display','block');
					$('#mensaje_opcional').css('display','none');
				}
			});
		}
	});
</script>