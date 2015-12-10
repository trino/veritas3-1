<!DOCTYPE html><TITLE>Register with MEE</TITLE>
<STYLE>
    .required:after {
        content: " *";
        color: #e32;
    }
</STYLE>

<?php
    include("api.php");
    $today = date("Y-m-d");
    $webroot2 = $_SERVER["REQUEST_URI"];
    $start = strpos($webroot2, "/", 1) + 1;
    $webroot2 = substr($webroot2, 0, $start);
    $webroot2 = str_replace("/application/", "/", $webroot2);

    if (!isset($ClientID)) {$ClientID = 26;}
    if(!isset($NoClass)){$NoClass = "notrequired";}

    $con = "";
    $logo = 'img/logos/';
    $company_name = "";

    function second($query) {//this won't work, it'll return the first result.
        global $con;
        $result = $con->query($query);
        while ($row = mysqli_fetch_object($result)) {
            return $row;
        }
    }

    $newsigmethod = true;
    if($newsigmethod){include("signature.php");}

    $con = connectdb();

    if (isset($ClientID)) {
        $row = first("SELECT * FROM clients where id = " . $ClientID);
        if ($row) {
            if($ClientID == 26){
                $logo = "img/logo.png";
            } else {
                $logo = "img/jobs/" . $row["image"];
            }
            $company_name = $row["company_name"];
        }

    }

    if(isset($_GET['form_id'])) {
        $application_for_employment_gfs = second("SELECT * FROM application_for_employment_gfs where id = ".$_GET['form_id']);
        $profile = second("SELECT * FROM profiles where id = ".$application_for_employment_gfs->profile_id);
    }
    if (!$logo) {
        $logo = "";//default logo here
    }



    function printoption2($value, $selected = "", $option) {
        $tempstr = "";
        if ($option == $selected or $value == $selected) {
            $tempstr = " selected";
        }
        echo '<OPTION VALUE="' . $value . '"' . $tempstr . ">" . $option . "</OPTION>";
    }



    function printprovinces($name, $selected = "", $isdisabled = "", $isrequired = false, $Title = "Province") {
        printoptions($name, array("", "AB", "BC", "MB", "NB", "NL", "NT", "NS", "NU", "ON", "PE", "QC", "SK", "YT"), $selected, array($Title, "Alberta", "British Columbia", "Manitoba", "New Brunswick", "Newfoundland and Labrador", "Northwest Territories", "Nova Scotia", "Nunavut", "Ontario", "Prince Edward Island", "Quebec", "Saskatchewan", "Yukon Territories"), $isdisabled, $isrequired);
    }

?>

<!--[if (gt IE 9)|!(IE)]><!--> <html class=""> <!--<![endif]-->
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css"/>
    <link href="<?= $webroot; ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?= $webroot; ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?= $webroot; ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?= $webroot; ?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet"
          type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="<?= $webroot; ?>assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $webroot; ?>assets/admin/pages/css/login3.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME STYLES -->
    <link href="<?= $webroot; ?>assets/global/css/components.css" id="style_components" rel="stylesheet"
          type="text/css"/>
    <link href="<?= $webroot; ?>assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $webroot; ?>assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="<?= $webroot; ?>assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css"
          id="style_color"/>
    <link href="<?= $webroot; ?>assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>
    <!-- END COPYRIGHT -->
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
    <script src="<?= $webroot; ?>assets/global/plugins/respond.min.js"></script>
    <script src="<?= $webroot; ?>assets/global/plugins/excanvas.min.js"></script>
    <![endif]-->
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet"
          type="text/css"/>


    <script src="<?= $webroot; ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?= $webroot; ?>assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
    <script src="<?= $webroot; ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?= $webroot; ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="<?= $webroot; ?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <script src="<?= $webroot; ?>assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="<?= $webroot; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
            type="text/javascript"></script>
    <script type="text/javascript" src="<?= $webroot; ?>assets/global/plugins/select2/select2.min.js"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?= $webroot; ?>assets/global/scripts/metronic.js" type="text/javascript"></script>
    <script src="<?= $webroot; ?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
    <script src="<?= $webroot; ?>assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
    <!--script src="<?= $webroot; ?>assets/admin/pages/scripts/login.js" type="text/javascript"></script-->
    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
        $(document).ready(function () {
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
            // Login.init();
            Demo.init();
        });

        function save_signature(uselessnumber){
            document.getElementById("myForm").submit();
        }
    </script>
    <style>
    p{text-align:justify!important;}
    </style>
    <!-- END JAVASCRIPTS -->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<!-- BEGIN LOGO -->

<!-- END LOGO -->
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->


<div class="logo"></div>

<div class="content" style="width:70%;">
<?php if(isset($_GET['msg'])&& $_GET['msg']=='error'){?>
    <div class="alert alert-info " >
        <button class="close" data-close="alert"></button>
        Could not submit the form. Please try again.
    </div>
<?php }elseif(isset($_GET['msg'])&& $_GET['msg']=='success'){?>
     <div class="alert alert-info " >
        <button class="close" data-close="alert"></button>
        The form has been submitted. We will get in touch shortly.
    </div>
<?php } else{  ?>

<div class="clearfix"></div>
    <form  action="<?= $webroot2;?>rapid/application_employment/<?= $ClientID; ?>" method="post" class="login-form" id="myForm">
        <div class="clearfix"></div>

        <div class="col-md-12" align="center">
            <img style="max-height: 100px;" src="<?= $webroot . $logo ;?>" />
            <h2><?= $company_name; ?> Application for Employment</h2>

            <div class="clearfix"></div>
        </div>
        <div class="col-md-12 oldie" style="color: red; font-weight: bold; display:none;">
          <center>Your version of Internet Explorer is not supported. Please update your version or download <a href="https://www.google.com/chrome/browser/desktop/" target="_blank">Chrome here</a>.</center>
        </div>
        <div class="clearfix"></div>

        <div class="hideoldie">

                    <div class="col-md-6">
                    <label class="control-label col-md-4 required" required>Title:</label>
                    <div class="col-md-8">
                       <select class="form-control required" name="title" required>
                       <option value="Mr." <?php if(isset($profile) && $profile->title =='Mr.')echo "selected='selected'";?>>Mr.</option>
                       <option value="Mrs." <?php if(isset($profile) && $profile->title =='Mrs.')echo "selected='selected'";?>>Mrs.</option>
                       <option value="Ms." <?php if(isset($profile) && $profile->title =='Ms.')echo "selected='selected'";?>>Ms.</option>
                       </select>
                    </div>
            </div>

            <div class="col-md-6">
                    <label class="control-label col-md-3 required">Name:</label>

                    <div class="col-md-3">
                        <input class="form-control required" name="fname" placeholder="First" required value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->fname;?>" />
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" name="mname" placeholder="Middle" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->mname;?>" />
                    </div>
                    <div class="col-md-3">
                        <input class="form-control required" name="lname" placeholder="Last" required value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->lname;?>" />
                    </div>
            </div>
        </div>

        <br>&nbsp;</br>

        <div class="col-md-6">
                <label class="control-label col-md-4 required">Telephone:</label>
                <div class="col-md-3">
                    <input class="form-control required" name="code" required placeholder="Area Code" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->code;?>" />
                </div>
                <div class="col-md-5">
                    <input class="form-control required" name="phone" required value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->phone;?>" />
                </div>
        </div>

        <div class="col-md-6">
                <label class="control-label col-md-3 required">Email:</label>
                <div class="col-md-9">
                    <input class="form-control email required" type="email"  name="email" required value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->email;?>" />
                </div>
        </div>

        <br>&nbsp;</br>

        <div class="col-md-6">
                <label class="control-label col-md-4 required">Gender:</label>
                <div class="col-md-8">
                <select class="form-control req_driver required" required name="gender">
                    <option>Select Gender</option>
                    <option value="Male" <?php if(isset($profile)&& $profile->gender=='Male')echo 'selected';?>>Male</option>
                    <option value="Female" <?php if(isset($profile)&& $profile->gender=='Female')echo 'selected';?>>Female</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <label class="control-label col-md-3 required">Country of Birth:</label>
            <div class="col-md-9">
                <input class="form-control birth required" type="text" name="placeofbirth" required value="<?php if(isset($profile))echo $profile->placeofbirth;?>" />
            </div>
        </div>

        <br />

        <?php
            if (isset($profile->dob)) {
                $currentyear = substr($profile->dob, 0, 4);
                $currentmonth = substr($profile->dob, 5, 2);
                $currentday = substr($profile->dob, -2);
            } else {
                $currentyear = date('Y');
                $currentmonth = date('m');
                $currentday = date('d');
            }
        ?>

        <div class="col-md-12">
            <label class="control-label col-md-3 required">Date of Birth (YYYY MM DD):</label>
            <div class="col-md-3 no-margin">
            <select name="doby" class="form-control req_driver required" required>
            <?php
                for($i=date('Y');$i>1950; --$i){
                      echo '<option value="' . $i . '" ';
                      if(isset($profile)&& $i==$currentyear) {echo 'selected'; }
                      echo '>' . $i . '</option>';
                }
            ?>
            </select>
            </div>
            <div class="col-md-3">
            <select name="dobm" class="form-control req_driver required" required>
            <option value="01" <?php if(isset($profile)&& 01==$currentmonth)echo 'selected';?>>01</option>
            <option value="02" <?php if(isset($profile)&& 02==$currentmonth)echo 'selected';?>>02</option>
            <option value="03" <?php if(isset($profile)&& 03==$currentmonth)echo 'selected';?>>03</option>
            <option value="04" <?php if(isset($profile)&& 04==$currentmonth)echo 'selected';?>>04</option>
            <option value="05" <?php if(isset($profile)&& 05==$currentmonth)echo 'selected';?>>05</option>
            <option value="06" <?php if(isset($profile)&& 06==$currentmonth)echo 'selected';?>>06</option>
            <option value="07" <?php if(isset($profile)&& 07==$currentmonth)echo 'selected';?>>07</option>
            <option value="08" <?php if(isset($profile)&& 08==$currentmonth)echo 'selected';?>>08</option>
            <option value="09" <?php if(isset($profile)&& 09==$currentmonth)echo 'selected';?>>09</option>
            <option value="10" <?php if(isset($profile)&& 10==$currentmonth)echo 'selected';?>>10</option>
            <option value="11" <?php if(isset($profile)&& 11==$currentmonth)echo 'selected';?>>11</option>
            <option value="12" <?php if(isset($profile)&& 12==$currentmonth)echo 'selected';?>>12</option>
            </select>
            </div>
            <div class="col-md-3">
            <select name="dobd" class="form-control req_driver ">
            <option value="01" <?php if(isset($profile)&& 01==$currentday)echo 'selected';?>>01</option>
            <option value="02" <?php if(isset($profile)&& 02==$currentday)echo 'selected';?>>02</option>
            <option value="03" <?php if(isset($profile)&& 03==$currentday)echo 'selected';?>>03</option>
            <option value="04" <?php if(isset($profile)&& 04==$currentday)echo 'selected';?>>04</option>
            <option value="05" <?php if(isset($profile)&& 05==$currentday)echo 'selected';?>>05</option>
            <option value="06" <?php if(isset($profile)&& 06==$currentday)echo 'selected';?>>06</option>
            <option value="07" <?php if(isset($profile)&& 07==$currentday)echo 'selected';?>>07</option>
            <option value="08" <?php if(isset($profile)&& 08==$currentday)echo 'selected';?>>08</option>
            <option value="09" <?php if(isset($profile)&& 09==$currentday)echo 'selected';?>>09</option>
            <option value="10" <?php if(isset($profile)&& 10==$currentday)echo 'selected';?>>10</option>
            <option value="11" <?php if(isset($profile)&& 11==$currentday)echo 'selected';?>>11</option>
            <option value="12" <?php if(isset($profile)&& 12==$currentday)echo 'selected';?>>12</option>
            <option value="13" <?php if(isset($profile)&& 13==$currentday)echo 'selected';?>>13</option>
            <option value="14" <?php if(isset($profile)&& 14==$currentday)echo 'selected';?>>14</option>
            <option value="15" <?php if(isset($profile)&& 15==$currentday)echo 'selected';?>>15</option>
            <option value="16" <?php if(isset($profile)&& 16==$currentday)echo 'selected';?>>16</option>
            <option value="17" <?php if(isset($profile)&& 17==$currentday)echo 'selected';?>>17</option>
            <option value="18" <?php if(isset($profile)&& 18==$currentday)echo 'selected';?>>18</option>
            <option value="19" <?php if(isset($profile)&& 19==$currentday)echo 'selected';?>>19</option>
            <option value="20" <?php if(isset($profile)&& 20==$currentday)echo 'selected';?>>20</option>
            <option value="21" <?php if(isset($profile)&& 21==$currentday)echo 'selected';?>>21</option>
            <option value="22" <?php if(isset($profile)&& 22==$currentday)echo 'selected';?>>22</option>
            <option value="23" <?php if(isset($profile)&& 23==$currentday)echo 'selected';?>>23</option>
            <option value="24" <?php if(isset($profile)&& 24==$currentday)echo 'selected';?>>24</option>
            <option value="25" <?php if(isset($profile)&& 25==$currentday)echo 'selected';?>>25</option>
            <option value="26" <?php if(isset($profile)&& 26==$currentday)echo 'selected';?>>26</option>
            <option value="27" <?php if(isset($profile)&& 27==$currentday)echo 'selected';?>>27</option>
            <option value="28" <?php if(isset($profile)&& 28==$currentday)echo 'selected';?>>28</option>
            <option value="29" <?php if(isset($profile)&& 29==$currentday)echo 'selected';?>>29</option>
            <option value="30" <?php if(isset($profile)&& 30==$currentday)echo 'selected';?>>30</option>
            <option value="31" <?php if(isset($profile)&& 31==$currentday)echo 'selected';?>>31</option>
            </select>
             </div>
        </div>

        <br>&nbsp;</br>

        <div class="col-md-12">
            <label class="control-label col-md-4 required">Address:</label>
            <div class="col-md-4">
                <input type="text" class="form-control req_driver required" placeholder="Address" required name="street" value="<?php if(isset($profile))echo $profile->street;?>">
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control req_driver required" placeholder="City" required name="city" value="<?php if(isset($profile))echo $profile->city;?>">
            </div>
        </div>

        <br>&nbsp;</br>

         <div class="col-md-12">
          <div class="col-md-4">
            <select class="form-control req_driver required" name="province" required>
            <option selected="" value="">Select Province</option>
            <option value="AB" <?php if(isset($profile)&& $profile->province=='AB')echo 'selected';?>>Alberta</option>
            <option value="BC" <?php if(isset($profile)&& $profile->province=='BC')echo 'selected';?>>British Columbia</option>
            <option value="MB" <?php if(isset($profile)&& $profile->province=='MB')echo 'selected';?>>Manitoba</option>
            <option value="NB" <?php if(isset($profile)&& $profile->province=='NB')echo 'selected';?>>New Brunswick</option>
            <option value="NL" <?php if(isset($profile)&& $profile->province=='NL')echo 'selected';?>>Newfoundland and Labrador</option>
            <option value="NT" <?php if(isset($profile)&& $profile->province=='NT')echo 'selected';?>>Northwest Territories</option>
            <option value="NS" <?php if(isset($profile)&& $profile->province=='NS')echo 'selected';?>>Nova Scotia</option>
            <option value="NU" <?php if(isset($profile)&& $profile->province=='NU')echo 'selected';?>>Nunavut</option>
            <option value="ON" <?php if(isset($profile)&& $profile->province=='ON')echo 'selected';?>>Ontario</option>
            <option value="PE" <?php if(isset($profile)&& $profile->province=='PE')echo 'selected';?>>Prince Edward Island</option>
            <option value="QC" <?php if(isset($profile)&& $profile->province=='QC')echo 'selected';?>>Quebec</option>
            <option value="SK" <?php if(isset($profile)&& $profile->province=='SK')echo 'selected';?>>Saskatchewan</option>
            <option value="YT" <?php if(isset($profile)&& $profile->province=='YT')echo 'selected';?>>Yukon Territories</option>
            </select>
            </div>
            <div class="col-md-4"><input type="text" name="postal" class="form-control req_driver required" required placeholder="Postal code" value="<?php if(isset($profile))echo $profile->postal;?>"></div>
            <div class="col-md-4"><input type="text" name="country" class="form-control req_driver required" required value="Canada" placeholder="Country" value="<?php if(isset($profile))echo $profile->country;?>"></div>
            </div>

            <br>&nbsp;</br>

            <div class="col-md-12">
                <label class="control-label col-md-6 required"> Where did you hear about us?</label>
                <div class="col-md-6">
                    <select name="hear" class="form-control required" required>
                        <option value="Referral" <?php if(isset($profile)&& $profile->hear=='Referral')echo 'selected';?>>Referral</option>
                        <option value="Company Website"  <?php if(isset($profile)&& $profile->hear=='Company Website')echo 'selected';?>>Company Website</option>
                        <option value="Workopolis"  <?php if(isset($profile)&& $profile->hear=='Workopolis')echo 'selected';?>>Workopolis</option>
                        <option value="Monster"  <?php if(isset($profile)&& $profile->hear=='Monster')echo 'selected';?>>Monster</option>
                        <option value="Nethire"  <?php if(isset($profile)&& $profile->hear=='Nethire')echo 'selected';?>>Nethire</option>
                        <option value="Indeed"  <?php if(isset($profile)&& $profile->hear=='Indeed')echo 'selected';?>>Indeed</option>
                        <option value="Newspaper"  <?php if(isset($profile)&& $profile->hear=='Newspaper')echo 'selected';?>>Newspaper</option>
                        <option value="Others"  <?php if(isset($profile)&& $profile->hear=='Others')echo 'selected';?>> Others</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                    <label class="control-label col-md-4 required">Have you ever applied for work with us before?</label>
                    <div class="col-md-3 radio-list yesNoCheck">
                        <label class="radio-inline">
                        <?php
                            if(isset($_GET['form_id'])) {
                                if(isset($application_for_employment_gfs) && $application_for_employment_gfs->workedbefore=='1') {
                                    echo '&#10004;';
                                } else {
                                    echo '&#10006;';
                                }
                            } else {
                                echo '<input type="radio" class="form-control" name="workedbefore" id="yesCheck" value="1"';
                                if(isset($application_for_employment_gfs) && $application_for_employment_gfs->workedbefore=='1') {echo "checked='checked'";}
                                echo '/>';
                            }
                        ?> <span>Yes</span>
                        </label>
                        <label class="radio-inline">
                        <?php
                            if(isset($_GET['form_id'])) {
                                if(isset($application_for_employment_gfs) && $application_for_employment_gfs->workedbefore=='0') {
                                    echo '&#10004;';
                                } else {
                                    echo '&#10006;';
                                }
                            } else {
                                echo '<input type="radio" class="form-control" name="workedbefore" id="noCheck" value="0" checked';
                                if(isset($application_for_employment_gfs) && $application_for_employment_gfs->workedbefore=='0') {echo "checked='checked'";}
                                echo '/>';
                            }
                        ?>
                        <span>No</span>
                        </label>
                    </div>
                    <div class="clearfix"></div>
                     <div class="col-md-4"></div>
                    <div id="yesDiv" style="display: none;" class="col-md-8">
                    <div class="row">
                        <label class="control-label col-md-3">If yes, when?</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="worked"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->worked;?></textarea>
                        </div>
                        </div>
                    </div>
            </div>

            <div class="col-md-12 nothuron">
                    <label class="control-label col-md-4">List anyone you know who woks for us:</label>
                    <div class="col-md-8">
                        <input class="form-control" name="for_us" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->for_us;?>" />
                    </div>
            </div>

            <div class="col-md-12 nothuron">
                    <label class="control-label col-md-4">Did anyone refer you?</label>
                    <div class="col-md-8">
                        <input class="form-control" name="refer"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->refer;?>" />
                    </div>
            </div>

            <div class="col-md-6" style="padding-right:0px ;">
                    <label class="control-label col-md-8 required">Are you 18 years of age or older?</label>
                    <div class="col-md-3 radio-list" style="padding-right:0px ;">
                        <label class="radio-inline">
                        <?php
                            if(isset($_GET['form_id'])) {
                                if(isset($application_for_employment_gfs) && $application_for_employment_gfs->age=='1') {
                                    echo '&#10004;';
                                } else {
                                    echo '&#10006;';
                                }
                            } else {
                                echo '<input type="radio" class="form-control" name="age" value="1"/>';
                            }
                        ?>
                        Yes
                        </label>
                        <label class="radio-inline">
                        <?php
                            if(isset($_GET['form_id'])) {
                                if(isset($application_for_employment_gfs) && $application_for_employment_gfs->age=='0') {
                                    echo '&#10004;';
                                } else {
                                    echo '&#10006;';
                                }
                            } else {
                                echo '<input type="radio" class="form-control" name="age" value="0" checked/>';
                            }
                        ?>
                         No
                        </label>
                    </div>
            </div>

            <div class="col-md-6" style="padding-right:0px ;">
                    <label class="control-label col-md-8 required">Are you legally eligible to work in Canada?</label>
                    <div class="col-md-4 radio-list" style="padding-right:0px ;">
                        <label class="radio-inline">
                        <?php
                        if(isset($_GET['form_id'])) {
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->legal=='1') {
                                echo '&#10004;';
                            } else {
                                echo '&#10006;';
                            }
                        } else {
                            echo '<input type="radio" class="form-control" name="legal" value="1" ';
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->legal=='1')echo "checked='checked'";
                            echo '/>';
                        }
                         ?>
                        Yes
                        </label>
                        <label class="radio-inline">
                        <?php
                        if(isset($_GET['form_id'])) {
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->legal=='0') {
                                echo '&#10004;';
                            } else {
                                echo '&#10006;';
                            }
                        } else {
                            echo '<input type="radio" checked class="form-control" name="legal" value="0" ';
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->legal=='0')echo "checked='checked'";
                            echo '/>';
                        }
                         ?>
                         No
                        </label>
                    </div>
            </div>

        <br>&nbsp;</br>

        <div class="col-md-12">
            <h3 class="col-md-12">Driver's License</h3>
        </div>

        <div class="col-md-12">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label required">Driver's License #:</label>
                    <input name="driver_license_no" type="text" required class="form-control required req_driver">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label required">Province issued:</label>
                    <select name="driver_province" required class="form-control req_driver required"><option value="">Select Province</option><option value="AB">Alberta</option><option value="BC">British Columbia</option><option value="MB">Manitoba</option><option value="NB">New Brunswick</option><option value="NL">Newfoundland and Labrador</option><option value="NT">Northwest Territories</option><option value="NS">Nova Scotia</option><option value="NU">Nunavut</option><option value="ON">Ontario</option><option value="PE" selected="">Prince Edward Island</option><option value="QC">Quebec</option><option value="SK">Saskatchewan</option><option value="YT">Yukon Territories</option></select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label required">Expiry Date:</label>
                    <input name="expiry_date" required type="text" class="form-control req_driver required datepicker">
                </div>
            </div>
        </div>

        <div class="col-md-12 nothuron">
            <h3 class="col-md-12">SIN Card</h3>
        </div>

        <div class="col-md-12 nothuron">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label required">SIN:</label>
                    <input name="sin" type="text" <?php if($ClientID==26) { echo "required"; } ?> class="form-control <?php if($ClientID==26) { echo "required"; } ?> req_driver">
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <h3 class="col-md-12">Education</h3>
        </div>
        <!--div class="col-md-12">
        <div class="table-scrollable">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>School</th>
                        <th>No. of Years Attended</th>
                        <th>City, State</th>
                        <th>Course</th>
                        <th>Did you Graduate?</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <th>Grammar</th>
                        <td><input class="form-control" name="g_years"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->g_years;?>" /></td>
                        <td><input class="form-control" name="g_city"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->g_city;?>" /></td>
                        <td><input class="form-control" name="g_course"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->g_course;?>" /></td>
                        <td><input class="form-control" name="g_grad"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->g_grad;?>" /></td>
                    </tr>
                    <tr>
                        <th>High</th>
                        <td><input class="form-control" name="h_years"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->h_years;?>" /></td>
                        <td><input class="form-control" name="h_city"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->h_city;?>" /></td>
                        <td><input class="form-control" name="h_course"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->h_course;?>" /></td>
                        <td><input class="form-control" name="h_grad"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->h_grad;?>" /></td>
                    </tr>
                    <tr>
                        <th>College</th>
                        <td><input class="form-control" name="c_years"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->c_years;?>" /></td>
                        <td><input class="form-control" name="c_city"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->c_city;?>" /></td>
                        <td><input class="form-control" name="c_course"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->c_course;?>" /></td>
                        <td><input class="form-control" name="c_grad"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->c_grad;?>" /></td>
                    </tr>
                    <tr>
                        <th>Other</th>
                        <td><input class="form-control" name="o_years"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->o_years;?>" /></td>
                        <td><input class="form-control" name="o_city"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->o_city;?>" /></td>
                        <td><input class="form-control" name="o_course"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->o_course;?>" /></td>
                        <td><input class="form-control" name="o_grad"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->o_grad;?>" /></td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>

            <p>&nbsp;</p-->
            <div class="col-md-12">
                    <label class="control-label col-md-6">Do you have any skills, qualifications or experiences which you feel would specially fit you for working with us? </label>
                    <div class="col-md-6">
                        <textarea class="form-control" name="skills"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->skills;?></textarea>
                    </div>
            </div>

            <div class="nothuron">
                    <div class="col-md-12">
                        <label class="control-label col-md-2">Job(s) Applied for: </label>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label col-md-1">1. </label>
                        <div class="col-md-3">
                            <input class="form-control" name="applied" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->applied;?>" />
                        </div>
                        <label class="control-label col-md-3">Rate of pay expected $ </label>
                        <div class="col-md-2">
                            <input class="form-control" name="rate" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->rate;?>" />
                        </div>
                        <label class="control-label col-md-1">per </label>
                        <div class="col-md-2">
                            <input class="form-control" name="per" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->per;?>" />
                        </div>
                    </div>
                    <p>&nbsp;</p>
                    <div class="col-md-12">
                        <label class="control-label col-md-1">2.</label>
                        <div class="col-md-3">
                            <input class="form-control" name="applied1" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->applied1;?>" />
                        </div>
                        <label class="control-label col-md-3">Rate of pay expected $</label>
                        <div class="col-md-2">
                            <input class="form-control" name="rate1" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->rate1;?>" />
                        </div>
                        <label class="control-label col-md-1">per</label>
                        <div class="col-md-2">
                            <input class="form-control" name="per1" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->per1;?>" />
                        </div>
                    </div>
            </div>
            <BR></BR>
            <div class="col-md-12">
                    <label class="control-label col-md-6">Do you want to work: </label>
                    <div class="col-md-6 radio-list">
                        <label class="radio-inline">
                        <?php
                        if(isset($_GET['form_id'])) {
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->legal1=='1') {
                                echo '&#10004;';
                            } else {
                                echo '&#10006;';
                            }
                        } else {
                            echo '<input type="radio" class="form-control" name="legal1" id="partTime" value="1" ';
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->legal1=='1') {echo "checked='checked'";}
                            echo '/>';
                        }
                         ?>
                         Part Time
                        </label>
                        <label class="radio-inline">
                        <?php
                        if(isset($_GET['form_id'])) {
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->legal1=='0') {
                                echo '&#10004;';
                            } else {
                                echo '&#10006;';
                            }
                        } else {
                            echo '<input type="radio" class="form-control" name="legal1" id="fullTime" value="0" ';
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->legal1=='0')echo "checked='checked'";
                            echo '/>';
                        }
                         ?>
                         Full Time ?
                        </label>
                    </div>
            </div>
            <div id="partTimeDiv" style="display: none;">
            <p>&nbsp;</p>
            <div class="col-md-12">
                <label class="control-label col-md-6">If applying only for part-time, which days and hours?</label>
                <div class="col-md-6">
                    <textarea class="form-control" name="part"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->part;?></textarea>
                </div>
            </div>
            </div>
            <p>&nbsp;</p>
            <div class="col-md-12">
                    <label class="control-label col-md-6">Are you able to do the job(s) for which you are applying?</label>
                    <div class="col-md-6 radio-list">
                        <label class="radio-inline">
                        <?php
                        if(isset($_GET['form_id'])) {
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->legal2=='1') {
                                echo '&#10004;';
                            } else {
                                echo '&#10006;';
                            }
                        } else {
                             echo '<input type="radio" class="form-control" name="legal2" id="ableToWork" value="1" ';
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->legal2=='1')echo "checked='checked'";
                            echo '/>';
                        }
                         ?>
                            Yes
                        </label>
                        <label class="radio-inline">
                        <?php
                        if(isset($_GET['form_id'])) {
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->legal2=='2') {
                                echo '&#10004;';
                            } else {
                                echo '&#10006;';
                            }
                        } else {
                            echo '<input type="radio" class="form-control" name="legal2" id="notAbleToWork" value="2" ';
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->legal2=='0')echo "checked='checked'";
                            echo '/>';
                        }
                        ?>
                        No
                        </label>
                    </div>
            </div>
            <div id="notAbleDiv" style="display: none;">
            <p>&nbsp;</p>
            <div class="col-md-12">
                <label class="control-label col-md-5">If no, please explain:</label>
                <div class="col-md-7">
                    <textarea class="form-control" name="no_explain"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->no_explain;?></textarea>
                </div>
            </div>
             </div>

             <p>&nbsp;</p>
            <!--div class="col-md-12">
                <label class="control-label col-md-5">If hired, when can you start?</label>
                <div class="col-md-7">
                    <input class="form-control" name="start"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->start;?>" />
                </div>
            </div>
            <div class="clearfix"></div>
            <hr />
            <div class="col-md-12">
              <h3>Driving Record</h3>
              </div>
              <div class="col-md-12">
              <p>Collision record for the past three (3) years (attach sheet if more space is needed).</p>
              </div>
              <div class="col-md-12">
              <div class="table-scrollable">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Dates</th>
                        <th>Nature of Collision<br />(Head-On, Rear-End, Backing, etc.)</th>
                        <th>Injuries / Fatalities</th>
                        <th>Vehicle Type <br />(Commercial or Personal)</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <th>Last Collision</th>
                        <td><input class="form-control" name="l_date"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->l_date;?>" /></td>
                        <td><input class="form-control" name="l_nature"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->l_nature;?>" /></td>
                        <td><input class="form-control" name="l_type"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->l_type;?>" /></td>
                    </tr>
                    <tr>
                        <th>Next Previous</th>
                         <td><input class="form-control" name="p_date"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->p_date;?>" /></td>
                        <td><input class="form-control" name="p_nature"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->p_nature;?>" /></td>
                        <td><input class="form-control" name="p_type"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->p_type;?>" /></td>
                    </tr>
                    <tr>
                        <th>Next Previous</th>
                         <td><input class="form-control" name="n_date"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->n_date;?>" /></td>
                        <td><input class="form-control" name="n_nature"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->n_nature;?>" /></td>
                        <td><input class="form-control" name="n_type"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->n_type;?>" /></td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div-->

        <div class="clearfix"></div>

    <!--div class="col-md-12">
        <h3 class="col-md-12">Driving Experience and Qualifications</h3>
    </div-->
              <!--div class="col-md-6">
              <div class="col-md-12">
                <h3>Driver Licenses</h3>
              </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Expires</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><input class="form-control" name="class1"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->class1;?>" /></td>
                        <td><input class="form-control" name="expires1"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->expires1;?>" /></td>
                    </tr>
                    <tr>
                        <td><input class="form-control" name="class2"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->class2;?>" /></td>
                        <td><input class="form-control" name="expires2"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->expires2;?>" /></td>
                    </tr>
                    <tr>
                        <td><input class="form-control" name="class3"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->class3;?>" /></td>
                        <td><input class="form-control" name="expires3"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->expires3;?>" /></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Class of Equipment</th>
                        <th>Approx. No. of Miles (Total)</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <th>Straight Truck</th>
                        <td><input class="form-control" name="starigt_miles"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->starigt_miles;?>" /></td>
                    </tr>
                    <tr>
                        <th>Tractor and Semi-Trailer</th>
                        <td><input class="form-control" name="semi_miles"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->semi_miles;?>" /></td>
                    </tr>
                    <tr>
                        <th>Tractor and Two-Trailer</th>
                        <td><input class="form-control" name="two_miles"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->two_miles;?>" /></td>
                    </tr>
                    <tr>
                        <th>Other</th>
                        <td><input class="form-control" name="other_miles"value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->other_miles;?>" /></td>
                    </tr>
                </tbody>
            </table>
        </div-->


        <!--div class="col-md-12">
            <label class="col-md-6">Show special courses or training that will help you as as driver</label>
            <div class="col-md-6"><textarea class="form-control" name="special_course"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->special_course;?></textarea></div>
            <p>&nbsp;</p>
        </div>
        <div class="col-md-12">
            <label class="col-md-6">Which safe driving awaards do you hold and from whom?</label>
            <div class="col-md-6"><textarea class="form-control" name="which_safe_driving"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->which_safe_driving;?></textarea></div>
            <p>&nbsp;</p>
        </div>
        <div class="col-md-12">
            <label class="col-md-6">Show any trucking, transportation or other experiences that may help in your work for this company:</label>
            <div class="col-md-6"><textarea class="form-control" name="show_any_trucking"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->show_any_trucking;?></textarea></div>
            <p>&nbsp;</p>
        </div>
        <div class="col-md-12">
            <label class="col-md-6">List courses and training other than shown elsewhere in this application</label>
            <div class="col-md-6"><textarea class="form-control" name="list_courses_training"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->list_courses_training;?></textarea></div>
            <p>&nbsp;</p>
        </div>
        <div class="col-md-12">
            <label class="col-md-6">List special equipment or technical materials you can work with (other than those already shown)</label>
            <div class="col-md-6"><textarea class="form-control" name="list_special_equipment"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->list_special_equipment;?></textarea></div>
            <p>&nbsp;</p>
        </div>

        <hr>
        <div class="col-md-12">
             <h3 class="col-md-12">EMPLOYMENT HISTORY</h3-->
             <!--p>Please list your most recent employment first. Add another sheet if necessary. History must be the last three year’s. Commercial drivers shall provide

                an additional seven year’s information on employers for whom the applicant operated a commercial vehicle.
            </p>
            <p>&nbsp;</p>
            <table class="table table-bordered">
                <tr>
                    <td colspan="3">
                        <label class="col-md-12">Name & Address Of Employer:</label>
                        <div class="col-md-12"><textarea name="name_and_address_employer1" class="form-control form-control"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->name_and_address_employer1;?></textarea></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="col-md-12">Dates of Employment</label>
                        <div class="col-md-6"><input type="text" class="date-picker form-control" name="date_of_employment_from1" placeholder="From" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->date_of_employment_from1;?>" /></div>
                        <div class="col-md-6"><input type="text" class="date-picker form-control" name="date_of_employment_to1" placeholder="To" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->date_of_employment_to1;?>" /></div></div>
                    </td>
                    <td colspan="2">
                        <label class="col-md-12">Type of work done</label>
                        <div class="col-md-12"><textarea name="type_of_work_done1" class="form-control"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->type_of_work_done1;?></textarea></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label class="col-md-12">Supervisor's Name & Phone No.:</label>
                        <div class="col-md-12"><textarea name="supervisor_name_phone1" class="form-control form-control"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->supervisor_name_phone1;?></textarea></div>
                    </td>
                    <td>
                       <label class="col-md-12">Final Salary</label>
                       <div class="col-md-12"><input type="text" class="form-control" name="final_salary1" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->final_salary1;?>" /></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <label class="col-md-12">Reasons of leaving:</label>
                        <div class="col-md-12"><textarea name="reasons_of_leaving1" class="form-control form-control"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->reasons_of_leaving1;?></textarea></div>
                    </td>
                </tr>
            </table>

            <p>&nbsp;</p>
            <table class="table table-bordered">
                <tr>
                    <td colspan="3">
                        <label class="col-md-12">Name & Address Of Employer:</label>
                        <div class="col-md-12"><textarea name="name_and_address_employer2" class="form-control form-control"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->name_and_address_employer2;?></textarea></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="col-md-12">Dates of Employment</label>
                        <div class="col-md-6"><input type="text" class="date-picker form-control" name="date_of_employment_from2" placeholder="From" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->date_of_employment_from2;?>" /></div>
                        <div class="col-md-6"><input type="text" class="date-picker form-control" name="date_of_employment_to2" placeholder="To" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->date_of_employment_to2;?>" /></div></div>
                    </td>
                    <td colspan="2">
                        <label class="col-md-12">Type of work done</label>
                        <div class="col-md-12"><textarea name="type_of_work_done2" class="form-control"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->type_of_work_done2;?></textarea></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label class="col-md-12">Supervisor's Name & Phone No.:</label>
                        <div class="col-md-12"><textarea name="supervisor_name_phone2" class="form-control form-control"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->supervisor_name_phone2;?></textarea></div>
                    </td>
                    <td>
                       <label class="col-md-12">Final Salary</label>
                       <div class="col-md-12"><input type="text" class="form-control" name="final_salary2" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->final_salary2;?>" /></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <label class="col-md-12">Reasons of leaving:</label>
                        <div class="col-md-12"><textarea name="reasons_of_leaving2" class="form-control form-control"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->reasons_of_leaving2;?></textarea></div>
                    </td>
                </tr>
            </table>

            <p>&nbsp;</p>
            <table class="table table-bordered">
                <tr>
                    <td colspan="3">
                        <label class="col-md-12">Name & Address Of Employer:</label>
                        <div class="col-md-12"><textarea name="name_and_address_employer3" class="form-control form-control"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->name_and_address_employer3;?></textarea></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="col-md-12">Dates of Employment</label>
                        <div class="col-md-6"><input type="text" class="date-picker form-control" name="date_of_employment_from3" placeholder="From" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->date_of_employment_from3;?>" /></div>
                        <div class="col-md-6"><input type="text" class="date-picker form-control" name="date_of_employment_to3" placeholder="To" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->date_of_employment_to3;?>" /></div></div>
                    </td>
                    <td colspan="2">
                        <label class="col-md-12">Type of work done</label>
                        <div class="col-md-12"><textarea name="type_of_work_done3" class="form-control"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->type_of_work_done3;?></textarea></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label class="col-md-12">Supervisor's Name & Phone No.:</label>
                        <div class="col-md-12"><textarea name="supervisor_name_phone3" class="form-control form-control"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->supervisor_name_phone3;?></textarea></div>
                    </td>
                    <td>
                       <label class="col-md-12">Final Salary</label>
                       <div class="col-md-12"><input type="text" class="form-control" name="final_salary3" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->final_salary3;?>" /></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <label class="col-md-12">Reasons of leaving:</label>
                        <div class="col-md-12"><textarea name="reasons_of_leaving3" class="form-control form-control"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->reasons_of_leaving3;?></textarea></div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-12">
            <label class="col-md-6">Would you be willing to take a physical exam?</label>
            <div class="col-md-6 radio-list">
            <label class="radio-inline">
            <?php
                        if(isset($_GET['form_id'])){
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->physical_exam=='1'){
                                echo '&#10004;';
                            }else{
                                echo '&#10006;';
                            }
                        }else{
                            echo '<input type="radio" name="physical_exam" value="1" ';
                            if(isset($application_for_employment_gfs)&& $application_for_employment_gfs->physical_exam=='1')echo "checked='checked'";
                            echo '/>';
                        }
                         ?>
             Yes &nbsp; &nbsp;
             </label>
             <label class="radio-inline">
             <?php
                        if(isset($_GET['form_id'])){
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->physical_exam=='0'){
                                echo '&#10004;';
                            }else{
                                echo '&#10006;';
                            }
                        }else{
                            echo '<input type="radio" name="physical_exam" value="0" ';
                            if(isset($application_for_employment_gfs)&& $application_for_employment_gfs->physical_exam=='0')echo "checked='checked'";
                            echo '/>';
                        }
                         ?>
              No
              </label>
              </div>
        </div>

        <div class="col-md-12">
            <label class="col-md-6">What are your aspirations, now and in the future?</label>
            <div class="col-md-12"><textarea class="form-control" name="aspirations"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->aspirations;?></textarea></div>
        </div>
        <div class="col-md-12">
            <label class="col-md-6">Why do you think you are the best qualified candidate?</label>
            <div class="col-md-12"><textarea class="form-control" name="best_qualified"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->best_qualified;?></textarea></div>
        </div>
        <div class="col-md-12">
            <label class="col-md-6">Would you be willing to relocate?</label>
            <div class="col-md-6 radio-list">
                <label class="radio-inline">
                <?php
                        if(isset($_GET['form_id'])){
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->willing_relocate=='1'){
                                echo '&#10004;';
                            }else{
                                echo '&#10006;';
                            }
                        }else{
                            echo '<input type="radio" name="willing_relocate" value="1" ';
                            if(isset($application_for_employment_gfs)&& $application_for_employment_gfs->willing_relocate=='1')echo "checked='checked'";
                            echo '/>';
                        }
                         ?>

                    Yes &nbsp; &nbsp;
                 </label>
                 <label class="radio-inline">
                 <?php
                        if(isset($_GET['form_id'])){
                            if(isset($application_for_employment_gfs) && $application_for_employment_gfs->willing_relocate=='0'){
                                echo '&#10004;';
                            }else{
                                echo '&#10006;';
                            }
                        }else{
                            echo '<input type="radio" name="willing_relocate" value="0" ';
                            if(isset($application_for_employment_gfs)&& $application_for_employment_gfs->willing_relocate=='0')echo "checked='checked'";
                            echo '/>';
                        }
                         ?>
                     No
                 </label>
             </div>
        </div>
        <div class="col-md-12">
            <label class="col-md-6">Which of your former positions did you like best and why?</label>
            <div class="col-md-12"><textarea class="form-control" name="best_former_posotions"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->best_former_posotions;?></textarea></div>
        </div>
        <p>&nbsp;</p-->
        <div class="col-md-12">
             <h3 class="col-md-12">OTHER INFORMATION</h3>
             <div class="col-md-12"><p>
             You may attach a separate sheet of paper to list any other information necessary to answer fully the above, or add any additional information about yourself that you wish to be considered.</p>
             <textarea name="other_information" class="form-control" placeholder="OTHER INFORMATION"><?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->other_information;?></textarea>
             </div>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12 nothuron">
             <h3 class="col-md-12">BUSINESS REFERENCES</h3>
             <div class="col-md-12">
             <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address and Telephone No.</th>
                        <th>Occupation</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input name="business_communication_name1" class="form-control" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->business_communication_name1;?>" /></td>
                        <td><input name="business_communication_address1" class="form-control" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->business_communication_address1;?>" /></td>
                        <td><input name="business_communication_occupation1" class="form-control" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->business_communication_occupation1;?>" /></td>
                    </tr>
                    <tr>
                        <td><input name="business_communication_name2" class="form-control" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->business_communication_name2;?>" /></td>
                        <td><input name="business_communication_address2" class="form-control" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->business_communication_address2;?>" /></td>
                        <td><input name="business_communication_occupation2" class="form-control" value="<?php if(isset($application_for_employment_gfs))echo $application_for_employment_gfs->business_communication_occupation2;?>" /></td>
                    </tr>
                </tbody>
             </table>
             </div>
        </div>

        <p>&nbsp;</p>
        <div class="col-md-12">
        <div class="col-md-12 nothuron">
            <h3>APPLICANT’S CERTIFICATION AND AGREEMENT</h3>
            <strong>PLEASE READ EACH SECTION CAREFULLY AND CHECK THE BOX:</strong>
            <p>&nbsp;</p>
            <p><LABEL>
                <input type="checkbox" name="checkbox1" id="checkbox1" value="1" <?php if(isset($application_for_employment_gfs) && $application_for_employment_gfs->checkbox1=='1')echo "checked='checked'";?>/> &nbsp; 1. AUTHORIZATION FOR EMPLOYMENT/EDUCATIONAL INFORMATION. I authorize the references listed in this

                Application for Employment, and any prior employer, educational institution, or any other persons or organizations to give <?= $company_name; ?>

                any and all information concerning my previous employment/educational accomplishments, disciplinary information or any other pertinent informa-
                tion they may have, personal or otherwise, and release all parties from all liability for any damage that may result from furnishing same to you. I

                hereby waive written notice that employment information is being provided by any person or organization.
                </LABEL></p>
            <p><LABEL>
                <input type="checkbox" name="checkbox2" id="checkbox2" value="1" <?php if(isset($application_for_employment_gfs) && $application_for_employment_gfs->checkbox2=='1')echo "checked='checked'";?>/> &nbsp; 2. TERMINATION OF EMPLOYMENT. If I am hired, in consideration of my employment, I agree to abide by the rules and policies of

                <?= $company_name; ?>, including any changes made from time to time, and agree that my employment and compensation can be terminated with or

                without cause, at any time with the provision of the appropriate statutory notice or pay in lieu of notice.
                </LABEL></p>
            <p><LABEL>
                <input type="checkbox" name="checkbox3" id="checkbox3" value="1" <?php if(isset($application_for_employment_gfs) && $application_for_employment_gfs->checkbox3=='1')echo "checked='checked'";?>/> &nbsp; 3. RELEASE OF MEDICAL INFORMATION. I authorize every medical doctor, physician or other healthcare provider to provide any

                and all information, including but not limited to, all medical reports, laboratory reports, X-rays or clinical abstracts relating to my previous health

                history or employment in connection with any examination, consultation, tests or evaluation. I hereby release every medical doctor, healthcare per-
                sonnel and every other person, firm, officer, corporation, association, organization or institution which shall comply with the authorization or

                request made in this respect from any and all liability. I understand

                until a job offer has been made
                </LABEL></p>
            <p><LABEL>
                <input type="checkbox" name="checkbox4" id="checkbox4" value="1" <?php if(isset($application_for_employment_gfs) && $application_for_employment_gfs->checkbox4=='1')echo "checked='checked'";?>/> &nbsp; 4. PHYSICAL EXAM AND DRUG AND ALCOHOL TESTING. I agree to take a physical exam and authorize <?= $company_name; ?>

or its designated agent(s) to withdraw specimen(s) of my blood, urine or hair for chemical analysis. One purpose of this analysis is to determine or

exclude the presence of alcohol, drugs or other substances. I authorize the release of the test results to <?= $company_name; ?>. I understand that deci-
sions concerning my employment will be made as a result of these tests.
                </LABEL></p>
            <p><LABEL>
                <input type="checkbox" name="checkbox5" id="checkbox5" value="1" <?php if(isset($application_for_employment_gfs) && $application_for_employment_gfs->checkbox5=='1')echo "checked='checked'";?>/> &nbsp; 5. CONSIDERATION FOR EMPLOYMENT. I understand that my application will be considered pursuant

normal procedures for a period of thirty (30) days. If I am still interested in employment thereafter, I must reapply.
                </LABEL></p>
            <p><LABEL>
                <input type="checkbox" name="checkbox6" id="checkbox6" value="1" <?php if(isset($application_for_employment_gfs) && $application_for_employment_gfs->checkbox6=='1')echo "checked='checked'";?>/> &nbsp; 6. DRIVING RECORDS CHECK. If applying for a position that requires driving a company vehicle, I authorize <?= $company_name; ?>,

Inc. and its agents the authority to make investigations and inquiries of my driving record following a conditional offer of employment.
                </LABEL></p>
            <p><LABEL>
                <input type="checkbox" name="checkbox7" id="checkbox7" value="1" <?php if(isset($application_for_employment_gfs) && $application_for_employment_gfs->checkbox7=='1')echo "checked='checked'";?>/> &nbsp; 7. CERTIFICATION OF TRUTHFULNESS. I certify that all statements on this Application for Employment are completed by me and

to the best of my knowledge are true, complete, without evasion, and further understand and agree that such statements may be investigated and if

found to be false will be sufficient reason for not being employed, or if employed may result in my dismissal. I have read and understood items one

through 7 inclusive, and acknowledge that with my signature below.
            </LABEL></p>
        </div></div>
        <div class="clearfix"></div>
        <p>&nbsp;</p>

        <div class="col-md-12">
              <div class="col-md-6">
                    <label class="col-md-6">Application Dated</label>
                    <input type="text" name="dated" class="form-control datepicker" value="<?= $today;?>" disabled/>
              </div>

              <div class="col-md-6">
                    <label class="">Signature</label>
                    <font color="red"  class="">(Required)</font>
                    <?php
                        if($newsigmethod){
                            includeCanvas("gfs_signature");
                        } else {
                            include('../webroot/canvas/apply.php');
                        }
                    ?>
              </div>

              <div class="clearfix"></div>
              <p>&nbsp;</p>
        </div>

        <div class="col-md-12 subz">
              <a href="javascript:void(0);" class="btn btn-primary btn-lg pull-right" onclick="return check_username();">
                    Next Step <i class="m-icon-swapright m-icon-white"></i>
              </a>
        </div>

        <input type="submit" id="hiddensub" style="display: none;"/>
</form>
<div class="clearfix"></div>
<?php }
backbutton();
?>
</div>
</div>

<script src="../webroot/assets/admin/pages/scripts/form-validate-roy.js"></script>

<script>
    var reasons = new Array();
    reasons["fail"] = '<?= addslashes("'%name%' (%value%) est non valable. (Attendu '%type%')"); ?>';
    reasons["postalcode"] = 'Postal Code';
    reasons["phone"] = 'Phone Number';
    reasons["email"] = 'Email Address';
    reasons["sin"] = 'SIN';
    reasons["required"] = 'Please fill out all the required fields.';
    reasons['postalzip'] = 'Postal or Zip code';

        function check_username() {
            var element, inputs, index;

            if (!checkalltags(false)){return false;}

            <?php if(!isset($ChecksNotNeeded)){ ?>
            for (var checkbox = 1; checkbox < 8; checkbox ++){
                element = document.getElementById("checkbox" + checkbox);
                if(!element.checked){
                    alert("Please read and agree to checkbox " + checkbox);
                    element.scrollIntoView();
                    return false;
                }
            }
            <?php } ?>

            if (<?php if($newsigmethod){
                echo "!savedgfs_signature";
            } else {
                echo "$('.touched').val()==0";
            } ?>){
                alert('Please provide your signature');
                return false;
            }

           if ($('.email').val() != '') {
                var un = $('.email').val();
                $.ajax({
                    url: '<?php echo $webroot2;?>profiles/check_email',
                    data: 'email=' + $('.email').val(),
                    type: 'post',
                    success: function (res) {
                        res = res.trim();
                        if (res == '1') {
                            $('.email').focus();
                            alert('Email already exists');
                            $('html,body').animate({
                                    scrollTop: $('.login-form').offset().top
                                },
                                'slow');
                            return false;
                        } else {
                            $(this).attr('disabled', 'disabled');
                            save_signature('100');
                       }
                   }
               });
          } else {
               alert("The email address is required");
          }

        return true;
     }

     $(function(){
       $('.hiddensub').click(function(){

       })

       <?php if(isset($_GET['form_id'])){?>

            $('.login-form input').attr('disabled','disabled');
            $('.login-form textarea').attr('readonly','readonly');
            $('.login-form select').attr('readonly','readonly');
            $('.subz').hide();

        <?php }?>
           $('#yesCheck').click(function(){
              $("#yesDiv").show();
            });
            $('#noCheck').click(function(){
              $("#yesDiv").hide();
            });

            $('#notAbleToWork').click(function(){
              $("#notAbleDiv").show();
            });
            $('#ableToWork').click(function(){
              $("#notAbleDiv").hide();
            });

            $('#partTime').click(function(){
              $("#partTimeDiv").show();
            });
            $('#fullTime').click(function(){
              $("#partTimeDiv").hide();
            });

    })
    </script>
</body>
</html>


<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>

<script>

    language = 'English';
    $(function () {
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '1980:2020',
            dateFormat: 'yy-mm-dd'
        });
    });
            (function ($) {
    "use strict";

    // Detecting IE
    var oldIE;
    if ($('html').is('.ie6, .ie7, .ie8')) {
        oldIE = true;
    }

    if (oldIE) {
        $('.oldie').show();
        $('.hideoldie').hide();
        // Here's your JS for IE..
    } else {
        $('.oldie').hide();
        $('.hideoldie').show();
        // ..And here's the full-fat code for everyone else
    }

}(jQuery));

</script>
