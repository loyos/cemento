<?php
App::uses('CakeEmail', 'Network/Email');
class PedidosController extends AppController {
	public $components = array('Paginator','Session','Search.Prg');
	public $uses = array('Pedido','Usuario','Periodo','Parametro');
	public $paginate = array(
		'fields' => array('DISTINCT Pedido.id', 'Pedido.*','Usuario.*'),
		'limit' => 50
	);
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('permitir');
	}
	
	public function index($solicitudes_marcadas = null) {
		$this->Paginator->settings = $this->paginate;
		$this->Paginator->settings['order'] = 'Pedido.fecha';
		$this->Prg->commonProcess();
		$this->Paginator->settings['conditions'] = $this->Pedido->parseCriteria($this->Prg->parsedParams());
		array_push($this->Paginator->settings['conditions'],array('Pedido.abierto' => 1));
		$pedidos  = $this->Paginator->paginate('Pedido');
		$fecha = date('Y-m-d');
		$parametro = $this->Parametro->find('first');
		$max_bolsas = $parametro['Parametro']['max_bolsas'];
		
		//Buscando cantidad de compras
		foreach ($pedidos as $p) {
			$pedidos_asigandos = $this->Pedido->find('all',array(
				'fields' => array('SUM(Pedido.aceptado)'),
				'conditions' => array(
					'Pedido.usuario_id' => $p['Usuario']['id']
				)
			));
			$num_compras[$p['Usuario']['id']] = $pedidos_asigandos[0][0]['SUM(`Pedido`.`aceptado`)'];
		}
		
		$this->set(compact('num_compras'));
		
		if (!empty($solicitudes_marcadas)) {
			$solicitudes_seleccionadas = explode('-',$solicitudes_marcadas);
			$this->set(compact('solicitudes_seleccionadas'));
		}
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
							$cantidades[$key] = $data['cantidad_aprobada'][$key];
							$pedidos[] = $key; 
							$hay_solicitud = true;
						}
					}
				}
				if (!$hay_solicitud) { //No selecciono ningun pedido
					$this->Session->setFlash('Se debe seleccionar al menos una solicitud','error');
					$this->redirect(array('action' => 'index'));
				} else { //Hay por lo menos un pedido seleccionado
					if ($data['Pedido']['boton'] == 'aceptado') {
						$cantidad_bolsas = $data['Pedido']['bolsas_total'];
						$this->set(compact('pedidos','cantidad_bolsas','cantidades'));
					} else { //Las solicitudes fueron rechazadas
						foreach ($pedidos as $p) {
							//cierro los pedidos sin aceptarlos
							$update_pedido = array('Pedido' => array(
								'id' => $p,
								'abierto' => 0,
							));
							$this->Pedido->save($update_pedido);
							$pedido = $this->Pedido->findById($p);
							
							//Borro el periodo
							$fecha = date('Y-m-d');
							$periodo = $this->Periodo->find('first',array(
								'conditions' => array(
									'Periodo.fecha_inicio <=' => $fecha,
									'Periodo.fecha_fin >=' => $fecha,
									'Periodo.usuario_id' => $pedido['Usuario']['id']
								)
							));
							$this->Periodo->deleteAll(array('Periodo.id' => $periodo['Periodo']['id']));
							$parametro = $this->Parametro->find('first');
							$correo_rechazo = $parametro['Parametro']['correo_rechazo'];
							$dias_activos = $this->Dia->find('all');
							
							$nombre= $pedido['Usuario']['nombre'];
							//$fecha_entrega = $this->data['Pedido']['fecha_entrega'];
							$dias = array('Mon' => 'Lunes','Tue' => 'Martes', 'Wed' => 'Miercoles', 'Thu' => 'Jueves','Fri' => 'Viernes','Sat' => 'Sabado','Sun' => 'Domingo');
							$dia = $dias[$dias_activos[0]['Dia']['dia']];
							$hora_inicio = $dias_activos[0]['Dia']['hora_inicio'];
							$hora_fin = $dias_activos[0]['Dia']['hora_fin'];
							//Envio los correos
							$Email = new CakeEmail();
							$Email->from(array($correo_rechazo => 'FerroMonica'));
							$Email->emailFormat('html');
							$Email->to($pedido['Usuario']['correo']);
							$Email->subject(__('Cemento FerroMonica'));
							$Email->template('pedido_rechazado');
							$Email->viewVars(compact('dias_activos','nombre','dia','hora_inicio','hora_fin'));
							$Email->send();
						}
						$this->Session->setFlash('Solicitudes rechazadas exitosamente. Se ha enviado un correo a los usuarios','success');
						$this->redirect(array('action' => 'index'));
					}
				}
			} elseif($data['paso'] == '2') { //Paso 2
				foreach ($data['solicitudes'] as $key => $s) {
					$pedido = $this->Pedido->findById($s);
					
					//Actualizo el pedido para colocar la fecha de entrega y cerrarlo
					$update_pedido = array('Pedido' => array(
						'id' => $pedido['Pedido']['id'],
						'fecha_entrega' => $this->data['Pedido']['fecha_entrega'],
						'abierto' => 0,
						'aceptado' => 1,
						'num_bolsas_asignadas' => $data['cantidades_aprobadas'][$key]
					));
					
					$this->Pedido->save($update_pedido);
					
					//Envio correo al usuario
					$parametro = $this->Parametro->find('first');
					$numero_cuenta = $parametro['Parametro']['numero_cuenta'];
					$correo_aceptacion = $parametro['Parametro']['correo_aceptacion'];
					$banco = $parametro['Parametro']['banco'];
					$precio = $parametro['Parametro']['precio']*$data['cantidades_aprobadas'][$key];
					$datos_usuario = $this->Usuario->findById($pedido['Usuario']['id']);
					$correo = $datos_usuario['Usuario']['correo'];
					$nombre = $datos_usuario['Usuario']['nombre'];
					$num_bolsas = $data['cantidades_aprobadas'][$key];
					$fecha_entrega = $this->data['Pedido']['fecha_entrega'];
					$dias = array('dias','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
					$dia = $dias[date('N', strtotime($fecha_entrega['year'].'-'.$fecha_entrega['month'].'-'.$fecha_entrega['day']))];
					$meses = array('meses','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
					$mes = $meses[intval($fecha_entrega['month'])];
					
					$Email = new CakeEmail();
					$Email->from(array($correo_aceptacion => 'FerroMonica'));
					$Email->emailFormat('html');
					$Email->to($correo);
					$Email->subject(__('Cemento'));
					$Email->template('pedido_asignado');
					$Email->viewVars(compact('nombre','num_bolsas','fecha_entrega','precio','dia','mes','numero_cuenta','banco'));
					$Email->send();
				}
				$this->Session->setFlash('Solicitudes procesadas exitosamente. Se ha enviado un correo a los usuarios');
				$this->redirect(array('action' => 'index'));
			} else { //Boton regresar
				$solicitudes = "";
				$i=0;
				foreach ($data['solicitudes'] as $s) {
					if ($i==0) {
						$solicitudes = $s;
					} else {
						$solicitudes = $solicitudes.'-'.$s; 
					} 
					$i++;
				}
				$this->redirect(array('action' => 'index',$solicitudes));
			}
		}
	}
	
	public function historial() {
		$this->Paginator->settings = $this->paginate;
		$this->Paginator->settings['order'] = 'Pedido.fecha DESC';
		$this->Prg->commonProcess();
		$this->Paginator->settings['conditions'] = $this->Pedido->parseCriteria($this->Prg->parsedParams());
		
		//Historial Paginado
		$pedidos  = $this->Paginator->paginate('Pedido');
		$ids_pedidos = Set::combine($pedidos, '{n}.Pedido.id', '{n}.Pedido.id');
		$count_bolsas_asignadas = $this->Pedido->find('all',array(
			'fields' => array('SUM(Pedido.num_bolsas_asignadas)'),
			'conditions' => array('Pedido.id' => $ids_pedidos)
		));
		$count_bolsas_asignadas = $count_bolsas_asignadas[0][0]['SUM(`Pedido`.`num_bolsas_asignadas`)'];
		$count_bolsas = $this->Pedido->find('all',array(
			'fields' => array('SUM(Pedido.cantidad)'),
			'conditions' => array('Pedido.id' => $ids_pedidos)
		));
		$count_bolsas = $count_bolsas[0][0]['SUM(`Pedido`.`cantidad`)'];
		
		//Historial a imprimir
		$pedidos_h = $this->Pedido->find('all',array(
			'fields' => array('DISTINCT Pedido.id', 'Pedido.*','Usuario.*'),
			'conditions' => $this->Paginator->settings['conditions'],
			'order' => array('Pedido.fecha DESC')
		));
		$ids_pedidos_h = Set::combine($pedidos_h, '{n}.Pedido.id', '{n}.Pedido.id');
		$count_bolsas_asignadas_h= $this->Pedido->find('all',array(
			'fields' => array('SUM(Pedido.num_bolsas_asignadas)'),
			'conditions' => array('Pedido.id' => $ids_pedidos_h)
		));
		$count_bolsas_asignadas_h = $count_bolsas_asignadas_h[0][0]['SUM(`Pedido`.`num_bolsas_asignadas`)'];
		$count_bolsas_h = $this->Pedido->find('all',array(
			'fields' => array('SUM(Pedido.cantidad)'),
			'conditions' => array('Pedido.id' => $ids_pedidos_h)
		));
		$count_bolsas_h = $count_bolsas_h[0][0]['SUM(`Pedido`.`cantidad`)'];
		
		$this->set(compact('count_bolsas','count_bolsas_asignadas','count_bolsas_h','count_bolsas_asignadas_h','pedidos_h'));
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
