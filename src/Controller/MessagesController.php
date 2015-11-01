<?php 
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Controller\Controller;


class MessagesController extends AppController {
    public function intialize() {
        parent::intialize();
        $this->loadComponent('Settings');
        //$this->Settings->verifylogin($this, "messages");
        echo $this->request->session()->read('Profile.id');
    }

    public function index() {
	   //$this->Settings->verifylogin($this, "messages");
	}

    public function inbox() {
        $this->layout = 'blank';
        if(!$this->request->session()->read('Profile.id')) {
            $this->redirect('/login');
        }
    }

    public function view() {
        $this->layout = 'blank';
        //$this->Settings->verifylogin($this, "messages");
    }
    
}