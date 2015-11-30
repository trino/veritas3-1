<?php
    $uid = ($this->request['action'] == 'add') ? "0" : $this->request['pass'][0];
    $sidebar = $Manager->loadpermissions($Me, "sidebar");
    $block = $Manager->loadpermissions($Me, "blocks");

    $YourSidebar = $Manager->loadpermissions(-1, "sidebar");
    $isadmin = $Manager->read("admin") == 1 || $Manager->read("super") == 1;
    if (!isset($is_disabled1)) {
        $is_disabled1 = "";
    }//something is wrong with this variable

    $activetab = "config";
    if ($activetab == "permissions") {
        if ((isset($Clientcount) && $Clientcount == 0) || $this->request->session()->read('Profile.profile_type') == '2') {
            $activetab = "assign";
        }
    } else {
        if ($this->request->session()->read('Profile.profile_type') == '2') {
            $activetab = "assign";
        }
    }

?>
<script>
    <?php
    if($this->request->params['action']=='edit')
    {
        ?>
    $(function () {
        $('#searchClient').keyup(function () {

            var key = $('#searchClient').val();
            $('#clientTable').html('<tbody><tr><td><img src="<?php echo $this->request->webroot;?>assets/admin/layout/img/ajax-loading.gif"/></td></tr></tbody>');
            $.ajax({
                url: '<?php echo $this->request->webroot;?>clients/getAjaxClient/<?php echo $id;?>',
                data: 'key=' + key,
                type: 'get',
                success: function (res) {
                    $('#clientTable').html(res);
                }
            });

        });
    });

    <?php
    }
    else
    {
    ?>

    $(function () {
        $('#searchClient').keyup(function () {

            var key = $('#searchClient').val();
            $('#clientTable').html('<tbody><tr><td><img src="<?php echo $this->request->webroot;?>assets/admin/layout/img/ajax-loading.gif"/></td></tr></tbody>');
            $.ajax({
                url: '<?php echo $this->request->webroot;?>clients/getAjaxClient',
                data: 'key=' + key,
                type: 'get',
                success: function (res) {
                    $('#clientTable').html(res);
                }
            });
        });
    });

    <?php
    }
    ?>
    $(function () {
        $('.scrolldiv').slimScroll({
            height: '250px'
        });

    });
</script>
<link href="<?php echo $this->request->webroot; ?>assets/admin/pages/css/profile.css" rel="stylesheet"
      type="text/css"/> <!--REQUIRED-->
<style>
    @media print {
        .page-header {
            display: none;
        }

        .page-footer, .nav-tabs, .page-title, .page-bar, .theme-panel, .page-sidebar-wrapper {
            display: none !important;
        }

        .portlet-body, .portlet-title {
            border-top: 1px solid #578EBE;
        }

        .tabbable-line {
            border: none !important;
        }

        #tab_1_4, #tab_1_7 {
            display: block !important;
        }

        #tab_1_4, #tab_1_7 {
            visibility: visible !important;
        }

        #tab_1_4 *, #tab_1_7 * {
            visibility: visible !important;
        }
    }
</style>

<?php
    $is_disabled = '';
    if (isset($disabled)) {
        $is_disabled = 'disabled="disabled"';
    }// style="border: 0px solid;"';}
    $hidepermissions = true;
    $userID = $this->request->session()->read('Profile.id');
    if (isset($profile)) {
        $p = $profile;
        $hidepermissions = $this->request->session()->read('Profile.admin') && $userID == $p->id;
    }

    //  $CanOrder = $Manager->loadpermissions($userID, "sidebar")->orders_create;
    $CanOrder = true;

    $settings = $Manager->get_settings();

    /*  what is this supposed to do?
        if ($this->request->session()->read('Profile.super')) {
            $sidebar = $this->requestAction("settings/all_settings/0/sidebar");
        } else {
            $sidebar = $this->requestAction("settings/all_settings/" . $userID . "/sidebar");
        }
    */

    include_once('subpages/api.php');
    $language = $this->request->session()->read('Profile.language');
    $strings = CacheTranslations($language, array("profiles_%", "forms_%", "file_missingdata", "clients_addeditimage", "clients_enablerequalify", "theme_%", "month_long%", "flash_cantorder%"), $settings);
    $Trans = "";
    if ($language == "Debug") {
        $Trans = " [Trans]";
    }
    $debug = $this->request->session()->read('debug');
    $param = $this->request->params['action'];
    $param2 = $strings["profiles_" . $param];

    if ($param != "add" && $userID == $this->request->params["pass"][0]) {
        include_once('subpages/profile/theme.php');
    }
    //includejavascript($strings);

    loadreasons($param, $strings, true);
?>
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->


<div class="clearfix"></div>
<h3 class="page-title">
    <?= $param2; ?>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo $this->request->webroot; ?>"><?= $strings["dashboard_dashboard"]; ?></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href=""><?= $param2; ?></a>
        </li>
    </ul>

    <?php
        if (isset($disabled)) {
            echo '<a href="javascript:window.print();" class="floatright btn btn-primary">' . $strings["dashboard_print"] . '</a>';
        }
        if (isset($profile) && $YourSidebar && $YourSidebar->profile_delete == '1') {
            if ($this->request->session()->read('Profile.super') == '1' || ($this->request->session()->read('Profile.profile_type') == '2' && ($profile->profile_type == '5'))) {
                if ($this->request->session()->read('Profile.id') != $profile->id) {
                    ?>
                        <a href="<?= $this->request->webroot; ?>profiles/delete/<?= $profile->id; ?><?php echo (isset($_GET['draft'])) ? "?draft" : ""; ?>"
                           onclick="return confirm('<?= ProcessVariables($language, $strings["dashboard_confirmdelete"], array("name" => addslashes(formatname($profile))), true);?>');"
                           class="floatright btn btn-danger btnspc"><?= $strings["dashboard_delete"]; ?></a>
                        </span>
                    <?php
                }
            }
        }
        if (isset($profile)) {
            $checker = $this->requestAction('settings/check_edit_permission/' . $this->request->session()->read('Profile.id') . '/' . $profile->id);
            if ($YourSidebar && $YourSidebar->profile_edit == '1' && $param == 'view') {
                echo $this->Html->link(__($strings["dashboard_edit"]), ['action' => 'edit', $profile->id], ['class' => 'floatright btn btn-primary btnspc']);
            } else if ($param == 'edit') {
                echo $this->Html->link(__($strings["dashboard_view"]), ['action' => 'view', $profile->id], ['class' => 'floatright btn btn-primary btnspc']);
            }
            if ($this->request->session()->read('Profile.super') && $this->request->session()->read('Profile.id') != $profile->id) {
                echo '<a href="' . $this->request->webroot . 'profiles/possess/' . $profile->id;
                echo '" onclick="return confirm(' . "'Are you sure you want to possess " . formatname($profile) . "?'";
                echo ');" class="floatright btn btnspc btn-danger">' . $strings["dashboard_possess"] . '</a>';
            }
        }
        if ($YourSidebar && $YourSidebar->profile_edit == '1' && $param == 'view') {
            $checker = $this->requestAction('settings/check_edit_permission/' . $this->request->session()->read('Profile.id') . '/' . $profile->id);
            if ($checker == 1) {
                ?>
                <a href="<?php
                    if ($profile->profile_type == '5') {
                        $tag = "submitted_for_id";
                    } else {
                        $tag = "submitted_by_id";
                    }
                    echo $this->request->webroot . 'documents/index?type=&' . $tag . '=' . $profile->id;
                ?>" class=" floatright btn  btn-primary btnspc"><?= $strings["profiles_mydocuments"]; ?></a>
                <?php
            }
        }

        if ($this->request->session()->read('debug') && ($param == "edit" || $param == "add")) {
            echo '<A ONCLICK="autofill2(false);" class="floatright btn btnspc btn-warning">' . $strings["dashboard_autofill"] . '</A>';
        }
    ?>
</div>


<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row margin-top-20">
    <div class="col-md-12">

        <div class="profile-content">
            <div class="row">
                <div class="col-md-3">
                    <!-- PORTLET MAIN -->
                    <div class="portlet light profile-sidebar-portlet">
                        <!-- SIDEBAR USERPIC -->
                        <div class="profile-userpic" style="max-width:250px;margin:0 auto;">
                            <img class="img-responsive" id="clientpic"
                                 alt="" style="height: auto;"
                                 src="<?php
                                     if (isset($profile)) {
                                         echo profileimage($this->request->webroot, $profile);
                                     } else {
                                         echo profileimage($this->request->webroot);
                                     }
                                 ?>"
                                />
                            <?php if (isset($id) && !(isset($disabled)) && $this->request->params['action'] == 'edit') { ?>
                                <center>
                                    <div class="form-group">
                                        <label class="sr-only"
                                               for="exampleInputEmail22"><?= $strings["clients_addeditimage"]; ?></label>

                                        <div class="input-icon">
                                            <br/>
                                            <a class="btn btn-xs  btn-primary   margin-t10" href="javascript:void(0)"
                                               id="clientimg">
                                                <i class="fa fa-image"></i>
                                                <?= $strings["clients_addeditimage"]; ?>
                                            </a>

                                        </div>
                                    </div>
                                </center>
                            <?php } ?>

                        </div>
                        <!-- END SIDEBAR USERPIC -->
                        <!-- SIDEBAR USER TITLE -->
                        <div class="profile-usertitle">
                            <div class="profile-usertitle-name">
                                <?php if (isset($p->fname)) echo ucwords($p->fname . ' ' . $p->lname); ?>
                            </div>

                            <?php
                                /*
                                if (isset($p->isb_id) && ($p->isb_id != "") && ($settings->mee == "MEE")) {
                                ?>
                                <div class="profile-usertitle-job">
                                    <small>
                                        ISB ID: <?php echo $p->isb_id; ?>
                                    </small>
                                </div>
                            <?php }
                                */

                                if (isset($p)) {
                                    $ClientID = $Manager->find_client($profile->id, true);
                                    if (!$profile->Ptype || ($profile->Ptype && $profile->Ptype->placesorders == 1) && $CanOrder) {

                                        $MissingFields = false;//$Manager->requiredfields(false, "profile2order");
                                        $MissingData = false;//$Manager->requiredfields($profile, "profile2order");
                                        $Missing = array();
                                        $sidebar = $Manager->loadpermissions($Me, "sidebar");

                                        if (true) { //$profile->is_complete && !$MissingData) {

                                            echo '<label class="uniform-inline" style="clear:both;margin-bottom: 20px;">
                                        <input type="checkbox" name="" value="1" id="' . $profile->id . '" class="checkrequalify"' . $is_disabled;
                                            if ($p->requalify == '1') {
                                                echo " checked";
                                            }
                                            echo '> ' . $strings["clients_enablerequalify"] . '<span class="req_msg"></span></label>';

                                            //driver, owner driver, owner operator, sales, employee
                                            echo '<label class="uniform-inline" style="">';

                                            echo '<input type="checkbox" name="stat" value="1" id="' . $profile->id . '" class="checkhiredriver"' . $is_disabled;
                                            if ($p->is_hired == '1') {
                                                echo " checked";
                                            }
                                            echo '/> ' . $strings["profiles_washired"] . ' <p class="hired_msg"></p></label>';
                                            if (isset($profile)) {
                                                ?>
                                                <div class="hired_date"
                                                     style='display:<?php if ($profile->is_hired == '0') echo "none"; ?>;'>
                                                    <?= $strings["forms_hireddate"] . ': ' . $profile->hired_date; ?>
                                                </div>
                                            <?php }



                                        }

                                        if (!$profile->iscomplete || $MissingData) {
                                            $Debug = ' (' . $MissingData . '|' . $profile->iscomplete . ')';
                                            if (!$profile->iscomplete) {
                                                $Missing[] = "Letter of Experience";
                                                $Missing[] = "Consent form";
                                            }
                                            foreach ($MissingFields as $Field => $String) {
                                                if (!$profile->$Field) {
                                                    $Missing[] = $strings[$String];
                                                }
                                            }

                                            //echo "<BR><B>" . $strings["flash_cantorder"] . '</BR><HR>' .
                                            echo "<BR><B>" . $strings["flash_cantorder2"] . ': </B>';
                                            echo implode(", ", $Missing);
                                        } else if (!isset($sidebar->orders_create)) {
                                            echo "<BR>" . $strings["flash_cantorder4"];
                                        } else if ($sidebar->orders_create == 1) {
                                            $title = getFieldname("Name", $language);
                                            echo '<DIV ID="doplaceorders"';
                                            if (!$ClientID) {
                                                echo ' STYLE="visibility: hidden;"';
                                            }
                                            echo '>';
                                            foreach ($products as $product) {
                                                $alias = $product->Sidebar_Alias;
                                                if ($alias && $alias != "bulk") {
                                                    $showit = false;
                                                    if ($product->profile_types) {
                                                        $profile_types = explode(",", $product->profile_types);
                                                        $showit = in_array($profile->profile_type, $profile_types);
                                                    }
                                                    if ($sidebar->$alias == 1 && $product->Visible == 1 && $showit) {
                                                        echo '<br><a href="' . $this->request->webroot . 'orders/productSelection?driver=' . $profile->id;
                                                        echo '&ordertype=' . $product->Acronym . '"';
                                                        echo ' class="blue-stripe btn floatleft ' . $product->ButtonColor . '" style="margin-top:2px;width:100%;">' . $product->$title . $Trans;
                                                        echo ' </a>';
                                                    }
                                                }
                                            }
                                            echo '</DIV>';
                                        } else {
                                            echo $strings["flash_cantorder5"];
                                        }

                                        if ($clients && isset($client) && $profile->email) {
                                            if (stristr("gordon food service", $client->company_name) || stristr("gfs", $client->company_name)) {
                                                echo '<P><P><a id="removethis" href="';
                                                echo '" onclick="return sendemails();" class="blue-stripe btn floatleft grey-cascade" style="margin-top:2px;width:75%; display:none;">' . $strings["profiles_sendforms"];
                                                echo ' <i class="icon-doc m-icon-white"></i></a>';
                                            }
                                        }
                                    } else {
                                        if (!$CanOrder) {
                                            echo $strings["flash_cantorder5"];
                                        } else {
                                            echo $strings["flash_cantorder6"];
                                        }
                                    }
                                }

                                //if (isset($client_docs)) {
                                //    include_once 'subpages/filelist.php';
                                //    listfiles($client_docs, "img/jobs/", 'profile_doc', false, 2);
                                //}
                            ?>
                        </div>


                    </div>


                </div>


                <div class="col-md-9">
                    <div class="portlet paddingless">
                        <div class="portlet-title line" style="display:none;">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span
                                    class="caption-subject font-blue-madison bold"><?php echo ucfirst($settings->profile); ?>
                                    Manager</span>
                            </div>
                        </div>

                        <div class="portlet-body">
                            <?php
                                if ($this->request['action'] == 'view' && ($profile->Ptype && $profile->Ptype->placesorders == 1)) {
                                    $activetab = "scorecard";
                                } else {
                                    $activetab = "profile";
                                }
                                //if ($this->request->session()->read('Profile.profile_type') > 1) {//is not an admin, block.php suggests using =2
                                if (isset($_GET['getprofilescore'])) {
                                    $activetab = "scorecard";
                                } //
                                if (isset($_SERVER['HTTP_REFERER'])) {
                                    if (strpos($_SERVER['HTTP_REFERER'], "profiles/edit/" . $id) > 0 or strpos($_SERVER['HTTP_REFERER'], "profiles/add") > 0 or strpos($_SERVER['HTTP_REFERER'], "productSelection") > 0 or isset($_GET["clientflash"])) { //. $id
                                        if (isset($Clientcount) && $Clientcount == 0) {
                                            $activetab = "permissions";
                                        }
                                    }
                                }

                                if (isset($_GET['activetab'])) {
                                    $activetab = $_GET['activetab'];
                                }

                                function activetab(&$activetab, $name, $needsclass = True)
                                {
                                    if ($activetab == $name || $activetab == "") {
                                        if (!$activetab) {
                                            $activetab = $name;
                                        }
                                        if ($needsclass) {
                                            echo " class='active'";
                                        } else {
                                            echo " active";
                                        }
                                        return $name;
                                    }
                                    return $activetab;
                                }

                            ?>
                            <!--BEGIN TABS-->
                            <div class="tabbable tabbable-custom">
                                <ul class="nav nav-tabs">
                                    <?php
                                        if ($this->request->session()->read('Profile.super') != '1' && $activetab == "permissions") {
                                            $activetab = "";
                                        }

                                        if ($this->request['action'] == 'view' && ($profile->Ptype && $profile->Ptype->placesorders == 1)) {
                                            ?>
                                            <li <?php activetab($activetab, "scorecard"); ?>>
                                                <a href="#tab_1_11"
                                                   data-toggle="tab"><?= $strings["profiles_viewscorecard"]; ?></a>
                                            </li>
                                            <?php
                                        }
                                    ?>
                                    <li <?php if ($this->request['action'] == 'view' && ($profile->Ptype && $profile->Ptype->placesorders == 1)) {
                                    } else {
                                        activetab($activetab, "profile");
                                    } ?> >
                                        <a href="#tab_1_1" data-toggle="tab"><?= $strings["profiles_profile"]; ?></a>
                                    </li>

                                    <?php

                                        if ($this->request['action'] != 'add') {

                                            if ($this->request->params['action'] != 'add' && $CanOrder) { ?>
                                                <li<?php activetab($activetab, "notes"); ?>>
                                                    <a href="#tab_1_9"
                                                       data-toggle="tab"><?= $strings["profiles_notes"]; ?></a>
                                                </li>


                                            <?php }
                                            $checker = $this->requestAction('/settings/check_edit_permission/' . $this->request->session()->read('Profile.id') . '/' . $profile->id . "/" . $profile->created_by);
                                            if ($this->request->session()->read('Profile.super') == '1') {//} || ($sidebar->profile_create == '1' && $sidebar->profile_edit == '1')) {?>
                                                <li <?php activetab($activetab, "permissions"); ?>>
                                                    <a href="#tab_1_7"
                                                       data-toggle="tab"><?= $strings["profiles_permissions"]; ?></a>
                                                </li>

                                            <?php }

                                            if (isset($profile->email) && $CanOrder && $uid <> $Manager->read("id")) { ?>
                                                <li <?php activetab($activetab, "feedback"); ?> >
                                                    <a href="#tab_1_8" data-toggle="tab">Message</a>
                                                </li>
                                                <?php
                                            }
                                        }
                                    ?>
                                </ul>


                                <div class="tab-content" style="padding: 10px;">
                                    <!-- PERSONAL INFO TAB -->


                                    <div class="tab-pane  <?php activetab($activetab, "profile", false); ?> "
                                         id="tab_1_1" style="padding: 10px;">
                                        <input type="hidden" name="user_id" value="<?php echo ""; ?>"/>
                                        <?php include('subpages/profile/info.php'); ?>
                                    </div>
                                    <!-- END PERSONAL INFO TAB -->
                                    <!-- CHANGE AVATAR TAB -->

                                    <?php if ($this->request['action'] != 'add') { ?>

                                        <div class="tab-pane <?php activetab($activetab, "notes", false); ?>"
                                             id="tab_1_9" style="padding: 10px;">
                                            <div class="cleafix">&nbsp;</div>

                                            <div class="portlet-body">
                                                <?php include('subpages/documents/recruiter_notes.php');//notes ?>
                                            </div>
                                        </div>

                                    <?php }

                                        if ($this->request['action'] == 'view') { ?>
                                            <div class="tab-pane <?php activetab($activetab, "scorecard", false); ?>"
                                                 id="tab_1_11" style="padding: 10px;">
                                                <?php include('subpages/documents/forview.php'); ?>
                                            </div>
                                        <?php }

                                        if ($this->request->session()->read('Profile.super')) { ?>
                                            <div class="tab-pane <?php activetab($activetab, "permissions", false); ?>"
                                                 id="tab_1_7">
                                                <?php if (!isset($BypassLogin)) $BypassLogin = false;
                                                    if (!$BypassLogin) {
                                                        include('subpages/profile/block.php');
                                                    }//permissions
                                                ?>
                                            </div>
                                        <?php } ?>
                                    <div class="tab-pane <?php activetab($activetab, "feedback", false); ?>"
                                         id="tab_1_8">
                                        <? include('subpages/profile/email.php'); ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROFILE CONTENT -->
        </div>
    </div>
</div>


<script>


    function initiate_ajax_upload(button_id) {
        var button = $('#' + button_id), interval;
        new AjaxUpload(button, {
            action: "<?php echo $this->request->webroot;?>profiles/upload_img/<?php if(isset($id))echo $id;?>",
            name: 'myfile',
            onSubmit: function (file, ext) {
                button.text('<?= addslashes($strings["forms_uploading"]); ?>');
                this.disable();
                interval = window.setInterval(function () {
                    var text = button.text();
                    if (text.length < 13) {
                        button.text(text + '.');
                    } else {
                        button.text('<?= addslashes($strings["forms_uploading"]); ?>');
                    }
                }, 200);
            },
            onComplete: function (file, response) {
                button.html('<i class="fa fa-image"></i> <?= addslashes($strings["clients_addeditimage"]); ?>');
                window.clearInterval(interval);
                this.enable();
                $("#clientpic").attr("src", '<?php echo $this->request->webroot;?>img/profile/' + response);
                $('#client_img').val(response);
                alert('<?= addslashes($strings["forms_datasaved"]); ?>');
            }
        });
    }
    $(function () {

        <?php
        if(isset($id))
        {
            if($this->request->params['action'] != 'view')
            {
                ?>

        initiate_ajax_upload('clientimg');
        <?php
            }
         ?>
        $('.addclientz').click(function () {
            /*
             var client_id = $(this).val();
             var addclient = "";
             var msg = '';
             var nameId = 'msg_' + $(this).val();
             if ($(this).is(':checked')) {
             addclient = '1';
             msg = '<span class="msg" style="color:#45B6AF">
            <?= addslashes($strings["forms_added"]); ?></span>';
             }
             else {
             addclient = '0';
             msg = '<span class="msg" style="color:red">
            <?= addslashes($strings["forms_removed"]); ?></span>';
             }

            <?php if(isset($profile) && ($profile->admin == 0 && $profile->super == 0)){; ?>
             $('.addclientz').each(function () {
             if ($(this).val() != client_id) {
             $(this).prop("disabled", addclient == 1);
             var parent = $(this).parent().parent();
             if (addclient == 1) {
             parent.addClass("disabled");
             } else {
             parent.removeClass("disabled");
             }
             }
             });
            <?php } ?>

             $.ajax({
             type: "post",
             data: "client_id=" + client_id + "&add=" + addclient + "&user_id=" +
            <?php //echo $id;?>,
             url: "
            <?php //echo $this->request->webroot;?>clients/addprofile",
             success: function () {
             $('.' + nameId).html(msg);
             }
             })*/
        });
        <?php
         }
         else
         {?>
        $('.addclientz').click(function () {
            /*
             var nameId = 'msg_' + $(this).val();
             var client_id = "";
             var msg = '';
             $('.addclientz').each(function () {
             if ($(this).is(':checked')) {
             msg = '<span class="msg" style="color:#45B6AF">
            <?= addslashes($strings["forms_added"]); ?></span>';
             client_id = client_id + "," + $(this).val();
             }
             else {
             msg = '<span class="msg" style="color:red">
            <?= addslashes($strings["forms_removed"]); ?></span>';
             }
             });

             client_id = client_id.substr(1, length.client_id);
             $('.client_profile_id').val(client_id);
             $('.' + nameId).html(msg);
             */
        });
        <?php
        }
        ?>
        $('#save_client_p1').click(function () {

            $('#save_client_p1').text('<?= addslashes($strings["forms_saving"]); ?>');

            $("#pass_form").validate({
                rules: {
                    password: {
                        required: true
                    },
                    retype_password: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                messages: {
                    password: "<?= addslashes($strings["forms_passplease"]); ?>",
                    retype_password: "<?= addslashes($strings["forms_passnotequal"]); ?>"
                },
                submitHandler: function () {
                    $('#pass_form').submit();
                }
            });
        });

    });
</script>
<script>
    $(function () {
        $('input,textarea,select').each(function () {


            var attr = $(this).attr('required');

            // For some browsers, `attr` is undefined; for others,
            // `attr` is false.  Check for both.
            if (typeof attr !== typeof undefined && attr !== false) {
                $(this).parent().find('label').addClass('required');
            }
            else {
                $(this).parent().find('label').removeClass('required');
            }

        })

        $('.checkhiredriver').click(function () {

            var oid = $(this).attr('id');
            var msgs = '';
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var Y = today.getFullYear();

            if (dd < 10) {
                dd = '0' + dd
            }

            if (mm < 10) {
                mm = '0' + mm
            }
            var tday = Y + '-' + mm + '-' + dd;
            if ($(this).is(":checked")) {
                var hired = 1;
                var hired_date = tday;
                msg = '<span class="msg" style="color:#45B6AF"> <?= addslashes($strings["forms_added"]); ?></span>';
                $('.date_hired').val(tday);
                $('.hired_date').show();
            }
            else {
                var hired = 0;
                var hired_date = '0000-00-00';
                msg = '<span class="msg" style="color:red"> <?= addslashes($strings["forms_removed"]); ?></span>';
                $('.date_hired').val('0000-00-00');
                $('.hired_date').hide();
            }

            $.ajax({
                url: "<?php echo $this->request->webroot;?>orders/savedriver/" + oid,
                type: 'post',
                data: 'is_hired=' + hired + '&hired_date=' + hired_date,
                success: function () {
                    $('.hired_msg').html(msg);
                }
            })
        });

        $('.checkrequalify').click(function () {
            var oid = $(this).attr('id');
            var msgs = '';
            if ($(this).is(":checked")) {
                var hired = 1;
                msg = '<span class="msg" style="color:#45B6AF"> <?= addslashes($strings["forms_added"]); ?></span>';
            }
            else {
                var hired = 0;
                msg = '<span class="msg" style="color:red"> <?= addslashes($strings["forms_removed"]); ?></span>';
            }

            $.ajax({
                url: "<?php echo $this->request->webroot;?>orders/requalify/" + oid,
                type: 'post',
                data: 'requalify=' + hired,
                success: function () {
                    $('.req_msg').html(msg);
                }
            })
        })
        /*
        <?php echo $this->request->webroot;?>clients/assignProfile/' + $(this).val() + '/
        <?php if(isset($id) && $id)echo $id;else echo '0'?>/yes';
         }
         else
         {
         msg = '<span class="msg" style="color:red">Removed</span>';
         var url = '
        <?php echo $this->request->webroot;?>clients / assignProfile / ' + $(this).val() + ' /
        <?php if(isset($id) && $id)echo $id;else echo '0'?> / no
         ';
         }

         $.ajax({
         url: url, success: function () {
         $('.' + nameId).html(msg);
         }
         });
         })
         ;
         */
    })
    ;
</script>

<SCRIPT>
    function removeelement(id) {
        return (elem = document.getElementById(id)).parentNode.removeChild(elem);
    }
    function setinnerHTML(id, HTML) {
        document.getElementById(id).innerHTML = HTML;
    }
    // $this->request->webroot . 'clients/quickcontact?Type=emailout&user_id=' . $profile->id
    function sendemails() {
        $.ajax({
            url: '<?php echo $this->request->webroot;?>clients/quickcontact',
            data: 'Type=emailout&user_id=' + '<?php if(isset($profile->id))echo $profile->id; ?>',
            type: 'get',
            success: function (res) {
                //alert(res);
                setinnerHTML("removethis", res + ' <i class="fa fa-check"></i>');
                //removeelement("removethis");//remove the button so it only can be clicked once
            },
            failure: function (res) {
                alert("Error: " + res);
            }
        });
        return false;
    }
</SCRIPT>

<style>
    .portlet-body {
        min-height: 250px !important;
    }
</style>