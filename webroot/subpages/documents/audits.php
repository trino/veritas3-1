<?php
if($this->request->params['controller']!='ClientApplication'){
 if($this->request->session()->read('debug')){  echo "<span style ='color:red;'>subpages/documents/audits.php #INC128</span>";}
 }
$is_disabled = '';
if(isset($disabled)){$is_disabled = 'disabled="disabled"';}

//this document type can't have attachments, reason unknown
?>
<div class="portlet-body form">
<!-- BEGIN FORM-->
<form  id="form_tab8" method="post" action="<?php echo $this->request->webroot;?>documents/audits/<?php echo $cid;?>/<?php echo $did;?>" class="form-horizontal">

<input type="hidden" class="document_type" name="document_type" value="<?php if(isset($dx))echo $dx->title;?>"/>

    <input type="hidden" name="sub_doc_id" value="8" class="sub_docs_id" id="af" />
<div class="form-body">
                                                
                                                <div class="form-group">
<label class="col-md-3 control-label">Company / Division: </label>
<div class="col-md-4">
<input type="text" name="company" class="form-control " <?php echo $is_disabled;?> value="<?php if(isset($audits))echo $audits->company;?>" />
</div>
</div>
                                                
<div class="form-group">
<label class="col-md-3 control-label">Conference Name: </label>
<div class="col-md-4">
<input type="text" name="conference_name"  class="form-control " <?php echo $is_disabled;?> value="<?php
if(isset($audits)) {
    echo $audits->conference_name;
} elseif($controller == "documents" && $action == "Create"){
    echo $client->company_name;
}
?>" />
</div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Related Association: </label>
<div class="col-md-4">
<input type="text" name="association" class="form-control " <?php echo $is_disabled;?> value="<?php if(isset($audits))echo $audits->association;?>"/>	</div>
</div>
                                                                                                
<div class="form-group">
<label class="col-md-3 control-label">Date: </label>
<?php if(isset($audits)) {
    $date = explode("-",$audits->date);
    $year = $date[0];
    $month = $date[1];
}

function makeselect($is_disabled, $Name){
    echo '<select class="form-control member_type" ' . $is_disabled . ' name="'. $Name . '" >';
}
function makedropdown($is_disabled, $Name, $TheValue, $Language, $EnglishValues, $FrenchValues = ""){
    makeselect($is_disabled, $Name);
    if ($FrenchValues == ""){ $Language = "English"; }
    $variable = $Language . "Values";
    foreach($$variable as $Key => $Value){
        makedropdownoption($Key, $Value, $TheValue);
    }
    echo '</select>';
}
function makedropdownoption($Key, $Value, $TheValue){
    echo '<option value="' . $Key . '"';
    if($TheValue == $Key){echo "selected='selected'";}
    echo '>' . $Value . '</option>';
}

$Language="English";

    if(isset($audits)) {
        $province=$audits->province;

        $boothrate=$audits->boothrate;
        $rating_1=$audits->rating_1;
        $rating_2=$audits->rating_2;
        $rating_3=$audits->rating_3;

        $total_rating =$boothrate+$rating_1+$rating_2+$rating_3;
    } else{

    $province="";
    $month=0;
    $year=0;
    $total_rating=0;
    $boothrate=0;
    $rating_1=0;
    $rating_2=0;
    $rating_3=0;
}

function V2K($data){
    $data2 = array();
    foreach($data as $value){
        $data2[$value] = $value;
    }
    return $data2;
}

function makemonthdropdown($is_disabled, $Name, $Value, $Language){
    $EnglishValues = array("" => "Month", 1=> "January", 2=> "February", 3=> "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");
    makedropdown($is_disabled, $Name, $Value, $Language, $EnglishValues);
}
function makeprovincedropdown($is_disabled, $Name, $Value, $Language){
    $EnglishValues = V2K(array("AB", "BC", "MB", "NB", "NL", "NT", "NS", "NU", "ON", "PE", "QC", "SK", "YT"));
    makedropdown($is_disabled, $Name, $Value, $Language, $EnglishValues);
}
function makeratingdropdown($is_disabled, $Name, $Value, $Count = 40, $Start = 1){
    makeselect($is_disabled, $Name);
    $End = $Start+$Count-1;
    for($i=$Start; $i<=$End; $i++){
        makedropdownoption($i,$i,$Value);
    }
    echo '</select>';
}
function makeyeardropdown($is_disabled, $Name, $Value, $Language, $Count = 5, $Start = -1){
    $Text = array("English" => "Year", "French" => "Année");
    makeselect($is_disabled, $Name);
    echo '<option value="">' . $Text[$Language] . '</option>';
    if ($Start == -1){ $Start=date("Y");}
    for($temp=$Start; $temp<=$Start+$Count; $temp++){
        makedropdownoption($temp, $temp, $Value);
    }
    echo '</select>';
}
?>
                                                      <div class="col-md-3">
                                                          <?php makemonthdropdown($is_disabled, "month", $month, $Language); ?>
                                                      </div>
                                                      <div class="col-md-3">
                                                         <?php makeyeardropdown($is_disabled, "year", $year, $Language); ?>
                             	  </div>
</div>


                                                <div class="form-group">
<label class="col-md-3 control-label">Location: </label>

<div class="col-md-3">
<input type="text" name="city"  class="form-control req_driver" <?php echo $is_disabled;?> placeholder="City" value="<?php if(isset($audits))echo $audits->city;?>">
</div>                                                    



                                                    <div class="col-md-3">
                                                            <?php makeprovincedropdown($is_disabled, "province", $province, $Language); ?>
                                                    </div>

                                                    <div class="col-md-3">
<input type="text" name="country" class="form-control req_driver" <?php echo $is_disabled;?> value="Canada" value="<?php if(isset($audits))echo $audits->country;?>">
</div>

</div>
 
 
                                                <div class="form-group">
<label class="col-md-3 control-label">Estimated Total Cost:
                                                    <small class=" control-label">Booth/Travel/Hotels/Meals</small>
                                                    </label>
                                                    
<div class="col-md-4">
<input type="text" name="total_cost" class="form-control " <?php echo $is_disabled;?> value="<?php if(isset($audits))echo $audits->total_cost;?>">
</div>
</div>
    <?
        $action = ucfirst($this->request->params['action']);
        if ($action != "Add" && $action!="Apply") {
    ?>
 	<div class="form-group">
<label class="col-md-3 control-label">Rating Total
                                                    <small class=" control-label">[Out of 40]</small>:
                                                    </label>
                                                    
<div class="col-md-4">
                                <?php makeratingdropdown($is_disabled, "total_rating", $total_rating); ?>

                                </div>
</div>
                   <?}?>
                                       	<h2> Objectives</h2>

<div class="form-group">
<label class="col-md-3 control-label">
                                                    What were the primary objectives at the show/event?
                                                    </label>
<div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="primary_objectives" id="primary_objectives" <?php echo $is_disabled;?> rows="3"><?php if(isset($audits))echo $audits->primary_objectives;?></textarea>
</div>
                                                    
                                                    
</div>                                                



<div class="form-group">
<label class="col-md-3 control-label">
                                                    Do you feel the objectives were achieved? Provide a grade rating of 1 to 10 (10 is best)
                                                    </label>

    <div class="col-md-4">
        <?php makeratingdropdown($is_disabled, "rating_1", $rating_1, 10); ?><BR>
    </div>
</div>


    <div class="form-group">
        <label class="col-md-3 control-label">
            Provide details.
        </label>
<div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="objectives" id="primary_objectives" <?php echo $is_disabled;?> rows="3"><?php if(isset($audits))echo $audits->objectives;?></textarea>
</div>
</div>   

<div class="form-group">
<label class="col-md-3 control-label">
                                                    Please provide suggestions for improvement.
                                                    </label>
<div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="improvement" id="primary_objectives" <?php echo $is_disabled;?> rows="3"><?php if(isset($audits))echo $audits->improvement;?></textarea>
</div>
</div> 
                                                <h2> Leads </h2>
                                                <div class="form-group">
<label class="col-md-3 control-label">
                                                    Was the lead-collecting process at the booth/event effective (e.g. badge scanner, business card collecting)?
                                                    </label>
<div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="lead_effective" id="primary_objectives" <?php echo $is_disabled;?> rows="3"><?php if(isset($audits))echo $audits->lead_effective;?></textarea>
</div>
</div>
 
                                                 <div class="form-group">
<label class="col-md-3 control-label">
How many leads were generated?                                                    </label>
<div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="leads" id="primary_objectives" <?php echo $is_disabled;?> rows="3"><?php if(isset($audits))echo $audits->leads;?></textarea>
</div>
</div> 
 
                                                 <div class="form-group">
<label class="col-md-3 control-label">
Rate the leads - how many do you feel are "quality"? Provide a grade rating of 1 to 10 (10 is best)
</label>
                                                     <div class="col-md-4">
                                                         <?php makeratingdropdown($is_disabled, "rating_2", $rating_2, 10); ?><BR>
                                                     </div>
</div>
    <div class="form-group">
        <label class="col-md-3 control-label">
            Provide details.                                                   </label>
<div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="leads_rate" id="primary_objectives" <?php echo $is_disabled;?> rows="3"><?php if(isset($audits))echo $audits->leads_rate;?></textarea>
</div>
</div>

 
                                                 <div class="form-group">
<label class="col-md-3 control-label">
Please provide suggestions for improvement of the lead collection and handling process.                                                   </label>
<div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="handling" id="primary_objectives" <?php echo $is_disabled;?> rows="3"><?php if(isset($audits))echo $audits->handling;?></textarea>
</div>
</div>
                                                
                                                <h2> Audience </h2>
   
                                                 <div class="form-group">
<label class="col-md-3 control-label">
Rate the type of attendees at the show/event. Provide a grade rating of 1 to 10 (10 is best)
</label>
                                                     <div class="col-md-4">
                                                         <?php makeratingdropdown($is_disabled, "rating_3", $rating_3, 10); ?><BR>
                                                     </div></div>

    <div class="form-group">
        <label class="col-md-3 control-label">
            (e.g. decision makers, decision influencers, general staff)?
            Provide details.                                                   </label>
<div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="attendees_rate" <?php echo $is_disabled;?> id="" rows="3"><?php if(isset($audits))echo $audits->attendees_rate;?></textarea>
</div>
</div> 
                                                                                             
                                                <h2> Booth/Event </h2>
   
                                                 <div class="form-group">
<label class="col-md-3 control-label">
Which of our services/products we provide was of most interest?
                    </label>
<div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="interest" <?php echo $is_disabled;?> id="primary_objectives" rows="3"><?php if(isset($audits))echo $audits->interest;?></textarea>
</div>
</div>                                                   
 
                                                  <div class="form-group">
<label class="col-md-3 control-label">
How was the booth location on the trade show floor or the venue location if it was an event? Provide details.
                    </label>
<div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="booth_location" <?php echo $is_disabled;?> id="primary_objectives" rows="3"><?php if(isset($audits))echo $audits->booth_location;?></textarea>
</div>
</div> 
 
                                                   <div class="form-group">
<label class="col-md-3 control-label">
    Rate the volume of booth traffic or attendance if it was event. Provide a grade rating of 1 to 10 (10 is best)
</label>

    <div class="col-md-4">
        <?php makeratingdropdown($is_disabled, "boothrate", $boothrate, 10); ?><BR>
    </div></div>


    <div class="form-group">
        <label class="col-md-3 control-label">
            Provide details.
        </label>
    <div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="rating" <?php echo $is_disabled;?> id="primary_objectives" rows="3"><?php if(isset($audits))echo $audits->rating;?></textarea>
</div>
</div>

    <div class="form-group">
<label class="col-md-3 control-label">
Please provide suggestions for improvement of the booth's appearance, messaging, display, location, or the event’s format, layout, etc.
                    </label>
<div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="suggestions" <?php echo $is_disabled;?> id="primary_objectives" rows="3"><?php if(isset($audits))echo $audits->suggestions;?></textarea>
</div>
</div> 
 	
                                            <h2> Promotion </h2>
                                            
                                                <div class="form-group">
<label class="col-md-3 control-label">
How was the promotional giveaway received (if applicable)? Provide details.
                    </label>
<div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="promotional" <?php echo $is_disabled;?> id="primary_objectives" rows="3"><?php if(isset($audits))echo $audits->promotional;?></textarea>
</div>
</div>                                            
 
                                             <h2> Staffing </h2>
                                            
                                                <div class="form-group">
<label class="col-md-3 control-label">
Approximately how many attendees did you engage in conversation?
                    </label>
<div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="attendees" <?php echo $is_disabled;?> id="primary_objectives" rows="3"><?php if(isset($audits))echo $audits->attendees;?></textarea>
</div>
</div> 

                                                <div class="form-group">
<label class="col-md-3 control-label">
Do you feel there was enough booth/event staff?
                    </label>
<div class="col-md-9 col-sm-9 col-xs-12">
<textarea class="form-control" name="booth_staff" <?php echo $is_disabled;?> id="primary_objectives" rows="3"><?php if(isset($audits))echo $audits->booth_staff;?></textarea>
</div>
</div> 
 <?php if($this->request->params['controller']!='Documents' && $this->request->params['controller']!='ClientApplication'){?>
 <div class="addattachment8 form-group col-md-12">

</div>
<?php }?>
<div class="clearfix"></div>
</div>

<!--
Shouldn't there be a place to add attachements?

<div class="form-actions">
<div class="row">
<div class="col-md-offset-3 col-md-9">
<button type="submit" class="btn btn-circle blue">Submit</button>
<button type="button" class="btn btn-circle default">Cancel</button>
</div>
</div>
</div>-->
</form>
<!-- END FORM-->
</div>