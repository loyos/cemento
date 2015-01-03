<?php
App::uses('AppModel', 'Model');

class Usuario extends AppModel {
    var $name = 'Usuario';
	
	var $hasMany = array(
		'Pedido' => array(
			'className' => 'Pedido',
			'foreignKey' => 'usuario_id'
		),
		'Entrega' => array(
			'className' => 'Entrega',
			'foreignKey' => 'usuario_id'
		),
		'Periodo' => array(
			'className' => 'Periodo',
			'foreignKey' => 'usuario_id'
		),
	);
	

    public $validate = array(
        'nombre' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Es requerido un nombre'
            )
        ),
		'cedula' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Es requerido colocar el número de bolsas a solicitar'
			),
			'valida' => array(
				'rule' =>  array('custom', '/^([VEJG]-\d{7,8}[-]?[0-9]?)$/'),
				'message' => 'Formato incorrecto'
			)
		),
		'telefono' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Es requerido colocar el número de teléfono'
			)
		),
		'correo' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Es requerido colocar el correo'
			),
			'correo_valido' => array(
				'rule' =>  array('email'),
				'message' => 'Correo inválido'
			)
		),
		'pais' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Es requerido colocar el pais'
			)
		),
		'estado' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Es requerido colocar el estado o región'
			)
		),
		'direccion' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Es requerido colocar una dirección'
			)
		),
		'num_bolsas' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Es requerido colocar el número de bolsas a solicitar'
			)
		),
    );
	
}

?>