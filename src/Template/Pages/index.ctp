<?php
$settings = $this->requestAction('settings/get_settings');
$sidebar =$this->requestAction("settings/get_side/".$this->Session->read('Profile.id'));
$debug=$this->request->session()->read('debug');
include_once('subpages/api.php');
$language = $this->request->session()->read('Profile.language');
$strings = CacheTranslations($language, array("clients_%", "index_%"),$settings);
if($debug && $language == "Debug"){ $Trans = " [Translated]"; } else {$Trans = "";}
if ($sidebar->training == 1 && $sidebar->client_list == 0) {
    header("Location: " . $this->request->webroot . "training");
    die();
}
?>



<script type="text/javascript" src="<?= $this->request->webroot;?>js/datetime.js"></script>
<body onLoad="ajaxpage('schedules/timezone');">

<div class="col-md-8" style="padding: 0;">
<h3 class="page-title">
    <?php
        echo $strings["dashboard_dashboard2"];
        if($settings->mee == 'MEE'){ echo " <small>" . $strings["index_qualify"] . "</small>"; }
    ?>

</h3>
</div>






<?php
    if(!$this->request->session()->read('Profile.super'))
    {
        $logomain = $this->requestAction('/clients/getLogo');
        if($logomain){
            ?>
            <div class="mainlogo col-md-4" style="text-align: right;padding:0;">
                <?php
                    //var_dump($logomain)
                    if(isset($logomain['client']) && $logomain['client'])
                    {
                        ?>
                        <img src="<?php echo $this->request->webroot;?>img/jobs/<?php echo $logomain['client'];?>" height="50px;" />
                    <?php

                    }
                    else
                    {
                        if(isset($logomain['setting']))
                        {
                            ?>
                            <img src="<?php echo $this->request->webroot;?>img/clients/<?php echo $logomain['setting'];?>" height="50px;" />
                        <?php

                        }
                    }
                ?>
            </div>
            
        <?php
        }}
?>
<div class="clearfix"></div>















<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo $this->request->webroot . '">' . $strings["dashboard_dashboard"]; ?></a>

        </li>

    </ul>
</div>


<div class="clearfix"></div>

<?php include('subpages/home_topblocks.php'); ?>


<? if ($settings->mee !="AFIMAC SMI"){ ?>
<div class="clearfix"></div>
<?php include('subpages/home_blocks.php'); ?>
<?}?>
<div class="clearfix"></div>

<?php
if(!$hideclient){
    $settings = $this->requestAction('settings/get_settings');
    $sidebar =$this->requestAction("settings/get_side/".$this->Session->read('Profile.id'));
    include('subpages/clients/listing.php');
}
echo '<div class="clearfix"></div>';
?>


<?php if($sidebar->recent ==1){?>
<?php include('subpages/recent_activities.php'); ?>
<div class="clearfix"></div>
<?php }?>



<style>
@media print {
    .page-header{display:none;}
    .page-footer,.nav-tabs,.page-title,.page-bar,.theme-panel,.page-sidebar-wrapper,.more{display:none!important;}
    .portlet-body,.portlet-title{border-top:1px solid #578EBE;}
    .tabbable-line{border:none!important;}
    }
</style>