<?php //var_dump($new_req); die();?>
<H3 class="page-title">Settings</H3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?= $this->request->webroot ?>">Dashboard</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="">Settings</a>
        </li>
    </ul>
</div>


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
    if (isset($disabled)) {$is_disabled = 'disabled="disabled"';}
    if (isset($profile)) {$p = $profile;}
    $settings = $Manager->get_settings();
    include_once('subpages/api.php');
    $languages = languages();

function makeselect($is_disabled=false, $Name=""){
    if($Name){
        echo '<select class="form-control" ' . $is_disabled . ' id="'. $Name . '" >';
    }else{
        echo "</select>";
    }
}
function makedropdown($is_disabled, $Name, $TheValue, $Language, $EnglishValues, $FrenchValues = ""){
    makeselect($is_disabled, $Name);
    if ($FrenchValues == ""){ $Language = "English"; }
    $variable = $Language . "Values";
    foreach($$variable as $Key => $Value){
        makedropdownoption($Key, $Value, $TheValue);
    }
    echo '</select>';
}
function makedropdownoption($Key, $Value, $TheValue){
    echo '<option value="' . $Key . '"';
    if($TheValue == $Key){echo "selected='selected'";}
    echo '>' . $Value . '</option>';
}

if (isset($_GET["includeonly"])){
    include('subpages/' . $_GET["includeonly"]);
    return;
}

?>

<div class="clearfix"></div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->



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

        <!--BEGIN TABS-->
        <div class="tabbable tabbable-custom">
            <ul class="nav nav-tabs">
                <?php
                function activetab($activetab, $thistab, $description = ""){
                    if (is_array($thistab)){
                        foreach($thistab as $Name => $description){
                            if(!$activetab){
                                $activetab = $Name;
                            }
                            activetab($activetab, $Name, $description);
                        }
                        return $activetab;
                    } else {
                        echo '<li><a href="#' . $thistab . '" data-toggle="tab"';
                        if ($activetab == $thistab || $activetab == $description) {
                            echo ' class="active"';
                        }
                        echo '>' . $description . '</a></li>';
                    }
                }

                if ($this->request['action'] != 'add') {
                    $activetab="tab_1_5";
                    if(isset($_GET['all_cron']))
                    {
                        $activetab="tab_1_30";
                    }
                    if(isset($_GET['activedisplay'])){$activetab = "tab_1_13";}
                    if ($this->request->session()->read('Profile.admin') && $this->request->session()->read('Profile.super')) {
                        activetab($activetab, array(
                            "tab_1_5" => "Logos",
                            "tab_1_6" => "Pages",
                            "tab_1_8" => "Display",
                            "tab_1_10" => "Packages",
                            "tab_1_14" => "Configuration",
                        ));
                        if($_SERVER['SERVER_NAME'] =='localhost') {
                            activetab($activetab, "tab_1_9", "Clear Data");
                        }
                    }
                    
                    if($this->request->session()->read('Profile.super')) {
                        activetab($activetab, array(
                            "tab_1_30" => "All Crons",
                            "tab_1_16" => "Profile Importer",
                            "tab_1_17" => "Email Editor",
                            "tab_1_18" => "Translation"
                        ));
                        ?>
                            <li>
                                <a href="<?= $this->request->webroot; ?>profiles/producteditor">Product Types</a>
                            </li>
                            <li style="display: none">
                                <a href="<?= $this->request->webroot; ?>profiles/jsonschema">JSON Schema</a>
                            </li>
                        <?php
                        }
                    }
                ?>
            </ul>


            <div class="tab-content">
                <?php
                if ($this->request['action'] != 'add') {
                      if(!isset($_GET['activedisplay']) && !isset($_GET['all_cron'])) {
                        echo '<div class="tab-pane active"  id="tab_1_5">';
                      } else {
                        echo '<div class="tab-pane"  id="tab_1_5">';
                      }
                      include('subpages/profile/logo.php');
                      echo '</div>';
                ?>


                    <div class="tab-pane" id="tab_1_6">
                        <?php include('subpages/profile/page.php'); ?>
                    </div>
                    <div class="tab-pane" id="tab_1_8">
                        <?php include('subpages/profile/client_setting.php'); ?>
                    </div>
                    <div class="tab-pane" id="tab_1_10">
                        <?php include('products.ctp'); //subpages/profile/products.php'); ?>
                    </div>
                     <div class="tab-pane <?php if(isset($_GET['all_cron'])){?>active<?php }?>" id="tab_1_30">
                        <?php /*?><div class="tabbable tabbable-custom">
                            <ul class="nav nav-tabs">

                                <!--li class="active">
                                    <a href="#tab_1_111" data-toggle="tab">Survey Crons</a>
                                </li-->
                                <li class="active">
                                    <a href="#tab_1_112" data-toggle="tab">Requalification Crons</a>
                                </li>
                                <?php
                                    if ($_SERVER['SERVER_NAME'] == "localhost") {
                                        echo '<a style="margin-top: 10px;" class="btn btn-primary" href="' . $this->request->webroot . 'profiles/cron/true">Run the CRON</A>';
                                    }
                                ?>
                            </ul>
                            <div class="tab-content">
                                 <!--div class="tab-pane active" id="tab_1_111">
                                    <?php //include('subpages/profile/survey.php'); ?>
                                </div-->
                                 <div class="tab-pane active" id="tab_1_112">
                                    <?php
                                        include('subpages/profile/requalify.php');
                                        //echo $this->requestAction('tasks/cron');
                                    ?>
                                </div>
                            </div>
                           </div>*/?>
                        
                     </div>
                     <div class="tab-pane" id="tab_1_16">
                        <?php include('subpages/import.php'); ?>
                     </div>
                     <div class="tab-pane" id="tab_1_17">
                        <?php include('subpages/profile/emails.php'); ?>
                     </div>
                     <div class="tab-pane" id="tab_1_18">
                        <?php include('subpages/profile/translation.php'); ?>
                     </div>

                    <div class="tab-pane" id="tab_1_14">
                        <div class="tabbable tabbable-custom">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1_11" data-toggle="tab">Profile Types</a>
                                </li>
                                <li>
                                    <a href="#tab_1_12" data-toggle="tab">Client Types</a>
                                </li>
                                <li>
                                    <a href="#tab_1_13" data-toggle="tab">Documents</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                 <div class="tab-pane active" id="tab_1_11">
                                    <?php include('subpages/profile/profile_types.php'); ?>
                                 </div>
                                 <div class="tab-pane" id="tab_1_12">
                                    <?php include('subpages/profile/client_types.php'); ?>
                                 </div>

                                 <div class="tab-pane" id="tab_1_13">
                                    <?php include('subpages/profile/editdocs.php'); ?>
                                 </div>
                            </div>
                        </div>
                     </div>

                     <?php if($_SERVER['SERVER_NAME'] =='localhost') {?>
                        <div class="tab-pane" id="tab_1_9">
                            <a href="javascript:void(0)" class="btn btn-danger" id="cleardata" onclick="cleardata();">Clear Data</a>
                            <a href="javascript:void(0)" class="btn btn-danger" id="scrambledata" onclick="scrambledata();">Scramble Data</a>
                            <a href="javascript:void(0)" class="btn btn-danger" id="clearcache" onclick="clearcache();">Clear Cache</a>

                            <div class="margin-top-10 alert alert-success display-hide cleardata_flash" style="display: none;">
                                Data Successfully Cleared.
                                <button class="close" data-close="alert"></button>
                            </div>
                        </div>
                    <?php }
                }
                 ?>
                 <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>





<script>
     function initiate_ajax_upload(button_id,type) {
        if(type == 'logo') {
            var url = "<?php echo $this->request->webroot;?>profiles/upload_img/<?php if(isset($id))echo $id;?>";
        }else {
            var url = "<?php echo $this->request->webroot;?>profiles/client_default";
        }
        var button = $('#' + button_id), interval;
        new AjaxUpload(button, {
            action: url,
            name: 'myfile',
            onSubmit: function (file, ext) {
                button.text('Uploading');
                this.disable();
                interval = window.setInterval(function () {
                    var text = button.text();
                    if (text.length < 13) {
                        button.text(text + '.');
                    } else {
                        button.text('Uploading');
                    }
                }, 200);
            },
            onComplete: function (file, response) {
                button.html('<i class="fa fa-image"></i> Add/Edit Image');
                window.clearInterval(interval);
                this.enable();
                if(type == 'logo') {
                    $("#clientpic").attr("src", '<?php echo $this->request->webroot;?>img/profile/' + response);
                    $('#client_img').val(response);
                } else {
                   //alert(response);
                    if(response != "error"){
                        $(".default_image").attr("src", '<?php echo $this->request->webroot;?>img/clients/' + response);
                        $('.flash').show();
                    } else {
                        $('.flash1').show();
                    }
                    //$('#client_img').val(response);
                }
            }
        });
    }

     function clearcache(){
         $(this).attr("disabled", "disabled");
         var dn = confirm("Confirm clear cache Data.");
         if (dn == true) {
             $.ajax({
                 url: "<?php echo $this->request->webroot;?>profiles/doop/clear_cache",
                 type: "post",
                 success: function (msg) {
                     $('#clearcache').removeAttr("disabled");
                     $(".cleardata_flash").show();
                     $(".cleardata_flash").html(msg);
                 }
             });
         }
     }

     function scrambledata(){
         $(this).attr("disabled", "disabled");
         var dn = confirm("Confirm scramble Database Data.");
         if (dn == true) {
             $.ajax({
                 url: "<?php echo $this->request->webroot;?>profiles/scrambledata",
                 type: "post",
                 success: function (msg) {
                     $('#scrambledata').removeAttr("disabled");
                     $(".cleardata_flash").show();
                     $(".cleardata_flash").html(msg);
                 }
             });
         }
     }

    function cleardata(){
            $(this).attr("disabled", "disabled");
            var dn = confirm("Confirm Clear Database Data.");
            if (dn == true) {
                $.ajax({
                    url: "<?php echo $this->request->webroot;?>profiles/cleardb",
                    type: "post",
                    success: function (msg) {
                        $('#cleardata').removeAttr("disabled");
                        $(".cleardata_flash").show();
                        $(".cleardata_flash").html(msg);
                    }
                });
            }
     }

        $(function () {

        $('.addsubdoc').click(function(){
            <?php
                foreach($languages as $language){
                   if($language == "English"){$language = "";}
                   echo "var subname" . $language ." = $('.subdocname" . $language . "').val();";
               }
           ?>
           if(subname == ''  ) {
                $('.flashSubDoc1').show();
                $('.flashSubDoc').hide();
                $('.subdocname').focus();
                        $('html,body').animate({
                                scrollTop: $('.page-title').offset().top
                            },
                            'slow');
                return false;
           } else {
               var data = 'languages=<?= implode(",", $languages); ?>'<?php //'subdocumentname=' + subname + '&subdocumentnameFrench=' + subnameFrench
                     foreach($languages as $language){
                        if($language == "English"){$language = "";}
                       echo " + '&subdocumentname" . $language . "=' + subname" . $language;
                    }
                ?>;
               $.ajax({
                   url: '<?php echo $this->request->webroot;?>clients/check_document',
                   data: data,
                   type: 'post',
                   success: function (res) {//alert(res);
                       if (res == '1') {
                           //alert(res);
                           $('.flashSubDoc').show();
                           $('.flashSubDoc1').hide();
                           $('.subdocname').focus();
                           $('html,body').animate({
                                   scrollTop: $('.page-title').offset().top
                               },
                               'slow');

                           return false;
                       } else {
                           window.location = '<?php echo $this->request->webroot;?>clients/addsubdocs/?'<?php
                                 foreach($languages as $language){
                                    if($language == "English"){$language = "";}
                                    echo " + '&sub" . $language . "=' + subname" . $language;
                                }
                                echo " + '&languages=" . implode(",", $languages) . "'";
                            ?>;
                       }
                   }
               });
            }
        });
        });

    $(function () {
        $('.editsubdoc').click(function(){
            $(this).html('Saving..');
            var id = $(this).attr('id').replace('subbtn','');
            <?php
                 foreach($languages as $language){
                    if($language == "English"){$language = "";}
                    echo "var subname" . $language ." = $('#editsubdocname" . $language . "_'+id).val();";
                }
            ?>
            var color = $('#select_color_'+id).val();
            var icon = $('#select_icon_'+id).val();
            var product = $('#select_product_'+id).val();

            var msg = '';
            var nameId = 'msg_'+id; //
            $('#flasheditSub1_'+id).hide();
                $('#flasheditSub_'+id).hide();
                $('#flashSelectColor_'+id).hide();
           if(!color && subname == '' && subnameFrench =='') {
                $('#flasheditSub1_'+id).show();
                $('#flasheditSub_'+id).hide();
                $('#flashSelectColor_'+id).show();
                $('#editsubdocname_'+id).focus();
                        $('html,body').animate({
                                scrollTop: $('#edit_sub_'+id).offset().top
                            },
                            'slow');
            $('#subbtn'+id).html('Save');
                        return false;
           } else if(!color && subname != '' && subnameFrench != '') {

            /**************************************************************************************************/


            $.ajax({
                url: '<?php echo $this->request->webroot;?>clients/check_document/'+id,
                data: 'languages=<?= implode(",", $languages); ?>'<?php //'subdocumentname=' + subname + '&subdocumentnameFrench=' + subnameFrench
                     foreach($languages as $language){
                        if($language == "English"){$language = "";}
                       echo " + '&subdocumentname" . $language . "=' + subname" . $language;
                    }
                ?>,
                type: 'post',
                success: function (res) {//alert(res);
                    if (res == '1') {
                        //alert(res);
                        $('#flasheditSub_'+id).show();
                        $('#flasheditSub1_'+id).hide();
                        $('#flashSelectColor_'+id).show();
                        $('#editsubdocname_'+id).focus();
                        $('html,body').animate({
                                scrollTop: $('#edit_sub_'+id).offset().top
                            },
                            'slow');
            $('#subbtn'+id).html('Save');
                        return false;
                    } else {
                        $('#flasheditSub1_'+id).hide();
                        $('#flasheditSub_'+id).hide();
                        $('#flashSelectColor_'+id).show();
                        $('#select_color_'+id).focus();
                                $('html,body').animate({
                                        scrollTop: $('#edit_sub_'+id).offset().top
                                    },
                                    'slow');
                         $('#subbtn'+id).html('Save');
                        return false;
                    }
                }
            });


            /****************************************************************************************************/

           } else if(color && subname == '' && subnameFrench == '') {
                $('#flasheditSub1_'+id).show();
                $('#flasheditSub_'+id).hide();
                $('#flashSelectColor_'+id).hide();
                $('#editsubdocname_'+id).focus();
                        $('html,body').animate({
                                scrollTop: $('#edit_sub_'+id).offset().top
                            },
                            'slow');
            $('#subbtn'+id).html('Save');
                        return false;
           } else if(color && subname != '' ) {
            $.ajax({
                url: '<?php echo $this->request->webroot;?>clients/check_document/'+id,
                data: 'languages=<?= implode(",", $languages); ?>'<?php //'subdocumentname=' + subname + '&subdocumentnameFrench=' + subnameFrench
                     foreach($languages as $language){
                        if($language == "English"){$language = "";}
                       echo " + '&subdocumentname" . $language . "=' + subname" . $language;
                    }
                ?>,
                type: 'post',
                success: function (res) {//alert(res);
                    if (res == '1') {
                        //alert(res);
                        $('#flasheditSub_'+id).show();
                        $('#flasheditSub1_'+id).hide();
                        //$('#flashSelectColor_'+id).hide();
                        $('#editsubdocname_'+id).focus();
                        $('html,body').animate({
                                scrollTop: $('#edit_sub_'+id).offset().top
                            },
                            'slow');
            $('#subbtn'+id).html('Save');

                        return false;
                    } else {
                            msg = '<span class="msg" style="color:#45B6AF">Saved</span>'; //searchforhere
                            var url = '<?php echo $this->request->webroot;?>clients/addsubdocs/?'<?php
                                //sub=' + subname + '&subFrench=' + subnameFrench
                                 foreach($languages as $language){
                                    if($language == "English"){$language = "";}
                                    echo " + '&sub" . $language . "=' + subname" . $language;
                                }
                                echo " + '&languages=" . implode(",", $languages) . "'";
                            ?> + '&updatedoc_id=' + id;
                            url = url + "&icon=" + icon + "&productid=" + product;
                            if(color){url = url + '&color=' + color;}
                    $.ajax({
                        url: url,success:function(){
                            $('#edit_sub_'+id).hide();
                            $('#'+nameId).show();
                        $('#'+nameId).html(msg);
                        $('#sub_'+id).html(subname);
                        $('#subbtn'+id).html('Save');
                        }
                        });
                    }
                }
            });
            }
        });
         initiate_ajax_upload('client_default','client');
        <?php
        if(isset($id))
        {
         ?>
            initiate_ajax_upload('clientimg','logo');
            $('.addclientz').click(function () {
                var client_id = $(this).val();
                var addclient = "";
                var msg = '';
                var nameId = 'msg_' + $(this).val();
                if ($(this).is(':checked')) {
                    addclient = '1';
                    msg = '<span class="msg" style="color:#45B6AF">Added</span>';
                }
                else {
                    addclient = '0';
                    msg = '<span class="msg" style="color:red">Removed</span>';
                }

                $.ajax({
                    type: "post",
                    data: "client_id=" + client_id + "&add=" + addclient + "&user_id=" +<?php echo $id;?>,
                    url: "<?php echo $this->request->webroot;?>clients/addprofile",
                    success: function () {
                        $('.' + nameId).html(msg);
                    }
                })
            });
        <?php
         }
         else
         {?>
            $('.addclientz').click(function () {
                var nameId = 'msg_' + $(this).val();
                var client_id = "";
                var msg = '';
                $('.addclientz').each(function () {
                    if ($(this).is(':checked')) {
                        msg = '<span class="msg" style="color:#45B6AF"> Added</span>';
                        client_id = client_id + "," + $(this).val();
                    }
                    else {
                        msg = '<span class="msg" style="color:red"> Removed</span>';
                    }
                });

                client_id = client_id.substr(1, length.client_id);
                $('.client_profile_id').val(client_id);
                $('.' + nameId).html(msg);

            });
        <?php
        }
        ?>
        $('#save_client_p1').click(function () {

            $('#save_client_p1').text('Saving..');

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
                    password: "Please enter password",
                    retype_password: "Password do not match"
                },
                submitHandler: function () {
                    $('#pass_form').submit();
                },
            });
        });

    });
</script>

<script>
    <?php
    if($this->request->params['action']=='edit')
    {
        ?>

        function searchClient() {
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
        }
    <?php
    }  else  {
    ?>
        function searchClient() {
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
        }
    <?php   }   ?>
    $(function () {
        $('#tab_1_30').load('<?php echo $this->request->webroot;?>tasks/cron/<?php if(isset($_GET['clientid']))echo '?clientid='.$_GET['clientid'];?>');
        $('.scrolldiv').slimScroll({
            height: '250px'
        });

    });
</script>
<style>
    .portlet-body {
        min-height: 250px !important;
    }
</style>
