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
							'placeholder' => 'Ejm: V-12345678, J-12345678-9',
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
					<div class="input select">
						<label for="estados">Estado *</label>
						<select id="estados" name="estado_id">
							<option>------ Seleccione ------</option>
							<?php foreach ($estados as $key => $value): ?>
								<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
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
						echo $this->Form->input('parroquia_id', array(
							'label' => 'Parroquia *',
							'class' => 'form-control',
							'empty' => '----- Selecciona un municipio -----'
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
				<div class="col-sm-10 col-md-offset-1" id = "bolsas_solicitadas">
					<?php
						echo $this->Form->input('num_bolsas', array(
							'label' => 'Bolsas de cemento solicitadas *',
							'class' => 'form-control',
							'type' => 'select',
							'options' => $options_bolsas
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
			options = "<option value = '0'>----- Seleccione un municipio -----</option>";
			$("#UsuarioParroquiaId").html(options);
			if(!isNaN($(this).val())){
				$.ajax({
					type:'POST',
					dataType:'JSON',
					data:{id:$("#estados").val()},
					url:"<?php echo Router::url(array('action'=>'municipios')); ?>",
					success:function(data){
						if(data.result){
							var options = "";
							options += "<option value = '0'>------ Seleccione ------</option>";
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
		
	//cargar las parroquias cuando se seleccione el municipio
		$("#UsuarioMunicipioId").change(function(){
			if(!isNaN($(this).val())){
				$.ajax({
					type:'POST',
					dataType:'JSON',
					data:{id:$("#UsuarioMunicipioId").val()},
					url:"<?php echo Router::url(array('action'=>'parroquias')); ?>",
					success:function(data){
						if(data.result){
							var options = "";
							$.each(data.datos,function(i,v){
								options +="<option value='"+v.Parroquia.id+"'>"+v.Parroquia.nombre+"</option>";
							});
							$("#UsuarioParroquiaId").html(options);
						}
					},
					beforeSend:function(){
						$("#UsuarioMunicipioId").attr('disabled',true);
					},
					complete:function(){
						$("#UsuarioMunicipioId").removeAttr('disabled');
					}

				});
			}
		});
	
	//Buscar datos por cédula
	$( "#buscar_cedula" ).click(function() {
		if(isNaN($("#UsuarioCedula").val())){
			$.ajax({
				type:'POST',
				dataType:'JSON',
				data:{cedula:$("#UsuarioCedula").val()},
				url:"<?php echo Router::url(array('action'=>'datos_usuario')); ?>",
				success:function(data){
					if(data.result){
						$("#UsuarioNombre").val(data.datos.Usuario.nombre);
						$("#UsuarioTelefono").val(data.datos.Usuario.telefono);
						$("#UsuarioTelefonoOpcional").val(data.datos.Usuario.telefono_opcional);
						$("#UsuarioCorreo").val(data.datos.Usuario.correo);
						$("#estados").val(data.estado_id);
						var options = "";
						$.each(data.municipios,function(i,v){
							options +="<option value='"+v.Municipio.id+"'>"+v.Municipio.nombre+"</option>";
						});
						$("#UsuarioMunicipioId").html(options);
						$("#UsuarioMunicipioId").val(data.municipio_id);
						
						var options = "";
						$.each(data.parroquias,function(i,v){
							options +="<option value='"+v.Parroquia.id+"'>"+v.Parroquia.nombre+"</option>";
						});
						$("#UsuarioParroquiaId").html(options);
						$("#UsuarioParroquiaId").val(data.datos.Parroquia.id);
						
						$("#UsuarioDireccion").val(data.datos.Usuario.direccion);
						
						
						options = '<div class="input select required"><label for="UsuarioNumBolsas">Bolsas de cemento solicitadas *</label><select name="data[Usuario][num_bolsas]" class="form-control" id="UsuarioNumBolsas" required="required">';
						$.each(data.options_bolsas,function(i,v){
							options +="<option value='"+v+"'>"+v+"</option>";
						});
						options += "</select></div>";
						
						$("#bolsas_solicitadas").html(options);
						
					}
				},
				beforeSend:function(){
					$("#UsuarioCedula").attr('disabled',true);
				},
				complete:function(){
					$("#UsuarioCedula").removeAttr('disabled');
				}

			});
		}
	});
</script>