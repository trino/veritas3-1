<?php
/**
 * Created by PhpStorm.
 * User: Roy
 * Date: 4/15/2015
 * Time: 1:44 PM
 */
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;


class TransComponent extends Component {
    public $components = ['Session'];//attempt to get session working
    /*  code doesn't work for french
    //http://www.onlamp.com/pub/a/php/2002/06/13/php.html
    //http://stackoverflow.com/questions/1450037/how-to-load-language-with-gettext-in-php
    // I18N support information here
    function setup(){
        $language = $this->request->session()->read('Profile.language');

        $acceptablelanguages = array("en_US", "fr_FR");
        if (!in_array($language, $acceptablelanguages)) {
            $language = $acceptablelanguages[0];
        }//default to english

        putenv("LANG=$language");
        putenv("LANGUAGE=$language");
        putenv("LC_ALL=$language");
        setlocale(LC_ALL, $language);

        // Set the text domain as 'messages'
        $domain = 'default';
        bindtextdomain($domain, "C:/wamp/www/veritas3-0/Locale");//www/veritsa3-0/,   Locale
        textdomain($domain);
    }
    */

    function translatedate($language = "English", $date, $longform = true){
        if($language != "English" && $language != "Debug"){
            if($longform){$longform = "long";} else {$longform = "short";}
            $months = TableRegistry::get('strings')->find()->select()->where(["name like" => "month_" . $longform . "%"]);
            foreach($months as $month){
                $date = str_replace($month->$language, $month->English, $date);
            }
        }
        return $date;
    }

    function getVariables($settings, $language = "English"){
        $variables = $this->Sadd("client", $language, $settings);
        $variables = array_merge($variables,$this->Sadd("document", $language, $settings));
        $variables = array_merge($variables,$this->Sadd("profile", $language, $settings));
        return array_merge($variables,$this->Sadd("mee", "English", $settings));//no french equivalent
    }

    function Sadd($Key, $language, $Value){
        $P="%";
        $NewName = $Key;
        if($language != "English" && $language != "Debug"){$NewName .= $language;}
        $Value=$Value->$NewName;
        $variables=array();
        $variables[$P. strtolower($Key) .$P] = strtolower($Value);
        $variables[$P. strtoupper($Key) .$P] = strtoupper($Value);
        $variables[$P. ucfirst($Key) .$P] = ucfirst($Value);
        return $variables;
    }

    function get_settings(){
        return TableRegistry::get('Settings')->find()->first();
    }

    public function getLanguage($UserID = ""){
        if($UserID) {
            if (is_numeric($UserID)) {//is a number, use it as a user id
                $Table = TableRegistry::get('profiles')->find()->select()->where(["id" => $UserID])->first()->language;
            } else {//is not a number, assume it's a language
                return ucfirst($UserID);
            }
        } else{//use current user
            if (is_object($this->request)) {//the user is logged in, use session variable
                $Table = $this->request->session()->read('Profile.language');//Call to a member function session() on a non-object
                //if this attempt fails, try: (from DocumentComponent)
                //$controller = $this->_registry->getController();
                //$controller->request->session()->read('Profile.id');
            } else { //the user is not logged in, use English or $GET["language"]
                if (isset($_GET["language"])) { return $_GET["language"];}
            }
        }
        if(isset($Table)){return $Table;}
        return "English";//assume english
    }

    public function getString($String, $Variables = "", $UserID=""){
        $Table = TableRegistry::get('strings')->find()->select()->where(["Name" => $String])->first();
        if(!$Table){return "[" . $String . " NOT FOUND]";}
        $language = $this->getLanguage($UserID);
        if($language=="Debug"){return "[TC:" . $String . "]";}
        $text = $Table->$language;
        if(!$text){ return "[" . $String . " is missing the " . $language. " translation]";}
        if (!is_array($Variables) AND is_numeric(strpos($text, "%"))){
            $Variables = $this->getVariables($this->get_settings(), $language);
        }
        if(is_array($Variables)){
            foreach($Variables as $Key => $Value){
                if (substr($Key, 0, 1) != "%") {$Key = "%" . $Key;}
                if (substr($Key, -1) != "%") {$Key .= "%";}
                if($language == "Debug"){
                    $text.= " [" . $Key . "=" . $Value . "]";
                } else {
                    $text = str_replace($Key, $Value, $text);
                }
            }
        }
        return $text;
    }
}