<?php
    if($this->request->params['controller']!='ClientApplication'){
    if ($this->request->session()->read('debug')) {
        echo "<span style ='color:red;'>subpages/documents/consent_form.php #INC139</span>";
    }
    //include_once 'subpages/filelist.php';
    if (isset($sub2)) { listfiles($sub2['con_at'], "attachments/", "", false, 3,false,'consent');     }}
    //includejavascript($strings);
    $strings2 = CacheTranslations($language, array("consent_%", "file_attachfile", "tasks_date", "profiles_name"), $settings, False);
?>
<?php if(isset($dx)){ echo '<h3 style="">' . $dx->title . '</h3>'; }?>
<form id="form_consent">
    <div class="form-group row">
        <p class="col-md-12" style="font-weight: bold;"><?= $strings2["consent_release"]; ?></p>
    </div>
    <div class="gndn">
        <div class="form-group row">
            <div class="col-md-4 margin-bottom-10"><label class="control-label required"><?= $strings["forms_firstname"]; ?>: </label>
                <input type="text" class="form-control required" name="first_name" value="<?php if (isset($consent_detail))echo $consent_detail->first_name;?>"/>
                <span class="error"></span>
            </div>

            <div class="col-md-4 margin-bottom-10"><label class="control-label"><?= $strings["forms_middlename"]; ?>: </label>
                <input type="text" class="form-control "  name="mid_name" value="<?php if (isset($consent_detail))echo $consent_detail->mid_name;?>"/>
            </div>

            <div class="col-md-4 margin-bottom-10"><label class="control-label required"><?= $strings["forms_lastname"]; ?>: </label>
                <input type="text" class="form-control required" name="last_name" value="<?php if (isset($consent_detail))echo $consent_detail->last_name;?>"/>
                <span class="error"></span>
            </div>

            <div class="col-md-4 margin-bottom-10"><label class="control-label">
                    <?= $strings2["consent_prevname"]; ?>:
                </label>
                <input type="text" class="form-control" name="previous_last_name" value="<?php if (isset($consent_detail))echo $consent_detail->previous_last_name;?>"/>
            </div>

            <div class="col-md-4 margin-bottom-10"><label class="control-label"><?= $strings["forms_placeofbirth"]; ?> (<?= $strings["forms_country"]; ?>): </label>
                <input type="text" class="form-control" name="place_birth_country" value="<?php if (isset($consent_detail))echo $consent_detail->place_birth_country;?>"/>
            </div>


            <div class="col-md-4 margin-bottom-10"><label class="control-label "><?= $strings["forms_dateofbirth"]; ?>: </label>
                <input type="text" class="form-control date-picker datepicker  " placeholder="<?= $strings["forms_dateformat"]; ?>" value="<?php if (isset($consent_detail))echo $consent_detail->birth_date;?>"
                       name="birth_date"/>
                       <span class="error"></span>
            </div>

            <div class="col-md-4 margin-bottom-10"><label class="control-label"><?= $strings["forms_gender"]; ?>: </label>
            <select name="sex" class="form-control">
                <option value="Male" <?php if (isset($consent_detail)&& $consent_detail->sex=='Male')echo "selected:selected";?>>Male</option>
                <option value="Female" <?php if (isset($consent_detail)&& $consent_detail->sex=='Female')echo "selected:selected";?>>Female</option>
            </select>
                
            </div>

            <div class="col-md-4 margin-bottom-10"><label class="control-label"><?= $strings["forms_phone"]; ?>: </label>
                <input type="text" class="form-control" name="phone" role="phone" value="<?php if (isset($consent_detail))echo $consent_detail->phone;?>"/>
            </div>


            <div class="col-md-4 margin-bottom-10"><label class="control-label"><?= $strings2["consent_aliases"]; ?>: </label>
                <input type="text" class="form-control" name="aliases" value="<?php if (isset($consent_detail))echo $consent_detail->aliases;?>"/>
            </div>


            <div class="col-md-4 margin-bottom-10"><label class="control-label"><?= $strings["forms_driverslicense"]; ?>: </label>
                <input type="text" class="form-control" name="driver_license_number" value="<?php if (isset($consent_detail))echo $consent_detail->driver_license_number;?>"/>
            </div>

            <div class="col-md-4 margin-bottom-10"><label class="control-label"><?= $strings["forms_provinceissued"]; ?>:</label>
                <?php
                $DefaultProvince = "";
                if (isset($consent_detail)) {$DefaultProvince = $consent_detail->driver_license_issued;}
                provinces("driver_license_issued", $DefaultProvince);
                ?>
            </div>

            <div class="col-md-4 margin-bottom-10"><label class="control-label"><?= $strings["forms_email"]; ?>: </label>
                <input type="text" class="form-control email1"  role="email" name="applicants_email" value="<?php if (isset($consent_detail))echo $consent_detail->applicants_email;?>"/>
            </div>
        </div>

        <div class="form-group row  col-md-12">
            <label class="control-label"><?= $strings2["consent_currentadd"]; ?>: </label>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="<?= $strings2["consent_streetandn"]; ?>" value="<?php if (isset($consent_detail))echo $consent_detail->current_street_address;?>"
                       name="current_street_address"/>
                       <span class="error"></span>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="<?= $strings2["consent_apartmentu"]; ?>" name="current_apt_unit" value="<?php if (isset($consent_detail))echo $consent_detail->current_apt_unit;?>"/>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="<?= $strings["forms_city"]; ?>" name="current_city" value="<?php if (isset($consent_detail))echo $consent_detail->current_city;?>"/>
                <span class="error"></span>
            </div>
            <div class="col-md-2">
                <?php
                    if (isset($consent_detail)) {$DefaultProvince = $consent_detail->current_province;}
                    provinces("current_province", $DefaultProvince);
                ?>
            </div>
            <div class="col-md-3">
                <input type="text" role="postalcode" class="form-control" placeholder="<?= $strings["forms_postalcode"]; ?>" name="current_postal_code" value="<?php if (isset($consent_detail))echo $consent_detail->current_postal_code;?>"/>
                <span class="error"></span>
            </div>
        </div>

        <div class="form-group row col-md-12">
            <label class="control-label"><?= $strings2["consent_previousad"]; ?>: </label>
        </div>
        <div class="form-group row">
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
            </div>
            <div class="col-md-3">

            <input type="text" role="postalcode" class="form-control" placeholder="<?= $strings["forms_postalcode"]; ?>" name="previous_postal_code" value="<?php if (isset($consent_detail))echo $consent_detail->previous_postal_code;?>"/>
            </div>
            </div>

        </div>

        <div class="form-group row">
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

        <div class="form-group row">
            <div class="col-md-12">
                <h4>*<?= $strings2["consent_c0"]; ?></h4>
                <p><?= $strings2["consent_c1"]; ?></p>
                <p><?= $strings2["consent_c2"]; ?></p>
                <p>*<?= $strings2["consent_c3"]; ?></p>
                <p>*<?= $strings2["consent_c4"]; ?></p>
            </div>
        </div>

        <div class="form-group row">
            <label style="  text-align: left;" class="control-label col-md-11"><?= $strings2["consent_d0"]; ?>: </label>
        </div>

        <DIV CLASS="row">
            <div class="form-group col-md-6">
                <?php include('canvas/consent_signature_driver2.php'); ?>
            </div>
            <?php if($this->request->params['action']!='apply'){?>
            <div class="form-group col-md-6">
                <?php include('canvas/consent_signature_witness2.php'); ?>
            </div>
            <?php }?>
        </div>

        <div class="clearfix"></div>
        <div class="form-group row">


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

    <div class="form-group row">
        <strong class="col-md-12">
            <?= $strings2["consent_d1"]; ?>
        </strong>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <p>*<?= $strings2["consent_d2"]; ?></p>
            <h4><?= $strings2["consent_d3"]; ?></h4>

            <div class="form-group row">


                <div class="col-md-4"><label class="control-label"><?= $strings2["consent_surname"]; ?>: </label>
                    <input type="text" class="form-control" name="criminal_surname" value="<?php if (isset($consent_detail))echo $consent_detail->criminal_surname;?>"/>
                </div>


                <div class="col-md-4"><label class="control-label"><?= $strings2["consent_givenname"]; ?>: </label>
                    <input type="text" class="form-control" name="criminal_given_name" value="<?php if (isset($consent_detail))echo $consent_detail->criminal_given_name;?>"/>
                </div>

                <div class="col-md-4"><label class="control-label"><?= $strings["forms_gender"]; ?>: </label>
                    <SELECT name="criminal_sex" class="form-control" >
                        <OPTION VALUE="Male"><?= $strings["forms_male"]; ?></OPTION>
                        <OPTION VALUE="Female"><?= $strings["forms_female"]; ?></OPTION>
                    </SELECT>
                </div>


                <div class="col-md-4"><label class="control-label"><?= $strings["forms_dateofbirth"]; ?>: </label>
                    <input type="text" class="form-control datepicker  date-picker" placeholder="<?= $strings["forms_dateformat"]; ?>" value="<?php if (isset($consent_detail))echo $consent_detail->criminal_date_birth;?>"
                           name="criminal_date_birth"/>
                </div>

                <div class="col-md-4"><label class="control-label"><?= $strings2["tasks_date"]; ?>: </label>
                    <input type="text" class="form-control datepicker date-picker" placeholder="<?= $strings["forms_dateformat"]; ?>" name="criminal_date" value="<?php if (isset($consent_detail))echo $consent_detail->criminal_date;?>"
                           value="<?php echo date("Y-m-d"); ?>"/>
                </div>

                <label class="control-label col-md-12"><?= $strings2["consent_currentadd"]; ?>: </label>

                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="<?= $strings["forms_address"]; ?>" name="criminal_current_address" value="<?php if (isset($consent_detail))echo $consent_detail->criminal_current_address;?>"/>
                </div>
                <div class="col-md-4">
                    <?php provinces("criminal_current_province"); ?>
                </div>
                <div class="col-md-4">
                    <input type="text" role="postalcode" class="form-control" placeholder="<?= $strings["forms_postalcode"]; ?>" value="<?php if (isset($consent_detail))echo $consent_detail->criminal_current_postal_code;?>"
                           name="criminal_current_postal_code"/>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <strong><?= $strings2["consent_d4"]; ?></strong>
            <ul>
                <li><?= $strings2["consent_d5"]; ?></li>
                <li><?= $strings2["consent_d6"]; ?></li>
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
                                <td><input type="text" class="form-control date-picker datepicker " name="date_of_sentence[]"
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
                    1.&nbsp;&nbsp;<?= $strings2["consent_g1a"]; ?>
                    <input type="text" class="form-control" name="psp_employer" style="width: 20% !important; display: inline;" placeholder="<?= str_replace('"', "&quot;", $strings2["consent_g2b"]); ?>"/>
                    <?= $strings2["consent_g1c"]; ?>
                </p>
                <p><?= $strings2["consent_g1d"]; ?></p>
                <p><?= $strings2["consent_g1e"]; ?></p>
                <p><?= $strings2["consent_g1f"]; ?></p>
                <p><?= $strings2["consent_g1g"]; ?>:</p>
            </div>
            <div class="col-md-12">
                    <P>
                    2.&nbsp;&nbsp;<?= $strings2["consent_g2a"]; ?>
                    <input type="text" class="form-control" style="width: 20% !important; display: inline;" name="authorize_name_hereby" placeholder="<?= str_replace('"', "&quot;", $strings2["consent_g2b"]); ?>"/>
                    <?= $strings2["consent_g2c"]; ?>
                    </P>
            </div>

            <div class="col-md-12">
                <p>3.&nbsp;&nbsp;<?= $strings2["consent_g3a"]; ?></p>
                <p>4.&nbsp;&nbsp;<?= $strings2["consent_g3b"]; ?></p>
                <p><?= $strings2["consent_g3c"]; ?></p>
            </div>

            <div class="col-md-12 row">
                <div class="form-group col-md-6">
                    <label class="control-label col-md-2"><?= $strings2["tasks_date"]; ?>: </label>
                    <div class="col-md-5">
                        <input type="text" class="form-control datepicker date-picker" name="authorize_date" value="<?= date('Y-m-d'); ?>"/>
                    </div>
                </DIV>
                <div class="form-group col-md-6">
                    <label class="control-label col-md-2"> <?= $strings2["profiles_name"]; ?>: </label>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="authorize_name" value="<?php if (isset($consent_detail))echo $consent_detail->authorize_name;?>"/>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <p><?= $strings2["consent_g3d"]; ?></p>

                <p><?= $strings2["consent_lastupdate"]; ?> 10/29/2012</p>
            </div>
            
            

            <div class="form-group col-md-6">
                <input type="hidden" class="form-control" name="authorize_signature" />
                <?php include('canvas/consent_signature_driver.php'); ?>
            </div>
            <?php if($this->request->params['action']!='apply'){?>
            <div class="form-group col-md-6">
                <?php include('canvas/consent_signature_witness.php'); ?>
            </div>
            <?php }?>
            <div class="clearfix"></div>

            <?php if($this->request->params['controller']!='Documents' && $this->request->params['controller']!='ClientApplication'){?>
            <div class="allattach">
                <?php
                    if (!isset($sub2['con_at'])) {
                        $sub2['con_at'] = array();
                    }
                    if (!count($sub2['con_at'])) {
                        ?>
                        <div class="form-group col-md-12 no-view" style="display:block;margin-top:5px; margin-bottom: 5px;">
                            <label class="control-label col-md-3"><?= $strings2["consent_attachid"]; ?>: </label>
    
                            <div class="col-md-9">
                                <input type="hidden" name="attach_doc[]" class="consent1"/>
                                <a href="javascript:void(0);" id="consent1" class="btn btn-primary no-print"><?= $strings["forms_browse"]; ?></a>
                                <span class="uploaded"></span>
                            </div>
                        </div>
                    <?php } ?>
                <div class="form-group col-md-12">
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
                                            <a href="#" id="consent<?php echo $at; ?>" class="btn btn-primary no-print"><?= $strings["forms_browse"]; ?></a>
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
           
                <div class="form-group col-md-12 no-print">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-9">
                        <a href="javascript:void(0);" class="btn btn-primary moremore" id="add_more_consent_doc"><?= $strings["forms_addmore"]; ?></a>
                    </div>
                </div>
           
                <div class="clearfix"></div>
            </div>
            <?php }?>
        </div>
        </div>
</form>


<script>
    $(function () {
        <?php if($this->request->params['action'] != 'vieworder' && $this->request->params['action']!= 'view'){?>
        $("#test3").jqScribble();
        $("#test4").jqScribble();
        $("#test5").jqScribble();
        $("#test6").jqScribble();
        
        $('#test3,#test6,#test4,#test5').click(function(){
            $(this).parent().parent().find('.touched').val('1');
        });
        
        <?php }?>

        <?php
        if(($this->request->params['action']=='addorder' || $this->request->params['action']=='add') && (!isset($sub2) || (isset($sub2) && !count($sub2['con_at']))))
        {
            ?>
        fileUpload('consent1');

        <?php
    }
    ?>

        $('#add_more_consent_doc').click(function () {
            var count = $('#more_consent_doc').data('consent');
            $('#more_consent_doc').data('consent', parseInt(count) + 1);
            $('#more_consent_doc').append('<div class="del_append_consent"><label class="control-label col-md-3"></label><div class="col-md-6 pad_bot"><input type="hidden" name="attach_doc[]" class="consent' + $('#more_consent_doc').data('consent') + '" /><a id="consent' + $('#more_consent_doc').data('consent') + '" href="javascript:void(0);" class="btn btn-primary no-print"><?= addslashes($strings["forms_browse"]); ?></a> <a  href="javascript:void(0);" class="btn btn-danger" id="delete_consent_doc">Delete</a> <span class="uploaded"></span></div></div><div class="clearfix"></div>');
            fileUpload('consent' + $('#more_consent_doc').data('consent'));
        });

        $('#delete_consent_doc').live('click', function () {
            $(this).closest('.del_append_consent').remove();
        });
    });
</script>