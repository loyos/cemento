<?php
App::uses('AppController', 'Controller');

class ParametrosController extends AppController {
	public $components = array('Session');
	public $uses = array('Usuario','Pedido','Periodo','Parametro','Entrega','Dia');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('permitir');
	}
	
	public function index() {
		if (!empty($this->data)) {
			$parametros = $this->Parametro->find('first');
			if ($parametros['Parametro']['dias'] != $this->data['Parametro']['dias']) {
				$periodos = $this->Periodo->find('all',array(
					'fields' => array('DISTINCT Periodo.usuario_id','Periodo.*'),
					'order' => array('Periodo.fecha_fin DESC')
				 ));
				if ($parametros['Parametro']['dias'] > $this->data['Parametro']['dias']) {
					$diferencia = $parametros['Parametro']['dias']-$this->data['Parametro']['dias'];
					 foreach ($periodos as $p) {
						$update_periodo = array('Periodo' => array(
							'id' => $p['Periodo']['id'],
							'fecha_fin' => date('Y-m-d',strtotime( '-'.$diferencia.' day' ,strtotime($p['Periodo']['fecha_fin']))),
						)); 
						$this->Periodo->save($update_periodo);
					 }
				} elseif ($parametros['Parametro']['dias'] < $this->data['Parametro']['dias']) {
					$diferencia = $this->data['Parametro']['dias']-$parametros['Parametro']['dias'];
					 foreach ($periodos as $p) {
						$update_periodo = array('Periodo' => array(
							'id' => $p['Periodo']['id'],
							'fecha_fin' => date('Y-m-d',strtotime( '+'.$diferencia.' day' ,strtotime($p['Periodo']['fecha_fin']))),
						)); 
						$this->Periodo->save($update_periodo);
					 }
				}
			}
			$this->Parametro->save($this->data);
			$this->Session->setFlash('Se actualizaron los parámetros con éxito');
		}
		$parametros = $this->Parametro->find('first');
		$dias = $this->Dia->find('all');
		$this->set(compact('parametros','dias'));
	}
	
	public function add_dia($id = null) {
		if (!empty($this->data)) {
			$data = $this->data;
			$this->Dia->save($data);
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($id)) {
			$this->data = $this->Dia->findById($id);
			$this->set(compact('id'));
		}
		$options = array(
			'Mon' => 'Lunes',
			'Tue' => 'Martes',
			'Wed' => 'Miércoles',
			'Thu' => 'Jueves',
			'Fri' => 'Viernes',
			'Sat' => 'Sábado',
			'Sun' => 'Domingo'
		);
		$this->set(compact('options'));
	}
	
	public function delete_dia($id) {
		$this->Dia->deleteAll(array(
			'Dia.id' => $id
		));
		$this->redirect(array('action' => 'index'));
	}
	
	function permitir() {}
}

?>