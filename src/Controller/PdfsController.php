<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;

include_once(APP . '..\webroot\subpages\soap\nusoap.php');

class PdfsController extends AppController {
    
    public function intialize() {
        parent::intialize();
        $this->loadComponent('Settings');
        //$this->Settings->verifylogin($this, "pdfs");
    }
    
    function getConsent($oid)
    {
        $this->layout = 'blank';
        $consent = TableRegistry::get('consent_form');
        $arr['consent'] = $consent
            ->find()
            ->where(['order_id' => $oid])->first();
        $this->set('detail',$arr);
        $criminal = TableRegistry::get('consent_form_criminal');
        $cri = $criminal
            ->find()
            ->where(['consent_form_id' => $arr['consent']->id]);
        $this->set('detail',$arr);
        $this->set(compact('cri'));
        $attach = TableRegistry::get('consent_form_attachments');
        $att = $attach
            ->find()
            ->where(['order_id' => $oid]);
        $this->set('detail',$arr);
        $this->set(compact('att'));
    }

    function getEmployment($oid)
    {
        $this->layout = 'blank';
        $consent = TableRegistry::get('employment_verification');
        $arr['consent'] = $consent
            ->find()
            ->where(['order_id' => $oid])->all();
            
        $this->set('detail',$arr);
        
        $attach = TableRegistry::get('employment_verification_attachments');
        $att = $attach
            ->find()
            ->where(['order_id' => $oid]);
        

        $this->set(compact('att'));
       
    } 

    function getEducation($oid)
    {
        $this->layout = 'blank';
        $consent = TableRegistry::get('education_verification');
        $education = $consent
            ->find()
            ->where(['order_id' => $oid]);;
        
        
        $attach = TableRegistry::get('education_verification_attachments');
        $att = $attach
            ->find()
            ->where(['order_id' => $oid]);
        $this->set(compact('education'));

        $this->set(compact('att'));
    }
}