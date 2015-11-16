<?php
 if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/documents/investigations_intake_form_benefit_claims.php #INC501</span>"; }
 $is_disabled = '';//there is no place for attachments
 if(isset($disabled)) { $is_disabled = 'disabled="disabled"'; }
 if(isset($dx)){ echo '<p>Document - ' . $dx->title . '</p>'; }
?>
<form role="form" action="<?php echo $this->request->webroot;?>documents/investigation/<?php echo $cid .'/' .$did;?>" method="post" id="form_tab<?php echo $dx->id;?>">

 <input type="hidden" class="document_type" name="document_type" value="<?php echo $dx->title;?>"/>
 <input type="hidden" name="sub_doc_id" value="<?php echo $dx->id;?>" class="sub_docs_id" id="af" />
   
 <div class="form-group col-md-12">
    <label class="control-label col-md-6">Date</label>
    <div class="col-md-6">
    <input type="text" class="form-control date-picker" name="date" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->date;}?>" />
    </div>
 </div>
 <div class="form-group col-md-12">
    <label class="control-label col-md-6">Company</label>
    <div class="col-md-6">
    <input type="text" class="form-control" name="company" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->company;}?>" />
    </div>
 </div>
 <div class="form-group col-md-12">
    <label class="control-label col-md-6">Client's Name</label>
    <div class="col-md-6">
    <input type="text" class="form-control" name="cient_name" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->cient_name;}?>" />
    </div>
 </div>
 
 <div class="form-group col-md-12">
    <label class="control-label col-md-6">Client's Business Phone Number</label>
    <div class="col-md-6">
    <input type="text" role="phone" class="form-control" name="cbpn" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->cbpn;}?>" />
    </div>
 </div>
  <div class="form-group col-md-12">
    <label class="control-label col-md-6">Client's Cellular Number</label>
    <div class="col-md-6">
    <input type="text" class="form-control" name="ccn" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->ccn;}?>" />
    </div>
 </div>
  <div class="form-group col-md-12">
    <label class="control-label col-md-6">Email Address</label>
    <div class="col-md-6">
    <input type="text" class="form-control" role="email" name="email" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->email;}?>" />
    </div>
 </div>
 <div class="form-group col-md-12">
    <label class="control-label col-md-6">Budget (If Applicable)</label>
    <div class="col-md-6">
    <input type="text" class="form-control" name="budget" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->budget;}?>" />
    </div>
 </div>
  <div class="form-group col-md-12">
    <label class="control-label col-md-6">Previous Surveillance Conducted</label>
    <div class="col-md-6">
    <input type="text" class="form-control" name="psc" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->psc;}?>" />
    </div>
 </div>
  <div class="form-group col-md-12">
    <label class="control-label col-md-6">Subject's Physical Limitation / Goal of Investigation</label>
    <div class="col-md-6">
    <input type="text" class="form-control" name="goi" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->goi;}?>" />
    </div>
 </div>
   <div class="form-group col-md-12">
    <label class="control-label col-md-6">Date of loss</label>
    <div class="col-md-6">
    <input type="text" class="form-control" name="dol" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->dol;}?>" />
    </div>
 </div>
  <div class="form-group col-md-12">
    <label class="control-label col-md-6">Subject's Name</label>
    <div class="col-md-6">
    <input type="text" class="form-control" name="s_name" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->s_name;}?>" />
    </div>
 </div>
   <div class="form-group col-md-12">
    <label class="control-label col-md-6">Subject's Date of Birth</label>
    <div class="col-md-6">
    <input type="text" class="form-control" name="subject_dob" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->subject_dob;}?>" />
    </div>
 </div>
   <div class="form-group col-md-12">
    <label class="control-label col-md-6">Subject's Address</label>
    <div class="col-md-6">
    <input type="text" class="form-control" name="s_address" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->s_address;}?>" />
    </div>
 </div>
  <div class="form-group col-md-12">
    <label class="control-label col-md-6">Subject's Secondary Address</label>
    <div class="col-md-6">
    <input type="text" class="form-control" name="ssa" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->ssa;}?>" />
    </div>
 </div>
  <div class="form-group col-md-12">
    <label class="control-label col-md-6">Subject's Work Address</label>
    <div class="col-md-6">
    <input type="text" class="form-control" name="swa" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->swa;}?>" />
    </div>
 </div>
   <div class="form-group col-md-12">
    <label class="control-label col-md-6">Subject's Telephone Number</label>
    <div class="col-md-6">
    <input type="text" class="form-control" role="phone" name="stn" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->stn;}?>" />
    </div>
 </div>
   <div class="form-group col-md-12">
    <label class="control-label col-md-6">Subject's Description</label>
    <div class="col-md-6">
    <textarea class="form-control" name="sd"><?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->sd;}?></textarea>
    </div>
 </div>
 <div class="form-group col-md-12">
    <label class="control-label col-md-6">Subject's Vehicle Information</label>
    
    <div class="col-md-6">
    <textarea class="form-control" name="svi"><?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->svi;}?></textarea>
    </div>
    
 </div>
  <div class="form-group col-md-12">
    <label class="control-label col-md-6">Subject's Driver License Number</label>
    <div class="col-md-6">
    <input type="text" class="form-control" name="sdln" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->sdln;}?>" />
    </div>
 </div>
 <div class="form-group col-md-12">
    <label class="control-label col-md-6">Subject's Partner / Children</label>
    <div class="col-md-6">
    <input type="text" class="form-control" name="sp" value="<?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->sp;}?>" />
    </div>
 </div>
  <div class="form-group col-md-12">
    <label class="control-label col-md-6">Instructions - Dates, Times, Location of Surveillance, etc.</label>
    <div class="col-md-6">
    <textarea class="form-control" name="instruction"><?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->instruction;}?></textarea>
    </div>
 </div>
 
 
  <div class="form-group col-md-12">
    <label class="control-label col-md-6">Social Media Search / Surveillance Offered</label>
    <div class="col-md-6">
    <textarea class="form-control" name="sms"><?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->sms;}?></textarea>
    </div>
 </div>
  <div class="form-group col-md-12">
    <label class="control-label col-md-6">Additional Requests / Miscellaneous Information</label>
    <div class="col-md-6">
    <textarea class="form-control" name="ar"><?php if(isset($investigations_intake_form_benefit_claims)){ echo $investigations_intake_form_benefit_claims->ar;}?></textarea>
    </div>
 </div>
 <?php if($this->request->params['controller']!='Documents'){?>
 <div class="addattachment23 form-group col-md-12"></div>
 <?php }?>
 <div class="clearfix"></div>
 
 </form>
 <script>
 $(function(){
    <?php
        if(isset($disabled))
        {
    ?>
           $('#form_tab23 input').attr('disabled','disabled');         
    <?php }   ?>
 })
 
 </script>