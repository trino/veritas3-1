<?php
if($this->request->params['controller']!='ClientApplication'){
 if($this->request->session()->read('debug')){  echo "<span style ='color:red;'>subpages/documents/past_employer_survey.php #INC204</span>";}
 }
 ?>
 <?php if(isset($dx)){?><p>Document - <?php echo $dx->title;?></p><?php }?>
<form id="form_tab<?php echo $dx->id;?>" action="<?php echo $this->request->webroot;?>documents/basic_mee_platform/<?php echo $cid .'/' .$did;?>" method="post">
        <input type="hidden" class="document_type" name="document_type" value="<?php echo $dx->title;?>"/>
        <input type="hidden" name="sub_doc_id" value="<?php echo $dx->id;?>" class="sub_docs_id" id="af" />
        <div class="clearfix"></div>
        <hr/>


    <?php if(false){?>

        <div class="col-md-12">
        <h3>Introduction</h3>
        </div>
        <div class="col-md-12">
                    <label class="control-label col-md-6">How did you hear about the opportunity?</label>  
                    <div class="col-md-6">              
                        <textarea name="hear_opportunity" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->hear_opportunity;?></textarea>
                    </div>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12">
        <h3>Basic Requirement</h3>
        </div>
        <div class="col-md-12">
                <label class="control-label col-md-6">Are you legally eligible to work in Canada? </label>  
                <div class="col-md-6 radio-list">
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->legally_eligible=='1')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input type="radio" class="form-control" name="legally_eligible" value="1" <?php if(isset($basic_mee_platform) && $basic_mee_platform->legally_eligible=='1')echo "checked='checked'";?> /> 
                            <?php
                        }
                         ?>              
                             <span>Yes</span>
                    </label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->legally_eligible=='0')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input type="radio" class="form-control" name="legally_eligible" value="0" <?php if(isset($basic_mee_platform) && $basic_mee_platform->legally_eligible=='0')echo "checked='checked'";?>/> 
                            <?php
                        }
                         ?>
                             <span>No</span>
                    </label>
                </div>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12">
                <label class="control-label col-md-6">Do you have canadian passport? </label>  
                <div class="col-md-6 radio-list">
                    <label class="radio-inline">
                          <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->canadian_passport=='1')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input onclick="$('#us_visa').hide();" type="radio" class="form-control" name="canadian_passport" value="1" <?php if(isset($basic_mee_platform) && $basic_mee_platform->canadian_passport=='1')echo "checked='checked'";?> /> 
                            <?php
                        }
                         ?>            
                             <span>Yes</span>
                    </label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->canadian_passport=='0')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input onclick="$('#us_visa').show();" type="radio" class="form-control" name="canadian_passport" value="0" <?php if(isset($basic_mee_platform) && $basic_mee_platform->canadian_passport=='0')echo "checked='checked'";?>/> 
                            <?php
                        }
                         ?>   
                             <span>No</span>
                    </label>
                </div>
        </div> 
        
        <p>&nbsp;</p>
        <div class="col-md-12" id="us_visa" style="<?php if(!isset($basic_mee_platform) || (isset($basic_mee_platform)&& !$basic_mee_platform->us_visa)){?>display: none;<?php }?>">
                <label class="control-label col-md-6">Do you have a Permanent Residency card and US Visa? </label>  
                <div class="col-md-6 radio-list">
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->us_visa=='1')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input type="radio" class="form-control" name="us_visa" value="1" <?php if(isset($basic_mee_platform) && $basic_mee_platform->us_visa=='1')echo "checked='checked'";?> /> 
                            <?php
                        }
                         ?>               
                             <span>Yes</span>
                    </label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->us_visa=='0')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input type="radio" class="form-control" name="us_visa" value="0" <?php if(isset($basic_mee_platform) && $basic_mee_platform->us_visa=='0')echo "checked='checked'";?>/> 
                            <?php
                        }
                         ?> 
                             <span>No</span>
                    </label>
                </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                <label class="control-label col-md-6">Have you ever been convicted of a criminal offence for which a pardon has not been granted or, which could cause you to not cross the border? </label>  
                <div class="col-md-6 radio-list">
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->no_cross_border=='1')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input type="radio" class="form-control" name="no_cross_border" value="1" <?php if(isset($basic_mee_platform) && $basic_mee_platform->no_cross_border=='1')echo "checked='checked'";?> /> 
                            <?php
                        }
                         ?>               
                             <span>Yes</span>
                    </label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->no_cross_border=='0')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input type="radio" class="form-control" name="no_cross_border" value="0" <?php if(isset($basic_mee_platform) && $basic_mee_platform->no_cross_border=='0')echo "checked='checked'";?>/> 
                            <?php
                        }
                         ?> 
                             <span>No</span>
                    </label>
                </div>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">How do you feel about running team? Do you have a partner?</label>  
                    <div class="col-md-6">              
                        <textarea name="running_team" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->running_team;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p> 
        <div class="col-md-12">
        <h3>Discovery</h3>
        </div>
        <div class="col-md-12">
                    <label class="control-label col-md-6">When did you get your AZ License and have you been commercially driving consistently since you got your license?</label>  
                    <div class="col-md-6">              
                        <input type="text" name="az_license_date" class="date-picker form-control" />
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Are you currently driving for another carrier? If yes, for whom and for how long?</label>  
                    <div class="col-md-6">              
                        <textarea name="another_carrier" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->another_carrier;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p> 
        
        
        <div class="col-md-12">
        <h3>Tell me about the work you have been doing?</h3>
        </div>
        
        <div class="col-md-12">
                    <label class="control-label col-md-6">Miles</label>  
                    <div class="col-md-6">              
                        <textarea name="miles" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->miles;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p> 
        <div class="col-md-12">
                    <label class="control-label col-md-6">Time out/home</label>  
                    <div class="col-md-6">              
                        <textarea name="time_out_home" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->time_out_home;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Locations</label>  
                    <div class="col-md-6">              
                        <textarea name="locations" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->locations;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p> 
        <div class="col-md-12">
                    <label class="control-label col-md-6">Border Cross</label>  
                    <div class="col-md-6">              
                        <textarea name="border_cross" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->border_cross;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p> 
        <div class="col-md-12">
                    <label class="control-label col-md-6">What type of equipment did you use?</label>  
                    <div class="col-md-6">              
                        <textarea name="equipment_type" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->equipment_type;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p> 
        <div class="col-md-12">
                    <label class="control-label col-md-6">Was your equipment standard or automatic?</label>  
                    <div class="col-md-6">              
                        <textarea name="equipment_standard" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->equipment_standard;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>  
        <div class="col-md-12">
                <label class="control-label col-md-6">Reefer Y or No How many loads? </label>  
                <div class="col-md-6 radio-list">
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->reefer_y_n=='1')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input type="radio" class="form-control" name="reefer_y_n" value="1" <?php if(isset($basic_mee_platform) && $basic_mee_platform->reefer_y_n=='1')echo "checked='checked'";?> /> 
                            <?php
                        }
                         ?>               
                             <span>Yes</span>
                    </label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->reefer_y_n=='0')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input type="radio" class="form-control" name="reefer_y_n" value="0" <?php if(isset($basic_mee_platform) && $basic_mee_platform->reefer_y_n=='0')echo "checked='checked'";?>/> 
                            <?php
                        }
                         ?> 
                             <span>No</span>
                    </label>
                </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">As part of our screening process we check precious employment references. Have you had any incidents that may not be on your CVOR, but that your previous employer has recorded?</label>  
                    <div class="col-md-6">              
                        <textarea name="incidents_cvor" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->incidents_cvor;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Driving at night is a requirement for the job. Do you see any reason why you would not be able to drive at night?</label>  
                    <div class="col-md-6">              
                        <textarea name="reason_drive_night" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->reason_drive_night;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
        <h3>Expectations</h3>
        </div>
        
        <div class="col-md-12">
                    <label class="control-label col-md-6">How many Miles are you hoping to run in a week?</label>  
                    <div class="col-md-6">              
                        <textarea name="miles_a_week" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->miles_a_week;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p> 
        <div class="col-md-12">
                    <label class="control-label col-md-6">What is your current salary? Gross or net?</label>  
                    <div class="col-md-6">              
                        <textarea name="current_gross_net" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->current_gross_net;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p> 
        <div class="col-md-12">
                    <label class="control-label col-md-6">What are you looking for in your next employer?</label>  
                    <div class="col-md-6">              
                        <textarea name="looking_next_employer" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->looking_next_employer;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p> 
        
        
        <div class="col-md-12">
        <h3>Closing</h3>
        </div>
        
        <div class="col-md-12">
                    <label class="control-label col-md-6">How soon could you start working?</label>  
                    <div class="col-md-6">              
                        <textarea name="how_soon" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->how_soon;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        
        <div class="col-md-12">
        <h3>Questions for Trainees</h3>
        </div>
        
        <div class="col-md-12">
                    <label class="control-label col-md-6">What school did you attend?</label>  
                    <div class="col-md-6">              
                        <textarea name="what_school" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->what_school;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">How many hours was your program?</label>  
                    <div class="col-md-6">              
                        <textarea name="program_hours" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->program_hours;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">When did you take your MTO or equivalent road test?</label>  
                    <div class="col-md-6">              
                        <textarea name="mto_test" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->mto_test;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Did you get your license on the 1st try? If no, how many attempts?</label>  
                    <div class="col-md-6">              
                        <textarea name="first_try" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->first_try;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        
        
        
        
        <div class="col-md-12">
        <h3>Have you driven since getting your AZ? If so, please provide details:</h3>
        </div>
        
        <div class="col-md-12">
                    <label class="control-label col-md-6">Miles:</label>  
                    <div class="col-md-6">              
                        <textarea name="miles_az" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->miles_az;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Time out/home:</label>  
                    <div class="col-md-6">              
                        <textarea name="time_out_home_az" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->time_out_home_az;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Locations:</label>  
                    <div class="col-md-6">              
                        <textarea name="locations_az" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->locations_az;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Border Cross:</label>  
                    <div class="col-md-6">              
                        <textarea name="border_cross_az" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->border_cross_az;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Type of equipment</label>  
                    <div class="col-md-6">              
                        <textarea name="equipment_type_az" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->equipment_type_az;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Comments</label>  
                    <div class="col-md-6">              
                        <textarea name="comments_az" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->comments_az;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        
        <?}?>

        <div class="col-md-12">
        <h3>Basic Driver Application</h3>
        </div>
        
        <div class="col-md-12">
                    <label class="control-label col-md-6">Address for past 3 years:</label>  
                    <div class="col-md-6">              
                        <textarea name="address_past_three" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->address_past_three;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Secondary Address if have not lived at current location for more than 2 years</label>  
                    <div class="col-md-6">              
                        <textarea name="secondary_address" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->secondary_address;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Incase of Emergency</label>  
                    <div class="col-md-6"> 
                        <input type="text" class="form-control" name="emergency_name" value="<?php if(isset($basic_mee_platform))echo $basic_mee_platform->emergency_name;?>" placeholder="Name" style="margin-bottom: 5px;" />
                        <input type="text" class="form-control" name="emergency_relation" value="<?php if(isset($basic_mee_platform))echo $basic_mee_platform->emergency_relation;?>" placeholder="Relationship" style="margin-bottom: 5px;" />             
                        <input type="text" class="form-control" role="phone" name="emergency_phone" value="<?php if(isset($basic_mee_platform))echo $basic_mee_platform->emergency_phone;?>" placeholder="Phone" style="margin-bottom: 5px;" />
                        
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                <label class="control-label col-md-6">Have you worked for this company before? </label>  
                <div class="col-md-6 radio-list">
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->any_company_before=='1')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input onclick="$('#company_before').show();" type="radio" class="form-control" name="any_company_before" value="1" <?php if(isset($basic_mee_platform) && $basic_mee_platform->any_company_before=='1')echo "checked='checked'";?> /> 
                            <?php
                        }
                         ?>               
                             <span>Yes</span>
                    </label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->any_company_before=='0')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input onclick="$('#company_before').hide();" type="radio" class="form-control" name="any_company_before" value="0" <?php if(isset($basic_mee_platform) && $basic_mee_platform->any_company_before=='0')echo "checked='checked'";?>/> 
                            <?php
                        }
                         ?> 
                             <span>No</span>
                    </label>
                </div>
        </div>
        
        <p>&nbsp;</p>
        
        <div id="company_before" style="<?php if(!isset($basic_mee_platform) || (isset($basic_mee_platform) && $basic_mee_platform->any_company_before=='0')){?>display: none;<?php }?>">
            <div class="col-md-12">
                        <label class="control-label col-md-6">Dates for the above?</label>  
                        <div class="col-md-6">              
                            <input type="text" name="date_above" class="form-control date-picker" value="<?php if(isset($basic_mee_platform))echo $basic_mee_platform->date_above;?>" />
                        </div>
            </div>
            
            <p>&nbsp;</p>
            <div class="col-md-12">
                        <label class="control-label col-md-6">Where?</label>  
                        <div class="col-md-6">              
                            <textarea name="where_worked" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->where_worked;?></textarea>
                        </div>
            </div>
            
            <p>&nbsp;</p>
            <div class="col-md-12">
                        <label class="control-label col-md-6">Position?</label>  
                        <div class="col-md-6">              
                            <textarea name="position_worked" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->position_worked;?></textarea>
                        </div>
            </div>
            
            <p>&nbsp;</p>
            <div class="col-md-12">
                        <label class="control-label col-md-6">Reason for leaving?</label>  
                        <div class="col-md-6">              
                            <textarea name="leaving_reason" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->leaving_reason;?></textarea>
                        </div>
            </div>
            
            <p>&nbsp;</p>
            
        </div>
        <div class="col-md-12">
                        <label class="control-label col-md-6">Who referred you?</label>  
                        <div class="col-md-6">              
                            <textarea name="who_referred_you" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->who_referred_you;?></textarea>
                        </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Rate of pay expected?</label>  
                    <div class="col-md-6">              
                        <textarea name="pay_rate_expected" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->pay_rate_expected;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Date of application</label>  
                    <div class="col-md-6">              
                        <input type="text" name="date_of_application2" class="form-control date-picker" value="<?php if(isset($basic_mee_platform))echo $basic_mee_platform->date_of_application2;?>" />
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Position(s) applied for</label>  
                    <div class="col-md-6">              
                        <input type="text" name="position_s_applied_for" class="form-control" value="<?php if(isset($basic_mee_platform))echo $basic_mee_platform->position_s_applied_for;?>" />
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
            <label class="control-label col-md-6">Are you 21 years of age or more? </label>  
            <div class="col-md-6 radio-list">
                <label class="radio-inline">
                    <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->are_you_21=='1')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input type="radio" class="form-control" name="are_you_21" value="1" <?php if(isset($basic_mee_platform) && $basic_mee_platform->are_you_21=='1')echo "checked='checked'";?> /> 
                            <?php
                        }
                         ?>                
                         <span>Yes</span>
                </label>
                <label class="radio-inline">
                    <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->are_you_21=='0')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input type="radio" class="form-control" name="are_you_21" value="0" <?php if(isset($basic_mee_platform) && $basic_mee_platform->are_you_21=='0')echo "checked='checked'";?>/> 
                            <?php
                        }
                         ?> 
                         <span>No</span>
                </label>
            </div>
        </div>
    
        <p>&nbsp;</p>
        <div class="col-md-12">
            <label class="control-label col-md-6">Can you provide proof of age? </label>  
            <div class="col-md-6 radio-list">
                <label class="radio-inline">
                    <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->proof_of_age=='1')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input type="radio" class="form-control" name="proof_of_age" value="1" <?php if(isset($basic_mee_platform) && $basic_mee_platform->proof_of_age=='1')echo "checked='checked'";?> /> 
                            <?php
                        }
                         ?>               
                         <span>Yes</span>
                </label>
                <label class="radio-inline">
                    <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($basic_mee_platform) && $basic_mee_platform->proof_of_age=='0')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input type="radio" class="form-control" name="proof_of_age" value="0" <?php if(isset($basic_mee_platform) && $basic_mee_platform->proof_of_age=='0')echo "checked='checked'";?>/> 
                            <?php
                        }
                         ?> 
                         <span>No</span>
                </label>
            </div>
        </div>
    
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Have you ever been denied entry into the US?</label>  
                    <div class="col-md-6">              
                        <textarea name="denied_us_entry" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->denied_us_entry;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Have you ever tested positive for a controlled substance?</label>  
                    <div class="col-md-6">              
                        <textarea name="controlled_substance" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->controlled_substance;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Had a breath alcohol test greater than 0.04?</label>  
                    <div class="col-md-6">              
                        <textarea name="breath_alcohol" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->breath_alcohol;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Do you have a FAST card?</label>  
                    <div class="col-md-6"> 
                        <input type="text" class="form-control" name="fast_number" value="<?php if(isset($basic_mee_platform))echo $basic_mee_platform->fast_number;?>" placeholder="Number" style="margin-bottom: 5px;" />
                        <input type="text" class="form-control date-picker" name="fast_expiry" value="<?php if(isset($basic_mee_platform))echo $basic_mee_platform->expiry_date;?>" placeholder="Expiry Date" style="margin-bottom: 5px;" />             
                        
                        
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Are there any reasons you may not be able to perform the functions of the position for which you have applied?</label>  
                    <div class="col-md-6">              
                        <textarea name="reason_not_able" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->reason_not_able;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Are you physically capable of heavy manual work?</label>  
                    <div class="col-md-6">              
                        <textarea name="capable_heavy_work" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->capable_heavy_work;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Have you ever been injured while on the job?</label>  
                    <div class="col-md-6"> 
                        
                        <label class="control-label">Nature and degree?</label>  
                        <div class="">              
                            <textarea name="nature_of_degree" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->nature_of_degree;?></textarea>
                        </div>
                        <p>&nbsp;</p>
                        <label class="control-label">How much time was lost from illness in the past 3 years?</label>  
                        <div class="">              
                            <textarea name="how_much_time_lost" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->how_much_time_lost;?></textarea>
                        </div>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Would you be willing to take a physical examination?</label>  
                    <div class="col-md-6">              
                        <textarea name="willing_physical_exam" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->willing_physical_exam;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Have you ever been denied a license, permit or privilege to operate a motor vehicle?</label>  
                    <div class="col-md-6">              
                        <textarea name="ever_denied_license" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->ever_denied_license;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">Has any license, permit or privilege ever been suspended or revoked?</label>  
                    <div class="col-md-6">              
                        <textarea name="license_suspend" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->license_suspend;?></textarea>
                    </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
                    <label class="control-label col-md-6">List states operated for last 5 years</label>  
                    <div class="col-md-6">              
                        <textarea name="states_operated" class="form-control"><?php if(isset($basic_mee_platform))echo $basic_mee_platform->states_operated;?></textarea>
                    </div>
        </div>



    <p>&nbsp;</p>



        <?php if($this->request->params['controller']!='Documents' && $this->request->params['controller']!='ClientApplication'){?>
        <div class="addattachment<?php echo $dx->id;?> form-group col-md-12"></div> 
        <?php }?>
        <div class="clearfix"></div>
        
        
    <p>&nbsp;</p>

</form>
<script>
 $(function(){
    <?php
        if(isset($disabled))
        {
    ?>
           $('#form_tab16 input').attr('disabled','disabled');
           $('#form_tab16 textarea').attr('disabled','disabled');             
    <?php }
    ?>

 })
 
 </script>
