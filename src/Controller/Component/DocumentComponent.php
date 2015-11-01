<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\View\Helper\SessionHelper;
    

class DocumentComponent extends Component{
    public $components = array('Manager');

	function fixsubmittedfor(){
        $Table = TableRegistry::get("documents");
        //debug($Table->query("UPDATE `documents` SET uploaded_for = user_id WHERE uploaded_for = 0"));
        $Documents =  $Table->find()->where(['OR'=> array('uploaded_for'=>null, 'uploaded_for'=>0)]);
        foreach($Documents as $Document){
            $Table->query()->update()
                ->set(['uploaded_for'=>$Document->user_id])
                ->where(['id' => $Document->id])
                ->execute();
        }
    }
	
    public function savedoc($Mailer, $cid = 0, $did = 0, $emailenabled = True){
             $controller = $this->_registry->getController();
              $settings = TableRegistry::get('settings');
              $setting = $settings->find()->first();
//         echo "<pre>";print_r($_POST);
            if (!isset($_GET['document'])) {
                // saving in order table
                $txtfile = '';
                $orders = TableRegistry::get('orders');
                if(!$did || $did == '0'){
                $arr['title'] = 'order_' . $_POST['uploaded_for'] . '_' . date('Y-m-d H:i:s');
                $txtfile = $txtfile.'Title: '.$arr['title']."\n";}
                $arr['uploaded_for'] = $_POST['uploaded_for'];
                $txtfile = $txtfile.'Uploaded for: User Id# '.$arr['uploaded_for']."\n";
                $txtfile = $txtfile.'Uploaded By: User Id# '.$controller->request->session()->read('Profile.id')."\n";
                $sig = explode('/',$_POST['recruiter_signature']);
                if(isset($_GET['order_type'])){
                $arr['order_type'] = $_GET['order_type'];
                $txtfile = $txtfile.'Order Type: '.$arr['order_type']."\n";
                }
                if(isset($_GET['forms'])){
                $arr['forms'] = $_GET['forms'];
                $txtfile = $txtfile.'Product selected: Numbers#'.$arr['forms']."\n";
                }
                $arr['recruiter_signature'] = end($sig);
                if(!isset($_GET['draft']) || (isset($_GET['draft']) && !$_GET['draft'])){
                
                $myfile = fopen(APP."../webroot/order_submitted/Order_".date('Y_m_d_h_i_s').".txt", "w") or die("Unable to open file!");
                
                fwrite($myfile, $txtfile);
                fclose($myfile);
                }
                //echo APP."../Order_".date('Y_m_d_h_i_s').".txt";
                //die('here');
                
                
                if($did) {
                    $o_model = TableRegistry::get('Orders');
                    $orde = $o_model->find()->where(['id' => $did])->first();
                    if($orde) {
                        $dr = $orde->draft;
                        if($dr=='0' || !$dr){
                            $dr = 0;
                            //$this->Flash->success('Order submitted successfully');
                        } else{
                            $dr =1;
                            //$this->Flash->success('Order saved as draft');
                        }
                    } else{
                        $dr = 1;
                        //$this->Flash->success('Order saved as draft');
                    }
                } else{
                    $dr = 1;
                    //$this->Flash->success('Order saved as draft');
                }
                //$this->set('dr',$dr);
                if (isset($_GET['draft']) && $_GET['draft']){
                    if($dr){
                        $arr['draft'] = 1;
                        //if(isset($_POST['conf_date']))
                        //$this->Flash->success('Order saved as draft');

                    } else{
                        $arr['draft'] = 0;
                        //$this->Flash->success('Order submitted successfully');
                    }
                } else{
                    //if(!$dr)
                    $arr['draft'] = 0;
                    //$this->Flash->success('Order submitted successfully');
                }
                    
                $arr['client_id'] = $cid;
                if (isset($_POST['division'])) {
                    $arr['division'] = urldecode($_POST['division']);
                }
                $arr['conf_recruiter_name'] = urldecode($_POST['conf_recruiter_name']);
                if(isset($_POST['conf_driver_name'])) {
                    $arr['conf_driver_name'] = urldecode($_POST['conf_driver_name']);
                }else{
                    $arr['conf_driver_name'] ="";
                }
                $arr['conf_date'] = urldecode($_POST['conf_date']);
                //$arr['order_type'] = $_POST['sub_doc_id'];
                if(!$did || $did == '0') {
                    $arr['created'] = date('Y-m-d H:i:s');
                }
                if (!$did || $did == '0') {
                    $arr['user_id'] = $controller->request->session()->read('Profile.id');
                    $order = $orders->newEntity($arr);

                    if ($orders->save($order)) {
                        //$this->Flash->success('Client saved successfully.');
                        
                        if($arr['draft']==0) {
                            $docus = TableRegistry::get('Documents');
                            $queri3 = $docus->query();
                            $queri3->update()
                                ->set(['draft'=>0])
                                ->where(['order_id' => $order->id])
                                ->execute();
                        }
                        
                        echo $order->id;
                    } else {
                        //$this->Flash->error('Client could not be saved. Please try again.');
                        //echo "e";
                    }
                } else {
                    $query2 = $orders->query();
                    $query2->update()
                        ->set($arr)
                        ->where(['id' => $did])
                        ->execute();
                    //$this->Flash->success('Client saved successfully.');

                    if($arr['draft']==0) {
                                $path = $this->getUrl();
                                $get_client = TableRegistry::get('Clients');
                                $gc = $get_client->find()->where(['id' => $cid])->first();
                                $client_name = $gc->company_name;
                                $assignedProfile = $this->getAssignedProfile($cid);
                                //var_dump($assignedProfile);die();
                                if($assignedProfile) {
                                    $profile = $this->getProfilePermission($assignedProfile->profile_id, 'orders');
                                    // var_dump($profile);die();
                                    if($profile) {
                                        foreach($profile as $p) {
                                            
                                            $pro_query = TableRegistry::get('Profiles');
                                            $email_query = $pro_query->find()->where(['super' => 1])->first();
                                            $em = $email_query->email;
                                            $user_id = $controller->request->session()->read('Profile.id');
                                            $uq = $pro_query->find('all')->where(['id' => $user_id])->first();
                                            if ($uq->profile_type) {
                                                $u = $uq->profile_type;
                                                $type_query = TableRegistry::get('profile_types');
                                                $type_q = $type_query->find()->where(['id'=>$u])->first(); 
                                                $ut = $type_q->title;
                                            } else {
                                                $ut = '';
                                            }
//$arr['document_type'] = urldecode($_GET['document']);
                                            $username =   $user_id = $controller->request->session()->read('Profile.username');
                                            $ret = array("site" => $setting->mee,"email" => $em, "company_name" => $client_name, "username" => $username, "id" => $did, "path" => $path, "profile_type" => $ut, "place" => 1);
                                            if($emailenabled) {$Mailer->handleevent("documentcreated", $ret);}
/*
                                            $from = array('info@'.$path => $setting->mee);
                                            $to = $p;
                                             $sub = 'Order Submitted';
                                            $msg = 'A new order has been created in '.$path.'<br />
                                            <br/>

                                            By: '.$uq->username.' (Profile Type: '.$ut.')<br/> Date : '.date('Y-m-d H:i:s').'<br/><br /> Client Name: ' . $client_name.'<br/> For: '.$p.'<br /><br /> Regards,<br />The '.$setting->mee.' Team';
                                            // $controller->Mailer->sendEmail($from, $to, $sub, $msg);
*/
                                        }
                                    }
                                } else {
                                    $ut = $this->getprofiletype();
                                    $username =   $user_id = $controller->request->session()->read('Profile.username');
                                    if($emailenabled) {
                                        $ret = array("site" => $setting->mee, "email" => "super", "company_name" => $client_name, "username" => $username, "id" => $did, "path" => $path, "profile_type" => $ut, "place" => 2);
                                        $Mailer->handleevent("documentcreated", $ret);
                                    }
                                }

                            $docus = TableRegistry::get('Documents');
                            $queri3 = $docus->query();
                            $queri3->update()
                                ->set(['draft'=>0])
                                ->where(['order_id' => $did])
                                ->execute();
                        }
                        
                           echo $did;
                           if (!is_dir(APP.'../webroot/orders/order_'.$did)) {
                               mkdir(APP . '../webroot/orders/order_' . $did, 0777);
                           }
                }

            } else {
                $docs = TableRegistry::get('Documents');
                if (isset($_GET['draft']) && $_GET['draft']){
                    $arr['draft'] = 1;
                    //$controller->Flash->success('Document saved as draft');
                    }
                else{
                    $arr['draft'] = 0;
                    //$controller->Flash->success('Document submitted successfully');
                    }
                $arr['sub_doc_id'] = $_POST['sub_doc_id'];
                if (isset($_POST['uploaded_for']))
                    $arr['uploaded_for'] = $_POST['uploaded_for'];

                $arr['client_id'] = $cid;
                $arr['document_type'] = urldecode($_GET['document']);
                    
                //$arr['conf_recruiter_name'] = $_POST['conf_recruiter_name'];
                //$arr['conf_driver_name'] = $_POST['conf_driver_name'];
                //$arr['conf_date'] = $_POST['conf_date'];
                
                if ((!$did || $did == '0') && ($arr['sub_doc_id'] < 7 || $arr['sub_doc_id'] == 15 || $arr['sub_doc_id'] == 9 || $arr['sub_doc_id'] == 10))
                {
                    $arr['created'] = date('Y-m-d H:i:s');
                    if($controller->request->session()->read('Profile.id'))
                        $arr['user_id'] = $controller->request->session()->read('Profile.id');
                   
                   
                    $doc = $docs->newEntity($arr);
                    
                    if ($docs->save($doc)) {
                       
                        $path = $this->getUrl();
                        $get_client = TableRegistry::get('Clients');
                        $gc = $get_client->find()->where(['id' => $cid])->first();
                        $client_name = $gc->company_name;
                        $assignedProfile = $this->getAssignedProfile($cid);
                       
                        if($assignedProfile)
                        {
                            if(!isset($_GET['draft']) && !($_GET['draft']))
                            {
                                $profile = $this->getProfilePermission($assignedProfile->profile_id, 'document');
                                if($profile)
                                {
                                    foreach($profile as $p)
                                    {
                                        $pro_query = TableRegistry::get('Profiles');
                                        $email_query = $pro_query->find()->where(['super' => 1])->first();
                                        $em = $email_query->email;
                                        $user_id = $controller->request->session()->read('Profile.id');
                                        $uq = $pro_query->find('all')->where(['id' => $user_id])->first();
                                        if (isset($uq->profile_type))
                                          {
                                            $u = $uq->profile_type;
                                            $type_query = TableRegistry::get('profile_types');
                                            $type_q = $type_query->find()->where(['id'=>$u])->first(); 
                                            $ut = $type_q->title;
                                          }
                                          //$path = 'https://isbmeereports.com/documents/view/'.$cid;
                                        if($emailenabled) {
                                            $ret = array("site" => $setting->mee, "email" => $p, "company_name" => $client_name, "username" => $uq->username, "id" => $did, "path" => $path, "profile_type" => $ut, "place" => 3);
                                            $Mailer->handleevent("documentcreated", $ret);
                                        }/*
                                        $from = array('info@'.$path => $setting->mee);
                                        $to = $p;
                                         $sub = 'Document Submitted';
                                        $msg = 'A new document has been created in '.$path.'<br /><br />
                                        Username : '.$uq->username.'<br/>Profile Type : '.$ut.'<br/> Date : '.date('Y-m-d H:i:s').'<br/>Client Name: ' . $client_name.'<br/> Document type : '.$arr['document_type'].'<br /><br />Regards,<br />The '.$setting->mee.' Team';
          */
                                         //$controller->Mailer->sendEmail($from, $to, $sub, $msg);
                                    }
                                }
                            }
                            else 
                            {
                                
                            }
                        }
                        //$this->Flash->success('Client saved successfully.');
                        echo $doc->id;
                        if(!isset($_GET['draft']) || !($_GET['draft'])){
                            $pro_query = TableRegistry::get('Profiles');
                            $email_query = $pro_query->find()->where(['super' => 1])->first();
                            $em = $email_query->email;
                            $user_id = $controller->request->session()->read('Profile.id');
                            $uq = $pro_query->find('all')->where(['id' => $user_id])->first();
                            if ($uq && $uq->profile_type)
                              {
                                $u = $uq->profile_type;
                                $type_query = TableRegistry::get('profile_types');
                                $type_q = $type_query->find()->where(['id'=>$u])->first(); 
                                if($type_q)
                                $ut = $type_q->title;
                                else
                                $ut = '';
                                
                              }
                              else
                              $ut = '';

                            //$this->Mailer->handleevent("documentcreated", array("site" => $setting->mee,"email" => $em, "company_name" => $client_name, "username" => $uq->username, "id" => $did, "path" => $path, "profile_type" => $ut));
                            /*
                            $from = array('info@'.$path => $setting->mee);
                            $to = $em;
                             $sub = 'Document Submitted';
                            $msg = 'A new document has been created in '.$path.'<br /><br />

                            Username : '.$uq->username.'<br/>Profile Type : '.$ut.'<br/> Date : '.date('Y-m-d H:i:s').'<br/>Client Name: ' . $client_name.'<br/> Document type : '.$arr['document_type'].'<br/> <br /> Regards,<br />The '.$setting->mee.' Team';
*/
                            // $controller->Mailer->sendEmail($from, $to, $sub, $msg);
                             }
                        if(isset($_POST['attach_doc']))
                        {
                            $model = $controller->loadModel('AttachDocs');
                            $model->deleteAll(['doc_id'=>$doc->id]);
                            $client_docs = explode(',',$_POST['attach_doc']);
                            foreach($client_docs as $d)
                            {
                                if($d != "")
                                {
                                    $attach = TableRegistry::get('attach_docs');
                                    $ds['doc_id']= $doc->id;
                                    $ds['file'] =$d;
                                     $att = $attach->newEntity($ds);
                                     $attach->save($att);
                                    unset($att);
                                }
                            }
                        }
                    } else {
                        //$this->Flash->error('Client could not be saved. Please try again.');
                        //echo "e";
                    }

                } else
                {
                   
                    $query2 = $docs->query();
                    $query2->update()
                        ->set($arr)
                        ->where(['id' => $did])
                        ->execute();
                        $path = $this->getUrl();
                        $get_client = TableRegistry::get('Clients');
                        $gc = $get_client->find()->where(['id' => $cid])->first();
                        $client_name = $gc->company_name;
                        $assignedProfile = $this->getAssignedProfile($cid);
                     
                        if($assignedProfile)
                        {
                            if(!isset($_GET['draft']) || !($_GET['draft']))
                            {
                                $profile = $this->getProfilePermission($assignedProfile->profile_id, 'document');
                                
                                if($profile)
                                {
                                    foreach($profile as $p)
                                    {
                                        //these will get the same results every time, why are they in a loop?
                                        $pro_query = TableRegistry::get('Profiles');
                                        $email_query = $pro_query->find()->where(['super' => 1])->first();
                                        $em = $email_query->email;

                                        $user_id = $p;//shouldn't this be $p?
                                        $uq = $pro_query->find('all')->where(['id' => $user_id])->first();
                                        if (isset($uq->profile_type))
                                          {
                                            $u = $uq->profile_type;
                                            $type_query = TableRegistry::get('profile_types');
                                            $type_q = $type_query->find()->where(['id'=>$u])->first(); 
                                            $ut = $type_q->title;
                                          }
                                          else
                                            $ut = '';

                                        //
                                          $path = 'https://isbmeereports.com/documents/view/'.$cid;
                                        /*
                                        $from = array('info@'.$path => $setting->mee);
                                        $to = $p;
                                        $sub = 'Document Submitted';
                                        $msg = 'A new document has been created in '.$path.'<br /><br />
                                        Username : '.$uq->username.'<br/>Profile Type : '.$ut.'<br/> Date : '.date('Y-m-d H:i:s').'<br/>Client Name: ' . $client_name.'<br/> Document type : '.$arr['document_type'].'<br /><br />Regards,<br />The '.$setting->mee.' Team';
            
                                        //$controller->Mailer->sendEmail($from, $to, $sub, $msg);
*/
                                        if($emailenabled) {
                                            $username = $user_id = $controller->request->session()->read('Profile.username');
                                            $ret = array("site" => $setting->mee, "email" => $p, "company_name" => $client_name, "username" => $username, "id" => $did, "path" => $path, "profile_type" => $ut, "place" => 4);
                                            $Mailer->handleevent("documentcreated", $ret);
                                        }
                                    }
                                }
                            }
                        }
                        /*if(isset($_POST['attach_doc']))
                        {
                            $model = $controller->loadModel('AttachDocs');
                            $model->deleteAll(['document_id'=>$did]);
                            $client_docs = explode(',',$_POST['attach_doc']);
                            foreach($client_docs as $d)
                            {
                                if($d != "")
                                {
                                    $attach = TableRegistry::get('attach_docs');
                                    $ds['doc_id']= $did;
                                    $ds['file'] =$d;
                                     $att = $attach->newEntity($ds);
                                     $attach->save($att);
                                    unset($att);
                                }
                            }
                        }*/
                    echo $did;
                }
                
            }
           // return $ret;
            die();
        }

        public function getprofiletype($user_id=""){
            if(!$user_id) {
                $controller = $this->_registry->getController();
                $user_id = $controller->request->session()->read('Profile.id');
            }
            $u = TableRegistry::get('Profiles')->find('all')->where(['id' => $user_id])->first()->profile_type;
            return TableRegistry::get('profile_types')->find()->where(['id'=>$u])->first()->title;
        }

        public function saveDocForOrder($arr) {
                $docs = TableRegistry::get('Documents');
                $orders = TableRegistry::get('Orders');
                $ord = $orders->find()->where(['id'=>$arr['order_id']])->first();
                $arr['draft'] = $ord->draft;
                
                
                unset($arr['uploaded_for']);
                $arr['uploaded_for'] = $ord->uploaded_for;
                unset($arr['user_id']);
                $arr['user_id'] = $ord->user_id;
                //unset($arr['client_id']);
                $arr['client_id'] = $ord->client_id;
                    
                
                $arr['created'] = $ord->created;
                if($arr['document_type']!='Employment Verification' && $arr['document_type']!='Education Verification' && $arr['document_type']!='Consent Form'){
                $doc = $docs->find()->where(['order_id'=>$arr['order_id'],'sub_doc_id'=>$arr['sub_doc_id']])->first();
                }
                else{
                    
                $doc = $docs->find()->where(['document_type'=>$arr['document_type'],'order_id'=>$arr['order_id'],'sub_doc_id'=>$arr['sub_doc_id']])->first();
                }
                if(!$doc)
                {
                    
                    $doc = $docs->newEntity($arr);
                    $docs->save($doc);
                }
                else
                {
                    //die('there');
                    $query2 = $docs->query();
                    $query2->update()
                        ->set($arr)
                        ->where(['id' => $doc->id])
                        ->execute();
                }
               return true;
        }
        public function savePrescreening()
        {
            $controller = $this->_registry->getController();
            $prescreen = TableRegistry::get('pre_screening');
            $arr['client_id'] = $_POST['cid'];
            $arr['user_id'] = $controller->request->session()->read('Profile.id');
            if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                if(!isset($_GET['order_id']))
                $arr['order_id'] = $_POST['order_id'];
                else
                $arr['order_id'] = $_GET['order_id'];
                $arr['document_id'] = 0;
                if (isset($_POST['uploaded_for']))
                    $uploaded_for = $_POST['uploaded_for'];
                else
                    $uploaded_for = '';
                $for_doc = array('document_type'=>'Pre-Screening','sub_doc_id'=>1,'order_id'=>$arr['order_id'],'user_id'=>$arr['user_id'],'uploaded_for'=>$uploaded_for);
                $this->saveDocForOrder($for_doc);
            } else {
                $arr['document_id'] = $_POST['order_id'];
                $arr['order_id'] = 0;
            }
            

            // checking db if order id exits in this table
            // first delete
            // $del_prescreen = $prescreen->get(['document_id'=>$_POST['document_id']]);
            $del = $prescreen->query();
            if (!isset($_GET['document']) || isset($_GET['order_id'])){
                if(!isset($_GET['order_id']))
                $del->delete()->where(['order_id' => $_POST['order_id']])->execute();
                else
                $del->delete()->where(['order_id' => $_GET['order_id']])->execute();
                }
            else
                $del->delete()->where(['document_id' => $_POST['order_id']])->execute();

            foreach (explode("&", $_POST['inputs']) as $data) {
                $input = explode("=", $data);
                $input[0];
                if ($input[0] == "document_type" || $input[0] == 'attach_doc[]' || $input[0] == 'attach_doc%5B%5D') {
                    if ($input[0] == 'attach_doc[]' || $input[0] == 'attach_doc%5B%5D'){
                        $atta = urldecode($input[1]);
                        $att[] = trim($atta);
                        
                        }
                    continue;
                }
                if ($input[1] != '') {

                    $arr[$input[0]] = urldecode($input[1]);
                }

                //echo $data."<br/>";
            }
            if (!isset($att))
                $att = null;
            //var_dump($att);
            if (isset($att)) {
                $count = 0;
                foreach ($att as $at) {
                    $count++;
                    if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                        if(!isset($_GET['order_id']))
                        $saveData['order_id'] = $_POST['order_id'];
                        else
                        $saveData['order_id'] = $_GET['order_id'];
                        $saveData['document_id'] = 0;
                    } else {
                        $saveData['document_id'] = $_POST['order_id'];
                        $saveData['order_id'] = 0;
                    }
                    $saveData['attachment'] = str_replace('<<','',$at);
                    $this->saveAttachmentsPrescreen($saveData, $count);
                }
            }
            //var_dump($saveData);

            $save = $prescreen->newEntity($arr);
            $prescreen->save($save);
            die;
        }
        public function savedDriverApp($document_id = 0, $cid = 0)
        {
            // echo "<pre>";print_r($_POST);die;
            $controller = $this->_registry->getController();
            $arr['client_id'] = $cid;
            $arr['user_id'] = $controller->request->session()->read('Profile.id');
            
            if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                if(!isset($_GET['order_id']))
                $arr['order_id'] = $document_id;
                else
                $arr['order_id'] = $_GET['order_id'];
                $arr['document_id'] = 0;
                
                if (isset($_POST['uploaded_for']))
                    $uploaded_for = $_POST['uploaded_for'];
                else
                    $uploaded_for = '';
                $for_doc = array('document_type'=>'Driver Application','sub_doc_id'=>2,'order_id'=>$arr['order_id'],'user_id'=>$arr['user_id'],'uploaded_for'=>$uploaded_for);
                $this->saveDocForOrder($for_doc);
            } else {
                $arr['document_id'] = $document_id;
                $arr['order_id'] = 0;
            }
            

            //$input_var = rtrim($_POST['inputs'],',');
            $driverApps = TableRegistry::get('driver_application');

            /* $delete_id=$driverApps->find()->where(['document_id'=>$_POST['document_id']]);
             $del_id = $delete_id->id;*/

            $del = $driverApps->query();
            if (!isset($_GET['document']) || isset($_GET['order_id'])){
                if(isset($_GET['order_id']))
                $document_id = $_GET['order_id']; 
                $del->delete()->where(['order_id' => $document_id])->execute();
                }
            else
                $del->delete()->where(['document_id' => $document_id])->execute();

            $driverAcc = array('date_of_accident',
                'nature_of_accident',
                'fatalities',
                'injuries',
                'driver_license',
                'province',
                'license_number',
                'class',
                'expiration_date',
                'attach_doc'
            );
            $total_acc_record = 0;
            $accident = array();
            foreach ($_POST as $data => $val) {

                if ($data == "document_type") {
                    continue;
                } else if ($data == "count_acc_record") {
                    $total_acc_record = $val;
                } else if (in_array($data, $driverAcc)) {
                    continue;
                } else {
                    // if(isset($_POST[$data]) ) {
                    $arr[$data] = urldecode($val);
                    // }
                }
            }
            $save = $driverApps->newEntity($arr);
            if ($driverApps->save($save)) {
                $id = $save->id;
                $driverAppAcc = TableRegistry::get('driver_application_accident');
                // $del = $driverAppAcc->query();
                // $del->delete()->where(['driver_application_id'=>$id])->execute();
                for ($i = 0; $i < $total_acc_record; $i++) {
                    $acc['driver_application_id'] = $id;
                    $acc['date_of_accident'] = urldecode($_POST['date_of_accident'][$i]);
                    $acc['nature_of_accident'] = urldecode($_POST['nature_of_accident'][$i]);
                    $acc['fatalities'] = urldecode($_POST['fatalities'][$i]);
                    $acc['injuries'] = urldecode($_POST['injuries'][$i]);
                    $saveAcc = $driverAppAcc->newEntity($acc);
                    $driverAppAcc->save($saveAcc);
                    $att = array();

                }
                if (isset($_POST['attach_doc'])) {
                    $count = 0;
                    foreach ($_POST['attach_doc'] as $v) {
                        $count++;
                        if (!isset($_GET['document']) || isset($_GET['order_id'])){ 
                            if(isset($_GET['order_id']))
                            $document_id = $_GET['order_id'];
                            $att['order_id'] = $document_id;
                            $att['document_id'] = 0;
                        } else {
                            $att['document_id'] = $document_id;
                            $att['order_id'] = 0;
                        }
                        $att['attachment'] = $v;
                        $this->saveAttachmentsDriverApp($att, $count);

                    }

                }

                $driverAppLic = TableRegistry::get('driver_application_licenses');
                // $del = $driverAppLic->query();
                // $del->delete()->where(['driver_application_id'=>$id])->execute();
                for ($i = 0; $i <= 2; $i++) {
                    $lic['driver_application_id'] = $id;
                    $lic['driver_license'] = urldecode($_POST['driver_license'][$i]);
                    $lic['province'] = urldecode($_POST['province'][$i]);
                    $lic['license_number'] = urldecode($_POST['license_number'][$i]);
                    $lic['class'] = $_POST['class'][$i];
                    $lic['expiration_date'] = urldecode($_POST['expiration_date'][$i]);
                    $saveLic = $driverAppLic->newEntity($lic);
                    $driverAppLic->save($saveLic);
                }

            }

            die;
        }
        public function savedDriverEvaluation($document_id = 0, $cid = 0)
        {
            // echo "<pre>";print_r($_POST);die;
            $controller = $this->_registry->getController();
            $roadTest = TableRegistry::get('road_test');
            
            $arr['client_id'] = $cid;
            $arr['user_id'] = $controller->request->session()->read('Profile.id');
            
            if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                if(!isset($_GET['order_id']))
                $arr['order_id'] = $document_id;
                else
                $arr['order_id'] = $_GET['order_id'];
                $arr['document_id'] = 0;
                
                
                if (isset($_POST['uploaded_for']))
                    $uploaded_for = $_POST['uploaded_for'];
                else
                    $uploaded_for = '';
                $for_doc = array('document_type'=>'Road test','sub_doc_id'=>3,'order_id'=>$arr['order_id'],'user_id'=>$arr['user_id'],'uploaded_for'=>$uploaded_for);
                $this->saveDocForOrder($for_doc);
                
                
            } else {
                $arr['document_id'] = $document_id;
                $arr['order_id'] = 0;
            }
            
            $del = $roadTest->query();
            if (!isset($_GET['document']) || isset($_GET['order_id'])){
                if(isset($_GET['order_id']))
                $document_id = $_GET['order_id'];
                $del->delete()->where(['order_id' => $document_id])->execute();
                }
            else
                $del->delete()->where(['document_id' => $document_id])->execute();

            if (isset($_POST['attach_doc'])) {
                $count = 0;
                foreach ($_POST['attach_doc'] as $v) {
                    $count++;
                    if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                        if(!isset($_GET['order_id']))
                        $att['order_id'] = $document_id;
                        else
                        $att['order_id'] = $_GET['order_id'];
                        $att['document_id'] = 0;
                    } else {
                        $att['document_id'] = $document_id;
                        $att['order_id'] = 0;
                    }
                    $att['attachment'] = $v;
                    $this->saveAttachmentsRoadTest($att, $count);

                }
            }
            foreach ($_POST as $data => $val) {

                if ($data == "document_type" || $data == 'attach_doc') {

                    continue;
                } else {
                    if (isset($_POST[$data])) {
                        $arr[$data] = urldecode($val);
                    }
                }

            }

            $save = $roadTest->newEntity($arr);
            if ($roadTest->save($save)) {
                //echo $save->id;
            }
            die;
        }
        public function savedMeeOrder($document_id = 0, $cid = 0)
        {
            $controller = $this->_registry->getController();
            $consentForm = TableRegistry::get('consent_form');
            
            $arr['client_id'] = $cid;
            $arr['user_id'] = $controller->request->session()->read('Profile.id');
            
            if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                if(!isset($_GET['order_id']))
                $arr['order_id'] = $document_id;
                else
                $arr['order_id'] = $_GET['order_id'];
                $arr['document_id'] = 0;
                
                
                if (isset($_POST['uploaded_for']))
                    $uploaded_for = $_POST['uploaded_for'];
                else
                    $uploaded_for = '';
                $for_doc = array('document_type'=>'Consent Form','sub_doc_id'=>4,'order_id'=>$arr['order_id'],'user_id'=>$arr['user_id'],'uploaded_for'=>$uploaded_for);
                $this->saveDocForOrder($for_doc);
                
                
                
            } else {
                $arr['document_id'] = $document_id;
                $arr['order_id'] = 0;
            }
            

            $del = $consentForm->query();
            if (!isset($_GET['document']) || isset($_GET['order_id'])){
                if(!isset($_GET['order_id']))
                $del->delete()->where(['order_id' => $document_id])->execute();
                else
                $del->delete()->where(['order_id' => $_GET['order_id']])->execute();
                }
            else
                $del->delete()->where(['document_id' => $document_id])->execute();

            $post = $_POST;
            if (isset($_POST['attach_doc'])) {
                $count = 0;
                foreach ($_POST['attach_doc'] as $v) {
                    $count++;
                    if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                        if(!isset($_GET['order_id']))
                        $att['order_id'] = $document_id;
                        else
                        $att['order_id'] = $_GET['order_id'];
                        $att['document_id'] = 0;
                    } else {
                        $att['document_id'] = $document_id;
                        $att['order_id'] = 0;
                    }
                    $att['attachment'] = $v;
                    $this->saveAttachmentsConsentForm($att, $count);

                }
            }
            foreach ($_POST as $data => $val) {

                if ($data == 'offence' || $data == 'date_of_sentence' || $data == 'location' || $data == 'attach_doc') {
                    continue;
                }
                //echo $data." ".$val."<br />";
                $arr[$data] = urldecode($val);

            }

// echo "<pre>";print_r($arr);die;
            $save = $consentForm->newEntity($arr);
            if ($consentForm->save($save)) {
                $id = $save->id;
                $consentFormCri = TableRegistry::get('consent_form_criminal');

                // $del = $consentFormCri->query();
                // $del->delete()->where(['consent_form_id'=>$id])->execute();

                for ($i = 0; $i < 8; $i++) {
                    $crm['consent_form_id'] = $id;
                    $crm['offence'] = urldecode($post['offence'][$i]);
                    $crm['date_of_sentence'] = urldecode($post['date_of_sentence'][$i]);
                    $crm['location'] = urldecode($post['location'][$i]);
                    $saveCrm = $consentForm->newEntity($crm);
                    $consentFormCri->save($saveCrm);
                }
            }

            die;
        }
        function saveEmployment($document_id = 0, $cid = 0)
        {
            // echo "<pre>";print_r($_POST);die;
            //employement
            $controller = $this->_registry->getController();
            $employment = TableRegistry::get('employment_verification');
            
            $arr['client_id'] = $cid;
            $arr['user_id'] = $controller->request->session()->read('Profile.id');
            
            
            if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                if(!isset($_GET['order_id']))
                $arr['order_id'] = $document_id;
                else
                $arr['order_id'] = $_GET['order_id'];
                $arr['document_id'] = 0;
                
                
                if (isset($_POST['uploaded_for']))
                    $uploaded_for = $_POST['uploaded_for'];
                else
                    $uploaded_for = '';
                
                $for_doc = array('document_type'=>'Employment Verification','sub_doc_id'=>9,'order_id'=>$arr['order_id'],'user_id'=>$arr['user_id'],'uploaded_for'=>$uploaded_for);                
                $this->saveDocForOrder($for_doc);
                
                
            } else {
                $arr['document_id'] = $document_id;
                $arr['order_id'] = 0;
            }
            
            
            
            

            $del = $employment->query();
            /*if (!isset($_GET['document']) || isset($_GET['order_id'])){
                if(!isset($_GET['order_id']))
                    $del->delete()->where(['order_id' => $_GET['order_id']])->execute();
                    else
                    $del->delete()->where(['order_id' => $document_id])->execute();
                    
                
                }
            else
                $del->delete()->where(['document_id' => $document_id])->execute();*/
            if (!isset($_GET['document']) || isset($_GET['order_id'])){
                if(isset($_GET['order_id']))
                $document_id = $_GET['order_id'];
                $del->delete()->where(['order_id' => $document_id])->execute();
                }
            else
                $del->delete()->where(['document_id' => $document_id])->execute();

            if (isset($_POST['attach_doc'])) {
                $count = 0;
                foreach ($_POST['attach_doc'] as $v) {
                    $count++;
                    if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                        if(!isset($_GET['order_id']))
                        $att['order_id'] = $document_id;
                        else
                        $att['order_id'] = $_GET['order_id'];
                        $att['document_id'] = 0;
                    } else {
                        $att['document_id'] = $document_id;
                        $att['order_id'] = 0;
                    }
                    $att['attachment'] = $v;
                    $this->saveAttachmentsEmployment($att, $count);

                }
            }
            for ($i = 0; $i < $_POST['count_past_emp']; $i++) {
                if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                    if(!isset($_GET['order_id']))
                    $arr2['order_id'] = $document_id;
                    else
                    $arr2['order_id'] = $_GET['order_id'];
                    $arr2['document_id'] = 0;
                } else {
                    $arr2['document_id'] = $document_id;
                    $arr2['order_id'] = 0;
                }
                $arr2['client_id'] = $cid;
                $arr2['user_id'] = $controller->request->session()->read('Profile.id');

                if (isset($_POST['company_name'][$i])) {
                    $arr2['company_name'] = urldecode($_POST['company_name'][$i]);
                }

                if (isset($_POST['address'][$i])) {
                    $arr2['address'] = urldecode($_POST['address'][$i]);
                }

                if (isset($_POST['city'][$i])) {
                    $arr2['city'] = urldecode($_POST['city'][$i]);
                }

                if (isset($_POST['state_province'][$i])) {
                    $arr2['state_province'] = urldecode($_POST['state_province'][$i]);
                }

                if (isset($_POST['country'][$i])) {
                    $arr2['country'] = urldecode($_POST['country'][$i]);
                }

                if (isset($_POST['supervisor_name'][$i])) {
                    $arr2['supervisor_name'] = urldecode($_POST['supervisor_name'][$i]);
                }

                if (isset($_POST['supervisor_phone'][$i])) {
                    $arr2['supervisor_phone'] = urldecode($_POST['supervisor_phone'][$i]);
                }

                if (isset($_POST['supervisor_email'][$i])) {
                    $arr2['supervisor_email'] = urldecode($_POST['supervisor_email'][$i]);
                }

                if (isset($_POST['supervisor_secondary_email'][$i])) {
                    $arr2['supervisor_secondary_email'] = urldecode($_POST['supervisor_secondary_email'][$i]);
                }

                if (isset($_POST['employment_start_date'][$i])) {
                    $arr2['employment_start_date'] = urldecode($_POST['employment_start_date'][$i]);
                }

                if (isset($_POST['employment_end_date'][$i])) {
                    $arr2['employment_end_date'] = urldecode($_POST['employment_end_date'][$i]);
                }
                if (isset($_POST['claims_recovery_date'][$i])) {
                    $arr2['claims_recovery_date'] = urldecode($_POST['claims_recovery_date'][$i]);
                }
                if (isset($_POST['emploment_history_confirm_verify_use'][$i])) {
                    $arr2['emploment_history_confirm_verify_use'] = urldecode($_POST['emploment_history_confirm_verify_use'][$i]);
                }
                if (isset($_POST['us_dot'][$i])) {
                    $arr2['us_dot'] = urldecode($_POST['us_dot'][$i]);
                }
                if (isset($_POST['us_dotsignature'][$i])) {
                    $arr2['us_dotsignature'] = urldecode($_POST['us_dotsignature'][$i]);
                }
                if (isset($_POST['signature'][$i])) {
                    $arr2['signature'] = urldecode($_POST['signature'][$i]);
                }
                if (isset($_POST['signature_datetime'][$i])) {
                    $arr2['signature_datetime'] = urldecode($_POST['signature_datetime'][$i]);
                }

                if (isset($_POST['equipment_vans'][$i])) {
                    $arr2['equipment_vans'] = urldecode($_POST['equipment_vans'][$i]);
                }
                if (isset($_POST['equipment_reefer'][$i])) {
                    $arr2['equipment_reefer'] = urldecode($_POST['equipment_reefer'][$i]);
                }
                if (isset($_POST['equipment_decks'][$i])) {
                    $arr2['equipment_decks'] = urldecode($_POST['equipment_decks'][$i]);
                }
                if (isset($_POST['equipment_super'][$i])) {
                    $arr2['equipment_super'] = urldecode($_POST['equipment_super'][$i]);
                }
                if (isset($_POST['equipment_straight_truck'][$i])) {
                    $arr2['equipment_straight_truck'] = urldecode($_POST['equipment_straight_truck'][$i]);
                }
                if (isset($_POST['equipment_others'][$i])) {
                    $arr2['equipment_others'] = urldecode($_POST['equipment_others'][$i]);
                }

                //driving
                if (isset($_POST['driving_experince_local'][$i])) {
                    $arr2['driving_experince_local'] = urldecode($_POST['driving_experince_local'][$i]);
                }
                if (isset($_POST['driving_experince_canada'][$i])) {
                    $arr2['driving_experince_canada'] = urldecode($_POST['driving_experince_canada'][$i]);
                }
                if (isset($_POST['driving_experince_canada_rocky_mountains'][$i])) {
                    $arr2['driving_experince_canada_rocky_mountains'] = urldecode($_POST['driving_experince_canada_rocky_mountains'][$i]);
                }
                if (isset($_POST['driving_experince_usa'][$i])) {
                    $arr2['driving_experince_usa'] = urldecode($_POST['driving_experince_usa'][$i]);
                }
                for ($l = 0; $l <= 100; $l++) {
                    if (isset($_POST['claims_with_employer_' . $l][$i])) {
                        $arr2['claims_with_employer'] = urldecode($_POST['claims_with_employer_' . $l][$i]);
                        break;
                    }
                }

                $save2 = $employment->newEntity($arr2);
                $employment->save($save2);
            }

            die;
        }
        function saveEducation($document_id = NULL, $cid = NULL)
        {
            // echo $_POST['college_school_name'][0]
            //education
            $controller = $this->_registry->getController();
            $education = TableRegistry::get('education_verification');
            
            
            
            $arr['client_id'] = $cid;
            if($controller->request->session()->read('Profile.id'))
                $arr['user_id'] = $controller->request->session()->read('Profile.id');
            else
                $arr['user_id'] = 0;
            
            
            if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                if(!isset($_GET['order_id']))
                $arr['order_id'] = $document_id;
                else
                $arr['order_id'] = $_GET['order_id'];
                $arr['document_id'] = 0;
                
                
                if (isset($_POST['uploaded_for']))
                    $uploaded_for = $_POST['uploaded_for'];
                else
                    $uploaded_for = '';
                
                $for_doc = array('document_type'=>'Education Verification','sub_doc_id'=>10,'order_id'=>$arr['order_id'],'user_id'=>$arr['user_id'],'uploaded_for'=>$uploaded_for);                
                $this->saveDocForOrder($for_doc);
                
                
                
            } else {
                $arr['document_id'] = $document_id;
                $arr['order_id'] = 0;
            }
            
            
            
            
            

            $del = $education->query();
            /*if (!isset($_GET['document']) || isset($_GET['order_id'])){
                if(!isset($_GET['order_id']))
                $del->delete()->where(['order_id' => $document_id])->execute();
                else
                $del->delete()->where(['order_id' => $_GET['order_id']])->execute();
                }
            else
                $del->delete()->where(['document_id' => $document_id])->execute();*/
            if (!isset($_GET['document']) || isset($_GET['order_id'])){
                if(isset($_GET['order_id']))
                $document_id = $_GET['order_id'];
                $del->delete()->where(['order_id' => $document_id])->execute();
                }
            else
                $del->delete()->where(['document_id' => $document_id])->execute();
                
                
            if (isset($_POST['attach_doc'])) {
                $count = 0;
                foreach ($_POST['attach_doc'] as $v) {
                    $count++;
                    if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                        if(!isset($_GET['order_id']))
                        $att['order_id'] = $document_id;
                        else
                        $att['order_id'] = $_GET['order_id'];
                        $att['document_id'] = 0;
                    } else {
                        $att['document_id'] = $document_id;
                        $att['order_id'] = 0;
                    }
                    $att['attachment'] = $v;
                    $this->saveAttachmentsEducation($att, $count);

                }
            }
            $_POST['count_more_edu']++;
            for ($i = 0; $i < $_POST['count_more_edu']; $i++) {
                if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                    if(!isset($_GET['order_id']))
                    $arr2['order_id'] = $document_id;
                    else
                    $arr2['order_id'] = $_GET['order_id'];
                    $arr2['document_id'] = 0;
                } else {
                    $arr2['document_id'] = $document_id;
                    $arr2['order_id'] = 0;
                }
                $arr2['client_id'] = $cid;
                if($controller->request->session()->read('Profile.id'))
                $arr2['user_id'] = $controller->request->session()->read('Profile.id');

                if (isset($_POST['college_school_name'][$i])) {
                    $arr2['college_school_name'] = urldecode($_POST['college_school_name'][$i]);
                }

                if (isset($_POST['address'][$i])) {
                    $arr2['address'] = urldecode($_POST['address'][$i]);
                }

                if (isset($_POST['supervisior_name'][$i])) {
                    $arr2['supervisior_name'] = urldecode($_POST['supervisior_name'][$i]);
                }

                if (isset($_POST['supervisior_phone'][$i])) {
                    $arr2['supervisior_phone'] = urldecode($_POST['supervisior_phone'][$i]);
                }

                if (isset($_POST['supervisior_email'][$i])) {
                    $arr2['supervisior_email'] = urldecode($_POST['supervisior_email'][$i]);
                }

                if (isset($_POST['supervisior_secondary_email'][$i])) {
                    $arr2['supervisior_secondary_email'] = urldecode($_POST['supervisior_secondary_email'][$i]);
                }

                if (isset($_POST['education_start_date'][$i])) {
                    $arr2['education_start_date'] = urldecode($_POST['education_start_date'][$i]);
                }

                if (isset($_POST['education_end_date'][$i])) {
                    $arr2['education_end_date'] = urldecode($_POST['education_end_date'][$i]);
                }

                if (isset($_POST['claim_tutor'][$i])) {
                    $arr2['claim_tutor'] = urldecode($_POST['claim_tutor'][$i]);
                }

                if (isset($_POST['date_claims_occur'][$i])) {
                    $arr2['date_claims_occur'] = urldecode($_POST['date_claims_occur'][$i]);
                }

                if (isset($_POST['education_history_confirmed_by'][$i])) {
                    $arr2['education_history_confirmed_by'] = urldecode($_POST['education_history_confirmed_by'][$i]);
                }
                if (isset($_POST['highest_grade_completed'][$i])) {
                    $arr2['highest_grade_completed'] = urldecode($_POST['highest_grade_completed'][$i]);
                }
                if (isset($_POST['high_school'][$i])) {
                    $arr2['high_school'] = urldecode($_POST['high_school'][$i]);
                }
                if (isset($_POST['college'][$i])) {
                    $arr2['college'] = urldecode($_POST['college'][$i]);
                }
                if (isset($_POST['last_school_attended'][$i])) {
                    $arr2['last_school_attended'] = urldecode($_POST['last_school_attended'][$i]);
                }
                if (isset($_POST['signature'][$i])) {
                    $arr2['signature'] = urldecode($_POST['signature'][$i]);
                }

                if (isset($_POST['date_time'][$i])) {
                    $arr2['date_time'] = urldecode($_POST['date_time'][$i]);
                }
                if (isset($_POST['performance_issue'][$i])) {
                    $arr2['performance_issue'] = urldecode($_POST['performance_issue'][$i]);
                }

                $save2 = $education->newEntity($arr2);
                $education->save($save2);
            }

            die;
        }
        public function mee_attach($cid = 0, $document_id = 0){
            // echo "<pre>";print_r($_POST);die;
            $controller = $this->_registry->getController();
            $roadTest = TableRegistry::get('mee_attachments');

            $arr['client_id'] = $cid;
            if ($cid == "undefined") {
                echo "THIS SHOULD NOT BE!";
            }

            $arr['user_id'] = $controller->request->session()->read('Profile.id');

            if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                if (!isset($_GET['order_id'])) {
                    $arr['order_id'] = $document_id;
                } else {
                    $arr['order_id'] = $_GET['order_id'];
                }
                $arr['document_id'] = 0;


                if (isset($_POST['uploaded_for'])) {
                    $uploaded_for = $_POST['uploaded_for'];
                }else {
                    $uploaded_for = '';
                }
                $for_doc = array('document_type' => 'MEE Attachments', 'sub_doc_id' => 15, 'order_id' => $arr['order_id'], 'user_id' => $arr['user_id'], 'uploaded_for' => $uploaded_for);
                $this->saveDocForOrder($for_doc);


            } else {
                $arr['document_id'] = $document_id;
                $arr['order_id'] = 0;
            }

            $del = $roadTest->query();
            if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                if (isset($_GET['order_id'])) {
                    $document_id = $_GET['order_id'];
                }
                $del->delete()->where(['order_id' => $document_id])->execute();
            } else {
                $del->delete()->where(['document_id' => $document_id])->execute();
            }

            $mee['order_id'] = $arr['order_id'];
            $mee['document_id'] = $arr['document_id'];
            $mee['certification'] = $_POST['certification'];
            $mee['cvor'] = $_POST['cvor'];
            $mee['driver_record_abstract'] = $_POST['driver_record_abstract'];
            $mee['id_piece1'] = $_POST['id_piece1'];
            $mee['id_piece2'] = $_POST['id_piece2'];
            $mee['resume'] = $_POST['resume'];
            $mee['client_id'] = $cid;
            $mee['user_id'] = $arr['user_id'];
            

            $save = $roadTest->newEntity($mee);
            if ($roadTest->save($save)) {
                if(isset($_POST['mee_attachments'])) {
                    $att_mee = TableRegistry::get('mee_attachments_more');
                    foreach($_POST['mee_attachments'] as $me_a) {
                        if($me_a){
                            $save_mee['mee_id'] = $save->id;
                            $save_mee['attachments'] = $me_a;
                            $saving = $att_mee->newEntity($save_mee);
                            $att_mee->save($saving);
                        }
                    }
                }
            }
            die;
        }
        public function getDocument($type = ""){
            $doc = TableRegistry::get('Subdocuments');
            $query = $doc->find();
            if ($type == 'orders') {
                $q= $query->select()->where(['display' => 1, 'orders' => 1])->order('id');
            } else {
                $q = $query->select()->where(['display' => 1])->order('id');
            }
            //debug($query);
            //$this->response->body($query);
            return $q;
        }
        function getDivById($id){
            //echo $id;die();
            if($id){
                $doc = TableRegistry::get('client_divison');
                $query = $doc->find();
                $q = $query->select()->where(['id' => $id])->first();

                //$this->response->body($q);
                return $q;
                die();
            }
            else
            die();
        }
        function getDocumentcount(){
            $doc = TableRegistry::get('Subdocuments');
            $query = $doc->find();
            $query->where(['display' => 1]);
            return $query->all();
        }

        function getUserDocumentcount(){
            //$this->loadComponent('Session');
            $controller = $this->_registry->getController();
            $doc = TableRegistry::get('Subdocuments');
            $query = $doc->find();
            $query->where(['display' => 1])->all();
            $cnt = 0;
            foreach ($query as $q) {
                $subdoc = TableRegistry::get('profilessubdocument');
                if ($query1 = $subdoc->find()->where(['profile_id' => $controller->request->session()->read('Profile.id'), 'subdoc_id' => $q->id, 'display <>' => 0])->first())
                    $cnt++;
            }
            return $cnt;
        }
        
        function getUser($user_id){
            
                $query = TableRegistry::get('Profiles');
                $query = $query->find()->where(['id' => $user_id]);
                $q = $query->first();
                //$this->response->body($q);
                return $q;
           
            die();
        }

        function getAllUser($sortby = "fname"){
            $query = TableRegistry::get('Profiles');
            //$query = $query->find();
            //$q = $query->find()->where(['profile_type !=' => '5'])->all();
            //$order = $orders->find()->order(['orders.id' => 'DESC'])->where(['draft' => 0, $cond])->all();
            $q = $query->find()->order([$sortby => 'ASC'])->all();
            //$this->response->body($q);
            return $q;
            die();
        }

        function getAllClient(){
            $query = TableRegistry::get('Clients');
            $query = $query->find()->order(["company_name" => 'ASC']);
            $q = $query->select();
            //$this->response->body($q);
            return $q;
            die();
        }


    function getProSubDoc($pro_id,$doc_id){
        $sub = TableRegistry::get('Profilessubdocument');
        $query = $sub->find();
        $query->select()->where(['profile_id'=>$pro_id, 'subdoc_id'=>$doc_id]);
        $q = $query->first();
        if (is_object(($q))) {return $q->display;}
        return $pro_id . "." . $doc_id . " is not set";
    }

        function getDocType($UserID = -1){
            $query = TableRegistry::get('Subdocuments');
            $query = $query->find();
            $q = $query->select()->where(['display' => '1']);
            //$this->response->body($q);
            if($UserID>-1){//a user ID was specified, check their settings
                //use: $this->request->session()->read('Profile.id') to get the user's ID
                foreach($q as $sub){//inject user's settings
                    $sub->user = $this->getProSubDoc($UserID, $sub->id);
                    //3 is both, 2 is create only, 1 is view only, 0 is none (which won't be returned in the results)
                    $sub->show = $sub->user == 3||$sub->user ==1;
                }
            }
            return $q;
            die();
        }
        
        public function getSpecificData($cid = 0, $order_id = 0){
            $modal = TableRegistry::get($_GET['form_type']);
            if (!isset($_GET['document'])) {
                $detail = $modal->find()->where(['client_id' => $cid, 'order_id' => $order_id, 'document_id' => 0]);
            } else {
                $detail = $modal->find()->where(['client_id' => $cid, 'document_id' => $order_id, 'order_id' => 0]);
            }
            // die('asd');
            echo json_encode($detail);
            die;
        }

        
        
        public function saveAttachmentsPrescreen($data = NULL, $count = 0)
        {//count is to delete all while first insertion and no delete for following insertion

            $prescreen = TableRegistry::get('doc_attachments');
            $del = $prescreen->query();
            if ($count == 1) {
                if (!isset($_GET['document']) || isset($_GET['order_id'])){
                    if(!isset($_GET['order_id'])) {
                        $del->delete()->where(['order_id' => $data['order_id'], 'sub_id' => 1])->execute();
                    } else {
                        $del->delete()->where(['order_id' => $_GET['order_id'], 'sub_id' => 1])->execute();
                    }
                }  else {
                    $del->delete()->where(['document_id' => $data['document_id']])->execute();
                }
            }
            $data['sub_id'] = 1;
            $save = $prescreen->newEntity($data);
            $prescreen->save($save);
        }

        public function saveAttachmentsDriverApp($data = NULL, $count = 0){
            $driverApp = TableRegistry::get('doc_attachments');
            $del = $driverApp->query();
            if ($count == 1) {
                if (!isset($_GET['document']) || isset($_GET['order_id'])){
                    if(!isset($_GET['order_id'])) {
                        $del->delete()->where(['order_id' => $data['order_id'], 'sub_id' => 2])->execute();
                    } else {
                        $del->delete()->where(['order_id' => $_GET['order_id'], 'sub_id' => 2])->execute();
                    }
                } else {
                    $del->delete()->where(['document_id' => $data['document_id']])->execute();
                }
            }
            $data['sub_id'] = 2;
            $save = $driverApp->newEntity($data);
            $driverApp->save($save);
        }

        public function saveAttachmentsRoadTest($data = NULL, $count = 0){
            $roadTest = TableRegistry::get('doc_attachments');
            $del = $roadTest->query();
            if ($count == 1) {
                if (!isset($_GET['document']) || isset($_GET['order_id'])){
                    if(!isset($_GET['order_id'])) {
                        $del->delete()->where(['order_id' => $data['order_id'], 'sub_id' => 3])->execute();
                    } else {
                        $del->delete()->where(['order_id' => $_GET['order_id'], 'sub_id' => 3])->execute();
                    }
                } else{
                    $del->delete()->where(['document_id' => $data['document_id']])->execute();
                }
            }
            $data['sub_id'] = 3;
            $save = $roadTest->newEntity($data);
            $roadTest->save($save);
        }

        public function saveAttachmentsConsentForm($data = NULL, $count = 0)
        {
            $consentForm = TableRegistry::get('doc_attachments');
            if ($count == 1) {
                $del = $consentForm->query();
                if (!isset($_GET['document']) || isset($_GET['order_id'])){
                    if(!isset($_GET['order_id']))
                    $del->delete()->where(['order_id' => $data['order_id'],'sub_id'=>4])->execute();
                    else
                    $del->delete()->where(['order_id' => $_GET['order_id'],'sub_id'=>4])->execute();
                    }
                else
                    $del->delete()->where(['document_id' => $data['document_id']])->execute();
            }
            $data['sub_id'] = 4;
            $save = $consentForm->newEntity($data);
            $consentForm->save($save);
        }

        public function saveAttachmentsEmployment($data = NULL, $count = 0)
        {

            $employment = TableRegistry::get('doc_attachments');
            if ($count == 1) {
                $del = $employment->query();
                if (!isset($_GET['document']) || isset($_GET['order_id'])){
                    if(!isset($_GET['order_id']))
                    $del->delete()->where(['order_id' => $data['order_id'],'sub_id'=>41])->execute();
                    else
                    $del->delete()->where(['order_id' => $_GET['order_id'],'sub_id'=>41])->execute();
                    }
                else
                    $del->delete()->where(['document_id' => $data['document_id']])->execute();
            }
            $data['sub_id'] = 41;
            $save = $employment->newEntity($data);
            $employment->save($save);
        }

        public function saveAttachmentsEducation($data = NULL, $count = 0)
        {
            $education = TableRegistry::get('doc_attachments');
            if ($count == 1) {
                $del = $education->query();
                 if (!isset($_GET['document']) || isset($_GET['order_id'])){
                    if(!isset($_GET['order_id']))
                    $del->delete()->where(['order_id' => $data['order_id'],'sub_id'=>42])->execute();
                    else
                    $del->delete()->where(['order_id' => $_GET['order_id'],'sub_id'=>42])->execute();
                    }
                else
                    $del->delete()->where(['document_id' => $data['document_id']])->execute();
            }
            $data['sub_id'] = 42;
            $save = $education->newEntity($data);
            $education->save($save);
        }

        public function getAttachedDoc($cid = 0, $order_id = 0)
        {
            // $id = $_GET['id'];
            if ($_GET['form_type'] == "company_pre_screen_question.php") {
                $prescreen = TableRegistry::get('doc_attachments');
                $prescreenAttach = $prescreen
                    ->find()
                    ->where(['order_id' => $order_id,'sub_id'=>1]);
                echo json_encode($prescreenAttach);

            } else if ($_GET['form_type'] == "driver_application.php") {
                $driverApp = TableRegistry::get('doc_attachments');
                $driverAppAttach = $driverApp
                    ->find()
                    ->where(['order_id' => $order_id,'sub_id'=>2]);
                echo json_encode($driverAppAttach);

            } else if ($_GET['form_type'] == "driver_evaluation_form.php") {
                $roadTest = TableRegistry::get('doc_attachments');
                $roadTestAttach = $roadTest
                    ->find()
                    ->where(['order_id' => $order_id,'sub_id'=>3]);
                echo json_encode($roadTestAttach);

            } else if ($_GET['form_type'] == "document_tab_3.php") {
                if ($_GET['sub_type'] == "Consent Form") {
                    $consentForm = TableRegistry::get('doc_attachments');
                    $consentFormAttach = $consentForm
                        ->find()
                        ->where(['order_id' => $order_id,'sub_id'=>4]);
                    echo json_encode($consentFormAttach);
                } else if ($_GET['sub_type'] == "Employment") {
                    $employment = TableRegistry::get('doc_attachments');
                    $employmentAttach = $employment
                        ->find()
                        ->where(['order_id' => $order_id,'sub_id'=>41]);
                    echo json_encode($employmentAttach);
                } else if ($_GET['sub_type'] == "Education") {
                    $education = TableRegistry::get('doc_attachments');

                    $educationAttach = $education
                        ->find()
                        ->where(['order_id' => $order_id,'sub_id'=>42]);
                    echo json_encode($educationAttach);
                }
            }
        }
        function getClientById($cid)
        {
            $model = TableRegistry::get('Clients');
            $q = $model->find()->where(['id' => $cid])->first();
            //$this->response->body($q);
            return $q;
            die();
        }
        
        function getDriverClient($driver,$client)
        {
            $controller = $this->_registry->getController();
            $logged_id = $controller->request->session()->read('Profile.id');
            //echo "<br/>";
            if(!$client){
            $cmodel = TableRegistry::get('Clients');
            if(!$controller->request->session()->read('Profile.super'))
            $clients = $cmodel->find()->where(['(profile_id LIKE "'.$logged_id.',%" OR profile_id LIKE "%,'.$logged_id.',%" OR profile_id LIKE "%,'.$logged_id.'")']);
            else{
                if(!$driver)
            $clients = $cmodel->find();
            else
            $clients = $cmodel->find()->where(['(profile_id LIKE "'.$driver.',%" OR profile_id LIKE "%,'.$driver.',%" OR profile_id LIKE "%,'.$driver.'")']);
            }
            
            }
            else
            {
               $cmodel = TableRegistry::get('Clients');
               $clients = $cmodel->find()->where(['id'=>$client]); 
               
               $cmodel2 = TableRegistry::get('Clients');
                $clients2 = $cmodel2->find()->where(['id'=>$client])->first();
                if($clients2) {
                    $profile_ids2 = $clients2->profile_id;
                } else {
                    $profile_ids2 = '';
                }
                if(!$profile_ids2)
                $profile_ids2 = '9999999';
                $model = TableRegistry::get('Profiles');
                
                $profile_ids2 = str_replace(',',' ',$profile_ids2);
                $profile_ids2 = trim($profile_ids2);
                $profile_ids2 = str_replace(' ',',',$profile_ids2);
                $profile_ids2 = str_replace(',,',',',$profile_ids2);
                $profile_ids2 = str_replace(',,',',',$profile_ids2);
                
                $q = $model->find()->where(['id IN ('.$profile_ids2.')','(profile_type = 5 OR profile_type = 7 OR profile_type = 8 OR profile_type = 11)'])->order('fname');
                
            }
            $profile_ids = '';
            foreach($clients as $c)
            {

                if($profile_ids) {
                    $profile_ids = $profile_ids.','.$c->profile_id;
                } else {
                    $profile_ids = $c->profile_id;
                }
            }
            if(!$profile_ids)
            $profile_ids = '9999999';
            
            $profile_ids = str_replace(',',' ',$profile_ids);
            $profile_ids = trim($profile_ids);
                $profile_ids = str_replace(' ',',',$profile_ids);
                $profile_ids = str_replace(',,',',',$profile_ids);
                $profile_ids = str_replace(',,',',',$profile_ids);
            //echo $profile_ids;die();
            //echo $profile_ids.'_';die();
            if($driver==0 && $client==0)
            {
                $model = TableRegistry::get('Profiles');
                $conditions = array($this->makeprofiletypequery());
                if(!$controller->request->session()->read('Profile.super')) {
                    $conditions[] = 'id IN ('.$profile_ids.')';
                }
                $q = $model->find()->where([$conditions])->order('fname');

            }
            else
            if($driver!=0) {
               $model = TableRegistry::get('Profiles');
               $q = $model->find()->where(['id' => $driver])->order('fname'); 
            }
            
            $dr_cl['driver'] = $q;
            $dr_cl['client'] = $clients; 
           // $dr_cl["query"] = $this->makeprofiletypequery();
            //$this->response->body($dr_cl);
            return $dr_cl;
            die();
            
        }

    function makeprofiletypequery(){
        //'(SELECT placesorders FROM profile_types WHERE profile_types.id == profile_type) == 1'
        $tempstr = "";
        $ptypes = TableRegistry::get('profile_types')->find()->where(['placesorders' => 1])->all();
        foreach($ptypes as $ptype){
            if($tempstr){
                $tempstr .= " OR profile_type = " .  $ptype->id;
            }else{
                $tempstr = "(profile_type = " .  $ptype->id;
            }
        }
        return $tempstr . ")";
    }

        public function getOrderData($cid = 0, $order_id = 0, $profile_id = 0) {
            if (!$order_id) {
                echo null;
                die();
            }
            // print_r($_GET);die;
            
            $consent_check = TableRegistry::get('orders')->find()->where(['id IN (SELECT order_id FROM consent_form)','uploaded_for'=>$profile_id])->first();
            if ($_GET['form_type'] == "company_pre_screen_question.php") {
                $preScreen = TableRegistry::get('pre_screening');
                if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                    if (isset($_GET['order_id'])) {
                        $prescreenDetail = $preScreen->find()->where(['client_id' => $cid, 'order_id' => $_GET['order_id'], 'document_id' => 0])->first();
                    } else {
                        $prescreenDetail = $preScreen->find()->where(['client_id' => $cid, 'order_id' => $order_id, 'document_id' => 0])->first();
                    }
                }else{
                    $prescreenDetail = $preScreen->find()->where(['client_id' => $cid, 'document_id' => $order_id, 'order_id' => 0])->first();
                }
                // die('asd');
                unset($prescreenDetail->id);
                unset($prescreenDetail->document_id);
                unset($prescreenDetail->order_id);
                unset($prescreenDetail->client_id);
                unset($prescreenDetail->user_id);
                if ($prescreenDetail) {
                    $prescreenDetail->sub_doc_id = 1;
                    $prescreenDetail->document_type = 'Pre-Screening';
                    echo json_encode($prescreenDetail);
                }

            } else if ($_GET['form_type'] == "driver_application.php") {

                // $this->getDriverAppData($cid,$order_id);
                $driveApp = TableRegistry::get('driver_application');
                if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                    if (isset($_GET['order_id'])) {
                        $driveAppDetail = $driveApp->find()->where(['client_id' => $cid, 'order_id' => $_GET['order_id'], 'document_id' => 0])->first();
                    } else {
                        $driveAppDetail = $driveApp->find()->where(['client_id' => $cid, 'order_id' => $order_id, 'document_id' => 0])->first();
                    }
                } else {
                    $driveAppDetail = $driveApp->find()->where(['client_id' => $cid, 'document_id' => $order_id, 'order_id' => 0])->first();
                }
                //$driveAppID = $driveAppDetail->id;
                unset($driveAppDetail->id);
                unset($driveAppDetail->document_id);
                unset($driveAppDetail->order_id);
                unset($driveAppDetail->client_id);
                unset($driveAppDetail->user_id);
                if ($driveAppDetail) {
                    $driveAppDetail->sub_doc_id = 2;
                    $driveAppDetail->document_type = 'Driver Application';
                    echo json_encode($driveAppDetail);
                }

                // $driveAppAcc = TableRegistry::get('driver_application_accident');
                // $driveAppDetail = $driveAppAcc->find()->where(['id'=>$driveAppID,'client_id'=>$cid,'order_id'=>$order_id]);

            } else if ($_GET['form_type'] == "driver_evaluation_form.php") {

                // $this->getRoadTestData($cid,$order_id);
                $roadTest = TableRegistry::get('road_test');
                if (!isset($_GET['document']) || isset($_GET['order_id'])){
                    if(isset($_GET['order_id'])) {
                       $roadTestDetail = $roadTest->find()->where(['client_id' => $cid, 'order_id' => $_GET['order_id'], 'document_id' => 0])->first(); 
                    }  else {
                        $roadTestDetail = $roadTest->find()->where(['client_id' => $cid, 'order_id' => $order_id, 'document_id' => 0])->first();
                    }
                } else {
                    $roadTestDetail = $roadTest->find()->where(['client_id' => $cid, 'document_id' => $order_id, 'order_id' => 0])->first();
                }
                // $prescreenID = $prescreenDetail->id;
                if ($roadTestDetail) {
                    $roadTestDetail->sub_doc_id = 3;
                    $roadTestDetail->document_type = 'Road test';
                    echo json_encode($roadTestDetail);
                }

            } else if ($_GET['form_type'] == "document_tab_3.php" && !$consent_check) {//consent form

                $consentForm = TableRegistry::get('consent_form');
                if (!isset($_GET['document']) || isset($_GET['order_id'])) {
                    if (isset($_GET['order_id'])) {
                        $consentFormDetail = $consentForm->find()->where(['client_id' => $cid, 'order_id' => $_GET['order_id'], 'document_id' => 0])->first();
                    } else {
                        $consentFormDetail = $consentForm->find()->where(['client_id' => $cid, 'order_id' => $order_id, 'document_id' => 0])->first();
                    }
                } else {
                    $consentFormDetail = $consentForm->find()->where(['client_id' => $cid, 'document_id' => $order_id, 'order_id' => 0])->first();
                }
                if($consentFormDetail){
                    $consentFormID = $consentFormDetail->id;

                    $consentFormCriminal = TableRegistry::get('consent_form_criminal');
                    $consentFormCrmDetail = $consentFormCriminal->find()->where(['consent_form_id' => $consentFormID])->first();

                    echo json_encode($consentFormDetail);
                }
            }
            die;
        }

        function getOrderById($id){
            $orders = TableRegistry::get('orders');
            $order = $orders->find()->where(['id'=>$id])->first();
            return $order;
        }
        
        function getAssignedProfile($cid = 0){
            $profile = TableRegistry::get('Clients');
            $pro = $profile->find()->where(['id' => $cid])->first();
            return $pro;
        }
        
        function getProfilePermission($profile,$type){
            $arr_profile = explode(',',$profile);
            $email_arr = array();
            foreach($arr_profile as $ap) {
                 $permit = $this->Manager->loadpermissions($ap, "sidebar"); //$query->first();
                 if($permit){
                    if($type =='document') {
                        $v = $permit->email_document;
                    } elseif($type =='orders') {
                        $v = $permit->email_orders;
                    }
                    if($v == 1) {
                        $email = $this->getEmail($ap);
                        if($email && $email != "")
                        $email_arr[] = $email;
                    }
                }
            }
            return $email_arr;
        }
        
       
        function getEmail($id, $Column = "email"){
            $query = TableRegistry::get('Profiles');
            $pro = $query->find()->where(['id'=>$id])->first();
			if($pro){
                return $pro->$Column;
			}
            return "";
        }
        
        function getUrl(){
            $url = $_SERVER['SERVER_NAME'];
            if($url=='localhost') { return 'localhost.com';}
            $url = str_replace(array('http://', '/', 'www'), array('', '', ''), $url);
            $email_from = $url;
            return $email_from;
        }


    function offsettime($value, $period = "minutes", $date = "", $format = "Y-m-d H:i:s"){
        if (!$date) {$date = date($format);}
        $newdate= date_create($date);
        if ($value < 0) {$direction = "";} else {$direction = "+";}
        if ($value) {$newdate->modify($direction . $value . " " . $period);};
        return $newdate->format($format);
    }

    function findcol($Table, $Key, $Value){
        if(!is_object($Table)) {$Table = TableRegistry::get($Table);}
        return $Table->find()->where([$Key => $Value])->first();
    }

    function insertdb($Table, $Data){
        if(!is_object($Table)) {$Table = TableRegistry::get($Table);}
        $ret = $Table->newEntity($Data);
        $Table->save($ret);
        return $ret;
    }


    function constructdocument($orderid, $document_type, $sub_doc_id, $user_id, $client_id, $draft = 0, $UploadedFor = false){
        //id, order_id, document_type, sub_doc_id, title, description, scale, reason, suggestion, user_id, client_id, uploaded_for, created, draft, file
        $offsethours = 0;
        $data = array("created" => $this->offsettime($offsethours, "hours"), "order_id" => $orderid);
        //$data["description"] = "Website order";
        $data["document_type"] = $document_type;
        $data["sub_doc_id"] = $sub_doc_id;
        $data["user_id"] = $user_id;
        $data["client_id"] = $client_id;
        if(!$UploadedFor){$UploadedFor = $user_id;}
        $data["uploaded_for"] = $UploadedFor;
        $data["draft"] = $draft;
        return $this->insertdb("documents", $data)->id;
    }

    function constructsubdoc($data, $formID, $userID, $clientID, $orderid=0, $retData = false, $UploadedFor = false){
        $subdocinfo = $this->findcol("subdocuments", "id", $formID);
        $table = $subdocinfo->table_name;
        $docTitle = $subdocinfo->title;
        $docid = $this->constructdocument($orderid, $docTitle, $formID, $userID, $clientID, 0, $UploadedFor);//22= doc id number, 81 = user id for SMI site, 1=client id for SMI
        $data["document_id"] = $docid;
        $data["order_id"] = $orderid;
        $data["client_id"] = $clientID;
        $data["user_id"] = $userID;
        $ID = $this->insertdb($table, $data)->id;
        if($retData){return array("docid" => $docid , "subdocid" => $ID);}
        $ret = "<BR>" . $ID;
        return $docid . $ret;
    }


    function isassocarray($my_array){
        //for (reset($my_array); is_int(key($my_array)); next($my_array));
        //return is_null(key($my_array));
        if(!is_array($my_array)) {return false;}
        if(count($my_array) <= 0) {return true;}
        return !(array_unique(array_map("is_int", array_keys($my_array))) === array(true));
    }

    function makeCSV($data, $newline = "\r\n"){
        $retvalue = "";
        $haswrittencolumns = false;
        foreach($data as $entry){
            $currentline = "";
            if (is_object($entry)) {
                $entry = $this->getProtectedValue($entry, "_properties");
            }
            if(!$haswrittencolumns){
                foreach($entry as $key => $value){
                    $newkey = "";
                    if (is_array($value)){
                        if($this->isassocarray($value)){
                            $newkey = $value;
                        }
                    } elseif(is_object($value)){
                        $newkey = $this->getProtectedValue($value, "_properties");
                    }
                    if (is_array($newkey)) {
                        foreach($newkey as $key2 => $value2) {
                            $currentline = $this->appendstring($currentline, $key . "." . $key2);
                        }
                    } else {
                        $currentline = $this->appendstring($currentline, $key);
                    }
                }
                $haswrittencolumns=true;
                $retvalue = $currentline;
                $currentline = "";
            }

            foreach($entry as $key => $value){
                $currentline = $this->appendstring($currentline, $this->CSVvalue($value));
            }

            $retvalue .= $newline . $currentline;
        }
        return $retvalue;
    }

    function appendstring($Current, $Append, $delimeter = ","){
        if($Current){return $Current . $delimeter . $Append;}
        return $Append;
    }
    function getProtectedValue($obj,$name) {
        $array = (array)$obj;
        $prefix = chr(0).'*'.chr(0);
        if (isset($array[$prefix.$name])) {
            return $array[$prefix . $name];
        }
    }
    function CSVvalue($value){
        if (is_object($value)){$value = $this->getProtectedValue($value, "_properties");}
        if (is_array($value)) {
            if ($this->isassocarray($value)) {
                $currentline = "";
                foreach ($value as $Key => $thevalue) {
                    if ($currentline) {
                        $currentline .= ",";
                    }
                    $currentline .= $this->CSVvalue($thevalue);
                }
                return $currentline;
            } else {
                return $this->CSVvalue(implode(",", $value));
            }
        } else {
            if (strpos($value, '+') !== False){$value = "'" . $value;}
            if ( (strpos($value, ",") || strpos($value, "\r\n") || strpos($value, '"') || strpos($value, '-') || strpos($value, '\\') || strpos($value, '+')) !== False) {
                return '"' . str_replace('"', '""', $value) . '"';
            }
            return $value;
        }
    }

    //returns the order ID
    function constructorder($title, $user_id, $client_id, $conf_recruiter_name, $conf_driver_name, $forms, $otherdata = "", $order_type = "PSA", $uploaded_for = 0){
        $controller = $this->_registry->getController();

        $offsethours = date('Y-m-d H:i:s');
        $data = array("created" => $offsethours, "socialdate1" => date('Y-m-d'), "socialdate2" => date('Y-m-d'), "physicaldate" => date('Y-m-d'));
        $data["description"] = "Rapid order";
        $data["title"] = $title;
        $data["user_id"] = $user_id;
        $data["client_id"] = $client_id;
        $data["conf_recruiter_name"] = $conf_recruiter_name;
        $data["conf_driver_name"] = $conf_driver_name;
        $data["forms"] = $forms;
        $data["uploaded_for"] = $uploaded_for;
        $data["order_type"] = $order_type;
        if(is_array($otherdata)){
            $data = array_merge($data, $otherdata);
        }

        $Order = $controller->Manager->new_entry("orders", "id", $data);
        return $Order["id"];
    }

}