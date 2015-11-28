<?php
    include_once('subpages/api.php');
    $settings = $Manager->get_settings();
    $language = $this->request->session()->read('Profile.language');
    $issuper = $this->request->session()->read('Profile.super');
    $strings = CacheTranslations($language, array("clients_%", "forms_%", "infoorder_%", "index_%", "documents_document", "application_process"), $settings);//,$registry);//$registry = $this->requestAction('/settings/getRegistry');
    if ($language == "Debug") {
        $Trans = " [Trans]";
    } else {
        $Trans = "";
    }

    $ProfileType = $Manager->get_entry("profile_types", $Manager->read("profile_type"), "id");

    include_once 'subpages/filelist.php';
    $delete = isset($disabled);
    $is_disabled = '';
    if (isset($disabled)) {
        $is_disabled = 'disabled="disabled"';
    }
    if (isset($client)) {
        $c = $client;
    }

    $sidebar = $Manager->loadpermissions($Me, "sidebar");;
    $getprofile = $this->requestAction('clients/getProfile/' . $id);
    $getcontact = $this->requestAction('clients/getContact/' . $id);
    $param = $this->request->params['action'];

    $action = ucfirst($param);
    if (isset($_GET["view"])) {
        $action = "View";
    }
    if ($action == "Add") {
        $action = "Create";
    }
    $title = $strings["clients_" . strtolower($action) . "client"];
    //includejavascript($strings);
    loadreasons($action, $strings, true);

    JSinclude($this, array("assets/global/plugins/fancybox/source/jquery.fancybox.css", "assets/admin/pages/css/portfolio.css" ), "PAGE LEVEL STYLES");
?>

<h3 class="page-title">
    <?= $title; ?>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?= $this->request->webroot . '">' . $strings["dashboard_dashboard"]; ?></a>
            <i class=" fa fa-angle-right"></i>
        </li>
        <li>
            <a href=""><?= $title; ?></a>
        </li>
    </ul>
    <!--a href="javascript:window.print();" class="floatright btn btn-primary">Print</a-->
    <?php
        if (isset($disabled) || isset($_GET['view'])) { ?>
            <a href="javascript:window.print();" class="floatright btn btn-primary"><?= $strings["dashboard_print"]; ?></a>
        <?php }

        if (isset($client) && $sidebar->client_delete == '1' && $param != 'add') { ?>
            <a href="<?= $this->request->webroot; ?>clients/delete/<?= $client->id; ?><?= (isset($_GET['draft'])) ? "?draft" : ""; ?>"
               onclick="return confirm('Are you sure you want to delete <?= h($client->company_name) ?>?');"
               class="floatright btn btn-danger btnspc"><?= $strings["dashboard_delete"]; ?></a>
        <?php }
        if (isset($client) && $sidebar->client_edit == '1' && isset($_GET['view'])) {
            echo $this->Html->link(__($strings["dashboard_edit"]), ['controller' => 'clients', 'action' => 'edit', $client->id], ['class' => 'floatright btn btn-primary btnspc']);
        } else if (isset($client) && $param == 'edit') {
            ?>
            <a href="<?= $this->request->webroot; ?>clients/edit/<?= $client->id; ?>?view"
               class='floatright btn btn-primary btnspc'><?= $strings["dashboard_view"]; ?></a>
            <?php
            if($this->request->session()->read('debug')){
                echo '<A ONCLICK="autofill2(false);" class="floatright btn btnspc btn-warning">' . $strings["dashboard_autofill"] . '</A>';
            }

        }

        if($sidebar->profile_list) {
            echo '<A HREF="' . $this->request->webroot . 'profiles/index?filter_by_client=' . $id . '" class="floatright btn btnspc btn-primary">' . $strings["index_listprofile"] . '</A>';
        }
        echo "</div>";
    ?>

    <div class="row ">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->

            <div class="row profile-account">
                <div class="col-md-3" align="center">
                    <img class="img-responsive" id="clientpic" alt=""
                         src="<?= clientimage($this->request->webroot, $settings, $client); ?>"/>

                    <div class="form-group">
                        <label class="sr-only"
                               for="exampleInputEmail22"><?= $strings["clients_addeditimage"]; ?></label>

                        <div class="input-icon">
                            <a class="btn btn-xs btn-primary" href="javascript:void(0)" id="clientimg">
                                <i class="fa fa-image"></i>
                                <?= $strings["clients_addeditimage"]; ?>
                            </a>

                        </div>
                    </div>

                    <!--php  if (isset($client_docs)) { listfiles($client_docs, "img/jobs/",'client_doc',$delete,  2); } ?-->

                </div>

                <div class="col-md-9">


                    <div class="clearfix"></div>

                    <div class="portlet box grey-salsa">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-briefcase"></i><?= $strings["clients_manager"]; ?>
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1_1" data-toggle="tab"><?= $strings["clients_info"]; ?></a>
                                </li>
                                <?php if ($this->request['action'] != "add" && !isset($_GET['view'])) {
                                    ?>

                                    <!--li>
                                        <a href="#tab_1_6" data-toggle="tab"><?= $strings["clients_requalify"] ?></a>
                                    </li-->


                                    <li>
                                        <a href="#tab_1_4" data-toggle="tab"><?= $strings["clients_products"]; ?></a>
                                    </li>

                                    <li>
                                        <a href="#tab_1_2" data-toggle="tab"><?= $strings["documents_document"]; ?></a>
                                    </li>

                                    <!--<li>
                                        <a href="#tab_1_7" data-toggle="tab"><?= $strings["application_process"]; ?></a>
                                    </li>-->

                                    <li>
                                        <a href="#tab_1_3"
                                           data-toggle="tab"><?php echo (!isset($_GET['view'])) ? $strings["clients_assigntoprofile"] : $strings["clients_assignedto"]; ?></a>
                                    </li>


                                    <?php
                                }
                                ?>


                            </UL>
                        </div>

                        <div class="portlet-body form">
                            <div class="form-body" style="padding-bottom: 0px;">
                                <div class="tab-content">
                                    <?php if (!isset($_GET['activedisplay'])){ ?>
                                        <div class="tab-pane active" id="tab_1_1">
                                        <div id="tab_1_1" class="tab-pane active">
                                    <?php }else{ ?>
                                        <div class="tab-pane" id="tab_1_1">
                                        <div id="tab_1_1" class="tab-pane">
                                    <?php }
                                        include("subpages/clients/info.php");
                                    ?>

                                                </div>
                                            </div>
                                        </div>


                                        <?php
                                            echo '<div';
                                            if (isset($_GET["products"])) {
                                                echo " active";
                                            }
                                            echo ' class="tab-pane" id="tab_1_4">';
                                            include('subpages/clients/products.php');
                                            echo "</DIV>";

                                            if ($this->request['action'] != "add" && !isset($_GET['view'])){
                                                if (isset($_GET['activedisplay'])){
                                                    ECHO '<div class="tab-pane active" id="tab_1_2">';
                                                } else {
                                                    echo '<div class="tab-pane" id="tab_1_2">';
                                                }

                                                include("subpages/clients/documents.php");

                                            echo '</div>';
                                    } ?>
                                            <div class="tab-pane" id="tab_1_3" style="min-height: 600px;">
                                                <?php
                                                    include('subpages/clients/recruiter_contact_table.php');
                                                ?>
                                            </div>
                                            <!--div class="tab-pane" id="tab_1_6" style="">
                                                <?php
                                                    if ($action != "Create") {
                                                            //include('subpages/clients/requalify.php');
                                                    }
                                                ?>
                                            </div-->

                                            <!--<div class="tab-pane" id="tab_1_7" style="">
                                                <?php
                                                    //include('subpages/clients/application_process.php');
                                                ?>
                                            </div>-->


                                            <!-- END SAMPLE FORM PORTLET-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(function () {
                    var tosend = '';
                    $('.sortable tbody').sortable({
                        items: "tr:not(.myclass)",
                        update: function (event, ui) {

                            $('.sublisting').each(function () {
                                var id = $(this).attr('id');
                                id = id.replace('subd_', '');
                                if (tosend == '') {
                                    tosend = id;
                                }
                                else
                                    tosend = tosend + ',' + id;
                            });
                            $.ajax({
                                url: '<?php echo $this->request->webroot;?>clients/updateOrder/<?php if(isset($id))echo $id;?>',
                                data: 'tosend=' + tosend,
                                type: 'post'
                            });
                            $.ajax({
                                url: '<?php echo $this->request->webroot;?>clients/updateOrderApplication/<?php if(isset($id))echo $id;?>',
                                data: 'tosend=' + tosend,
                                type: 'post'
                            });
                            tosend = '';


                        }
                    });

                   /* var tosend2 = '';
                    $('.sortable2 tbody').sortable({
                        items: "tr:not(.myclass2)",
                        update: function (event, ui) {

                            $('.sublisting2').each(function () {
                                var id = $(this).attr('id');
                                id = id.replace('subd_', '');
                                if (tosend2 == '') {
                                    tosend2 = id;
                                }
                                else
                                    tosend2 = tosend2 + ',' + id;
                            });
                            $.ajax({
                                url: '<?php echo $this->request->webroot;?>clients/updateOrderApplication/<?php if(isset($id))echo $id;?>',
                                data: 'tosend=' + tosend2,
                                type: 'post'
                            });
                            tosend = '';


                        }
                    });*/
                    <?php
                    if(isset($_GET['view']))
                    {
                        ?>
                    $('#client_form input').each(function () {
                        $(this).attr("disabled", 'disabled');
                    });
                    $('#client_form a').hide();
                    $('.uploaded').show();
                    $('#clientimg').hide();
                    $('#client_form textarea').each(function () {
                        $(this).attr("disabled", 'disabled');
                    });

                    $('#client_form select').each(function () {
                        $(this).attr("disabled", 'disabled');
                    });

                    $('.recruiters input').each(function () {
                        $(this).attr("disabled", 'disabled');
                    });
                    $('#searchProfile').hide();
                    $('#save_client_p1').hide();
                    $('#attach_label').hide();
                    <?php }?>
                    $('input [type="email"]').keyup(function () {
                        $(this).removeAttr('style');
                    });
                    initiate_ajax_upload('clientimg', 'asdas');
                    initiate_ajax_upload('addMore1', 'doc');
                    <?php
                    if(isset($id))
                    {
                    ?>

                    $('#save_display1').click(function () {
                        $('#save_display1').text('Saving..');
                        //var str = $('#displayform1 input.fororder').serialize();
                        var str = $('#displayform1 input.fororder').serialize();
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>clients/displaySubdocs/<?php echo $id;?>',
                            data: str,
                            type: 'post',
                            success: function (res) {
                                $('.flash').show();
                                //$('#save_display1').text('<?= $strings["forms_savechanges"]; ?>');
                            }
                        });
                        
                        var str2 = $('#displayform1 input.forapp').serialize();
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>clients/displaySubdocsApplication/<?php echo $id;?>',
                            data: str2,
                            type: 'post',
                            success: function (res) {
                                $('.flash').show();
                                $('#save_display1').text('<?= $strings["forms_savechanges"]; ?>');
                            }
                        })
                    });

                    /*$('#save_display7').click(function () {
                        $('#save_display7').text('Saving..');
                        var str = $('#displayform7 input').serialize();
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>clients/displaySubdocsApplication/<?php echo $id;?>',
                            data: str,
                            type: 'post',
                            success: function (res) {
                                $('.flash').show();
                                $('#save_display1').text('<?= $strings["forms_savechanges"]; ?>');
                            }
                        })
                    });*/
                    <?php
                    }
                    ?>
                    $('.save_client_all').submit(function (event) {
                        event.preventDefault();
                        if (!checkalltags("")) {
                            return false;
                        }

                        $('.overlay-wrapper').show();
                        $('#save_client_p1').text('<?= $strings["forms_saving"];?>');
                        var str = '';
                        $('.moredocs').each(function () {
                            if ($(this).val() != "") {
                                if (str == '')
                                    str = 'client_doc[]=' + $(this).val();
                                else
                                    str = str + '&client_doc[]=' + $(this).val();
                            }

                        });
                        if (str == '') {
                            str = $('#tab_1_1 input').serialize();
                        }
                        else {
                            str = str + '&' + $('#tab_1_1 input').serialize();
                        }
                        if (str == '') {
                            str = $('#tab_1_1 select').serialize();
                        }
                        else {
                            str = str + '&' + $('#tab_1_1 select').serialize();
                        }
                        str = str + '&customer_type=' + $('#customer_type').val();
                        str = str + '&division=' + $('#division').val();
                        str = str + '&referred_by=' + $('#referred_by').val();
                        str = str + '&invoice_terms=' + $('#invoice_terms').val();
                        str = str + '&description=' + $('#description').val();

//alert(str);           

                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>clients/saveClients/<?php echo $id?>',
                            data: str,
                            type: 'post',
                            success: function (res) {

                                if (res != 'e' && res != 'email' && res != 'Invalid Email') {
                                    window.location = '<?php echo $this->request->webroot;?>clients/edit/' + res + '?flash';
                                }
                                else if (res == 'email') {
                                    alert('<?= addslashes($strings["dashboard_emailexists"]); ?>');
                                }
                                else if (res == 'Invalid Email') {
                                    $('#tab_1_1 input[type="email"]').focus();
                                    $('#tab_1_1 input[type="email"]').attr('style', 'border-color:red');
                                    $('html,body').animate({
                                            scrollTop: $('#tab_1_1').offset().top
                                        },
                                        'slow');
                                }

                                else {
                                    alert('<?= addslashes($strings["clients_notsaved"]); ?>');
                                }
                                $('#save_client_p1').text('<?= addslashes($strings["forms_save"]);?>');
                            }
                        })
                    });
                });

                $('#addMoredoc').click(function () {
                    var total_count = $('.docMore').data('count');
                    $('.docMore').data('count', parseInt(total_count) + 1);
                    total_count = $('.docMore').data('count');
                    var input_field = '<div  class="form-group"><div class="col-md-12" style="margin-top:10px;"><a href="javascript:void(0);" id="addMore' + total_count + '" class="btn btn-primary"><?= addslashes($strings["forms_browse"]); ?></a><input type="hidden" name="client_doc[]" value="" class="addMore' + total_count + '_doc moredocs" /><a href="javascript:void(0);" class = "btn btn-danger img_delete" id="delete_addMore' + total_count + '" title =""><?= $strings["dashboard_delete"];?></a><span></span></div></div>';
                    $('.docMore').append(input_field);
                    initiate_ajax_upload('addMore' + total_count, 'doc');

                });
                //delete image
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

                    var con = confirm('Are you sure you want to delete "' + file + '"?');
                    if (con == true) {
                        $.ajax({
                            type: "post",
                            data: 'id=' + id,
                            url: "<?php echo $this->request->webroot;?>clients/removefiles/" + file,
                            success: function (msg) {

                            }
                        });
                        $(this).parent().parent().remove();

                    }
                    else
                        return false;
                });
                var removeLink = 0;// this variable is for showing and removing links in a add document
                function addMore(e, obj) {
                    e.preventDefault();

                    var total_count = $('.docMore').data('count');
                    $('.docMore').data('count', parseInt(total_count) + 1);
                    total_count = $('.docMore').data('count');
                    var input_field = '<div style="display:block;margin:5px;"><a href="javascript;void(0);" id="addMore' + total_count + '" class="btn btn-primary"><?= addslashes($strings["forms_browse"]); ?></a><span></span><input type="hidden" name="client_doc[]" value="" class="addMore' + total_count + '_doc moredocs" /></div>';
                    $('.docMore').append(input_field);
                    if (parseInt(total_count) > 1 && removeLink == 0) {
                        removeLink = 1;
                        $('#addMoredoc').after('<a href="#" id="removeMore" class="btn btn-danger" onclick="removeMore(event,this)"><?= addslashes($strings["forms_removelast"]);?></a>');
                        initiate_ajax_upload('addMore' + total_count, 'doc');
                    }
                }

                function removeMore(e, obj) {
                    e.preventDefault();
                    var total_count = $('.docMore').data('count');
//$('.docMore input[type="file"]:last').remove();
                    $('.docMore div:last-child').remove();
                    $('.docMore').data('count', parseInt(total_count) - 1);
                    total_count = $('.docMore').data('count');
                    if (parseInt(total_count) == 1) {
                        $('#removeMore').remove();
                        removeLink = 0;
                    }
                }
            </script>


            <script>
                function initiate_ajax_upload(button_id, doc) {
                    var button = $('#' + button_id), interval;
                    if (doc == 'doc') {
                        var act = "<?php echo $this->request->webroot;?>clients/upload_all/<?php if(isset($id))echo $id;?>";
                    } else {
                        var act = "<?php echo $this->request->webroot;?>clients/upload_img/<?php if(isset($id))echo $id;?>";
                    }
                    new AjaxUpload(button, {
                        action: act,
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
                            if (doc == "doc") {
                                button.html('<?= addslashes($strings["forms_browse"]);?>');
                            } else {
                                button.html('<i class="fa fa-image"></i> <?= addslashes($strings["clients_addeditimage"]); ?>');
                            }

                            window.clearInterval(interval);
                            this.enable();
                            if (doc == "doc") {
                                $('#' + button_id).parent().find('span').text(" " + response);
                                $('.' + button_id + "_doc").val(response);
                                $('#delete_' + button_id).attr('title', response);
                            }
                            else {
                                $("#clientpic").attr("src", '<?php echo $this->request->webroot;?>img/jobs/' + response);
                                $('#client_img').val(response);
                            }
//$('.flashimg').show();
                        }
                    });
                }
            </script>