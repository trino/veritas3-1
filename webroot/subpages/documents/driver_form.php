<?php $strings2 = CacheTranslations($language, array("verifs_%", "tasks_date", "file_attachfile", "file_download"), $settings, False); ?>
<h3 style="float: left;">Create Driver</h3>
<div class="row">
<div class="form-group row col-md-12 splitcols" ID="GNDN">
    
    <input type="hidden" name="document_type" value="driver_form" />
    <input type="hidden" name="client_id" value="<?php if(isset($cid))echo $cid;?>" />
    <?php if($this->request->controller !='ClientApplication' && $this->request->controller !='Orders'){?>
    <div class="col-md-4"><label class="control-label required notonclient">Your Username: </label>
        <input type="text" class="form-control required notonclient uname"  name="username" <?php if (isset($p->username)) { ?> value="<?php echo $p->username; ?>" <?php }?> />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required notonclient">Your <?= $strings["forms_password"]; ?>: </label>
        <input type="password" class="form-control required notonclient"  name="password"  />
        <span class="error"></span>
    </div>
    <!--div class="col-md-4"><label class="control-label"><?= $strings["forms_retypepassword"]; ?>: </label>
        <input type="text" class="form-control required" required name="password2" />
    </div-->
    <?php }?>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_firstname"]; ?>: </label>
        <input type="text" class="form-control required" required name="fname" <?php if (isset($p->fname)) { ?> value="<?php echo $p->fname; ?>" <?php }?> />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label"><?= $strings["forms_middlename"]; ?>: </label>
        <input type="text" class="form-control" name="mname" <?php if (isset($p->mname)) { ?> value="<?php echo $p->mname; ?>" <?php }?> />
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_lastname"]; ?>: </label>
        <input type="text" class="form-control required" required name="lname" <?php if (isset($p->lname)) { ?> value="<?php echo $p->lname; ?>" <?php }?> />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label"><?= $strings["forms_gender"]; ?>: </label>
        <select class="form-control" name="gender" />
            <option value="Male" <?php if (isset($p->gender) && $p->gender=='Male') { ?>selected="selected"<?php }?>>Male</option>
            <option value="Female" <?php if (isset($p->gender) && $p->gender=='Female') { ?>selected="selected"<?php }?>>Female</option>
            <span class="error"></span>
        </select>
    </div>
    <div class="col-md-4"><label class="control-label"><?= $strings["forms_title"]; ?>: </label>
        <select class="form-control" name="title" />
            <option value="Mr." <?php if (isset($p->title) && $p->gender=='Mr.') { ?>selected="selected"<?php }?>>Mr.</option>
            <option value="Ms." <?php if (isset($p->title) && $p->gender=='Ms.') { ?>selected="selected"<?php }?>>Ms.</option>
            <option value="Mrs." <?php if (isset($p->title) && $p->gender=='Mrs.') { ?>selected="selected"<?php }?>>Mrs.</option>
        </select>
        <span class="error"></span>
    </div>

    <div class="col-md-4"><label class="control-label"><?= $strings["forms_email"]; ?>: </label>
        <input type="text" class="form-control emailz" name="email" role="email" <?php if (isset($p->email)) { ?> value="<?php echo $p->email; ?>" <?php }?>  />
        <span class="error"></span>
    </div>

    <div class="col-md-4"><label class="control-label"><?= $strings["forms_placeofbirth"]; ?>: </label>
        <input type="text" class="form-control" name="placeofbirth" <?php if (isset($p->placeofbirth)) { ?> value="<?php echo $p->placeofbirth; ?>" <?php }?>  />
        <span class="error"></span>
    </div>

    <div class="col-md-4"><label class="control-label"><?= $strings["forms_sin"]; ?>: </label>
        <input type="text" class="form-control" name="sin" role="sin" <?php if (isset($p->sin)) { ?> value="<?php echo $p->sin; ?>" <?php }?>  />
        <span class="error"></span>
    </div>

    <div class="col-md-4"><label class="control-label"><?= $strings["forms_phone"]; ?>: </label>
        <input type="text" class="form-control" name="phone" role="phone" <?php if (isset($p->phone)) { ?> value="<?php echo $p->phone; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label"><?= $strings["forms_address"]; ?>: </label>
        <input type="text" class="form-control" name="street" <?php if (isset($p->street)) { ?> value="<?php echo $p->street; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label"><?= $strings["forms_city"]; ?>: </label>
        <input type="text" class="form-control required" name="city" <?php if (isset($p->city)) { ?> value="<?php echo $p->city; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label"><?= $strings["forms_provincestate"]; ?>: </label>
        <?php
                                            if (isset($p->province))
                                                provinces("province", $p->province, '');
                                            else
                                                provinces("province", "", '');
                                        ?>
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label"><?= $strings["forms_postalcode"]; ?>: </label>
        <input type="text" class="form-control" name="postal" role="postalcode" <?php if (isset($p->postal)) { ?> value="<?php echo $p->postal; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label"><?= $strings["forms_country"]; ?>: </label>
        <input type="text" class="form-control" name="country" value="Canada" <?php if (isset($p->country)) { ?> value="<?php echo $p->country; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label"><?= $strings["forms_dateofbirth"]; ?>: </label>
        <input type="text" class="form-control dp" name="dob" <?php if (isset($p->dob)) { ?> value="<?php echo $p->dob; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_driverslicense"]; ?>: </label>
        <input type="text" class="form-control required" required name="driver_license_no" <?php if (isset($p->driver_license_no)) { ?> value="<?php echo $p->driver_license_no; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_provinceissued"]; ?>: </label>
        <?php
        if (isset($p->province))
                                                provinces("driver_province", $p->driver_province, 'required');
                                            else
                                                provinces("driver_province", "", 'required');
        ?>
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label"><?= $strings["forms_expirydate"]; ?>: </label>
        <input type="text" class="form-control dp" name="expiry_date" <?php if (isset($p->expiry_date)) { ?> value="<?php echo $p->expiry_date; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    

    
    

    
</div>
</div>
<div class="clearfix"></div>
<script>
$(function(){
   $('.dp').datepicker(); 
});
</script>

