<div class="form-group row">
    <center><h3 class="col-md-12">Education Experience confirmation</h3></center>
</div>
<?php
    $startdate = "start";
    $enddate = "end";
    $graduated = "date provided";
    $isset = isset($edu_verifs);
    if($isset) {debug($edu_verifs);}
    $viewing = false;
    if (isset($this)) {
        $viewing = $this->request->params['action'] == 'vieworder' || $this->request->params['action'] == 'view';
    }

    function printcheck($viewing, $Value, $Checkfor){
        return !$viewing;
    }

?>
<div class="form-group row">
    <label class="control-label required col-md-3">School Name:</label>
    <div class="col-md-9">
        <input type="text" required class="form-control required" name="school_name" value="<?php if($isset) { echo $edu_verifs->school_name;} ?>" <?= $is_disabled; ?>/>
    </div>

    <label class="control-label required col-md-3">Verified By:</label>
    <div class="col-md-9">
        <input type="text" required class="form-control required" name="verified_by" value="<?php if($isset) { echo $edu_verifs->verified_by;} ?>" <?= $is_disabled; ?>/>
    </div>

    <label class="control-label required col-md-9">Did this driver attend your school from this (<?= $startdate; ?>) date to this (<?= $enddate; ?>) date?:</label>
    <div class="col-md-3">
        <LABEL><input type="radio" required class="form-control" name="did_attend" value="1" <?= $is_disabled; ?>/>Yes</LABEL>
        <LABEL><input type="radio" required class="form-control" name="did_attend" value="0" checked <?= $is_disabled; ?>/>No</LABEL>
    </div>

    <label class="control-label required col-md-9">Did this driver graduate on this day (<?= $graduated; ?>) ?:</label>
    <div class="col-md-3">
        <LABEL><input type="radio" required class="form-control" name="did_graduate" value="1" onclick="$('#didnt_graduate').hide();" <?= $is_disabled; ?>/>Yes</LABEL>
        <LABEL><input type="radio" required class="form-control" name="did_graduate" value="0" onclick="$('#didnt_graduate').show();" checked  <?= $is_disabled; ?>/>No</LABEL>
    </div>
</div>

<div class="form-group row" id="didnt_graduate">
    <label class="control-label required col-md-7">What were the circumstances why this driver did not pass at your institution?:</label>
    <div class="col-md-5">
        <TEXTAREA NAME="didnt_graduate" class="form-control" style="width: 100%;" <?= $is_disabled; ?>><?php if($isset) { echo $edu_verifs->didnt_graduate;} ?></TEXTAREA>
    </div>
</div>

<div class="form-group row">
    <label class="control-label required col-md-9">Were there any claims with this driver while attending your institution? If declared verify:</label>
    <div class="col-md-3">
        <LABEL><input type="radio" required class="form-control" name="claims" value="1" onclick="$('#how_many').show();" />Yes</LABEL>
        <LABEL><input type="radio" required class="form-control" name="claims" value="0" onclick="$('#how_many').hide();" checked/>No</LABEL>
    </div>
</div>

<div class="form-group row" id="how_many" <?php if (!$isset  || $isset && !$edu_verifs->claims){echo 'style="display: none;"';} ?> >
    <label class="control-label required col-md-9">How many?:</label>
    <div class="col-md-3">
        <input type="number" required class="form-control required" name="how_many" value="<?php if($isset) { echo $edu_verifs->how_many;} else { echo 0;} ?>" <?= $is_disabled; ?>/>
    </div>

    <label class="control-label required col-md-9">What were the circumstances?:</label>
    <div class="col-md-3">
        <LABEL><input type="checkbox" required class="form-control" name="at_fault" value="1"/>At fault accident</LABEL>
        <LABEL><input type="checkbox" required class="form-control" name="not_at_fault" value="1" checked/>Not at fault accident</LABEL>
    </div>
</div>
