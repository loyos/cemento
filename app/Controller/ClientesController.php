<?php
// app/Controller/UsersController.php
App::uses('AppController', 'Controller');

/**
 * Clientes Controller
 *
 * @property Cliente $Cliente
 * @property PaginatorComponent $Paginator
 */

class ClientesController extends AppController {
	
	public $components = array('Paginator');
	public $uses = array('Cliente');
	
	public function index() {
	
	
	$this->paginate = array(
			'recursive'=>0,
			'limit'=>10
		);
		$this->set('clientes', $this->paginate('Cliente'));
	
 	}
	
	
	public function add() {
        if ($this->request->is('post')) {
            $this->Cliente->create();
            if ($this->Cliente->save($this->request->data)) {
                $this->Session->setFlash(__('El Cliente ha sido creado con &eacute;xito.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('El Cliente no ha sido creado, intente nuevamente.')
            );
        }
    }
	
	
	public function edit($id = null) {
    if (!$id) {
        throw new NotFoundException(__('Cliente invalido'));
    }

    $cliente = $this->Cliente->findById($id);
    if (!$cliente) {
        throw new NotFoundException(__('Cliente invalido'));
    }

    if ($this->request->is(array('cliente', 'put'))) {
        $this->Cliente->id = $id;
        if ($this->Cliente->save($this->request->data)) {
            $this->Session->setFlash(__('El cliente ha sido actualizado satisfactoriamente.'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('No pudo ser actualizado'));
    }

    if (!$this->request->data) {
        $this->request->data = $cliente;
    }
	}
	
	
	public function view($id = null) {
        $this->Cliente->id = $id;
        $this->set('cliente', $this->Cliente->read());
    }
	
	
	/**
 * Activar a cliente 
 * @param int $id Id del usuario administrador que se va a activar
 * @return void
 */
	public function activar($id) {
		if ($this->request->is('post')) {
			if (!$this->Cliente->exists($id)) {
				throw new NotFoundException(__('Cliente desconocido.'));
			}
			$cliente = $this->Cliente->findById($id);
			$this->Cliente->id = $id;
			if($cliente['Cliente']['status']=='activo'){
				$this->Cliente->saveField('status','inactivo');
				$this->Session->setFlash(__('El cliente ha sido desactivado.'),'default',array('class'=>'message success'));
				
			}
			else{
				$this->Cliente->saveField('status','activo');
				$this->Session->setFlash(__('El cliente ha sido activado.'),'default',array('class'=>'message success'));
			}
			if(isset($this->request->data['src'])&&$this->request->data['src']=='cliente')
				$this->redirect('cliente');
			else
				$this->redirect('index');
		}else
			$this->redirect($this->adminDefaultRoute);
	}


	
	
}

?>
