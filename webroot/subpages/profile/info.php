<style>div {
        border: 0px solid green;
    }
</style>
<SCRIPT>
    function emailthecreds(){
        var doit = true;
        var element = document.getElementById("emailcreds");
        var reason;
        if (!document.getElementById("username_field").value){doit = false; reason = "missing username";}
        if (!document.getElementById("email").value){doit = false; reason = "missing email";}
        if (!document.getElementById("password").value){doit = false; reason = "missing password";}
        if (document.getElementById("password").value != document.getElementById("retype_password").value){doit = false; reason = "passwords do not match";}

        $("#emailcreds").prop("disabled", !doit );
        var parent = $("#emailcreds").parent().parent();
        if(!doit){
            //alert(reason);
            parent.addClass("disabled");
            $("#emailcreds").prop("checked", false);
            $("#emailcreds").parent().removeClass("checked");
        } else {
            element.removeAttribute("disabled");
            parent.removeClass("disabled");
        }
    }
</SCRIPT>

<?php
$param = $this->request->params['action'];
if ($this->request->session()->read('debug')) {
    echo "<span style ='color:red;'>subpages/profile/info.php #INC117</span>";
}

$showcreds = true;
$userID = $this->Session->read('Profile.id');
if(!$userID && isset($_GET["client"])){$userID = 0;}

$canedit = $this->request->session()->read('Profile.super') || $this->request->session()->read('Profile.admin');
$ShouldShow = isset($p->profile_type) && ($p->profile_type=='1' || $p->profile_type=='2');
if($ShouldShow || $canedit){
    $ShouldShow = 'display:block';
    $canedit=true;
} else {
    $ShouldShow = 'display:none';
}
$getProfileType = $this->requestAction('profiles/getProfileType/' . $userID);

function printoption2($value, $selected = "", $option){
    $tempstr = "";
    if ($option == $selected or $value == $selected) {
        $tempstr = " selected";
    }
    echo '<OPTION VALUE="' . $value . '"' . $tempstr . ">" . $option . "</OPTION>";
}

function printoptions($name, $valuearray, $selected = "", $optionarray, $isdisabled = "", $isrequired = false){
    if ($name == 'profile_type') {
        echo '<SELECT ' . $isdisabled . ' name="' . $name . '" class="form-control member_type "';
    } else {
        echo '<SELECT ' . $isdisabled . ' name="' . $name . '" class="form-control '.$name.'"';
    }
    echo '>';

    for ($temp = 0; $temp < count($valuearray); $temp += 1) {
        printoption2($valuearray[$temp], $selected, $optionarray[$temp]);
    }
    echo '</SELECT>';
}

function printprovinces($language, $name, $selected = "", $isdisabled = "", $isrequired = false){
    $acronyms = getprovinces("Acronyms");
    $provinces = getprovinces($language);
    printoptions($name, $acronyms, $selected, $provinces, $isdisabled, $isrequired);
}
/*
$settings = $Manager->get_settings();
include_once('subpages/api.php');
$language = $this->request->session()->read('Profile.language');
$strings = CacheTranslations($language, array("forms_%"), $settings);
*/
loadreasons($param, $strings, true);
?>

<div class="portlet-body form">
    <input type="hidden" name="client_ids" value="" class="client_profile_id"/>

    <div class="form-body">
        <div class="tab-content">
            <div class="tab-pane active" id="subtab_4_1">


                <div class="portlet box" style="margin-bottom:0px;">


                    <form role="form" action="" method="post" id="save_clientz">
                        <input type="hidden" name="client_ids" value="" class="client_profile_id"/>
                        <input type="hidden" name="drafts" value="0" id="profile_drafts"/>

                        <div class="row">
                            <input type="hidden" name="created_by" value="<?php echo $this->request->session()->read('Profile.id') ?>"/>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?= $strings["profiles_profiletype"]; ?>:</label>
                                    <!--old code:  <input type="hidden" id="nProfileType" name="profile_type" value="<!php if(!isset($p) && isset($getProfileType->profile_type) && $getProfileType->profile_type == 2)echo "5"; else echo $p->profile_type;!>" <!php echo $is_disabled !> />-->
                                    <?php if (isset($p)) { ?>
                                        <input type="hidden" id="nProfileType" name="profile_type"
                                               value="<?php if (isset($p)) {
                                                   echo $p->profile_type;
                                               } ?>" <?php echo $is_disabled ?> />
                                    <?php } ?>

                                    <?php if ($this->request->params['action'] == 'add' || ($this->request->params['action'] == 'edit' && $this->request->session()->read('Profile.id') != $id)) {

                                        ?>
                                        <select  <?php echo $is_disabled ?>
                                            name="<?php if (!isset($p)) {
                                                echo 'profile_type';
                                            } ?>" <?php if ((isset($id) && $this->request->session()->read('Profile.id') == $id)/* || ($this->request->session()->read('Profile.profile_type') == '2')*/) echo "disabled='disabled'"; ?>
                                            class="form-control member_type"
                                            onchange="$('#nProfileType').val($(this).val());">
                                            <option value=""><?= $strings["forms_select"]; ?></option>

                                            <?php

                                            $isISB = (isset($sidebar) && $settings->client_option == 0);
                                            $ptyp = $this->requestAction('profiles/gettypes/ptypes/' . $this->request->session()->read('Profile.id'));
                                            if ($ptyp != "") {
                                                $pts = explode(",", $ptyp);
                                            }
                                            $fieldname = getFieldname("title", $language);
                                            if($language == "Debug"){$Trans = " [Trans]";} else {$Trans="";}
                                            foreach ($ptypes as $k => $pt) {
                                                //var_dump($pt);
                                                if (isset($pts)) {
                                                    if (in_array($pt->id, $pts)) {
                                                            ?>
                                                            <option
                                                                value="<?php echo $pt->id; ?>" <?php if (isset($p) && $p->profile_type == $pt->id) { ?> selected="selected" <?php } ?>>
                                                                <?php echo $pt->$fieldname . $Trans; ?>
                                                            </option>
                                                        ?>

                                                    <?php
                                                    }
                                                } else {
                                                    if ($pt->id == '1') {
                                                        //if($this->request->session()->read('Profile.super'))
                                                        //{
                                                        ?>
                                                        <option
                                                            value="<?php echo $pt->id; ?>" <?php if (isset($p) && $p->profile_type == 1) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $pt->$fieldname . $Trans; ?>
                                                        </option>
                                                        <?php

                                                        //}
                                                    } else {
                                                        ?>
                                                        <option
                                                            value="<?php echo $pt->id; ?>" <?php if (isset($p) && $p->profile_type == $pt->id) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $pt->$fieldname . $Trans; ?>
                                                        </option>
                                                        <?php
                                                        //}
                                                    }

                                                }
                                            }
                                            ?>

                                        </select>
                                    <?php } else {
                                        ?>
                                        <select  <?php echo $is_disabled ?>
                                            name="<?php if (!isset($p)) {
                                                echo 'profile_type';
                                            } ?>" <?php if ((isset($id) && $userID == $id)/* || ($this->request->session()->read('Profile.profile_type') == '2')*/) echo "disabled='disabled'"; ?>
                                            class="form-control member_type"
                                            onchange="$('#nProfileType').val($(this).val());">

                                            <option selected=""
                                                    value="<?= $p->profile_type; ?>"><?php echo $this->requestAction('/profiles/getTypeTitle/' . $p->profile_type . "/" . $language) . $Trans ?></option>

                                        </select>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-6" id="isb_id"
                                 style="display:
                                 <?php
                                 /* as discussed on march 18, isb id only for recruiter and driver type for driver , owners etc
                                  if ((isset($p) && $p->profile_type != 5) && (isset($getProfileType->profile_type) && $getProfileType->profile_type == 1) || ($this->request->session()->read('Profile.profile_type') == 2 && (isset($p) && $p->id == ($this->request->session()->read('Profile.id')))) || ($this->request->session()->read('Profile.profile_type') == 2 && (isset($p) && $p->id != 5  ))) echo 'block'; else echo "none" */ ?>
                                 <?php
                                 if ((isset($p) && $p->profile_type == 2))
                                     echo 'block'; else echo "none" ?>
                                     ;">
                                <div class="form-group">
                                    <label class="control-label">ISB ID: </label>
                                    <input <?php echo $is_disabled ?>
                                        name="isb_id" type="text"
                                        placeholder=""
                                        class="form-control isb_id" <?php if (isset($p->isb_id)) { ?> value="<?php echo $p->isb_id; ?>" <?php }
                                    if (isset($p->isb_id) && !$this->request->session()->read('Profile.super')) {
                                        ?>
                                        disabled="disabled"
                                    <?php
                                    }
                                    ?>  />
                                </div>
                            </div>


                            <?php
                            //if(isset($p))
                            $client_id = $this->requestAction('/clients/getclient_id/'.$this->request->session()->read('Profile.id'));
                            if(isset($p)) {
                                $user_client = $this->requestAction('/clients/getclient_id/' . $p->id);
                            }else {
                                $user_client = 0;
                            }
                            // if ($settings->client_option == 0) {

                            if($client_id || $user_client){
                                ?>

                            <div class="col-md-6" id="driver_div"
                                 style="display:<?php if ((isset($p) && $p->profile_type == 5) || ($this->request->session()->read('Profile.profile_type') == 2 && (isset($p) && $p->id != ($this->request->session()->read('Profile.id'))))) echo 'block'; else echo "none" ?>;">
                                <div class="form-group">
                                    <label class="control-label"><?= $strings["forms_drivertype"];?>: </label>
                                    <select  <?php echo $is_disabled ?> name="driver"
                                                                        class="form-control select_driver">
                                        <option value=""><?= $strings["forms_selectdrivertype"];?></option>
                                        <option
                                            value="1" <?php if (isset($p) && $p->driver == 1) echo "selected='selected'"; ?>
                                            >BC - BC FTL AB/BC
                                        </option>
                                        <option value="2"
                                            <?php if (isset($p) && $p->driver == 2) echo "selected='selected'"; ?>>
                                            BCI5 - BC FTL I5
                                        </option>
                                        <option value="3"
                                            <?php if (isset($p) && $p->driver == 3) echo "selected='selected'"; ?>>
                                            BULK
                                        </option>
                                        <option value="4"
                                            <?php if (isset($p) && $p->driver == 4) echo "selected='selected'"; ?>>
                                            CLIMATE
                                        </option>
                                        <option value="5"
                                            <?php if (isset($p) && $p->driver == 5) echo "selected='selected'"; ?>>
                                            FTL - SINGLE DIVISION
                                        </option>
                                        <option value="6"
                                            <?php if (isset($p) && $p->driver == 6) echo "selected='selected'"; ?>>
                                            FTL - TOYOTA SINGLE HRLY
                                        </option>
                                        <option value="7"
                                            <?php if (isset($p) && $p->driver == 7) echo "selected='selected'"; ?>>
                                            FTL - TOYOTA SINGLE HWY
                                        </option>
                                        <option value="8"
                                            <?php if (isset($p) && $p->driver == 8) echo "selected='selected'"; ?>>
                                            LCV - LCV UNITS
                                        </option>
                                        <option value="9"
                                            <?php if (isset($p) && $p->driver == 9) echo "selected='selected'"; ?>>
                                            LOC - LOCAL
                                        </option>
                                        <option value="10"
                                            <?php if (isset($p) && $p->driver == 10) echo "selected='selected'"; ?>>
                                            OWNER - OPERATOR
                                        </option>
                                        <option value="11"
                                            <?php if (isset($p) && $p->driver == 11) echo "selected='selected'"; ?>>
                                            OWNER - DRIVER
                                        </option>
                                        <option value="12"
                                            <?php if (isset($p) && $p->driver == 12) echo "selected='selected'"; ?>>
                                            SCD - SPECIAL COMMODITIES
                                        </option>
                                        <option value="13"
                                            <?php if (isset($p) && $p->driver == 13) echo "selected='selected'"; ?>>
                                            SST-SANDRK- OPEN FUEL
                                        </option>
                                        <option value="14"
                                            <?php if (isset($p) && $p->driver == 14) echo "selected='selected'"; ?>>
                                            SWD-SANDRK
                                        </option>
                                        <option value="15"
                                            <?php if (isset($p) && $p->driver == 15) echo "selected='selected'"; ?>>
                                            TBL-TRANSBORDER
                                        </option>
                                        <option value="16"
                                            <?php if (isset($p) && $p->driver == 16) echo "selected='selected'"; ?>>
                                            TEM - TEAM DIVISION
                                        </option>
                                        <option value="17"
                                            <?php if (isset($p) && $p->driver == 17) echo "selected='selected'"; ?>>
                                            TEM - TOYOTA TEAM
                                        </option>
                                        <option value="18"
                                            <?php if (isset($p) && $p->driver == 18) echo "selected='selected'"; ?>>
                                            WD - Wind
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <?php //}
                            }
                            //echo $p->profile_type;


                                ?>
                                   <div class="col-md-6 hideusername admin_rec email_rec" style="<?= $ShouldShow; ?>">
                                    <div class="form-group">
                                        <label class="control-label"><?= $strings["profiles_username"]; ?>: </label>
                                        <input <?php echo $is_disabled ?> id="username_field" name="username" type="text" onkeyup="emailthecreds();"
                                                                          class="form-control username" <?php if (isset($p->username)) { ?> value="<?php echo $p->username; ?>" <?php } ?>
                                            <?php
                                            if ($userID>0 && ($this->request->session()->read('Profile.super') != '1' && ($this->request->params['action'] == 'edit'))) {
                                                echo 'disabled="disabled"';
                                            }
                                            ?>/>
                            <span class="error passerror flashUser"
                                  style="display: none;"><?= $strings["profiles_usernameexists"]; ?></span>
                            <span class="error passerror flashUser1"
                                  style="display: none;"><?= $strings["forms_usernamerequired"]; ?></span>
                                    </div>
                                </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?= $strings["forms_email"]; ?>: </label>
                                    <input <?php echo $is_disabled ?> name="email" type="email" onkeyup="emailthecreds();" id="email"
                                                                      role="email"
                                                                      class="form-control email"  <?php if (isset($p->email)) { ?> value="<?php echo $p->email; ?>" <?php } ?><?php if (isset($p->profile_type) && ($p->profile_type == '9' || $p->profile_type=='12')) { ?> <?php } ?>/>
                            <span class="error passerror flashEmail"
                                  style="display: none;"><?= $strings["dashboard_emailexists"]; ?></span>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <?php if(isset($p) && $p->emailsent && $showcreds) {
                                $showcreds = false; ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?= $strings["forms_credssent"]; ?>: </label><BR>
                                        <input type="text" class="form-control" value="<?= $p->emailsent; ?>" disabled>
                                    </DIV>
                                </DIV>
                            <?php }

                            if (strlen($is_disabled) == 0) {

                                ?>
                                <div class="col-md-4 admin_rec email_rec passwords" style="<?= $ShouldShow; ?>">
                                    <div class="form-group">
                                        <label class="control-label"><?= $strings["forms_password"]; ?>: </label>


                                        <!-- <input  <?php echo $is_disabled ?> type="password" name="password" id="password" class="form-control"
                                   <?php // if (isset($p->password)){ ?><?php //echo $p->password; ?> <?php //} ?>
                                   <?php if (isset($p->password) && $p->password) {//do nothing
                                        } else { ?><?php } ?>  />-->


                                        <input  <?php echo $is_disabled ?> type="password" value="" onkeyup="emailthecreds();"
                                                                           autocomplete="off"
                                                                           name="pass_word" id="password"
                                                                           class="form-control pass_word  <?php if (!isset($p->password)) {?><?php }?>" <?php if (isset($p->password) && $p->password) {//do nothing
                                        } ?>/>
                                    </div>
                                </div>
                                <?php if (isset($p->password)) { ?>
                                    <input type="hidden" value="<?php $p->password ?>" name="hid_pass"/>
                                <?php } ?>
                                <div class="col-md-4 admin_rec email_rec" style="<?= $ShouldShow; ?>">
                                    <div class="form-group">
                                        <label class="control-label"><?= $strings["forms_retypepassword"]; ?>: </label>
                                        <input <?php echo $is_disabled ?> onkeyup="emailthecreds();"
                                               type="password" class="form-control <?php if (!isset($p->password) || (isset($p) && $p->profile_type!= 3)) {?><?php }?>"
                                               id="retype_password" <?php //if (isset($p->password)) { ?> <?php // echo $p->password; ?>  <?php // } ?>/>
                            <span class="error passerror flashPass1"
                                  style="display: none;"><?= $strings["forms_passnotequal"]; ?></span>
                                    </div>
                                </div>
                                <?php
                                if ($param == "add" || ($canedit && $param == "edit")) {
                                    ?>
                                    <div class="col-md-4 admin_rec email_rec" style="<?= $ShouldShow; ?>" >
                                        <div class="form-group">
                                            <label class="control-label"><?= $strings["forms_emailcreds"]; ?>: </label><BR>
                                            <input type="checkbox" name="emailcreds" disabled id="emailcreds">
                                            <label style="margin-top: 5px;" for="emailcreds"><?= $strings["forms_email2new"]; ?></label>
                                        </DIV>
                                    </DIV>
                                <?php } elseif( $userID == $id) { ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label"><?= $strings["forms_language"]; ?>: </label><BR>
                                            <select name="language" class="form-control">
                                                <?php
                                                $languages = languagenames();
                                                foreach($languages as $English => $Native){
                                                    printoption($Native, $language, $English);
                                                }
                                                if($this->request->session()->read('Profile.super')==1){
                                                    printoption("Debug", $language, "Debug");
                                                }
                                                ?>
                                            </select>
                                        </DIV>
                                    </DIV>
                                <?php } elseif($p->emailsent && $showcreds) { ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label"><?= $strings["forms_credssent"]; ?>: </label><BR>
                                            <input type="text" name="emailcreds" class="form-control" id="emailcreds" value="<?= $p->emailsent; ?>" disabled>
                                        </DIV>
                                    </DIV>
                                <?php } ?>

                                <div class="clearfix"></div>
                            <?php } ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php
                                    $title = "";
                                    if (isset($p->title)) {$title = $p->title;}
                                    selecttitle($language, $strings, "title", $title, $is_disabled);//$language, $strings, $name, $title, $is_disabled
                                    ?>
                                    <!--
                                                                        <input < php echo $is_disabled ?> name="title" type="text"
                                                                                                          placeholder="eg. Mr"
                                                                                                          class="form-control " < php if (isset($p->title)) { ?> value="< php echo $p->title; ?>" < php } ?> /> -->
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">

                                    <label class="control-label"><?= $strings["forms_firstname"]; ?>: </label>
                                    <input <?php echo $is_disabled ?> name="fname" type="text"
                                                                      placeholder=""
                                                                      class="form-control fname" <?php if (isset($p->fname)) { ?>
                                        value="<?php echo $p->fname; ?>" <?php } ?>/>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">

                                    <label class="control-label"><?= $strings["forms_middlename"]; ?>: </label>
                                    <input <?php echo $is_disabled ?> name="mname" type="text"
                                                                      placeholder=""
                                                                      class="form-control" <?php if (isset($p->mname)) { ?>
                                        value="<?php echo $p->mname; ?>" <?php } ?>/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"><?= $strings["forms_lastname"]; ?>: </label>
                                    <input <?php echo $is_disabled ?> name="lname" type="text"
                                                                      placeholder=""
                                                                      class="form-control lname" <?php if (isset($p->lname)) { ?>
                                        value="<?php echo $p->lname; ?>" <?php } ?>/>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">

                                    <label class="control-label"><?= $strings["forms_phone"]; ?>: </label>
                                    <input <?php echo $is_disabled ?> name="phone" type="text"
                                                                      placeholder="i.e. 905-500-5555" role='phone'
                                                                      class="form-control" <?php if (isset($p->phone)) { ?>
                                        value="<?php echo $p->phone; ?>" <?php } ?>/>
                                </div>
                            </div>


                            <!--div class="col-md-4">
                                <div class="form-group">

                                    <label class="control-label"><?= $strings["forms_gender"]; ?>: </label>
                                    <SELECT <?php echo $is_disabled ?> name="gender"
                                                                       class="form-control" ><?php
                                        $gender = "";
                                        if (isset($p->gender)) {
                                            $gender = $p->gender;
                                        }
                                        printoption($strings["forms_selectgender"], "");
                                        printoption($strings["forms_male"], $gender, "Male");
                                        printoption($strings["forms_female"], $gender, "Female");
                                        ?></SELECT>
                                </div>
                            </div-->




                            <?php if($settings->mee== "MEE"){?>
                            <div class="col-md-4">
                                <div class="form-group">

                                    <label class="control-label"><?= $strings["forms_placeofbirth"]; ?>: </label>
                                    <input <?php echo $is_disabled ?> name="placeofbirth" type="text" placeholder=""
                                                                      class="form-control placeofbirth" <?php if (isset($p->placeofbirth)) { ?>
                                        value="<?php echo $p->placeofbirth; ?>" <?php } ?>/>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="form-group">
                                    <label class="control-label"><?= $strings["forms_dateofbirth"]; ?> (<?= $strings["forms_dateformat"]; ?>): </label><BR>

                                            <?php
                                            $currentyear = "0000";
                                            $currentmonth = 0;
                                            $currentday = 0;
                                            $currentdate="";
                                            if (isset($p->dob)) {
                                                $currentdate = $p->dob;
                                                $currentyear = substr($p->dob, 0, 4);
                                                $currentmonth = substr($p->dob, 5, 2);
                                                $currentday = substr($p->dob, -2);
                                            }

                                            echo '<INPUT TYPE="TEXT" CLASS="form-control date-picker" ID="DOB" value="' . $currentdate . '" onchange="refreshdob();">';
                                            echo '<INPUT TYPE="HIDDEN" ID="doby" NAME="doby" ' . $is_disabled . '>';
                                            echo '<INPUT TYPE="HIDDEN" ID="dobm" NAME="dobm" ' . $is_disabled . '>';
                                            echo '<INPUT TYPE="HIDDEN" ID="dobd" name="dobd" ' . $is_disabled . '>';
                                            ?>
                                            <SCRIPT>
                                                function refreshdob(){
                                                    var date = getinputvalue("DOB").split("-");
                                                    if(date.length>2) {
                                                        setinputvalue("doby", date[0]);
                                                        setinputvalue("dobm", date[1]);
                                                        setinputvalue("dobd", date[2]);
                                                    }
                                                }
                                                refreshdob();
                                            </SCRIPT>

                                </div>
                            </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h3 class=""><?= $strings["forms_address"]; ?>: </h3>
                                    </div>
                                </div>


                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input <?php echo $is_disabled ?> name="street" type="text"
                                                                          placeholder="<?= $strings["forms_address"]; ?>"
                                                                          class="form-control" <?php if (isset($p->street)) { ?>
                                            value="<?php echo $p->street; ?>" <?php } ?>/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input <?php echo $is_disabled ?> name="city" type="text"
                                                                          placeholder="<?= $strings["forms_city"]; ?>"
                                                                          class="form-control" <?php if (isset($p->city)) { ?>
                                            value="<?php echo $p->city; ?>" <?php } ?>/>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php
                                        if (isset($p->province)) {
                                            printprovinces($language, "province", $p->province, $is_disabled, false);
                                        } else {
                                            printprovinces($language, "province", "", $is_disabled, false);
                                        }
                                        ?>

                                        <!-- old
                                        <SELECT  < php echo $is_disabled ?> name="province" class="form-control ">< php
                                                $provinces = array("AB", "BC", "MB", "NB", "NL", "NT", "NS", "NU", "ON", "PE", "QC", "SK", "YT");
                                                $province = "";
                                                if (isset($p->province)) {
                                                    $province = $p->province;
                                                }
                                                for ($temp = 0; $temp < count($provinces); $temp += 1) {
                                                    printoption($provinces[$temp], $province, $provinces[$temp]);
                                                }
                                            ?></SELECT>
                                                <input < php echo $is_disabled ?> name="province" type="text"
                                                                                   placeholder="Province"
                                                                                   class="form-control " < php if (isset($p->province)) { ?> value="< php echo $p->province; ?>" < php } ?>/> -->
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input <?php echo $is_disabled ?>  type="text"
                                                                           placeholder="<?= $strings["forms_postalcode"]; ?> (M5V2X2)"
                                                                           class="form-control"
                                                                           name="postal" role='postalcode'  <?php if (isset($p->postal)) { ?>
                                            value="<?php echo $p->postal; ?>" <?php } ?>/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input <?php echo $is_disabled ?>  type="text"
                                                                           placeholder="<?= $strings["forms_country"]; ?>" value="Canada"
                                                                           class="form-control"
                                                                           name="country" <?php if (isset($p->country)) { ?>
                                            value="<?php echo $p->country; ?>" <?php } ?>/>
                                    </div>
                                </div>


                                <div class="driver_license" oldstyle="<?php if(isset($p) &&($p->profile_type == 0 || $p->profile_type=='5'||$p->profile_type=='7'||$p->profile_type=='8'||$p->profile_type=='12'))echo "display:block" ;else echo "display:none";?>">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h3 class="block"><?= $strings["forms_driverslicense"]; ?>: </h3></div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label"><?= $strings["forms_driverslicense"]; ?> #: </label>
                                        <input <?php echo $is_disabled ?> name="driver_license_no" type="text"
                                                                          class="form-control driver_license_no"  <?php if (isset($p->driver_license_no)) { ?>
                                            value="<?php echo $p->driver_license_no; ?>" <?php } ?> />
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label"><?= $strings["forms_provinceissued"]; ?>: </label>
                                        <?php
                                            if (isset($p->driver_province)) {
                                                printprovinces($language, "driver_province", $p->driver_province, $is_disabled, true);
                                            } else {
                                                printprovinces($language, "driver_province", "", $is_disabled, true);
                                            }
                                        ?>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label"><?= $strings["forms_expirydate"]; ?>: </label>
                                        <input <?php echo $is_disabled ?> name="expiry_date" type="text"
                                                                          class="form-control date-picker expiry_date"
                                                                          value="<?php if (isset($p->expiry_date)) echo $p->expiry_date; ?>"/>


                                    </div>
                                </div>
                                </div>
                                <?php
                                } else { ?>
                                    <input type="hidden" name="doby" value="0000"/>
                                    <input type="hidden" name="dobm" value="00"/>
                                    <input type="hidden" name="dobd" value="00"/>
                                <?php
                                }

                                $delete = isset($disabled);
                                if (isset($client_docs)) {
                                    //   include_once 'subpages/filelist.php';
                                    //   listfiles($client_docs, "img/jobs/",'profile_doc',$delete);
                                }
                                ?>
                                <div class="form-group col-md-8 col-sm-8">


                                <!--label class="control-label col-md-6"> <?= $strings["forms_hearaboutus"]; ?></label-->
                                    <div class="">
                                        <select name="hear" class="form-control">
                                            <option value="Referral" <?php if(isset($p)&& $p->hear=='Referral')echo 'selected';?>><?= $strings["forms_hearaboutus"]; ?></option>
                                            <option value="Company Website"  <?php if(isset($p)&& $p->hear=='Company Website')echo 'selected';?>><?= $strings["forms_companywebsite"]; ?></option>
                                            <option value="Workopolis"  <?php if(isset($p)&& $p->hear=='Workopolis')echo 'selected';?>>Workopolis.com</option>
                                            <option value="Monster"  <?php if(isset($p)&& $p->hear=='Monster')echo 'selected';?>>Monster.ca</option>
                                            <option value="Nethire"  <?php if(isset($p)&& $p->hear=='Nethire')echo 'selected';?>>Nethireinc.com</option>
                                            <option value="Indeed"  <?php if(isset($p)&& $p->hear=='Indeed')echo 'selected';?>>Indeed.ca</option>
                                            <option value="Newspaper"  <?php if(isset($p)&& $p->hear=='Newspaper')echo 'selected';?>><?= $strings["forms_newspaper"]; ?></option>
                                            <option value="Others"  <?php if(isset($p)&& $p->hear=='Others')echo 'selected';?>><?= $strings["forms_other"]; ?></option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><?= $strings["forms_other"]; ?>: </label>
                                        <textarea <?php echo $is_disabled ?> name="otherinfo" type="text" class="form-control"><?php if (isset($p->otherinfo)) { echo $p->otherinfo; } ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><?= $strings["forms_sin"]; ?>: </label>
                                        <input <?php echo $is_disabled ?> name="sin" type="text" role="sin"
                                                                          class="form-control" <?php if (isset($p->sin)) { ?>
                                            value="<?php echo $p->sin; ?>" <?php } ?> />
                                    </div>
                                </div>






                                <div class="clearfix"></div>

















                                <div class=""
                                     id="subtab_2_4" style="padding: 10px;">

                                     <label class="control-label"><?= $strings["profiles_assigntoclient"]; ?>:</label>

                                    <?php
                                        if (($this->request->session()->read("Profile.super") || ($this->request->session()->read("Profile.admin") == 1 || $this->request->session()->read("Profile.profile_type") == 2))) {
                                            if($this->request->params['action']!='view'){
                                            ?>

                                        <div class="input-group">
                                            <span class="input-group-addon">
                                            <i class="fa fa-search"></i>
                                            </span>
                                            <input type="text" id="searchClient" onkeyup="" class="form-control"
                                               <?php if ($this->request->session()->read('Profile.profile_type') == 2 && $this->request->session()->read('Profile.id') == $id){ ?>disabled=""<?php } ?> />
                                        </div>
                                        <?php
                                         }?>
                                        <div class="<?php if($this->request->params['action']!='view')echo '';?>" <?php if($this->request->params['action']=='view'){
                                            ?> style="border-top: 1px solid #e5e5e5;max-height:30px !important;min-height:30px !important;height: 30px !important;"<?php }?>>
                                            <table class="table" id="clientTable" style="border: 1px solid #e5e5e5;border-top:none;">
                                                <?php
                                                    $IsAdmin = $Manager->read("admin") || $Manager->read("super");

                                                    $CheckID = $id;
                                                    if(!$CheckID){
                                                        $CheckID=$userID;
                                                    }
                                                    $clients = $this->requestAction('/clients/getAllClient/');
                                                    $AssignedTo = array();
                                                    $clientcount=0;
                                                    foreach ($clients as $client) {
                                                        $pro_ids = explode(",", $client->profile_id);
                                                        if (in_array($CheckID, $pro_ids)){
                                                            $AssignedTo[] = $client->id;
                                                            $clientcount++;
                                                        }
                                                    }

                                                    if(!$id && count($AssignedTo)>1){
                                                        $AssignedTo = array();
                                                    }

                                                    $cidss = implode(",", $AssignedTo);
                                                    $count = 0;
                                                    if ($clients) {
                                                        $Row=0;
                                                        $Columns = 2;
                                                        $Column = 1;
                                                        foreach ($clients as $o) {
                                                            $isassigned = in_array($o->id, $AssignedTo);
                                                            if($this->request->params['action'] == 'view') {
                                                                if (!$isassigned) {
                                                                        continue;
                                                                }
                                                            }
                                                            if($Column==1){
                                                                echo '<tr>';
                                                            }
                                                            ?>
                                                                <td width="1" <?php if($Row==0){ echo 'style="border-top:none;"'; }?>>
                                                                    <input
                                                                        <?php if ($this->request->session()->read('Profile.profile_type') == 2 && $this->request->session()->read('Profile.id') == $id){ ?>disabled=""<?php } ?>
                                                                        id="c_<?= $count ?>" onclick="clientclick(<?= $count ?>);"
                                                                        type="checkbox" value="<?php echo $o->id; ?>"
                                                                        class="addclientz" name="client_idss[]" <?php if ($isassigned) {
                                                                        echo "checked";
                                                                    } ?>  <?php echo $is_disabled;
                                                                     if(!$isassigned && $clientcount >0 && !$IsAdmin){
                                                                         echo " disabled";
                                                                     }
                                                                    ?> />
                                                                </td><td width="50" align="center" <?php if($Row==0){ echo 'style="border-top:none;"'; }?>> <img height="32" src="<?=
                                                                    clientimage( $this->request->webroot, $settings, $o);
                                                                    ?>"></td><td <?php if($Row==0){ echo 'style="border-top:none;"'; }?>>

                                                                    <label
                                                                        for="c_<?= $count ?>"><?php echo $o->company_name; ?></label><span
                                                                        class="msg_<?php echo $o->id; ?>"></span></td>

                                                            <?php
                                                            if($Column == $Columns){
                                                                $Column=0;
                                                                $Row++;
                                                                echo '</tr>';
                                                            }
                                                            $Column++;
                                                            $count += 1;
                                                        }
                                                    }
                                                ?>

                                            </table>
                                        </div>
                                        <div class="clearfix"></div>

                                        <!-- </div>
                                     </div>-->
                                    <?php }?>
                                    <div class="margin-top-10 alert alert-success display-hide clientadd_flash"
                                         style="display: none;">
                                        <button class="close" data-close="alert"></button>

                                    </div>
                                    <input type="hidden" class="cids" name="cids" value="<?php if(isset($cidss))echo $cidss;?>" />
                                </div>


                                <div class="col-md-12" align="right">

                                    <?php if(!($is_disabled)){?>
                                    <a href="javascript:void(0)" class="btn btn-primary" onclick="return check_username();" id="savepro">
                                        <?= $strings["forms_savechanges"]; ?>
                                    </a>
                                    <?php }
                                    ?>
                                    <!--button class="btn btn-primary"
                                            onclick="$('#profile_drafts').val('1'); $('#save_clientz').attr('novalidate','novalidate');$('#hiddensub').click();">
                                        Save As Draft
                                    </button-->
                                    <input type="submit" style="display: none;" id="hiddensub"/>
                                </div>

                                <div class="clearfix"></div>
                                <?php //} ?>


                    </form>

                    <div class="clearfix"></div>

                </div>

            </div>

        </div>
    </div>
</div>
<script>
    function check_username() {
        //if(!checkalltags("subtab_4_1")){return false;}
        if ($('#retype_password').val() == $('#password').val()) {
            var client_id = $('.client_profile_id').val();
            if($('.member_type').val()=='1'||$('.member_type').val()=='2') {
                var un = $('.uname').val();
            }
            var un = $('.uname').val();

            var element = document.getElementById("emailcreds");
            if(element != null) {
                if (!element.checked) {element.value = "";}
            }


            $.ajax({
                url: '<?php echo $this->request->webroot;?>profiles/check_user/<?php echo $uid;?>',
                data: 'username=' + $('.uname').val(),
                type: 'post',
                success: function (res) {
                    res = res.trim();
                    if (res == '1') {
                        //alert(res);
                        alert('<?= addslashes($strings["profiles_usernameexists"]); ?>');

                        $('.uname').focus();
                        $('html,body').animate({
                                scrollTop: $('.page-title').offset().top
                            },
                            'slow');

                        return false;
                    } else {
                        $('.flashUser').hide();
                        if ($('.email').val() != '') {
                            var un = $('.email').val();
                            $.ajax({
                                url: '<?php echo $this->request->webroot;?>profiles/check_email/<?php echo $uid;?>',
                                data: 'email=' + $('.email').val(),
                                type: 'post',
                                success: function (res) {
                                    res = res.trim();
                                    if (res == '1') {
                                        $('.email').focus();
                                        alert('<?= addslashes($strings["dashboard_emailexists"]); ?>');
                                        $('html,body').animate({
                                                scrollTop: $('.page-title').offset().top
                                            },
                                            'slow');

                                        return false;
                                    } else {

                                        $(this).attr('disabled', 'disabled');
                                        $('#hiddensub').click();

                                    }
                                }
                            });
                        } else {
                            $('#hiddensub').click();

                        }
                    }
                }
            });


        } else {
            $('#retype_password').focus();
            $('html,body').animate({
                    scrollTop: $('.page-title').offset().top
                },
                'slow');
            $('.flashPass1').show();
            //$('.flashPass1').fadeOut(7000000);
            return false;
        }

    }

    function confirmdelete(Name){
        var text = "<?= addslashes($strings["dashboard_confirmdelete"]); ?>";
        return confirm(text.replace("%name%", Name));
    }
    //I break for bad code
</SCRIPT>
<SCRIPT>
    function make_required(type)
    {
        if(type == '5' || type == '7' || type == '8')
        {
            $('.fname').attr('required','');
            $('.lname').attr('required','');
            $('.driver_license_no').attr('required','');
            $('.driver_province').attr('required','');
            $('.expiry_date').attr('required','');

            $('.isb_id').removeAttr('required');
            $('.username').removeAttr('required');
            $('.password').removeAttr('required');
            $('.email').removeAttr('required');
        }
        else
        if(type == '1' || type=='2')
        {
            $('.fname').attr('required','');
            $('.lname').attr('required','');
            $('.isb_id').attr('required','');
            $('.username').attr('required','');
            $('.password').attr('required','');
            $('.email').attr('required','');

            $('.driver_license_no').removeAttr('required');
            $('.driver_province').removeAttr('required');
            $('.expiry_date').removeAttr('required');

        }
        else
        {
            $('.fname').attr('required','');
            $('.lname').attr('required','');
            $('.email').attr('required','');

            $('.driver_license_no').removeAttr('required');
            $('.driver_province').removeAttr('required');
            $('.expiry_date').removeAttr('required');
        }
        $('input,textarea,select').each(function(){


        var attr = $(this).attr('required');

        // For some browsers, `attr` is undefined; for others,
        // `attr` is false.  Check for both.
        if (typeof attr !== typeof undefined && attr !== false) {
            $(this).parent().find('label').addClass('required');
        }


        });
    }
    $(function(){

        $('.addclientz').live('change',function(){

           if($(this).is(':checked'))
           var chci = 1;
           else
           var chci = 0;
           var cids = $('.cids').val();
           if(cids == '')
           {
                if(chci == 1)
                cids = $(this).val();
           }
           else{
               if(chci==1)
               cids = cids+','+$(this).val();
               else{
               cids_arr = cids.split(',');
               cids = '';
                   for(i=0;i<cids_arr.length;i++)
                   {
                    if(cids_arr[i]!=$(this).val()){
                    if(cids=='')
                    cids = cids_arr[i];
                    else
                    cids = cids+','+cids_arr[i];
                    }
                   }
               }
           }
           $('.cids').val(cids);
        });
        $('#password').val('');
        //initiate_ajax_upload1('addMore1', 'doc');

        $('#addMoredoc').click(function () {
            var total_count = $('.docMore').data('count');
            $('.docMore').data('count', parseInt(total_count) + 1);
            total_count = $('.docMore').data('count');
            var input_field = '<div  class="form-group" ><div class="" style="margin-top:10px;"><a href="javascript:void(0);" id="addMore' + total_count + '" class="btn btn-primary"><?= addslashes($strings["forms_browse"]); ?></a><input type="hidden" name="profile_doc[]" value="" class="addMore' + total_count + '_doc moredocs" /><a href="javascript:void(0);" class = "btn btn-danger img_delete" id="delete_addMore' + total_count + '" title =""><?= addslashes($strings["dashboard_delete"]); ?></a><span></span></div></div>';
            $('.docMore').append(input_field);
            initiate_ajax_upload1('addMore' + total_count, 'doc');

        });
        $('.img_delete').live('click', function () {
            var file = $(this).attr('title');
            if (file == file.replace("&", " ")) {
                var id = 0;
            } else {
                var f = file.split("&");
                file = f[0];
                var id = f[1];
            }

            var con = confirmdelete(file);// confirm('Are you sure you want to delete "' + file + '"?');
            if (con == true) {
                $.ajax({
                    type: "post",
                    data: 'id=' + id,
                    url: "<?php echo $this->request->webroot;?>profiles/removefiles/" + file,
                    success: function (msg) {

                    }
                });
                $(this).parent().parent().remove();

            } else {
                return false;
            }
        });
        $('#save_clientz').submit(function (event) {
            event.preventDefault();
            if(!checkalltags("subtab_4_1")){return false;}

             $('.overlay-wrapper').show();
            $('#savepro').text("<?= addslashes($strings["forms_saving"]); ?>");
            var strs = $(this).serialize();
            $('#save_clientz').each(function () {
                strs = strs + '&' + $(this).attr('name') + '=' + $(this).val();
            });
            $(':disabled[name]').each(function () {
                strs = strs + '&' + $(this).attr('name') + '=' + $(this).val();
            });
            strs = strs+'&cids='+$('.cids').val();
            var adds = "<?php echo ($this->request['action']=='add')?'0':$this->request['pass'][0];?>";
            $.ajax({
                url: '<?php echo $this->request->webroot;?>profiles/saveprofile/' + adds,
                data: strs,
                type: 'post',
                success: function (res) {
                    res = res.replace(' ', '');
                    //alert(res);
                    if (res != 0 && !isNaN(res)) {
                        $('#savepro').text("<?= addslashes($strings["forms_savechanges"]); ?>");
                        $('.flash').show();
                        $('.flash').fadeOut(3500);
                        window.location.href = '<?php echo $this->request->webroot;?>profiles/edit/' + res;
                    }
                }

            });

            return false;

        });
        $('#addmore_id').click(function () {
            $('#more_id_div').append('<div id="append_id"><div class="pad_bot"><a href="" class="btn btn-primary"><?= addslashes($strings["forms_browse"]); ?></a> <a href="javascript:void(0);" id="delete_id_div" class="btn btn-danger"><?= addslashes($strings["dashboard_delete"]); ?></a></div></div>')
        });

        $('#delete_id_div').live('click', function () {
            $(this).closest('#append_id').remove();
        })

        $('#addmore_trans').click(function () {
            $('#more_trans_div').append('<div id="append_trans"><div class="pad_bot"><a href="" class="btn btn-primary"><?= addslashes($strings["forms_browse"]); ?></a> <a href="javascript:void(0);" id="delete_trans_div" class="btn btn-danger"><?= addslashes($strings["dashboard_delete"]); ?></a></div></div>')
        });

        $('#delete_trans_div').live('click', function () {
            $(this).closest('#append_trans').remove();
        })

        $('.member_type').change(function () {

            var memt = $(this).val();
            make_required(memt);

                                    if ($(this).val() == '5' || $(this).val() == '7' || $(this).val() == '8'|| $(this).val() == '9'|| $(this).val() == '12') {
                                                if($(this).val() == '5' || $(this).val() == '7' || $(this).val() == '9' || $(this).val() == '12') {
                                                    $('.hideusername').hide();
                                                }


                                                if($(this).val() == '5' || $(this).val() == '7' || $(this).val() == '8' || $(this).val() == '9' || $(this).val() == '12'){
                                                    $('.driver_license').show();
                                                    if($(this).val() == '5' || $(this).val() == '7' || $(this).val() == '8'){

                                                        $('#driver_div').show();
                                                    } else {
                                                        $('#driver_div').hide();



                                                    }

                                                } else{
                                                    $('.driver_license').hide();
                                                    $('#driver_div').hide();

                                                }
                                                $('#isb_id').hide();


                                        } else {
                                            $('.nav-tabs li:not(.active)').each(function () {
                                                $(this).show();
                                            });
                                            $('#driver_div').hide();
                                            $('#isb_id').hide();

                                            <?php
                                                if(isset($p->password) && $p->password){

                                                } else{
                                                    ?>
                                                    if (profile_type == '1' || profile_type == '2'){

                                                        $('.admin_rec').show();

                                                    }
                                                    <?php
                                                }
                                            ?>
                                        }

                                        var profile_type = $(this).val();
                                        if (profile_type == '1' || profile_type == '2') {
                                            $('#isb_id').show();

                                            $('.admin_rec').show();
                                            $('.driver_license').hide();
                                        } else {
                                             $('.admin_rec').hide();
                                        }



                        <?php if($canedit){ echo "$('.email_rec').show();"; } ?>



                                  });

                                    var mem_type = $('.member_type').val();
                                    if(mem_type)
                                    {
                                        make_required(mem_type);
                                    }
                                    if (!isNaN(parseFloat(mem_type)) && isFinite(mem_type)) {
                                        if (mem_type == '5' || mem_type == '7' || mem_type == '8' || mem_type == '9' || mem_type == '12') {

                                            if(mem_type == '5' || mem_type == '7' || mem_type == '8' || mem_type=='9' || mem_type=='12'){
                                                if($(this).val() == '5' || $(this).val() == '7' || $(this).val() == '8'){
                                                    $('#driver_div').show();

                                                } else {
                                                    $('#driver_div').hide();

                                                }
                                            }
                                            $('#isb_id').hide();


                                        }  else {
                                            $('.nav-tabs li:not(.active)').each(function () {
                                                $(this).show();
                                            });
                                            $('#driver_div').hide();
                                            $('#isb_id').hide();



                                        if (mem_type == '1' || mem_type == '2') {
                                            $('#isb_id').show();
                                        }
                                    }

                                    }

        });








        function initiate_ajax_upload1(button_id, doc) {
            var button = $('#' + button_id), interval;
            if (doc == 'doc') {
                var act = "<?php echo $this->request->webroot;?>profiles/upload_all/<?php if(isset($id))echo $id;?>";
            } else {
                var act = "<?php echo $this->request->webroot;?>profiles/upload_img/<?php if(isset($id))echo $id;?>";
            }
            new AjaxUpload(button, {
                action: act,
                name: 'myfile',
                onSubmit: function (file, ext) {
                    button.text('<?= addslashes($strings["forms_uploading"]); ?>');
                    this.disable();
                    interval = window.setInterval(function () {
                        var text = button.text();
                        if (text.length < 13) {
                            button.text(text + '.');
                        } else {
                            button.text('<?= addslashes($strings["forms_uploading"]); ?>');
                        }
                    }, 200);
                },
                onComplete: function (file, response) {
                    if (doc == "doc") {
                        button.html('Browse');
                    } else {
                        button.html('<i class="fa fa-image"></i> <?= addslashes($strings["clients_addeditimage"]); ?>');
                    }
                    window.clearInterval(interval);
                    this.enable();
                    if (doc == "doc") {
                        $('#' + button_id).parent().find('span').text(" " + response);
                        $('.' + button_id + "_doc").val(response);
                        $('#delete_' + button_id).attr('title', response);
                        if (button_id == 'addMore1') {
                            $('#delete_' + button_id).show();
                        }
                    } else {
                        $("#clientpic").attr("src", '<?php echo $this->request->webroot;?>img/jobs/' + response);
                        $('#client_img').val(response);
                    }
//$('.flashimg').show();
                }
            });
        }

    function clientclick(Index){
        //var ProfileType = $('#nProfileType').val();
        <?php if(!isset($p) || ! ($p->admin || $p->super)){?>
        var elements = document.getElementsByClassName('addclientz');
        var element = document.getElementById("c_" + Index), id, checked = element.checked;
        set_visible("doplaceorders", checked);
        for (var i = 0; i < elements.length; ++i) {
            element = elements[i];
            id = element.getAttribute('id').substr(2);
            if(id != Index) {
                element.disabled = checked;
                element = element.parentNode;
                if(checked) {
                    element.classList.add("disabled");
                    element.parentNode.classList.add("disabled");
                } else {
                    element.classList.remove("disabled");
                    element.parentNode.classList.remove("disabled");
                }
            }
        }
        <?php } ?>
    }
</script>

</div>
<!-- </div> END PORTLET-->