<?php
if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/profile/page.php #INC123</span>";}
include_once("subpages/api.php");
$languages = languages();

function makepage($webroot, $tabIndex, $name, $cms, $active, $languages){
    //$cms = $This->requestAction("/pages/get_content/' . $name . '");
    $Title = ucfirst(str_replace("_", " ", $name));

    echo '<!-- BEGIN FORM--><div class="tab-pane ' . $active . '" id="subtab_1_' . $tabIndex . '"><div class="portlet box"><div class="portlet-body form">';
    echo '<form action="' . $webroot . 'pages/edit/' . $name . '" method="post" class="form-horizontal form-bordered" id="' . $name . '">';
    echo '<input type="hidden" name="languages" value="' . implode(",", $languages) . '" />';

    for($I = 0; $I < count($languages); $I+=2) {

        echo '<div class="form-body"><div class="form-group last"><label class="col-md-2"></label><label class="col-md-5" align="CENTER">' . $languages[$I] . '</label>';
        if($I+1 < count($languages)){ echo '<label class="col-md-5" align="CENTER">' . $languages[$I + 1] . '</label>'; }
        echo '</div></div>';

        $Field = getFieldname("title", $languages[$I]);

        echo '<div class="form-body"><div class="form-group last"><label class="control-label col-md-2">' . $Title . ' Title</label>';
        echo '<div class="col-md-5"><input class="form-control" name="' . $Field . '" id="' . $Field . '-' . $name . '" value="' . ucfirst($cms->$Field) . '"/></div>';
        if($I+1 < count($languages)) {
            $Field = getFieldname("title", $languages[$I+1]);
            echo '<div class="col-md-5"><input class="form-control" name="' . $Field . '" id="' . $Field . '-' . $name . '" value="' . ucfirst($cms->$Field) . '"/></div>';
        }
        echo '</div></div>';

        $Field = getFieldname("desc", $languages[$I]);
        echo '<div class="form-body"><div class="form-group last"><label class="control-label col-md-2">Description</label><div class="col-md-5">';
        echo '<textarea class="ckeditor form-control" name="' . $Field . '" rows="6" id="' . $Field . $name . '">' . $cms->$Field . '</textarea></div>';
        if($I+1 < count($languages)) {
            $Field = getFieldname("desc", $languages[$I + 1]);
            echo '<div class="col-md-5"><textarea class="ckeditor form-control" name="' . $Field . '" rows="6" id="' . $Field . $name . '">' . $cms->$Field . '</textarea></div>';
        }
        echo '</div></div>';
    }

    echo '<div class="form-actions" style="margin-left: -10px;margin-right: -10px;"><div class="row" align="right"><div class="col-md-offset-2 col-md-10">';
    echo '<button type="submit" class="btn btn-primary">Save Changes</button><button type="button" class="btn default" style="margin-right: 8px;">Cancel</button>';
    echo '</div></div></div></form></div></div></div><!-- END FORM-->';

    return "";
}
//<button type="submit"   class="btn btn-primary" onclick="savepage(' . "'" . $name . "'" . ');" >
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
    $class = makepage($this->request->webroot, $key, $value, $this->requestAction("/pages/get_content/" . $value), $class, $languages);
}
 ?>
                                    </div></div>