<?php
App::uses('AppModel', 'Model');

class Entrega extends AppModel {
    var $name = 'Entrega';
	
	public $belongsTo = array(
        'Usuario' => array(
            'className'    => 'Usuario',
            'foreignKey'   => 'usuario_id'
        ),
		 'Pedido' => array(
            'className'    => 'Pedido',
            'foreignKey'   => 'pedido_id'
        ),
		 'Periodo' => array(
            'className'    => 'Periodo',
            'foreignKey'   => 'periodos_id'
        ),
	);
	
}

?>