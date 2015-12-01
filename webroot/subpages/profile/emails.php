<?php
if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/profile/emails.php #INC???</span>"; }

$languages = array("English", "French");
$strings2 = array();
foreach($languages as $language){
    $data = CacheTranslations($language, array("email_%"), $settings, False);
    unset($data["Date"]);
    $strings2[$language] = $data;
}

$fullmode = false;

$emails = array();

foreach($strings2["English"] as $Key => $Data){
    $fullmode = strpos($Key, "_subject") || strpos($Key, "_message");
    break;
}

$FirstLanguage = $languages[0];
$strings3 = $strings2[$FirstLanguage];
if(!$fullmode) {$languages = array($FirstLanguage);}

if(!function_exists("cleanit")) {
    function cleanit($array) {
        return str_replace("\r\n", "", str_replace('\"', '"', addslashes(implode('", "', $array))));
    }
}

foreach($strings3 as $Key => $Data){
    $currentemail = array();
    if ($fullmode){
        $name = str_replace("email_", "", $Key);
        if (strpos($Key, "_subject")){
            $name = str_replace("_subject", "", $name);
            $currentemail["subject[" . $FirstLanguage . "]"] = $Data;
            $currentemail["message[" . $FirstLanguage . "]"] = $strings3["email_" . $name . "_message"];
            if (isset($strings3["email_" . $name . "_variables"])) {$currentemail["variables"] = $strings3["email_" . $name . "_variables"];} else {$currentemail["variables"] = "";}
            foreach($languages as $language){
                if($language != $FirstLanguage){
                    $currentemail["subject[" . $language . "]"] = $strings2[$language]["email_" . $name . "_subject"];
                    $currentemail["message[" . $language . "]"] = $strings2[$language]["email_" . $name . "_message"];
                }
            }
        }
    } elseif(strpos($Key, "email_") === 0 && !strpos($Key, "_variables")) {
        $name = str_replace("email_", "", $Key);
        $currentemail["subject"] = $Data;
        $currentemail["message"] = $strings2["French"]["email_" . $name];
        if (isset($strings3[$Key . "_variables"])) {$currentemail["variables"] = $strings3[$Key . "_variables"];} else { $currentemail["variables"] = "";}
    }
    if($currentemail) {
        $emails[$name] = $currentemail;
    }
}

if(isset($_GET["export"])){
    echo $CRLF = "\r\n";
    foreach($emails as $Key => $Data){
        echo "[" . $Key . "]" . $CRLF;
        echo "Subject=" . $Data["subject[English]"] . $CRLF;
        echo "Email=" . $CRLF . $Data["message[English]"] . $CRLF . $CRLF;
    }
    die();
}

echo '<TABLE CLASS="table table-hover" width="100%"><THEAD><TH>Event</TH><TH WIDTH="100%">Email</TH></THEAD><TBODY><TD>';
echo '<div class="tabbable tabbable-custom"><ul class="nav"><LI><A onclick="return newkey();">[New event]</A></LI>';

$FirstEmail = "";
foreach($emails as $Key => $Data){
    if(!$FirstEmail){$FirstEmail = $Key;}
    echo '<LI><A onclick="return show(' . "'" . $Key  . "'" . ')">' . $Key . '</A></LI>';
}
echo "</DIV></DIV></TD><TD><H4>Global variables:</H4>";// %event%, %webroot%, %created%, %login%, %variables%, %site%";

function globalvariable($name, $tooltiptext, $first = false){
    if(!$first){ echo ", ";}
    $tooltiptext = str_replace("*", " (for debugging purposes)", $tooltiptext);
    echo '<SPAN TITLE="' . $tooltiptext . '">%' . $name . "%</SPAN>";
}

globalvariable("event", "Name of the event*", true);
globalvariable("variables", "A list of all the variables being passed to the event handler*");
globalvariable("webroot", "root directory of this site (" . $this->request->webroot . ")");
globalvariable("created", "the date and time the email was sent out");
globalvariable("login", "A hyperlink to the login page (" . LOGIN . ") saying 'Click here to login'");
globalvariable("site", "name of the site (" . $settings->mee . ")");

echo "<BR>If the subject contains [DISABLED], this event will not be sent out";

function printvariables($Variables){
    if($Variables) {
        echo "<H4>Local Variables:</H4> %" . str_replace(", ", "%, %", $Variables) . "%<P>";
    }
}

foreach($emails as $Key => $Data){
    echo '<div id="email_' . $Key . '" style="display: none;">';
    echo '<H3>' . $Key . '</H3>';
    printvariables($Data["variables"]);

    foreach($Data as $Key2 => $Value){
        if ($Key2 != "variables") {
            echo '<div class="form-group"><label class="control-label">' . $Key2 . ': </label>';
            $id = $Key . "_" . $Key2;
            if (strpos($Key2, "subject") === 0) {
                if (!$fullmode) {$Key2 = "[English]";}
                echo '<INPUT ONCHANGE="haschanged = true;" ID="' . $id . '" TYPE="TEXT" CLASS="form-control email_' . $Key . '" NAME="' . $Key2 . '" VALUE="' . $Value . '">';
            } elseif (strpos($Key2, "message") === 0) {
                if (!$fullmode) {$Key2 = "[French]";}
                echo '<TEXTAREA ROWS="5" ONCHANGE="haschanged = true;" ID="' . $id . '" CLASS="form-control email_' . $Key . '" NAME="' . $Key2 . '">' . $Value . '</TEXTAREA>';
            }
            echo '</DIV>';
        }
    }
    echo '</div>';
}
?></TD></TBODY>
<TFOOT>
<TD COLSPAN="2" ALIGN="RIGHT" valign="center">
    <CENTER>WARNING: Emails can only be edited by the primary translator, or the changes will be overwritten when the strings table gets updated next</CENTER>
    <button class="btn btn-danger" id="delete" onclick="deletekey(lastkey);">Delete</button>
    <button class="btn btn-primary" id="save" onclick="saveall(lastkey);">Save</button>
    <button class="btn btn-primary" id="send" onclick="sendtest(lastkey);" title="This will not substitute any variables">Send to yourself</button>
</TD>
</TFOOT></TABLE>
<script>
    var lastkey = "";
    var haschanged = false;
    function show(key){
        if(haschanged){
            if (!confirm("You have unsaved changes, are you sure you want to switch to a different email?")){
                return false;
            }
        }

        var element;
        if(lastkey){
            element = document.getElementById("email_" + lastkey);
            element.style.display = 'none';
        }
        element = document.getElementById("email_" + key);
        element.style.display = 'block';
        lastkey = key;
        haschanged = false;
        return false;
    }
    show("<?= $FirstEmail; ?>");

    function sendtest(key){
        if(key){
            $.ajax({
                url: "<?php echo $this->request->webroot;?>profiles/products",
                type: "post",
                dataType: "HTML",
                data: "Type=sendemail&event=" + key,
                success: function (msg) {
                    alert(msg);
                }
            })
        }
    }

    function saveall(key){
        if(!haschanged){
            alert("You have made no changes");
            return false;
        }

        var element = document.getElementById("save");
        var EndVar = "key=" + key;
        element.innerHTML = "Saving...";
        $('.email_' + key).each(function(){
            var NewVar = $(this).attr('name') + "=" + encodeURIComponent($(this).val());
            EndVar = EndVar + "&" + NewVar;
        });
        $.ajax({
            url: "<?php echo $this->request->webroot;?>profiles/products",
            type: "post",
            dataType: "HTML",
            data: "Type=editemail&" + EndVar,
            success: function (msg) {
                alert(msg);
                element.innerHTML = "Save";
                haschanged = false;
            }
        })
    }

    function escapeRegExp(string) {
        return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
    }
    function replaceAll(string, find, replace) {
        return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
    }


    function deletekey(thekey){
        if (confirm("Are you sure you want to delete '" + thekey + "' ?")){
            $.ajax({
                url: "<?php echo $this->request->webroot;?>profiles/products",
                type: "post",
                dataType: "HTML",
                data: "Type=deleteemail&key=" + thekey,
                success: function (msg) {
                    reload();
                },
                error: function (msg) {
                    alert("'" + thekey + "' was not deleted");
                }
            })
        }
    }

    function newkey(){
        if(haschanged){
            if (!confirm("You have unsaved changes, are you sure you want to switch to a different email?")){
                return false;
            }
        }
        var thekeys = ["<?= cleanit(array_keys($emails)); ?>"];
        var thekey = prompt("Please enter the new name for the email event (no spaces, or the word email)", "");
        if (thekey){
            thekey = replaceAll(thekey, " ", "");
            thekey = replaceAll(thekey, "email", "");
            var index = thekeys.indexOf(thekey);
            if (index>-1) {
                alert("'" + thekey + "' is in use already");
                return false;
            }

            $.ajax({
                url: "<?= $this->request->webroot;?>profiles/products",
                type: "post",
                dataType: "HTML",
                data: "Type=editemail&key=" + thekey,
                success: function (msg) {
                    alert(msg);
                    reload();
                },
                error: function (msg) {
                    alert("'" + thekey + "' was not saved");
                }
            })
        }
    }

    function reload(){
        window.location = '<?= $this->request->webroot; ?>profiles/settings?includeonly=profile/emails.php';
    }
</script>