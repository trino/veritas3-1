<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;
use Cake\Network\Email\Email;

class ClientApplicationController extends AppController {

    public $paginate = [
        'limit' => 10,
        'order' => ['id' => 'desc']
    ];

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Settings');
        $this->loadComponent('Manager');
        $this->loadComponent('Document');
        $this->loadComponent('Mailer');
        $this->loadComponent('Trans');
        $this->layout = 'application';
        $this->request->session()->write('Profile.language','English');

        //$this->Settings->verifylogin($this, "clients");
    }
    
    public function index()
    {
        $client = TableRegistry::get('clients')->find();
        $this->set('client',$client);
    }
    public function apply($id)
    {
        $sub = TableRegistry::get('client_application_sub_order')->find()->where(['sub_id IN (SELECT subdoc_id FROM clientssubdocument WHERE client_id = '.$id.' AND display_application = 1)','client_id'=>$id])->order(['display_order'=>'ASC']);
        $client = TableRegistry::get('clients')->find()->where(['id'=>$id])->first();
        $this->set('client',$client);
        $this->set('subd',$sub);
        $this->set('Manager',$this->Manager);
        $this->set('did','0');
        $this->set('doc',TableRegistry::get('subdocuments')->find()->all());
        
    }
    public function getForm($id)
    {
        //echo $id;
        $client = TableRegistry::get('subdocuments')->find()->where(['id'=>$id])->first();
        $q = $client->form;
        $this->response->body($q);
            return $this->response;
            die();
    }
    public function getSub($id)
    {
        //echo $id;
        $client = TableRegistry::get('subdocuments')->find()->where(['id'=>$id])->first();
        $q = $client;
        $this->response->body($q);
            return $this->response;
            die();
    }
    function get_settings() {
            $settings = TableRegistry::get('settings');
            $query = $settings->find();

            $q = $query->first();
            $this->response->body($q);
            return $this->response;
            
        }
    function saveDriver($id=0)
    {
        
        $model = TableRegistry::get('profiles');
        if($id==0){
        $profile = $model->newEntity($_POST);
        $model->save($profile);
        echo $pid = $profile->id;
        }
        else
        {
            $arrs = $_POST;
            unset($arrs['client_id']);
            unset($arrs['c_id']);
            unset($arrs['document_type']);
            $model->query()->update()
                ->set($_POST)
                ->where(['id' => $id])
                ->execute();
                echo $pid = $id;
        }
        
        $cid = $_POST['c_id'];
        $client = TableRegistry::get('clients');
        $profile_ids = $client->find()->select('profile_id')->where('id',$cid)->first();
        $arr['profile_id'] = $profile_ids->profile_id.",".$pid;
        $query2 = $client->query();
                    $query2->update()
                        ->set($arr)
                        ->where(['id' => $cid])
                        ->execute();
        die();
    }
    public function savedoc($cid = 0, $did = 0) {
        $this->set('doc_comp',$this->Document);
        $this->loadComponent('Mailer');
        //$this->Mailer->handleevent("documentcreatedb", array("site" => "","email" => "roy", "company_name" => "", "username" => $this->request->session()->read('Profile.username'), "id" => $did, "path" => "", "profile_type" => ""));

        $ret = $this->Document->savedoc($this->Mailer, $cid,$did);
        //$this->Mailer->handleevent("documentcreated", $ret);
        die();
    }

    public function savePrescreening() {
        $this->Document->savePrescreening();
        die;
    }

    public function savedDriverApp($document_id = 0, $cid = 0){
        $this->Document->savedDriverApp($document_id,$cid);
        die;
    }


    public function savedDriverEvaluation($document_id = 0, $cid = 0){
        $this->Document->savedDriverEvaluation($document_id,$cid);
        die();
    }

    public function savedMeeOrder($document_id = 0, $cid = 0){
        $this->Document->savedMeeOrder($document_id,$cid);
        die();
    }

    function saveEmployment($document_id = 0, $cid = 0){
        $this->Document->saveEmployment($document_id,$cid);
        die();
    }

    function saveEducation($document_id = 0, $cid = 0){
        $this->Document->saveEducation($document_id,$cid);
        die();
    }
       public function mee_attach($order_id,$cid){
        $this->Document->mee_attach($cid,$order_id);
        die();
    }
        function fileUpload($id = "") {
        // print_r($_POST);die;
        if (isset($_FILES['myfile']['name']) && $_FILES['myfile']['name']) {
            $arr = explode('.', $_FILES['myfile']['name']);
            $ext = end($arr);
            $rand = rand(100000, 999999) . '_' . rand(100000, 999999) . '.' . $ext;
            $allowed = array('jpg', 'jpeg', 'png', 'bmp', 'gif', 'pdf', 'doc', 'docx', 'txt', 'xlsx', 'xls', 'csv', 'mp4');
            $check = strtolower($ext);
            if (in_array($check, $allowed)) {
                if (isset($_POST['type'])) {
                    $doc_type = $_POST['type'];
                }
                $destination = WWW_ROOT . 'attachments';

                if (!file_exists($destination)){
                    mkdir($destination, 0777, true);
                }

                $source = $_FILES['myfile']['tmp_name'];
                move_uploaded_file($source, $destination . '/' . $rand);
                $saveData = array();
                if (isset($_POST['order_id'])) {
                    $saveData['order_id'] = $_POST['order_id'];
                }
                $saveData['path'] = $rand;
                echo $rand;
            } else {
                echo 'error';
            }
        }
        die();
    }
    
    function application_employment($cid, $did){
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
                    
                    $arr['user_id'] = $_GET['user_id'];
                    $arr['uploaded_for'] = $_GET['user_id'];
                    $arr['created'] = date('Y-m-d H:i:s');
                    $docs = TableRegistry::get('Documents');
                    $doc = $docs->newEntity($arr);

                    if ($docs->save($doc)) {
                        $doczs = TableRegistry::get('application_for_employment_gfs');
                        $ds['document_id'] = $doc->id;

                        foreach($_POST as $k=>$v) {
                            $ds[$k]=$v;
                        }
                        $docz = $doczs->newEntity($ds);
                        $doczs->save($docz);
                        $did = $doc->id;
                        if(isset($_POST['attach_doc'])) {
                            $model = $this->loadModel('DocAttachments');
                            $model->deleteAll(['document_id'=> $did]);
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
                        $this->success(True, $draft);
                    } else {
                        $this->success(False, $draft);
                    }

                } else {
                    $docs = TableRegistry::get('Documents');
                    $query2 = $docs->query();
                    $query2->update()
                        ->set($arr)
                        ->where(['id' => $did])
                        ->execute();
                    $this->loadModel('application_for_employment_gfs');
                    $this->application_for_employment_gfs->deleteAll(['document_id' => $did]);
                    $doczs = TableRegistry::get('application_for_employment_gfs');
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
                    $this->success(True, $draft, True, $did);
                }
            } else {
                $arr['document_id'] = 0;
                if (!isset($_GET['order_id'])) {
                    $arr['order_id'] = $did;
                } else {
                    $arr['order_id'] = $_GET['order_id'];
                    $did = $_GET['order_id'];
                }
                $arr['document_id'] = 0;
                if (isset($_POST['uploaded_for'])){
                    $uploaded_for = $_POST['uploaded_for'];
                }else{
                    $uploaded_for = '';
                }
                $for_doc = array('document_type'=>'application_for_employment_gfs','sub_doc_id'=>18,'order_id'=>$arr['order_id'],'user_id'=>'','uploaded_for'=>$uploaded_for);
                $this->Document->saveDocForOrder($for_doc);

                $doczs = TableRegistry::get('application_for_employment_gfs');
                $check = $doczs->find()->where(['order_id'=>$did])->first();
                unset($doczs);
                if (!$check) {
                    $ds['order_id'] = $did;
                    $ds['document_id'] = 0;
                    $doczs = TableRegistry::get('application_for_employment_gfs');
                    foreach($_POST as $k=>$v) {
                        $ds[$k]=$v;
                    }
                    $docz = $doczs->newEntity($ds);
                    $doczs->save($docz);
                    unset($doczs);
                } else {
                    $this->loadModel('application_for_employment_gfs');
                    $this->application_for_employment_gfs->deleteAll(['order_id' => $did]);
                    $doczs = TableRegistry::get('application_for_employment_gfs');
                    $ds['order_id'] = $did;

                    foreach($_POST as $k=>$v) {
                        $ds[$k]=$v;
                    }
                    $docz = $doczs->newEntity($ds);
                    $doczs->save($docz);
                    unset($doczs);
                }
               
            }
        }
        die();
    }
    function success($Success=true, $draft = "", $redirect = true, $DID = ""){
        $Document = $this->Settings->get_settings()->document;
        $saved = "saved";
        if($DID){$saved = "updated";}
        if($Success) {
            $this->Flash->success($this->Trans->getString("flash_doc" . $saved));
        }else{
            $this->Flash->error($this->Trans->getString("flash_docnot" . $saved));
        }
        if($redirect){
            $this->redirect(array('action' => 'index'.$draft));
        }
    }



}