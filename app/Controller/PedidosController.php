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
		$this->Paginator->settings['order'] = 'Usuario.bolsas_actual, Pedido.fecha';
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
	
	public function entregar() {
		if (!empty($this->data)) { 
			$data = $this->data;
			//Verifico en que paso estoy
			if ($data['paso'] == '1') { //Paso 1
				$hay_solicitud  = false;
				if (!empty($data['solicitud'])) {
					foreach ($data['solicitud'] as $key => $s) {
						if ($s == '1') {
							$pedidos[] = $key; 
							$hay_solicitud = true;
						}
					}
				}
				if (!$hay_solicitud) {
					$this->Session->setFlash('Se debe seleccionar al menos una solicitud','error');
					$this->redirect(array('action' => 'index'));
				} else {
					$cantidad_bolsas = $data['Pedido']['bolsas_total'];
					$this->set(compact('pedidos','cantidad_bolsas'));
				}
			} else { //Paso 2
				foreach ($data['solicitudes'] as $s) {
					$pedido = $this->Pedido->findById($s);
					
					//Actualizo el pedido para colocar la fecha de entrega y cerrarlo
					$update_pedido = array('Pedido' => array(
						'id' => $pedido['Pedido']['id'],
						'fecha_entrega' => $this->data['Pedido']['fecha_entrega'],
						'abierto' => 0
					));
					
					$this->Pedido->save($update_pedido);
					
					$fecha = date('Y-m-d');
					$parametro = $this->Parametro->find('first');
					$dias_espera = $parametro['Parametro']['dias'];
					//Chequear periodos
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
								'bolsas' => $periodo['Periodo']['bolsas'] + $pedido['Pedido']['cantidad']
							)
						);
						$this->Periodo->save($update_periodo);
					} else { //Abro un periodo
						$nuevo_periodo = array(
							'Periodo' => array(
								'fecha_inicio' => $fecha,
								'fecha_fin' => date('Y-m-d',strtotime( '+'.$dias_espera.' day' ,strtotime($fecha))),
								'bolsas' => $pedido['Pedido']['cantidad'],
								'usuario_id' => $pedido['Usuario']['id']
							)
						);
						$this->Periodo->create();
						$this->Periodo->save($nuevo_periodo);
						$periodo_id = $this->Periodo->id;
						
						//Actualizo las bolsas actuales del usuario
						$update_usuario = array(
							'Usuario' => array(
								'id' => $pedido['Usuario']['id'],
								'bolsas_actual' => 0
							)
						);
						
						$this->Usuario->save($update_usuario);
					}
					
					//Actualizo las bolsas del usuario en el periodo actual
					$usuario = $this->Usuario->findById($pedido['Usuario']['id']);
					$bolsas_actual = $pedido['Pedido']['cantidad']+$usuario['Usuario']['bolsas_actual'];
					$update_usuario = array('Usuario' => array(
						'id' => $pedido['Usuario']['id'],
						'bolsas_actual' => $bolsas_actual
					));
					$this->Usuario->save($update_usuario);
					
					//Envio correo al usuario
					$datos_usuario = $this->Usuario->findById($pedido['Usuario']['id']);
					$correo = $datos_usuario['Usuario']['correo'];
					$nombre = $datos_usuario['Usuario']['nombre'];
					$num_bolsas = $pedido['Pedido']['cantidad'];
					$fecha_entrega = $this->data['Pedido']['fecha_entrega'];
					
					$Email = new CakeEmail();
					$Email->from(array('cemento@cemento.com' => 'Cemento.com'));
					$Email->emailFormat('html');
					$Email->to($correo);
					$Email->subject(__('Cemento'));
					$Email->template('pedido_asignado');
					$Email->viewVars(compact('nombre','num_bolsas','fecha_entrega'));
					$Email->send();
				}
				$this->redirect(array('action' => 'index'));
			}
		}
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
	
	function delete($id) {
		$this->Pedido->deleteAll(array(
			'Pedido.id' => $id
		));
		$this->redirect(array('action' => 'historial'));
	}
	
	function permitir(){}
}

?>
