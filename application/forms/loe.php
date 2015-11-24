<?php
    $strings2 = CacheTranslations($language, array("verifs_%", "tasks_date", "file_attachfile", "file_download"), $settings, False);
    $datetype = "text";
?>
<STYLE>
    .left15{
        position: relative;
        left: -15px;
    }
</STYLE>
<div class="form-group row">
    <center><h3 class="col-md-12">Letter of Experience</h3></center>
    <H2> <?= $strings["verifs_referencenum"]; ?>: 1 </H2>
    <h3 class="col-md-12"><?= $strings2["verifs_pastemploy"]; ?></h3>
</div>
<div class="gndn">
        <div class="form-group row">
            <div class=" col-md-12">
                <font color="red" style="display:none;">A minimum of 2 of references must be filled out. Click "<?= $strings["forms_addmore"]; ?>" to add another form</font>
            </div>

            <label class="control-label  col-md-12"></label>
            <label class="control-label required col-md-3"><?= $strings["forms_companyname"]; ?>:</label>
            <div class=" col-md-9">
                <input type="text" required class="form-control required" name="company_name[]"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="control-label required col-md-3"><?= $strings["forms_address"]; ?>:</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="address[]"/>
            </div>

            <label class="control-label required col-md-3"><?= $strings["forms_city"]; ?>:</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="city[]"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="control-label required col-md-3"><?= $strings["forms_provincestate"]; ?>:</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="state_province[]"/>
            </div>

            <label class="control-label required col-md-3"><?= $strings["forms_country"]; ?>:</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="country[]"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label required col-md-3"><?= $strings2["verifs_supername"]; ?>:</label>
            <div class="col-md-3">
                <input type="text" class="form-control" name="supervisor_name[]"/>
            </div>

            <label class="control-label col-md-3 required"><?= $strings["forms_phone"]; ?>:</label>
            <div class="col-md-3">
                <input type="text" class="form-control" role="phone" name="supervisor_phone[]"/>
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
                <input type="<?= $datetype; ?>" class="form-control datepicker required" required name="employment_start_date[]" placeholder="mm/dd/yyyy"/>
            </div>

            <label class="control-label col-md-3 required"><?= $strings2["verifs_employment2"]; ?>:</label>
            <div class="col-md-3">
                <input type="<?= $datetype; ?>" class="form-control datepicker required" required name="employment_end_date[]" placeholder="mm/dd/yyyy"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label col-md-3"><?= $strings2["verifs_claimswith"]; ?>:</label>

            <div class="col-md-3"> <label class="radio-inline">
                &nbsp;&nbsp;<input type="radio" name="claims_with_employer[]" value="1"/>&nbsp;&nbsp;<?= $strings["dashboard_affirmative"]; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label class="radio-inline">
                <input type="radio" name="claims_with_employer[]" value="0"/>&nbsp;&nbsp;&nbsp;&nbsp;<?= $strings["dashboard_negative"]; ?></label>
            </div>
            <label class="control-label col-md-3"><?= $strings2["verifs_dateclaims"]; ?>:</label>

            <div class="col-md-3">
                <input type="<?= $datetype; ?>" class="form-control datepicker" name="claims_recovery_date[]" placeholder="mm/dd/yyyy"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="control-label col-md-3"><?= $strings2["verifs_employment3"]; ?>:</label>

            <div class="col-md-3">
                <input type="text" class="form-control" name="emploment_history_confirm_verify_use[]"/>
            </div>

            <label class="control-label col-md-3">US DOT MC/MX#:</label>

            <div class="col-md-3">
                <input name="us_dot[]" type="text" class="form-control" name="us_dot[]"/>
            </div>
            <label class="control-label col-md-3" style="display: none;"><? $strings["forms_signature"]; ?>:</label>

            <div class="col-md-3">
                <input type="text" class="form-control" style="display: none;" name="signature[]"/>
            </div>
        </div>

        <div class="form-group row">
            <label class="control-label col-md-3"><?= $strings2["verifs_date"]; ?>:</label>

            <div class="col-md-3">
                <input type="<?= $datetype; ?>" class="form-control" name="signature_datetime[]" placeholder="mm/dd/yyyy" disabled value="<?= date("m/d/Y"); ?>"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label col-md-3"><?= $strings2["verifs_equipmento"]; ?>: </label>

            <div class="col-md-9">
                <label class="radio-inline"><input type="checkbox" name="equipment_vans[]" value="1"/>&nbsp;<?= $strings2["verifs_vans"]; ?></label>&nbsp;
                <label class="radio-inline"><input type="checkbox" name="equipment_reefer[]" value="1"/>&nbsp;<?= $strings2["verifs_reefers"]; ?></label>&nbsp;
                <label class="radio-inline"><input type="checkbox" name="equipment_decks[]" value="1"/>&nbsp;<?= $strings2["verifs_decks"]; ?></label>&nbsp;
                <label class="radio-inline"><input type="checkbox" name="equipment_super[]" value="1"/>&nbsp;<?= $strings2["verifs_superbs"]; ?></label>&nbsp;
                <label class="radio-inline"><input type="checkbox" name="equipment_straight_truck[]" value="1"/>&nbsp;<?= $strings2["verifs_straighttr"]; ?></label>&nbsp;
                <label class="radio-inline"><input type="checkbox" name="equipment_others[]" value="1"/>&nbsp;<?= $strings2["verifs_others"]; ?></label>
            </div>
        </div>
        <div class="form-group row">
            <label class="control-label col-md-3"><?= $strings2["verifs_drivingexp"]; ?>: </label>

            <div class="col-md-9">
                <label class="radio-inline"><input type="checkbox" name="driving_experince_local[]" value="1"/>&nbsp;<?= $strings2["verifs_local"]; ?>&nbsp;</label>
                <label class="radio-inline"><input type="checkbox" name="driving_experince_canada[]" value="1"/>&nbsp;<?= $strings2["verifs_canada"]; ?>&nbsp;</label>
                <label class="radio-inline"><input type="checkbox" name="driving_experince_canada_rocky_mountains[]" value="1"/>&nbsp;<?= $strings2["verifs_canadarock"]; ?>&nbsp;</label>
                <label class="radio-inline"><input type="checkbox" name="driving_experince_usa[]" value="1"/>&nbsp;<?= $strings2["verifs_usa"]; ?>&nbsp;</label>
            </div>

        </div>
    </div>

    <div id="more_div" class="left15"></div>

    <div id="add_more_div">
        <p>&nbsp;</p>
        <input type="hidden" name="count_past_emp" id="count_past_emp" value="<?php if (isset($sub3['emp'])) {
            echo count($sub3['emp']);
        } else { echo '1'; }?>">
        <a href="javascript:void(0);" class="btn btn-primary no-print" onclick="add_more();"><?= $strings["forms_addmore"]; ?></a>
    </div>

<script>
    var references = 1;
    var requiredreferences = 3;

    function add_more() {//$("#add_more").click(function () {
        var URL = "<?= $webroot;?>subpages/documents/past_employer.php?language=" + language + "&references=" + references;
        if(references>1){URL = URL + "&notrequired";}
        return $.ajax({
            url: URL,
            async: false,
            success: function (res) {
                references = references + 1;
                $("#more_div").append( res);
                var c = $('#count_past_emp').val();
                var counter = parseInt(c) + 1;
                $('#count_past_emp').attr('value', counter);
            },
            error: function (res){
                //alert(res);
            }
        });
    }

    $("#delete").live("click", function () {
        $(this).parent().parent().remove();
        var c = $('#count_past_emp').val();
        var counter = parseInt(c) - 1;
        $('#count_past_emp').attr('value', counter);
        references = references - 1;
    });


    function checkformint(){
        if (references < 2){
            alert("Please include at least 2 references by clicking add more");
            return false;
        }
        return true;
    }

    for(var i = references; i <requiredreferences; i++){
        references=i;
        add_more();
    }
</script>
