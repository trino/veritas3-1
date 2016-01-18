<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
include_once('subpages/api.php');

class TrainingController extends AppController {
    public function nopermissions(){ return "You can not edit courses."; }

    //my pages\actions
    public function index() {
        if (isset($_GET["action"])) {
            if ($this->canedit()) {
                switch ($_GET["action"]) {
                    case "delete":
                        $this->deletequiz($_GET["quizid"]);
                        break;
                    case "test":
                        $this->loadComponent("Mailer");
                        $this->Mailer->savevariables("test", array("var" => "Value", "var2" => "Value"));
                        break;
                }
            } else {
                $this->Flash->error($this->nopermissions());
            }
        }
        $this->set('hasusertakenquiz', false);
        if (isset($_GET["quizid"])){
            $this->set('hasusertakenquiz', $this->hasusertakenquiz($_GET["quizid"], $this->getuserid() ));
            $this->set('results', $this->evaluateuser($_GET["quizid"],$this->getuserid() ));
            $this->getprofile($this->getuserid());
        }
        $this->set('enrolledquizzes', $this->enumenrolledquizzes($this->getuserid()));
        $this->enumquizes();
        $this->set('canedit', $this->canedit());
    }

    public function edit(){
        $this->set('canedit', $this->canedit());
        if($this->canedit()) {
            if (isset($_GET["action"])) {
                switch ($_GET["action"]) {
                    case "delete":
                        $this->deletequestion($_GET["quizid"], $_GET["QuestionID"]);
                        break;
                    case "save":
                        $lastid = $this->savequiz($_POST);
                        if ($lastid) {$this->redirect('/training/edit?quizid=' . $lastid);}
                        break;
                }
            }

            if (isset($_GET["quizid"])) {
                $quiz = $this->getQuizHeader($_GET["quizid"]);// $table->find()->where(['ID'=>$_GET["quizid"]])->first();
                $this->set('quiz', $quiz);
                $this->quiz();
            }
        } else {
            $this->Flash->error($this->nopermissions());
            $this->redirect('/training');
        }
    }

    public function users(){
        if ($this->canedit()){
            if (isset($_GET["quizid"]) && $this->quizexists($_GET["quizid"])) {
                $this->set("pass", $this->getQuizHeader($_GET["quizid"])->pass);
                if (isset($_GET['userid'])){
                    $action="unenroll";
                    if (isset($_GET["action"])){ $action = $_GET["action"];}
                    switch($action){
                        case "unenroll":
                            $this->unenrolluser($_GET["quizid"], $_GET['userid']);
                            $this->Flash->success('The user was unenrolled');
                            break;
                        case "deleteanswers":
                            $this->deleteanswers($_GET['userid'],$_GET["quizid"]);
                            $this->Flash->success("The user's answers for this quiz were deleted");
                            break;
                    }

                }
                $this->enumusers($_GET["quizid"], $this->get("sitename"), $this->get("asapdivision"));
                $this->set('users2', $this->enumenrolledusers($_GET["quizid"], $this->get("sitename"), $this->get("asapdivision")));
                if ($this->isASAPtraining()) {
                    $this->set("sitenames", $this->getdistinctfields("profiles", "sitename"));
                    $this->set("asapdivisions", $this->getdistinctfields("profiles", "asapdivision"));
                }
            } else {
                $this->enumquizes(true);
            }
        } else{
            $this->Flash->error($this->nopermissions());
            {$this->redirect('/training'); }
        }
        $this->set('canedit', $this->canedit());
    }

    function isASAPtraining(){
        return $this->Manager->get_settings()->mee == "ASAP Secured Training";
    }

    function get($Key){
        if (isset($_GET[$Key])){ return $_GET[$Key]; }
    }

    public function quiz(){
        $answers =  $this->getQuiz($_GET["quizid"]);
        $this->set('questions', $answers);
        if (count($_POST)>0){ $this->saveanswers($this->getuserid(), $answers, $_POST); }
        $userid = $this->getuserid();
        if ($this->canedit() && isset($_GET["userid"])){ $userid = $_GET["userid"];}
        $this->enumanswers($_GET["quizid"], $userid);
        $this->set('canedit', $this->canedit());
        $this->set('results', $this->evaluateuser($_GET["quizid"],$userid));
        $this->getprofile($userid);
    }

    public function video(){}//just a simple video player

    public function editquestion(){
        if ($this->canedit()){
            if(isset($_GET["action"])) {
                switch ($_GET["action"]) {
                    case "save":
                         if ($this->savequestion($_POST) == 1){
                             $this->redirect("/training/edit?quizid=" . $_POST["QuizID"]); //new question
                         }
                        break;
                    case "delete":
                        $this->deletequestion($_GET["quizid"], $_GET["QuestionID"]);
                        break;
                }
            }
            if (isset($_GET["QuestionID"])) {
                //echo "quizID= " . $_GET["quizid"] . " questionid=" . $_GET["QuestionID"];
                $table = TableRegistry::get('training_quiz');
                $quiz =  $table->find()->where(['QuizID'=>$_GET["quizid"], 'QuestionID'=> $_GET["QuestionID"]])->first();
                $this->set('question',$quiz );
            }
        } else {
            $this->Flash->error($this->nopermissions());
        }
        $this->set('canedit', $this->canedit());
    }

    public function certificate(){
        $userid = $this->getuserid();
        if (isset($_GET["userid"])){ $userid = $_GET["userid"];}
        $this->getprofile($userid);
        if (isset($_GET["quizid"])) {
            $quiz = $this->getQuizHeader($_GET["quizid"]);// $table->find()->where(['ID'=>$_GET["quizid"]])->first();
            $this->set('quiz', $quiz);
            $this->set("date", $this->getanswereddate($_GET["quizid"], $userid));
        }
        $this->set('canedit', $this->canedit());
    }







    //my API
    public function i2($post){
        foreach($post as $key => $value){
            if (!is_numeric($value) || $value == true) { $post2[$key] = $this->clean($value);  } else {$post2[$key] = $value;}
        }
        return $post2;
    }
    public function clean($text){
        return '"' . mysql_real_escape_string(trim($text)) . '"';
    }
    public function unclean($data){
        if (substr($data,0,1)== '"' && substr($data,-1) == '"'){$data = substr($data,1, strlen($data)-2);}
        $data = str_replace("\\r\\n", "\r\n", (trim($data))) ;
        return $data;
    }
    public function canedit($ID = false){
        $isASAP = $this->isASAPtraining();
        if(isset($_GET["myid"])){$ID = $_GET["myid"];}
        if($ID){
            $Profile = $this->getprofile($ID);
            if($isASAP && ($Profile->profile_type == 12 || $Profile->profile_type == 14 || $Profile->profile_type == 15)){return true;}//manager
            return $Profile->super || $Profile->admin || $Profile->profile_type == 13;
        }
        if($isASAP && $this->request->session()->read('Profile.profile_type') == 12){return true;}
        return  $this->request->session()->read('Profile.super') || $this->request->session()->read('Profile.admin') or $this->request->session()->read('Profile.profile_type') == 13;//teacher
    }
    public function getuserid(){
        return $this->request->session()->read('Profile.id');
    }
    public function enumquizes($getapplicants = false){
        $table = TableRegistry::get('training_list')->find('all');
        if ($getapplicants){
               foreach($table as $quiz){
                   $quiz->applicants = $this->countapplicants($quiz->ID);
               }
        }
        $this->set('quizes',$table );
        return $table;
    }
    public function countapplicants($quizid){
        $table = TableRegistry::get("training_answers");
        $users =  $table->find('all',array('conditions' => array('QuizID' => $quizid), 'group' => 'UserID'));
        return $this->countobject($users);
    }
    public function countobject($object){
        return iterator_count($object);
    }

    public function quizexists($QuizID){
        $table = TableRegistry::get('training_list');
        $quiz =  $table->find()->where(['ID'=>$QuizID])->first();
        if($quiz){return true;}
    }

    public function getQuizHeader($QuizID){
        $table = TableRegistry::get('training_list');
        $quiz =  $table->find()->where(['ID'=>$QuizID])->first();
        return $quiz;
    }
    public function getQuiz($QuizID){
        $table = TableRegistry::get('training_quiz');
        $answers =  $table->find('all', array('conditions' => array('QuizID' => $QuizID), 'order' => array('QuestionID ASC') ));
        return $answers;
    }
    public function deletequiz($quizID){
        $table = TableRegistry::get('training_list');
        $table->deleteAll(array('ID' => $quizID), false);
        $table = TableRegistry::get('training_quiz');
        $table->deleteAll(array('QuizID' => $quizID), false);
        $table = TableRegistry::get('training_answers');
        $table->deleteAll(array('QuizID' => $quizID), false);
        $this->Flash->success('The quiz was deleted.');
    }
    public function deletequestion($QuizID, $QuestionID){
        $table = TableRegistry::get('training_quiz');
        $table->deleteAll(array('QuizID' => $QuizID, 'QuestionID' => $QuestionID), false);
        $table = TableRegistry::get('training_answers');
        $table->deleteAll(array('QuizID' => $QuizID, 'QuestionID' => $QuestionID), false);
        $this->Flash->success('The question was deleted.');
    }

    public function savequiz($post){//ID Name Description Attachments image
        $table = TableRegistry::get('training_list');
        $post=$_POST;

        $data = array('Name' => $post["Name"], 'Description' =>  $post["Description"], 'Attachments' => $post['Attachments'], 'image' => $post['image'], 'pass' => $post['pass'], "hascert" => $post['hascert']);

        if (isset($post["ID"])){
            $ID = str_replace('"', "", $post["ID"]);
            $table->query()->update()->set($data)
                ->where(['ID' => $ID])
                ->execute();

            $this->Flash->success('The quiz was edited');
            return $ID;
        } else { //new
            $table->query()->insert(array_keys($data))
             ->values($data)->execute();
            $lastID = $this->newestquiz($post["Name"],$post["Description"],$post['Attachments'],$post['image']);
            $this->Flash->success('The quiz was created');
            return $lastID;
        }
    }

    public function newestquiz($Name, $Description, $Attachments, $image){
        $table = TableRegistry::get('training_list');
        $quiz =  $table->find('all', array('conditions' => array(['Name' => $Name, 'Description' =>  $Description, 'Attachments' => $Attachments, 'image' => $image]),'order' => array('ID' => 'DESC')))->first();
        if($quiz) {return $quiz->ID;}
    }

    public function savequestion($post){
        //$post=$this->i2($post);
        $table = TableRegistry::get('training_quiz');
        if($post['new'] == "true") {
            $table->query()->insert(['Question', 'QuizID', 'QuestionID', 'Answer', 'Choice0', 'Choice1', 'Choice2', 'Choice3', 'Choice4', 'Choice5', 'Picture'])
                ->values(['Question' => $post["Question"], 'QuizID' => $post["QuizID"], 'QuestionID' => $post['QuestionID'], 'Answer' => $post['answer'], 'Choice0' => $post['Choice0'], 'Choice1' => $post['Choice1'], 'Choice2' => $post['Choice2'], 'Choice3' => $post['Choice3'], 'Choice4' => $post['Choice4'], 'Choice5' => $post['Choice5'], 'Picture' => $post['Picture']])
                ->execute();
            $this->Flash->success('The question was created');
            return 1;
        }else{
            $table->query()->update()->set(['Question' => $post["Question"], 'Answer' => $post['answer'], 'Choice0' => $post['Choice0'], 'Choice1' => $post['Choice1'], 'Choice2' => $post['Choice2'], 'Choice3' => $post['Choice3'], 'Choice4' => $post['Choice4'], 'Choice5' => $post['Choice5'], 'Picture' => $post['Picture']])
                ->where(['QuizID' => $post['QuizID'], 'QuestionID' => $post['QuestionID']])->execute();
            $this->Flash->success('The question was saved');
            return 2;
        }
        return 0;
    }

    function lastQuery(){
        $dbo = $this->getDatasource();
        $logs = $dbo->_queriesLog;
        return current(end($logs));
    }

    public function hasusertakenquiz($QuizID, $UserID){
        $table = TableRegistry::get("training_answers");
        $options = array();
        $options['conditions'] = array('training_answers.QuizID = ' . $QuizID . ' AND training_answers.UserID = ' . $UserID);
        $options['group'] = 'training_answers.UserID';
        $users =  $table->find('all', $options)->first();
        return is_object($users);
    }
    public function enumusers($QuizID, $sitename = "", $asapdivision = ""){//LEFT JOIN IS A PAIN!
        $table = TableRegistry::get("training_answers");
        $options = array();
        $options['conditions'] = array('training_answers.QuizID =' . $QuizID); //array('QuizID' => $QuizID);
        if($sitename){$options['conditions'][] = "Profiles.sitename = '" . $sitename . "'";}
        if($asapdivision){$options['conditions'][] = "Profiles.asapdivision = '" . $asapdivision . "'";}
        $options['group'] = 'training_answers.UserID';
        $users =  $table->find('all', $options)->contain("profiles");//->where(['training_answers.QuizID = ' . $QuizID . ' or 1=1'])
        $quiz = $this->getQuiz($QuizID);
        foreach($users as $user){
            $score = $this->gradetest($quiz,$QuizID, $user->UserID);
            $user->profile = $score;
        }
        $this->set('users',$users);
    }

    public function gradetest($Quiz, $QuizID, $UserID){
        $answers = $this->enumanswers($QuizID, $UserID);
        $pass = $this->getQuizHeader($QuizID)->pass;
        $questions=0;
        $correct=0;
        $percent=0;
        foreach($Quiz as $question){
            foreach($answers as $answer) {
                if ($answer->QuestionID == $question->QuestionID) {
                    if ($question->Answer == $answer->Answer) {
                        $correct += 1;
                    }
                    $questions += 1;
                    continue;
                }
            }
        }
        if ($questions >0) { $percent = $correct/$questions*100;}
        return array('pass' => $pass, 'questions' => $questions, 'correct' => $correct, 'percent' => $percent);
    }

    public function getanswereddate($QuizID, $UserID){
        $table = TableRegistry::get("training_answers");
        $quiz =  $table->find('all', array('conditions' => array(['QuizID'=>$QuizID, 'UserID'=>$UserID]), 'order' => array('QuestionID ASC') ))->first();
        if($quiz) {return $quiz->created;}
        return false;
    }

    public function enumanswers($QuizID, $UserID){
        $table = TableRegistry::get("training_answers");
        $quiz =  $table->find('all', array('conditions' => array(['QuizID'=>$QuizID, 'UserID'=>$UserID]), 'order' => array('QuestionID ASC') ));
        $this->set('useranswers',$quiz);
        return $quiz;
    }

    public function saveanswers($UserID, $Quiz, $Post){
        //debug($Quiz);
        $answers=0;
        $correct=0;
        foreach($Quiz as $question){//QuizID QuestionID
            $QuizID=$question->QuizID;
            $QuestionName = $question->QuizID . ":" . $question->QuestionID;
            if(isset($Post[$QuestionName . "_answer"])) {
                $Answer = $Post[$QuestionName . "_answer"];
                $Flagged = false;
                if (isset($Post[$QuestionName . "_flaggedcheckbox"])) {
                    $Flagged = $Post[$QuestionName . "_flaggedcheckbox"] == 1;
                }
                if ($question->Answer == $Answer) {
                    $correct++;
                }
                $this->saveanswer($UserID, $question->QuizID, $question->QuestionID, $Answer, $Flagged);
                $answers += 1;
            }
        }
        if($answers>0) {
            $profile=$this->getprofile($UserID);
            $score = round($correct / $answers * 100, 2);
            $event = "training_failed";
            $pass = $this->getQuizHeader($QuizID)->pass;
            $this->evaluateuser($QuizID, $UserID);
            if ($score>=$pass) {$event = "training_passed";}
            $path = LOGIN . "training/certificate?quizid=" . $QuizID . "&userid=" . $UserID;
            $users = $this->enumsupers();
            $users[] = $UserID;
            $this->handleevent($event, array("email" => $users, "score" => $score, "username" => $profile->username, "path" => $path));
            $this->loadComponent('Trans');
            $this->Flash->success($this->Trans->getString("training_answerssaved", array("num" => $answers)));
        }
    }

    function handleevent($Event, $variables){
        $this->loadComponent('Mailer');
        if(method_exists($this->Mailer, "handleevent")) {
            $this->Mailer->handleevent($Event, $variables);
        } else {//fallback method
            $this->loadComponent('Settings');
            $settings = $this->Settings->get_settings();
            $variables["site"] = $settings->mee;
            foreach(array("client", "document", "profile") as $field){
                $variables[$field] = $settings->field;
                $variables[ucfirst($field)] = ucfirst($settings->field);
            }
            $subject="%site%: ";
            switch($Event){
                case "training_failed":
                    $subject.="Course completion (Failure)";
                    $message="%username% not not pass the course";
                    break;
                case "training_passed":
                    $subject.="Course completion (Success!)";
                    $message='%username% passed!<BR><A HREF="%path%">Click here to view the certificate</A><BR>Score: %score% %';
                    break;
                case "training_enrolled":
                    $subject.="You have been enrolled in a quiz";
                    $message='You have been enrolled in %quiz%<BR><A HREF="%path%">Click here to take the quiz</A>';
                    break;
                default:
                    $subject = $Event . " is unhandled";
                    $message = "this event is not setup";
            }


            foreach($variables as $Key => $Value){
                if(!is_array($Value)) {
                    $subject = str_replace("%" . $Key . "%", $Value, $subject);
                    $message = str_replace("%" . $Key . "%", $Value, $message);
                }
            }
            if(is_array($variables["email"])){
                foreach($variables["email"] as $to){
                    if(is_numeric($to)){
                        $to = $this->getprofile($to)->email;
                    }
                    $this->Mailer->sendEmail('', $to, $subject, $message);
                }
            } else {
                if(is_numeric($variables["email"])){
                    $to = $this->getprofile($variables["email"])->email;
                }
                $this->Mailer->sendEmail('', $variables["email"], $subject, $message);
            }
        }
    }

    public function deleteanswers($UserID, $QuizID){
        $table = TableRegistry::get("training_answers");
        $table->deleteAll(array('UserID'=>$UserID, 'QuizID'=>$QuizID), false);
    }
    public function saveanswer($UserID, $QuizID, $QuestionID, $Answer, $Flagged){
        $table = TableRegistry::get("training_answers");
        $table->deleteAll(array('UserID'=>$UserID, 'QuizID'=>$QuizID, 'QuestionID'=> $QuestionID), false);
        $table->query()->insert(['UserID', 'QuizID', 'QuestionID', 'Answer', 'flagged', 'created'])
            ->values(['UserID' => $UserID, 'QuizID' => $QuizID, 'QuestionID' => $QuestionID, 'Answer' => $Answer, 'flagged' => $Flagged, 'created' => date("Y-m-d H:i:s")])->execute();
    }

    public function getprofile($UserID, $set=true){
        $table = TableRegistry::get("profiles");
        $results = $table->find('all', array('conditions' => array('id'=>$UserID)))->first();
        if ($set) { $this->set('user',$results); }
        return $results;
    }

    public function assignContact($contact,$id,$status){
        file_put_contents("log.txt", "Contact: " . $contact .  " " . $id . " " . $status . "\r\n", FILE_APPEND);
    }
    public function assignProfile($profile,$id,$status){
        if ($status=="yes"){
            $this->enrolluser($id, $profile);
        } else {
            $this->unenrolluser($id, $profile);
        }
        die();
    }
    public function returnJSON($success){
        header('Content-type: application/json');
        $response_array=array();
        if($success){
            $response_array['status'] = 'success';
        }else {
            $response_array['status'] = 'error';
        }
        echo json_encode($response_array);
    }

    public function enrolluser($QuizID, $UserID, $Enabled = True){
        $UserID = str_replace(",", "", $UserID);
        if(!$this->isuserenrolled($QuizID, $UserID)){
            $quizname = $this->unclean($this->getQuizHeader($QuizID)->Name);
            $EnrolledBy = $this->getuserid();
            if($Enabled) {
                $table = TableRegistry::get("training_enrollments");
                $table->query()->insert(['QuizID', 'UserID', 'EnrolledBy'])->values(['QuizID' => $QuizID, 'UserID' => $UserID, 'EnrolledBy' => $EnrolledBy])->execute();
            }
            $table = TableRegistry::get('sidebar');
            $table->query()->update()->set(['training' => 1])->where(['user_id' => $UserID])->execute();

            $profile = $this->getprofile($UserID, false);
            $path = LOGIN .'training?quizid=' . $QuizID;
            $this->handleevent("training_enrolled", array("email" => "$profile->email", "path" => $path, "quiz" => $quizname));
            return true;
        }
    }

    public function unenrolluser($QuizID, $UserID){
        $table = TableRegistry::get("training_enrollments");
        $table->deleteAll(array('QuizID' => $QuizID, 'UserID' => $UserID), false);
    }

    public function enumenrolledusers($QuizID, $sitename = "", $asapdivision = ""){
        $table = TableRegistry::get("training_enrollments");
        $conditions = array('QuizID'=>$QuizID);
        if($sitename){$conditions[] = "Profiles.sitename = '" . $sitename . "'";}
        if($asapdivision){$conditions[] = "Profiles.asapdivision = '" . $asapdivision . "'";}
        $results = $table->find('all', array('conditions' => $conditions))->contain("profiles");
        foreach($results as $Profile){
            $this->evaluateuser($QuizID,$Profile->UserID);
        }
        return $results;
    }
    public function isuserenrolled($QuizID, $UserID){
        $table = TableRegistry::get("training_enrollments");
        $results = $table->find('all', array('conditions' => array('QuizID'=>$QuizID, 'UserID'=>$UserID)))->first();
        return is_object($results);
    }

    public function whoenrolled($QuizID, $UserID){
        $table = TableRegistry::get("training_enrollments");
        $results = $table->find('all', array('conditions' => array('QuizID' => $QuizID, 'UserID'=>$UserID), 'fields' => array('EnrolledBy')))->first();
        if ($results) { return $results->EnrolledBy;}
        return false;
    }

    public function enumenrolledquizzes($UserID){
        $table = TableRegistry::get("training_enrollments");
        $results = $table->find('all', array('conditions' => array('UserID'=>$UserID), 'fields' => array('QuizID')));
        return $results;
    }

    public function enumsupers($fieldname = "email"){
        $table = TableRegistry::get("profiles");
        $results = $table->find('all', array('conditions' => array('super'=>1)));
        $users=array();
        foreach($results as $user){
            $users[] = $user->$fieldname;
        }
        return $users;
    }

    function evaluateuser($QuizID, $UserID){
        $useranswers = $this->enumanswers($QuizID, $UserID);
        if (!is_object($useranswers)){ return ""; }
        if($this->quizexists($QuizID)) {
            $Quiz = $this->getQuizHeader($QuizID);
            $questions = $this->getQuiz($QuizID);
            $pass = $Quiz->pass;
            $results = array("pass" => $pass, "incorrect" => 0, "missing" => 0, "correct" => 0, "total" => 0, "datetaken" => $this->getdatetaken($useranswers));
            foreach ($questions as $question) {
                $result = $this->preprocess($this->usersanswer($useranswers, $question->QuestionID), $question->Answer);
                $results[$result] += 1;
                $results["total"] += 1;
            }
            $results["hascert"] = $Quiz->hascert;

            //save results
            $table = TableRegistry::get('training_enrollments');
            $theresults = $table->find('all', array('conditions' => ['QuizID' => $QuizID, "UserID" => $UserID]))->first();
            if($theresults) {
                $table->query()->update()->set($results)->where(['QuizID' => $QuizID, "UserID" => $UserID])->execute();
            } else {
                $results["QuizID"] = $QuizID;
                $results["UserID"] = $UserID;
                $table->query()->insert(array_keys($results))->values($results)->execute();
            }
            //end save

            return $results;
        }
    }

    function preprocess($usersanswer, $correctanswer){
        $correct = "missing";
        if (is_object($usersanswer)) {
            $correct = "incorrect";
            if ($usersanswer->Answer == -1) {
                $correct = "missing";
            } elseif ($correctanswer == $usersanswer->Answer) {
                $correct = "correct";
            }
        }
        return $correct;
    }
    function getdatetaken($useranswers){
        foreach ($useranswers as $answers) {
            if ($answers->created) { return $answers->created; }
        }
        return "";
    }

    function usersanswer($useranswers, $questionid){
        if (isset($useranswers)) {
            foreach ($useranswers as $answers) {
                if ($answers->QuestionID == $questionid) {return $answers;}
            }
        }
    }


//API stolen from profilescontroller
    public function initialize(){
        parent::initialize();
        $this->loadComponent('Settings');
        $this->loadComponent('Mailer');
        if(!isset($this->Manager)) {
            $this->loadComponent('Manager');
            $this->Manager->init($this);
        }
    }
    public $paginate = [
        'limit' => 20,
        'order' => ['id' => 'DESC'],
    ];

    public function enroll() {
        if (isset($_GET["userid"]) AND isset($_GET["quizid"])) {//enrolluser
            $username = ucfirst(trim($this->getprofile($_GET["userid"], false)->username));
            $quizname = $this->unclean($this->getQuizHeader($_GET["quizid"])->Name);
            if(!$username){$username = "User ID: " . $_GET["userid"];}
            if ($this->enrolluser($_GET["quizid"], $_GET["userid"])){
                $message = $username . ' was enrolled in ' . $quizname;
            } else {
                $this->unenrolluser($_GET["quizid"], $_GET["userid"]);
                $message = $username . ' was unenrolled from ' . $quizname;
            }
            if(isset($_GET["myid"])){
                echo $message;
                die();
            } else {
                $this->Flash->success($message);
                return $quizname;
            }
        }

        if(isset($_GET["new"])) {
            $users=$this->enumenrolledusers($_GET["quizid"]);
            $contacts=array();
            foreach($users as $user){
                $contacts[] = $user->UserID;
            }
            $this->set('profile', $contacts);
        }else {
            $this->set('doc_comp', $this->Document);
            $setting = $this->Settings->get_permission($this->request->session()->read('Profile.id'));
            $u = $this->request->session()->read('Profile.id');
            $this->set('ProClients', $this->Settings);
            $super = $this->request->session()->read('Profile.super');
            $condition = $this->Settings->getprofilebyclient($u, $super);
            if ($setting->profile_list == 0) {
                $this->Flash->error('Sorry, you don\'t have the required permissions.');
                return $this->redirect("/");
            }
            if (isset($_GET['draft'])) {
                $draft = 1;
            } else {
                $draft = 0;
            }
            $cond = 'drafts = ' . $draft;
            if (isset($_GET['searchprofile'])) {
                $search = $_GET['searchprofile'];
                $searchs = strtolower($search);
            }

            if (isset($_GET['filter_profile_type'])) {
                $profile_type = $_GET['filter_profile_type'];
            }
            if (isset($_GET['filter_by_client'])) {
                $client = $_GET['filter_by_client'];
            }
            $querys = TableRegistry::get('Profiles');

            if (isset($_GET['searchprofile']) && $_GET['searchprofile']) {
                if ($cond == '') {
                    $cond = $cond . ' (LOWER(title) LIKE "%' . $searchs . '%" OR LOWER(fname) LIKE "%' . $searchs . '%" OR LOWER(lname) LIKE "%' . $searchs . '%" OR LOWER(username) LIKE "%' . $searchs . '%" OR LOWER(address) LIKE "%' . $searchs . '%")';
                } else {
                    $cond = $cond . ' AND (LOWER(title) LIKE "%' . $searchs . '%" OR LOWER(fname) LIKE "%' . $searchs . '%" OR LOWER(lname) LIKE "%' . $searchs . '%" OR LOWER(username) LIKE "%' . $searchs . '%" OR LOWER(address) LIKE "%' . $searchs . '%")';
                }
            }

            if (isset($_GET['filter_profile_type']) && $_GET['filter_profile_type']) {
                if ($cond == '') {
                    $cond = $cond . ' (profile_type = "' . $profile_type . '" OR admin = "' . $profile_type . '")';
                } else {
                    $cond = $cond . ' AND (profile_type = "' . $profile_type . '" OR admin = "' . $profile_type . '")';
                }
            }

            $ClientID = $this->Manager->find_client(false, false);
            if($ClientID && !is_array($ClientID)){
                $_GET['filter_by_client'] = $ClientID;
                $this->set("ClientID", $ClientID);
            }
            $this->set("ProfileTypes", $this->Manager->enum_all("profile_types"));
                        
            if (isset($_GET['filter_by_client']) && $_GET['filter_by_client']) {
                $sub = TableRegistry::get('Clients');
                $que = $sub->find();
                $que->select()->where(['id' => $_GET['filter_by_client']]);
                $q = $que->first();
                $profile_ids = $q->profile_id;
                if (!$profile_ids) {
                    $profile_ids = '99999999999';
                }
                if ($cond == '') {
                    $cond = $cond . ' (id IN (' . $profile_ids . '))';
                } else {
                    $cond = $cond . ' AND (id IN (' . $profile_ids . '))';
                }
            }
            if ($this->request->session()->read('Profile.profile_type') == '2' && !$cond) {
                $condition['created_by'] = $this->request->session()->read('Profile.id');
            }

            if(isset($_GET["sitename"]) && $_GET["sitename"]){
                if($cond){$cond .= ' AND ';}
                $cond .= 'sitename = "' . $_GET["sitename"] . '"';
            }
            if(isset($_GET["asapdivision"]) && $_GET["asapdivision"]){
                if($cond){$cond .= ' AND ';}
                $cond .= 'asapdivision = "' . $_GET["asapdivision"] . '"';
            }

            if ($cond) {
                $query = $querys->find();
                $query = $query->where([$cond]);
            } else {
                $query = $this->Profiles->find()->where(['OR' => $condition, 'AND' => 'super = 0']);
            }
            if (isset($search)) {
                $this->set('search_text', $search);
            }
            if (isset($profile_type)) {
                $this->set('return_profile_type', $profile_type);
            }
            if (isset($client)) {
                $this->set('return_client', $client);
            }
            $query= $this->paginate($query);
            foreach($query as $profile){
                $profile->isenrolled = $this->isuserenrolled($_GET["quizid"], $profile->id);
            }
            $this->set('profiles',$query);

            if ($this->isASAPtraining()) {
                $this->set("sitenames", $this->getdistinctfields("profiles", "sitename"));
                $this->set("asapdivisions", $this->getdistinctfields("profiles", "asapdivision"));
            }
        }
    }

    function getdistinctfields($Table, $Field){
        $Results = TableRegistry::get($Table)->find('all', array('fields' => $Field, 'group' =>  $Field));
        $Ret = array();
        foreach($Results as $Result){
            $Ret[] = $Result->$Field;
        }
        return $Ret;
    }
}