<!DOCTYPE html><TITLE>MEE</TITLE>
<style>
    .required:after {
        content: " *";
        color: #e32;
    }
 
    .content{
        width: 80% !important;
        margin-top:20px!important;
    }
    .overlay-wrapper {
    background: rgba(255, 255, 255, 0.7) none repeat scroll 0 0;
    height: 100%;
    left: 0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 99999;
    display: none;
}
.overlay {
    left: 50%;
    position: absolute;
    top: 50%;
}
    .listclient{display:block;padding:5px 10px;background:#f5f5f5;margin:5px 0;font-size: 14px;color:#555;text-decoration:none;}
    .listclient:hover{text-decoration:none!important;background:#F4FCFD;font-weight:bold;}
    .steps .col-md-4{margin-bottom:10px;}
    .steps .col-md-12{margin-bottom:10px;}
    .steps span.error{color:red;}
    

    @media print {
        .content{
            width: 90% !important;
        }

        a[href]:after {
            content: none !important;
        }

        .no-print, .no-print * {
            display: none !important;
        }


        .splitcolsOLD {
            -webkit-column-count: 2 !important; /* Chrome, Safari, Opera */
            -moz-column-count: 2 !important; /* Firefox */
            column-count: 2 !important; */
        }

        .row {
            margin-left: -30px;
            margin-right: -30px;
        }

        .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
            float: left;
        }
        .col-md-12 {
            width: 100%;
        }
        .col-md-11 {
            width: 91.66666666666666%;
        }
        .col-md-10 {
            width: 83.33333333333334%;
        }
        .col-md-9 {
            width: 75%;
        }
        .col-md-8 {
            width: 66.66666666666666%;
        }
        .col-md-7 {
            width: 58.333333333333336%;
        }
        .col-md-6 {
            width: 50%;
        }
        .col-md-5 {
            width: 41.66666666666667%;
        }
        .col-md-4 {
            width: 33.33333333333333%;
        }
        .col-md-3 {
            width: 25%;
        }
        .col-md-2 {
            width: 16.666666666666664%;
        }
        .col-md-1 {
            width: 8.333333333333332%;
        }

    }

     .nowrap{
         white-space: nowrap;
     }
</style>


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
            <?php
                //include_once('subpages/api.php');
                //JSinclude($this, "application/assets" );die("HERE");
            ?>

            <link href="<?php echo $this->request->webroot;?>application/assets/opensans.css" rel="stylesheet"
                  type="text/css"/>
            <link href="<?php echo $this->request->webroot;?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"
                  type="text/css"/>
            <link href="<?php echo $this->request->webroot;?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet"
                  type="text/css"/>
            <link href="<?php echo $this->request->webroot;?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"
                  type="text/css"/>
            <link href="<?php echo $this->request->webroot;?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet"
                  type="text/css"/>
            <!-- END GLOBAL MANDATORY STYLES -->
            <!-- BEGIN PAGE LEVEL STYLES -->
            <link href="<?php echo $this->request->webroot;?>assets/global/plugins/select2/select2.css" rel="stylesheet" type="text/css"/>
            <link href="<?php echo $this->request->webroot;?>assets/admin/pages/css/login3.css" rel="stylesheet" type="text/css"/>
            <!-- END PAGE LEVEL SCRIPTS -->
            <!-- BEGIN THEME STYLES -->
            <link href="<?php echo $this->request->webroot;?>assets/global/css/components.css" id="style_components" rel="stylesheet"
                  type="text/css"/>
            <link href="<?php echo $this->request->webroot;?>assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
            <link href="<?php echo $this->request->webroot;?>assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
            <link href="<?php echo $this->request->webroot;?>assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css"
                  id="style_color"/>
            <link href="<?php echo $this->request->webroot;?>assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
            <!-- END THEME STYLES -->
            <link rel="shortcut icon" href="favicon.ico"/>
            <!-- END COPYRIGHT -->
            <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
            <!-- BEGIN CORE PLUGINS -->
            <!--[if lt IE 9]>
            <script src="<?php echo $this->request->webroot;?>assets/global/plugins/respond.min.js"></script>
            <script src="<?php echo $this->request->webroot;?>assets/global/plugins/excanvas.min.js"></script>
            <![endif]-->
            <link href="<?php echo $this->request->webroot;?>application/assets/jquery-ui.css" rel="stylesheet" type="text/css"/>


            <script src="<?php echo $this->request->webroot;?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
            <script src="<?php echo $this->request->webroot;?>assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
            <script src="<?php echo $this->request->webroot;?>application/assets/jquery-ui.min.js" type="text/javascript"></script>
            <script src="<?php echo $this->request->webroot;?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
            <script src="<?php echo $this->request->webroot;?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
            <script src="<?php echo $this->request->webroot;?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
            <script src="<?php echo $this->request->webroot;?>assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
            <!-- END CORE PLUGINS -->
            <!-- BEGIN PAGE LEVEL PLUGINS -->
            <script src="<?php echo $this->request->webroot;?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
            <script type="text/javascript" src="<?php echo $this->request->webroot;?>assets/global/plugins/select2/select2.min.js"></script>
            <!-- END PAGE LEVEL PLUGINS -->
            <!-- BEGIN PAGE LEVEL SCRIPTS -->
            <script src="<?php echo $this->request->webroot;?>assets/global/scripts/metronic.js" type="text/javascript"></script>
            <script src="<?php echo $this->request->webroot;?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
            <script src="<?php echo $this->request->webroot;?>assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
            <!--script src="<?php echo $this->request->webroot;?>assets/admin/pages/scripts/login.js" type="text/javascript"></script-->
            <script type="text/javascript" src="<?php echo $this->request->webroot;?>js/ajaxupload.js"></script>
            
            <!-- END PAGE LEVEL SCRIPTS -->
            <script>
                $(document).ready(function () {
                    Metronic.init(); // init metronic core components
                    Layout.init(); // init current layout
                    // Login.init();
                    Demo.init();
                });

                function removeelement(id) {
                    return (elem=document.getElementById(id)).parentNode.removeChild(elem);
                }
            </script>
            <!-- END JAVASCRIPTS -->
        </head>
        <body class="login">
            <div class="content">
                            <?= $this->Flash->render() ?>
                            <?= $this->fetch('content') ?>
            </div>
            <div class="overlay-wrapper">
            <div class="overlay">
            <img src="<?php echo $this->request->webroot;?>ajax.gif" />
            </div>
            </div>
        </body>
        </html>