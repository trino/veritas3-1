<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;

class AppController extends Controller {

	public function initialize() {
        date_default_timezone_set('America/Toronto');
        $this->request->session()->write('debug',Configure::read('debug'));
        $this->loadComponent('Flash');
        $this->loadComponent('Manager');
        $this->Manager->init($this);
	}



}
