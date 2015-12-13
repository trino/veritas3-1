<?php
    $param = $this->request->params['action'];
    include_once('subpages/api.php');
    $settings = $Manager->get_settings();
    $language = $this->request->session()->read('Profile.language');
    $strings = CacheTranslations($language, array("clientapplication_%", "forms_%", "clients_addeditimage", "infoorder_selectclient", "documents_%"), $settings);
    JSinclude($this, "assets/admin/pages/scripts/form-validate-roy.js");
    includejavascript($strings, $settings );

    if (isset($disabled)) {
        $is_disabled = 'disabled="disabled"';
        $view = "view";
    } else {
        $is_disabled = "";
    }

    $action = ucfirst($param);
    if ($action == "Add" || $action == "Apply") {
        $action = "Create";
        if(isset($did) && $did) { $action = "Edit";}
    }

    if (isset($this->request->params['pass'][0])) {
        $ClientID = $this->request->params['pass'][0];
        $Client = $Manager->get_client($ClientID);
        $Image = clientimage($this->request->webroot, $settings, $Client);
    }

    if (isset($this->request->params['pass'][1])) {
        $id1 = $this->request->params['pass'][1];
        $id2="?type=".$_GET['type'];
        if (isset($_GET['order_id'])) { $id2= '?order_id=' . $_GET['order_id']; }
    }

    if($language == "Debug") { $Trans = " [Trans]";} else { $Trans = ""; }
    $title = $strings["index_" . strtolower($action) . "document"];
    printCSS($this);

    loadreasons($action, $strings, true);

    function printsteps($strings, $CurrentStep, $doc_count, $Image){
        $string = $strings["forms_steps"];
        $string = str_replace("%step%", '<span class="counters counter">' . $CurrentStep . '</span>', $string);
        $string = "<span class='tot_step'>".str_replace("%total%", $doc_count+2, $string)."</span>";
        $string = '<p style="font-size:16px;padding:5px 10px 5px 10px;color: #888;background:#f4f4f4;width:120px;text-align: center;">' . $string . '</p>';
        return $string;
    }
?>
<style>.onlyapply{margin-left:15px;}</style>
<div id="tab0">
    <h2 style="float:left;font-weight: bold;margin-top: 0;">Application for <?= $client->company_name;?></h2>

    <IMG SRC="<?=$Image;?>" STYLE="max-height: 50px;float:right;">
    <div class="clearfix"></div>
    <input type="hidden" id="user_id" value=""/>
    <div class="step_counters">
        <?= printsteps($strings, 1, $subd->count(), $Image); ?>
    </div>
    <div class="clearfix"></div>

    <div class="steps" id="step0" class="active" style="padding-top: 0;margin-top: -10px;">
        <input type="hidden" name="c_id" value="<?= $client->id;?>" />
        <?php include('subpages/documents/driver_form.php');?>
        <!--hr /-->
        <a href="javascript:void(0)" id="button0" class="buttons btn btn-primary"><?= $strings["dashboard_next"]; ?></a>
        <?php if($this->request->session()->read('debug')){
            echo '<A ONCLICK="autofill2(false);" class="floatright btn btnspc btn-primary">' . $strings["dashboard_autofill"] . '</A>';
        } ?>
    </div>
    <?php
    $cid = $client->id;
    $jj=0;
    foreach($subd as $s) {
        $dx = $this->requestAction('/clientApplication/getSub/'.$s->sub_id);
        //var_dump($s);
        $jj++;
        $includedoc = $this->requestAction('/clientApplication/getForm/'.$s->sub_id);
        ?>
        <div class="steps" id="step<?php echo $jj;?>" style="display:none;">
            <input type="hidden" class="doc_id" value=""/>
            <?php include('subpages/documents/'. $includedoc);?>
            <!--hr /-->
            <a href="javascript:void(0)" id="buttonprev<?php echo $jj-1;?>" class="buttonprev btn btn-primary"><?= $strings["dashboard_previous"]; ?></a>
            <a href="javascript:void(0)" id="button<?php echo $jj;?>" class="buttons btn btn-primary"><?= $strings["dashboard_next"]; ?></a>
            <?php if($this->request->session()->read('debug')){
                echo '<A ONCLICK="autofill2(false);" class="floatright btn btnspc btn-primary">' . $strings["dashboard_autofill"] . '</A> ';
                echo 'Included: subpages/documents/' . $includedoc;
            }
            echo '</div>';
        //echo $s->sub_id;
    }
    ?>
    <div class="steps" id="step<?php echo ++$jj;?>" style="display: none;">
    <!--hr /-->
        <p style="color: #45b6af;font-size: 24px;font-weight: 400; text-align:center;">
            <?= $strings["clientapplication_done"]; ?>
        </p>
    </div>
</div>
<?php include('subpages/commonjs.php');?>
<script>
    $(function(){
        $('#more_div').css({'padding':'0'});
        
    })
</script>