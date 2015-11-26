<?php
include_once('subpages/api.php');
$language = $this->request->session()->read('Profile.language');
$settings = $Manager->get_settings();
$strings = CacheTranslations($language, "aggregate_%",$settings);//,$registry);
?>

<h3 class="page-title">
    Aggregate <small>Aggregate Audits</small>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo $this->request->webroot;?>">Dashboard</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="<?php echo $this->request->webroot;?>documents/index">Documents</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="" onclick="return false;">Aggregate</a>

        </li>

    </ul>
    <a href="javascript:window.print();" class="floatright btn btn-primary"><?= $strings["dashboard_print"]; ?></a>
</div>

<div class="portlet box blue">

                                    <div class="portlet-title">
                                        <div class="caption">
                                            Aggregate Audits
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                    <div class="form-body" style="padding-bottom: 0px;">
                                                    <div class="tab-content"><div class="portlet-body form">
                                                    
                                                     <?php
             function getaverage($variable){
                 $avg=0;
                 $avco = 0;
                 foreach($variable as $k=>$v) {
                     if(is_numeric($v)) {
                         $avco++;
                         $avg = $avg + $v;
                     }
                 }
                 if($avco==0){return 0;}
                 return round($avg/$avco,2);
             }

            function printall($prof, $variable, $variable2 = "", $Outof = 10){
                if ($variable2 != ""){
                    echo '<p style="padding-top: 5px;">Total Average (Out of ' . $Outof . '): ' .  getaverage($variable2) . '</p>';
                }
                foreach($variable as $k=>$v) {
                    echo '<p><strong>';
                    if(isset($prof)&& $prof[$k]) {echo $prof[$k] . ": ";} else {echo "Unknown";}
                    echo '</strong>' . $v . '</p>';
                }
            }


             if($audits) {
                foreach($audits as $aud) {
                    $doid[] = $aud->document_id;
                    $pr = $this->requestAction('/documents/getProfileByDocument/'.$aud->document_id);
                    if($pr)
                    $prof[] = $pr->fname.' '.$pr->lname;
                    $ec[] = $aud->total_cost;

                    $po[] = $aud->primary_objectives;
                    $ob[] = $aud->objectives;
                    $imp[] = $aud->improvement;
                    $le[] = $aud->lead_effective;
                    $leads[] = $aud->leads;
                    $lr[] = $aud->leads_rate;
                    $hand[] = $aud->handling;
                    $ar[] = $aud->attendees_rate;
                    $int[] = $aud->interest;
                    $bl[] = $aud->booth_location;
                    $rat1[] = $aud->boothrate;
                    $rat[] = $aud->rating;
                    $sugg[] = $aud->suggestions;
                    $prom[] = $aud->promotional;
                    $att[] = $aud->attendees;
                    $bs[] = $aud->booth_staff;

                    $rate_1[] = $aud->rating_1;
                    $rate_2[] = $aud->rating_2;
                    $rate_3[] = $aud->rating_3;

                    $aud->total_rating = $aud->boothrate + $aud->rating_1 + $aud->rating_2 + $aud->rating_3;
                    $rati[] = $aud->total_rating;
                }
             }
             ?> 
<!-- BEGIN FORM-->
<form  id="form_tab8" method="post" action="/veritas3-0/documents/audits/1/15" class="form-horizontal">


    <table class="table-condensed table-striped table-bordered table-hover dataTable no-footer">
        <TR><Th>Client</Th><TD align="center"><?php echo $client->id;?></TD><TD><?php echo $client->company_name;?></TD><td><?php if($client->date_start)echo $client->date_start;else echo "No date available.";?></td></TR>
    </table>

<input type="hidden" class="document_type" name="document_type" value="Audits"/>

    <input type="hidden" name="sub_doc_id" value="8" class="sub_docs_id" id="af" />
<div class="form-body">
                                                
                                                
 
 
                                                <div class="form-group">
<label class="col-md-3 control-label">Estimated Total Cost:
                                                    <small class=" control-label">Booth/Travel/Hotels/Meals</small>
                                                    </label>
                                                    
<div class="col-md-4">
<?php if(isset($ec)){ printall($prof, $ec);} ?>
</div>
</div>                                                

 	<div class="form-group">
<label class="col-md-3 control-label">Avg. Rating Total
                                                    <small class=" control-label">[Out of 40]</small>:
                                                    </label>
                                                    
<div class="col-md-4">

                                                            <?php
                                                            if(isset($rati)){
echo '<p style="padding-top: 5px;"> ' .  getaverage($rati) . '</p>';

}?>

                                </div>
</div>
             
                                              
                                       	<h2> Objectives</h2>

<div class="form-group">
<label class="col-md-3 control-label">
                                                    What were the primary objectives at the show/event?
                                                    </label>
<div class="col-md-8">

<?php if(isset($po)){ printall($prof, $po); }?>
</div>
                                                    
                                                    
</div>                                                

<div class="form-group">
<label class="col-md-3 control-label">
                                                    Do you feel the objectives were achieved? Provide a grade rating of 1 to 10 (10 is best) and provide details.
                                                    </label>
<div class="col-md-8">
<?php  if(isset($ob)){ printall($prof, $ob, $rate_1);} ?>
</div>
</div>   

<div class="form-group">
<label class="col-md-3 control-label">
                                                    Please provide suggestions for improvement.
                                                    </label>
<div class="col-md-8">
<?php  if(isset($imp)){ printall($prof, $imp);} ?>
</div>
</div> 
                                                <h2> Leads </h2>
                                                <div class="form-group">
<label class="col-md-3 control-label">
                                                    Was the lead-collecting process at the booth/event effective (e.g. badge scanner, business card collecting)?
                                                    </label>
<div class="col-md-8">
<?php  if(isset($le)){ printall($prof, $le);} ?>
</div>
</div>
 
                                                 <div class="form-group">
<label class="col-md-3 control-label">
How many leads were generated?                                                    </label>
<div class="col-md-8">
<?php  if(isset($leads)){ printall($prof, $leads);} ?>
</div>
</div> 
 
                                                 <div class="form-group">
<label class="col-md-3 control-label">
Rate the leads - how many do you feel are "quality"? 
                                                        Provide a grade rating of 1 to 10 (10 is best) and provide details.                                                   </label>
<div class="col-md-8">
<?php if(isset($lr)){ printall($prof, $lr, $rate_2);} ?>
</div>
</div>

 
                                                 <div class="form-group">
<label class="col-md-3 control-label">
Please provide suggestions for improvement of the lead collection and handling process.                                                   </label>
<div class="col-md-8">
<?php  if(isset($hand)){ printall($prof, $hand);} ?>
</div>
</div>
                                                
                                                <h2> Audience </h2>
   
                                                 <div class="form-group">
<label class="col-md-3 control-label">
Rate the type of attendees at the show/event
                                                        (e.g. decision makers, decision influencers, general staff)?
                                                        Provide a grade rating of 1 to 10 (10 is best) and provide details.                                                   </label>
<div class="col-md-8">
<?php if(isset($ar)){ printall($prof, $ar, $rate_3);}?>
</div>
</div> 
                                                                                             
                                                <h2> Booth/Event </h2>
   
                                                 <div class="form-group">
<label class="col-md-3 control-label">
    Which of our services/products we provide was of most interest?
</label>
<div class="col-md-8">
<?php if(isset($int)){ printall($prof, $int);} ?>
</div>
</div>                                                   
 
                                                  <div class="form-group">
<label class="col-md-3 control-label">
    How was the booth location on the trade show floor or the venue location if it was an event? Provide details.
</label>
<div class="col-md-8">
<?php  if(isset($bl)) { printall($prof, $bl);} ?>
</div>
</div> 
 
                                                   <div class="form-group">
<label class="col-md-3 control-label">
    Rate the volume of booth traffic or attendance if it was event.
    Provide a grade rating of 1 to 10 (10 is best) and provide details.
                    </label>
<div class="col-md-8">
                                                            <?php 
if(isset($rat1)) {
    echo '<p style="padding-top: 5px;">Total Average (Out of 10): ' . getaverage($rat1) . '</p>';
}

if(isset($rat)) { printall($prof, $rat); }
?>
</div>
</div> 
 
                                                    <div class="form-group">
<label class="col-md-3 control-label">
Please provide suggestions for improvement of the booth's appearance, messaging, display, location, or the event’s format, layout, etc.
                    </label>
<div class="col-md-8">
<?php if(isset($sugg)){ printall($prof, $sugg); } ?>
</div>
</div> 
 	
                                            <h2> Promotion </h2>
                                            
                                                <div class="form-group">
<label class="col-md-3 control-label">
How was the promotional giveaway received (if applicable)? Provide details.
                    </label>
<div class="col-md-8">
<?php if(isset($prom)){ printall($prof, $prom);} ?>
</div>
</div>                                            
 
                                             <h2> Staffing </h2>
                                            
                                                <div class="form-group">
<label class="col-md-3 control-label">
Approximately how many attendees did you engage in conversation?
</label>
<div class="col-md-8">
<?php if(isset($att)) { printall($prof, $att); } ?>
</div>
</div> 

                                                <div class="form-group">
<label class="col-md-3 control-label">
    Do you feel there was enough booth/event staff?
</label>
<div class="col-md-8">
<?php  if(isset($bs)) { printall($prof, $bs);} ?>
</div>
</div> 
 <?php
if($client_docs)
{
    ?>
    
 <div class="addattachment8 form-group col-md-12">
    <table class="table-condensed table-striped table-bordered table-hover dataTable no-footer">
<?php
$count=0;
foreach ($client_docs as $k => $cd) {
           
            $count += 1;
            
                 $file = $cd->attachment;
            
                //include filelist and use the api from there!!!!!!
            if ($file) {//id, client_id
                if ($count==1){ echo '<TR><TH colspan="5">Attachments</TH></TR>'; }
                $path = "/attachments/" . $file;
                $extension = getextension($file);
                $filename = getextension($file, PATHINFO_FILENAME);
                echo "<TR><TD width='29' align='center'><i class='fa fa-";
                switch (TRUE) {//file-excel-o,,
                    case $extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'bmp' || $extension == 'gif';
                        $type = "Image";
                        echo 'file-image-o';
                    break;
                    case $extension == "pdf";
                        $type = "Portable Document Format";
                        echo 'file-pdf-o';
                        break;
                    case $extension == 'zip' || $extension == 'rar';
                        $type = "File Archive";
                        echo 'file-archive-o';
                        break;
                    case $extension == 'wav' || $extension == 'mp3';
                        $type = "Audio Recording";
                        echo 'file-audio-o';
                        break;
                    case $extension == 'docx' || $extension == 'doc';
                        $type = "Microsoft Word Document";
                        echo 'file-word-o';
                        break;
                    case $extension == 'mp4' || $extension == 'avi';
                        $type = "Video";
                        echo 'file-video-o';
                        break;
                    case $extension == 'php' || $extension == 'js' || $extension == 'ctp';
                        $type = "Code Script";
                        echo 'file-code-o';
                        break;
                    case $extension == 'ppt' || $extension == 'pps';
                        $type = "Powerpoint Presentation";
                        echo 'file-powerpoint-o';
                        break;
                    default:
                        $type = "Unknown";
                        echo 'paperclip';
                }
                echo "' title='" . $type . "'></i></TD>";
                echo "<TD><A class='nohide' HREF='" . $this->request->webroot . 'attachments/' . $file . "'>" . $filename . "</A>
                
                </TD>";
                echo "<TD>" . date('Y-m-d H:i:s', filemtime(getcwd() . $path)) . "</TD>";
                
                echo "<TD width='1%'>" . $extension . "</TD></TR>";
            }
        }
?>
</table>
</div>
<?php
}
function getextension($path, $value=PATHINFO_EXTENSION){
    return strtolower(pathinfo($path, $value));
}
 ?>
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
</div></div></div></div></div>