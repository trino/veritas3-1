<?php //$settings = $this->requestAction('settings/get_settings');?>
<?php $sidebar =$this->requestAction("settings/get_side/".$this->Session->read('Profile.id'));?>
<h3 class="page-title">
			Feedbacks <small>View/Edit/Delete Feedbacks</small>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo $this->request->webroot;?>">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
                    <li>
                        <a href="<?php echo $this->request->webroot;?>feedbacks">Feedbacks</a>
                    </li>
				</ul>
				<div class="page-toolbar">
					<div id="dashboard-report-range" style="padding-bottom: 6px;" class="pull-right tooltips btn btn-fit-height grey-salt" data-placement="top" data-original-title="Change dashboard date range">
						<i class="icon-calendar"></i>&nbsp;
						<span class="thin uppercase visible-lg-inline-block">&nbsp;</span>&nbsp;
						<i class="fa fa-angle-down"></i>
					</div>
				</div>
                <a href="javascript:window.print();" class="floatright btn btn-info">Print</a>
			</div>



<div class="row">
    <div class="col-md-12">





        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i>
                    Feedbacks
                </div>
            </div>    
            <div class="portlet-body">








				<div class="chat-form">
					<form>
                        
                        <div class="col-md-6"  style="padding-left:0;">
                                    <input class="form-control input-inline" type="search"     placeholder=" Search for Feedbacks"      aria-controls="sample_1"/>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
						
                        
                        <div class="col-md-3 col-sm-12">
                        <a href="<?php echo $this->request->webroot; ?>feedbacks/add" class="btn btn-primary">Add New Feedback</a>
                        </div>

					</form>
				</div>


				<div class="clearfix"></div>



                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered table-hover dataTable no-footer">
                    	<thead>
                        
                        
                    		<tr>
                    			<th>Title</th>                   			
                    			<th class="actions"><?= __('Actions') ?></th>
                    		</tr>
                    	</thead>
                    	<tbody>
                        
                        <?php
                        $row_color_class = "odd";
                        foreach ($feedbacks as $feedback):

                            if($row_color_class=="even")
                            {
                                $row_color_class ="odd";
                            }else{
                                $row_color_class ="even";
                            }
                            ?>

                            <tr class="<?=$row_color_class;?>" role="row">
                                <td><?= $this->Number->format($feedback->id) ?></td>
                                <td><?= h($feedback->title) ?></td>
                                <td class="actions">

                                    <?php  if($sidebar->document_list=='1'){ echo $this->Html->link(__('View'), ['action' => 'view', $feedback->id], ['class' => 'btn btn-info']);} ?>
                                    <?php  if($sidebar->document_edit=='1'){ echo $this->Html->link(__('Edit'), ['action' => 'edit', $feedback->id], ['class' => 'btn btn-primary']);} ?>
                                    <?php  if($sidebar->document_delete=='1'){ echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $feedback->id], ['class' => 'btn btn-danger'], ['confirm' => __('Are you sure you want to delete # {0}?', $feedback->id)]);} ?>

                                </td>
                            </tr>

                        <?php endforeach; ?>
                    	
                    		
                    	</tbody>
            	</table>

                </div>



				<div id="sample_2_paginate" class="dataTables_paginate paging_simple_numbers">
					<ul class="pagination">
						<li id="sample_2_previous" tabindex="0" aria-controls="sample_2"
							class="paginate_button previous disabled"><a href="#"><i
									class="fa fa-angle-left"></i></a></li>
						<li tabindex="0" aria-controls="sample_2" class="paginate_button active"><a href="#">1</a>
						</li>
						<li tabindex="0" aria-controls="sample_2" class="paginate_button "><a href="#">2</a></li>
						<li tabindex="0" aria-controls="sample_2" class="paginate_button "><a href="#">3</a></li>
						<li tabindex="0" aria-controls="sample_2" class="paginate_button "><a href="#">4</a></li>
						<li tabindex="0" aria-controls="sample_2" class="paginate_button "><a href="#">5</a></li>
						<li id="sample_2_next" tabindex="0" aria-controls="sample_2" class="paginate_button next"><a
								href="#"><i class="fa fa-angle-right"></i></a></li>
					</ul>
					<ul class="pagination">
						<li class="prev disabled">
							<a href="">< previous</a>
						</li>
						<li class="next">
							<a href="#" rel="next">next ></a>
						</li>
					</ul>
				</div>



            </div>
        </div>
        </div>
        </div>
<style>
@media print {
    .page-header{display:none;}
    .page-footer,.chat-form,.nav-tabs,.page-title,.page-bar,.theme-panel,.page-sidebar-wrapper,.more{display:none!important;}
    .portlet-body,.portlet-title{border-top:1px solid #578EBE;}
    .tabbable-line{border:none!important;}
    a:link:after,
    a:visited:after {
        content: "" !important;
    }
    .actions{display:none}
    .paging_simple_numbers{display:none;}
    }
    
</style>