<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {

	public $uses = array('User','Parametro','Dia');
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
		
		//if (!empty($this->Auth->user('username'))) {
			$username_user = $this->Auth->user('username');
		//}
		$parametro = $this->Parametro->find('first');
		$correo_rechazo = $parametro['Parametro']['correo_rechazo'];
		
		$dias_activos = $this->Dia->find('all');
		
		$this->set(compact('correo_rechazo','dias_activos','username_user'));
		
    }
}
