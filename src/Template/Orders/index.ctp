<?php $settings = $Manager->get_settings();
$sidebar = $Manager->loadpermissions($Me, "sidebar");
//this page is bypassed to orders/orderslist

function formatname($profile){

}

?>

<h3 class="page-title">
    Orders <?php if(isset($_GET['draft'])){?>(Draft)<?php }?>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo $this->request->webroot; ?>">Dashboard</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="">Orders</a>
        </li>
    </ul>
    <div class="page-toolbar">

    </div>
    <a href="javascript:window.print();" class="floatright btn btn-info">Print</a>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-clipboard"></i>
                    List Orders
                </div>
            </div>
            <div class="portlet-body">
                <div class="chat-form">
                    <form action="<?php echo $this->request->webroot; ?>orders/index" method="get">
                        <?php if(isset($_GET['draft'])){?><input type="hidden" name="draft" /><?php }?>
                        <?php
                            $users = $doc_comp->getAllUser();
                        ?>
                        <div class="col-md-2" style="padding-left:0;">
                            <input class="form-control" name="searchdoc" type="search" placeholder="Search Order Title"
                                   value="<?php if (isset($search_text)) echo $search_text; ?>"
                                   aria-controls="sample_1"/>
                        </div>
                        <div class="col-md-3" style="padding-left:0;">
                            <select class="form-control" name="submitted_by_id" style="">
                                <option value="">Submitted by</option>
                                <?php
                                    foreach ($users as $u) {
                                        ?>
                                        <option
                                            value="<?php echo $u->id; ?>" <?php if (isset($return_user_id) && $return_user_id == $u->id) { ?> selected="selected"<?php } ?> ><?= formatname($u); ?></option>
                                    <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <!--
                        <?php
                            $type = $doc_comp->getDocType();
                        ?>
						<div class="col-md-3 col-sm-12">
							<select class="form-control" name="type">
								<option value="">Order Type</option>
								<?php
                            foreach ($type as $t) {
                                ?>
                                        <option value="<?php echo $t->title;?>" <?php if (isset($return_type) && $return_type == $t->title) { ?> selected="selected"<?php } ?> ><?php echo ucfirst($t->title); ?></option>
                                        <?php
                            }
                        ?>
                                 <option value="orders" <?php if (isset($return_type) && $return_type == 'orders') { ?> selected="selected"<?php } ?>>Orders</option>
                                 <option value="feedbacks" <?php if (isset($return_type) && $return_type == 'feedbacks') { ?> selected="selected"<?php } ?>>Feedbacks</option>
							</select>
						</div>-->
                        <!--</form>-->
                        <?php
                            $clients = $doc_comp->getAllClient();
                        ?>
                        <!--<form action="<?php //echo $this->request->webroot; ?>documents/filterByClient" method="get">-->
                        <div class="col-md-3 " style="padding-left:0;">
                            <select class="form-control showdivision" name="client_id">
                                <option value=""><?php echo ucfirst($settings->client); ?></option>
                                <?php
                                    foreach ($clients as $c) {
                                        ?>
                                        <option
                                            value="<?php echo $c->id;?>" <?php if (isset($return_client_id) && $return_client_id == $c->id) { ?> selected="selected"<?php } ?> ><?php echo $c->company_name; ?></option>
                                    <?php
                                    }
                                ?>

                            </select>
                        </div>

                        <div class="col-md-2 divisions" style="padding-left:0;">
                          <!-- Divisions section -->  
                        </div>
                        <div class="col-md-2" align="Right" style="padding-left:0;padding-right:0;">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>

                    </form>
                </div>


                <div class="clearfix"></div>

                <script>
                $(function () {
                    $('.sorting').find('a').each(function(){
                        
                       <?php if(isset($_GET['draft'])){?>
                       var hrf = $(this).attr('href');
                       if(hrf!="")
                        $(this).attr('href',hrf+'&draft');
                       <?php } ?> 
                    });
                })
                </script>
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                        <tr class="sorting">
                            <th><?= $this->Paginator->sort('id'); ?></th>
                            <th><?= $this->Paginator->sort('orders.title', "Title"); ?></th>
                            <th><?= $this->Paginator->sort('user_id', 'Uploaded by'); ?></th>
                            <th><?= $this->Paginator->sort('uploaded_for', 'Uploaded for'); ?></th>
                            <th><?= $this->Paginator->sort('client_id', ucfirst($settings->client)); ?></th>
                            <th>Division</th>                            
                            <th><?= $this->Paginator->sort('created', 'Created'); ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                            <th><?= $this->Paginator->sort('bright_planet_html_binary', 'Status'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $row_color_class = "odd";

                        if (count($orders) == 0){
                            echo '<TR><TD COLSPAN="10" ALIGN="CENTER">No orders found';
                            if(isset($_GET['searchdoc'])) { echo " matching '" . $_GET['searchdoc'] . "'";}
                            echo '</TD></TR>';
                        }

                            foreach ($orders as $order):
                                
                                if ($row_color_class == "even") {
                                    $row_color_class = "odd";
                                } else {
                                    $row_color_class = "even";
                                }
                                    if($order->user_id) {
                                        $uploaded_by = $doc_comp->getUser($order->user_id);
                                    }
                                    if($order->uploaded_for) {
                                        $uploaded_for = $doc_comp->getUser($order->uploaded_for);
                                    }
                               
                                $client = $this->requestAction("clients/getClient/" . $order->client_id);
                                ?>
                                <tr class="<?= $row_color_class; ?>" role="row">
                                    <td><?php $this->Number->format($order->id); //echo $order->profile->title;
                                        if($order->hasattachments) { echo '<BR><i  title="Has Attachment" class="fa fa-paperclip"></i>';}  ?></td>
                                    <td><?= h($order->title) ?></td>
                                    <td><?php if(isset($uploaded_by)) {echo h($uploaded_by->username)} ?></td>
                                    <td><?php if(isset($uploaded_for)) {echo h($uploaded_for->fname.' '.$uploaded_for->mname.' '.$uploaded_for->lname)} ?></td>
                                    <td><?php
                                        if (is_object($client)) {
                                            echo h($client->company_name);
                                        } else {
                                            echo "Deleted " . $settings->client;
                                        }
                                    ?></td>
                                    <td><?php if($order->division){$div = $doc_comp->getDivById($order->division);echo $div->title;}else{echo '';} ?></td>
                                                                        
                                    <td><?= h($order->created) ?></td>
                                    <td class="actions  util-btn-margin-bottom-5">

                                        <?php
                                            if ($sidebar->orders_list == '1') {
                                                if (!isset($_GET['table']))
                                                    echo $this->Html->link(__('View'), ['action' => 'vieworder', $order->client_id, $order->id], ['class' => 'btn btn-info']);
                                                else
                                                    echo $this->Html->link(__('View'), ['action' => 'vieworder', $order->client_id, $order->id, $_GET['table']], ['class' => 'btn btn-info']);
                                            } ?>

                                        <?php
                                            $super = $this->request->session()->read('Profile.super');
                                            if (isset($super) || isset($_GET['draft'])) {
                                                if ($sidebar->orders_edit == '1') {
                                                    if (!isset($_GET['table']) && $order->draft==1){
                                                        echo $this->Html->link(__('Edit'), ['controller' => 'orders', 'action' => 'addorder', $order->client_id, $order->id], ['class' => 'btn btn-primary']);
                                                        }
                                                    elseif(isset($_GET['table'])){
                                                        echo $this->Html->link(__('Edit'), ['controller' => 'orders', 'action' => 'addorder', $order->client_id, $order->id, $_GET['table']], ['class' => 'btn btn-primary']);
                                                        }

                                                }
                                                if ($sidebar->orders_delete == '1') {
                                                    ?><a
                                                    href="<?php echo $this->request->webroot;?>orders/deleteorder/<?php echo $order->id;?><?php if(isset($_GET['draft']))echo "?draft";?>"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('<?= ProcessVariables($language, $strings["dashboard_confirmdelete"], array("name" => ucfirst(h($order->title))), true);?>');">
                                                        <?= $strings["dashboard_delete"]; ?></a>
                                                <?php
                                                }
                                            }
                                        //clients_requalify orders_scorecard documents_complete documents_pending documents_draft
                                         if ($sidebar->orders_requalify == '1' && $order->draft == '0') echo $this->Html->link(__($strings["clients_requalify"]), ['controller' => 'orders', 'action' => 'addorder', $order->client_id, $order->id], ['class' => 'btn btn-warning']);

                                        if (!isset($_GET['draft'])) echo $this->Html->link(__($strings["orders_scorecard"]), ['controller' => 'orders', 'action' => 'viewReport', $order->client_id, $order->id], ['class' => 'btn btn-success']);?>
                                    </TD><td valign="middle">
                                        <?php if (!isset($_GET['draft'])) { ?>
                                            <?php if (isset($order->bright_planet_html_binary)) { ?>
                                                <span class="label label-sm label-success"
                                                      style="float:right;padding:4px;"><?= $strings["documents_complete"] ?></span>
                                            <?php } else { ?>
                                                <span class="label label-sm label-primary" style="float:right;padding:4px;"><?= $strings["documents_pending"] ?></span>
                                            <?php }
                                        }else{?>
                                            <span class="label label-sm label-primary" style="float:right;padding:4px;"><?= $strings["documents_draft"] ?></span>

                                        <?php } ?>

                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>


                <div id="sample_2_paginate" class="dataTables_paginate paging_simple_numbers">
                    <ul class="pagination sorting">
                    


                        <?= $this->Paginator->prev('< ' . __('previous')); ?>
                        <?= $this->Paginator->numbers(); ?>
                        <?= $this->Paginator->next(__('next') . ' >'); ?>
                    </ul>
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
            if(client_id !="")
            {
                $.ajax({
                    type: "post",
                    data: "client_id="+client_id,
                    url: "<?php echo $this->request->webroot;?>clients/getdivisions",
                    success: function(msg){
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