<?php
if($this->request->params['controller']!='ClientApplication'){
    if($this->request->session()->read('debug'))
    {
        echo "<span style ='color:red;'>subpages/documents/employment_verification_form.php #INC???</span>"; 
    }
}
$strings2 = CacheTranslations($language, array("verifs_%", "tasks_date", "file_attachfile", "file_download"), $settings, False);

function ifchar($Value, $True = '&#10004;', $False = '&#10006;'){
    if($Value){echo $True;} else {echo $False;}
}

?>
<STYLE>
    .required:after {
        content: " *";
        color: #e32;
    }
</STYLE>
<div id="form_tab9">
<input class="document_type" type="hidden" name="document_type" value="<?php if(isset($dx))echo $dx->title;else echo "Employment";?>" />
<input type="hidden" class="sub_docs_id" name="sub_doc_id" value="9"  />
<div class="tab-content">
<div class="tab-pane active" id="subtab_2_2">
    <form id="form_employment">

        <?php
        if($this->request->params['controller']!='ClientApplication'){
        include_once 'subpages/filelist.php';
        if (isset($sub3['att'])) { listfiles($sub3['att'], "attachments/", "", false,3,false,'employment');}
        }
        ?>

        <div class="form-group row">
            <h3 class="col-md-12"><?= $strings2["verifs_pastemploy"]; ?></h3>
        </div>
        <div class="GNDN">

                <?php
                $counter=0;
                if(isset($sub3['emp']) && count($sub3['emp'])){
                    
                    if($this->request->params['controller']!='Orders'){
                    if (count($sub3['emp']) == 1 && is_object($sub3['emp'])){
                        $sub3['emp'] = array($sub3['emp']);
                    }}

                    foreach($sub3['emp'] as $emp){
                        //var_dump($emp);die();
                        $counter++;
                        if($counter!=1){
                            if($counter==2){
                                echo '<div id="more_div" style="padding-left: 32px;">';
                            }
                            echo '<div id="toremove">';
                        }
                        ?>


                       <div class="form-group col-md-12">
                            <label class="control-label col-md-3 required"><?= $strings["forms_companyname"]; ?>: </label>
                            <div class="col-md-9">
                            <input type="text" class="form-control required" name="company_name[]" required value="<?php echo $emp->company_name;?>"  />
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label class="control-label col-md-3 required"><?= $strings["forms_address"]; ?>: </label>
                            <div class="col-md-3">
                                <input type="text" class="form-control required" required name="address[]" value="<?php echo $emp->address;?>" />
                            </div>

                            <label class="control-label col-md-3 required"><?= $strings["forms_city"]; ?>: </label>
                            <div class="col-md-3">
                                <input type="text" class="form-control required" required name="city[]" value="<?php echo $emp->city;?>" />
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label class="control-label col-md-3"><?= $strings["forms_provincestate"]; ?>: </label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="state_province[]" value="<?php echo $emp->state_province;?>" />
                            </div>
                            <label class="control-label col-md-3"><?= $strings["forms_country"]; ?>: </label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="country[]" value="<?php echo $emp->country;?>" />
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label class="control-label col-md-3"><?= $strings2["verifs_supername"]; ?>: </label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="supervisor_name[]" value="<?php echo $emp->supervisor_name;?>"/>
                            </div>
                            <label class="control-label col-md-3"><?= $strings["forms_phone"]; ?>: </label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="supervisor_phone[]" role="phone" value="<?php echo $emp->supervisor_phone;?>"/>
                            </div>
                       </div>

                       <div class="form-group col-md-12">
                            <label class="control-label col-md-3"><?= $strings2["verifs_superemail"]; ?>: </label>
                            <div class="col-md-3">
                                <input type="text" class="form-control email1" name="supervisor_email[]" role="email" value="<?php echo $emp->supervisor_email;?>"/>
                            </div>
                            <label class="control-label col-md-3"><?= $strings2["verifs_secondarye"]; ?>: </label>
                            <div class="col-md-3">
                                <input type="text" class="form-control email1" name="supervisor_secondary_email[]" role="email" value="<?php echo $emp->supervisor_secondary_email;?>"/>
                            </div>
                       </div>

                       <div class="form-group col-md-12">
                        <label class="control-label col-md-3"><?= $strings2["verifs_employment"]; ?>: </label>
                        <div class="col-md-3">
                        <input type="text" class="form-control date-picker datepicker" name="employment_start_date[]" value="<?php echo $emp->employment_start_date;?>"/>
                        </div>
                        <label class="control-label col-md-3"><?= $strings2["verifs_employment2"]; ?>: </label>
                        <div class="col-md-3">
                        <input type="text" class="form-control date-picker datepicker" name="employment_end_date[]" value="<?php echo $emp->employment_end_date;?>"/>
                        </div>
                        </div>
                        <div class="form-group col-md-12">
                        <label class="control-label col-md-3"><?= $strings2["verifs_claimswith"]; ?>: </label>
                        <div class="col-md-3 radio-list">
                        &nbsp;&nbsp;
                        <?php
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view') {
                            ifchar($emp->claims_with_employer);
                        } else {
                            ?>
                            <input type="radio" name="claims_with_employer_<?php $rand = rand(0,100); echo $rand; ?>[]" value="1" <?php if($emp->claims_with_employer == 1){?>checked="checked"<?php }?>/>
                            <?php
                        }
                         ?>
                         <label class="radio-inline">
                             <?= $strings["dashboard_affirmative"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </label>
                        <?php
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view') {
                            ifchar($emp->claims_with_employer);
                        } else {
                            ?>
                            <input type="radio" name="claims_with_employer_<?php echo $rand;?>[]"  value="0" <?php if($emp->claims_with_employer == 0){?>checked="checked"<?php }?>/>
                            <?php
                        }
                         ?>
                          <label class="radio-inline">
                              <?= $strings["dashboard_negative"]; ?>
                        </label>
                        </div>
                         <label class="control-label col-md-3"><?= $strings2["verifs_dateclaims"]; ?>: </label>
                         <div class="col-md-3">
                         <input type="text" class="form-control date-picker datepicker" name="claims_recovery_date[]" value="<?php echo $emp->claims_recovery_date;?>"/>
                         </div>
                         </div>

                         <div class="form-group col-md-12">
                        <label class="control-label col-md-3"><?= $strings2["verifs_employment3"]; ?>: </label>
                        <div class="col-md-9">
                        <input type="text" class="form-control" name="emploment_history_confirm_verify_use[]" value="<?php echo $emp->emploment_history_confirm_verify_use;?>"/>
                        </div>
                        </div>

                        <div class="form-group col-md-12">
                        <label class="control-label col-md-3">US DOT MC/MX#: </label>
                        <div class="col-md-3">
                        <input name="us_dot[]" type="text" class="form-control" name="us_dot[]" value="<?php echo $emp->us_dot;?>" />
                        </div>
                        <label class="control-label col-md-3" style="display: none;"><?= $strings["forms_signature"]; ?>: </label>
                        <div class="col-md-3">
                        <input type="text" class="form-control" style="display: none;" name="signature[]" value="<?php echo $emp->signature;?>" />
                        </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label class="control-label col-md-3"><?= $strings2["tasks_date"]; ?>: </label>
                        <div class="col-md-9">
                        <input type="text" class="form-control date-picker datepicker" name="signature_datetime[]" value="<?php echo $emp->signature_datetime;?>"/>
                        </div>
                        </div>
                        <div class="form-group col-md-12">
                                    <label class="control-label col-md-3"><?= $strings2["verifs_equipmento"]; ?>: </label>
                                    <div class="col-md-9">
                                        <?php
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view') {
                    ifchar($emp->equipment_vans, '&#9745;','&#9744;');
                } else {
                    ?>
                    <input type="checkbox" <?php if($emp->equipment_vans == 1){?>checked="checked"<?php }?> name="equipment_vans[]" value="1"/>
                    <?php
                }
             ?>
                                        &nbsp;<?= $strings2["verifs_vans"]; ?>&nbsp;
                                        <?php
                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view') {
                                                ifchar($emp->equipment_reefer, '&#9745;','&#9744;');
                                            } else {?>
                                                <input type="checkbox" <?php if($emp->equipment_reefer == 1){?>checked="checked"<?php }?> name="equipment_reefer[]" value="1"/>
                                                <?php
                                            }
                                         ?>
                                        &nbsp;<?= $strings2["verifs_reefers"]; ?>&nbsp;
                                        <?php
                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view') {
                                                ifchar($emp->equipment_decks, '&#9745;','&#9744;');
                                            } else {
                                                ?>
                                                <input type="checkbox" <?php if($emp->equipment_decks == 1){?>checked="checked"<?php }?> name="equipment_decks[]" value="1"/>
                                                <?php
                                            }
                                         ?>
                                        &nbsp;<?= $strings2["verifs_decks"]; ?>&nbsp;
                                        <?php
                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view') {
                                                ifchar($emp->equipment_super, '&#9745;','&#9744;');
                                            } else {
                                                ?>
                                                <input type="checkbox" <?php if($emp->equipment_super == 1){?>checked="checked"<?php }?> name="equipment_super[]" value="1"/>
                                                <?php
                                            }
                                        ?>
                                        &nbsp;<?= $strings2["verifs_superbs"]; ?>&nbsp;
                                        <?php
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view') {
                    ifchar($emp->equipment_straight_truck, '&#9745;','&#9744;');
                } else {
                    ?>
                    <input type="checkbox" <?php if($emp->equipment_straight_truck == 1){?>checked="checked"<?php }?> name="equipment_straight_truck[]" value="1"/>
                    <?php
                }
             ?>&nbsp;<?= $strings2["verifs_straighttr"]; ?>&nbsp;
                                        <?php
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view') {
                    ifchar($emp->equipment_others, '&#9745;','&#9744;');
                } else {
                    ?>
                    <input type="checkbox" <?php if($emp->equipment_others == 1){?>checked="checked"<?php }?> name="equipment_others[]" value="1"/>
                    <?php
                }
             ?>&nbsp;<?= $strings2["verifs_others"]; ?>:
                        </div>
                        </div>
                        <div class="form-group col-md-12">
                        <label class="control-label col-md-3"><?= $strings2["verifs_drivingexp"]; ?>: </label>
                        <div class="col-md-9">
                            <?php
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view') {
                    ifchar($emp->driving_experince_local, '&#9745;','&#9744;');
                } else {
                    ?>
                    <input type="checkbox" <?php if($emp->driving_experince_local == 1){?>checked="checked"<?php }?> name="driving_experince_local[]" value="1"/>
                    <?php
                }
             ?>
                            &nbsp;<?= $strings2["verifs_local"]; ?>&nbsp;
                            <?php
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view') {
                    ifchar($emp->driving_experince_canada, '&#9745;','&#9744;');
                } else {
                    ?>
                    <input type="checkbox" <?php if($emp->driving_experince_canada == 1){?>checked="checked"<?php }?> name="driving_experince_canada[]" value="1"/>
                    <?php
                }
             ?>&nbsp;<?= $strings2["verifs_canada"]; ?>&nbsp;
                            <?php
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view') {
                    ifchar($emp->driving_experince_canada_rocky_mountains, '&#9745;','&#9744;');
                } else {
                    ?>
                    <input type="checkbox" <?php if($emp->driving_experince_canada_rocky_mountains == 1){?>checked="checked"<?php }?> name="driving_experince_canada_rocky_mountains[]" value="1"/>
                    <?php
                }
             ?>
                            &nbsp;<?= $strings2["verifs_canadarock"]; ?>&nbsp;
                            <?php
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view') {
                    ifchar($emp->driving_experince_usa, '&#9745;','&#9744;');
                } else {
                    ?>
                    <input type="checkbox" <?php if($emp->driving_experince_usa == 1){?>checked="checked"<?php }?> name="driving_experince_usa[]" value="1"/>
                    <?php
                }
             ?>
                            &nbsp;<?= $strings2["verifs_usa"]; ?>&nbsp;
                        </div>

                        </div>
                        <div class="clearfix"></div>
                        <hr />



                        <?php
                        if($counter!=1) {
                            ?>
                                <div class="delete">
                                    <a href="javascript:void(0);" class="btn red" id="delete"><?= $strings["dashboard_delete"]; ?></a>
                                </div>
                            </div>

                            <?php

                        }

                    }
                    if($counter==1) {
                        echo '<div id="more_div" style="padding-left: 32px;"></div>';
                    } else {
                        if ($counter > 1) {
                            echo '</div>';
                        }
                    }
                } else {
                   ?>

                    <div class="form-group row">
                        <label class="control-label col-md-3 required"><?= $strings["forms_companyname"]; ?>:</label>
                        <div class=" col-md-9">
                            <input type="text" class="form-control required" required name="company_name[]" />
                        </div>
                    </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 required"><?= $strings["forms_address"]; ?>:</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control required" required name="address[]" />
                                    </div>
                                    <label class="control-label col-md-3 required"><?= $strings["forms_city"]; ?>:</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control required" required name="city[]" />
                                    </div>
                                </div>

                                <div class="form-group row">
                                <label class="control-label col-md-3 required"><?= $strings["forms_provincestate"]; ?>:</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control required" required name="state_province[]" />
                                </div>
                                <label class="control-label col-md-3 required"><?= $strings["forms_country"]; ?>:</label>
                                <div class="col-md-3">
                                <input type="text" class="form-control required" required name="country[]" />
                                </div>
                                </div>
                                <div class="form-group row">
                                <label class="control-label col-md-3 required"><?= $strings2["verifs_supername"]; ?>:</label>
                                <div class="col-md-3">
                                <input type="text" class="form-control required" required name="supervisor_name[]"/>
                                </div>
                               <label class="control-label col-md-3 required"><?= $strings["forms_phone"]; ?>:</label>
                               <div class="col-md-3">
                               <input type="text" class="form-control required" required role="phone" name="supervisor_phone[]"/>
                               </div>
                               </div>

                               <div class="form-group row">
                               <label class="control-label col-md-3"><?= $strings2["verifs_superemail"]; ?>:</label>
                               <div class="col-md-3">
                               <input type="text" class="form-control email1" role="email" name="supervisor_email[]"/>
                               </div>
                               <label class="control-label col-md-3"><?= $strings2["verifs_secondarye"]; ?>:</label>
                               <div class="col-md-3">
                               <input type="text" class="form-control email1" role="email" name="supervisor_secondary_email[]"/>
                               </div>
                               </div>

                               <div class="form-group row">
                                <label class="control-label col-md-3 required"><?= $strings2["verifs_employment"]; ?>:</label>
                                <div class="col-md-3">
                                <input type="text" class="form-control date-picker datepicker required" required name="employment_start_date[]"/>
                                </div>
                                <label class="control-label col-md-3 required"><?= $strings2["verifs_employment2"]; ?>:</label>
                                <div class="col-md-3">
                                <input type="text" class="form-control date-picker datepicker required" required name="employment_end_date[]"/>
                                </div>
                                </div>
                                <div class="form-group row">
                                <label class="control-label col-md-3"><?= $strings2["verifs_claimswith"]; ?>:</label>
                               <div class="col-md-3">
                                &nbsp;&nbsp;<input type="radio" name="claims_with_employer_<?php $rand = rand(10000,99999); echo $rand; ?>[]" value="1"/>&nbsp;&nbsp;<?= $strings["dashboard_affirmative"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="claims_with_employer_<?php echo $rand;?>[]"  value="0"/>&nbsp;&nbsp;&nbsp;&nbsp;<?= $strings["dashboard_negative"]; ?>
                                </div>
                                 <label class="control-label col-md-3"><?= $strings2["verifs_dateclaims"]; ?>:</label>
                                 <div class="col-md-3">
                                 <input type="text" class="form-control date-picker datepicker" name="claims_recovery_date[]"/>
                                 </div>
                                 </div>

                                 <div class="form-group row">
                                    <label class="control-label col-md-3"><?= $strings2["verifs_employment3"]; ?>:</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="emploment_history_confirm_verify_use[]"/>
                                    </div>

                                <label class="control-label col-md-3">US DOT MC/MX#:</label>
                                <div class="col-md-3">
                                <input name="us_dot[]" type="text" class="form-control" name="us_dot[]" />
                                </div>

                                <label class="control-label col-md-3" style="display: none;"><? $strings["forms_signature"] . ":"; ?></label>
                                <div class="col-md-3">
                                <input type="text" class="form-control" style="display: none;" name="signature[]"/>
                                </div>
                                </div>

                                <div class="form-group row">
                                <label class="control-label col-md-3"><?= $strings2["tasks_date"]; ?>:</label>
                                <div class="col-md-3">
                                <input type="text" class="form-control date-picker datepicker" name="signature_datetime[]"/>
                                </div>
                                </div>
                                <div class="form-group row">
                                            <label class="control-label col-md-3"><?= $strings2["verifs_equipmento"]; ?>: </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" name="equipment_vans[]" value="1"/>&nbsp;<?= $strings2["verifs_vans"]; ?>&nbsp;
                                                <input type="checkbox" name="equipment_reefer[]" value="1"/>&nbsp;<?= $strings2["verifs_reefers"]; ?>&nbsp;
                                                <input type="checkbox" name="equipment_decks[]" value="1"/>&nbsp;<?= $strings2["verifs_decks"]; ?>&nbsp;
                                                <input type="checkbox" name="equipment_super[]" value="1"/>&nbsp;<?= $strings2["verifs_superbs"]; ?>&nbsp;
                                                <input type="checkbox" name="equipment_straight_truck[]" value="1"/>&nbsp;<?= $strings2["verifs_straighttr"]; ?>&nbsp;
                                                <input type="checkbox" name="equipment_others[]" value="1"/>&nbsp;<?= $strings2["verifs_others"]; ?>:
                                </div>
                                </div>
                                <div class="form-group row">
                                <label class="control-label col-md-3"><?= $strings2["verifs_drivingexp"]; ?>: </label>
                                <div class="col-md-9">
                                    <input type="checkbox" name="driving_experince_local[]" value="1"/>&nbsp;<?= $strings2["verifs_local"]; ?>&nbsp;
                                    <input type="checkbox" name="driving_experince_canada[]" value="1"/>&nbsp;<?= $strings2["verifs_canada"]; ?>&nbsp;
                                    <input type="checkbox" name="driving_experince_canada_rocky_mountains[]" value="1"/>&nbsp;<?= $strings2["verifs_canadarock"]; ?>&nbsp;
                                    <input type="checkbox" name="driving_experince_usa[]" value="1"/>&nbsp;<?= $strings2["verifs_usa"]; ?>&nbsp;
                                </div>

                </div>

                    <div id="more_div" style="padding-left: 16px;"></div>
                   <?php
                }
                ?>

        <div id="add_more_div" class="no-print" style="padding-bottom:10px">
            <p>&nbsp;</p>
            <input type="hidden" name="count_past_emp" id="count_past_emp" value="<?php if(isset($sub3['emp'])){echo count($sub3['emp']);}else{?>1<?php }?>">
            <a href="javascript:void(0);" class="btn green no-print" id="add_more"><?= $strings["forms_addmore"]; ?></a>
        </div>
        <?php if($this->request->params['controller']!='Documents' && $this->request->params['controller']!='ClientApplication'){?>
        <div class="allattach" class="no-print">
         <?php

         if(!isset($sub3['att'])) {
             $sub3['att'] = array();
         }
                                                        if(!count($sub3['att'])) {?>
        <div class="form-group row no-print" style="display:block;margin-top:5px; margin-bottom: 5px;">
            <label class="control-label col-md-3"><?= $strings2["file_attachfile"]; ?>: </label>
            <div class="col-md-9">
            <input type="hidden" name="attach_doc[]" class="emp1" />
            <a href="javascript:void(0);" id="emp1" class="btn btn-primary"><?= $strings["forms_browse"]; ?></a> <span class="uploaded"></span>
            </div>
           </div>
           <?php }



           ?>
          <div class="form-group row no-print">
                <div id="more_employ_doc" data-emp="<?php if(count($sub3['att']))echo count($sub3['att']);else echo '1';?>">
                <?php
                        if(count($sub3['att'])){//THIS SHOULD BE USING FILELIST.PHP!!!!!{
                            $at=0;
                            foreach($sub3['att'] as $pa) {
                                if($pa->attachment){
                                $at++;
                                ?>
                                <div class="del_append_employ"><label class="control-label col-md-3"><?= $strings2["file_attachfile"]; ?>: </label><div class="col-md-6 pad_bot"><input type="hidden" class="emp<?php echo $at;?>" name="attach_doc[]" value="<?php echo $pa->attachment;?>" /><a href="#" id="emp<?php echo $at;?>" class="btn btn-primary"><?= $strings["forms_browse"]; ?></a> <?php if($at>1){?><a  href="javascript:void(0);" class="btn btn-danger" id="delete_employ_doc" onclick="$(this).parent().remove();"><?= $strings["dashboard_delete"]; ?></a><?php }?>
                                <span class="uploaded"><?php echo $pa->attachment;?>  <?php if($pa->attachment){$ext_arr = explode('.',$pa->attachment);$ext = end($ext_arr);$ext = strtolower($ext);if(in_array($ext,$img_ext)){?><img src="<?php echo $this->request->webroot;?>attachments/<?php echo $pa->attachment;?>" style="max-width:120px;" /><?php }elseif(in_array($ext,$doc_ext)){?><a class="dl" href="<?php echo $this->request->webroot;?>attachments/<?php echo $pa->attachment;?>"><?= $strings["file_download"]; ?></a><?php }else{?><br />
                             <video width="320" height="240" controls>
                              <source src="<?php echo $this->request->webroot;?>attachments/<?php echo $pa->attachment;?>" type="video/mp4">
                              <source src="<?php echo $this->request->webroot;?>attachments/<?php echo str_replace('.mp4','.ogg',$pa->attachment);?>" type="video/ogg">
                                 <?= $strings["forms_novideo"]; ?>
                            </video>
                            <?php } }?></span>
                                </div></div><div class="clearfix"></div>
                                <script>
                                $(function(){
                                    fileUpload('emp<?php echo $at;?>');
                                });
                                </script>
                                <?php
                            }}
                        }
                    ?>
                </div>
          </div>

          <div class="form-group row no-print">
            <div class="col-md-3">
            </div>
            <div class="col-md-9">
                <a href="javascript:void(0);" class="btn btn-success moremore no-print" id="add_more_employ_doc"><?= $strings["forms_addmore"]; ?></a>
            </div>
          </div>
          <div class="clearfix"></div>
          </div>
          <?php }?>

</form>
<script>
    <?php loadstringsJS(array_merge($strings, $strings2)); ?>
    $(function(){
        <?php
            if(!isset($sub3['att'])){
                $sub3['att'] = array();
            }
            if(($this->request->params['action']=='addorder' || $this->request->params['action']=='add') && !count($sub3['att'])) {
                echo "fileUpload('emp1');";
            }
        ?>

      $("#add_more").click(function(){
        <?php if($this->request->params['controller']=='ClientApplication'){?>
            language = 'English';
        <?php }?>
            $('.overlay-wrapper').show();
            $.ajax({
                   url:"<?php echo $this->request->webroot;?>subpages/documents/past_employer.php?language=" + language,
                   success:function(res){
                    $("#more_div").append(res);
                     <?php if($this->request->params['controller']=='ClientApplication'){?>
                        $('#more_div').find('.toremove').addClass('row');
                        $('#more_div').find('.delete').css({'padding-left':'30px'});
                     <?php }?>
                    var c = $('#count_past_emp').val();
                    var counter = parseInt(c)+1;
                    $('#count_past_emp').attr('value',counter);
                    $('.date-picker').datepicker({
                            rtl: Metronic.isRTL(),
                            orientation: "left",
                            autoclose: true,
                            format: 'yyyy-mm-dd'
                        });
                        $('.overlay-wrapper').hide();
                   }
            });
      });
      $("#delete").live("click",function(){
            $('.overlay-wrapper').show();
            $(this).parent().parent().remove();
            var c = $('#count_past_emp').val();
            var counter = parseInt(c)-1;
            $('#count_past_emp').attr('value',counter);
            $('.overlay-wrapper').hide();
      });


      $('#add_more_employ_doc').click(function(){
            var count = $('#more_employ_doc').data('emp');
            $('#more_employ_doc').data('emp',parseInt(count)+1);
            $('#more_employ_doc').append('<div class="del_append_employ"><label class="control-label col-md-3"></label><div class="col-md-6 pad_bot"><input type="hidden" name="attach_doc[]" class="emp'+$('#more_employ_doc').data('emp')+'" /><a href="javascript:void(0);" id="emp'+$('#more_employ_doc').data('emp')+'" class="btn btn-primary"><?= addslashes($strings["forms_browse"]); ?></a> <a  href="javascript:void(0);" class="btn btn-danger" id="delete_employ_doc"><?= $strings["dashboard_delete"]; ?></a> <span class="uploaded"></span></div></div><div class="clearfix"></div>');
            fileUpload('emp'+$('#more_employ_doc').data('emp'));
      });

      $('#delete_employ_doc').live('click',function(){
            var count = $('#more_employ_doc').data('emp');
            $('#more_employ_doc').data('emp',parseInt(count)-1);
            $(this).closest('.del_append_employ').remove();
      });
 });
</script>
</div></div></div></div>