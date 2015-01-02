<?php
App::uses('AppModel', 'Model');

class PClientesroducto extends AppModel {
 public $name = 'ClientesProducto';

	
	/**
 * hasMany  associations
 *
 * @var array
 */
	
	public $belongsTo = array(
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Producto' => array(
			'className' => 'Producto',
			'foreignKey' => 'producto_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	
}

?>