<?php
// app/Controller/UsersController.php
App::uses('AppController', 'Controller');

/**
 * Clientes Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */

class UsersController extends AppController {
	public $components = array('Paginator', 'Attempt.Attempt');
	public $uses = array('User','Role');
	
	public $loginAttemptLimit = 3;
    public $loginAttemptDuration = '+1 hour';
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('logout', 'view');
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

	public function login() {
		if ($this->request->is('post')) {
			if ( $this->Attempt->limit('login', $this->loginAttemptLimit) ) {
				if ($this->Auth->login()) {
					$this->Attempt->reset('login');
					if($this->Auth->user('status') == 'activo'){
						return $this->redirect($this->Auth->redirectUrl());
					}else{
						$this->Auth->logout();
						$this->Session->setFlash(__('Usuario inactivo'), 'error');
						return true;
					}
				}
			$this->Attempt->fail('login', $this->loginAttemptDuration);
			$this->Session->setFlash(__('Nombre de usuario o password incorrecto'), 'error');
			}else{
				$this->Session->setFlash(__('Usuario bloqueado, intente nuevamente en una hora.'), 'error');
			}
		}
	}

	public function logout() {
		return $this->redirect($this->Auth->logout());
	}

    public function index() {
	
		$this->paginate = array(
			'recursive'=>0,
			'limit'=>10
		);
		$this->set('user', $this->paginate('User'));
	
	
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Usuario incorrecto'), 'error');
        }
        $this->set('user', $this->User->read(null, $id));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('El Usuario ha sido creado con éxito.'), 'success');
                return $this->redirect(array('action' => 'index'));
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
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('Los cambios no se han guardado, intente nuevamente.'), 'error'
            );
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }

    public function delete($id = null) {
        // Prior to 2.5 use
        // $this->request->onlyAllow('post');

        $this->request->allowMethod('get');

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Usuario incorrecto'), 'error');
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('Usuario Eliminado'), 'success');
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('El Usuario no ha podido ser eliminado'), 'error');
        return $this->redirect(array('action' => 'index'));
    }
	
	public function roles(){
		if ($this->request->is('post')){
			debug($this->data);
		}
		$roles = $this->Role->find('all');
		$this->set('roles', $roles);
	}
	
	public function edit_rol($rol_id = null){
		 $this->Role->id = $rol_id;
        if (!empty($this->data)) {
            if ($this->Role->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'roles'));
            }
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        } else {
            $this->request->data = $this->Role->read(null, $rol_id);
			debug('pass');
        }
	}
	
	
	/**
 * Activar a cliente 
 * @param int $id Id del usuario administrador que se va a activar
 * @return void
 */
	public function activar($id) {
		if ($this->request->is('post')) {
			if (!$this->User->exists($id)) {
				throw new NotFoundException(__('Usuario desconocido.'));
			}
			$user = $this->User->findById($id);
			$this->User->id = $id;
			if($user['User']['status']=='activo'){
				$this->User->saveField('status','inactivo');
				$this->Session->setFlash(__('El Usuario ha sido desactivado.'));
				
			}
			else{
				$this->User->saveField('status','activo');
				$this->Session->setFlash(__('El usuario ha sido activado.'));
			}
			if(isset($this->request->data['src'])&&$this->request->data['src']=='user')
				$this->redirect('user');
			else
				$this->redirect('index');
		}else
			$this->redirect($this->adminDefaultRoute);
	}


	

}

?>
