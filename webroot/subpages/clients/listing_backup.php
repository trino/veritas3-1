<div class="row">

    <div class="col-md-12">
        <div class="portlet box grey-salsa">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe"></i>
                    List <?php echo ucfirst($settings->client); ?>s
                </div>
            </div>
            <div class="portlet-body form">


                <form action="<?php echo $this->request->webroot; ?>clients/search" method="get" class="form-actions"
                      align="right">


                    <?php if (isset($_GET['draft'])) { ?><input type="hidden" name="draft"/><?php } ?>

                    <input class="form-control input-inline" name="search" type="search"
                           placeholder="Search <?php echo ucfirst($settings->client); ?>s"
                           value="<?php if (isset($search_text)) echo $search_text; ?>"
                           aria-controls="sample_1"/>
                    <button type="submit" class="btn btn-primary input-inline" style="">Search</button>

                </form>


                <div class="form-body">
                    <div class="table-scrollable ">
                        <table class="table table-hover  table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr class="sorting">
                                <th><?= $this->Paginator->sort('id', 'Id', ['escape' => false]) ?></th>
                                <th>Logo</th>
                                <th><?= $this->Paginator->sort('company_name', ucfirst($settings->client), ['escape' => false]) ?></th>

                                <th class="actions"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $profile_id = $this->request->session()->read('Profile.id');

                                if (count($client) == 0) {
                                    echo '<TR><TD COLSPAN="6" ALIGN="CENTER">No ' . strtolower($settings->client) . 's found';
                                    if (isset($_GET['search'])) {
                                        echo " matching '" . $_GET['search'] . "'";
                                    }
                                    echo '</TD></TR>';
                                }

                                foreach ($client as $clients):
                                    $profiles = explode(",", $clients->profile_id);

                                    if (in_array($profile_id, $profiles) || $this->request->session()->read('Profile.super') == '1') {
                                        ?>


                                        <tr>
                                            <td><?= $this->Number->format($clients->id) ?></td>
                                            <td>
                                                <img class="img-responsive" style="max-width:180px;"
                                                     id="clientpic"
                                                     alt=""
                                                     src="<?php
                                                        echo $this->request->webroot;
                                                        $filename = "img/logos/MEELogo.png";
                                                        if (isset($clients->image) && $clients->image) {
                                                            $testfile = "img/jobs/" . $clients->image;
                                                            if (file_exists($testfile)) {
                                                                $filename = $testfile;
                                                            }
                                                        }
                                                        echo $filename;
                                                        ?>"/>
                                                    <?php
                                                    } else {
                                                        ?>
                                                        <img class="img-responsive" style="max-width:180px;"
                                                             id="clientpic"
                                                             alt=""
                                                             src="<?php echo $this->request->webroot; ?>
                                                    <?php
                                                    }
                                                ?>"/>

                                            </td>
                                            <td><?= ucfirst(h($clients->company_name)) ?></td>

                                            <td class="actions  util-btn-margin-bottom-5">


                                                <?php
                                                    if ($sidebar->client_list == '1' && !isset($_GET["draft"])) {
                                                        ?>
                                                        <a class="btn btn-primary"
                                                           href="<?php echo $this->request->webroot; ?>clients/edit/<?php echo $clients->id; ?>?view">View</a>
                                                    <?php
                                                    }
                                                    if ($sidebar->client_edit == '1') {
                                                        echo $this->Html->link(__('Edit'), ['controller' => 'clients', 'action' => 'edit', $clients->id], ['class' => 'btn btn-primary']);
                                                    }
                                                    if ($sidebar->client_delete == '1') { ?>
                                                        <a href="<?php echo $this->request->webroot; ?>clients/delete/<?php echo $clients->id; ?><?php echo (isset($_GET['draft']))?"?draft":""; ?>"
                                                           onclick="return confirm('Are you sure you want to delete <?= h($clients->company_name) ?>?');"
                                                           class="btn btn-danger">Delete</a>

                                                    <?php }


                                                    if ($sidebar->document_create == '1' && !isset($_GET["draft"])) {
                                                        echo $this->Html->link(__('Create ' . ucfirst($settings->document)), ['controller' => 'documents', 'action' => 'add', $clients->id], ['class' => 'btn btn-success']);

                                                    }

                                                    if ($sidebar->orders_create == '1' && !isset($_GET["draft"])) {
                                                        ?>
                                                        <a href="<?= $this->request->webroot; ?>orders/productSelection?client=<?= $clients->id;                                                                         ?>&ordertype=MEE" class="btn red-flamingo">Place Order</a>

                                                        <a href="<?= $this->request->webroot; ?>orders/productSelection?client=<?= $clients->id;                                                                         ?>&ordertype=CART" class="btn btn-success"> A La Carte/Re-qualify</a>

                                                    <?php
                                                    }

                                                    if ($sidebar->orders_list == '1' && !isset($_GET["draft"])) {
                                                        ?>
                                                        <a href="<?= $this->request->webroot; ?>orders/orderslist/?client_id=<?= $clients->id; ?>" class="btn btn-primary">View Orders</a>
                                                    <?php

                                                    }
                                                ?>
                                            </td>
                                        </tr>

                                    <?php
                                    } // endif
                                endforeach;

                            ?>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="form-actions" style="height:75px;">
                    <div class="row">
                        <div class="col-md-12" align="right">

                            <div id="sample_2_paginate" class="dataTables_paginate paging_simple_numbers"
                                 style="margin-top:-10px;">

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