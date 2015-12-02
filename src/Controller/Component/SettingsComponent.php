<?php
    namespace App\Controller\Component;

    use Cake\Controller\Component;
    use Cake\ORM\TableRegistry;
    use Cake\Event\Event;

    class SettingsComponent extends Component {
        public $components = array('Manager');

        public function verifylogin($_this, $controller){
            // $this->loadComponent('Settings');
            // $this->Settings->verifylogin($this, "feedbacks");
            $exceptions = "";
            $controller= strtolower($controller);
            //valid controllers: clients, documents, feedbacks, formbuilder, jobs, logos, messages, orders, pages, pdfs, quickcontacts, schedules, settings, training, users
            switch($controller){
                case "profiles":
                    $exceptions = array("forgetpassword");
                    break;
                case "clients":
                    $exceptions = array("quickcontact");
                    break;
                case "orders":
                    $exceptions = array("webservice");
                    break;
                case "rapid":
                case "clientapplication":
                    return false;
                    break;
            }

            $Files = scandir(getcwd());
            if (in_array($controller, $Files) || in_array($controller, array("login", "logos", "layout", "error", "element"))){
                if(!is_dir(getcwd() . "/" . $controller)) { return false; }//doesn't ever need logging in
            }

            if($exceptions) {
                if (!is_array($exceptions)) {$exceptions = array($exceptions);}
                foreach ($exceptions as $exception) {
                    if (strpos($_SERVER["REQUEST_URI"], $controller . "/" . $exception) !== false) {
                        return true;
                    }
                }
            }
            $profileID = $_this->request->session()->read('Profile.id');
            if (!$profileID) {
                $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $_this->redirect('/login?url=' . urlencode($url));
                header('Location: ' . $_this->request->webroot . 'login?url=' . urlencode($url));

                exit();
                return true;
            }
        }

        function acceptablelanguages($includeDebug = false){
            $acceptablelanguages = $this->getColumnNames("strings", array("ID", "Name"), true);
            if ($includeDebug){$acceptablelanguages[] = "Debug";}
            return $acceptablelanguages;
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

        function get_permission($uid)   {
            return $this->Manager->loadpermissions($uid, "sidebar");
        }

        function get_settings() {
            $settings = TableRegistry::get('settings');
            $query = $settings->find();

            $l = $query->first();
            return $l;
        }

        function getprofilebyclient($u,$super,$cid="") {
            $cond = [];
            $pro_id = [];
            $clients = TableRegistry::get('clients');
            if($cid) {
                $qs = $clients->find()->select('profile_id')->where(['id'=>$cid])->first();
                if(count($qs)>0) {
                    $p = explode("," ,$qs->profile_id);
                    foreach($p as $pro) {
                        array_push($pro_id,$pro);
                    }
                    $pro_id =array_unique($pro_id);

                    foreach($pro_id as $pid) {
                        array_push($cond,['id'=>$pid]);
                    }
                } else {
                    $cond = ['id >'=>'0'];
                }

            } else {
                if(!$super) {
                    //$qs = $clients->find()->select('profile_id')->where(['profile_id LIKE "'.$u.',%" OR profile_id LIKE "%,'.$u.',%" OR profile_id LIKE "%,'.$u.'" OR profile_id ="'.$u.'"'])->all();
                    $qs = $clients->find()->select('profile_id')->where(["FIND_IN_SET(" . $u . ", profile_id) > 0" ])->all();
                    if(count($qs)>0) {
                        foreach($qs as $q) {
                            $p = explode("," ,$q->profile_id);
                            foreach($p as $pro) {
                                array_push($pro_id,$pro);
                            }
                        }
                        //var_dump($pro_id);
                        $pro_id =array_unique($pro_id);

                        foreach($pro_id as $pid) {
                            array_push($cond,['id'=>$pid]);
                        }
                    } else {
                        $cond = ['id >'=>'0'];
                    }
                } else {
                    $cond = ['id >' => '0'];
                }
            }
            //var_dump($cond);
            return $cond;
        }
        function getclientids($u,$super,$model="") {
            if($model!="") {
                $model = $model . ".";
            }
            $cond = [];
            $pro_id = [];
            if(!$super) {
                $clients = TableRegistry::get('clients');
                //$qs = $clients->find()->select('id')->where(['profile_id LIKE "'.$u.',%" OR profile_id LIKE "%,'.$u.',%" OR profile_id LIKE "%,'.$u.'" OR profile_id ="'.$u.'"'])->all();
                $qs = $clients->find()->select('id')->where(["FIND_IN_SET(" . $u . ", profile_id) > 0"])->all();
                $pro_id = [];
                $cond = [];
                if(count($qs)>0) {
                    foreach($qs as $q) {
                        $p = explode("," ,$q->id);
                        foreach($p as $pro) {
                            array_push($pro_id,$pro);
                        }
                    }
                    //var_dump($pro_id);die();
                    $pro_id =array_unique($pro_id);

                    foreach($pro_id as $pid) {
                        array_push($cond,[$model.'client_id'=>$pid]);
                    }
                } else {
                    $cond = [$model . 'id >' => '0'];
                }
            } else {
                $cond = [$model . 'id >' => '0'];
            }
            return $cond;

        }

        function getAllClientsId($uid) {
            $clients = TableRegistry::get('clients');
            //$qs = $clients->find()->select('id')->where(['profile_id LIKE "'.$uid.',%" OR profile_id LIKE "%,'.$uid.',%" OR profile_id LIKE "%,'.$uid.'" OR profile_id ="'.$uid.'"'])->all();
            $qs = $clients->find()->select('id')->where(["FIND_IN_SET(" . $uid . ", profile_id) > 0" ])->all();
            $client_ids ="";
            if(count($qs)>0) {
                foreach($qs as $k=>$q) {
                    if(count($qs)==$k+1) {
                        $client_ids .= $q->id;
                    }else {
                        $client_ids .= $q->id . ",";
                    }
                }
            }
            return $client_ids;
        }

        function getAllClientsname($uid) {
            $controller = $this->_registry->getController();
            $clients = TableRegistry::get('clients');
            //$qs = $clients->find()->select(['company_name','id'])->where(['profile_id LIKE "'.$uid.',%" OR profile_id LIKE "%,'.$uid.',%" OR profile_id LIKE "%,'.$uid.'" OR profile_id ="'.$uid.'"'])->all();
            $qs = $clients->find()->select(['company_name','id'])->where(["FIND_IN_SET(" . $uid . ", profile_id) > 0" ])->all();
            //debug($qs);die();
            $client_ids ="";
            if(count($qs)>0) {
                foreach($qs as $k=>$q) {
                    //var_dump($q); die();
                    if(count($qs)==$k+1) {
                        $client_ids .= "<a href='" . $controller->request->webroot . "clients/edit/" . $q->id . "?view' target ='_blank'>" . trim(ucfirst($q->company_name)) . "</a>";
                    }else {
                        $client_ids .= "<a href='" . $controller->request->webroot . "clients/edit/" . $q->id . "?view' target ='_blank'>" . trim(ucfirst($q->company_name)) . "</a>, ";
                    }
                }
            }
            return $client_ids;
        }
        
        function getClientName($uid)
        {
            $controller = $this->_registry->getController();
            $clients = TableRegistry::get('clients');
            //$qs = $clients->find()->select(['company_name','id'])->where(['profile_id LIKE "'.$uid.',%" OR profile_id LIKE "%,'.$uid.',%" OR profile_id LIKE "%,'.$uid.'" OR profile_id ="'.$uid.'"'])->all();
            $qs = $clients->find()->select(['company_name','id'])->where(["FIND_IN_SET(" . $uid . ", profile_id) > 0" ])->all();
            //debug($qs);die();
            $client_ids ="";
            if(count($qs)>0) {
                foreach($qs as $k=>$q) {
                    //var_dump($q); die();
                    if(count($qs)==$k+1) {
                        $client_ids .= $q->company_name;
                    }else {
                        $client_ids .= $q->company_name;
                    }
                }
            }
            return $client_ids;
        }


        function check_pro_id($id) {
            $profile = TableRegistry::get('profiles');
            $query = $profile->find()->select('id')->where(['id'=>$id]);
            $l = $query->first();
            if(!$l) {
                return 1;
            }
        }

        function check_client_id($id) {
            $profile = TableRegistry::get('clients');
            $query = $profile->find()->select('id')->where(['id'=>$id]);

            $l = $query->first();
            if(!$l) {
                return 1;
            }
        }

        function check_permission($user_id,$target_id) {//checks is user_id can delete target_id
            $user_profile = TableRegistry::get('profiles');
            $user = $user_profile->find()->where(['id'=>$user_id])->first();//the user making the operation (delete)
            if($user) {
                $profile = $user_profile->find()->where(['id' => $target_id]);
                $target = $profile->first();//the target user the operation (delete) will be performed upon
                $usertype = $user->profile_type;

                $setting = $this->get_permission($user_id);// $setting->find()->where(['user_id' => $user_id])->first();
                /*=================================================================================*/
                /*
                if($setting->profile_delete == '1'){
                   if($q1->profile_type  == '1' && $q1->super == '1' && $q1->admin == '1'){
                       if($uid != $pid)   {
                           return 1;
                       } else {
                            return 0;
                        }
                   }else if(($q1->profile_type == '1' || $q1->admin == '1')){
                       if($q2->profile_type!='1' && $q2->super!='1' && $q2->admin!='1') {
                           if($uid != $pid) {
                               return 1;
                           }  else {
                                return 0;
                            }
                       }
                   }  else  {
                       if($q2->profile_type == '5')   {
                           return 1;
                       } else {
                            return 0;
                        }
                   }
                } */
                if ($user_id != $target_id) {//cannot delete self
                    if ($user->super || $setting->profile_delete == '1' || $user->profile_type == '2') {
                        return 1;
                    }
                }
            }
            return 0;
        }

        function check_edit_permission($user_id,$target_id,$cby="") {
            if($user_id == $target_id) {
                return 1;
            }
            $user_profile = TableRegistry::get('profiles');
            $user = $user_profile->find()->where(['id'=>$user_id])->first();
            if($user) {
                $target = $user_profile->find()->select('profile_type')->where(['id'=>$target_id])->first();
                $usertype = $user->profile_type;
                $targettype = $target->profile_type;

                $setting = $this->get_permission($user_id);//$setting->first();
                //echo $q1->profile_type;
                //echo $q2->profile_type;die();
                /*=================================================================================*/

                /* only admin super admin
                if($setting->profile_edit=='1')
                {
                   if($q1->super == '1' || $uid == $pid)
                   {
                       return 1;
                   }
                   else if($q1->profile_type == '1' || $q1->admin == '1')
                   {
                       if($uid == $pid)
                       {
                           return 1;
                       }
                      else if($q2->profile_type!='1' && $q2->super!='1' && $q2->admin!='1')
                       {
                           return 1;
                       }
                       else return 0;
                   }
                   else
                   {
                       if($q2->profile_type == '5' || $uid == $pid)
                       {
                           return 1;
                       }
                       else return 0;
                   }
                } */

                if($setting->profile_edit=='1') {
                    if($user->super == '1') {
                        return 1;
                    } else {
                        if($usertype == $targettype){
                            return 0;
                        }
                        if($user_id==$cby) {
                            return 1;
                        }

                        /*if($q1->profile_type == '2')
                        {
                            if($q2->profile_type == '5' || $q2->profile_type == '7' || $q2->profile_type == '8' || $uid == $pid)
                            {
                                return 1;
                            }
                            else return 0;
                        }*/
                    }


                }
                /*=================================================================================*/
            }
            return 0;
        }

        function check_client_permission($uid,$cid) {
            $client_profile = TableRegistry::get('clients');
            $user_profile = TableRegistry::get('profiles');
            $query = $user_profile->find()->where(['id'=>$uid]);
            $q1 = $query->first();
            if($q1) {
                $profile = $user_profile->find()->where(['id'=>$uid]);
                $q2 = $profile->first();
                $usertype = $q1->profile_type;
                //$createdby = ($q1->created_by == $uid)?"1":"0";
                $client = $client_profile->find()->select('profile_id')->where(['id'=>$cid]);
                $q2 = $client->first();
                //var_dump($q2); echo $uid; die();
                $arr = explode(',',$q2->profile_id);
                if(in_array($uid,$arr) || $usertype== 1 || $q1->super == 1 || $q1->admin == 1 ) {
                    return 1;
                }
                return 0;
            }
        }

        function getClientCountByProfile($uid) {
            $query = TableRegistry::get('Clients');
            $q = $query->find();
            $u = trim($uid);
            //$q =$q->select()->where(['profile_id LIKE "'.$u.',%" OR profile_id LIKE "%,'.$u.',%" OR profile_id LIKE "%,'.$u.'" OR profile_id LIKE "'.$u.'" '])->count();
            $q =$q->select()->where(["FIND_IN_SET(" . $u . ", profile_id) > 0" ])->count();
            return $q;
        }
    }
