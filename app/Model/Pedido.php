<?php
App::uses('AppModel', 'Model');

class Pedido extends AppModel {
    var $name = 'Pedido';
	
	public $actsAs = array(
		'Search.Searchable'
	);
	
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
		'ced' => array(
			'type' => 'query',
			'field' => 'Usuario.cedula',
			'method' => 'buscar_cedula'
		),
		'nombre_completo' => array(
			'type' => 'like',
			'field' => 'Usuario.nombre',
		),
		'status' => array(
			'type' => 'query',
			'field' => 'abierto',
			'method' => 'buscar_status'
		),
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
		
		$month_inicio = $data['fecha_inicio']['month'];
		$day_inicio = $data['fecha_inicio']['day'];
		$year_inicio = $data['fecha_inicio']['year'];
		
		$month_fin = $data['fecha_fin']['month'];
		$day_fin = $data['fecha_fin']['day'];
		$year_fin = $data['fecha_fin']['year'];
		

		if (!empty($month_inicio) && !empty($day_inicio) && !empty($year_inicio)) {
			$fecha_inicio = $year_inicio.'-'.$month_inicio.'-'.$day_inicio;
			$date = strtotime($fecha_inicio);
			$condition1 = array(
					'Pedido.fecha >=' => date('Y-m-d', $date),
			);
			//debug($condition1);die();
			array_push($condition, $condition1);
		}
		
		if (!empty($month_fin) && !empty($day_fin) && !empty($year_fin)) {
			$fecha_fin = $year_fin.'-'.$month_fin.'-'.$day_fin;
			$date = strtotime($fecha_fin);
			$condition1 = array(
					'Pedido.fecha <=' => date('Y-m-d', $date),
			);
			array_push($condition, $condition1);
		}
		
		return $condition;
	}
	
	public function buscar_status($data = array()) {
		if (!empty($data['status']) && $data['status']!='0') {
			if ($data['status'] == '1' ) { //Solicitudes pendientes
				$condition = array(
					'Pedido.abierto' => 1
				);
			} elseif ($data['status'] == '2' ) { //Solicitudes asignadas
				$condition = array(
					'Pedido.abierto' => 0,
					'Pedido.aceptado' => 1
				);
			} else { //Solicitudes rechazadas
				$condition = array(
					'Pedido.abierto' => 0,
					'Pedido.aceptado' => 0
				);
			}
		} else {
			$condition = array();
		}
		//debug($condition);die();
		return $condition;
	}
	
	function buscar_cedula($data=array()) {
		if (!empty($data['ced'])) {
			$ced = array();
			if (strpos(strtoupper($data['ced']), 'V-') === false) {
				$ced[] = 'v-'.$data['ced'];
			} else {
				$ced[] = $data['ced'];
			}
			
			if (strpos(strtoupper($data['ced']), 'E-') === false) {
				$ced[] = 'e-'.$data['ced'];
			} else {
				$ced[] = $data['ced'];
			}
			$condition = array(
					'Usuario.cedula' => $ced
			);
		} else {
			$condition = array();
		}
		return $condition;
	}
	
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