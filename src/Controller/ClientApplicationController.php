<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;
use Cake\Network\Email\Email;

class ClientApplicationController extends AppController {

    public $paginate = [
        'limit' => 20,
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
    
    public function index() {
        $client = TableRegistry::get('clients')->find();
        $this->set('client',$client);
    }

    public function apply($slug) {
        $client = TableRegistry::get('clients')->find()->where(['slug'=>$slug])->first();
        $id = $client->id;
        $sub = TableRegistry::get('client_application_sub_order')->find()->where(['sub_id IN (SELECT subdoc_id FROM clientssubdocument WHERE client_id = '.$id.' AND display_application = 1)','client_id'=>$id])->order(['display_order'=>'ASC']);
        //$client = TableRegistry::get('clients')->find()->where(['id'=>$id])->first();
        $this->set('client',$client);
        $this->set('subd',$sub);
        $this->set('Manager',$this->Manager);
        $this->set('did','0');
        $this->set('doc',TableRegistry::get('subdocuments')->find()->all());
    }

    public function getForm($id) {
        //echo $id;
        $client = TableRegistry::get('subdocuments')->find()->where(['id'=>$id])->first();
        $q = $client->form;
        $this->response->body($q);
            return $this->response;
            die();
    }

    public function getSub($id) {
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

    function saveDriver($id=0) {
        $model = TableRegistry::get('profiles');
        if($id==0 && $_POST['driver_id']==""){
            $_POST["profile_type"] = 5;
            $profile = $model->newEntity($_POST);
            $model->save($profile);
            echo $pid = $profile->id;
        }
        elseif($_POST['driver_id']!='')
        {
            $arrs = $_POST;
            unset($arrs['client_id']);
            unset($arrs['c_id']);
            unset($arrs['document_type']);
            unset($arrs['driver_id']);
            $model->query()->update()
                ->set($arrs)
                ->where(['id' => $_POST['driver_id']])
                ->execute();
                echo $pid = $_POST['driver_id'];
        } else {
            $arrs = $_POST;
            unset($arrs['client_id']);
            unset($arrs['c_id']);
            unset($arrs['document_type']);
            unset($arrs['driver_id']);

            $model->query()->update()
                ->set($arrs)
                ->where(['id' => $id])
                ->execute();
                echo $pid = $id;
        }
        $cid = $_POST['c_id'];
        $this->Manager->assign_profile_to_client($pid, $cid);
        die();
    }

    public function savedoc($cid = 0, $did = 0) {
        $this->set('doc_comp',$this->Document);
        $this->loadComponent('Mailer');
        //$this->Mailer->handleevent("documentcreatedb", array("site" => "","email" => "roy", "company_name" => "", "username" => $this->request->session()->read('Profile.username'), "id" => $did, "path" => "", "profile_type" => ""));

        $ret = $this->Document->savedoc($this->Mailer, $cid, $did, false);
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

    public function notify($document_id, $client_id){
        $Emails = $this->Document->enum_profiles_permission($client_id, "clientapp_emails", "email"); //$this->Document->enum_emails_canorder($cid);
        //$Emails[] = "super";
        $ProfileID = $this->Manager->get_entry("documents", $document_id)->user_id;
        $URL = LOGIN . 'profiles/view/' . $ProfileID;
        $this->Mailer->handleevent("application", array("email" => $Emails, "documentid" => $document_id, "clientid" => $client_id, "path" => $URL));
        $this->Document->sendEmailForProcesses($document_id, "documents", true);
    }

    public function savedMeeOrder($document_id = 0, $client_id = 0){
        //enum_all($Table, $conditions = "", $SortBy = false, $Direction = "ASC")
        $Last = $this->Manager->enum_all("client_application_sub_order", array( "client_id" => $client_id ),  "display_order", "DESC")->first()->sub_id;
        if(isset($_GET["document"]) && ($_GET["document"] == "Consent Form" || $Last == $document_id)) {
           $this->notify($document_id,$client_id);
        }

        $this->Document->savedMeeOrder($document_id,$client_id);
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
                    if(isset($_GET['uploaded_for']))
                        $arr['uploaded_for']= $_GET['uploaded_for'];
                    else
                        $arr['uploaded_for'] = $_GET['user_id'];
                    //die($arr['uploaded_for']);
                    $arr['created'] = date('Y-m-d H:i:s');
                    //var_dump($arr);
                    //die();
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
                    $doczs = TableRegistry::get('application_for_employment_gfs');
                    $app = $doczs->find()->where(['document_id'=>$did])->first();
                    //debug($app);
                    if($app->gfs_signature!=''&& $_POST['gfs_signature']!= $app->gfs_signature)
                        @unlink(WWW_ROOT."canvas/".$app->gfs_signature);
                    $this->application_for_employment_gfs->deleteAll(['document_id' => $did]);
                    
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


     public function getJsonFields($driver)
        {
            $profiles = TableRegistry::get('Profiles')->find()->where(['id'=>$driver])->first();
            $arr = array('username','email','password','fname','mname','lname','title','phone','gender','dob','placeofbirth','street','city','province','postal','driver_license_no','driver_province','expiry_date','sin');
            foreach($arr as $f)
            {
                if($profiles)
                $arr2[$f] = $profiles->$f;
            }            
            $fields = TableRegistry::get('driver_fields')->find()->all();
            foreach($fields as $f)
            {
                //echo $f->email;echo '<br/>';
                //echo $f2;echo "<br/>";
                foreach($arr as $f2)
                {
                    $temp = $f->$f2;
                    if($f2=='expiry_date')
                    {
                        $exp = explode('-',$temp);
                        if(is_array($exp) && count($exp)>2)
                        $temp = $exp[2].'/'.$exp[1].'/'.$exp[0];
                    }
                    
                    if($temp){
                    
                    $arr3[$f2][] = $temp;
                    }
                    //echo '<br/>';}
                    /*var_dump($f);die();
                    if(!$f->f2)
                    continue;
                    echo $f2;echo "<br/>";
                    echo $f->f2;echo "<br/>";die();
                    //echo $f2;
                    if($f->f2)
                    $arr3[$f2][] = $f->f2; */
                }
                //die();
            }
            foreach($arr3 as $k=>$a)
            {
                foreach($a as $v)
                {
                    $final[$v] = $arr2[$k];
                }
            }
            echo json_encode($final);die();
            
        }
        
        public function getJsonPrevious($driver=0,$sub=0)
        {
            
            
            
            
            $subdoc = TableRegistry::get('subdocuments')->find()->where(['id'=>$sub])->first();
            $table_name = $subdoc->table_name;
            
            $table_arr = explode('_',$table_name);
            $table = '';            
            foreach($table_arr as $ta)
            {
                $table = $table.ucfirst($ta);
            }
            //echo ucfirst($table);die();
            
            $this->loadModel($table);
            $arr = (array)$this->$table->schema();
            $i=0;
            foreach($arr as $a)
            {
                $i++;
                if($i==2)
                {
                    $array = $a;
                }
                
            }
            foreach($array as $key=>$v)
            {
                
                $fields[] = $key;
            }
            
            //var_dump($fields);die();
            $q = TableRegistry::get($table_name)->find()->where(['document_id IN (SELECT id FROM documents WHERE uploaded_for = '.$driver.') OR order_id IN (SELECT id FROM orders WHERE uploaded_for = '.$driver.')'])->order(['id'=>'DESC'])->first();
            //echo $q->id;
            foreach($fields as $f)
            {
                if($q){
                $temp = $q->$f;
                if(str_replace('date','',$f)!=$f)
                {
                        $exp = explode('-',$temp);
                        if(is_array($exp) && count($exp)>2)
                        $temp = $exp[2].'/'.$exp[1].'/'.$exp[0];
                    
                }
                
                $return[$f] = $temp;
                }
            }
            
            if(isset($return) && $return)
            echo json_encode($return);
            else
            echo json_encode(array());
            die(); 
        }
        public function getfullname($id = 0)
        {
            $q = TableRegistry::get('profiles')->find()->where(['id'=>$id])->first();
            if($q){
            $name = $q->fname.' '.$q->mname.' '.$q->lname;
            $name = trim($name);
            }
            else
            $name = '';
            $this->response->body($name);
            return $this->response;
            die(); 
        }
        public function sendEmailForProcesses($id)
        {
            $doc = $this->Document->sendEmailForProcesses($id,"documents");
            die();
        }

}