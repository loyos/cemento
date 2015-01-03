<?php
App::uses('AppController', 'Controller');

class UsuariosController extends AppController {
	public $components = array('Paginator','Session');
	public $uses = array('Usuario','Pedido','Periodo','Parametro','User');
	
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
		if (!empty($this->data)) {
			$data = $this->data;
			$parametro = $this->Parametro->find('first');
			$max_bolsas = $parametro['Parametro']['max_bolsas'];
			
			if ($max_bolsas >= $data['Usuario']['num_bolsas']) {
				$usuario = $this->Usuario->find('first',array(
					'conditions' => array(
						'cedula' => $data['Usuario']['cedula']
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
							$this->Session->setFlash('Ya ha realizado solicitudes y el monto total excede al máximo de bolsas de cemento permitido');
						} else {		
							if ($hay_periodo_abierto) {
								if (($periodo['Periodo']['bolsas'] + $data['Usuario']['num_bolsas'] + $pedidos[0][0]['SUM(Pedido.num_bolsas)']) > $max_bolsas) {
									$this->Session->setFlash('El número de bolsas solicitadas mas los pedidos pendientes supera el número de bolsas maximas cada '.$dias_espera);
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
								$this->Session->setFlash('La solicitud se generó con exito');
								$this->redirect(array('action' => 'index'));
							}
						}
					} else {
						if ($hay_periodo_abierto) {
							if (($periodo['Periodo']['bolsas'] + $data['Usuario']['num_bolsas'] + $pedidos[0][0]['SUM(`Pedido`.`num_bolsas`)']) > $max_bolsas) {
								$this->Session->setFlash('El número de bolsas solicitadas supera el número de bolsas maximas cada '.$dias_espera);
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
							$this->Session->setFlash('La solicitud se generó con exito');
							$this->redirect(array('action' => 'index'));
						}
					}
				}
				} else {
					$this->Session->setFlash('Corrige los errores');
				}
			} else {
				$this->Session->setFlash('El máximo de bolsas de cemento para solicitar es '.$max_bolsas);
			}
		}
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
}

?>
