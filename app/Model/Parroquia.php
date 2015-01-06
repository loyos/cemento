<?php
App::uses('AppModel', 'Model');

class Parroquia extends AppModel {
    var $name = 'Parroquia';
	
	public $belongsTo = array(
        'Municipio' => array(
            'className'    => 'Municipio',
            'foreignKey'   => 'municipio_id'
        ),
    );
	
	var $hasMany = array(
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'parroquia_id'
		),
	);
}

?>