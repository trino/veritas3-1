<!DOCTYPE html>
<?php
    $settings = $this->requestAction('settings/get_settings');// $Manager->get_settings();
    use Cake\ORM\TableRegistry;
    $debug = $this->request->session()->read('debug');
    include_once('subpages/api.php');
    $language = $this->request->session()->read('Profile.language');
    $strings = CacheTranslations($language, array("langswitch", "permissions_%"), $settings);//,$registry);
?>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title><?php echo $settings->mee; ?> - Dashboard</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <?php
        JSinclude($this, array(
            "css/jquery-ui.css",

            "assets/global/plugins",
                "font-awesome/css/font-awesome.min.css",
                "simple-line-icons/simple-line-icons.min.css",
                "bootstrap/css/bootstrap.min.css",
                "uniform/css/uniform.default.css",
                "bootstrap-switch/css/bootstrap-switch.min.css",
                "select2/select2.css",
                "bootstrap-daterangepicker/daterangepicker-bs3.css",
                "bootstrap-timepicker/css/bootstrap-timepicker.min.css",
                "fullcalendar/fullcalendar.css", //fullcalendar/fullcalendar.print.css
                "jqvmap/jqvmap/jqvmap.css",

            "assets/admin/pages/css",
                "profile.css",
                "pricing-table.css",
                "pricing-tables.css",
                "todo.css",//profile-old.css
                "tasks.css",

            "assets/global/css",
                "components.css" => "style_components",//To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css'
                "plugins.css",

            "assets/admin/layout/css",
                "layout.css",
                "themes/" . $settings->layout . ".css" => "style_color",

            "",
            "css/style.css"
        ));

    ?>
    <!-- TEST REMOVAL <link href="< php echo $this->request->webroot;?>assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/> -->
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="<?php echo WEB_ROOT ?>favicon.ico"/>
    <!--[if lt IE 9]>
    <script src="<?= $this->request->webroot; ?>assets/global/plugins/"></script>
    <script src="<?= $this->request->webroot; ?>assets/global/plugins/excanvas.min.js"></script>
    <![endif]-->

    <?php
        JSinclude($this, array(
            "assets/global/plugins",
                "jquery.min.js",
                "jquery-migrate.min.js",
                "jquery-ui/jquery-ui-1.10.3.custom.min.js",
                "bootstrap/js/bootstrap.min.js",
                "bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js",
                "jquery-slimscroll/jquery.slimscroll.min.js",
                "jquery.blockui.min.js",
                "jquery.cokie.min.js",
                "uniform/jquery.uniform.min.js",
                "bootstrap-switch/js/bootstrap-switch.min.js",

            "assets/global/plugins/jqvmap/jqvmap",
                "jquery.vmap.js",
                "maps/jquery.vmap.russia.js",
                "maps/jquery.vmap.world.js",
                "maps/jquery.vmap.europe.js",
                "maps/jquery.vmap.germany.js",
                "maps/jquery.vmap.usa.js",
                "data/jquery.vmap.sampledata.js",

            "assets/global/plugins/flot",
                "jquery.flot.min.js",
                "jquery.flot.resize.min.js",
                "jquery.flot.categories.min.js",

            "assets/global/plugins",
                "jquery.pulsate.min.js",
                "bootstrap-daterangepicker/moment.min.js",
                "bootstrap-daterangepicker/daterangepicker.js",
                "jquery-easypiechart/jquery.easypiechart.min.js",
                "jquery.sparkline.min.js",
                "bootstrap-fileinput/bootstrap-fileinput.js",
                "ckeditor/ckeditor.js"
        ));
    /*
    <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
    <!-- TEST REMOVAL <script src="< php echo $this->request->webroot;?>assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script> -->
    */
    //<script type="text/javascript" src="<?php echo $this->request->webroot; js/ajaxupload.js"></script>
        includejavascript("", $settings);
        JSinclude($this, "js/ajaxupload.js");

        JSinclude($this, array(
            "assets/global/plugins",
                "jquery-validation/js/jquery.validate.min.js",
                "jquery-validation/js/additional-methods.min.js",
                "bootstrap-wizard/jquery.bootstrap.wizard.min.js",
                "select2/select2.min.js",
                "bootstrap-datepicker/js/bootstrap-datepicker.js",
                "bootstrap-timepicker/js/bootstrap-timepicker.min.js",
                "clockface/js/clockface.js",
                "bootstrap-daterangepicker/moment.min.js",
                "bootstrap-daterangepicker/daterangepicker.js",
                "bootstrap-colorpicker/js/bootstrap-colorpicker.js",
                "bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"
        ));

        translatedatepicker($language, $this);
        //<script src="<?php echo $this->request->webroot; assets/admin/pages/scripts/form-wizard.js"></script>
        //includejavascript($strings);
        JSinclude($this, array(
            "assets/global/scripts/metronic.js",

            "assets/admin/layout/scripts",
                "layout.js",
                "quick-sidebar.js",
                "demo.js",

            "assets/admin/pages/scripts",
                "index.js",
                "tasks.js",
                "profile.js",

                "form-wizard.js",
                "form-validate-roy.js",
                "components-pickers.js",
                "components-dropdowns.js"
        ));
    ?>

    <style>
    .required:after {
        content: " *";
        color: #e32;
    }
        .page-logo a {
            max-width: 100%;
            max-height: 100%;
        }

        .page-logo img {
            max-width: 100%;
            max-height: 70px !important;
        }

        @media print {
            .form-group {
                width: 100%;
            }
            a[href]:after {
                content: none !important;
            }
        <?php
        for($i=1;$i<13;$i++) {
            ?>
            .col-md-<?php echo $i;?> {
                width: <?php echo ($i/12)*100;?>% !important;
                display: inline-block !important;
                float: left;
            }

        <?php
    }
    ?>

        }
    </style>

</head>
<body class="<?= $settings->body; ?>">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner <?php if ($settings->box == '1') echo "container"; ?>">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?= $this->request->webroot; ?>"><img src="<?php
                $logo = $this->requestAction('Logos/getlogo/0', ['return']);
                    $DIR = getcwd() . "/img/logos/";
                    if(!file_exists($DIR . $logo)){
                        $logo = "MEELogo.png";
                    }
                    echo $this->request->webroot . "img/logos/" . $logo;
                    ?>" alt="logo" class="" style="max-width:225px;"/>
            </a>

            <div class="menu-toggler sidebar-toggler hide">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <?php $c = $this->requestAction('profiles/getuser');

                    if ($c) {

                        ?>
                        <li class="dropdown dropdown-user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                               data-close-others="true">
                                <img alt="" class="img-circle" src="<?= profileimage($this->request->webroot, $c); ?>"/>
					<span class="username username-hide-on-mobile">
					<?php echo ucfirst($c->username);?> </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="<?= $this->request->webroot; ?>profiles/edit/<?php echo $this->request->session()->read('Profile.id'); ?>">
                                        <i class="icon-user"></i> <?= $strings["dashboard_mysettings"] ?> </a>
                                </li>
                                <?php if ($debug || true) {
                                    echo '<li><a href="' . $this->request->webroot . 'profiles/langswitch/' . $this->request->session()->read('Profile.id') . '"><i class="icon-user"></i> ';
                                    echo $strings["langswitch"] . '</a></li>';
                                }

                                echo '<li class="divider"></li>';

                                if( $this->request->session()->check('Profile.oldid')){
                                    echo '<LI><a href="' . $this->request->webroot . 'profiles/possess/-1';
                                    echo '" onclick="return confirm(' . "'Are you sure you want to de-possess " . ucfirst(h($this->request->session()->read("Profile.username"))) . "?'";
                                    echo ');" ><i class="icon-key"></i> De-possess</a></LI>';
                                }
                                echo '<li><a href="' . $this->request->webroot . 'profiles/logout"><i class="icon-key"></i> ' . $strings["dashboard_logout"] . '</a></li>';
                                ?>
                            </ul>
                        </li>
                    <?php
                    }
                ?>

            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<?php if ($settings->box == '1'){ ?>
<div class="container"><?php } ?>



    <script>
        var screenWidth = window.screen.width,
            screenHeight = window.screen.height;

        if (screenHeight > 1500) {
            <?php $screenwidth = "col-md-12"; ?>
        }

    </script>

    <div class="page-container <?= $screenwidth ?>">
        <?php
            $productlist = TableRegistry::get('product_types')->find('all');
            include('subpages/sidebar.php');
            echo '<div class="page-content-wrapper"><div class="page-content">';

        echo '<DIV ID="nojavascript" align="CENTER"><STRONG>Javascript is required to use this page, please enable it</STRONG></DIV>';

                    echo $this->Flash->render();
                    echo $this->fetch('content');
                    //debug($permissions);
        ?>
            </div>
        </div>

        <!-- END CONTENT -->
        <!-- BEGIN QUICK SIDEBAR -->
        <a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-close"></i></a>

        <!-- END QUICK SIDEBAR -->
    </div>
    <?php if ($settings->box == '1'){ echo '</div>'; } ?>

<!-- BEGIN FOOTER -->
<div class="page-footer">
    <?php if ($settings->box == '1'){ ?>
    <div class="container"><?php } ?>
        <div class="page-footer-inner">
            &copy; <?php echo $settings->mee; ?> 2015 / <a style="color:white;" href="https://isbc.ca">ISB Canada</a>
            <?php
                if(isset($permissions)){
                    echo '<SPAN STYLE="margin-left:5em;">' . $strings["permissions_used"] . ': ';
                    listpermissions($strings, $permissions, "sidebar");
                    listpermissions($strings, $permissions, "blocks");
                    echo '</SPAN>';
                }

                function listpermissions($strings, $permissions, $Table){
                    $Yes = "&#9745;"; $No = "&#9746;";
                    if(isset($permissions[$Table])){
                        if(!is_array($permissions[$Table])){
                            $permissions[$Table] = array($permissions[$Table]);
                        }
                        foreach($permissions[$Table] as $permission){
                            $Title = str_replace("_", "", $permission);
                            if( isset($strings["permissions_" . $Title])){
                                $Title = $strings["permissions_" . $Title];
                                if (strpos($Title , "_") !== false && isset($strings[$Title])){
                                    $Title = $strings[$Title];
                                }
                            }
                            if($Title) {
                                echo '<A TITLE="' . $strings["permissions_requiredto"] . ": " . $Title . '" ONCLICK="alert(this.getAttribute(' . "'title'" . '));" STYLE="color:';
                                if ($permissions[$Table . "_actual"]->$permission) {
                                    echo '#c9dae9" CLASS="shadow">' . $Yes;
                                } else {
                                    echo 'grey" CLASS="shadow">' . $No;
                                }
                                echo '</A>';
                            }
                        }
                    }
                }

                if($debug) {
                    echo '<SPAN STYLE="margin-left:5em;">Total Time: ' . round(microtime(true) - $StartTime, 4) . ' seconds</span>';
                }
            ?>
        </div>

        <div class="page-footer-inner" style="float:right;">

            <?php
                if (!function_exists('get_title')) {
                    $content = TableRegistry::get("contents")->find('all');

                    function get_title($content, $slug, $language = "English") {
                        $l = FindIterator($content, "slug", $slug);

                        $title = "title";
                        $desc = "desc";
                        if ($language != "English") {
                            $title .= $language;
                            $desc .= $language;
                        }
                        if (isset($l->$title) && strlen($l->$desc) > 0) {
                            return ucfirst($l->$title);
                        }
                    }

                    $isfirst = true;
                    function print_title($content, $webroot, $URL, $slug, $isfirst, $Bypass = false, $language = "English") {
                        if (!$Bypass) {
                            $slug = get_title($content, $slug, $language);
                        }
                        if ($slug) {
                            if (!$isfirst) {
                                echo " / ";
                            }
                            echo '<a style="color:white;" href="' . $webroot . $URL . '">' . $slug . '</a>';
                            return false;
                        }
                        return $isfirst;
                    }
                }
                $isfirst = print_title($content, $this->request->webroot, "pages/view/product_example", "product_example", $isfirst, false, $language);
                $isfirst = print_title($content, $this->request->webroot, "pages/view/help", "help", $isfirst, false, $language);
                $isfirst = print_title($content, $this->request->webroot, "pages/view/faq", "faq", $isfirst, false, $language);
                $isfirst = print_title($content, $this->request->webroot, "pages/view/privacy_code", "privacy_code", $isfirst, false, $language);
                $isfirst = print_title($content, $this->request->webroot, "pages/view/terms", "terms", $isfirst, false, $language);
                if ($this->request->session()->read('Profile.super') && ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['REMOTE_ADDR'] == '24.36.161.100')) {
                    $isfirst = print_title($content, $this->request->webroot, "pages/view/version_log", "version_log", $isfirst, false, $language);
            //      $isfirst = print_title($content, $this->request->webroot, "pages/view/version_log", "email_log", $isfirst, false, $language);

                    $debugmode = false;
                    if (file_exists($_SERVER["DOCUMENT_ROOT"] . "debugmode.txt")){
                        $debugmode = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "debugmode.txt") == $_SERVER['REMOTE_ADDR'];
                    }
                    if ($debugmode) {
                        $debugmode = "dashboard_on";
                    } else {
                        $debugmode = "dashboard_off";
                    }
                    $debugmode = " (" . $strings[$debugmode] . ")";
                    $isfirst = print_title($content, $this->request->webroot, "profiles/settings?toggledebug", $strings["dashboard_debug"] . $debugmode, $isfirst, True, $language);
                    $isfirst = print_title($content, $this->request->webroot, "profiles/settings", $strings["dashboard_settings"], $isfirst, true, $language);

                    $isfirst = print_title($content, $this->request->webroot, "royslog.txt", $strings["dashboard_emaillog"], $isfirst, true, $language);
                }
            ?>
        </div>

        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
        <?php if ($settings->box == '1'){ ?></div><?php } ?>
</div>

<script>
    document.getElementById("nojavascript").setAttribute("style", "display: none;");

    jQuery(document).ready(function () {
        Metronic.init(); // init metronic core componets
        Layout.init(); // init layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        FormWizard.init();
        Index.init();
        Profile.init(); // init page demo
        Index.initDashboardDaterange();
        Index.initJQVMAP(); // init index page's custom scripts
        //Index.initCalendar(); // init index page's custom scripts
        Index.initCharts(); // init index page's custom scripts
        Index.initChat();
        Index.initMiniCharts();
        Tasks.initDashboardWidget();
        ComponentsPickers.init();
        //ComponentsDropdowns.init();
        //change_text(<?php echo $settings->display;?>);


    });
     function validate_data1(Data, DataType){
            return true;
     }

    //change layout
    function change_layout(msg) {
        $.ajax({
            url: "<?= $this->request->webroot; ?>logos/change_layout",
            type: "post",
            data: "layout=" + msg,
            success: function (m) {
                //alert(m);
            }

        });
    }
    function change_box() {
        var cls = "";

        $('body').on('change', function () {
            var b = $('#boxed').val();
            cls = $('body').attr('class');
            if (b == "boxed")
                var box = 1;
            else
                var box = 0;

            var sidebar = $('#mainbar').attr('class');
            //alert(sidebar);

            $.ajax({
                url: "<?= $this->request->webroot; ?>settings/changebody",
                type: "post",
                data: "class=" + cls + '&side=' + sidebar + '&box=' + box,
                success: function (m) {

                }

            });

        });
    }
    function change_body() {
        var cls = "";

        $('body').on('change', function () {

            cls = $('body').attr('class');


            var sidebar = $('#mainbar').attr('class');
            //alert(sidebar);

            $.ajax({
                url: "<?= $this->request->webroot; ?>settings/changebody",
                type: "post",
                data: "class=" + cls + '&side=' + sidebar,
                success: function (m) {

                }

            });

        });


    }

    function sider_bar() {

        $('#mainbar').on('focus', function () {
            var sidebar = $(this).attr('class');
            alert(sidebar);
        });
    }

    function change_text(v) {

        var n = $('#notli').html();
        $.ajax({
            type: "post",
            url: "<?= $this->request->webroot; ?>settings/display",
            data: "display=" + v,
            success: function () {

            }
        });
        var bdy = $('.page-container').not('#notli').html();
        if (v == '2') {
            $('.page-container').html($('.page-container').html().replace(/Client/g, 'Job'));
        }
        if (v == '1') {
            $('.page-container').html($('.page-container').html().replace(/Job/g, 'Client'));
        }
    }


</script>
<div class="overlay-wrapper">
<div class="overlay">
<img src="<?= $this->request->webroot; ?>assets/admin/layout/img/ajax-loading.gif" />
</div>
</div>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
