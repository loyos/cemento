<?php
App::uses('AppModel', 'Model');

class Cliente extends AppModel {
 public $name = 'Cliente';

	public $validate = array(
        'nombre' => array(
            'rule' => 'notEmpty'
        ),
		
		'contacto' => array(
            'rule' => 'notEmpty'
        ),
		'departamento' => array(
            'rule' => 'notEmpty'
        ),
		
        'cargo' => array(
            'rule' => 'notEmpty'
        ),
		'n_caracteres' => array(
            'rule' => 'notEmpty'
        )
    );
	
	/**
 * hasMany associations
 *
 * @var array
 */
	
	var $hasMany  = array(
        'CientesProducto' =>
            array('className' => 'ClientesProducto',
                 'foreignKey' => 'cliente_id',
                'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
            )
        );
	
}

?>