<?php
include_once('subpages/api.php');
$settings = $Manager->get_settings();
$language = $this->request->session()->read('Profile.language');
$strings = CacheTranslations($language, "pages_%",$settings);//,$registry);//$registry = $this->requestAction('/settings/getRegistry');

$title=getFieldname("title", $language);
$desc=getFieldname("desc", $language);
?>

<h3 class="page-title">
			<?php echo ucfirst($content->$title);?>
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
						<a href="#"><?php echo ucfirst($content->$title);?></a>
                        
					</li>
				</ul>
			
			</div>
<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXTRAS PORTLET-->
					<div class="portlet">
						
                        
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							
                                
								<div class="form-body">
									<div class="form-group last">
									   <?php echo $content->$desc;?>
									</div>
								</div>
								
							
							<!-- END FORM-->
						</div>
					</div>
					<!-- END EXTRAS PORTLET-->
				</div>
			</div>