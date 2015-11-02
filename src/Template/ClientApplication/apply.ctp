<?php
//$settings = $this->requestAction('clientApplication/get_settings');
    //use Cake\ORM\TableRegistry;
    //$debug = $this->request->session()->read('debug');
    //include_once('subpages/api.php');
    //$language = $this->request->session()->read('Profile.language');
    
    include_once('subpages/api.php');
    $settings = $this->requestAction('clientApplication/get_settings');
    $language = $this->request->session()->read('Profile.language');
    $strings = CacheTranslations($language, array("clientApplication_%", "forms_%", "clients_addeditimage", "infoorder_selectclient"), $settings);//,$registry);//$registry = $this->requestAction('/settings/getRegistry');

//$language = $this->request->session()->read('Profile.language');

//var_dump($strings);
?>
<h2>Application for <?php echo $client->company_name;?></h2>
<input type="hidden" id="user_id" value=""/>
<div class="steps" id="step0" class="active">
    <input type="hidden" name="c_id" value="<?php echo $client->id;?>" />
    <?php include('subpages/documents/driver_form.php');?>    
    <hr />
    <a href="javascript:void(0)" id="button0" class="buttons btn btn-primary">Proceed to step 1</a>
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
         
        <a href="javascript:void(0)" id="button<?php echo $jj;?>" class="buttons btn btn-primary">Proceed to step <?php echo $jj+1;?></a>
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
<?php include('subpages/commonjs.php');?>
