<?php 
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Controller\Controller;


class UsersController extends AppController {

    public $paginate = [
            'limit' => 10,
        ];

     public function initialize() {
        parent::initialize();
        $this->loadComponent('Trans');
		 $this->loadComponent('Settings');
		 //$this->Settings->verifylogin($this, "users");
    }
    
	public function index() {
		$this->set('users', $this->paginate($this->Users));
	}

    public function check_client_count(){
        $setting = TableRegistry::get('clients');
        $u = $this->request->session()->read('Profile.id');
        $query = $setting->find()->where(['profile_id LIKE "'.$u.'%" OR profile_id LIKE "%,'.$u.',%" OR profile_id LIKE "%,'.$u.'"']);
		var_dump($query);die();
		$this->response->body(($l));
		return $this->response;
    }


	public function view($id = null) {
		$user = $this->Users->get($id, [ 'contain' => []]);
		$this->set('user', $user);
        $this->set('disabled', 1);
        $this->render("edit");
	}

	public function add() {
	    $this->loadModel('Logos');
	    
        $this->set('logos', $this->paginate($this->Logos->find()->where(['secondary'=>'0'])));
        $this->set('logos1', $this->paginate($this->Logos->find()->where(['secondary'=>'1'])));
		$user = $this->Users->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Users->save($user)) {
				$this->Flash->success($this->Trans->getString("flash_usersaved"));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error($this->Trans->getString("flash_usernotsaved"));
			}
		}
		$this->set(compact('user'));
        $this->render("edit");
	}

	public function edit($id = null) {
		$user = $this->Users->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$user = $this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$this->Flash->success($this->Trans->getString("flash_usersaved"));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error($this->Trans->getString("flash_usernotsaved"));
			}
		}
		$this->set(compact('user'));
	}

	public function delete($id = null) {
		$user = $this->Users->get($id);
		$this->request->allowMethod(['post', 'delete']);
		if ($this->Users->delete($user)) {
			$this->Flash->success($this->Trans->getString("flash_userdeleted"));
		} else {
			$this->Flash->error($this->Trans->getString("flash_usernotdeleted"));
		}
		return $this->redirect(['action' => 'index']);
	}
    
    function logout() {
        $this->request->session()->delete('User.id');
        $this->redirect('/login');
    }
    
   
    
    

}
