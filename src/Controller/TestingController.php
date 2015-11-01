<?php
    namespace App\Controller;

    use App\Controller\AppController;
    use Cake\Event\Event;
    use Cake\Controller\Controller;
    use Cake\ORM\TableRegistry;
    use Cake\Network\Email\Email;
    use Cake\Controller\Component\CookieComponent;
    use Cake\Datasource\ConnectionManager;


    class TestingController extends AppController
    {

        
        function index()
        {
            $orders = TableRegistry::get('training_answers');
            $order = $orders->find()->where(['training_answers.id'=>1])->contain(['Profiles']);
            foreach($order as $o)
            {
                echo $o->profile->fname;
            }
            die();
        }
        }
        ?>