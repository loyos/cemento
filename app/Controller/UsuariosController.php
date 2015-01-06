<?php
App::uses('AppController', 'Controller');

class UsuariosController extends AppController {
	public $components = array('Paginator','Session');
	public $uses = array('Usuario','Pedido','Periodo','Parametro','User','Dia','Estado','Parroquia','Municipio','Ciudad');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('logout', 'index','login');
	}
	
	function beforeSave() {
        if ($this->request->data['User']['pass']== ''){			
			unset($this->request->data['User']['password']);
        }else{
			$this->request->data['User']['password'] = $this->request->data['User']['pass'];
		}
		unset($this->request->data['User']['pass']);
     return true;         
 }
	
	public function index() {
		$parametro = $this->Parametro->find('first');
		$max_bolsas = $parametro['Parametro']['max_bolsas'];
		if (!empty($this->data)) {
			$data = $this->data;
			if ($max_bolsas >= $data['Usuario']['num_bolsas']) {
				$usuario = $this->Usuario->find('first',array(
					'conditions' => array(
						'UPPER(cedula)' => strtoupper($data['Usuario']['cedula'])
					)
				));
				$dias_espera = $parametro['Parametro']['dias'];
				$this->Usuario->set($this->data);
				if ($this->Usuario->validates($this->data)) {
				if (empty($usuario)) { //El usuario no esta en el sistema y nunca ha hecho un pedido.
					$this->Usuario->save($data); //Guardo la información del usuario.
					$usuario_id = $this->Usuario->id;
					
					//Creo el pedido
					$pedido = array('Pedido' => array(
						'usuario_id' => $usuario_id,
						'cantidad' => $data['Usuario']['num_bolsas'],
						'num_bolsas' => $data['Usuario']['num_bolsas'],
					));
				
					$this->Pedido->save($pedido);
					$this->Session->setFlash('La solicitud se generó con exito');
					$this->redirect(array('action' => 'index'));
				} else { //El usuario ha hecho pedidos anteriormente
					$usuario_id = $usuario['Usuario']['id'];
					$fecha = date('Y-m-d');
					
					//Verifico si el número de bolsas es valido
					
					//Verifico el numero de bolsas de pedidos abiertos
					$pedidos = $this->Pedido->find('all',array(
						'fields' => array('SUM(Pedido.num_bolsas)'),
						'conditions' => array(
							'Pedido.abierto' => 1,
							'Pedido.usuario_id' => $usuario_id
						)
					));
					$periodo = $this->Periodo->find('first',array(
						'conditions' => array(
							'Periodo.usuario_id' => $usuario_id
						),
						'order' => array('Periodo.id DESC')
					));
					if (!empty($periodo) && $periodo['Periodo']['fecha_inicio'] <= $fecha && $periodo['Periodo']['fecha_fin'] >= $fecha) {
						$hay_periodo_abierto = true;
					} else {
						$hay_periodo_abierto = false;
					}
					
					if (!empty($pedidos)) { //hay pedidos abiertos
						if (($data['Usuario']['num_bolsas'] + $pedidos[0][0]['SUM(`Pedido`.`num_bolsas`)']) > $max_bolsas) {
							$this->Session->setFlash('Ya ha realizado solicitudes y el monto total excede al máximo de bolsas de cemento permitido','error');
						} else {		
							if ($hay_periodo_abierto) {
								if (($periodo['Periodo']['bolsas'] + $data['Usuario']['num_bolsas'] + $pedidos[0][0]['SUM(Pedido.num_bolsas)']) > $max_bolsas) {
									$this->Session->setFlash('El número de bolsas solicitadas mas los pedidos pendientes supera el número de bolsas maximas cada '.$dias_espera,'error');
								}
							} else {
								//Actualizo el usuario
								$data['Usuario']['id'] = $usuario_id;
								$this->Usuario->save($data);
								$pedido = array('Pedido' => array(
									'usuario_id' => $usuario_id,
									'cantidad' => $data['Usuario']['num_bolsas'],
									'num_bolsas' => $data['Usuario']['num_bolsas'],
								));
							
								$this->Pedido->save($pedido);
								$this->Session->setFlash('La solicitud se generó con exito','success');
								$this->redirect(array('action' => 'index'));
							}
						}
					} else {
						if ($hay_periodo_abierto) {
							if (($periodo['Periodo']['bolsas'] + $data['Usuario']['num_bolsas'] + $pedidos[0][0]['SUM(`Pedido`.`num_bolsas`)']) > $max_bolsas) {
								$this->Session->setFlash('El número de bolsas solicitadas supera el número de bolsas maximas cada '.$dias_espera,'error');
							}
						} else {
							//Actualizo el usuario
							$data['Usuario']['id'] = $usuario_id;
							$this->Usuario->save($data);
							$pedido = array('Pedido' => array(
								'usuario_id' => $usuario_id,
								'cantidad' => $data['Usuario']['num_bolsas'],
								'num_bolsas' => $data['Usuario']['num_bolsas'],
							));
						
							$this->Pedido->save($pedido);
							$this->Session->setFlash('La solicitud se generó con exito','success');
							$this->redirect(array('action' => 'index'));
						}
					}
				}
				} else {
					$this->Session->setFlash('Corrige los errores','error');
				}
			} else {
				$this->Session->setFlash('El máximo de bolsas de cemento para solicitar es '.$max_bolsas);
			}
		}
		$dias = $this->Dia->find('all');
		$fecha = date('D');
		$hora = date('H:i:s');
		$activado = false;
		foreach ($dias as $d) {  
			if ($fecha == $d['Dia']['dia']) {
				if ($hora >= $d['Dia']['hora_inicio'] && $hora <= $d['Dia']['hora_fin']) {
					$activado = true;
				}
			}
		} 
		$estados = $this->Estado->find('list',array(
			'fields' => array('id','nombre'),
			'order' => array('nombre')
		));			
		$options_bolsas = array();
		for ($i=0;$i<=$max_bolsas;$i++) {
				$options_bolsas[$i] = $i;
		}
		
		$this->set(compact('activado','dias','estados','options_bolsas'));
		
	}
	
	function admin_index(){
		//$id = $this->Auth->user('id');
		$users = $this->User->find('all');
		$this->set(compact('users','id'));
	}
	
	function admin_add() {
		if (!empty($this->data)) {
			$this->User->set($this->data);
			if ($this->User->validates($this->data)) {
				$this->User->save($this->data);
				$this->redirect(array('action' => 'admin_index'));
			} else {
				$this->Session->setFlash('Corrige los errores');
			}	
		}
	}
	
	 public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('El Usuario ha sido creado con éxito.'), 'success');
                return $this->redirect(array('action' => 'admin_index'));
            }
            $this->Session->setFlash(
                __('El Usuario no ha sido creado, intente nuevamente.'), 'error'
            );
        }
    }
	
	public function edit($id = null) {
		
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Usuario incorrecto'), 'error');
        }
		// debug($this->request->data);
		$this->User->validator()->remove('password');
        if ($this->request->is('post') || $this->request->is('put')) {
			if($this->request->data['User']['password'] = ''){
				unset($this->request->data['User']['password']);
			}
			$this->beforeSave();
			// debug($this->request->data);
			// die();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Los datos han sido actualizados.'), 'success');
                return $this->redirect(array('action' => 'admin_index'));
            }
            $this->Session->setFlash(
                __('Los cambios no se han guardado, intente nuevamente.'), 'error'
            );
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }
	
	/**
	 * Funcion para cargar las parroquias dado el id via POST de un municipio
	 * @return JSON 
	 */
	public function parroquias(){
		if($this->request->isAjax()){
			$this->autoRender = false;
			$id = $this->request->data['id'];
			if(empty($id))
				$this->redirect($this->defaultRoute);
			$result = array(
				'result'=>false,
				'datos'=>array(),
			);
			$parroquias = $this->Parroquia->find('all',array(
				'conditions'=>array('municipio_id'=>$id)
			));
			if(!empty($parroquias)){
				$result['result'] = true;
				$result['datos']= $parroquias;
			}
			return json_encode($result);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	/**
	 * Funcion para cargar los municipios dado el id via POST de un estado
	 * @return JSON 
	 */
	public function municipios(){
		if($this->request->isAjax()){
			$this->autoRender = false;
			$id = $this->request->data['id'];
			if(empty($id))
				$this->redirect($this->defaultRoute);
			$result = array(
				'result'=>false,
				'datos'=>array(),
			);
			$municipios = $this->Municipio->find('all',array(
				'conditions'=>array('estado_id'=>$id)
			));
			if(!empty($municipios)){
				$result['result'] = true;
				$result['datos']= $municipios;
			}
			return json_encode($result);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
	public function datos_usuario(){
		if($this->request->isAjax()){
			$this->autoRender = false;
			$id = $this->request->data['cedula'];
			if(empty($id))
				$this->redirect($this->defaultRoute);
			$result = array(
				'result'=>false,
				'datos'=> array(),
				'estado_id' => array(),
				'municipios' => array(),
				'municipio_id' => array(),
				'parroquias' => array(),
			);
			$usuario = $this->Usuario->find('first',array(
				'conditions'=>array('UPPER(cedula)' => strtoupper($id)),
			));
			$parroquia = $this->Parroquia->find('first',array(
				'conditions' => array('Parroquia.id' => $usuario['Parroquia']['id'])
			));
			$estado_id = $parroquia['Parroquia']['estado_id'];
			$municipios = $this->Municipio->find('all',array(
				'conditions' => array('Municipio.estado_id' => $estado_id)
			));
			$parroquias = $this->Parroquia->find('all',array(
				'conditions' => array('Parroquia.municipio_id' => $parroquia['Parroquia']['municipio_id'])
			));
			
			//Buscando el numero de bolsas que puede solicitar
			$parametro = $this->Parametro->find('first');
			$max_bolsas = $parametro['Parametro']['max_bolsas'];
			$bolsas_actual = 0;
			
			//Pedidos Pendientes
			$pedidos = $this->Pedido->find('all',array(
				'conditions' => array(
					'Pedido.abierto' => 1,
					'Pedido.usuario_id' => $usuario['Usuario']['id']
				)
			));
			if (!empty($pedidos)) {
				foreach ($pedidos as $p) {
					$bolsas_actual = $bolsas_actual+$p['Pedido']['num_bolsas'];
				}
			}
			
			//Busco si hay un periodo abierto
			
			$fecha = date('Y-m-d');
			$periodo = $this->Periodo->find('first',array(
				'conditions' => array(
					'Periodo.fecha_inicio <=' => $fecha,
					'Periodo.fecha_fin >=' => $fecha,
					'Periodo.usuario_id' => $usuario['Usuario']['id']
				)
			));
			
			if (!empty($periodo)) {
				$bolsas_actual = $bolsas_actual + $usuario['Usuario']['bolsas_actual'];
			} else {
				$update_usuario = array(
						'Usuario' => array(
							'id' => $usuario['Usuario']['id'],
							'bolsas_actual' => 0
						)
					);
				$this->Usuario->save($update_usuario);
			}
			
			$bolsas_restantes = $max_bolsas - $bolsas_actual;
			$options_bolsas = array();
			for ($i=0;$i<=$bolsas_restantes;$i++) {
				$options_bolsas[] = $i;
			}
			
			if(!empty($usuario)){
				$result['result'] = true;
				$result['datos']= $usuario;
				$result['estado_id'] = $estado_id;
				$result['municipios'] = $municipios;
				$result['municipio_id'] = $parroquia['Parroquia']['municipio_id'];
				$result['parroquias'] = $parroquias;
				$result['options_bolsas'] = $options_bolsas;
			}
			return json_encode($result);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
}

?>
