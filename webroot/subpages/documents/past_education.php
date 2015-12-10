<?php
//if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/documents/past_education.php #INC145</span>"; }
if(isset($dx)){ echo '<h3>' . $dx->title . '</h3>'; }?>
<div id="toremove">
<div class="clearfix"></div>
<hr />
 <div class="form-group row">
                    <p class="control-label col-md-12" style="font-weight: bold;">Past Education  <?php if(isset($_GET['cou']))echo $_GET['cou'];?></h4>
                    </div>
                    <div class="form-group row">
                    <label class="control-label col-md-3">School/College Name </label>
                    <div class="col-md-9">
                    <input type="text" class="form-control" name="college_school_name[]" />
                    </div>
                    </div>

                    <div class="form-group row">
                    <label class="control-label col-md-3">Address </label>
                    <div class="col-md-9">
                    <input type="text" class="form-control" name="address[]" />
                    </div>
                    </div>

                    <div class="form-group row">
                                <label class="control-label col-md-3">Supervisor's Name</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="supervisior_name[]" />
                                </div>
                                <label class="control-label col-md-3">Phone #</label>
                                <div class="col-md-3">
                                    <input type="text" role="phone" class="form-control" name="supervisior_phone[]" />
                                </div>
                    </div>


                    <div class="form-group row">
                                <label class="control-label col-md-3">Supervisor's Email</label>
                                <div class="col-md-3">
                                    <input type="text" role="email" class="form-control email1" name="supervisior_email[]" />
                                </div>
                                <label class="control-label col-md-3">Secondary Email</label>
                                <div class="col-md-3">
                                    <input type="text" role="email" class="form-control email1" name="supervisior_secondary_email[]" />
                                </div>
                    </div>

                     <div class="form-group row">
                                <label class="control-label col-md-3">Education Start Date</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control date-picker" name="education_start_date[]" />
                                </div>
                                <label class="control-label col-md-3">Education End Date</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control date-picker" name="education_end_date[]" />
                                </div>
                    </div>

                     <div class="form-group row">
                                <label class="control-label col-md-3">Claims with this Tutor</label>
                                <div class="col-md-3">
                                    &nbsp;&nbsp;<input type="radio" name="claim_tutor[]" value="1" class="refreshme"/>&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="claim_tutor[]" value="0" class="refreshme"/>&nbsp;&nbsp;&nbsp;&nbsp;No</td>
                                </div>
                                <label class="control-label col-md-3">Date Claims Occured</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control date-picker" name="date_claims_occur[]" />
                                </div>
                    </div>

                    <div class="form-group row">
                                <label class="control-label col-md-3">Education history confirmed by (Verifier Use Only):</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="education_history_confirmed_by[]" />
                                </div>
                    </div>

                            <div class="form-group row">

                                <label class="col-md-3 control-label">Highest grade completed: </label>
                                <div class="col-md-3">
                                <select name="highest_grade_completed[]" class="form-control">
                                <?php
                                for($i=1;$i<=8;$i++)
                                {
                                    ?>
                                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                    <?php
                                }
                                ?>
                                </select>
                                </div>
                                <label class="col-md-3 control-label">High School: </label>
                                <div class="col-md-3">
                                    <select name="high_school[]" class="form-control">
                                    <?php
                                    for($i=1;$i<=4;$i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i?>"><?php echo $i;?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="col-md-3 control-label">College: </label>
                                <div class="col-md-3">
                                    <select name="college[]" class="form-control">
                                    <?php
                                    for($i=1;$i<=4;$i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i?>"><?php echo $i;?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                </div>
                                <label class="col-md-3 control-label">Last School attended: </label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="last_school_attended[]" />
                                </div>
                            </div>
                        <div class="form-group row">
                                <label class="col-md-3 control-label">Did the employee have any safety or performance issues?</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="performance_issue[]" ></textarea>
                                </div>
                            </div>
                        <div class="form-group row">
                                <!--<label class="col-md-3 control-label" style="display: none;">Signature:</label>
                                <div class="col-md-3">-->
                                <input type="text" class="form-control" style="display: none;" name="signature[]"/>
                                <!--</div>-->
                        <label class="col-md-3 control-label">Date:</label>
                        <div class="col-md-3">
                        <input type="text" class="form-control date-picker" name="date_time[]" />
                        </div>

    </div>
<div class="delete">
    <a href="javascript:void(0);" class="btn red" id="delete">Delete</a>
</div>
  </div>

