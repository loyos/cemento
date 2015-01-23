<?php
App::uses('AppModel', 'Model');

class Municipio extends AppModel {
    var $name = 'Municipio';
	
	public $belongsTo = array(
        'Estado' => array(
            'className'    => 'Estado',
            'foreignKey'   => 'estado_id'
        ),
    );
	
	var $hasMany = array(
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'municipio_id'
		),
	);
	
}

?>