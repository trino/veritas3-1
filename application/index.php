<!DOCTYPE html><TITLE>MEE</TITLE>
<STYLE>
    .required:after {
        content: " *";
        color: #e32;
    }
 
    .content{
        width: 70% !important;
    }

    @media print {
        .content{
            width: 90% !important;
        }

        a[href]:after {
            content: none !important;
        }

        .no-print, .no-print * {
            display: none !important;
        }


        .splitcolsOLD {
            -webkit-column-count: 2 !important; /* Chrome, Safari, Opera */
            -moz-column-count: 2 !important; /* Firefox */
            column-count: 2 !important; */
        }

        .row {
            margin-left: -30px;
            margin-right: -30px;
        }

        .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
            float: left;
        }
        .col-md-12 {
            width: 100%;
        }
        .col-md-11 {
            width: 91.66666666666666%;
        }
        .col-md-10 {
            width: 83.33333333333334%;
        }
        .col-md-9 {
            width: 75%;
        }
        .col-md-8 {
            width: 66.66666666666666%;
        }
        .col-md-7 {
            width: 58.333333333333336%;
        }
        .col-md-6 {
            width: 50%;
        }
        .col-md-5 {
            width: 41.66666666666667%;
        }
        .col-md-4 {
            width: 33.33333333333333%;
        }
        .col-md-3 {
            width: 25%;
        }
        .col-md-2 {
            width: 16.666666666666664%;
        }
        .col-md-1 {
            width: 8.333333333333332%;
        }

    }

     .nowrap{
         white-space: nowrap;
     }
</STYLE>

<?php
//include_once ($dirroot . '/../webroot/subpages/api.php');
include_once ('api.php');
$offsethours = 0;
$AllowUploads = ' style="display: none"';
$doback = true;
$dosubmit = true;
$language = get("language", "English");
$settings = array();
$is_disabled=false;
$DoVeritas=false;

if(isset($_GET["client_id"])){
    $clientID = $_GET["client_id"];
    $client = first("SELECT * FROM clients WHERE id = " . $clientID);
    $logo = "img/jobs/" . $client["image"];
    $isGFS = false;
} else {
    $client = first("SELECT * FROM clients WHERE company_name LIKE 'GFS%' OR company_name LIKE 'Gordon%'");//Find gordon food services
    $clientID = $client["id"];
    $logo = "img/logo.png";
    $isGFS = true;
}
$clientname = $client["company_name"];

function offsettime($value, $period = "minutes", $date = "", $format = "Y-m-d H:i:s"){
    if (!$date) {$date = date($format);}
    $newdate= date_create($date);
    if ($value < 0) {$direction = "";} else {$direction = "+";}
    if ($value) {$newdate->modify($direction . $value . " " . $period);};
    return $newdate->format($format);
}

$today = date("Y-m-d");

//returns the order ID
function constructorder($title, $user_id, $client_id, $conf_recruiter_name, $conf_driver_name, $forms, $otherdata = "", $order_type = "PSA"){
    global $offsethours, $con;
    $data = array("created" => offsettime($offsethours, "hours"), "socialdate1" => date('Y-m-d'), "socialdate2" => date('Y-m-d'), "physicaldate" => date('Y-m-d'));
    $data["description"] = "Website order";
    $data["title"] = $title;
    $data["user_id"] = $user_id;
    $data["client_id"] = $client_id;
    $data["conf_recruiter_name"] = $conf_recruiter_name;
    $data["conf_driver_name"] = $conf_driver_name;
    $data["forms"] = $forms;
    $data["order_type"] = $order_type;
    if(is_array($otherdata)){
        $data = array_merge($data, $otherdata);
    }
    insertdb($con, "orders", $data);
    return mysqli_insert_id($con);
}
function constructdocument($orderid, $document_type, $sub_doc_id, $user_id, $client_id, $uploaded_for = 0, $draft = 0, $Execute = True){
    //id, order_id, document_type, sub_doc_id, title, description, scale, reason, suggestion, user_id, client_id, uploaded_for, created, draft, file
    global $offsethours, $con;
    $data = array("created" => offsettime($offsethours, "hours"), "order_id" => $orderid);
    //$data["description"] = "Website order";
    $data["document_type"] = $document_type;
    $data["sub_doc_id"] = $sub_doc_id;
    $data["user_id"] = $user_id;
    $data["client_id"] = $client_id;
    $data["uploaded_for"] = $uploaded_for;
    $data["draft"] = $draft;
    $data = insertdb($con, "documents", $data, "", $Execute);//$conn, $Table, $DataArray, $PrimaryKey = "", $Execute =
    //die("<BR>Current date: " . $this->offsettime(0, "hours"));
    if($Execute){ return mysqli_insert_id($con); }
    return $data;
}

function constructsubdoc($data, $formID, $userID, $clientID, $orderid=0, $Execute = True){
    global $con;
    $subdocinfo = first("SELECT * from subdocuments WHERE id = " . $formID);
    $table = $subdocinfo["table_name"];
    $docTitle = $subdocinfo["title"];
    $docid = constructdocument($orderid, $docTitle, $formID, $userID, $clientID, $userID,0, $Execute);//22= doc id number, 81 = user id for SMI site, 1=client id for SMI
    $data["document_id"] = $docid;
    $data["order_id"] = $orderid;
    $data["client_id"] = $clientID;
    $data["user_id"] = $userID;
    if(!$Execute){$data["document_id"] = " -- No Document ID --- ";}
    $remove = "";
    switch ($formID){
        case 9:
            $remove = array("count_past_emp", "attach_doc");
            break;
        case 4:
            $remove = array('customlink');
    }
    if (is_array($remove)){
        foreach($remove as $key){
            unset($data[$key]);
        }
    }
    $ret="";
    switch ($formID){
        case 9:
            $formcount = countforms($data);
            for($index = 0; $index < $formcount; $index ++){
                $form = converge($data, $index);
                $ret .= "<BR>" . insertdb($con, $table, $form, "", $Execute);
            }
            break;
        default:
            $ret = "<BR>" . insertdb($con, $table, $data, "", $Execute);
    }
    if($Execute){return $docid;}
    return $docid . $ret;
}

function countforms($array){
    foreach($array as $Key => $Value) {
        if (is_array($Value)) {
            return count($Value);
        }
    }
    return 0;
}

function converge($array, $index){
    $data = array();
    foreach($array as $Key => $Value){//it's doing some weird thing where values are put in arrays instead
        $newKey = $Key;
        if (strpos($Key, "_")){//remove numbers from the end
            $newKey =  explode("_", $Key);
            $lastvalue = $newKey[count($newKey)-1];
            if (is_numeric($lastvalue)){
                $newKey = str_replace("_" . $lastvalue, "", $Key);
            } else {
                $newKey = $Key;
            }
        }

        if (is_array($Value)){
            if($index < count($Value)) {
                $data[$newKey] = $Value[$index];
            }
        } else {
            $data[$newKey] = $Value;
        }
    }
    return $data;
}

function AJAX($Query){
    $URL =  "http://" . $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    $URL = str_replace("index.php", "", $URL);
    $Q = strpos($URL, "?");
    if($Q){$URL = substr($URL, 0, $Q);}
    $URL = str_replace("application/", "", $URL);//must be updated to the current path of the file
    return file_get_contents($URL . $Query );
}

function handlemsg($strings = "", $bypass = false) {
    global $clientname;
    $message = "";
    if ($bypass || isset($_GET["msg"])) {
        if (!$bypass && isset($_GET["msg"])) {$bypass = $_GET["msg"];}
        if (isset($strings["uniform_" . $bypass])) {$message = $strings["uniform_" . $bypass];}
        if ($message) {
            $Client_ID = 26;
            if(isset($_GET["client_id"])){ $Client_ID =$_GET["client_id"]; }
            $message = str_replace("%client%", $clientname, str_replace("Gordon Food Service", $clientname, $message));

            echo '<div class="alert alert-info"><button class="close" data-close="alert"></button>' . $message . '</div>';
        }
    }
}

if (count($_POST) > 0) {
    $strings = CacheTranslations($language, array("uniform_%", "addorder_back"), $settings);
    includeCSS("login");
    include_once ('api.php');
    //var_dump($_POST);
    //$_POST = converge($_POST); //do not do
    echo '<div class="logo"></div><div class="content">';
    $dosubmit = false;

    $userID = get("user_id", 81);//TEST DATA
    $Execute = true;//False = test mode
    unset($_POST["msg"]);

    switch ($_GET["form"]) {
        case 4://consent: offence, date_of_sentence, location go into consent_form_criminal
            $offences = $_POST["offence"];
            $date_of_sentences = $_POST["date_of_sentence"];
            $locations = $_POST["location"];
            unset($_POST["offence"]);
            unset($_POST["date_of_sentence"]);
            unset($_POST["location"]);
            unset($_GET['customlink']);
            break;
        case "driver":
            savedriver($webroot);
            break;
        case "orderstatus":
            orderstatus($webroot);
            break;
        case "test":
            echo "POST DATA: ";
            print_r($_POST);
            die();
            break;
    }

    $query = constructsubdoc($_POST, $_GET["form"], $userID, $clientID, 0, $Execute);
    $redir = "";
    if($Execute) {
        switch ($_GET["form"]) {
            case 4://consent: offence, date_of_sentence, location go into consent_form_criminal
                $data = array("consent_form_id" => mysqli_insert_id($con));//might use $query instead
                foreach($offences as $ID => $offense){
                    $data["offence"] = $offense;
                    $data["date_of_sentence"] = $date_of_sentences[$ID];
                    $data["location"] = $locations[$ID];
                    insertdb($con, "consent_form_criminal", $data, "", $Execute);
                }
                Query("UPDATE profiles SET iscomplete = 1 WHERE id = " . $userID . ";");
                break;
            case 9://letter of experience
                $redir = '<script> window.location = "?form=4&msg=success&user_id=' . $_POST["user_id"] . '&client_id=' . $clientID . '"; </script>';
                break;
        }

        //AJAX("clients/quickcontact?Type=email&user_id=" . $_POST["user_id"] . "&doc_id=" . $query . "&form=" . $_GET["form"] . "&client_id=" . $clientID);
        //echo "Application submitted successfully. A GFS employee will get in touch with you shortly";
    var_dump($strings);
        handlemsg(uniform_done, "done");
        if($redir){ echo "<P>" . $redir;}
        //echo "<P>" . $query;
    } else {
        echo "<P>" . $query . "<P>";
    }
} else {
    includeCSS("login");
    $is_disabled = '';
    if (isset($disabled)){ $is_disabled = 'disabled="disabled"';}
    $strings = CacheTranslations($language, array("orders_%", "forms_%", "documents_%", "profiles_null", "clients_addeditimage", "addorder_%", "uniform_%", "verifs_%", "tasks_date"), $settings);
    $form="";
    if(isset($_GET["form"])){$form=$_GET["form"];}

    echo '<FORM ACTION="" METHOD="POST" ID="myForm"';
    if ($form == "driver"){echo ' enctype="multipart/form-data"';}
    echo '><div class="logo"></div> <div class="content">';

    $ignore = array("language", "form");
    foreach($_GET as $Key => $Value){
        if (!in_array($Key, $ignore)){
            echo '<INPUT TYPE="HIDDEN" NAME="' . $Key . '" VALUE="' . $Value . '">';
        }
    }

    if (isset($_GET["user_id"])) {
        if (get("form")) {
            $profile = first("SELECT * FROM profiles WHERE id = " . $_GET["user_id"]);
            //print_r ($profile);
        }
    } else if($form == "driver" || $form = "orderstatus"){
        //GNDN
    } else if($form != "thankyou") {
        $dosubmit= false;
        echo '<div class="alert alert-danger display-hide no-print" style="display: block;">' . $strings["uniform_nouserid"] . '</div>';
    }
    handlemsg($strings);

   // echo '<a href="javascript:window.print();" class="floatright btn btn-primary no-print" style="float:right;">' . $strings["dashboard_print"] . '</a>';
    echo '<DIV ALIGN="CENTER"><img style="max-width: 100px;" src="' . $webroot . $logo . '"  /></DIV>';//gfs
?>
<script src="../webroot/assets/admin/pages/scripts/form-validate-roy.js"></script>
<SCRIPT>
    var reasons = new Array();
    reasons["fail"] = '<?= addslashes($strings["forms_failed"]); ?>';
    reasons["postalcode"] = '<?= addslashes($strings["forms_postalcode"]); ?>';
    reasons["phone"] = '<?= addslashes($strings["forms_phone"]); ?>';
    reasons["email"] = '<?= addslashes($strings["forms_email"]); ?>';
    reasons["sin"] = '<?= addslashes($strings["forms_sin"]); ?>';
    reasons["required"] = '<?= addslashes($strings["forms_fillall"]); ?>';
    reasons["postalzip"] = '<?= addslashes($strings["forms_postalzip"]); ?>';

    $(document).ready(function () {
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        // Login.init();
        Demo.init();
    });

    var language = '<?= $language ?>';
    $(function () {
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '1980:2020',
            dateFormat: 'mm/dd/yy'
        });
    });

    <?php loadstringsJS($strings); ?>

    function checkformext(){//do not add code to this function
        var ret = true;
        if (!checkalltags(false)){return false;}

        if (typeof checkformint == 'function') {
            ret = checkformint();
        }
        if(ret){
            document.getElementById("myForm").submit();
        }
    }

    <?php loadstringsJS($strings); ?>
</SCRIPT>
<?php
    $stages = "";
    $Form = get("form");
    switch ($Form){
        case "thankyou":
            handlemsg($strings, "done");
            $dosubmit= false;
        break;
        case 9:
            $stages = " (2 of 3)";
            include("forms/loe.php");//works!
        break;
        case 4:
            $stages = " (3 of 3)";
            include("forms/consent.php");
        break;
        case 24:
            include("../webroot/subpages/documents/edu_verifs.php");
        break;
        default:
            if(file_exists("forms/" . $Form . '.php')){
                include("forms/" . $Form . ".php");
            } else if ($_SERVER['SERVER_NAME']  == "localhost") {
                $doback = false;
                $DoVeritas=true;
                echo $strings["uniform_pleaseselect"] . ":<UL>";
                $forms = array(4, 9, 24);
                $fieldname = "title";
                if ($language != "English" && $language != "Debug") {
                    $fieldname .= $language;
                }

                $Files = scandir("forms");
                removefromarray($Files, array(".", "..", "consent.php", "loe.php"));
                foreach ($forms as $formID) {
                    $form = first("SELECT * FROM subdocuments WHERE id = " . $formID);
                    if ($form[$fieldname]) {
                        echo '<LI><A HREF="' . getq("form=" . $formID) . '">' . ucfirst($form[$fieldname]) . '</A></LI>';
                    }
                }
                foreach($Files as $Filename){
                    $Filename = left($Filename, strlen($Filename) - 4);//chop off .php
                    echo '<LI><A HREF="' . getq("form=" . $Filename) . '">' . ucfirst($Filename) . '</A></LI>';
                }
                //echo '<LI><A HREF="' . getq("form=driver") . '">New Driver</A></LI>';
                //echo '<LI><A HREF="' . getq("form=orderstatus") . '">Order Status</A></LI>';
                //echo '<LI><A HREF="' . getq("form=test") . '">Test</A></LI>';
                echo '<LI><A HREF="assets/consentform.pdf" download="consentform.pdf">Consent Form PDF</A></LI>';
                echo '<LI><A HREF="readme.txt">Read me (API)</A></LI>';
                echo '<LI><A HREF="30days.php' . getq() . '">30 days</A></LI>';
                echo '<LI><A HREF="60days.php' . getq() . '">60 days</A></LI>';
                echo '<LI><A HREF="apply.php' . getq() . '">Apply (GFS)</A></LI>';
                echo '<LI><A HREF="huron.php' . getq() . '">Apply (Huron)</A></LI>';
                echo '<LI><A HREF="register.php' . getq() . '">Register</A></LI>';
                echo "</UL>";
            }
    }
}

function removefromarray(&$array, $value){
    if(is_array($value)){
        foreach($value as $val){
            removefromarray($array, $val);
        }
    } else {
        $Index = array_search($value, $array);
        if ($Index > -1) {unset($array[$Index]);}
    }
}

function getq($data = ""){
    if( $_SERVER['QUERY_STRING']){
        if($data) {
            return "?" . $_SERVER['QUERY_STRING'] . "&" . $data;
        } else {
            return "?" . $_SERVER['QUERY_STRING'];
        }
    } elseif($data) {
        return "?" . $data;
    }
}

function array_flatten($array) {
    if(isset($array["form"])) {
        foreach ($array["form"] as $ID => $Data) {
            foreach ($Data as $Key => $Value) {
                $array["data[" . $ID . "][" . $Key . ']'] = $Value;
            }
        }
    }
    $array["password"] = md5($array["password"]);
    unset($array["MAX_FILE_SIZE"]);//not needed
    unset($array["form"]);
    return $array;
}

function printthrobber($status){
    if($status){
        echo '<DIV ID="LOADING" align="center"><IMG SRC="../webroot/assets/admin/layout/img/loading-spinner-blue.gif">' .  str_pad(' ',1024). "\n</DIV>";
        flush();//http://php.net/manual/en/function.flush.php
    } else {
        echo '<div class="clearfix"></div><SCRIPT>removeelement("LOADING");</SCRIPT>';
    }
}
function orderstatus($webroot){
    printthrobber(true);
    $URL = "http://" . $_SERVER['SERVER_NAME'] . str_replace("webroot/", 'rapid/placerapidorder?action=orderstatus&', $webroot);
    $_POST["password"] = md5($_POST["password"]);
    $URL .= implode2($_POST, "=", "&");
    echo "URL = " . $URL;
    $Result = file_get_contents($URL);
    echo "<BR>Result = " . $Result;
    printthrobber(false);
    die();
}

function savedriver($webroot){
    printthrobber(true);
    foreach($_FILES as $FormName => $Data){
        if($Data["error"] == 0 && is_uploaded_file($Data["tmp_name"])){
            $Filename = str_replace("FILE", "BASE", $FormName);
            $_POST[$Filename] = base64encodefile($Data["tmp_name"], extension($Data["name"]));
            unlink($Data["tmp_name"]);
        } else if($Data["error"] != UPLOAD_ERR_NO_FILE) {
            $Reasons = array(
                UPLOAD_ERR_INI_SIZE =>      "The uploaded file exceeds the upload_max_filesize directive in php.ini",
                UPLOAD_ERR_FORM_SIZE =>     "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
                UPLOAD_ERR_PARTIAL =>       "The uploaded file was only partially uploaded",
                UPLOAD_ERR_NO_TMP_DIR =>    "Missing a temporary folder",
                UPLOAD_ERR_CANT_WRITE =>    "Failed to write file to disk",
                UPLOAD_ERR_EXTENSION =>     "An unknown PHP extension stopped the file upload. Examining the list of loaded extensions with phpinfo() may help"
            );
            die($FormName . " (" . $Data["name"] . ") failed to upload: " . $Reasons[$Data["error"]]);
        }
    }

    $URL = "http://" . $_SERVER['SERVER_NAME'] . str_replace("webroot/", 'rapid/placerapidorder', $webroot);
    echo "URL = " . $URL . '<BR>';
    $_POST = array_flatten($_POST);
    $Result = cURL($URL, $_POST);
    //$Result = json_encode(array("Status" => True, "OrderID" => 993));
    echo "Result = " . $Result . '<BR>$_POST = ' . tostring($_POST) . ';';
    $Result = toarray($Result);
    if($Result->Status){
        $URL = str_replace("webroot/", "", $webroot) . 'orders/vieworder/' . $_POST["clientid"] . '/' . $Result->OrderID . '?order_type=' . $_POST["ordertype"] . '&forms=' . $_POST["forms"];
        //echo '<BR><A HREF="' . $URL . '" target="_blank">Click here to view the order</A>';
        echo '<DIV ID="orderstatus"><A onclick="return checkorderstatus(' . $Result->OrderID . ');">Click here to view the status of the order</A></DIV>';
    }
    printthrobber(false);
    ?><SCRIPT>
    function checkorderstatus(OrderID){
        var element = document.getElementById("orderstatus");
        element.innerHTML = '<IMG SRC="../webroot/assets/admin/layout/img/loading-spinner-blue.gif">';
        $.ajax({
            url: "<?= str_replace("webroot/", "", $webroot) . 'rapid/placerapidorder'; ?>",
            type: "post",
            dataType: "HTML",
            data: 'action=orderstatus&orderid=' + OrderID + '&username=<?= $_POST["username"]; ?>&password=<?= $_POST["password"]; ?>&pretty=true',
            success: function (msg) {
                element.innerHTML = msg;
            }
        });
        return false;
    }
    </SCRIPT><?php
    die();
}

function tostring($Array){
    $tempstr = false;
    $Delimeter = '[';
    foreach($Array as $Key => $Value){
        if($tempstr) {$Delimeter = ', ';}
        if(is_array($Value)){
            $tempstr .= $Delimeter . '"' . $Key . '" => ' . tostring($Value) ;
        } else {
            $tempstr .= $Delimeter . '"' . $Key . '" => "' . str_replace('"', "'", $Value) . '"';
        }
    }
    return $tempstr . ']';
}
function toarray($Result){
    $start = strpos($Result, "{");
    $end = strpos($Result, "}");
    if($start !== False && $end !==False) {
        $Result = right($Result, strlen($Result) - $start);
        return json_decode($Result);
    }
    return array("Result" => False);
}
?>
<?php if($doback || $DoVeritas){
    if ($dosubmit && !$DoVeritas){ ?>
        <div class="clearfix"></div>
        <INPUT TYPE="button" class="btn btn-primary btn-lg" onclick="return checkformext();" VALUE="<?php echo (isset($_GET['customlink']))?'Submit':'Next Step'.$stages;?>" STYLE="float: right;" oldtitle="<?=$strings["forms_submit"];?>">
        <div class="clearfix"></div>
    <?php }
        backbutton($strings["addorder_back"], $DoVeritas);
    } ?>
</div></form>
</BODY>