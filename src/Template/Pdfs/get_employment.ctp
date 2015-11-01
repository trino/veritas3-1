        	<div class="form-body">
                <?php
                $counter=0;
                if(isset($detail['consent']) && $detail['consent'])
                {
                    foreach($detail['consent'] as $emp)
                    {
                        $counter++;
                        if($counter!=1)
                        {
                            if($counter==2)
                            {
                                ?>
                                <div id="more_div">
                                <?php
                            }
                            ?>
                                    <div id="toremove">
                            <?php
                        }
                        ?>
                        
                        <div class="form-group col-md-12">
                
                                <h4 class="control-label col-md-12">Past Employer</h4>
                        </div>
                
                               <div class="form-group col-md-12">
                                <label class="control-label col-md-3">Company Name</label>
                                <div class=" col-md-9">
                                <?php echo $emp->company_name;?>
                                </div>
                                </div>
                                <div class="form-group col-md-12">
                                <label class="control-label col-md-3">Address</label>
                                <div class="col-md-3">
                                    <?php echo $emp->address;?>
                                </div>
                                <label class="control-label col-md-3">City</label>
                                <div class="col-md-3">
                                    <?php echo $emp->city;?>
                                </div>
                                </div>
                                <div class="form-group col-md-12">
                                <label class="control-label col-md-3">State/Province</label>
                                <div class="col-md-3">
                                    <?php echo $emp->state_province;?>
                                </div>
                                <label class="control-label col-md-3">Country</label>
                                <div class="col-md-3">
                                <?php echo $emp->country;?>
                                </div>
                                </div>
                                <div class="form-group col-md-12">
                                <label class="control-label col-md-3">Supervisor's Name:</label>
                                <div class="col-md-3">
                                <?php echo $emp->supervisor_name;?>
                                </div>
                               <label class="control-label col-md-3">Phone #:</label>
                               <div class="col-md-3">
                               <?php echo $emp->supervisor_phone;?>
                               </div>
                               </div>
                               
                               <div class="form-group col-md-12">
                               <label class="control-label col-md-3">Supervisor's Email:</label>
                               <div class="col-md-3">
                               <?php echo $emp->supervisor_email;?>
                               </div>
                               <label class="control-label col-md-3">Secondary Email:</label>
                               <div class="col-md-3">
                               <?php echo $emp->supervisor_secondary_email;?>
                               </div>
                               </div>
                               
                               <div class="form-group col-md-12">
                                <label class="control-label col-md-3">Employment Start Date:</label>
                                <div class="col-md-3">
                                <?php echo $emp->employment_start_date;?>
                                </div>
                                <label class="control-label col-md-3">Employment End Date:</label>
                                <div class="col-md-3">
                                <?php echo $emp->employment_end_date;?>
                                </div>
                                </div>
                                <div class="form-group col-md-12">
                                <label class="control-label col-md-3">Claims with this Employer:</label>
                                <div class="col-md-3">
                                <?php if($emp->claims_with_employer == 1){ echo "&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }?>
                                        <?php if($emp->claims_with_employer == 0){echo "&nbsp;&nbsp;&nbsp;&nbsp;No";}?>
                                </div>
                                 <label class="control-label col-md-3">Date Claims Occured:</label>
                                 <div class="col-md-3">
                                 <?php echo $emp->claims_recovery_date;?>
                                 </div>
                                 </div>
                                 
                                 <div class="form-group col-md-12">
                                <label class="control-label col-md-6">Employment history confirmed by (Verifier Use Only):</label>
                                <div class="col-md-6">
                                <?php echo $emp->emploment_history_confirm_verify_use;?>
                                </div>
                                </div>
                                
                                <div class="form-group col-md-12">
                                <label class="control-label col-md-3">US DOT MC/MX#:</label>
                                <div class="col-md-3">
                                <?php echo $emp->us_dot;?>
                                </div>
                                <label class="control-label col-md-3">Signature:</label>
                                <div class="col-md-3">
                                <?php echo $emp->signature;?>
                                </div>
                                </div>
                                
                                <div class="form-group col-md-12">
                                <label class="control-label col-md-3">Date:</label>
                                <div class="col-md-9">
                                <?php echo $emp->signature_datetime;?>
                                </div>
                                </div>
                                <div class="form-group col-md-12">
                                            <label class="control-label col-md-3">Equipment Operated : </label>
                                            <div class="col-md-9">
                                                <?php if($emp->equipment_vans == 1){?>&nbsp;Vans&nbsp;<?php }?>
                                                    <?php if($emp->equipment_reefer == 1){?>&nbsp;Reefers&nbsp;<?php }?>
                                                    <?php if($emp->equipment_decks == 1){?>&nbsp;Decks&nbsp;<?php }?>
                                                    <?php if($emp->equipment_super == 1){?>&nbsp;Super B's&nbsp;<?php }?>
                                                    <?php if($emp->equipment_straight_truck == 1){?>&nbsp;Straight Truck&nbsp;<?php }?>
                                                    <?php if($emp->equipment_others == 1){?>&nbsp;Others<?php }?>
                                </div>
                                </div>
                                <div class="form-group col-md-12">
                                <label class="control-label col-md-3">Driving Experience : </label>
                                <div class="col-md-9">
                                    <?php if($emp->driving_experince_local == 1){?>&nbsp;Local&nbsp;<?php }?>
                                    <?php if($emp->driving_experince_canada == 1){?>&nbsp;Canada&nbsp;<?php }?>
                                    <?php if($emp->driving_experince_canada_rocky_mountains == 1){?>&nbsp;Canada : Rocky Mountains&nbsp;<?php }?>
                                    <?php if($emp->driving_experince_usa == 1){?>&nbsp;USA&nbsp;<?php }?>
                                </div>
                
                </div>
                <?php
                    }
                    
                }
                
                ?>
        
     
          <br />
<strong>Attachments</strong>
<p>&nbsp;</p>
<?php
if($_SERVER['SERVER_NAME']=='localhost')
$initials = 'http://localhost';
else
$initials = 'http://isbmeereports.com';
if($att)
{
    foreach($att as $a)
    {
        ?>
        <img src="<?php echo $initials.$this->request->webroot;?>attachments/<?php echo $a->attach_doc;?>" /><br /><br />
        <?php
    }
}
?>

          
          
          <div class="clearfix"></div>
        </div>

