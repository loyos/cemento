<ul class="nav main">
	<li>
		<?php
			if(!empty($username)){ 
				echo $this->Html->link('Archivo', '#');
			}
		?>
		<ul>
			<li>
				<?php
					if($permisos_usuarios['Permiso']['usuarios'] ){
						echo $this->Html->link(
							 $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Usuarios del Sistema ',
							 array(
								 '#'
							 ),array('escape'=>false)
						 );
					}
				?>
				<ul class = "second_level" style = "left: 154px; top: 0px;">
					<li> 
							<?php
								 echo $this->Html->link(
									 $this->Html->image('listLook.png' , array('width' => '15px')) . ' Usuarios del Sistema ',
									 array(
										 'controller' => 'users',
										 'action' => 'index'
									 ),array('escape'=>false)
								 );
							?>
						</li>
						<li>
							<?php 
								 echo $this->Html->link(
									 $this->Html->image('listAddContacts.png', array('width' => '15px')) . ' Agregar Usuario ',
									 array(
										 'controller' => 'users',
										 'action' => 'add'
									 ),array('escape'=>false)
								 );
							?>
						</li>
				</ul>
			</li>
			
			
			<li>
				<?php
					if($permisos_usuarios['Permiso']['clientes'] ){
						echo $this->Html->link(
							 $this->Html->image('clientes.png' , array('width' => '14px')) .' Clientes del Sistema ',
							 array(
								 '#'
							 ),array('escape'=>false)
						 );
					}
				?>
				<ul class = "second_level" style = "left: 154px; top: 0px;">
					<li> 
							<?php
								 echo $this->Html->link(
									 $this->Html->image('listLook.png' , array('width' => '14px')) . ' Listar Clientes ',
									 array(
										 'controller' => 'clientes',
										 'action' => 'index'
									 ),array('escape'=>false)
								 );
							?>
						</li>
						<li>
							<?php 
								 echo $this->Html->link(
									 $this->Html->image('listAddContacts.png', array('width' => '14px')) . ' Agregar Cliente ',
									 array(
										 'controller' => 'clientes',
										 'action' => 'add'
									 ),array('escape'=>false)
								 );
							?>
						</li>
				</ul>
			</li>




			<li>
				<?php
					if($permisos_usuarios['Permiso']['productos'] ){
						echo $this->Html->link(
							 $this->Html->image('addListIcon.png' , array('width' => '14px')) .' Productos de clientes ',
							 array(
								 '#'
							 ),array('escape'=>false)
						 );
					}
				?>
				<ul class = "second_level" style = "left: 154px; top: 0px;">
					<li> 
							<?php
								 echo $this->Html->link(
									 $this->Html->image('listLook.png' , array('width' => '14px')) . ' Listar productos',
									 array(
										 'controller' => 'productos',
										 'action' => 'index'
									 ),array('escape'=>false)
								 );
							?>
						</li>
						<li>
							<?php 
								 echo $this->Html->link(
									 $this->Html->image('listAddContacts.png', array('width' => '14px')) . ' Agregar Producto ',
									 array(
										 'controller' => 'productos',
										 'action' => 'add'
									 ),array('escape'=>false)
								 );
							?>
						</li>
				</ul>
			</li>


			
			
			<li>
				<?php 
					if($permisos_usuarios['Permiso']['roles'] ){
						echo $this->Html->link(
							 $this->Html->image('roles.png' , array('width' => '15px')) . ' Roles ',
							 array(
								 '#'
							 ),array('escape'=>false)
						 );
					}
				?>
				<ul class = "second_level" style = "left: 154px; top: 0px;">
					<li>
						<?php
							 echo $this->Html->link(
									 $this->Html->image('listsms.png', array('width' => '15px')) . ' Administrar Roles ',
									 array(
										 'controller' => 'permisos',
										 'action' => 'index'
									 ),array('escape'=>false)
								 );
						?>
					</li>
				</ul>
			</li>
			<li>
				<?php 
					if($permisos_usuarios['Permiso']['dias'] ){
						echo $this->Html->link(
							 $this->Html->image('info.png' , array('width' => '15px')) . ' Días No Laborables ',
							 array(
								 '#'
							 ),array('escape'=>false)
						 );
					}
				?>
				<ul class = "second_level" style = "left: 154px; top: 0px;">
					<li>
						<?php
							 echo $this->Html->link(
									 $this->Html->image('listsms.png', array('width' => '15px')) . ' Administrar Días ',
									 array(
										 'controller' => 'dias',
										 'action' => 'index'
									 ),array('escape'=>false)
								 );
						?>
					</li>
				</ul>
			</li>
		</ul>	
	</li>
	<?php if(!empty($username)){?>
		<li class = 'secondary'>
			<?php
				echo $this->Html->link(
					$this->Html->image('logout.png', array('width' => '15px')) . ' Cerrar Sesión ',
					array(
					 'controller' => 'users',
					 'action' => 'logout'
					),array('escape'=>false)
				);
			?>
		</li>
		<li class = "secondary">
			<?php echo $this->Html->link('Bienvenido (a) '. $username, '#'); ?>
			<ul>
				<li>
					<?php
						echo $this->Html->link(
							$this->Html->image('listLook.png', array('width' => '15px')) . ' Ver mis datos ',
							array(
							 'controller' => 'users',
							 'action' => 'view',
							 $id
							),array('escape'=>false)
						);
					?>
				</li>
				<li>
					<?php 
					if($rol_activo == 'administrador'){
						echo $this->Html->link(
							$this->Html->image('listEdit.png', array('width' => '15px')) . ' Editar mis datos ',
							array(
							 'controller' => 'users',
							 'action' => 'edit',
							 $id
							),array('escape'=>false)
						);
					}
					?>
				</li>
			</ul>
		</li>
	<?php }else{ ?>
			<li class = 'secondary'>
				<?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login')); ?>
			</li>
	<?php } ?>
	
</ul>
<script>
	
</script>