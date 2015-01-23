<?php
App::uses('CakeEmail', 'Network/Email');
class UsuariosController extends AppController {
	public $components = array('Paginator','Session');
	public $uses = array('Usuario','Pedido','Periodo','Parametro','User','Dia','Estado','Parroquia','Municipio','Ciudad');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('logout','index','login','datos_usuario','municipios','parroquias');
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
		//Variables configurables para usar
		$parametro = $this->Parametro->find('first');
		$num_cuenta = $parametro['Parametro']['numero_cuenta'];
		$banco = $parametro['Parametro']['banco'];
		$max_bolsas = $parametro['Parametro']['max_bolsas'];
		$correo_respuesta = $parametro['Parametro']['correo_respuesta'];
		$dias_espera = $parametro['Parametro']['dias']; 
		$fecha = date('Y-m-d');
		
		$estados = $this->Estado->find('list',array(
			'fields' => array('id','nombre'),
			'order' => array('nombre')
		));	
		
		$this->set(compact('num_cuenta','banco'));
		
		if (!empty($this->data)) {
			$data = $this->data;
			if ($max_bolsas >= $data['Usuario']['num_bolsas']) { //Vuelvo a verificar que el numero de bolsas no sea mayor al maximo
				$usuario = $this->Usuario->find('first',array(
					'conditions' => array(
						'UPPER(cedula)' => strtoupper($data['Usuario']['cedula'])
					)
				));
			
				$this->Usuario->set($this->data);
				if ($this->Usuario->validates($this->data)) {
					if (empty($usuario)) { //El usuario no esta en el sistema y nunca ha hecho un pedido.
						$this->Usuario->save($data); //Guardo la información del usuario.
						$usuario_id = $this->Usuario->id;
					} else { //El usuario esta registrado en el sistema
						$usuario_id = $usuario['Usuario']['id'];
						$data['Usuario']['id'] = $usuario_id;
						$this->Usuario->save($data); 
					}
					
					//Creo el pedido
					$pedido = array('Pedido' => array(
						'usuario_id' => $usuario_id,
						'cantidad' => $data['Usuario']['num_bolsas'],
						'num_bolsas_asignadas' => 0,
						'fecha' => $fecha
					));
					$this->Pedido->save($pedido);
					
					//Crear un periodo
					
					//Chequeo que no exista periodo
					$existe_periodo = $this->Periodo->find('first',array(
						'conditions' => array(
							'Periodo.usuario_id' => $usuario_id,
							'Periodo.fecha_inicio <=' => $fecha,
							'Periodo.fecha_fin >=' => $fecha,
						)
					));
					if (!empty($periodo)) { //Se actualiza el periodo
						$nuevo_periodo = array(
							'Periodo' => array(
								'id' => $periodo['Periodo']['id'],
								'fecha_inicio' => $fecha,
								'fecha_fin' => date('Y-m-d',strtotime( '+'.$dias_espera.' day' ,strtotime($fecha))),
								'usuario_id' => $usuario_id
							)
						);
					} else {
						$nuevo_periodo = array(
							'Periodo' => array(
								'fecha_inicio' => $fecha,
								'fecha_fin' => date('Y-m-d',strtotime( '+'.$dias_espera.' day' ,strtotime($fecha))),
								'usuario_id' => $usuario_id
							)
						);
					}
					$this->Periodo->save($nuevo_periodo);
					
					$this->Session->setFlash('La solicitud se generó con exito');
					//Envio el correo al usuario de que la solicitud fue enviada 
							
					$datos_usuario = $this->Usuario->findById($usuario_id);
					$correo = $datos_usuario['Usuario']['correo'];
					$nombre = $datos_usuario['Usuario']['nombre'];
					$bolsas = $data['Usuario']['num_bolsas'];
					
					$Email = new CakeEmail();
					$Email->from(array($correo_respuesta => 'FerroMonica'));
					$Email->emailFormat('html');
					$Email->to($correo);
					$Email->subject(__('Cemento'));
					$Email->template('pedido_recibido');
					$Email->viewVars(compact('nombre','bolsas'));
					$Email->send();
					$this->redirect(array('action' => 'index'));
				} else {
					//Si hay errores debo mantener los combos de estado-municipio-parroquia
					if (!empty($this->data['Usuario']['estado_id'])) {
						$municipios = $this->Municipio->find('list',array(
							'fields' => array('id','nombre'),
							'conditions' => array('Municipio.estado_id' => $this->data['Usuario']['estado_id']),
							'order' => array('Municipio.nombre')
						));
						$this->set(compact('municipios'));
					}
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
		if (!empty($dias)) {
			foreach ($dias as $d) {  
				if ($fecha == $d['Dia']['dia']) {
					if ($hora >= $d['Dia']['hora_inicio'] && $hora <= $d['Dia']['hora_fin']) {
						$activado = true;
					}
				}
			}
			if ($activado) {
				$parametros = $this->Parametro->find('first');
				$max_bolsas = $parametros['Parametro']['max_bolsas'];
				$dias_periodo = $parametros['Parametro']['dias'];
				$this->set(compact('max_bolsas','dias_periodo'));
			}
		} else {
			$activado = false;
		}
		if (!empty($dias)) {
			$dias_espanol = array('Mon' => 'Lunes','Tue' => 'Martes', 'Wed' => 'Miercoles', 'Thu' => 'Jueves','Fri' => 'Viernes','Sat' => 'Sabado','Sun' => 'Domingo');
			$dia = $dias_espanol[$dias[0]['Dia']['dia']];
			$hora_inicio = $dias[0]['Dia']['hora_inicio'];
			$hora_fin = $dias[0]['Dia']['hora_fin'];
		}
		$options_bolsas = array();
		for ($i=1;$i<=$max_bolsas;$i++) {
				$options_bolsas[$i] = $i;
		}
		
		$this->set(compact('activado','dias','estados','options_bolsas','max_bolsas','dia','hora_inicio','hora_fin'));
		
	}
	
	function admin_index(){
		$id = $this->Auth->user('id');
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
	
	function delete($id) {
		$this->User->deleteAll(array(
			'User.id' => $id
		));
		$this->Session->setFlash('El usuario se eliminó con éxito','success');
		$this->redirect(array('action' => 'admin_index'));
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
				'result'=> false,
				'datos'=> array(),
				'estado_id' => array(),
				'municipios' => array(),
				'municipio_id' => array(),
			);
			$usuario = $this->Usuario->find('first',array(
				'conditions'=>array('UPPER(cedula)' => strtoupper($id)),
			));
			
			if(!empty($usuario)) { //Cedula registrada en el sistema
			
			$municipio_id = $usuario['Usuario']['municipio_id'];
			
			$estado_id = $usuario['Municipio']['estado_id'];
			$municipios = $this->Municipio->find('all',array(
				'conditions' => array('Municipio.estado_id' => $estado_id)
			));
			
			//Buscando el numero de bolsas que puede solicitar
			$parametro = $this->Parametro->find('first');
			$max_bolsas = $parametro['Parametro']['max_bolsas'];
		
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
				//Verifico que su pedido no haya sido rechazado
				$tiene_pedido = $this->Pedido->find('all',array(
					'conditions' => array(
						'Pedido.fecha >=' => $periodo['Periodo']['fecha_inicio'],
						'Pedido.fecha <=' => $periodo['Periodo']['fecha_fin'],
						'Pedido.usuario_id' => $usuario['Usuario']['id'],
					)
				));
				if (!empty($tiene_pedido)) {
					$count = 0;
					foreach ($tiene_pedido as $p) {
						if ($p['Pedido']['abierto'] == 0 && $p['Pedido']['aceptado'] == 0) { //El pedido fue rechazado
							$count = $count;
						} else { //Hay pedidos asignados
							$count = $count+1;
						}
					}
					if ($count == 0) { //El pedido fue rechazado
							$result['puede_comprar'] = true;
					} else {
						$result['puede_comprar'] = false;
						$dias	= (strtotime($fecha)-strtotime(date($periodo['Periodo']['fecha_fin'],strtotime('+1 day'))))/86400;
						$dias 	= abs($dias); 
						$dias = floor($dias);
						$result['dias_faltantes'] = $dias;
					}
				} else {	
					$result['puede_comprar'] = true;
				}
			} else {
				$result['puede_comprar'] = true;
			}
			
			
			
			if(!empty($usuario)){
				$result['result'] = true;
				$result['datos']= $usuario;
				$result['estado_id'] = $estado_id;
				$result['municipios'] = $municipios;
				$result['municipio_id'] = $municipio_id;
				//$result['options_bolsas'] = $options_bolsas;
			}
			}
			return json_encode($result);
		}else{
			$this->redirect($this->defaultRoute);
		}
	}
	
}

?>