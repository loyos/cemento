<div style="float:left; color:blue; width:200px; margin-left:170px">
	<div style="float:left">Total de sacos solicitados:</div>
	<div id="cantidad_total" style="color:blue;">
		<?php echo $count_bolsas_h ?>
	</div>
</div>
<div style="float:right; color:blue; width:200px;margin-right:20px">
	<div style="float:left">Total de sacos asignados:</div> 
	<div id="cantidad_total" style="color:blue">
		<?php echo $count_bolsas_asignadas_h ?>
	</div>
</div>
<table class="table table-hover user_table" style="width: 98%;border: 1px solid #bbb;margin-bottom: 10px;margin: 1%;">
		<tr>
			<th style="border-top: 1px solid #bbb;background: #eee;">
			<?php echo 'Cédula'; ?>
			</th>
			<th style="border-top: 1px solid #bbb;background: #eee;">
			<?php echo 'Nombre'; ?>
			</th>
			<th style="border-top: 1px solid #bbb;background: #eee;">
			<?php echo 'Fecha de solicitud'; ?>
			</th>
			<th style="border-top: 1px solid #bbb;background: #eee;">
			<?php echo 'Fecha de entrega'; ?>
			</th>
			<th style="border-top: 1px solid #bbb;background: #eee;">Cantidad solicitada</th>
			<th style="border-top: 1px solid #bbb;background: #eee;">Cantidad asignada</th>
		</tr>
<?php
	foreach($pedidos_h as $p){ ?>
		
		<tr>
			<td style="border-top: 1px solid #bbb;background: #eee;">
				<?php echo $p['Usuario']['cedula']; ?>
			</td>
			<td style="border-top: 1px solid #bbb;background: #eee;">
				<?php echo $p['Usuario']['nombre']; ?>
			</td>
			<td style="border-top: 1px solid #bbb;background: #eee;">
				<?php 
				$fecha = explode(' ',$p['Pedido']['fecha']);
				$date = date_create($fecha[0]);
				echo date_format($date, 'd-m-Y'); ?>
			</td>
			<td style="border-top: 1px solid #bbb;background: #eee;">
				<?php 
				if ($p['Pedido']['fecha_entrega'] == '0000-00-00 00:00:00' && $p['Pedido']['abierto'] == 1) {
					$p['Pedido']['fecha_entrega'] = 'Sin fecha definida';
				} elseif ($p['Pedido']['fecha_entrega'] == '0000-00-00 00:00:00' && $p['Pedido']['abierto'] == 0) {
					$p['Pedido']['fecha_entrega'] = 'Rechazada';
				}
				if ($p['Pedido']['fecha_entrega'] == 'Rechazada') {
					$rechazada = true;
					echo '<span style="color:red">'.$p['Pedido']['fecha_entrega'].'</span>';
				} else {
					echo $p['Pedido']['fecha_entrega'];
				}
				?>
			</td>
			<td style="border-top: 1px solid #bbb;background: #eee;">
				<?php echo $p['Pedido']['cantidad']; ?>
			</td>
			<td style="border-top: 1px solid #bbb;background: #eee;">
				<?php 
				if (!empty($rechaza)) {
					echo 0;
				} else {
					echo $p['Pedido']['num_bolsas_asignadas'];
				}
				?>
			</td>
		</tr>
		
<?php
	}
?>

</table>
</div>
