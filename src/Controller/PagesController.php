<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;



use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Inflector;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Network\Email\Email;

class PagesController extends AppController {
    public $paginate = [
            'limit' => 10,
            'order' => ['id' => 'desc']
    ];

     public function initialize() {
        parent::initialize();
        $this->loadComponent('Settings');
        $this->loadComponent('Trans');
        $this->Settings->verifylogin($this, "pages");
    }

	public function index() {
	    $this->loadComponent('Document');
        $this->set('doc_comp',$this->Document);
        $this->getAllClient();

        if(isset($_GET['orderflash']))
        $this->Flash->success($this->Trans->getString("flash_orderdraft"));
        $userid=$this->request->session()->read('Profile.id');
		$setting = $this->Settings->get_permission($userid);

        if(isset($setting->client_list) && $setting->client_list==0) {
            $this->set('hideclient', 1);
        } else {
            $this->set('hideclient', 0);
        }
        $conditions="";
        if(!$this->request->session()->read('Profile.super')){
            $conditions["id"] = $this->Manager->find_client($userid, false);
            $client_ids = implode($conditions['id'],',');
            $clients = TableRegistry::get('clients')->find('all')->where('id IN ('.$client_ids.')');
          
            $this->set('client', $this->paginate($clients));
        }
        else
            $this->set('client', $this->paginate($this->Manager->enum_all("clients", $conditions)));
       
        $this->loadproducts();

        $this->set('forms',  TableRegistry::get('order_products')->find('all'));
        $this->getsubdocument_topblocks($userid);

        $block =  $this->Manager->loadpermissions($userid, "blocks");//$this->requestAction("settings/all_settings/" . $userid . "/blocks");
        $sidebar = $this->Manager->loadpermissions($userid, "sidebar");//$this->requestAction("settings/all_settings/" . $userid . "/sidebar");
        $this->set("userid",    $userid);
        $this->set('block',     $block);
        $this->set('sidebar',   $sidebar);



  //      debug($sidebar);die();
  //      $Count = $this->countenabled($block, array("id", "user_id"));
        if(!$sidebar->orders){
            if($setting->profile_list) {
               $this->redirect("/profiles");
            } else if ($setting->training) {
                $this->redirect("/training");
            }
        }
	}

    function countenabled($Data, $Filter = array()){
        if(is_object($Data)){
            $Data = $this->Manager->getProtectedValue($Data, "_properties");
        }
        if(!is_array($Filter)){$Filter = array($Filter);}
        foreach($Filter as $Key){
            unset($Data[$Key]);
        }
        $Count = 0;
        foreach($Data as $Value){
            if($Value){$Count++;}
        }
        return $Count;
    }

    function loadproducts($VariableName = 'products'){
        $products = TableRegistry::get('product_types')->find('all');
        $this->set($VariableName,  $products);
    }

    function getenabledprovinces($ProductID, $Province = "ALL"){
        $forms = array();
        $items = TableRegistry::get('order_provinces')->find("all")->where(['ProductID' => $ProductID, "Province" => $Province]);
        foreach($items as $item){
            $forms[] = $item->ProductID;
        }
        return implode(",", $forms);
    }

    function org_chart(){
    }
    
    function test(){
        $this->layout = 'blank';
    }
    
    function edit($slug){
        //var_dump($_POST); die();
        $languages = explode(",", $_POST["languages"]);
        foreach($languages as $language){
            if($language == "English"){ $language = "";}
            $con['title' . $language] = $_POST['title' . $language];
            $con['desc' . $language] = $_POST['desc' . $language];
        }
        $pages = TableRegistry::get("contents");
        $query = $pages->query();
                    $query->update()
                    ->set($con)
                    ->where(['slug'=>$slug])
                    ->execute();
         $this->Flash->success($this->Trans->getString("flash_pagesaved"));
        $this->redirect('/profiles/settings');
    }

    function get_content($slug){
        $content = TableRegistry::get("contents");
        //$query = $content->query();
        $l =  $content->find()->where(['slug'=>$slug])->first();
        $this->response->body(($l));
        return $this->response;
        die();
    }

    function cms($slug){
    }

    function getsubdocument_topblocks($UserID){
        $table = TableRegistry::get('order_products_topblocks');
        $query = $table->find()->select()->where(['UserID' => $UserID])->order(['ProductID' => 'asc']);
        $products = TableRegistry::get('order_products')->find('all');
        foreach($products as $product){
            $product->TopBlock = 0;
            if(is_object($this->FindIterator($query, "ProductID", $product->number))) {$product->TopBlock = 1;}
        }
        $this->set("theproductlist", $products);
    }

    function FindIterator($ObjectArray, $FieldName, $FieldValue){
        foreach($ObjectArray as $Object){
            if ($Object->$FieldName == $FieldValue){return $Object;}
        }
        return false;
    }

    function getAllClient(){
        $query = TableRegistry::get('Clients')->find('all');
        $this->set('clients', $query);
    }

    function view($slug){
        $content = TableRegistry::get("contents");
        //$query = $content->query();
          $l =  $content->find()->where(['slug'=>$slug])->first();
          $this->set('content',$l);
    }

    function recent_more(){
        $this->layout = 'blank';
    }

    function test_email(){
        $this->Mailer->handleevent("test", array("email"=> array('reshma.alee@gmail.com','justdoit_2045@hotmail.com')));
        die('here');
    }
    
}
