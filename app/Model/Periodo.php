<?php
App::uses('AppModel', 'Model');

class Periodo extends AppModel {
    var $name = 'Periodo';
	
	var $hasOne = array('Entrega');
	
	public $belongsTo = array(
        'Usuario' => array(
            'className'    => 'Usuario',
            'foreignKey'   => 'usuario_id'
        )
	);
	
}

?>