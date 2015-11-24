<h3 class="page-title">
			Page manager
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo $this->request->webroot;?>">Home</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="#">Pages</a>
					    <i class="fa fa-angle-right"></i>
					</li>
                    	<li>
						<a href="#"><?php echo ucfirst(str_replace("-"," ",$this->request->pass[0]));?></a>
                        
					</li>
				</ul>
			
			</div>
<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXTRAS PORTLET-->
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-gift"></i>Page Manager
							</div>
						</div>
                        
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<form action="#" class="form-horizontal form-bordered">
                                <div class="form-body">
									<div class="form-group last">
										<label class="control-label col-md-3">Page Title</label>
										<div class="col-md-4">
											<input class="form-control" name="title" value="<?php echo ucfirst(str_replace("-"," ",$this->request->pass[0]));?>" />
										</div>
									</div>
								</div>
								<div class="form-body">
									<div class="form-group last">
										<label class="control-label col-md-3">Description</label>
										<div class="col-md-9">
											<textarea class="ckeditor form-control" name="editor1" rows="6"></textarea>
										</div>
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
											<button type="button" class="btn btn-primary">Cancel</button>
										</div>
									</div>
								</div>
							</form>
							<!-- END FORM-->
						</div>
					</div>
					<!-- END EXTRAS PORTLET-->
				</div>
			</div>