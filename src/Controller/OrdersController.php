<?php
    namespace App\Controller;

    use App\Controller\AppController;
    use Cake\Event\Event;
    use Cake\Controller\Controller;
    use Cake\ORM\TableRegistry;

    include_once(APP . '../webroot/subpages/soap/nusoap.php');

    class OrdersController extends AppController {

        public $paginate = [
            'limit' => 10,
            'order' => ['id' => 'DESC'],
        ];

        public function intact(){

        }

        public function productSelection() {
            $this->set('doc_comp', $this->Document);
            $this->loadModel('OrderProducts');
            $settings = $this->Settings->get_settings();
            $this->set('products', $this->OrderProducts->find()->where(['enable'=>'1'])->all());
            $this->set('settings', $settings);

            $ordertype = strtoupper(substr($_GET["ordertype"], 0, 3));
            $table = TableRegistry::get('product_types')->find()->where(['Acronym' => $ordertype])->first();;
            $this->set('product', $table);
        }

        public function initialize() {
            parent::initialize();
            $this->loadComponent('Settings');
            $this->loadComponent('Document');
            $this->loadComponent('Mailer');
            $this->loadComponent('Trans');
            //$this->Settings->verifylogin($this, "orders");
        }

        public function vieworder($cid = null, $did = null, $table = null) {
            if($cid)
            {
                $this->set('client',TableRegistry::get('clients')->find()->where(['id'=>$cid])->first());
            }
            $this->LoadSubDocs($_GET["forms"]);
            $meedocs = TableRegistry::get('mee_attachments_more');
            $this->set('meedocs',$meedocs);
            $this->set('doc_comp', $this->Document);
            $this->set('table', $table);
            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));
            $doc = $this->Document->getDocumentcount();
            $cn = $this->Document->getUserDocumentcount();
            if ($setting->orders_list == 0 || count($doc) == 0 || $cn == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (011)');
                return $this->redirect("/");

            }
            $orders = TableRegistry::get('orders');
            if ($did) {
                $order_id = $orders->find()->where(['id' => $did])->first();
                $this->set('ooo',$order_id);
                $this->loadModel('Profiles');
                $profiles = $this->Profiles->find()->where(['id' => $order_id->uploaded_for])->first();
                $this->set('p', $profiles);
            }
            if (isset($order_id)) {
                $this->set('modal', $order_id);
            }
            $this->set('cid', $cid);
            $this->set('did', $did);
            $this->set('disabled', 1);
            if ($did) {
                $feeds = TableRegistry::get('feedbacks');
                //$pre_at = TableRegistry::get('driver_application_accident');

                $feed = $feeds->find()->where(['order_id' => $did])->first();
                $this->set('feeds', $feed);

                $pre_employment_road_test = TableRegistry::get('pre_employment_road_test');
                $pre_employment_road_test = $pre_employment_road_test->find()->where(['order_id' => $did])->first();
                $this->set('pre_employment_road_test', $pre_employment_road_test);

                $past_employment_survey = TableRegistry::get('past_employment_survey');
                $past_employment_survey = $past_employment_survey->find()->where(['order_id' => $did])->first();
                $this->set('past_employment_survey', $past_employment_survey);

                $application_for_employment_gfs = TableRegistry::get('application_for_employment_gfs');
                $application_for_employment_gfs = $application_for_employment_gfs->find()->where(['order_id' => $did])->first();
                $this->set('application_for_employment_gfs', $application_for_employment_gfs);

                $basic_mee_platform = TableRegistry::get('basic_mee_platform');
                $basic_mee_platform = $basic_mee_platform->find()->where(['order_id' => $did])->first();
                $this->set('basic_mee_platform', $basic_mee_platform);

                $survey = TableRegistry::get('Survey');
                //$pre_at = TableRegistry::get('driver_application_accident');
                $sur = $survey->find()->where(['order_id' => $did])->first();
                $this->set('survey', $sur);

                $attachments = TableRegistry::get('attachments');
                //$pre_at = TableRegistry::get('driver_application_accident');
                $attachment = $attachments->find()->where(['order_id' => $did])->all();
                $this->set('attachments', $attachment);

                $attachments = TableRegistry::get('audits');
                //$pre_at = TableRegistry::get('driver_application_accident');
                $audits = $attachments->find()->where(['order_id' => $did])->first();
                $this->set('audits', $audits);

                $pre = TableRegistry::get('doc_attachments');
                //$pre_at = TableRegistry::get('driver_application_accident');
                $pre_at['attach_doc'] = $pre->find()->where(['order_id' => $did, 'sub_id' => 1])->all();
                $this->set('pre_at', $pre_at);

                $mee_att = TableRegistry::get('mee_attachments');
                //$pre_at = TableRegistry::get('driver_application_accident');
                $mee_a['attach_doc'] = $mee_att->find()->where(['order_id' => $did])->first();
                $this->set('mee_att', $mee_a);

                $ps_detail = TableRegistry::get('pre_screening')->find()->where(['order_id' => $did])->first();
                $this->set('ps_detail',$ps_detail);

                $deval_detail = TableRegistry::get('road_test')->find()->where(['order_id' => $did])->first();
                $this->set('deval_detail',$deval_detail);

                $da = TableRegistry::get('driver_application');
                $da_detail = $da->find()->where(['order_id' => $did])->first();
                $this->set('da_detail',$da_detail);
                if ($da_detail) {
                    $da_ac = TableRegistry::get('driver_application_accident');
                    $sub['da_ac_detail'] = $da_ac->find()->where(['driver_application_id' => $da_detail->id])->all();

                    $da_li = TableRegistry::get('driver_application_licenses');
                    $sub['da_li_detail'] = $da_li->find()->where(['driver_application_id' => $da_detail->id])->all();

                    $da_at = TableRegistry::get('doc_attachments');
                    $sub['da_at'] = $da_at->find()->where(['order_id' => $did, 'sub_id' => 2])->all();

                    $de_at = TableRegistry::get('doc_attachments');
                    $sub['de_at'] = $de_at->find()->where(['order_id' => $did, 'sub_id' => 3])->all();

                    $this->set('sub', $sub);
                }
                $con = TableRegistry::get('consent_form');
                $con_detail = $con->find()->where(['order_id' => $did])->first();
                if ($con_detail) {
                    //echo $con_detail->id;die();
                    $con_cri = TableRegistry::get('consent_form_criminal');
                    $sub2['con_cri'] = $con_cri->find()->where(['consent_form_id' => $con_detail->id])->all();

                    $con_at = TableRegistry::get('doc_attachments');
                    $sub2['con_at'] = $con_at->find()->where(['order_id' => $did, 'sub_id' => 4])->all();
                    $this->set('sub2', $sub2);
                    $this->set('consent_detail', $con_detail);
                }

                $emp = TableRegistry::get('employment_verification');
                $sub3['emp'] = $emp->find()->where(['order_id' => $did])->all();

                //echo $con_detail->id;die();
                $emp_att = TableRegistry::get('doc_attachments');
                $sub3['att'] = $emp_att->find()->where(['order_id' => $did, 'sub_id' => 41])->all();

                $this->set('sub3', $sub3);

                $edu = TableRegistry::get('education_verification');
                $sub4['edu'] = $edu->find()->where(['order_id' => $did])->all();
                //echo $con_detail->id;die();
                $edu_att = TableRegistry::get('doc_attachments');
                $sub4['att'] = $edu_att->find()->where(['order_id' => $did, 'sub_id' => 42])->all();
                $this->set('sub4', $sub4);

                $sur_att = TableRegistry::get('doc_attachments');
                $sub6['att'] = $sur_att->find()->where(['order_id' => $did, 'sub_id' => 6])->all();
                $this->set('sub6', $sub6);
                $docuu = TableRegistry::get('Documents');

                $queryy = $docuu->find()->where(['order_id' => $did])->first();
                $sub_doc = TableRegistry::get('Subdocuments');
                $sd = $sub_doc->find()->all();

                foreach($sd as $s) {
                    if($s->id >20 && is_object($queryy)) {
                        if ($queryy->sub_doc_id == $s->id) {
                            $mods = TableRegistry::get($s->table_name);
                            $mod = $mods->find()->where(['order_id' => $did])->first();
                            $this->set($s->table_name, $mod);
                        }
                    }
                }
            }
            $this->render('addorder');
        }


        public function addorder($cid = 0, $did = 0, $table = null) {
            if($cid)
            {
                $this->set('client',TableRegistry::get('clients')->find()->where(['id'=>$cid])->first());
            }
            $this->set('doc_comp', $this->Document);
            $meedocs = TableRegistry::get('mee_attachments_more');
            $this->set('meedocs',$meedocs);
            $this->set('uid', '');
            $this->set('table', $table);
            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));
            $doc = $this->Document->getDocumentcount();
            $cn = $this->Document->getUserDocumentcount();

            //die(count($doc));
            if ($setting->orders_create == 0 || count($doc) == 0 || $cn == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (010)');
                return $this->redirect("/");
            }

            $orders = TableRegistry::get('orders');
            if ($did) {
                $order_id = $orders->find()->where(['id' => $did])->first();
                $this->set('ooo',$order_id);
                $this->loadModel('Profiles');
                $profiles = $this->Profiles->find()->where(['id' => $order_id->uploaded_for])->first();
                $this->set('p', $profiles);
            } else {
                if (isset($_GET['driver']) && is_numeric($_GET['driver']) && $_GET['driver']) {
                    $this->loadModel('Profiles');
                    $profiles = $this->Profiles->find()->where(['id' => $_GET['driver']])->first();
                    $this->set('p', $profiles);
                }
            }

            $MissingFields = $this->Manager->requiredfields($profiles, "profile2order");
            if($MissingFields || !$profiles->iscomplete){
                if(isset($_GET["debug"])) {
                    debug($profiles);
                    debug($MissingFields);
                    die();
                }
                $this->Flash->error($this->Trans->getString("flash_cantorder"));
            }

            if ($did) {
                $o_model = TableRegistry::get('Orders');
                $orde = $o_model->find()->where(['id' => $did])->first();
                if ($orde) {
                    $dr = $orde->draft;
                    if ($dr == '0' || !$dr) {
                        $dr = 0;
                        $this->Flash->success($this->Trans->getString("flash_ordersaved"));
                        //die();
                    } else {
                        $dr = 1;
                    }
                } else {
                    $dr = 1;
                }
            } else {
                $dr = 1;
            }
            $this->set('dr', $dr);
            //$did= $document_id->id;
            if (isset($order_id)) {
                $this->set('modal', $order_id);
            }
            $this->set('cid', $cid);
            $this->set('did', $did);
            if ($did) {

                $feeds = TableRegistry::get('feedbacks');
                $feed = $feeds->find()->where(['order_id' => $did])->first();
                $this->set('feeds', $feed);

                $pre_employment_road_test = TableRegistry::get('pre_employment_road_test');
                $pre_employment_road_test = $pre_employment_road_test->find()->where(['order_id' => $did])->first();
                $this->set('pre_employment_road_test', $pre_employment_road_test);

                $past_employment_survey = TableRegistry::get('past_employment_survey');
                $past_employment_survey = $past_employment_survey->find()->where(['order_id' => $did])->first();
                $this->set('past_employment_survey', $past_employment_survey);

                $application_for_employment_gfs = TableRegistry::get('application_for_employment_gfs');
                $application_for_employment_gfs = $application_for_employment_gfs->find()->where(['order_id' => $did])->first();
                $this->set('application_for_employment_gfs', $application_for_employment_gfs);

                $basic_mee_platform = TableRegistry::get('basic_mee_platform');
                $basic_mee_platform = $basic_mee_platform->find()->where(['order_id' => $did])->first();
                $this->set('basic_mee_platform', $basic_mee_platform);

                $survey = TableRegistry::get('Survey');
                //$pre_at = TableRegistry::get('driver_application_accident');
                $sur = $survey->find()->where(['order_id' => $did])->first();
                $this->set('survey', $sur);

                $attachments = TableRegistry::get('attachments');
                //$pre_at = TableRegistry::get('driver_application_accident');
                $attachment = $attachments->find()->where(['order_id' => $did])->all();
                $this->set('attachments', $attachment);

                $attachments = TableRegistry::get('audits');
                //$pre_at = TableRegistry::get('driver_application_accident');
                $audits = $attachments->find()->where(['order_id' => $did])->first();
                $this->set('audits', $audits);

                $pre = TableRegistry::get('doc_attachments');
                //$pre_at = TableRegistry::get('driver_application_accident');
                $pre_at['attach_doc'] = $pre->find()->where(['order_id' => $did, 'sub_id' => 1])->all();
                $this->set('pre_at', $pre_at);

                $mee_att = TableRegistry::get('mee_attachments');
                //$pre_at = TableRegistry::get('driver_application_accident');
                $mee_a['attach_doc'] = $mee_att->find()->where(['order_id' => $did])->first();
                $this->set('mee_att', $mee_a);

                $ps_detail = TableRegistry::get('pre_screening')->find()->where(['order_id' => $did])->first();
                $this->set('ps_detail',$ps_detail);

                $deval_detail = TableRegistry::get('road_test')->find()->where(['order_id' => $did])->first();
                $this->set('deval_detail',$deval_detail);

                $da = TableRegistry::get('driver_application');
                $da_detail = $da->find()->where(['order_id' => $did])->first();
                $this->set('da_detail',$da_detail);
                if ($da_detail) {
                    $da_ac = TableRegistry::get('driver_application_accident');
                    $sub['da_ac_detail'] = $da_ac->find()->where(['driver_application_id' => $da_detail->id])->all();

                    $da_li = TableRegistry::get('driver_application_licenses');
                    $sub['da_li_detail'] = $da_li->find()->where(['driver_application_id' => $da_detail->id])->all();

                    $da_at = TableRegistry::get('doc_attachments');
                    $sub['da_at'] = $da_at->find()->where(['order_id' => $did, 'sub_id' => 2])->all();

                    $de_at = TableRegistry::get('doc_attachments');
                    $sub['de_at'] = $de_at->find()->where(['order_id' => $did, 'sub_id' => 3])->all();

                    $this->set('sub', $sub);

                }



                $con = TableRegistry::get('consent_form');
                $con_detail = $con->find()->where(['order_id' => $did])->first();
                if ($con_detail) {
                    $con_cri = TableRegistry::get('consent_form_criminal');
                    $criminal = $con_cri->find()->where(['consent_form_id' => $con_detail->id])->all();
                    $sub2['con_cri'] = $criminal;
                } else {
                    $con_detail = $this->getlastdocument($order_id->uploaded_for, 4, "consent_form");
                }

                $con_at = TableRegistry::get('doc_attachments');
                $sub2['con_at'] = $con_at->find()->where(['order_id' => $did, 'sub_id' => 4])->all();
                $this->set('sub2', $sub2);
                $this->set('consent_detail', $con_detail);

                $emp = TableRegistry::get('employment_verification');//
                $sub3['emp'] = $emp->find()->where(['order_id' => $did])->all();
                
                

                //echo $con_detail->id;die();
                $emp_att = TableRegistry::get('doc_attachments');
                $sub3['att'] = $emp_att->find()->where(['order_id' => $did, 'sub_id' => 41])->all();

                $this->set('sub3', $sub3);

                $edu = TableRegistry::get('education_verification');
                $sub4['edu'] = $edu->find()->where(['order_id' => $did])->all();
                //echo $con_detail->id;die();
                $edu_att = TableRegistry::get('doc_attachments');
                $sub4['att'] = $edu_att->find()->where(['order_id' => $did, 'sub_id' => 42])->all();
                $this->set('sub4', $sub4);

                $docuu = TableRegistry::get('Documents');

                $queryy = $docuu->find()->where(['order_id' => $did])->first();
                $sub_doc = TableRegistry::get('Subdocuments');
                $sd = $sub_doc->find()->all();

                foreach($sd as $s) {
                    if($s->id >20) {
                        if ($queryy->sub_doc_id == $s->id) {
                            $mods = TableRegistry::get($s->table_name);

                            $mod = $mods->find()->where(['order_id' => $did])->first();
                            $this->set($s->table_name, $mod);
                        }
                    }
                }

            } else {
                $this->loadlastforms($_GET["driver"]);
            }

            $this->LoadSubDocs($_GET["forms"]);
        }

        function getlastdocument($Profile_ID, $DocSubType, $Table){//this is roy's sub for loading old data aka getolddocument loadolddocument phogey
            $consentform = TableRegistry::get('documents')->find()->order("id desc")->where(['uploaded_for' => $Profile_ID, "sub_doc_id" => $DocSubType])->first();
            //$consentform = TableRegistry::get("documents")->find('all', array('order' => "id DESC" ,'conditions' => array('uploaded_for' => $Profile_ID, "sub_doc_id" => $DocSubType)))->first();
            if ($consentform) {
                if ($consentform->order_id) {
                    $con_detail = TableRegistry::get($Table)->find()->where(['order_id' => $consentform->order_id])->first();//last
                } else {
                    $con_detail = TableRegistry::get($Table)->find()->where(['document_id' => $consentform->id])->first();//last
                }
                return $con_detail;
            }
        }

        public function getconsentform($con_detail = "", $Profile_ID = 0){
            if (!$con_detail && $Profile_ID){
                $con_detail = $this->getlastdocument($Profile_ID, 4, "consent_form");
            }
            if($con_detail) {
                $con_detail = TableRegistry::get('consent_form_criminal')->find()->where(['consent_form_id' => $con_detail->id])->all();
                return $con_detail;
            }
        }

        public function loadlastforms($Profile_ID = ""){
            $con_detail = $this->getlastdocument($Profile_ID, 4, "consent_form");
            if($con_detail){
                $criminal = $this->getconsentform($con_detail);
                //$con_at = TableRegistry::get('doc_attachments');

                $sub2['con_cri'] = $criminal;
                $sub2['con_at'] = "";//$con_at->find()->where(['order_id' => $did, 'sub_id' => 4])->all();

                $this->set('sub2', $sub2);
                $this->set('consent_detail', $con_detail);
            }

            //LETTER OF EXPERIENCE
            $con_detail = $this->getlastdocument($Profile_ID, 9, "employment_verification");
            if($con_detail) {
                if ($con_detail->document_id) {
                    $emp = TableRegistry::get('employment_verification')->find()->where(['document_id' => $con_detail->document_id])->all();
                } elseif ($con_detail->order_id) {
                    $emp = TableRegistry::get('employment_verification')->find()->where(['order_id' => $con_detail->order_id])->all();
                }

                if ($emp) {
                    //$emp = TableRegistry::get('employment_verification')->find()->order(['id' => 'DESC'])->where(['user_id' => $Profile_ID])->all();
                    $listofdocs = array();
                    foreach ($emp as $document) {
                        if (count($listofdocs) == 0) {
                            $listofdocs[] = $document;
                        } elseif ($listofdocs[0]->document_id && $listofdocs[0]->document_id == $document->document_id) {
                            $listofdocs[] = $document;
                        } elseif ($listofdocs[0]->order_id && $listofdocs[0]->order_id == $document->order_id) {
                            $listofdocs[] = $document;
                        } else {
                            break;
                        }
                    }

                    $sub3['emp'] = $listofdocs;
                    if ($sub3['emp']) {
                        $did = "";
                        foreach ($sub3['emp'] as $document) {
                            if (!$did) {
                                $did = $document->document_id;
                            }
                        }
                        if ($did) {
                            $emp_att = TableRegistry::get('doc_attachments');
                            $sub3['att'] = $emp_att->find()->where(['document_id' => $did])->all();
                        }
                        $this->set('sub3', $sub3);
                    }
                }
            }
        }

        public function savedoc($cid = 0, $did = 0){
            $this->loadComponent('Mailer');
            $ret = $this->Document->savedoc($this->Mailer, $cid, $did, false);
            die();
        }

        public function savePrescreening(){
            $this->Document->savePrescreening();
            die;
        }

        public function savedDriverApp($document_id = 0, $cid = 0){
            $this->Document->savedDriverApp($document_id, $cid);
            die;
        }

        public function savedDriverEvaluation($document_id = 0, $cid = 0){
            $this->Document->savedDriverEvaluation($document_id, $cid);
            die();
        }

        public function savedMeeOrder($document_id = 0, $cid = 0){
            $this->Document->savedMeeOrder($document_id, $cid);
            die();
        }

        function saveEmployment($document_id = 0, $cid = 0){
            $this->Document->saveEmployment($document_id, $cid);
            die();
        }

        function saveEducation($document_id = 0, $cid = 0){
            $this->Document->saveEducation($document_id, $cid);
            die();
        }

        public function deleteOrder($id, $draft = ''){
            if (isset($_GET['draft'])) {
                $draft = 1;
            }
            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));
            if ($setting->orders_delete == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (009)');
                return $this->redirect("/");
            }

            $this->loadModel('Orders');
            $this->Orders->deleteAll(array('id' => $id));

            $this->loadModel('Documents');
            $this->Documents->deleteAll(array('order_id' => $id));
            $this->Flash->success($this->Trans->getString("flash_orderdeleted"));
            if ($draft) {
                $this->redirect('/orders/orderslist?draft');
            }else {
                $this->redirect('/orders/orderslist');
            }
        }

        public function subpages($filename) {
            $this->set('doc_comp', $this->Document);
            $this->layout = "blank";
            $this->set("filename", $filename);
        }

        public function index(){
            $this->redirect(array('controller'=>'orders','action'=>'orderslist'));

            $this->set('doc_comp', $this->Document);
            if (isset($_GET['draft']) && isset($_GET['flash'])) {
                $this->Flash->success($this->Trans->getString("flash_orderdraft"));
            } else {
                if (isset($_GET['flash'])) {
                    $this->Flash->success($this->Trans->getString("flash_ordersaved"));
                }
            }
            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));
            $doc = $this->Document->getDocumentcount();
            $cn = $this->Document->getUserDocumentcount();

            if ($setting->orders_list == 0 || count($doc) == 0 || $cn == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (008)');
                return $this->redirect("/");
            }

            $orders = TableRegistry::get('orders');
            $order = $orders->find();
            //$order = $order->order(['orders.id' => 'DESC']);
            $order = $order->select();
            $cond = '';
            if (!$this->request->session()->read('Profile.super')) {
                $u = $this->request->session()->read('Profile.id');

                $setting = $this->Settings->get_permission($u);
                if ($setting->document_others == 0) {
                    if ($cond == '') {
                        $cond = $cond . ' user_id = ' . $u;
                    }else {
                        $cond = $cond . ' AND user_id = ' . $u;
                    }
                }

            }
            if (isset($_GET['searchdoc']) && $_GET['searchdoc']) {
                $cond = $cond . ' (orders.title LIKE "%' . $_GET['searchdoc'] . '%" OR orders.description LIKE "%' . $_GET['searchdoc'] . '%")';
            }

            if (isset($_GET['table']) && $_GET['table']) {
                if ($cond == '') {
                    $cond = $cond . ' orders.id IN (SELECT order_id FROM ' . $_GET['table'] . ')';
                }else {
                    $cond = $cond . ' AND orders.id IN (SELECT order_id FROM ' . $_GET['table'] . ')';
                }
            }
            if (!$this->request->session()->read('Profile.admin') && $setting->orders_others == 0) {
                if ($cond == '') {
                    $cond = $cond . ' orders.user_id = ' . $this->request->session()->read('Profile.id');
                }else {
                    $cond = $cond . ' AND orders.user_id = ' . $this->request->session()->read('Profile.id');
                }
            }
            if (isset($_GET['submitted_by_id']) && $_GET['submitted_by_id']) {
                if ($cond == '') {
                    $cond = $cond . ' orders.user_id = ' . $_GET['submitted_by_id'];
                }else {
                    $cond = $cond . ' AND orders.user_id = ' . $_GET['submitted_by_id'];
                }
            }
            if (isset($_GET['client_id']) && $_GET['client_id']) {
                if ($cond == '') {
                    $cond = $cond . ' orders.client_id = ' . $_GET['client_id'];
                }else {
                    $cond = $cond . ' AND orders.client_id = ' . $_GET['client_id'];
                }
            }
            if (isset($_GET['division']) && $_GET['division']) {
                if ($cond == '') {
                    $cond = $cond . ' division = "' . $_GET['division'] . '"';
                }else {
                    $cond = $cond . ' AND division = "' . $_GET['division'] . '"';
                }
            }
            if ($cond) {
                $order = $order->where([$cond])->contain(['Profiles']);
            } else {
                $order = $order->contain(['Profiles']);
            }
            if (isset($_GET['searchdoc'])) {
                $this->set('search_text', $_GET['searchdoc']);
            }
            if (isset($_GET['submitted_by_id'])) {
                $this->set('return_user_id', $_GET['submitted_by_id']);
            }
            if (isset($_GET['client_id'])) {
                $this->set('return_client_id', $_GET['client_id']);
            }
            if (isset($_GET['type'])) {
                $this->set('return_type', $_GET['type']);
            }
            $this->set('orders', $this->paginate($order));

        }

        function get_orderscount($type, $c_id = ""){

            $u = $this->request->session()->read('Profile.id');

            if (!$this->request->session()->read('Profile.super')) {
                $setting = $this->Settings->get_permission($u);
                //var_dump($setting);
                if ($setting->document_others == 0) {
                    $u_cond = "Orders.user_id=$u";
                } else {
                    $u_cond = "";
                }
            } else {
                $u_cond = "";
            }
            $model = TableRegistry::get($type);

            if ($c_id != "") {

                $cnt = $model->find()->where(['((document_id IN (SELECT id FROM documents WHERE draft = 0)) OR (order_id IN (SELECT id FROM orders WHERE draft = 0)))', $u_cond, $type . '.client_id' => $c_id])->contain(['Orders'])->count();
            } else {

                $cond = $this->Settings->getclientids($u, $this->request->session()->read('Profile.super'), $type);
                $cnt = $model->find()->where(['((document_id IN (SELECT id FROM documents WHERE draft = 0)) OR (order_id IN (SELECT id FROM orders WHERE draft = 0)))', $u_cond, 'OR' => $cond])->contain(['Orders'])->count();
            }
            //debug($cnt); die();
            $this->response->body(($cnt));
            return $this->response;
            die();
        }

        public function StartOrderSave($orderid = null, $response = null){
            $this->set('doc_comp', $this->Document);
            echo '!!!!!!';
            echo $response;
            echo $arr['response'] = $_GET['response'];

            echo "AAAAAAAAAAAH I'M ON FIRE! SOMEONE HELP ME!";

            die();
            $querys = TableRegistry::get('orders');
            $query2 = $querys->query();
            $query2->update()
                ->set($arr)
                ->where(['id' => $orderid])
                ->execute();
            die();
        }

        public function save_ebs_pdi($orderid, $pdi) {
            $this->set('doc_comp', $this->Document);
            $query2 = TableRegistry::get('orders');
            $arr['ebs_pdi'] = $pdi;
            $query2 = $query2->query();
            $query2->update()
                ->set($arr)
                ->where(['id' => $orderid])
                ->execute();
            $this->response->body($query2);
            return $this->response;
        }



        public function writing_complete($orderid = false) {
            if(!$orderid){
                $orderid = $this->Manager->enum_table("orders", "id", "DESC")->first()->id;//just get the latest order
            }
            $query2 = TableRegistry::get('orders');
            $arr['complete_writing'] = 1;
            $query2 = $query2->query();
            $query2->update()
                ->set($arr)
                ->where(['id' => $orderid])
                ->execute();
            $this->response->body($query2);
            return $this->response;
        }



        public function save_webservice_ids($orderid, $ins_id, $ebs_id) {
            $this->set('doc_comp', $this->Document);
            $query2 = TableRegistry::get('orders');
            $arr['ins_id'] = $ins_id;
            $arr['ebs_id'] = $ebs_id;
            $query2 = $query2->query();
            $query2->update()
                ->set($arr)
                ->where(['id' => $orderid])
                ->execute();
            $this->response->body($query2);
            return $this->response;
        }

        public function save_pdi($orderid, $id, $pdi) {
      //   echo '555555555'. $orderid . ' ' .  $id . ' ' . $pdi;
            $this->set('doc_comp', $this->Document);
            $query2 = TableRegistry::get('orders');
            $arr="";
            if (in_array($pdi, array("ins_79", "ins_1", "ins_14", "ins_77", "ins_78", "ebs_1603", "ebs_1627", "ebs_1650", "ins_72", "ins_31", "ins_32"))) {
                $arr = array($pdi => $id);

                $query2 = $query2->query();
                $query2->update()
                    ->set($arr)
                    ->where(['id' => $orderid])
                    ->execute();

                $this->response->body($query2);
            }

            return $this->response;
        }

        public function getprofile($UserID){
            $table = TableRegistry::get("profiles");
            $results = $table->find('all', array('conditions' => array('id'=>$UserID)))->first();
            return $results;
        }

        public function filternonnumeric($Text){
            if(!is_numeric($Text)){
                $tempstr="";
                for($temp =0; $temp< strlen($Text); $temp++){
                    $tempstr2 = substr($Text, $temp, 1);
                    if(is_numeric($tempstr2)){
                        $tempstr.=$tempstr2;
                    } else {
                        return $tempstr;
                    }
                }
                return $tempstr;
            }
            return $Text;
        }

        function test1() {
            return '12';
        }

        public function webservice($order_type = null, $forms = null, $driverid = null, $orderid = null) {
            $all_attachments = TableRegistry::get('mee_attachments');
            $mee_query = $all_attachments->find()->where(['order_id'=>$orderid]);
            $orderid=$this->filternonnumeric($orderid);//there is an error message being passed in $orderid!!!
            $uploadedfor = $this->getprofile($driverid);

            if($mee_query) {
                foreach($mee_query as $mq) {
                    /* UNCOMMENT BELOW TO VIEW THE ATTACHMENTS OF MEE*/
                    echo "forms: " . $forms . "<BR>";
                    echo 'id_piece1: '.$mq->id_piece1.'<br/>';
                    echo 'id_piece2: '.$mq->id_piece2.'<br/>';
                    echo 'driver_record_abstract: '.$mq->driver_record_abstract.'<br/>';
                    echo 'cvor: '.$mq->cvor.'<br/>';
                    echo 'resume: '.$mq->resume.'<br/>';
                    echo 'certification: '.$mq->certification.'<br/>';
                    $more_mee = TableRegistry::get('mee_attachments_more');
                    $more = $more_mee->find()->where(['mee_id'=>$mq->id]);
                    if($more) {//WEBSERVICE DEBUGGER
                        $First=true;
                        foreach($more as $file) {
                            $realpath = getcwd() . "/attachments/" . $file->attachments;
                            if (file_exists($realpath)) {
                                $label = "ADDITIONAL ATTACHMENT: ";
                                if($First){//} && !empty($driverid)){
                                    $DriverProvince = $uploadedfor->driver_province;
                                    echo "Driver's license Province: " . $DriverProvince . "<BR>";
                                    $forms = explode(",", $forms);
                                    $First = (in_array("1", $forms) && $DriverProvince == "QC") || (in_array("14", $forms) && ($DriverProvince == "SK" || $DriverProvince == "BC"));
                                    if ($First) {$label = "Abstract consent form: ";}
                                }
                                echo $label . $file->attachments . '<br/>';

                                //debug($file);
                                $First = False;
                            }
                        }

                        $this->set('attachments_more', $more);

                    }

                    $this->set('attachments1', $mq);
                }
            }

            $all_attachments = TableRegistry::get('doc_attachments');
            $this->layout = "blank";

            $model = TableRegistry::get('profiles');
            $driverinfo = $model->find()->where(['id' => $driverid])->first(); //$conditions[] = 'find_in_set(id, ' . $conditions2 . ')'

            $this->set('orderid', $orderid);
            $this->set('driverinfo', $driverinfo);

            if ($order_type == "Requalification") {
                $ordertype1 = "MEE-REQ";
            } else if ($order_type == "Order Products") {
                $ordertype1 = "MEE-IND";
            } else {
                $ordertype1 = "MEE";
            }
            $this->set('ordertype', $ordertype1);

            $orders = TableRegistry::get('orders');
            $order_info = $orders->find()->where(['id' => $orderid])->first();
            $this->set('order_info', $order_info);

            $order_attach = $all_attachments->find()->where(['order_id'=>$orderid]);

            $this->set('order_attach', $order_attach);
            $this->set('subdocument', TableRegistry::get('subdocuments'));

            if ($order_info->user_id == 0){ $order_info->user_id = $this->request->session()->read('Profile.id'); }
            $profile = $this->getcol("profiles", "id", $order_info->user_id);
            $client =  $this->getcol("clients", "id", $order_info->client_id);

            $setting = TableRegistry::get('settings')->find()->first();

            $this->set('servicearr',array("email" => "super", "username" => $profile->username, "profile_type" => $this->profiletype($profile->profile_type), "company_name" => $client->company_name, "site" => $setting->mee, "for" => $uploadedfor->username, 'path' => LOGIN . 'profiles/view/' . $order_info->uploaded_for));
            $this->set('mailer',$this->Mailer);
            $this->set('order_model',$orders);
            $this->set('orderid',$orderid);
            //$this->Mailer->handleevent("ordercompleted", );//$order_info
        }



        function profiletype($type){
            return TableRegistry::get('profile_types')->find()->where(['id'=>$type])->first()->title;
        }
        
        function orderinfo($id)
        {
            return TableRegistry::get('orders')->find()->where(['id' => $id])->first();
        }

        function getcol($table, $primarykey, $value){
            if(!is_object($table)) {$table = TableRegistry::get($table);}
            return $table->find()->where([$primarykey => $value])->first();
        }

        public function createPdfBg() {
            //die();
        }

        public function createPdf($oid) {
            $this->set('doc_comp', $this->Document);
            $this->set('oid', $oid);
            $this->layout = 'blank';

            $this->layout = 'blank';

            $consent = TableRegistry::get('consent_form');
            $arr['consent'] = $consent
                ->find()
                ->where(['order_id' => $oid])->first();
            $this->set('detail', $arr);
            $criminal = TableRegistry::get('consent_form_criminal');
            $cri = $criminal
                ->find()
                ->where(['consent_form_id' => $arr['consent']->id]);
            $this->set('detail', $arr);
            $this->set(compact('cri'));
            $attach = TableRegistry::get('doc_attachments');
            $att = $attach
                ->find()
                ->where(['order_id' => $oid, 'sub_id' => 4, 'attachment <> ""']);
            $this->set('detail', $arr);
            $this->set(compact('att'));

        }

        public function createPdfEmployment($id) {
            $this->set('doc_comp', $this->Document);
            $this->layout = 'blank';
            $consent = TableRegistry::get('employment_verification');
            $arr['consent'] = $consent
                ->find()
                ->where(['order_id' => $id])->all();


            $this->set('detail', $arr);
            $attach = TableRegistry::get('doc_attachments');
            $att = $attach
                ->find()
                ->where(['order_id' => $id, 'sub_id' => 41, 'attachment <> ""'])->all();

            $this->set('order_id', $id);
            $this->set(compact('att'));
        }

        public function createPdfEducation($oid) {
            $this->set('doc_comp', $this->Document);
            $this->set('oid', $oid);
            $this->layout = 'blank';
            $consent = TableRegistry::get('education_verification');
            $education = $consent
                ->find()
                ->where(['order_id' => $oid]);

            $attach = TableRegistry::get('doc_attachments');
            $att = $attach
                ->find()
                ->where(['order_id' => $oid, 'sub_id' => 42, 'attachment <> ""']);

            $this->set(compact('education'));

            $this->set(compact('att'));
        }

        public function viewReport($client_id, $order_id) {
            $this->set('doc_comp', $this->Document);
            $orders = TableRegistry::get('orders');
            $order = $orders
                ->find()
                ->where(['orders.id' => $order_id])->contain(['Profiles', 'Clients', 'RoadTest'])->first();

            $this->set('order', $order);
            //  debug($order);
        }

        function savedriver($oid) {
            $this->set('doc_comp', $this->Document);
            $arr['is_hired'] = $_POST['is_hired'];
            $arr['hired_date'] = $_POST['hired_date'];
            $orders = TableRegistry::get('profiles');
            $order = $orders
                ->query()->update()
                ->set($arr)
                ->where(['profiles.id' => $oid])->execute();

            die();
        }
        function requalify($uid) {
            $this->set('doc_comp', $this->Document);
            $arr['requalify'] = $_POST['requalify'];
            $orders = TableRegistry::get('profiles');
            $order = $orders
                ->query()->update()
                ->set($arr)
                ->where(['profiles.id' => $uid])->execute();

            die();
        }



        public function saveAttachmentsPrescreen($data = NULL, $count = 0)
        {//count is to delete all while first insertion and no delete for following insertion

            $this->Document->saveAttachmentsPrescreen($data, $count);
            die();
        }

        public function saveAttachmentsDriverApp($data = NULL, $count = 0) {
            $this->Document->saveAttachmentsDriverApp($data, $count);
            die();
        }

        public function saveAttachmentsRoadTest($data = NULL, $count = 0) {
            $this->Document->saveAttachmentsRoadTest($data, $count);
            die();
        }

        public function saveAttachmentsConsentForm($data = NULL, $count = 0) {
            $this->Document->saveAttachmentsConsentForm($data, $count);
            die();
        }

        public function saveAttachmentsEmployment($data = NULL, $count = 0) {
            $this->Document->saveAttachmentsEmployment($data, $count);
            die();
        }

        public function saveAttachmentsEducation($data = NULL, $count = 0) {
            $this->Document->saveAttachmentsEducation($data, $count);
            die();
        }

        function getprocessed($table, $oid) {
            $model = TableRegistry::get($table);
            $q = $model->find()->where(['order_id' => $oid])->count();
            $this->response->body($q);
            return $this->response;
        }

        public function drafts() {

        }

        function getDriverByClient($client) {
            //$logged_id = $this->request->session()->read('Profile.id');
            $cmodel = TableRegistry::get('Clients');
            if (!is_numeric($client)) {
                $logged_id = $this->request->session()->read('Profile.id');
                //echo "<br/>";
                if (!$this->request->session()->read('Profile.admin') && !$this->request->session()->read('Profile.super')) {
                    $clients = $cmodel->find()->where(['(profile_id LIKE "' . $logged_id . ',%" OR profile_id LIKE "%,' . $logged_id . ',%" OR profile_id LIKE "%,' . $logged_id . '")']);
                }else {
                    $clients = $cmodel->find();
                }

                $profile_ids = '';
                foreach ($clients as $c) {
                    if ($profile_ids) {
                        $profile_ids = $profile_ids . ',' . $c->profile_id;
                    } else {
                        $profile_ids = $c->profile_id;
                    }
                }
                if (!$profile_ids) {
                    $profile_ids = '9999999';
                }
            } else {
                $clients = $cmodel->find()->where(['id' => $client])->first();
                $profile_ids = $clients->profile_id;
            }

            $profile_ids = str_replace(',', ' ', $profile_ids);
            $profile_ids = trim($profile_ids);
            $profile_ids = str_replace(' ', ',', $profile_ids);
            while(strpos($profile_ids, ",,")) {
                $profile_ids = str_replace(',,', ',', $profile_ids);
            }

            $model = TableRegistry::get('Profiles');
            //$profile = $model->find()->where(['id IN (' . $profile_ids . ')', '(profile_type = 5 OR profile_type = 7 OR profile_type = 8 OR profile_type = 11)']);

            $profile = $model->find()->where(['id IN (' . $profile_ids . ')', $this->makeprofiletypequery()]);

            //echo "<OPTION>" . $this->makeprofiletypequery() . "</OPTION>";

            echo "<option value='' title='Orderscontroller.getDriverByClient'>" . $this->Trans->getString("forms_selectdriver") . "</option>";
            if ($profile) {
                foreach ($profile as $p) {
                    echo "<option value='" . $p->id . "'";
                    if($this->Manager->requiredfields($p, "profile2order") || !$p->iscomplete){
                        echo ' DISABLED';
                    }
                    $username="";
                    if($p->username){
                        $username = " (" . $p->username . ")";
                    }
                    $username = trim($p->fname . ' ' . $p->mname . ' ' . $p->lname . $username);
                    echo ">" . $username . "</option>";
                }
            }

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


        function testing() {
            $this->set('doc_comp', $this->Document);
        }

        public function orderslist(){
            $userid = $this->request->session()->read('Profile.id');

            $this->set('doc_comp', $this->Document);
            if (isset($_GET['draft']) && isset($_GET['flash'])) {
                $this->Flash->success($this->Trans->getString("flash_orderdraft"));
            } elseif (isset($_GET['flash'])) {
                $this->Flash->success($this->Trans->getString("flash_ordersaved"));
            }
            $setting = $this->Settings->get_permission($userid);
            $doc = $this->Document->getDocumentcount();
            $cn = $this->Document->getUserDocumentcount();
            $this->set('products', TableRegistry::get('product_types')->find('all'));

            if ($setting->orders_list == 0 || count($doc) == 0 || $cn == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (007)');
                return $this->redirect("/");
            }

            $orders = TableRegistry::get('orders');
            $order = $orders->find();
            //$order = $order->order(['orders.id' => 'DESC']);
            $order = $order->select();
            $cond = '';
            $sess = $userid;
            $cls = TableRegistry::get('Clients');
            $cl = $cls->find()->where(['(profile_id LIKE "' . $sess . ',%" OR profile_id LIKE "%,' . $sess . ',%" OR profile_id LIKE "%,' . $sess . '%")'])->all();
            $cli_id = '999999999';
            foreach ($cl as $cc) {
                $cli_id = $cli_id . ',' . $cc->id;
            }

            if (!$this->request->session()->read('Profile.super')) {
                $u =$userid;
                $setting = $this->Settings->get_permission($u);
            }

            if (isset($_GET['searchdoc']) && $_GET['searchdoc']) {
                //$cond = $this->AppendSQL($cond, '(orders.title LIKE "%' . $_GET['searchdoc'] . '%" OR orders.description LIKE "%' . $_GET['searchdoc'] . '%")');
                $cond = $this->AppendSQL($cond, '(orders.user_id IN (SELECT id FROM profiles WHERE username LIKE "%'.$_GET['searchdoc'].'%" OR fname LIKE "%'.$_GET['searchdoc'].'%" OR lname LIKE "%'.$_GET['searchdoc'].'%") OR orders.id IN (SELECT order_id FROM footprint WHERE fullname LIKE "%'.$_GET['searchdoc'].'%") OR orders.client_id IN (SELECT id FROM clients WHERE company_name LIKE "%'.$_GET['searchdoc'].'%"))');

            }

            if (isset($_GET['table']) && $_GET['table']) {
                $cond = $this->AppendSQL($cond, 'orders.id IN (SELECT order_id FROM ' . $_GET['table'] . ')');
            }

            if (!$this->request->session()->read('Profile.admin')){
                if ($setting->orders_others == 0) {
                    $cond = $this->AppendSQL($cond, 'orders.user_id = ' . $userid);
                } else {
                    $cond = $this->AppendSQL($cond, 'orders.client_id IN (' . $cli_id . ')');
                }
            }

            if (!$this->request->session()->read('Profile.super')) {
                $clients_id = $this->Settings->getAllClientsId($userid);
                if($clients_id && !strpos($clients_id, ",")){
                    $cond = $this->AppendSQL($cond, 'orders.client_id = ' . $clients_id);
                }
            }

            if (isset($_GET['submitted_by_id']) && $_GET['submitted_by_id']) {
                $cond = $this->AppendSQL($cond, 'orders.user_id = ' . $_GET['submitted_by_id']);
            }

            if (isset($_GET['uploaded_for']) && $_GET['uploaded_for']) {
                $cond = $this->AppendSQL($cond, 'orders.uploaded_for = ' . $_GET['uploaded_for']);
            }

            if (isset($_GET['client_id']) && $_GET['client_id']) {
                $cond = $this->AppendSQL($cond, 'orders.client_id = ' . $_GET['client_id']);
            }

            if (isset($_GET['division']) && $_GET['division']) {
                $cond = $this->AppendSQL($cond, 'division = "' . $_GET['division'] . '"');
            }

            if (isset($_GET['draft'])) {
                $cond = $this->AppendSQL($cond, 'orders.draft = 1');
            }

            if ($cond) {
                $order = $order->where([$cond])->contain(['Profiles']);
            } else {
                $order = $order->contain(['Profiles']);
            }

            if (isset($_GET['searchdoc'])) {
                $this->set('search_text', $_GET['searchdoc']);
            }

            if (isset($_GET['submitted_by_id'])) {
                $this->set('return_user_id', $_GET['submitted_by_id']);
            }

            if (isset($_GET['client_id'])) {
                $this->set('return_client_id', $_GET['client_id']);
            }

            if (isset($_GET['type'])) {
                $this->set('return_type', $_GET['type']);
            }

            //debug($order);
            $this->set('orders', $this->appendattachments($this->paginate($order)));

            $usertype = TableRegistry::get('profiles')->find()->where(['id'=>$sess])->first()->profile_type;
            $profiletype = TableRegistry::get('profile_types')->find()->where(['id'=>$usertype])->first();
            $this->set('profiletype', $profiletype);
        }

        function AppendSQL($SQL, $Query){
            if($SQL){ return $SQL . " AND " . $Query; }
            return $Query;
        }

        function getClientByDriver($driver)
        {
            //$controller = $this->_registry->getController();
            $settings = $this->Settings->get_settings();
            $logged_id = $this->request->session()->read('Profile.id');
            $cmodel = TableRegistry::get('Clients');
            if (!$this->request->session()->read('Profile.admin') && !$this->request->session()->read('Profile.super')) {
                $clients = $cmodel->find()->where(['(profile_id LIKE "' . $logged_id . ',%" OR profile_id LIKE "%,' . $logged_id . ',%" OR profile_id LIKE "%,' . $logged_id . '") AND (profile_id LIKE "' . $driver . ',%" OR profile_id LIKE "%,' . $driver . ',%" OR profile_id LIKE "%,' . $driver . '")']);//Selecting client with respect to both loggedin user and driver
            }else {
                $clients = $cmodel->find()->where(['(profile_id LIKE "' . $driver . ',%" OR profile_id LIKE "%,' . $driver . ',%" OR profile_id LIKE "%,' . $driver . '")']);
            }
            if (!is_numeric($driver)) {
                if (!$this->request->session()->read('Profile.admin') && !$this->request->session()->read('Profile.super')) {
                    $clients = $cmodel->find()->where(['(profile_id LIKE "' . $logged_id . ',%" OR profile_id LIKE "%,' . $logged_id . ',%" OR profile_id LIKE "%,' . $logged_id . '")']);
                }else {
                    $clients = $cmodel->find();
                }
            }
            if ($clients->count() > 0) {
                echo "<option value=''>Select " . ucfirst($settings->client) . "s</option>";
                foreach ($clients as $c) {
                    echo "<option value='" . $c->id . "'>" . $c->company_name . "</option>";
                }
            } else {
                echo "Error";
            }

            die();
        }




        public function getOrderData($cid = 0, $order_id = 0, $profile_id = 0) {
            $this->Document->getOrderData($cid, $order_id, $profile_id);
            die;
        }

        public function getSubDocs() {
            $docs = TableRegistry::get('subdocuments');
            $doc = $docs->find()->all();
            //$do = $doc->select('all');
            $this->response->body($doc);
            return $this->response;
            die;
        }

        public function getdocid($sub_doc_id, $order_id) {
            $doc = TableRegistry::get('documents');
            $doc = $doc->find()->where(['sub_doc_id' => $sub_doc_id, 'order_id' => $order_id])->first();
            $this->response->body($doc);
            return $this->response;
            die;
        }

        public function getProductTitle($id='') {
            $doc = TableRegistry::get('order_products');
            $doc = $doc->find()->where(['number' => $id])->first();
            $this->response->body($doc);
            return $this->response;
            die;
        }

        function check_driver_abstract2($id) {
            $doc = TableRegistry::get('profiles');
            $doc = $doc->find()->where(['id' => $id])->first();
            $this->response->body($doc);
            return $this->response;
            die;
        }

        function check_cvor2($id) {
            $doc = TableRegistry::get('profiles');
            $doc = $doc->find()->where(['id' => $id])->first();
            $this->response->body($doc);
            return $this->response;
            die;
        }

        function check_driver_abstract($id) {
            $doc = TableRegistry::get('profiles');
            $doc = $doc->find()->where(['id' => $id])->first();
            $province = $doc->driver_province;
            $arr = array('BC','MB','NU','NT','QC','SK','YT');
            //$arr = array('BC','SK','MB');
            echo '0';
            if(in_array($province,$arr)) {echo '1';}
            die();
        }

        function check_cvor($id) {
            $doc = TableRegistry::get('profiles');
            $doc = $doc->find()->where(['id' => $id])->first();
            $province = $doc->driver_province;
            //$arr = array('BC','MB','NU','NT','QC','SK','YT');
            $arr = array('BC','SK','MB');
            echo '0';
            if(in_array($province,$arr)) {echo '1';}
            die();
        }


        public function appendattachments($query){
            foreach($query as $client){
                $client->hasattachments = $this->hasattachments($client->id);
            }
            return $query;
        }
        public function hasattachments($orderid){
            $docs = TableRegistry::get('doc_attachments');
            $query = $docs->find();
            $client_docs = $query->select()->where(['order_id' => $orderid, 'attachment LIKE' => "%.%"])->first();
            if($client_docs) {return true;}
            return false;
        }


        function isproductprovinceenabled($Table, $ProductID, $DocumentID, $Province){//old slow method
            //if($Province != "ALL"){  if ($this->isproductprovinceenabled($ProductID, $DocumentID, "ALL")) { return true;}} //doubles the load time, so it was removed
            $item = $Table->find()->where(['ProductID' => $ProductID, 'FormID' => $DocumentID, "Province" => $Province])->first();
            if($item) {return true;} else {return false;}
        }

        function isproductprovinceenabled2($Items, $ProductID, $DocumentID, $Province){//new fast method
            foreach($Items as $Item){
                if ($Item->ProductID == $ProductID){
                    if ($Item->FormID == $DocumentID || $Item->FormID == 0){
                        if ($Item->Province == $Province || $Item->Province == "ALL"){
                            return true;
                        }
                    }
                }
            }
            return false;
        }

        public function LoadSubDocs($Forms){
            $Table = TableRegistry::get('order_provinces');
            $subdocuments = TableRegistry::get('subdocuments')->find('all');//id title
            $provinces = array("AB", "BC", "MB", "NB", "NL","NT","NS","NU","ON","PE","QC","SK","YT");//"ALL",
            $forms = explode(",", $Forms);

            $return = array();
            foreach($subdocuments as $document){
                $query = $Table->find('all', array('conditions' => array("OR" => array( array('FormID' => $document->id),array('FormID' => 0)))));//cache values
                $insert = array();
                $insert["ID"] = $document->id;
                foreach ($provinces as $province) {
                    foreach ($forms as $form) {
                        if ($this->isproductprovinceenabled2($query, $form, $document->id, $province)) {
                            $insert[$province] = true;
                            break;
                        }
                    }
                }
                $return[strtolower(trim($document->title))] = $insert;
            }

            if (isset($_GET["order_type"])){
                $Table = TableRegistry::get('product_types');//->find()->where(['LOWER(Name)'=> strtolower($_GET["order_type"])])->first();
                $ordertype=strtolower(urldecode($_GET["order_type"]));
                $prod = $Table->find('all', array('conditions' => array("OR" => array( array('LOWER(Acronym)' => $ordertype),array('LOWER(Name)' => $ordertype)))));
                $this->set('theproduct', $prod->first());
            }

            $this->set('thedocuments',  $return);
            //var_dump($return);
            return $return;
        }

        function getProNum() {
            $products =  TableRegistry::get('order_products');
            $pro = $products->find()->where(['enable'=>1,'id <>'=>8]);
            $prod = '';
            foreach($pro as $p) {
                if($prod == '') {
                    $prod = $p->number;
                } else {
                    $prod = $prod . ',' . $p->number;
                }
            }
            $this->response->body($prod);
            return $this->response;
            die;
        }

        function getSubDetail($id) {
            //die('here');
            $products =  TableRegistry::get('subdocuments');
            $pro = $products->find()->where(['id'=>$id])->first();
            $this->response->body($pro);
            return $this->response;
            die;
        }

        public function invoice() {
            $query = TableRegistry::get('Clients');
            $q = $query->find();
            $u = $this->request->session()->read('Profile.id');
            if ($this->request->session()->read('Profile.super')) {
                $q = $q->select();
            }else {
                $q = $q->select()->where(['profile_id LIKE "' . $u . ',%" OR profile_id LIKE "%,' . $u . ',%" OR profile_id LIKE "%,' . $u . '" OR profile_id LIKE "' . $u . '" ']);
            }
            $this->set('clients', $q);

            if(isset($_GET)) {
                $cond =[];
                if(isset($_GET['from'])) {
                    array_push($cond, ["created >=" => $_GET['from']]);
                }
                if(isset($_GET['to'])) {
                    array_push($cond, ["created <=" => $_GET['to']]);
                }
                if(isset($_GET['client_id'])) {
                    array_push($cond, ["client_id" => $_GET['client_id']]);
                }

                $orders = TableRegistry::get('orders');
                $order = $orders->find()->order(['orders.id' => 'DESC'])->where(['draft' => 0, $cond])->all();
                $this->set('orders', $order);

                $this->set('products', TableRegistry::get('product_types')->find('all'));
                $this->set('profiles',  TableRegistry::get('profiles')->find('all'));
                $this->set('taxes', 0.13);
            }
        }

        public function bulksubmit() {
            $dri = $_POST['drivers'];
            $drivers = explode(',',$dri);
            //$forms = $_POST['forms'];
            $arr['forms'] = $_POST['forms'];
            $arr['order_type'] = 'BUL';
            $arr['draft'] = 0;
            $arr['title'] = 'order_'.date('Y-m-d H:i:s');
            $arr['client_id'] = $_POST['client'];
            $arr['created'] = date('Y-m-d H:i:s');
            $arr['division'] = $_POST['division'];
            $arr['user_id'] = $this->request->session()->read('Profile.id');
            $arr['driver'] = '';
            $arr['order_id'] = '';
            foreach($drivers as $driver) {
                $arr['uploaded_for'] = $driver;
                $ord = TableRegistry::get('orders');

                $doc = $ord->newEntity($arr);
                $ord->save($doc);
                //$this->webservice('BUL', $arr['forms'], $arr['user_id'], $doc->id);
                if($arr['driver']) {
                    $arr['driver'] = $arr['driver'] . ',' . $driver;
                }else {
                    $arr['driver'] = $driver;
                }
                if($arr['order_id']) {
                    $arr['order_id'] = $arr['order_id'] . ',' . $doc->id;
                }else {
                    $arr['order_id'] = $doc->id;
                }
                
                if (!is_dir(APP.'../webroot/orders/order_'.$doc->id)) {
                               mkdir(APP . '../webroot/orders/order_' . $doc->id, 0777);
                           }
                unset($doc);
            }

            echo json_encode($arr);
            $this->Flash->success($this->Trans->getString("flash_bulkorder"));
            die();
        }
        public function checkPermisssionOrder($did,$driver) {
            $recruiter = $this->request->session()->read('Profile.id');
            $ord = TableRegistry::get('profilessubdocument');
            $check = $ord->find()->where(['profile_id'=>$recruiter,'subdoc_id'=>$did])->first();
            $this->response->body($check);
            return $this->response;
            die;

        }

        public function checkSignature($did) {
            $ord = TableRegistry::get('consent_form')->find()->where(['order_id'=>$did])->first();
            $check = '0';
            if($ord->criminal_signature_applicant && $ord->criminal_signature_applicant2 && $ord->signature_company_witness2 && $ord->signature_company_witness) {
                $check = '1';
            }
            echo $check;
            die;
        }



        public function makeneworder($values){
            $table = TableRegistry::get('orders');
            $date = date('Y-m-d H:i:s');
            //$values = array();
            $table->query()->insert(array_keys($values))->values($values)->execute();
        }
        public function groupdocument($docid, $orderid){
            $table = TableRegistry::get('profilessubdocument');
            $table->query()->update()->set(['order_id' => $orderid])
                ->where(['id' => $docid])
                ->execute();
        }
        
        function test_order($id='12')
        {
             $this->response->file(WWW_ROOT.'orders/order_'. $id .'/test.html', array('download' => true, 'name' => 'Test'));
            die();
        }
        
        function saveRecruiterInfo($oid)
        {
            $table = TableRegistry::get('orders');
            $table->query()->update()->set($_POST)
                ->where(['id' => $oid])
                ->execute();
                die();
        }
    }

