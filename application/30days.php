<title>30 Day Employee Review</title><?php
    include("api.php");
    includeCSS("login");

    $webroot = $_SERVER["REQUEST_URI"];
    $start = strpos($webroot, "/", 1)+1;
    $webroot = substr($webroot,0,$start);
    
    error_reporting(E_ERROR | E_PARSE);//suppress warnings
    include("../config/app.php");
    error_reporting(E_ALL);//re-enable warnings
    $con = "";
    $logo = 'img/logos/';
    $company_name = "";

     $con = connectdb();

    $userID=false;
    if (isset($_GET["p_id"])) { $userID = $_GET["p_id"];}
    if (isset($_GET["user_id"])) { $userID = $_GET["user_id"];}

    if ($userID) {
        $row = first("SELECT * FROM profiles where id = " . $userID);
        if ($row) {
            $uname = $row['username'];
            $email = $row['email'];
        }

    }
    if(isset($_GET['form_id'])) {
        $form = first("SELECT * FROM 30days where id = " . $_GET["form_id"]);
    }

?>
 <!--DOCTYPE html>
 <html>
 <head>
 	<title>30 Day Employee Review</title>
 	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <style>
    p{text-align:justify!important;}
    </style>
 </head>
 <body class="login"-->
 	<!-- Latest compiled and minified CSS -->
 	<link href="<?= $webroot; ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $webroot; ?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $webroot; ?>assets/admin/pages/css/login3.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $webroot; ?>assets/global/css/components.css" rel="stylesheet" type="text/css"/>
    
    <div style="width:77%" class="content">
 		<div class="logo col-md-12  form-group" style="margin-top: 0px;">
 				<img src="<?= $webroot; ?>img/logo.png" />
 		</div>

 		<div class="title col-md-12  form-group" style="text-align:center;color:#ff0000;">
 			30 Day Employee Review
 			<span style="display:block;">WAREHOUSE</span>
 		</div>
         <?php 
         
         if(isset($_GET["test"]) || (!isset($_GET['msg']) || (isset($_GET['msg']) && $_GET['msg']=='error'))&&(isset($row)&& ($row['profile_type']=='9'||$row['profile_type']=='12'))){
            if(isset($_GET['msg']) && $_GET['msg']=='error')
             echo '<div class="clearfix"></div><div class="alert alert-danger display-hide" style="display: block;">
                        <button class="close" data-close="alert"></button>
                        Could not submit the form. Please try again.
                        </div>';    
        ?>
 		<form method="post" action="<?php echo $webroot;?>rapid/days/30" class="formz" >
            <input type="hidden" name="profile_id" value="<?php if($userID)echo $userID;else echo "0";?>" />
 			<div class="name form-group">
 				<label for="name" class="control-label col-md-2">Name:</label>
 				<div class="col-md-4 form-group">
                <div class="input-icon">
                 <input type="text" class="form-control" name="surname" placeholder="Surname" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['surname'];?>" >
                 </div>
                 </div>
 				<div class="col-md-2 form-group"></div>
 				<div class="col-md-4 form-group">
                 <div class="input-icon"><input type="text" name="given" class="form-control" placeholder="Given" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['given'];?>">
                 </div>
                 </div>
 			</div>
 			<div class="clearfix"></div>
 			<div class="">
 				<div class="hire">
 					<label for="hire" class="control-label col-md-2">Hire Date:</label>
 					<div class="col-md-4 form-group">
                     <div class="input-icon">
                     <input type="text" class="form-control datepicker" name="h_date" placeholder="MM/DD/YYYY" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['h_date'];?>"></div>
 				</div>
                </div>
 				<div class="review">
 					<label for="review" class="control-label col-md-2">Review Date:</label>
 					<div class="col-md-4 form-group">
                      <div class="input-icon"><input type="text" class="form-control datepicker" name="r_date" placeholder="MM/DD/YYYY" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['r_date'];?>"></div>
 				</div>
                </div>
 			</div>
 			<div class="supervisor">
 				<label for="supervisor" class="control-label col-md-2">Supervisor:</label>
 				<div class="supervisor-radio col-md-10 form-group" style="padding:0;">
 					<div class="col-md-2"><LABEL><input type="radio" name="supervisor" value="Richard Chilvers" <?php if(isset($form) && $form['supervisor']=='Richard Chilvers')echo "checked";?>>  Richard Chilvers</LABEL></div>
 					<div class="col-md-2"><LABEL><input type="radio" name="supervisor" value="Nelson Ralph" <?php if(isset($form) && $form['supervisor']=='Nelson Ralph')echo "checked";?>>  Nelson Ralph	</LABEL></div>
 					<div class="col-md-2"><LABEL><input type="radio" name="supervisor" value="Gary Martin" <?php if(isset($form) && $form['supervisor']=='Gary Martin')echo "checked";?>>  Gary Martin</LABEL></div>		
 					<div class="col-md-2"><LABEL><input type="radio" name="supervisor" value="Mike Ruehl" <?php if(isset($form) && $form['supervisor']=='Mike Ruehl')echo "checked";?>>  Mike Ruehl</LABEL></div>	
 					<div class="col-md-2"><LABEL><input type="radio" name="supervisor" value="Michael Torrance" <?php if(isset($form) && $form['supervisor']=='Michael Torrance')echo "checked";?>>  Michael Torrance</LABEL></div>
 					<div class="col-md-2"><LABEL><input type="radio" name="supervisor" value="Matt Bancroft" <?php if(isset($form) && $form['supervisor']=='Matt Bancroft')echo "checked";?>>  Matt Bancroft</LABEL></div>	
 				</div>
 			</div>
 			<div class="clearfix"></div>

 			<div class="instructions col-md-12">
 				<div class="">
 					<div class="title form-group" style="text-align:center;font-weight:bold;text-transform:uppercase;">Instructions</div>
 					<p>GFS is committed to maintaining a positive work environment for its employees. This questionnaire provides a valuable source of information that helps identify where we need to improve to meet this goal.</p>
 					<p>The data obtained from this questionnaire will be used to enhance various aspects of our recruitment, retention and training efforts. The feedback will also enable us to evaluate the overall quality of life at Gordon Food Service.</p>								
 					<p>We are interested in getting your honest and objective feedback. Human Resources will share this information with the Leadership Team, allowing identified strengths to be maximized and opportunities to be addressed.</p>
 					<p>Please complete the questionnaire and return it to your Supervisor the same day you receive it. An envelope has been provided to ensure confidentiality.</p>
 					<p>Your supervisor will advise you of the date and time of your meeting with Human Resources to discuss the questionnaire in detail. Your continued input will help us to provide a strong work environment for all employees.</p>
 					<p>Thank you for your time.</p>
 				</div>
 			</div>
 			<div class="hr col-md-12">
 				<div class="title form-group" style="color:#ff0000;text-decoration:underline;font-weight:bold;text-align:center;">
 					Section 1: Human Resources
 				</div>
 				<div class="hr1">
 					<label for="hr1" class="control-label col-md-8" style="padding-left:0px;">Does this job live up to your expectations?</label>
 					<div class="hr1-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr1" value="yes" <?php if(isset($form) && $form['hr1']=='yes')echo "checked";?>>  Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr1" value="no" <?php if(isset($form) && $form['hr1']=='no')echo "checked";?>>  No</LABEL></div>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="hr2">
 					<label for="hr2" class="control-label form-group" style="padding-left:0px;">What could we have done to better prepare you for the job?</label>
 					<div class=" form-group">
 						<textarea name="hr2" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['hr2'];?></textarea>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="hr3">
 					<label for="hr3" class="control-label col-md-8" style="padding-left:0px;">Is there something we should have discussed in the interview process but failed	to touch on or should have expanded on about the job?</label>
 					<div class="hr3-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr3" value="yes" <?php if(isset($form) && $form['hr3']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr3" value="no" <?php if(isset($form) && $form['hr3']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="hr4">
 					<label for="hr4" class="control-label form-group" style="padding-left:0px;">If "Yes" please explain:</label>
 					<div class=" form-group">
 						<textarea name="hr3_ans" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['hr3_ans'];?></textarea>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="hr4">
 					<label for="hr4" class="control-label col-md-8" style="padding-left:0px;">Do you feel your H.R. team understands the challenges that are unique to your  position and provides adequate support and guidance?</label>
 					<div class="hr5-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr4" value="yes" <?php if(isset($form) && $form['hr4']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr4" value="no" <?php if(isset($form) && $form['hr4']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="hr5">
 					<label for="hr5" class="control-label col-md-8" style="padding-left:0px;">Did you know there is a H.R representative here every Friday from 5am  - 5pm?</label>
 					<div class="hr6-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr5" value="yes" <?php if(isset($form) && $form['hr5']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr5" value="no" <?php if(isset($form) && $form['hr5']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="hr6">
 					<label for="hr6" class="control-label col-md-8" style="padding-left:0px;">Were you aware you will be receiving benefits after 3 months of starting at GFS?</label>
 					<div class="hr6-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr6" value="yes" <?php if(isset($form) && $form['hr6']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr6" value="no" <?php if(isset($form) && $form['hr6']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="hr7">
 					<label for="hr7" class="control-label col-md-8" style="padding-left:0px;">Are you aware that you must advise H.R. of any personal information changes?</label>
 					<div class="hr7-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr7" value="yes" <?php if(isset($form) && $form['hr7']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr7" value="no" <?php if(isset($form) && $form['hr7']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="hr8">
 					<label for="hr8" class="control-label col-md-8" style="padding-left:0px;">Do you understand your responsibilities as a GFS employee?(e.g. sick-line, Code of Business Conduct)</label>
 					<div class="hr8-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr8" value="yes" <?php if(isset($form) && $form['hr8']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr8" value="no" <?php if(isset($form) && $form['hr8']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="hr9">
 					<label for="hr9" class="control-label col-md-8" style="padding-left:0px;">Do you know we have an Employee Announcement board in the lunchroom?</label>
 					<div class="hr9-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr9" value="yes" <?php if(isset($form) && $form['hr9']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr9" value="no" <?php if(isset($form) && $form['hr9']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="hr10">
 					<label for="hr10" class="control-label col-md-8" style="padding-left:0px;">Are you aware of the referral process at GFS?</label>
 					<div class="hr10-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr10" value="yes" <?php if(isset($form) && $form['hr10']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr10" value="no" <?php if(isset($form) && $form['hr10']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="hr11">
 					<label for="hr11" class="control-label col-md-8" style="padding-left:0px;">Do you know where we post internal job opportunities?</label>
 					<div class="hr11-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr11" value="yes" <?php if(isset($form) && $form['hr11']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr11" value="no" <?php if(isset($form) && $form['hr11']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="hr12">
 					<label for="hr12" class="control-label col-md-8" style="padding-left:0px;">Have you completed your AODA training?</label>
 					<div class="hr12-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr12" value="yes" <?php if(isset($form) && $form['hr12']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr12" value="no" <?php if(isset($form) && $form['hr12']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="hr13">
 					<label for="hr13" class="control-label form-group" style="padding-left:0px;">Additional Comments or Suggestions:</label>
 					<div class=" form-group">
 						<textarea name="hr13" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['hr13'];?></textarea>
 					</div>
 				</div>
 			</div>

 			<div class="rs col-md-12">
 				<div class="title  form-group" style="color:#ff0000;text-decoration:underline;font-weight:bold;text-align:center;">
 					Section 2: Relationships
 				</div>
 				<div class="rs1">
 					<label for="rs1" class="control-label form-group">Who do you feel comfortable going to with a problem or issue? (please circle)</label>
 					<div class="rs1-radio col-md-12 form-group" style="padding:0;">
 						<div class="col-md-2" style="padding:0;"><LABEL><input type="radio" name="h1" value="super" <?php if(isset($form) && $form['h1']=='super')echo "checked";?>> Supervisor</LABEL></div>
 						<div class="col-md-2" style="padding:0;"><LABEL><input type="radio" name="h1" value="fellow" <?php if(isset($form) && $form['h1']=='fellow')echo "checked";?>> Fellow Colleague</LABEL></div>
 						<div class="col-md-2" style="padding:0;"><LABEL><input type="radio" name="h1" value="ats" <?php if(isset($form) && $form['h1']=='ats')echo "checked";?>> A.T.S.</LABEL></div>
 						<div class="col-md-2" style="padding:0;"><LABEL><input type="radio" name="h1" value="hr" <?php if(isset($form) && $form['h1']=='hr')echo "checked";?>> H.R.</LABEL></div>
 						<div class="col-md-4" style="padding:0;">
 							<label for="other" class="control-label col-md-4">Other:</label>
 							<div class="col-md-8" style="padding-right:0;">
 							<div class="input-icon">	<input type="text" name="h1_other" class="form-control" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['h1_other'];?>"></div>
 							</div>
 						</div>
 					</div>
 				</div>
 				<div class="rs2">
 					<label for="rs2" class="control-label col-md-8 form-group" style="padding:0;">Describe the relationship you have with your Supervisor:</label>
 					<div class="rs2-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-4"><LABEL><input type="radio" name="h2" value="poor" <?php if(isset($form) && $form['h2']=='poor')echo "checked";?>> Poor</LABEL></div>
 						<div class="col-md-4"><LABEL><input type="radio" name="h2" value="fair" <?php if(isset($form) && $form['h2']=='fair')echo "checked";?>> Fair</LABEL></div>
 						<div class="col-md-4"><LABEL><input type="radio" name="h2" value="excellent" <?php if(isset($form) && $form['h2']=='excellent')echo "checked";?>> Excellent</LABEL></div>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="rs3">
 					<label for="other" class="control-label form-group">Comments:</label>
 					<div class=" form-group">
 						<textarea name="h2_comment" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['h2_comment'];?></textarea>
 					</div>
 				</div>
 				<div class="rs4">
 					<label for="rs4" class="control-label col-md-8 form-group" style="padding:0;">Describe the relationship you have with your co-workers:</label>
 					<div class="rs4-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-4"><LABEL><input type="radio" name="h3" value="poor" <?php if(isset($form) && $form['h3']=='poor')echo "checked";?>> Poor</LABEL></div>
 						<div class="col-md-4"><LABEL><input type="radio" name="h3" value="fair" <?php if(isset($form) && $form['h3']=='fair')echo "checked";?>> Fair</LABEL></div>
 						<div class="col-md-4"><LABEL><input type="radio" name="h3" value="excellent" <?php if(isset($form) && $form['h3']=='excellent')echo "checked";?>> Excellent</LABEL></div>
 					</div>
 				</div>

 				<div class="col-md-12 rs5" style="padding:0;">
 					<label for="other" class="control-label col-md-12 form-group" style="padding:0;">Comments:</label>
 					<div class="col-md-12 form-group" style="padding:0;">
 						<textarea name="h3_comment" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['h3_comment'];?></textarea>
 					</div>
 				</div>
 				<div class="rs6">
 					<label for="rs6" class="control-label col-md-8 form-group" style="padding:0;">Describe the relationship you have with the H.R. team:</label>
 					<div class="rs6-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-4"><LABEL><input type="radio" name="h4" value="poor" <?php if(isset($form) && $form['h4']=='poor')echo "checked";?>> Poor</LABEL></div>
 						<div class="col-md-4"><LABEL><input type="radio" name="h4" value="fair" <?php if(isset($form) && $form['h4']=='fair')echo "checked";?>> Fair</LABEL></div>
 						<div class="col-md-4"><LABEL><input type="radio" name="h4" value="excellent" <?php if(isset($form) && $form['h4']=='excellent')echo "checked";?>> Excellent</LABEL></div>
 					</div>
 				</div>

 				<div class="col-md-12 rs7" style="padding:0;">
 					<label for="other" class="control-label col-md-12 form-group" style="padding:0;">Comments:</label>
 					<div class="col-md-12 form-group" style="padding:0;">
 						<textarea name="h4_comment" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['h4_comment'];?></textarea>
 					</div>
 				</div>

 				<div class="rs8">
 					<label for="rs8" class="control-label col-md-8 form-group" style="padding:0;">Do you believe all GFS employees are treated with fairness and equality?	</label>
 					<div class="rs8-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-4"><LABEL><input type="radio" name="h5" value="yes" <?php if(isset($form) && $form['h5']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-4"><LABEL><input type="radio" name="h5" value="no" <?php if(isset($form) && $form['h5']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="rs9">
 					<label for="rs9" class="control-label col-md-8 form-group" style="padding:0;">How would you rate the level of teamwork within you deparment?</label>
 					<div class="rs9-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-4"><LABEL><input type="radio" name="h6" value="poor" <?php if(isset($form) && $form['h6']=='poor')echo "checked";?>> Poor</LABEL></div>
 						<div class="col-md-4"><LABEL><input type="radio" name="h6" value="fair" <?php if(isset($form) && $form['h6']=='fair')echo "checked";?>> Fair</LABEL></div>
 						<div class="col-md-4"><LABEL><input type="radio" name="h6" value="excellent" <?php if(isset($form) && $form['h6']=='excellent')echo "checked";?>> Excellent</LABEL></div>
 					</div>
 				</div>
 				<div class="rs10">
 					<label for="rs10" class="control-label col-md-8 form-group" style="padding:0;">How would you rate the communication within your department?</label>
 					<div class="rs10-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-4"><LABEL><input type="radio" name="h7" value="poor" <?php if(isset($form) && $form['h7']=='poor')echo "checked";?>> Poor</LABEL></div>
 						<div class="col-md-4"><LABEL><input type="radio" name="h7" value="fair" <?php if(isset($form) && $form['h7']=='fair')echo "checked";?>> Fair</LABEL></div>
 						<div class="col-md-4"><LABEL><input type="radio" name="h7" value="excellent" <?php if(isset($form) && $form['h7']=='excellent')echo "checked";?>> Excellent</LABEL></div>
 					</div>
 				</div>

 				<div class="rs11">
 					<label for="rs11" class="control-label col-md-8 form-group" style="padding:0;">How would you rate the communication within the company?</label>
 					<div class="rs11-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-4"><LABEL><input type="radio" name="h8" value="poor" <?php if(isset($form) && $form['h8']=='poor')echo "checked";?>> Poor</LABEL></div>
 						<div class="col-md-4"><LABEL><input type="radio" name="h8" value="fair" <?php if(isset($form) && $form['h8']=='fair')echo "checked";?>> Fair</LABEL></div>
 						<div class="col-md-4"><LABEL><input type="radio" name="h8" value="excellent" <?php if(isset($form) && $form['h8']=='excellent')echo "checked";?>> Excellent</LABEL></div>
 					</div>
 				</div>

 				<div class="col-md-12 rs12" style="padding:0;">
 					<label for="other" class="control-label col-md-12 form-group" style="padding:0;">Who has had the most positive impact on you since you started at GFS and why?	</label>
 					<div class="col-md-12 form-group" style="padding:0;">
 						<textarea name="h9" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['h9'];?></textarea>
 					</div>
 				</div>
 				<div class="col-md-12 rs13" style="padding:0;">
 					<label for="other" class="control-label col-md-12 form-group" style="padding:0;">How would you improve your department? (e.g. better communication with supervisors etc)</label>
 					<div class="col-md-12 form-group" style="padding:0;">
 						<textarea name="h10" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['h10'];?></textarea>
 					</div>
 				</div>
    </div>
 				<div class="jst col-md-12">
 					<div class="title form-group" style="color:#ff0000;text-decoration:underline;font-weight:bold;text-align:center;">
 						Section 3: Job Specific Training
 					</div>
 					<div class="jst1">
 						<label for="jst1" class="control-label col-md-8 form-group" style="padding:0;">Were your job expectations clearly described to you?</label>
 						<div class="jst1-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="jst1" value="yes" <?php if(isset($form) && $form['jst1']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="jst1" value="no" <?php if(isset($form) && $form['jst1']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>
 					<div class="jst2">
 						<label for="jst2" class="control-label col-md-8 form-group" style="padding:0;">Do you understand how to read the stickers?</label>
 						<div class="jst2-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="jst2" value="yes" <?php if(isset($form) && $form['jst2']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="jst2" value="no" <?php if(isset($form) && $form['jst2']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>
 					<div class="jst3">
 						<label for="jst3" class="control-label col-md-8 form-group" style="padding:0;">Do you feel you had sufficient time to learn your job functions in order to meet the performance expectations within the timelines given?</label>
 						<div class="jst3-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="jst3" value="yes" <?php if(isset($form) && $form['jst3']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="jst3" value="no" <?php if(isset($form) && $form['jst3']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>
 					<div class="jst4">
 						<label for="jst4" class="control-label col-md-8 form-group" style="padding:0;">How long were you trained before you were left on your own? (days/weeks)</label>
 						<div class="jst4-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-12" style="padding-right:0;">
                            <div class="input-icon"> <input type="text" name="jst4" class="form-control" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['jst4'];?>"></div></div>
 						</div>
 					</div>

 					<div class="jst5">
 						<label for="jst5" class="control-label col-md-8 form-group" style="padding:0;">Do you feel this was a sufficient amount of time?</label>
 						<div class="jst5-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="jst5" value="yes" <?php if(isset($form) && $form['jst5']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="jst5" value="no" <?php if(isset($form) && $form['jst5']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>

 					<div class="jst6">
 						<label for="jst6" class="control-label col-md-8 form-group" style="padding:0;">Do you feel you were given constructive feedback and coaching?</label>
 						<div class="jst6-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="jst6" value="yes" <?php if(isset($form) && $form['jst6']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="jst6" value="no" <?php if(isset($form) && $form['jst6']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>
 					<div class="jst7">
 						<label for="jst7" class="control-label col-md-8 form-group" style="padding:0;">What was one of the most helpful aspects of training in your opinion? Why?</label>
 						<div class="jst6-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="jst7" value="yes" <?php if(isset($form) && $form['jst7']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="jst7" value="no" <?php if(isset($form) && $form['jst7']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
                         <div class="col-md-12 form-group" style="padding:0;">
 							<textarea name="jst7_why" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['jst7_why'];?></textarea>
 						</div>
 					</div>
 					<div class="jst8">
 						<label for="jst8" class="control-label col-md-8 form-group" style="padding:0;">Is there anything you would like more training in?</label>
 						<div class="col-md-12 form-group" style="padding:0;">
 							<textarea name="jst8" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['jst8'];?></textarea>
 						</div>
 					</div>
 					<div class="jst9">
 						<label for="jst9" class="control-label col-md-8 form-group" style="padding:0;">What part of training  did you find most challenging?  How did you overcome it?</label>
 						<div class="col-md-12 form-group" style="padding:0;">
 							<textarea name="jst9" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['jst9'];?></textarea>
 						</div>
 					</div>
 					<div class="jst10">
 						<label for="jst10" class="control-label col-md-8 form-group" style="padding:0;">What suggestions do you have that might help to improve the training process for new employees?</label>
 						<div class="col-md-12 form-group" style="padding:0;">
 							<textarea name="jst10" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['jst10'];?></textarea>
 						</div>
 					</div>
 					<div class="jst11">
 						<label for="jst11" class="control-label col-md-8 form-group" style="padding:0;">Is there anything you feel makes your job more of a challenge then it needs to be?</label>
 						<div class="col-md-12 form-group" style="padding:0;">
 							<textarea name="jst11" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['jst11'];?></textarea>
 						</div>
 					</div>

 				</div>

 				<div class="ohs col-md-12">
 					<div class="title form-group" style="color:#ff0000;text-decoration:underline;font-weight:bold;text-align:center;">
 						Section 4:  Occupational Health & Safety
 					</div>
 					<div class="ohs1">
 						<label for="ohs1" class="control-label col-md-8 form-group" style="padding:0;">Do you know the evacuation meeting point in case of an emergency?</label>
 						<div class="ohs1-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs1" value="yes" <?php if(isset($form) && $form['ohs1']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs1" value="no" <?php if(isset($form) && $form['ohs1']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>
 					<div class="ohs2">
 						<label for="ohs2" class="control-label col-md-8 form-group" style="padding:0;">Are you familiar with your Rights under the Occupational Health & Safety Act?</label>
 						<div class="ohs2-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs2" value="yes" <?php if(isset($form) && $form['ohs2']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs2" value="no" <?php if(isset($form) && $form['ohs2']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>
 					<div class="ohs3">
 						<label for="ohs3" class="control-label col-md-8 form-group" style="padding:0;">Has your Supervisor explained the particular hazards that may be present in the work area?</label>
 						<div class="ohs3-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs3" value="yes" <?php if(isset($form) && $form['ohs3']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs3" value="no" <?php if(isset($form) && $form['ohs3']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>
 					<div class="ohs4">
 						<label for="ohs4" class="control-label col-md-8 form-group" style="padding:0;">Do you know your representative on the Joint Health & Safety Committee?</label>
 						<div class="ohs4-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs4" value="yes" <?php if(isset($form) && $form['ohs4']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs4" value="no" <?php if(isset($form) && $form['ohs4']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>
 					<div class="ohs5">
 						<label for="ohs5" class="control-label col-md-8 form-group" style="padding:0;">Do you know where we post Health & Safety information?</label>
 						<div class="ohs5-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs5" value="yes" <?php if(isset($form) && $form['ohs5']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs5" value="no" <?php if(isset($form) && $form['ohs5']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>
 					<div class="ohs6">
 						<label for="ohs6" class="control-label col-md-8 form-group" style="padding:0;">Do you know where the location of MSDS information, eyewash station, first-aid room and fire extinguishers are?</label>
 						<div class="ohs6-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs6" value="yes" <?php if(isset($form) && $form['ohs6']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs6" value="no" <?php if(isset($form) && $form['ohs6']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>
 					<div class="ohs7">
 						<label for="ohs7" class="control-label col-md-8 form-group" style="padding:0;">Are you familiar with the injury/incident reporting procedures?</label>
 						<div class="ohs7-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs7" value="yes" <?php if(isset($form) && $form['ohs7']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs7" value="no" <?php if(isset($form) && $form['ohs7']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>
 					<div class="ohs8">
 						<label for="ohs8" class="control-label col-md-8 form-group" style="padding:0;">Have you reviewed the DC. Operations Safety Handbook?</label>
 						<div class="ohs8-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs8" value="yes" <?php if(isset($form) && $form['ohs8']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs8" value="no" <?php if(isset($form) && $form['ohs8']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>
 					<div class="ohs9">
 						<label for="ohs9" class="control-label col-md-8 form-group" style="padding:0;">Has anyone advised you about Personal Protective Equipment that is available or required for particular environment?</label>
 						<div class="ohs9-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs9" value="yes" <?php if(isset($form) && $form['ohs9']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-6"><LABEL><input type="radio" name="ohs9" value="no" <?php if(isset($form) && $form['ohs9']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>

 					<div class="ohs10">
 						<label for="ohs10" class="control-label col-md-8 form-group" style="padding:0;">Comments</label>
 						<div class="col-md-12 form-group" style="padding:0;">
 							<textarea name="ohs10" class="form-control"> <?php if(isset($form) && isset($_GET['form_id']))echo $form['ohs10'];?></textarea>
 						</div>
 					</div>

 				</div>

 				<div class="coc col-md-12">
 					<div class="title form-group" style="color:#ff0000;text-decoration:underline;font-weight:bold;text-align:center;">
 						Confirmation of Completion
 					</div>
 					<div class="">
 						<div class="col-md-2"></div>
 						<div class="col-md-3 form-group"><div class="input-icon"><input type="text" class="form-control" name="employee" placeholder="Employee" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['employee'];?>"></div>
 						</div>
                         <div class="col-md-2"></div>
 						<div class="col-md-3 form-group"><div class="input-icon"><input type="text" class="form-control datepicker" name="emp_date" placeholder="Date" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['emp_date'];?>"></div>
 						</div>
                         <div class="col-md-2"></div>
 					</div>
 					<div class="clearfix"></div>
 					<div class="">
 						<div class="col-md-2"></div>
 						<div class="col-md-3">
 							<div class="input-icon"><input type="text" class="form-control" name="hr_representative" placeholder="H.R. Representative" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['hr_representative'];?>"></div>
 						     </div>
                              	<div class="col-md-2"></div>
 							<div class="col-md-3 form-group"><div class="input-icon"><input type="text" class="form-control datepicker" name="hr_date" placeholder="Date" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['hr_date'];?>"></div>
 							</div>
                             <div class="col-md-2"></div>
 						</div>
 					</div>


 			
                <div class="clearfix"></div>
        
            <div class="form-actions">
                <button  type="submit" class="btn btn-primary" >
                    Submit <i class="m-icon-swapright m-icon-white"></i>
                </button>
            </div>
            
        
 			</form>
            <?php } elseif(!isset($row)&&!isset($_GET['msg'])) {
                 echo '<div class="clearfix"></div><div class="alert alert-danger display-hide" style="display: block;">
                                <button class="close" data-close="alert"></button>
                                Sorry, the profile does not exist.
                                </div>'; 
            } elseif(isset($row)&&($row['profile_type']!='9' && $row['profile_type']!='12')&&!isset($_GET['msg'])) {
                 echo '<div class="clearfix"></div><div class="alert alert-danger display-hide" style="display: block;">
                                <button class="close" data-close="alert"></button>
                            User can not submit this form.
                        </div>';
            } else {
                 echo '<div class="clearfix"></div><div class="alert alert-info display-hide" style="display: block;">
                                <button class="close" data-close="alert"></button>
                            Survey submitted successfully.
                        </div>';
            }

        backbutton();
            ?>
</div>

 		</div>
        <script>
    $(function(){
        <?php if(isset($_GET['form_id'])){?>
            $('.formz input').attr('disabled','disabled');
            $('.formz textarea').attr('readonly','readonly');
            $('.subz').hide();
            
        <?php }?>
    })
    
    </script>
    <?php initdatepicker("mm/dd/yy"); ?>
 	</body>
 	</html>