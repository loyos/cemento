<?php 	
	echo '<p>Estimado(a) Sr(a). <b>'.$nombre.',</b></p>';
	echo '<p>Reciba un cordial saludo a continuación le informamos que le fueron aprobados '.$num_bolsas.' sacos de cemento gris, debe ir al banco '.$banco.' a depositar por taquilla o cajero inteligente la cantidad de '.number_format($precio, 2, ',', '.').' bolivares en efectivo en la cuenta corriente '.$numero_cuenta.' a nombre de COMERCIAL FERROMONICA C.A. RIF: J-00126623-2 (No aceptamos transferencias). </p>';
	echo '<p>Debe pasar el día '.$dia.' '.$fecha_entrega['day'].' de '.$mes.' a las '.$fecha_entrega['hour'].':'.$fecha_entrega['min'].' '.$fecha_entrega['meridian'].' a retirarlos con los siguientes requistos:</p>';
	echo '1. Original y copia de la cédula <br>';
	echo '2. Original de este correo <br>';
	echo '3. Original del deposito bancario(Taquilla o Cajero) <br>';
	echo '<p>Nota: solo podrá retirar el cemento el dia y la hora asignada por la persona solicitante. (No se aceptan autorizaciones). </p>';
	echo '<p>Gracias. </p>';
	echo '<p>Favor no contestar este correo.</p>';
?>