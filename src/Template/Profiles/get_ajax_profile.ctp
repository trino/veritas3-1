<?php
//uses profiles/getAjaxProfile
$Columns = 3;
use Cake\ORM\TableRegistry;
$language = $this->request->session()->read('Profile.language');

function getstring($String, $language){//no variable processing
    $Table = TableRegistry::get('strings')->find()->select()->where(["Name" => $String])->first();
    if(!$Table){$String.=" NOT FOUND";}
    return "[" . $String . "]";
    return $Table->$language;
}

$debug=$this->request->session()->read('debug');
if($debug) {
  //  echo "<TR><TD><span style ='color:red;'>profiles/get_ajax_profile.ctp #INC???</span></TD></TR>";
}

$i=0;
$pType = $this->requestAction('/profiles/getProfileTypes/' . $language);// ['','Admin','Recruiter','External','Safety','Driver','Contact'];

$mode = $profiles->mode;
switch ($mode){
    case 1://bulk order
        $sub = "addProfile";
        $selected = explode(",", $profiles->selected);
        break;
}

function printtdline($Text, $Columns){
    echo "<TR><TD COLSPAN=" . $Columns . ">" . $Text . "</TD></TR>";
}

$Fields = array ("fname" => "forms_firstname", "email" => "forms_email", "lname" => "forms_lastname", "profile_type" => "profiles_profiletype", "gender" => "forms_gender",  "driver_province" => "forms_provinceissued", "title" => "forms_title", "placeofbirth" => "forms_placeofbirth", "sin" => "forms_sin", "phone" => "forms_phone", "street" => "forms_address", "city" => "forms_city", "province" => "forms_provincestate", "postal" => "forms_postalcode", "country" => "forms_country", "dob" => "forms_dateofbirth", "driver_license_no" => "forms_driverslicense", "expiry_date" => "forms_expirydate");

function hasallfields($r, $Fields){
    return true;
    if($r->is_complete){
        return true;
    }
    foreach($Fields as $Key => $Value){
        if(!$r->$Key){return false;}
    }
    return true;
}

$fulllist="";
$Count = 0;//iterator_count($profiles);
foreach ($profiles as $r) {
    $DOIT = $r->is_complete;
    if ($mode == 1) {
        $DOIT = false;
        if($r->profile_type) {
            $DOIT = $pType[$r->profile_type . ".canorder"] == 1;
        }
        if($DOIT){ $DOIT = hasallfields($r, $Fields); }
    }
    if($DOIT){
        $Count++;
    }
}
if($Count==0){
    printtdline(getstring("infoorder_nonefound", $language), $Columns);
} else {
    if($Count < $Columns){
        $Columns= $Count;
    }
    //printtdline(getstring("infoorder_disabled", $language));
    $Entries = ceil($Count / $Columns);
    //printtdline( $profiles->count() / $Columns . " " . $Entries . " " . $profiles->count(), $Columns );
    $Entry = 0;
    $Width = floor(100 / $Columns);
    $Class = ' style="white-space: nowrap; overflow-x: hidden; max-width:' . $Width . '%;" TITLE="entries: ' . $Count . ' entries per col: ' . $Entries . ' "';
    $Cols =0;
    $Table = '<TD WIDTH="' . $Width .'%" class="nopadorborder"' . $Class . '><TABLE style="max-width:100%" class="table table-striped table-bordered table-hover recruiters nopadorborder">';
    echo '<TR>' . $Table;
    foreach ($profiles as $r) {
        $DOIT = $r->is_complete;
        $username = "[NO NAME]";
        $profiletype = "";
        if (strlen(trim($r->username) > 0)) {
            $username = $r->username;
        } elseif (strlen(trim($r->fname . $r->lname)) > 0) {
            $username = $r->fname . " " . $r->lname;
        }
        if ($fulllist) {
            $fulllist .= "," . $r->id;
        } else {
            $fulllist = $r->id;
        }
        if (isset($pType[$r->profile_type])) {
            $profiletype = " (" . $pType[$r->profile_type] . ")";
        }
        if (!$profiletype) {
            $profiletype = " (Draft)";
        }
        if ($mode == 1) {
            $DOIT = false;
            if($r->profile_type) {
                $DOIT = $pType[$r->profile_type . ".canorder"] == 1;
            }
            if($DOIT){ $DOIT = hasallfields($r, $Fields); }
        }

        if ($DOIT) {
            echo '<tr><td nowrap' . $Class . ' title="' . $username . '"><span><input id="p_' . $i . '" name="p_' . $r->id . '" class="profile_client" onchange="';
            $checked = "";
            if ($mode == 0) {
                echo "if($(this).is(':checked')){assignProfile($(this).val(),'" . $cid . "','yes');}else{assignProfile($(this).val(),'" . $cid . "','no');}";
                $checked = in_array($r->id, $profile);
            } else {
                echo $sub . "(" . $r->id . ");";
                $checked = in_array($r->id, $selected);
            }
            if ($checked) {
                $checked = 'checked="checked"';
            }
            echo '" type="checkbox" ' . $checked . ' value="' . $r->id . '"/></span>';
            echo '<span><label for="p_' . $i . '">' . $username;
            if ($profiletype) {
                echo ' ' . $profiletype;
            }
            echo '</span></label><span class="msg_' . $r->id . '"></span></td></tr>';
            $i++;

            $Entry++;
            if ($Entry == $Entries){
                $Cols++;
                if ($Cols < $Columns) {
                    $Entry = 0;
                    echo '</TABLE></TD>' . $Table;
                }
            }
        }
    }
}
    echo '</TD></TR></TABLE>';
    if ($mode == 1) {
        if ($i > 1) {
            $fulllist = "'" . $fulllist . "'";
            echo '<TR><TD COLSPAN="3"><SPAN><INPUT TYPE="CHECKBOX" ID="selectall" ONCHANGE="selectall(' . $fulllist . ');"></SPAN> <SPAN><LABEL FOR="selectall">Select All</LABEL></SPAN></TD></TR>';
        }
    }
?>
<script>
    function assignProfile(profile,client,status) {
        if(status=='yes') {
            var url= '<?= $this->request->webroot;?>clients/assignProfile/'+profile+'/'+client+'/yes';
        } else {
            var url= '<?= $this->request->webroot;?>clients/assignProfile/'+profile+'/'+client+'/no';
        }
        $.ajax({url:url});
    }

    function assignClient(profile,client,status) {
        if(status=='yes') {
            var url= '<?= $this->request->webroot;?>clients/assignClient/'+profile+'/'+client+'/yes';
        } else {
            var url= '<?= $this->request->webroot;?>clients/assignClient/'+profile+'/'+client+'/no';
        }
        $.ajax({url:url});
    }
</script>