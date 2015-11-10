<?php
    $param = $this->request->params['action'];
    include_once 'subpages/filelist.php';
    if(!isset($_GET['type'])) {$_GET['type'] = 0;}
    $doc_ext = array('pdf','doc','docx','txt','csv','xls','xlsx');
    $img_ext = array('jpg','jpeg','png','bmp','gif');
    $is_disabled = '';
    $view ="";
    if (isset($disabled)) {
        $is_disabled = 'disabled="disabled"';
        $view = "view";
    }

    $settings = $this->requestAction('settings/get_settings');
    $action = ucfirst($param);
    if ($action == "Add") {
        $action = "Create";
        if(isset($did) && $did) { $action = "Edit";}
    }

    if (isset($this->request->params['pass'][0])) {
        $ClientID = $this->request->params['pass'][0];
    }

    if (isset($this->request->params['pass'][1])) {
        $id1 = $this->request->params['pass'][1];
        $id2="?type=".$_GET['type'];
        if (isset($_GET['order_id'])) { $id2= '?order_id=' . $_GET['order_id']; }
    }

    include_once('subpages/api.php');
    $language = $this->request->session()->read('Profile.language');
    $strings = CacheTranslations($language, array("documents_%", "forms_%", "clients_addeditimage", "infoorder_selectclient"), $settings);//,$registry);//$registry = $this->requestAction('/settings/getRegistry');
    if($language == "Debug") { $Trans = " [Trans]";} else { $Trans = ""; }
    $title = $strings["index_" . strtolower($action) . "document"];
    printCSS($this);
    JSinclude($this, "js/jquery.easyui.min.js");
    loadreasons($action, $strings, true);
    $Debug = $this->request->session()->read('debug') || $language == "Debug";
?>
<h3 class="page-title">
    <?= $title; ?>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?= $this->request->webroot; ?>"><?= $strings["dashboard_dashboard"]; ?></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href=""><?= $title; ?>
            </a>
        </li>
    </ul>

    <?php
        if($Debug){
            echo '<A ONCLICK="autofill2(false);" class="floatright btn btn-warning">' . $strings["dashboard_autofill"] . '</A>';
        }
        if (isset($disabled)) {
            echo ' <a href="javascript:window.print();" class="floatright btn btn-primary">' . $strings["dashboard_print"] . '</a>';
        }
        $opposite = "edit"; $url="add";
        if ($action=="Edit"){ $opposite = "view"; $url= "view";}
        if($did){
            if(str_replace('?','',$id1.$id2)==$id1.$id2)
                $sep = '?';
            else
                $sep = '&';}
        if (isset($this->request->params['pass'][1])&& !isset($_GET['order_id'])) { echo '<a href="../../' . $url . '/' . $ClientID . "/" . $id1 . $id2 .$sep. 'type='.$_GET['type'].'" class="floatright btn btn-info btnspc">' . $strings["dashboard_" . $opposite] . '</a>'; }


        function makeportlet($did, $color="", $Title=""){
            if (strlen($Title)>0){
                echo '<div class="row">
                            <div class="col-md-12">
                            <div class="portlet box ' . $color .'">

                                    <div class="portlet-title">
                                        <div class="caption">
                                            ' . $Title . '
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                    <div class="form-body" id="parentdiv" style="padding-bottom: 0px;">
                                                    <div class="tab-content" ID="tab0">';
                printdocumentinfo($did);
            }
        }

        function docname($ID, $subdoccli, $language) {
            $keyname = getFieldname("title", $language);
            $object = getIterator($subdoccli, "sub_id", $ID)->subtype;
            if ($language == 'Debug') { return $object->English . " [Trans]";}
            return $object->$keyname;
        }
    ?>


    <!--a href="" class="floatright btn btn-success">Re-Qualify</a>
    <a href="" class="floatright btn btn-info">Add to Task List</a-->

</div>


<div class="row">
    <div class="col-md-12">
        <?php
            $tab = 'nodisplay';
        ?>
        <div class="form">
            <div class="form-horizontal steps">
                <div class="">
                    <?php

                        if ($param != 'view') {
                            $tab = 'tab-pane';
                            $doc = $doc_comp->getDocument();
                            ?>
                            <?php
                        }
                    ?>
                    <?php
                        $doc_count = 0;
                        if(isset($mod->uploaded_for)){
                            $driver = $mod->uploaded_for;
                            $_GET['driver'] = $mod->uploaded_for;
                        }
                        else
                            $driver=0;
                    ?>
                    <div class="col-md-4" style="padding-right: 0;  padding-left: 0;">
                        <div class="portlet box blue no-print" style="border:0;">
                            <div class="portlet-title">
                                <div class="caption"> <?= $strings["documents_docoptions"]; ?> </div>
                            </div>
                            <div class="portlet-body form" >

                                <div class="col-md-12 clients_select no-print" style="margin: 10px 0;padding:0">

                                    <select name="clients" class="form-control select2me no-print" data-placeholder="<?=$settings->client?>" id="changeclient" <?php if($this->request->params['action']=='view'){?>disabled="disabled"<?php }?> onchange="changeclient_onchange();">
                                        <option value="0"><?= $strings["infoorder_selectclient"]; ?></option>
                                        <?php
                                            $profile_id = $this->request->session()->read('Profile.id');
                                            foreach ($clients as $c){
                                                $profiles = explode(",", $c->profile_id);

                                                if(in_array($profile_id, $profiles)|| $this->request->session()->read('Profile.super'))
                                                { ?>
                                                    <option value="<?php echo $c->id;?>" <?php if($cid ==$c->id)echo "selected='selected'";?>><?php echo $c->company_name;?></option>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </select>

                                </div>
                                <?php
                                    if($cid)
                                    {
                                        ?>

                                        <div class="col-md-12 doc_select" style="margin: 10px 0;padding:0">

                                            <select name="doctype" class="form-control select2me no-print" data-placeholder="<?= $strings["documents_selectdocument"]; ?>" onchange="window.location='<?php echo $this->request->webroot;?>documents/add/<?php echo $cid;?>?type='+$(this).val()" <?php if($this->request->params['action']=='view' || $this->request->params['pass']['1']!=''){?>disabled="disabled"<?php }?>>
                                                <option value="0"><?= $strings["documents_selectdocument"]; ?></option>
                                                <?php
                                                    $doc = $doc_comp->getDocument('document');
                                                    $subdoccli = $this->requestAction('/clients/getSubCli2/'.$cid.'/document/true/true');

                                                    $subdoccli2 = $subdoccli;
                                                    //$doc2 = $doc;
                                                    $i = 2;
                                                    $end = 0;
                                                    $k_c=0;
                                                    $index=0;
                                                    $keyname = getFieldname("title", $language);

                                                    foreach ($subdoccli as $sd) {
                                                        $index+=1;
                                                        $d = $sd->subtype;//$this->requestAction('/clients/getFirstSub/'.$sd->sub_id);
                                                        ?>
                                                        <option value="<?php echo $d->id;?>" <?php if($_GET['type'] ==$d->id)echo "selected='selected'";?>><?php echo ucfirst(str_replace('_',' ',$d->$keyname . $Trans));?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>

                                        </div>


                                        <div class="col-md-12" style="margin: 10px 0;padding:0 ;">

                                            <?php $dr_cl = $doc_comp->getDriverClient(0, $cid);?>
                                            <select class="form-control select2me no-print" data-placeholder="No Driver"
                                                    id="selecting_driver" <?php if ($driver || $this->request->params['action']=='view' ){ ?>disabled="disabled"<?php } ?>>
                                                <option value="0"><?= $strings["documents_nodriver"]; ?>
                                                </option>
                                                <?php


                                                    foreach ($dr_cl['driver'] as $dr) {

                                                        $driver_id = $dr->id;
                                                        ?>
                                                        <option value="<?php echo $dr->id; ?>"
                                                                <?php if ($dr->id == $driver){ ?>selected="selected"<?php } ?>><?php echo $dr->fname . ' ' . $dr->mname . ' ' . $dr->lname ?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>

                                            <input type="hidden" name="did" value="<?php echo $did; ?>" id="did"/>
                                            <?php
                                                if(isset($_GET['type']))
                                                {
                                                    $sid = $_GET['type'];
                                                }
                                                else
                                                    $sid = 0;
                                            ?>
                                            <input type="hidden" name="sub_doc_id" value="<?php echo $sid; ?>" id="sub_id"/>

                                        </div>
                                     <div class="clearfix"></div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="moredocxs">

                        <?php

                            $controller = $this->request->params['controller'];
                            $controller = strtolower($controller);
                        ?>

                        <?php
                            if($_GET['type']==1)
                            {
                            $dx = $subdoc->find()->where(['id'=>$_GET['type']])->first();
                        ?>
                        <div class="subform1">
                            <?php
                                if($controller == 'documents' ) {
                                    $colr = $this->requestAction('/documents/getColorId/1');
                                    if(!$colr){$colr = $class[0];}
                                    makeportlet($did, $colr,docname(1, $subdoccli, $language));
                                } else {

                                }
                                include('subpages/documents/company_pre_screen_question.php');
                                if($controller == 'documents' ) {
                                    echo '</div></div></div></div></div></div>' ;
                                }
                                echo "</div>";
                                }

                            ?>

                            <?php
                                if($_GET['type']==2)
                                {
                                    $dx = $subdoc->find()->where(['id'=>$_GET['type']])->first();

                                    echo '<div class="subform2">';

                                    ?>

                                    <?php include('subpages/documents/driver_application.php');?>

                                    <?php
                                    echo "</div>";
                                }
                                if($_GET['type']==3)
                                {
                                    $dx = $subdoc->find()->where(['id'=>$_GET['type']])->first();
                                    ?>
                                    <div class="subform3"><?php
                                            if($controller == 'documents' ) {

                                                $colr = $this->requestAction('/documents/getColorId/3');
                                                if(!$colr){$colr = $class[2];}

                                                makeportlet($did, $colr, docname(3, $subdoccli, $language) );
                                            } else {

                                            }
                                            include('subpages/documents/driver_evaluation_form.php');
                                            if($controller == 'documents' ) {
                                                echo '</div></div></div></div></div></div>' ;
                                            }
                                        ?>
                                    </div>
                                <?php }?>
                            <?php
                                if($_GET['type']==4)
                                {
                                    $dx = $subdoc->find()->where(['id'=>$_GET['type']])->first();
                                    ?>
                                    <div class="subform4">
                                        <?php
                                            if($controller == 'documents' ) {
                                                $colr = $this->requestAction('/documents/getColorId/4');
                                                if(!$colr) {$colr = $class[3];}
                                                makeportlet($did, $colr,docname(4, $subdoccli, $language));
                                            }
                                            else {

                                            }
                                            include('subpages/documents/document_tab_3.php');
                                            if($controller == 'documents' ) {
                                                echo '</div></div></div></div></div></div>' ;
                                            }
                                        ?>
                                    </div>
                                <?php }?>
                            <?php
                                if($_GET['type']==5)
                                {
                                    $dx = $subdoc->find()->where(['id'=>$_GET['type']])->first();
                                    ?>
                                    <div class="subform5">
                                        <?php
                                            if($controller == 'documents' ) {
                                                $colr = $this->requestAction('/documents/getColorId/5');
                                                if(!$colr) {$colr = $class[4];}
                                                makeportlet($did, $colr,docname(5, $subdoccli, $language));
                                            } else {

                                            }
                                            include('subpages/documents/survey.php');
                                            if($controller == 'documents' ) {
                                                echo '</div></div></div></div></div></div>' ;
                                            }
                                        ?>
                                    </div>
                                    <?php
                                }

                                if($_GET['type']==6)
                                {
                                    $dx = $subdoc->find()->where(['id'=>$_GET['type']])->first();
                                    ?>
                                    <div class="subform6">
                                        <?php
                                            if($controller == 'documents' )
                                            {

                                                $colr = $this->requestAction('/documents/getColorId/6');
                                                if(!$colr) {$colr = $class[5];}
                                                makeportlet($did, $colr,docname(6, $subdoccli, $language));
                                            }
                                            else {

                                            }
                                        ?>
                                        <?php include('subpages/documents/feedbacks.php');
                                            if($controller == 'documents' ) {
                                                echo '</div></div></div></div></div></div>' ;
                                            }
                                        ?>
                                    </div>
                                    <?php
                                }

                                if($_GET['type']==7)
                                {
                                    $dx = $subdoc->find()->where(['id'=>$_GET['type']])->first();
                                    ?>
                                    <div class="subform7">
                                        <?php
                                            if($controller == 'documents' ) {
                                                $colr = $this->requestAction('/documents/getColorId/7');
                                                if(!$colr) {$colr = $class[6];}
                                                makeportlet($did, $colr,docname(7, $subdoccli, $language));
                                            }
                                            else {

                                            }
                                        ?>
                                        <?php include('subpages/documents/attachments.php');
                                            if($controller == 'documents' ) {
                                                echo '</div></div></div></div></div></div>' ;
                                            }
                                        ?>
                                    </div>
                                    <?php
                                }

                                if($_GET['type']==8)
                                {
                                    $dx = $subdoc->find()->where(['id'=>$_GET['type']])->first();
                                    ?>
                                    <div class="subform8">
                                        <?php
                                            if($controller == 'documents' ) {
                                                $colr = $this->requestAction('/documents/getColorId/8');
                                                if(!$colr) {$colr = $class[7];}
                                                makeportlet($did, $colr,docname(8, $subdoccli, $language));
                                            } else {

                                            }
                                        ?>
                                        <?php include('subpages/documents/audits.php');
                                            if($controller == 'documents' ) {
                                                echo '</div></div></div></div></div></div>' ;
                                            }
                                        ?>
                                    </div>
                                    <?php
                                }

                                if($_GET['type']==9)
                                {
                                    $dx = $subdoc->find()->where(['id'=>$_GET['type']])->first();
                                    ?>
                                    <div class="subform9">
                                        <?php
                                            if($controller == 'documents' ) {
                                                $colr = $this->requestAction('/documents/getColorId/9');
                                                if(!$colr) {$colr = $class[8];}
                                                makeportlet($did, $colr,docname(9, $subdoccli, $language));
                                            } else {

                                            }
                                        ?>
                                        <?php include('subpages/documents/employment_verification_form.php');
                                            if($controller == 'documents' ) {
                                                echo '</div></div></div></div></div></div>' ;
                                            }
                                        ?>
                                    </div>
                                    <?php
                                }

                                if($_GET['type']==10)
                                {
                                    //die($_GET['type']);
                                    $dx = $subdoc->find()->where(['id'=>$_GET['type']])->first();


                                    ?>

                                    <div class="subform10">
                                        <?php
                                            if($controller == 'documents' ) {

                                                $colr = $this->requestAction('/documents/getColorId/10');
                                                if(!$colr) {$colr = $class[9];}
                                                makeportlet($did, $colr,docname(10, $subdoccli, $language));
                                            } else {

                                            }
                                        ?>
                                        <?php include('subpages/documents/education_verification_form.php');
                                            if($controller == 'documents' ) {
                                                echo '</div></div></div></div></div></div>' ;
                                            }
                                        ?>
                                    </div>
                                    <?php
                                }

                            ?>
                            <?php foreach($doc as $dx)
                            {
                                if($dx->id >10){
                                    if($_GET['type'] == $dx->id){
                                        ?>
                                        <div class="subform<?php echo $dx->id;?>">
                                            <input type="hidden" class="document_type" name="document_type" value="<?php echo $dx->title;?>"/>
                                            <input type="hidden" name="sub_doc_id" value="<?php echo $dx->id;?>" class="sub_docs_id"  />
                                            <?php
                                                if($controller == 'documents' ) {
                                                    $colr = $this->requestAction('/documents/getColorId/'.$dx->id);
                                                    if(!$colr) {$colr = $class[9];}
                                                    makeportlet($did, $colr,$dx->$keyname  . $Trans);
                                                } else {

                                                }
                                            ?>
                                            <?php if($dx->form && file_exists('subpages/documents/'.$dx->form))include('subpages/documents/'.$dx->form);
                                                if($controller == 'documents' ) {
                                                    echo '</div></div></div></div></div></div>' ;
                                                }
                                            ?>
                                        </div>
                                        <?php
                                    }}
                            }
                            ?>

                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9 btndocs" <?php if(!isset($_GET['type'])){?>style="display: none;"<?php }?>>


                                <a href="javascript:void(0)" class="btn green cont buttons" id="button1"><?= $strings["forms_save"]; ?></a>

                                <?php
                                    if(!isset($_GET['order_id']))
                                    {
                                        ?>

                                        <a href="javascript:;" id="button1" title="draft" class="btn blue cont buttons">
                                            <?= $strings["forms_savedraft"]; ?> <i class="m-icon-swapright m-icon-white"></i>
                                        </a>
                                        <?php
                                    }
                                ?>
                                <div class="margin-top-10 alert alert-success display-hide flashDoc" style="display: none;">
                                    <button class="close" data-close="alert"></button>
                                    <?php echo ucfirst($settings->document); ?> <?= $strings["forms_uploaded"]; ?>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<script>
    

    client_id = '<?=$cid?>',
        doc_id = '<?=$did?>';
    $(function(){
         $('.links a:nth-child(5), .links p').css({'display':'none'});
        if(doc_id && doc_id != '0')
        {
            <?php if(isset($_GET['type'])){ ?>

            showforms('<?php $subdet = $this->requestAction('/orders/getSubDetail/'.$_GET['type']); echo $subdet->form."?doc_id=".$subdet->id;?>');
            <?php }?>
        }
        if (!doc_id || doc_id=='0') {

            $('#selecting_driver').change(function () {

                fillform();
            });
        }
    })

    function saveSignature() {
        if($(".subform4").attr('style')) {
            if($('.sub_docs_id').val()=='18') {
                save_signature('8');
            }
        }
    }
/*
    function save_signature(numb) {
         var d = $.Deferred();
        $("#test"+numb).data("jqScribble").save(function(imageData)
        {
            //alert($('#signature_company_witness2').parent().find('.touched').val());
            if((numb=='1' && $('#recruiter_signature').parent().find('.touched').val()==1) || (numb=='3' && $('#criminal_signature_applicant').parent().find('.touched').val()==1) || (numb=='4' && $('#signature_company_witness').parent().find('.touched').val()==1) || (numb=='5' && $('#criminal_signature_applicant2').parent().find('.touched').val()==1) || (numb=='6' && $('#signature_company_witness2').parent().find('.touched').val()==1) || (numb=='8' && $('#gfs_signature').parent().find('.touched').val()==1)){
                $.post('<?php echo $this->request->webroot; ?>canvas/image_save.php', {imagedata: imageData}, function(response) {
                     d.resolve(response);
                    if(numb=='1') {
                        $('#recruiter_signature').val(response);
                    }
                    if(numb=='3') {
                        $('#criminal_signature_applicant').val(response);
                    }
                    if(numb=='4') {
                        $('#signature_company_witness').val(response);
                    }
                    if(numb=='5') {
                        $('#criminal_signature_applicant2').val(response);
                    }
                    if(numb=='6') {
                        $('#signature_company_witness2').val(response);
                    }
                    if(numb=='8') {
                        $('#gfs_signature').val(response);
                    }
                    $('.saved'+numb).html('Saved');
                });
            }



        });
        return d.promise();
    }
    */
    //showforms(doc_type);
    function showforms(form_type) {

        $('.moredocxs').show();
        $('.btndocs').show();
        $('.clients_select').hide();
        var arr_formtype = form_type.split('?');
        var sub_doc_id = arr_formtype[1];

        var s_arr = sub_doc_id.split('=');
        var ftype = arr_formtype[0];


        $('#sub_id').val(s_arr[1]);
        for(var k=1;k<=parseFloat('<?php echo $doc_count;?>');k++) {
            $('.subform'+k+' .document_type').remove();
            $('.subform'+k+' .sub_docs_id').remove();

        }
        <?php foreach($doc as $dx)
               {
                   if($dx->id >11)
                   {
                   ?>
        if(s_arr[1] == <?php echo $dx->id;?>){
            $('#form_tab<?php echo $dx->id;?>').prepend('<input class="document_type" type="hidden" name="document_type" value="<?php echo addslashes($dx->title);?>" />' +
                '<input type="hidden" class="sub_docs_id" name="sub_doc_id" value="<?php echo $dx->id;?>"  />');
            $('.addattachment<?php echo $dx->id;?>').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view;?>', function(){
                if ($("#addMore1").length)
                    initiate_ajax_upload1('addMore1', 'doc');
            });
        }
        <?php       }
                }
        ?>
        if (s_arr[1] == 5) {
            $('#form_tab5').prepend('<input class="document_type" type="hidden" name="document_type" value="Survey" />' +
                '<input type="hidden" class="sub_docs_id" name="sub_doc_id" value="5"  />');
            $('.addattachment5').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view;?>', function(){
                if ($("#addMore1").length)
                    initiate_ajax_upload1('addMore1', 'doc');
            });
        }
        if (s_arr[1] == 6) {
            $('#form_tab6').prepend('<input class="document_type" type="hidden" name="document_type" value="Feedback" />' +
                '<input type="hidden" class="sub_docs_id" name="sub_doc_id" value="6"  />');
            $('.addattachment6').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view;?>', function(){
                if ($("#addMore1").length)
                    initiate_ajax_upload1('addMore1', 'doc');
            });
        }
        if (s_arr[1] == 7) {
            $('#form_tab7').prepend('<input class="document_type" type="hidden" name="document_type" value="Attachment" />' +
                '<input type="hidden" class="sub_docs_id" name="sub_doc_id" value="7"  />');
            $('.addattachment7').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view;?>', function(){
                if ($("#addMore1").length)
                    initiate_ajax_upload1('addMore1', 'doc');
            });
        }
        if (s_arr[1] == 8) {
            $('#form_tab8').prepend('<input class="document_type" type="hidden" name="document_type" value="Audits" />' +
                '<input type="hidden" class="sub_docs_id" name="sub_doc_id" value="8"  />');
            $('.addattachment8').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view;?>', function(){
                if ($("#addMore1").length)
                    initiate_ajax_upload1('addMore1', 'doc');
            });

        }
        if (s_arr[1] == 9) {
            $('#form_tab9').prepend('<input class="document_type" type="hidden" name="document_type" value="Employment Verification" />' +
                '<input type="hidden" class="sub_docs_id" name="sub_doc_id" value="9"  />');


        }
        if (s_arr[1] == 10) {
            $('#form_tab10').prepend('<input class="document_type" type="hidden" name="document_type" value="Education Verification" />' +
                '<input type="hidden" class="sub_docs_id" name="sub_doc_id" value="10"  />');

        }
        if (s_arr[1] == 11) {
            $('#form_tab11').prepend('<input class="document_type" type="hidden" name="document_type" value="Basic Pre-Screen Questions" />' +
                '<input type="hidden" class="sub_docs_id" name="sub_doc_id" value="11"  />');
            $('.addattachment11').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view;?>', function(){
                if($("#addMore1").length)
                    initiate_ajax_upload1('addMore1', 'doc');
            });

        }

        if (s_arr[1] == 1) {
            $('#form_tab1').prepend('<input type="hidden" class="document_type" name="document_type" value="Pre-Screening"/>' +
                '<input type="hidden" name="sub_doc_id" value="1" class="sub_docs_id" id="af" />');
        }
        if (s_arr[1] == 2) {
            $('#form_tab2').prepend('<input type="hidden" class="document_type" name="document_type" value="Driver Application"/>' +
                '<input type="hidden" name="sub_doc_id" value="2" class="sub_docs_id" id="af" />');
        }
        if (s_arr[1] == 3) {
            $('#form_tab3').prepend('<input class="document_type" type="hidden" name="document_type" value="Road test" />' +
                '<input type="hidden" class="sub_docs_id" name="sub_doc_id" value="3" id="af" />');
        }
        if (s_arr[1] == 4) {
            $('#form_tab4').prepend('<input class="document_type" type="hidden" name="document_type" value="Consent Form" />' +
                '<input type="hidden" class="sub_docs_id" name="sub_doc_id" value="4"  />');
        }





        if(s_arr[1]>4) {
            $('.attachments').show();
        } else {
            $('.attachments').hide();
        }
        if (ftype != "") {
            //alert(form_type);
            for (var p = 1; p <= parseFloat('<?php echo $doc_count;?>'); p++) {
                //alert(p);
                $('.subform' + p).hide();

            }
            $('.subform' + s_arr[1]).show(200, function () {
                var url = '<?php echo $this->request->webroot;?>documents/getOrderData/' + client_id + '/' + doc_id + '/?document=1<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>',
                    param = {form_type: ftype};
                $.getJSON(url, param, function (res) {
                    if (res) {
                        if (ftype == "company_pre_screen_question.php") {

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



                        } else if (ftype == "driver_application.php") {
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

                        } else if (ftype == "driver_evaluation_form.php") {

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
                                //alert('test');
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


                            // end road test
                        } else if (ftype == "document_tab_3.php") {

                            $('#form_consent').find(':input').each(function () {
                                if($(this).attr('class')!='touched' && $(this).attr('class')!='touched_edit3' && $(this).attr('class')!='touched_edit1' && $(this).attr('class')!='touched_edit2' && $(this).attr('class')!='touched_edit4'){
                                    var $name = $(this).attr('name');

                                    if ($name != 'offence[]' && $name != 'date_of_sentence[]' && $name != 'location[]' && $name != 'attach_doc[]') {
                                        $(this).val(res[$name]);

                                    }
                                }
                            });


                        }
                    }

                });
            });
        }
        else{
            $('.subform').html("");


        }
    }
    function fillform()
    {
        var prof_id = $('#selecting_driver').val();
        profile_id = prof_id;
        if (prof_id != 0 && prof_id != '0') {
            $.ajax({
                url: '<?php echo $this->request->webroot;?>profiles/getProfileById/' + prof_id + '/1',
                success: function (res2) {
                    var response = JSON.parse(res2);
                    $('#form_tab1').find(':input').each(function () {
                        var name_attr = $(this).attr('name');

                        //alert(name_attr);
                        if (response[name_attr]) {

                            $(this).val(response[name_attr]);

                            $(this).attr('disabled', 'disabled');

                        }
                    });
                }
            });
            $.ajax({
                url: '<?php echo $this->request->webroot;?>profiles/getProfileById/' + prof_id + '/2',
                success: function (res2) {
                    var response = JSON.parse(res2);
                    $('#form_tab2').find(':input').each(function () {
                        var name_attr = $(this).attr('name');

                        //alert(name_attr);
                        if (response[name_attr]) {

                            $(this).val(response[name_attr]);

                            $(this).attr('disabled', 'disabled');

                        }
                    });
                }
            });
            $.ajax({
                url: '<?php echo $this->request->webroot;?>profiles/getProfileById/' + prof_id + '/3',
                success: function (res2) {
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
            });
            $.ajax({
                url: '<?php echo $this->request->webroot;?>profiles/getProfileById/' + prof_id + '/4',
                success: function (res2) {
                    var response = JSON.parse(res2);
                    $('#form_consent').find(':input').each(function () {

                        var name_attr = $(this).attr('name');

                        //alert(name_attr);
                        if (response[name_attr]) {

                            $(this).val(response[name_attr]);

                            $(this).attr('disabled', 'disabled');

                        }
                    });
                    $('#conf_driver_name').val(response['applicant_name']);
                    $('#conf_driver_name').attr('disabled', 'disabled');

                }
            });
        }
    }

    function assignValue(formID, obj) {
    }



    function subform(form_type) {
        var filename = form_type.replace(/\W/g, '_');
        var filename = filename.toLowerCase();
        $('.subform').show();
        $('.subform').load('<?php echo $this->request->webroot;?>documents/subpages/' + filename);
    }

    function addmoredoc(idname) {
        var total_count = $('.'+idname).data('count');
        $('.'+idname).data('count', parseInt(total_count) + 1);
        total_count = $('.'+idname).data('count');
        var input_field = '<div  class="form-group col-md-12" style="padding-left:15px;"><div class="col-md-12"><a href="javascript:void(0);" id="'+idname + total_count + '" class="btn btn-primary"><?= addslashes($strings["forms_browse"]); ?></a><input type="hidden" name="attach_doc[]" value="" class="'+idname + total_count + '_doc moredocs" /> <a href="javascript:void(0);" class = "btn btn-danger img_delete" id="delete_'+idname + total_count + '" title =""><?= addslashes($strings["dashboard_delete"]); ?></a><span></span></div></div>';
        $('.'+idname).append(input_field);
        if($('#'+idname + total_count).length)
            initiate_ajax_upload1(idname + total_count, 'doc');
    }

    jQuery(document).ready(function () {
        var subdocid = $('#sub_id').val();
        subdocid = parseFloat(subdocid);
        <?php
        if(isset($_GET['type']))
        {
            ?>
        <?php foreach($doc as $dx)
            {
                if($dx->id >11)
                {
                ?>
        if(subdocid == <?php echo $dx->id;?>){

            $('.addattachment<?php echo $dx->id;?>').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view;?>', function(){
                if($('#addMore1').length)
                    initiate_ajax_upload1('addMore1', 'doc');
            });
        }
        <?php       }
                }
        ?>
        if (subdocid == 5) {

            $('.addattachment5').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view;?>', function(){
                if($('#addMore1').length)
                    initiate_ajax_upload1('addMore1', 'doc');
            });
        }
        if (subdocid == 6) {

            $('.addattachment6').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view;?>', function(){
                if($('#addMore1').length)
                    initiate_ajax_upload1('addMore1', 'doc');
            });
        }
        if (subdocid == 7) {

            $('.addattachment7').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view;?>', function(){
                if($('#addMore1').length)
                    initiate_ajax_upload1('addMore1', 'doc');
            });
        }
        if (subdocid == 8) {

            $('.addattachment8').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view;?>', function(){
                if($('#addMore1').length)
                    initiate_ajax_upload1('addMore1', 'doc');
            });

        }

        if (subdocid == 11) {

            $('.addattachment11').load('<?php echo $this->request->webroot;?>documents/attach_doc/<?php echo $did."/".$view;?>', function(){
                if($('#addMore1').length)
                    initiate_ajax_upload1('addMore1', 'doc');
            });

        }


        <?php
    }
    ?>

        $('#changeclient').change(function(){
            changeclient_onchange();
        });
        $('.dashboard-stat .more').click(function(){
            var moreid = $(this).attr('id');
            $('.dashboard-stat .more').each(function(){
                $(this).parent().parent().fadeOut(300);
            });
            $('#'+moreid).parent().parent().show(300);
            $('.moreback').show();

        });
        $('.required').live('keyup',function(){
            //alert('test');
            //alert($('.email1').val());
            if($(this).val().length>0){
                $(this).removeAttr('style');
                //$('.cont').attr('disabled','');
            }


        });
        $('.required').live('blur',function(){
            //alert($('.email1').val());
            if($(this).val().length==0){
                $(this).val('');
                //$('.cont').removeAttr('disabled');
                $(this).attr('style','border-color:red');
            }


        });
        //old validation
        /*
        $('.email1').live('keyup',function(){
            //alert($('.email1').val());
            if($(this).val()!='' && ($(this).val().replace('@','')== $(this).val() || $(this).val().replace('.','')== $(this).val() || $(this).val().length<5)){
                $(this).attr('style','border-color:red');
                $('.cont').attr('disabled','');
            }
            else{
                // alert($('.email1').val());
                $('.cont').removeAttr('disabled');
                $(this).removeAttr('style');
            }

        });
        $('.email1').live('blur',function(){
            //alert($('.email1').val());
            if($(this).val()!='' && ($(this).val().replace('@','')== $(this).val() || $(this).val().replace('.','')== $(this).val() || $(this).val().length<5)){
                $(this).val('');
                $('.cont').removeAttr('disabled');
                $(this).removeAttr('style');
            }


        });
        */
        <?php
        if($this->request->params['action']=='view')
        {
            ?>

        var h = '<?php echo $_GET['type'];?>';
        $('#form_tab' + h + ' input').attr('disabled', 'disabled');
        $('#form_tab' + h + ' textarea').attr('disabled', 'disabled');
        $('#form_tab' + h + ' select').attr('disabled', 'disabled');
        $('#form_tab' + h + ' button').hide();
        $('#form_tab' + h + ' a').not('.dl').hide();
        $('.nav a').show();
        $('#form_tab' + h + ' input[type="submit"]').hide();
        $('.form-actions').hide();
        <?php
    }
    if(isset($did) && $did)
    {
        ?>
        $('#sub_doc_click<?php echo $mod->sub_doc_id?>').click();
        <?php
    }
    ?>
        var draft = 0;
        $(document.body).on('click', '.cont', function () {
            //var isvalid = checkalltags("tab0");
            //if(!isvalid){return false;}

            var sid = $('#sub_id').val();
            var type = <?php if(!isset($_GET['doc'])) {
                echo '$(".document_type").val()';
            } else {
                echo '"' . urldecode($_GET['doc']) . '"';
            } ?>;
            $('.overlay-wrapper').show();
            //alert(type);return false;
            switch(type) {
                case 'Driver Application':
                    if(!$('#confirm_check').is(':checked')) {
                        alert(readTOS);
                        $('#confirm_check').focus();
                        $('html,body').animate({scrollTop: $('#confirm_check').offset().top}, 'slow');
                        $('.overlay-wrapper').hide();
                        return false;
                    }
                    break;
            }

            $(this).attr('disabled','disabled');

            
           
            var attach_docs = "";
            $('.moredocs').each(function(){
                attach_docs += $(this).val()+",";
            })
            attach_docs = attach_docs.substring(0,attach_docs.length-1);
            //alert(type);
            //alert($('#sub_id').val());return;
            var data = {
                uploaded_for: $('#selecting_driver').val(),
                type: type,
                sub_doc_id: $('#sub_id').val(),
                division: $('#division').val(),
                attach_doc: attach_docs,
                user_id: '<?php echo $this->request->session()->read('Profile.id');?>'
            };
            //alert(type);return false;
            /*
            $.ajax({
                //data:'uploaded_for='+$('#uploaded_for').val(),
                data: data,
                type: 'post',
                beforeSend:saveSignature,
                url: '<?php echo $this->request->webroot;?>clientApplication/savedoc/<?php echo $cid;?>/' + doc_id + '/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>',
                success: function (res) {

                    $('#did').val(res);
                   
                    //alert(type);return false;
                    //alert(type);return false;
                    if (sid == "1") {
                        var forms = $(".tab-pane.active").prev('.tab-pane').find(':input'),
                            url = '<?php echo $this->request->webroot;?>clientApplication/savePrescreening/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>',
                            order_id = $('#did').val(),
                            cid = '<?php echo $cid;?>';
                        savePrescreen(url, order_id, cid, draft);

                    } else if (sid == "2") {
                        var order_id = $('#did').val(),
                            cid = '<?php echo $cid;?>',
                            url = '<?php echo $this->request->webroot;?>clientApplication/savedDriverApp/' + order_id + '/' + cid + '/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>';
                        savedDriverApp(url, order_id, cid,draft);
                    } else if (sid == "3") {
                        var order_id = $('#did').val(),
                            cid = '<?php echo $cid;?>',
                            url = '<?php echo $this->request->webroot;?>clientApplication/savedDriverEvaluation/' + order_id + '/' + cid + '/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>';
                        savedDriverEvaluation(url, order_id, cid,draft);
                    } else if (sid == "4") {
                        save_signature('3');
                        save_signature('4');
                        save_signature('5');
                        save_signature('6');
                        var order_id = $('#did').val(),
                            cid = '<?php echo $cid;?>',
                            url = '<?php echo $this->request->webroot;?>clientApplication/savedMeeOrder/' + order_id + '/' + cid + '/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>';
                        setTimeout(function(){
                            savedMeeOrder(url, order_id, cid, type,draft);
                        },1000);

                    }
                    else if (sid == "9") {

                        //alert(type);
                        var order_id = $('#did').val(),
                            cid = '<?php echo $cid;?>',
                            url = '<?php echo $this->request->webroot;?>clientApplication/saveEmployment/' + order_id + '/' + cid + '/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>';
                        saveEmployment(url, order_id, cid, type,draft);
                    }
                    else if (sid == "10") {

                        //alert(type);
                        var order_id = $('#did').val(),
                            cid = '<?php echo $cid;?>',
                            url = '<?php echo $this->request->webroot;?>clientApplication/saveEducation/' + order_id + '/' + cid + '/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>';
                        saveEducation(url, order_id, cid, type,draft);
                    }
                    else if (sid == "6") {
                        var order_id = $('#did').val(),
                            cid = '<?php echo $cid;?>',
                            url = '<?php echo $this->request->webroot;?>feedbacks/add/' + order_id + '/' + cid + '/?document=' + type + '&draft=' + draft;
                        var param = $('#form_tab6').serialize();
                        $.ajax({
                            url: url,
                            data: param,
                            type: 'POST',
                            success: function (res) {
                                if (res == 'OK'){
                                    if(draft==0)
                                        window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                                    else
                                        window.location = '<?php echo $this->request->webroot?>documents/index?flash';

                                }
                            }
                        });

                    }
                    else if (sid == "5") {
                        var order_id = $('#did').val(),
                            cid = '<?php echo $cid;?>',
                            url = '<?php echo $this->request->webroot;?>feedbacks/addsurvey/' + order_id + '/' + cid + '/?document=' + type + '&draft=' + draft;
                        var param = $('#form_tab5').serialize();
                        $.ajax({
                            url: url,
                            data: param,
                            type: 'POST',
                            success: function (res) {
                                if (res == 'OK'){
                                    if(draft==0)
                                        window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                                    else
                                        window.location = '<?php echo $this->request->webroot?>documents/index?flash';

                                }
                            }
                        });

                    }
                    else if (sid == "7") {
                        var act = $('#form_tab7').attr('action');

                        $('#form_tab7').attr('action', function (i, val) {
                            return val + '?draft=' + draft;
                        });
                        $('#form_tab7').submit();


                    }
                    else if (sid == "8") {
                        var act = $('#form_tab8').attr('action');

                        $('#form_tab8').attr('action', function (i, val) {
                            return val + '?draft=' + draft;
                        });

                        $('#form_tab8').submit();


                    }
                    else if(sid == '11')
                    {
                        var act = $('#form_tab11').attr('action');

                        $('#form_tab11').attr('action', function (i, val) {
                            return val + '?draft=' + draft;
                        });

                        $('#form_tab11').submit();

                    }
                    else
                    if (sid == "15") {
                        //alert('test');return;
                        var order_id = $('#did').val(),
                            cid = '<?php echo $cid;?>',
                            url = '<?php echo $this->request->webroot;?>clientApplication/mee_attach/' + order_id + '/' + cid + '/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>';
                        var param = $('#form_tab15').serialize();
                        $.ajax({
                            url: url,
                            data: param,
                            type: 'POST',
                            success: function (res) {

                                if(draft==0)
                                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                                else
                                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                            }


                        });

                    }
                      else
                        if (sid == "18") {
                            if($('#test8').parent().parent().find('.touched').val()=='1'){
                            $.when(save_signature('8')).done(function(d1){
                                $('#gfs_signature').val(d1);
                                var order_id = $('#did').val();
                                    cid = '<?php echo $cid;?>';
                                    url = '<?php echo $this->request->webroot;?>clientApplication/application_employment/'+ cid +'/'+ order_id + '/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>&user_id=<?php echo $this->request->session()->read('Profile.id');?>&uploaded_for='+$('#selecting_driver').val();
                                var param = $('#form_tab18').serialize();
                                 $.ajax({
                                    url: url,
                                    data: param,
                                    type: 'POST',
                                    success: function (res) {
                                           
                                                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';

                                         }
        
        
                                });
                            });
                           }
                           else
                           {
                                var order_id = $('#did').val();
                                    cid = '<?php echo $cid;?>';
                                    url = '<?php echo $this->request->webroot;?>clientApplication/application_employment/'+ cid +'/'+ order_id + '/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>&user_id=<?php echo $this->request->session()->read('Profile.id');?>&uploaded_for='+$('#selecting_driver').val();
                                var param = $('#form_tab18').serialize();
                                 $.ajax({
                                    url: url,
                                    data: param,
                                    type: 'POST',
                                    success: function (res) {
                                             window.location = '<?php echo $this->request->webroot?>documents/index?flash';

                                         }
        
        
                                });
                           } 
    
                        }
                    else{
                        <?php foreach($doc as $dx)
                                {
                                    if($dx->id >11)
                                    {
                                    ?>
                        if(type == "<?php echo addslashes($dx->title);?>")
                        {
                            var act = $('#form_tab<?php echo $dx->id;?>').attr('action');

                            $('#form_tab<?php echo $dx->id;?>').attr('action', function (i, val) {
                                return val + '?draft=' + draft;
                            });

                            $('#form_tab<?php echo $dx->id;?>').submit();
                        }

                        <?php       }
                                }
                        ?>

                    }
                }
            });*/
        });
        
        $('#addfiles').click(function () {
            //alert("ssss");
            $('#doc').append('<div style="padding-top:10px;"><a href="#" class="btn btn-success">Browse</a> <a href="javascript:void(0);" class="btn btn-danger" onclick="$(this).parent().remove();"><?= addslashes($strings["dashboard_delete"]); ?></a><br/></div>');
        });
        $('.nohide').show();
    });
    /*
    function savePrescreen(url, order_id, cid,draft) {

        inputs = $('#form_tab1').serialize();

        $('#form_tab1 :disabled[name]').each(function () {
            inputs = inputs + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        var param = {
            order_id: order_id,
            cid: cid,
            inputs: inputs
        };
        $.ajax({
            url: url,
            data: param,
            type: 'POST',
            success: function (res) {
                //alert(draft);
                //return;
                if(draft==0)
                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                else
                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';


            }
        });
    }

    function savedDriverApp(url, order_id, cid,draft) {
        var param = $('#form_tab2').serialize();
        $('#form_tab2 :disabled[name]').each(function () {
            param = param + '&' + $(this).attr('name') + '=' + $(this).val();
        });

        $.ajax({
            url: url,
            data: param,
            type: 'POST',
            success: function (res) {
                if(draft==0){
                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                }
                else{
                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                }
            }
        });
    }
    function savedDriverEvaluation(url, order_id, cid,draft) {
        var param = $('#form_tab3').serialize();
        $('#form_tab3 :disabled[name]').each(function () {
            param = param + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        $.ajax({
            url: url,
            data: param,
            type: 'POST',
            success: function (res) {
                if(draft==0){
                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                }
                else
                {
                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                }
            }
        });
    }

    function savedMeeOrder(url, order_id, cid, type,draft) {
        var param = $('#form_consent').serialize();
        $('#form_consent :disabled[name]').each(function () {
            param = param + '&' + $(this).attr('name') + '=' + $(this).val();
        });

        $.ajax({
            url: url,
            data: param,
            type: 'POST',
            success: function (res) {
                if(draft==0){
                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                }
                else
                {
                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                }

            }
        });
    }

    function saveEmployment(url, order_id, cid, type,draft) {

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

                if(draft==0){
                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                }
                else
                {
                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                }
            }
        });
    }

    function saveEducation(url, order_id, cid, type,draft) {
        //alert('test2');
        //$('#loading5').show();
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
                if(draft==0){
                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                }
                else
                {
                    window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                }
            }
        });
    }
      



    function fileUpload(ID) {
        // e.preventDefault();

        var $type = $(".tab-pane.active").find("input[name='document_type']").val(),
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
                    alert('Invalid file type.');
                }
            }

        });
    }
    */
</script>
<?php //includejavascript($strings);?>
<script>
    function changeclient_onchange(){
        var id = $('#changeclient').val();
        window.location ="<?php echo $this->request->webroot;?>documents/add/"+id;
    }
    <?php
        if (!isset($disabled)) {
    ?>
    $(function () {



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

            var con = confirmdelete(file); // confirm('Are you sure you want to delete "' + file + '"?');
            if (con == true) {
                $.ajax({
                    type: "post",
                    data: 'id=' + id,
                    url: "<?php echo $this->request->webroot;?>documents/removefiles/" + file,
                    success: function (msg) {

                    }
                });
                $(this).parent().parent().remove();

            }
            else
                return false;
        });
    });
    function initiate_ajax_upload1(button_id, doc) {
        alert(button_id);
        var button = $('#' + button_id), interval;
        if (doc == 'doc')
            var act = "<?php echo $this->request->webroot;?>documents/fileUpload/<?php if(isset($id))echo $id;?>";
        else
            var act = "<?php echo $this->request->webroot;?>documents/fileUpload/<?php if(isset($id))echo $id;?>";
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
                if (doc == "doc")
                    button.html('Browse');
                else
                    button.html('<i class="fa fa-image"></i> <?= addslashes($strings["clients_addeditimage"]); ?>');

                window.clearInterval(interval);
                this.enable();
                if (doc == "doc") {
                    $('#' + button_id).parent().find('span').text(" " + response);
                    $('.' + button_id + "_doc").val(response);
                    $('#delete_' + button_id).attr('title', response);
                    if(button_id =='addMore1')
                        $('#delete_'+button_id).show();
                }
                else {
                    $("#clientpic").attr("src", '<?php echo $this->request->webroot;?>img/jobs/' + response);
                    $('#client_img').val(response);
                }
//$('.flashimg').show();
            }
        });
    }
    <?php }?>
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
<?php include_once('subpages/commonjs.php');    ?>
