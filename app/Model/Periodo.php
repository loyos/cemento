<?php
App::uses('AppModel', 'Model');

class Periodo extends AppModel {
    var $name = 'Periodo';
	
	public $belongsTo = array(
        'Usuario' => array(
            'className'    => 'Usuario',
            'foreignKey'   => 'usuario_id'
        )
	);
	
}

?>