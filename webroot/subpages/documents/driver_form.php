<?php
    if($this->request->session()->read('debug')){  echo "<span style ='color:red;'>subpages/documents/driver_form.php #INC???</span>";}
    $strings2 = CacheTranslations($language, array("verifs_%", "tasks_date", "file_attachfile", "file_download"), $settings, False);

    $requiredfields = array("fname", "lname", "driver_province");
    if(isset($_GET["forms"])){
        $forms = explode(",", $_GET["forms"]);
        requiredfields($requiredfields, $forms, 79,     array("driver_license_no"));
        requiredfields($requiredfields, $forms, 32,     array("driver_license_no", "dob", "gender", "street", "city", "province"));
        requiredfields($requiredfields, $forms, 31,     array("driver_license_no", "gender", "street", "city", "province", "postal"));
        requiredfields($requiredfields, $forms, 1,      array("driver_license_no", "dob", "driver_province"));
        requiredfields($requiredfields, $forms, 14,     array("driver_license_no", "dob", "driver_province"));
        requiredfields($requiredfields, $forms, 72,     array("driver_license_no", "dob", "driver_province"));
        requiredfields($requiredfields, $forms, 77,     array("driver_license_no", "dob", "driver_province"));
        requiredfields($requiredfields, $forms, 78,     array("email", "driver_province"));
        requiredfields($requiredfields, $forms, 1650,   array("dob", "driver_province"));
        requiredfields($requiredfields, $forms, 1627,   array("dob", "driver_province"));
        requiredfields($requiredfields, $forms, 1603,   array("dob", "driver_province", "gender", "mname"));
    } else {
        $requiredfields = array_merge($requiredfields, array("driver_license_no", "mname", "dob", "gender", "email", "street", "province", "city", "postal"));
    }

    function requiredfields(&$requiredfields, $forms, $product, $fields){
        if(in_array($product, $forms)){
            $requiredfields = array_unique(array_merge($requiredfields, $fields));
        }
    }

    function isitrequired($requiredfields, $field){
        if(in_array($field, $requiredfields)){
            return ' required" required ';
        }
        return '" ';
    }

    //debug($requiredfields);
?>
<h3 style="float: left;"><?= $strings2["verifs_driverinfo"]; ?></h3>
<div class="row">
<div class="form-group row col-md-12 splitcols" ID="GNDN">

    <input type="hidden" name="document_type" value="driver_form" />
    <input type="hidden" name="client_id" value="<?php if(isset($cid))echo $cid;?>" />
    <?php if($this->request->controller !='ClientApplication' && $this->request->controller !='Orders'){?>
    <div class="col-md-4"><label class="control-label required notonclient"><?= $strings2["verifs_yourusername"]; ?>: </label>
        <input type="text" class="form-control required notonclient uname"  name="username" <?php if (isset($p->username)) { ?> value="<?php echo $p->username; ?>" <?php }?> />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label required notonclient"><?= $strings["verifs_yourpassword"]; ?>: </label>
        <input type="password" class="form-control required notonclient"  name="password"  />
        <span class="error"></span>
    </div>
    <!--div class="col-md-4"><label class="control-label"><?= $strings["forms_retypepassword"]; ?>: </label>
        <input type="text" class="form-control required" required name="password2" />
    </div-->
    <?php }?><div class="clearfix"></div>

    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "fname"); ?>><?= $strings["forms_firstname"]; ?>: </label>
        <input type="text" class="form-control <?= isitrequired($requiredfields, "fname"); ?> name="fname" <?php if (isset($p->fname)) { ?> value="<?php echo $p->fname; ?>" <?php }?> />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label<?= isitrequired($requiredfields, "mname"); ?>><?= $strings["forms_middlename"]; ?>: </label>
        <input type="text" class="form-control <?= isitrequired($requiredfields, "mname"); ?> name="mname" <?php if (isset($p->mname)) { ?> value="<?php echo $p->mname; ?>" <?php }?> />
    </div>
    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "lname"); ?>><?= $strings["forms_lastname"]; ?>: </label>
        <input type="text" class="form-control <?= isitrequired($requiredfields, "lname"); ?> name="lname" <?php if (isset($p->lname)) { ?> value="<?php echo $p->lname; ?>" <?php }?> />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label<?= isitrequired($requiredfields, "gender"); ?>><?= $strings["forms_gender"]; ?>: </label>
        <select class="form-control <?= isitrequired($requiredfields, "gender"); ?> name="gender" />
            <option value="Male" <?php if (isset($p->gender) && $p->gender=='Male') { ?>selected="selected"<?php }?>>Male</option>
            <option value="Female" <?php if (isset($p->gender) && $p->gender=='Female') { ?>selected="selected"<?php }?>>Female</option>
            <span class="error"></span>
        </select>
    </div>
    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "title"); ?>><?= $strings["forms_title"]; ?>: </label>
        <select class="form-control <?= isitrequired($requiredfields, "title"); ?> name="title" />
            <option value="Mr." <?php if (isset($p->title) && $p->gender=='Mr.') { ?>selected="selected"<?php }?>>Mr.</option>
            <option value="Ms." <?php if (isset($p->title) && $p->gender=='Ms.') { ?>selected="selected"<?php }?>>Ms.</option>
            <option value="Mrs." <?php if (isset($p->title) && $p->gender=='Mrs.') { ?>selected="selected"<?php }?>>Mrs.</option>
        </select>
        <span class="error"></span>
    </div>

    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "email"); ?>><?= $strings["forms_email"]; ?>: </label>
        <input type="text" class="form-control emailz <?= isitrequired($requiredfields, "email"); ?> name="email" role="email" <?php if (isset($p->email)) { ?> value="<?php echo $p->email; ?>" <?php }?>  />
        <span class="error"></span>
    </div>

    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "placeofbirth"); ?>><?= $strings["forms_placeofbirth"]; ?>: </label>
        <input type="text" class="form-control <?= isitrequired($requiredfields, "placeofbirth"); ?> name="placeofbirth" <?php if (isset($p->placeofbirth)) { ?> value="<?php echo $p->placeofbirth; ?>" <?php }?>  />
        <span class="error"></span>
    </div>

    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "sin"); ?>><?= $strings["forms_sin"]; ?>: </label>
        <input type="text" class="form-control <?= isitrequired($requiredfields, "sin"); ?> name="sin" role="sin" <?php if (isset($p->sin)) { ?> value="<?php echo $p->sin; ?>" <?php }?>  />
        <span class="error"></span>
    </div>

    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "phone"); ?>><?= $strings["forms_phone"]; ?>: </label>
        <input type="text" class="form-control <?= isitrequired($requiredfields, "phone"); ?> name="phone" role="phone" <?php if (isset($p->phone)) { ?> value="<?php echo $p->phone; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "street"); ?>><?= $strings["forms_address"]; ?>: </label>
        <input type="text" class="form-control <?= isitrequired($requiredfields, "street"); ?> name="street" <?php if (isset($p->street)) { ?> value="<?php echo $p->street; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "city"); ?>><?= $strings["forms_city"]; ?>: </label>
        <input type="text" class="form-control <?= isitrequired($requiredfields, "city"); ?> name="city" <?php if (isset($p->city)) { ?> value="<?php echo $p->city; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "province"); ?>><?= $strings["forms_provincestate"]; ?>: </label>
        <?php
            if (isset($p->province)) {
                provinces("province", $p->province, isitrequired($requiredfields, "province"));
            }else {
                provinces("province", "", isitrequired($requiredfields, "province"));
            }
        ?>
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "postal"); ?>><?= $strings["forms_postalcode"]; ?>: </label>
        <input type="text" class="form-control <?= isitrequired($requiredfields, "postal"); ?> name="postal" role="postalcode" <?php if (isset($p->postal)) { ?> value="<?php echo $p->postal; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "country"); ?>><?= $strings["forms_country"]; ?>: </label>
        <input type="text" class="form-control <?= isitrequired($requiredfields, "country"); ?> name="country" value="Canada" <?php if (isset($p->country)) { ?> value="<?php echo $p->country; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "dob"); ?>><?= $strings["forms_dateofbirth"]; ?>: </label>
        <input type="text" class="form-control dp <?= isitrequired($requiredfields, "dob"); ?> name="dob" <?php if (isset($p->dob)) { ?> value="<?php echo $p->dob; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "driver_license_no"); ?>><?= $strings["forms_driverslicense"]; ?>: </label>
        <input type="text" class="form-control <?= isitrequired($requiredfields, "driver_license_no"); ?> required name="driver_license_no" <?php if (isset($p->driver_license_no)) { ?> value="<?php echo $p->driver_license_no; ?>" <?php }?>  />
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "driver_province"); ?>><?= $strings["forms_provinceissued"]; ?>: </label>
        <?php
            if (isset($p->province)) {
                provinces("driver_province", $p->driver_province, isitrequired($requiredfields, "driver_province"));
            }else {
                provinces("driver_province", "", isitrequired($requiredfields, "driver_province"));
            }
        ?>
        <span class="error"></span>
    </div>
    <div class="col-md-4"><label class="control-label <?= isitrequired($requiredfields, "expiry_date"); ?>><?= $strings["forms_expirydate"]; ?>: </label>
        <input type="text" class="form-control dp <?= isitrequired($requiredfields, "expiry_date"); ?> name="expiry_date" <?php if (isset($p->expiry_date)) { ?> value="<?php echo $p->expiry_date; ?>" <?php }?>  />
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

