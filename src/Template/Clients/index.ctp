<?php
$settings = $this->requestAction('settings/get_settings');
$sidebar =$this->requestAction("settings/all_settings/".$this->Session->read('Profile.id')."/sidebar");
include_once('subpages/api.php');
$language = $this->request->session()->read('Profile.language');
$strings = CacheTranslations($language, $this->request->params['controller'] . "_%",$settings);
?>
<h3 class="page-title">
			<?php echo ucfirst($strings["settings_client"]);?>s
			</h3>
    <div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo $this->request->webroot . '">' . $strings["dashboard_dashboard"] ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href=""><?php echo ucfirst($strings["settings_client"]);?>s</a>
					</li>
				</ul>

			<a href="javascript:window.print();" class="floatright btn btn-info"><?= $strings["dashboard_print"]; ?></a>

        <?php  if ($sidebar->client_create == 1) {  ?>
             <a href="<?php echo $this->request->webroot; ?>clients/add" class="floatright btn btn-primary btnspc">
                <?php echo $strings["index_createclient"];?></a>
        <?php } ?>

			</div>
<?php include('subpages/clients/listing.php');?>