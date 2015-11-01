<?php

namespace App\Controller;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Inflector;
use Cake\View\Exception\MissingTemplateException;


class DashboardController extends AppController {

     public function initialize() {
        parent::initialize();
        if(!$this->request->session()->read('Profile.id'))
        {
            $this->redirect('/login');
        }
        
    }
	public function index() {
		
	}
    
    function test()
    {
        $this->layout = 'blank';
    }
    function cms($slug)
    {
        
    }
    function view($slug)
    {
        
    }
}
