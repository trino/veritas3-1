<?php
$strings2 = CacheTranslations($language, array("consent_%", "file_attachfile", "tasks_date", "profiles_name", "addorder_problem", "forms_datasaved", "forms_signplease"), $settings, False);
$gender = "";
$newsigmethod = true;
if($newsigmethod){include("signature.php");}
?>
<script>
      
    function save_signature(numb) {
        $("#test" + numb).data("jqScribble").save(function (imageData) {
            $.post('<?php echo $webroot; ?>canvas/image_save.php', {imagedata: imageData}, function (response) {
                if(response=='') {
                    alert('<?= addslashes($strings["addorder_problem"]); ?>');
                }
                switch (numb) {
                    case '1004':
                        $('#signature_company_witness').val(response);
                        break;
                    case '1003':
                        $('#criminal_signature_applicant').val(response);
                        break;
                    case '1002':
                        $('#signature_company_witness2').val(response);
                        break;
                    case '1001':
                        $('#criminal_signature_applicant2').val(response);
                        break;
                }
                $('.saved'+numb).html('<?= addslashes($strings["forms_datasaved"]); ?>');
                /*$('#hiddensub').click();*/
            });
        });
    }

    function checkformint(){
        /*
         save_signature('1001');
         save_signature('1002');
         save_signature('1003');
         save_signature('1004');
         //if(criminal_signature_applicant2!="" ||signature_company_witness2!="" ||criminal_signature_applicant!="" ||signature_company_witness!="")
         alert('Form submitted succesfully.')
         */

        /*
         if($(".touched1").val() == 0 || $(".touched2").val() == 0 || $(".touched3").val() == 0 || $(".touched4").val() == 0 ){
         alert("Please save the signatures");
         return false;
         }
         */
        var scrollto = "";
        <?php if($newsigmethod){ ?>
            if(!savedcriminal_signature_applicant2){scrollto = "signature-padcriminal_signature_applicant2"; }
            if(!scrollto && !savedcriminal_signature_applicant){scrollto = "signature-padcriminal_signature_applicant"; }
        <?php } else { ?>
            if($('.touched1').val()==0) {scrollto = '#test1001';}
            //if($('.touched2').val()==0) {scrollto = '#test1002';}
            if($('.touched3').val()==0) {scrollto = '#test1003';}
            //if($('.touched4').val()==0) {scrollto = '#test1004';}
        <?php  } ?>
        if(scrollto) {
            alert('<?= addslashes($strings["forms_signplease"]); ?>');
            $('html,body').animate({scrollTop: $(scrollto).offset().top}, 'slow');
            return false;
        }
        return true;

    }


</script>


    <div class="form-group row col-md-12">
     <center>  <p class="col-md-12" style="font-weight: bold;"><?= $strings2["consent_release"]; ?></p></center>
    </div>
    <div class="gndn">
        <div class="form-group row col-md-12 splitcols">

            <div class="col-md-4"><label class="control-label required"><?= $strings["forms_firstname"]; ?>: </label>
                <input type="text" class="form-control required" required name="first_name" value="<?php if (isset($profile))echo $profile["fname"];?>"/>
            </div>

            <div class="col-md-4"><label class="control-label"><?= $strings["forms_middlename"]; ?>: </label>
                <input type="text" class="form-control" name="mid_name" value="<?php if (isset($profile))echo $profile["mname"];?>"/>
            </div>

            <div class="col-md-4"><label class="control-label required"><?= $strings["forms_lastname"]; ?>: </label>
                <input type="text" class="form-control required" required name="last_name" value="<?php if (isset($profile))echo $profile["lname"];?>"/>
            </div>

            <div class="col-md-4"><label class="control-label">
                    <?= $strings2["consent_prevname"]; ?>:
                </label>
                <input type="text" class="form-control" name="previous_last_name"/>
            </div>

            <div class="col-md-4"><label class="control-label required"><?= $strings["forms_placeofbirth"]; ?> (<?= $strings["forms_country"]; ?>): </label>
                <input type="text" class="form-control required" required name="place_birth_country" value="Canada"/>
            </div>


            <div class="col-md-4"><label class="control-label required"><?= $strings["forms_dateofbirth"]; ?>: </label>
                <input type="text" class="form-control datepicker required" required placeholder="<?= $strings["forms_dateformat"]; ?>" value="<?php if (isset($profile))echo $profile["dob"];?>"
                       name="birth_date"/>
            </div>

            <div class="col-md-4"><label class="control-label required"><?= $strings["forms_gender"]; ?>: </label>
                <SELECT class="form-control required" required name="sex">
                    <?php
                    if (isset($profile)) {
                        $gender = "Female";
                        if ($profile["title"] == "Mr.") {$gender = "Male";}
                    }
                    printoption($strings["forms_selectgender"], "");
                    printoption($strings["forms_male"], $gender, "Male");
                    printoption($strings["forms_female"], $gender, "Female");
                    ?>
                </SELECT>
            </div>

            <div class="col-md-4"><label class="control-label required"><?= $strings["forms_phone"]; ?>: </label>
                <input type="text" class="form-control required" required name="phone" value="<?php if (isset($profile))echo $profile["phone"];?>"/>
            </div>


            <div class="col-md-4"><label class="control-label"><?= $strings2["consent_aliases"]; ?>: </label>
                <input type="text" class="form-control" name="aliases"/>
            </div>


            <!--div class="col-md-4"><label class="control-label required "><?= $strings["forms_driverslicense"]; ?>: </label>
                <input type="text" required class="form-control required" name="driver_license_number" value="<?php if (isset($profile))echo $profile["driver_license_no"];?>"/>
            </div>

            <div class="col-md-4"><label class="control-label required"><?= $strings["forms_provinceissued"]; ?>:</label>
                <?php
                $province = "";
                if (isset($profile)){$province = $profile["driver_province"];}
                provinces("driver_license_issued", $province, true);
                ?>
            </div-->


            <div class="col-md-4"><label class="control-label required"><?= $strings["forms_email"]; ?>: </label>
                <input type="text" class="form-control email1 required" required name="applicants_email" value="<?php if (isset($profile))echo $profile["email"];?>"/>
            </div>
        </div>


        <div class="form-group row col-md-12 splitcols">
            <label class="control-label col-md-12 required"><?= $strings2["consent_currentadd"]; ?>: </label>
            <div class="col-md-3">
                <input type="text" class="form-control required" required placeholder="<?= $strings2["consent_streetandn"]; ?>" value="<?php if (isset($profile))echo $profile["street"];?>"
                       name="current_street_address"/>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="<?= $strings2["consent_apartmentu"]; ?>" name="current_apt_unit" value="<?php if (isset($consent_detail))echo $consent_detail->current_apt_unit;?>"/>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control required" required placeholder="<?= $strings["forms_city"]; ?>" name="current_city" value="<?php if (isset($profile)) echo $profile["city"];?>"/>
            </div>
            <div class="col-md-2">
                <?php
                $province = "";
                if (isset($profile)){$province = $profile["province"];}
                provinces("current_province", $province, true); ?>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control required" required placeholder="<?= $strings["forms_postalcode"]; ?>" name="current_postal_code" value="<?php if (isset($profile))echo $profile["postal"];?>"/>
            </div>
        </div>

        <div class="form-group row col-md-12 splitcols">
            <label class="control-label col-md-12 "><?= $strings2["consent_previousad"]; ?>: </label>
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="<?= $strings2["consent_streetandn"]; ?>" name="previous_street_address" value="<?php if (isset($consent_detail))echo $consent_detail->previous_street_address;?>"/>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="<?= $strings2["consent_apartmentu"]; ?>" name="previous_apt_unit" value="<?php if (isset($consent_detail))echo $consent_detail->previous_apt_unit;?>"/>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="<?= $strings["forms_city"]; ?>" name="previous_city" value="<?php if (isset($consent_detail))echo $consent_detail->previous_city;?>"/>
            </div>
            <div class="col-md-2">
                <?php provinces("previous_province"); ?>
                <!-- <input type="text" class="form-control" placeholder="Province" name="previous_province"/> -->
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="<?= $strings["forms_postalcode"]; ?>" name="previous_postal_code" value="<?php if (isset($consent_detail))echo $consent_detail->last_name;?>"/>
            </div>
        </div>

        <div class="form-group row col-md-12">
            <div class="col-md-12">
                <p><?= $strings2["consent_a0"]; ?>:</p>
                <ul>
                    <li><?= $strings2["consent_a1"]; ?></li>
                    <li><?= $strings2["consent_a2"]; ?></li>
                    <li><?= $strings2["consent_a3"]; ?></li>
                    <li><?= $strings2["consent_a4"]; ?></li>
                    <li><?= $strings2["consent_a5"]; ?></li>
                    <li><?= $strings2["consent_a6"]; ?></li>
                    <li><?= $strings2["consent_a7"]; ?></li>
                    <li><?= $strings2["consent_a8"]; ?></li>
                </ul>
                <p><?= $strings2["consent_b0"]; ?>: </p>
                <ul>
                    <li><?= $strings2["consent_b1"]; ?></li>
                    <li><?= $strings2["consent_b2"]; ?></li>
                </ul>
            </div>
        </div>

        <div class="form-group row col-md-12">
            <div class="col-md-12">
                <h4>*<?= $strings2["consent_c0"]; ?></h4>
                <p><?= $strings2["consent_c1"]; ?></p>
                <p><?= $strings2["consent_c2"]; ?></p>
                <p>*<?= $strings2["consent_c3"]; ?></p>
                <p>*<?= $strings2["consent_c4"]; ?></p>
            </div>
        </div>

        <div class="form-group row col-md-12">
            <label style="  text-align: left;" class="control-label col-md-11"><?= $strings2["consent_d0"]; ?>: </label>

            <p class="col-md-11"><i style="color:red;"><?= $strings["forms_savesig"] . " " .  $strings2["consent_notrequired"]; ?></i></p>




        </div>

        <DIV CLASS="splitcols">
            <div class="form-group col-md-6">
                <?php
                    if($newsigmethod){
                        includeCanvas("criminal_signature_applicant2");
                    } else {
                        include('../webroot/canvas/gfs_sign1.php');
                    }
                ?>
                <div class="col-sm-10" style="display: none">
                    <p class="no-print no-view" style="color: red;"><?= $strings["forms_signhere"]; ?></p>
                </DIV>
            </div>
            <div class="form-group col-md-6">
                <?php
                    if($newsigmethod){
                        //includeCanvas("signature_company_witness2");
                    } else {
                        //include('../webroot/canvas/gfs_sign2.php');
                    }
                ?>
                <div class="col-sm-10" style="display: none">
                    <p class="no-print no-view" style="color: red;"><?= $strings["forms_signhere"]; ?></p>
                </DIV
            </div>
        </DIV>

        <div class="clearfix"></div>
        <div class="form-group row col-md-12 splitcols">


            <div class="col-md-4"><label class="control-label"><?= $strings2["consent_companynam"]; ?>: </label>
                <input type="text" class="form-control" name="company_name_requesting" value="<?php if (isset($consent_detail))echo $consent_detail->company_name_requesting;?>"/>
            </div>


            <div class="col-md-4"><label class="control-label"><?= $strings2["consent_printednam"]; ?>: </label>
                <input type="text" class="form-control" name="printed_name_company_witness" value="<?php if (isset($consent_detail))echo $consent_detail->printed_name_company_witness;?>"/>
            </div>

            <div class="col-md-4"><label class="control-label"><?= $strings2["consent_companyloc"]; ?> (<?= $strings["forms_country"]; ?>): </label>
                <input type="text" class="form-control" name="company_location" value="<?php if (isset($consent_detail))echo $consent_detail->company_location;?>"/>
            </div>

        </div>

        <div class="clearfix"></div>
    </div>


    <div class="clearfix"></div>
    <hr/>

    <div class="form-group row col-md-12">
        <strong class="col-md-12">
            <?= $strings2["consent_d1"]; ?>
        </strong>
    </div>

    <div class="form-group row col-md-12 ">
        <div class="col-md-12">
            <p>*<?= $strings2["consent_d2"]; ?></p>
            <h4><?= $strings2["consent_d3"]; ?></h4>
        </div>

        <div class="form-group row col-md-12 splitcols">
            <div class="col-md-4"><label class="control-label"><?= $strings2["consent_surname"]; ?>: </label>
                <input type="text" class="form-control" name="criminal_surname" value="<?php if (isset($consent_detail))echo $consent_detail->criminal_surname;?>"/>
            </div>


            <div class="col-md-4"><label class="control-label"><?= $strings2["consent_givenname"]; ?>: </label>
                <input type="text" class="form-control" name="criminal_given_name" value="<?php if (isset($consent_detail))echo $consent_detail->criminal_given_name;?>"/>
            </div>

            <div class="col-md-4"><label class="control-label"><?= $strings["forms_gender"]; ?>: </label>
                <SELECT name="criminal_sex" class="form-control" >
                    <?php
                        printoption($strings["forms_selectgender"], "");
                        printoption($strings["forms_male"], $gender, "Male");
                        printoption($strings["forms_female"], $gender, "Female");
                    ?>
                </SELECT>
                <!--<input type="text" class="form-control" name="criminal_sex"/>-->
            </div>

            <DIV CLASS="splitcols">
                <div class="col-md-4"><label class="control-label"><?= $strings["forms_dateofbirth"]; ?>: </label>
                    <input type="text" class="form-control datepicker" placeholder="<?= $strings["forms_dateformat"]; ?>" value="<?php if (isset($profile))echo $profile["dob"];?>"
                           name="criminal_date_birth"/>
                </div>

                <div class="col-md-4"><label class="control-label"><?= $strings2["tasks_date"]; ?>: </label>
                    <input type="text" class="form-control datepicker" placeholder="<?= $strings["forms_dateformat"]; ?>" name="criminal_date"
                           value="<?php if (isset($consent_detail)) {echo $consent_detail->criminal_date;} else { echo date("Y-m-d"); }?>">
                </div>
            </DIV>
        </div>


        <div class="form-group row col-md-12 splitcols">
            <label class="control-label col-md-12"><?= $strings2["consent_currentadd"]; ?>: </label>

            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="<?= $strings["forms_address"]; ?>" name="criminal_current_address" value="<?php if (isset($consent_detail))echo $consent_detail->criminal_current_address;?>"/>
            </div>
            <div class="col-md-4">
                <?php provinces("criminal_current_province"); ?>
                <!--                 <input type="text" class="form-control" placeholder="Province" name="criminal_current_province"/>-->
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="<?= $strings["forms_postalcode"]; ?>" value="<?php if (isset($consent_detail))echo $consent_detail->criminal_current_postal_code;?>"
                       name="criminal_current_postal_code"/>
            </div>
        </div>

        <div class="col-md-12">
            <strong><?= $strings2["consent_d4"]; ?></strong>
            <ul>
                <li><?= $strings2["consent_d5"]; ?></li>
                <li><?= $strings2["consent_d6"]; ?>.</li>
            </ul>
        </div>

        <div class="col-md-12">
            <strong><?= $strings2["consent_e0"]; ?>:</strong>
            <ul>
                <li><?= $strings2["consent_e1"]; ?></li>
                <li><?= $strings2["consent_e2"]; ?></li>
                <li><?= $strings2["consent_e3"]; ?></li>
                <li><?= $strings2["consent_e4"]; ?></li>
                <li><?= $strings2["consent_e5"]; ?></li>
                <li><?= $strings2["consent_e6"]; ?></li>
            </ul>
        </div>

        <div class="col-md-12">
            <strong><?= $strings2["consent_f0"]; ?>:</strong>
            <p><?= $strings2["consent_f1"]; ?></p>
        </div>

        <div class="col-md-12">
            <div class="table-scrollable">
                <table class="table">
                    <thead>
                    <tr>
                        <th><?= $strings2["consent_offence"]; ?></th>
                        <th><?= $strings2["consent_dateofsent"]; ?></th>
                        <th><?= $strings2["consent_location"]; ?></th>
                    </tr>
                    </thead>
                    <?php
                    $i = 0;
                    if (isset($sub2) && $sub2) {
                        foreach ($sub2['con_cri'] as $con_cri) {
                            $co[$i] = $con_cri->offence;
                            $cd[$i] = $con_cri->date_of_sentence;
                            $cl[$i] = $con_cri->location;

                            $i++;
                        }
                    }
                    if ($i <= 7) {
                        for ($j = $i; $j <= 7; $j++) {
                            $co[$j] = '';
                            $cd[$j] = '';
                            $cl[$j] = '';
                        }
                    }

                    ?>
                    <?php
                    for ($k = 0; $k < 8; $k++) {
                        ?>
                        <tr>
                            <td><input type="text" class="form-control" name="offence[]"
                                       value="<?php echo $co[$k]; ?>"/>
                            </td>
                            <td><input type="text" class="form-control datepicker" name="date_of_sentence[]"
                                       value="<?php echo $cd[$k]; ?>"/></td>
                            <td><input type="text" class="form-control" name="location[]"
                                       value="<?php echo $cl[$k]; ?>"/></td>
                        </tr>
                    <?php
                    }
                    ?>

                </table>
            </div>
        </div>

        <div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>
    <hr/>

    <div class="form-group row">
        <h3 class="col-md-12">
            <?= $strings2["consent_f2"]; ?>
        </h3>

        <div class="gndn">
            <div class="col-md-12">
                <h4><?= $strings2["consent_f3"]; ?></h4>
            </div>
            <div class="col-md-12">
                <p>

                <div class="col-md-5">1.&nbsp;&nbsp;<?= $strings2["consent_g1a"]; ?></div>
                <div class="col-md-3"><input type="text" class="form-control" disabled name="psp_employer" value="<?=$clientname; ?>"/></div>
                <div class="col-md-4"><?= $strings2["consent_g1b"]; ?></div>
                <br/><br/> <?= $strings2["consent_g1c"]; ?></p>
                <p><?= $strings2["consent_g1d"]; ?></p>
                <p><?= $strings2["consent_g1e"]; ?></p>
                <p><?= $strings2["consent_g1f"]; ?></p>
                <p><?= $strings2["consent_g1g"]; ?>:</p>
            </div>
            <div class="col-md-12">
                <p>

                <div class="col-md-2">2.&nbsp;&nbsp;<?= $strings2["consent_g2a"]; ?></div>
                <div class="col-md-3"><input type="text" class="form-control" value="<?=$clientname; ?>" disabled name="authorize_name_hereby"/></div>
                <div class="col-md-7"><?= $strings2["consent_g2b"]; ?></div>
                </p><br/><br/>

                <p><?= $strings2["consent_g2c"]; ?></p>
                <p>3.&nbsp;&nbsp;<?= $strings2["consent_g3a"]; ?></p>
                <p>4.&nbsp;&nbsp;<?= $strings2["consent_g3b"]; ?></p>
                <p><?= $strings2["consent_g3c"]; ?></p>

                <label class="control-label col-md-2"><?= $strings2["tasks_date"]; ?>: </label>

                <div class="col-md-2">
                    <input type="text" class="form-control" value="<?= $today; ?>" disabled name="authorize_date"/>
                </div>
                <!--<label class="control-label col-md-3">Signature: </label>
                <div class="col-md-3">
                    <input type="hidden" class="form-control" name="authorize_signature"/>
                </div>-->
                <input type="hidden" class="form-control" name="authorize_signature" />

                <label class="control-label col-md-2"> <?= $strings2["profiles_name"]; ?>: </label>

                <div class="col-md-5">
                    <input type="text" class="form-control" name="authorize_name" <?php if (isset($profile)) {echo 'value="' . $profile["fname"] . " " . $profile["mname"] . " " . $profile["lname"] . '" disabled';}?>/>
                </div>
            </div>
            <div class="col-md-12">
                <p><?= $strings2["consent_g3d"]; ?></p>

                <p><?= $strings2["consent_lastupdate"]; ?> 10/29/2012</p>
            </div>

            <DIV CLASS="splitcols">
                <div class="form-group col-md-6">
                    <?php
                        if($newsigmethod){
                            includeCanvas("criminal_signature_applicant");
                        } else {
                            include('../webroot/canvas/gfs_sign3.php');
                        }
                    ?>
                    <div class="col-sm-10" style="display: none">
                        <p class="no-print no-view" style="color: red;"><?= $strings["forms_signhere"]; ?></p>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <?php
                    if($newsigmethod){
                        //includeCanvas("signature_company_witness");
                    } else {
                        //include('../webroot/canvas/gfs_sign4.php');
                    }
                    ?>
                    <div class="col-sm-10" style="display: none">
                        <p class="no-print no-view" style="color: red;"><?= $strings["forms_signhere"]; ?></p>
                    </div>
                </div>
            </DIV>

            <!--<div class="col-md-12">
            <p>&nbsp;</p>

            <div>
                <div class="col-md-12"><strong>Reference #1</strong></div>
                <div class="col-md-4">
                <label>Phone Number</label>
                <input type="text" name="r1_phone" required=""   class="form-control" />
                </div>
                <div class="col-md-4">
                <label>Name</label>
                <input type="text" name="r1_name" required=""  class="form-control" />
                </div>
                <div class="col-md-4">
                <label>Position</label>
                <input type="text" name="r1_position" required=""  class="form-control" />
                </div>
            </div>

            <p>&nbsp;</p>
            <div>
                <div class="col-md-12"><strong>Reference #2</strong></div>
                <div class="col-md-4">
                <label>Phone Number</label>
                <input type="text" name="r2_phone" required=""  class="form-control" />
                </div>
                <div class="col-md-4">
                <label>Name</label>
                <input type="text" name="r2_name" required=""  class="form-control" />
                </div>
                <div class="col-md-4">
                <label>Position</label>
                <input type="text" name="r2_position" required="" class="form-control" />
                </div>
            </div>
            <p>&nbsp;</p>
            </div>-->

<?php return; ?>
            <div class="clearfix"></div>
            <div class="allattach" <?= $AllowUploads; ?>>
                <?php
                if (!isset($sub2['con_at'])) {
                    $sub2['con_at'] = array();
                }
                if (!count($sub2['con_at'])) {
                    ?>
                    <div class="form-group col-md-12" style="display:block;margin-top:5px; margin-bottom: 5px;">
                        <label class="control-label col-md-3"><?= $strings2["consent_attachid"]; ?>: </label>

                        <div class="col-md-9">
                            <input type="hidden" name="attach_doc[]" class="consent1"/>
                            <a href="javascript:void(0);" id="consent1" class="btn btn-primary"><?= $strings["forms_browse"]; ?></a>
                            <span class="uploaded"></span>
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group col-md-12" >
                    <div id="more_consent_doc"
                         data-consent="<?php if (count($sub2['con_at'])) echo count($sub2['con_at']); else echo '1'; ?>">
                        <?php
                        if (count($sub2['con_at'])) {
                            $at = 0;
                            foreach ($sub2['con_at'] as $pa) {
                                if($pa->attachment){
                                    $at++;
                                    ?>
                                    <div class="del_append_consent">
                                        <label class="control-label col-md-3"><?= $strings2["file_attachfile"]; ?>: </label>

                                        <div class="col-md-6 pad_bot">
                                            <input type="hidden" class="consent<?php echo $at; ?>" name="attach_doc[]"
                                                   value="<?php echo $pa->attachment; ?>"/>
                                            <a href="#" id="consent<?php echo $at; ?>" class="btn btn-primary"><?= $strings["forms_browse"]; ?></a>
                                            <a href="javascript:void(0);" class="btn btn-danger" id="delete_doc"
                                               onclick="$(this).parent().remove();"><?= $strings["dashboard_delete"]; ?></a>
                                    <span class="uploaded"><?php echo $pa->attachment; ?>  <?php if ($pa->attachment) {
                                            $ext_arr = explode('.', $pa->attachment);
                                            $ext = end($ext_arr);
                                            $ext = strtolower($ext);
                                            if (in_array($ext, $img_ext)) { ?><img
                                                src="<?php echo $this->request->webroot; ?>attachments/<?php echo $pa->attachment; ?>"
                                                style="max-width:120px;" /><?php } elseif (in_array($ext, $doc_ext)) { ?>
                                            <a class="dl"
                                               href="<?php echo $this->request->webroot; ?>attachments/<?php echo $pa->attachment; ?>">
                                                    Download</a><?php } else { ?><br/>
                                                <video width="320" height="240" controls>
                                                    <source
                                                        src="<?php echo $this->request->webroot; ?>attachments/<?php echo $pa->attachment; ?>"
                                                        type="video/mp4">
                                                    <source
                                                        src="<?php echo $this->request->webroot; ?>attachments/<?php echo str_replace('.mp4', '.ogg', $pa->attachment); ?>"
                                                        type="video/ogg">
                                                    <?= $strings["forms_novideo"]; ?>
                                                </video>
                                            <?php }
                                        } ?></span>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <script>
                                        $(function () {
                                            fileUpload('consent<?php echo $at;?>');
                                        });
                                    </script>
                                <?php
                                }}
                        }
                        ?>
                    </div>
                </div>

                <div class="form-group col-md-12" <?= $AllowUploads; ?>>
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-9">
                        <a href="javascript:void(0);" class="btn btn-primary moremore" id="add_more_consent_doc"><?= $strings["forms_addmore"]; ?></a>
                    </div>
                </div>



                <div class="clearfix"></div>


        <!--script>
            $(function () {
                <?php if($this->request->params['action'] != 'vieworder' && $this->request->params['action']!= 'view'){?>
                $("#test3").jqScribble();
                $("#test4").jqScribble();
                $("#test5").jqScribble();
                $("#test6").jqScribble();
                <?php }?>

                <?php
                if(($this->request->params['action']=='addorder' || $this->request->params['action']=='add') && !count($sub2['con_at']))
                {
                    ?>
                fileUpload('consent1');

                <?php
            }
            ?>

                $('#add_more_consent_doc').click(function () {
                    var count = $('#more_consent_doc').data('consent');
                    $('#more_consent_doc').data('consent', parseInt(count) + 1);
                    $('#more_consent_doc').append('<div class="del_append_consent"><label class="control-label col-md-3"></label><div class="col-md-6 pad_bot"><input type="hidden" name="attach_doc[]" class="consent' + $('#more_consent_doc').data('consent') + '" /><a id="consent' + $('#more_consent_doc').data('consent') + '" href="javascript:void(0);" class="btn btn-primary"><?= addslashes($strings["forms_browse"]); ?></a> <a  href="javascript:void(0);" class="btn btn-primary" id="delete_consent_doc">Delete</a> <span class="uploaded"></span></div></div><div class="clearfix"></div>');
                    fileUpload('consent' + $('#more_consent_doc').data('consent'));
                });

                $('#delete_consent_doc').live('click', function () {
                    $(this).closest('.del_append_consent').remove();
                });
            });
        </script-->
