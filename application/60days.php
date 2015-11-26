<title>	60 Day Employee Review</title>  <?php
    include("api.php");
    includeCSS("login");

    $webroot = $_SERVER["REQUEST_URI"];
    $start = strpos($webroot, "/", 1)+1;
    $webroot = substr($webroot,0,$start);

    $logo = 'img/logos/';
    $company_name = "";

     $con = connectdb();

    if (isset($_GET["user_id"]) && !isset($_GET["p_id"])) {$_GET["p_id"] = $_GET["user_id"];}
    if (isset($_GET["p_id"])) {
        $row = first("SELECT * FROM profiles where id = " . $_GET["p_id"]);
        if ($row) {
            $uname = $row['username'];
            $email = $row['email'];
        }
    }

    if(isset($_GET['form_id'])) {
        $form = first("SELECT * FROM 60days where id = " . $_GET["form_id"]);
    }
    
?>

 <!--DOCTYPE html>
 <html>
 <head>

 	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
 </head>
 <body class="login"-->

 	<!-- Latest compiled and minified CSS -->
 	<link href="<?= $webroot; ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $webroot; ?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $webroot; ?>assets/admin/pages/css/login3.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $webroot; ?>assets/global/css/components.css" rel="stylesheet" type="text/css"/>
    <style>
    p{text-align:justify!important;}
    </style>
 	<div style="width:77%" class="content">
 		<div class="logo col-md-12" style="margin-top: 0;">
 			<img src="<?= $webroot; ?>img/logo.png" />
 		</div>

 		<div class="title col-md-12 form-group" style="text-align:center;color:#ff0000;">
 			60 Day Employee Review
 			<span style="display:block;">TRANSPORTATION</span>
 		</div>
        <?php if((!isset($_GET['msg']) || (isset($_GET['msg']) && $_GET['msg']=='error'))&&(isset($row) && ($row['profile_type']=='5'||$row['profile_type']=='7'||$row['profile_type']=='8'))){
            if(isset($_GET['msg']) && $_GET['msg']=='error')
             echo '<div class="clearfix"></div><div class="alert alert-danger display-hide" style="display: block;">
                        <button class="close" data-close="alert"></button>
                        Could not submit the form. Please try again.
                        </div>';    
        ?>
 		<form action="<?php echo $webroot;?>rapid/days/60"  method="post" class="formz" >
            <input type="hidden" name="profile_id" value="<?php if(isset($_GET['p_id']))echo $_GET['p_id']; else echo "0";?>" />
 			<div class="name form-group">
 				<label for="name" class="control-label col-md-2">Name:</label>
 				<div class="col-md-4"> <div class="input-icon"><input type="text" class="form-control" name="surname" placeholder="Surname" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['surname'];?>"></div>
 				</div>
                 <div class="col-md-2">&nbsp;</div>
 				<div class="col-md-4"> <div class="input-icon"><input type="text" name="given" class="form-control" placeholder="Given" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['given'];?>"></div>
 		         </div>
         	</div>
 			<div class="clearfix"></div>
 			<div class="">
 			<div class="hire form-group">
 				<label for="hire" class="control-label col-md-2 form-group">Hire Date:</label>
 				<div class="col-md-4 form-group"> <div class="input-icon"><input type="text" class="form-control datepicker" name="h_date" placeholder="YYYY-MM-DD" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['h_date'];?>"></div>
 			</div>
            </div>
 			<div class="review form-group">
 				<label for="review" class="control-label col-md-2 form-group">Review Date:</label>
 				<div class="col-md-4 form-group"> <div class="input-icon"><input type="text" class="form-control datepicker " name="r_date" placeholder="YYYY-MM-DD" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['r_date'];?>"></div>
 			</div>
 			</div>
            </div>
 			<div class="supervisor form-group">
 				<label for="supervisor" class="control-label col-md-2 form-group">Supervisor:</label>
 				<div class="supervisor-radio col-md-10 form-group" style="padding:0;">
 					<div class="col-md-2"><LABEL><input type="radio" name="supervisor" value="Mario Ross" <?php if(isset($form) && isset($_GET['form_id']) && $form['supervisor']=='Mario Ross')echo "checked";?>> Mario Ross</LABEL></div>
 					<div class="col-md-2"><LABEL><input type="radio" name="supervisor" value="Roy Ralph"  <?php if(isset($form) && isset($_GET['form_id']) && $form['supervisor']=='Roy Ralph')echo "checked";?>> Roy Ralph</LABEL></div>
 					<div class="col-md-2"><LABEL><input type="radio" name="supervisor" value="Gord Ade"  <?php if(isset($form) && isset($_GET['form_id']) && $form['supervisor']=='Gord Ade')echo "checked";?>> Gord Ade</LABEL></div>
 					<div class="col-md-2"><LABEL><input type="radio" name="supervisor" value="Mark Dunlop"  <?php if(isset($form) && isset($_GET['form_id']) && $form['supervisor']=='Mark Dunlop')echo "checked";?>> Mark Dunlop</LABEL></div>
 					<div class="col-md-2"><LABEL><input type="radio" name="supervisor" value="Henry Diego"  <?php if(isset($form) && isset($_GET['form_id']) && $form['supervisor']=='Henry Diego')echo "checked";?>> Henry Diego</LABEL></div>
 					<div class="col-md-2"><LABEL><input type="radio" name="supervisor" value="Dave Halliday"  <?php if(isset($form) && isset($_GET['form_id']) && $form['supervisor']=='Dave Halliday')echo "checked";?>> Dave Halliday</LABEL></div>
 					<div class="col-md-4"><LABEL><input type="radio" name="supervisor" value="Brett Whitehead"  <?php if(isset($form) && isset($_GET['form_id']) && $form['supervisor']=='Brett Whitehead')echo "checked";?>> Brett Whitehead</LABEL></div>
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
 					<label for="hr1" class="control-label col-md-8 form-group" style="padding-left:0;">Does this job live up to your expectations?</label>
 					<div class="hr1-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr1" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr1']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr1" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr1']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="hr2">
 					<label for="hr2" class="control-label col-md-12 form-group" style="padding-left:0;">What could we have done to better prepare you for the job?</label>
 					<div class="col-md-12 form-group" style="padding-left:0;">
 						<textarea name="hr2" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['hr2'];?></textarea>
 					</div>
 				</div>
 				<div class="hr3">
 					<label for="hr3" class="control-label col-md-8 form-group" style="padding-left:0;">Is there something we should have discussed in the interview process but failed	to touch on or should have expanded on about the job?</label>
 					<div class="hr3-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr3" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr3']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr3" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr3']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="hr4">
 					<label for="hr4" class="control-label col-md-12 form-group" style="padding-left:0;">If "Yes" please explain:</label>
 					<div class="col-md-12 form-group" style="padding-left:0;">
 						<textarea name="hr4" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['hr4'];?></textarea>
 					</div>
 				</div>
 				<div class="hr5">
 					<label for="hr5" class="control-label col-md-8 form-group" style="padding-left:0;">Do you feel your H.R. team understands the challenges that are unique to your  position and provides adequate support and guidance?</label>
 					<div class="hr5-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr5" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr5']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr5" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr5']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="hr6">
 					<label for="hr6" class="control-label col-md-8 form-group" style="padding-left:0;">Did you know there is a H.R representative here every Friday from 5am  - 5pm?</label>
 					<div class="hr6-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr6" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr6']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr6" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr6']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="hr7">
 					<label for="hr7" class="control-label col-md-8 form-group" style="padding-left:0;">Were you aware you will be receiving benefits after 3 months of starting at GFS?</label>
 					<div class="hr7-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr7" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr7']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr7" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr7']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="hr8">
 					<label for="hr8" class="control-label col-md-8 form-group" style="padding-left:0;">Are you aware that you must advise H.R. of any personal information changes?</label>
 					<div class="hr8-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr8" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr8']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr8" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr8']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="hr9">
 					<label for="hr9" class="control-label col-md-8 form-group" style="padding-left:0;">Do you understand your responsibilities as a GFS employee?(e.g. sick-line, Code of Business Conduct)</label>
 					<div class="hr9-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr9" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr9']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr9" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr9']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="hr10">
 					<label for="hr10" class="control-label col-md-8 form-group" style="padding-left:0;">Do you know we have an Employee Announcement board in the lunchroom?</label>
 					<div class="hr10-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr10" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr10']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr10" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr10']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="hr11">
 					<label for="hr11" class="control-label col-md-8 form-group" style="padding-left:0;">Are you aware of the referral process at GFS?</label>
 					<div class="hr11-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr11" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr11']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr11" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr11']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="hr12">
 					<label for="hr12" class="control-label col-md-8 form-group" style="padding-left:0;">Do you know where we post internal job opportunities?</label>
 					<div class="hr12-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr12" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr12']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr12" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr12']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="hr13">
 					<label for="hr13" class="control-label col-md-8 form-group" style="padding-left:0;">Have you completed your AODA training?</label>
 					<div class="hr13-radio col-md-4 form-group" style="padding:0;">
 						<div class="col-md-6"><LABEL><input type="radio" name="hr13" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr13']=='yes')echo "checked";?>> Yes</LABEL></div>
 						<div class="col-md-6"><LABEL><input type="radio" name="hr13" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['hr13']=='no')echo "checked";?>> No</LABEL></div>
 					</div>
 				</div>
 				<div class="clearfix"></div>
 				<div class="hr14">
 					<label for="hr14" class="control-label col-md-12 form-group" style="padding-left:0;">Additional Comments or Suggestions:</label>
 					<div class="col-md-12 form-group" style="padding-left:0;">
 						<textarea name="hr14" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['hr14'];?></textarea>
 					</div>
 				</div>
 				</div>

 				<div class="rs col-md-12">
 					<div class="title form-group" style="color:#ff0000;text-decoration:underline;font-weight:bold;text-align:center;">
 						Section 2: Relationships
 					</div>
 					<div class="h1s">
 						<label for="h1" class="control-label col-md-12 form-group" style="padding:0;">Who do you feel comfortable going to with a problem or issue? (please circle)</label>
 						<div class="h1-radio col-md-12 form-group" style="padding:0;">
 							<div class="col-md-2" style="padding:0;"><LABEL><input type="radio" name="h1" value="supervisor"  <?php if(isset($form) && isset($_GET['form_id']) && $form['supervisor']=='yes')echo "checked";?>> Supervisor</LABEL></div>
 							<div class="col-md-2" style="padding:0;"><LABEL><input type="radio" name="h1" value="fellow"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h1']=='fellow')echo "checked";?>> Fellow Colleague</LABEL></div>
 							<div class="col-md-2" style="padding:0;"><LABEL><input type="radio" name="h1" value="ats"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h1']=='ats')echo "checked";?>> A.T.S</LABEL></div>
 							<div class="col-md-2" style="padding:0;"><LABEL><input type="radio" name="h1" value="hr"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h1']=='hr')echo "checked";?>> H.R.</LABEL></div>
 							<div class="col-md-4" style="padding:0;">
 								<label for="other" class="control-label col-md-4">Other:</label>
 								<div class="col-md-8" style="padding-right:0;">
 									<input type="text" name="h1_other" class="form-control" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['h1_other'];?>">
 								</div>
 							</div>
 						</div>
 					</div>
 					<div class="h2s">
 						<label for="h2" class="control-label col-md-8 form-group" style="padding:0;">Describe the relationship you have with your Supervisor:</label>
 						<div class="h2-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-4"><LABEL><input type="radio" name="h2" value="poor"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h2']=='poor')echo "checked";?>> Poor</LABEL></div>
 							<div class="col-md-4"><LABEL><input type="radio" name="h2" value="fair"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h2']=='fair')echo "checked";?>> Fair</LABEL></div>
 							<div class="col-md-4"><LABEL><input type="radio" name="h2" value="excellent"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h2']=='excellent')echo "checked";?>> Excellent</LABEL></div>
 						</div>
 					</div>

 					<div class="col-md-12 rs3" style="padding:0;">
 						<label for="other" class="control-label col-md-12 form-group" style="padding:0;">Comments:</label>
 						<div class="col-md-12 form-group" style="padding:0;">
 							<textarea name="h2_comment" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['h2_comment'];?></textarea>
 						</div>
 					</div>
 					<div class="rs4">
 						<label for="rs4" class="control-label col-md-8 form-group" style="padding:0;">Describe the relationship you have with your co-workers:</label>
 						<div class="rs4-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-4"><LABEL><input type="radio" name="h3" value="poor"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h3']=='poor')echo "checked";?>> Poor</LABEL></div>
 							<div class="col-md-4"><LABEL><input type="radio" name="h3" value="fair"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h3']=='fair')echo "checked";?>> Fair</LABEL></div>
 							<div class="col-md-4"><LABEL><input type="radio" name="h3" value="excellent"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h3']=='excellent')echo "checked";?>> Excellent</LABEL></div>
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
 							<div class="col-md-4"><LABEL><input type="radio" name="h4" value="poor"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h4']=='poor')echo "checked";?>> Poor</LABEL></div>
 							<div class="col-md-4"><LABEL><input type="radio" name="h4" value="fair"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h4']=='fair')echo "checked";?>> Fair</LABEL></div>
 							<div class="col-md-4"><LABEL><input type="radio" name="h4" value="excellent"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h4']=='excellent')echo "checked";?>> Excellent</LABEL></div>
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
 							<div class="col-md-4"><LABEL><input type="radio" name="h5" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h5']=='yes')echo "checked";?>> Yes</LABEL></div>
 							<div class="col-md-4"><LABEL><input type="radio" name="h5" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h5']=='no')echo "checked";?>> No</LABEL></div>
 						</div>
 					</div>
 					<div class="rs9">
 						<label for="rs9" class="control-label col-md-8 form-group" style="padding:0;">How would you rate the level of teamwork within you deparment?</label>
 						<div class="rs9-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-4"><LABEL><input type="radio" name="h6" value="poor"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h6']=='poor')echo "checked";?>> Poor</LABEL></div>
 							<div class="col-md-4"><LABEL><input type="radio" name="h6" value="fair"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h6']=='fair')echo "checked";?>> Fair</LABEL></div>
 							<div class="col-md-4"><LABEL><input type="radio" name="h6" value="excellent"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h6']=='excellent')echo "checked";?>> Excellent</LABEL></div>
 						</div>
 					</div>
 					<div class="rs10">
 						<label for="rs10" class="control-label col-md-8 form-group" style="padding:0;">How would you rate the communication within your department?</label>
 						<div class="rs10-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-4"><LABEL><input type="radio" name="h7" value="poor"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h7']=='poor')echo "checked";?>> Poor</LABEL></div>
 							<div class="col-md-4"><LABEL><input type="radio" name="h7" value="fair"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h7']=='fair')echo "checked";?>> Fair</LABEL></div>
 							<div class="col-md-4"><LABEL><input type="radio" name="h7" value="excellent"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h7']=='excellent')echo "checked";?>> Excellent</LABEL></div>
 						</div>
 					</div>

 					<div class="rs11">
 						<label for="rs11" class="control-label col-md-8 form-group" style="padding:0;">How would you rate the communication within the company?</label>
 						<div class="rs11-radio col-md-4 form-group" style="padding:0;">
 							<div class="col-md-4"><LABEL><input type="radio" name="h8" value="poor"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h6']=='poor')echo "checked";?>> Poor</LABEL></div>
 							<div class="col-md-4"><LABEL><input type="radio" name="h8" value="fair"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h6']=='fair')echo "checked";?>> Fair</LABEL></div>
 							<div class="col-md-4"><LABEL><input type="radio" name="h8" value="excellent"  <?php if(isset($form) && isset($_GET['form_id']) && $form['h6']=='excellent')echo "checked";?>> Excellent</LABEL></div>
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
 							<label for="jst1" class="control-label col-md-8 form-group" style="padding:0;">Do you understand how to process all job-related paperwork?</label>
 							<div class="jst1-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="jst1" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst1']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="jst1" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst1']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>
 						<div class="jst2">
 							<label for="jst2" class="control-label col-md-8 form-group" style="padding:0;">Do you feel you had sufficient time to learn your job functions in order to meet the performance expectations within the timelines given?</label>
 							<div class="jst2-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="jst2" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst2']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="jst2" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst2']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>
 						<div class="jst3">
 							<label for="jst3" class="control-label col-md-8 form-group" style="padding:0;">How long were you trained before you were left on your own? (days/weeks)</label>
 							<div class="jst3-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-12" style="padding-right:0;"> <div class="input-icon"><input type="text" name="jst3" class="form-control" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['jst3'];?>"></div>
 						</div>
                        </div>
 						<div class="jst4">
 							<label for="jst4" class="control-label col-md-8 form-group" style="padding:0;">Do you feel this was a sufficient amount of time?</label>
 							<div class="jst4-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="jst4" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst4']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="jst4" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst4']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>

 						<div class="jst5">
 							<label for="jst5" class="control-label col-md-8 form-group" style="padding:0;">Do you feel you were given constructive feedback and coaching?</label>
 							<div class="jst5-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="jst5" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst5']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="jst5" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst5']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>

 						<div class="jst6">
 							<label for="jst6" class="control-label col-md-8 form-group" style="padding:0;">Do you know how to use our handheld and all the features of it? (ex: Pre/Post trip, Customer Orders, Credits)</label>
 							<div class="jst6-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="jst6" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst6']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="jst6" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst6']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>
 						<div class="jst7">
 							<label for="jst7" class="control-label col-md-8 form-group" style="padding:0;">Do you understand what the route tracker in your power unit is tracking?</label>
 							<div class="jst7-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="jst7" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst7']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="jst7" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst7']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>
 						<div class="jst8">
 							<label for="jst8" class="control-label col-md-8 form-group" style="padding:0;">Do you know what temps to record for our cold chain validation?</label>
 							<div class="jst8-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="jst8" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst8']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="jst8" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst8']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>
 						<div class="jst9">
 							<label for="jst9" class="control-label col-md-8 form-group" style="padding:0;">What was one of the most helpful aspects of training in your opinion? Why?</label>
 							<div class="jst9-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="jst9" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst9']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="jst9" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst9']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 							<div class="col-md-12 form-group" style="padding:0;">
 							<textarea name="jst9_why" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['jst9_why'];?></textarea>
 						</div>
 						</div>
 						<div class="jst10">
 							<label for="jst10" class="control-label col-md-12 form-group" style="padding:0;">What part of training influences you the most?</label>
 							<div class="jst10-radio col-md-12 form-group" style="padding:0;">
 								<div class="col-md-4"><LABEL><input type="radio" name="jst10" value="oneon"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst10']=='oneon')echo "checked";?>> One-on-one with trainer</LABEL></div>
 								<div class="col-md-4"><LABEL><input type="radio" name="jst10" value="warehouse"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst10']=='warehouse')echo "checked";?>> Warehouse Training</LABEL></div>
 								<div class="col-md-4"><LABEL><input type="radio" name="jst10" value="driving"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst10']=='driving')echo "checked";?>> Driving training</LABEL></div>
 							</div>
 							<div class="col-md-12 form-group" style="padding:0;">
 							<textarea name="jst10_how" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['jst10_how'];?></textarea>
 						</div>
 						</div>
 						<div class="jst11">
 							<label for="jst11" class="control-label col-md-12 form-group" style="padding:0;">What part of training  did you find most challenging?  How did you overcome it?</label>
 							<div class="jst11-radio col-md-12 form-group" style="padding:0;">
 								<div class="col-md-4"><LABEL><input type="radio" name="jst11" value="oneon"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst11']=='oneon')echo "checked";?>> One-on-one with trainer</LABEL></div>
 								<div class="col-md-4"><LABEL><input type="radio" name="jst11" value="warehouse"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst11']=='warehouse')echo "checked";?>> Warehouse Training</LABEL></div>
 								<div class="col-md-4"><LABEL><input type="radio" name="jst11" value="driving"  <?php if(isset($form) && isset($_GET['form_id']) && $form['jst11']=='driving')echo "checked";?>> Driving training</LABEL></div>
 							</div>
 							<div class="col-md-12 form-group" style="padding:0;">
 							<textarea name="jst11_how" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['jst11_how'];?></textarea>
 						</div>
 						</div>
 							<div class="jst12">
 							<label for="jst12" class="control-label col-md-8 form-group" style="padding:0;">What suggestions do you have that might help to improve the training process for new employees?</label>
 							<div class="col-md-12 form-group" style="padding:0;">
 								<textarea name="jst12" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['jst12'];?></textarea>
 							</div>
 						</div>
 						<div class="jst13">
 							<label for="jst13" class="control-label col-md-12 form-group" style="padding:0;">Is there anything you would like to train more on?  Place an "X" where applicable:</label>
 							<div class="jst13-radio col-md-12 form-group" style="padding:0;">
                            <?php if(isset($form) && isset($_GET['form_id']))
                            {
                               $checks = explode(",",$form['jst13']); 
                            }
                            else
                                $checks = array(); 
                            ?>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="divr"  <?php if(in_array('divr', $checks))echo "checked";?>> D.I.V.R</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="time"  <?php if(in_array('time', $checks))echo "checked";?>> Time Sheets</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="fueling"  <?php if(in_array('fueling', $checks))echo "checked";?>> Fueling Power Units</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="bid"  <?php if(in_array('bid', $checks))echo "checked";?>> Bid Process</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="fuel"  <?php if(in_array('fuel', $checks))echo "checked";?>> Fuel Sheets</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="reefer"  <?php if(in_array('reefer', $checks))echo "checked";?>> Reefer Training</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="parking"  <?php if(in_array('parking', $checks))echo "checked";?>> Parking Tickets</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="policies"  <?php if(in_array('policies', $checks))echo "checked";?>> Policies & Procedures</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="backing"  <?php if(in_array('backing', $checks))echo "checked";?>> Backing</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="voicemail"  <?php if(in_array('voicemail', $checks))echo "checked";?>> Voicemail</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="hand"  <?php if(in_array('hand', $checks))echo "checked";?>> Hand Carts</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="credits"  <?php if(in_array('credits', $checks))echo "checked";?>> Credits</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="sick"  <?php if(in_array('sick', $checks))echo "checked";?>> Sick-line Proceduer</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="coupling"  <?php if(in_array('coupling', $checks))echo "checked";?>> Coupling & Uncoupling</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="damages"  <?php if(in_array('damages', $checks))echo "checked";?>> Damages</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="customer"  <?php if(in_array('customer', $checks))echo "checked";?>> Customer Service</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="log"  <?php if(in_array('log', $checks))echo "checked";?>> Logbooks</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="fourzero"  <?php if(in_array('fourzero', $checks))echo "checked";?>> 407</LABEL></div>
 								<div class="">
 									<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="wd"  <?php if(in_array('wd', $checks))echo "checked";?>>Wash Day</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="ap"  <?php if(in_array('ap', $checks))echo "checked";?>> Attendance Policy</LABEL></div>
 								<div class="col-md-4"></div>
 								</div>
 								<div class="">
 									<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="htsf"  <?php if(in_array('htsf', $checks))echo "checked";?>> How to Slide a fifth Wheel</LABEL></div>
 								<div class="col-md-4"> <LABEL><input type="checkbox" name="train[]" value="dqp"  <?php if(in_array('dqp', $checks))echo "checked";?>> Driver's Qualification Policy</LABEL></div>
 								<div class="col-md-4"></div>
 								</div>
 								
 							</div>
 						</div>

 					</div>
 					</div>

 					<div class="ohs col-md-12">
 						<div class="title" style="color:#ff0000;text-decoration:underline;font-weight:bold;text-align:center;">
 							Section 4:  Occupational Health & Safety
 						</div>
 						<div class="ohs1">
 							<label for="ohs1" class="control-label col-md-8 form-group" style="padding:0;">Do you know what to do in the event of an accident/collision?</label>
 							<div class="ohs1-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs1" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs1']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs1" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs1']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>
 						<div class="ohs2">
 							<label for="ohs2" class="control-label col-md-8 form-group" style="padding:0;">Are you familiar with your Rights under the Occupational Health & Safety Act?</label>
 							<div class="ohs2-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs2" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs2']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs2" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs2']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>
 						<div class="ohs3">
 							<label for="ohs3" class="control-label col-md-8 form-group" style="padding:0;">Has your Supervisor explained the particular hazards that may be present in the work area?</label>
 							<div class="ohs3-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs3" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs3']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs3" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs3']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>
 						<div class="ohs4">
 							<label for="ohs4" class="control-label col-md-8 form-group" style="padding:0;">Do you know your representative on the Joint Health & Safety Committee?</label>
 							<div class="ohs4-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs4" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs4']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs4" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs4']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>
 						<div class="ohs5">
 							<label for="ohs5" class="control-label col-md-8 form-group" style="padding:0;">Do you know where we post Health & Safety information?</label>
 							<div class="ohs5-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs5" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs5']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs5" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs5']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>
 						<div class="ohs6">
 							<label for="ohs6" class="control-label col-md-8 form-group" style="padding:0;">Do you know the location of the first-aid kits and fire extinguishers?</label>
 							<div class="ohs6-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs6" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs6']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs6" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs6']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>
 						<div class="ohs7">
 							<label for="ohs7" class="control-label col-md-8 form-group" style="padding:0;">Are you familiar with the injury/incident reporting procedures?</label>
 							<div class="ohs7-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs7" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs7']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs7" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs7']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>
 						<div class="ohs8">
 							<label for="ohs8" class="control-label col-md-8 form-group" style="padding:0;">Are you comfortable with pre and post trip inspections?</label>
 							<div class="ohs8-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs8" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs8']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs8" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs8']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>
 						<div class="ohs9">
 							<label for="ohs9" class="control-label col-md-8 form-group" style="padding:0;">Are you familiar with our trailer security program, and when to use locks?</label>
 							<div class="ohs9-radio col-md-4 form-group" style="padding:0;">
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs9" value="yes"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs9']=='yes')echo "checked";?>> Yes</LABEL></div>
 								<div class="col-md-6"><LABEL><input type="radio" name="ohs9" value="no"  <?php if(isset($form) && isset($_GET['form_id']) && $form['ohs9']=='no')echo "checked";?>> No</LABEL></div>
 							</div>
 						</div>

 						<div class="ohs10">
 							<label for="ohs10" class="control-label col-md-8 form-group" style="padding:0;">Comments</label>
 							<div class="col-md-12 form-group" style="padding:0;">
 								<textarea name="ohs10" class="form-control"><?php if(isset($form) && isset($_GET['form_id']))echo $form['ohs10'];?></textarea>
 							</div>
 						</div>

 					</div>

 					<div class="coc col-md-12" style="padding:0;">
 						<div class="title form-group" style="color:#ff0000;text-decoration:underline;font-weight:bold;text-align:center;">
 							Confirmation of Completion
 						</div>
 						<div class="">
 							<div class="col-md-2"></div>
 							<div class="col-md-3 form-group">
                             <div class="input-icon"><input type="text" class="form-control" name="employee" placeholder="Employee" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['employee'];?>"></div>
 							</div>
                             <div class="col-md-2"></div>
 							<div class="col-md-3 form-group"> <div class="input-icon"><input type="text" class="form-control datepicker" name="emp_date" placeholder="Date" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['emp_date'];?>"></div>
 							</div>
                             <div class="col-md-2"></div>
 						</div>
 						<div class="clearfix"></div>
 						<div class="">
 							<div class="col-md-2"></div>
 							<div class="col-md-3 form-group"> <div class="input-icon"><input type="text" class="form-control" name="hr_representative" placeholder="H.R. Representative" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['hr_representative'];?>"></div>
 							</div>
                             <div class="col-md-2"></div>
 							<div class="col-md-3 form-group"> <div class="input-icon"><input type="text" class="form-control datepicker" name="hr_date" placeholder="Date" value="<?php if(isset($form) && isset($_GET['form_id']))echo $form['hr_date'];?>"></div>
 							</div>
                             <div class="col-md-2"></div>
 						</div>
 					</div>
                <div class="clearfix"></div>
        
            <div class="form-actions">
                <button  type="submit" class="btn btn-primary subz" >
                    Submit <i class="m-icon-swapright m-icon-white"></i>
                </button>
            </div>
 			</form>
            
    <?php }
    elseif(!isset($row)&&!isset($_GET['msg']) )
    {
         echo '<div class="clearfix"></div><div class="alert alert-danger display-hide" style="display: block;">
                        <button class="close" data-close="alert"></button>
                        Sorry, the profile does not exist.
                        </div>'; 
    }
    elseif(isset($row)&&($row['profile_type']!='5' && $row['profile_type']!='7' && $row['profile_type']!='8')&&!isset($_GET['msg']))
    {
         echo '<div class="clearfix"></div><div class="alert alert-danger display-hide" style="display: block;">
                        <button class="close" data-close="alert"></button>
                    User can not submit this form.
                </div>';
    }
    else
    {
         echo '<div class="clearfix"></div><div class="alert alert-info display-hide" style="display: block;">
                        <button class="close" data-close="alert"></button>
                            Survey submitted successfully.
                </div>';
    }
        backbutton();
            ?>
</div>
 	</div>	
 	</body>
    <script>
    $(function(){
        <?php if(isset($_GET['form_id'])){?>
            $('.formz input').attr('disabled','disabled');
            $('.formz textarea').attr('readonly','readonly');
            $('.subz').hide();
            
        <?php }?>
    })
    
    </script>
    <?php initdatepicker("yy-mm-dd"); ?>
 	</html>