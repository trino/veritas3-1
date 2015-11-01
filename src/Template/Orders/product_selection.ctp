<!--h3 class="page-title">
    Place MEE Order
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo $this->request->webroot; ?>">Dashboard</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="">Place MEE Order
            </a>
        </li>
    </ul>

</div-->

<div class="row">
    <div class="col-md-12"></div>
    <?php
        
        $settings = $this->requestAction("settings/all_settings/" . $this->request->session()->read('Profile.id') . "/sidebar");
    include('subpages/profile/info_order2.php'); ?>
</div>
</div>