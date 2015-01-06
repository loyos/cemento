<?php
App::uses('AppModel', 'Model');

class Ciudad extends AppModel {
    var $name = 'Ciudad';
	
	public $belongsTo = array(
        'Parroquia' => array(
            'className'    => 'Parroquia',
            'foreignKey'   => 'parroquia_id'
        ),
    );
	
	var $hasMany = array(
		'Usuario' => array(
			'className' => 'Usuario',
			'foreignKey' => 'ciudad_id'
		),
	);
}

?>