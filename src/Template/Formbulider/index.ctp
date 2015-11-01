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


<?php
    $getProfileType = $this->requestAction('profiles/getProfileType/' . $this->Session->read('Profile.id'));
    $settings = $this->requestAction('settings/get_settings');
    $sidebar = $this->requestAction("settings/all_settings/" . $this->request->session()->read('Profile.id') . "/sidebar");

    function hasget($name)
    {
        if (isset($_GET[$name])) {
            return strlen($_GET[$name]) > 0;
        }
        return false;
    }

?>
<h3 class="page-title">
    <?php echo ucfirst($settings->profile); ?>s
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo $this->request->webroot; ?>">Dashboard</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href=""><?php echo ucfirst($settings->profile); ?>s</a>
        </li>
    </ul>
    <a href="javascript:window.print();" class="floatright btn btn-info">Print</a>
</div>

<div class="row">
    <div class="col-md-12">
            <div class="portlet box green-haze">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i>
                    List
                </div>
            </div>
            <div class="portlet-body form">
                <!-- Header End -->
                <div class="row margin-top-20">
                    <div class="col-md-12">
                        <div class="profile-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet paddingless">
                                        <div class="portlet-title line" style="display:none;">
                                            <div class="caption caption-md">
                                                <i class="icon-globe theme-font hide"></i>
                                                <span
                                                    class="caption-subject font-blue-madison bold">Form Bulider</span>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END PROFILE CONTENT -->
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>
