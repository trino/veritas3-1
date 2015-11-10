<?php
//$settings = $this->requestAction('clientApplication/get_settings');
    //use Cake\ORM\TableRegistry;
    //$debug = $this->request->session()->read('debug');
    //include_once('subpages/api.php');
    //$language = $this->request->session()->read('Profile.language');
    $param = $this->request->params['action'];
    include_once('subpages/api.php');
    $settings = $this->requestAction('clientApplication/get_settings');
    $language = $this->request->session()->read('Profile.language');
    $strings = CacheTranslations($language, array("clientApplication_%", "forms_%", "clients_addeditimage", "infoorder_selectclient"), $settings);//,$registry);//$registry = $this->requestAction('/settings/getRegistry');

//$language = $this->request->session()->read('Profile.language');

JSinclude($this, "assets/admin/pages/scripts/form-validate-roy.js");
//var_dump($strings);
    if (isset($disabled)) {
        $is_disabled = 'disabled="disabled"';
        $view = "view";
    } else {
        $is_disabled = "";
    }

    $settings = $this->requestAction('settings/get_settings');
    $action = ucfirst($param);
    if ($action == "Add" || $action == "Apply") {
        $action = "Create";
        if(isset($did) && $did) { $action = "Edit";}
    }

    if (isset($this->request->params['pass'][0])) {
        $ClientID = $this->request->params['pass'][0];
    }

    if (isset($this->request->params['pass'][1])) {
        $id1 = $this->request->params['pass'][1];
        $id2="?type=".$_GET['type'];
        if (isset($_GET['order_id'])) { $id2= '?order_id=' . $_GET['order_id']; }
    }

    $language = $this->request->session()->read('Profile.language');
    $strings = CacheTranslations($language, array("documents_%", "forms_%", "clients_addeditimage", "infoorder_selectclient"), $settings);//,$registry);//$registry = $this->requestAction('/settings/getRegistry');
    if($language == "Debug") { $Trans = " [Trans]";} else { $Trans = ""; }
    $title = $strings["index_" . strtolower($action) . "document"];
    printCSS($this);

    loadreasons($action, $strings, true);
?>
<div id="tab0">
<h2>Application for <?php echo $client->company_name;?></h2>
<input type="hidden" id="user_id" value=""/>
<div class="step_counters" style="float: right;
    text-transform: uppercase;
    font-size: 15px;"><strong><p style="color: #578ebe;"> Step <span class="counter">1</span> of <?php echo $subd->count()+2;?></p></strong></div>
<div class="steps" id="step0" class="active">
    <input type="hidden" name="c_id" value="<?php echo $client->id;?>" />
    <?php include('subpages/documents/driver_form.php');?>    
    <hr />
    <a href="javascript:void(0)" id="button0" class="buttons btn btn-primary">Proceed</a>
</div>
<?php 
$cid = $client->id;
$jj=0;
foreach($subd as $s)
{
    $dx = $this->requestAction('/clientApplication/getSub/'.$s->sub_id);
    //var_dump($s);
    $jj++;
    ?>
    <div class="steps" id="step<?php echo $jj;?>" style="display:none;">
        <?php include('subpages/documents/'.$this->requestAction('/clientApplication/getForm/'.$s->sub_id));?>
        <hr />
        <a href="javascript:void(0)" id="buttonprev<?php echo $jj-1;?>" class="buttonprev btn btn-primary">Previous</a> 
        <a href="javascript:void(0)" id="button<?php echo $jj;?>" class="buttons btn btn-primary">Proceed</a>
    </div>
    <?php
    //echo $s->sub_id;
}
?>
<div class="steps" id="step<?php echo ++$jj;?>" style="display: none;">
<hr />
    <p style="color: #45b6af;font-size: 24px;font-weight: 400; text-align:center;">
    Thank you. Your process is complete.
    </p>
</div>
</div>
<?php include('subpages/commonjs.php');?>
<script>
    $(function(){
        $('#more_div').css({'padding':'0'});
        $('#more_div').addClass('row');
   
        
    })

</script>