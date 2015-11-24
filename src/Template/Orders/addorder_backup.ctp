<?php
use Cake\ORM\TableRegistry;
include_once 'subpages/filelist.php';
$param = $this->request->params['action'];
include_once('subpages/api.php');

$view = 'nope';
$debugging=isset($_GET["debug"]);

if($this->request->params['action'] == 'vieworder'){$view = 'view';}
$action = ucfirst($param);
if ($action == "Vieworder") { $action = "View";}
if ($action == "Addorder") {
    $action = "Create" ;
    if ($did>0){ $action = "Edit";}
}
if (isset($this->request->params['pass'][0])) {
    $ClientID = $this->request->params['pass'][0];
}
$doc_ext = array('pdf', 'doc', 'docx', 'txt', 'csv', 'xls', 'xlsx');
$img_ext = array('jpg', 'jpeg', 'png', 'bmp', 'gif');
if($did) {
    $_GET['driver'] = $ooo->uploaded_for;
}

$is_disabled = '';
if (isset($disabled)){ $is_disabled = 'disabled="disabled"';}
$settings = $Manager->get_settings();
$language = $this->request->session()->read('Profile.language');
$strings = CacheTranslations($language, array("orders_%", "forms_%", "documents_%", "profiles_null", "clients_addeditimage", "addorder_%"), $settings);
if($language=="Debug"){$Trans = " [Trans]";} else {$Trans = "";}
$title = $strings["orders_" . strtolower($action)];
//<script src="<?php echo $this->request->webroot;  js/jquery.easyui.min.js" type="text/javascript"></script>
//<script src="<?php echo $this->request->webroot;  js/ajaxupload.js" type="text/javascript"></script>
//includejavascript($strings);
JSinclude($this,"js/jquery.easyui.min.js");
JSinclude($this,"js/ajaxupload.js");
printCSS($this);
    ?>
<style>.allattach{display:none;}</style>

<script>
    document.onmousedown  = myClickListener;
    var eventIsFiredFromElement = "";
    function myClickListener(e){
        if(e==null){
            eventIsFiredFromElement = event.srcElement.innerHTML;//IE
        } else {
            eventIsFiredFromElement = e.target.innerHTML;//firefox
        }
    }

    var readTOS = '<?= addslashes($strings["forms_pleaseconfirm"]); ?>';
    var giveSIG = '<?= addslashes($strings["forms_signplease"]); ?>';
    var fillALL = '<?= addslashes($strings["forms_fillall"]); ?>';

    <?php loadreasons($action, $strings); ?>

</script>

<input type="hidden" id="tablename" value="<?php echo $table; ?>"/>

<h3 class="page-title">
    <?= $title;?>
</h3>
<input type="hidden" id="dr" value="<?php if (isset($dr)) echo $dr; ?>"/>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo $this->request->webroot; ?>"><?= $strings["dashboard_dashboard"]; ?></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="">
                <?= $title; ?>
            </a>
        </li>
    </ul>
    <?php

    $forms=array();
    if (isset($_GET["forms"])){
        $forms = explode(",", $_GET["forms"]);
    } else {
        $forms = $this->requestAction('/orders/getProNum');
    }

    //returns: boolean, if this form should be displayed
    //parameters:
    //  $forms  -   pass in the $forms variable since globals don't seem to work
    //  $id     -   the ID/index number of the form to check
    function isallone($forms){
        if(count($forms)<7) {
            return false;
        } else if(count($forms)=='7' && urldecode($_GET['order_type'])!='Order MEE') {
            return false;
        } else {
            return true;
        }
    }
    $_this = $this;

    $DriverProvince = "AB";
    $DriverID = $_GET["driver"];
    $UserID= $this->request->session()->read('Profile.id');
    if ($DriverID>0 && is_object($p)){
        $DriverProvince = $p->driver_province;
    }

    $enableddocs= TableRegistry::get('Profilessubdocument')->find('all')->where(['profile_id'=>$UserID]);
    foreach($thedocuments as $Key => $Value){//$thedocuments
        $userinfo = FindIterator($enableddocs, "subdoc_id", $Value["ID"]);
        $thedocuments[$Key]["Display"] = 0;
        if($userinfo) { $thedocuments[$Key]["Display"] = $userinfo->display;}
    }

    echo "<SCRIPT>var DriverProvince = '" . $DriverProvince . "';</SCRIPT>";
    if($theproduct->doc_ids && $theproduct->Bypass==0){
        $forms = explode(",", $theproduct->doc_ids);
        $theproduct->BypassForms = array();
        foreach($forms as $form){
            $theproduct->BypassForms[strtolower(getForm($form)->title)] = true;
        }
    }

    if($debugging){
        echo "<BR>The Product:";
        debug($theproduct);
    }

    function getForm($ID){
        $table = TableRegistry::get('subdocuments')->find();
        return $table->select()->where(['id' => $ID])->first();
    }

    function displayform2($DriverProvince, $thedocuments, $name, $theproduct,$did=0,$_this){
        $name = strtolower($name);
        $debugmode = isset($_GET["debug"]);
        if($did) {
            //$checker = $_this->requestAction('/orders/checkPermisssionOrder/'.$did.'/'.$_GET['driver']);
            //if(!$checker)
            //return false; //code does not work properly
            if ($thedocuments[$name]["Display"] == 0){return false;}//checks order taker's profile setting
        }
        if(isset($_GET['order_type'])) {
            switch ($theproduct->Acronym){
                //case "SIN":
                //    return $name == strtolower($_GET["SpecificForm"]);
                case "MEE":
                    if($debugmode){return "Is MEE";}
                    return true;
                    break;
                case "GEM":
                    if ($name == "challenger road test"){ return false;}
                    break;
            }
        }
        if(isset($theproduct->BypassForms)){
            return isset($theproduct->BypassForms[$name]);
        }
        if(isset($_GET["debug"])){
            debug($thedocuments);
        }
        //echo "Testing: " . $name . " '" . isset($thedocuments[$name][$DriverProvince]) . "'"; debug($thedocuments);
        //echo "<BR>" . $DriverProvince . " " . $name . " <BR>"; print_r($thedocuments[$name]);
        return isset($thedocuments[$name][$DriverProvince]);
    }

    if (isset($disabled)) { ?>
        <a href="javascript:window.print();" class="floatright btn btn-primary"><?= $strings["dashboard_print"]; ?></a>

        <!--a href="" class="floatright btn btn-success">Re-Qualify</a>
        <a href="" class="floatright btn btn-primary">Add to Task List</a-->
    <?php }

    echo '</div>';

    if($p->iscomplete){
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue" id="form_wizard_1">
            <div class="portlet-title">
                <?php
                $param = $this->request->params['action'];
                if(strtolower($param) == 'vieworder') {
                    echo '<input type="hidden" id="viewingorder" value="1" />';
                } else {
                    echo '<input type="hidden" id="viewingorder" value="0" />';
                }
                $tab = 'nodisplay';
                ?>
                <div class="caption">
                    <i class="fa fa-clipboard"></i><?= $strings ["addorder_orderforms"]; ?>
                </div>
            </div>
            <div class="portlet-body form">
                <!--<form action="#" class="form-horizontal" id="submit_form" method="POST"> -->
                <div class="form-wizard">
                    <div class="form-body" style="position: relative;">
                        <?php

                        if ($param != 'view') {
                            $tab = 'tab-pane';
                            $i = 1;
                            ?>

                            <ul class="nav nav-pills nav-justified steps">
                                <?php if ($DriverID>0){
                                    $i++;?>
                                    <li>
                                        <a href="#tab1" data-toggle="tab" class="step">
												<span class="number">
												1</span><br/>
												<span class="desc">
												<i class="fa fa-check"  align="center"></i> <?= $strings["profiles_null"]; ?> </span>
                                        </a>
                                    </li>
                                <?php }?>
                                <!--<li>
                                   <a href="#tab2" data-toggle="tab" class="step">
                                           <span class="number">
                                           2</span><br/>
                                           <span class="desc">
                                           <i class="fa fa-check"></i> Products </span>
                                   </a>
                               </li>-->
                                <?php

                                $doc = $doc_comp->getDocument('orders');
                                $doc_ids = $this->requestAction('/clients/orders_doc/'.$cid.'/'.$_GET['order_type']);
                                if(is_iterable($doc_ids)) {
                                    $subdoccli = $doc_ids;
                                    if($debugging) {echo "Source: orders_doc";}
                                } else {
                                    $subdoccli = $this->requestAction('/clients/getSubCli2/' . $cid);
                                    if($debugging) {echo "Source: getSubCli2";}
                                }
                                $subdoccli2 = $subdoccli;
                                $doc2 = $doc;
                                $end = 0;
                                $k_c=0;
                                $index=0;
                                //client permissions
                                //http://localhost/veritas3-0/clients/edit/1
                                //user permissions
                                //http://localhost/veritas3-0/profiles/edit/118
                                //product settings
                                //http://localhost/veritas3-0/profiles/settings
                                $Fieldname = getFieldname("title", $language);

                                foreach ($subdoccli as $sd) {
                                    $index+=1;
                                    $d = $this->requestAction('/clients/getFirstSub/'.$sd->sub_id);

                                    if($debugging) {
                                        echo "<BR>Displayform: " . displayform2($DriverProvince, $thedocuments, $d->title, $theproduct,$d->id,$_this);
                                        $thedocuments[strtolower($d->title)]["IsSet"] = true;
                                        debug($d);
                                    }

                                    if (displayform2($DriverProvince,$thedocuments,$d->title, $theproduct,$d->id,$_this)){//(displayform($DriverProvince, $provinces, $forms, $d->title,$_this)){

                                        $index+=1;
                                        $act = 0;
                                        if ($d->table_name == $table) {
                                            $act = 1;
                                            $end = 1;
                                        }

                                        $prosubdoc = $this->requestAction('/settings/all_settings/0/0/profile/' . $this->Session->read('Profile.id') . '/' . $d->id);

                                        if (true){ //($prosubdoc['display'] != 0 && $d->display == 1) {

                                            $k_c++;
                                            $j = $d->id;
                                            $j = $j + 1;
                                            if($k_c==1) {
                                                $k_cou = $j;
                                            } else if($k_cou<$j) {
                                                $k_cou=$j;
                                            }
                                            ?>
                                            <li <?php if ($table && $end == 0) echo "class = 'done'";
                                            if ($act == 1) {
                                                echo 'class="active"';
                                            } ?>>
                                                <a href="#tab<?php echo $j; ?>" data-toggle="tab" class="step">
                                                        <span class="number">
                                                        <?php echo $i; ?> </span><br/>
                                                        <span class="desc">
                                                        <i class="fa fa-check"></i> <?php echo ucfirst($d->$Fieldname).ucfirst($d->$Fieldname) . $Trans; ?> </span>
                                                </a>
                                            </li>
                                            <?php

                                            $i++;
                                        }}
                                }
                                if($debugging) { debug($thedocuments);}
                                if(!isset($k_cou)){ $k_cou = 1; }
                                ?>

                                <li>
                                    <a href="#tab100000x" data-toggle="tab" class="step confirmations">
												<span class="number">
												<?php echo $i++;?></span><br/>
												<span class="desc">
												<i class="fa fa-check"></i> <?= $strings["orders_confirmation"]; ?> </span>
                                    </a>
                                </li>
                                <li style="display: none;">
                                    <a href="#tab100001x" data-toggle="tab" class="step">
												<span class="number">
												<?php echo $i++;?></span><br/>
												<span class="desc">
												<i class="fa fa-check"></i> <?= $strings["orders_success"]; ?> </span>
                                    </a>
                                </li>

                            </ul>
                            <div id="bar" class="progress progress-striped" role="progressbar">
                                <div class="progress-bar progress-bar-info">
                                </div>
                            </div>
                            <div
                                style="
  opacity:0.5;
    background-color:#dadada;
                                    position:fixed;
    width:100%;
    height:100%;
    top:0px;
    left:0px;
    z-index:1000;
    display:none;"
                                id="loading5">
                                <center><br/>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <strong style="color: #111;font-size: 36px;"><?= $strings["addorder_pleasewait"]; ?></strong>
                                    <br/><br/>

                                    <img
                                        src="<?php echo $this->request->webroot;?>assets/admin/layout/img/ajax-loading.gif"/>
                                </center>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="form-actions <?php if ($tab == 'nodisplay') echo $tab; ?>">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <a href="javascript:;" class="btn default button-previous"
                                       onclick="$('#skip').val('0');">
                                        <i class="m-icon-swapleft"></i> <?= $strings["addorder_back"]; ?> </a>

                                    <a href="javascript:;" class="btn red button-next skip cont"
                                       onclick="$('#skip').val('1');">
                                        <?= $strings["addorder_skip"]; ?> <i class="m-icon-swapdown m-icon-white"></i>
                                    </a>

                                    <input type="hidden" id="skip" value="0"/>
                                    <a href="javascript:;" class="btn blue button-next cont"
                                       onclick="$('#skip').val('0');">
                                        <?= $strings["addorder_savecontinue"]; ?> <i class="m-icon-swapright m-icon-white"></i>
                                    </a>


                                    <a href="javascript:window.print();" class="btn btn-primary button-submit"
                                       onclick="$('#skip').val('0');"><?= $strings["dashboard_print"]; ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content mar">

                            <div class="alert alert-danger display-none">
                                <button class="close" data-dismiss="alert"></button>
                                <?= $strings["addorder_errors"]; ?>
                            </div>
                            <div class="alert alert-success display-none">
                                <button class="close" data-dismiss="alert"></button>
                                <?= $strings["addorder_success"]; ?>
                            </div>

                            <div class="form-group col-md-12 uploaded_for">
                                <input type="hidden" name="client_id" value="<?php echo $cid; ?>" id="client_id"/>
                                <input type="hidden" name="did" value="<?php echo $did; ?>" id="did"/>
                                <input type="hidden" name="uploaded_for" id="uploaded_for"
                                       value="<?php if (isset($modal) && $modal) echo $modal->uploaded_for;else{if(isset($_GET['driver']) && is_numeric($_GET['driver']))echo $_GET['driver'];} ?>"/>
                                <input type="hidden" id="division" value="<?php if(isset($_GET['division']))echo $_GET['division'];?>" />
                                <?php
                                if (!$did) {
                                    ?>
                                    <input type="hidden" name="user_id"
                                           value="<?= $this->request->session()->read('Profile.id');?>"
                                           id="user_id"/>

                                <?php
                                } else {
                                    ?>
                                    <input type="hidden" name="user_id"
                                           value="<?php if (isset($modal) && $modal) echo $modal->user_id;?>"
                                           id="user_id"/>
                                <?php
                                }
                                ?>

                            </div>
                            <?php $division = $this->requestAction("clients/getdivision/" . $cid);
                            if (count($division) > 0){

                                ?>
                                <input type="hidden" id="check_div" value="1"/>

                            <?php
                            }
                            else {
                                ?>
                                <input type="hidden" id="check_div" value="0"/>
                            <?php
                            }
                            ?>
                            <div class="clearfix"></div>

                            <?php if($DriverID>0){?>
                                <div class="tabber <?php echo $tab; ?> <?php if (!($table)) {
                                    if ($tab == 'tab-pane') { ?>active<?php }
                                } else {
                                    if ($table == $d->table_name) { ?>active changeactive<?php }
                                } ?>" id="tab1">
                                    <?php
                                    include('subpages/profile/info_order.php');
                                    ?>
                                </div>
                            <?php } ?>

                            <!--<div class="tabber <?php echo $tab; ?>"  id="tab2">
                            <?php //include('subpages/documents/products.php'); ?>
                        </div>-->

                            <?php
                            $k_c = 0;
                            if(!isset($show_all2)) {
                                $show_all2='all';
                            }

                            foreach ($subdoccli as $sd) {
                                $d = $this->requestAction('/clients/getFirstSub/'.$sd->sub_id);
                                $dx = $this->requestAction('/orders/getSubDetail/'.$sd->sub_id);
                                // debug($d);

                                if (displayform2($DriverProvince,$thedocuments,$d->title, $theproduct,$d->id,$_this)){
                                    //if (displayform($DriverProvince, $provinces, $forms, $d->title,$_this)){
                                    $prosubdoc = $this->requestAction('/settings/all_settings/0/0/profile/' . $this->Session->read('Profile.id') . '/' . $d->id);
                                    if (true){ //($prosubdoc['display'] != 0 && $d->display == 1) {
                                        $k_c++;

                                        $tab_count = $d->id + 1;
                                        if($k_c==1 || $k_co<$tab_count) {
                                            $k_co = $tab_count;
                                        }
                                        ?>
                                        <div class="tabber <?= $tab; ?>" id="tab<?php echo $tab_count; ?>">
                                            <?php
                                            if ($action == "View") {
                                                $DocID = $Manager->get_document_id($did, $d->id);
                                                printdocumentinfo($DocID);
                                            }
                                            include('subpages/documents/' . $d->form); ?>
                                        </div>
                                    <?php }}}
                            if(!isset($k_co)) {$k_co=1;} ?>

                            <div class="tabber <?php echo $tab; ?> confirmations2" id="tab100000x">
                                <?php include('subpages/documents/confirmation.php'); ?>
                            </div>
                            <div class="tabber <?php echo $tab; ?>" id="tab100001x">
                                <?php include('subpages/documents/success.php'); //include('subpages/documents/forview.php'); ?>
                            </div>
                            <?php if ($tab == 'nodisplay') { // For view action only ?>

                                <div class="forview <?php if ($tab == 'tab-pane') echo 'nodisplay';?>">
                                    <?php include('subpages/documents/forview.php');?>
                                </div>

                            <?php } ?>

                        </div>
                    </div>
                    <div class="form-actions <?php if ($tab == 'nodisplay') echo $tab; ?>" id="bottomact">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <a href="javascript:;" class="btn default button-previous" onclick="$('#skip').val('0');">
                                    <i class="m-icon-swapleft"></i> <?= $strings["addorder_back"]; ?> </a>

                                <a href="javascript:;" class="btn red button-next skip cont" onclick="$('#skip').val('1');">
                                    <?= $strings["addorder_skip"]; ?> <i class="m-icon-swapdown m-icon-white"></i>
                                </a>
                                <!--<a href="javascript:;" class="btn red skip" id="submit_dra"
                                           onclick="$('#skip').val('1');" style="display: inline-block;">
                                            Save as draft <i class="m-icon-swapdown m-icon-white"></i>
                                        </a>-->

                                <a href="javascript:;" class="btn blue button-next cont" onclick="$('#skip').val('0');">
                                    <?= $strings["addorder_savecontinue"]; ?> <i class="m-icon-swapright m-icon-white"></i>
                                </a>

                                <a href="javascript:window.print();" class="btn btn-primary button-submit"><?= $strings["dashboard_print"]; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--</form> -->
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script>
    client_id = '<?=$cid?>';
    doc_id = '<?=$did?>';
    profile_id = '<?= $_GET["driver"] ?>';
    if (doc_id) {
        doc_id = parseInt(doc_id);
    }
    if (!doc_id) {
        $('#uploaded_for').change(function () {
            if ($(this).val())
                $('.select2-choice').removeAttr('style');
            showforms('company_pre_screen_question.php');
            showforms('driver_application.php');
            showforms('driver_evaluation_form.php');
            showforms('document_tab_3.php');
        });
    }
    <?php if($did) { ?>
        showforms('company_pre_screen_question.php');
        showforms('driver_application.php');
        showforms('driver_evaluation_form.php');
        showforms('document_tab_3.php');
    <?php } ?>
    //showforms(doc_type);
    function showforms(form_type) {
        //alert(form_type);
        if (form_type != "") {
            $('.subform').load('<?php echo $this->request->webroot;?>documents/subpages/' + form_type);

            var url = '<?php echo $this->request->webroot;?>orders/getOrderData/' + client_id + '/' + doc_id + '/' + profile_id,
                param = {form_type: form_type};
            $.getJSON(url, param, function (res) {
                if (form_type == "company_pre_screen_question.php") {

                    if (res) {
                        $('#form_tab1').form('load', res);


                        if (res.legal_eligible_work_cananda == 1) {
                            // debugger;
                            jQuery('#legal_eligible_work_cananda_1').closest('span').addClass('checked');
                            // document.getElementById("legal_eligible_work_cananda_1").checked = true;
                        } else if (res.legal_eligible_work_cananda == 0) {
                            $('#form_tab1').find('#legal_eligible_work_cananda_0').closest('span').addClass('checked')
                        }

                        if (res.hold_current_canadian_pp == 1) {
                            $('#form_tab1').find('#hold_current_canadian_pp_1').closest('span').addClass('checked')
                        } else if (res.hold_current_canadian_pp == 0) {
                            $('#form_tab1').find('#hold_current_canadian_pp_0').closest('span').addClass('checked')

                        }

                        if (res.have_pr_us_visa == 1) {
                            $('#form_tab1').find('#have_pr_us_visa_1').closest('span').addClass('checked')
                        } else if (res.have_pr_us_visa == 0) {
                            $('#form_tab1').find('#have_pr_us_visa_0').closest('span').addClass('checked')

                        }

                        if (res.fast_card == 1) {
                            $('#form_tab1').find('#fast_card_1').closest('span').addClass('checked')
                        } else if (res.fast_card == 0) {
                            $('#form_tab1').find('#fast_card_0').closest('span').addClass('checked')

                        }

                        if (res.criminal_offence_pardon_not_granted == 1) {
                            $('#form_tab1').find('#criminal_offence_pardon_not_granted_1').closest('span').addClass('checked')
                        } else if (res.criminal_offence_pardon_not_granted == 0) {
                            $('#form_tab1').find('#criminal_offence_pardon_not_granted_0').closest('span').addClass('checked')

                        }

                        if (res.reefer_load == 1) {
                            $('#form_tab1').find('#reefer_load_1').closest('span').addClass('checked')
                        } else if (res.reefer_load == 0) {
                            $('#form_tab1').find('#reefer_load_0').closest('span').addClass('checked')

                        }
                    }

                    var prof_id = $('#uploaded_for').val();
                    if (prof_id) {
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>profiles/getProfileById/' + prof_id + '/1',
                            success: function (res2) {

                                if (res2) {

                                    var response = JSON.parse(res2);
                                    //alert(res2);

                                    var app_name = res2.replace('{"applicant_phone_number":"', '');
                                    var app_name = app_name.replace('","aplicant_name":"', ',');
                                    var app_name = app_name.replace('","applicant_email":"', ',');
                                    var app_name = app_name.replace('"}', '');
                                    var app_name_arr = app_name.split(',');
                                    app_name = app_name_arr[1];
                                    //app_name = app_name.replace('","applicant_email":"ttt@ttt.com"}','');
                                    $('#conf_driver_name').val(app_name);
                                    $('#conf_driver_name').attr('disabled', 'disabled');
                                    $('#form_tab1').find(':input').each(function () {
                                        var name_attr = $(this).attr('name');

                                        //alert(name_attr);
                                        if (response[name_attr]) {

                                            $(this).val(response[name_attr]);

                                            $(this).attr('disabled', 'disabled');

                                        }
                                    });
                                }

                            }
                        });
                    }


                    //$('input[type="radio"]').buttonset("refresh");
                    // end pre screening
                } else if (form_type == "driver_application.php") {

                    if (res) {
                        $('#form_tab2').form('load', res);

                        if (res.worked_for_client == 1) {
                            jQuery('#form_tab2').find('#worked_for_client_1').closest('span').addClass('checked')
                        } else if (res.worked_for_client == 0) {
                            $('#form_tab2').find('#worked_for_client_0').closest('span').addClass('checked')
                        }

                        if (res.confirm_check == 1) {
                            jQuery('#form_tab2').find('#confirm_check').closest('span').addClass('checked')
                        }

                        if (res.is_employed == 1) {
                            jQuery('#form_tab2').find('#is_employed_1').closest('span').addClass('checked')
                        } else if (res.is_employed == 0) {
                            $('#form_tab2').find('#is_employed_0').closest('span').addClass('checked')
                        }

                        if (res.age_21 == 1) {
                            $('#form_tab2').find('#age_21_1').closest('span').addClass('checked')
                        } else if (res.age_21 == 0) {
                            $('#form_tab2').find('#age_21_0').closest('span').addClass('checked')

                        }

                        if (res.proof_of_age == 1) {
                            $('#form_tab2').find('#proof_of_age_1').closest('span').addClass('checked')
                        } else if (res.proof_of_age == 0) {
                            $('#form_tab2').find('#proof_of_age_0').closest('span').addClass('checked')

                        }

                        if (res.proof_of_age == 1) {
                            $('#form_tab2').find('#proof_of_age_1').closest('span').addClass('checked')
                        } else if (res.proof_of_age == 0) {
                            $('#form_tab2').find('#proof_of_age_0').closest('span').addClass('checked')

                        }

                        if (res.convicted_criminal == 1) {
                            $('#form_tab2').find('#convicted_criminal_1').closest('span').addClass('checked')
                        } else if (res.convicted_criminal == 0) {
                            $('#form_tab2').find('#convicted_criminal_0').closest('span').addClass('checked')

                        }

                        if (res.denied_entry_us == 1) {
                            $('#form_tab2').find('#denied_entry_us_1').closest('span').addClass('checked')
                        } else if (res.denied_entry_us == 0) {
                            $('#form_tab2').find('#denied_entry_us_0').closest('span').addClass('checked')

                        }

                        if (res.denied_entry_us == 1) {
                            $('#form_tab2').find('#denied_entry_us_1').closest('span').addClass('checked')
                        } else if (res.denied_entry_us == 0) {
                            $('#form_tab2').find('#denied_entry_us_0').closest('span').addClass('checked')

                        }

                        if (res.positive_controlled_substance == 1) {
                            $('#form_tab2').find('#positive_controlled_substance_1').closest('span').addClass('checked')
                        } else if (res.positive_controlled_substance == 0) {
                            $('#form_tab2').find('#positive_controlled_substance_0').closest('span').addClass('checked')

                        }

                        if (res.refuse_drug_test == 1) {
                            $('#form_tab2').find('#refuse_drug_test_1').closest('span').addClass('checked')
                        } else if (res.refuse_drug_test == 0) {
                            $('#form_tab2').find('#refuse_drug_test_0').closest('span').addClass('checked')

                        }

                        if (res.breath_alcohol == 1) {
                            $('#form_tab2').find('#breath_alcohol_1').closest('span').addClass('checked')
                        } else if (res.breath_alcohol == 0) {
                            $('#form_tab2').find('#breath_alcohol_0').closest('span').addClass('checked')

                        }

                        if (res.fast_card == 1) {
                            $('#form_tab2').find('#fast_card_1').closest('span').addClass('checked')
                        } else if (res.fast_card == 0) {
                            $('#form_tab2').find('#fast_card_0').closest('span').addClass('checked')

                        }

                        if (res.not_able_perform_function_position == 1) {
                            $('#form_tab2').find('#not_able_perform_function_position_1').closest('span').addClass('checked')
                        } else if (res.not_able_perform_function_position == 0) {
                            $('#form_tab2').find('#not_able_perform_function_position_0').closest('span').addClass('checked')

                        }

                        if (res.physical_capable_heavy_manual_work == 1) {
                            $('#form_tab2').find('#physical_capable_heavy_manual_work_1').closest('span').addClass('checked')
                        } else if (res.physical_capable_heavy_manual_work == 0) {
                            $('#form_tab2').find('#physical_capable_heavy_manual_work_0').closest('span').addClass('checked')

                        }

                        if (res.injured_on_job == 1) {
                            $('#form_tab2').find('#injured_on_job_1').closest('span').addClass('checked')
                        } else if (res.injured_on_job == 0) {
                            $('#form_tab2').find('#injured_on_job_0').closest('span').addClass('checked')

                        }

                        if (res.willing_physical_examination == 1) {
                            $('#form_tab2').find('#willing_physical_examination_1').closest('span').addClass('checked')
                        } else if (res.willing_physical_examination == 0) {
                            $('#form_tab2').find('#willing_physical_examination_0').closest('span').addClass('checked')

                        }
                        if (res.ever_been_denied == 1) {
                            $('#form_tab2').find('#ever_been_denied_1').closest('span').addClass('checked')
                        } else if (res.ever_been_denied == 0) {
                            $('#form_tab2').find('#ever_been_denied_0').closest('span').addClass('checked')

                        }
                        if (res.suspend_any_license == 1) {
                            $('#form_tab2').find('#suspend_any_license_1').closest('span').addClass('checked')
                        } else if (res.suspend_any_license == 0) {
                            $('#form_tab2').find('#suspend_any_license_0').closest('span').addClass('checked')

                        }
                    }

                    var prof_id = $('#uploaded_for').val();
                    if (prof_id) {
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>profiles/getProfileById/' + prof_id + '/2',
                            success: function (res2) {


                                if (res2) {

                                    var response = JSON.parse(res2);

                                    $('#form_tab2').find(':input').each(function () {
                                        var name_attr = $(this).attr('name');

                                        //alert(name_attr);
                                        if (response[name_attr]) {

                                            $(this).val(response[name_attr]);

                                            $(this).attr('disabled', 'disabled');
                                        }

                                    });
                                }}
                        });
                    }


                    // driver applicaton ends
                } else if (form_type == "driver_evaluation_form.php") {
                    if (res) {
                        $('#form_tab3').form('load', res);


                        if (res.transmission_manual_shift == 1) {
                            $('#form_tab3').find('#transmission_manual_shift_1').closest('span').addClass('checked')
                        }
                        if (res.transmission_auto_shift == 2) {
                            $('#form_tab3').find('#transmission_auto_shift_2').closest('span').addClass('checked')
                        }

                        if (res.pre_hire == 1) {
                            $('#form_tab3').find('input[name="pre_hire"]').closest('span').addClass('checked')
                        }
                        if (res.post_accident == 2) {
                            $('#form_tab3').find('input[name="post_accident"]').closest('span').addClass('checked')
                        }
                        if (res.post_injury == 1) {
                            $('#form_tab3').find('input[name="post_injury"]').closest('span').addClass('checked')
                        }
                        if (res.post_training == 2) {
                            $('#form_tab3').find('input[name="post_training"]').closest('span').addClass('checked')
                        }
                        if (res.annual == 1) {
                            $('#form_tab3').find('input[name="annual"]').closest('span').addClass('checked')
                        }
                        if (res.skill_verification == 2) {
                            $('#form_tab3').find('input[name="skill_verification"]').closest('span').addClass('checked')
                        }

                        if (res.fuel_tank == 1) {
                            $('#form_tab3').find('input[name="fuel_tank"]').closest('span').addClass('checked')
                        }
                        if (res.all_gauges == 1) {
                            $('#form_tab3').find('input[name="all_gauges"]').closest('span').addClass('checked')
                        }
                        if (res.audible_air == 1) {
                            $('#form_tab3').find('input[name="audible_air"]').closest('span').addClass('checked')
                        }
                        if (res.wheels_tires == 1) {
                            $('#form_tab3').find('input[name="wheels_tires"]').closest('span').addClass('checked')
                        }
                        if (res.trailer_brakes == 1) {
                            $('#form_tab3').find('input[name="trailer_brakes"]').closest('span').addClass('checked')
                        }
                        if (res.trailer_airlines == 1) {
                            $('#form_tab3').find('input[name="trailer_airlines"]').closest('span').addClass('checked')
                        }
                        if (res.inspect_5th_wheel == 1) {
                            $('#form_tab3').find('input[name="inspect_5th_wheel"]').closest('span').addClass('checked')
                        }
                        if (res.cold_check == 1) {
                            $('#form_tab3').find('input[name="cold_check"]').closest('span').addClass('checked')
                        }
                        if (res.seat_mirror == 1) {
                            $('#form_tab3').find('input[name="seat_mirror"]').closest('span').addClass('checked')
                        }
                        if (res.coupling == 1) {
                            $('#form_tab3').find('input[name="coupling"]').closest('span').addClass('checked')
                        }
                        if (res.paperwork == 1) {
                            $('#form_tab3').find('input[name="paperwork"]').closest('span').addClass('checked')
                        }
                        if (res.lights_abs_lamps == 1) {
                            $('#form_tab3').find('input[name="lights_abs_lamps"]').closest('span').addClass('checked')
                        }
                        if (res.annual_inspection_strickers == 1) {
                            $('#form_tab3').find('input[name="annual_inspection_strickers"]').closest('span').addClass('checked')
                        }
                        if (res.cab_air_brake_checked == 1) {
                            $('#form_tab3').find('input[name="cab_air_brake_checked"]').closest('span').addClass('checked')
                        }
                        if (res.landing_gear == 1) {
                            $('#form_tab3').find('input[name="landing_gear"]').closest('span').addClass('checked')
                        }
                        if (res.emergency_exit == 1) {
                            $('#form_tab3').find('input[name="emergency_exit"]').closest('span').addClass('checked')
                        }

                        if (res.driving_follows_too_closely == 1) {
                            $('#form_tab3').find('#driving_follows_too_closely_1').closest('span').addClass('checked')
                        } else if (res.driving_follows_too_closely == 2) {
                            $('#form_tab3').find('#driving_follows_too_closely_2').closest('span').addClass('checked')
                        } else if (res.driving_follows_too_closely == 3) {
                            $('#form_tab3').find('#driving_follows_too_closely_3').closest('span').addClass('checked')
                        } else if (res.driving_follows_too_closely == 4) {
                            $('#form_tab3').find('#driving_follows_too_closely_4').closest('span').addClass('checked')
                        }


                        if (res.driving_improper_choice_lane == 1) {
                            $('#form_tab3').find('#driving_improper_choice_lane_1').closest('span').addClass('checked')
                        } else if (res.driving_improper_choice_lane == 2) {
                            $('#form_tab3').find('#driving_improper_choice_lane_2').closest('span').addClass('checked')
                        } else if (res.driving_improper_choice_lane == 3) {
                            $('#form_tab3').find('#driving_improper_choice_lane_3').closest('span').addClass('checked')
                        } else if (res.driving_improper_choice_lane == 4) {
                            $('#form_tab3').find('#driving_improper_choice_lane_4').closest('span').addClass('checked')
                        }


                        if (res.driving_fails_use_mirror_properly == 1) {
                            $('#form_tab3').find('#driving_fails_use_mirror_properly_1').closest('span').addClass('checked')
                        } else if (res.driving_fails_use_mirror_properly == 2) {
                            $('#form_tab3').find('#driving_fails_use_mirror_properly_2').closest('span').addClass('checked')
                        } else if (res.driving_fails_use_mirror_properly == 3) {
                            $('#form_tab3').find('#driving_fails_use_mirror_properly_3').closest('span').addClass('checked')
                        } else if (res.driving_fails_use_mirror_properly == 4) {
                            $('#form_tab3').find('#driving_fails_use_mirror_properly_4').closest('span').addClass('checked')
                        }

                        if (res.driving_signal == 1) {
                            $('#form_tab3').find('#driving_signal_1').closest('span').addClass('checked')
                        } else if (res.driving_signal == 2) {
                            $('#form_tab3').find('#driving_signal_2').closest('span').addClass('checked')
                        } else if (res.driving_signal == 3) {
                            $('#form_tab3').find('#driving_signal_3').closest('span').addClass('checked')
                        } else if (res.driving_signal == 4) {
                            $('#form_tab3').find('#driving_signal_4').closest('span').addClass('checked')
                        }

                        if (res.driving_fail_use_caution_rr == 1) {
                            $('#form_tab3').find('#driving_fail_use_caution_rr_1').closest('span').addClass('checked')
                        } else if (res.driving_fail_use_caution_rr == 2) {
                            $('#form_tab3').find('#driving_fail_use_caution_rr_2').closest('span').addClass('checked')
                        } else if (res.driving_fail_use_caution_rr == 3) {
                            $('#form_tab3').find('#driving_fail_use_caution_rr_3').closest('span').addClass('checked')
                        } else if (res.driving_fail_use_caution_rr == 4) {
                            $('#form_tab3').find('#driving_fail_use_caution_rr_4').closest('span').addClass('checked')
                        }

                        if (res.driving_speed == 1) {
                            $('#form_tab3').find('#driving_speed_1').closest('span').addClass('checked')
                        } else if (res.driving_speed == 2) {
                            $('#form_tab3').find('#driving_speed_2').closest('span').addClass('checked')
                        } else if (res.driving_speed == 3) {
                            $('#form_tab3').find('#driving_speed_3').closest('span').addClass('checked')
                        } else if (res.driving_speed == 4) {
                            $('#form_tab3').find('#driving_speed_4').closest('span').addClass('checked')
                        }

                        if (res.driving_incorrect_use_clutch_brake == 1) {
                            $('#form_tab3').find('#driving_incorrect_use_clutch_brake_1').closest('span').addClass('checked')
                        } else if (res.driving_incorrect_use_clutch_brake == 2) {
                            $('#form_tab3').find('#driving_incorrect_use_clutch_brake_2').closest('span').addClass('checked')
                        } else if (res.driving_incorrect_use_clutch_brake == 3) {
                            $('#form_tab3').find('#driving_incorrect_use_clutch_brake_3').closest('span').addClass('checked')
                        } else if (res.driving_incorrect_use_clutch_brake == 4) {
                            $('#form_tab3').find('#driving_incorrect_use_clutch_brake_4').closest('span').addClass('checked')
                        }

                        if (res.driving_accelerator_gear_steer == 1) {
                            $('#form_tab3').find('#driving_accelerator_gear_steer_1').closest('span').addClass('checked')
                        } else if (res.driving_accelerator_gear_steer == 2) {
                            $('#form_tab3').find('#driving_accelerator_gear_steer_2').closest('span').addClass('checked')
                        } else if (res.driving_accelerator_gear_steer == 3) {
                            $('#form_tab3').find('#driving_accelerator_gear_steer_3').closest('span').addClass('checked')
                        } else if (res.driving_accelerator_gear_steer == 4) {
                            $('#form_tab3').find('#driving_accelerator_gear_steer_4').closest('span').addClass('checked')
                        }

                        if (res.driving_incorrect_observation_skills == 1) {
                            $('#form_tab3').find('#driving_incorrect_observation_skills_1').closest('span').addClass('checked')
                        } else if (res.driving_incorrect_observation_skills == 2) {
                            $('#form_tab3').find('#driving_incorrect_observation_skills_2').closest('span').addClass('checked')
                        } else if (res.driving_incorrect_observation_skills == 3) {
                            $('#form_tab3').find('#driving_incorrect_observation_skills_3').closest('span').addClass('checked')
                        } else if (res.driving_incorrect_observation_skills == 4) {
                            $('#form_tab3').find('#driving_incorrect_observation_skills_4').closest('span').addClass('checked')
                        }

                        if (res.driving_respond_instruction == 1) {
                            $('#form_tab3').find('#driving_respond_instruction_1').closest('span').addClass('checked')
                        } else if (res.driving_respond_instruction == 2) {
                            $('#form_tab3').find('#driving_respond_instruction_2').closest('span').addClass('checked')
                        } else if (res.driving_respond_instruction == 3) {
                            $('#form_tab3').find('#driving_respond_instruction_3').closest('span').addClass('checked')
                        } else if (res.driving_respond_instruction == 4) {
                            $('#form_tab3').find('#driving_respond_instruction_4').closest('span').addClass('checked')
                        }

                        if (res.cornering_signaling == 1) {
                            $('#form_tab3').find('#cornering_signaling_1').closest('span').addClass('checked')
                        } else if (res.cornering_signaling == 2) {
                            $('#form_tab3').find('#cornering_signaling_2').closest('span').addClass('checked')
                        } else if (res.cornering_signaling == 3) {
                            $('#form_tab3').find('#cornering_signaling_3').closest('span').addClass('checked')
                        } else if (res.cornering_signaling == 4) {
                            $('#form_tab3').find('#cornering_signaling_4').closest('span').addClass('checked')
                        }

                        if (res.cornering_speed == 1) {
                            $('#form_tab3').find('#cornering_speed_1').closest('span').addClass('checked')
                        } else if (res.cornering_speed == 2) {
                            $('#form_tab3').find('#cornering_speed_2').closest('span').addClass('checked')
                        } else if (res.cornering_speed == 3) {
                            $('#form_tab3').find('#cornering_speed_3').closest('span').addClass('checked')
                        } else if (res.cornering_speed == 4) {
                            $('#form_tab3').find('#cornering_speed_4').closest('span').addClass('checked')
                        }

                        if (res.cornering_fails == 1) {
                            $('#form_tab3').find('#cornering_fails_1').closest('span').addClass('checked')
                        } else if (res.cornering_fails == 2) {
                            $('#form_tab3').find('#cornering_fails_2').closest('span').addClass('checked')
                        } else if (res.cornering_fails == 3) {
                            $('#form_tab3').find('#cornering_fails_3').closest('span').addClass('checked')
                        } else if (res.cornering_fails == 4) {
                            $('#form_tab3').find('#cornering_fails_4').closest('span').addClass('checked')
                        }

                        if (res.cornering_proper_set_up_turn == 1) {
                            $('#form_tab3').find('#cornering_proper_set_up_turn_1').closest('span').addClass('checked')
                        } else if (res.cornering_proper_set_up_turn == 2) {
                            $('#form_tab3').find('#cornering_proper_set_up_turn_2').closest('span').addClass('checked')
                        } else if (res.cornering_proper_set_up_turn == 3) {
                            $('#form_tab3').find('#cornering_proper_set_up_turn_3').closest('span').addClass('checked')
                        } else if (res.cornering_proper_set_up_turn == 4) {
                            $('#form_tab3').find('#cornering_proper_set_up_turn_4').closest('span').addClass('checked')
                        }

                        if (res.cornering_turns == 1) {
                            $('#form_tab3').find('#cornering_turns_1').closest('span').addClass('checked')
                        } else if (res.cornering_turns == 2) {
                            $('#form_tab3').find('#cornering_turns_2').closest('span').addClass('checked')
                        } else if (res.cornering_turns == 3) {
                            $('#form_tab3').find('#cornering_turns_3').closest('span').addClass('checked')
                        } else if (res.cornering_turns == 4) {
                            $('#form_tab3').find('#cornering_turns_4').closest('span').addClass('checked')
                        }


                        if (res.cornering_wrong_lane_impede == 1) {
                            $('#form_tab3').find('#cornering_wrong_lane_impede_1').closest('span').addClass('checked')
                        } else if (res.cornering_wrong_lane_impede == 2) {
                            $('#form_tab3').find('#cornering_wrong_lane_impede_2').closest('span').addClass('checked')
                        } else if (res.cornering_wrong_lane_impede == 3) {
                            $('#form_tab3').find('#cornering_wrong_lane_impede_3').closest('span').addClass('checked')
                        } else if (res.cornering_wrong_lane_impede == 4) {
                            $('#form_tab3').find('#cornering_wrong_lane_impede_4').closest('span').addClass('checked')
                        }

                        if (res.shifting_smooth_take_off == 1) {
                            $('#form_tab3').find('#shifting_smooth_take_off_1').closest('span').addClass('checked')
                        } else if (res.shifting_smooth_take_off == 2) {
                            $('#form_tab3').find('#shifting_smooth_take_off_2').closest('span').addClass('checked')
                        } else if (res.shifting_smooth_take_off == 3) {
                            $('#form_tab3').find('#shifting_smooth_take_off_3').closest('span').addClass('checked')
                        } else if (res.shifting_smooth_take_off == 4) {
                            $('#form_tab3').find('#shifting_smooth_take_off_4').closest('span').addClass('checked')
                        }

                        if (res.shifting_proper_gear_selection == 1) {
                            $('#form_tab3').find('#shifting_proper_gear_selection_1').closest('span').addClass('checked')
                        } else if (res.shifting_proper_gear_selection == 2) {
                            $('#form_tab3').find('#shifting_proper_gear_selection_2').closest('span').addClass('checked')
                        } else if (res.shifting_proper_gear_selection == 3) {
                            $('#form_tab3').find('#shifting_proper_gear_selection_3').closest('span').addClass('checked')
                        } else if (res.shifting_proper_gear_selection == 4) {
                            $('#form_tab3').find('#shifting_proper_gear_selection_4').closest('span').addClass('checked')
                        }

                        if (res.shifting_proper_clutching == 1) {
                            $('#form_tab3').find('#shifting_proper_clutching_1').closest('span').addClass('checked')
                        } else if (res.shifting_proper_clutching == 2) {
                            $('#form_tab3').find('#shifting_proper_clutching_2').closest('span').addClass('checked')
                        } else if (res.shifting_proper_clutching == 3) {
                            $('#form_tab3').find('#shifting_proper_clutching_3').closest('span').addClass('checked')
                        } else if (res.shifting_proper_clutching == 4) {
                            $('#form_tab3').find('#shifting_proper_clutching_4').closest('span').addClass('checked')
                        }

                        if (res.shifting_gear_recovery == 1) {
                            $('#form_tab3').find('#shifting_gear_recovery_1').closest('span').addClass('checked')
                        } else if (res.shifting_gear_recovery == 2) {
                            $('#form_tab3').find('#shifting_gear_recovery_2').closest('span').addClass('checked')
                        } else if (res.shifting_gear_recovery == 3) {
                            $('#form_tab3').find('#shifting_gear_recovery_3').closest('span').addClass('checked')
                        } else if (res.shifting_gear_recovery == 4) {
                            $('#form_tab3').find('#shifting_gear_recovery_4').closest('span').addClass('checked')
                        }

                        if (res.shifting_up_down == 1) {
                            $('#form_tab3').find('#shifting_up_down_1').closest('span').addClass('checked')
                        } else if (res.shifting_up_down == 2) {
                            $('#form_tab3').find('#shifting_up_down_2').closest('span').addClass('checked')
                        } else if (res.shifting_up_down == 3) {
                            $('#form_tab3').find('#shifting_up_down_3').closest('span').addClass('checked')
                        } else if (res.shifting_up_down == 4) {
                            $('#form_tab3').find('#shifting_up_down_4').closest('span').addClass('checked')
                        }

                        if (res.backing_uses_proper_set_up == 1) {
                            $('#form_tab3').find('#backing_uses_proper_set_up_1').closest('span').addClass('checked')
                        }

                        if (res.backing_path_before_while_driving == 1) {
                            $('#form_tab3').find('#backing_path_before_while_driving_1').closest('span').addClass('checked')
                        } else if (res.backing_path_before_while_driving == 2) {
                            $('#form_tab3').find('#backing_path_before_while_driving_2').closest('span').addClass('checked')
                        }

                        if (res.backing_use_4way_flashers_city_horn == 1) {
                            $('#form_tab3').find('#backing_use_4way_flashers_city_horn_1').closest('span').addClass('checked')
                        } else if (res.backing_use_4way_flashers_city_horn == 2) {
                            $('#form_tab3').find('#backing_use_4way_flashers_city_horn_2').closest('span').addClass('checked')
                        }

                        if (res.backing_show_certainty_while_steering == 1) {
                            $('#form_tab3').find('#backing_show_certainty_while_steering_1').closest('span').addClass('checked')
                        } else if (res.backing_show_certainty_while_steering == 2) {
                            $('#form_tab3').find('#backing_show_certainty_while_steering_2').closest('span').addClass('checked')
                        }

                        if (res.backing_continually_uses_mirror == 1) {
                            $('#form_tab3').find('#backing_continually_uses_mirror_1').closest('span').addClass('checked')
                        } else if (res.backing_continually_uses_mirror == 2) {
                            $('#form_tab3').find('#backing_continually_uses_mirror_2').closest('span').addClass('checked')
                        }

                        if (res.backing_maintain_proper_seed == 1) {
                            $('#form_tab3').find('#backing_maintain_proper_seed_1').closest('span').addClass('checked')
                        }

                        if (res.backing_complete_reasonable_time_fashion == 1) {
                            $('#form_tab3').find('#backing_complete_reasonable_time_fashion_1').closest('span').addClass('checked')
                        }

                        if (res.recommended_for_hire == 1) {
                            $('#form_tab3').find('#recommended_for_hire_1').closest('span').addClass('checked')
                        } else if (res.recommended_for_hire == 2) {
                            $('#form_tab3').find('#recommended_for_hire_2').closest('span').addClass('checked')
                        }

                        if (res.recommended_full_trainee == 1) {
                            $('#form_tab3').find('#recommended_full_trainee_1').closest('span').addClass('checked')
                        } else if (res.recommended_full_trainee == 2) {
                            $('#form_tab3').find('#recommended_full_trainee_0').closest('span').addClass('checked')

                        }
                        if (res.recommended_fire_hire_trainee == 1) {
                            $('#form_tab3').find('#recommended_fire_hire_trainee_1').closest('span').addClass('checked')
                        } else if (res.recommended_fire_hire_trainee == 2) {
                            $('#form_tab3').find('#recommended_fire_hire_trainee_0').closest('span').addClass('checked')
                        }
                    }

                    var prof_id = $('#uploaded_for').val();
                    if (prof_id) {
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>profiles/getProfileById/' + prof_id + '/3',
                            success: function (res2) {

                                if (res2) {

                                    var response = JSON.parse(res2);
                                    $('#form_tab3').find(':input').each(function () {
                                        var name_attr = $(this).attr('name');

                                        //alert(name_attr);
                                        if (response[name_attr]) {

                                            $(this).val(response[name_attr]);
                                            $(this).attr('disabled', 'disabled');


                                        }

                                    });
                                }
                            }
                        });
                    }


                    // end road test
                } else if (form_type == "document_tab_3.php") {


                    if (res) {

                        $('#form_consent').find(':input').each(function () {
                            if($(this).attr('class')!='touched' && $(this).attr('class')!='touched_edit3' && $(this).attr('class')!='touched_edit1' && $(this).attr('class')!='touched_edit2' && $(this).attr('class')!='touched_edit4'){
                                var $name = $(this).attr('name');

                                //alert(doc_id + " " + $name + " " + res[$name]);

                                if ($name != 'offence[]' && $name != 'date_of_sentence[]' && $name != 'location[]' && $name != 'attach_doc[]') {
                                    if (doc_id && $(this).val() == "") {
                                        $(this).val(res[$name]);
                                    }
                                }
                            }
                        });
                    }
                    var prof_id = $('#uploaded_for').val();
                    if (prof_id) {
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>profiles/getProfileById/' + prof_id + '/4',
                            success: function (res2) {

                                if (res2) {

                                    var response = JSON.parse(res2);
                                    $('#form_consent').find(':input').each(function () {
                                        var name_attr = $(this).attr('name');

                                        //alert(name_attr);
                                        if (response[name_attr]) {

                                            $(this).val(response[name_attr]);
                                            $(this).attr('disabled', 'disabled');

                                        }

                                    });
                                }
                            }
                        });
                    }


                    // assignValue('form_tab4',res);
                    // mee order ends
                }
            });
        }
        else
            $('.subform').html("");
    }


    function assignValue(formID, obj) {
        $('#' + formID).form('load', obj);
    }


    function subform(form_type) {
        var filename = form_type.replace(/\W/g, '_');
        var filename = filename.toLowerCase();
        $('.subform').show();
        $('.subform').load('<?php echo $this->request->webroot;?>documents/subpages/' + filename);
    }
    jQuery(document).ready(function () {




        if($('.tabber.active').attr('id')=='tab1')
        {
            $('.skip').hide();
        }


        <?php if(isset($_GET['driver']) && is_numeric($_GET['driver'])){?>

        showforms('company_pre_screen_question.php');
        showforms('driver_application.php');
        showforms('driver_evaluation_form.php');
        showforms('document_tab_3.php');
        <?php }?>

        $('.button-next').click(function () {
            $('.cont').removeAttr('disabled');
            //$('.tab-pane.active').find('input[type="email"]').val('');
        });
        $('.button-previous').click(function () {
            $('.cont').removeAttr('disabled');
            //$('.tab-pane.active input[type="email"]').val('');
        });
        $('.email1').live('keyup', function () {
            //alert($('.email1').val());
            if ($(this).val() != '' && ($(this).val().replace('@', '') == $(this).val() || $(this).val().replace('.', '') == $(this).val() || $(this).val().length < 5)) {
                $(this).attr('style', 'border-color:red');
                $('.cont').attr('disabled', '');
            }
            else {
                // alert($('.email1').val());
                $('.cont').removeAttr('disabled');
                $(this).removeAttr('style');
            }

        });
        $('.email1').live('blur', function () {
            //alert($('.email1').val());
            if ($(this).val() != '' && ($(this).val().replace('@', '') == $(this).val() || $(this).val().replace('.', '') == $(this).val() || $(this).val().length < 5)) {
                $(this).val('');
                $('.cont').removeAttr('disabled');
                $(this).removeAttr('style');
            }


        });
        $('.required').live('keyup', function () {
            //alert('test');
            //alert($('.email1').val());
            if ($(this).val().length > 0) {
                $(this).removeAttr('style');
                //$('.cont').attr('disabled','');
            }


        });
        $('#driverEm').live('keyup', function () {
            //alert('test');
            //alert($('.email1').val());
            if ($(this).val().length > 0) {
                $(this).removeAttr('style');
                //$('.cont').attr('disabled','');
            }


        });
        $('.required').live('change', function () {
            //alert('test');
            //alert($('.email1').val());
            if ($(this).val().length > 0) {
                $(this).removeAttr('style');
                //$('.cont').attr('disabled','');
            }


        });
        $('.required').live('blur', function () {
            //alert($('.email1').val());
            if ($(this).val().length == 0) {
                $(this).val('');
                //$('.cont').removeAttr('disabled');
                $(this).attr('style', 'border-color:red');
            }


        });


        <?php
        if($this->request['action']=='vieworder')
        {
            ?>
        $('.tab-content input').attr('disabled', 'disabled');
        $('.tab-content select').attr('disabled', 'disabled');
        $('.tab-content textarea').attr('disabled', 'disabled');
        $('.tab-content button').hide();
        $('.tab-content a').hide();
        $('.nav a').show();
        $('.cont').html('<?= addslashes($strings["addorder_next"]); ?> <i class="m-icon-swapright m-icon-white"></i>');
        $('.cont').parent().find('.red').remove();
        $('.cont').each(function () {
            $(this).attr('id', 'nextview');
            $(this).addClass('nextview');
        });
        $('.dl').each(function () {
            $(this).show();
            //$(this).addClass('nextview');
        });
        $('.cont').removeClass('cont');
        $('.uploaded a').show();

        <?php
    }

    ?>




        $(document.body).on('click', '.consents a', function () {
            //alert($(this).attr('href').replace('#',''));
            //$('#'+$(this).attr('href').replace('#','')).show();
            var ba = $('#' + $(this).attr('href').replace('#', '') + ' .moremore').offset();
            // alert(ba.top);
            //alert(ba.top);
            $('input[type="file"]').each(function () {
                $(this).parent().attr('style', 'display: block; position: absolute; overflow: hidden; margin: 0px; padding: 0px; opacity: 0; direction: ltr; z-index: 2147483583; left: 551.5px; top: ' + ba.top + 'px; width: 77px; height: 34px; visibility: hidden;');
                //  css = css.
            });
        });

        $(document.body).on('click', '.button-next', function () {

            var ba = $('#bottomact').offset();
            //alert(ba.top);
            $('input[type="file"]').each(function () {
                $(this).parent().attr('style', 'display: block; position: absolute; overflow: hidden; margin: 0px; padding: 0px; opacity: 0; direction: ltr; z-index: 2147483583; left: 551.5px; top: ' + ba.top + 'px; width: 77px; height: 34px; visibility: hidden;');
                //  css = css.
            });
        });

        $(document.body).on('click', '.button-previous', function () {
            var ba = $('#bottomact').offset();
            //alert(ba.top);
            $('input[type="file"]').each(function () {
                $(this).parent().attr('style', 'display: block; position: absolute; overflow: hidden; margin: 0px; padding: 0px; opacity: 0; direction: ltr; z-index: 2147483583; left: 551.5px; top: ' + ba.top + 'px; width: 77px; height: 34px; visibility: hidden;');
                //  css = css.
            });
        });

        var draft = 0;
        var saving_draft = 0;
        $(document.body).on('click', '.cont', function () {


            //$('.submit_dra').attr('style','display:inline-block');

            if ($(this).attr('id') == 'draft') {
                draft = 1;
            } else{
                if($(this).attr('id') == 'submit_dra'){
                    draft = 1;
                    saving_draft = 1;
                    $('#loading5').show();
                } else {
                    draft = 0;
                }
            }
            if(draft==1) {
                $('.blockmsg').html('<h4 class="block"><?= addslashes($strings["addorder_orderdraft"]); ?>!</h4>'+
                '<p><?= addslashes($strings["addorder_youcanedit"]); ?></p>')
            } else {
                $('.blockmsg').html('<h4 class="block"><?= addslashes($strings["addorder_ordersubmit"]); ?>!</h4>'+
                '<p><?= addslashes($strings["addorder_notified"]); ?></p>')
            }

            var type = $(".tabber.active").prev('.tabber').find("input[name='document_type']").val();
            var sid = $(".tabber.active").prev('.tabber').find("input[name='sub_doc_id']").val();
            //alert(type);
            if (type == 'add_driver') {
                saveDriver('<?php echo $cid;?>');
            } else {
                var confirmation = $(".tabber.active").prev('.tabber').find("#confirmation").val();
                var data = {
                    uploaded_for: $('#uploaded_for').val(),
                    type: type,
                    division: $('#division').val(),
                    conf_recruiter_name: $('#conf_recruiter_name').val(),
                    conf_driver_name: $('#conf_driver_name').val(),
                    conf_date: $('#conf_date').val(),
                    recruiter_signature: $('#recruiter_signature').val(),
                    //confirm_check:$('#confirm_check1').val()
                };
                    

                $.ajax({
                    data: data,
                    type: 'post',
                    beforeSend: saveSignature,
                    url: '<?php echo $this->request->webroot;?>orders/savedoc/<?php echo $cid;?>/' + $('#did').val() + '?draft=' + draft+'&order_type=<?php if(isset($_GET['order_type']))echo $_GET['order_type'];?>&forms=<?php if(isset($_GET['forms']))echo $_GET['forms'];?>',
                    success: function (res) {

                        $('#did').val(res);
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>orders/savedoc/<?php echo $cid;?>/' + $('#did').val() + '?draft=' + draft+'&order_type=<?php if(isset($_GET['order_type']))echo $_GET['order_type'];?>&forms=<?php if(isset($_GET['forms']))echo $_GET['forms'];?>',
                            type: 'post',
                            data: {
                                uploaded_for: $('#uploaded_for').val(),
                                type: type,
                                division: $('#division').val(),
                                conf_recruiter_name: $('#conf_recruiter_name').val(),
                                conf_driver_name: $('#conf_driver_name').val(),
                                conf_date: $('#conf_date').val(),
                                recruiter_signature: $('#recruiter_signature').val()
                            }
                        });
                        // saving data
                        doc_id = res;
                        if (sid == "1") {
                            var forms = $(".tabber.active").prev('.tabber').find(':input'),
                                url = '<?php echo $this->request->webroot;?>documents/savePrescreening',
                                order_id = $('#did').val(),
                                cid = '<?php echo $cid;?>';

                            savePrescreen(url, order_id, cid, forms);


                        } else if (sid == "2") {
                            if ($('#confirm_check').is(':checked')) {
                                var order_id = $('#did').val(),
                                    cid = '<?php echo $cid;?>',
                                    url = '<?php echo $this->request->webroot;?>documents/savedDriverApp/' + order_id + '/' + cid;
                                savedDriverApp(url, order_id, cid);
                            }


                        } else if (sid == "3") {
                            var order_id = $('#did').val(),
                                cid = '<?php echo $cid;?>',
                                url = '<?php echo $this->request->webroot;?>documents/savedDriverEvaluation/' + order_id + '/' + cid;
                            savedDriverEvaluation(url, order_id, cid);
                        } else if (sid == "4") {

                            //alert(type);
                            var order_id = $('#did').val(),
                                cid = '<?php echo $cid;?>',
                                url = '<?php echo $this->request->webroot;?>documents/savedMeeOrder/' + order_id + '/' + cid;
                            savedMeeOrder(url, order_id, cid);
                        }
                        else if (sid == "9") {

                            //alert(type);
                            var order_id = $('#did').val(),
                                cid = '<?php echo $cid;?>',
                                url = '<?php echo $this->request->webroot;?>documents/saveEmployment/' + order_id + '/' + cid;
                            saveEmployment(url, order_id, cid);
                        }
                        else if (sid == "10") {

                            //alert(type);
                            var order_id = $('#did').val(),
                                cid = '<?php echo $cid;?>',
                                url = '<?php echo $this->request->webroot;?>documents/saveEducation/' + order_id + '/' + cid;
                            saveEducation(url, order_id, cid);
                        }
                        else if (sid == "6") {
                            var order_id = $('#did').val(),
                                cid = '<?php echo $cid;?>',
                                url = '<?php echo $this->request->webroot;?>feedbacks/add/' + order_id + '/' + cid;
                            var param = $('#form_tab6').serialize()+'&order_id='+order_id;
                            $.ajax({
                                url: url,
                                data: param,
                                type: 'POST'
                            });

                        }
                        else if (sid == "5") {
                            var order_id = $('#did').val(),
                                cid = '<?php echo $cid;?>',
                                url = '<?php echo $this->request->webroot;?>feedbacks/addsurvey/' + order_id + '/' + cid;
                            var param = $('#form_tab5').serialize()+'&order_id='+order_id;
                            $.ajax({
                                url: url,
                                data: param,
                                type: 'POST'
                            });

                        }
                        else if (sid == "7") {

                            var order_id = $('#did').val(),
                                cid = '<?php echo $cid;?>',
                                url = '<?php echo $this->request->webroot;?>documents/addattachment/' + cid + '/' + order_id+ '?draft=' + draft;
                            var param = $('#form_tab7').serialize()+'&order_id='+order_id;
                            $.ajax({
                                url: url,
                                data: param,
                                type: 'POST'
                            });


                        }
                        else if (sid == "8") {
                            var order_id = $('#did').val(),
                                cid = '<?php echo $cid;?>',
                                url = '<?php echo $this->request->webroot;?>documents/audits/' + cid + '/' + order_id+ '?draft=' + draft;
                            var param = $('#form_tab8').serialize()+'&order_id='+order_id;
                            $.ajax({
                                url: url,
                                data: param,
                                type: 'POST'
                            });

                        }
                        else if (sid == "15") {
                            var order_id = $('#did').val(),
                                cid = '<?php echo $cid;?>',
                                url = '<?php echo $this->request->webroot;?>documents/mee_attach/' + order_id + '/' + cid+ '?draft=' + draft;
                            var param = $('#form_tab15').serialize()+'&order_id='+order_id;
                            $.ajax({
                                url: url,
                                data: param,
                                type: 'POST'
                            });

                        }
                        else{
                            <?php foreach($doc as $dx)
                                    {
                                        if($dx->id >15 || $dx->id==11)
                                        {

                                        ?>
                            //alert(type);
                            if(type == "<?php echo addslashes($dx->title);?>")
                            {

                                var order_id = $('#did').val();
                                $('#form_tab<?php echo $dx->id;?>').attr('action', function (i, val) {
                                    return val + '?order_id='+order_id+'&draft=' + draft;
                                });
                                var act = $('#form_tab<?php echo $dx->id;?>').attr('action');


                                var  cid = '<?php echo $cid;?>';
                                var  url = act;
                                var param = $('#form_tab<?php echo $dx->id;?>').serialize()+'&order_id='+order_id;
                                $.ajax({
                                    url: url,
                                    data: param,
                                    type: 'POST'
                                });
                            }

                            <?php       }
                                    }
                            ?>

                        }

                        if(saving_draft==1) {
                            $('#loading5').show();
                            setTimeout(function(){ window.location = '<?php echo $this->request->webroot; ?>orders/orderslist?flash&draft'; }, 5000);
                            
                        }
                    }
                });
            }
        });

        $('#submit_ord').live('click', function () {
            URL='<?php echo $this->request->webroot;?>profiles/view/'+$('#uploaded_for').val()+'?getprofilescore=1&success';
            if($('#uploaded_for').val() ==0){
                URL='<?php echo $this->request->webroot;?>orders/orderslist?flash';
            }

            setTimeout(function(){
                $.ajax({
                    url: '<?php echo $this->request->webroot;?>orders/webservice/<?php echo $_GET['order_type'];?>/<?php echo $_GET['forms']; ?>/' +  $('#uploaded_for').val() +'/' +  $('#did').val(),
                    success:function(msg){
                            //alert("Order saved: " + msg);
                     window.location = URL;
                    },
                    error:function(){
                        window.location = URL;
                    }
                });

            }, 5000);

        });

        $('#submit_dra').live('click', function () {
            // alert($(this).text());


            $('.blockmsg').html('<h4 class="block">Your Order Has Been Saved As Draft!</h4>'+
            '<p>You can edit your order anytime.</p>')

            var type = $(".tab-pane.active").prev('.tab-pane').find("input[name='document_type']").val();
            var tabid = $(".tab-pane.active").prev('.tab-pane').find("#confirmation").val();
            if (tabid == '1') {
                var confirmation = $(".tab-pane.active").prev('.tab-pane').find("#confirmation").val();
                var data = {
                    uploaded_for: $('#uploaded_for').val(),
                    type: type,
                    division: $('#division').val(),
                    conf_recruiter_name: $('#conf_recruiter_name').val(),
                    conf_driver_name: $('#conf_driver_name').val(),
                    conf_date: $('#conf_date').val(),
                    recruiter_signature: $('#recruiter_signature').val()
                };
                $.ajax({
                    //data:'uploaded_for='+$('#uploaded_for').val(),
                    data: data,
                    type: 'post',
                    beforeSend: saveSignature,
                    url: '<?php echo $this->request->webroot;?>orders/savedoc/<?php echo $cid;?>/' + $('#did').val() + '?draft=1&order_type=<?php if(isset($_GET['order_type']))echo $_GET['order_type'];?>&forms=<?php if(isset($_GET['forms']))echo $_GET['forms'];?>',
                    success: function (res) {
                        $('#did').val(res);
                        var draftmode = '<h4 class="block"><?= addslashes($strings["addorder_orderdraft"]); ?></h4><p> <?= addslashes($strings["addorder_youcanedit"]); ?> </p>'
                        $('#tab6 .note').html(draftmode);
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>orders/savedoc/<?php echo $cid;?>/' + $('#did').val() + '?draft=1&order_type=<?php if(isset($_GET['order_type']))echo $_GET['order_type'];?>&forms=<?php if(isset($_GET['forms']))echo $_GET['forms'];?>',
                            type: 'post',
                            data: {
                                uploaded_for: $('#uploaded_for').val(),
                                type: type,
                                division: $('#division').val(),
                                conf_recruiter_name: $('#conf_recruiter_name').val(),
                                conf_driver_name: $('#conf_driver_name').val(),
                                conf_date: $('#conf_date').val(),
                                recruiter_signature: $('#recruiter_signature').val()
                            },
                            success:function(){
                         window.location = base_url+'orders/orderslist?flash';
                            },
                            error:function(){
                           window.location = base_url+'orders/orderslist?flash';
                            }
                        });
                    }
                });
            }
        });
        $('.nohide').show();
    });
    function saveSignature() {
        if ($(".tabber.active").prev('.tabber').find("input[name='document_type']").val() == 'Consent Form') {
            //check_role();
            //save_signature('3');
            //save_signature('4');
            //save_signature('5');
            //save_signature('6');

        } else {
            if ($(".tabber.active").prev('.tabber').attr('id') == 'tab19') {
                save_signature('8');
            }
        }
    }

    function save_signature(numb) {
        //alert('trd');
        if(numb == '5' || numb == '6' || numb == '3' || numb == '4') {
            if(numb == '5') {
               $('#criminal_signature_applicant2').parent().find('.touched').val(1); 
            }
            if(numb == '4') {
                $('#signature_company_witness').parent().find('.touched').val(1);
            }
            if(numb == '3') {
                $('#criminal_signature_applicant').parent().find('.touched').val(1);
            }
            if(numb == '6') {
                $('#signature_company_witness2').parent().find('.touched').val(1);
            }
        }
        $("#test" + numb).data("jqScribble").save(function (imageData) {
            if ((numb == '8' && $('#gfs_signature').parent().find('.touched').val() == 1) || (numb == '1' && $('#recruiter_signature').parent().find('.touched').val() == 1) || (numb == '3' && $('#criminal_signature_applicant').parent().find('.touched').val() == 1) || (numb == '4' && $('#signature_company_witness').parent().find('.touched').val() == 1) || (numb == '5' && $('#criminal_signature_applicant2').parent().find('.touched').val() == 1) || (numb == '6' && $('#signature_company_witness2').parent().find('.touched').val() == 1)) {
                $.post('<?php echo $this->request->webroot; ?>canvas/image_save.php', {imagedata: imageData}, function (response) {
                    if(response=='' && (numb=='3' || numb=='5' || numb=='4' || numb=='6'))
                    {
                        alert('<?= addslashes($strings["addorder_problem"]); ?>');
                    }
                    if (numb == '1') {
                        $('#recruiter_signature').val(response);
                    }
                    if (numb == '3') {
                        $('#criminal_signature_applicant').val(response);
                    }
                    if (numb == '4') {
                        $('#signature_company_witness').val(response);
                    }
                    if (numb == '5') {
                        $('#criminal_signature_applicant2').val(response);
                    }
                    if (numb == '6') {
                        $('#signature_company_witness2').val(response);
                    }
                    if (numb == '8') {
                        $('#gfs_signature').val(response);
                    }
                    $('.saved'+numb).html('<?= ucfirst($strings["documents_saved"]); ?>');
                });
            }
        });
    }

    function nextform(){
        showforms('driver_application.php');
        showforms('driver_evaluation_form.php');
        showforms('document_tab_3.php');
        showforms('company_pre_screen_question.php');
    }

    function saveDriver(cid) {
        var fields = $('#createDriver').serialize();
        fields = fields + '&profile_type=' + $('.member_type').val();
        //alert("<?= $action; ?>");

        var param = {
            cid: cid,
            inputs: fields
        };
        $.ajax({
            url: '<?php echo $this->request->webroot;?>profiles/saveDriver/',
            data: param,
            type: 'POST',
            success: function (res) {
                if (res != 'exist') {
                    $('#uploaded_for').val(res);
                    $('.driver_id').val(res);
                    nextform();
                }  else {
                    alert('<?= addslashes($strings["dashboard_emailexists"]); ?>');
                    $('#driverEm').focus();
                    $('#driverEm').attr('style', 'border-color:red');
                    $('.button-previous').click();
                    $('html,body').animate({scrollTop: $('.active').offset().top},'slow');
                }
            }
        });
    }

    function savePrescreen(url, order_id, cid) {
        var fields = $('#form_tab1').serialize();
        $(':disabled[name]', '#form_tab1').each(function () {
            fields = fields + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        var param = {
            order_id: order_id,
            cid: cid,

            inputs: fields
        };
        $.ajax({
            url: url,
            data: param,
            type: 'POST'
        });
    }

    function savedDriverApp(url, order_id, cid) {
        var fields = $('#form_tab2').serialize();
        $(':disabled[name]', '#form_tab2').each(function () {
            fields = fields + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        var param = fields
        $.ajax({
            url: url,
            data: param,
            type: 'POST'
        });
    }
    function savedDriverEvaluation(url, order_id, cid) {
        var fields = $('#form_tab3').serialize();
        $(':disabled[name]', '#form_tab3').each(function () {
            fields = fields + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        var param = fields

        $.ajax({
            url: url,
            data: param,
            type: 'POST',
            success: function (res) {

            }
        });
    }

    function savedMeeOrder(url, order_id, cid) {
        $('#loading5').show();
        var fields = $('#form_consent').serialize();
        $(':disabled[name]', '#form_consent').each(function () {
            fields = fields + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        var param = fields
        $.ajax({
            url: url,
            data: param,
            type: 'POST',
            success: function (res) {
                
                $.ajax({
                    url: '<?php echo $this->request->webroot;?>orders/checkSignature/' + $('#did').val(),
                    success:function(resp) {
                        if(resp=='1') {
                            $.ajax({
                                url: '<?php echo $this->request->webroot;?>orders/createPdf/' + $('#did').val(),
                                success:function()
                                {
                                    $('#loading5').hide();
                                }
                            });
                        } else {
                            alert('<?= addslashes($strings["addorder_problem"]); ?>');
                            $('#loading5').hide();

                        }
                    }
                });

            }
        });
    }

    function saveEmployment(url, order_id, cid) {
        $('#loading5').show();
        var fields = $('#form_employment').serialize();
        $(':disabled[name]', '#form_employment').each(function () {
            fields = fields + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        var param = fields
        $.ajax({
            url: url,
            data: param,
            type: 'POST',
            success: function (rea) {
                $.ajax({
                    url: '<?php echo $this->request->webroot;?>orders/createPdfEmployment/' + $('#did').val(),
                    success: function () {
                        $('#loading5').hide();
                    }
                });
            }
        });
    }

    function saveEducation(url, order_id, cid) {
        $('#loading5').show();
        var fields = $('#form_education').serialize();
        $(':disabled[name]', '#form_education').each(function () {
            fields = fields + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        var param = fields
        $.ajax({
            url: url,
            data: param,
            type: 'POST',
            success: function (res) {
                $.ajax({
                    url: '<?php echo $this->request->webroot;?>orders/createPdfEducation/' + $('#did').val(),
                    success: function () {
                        $('#loading5').hide();
                    }
                });
            }
        });
    }


</script>

<style>
    @media print {
        .page-header {
            display: none;
        }

        .page-footer, .nav-tabs, .page-title, .page-bar, .theme-panel, .page-sidebar-wrapper, .form-actions, .steps, .caption {
            display: none !important;
        }

        .portlet-body, .portlet-title {
            border-top: 1px solid #578EBE;
        }

        .tabbable-line {
            border: none !important;
        }

    }
</style>

<script type="text/javascript">
    function addmoredoc(idname)
    {
        var total_count = $('.'+idname).data('count');
        $('.'+idname).data('count', parseInt(total_count) + 1);
        total_count = $('.'+idname).data('count');
        var input_field = '<div  class="form-group col-md-12" style="padding-left:0;"><div class="col-md-12"><a href="javascript:void(0);" id="'+idname + total_count + '" class="btn btn-primary">Browse</a><input type="hidden" name="attach_doc[]" value="" class="'+idname + total_count + '_doc moredocs" /> <a href="javascript:void(0);" class = "btn btn-danger img_delete" id="delete_'+idname + total_count + '" title =""><?= addslashes($strings["dashboard_delete"]); ?></a><span></span></div></div>';
        $('.'+idname).append(input_field);
        initiate_ajax_upload1(idname + total_count, 'doc');
    }
    $(function () {


        $('.addattachment5').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view.'/attachfive_1/5';?>', function(){
            if($('#attachfive_1').length)
                initiate_ajax_upload1('attachfive_1', 'doc');
        });

        $('.addattachment6').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view.'/attachsix_1/6';?>', function(){
            if($('#attachsix_1').length)
                initiate_ajax_upload1('attachsix_1', 'doc');
        });

        $('.addattachment7').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view.'/attachseven_1/7';?>', function(){
            if($('#attachseven_1').length)
                initiate_ajax_upload1('attachseven_1', 'doc');
        });

        $('.addattachment8').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view.'/attacheight_1/8';?>', function(){
            if($('#attacheight_1').length)
                initiate_ajax_upload1('attacheight_1', 'doc');
        });

        $('.addattachment12').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view.'/attachtwelve_1/12';?>', function(){
            if($('#attachtwelve_1').length)
                initiate_ajax_upload1('attachtwelve_1', 'doc');
        });

        $('.addattachment11').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view.'/attacheleven_1/11';?>', function(){
            if($('#attacheleven_1').length)
                initiate_ajax_upload1('attacheleven_1', 'doc');
        });

        $('.img_delete').live('click', function () {
            var file = $(this).attr('title');
            if (file == file.replace("&", " ")) {
                var id = 0;
            }
            else {
                var f = file.split("&");
                file = f[0];
                var id = f[1];
            }

            var con = confirmdelete(file);//confirm('Are you sure you want to delete "' + file + '"?');
            if (con == true) {
                $.ajax({
                    type: "post",
                    data: 'id=' + id,
                    url: "<?php echo $this->request->webroot;?>documents/removefiles/" + file,
                    success: function (msg) {

                    }
                });
                $(this).parent().parent().remove();

            } else {
                return false;
            }
        });
    });
    function initiate_ajax_upload1(button_id, doc) {

        var button = $('#' + button_id), interval;
        if (doc == 'doc') {
            var act = "<?php echo $this->request->webroot;?>documents/fileUpload/<?php if(isset($id))echo $id;?>";
        } else {
            var act = "<?php echo $this->request->webroot;?>documents/fileUpload/<?php if(isset($id))echo $id;?>";
        }
        new AjaxUpload(button, {
            action: act,
            name: 'myfile',
            onSubmit: function (file, ext) {
                button.text('<?= addslashes($strings["addorder_uploading"]); ?>');
                this.disable();
                interval = window.setInterval(function () {
                    var text = button.text();
                    if (text.length < 13) {
                        button.text(text + '.');
                    } else {
                        button.text('<?= addslashes($strings["addorder_uploading"]); ?>');
                    }
                }, 200);
            },
            onComplete: function (file, response) {
                if (doc == "doc")
                    button.html('<?= addslashes($strings["forms_browse"]); ?>');
                else
                    button.html('<i class="fa fa-image"></i> <?= addslashes(addslashes($strings["clients_addeditimage"])); ?>');

                window.clearInterval(interval);
                this.enable();
                if (doc == "doc") {
                    $('#' + button_id).parent().find('span').text(" " + response);
                    $('.' + button_id + "_doc").val(response);
                    $('#delete_' + button_id).attr('title', response);
                    if(button_id =='addMore1')
                        $('#delete_'+button_id).show();
                } else {
                    $("#clientpic").attr("src", '<?php echo $this->request->webroot;?>img/jobs/' + response);
                    $('#client_img').val(response);
                }
//$('.flashimg').show();
            }
        });
    }
    function fileUpload(ID) {
        // alert(ID);
        // e.preventDefault();

        var $type = $(".tabber.active").find("input[name='document_type']").val(),
            param = {
                type: 'order',
                doc_type: $type,
                order_id: $('#did').val(),
                cid: '<?php echo $cid;?>'
            };
        if ($type == "Consent Form") {
            //get sub content tab active
            var subContent = $(".tab-pane.active #form_tab4").find('.tab-content .tab-pane.active form').attr('id');
            // debugger;
            if (subContent == "form_consent") {
                param.subtype = 'Consent Form';
            } else if (subContent == "form_employment") {
                param.subtype = 'Employment';
            } else if (subContent == "form_education") {
                param.subtype = 'Education';
            }
        }
        var upload = new AjaxUpload("#" + ID, {
            action: "<?php echo $this->request->webroot;?>documents/fileUpload",
            enctype: 'multipart/form-data',
            data: param,
            name: 'myfile',
            onSubmit: function (file, ext) {
            },
            onComplete: function (file, response) {
                if (response != 'error') {
                    $('#' + ID).parent().find('.uploaded').text(response);
                    $('.' + ID).val(response);
                } else {
                    alert('<?= addslashes($strings["addorder_invalidfile"]); ?>');
                }
            }
        });
    }
</script>
