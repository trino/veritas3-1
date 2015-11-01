<?php 
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class TasksController extends AppController {

    
     public function initialize() {
        parent::initialize();
        $this->loadComponent('Trans');
         $this->loadComponent('Settings');
         //$this->Settings->verifylogin($this, "schedules");
    }
    
	public function index() {

	}

    public function cron($Duration = "+2 years"){
        if($_POST){
            $this->Flash->success("Data has been saved");
        }

        //////////////////copied from profile controller
        $today = date('Y-m-d');
        $EndTime = date('Y-m-d', strtotime($Duration, strtotime($today)));
        $this->set('Today', $today);
        $this->set('EndTime', $EndTime);
        $this->set('Duration', $Duration);

        $cron = TableRegistry::get('client_crons')->find()->where(['orders_sent'=>'1','manual'=>'0'])->all();
        $this->set('requalify',$cron);
        $p_type = "";
        $clients = TableRegistry::get('clients')->find('all')->where(['requalify' => '1','requalify_product <> ""']);
        $profile_type = TableRegistry::get("profile_types")->find('all')->where(['placesorders' => 1]);
        foreach ($profile_type as $ty) {
            $p_type .= $ty->id . ",";
        }
        $p_types = substr($p_type, 0, strlen($p_type) - 1);

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

            $profile = TableRegistry::get('profiles')->find('all')->where(['id IN(' . $c->profile_id . ')', 'profile_type IN(' . $p_types . ')', 'is_hired' => '1', 'requalify' => '1','expiry_date <> ""','expiry_date >='=>$today]);
            foreach ($profile as $p) {
                if ($c->requalify_re == '0') {
                    $date = $c->requalify_date;
                    if(strtotime($date)<= strtotime($today)) {
                        $date = $this->getnextdate($date,$frequency);
                        if($this->checkcron($c->id, $date, $p->id)) {
                            $date = $this->getnextdate($date, $frequency);
                        }
                    }
                } else {
                    $date = $p->hired_date;
                    if(strtotime($date) <= strtotime($today)) {
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
                        }
                    } else {
                        if (strtotime($date) == strtotime($today)) {
                            $date = $this->getnextdate($date, $frequency);
                        }
                    }
                }

                $n_req['cron_date']=    $date;
                $n_req['client_id'] =   $c->id;
                $n_req['profile_id'] =  $p->id;
                $n_req['forms'] =       $c->requalify_product;
                $n_req['expiry_date'] = $p->expiry_date;

                $n_req["dates"][] = $date;
                while ($date < $EndTime){
                    $date=$this->getnextdate($date, $frequency);
                    $n_req["dates"][] = $date;
                }

                array_push($reqs,$n_req);
                unset($n_req);
                unset($date);

            }
        }
        $this->sksort($reqs,'cron_date',true);
        $this->set('new_req',$reqs);
    }

    function sksort(&$array, $subkey="id", $sort_ascending=false) {
        if (count($array)) {
            $temp_array[key($array)] = array_shift($array);
        }
        foreach($array as $key => $val){
            $offset = 0;
            $found = false;
            foreach($temp_array as $tmp_key => $tmp_val) {
                if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey])) {
                    $temp_array = array_merge((array)array_slice($temp_array,0,$offset),
                        array($key => $val),
                        array_slice($temp_array,$offset)
                    );
                    $found = true;
                }
                $offset++;
            }
            if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
        }

        if(is_array($temp_array)) {
            if ($sort_ascending) {
                $array = array_reverse($temp_array);
            } else {
                $array = $temp_array;
            }
        }
    }

    function checkcron($cid,$date,$pid) {
        $client_crons = TableRegistry::get('client_crons');
        $cnt = $client_crons->find('all')->where(['client_id'=>$cid,'orders_sent'=>'1','cron_date'=>$date,'profile_id'=>$pid])->count();
        return $cnt;
    }

    function getnextdate($date, $frequency) {
        $today = date('Y-m-d');//                              24 hours * 60 minutes * 60 seconds * 30 days
        $nxt_date = date('Y-m-d', strtotime($date)+($frequency*24*60*60*30));
        if (strtotime($nxt_date) < strtotime($today)) {
            $d = $this->getnextdate($nxt_date, $frequency);
        } else {
            $d = $nxt_date;
        }
        return $d;
    }


    function timezone(){
        $offset = date("Z")/3600 ;
        $this->request->session()->write('time',$_GET['time']);
        $this->request->session()->write('timediff',$_GET['time'] - $offset);
    }


	public function view($id = null) {
	    $events = TableRegistry::get('Events');
        $event = $events->find()->where(['id'=>$id])->first();
        $this->set('event',$event);
        $this->set('isdisabled','1');
        $this->render('add');
	}

	public function add() {
	   if(isset($_POST['submit'])) {
        date_default_timezone_set('Canada/Central');
           $offset = -$_POST['offset'];

           $language = $this->request->session()->read('Profile.language');
           $_POST["date"] = $this->Trans->translatedate($language, $_POST["date"]);

            foreach($_POST as $k=>$v) {
                if($k == 'date') {
                    $k = $this->offsettime($k, $offset);
                    $arr[$k] = date('Y-m-d H:i:s', strtotime(trim(str_replace("-", " ", $v)) . ":00"));
                } else if ($k != "timezoneoffset" && $k != "offset" && $k != "submit") {
                    $arr[$k] = addslashes($v);
                }
            }
            $events = TableRegistry::get('Events');
    	    $arr['user_id'] = $this->request->session()->read('Profile.id');

            $event = $events->newEntity($arr);
    
            if ($events->save($event)) {
                $this->Flash->success($this->Trans->getString("flash_eventsaved"));
            } else {
                $this->Flash->error($this->Trans->getString("flash_eventnotsaved"));
            }
            return $this->redirect(['action' => 'calender']);
            
        }
	}


	public function edit($id = null) {
	    $events = TableRegistry::get('Events');
        $event = $events->find()->where(['id'=>$id])->first();
        $this->set('event',$event);
        if(isset($_POST['submit'])) {
           $offset = -$_POST['offset'];
           foreach($_POST as $k=>$v) {
               if($k == 'date') {
                   $arr[$k] = $this->offsettime(date('Y-m-d H:i:s', strtotime(trim(str_replace("-", " ", $v)) . ":00")), $offset);
               } else if ($k != "timezoneoffset" && $k != "offset" && $k != "submit") {
                   $arr[$k] = $v;
               }
           }
            $events = TableRegistry::get('Events');
            
            $query2 = $events->query();
            $query2->update()
                ->set($arr)
                ->where(['id' => $id])
                ->execute();
    	        $this->Flash->success($this->Trans->getString("flash_eventsaved"));
                
            
            return $this->redirect(['action' => 'calender']);
            
        }
       $this->render('add');
	}

	public function delete($id = null) {
	  $event = TableRegistry::get('Events');
      $query = $event->query();
      if($query->delete()
        ->where(['id' => $id])
        ->execute()) {
          $this->Flash->success($this->Trans->getString("flash_eventdeleted"));
      } else {
          $this->Flash->error($this->Trans->getString("flash_eventnotdeleted"));
      }
      return $this->redirect(['action' => 'calender']);
	}
    
    function logout() {
        $this->request->session()->delete('Profile.id');
        $this->redirect('/login');
    }
    
    function todo() {
        
    }

    function picker(){
        $this->layout= 'blank';
    }

    function calender1(){
        $this->layout= 'blank';
    }
    
    function date($date) {
        $events = TableRegistry::get('Events');
        $event = $events->find()->where(['user_id'=>$this->request->session()->read('Profile.id'),'date LIKE "'.$date.'%"'])->order(['date'])->all();
        //debug($event);
        $this->set('events', $event);
    }

    function calender() {
        $events = TableRegistry::get('Events');
        $event = $events->find()->where(['user_id'=>$this->request->session()->read('Profile.id')])->order(['date'=>'DESC'])->all();
        //debug($event);
        $this->set('events', $event);
    }




    function offsettime($date, $offset){
        if ($offset == 0){ return $date;}
        $newdate= date_create($date);
        $hours = floor($offset);
        $minutes = ($offset-$hours)*60;
        if ($minutes > 0) {$newdate->modify("+" . $minutes . " minutes"); }
        if ($hours>0) { $hours = "+" . $hours;}
        $newdate->modify($hours . " hours");
        return $newdate->format('Y-m-d H:i:s');
    }
}
