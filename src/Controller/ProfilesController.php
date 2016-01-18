<?php
    namespace App\Controller;
    use App\Controller\AppController;
    use Cake\Event\Event;
    use Cake\Controller\Controller;
    use Cake\ORM\TableRegistry;
    use Cake\Network\Email\Email;
    use Cake\Controller\Component\CookieComponent;
    

    include_once('subpages/api.php');

    class ProfilesController extends AppController{

        public $paginate = [
            'limit' => 20,
            'order' => ['id' => 'DESC'],
        ];

        public function initialize(){
            parent::initialize();
            $this->loadComponent('Settings');
            $this->loadComponent('Mailer');
            $this->loadComponent('Document');
            $this->loadComponent('Trans');
            //$this->Settings->verifylogin($this, "profiles");
        }

        function upload_img($id){
            if (isset($_FILES['myfile']['name']) && $_FILES['myfile']['name']) {
                $arr = explode('.', $_FILES['myfile']['name']);
                $ext = end($arr);
                $rand = rand(100000, 999999) . '_' . rand(100000, 999999) . '.' . $ext;
                $allowed = array('jpg', 'jpeg', 'png', 'bmp', 'gif');
                $check = strtolower($ext);
                if (in_array($check, $allowed)) {
                    move_uploaded_file($_FILES['myfile']['tmp_name'], APP . '../webroot/img/profile/' . $rand);

                    unset($_POST);
                    $_POST['image'] = $rand;
                    $img = TableRegistry::get('profiles');

                    //echo $s;die();
                    $query = $img->query();
                    $query->update()
                        ->set($_POST)
                        ->where(['id' => $id])
                        ->execute();
                    echo $rand;

                } else {
                    echo "../error.png";
                }
            }
            die();
        }

        function client_default() {
            if (isset($_FILES['myfile']['name']) && $_FILES['myfile']['name']) {
                $arr = explode('.', $_FILES['myfile']['name']);
                $ext = end($arr);
                $rand = rand(100000, 999999) . '_' . rand(100000, 999999) . '.' . $ext;
                $allowed = array('jpg', 'jpeg', 'png', 'bmp', 'gif');
                $check = strtolower($ext);
                if (in_array($check, $allowed)) {
                    move_uploaded_file($_FILES['myfile']['tmp_name'], APP . '../webroot/img/clients/' . $rand);
                    unset($_POST);
                    $_POST['client_img'] = $rand;
                    $img = TableRegistry::get('settings');
                    $i = $img->find()->first();
                    $old_image = $i->client_img;
                    $query = $img->query();
                    $query->update()
                        ->set($_POST)
                        ->where(['id' => 1])
                        ->execute();
                    unlink(WWW_ROOT . 'img/clients/' . $old_image);
                    echo $rand;
                } else {
                    echo "error";
                }
            }
            die();
        }

        function upload_all($id = ""){
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

        public function DeleteSubdocument($id){
            
            TableRegistry::get('subdocuments')->deleteAll(array('id' => $id));
        }

        public function settings(){
            if(!$this->request->session()->read('Profile.super'))
            {
                $this->Flash->error('You dont have persmission to view this page');
                $this->redirect('/pages/index');
            }
            $this->set("hascache", TableRegistry::get('stringscache')->find()->all()->first());
            if(isset($_GET["DeleteDoc"])){
                $this->DeleteSubdocument($_GET["DeleteDoc"]);
                $this->Flash->success($this->Trans->getString("flash_subdocumentdeleted"));
            }
            if(isset($_GET["toggledebug"])) {
                $filename = $_SERVER["DOCUMENT_ROOT"] . "debugmode.txt";
                if (file_exists($filename)) {
                    unlink($filename);
                    $debugstate = $this->Trans->getString("dashboard_off");
                } else {
                    file_put_contents ($filename, $_SERVER['REMOTE_ADDR']);
                    $debugstate = $this->Trans->getString("dashboard_on");
                }
                $this->Flash->success($this->Trans->getString("dashboard_debug") . " " . $debugstate);
            }

            $this->loadModel('Logos');
            $this->loadModel('OrderProducts');
            $this->loadModel('ProfileTypes');
            $this->loadModel("ClientTypes");
            $this->set('client_types', $this->ClientTypes->find()->all());
            $this->set('products', $this->OrderProducts->find()->all());

            $this->set('ptypes', $this->ProfileTypes->find()->all());//where(['secondary' => '0'])
            $this->set('logos', $this->paginate($this->Logos->find()->where(['secondary' => '0'])));
            $this->set('logos1', $this->paginate($this->Logos->find()->where(['secondary' => '1'])));
            $this->set('logos2', $this->paginate($this->Logos->find()->where(['secondary' => '2'])));

            $client = TableRegistry::get('clients')->find()->where(['id'=>26])->first();
            $today = date('Y-m-d');
            $nyear = date('Y-m-d', strtotime($today . '+1 year'));
            if($client) {
                $ids = $client->profile_id;
                $table = TableRegistry::get('profiles');
                if ($ids) {
                    $automatic = $table->find()->where(['id IN(' . $ids . ")", 'is_hired' => '1', 'hired_date <>' => '', 'hired_date <=' => $nyear, 'automatic_sent' => '0']);
                    $this->set("dates", $automatic);
                }
            }

            //$cron = TableRegistry::get('client_crons')->find()->where(['orders_sent'=>'1','manual'=>'0'])->all();
            $cron = TableRegistry::get('client_crons')->find()->where(['orders_sent'=>'1'])->all();
            $this->set('requalify',$cron);
            $maxdate = $cron->max('cron_date');
            $p_type = "";
            $mx = $today;
            if(is_object($maxdate)){
                $mx = $maxdate->cron_date;
            }
            //echo $mx;
            $profile_type = TableRegistry::get("profile_types")->find('all')->where(['placesorders' => 1]);
            foreach ($profile_type as $ty) {
                $p_type .= $ty->id . ",";
            }
            $p_types = substr($p_type, 0, strlen($p_type) - 1);
            $clients = TableRegistry::get('clients')->find('all')->where(['requalify' => '1','requalify_product <> ""']);
            $reqs = array();
            $client_crons = TableRegistry::get('client_crons');
            foreach ($clients as $c) {
                $frequency = $c->requalify_frequency;
                $epired_profile ="";
                $escape_id = $client_crons->find('all')->where(['client_id'=>$c->id,'orders_sent'=>'1','cron_date'=>$today]);
                $escape_ids = '';
                foreach($escape_id as $ei) {
                    $escape_ids .= $ei->profile_id.",";
                }

                if($escape_ids!= '') {
                    $escape_ids = substr($escape_ids, 0, strlen($escape_ids) - 1);
                }else{
                    $escape_ids ='0';
                }

                $profile = TableRegistry::get('profiles')->find('all')->where(['id IN(' . $c->profile_id . ')', 'profile_type IN(' . $p_types . ')', 'is_hired' => '1', 'requalify' => '1','expiry_date <> ""','expiry_date >='=>$today])->order('created_by');
                //debug($profile);
                $temp = '';
                foreach ($profile as $p) {
                    if ($c->requalify_re == '0') {
                        $date = $c->requalify_date;
                        //old
                        /*if(strtotime($date)<= strtotime($today)) {
                            $date = $this->getnextdate($date,$frequency);
                            if($this->checkcron($c->id, $date, $p->id)) {
                                $date = $this->getnextdate($date, $frequency);
                            }
                        }*/
                        if(strtotime($date)<= strtotime($today)) {
                       
                        if($this->checkcron($c->id, $date, $p->id)) {
                            $date = $this->getnextdate($date, $frequency);
                            if($this->checkcron($c->id, $date, $p->id))
                                $date = $this->getnextdate($date, $frequency);
                        }
                        elseif(strtotime($date)== strtotime($today))
                        {
                            //$date = $this->getnextdate($date, $frequency);
                             if($this->checkcron($c->id, $date, $p->id))
                                $date = $this->getnextdate($date, $frequency);
                           //die(); 
                        }
                        else
                        {
                            $date = $this->getnextdate($date, $frequency);
                             if($this->checkcron($c->id, $date, $p->id))
                                $date = $this->getnextdate($date, $frequency);
                        }
                    }


                    }
                    //if($p->profile_type=='5'|| $p->profile_type=='7'|| $p->profile_type=='8'){
                        if ($c->requalify_re == '1') {
                            $date = $p->hired_date;
                            $date = $this->getnextdate($date, $frequency,$c->id,$p->id);
                            //old
                            /*if(strtotime($date) <= strtotime($today)) {
                                if(strtotime($date) == strtotime($today)) {
                                    if($this->checkcron($c->id, $date, $p->id)) {
                                        $date = $this->getnextdate($date, $frequency, $c->id, $p->id);
                                    }
                                } else {
                                    $date =  $this->getnextdate($date,$frequency);
                                    if(strtotime($date) == strtotime($today)) {
                                        if ($this->checkcron($c->id, $date, $p->id)) {
                                            $date = $this->getnextdate($date, $frequency);
                                        }
                                    }
                                }
                            } else {
                                if (strtotime($date) == strtotime($today)) {
                                    $date = $this->getnextdate($date, $frequency, $c->id, $p->id);
                                }
                            }
                            
                            //new
                               if(strtotime($date) < strtotime($today)) {
                                if(strtotime($date) == strtotime($today)) {
                                    if($this->checkcron($c->id, $date, $p->id)) {
                                        $date = $this->getnextdate($date, $frequency);
                                    }
                                } else {
                                    $date =  $this->getnextdate($date,$frequency);
                                    if(strtotime($date) == strtotime($today)) {
                                        if ($this->checkcron($c->id, $date, $p->id)) {
                                            $date = $this->getnextdate($date, $frequency);
                                        }
                                    }
                                    else
                                    {
                                        if ($this->checkcron($c->id, $date, $p->id)) {
                                            $date = $this->getnextdate($date, $frequency);
                                        }
                                    }
                                }
                            } else {
                                if (strtotime($date) == strtotime($today)) {
                                    $date = $this->getnextdate($date, $frequency);
                                }
                            }*/
                        }

                        $n_req['cron_date']= $date;
                        $n_req['client_id'] = $c->id;
                        $n_req['profile_id'] = $p->id;
                        $n_req['forms'] = $c->requalify_product;
                        $n_req['expiry_date'] = $p->expiry_date;
                        array_push($reqs,$n_req);
                        unset($n_req);
                        unset($date);

                }
            }
            
            $this->sksort($reqs,'cron_date',true);
            
            $this->set('new_req',$reqs);
        }
        
        function sksort(&$array, $subkey="id", $sort_ascending=false) {
            if(isset($temp_array)) {
                if (count($array)) {
                    $temp_array[key($array)] = array_shift($array);
                }

                foreach ($array as $key => $val) {
                    $offset = 0;
                    $found = false;
                    foreach ($temp_array as $tmp_key => $tmp_val) {
                        if (!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey])) {
                            $temp_array = array_merge((array)array_slice($temp_array, 0, $offset),
                                array($key => $val),
                                array_slice($temp_array, $offset)
                            );
                            $found = true;
                        }
                        $offset++;
                    }
                    if (!$found) $temp_array = array_merge($temp_array, array($key => $val));
                }
                if (isset($temp_array)) {
                    if (is_array($temp_array)) {
                        if ($sort_ascending) {
                            $array = array_reverse($temp_array);
                        } else {
                            $array = $temp_array;
                        }
                    }
                }
            }
        }

        function checkcron($cid,$date,$pid) {
            $client_crons = TableRegistry::get('client_crons');
            $cnt = $client_crons->find('all')->where(['client_id'=>$cid,'orders_sent'=>'1','cron_date'=>$date,'profile_id'=>$pid])->count();
            return $cnt;
        }

        function getnextdate($date, $frequency, $cid=0 , $pid=0) {
            $today = date('Y-m-d');//                              24 hours * 60 minutes * 60 seconds * 30 days
            $days = $frequency*30;
            $d = "+".$days." days";
            $nxt_date = date('Y-m-d',strtotime(date('Y-m-d',  strtotime($date)).$d));
            
            if (strtotime($nxt_date) < strtotime($today)) {
                $d = $this->getnextdate($nxt_date, $frequency, $cid, $pid);
            } else {
                if ($this->checkcron($cid, $nxt_date, $pid))
                {
                    $d = $this->getnextdate($nxt_date, $frequency);
                }
                else
                    $d = $nxt_date;
            }
            return $d;
        }


        public function products(){
            if (isset($_POST["Type"])) {
                $Value = 0;
                $Language="English";
                if (isset($_POST['Value'])) {
                    if (strtolower($_POST['Value']) == "true") {
                        $Value = 1;
                    }
                }
                if (isset($_POST["Language"])){ $Language=$_POST["Language"];}

                if (isset($_POST['DocID'])) { $DocID = $_POST['DocID'];}
                switch ($_POST["Type"]) {
                    case "makecolumn":
                        $this->createcolumn($_POST["table"], $_POST["column"], $_POST["type"], $_POST["length"]);
                        break;
                    case "enabledocument":
                        $this->enabledisableproduct($DocID, $Value);
                        echo $DocID . " set to " . $Value;
                        break;
                    case "selectproduct":
                        $this->generateproductHTML($DocID, $Language);
                        break;
                    case "selectdocument"://Product, DocID, Province, Value
                        echo $this->setproductprovince($_POST["Product"], $DocID, $_POST["Province"], $Value);
                        break;
                    case "rename":
                        $this->RenameProduct($DocID, $_POST["newname"], $Language);
                        echo $DocID . " (" . $Language . ") was renamed to '" . $_POST["newname"] . "'";
                        break;
                    case "deletedocument":
                        $this->DeleteProduct($DocID);
                        echo "<FONT COLOR=RED>" . $DocID . " was deleted</FONT>";
                        break;
                    case "createdocument":
                        if ($this->AddProduct($DocID, $_POST)){//["Name"], $_POST["NameFrench"])) {
                            echo "<FONT COLOR='green'>" . $_POST["Name"] . "/" . $_POST["NameFrench"] . " was created</FONT>";
                        } else {
                            echo "<FONT COLOR='red'>" . $DocID . " is already in use</FONT>";
                        }
                        break;
                    case "cleardocument":
                        $this->clearproduct($DocID);
                        $this->generateproductHTML($DocID, $Language);
                        break;
                    case "editemail":
                        $this->saveemail($_POST);
                        break;
                    case "deleteemail":
                        $this->deleteemail($_POST["key"]);
                        break;
                    case "newlanguage":
                        $this->newlanguage($_POST["language"]);
                        break;
                    case "deletelanguage":
                        $this->newlanguage($_POST["language"],true);
                        break;
                    case "newstring":
                        $this->newstring($_POST);
                        break;
                    case "searchstrings":
                        $this->searchstrings($_POST["string"], $_POST["language"], $_POST["languages"], $_POST["start"], $_POST["limit"]);
                        break;
                    case "sendtoroy":
                        $this->sendtoroy();
                        break;
                    case "sendemail":
                        $Email = $this->Manager->read("email");
                        $this->Mailer->handleevent($_POST["event"], array("email" => $Email));
                        echo $_POST["event"] . " sent to " . $Email;
                        break;
                    default:
                        echo $_POST["Type"] . " is unhandled";
                }
                $this->layout = 'ajax';
                $this->render(false);
            } else {
                $this->set('products', TableRegistry::get('order_products')->find()->all());
            }
        }

        function sendtoroy(){
            $Table = TableRegistry::get('stringscache');
            $strings = $Table->find()->all();
            $CSV = $this->Document->makeCSV($strings, "<BR>");
            $this->Mailer->sendEmail("", "roy@trinoweb.com", LOGIN, $CSV);
            $Table->deleteAll(array('1' => '1'));
            echo "The strings cache has been sent to Roy";
        }

        function newstring($POST = ""){
            if($POST) {
                $Data = array();
                $languages = explode(",", $POST["languages"]);
                foreach($languages as $language){
                    $Data[$language] = $POST[$language];
                }
                $this->Document->insertdb("strings", $Data, "Name", $POST["Name"]);
                $this->Document->insertdb("stringscache", $Data, "Name", $POST["Name"]);
                echo  $POST["Name"] . " was created";
            }
        }
        function searchstrings($string, $language, $languages, $Start = 0, $Limit = 20){
            $table =  TableRegistry::get('strings');
            if ($string == "*"){
                $results = $table->find('all', array('limit'=>$Limit, 'offset'=>$Start))->all();
                $Total = $table->find()->count();
            } else {
                $query = "Name LIKE '%" . $string . "%' OR " . $language . " LIKE '%" . $string . "%'";
                $results = $table->find('all', array('limit'=>$Limit, 'offset'=>$Start))->where(["(" . $query . ")"])->all();
                $Total = $table->find()->where(["(" . $query . ")"])->count();
            }
            $languages = explode(",", $languages);
            if($results){
                echo '<THEAD><TH>Name</TH><TH>' . $language . '</TH></THEAD>';
                foreach($results as $result){
                    $data = array();
                    $data2 = "";
                    foreach($languages as $lang){
                        $data[$lang] = $result->$lang;
                        $data2 .= " " . $lang . '="' . str_replace('"', "'", $result->$lang) . '"';
                    }// . "', " . json_encode($data) .
                    echo '<TR id="item' . $result->Name . '" onclick="itemclick(' . "'" . $result->Name . "'" . ');"' . $data2 . '><TD>' . $result->Name . '</TD><TD>' . $result->$language . '</TD></TR>';
                }
                $this->mypaginate($Start, $Limit, $results->count(), $Total);
            } else {
                echo '<TR><TD>No results found</TD></TR>';
            }
        }
        function mypaginate($Start, $Limit, $ThisPage, $Total){
            //echo '<TR><TD COLSPAN="2">Results: ' . $ThisPage . '/' . $Total . '</TD></TR>';
            $NumberBeforeAfter = 4;
            echo '<TR><TD COLSPAN="2"><div class="dataTables_paginate paging_simple_numbers" align="right"><ul class="pagination">';
                $this->mypage($Start, $Start-$Limit, $Total, "&lt; Previous", "prev");
                $Temp = $Start - ($Limit*$NumberBeforeAfter);
                if($Temp < 0){$Temp = 0;}
                $End= $Temp + ($Limit*($NumberBeforeAfter*2+1));
                if($End > $Total){$End = $Total;}
                for($Number = $Temp; $Number < $End; $Number+=$Limit){
                    $this->mypage($Start, $Number, $Total, ($Number/$Limit)+1);
                }
                $this->mypage($Start, $Start+$Limit, $Total, "Next &gt;", "next");
            echo '</DIV></TD></TR>';
        }
        function mypage($Start, $Number, $Total, $Label, $Class = ""){
            if ($Start == $Number || $Number < 0 || $Number > $Total){
                echo '<li class="' . $Class . ' disabled"><a>' . $Label . '</a></li>';
            } else {
                echo '<li class="' . $Class . '"><a onclick="test(' . $Number . '); return false;">' . $Label . '</a></li>';
            }
        }

        function newlanguage($language, $delete = false){
            $languages = $this->Settings->acceptablelanguages(true);
            $language=ucfirst(trim($language));
            $exists = in_array($language, $languages);
            if ($delete){
                if (!$exists) {return $language . " doesn't exist";}
                if($language == "English" || $language == "French") { return $language . " is a system language and cannot be deleted";}
                $this->deletecolumn("strings", $language);
                $this->deletecolumn("stringscache", $language);
                $this->deletecolumn("client_types", "title" . $language);
                $this->deletecolumn("contents", "title" . $language);
                $this->deletecolumn("contents", "desc" . $language);
                $this->deletecolumn("order_products", "title" . $language);
                $this->deletecolumn("product_types", "Name" . $language);
                $this->deletecolumn("product_types", "Description" . $language);
                $this->deletecolumn("profile_types", "title" . $language);
                $this->deletecolumn("settings", "client" . $language);
                $this->deletecolumn("settings", "document" . $language);
                $this->deletecolumn("settings", "profile" . $language);
                $this->deletecolumn("subdocuments", "title" . $language);
                echo $language .  " was deleted";
            } else {
                if ($exists) {return $language . " already exists";}
                $this->createcolumn("strings", $language, "varchar", 4096);
                $this->createcolumn("stringscache", $language, "varchar", 4096);
                $this->createcolumn("client_types", "title" . $language, "varchar", 255);
                $this->createcolumn("contents", "title" . $language, "varchar", 255);
                $this->createcolumn("contents", "desc" . $language, "varchar", 10000);
                $this->createcolumn("order_products", "title" . $language, "varchar", 255);
                $this->createcolumn("product_types", "Name" . $language, "varchar", 255);
                $this->createcolumn("product_types", "Description" . $language, "varchar", 255);
                $this->createcolumn("profile_types", "title" . $language, "varchar", 255);
                $this->createcolumn("settings", "client" . $language, "varchar", 255);
                $this->createcolumn("settings", "document" . $language, "varchar", 255);
                $this->createcolumn("settings", "profile" . $language, "varchar", 255);
                $this->createcolumn("subdocuments", "title" . $language, "varchar", 255);
                echo $language .  " was created";
            }
        }

        function deleteemail($key){
            $table = TableRegistry::get('strings');
            $table->deleteAll(array('Name' => "email_" . $key . "_subject"), false);
            $table->deleteAll(array('Name' => "email_" . $key . "_message"), false);
            $this->Flash->success("'" . $key . "' was deleted");
        }

        function saveemail($Data){
            $table = TableRegistry::get('strings');
            $string =  $table->find()->where(['Name'=> "email_" . $Data["key"] . "_subject"])->first();
            if (isset($Data["subject"])) {$Subject = $Data["subject"];}
            if (isset($Data["message"])) {$Message = $Data["message"];}
            if($string){//update
                $table->query()->update()->set($Subject)->where(['Name'=> "email_" . $Data["key"] . "_subject"])->execute();
                $table->query()->update()->set($Message)->where(['Name'=> "email_" . $Data["key"] . "_message"])->execute();
            } else {
                $Subject["Name"] = "email_" . $Data["key"] . "_subject";
                $Message["Name"] = "email_" . $Data["key"] . "_message";
                $table->query()->insert(array_keys($Subject))->values($Subject)->execute();
                $table->query()->insert(array_keys($Message))->values($Message)->execute();
            }
            echo $Data["key"] . " has been saved";
        }

        function createcolumn($Table, $Column, $Type, $Length="", $Default ="", $AutoIncrement=false, $Null = false){
            echo $this->Manager->create_column($Table, $Column, $Type, $Length, $Default, $AutoIncrement, $Null);
        }

        function deletecolumn($Table, $Column){
            $this->Manager->delete_column($Table, $Column);
        }

        public function clear_cache() {
            $this->Manager->clear_cache();
        }

        function enabledisableproduct($ID, $Value){
            $table = TableRegistry::get('order_products');
            $table->query()->update()->set(['enable' => $Value])->where(['number' => $ID])->execute();
        }

        function getenabledprovinces($ProductID, $Province = "ALL"){
            $forms = array();
            $items = TableRegistry::get('order_provinces')->find("all")->where(['ProductID' => $ProductID, "Province" => $Province]);
            foreach($items as $item){
                $forms[] = $item->ProductID;
            }
            return implode(",", $forms);
        }

        function isproductprovinceenabled($ProductID, $DocumentID, $Province){
            $item = TableRegistry::get('order_provinces')->find()->where(['ProductID' => $ProductID, 'FormID' => $DocumentID, "Province" => $Province])->first();
            if ($item) {
                return true;
            } else {
                return false;
            }
        }

        function setproductprovince($ProductID, $DocumentID, $Province, $Value){
            $table = TableRegistry::get('order_provinces');//ProductID, Province, FormID
            if ($Value == 1) {
                $color = "green";
                $item = $table->find()->where(['ProductID' => $ProductID, 'FormID' => $DocumentID, "Province" => $Province])->first();
                $message = " was already enabled for ";
                if (!$item) {
                    $table->query()->insert(['ProductID', "FormID", "Province"])->values(['ProductID' => $ProductID, 'FormID' => $DocumentID, "Province" => $Province])->execute();
                    $message = " was enabled for ";
                }
            } else {
                $color = "red";
                $table->deleteAll(array('ProductID' => $ProductID, 'FormID' => $DocumentID, "Province" => $Province), false);
                $message = " was disabled for ";
            }
            return "<FONT COLOR='" . $color . "'>" . $DocumentID . $message . $ProductID . "." . $Province . "</FONT>";
        }

        function generateproductHTML($Product, $Language){
            //TableRegistry::get('order_provinces')->find()->where(['ProductID' => $Product])->all();
            $Fieldname = "title";
            if($Language!="English"){$Fieldname.=$Language;}
            $provincelist = $this->enumProvinces();
            $subdocuments = TableRegistry::get('subdocuments')->find('all');//subdocument type list (id, title, display, form, table_name, orders, color_id)
            echo '<TABLE CLASS="table table-condensed table-striped table-bordered table-hover dataTable no-footer">';
            echo '<thead><TR><TH WIDTH="1%">ID</TH><TH>Document</TH>';
            foreach ($provincelist as $acronym => $fullname) {
                echo '<th width="1%" TITLE="' . $fullname . '">' . $acronym . '</th>';
            }
            echo '</TR></thead>';
            $this->generateRowHTML(0, "All documents", $Product, $provincelist);
            foreach ($subdocuments as $doc) {
                $this->generateRowHTML($doc->id, $this->getDefault($doc->title, $doc->$Fieldname), $Product, $provincelist);
            }
            echo '</TABLE>';
        }

        function getDefault($Default, $Value){
            if($Value){return $Value;}
            return "[" . $Default . "]";
        }

        function generateRowHTML($ID, $Title, $Product, $provincelist){
            echo '<TR><TD>' . $ID . '</TD><TD><DIV ID="dn' . $ID . '">' . $this->ucfirst2($Title) . '</DIV></TD>';
            foreach ($provincelist as $acronym => $fullname) {
                if ($this->isproductprovinceenabled($Product, $ID, $acronym)) {
                    $checked = " CHECKED";
                } else {
                    $checked = "";
                }//$ProductID, $DocumentID, $Province
                /// $checkID = 'chk' . $ID . "." . $acronym;//ONCLICK="simulateClick(' . "'" . $checkID . "'" . ');"
                echo '<TD TITLE="' . $fullname . '"><INPUT TYPE="CHECKBOX" ONCLICK="setprov(' . $ID . ", '" . $acronym . "'" . ');" ID="' . $ID . "." . $acronym . '"' . $checked . ' STYLE="width:100%;height:100%;"></TD>';
            }
            echo "</TR>";
        }

        function enumProvinces(){
            return array("ALL" => "All Provinces", "AB" => "Alberta", "BC" => "British Columbia", "MB" => "Manitoba", "NB" => "New Brunswick", "NL" => "Newfoundland and Labrador", "NT" => "Northwest Territories", "NS" => "Nova Scotia", "NU" => "Nunavut", "ON" => "Ontario", "PE" => "Prince Edward Island", "QC" => "Quebec", "SK" => "Saskatchewan", "YT" => "Yukon Territories");
        }

        function ucfirst2($Text){
            $Words = explode(" ", $Text);
            $Words2 = array();//php forces me to make a copy
            foreach ($Words as $Word) {
                $Words2[] = ucfirst($Word);
            }
            return implode(" ", $Words2);
        }

        function ClearProduct($Number){
            TableRegistry::get("order_provinces")->deleteAll(array('ProductID' => $Number), false);
        }

        function RenameProduct($Number, $NewName, $Language){
            $Fieldname = "title";
            if ($Language!="English"){$Fieldname.=$Language;}
            TableRegistry::get("order_products")->query()->update()->set([$Fieldname => $NewName])->where(['number' => $Number])->execute();
        }

        function DeleteProduct($Number){
            $this->ClearProduct($Number);
            TableRegistry::get("order_products")->deleteAll(array('number' => $Number), false);
            //TableRegistry::get("order_provinces")->deleteAll(array('ProductID' => $Number), false);
        }

        function startsWith($haystack, $needle) {
            // search backwards starting from haystack length characters from the end
            return $this->Manager->left($haystack, strlen($needle)) == $needle;
        }
        function AddProduct($Number, $post){//$Name, $FrenchName){
            $table = TableRegistry::get("order_products");
            $item = $table->find()->where(['number' => $Number])->first();
            if ($item) {return false;}
            $data = array("number" => $Number, "enable" => 0);
            foreach($post as $Key => $Value){
                if($this->startsWith($Key, "Name")){
                    $data[str_replace("Name", "title", $Key)] = $Value;
                }
            }
            $table->query()->insert(array_keys($data))->values($data)->execute();
            //$table->query()->insert(['number', "title", "titleFrench", "enable"])->values(['number' => $Number, 'title' => $Name, "titleFrench"=> $FrenchName, "enable" => 0])->execute();
            return true;
        }

        public function index(){
            $u = $this->request->session()->read('Profile.id');
            $condition = TableRegistry::get("profiles")->find()->where(['id' => $u])->first()->ptypes;
            $this->set('cancreate', explode(",", $condition) ) ;

            $this->loadModel('ProfileTypes');
            $this->set('ptypes', $this->ProfileTypes->find()->where(array("OR" => ['enable' => '1', 'id' => 0])));
            //$this->set('ptypes', $this->ProfileTypes->find()->where(['enable' => '1']));
            $this->set('doc_comp', $this->Document);

            $setting = $this->Settings->get_permission($u);
            $this->set('ProClients', $this->Settings);
            $super = $this->request->session()->read('Profile.super');
            $condition = $this->Settings->getprofilebyclient($u, $super);
            //var_dump($condition);die();
            if ($setting->profile_list == 0) {
                $this->Flash->error($this->Trans->getpermissions("005", "profile_list"));
                return $this->redirect("/");
            }
            if (isset($_GET['draft'])) {
                $draft = 1;
            } else {
                $draft = 0;
            }
            $cond = '';
            $cond = 'drafts = ' . $draft;
            if (isset($_GET['searchprofile'])) {
                $search = $_GET['searchprofile'];
                $searchs = strtolower($search);
            }

            if (isset($_GET['filter_profile_type'])&& $_GET['filter_profile_type']!="") {
                $profile_type = $_GET['filter_profile_type'];
            }
            if (isset($_GET['filter_by_client'])) {
                $client = $_GET['filter_by_client'];
            }
            $querys = TableRegistry::get('Profiles');

            if (isset($_GET['searchprofile']) && $_GET['searchprofile']) {
                if ($cond){ $cond.= ' AND'; }
                $cond .= ' (LOWER(title) LIKE "%' . $searchs . '%" OR LOWER(fname) LIKE "%' . $searchs . '%" OR LOWER(lname) LIKE "%' . $searchs . '%" OR LOWER(username) LIKE "%' . $searchs . '%" OR LOWER(address) LIKE "%' . $searchs . '%")';
            }

            if (isset($_GET['filter_profile_type']) && $_GET['filter_profile_type'] > -1 && $_GET['filter_profile_type']!='') {
                if ($cond){ $cond.= ' AND'; }
                if($_GET['filter_profile_type'] == "NULL") {
                    $cond .= ' profile_type IS NULL';
                }else {
                    $cond .= ' profile_type = ' . $profile_type;
                }
            }

            if(isset($_GET["sitename"]) && $_GET["sitename"]){
                if($cond){$cond .= ' AND ';}
                $cond .= 'sitename = "' . $_GET["sitename"] . '"';
            }
            if(isset($_GET["asapdivision"]) && $_GET["asapdivision"]){
                if($cond){$cond .= ' AND ';}
                $cond .= 'asapdivision = "' . $_GET["asapdivision"] . '"';
            }

            if (isset($_GET['filter_by_client']) && $_GET['filter_by_client']) {
                if($_GET['filter_by_client'] == -1){
                    if ($cond) {$cond .= ' AND';}
                    $cond .= ' is_hired = 0';
                } else {
                    $sub = TableRegistry::get('Clients');
                    $que = $sub->find();
                    $que->select()->where(['id' => $_GET['filter_by_client']]);
                    $q = $que->first();
                    $profile_ids = $q->profile_id;
                    if ($profile_ids) {
                        if ($cond) {$cond .= ' AND';}
                        $cond .= ' (id IN (' . $profile_ids . '))';
                    }
                }
            }

            if ($this->request->session()->read('Profile.profile_type') == '2') {
                if ($cond) {
                    //$cond = $cond . ' AND (created_by = ' . $this->request->session()->read('Profile.id') . ')';
                } else {
                    $condition['created_by'] = $this->request->session()->read('Profile.id');
                }

            }

            if(!$this->request->session()->read('Profile.super')) {
                if ($cond) {$cond .= ' AND ';}
                $Me = $this->Manager->get_profile($u)->ptypes;
                if($Me) {
                    $cond .= 'profile_type IN (' . $Me . ')';
                } else {
                    $cond .= "1 = 0";
                }
                $cond .= " AND super = 0";
            }

            /*=================================================================================================== */
            if(true){
           // if($setting->viewprofiles == 0){
                $Clients = TableRegistry::get('Clients')->find()->select()->where([true]);
                $OR = array();
                $IsSuper = $this->request->session()->read('Profile.super');
                if(!$IsSuper) {
                    foreach ($Clients as $Client) {
                        if ($Client->profile_id) {
                            $Profiles = explode(",", $Client->profile_id);
                            if (in_array($u, $Profiles)) {
                                $OR = array_merge($OR, $Profiles);
                            }
                        }
                    }
                    $OR = implode(",", array_unique($OR));
                    if ($OR) {
                        if ($cond) {$cond .= ' AND ';}
                        $cond .= "id IN (" . $OR . ")";
                    }
                }
                $query = $this->Profiles->find()->where($cond);
            } elseif ($cond) {
                $query = $querys->find()->where([$cond, 'OR' => $condition, 'AND' => 'super = 0']);
            } else {
                $query = $this->Profiles->find()->where(['OR' => $condition, 'AND' => 'super = 0']);
            }
            //debug($query);die();
            if (isset($search)) {
                $this->set('search_text', $search);
            }
            if (isset($profile_type)&& $profile_type!="") {
                $this->set('return_profile_type', $profile_type);
            }
            if (isset($client)) {
                $this->set('return_client', $client);
            }

            if($query) {
                if (isset($_GET["all"])) {
                    $this->set('profiles', $this->appendattachments($query));
                } else {
                    $this->set('profiles', $this->appendattachments($this->paginate($query)));
                }
            }

            if (!$this->request->session()->read('Profile.super')) {
                $table = TableRegistry::get("clients");
                $results = $table->find('all', array('conditions' => array('id'=>26)))->first();
                if($results){$results=$results->profile_id;}
                $this->set('assignedtoGFS', $results);
            }

            $this->Manager->permissions(array("sidebar" => array("profile_list", "profile_edit", "profile_delete", "profile_create", "bulk", "document_list", "orders_list")), $setting, false, $u);// "client_option", I don't know what this is used for
            if ($this->Manager->get_settings()->mee == "ASAP Secured Training") {
                $this->set("sitenames", $this->getdistinctfields("profiles", "sitename"));
                $this->set("asapdivisions", $this->getdistinctfields("profiles", "asapdivision"));
            }
        }


        function removefiles($file)
        {
            if (isset($_POST['id']) && $_POST['id'] != 0) {
                $this->loadModel("ProfileDocs");
                $this->ProfileDocs->deleteAll(['id' => $_POST['id']]);
            }
            @unlink(WWW_ROOT . "img/jobs/" . $file);
            die();
        }

        public function view($id = null){
            if (isset($_GET['success'])) {
                $this->Flash->success($this->Trans->getString("flash_ordersaved"));
            }
            if($id>0) {
                $sidebar = $this->getsidebar("Sidebar", $id);//$sidebar->viewprofiles
                $userid=$this->request->session()->read('Profile.id');
                $this->set('products', TableRegistry::get('product_types')->find('all'));
                $this->getsubdocument_topblocks($id, false);
                $this->loadModel("ProfileTypes");
                $this->set("ptypes", $this->ProfileTypes->find()->where(['enable' => '1'])->all());
                $this->set('uid', $id);
                $this->set('doc_comp', $this->Document);
                $setting = $this->Settings->get_permission($userid);
/*
                if ($setting->profile_list == 0 || ($userid != $id && $setting->viewprofiles ==0)) {
                    $ClientID = $this->Manager->find_client($id, false);
                    $visibleprofiles = false;
                    if($ClientID) {
                        if (!is_array($ClientID)) {$ClientID = array($ClientID);}
                        foreach($ClientID as $Client) {
                            $Client = $this->Manager->get_client($Client);
                            if($Client->visibleprofiles){$visibleprofiles=true;}
                        }
                    }
                    $place = "View: ";
                    if($setting->profile_list == 0) { $place .= "profile_list";}
                    if($userid != $id && $setting->viewprofiles ==0) { $place .= "viewprofiles";}

                    if(!$visibleprofiles) {
                        //$this->Flash->error($this->Trans->getString("flash_permissions", array("place" => $place)) . ' (006)');
                     //   return $this->redirect("/");
                    }
                }
*/
                $docs = TableRegistry::get('profile_docs');
                $query = $docs->find();
                $client_docs = $query->select()->where(['profile_id' => $id])->all();
                $this->set('client_docs', $client_docs);
                $this->loadModel('Logos');

                $this->set('logos', $this->paginate($this->Logos->find()->where(['secondary' => '0'])));
                $this->set('logos1', $this->paginate($this->Logos->find()->where(['secondary' => '1'])));
                $profile = $this->Profiles->get($id, ['contain' => []]);

                $this->set('doc_comp', $this->Document);
                $orders = TableRegistry::get('orders');
                $Parameters = array('orders.uploaded_for' => $id, 'orders.draft' => 0);
                if(!$this->request->session()->read('Profile.super')){
                    $Clients = $this->Manager->find_client(false, false);
                    if(is_array($Clients)){$Clients = implode(",", $Clients);}
                    if($Clients) {
                        $Parameters[] = "orders.client_id IN (" . $Clients . ")";
                    } else {
                        $Parameters[] = "1=2";
                    }
                }
                $order = $orders->find()->where($Parameters)->order('orders.id DESC')->contain(['Profiles', 'Clients', 'RoadTest']);


                $profile->Ptype = $this->getprofiletypeData($profile->profile_type);
                $this->set('orders', $order);
                $this->set('profile', $profile);
                $this->set('disabled', 1);
                $this->set('id', $id);
                $this->loadclients($profile->id);

                //if($profile->Ptype->placesorders && ($this->Manager->requiredfields($profile, "profile2order") || !$profile->iscomplete)) {
                    //$this->Flash->error($this->Trans->getString("flash_cantorder"));
                //}

                $order = TableRegistry::get('documents')->find()->where(['order_id' => 0, 'uploaded_for' => $id])->order('id DESC');
                $this->set('documents', $order);
                $order = TableRegistry::get('subdocuments')->find();
                $this->set('subdocuments', $order);
            }
            $this->render("edit");
        }

        function getprofiletypeData($ID){
            return TableRegistry::get('profile_types') ->find()->where(['id' => $ID])->first();
        }


        public function viewReport($profile, $profile_edit_view = 0) {
            $this->set('doc_comp', $this->Document);
            $orders = TableRegistry::get('orders');
            $order = $orders
                ->find()
                ->where(['orders.uploaded_for' => $profile])->contain(['Profiles', 'Clients', 'RoadTest']);

            if (isset($profile_edit_view) && $profile_edit_view == 1) {
                $this->response->body(($order));
                return $this->response;
                die();
            } else {
                $this->set('orders', $order);
            }
        }

        public function editnote() {
            $userid = $this->request->session()->read('Profile.id');
            $profile = $this->Profiles->get($userid);
            $this->set('profile', $profile);
        }


        public function getsidebar($Set = "", $UserID =""){
            if(!$UserID) {$UserID = $this->request->session()->read('Profile.id');}
            $UserID = $this->Manager->loadpermissions($UserID, "sidebar");
            if($Set){$this->set($Set, $UserID);}
            return $UserID;
        }

        public function add() {//called when creating an account
            $this->getsidebar("Sidebar");

            $this->set('uid', '0');
            $this->set('id', '0');
            $this->loadModel("ProfileTypes");
            $this->set("ptypes", $this->ProfileTypes->find()->where(['enable' => '1'])->all());
            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));

            if ($setting->profile_create == 0 && !$this->request->session()->read('Profile.super')) {


                $this->Flash->error($this->Trans->getpermissions("004", "profile_create"));
                //$this->Flash->error($this->Trans->getString("flash_permissions", array("place" => "add")) . ' (004)');
                return $this->redirect("/");
            }
            $this->loadModel('Logos');

            $this->set('logos', $this->paginate($this->Logos->find()->where(['secondary' => '0'])));
            $this->set('logos1', $this->paginate($this->Logos->find()->where(['secondary' => '1'])));
            $this->set('logos2', $this->paginate($this->Logos->find()->where(['secondary' => '2'])));
            $profiles = TableRegistry::get('Profiles');

            $_POST['created'] = date('Y-m-d');
            if (isset($_POST['password'])) { $_POST['password'] = md5($_POST['password']);}

            if ($this->request->is('post')) {
                if (isset($_POST['profile_type']) && $_POST['profile_type'] == 1) {
                    $_POST['admin'] = 1;
                }
                $_POST['password'] = md5($_POST['pass_word']);
                unset($_POST['pass_word']);

                $_POST['dob'] = $_POST['doby'] . "-" . $_POST['dobm'] . "-" . $_POST['dobd'];
                //debug($_POST);die();
                $profile = $profiles->newEntity($_POST);
                if ($profiles->save($profile)) {
                    //var_dump($_POST);
                    $this->checkusername($profile->id, $_POST);
                    if(isset($_POST['cids']) && $_POST['cids']) {
                        $_POST['client_idss'] = explode(',',$_POST['cids']);
                        $cquery = TableRegistry::get('Clients');
                        $cq = $cquery->find();
                        foreach($cq as $ccq) {
                            $this->addprofile(0,$ccq->id,$profile->id);
                        }
                        foreach($_POST['client_idss'] as $cid) {
                            $this->addprofile(1,$cid,$profile->id);
                        }
                    }
                    //die();

                    $this->Manager->makepermissions($profile->id, "blocks", $profile->profile_type);
                    $this->Manager->makepermissions($profile->id, "sidebar", $profile->profile_type);
                    $Master = $this->Manager->getmaster($profile->id, $profile->profile_type);
                    if($Master <> $profile->id){
                        $this->copysubdocs($Master, $profile->id);
                    }

                    if(isset($_POST["ClientID"]) && $_POST["ClientID"]) {
                        $this->Manager->assign_profile_to_client($profile->id, $_POST["ClientID"]);
                        //$this->notify($profile->id, "email_profile");
                    }
                    $this->Flash->success($this->Trans->getString("flash_profilecreated"));
                    return $this->redirect(['action' => 'edit', $profile->id]);
                } else {
                    $this->Flash->error($this->Trans->getString("flash_profilenotcreated"));
                }
            }
            $this->set(compact('profile'));

            if ($this->Manager->get_settings()->mee == "ASAP Secured Training") {
                $this->set("sitenames", $this->getdistinctfields("profiles", "sitename"));
                $this->set("asapdivisions", $this->getdistinctfields("profiles", "asapdivision"));
            }

            $this->render("edit");
        }

        function copysubdocs($From, $To){
            $SubDocs = $this->Manager->enum_all("profilessubdocument", array("profile_id" => $From));
            foreach($SubDocs as $SubDoc){
                $this->Manager->new_entry("profilessubdocument", "id", array("profile_id" => $To, "subdoc_id" => $SubDoc->subdoc_id, "display" => $SubDoc->display, "Topblock"  => $SubDoc->display));
            }
        }

        function checkusername($profile, $post){//updates username of $profile->id
            $username = trim($post['username']);
            if(!$username && is_object($profile)) {
                $username = TableRegistry::get('profile_types')->find()->where(['id' => $profile->profile_type])->first();
                if($username) {
                    $username = str_replace(" ", "_", $username->title . "_" . $profile->id);

                    $queries = TableRegistry::get('Profiles');
                    $queries->query()->update()->set(['username' => $username])
                        ->where(['id' => $profile->id])
                        ->execute();
                }
            }
        }

        public function getprofileByAnyKey($Key, $Value){
            $table = TableRegistry::get("profiles");
            $results = $table->find('all', array('conditions' => array($Key => $Value)))->first();
            return $results;
        }
        public function Update1Column($Table, $PrimaryKey, $PrimaryValue, $Key, $Value){
            TableRegistry::get($Table)->query()->update()->set([$Key => $Value])->where([$PrimaryKey=>$PrimaryValue])->execute();
        }

        function getmiddlename($array){
            unset($array[ count($array) -1 ]);
            unset($array[0]);
            return trim(implode(" ", $array));
        }

        function csv() {
            $profile = TableRegistry::get('profiles');
            $arr = explode('.', $_FILES['csv']['name']);
            $ext = end($arr);

            $allowed = array('csv');
            $check = strtolower($ext);
            if (in_array($check, $allowed)) {
                $Filename = $_FILES['csv']['tmp_name'];
                if ($_FILES['csv']['size'] > 0) {
                    $handle = fopen($Filename, "r");

                    $file = fopen($Filename,"r");
                    $Contents = fread($file,$_FILES['csv']['size']);
                    fclose($file);
                    if (strpos($Contents, ",") !== false){
                        $Delimeter = ",";
                    } else if (strpos($Contents, "\t") !== false){
                        $Delimeter = "\t";
                    }
                    $i=0;
                    $flash ="";
                    $line = 0;
                    while (($data = fgetcsv($handle, 1000, $Delimeter)) !== FALSE) {
                        if($i==0) {
                            if($data[0] == "DEFAULT"){
                                $Columns = explode(",", "profile_type,driver,username,title,fname,mname,lname,phone,gender,placeofbirth,dob,street,city,province,postal,country,driver_license_no,driver_province,expiry_date,email,CLIENTID,hired_date,sin");
                            } else {
                                $Columns = $data;
                            }
                            /*
                                $Columns = $this->Manager->getColumnNames("profiles");
                                $Columns["FULLNAME"];
                                $Columns["CLIENTID"];
                            */
                        } else {
                            $line++;
                            $DOIT=count($data);
                            if($DOIT) {
                                foreach ($data as $KEY => $VALUE) {//clean all values
                                    $data[$KEY] = trim(ucfirst(addslashes($VALUE)));
                                }
                                $pro = array_combine($Columns, $data);
                                $ClientID = 0;
                                if (isset($pro["CLIENTID"])) {
                                    $ClientID = $pro["CLIENTID"];
                                    unset($pro["CLIENTID"]);
                                }
                                $pro["country"] = "Canada";
                                if (isset($pro["title"])) {
                                    if (strpos($pro["title"], ".") === false) {
                                        $pro["title"] .= ".";
                                    }
                                }
                                if (isset($pro["province"])) {
                                    $pro["province"] = strtoupper($pro["province"]);
                                }
                                foreach (array("dob", "expiry_date", "hired_date") as $KEY) {
                                    if (isset($pro[$KEY])) {
                                        $pro[$KEY] = date('Y-m-d', strtotime($pro[$KEY]));
                                    }
                                }
                                if (isset($pro["FULLNAME"])) {
                                    $pro["FULLNAME"] = explode(" ", $pro["FULLNAME"]);
                                    $pro["fname"] = $pro["FULLNAME"][0];
                                    $pro["lname"] = $pro["FULLNAME"][count($pro["FULLNAME"]) - 1];
                                    if (count($pro["FULLNAME"]) > 2) {
                                        $pro["mname"] = $this->getmiddlename($pro["FULLNAME"]);
                                    }
                                    unset($pro["FULLNAME"]);
                                }
                                foreach (array("fname", "mname", "lname") as $KEY) {
                                    if (isset($pro[$KEY])) {
                                        $pro[$KEY] = ucfirst( strtolower($pro[$KEY]));
                                    }
                                }

                                if (isset($pro["email"]) && $pro["email"]) {
                                    $em = $this->check_email('', $pro["email"]);
                                    if ($em == 1) {
                                        $flash .= "Failed: Email '" . $pro["email"] . "' already exists(Line no " . $line . ")<BR>";
                                        $DOIT = false;
                                    }
                                }
                                if (isset($pro["username"]) && $pro["username"]) {
                                    $em = $this->check_user('', $pro["username"]);
                                    if ($em == 1) {
                                        $flash .= "Failed: Username '" . $pro["username"] . "' already exists(Line no " . $line . ")<BR>";
                                        $DOIT = false;
                                    }
                                }
                                if(isset($pro["driver_license_no"]) && $pro["driver_license_no"]){
                                    $em = $this->Manager->get_entry("profiles", $pro["driver_license_no"],  "driver_license_no");
                                    if($em){
                                        $flash .= "Failed: Driver's license # '" . $pro["driver_license_no"] . "' already exists(Line no " . $line . ")<BR>";
                                        $DOIT = false;
                                    }
                                }
                                if(!isset($pro["profile_type"])){
                                    $pro["profile_type"] = 5;//driver
                                }
                                $pro["import_type"] = 1;

                                $pro = $this->Manager->remove_empties($pro);
                                if ($DOIT) {
                                    $pros = $profile->newEntity($pro);
                                    if ($profile->save($pros)) {
                                        $flash .= "Success (Line no " . $line . "), ";
                                        if ($ClientID) {
                                            $this->Manager->assign_profile_to_client($pros->id, $ClientID);
                                        }
                                        $this->Manager->makepermissions($pros->id, "blocks", $pros->profile_type);
                                        $this->Manager->makepermissions($pros->id, "sidebar", $pros->profile_type);
                                    }
                                }
                            }
                        }
                        $i++;

                    }
                    fclose($handle);

                }


                $this->Flash->success($this->Trans->getString("flash_profilesimported") . ' ' . $flash);
                $this->redirect('/profiles');
            } else {
                $this->Flash->error($this->Trans->getString("flash_invalidcsv"));
                $this->redirect('/profiles/settings');
            }
        }

        public function getfirstrow($Table, $PrimaryKey, $Value){
            return TableRegistry::get($Table)->find('all', array('conditions' => array($PrimaryKey=>$Value)))->first();
        }

        function refreshsession(){
            $userid = $this->request->session()->read('Profile.id');
            $q = $this->getfirstrow("profiles", "id", $userid);
            //$this->request->session()->write('Profile.id',$q->id);
            $this->request->session()->write('Profile.username',$q->username);
            $this->request->session()->write('Profile.fname',$q->fname);
            $this->request->session()->write('Profile.lname',$q->lname);
            $this->request->session()->write('Profile.isb_id',$q->isb_id);
            $this->request->session()->write('Profile.mname',$q->mname);
            $this->request->session()->write('Profile.profile_type',$q->profile_type);
            $this->request->session()->write('Profile.language', $q->language);
            $this->request->session()->write('Profile.email',$q->email);
        }

        function updatelanguage($post){
            $language = "English";
            if(isset($post["language"])){
                $language=ucfirst($post["language"]);
                $this->request->session()->write("Profile.language", $language);
            }
            return $language;
        }

        function saveprofile($add = "") {
            $settings = $this->Settings->get_settings();
            $profiles = TableRegistry::get('Profiles');
            $path = $this->Document->getUrl();
            $delimeter = "_";

            $this->updatelanguage($_POST);

            //$this->Flash->success("Add: " . $add);
            if ($add == '0') {
                $Event = "profilecreated";
                $profile_type = $this->request->session()->read('Profile.profile_type');
                $_POST['created'] = date('Y-m-d');
                $username = $_POST["username"];
                if (isset($_POST['pass_word']) && $_POST['pass_word'] == '') {
                    $password = '';
                    unset($_POST['pass_word']);
                } else {
                    if (isset($_POST['pass_word']) && $_POST['pass_word'] != '') {
                        $password = $_POST['pass_word'];
                        $_POST['password'] = md5($_POST['pass_word']);
                    }
                }

                if ($this->request->is('post')) {
                    if (isset($_POST['profile_type']) && $_POST['profile_type'] == 1) {
                        $_POST['admin'] = 1;
                    }
                    $_POST['dob'] = $_POST['doby'] . "-" . $_POST['dobm'] . "-" . $_POST['dobd'];
                    if(isset($_POST['expiry_date']) && $_POST['expiry_date']!= '') {
                        $_POST['expiry_date'] = date('Y-m-d', strtotime($_POST['expiry_date']));
                    }
                    $profile = $profiles->newEntity($_POST);
                    if ($profiles->save($profile)) {
                        if(isset($_POST['cids']) && $_POST['cids']) {
                            $_POST['client_idss'] = explode(',',$_POST['cids']);
                            $cquery = TableRegistry::get('Clients');
                            $cq = $cquery->find();
                            foreach($cq as $ccq) {
                                $this->addprofile(0,$ccq->id,$profile->id);
                            }
                            foreach($_POST['client_idss'] as $cid) {
                                $this->addprofile(1,$cid,$profile->id);
                            }
                            
                        }
                        $this->checkusername($profile,$_POST);
                        $this->loadModel('ProfileDocs');
                        $this->ProfileDocs->deleteAll(['profile_id' => $profile->id]);
                        if (isset($_POST['profile_doc'])) {
                            $profile_docs = array_unique($_POST['profile_doc']);
                            foreach ($profile_docs as $d) {
                                if ($d != "") {
                                    $docs = TableRegistry::get('profile_docs');
                                    $ds['profile_id'] = $profile->id;
                                    $ds['file'] = $d;
                                    $doc = $docs->newEntity($ds);
                                    $docs->save($doc);
                                    unset($doc);
                                }
                            }
                        }

                        if(!$username && $_POST['profile_type']) {
                            $profiletypes = $this->Manager->enum_profile_types();
                            $profiletype = $this->Manager->getIterator($profiletypes, "id", $_POST['profile_type'])->title;
                            $profiletype = str_replace(" ", $delimeter, $profiletype);

                            $queries = TableRegistry::get('Profiles');
                            $username = $profiletype . $delimeter . $profile->id;
                            $queries->query()->update()->set(['username' => $username])
                                ->where(['id' => $profile->id])
                                ->execute();
                        }


                        if ($profile_type == 2) {
                            //save profiles to clients if recruiter
                            $clients_id = $this->Settings->getAllClientsId($this->request->session()->read('Profile.id'));
                            if ($clients_id != "") {
                                $client_id = explode(",", $clients_id);
                                foreach ($client_id as $cid) {
                                    $this->Manager->assign_profile_to_client($profile->id, $cid);
                                }
                            }
                        }

                        if ($_POST['client_ids']) {
                            $client_id = explode(",", $_POST['client_ids']);
                            foreach ($client_id as $cid) {
                                $this->Manager->assign_profile_to_client($profile->id, $cid);
                            }
                        }

                        $this->Manager->makepermissions($profile->id, "blocks", $profile->profile_type);
                        $this->Manager->makepermissions($profile->id, "sidebar", $profile->profile_type);

                        if (isset($_POST['drafts']) && ($_POST['drafts'] == '1')) {
                            $this->Flash->success($this->Trans->getString("flash_profilesaveddraft"));
                        } else {
                            $pro_query = TableRegistry::get('Profiles');
                            $email_query = $pro_query->find()->where(['super' => 1])->first();
                            $em = $email_query->email;
                            $user_id = $this->request->session()->read('Profile.id');
                            $uq = $pro_query->find('all')->where(['id' => $user_id])->first();
                            if ($uq->profile_type) {
                                $u = $uq->profile_type;
                                $type_query = TableRegistry::get('profile_types');
                                $type_q = $type_query->find()->where(['id' => $u])->first();
                                if ($type_q) {
                                    $ut = $type_q->title;
                                }else {
                                    $ut = '';
                                }
                            } else
                                $ut = '';
                            if ($_POST['profile_type']) {
                                $pt = $_POST['profile_type'];
                                $u = $pt;
                                $type_query = TableRegistry::get('profile_types');
                                $type_q = $type_query->find()->where(['id' => $u])->first();
                                if ($type_q) {
                                    $protype = $type_q->title;
                                }else {
                                    $protype = '';
                                }
                            } else {
                                $protype = '';
                            }


                            //$this->Mailer->debugprint(print_r($_POST,true));


                            $emails = array();
                            if (isset($_POST['client_idss']) && $_POST['client_idss']) {
                                $client_id = $_POST['client_idss'];
                                $emails = $this->Document->enum_profiles_permission($client_id, "email_profile", "email");
                                $emails = $this->Manager->remove_empties($emails);
                            }
                            $emails[] = "super";
                            if (isset($_POST["emailcreds"]) && $_POST["emailcreds"] && strlen(trim($_POST["email"])) > 0) {
                                $emails[] = $_POST["email"];
                                $profiles->query()->update()->set(['emailsent' => date('Y-m-d H:i:s')])->where(['id' => $profile->id])->execute();
                            }
                            $this->Mailer->handleevent("profilecreated", array("username" => $username,"email" => $emails, "path" =>$path, "createdby" => $uq->username, "type" => $protype, "password" => $password, "id" =>  $profile->id ));

                            $this->Flash->success($this->Trans->getString("flash_profilesaved"));
                        }
                        echo $profile->id;

                    } else {
                        echo "0";
                    }
                }
            } else {
                $Event = "profileedited";
                $profile = $this->Profiles->get($add, ['contain' => []]);
                if ($this->request->is(['patch', 'post', 'put'])) {
                    if (isset($_POST['pass_word']) && $_POST['pass_word'] == '') {
                        $this->request->data['password'] = $profile->password;
                        $Password = "[ENCRYPTED]";
                    } else {
                        if (isset($_POST['pass_word'])) {
                            $Password = $_POST['pass_word'];
                            $this->request->data['password'] = md5($_POST['pass_word']);
                        }
                    }
                    if (isset($_POST['profile_type']) && $_POST['profile_type'] == 1) {
                        $this->request->data['admin'] = 1;
                    } else {
                        $this->request->data['admin'] = 0;
                    }
                    $this->request->data['dob'] = $_POST['doby'] . "-" . $_POST['dobm'] . "-" . $_POST['dobd'];
                    $username= "";
                    if (isset($this->request->data['username'])){
                        $username= $this->request->data['username'];
                        if ($this->request->data['username'] == 5) {
                            unset($this->request->data['username']);
                        }
                    }

                    //var_dump($this->request->data); die();//echo $_POST['admin'];die();
                    $profile = $this->Profiles->patchEntity($profile, $this->request->data);
                    if ($this->Profiles->save($profile)) {
                        if(isset($_POST['cids']) && $_POST['cids']) {
                            $_POST['client_idss'] = explode(',',$_POST['cids']);
                            $cquery = TableRegistry::get('Clients');
                            $cq = $cquery->find();
                            foreach($cq as $ccq) {
                                $this->addprofile(0,$ccq->id,$profile->id);
                            }
                            foreach($_POST['client_idss'] as $cid) {
                                $this->addprofile(1,$cid,$profile->id);
                            }
                        }
                        $this->loadModel('ProfileDocs');
                        $this->ProfileDocs->deleteAll(['profile_id' => $profile->id]);
                        if (isset($_POST['profile_doc'])) {
                            $profile_docs = array_unique($_POST['profile_doc']);
                            foreach ($profile_docs as $d) {
                                if ($d != "") {
                                    $docs = TableRegistry::get('profile_docs');
                                    $ds['profile_id'] = $profile->id;
                                    $ds['file'] = $d;
                                    $doc = $docs->newEntity($ds);
                                    $docs->save($doc);
                                    unset($doc);
                                }
                            }
                        }
                        echo $profile->id;

                        if(!$username && $_POST['profile_type']) {
                            $profiletypes = $this->Manager->enum_profile_types();
                            $profiletype = $this->Manager->getIterator($profiletypes, "id", $_POST['profile_type'])->title;
                            $profiletype = str_replace(" ", $delimeter, $profiletype);

                            $queries = TableRegistry::get('Profiles');
                            $username = $profiletype . $delimeter . $profile->id;
                            $queries->query()->update()->set(['username' => $username])
                                ->where(['id' => $profile->id])
                                ->execute();
                        }

                        if (isset($_POST['drafts']) && ($_POST['drafts'] == '1')) {
                            $this->Flash->success($this->Trans->getString("flash_profilesaveddraft"));
                        } else {
                            $this->Flash->success($this->Trans->getString("flash_profilesaved"));
                        }
                    } else {
                        echo "0";
                    }
                }
            }


            if(isset($profile->id) && $add == '0') {
                $this->notify($profile->id, "email_profile");
            }
            $this->refreshsession();
            die();
        }

        public function saveDriver() {
            //echo $client_id = $_POST['cid'];die() ;
            $arr_post = explode('&', $_POST['inputs']);
            //var_dump($arr_post);die();
            foreach ($arr_post as $ap) {
                $arr_ap = explode('=', $ap);
                if (isset($arr_ap[1])) {
                    $post[$arr_ap[0]] = urldecode($arr_ap[1]);
                    $post[$arr_ap[0]] = str_replace('Select Gender', '', urldecode($arr_ap[1]));
                }
            }
            //var_dump($post);die();
            $que = $this->Profiles->find()->where(['email' => $post['email'], 'id <> ' => $post['id']])->first();
            if ($que) {
                $Profile = $this->Profiles->find()->where(['id ' => $post['id']])->first();
                if ($Profile->email != $post['email']) {//some have the same email for testing
                    //echo count($que);
                    echo 'exist';
                    die();
                }
            }
            //$post = $_POST['inputs'];
            // var_dump($post);die();
            $profiles = TableRegistry::get('Profiles');

            if ($this->request->is('post')) {

                //var_dump($_POST['inputs']);die();
                $post['dob'] = $post['doby'] . "-" . $post['dobm'] . "-" . $post['dobd'];
                //debug($_POST);die();
                if ($post['id'] == 0 || $post['id'] == '0') {
                    $post['created'] = date('Y - m - d');
                    unset($post['id']);
                    $profile = $profiles->newEntity($post);
                    if ($profiles->save($profile)) {
                        $this->checkusername( $profile->id, $post);

                        if ($post['client_ids'] != "") {
                            $client_id = explode(",", $post['client_ids']);
                            foreach ($client_id as $cid) {
                                $this->Manager->assign_profile_to_client($profile->id, $cid);
                            }
                        }
                        echo $profile->id;
                        die();

                    }
                } else {

                    //var_dump($post);
                    $id = $post['id'];
                    unset($post['id']);
                    unset($post['profile_type']);

                    $pro = $this->Profiles->get($id, [
                        'contain' => []
                    ]);
                    $pros = $this->Profiles->patchEntity($pro, $post);
                    $this->Profiles->save($pros);

                    echo $id;
                    die();

                }
            }
            die();
        }

        public function langswitch($id = null, $newlanguage = "") {
            $id = $this->request->session()->read('Profile.id');
            $language = $this->request->session()->read('Profile.language');
            $acceptablelanguages = $this->Settings->acceptablelanguages();
            if (!in_array($language, $acceptablelanguages)) {
                $language = $acceptablelanguages[0];
            }//default to english

            if(!$newlanguage && count($acceptablelanguages) == 2) {
                $index = array_search($language, $acceptablelanguages) + 1;
                if ($index >= count($acceptablelanguages)) {
                    $index = 0;
                }
                $newlanguage = $acceptablelanguages[$index];
            }

            if ($newlanguage) {
                $this->request->session()->write('Profile.language', $newlanguage);
                TableRegistry::get('profiles')->query()->update()->set(['language' => $newlanguage])->where(['id' => $id])->execute();
                $this->set("newlanguage", $newlanguage);
            }

            $this->set("id", $id);
            $this->set("language", $language);
            $this->set("languages", $acceptablelanguages);
        }

        public function edit($id = null) {
            if(isset($_POST['cids']) && $_POST['cids']) {
                    die('here');
                $_POST['client_idss'] = explode(',',$_POST['cids']);
                $cquery = TableRegistry::get('Clients');
                $cq = $cquery->find();
                foreach($cq as $ccq) {
                    $this->addprofile(0,$ccq->id,$id);
                }
                foreach($_POST['client_idss'] as $cid) {
                    $this->addprofile(1,$cid,$id);
                }
            }
        //called when editing an existing account
            $this->set('doc_comp', $this->Document);
            $check_pro_id = $this->Settings->check_pro_id($id);
            if ($check_pro_id == 1) {
                $this->Flash->error($this->Trans->getString("flash_profilenotfound"));
                return $this->redirect("/profiles/index");
            }

            $clientcount = $this->Settings->getClientCountByProfile($id);
            $this->set('Clientcount', $clientcount);
            if (isset($_GET['clientflash']) || $clientcount == 0) {
                $this->Flash->success($this->Trans->getString("flash_profilecreated"));
            }

            $userid=$this->request->session()->read('Profile.id');
            $pr = TableRegistry::get('profiles');
            $query = $pr->find();
            $aa = $query->select()->where(['id' => $id])->first();

            if($aa->password){
                if(!$this->Manager->isValidMd5($aa->password)){
                    $this->Manager->update_database("profiles", "id", $aa->id, array("password" => md5($aa->password)));
                }
            }

            if($aa->created_by=='' || $aa->created_by=='0') {
                $pr->query()->update()
                    ->set(['created_by'=>$this->request->session()->read('Profile.id')])
                    ->where(['id' => $id])
                    ->execute();
            }
            $checker = $this->Settings->check_edit_permission($userid, $id, $aa->created_by);

            $setting = $this->Settings->get_permission($userid);
            if (($setting->profile_edit == 0) && $id != $userid) {
                $this->Flash->error($this->Trans->getpermissions("004", array("profile_edit", "viewprofiles")));
                //$this->Flash->error($this->Trans->getString("flash_permissions", array("place" => "edit")) . ' (000)');
                return $this->redirect("/");
            } else {
                $this->set('myuser', '1');
            }

            $this->getsubdocument_topblocks($id, false);//subdocument_topblocks
            $this->loadModel("ProfileTypes");
            $this->set("ptypes", $this->ProfileTypes->find()->where(['enable' => '1'])->all());
            $this->loadModel("ClientTypes");
            $this->set('client_types', $this->ClientTypes->find()->where(['enable' => '1'])->all());
            $docs = TableRegistry::get('profile_docs');
            $query = $docs->find();
            $client_docs = $query->select()->where(['profile_id' => $id])->all();
            $this->set('client_docs', $client_docs);
            $this->loadModel('Logos');

            $this->set('logos', $this->paginate($this->Logos->find()->where(['secondary' => '0'])));
            $this->set('logos1', $this->paginate($this->Logos->find()->where(['secondary' => '1'])));
            $this->set('logos2', $this->paginate($this->Logos->find()->where(['secondary' => '2'])));
            $profile = $this->Profiles->get($id, [
                'contain' => []
            ]);

            if(isset($_POST["action"]) && $_POST["action"]) {
                switch($_POST["action"]){
                    case "sendmessage":
                        $From = $this->Manager->get_profile();
                        $this->Manager->handleevent("sendmessage", array("message" => $_POST["message"], "from" => $From->username, "email" => array($profile->email, $From->email)));
                        $this->Flash->success($this->Trans->getString("flash_messagesent"));

                        break;
                }
            } else if ($this->request->is(['patch', 'post', 'put'])) {
                $Password=$_POST['pass_word'];
                $data = $_POST;
                $data['password'] = md5($Password);
                if (isset($_POST['pass_word']) && $_POST['pass_word'] == '') {
                    //die('here');
                    $data['password'] = $profile->password;
                    $Password = '[ENCRYPTED]';
                }

                if (isset($_POST["emailcreds"]) && $_POST["emailcreds"] && strlen(trim($_POST["email"])) > 0) {
                    $data["emailsent"] = date('Y-m-d H:i:s');
                }

                if(isset($_POST["emailcreds"]) && $_POST["emailcreds"] && trim($_POST["email"])) {
                    $data["emailsent"] = date('Y-m-d H:i:s');
                    $this->Mailer->handleevent("passwordreset", array("username" => $_POST['username'], "email" => $_POST["email"], "password" => $Password));
                }

                if (isset($_POST['profile_type']) && $_POST['profile_type'] == 1) {
                    $data['admin'] = 1;
                } else {
                    $data['admin'] = 0;
                }
                $data['dob'] = $_POST['doby'] . "-" . $_POST['dobm'] . "-" . $_POST['dobd'];
                $data['language'] = $this->updatelanguage($_POST);

                //$profile = $this->Profiles->patchEntity($profile, $data);//doesn't work
                $data = $this->Manager->update_database("profiles", "id", $id, $data, true);

                $emails = array("super");
                if (isset($_POST["emailcreds"]) && $_POST["emailcreds"] && strlen(trim($_POST["email"])) > 0) {
                    $emails[] = $_POST["email"];
                }

                if(!$id) {
                    $path = $this->Document->getUrl();
                    $this->Mailer->handleevent("profilecreated", array("username" => $_POST['username'], "email" => $emails, "path" => $path, "createdby" => $this->Manager->read("username"), "type" => $profile->profile_type, "password" => $Password, "id" => $id));
                }

                if ($this->Profiles->save($profile)) {
                    $this->Flash->success("TESTING ID: " . $id . " " . $this->Trans->getString("flash_profilesaved"));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error($this->Trans->getString("flash_profilenotsaved"));
                }
            }
            $profile->Ptype = $this->getprofiletypeData($profile->profile_type);

            $this->set('doc_comp', $this->Document);
            $orders = TableRegistry::get('orders');
            $order = $orders->find()->where(['orders.uploaded_for' => $id])->contain(['Profiles', 'Clients', 'RoadTest']);

            $this->set('orders', $order);
            $this->set(compact('profile'));
            $this->set('id', $id);
            $this->set('uid', $id);

            $this->set('products', TableRegistry::get('product_types')->find()->where(['id <>' => 7]));
            $this->loadclients($profile->id);

            if ($this->Manager->get_settings()->mee == "ASAP Secured Training") {
                $this->set("sitenames", $this->getdistinctfields("profiles", "sitename"));
                $this->set("asapdivisions", $this->getdistinctfields("profiles", "asapdivision"));
            }
        }

        function addprofile($add,$client_id,$user_id){
            $this->Manager->assign_profile_to_client($user_id, $client_id, $add == '1');
            return true;
        }

        function loadclients($userid){
            $clients_id = $this->Settings->getAllClientsId($userid);
            $this->set('clients', $clients_id);
            $clients = explode(",", $clients_id);
            if (count($clients) == 1){
                $this->set('client', TableRegistry::get('clients')->find()->where(['id' => $clients[0]])->first());
            }
        }

        function changePass($id) {
            $profile = $this->Profiles->get($id, [
                'contain' => []
            ]);
            if ($this->request->is(['patch', 'post', 'put'])) {
                $profiles = $this->Profiles->patchEntity($profile, $this->request->data);
                if ($this->Profiles->save($profiles)) {
                    echo "1";
                } else {
                    echo "0";
                }
            }
            die();
        }

        public function delete($id = null) {
            $this->notify($id, "email_profile", "profiledeleted");

            $check_pro_id = $this->Settings->check_pro_id($id);
            if ($check_pro_id == 1) {
                $this->Flash->error($this->Trans->getString("flash_profilenotfound"));
                return $this->redirect("/profiles/index");
                die();
            }

            $checker = $this->Settings->check_permission($this->request->session()->read('Profile.id'), $id);
            if ($checker == 0) {
                $this->Flash->error($this->Trans->getString("flash_permissions", array("place" => "delete 1")) . ' (001)');
                return $this->redirect("/profiles/index");
                die();
            }

            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));
            if ($setting->profile_delete == 0) {
                $this->Flash->error($this->Trans->getpermissions("002", "profile_delete"));
                //$this->Flash->error($this->Trans->getString("flash_permissions", array("place" => "delete 2")) . ' (002)');
                return $this->redirect("/");
            }

            if (isset($_GET['draft'])) {
                $draft = "?draft";
            } else {
                $draft = "";
            }
            $profile = $this->Profiles->get($id);
            // $this->request->allowMethod(['post', 'delete']);
            if ($this->Profiles->delete($profile)) {
                $this->Flash->success($this->Trans->getString("flash_profiledeleted"));
            } else {
                $this->Flash->error($this->Trans->getString("flash_profilenotdeleted"));
            }
            return $this->redirect(['action' => 'index' . $draft]);
        }

        function logout() {
            $this->loadComponent('Cookie');
            $this->Cookie->configKey('Check_login', [
                    'expires' => ' Sat, 26 Jul 1997',
                    'httpOnly' => true
                ]);
            $this->Cookie->delete('Check_login');
            $this->Cookie->delete('Profile.username');
            $this->Cookie->delete('Profile.password');
            $this->Cookie->delete('bar');
             
            $this->request->session()->destroy();

            if ($_SERVER['SERVER_NAME'] == 'localhost') {
                $this->redirect('/login');
            } else if ($_SERVER['SERVER_NAME'] == 'isbmeereports.com') {
                $this->redirect('http://' . getHost()); //isbmee.com');
            } else {
                $this->redirect('/login');
            }
        }

        function todo() {

        }

        function todos() {
            $this->layout = 'blank';
        }

        function blocks($client = "") {
            $user_id = $_POST['form'];
            if ($user_id != 0) {
                $side['user_id'] = $_POST['side']['user_id'];
            }
            $CopyFrom=false;
            foreach ($_POST['side'] as $k => $v) {
                //echo $k."=>".$v."<br/>";
                $side[$k] = $v;
            }
            if ($client == "") {
                $sides = $this->getColumnNames("sidebar", "id", true);//why does this use sidebar columns instead of block?
               
                foreach ($sides as $s) {
                    if (!isset($_POST['side'][$s])) {
                        $side[$s] = 0;
                    }
                }
            }

            $sidebar = TableRegistry::get('sidebar');
            $s1 = $sidebar->find()->where(['user_id' => $user_id])->count();
            if ($user_id != 0 && $s1 != 0) {
                $Conditions = array('user_id' => $user_id);
                if(isset($_POST["changefuture"]) || isset($_POST["changeexisting"])) {
                    $ProfileType = $this->Manager->get_profile($user_id)->profile_type;
                }

                if(isset($_POST["changeexisting"]) && $_POST["changeexisting"]){
                    $Conditions = array('user_id IN (' . $this->enumprofiletype( $ProfileType ) . ')');
                    unset($side["user_id"]);
                }

                $query1 = $sidebar->query();
                $query1->update()
                    ->set($side)
                    ->where($Conditions)
                    ->execute();

                $IsMaster=0;
                if(isset($_POST["changefuture"]) && $_POST["changefuture"]){
                    $this->Manager->update_database("profiles", array("profile_type" => $ProfileType, "master" => 1), array("master" => 0));
                    $IsMaster = 1;
                }
                $this->Manager->update_database("profiles", "id", $user_id, array("master" => $IsMaster));
            } else {
                $article = $sidebar->newEntity($_POST['side']);
                $sidebar->save($article);
            }
            $SubDocs = $this->displaySubdocs($user_id);
            if(isset($_POST["changeexisting"]) && $_POST["changeexisting"]){
                $Profiles = $this->tocsv($this->Manager->enum_all("profiles", array("profile_type" => $ProfileType)), "id");
                foreach($SubDocs as $SubDoc) {
                    $this->Manager->update_database("Profilessubdocument", array("profile_id IN (" . $Profiles . ')', "subdoc_id" => $SubDoc["subdoc_id"]), array("display" => $SubDoc["display"], "Topblock" => $SubDoc["Topblock"]));
                }

            }
            die();
        }

        function tocsv($Objects, $Field){
            $Array = array();
            foreach($Objects as $Object){
                $Array[] = $Object->$Field;
            }
            return implode(",", $Array);
        }

        function enumprofiletype($ProfileType){
            $RET = array();
            $Profiles = $this->Manager->enum_all("profiles", array("profile_type" => $ProfileType));
            foreach($Profiles as $Profile){
                $RET[] = $Profile->id;
            }
            return implode(",", $RET);
        }

        function homeblocks() {
            $user_id = $_POST['form'];
            if ($user_id != 0) {
                $block['user_id'] = $_POST['block']['user_id'];
            }
            foreach ($_POST['block'] as $k => $v) {
                $block[$k] = $v;
            }
            
            $blocks = TableRegistry::get('blocks');
            $filednames = ['addadriver','searchdriver','submitorder','orderhistory','schedule','schedule_add','tasks',
                          'feedback','analytics','masterjob','user_id','submit_document','list_document','list_order',
                          'list_client','add_client','list_profile','message','orders_draft','document_draft','ordersmee',
                          'ordersproducts','ordersrequalify','draft_client','draft_profile','orders_intact','bulk','ordersbulk',
                          'ordersgem','ordersgdr','orders_spf','orders_sms','orders_psa','orders_cch','orders_emp','orders_sal',
                          'orders_gdo' ,'training' ];
            foreach($filednames as $key)
            {
                if(isset($block[$key]))
                    $block[$key] = $block[$key];
                else 
                    $block[$key] = 0;
            }
           //var_dump($block); die();
            $s = $blocks->find()->where(['user_id' => $user_id])->count();
            if ($user_id != 0 && $s != 0) {
                $this->Manager->update_database('blocks', 'user_id', $user_id, $block);
            } else {
                $article = $blocks->newEntity($_POST['block']);
                $blocks->save($article);
            }
            die();
        }

        function getsubdocument_topblocks($UserID, $getpost = false) {
            $table = TableRegistry::get('order_products_topblocks');
            if ($getpost) {//save
                $table->deleteAll(array('UserID' => $UserID), false);
                foreach ($_POST['topblocks'] as $Key => $Value) {
                    if ($Value == 1) {
                        $table->query()->insert(['UserID', 'ProductID'])->values(['UserID' => $UserID, 'ProductID' => $Key])->execute();
                    }
                }
            } else {//load
                $query = $table->find()->select()->where(['UserID' => $UserID])->order(['ProductID' => 'asc']);
                $products = TableRegistry::get('order_products')->find('all');
                foreach ($products as $product) {
                    $product->TopBlock = 0;
                    if (is_object($this->FindIterator($query, "ProductID", $product->number))) {
                        $product->TopBlock = 1;
                    }
                }
                $this->set("theproductlist", $products);
            }
        }

        function FindIterator($ObjectArray, $FieldName, $FieldValue) {
            foreach ($ObjectArray as $Object) {
                if ($Object->$FieldName == $FieldValue) {
                    return $Object;
                }
            }
            return false;
        }

        function getProAllSubDoc($pro_id){
            //$pro_id = $this->Manager->getmaster($pro_id);
            $sub = TableRegistry::get('Profilessubdocument')->find()->select()->where(['profile_id'=>$pro_id]);
            return $sub;
        }

        function getSub($UserID = false, $sortByTitle=false){
            $sub = TableRegistry::get('Subdocuments');
            $query = $sub->find();
            $q = $query->select();
            if ($UserID){
                //$UserID = $this->Manager->getmaster($UserID);
                $table = TableRegistry::get('Profilessubdocument');
                if($sortByTitle){$subdoc2=array();}
                foreach($q as $subdoc){
                    $subdoc->forms = $subdoc->ProductID;// $this->getenabledprovinces($subdoc->ProductID);
                    $subdoc->subdoc = $table->find()->select()->where(['profile_id'=>$UserID, 'subdoc_id'=>$subdoc->id])->first();
                    if($sortByTitle){$subdoc2[]=$subdoc;}
                }
                $q->Subdocs = $this->getProAllSubDoc($UserID);
            }
            if($sortByTitle){
                usort($subdoc2, array($this,'sortByOrder'));
                $this->response->body($subdoc2);
            }else {
                $this->response->body($q);
            }
            return $this->response;
        }

        function sortByOrder($a, $b) {
            return strcmp($a['title'], $b['title']);
        }

        function getProSubDoc($pro_id, $doc_id){
            $sub = TableRegistry::get('Profilessubdocument');
            //$pro_id = $this->Manager->getmaster($pro_id);
            $query = $sub->find();
            $query->select()->where(['profile_id' => $pro_id, 'subdoc_id' => $doc_id]);
            $q = $query->first();
            $this->response->body($q);
            return $this->response;
        }

        function displaySubdocs($id) {
            $user['profile_id'] = $id;//$this->Manager->getmaster($id);
            $SubDocs = array();
            //for user base
            $subp = TableRegistry::get('Profilessubdocument');
            $query2 = $subp->query();
            $query2->delete()->where(['profile_id' => $id])->execute();

            foreach ($_POST['profile'] as $k2 => $v) {
                $TopBlock = 0;
                if (isset($_POST['topblock'][$k2])) {
                    $TopBlock = $_POST['topblock'][$k2];
                }
                $query2->insert(['profile_id', 'subdoc_id', 'display', 'Topblock'])->values(['profile_id' => $id, 'subdoc_id' => $k2, 'display' => $_POST['profile'][$k2], 'Topblock' => $TopBlock])->execute();

                $SubDocs[] = array("subdoc_id" => $k2, 'display' => $_POST['profile'][$k2], 'Topblock' => $TopBlock);
            }

            $subd = TableRegistry::get('Subdocuments');
            foreach ($_POST as $k => $v) {
                if ($k != 'profile') {
                    $subd->query()->update()->set(['display' => $v])->where(['id' => $k])->execute();
                }
            }
            return $SubDocs;
        }


        function getRecruiter() {
            $rec = TableRegistry::get('Profiles');
            $query = $rec->find()->where(['profile_type' => 2]);
            //$q = $query->select();
            $this->response->body($query);
            return $this->response;
            die();
        }

        function getProfile($ClientID = 0) {
            $rec = TableRegistry::get('Profiles');
            $query = $rec->find();
            $u = $this->request->session()->read('Profile.id');
            $super = $this->request->session()->read('Profile.super');

            $conditions = array('super <>' => 1, 'drafts' => 0);

            if($ClientID>0) {//SET @profiles = (
                $conditions2 = '(SELECT profile_id FROM clients WHERE id = ' . $ClientID . ")";
                $conditions[] = 'find_in_set(id, ' . $conditions2 . ')';
            } else if (!$super) {
                $conditions['created_by'] = $u;
            }
            $query = $rec->find()->where($conditions)->order('fname');

            $this->response->body($query);
            return $this->response;
            die();
        }


        function getProfileTypes($Language = "English", $retasrequest = true) {
            $rec = TableRegistry::get('profile_types')->find();
            $query = array();
            $column="title";
            if($Language == "Debug"){$Language = "English"; $Trans=" [Trans]"; } else {$Trans="";}
            if($Language != "English"){$column.=$Language;}
            foreach($rec as $Ptype){//id title enable ISB titleFrench placesorders
                $name= $Ptype->$column;
                if(!$name){$name=$Ptype->title . " (MISSING: " . $Language . ")";}
                $query[$Ptype->id] = $name . $Trans;
                $query[$Ptype->id . ".canorder"] = $Ptype->placesorders;
            }
            if ($retasrequest) {
                $this->response->body($query);
                return $this->response;
                die();
            }
            return $query;
        }

        function possess($userID){
            if ($this->request->session()->read('Profile.super') || $userID == -1){
                if($userID == -1){//de-possess
                    $userID = $this->request->session()->read('Profile.oldid');
                    $this->request->session()->delete('Profile.oldid');
                } else {
                    $this->request->session()->write('Profile.oldid', $this->request->session()->read('Profile.id'));
                }
                $q = $this->Profiles->find()->where(["id" => $userID])->first();
                if($q) {
                    $this->request->session()->write('Profile.id', $q->id);
                    $this->request->session()->write('Profile.username', $q->username);
                    $this->request->session()->write('Profile.fname', $q->fname);
                    $this->request->session()->write('Profile.lname', $q->lname);
                    $this->request->session()->write('Profile.isb_id', $q->isb_id);
                    $this->request->session()->write('Profile.mname', $q->mname);
                    $this->request->session()->write('Profile.profile_type', $q->profile_type);
                    $this->request->session()->write('Profile.language', $q->language);
                    $this->request->session()->write('Profile.email', $q->email);
                    $this->request->session()->write('Profile.super', $q->super);
                    $this->request->session()->write('Profile.admin', $q->admin);
                }
            }
            $this->redirect('/');
        }

        function getAjaxProfile($id = 0, $mode = 0) {
            $this->layout = 'blank';
            if($mode==0) {
                if ($id) {
                    $this->loadModel('Clients');
                    $profile = $this->Clients->get($id, [
                        'contain' => []
                    ]);
                    $arr = explode(',', $profile->profile_id);
                    $this->set('profile', $arr);
                } else {
                    $this->set('profile', array());
                }
            }

            $key = $_GET['key'];
            $rec = TableRegistry::get('Profiles');
            $query = $rec->find();
            $u = $this->request->session()->read('Profile.id');
            $super = $this->request->session()->read('Profile.admin');
            $cond = $this->Settings->getprofilebyclient($u, $super);

            $conditions=array('iscomplete' >= 1, 'super <>' => 1, 'drafts' => 0, '(fname LIKE "%' . $key . '%" OR lname LIKE "%' . $key . '%" OR username LIKE "%' . $key . '%")');
            if($mode==1 && $id>0) {//search by client
                $conditions[] = 'find_in_set(id, (SELECT profile_id FROM clients WHERE id = ' . $id . '))';
                //$RequiredFields = array_keys($this->Manager->requiredfields("", "profile2order"));
                //foreach($RequiredFields as $Field){
                //    $conditions[] = "CHAR_LENGTH(" . $Field . ') > 0';
                //}
            } else if (!$super) {
                $conditions['created_by'] = $u;
            }
            $query = $rec->find()->where($conditions)->order('fname');

            $query->mode = $mode;
            if($mode==1) {
                foreach($_GET as $Key => $Value) {
                    $query->$Key = $Value;
                }
                
            }
            $this->set('profiles', $query);
            $this->set('cid', $id);
        }

        function getProfileNames($IDs = ""){
            $names='';
            if($IDs) {
                $query = TableRegistry::get('profiles')->find()->where(array("find_in_set(id, '" . $IDs . "')"))->order('fname');
                foreach ($query as $profile) {
                    $name = $profile->fname . " " . $profile->lname . " (" . $profile->username . ")";
                    if ($names) {
                        $names .= ", " . $name;
                    } else {
                        $names = $name;
                    }
                }
            }
            $this->response->body($names);
            return $this->response;
            die();
        }

        function getAjaxContact($id = 0) {
            $this->layout = 'blank';
            if ($id) {
                $this->loadModel('Clients');
                $profile = $this->Clients->get($id, [
                    'contain' => []
                ]);
                $arr = explode(',', $profile->contact_id);
                $this->set('contact', $arr);
            } else {
                $this->set('contact', array());
            }
            $key = $_GET['key'];
            $rec = TableRegistry::get('Profiles');
            $query = $rec->find();
            $u = $this->request->session()->read('Profile.id');
            $super = $this->request->session()->read('Profile.super');
            $cond = $this->Settings->getprofilebyclient($u, $super);
            //$query = $query->select()->where(['super'=>0]);
            $query = $query->select()->where(['profile_type NOT IN' => '(6)', 'OR' => $cond])
                ->andWhere(['super' => 0, 'profile_type' => 6, '(fname LIKE "%' . $key . '%" OR lname LIKE "%' . $key . '%" OR username LIKE "%' . $key . '%")']);
            $this->set('contacts', $query);
            $this->set('cid', $id);
        }

        function getContact() {
            $con = TableRegistry::get('Profiles');
            $query = $con->find()->where(['profile_type' => 6]);
            $this->response->body($query);
            return $this->response;
            die();
        }

        function filterBy() {
            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));
            if ($setting->profile_list == 0) {
                $this->Flash->error($this->Trans->getpermissions("003", "profile_list"));
                //$this->Flash->error($this->Trans->getString("flash_permissions", array("place" => "filter by")) . '(003)');
                return $this->redirect("/");
            }
            $profile_type = $_GET['filter_profile_type'];
            $querys = TableRegistry::get('Profiles');
            $query = $querys->find()->where(['profile_type' => $profile_type]);
            $this->set('profiles', $this->paginate($this->Profiles));
            $this->set('profiles', $query);
            $this->set('return_profile_type', $profile_type);
            $this->render('index');
        }

        function getuser() {
            if ($id = $this->request->session()->read('Profile.id')) {
                $profile = TableRegistry::get('profiles');
                $query = $profile->find()->where(['id' => $id]);

                $l = $query->first();
                $this->response->body($l);
                return $this->response;
                //return $l;

            } else {
                return $this->response->body(null);
            }
            die();
        }

        function getallusers($profile_type = "", $client_id = "") {
            $u = $this->request->session()->read('Profile.id');
            $super = $this->request->session()->read('Profile.super');
            $cond = $this->Settings->getprofilebyclient($u, $super, $client_id);
            $profile = TableRegistry::get('profiles');
            if ($profile_type != "") {
                $query = $profile->find()->where(['super' => 0, 'profile_type' => $profile_type, 'OR' => $cond]);
            }else {
                $query = $profile->find()->where(['super' => 0, 'OR' => $cond]);
            }
            //debug($query);
            $l = $query->all();
            $this->response->body($l);
            return $this->response;

        }

        function getusers() {
            $title = $_POST['v'];

            if ($title != "") {
                $u = $this->request->session()->read('Profile.id');
                $super = $this->request->session()->read('Profile.super');
                $cond = $this->Settings->getprofilebyclient($u, $super);

                $profile = TableRegistry::get('profiles');
                $query = $profile->find()->where(['username LIKE' => '%' . $title . "%", 'OR' => $cond]);

                $l = $query->all();
                if (count($l) > 0) {
                    foreach ($l as $user) {
                        echo "<a style='display:block; padding:5px 0; text-decoration:none;' onclick='$(\".madmin\").val(\"$user->username\"); $(\".loadusers\").hide()'>" . $user->username . "</a>";
                    }
                } else {
                    echo "1";
                }
            } else {
                echo "0";
            }
            die();

        }

        function getOrder($id) {
            $con = TableRegistry::get('Documents');
            $query = $con->find()->where(['uploaded_for' => $id, 'document_type' => 'order']);
            $this->response->body($query);
            return $this->response;
            die();
        }

        function getClient() {
            $query = TableRegistry::get('Clients');
            $q = $query->find()->order(["company_name" => 'ASC']);
            $que = $q->select();
            $this->response->body($que);
            return $this->response;
            die();
        }

        function getProfileType($id = null) {
            $q = TableRegistry::get('Profiles');
            $que = $q->find();
            $que->select(['profile_type'])->where(['id' => $id]);
            $query = $que->first();
            $this->response->body($query);
            return $this->response;
            die();
        }

        function getProfileById($id, $sub) {
            if($id) {
                $q = TableRegistry::get('Profiles');
                $query = $q->find();
                $que = $query->select()->where(['id' => $id])->first();

                if ($sub == 1) {
                    $arr['applicant_phone_number'] = $que->phone;
                    $arr['aplicant_name'] = $que->fname . ' ' . $que->lname;
                    $arr['applicant_email'] = $que->email;
                }
                if ($sub == 2) {
                    $arr['street_address'] = $que->street;
                    $arr['city'] = $que->city;
                    $arr['state_province'] = $que->province;
                    $arr['postal_code'] = $que->postal;
                    $arr['last_name'] = $que->lname;
                    $arr['first_name'] = $que->fname;
                    $arr['phone'] = $que->phone;
                    $arr['email'] = $que->email;
                }
                if ($sub == 3) {
                    $arr['driver_name'] = $que->fname . ' ' . $que->lname;
                    $arr['d_l'] = $que->driver_license_no;
                }
                if ($sub == 4) {
                    $arr['last_name'] = $que->lname;
                    $arr['first_name'] = $que->fname;
                    $arr['mid_name'] = $que->mname;
                    $arr['sex'] = $que->gender;
                    $arr['birth_date'] = $que->dob;
                    $arr['phone'] = $que->phone;
                    $arr['current_city'] = $que->city;
                    $arr['current_province'] = $que->province;
                    $arr['current_postal_code'] = $que->postal;
                    $arr['driver_license_number'] = $que->driver_license_no;
                    $arr['driver_license_issued'] = $que->driver_province;
                    $arr['current_street_address'] = $que->street;
                    $arr['applicants_email'] = $que->email;
                }

                echo json_encode($arr);
                die;
            }else{die();}
        }

        public function getNotes($driver_id) {
            $q = TableRegistry::get('recruiter_notes');
            $que = $q->find();
            $query = $que->select()->where(['driver_id' => $driver_id])->order(['id' => 'desc']);
            $this->response->body($query);
            return $this->response;
            die();
        }

        public function getRecruiterById($id) {
            $q = TableRegistry::get('Profiles');
            $que = $q->find();
            $query = $que->select()->where(['id' => $id])->first();
            $this->response->body($query);
            return $this->response;
            die();
        }

        public function deleteNote($id) {
            $this->loadModel('recruiter_notes');
            $note = $this->recruiter_notes->get($id);
            $this->recruiter_notes->delete($note);
            die();
        }

        public function saveNote($id, $rid) {
            $note = TableRegistry::get('recruiter_notes');
            $_POST['driver_id'] = $id;
            if (!$rid) {
                $_POST['recruiter_id'] = $this->request->session()->read('Profile.id');

                $_POST['created'] = date('Y-m-d');
            }
            if (!$rid) {
                $save = $note->newEntity($_POST);

                if ($note->save($save)) {
                    echo '<div class="item">
            <div class="item-head">
                <div class="item-details">
                    <a href="" class="item-name primary-link">' . $this->request->session()->read('Profile.fname') . ' ' . $this->request->session()->read('Profile.mname') . ' ' . $this->request->session()->read('Profile.lname') . '</a>
                    <span class="item-label">' . date('m') . '/' . date('d') . '/' . (date('Y') - 2000) . '</span>
                </div>
                
            </div>
            <div class="item-body">
                <span id="desc' . $save->id . '">' . $_POST['description'] . '</span><br/><a href="javascript:void(0);" class="btn btn-small btn-primary editnote" style="padding: 0 8px;" id="note_' . $save->id . '">' . $this->Trans->getString("dashboard_edit") . '</a> <a href="javascript:void(0);" class="btn btn-small btn-danger deletenote" style="padding: 0 8px;" id="dnote_' . $save->id . '" onclick="return deletenote(' . $save->id  . ", '" . $this->Trans->getString("dashboard_confirmdelete", array("name" => $_POST['description'])) . '\');">' . $this->Trans->getString("dashboard_delete") . '</a><br/><br/>

            </div>
        </div>';
                }else {
                    echo $this->Trans->getString("flash_error");
                }
                die();
            } else {
                $note->query()->update()
                    ->set($_POST)
                    ->where(['id' => $rid])
                    ->execute();
                //$q = TableRegistry::get('Profiles');
                $que = $note->find();
                $query = $que->select()->where(['id' => $id])->first();
                $arr_cr = explode(',', $query->created);

                $q = TableRegistry::get('Profiles');
                $query2 = $q->find();
                $que2 = $query->select()->where(['id' => $query->recruiter_id])->first();
                echo '<div class="item">
            <div class="item-head">
                <div class="item-details">
                    <a href="" class="item-name primary-link">' . $que2->fname . ' ' . $que2->mname . ' ' . $que2->lname . '</a>
                    <span class="item-label">' . $arr_cr[0] . '</span>
                </div>
                
            </div>
            <div class="item-body">
                <span id="desc' . $rid . '">' . $_POST['description'] . '</span><br/><a href="javascript:void(0);" class="btn btn-small btn-primary editnote" style="padding: 0 8px;" id="note_' . $rid . '">Edit</a> <a href="javascript:void(0);" class="btn btn-small btn-danger deletenote" style="padding: 0 8px;" id="dnote_' . $rid . '" onclick="return confirm(\'' . $this->Trans->getString("dashboard_confirmdelete", array("name" => $_POST['description'])) . '\');">Delete</a><br/><br/>
            </div>
        </div>';
            }
        }

        public function check_user($uid = '',$user1="") {
            $r = '';
            if (isset($_POST['username']) && $_POST['username'] && $user1=="") {
                $user = $_POST['username'];
            }else {
                if ($user1) {
                    $user = $user1;
                } else {
                    echo '0';
                    die();
                }
            }
            $q = TableRegistry::get('profiles');
            $que = $q->find();

            if ($uid != "") {
                $query = $que->select()->where(['id !=' => $uid, 'username' => $user])->first();
            }else {
                $query = $que->select()->where(['username' => $user])->first();
            }
            $r = '0';
            if ($query) {
                $r = '1';
            }
            if($user1!="") {
                return $r;
            }else {
                echo $r;
                die();
            }
        }

        public function check_email($uid = '', $email1="") {
            if($email1 == "") {
                $email = $_POST['email'];
            }else {
                $email = $email1;
            }
            $q = TableRegistry::get('profiles');
            $que = $q->find();
            if ($uid != "") {
                $query = $que->select()->where(['id !=' => $uid, 'email' => $email])->first();
            }else {
                $query = $que->select()->where(['email' => $email])->first();
            }
            if ($query) {
                $r = '1';
            }else {
                $r = '0';
            }
            if($email1!="") {
                return $r;
            }else{
                echo $r;
                die();
            }
        }








        function get_string_between($string, $start, $end) {
            $string = " " . $string;
            $ini = strpos($string, $start);
            if ($ini == 0) return "";
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr($string, $ini, $len);
        }

        function get_mee_results_binary($bright_planet_html_binary, $document_type) {
            return ($this->get_string_between(base64_decode($bright_planet_html_binary), $document_type, '</tr>'));
        }

        function create_files_from_binary($order_id, $pdi, $binary) {
            $createfile_pdf = "orders/order_" . $order_id . '/' . $pdi . '.pdf';
            $createfile_html = "orders/order_" . $order_id . '/' . $pdi . 'html';
            $createfile_text = "orders/order_" . $order_id . '/' . $pdi . 'txt';

            if (!file_exists($createfile_pdf) && !file_exists($createfile_text) && !file_exists($createfile_html)) {

                if (isset($binary) && $binary != "") {
                    file_put_contents('unknown_file', base64_decode($binary));
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, 'unknown_file');
                    if ($mime == "application/pdf") {
                        rename("unknown_file", "orders/order_" . $order_id . '/' . $pdi . '.pdf');
                    } elseif ($mime == "text/html") {
                        rename("unknown_file", "orders/order_" . $order_id . '/' . $pdi . '.html');
                    } elseif ($mime == "text/plain") {
                        rename("unknown_file", "orders/order_" . $order_id . '/' . $pdi . '.html');
                    } else {
                        rename("unknown_file", "orders/order_" . $order_id . '/' . $pdi . '.html');
                    }
                }
            }
        }

        public function save_bright_planet_grade($orderid = null, $product_id = null, $grade = null) {
            $querys = TableRegistry::get('orders');
            $arr[$product_id] = $grade;
            $query2 = $querys->query();
            $query2->update()
                ->set($arr)
                ->where(['id' => $orderid])
                ->execute();
            $this->response->body($query2);
            return $this->response;
        }

/////////////////////////////////////////////////////////////////////////////////////process order
        function cron($debugging = false) {//////////////////////////////////send out emails
            $path = $this->Document->getUrl();
            $this->layout = 'blank';
            $setting = TableRegistry::get('settings')->find()->first();
            $q = TableRegistry::get('events');
            $que = $q->find();
            //$query = $que->select()->where(['(date LIKE "%' . $date . '%" OR date LIKE "%' . $date2 . '%")', 'sent' => 0])->limit(200);
            $datetime = date('Y-m-d H:i:s');
            $conditions = array('(date <= "' . $datetime . '")');
            if(!$debugging){$conditions['sent'] = 0;}
            $query = $que->select()->where($conditions)->limit(2500);
            //VAR_Dump($query);die();

            $Emails = 1;
            echo '<TABLE BORDER="1" cellspacing="0" id="crontable"><THEAD><TR><TH>ID</TH><TH>TITLE</TH><TH>NOTES</TH></TR></THEAD>';
            if($debugging){echo '<TR><TH COLSPAN="3">Debugging mode is on</TH></TR>';}
            echo '<TR><TH COLSPAN="3">Task events before now (' . $datetime . ')</TH></TR>';
            if (isset($_GET["testemail"])) {
                $email = $this->request->session()->read('Profile.email');
                echo '<TR><TD>' . $Emails++ . '</TD><TD>' . $this->sendtaskreminder($email, "test", $path, "TEST EMAIL") . '</TD></TR>';
            }
            foreach ($query as $todo) {
                if ($todo->email_self == '1') {
                    $query2 = $this->loadprofile($todo->user_id);
                    $email = $query2->email;
                    if ($email) {
                        echo '<TR><TD>' . $Emails++ . '</TD><TD>' . $this->sendtaskreminder($email, $todo, $path, "Account holder") . '</TD></TR>';
                    }
                }
                if (strlen($todo->others_email) > 0) {
                    $emails = explode(",", $todo->others_email);
                    foreach ($emails as $em) {
                        echo '<TR><TD>' . $Emails++ . '</TD><TD>' . $this->sendtaskreminder($em, $todo, $path, "Added by account holder") . '</TD></TR>';
                    }
                }
                $q->query()->update()->set(['sent' => 1, 'email_self' => 0])->where(['id' => $todo->id])->execute();
            }

            $Table = TableRegistry::get('orders');

            $Hour = date("G");
            //if($Hour >= 2 && $Hour <= 23) {//only run between 2 and 3 AM
                $orders = $Table->find()->where(['order_type' => "BUL", "is_bulk" => 1, "complete_writing" => 0]);
                echo '<TR><TH COLSPAN="3">Bulk Orders waiting to pass to the webservice</TH></TR>';
                foreach ($orders as $order) {
                    //$DIR = getcwd() . '/orders/order_' . $order->id;//APP
                    //if (!file_exists($DIR)) {
                        $Result = file_get_contents(LOGIN . '/orders/webservice/BUL/' . $order->forms . '/' . $order->uploaded_for . '/' . $order->id);
                        echo '<TR><TD>' . $order->id . '</TD><TD COLSPAN="2">' . $Result . '</TD></TR>';
                    //}
                }
            //}


            $order = $Table->find()->where(['orders.draft' => 0, "orders.complete" => 0, "orders.complete_writing" => 1])->order('orders.id DESC')->limit(150);

            echo '<TR><TH COLSPAN="3">The first 150 non-draft orders</TH></TR>';
            foreach ($order as $o) {
                echo '<TR><TD>' . $o->id . '</TD><TD>' . $o->title . '</TD><TD>' ;
                // echo $o->id .',';
                $complete = 1;

                if ($o->ins_1 && $o->ins_1_binary == null) {
                    $complete = 0;
                    echo "ins 1 not complete<br>";
                } else if ($o->ins_1 && $o->ins_1_binary != "done") {
                    $this->create_files_from_binary($o->id, "1", $o->ins_1_binary);
                    $this->save_bright_planet_grade($o->id, 'ins_1_binary', 'done');
                    echo "ins 1 complete<br>";
                }

                if ($o->ins_14 && $o->ins_14_binary == null) {
                    $complete = 0;
                    echo "ins 14 not complete<br>";
                } else if ($o->ins_14 && $o->ins_14_binary != "done") {
                    $this->create_files_from_binary($o->id, "14", $o->ins_14_binary);
                    $this->save_bright_planet_grade($o->id, 'ins_14_binary', 'done');
                    echo "ins 14 complete<br>";
                }


                if ($o->ins_72 && $o->ins_72_binary == null) {
                    $complete = 0;
                    echo "ins 72 not complete<br>";
                } else if ($o->ins_72 && $o->ins_72_binary != "done") {
                    $this->create_files_from_binary($o->id, "72", $o->ins_72_binary);
                    $this->save_bright_planet_grade($o->id, 'ins_72_binary', 'done');
                    echo "ins 72 complete<br>";
                }

                if ($o->ins_77 && $o->ins_77_binary == null) {
                    $complete = 0;
                    echo "ins 77 not complete<br>";
                } else if ($o->ins_77 && $o->ins_77_binary != "done") {
                    $this->create_files_from_binary($o->id, "77", $o->ins_77_binary);
                    $this->save_bright_planet_grade($o->id, 'ins_77_binary', 'done');
                    echo "ins 77 complete<br>";
                }



                if ($o->ins_31 && $o->ins_31_binary == null) {
                    $complete = 0;
                    echo "ins 31 not complete<br>";
                } else if ($o->ins_31 && $o->ins_31_binary != "done") {
                    $this->create_files_from_binary($o->id, "31", $o->ins_31_binary);
                    $this->save_bright_planet_grade($o->id, 'ins_31_binary', 'done');
                    echo "ins 31 complete<br>";
                }


                if ($o->ins_32 && $o->ins_32_binary == null) {
                    $complete = 0;
                    echo "ins 32 not complete<br>";
                } else if ($o->ins_32 && $o->ins_32_binary != "done") {
                    $this->create_files_from_binary($o->id, "32", $o->ins_32_binary);
                    $this->save_bright_planet_grade($o->id, 'ins_32_binary', 'done');
                    echo "ins 32 complete<br>";
                }

                if ($o->ins_78 && $o->ins_78_binary == null) {
                    $complete = 0;
                    echo "ins 78 not complete<br>";
                } else if ($o->ins_78 && $o->ins_78_binary != "done") {
                    $this->create_files_from_binary($o->id, "78", $o->ins_78_binary);
                    $this->save_bright_planet_grade($o->id, 'ins_78_binary', 'done');
                    echo "ins 78 complete<br>";
                }

                if ($o->ebs_1603 && $o->ebs_1603_binary == null) {
                    $complete = 0;
                    echo "ebs 1603 not complete<br>";
                } else if ($o->ebs_1603 && $o->ebs_1603_binary != "done") {
                    $this->create_files_from_binary($o->id, "1603", $o->ebs_1603_binary);
                    $this->save_bright_planet_grade($o->id, 'ebs_1603_binary', 'done');
                    echo "ebs 1603 complete<br>";
                }


                if ($o->ebs_1627 && $o->ebs_1627_binary == null) {
                    $complete = 0;
                    echo "ebs 1627 not complete<br>";
                } else if ($o->ebs_1627 && $o->ebs_1627_binary != "done") {
                    $this->create_files_from_binary($o->id, "1627", $o->ebs_1627_binary);
                    $this->save_bright_planet_grade($o->id, 'ebs_1627_binary', 'done');
                    echo "ebs 1627 complete<br>";
                }


                if ($o->ebs_1650 && $o->ebs_1650_binary == null) {
                    $complete = 0;
                    echo "ebs 1650 not complete<br>";
                } else if ($o->ebs_1650 && $o->ebs_1650_binary != "done") {
                    $this->create_files_from_binary($o->id, "1650", $o->ebs_1650_binary);
                    $this->save_bright_planet_grade($o->id, 'ebs_1650_binary', 'done');
                    echo "ebs 1650 complete<br>";
                }

                if ($o->bright_planet_html_binary) {
                    $this->create_files_from_binary($o->id, "bright_planet_html_binary", $o->bright_planet_html_binary);

                    /*
                    $sendit = strip_tags(trim($this->get_mee_results_binary($o->bright_planet_html_binary, "Driver's Record Abstract")));
                    if ($sendit) {
                        $this->save_bright_planet_grade($o->id, 'ins_1', $sendit);
                    }

                    $sendit = strip_tags(trim($this->get_mee_results_binary($o->bright_planet_html_binary, "Pre-employment Screening Program Report")));
                    if ($sendit) {
                     $this->save_bright_planet_grade($o->id, 'ins_77', $sendit);
                    }

                    $sendit = strip_tags(trim($this->get_mee_results_binary($o->bright_planet_html_binary, "CVOR")));
                    if ($sendit) {
                       $this->save_bright_planet_grade($o->id, 'ins_14', $sendit);
                    }
                    $sendit = strip_tags(trim($this->get_mee_results_binary($o->bright_planet_html_binary, "Premium National Criminal Record Check")));
                    if ($sendit) {
                      $this->save_bright_planet_grade($o->id, 'ebs_1603', $sendit);
                    }

                    $sendit = strip_tags(trim($this->get_mee_results_binary($o->bright_planet_html_binary, "Certifications")));
                    if ($sendit) {
                     $this->save_bright_planet_grade($o->id, 'ebs_1650', $sendit);
                    }

                    $sendit = strip_tags(trim($this->get_mee_results_binary($o->bright_planet_html_binary, "TransClick")));
                    if ($sendit) {
                     $this->save_bright_planet_grade($o->id, 'ins_78', $sendit);
                    }

                    $sendit = strip_tags(trim($this->get_mee_results_binary($o->bright_planet_html_binary, "Letter Of Experience")));
                    if ($sendit) {
                     $this->save_bright_planet_grade($o->id, 'ebs_1627', $sendit);
                    }

                   $this->save_bright_planet_grade($o->id, 'bright_planet_html_binary', null);
                    */
                }

                if ($complete == 1 && $o->complete == 0) {
                    $or = TableRegistry::get('orders');
                    $quer = $or->query();
                    $quer->update()
                        ->set(['complete' => 1])
                        ->where(['id' => $o->id])
                        ->execute();

                    $table = TableRegistry::get('profiles');
                    $profile1 = $table->find()->where(['id' => $o->user_id])->first();

                    if ($profile1->email) {
                        $path = LOGIN . 'profiles/view/' . $o->uploaded_for . '?getprofilescore=1';
                        $HTML = $this->Manager->order_to_email($o->id, false, "");
                        $this->Mailer->handleevent("cronordercomplete", array("site" => $setting->mee, "path" => $path, "html" => $HTML, "email" => array('super',$profile1->email)));
                    }
                }
                echo '</TD></TR>';
            }


            /* for automatic survey email */
            //$client = TableRegistry::get('clients')->find()->where(['id'=>26])->first();//hard-coded to GFS
            $ids = $this->Manager->get_clients_profiles(26);//  $client->profile_id;
            $table = TableRegistry::get('profiles');
            $conditions = array('id IN('.$ids.")",'is_hired'=>'1', 'hired_date <>'=>'');
            if(!$debugging){$conditions['automatic_sent'] = 0;}

            $automatic = $table->find()->where($conditions);//set automatic_sent to 0 when not debugging
            if($automatic) {
                $queries = TableRegistry::get('Profiles');
                foreach($automatic as $auto) {//this system will only work if it is run every day!!!
                    $today = date('Y-m-d');
                    $thirty = date('Y-m-d', strtotime($auto->hired_date.'+30 days'));
                    $sixty = date('Y-m-d', strtotime($auto->hired_date.'+60 days'));
                    if($debugging) {$sixty = $today; $thirty =$today;} //date('Y-m-d', strtotime($today.'-60 days'));$thirty = $sixty;}//testing mode only

                    if ($auto->email) {//drivers are 60, others are 30
                        $sent=0;
                        if (($auto->profile_type == '5' || $auto->profile_type == '7' || $auto->profile_type == '8')) {//Driver, Owner Operator, Owner Driver
                            if($today == $sixty) {
                                $this->Mailer->handleevent("survey", array("site" => $setting->mee, "username" => $auto->username, "email" => $auto->email, "monthsFrench" => "quelques mois", "months" => "few months", "days" => "60", "id" => $auto->id, "path" => LOGIN . 'application/60days.php?p_id=' . $auto->id));
                                $sent = 60;
                            }
                        } else if ($today == $thirty) {//($auto->profile_type == '9' || $auto->profile_type == '12') &&
                            $this->Mailer->handleevent("survey", array("email" => $auto->email, "username" => $auto->username, "days" => "30", "monthsFrench" => "mois", "months" => "month", "id" => $auto->id, "path" => LOGIN . 'application/30days.php?p_id=' . $auto->id, "site" => $setting->mee));
                            $sent=30;
                        }
                        if($sent){
                            echo "<BR>Sending " . $sent . " day notification email: " . $auto->email;
                            $queries->query()->update()->set(['automatic_sent' => '1'])
                                ->where(['id' => $auto->id])
                                ->execute();
                        }
                    }
                }
            }

            $email="";
            if (isset($_GET["testemail"]) || $debugging) {
                $email = "/" . $this->request->session()->read('Profile.email');
            }
            //echo '<script src="'.$this->request->webroot.'"assets/global/plugins/jquery.min.js"></script>';
            echo '<TR><TH COLSPAN="3">Clients</TH></TR>';
            
            $this->set('email',$email);
            //$this->requestAction("rapid/cron" . $email);

            echo '</TABLE>';

            if (file_exists("royslog.txt")){
                echo '<BR><span style="border: 2px solid #000000;padding:3px;" onclick="document.getElementById(' . "'royslog'" . ").style.display = ''" . '"><STRONG>Click to see the contents of the log file</STRONG></span>';
                echo '<BR><SPAN ID="royslog" style="display: none;">' . str_replace("\r\n", "<BR>", file_get_contents ("royslog.txt")) . '</SPAN>';
            }
            
        }

        function ajax_cron($type, $profile_id ) {
            $path = $this->Document->getUrl();
            $setting = TableRegistry::get('settings')->find()->first();
            $profile = TableRegistry::get('profiles')->find()->where(['id'=>$profile_id])->first();
            if($profile->email) {
                $this->Mailer->handleevent("survey", array("email" => $profile->email, "username" => $profile->username, "days" => $type, "%monthsFrench%" => "mois", "%months%" => "month", "id" => $profile->id, "path" => LOGIN . 'application/' . $type . 'days.php?p_id='.$profile->id, "site" => $setting->mee));

                $queries = TableRegistry::get('Profiles');
                $queries->query()->update()->set(['automatic_sent' => '1'])
                    ->where(['id' => $profile->id])
                    ->execute();
                if($queries) {
                    echo "1";
                }
            }
            echo "0";
            die();
        }

        public function loadprofile($UserID, $fieldname = "id") {
            $table = TableRegistry::get("profiles");
            $results = $table->find('all', array('conditions' => array($fieldname => $UserID)))->first();
            if (is_object($results)) {
                return $results;
            }
            return false;
        }

        function sendtaskreminder($email, $todo, $path, $name) {
            $setting = TableRegistry::get('settings')->find()->first();
            if(is_object($todo)) {
                $this->Mailer->handleevent("taskreminder", array("title" => $todo->title, "email" => $email, "description" => $todo->description, "dueby" => $todo->date, "domain" => getHost("isbmee.com"), "site" => $setting->mee, "path" => $path
                ));
                return $email . '</TD><TD>' . $name;
            }
        }

        function getDriverById($id) {
            $q2 = TableRegistry::get('profiles');
            $que2 = $q2->find();
            $query2 = $que2->select()->where(['id' => $id])->first();
            $this->response->body($query2);
            return $this->response;
        }

        function getOrders($id) {
            $order = TableRegistry::get('orders');
            $order = $order->find()->where(['uploaded_for' => $id]);
            $this->response->body($order);
            return $this->response;
        }

        function forgetpassword() {
            $settings = TableRegistry::get('settings');
            $setting = $settings->find()->first();
            $path = $this->Document->getUrl();
            $email = str_replace(" ", "+", trim($_POST['email']));
            $profiles = TableRegistry::get('profiles');
            if ($profile = $profiles->find()->where(['LOWER(email)' => strtolower($email)])->first()) {
                //debug($profile);
                $new_pwd = $this->generateRandomString(6);
                $p = TableRegistry::get('profiles');
                if ($p->query()->update()->set(['password' => md5($new_pwd)])->where(['id' => $profile->id])->execute()) {
                    $this->Mailer->handleevent("passwordreset", array("email" =>  $profile->email, "username" => $profile->username, "password" => $new_pwd, "site" => $setting->mee));
                    echo $this->Trans->getString("email_passwordreset_subject");
                }
            } else {
                echo $this->Trans->getString("flash_invalidemail");
            }
            die();
        }

        function generateRandomString($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        function removeplus($Email, $RetPlus = false){
            $Plus = strpos($Email, "+");
            $At = strpos($Email, "@");
            if($Plus !== false){
                $Middle = substr($Email, $Plus, $At-$Plus);
                if($RetPlus){return str_replace("+", "", $Middle);}
                return str_replace($Middle, "", $Email);
            }
            if($RetPlus){return substr($Email, 0, $At);}
            return $Email;
        }

        function injectplus($Email, $Plus){
            $Email = explode("@", $this->removeplus($Email));
            return $Email[0] . '+' . $Plus . '@' . $Email[1];
        }

        function stringmask($Data, $Mask, $MaskDigit = "X"){
            $NewData = "";
            $Biggest = strlen($Mask);
            if(strlen($Data) > $Biggest){$Biggest = strlen($Data);}
            for($temp = 0; $temp < $Biggest; $temp++){
                if($temp < strlen($Mask)) {$MaskLetter = substr($Mask, $temp, 1);} else {$MaskLetter = $MaskDigit;}
                if($temp < strlen($Data)) {$DataLetter = substr($Data, $temp, 1);} else {$DataLetter = "";}
                if($MaskLetter == $MaskDigit){
                    if(strlen($DataLetter)){$NewData .= $DataLetter;}
                } else {
                    $NewData .= $MaskLetter;
                }
            }
            return $NewData;
        }

        function doop($Operation){
            /*call_user_func_array(
                array($this, $this->data['action']),
                $params
            );*/
            if(is_callable(array($this->Manager, $Operation))) {
                $this->Manager->$Operation();
                echo $Operation . " performed";
            } else {
                echo "Unable to find " . $Operation;
            }
            $this->layout = "blank";
            die();
        }


        function scrambledata(){
            if ($this->request->session()->read('Profile.super') == 1) {
                $SuperEmail = $this->removeplus($this->Manager->get_entry("profiles", 1, "super")->email);

                $Profiles = $this->Manager->enum_all("profiles", array("super" => 0));
                foreach($Profiles as $Profile){
                    if($Profile->email) {$newemail = $this->injectplus($SuperEmail, "User" . $Profile->id);} else {$newemail="";}
                    if($Profile->phone) {$newphone = $this->Manager->format_phone($this->stringmask($this->Manager->validate_data($Profile->phone, "number"), "XXX555XXXX"));} else {$newphone="";}
                    $this->Manager->update_database("profiles", "id", $Profile->id, array("email" => $newemail, "phone" => $newphone, "street" => "123 fake st", "driver_license_no" => "123-456-789", "sin" => "123-456-789"));
                }

                $this->Manager->update_database("clients", "1", "1", array("company_phone" => "", "sig_email" => "", "company_address" => "", "billing_address" => ""));
                $this->Manager->update_database("events", "1", "1", array("others_email" => ""));

                echo "Data has been scrambled to " . $SuperEmail;
            }
            $this->layout = "blank";
            die();
        }

        function cleardb() {
            if ($this->request->session()->read('Profile.super') == 1) {
                $Tables = $this->Manager->enum_tables();

                echo "Database: " . DATABASE;
                //$query = $conn->query("show tables");

                //WHITELIST//
                $this->DeleteAttachment(-1, "attachments", "/attachments/");
                $this->DeleteAttachment(-1, "client_docs", "/img/jobs/");
                $this->DeleteAttachment(-1, "profiles", "/img/profile/");
                $this->DeleteAttachment(-1, "doc_attachments", "/attachments/");
                $this->DeleteUser(-1);//deletes all users
                $this->DeleteTables(array("clients", "clientssubdocument", "client_divison", "client_sub_order", "client_products"), $Tables);//deletes clients
                //deletes documents
                $this->DeleteTables(array("audits", "consent_form", "consent_form_criminal", "documents", "driver_application", "road_test", "survey"), $Tables);
                $this->DeleteTables(array("abstract_forms", "bc_forms", "quebec_forms", "education_verification", "employment_verification", "feedbacks", "orders"), $Tables);
                $this->DeleteTables(array("driver_application_accident", "driver_application_licenses", "clientssubdocument", "mee_attachments", "training_enrollments", "training_answers"), $Tables);
                $this->DeleteTables(array("pre_screening", "generic_forms", "pre_employment_road_test", "past_employment_survey", "application_for_employment_gfs"), $Tables);
                $this->DeleteTables(array("basic_mee_platform", "events", "client_crons", "consent_form_attachments", "driver_application_attachments", "education_verification_attachments"), $Tables);
                $this->DeleteTables(array("employment_verification_attachments", "mee_attachments_more", "pre_screening_attachments", "road_test_attachments"), $Tables);

                if($Tables) {
                    echo "<BR>Untouched tables: " . implode(", ", $Tables);
                }

                //do not delete settings, contents, logos, subdocuments, order_products, color_class, client_types, profile_types, training_quiz, training_list,

                $this->DeleteDir(getcwd() . "/canvas", ".png");//deletes all signatures
                $this->DeleteDir(getcwd() . "/attachments");//deletes all document attachments
                $this->DeleteDir(getcwd() . "/img/jobs");//deletes all client pictures
                $this->DeleteDir(getcwd() . "/img/certificates", ".pdf", "certificate.jpg");//deletes pdf certificates, leaves the jpg
                $this->DeleteDir(getcwd() . "/img/profile", "", array("female.png", "male.png", "default.png"), "image");//deletes profile pics
                $this->DeleteDir(getcwd() . "/orders", "", "", "", true);//deletes the pdfs and their sub-directories
                $this->DeleteDir(getcwd() . "/pdfs");//deletes the pdfs

                //die();
                $this->layout = "blank";
            }
            die();
        }

        function DeleteTables($Table, &$Tables = array()) {
            if (is_array($Table)) {
                foreach ($Table as $Key => $table) {
                    $this->DeleteTables($table);
                    unset($Tables[$Key]);
                }
            } else {
                switch ($Table) {
                    case "clients":
                        $table = TableRegistry::get("clients")->find('all');
                        foreach ($table as $client) {
                            if($client->image) {
                                @unlink(getcwd() . "/img/jobs/" . $client->image); //delete image
                            }
                        }
                        break;
                    case "settings":
                        echo "<BR> Cannot delete settings";
                        return false;
                        break;
                }
                $this->Manager->delete_table($Table);
                echo "<BR>Deleted table: " . $Table;
            }
        }

        function DeleteUser($ID) {
            $table = TableRegistry::get("profiles");
            if ($ID == -1) {
                $users = $table->find('all', array('conditions' => array(['super' => 0])));
                foreach ($users as $user) {
                    $this->DeleteUser($user->id);
                }
                //clean up any nonexistent users still in the database
                $this->CleanUsers("blocks");
                $this->CleanUsers("sidebar");
                $this->CleanUsers("events");
                $this->CleanUsers("profilessubdocument", "profile_id");
                $this->CleanUsers("profile_docs", "profile_id");
                $this->CleanUsers("recruiter_notes", "driver_id");
                $this->CleanUsers("recruiter_notes", "recruiter_id");
                $this->CleanUsers("training_answers", "UserID");
                $this->CleanUsers("training_enrollments", "UserID");
                $this->CleanUsers("training_enrollments", "EnrolledBy");

                if (!$this->loadprofile(0)) {
                    $this->DeleteUser(0);
                }
            } else if (is_numeric($ID) > 0) {
                $user = $this->loadprofile($ID);
                if ($user) {
                    if ($user->super == 1) {
                        return false;
                    }//cannot delete supers
                    if($user->image != "default.png") {
                        @unlink(getcwd() . "/img/profile/" . $user->image);
                    }
                }//delete image
                $attachments = TableRegistry::get("profile_docs")->find('all', array('conditions' => array(['profile_id' => $ID])));
                foreach ($attachments as $attachment) {
                    $this->DeleteAttachment($attachment->id, "profile_docs", "/img/jobs/");
                }

                TableRegistry::get("blocks")->deleteAll(array('user_id' => $ID), false);
                TableRegistry::get("sidebar")->deleteAll(array('user_id' => $ID), false);
                TableRegistry::get("events")->deleteAll(array('user_id' => $ID), false);
                TableRegistry::get("profilessubdocument")->deleteAll(array('profile_id' => $ID), false);
                TableRegistry::get("recruiter_notes")->deleteAll(array('driver_id' => $ID), false);
                TableRegistry::get("recruiter_notes")->deleteAll(array('recruiter_id' => $ID), false);
                TableRegistry::get("training_answers")->deleteAll(array('UserID' => $ID), false);
                TableRegistry::get("training_enrollments")->deleteAll(array('UserID' => $ID), false);

                $table->deleteAll(array('id' => $ID), false);
                echo "<BR>Deleted User: " . $ID;
            }
        }

        function CleanUsers($tablename, $fieldname = "user_id") {
            $table = TableRegistry::get($tablename);
            $users = $table->find('all');
            foreach ($users as $user) {
                $user2 = $this->loadprofile($user->$fieldname);
                if (!is_object($user2)) {//delete any non-existent profile
                    $this->DeleteUser($user->$fieldname);
                }
            }
        }

        function DeleteDir($path, $like = "", $notlike = "", $fieldname = "", $recursive = false) {
            if (is_dir($path)) {
                $files = scandir($path);
                echo "<BR>Deleting Directory: " . $path;
                foreach ($files as $file) { // iterate files
                    $doit = true;
                    if ($file != "." && $file != "..") {
                        if ($fieldname) {//blocks the delete of any file that can be found in profiles.$fieldname
                            if ($this->loadprofile($file, $fieldname)) {
                                $doit = false;
                            }
                        }
                        if ($like) {//only allows the delete of any file containing $like
                            if (is_array($like)) {
                                $doit = false;
                                foreach ($like as $pattern) {
                                    if ($file == $pattern || stripos($file, $pattern)) {
                                        $doit = true;
                                    }
                                }
                            } else {
                                $doit = $file == $like || stripos($file, $like);
                            }
                        }
                        if ($notlike) {//blocks the delete of any file containing $notlike
                            if (is_array($notlike)) {
                                foreach ($notlike as $pattern) {
                                    if ($file == $pattern || stripos($file, $pattern)) {
                                        $doit = false;
                                    }
                                }
                            } else {
                                if ($file == $notlike || stripos($file, $notlike)) {
                                    $doit = false;
                                }
                            }
                        }
                        if ($doit) {//if approved, delete the file
                            $file = $path . "/" . $file;
                            if (is_file($file)) {// delete file}
                                unlink($file);
                                echo "<BR>Deleting file: " . $file;
                            } else if ($recursive && is_dir($file)) {//deletes sub directories
                                $this->DeleteDir($file, $like, $notlike, $fieldname, $recursive);
                                rmdir($file);
                            }
                        }
                    }
                }
            } else {
                echo "<BR>" . $path . " Was not a directory";
            }
        }

        function DeleteAttachment($ID, $TableName = 'attachments', $Path = "/attachments/") {//$ID=-1 deletes all attachments
            $table = TableRegistry::get($TableName);
            if ($ID == -1) {
                echo "<BR>Deleting all attachments from " . $TableName;
                $table = $table->find('all');
                foreach ($table as $attachment) {
                    $this->DeleteAttachment($attachment->id, $TableName, $Path);
                }
            } else {
                $attachment = $table->find()->where(['id' => $ID])->first();
                $filename = "";
                if (isset($attachment->title)) {
                    $filename = $attachment->title;
                }
                if (isset($attachment->file)) {
                    $filename = $attachment->file;
                }
                if (isset($attachment->attachment)) {
                    $filename = $attachment->attachment;
                }
                if ($filename) {
                    if (file_exists(getcwd() . $Path . $filename) && is_file(getcwd() . $Path . $filename)) {
                        echo "<BR>Deleted file " . $Path . $filename;
                        unlink(getcwd() . $Path . $filename);
                    }
                } else {
                    echo "<BR>No file to delete " . $ID . " in " . $TableName;
                }
                if ($TableName != "profiles") $table->deleteAll(array('id' => $ID), false);
            }
        }

        function sproduct($id = '0'){
            if (isset($_POST)) {
                $p = TableRegistry::get('order_products');
                $title = $_POST['title'];
                if ($id != 0) {
                    if ($p->query()->update()->set(['title' => $title])->where(['id' => $id])->execute()) {
                        echo $title;
                    }
                } else {
                    $profile = $p->newEntity($_POST);
                    if ($p->save($profile)) {
                        echo '<tr>
                            <!--td>' . $profile->id . '</td-->
                            <td class="title_' . $profile->id . '">' . $title . '</td>
                            <td><input type="checkbox" id="chk_' . $profile->id . '" class="enable"/></td>
                            <td><span  class="btn btn-primary editpro" id="edit_' . $profile->id . '">' . $this->Trans->getString("dashboard_edit") . '</span></td>
                        </tr>';
                    }
                }
            }
            die();
        }

        function ptypes($id = '0'){
            if (isset($_POST)) {
                $p = TableRegistry::get('profile_types');
                $data = array();
                $languages = explode(",", $_POST["languages"]);
                foreach($languages as $language){
                    if($language=="English"){$language="";}
                    $data['title' . $language] = $_POST['title' . $language];
                }

                if ($id != -1) {
                    if ($p->query()->update()->set($data)->where(['id' => $id])->execute()) {
                        echo $data['title'];
                    }
                } else {
                    $profile = $p->newEntity($_POST);
                    if ($p->save($profile)) {
                        echo '<tr><td>' . $profile->id . '</td>';
                        foreach($languages as $language) {
                            if ($language == "English") {$language = "";}
                            echo '<td class="titleptype' . $language . '_' . $profile->id . '">' . $data['title' . $language] . '</td>';
                        }
                        echo '    <td><input type="checkbox" id="pchk_' . $profile->id . '" class="penable"/><span class="span_' . $profile->id . '"></span></td>
                        <td><input type="checkbox" class="oenable" id="ochk_' . $profile->id . '" /><span class="span2_' . $profile->id . '"></span></td>
                        <td><span  class="btn btn-primary editptype" id="editptype_' . $profile->id . '">' .  $this->Trans->getString("dashboard_edit") . '</span></td>
                    </tr>';
                    }
                }
            }
            die();
        }

        function ctypes($id = '0'){
            if (isset($_POST)) {
                $table = TableRegistry::get('client_types');
                $data = array();
                $languages = explode(",", $_POST["languages"]);
                foreach($languages as $language){
                    if($language=="English"){$language="";}
                    $data['title' . $language] = $_POST['title' . $language];
                }

                if ($id != 0) {
                    if ($table->query()->update()->set($data)->where(['id' => $id])->execute()) {
                        print_r($data);
                    }
                } else {
                    $profile = $table->newEntity($_POST);
                    if ($table->save($profile)) {
                        echo '<tr><td>' . $profile->id . '</td>';
                        foreach($languages as $language) {
                            if ($language == "English") {$language = "";}
                            echo '<td class="titlectype' . $language .'_' . $profile->id . '">' . $data["title" . $language] . '</td>';
                        }
                        echo '     <td><input type="checkbox" id="cchk_' . $profile->id . '" class="cenable"/><span class="span_' . $profile->id . '"></span></td>
                            <td><span  class="btn btn-primary editctype" id="editctype_' . $profile->id . '">' . $this->Trans->getString("dashboard_edit") . '</span></td>
                        </tr>';
                    }
                }
            }
            die();
        }

        function enableproduct($id) {
            $p = TableRegistry::get('order_products');
            $enable = $_POST['enable'];
            if ($p->query()->update()->set(['enable' => $enable])->where(['id' => $id])->execute()) {
                echo $enable;
            }
            die();
        }

        function ptypesenable($id, $field = "enable") {
            $p = TableRegistry::get('profile_types');
            $enable = $_POST['enable'];
            if ($p->query()->update()->set([$field => $enable])->where(['id' => $id])->execute()) {
                if ($enable == '1') {
                    echo "Added";
                }else{
                    echo "Removed";
                }
            }

            die();
        }

        function ctypesenable($id){
            $p = TableRegistry::get('client_types');
            $enable = $_POST['enable'];
            if ($p->query()->update()->set(['enable' => $enable])->where(['id' => $id])->execute()) {
                if ($enable == '1') {
                    echo "Added";
                }else {
                    echo "Removed";
                }
            }
            die();
        }

        function ctypesenb($id){
            $ctype = "";
            foreach ($_POST['ctypes'] as $k => $v) {
                if (count($_POST['ctypes']) == $k + 1) {
                    $ctype .= $v;
                }else {
                    $ctype .= $v . ",";
                }
            }
            $p = TableRegistry::get('profiles');
            $p->query()->update()->set(['ctypes' => $ctype])->where(['id' => $id])->execute();
            die();
        }

        function ptypesenb($id){
            $ptype = "";
            foreach ($_POST['ptypes'] as $k => $v) {
                if (count($_POST['ptypes']) == $k + 1) {
                    $ptype .= $v;
                }else {
                    $ptype .= $v . ",";
                }
            }
            $p = TableRegistry::get('profiles');
            $p->query()->update()->set(['ptypes' => $ptype])->where(['id' => $id])->execute();
            die();
        }

        function gettypes($type, $uid){
            $p = TableRegistry::get('profiles');
            $profile = $p->find()->where(['id' => $uid])->first();
            if ($type == 'ptypes') {
                $this->response->body(($profile->ptypes));
            } elseif ($type == "ctypes") {
                $this->response->body(($profile->ctypes));
            }
            return $this->response;
        }

        public function appendtext($Start, $Finish, $Delimeter = ","){
            if($Start) { return $Start . $Delimeter . $Finish; }
            return $Finish;
        }

        public function appendattachments($query){
            foreach ($query as $client) {
                $client->hasattachments = $this->hasattachments($client->id);
            }
            return $query;
        }

        public function hasattachments($id){
            $docs = TableRegistry::get('profile_docs');
            $query = $docs->find();
            $client_docs = $query->select()->where(['profile_id' => $id])->first();
            if ($client_docs) {
                return true;
            }
        }

        public function getTypeTitle($id, $language = "English"){
            $docs = TableRegistry::get('profile_types');
            $query = $docs->find()->where(['id' => $id])->first();
            if ($query) {
                $fieldname = $this->getFieldname("title",$language);
                $q = $query->$fieldname;
            }else {
                $q = '';
            }
            $this->response->body($q);
            return $this->response;
        }

        function getFieldname($Fieldname, $Language){
            if($Language == "English" || $Language == "Debug"){ return $Fieldname; }
            return $Fieldname . $Language;
        }

        function producteditor(){
            if(isset($_GET["Delete"])){
                TableRegistry::get('product_types')->deleteAll(array('Acronym'=>$_GET["Delete"]), false);
                $this->Flash->success($this->Trans->getString("flash_productdeleted", array("%Name%" => $_GET["Delete"])));
            }
            if(isset($_GET["Name"])){
                $this->SaveFields('product_types', $_GET, "Acronym");
            }

            $this->set("producttypes",  TableRegistry::get('product_types')->find('all') );
            $this->set("colors", TableRegistry::get('color_class')->find('all') );

            $this->set("blockscols", $this->getColumnNames('blocks'), "", true);
            $this->set("sidebarcols", $this->getColumnNames('sidebar'), "", true);

            $this->set("order_products",  TableRegistry::get('order_products')->find('all') );
            $this->set("subdocuments", TableRegistry::get('subdocuments')->find('all') );

            $this->set("profile_types",  TableRegistry::get('profile_types')->find()->select()->where(['placesorders' => 1]) );
        }

        function SaveFields($Table, $Data, $PrimaryKey, $Default = "0", $Ignore = "ID"){
            //detect unchecked checkboxes
            $Columns = $this->getColumnNames($Table, $Ignore);
            foreach($Columns as $Column => $ColumnType){
                if (!isset($Data[$Column])){
                    $Data[$Column] = $Default;
                }
            }
            $Table = TableRegistry::get($Table);
            $exists =  $Table->find('all', array('conditions' => array([$PrimaryKey => $Data[$PrimaryKey]])))->first();
            unset($Data['submit']);

            if($exists){
                $Table->query()->update()->set($Data)->where([$PrimaryKey => $Data[$PrimaryKey]])->execute();
                $this->Flash->success($this->Trans->getString("flash_productupdated", array("%Name%" => $Data[$PrimaryKey])));
            } else {
                $Table->query()->insert(array_keys($Data))->values($Data)->execute();
                $this->Flash->success($this->Trans->getString("flash_productcreated", array("%Name%" => $Data[$PrimaryKey])));
            }
        }

        function getColumnNames($Table, $ignore = "", $justColumnNames = false){
            $Columns = TableRegistry::get($Table)->schema();
            $Data = $this->getProtectedValue($Columns, "_columns");
            if ($Data) {
                if (is_array($ignore)) {
                    foreach ($ignore as $value) {
                        unset($Data[$value]);
                    }
                } elseif ($ignore) {
                    unset($Data[$ignore]);
                }
                if ($justColumnNames){
                    return array_keys($Data);
                }
                return $Data;
            }
            //}
        }
        function getProtectedValue($obj,$name) {
            $array = (array)$obj;
            $prefix = chr(0).'*'.chr(0);
            if (isset($array[$prefix.$name])) {
                return $array[$prefix . $name];
            }
        }

        function getDriverProv($driver) {
            $dri = TableRegistry::get('profiles')->find()->where(['id'=>$driver])->first();
            $pro = $dri->driver_province;
            $this->response->body($pro);
            return $this->response;
        }


        function jsonschema(){
            $this->set("tables", $this->Manager->enum_tables());
            if (isset($_GET["table"])){
                $this->set("columns", getColumnNames($_GET["table"], "", false));
                $this->set("test", $this->Manager->enum_table($_GET["table"])->first());
            }

            $JSON=$this->Manager->get("action");
            if($JSON) {
                $JSON="";
                $HTML = false;
                if (isset($_POST["JSON"])) {$JSON = $_POST["JSON"];}
                switch ($this->Manager->get("action")) {
                    case "order_to_json";
                        $JSON = $this->Manager->order_to_json($_GET["OrderID"]);
                        break;
                    case "profile_to_json";
                        $JSON = $this->Manager->profile_to_array($_GET["ProfileID"], true, true);
                        break;
                    case "json_to_profile";
                        $JSON = $this->convertedto($this->Manager->json_to_profile($JSON), "Profile");
                        break;
                    case "json_to_order";
                        $JSON = $this->convertedto($this->Manager->json_to_order($JSON), "Order");
                        break;
                    case "json_to_html";
                        $JSON = $this->Manager->json_to_html($JSON);
                        $HTML = true;
                        break;
                    case "order_to_html";
                        $JSON = $this->Manager->order_to_email($_GET["OrderID"]);
                        $HTML = true;
                        break;
                    case "validate_all";
                        $this->validate_all_data();
                        $JSON = "Done!";
                    default;
                        $JSON = $this->Manager->get("action") . " is unhandled";
                }
                $this->set("JSON", $JSON);
                $this->set("HTML", $HTML);
            }
        }
        function convertedto($JSON, $Type){
            if($JSON) {
                return "JSON converted to " . $Type . " ID: " . $JSON;
            } else {
                return "The JSON was not a valid " . $Type;
            }
        }

        function validate_all_data(){
            $Tables = array();//TableName = array(ColumnName => RuleName); (If columname contains the rule name, you don't need to specify it)
            //Rules: number, alphabetic, alphanumeric, ip, mac, url, email, postalcode, phone, sin, zipcode, postalzip

            $Tables["application_for_employment_gfs"] = array("phone", "code" => "postalcode");
            $Tables["clients"] = array("company_phone", "postal" => "postalzip", "admin_email", "admin_phone");
            $Tables["consent_form"] = array("phone", "current_postal_code", "previous_postal_code", "applicants_email", "criminal_current_postal_code");
            $Tables["driver_application"] = array("social_insurance_number" => "sin", "postal_code", "past3_postal_code1", "past3_postal_code2", "phone", "mobile" => "phone", "email", "emergency_notify_phone");
            $Tables["education_verification"] = array("supervisior_phone", "supervisior_email", "supervisior_secondary_email");
            $Tables["employment_verification"] = array("supervisior_phone", "supervisior_email", "supervisior_secondary_email");
            $Tables["footprint"] = array("custemail", "email", "email1", "postal", "work_phone", "home_phone", "cell_phone");
            $Tables["investigations_intake_form_benefit_claims"] = array("email");
            $Tables["pre_screening"] = array("applicant_phone_number", "applicant_email");
            $Tables["profiles"] = array("email", "postal", "phone", "applicants_email");
            $Tables["quebec_forms"] = array("postal_code", "telephone", "area_code" => "number", "extension" => "number", "postal_code1", "area_code1" => "number", "telephone1", "extension1" => "number", "tel_home" => "phone", "tel_work" => "phone");

            $DataPoints = 0;
            foreach($Tables as $Name => $Table){
                $Rows = $this->Manager->enum_all($Name);
                $PrimaryKey = $this->Manager->get_primary_key($Name);
                foreach($Rows as $Row) {
                    $NewData = array();
                    foreach ($Table as $Column => $DataType) {
                        if(is_numeric($Column)){
                            $Column = $DataType;
                            $DataType="";
                            if (strpos(strtolower($Column), "email") !== false){$DataType = "email";}
                            if (strpos(strtolower($Column), "phone") !== false){$DataType = "phone";}
                            if (strpos(strtolower($Column), "postal") !== false){$DataType = "postalcode";}
                        }
                        if($DataType) {
                            $Data = $this->Manager->validate_data($Row->$Column, $DataType);
                            if ($Data != $Row->$Column) {$NewData[$Column] = $Data;}
                        }
                    }
                    if($NewData) {
                        $DataPoints=$DataPoints+count($NewData);
                        echo "UPDATE " . print_r($NewData, true) . " WHERE " . $Name . "." . $PrimaryKey . ' = ' . $Row->$PrimaryKey . '<BR>';
                        $this->Manager->update_database($Name, $PrimaryKey, $Row->$PrimaryKey, $NewData);//uncomment to write to the database
                    }
                }
            }
            return $DataPoints;
        }

        public function huron($cid) {
            $file = fopen(APP."../webroot/profile.csv","r");
            $fields = array('title',
                'fname',
                'lname',
                'username',
                'email',
                'password',
                'driver',
                'address',
                'street',
                'city',
                'province',
                'postal',
                'country',
                'phone',
                'image',
                'admin',
                'super',
                'profile_type',
                'driver_license_no',
                'driver_province',
                'us_dot',
                'applicants_email',
                'transclick',
                'mname',
                'dob',
                'expiry_date',
                'gender',
                'isb_id',
                'placeofbirth',
                'created_by',
                'created',
                'drafts',
                'is_hired',
                'ptypes',
                'ctypes',
                'language',
                'automatic_email',
                'automatic_sent',
                'hear',
                'requalify',
                'hired_date',
                'emailsent',
                'send_to',
                'sin',
                'otherinfo');

            $mon = array('Jan'=>'01','Feb'=>'02','Mar'=>'03','Apr'=>'04','May'=>'05','Jun'=>'06','Jul'=>'07','Aug'=>'08','Sep'=>'09','Oct'=>'10','Nov'=>'11','Dec'=>'12');

            while($arrs = fgetcsv($file)) {
                //var_dump($arrs);die();
                foreach($arrs as $k=>$arr){
                    if($k==0) {
                        continue;
                    }else{
                        // var_dump($arr);die();
                        $pro[$fields[$k-1]] = $arr;
                        if($fields[$k-1] == 'dob') {
                            //echo $arr;die();
                            $arr = str_replace(array('Sept','April'),array('Sep','Apr'),$arr);
                            $date = explode(' ',$arr);
                            if(isset($date[2])) {
                                if($date[1]>=10) {
                                    $pro[$fields[$k - 1]] = $date[2] . '-' . $mon[$date[0]] . '-' . $date[1];
                                }else {
                                    $pro[$fields[$k - 1]] = $date[2] . '-' . $mon[$date[0]] . '-0' . $date[1];
                                }
                            } else {
                                $pro[$fields[$k - 1]] = '';
                            }
                        }
                    }
                }




                $profiles = TableRegistry::get('Profiles');
                $profile = $profiles->newEntity($pro);
                if ($profiles->save($profile)) {
                    if ($cid != "") {
                        $client_id = array($cid);
                        foreach ($client_id as $cid) {
                            $this->Manager->assign_profile_to_client($profile->id, $cid);
                        }
                    }

                    $this->Manager->makepermissions($profile->id, "blocks", $profile->profile_type);
                    $this->Manager->makepermissions($profile->id, "sidebar", $profile->profile_type);
                }
            }
            die();
        }


        function notify($UserID, $ProfileType = 2, $Event = "notify"){//recruiter
            if ($UserID == $this->Manager->read("id")){return false;}//don't do it for yourself
            $AssignedClients = $this->Manager->find_client($UserID, false);
            $Profile = $this->Manager->get_profile($UserID);
            $Name = $this->formatname($Profile);

            if($AssignedClients) {
                if (!is_array($AssignedClients)) {$AssignedClients = array($AssignedClients);}
                $Clients = $this->Manager->enum_all('clients', array("id IN(" . implode(",", $AssignedClients) . ")"));
                $Emails = array();
                foreach ($Clients as $Client) {
                    if($Client->profile_id) {
                        if(is_numeric($ProfileType)){
                            $Profiles = $this->Manager->enum_all("profiles", array($Profile->profile_type . " IN (ptypes)", "profile_type IN (" . $ProfileType . ")", "id IN (" . $Client->profile_id . ")"));
                        } else {
                            $Profiles = $this->Manager->enum_all("profiles", array($Profile->profile_type . " IN (ptypes)", "id IN (SELECT user_id FROM sidebar WHERE user_id IN (" . $Client->profile_id . ") AND " . $ProfileType . " = 1 )"));
                        }
                        foreach($Profiles as $Profile){
                            if($Profile->email){
                                $Emails[] = $Profile->email;
                            }
                        }
                    }
                }
                $Emails = array_unique($Emails);
                $Emails[] = "super";
                if($Emails){
                    $Path = LOGIN . 'profiles/view/' . $UserID;
                    $this->Manager->handleevent($Event, array("email" => $Emails, "name" => $Name, "path" => $Path, "userid" => $UserID, "byuserid" => $this->Manager->read("id"), "byname" => $this->formatname()));
                }
            }
        }

        function formatname($UserID = false, $Format = "%fname% %mname% %lname% (%username%) %email%"){
            if(!is_object($UserID)) {$UserID = $this->Manager->get_profile($UserID);}
            return $this->formatobject($UserID, $Format);
        }

        function formatobject($Object, $Format){
            if(is_object($Object)){$Object = $this->Manager->properties_to_array($Object);}
            foreach($Object as $Key => $Value){
                $Format = str_replace( "%" . $Key . "%", $Value, $Format);
            }
            return $Format;
        }

        function getdistinctfields($Table, $Field){
            $Results = TableRegistry::get($Table)->find('all', array('fields' => $Field, 'group' =>  $Field));
            $Ret = array();
            foreach($Results as $Result){
                $Ret[] = $Result->$Field;
            }
            return $Ret;
        }
        function changeExp($uid,$stat)
        {
            $img = TableRegistry::get('profiles');

                    //echo $s;die();
                    $query = $img->query();
                    $query->update()
                        ->set(['us_driving_experience'=>$stat])
                        ->where(['id' => $uid])
                        ->execute();
                        die();
        }
        function check_exp($uid){
            $img = TableRegistry::get('profiles')->find()->where(['id'=>$uid])->first();
            if($img->us_driving_experience)
            {
                echo '0';
            }
            else
            echo '1';
            die();
        }

    }
?>
