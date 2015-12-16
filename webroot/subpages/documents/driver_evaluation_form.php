<?php
    if($this->request->params['controller']!='ClientApplication'){
        if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/documents/driver_evaluation_form.php #INC141</span>"; }
        include_once 'subpages/filelist.php';
        if( isset($sub['de_at'])){  listfiles($sub['de_at'], "attachments/", "", false,3); }
    }
    $strings2 = CacheTranslations($language, array("drivereval_%", "tasks_date", "file_attachfile",'forms_driverslicense'), $settings, False);
    if(isset($dx)){ echo '<h3>' . $dx->title . '</h3>';}
?>
<form id="form_tab3">
<input class="document_type" type="hidden" name="document_type" value="<?php if(isset($dx))echo $dx->title;?>" />

<input type="hidden" class="sub_docs_id" name="sub_doc_id" value="3" id="af" />
<div class="clearfix"></div>
<hr />
                                                <div class="form-group row">
													<label class="control-label col-md-3"><?= $strings2["drivereval_drivername"]; ?>
													</label>
													<div class="col-md-6">
														<input type="text" class="form-control" name="driver_name" value="<?php if(isset($deval_detail) && $deval_detail->driver_name) {echo $deval_detail->driver_name;}?>"/>
														
													</div>
												</div>
												<div class="form-group row">
													<label class="control-label col-md-3"><?= $strings["forms_driverslicense"]; ?># 
													</label>
													<div class="col-md-6">
														<input type="text" placeholder="" class="form-control" name="d_l" value="<?php if(isset($deval_detail) && $deval_detail->d_l) {echo $deval_detail->d_l;}?>" />
														
													</div>
												</div>
												<div class="form-group row">
													<label class="control-label col-md-3"><?= $strings2["tasks_date"]; ?> 
													</label>
													<div class="col-md-6">
														<input type="text" placeholder="" class="form-control date-picker" name="issued_date" value="<?php if(isset($deval_detail) && $deval_detail->issued_date) {echo $deval_detail->issued_date;}?>" />
														
													</div>
												</div>
												<div class="form-group row">
													<label class="control-label col-md-3"><?= $strings2["drivereval_transmissi"]; ?> 
													</label>
													<div class="col-md-9">
                                                        <div class="checkbox-list col-md-3 nopad">
															<label>
                                                            <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view') {
                                                                if(isset($deval_detail) && $deval_detail->transmission_manual_shift =='1') {
                                                                    echo '&#9745;';
                                                                } else {
                                                                    echo '&#9744;';
                                                                } 
                                                            } else {
                                                                echo '<input type="checkbox" id="transmission_manual_shift_1" name="transmission_manual_shift" class="" value="1"/>';
                                                            }
                                                         ?>
                                                            <?= $strings2["drivereval_manualshif"]; ?> </label></div><div class="checkbox-list col-md-3 nopad">
															<label>
                                                            <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view') {
                                                                if(isset($deval_detail) && $deval_detail->transmission_auto_shift =='2') {
                                                                    echo '&#9745;';
                                                                } else {
                                                                    echo '&#9744;';
                                                                } 
                                                            } else {
                                                                echo '<input class="" type="checkbox" id="transmission_auto_shift_2" name="transmission_auto_shift" value="2"/>';
                                                            }
                                                         ?>
                                                            <?= $strings2["drivereval_autoshifta"]; ?> </label>
														</div>
														<div id="form_payment_error">
														</div>
													</div>
												</div>
                                                <div class="form-group row">
													<label class="control-label col-md-3"><?= $strings2["drivereval_nameofeval"]; ?> 
													</label>
													<div class="col-md-6">
                                                        <?php
                                                            $value="";
                                                            if(!$did){
                                                                $value = $this->request->session()->read('Profile.fname').' '.$this->request->session()->read('Profile.mname').' '.$this->request->session()->read('Profile.lname');
                                                            }
                                                        ?>

														<input type="text" placeholder="" class="form-control" name="name_evaluator" value="<?= $value; ?>" <?php if(strlen($value)>1) { echo " disabled"; }?> />
													</div>
												</div>
                                                
                                                <div class="form-group row">
													<label class="control-label col-md-3"><?= $strings["forms_select"]; ?> 
													</label>
													<div class="col-md-9">
														<div class="checkbox-list col-md-3 nopad">
															<label>
                                                            <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->pre_hire =='1')
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
                                                                <input class="" type="checkbox" name="pre_hire" value="1"/> 
                                                                <?php
                                                            }
                                                         ?>
                                                                <?= $strings2["drivereval_prehireloc"]; ?> </label>
															<label>
                                                            <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->post_accident =='2')
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
                                                                <input class="" type="checkbox" name="post_accident" value="2"/> 
                                                                <?php
                                                            }
                                                         ?>
                                                                <?= $strings2["drivereval_postaccide"]; ?> </label>
														</div>
														<div class="checkbox-list col-md-3 nopad">
															<label>
                                                            <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->post_injury =='1')
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
                                                                <input class="" type="checkbox" name="post_injury" value="1"/> 
                                                                <?php
                                                            }
                                                         ?>
                                                                <?= $strings2["drivereval_postinjury"]; ?> </label>
															<label>
                                                            <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->post_training =='2')
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
                                                                <input class="" type="checkbox" name="post_training" value="2"/> 
                                                                <?php
                                                            }
                                                         ?>
                                                                <?= $strings2["drivereval_posttraini"]; ?> </label>
														</div>
                                                        <div class="checkbox-list col-md-3 nopad">
															<label>
                                                            <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->annual =='1')
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
                                                                <input class="" type="checkbox" name="annual" value="1"/> 
                                                                <?php
                                                            }
                                                         ?>
                                                                <?= $strings2["drivereval_annualannu"]; ?> </label>
															<label>
                                                            <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->skill_verification =='2')
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
                                                                <input class="" type="checkbox" name="skill_verification" value="2"/> 
                                                                <?php
                                                            }
                                                         ?>
                                                                <?= $strings2["drivereval_skillverif"]; ?> </label>
														</div>
													</div>
												</div>
                                                <div class="clearfix"></div>
                                                <hr />
                                                <div class="scores">
                                                    <div class="form-group row">
                                                    <div class="col-md-6">
                                                        <div class="portlet box blue">
                                                            <div class="portlet-title">
                                                                <div class="caption"><strong><?= $strings2["drivereval_pretripins"]; ?>:</strong> <?= $strings2["drivereval_failstoche"]; ?></div>
                                                            </div>
                                                            
                                                            <div class="portlet-body" id="firstcheck">
                                                                <div class="col-md-6 checkbox-list">
                                                                    <label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->fuel_tank =='1')
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
                                                                <input type="checkbox" name="fuel_tank" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_fueltankrs"]; ?> </label>
        															<label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->all_gauges =='1')
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
                                                                <input type="checkbox" name="all_gauges" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_allgaugest"]; ?> </label>
                                                                    <label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->audible_air =='1')
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
                                                                <input type="checkbox" name="audible_air" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_audibleair"]; ?> </label>
        															<label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->wheels_tires =='1')
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
                                                                <input type="checkbox" name="wheels_tires" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_wheelstire"]; ?>  </label>
                                                                    <label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->trailer_brakes =='1')
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
                                                                <input type="checkbox" name="trailer_brakes" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_trailerbra"]; ?> </label>
                                                                    <label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->trailer_airlines =='1')
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
                                                                <input type="checkbox" name="trailer_airlines" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_trailerair"]; ?> </label>
                                                                    <label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->inspect_5th_wheel =='1')
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
                                                                <input type="checkbox" name="inspect_5th_wheel" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_inspectthw"]; ?> </label>
                                                                    <label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->cold_check =='1')
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
                                                                <input type="checkbox" name="cold_check" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_coldcheckv"]; ?> </label>

                                                                    <label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->seat_mirror =='1')
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
                                                                <input type="checkbox" class="1" name="seat_mirror" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_seatandmir"]; ?> </label></div>
                                                                <div class="col-md-6 checkbox-list ">
        															<label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->coupling =='1')
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
                                                                <input type="checkbox" class="1" name="coupling" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_couplingac"]; ?>&nbsp; &nbsp; &nbsp; &nbsp;</label>

        															<label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->lights_abs_lamps =='1')
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
                                                                <input type="checkbox" class="1" name="lights_abs_lamps" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_lightsabsl"]; ?> </label>
                                                                    <label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->annual_inspection_strickers =='1')
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
                                                                <input type="checkbox" class="1" name="annual_inspection_strickers" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_annualinsp"]; ?> </label>
                                                                    <label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->cab_air_brake_checked =='1')
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
                                                                <input type="checkbox" class="1" name="cab_air_brake_checked" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_incabairbr"]; ?> </label>
                                                                    <label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->landing_gear =='1')
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
                                                                <input type="checkbox" class="1" name="landing_gear" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_landinggea"]; ?> </label>
                                                                    <label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->emergency_exit =='1')
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
                                                                <input type="checkbox" class="1" name="emergency_exit" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_emergencye"]; ?> </label>
                                                                    <label>
                                                                    <?php 
                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                            {
                                                                if(isset($deval_detail) && $deval_detail->paperwork =='1')
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
                                                                <input type="checkbox" class="1" name="paperwork" value="1" /> 
                                                                <?php
                                                            }
                                                         ?>
                                                                        <?= $strings2["drivereval_paperworkp"]; ?> </label>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <input class="form-control" type="hidden" name="total1" id="total1" <?php if(!$did){?>value="0"<?php }?> />
                                                            </div>
                                                        </div>

                                                            <div class="portlet box blue">
                                                                <div class="portlet-title">
                                                                    <div class="caption"><strong><?= $strings2["drivereval_corneringv"]; ?>:</strong></div>
                                                                </div>

                                                                <div class="portlet-body">
                                                                    <div>
                                                                        <div class="col-md-12">
                                                                            <?= $strings2["drivereval_signalings"]; ?>
                                                                        </div>
                                                                        <div class="col-md-12 radio-list">
                                                                            <label class="radio-inline">2
                                                                            <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_signaling == '1')
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
                                                                                <input type="radio" class="2" id="cornering_signaling_1" name="cornering_signaling" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">4
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_signaling == '2')
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
                                                                                <input type="radio" class="4" id="cornering_signaling_2" name="cornering_signaling" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">6
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_signaling == '3')
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
                                                                                <input type="radio" class="6" id="cornering_signaling_3" name="cornering_signaling" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">8
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_signaling == '4')
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
                                                                                <input type="radio" class="8" id="cornering_signaling_4" name="cornering_signaling" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                    </div>

                                                                    <div>
                                                                        <div class="col-md-12">
                                                                            <?= $strings2["drivereval_speedvites"]; ?>
                                                                        </div>
                                                                        <div class="col-md-12 radio-list">
                                                                            <label class="radio-inline">2
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_speed == '1')
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
                                                                                <input type="radio" class="2" id="cornering_speed_1" name="cornering_speed" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">4
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_speed == '2')
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
                                                                                <input type="radio" class="4" id="cornering_speed_2" name="cornering_speed" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">6
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_speed == '3')
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
                                                                                <input type="radio" class="6" id="cornering_speed_3" name="cornering_speed" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">8
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_speed == '4')
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
                                                                                <input type="radio" class="8" id="cornering_speed_4" name="cornering_speed" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                    </div>

                                                                    <div>
                                                                        <div class="col-md-12">
                                                                            <?= $strings2["drivereval_failstoget"]; ?>
                                                                        </div>
                                                                        <div class="col-md-12 radio-list">
                                                                            <label class="radio-inline">2
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_fails == '1')
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
                                                                                <input type="radio" class="2" id="cornering_fails_1" name="cornering_fails" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">4
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_fails == '2')
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
                                                                                <input type="radio" class="4" id="cornering_fails_2" name="cornering_fails" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">6
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_fails == '3')
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
                                                                                <input type="radio" class="6" id="cornering_fails_3" name="cornering_fails" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">8
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_fails == '4')
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
                                                                                <input type="radio" class="8" id="cornering_fails_4" name="cornering_fails" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                    </div>

                                                                    <div>
                                                                        <div class="col-md-12">
                                                                            <?= $strings2["drivereval_propersetu"]; ?>
                                                                        </div>
                                                                        <div class="col-md-12 radio-list">
                                                                            <label class="radio-inline">2
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_proper_set_up_turn == '1')
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
                                                                                <input type="radio" class="2" id="cornering_proper_set_up_turn_1" name="cornering_proper_set_up_turn" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">4
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_proper_set_up_turn == '2')
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
                                                                                <input type="radio" class="4" id="cornering_proper_set_up_turn_2" name="cornering_proper_set_up_turn" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">6
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_proper_set_up_turn == '3')
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
                                                                                <input type="radio" class="6" id="cornering_proper_set_up_turn_3" name="cornering_proper_set_up_turn" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">8
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_proper_set_up_turn == '4')
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
                                                                                <input type="radio" class="8" id="cornering_proper_set_up_turn_4" name="cornering_proper_set_up_turn" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                    </div>

                                                                    <div>
                                                                        <div class="col-md-12">
                                                                            <?= $strings2["drivereval_turns"]; ?>
                                                                        </div>
                                                                        <div class="col-md-12 radio-list">
                                                                            <label class="radio-inline">2
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_turns == '1')
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
                                                                                <input type="radio" class="2" id="cornering_turns_1" name="cornering_turns" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">4
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_turns == '2')
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
                                                                                <input type="radio" class="4" id="cornering_turns_2" name="cornering_turns" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">6
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_turns == '3')
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
                                                                                <input type="radio" class="6" id="cornering_turns_3" name="cornering_turns" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">8
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_turns == '4')
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
                                                                                <input type="radio" class="8" id="cornering_turns_4" name="cornering_turns" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                    </div>

                                                                    <div>
                                                                        <div class="col-md-12">
                                                                            <?= $strings2["drivereval_useofwrong"]; ?>
                                                                        </div>
                                                                        <div class="col-md-12 radio-list">
                                                                            <label class="radio-inline">2
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_wrong_lane_impede == '1')
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
                                                                                <input type="radio" class="2" id="cornering_wrong_lane_impede_1" name="cornering_wrong_lane_impede" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">4
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_wrong_lane_impede == '2')
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
                                                                                <input type="radio" class="4" id="cornering_wrong_lane_impede_2" name="cornering_wrong_lane_impede" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">6
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_wrong_lane_impede == '3')
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
                                                                                <input type="radio" class="6" id="cornering_wrong_lane_impede_3" name="cornering_wrong_lane_impede" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                            <label class="radio-inline">8
                                                                                 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->cornering_wrong_lane_impede == '4')
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
                                                                                <input type="radio" class="8" id="cornering_wrong_lane_impede_4" name="cornering_wrong_lane_impede" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                                </label>
                                                                        </div>
                                                                        <div class="clearfix"></div>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        <div class="portlet box blue">
                                                            <div class="portlet-title">
                                                                <div class="caption"><strong><?= $strings2["drivereval_shifting"]; ?>:</strong> <?= $strings2["drivereval_failstoper"]; ?></div>
                                                            </div>

                                                            <div class="portlet-body">
                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_smoothtake"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
                                                                        <label class="radio-inline">1
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_smooth_take_off == '1')
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
                                                                                <input type="radio" class="1" id="shifting_smooth_take_off_1" name="shifting_smooth_take_off" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">2
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_smooth_take_off == '2')
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
                                                                                <input type="radio" class="2" id="shifting_smooth_take_off_2" name="shifting_smooth_take_off" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">3
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_smooth_take_off == '3')
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
                                                                                <input type="radio" class="3" id="shifting_smooth_take_off_3" name="shifting_smooth_take_off" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">4
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_smooth_take_off == '4')
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
                                                                                <input type="radio" class="4" id="shifting_smooth_take_off_4" name="shifting_smooth_take_off" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div>

                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_propergear"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
                                                                        <label class="radio-inline">1
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_proper_gear_selection == '1')
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
                                                                                <input type="radio" class="1" id="shifting_proper_gear_selection_1" name="shifting_proper_gear_selection" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            
                                                                            </label>
                                                                        <label class="radio-inline">2
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_proper_gear_selection == '2')
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
                                                                                <input type="radio" class="2" id="shifting_proper_gear_selection_2" name="shifting_proper_gear_selection" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                             </label>
                                                                            <label class="radio-inline">3
                                                                              <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_proper_gear_selection == '3')
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
                                                                                <input type="radio" class="3" id="shifting_proper_gear_selection_3" name="shifting_proper_gear_selection" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">4
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_proper_gear_selection == '4')
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
                                                                                <input type="radio" class="4" id="shifting_proper_gear_selection_4" name="shifting_proper_gear_selection" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div>

                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_properclut"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
                                                                        <label class="radio-inline">1
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_proper_clutching == '1')
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
                                                                                <input type="radio" class="1" id="shifting_proper_clutching_1" name="shifting_proper_clutching" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">2
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_proper_clutching == '2')
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
                                                                                <input type="radio" class="2" id="shifting_proper_clutching_2" name="shifting_proper_clutching" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">3
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_proper_clutching == '3')
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
                                                                                <input type="radio" class="3" id="shifting_proper_clutching_3" name="shifting_proper_clutching" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">4
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_proper_clutching == '4')
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
                                                                                <input type="radio" class="4" id="shifting_proper_clutching_4" name="shifting_proper_clutching" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div>

                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_gearrecove"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
                                                                        <label class="radio-inline">1
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_gear_recovery == '1')
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
                                                                                <input type="radio" class="1" id="shifting_gear_recovery_1" name="shifting_gear_recovery" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">2
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_gear_recovery == '2')
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
                                                                                <input type="radio" class="2" id="shifting_gear_recovery_2" name="shifting_gear_recovery" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">3
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_gear_recovery == '3')
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
                                                                                <input type="radio" class="3" id="shifting_gear_recovery_3" name="shifting_gear_recovery" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">4
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_gear_recovery == '4')
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
                                                                                <input type="radio" class="4" id="shifting_gear_recovery_4" name="shifting_gear_recovery" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div>

                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_updownshif"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
                                                                        <label class="radio-inline">1
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_up_down == '1')
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
                                                                                <input type="radio" class="1" id="shifting_up_down_1" name="shifting_up_down" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">2
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_up_down == '2')
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
                                                                                <input type="radio" class="2" id="shifting_up_down_2" name="shifting_up_down" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">3
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_up_down == '3')
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
                                                                                <input type="radio" class="3" id="shifting_up_down_3" name="shifting_up_down" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">4
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->shifting_up_down == '4')
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
                                                                                <input type="radio" class="4" id="shifting_up_down_4" name="shifting_up_down" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div>


                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="portlet box blue">
                                                            <div class="portlet-title">
                                                                <div class="caption"><strong><?= $strings2["drivereval_driving"]; ?>:</strong></div>
                                                            </div>
                                                            
                                                            <div class="portlet-body" id="secondcheck">
                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_followstoo"]; ?>
                                                                    </div>
                                                                        <div class="col-md-12 radio-list">
					                                                   <label class="radio-inline">2
                                                                        <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_follows_too_closely == '1')
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
                                                                                <input type="radio" class="2" id="driving_follows_too_closely_1" name="driving_follows_too_closely" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                       </label>
                                                                        <label class="radio-inline">4
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_follows_too_closely == '2')
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
                                                                                <input type="radio" class="4" id="driving_follows_too_closely_2" name="driving_follows_too_closely" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">6
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_follows_too_closely == '3')
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
                                                                                <input type="radio" class="6" id="driving_follows_too_closely_3" name="driving_follows_too_closely" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">8
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_follows_too_closely == '4')
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
                                                                                <input type="radio" class="8" id="driving_follows_too_closely_4" name="driving_follows_too_closely" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                    </div>
    							                                    <div class="clearfix"></div>
                                                                </div> 
                                                                
                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_improperch"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
					                                                   <label class="radio-inline">2
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_improper_choice_lane == '1')
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
                                                                                <input type="radio" class="2" id="driving_improper_choice_lane_1" name="driving_improper_choice_lane" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">4
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_improper_choice_lane == '2')
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
                                                                                <input type="radio" class="4" id="driving_improper_choice_lane_2" name="driving_improper_choice_lane" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">6
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_improper_choice_lane == '3')
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
                                                                                <input type="radio" class="6" id="driving_improper_choice_lane_3" name="driving_improper_choice_lane" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">8
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_improper_choice_lane == '4')
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
                                                                                <input type="radio" class="8" id="driving_improper_choice_lane_4" name="driving_improper_choice_lane" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                    </div>
    							                                    <div class="clearfix"></div>
                                                                </div>
                                                                
                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_failstouse"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
					                                                   <label class="radio-inline">2
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_fails_use_mirror_properly == '1')
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
                                                                                <input type="radio" class="2" id="driving_fails_use_mirror_properly_1" name="driving_fails_use_mirror_properly" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">4
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_fails_use_mirror_properly == '2')
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
                                                                                <input type="radio" class="4" id="driving_fails_use_mirror_properly_2" name="driving_fails_use_mirror_properly" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">6
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_fails_use_mirror_properly == '3')
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
                                                                                <input type="radio" class="6" id="driving_fails_use_mirror_properly_3" name="driving_fails_use_mirror_properly" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">8
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_fails_use_mirror_properly == '4')
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
                                                                                <input type="radio" class="8" id="driving_fails_use_mirror_properly_4" name="driving_fails_use_mirror_properly" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                    </div>
    							                                    <div class="clearfix"></div>
                                                                </div>  
                                                                
                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_signal"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
					                                                   <label class="radio-inline">2
                                                                        <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_signal == '1')
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
                                                                                <input type="radio" class="2" id="driving_signal_1" name="driving_signal" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">4
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_signal == '2')
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
                                                                                <input type="radio" class="4" id="driving_signal_2" name="driving_signal" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">6
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_signal == '3')
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
                                                                                <input type="radio" class="6" id="driving_signal_3" name="driving_signal" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">8
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_signal == '4')
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
                                                                                <input type="radio" class="8" id="driving_signal_4" name="driving_signal" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                    </div>
    							                                    <div class="clearfix"></div>
                                                                </div>  
                                                                
                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_failstous2"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
					                                                   <label class="radio-inline">2
                                                                        <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_fail_use_caution_rr == '1')
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
                                                                                <input type="radio" class="2" id="driving_fail_use_caution_rr_1" name="driving_fail_use_caution_rr" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">4
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_fail_use_caution_rr == '2')
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
                                                                                <input type="radio" class="4" id="driving_fail_use_caution_rr_2" name="driving_fail_use_caution_rr" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">6
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_fail_use_caution_rr == '3')
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
                                                                                <input type="radio" class="6" id="driving_fail_use_caution_rr_3" name="driving_fail_use_caution_rr" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">8
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_fail_use_caution_rr == '4')
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
                                                                                <input type="radio" class="8" id="driving_fail_use_caution_rr_4" name="driving_fail_use_caution_rr" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                    </div>
    							                                    <div class="clearfix"></div>
                                                                </div>
                                                                
                                                                 <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_speed"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
					                                                   <label class="radio-inline">
                                                                        2
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_speed == '1')
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
                                                                                <input type="radio" class="2" id="driving_speed_1" name="driving_speed" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        4
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_speed == '2')
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
                                                                                <input type="radio" class="4" id="driving_speed_2" name="driving_speed" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        6
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_speed == '3')
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
                                                                                <input type="radio" class="6" id="driving_speed_3" name="driving_speed" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        8
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_speed == '4')
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
                                                                                <input type="radio" class="8" id="driving_speed_4" name="driving_speed" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                    </div>
    							                                    <div class="clearfix"></div>
                                                                </div> 
                                                                
                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_incorrectu"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
					                                                   <label class="radio-inline">
                                                                        2
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_incorrect_use_clutch_brake == '1')
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
                                                                                <input type="radio" class="2" id="driving_incorrect_use_clutch_brake_1" name="driving_incorrect_use_clutch_brake" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        4
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_incorrect_use_clutch_brake == '2')
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
                                                                                <input type="radio" class="4" id="driving_incorrect_use_clutch_brake_2" name="driving_incorrect_use_clutch_brake" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        6
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_incorrect_use_clutch_brake == '3')
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
                                                                                <input type="radio" class="6" id="driving_incorrect_use_clutch_brake_3" name="driving_incorrect_use_clutch_brake" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        8
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_incorrect_use_clutch_brake == '4')
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
                                                                                <input type="radio" class="8" id="driving_incorrect_use_clutch_brake_4" name="driving_incorrect_use_clutch_brake" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                    </div>
    							                                    <div class="clearfix"></div>
                                                                </div>
                                                                
                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_accelerato"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
					                                                   <label class="radio-inline">
                                                                        2
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_accelerator_gear_steer == '1')
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
                                                                                <input type="radio" class="2" id="driving_accelerator_gear_steer_1" name="driving_accelerator_gear_steer" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        4
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_accelerator_gear_steer == '2')
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
                                                                                <input type="radio" class="4" id="driving_accelerator_gear_steer_2" name="driving_accelerator_gear_steer" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        6
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_accelerator_gear_steer == '3')
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
                                                                                <input type="radio" class="6" id="driving_accelerator_gear_steer_3" name="driving_accelerator_gear_steer" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        8
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_accelerator_gear_steer == '4')
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
                                                                                <input type="radio" class="8" id="driving_accelerator_gear_steer_4" name="driving_accelerator_gear_steer" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                    </div>
    							                                    <div class="clearfix"></div>
                                                                </div>
                                                                
                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_incorrecto"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
					                                                   <label class="radio-inline">
                                                                        2
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_incorrect_observation_skills == '1')
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
                                                                                <input type="radio" class="2" id="driving_incorrect_observation_skills_1" name="driving_incorrect_observation_skills" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        4
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_incorrect_observation_skills == '2')
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
                                                                                <input type="radio" class="4" id="driving_incorrect_observation_skills_2" name="driving_incorrect_observation_skills" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        6
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_incorrect_observation_skills == '3')
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
                                                                                <input type="radio" class="6" id="driving_incorrect_observation_skills_3" name="driving_incorrect_observation_skills" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        8
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_incorrect_observation_skills == '4')
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
                                                                                <input type="radio" class="8" id="driving_incorrect_observation_skills_4" name="driving_incorrect_observation_skills" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                    </div>
    							                                    <div class="clearfix"></div>
                                                                </div>
                                                                
                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_doesntresp"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
					                                                   <label class="radio-inline">
                                                                        2
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_respond_instruction == '1')
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
                                                                                <input type="radio" class="2" id="driving_respond_instruction_1" name="driving_respond_instruction" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        4
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_respond_instruction == '2')
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
                                                                                <input type="radio" class="4" id="driving_respond_instruction_2" name="driving_respond_instruction" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        6
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_respond_instruction == '3')
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
                                                                                <input type="radio" class="6" id="driving_respond_instruction_3" name="driving_respond_instruction" value="3"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                        <label class="radio-inline">
                                                                        8
                                                                         <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->driving_respond_instruction == '4')
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
                                                                                <input type="radio" class="8" id="driving_respond_instruction_4" name="driving_respond_instruction" value="4"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                        </label>
                                                                    </div>
    							                                    <div class="clearfix"></div>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="portlet box blue">
                                                            <div class="portlet-title">
                                                                <div class="caption"><strong><?= $strings2["drivereval_backing"]; ?>:</strong> <?= $strings2["drivereval_sightsideb"]; ?> | <em><?= $strings2["drivereval_sightsidec"]; ?></em></div>
                                                            </div>

                                                            <div class="portlet-body">
                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_usesproper"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
                                                                        <label class="radio-inline">
                                                                            1
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->backing_uses_proper_set_up == '1')
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
                                                                                <input type="radio" class="1" id="backing_uses_proper_set_up_1" name="backing_uses_proper_set_up" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            
                                                                    </label>
                                                                    <div class="clearfix"></div>
                                                                </div>

                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_checkvehic"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
                                                                        <label class="radio-inline">
                                                                            1
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->backing_path_before_while_driving == '1')
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
                                                                                <input type="radio" class="1" id="backing_path_before_while_driving_1" name="backing_path_before_while_driving" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">
                                                                            2
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->backing_path_before_while_driving == '2')
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
                                                                                <input type="radio" class="2" id="backing_path_before_while_driving_2" name="backing_path_before_while_driving" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div>

                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_useofwayfl"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
                                                                        <label class="radio-inline">
                                                                            1
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->backing_use_4way_flashers_city_horn == '1')
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
                                                                                <input type="radio" class="1" id="backing_use_4way_flashers_city_horn_1" name="backing_use_4way_flashers_city_horn" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">
                                                                            2
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->backing_use_4way_flashers_city_horn == '2')
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
                                                                                <input type="radio" class="2" id="backing_use_4way_flashers_city_horn_2" name="backing_use_4way_flashers_city_horn" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div>

                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_showscerta"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
                                                                        <label class="radio-inline">
                                                                            1
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->backing_show_certainty_while_steering == '1')
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
                                                                                <input type="radio" class="1" id="backing_show_certainty_while_steering_1" name="backing_show_certainty_while_steering" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">
                                                                            2
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->backing_show_certainty_while_steering == '2')
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
                                                                                <input type="radio" class="2" id="backing_show_certainty_while_steering_2" name="backing_show_certainty_while_steering" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div>

                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_continuall"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
                                                                        <label class="radio-inline">
                                                                            1
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->backing_continually_uses_mirror == '1')
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
                                                                                <input type="radio" class="1" id="backing_continually_uses_mirror_1" name="backing_continually_uses_mirror" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                        <label class="radio-inline">
                                                                            2
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->backing_continually_uses_mirror == '2')
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
                                                                                <input type="radio" class="2" id="backing_continually_uses_mirror_2" name="backing_continually_uses_mirror" value="2"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div>
                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_maintainpr"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
                                                                        <label class="radio-inline">
                                                                            1
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->backing_maintain_proper_seed == '1')
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
                                                                                <input type="radio" class="1" id="backing_maintain_proper_seed_1" name="backing_maintain_proper_seed" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div>
                                                                <div>
                                                                    <div class="col-md-12">
                                                                        <?= $strings2["drivereval_completein"]; ?>
                                                                    </div>
                                                                    <div class="col-md-12 radio-list">
                                                                        <label class="radio-inline">
                                                                            1
                                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->backing_complete_reasonable_time_fashion == '1')
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
                                                                                <input type="radio" class="1" id="backing_complete_reasonable_time_fashion_1" name="backing_complete_reasonable_time_fashion" value="1"/> 
                                                                                <?php
                                                                            }
                                                                             ?> 
                                                                            </label>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div>


                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                                    </div>

                                                        <div class="clearfix"></div>
                                                        </div>
                                                        <hr />

                                                
                                                

                                                <div class="form-group row">

													<div class="col-md-4">
                                                        <label class="control-label"><?= $strings2["drivereval_totalscore"]; ?> </label>
														<input type="text" id="total_score" class="form-control" name="total_score"  <?php if(!$did){?>value="0"<?php }else{?> value="<?php if(isset($deval_detail) && $deval_detail->total_score) {echo $deval_detail->total_score;}?>"<?php }?>/>
														
													</div>


													<div class="col-md-4">
                                                        <label class="control-label"><?= $strings2["drivereval_autoshifta"]; ?> </label>
														<input type="text" class="form-control" name="auto_shift" value="<?php if(isset($deval_detail) && $deval_detail->auto_shift) {echo $deval_detail->auto_shift;}?>"/>
														
													</div>

													<div class="col-md-4">
                                                        <label class="control-label"><?= $strings2["drivereval_manualshif"]; ?></label>
														<input type="text" class="form-control" name="manual" value="<?php if(isset($deval_detail) && $deval_detail->manual) {echo $deval_detail->manual;}?>"/>
														
													</div>
												</div>

                                                <div class="form-group row">
													<p class="center col-md-12 fontRed"><?= $strings2["drivereval_thetotalsc"]; ?></p>
												</div>
                                                <hr />
                                                <div class="form-group row">
                                                    <p class="control-label col-md-6"><strong><?= $strings2["drivereval_summary"]; ?></strong></p>
                                                </div>
                                                <div class="form-group row">
													<label class="control-label col-md-4"><?= $strings2["drivereval_rec4hire"]; ?> 
													</label>
													<div class="col-md-8 radio-list">
                                                        <div class="checkbox-list col-md-3 nopad">
															<label class="radio-inline">
                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->recommended_for_hire == '1')
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
                                                                                <input type="radio" id="recommended_for_hire_1" name="recommended_for_hire" value="1"/>
                                                                                <?php
                                                                            }
                                                                             ?> 
															 <?= $strings["dashboard_affirmative"]; ?> </label>
                                                            </div>
                                                            <div class="checkbox-list col-md-3 nopad">
															<label class="radio-inline">
															 <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->recommended_for_hire == '0')
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
                                                                                <input type="radio" id="recommended_for_hire_2" checked="" name="recommended_for_hire" value="0"/>
                                                                                <?php
                                                                            }
                                                                             ?>
                                                                <?= $strings["dashboard_negative"]; ?> </label>
														</div>
														<div id="form_payment_error">
														</div>
													</div>
												</div>
                                                <div class="form-group row">
													<label class="control-label col-md-4"><?= $strings2["drivereval_rec4full"]; ?> 
													</label>
													<div class="col-md-8 radio-list">
														<div class="checkbox-list col-md-3 nopad">
															<label class="radio-inline">
                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->recommended_full_trainee == '1')
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
                                                                                <input type="radio" id="recommended_full_trainee_1" name="recommended_full_trainee" value="1"/>
                                                                                <?php
                                                                            }
                                                                             ?>
                                                                <?= $strings["dashboard_affirmative"]; ?> </label></div><div class="checkbox-list col-md-3 nopad">
															<label class="radio-inline">
                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->recommended_full_trainee == '0')
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
                                                                                <input type="radio" id="recommended_full_trainee_0" checked name="recommended_full_trainee" value="0"/>
                                                                                <?php
                                                                            }
                                                                             ?>
                                                                <?= $strings["dashboard_negative"]; ?> </label>
														</div>
														<div id="form_payment_error">
														</div>
													</div>
												</div>
                                                <div class="form-group row">
													<label class="control-label col-md-4"><?= $strings2["drivereval_rec4fire"]; ?> 
													</label>
													<div class="col-md-8 radio-list">
                                                        <div class="checkbox-list col-md-3 nopad">
															<label class="radio-inline">
                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->recommended_fire_hire_trainee == '1')
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
                                                                                <input type="radio" id="recommended_fire_hire_trainee_1" name="recommended_fire_hire_trainee" value="1"/>
                                                                                <?php
                                                                            }
                                                                             ?>
                                                                <?= $strings["dashboard_affirmative"]; ?> </label></div><div class="checkbox-list col-md-3 nopad">
															<label class="radio-inline">
                                                             <?php 
                                                                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                                                            {
                                                                                if(isset($deval_detail) && $deval_detail->recommended_fire_hire_trainee == '0')
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
                                                                                <input type="radio" id="recommended_fire_hire_trainee_0" checked name="recommended_fire_hire_trainee" value="0"/>
                                                                                <?php
                                                                            }
                                                                             ?>
                                                                <?= $strings["dashboard_negative"]; ?> </label>
														</div>
														<div id="form_payment_error">
														</div>
													</div>
												</div>
                                                
                                                <div class="form-group row">
													<label class="control-label col-md-4"><?= $strings2["drivereval_comments"]; ?> 
													</label>
													<div class="col-md-6">
														<textarea  placeholder="" class="form-control" name="comments" style="height:140px"><?php if(isset($deval_detail) && $deval_detail->comments) {echo $deval_detail->comments;}?></textarea>
														
													</div>
												</div>
                                                <div class="clearfix"></div>
                                                <?php if($this->request->params['controller']!='Documents' && $this->request->params['controller']!='ClientApplication'){?>
                                                <div class="allattach">
                                                <?php
                                                        if(!isset($sub['de_at']))//THIS SHOULD BE USING FILELIST.PHP!!!!!
                                                        {
                                                            $sub['de_at'] = array();
                                                        }
                                                        
                                                        if(!count($sub['de_at'])){?>
                                                <div class="form-group row" style="display:block;margin-top:5px; margin-bottom: 5px;">
                                                    <label class="control-label col-md-4"><?= $strings2["file_attachfile"]; ?>: </label>
                                                    <div class="col-md-8">
                                                    <input type="hidden" class="road1" name="attach_doc[]" />
                                                    <a href="#" id="road1" class="btn btn-primary"><?= $strings["forms_browse"]; ?></a> <span class="uploaded"></span>
                                                    </div>
                                                   </div>
                                                   <?php }?>
                                                  <div class="form-group row">
                                                    <div id="more_driver_doc" data-road="<?php if(count($sub['de_at']))echo count($sub['de_at']);else echo '1';?>">
                                                       <?php
                                                       
                                                        if(count($sub['de_at']))//THIS SHOULD BE USING FILELIST.PHP!!!!!!!!!!!!
                                                        {
                                                            //die('there');
                                                            $at=0;
                                                            foreach($sub['de_at'] as $pa)
                                                            {
                                                                if($pa->attachment){
                                                                $at++;
                                                                ?>
                                                                <div class="del_append_driver"><label class="control-label col-md-4">Attach File: </label><div class="col-md-6 pad_bot"><input type="hidden" class="road<?php echo $at;?>" name="attach_doc[]" value="<?php echo $pa->attachment;?>" /><a href="#" id="road<?php echo $at;?>" class="btn btn-primary"><?= $strings["forms_browse"]; ?></a> <?php if($at>1){?><a  href="javascript:void(0);" class="btn btn-danger" id="delete_driver_doc" onclick="$(this).parent().remove();">Delete</a><?php }?>
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
                                                                    fileUpload('road<?php echo $at;?>');
                                                                });
                                                                </script>
                                                                <?php
                                                            }}
                                                        }
                                                        ?> 
                                                    </div>
                                                    <div class="col-md-8">
                                                    </div>
                                                  </div>
                                                  
                                                  <div class="form-group row">
                                                    <div class="col-md-4">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <a href="javascript:void(0);" class="btn btn-primary" id="add_more_driver_doc"><?= $strings["forms_addmore"]; ?></a>
                                                    </div>
                                                  </div>
                                                  </div>
                                                  <?php }?>

</form>
                                                    
<div class="clearfix"></div>
 <script>
    $(function(){
        $('#firstcheck input[type="checkbox"]').change(function(){
            if(!$('#total1').val())
            {
                var total1 = 0;
            }
            else
            var total1 = parseInt($('#total1').val());
            if($(this).is(':checked'))
            {
                total1 = total1+1;
                $('#total1').val(total1);
                $('#total_score').val(parseInt($('#total_score').val())+1);
            }
            else
            {
                total1 = total1-1;
                $('#total1').val(total1);
                $('#total_score').val(parseInt($('#total_score').val())-1);
            }
            
        });
        
        $('.scores input[type="radio"]').change(function(){
            total2 = 0;
            $('#total_score').val($('#total1').val());
            $('.scores input[type="radio"]').each(function(){
                if($(this).is(':checked')) {
                    total2 = total2+parseInt($(this).attr('class'));
                }
            });
            $('#total_score').val(total2 + parseInt( $('#total1').val() ) );
        });
        
        
        <?php
        if(!isset($sub['de_at']))
        $sub['de_at'] = array();
            if(($this->request->params['action']=='addorder' || $this->request->params['action']=='add') && !count($sub['de_at'])) {
                echo "fileUpload('road1');";
            }
        ?>
//        
       $('#add_more_driver_doc').click(function(){
        var count = $('#more_driver_doc').data('road');
        $('#more_driver_doc').data('road',parseInt(count)+1);
        $('#more_driver_doc').append('<div class="del_append_driver"><label class="control-label col-md-4"></label><div class="col-md-8 pad_bot"><input type="hidden" class="road'+$('#more_driver_doc').data('road')+'" name="attach_doc[]" /><a href="#" id="road'+$('#more_driver_doc').data('road')+'" class="btn btn-primary"><?= addslashes($strings["forms_browse"]); ?></a> <a  href="javascript:void(0);" class="btn btn-danger" id="delete_driver_doc">Delete</a> <span class="uploaded"></span></div></div><div class="clearfix"></div>');
        fileUpload('road'+$('#more_driver_doc').data('road'));
       }); 
       
       $('#delete_driver_doc').live('click',function(){
         var count = $('#more_driver_doc').data('road');
        $('#more_driver_doc').data('road',parseInt(count)-1);
            $(this).closest('.del_append_driver').remove();
       });
       
        //$("#test2").jqScribble();
    });
    	
</script>     
                                         