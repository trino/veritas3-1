<?php

use Cake\ORM\TableRegistry;
include_once 'subpages/filelist.php';
$param = $this->request->params['action'];
include_once('subpages/api.php');

$view = 'nope';
$debugging=isset($_GET["debug"]);

if($this->request->params['action'] == 'vieworder'){$view = 'view';}
$action = ucfirst($param);
if ($action == "Vieworder") { $action = "View";}
if ($action == "Addorder") {
    $action = "Create" ;
    if ($did>0){ $action = "Edit";}
}
if (isset($this->request->params['pass'][0])) {
    $ClientID = $this->request->params['pass'][0];
}
$doc_ext = array('pdf', 'doc', 'docx', 'txt', 'csv', 'xls', 'xlsx');
$img_ext = array('jpg', 'jpeg', 'png', 'bmp', 'gif');
if($did) {
    $_GET['driver'] = $ooo->uploaded_for;
}
if(!isset($_GET["driver"]))
$_GET['driver'] = 0;
$is_disabled = '';
if (isset($disabled)){ $is_disabled = 'disabled="disabled"';}
$settings = $this->requestAction('settings/get_settings');
$language = $this->request->session()->read('Profile.language');
$strings = CacheTranslations($language, array("orders_%", "forms_%", "documents_%", "profiles_null", "clients_addeditimage", "addorder_%"), $settings);
if($language=="Debug"){$Trans = " [Trans]";} else {$Trans = "";}
$title = $strings["orders_" . strtolower($action)];
//<script src="<?php echo $this->request->webroot;  js/jquery.easyui.min.js" type="text/javascript"></script>
//<script src="<?php echo $this->request->webroot;  js/ajaxupload.js" type="text/javascript"></script>
//includejavascript($strings);
JSinclude($this,"js/jquery.easyui.min.js");
JSinclude($this,"js/ajaxupload.js");
printCSS($this);
    ?>
<style>.allattach{display:none;}</style>

<script>
    document.onmousedown  = myClickListener;
    var eventIsFiredFromElement = "";
    function myClickListener(e){
        if(e==null){
            eventIsFiredFromElement = event.srcElement.innerHTML;//IE
        } else {
            eventIsFiredFromElement = e.target.innerHTML;//firefox
        }
    }

    var readTOS = '<?= addslashes($strings["forms_pleaseconfirm"]); ?>';
    var giveSIG = '<?= addslashes($strings["forms_signplease"]); ?>';
    var fillALL = '<?= addslashes($strings["forms_fillall"]); ?>';

    <?php loadreasons($action, $strings); ?>

</script>

<input type="hidden" id="tablename" value="<?php echo $table; ?>"/>

<h3 class="page-title">
    <?= $title;?>
</h3>
<input type="hidden" id="dr" value="<?php if (isset($dr)) echo $dr; ?>"/>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo $this->request->webroot; ?>"><?= $strings["dashboard_dashboard"]; ?></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="">
                <?= $title; ?>
            </a>
        </li>
    </ul>
    <?php

    $forms=array();
    if (isset($_GET["forms"])){
        $forms = explode(",", $_GET["forms"]);
    } else {
        $forms = $this->requestAction('/orders/getProNum');
    }

    //returns: boolean, if this form should be displayed
    //parameters:
    //  $forms  -   pass in the $forms variable since globals don't seem to work
    //  $id     -   the ID/index number of the form to check
    function isallone($forms){
        if(count($forms)<7) {
            return false;
        } else if(count($forms)=='7' && urldecode($_GET['order_type'])!='Order MEE') {
            return false;
        } else {
            return true;
        }
    }
    $_this = $this;

    $DriverProvince = "AB";
    $DriverID = $_GET["driver"];
    $UserID= $this->request->session()->read('Profile.id');
    if ($DriverID>0 && is_object($p)){
        $DriverProvince = $p->driver_province;
    }

    $enableddocs= TableRegistry::get('Profilessubdocument')->find('all')->where(['profile_id'=>$UserID]);
    foreach($thedocuments as $Key => $Value){//$thedocuments
        $userinfo = FindIterator($enableddocs, "subdoc_id", $Value["ID"]);
        $thedocuments[$Key]["Display"] = 0;
        if($userinfo) { $thedocuments[$Key]["Display"] = $userinfo->display;}
    }

    echo "<SCRIPT>var DriverProvince = '" . $DriverProvince . "';</SCRIPT>";
    if($theproduct->doc_ids && $theproduct->Bypass==0){
        $forms = explode(",", $theproduct->doc_ids);
        $theproduct->BypassForms = array();
        foreach($forms as $form){
            $theproduct->BypassForms[strtolower(getForm($form)->title)] = true;
        }
    }

    if($debugging){
        echo "<BR>The Product:";
        debug($theproduct);
    }

    function getForm($ID){
        $table = TableRegistry::get('subdocuments')->find();
        return $table->select()->where(['id' => $ID])->first();
    }

    function displayform2($DriverProvince, $thedocuments, $name, $theproduct,$did=0,$_this){
        $name = strtolower($name);
        $debugmode = isset($_GET["debug"]);
        if($did) {
            //$checker = $_this->requestAction('/orders/checkPermisssionOrder/'.$did.'/'.$_GET['driver']);
            //if(!$checker)
            //return false; //code does not work properly
            if ($thedocuments[$name]["Display"] == 0){return false;}//checks order taker's profile setting
        }
        if(isset($_GET['order_type'])) {
            switch ($theproduct->Acronym){
                //case "SIN":
                //    return $name == strtolower($_GET["SpecificForm"]);
                case "MEE":
                    if($debugmode){return "Is MEE";}
                    return true;
                    break;
                case "GEM":
                    if ($name == "challenger road test"){ return false;}
                    break;
            }
        }
        if(isset($theproduct->BypassForms)){
            return isset($theproduct->BypassForms[$name]);
        }
        if(isset($_GET["debug"])){
            debug($thedocuments);
        }
        //echo "Testing: " . $name . " '" . isset($thedocuments[$name][$DriverProvince]) . "'"; debug($thedocuments);
        //echo "<BR>" . $DriverProvince . " " . $name . " <BR>"; print_r($thedocuments[$name]);
        return isset($thedocuments[$name][$DriverProvince]);
    }

    if (isset($disabled)) { ?>
        <a href="javascript:window.print();" class="floatright btn btn-primary"><?= $strings["dashboard_print"]; ?></a>

        <!--a href="" class="floatright btn btn-success">Re-Qualify</a>
        <a href="" class="floatright btn btn-info">Add to Task List</a-->
    <?php }

    echo '</div>';

    if($p->iscomplete){
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue" id="form_wizard_1">
            <div class="portlet-title">
                <?php
                $param = $this->request->params['action'];
                if(strtolower($param) == 'vieworder') {
                    echo '<input type="hidden" id="viewingorder" value="1" />';
                } else {
                    echo '<input type="hidden" id="viewingorder" value="0" />';
                }
                $tab = 'nodisplay';
                ?>
                <div class="caption">
                    <i class="fa fa-clipboard"></i><?= $strings ["addorder_orderforms"]; ?>
                </div>
            </div>
            <div class="portlet-body form">
                <!--<form action="#" class="form-horizontal" id="submit_form" method="POST"> -->
                <div class="form-wizard">
                    <div class="form-body" style="position: relative;" id="tab0">
                    <input type="hidden" id="orderid" value="" />
                    <input type="hidden" id="did" value="<?=$did?>" />
                    <input type="hidden" id="user_id" value="<?php echo $_GET['driver'];?>" />
                    <input type="hidden" id="division" value="<?php if(isset($_GET['division']))echo $_GET['division'];?>" />
                        <?php

                        if ($param != 'view') {
                            $tab = 'tab-pane';
                            $i = 1;
                            ?>

                            
                                <?php if ($DriverID>0){
                                    $i++;?>
                                    
                                    
                                    
                                    <h2>Application for <?php echo $client->company_name;?></h2>
                                    <input type="hidden" id="user_id" value=""/>
                                    <div class="steps" id="step0" class="active">
                                        <input type="hidden" name="c_id" value="<?php echo $client->id;?>" />
                                        <?php include('subpages/documents/driver_form.php');?>    
                                        <hr />
                                        <a href="javascript:void(0)" id="button0" class="buttons btn btn-primary">Proceed</a>
                                    </div>
                                    
                                    
                                    
                                <?php }?>
                                
                                <?php

                                $doc = $doc_comp->getDocument('orders');
                                $doc_ids = $this->requestAction('/clients/orders_doc/'.$cid.'/'.$_GET['order_type']);
                                if(is_iterable($doc_ids)) {
                                    //die('here');
                                    $subdoccli = $doc_ids;
                                    if($debugging) {echo "Source: orders_doc";}
                                } else {
                                    //die('there');
                                    $subdoccli = $this->requestAction('/clients/getSubCli2/' . $cid);
                                    if($debugging) {echo "Source: getSubCli2";}
                                }
                                $subdoccli2 = $subdoccli;
                                $doc2 = $doc;
                                $end = 0;
                                $k_c=0;
                                $index=0;
                                //client permissions
                                //http://localhost/veritas3-0/clients/edit/1
                                //user permissions
                                //http://localhost/veritas3-0/profiles/edit/118
                                //product settings
                                //http://localhost/veritas3-0/profiles/settings
                                $Fieldname = getFieldname("title", $language);
                                $jj=0;
                                $doc_count = 0;
                                foreach($subdoccli as $getcounter)
                                {
                                   $doc_count++; 
                                }
                                foreach ($subdoccli as $sd) {
                                    //var_dump($sd);
                                    $index+=1;
                                    $d = $this->requestAction('/clients/getFirstSub/'.$sd->sub_id);

                                    if($debugging) {
                                        //die('here');
                                        echo "<BR>Displayform: " . displayform2($DriverProvince, $thedocuments, $d->title, $theproduct,$d->id,$_this);
                                        $thedocuments[strtolower($d->title)]["IsSet"] = true;
                                        debug($d);
                                    }
                                    
                                    
                                    if (displayform2($DriverProvince,$thedocuments,$d->title, $theproduct,$d->id,$_this)){//(displayform($DriverProvince, $provinces, $forms, $d->title,$_this)){
                                        
                                        $index+=1;
                                        $act = 0;
                                        if ($d->table_name == $table) {
                                            $act = 1;
                                            $end = 1;
                                        }

                                        $prosubdoc = $this->requestAction('/settings/all_settings/0/0/profile/' . $this->Session->read('Profile.id') . '/' . $d->id);

                                        if (true){ //($prosubdoc['display'] != 0 && $d->display == 1) {

                                            $k_c++;
                                            $j = $d->id;
                                            $j = $j + 1;
                                            if($k_c==1) {
                                                $k_cou = $j;
                                            } else if($k_cou<$j) {
                                                $k_cou=$j;
                                            }
                                            ?>
                                            
                                            
                                            <?php
                                            //$d = $this->requestAction('/clients/getFirstSub/'.$sd->sub_id);
                                            $dx = $this->requestAction('/orders/getSubDetail/'.$sd->sub_id);
                                            //var_dump($s);
                                            $jj++;
                                            ?>
                                            <div class="steps" id="step<?php echo $jj;?>" style="display:none;">
                                                <?php include('subpages/documents/'.$this->requestAction('/clientApplication/getForm/'.$sd->sub_id));?>
                                                <hr />
                                                <a href="javascript:void(0)" class="buttonprev btn btn-success" id="buttonprev<?php echo $jj-1;?>">Previous</a> 
                                                <a href="javascript:void(0)" id="button<?php echo $jj;?>" class="buttons btn btn-primary">Next</a>
                                                <?php if($this->request->params['action'] == 'addorder'){?>
                                                <a href="javascript:void(0)" id="draft<?php echo $jj;?>" class="buttons btn btn-info">Save as draft</a>
                                                <?php }?>
                                            </div>
                                            
                                            
                                                
                                            
                                            <?php

                                            $i++;
                                        }}
                                }
                                $jj++;
                                ?>
                                <div class="steps" id="step<?php echo $jj;?>" style="display:none;">
                                                <?php include('subpages/documents/confirmation.php');?>
                                                <hr />
                                                <a href="javascript:void(0)" class="buttonprev btn btn-success" id="buttonprev<?php echo $jj-1;?>">Previous</a>
                                                <?php if($this->request->params['action'] == 'addorder'){?> 
                                                <a href="javascript:void(0)" id="draft<?php echo $jj;?>" class="buttons btn btn-info">Save as draft</a>
                                                <a href="javascript:void(0)" id="save<?php echo $jj;?>" class="buttons btn btn-primary">Save</a>
                                                <?php }?>
                                                    
                                            </div>
                                <?php
                                if($debugging) { debug($thedocuments);}
                                if(!isset($k_cou)){ $k_cou = 1; }
                                ?>

                               
                            
                        <?php
                        }
                        ?>
                        
                        
                    </div>
                    
                </div>
                <!--</form> -->
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php include('subpages/commonjs.php');?>
