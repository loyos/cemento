<?php
App::uses('AppModel', 'Model');

class Pedido extends AppModel {
    var $name = 'Pedido';
	
	public $actsAs = array(
		'Search.Searchable'
	);
	
	var $hasOne = array('Entrega');
	
	public $belongsTo = array(
        'Usuario' => array(
            'className'    => 'Usuario',
            'foreignKey'   => 'usuario_id'
        ),
    );
	
	
	
	public $filterArgs = array(
		'fecha_inicio' => array(
			'type' => 'query',
			'field' => 'fecha',
			'method' => 'filtro_fecha'
		),
		'fecha_fin' => array(
			'type' => 'query',
			'field' => 'fecha',
			'method' => 'filtro_fecha'
		),
		// 'cedula' => array(
			// 'type' => 'query',
			// 'field' => 'cedula',
			// 'method' => 'buscar_cedula'
		// )
		
    );
	
	 public $validate = array(
        'cantidad_aprobada' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Es requerido colocar la cantidad'
            ),
			'cantidad_menor' => array(
				'rule' =>  array('verificar_cantidad'),
				'message' => 'La cantidad debe ser menor o igual a la solicitada'
			)
        ),
		'fecha_entrega' => array(
			'fecha_mayor' => array(
				'rule' =>  array('verificar_fecha'),
				'message' => 'La fecha debe ser mayor a la actual'
			)
		),
    );
	
	public function filtro_fecha($data = array()){
		$condition = array();
		if (!empty($data['fecha_inicio'])) {
			$condition1 = array(
					'fecha >=' => $data['fecha_inicio'],
			);
			array_push($condition, $condition1);
		}
		if (!empty($data['fecha_fin'])) {
			$condition2 = array(
					'fecha <=' => $data['fecha_fin'],
			);
			array_push($condition, $condition2);
		}
		return $condition;
	}
	
	// public function buscar_cedula($data = array()) {
		// if (!empty($data['cedula'])) {
			// $condition = array(
			// 'Usuario.cedula' => $data['cedula']
			// );
		// } else {
			// $condition = array();
		// }
		// return $condition;
	// }
	
	function verificar_cantidad() {
		$cantidad_solicitada = $this->data['Pedido']['cantidad_solicitada'];
		if ($cantidad_solicitada < $this->data['Pedido']['cantidad_aprobada']) {
			return false;
		}
		return true;
	}
	
	function verificar_fecha() {
		$fecha = date('Y-m-d');
		if ($fecha >= $this->data['Pedido']['fecha_entrega']) {
			return false;
		}
		return true;
	}
}

?>