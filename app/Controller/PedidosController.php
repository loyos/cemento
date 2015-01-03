<?php
App::uses('AppController', 'Controller');

class PedidosController extends AppController {
	public $components = array('Paginator','Session','Search.Prg');
	public $uses = array('Usuario','Pedido','Periodo','Parametro','Entrega');
	public $paginate = array(
		'fields' => array('DISTINCT Pedido.id', 'Pedido.*','Usuario.*'),
		'contain' => array('Usuario')
	);
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}
	
	public function index() {
		$this->Paginator->settings = $this->paginate;
		$this->Prg->commonProcess();
		//if (!empty($this->Pedido->parseCriteria($this->Prg->parsedParams()))) {
			$this->Paginator->settings['conditions'] = $this->Pedido->parseCriteria($this->Prg->parsedParams());
		//}
		$pedidos  = $this->Paginator->paginate('Pedido');
		
		$fecha = date('Y-m-d');
		$parametro = $this->Parametro->find('first');
		$max_bolsas = $parametro['Parametro']['max_bolsas'];
	  
	  //Buscando las bolsas restantes 
		foreach ($pedidos as $p) {
			$periodo = $this->Periodo->find('first',array(
				'conditions' => array(
					'Periodo.fecha_inicio <=' => $fecha,
					'Periodo.fecha_fin >=' => $fecha,
					'Periodo.usuario_id' => $p['Usuario']['id']
				)
			));
			if (!empty($periodo) && $periodo['Periodo']['bolsas'] < $max_bolsas) {
				$cantidad_restante[$p['Usuario']['id']] = $max_bolsas - $periodo['Periodo']['bolsas']; 
			} else {
				$cantidad_restante[$p['Usuario']['id']] = $max_bolsas; 
			}
		}
		$this->set(compact('cantidad_restante'));
		$this->set('pedidos',$this->Paginator->paginate('Pedido'));
	}
	
	public function entregar($pedido_id) {
		if (!empty($this->data)) {
			$this->Pedido->set($this->data);
			if ($this->Pedido->validates($this->data)) { 
				$fecha = date('Y-m-d');
				$parametro = $this->Parametro->find('first');
				$dias_espera = $parametro['Parametro']['dias'];
				$pedido = $this->Pedido->findById($this->data['Pedido']['pedido_id']);
				
				//Actualizando tabla pedidos
				$update_pedido = array('Pedido' => array(
					'id' => $pedido['Pedido']['id'],
					'num_bolsas' => $pedido['Pedido']['num_bolsas'] - $this->data['Pedido']['cantidad_aprobada'],
					'fecha_entrega' => $this->data['Pedido']['fecha_entrega'],
				));
				if ($pedido['Pedido']['num_bolsas'] - $this->data['Pedido']['cantidad_aprobada'] == 0) {
					$update_pedido['Pedido']['abierto'] = 0;
				}
				$this->Pedido->save($update_pedido);
				
				//Periodos
				$periodo = $this->Periodo->find('first',array(
					'conditions' => array(
						'Periodo.fecha_inicio <=' => $fecha,
						'Periodo.fecha_fin >=' => $fecha,
						'Periodo.usuario_id' => $pedido['Usuario']['id']
					)
				));
				if (!empty($periodo)) {
					$periodo_id = $periodo['Periodo']['id'];
					$update_periodo = array(
						'Periodo' => array(
							'id' => $periodo_id,
							'bolsas' => $periodo['Periodo']['bolsas'] + $this->data['Pedido']['cantidad_aprobada']
						)
					);
					$this->Periodo->save($update_periodo);
				} else { //Abro un periodo
					$nuevo_periodo = array(
						'Periodo' => array(
							'fecha_inicio' => $fecha,
							'fecha_fin' => date('Y-m-d',strtotime( '+'.$dias_espera.' day' ,strtotime($fecha))),
							'bolsas' => $this->data['Pedido']['cantidad_aprobada'],
							'usuario_id' => $pedido['Usuario']['id']
						)
					);
					$this->Periodo->save($nuevo_periodo);
					$periodo_id = $this->Periodo->id;
				}
				
				//Actualizando Tabla Entregas
				$nueva_entrega = array('Entrega' => array(
					'pedido_id' => $pedido['Pedido']['id'],
					'num_bolsas' => $this->data['Pedido']['cantidad_aprobada'],
					'usuario_id' =>  $pedido['Usuario']['id'],
					'fecha_entrega' => $this->data['Pedido']['fecha_entrega'],
					'periodo_id' => $periodo_id
				));
				
				$this->Entrega->save($nueva_entrega);
			}
			$this->redirect(array('action' => 'index'));
		}
		$pedido = $this->Pedido->findById($pedido_id);
		$this->set(compact('pedido'));
	}
}

?>
