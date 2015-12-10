<STYLE>
    .btntop {
        display: inline-block;
        position: fixed;
        top: 50px;
        z-index: 10001;
        right: 10px;
    }
</STYLE>
<?php
    if ($this->request->session()->read('debug')) {
        echo "<span style ='color:red;'>subpages/profile/emaillog.php #INC???</span>";
    }
    include_once("subpages/api.php");
    $Filename = "royslog.txt";
    if(isset($_GET["filename"]) && $_GET["filename"]){
        $Filename = $_GET["filename"];
    }
    $Extension = getextension($Filename);
    if(file_exists($Filename) && $Filename == "royslog.txt") {
        if (isset($_GET["delete"])) {
            unlink($Filename);
            echo '<SCRIPT>
                        $( document ).ready(function() {
                            var URL = window.location + "";
                            URL = URL.replace("&delete", "");
                            ChangeUrl("log file", URL);
                        });
                  </SCRIPT>';
        } else {
            echo '<a HREF="' . $this->request->webroot . 'profiles/settings?includeonly=profile/emaillog.php&delete" onclick="return confirm(';
            echo "'Are you sure you want to delete the log file?'";
            echo ');" class="btn btn-danger btntop">Delete</a>';
        }
    }
    if($Extension == "txt"){ echo '<PRE>';}
    if (file_exists($Filename)) {
        if($Extension == "php"){
            include($Filename);
        } else {
            readfile($Filename);
        }
    } else {
        echo 'The log file is empty';
    }
    if($Extension == "txt"){ echo '</PRE>';}
?>
<SCRIPT>
    function ChangeUrl(page, url) {
        if (typeof (history.pushState) != "undefined") {
            var obj = {Page: page, Url: url};
            history.pushState(obj, obj.Page, obj.Url);
            return true;
        }
    }
</SCRIPT>
