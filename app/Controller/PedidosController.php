<?php
App::uses('CakeEmail', 'Network/Email');
class PedidosController extends AppController {
	public $components = array('Paginator','Session','Search.Prg');
	public $uses = array('Pedido','Usuario','Periodo','Parametro','Entrega');
	public $paginate = array(
		'fields' => array('DISTINCT Pedido.id', 'Pedido.*','Usuario.*'),
	);
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('permitir');
	}
	
	public function index() {
		$this->Paginator->settings = $this->paginate;
		$this->Paginator->settings['order'] = 'Usuario.bolsas_actual, Pedido.fecha DESC';
		$this->Prg->commonProcess();
		$this->Paginator->settings['conditions'] = $this->Pedido->parseCriteria($this->Prg->parsedParams());
		array_push($this->Paginator->settings['conditions'],array('Pedido.abierto' => 1));
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
				
				$p = $this->Periodo->findById($periodo_id);
				//Actualizar bolsas que lleva en el mes al usuario
				$update_usuario = array('Usuario' => array(
					'id' => $pedido['Usuario']['id'],
					'bolsas_actual' => $p['Periodo']['bolsas']
				));
				$this->Usuario->save($update_usuario);
				
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
			
			$datos_usuario = $this->Usuario->findById($pedido['Usuario']['id']);
			$correo = $datos_usuario['Usuario']['correo'];
			$nombre = $datos_usuario['Usuario']['nombre'];
			$num_bolsas = $this->data['Pedido']['cantidad_aprobada'];
			$fecha_entrega = $this->data['Pedido']['fecha_entrega'];
			
			$Email = new CakeEmail();
			$Email->from(array('cemento@cemento.com' => 'Cemento.com'));
			$Email->emailFormat('html');
			$Email->to($correo);
			$Email->subject(__('Cemento'));
			$Email->template('pedido_asignado');
			$Email->viewVars(compact('nombre','num_bolsas','fecha_entrega'));
			$Email->send();
				
			$this->redirect(array('action' => 'index'));
		}
		$pedido = $this->Pedido->findById($pedido_id);
		$this->set(compact('pedido'));
	}
	
	public function historial() {
		$this->Paginator->settings = $this->paginate;
		$this->Prg->commonProcess();
		$this->Paginator->settings['conditions'] = $this->Pedido->parseCriteria($this->Prg->parsedParams());
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
	
	function permitir(){}
}

?>
