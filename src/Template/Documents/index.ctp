<?php
    $settings = $Manager->get_settings();
    $sidebar = $Manager->loadpermissions($Me, "sidebar");// $this->requestAction("settings/all_settings/" . $this->Session->read('Profile.id') . "/sidebar");
    $debug=$this->request->session()->read('debug');
    include_once('subpages/api.php');
    $language = $this->request->session()->read('Profile.language');
    $controller =  $this->request->params['controller'];
    $strings = CacheTranslations($language, array($controller  . "_%", "month_short%"),$settings);
    if($debug && $language == "Debug"){ $Trans = " [Translated]"; } else {$Trans = "";}
?>
<style>
    .select2me{
        width: 200px;
        margin-left: 5px;
    }
    .showclientdivision{
        margin-left: 5px !important;
    }
</style>
<h3 class="page-title">
    <?php
        $string = "index_documents";
        if (isset($_GET['draft'])) { $string = "index_documentdrafts"; }
        echo $strings[$string];
    ?>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo $this->request->webroot; ?>"><?=$strings["dashboard_dashboard"];?></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href=""><?= $strings["index_documents"];?></a>
        </li>
    </ul>

    <a href="javascript:window.print();" class="floatright btn btn-primary"><?=$strings["dashboard_print"];?></a>
    <?php if ($sidebar->document_create == 1) { ?>
        <a href="<?php echo $this->request->webroot; ?>documents/add" class="floatright btn btn-primary btnspc">
            <?= $strings["index_createdocument"]; ?></a>
    <?php }
        if (isset($_GET["draft"])) { ?>
            <a href="<?php echo $this->request->webroot; ?>documents/index" class="floatright btn btn-primary btnspc">
                <?=$strings["index_listdocuments"];?></a>
        <?php } else { ?>
            <a href="<?php echo $this->request->webroot; ?>documents/index?draft" class="floatright btn btn-primary btnspc">
                <?=$strings["dashboard_drafts"];?></a>
        <?php } ?>

</div>


<div class="row">
    <div class="col-md-12">
        <div class="portlet box yellow-casablanca ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-shopping-cart"></i>
                    <?=$strings["index_listdocuments"];?>
                </div>
            </div>
            <div class="portlet-body form">

                <div class="form-actions top chat-form" style="margin-top:0;margin-bottom:0;" align="right">

                    <div class="btn-set pull-left">

                    </div>
                    <div class="btn-set pull-right">


                        <form action="<?= $this->request->webroot; ?>documents/index" method="get">
                            <?php
                                if (isset($_GET['draft'])) { echo '<input type="hidden" name="draft"/>'; }
                                $type = $doc_comp->getDocType($this->request->session()->read('Profile.id'));
                                echo '<select class="form-control input-inline" name="type"><option value="">' . $strings["documents_document"] . '</option>';

                                $fieldname = getFieldname("title", $language);
                                foreach ($type as $t) {
                                    if(!isset($t->show) || $t->show) {
                                        echo '<option value="' . $t->id . '"';
                                            if (isset($return_type) && $return_type == $t->id) { echo ' selected="selected"';}
                                             echo '>' . ucfirst($t->$fieldname . $Trans);
                                            echo '</option>';
                                    }
                                }

                                echo '</select><select class="form-control input-inline select2me" name="submitted_by_id"><option value="">' . $strings["documents_submittedby"] . '</option>';

                                $users = $doc_comp->getAllUser();
                                foreach ($users as $u) {
                                    echo '<option value="' . $u->id . '" ';
                                    if (isset($return_user_id) && $return_user_id == $u->id) { echo ' selected="selected"';}
                                    echo '>' . formatname($u) . '</option>';
                                }
                                echo '</select>';

                                if ($settings->mee == "MEE") {
                                echo '<select class="form-control input-inline select2me" name="submitted_for_id" style=""><option value="">' . $strings["documents_submittedfor"] . '</option>';

                                    $dr_cl = $doc_comp->getDriverClient(0, 0);
                                    $drcl_d = $dr_cl['driver'];
                                    foreach ($drcl_d as $drcld) {
                                        echo '<option value="' . $drcld->id . '" ';
                                        if (isset($return_submitted_for_id) && $return_submitted_for_id == $drcld->id) { echo ' selected="selected"'; }
                                        echo '>' .  formatname($drcld) . '</option>';
                                    }
                                echo '</select>';
                            }

                            $clients = $doc_comp->getAllClient();
                            echo '<select class="form-control showclientdivision input-inline" name="client_id"><option value="">' . $strings["settings_client"] . '</option>';
                                    foreach ($clients as $c) {
                                        $doit=true;
                                        if (!$this->Session->read('Profile.super')){
                                            $doit = $userclients == $c->id;
                                            $return_client_id= $userclients;
                                        }
                                        if($doit) {
                                            echo '<option value="' . $c->id . '" ';
                                            if (isset($return_client_id) && $return_client_id == $c->id) { echo ' selected="selected"';}
                                            echo '>' . ucfirst($c->company_name) . '</option>';
                                        }
                                    }
                                ?>

                            </select>


                            <input class="form-control input-inline" name="searchdoc" type="search" id="searchdoc"
                                   placeholder="<?= $strings["documents_search"]; ?>"
                                   value="<?php if (isset($search_text)) echo $search_text; ?>"
                                   aria-controls="sample_1"/>
                            <SCRIPT>
                                expandtofitplaceholder("searchdoc");
                                function expandtofitplaceholder(ElementName) {
                                    var input = document.getElementById(ElementName);
                                    input.setAttribute('size',input.getAttribute('placeholder').length);
                                }
                            </SCRIPT>

                            <button type="submit" class="btn btn-primary input-inline" id="search"><?= $strings["dashboard_search"]; ?></button>

                        </form>
                    </div>
                </div>


                <div class="clearfix"></div>

                <div class="form-body">
                    <div class="table-scrollable">
                        <table
                            class="table table-condensed table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr class="sorting">
                                <th title="Document ID"><?= $this->Paginator->sort('id', "ID"); ?></th>
                                <th><?= $this->Paginator->sort('document_type', $strings["documents_document"]); ?></th>

                                <?php if ($settings->mee == "MEE") { ?>

                                <th title="Order ID" ><?= $this->Paginator->sort('oid', $strings["documents_orderid"]); ?></th>
                                <?php } ?>

                                <th><?= $this->Paginator->sort('user_id', $strings["documents_submittedby"]); ?><?php if (isset($end)) echo $end;
                                        if (isset($start)) echo "//" . $start; ?></th>


                                <?php if ($settings->mee == "MEE") { ?>

                                <th><?= $this->Paginator->sort('uploaded_for', $strings["documents_submittedfor"]); ?><?php if (isset($end)) echo $end;
                                        if (isset($start)) echo "//" . $start; ?></th>

                                <?php } ?>

                                <th><?= $this->Paginator->sort('created', $strings["documents_created"]); ?></th>
                                <th><?= $this->Paginator->sort('client_id', $strings["settings_client"]); ?></th>
                                <th class="actions"><?= __($strings["dashboard_actions"]) ?></th>
                                <th><?= $this->Paginator->sort('draft', $strings["documents_status"]); ?></th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $row_color_class = "odd";
                                $subdoc = $this->requestAction('/profiles/getSub');
                                $docz = [''];
                                foreach ($subdoc as $d) {
                                    array_push($docz, $d->title);
                                }
                                $colors = $Manager->enum_table("color_class");

                                function hasget($name) {
                                    if (isset($_GET[$name])) {
                                        return strlen($_GET[$name]) > 0;
                                    }
                                    return false;
                                }

                                //var_dump($docz);
                                if (count($documents) == 0) {
                                    echo '<TR><TD COLSPAN="9" ALIGN="CENTER">' . $strings["documents_noresults"];
                                    if($debug){
                                        echo '<BR>' . $sql;
                                    }
                                    echo '</TD></TR>';
                                }

                                foreach ($documents as $docs){

                            if ($docs->document_type == 'feedbacks' && !$this->request->session()->read('Profile.super')) {
                                continue;
                            }

                            if ($row_color_class == "even") {
                                $row_color_class = "odd";
                            } else {
                                $row_color_class = "even";
                            }

                            $uploaded_by = getIterator($profiles, "id", $docs->user_id);
                            $uploaded_for = getIterator($profiles, "id", $docs->uploaded_for);

                            $getClientById = getIterator($clients, "id", $docs->client_id);// $doc_comp->getClientById($docs->client_id);
                            $orderID = $this->Number->format($docs->order_id);
                            if ($orderID) {
                                $orderDetail = $doc_comp->getOrderById($docs->order_id);
                            }

                            $getColorId = getIterator($subdoc, "id", $docs->sub_doc_id)->color_id;
                            $getColorId = getIterator($colors, "id", $getColorId)->color;
                            ?>
                            <tr ID="row<?= $docs->id; ?>" class="<?= $row_color_class; ?>" role="row">
                                <td class="v-center" align="center">
                                    <?php
                                    if ($docs->hasattachments) {
                                        echo '<i title="Has Attachment" class="fa fa-paperclip"></i>';
                                    }
                                    echo $this->Number->format($docs->id)
                                    ?>
                                </td>

                                <td width="220" style="width: 220px; white-space: nowrap;" class="v-center">
                                    <?php
                                    $VIEWURL = $this->request->webroot . "documents/view/" . $docs->client_id . "/" . $docs->id . '?type=' . $docs->sub_doc_id;
                                    if ($docs->sub_doc_id == 4) {
                                        $VIEWURL .= '&doc=' . urlencode($docs->document_type);
                                    }
                                    if ($docs->order_id) {
                                        $VIEWURL .= "&order_id=" . $docs->order_id;
                                    }
                                    $EDITURL = str_replace("/view/", "/add/", $VIEWURL);
                                    if ($docs->draft == 1 || isset($_GET["draft"])) {
                                        $VIEWURL = $EDITURL;
                                    }

                                    switch (1){//change the number to pick a style
                                    case 0://plain text
                                        echo h($docs->document_type);
                                        break;
                                    case 1://top block
                                    echo '<div class="dashboard-stat ';
                                    if (isset($getColorId)) {
                                        echo $getColorId;
                                    } else {
                                        echo "blue";
                                    }
                                    ?>">

                                    <a class="more" id="sub_doc_click1" href="<?= $VIEWURL; ?>">
                                        <?= h(str_replace('_', ' ', $docs->document_type)); //it won't let me put it in the desc  ?>
                                        <i class="fa fa-copy"></i>
                                    </a>
                    </div>

                    <?php break;
                    case 2: //tile, doesn't work. CSS not included?
                        ?>
                        <a href="<?= $this->request->webroot; ?>orders/productSelection?driver=0&amp;ordertype=MEE"
                           class="tile bg-yellow" style="display: block; height: 100px; ">
                            <div class="tile-body">
                                <i class="icon-docs"></i>
                            </div>
                            <div class="tile-object">
                                <div class="name">Order MEE</div>
                                <div class="number"></div>
                            </div>
                        </a>
                        <?php break;
                    }
                    echo '</td>';

                    if ($settings->mee == "MEE") {
                        echo '<td class="v-center" align="center">';
                        if ($orderID > 0) {
                            echo '<a href="' . $this->request->webroot . 'orders/vieworder/' . $orderDetail->client_id . '/' . $orderDetail->id;
                            if ($orderDetail->order_type) {
                                echo '?order_type=' . urlencode($orderDetail->order_type);
                                if ($orderDetail->forms) {
                                    echo '&forms=' . $orderDetail->forms;
                                }
                            }
                            echo '">' . $orderDetail->id . '</a>';
                        } else {
                            echo $strings["documents_na"];
                        }
                        echo '</td>';
                    }

                    echo '<td class="v-center">';
                    $docname = h($docs->document_type) . " #: " . $this->Number->format($docs->id);
                    if (is_object($uploaded_by)) {
                        $user = '<a href="' . $this->request->webroot . 'profiles/view/' . $docs->user_id . '" target="_blank">' . formatname($uploaded_by);
                        $docname .= ", " . $strings["documents_submittedby"] . " " . formatname($uploaded_by);
                    } else {
                        $user = $strings["dashboard_deletedprofile"];
                    }
                    echo $user . '</td>';

                    if ($settings->mee == "MEE") {
                        echo '<td class="v-center">';
                        if (is_object($uploaded_for)) {
                            $user = '<a href="' . $this->request->webroot . 'profiles/view/' . $docs->uploaded_for . '" target="_blank">' . formatname($uploaded_for);
                            if (!is_object($uploaded_by) || $uploaded_for->id <> $uploaded_by->id) {
                                $docname .= ", " . $strings["documents_submittedfor"] . " " . formatname($uploaded_for);
                            }
                        } else {
                            $user = $strings["dashboard_deletedprofile"];
                        }
                        echo $user . '</td>';
                    }

                    echo '<td class="v-center" align="center">' . getdatecolor(h($docs->created), $strings) . '</td><td class="v-center">';
                    $docname .= ", " . $strings["documents_at"] . " " . h($docs->created);
                    if (is_object($getClientById)) {
                        echo "<a href ='" . $this->request->webroot . "clients/edit/" . $docs->client_id . "?view'>" . ucfirst(h($getClientById->company_name)) . "</a>";
                    } else {
                        echo $strings["documents_missingclient"];
                    }

                    echo '</td><td class="actions util-btn-margin-bottom-5 v-center">';

                    if ($sidebar->document_list == '1' && !isset($_GET["draft"])) {
                        //echo $this->Html->link(__('View'), ['action' => 'view', $docs->client_id, $docs->id], ['class' => btnclass("VIEW")]);
                        echo '<a class="' . btnclass("VIEW") . '" href="' . $VIEWURL . '" style="margin-bottom: 0 !important;">' . $strings["dashboard_view"] . '</a>';
                    }

                    if ($sidebar->document_edit == '1' && ($profiletype->caneditall == 1 || $this->request->session()->read('Profile.super') == 1 || $this->request->session()->read('Profile.id') == $docs->user_id)) {
                        if (!$docs->order_id || $this->request->session()->read('Profile.super')) {
                            echo '<a class="' . btnclass("EDIT") . '" href="' . $EDITURL . '" style="margin-bottom: 0 !important;">' . $strings["dashboard_edit"] . '</a>';
                        }
                    }

                    $isssuper = $this->request->session()->read('Profile.super');
                    if ($sidebar->document_delete == '1' && ($docs->order_id == 0 || $isssuper)) {
                        $dl_show = $isssuper;
                        if (!$isssuper && $docs->user_id == $this->request->session()->read('Profile.id')) {
                            $dl_show = true;
                        }
                        if ($dl_show) {
                            echo '<a onclick="deletedocument(' . $docs->id . ", '" . isset($_GET['draft']) . "', '" . addslashes3($docname) . "'" . ');"';
                            echo 'class="' . btnclass("DELETE") . '" style="margin-bottom: 0 !important;">' . $strings["dashboard_delete"] . '</a>';
                        }
                    }

                    echo '</td><td align="center" class="v-center">';
                    if ($docs->draft == 1) {
                        $Color = "label-warning";
                        $Label = $strings["documents_draft"];
                    } else {
                        $Color = "label-success";
                        $Label = $strings["documents_saved"];
                    }
                    echo '<span class="label label-sm ' . $Color . '" style="padding:4px;">' . $Label . '</span></td></tr>';

                    $st_query = '';
                    } ?>
                    </tbody>
                    </table>

                </div>
            </div>

            <div class="form-actions" style="height:75px;">
                <div class="row">
                    <div class="col-md-12" align="right">

                        <?php
                        if(isset($_GET['doc_type']))
                        {
                            $st_query = $st_query.'?doc_type='.$_GET['doc_type'];
                        }
                        ?>
                        <div id="sample_2_paginate" class="dataTables_paginate paging_simple_numbers"
                             style="margin-top:-10px;">

                            <ul class="pagination sorting">
                                <?= $this->Paginator->prev('< ' . __($strings["dashboard_previous"])); ?>
                                <?= $this->Paginator->numbers(); ?>
                                <?= $this->Paginator->next(__($strings["dashboard_next"]) . ' >'); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
<style>
    @media print {
        .page-header {
            display: none;
        }

        .page-footer, .chat-form, .nav-tabs, .page-title, .page-bar, .theme-panel, .page-sidebar-wrapper, .more {
            display: none !important;
        }

        .portlet-body, .portlet-title {
            border-top: 1px solid #578EBE;
        }

        .tabbable-line {
            border: none !important;
        }

        a:link:after,
        a:visited:after {
            content: "" !important;
        }

        .actions {
            display: none
        }

        .paging_simple_numbers {
            display: none;
        }
    }

</style>
<script>
    $(function () {
        $('.sorting').find('a').each(function () {

            <?php if(isset($_GET['draft'])){?>
            var hrf = $(this).attr('href');
            if (hrf != "")
                $(this).attr('href', hrf + '&draft');
            <?php } ?>
        });
    })
</script>
<script>
    $('.applyBtn').live('click', function () {
        var to = $('.daterangepicker_end_input .input-mini').val();
        var from = $('.daterangepicker_start_input .input-mini').val();
        var url = '<?php echo $this->request->webroot; ?>documents/index';
        var base = url;

        <?php
        if(isset($_GET['searchdoc']))   {
        ?>
        if (url == base) {
            url = url + '?searchdoc=<?php echo $_GET['searchdoc']?>';
        }else {
            url = url + '&searchdoc=<?php echo $_GET['searchdoc']?>';
        }
        <?php
        }
        ?>
        <?php
        if(isset($_GET['submitted_by_id']))
        {
        ?>
        if (url == base) {
            url = url + '?submitted_by_id=<?php echo $_GET['submitted_by_id']?>';
        }else {
            url = url + '&submitted_by_id=<?php echo $_GET['submitted_by_id']?>';
        }
        <?php
        }
        ?>
        <?php
        if(isset($_GET['type']))
        {
        ?>
        if (url == base) {
            url = url + '?type=<?php echo $_GET['type']?>';
        }else {
            url = url + '&type=<?php echo $_GET['type']?>';
        }
        <?php
        }
        ?>
        <?php
        if(isset($_GET['client_id']))
        {
        ?>
        if (url == base) {
            url = url + '?client_id=<?php echo $_GET['client_id']?>';
        }else {
            url = url + '&client_id=<?php echo $_GET['client_id']?>';
        }
        <?php
        }
        ?>
        if (url == base) {
            url = url + '?to=' + to + '&from=' + from;
        } else {
            url = url + '&to=' + to + '&from=' + from;
        }
        window.location = url;
    });

    <?php if(isset($_GET['division'])&& $_GET['division']!=""){
    //var_dump($_GET);
    ?>
    var client_id = <?php echo $_GET['client_id'];?>;
    var division_id = <?php echo $_GET['division'];?>;
    //alert(client_id+'__'+division_id);
    if (client_id != "") {
        $.ajax({
            type: "post",
            data: "client_id=" + client_id,
            url: "<?php echo $this->request->webroot;?>clients/getdivisions/" + division_id,
            success: function (msg) {
//alert(msg);
                $('.clientdivision').html(msg);
            }
        });
    }
    <?php
    }?>
    $('.showclientdivision').change(function () {
        var client_id = $(this).val();
        if (client_id != "") {
            $.ajax({
                type: "post",
                data: "client_id=" + client_id,
                url: "<?php echo $this->request->webroot;?>clients/getdivisions",
                success: function (msg) {
                    $('.clientdivision').html(msg);
                }
            });
        }
    });
    var client_id = $('.showclientdivision').val();
    if (client_id != "") {
        $.ajax({
            type: "post",
            data: "client_id=" + client_id,
            url: "<?php echo $this->request->webroot;?>clients/getdivisions",
            success: function (msg) {
                $('.clientdivision').html(msg);
            }
        });
    }

    var Documents = <?= iterator_count($documents); ?>;
    function deletedocument(DocID, Draft, Name){
        var Confirm = '<?= addslashes3($strings["dashboard_confirmdelete"]); ?>';
        Confirm = Confirm.replace("%name%", Name);
        if (confirm(Confirm)){
            var URL = "<?= $this->request->webroot;?>documents/delete/" + DocID;
            if(Draft){
                URL = URL + "/draft";
            }
            $.ajax({
                type: "get",
                url: URL,
                success: function (msg) {
                    $('#row'+DocID).fadeOut();
                    Documents--;
                    if(!Documents){location.reload();}
                }
            });
        }
    }
</script>
