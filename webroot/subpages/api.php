<?php
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
function languages($includeDebug = false){
    $acceptablelanguages = getColumnNames("strings", array("ID", "Name"), true);
    if ($includeDebug){
        $acceptablelanguages[] = "Debug";
    }
    return $acceptablelanguages;
}

function is_iterable($var) {
    return (is_array($var) || $var instanceof Traversable);
}

function languagenames(){
    $table =  TableRegistry::get('strings');
    $table = $table->find()->where(["Name" => "name"])->first();
    $acceptablelanguages = languages();
    $languages = array();
    foreach($acceptablelanguages as $language){
        if($table->$language){
            $languages[$language] = $table->$language;
        } else {
            $languages[$language] = "[" .  $language . "]";
        }

    }
    return $languages;
}

function printCSS($_this = ""){
    ?>
    <STYLE>
        @media print {
            div{
                /* page-break-inside: avoid; */
            }

            input {
                 page-break-inside: avoid;
            }

            script, style {
                display:none;
            }

            a[href]:after {
                content: none !important;
            }

            .no-print, .no-print * {
                display: none !important;
            }

            .portlet > .portlet-body {
                page-break-inside : auto  !important;
            }

            .portlet > .portlet-title {
                /* only works outside of print mode */
                /*color: red !important;*/
                display: none !important;
            }
            .portlet{                border: 0px !important;
            }
            p,h1,h2,h3,h4,h5,h6,li,label,strong,input,select{font-size:80% !important;padding:2px !important;}
        }
    <?php
    if (is_object($_this)){
        $action = $_this->request->params['action'];
         if ($action == "view" || $action == "vieworder") {
             ?>
             .no-view, .no-view * {
                 display: none !important;
             }
            <?php
         }
    }
    echo '</STYLE>' . "\r\n";
}

function formatnumber($Number){
    if ($Number < 10){$Number  = "0" . $Number;}
    return $Number;
}
function formatdate($Date, $strings){
    //month_short
    $Date = date_parse($Date);
    $Date["month"]  = formatnumber($Date["month"]);
    $Date["minute"] = formatnumber($Date["minute"]);
    if (isset($strings["month_short" . $Date["month"]])) {$Date["monthshort"] = $strings["month_short" . $Date["month"]]; $Format = $strings["month_short_format"];}
    if (isset($strings["month_long" . $Date["month"]])) {$Date["monthshort"] = $strings["month_long" . $Date["month"]]; $Format = $strings["month_short_format"];}
    foreach($Date as $Key => $Value){
        if(!is_array($Value)){
            $Format = str_replace("%" . $Key . "%", $Value, $Format);
        }
    }
    return $Format;
}

function updatetable($Table, $PrimaryKey, $Value, $Data){
    if(!is_object($Table)) {$Table = TableRegistry::get($Table);}
    $item = $Table->find()->where([$PrimaryKey => $Value])->first();
    if($item){
        $Table->query()->update()->set($Data)->where([$PrimaryKey => $Value])->execute();
    } else {
        $Data[$PrimaryKey] = $Value;
        $Table->query()->insert(array_keys($Data))->values($Data)->execute();
    }
}

$SQLfile = getcwd() .  "/strings.sql";
if (file_exists($SQLfile)) {//Check for translation update in veritsa3-0/webroot/strings.sql
    $Table = TableRegistry::get('strings');
    $LastUpdate = $Table->find()->select()->where(["Name" => "Date"])->first();
    if($LastUpdate){$LastUpdate = $LastUpdate->English;} else {$LastUpdate = 0;}
    $UpdateFile = filemtime($SQLfile);
    if ($LastUpdate < $UpdateFile) {
        //echo "<SCRIPT>alert('Applying translation update');</SCRIPT>";//silent, so no one will know I did anything...
        $SQLfile = getSQL($SQLfile);
        if ($SQLfile) {
            $db = ConnectionManager::get('default');
            $db->execute("TRUNCATE TABLE strings;");
            $db->execute($SQLfile);
            $Table->query()->update()->set(['English' => $UpdateFile])->where(['Name' => "Date"])->execute();
        }
    }
}

function JSinclude($_this, $File, $Title = false, $Dir = "", $ID = ""){
    if($Title) { echo '<!-- BEGIN ' . $Title . ' -->' . "\r\n";}
    if(is_array($File)){
        $Dir = "";
        foreach($File as $Key => $Afile){
            if(is_numeric($Key)){
                $Dir = JSinclude($_this, $Afile, "", $Dir);
            } else {
                $Dir = JSinclude($_this, $Key, "", $Dir, $Afile);
            }
        }
    } else if(!$File) {
        $Dir = "";
    } else {
        if(!getextension($File)){$Dir = "";}
        $URL = $_this->request->webroot . $Dir . $File;
        $OldFile=$File;
        $File = getcwd() . "/" . $Dir . $File;
        if (is_dir($File)){
            $Dir = $OldFile;
            if (substr($Dir,-1) != "/"){$Dir.="/";}
        } else if (file_exists($File)) {
            if($ID){
                $ID = ' ID="' . $ID . '"';
            }
            switch (getextension($File)){
                case "js":
                    echo '<script src="' . $URL . '?' . filemtime($File) . '"' . $ID . ' type="text/javascript"></script>' . "\r\n";
                    break;
                case "css":
                    echo '<link href="' . $URL . '?' . filemtime($File) . '"' . $ID . ' rel="stylesheet" type="text/css"/>' . "\r\n";
                    break;
            }
        } else {
            echo '<!--' . $URL . ' NOT FOUND!-->' . "\r\n";
        }
    }
    if($Title) { echo '<!-- END ' . $Title . ' -->' . "\r\n";}
    return $Dir;
}

function getextension($path, $value = PATHINFO_EXTENSION) {
        return strtolower(pathinfo($path, $value));
    }

function getSQL($Filename){
    $File = file_get_contents($Filename);
    $Start = strpos($File, "--", strpos($File, "Dumping data for table ")) + 3;
    $End = strpos($File, "/*", $Start);
    return substr($File, $Start, $End-$Start);
}
//end auto updater

$islocal=false;
if ($_SERVER['SERVER_NAME'] == "localhost" || $_SERVER['SERVER_NAME'] == "127.0.0.1" || $_SERVER['SERVER_ADDR'] == "127.0.0.1") { $islocal=true;}
$GLOBALS["islocal"] =$islocal;
$GLOBALS["translated"] =false;
$emailaddress= "info@" . getHost("isbmee.com");
$GLOBALS["webroot"]="";
$GLOBALS["language"] = "English";

function translatedatepicker($Language='English', $_this) {
    $webroot = $_this->request->webroot;
    $Lang = "";
    switch ($Language) {
        case "French":
            $Lang = "fr";
            break;
        case "Debug":
            echo "<!-- DEBUG MODE -->";
            break;
    }
    if ($Lang) {//Remember: The datepicker and datetimepicker locales need to be fixed, see the french ones for an example
        JSinclude($_this, 'assets/global/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.' . $Lang . '.js');
        JSinclude($_this, 'assets/global/plugins/select2/select2_locale_' . $Lang . '.js');
        JSinclude($_this, 'assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.' . $Lang . '.js');
    }
}

/*
green
green-meadow
green-seagreen
green-turquoise
green-haze
green-jungle
green-sharp
green-soft

blue
blue-madison
blue-chambray
blue-ebonyclay
blue-hoki
blue-steel
blue-soft
blue-dark
blue-sharp

grey
grey-steel
grey-cararra
grey-gallery
grey-cascade
grey-silver
grey-salsa
grey-salt
grey-mint

red
red-pink
red-sunglo
red-intense
red-thunderbird
red-flamingo
red-soft

yellow
yellow-gold
yellow-casablanca
yellow-crusta
yellow-lemon
yellow-saffron

purple
purple-plum
purple-medium
purple-studio
purple-wisteria
purple-seance
purple-intense
purple-sharp
purple-soft
 */
function btnclass($xscolor, $stripecolor = ""){
    $size = "btn-xs";
    $mode = true;//true = regular button, false = striped
    switch ($xscolor){
        case "VIEW":
            $xscolor = "btn-primary";//light blue
            $stripecolor = "blue";
            break;
        case "EDIT":
            $xscolor = "btn-primary";
            $stripecolor = "blue";
            break;
        case "DELETE":
            $xscolor = "btn-danger";
            $stripecolor = "red";
    }

    if ($mode ){
        if (strlen($stripecolor)==0){$stripecolor = $xscolor;}
        return "btn default " . $size . " " . $stripecolor . "-stripe";
    } else {
        return "btn " . $size . " " . $xscolor;
    }
}


function test(){
    die("HELLO WORLD");
}



function getHost($localhost = "localhost") {//get HTTP host name
    if ($GLOBALS["islocal"] && $localhost) {return $localhost;}
    $possibleHostSources = array('HTTP_X_FORWARDED_HOST', 'HTTP_HOST', 'SERVER_NAME', 'SERVER_ADDR');
    $sourceTransformations = array(
        "HTTP_X_FORWARDED_HOST" => function($value) {
            $elements = explode(',', $value);
            return trim(end($elements));
        }
    );
    $host = '';
    foreach ($possibleHostSources as $source) {
        if (!empty($host)) break;
        if (empty($_SERVER[$source])) continue;
        $host = $_SERVER[$source];
        if (array_key_exists($source, $sourceTransformations)) {
            $host = $sourceTransformations[$source]($host);
        }
    }
    $host = preg_replace('/:\d+$/', '', $host); // Remove port number from host
    return trim($host);
}

function s($settings, $language = "English"){
    $variables = Sadd("client", $language, $settings);
    $variables = array_merge($variables,Sadd("document", $language, $settings));
    $variables = array_merge($variables,Sadd("profile", $language, $settings));
    return array_merge($variables,Sadd("mee", "English", $settings));//no french equivalent
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

function getIterator($Objects, $Fieldname, $Value){
    if(is_iterable($Objects)){
    foreach($Objects as $Object){
        if ($Object->$Fieldname == $Value){
            return $Object;
        }
    }
    }
    return false;
}
function addTrans($array, $Trans = ""){
    if($Trans){
        foreach($array as $Key => $Value){
            $array[$Key] = $Value . $Trans;
        }
    }
    return $array;
}

function CacheTranslations($Language='English', $Text, $Variables = "", $Common = True) {
    $GLOBALS["language"] = $Language;
    if (!is_array($Text)) {
        $Text = array($Text);
    }
    if (is_object($Variables)) {
        $Variables = s($Variables, $Language);
    }

    if ($Common) {
        $Text[] = "dashboard_%";//for all pages
        $Text[] = "settings_%";//for all pages
        $Text[] = "index_%";//for all pages
    }
    $table =  TableRegistry::get('strings');

    $query="Name = 'Date'";
    foreach($Text as $text){
        if(strlen($query)>0){ $query.= " OR ";}
        if (strpos($text, "%")){
            $query .= "Name LIKE '" . strtolower($text) . "'";
        } else {
            $query .= "Name = '" . strtolower($text) . "'";
        }
    }

    $Language = trim($Language);
    $acceptablelanguages = languages(true);
    if(!in_array ($Language, $acceptablelanguages)){$Language = $acceptablelanguages[0]; }
    $table = $table->find()->where(["(" . $query . ")"])->all();
    $data = array();
    foreach($table as $entry){
        if($Language=="Debug"){
            $data[$entry->Name] = '[' . $entry->Name . ']';
        } else {
            $data[$entry->Name] = ProcessVariables($entry->Name, $entry->$Language, $Variables);
        }
    }
    $GLOBALS["translated"]= true;
    return $data;
}

function Translate($ID, $Language, $Variables = ""){
    $table = TableRegistry::get('strings');
    if (is_numeric($ID)) {$column = "ID";} else {$column = "Name";}
    $query = $table->find()->select()->where([$column => $ID])->first();
    if ($query && $Language!="Debug") {
        return  ProcessVariables($ID, $query->$Language, $Variables);
    } else {
        return $ID . "." . $Language . " is missing a translation";
    }
}
function ProcessVariables($ID, $Text, $Variables = "", $addSlashes = false){
    if (is_array($Variables)) {
        foreach ($Variables as $Key => $Value) {
            if (substr($Key, 0, 1) != "%") {$Key = "%" . $Key;}
            if (substr($Key, -1) != "%") {$Key .= "%";}
            if($ID == "Debug"){
                $Text.= " [" . $Key . "=" . trim($Value) . "]";
            } else {
                $Text = str_replace($Key, trim($Value), $Text);
            }
        }
    }
    if($addSlashes) {//&apos;
       $Text = addslashes2($Text);//&#039; breaks javascript
    }
    if($Text) {return $Text;}
    return $ID;
}

function addslashes2($Text){
    return str_replace("&#039;", "\'", addslashes(trim($Text)));
}
function addslashes3($Text){
    return str_replace("&quot;", '"',  str_replace("&#039;", "\'", addslashes(trim($Text))));
}

function FindIterator($ObjectArray, $FieldName, $FieldValue){
    foreach($ObjectArray as $Object){
        if ($Object->$FieldName == $FieldValue){return $Object;}
    }
    return false;
}

function getFieldname($Fieldname, $Language){
    if($Language == "English" || $Language == "Debug"){ return $Fieldname; }
    return $Fieldname . $Language;
}

function getField($Object, $Fieldname, $Language){
    if(is_object($Object)) {
        if($Language!="English") {
            $newField = $Fieldname . $Language;
            if ($Object->$newField){return $Object->$newField;}
            return "[" . $Object->$Fieldname . "]";//untranslated notifier
        }
        return $Object->$Fieldname;
    }
}

function getdatestamp($date){
    $newdate = date_create($date);
    return date_timestamp_get($newdate);
}

function getdatecolor($date, $strings = false, $now=""){
    $datestamp = getdatestamp($date);
    if(!$now){$now=time();}
    $color = "";
    $oneday = 86400;//24*60*60
    $datediff = $now - $datestamp;
    if($datediff > $oneday){//0-24 hours no colour
        $title="Less than 24 hours old";
        if($datediff< $oneday*2){//24-48 hours green
            $color = "green";
        } elseif($datediff< $oneday*7){//48-one week yellow
            $color = "#FFAF0A";
        } else {//One week + red
            $color ="red";
        }
    }
    if($color){return '<FONT COLOR="' . $color . '">' . formatdate($date, $strings) . "</FONT>";}
    return formatdate($date, $strings);
}

function provinces($name, $Selected = "", $req=''){
    echo '<SELECT class="form-control '.$req.'" name="' . $name . '">';
    $acronyms = getprovinces("Acronyms");
    $Provinces = getprovinces("");
    $ID=0;
    if(!$Selected){$Selected="ON";}
    foreach($acronyms as $acronym){
        printoption($Provinces[$ID], $Selected, $acronym);
        $ID++;
    }
    echo '</SELECT>';
}

function getprovinces($Language = "English", $IncludeUSA = False){
    $Trans="";
    if($Language == ""){$Language = $GLOBALS["language"];}
    if($Language == "Debug"){
        $Language = "English";
        $Trans = " [TRANS]";
    }
    switch ($Language){
        case "Acronyms":
            $Trans="";//these are keys, and must not be altered in any way
            $provinces = array("", "AB", "BC", "MB", "NB", "NL", "NT", "NS", "NU", "ON", "PE", "QC", "SK", "YT");
            if($IncludeUSA) {$states = array("AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "FL", "GA", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY");}
            break;
        case "English":
            $provinces = array("Select Province", "Alberta", "British Columbia", "Manitoba", "New Brunswick", "Newfoundland and Labrador", "Northwest Territories", "Nova Scotia", "Nunavut", "Ontario", "Prince Edward Island", "Quebec", "Saskatchewan", "Yukon Territories");
            if($IncludeUSA) {$states = array("Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "Virginia", "Wisconsin", "Wyoming");}
            break;
        case "French":
            $provinces = array("Choisir la province", "Alberta", "Colombie-Britannique", "Manitoba", "Nouveau-Brunswick", "Terre-Neuve-et-Labrador", "Territoires du Nord-Ouest", "Nouvelle-Écosse", "Nunavut", "Ontario", "Île-du-Prince-Édouard", "Québec", "Saskatchewan", "Yukon");
            if($IncludeUSA) {$states = array("Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiane", "Maine", "Maryland", "Massachusetts ", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "Nouveau-Mexique", "New York", "Nord Carolina", "le Dakota du Nord", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "Caroline du Sud", "Dakota du Sud", "Tennessee", "Texas", "Utah ", "Vermont", "Virginia", "Washington", "Virginia", "Wisconsin", "Wyoming");}
            break;
        default:
            echo "Please add support for '" . $Language . "' in subpages/api.php (getprovinces)";
            die();
    }
    if($IncludeUSA) {$provinces = array_merge($provinces, $states);}
    $provinces = addTrans($provinces, $Trans);//debug mode
    return $provinces;
}

function includejavascript($strings = "", $settings = ""){
    $language =  $GLOBALS["language"];
    $variables = array("SaveAndContinue" => "addorder_savecontinue", "SaveAsDraft" => "forms_savedraft", "Submit" => "forms_submit", "Select" => "forms_select", "SelectOne" => "forms_selectone", "SignPlease" => "forms_signplease", "MissingID" => "forms_missingid", "MissingAbstract" => "forms_missingabstract", "FillAll" => "forms_fillall", "SaveSig" => "forms_savesig", "Success" => "orders_success", "Clear" => "forms_clear", "ConfDelete" => "dashboard_confirmdelete", "FillAll" => "forms_fillall", "SelOne" => "forms_selectone");
    if (!$strings){
        $strings = CacheTranslations($GLOBALS["language"], array_values($variables), $settings, False);
    }
    echo "\r\n<SCRIPT>//pass data to form-wizard.js";
    foreach($variables as $key => $value){
        if (isset($strings[$value])) {
            echo "\r\n" . '    var ' . $key . ' = "' . addslashes($strings[$value]) . '";';
        } else {

        }
    }
    echo "\r\n";
?>
    var language = '<?= $language; ?>';

    function confirmdelete(Name){
        var text = "<?= addslashes($strings["dashboard_confirmdelete"]); ?>";
        return confirm(text.replace("%name%", Name));
    }
    <?php if($language != "English" && $language != "Debug") {
        echo '$(document).ready(function () {';
        changevalidation("INPUT", $strings["forms_fillall"]);
        changevalidation("SELECT", $strings["forms_selectone"]);
        echo '});';
    }
    echo '</SCRIPT>';
    $strings["hasJS"] = true;
    return true;
}

function selecttitle($language, $strings, $name, $title, $is_disabled = ""){
    echo '<label class="control-label">' . $strings["forms_title"] . ':</label><BR>';
    echo '<SELECT ' . $is_disabled . ' name="' . $name . '" class="form-control ">';
        $title = "";
        if($language == "French"){if ($title == "Ms.") { $title = "Mrs."; }}
        printoption($strings["forms_mr"], $title, "Mr");
        printoption($strings["forms_mrs"], $title, "Mrs");
        if($language != "French"){ printoption($strings["forms_ms"], $title, "Ms");}
    echo '</SELECT>';
}

function printoption($option, $selected = "", $value = ""){
    $tempstr = "";
    if ($option == $selected || $value == $selected) {$tempstr = " SELECTED";}
    echo '<OPTION VALUE="' . $value . '"' . $tempstr . ">" . $option . "</OPTION>";
}

function changevalidation($inputtype, $message){
    ?>
        var intputElements = document.getElementsByTagName("<?= $inputtype; ?>");
        for (var i = 0; i < intputElements.length; i++) {
            element = intputElements[i];
            intputElements[i].oninvalid = function (e) {
                e.target.setCustomValidity("");
                if (!e.target.validity.valid) {
                    var message = "<?= addslashes($message); ?>";
                    e.target.setCustomValidity(message);
                }
            }
        }
    <?php
}

function loadreasons($action, $strings, $IncludeScript = false){
    if($IncludeScript) {echo '<SCRIPT>';}
    $action = strtolower($action);
    if($action == "create" || $action == "add" || $action == "edit" || $action == 'apply'){
        echo "var reasons = new Array();";
        echo "reasons['fail'] = '" . addslashes($strings["forms_failed"]) . "';";
        echo "reasons['postalcode'] = '" . addslashes($strings["forms_postalcode"]) . "';";
        echo "reasons['phone'] = '" . addslashes($strings["forms_phone"]) . "';";
        echo "reasons['email'] = '" . addslashes($strings["forms_email"]) . "';";
        echo "reasons['sin'] = '" . addslashes($strings["forms_sin"]) . "';";
        echo "reasons['required'] = '" . addslashes($strings["forms_fillall"]) . "';";
        echo "reasons['postalzip'] = '" . addslashes($strings["forms_postalzip"]) . "';";
        echo "reasons['number'] = '" . addslashes($strings["forms_number"]) . "';";
        echo "reasons['paradox'] = '" . addslashes($strings["forms_paradox"]) . "';";
        echo "reasons['expired'] = '" . addslashes($strings["forms_expired"]) . "';";
    } else {
        echo "var reasons = false; //Action is: " . $action;
    }
    if($IncludeScript) {echo '</SCRIPT>';}
}

function copy2globals($strings, $values){
    foreach($values as $value){
        $GLOBALS[$value] = $strings[$value];
    }
}

function getpost($Key, $Default = ""){
    if (isset($_GET[$Key])){ return $_GET[$Key]; }
    if (isset($_POST[$Key])){ return $_POST[$Key]; }
    return $Default;
}

function formatname($profile){
    $name = trim(ucfirst(strtolower($profile->fname)) . " " . ucfirst(strtolower($profile->lname)));
    if ($profile->username){
        if($name){
            $name .= " (" . ucfirst(h($profile->username)) . ")";
        } else {
            $name =  ucfirst(h($profile->username));
        }
    }
    if(!trim($name)){
        return "#" . $profile->id;
    }
    return h(trim($name));
}

function clientimage($webroot, $settings, $clients = ""){
    $filename = 'img/clients/' . $settings->client_img;
    if (is_object($clients) && isset($clients->image) && $clients->image){
        $tempfilename = 'img/jobs/' . $clients->image;
        if (file_exists($tempfilename)){
            $filename = $tempfilename;
        }
    }
    if(!file_exists($filename)) {
        //$filename = scandir("img/clients");
        //$filename = "img/clients/" . $filename[2];
        $filename = "img/logos/MEELogo.png";
    }
    return $webroot . $filename;
}
function profileimage($webroot, $profile = ""){
    $dir = "img/profile/";
    $filename = "default.png";
    if (is_object($profile) && isset($profile->image) && $profile->image && file_exists($dir . $profile->image)) {
        $filename = $profile->image;
    }
    return $webroot . $dir . $filename;
}

function cleanit($array){
    return str_replace("\r\n", "", str_replace('\"', '"', addslashes(implode('", "',$array))));
}
function loadstringsJS($strings){
    echo 'var stringnames = ["' . cleanit(array_keys($strings)) . '"];' . "\r\n";
    echo '    var stringvalues = ["' . cleanit(array_values($strings)) . '"];' . "\r\n";
    ?>
    function getstring(Name){
        for (index = 0; index < stringnames.length; index++) {
            if (stringnames[index] == Name){
                return stringvalues[index];
            }
        }
    }
    function translate(){
        var elements = document.body.getElementsByTagName("translate");
        var key = "";
        for (id = 0; id < elements.length; id++) {
            element = elements[id];
            key = element.innerHTML;
            if (key.indexOf("_") > 0){
                value = getstring(key);
                element.innerHTML = value;
            }
        }
    }
    <?php
}

function readCSV($filename, $primarykey = "") {
    $columnheaders = array();
    $returndata = array();
    $row = 0;
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($row == 0){
                $columnheaders = $data;
            } else {
                $data = array_combine($columnheaders, $data);
                foreach($data as $Key => $Value){
                    if (strpos($Value, '+') !== False && strpos($Value, "'") === 0){
                        $data[$Key] = substr($Value, 1, strlen($Value)-1);
                    }
                }
                if ($primarykey){
                    $returndata[$data[$primarykey]] = $data;
                } else {
                    $returndata[] = $data;
                }
            }
            $row++;
        }
        fclose($handle);
    }
    return $returndata;
}

     function getColumnNames($Table, $ignore = "", $justColumnNames = false){
        $Columns = TableRegistry::get($Table)->schema();
        $Data = getProtectedValue($Columns, "_columns");
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