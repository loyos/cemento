<?php
App::uses('AppModel', 'Model');

class Estado extends AppModel {
    var $name = 'Estado';
	
	var $hasMany = array(
		'Municipio' => array(
			'className' => 'Municipio',
			'foreignKey' => 'estado_id'
		),
	);
}

?>