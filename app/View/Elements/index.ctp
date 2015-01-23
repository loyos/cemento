<div class = " grid_16 ">
		<div style="text-align:center"> 
			<?php 
			//echo $this->Form->create(null,array('id' => 'form_index'));
			echo '<span style="font-size:30px;font-weight:bold">COMERCIAL FERROMONICA C.A</span>'; 
			echo '<br> J-00126623-2 <br>';
			echo 'AVENIDA ARTURO MICHELENA EDIF. ARAURIMA LOCAL B STA.MONICA <br>';
			echo 'TELF: 0212-6613004 / 6612045 CORREO: ferromonica2012@gmail.com <br><br>';
			echo '<h1>Sistema de compra programada de cemento por Internet</h1> <br></div>';
			if ($activado && !empty($dia)) {
				echo '<div style="text-align:justify;font-size: 16px;width: 725px;margin-left: auto;margin-right: auto;">';
				echo 'Estimados clientes y amigos conscientes de las dificultades y largas colas para la compra de cemento hemos diseñado este servicio de compra al detal por internet para personas naturales buscando de esta manera apoyar la misma sin cola alguna en nuestro establecimiento. Ahora le indicaremos el proceso de compra paso a paso por el cual usted realizará su solicitud de compra desde 01 hasta '.$max_bolsas.' sacos cada '.$dias_periodo.' días continuos de la fecha de su última solicitud por persona mayor de edad.<br><br>';
				echo '<b>Paso 1:</b> debe ingresar en internet a la direccion  <span style="color:blue"><b>www.ferromonica.com.ve</b></span>  y hacer su solicitud el día viernes en horario de 8:00 am  a 9:00 am , solo se recibirán 100 solicitudes por semana.<br><br>';
				echo '<b>Paso 2:</b> efectuar su solicitud ingresando todo los datos requeridos por la plataforma del sistema al completar los requisitos pulsar la tecla <b>enviar</b> y recibira un correo de recepción de su solicitud.<br><br>';
				echo '<b>Paso 3:</b> si está dentro del cupo de solicitudes  de esa semana (100 solicitudes) recibirá un correo a la brevedad posible con la fecha, cantidad de sacos asignados, hora a retirar y bolivares a cancelar. <b>Deberá efectuar deposito bancario en la agencia del banco '.$banco.' o en las taquillas inteligentes del mismo banco en efectivo, en la cuenta corriente numero '.$num_cuenta.' a nombre de COMERCIAL FERROMONICA C.A. / RIF:J001266232</b> cancelado la cantidad en bolivares asignados. <b>(No se aceptan transferencias)</b>.<br><br>';
				echo '<b>Paso 4:</b> el dia asignado para la entrega del cemento debe traer original y copia de la cédula de identidad , original del correo donde se indica fecha y hora para retirar el producto, cantidad de cemento aprobado y monto a depositar , original de la planilla bancaria o recibo del cajero inteligente <span style="color:red"><b>(SOLO SE PODRÁ RETIRAR EL CEMENTO EL DIA Y  LA HORA ASIGNADA POR LA PERSONA SOLICITANTE)</b></span>.<b>NO SE ACEPTAN AUTORIZACIONES.</b><br><br>';
				echo '<b>Importante:<br>';
				echo 'La venta de cemento sera de uno (01) a diez (10) unidades por persona mayor de edad cada cuarenta y cinco (45) dias continuos de su última solicitud según la existencia y despachos realizados por fábrica .Solo serán procesadas solicitudes de personas residenciadas en las zonas establecidas y nos reservamos el derecho de asignar las cantidades según la disponibilidad. Les pedimos colaboración y paciencia para que este sistema de compra programada funcione en beneficio de todos.<br><br>';
				echo 'Gracias.</b><br><br>';
					echo '<span style="font-weight:bold;color:red">Al presionar continuar estará aceptando los términos establecidos en este documento.</span><br>';
					echo '<div style="width:72px;margin-left: auto;margin-right: auto;margin-top: 10px;margin-bottom: 10px;">';
					echo $this->Form->button('Continuar',array('id' => 'boton_index'));
					echo '</div>';
			} elseif (!$activado && empty($dia)) {
				echo '<div style="color:red;text-align:center;font-size: 16px;width:810px;margin-left: auto;margin-right: auto;">EN ESTOS MOMENTOS EL SISTEMA SE ENCUENTRA EN MANTENIMIENTO. SENTIMOS LAS MOLESTIAS CAUSADAS.<br><br><span style="text-align:justify">GRACIAS.</span></div>';
			} else {
				echo '<div style="color:red;text-align:center;font-size: 16px;width:810px;margin-left: auto;margin-right: auto;">EN ESTOS MOMENTOS EL SISTEMA NO ESTÁ HABILITADO DEBE INGRESAR EL DIA '.STRTOUPPER($dia).' EN EL HORARIO DE '.$hora_inicio['0'].$hora_inicio['1'].':'.$hora_inicio['3'].$hora_inicio['4'].' AM HASTA LAS '.$hora_fin['0'].$hora_fin['1'].':'.$hora_fin['3'].$hora_fin['4'].' AM PARA REALIZAR SU SOLICITUD.<br><br><span style="text-align:justify">GRACIAS.</span></div>';
			}
			echo '</div>';
			?>
</div>