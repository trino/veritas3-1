<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;
use Cake\Network\Email\Email;


class FeedbacksController extends AppController{
    
    public function intialize() {
        parent::intialize();
        $this->loadComponent('Settings');
        $this->loadComponent('Trans');
        //$this->Settings->verifylogin($this, "feedbacks");
    }
    
    public function index() {
        
    }
    
    public function add($order_id,$cid) {
        $this->loadComponent('Document');
        if(isset($_GET['document'])) {
            $_POST['document_id'] = $order_id;
            $_POST['order_id'] = 0;
        } else{
            $_POST['order_id'] = $order_id;
            $_POST['document_id'] = 0;
        }
        $_POST['client_id'] = $cid;
        $_POST['user_id'] = $this->request->session()->read('Profile.id');
        
        
        $docs = TableRegistry::get('Feedbacks');
        if(isset($_GET['document'])) {
            $docx = $docs->find()->where(['document_id' => $order_id])->first();
        }else {
            $docx = $docs->find()->where(['order_id' => $order_id])->first();
        }
        if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                if (!isset($_GET['order_id'])){
                    $arr['order_id'] = $_POST['order_id'];
                }else {
                    $arr['order_id'] = $_GET['order_id'];
                }
                $arr['document_id'] = 0;
                if (isset($_POST['uploaded_for'])) {
                    $uploaded_for = $_POST['uploaded_for'];
                }else {
                    $uploaded_for = '';
                }
                $for_doc = array('document_type'=>'Feedback','sub_doc_id'=>6,'order_id'=>$arr['order_id'],'user_id'=>$_POST['user_id'],'uploaded_for'=>$uploaded_for);
                 $this->Document->saveDocForOrder($for_doc);
                 
                 if(isset($_POST['attach_doc'])) {
                            $model = $this->loadModel('DocAttachments');
                            $model->deleteAll(['order_id'=> $order_id,'sub_id'=>6]);
                            $client_docs = $_POST['attach_doc'];
                            foreach($client_docs as $d) {
                                if($d != "") {
                                    $attach = TableRegistry::get('doc_attachments');
                                    $ds['order_id']= $order_id;
                                    $ds['attachment'] =$d;
                                    $ds['sub_id'] = 6;
                                    $att = $attach->newEntity($ds);
                                    $attach->save($att);
                                    unset($att);
                                }
                            }
                        } 
            } else {
                if(isset($_POST['attach_doc'])) {
                            $model = $this->loadModel('DocAttachments');
                            $model->deleteAll(['document_id'=> $order_id]);
                            $client_docs = $_POST['attach_doc'];
                            foreach($client_docs as $d) {
                                if($d != "") {
                                    $attach = TableRegistry::get('doc_attachments');
                                    $ds['document_id']= $order_id;
                                    $ds['attachment'] =$d;
                                    $ds['sub_id'] = 6;
                                    $att = $attach->newEntity($ds);
                                    $attach->save($att);
                                    unset($att);
                                }
                            }
                        } 
            }
        if($docx) {
            
            $feedback['title']= $_POST['title'];
            $feedback['description'] = $_POST['description'];
            $feedback['reason'] = $_POST['reason'];
            $feedback['scale'] = $_POST['scale'];
            $feedback['suggestion'] = $_POST['suggestion'];
            //var_dump($docx);
            // var_dump($feedback);
            //die();
            $id = $docx->id;
                    
            $updates = $docs->query();
               $update = $updates->update()
                ->set($feedback)
                ->where(['id' => $id])
                ->execute();
             if($update) {
                 echo "OK";
             }
        } else {
            //die('2');
            $doc = $docs->newEntity($_POST);
    		if ($this->request->is('post')) {
    		  
    			if ($docs->save($doc)) {
    			     echo "OK";
    				//$this->Flash->success('The feedback has been sent.');
                    	//return $this->redirect('/documents/index');
    			} else {
    			 echo "ss";
    				//$this->Flash->error('Feedback not sent. Please try again.');
                    //return $this->redirect('/feedbacks/add');
    			}
    		}
        }
		//$this->set(compact('client'));
        //$this->render('add');
        die();
    }
    
    
    function basic($cid, $did) {
            $this->set('doc_comp',$this->Document);
           
            if (isset($_POST)) {
                
                if (isset($_GET['draft']) && $_GET['draft']) {
                    $arr['draft'] = 1;
                    $draft = '?draft';
                } else {
                    $arr['draft'] = 0;
                    $draft = '';    
                }
                $arr['sub_doc_id'] = $_POST['sub_doc_id'];
                $arr['client_id'] = $cid;
                $arr['document_type'] = $_POST['document_type'];
               
                
                 if(!isset($_GET['order_id'])){
                if (!$did || $did == '0') {
                    
                    $arr['user_id'] = $this->request->session()->read('Profile.id');
                    $arr['created'] = date('Y-m-d H:i:s');
                    $docs = TableRegistry::get('Documents');
                    $doc = $docs->newEntity($arr);

                    if ($docs->save($doc)) {

                        $doczs = TableRegistry::get('generic_forms');
                        $ds['document_id'] = $doc->id;
                        
                        foreach($_POST as $k=>$v) {
                            $ds[$k]=$v;
                        }
                        $docz = $doczs->newEntity($ds);
                        $doczs->save($docz);
                        $did = $doc->id;
                        if(isset($_POST['attach_doc'])) {
                            //var_dump($_POST['attach_doc']);die();
                            $model = $this->loadModel('DocAttachments');
                            $model->deleteAll(['document_id'=> $did]);
                            //$client_do = implode(',',$_POST['attach_doc']);
                            //$client_docs=explode(',',$client_do);
                            foreach($_POST['attach_doc'] as $d) {
                                if($d != "") {
                                    $attach = TableRegistry::get('doc_attachments');
                                    $ds['document_id']= $did;
                                    $ds['attachment'] =$d;
                                    $ds['sub_id'] = $arr['sub_doc_id'];
                                    $att = $attach->newEntity($ds);
                                    $attach->save($att);
                                    unset($att);
                                }
                            }
                            
                        }
                        unset($doczs);
                        $this->Flash->success($this->Trans->getString("flash_docsaved"));
                        $this->redirect(array('action' => 'index'.$draft));
                    } else {
                        $this->Flash->error($this->Trans->getString("flash_docnotsaved"));
                        $this->redirect(array('action' => 'index'.$draft));
                    }

                } else {
                    $docs = TableRegistry::get('Documents');
                    $query2 = $docs->query();
                    $query2->update()
                        ->set($arr)
                        ->where(['id' => $did])
                        ->execute();
                        $this->loadModel('GenericForms');
                        $this->GenericForms->deleteAll(['document_id' => $did]);
                        $doczs = TableRegistry::get('generic_forms');
                        $ds['document_id'] = $did;
                        
                        foreach($_POST as $k=>$v) {
                            $ds[$k]=$v;
                        }
                        $docz = $doczs->newEntity($ds);
                        $doczs->save($docz);
                        if(isset($_POST['attach_doc'])) {
                            
                            $model = $this->loadModel('DocAttachments');
                            $model->deleteAll(['document_id'=> $did]);
                            $client_docs = $_POST['attach_doc'];
                            foreach($client_docs as $d) {
                                if($d != "") {
                                    $attach = TableRegistry::get('doc_attachments');
                                    $ds['document_id']= $did;
                                    $ds['attachment'] =$d;
                                    $ds['sub_id'] = $arr['sub_doc_id'];
                                    $att = $attach->newEntity($ds);
                                    $attach->save($att);
                                    unset($att);
                                }
                            }
                        }
                        unset($doczs);
                    $this->Flash->success($this->Trans->getString("flash_docupdated"));
                    $this->redirect(array('action' => 'index'.$draft));
                }
                } else {
                    $arr['document_id'] = 0;                   
                    if(!isset($_GET['order_id'])) {
                        $arr['order_id'] = $did;
                    }else{
                        $arr['order_id'] = $_GET['order_id'];
                        $did = $_GET['order_id'];
                    }
                    $arr['document_id'] = 0;
                    if (isset($_POST['uploaded_for'])) {
                        $uploaded_for = $_POST['uploaded_for'];
                    }else {
                        $uploaded_for = '';
                    }
                    $for_doc = array('document_type'=>'Audit','sub_doc_id'=>8,'order_id'=>$arr['order_id'],'user_id'=>'','uploaded_for'=>$uploaded_for);
                    $this->Document->saveDocForOrder($for_doc);
                    
                    $doczs = TableRegistry::get('generic_forms');
                    $check = $doczs->find()->where(['order_id'=>$did])->first();
                    unset($doczs);
                    if (!$check) {
                        $ds['order_id'] = $did;
                        $ds['document_id'] = 0;
                        $doczs = TableRegistry::get('generic_forms');
                        foreach($_POST as $k=>$v) {
                            $ds[$k]=$v;
                        }
                        $docz = $doczs->newEntity($ds);
                        $doczs->save($docz);
                        unset($doczs);
                    } else {
                            $this->loadModel('GenericForms');
                            $this->Audits->deleteAll(['order_id' => $did]);
                            $doczs = TableRegistry::get('generic_forms');
                            $ds['order_id'] = $did;
                            
                            foreach($_POST as $k=>$v) {
                                $ds[$k]=$v;
                            }
                            $docz = $doczs->newEntity($ds);
                            $doczs->save($docz);
                            unset($doczs);  
                        }
                    die();
                }
            }
        }
    
    public function addsurvey($order_id,$cid) {
        $this->loadComponent('Document');
        if(isset($_GET['document'])) {
            $_POST['document_id'] = $order_id;
            $_POST['order_id'] = 0;
        } else{
            $_POST['order_id'] = $order_id;
            $_POST['document_id'] = 0;
        }
        
        $_POST['client_id'] = $cid;
        
        $_POST['user_id'] = $this->request->session()->read('Profile.id');
        if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                if(!isset($_GET['order_id'])) {
                    $arr['order_id'] = $_POST['order_id'];
                }else {
                    $arr['order_id'] = $_GET['order_id'];
                }
                $arr['document_id'] = 0;
                if (isset($_POST['uploaded_for'])) {
                    $uploaded_for = $_POST['uploaded_for'];
                }else {
                    $uploaded_for = '';
                }
                $for_doc = array('document_type'=>'Survey','sub_doc_id'=>5,'order_id'=>$arr['order_id'],'user_id'=>$_POST['user_id'],'uploaded_for'=>$uploaded_for);
                
                $this->Document->saveDocForOrder($for_doc);
                
                if(isset($_POST['attach_doc'])) {
                            
                            $model = $this->loadModel('DocAttachments');
                            $model->deleteAll(['order_id'=> $arr['order_id'],'sub_id'=>5]);
                            $client_docs = $_POST['attach_doc'];
                            foreach($client_docs as $d) {
                                if($d != "") {
                                    $attach = TableRegistry::get('doc_attachments');
                                    $ds['order_id']= $arr['order_id'];
                                    $ds['attachment'] =$d;
                                    $ds['sub_id'] = 5;
                                    $att = $attach->newEntity($ds);
                                    $attach->save($att);
                                    unset($att);
                                }
                            }
                        } 
            } else {
                if(isset($_POST['attach_doc'])) {
                            
                            $model = $this->loadModel('DocAttachments');
                            $model->deleteAll(['document_id'=> $order_id,'sub_id'=>5]);
                            $client_docs = $_POST['attach_doc'];
                            foreach($client_docs as $d) {
                                if($d != "") {
                                    $attach = TableRegistry::get('doc_attachments');
                                    $ds['document_id']= $order_id;
                                    $ds['attachment'] =$d;
                                    $ds['sub_id'] = 5;
                                    $att = $attach->newEntity($ds);
                                    $attach->save($att);
                                    unset($att);
                                }
                            }
                        } 
            }
        
        
        
        $docs = TableRegistry::get('Survey');
        if(isset($_GET['document']) && !isset($_GET['order_id'])) {
            $docx = $docs->find()->where(['document_id' => $order_id])->first();
        }else{
            if(isset($_GET['order_id'])) {
                $docx = $docs->find()->where(['order_id' => $_GET['order_id']])->first();
            }else {
                $docx = $docs->find()->where(['order_id' => $order_id])->first();
            }
        }
        if($docx) {
            $survey['ques1']= $_POST['ques1'];
            $survey['ques2a'] = $_POST['ques2a'];
            $survey['ques2b'] = $_POST['ques2b'];
            $survey['ques2c'] = $_POST['ques2c'];
            $survey['ques4'] = $_POST['ques4'];
            $survey['ans4'] = $_POST['ans4'];
            $id = $docx->id;
                    
            $updates = $docs->query();
               $update = $updates->update()
                ->set($survey)
                ->where(['id' => $id])
                ->execute();
                if($update)
            	   echo "OK";
                   
                   
                  
        } else {
            $doc = $docs->newEntity($_POST);
    		if ($this->request->is('post')) {
    			if ($docs->save($doc)) {
    			      echo "OK";
    				//$this->Flash->success('The Survey has been sent.');
                    	//return $this->redirect('/documents/index');
    			} else {
    				//$this->Flash->error('Survey not sent. Please try again.');
                    //return $this->redirect('/feedbacks/add');
    			}
    		}
        }
		//$this->set(compact('client'));
        $this->render('add');
        die();
    }
    public function edit($id = NULL) {
        
        $docs = TableRegistry::get('Documents');
        $query = $docs->find()->where(['id'=>$id]);
        $feeds = $query->first();
        
        $doc = $docs->newEntity($_POST);
		if ($this->request->is('post')) {
		      $updates = $docs->query();
               $update = $updates->update()
                ->set($_POST)
                ->where(['id' => $id])
                ->execute();
			if ($update) {
				$this->Flash->success($this->Trans->getString("flash_feedbacksent"));
                	return $this->redirect('/documents/index');
			} else {
				$this->Flash->error($this->Trans->getString("flash_feedbacknotsent"));
                return $this->redirect('/feedbacks/add');
			}
		}
        $this->set(compact('feeds'));
        $this->render('add');
    }
    
  
}

?>