<?php
App::uses('AppModel', 'Model');

class Dia extends AppModel {

	// public $hasOne = 'Role';
	
	public $actsAs = array(
		'Search.Searchable'
	);
	
	public $filterArgs = array(
		'descripcion' => array(
			'type' => 'like',
			'field' => 'descripcion'
		),
		'fecha' => array(
			'type' => 'query',
			'method' => 'filtro_fecha'
		),
    );
	
	public function filtro_fecha($data = array()){ // esto quedo bello! jajaja
		// debug($data['fecha']);
		$month = $data['fecha']['month'];
		$day = $data['fecha']['day'];
		$year = $data['fecha']['year'];
		$condition = array();
		
		if(!empty($month)){
			$condition1 = array(
				'MONTH(fecha)' => $month
			);
			array_push($condition, $condition1);
		}
		
		if(!empty($day)){
			$condition2 = array(
					'DAY(fecha)' => $day
			);
			array_push($condition, $condition2);
		}
		
		if(!empty($year)){
			$condition3 = array(
					'YEAR(fecha)' => $year
			);
			array_push($condition, $condition3);
		}
		
		return $condition;
	}
	
    public $validate = array(
       
    );
}

?>