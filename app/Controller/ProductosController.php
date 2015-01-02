<?php
// app/Controller/UsersController.php
App::uses('AppController', 'Controller');

/**
 * Productos Controller
 *
 * @property Productos $Producto
 * @property PaginatorComponent $Paginator
 */

class ProductosController extends AppController {
	
	public $components = array('Paginator');
	public $uses = array('Producto');
	
	public function index() {
	
	
	$this->paginate = array(
			'recursive'=>0,
			'limit'=>10
		);
		$this->set('productos', $this->paginate('Producto'));
	
 	}
	
	
	public function add() {
        if ($this->request->is('post')) {
            $this->Producto->create();
            if ($this->Producto->save($this->request->data)) {
                $this->Session->setFlash(__('El Producto ha sido creado con &eacute;xito.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('El Producto no ha sido creado, intente nuevamente.')
            );
        }
    }
	
	
	public function edit($id = null) {
    if (!$id) {
        throw new NotFoundException(__('Producto invalido'));
    }

    $producto = $this->Producto->findById($id);
    if (!$producto) {
        throw new NotFoundException(__('Producto invalido'));
    }

    if ($this->request->is(array('producto', 'put'))) {
        $this->Producto->id = $id;
        if ($this->Producto->save($this->request->data)) {
            $this->Session->setFlash(__('El producto ha sido actualizado satisfactoriamente.'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('No pudo ser actualizado'));
    }

    if (!$this->request->data) {
        $this->request->data = $producto;
    }
	}
	
	
	public function view($id = null) {
        $this->Producto->id = $id;
        $this->set('producto', $this->Producto->read());
    }
	
	
	/**
 * Activar a cliente 
 * @param int $id Id del usuario administrador que se va a activar
 * @return void
 */
	public function activar($id) {
		if ($this->request->is('post')) {
			if (!$this->Producto->exists($id)) {
				throw new NotFoundException(__('Producto desconocido.'));
			}
			$producto = $this->Producto->findById($id);
			$this->Producto->id = $id;
			if($producto['Producto']['status']=='activo'){
				$this->Producto->saveField('status','inactivo');
				$this->Session->setFlash(__('El producto ha sido desactivado.'),'default',array('class'=>'message success'));
				
			}
			else{
				$this->Producto->saveField('status','activo');
				$this->Session->setFlash(__('El Producto ha sido activado.'),'default',array('class'=>'message success'));
			}
			if(isset($this->request->data['src'])&&$this->request->data['src']=='producto')
				$this->redirect('producto');
			else
				$this->redirect('index');
		}else
			$this->redirect($this->adminDefaultRoute);
	}


	
	
}

?>
