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
	public $components = array('Paginator');
	public $uses = array('User','Role');
	
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
			if ($this->Auth->login()) {
				//if($this->Auth->user('status') == 'activo'){
					return $this->redirect($this->Auth->redirectUrl());
				// }else{
					// $this->Auth->logout();
					// $this->Session->setFlash(__('Usuario inactivo'), 'error');
					// return true;
				// }
			}
			$this->Session->setFlash(__('Nombre de usuario o password incorrecto'), 'error');
		}
	}

	public function logout() {
		return $this->redirect($this->Auth->logout());
	}

}

?>
