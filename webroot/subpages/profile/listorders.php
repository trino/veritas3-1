 <?php
 if($this->request->session()->read('debug'))
        echo "<span style ='color:red;'>listorders.php #INC153</span>";
 ?>
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                        <tr class="sorting">
                            <th width="75%"><a href="<?=$this->request->webroot;?>documents/orderslist?sort=orders.title&amp;direction=asc">Titl2e</a></th>
                            <th width="25%"><a href="<?=$this->request->webroot;?>documents/orderslist?sort=created&amp;direction=asc"">Created</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $getOrders = $this->requestAction('profiles/getOrders/'.$id);
                        $found=false;
                        $class="even";
                        foreach($getOrders as $g ) {

                            if($g->draft == 0){
                          ?>
                            <tr class="<?= $class ?>" role="row">
                                <td>
                                <!--<input type="checkbox" id="<?php echo $g->id ?>"/>-->
                                <a href="<?php echo $this->request->webroot; ?>orders/vieworder/<?php echo $g->client_id.'/'.$g->id; ?>"><?php echo $g->title; ?></a>
                                </td>
                                <td align="center"><?php echo $g->created; ?></td>
                            </tr>
                            <?php

                            $found=true;
                            if ($class=="even"){$class = "odd";} else {$class="even";}
                        }
                        }
                        if (!$found) {//count returns 1 even when there is 0 results :/
                            echo '<tr class="even" role="row"><td colspan="2" align="center">No orders found</td></tr></tr>';
                        }

                        ?>
                        </tbody>
                    </table>

                </div>
                <div id="sample_2_paginate" class="dataTables_paginate paging_simple_numbers">
                    <ul class="pagination sorting">
                        <li class="prev disabled"><a href="" style="width:100px;">&lt; previous</a></li> <li class="next disabled" align="right"><a href="" style="width:100px;">next &gt;</a></li>
                    </ul>
                </div>