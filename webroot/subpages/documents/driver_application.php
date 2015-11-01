<?php
if($this->request->params['controller']!='ClientApplication'){
 if($this->request->session()->read('debug'))
        echo "<span style ='color:red;'>subpages/documents/driver_application.php #INC140</span>";
        }
 ?>
<form id="form_tab2">
<input type="hidden" class="document_type" name="document_type" value="<?php echo $dx->title;?>" id="af" />
<input type="hidden" name="sub_doc_id" value="2" class="sub_docs_id" id="af" />
<div class="clearfix"></div>
<hr />

<?php
    $controller = $this->request->params['controller'];
    $controller = strtolower($controller);
    if($controller == 'documents' )
    {
        
        $colr = $this->requestAction('/documents/getColorId/2');
                            if(!$colr)
                            $colr = $class[1];
                            
                             echo '<div class="row">
                            <div class="col-md-12">
                            <div class="portlet box '.$colr.'">
        
                <div class="portlet-title">
                    <div class="caption">
                        Driver Application
                    </div>
                </div>
                <div class="portlet-body form">
                <div class="form-body" style="padding-bottom: 0px;">
                                <div class="tab-content">';
                                }
                    else {
                        
                    }

if($this->request->params['controller']!='ClientApplication'){
include_once 'subpages/filelist.php';
if( isset($sub['da_at'])){ listfiles($sub['da_at'], "attachments/", "", false,3); }}
    ?><P>
    <div class="form-group row">
        <div class="col-md-12">
<p>Welcome Prospective Drivers of Challenger. </p>
<p>Thank you for your interest in Challenger Motor Freight. In order to process your application in a timely manner, please complete all information requested including dates and contact information for your employment history. Please provide the following additional information.</p>



			<h4 class="">Driver Application for Employment</h4>
		</div>
			<div class="col-md-12"><!--form-body-->
            <p>(Answer all questions)</p>
            <p>In compliance with Federal and Provincial equal employment opportunity laws, qualified applicants are considered for all positions without regard to race, colour, religion, sex, national origin, age, marital status, or the presence of a non-job related medical condition or handicap.</p>
            <div class="form-group row">
				<label class="control-label col-md-3">Name: </label>
				<div class="col-md-3">
					<input type="text" class="form-control" placeholder="Last" name="last_name"/>
				</div>
                <div class="col-md-3">
					<input type="text" class="form-control" placeholder="First" name="first_name"/>
				</div>
                <div class="col-md-3">
					<input type="text" class="form-control" placeholder="Middle" name="last_name"/>
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3">Social Insurance No.: </label>
				<div class="col-md-9">
					<input type="text" role="sin" class="form-control" name="social_insurance_number"/>
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3">Address: </label>
				<div class="col-md-3">
					<input type="text" class="form-control" placeholder="Street" name="street_address"/>
				</div>
                <div class="col-md-2">
					<input type="text" class="form-control" placeholder="City" name="city"/>
				</div>
                <div class="col-md-2">
					<!--<input type="text" class="form-control" placeholder="Province" name="state_province"/>-->
                    <?php provinces("state_province") ?>
				</div>
                <div class="col-md-2">
					<input type="text" class="form-control" role="postalcode" placeholder="Postal Code" name="postal_code"/>
				</div>
			</div>

            <div class="form-group row">
                <label class="control-label col-md-4">Addresses for past 3 years: </label>

            </div>

            <div class="form-group row">
                <div class="col-md-3">
    					<input type="text" class="form-control" placeholder="City" name="past3_city1"/>
    			</div>

                <div class="col-md-3">
					<!--<input type="text" class="form-control" placeholder="Province" name="past3_state_provinve1"/>-->
                    <?php provinces("past3_state_provinve1") ?>
				</div>
                <div class="col-md-3">
					<input type="text" class="form-control" role="postalcode" placeholder="Postal Code" name="past3_postal_code1"/>
				</div>
                <div class="col-md-3">
					<input type="text" class="form-control" placeholder="Duration" name="past3_duration1"/>
				</div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
    					<input type="text" class="form-control" placeholder="City" name="past3_city2"/>
    			</div>

                <div class="col-md-3">
					<!--<input type="text" class="form-control" placeholder="Province" name="past3_state_province2"/>-->
                    <?php provinces("past3_state_province2") ?>
				</div>
                <div class="col-md-3">
					<input type="text" class="form-control" role="postalcode" placeholder="Postal Code" name="past3_postal_code2"/>
				</div>
                <div class="col-md-3">
					<input type="text" class="form-control" placeholder="Duration" name="past3_duration2"/>
				</div>
            </div>

                <div class="form-group row">
                    <label class="control-label col-md-3">Contact information:</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" role="phone" placeholder="Phone number" name="phone"/>
                    </div>

                    <div class="col-md-3">
                        <input type="text" class="form-control" role="phone" placeholder="Cell Phone" name="mobile"/>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control email1" role="email" placeholder="Email Address" name="email"/>
                    </div>
                </div>



            <div class="form-group row">
                <label class="control-label col-md-3">In case of emergency notify: </label>
				<div class="col-md-3">
					<input type="text" class="form-control" placeholder="Name" name="emergency_notify_name"/>
				</div>

                <div class="col-md-3">
					<input type="text" class="form-control" placeholder="Relationship" name="emergency_notify_relation"/>
				</div>
                <div class="col-md-3">
					<input type="text" class="form-control" role="phone" placeholder="Phone" name="emergency_notify_phone"/>
				</div>
            </div>
            <div class="clearfix"></div>
            <hr/>


            <div class="form-group row">
                <label class="control-label col-md-9">Have you worked for this company before?: </label>
				<div class="col-md-3 radio-list" align="center">
					<label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->worked_for_client == '1' )
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
                            <input type="radio" id="worked_for_client_1" name="worked_for_client" value="1" /> 
                            <?php
                        }
                         ?>
                         Yes
                    </label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->worked_for_client == '0' )
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
                            <input type="radio" id="worked_for_client_0" name="worked_for_client" value="0"/> 
                            <?php
                        }
                         ?>
                         No
                    </label>
                </div>
                </div>
                <div class="form-group row">
                <label class="control-label col-md-3" align="left">Dates: </label>

                <div class="col-md-3">
                    <input type="text" placeholder="From" class="form-control date-picker" name="worked_start_date"/>
                </div><label class="control-label col-md-3"> </label>
                <div class="col-md-3">
                    <input type="text" placeholder="To" class="form-control date-picker" name="worked_end_date"/>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3">Where?: </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="worked_where"/>
                </div>
                <label class="control-label col-md-3">Position: </label>
				<div class="col-md-3">
					<input type="text" class="form-control" name="worked_position"/>
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3">Reason for leaving: </label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="reason_to_leave"/>
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3">Are you now employed?: </label>
				<div class="col-md-3 radio-list">
					<label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->is_employed == '1' )
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
                            <input type="radio" id="is_employed_1" name="is_employed" value="1"/> 
                            <?php
                        }
                         ?>
                        Yes
                    </label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->is_employed == '0' )
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
                            <input type="radio" id="is_employed_0" name="is_employed" value="0" /> 
                            <?php
                        }
                         ?>
                        No
                    </label>
				</div>
                <label class="control-label col-md-3">If not, how long since leaving last employment?: </label>
				<div class="col-md-3">
					<input type="text" class="form-control" name="unemployed_total_time"/>
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3">Who referred you?: </label>
				<div class="col-md-3">
					<input type="text" class="form-control" name="referrer_name"/>
				</div>
                <label class="control-label col-md-3">Rate of pay expected: </label>
				<div class="col-md-3">
					<input type="text" class="form-control" name="rate_of_pay_excepted"/>
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3">Date of Application: </label>
				<div class="col-md-3">
					<input type="text" class="form-control date-picker" name="date_of_application"/>
				</div>
                <label class="control-label col-md-3">Position(s) Applied for: </label>
				<div class="col-md-3">
					<input type="text" class="form-control" name="position_apply_for"/>
				</div>
            </div>

            <div class="clearfix"></div>
            <hr />

            <div class="form-group row">
                <label class="control-label col-md-6">Are you 21 years of age or more? </label>
				<div class="col-md-6 radio-list">
					<label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->age_21 == '1' )
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
                            <input type="radio" id="age_21_1" name="age_21" value="1" /> 
                            <?php
                        }
                         ?>
                         Yes
                    </label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->age_21 == '0' )
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
                            <input type="radio" id="age_21_0" name="age_21" value="0"  /> 
                            <?php
                        }
                         ?>
                         No
                    </label>
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-6">Can you provide proof of age?  </label>
				<div class="col-md-6 radio-list">
					<label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->proof_of_age == '1' )
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
                            <input type="radio" id="proof_of_age_1" name="proof_of_age" value="1" /> 
                            <?php
                        }
                         ?>
                         Yes
                    </label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->proof_of_age == '0' )
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
                            <input type="radio" id="proof_of_age_0" name="proof_of_age" value="0"  /> 
                            <?php
                        }
                         ?>
                         No
                    </label> (Required for Truck Drivers)
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-6">Have you ever been convicted of a criminal offence for which a pardon has not been granted?  </label>
                <div class="col-md-6 radio-list">
					<label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->convicted_criminal == '1' )
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
                            <input type="radio" id="convicted_criminal_1" name="convicted_criminal" value="1" /> 
                            <?php
                        }
                         ?>
                         Yes
                    </label>
                   <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->convicted_criminal == '0' )
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
                            <input type="radio" id="convicted_criminal_0" name="convicted_criminal" value="0" /> 
                            <?php
                        }
                         ?>
                         No
                    </label>
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-6">Have you ever been denied entry into the U.S? </label>
                <div class="col-md-6 radio-list">
					<label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->denied_entry_us == '1' )
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
                            <input type="radio" id="denied_entry_us_1" name="denied_entry_us" value="1" /> 
                            <?php
                        }
                         ?>
                         Yes
                    </label>
                   <label class="radio-inline"> 
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->denied_entry_us == '0' )
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
                            <input type="radio" id="denied_entry_us_0" name="denied_entry_us" value="0"  /> 
                            <?php
                        }
                         ?>
                         No
                   </label>
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-6">Have you ever tested positive for a controlled substance?  </label>
                <div class="col-md-6 radio-list">
					<label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->positive_controlled_substance == '1' )
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
                            <input type="radio" id="positive_controlled_substance_1" name="positive_controlled_substance" value="1" /> 
                            <?php
                        }
                         ?>
                         Yes
                    </label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->positive_controlled_substance == '0' )
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
                            <input type="radio" id="positive_controlled_substance_0" name="positive_controlled_substance" value="0"/> 
                            <?php
                        }
                         ?>
                         No
                    </label>
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-6">Have you ever refused a drug test? </label>
                <div class="col-md-6 radio-list">
					<label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->refuse_drug_test == '1' )
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
                            <input type="radio" id="refuse_drug_test_1" name="refuse_drug_test" value="1"/> 
                            <?php
                        }
                         ?>
                         Yes
                    </label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->refuse_drug_test == '0' )
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
                            <input type="radio" id="refuse_drug_test_0" name="refuse_drug_test" value="0" /> 
                            <?php
                        }
                         ?>
                         No
                    </label>
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-6">Had a breath alcohol test greater than 0.04? </label>
                <div class="col-md-6 radio-list">
					<label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->breath_alcohol == '1' )
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
                            <input type="radio" id="breath_alcohol_1" name="breath_alcohol" value="1" /> 
                            <?php
                        }
                         ?>
                         Yes
                    </label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->breath_alcohol == '0' )
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
                            <input type="radio" id="breath_alcohol_0" name="breath_alcohol" value="0" /> 
                            <?php
                        }
                         ?>
                         No
                    </label>
                    (For a company to which you applied but did not work for)
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-6">Do you have a FAST card? </label>
                <div class="col-md-6 radio-list">
					<label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->fast_card == '1' )
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
                            <input type="radio" id="fast_card_1" name="fast_card" value="1" /> 
                            <?php
                        }
                         ?>
                         Yes
                    </label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->fast_card == '0' )
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
                            <input type="radio" id="fast_card_0" name="fast_card" value="0" /> 
                            <?php
                        }
                         ?>
                         No
                    </label>
				</div>
                </div>
                <div class="form-group row">
                <label class="control-label col-md-3">Card Number:</label>
                <div class="col-md-3">
					<input type="text" class="form-control" name="card_number"/>
                </div>

                <label class="control-label col-md-3">Expiry Date:</label>
                <div class="col-md-3">
					<input type="text" class="form-control date-picker" name="card_expiry_date"/>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr />

            <div class="form-group row">
                <label class="control-label col-md-6">Are there any reasons you may not be able to perform the functions of the position for which you have applied? </label>
				<div class="col-md-6 radio-list">
					<label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->not_able_perform_function_position == '1' )
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
                            <input type="radio" id="not_able_perform_function_position_1" name="not_able_perform_function_position" value="1"/> 
                            <?php
                        }
                         ?>
                         Yes</label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->not_able_perform_function_position == '0' )
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
                            <input type="radio" id="not_able_perform_function_position_0" name="not_able_perform_function_position" value="0"/> 
                            <?php
                        }
                         ?>
                         No
                    </label>
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-6">If yes, please provide details: </label>
                <div class="col-md-6">
                <textarea class="form-control" name="reason_not_perform_function_of_position"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-md-6">Are you physically capable of heavy manual work?</label>
				<div class="col-md-6 radio-list">
					<label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->physical_capable_heavy_manual_work == '1' )
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
                            <input type="radio" id="physical_capable_heavy_manual_work_1" name="physical_capable_heavy_manual_work" value="1" /> 
                            <?php
                        }
                         ?>
                         Yes</label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->physical_capable_heavy_manual_work == '0' )
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
                            <input type="radio" id="physical_capable_heavy_manual_work_0" name="physical_capable_heavy_manual_work" value="0" /> 
                            <?php
                        }
                         ?>
                         No</label>
				</div>
            </div>
            <div class="form-group row">
                <label class="control-label col-md-6">Have you ever been injured while on the job? </label>
				<div class="col-md-6 radio-list">
					<label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->injured_on_job == '1' )
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
                            <input type="radio" id="injured_on_job_1" name="injured_on_job" value="1" /> 
                            <?php
                        }
                         ?>
                         Yes</label>
                    <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->injured_on_job == '0' )
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
                            <input type="radio" id="injured_on_job_0" name="injured_on_job" value="0"/> 
                            <?php
                        }
                         ?>
                         No</label>
				</div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-6">Give nature and degree of such injuries: </label>
                <div class="col-md-6">
					<textarea class="form-control" name="give_nature_degree_of_injury"></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-6">How much time lost from work in the past three years for illness?  </label>
                <div class="col-md-6">
					<textarea class="form-control" name="total_time_loss_due_injury_past3"></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-6">Would you be willing to take a physical examination?</label>
				<div class="col-md-6 radio-list">
					<label class="radio-inline">
                    <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->willing_physical_examination == '1' )
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
                            <input type="radio" id="willing_physical_examination_1" name="willing_physical_examination" value="1"/> 
                            <?php
                        }
                         ?>
					 Yes</label>
                    <label class="radio-inline">
                    <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->willing_physical_examination == '0' )
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
                            <input type="radio" id="willing_physical_examination_0" name="willing_physical_examination" value="0" /> 
                            <?php
                        }
                         ?>
                     No</label>
				</div>
            </div>
            <div class="clearfix"></div>
            <hr />




		<div class="form-group row">
		  <h4 class="col-md-12">Accident Record For Past 5 Years or More</h4>
		</div>
        <div class="gndn">
            <?php
            if(isset($sub['da_ac_detail']) && count($sub['da_ac_detail']))
            {
                foreach($sub['da_ac_detail'] as $da_ac)
                {
                    ?>
                    <div class="form-group row">
                        <label class="control-label col-md-6">Date: </label>
                        <div class="col-md-6">
        					<input type="text" class="form-control date-picker" name="date_of_accident[]" value="<?php echo $da_ac->date_of_accident;?>"/>
        				</div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-6">Nature of Accident(Head-On, Rear-End, Upset, etc.): </label>
                        <div class="col-md-6">
        					<textarea class="form-control" name="nature_of_accident[]"><?php echo $da_ac->nature_of_accident;?></textarea>
        				</div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-6">Fatalities: </label>
                        <div class="col-md-6">
        					<textarea class="form-control" name="fatalities[]"><?php echo $da_ac->fatalities;?></textarea>
        				</div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-6">Injuries: </label>
                        <div class="col-md-6">
        					<textarea class="form-control" name="injuries[]"><?php echo $da_ac->injuries;?></textarea>
        				</div>
                    </div>

                    <div class="clearfix"></div>
                    <hr />
                    <?php
                }
            }
            else
            {
                ?>
                <div class="form-group row">
                    <label class="control-label col-md-6">Date: </label>
                    <div class="col-md-6">
    					<input type="text" class="form-control date-picker" name="date_of_accident[]"/>
    				</div>
                </div>

                <div class="form-group row">
                    <label class="control-label col-md-6">Nature of Accident(Head-On, Rear-End, Upset, etc.): </label>
                    <div class="col-md-6">
    					<textarea class="form-control" name="nature_of_accident[]"></textarea>
    				</div>
                </div>

                <div class="form-group row">
                    <label class="control-label col-md-6">Fatalities: </label>
                    <div class="col-md-6">
    					<textarea class="form-control" name="fatalities[]"></textarea>
    				</div>
                </div>

                <div class="form-group row">
                    <label class="control-label col-md-6">Injuries: </label>
                    <div class="col-md-6">
    					<textarea class="form-control" name="injuries[]"></textarea>
    				</div>
                </div>

                <div class="clearfix"></div>
                <hr />
                <?php
            }
            ?>


            <div class="more_acc_record"></div>
            <input type="hidden" id="count_acc_record" name="count_acc_record" value="1">
            <a href="javascript:void(0);" class="add_more_acc_record btn green">Add More</a>

            <div class="clearfix"></div>
            <hr />


            <div class="table-scrollable">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="center"  style="width: 20%;">Driver Licenses</th>
                        <th class="center"  style="width: 20%;">Province</th>
                        <th class="center"  style="width: 20%;">License Number</th>
                        <th class="center"  style="width: 20%;">Class</th>
                        <th class="center"  style="width: 20%;">Expiration Date</th>
                    </tr>
                    </thead>
                    <tr>
                        <?php
                        $i=0;
                        if(isset($sub['da_li_detail']) && $sub['da_li_detail']){
                            foreach($sub['da_li_detail'] as $da_li){
                                $dl[$i] = $da_li->driver_license;
                                $dp[$i] = $da_li->province;
                                $dln[$i] = $da_li->license_number;
                                $dc[$i] = $da_li->class;
                                $de[$i] = $da_li->expiration_date;
                                $i++;
                            }
                        }
                        if($i<=2)
                        {
                            for($j=$i;$j<=2;$j++)
                            {
                                $dl[$j] = '';
                                $dp[$j] = '';
                                $dln[$j] = '';
                                $dc[$j] = '';
                                $de[$j] = '';
                            }
                        }
                        ?>
                        <td>


                            <input type="text" class="form-control" name="driver_license[]" value="<?php echo $dl[0]?>" /></td>
                        <td><input type="text" class="form-control" name="province[]" value="<?php echo $dp[0]?>"/></td>
                        <td><input type="text" class="form-control" name="license_number[]" value="<?php echo $dln[0]?>"/></td>
                        <td><input type="text" class="form-control" name="class[]" value="<?php echo $dc[0]?>"/></td>
                        <td><input type="text" class="form-control date-picker" name="expiration_date[]" value="<?php echo $de[0]?>"/></td>
                    </tr>
                    <tr>
                        <td>


                            <input type="text" class="form-control" name="driver_license[]" value="<?php echo $dl[1]?>" /></td>
                        <td><input type="text" class="form-control" name="province[]" value="<?php echo $dp[1]?>"/></td>
                        <td><input type="text" class="form-control" name="license_number[]" value="<?php echo $dln[1]?>"/></td>
                        <td><input type="text" class="form-control" name="class[]" value="<?php echo $dc[1]?>"/></td>
                        <td><input type="text" class="form-control date-picker" name="expiration_date[]" value="<?php echo $de[1]?>"/></td>
                    </tr>
                    <tr>
                        <td>


                            <input type="text" class="form-control" name="driver_license[]" value="<?php echo $dl[2]?>" /></td>
                        <td><input type="text" class="form-control" name="province[]" value="<?php echo $dp[2]?>"/></td>
                        <td><input type="text" class="form-control" name="license_number[]" value="<?php echo $dln[2]?>"/></td>
                        <td><input type="text" class="form-control" name="class[]" value="<?php echo $dc[2]?>"/></td>
                        <td><input type="text" class="form-control date-picker" name="expiration_date[]" value="<?php echo $de[2]?>"/></td>
                    </tr>
                </table>
            </div>

<div class="clearfix"></div>
            <div class="form-group row">
										<label class="control-label col-md-8">A) Have you ever been denied a license, permit or privilege to operate a motor vehicle? </label>
										<div class="col-md-3 radio-list">
					                   <label class="radio-inline">
                                            <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->ever_been_denied == '1' )
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
                            <input type="radio" id="ever_been_denied_1" name="ever_been_denied" value="1" /> 
                            <?php
                        }
                         ?>
                                             Yes</label>
                                        <label class="radio-inline">
                                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->ever_been_denied == '0' )
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
                            <input type="radio" id="ever_been_denied_0" name="ever_been_denied" value="0" /> 
                            <?php
                        }
                         ?>
                                             No</label>
                                        </div>
            </div>

            <div class="form-group row">
										<label class="control-label col-md-8">B) Has any license, permit or privilege ever been suspended or revoked?</label>
											<div class="col-md-3 radio-list">
					                       <label class="radio-inline">
                                           <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->suspend_any_license == '1' )
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
                            <input type="radio" id="suspend_any_license_1" name="suspend_any_license" value="1" /> 
                            <?php
                        }
                         ?>
                                             Yes</label>
                                            <label class="radio-inline">
                                            <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($da_detail)&&$da_detail->suspend_any_license == '0' )
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
                            <input type="radio" id="suspend_any_license_0" name="suspend_any_license" value="0" /> 
                            <?php
                        }
                         ?>
                           No</label>
                                        </div>
            </div>
            <label class="control-label col-md-8">If the answer to either A or B is Yes, attach statement giving details.</label>
            <div class="clearfix"></div>
            <hr />



                <div class="table-scrollable">
                    <table class="table table-striped">

                        <tr>
                            <th class="center"  style="width: 25%;">Class of equipment</th>
                            <th class="center"  style="width: 25%;">Type of equipment<br />(Van,Tank,Flat,etc)</th>
                            <th class="center"  style="width: 25%;" colspan="2">Dates<br />From&nbsp;&nbsp;&nbsp;&nbsp;To</th>
                            <th class="center"  style="width: 25%;">Approx. No. of miles<br />(Total)</th>
                        </tr>
                        <tr><td class="center">Straight Truck</td>
                            <td><input type="text" class="form-control" name="straight_truck_type" /></td>
                            <td><input type="text" class="form-control date-picker" name="straight_truck_start_date" /></td>
                            <td><input type="text" class="form-control date-picker" name="straight_truck_end_date" /></td>
                            <td><input type="text" class="form-control" name="straight_truck_miles" /></td>
                        </tr>
                        <tr><td class="center">Tractor and Semi-Trailer</td>
                            <td><input type="text" class="form-control" name="tractor_semi_types" /></td>
                            <td><input type="text" class="form-control date-picker" name="tractor_semi_start_date" /></td>
                            <td><input type="text" class="form-control date-picker" name="tractor_semi_end_date" /></td>
                            <td><input type="text" class="form-control" name="tractor_miles" /></td>
                        </tr>
                        <tr><td class="center">Tractor-Two Trailers</td>
                            <td><input type="text" class="form-control" name="tractor_two_types" /></td>
                            <td><input type="text" class="form-control date-picker" name="tractor_two_start_date" /></td>
                            <td><input type="text" class="form-control date-picker" name="tractor_two_end_date" /></td>
                            <td><input type="text" class="form-control" name="tractor_two_miles" /></td>
                        </tr>
                        <tr><td class="center">Other</td>
                            <td><input type="text" class="form-control" name="other_types" /></td>
                            <td><input type="text" class="form-control date-picker" name="other_start_date" /></td>
                            <td><input type="text" class="form-control date-picker" name="other_end_date" /></td>
                            <td><input type="text" class="form-control" name="other_miles" /></td>
                        </tr>
                    </table>
                </div>
            <div class="form-group row">

										<label class="col-md-6 control-label">List states operated for in five years: </label>
										<div class="col-md-6">
                                            <textarea class="form-control" name="list_states_operated_5year"></textarea>
                                        </div>
            </div>

             <div class="form-group row">
										<label class="col-md-6 control-label">Which safe driving awards do you hold and for whom? </label>
										<div class="col-md-6">
                                            <textarea class="form-control" name="safe_driving_award_hold_whom"></textarea>
                                        </div>
            </div>

        <div class="clearfix"></div>
            <hr />
            <div class="clearfix"></div>

            		<div class="">
            			<h4 class="">
                        Medical Declaration
                        </h4>
            		</div>
                    <div class=" ">
            <p>
            On March 30, 1999, Transport Canada and U.S. Federal Highway Administration (FHWA) entered into a reciprocal agreement regarding the physical requirements for a Canadian driver of a commercial vehicle in the U.S., as currently contained in the Federal Motor Carrier Safety Regulations, Part 391.41 et seq, and vice-versa. The reciprocal agreement removes the requirement for a Canadian driver to carry a copy of a medical examiner's certificate indicating that the driver is physically qualified. (In effect, the existence of valid driver's license issued by a province in Canada is deemed to be proof that a driver is physically qualified to drive in the U.S.) However, FHWA will not recognize a Provincial license if the driver has certain medical conditions and those conditions would prohibit him from driving in the U.S.
            </p>
            <div class="">
            <div class="col-md-1" align="right">
                <!--<label class="col-md-1 control-label">I,</label>-->I,
            </div>
                <div class="col-md-4 ">
                    <input type="text" class="form-control" name="medical_certify_name" />
                </div>
                <div class="col-md-7">
                certify that I am qualified to operate a commercial motor vehicle in the United States.
                 </div>
                 </div>
            <p>I further certify that:</p>
            <ol>
                <li>I have no clinical diagnosis of diabetes currently requiring insulin for control.</li>
                <li>I have no established medical history or clinical diagnosis of epilepsy.</li>
                <li>I do not have impaired hearing. (A driver must be able to first perceive a forced whispered voice in the better ear at not less than 5 feet with or without the use of a hearing aid, or does not have an average hearing loss in the better ear greater than 40 decibels at 500Hz, 1000Hz, or 2000Hz with or without a hearing aid when tested by an audiometric device calibrated to American National Standard Z24.5-1951.)</li>
                <li>I have not been issued a waiver by any Canadian Province allowing me to operate a commercial motor vehicle pursuant to Section 20 or 21 of Ontario Regulation 340\94.</li>
            </ol>
            <p>I further agree to inform Challenger Motor Freight Inc. should my medical status change, or if I can no longer certify conditions A to D, described above.</p>

             <div class="form-group row">
                                    <div class="col-md-6">
										<input type="text" class="form-control date-picker" placeholder="Date" name="medical_certify_date"/>
                                        </div>
                                        <div class="col-md-6">
			                             <input type="text" class="form-control" placeholder="Signature" name="medical_certify_signature"/>
                                         </div>
            </div>

            <div class="clearfix"></div>
            <hr />

                    </div>




            		<div class="">
            			<div class=""><h4>To be read and signed by applicant</h4></div>
            		</div>
                    <div class="">
                    <p>THIS CERTIFIES THAT THIS APPLICATION WAS COMPLETED BY ME, AND THAT ALL ENTRIES ON IT AND INFORMATION IN IT ARE TRUE AND COMPLETE TO THE BEST OF MY KNOWLEDGE. I AUTHORIZE YOU TO MAKE SUCH INVESTIGATIONS AND INQUIRIES OF MY PERSONAL, EMPLOYMENT, FINANCIAL OR MEDICAL HISTORY AND OTHER RELATED MATTERS AS MAY BE NECESSARY IN ARRIVING AT AN EMPLOYMENT DECISION. I HEREBY RELEASE EMPLOYERS, SCHOOLS OR PERSONS FROM ALL LIABILITY IN RESPONDING TO INQUIRIES IN CONNECTION WITH MY APPLICATION.IN THE EVENT OF EMPLOYMENT, I UNDERSTAND THAT FALSE OR MISLEADING INFORMATION GIVEN IN MY APPLICATION OR INTERVIEW(S) MAY RESULT IN DISCHARGE. I UNDERSTAND, ALSO, THAT I AM REQUIRED TO ABIDE BY ALL RULES AND REGULATIONS OF THE COMPANY, AS PERMITTED BY LAW.I UNDERSTAND THAT I HAVE THE RIGHT TO REVIEW INFORMATION PROVIDED BY PREVIOUS EMPLOYERS, HAVE ERRORS CORRECTED BY PREVIOUS EMPLOYER AND RESUBMITTED TO CHALLENGER MOTOR FREIGHT INC AND /OR HAVE A REBUTTAL STATEMENT ATTACHED TO ERRONEOUS INFORMATION IF MY PREVIOUS EMPLOYER AND I CANNOT AGREE ON THE ACCURACY OF THE INFORMATION. I UNDERSTAND THAT I MUST REQUEST PAST EMPLOYER INFORMATION OBTAINED BY CHALLENGER MOTOR FREIGHT INC IN WRITING WITHIN 30-DAYS OF EMPLOYMENT OR DENIAL OF EMPLOYMENT.</p>
                    <div class="form-group row">
                                    <div class="col-md-6">
										<input type="text" class="form-control date-picker" placeholder="Date" name="read_sign_date"/>
                                        </div>
                                        <div class="col-md-6">
			                             <input type="text" class="form-control" placeholder="Signature" name="read_signature"/>
                                         </div>
                       </div>
                       <div class="clearfix"></div>
                    </div>

                    <hr />
                <div class="clearfix"></div>
            		<div class="">
            			<h4 class="">Certification of compliance with driver license requirements</h4>
            		</div>
                    <div class="">
                    <p>MOTOR CARRIER INSTRUCTIONS: The requirements in Part 383 apply to every driver who operates in intrastate, interstate, or foreign commerce and operates a vehicle weighing 26,001 pounds or more, can transport more than 15 people, or transports hazardous materials that require placarding.</p>
                    <p>The requirements in Part 391 apply to every driver who operates in interstate commerce and operates a vehicle weighing 10,001 pounds or more, can transport more than 15 people, or transports hazardous materials that require placarding.</p>
                    <p>DRIVER REQUIREMENTS: Parts 383 and 391 of the Federal Motor Carrier Safety Regulations contain some requirements that you as a driver must comply with. These requirements are in effect as of July 1, 1987. They are as follows:</p>
                    <p>A) You, as a commercial vehicle driver, may not possess more than one license. The only exception is if a state requires you to have more than one license. This exception is allowed until January 1, 1990.</p>
                    <p>If you currently have more than one license, you should keep the license from your state of residence and return the additional licenses to the states that issued them. DESTROYING a license does not close the record in the state that issued it; you must notify the state. If a multiple license has been lost, stolen, or destroyed, you should close your record by notifying the state of issuance that you no longer want to be licensed by that state.</p>
                    <p>B) Part 392.42 and Part 383.33 of the Federal Motor Carrier Safety Regulations require that you notify your employer the NEXT BUSINESS DAY of any revocation or suspension of your driver's license. In addition, Part 383.31 requires that any time you violate a state or local traffic law (other than parking), you must report it to your employing motor carrier and the state that issued your license within 30 days.</p>
                    <p>DRIVER CERTIFICATION: I certify that I have read and understand the above requirements.</p>
                    <p>The following license is the only one I will possess:</p>
                    <div class="form-group row">
                            <div class="col-md-3">
                                <label class="control-label">Driver's License No.</label>
                            </div>
                            <div class="col-md-3">
    							<input type="text" class="form-control" name="posses_driver_license_no"/>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Province</label>
                            </div>
                            <div class="col-md-3">
                                <?php provinces("posses_province"); ?>
                                 <!--<input type="text" class="form-control" name="posses_province"/> -->
                            </div>
                       </div>
                       <div class="form-group row">
                            <div class="col-md-3">
                                <label class="control-label">Exp. Date</label>
                            </div>
                            <div class="col-md-3">
    							<input type="text" class="form-control date-picker" name="posses_expiry_date"/>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Driver's Signature </label>
                            </div>
                            <div class="col-md-3">
                                 <input type="text" class="form-control" name="posses_driver_signature"/>
                            </div>
                       </div>
                       <div class="form-group row">
                            <div class="col-md-3">
                                <label class="control-label">Notes </label>
                            </div>
                            <div class="col-md-9">
    							<textarea class="form-control" name="posses_notes"></textarea>
                            </div>
                       </div>

                <div class="clearfix"></div>
                    </div>


                <hr />
                <div class="clearfix"></div>

            		<div class="">
            			<h4 class="">Challenger Driver New Hire Application Process</h4>
            		</div>
                    <div class="">
                    <p>Thank-you for applying for a driving position with Challenger Motor Freight Inc. Please take a few minutes to review what the companies requirements are to be considered for a driving position with the company.</p>
                    <p>The following requirements need to be completed and/or provided prior to an offer of employment being given to the potential applicant:</p>
                    <ol>
                        <li>A road test evaluation is to be completed with an assigned Challenger Driver Trainer</li>
                        <li>A drug test and health assessment will be conducted with the company's Occupational and Health Care Provider.</li>
                        <li>A valid AZ/Class 1 driver's license.</li>
                        <li>A valid Canadian passport or proof of permanent residence status in Canada.</li>
                        <li>A criminal record search conducted.</li>
                        <li>Provide a driver's abstract (all applicants), and CVOR (Ontario applicants only).</li>
                        <li>Provide relevant reference contacts for the past ten years of work experience.</li>
                        <li>Satisfactory completion of the Training (Orientation) Program.</li>
                    </ol>

                    <p>I understand that I may receive an offer of employment once this process has been completed by Challenger based on successful results of the above. I also understand that I will not receive and am not entitled to any payment for participating in the Training (Orientation) Program.</p>

                        <div class="note note-success">

                            <label for="confirm_check" style="margin: 0;">
                                <h4 style="line-height: 120%;">
                                    <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($da_detail) && $da_detail->confirm_check =='1')
                    {
                        ?>
                        &#9745;
                        <?php
                    }
                    else 
                    {
                        ?>
                        &#9744;
                        <?php
                    } 
                }
                else
                {
                    ?>                                      
                    <input type="checkbox" class="form-control" value="1" id="confirm_check" name="confirm_check"/> 
                    <?php
                }
             ?>
                                    I confirm that I have read and understand the above conditions as part of the application process. I have been given an opportunity to ask questions.</h4></label>
                        </div>
                    </div>

                     <div class="form-group row">
                                        <label class="control-label col-md-6">Dated at on the day of: </label>
                                        <div class="col-md-6">
										<input type="text" class="form-control date-picker" placeholder="YYYY-MM-DD" name="dated_day"/>
                                        </div>
                       </div>

                       <div class="form-group row">
                                        <div class="col-md-6">
										<input type="text" class="form-control" placeholder="Witness (Print Name)" name="witness_name"/>
                                        </div>

                                        <div class="col-md-6">
										<input type="text" class="form-control" placeholder="Applicant (Print Name)" name="applicant_name"/>
                                        </div>
                       </div>

                       <!--div class="form-group ">
                                        <div class="col-md-6">
										<input type="text" class="form-control" placeholder="Witness Signature" name="witness_signature"/>
                                        </div>

                                        <div class="col-md-6">
										<input type="text" class="form-control" placeholder="Applicant Signature" name="applicant_signature"/>
                                        </div>
                       </div-->
                       <?php if($this->request->params['controller']!='Documents' && $this->request->params['controller']!='ClientApplication'){?>
                       <div class="allattach">
                       <?php
                                        if(!isset($sub['da_at']))//THIS SHOULD BE USING FILELIST.PHP!!!!!!!!!!!!
                                        {
                                            $sub['da_at'] = array();
                                            }
                                            if(!count($sub['da_at'])){
                                            ?>
                       <div class="form-group " style="display:block;margin-top:5px; margin-bottom: 5px;">
                                        <label class="control-label col-md-3">Attach File: </label>
                                        <div class="col-md-9">
                                        <input type="hidden" class="driveApp1" name="attach_doc[]" />
                                        <a href="#" id="driveApp1" class="btn btn-primary">Browse</a> <span class="uploaded"></span>
                                        </div>
                       </div>
                       <?php }?>
                      <div class="form-group row">
                        <div id="more_doc" data-driverapp="<?php if(count($sub['da_at']))echo count($sub['da_at']);else echo '1';?>">
                        <?php
                                        if(count($sub['da_at']))//THIS SHOULD BE USING FILELIST.PHP!!!!!
                                        {
                                            $at=0;
                                            foreach($sub['da_at'] as $pa)
                                            {
                                                if($pa->attachment){
                                                $at++;
                                                ?>
                                                <div class="del_append"><label class="control-label col-md-3">Attach File: </label><div class="col-md-6 pad_bot"><input type="hidden" class="driveApp<?php echo $at;?>" name="attach_doc[]" value="<?php echo $pa->attachment;?>" /><a href="#" id="driveApp<?php echo $at;?>" class="btn btn-primary">Browse</a> <a  href="javascript:void(0);" class="btn btn-danger" id="delete_doc" onclick="$(this).parent().remove();">Delete</a>
                                                <span class="uploaded"><?php echo $pa->attachment;?>  <?php if($pa->attachment){$ext_arr = explode('.',$pa->attachment);$ext = end($ext_arr);$ext = strtolower($ext);if(in_array($ext,$img_ext)){?><img src="<?php echo $this->request->webroot;?>attachments/<?php echo $pa->attachment;?>" style="max-width:120px;" /><?php }elseif(in_array($ext,$doc_ext)){?><a class="dl" href="<?php echo $this->request->webroot;?>attachments/<?php echo $pa->attachment;?>">Download</a><?php }else{?><br />
                                                 <video width="320" height="240" controls>
                                                  <source src="<?php echo $this->request->webroot;?>attachments/<?php echo $pa->attachment;?>" type="video/mp4">
                                                  <source src="<?php echo $this->request->webroot;?>attachments/<?php echo str_replace('.mp4','.ogg',$pa->attachment);?>" type="video/ogg">
                                                Your browser does not support the video tag.
                                                </video>
                                                            <?php } }?></span>
                                                </div></div><div class="clearfix"></div>
                                                <script>
                                                $(function(){
                                                    fileUpload('driveApp<?php echo $at;?>');
                                                });
                                                </script>
                                                <?php
                                            }}
                                        }
                                        ?>
                        </div>
                      </div>

                      <div class="form-group row">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <a href="javascript:void(0);" class="btn btn-success" id="add_more_doc">Add More</a>
                        </div>
                      </div>
                      <!--<div class="form-group col-md-12">
                            <label class="control-label col-md-3">Signature: </label>
                            <?php //include('canvas/example2.php');?>
                            <div class="clearfix"></div>                                        
                      </div>-->
                       
                      
                       
                    
                    <div class="clearfix"></div>
                    </div>
                    <?php }?>

</form>
            
        <div class="clearfix"></div>

 <script>
 
 jQuery(function(){
    <?php
        if(($this->request->params['action']=='addorder' || $this->request->params['action']=='add' || $this->request->params['action']=='apply') && !count($sub['da_at']))
        {
            ?>
            fileUpload('driveApp1');
            <?php
        }
        ?>
    //
    $('#add_more_form').click(function(){
      $.ajax({
        url:" <?php echo $this->request->webroot;?>subpages/period_of_unemployment.php",
        success:function(res){
           $('.more_form').append(res);
        }
      });     
    });
    
    $('.delete_form').live('click',function(){
        $(this).parent().remove();
       });
       
    $('#add_more_doc').click(function(){
        var count = $('#more_doc').data('driverapp');
         $('#more_doc').data('driverapp',parseInt(count)+1);
         
        $('#more_doc').append('<div class="del_append"><label class="control-label col-md-3"></label><div class="col-md-6 pad_bot"><input type="hidden" class="driveApp'+$('#more_doc').data('driverapp')+'" name="attach_doc[]" /><a href="#" id="driveApp'+$('#more_doc').data('driverapp')+'" class="btn btn-primary">Browse</a> <a  href="javascript:void(0);" class="btn btn-danger" id="delete_doc">Delete</a> <span class="uploaded"></span></div></div><div class="clearfix"></div>')
        fileUpload('driveApp'+$('#more_doc').data('driverapp'));
    }) ;
    
    $('#delete_doc').live('click',function(){
        var count = $('#more_doc').data('driverapp');
         $('#more_doc').data('driverapp',parseInt(count)-1);
       $(this).closest('.del_append').remove(); 
    });
 });
 
 $('.add_more_acc_record').click(function(){
    $.ajax({
       url:"<?php echo $this->request->webroot; ?>subpages/accident_record.php",
       success:function(res){
        $('.more_acc_record').append(res);
        var c = $('#count_acc_record').val();
        $('#count_acc_record').val(parseInt(c)+1);
        $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
       } 
    });
    
    $('.delete_acc_record').live('click',function(){
       $(this).parent().remove(); 
        var c = $('#count_acc_record').val();
        $('#count_acc_record').val(parseInt(c)-1);
    });
 });
 </script></div></div></div>
 <?php
  
if($controller == 'documents' )
{
echo '</div></div></div></div></div></div>' ;
}
 ?>
