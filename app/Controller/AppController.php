<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {

	public $uses = array('User','Permiso');
	public $viewClass = 'Theme';
	// public $helpers = array('Menu');

	 public $components = array(
        'Session',
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'index',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'users',
                'action' => 'login'
            ),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish'
                )
            ),
			'authorize' => array('Controller'),
			'unauthorizedRedirect' => '/'
        ),
    );
	
	public function isAuthorized($user) {
		return true;
	}

    public function beforeFilter() {
		$this->theme='960-fluid';
		
		if ($this->params['controller'] == 'usuarios' && $this->params['action'] == 'index') {
			$this->layout = 'usuario';
		} else {
			$this->layout = 'admin';
		}
		
		$username = $this->Auth->user('username');
		$rol_activo = $this->Auth->user('rol');
		$id = $this->Auth->user('id');
		
		if(!empty($username)){
			$this->set('username', $username);
			$this->set('id', $id);
			$this->set('rol_activo', $rol_activo);
		}
    }
}
