<ul class="nav main">
	<li>
		<?php
			if(!empty($username_user)){ 
				echo $this->Html->link('Archivo', '#');
			}
		?>
		<ul>
			<li>
				<?php
					echo $this->Html->link(
						 $this->Html->image('contactsms.png' , array('width' => '15px')) . ' Usuarios del Sistema ',
						 array(
							 '#'
						 ),array('escape'=>false)
					 );
				?>
				<ul class = "second_level" style = "left: 154px; top: 0px;">
					<li> 
							<?php
								 echo $this->Html->link(
									 $this->Html->image('listLook.png' , array('width' => '15px')) . ' Usuarios del Sistema ',
									 array(
										 'controller' => 'usuarios',
										 'action' => 'admin_index'
									 ),array('escape'=>false)
								 );
							?>
						</li>
				</ul>
			</li>
			<li>
				<?php
				echo $this->Html->link(
						 $this->Html->image('addListIcon.png' , array('width' => '14px')) .' Solicitudes ',
						 array(
							 '#'
						 ),array('escape'=>false)
					 );
				?>
				<ul class = "second_level" style = "left: 154px; top: 0px;">
					<li> 
							<?php
								 echo $this->Html->link(
									 $this->Html->image('listLook.png' , array('width' => '14px')) . ' Solicitudes en curso',
									 array(
										 'controller' => 'pedidos',
										 'action' => 'index'
									 ),array('escape'=>false)
								 );
							?>
						</li>
						<li>
							<?php 
								 echo $this->Html->link(
									 $this->Html->image('listLook.png', array('width' => '14px')) . ' Historial de solicitudes ',
									 array(
										 'controller' => 'pedidos',
										 'action' => 'historial'
									 ),array('escape'=>false)
								 );
							?>
						</li>
				</ul>
			</li>
			<li>
				<?php
						echo $this->Html->link(
							 $this->Html->image('addListIcon.png' , array('width' => '14px')) .' Parametrización ',
							 array(
								 '#'
							 ),array('escape'=>false)
						 );
				?>
				<ul class = "second_level" style = "left: 154px; top: 0px;">
					<li> 
							<?php
								 echo $this->Html->link(
									  $this->Html->image('addListIcon.png' , array('width' => '14px')) .' Editar parámetros',
									 array(
										 'controller' => 'parametros',
										 'action' => 'index'
									 ),array('escape'=>false)
								 );
							?>
						</li>
				</ul>
			</li>
		</ul>	
	</li>
	<?php if(!empty($username_user)){?>
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
			<?php echo $this->Html->link('Bienvenido (a) '. $username_user, '#'); ?>
			
		</li>
	<?php }else{ ?>
			<li class = 'secondary'>
				<?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login')); ?>
			</li>
	<?php } ?>
	
</ul>
<script>
	
</script>