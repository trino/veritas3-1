<!-- BEGIN PORTLET-->
<!--<div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-briefcase"></i>Pages
        </div>
        -->
<?php
 if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/profile/page.php #INC123</span>";}


function makepage($webroot, $tabIndex, $name, $cms, $active){
    //$cms = $This->requestAction("/pages/get_content/' . $name . '");
    $Title = ucfirst(str_replace("_", " ", $name));

    echo '<!-- BEGIN FORM-->
                                        <div class="tab-pane ' . $active . '" id="subtab_1_' . $tabIndex . '">
                                            <div class="portlet box">
                                                <div class="portlet-body form">
<form action="' . $webroot . 'pages/edit/' . $name . '" method="post" class="form-horizontal form-bordered" id="' . $name . '">
    <input type="hidden" name="languages" value="English,French" />
    <div class="form-body">
        <div class="form-group last">
            <label class="col-md-2"></label>
            <label class="col-md-5" align="CENTER">English</label>
            <label class="col-md-5" align="CENTER">français</label>
        </div>
    </div>

    <div class="form-body">
        <div class="form-group last">
            <label class="control-label col-md-2">' . $Title . ' Title</label>
            <div class="col-md-5">
                <input class="form-control" name="title" id="title-' . $name . '" value="' . ucfirst($cms->title) . '"/>
            </div>
             <div class="col-md-5">
                <input class="form-control" name="titleFrench" id="titleFrench-' . $name . '" value="' . ucfirst($cms->titleFrench) . '"/>
            </div>
        </div>
    </div>
    <div class="form-body">
        <div class="form-group last">
            <label class="control-label col-md-2">Description</label>
            <div class="col-md-5">
                                                                    <textarea class="ckeditor form-control"
                                                                              name="desc" rows="6" id="desc' . $name . '">' . $cms->desc . '</textarea>
            </div>
            <div class="col-md-5">
                                                                    <textarea class="ckeditor form-control"
                                                                              name="descFrench" rows="6" id="descFrench' . $name . '">' . $cms->descFrench . '</textarea>
            </div>
        </div>
    </div>
    <div class="form-actions" style="margin-left: -10px;margin-right: -10px;">
        <div class="row" align="right">
            <div class="col-md-offset-2 col-md-10">
                <button type="submit"   class="btn blue"  >
                    Save Changes
                </button>
                <button type="button" class="btn default" style="margin-right: 8px;">Cancel
                </button>
            </div>
        </div>

    </div>
</form></div></div></div>
<!-- END FORM-->';
    return "";
}
//<button type="submit"   class="btn blue" onclick="savepage(' . "'" . $name . "'" . ');" >
$pages = array(11 => "product_example", 6 => "help",  7 => "privacy_code", 8 => "version_log",  4 => "terms", 5 => "faq");

echo '<ul class="nav nav-tabs nav-justified">';
$class = "active";
foreach($pages as $key => $value){
    $Title = ucfirst(str_replace("_", " ", $value));
    echo '<li class="' . $class . '"><a href="#subtab_1_' . $key . '" data-toggle="tab">' . $Title . '</a></li>';
    $class="";
}
echo '</ul><div class="portlet-body"><div class="tab-content">';
$class = "active";
foreach($pages as $key => $value){
    $class = makepage($this->request->webroot, $key, $value, $this->requestAction("/pages/get_content/" . $value), $class);
}
 ?>
                                    </div></div>