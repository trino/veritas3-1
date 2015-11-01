<h3 class="page-title">
			Primary logo manager
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo $this->request->webroot;?>">Home</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="#">Logo Manger</a>
					    <i class="fa fa-angle-right"></i>
					</li>
                    	<li>
						<a href="#">Primary Logo</a>
                        
					</li>
				</ul>
			
			</div>

<div class="row ">
	<div class="col-md-12">
		<!-- BEGIN SAMPLE FORM PORTLET-->


		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-user"></i>Choose A Primary Logo Manager
				</div>
				
			</div>
			<div class="portlet-body">
            
            <form action="" method="post" class="form-inline" role="form" >
            <?php foreach ($logos as $logo){ ?>
                <div class="form-group col-md-12">
                    <div class="col-md-1">
                        <input type="radio" value="<?php echo $logo->id;?>" name="logo" <?php echo ($logo->active == '1')?"checked='checked'":"" ;?>/>
                    </div>
                    <div class="col-md-10">
                        <img src="<?php echo $this->request->webroot;?>img/logos/<?php echo $logo->logo;?>" width="86px" height="14px" />
                    </div>
                </div>
                <div class="clearfix"></div>
                <hr />
                
            <?php }?>
            <input type="submit" class="btn btn-success" value="submit" name="submit" />
            </form>
      <!--      
	<div class="paginator">
		<ul class="pagination">
			<?= $this->Paginator->prev('< ' . __('previous')); ?>
			<?= $this->Paginator->numbers(); ?>
			<?=	$this->Paginator->next(__('next') . ' >'); ?>
		</ul>
		<p><?= $this->Paginator->counter(); ?></p>
	</div>-->
</div>
</div>
</div>
