<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\View\Helper\FlashHelper;
use Cake\Controller\Component\FlashComponent;
use Cake\Controller\Component\CookieComponent;
use Cake\ORM\TableRegistry;
class LoginController extends AppController{
    public function initialize() {
        parent::initialize();
        $this->loadComponent('Settings');
        $this->loadComponent('Trans');
        if($this->request->session()->read('Profile.id'))
        {
            //$this->redirect($this->referer());
            $this->redirect('/pages');
        }
        //if($this->Cookie->read('name'))
        
    }
    function index(){
        $this->loadComponent('Cookie');
        $this->Cookie->config([
    'expires' => '+10000 days',
    'httpOnly' => true
]);
        $this->layout = 'login';
        $usedcookie=false;
        if($this->Cookie->read('Profile.username') && $this->Cookie->read('Profile.password') && !isset($_POST["nocookie"]) && !isset($_GET["nocookie"])) {
            $_POST['username'] = $this->Cookie->read('Profile.username');
            $_POST['password'] = $this->Cookie->read('Profile.password');
            $_POST['name'] = $_POST['username'];
            $usedcookie=true;
        }
        
        if(isset($_POST['name'])){
            $this->loadModel('Profiles');
            $conditions = array();
            $conditions['username'] = $_POST['name'];
            $arr['password'] = $_POST['password'];
            $conditions['password'] = md5($_POST['password']);
            $arr['remember'] = 0;
            if(isset($_POST['remember'])) {$arr['remember'] = 1;}
            $q = $this->Profiles->find()->where($conditions)->first();

            if($q) {
                if($arr['remember']){
                    $this->Cookie->write('Profile.username', $q->username);
                    $this->Cookie->write('Profile.password', $arr['password']);
                }
                $this->Cookie->write('Check_login','1');

                $this->request->session()->write('Profile.id',$q->id);
                $this->request->session()->write('Profile.username',$q->username);
                $this->request->session()->write('Profile.fname',$q->fname);
                $this->request->session()->write('Profile.lname',$q->lname);
                $this->request->session()->write('Profile.isb_id',$q->isb_id);
                $this->request->session()->write('Profile.mname',$q->mname);
                $this->request->session()->write('Profile.profile_type',$q->profile_type);
                $this->request->session()->write('Profile.language', $q->language);
                $this->request->session()->write('Profile.email',$q->email);
                if($q->first_login == 0){
                $firstlogin = TableRegistry::get('profiles');
                    $query2 = $firstlogin->query();
                    $query2->update()
                        ->set(['first_login'=>1])
                        ->where(['id' => $q->id])
                        ->execute();
                        
                        $this->Flash->error("We've updated our system to serve you better. Please hit CTRL + F5 to clear your browser cache and load the latest version of MEE.");
                    }

                if(($q->admin ==1) || ($q->super==1)) {
                    $this->request->session()->write('Profile.admin',1);
                    if($q->super == 1)
                    $this->request->session()->write('Profile.super',1);
                }
                //$this->redirect($this->referer());

                if(isset($_POST['url']) && $_POST['url']) {
                    $URL=$_POST['url'];
                    echo '<SCRIPT>window.location = "' . $URL . '";</SCRIPT>';die();

                    debug($URL);die();

                    $URL=$this->converttoredirect($URL, LOGIN);
                    $this->redirect($URL);
                }else {
                    $this->redirect('/pages');
                }
            } else{
                //if (!strpos($_SERVER['REQUEST_URI'], "login")) {//does not work (prevents the invalid username/password notification
                    $language = "English";
                    if (isset($_POST["language"])) {$language = $_POST["language"];}
                    if (isset($_GET["language"])) {$language = $_GET["language"];}
                    $this->Flash->error($this->Trans->getString("flash_invalidlogin", "", $language));
                    $URL = '/login?language=' . $language;
                    if ($usedcookie || isset($_POST["nocookie"]) || isset($_GET["nocookie"])) {$URL .= "&nocookie";}
                    if(! isset($_GET["nocookie"])) {$this->redirect($URL);}
                //}
            }
        }else {
           // die();
        }
    }

    function converttoredirect($URL, $BaseURL){
        if(is_array($URL)){//return to url
            $Del = "?";
            $BaseURL = $BaseURL . $URL["controller"] . '/' . $URL["action"];
            foreach($URL["?"] as $Key => $Value){
                $BaseURL .= $Del;
                if(is_numeric($Key)){
                    $BaseURL .= $Value;
                } else {
                    $BaseURL .= $Key . "=" . $Value;
                }
                $Del = "&";
            }
        } else {//turn to URL
            if (strtolower($this->Manager->left($URL, strlen($BaseURL))) == $BaseURL) {
                $URL = $this->Manager->right($URL, strlen($URL) - strlen($BaseURL));
            }
            if (strpos($URL, "?") !== false) {
                $URL = explode("/", $URL);
                $REDIR = array('controller' => $URL[0], "action" => "", "?" => array());
                $URL = explode("?", $URL[1]);
                $REDIR["action"] = $URL[0];
                $URL = explode("&", $URL[1]);
                foreach ($URL as $GET) {
                    if (strpos($GET, "=") === false) {
                        $REDIR["?"][] = $GET;
                    } else {
                        $GET = explode("=", $GET);
                        $REDIR["?"][$GET[0]] = $GET[1];
                    }
                }
                return $REDIR;
            }
            return "/" . $URL;
        }
    }
} 