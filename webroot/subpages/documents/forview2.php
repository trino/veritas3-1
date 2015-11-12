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
    }

</style>
<?php

    function get_string_between($string, $start, $end)
    {
        $string = " " . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }


    function get_mee_results_binary($bright_planet_html_binary, $document_type)
    {
        return (get_string_between(base64_decode($bright_planet_html_binary), $document_type, '</tr>'));
    }

    function get_color($result_string)
    {

        $return_color = '<span  class="label label-sm label-warning" style="float:right;padding:4px;">' . $result_string . '</span>';

        switch (strtoupper(trim($result_string))) {
            case 'NOT ATTACHED':
                echo $return_color = '<span  class="label label-sm label-danger" style="float:right;padding:4px;">' . $result_string . '</span>';;
                break;
            case 'PASS':
                echo $return_color = '<span  class="label label-sm label-success" style="float:right;padding:4px;">' . $result_string . '</span>';
                break;
            case 'DISCREPANCIES':
                echo $return_color = '<span  class="label label-sm label-warning" style="float:right;padding:4px;">' . $result_string . '</span>';
                break;
            case 'COACHING REQUIRED':
                echo $return_color = '<span  class="label label-sm label-warning" style="float:right;padding:4px;">' . $result_string . '</span>';
                break;
            case 'VERIFIED':
                echo $return_color = '<span  class="label label-sm label-success" style="float:right;padding:4px;">' . $result_string . '</span>';
                break;
            case 'POTENTIAL TO SUCCEED':
                echo $return_color = '<span  class="label label-sm label-warning" style="float:right;padding:4px;">' . $result_string . '</span>';
                break;
            case 'IDEAL CANDIDATE':
                echo $return_color = '<span  class="label label-sm label-success" style="float:right;padding:4px;">' . $result_string . '</span>';
                break;
            case 'INCOMPLETE':
                echo $return_color = '<span  class="label label-sm label-danger" style="float:right;padding:4px;">' . $result_string . '</span>';
                break;
            case 'SATISFACTORY':
                echo $return_color = '<span  class="label label-sm label-warning" style="float:right;padding:4px;">' . $result_string . '</span>';
                break;
            case 'REQUIRES ATTENTION':
                echo $return_color = '<span  class="label label-sm label-warning" style="float:right;padding:4px;">' . $result_string . '</span>';
                break;
            case '':
                // echo $return_color = '<span  class="label label-sm label-success" style="float:right;padding:4px;">' . $result_string . '</span>';
                break;
            default:
                echo $return_color = '<span  class="label label-sm label-warning" style="float:right;padding:4px;">NO COMMENT</span>';
        }
    }

    function return_link($pdi, $order_id)
    {
        if (file_exists("orders/order_" . $order_id . '/' . $pdi . '.pdf')) {
            $link = "orders/order_" . $order_id . '/' . $pdi . '.pdf';
            return $link;

        } else if (file_exists("orders/order_" . $order_id . '/' . $pdi . '.html')) {
            $link = "orders/order_" . $order_id . '/' . $pdi . '.html';
            return $link;

        } else if (file_exists("orders/order_" . $order_id . '/' . $pdi . '.txt')) {
            $link = "orders/order_" . $order_id . '/' . $pdi . '.txt';
            return $link;

        }
        return false;
    }

    function create_files_from_binary($order_id, $pdi, $binary)
    {
        $createfile_pdf = "orders/order_" . $order_id . '/' . $pdi . '.pdf';
        $createfile_html = "orders/order_" . $order_id . '/' . $pdi . 'html';
        $createfile_text = "orders/order_" . $order_id . '/' . $pdi . 'txt';

        if (!file_exists($createfile_pdf) && !file_exists($createfile_text) && !file_exists($createfile_html)) {

            if (isset($binary) && $binary != "") {
                file_put_contents('unknown_file', base64_decode($binary));
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, 'unknown_file');

                if ($mime == "application/pdf") {
                    rename("unknown_file", "orders/order_" . $order_id . '/' . $pdi . '.pdf');
                } elseif ($mime == "text/html") {
                    rename("unknown_file", "orders/order_" . $order_id . '/' . $pdi . '.html');
                } elseif ($mime == "text/plain") {
                    rename("unknown_file", "orders/order_" . $order_id . '/' . $pdi . '.html');
                } else {
                    rename("unknown_file", "orders/order_" . $order_id . '/' . $pdi . '.html');
                }
            }
        }
    }

    create_files_from_binary($order->id, '1603', $order->ebs_1603_binary);
    create_files_from_binary($order->id, '1', $order->ins_1_binary);
    create_files_from_binary($order->id, '14', $order->ins_14_binary);
    create_files_from_binary($order->id, '77', $order->ins_77_binary);
    create_files_from_binary($order->id, '78', $order->ins_78_binary);
    create_files_from_binary($order->id, '1650', $order->ebs_1650_binary);
    create_files_from_binary($order->id, '1627', $order->ebs_1627_binary);
?>

<h3 class="page-title">
    View Report
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo $this->request->webroot; ?>">Dashboard</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="">View Report
            </a>
        </li>
    </ul>
    <a href="javascript:window.print();" class="floatright btn btn-info">Print</a>
</div>
<!-- BEGIN PROFILE SIDEBAR -->
<div class="profile-sidebar">
    <!-- PORTLET MAIN -->
    <div class="portlet light profile-sidebar-portlet">
        <!-- SIDEBAR USERPIC -->
        <div class="profile-userpic">
            <center>
                <?php
                    //debug($order);
                    if (isset($order->profile->image) && $order->profile->image != "") { ?>
                        <img
                            src="<?php echo $this->request->webroot; ?>img/profile/<?php echo $order->profile->image ?>"
                            class="img-responsive" alt="" id="clientpic"/>

                    <?php } else {
                        ?>
                        <img src="<?php echo $this->request->webroot; ?>img/profile/default.png" class="img-responsive"
                             id="clientpic"
                             alt=""/>
                    <?php
                    }
                ?>
            </center>
        </div>
        <!-- END SIDEBAR USERPIC -->
        <!-- SIDEBAR USER TITLE -->
        <div class="profile-usertitle">
            <div class="profile-usertitle-name">
                <?php echo ucwords($order->profile->fname); ?>   <?php echo ucwords($order->profile->lname); ?>
            </div>
            <div class="profile-usertitle-job">
                Reference Number <?php echo ucwords($order->profile->id); ?>
            </div>




                    <!--<div class="inputs">
                        <div class="profile-usertitle-job">-->
                        <?php if($profile->profile_type == 5 || $profile->profile_type == 7 || $profile->profile_type == 8 || $profile->profile_type == 11 && $settings->mee =="MEE") { ?>
                            <label class="uniform-inline" style="margin-top:10px;">
                            <?php 
                                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                {
                                    if($order->profile->is_hired == '1')
                                    {
                                        ?>
                                        &#9745;
                                        <?php
                                    }
                                    else 
                                    {
                                        ?>
                                        &#9744;
                                        <?php
                                    } 
                                }
                                else
                                {
                                    ?>                                      
                                    <input <?php if(!$this->request->session()->read('Profile.super') && ($this->request->session()->read('Profile.profile_type') != '2')) {?> disabled="disabled" <?php }?> type="checkbox" name="stat" value="1" id="<?php echo $order->profile->id; ?>" class="checkdriver" <?php if ($order->profile->is_hired == '1') echo "checked"; ?> /> 
                                    <?php
                                }
                             ?>
                                
                                Was this applicant hired? </label>
                            <?php } ?>
                       <!-- </div>
                    </div>--></div>
        <!-- END SIDEBAR USER TITLE -->
        <!-- SIDEBAR BUTTONS -->
    </div>
    <script>
        $(function () {

            $('.checkdriver').click(function () {

                var oid = $(this).attr('id');
                if ($(this).is(":checked")) {
                    var hired = 1;
                }
                else
                    var hired = 0;

                $.ajax({
                    url: "<?php echo $this->request->webroot;?>orders/savedriver/" + oid,
                    type: 'post',
                    data: 'is_hired=' + hired,
                    success: function (msg) {
                    }
                })
            });
        });
    </script>
    <!-- END PORTLET MAIN -->
    <!-- PORTLET MAIN -->
    <div class="portlet light">




        <?php $settings = $Manager->get_settings();
            $uploaded_by = $doc_comp->getUser($order->user_id);
        ?>


        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-pencil"></i>Recruiter Notes
                </div>

            </div>
            <div class="portlet-body">

                <?php include('subpages/documents/recruiter_notes.php'); ?>
            </div>

        </div>

    </div>


    <!-- END PORTLET MAIN -->
</div>

<!-- END BEGIN PROFILE SIDEBAR -->
<!-- BEGIN PROFILE CONTENT -->
<div class="profile-content">
    <div class="row">

        <div class="clearfix"></div>
        <div class="col-md-12">
            <!-- BEGIN PORTLET -->
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase">Driver Score Sheet</span>
                        <span class="caption-helper"></span>
                    </div>
                </div>





                <div class="portlet box yellow">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-folder-open-o"></i>ISB MEE Products
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-scrollable">

                            <div class="col-sm-6" style="padding-top: 10px;">
                            <span class="profile-desc-text">   <p>  <?php echo ucfirst($settings->document); ?> type:
                                <strong>Orders</strong></p>
                			<p>Filed by: <strong><?php echo $uploaded_by->username; ?></strong></p>

                			<p>Recruiter ID # <strong><?php echo $uploaded_by->isb_id; ?></strong></p>
                			<p>Client: <strong><?php echo $order->client->company_name; ?></strong></p>

                			<p>Uploaded on: <strong><?php echo $order->created; ?></strong></p>

                			</span>

                        </div>
                        <div class="margin-bottom-20 col-sm-6" style="padding-top: 10px;text-align:right;">

                            <a href="#" class="btn btn-lg default yellow-stripe">
                                Road Test Score </a><a href="#" class="btn btn-lg yellow">
                                <i class="fa fa-bar-chart-o"></i> <?php if (isset($order->road_test[0]->total_score)) echo $order->road_test[0]->total_score; ?>
                            </a>

                        </div>

                        <div class="clearfix"></div>


                            <table class="table ">

                                <tbody>


                                <tr class="even" role="row">


                                    <td>
                                        <span class="icon-notebook"></span>

                                    </td>

                                    <td>Premium National Criminal Record Check
                                        <?php
                                            get_color(strip_tags(get_mee_results_binary($order->bright_planet_html_binary, "Premium National Criminal Record Check")));
                                        ?>

                                    </td>

                                    <td class="actions">
                                        <?php
                                            $createfile = APP . "../webroot/orders/order_" . $order->id . '/1603.pdf';
                                            if (return_link('1603', $order->id) == false) { ?>
                                                <span class="label label label-info">Pending </span>
                                            <? } else { ?>
                                                <a target="_blank"
                                                   href="<? echo $this->request->webroot . return_link('1603', $order->id); ?>"
                                                   class="btn btn-primary dl">Download</a>
                                            <? } ?>
                                    </td>
                                </tr>


                                <tr class="even" role="row">
                                    <td>
                                        <span class="icon-notebook"></span>

                                    </td>
                                    <td>Driver's Record Abstract
                                        <?php
                                            get_color(strip_tags(get_mee_results_binary($order->bright_planet_html_binary, "Driver's Record Abstract")));
                                        ?>
                                    </td>

                                    <td class="actions">
                                        <?php
                                            if (return_link('1', $order->id) == false) { ?>
                                                <span class="label label label-info">Pending </span>
                                            <? } else { ?>
                                                <a target="_blank"
                                                   href="<? echo $this->request->webroot . return_link('1', $order->id); ?>"
                                                   class="btn btn-primary">Download</a>
                                            <? } ?>

                                    </td>
                                </tr>

                                <tr class="even" role="row">
                                    <td>
                                        <span class="icon-notebook"></span>

                                    </td>
                                    <td>CVOR
                                        <?php get_color(strip_tags(get_mee_results_binary($order->bright_planet_html_binary, "CVOR"))); ?>
                                    </td>

                                    <td class="actions">
                                        <?php
                                            if (return_link('14', $order->id) == false) { ?>
                                                <span class="label label label-info">Pending </span>
                                            <? } else { ?>
                                                <a target="_blank"
                                                   href="<? echo $this->request->webroot . return_link('14', $order->id); ?>"
                                                   class="btn btn-primary">Download</a>
                                            <? } ?>
                                    </td>
                                </tr>


                                <tr class="odd" role="row">

                                    <td>
                                        <span class="icon-notebook"></span>

                                    </td>
                                    <td>Pre-employment Screening Program Report

                                        <?php
                                            get_color(strip_tags(get_mee_results_binary($order->bright_planet_html_binary, "Pre-employment Screening Program Report")));
                                        ?>

                                    </td>

                                    <td class="actions">
                                        <?php
                                            if (return_link('77', $order->id) == false) { ?>
                                                <span class="label label label-info">Pending </span>
                                            <? } else { ?>
                                                <a target="_blank"
                                                   href="<? echo $this->request->webroot . return_link('77', $order->id); ?>"
                                                   class="btn btn-primary">Download</a>
                                            <? } ?>

                                    </td>
                                </tr>


                                <tr class="even" role="row">

                                    <td>
                                        <span class="icon-notebook"></span>

                                    </td>
                                    <td>Transclick

                                        <?php
                                            get_color(strip_tags(get_mee_results_binary($order->bright_planet_html_binary, "TransClick")));
                                        ?>
                                    </td>

                                    <td class="actions">
                                        <?php
                                            if (return_link('78', $order->id) == false) { ?>
                                                <span class="label label label-info">Pending </span>
                                            <? } else { ?>
                                                <a target="_blank"
                                                   href="<? echo $this->request->webroot . return_link('78', $order->id); ?>"
                                                   class="btn btn-primary">Download</a>
                                            <? } ?>

                                    </td>
                                </tr>


                                <tr class="odd" role="row">
                                    <td>
                                        <span class="icon-notebook"></span>

                                    </td>
                                    <td>Certifications
                                        <?php
                                            get_color(strip_tags(get_mee_results_binary($order->bright_planet_html_binary, "Certifications")));
                                        ?>
                                    </td>

                                    <td class="actions">
                                        <?php
                                            if (return_link('1650', $order->id) == false) { ?>
                                                <span class="label label label-info">Pending </span>
                                            <? } else { ?>
                                                <a target="_blank"
                                                   href="<? echo $this->request->webroot . return_link('1650', $order->id); ?>"
                                                   class="btn btn-primary">Download</a>
                                            <? } ?>
                                    </td>
                                </tr>

                                <tr class="odd" role="row">

                                    <td>
                                        <span class="icon-notebook"></span>

                                    </td>
                                    <td>Letter of Experience

                                        <?php
                                            get_color(strip_tags(get_mee_results_binary($order->bright_planet_html_binary, "Letter Of Experience")));
                                        ?>
                                    </td>
                                    <td class="actions">
                                        <?php
                                            if (return_link('1627', $order->id) == false) { ?>
                                                <span class="label label label-info">Pending </span>
                                            <? } else { ?>
                                                <a target="_blank"
                                                   href="<? echo $this->request->webroot . return_link('1627', $order->id); ?>"
                                                   class="btn btn-primary">Download</a>
                                            <? } ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <div class="col-md-12">
                                    <!-- BEGIN PORTLET -->
                                    <div class="portlet light tasks-widget">
                                        <div class="portlet-title">
                                            <div class="caption caption-md">
                                                <i class="icon-bar-chart theme-font hide"></i>
                                                <span
                                                    class="caption-subject font-blue-madison bold uppercase"><?php echo ucfirst($settings->document); ?>
                                                    Check-list</span>
                                                <span class="caption-helper"></span>
                                            </div>
                                            <div class="inputs">
                                                <div class="portlet-input input-small input-inline">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="task-content">
                                                <div class="slimScrollDiv"
                                                     style="position: relative; overflow: hidden; width: auto; height: 282px;">
                                                    <div class="scroller" style="height: 282px; overflow: hidden; width: auto;"
                                                         data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2"
                                                         data-initialized="1">
                                                        <!-- START TASK LIST -->
                                                        <ul class="task-list">
                                                            <li>
                                                                <!--<div class="task-checkbox">
                                                                    <input type="hidden" value="1" name="test">

                                                                    <div class="checker"><span><input type="checkbox" class="liChild" value="2"
                                                                                                      name="test"></span></div>
                                                                </div>-->
                                                                <div class="task-title">
                        															<span class="task-title-sp">
                        														<span class="icon-notebook"></span>	Pre-screening form
                         </span>                                    <?php $cnt = $this->requestAction("/orders/getprocessed/pre_screening/" . $order->id); ?>
                                                                    <?php if ($cnt > 0) { ?>
                                                                        <span style="float:right;padding:5px"
                                                                              class="label label-sm label-success">Submitted</span>
                                                                        &#x2713;
                                                                    <?php } else {
                                                                        ?>
                                                                        <span style="float:right;padding:5px"
                                                                              class="label label-sm label-danger">Skipped</span>

                                                                    <?php
                                                                    } ?>


                                                                </div>

                                                            </li>
                                                            <li>
                                                                <!--<div class="task-checkbox">
                                                                    <div class="checker"><span><input type="checkbox" class="liChild" value=""></span>
                                                                    </div>
                                                                </div>-->
                                                                <div class="task-title">
                        											<span class="task-title-sp">
                        											<span class="icon-notebook"></span> Driver Application	 </span>
                                                                    <?php $cnt = $this->requestAction("/orders/getprocessed/driver_application/" . $order->id); ?>
                                                                    <?php if ($cnt > 0) { ?>
                                                                        <span style="float:right;padding:5px"
                                                                              class="label label-sm label-success">Submitted</span>
                                                                        &#x2713;
                                                                    <?php } else {
                                                                        ?>
                                                                        <span style="float:right;padding:5px"
                                                                              class="label label-sm label-danger">Skipped</span>

                                                                    <?php
                                                                    } ?>

                                                                </div>

                                                            </li>
                                                            <li>
                                                                <!--<div class="task-checkbox">
                                                                    <div class="checker"><span><input type="checkbox" class="liChild" value=""></span>
                                                                    </div>
                                                                </div>-->
                                                                <div class="task-title">
                        											<span class="task-title-sp">
                                                                        <span class="icon-notebook"></span> Road Test
                                                                    </span>
                                                                    <?php $cnt = $this->requestAction("/orders/getprocessed/road_test/" . $order->id); ?>
                                                                    <?php if ($cnt > 0) { ?>
                                                                        <span style="float:right;padding:5px"
                                                                              class="label label-sm label-success">Submitted</span>
                                                                        &#x2713;
                                                                    <?php } else {
                                                                        ?>
                                                                        <span style="float:right;padding:5px"
                                                                              class="label label-sm label-danger">Skipped</span>

                                                                    <?php
                                                                    } ?>
                                                                </div>

                                                            </li>
                                                            <li>
                                                                <!--<div class="task-checkbox">
                                                                    <div class="checker"><span><input type="checkbox" class="liChild" value=""></span>
                                                                    </div>
                                                                </div>-->
                                                                <div class="task-title">
                        											<span class="task-title-sp">

                                                                       <span class="icon-notebook"></span>  Consent Form	 </span>
                                                                    <?php $cnt = $this->requestAction("/orders/getprocessed/consent_form/" . $order->id); ?>
                                                                    <?php if ($cnt > 0) { ?>
                                                                        <span style="float:right;padding:5px"
                                                                              class="label label-sm label-success">Submitted</span>
                                                                        &#x2713;
                                                                    <?php } else {
                                                                        ?>
                                                                        <span style="float:right;padding:5px"
                                                                              class="label label-sm label-danger">Skipped</span>

                                                                    <?php
                                                                    } ?>

                                                                </div>

                                                            </li>
                                                            <li>
                                                                <!--<div class="task-checkbox">
                                                                    <div class="checker"><span><input type="checkbox" class="liChild" value=""></span>
                                                                    </div>
                                                                </div>-->
                                                                <div class="task-title">
                        											<span class="task-title-sp">
                                                                  <span class="icon-notebook"></span>  Confirmation  </span>
                                                                    <?php if ($order->draft == 0) { ?>
                                                                        <span style="float:right;padding:5px"
                                                                              class="label label-sm label-success">Submitted</span>
                                                                        &#x2713;
                                                                    <?php } else {
                                                                        ?>
                                                                        <span style="float:right;padding:5px"
                                                                              class="label label-sm label-danger">Skipped</span>

                                                                    <?php
                                                                    } ?>

                                                                </div>

                                                            </li>

                                                        </ul>
                                                        <!-- END START TASK LIST -->
                                                    </div>
                                                    <div class="slimScrollBar"
                                                         style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 227.211428571429px; background: rgb(215, 220, 226);"></div>
                                                    <div class="slimScrollRail"
                                                         style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(234, 234, 234);"></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- END PORTLET -->
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PORTLET -->
        </div>
    </div>

</div>
<!-- END PROFILE CONTENT -->
