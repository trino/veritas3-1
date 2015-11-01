<?php
    $settings = $this->requestAction('settings/get_settings');
    $sidebar = $this->requestAction("settings/get_side/" . $this->Session->read('Profile.id'));
    $debug=$this->request->session()->read('debug');
    include_once('subpages/api.php');
    $language = $this->request->session()->read('Profile.language');
    $controller =  $this->request->params['controller'];
    $strings = CacheTranslations($language, array($controller  . "_%", "documents_%", "forms_dateformat"),$settings);
    if($debug && $language == "Debug"){ $Trans = " [Translated]"; } else {$Trans = "";}

    function getColor($products, $OrderType, $Default = "blue"){
        $product = getIterator($products, "Name", $OrderType);
        if (is_object($product)) { return $product->ButtonColor;}
        return $Default;
    }

?>

<h3 class="page-title">
    <?php
        if (isset($_GET['draft'])) {
            echo $strings["index_orderdrafts"];
        } else {
            echo $strings["index_orders"];
        }
    ?>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo $this->request->webroot; ?>"><?= $strings["dashboard_dashboard"];?></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href=""><?= $strings["index_orders"];?></a>
        </li>
    </ul>
    <div class="page-toolbar">

    </div>
    <a href="javascript:window.print();" class="floatright btn btn-info"><?= $strings["dashboard_print"]; ?></a>

    <?php
        if ($sidebar->orders_list == 1 && !isset($_GET["draft"])) {
            ?>
            <a href="<?php echo $this->request->webroot; ?>orders/orderslist?draft"
               class="floatright btn btn-warning btnspc">
                <?= $strings["index_orderdrafts"]; ?></a>
        <?php } elseif (isset($_GET["draft"])) { ?>
            <a href="<?php echo $this->request->webroot; ?>orders/orderslist" class="floatright btn btn-warning btnspc">
                <?= $strings["orders_all"];?></a>
        <?php }


    if ($sidebar->orders_create == 1  && false){
        foreach($products as $product){
            $alias = $product->Sidebar_Alias;
            if($sidebar->$alias ==1 && $product->Visible==1) {
                if(strtolower($product->Acronym) !="bul" && strtolower($product->Acronym) !="req") {
                    echo '<a href="' . $this->request->webroot . 'orders/productSelection?driver=0&ordertype=' . $product->Acronym . '"';
                    echo ' class="floatright btn ' . $product->ButtonColor . ' btnspc">' . $product->Name . "</a>";
                }else{
                    echo '<a href="' . $this->request->webroot . 'profiles?all" class="floatright btn ' . $product->ButtonColor . ' btnspc">'.(strtolower($product->Acronym) =="bul")?'Bulk Order':'Requalifed Order'.'</a>';
                }
            }
        }
    }
    ?>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-clipboard"></i>
                    <?= $strings["index_listorders"]; ?>
                </div>
            </div>
            <div class="portlet-body form">


                <div class="form-actions top chat-form" style="margin-bottom:0;" align="right">

                    <div class="btn-set pull-left">

                    </div>
                    <div class="btn-set pull-right">

                        <form action="<?php echo $this->request->webroot; ?>orders/orderslist" method="get">
                            <?php if (isset($_GET['draft'])) { ?><input type="hidden" name="draft"/><?php } ?>
                            <?php
                                $users = $doc_comp->getAllUser();
                            ?>
                            <select class="form-control input-inline" name="submitted_by_id" style="">
                                <option value=""><?= $strings["documents_submittedby"];?></option>
                                <?php
                                    foreach ($users as $u) {
                                        ?>
                                        <option value="<?php echo $u->id; ?>" <?php if (isset($return_user_id) && $return_user_id == $u->id) { ?> selected="selected"<?php } ?> ><?= formatname($u); ?></option>
                                    <?php
                                    }
                                ?>
                            </select>
                            <select class="form-control input-inline" name="uploaded_for" style="">
                                <option value=""><?= $strings["documents_submittedfor"];?></option>
                                <?php
                                    foreach ($users as $u) {
                                        ?>
                                        <option value="<?php echo $u->id; ?>" <?php if (isset($_GET['uploaded_for']) && $_GET['uploaded_for'] == $u->id) { ?> selected="selected"<?php } ?> ><?= formatname($u); ?></option>
                                        <?php
                                    }
                                ?>
                            </select>

                            <?php
                            if($this->request->session()->read('Profile.super')) {
                                $clients = $doc_comp->getAllClient();
                            ?>
                            <select class="form-control showdivision input-inline" name="client_id">
                                <option value=""><?=$strings["settings_client"]; ?></option>
                                <?php
                                    foreach ($clients as $c) {
                                        ?>
                                        <option
                                            value="<?php echo $c->id; ?>" <?php if (isset($return_client_id) && $return_client_id == $c->id) { ?> selected="selected"<?php } ?> ><?php echo ucfirst($c->company_name); ?></option>
                                    <?php
                                    }
                                ?>

                            </select>
                            <?php }?>
                            <div class="divisions input-inline" style="">
                                <!-- Divisions section -->
                            </div>

                            <input class="form-control input-inline" name="searchdoc" type="search"
                                   placeholder="<?=$strings["orders_search"];?>"
                                   value="<?php if (isset($search_text)) echo $search_text; ?>"
                                   aria-controls="sample_1"/>


                            <button type="submit" class="btn btn-primary input-inline"><?= $strings["dashboard_search"]; ?></button>


                        </form>
                    </div>
                </div>


                <div class="clearfix"></div>

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

                <div class="form-body">
                    <div class="table-scrollable">
                        <table
                            class="table table-condensed table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr class="sorting">
                                <th><?= $this->Paginator->sort('id', "ID"); ?></th>
                                <th><?= $this->Paginator->sort('orders.order_type', $strings["orders_ordertype"]); ?></th>
                                <th><?= $this->Paginator->sort('user_id', $strings["documents_submittedby"]); ?></th>
                                <th><?= $this->Paginator->sort('uploaded_for', $strings["documents_submittedfor"]); ?></th>
                                <th><?= $this->Paginator->sort('client_id', $strings["settings_client"]); ?></th>
                                <th><?=$strings["orders_division"]; ?></th>
                                <th><?= $this->Paginator->sort('created', $strings["documents_created"] . ""); ?></th>
                                <th class="actions"><?= __($strings["dashboard_actions"]) ?></th>
                                <!--th><?= $this->Paginator->sort('bright_planet_html_binary', 'Status'); ?></th-->
                                <th><?= $this->Paginator->sort('complete', $strings["documents_status"]); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $row_color_class = "odd";

                                function hasget($name){
                                    if (isset($_GET[$name])) {
                                        return strlen($_GET[$name]) > 0;
                                    }
                                    return false;
                                }

                                if (count($orders) == 0) {
                                    echo '<TR><TD COLSPAN="10" ALIGN="CENTER">' . $strings["orders_noresults"] . '</TD></TR>';
                                }

                                foreach ($orders as $order){
                                    $isRapid = substr($order->title,0, 12) == "RAPID ORDER ";

                                    if ($row_color_class == "even") {
                                        $row_color_class = "odd";
                                    } else {
                                        $row_color_class = "even";
                                    }
                                    if ($order->user_id) {
                                        $uploaded_by = $doc_comp->getUser($order->user_id);
                                    }
                                    if ($order->uploaded_for) {
                                        $uploaded_for = $doc_comp->getUser($order->uploaded_for);
                                    }
                                    $client = $this->requestAction("clients/getClient/" . $order->client_id);

                                    $EDITURL = $Manager->make_order_path($order);

                                    ?>
                                    <tr class="<?= $row_color_class; ?>" role="row">
                                        <td><?= $this->Number->format($order->id);
                                                if ($order->hasattachments) {
                                                    echo '<BR><i  title="Has Attachment" class="fa fa-paperclip"></i>';
                                                }  //echo $order->profile->title;      ?></td>
                                        <td style="min-width: 145px;">

                                            <?php
                                            if (is_object($order) && $order->order_type) {
                                                    echo '<div style="" class="dashboard-stat ';

                                                    $ordertype = FindIterator($products, "Acronym", $order->order_type);
                                                    if (is_object($ordertype)) {
                                                        echo $ordertype->ButtonColor;
                                                    } else {
                                                        echo "grey";
                                                    }
                                                    ?>">

                                                    <?php
                                                    if($order->order_type != 'BUL' && $order->order_type != 'REQ')  { ?>
                                                        
                                                    <a class="more" id="sub_doc_click1"
                                                        <?php if($isRapid) {
                                                           echo 'onclick="return false;';
                                                        } else {
                                                           echo 'href="';
                                                           if ($order->draft == "1" or isset($_GET["draft"])) {
                                                               echo $EDITURL;
                                                           } else if ($sidebar->document_list == '1' && !isset($_GET["draft"])) {
                                                               echo $this->request->webroot . 'orders/vieworder/' . $order->client_id . '/' . $order->id;
                                                               if ($order->order_type) {
                                                                   echo '?order_type=' . urlencode($order->order_type);
                                                                   if ($order->forms) echo '&forms=' . $order->forms;
                                                               }
                                                           } else {
                                                               if ($sidebar->document_list == '1') {
                                                                   echo $this->request->webroot . 'orders/addorder/' . $order->client_id . '/' . $order->id;
                                                                   if ($order->order_type) {
                                                                       echo '?order_type=' . urlencode($order->order_type);
                                                                       if ($order->forms) echo '&forms=' . $order->forms;
                                                                   }
                                                               } else {
                                                                   echo 'javascript:;';
                                                               }
                                                           }
                                                        }
                                                        echo '">';
                                                    } else  {
                                                        echo '<span class="more">';
                                                    }
                                                    if($order->order_type == 'REQ') {echo 'RE-QUALIFICATION';}
                                                    echo '<i class="fa fa-shopping-cart"></i>';
                                                    echo h(getField($ordertype, "Name", $language) . $Trans); //it won't let me put it in the desc
                                                    if($order->order_type != 'BUL' && $order->order_type != 'REQ') {
                                                        echo '</a>';
                                                    }else{
                                                        echo '</span>';
                                                    }
                                                    echo "</div>";
                                                } else {
                                                    echo "Unknown";
                                                }
                                                //if($order->draft == 1) echo ' (Draft)';
                                            ?>


                                        </td>
                                        <td><?php if (isset($uploaded_by)) echo '<a href="' . $this->request->webroot . 'profiles/view/' . $order->user_id . '" target="_blank">' . formatname($uploaded_by);?></td>
                                        <td><?php if (isset($uploaded_for)) echo '<a href="' . $this->request->webroot . 'profiles/view/' . $order->uploaded_for . '" target="_blank">' .formatname($uploaded_for) . "</a>" ?></td>
                                        <td><?php
                                                if (is_object($client)) {
                                                    echo "<a href ='" . $this->request->webroot . "clients/edit/" . $order->client_id . "?view' target='_blank'>" . ucfirst(h($client->company_name)) . "</a>";
                                                } else {
                                                    echo $strings["documents_missingclient"];
                                                }
                                            ?></td>
                                        <td><?php if ($order->division) {
                                                $div = $doc_comp->getDivById($order->division);
                                                if (is_object($div)) {
                                                    echo ucfirst($div->title);
                                                } elseif ($this->request->session()->read('Profile.profile_type') == 1) {
                                                    echo $strings["documents_missingdivision"] . ": " . $order->division; //only shows for admins
                                                }
                                            } ?></td>

                                        <td><?= getdatecolor(h($order->created)) ?></td>
                                        <td class="actions  util-btn-margin-bottom-5">

                                            <?php
                                                if ($sidebar->orders_list == '1' && $order->draft != 1 && $order->order_type!='BUL' && $order->order_type!='REQ' && !$isRapid) {
                                                    ?>
                                                    <a class="<?= btnclass("VIEW") ?>"
                                                       href="<?php echo $this->request->webroot; ?>orders/vieworder/<?php echo $order->client_id; ?>/<?php echo $order->id;
                                                           if ($order->order_type) {
                                                               echo '?order_type=' . urlencode($order->order_type);
                                                               if ($order->forms) echo '&forms=' . $order->forms;
                                                           } ?>"><?= $strings["dashboard_view"]; ?></a>
<?php

                                                }

                                                $super = $this->request->session()->read('Profile.super');// || $profiletype->caneditall;
                                                $candelete = $sidebar->orders_delete;
                                                //if (isset($super) && isset($_GET['draft'])) {
                                                    if ($sidebar->orders_edit == '1' && $order->order_type!='BUL' && $order->order_type!='REQ' && ($super==1 || $this->request->session()->read('Profile.id')==$order->user_id)) {
                                                        if (!isset($_GET['table']) && $order->draft == 1) {
                                                            ?>
                                                            <a class="<?= btnclass("EDIT") ?>"
                                                               href="<?= $EDITURL ?>"><?= $strings["dashboard_edit"]; ?></a>
<?php


                                                    }

                                                }
                                            ?>

                                            <?php if ($sidebar->orders_requalify == '1' && $order->draft == '0') {
                                                ?>
                                                <!--a class="clearfix btn btn-warning" href="<?php echo $this->request->webroot; ?>documents/productSelection?driver=<?php echo $order->uploaded_for; ?>"/>Re-qualify</a-->
                                            <?php
                                            }

                                            if (!isset($_GET['draft']) && is_object($order->profile) && ($order->draft == 0)) {
                                                ?>
                                                <a href="<?php echo $this->request->webroot; ?>profiles/view/<?php echo $order->profile->id ?>?getprofilescore=1"
                                                   class="<?= btnclass("btn-info", "blue-soft") ?>"><?= $strings["orders_scorecard"]; ?></a>
                                            <?php
                                            }


                                                if ($super || (isset($_GET['draft']) || $candelete && $this->request->session()->read('Profile.id') == $order->user_id)) {
                                                    ?><a
                                                    href="<?php echo $this->request->webroot; ?>orders/deleteorder/<?php echo $order->id; ?><?php if (isset($_GET['draft'])) echo "?draft"; ?>"
                                                    class="<?= btnclass("DELETE") ?>"
                                                    onclick="return confirm('<?= ProcessVariables($language, $strings["dashboard_confirmdelete"], array("name" => $order->id), true); ?>');">
                                                        <?= $strings["dashboard_delete"];?></a>
                                                <?php
                                                }



                                          //if (!isset($_GET['draft'])) echo $this->Html->link(__('Score Card'), ['controller' => 'orders', 'action' => 'viewReport', $order->client_id, $order->id], ['class' => 'btn btn-success']);
                                            ?>
                                        </td>


                                        <td valign="middle">
                                            <?php
                                            //   var_dump($order);
                                            if($order->draft == 1) {
                                                ?>
                                                 <span class="label label-sm label-warning"  style="float:right;padding:4px;"><?= $strings["documents_draft"]; ?></span>
                                                <?php

                                            }else if ($order->complete == 0) {
                                                    ?>
                                                    <span class="label label-sm label-primary" style="float:right;padding:4px;"><?= $strings["documents_pending"];?></span>
                                                <?php

                                            } else { ?>
                                                <span class="label label-sm label-success"  style="float:right;padding:4px;"><?= $strings["documents_complete"];?></span>
                                            <?php } ?>
                                        </td>

                                    </tr>
                                <?php }; ?>
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="form-actions" style="height:75px;">
                    <div class="row">
                        <div class="col-md-12" align="right">


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
<script>
    $(function () {
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
                    $('.divisions').html(msg);
                }
            });
        }
        <?php
        }
        //if(isset($_GET['division'])&& $_GET['division']!="")
        ?>

        $('.showdivision').change(function () {
            var client_id = $(this).val();
            if (client_id != "") {
                $.ajax({
                    type: "post",
                    data: "client_id=" + client_id,
                    url: "<?php echo $this->request->webroot;?>clients/getdivisions",
                    success: function (msg) {
                        $('.divisions').html(msg);
                    }
                });
            }
        });
        var client_id = $('.showdivision').val();
        if (client_id != "") {
            $.ajax({
                type: "post",
                data: "client_id=" + client_id,
                url: "<?php echo $this->request->webroot;?>clients/getdivisions",
                success: function (msg) {
                    $('.divisions').html(msg);
                }
            });
        }

    });
</script>
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