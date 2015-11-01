<?php
    namespace App\Controller;

    use App\Controller\AppController;
    use Cake\Event\Event;
    use Cake\Controller\Controller;
    use Cake\ORM\TableRegistry;
    use Cake\Network\Email\Email;
    use Cake\Controller\Component\CookieComponent;


    class ProfilesController extends AppController {

        public $paginate = [
            'limit' => 20,
            'order' => ['id' => 'DESC'],
        ];

        public function initialize() {
            parent::initialize();
            $this->loadComponent('Settings');
            $this->loadComponent('Mailer');
            $this->loadComponent('Document');
            //$this->Settings->verifylogin($this, "formbuilder");
        }

    
        public function index() {
            
        }
    }
?>
