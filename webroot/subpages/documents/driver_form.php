<?php $strings2 = CacheTranslations($language, array("verifs_%", "tasks_date", "file_attachfile", "file_download"), $settings, False); ?>
<div class="form-group row col-md-12 splitcols" ID="GNDN">
    
    <input type="hidden" name="document_type" value="driver_form" />
    <input type="hidden" name="client_id" value="<?php if(isset($cid))echo $cid;?>" />
    <div class="col-md-4"><label class="control-label required notonclient">Your Username: </label>
        <input type="text" class="form-control required notonclient" required name="username" />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required notonclient">Your <?= $strings["forms_password"]; ?>: </label>
        <input type="password" class="form-control required notonclient" required name="password" />
        <span class="error"></span>
    </div>
    <!--div class="col-md-4"><label class="control-label"><?= $strings["forms_retypepassword"]; ?>: </label>
        <input type="text" class="form-control required" required name="password2" />
    </div-->
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_firstname"]; ?>: </label>
        <input type="text" class="form-control required" required name="fname" />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label"><?= $strings["forms_middlename"]; ?>: </label>
        <input type="text" class="form-control" name="mname" />
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_lastname"]; ?>: </label>
        <input type="text" class="form-control required" required name="lname" />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_gender"]; ?>: </label>
        <select class="form-control required" required name="gender" />
            <option>Male</option>
            <option>Female</option>
            <span class="error"></span>
        </select>
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_title"]; ?>: </label>
        <select class="form-control required" required name="title" />
            <option>Mr.</option>
            <option>Ms.</option>
            <option>Mrs.</option>
        </select>
        <span class="error"></span>
    </div>

    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_email"]; ?>: </label>
        <input type="text" class="form-control required" required name="email" role="email" />
        <span class="error"></span>
    </div>

    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_placeofbirth"]; ?>: </label>
        <input type="text" class="form-control required" required name="placeofbirth" />
        <span class="error"></span>
    </div>

    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_sin"]; ?>: </label>
        <input type="text" class="form-control required" required name="sin" role="sin" />
        <span class="error"></span>
    </div>

    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_phone"]; ?>: </label>
        <input type="text" class="form-control required" required name="phone" role="phone" />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_address"]; ?>: </label>
        <input type="text" class="form-control required" required name="street" />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_city"]; ?>: </label>
        <input type="text" class="form-control required" required name="city" />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_provincestate"]; ?>: </label>
        <?php provinces("province", "",  true); ?>
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_postalcode"]; ?>: </label>
        <input type="text" class="form-control required" required name="postal" role="postalcode" />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_country"]; ?>: </label>
        <input type="text" class="form-control required" required name="country" value="Canada"/>
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_dateofbirth"]; ?>: </label>
        <input type="text" class="form-control datepicker date-picker required" required name="dob" />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_driverslicense"]; ?>: </label>
        <input type="text" class="form-control required" required name="driver_license_no" />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_provinceissued"]; ?>: </label>
        <?php provinces("driver_province", "", true); ?>
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required"><?= $strings["forms_expirydate"]; ?>: </label>
        <input type="text" class="form-control datepicker date-picker required" required name="expiry_date" />
        <span class="error"></span>
    </div>
    

    
    

    
</div>
<div class="clearfix"></div>

