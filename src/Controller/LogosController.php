<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;

class LogosController extends AppController {
 
    public function intialize() {
        parent::intialize();
        $this->loadComponent('Settings');
        $this->loadComponent('Trans');
        //$this->Settings->verifylogin($this, "logos");
    }
	public function index() {
		$lg = $this->paginate($this->Logos->find()->where(['secondary'=>'0']));
        $this->set('logos', $this->paginate($this->Logos->find()->where(['secondary'=>'0'])));
         if ($this->request->is(['post', 'put'])){
            foreach($lg as  $l) {
                $log = TableRegistry::get('Logos');
                $query = $log->query();
                $query->update()
                ->set(['active' => 0])
                ->where(['id' => $l->id])
                ->execute();
            }
            $id = $_POST['logo'];
            $logo = TableRegistry::get('Logos');
            $query1 = $logo->query();
            if($query1->update()->set(['active' => 1])->where(['id' => $id])->execute()) {
                $this->Flash->success(__($this->Trans->getString("flash_logosaved")));
                return $this->redirect(['controller'=>'profiles','action' => 'add']);
            }
            $this->Flash->error(__($this->Trans->getString("flash_logonotsaved")));
         }
	}

    public function upload($type="") {
        if($type == 'addnewlogo'){
            $response['secondary'] = 0;
            $arr['type'] = 0;
        } elseif($type == 'addnewlogo1'){
            //$response['type'] = 'primary';
            $response['secondary'] = 1;
            $arr['type'] = 1;
        } elseif($type == 'addnewlogo2'){
            //$response['type'] = 'login';
            $response['secondary'] = 2;
            $arr['type'] = 2;
        }

        $response['active'] = 0;
        $file = $_FILES['myfile']['name'];
        $arr_file = explode('.',$file);
        $ext = end($arr_file);
        $lower = strtolower($ext);
        $allowed = array('jpg','jpeg','png','gif');
        if(in_array($lower,$allowed)) {
            $rand = rand(100000000,999999999).'logo'.'.'.$ext;
            if(move_uploaded_file($_FILES['myfile']['tmp_name'],APP.'../webroot/img/logos/'.$rand)) {
                $arr['image'] = $rand;
                $response['logo'] = $rand;
                //var_dump($response);
                $logos = TableRegistry::get('Logos');
                $logo = $logos->newEntity($response);
                if ($logos->save($logo)) {
                    //$this->Flash->success('Client saved successfully.');
                    $arr['id'] = $logo->id;
                    echo json_encode($arr);
                    die();
                }
            }
        }
        die();
    }

    function ajaxlogo() {
        $lg = $this->Logos->find()->where(['secondary'=>'0']);
        foreach($lg as  $l) {
            $log = TableRegistry::get('Logos');
            $query = $log->query();
            $query->update()
            ->set(['active' => 0])
            ->where(['id' => $l->id])
            ->execute();
        }
        $id = $_POST['logo'];
        $logo = TableRegistry::get('Logos');
        $query1 = $logo->query();
        $query1->update()->set(['active' => 1])->where(['id' => $id])->execute();
       die();
    }

    function ajaxlogo1() {
        $lg = $this->Logos->find()->where(['secondary'=>'1']);
        foreach($lg as  $l) {
            $log = TableRegistry::get('Logos');
            $query = $log->query();
            $query->update()
            ->set(['active' => 0])
            ->where(['id' => $l->id])
            ->execute();
        }
        $id = $_POST['logo'];
        $logo = TableRegistry::get('Logos');
        $query1 = $logo->query();
        $query1->update()->set(['active' => 1])->where(['id' => $id])->execute();
        die();
    }

    function ajaxlogo2() {
        $lg = $this->Logos->find()->where(['secondary'=>'2']);
        foreach($lg as  $l) {
            $log = TableRegistry::get('Logos');
            $query = $log->query();
            $query->update()
            ->set(['active' => 0])
            ->where(['id' => $l->id])
            ->execute();
        }
        $id = $_POST['logo'];
        $logo = TableRegistry::get('Logos');
        $query1 = $logo->query();
        $query1->update()->set(['active' => 1])->where(['id' => $id])->execute();
        die();
    }

    public function secondary() {
		$lg = $this->paginate($this->Logos->find()->where(['secondary'=>'1']));
        $this->set('logos', $this->paginate($this->Logos->find()->where(['secondary'=>'1'])));
         if ($this->request->is(['post', 'put'])){
            foreach($lg as  $l) {
                $log = TableRegistry::get('Logos');
                $query = $log->query();
                $query->update()
                ->set(['active' => 0])
                ->where(['id' => $l->id])
                ->execute();
            }
            $id = $_POST['logo'];
            $logo = TableRegistry::get('Logos');
            $query1 = $logo->query();
            if($query1->update()->set(['active' => 1])->where(['id' => $id])->execute()) {
                $this->Flash->success(__($this->Trans->getString("flash_logosaved")));
                return $this->redirect(['controller'=>'profiles','action' => 'add']);
            }
            $this->Flash->error(__($this->Trans->getString("flash_logonotsaved")));
         }
	}

	public function view($id = null) {
		$logo = $this->Logos->get($id, [
			'contain' => []
		]);
		$this->set('logo', $logo);
	}

	public function add() {
		$logo = $this->Logos->newEntity($this->request->data);
		if ($this->request->is('post')) {
			if ($this->Logos->save($logo)) {
				$this->Flash->success($this->Trans->getString("flash_logosaved"));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error($this->Trans->getString("flash_logonotsaved"));
			}
		}
		$this->set(compact('logo'));
	}

	public function edit($id = null) {
		$logo = $this->Logos->get($id, [
			'contain' => []
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$logo = $this->Logos->patchEntity($logo, $this->request->data);
			if ($this->Logos->save($logo)) {
				$this->Flash->success($this->Trans->getString("flash_logosaved"));
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error($this->Trans->getString("flash_logonotsaved"));
			}
		}
		$this->set(compact('logo'));
	}

	public function delete($id = null) {
		$logo = $this->Logos->get($id);
		//$this->request->allowMethod(['post', 'delete']);
		if ($this->Logos->delete($logo)) {
			echo "ok";
		} else {
			echo "error";
		}
	    die();
	}
    
    function getlogo($type) {
        $logo = TableRegistry::get('Logos');
        $query = $logo->find();
        $query->select(['logo'])->where(['active' => 1, 'secondary'=>$type]);
        $lg = $query->first();
        $log = $lg['logo'];
        $this->response->body(($log));
        return $this->response;
        die();
    }
    
    function change_layout() {
         $layout = $_POST['layout'];
         $setting = TableRegistry::get('Settings');
         $query = $setting->query();
                $query->update()
                ->set(['layout' => $layout])
                ->where(['id' => 1])
                ->execute();
         
         die();
    }

    function get_layout() {
         $setting = TableRegistry::get('Settings');
         $query = $setting->find();
         $query->select(['layout']);
         $l = $query->first();
         $layout = $l->layout;        
         $this->response->body(($layout));
         return $this->response;
         die();
    }
        
    

}
