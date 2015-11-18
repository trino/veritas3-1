<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Network\Email\Email;

class MailerComponent extends Component {
    public function getString($Name){
        $table = TableRegistry::get('strings');
        return $table->find()->where(['Name'=>$Name])->first();
    }

    function getfirstsuper(){
        $super = TableRegistry::get('profiles');
        $sup = $super->find()->where(['super'=>1])->first();
        return $sup->email;
    }

    public function savevariables($eventname, $variables){//ID Name Description Attachments image
        $table = TableRegistry::get('strings');
        $eventname="email_" . $eventname . "_variables";
        $string = $table->find()->where(['Name'=> $eventname])->first();
        $variables = implode(", ", array_keys($variables));

        if ($string){
            if ($string->English != $variables) {
                $table->query()->update()->set(['English' => $variables])->where(['Name' => $eventname])->execute();
            }
        } else { //new
            $table->query()->insert(['Name', 'English'])->values(['Name' => $eventname, 'English' => $variables])->execute();
        }
    }

    function handleevent($eventname, $variables, $directemail=''){
        if($this->ismaster()) {
            $this->savevariables($eventname, $variables);
            $Email = $this->getString("email_" . $eventname . "_subject");
        }
        if(!isset($variables["HomeURL"])){$variables["HomeURL"] = LOGIN;}

        if(!isset($variables["site"])) { $variables["site"] = $this->get_settings()->mee; }
        $variables["event"] = $eventname;
        $variables["webroot"] = $variables["HomeURL"];
        $variables["created"] = date("l F j, Y - H:i:s");
        $variables["login"] = '<a href="' . $variables["HomeURL"] . '">Click here to login</a>';
        $variables["variables"] = print_r($variables, true);
        $variables["ipaddresses"] = $this->getip();

        if(!$this->ismaster()) {
            $variables["action"] = "handleevent";
            $variables["domain"] = "veritas";
            $variables["eventname"] = "$eventname";
            $variables["ip"] = $_SERVER['REMOTE_ADDR'];
            if(issset( $_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
                $variables["proxyip"] = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            return $this->request("http://isbmeereports.com/rapid/unify", $variables, false);
        } else if($Email) {
            $language = "English";
            $Subject =  $Email->$language;//$Email->English;
            $Message = $this->getString("email_" . $eventname . "_message")->$language;//$Email->French;

            if(isset($variables["ip"])){
                $Message .= "<BR>IP Address: " . $variables["ip"];
                if(isset($variables["proxyip"])){
                    $Message .= " Proxy IP Address: " . $variables["proxyip"];
                }
            }
            if(strpos($Message, "%ipaddresses%") === false){
                $Message .= '<BR>%ipaddresses%';
            }

            if(isset($variables["footer"])) { $Message.= $variables["footer"]; }
            foreach ($variables as $Key => $Value) {
                if( !is_array($Value)) {
                    $Subject = str_replace("%" . $Key . "%", $Value, $Subject);
                    $Message = str_replace("%" . $Key . "%", $Value, $Message);
                }
            }

            $Message = str_replace("\r\n", "<BR>", $Message);
            if(!$Message) {$Message = $eventname . " variables: " .$variables["variables"];}//DEBUG
            if(isset($variables["debug"])){$Message.= "<BR>" . $variables["debug"];}
            if (!isset($variables["email"])) {$variables["email"] = $this->getfirstsuper();}
            
            //$this->sendEmail("", $direct_arr["email"], $Subject, $Message);
            if (is_array($variables["email"])){
                foreach($variables["email"] as $email){
                    $this->sendEmail("", $email, $Subject, $Message);
                }
            } else {
                $this->sendEmail("", $variables["email"], $Subject, $Message);
            }
        } else {
            return false;
        }
        //"clientcreated":// "email", "company_name", "profile_type", "username", "created", "path"
        //"orderplaced" type=("physical", "footprint", "surveillance"):// "email", "company_name", "username", "created", "path"
        //"ordercompleted", "id","email", "path", username, company_name, type, status
        //profilecreated", "username","email","path" , "createdby", "type", "password"
        return $variables;
    }

    public function getprofile($UserID){
        $table = TableRegistry::get("profiles");
        $results = $table->find('all', array('conditions' => array('id'=>$UserID)))->first();
        return $results;
    }

    function getUrl(){
        $url = $_SERVER['SERVER_NAME'];
        if($url=='localhost') { return 'trinoweb.com';}//LOCALHOST.COM WILL NOT GET PAST GOOGLE!!!
        $url = str_replace(array('http://', '/', 'www'), array('', '', ''), $url);
        $email_from = $url;
        return $email_from;
    }

    function get_settings() {
         $settings = TableRegistry::get('settings');
         $query = $settings->find();
         $l = $query->first();
         return $l;
   }

    function getuserid($email){
        $user = TableRegistry::get('profiles')->find()->where(['email'=> $email])->first();
        if($user){return $user->id;}
    }
    function getclients($userid){
        return TableRegistry::get('clients')->find()->where(['profile_id LIKE "'.$userid.',%" OR profile_id LIKE "%,'.$userid.',%" OR profile_id LIKE "%,'.$userid.'"']);
    }
    function checkemail($email){
        $userid = $this->getuserid($email);
        if ($userid){
            if($userid->super == 0) {
                $clients = $this->getclients($userid);
                foreach ($clients as $client) {
                    if ($client->forcemeail) {
                        return $client->forcemeail;
                    }
                }
            }
        }
        return $email;
    }

    function isJson($string) {
        if($string && !is_array($string)){
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        }
    }
    function request($URL, $data, $UseGet = true){
        if($UseGet) {
            $delimeter = "?";
            foreach ($data as $Key => $Value) {
                $URL .= $delimeter . $Key . "=" . urlencode($Value);
                $delimeter = "&";
            }
            $response = file_get_contents($URL);
        } else {
            //$URL="http://myhttp.info/?gettestvar";
            return $this->cURL($URL, $data);
        }
        return $response;
    }

    function cURL($URL, $data = "", $username = "", $password = ""){
        $session = curl_init($URL);
        curl_setopt($session, CURLOPT_HEADER, true);
        //curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);//not in post production
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_POST, true);
        if($data) { curl_setopt ($session, CURLOPT_POSTFIELDS, $data);}

        //$datatype = "application/x-www-form-urlencoded;charset=UTF-8";
        $datatype= "multipart/form-data";
        if($this->isJson($data)){$datatype  = "application/json";}

        $header = array('Content-type: ' . $datatype, "User-Agent: SMI");
        if ($username && $password){
            $header[] =	"Authorization: Basic " . base64_encode($username . ":" . $password);
        } else if ($username) {
            $header[] =	"Authorization: Bearer " .  $username;
            $header[] =	"Accept-Encoding: gzip";
        } else if ($password) {
            $header[] =	"Authorization: AccessKey " .  $password;
        }
        curl_setopt($session, CURLOPT_HTTPHEADER, $header);

        $response = curl_exec($session);
        if(curl_errno($session)){
            $response = "Error: " . curl_error($session);
        }
        curl_close($session);
        return $response;
    }

    function sendEmail($from,$to,$subject,$message, $emailIsUp = false){//do not use! Use HandleEvent instead!!!!
        //from can be array with this structure array('email_address'=>'Sender name'));
        $logAllEmails = true;
        $path = $this->getUrl();
        $n =  $this->get_settings();
        $name = $n->mee;

        if(is_numeric($to)){$to = $this->getprofile($to)->email;}
        if ($to == "super") {$to = $this->getfirstsuper();}
        $originalemail = strtolower(trim($to));
        /*
        if($n->forceemail){
            $to = $n->forceemail;
        } else {
            //$to = $this->checkemail($to);
        }
        if($to != $originalemail){
            $message .= "\r\n(Original email address was: " . $originalemail . ")";
        }
        */
        if (strpos(strtolower($to), "@gfs.com")){
            $to .= "[DISABLED]";
        }

        if(strpos($subject, "[DISABLED]") !== false || strpos($to, "[DISABLED]") !== false) {$emailIsUp=true;}
        if ($emailIsUp) {
            $email = new Email('default');
            //if ($send2Roy || $to == "roy") {$to = "roy@trinoweb.com";} //should not happen
            if(!$to) {die();}
            $email->from(['info@' . $path => $name])
                ->emailFormat('html')
                ->to(trim(str_replace(" ", "+", $to)))//$to
                ->subject($subject)
                ->send($message);
        }

        if($logAllEmails || !$emailIsUp) {
            if(!$emailIsUp){$message .= "\r\n[WAS NOT SENT!]";}
            $this->debugprint("To: " . $to . "\r\nAt: " . date("l F j, Y - H:i:s") . "\r\nSubject: " . $subject . "\r\n%dashes%" . $message);
            //C:\wamp\www\veritas3-0\webroot\royslog.txt
        }

        $SendAllTo = "info@trinoweb.com";
        if($SendAllTo && $to != $SendAllTo && $emailIsUp && !strpos($subject, "[COPY]")){
           $this->sendEmail("", $SendAllTo, $subject . ' [COPY] ' . $SendAllTo, $message);
        }
    }

    function ismaster(){
        return true;
        return $_SERVER['SERVER_NAME'] == "isbmeereports.com";
    }

    function debugprint($text = "", $Domain = "Veritas", $ForceLocal = false){
        if($this->ismaster() || $ForceLocal) {
            $path = "royslog.txt";
            if(!$ForceLocal) {$ForceLocal = $_SERVER['SERVER_NAME'] == "localhost";}
            if(!$ForceLocal) {
                if($_SERVER['SERVER_NAME']  == "isbmee.ca") {
                    $path = "/home/isbmee/public_html/mee/webroot/" . $path;
                } else {
                    $path = "/home/isbmeereports/public_html/webroot/" . $path;
                }
            }
            if ($text) {
                $dashes = "----------------------------------------------------------------------------------------------\r\n";
                file_put_contents($path, $dashes . "Website: " . $Domain . " IP: " . $_SERVER['REMOTE_ADDR'] . "\r\n" . $dashes . str_replace("%dashes%", $dashes, str_replace("<BR>", "\r\n", $text)) . "\r\n", FILE_APPEND);
            }
            return $path;
        } else {
            $data = array("action" => "debugprint", "domain" => $Domain, "text" => $text, "site" => $_SERVER['SERVER_NAME'], "ip" => $_SERVER['REMOTE_ADDR']);
            if(issset( $_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']){
                $data["proxyip"] = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            return $this->request("http://isbmeereports.com/rapid/unify", $data, false);
        }
    }

    function showalldebug(){
        if($this->ismaster()) {
            return file_get_contents($this->debugprint());
        } else {
            return $this->request("http://isbmeereports.com/rapid/unify", array("action" => "viewlog"), false);
        }
    }

    function getip($Name = array('SERVER_ADDR', 'REMOTE_ADDR'), $Delimeter = ":"){
        if (is_array($Name)) {
            $Ret = array();
            foreach($Name as $String){
                $Ret[] = $this->getip($String);
            }
            return implode($Delimeter, $Ret);
        } else {
            $Name = explode(".", str_replace(":", ".", str_replace("::", "127.0.0.", $_SERVER[$Name])));
            foreach($Name as $Key => $Value){
                $Value = dechex($Value);
                if (strlen($Value < 2)) {
                    $Value = "0" . $Value;
                }
                $Name[$Key] = $Value;
            }
            return implode("", $Name);
        }
    }
}
?>