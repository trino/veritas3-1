<?php
    namespace App\Controller;

    use App\Controller\AppController;
    use Cake\Event\Event;
    use Cake\Controller\Controller;
    use Cake\ORM\TableRegistry;
    use Cake\Network\Email\Email;
    use App\Controller\OrdersController;
    
    class ClientsController extends AppController {

        public $paginate = [
            'limit' => 10,
            'order' => ['id' => 'desc']
        ];

        public function initialize() {
            parent::initialize();
            $this->loadComponent('Settings');
            $this->loadComponent('Document');
            $this->loadComponent('Mailer');
            $this->loadComponent('Trans');

            //$this->Settings->verifylogin($this, "clients");
        }

        function getclient_id($id) {
            $client = TableRegistry::get('clients')->find()->where(['id' => '17'])->first();
            $q = 0;
            if($client) {
                $pid = $client->profile_id;
                $pids = explode(",", $pid);
                if (in_array($id, $pids)) {
                    $q = '1';
                }
            }
            $this->response->body($q);
            return $this->response;
            die();
        }

        function upload_img($id = "") {
            if (isset($_FILES['myfile']['name']) && $_FILES['myfile']['name']) {
                $arr = explode('.', $_FILES['myfile']['name']);
                $ext = end($arr);
                $rand = rand(100000, 999999) . '_' . rand(100000, 999999) . '.' . $ext;
                $allowed = array('jpg', 'jpeg', 'png', 'bmp', 'gif');
                $check = strtolower($ext);
                if (in_array($check, $allowed)) {
                    move_uploaded_file($_FILES['myfile']['tmp_name'], APP . '../webroot/img/jobs/' . $rand);
                    unset($_POST);
                    if (isset($id)) {
                        $_POST['image'] = $rand;
                        $img = TableRegistry::get('clients');

                        //echo $s;die();
                        $query = $img->query();
                        $query->update()
                            ->set($_POST)
                            ->where(['id' => $id])
                            ->execute();
                    }
                    echo $rand;

                } else {
                    echo "../error.png";
                }
            }
            die();
        }

        function upload_all($id = "") {
            if (isset($_FILES['myfile']['name']) && $_FILES['myfile']['name']) {
                $arr = explode('.', $_FILES['myfile']['name']);
                $ext = end($arr);
                $rand = rand(100000, 999999) . '_' . rand(100000, 999999) . '.' . $ext;
                $allowed = array('jpg', 'jpeg', 'png', 'bmp', 'gif', 'pdf', 'doc', 'docx', 'csv', 'xlsx', 'xls');
                $check = strtolower($ext);
                if (in_array($check, $allowed)) {
                    move_uploaded_file($_FILES['myfile']['tmp_name'], APP . '../webroot/img/jobs/' . $rand);
                    unset($_POST);
                    echo $rand;
                } else {
                    echo "error";
                }
            }
            die();
        }

        function removefiles($file) {
            if (isset($_POST['id']) && $_POST['id'] != 0) {
                $this->loadModel("ClientDocs");
                $this->ClientDocs->deleteAll(['id' => $_POST['id']]);
            }
            @unlink(WWW_ROOT . "img/jobs/" . $file);
            die();
        }

        public function index() {
            if (isset($_GET['flash'])) {
                $this->Flash->success($this->Trans->getString("flash_selectclient"));
            }
            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));
            if ($setting->client_list == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (020)');
                return $this->redirect("/");
            }
            if (isset($_GET['draft'])) {$draft = 1;} else {$draft = 0;}
            $querys = TableRegistry::get('Clients');
            $conditions['drafts'] = $draft;
            if(!$this->request->session()->read('Profile.super')){
                $conditions['id'] = $this->Manager->find_client();
            }
            $query = $querys->find()->where($conditions);

            $this->set('client', $this->appendattachments($this->paginate($query)));
        }

        function search() {
            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));
            if ($setting->client_list == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (019)');
                return $this->redirect("/");
            }
            if (isset($_GET['draft'])) {
                $draft = 1;
            } else {
                $draft = 0;
            }
            if (isset($_GET['search'])) {
                $search = $_GET['search'];
            } else {
                $search = "";
            }
            $searchs = strtolower($search);
            $querys = TableRegistry::get('Clients');
            $query = $querys->find()
                ->where(['drafts' => $draft, 'OR' => [['LOWER(company_name) LIKE' => '%' . $searchs . '%']]
                ]);
            $this->set('client', $this->paginate($query));
            $this->set('search_text', $search);
            $this->render('index');
        }

        public function view($id = null) {
            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));

            if ($setting->client_list == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (018)');
                return $this->redirect("/");
            }
            $this->loadModel("ClientTypes");
            $this->set('client_types', $this->ClientTypes->find()->where(['enable' => '1'])->all());
            $querys = TableRegistry::get('Clients');
            $query = $querys->find()->where(['id' => $id]);
            $this->set('client', $query->first());
            $this->set('id', $id);
        }

        public function assignContact($contact, $id, $status) {
            $querys = TableRegistry::get('Clients');
            $query = $querys->find()->where(['id' => $id])->first();
            if ($status == 'yes') {
                if ($query->contact_id == '') {
                    $arr['contact_id'] = $contact;
                } else
                    $arr['contact_id'] = $query->contact_id . ',' . $contact;
            } else {
                $arr['contact_id'] = '';
                if ($query->contact_id == '')
                    die();
                else {
                    $array = explode(',', $query->contact_id);
                    if ($array) {
                        foreach ($array as $a) {
                            if ($a == $contact) {
                                continue;
                            } else {
                                if ($arr['contact_id'] == '') {
                                    $arr['contact_id'] = $a;
                                } else {
                                    $arr['contact_id'] = $arr['contact_id'] . ',' . $a;
                                }
                            }
                        }
                    }

                }
            }
            $arr['contact_id'] = str_replace(',', ' ', $arr['contact_id']);
            $arr['contact_id'] = trim($arr['contact_id']);
            $arr['contact_id'] = str_replace(' ', ',', $arr['contact_id']);
            $query2 = $querys->query();
            $query2->update()
                ->set($arr)
                ->where(['id' => $id])
                ->execute();
            die();
        }

        public function assignProfile($profile, $id, $status) {
            $querys = TableRegistry::get('Clients');
            $query = $querys->find()->where(['id' => $id])->first();

            if ($status == 'yes') {
                if ($query->profile_id == '') {
                    $arr['profile_id'] = $profile;
                } else {
                    $arr['profile_id'] = $query->profile_id . ',' . $profile;
                }
            } else {
                $arr['profile_id'] = '';
                if ($query->profile_id == '') {
                    die();
                } else {
                    $array = explode(',', $query->profile_id);
                    if ($array) {
                        foreach ($array as $a) {
                            if ($a == $profile) {
                                continue;
                            } else {
                                if ($arr['profile_id'] == '') {
                                    $arr['profile_id'] = $a;
                                } else {
                                    $arr['profile_id'] = $arr['profile_id'] . ',' . $a;
                                }
                            }
                        }
                    }

                }
            }
            $arr['profile_id'] = str_replace(',', ' ', $arr['profile_id']);
            $arr['profile_id'] = trim($arr['profile_id']);
            $arr['profile_id'] = str_replace(' ', ',', $arr['profile_id']);
            $arr['profile_id'] = str_replace(',,', ',', $arr['profile_id']);
            $arr['profile_id'] = str_replace(',,', ',', $arr['profile_id']);
            $arr['profile_id'] = str_replace(',,', ',', $arr['profile_id']);
            $query2 = $querys->query();
            $query2->update()
                ->set($arr)
                ->where(['id' => $id])
                ->execute();
            die();
        }

        public function add() {
            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));
            $settings = $this->Settings->get_settings();
            $this->set('settings', $settings);

            $this->loadModel("ClientTypes");
            $this->set('client_types', $this->ClientTypes->find()->where(['enable' => '1'])->all());
            if ($setting->client_create == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (017)');
                return $this->redirect("/");

            }
            $rec = '';
            $con = '';
            $count = 1;
            if (isset($_POST['recruiter_id'])) {
                foreach ($_POST['recruiter_id'] as $ri) {
                    if ($count == 1) {
                        $rec = $ri;
                    } else {
                        $rec = $rec . ',' . $ri;
                    }
                    $count++;

                }
            }
            unset($_POST['recruiter_id']);
            $_POST['recruiter_id'] = $rec;

            if (isset($_POST['contact_id'])) {
                foreach ($_POST['contact_id'] as $ri) {
                    if ($count == 1) {
                        $rec = $ri;
                    } else {
                        $rec = $rec . ',' . $ri;
                    }
                    $count++;

                }
            }
            unset($_POST['contact_id']);
            $_POST['contact_id'] = $rec;
            $clients = TableRegistry::get('Clients');
            if(isset($_POST['company_name'])){
            $company_name = $_POST['company_name'];
           $slug = strtolower($company_name);
           $_POST['slug'] = str_replace(' ','_',$slug);
           }
            $client = $clients->newEntity($_POST);
            if ($this->request->is('post')) {

                if ($clients->save($client)) {
                    
                    //if (isset($_POST['division'])) {
                    //}
                    $this->Flash->success($this->Trans->getString("flash_usersaved"));
                    return $this->redirect(['action' => 'edit', $client->id]);
                } else {
                    $this->Flash->error($this->Trans->getString("flash_usernotsaved"));
                }
            }
            $this->set(compact('client'));
            $this->set('profile', array());
            $this->set('contacts', array());
            $this->set('id', '');
            $this->render('add');
        }

        public function saveClients($id = 0) {
            if (isset($_POST["image"]) && !$_POST["image"]) {
                unset($_POST["image"]);
            }

            $settings = TableRegistry::get('settings');
            $setting = $settings->find()->first();
            $sub_sup = TableRegistry::get('subdocuments');
            $sub_sup_count = $sub_sup->find()->all();
            $settings = $this->Settings->get_settings();

            $rec = '';
            $con = '';
            $count = 1;
            $rec = "";
            $count = 1;
            if (isset($_POST['contact_id'])) {
                foreach ($_POST['contact_id'] as $ri) {
                    if ($count == 1) {
                        $rec = $ri;
                    } else {
                        $rec = $rec . ',' . $ri;
                    }
                    $count++;

                }
            }

            unset($_POST['contact_id']);
            $_POST['contact_id'] = $rec;
            $_POST['created'] = date('Y-m-d');
            $clients = TableRegistry::get('Clients');
            if (!$id) {

                $cnt = 0;
                if (isset($_POST['sig_email']) && $_POST['sig_email'] != "") {
                    $cnt = $clients->find()->where(['sig_email' => $_POST['sig_email']])->count();
                }
                if ($cnt > 0) {
                    echo $this->Trans->getString("flash_invalidemail");
                    die();
                }

                if (isset($_POST['sig_email']) && ((str_replace(array('@', '.'), array('', ''), $_POST['sig_email']) == $_POST['sig_email'] || strlen($_POST['sig_email']) < 5) && $_POST['sig_email'] != '')) {
                    echo $this->Trans->getString("flash_invalidemail");
                    die();
                } else {
                    $_POST['profile_id'] = $this->request->session()->read('Profile.id');
                    $client = $clients->newEntity($_POST);


                    if ($this->request->is('post')) {
                        if ($clients->save($client)) {
                            $arr_s['client_id'] = $client->id;

                            foreach ($sub_sup_count as $ssc) {
                                $arr_s['sub_id'] = $ssc->id;
                                $sub_c = TableRegistry::get('client_sub_order');
                                $sc = $sub_c->newEntity($arr_s);
                                $sub_c->save($sc);
                            }
                            if ($_POST['division'] != "") {//create new division list THIS SHOULD USE OVERWRITE DIVISIONS!!!
                                $division = nl2br(str_replace(",", "<br />", $_POST['division']));
                                $division = str_replace(',', '<br />', $division);
                                $dd = explode("<br />", $division);
                                $divisions['client_id'] = $client->id;
                                foreach ($dd as $d) {
                                    $divisions['title'] = trim($d);
                                    $divs = TableRegistry::get('client_divison');
                                    $div = $divs->newEntity($divisions);
                                    $divs->save($div);
                                    unset($div);
                                }

                                //die();

                            }
                            $this->loadModel('ClientDocs');
                            $this->ClientDocs->deleteAll(['client_id' => $client->id]);
                            $client_docs = array_unique($_POST['client_doc']);
                            foreach ($client_docs as $d) {
                                if ($d != "") {
                                    $docs = TableRegistry::get('client_docs');
                                    $ds['client_id'] = $client->id;
                                    $ds['file'] = $d;
                                    $doc = $docs->newEntity($ds);
                                    $docs->save($doc);
                                    unset($doc);
                                }
                            }
                            if (isset($_POST['drafts']) && $_POST['drafts'] == '1') {
                                $this->Flash->success($this->Trans->getString("flash_clientsaveddraft"));
                            } else {
                                $this->Flash->success($this->Trans->getString("flash_clientsaved"));
                            }
                            echo $client->id;
                            $path = $this->Document->getUrl();
                            $pro_query = TableRegistry::get('Profiles');
                            $email_query = $pro_query->find()->where(['super' => 1])->first();
                            $email_id = $email_query->id;
                            $em = $email_query->email;
                            $user_id = $this->request->session()->read('Profile.id');
                            $uq = $pro_query->find('all')->where(['id' => $user_id])->first();
                            if ($uq->profile_type) {
                                $u = $uq->profile_type;
                                $type_query = TableRegistry::get('profile_types');
                                $type_q = $type_query->find()->where(['id' => $u])->first();
                                $ut = $type_q->title;
                                $username = $uq->username;
                            } else {
                                $username = '';
                                $ut = '';
                            }

                            $this->Mailer->handleevent("clientcreated", array("email" => $em, "company_name" => $_POST['company_name'], "profile_type" => $ut, "username" => $username, "path" => $path, "site" => $setting->mee));

                        } else {
                            $this->Flash->error($this->Trans->getString("flash_clientnotsaved"));
                            echo "e";
                        }
                    }
                }
            } else {
                $cnt = 0;
                if ($_POST['sig_email'] != "") {
                    $cnt = $clients->find()->where(['sig_email' => $_POST['sig_email'], 'id<>' . $id])->count();
                }
                if ($cnt > 0) {
                    echo "email";
                }
                if ((str_replace(array('@', '.'), array('', ''), $_POST['sig_email']) == $_POST['sig_email'] || strlen($_POST['sig_email']) < 5) && $_POST['sig_email'] != '') {
                    echo $this->Trans->getString("flash_invalidemail");
                    die();
                } else {
                    foreach ($_POST as $k => $v) {
                        if ($k != "client_doc")
                            $edit[$k] = $v;

                    }
                    //var_dump($edit);
                    $query2 = $clients->query();
                    $query2->update()
                        ->set($edit)
                        ->where(['id' => $id])
                        ->execute();
                    $this->Flash->success($this->Trans->getString("flash_clientsaved"));
                    $this->overwritedivisions($id, $_POST['division']);

                    $this->loadModel('ClientDocs');
                    $this->ClientDocs->deleteAll(['client_id' => $id]);
                    $client_docs = array_unique($_POST['client_doc']);
                    foreach ($client_docs as $d) {
                        if ($d != "") {
                            $docs = TableRegistry::get('client_docs');
                            $ds['client_id'] = $id;
                            $ds['file'] = $d;
                            $doc = $docs->newEntity($ds);
                            $docs->save($doc);
                            unset($doc);
                        }
                    }
                    echo $id;
                }
            }
            die();
        }

        function overwritedivisions($id, $Divisions) {
            $Table = TableRegistry::get('client_divison');
            $division = trim(nl2br(str_replace(",", "<br />", $Divisions)));
            if (!$division) {//is empty, delete them all
                $Table->deleteAll(array('client_id' => $id));
            } else {//isn't empty
                $dd = explode("<br />", $division);
                $ddcount = count($dd);
                $currentdivision = 0;

                //overwrite existing divisions
                $divisionlist = $Table->find()->where(['client_id' => $id]);
                foreach ($divisionlist as $div) {
                    if ($currentdivision < $ddcount) {//has a division of this index, use it
                        $dd[$currentdivision] = trim($dd[$currentdivision]);
                        $Table->query()->update()->set(['title' => $dd[$currentdivision]])->where(['id' => $div->id])->execute();
                    } else {//doesn't have a new division of this index, delete it
                        $Table->deleteAll(array("id" => $div->id));
                    }
                    $currentdivision++;
                }
                //there are more new divisions than existing divisions, save the new ones
                for ($temp = $currentdivision; $temp < $ddcount; $temp++) {
                    $dd[$currentdivision] = trim($dd[$currentdivision]);
                    $Table->query()->insert(['client_id', 'title'])->values(['client_id' => $id, 'title' => $dd[$temp]])->execute();
                }
            }
        }

        function profiletype($type) {
            return TableRegistry::get('profile_types')->find()->where(['id' => $type])->first()->title;
        }

        function copyGETtoPOST(){
            $_POST = array_merge($_POST, $_GET);
        }

        function HandleAJAX(){//url = $this->request->webroot. 'clients/quickcontact',
            $Value = false;
            $this->copyGETtoPOST();
            if (isset($_POST['Value'])) {$Value = strtolower($_POST['Value']) == "true"; }
            $setting = TableRegistry::get('settings')->find()->first();
            switch ($_POST["Type"]) {
                case "enabledocument":
                    $this->setproductstatus($_POST["ClientID"], $_POST["ProductID"], $Value);
                    //return $_POST["ClientID"] . " " . $_POST["ProductID"] . " " . $Value;
                    break;
                case "generateHTML":
                    $this->generateproductHTML($_POST["ClientID"], $_POST["Ordertype"], $_POST["Language"]);
                    break;
                case "email"://user_id doc_id form client_id to profile->created_by
                    $profile = $this->loadprofile($_POST["user_id"]);
                    $creator = $this->loadprofile( $profile->send_to);
                    $client = $this->loadprofile($_POST["client_id"], "id", "clients");
                    $document = $this->loadprofile($_POST["doc_id"], "id", "documents");
                    $URL = LOGIN . "documents/view/" . $_POST["client_id"] . "/" . $_POST["doc_id"] . "?type=" . $_POST["form"];

                    //echo print_r($profile, true) . "<p>" .  print_r($creator, true)  . "<p>" .  print_r($_POST, true)  . "<P>" . $URL;

                    $ut = '';
                    if ($profile->profile_type) {
                        $u = $profile->profile_type;
                        $type_q = TableRegistry::get('profile_types')->find()->where(['id'=>$u])->first();
                        $ut = $type_q->title;
                    }

                    $this->Mailer->handleevent("documentcreated", array("email" => $creator->email, "username" => $profile->username, "path" => $URL, "site" => $setting->mee, "place" => 5, "profile_type" => $ut, "company_name" => $client->company_name, "document_type" => $document->document_type));

                    echo $this->Trans->getString("flash_emailsent", array("user" =>  $creator->username), $_POST["user_id"]);
                    break;
                case "emailout"://user_id
                    $profile = $this->loadprofile($_POST["user_id"]);
                    $URL = LOGIN . "application/index.php?user_id=" . $profile->id . "&form=";
                    $this->Mailer->handleevent("gfs", array("email" => $profile->email, "path1" => $URL . 4, "path2" => $URL . 9, "site" => $setting->mee, "username" => $this->request->session()->read('Profile.username')));
                    $this->updatetable("profiles", "id", $profile->id, "send_to", $this->request->session()->read('Profile.id'));
                    echo $this->Trans->getString("flash_emailwassent", array("email" => $profile->email) );
                    break;
                case "visibleprofiles":
                    $Status=0;
                    if(strtolower($_POST["status"]) == "true"){$Status=1;}
                    $this->Manager->update_database("clients", "id", $_POST["clientid"], array("visibleprofiles" => $Status));
                    break;
                default:
                    echo $_POST["Type"] . " is not a handled AJAX type (ClientsController - HandleAJAX)";
            }
            $this->layout = 'ajax';
            $this->render(false);
            return true;
        }

        public function updatetable($table, $primarykey, $keyvalue, $fieldname, $fieldvalue){
            $table = TableRegistry::get($table);
            $table->query()->update()->set([$fieldname => $fieldvalue])->where([$primarykey => $keyvalue])->execute();
        }

        public function loadprofile($UserID, $fieldname = "id", $table = "profiles") {
            $table = TableRegistry::get($table);
            $results = $table->find('all', array('conditions' => array($fieldname => $UserID)))->first();
            return $results;
        }

        function setproductstatus($ClientID, $ProductNumber, $Status){
            if ($ClientID==-1){//global
                $table = TableRegistry::get('order_products');
                if($Status){$Status=1;} else {$Status=0;}
                $table->query()->update()->set(['enable' => $Status])->where(['number' => $ProductNumber])->execute();
            } else {//local
                $table = TableRegistry::get('client_products');//ProductID, Province, FormID
                if ($Status) {
                    $Item = $table->find()->where(['ClientID' => $ClientID, 'ProductNumber' => $ProductNumber])->first();
                    if (!$Item) {
                        $table->query()->insert(['ClientID', "ProductNumber"])->values(['ClientID' => $ClientID, 'ProductNumber' => $ProductNumber])->execute();
                    }
                } else {
                    $table->deleteAll(array('ClientID' => $ClientID, 'ProductNumber' => $ProductNumber), false);
                }
            }
        }
        function getproductlist($ClientID){
            $products = TableRegistry::get('order_products')->find('all');
            $client = TableRegistry::get('client_products')->find()->where(['ClientID'=>$ClientID]);
            foreach($products as $product){
                $product->clientenabled = false;
                foreach($client as $item){
                    if ($item->ProductNumber == $product->number){
                        $product->clientenabled = true;
                        break;
                    }
                }
            }
            $this->set('products',  $products);
            return $products;
        }

        function generateproductHTML($ClientID, $ordertype, $Language = "English"){//$ordertype = acronym
            //function productslist($ordertype, $products, $ID, $Checked = false, $Blocked = ""){
            $Product =  TableRegistry::get('product_types')->find()->where(['Acronym' => $ordertype])->first();
            if ($Product->Checked == 1) { $Checked = ' checked disabled';} else { $Checked = "";}
            if( $Product->Blocked) {$Blocked = explode(",", $Product->Blocked);}else {$Blocked = "";}
            $products = $this->getproductlist($ClientID);
            $count=0;

            $NameField = "title";
            if($Language=="Debug"){$Trans = " [Translated]";} else {$Trans ="";}
            if($Language!="English" && $Language != "Debug"){$NameField.=$Language;}
            foreach ($products as $p) {
                $isfound=true;
                if(is_array($Blocked)){$isfound=in_array($p->number, $Blocked);}
                if($isfound && $p->clientenabled) {
                    echo '<li id="product_' . $p->number . '"><div class="col-xs-10"><i class="fa fa-file-text-o"></i> ';
                    echo '<label for="form' . $count . '">' . $p->$NameField . $Trans . '</label></div>';
                    echo '<div class="col-xs-2"><input type="checkbox" value="' . $p->number . '" id="form' . $count . '"' . $Checked . '/></div>';
                    echo '<div class="clearfix"></div></li>';
                    $count+=1;
                }
            }
            if($count==0){//http://localhost/veritas3-0/2
                echo '<DIV ALIGN="CENTER"><A HREF="' . $this->request->webroot . 'clients/edit/' . $ClientID . '?products">' . $this->Trans->getString("flash_noproducts") . '</A></DIV>';
            }
        }

        function edit($id = null){
            if(!is_numeric($id)){die("ID should be a number, but it is: " . $id);}//error message that doesn't need translating
            $check_client_id = $this->Settings->check_client_id($id);
            if ($check_client_id == 1) {
                $this->Flash->error($this->Trans->getString("flash_norecord"));
                return $this->redirect("/clients/index");
                //die();
            }

            $checker = $this->Settings->check_client_permission($this->request->session()->read('Profile.id'), $id);
            if ($checker == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (016)');
                return $this->redirect("/clients/index");
            }

            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));
            if (isset($_GET['view']) && $setting->client_list == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (015)');
                return $this->redirect("/clients");
            }
            if(isset($_GET['flash'])) {
                $this->Flash->success($this->Trans->getString("flash_clientsaved"));
            }


            
            $this->loadModel("ClientTypes");
            $this->set('client_types', $this->ClientTypes->find()->where(['enable' => '1'])->all());
            $docs = TableRegistry::get('client_docs');
            $query = $docs->find();
            $client_docs = $query->select()->where(['client_id' => $id])->all();
            $this->set('client_docs', $client_docs);
            $client = $this->Clients->get($id, [
                'contain' => []
            ]);
            $arr = explode(',', $client->profile_id);
            $arr2 = explode(',', $client->contact_id);

            if ($this->request->is(['patch', 'post', 'put'])) {
                $clients = $this->Clients->patchEntity($client, $this->request->data);
                if ($this->Clients->save($clients)) {
                    $this->Flash->success($this->Trans->getString("flash_usersaved"));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error($this->Trans->getString("flash_usernotsaved"));
                }
            }

            //$client_details = $query->select()->where(['id'=>$id]);
            $this->set(compact('client'));
            //$this->set('client_details',$client_details);
            $this->set('id', $id);
            $this->set('profile', $arr);
            $this->set('contacts', $arr2);

            $this->getproductlist($id);
            $this->render('add');
        }

        function delete($id = null){
            $settings = $this->Settings->get_settings();
            $check_client_id = $this->Settings->check_client_id($id);
            if ($check_client_id == 1) {
                $this->Flash->error($this->Trans->getString("flash_norecord"));
                return $this->redirect("/clients/index");
                //die();
            }
            if (isset($_GET['draft'])) {
                $draft = "?draft";
            } else {
                $draft = "";
            }
            $checker = $this->Settings->check_client_permission($this->request->session()->read('Profile.id'), $id);
            if ($checker == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (014)');
                return $this->redirect("/clients/index" . $draft);
            }
            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));

            if ($setting->client_delete == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (013)');
                return $this->redirect("/");
            }
            $profile = $this->Clients->get($id);
            //$this->request->allowMethod(['post', 'delete']);
            if ($this->Clients->delete($profile)) {
                $sub_c = TableRegistry::get('client_sub_order');
                $del = $sub_c->query();
                $del->delete()->where(['client_id' => $id])->execute();
                TableRegistry::get('client_products')->deleteAll(array('ClientID' => $id), false);
                $this->Flash->success($this->Trans->getString("flash_clientdeleted"));
            } else {
                $this->Flash->error($this->Trans->getString("flash_clientnotdeleted"));
            }
            return $this->redirect(['action' => 'index' . $draft]);
        }

        function quickcontact(){
            if (isset($_POST["Type"]) || isset($_GET["Type"])) {
                $this->HandleAJAX();
                die();
            } else {
                echo "Needs a type!";
                die();
            }
        }

        function getSub(){
            $sub = TableRegistry::get('Subdocuments');
            $query = $sub->find();
            $q = $query->select();
            $this->response->body($q);
            return $this->response;
            die();
        }

        function getFirstSub($id, $ReturnAsResponse = true){
            //echo $id;die();
            $sub = TableRegistry::get('subdocuments');
            $query = $sub->find();
            $q = $query->select()->where(['id' => $id])->first();
            if($ReturnAsResponse) {
                $this->response->body($q);
                return $this->response;
                die();
            }
            return $q;
        }

        function getSubCli($id){
            $sub = TableRegistry::get('client_sub_order');
            $query = $sub->find();
            $q = $query->select()->where(['client_id' => $id])->order(['display_order' => 'ASC']);
            $this->response->body($q);
            return $this->response;
            die();
        }
        
        function getSubCliApplication($id){
            $sub = TableRegistry::get('client_application_sub_order');
            $query = $sub->find();
            $q = $query->select()->where(['client_id' => $id])->order(['display_order' => 'ASC']);
            $this->response->body($q);
            return $this->response;
            die();
        }


        function getSubCli2($id, $type="", $getTitle=false, $SortByTitle=false){
            $sub = TableRegistry::get('client_sub_order');
            $query = $sub->find();
            if($type=="") {
                $q = $query->select()->where(['client_id' => $id, 'sub_id IN (SELECT id FROM subdocuments WHERE display = 1 AND orders = 1)', 'sub_id IN (SELECT subdoc_id FROM clientssubdocument WHERE display_order = 1 AND client_id = ' . $id . ')', 'sub_id IN (SELECT subdoc_id FROM profilessubdocument WHERE profile_id = ' . $this->request->session()->read('Profile.id') . ' AND (display = 3 OR display = 2))'])->order(['display_order' => 'ASC']);
            }elseif($type =='document') {
                $q = $query->select()->where(['client_id' => $id, 'sub_id IN (SELECT id FROM subdocuments WHERE display = 1 )', 'sub_id IN (SELECT subdoc_id FROM clientssubdocument WHERE display = 1 AND client_id = ' . $id . ')', 'sub_id IN (SELECT subdoc_id FROM profilessubdocument WHERE profile_id = ' . $this->request->session()->read('Profile.id') . ' AND (display = 3 OR display = 2))'])->order(['display_order' => 'ASC']);
            }
            if($getTitle){
                $q2=array();
                foreach($q as $document){
                    $document->test="Hello";
                    $document->subtype = $this->getFirstSub($document->sub_id, false);
                    $document->title = $document->subtype->title;
                    $q2[] = $document;
                }
                if($SortByTitle){usort($q2, array($this,"cmp"));}
                $this->response->body($q2);
            } else {
                $this->response->body($q);
            }
            return $this->response;
            die();
        }
        function cmp($a, $b){
            return strcmp($a->subtype->title, $b->subtype->title);
        }

        function orders_doc($cid,$o_name){
            $products = TableRegistry::get('product_types');
            $ordertype=strtolower(urldecode($o_name));
            //$product = $products->find()->where(['Name'=>urldecode($o_name)])->first();
            $product=$products->find('all', array('conditions' => array("OR" => array( array('LOWER(Acronym)' => $ordertype),array('LOWER(Name)' => $ordertype)))))->first();


            $doc_ids= $product->doc_ids;
            //echo $doc_ids;die()
            //die($doc_ids);
            if($doc_ids!="" && $product->Bypass==0) {
                $doc = TableRegistry::get('client_sub_order');
                $query = $doc->find();
                $q= $query->select()->where(['client_id' => $cid,'sub_id IN('.$doc_ids.')','sub_id IN (SELECT subdoc_id FROM clientssubdocument WHERE display_order = 1 AND client_id = '.$cid.')']);
            } else {
                $q = null;
            }
            $this->response->body($q);
            return $this->response;
            die; 
        }

        function getCSubDoc($c_id, $doc_id){
            $sub = TableRegistry::get('clientssubdocument');
            $query = $sub->find();
            $query->select()->where(['client_id' => $c_id, 'subdoc_id' => $doc_id]);
            $q = $query->first();
            $this->response->body($q);
            return $this->response;
        }

        function displaySubdocs($id){
            //var_dump($_POST);die();
            $user['client_id'] = $id;
            $display = $_POST; //defining new variable for system base below upcoming foreach
            //for user base
            foreach ($_POST as $k => $v) {
                if ($k == 'clientC') {
                    foreach ($_POST[$k] as $k2 => $v2) {
                        $subp = TableRegistry::get('clientssubdocument');
                        $query = $subp->find();
                        $query->select()
                            ->where(['client_id' => $id, 'subdoc_id' => $k2]);
                        $check = $query->first();

                        if ($v2 == '1') {
                            if ($check) {
                                $query2 = $subp->query();
                                $query2->update()
                                    ->set(['display' => $v2])
                                    ->where(['client_id' => $id, 'subdoc_id' => $k2])
                                    ->execute();
                            } else {
                                $query2 = $subp->query();
                                $query2->insert(['client_id', 'subdoc_id', 'display'])
                                    ->values(['client_id' => $id, 'subdoc_id' => $k2, 'display' => $v2])
                                    ->execute();
                            }
                        } else {
                            if ($check) {
                                $query2 = $subp->query();
                                $query2->update()
                                    ->set(['display' => 0])
                                    ->where(['subdoc_id' => $k2, 'client_id' => $id])
                                    ->execute();
                            } else {
                                $query2 = $subp->query();
                                $query2->insert(['client_id', 'subdoc_id', 'display'])
                                    ->values(['client_id' => $id, 'subdoc_id' => $k2, 'display' => 0])
                                    ->execute();
                            }
                        }

                    }
                }
                if ($k == 'clientO') {
                    foreach ($_POST[$k] as $k2 => $v2) {
                        //echo $id.'_'.$k2.'_'.$v2.'<br/>';
                        $subp = TableRegistry::get('clientssubdocument');
                        $query = $subp->find();
                        $query->select()
                            ->where(['client_id' => $id, 'subdoc_id' => $k2]);
                        $check = $query->first();

                        if ($v2 == '1') {

                            if ($check) {

                                $query2 = $subp->query();
                                $query2->update()
                                    ->set(['display_order' => $v2])
                                    ->where(['client_id' => $id, 'subdoc_id' => $k2])
                                    ->execute();
                            } else {

                                $query2 = $subp->query();
                                $query2->insert(['client_id', 'subdoc_id', 'display_order'])
                                    ->values(['client_id' => $id, 'subdoc_id' => $k2, 'display_order' => $v2])
                                    ->execute();
                            }
                        } else {
                            if ($check) {
                                $query2 = $subp->query();
                                $query2->update()
                                    ->set(['display_order' => 0])
                                    ->where(['subdoc_id' => $k2, 'client_id' => $id])
                                    ->execute();
                            } else {
                                $query2 = $subp->query();
                                $query2->insert(['client_id', 'subdoc_id', 'display_order'])
                                    ->values(['client_id' => $id, 'subdoc_id' => $k2, 'display_order' => 0])
                                    ->execute();
                            }
                        }

                    }
                }
            }
            unset($display['clientC']);
            unset($display['clientO']);
            unset($display['client']);

            //For System base
            foreach ($display as $k => $v) {
                $subd = TableRegistry::get('Subdocuments');
                $query3 = $subd->query();
                $query3->update()
                    ->set(['display' => $v])
                    ->where(['id' => $k])
                    ->execute();
            }

            //var_dump($str);
            die('here');
        }
        
        function displaySubdocsApplication($id){
            //var_dump($_POST);die();
            $user['client_id'] = $id;
            $display = $_POST; //defining new variable for system base below upcoming foreach
            //for user base
            foreach ($_POST as $k => $v) {
                if ($k == 'clientC') {
                    foreach ($_POST[$k] as $k2 => $v2) {
                        $subp = TableRegistry::get('clientssubdocument');
                        $query = $subp->find();
                        $query->select()
                            ->where(['client_id' => $id, 'subdoc_id' => $k2]);
                        $check = $query->first();

                        if ($v2 == '1') {
                            if ($check) {
                                $query2 = $subp->query();
                                $query2->update()
                                    ->set(['display' => $v2])
                                    ->where(['client_id' => $id, 'subdoc_id' => $k2])
                                    ->execute();
                            } else {
                                $query2 = $subp->query();
                                $query2->insert(['client_id', 'subdoc_id', 'display'])
                                    ->values(['client_id' => $id, 'subdoc_id' => $k2, 'display' => $v2])
                                    ->execute();
                            }
                        } else {
                            if ($check) {
                                $query2 = $subp->query();
                                $query2->update()
                                    ->set(['display' => 0])
                                    ->where(['subdoc_id' => $k2, 'client_id' => $id])
                                    ->execute();
                            } else {
                                $query2 = $subp->query();
                                $query2->insert(['client_id', 'subdoc_id', 'display'])
                                    ->values(['client_id' => $id, 'subdoc_id' => $k2, 'display' => 0])
                                    ->execute();
                            }
                        }

                    }
                }
                if ($k == 'clientO') {
                    foreach ($_POST[$k] as $k2 => $v2) {
                        //echo $id.'_'.$k2.'_'.$v2.'<br/>';
                        $subp = TableRegistry::get('clientssubdocument');
                        $query = $subp->find();
                        $query->select()
                            ->where(['client_id' => $id, 'subdoc_id' => $k2]);
                        $check = $query->first();

                        if ($v2 == '1') {

                            if ($check) {

                                $query2 = $subp->query();
                                $query2->update()
                                    ->set(['display_application' => $v2])
                                    ->where(['client_id' => $id, 'subdoc_id' => $k2])
                                    ->execute();
                            } else {

                                $query2 = $subp->query();
                                $query2->insert(['client_id', 'subdoc_id', 'display_application'])
                                    ->values(['client_id' => $id, 'subdoc_id' => $k2, 'display_application' => $v2])
                                    ->execute();
                            }
                        } else {
                            if ($check) {
                                $query2 = $subp->query();
                                $query2->update()
                                    ->set(['display_application' => 0])
                                    ->where(['subdoc_id' => $k2, 'client_id' => $id])
                                    ->execute();
                            } else {
                                $query2 = $subp->query();
                                $query2->insert(['client_id', 'subdoc_id', 'display_application'])
                                    ->values(['client_id' => $id, 'subdoc_id' => $k2, 'display_application' => 0])
                                    ->execute();
                            }
                        }

                    }
                }
            }
            unset($display['clientC']);
            unset($display['clientO']);
            unset($display['client']);

            //For System base
            foreach ($display as $k => $v) {
                $subd = TableRegistry::get('Subdocuments');
                $query3 = $subd->query();
                $query3->update()
                    ->set(['display' => $v])
                    ->where(['id' => $k])
                    ->execute();
            }

            //var_dump($str);
            die('here');
        }

        function getProfile($id = null){
            $profile = TableRegistry::get('Clients');
            $query = $profile->find()->where(['id' => $id]);
            $q = $query->first();

            $pro = TableRegistry::get('Profiles');

            if (is_object($q)) {
                if ($q->profile_id) {
                    $q->profile_id = ltrim($q->profile_id, ',');
                }
            }

            $didit = false;
            if (is_object($q)) {
                if ($q->profile_id) {
                    $q->profile_id = str_replace(',,',',',$q->profile_id);
                    $q->profile_id = str_replace(',,',',',$q->profile_id);
                    $querys = $pro->find()->where(['id IN (' . $q->profile_id . ')']);
                    $didit = true;
                }
            }

            if (!$didit) {
                $querys = array();
            }
            $this->response->body(($querys));
            return $this->response;
        }

        function getContact($id = null){
            $contact = TableRegistry::get('Clients');
            $query = $contact->find()->where(['id' => $id]);
            $q = $query->first();
            $pro = TableRegistry::get('Profiles');

            $didit = false;
            if (is_object($q)) {
                if ($q->contact_id) {
                    $querys = $pro->find()->where(['id IN (' . $q->contact_id . ')']);
                    $didit = true;
                }
            }

            if (!$didit) {
                $querys = array();
            }
            $this->response->body(($querys));
            return $this->response;

        }

        function getDocCount($id = null){
            $doc = TableRegistry::get('Documents');
            $query = $doc->find();
            $count = $query->select()->where(['client_id' => $id]);
            $this->response->body(($count));
            return $this->response;
        }

        function getOrderCount($id = null){
            $doc = TableRegistry::get('Orders');
            $query = $doc->find();
            $count = $query->select()->where(['client_id' => $id]);
            $this->response->body(($count));
            return $this->response;
        }

        function countClientDoc($id = null, $doc_type = null){
            $query = TableRegistry::get('Documents');
            $q = $query->find();
            $q = $q->select()->where(['document_type' => $doc_type])->andWhere(['client_id' => $id]);
            $this->response->body($q);
            return $this->response;
        }

        function getClient($id = null){
            $contact = TableRegistry::get('Clients');
            $query = $contact->find()->where(['id' => $id]);
            $q = $query->first();
            $this->response->body(($q));
            return $this->response;
            //return $q;

        }

        function getAllClient(){
            $query = TableRegistry::get('Clients');
            $q = $query->find();
            $u = $this->request->session()->read('Profile.id');
            if ($this->request->session()->read('Profile.super')) {
                $q = $q->select();
            }else {
                $q = $q->select()->where(['profile_id LIKE "' . $u . ',%" OR profile_id LIKE "%,' . $u . ',%" OR profile_id LIKE "%,' . $u . '" OR profile_id LIKE "' . $u . '" ']);
            }

            $this->response->body($q);
            return $this->response;
        }

        function getAjaxClient($id = ""){
            $this->layout = 'blank';
            $key = $_GET['key'];
            $query = TableRegistry::get('Clients');
            $q = $query->find();
            $u = $this->request->session()->read('Profile.id');
            if ($this->request->session()->read('Profile.super'))
                $q = $q->select()->where(['company_name LIKE "%' . $key . '%"']);
            else {
                $q = $q->select()->where(['(profile_id LIKE "' . $u . ',%" OR profile_id LIKE "%,' . $u . ',%" OR profile_id LIKE "%,' . $u . '"  OR profile_id LIKE "' . $u . '" ) AND company_name LIKE "%' . $key . '%" ']);

            }
            $this->set('clients', $q);
            $this->set('id', $id);

        }

        function getdivision($cid){
            $query = TableRegistry::get('client_divison');
            $q = $query->find()->where(['client_id' => $cid])->all();
            $this->response->body($q);
            return $this->response;

        }

        function dropdown(){
            $this->layout = 'blank';
        }

        

        function getdivisions($did = ""){
            $cid = $_POST['client_id'];
            $query = TableRegistry::get('client_divison');
            $q = $query->find()->where(['client_id' => $cid])->all();
            if (count($q) > 0) {
                echo "<select class='form-control input-inline' name='division'><option value=''>" . $this->Trans->getString("orders_division") . "s</option>";
                foreach ($q as $d) {
                    $sel = ($did == $d->id) ? "selected='selected'" : '';
                    echo "<option value='" . $d->id . "'" . $sel . " >" . $d->title . "</option>";
                }
                echo "</select>";
            }
            die();
        }

        function divisionDropDown($cid){
            $size = "xlarge";
            if (isset($_GET["istable"])) {
                if ($_GET["istable"] == 1) {
                    $size = "large";
                }
            }
            $size = "ignore";

            $query = TableRegistry::get('client_divison');
            $q = $query->find()->where(['client_id' => $cid])->all();
            $q2 = $q;
            $u = 0;
            foreach ($q2 as $q3) {
                $u++;
            }
            if (count($q) > 0) {
                if ($size == "large" || $size == "ignore") {
                    echo '<div class="row">';
                }
                echo '<div class="col-xs-3 control-label" align="right" style="margin-top: 6px;">' . $this->Trans->getString("orders_division") . ' </div><div class="col-xs-8 ">';

                if ($u != 1) { //form-control input-xlarge select2me
                    echo "<select class='form-control select2me input-" . $size . "' name='division' id='divisionsel'>";
                } else {
                    echo "<select class='form-control select2me input-" . $size . "' name='division' id='divisionsel' disabled='disabled'>";
                }
                foreach ($q as $d) {
                    $sel = '';
                    echo "<option value='" . $d->id . "'" . $sel . " >" . $d->title . "</option>";
                }
                echo "</select></div>";
                if ($size == "large" || $size == "ignore") {
                    echo "</div>";
                }
            }
            die();
        }

        function charlie(){
            $this->layout = 'blank';
        }

        function forOrder(){
            $sub_sup = TableRegistry::get('subdocuments');
            $sub_sup_count = $sub_sup->find()->count();
            $counter = $sub_sup_count + 1;
            $query = TableRegistry::get('clients');
            $q = $query->find()->all();
            foreach ($q as $c) {
                $arr_s['client_id'] = $c->id;
                for ($i = 1; $i < $counter; $i++) {
                    $arr_s['sub_id'] = $i;
                    $sub_c = TableRegistry::get('client_sub_order');
                    $sc = $sub_c->newEntity($arr_s);
                    $sub_c->save($sc);
                }
            }
            die();
        }

        function updateOrder($cid){
            $ids = $_POST['tosend'];
            $arr = explode(',', $ids);
            $arr_s['client_id'] = $cid;
            $sub_c = TableRegistry::get('client_sub_order');
            $del = $sub_c->query();
            $del->delete()->where(['client_id' => $cid])->execute();
            foreach ($arr as $k => $sid) {
                $arr_s['sub_id'] = $sid;
                $arr_s['display_order'] = $k + 1;

                $sc = $sub_c->newEntity($arr_s);
                $sub_c->save($sc);
            }
            die();
        }
        
        function updateOrderApplication($cid){
            $ids = $_POST['tosend'];
            $arr = explode(',', $ids);
            $arr_s['client_id'] = $cid;
            $sub_c = TableRegistry::get('client_application_sub_order');
            $del = $sub_c->query();
            $del->delete()->where(['client_id' => $cid])->execute();
            foreach ($arr as $k => $sid) {
                $arr_s['sub_id'] = $sid;
                $arr_s['display_order'] = $k + 1;

                $sc = $sub_c->newEntity($arr_s);
                $sub_c->save($sc);
            }
            die();
        }

        function addsubdocs(){
            $languages = explode(",", $_GET["languages"]);
            $data = array();
            foreach($languages as $language){
                if($language == "English"){ $language = "";}
                $data["title" . $language] = $_GET['sub' . $language];
            }

            //$client_id = $_GET['client_id'];
            if ($this->request->session()->read('Profile.super')) {
                if (isset($_GET['updatedoc_id'])) {
                    $doc_id = $_GET['updatedoc_id'];
                    $up_que = TableRegistry::get('subdocuments');
                    $query = $up_que->query();

                    $data["ProductID"] =  $_GET["productid"];
                    $data["icon"] = $_GET["icon"];
                    $q_update = $query->update()////url = url + "&icon=" + icon + "&productid=" product;
                        ->set($data)
                        ->where(['id' => $doc_id])
                        ->execute();
                    if (isset($_GET['color'])) {
                        $color = $_GET['color'];
                        $sel_query = $up_que->find()->where(['id' => $doc_id])->first;
                        {
                            $col = $sel_query->color_id;
                            if ($col != $color) {
                                $q_update = $query->update()
                                    ->set(['color_id' => $color])
                                    ->where(['id' => $doc_id])
                                    ->execute();

                            }
                        }
                    }
                    if ($q_update) return $this->redirect("/profiles/settings/?activedisplay");

                } else {

                    $que = TableRegistry::get('subdocuments');
                    //$que = $queries->query();
                    $col_query = TableRegistry::get('color_class');
                    $col_q = $col_query->find('all')->order('rand()')->first();
                    $col_id = $col_q->id;
                    //$col_q = $col_q->select(['id'])->where(['order' => 'rand()', 'limit' => 1])->execute();

                    $data["display"] = 1;
                    $data["table_name"] = $data["title"];
                    $data["orders"] = 1;
                    $data["color_id"] = $col_id;

                    $q = $que->newEntity($data);
                    $que->save($q);
                    if ($q) {
                        $sid = $q->id;
                        $clientsubdocs = TableRegistry::get('clientssubdocument');
                        $clientsubdoc = $clientsubdocs->find();
                        $csd = $clientsubdoc->select(['client_id'])->distinct(['client_id']);
                        if ($csd) {
                            $checker_q2 = 0;
                            foreach ($csd as $c) {
                                $clientsubdoc_q = $clientsubdocs->query();
                                $q2 = $clientsubdoc_q->insert(['client_id', 'subdoc_id', 'display', 'display_order'])
                                    ->values([
                                        'client_id' => $c->client_id,
                                        'subdoc_id' => $sid,
                                        'display' => 0,
                                        'display_order' => 0
                                    ])
                                    ->execute();
                                if ($q2) {
                                    $checker_q2 = 1;
                                }
                            }
                        }

                        $profilesubdocs = TableRegistry::get('profilessubdocument');
                        $profilesubdoc = $profilesubdocs->find();
                        $psd = $profilesubdoc->select(['profile_id'])->distinct(['profile_id']);
                        if ($psd) {
                            $checker_q3 = 0;
                            foreach ($psd as $p) {
                                $profilesubdoc_q = $profilesubdocs->query();
                                $q3 = $profilesubdoc_q->insert(['profile_id', 'subdoc_id', 'display'])
                                    ->values([
                                        'profile_id' => $p->profile_id,
                                        'subdoc_id' => $sid,
                                        'display' => 0
                                    ])
                                    ->execute();
                                if ($q3) {
                                    $checker_q3 = 1;
                                }
                            }
                        }

                        $clientsuborders = TableRegistry::get('client_sub_order');
                        $clientsuborder = $clientsuborders->find();
                        $cbo = $clientsuborder->select(['client_id'])->distinct(['client_id']);
                        if ($cbo) {
                            $checker_q4 = 0;
                            foreach ($cbo as $o) {
                                $clientsuborder_q = $clientsuborders->query();
                                $q4 = $clientsuborder_q->insert(['client_id', 'sub_id', 'display_order'])
                                    ->values([
                                        'client_id' => $o->client_id,
                                        'sub_id' => $sid,
                                        'display_order' => 0
                                    ])
                                    ->execute();
                                if ($q4) {
                                    $checker_q4 = 1;
                                }
                            }
                        }

                        if ($checker_q2 && $checker_q3 && $checker_q4) {
                            return $this->redirect("/profiles/settings/?activedisplay");
                        } else {
                            return $this->redirect("/");
                        }
                    }
                }
            } else {
                $this->Flash->error($this->Trans->getString("flash_permissions") . ' (012)');
                return $this->redirect("/");
            }
        }

        public function check_document($subid = ''){
            $languages = $_POST["languages"];

            //$subname = strtolower($subname);
            $q = TableRegistry::get('subdocuments');
            $que = $q->find();

            foreach($languages as $language){
                if($language == "English"){ $language = "";}
                if ($subid) {
                    $query = $que->select()->where(['id !=' => $subid, 'title' . $language => $_POST['subdocumentname' . $language]])->first();
                } else {
                    $query = $que->select()->where(['title' . $language => $_POST['subdocumentname' . $language]])->first();
                }
                if ($query){ return '1';}
            }
            echo '0';
            die();
        }

        public function getColorClass(){
            $query = TableRegistry::get('color_class');
            $q = $query->find()->all();
            $this->response->body($q);
            return $this->response;
            die;
        }

        public function appendattachments($query){
            foreach ($query as $client) {
                $client->hasattachments = $this->hasattachments($client->id);
            }
            return $query;
        }

        public function hasattachments($id){
            $docs = TableRegistry::get('client_docs');
            $query = $docs->find();
            $client_docs = $query->select()->where(['client_id' => $id])->first();
            if ($client_docs) {
                return true;
            }
        }

        public function getLogo(){
            $id = $this->request->session()->read('Profile.id');
            $client = TableRegistry::get('clients')->find()->where(['profile_id LIKE "'.$id.',%" OR profile_id LIKE "%,'.$id.',%" OR profile_id LIKE "%,'.$id.'"'])->first();
            $image = array();
            if($client)
            $image['client'] = $client->image;
            else
            $image['client'] = false;
            if(!$image['client']) {
                if($client) {
                    $cid = $client->id;
                    $setting = TableRegistry::get('settings')->find()->where(['id'=>1])->first();
                    $image['setting'] = $setting->client_img;
                }
                
            }
            $this->response->body($image);
            return $this->response;
        }
        
        function requalify($cid){
            $p = '';
            foreach($_POST['requalify_product'] as $k=>$r) {
                if($k+1==count($_POST['requalify_product'])) {
                    $p .= $r;
                }else {
                    $p .= $r . ",";
                }
            }
            if(!isset($_POST['requalify'])) {
                $_POST['requalify'] = 0;
            }
            if(!isset($_POST['requalify_re'])) {
                $_POST['requalify_re'] = 0;
            }
            $_POST['requalify_product'] = $p;
            $id = $_POST['id'];
            $cleint = TableRegistry::get('clients');

            $RunCron = isset($_POST["runcron"]) && $_POST["runcron"];
            unset($_POST["runcron"]);

            $query = $cleint->query();
                        $query->update()
                            ->set($_POST)
                            ->where(['id' => $id])
                            ->execute();
            if($RunCron){$this->cron($this->Manager->read("email"));}
            die();
        }
        

        function cron($IsDebug = false) {
            $msg =              "";
            $client_crons =     TableRegistry::get('client_crons');
            $ord =              TableRegistry::get('orders');
            $clients =          TableRegistry::get('clients')->find('all')->where(['requalify'=>'1']);
            $marr =             array();
            $Subject =          'Requalifed';
            
            foreach($clients as $c) {
                $msg .= "<TR><TD>" . $c->id . '</TD><TD>' . $c->company_name . '</TD><TD>';
                if($c->requalify_re == '0') {
                     $date = $c->requalify_date;
                }
                
                $today = date('Y-m-d');
                
                $frequency = $c->requalify_frequency;
                $forms = $c->requalify_product;
                $msg .= "Selected Forms: ".$forms."<br/>";

                $nxt_date = $this->getnextdate($today,$frequency);
                $pro = '';
                $p_type = '';
                $p_name = "";
                $emails ='';
                $profile_type = TableRegistry::get("profile_types")->find('all')->where(['placesorders'=>1]);
                foreach($profile_type as $ty) {
                    $p_type .= $ty->id.",";
                }
                $p_types = substr($p_type,0,strlen($p_type)-1);
                $users = explode(',',$c->profile_id);
                $rec = array();
                //escape already processed profiles
                $escape_id = $client_crons->find('all')->where(['client_id'=>$c->id,'orders_sent'=>'1','cron_date'=>$today]);
                $escape_ids = '';
                foreach($escape_id as $ei) {
                    $escape_ids .= $ei->profile_id.",";
                }
                if($escape_ids!= '') {
                    $escape_ids = substr($escape_ids, 0, strlen($escape_ids) - 1);
                }else {
                    $escape_ids = '0';
                }
                $profile = TableRegistry::get('profiles')->find('all')->where(['id IN('.$c->profile_id.')','id NOT IN ('.$escape_ids.')','requalify'=>'1', 'profile_type IN('.$p_types.')','expiry_date<>""','expiry_date >='=>$today]);
                foreach($profile as $p) {
                    if($c->requalify_re == '1') {
                         $date = $p->hired_date;
                          if(strtotime($date) < strtotime($today)) {
                                $date =  $this->getnextdate($date,$frequency);
                          }
                    }
                    if($today == $date || $date == $nxt_date || $IsDebug) {
                        $pro .=$p->id.","; 
                        $p_name .= $p->username.",";
                    }
                  }
                  if($pro !=""){
                  $msg .= "Profiles:".$p_name."<br/>";
                  if($IsDebug){
                      $this->Mailer->sendEmail("", $IsDebug, $Subject, $msg);//sendEmail should never be used, use handlevent instead
                  } else {
                      $recruiters = TableRegistry::get('profiles')->find('all')->where(['id IN(' . $c->profile_id . ')', 'requalify' => '1', 'profile_type IN' => '2', 'email<>""']);
                      foreach ($recruiters as $emrec) {
                          array_push($rec, $emrec->email);
                          $emails .= $emrec->email . ",";
                          $this->Mailer->sendEmail("", $emrec->email, $Subject, $msg);//sendEmail should never be used, use handlevent instead
                      }
                  }
                  
                  $emails = substr($emails,0,strlen($emails)-1);
                  $pro = substr($pro,0,strlen($pro)-1);
                  //$p_name = substr($p_name,0,strlen($p_name)-1);
                  //$this->bulksubmit($pro,$forms,$c->id);
                  $msg .= "Emails Sent to:".$emails."<br/>";
                  $dri = $pro;
              
                    $drivers = explode(',',$dri);
                    //$forms = $_POST['forms'];
                    $arr['forms'] = $forms;
                    $arr['order_type'] = 'BUL';
                    $arr['draft'] = 0;
                    $arr['title'] = 'order_'.date('Y-m-d H:i:s');
                    
                    $arr['client_id'] = $c->id;
                    $arr['created'] = date('Y-m-d H:i:s');
                    //$arr['division'] = $_POST['division'];
                    //$arr['user_id'] = $this->request->session()->read('Profile.id');
                    $arr['driver'] = '';
                    $arr['order_id'] = '';
                    foreach($drivers as $driver) {
                        $arr['uploaded_for'] = $driver;
                        $doc = $ord->newEntity($arr);
                        if($ord->save($doc)) {
                            $cc['profile_id'] = $driver;
                            $cc['cron_date'] = date('Y-m-d');
                            $cc['client_id'] = $c->id;
                            $cc['orders_sent'] = '1';
                            $cc['order_id'] = $doc->id;
                            $cc['manual'] = '0';
                            $crons = $client_crons->newEntity($cc);
                            $client_crons->save($crons);
                        }
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
                        
                    }
                    array_push($marr,$arr);
                    unset($arr);
                } else {
                    $msg .= "No Profiles found.";
                }
            }
            $this->set('arrs',$marr);
            $this->set('msg',$msg);
            echo $msg . '</TD></TR>';
        }

    function getnextdate($date, $frequency) {
            $today = date('Y-m-d');
            $nxt_date = date('Y-m-d', strtotime($date)+($frequency*24*60*60*30));
            if (strtotime($nxt_date) < strtotime($today)) {
                $d = $this->getnextdate($nxt_date, $frequency);
            } else {
                $d = $nxt_date;
            }
            return $d;
    }

    function web($order_type = null, $forms = null, $driverid = null, $orderid = null) {
        $this->set('order_type',$order_type);
        $this->set('form',$forms);
        $this->set('driverid',$driverid);
        $this->set('orderid',$orderid);
    }
    
    function assignedTo($cid,$rid) {
        $cli = TableRegistry::get('clients')->find()->where(['id'=>$cid])->first();
        $pro = $cli->profile_id;
        $arr = explode(',',$pro);
        //echo $rid;
        //var_dump($arr);
        $check = in_array($rid,$arr);
        $this->response->body($check);
        return $this->response;
    }
    
    function createSlug()
    {
        $clients = TableRegistry::get('clients');
        $cli = $clients->find()->all();
        foreach($cli as $c)
        {
           $company_name = $c->company_name;
           $slug = strtolower($company_name);
           $slug = str_replace(' ','_',$slug);

                        //echo $s;die();
                        $query = $clients->query();
                        $query->update()
                            ->set(['slug'=>$slug])
                            ->where(['id' => $c->id])
                            ->execute(); 
        }
        die('Slugs generated successfully');
    }
        
}
?>
