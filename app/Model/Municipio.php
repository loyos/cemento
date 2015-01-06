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
		'Parroquia' => array(
			'className' => 'Parroquia',
			'foreignKey' => 'municipio_id'
		),
	);
}

?>