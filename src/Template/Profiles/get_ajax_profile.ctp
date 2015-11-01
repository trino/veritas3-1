<?php

use Cake\ORM\TableRegistry;
$language = $this->request->session()->read('Profile.language');
function getstring($String, $language){//no variable processing
    $Table = TableRegistry::get('strings')->find()->select()->where(["Name" => $String])->first();
    if($language=="Debug"){
        if(!$Table){$String.=" NOT FOUND";}
        return "[" . $String . "]";
    }
    return $Table->$language;
}

$debug=$this->request->session()->read('debug');
if($debug) {
    echo "<TR><TD><span style ='color:red;'>profiles/get_ajax_profile.ctp #INC???</span></TD></TR>";
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

function printtdline($Text){
    echo "<TR><TD>" . $Text . "</TD></TR>";
}


$fulllist="";
if(iterator_count($profiles)==0){
    printtdline(getstring("infoorder_nonefound", $language));
} else {
    foreach ($profiles as $r) {
        $DOIT = true;
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
            //$DOIT = false;
            //if (empty($r->profile_type) || $r->profile_type == 5 || $r->profile_type == 8 || $r->profile_type == 11 || $r->profile_type == 17) {
            //    $DOIT = true;
            //}

            $DOIT = $pType[$r->profile_type . ".canorder"] == 1;
            //$profiletype.= " [" . $r->profile_type . "]";
        }
//echo $r->username;continue;
//if($i%2==0)
        if ($DOIT) {
            ?>
            <tr>
                <td>
<span><input id="p_<?= $i ?>" name="p_<?= $r->id ?>" class="profile_client" onchange="<?php
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

    ?>" type="checkbox" <?= $checked; ?> value="<?php echo $r->id; ?>"/></span>
                <span><label for="p_<?= $i ?>"><?php echo $username; ?> <?php if ($profiletype) {
                            echo $profiletype;
                        } ?> </span></label>
                    <span class="msg_<?php echo $r->id; ?>"></span>
                </td>
            </tr>
<?php
// }

            $i++;
        }
    }
}

    if ($mode == 1) {
        if ($i > 1) {
            $fulllist = "'" . $fulllist . "'";
            echo '<TR><TD><SPAN><INPUT TYPE="CHECKBOX" ID="selectall" ONCHANGE="selectall(' . $fulllist . ');"></SPAN> <SPAN><LABEL FOR="selectall">Select All</LABEL></SPAN></TD></TR>';
        }
    }


//if(($i+1)%2!=0)
//                                                {
//                                                    echo "</td></tr>";
//                                                }
?>

<script>
    function assignProfile(profile,client,status) {
        if(status=='yes') {
            var url= '<?php echo $this->request->webroot;?>clients/assignProfile/'+profile+'/'+client+'/yes';
        } else {
            var url= '<?php echo $this->request->webroot;?>clients/assignProfile/'+profile+'/'+client+'/no';
        }
        $.ajax({url:url});
    }

    function assignClient(profile,client,status) {
        if(status=='yes') {
            var url= '<?php echo $this->request->webroot;?>clients/assignClient/'+profile+'/'+client+'/yes';
        } else {
            var url= '<?php echo $this->request->webroot;?>clients/assignClient/'+profile+'/'+client+'/no';
        }
        $.ajax({url:url});
    }
</script>