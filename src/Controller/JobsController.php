<?php 
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Controller\Controller;


class JobsController extends AppController {


    public $paginate = [
            'limit' => 10,
            
        ];
     public function initialize() {
        parent::initialize();
		 $this->loadComponent('Trans');
		 $this->loadComponent('Settings');
		 //$this->Settings->verifylogin($this, "jobs");
    }
    
	public function index() {
	   
		$this->set('job', $this->paginate($this->Jobs));
	}



	public function view($id = null) {
		$this->set('disabled',1);
        $this->render('add');
	}

	public function add() {
		$user = $this->Jobs->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Jobs->save($user)) {
				$this->Flash->success($this->Trans->getString("flash_usersaved"));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error($this->Trans->getString("flash_usernotsaved"));
			}
		}
		$this->set(compact('user'));
	}

	public function edit($id = null) {
		$user = $this->Jobs->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$user = $this->Jobs->patchEntity($user, $this->request->data);
			if ($this->Jobs->save($user)) {
				$this->Flash->success($this->Trans->getString("flash_usersaved"));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error($this->Trans->getString("flash_usernotsaved"));
			}
		}
		$this->set(compact('user'));
        $this->render('add');
	}

	public function delete($id = null) {
		$user = $this->Jobs->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->Jobs->delete($user)) {
			$this->Flash->success($this->Trans->getString("flash_jobdeleted"));
		} else {
			$this->Flash->error($this->Trans->getString("flash_jobnotdeleted"));
		}
		return $this->redirect(['action' => 'index']);
	}
    
    function quickcontact()
    {
        
    }
    
}
