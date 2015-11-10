<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;

include_once(APP . '../webroot/subpages/soap/nusoap.php');
include_once('subpages/api.php');

class DocumentsController extends AppController{
    function translate(){////veritas3-0\webroot\Locale\[language]\LC_MESSAGES will need clearing of duplicate mo files
        $language = $this->request->session()->read('Profile.language');
        $acceptablelanguages = $this->Settings->acceptablelanguages(false);;
        if (!in_array($language, $acceptablelanguages)) { $language = $acceptablelanguages[0]; }//default to english
        $this->set("language", $language);
        $this->response->body($language);
        return $this->response;
        die();
    }

    function isdebugging(){
        $this->response->body($this->request->session()->read('debug'));
        return $this->response;
        die();
    }

    public $paginate = [
        'limit' => 10,
        'order' => ['id' => 'DESC'],
    ];

    public function initialize(){
        parent::initialize();
        $this->loadComponent('Settings');
        $this->loadComponent('Document');
        $this->loadComponent('Mailer');
        $this->loadComponent('Trans');

        //$this->Settings->verifylogin($this, "documents");
    }

    public function index() {
        $cond = '';
        $this->set('doc_comp', $this->Document);

        $this->Document->fixsubmittedfor();

        $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));
        $doc = $this->Document->getDocumentcount();
        $cn = $this->Document->getUserDocumentcount();
        if ($setting->document_list == 0 || count($doc) == 0 || $cn == 0) {
            $this->Flash->error($this->Trans->getString("flash_cantviewdocs"));
            return $this->redirect("/");
        }
        $sess = $this->request->session()->read('Profile.id');

        if (!$this->request->session()->read('Profile.super')) {
            $setting = $this->Settings->get_permission($sess);
            if ($setting && $setting->document_others == 0) {
                $cond = 'user_id = ' . $sess;
            }
        }


        $language = $this->request->session()->read('Profile.language');




        $docs = TableRegistry::get('Documents');
        $cls = TableRegistry::get('Clients');
        //$attachments = TableRegistry::get('attachments');

        $cl = $cls->find()->where(['(profile_id LIKE "' . $sess . ',%" OR profile_id LIKE "%,' . $sess . ',%" OR profile_id LIKE "%,' . $sess . '%")'])->all();
        $cli_id = '999999999';
        foreach ($cl as $cc) {
            $cli_id = $cli_id . ',' . $cc->id;
        }
        $doc = $docs->find();
        $doc = $doc->select()->where(['(order_id = 0 OR (order_id <> 0 AND order_id IN (SELECT id FROM orders)))']);
        if (isset($_GET['draft'])) {
            $doc = $doc->select()->where(['draft' => 1, '(order_id = 0 OR (order_id <> 0 AND order_id IN (SELECT id FROM orders)))']);
        }

        if (isset($_GET['searchdoc']) && $_GET['searchdoc']) {
            $cond = $this->AppendSQL($cond, '(title LIKE "%' . $_GET['searchdoc'] . '%" OR document_type LIKE "%' . $_GET['searchdoc'] . '%" OR description LIKE "%' . $_GET['searchdoc'] . '%")');
        }

        if (!$this->request->session()->read('Profile.admin')){
            if ($setting->document_others == 0) {
                $cond = $this->AppendSQL($cond, 'user_id = ' . $sess);
            } else {
                $cond = $this->AppendSQL($cond, 'client_id IN (' . $cli_id . ')');
            }
        }

        if (isset($_GET['submitted_by_id']) && $_GET['submitted_by_id']) {
            $cond = $this->AppendSQL($cond, 'user_id = ' . $_GET['submitted_by_id']);
        }

        if (isset($_GET['submitted_for_id']) && $_GET['submitted_for_id']) {
            $cond = $this->AppendSQL($cond, 'uploaded_for = ' . $_GET['submitted_for_id']);
        }

        if (isset($_GET['client_id']) && $_GET['client_id']) {
            $cond = $this->AppendSQL($cond, 'client_id = ' . $_GET['client_id']);
        }

        if (isset($_GET['type']) && $_GET['type']) {
            $cond = $this->AppendSQL($cond, 'sub_doc_id = "' . $_GET['type'] . '"');
        }

        if (isset($_GET['from']) && isset($_GET['to'])) {
            $f = date('Y-m-d h:i:s', strtotime($_GET['from']));
            $t = date('Y-m-d h:i:s', strtotime($_GET['to']));
            $cond = $this->AppendSQL($cond, '(created >="' . $f . '" AND created <= "' . $t . '")');
        }

        $cond = $this->AppendSQL($cond, '(order_id = 0 OR (order_id <> 0 AND order_id IN (SELECT id FROM orders)))');

        //$cond = $cond . " LEFT JOIN attachments ON attachments.document_id = Documents__id";
        // $attachments = TableRegistry::get('attachments');
        //$attachment = $attachments->find()->where(['document_id' => $did])->all();
        //$this->set('attachments', $attachment);

        $this->set('userclients', "");
        if (!$this->request->session()->read('Profile.super')) {
            $clients_id = $this->Settings->getAllClientsId($sess);
            if($clients_id && !strpos($clients_id, ",")){
                $this->set('userclients', $clients_id);
                $cond = $this->AppendSQL($cond, ' client_id = ' . $clients_id);
            }
        }


        if ($cond) {
           // debug($doc);die($cond);
            $doc = $doc->where([$cond]);
        }

        if (isset($_GET['searchdoc'])) {
            $this->set('search_text', $_GET['searchdoc']);
        }
        if (isset($_GET['submitted_by_id'])) {
            $this->set('return_user_id', $_GET['submitted_by_id']);
        }
        if (isset($_GET['submitted_for_id'])) {
            $this->set('return_submitted_for_id', $_GET['submitted_for_id']);
        }
        if (isset($_GET['client_id'])) {
            $this->set('return_client_id', $_GET['client_id']);
        }
        if (isset($_GET['type'])) {
            $this->set('return_type', $_GET['type']);
        }
        $this->set('documents', $this->appendattachments($this->paginate($doc), $language));
        if (isset($_GET['flash'])) {
            $this->success(true, "", false);//I don't know why it doesn't redirect.
        }

        $usertype = TableRegistry::get('profiles')->find()->where(['id'=>$sess])->first()->profile_type;
        $profiletype = TableRegistry::get('profile_types')->find()->where(['id'=>$usertype])->first();
        $this->set('profiletype', $profiletype);
    }

    function AppendSQL($SQL, $Query){
        if($SQL && $Query){ return $SQL . " AND " . $Query; }
        return $Query;
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

    public function load_sub_doc($table, $varname, $did = 0){
        $survey = TableRegistry::get($table);
        //$pre_at = TableRegistry::get('driver_application_accident');
        if(!isset($_GET['order_id'])) {
            $sur = $survey->find()->where(['document_id' => $did])->first();
        }else {
            $sur = $survey->find()->where(['order_id' => $_GET['order_id']])->first();
        }
        $this->set($varname, $sur);
        return $sur;
    }

    public function view($cid = 0, $did = 0) {
        $this->set('doc_comp',$this->Document);
        $meedocs = TableRegistry::get('mee_attachments_more');
        $this->set('meedocs',$meedocs);
        $subdoc = TableRegistry::get('subdocuments');
        $this->set('subdoc',$subdoc);
        $clients = TableRegistry::get('Clients');
        $c = $clients->find()->all();
        $this->set('clients', $c);
        if (!$this->request->session()->read('Profile.id')) {
            $this->redirect('/login');
        } else {
            $this->set('cid', $cid);
            $this->set('did', $did);
            $this->set('sid', '');
            if ($did) {
                $docs = TableRegistry::get('documents');
                $document = $docs->find()->where(['id' => $did])->first();
                $this->set('mod', $document);

                $order = TableRegistry::get('orders')->find()->where(['id' => $document->order_id])->first();
                if($order) {
                    $this->set('forms', explode(",", $order->forms));
                } else {
                    $this->set('forms', '');
                }
                $profile = TableRegistry::get('profiles')->find()->where(['id' => $document->user_id])->first();

                if(is_object($profile)) {
                    $this->set('DriverProvince', $profile->driver_province);
                    $this->set('profile', $profile);
                }
                $att = TableRegistry::get('attach_docs');
                $query = $att->find();
                $attachments = $query->select()->where(['doc_id'=>$did])->all();
                $this->set('attachments',$attachments);
            }
            $doc = $this->Document->getDocumentcount();
            $cn = $this->Document->getUserDocumentcount();
            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));
            $doc = $this->Document->getDocumentcount();
            $cn = $this->Document->getUserDocumentcount();
            if ($setting->document_list == 0 || count($doc) == 0 || $cn == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (024)');
                return $this->redirect("/");
            }
            $this->set('disabled', 1);
            if ($did) {
                $doc = TableRegistry::get('Documents');
                $query = $doc->find()->where(['id' => $did])->first();
                $query = $doc->find()->where(['id' => $did])->first();
                $sub_doc = TableRegistry::get('Subdocuments');
                $sd = $sub_doc->find()->all();

                foreach($sd as $s) {
                    if($s->id >12) {
                        if ($query->sub_doc_id == $s->id) {
                            //echo $s->table_name;
                            $mods = TableRegistry::get($s->table_name);
                            if(!isset($_GET['order_id'])) {
                                $mod = $mods->find()->where(['document_id' => $did])->first();
                            } else {
                                $mod = $mods->find()->where(['order_id' => $_GET['order_id']])->first();
                            }
                            $this->set($s->table_name, $mod);
                        }
                    }
                }
                switch($query->sub_doc_id){
                    case 5:
                        $this->load_sub_doc('Survey', 'survey', $did);
                        break;
                    case 6:
                        $this->load_sub_doc('feedbacks', 'feeds', $did);
                        break;
                    case 7:
                        $this->load_sub_doc('attachments', 'attach', $did);
                        break;
                    case 8:
                        $this->load_sub_doc('audits', 'audits', $did);
                        break;
                    case 11:
                        $this->load_sub_doc('generic_forms', 'generic', $did);
                        break;
                    case 12:
                        $this->load_sub_doc('abstract_forms', 'abstract', $did);
                        break;
                    case 24:
                        $this->load_sub_doc('edu_verifs', 'edu_verifs', $did);
                        break;
                }

                $pre = TableRegistry::get('doc_attachments');
                //$pre_at = TableRegistry::get('driver_application_accident');
                if(!isset($_GET['order_id'])) {
                    $pre_at['attach_doc'] = $pre->find()->where(['document_id' => $did])->all();
                }else {
                    $pre_at['attach_doc'] = $pre->find()->where(['order_id' => $_GET['order_id'], 'sub_id' => 1])->all();
                }
                $this->set('pre_at', $pre_at);

                $mee_a['attach_doc'] = $this->load_sub_doc('mee_attachments', 'mee_att', $did);
                $this->set("mee_att", $mee_a);

                $this->load_sub_doc('pre_screening', 'ps_detail', $did);
                $this->load_sub_doc('road_test', 'deval_detail', $did);
                $da_detail = $this->load_sub_doc('driver_application', 'da_detail', $did);

                if ($da_detail) {
                    $da_ac = TableRegistry::get('driver_application_accident');
                    $sub['da_ac_detail'] = $da_ac->find()->where(['driver_application_id' => $da_detail->id])->all();

                    $da_li = TableRegistry::get('driver_application_licenses');
                    $sub['da_li_detail'] = $da_li->find()->where(['driver_application_id' => $da_detail->id])->all();

                    $da_at = TableRegistry::get('doc_attachments');
                    if(!isset($_GET['order_id'])) {
                        $sub['da_at'] = $da_at->find()->where(['document_id' => $did])->all();
                    }else {
                        $sub['da_at'] = $da_at->find()->where(['order_id' => $_GET['order_id'], 'sub_id' => 2])->all();
                    }
                    $this->set('sub', $sub);
                }

                $de = TableRegistry::get('road_test');
                if(!isset($_GET['order_id'])) {
                    $de_detail = $de->find()->where(['document_id' => $did])->first();
                }else {
                    $de_detail = $de->find()->where(['order_id' => $_GET['order_id']])->first();
                }
                if ($de_detail) {
                    $de_at = TableRegistry::get('doc_attachments');
                    if(!isset($_GET['order_id'])) {
                        $sub['de_at'] = $de_at->find()->where(['document_id' => $did])->all();
                    }else {
                        $sub['de_at'] = $de_at->find()->where(['order_id' => $_GET['order_id'], 'sub_id' => 3])->all();
                    }
                    $this->set('sub', $sub);
                }


                $con = TableRegistry::get('consent_form');
                if(!isset($_GET['order_id'])) {
                    $con_detail = $con->find()->where(['document_id' => $did])->first();
                }else {
                    $con_detail = $con->find()->where(['order_id' => $_GET['order_id']])->first();
                }
                if ($con_detail) {
                    //echo $con_detail->id;die();
                    $con_cri = TableRegistry::get('consent_form_criminal');
                    $sub2['con_cri'] = $con_cri->find()->where(['consent_form_id' => $con_detail->id])->all();

                    $con_at = TableRegistry::get('doc_attachments');
                    if(!isset($_GET['order_id'])) {
                        $sub2['con_at'] = $con_at->find()->where(['document_id' => $did])->all();
                    }else {
                        $sub2['con_at'] = $con_at->find()->where(['order_id' => $_GET['order_id'], 'sub_id' => 4])->all();
                    }
                    $this->set('sub2', $sub2);
                    $this->set('consent_detail', $con_detail);
                }

                $emp = TableRegistry::get('employment_verification');
                if(!isset($_GET['order_id'])) {
                    $sub3['emp'] = $emp->find()->where(['document_id' => $did])->all();
                }else {
                    $sub3['emp'] = $emp->find()->where(['order_id' => $_GET['order_id']])->all();
                }
                //echo $con_detail->id;die();
                $emp_att = TableRegistry::get('doc_attachments');
                if(!isset($_GET['order_id'])) {
                    $sub3['att'] = $emp_att->find()->where(['document_id' => $did])->all();
                }else {
                    $sub3['att'] = $emp_att->find()->where(['order_id' => $_GET['order_id'], 'sub_id' => 41])->all();
                }
                $this->set('sub3', $sub3);

                $edu = TableRegistry::get('education_verification');
                if(!isset($_GET['order_id'])) {
                    $sub4['edu'] = $edu->find()->where(['document_id' => $did])->all();
                }else {
                    $sub4['edu'] = $edu->find()->where(['order_id' => $_GET['order_id']])->all();
                }
                //echo $con_detail->id;die();
                $edu_att = TableRegistry::get('doc_attachments');
                if(!isset($_GET['order_id'])) {
                    $sub4['att'] = $edu_att->find()->where(['document_id' => $did])->all();
                }else {
                    $sub4['att'] = $edu_att->find()->where(['order_id' => $_GET['order_id'], 'sub_id' => 41])->all();
                }
                $this->set('sub4', $sub4);
            }
            $this->render('add');
        }
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




    function getdoc($Table, $did, $Set= ""){
        if(!$Set){$Set=$Table;}
        $Table = TableRegistry::get($Table);
        if(!isset($_GET['order_id'])) {
            $Table = $Table->find()->where(['document_id' => $did])->first();
        }else {
            $Table = $Table->find()->where(['order_id' => $_GET['order_id']])->first();
        }
        if($Set != "NA") {$this->set($Set, $Table);}
        return $Table;
    }
    function add($cid = 0, $did = 0, $type = NULL){
        $clients = TableRegistry::get('Clients');
        if($cid==0) {
            $clientcount = $clients->find('all')->count();
            if($clientcount==1){
                $cid = $clients->find('all')->first()->id;
            }
        }
        if($cid) {
            $this->set('client', $clients->find()->where(['id' => $cid])->first());
        }
        $this->set('doc_comp',$this->Document);
        $this->set('cid', $cid);
        $this->set('did', $did);
        $this->set('sid', '');
        $subdoc = TableRegistry::get('subdocuments');
        $this->set('subdoc',$subdoc);
        $meedocs = TableRegistry::get('mee_attachments_more');
        $this->set('meedocs',$meedocs);

        $c = $clients->find()->order('company_name')->all();
        $this->set('clients', $c);
        if ($did) {
            $docs = TableRegistry::get('documents');
            $document = $docs->find()->where(['id' => $did])->first();
            $this->set('mod', $document);

            $att = TableRegistry::get('doc_attachments');
            $query = $att->find();
            $attachments = $query->select()->where(['document_id'=>$did])->all();

            $this->set('attachments',$attachments);
        }
        $doc = $this->Document->getDocumentcount();
        $cn = $this->Document->getUserDocumentcount();
        $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));
        //var_dump($setting);die();
        if (is_null($type)) {
            if ($did != 0) {
                $doc = TableRegistry::get('Documents');
                $query = $doc->find()->where(['id' => $did])->first();
                $this->set('document', $query);
                if ($setting->document_edit == 0 || count($doc) == 0 || $cn == 0) {
                    $this->Flash->error($this->Trans->getString("flash_cantuploaddocs"));
                }
            } else {
               if ($setting->document_create == 0 || count($doc) == 0 || $cn == 0) {
                    $this->Flash->error($this->Trans->getString("flash_cantuploaddocs"));
                }
            }
            if (isset($_POST['uploaded_for'])) {
                $docs = TableRegistry::get('Documents');

                $arr['uploaded_for'] = $_POST['uploaded_for'];
                $arr['client_id'] = $cid;
                if (isset($_POST['document_type']))
                    $arr['document_type'] = $_POST['document_type'];
                $arr['created'] = date('Y-m-d H:i:s');

                if (!$did || $did == '0') {
                    $arr['user_id'] = $this->request->session()->read('Profile.id');
                    $doc = $docs->newEntity($arr);
                    if ($docs->save($doc)) {
                        $this->Flash->success($this->Trans->getString("flash_docsaved"));
                        $this->redirect('/documents');
                    } else {
                        //$this->Flash->error('Client could not be saved. Please try again.');
                        //echo "e";
                    }

                } else {
                    $query2 = $docs->query();
                    $query2->update()
                        ->set($arr)
                        ->where(['id' => $did])
                        ->execute();
                    $this->Flash->success( $this->Trans->getString("flash_docsaved"));
                    $this->redirect('/documents');
                }
            }
        } else {

            if ($did != 0) {
                $doc = TableRegistry::get('orders');
                $query = $doc->find()->where(['id' => $did])->first();
                $this->set('document', $query);
                if ($setting->document_edit == 0 || count($doc) == 0 || $cn == 0) {
                    $this->Flash->error($this->Trans->getString("flash_permissions") . ' (023)');
                    return $this->redirect("/");

                }

            } else {
                if ($setting->document_create == 0 || count($doc) == 0 || $cn == 0) {
                    $this->Flash->error($this->Trans->getString("flash_permissions") . ' (022)');
                    return $this->redirect("/");

                }
            }
            if (isset($_POST['uploaded_for'])) {
                $docs = TableRegistry::get('orders');

                $arr['uploaded_for'] = $_POST['uploaded_for'];
                $arr['client_id'] = $cid;
                if (isset($_POST['order_type']))
                    $arr['order_type'] = $_POST['order_type'];
                $arr['created'] = date('Y-m-d H:i:s');

                if (!$did || $did == '0') {
                    $arr['user_id'] = $this->request->session()->read('Profile.id');
                    $doc = $docs->newEntity($arr);
                    if ($docs->save($doc)) {
                        $this->Flash->success($this->Trans->getString("flash_docsaved"));
                        $this->redirect('orders/orderslist');
                    } else {
                        //$this->Flash->error('Client could not be saved. Please try again.');
                        //echo "e";
                    }

                } else {
                    $query2 = $docs->query();
                    $query2->update()
                        ->set($arr)
                        ->where(['id' => $did])
                        ->execute();

                    $this->Flash->success($this->Trans->getString("flash_docsaved"));
                    $this->redirect('/documents');
                }
            }
        }

        if ($did) {
            $doc = TableRegistry::get('Documents');

            $query = $doc->find()->where(['id' => $did])->first();
            $sub_doc = TableRegistry::get('Subdocuments');
            $sd = $sub_doc->find()->all();

            foreach($sd as $s) {
                if($s->id >12) {
                    if ($query->sub_doc_id == $s->id) {
                        $this->getdoc($s->table_name, $did);
                    }
                }
            }

            if ($query->sub_doc_id == '6') {
                $this->getdoc('feedbacks', $did, 'feeds');
            } elseif ($query->sub_doc_id == '5') {
                $this->getdoc('Survey', $did, 'survey');
            } elseif ($query->sub_doc_id == '7') {
                $this->getdoc('attachments', $did, 'attach');
            }  elseif ($query->sub_doc_id == '8') {
                $this->getdoc('audits', $did, 'audits');
            } elseif ($query->sub_doc_id == '11') {
                $this->getdoc('generic_forms', $did, 'generic');
            } elseif ($query->sub_doc_id == '12') {
                $this->getdoc('abstract_forms', $did, 'abstract');
            }

            $this->getdoc('doc_attachments', $did, 'pre_at');
            $this->getdoc('mee_attachments', $did, 'mee_att');
            $this->getdoc('pre_screening', $did, 'ps_detail');
            $this->getdoc('road_test', $did, 'deval_detail');
            $da_detail = $this->getdoc('driver_application', $did, 'da_detail');

            if ($da_detail) {
                $da_ac = TableRegistry::get('driver_application_accident');
                $sub['da_ac_detail'] = $da_ac->find()->where(['driver_application_id' => $da_detail->id])->all();

                $da_li = TableRegistry::get('driver_application_licenses');
                $sub['da_li_detail'] = $da_li->find()->where(['driver_application_id' => $da_detail->id])->all();

                $da_at = TableRegistry::get('doc_attachments');
                if(!isset($_GET['order_id'])) {
                    $sub['da_at'] = $da_at->find()->where(['document_id' => $did])->all();
                }else {
                    $sub['da_at'] = $da_at->find()->where(['order_id' => $_GET['order_id'], 'sub_id' => 2])->all();
                }

                $this->set('sub', $sub);
            }

            $de_detail =  $this->getdoc('road_test', $did, 'NA');
            if ($de_detail) {
                $de_at = TableRegistry::get('doc_attachments');
                if(!isset($_GET['order_id'])) {
                    $sub['de_at'] = $de_at->find()->where(['document_id' => $did])->all();
                }else {
                    $sub['de_at'] = $de_at->find()->where(['order_id' => $_GET['order_id'], 'sub_id' => 3])->all();
                }
                $this->set('sub', $sub);
            }


            $con_detail =  $this->getdoc('consent_form', $did, 'NA');
            if ($con_detail) {
                //echo $con_detail->id;die();
                $con_cri = TableRegistry::get('consent_form_criminal');
                $sub2['con_cri'] = $con_cri->find()->where(['consent_form_id' => $con_detail->id])->all();
                $con_at = TableRegistry::get('doc_attachments');
                if(!isset($_GET['order_id'])) {
                    $sub2['con_at'] = $con_at->find()->where(['document_id' => $did])->all();
                }else {
                    $sub2['con_at'] = $con_at->find()->where(['order_id' => $_GET['order_id'], 'sub_id' => 4])->all();
                }
                $this->set('sub2', $sub2);
                $this->set('consent_detail', $con_detail);

            }

            $sub3['emp'] = $this->getdoc('employment_verification', $did, 'NA');
            $sub3['edu'] = $this->getdoc('education_verification', $did, 'NA');

            //echo $con_detail->id;die();
            $emp = TableRegistry::get('employment_verification');
            if(!isset($_GET['order_id'])) {
                $sub3['emp'] = $emp->find()->where(['document_id' => $did])->all();
            }else {
                $sub3['emp'] = $emp->find()->where(['order_id' => $_GET['order_id']])->all();
            }
            $emp_att = TableRegistry::get('doc_attachments');
            if(!isset($_GET['order_id'])) {
                $sub3['att'] = $emp_att->find()->where(['document_id' => $did])->all();
            }else {
                $sub3['att'] = $emp_att->find()->where(['order_id' => $_GET['order_id'], 'sub_id' => 41])->all();
            }
            $this->set('sub3', $sub3);

            $edu_att = TableRegistry::get('doc_attachments');
            if(!isset($_GET['order_id'])) {
                $sub4['att'] = $edu_att->find()->where(['document_id' => $did])->all();
            }else {
                $sub4['att'] = $edu_att->find()->where(['order_id' => $_GET['order_id'], 'sub_id' => 42])->all();
            }
            $edu = TableRegistry::get('education_verification');
            if(!isset($_GET['order_id'])) {
                $sub4['edu'] = $edu->find()->where(['document_id' => $did])->all();
            }else {
                $sub4['edu'] = $edu->find()->where(['order_id' => $_GET['order_id']])->all();
            }
            $this->set('sub4', $sub4);
             $mee_a['attach_doc'] = $this->load_sub_doc('mee_attachments', 'mee_att', $did);
             $this->set("mee_att", $mee_a);

        }
    }

    public function forMail() {
        $settings = TableRegistry::get('settings');
        $setting = $settings->find()->first();
        $pro_query = TableRegistry::get('Profiles');
        $email_query = $pro_query->find()->where(['super' => 1])->first();
        $em = $email_query->email;
        $user_id = $this->request->session()->read('Profile.id');
        $uq = $pro_query->find('all')->where(['id' => $user_id])->first();
        if (isset($uq->profile_type)) {
            $u = $uq->profile_type;
            $ut = $this->profiletype($u);
        }
        $path = $this->Document->getUrl();
        $this->Mailer->handleevent("clientcreated", array("email" => $em, "company_name" => $_POST['company_name'], "profile_type" => $ut, "username" => $uq->username, "created" =>$_POST['created'], "path" => $path, "site" => $setting->mee));
    }

    function profiletype($type){
        return TableRegistry::get('profile_types')->find()->where(['id'=>$type])->first()->title;
    }

    public function delete($id = null, $type = ""){
        $settings = $this->Settings->get_settings();
        $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));

        if ($setting->document_delete == 0) {
            $this->Flash->error($this->Trans->getString("flash_permissions") . ' (021)');
            return $this->redirect("/");

        }
        if ($id != "") {
            $doc = TableRegistry::get('Subdocuments');
            $query = $doc->find();
            if ($type == 'orders') {
                $query->select()->where(['display' => 1, 'orders' => 1])->all();
            } else {
                $query->select()->where(['display' => 1, 'orders' => 0])->all();
            }

            if ($this->Documents->deleteAll(array('id' => $id))) {
                $this->Flash->success($this->Trans->getString("flash_docdeleted"));
            } else {
                $this->Flash->error($this->Trans->getString("flash_docnotdeleted"));
            }

            if($type=='draft') {
                return $this->redirect('/documents/index?draft');
            } else {
                return $this->redirect('/documents/index');
            }

        }
    }

    public function subpages($filename) {
        $this->set('doc_comp',$this->Document);
        $this->layout = "blank";
        $this->set("filename", $filename);
    }

    public function stats() {

    }

    public function drafts() {

    }

    function analytics1(){
        $this->layout = "blank";
    }

    function analytics(){
        $isAdmin = $this->Manager->read("super") || $this->Manager->read("admin");
        $conditions = array('draft' => 0);
        $isAdmin=false;
        if(!$isAdmin){
            $ClientIDs = $this->Manager->find_client("", false);
            if($ClientIDs){
                if (is_array($ClientIDs)) {
                    $conditions[] = "client_id = " . implode(" OR client_ID = ", $ClientIDs);
                } else {
                    $conditions['client_id'] = $ClientIDs;
                    $this->set('client',$this->Manager->get_client($ClientIDs));
                }
            }
        }

        $this->set('doc_comp',$this->Document);
        $orders = TableRegistry::get('orders');
        $order = $orders->find()->order(['orders.id' => 'DESC'])->where($conditions)->select();
        $this->set('orders', $order);

        $docs = TableRegistry::get('documents');
        $doc = $docs->find()->select()->where($conditions);
        $this->set('documents',$doc);

        $clients = TableRegistry::get('Clients');
        $cli =  $clients->find()->select();
        $this->set('clients', $cli);

        $profiles = TableRegistry::get('Profiles');
        $pro =  $profiles->find()->select();
        $this->set('profiles', $pro);

        $quizzes = TableRegistry::get('training_list');
        $qui = $quizzes->find()->select();
        $this->set('courses', $qui);

        $answers = TableRegistry::get('training_answers');
        $ans =  $answers->find('all',array('group' => array('UserID', "QuizID")));
        $this->set('answers', $ans);

        $this->set('profiletypes', TableRegistry::get('profile_types')->find()->select());
        $this->set('clienttypes',  TableRegistry::get('client_types')->find()->select());

        $this->set('subdocuments', TableRegistry::get('subdocuments')->find()->select());
    }


    function removefiles($file) {
        if(isset($_POST['id']) && $_POST['id']!= 0) {
            $this->loadModel("AttachDocs");
            $this->AttachDocs->deleteAll(['id'=>$_POST['id']]);

        }
        unlink(WWW_ROOT."img/jobs/" . $file);
        die();
    }
    function get_documentcount($subdocid, $c_id = "") {
        $this->set('doc_comp',$this->Document);
        //$cond = $this->Settings->getprofilebyclient($this->request->session()->read('Profile.id'),0);
        //var_dump($cond);die();
        $u = $this->request->session()->read('Profile.id');

        if (!$this->request->session()->read('Profile.super')) {
            $setting = $this->Settings->get_permission($u);
            if ($setting->document_others == 0) {
                $u_cond = "user_id=$u";
            } else {
                $u_cond = "";
            }
        } else {
            $u_cond = "";
        }
        $model = TableRegistry::get("Documents");
        if ($c_id != "") {
            $cnt = $model->find()->where(["sub_doc_id" => $subdocid, 'draft' => '0', $u_cond, 'client_id' => $c_id])->count();
        } else {
            $cond = $this->Settings->getclientids($u, $this->request->session()->read('Profile.super'));
            $cnt = $model->find()->where(["sub_doc_id" => $subdocid, 'draft' => '0', $u_cond, 'OR' => $cond])->count();
        }
        //debug($cnt); die();
        $this->response->body(($cnt));
        return $this->response;
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



    function survey(){
        $this->render('survey');
    }



    function addattachment($cid, $did){
        if (isset($_POST) && isset($_GET['draft'])) {
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
            $arr['title'] = $_POST['title'];
            $arr['created'] = date('Y-m-d H:i:s');
            if(!isset($_REQUEST['order_id'])){
                if (!$did || $did == '0') {

                    $arr['user_id'] = $this->request->session()->read('Profile.id');

                    $docs = TableRegistry::get('Documents');
                    $doc = $docs->newEntity($arr);

                    if ($docs->save($doc)) {

                        $doczs = TableRegistry::get('attachments');
                        $ds['document_id'] = $doc->id;
                        $ds['title'] = $_POST['title'];
                        $docz = $doczs->newEntity($ds);
                        $doczs->save($docz);
                        if(isset($_POST['attach_doc'])) {
                            $model = $this->loadModel('DocAttachments');
                            $model->deleteAll(['document_id'=> $doc->id]);

                            $client_docs = $_POST['attach_doc'];
                            foreach($client_docs as $d) {
                                if($d != "") {
                                    $attach = TableRegistry::get('doc_attachments');
                                    $ds['document_id']= $doc->id;
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
                    $this->loadModel('Attachments');
                    $this->Attachments->deleteAll(['document_id' => $did]);
                    $doczs = TableRegistry::get('attachments');
                    $ds['document_id'] = $did;
                    $ds['title'] = $_POST['title'];
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
                    $this->success(True, $draft);
                }
            } else {
                $did = $_REQUEST['order_id'];
                $doczs = TableRegistry::get('attachments');
                $check = $doczs->find()->where(['order_id'=>$did]);
                unset($doczs);
                if ($check) {
                    $this->loadModel('Attachments');
                    $this->Attachments->deleteAll(['order_id' => $did]);
                    $doczs = TableRegistry::get('attachments');
                    $ds['order_id'] = $did;
                    $ds['document_id'] = 0;
                    $ds['title'] = $_POST['title'];
                    $docz = $doczs->newEntity($ds);
                    $doczs->save($docz);
                    unset($doczs);
                }
                $arr['document_id'] = 0;

                if(!isset($_GET['order_id'])) {
                    $arr['order_id'] = $did;
                }else{
                    $arr['order_id'] = $_GET['order_id'];
                    $did = $_GET['order_id'];
                }
                $arr['document_id'] = 0;
                $uploaded_for = '';
                if (isset($_POST['uploaded_for'])) {
                    $uploaded_for = $_POST['uploaded_for'];
                }
                $for_doc = array('document_type'=>'Attachment','sub_doc_id'=>7,'order_id'=>$arr['order_id'],'user_id'=>'','uploaded_for'=>$uploaded_for);
                $this->Document->saveDocForOrder($for_doc);

                if(isset($_POST['attach_doc'])) {
                    $model = $this->loadModel('DocAttachments');
                    $model->deleteAll(['order_id'=> $arr['order_id'],'sub_id'=>7]);
                    $client_docs = $_POST['attach_doc'];
                    foreach($client_docs as $d) {
                        if($d != "") {
                            $attach = TableRegistry::get('doc_attachments');
                            $ds['order_id']= $arr['order_id'];
                            $ds['attachment'] =$d;
                            $ds['sub_id'] = 7;
                            $att = $attach->newEntity($ds);
                            $attach->save($att);
                            unset($att);
                        }
                    }
                }
                die();
            }
        }
    }

    function audits($cid, $did){
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
                        $doczs = TableRegistry::get('audits');
                        if(!isset($_GET['order_id']) && !isset($_POST['oder_id']))
                        $ds['document_id'] = $doc->id;
                        $ds['date'] = $_POST['year']."-".$_POST['month'];
                        foreach($_POST as $k=>$v) {
                            $ds[$k]=$v;
                        }
                        $docz = $doczs->newEntity($ds);
                        $doczs->save($docz);
                        $did = $doc->id;
                        if(isset($_POST['attach_doc'])) {
                            //var_dump($_POST['attach_doc']);die();
                            $model = $this->loadModel('doc_attachments');
                            $model->deleteAll(['document_id'=> $did]);
                            //$client_do = implode(',',$_POST['attach_doc']);
                            //$client_docs=explode(',',$client_do);
                            foreach($_POST['attach_doc'] as $d) {
                                if($d != "") {
                                    $attach = TableRegistry::get('doc_attachments');
                                    $ds['document_id']= $did;
                                    $ds['attachment'] =$d;
                                     $att = $attach->newEntity($ds);
                                     $attach->save($att);
                                    unset($att);
                                }
                            }
                            
                        }
                        unset($doczs);
                        //$this->Flash->success('Document saved successfully.');//shouldn't this use the settings variable for document?
                        //$this->redirect(array('action' => 'index'.$draft));
                        $this->success(True, $draft);
                    } else {
                        //$this->Flash->error('Document could not be saved. Please try again.');
                        //$this->redirect(array('action' => 'index'.$draft));
                        $this->success(False, $draft);
                    }

                } else {
                    $docs = TableRegistry::get('Documents');
                    $query2 = $docs->query();
                    $query2->update()
                        ->set($arr)
                        ->where(['id' => $did])
                        ->execute();
                        $this->loadModel('Audits');
                        $this->Audits->deleteAll(['document_id' => $did]);
                        $doczs = TableRegistry::get('audits');
                        $ds['document_id'] = $did;
                        $ds['date'] = $_POST['year']."-".$_POST['month'];
                        foreach($_POST as $k=>$v) {
                            $ds[$k]=$v;
                        }
                        $docz = $doczs->newEntity($ds);
                        $doczs->save($docz);
                        if(isset($_POST['attach_doc'])) {
                           
                            $model = $this->loadModel('doc_attachments');
                            $model->deleteAll(['document_id'=> $did]);
                            $client_docs = $_POST['attach_doc'];
                            foreach($client_docs as $d) {
                                if($d != "") {
                                    $attach = TableRegistry::get('doc_attachments');
                                    $ds['document_id']= $did;
                                    $ds['attachment'] =$d;
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
                    
                    $doczs = TableRegistry::get('audits');
                    $check = $doczs->find()->where(['order_id'=>$did])->first();
                    unset($doczs);
                    if (!$check) {
                        $ds['order_id'] = $did;
                        $ds['document_id'] = 0;
                        $ds['date'] = $_POST['year']."-".$_POST['month'];
                        $doczs = TableRegistry::get('audits');
                        foreach($_POST as $k=>$v) {
                            $ds[$k]=$v;
                        }
                        $docz = $doczs->newEntity($ds);
                        $doczs->save($docz);
                        unset($doczs);
                        } else {
                            $this->loadModel('Audits');
                          $this->Audits->deleteAll(['order_id' => $did]);
                            $doczs = TableRegistry::get('audits');
                            $ds['order_id'] = $did;
                            $ds['date'] = $_POST['year']."-".$_POST['month'];
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

    function pre_employment_road_test($cid, $did){
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
                        $doczs = TableRegistry::get('pre_employment_road_test');
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
                    $this->loadModel('pre_employment_road_test');
                    $this->pre_employment_road_test->deleteAll(['document_id' => $did]);
                    $doczs = TableRegistry::get('pre_employment_road_test');
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
                $for_doc = array('document_type'=>'pre_employment_road_test','sub_doc_id'=>17,'order_id'=>$arr['order_id'],'user_id'=>'','uploaded_for'=>$uploaded_for);
                $this->Document->saveDocForOrder($for_doc);

                $doczs = TableRegistry::get('pre_employment_road_test');
                $check = $doczs->find()->where(['order_id'=>$did])->first();
                unset($doczs);
                if (!$check) {
                    $ds['order_id'] = $did;
                    $ds['document_id'] = 0;
                    $doczs = TableRegistry::get('pre_employment_road_test');
                    foreach($_POST as $k=>$v) {
                        $ds[$k]=$v;
                    }
                    $docz = $doczs->newEntity($ds);
                    $doczs->save($docz);
                    unset($doczs);
                } else {
                    $this->loadModel('pre_employment_road_test');
                    $this->pre_employment_road_test->deleteAll(['order_id' => $did]);
                    $doczs = TableRegistry::get('pre_employment_road_test');
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
    function basic_mee_platform($cid,$did){
        // var_dump($_POST);die();
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
                        $doczs = TableRegistry::get('basic_mee_platform');
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
                    $this->loadModel('basic_mee_platform');
                    $this->basic_mee_platform->deleteAll(['document_id' => $did]);
                    $doczs = TableRegistry::get('basic_mee_platform');
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
                if(!isset($_GET['order_id'])) {
                    $arr['order_id'] = $did;
                }else{
                    $arr['order_id'] = $_GET['order_id'];
                    $did = $_GET['order_id'];
                }
                $arr['document_id'] = 0;
                if (isset($_POST['uploaded_for'])) {
                    $uploaded_for = $_POST['uploaded_for'];
                } else {
                    $uploaded_for = '';
                }
                $for_doc = array('document_type'=>'basic_mee_platform','sub_doc_id'=>19,'order_id'=>$arr['order_id'],'user_id'=>'','uploaded_for'=>$uploaded_for);
                $this->Document->saveDocForOrder($for_doc);

                $doczs = TableRegistry::get('basic_mee_platform');
                $check = $doczs->find()->where(['order_id'=>$did])->first();
                unset($doczs);
                if (!$check) {
                    $ds['order_id'] = $did;
                    $ds['document_id'] = 0;
                    $doczs = TableRegistry::get('basic_mee_platform');
                    foreach($_POST as $k=>$v) {
                        $ds[$k]=$v;
                    }
                    $docz = $doczs->newEntity($ds);
                    $doczs->save($docz);
                    unset($doczs);
                } else {
                    $this->loadModel('basic_mee_platform');
                    $this->basic_mee_platform->deleteAll(['order_id' => $did]);
                    $doczs = TableRegistry::get('basic_mee_platform');
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
                    $arr['user_id'] = $this->request->session()->read('Profile.id');
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
                die();
            }
        }

    }

    function basic($cid, $did){
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
                    $this->success(True, $draft, True, $did);
                }
            } else {
                $arr['document_id'] = 0;
                if(!isset($_GET['order_id'])) {
                    $arr['order_id'] = $did;
                } else{
                    $arr['order_id'] = $_GET['order_id'];
                    $did = $_GET['order_id'];
                }
                $arr['document_id'] = 0;
                if (isset($_POST['uploaded_for'])) {
                    $uploaded_for = $_POST['uploaded_for'];
                }else {
                    $uploaded_for = '';
                }
                $for_doc = array('document_type'=>'Audit','sub_doc_id'=>11,'order_id'=>$arr['order_id'],'user_id'=>'','uploaded_for'=>$uploaded_for);
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
                    $this->GenericForms->deleteAll(['order_id' => $did]);
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
    function addpastemployer($cid, $did){
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

                        $doczs = TableRegistry::get('past_employment_survey');
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
                    $this->loadModel('PastEmploymentSurvey');
                    $this->PastEmploymentSurvey->deleteAll(['document_id' => $did]);
                    $doczs = TableRegistry::get('past_employment_survey');
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
                if (!isset($_GET['order_id'])){
                    $arr['order_id'] = $did;
                }else{
                    $arr['order_id'] = $_GET['order_id'];
                    $did = $_GET['order_id'];
                }
                $arr['document_id'] = 0;
                if (isset($_POST['uploaded_for'])) {
                    $uploaded_for = $_POST['uploaded_for'];
                } else {
                    $uploaded_for = '';
                }
                $for_doc = array('document_type'=>'Past Employment Survey','sub_doc_id'=>16,'order_id'=>$arr['order_id'],'user_id'=>'','uploaded_for'=>$uploaded_for);
                $this->Document->saveDocForOrder($for_doc);

                $doczs = TableRegistry::get('PastEmploymentSurvey');
                $check = $doczs->find()->where(['order_id'=>$did])->first();
                unset($doczs);
                if (!$check) {
                    $ds['order_id'] = $did;
                    $ds['document_id'] = 0;
                    $doczs = TableRegistry::get('PastEmploymentSurvey');
                    foreach($_POST as $k=>$v) {
                        $ds[$k]=$v;
                    }
                    $docz = $doczs->newEntity($ds);
                    $doczs->save($docz);
                    unset($doczs);
                } else {
                    $this->loadModel('PastEmploymentSurvey');
                    $this->PastEmploymentSurvey->deleteAll(['order_id' => $did]);
                    $doczs = TableRegistry::get('PastEmploymentSurvey');
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

    function quebec($cid, $did){
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
                        $doczs = TableRegistry::get('quebec_forms');
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
                    $this->loadModel('QuebecForms');
                    $this->QuebecForms->deleteAll(['document_id' => $did]);
                    $doczs = TableRegistry::get('quebec_forms');
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
                if(!isset($_GET['order_id'])) {
                    $arr['order_id'] = $did;
                } else{
                    $arr['order_id'] = $_GET['order_id'];
                    $did = $_GET['order_id'];
                }
                $arr['document_id'] = 0;
                if (isset($_POST['uploaded_for'])) {
                    $uploaded_for = $_POST['uploaded_for'];
                } else {
                    $uploaded_for = '';
                }
                $for_doc = array('document_type'=>'Quebic','sub_doc_id'=>13,'order_id'=>$arr['order_id'],'user_id'=>'','uploaded_for'=>$uploaded_for);
                $this->Document->saveDocForOrder($for_doc);

                $doczs = TableRegistry::get('quebec_forms');
                $check = $doczs->find()->where(['order_id'=>$did])->first();
                unset($doczs);
                if (!$check) {
                    $ds['order_id'] = $did;
                    $ds['document_id'] = 0;
                    $doczs = TableRegistry::get('quebec_forms');
                    foreach($_POST as $k=>$v) {
                        $ds[$k]=$v;
                    }
                    $docz = $doczs->newEntity($ds);
                    $doczs->save($docz);
                    unset($doczs);
                } else {
                    $this->loadModel('QuebecForms');
                    $this->QuebecForms->deleteAll(['order_id' => $did]);
                    $doczs = TableRegistry::get('quebec_forms');
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
    function investigation($cid, $did){//why are you in veritas?
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

                        $doczs = TableRegistry::get('investigations_intake_form_benefit_claims');
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
                    $this->loadModel('investigations_intake_form_benefit_claims');
                    $this->investigations_intake_form_benefit_claims->deleteAll(['document_id' => $did]);
                    $doczs = TableRegistry::get('investigations_intake_form_benefit_claims');
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
                if (!isset($_GET['order_id'])){
                    $arr['order_id'] = $did;
                }else{
                    $arr['order_id'] = $_GET['order_id'];
                    $did = $_GET['order_id'];
                }
                $arr['document_id'] = 0;
                if (isset($_POST['uploaded_for'])) {
                    $uploaded_for = $_POST['uploaded_for'];
                } else {
                    $uploaded_for = '';
                }
                $for_doc = array('document_type'=>'Investigations Intake Form – Benefit Claims','sub_doc_id'=>23,'order_id'=>$arr['order_id'],'user_id'=>'','uploaded_for'=>$uploaded_for);
                $this->Document->saveDocForOrder($for_doc);

                $doczs = TableRegistry::get('investigations_intake_form_benefit_claims');
                $check = $doczs->find()->where(['order_id'=>$did])->first();
                unset($doczs);
                if (!$check) {
                    $ds['order_id'] = $did;
                    $ds['document_id'] = 0;
                    $doczs = TableRegistry::get('investigations_intake_form_benefit_claims');
                    foreach($_POST as $k=>$v) {
                        $ds[$k]=$v;
                    }
                    $docz = $doczs->newEntity($ds);
                    $doczs->save($docz);
                    unset($doczs);
                } else {
                    $this->loadModel('investigations_intake_form_benefit_claims');
                    $this->investigations_intake_form_benefit_claims->deleteAll(['order_id' => $did]);
                    $doczs = TableRegistry::get('investigations_intake_form_benefit_claims');
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
        function footprint($cid, $did){
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

                        $doczs = TableRegistry::get('footprint');
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
                    $this->loadModel('footprint');
                    $this->footprint->deleteAll(['document_id' => $did]);
                    $doczs = TableRegistry::get('footprint');
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
                if (!isset($_GET['order_id'])){
                    $arr['order_id'] = $did;
                }else{
                    $arr['order_id'] = $_GET['order_id'];
                    $did = $_GET['order_id'];
                }
                $arr['document_id'] = 0;
                if (isset($_POST['uploaded_for'])) {
                    $uploaded_for = $_POST['uploaded_for'];
                } else {
                    $uploaded_for = '';
                }
                $for_doc = array('document_type'=>'Foot Print','sub_doc_id'=>22,'order_id'=>$arr['order_id'],'user_id'=>'','uploaded_for'=>$uploaded_for);
                $this->Document->saveDocForOrder($for_doc);

                $doczs = TableRegistry::get('footprint');
                $check = $doczs->find()->where(['order_id'=>$did])->first();
                unset($doczs);
                if (!$check) {
                    $ds['order_id'] = $did;
                    $ds['document_id'] = 0;
                    $doczs = TableRegistry::get('footprint');
                    foreach($_POST as $k=>$v) {
                        $ds[$k]=$v;
                    }
                    $docz = $doczs->newEntity($ds);
                    $doczs->save($docz);
                    unset($doczs);
                } else {
                    $this->loadModel('footprint');
                    $this->footprint->deleteAll(['order_id' => $did]);
                    $doczs = TableRegistry::get('footprint');
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
    function bc($cid, $did){
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

                        $doczs = TableRegistry::get('bc_forms');
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
                    $this->loadModel('BcForms');
                    $this->BcForms->deleteAll(['document_id' => $did]);
                    $doczs = TableRegistry::get('bc_forms');
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
                if (!isset($_GET['order_id'])){
                    $arr['order_id'] = $did;
                }else{
                    $arr['order_id'] = $_GET['order_id'];
                    $did = $_GET['order_id'];
                }
                $arr['document_id'] = 0;
                if (isset($_POST['uploaded_for'])) {
                    $uploaded_for = $_POST['uploaded_for'];
                } else {
                    $uploaded_for = '';
                }
                $for_doc = array('document_type'=>'Quebic','sub_doc_id'=>14,'order_id'=>$arr['order_id'],'user_id'=>'','uploaded_for'=>$uploaded_for);
                $this->Document->saveDocForOrder($for_doc);

                $doczs = TableRegistry::get('bc_forms');
                $check = $doczs->find()->where(['order_id'=>$did])->first();
                unset($doczs);
                if (!$check) {
                    $ds['order_id'] = $did;
                    $ds['document_id'] = 0;
                    $doczs = TableRegistry::get('bc_forms');
                    foreach($_POST as $k=>$v) {
                        $ds[$k]=$v;
                    }
                    $docz = $doczs->newEntity($ds);
                    $doczs->save($docz);
                    unset($doczs);
                } else {
                    $this->loadModel('BcForms');
                    $this->BcForms->deleteAll(['order_id' => $did]);
                    $doczs = TableRegistry::get('bc_forms');
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
    function absract($cid, $did){
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
                        $doczs = TableRegistry::get('abstract_forms');
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
                        //$this->Flash->success('Document saved successfully.');
                        //$this->redirect(array('action' => 'index'.$draft));
                        $this->success(True, $draft);
                    } else {
                        //$this->Flash->error('Document could not be saved. Please try again.');
                        //$this->redirect(array('action' => 'index'.$draft));
                        $this->success(False, $draft);
                    }
                } else {
                    $docs = TableRegistry::get('Documents');
                    $query2 = $docs->query();
                    $query2->update()
                        ->set($arr)
                        ->where(['id' => $did])
                        ->execute();
                    $this->loadModel('AbstractForms');
                    $this->AbstractForms->deleteAll(['document_id' => $did]);
                    $doczs = TableRegistry::get('abstract_forms');
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
                if (!isset($_GET['order_id'])){
                    $arr['order_id'] = $did;
                } else{
                    $arr['order_id'] = $_GET['order_id'];
                    $did = $_GET['order_id'];
                }
                $arr['document_id'] = 0;
                if (isset($_POST['uploaded_for'])) {
                    $uploaded_for = $_POST['uploaded_for'];
                } else {
                    $uploaded_for = '';
                }
                $for_doc = array('document_type'=>'Abstract','sub_doc_id'=>12,'order_id'=>$arr['order_id'],'user_id'=>'','uploaded_for'=>$uploaded_for);
                $this->Document->saveDocForOrder($for_doc);

                $doczs = TableRegistry::get('abstract_forms');
                $check = $doczs->find()->where(['order_id'=>$did])->first();
                unset($doczs);
                if (!$check) {
                    $ds['order_id'] = $did;
                    $ds['document_id'] = 0;
                    $doczs = TableRegistry::get('abstract_forms');
                    foreach($_POST as $k=>$v) {
                        $ds[$k]=$v;
                    }
                    $docz = $doczs->newEntity($ds);
                    $doczs->save($docz);
                    unset($doczs);
                } else {
                    $this->loadModel('AbstractForms');
                    $this->AbstractForms->deleteAll(['order_id' => $did]);
                    $doczs = TableRegistry::get('abstract_forms');
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


    public function saveAttachmentsPrescreen($data = NULL, $count = 0){//count is to delete all while first insertion and no delete for following insertion
        $this->Document->saveAttachmentsPrescreen($data,$count);
        die();
    }

    public function saveAttachmentsDriverApp($data = NULL, $count = 0){
        $this->Document->saveAttachmentsDriverApp($data,$count);
        die();
    }

    public function saveAttachmentsRoadTest($data = NULL, $count = 0){
        $this->Document->saveAttachmentsRoadTest($data,$count);
        die();
    }

    public function saveAttachmentsConsentForm($data = NULL, $count = 0){
        $this->Document->saveAttachmentsConsentForm($data,$count);
        die();
    }

    public function saveAttachmentsEmployment($data = NULL, $count = 0){
        $this->Document->saveAttachmentsEmployment($data,$count);
        die();
    }

    public function saveAttachmentsEducation($data = NULL, $count = 0){
        $this->Document->saveAttachmentsEducation($data,$count);
        die();
    }




    function download($file){
        $this->response->file(WWW_ROOT.'attachments/'.$file,array('download' => true));
        // Return response object to prevent controller from trying to render a view.
        return $this->response;
    }
    function download_order($oid,$file){
        $folder = 'orders/order_'.$oid.'/'.$file;
        $this->response->file(WWW_ROOT.$folder,array('download' => true));
        // Return response object to prevent controller from trying to render a view.
        return $this->response;
    }
    public function getOrderData($cid = 0, $order_id = 0){
        $this->Document->getOrderData($cid,$order_id);
        die;

    }

    function attach_doc($did="",$view="",$id='addMore1',$sub=0){
        $this->set('addmoreid',$id);
        if($view=='view') {
            $this->set('disabled', '1');
        }
        if($did){
            $att = TableRegistry::get('doc_attachments');
            $query = $att->find();
            if(!$sub) {
                $attachments = $query->select()->where(['document_id' => $did])->all();
            }else{
                $attachments = $query->select()->where(['order_id'=>$did,'sub_id'=>$sub])->all();
                //debug($attachments);
            }
            $this->set('attachments',$attachments);
        }
        $this->layout ='blank';
    }


    public function getColor($id = 0){
        $query = TableRegistry::get('color_class');
        $q = $query->find()->where(['id'=>$id])->first();
        $this->response->body($q);
        return $this->response;
    }

    public function getColorId($id = 0){
        $query = TableRegistry::get('subdocuments');
        $q = $query->find('all')->where(['id'=>$id])->first();
        if($q) {
            $query_col = TableRegistry::get('color_class');
            $q_col = $query_col->find('all')->where(['id'=>$q->color_id])->first();
            $this->response->body($q_col->color);
            return $this->response;
        }

    }

    public function appendattachments($query, $Language = "English"){
        $fieldname = "title";
        if($Language != "English" && $Language != "Debug"){$fieldname.=$Language;}
        $docs = TableRegistry::get('subdocuments')->find();
        $docnames = array();
        foreach($docs as $doc){
            $docnames[$doc->id] = $doc->$fieldname;
            if($Language=="Debug"){$docnames[$doc->id].=" [Trans]";}
        }
        foreach($query as $client){
            if (isset($docnames[$client->sub_doc_id])) {$client->document_type = $docnames[$client->sub_doc_id];}
            $client->hasattachments = $this->hasattachments($client->order_id, $client->id);
        }
        return $query;
    }
    public function hasattachments($orderid, $documentid){
        $docs = TableRegistry::get('doc_attachments');
        $query = $docs->find();
        $client_docs = $query->select()->where(['order_id' => $orderid,'document_id'=>$documentid, 'attachment LIKE' => "%.%"])->first();
        if($client_docs) {return true;}
    }
    public function mee_attach($order_id,$cid){
        $this->Document->mee_attach($cid,$order_id);
        die();
    }
    public function getSubDetails($id){
        //die('here');
        $products =  TableRegistry::get('subdocuments');
        $pro = $products->find()->where(['id'=>$id])->first();
        $this->response->body($pro);
        return $this->response;
        die;
    }

    public function aggregate($id){
        $client =  TableRegistry::get('clients')->find()->where(['id'=>$id])->first();
        $audits = TableRegistry::get('audits')->find()->where(['document_id IN (SELECT id FROM documents WHERE draft = 0 AND sub_doc_id = 8 AND client_id = '.$id.')'])->all();
        $this->set('client',$client);
        $this->set('audits',$audits);
        $att =  TableRegistry::get('doc_attachments')->find()->where(['document_id IN (SELECT id FROM documents WHERE draft = 0 AND client_id = '.$id.' AND sub_doc_id = 8)'])->all();
        $this->set('client_docs',$att);
        
    }

    public function getProfileByDocument($id=0){
        if($id) {
            $products = TableRegistry::get('profiles')->find()->where(['id IN (SELECT user_id FROM documents WHERE id = ' . $id . ')'])->first();
        } else {
            $products = false;
        }
        $this->response->body($products);
        return $this->response;
        
    }

}
