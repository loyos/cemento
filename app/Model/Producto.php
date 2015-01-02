<?php
App::uses('AppModel', 'Model');

class Producto extends AppModel {
 public $name = 'Producto';

	public $validate = array(
        'producto' => array(
            'rule' => 'notEmpty'
        ),
		
		'descripcion' => array(
            'rule' => 'notEmpty'
        )
    );
	
	
	
	/**
 * hasMany  associations
 *
 * @var array
 */
	
	var $hasMany  = array(
        'CientesProducto' =>
            array('className' => 'ClientesProducto',
                 'foreignKey' => 'producto_id',
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