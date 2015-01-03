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
	// debug('pass');
        // $this->Auth->allow('logout');
		// parent::beforeFilter();
		$this->theme='960-fluid';
		$this->layout = 'admin';
		
		$username = $this->Auth->user('username');
		$rol_activo = $this->Auth->user('rol');
		$id = $this->Auth->user('id');
		
		if(!empty($username)){
			$this->set('username', $username);
			$this->set('id', $id);
			$this->set('rol_activo', $rol_activo);
		}
		
		//permisos
			$tipo_usuario = $this->Auth->user('rol');
			$permisos_usuarios = $this->Permiso->find('first', array(
				'conditions' => array(
					'nombre' => $tipo_usuario
				)
			));
			$this->set('permisos_usuarios', $permisos_usuarios);
			// debug($permisos_usuarios);
		//
    }
}
