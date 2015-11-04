<!DOCTYPE html>
<?php
    $settings = $this->requestAction('settings/get_settings');
    use Cake\ORM\TableRegistry;
    $debug = $this->request->session()->read('debug');
    include_once('subpages/api.php');
    $language = $this->request->session()->read('Profile.language');
    $strings = CacheTranslations($language, "langswitch", $settings);//,$registry);
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

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo $this->request->webroot; ?>assets/global/plugins/font-awesome/css/font-awesome.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="<?php echo $this->request->webroot; ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap/css/bootstrap.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="<?php echo $this->request->webroot; ?>assets/global/plugins/uniform/css/uniform.default.css"
          rel="stylesheet" type="text/css"/>
    <link
        href="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css"
        rel="stylesheet" type="text/css"/>
    <link href="<?php echo $this->request->webroot; ?>css/jquery-ui.css" rel="stylesheet" type="text/css"/>

    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- WARNING ABOUT TEST REMOVALS: The PHP question mark has been removed and would need to be replaced to un-comment the CSS -->
    <link rel="stylesheet" type="text/css"
          href="<?php echo $this->request->webroot; ?>assets/global/plugins/select2/select2.css"/>
    <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
    <link
        href="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"
        rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
    <link href="<?php echo $this->request->webroot; ?>assets/global/plugins/fullcalendar/fullcalendar.css"
          rel="stylesheet" type="text/css"/>

    <!--<link href="<?php echo $this->request->webroot; ?>assets/global/plugins/fullcalendar/fullcalendar.print.css" rel="stylesheet" type="text/css"/>-->
    <link href="<?php echo $this->request->webroot; ?>assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet"
          type="text/css"/>

    <link href="<?php echo $this->request->webroot; ?>assets/admin/pages/css/profile.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo $this->request->webroot; ?>assets/admin/pages/css/pricing-table.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo $this->request->webroot; ?>assets/admin/pages/css/pricing-tables.css" rel="stylesheet"
          type="text/css"/>

    <link href="<?php echo $this->request->webroot; ?>assets/admin/pages/css/todo.css" rel="stylesheet"
          type="text/css"/>
    <!--<link href="<?php echo $this->request->webroot; ?>assets/admin/pages/css/profile-old.css" rel="stylesheet" type="text/css"/>-->

    <link href="<?php echo $this->request->webroot; ?>assets/admin/pages/css/tasks.css" rel="stylesheet"
          type="text/css"/>

    <!-- DOC: To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css' in the below style tag -->
    <link href="<?php echo $this->request->webroot; ?>assets/global/css/components.css" id="style_components"
          rel="stylesheet" type="text/css"/>
    <link href="<?php echo $this->request->webroot; ?>assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo $this->request->webroot; ?>assets/admin/layout/css/layout.css" rel="stylesheet"
          type="text/css"/>
    <link
        href="<?php echo $this->request->webroot; ?>assets/admin/layout/css/themes/<?php echo $settings->layout; ?>.css"
        rel="stylesheet" type="text/css" id="style_color"/>

    <link href="<?php echo $this->request->webroot; ?>css/style.css" rel="stylesheet" type="text/css"/>


    <!-- TEST REMOVAL <link href="< php echo $this->request->webroot;?>assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/> -->
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="<?php echo WEB_ROOT ?>favicon.ico"/>

    <!--[if lt IE 9]>
    <script src="<?php echo $this->request->webroot;?>assets/global/plugins/respond.min.js"></script>
    <script src="<?php echo $this->request->webroot;?>assets/global/plugins/excanvas.min.js"></script>
    <![endif]-->
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/jquery.min.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/jquery-migrate.min.js"
            type="text/javascript"></script>
    <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap/js/bootstrap.min.js"
            type="text/javascript"></script>
    <script
        src="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"
        type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/jquery.blockui.min.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/jquery.cokie.min.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/uniform/jquery.uniform.min.js"
            type="text/javascript"></script>
    <script
        src="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"
        type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js"
            type="text/javascript"></script>
    <script
        src="<?php echo $this->request->webroot; ?>assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js"
        type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/flot/jquery.flot.min.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/flot/jquery.flot.resize.min.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/flot/jquery.flot.categories.min.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/jquery.pulsate.min.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-daterangepicker/moment.min.js"
            type="text/javascript"></script>
    <script
        src="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"
        type="text/javascript"></script>
    <!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
    <!-- TEST REMOVAL <script src="< php echo $this->request->webroot;?>assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script> -->
    <script
        src="<?php echo $this->request->webroot; ?>assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js"
        type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/jquery.sparkline.min.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"
            type="text/javascript"></script>


    <script type="text/javascript"
            src="<?php echo $this->request->webroot; ?>assets/global/plugins/ckeditor/ckeditor.js"></script>


    <?php //<script type="text/javascript" src="<?php echo $this->request->webroot; js/ajaxupload.js"></script>
        includejavascript("", $settings);
        JSinclude($this, "js/ajaxupload.js");
    ?>

    <script type="text/javascript"
            src="<?php echo $this->request->webroot; ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
    <script type="text/javascript"
            src="<?php echo $this->request->webroot; ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script>
    <script type="text/javascript"
            src="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
    <script type="text/javascript"
            src="<?php echo $this->request->webroot; ?>assets/global/plugins/select2/select2.min.js"></script>

    <script type="text/javascript"
            src="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript"
            src="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <script type="text/javascript"
            src="<?php echo $this->request->webroot; ?>assets/global/plugins/clockface/js/clockface.js"></script>
    <script type="text/javascript"
            src="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-daterangepicker/moment.min.js"></script>
    <script type="text/javascript"
            src="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script type="text/javascript"
            src="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
    <script type="text/javascript"
            src="<?php echo $this->request->webroot; ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <?php
        translatedatepicker($language, $this);
    ?>
    <!-- default END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?php echo $this->request->webroot; ?>assets/global/scripts/metronic.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/admin/layout/scripts/layout.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/admin/layout/scripts/quick-sidebar.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/admin/layout/scripts/demo.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/admin/pages/scripts/index.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/admin/pages/scripts/tasks.js"
            type="text/javascript"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/admin/pages/scripts/profile.js"
            type="text/javascript"></script>

    <?php //<script src="<?php echo $this->request->webroot; assets/admin/pages/scripts/form-wizard.js"></script>
        //includejavascript($strings);
        JSinclude($this, "assets/admin/pages/scripts/form-wizard.js");
        JSinclude($this, "assets/admin/pages/scripts/form-validate-roy.js");
    ?>

    <script src="<?php echo $this->request->webroot; ?>assets/admin/pages/scripts/components-pickers.js"></script>
    <script src="<?php echo $this->request->webroot; ?>assets/admin/pages/scripts/components-dropdowns.js"></script>

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
            <a href="<?php echo $this->request->webroot; ?>">
                <?php $logo = $this->requestAction('Logos/getlogo/0', ['return']); ?>
                <img src="<?php echo $this->request->webroot; ?>img/logos/<?php echo $logo; ?>" alt="logo" class=""
                     style="max-width:225px;"/>
            </a>

            <div class="menu-toggler sidebar-toggler hide">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse">
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">

                  <ul class="dropdown-menu">
                    <li class="external">
                        <h3>You have <span class="bold">0 New</span> Messages</h3>
                        <a href="#">view all</a>
                    </li>
                    <li>
                        <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">

                        </ul>
                    </li>
                </ul>
              
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
                                    <a href="<?php echo $this->request->webroot;?>profiles/edit/<?php echo $this->request->session()->read('Profile.id'); ?>">
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
        ?>

        <div class="page-content-wrapper">

            <div class="page-content">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </div>

        <!-- END CONTENT -->
        <!-- BEGIN QUICK SIDEBAR -->
        <a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-close"></i></a>

        <!-- END QUICK SIDEBAR -->
    </div>
    <?php if ($settings->box == '1'){ ?></div><?php } ?>

<!-- BEGIN FOOTER -->
<div class="page-footer">
    <?php if ($settings->box == '1'){ ?>
    <div class="container"><?php } ?>
        <div class="page-footer-inner">
            &copy; <?php echo $settings->mee; ?> 2015 <!--a style="color:white;" href="https://isbc.ca">ISB Canada</a-->
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
                    function print_title($content, $webroot, $URL, $slug, $isfirst, $Bypass = false, $language = "English")
                    {
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

                }
            ?>
        </div>

        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
        <?php if ($settings->box == '1'){ ?></div><?php } ?>
</div>

<script>

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
            url: "<?php echo $this->request->webroot;?>logos/change_layout",
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
                url: "<?php echo $this->request->webroot;?>settings/changebody",
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
                url: "<?php echo $this->request->webroot;?>settings/changebody",
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
            url: "<?php echo $this->request->webroot;?>settings/display",
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
<img src="<?php echo $this->request->webroot;?>assets/admin/layout/img/ajax-loading.gif" />
</div>
</div>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
