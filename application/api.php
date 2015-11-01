<?php
$webroot = $_SERVER["REQUEST_URI"];
$start = strpos($webroot, "/", 1) + 1;
$webroot = substr($webroot, 0, $start) . "webroot/";
if ( $_SERVER["SERVER_NAME"] != "localhost"){$webroot = str_replace("application/", "", $webroot);}
$dirroot = getcwd();

error_reporting(E_ERROR | E_PARSE);//suppress warnings
include("../config/app.php");//config file is not meant to be run without cake, thus error reporting needs to be suppressed
error_reporting(E_ALL);//re-enable warnings

$con = "";

function connectdb() {
    global $con, $config;
    $localhost = "localhost";
    if ( $_SERVER["SERVER_NAME"] == "localhost"){$localhost.= ":3306";}
    $con = mysqli_connect($localhost, $config['Datasources']['default']['username'], $config['Datasources']['default']['password'], $config['Datasources']['default']['database']) or die("Error " . mysqli_connect_error($con));
    return $con;
}

function left($text, $length){
    return substr($text,0,$length);
}
function right($text, $length){
    return substr($text, -$length);
}
function mid($text, $start, $length){
    return substr($text,$start, $length);
}

function initdatepicker($dateformat = 'yy-mm-dd'){
    ?>
    <SCRIPT>
        $(function () {
            $(".datepicker").datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: '1980:2020',
                dateFormat: '<?= $dateformat; ?>'
            });
        });
    </SCRIPT>
    <?php
}

function backbutton($text = "Back", $DoVeritas = false){
    if ( $_SERVER["SERVER_NAME"] == "localhost") {
        echo '<DIV align="center" class="no-print"><A HREF="';
        if($DoVeritas){
            echo "../";
        } else {
            echo 'index.php';
            if (isset($_GET["user_id"])) {
                echo "?user_id=" . $_GET["user_id"];
            } else if (isset($_GET["p_id"])) {
                echo "?user_id=" . $_GET["p_id"];
            }
        }
        echo '">' . $text . '</A></DIV>';
    }
}

function insertdb($conn, $Table, $DataArray, $PrimaryKey = "", $Execute = True){
    if (is_object($conn)){$DataArray = escapearray($conn, $DataArray);}
    $query = "INSERT INTO " . $Table . " (" . getarrayasstring($DataArray, True) . ") VALUES (" . getarrayasstring($DataArray, False) . ")";
    if($PrimaryKey) {
        $query.= " ON DUPLICATE KEY UPDATE";
        $delimeter = " ";
        foreach($DataArray as $Key => $Value){
            if($Key != $PrimaryKey){
                $query.= $delimeter . $Key . "='" . $Value . "'";
                $delimeter = ", ";
            }
        }
    }
    $query.=";";
    if($Execute && is_object($conn)) {
        mysqli_query($conn, $query) or die ('Unable to execute query. '. mysqli_error($conn) . "<P>Query: " . $query);
    }
    return $query;
}

function escapearray($conn, $DataArray){
    foreach($DataArray as $Key => $Value) {
        $DataArray[$Key] = mysqli_real_escape_string($conn, $Value);
    }
    return $DataArray;
}

function getarrayasstring($DataArray, $Keys = True){
    if ($Keys) {
        $DataArray = array_keys($DataArray);
        return implode(", ", $DataArray);
    } else {
        $DataArray = array_values($DataArray);
        $DataArray = implode("', '", $DataArray);
        return "'" . $DataArray . "'";
    }
}

function first($query) {
    global $con;
    $result = $con->query($query);
    if($result) {
        while ($row = mysqli_fetch_array($result)) {
            return $row;
        }
    }
}

$con = connectdb();

function get($Key, $default = ""){
    if (isset($_POST[$Key])) { return $_POST[$Key];}
    if (isset($_GET[$Key])) { return $_GET[$Key];}
    return $default;
}

function Query($query){
    global $con;
    return $con->query($query);
    //use while ($row = mysqli_fetch_array($result)) { to get results
}

function extension($Filename){
    $type = strtolower(pathinfo($Filename, PATHINFO_EXTENSION));
    if($type == "jpeg"){$type="jpg";}
    return $type;
}

function base64encodefile($Filename, $Extension = ""){
    if (file_exists($Filename)) {
        if(!$Extension){$Extension= extension($Filename);}
        return "data:image/" . $Extension . ";base64," . base64_encode(file_get_contents($Filename));
    }
}

function CacheTranslations($Language='English', $Text, $Variables = "", $Common = True){
    global $con;
    $wasdebug = false;
    if ($Language == "Debug"){ $Language = "English"; $wasdebug= true;}
    $GLOBALS["language"] = $Language;
    if (!is_array($Text)) {
        $Text = array($Text);
    }
    if (is_object($Variables)) {
        $Variables = s($Variables);
    }

    if ($Common) {
        $Text[] = "dashboard_%";//for all pages
        $Text[] = "settings_%";//for all pages
        $Text[] = "index_%";//for all pages
    }

    $query="SELECT Name, " . $Language . " FROM strings WHERE Name = 'Date'";
    foreach($Text as $text){
        if(strlen($query)>0){ $query.= " OR ";}
        if (strpos($text, "%")){
            $query .= "Name LIKE '" . strtolower($text) . "'";
        } else {
            $query .= "Name = '" . strtolower($text) . "'";
        }
    }

    $data = array();
    $result = $con->query($query);
    while ($row = mysqli_fetch_array($result)) {
        $data[$row["Name"]] = $row[$Language];
    }
    if ($wasdebug){
        foreach($data as $Key => $Value){
            $data[$Key] = "[" . $Key . "]";
        }
    }

    return $data;
}

function includeCSS($Class = ""){
    global $webroot;
    if($Class ){
        ?>

        <!--[if IE 8]>
        <html lang="en" class="ie8 no-js"> <![endif]-->
        <!--[if IE 9]>
        <html lang="en" class="ie9 no-js"> <![endif]-->
        <!--[if !IE]><!-->
        <html lang="en">
        <!--<![endif]-->
        <!-- BEGIN HEAD -->
        <head>
            <meta charset="utf-8"/>
            <title></title>
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
            <meta http-equiv="Content-type" content="text/html; charset=utf-8">
            <meta content="" name="description"/>
            <meta content="" name="author"/>
            <!-- BEGIN GLOBAL MANDATORY STYLES -->
            <link href="<?= $webroot; ?>../application/assets/opensans.css" rel="stylesheet"
                  type="text/css"/>
            <link href="<?= $webroot; ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"
                  type="text/css"/>
            <link href="<?= $webroot; ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet"
                  type="text/css"/>
            <link href="<?= $webroot; ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"
                  type="text/css"/>
            <link href="<?= $webroot; ?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet"
                  type="text/css"/>
            <!-- END GLOBAL MANDATORY STYLES -->
            <!-- BEGIN PAGE LEVEL STYLES -->
            <link href="<?= $webroot; ?>assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
            <link href="<?= $webroot; ?>assets/admin/pages/css/login3.css" rel="stylesheet" type="text/css"/>
            <!-- END PAGE LEVEL SCRIPTS -->
            <!-- BEGIN THEME STYLES -->
            <link href="<?= $webroot; ?>assets/global/css/components.css" id="style_components" rel="stylesheet"
                  type="text/css"/>
            <link href="<?= $webroot; ?>assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
            <link href="<?= $webroot; ?>assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
            <link href="<?= $webroot; ?>assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css"
                  id="style_color"/>
            <link href="<?= $webroot; ?>assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
            <!-- END THEME STYLES -->
            <link rel="shortcut icon" href="favicon.ico"/>
            <!-- END COPYRIGHT -->
            <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
            <!-- BEGIN CORE PLUGINS -->
            <!--[if lt IE 9]>
            <script src="<?= $webroot; ?>assets/global/plugins/respond.min.js"></script>
            <script src="<?= $webroot; ?>assets/global/plugins/excanvas.min.js"></script>
            <![endif]-->
            <link href="<?= $webroot; ?>../application/assets/jquery-ui.css" rel="stylesheet" type="text/css"/>


            <script src="<?= $webroot; ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
            <script src="<?= $webroot; ?>assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
            <script src="<?= $webroot; ?>../application/assets/jquery-ui.min.js" type="text/javascript"></script>
            <script src="<?= $webroot; ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
            <script src="<?= $webroot; ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
            <script src="<?= $webroot; ?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
            <script src="<?= $webroot; ?>assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
            <!-- END CORE PLUGINS -->
            <!-- BEGIN PAGE LEVEL PLUGINS -->
            <script src="<?= $webroot; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
            <script type="text/javascript" src="<?= $webroot; ?>assets/global/plugins/select2/select2.min.js"></script>
            <!-- END PAGE LEVEL PLUGINS -->
            <!-- BEGIN PAGE LEVEL SCRIPTS -->
            <script src="<?= $webroot; ?>assets/global/scripts/metronic.js" type="text/javascript"></script>
            <script src="<?= $webroot; ?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
            <script src="<?= $webroot; ?>assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
            <!--script src="<?= $webroot; ?>assets/admin/pages/scripts/login.js" type="text/javascript"></script-->
            <!-- END PAGE LEVEL SCRIPTS -->
            <script>
                $(document).ready(function () {
                    Metronic.init(); // init metronic core components
                    Layout.init(); // init current layout
                    // Login.init();
                    Demo.init();
                });

                function removeelement(id) {
                    return (elem=document.getElementById(id)).parentNode.removeChild(elem);
                }
            </script>
            <!-- END JAVASCRIPTS -->
        </head>
        <body class="<?= $Class ?>">

        <?php
    } else {
        $CSSdir = $webroot . "assets/admin/layout/css";
        echo '<link href="' . $webroot . 'assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>';
        echo '<link href="' . $webroot . 'assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>';
        echo '<link href="' . $webroot . 'assets/admin/pages/css/login3.css" rel="stylesheet" type="text/css"/>';
        echo '<link href="' . $webroot . 'assets/global/css/components.css" rel="stylesheet" type="text/css"/>';
    }
}

function provinces($name, $value = "", $required = false){
    echo '<SELECT class="form-control" name="' . $name . '"';
    if ($required) { echo " required"; }
    echo '>';
    $acronyms = getprovinces("Acronyms");
    $Provinces = getprovinces("");
    $ID=0;
    foreach($acronyms as $acronym){
        echo '<OPTION value="' . $acronym . '"';
        if ($value == $acronym || $value == $Provinces[$ID]) { echo " SELECTED";}
        echo '>' . $Provinces[$ID] . '</OPTION>';
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
            $provinces = array("Choisir la province", "Alberta", "la Colombie-Britannique", "Manitoba", "Nouveau-Brunswick", "Terre-Neuve-et-Labrador", "Territoires du Nord-Ouest", "la Nouvelle-Écosse", "Nunavut", "Ontario", "Prince-Édouard Island", "Le Québec", "Saskatchewan", "Yukon");
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

function addTrans($array, $Trans = ""){
    if($Trans){
        foreach($array as $Key => $Value){
            $array[$Key] = $Value . $Trans;
        }
    }
    return $array;
}

function printoption($option, $selected = "", $value = ""){
    $tempstr = "";
    if ($option == $selected) {$tempstr = " selected";}
    if (strlen($value) > 0) {$value = " value='" . $value . "'";}
    echo '<option' . $value . $tempstr . ">" . $option . "</option>";
}

function printoptions($name, $valuearray, $selected = "", $optionarray, $isdisabled = ""){
    echo '<SELECT ' . $isdisabled . ' name="' . $name . '" class="form-control member_type" >';
    for ($temp = 0; $temp < count($valuearray); $temp += 1) {
        printoption2($valuearray[$temp], $selected, $optionarray[$temp]);
    }
    echo '</SELECT>';
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

function isJson($string) {
    if($string && !is_array($string)){
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
function cURL($URL, $data = "", $username = "", $password = ""){
    $session = curl_init($URL);
    curl_setopt($session, CURLOPT_HEADER, true);
    //curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);//not in post production
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_POST, true);
    if($data) {
       // if(is_array($data)){
       //     $data = http_build_query($data);
       // }
        curl_setopt ($session, CURLOPT_POSTFIELDS, $data);
    }
    //$datatype = "application/x-www-form-urlencoded;charset=UTF-8";
    $datatype= "multipart/form-data";
    if(isJson($data)){$datatype  = "application/json";}

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
        $response = "[Error: " . curl_error($session) . ']';
    }
    curl_close($session);
    $FIND="Content-Type: text/html";
    $START = strpos($response, $FIND);
    if($START){$response = substr($response,$START + strlen($FIND) + 4);}
    if(!$response){return "ERROR: Blank data could mean a missing this reference";}
    return $response;
}



function debug_string_backtrace() {
    $BACK = debug_backtrace(0);
    $BACK[2]["line"] = $BACK[1]["line"];
    return $BACK[2];
}

function implode2($Array, $SmallGlue, $BigGlue){
    foreach($Array as $Key => $Value){
        $Array[$Key] = $Key . $SmallGlue. $Value;
    }
    return implode($Array,$BigGlue);
}
function debug($Iterator, $DoStacktrace = true){
    if($DoStacktrace) {
        $Backtrace = debug_string_backtrace();
        echo '<B>' . $Backtrace["file"] . ' (line ' . $Backtrace["line"] . ') From function: ' . $Backtrace["function"] . '();</B> ';
    }

    if(is_array($Iterator)){
        echo '(array)<BR>';
        var_dump($Iterator);
    } else if (is_object($Iterator)) {
        if(is_iterable($Iterator)) {
            echo '(object array)<BR>';
            foreach ($Iterator as $It) {
                debug($It, false);
            }
        } else {
            echo '(object)<BR>';
            var_dump($Iterator);
        }
    } else {
        echo '(value)<BR>';
        echo $Iterator;
    }
}
function is_iterable($var) {
    return (is_array($var) || $var instanceof Traversable);
}
?>