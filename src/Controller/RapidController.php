<?php
    namespace App\Controller;

    use App\Controller\AppController;
    use Cake\Event\Event;
    use Cake\Controller\Controller;
    use Cake\ORM\TableRegistry;
    use Cake\Network\Email\Email;
    use Cake\Controller\Component\CookieComponent;
    use Cake\Datasource\ConnectionManager;

    class RapidController extends AppController
    {
        public function initialize()
        {
            parent::initialize();
            $this->loadComponent('Mailer');
            $this->loadComponent('Document');
        }

        public function index()
        {
            $this->set('uid', '0');
            $this->set('id', '0');

            $profiles = TableRegistry::get('Profiles');

            $_POST['created'] = date('Y-m-d');
            //var_dump($profile);die();

            if ($this->request->is('post')) {

                if (isset($_POST['profile_type']) && $_POST['profile_type'] == 1) {
                    $_POST['admin'] = 1;
                }

                $_POST['dob'] = $_POST['dob'];//what?
                if ($_POST['title'] == "Mr.") {
                    $_POST["gender"] = "Male";
                } else {
                    $_POST["gender"] = "Female";
                }

                $profile = $profiles->newEntity($_POST);

                $profilesToEmail = array();

                if ($profiles->save($profile)) {
                    if (!$_POST['username']) {//if no username, make one
                        $profile_id = $profile->id;
                        $_POST['username'] = "Driver_" . $profile_id;
                        $this->Update1Column("profiles", "email", $_POST['email'], "username", $_POST['username']);
                    }

                    if ($_POST['client_ids']) {
                        $client_id = explode(",", $_POST['client_ids']);
                        foreach ($client_id as $cid) {//asign to clients
                            $this->Manager->assign_profile_to_client($profile->id, $cid);
                        }
                    }

                    $this->Manager->makepermissions($profile->id, "blocks", $profile->profile_type);
                    $this->Manager->makepermissions($profile->id, "sidebar", $profile->profile_type);

                    $this->emaileveryone($profilesToEmail, $profile->id, $_POST);
                    return $this->redirect('/application/register.php?client=' . $_POST['client_ids'] . '&username=' . $_POST['username'] . '&userid=' . $profile->id);
                } else {
                    return $this->redirect('/application/register.php?client=' . $_POST['client_ids'] . '&error=' . $_POST['username']);
                }
            }
            die();
        }

        function days($type = "", $ClientID = 26)
        {
            $train = "";
            if (isset($_POST)) {
                $_POST['created'] = date('Y-m-d');
                if ($type == '60') {
                    if (isset($_POST['train']))
                        foreach ($_POST['train'] as $k => $values) {
                            if (($k + 1) == count($_POST['train'])) {
                                $train .= $values;
                            } else
                                $train .= $values . ",";
                        }
                    $_POST['jst13'] = $train;
                }
                $path = $this->Document->getUrl();
                $modal = TableRegistry::get($type . 'days');
                $data = $modal->newEntity($_POST);
                $settings = TableRegistry::get('settings');
                $setting = $settings->find()->first();
                if ($modal->save($data)) {
                    $from = array('info@' . $path => $setting->mee);
                    $pro = TableRegistry::get('profiles')->find()->where(['id' => $_POST['profile_id']])->first();
                    $emails = $this->getallrecruiters($ClientID);
                    $path = LOGIN . "application/" . $type . "days.php?p_id=" . $_POST['profile_id'] . "&form_id=" . $data->id;
                    $site = TableRegistry::get('settings')->find()->first()->mee;//, "site" => $site

                    foreach ($emails as $e) {
                        $this->Mailer->handleevent("surveycomplete", array("email" => $e, "username" => $pro->username, "type" => $type, "path" => $path, "site" => $site));
                    }
                    $this->Mailer->handleevent("surveycomplete", array("email" => "super", "username" => $pro->username, "type" => $type, "path" => $path, "site" => $site));
                    return $this->redirect('/application/' . $type . "days.php?msg=success");
                } else
                    return $this->redirect('/application/' . $type . "days.php?msg=error");

            }
            die();
        }

        function getallrecruiters($cid)
        {
            $email = array();
            $pros = $this->Manager->get_clients_profiles($cid);
            $profiles = TableRegistry::get('profiles')->find('all')->where(['id in(' . $pros . ')']);
            foreach ($profiles as $p) {
                if ($p->profile_type == '2' && $p->email != "") {
                    array_push($email, $p->email);
                }
            }
            return $email;
        }

        public function emaileveryone($profilesToEmail, $ProfileID, $POST)
        {
            //   $settings = $this->Settings->get_settings();
            $emails = array();
            foreach ($profilesToEmail as $Profile) {
                $Profile = $this->Manager->loadpermissions($Profile, "sidebar");// $this->getTableByAnyKey("sidebar", "user_id", $Profile);
                if (is_object($Profile) && $Profile->email_profile == 1) {
                    $emails[] = $Profile->email;
                }
            }
            $path = LOGIN . "profiles/view/" . $ProfileID;
            $this->Mailer->handleevent("profilecreated", array("username" => $_POST['username'], "email" => $emails, "path" => $path, "createdby" => "Application", "type" => "Applicant", "password" => "[Blank]", "id" => $ProfileID));
        }

        public function Update1Column($Table, $PrimaryKey, $PrimaryValue, $Key, $Value)
        {
            TableRegistry::get($Table)->query()->update()->set([$Key => $Value])->where([$PrimaryKey => $PrimaryValue])->execute();
        }

        public function getTableByAnyKey($Table, $Key, $Value)
        {
            return TableRegistry::get($Table)->find('all', array('conditions' => array($Key => $Value)))->first();
        }

        function checkcron($cid, $date, $pid)
        {
            $client_crons = TableRegistry::get('client_crons');
            $cnt = $client_crons->find('all')->where(['client_id' => $cid, 'orders_sent' => '1', 'cron_date' => $date, 'profile_id' => $pid])->count();
            return $cnt;
        }

        function cron($IsDebug = false)
        {
            if (isset($_GET['blank']))
                $this->layout = 'blank';
            $today = date('Y-m-d');
            $msg = "";
            $clients = TableRegistry::get('clients')->find('all')->where(['requalify' => '1', 'requalify_product <> ""']);
            $marr = array();
            $a = TableRegistry::get('profiles')->find()->where(['super' => '1'])->first();
            $admin_email = $a->email;
            $user_count = 0;

            foreach ($clients as $c) {
                $pro = '';
                $msg .= "<br/><br/><strong>Client:</strong><br/>";
                $msg .= $c->company_name;
                $msg .= "<br/>";
                $message = "Your drivers have been re-qualified." . "</br>";
                $message .= "Re-qualified Date:" . $today . "</br>";
                $em_names = '';
                $pronames = array();
                $em = array();

                if ($c->requalify_re == '0') {
                    $date = $c->requalify_date;
                }

                $frequency = $c->requalify_frequency;
                $forms = $c->requalify_product;
                $fname = explode(',', $forms);
                $new_form = "";
                foreach ($fname as $n) {
                    if ($n == '1') {
                        $nam = '(MVR)';
                    } elseif ($n == '14') {
                        $nam = '(CVOR)';
                    } elseif ($n == '72') {
                        $nam = '(DL)';
                    }
                    $new_form .= $nam . ",";

                }
                $new_form = substr($new_form, 0, strlen($new_form) - 1);
                $msg .= "Selected Forms:" . $new_form . "<br/>";
                //$nxt_sec = strtotime($today)+($frequency*24*60*60*30);
                //$nxt_date = date('Y-m-d', strtotime('+'.$frequency.' months'));
                $epired_profile = '';
                $p_type = '';
                $p_name = "";
                $emails = '';
                $profile_type = TableRegistry::get("profile_types")->find('all')->where(['placesorders' => 1]);
                foreach ($profile_type as $ty) {
                    $p_type .= $ty->id . ",";
                }
                $p_types = substr($p_type, 0, strlen($p_type) - 1);

                $crons = TableRegistry::get('client_crons');
                $profile = TableRegistry::get('profiles')->find('all')->where(['id IN(' . $c->profile_id . ')', 'profile_type IN(' . $p_types . ')', 'is_hired' => '1', 'requalify' => '1'])->order('created_by');
                //debug($profile);die();
                $temp = '';
                foreach ($profile as $p) {

                    if ($p->expiry_date == '') {
                        $p->expiry_date = '0000-00-00';
                    }
                    //echo $p->expiry_date."<br/>" ;
                    //echo strtotime($p->expiry_date)."<br/>".time();
                    //if (($p->profile_type == '5' || $p->profile_type == '7' || $p->profile_type == '8')) {
                    //echo $p->id."</br>";
                    //echo $p->created_by;

                    //Test for expired profile
                    /*if (strtotime($p->expiry_date) < strtotime($today)) {
                        $epired_profile .= $p->username . ",";

                    } else {
                    */

                    if (true) {

                        if ($c->requalify_re == '1') {

                            $date = $p->hired_date;
                            if (strtotime($date) <= strtotime($today)) {

                                if (strtotime($date) == strtotime($today)) {
                                    $date = $this->getnextdate($date, $frequency);
                                    if ($this->checkcron($c->id, $date, $p->id)) {
                                        $date = $this->getnextdate($date, $frequency);
                                    }
                                } else {
                                    $date = $this->getnextdate($date, $frequency);

                                    if ($this->checkcron($c->id, $date, $p->id)) {
                                        $date = $this->getnextdate($date, $frequency);
                                    }

                                }
                            } else {
                                continue;
                            }
                        }
                        //echo $date;die();
                        $nxt_date = $this->getnextdate($date, $frequency);

                        if ($today == $date || $today == $nxt_date) {

                            $cron_p = $crons->find()->where(['profile_id' => $p->id, 'client_id' => $c->id, 'orders_sent' => '1', 'cron_date' => $today])->first();
                            if (count($cron_p) == 0) {
                                $user_count++;
                                $pro .= $p->id . ",";
                                if ($temp == $p->created_by) {
                                    $p_name .= $p->username . ",";
                                } else {
                                    if ($temp != "") {

                                        array_push($pronames, $p_name);
                                        $p_name = "";
                                    }
                                    $temp = $p->created_by;
                                    $p_name .= $p->username . ",";
                                    //echo "<br/>";
                                }
                            }

                            $rec = TableRegistry::get('profiles')->find()->where(['id' => $p->created_by])->first();
                            if ($rec->email) {
                                $rec_email = $rec->email;
                                array_push($em, $rec->email);
                            }

                        }
                        //} //for else condition
                    }
                }
                //die();
                array_push($pronames, $p_name);
                //var_dump($pronames);
                $em = array_unique($em);
                $i = 0;
                $username = substr($pronames[$i], 0, strlen($pronames[$i]) - 1);
                $mesg = "Selected Forms:" . $new_form . "<br/>";
                $new_form = trim(substr($pronames[$i], 0, strlen($pronames[$i]) - 1));
                if ($new_form) {
                    $mesg .= "Profile(s): '" . $new_form . "' have been re-qualified on " . $today . " for client: " . $c->company_name . ".<br /><br />Click <a href='" . LOGIN . "'>here</a> to login to view the reports.<br /><br />Regards,<br />The MEE Team";
                    $footer = "";
                    //echo $epired_profile; die();
                    //expired profile test
                    /*if ($epired_profile != "") {
                        $mesg .= "<br/>Expired Profiles:" . $epired_profile;
                    }*/
                    if ($IsDebug)
                        foreach ($em as $e) {
                            $this->Mailer->handleevent("requalification", array("email" => $e, "company_name" => $c->company_name, "username" => $username, "expired" => $epired_profile));
                            //$this->Mailer->sendEmail("", $e, "Driver Re-qualified (" . $c->company_name . ")", $mesg);

                            $emails .= $e . ",";
                            $i++;
                        }
                    unset($em);
                }
                $epired_profile = "";
                $p_newname = '';
                foreach ($pronames as $p) {
                    $p_newname .= $p . ",";
                }
                //die();
                $em_names = substr($em_names, 0, strlen($em_names) - 1);
                $emails = substr($emails, 0, strlen($emails) - 1);
                $pro = substr($pro, 0, strlen($pro) - 1);
                $p_name = substr($p_newname, 0, strlen($p_newname) - 1);
                //$this->bulksubmit($pro,$forms,$c->id);
                $msg .= "Profiles:" . str_replace(',,', ',', $p_name) . "<br/>";
                $msg .= "Emails Sent to:" . $emails . "<br/>";
                $message .= "Recruited Profiles:" . $em_names . "</br>";

                if ($pro != "") {
                    $drivers = explode(',', $pro);
                    //$forms = $_POST['forms'];
                    $arr['forms'] = $forms;
                    $arr['order_type'] = 'REQ';
                    $arr['draft'] = 0;
                    $arr['title'] = 'order_' . date('Y-m-d H:i:s');

                    $arr['client_id'] = $c->id;
                    $arr['created'] = date('Y-m-d H:i:s');
                    //$arr['division'] = $_POST['division'];
                    //$arr['user_id'] = $this->request->session()->read('Profile.id');
                    $arr['driver'] = '';
                    $arr['order_id'] = '';

                    foreach ($drivers as $driver) {

                        $arr['uploaded_for'] = $driver;
                        $ord = TableRegistry::get('orders');
                        $doc = $ord->newEntity($arr);
                        $ord->save($doc);

                        //$this->webservice('BUL', $arr['forms'], $arr['user_id'], $doc->id);
                        if ($arr['driver']) {
                            $arr['driver'] = $arr['driver'] . ',' . $driver;
                        } else {
                            $arr['driver'] = $driver;
                        }
                        if ($arr['order_id']) {
                            $arr['order_id'] = $arr['order_id'] . ',' . $doc->id;
                        } else {
                            $arr['order_id'] = $doc->id;
                        }

                        $cron['order_id'] = $doc->id;
                        $cron['profile_id'] = $driver;
                        $cron['orders_sent'] = '1';
                        $cron['cron_date'] = $today;
                        $cron['client_id'] = $c->id;

                        $s = $crons->newEntity($cron);
                        $crons->save($s);
                        unset($cron);

                    }
                    array_push($marr, $arr);
                }

                unset($arr);
                unset($rec);

            }

            if ($user_count != 0) {

                // $this->Mailer->sendEmail("", $admin_email, 'Driver Re-qualification Cron', "Cron date:" . $today . "</br>" . $msg);

            }

            $this->set('profiles', $user_count);
            $this->set('arrs', $marr);
            $this->set('msg', $msg);
            $this->set('message', $message);

        }

        function getnextdate($date, $frequency)
        {
            $today = date('Y-m-d');//                              24 hours * 60 minutes * 60 seconds * 30 days
            $days = $frequency * 30;
            $d = "+" . $days . " days";
            $nxt_date = date('Y-m-d', strtotime(date('Y-m-d', strtotime($date)) . $d));

            if (strtotime($nxt_date) < strtotime($today)) {
                $d = $this->getnextdate($nxt_date, $frequency);
            } else {
                $d = $nxt_date;
            }
            return $d;
        }

        function getcronProfiles($c_profile)
        {
            $p_type = "";
            $profile_type = TableRegistry::get("profile_types")->find('all')->where(['placesorders' => 1]);
            foreach ($profile_type as $ty) {
                $p_type .= $ty->id . ",";
            }
            $p_types = substr($p_type, 0, strlen($p_type) - 1);
            $profiles = TableRegistry::get('profiles')->find('all')->where(['id IN(' . $c_profile . ')', 'profile_type IN(' . $p_types . ')']);
            $this->response->body($profiles);
            return $this->response;
        }

        function application_employment($Client_ID = 26)
        {
            if (isset($_POST)) {
                $profile['fname'] = $_POST['fname'];
                $profile['lname'] = $_POST['lname'];
                $profile['mname'] = $_POST['mname'];
                $profile['email'] = $_POST['email'];
                $profile['phone'] = $_POST['code'] . "-" . $_POST['phone'];
                $profile['street'] = $_POST['street'];
                $profile['country'] = $_POST['country'];
                $profile['province'] = $_POST['province'];
                $profile['city'] = $_POST['city'];
                $profile['dob'] = $_POST['doby'] . "-" . $_POST['dobm'] . "-" . $_POST['dobd'];
                $profile['placeofbirth'] = $_POST['placeofbirth'];
                $profile['gender'] = $_POST['gender'];
                $profile['title'] = $_POST['title'];
                $profile['postal'] = $_POST['postal'];
                $profile['hear'] = $_POST['hear'];
                $profile["profile_type"] = 0;
                $profile["driver_license_no"] = $_POST["driver_license_no"];
                $profile["driver_province"] = $_POST["driver_province"];
                $profile["expiry_date"] = $_POST["expiry_date"];
                $profile["sin"] = $_POST["sin"];
                $profile["iscomplete"] = 0;

                $modal = TableRegistry::get('profiles');
                $p = $modal->newEntity($profile);
                if ($modal->save($p)) {
                    $p_id = $p->id;
                    $client = TableRegistry::get('clients');
                    $c = $client->find()->where(['id' => $Client_ID])->first();
                    $p_ids = $c->profile_id;
                    $_POST['profile_id'] = $p_id;
                    if ($p_ids) {
                        $profile_ids = $p_ids . "," . $p_id;
                    } else {
                        $profile_ids = $p_id;
                    }
                    $client->query()->update()->set(['profile_id' => $profile_ids])->where(['id' => $Client_ID])->execute();

                    //18  GFS Application for Employment  1   application_for_employment_gfs.php  application_for_employment_gfs  1   1   GFS Demande d'emploi    0
                    $docID = $this->Document->constructdocument(0, "GFS Application for Employment", 18, $p_id, $Client_ID, 0);
                    $_POST["document_id"] = $docID;
                    $_POST["address"] = $_POST["street"] . " " . $_POST["city"] . ", " . $_POST["province"] . " " . $_POST["country"];
                    $app = TableRegistry::get('application_for_employment_gfs');

                    $application = $app->newEntity($_POST);

                    $path = $this->Document->getUrl();
                    if ($app->save($application)) {
                        $emails = $this->getallrecruiters($Client_ID);//GFS
                        $path = LOGIN . "documents/view/" . $Client_ID . "/" . $docID . "?type=18";//18=document type ID
                        $site = TableRegistry::get('settings')->find()->first()->mee;//, "site" => $site
                    }
                    $this->redirect('/application/index.php?form=9&msg=success&user_id=' . $p_id . '&client_id=' . $Client_ID);
                }
            }
        }

        function cron_client($pid, $cid)
        {
            $r = "";
            $cronp = TableRegistry::get('client_crons')->find('all')->where(['profile_id' => $pid, 'client_id' => $cid, 'orders_sent' => '1']);
            foreach ($cronp as $c) {
                $r .= $c->cron_date . "&" . $c->order_id . ",";
            }
            $r = substr($r, 0, strlen($r) - 1);
            $this->response->body($r);
            return $this->response;
        }

        function getcron($date)
        {
            return TableRegistry::get('client_crons')->find()->where(['cron_date' => $date]);
        }

        function check_status($date, $client_id, $profile_id)
        {
            $crons = TableRegistry::get('client_crons');
            $cron_p = $crons->find()->where(['profile_id' => $profile_id, 'client_id' => $client_id, 'cron_date' => $date, 'manual' => '1'])->first();
            $this->response->body(count($cron_p));
            return $this->response;
            die();
        }

        function cron_user($date, $client_id, $profile_id)
        {
            $user_count = 0;
            $client = TableRegistry::get('clients')->find()->where(['id' => $client_id])->first();
            $forms = $client->requalify_product;
            $profile = TableRegistry::get('profiles')->find()->where(['id' => $profile_id])->first();

            $crons = TableRegistry::get('client_crons');
            $cron_p = $crons->find()->where(['profile_id' => $profile_id, 'client_id' => $client_id, 'orders_sent' => '1', 'cron_date' => $date])->first();
            if (count($cron_p) == 0) {
                $user_count++;
                $pro = $profile_id;
                $rec = TableRegistry::get('profiles')->find()->where(['id' => $profile->created_by])->first();
                if ($rec->email) {
                    $e = $rec->email;
                }
                if ($profile_id != "") {

                    $drivers = explode(',', $profile_id);
                    //$forms = $_POST['forms'];
                    $arr['forms'] = $forms;
                    $arr['order_type'] = 'REQ';
                    $arr['draft'] = 0;
                    $arr['title'] = 'order_' . date('Y-m-d H:i:s');

                    $arr['client_id'] = $client_id;
                    $arr['created'] = date('Y-m-d H:i:s');
                    //$arr['division'] = $_POST['division'];
                    //$arr['user_id'] = $this->request->session()->read('Profile.id');
                    $arr['driver'] = '';
                    $arr['order_id'] = '';

                    foreach ($drivers as $driver) {

                        $arr['uploaded_for'] = $driver;
                        $ord = TableRegistry::get('orders');
                        $doc = $ord->newEntity($arr);
                        $ord->save($doc);

                        //$this->webservice('BUL', $arr['forms'], $arr['user_id'], $doc->id);
                        if ($arr['driver']) {
                            $arr['driver'] = $arr['driver'] . ',' . $driver;
                        } else {
                            $arr['driver'] = $driver;
                        }
                        if ($arr['order_id']) {
                            $arr['order_id'] = $arr['order_id'] . ',' . $doc->id;
                        } else {
                            $arr['order_id'] = $doc->id;
                        }

                        $cron['order_id'] = $doc->id;
                        $cron['profile_id'] = $driver;
                        $cron['orders_sent'] = '1';
                        $cron['cron_date'] = $date;
                        $cron['client_id'] = $client->id;
                        $cron['manual'] = 1;

                        $s = $crons->newEntity($cron);
                        $crons->save($s);
                        unset($cron);

                    }
                    $this->set('arr', $arr);
                }

                $i = 0;
                $setting = TableRegistry::get('settings')->find()->first();
                $this->Mailer->handleevent("requalification", array("site" => $setting->mee, "email" => $e, "username" => $profile->username, "company_name" => $client->company_name));
            }
            $this->set('profiles', 1);
        }

        function get($Key, $Default = "", $Array = "")
        {
            if (isset($_GET[$Key])) {
                return $_GET[$Key];
            }
            if (isset($_POST[$Key])) {
                return $_POST[$Key];
            }
            if (isset($Array[$Key])) {
                return $Array[$Key];
            }
            return $Default;
        }

        function unify($Array = "")
        {//for unifying email and debug logging onto 1 server
            $Action = $this->get("action", "", $Array);
            $MergedArray = array_merge($_POST, $_GET);
            if (is_array($Array)) {
                $MergedArray = array_merge($MergedArray, $Array);
            }
            switch ($Action) {
                case "viewlog":
                    $file = file_get_contents($this->Mailer->debugprint());
                    echo "<PRE>" . $file . "</PRE>";
                    break;
                case "debugprint":
                    $this->Mailer->debugprint("IP Address: " . $this->get("ip") . " Proxy IP: " . $this->get("proxyip", "[N/A]") . "\r\n" . $this->get("text"), $this->get("domain", "ISBMEE"));
                    echo $this->get("text") . " was printed";
                    break;
                case "handleevent":
                    $Event = $this->get("eventname");
                    $Sent = false;
                    if ($this->get("domain") != "veritas") {
                        $Event = $this->get("domain") . "_" . $Event;
                        $Sent = $this->Mailer->handleevent($Event, $MergedArray);
                        if (!$Sent) {
                            $Sent = $this->get("eventname");
                        }
                    }
                    if (!$Sent) {
                        $Sent = $this->Mailer->handleevent($Event, $MergedArray);
                    }
                    return $Sent;
                    break;
                case "placeorder":
                    $this->placerapidorder($MergedArray);
                    break;
                case "orderstatus":
                    echo $this->getorderstatus($MergedArray);
                    die();
                    break;
                case "test":
                    echo "Unify: Success!";
                    break;
                default:
                    echo $Action . " is not handled<BR>";
                    $this->debugall($_POST, "<BR>Post<BR>");
                    $this->debugall($_GET, "<BR>Get<BR>");
            }
            die();
        }

        function debugall($Array, $Name = "")
        {
            $CRLF = "\r\n";
            if ($Name) {
                Echo 'Name: ' . $Name . $CRLF;
            }
            foreach ($Array as $Key => $Value) {
                Echo 'Key: ' . $Key . $CRLF;
                Echo 'Value: ' . $Value . $CRLF;
            }
        }

        function testuser($GETPOST, $Key)
        {
            if (is_array($GETPOST)) {
                if (isset($GETPOST[$Key]) && $GETPOST[$Key]) {
                    $GETPOST = $GETPOST[$Key];
                }
            }
            $Entry = $this->Manager->get_entry("profiles", $GETPOST, $Key);
            if ($Entry) {
                $this->status(false, $Key . ' is in use');
            }
            return $GETPOST;
        }

        function getorderstatus($GETPOST = "")
        {
            if (!$GETPOST) {
                $GETPOST = array_merge($_POST, $_GET);
            }
            $Entry = $this->Manager->get_entry("orders", $GETPOST["orderid"], "id");
            if (!$Entry) {
                $this->status(false, "Order not found");
            }
            if (isset($GETPOST["youruserid"])) {
                $User = $this->Manager->get_profile($GETPOST["youruserid"]);
                if ($GETPOST["youruserid"] != $Entry["user_id"] && !$User->super) {
                    $this->status(False, "You did not place this order");
                }
            }
            $PATH = "webroot/orders/order_" . $GETPOST["orderid"];
            $baseURL = LOGIN . $PATH . "/";
            $basedir = str_replace("//", "/", $_SERVER['DOCUMENT_ROOT'] . $this->Manager->webroot() . $PATH);

            $Files2 = array();
            if (is_dir($basedir) && file_exists($basedir)) {
                $files = scandir($basedir);
                unset($files[0]);
                unset($files[1]);
                foreach ($files as $file) {
                    $Files2[] = $baseURL . $file;
                }
            }

            /*  upload ID
            $files = $this->Manager->get_entry("mee_attachments", $GETPOST["orderid"], "order_id");
            $baseURL = LOGIN . "webroot/attachments" . "/";
            $Files2[] = $baseURL . $files->id_piece1;
            */

            $Entry->Status = true;
            $Entry->Files = $Files2;
            if (isset($GETPOST["test"])) {
                var_dump($Entry);
                die();
            }
            if (isset($GETPOST["pretty"])) {
                return '<PRE>' . json_encode($Entry, JSON_PRETTY_PRINT) . '</PRE>';
            }
            return json_encode($Entry);
        }

        function array_flatten($array)
        {
            if (isset($array["form"])) {
                foreach ($array["form"] as $ID => $Data) {
                    foreach ($Data as $Key => $Value) {
                        $array["data[" . $ID . "][" . $Key . ']'] = $Value;
                    }
                }
            }
            unset($array["MAX_FILE_SIZE"]);//not needed
            unset($array["form"]);
            return $array;
        }

        function checkstatus()
        {
            $data["username"] = "revolution_user";
            $data["password"] = md5("Pass34533!z4");
            $data["orderid"] = 2536;
            $data["action"] = "orderstatus";
            $data = $this->array_flatten($data);//your URL: 'http://isbmee.ca/mee/rapid/placerapidorder'
            echo $this->Manager->cURL('http://isbmee.ca/mee/rapid/placerapidorder', $data, "multipart/form-data");//hard way (the same way they'll be doing it)
            die();
        }

        function testpost($Action = "postorder", $OrderID = 953)
        {
            switch ($Action) {
                case "postorder":
                    $data = array("fname" => "Test", "mname" => "Ing", "lname" => "User", "gender" => "Male", "title" => "Mr.", "email" => $this->Manager->randomtext(20) . "@trinoweb.com", "expiry_date" => "2015-10-10", "placeofbirth" => "Canada", "sin" => "123-456-789", "phone" => "(905) 555-5123", "street" => "123 fake st", "city" => "fakington", "province" => "ON", "postal" => "L7P 6V6", "country" => "Canada", "dob" => "10/04/2015", "driver_license_no" => "123-456-789", "driver_province" => "ON", "clientid" => "41", "forms" => "1603,1,14,77,78,1650,1627,72,32,31", "ordertype" => "CAR", "driverphotoBASE" => "data:image/png;base64,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",
                        "signatureBASE" => "", "consentBASE" => "data:image/png;base64,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"
                    );

                    $Form = array();
                    $Form['type'] = '9';
                    $Form['company_name'] = 'TrinoWeb Solutions';
                    $Form['address'] = '123 fake st';
                    $Form['city'] = 'fakington';
                    $Form['state_province'] = 'Ontario';
                    $Form['country'] = 'Canada';
                    $Form['supervisor_name'] = 'Van Trinh';
                    $Form['supervisor_phone'] = '9055555123';
                    $Form['supervisor_email'] = 't78e8st@testing.com';
                    $Form['supervisor_secondary_email'] = '';
                    $Form['employment_start_date'] = '10/04/2015';
                    $Form['employment_end_date'] = '10/04/2015';
                    $Form['claims_with_employer'] = '0';
                    $Form['claims_recovery_date'] = '10/04/2015';
                    $Form['emploment_history_confirm_verify_use'] = '';
                    $Form['us_dot'] = 'TEST';
                    $Form['signature'] = '';
                    $data["form"][] = $Form;

                    $Form = array();
                    $Form['type'] = '10';
                    $Form['college_school_name'] = 'Mohawk';
                    $Form['address'] = '123 fake st';
                    $Form['supervisior_name'] = 'Van Trinh';
                    $Form['supervisior_phone'] = '9055555123';
                    $Form['supervisior_email'] = 'test@testing.com';
                    $Form['supervisior_secondary_email'] = '';
                    $Form['education_start_date'] = '10/04/2015';
                    $Form['education_end_date'] = '10/04/2015';
                    $Form['claim_tutor'] = '1';
                    $Form['date_claims_occur'] = '10/04/2015';
                    $Form['education_history_confirmed_by'] = '';
                    $Form['performance_issue'] = '';
                    $Form['signature'] = '';
                    $data["form"][] = $Form;

                    break;
                case "orderstatus":
                    $data = array(
                        "action" => "orderstatus",
                        "orderid" => $OrderID);
                    break;
            }

            $data["username"] = "revolution_user";
            $data["password"] = md5("Pass34533!z4");

            $JSON = false;
            $BaseURL = 'http://isbmee.ca/mee/';//(REMOTE)
            //$BaseURL = LOGIN;//(LOCAL)
            if ($JSON) {

                $data = json_encode($data);
                echo $this->Manager->cURL($BaseURL . 'rapid/placerapidorder', $data);//hard way (the same way they'll be doing it)

            } else {

                //    echo $this->placerapidorder($data);//fast way

                $data = $this->array_flatten($data);//your URL: 'http://isbmee.ca/mee/rapid/placerapidorder'
                echo $this->Manager->cURL($BaseURL . 'rapid/placerapidorder', $data, "multipart/form-data");//hard way (the same way they'll be doing it)
            }
            die();
        }

        function status($Status, $Reason, $Text = "Reason")
        {
            $NewStatus = array("Status" => $Status, $Text => $Reason);
            echo json_encode($NewStatus);
            die();
        }

        function requiredfields($Data, $Fields, $Title = "")
        {
            if (is_array($Data) && is_array($Fields)) {
                foreach ($Fields as $Key) {
                    if (!isset($Data[$Key]) || !$Data[$Key]) {
                        $this->status(false, $Title . $Key . " is required and missing");
                    }
                }
            }
            return true;
        }

        function placerapidorder($GETPOST = "")
        {
            if (!$GETPOST) {
                $GETPOST = array_merge($_POST, $_GET);
            }
            if (!count($GETPOST)) {
                $GETPOST = $this->request->input('json_decode', true);//JSON handler/backup
            }

            //login requirements
            if (!isset($GETPOST["username"])) {
                $this->Status(False, "Username not specified");
            }
            $Super = $this->Manager->get_entry("profiles", $GETPOST["username"], "username");
            if (!$Super) {
                $this->Status(False, "Username '" . $GETPOST["username"] . "' not found");
            } else {
                $GETPOST["youruserid"] = $Super->id;
            }
            if (!$this->Manager->isValidMd5($GETPOST["password"])) {
                $GETPOST["password"] = md5($GETPOST["password"]);
            }
            if ($GETPOST["password"] != $Super->password) {
                $this->Status(False, "Password mismatch");
            }

            if (isset($GETPOST["action"])) {
                $this->unify($GETPOST);
                die();
            }

            $Validation = array("gender" => ["Male", "Female"], "title" => ["Mr.", "Mrs.", "Ms."], "email" => "email", "phone" => "phone", "province" => "province", "driver_province" => "province", "clientid" => "number", "driverphotoBASE" => "base64file", "driverphoto2BASE" => "base64file", "forms" => "csv", 'signatureBASE' => "base64file", 'consentBASE' => "base64file", "dob" => "date");
            switch (strtolower(trim($GETPOST["country"]))) {
                case "canada":
                    $Validation["postal"] = "postalcode";
                    break;
                case "usa":
                    $Validation["postal"] = "zipcode";
                    break;
            }
            $Formdata = $this->Manager->validate_data($GETPOST, $Validation);

            //$Required = array("clientid", "forms", "ordertype", "driverphotoBASE", "consentBASE", "fname", "lname", "gender", "email", "driver_province", "title", "placeofbirth", "sin", "phone", "street", "city", "province", "postalcode", "country", "dob", "driver_license_no", "expiry_date");
            $Required = array("clientid", "forms", "ordertype", "email", "phone", "driver_province", "driverphotoBASE", "expiry_date", "placeofbirth");

            $this->requiredfields($GETPOST, $Required);//required field validation
            if (in_array(1603, explode(",", $GETPOST["forms"]))) {
                $this->requiredfields($GETPOST, array("consentBASE"));//required field validation
            }

            if (!is_array($Formdata)) {
                $this->status(False, $Formdata);
            }

            $Name = "data";
            if (isset($GETPOST["form"]) && !isset($GETPOST["data"])) {
                $Name = "form";
            }
            if (isset($GETPOST[$Name])) {
                foreach ($GETPOST[$Name] as $Key => $Formdata) {
                    if (isset($Formdata["type"])) {//account for removed forms
                        $FormType = $Formdata["type"];
                        $Replace = false;
                        $Roles = false;
                        $Required = false;
                        switch ($FormType) {//unfix typos
                            case 9://letter of experience //"state_province" => "province",
                                $Roles = array("supervisor_phone" => "phone", "supervisor_email" => "email", "supervisor_secondary_email" => "email", "employment_start_date" => "date", "employment_end_date" => "date", "claims_with_employer" => "bool", "claims_recovery_date" => "date", "signature_datetime" => "date", "equipment_vans" => "bool", "equipment_reefer" => "bool", "equipment_decks " => "bool", "equipment_super" => "bool", "equipment_straight_truck" => "bool", "equipment_others" => "bool", "driving_experince_local" => "bool", "driving_experince_canada" => "bool", "driving_experince_canada_rocky_mountains" => "bool", "driving_experince_usa" => "bool");
                                $Required = array("company_name", "address", "city");
                                break;
                            case 10://education verification
                                $Roles = array("supervisior_phone" => "phone", "supervisor_email" => "email", "education_start_date" => "date", "education_end_date" => "date", "claim_tutor" => "bool", "date_claims_occur" => "date", "highest_grade_completed" => "number", "high_school" => "number", "college" => "number", "date_time" => "date");
                                $Replace = array("supervisor_name" => "supervisior_name", "supervisor_phone" => "supervisior_phone", "supervisor_email" => "supervisior_email");
                                //nothing is required
                                break;
                        }
                        if (is_array($Replace)) {//masks misspelled columns from the user
                            foreach ($Replace as $FROM => $TO) {
                                if (isset($Formdata[$FROM])) {
                                    $Formdata[$TO] = $Formdata[$FROM];
                                    unset($Formdata[$FROM]);
                                }
                            }
                        }
                        if (is_array($Roles)) {//data validation
                            $Formdata = $this->Manager->validate_data($Formdata, $Roles);
                            if (is_array($Formdata)) {
                                $GETPOST["data"][$Key] = $Formdata;
                            } else {
                                $this->status(False, 'Form[' . $FormType . '].' . $Formdata);
                            }
                        }
                        if (is_array($Required)) {//required field validation
                            $this->requiredfields($Formdata, $Required, 'Form[' . $FormType . '].');
                        }
                    }
                }
            }

            $ClientID = 38;
            if (isset($GETPOST["clientid"]) && $GETPOST["clientid"]) {
                $ClientID = $GETPOST["clientid"];
            }
            if (!$ClientID) {
                $this->Status(False, "Not a valid client ID");
            }

            //construct and/or get driver
            $GETPOST["email"] = trim($GETPOST["email"]);
            $Profile = $this->Manager->get_entry("profiles", $GETPOST["email"], "email");
            /* if(!$Profile && $GETPOST["driver_license_no"]){
                $Profile = $this->Manager->get_entry("profiles", $GETPOST["driver_license_no"], "driver_license_no");
            } */
            if ($Profile) {
                $Clients = $this->Manager->find_client($Super->id, false);
                if ($this->Manager->is_assigned_to_client($Profile->id, $Clients)) {
                    $GETPOST["driverid"] = $Profile->id;
                }
            }

            if (isset($GETPOST["driverid"])) {
                $Driver = $GETPOST["driverid"];
            } else {
                //$GETPOST["email"] = "roy@trinoweb.com";//comment out when in post production!!!!!
                if (!$this->Manager->validate_data($this->testuser($GETPOST, "email"), "email")) {
                    $this->Status(False, "Not a valid email address");
                }

                $DateOfBirth = $GETPOST["dob"];
                if ($DateOfBirth) {//change "10/15/2015" to "2015-10-15"
                    $DateOfBirth = substr($DateOfBirth, 6, 4) . "-" . substr($DateOfBirth, 0, 2) . "-" . substr($DateOfBirth, 3, 2);
                    $GETPOST["dob"] = $DateOfBirth;
                }

                $Driver = $this->Manager->copyitems($GETPOST, array("profile_type" => 0, "fname", "mname", "lname", "title", "gender" => "Female", "street", "city", "province", "postal", "dob", "driver_license_no", "driver_province", "email", "phone", "city", "country", "sin", "expiry_date", "placeofbirth", "import_type" => 2));//"password", "username",
                $Driver = $this->Manager->new_entry("profiles", "id", $Driver);
                $Driver = $Driver["id"];
                if (!isset($GETPOST["username"]) || !$GETPOST["username"]) {
                    $this->Manager->update_database("profiles", "id", $Driver, array("username" => "Applicant_" . $Driver));
                }
                $this->Manager->assign_profile_to_client($Driver, $ClientID);
            }

            //get forms list
            if (!$GETPOST["ordertype"]) {
                $GETPOST["ordertype"] = "CAR";
            }
            if (!isset($GETPOST["forms"]) || !$GETPOST["forms"]) {
                $Forms = $this->Manager->get_entry("product_types", $GETPOST["ordertype"], "Acronym");
                $GETPOST["forms"] = $Forms->Blocked; //"1603,1,14,77,78,1650,1627,72,32,31,99,500,501";
            }

            //construct order
            $this->loadComponent("Document");
            $OrderID = $this->Document->constructorder("RAPID ORDER " . $this->Manager->now(), $Super->id, $ClientID, $Super->fname . " " . $Super->lname, $this->get("fname") . " " . $this->get("lname"), $GETPOST["forms"], "", $GETPOST["ordertype"], $Driver);

            //attachments
            $this->handleattachments($GETPOST, "signatureBASE", 'webroot/canvas', 4, array("criminal_signature_applicant2", "criminal_signature_applicant"), $Super, $ClientID, $OrderID, $Driver);//signature (consent form)

            if (!isset($GETPOST["driverphotoBASE"]) || !$GETPOST["driverphotoBASE"]) {
                if (isset($GETPOST["driverphoto2BASE"]) && $GETPOST["driverphoto2BASE"]) {
                    $GETPOST["driverphotoBASE"] = $GETPOST["driverphoto2BASE"];
                    unset($GETPOST["driverphoto2BASE"]);
                }
            }

            $Formdata = $this->handleattachments($GETPOST, "driverphotoBASE", 'webroot/attachments', 15, "id_piece1", $Super, $ClientID, $OrderID, $Driver);//Photo ID (Upload ID) 1
            $this->handleattachments($GETPOST, "driverphoto2BASE", 'webroot/attachments', 15, "id_piece2", $Super, $ClientID, $OrderID, $Driver, $Formdata);//Photo ID (Upload ID) 2
            if (!$Formdata) {
                $Formdata = $this->Document->constructsubdoc(array(), 15, $Super->id, $ClientID, $OrderID, true, $Driver);
            }
            $this->handleattachments($GETPOST, "consentBASE", 'webroot/attachments', -15, $Formdata["subdocid"], $Super->id, $ClientID, $OrderID, $Driver);//consent form as MEE_ID

            //sub-documents
            if (isset($GETPOST["data"])) {
                foreach ($GETPOST["data"] as $Formdata) {
                    if (isset($Formdata["type"])) {//account for removed forms
                        $FormType = $Formdata["type"];
                        unset($Formdata["type"]);
                        $this->Document->constructsubdoc($Formdata, $FormType, $Super->id, $ClientID, $OrderID, true, $Driver);
                    }
                }
            }

            // create FOLDER MESSAGE UP????? TEST
            $new_url = APP . 'webroot/orders/order_' . $OrderID;
            $new_url = str_replace('src/', '', $new_url);

            $new_url = str_replace("src\\", "", $new_url);
            $new_url = str_replace("/", "\\", $new_url);
            //echo $new_url ; die();
            if (!is_dir($new_url)) {
                mkdir($new_url, 0777);
            }

            //call web service
            if (false) {//disable for faster testing
                $this->Manager->callsub("orders", "webservice", array($GETPOST["ordertype"], $GETPOST["forms"], $Driver, $OrderID));
            } else {
                // echo "SKIPPING WEB SERVICE FOR TESTING!";
            }
            /* How to call a remote sub without loading the page
            $Orders = new OrdersController;
            $Orders->constructClasses();//Load model, components...
            echo $Orders->webservice($GETPOST["ordertype"], $GETPOST["forms"], $Driver, $OrderID);
            */
            $this->Status(True, $OrderID, "OrderID");
        }

        function handleattachments($GETPOST, $Name, $Path, $SubDocID, $Field, $Super, $ClientID, $OrderID, $Driver, $ExistingFormData = false)
        {
            //constructsubdoc($data, $formID, $userID, $clientID, $orderid=0, $retData = false
            if (is_array($Name)) {
                $Index = 0;
                foreach ($Name as $Filename) {
                    $ExistingFormData = handleattachments($GETPOST, $Filename, $Path, $SubDocID, $Field[$Index], $Super, $ClientID, $OrderID, $Driver, $ExistingFormData);
                    $Index++;
                }
            } else if (isset($GETPOST[$Name]) && strpos($GETPOST[$Name], "data:image/") !== false && strpos($GETPOST[$Name], ";base64,") !== false) {
                $GETPOST[$Name] = str_replace("data:image/tmp;base64,", "data:image/png;base64,", $GETPOST[$Name]);
                $Filename = $this->Manager->unbase_64_file($GETPOST[$Name], $Path);
                if ($SubDocID > 0) {
                    $Data = array();
                    if (is_array($Field)) {
                        foreach ($Field as $Key) {
                            $Data[$Key] = $Filename;
                        }
                    } else {
                        $Data[$Field] = $Filename;
                    }
                    if ($ExistingFormData) {
                        $Table = $this->Manager->get_entry("subdocuments", $SubDocID)->table_name;
                        $this->Manager->update_database($Table, "id", $ExistingFormData["subdocid"], $Data);
                    } else {
                        return $this->Document->constructsubdoc($Data, $SubDocID, $Super->id, $ClientID, $OrderID, true, $Driver);//MEE Attach (Upload ID)
                    }
                } else if ($SubDocID == -15) {//mee_attachments, Field is the MEE_ID
                    $Data = array("mee_id" => $Field, "attachments" => $Filename);
                    return $this->Manager->new_entry("mee_attachments_more", "id", $Data);
                }
            }
            return $ExistingFormData;
        }

        function copyarray($SRC, $Cells)
        {
            $To = array();
            foreach ($Cells as $Key => $Value) {
                $Dest = $Value;
                if (is_numeric($Key)) {
                    $Source = $Value;
                } else {
                    $Source = $Key;
                }
                if (isset($SRC[$Source])) {
                    $To[$Dest] = $SRC[$Source];
                }
            }
            return $To;
        }
    }
