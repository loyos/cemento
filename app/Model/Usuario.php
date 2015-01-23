<?php
App::uses('AppModel', 'Model');

class Usuario extends AppModel {
    var $name = 'Usuario';
	
	var $hasMany = array(
		'Pedido' => array(
			'className' => 'Pedido',
			'foreignKey' => 'usuario_id'
		),
		'Periodo' => array(
			'className' => 'Periodo',
			'foreignKey' => 'usuario_id'
		),
	);
	
	public $belongsTo = array(
        'Municipio' => array(
            'className'    => 'Municipio',
            'foreignKey'   => 'municipio_id'
        ),
    );

    public $validate = array(
		'cedula' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Es requerido una cédula'
            )
        ),
        'nombre' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Es requerido un nombre'
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
		'direccion' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Este campo es requerido'
			)
		),
		'municipio_id' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Es requerido seleccionar un estado y municipio'
			)
		),
		'fecha_nacimiento' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Es requerida la fecha de nacimiento'
			)
		),
		'parroquia' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Es requerida la parroquia'
			)
		),
    );
	
}

?>