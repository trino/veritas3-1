<?php
    if($this->request->session()->read('debug')) {
        echo "<span style ='color:red;'>subpages/clients/requalify.php #INC ABFFF</span><BR>";
    }
    echo $strings["clients_requalifynotice"];
?>
<form action="" method="post" class="requalify_form">
<input type="hidden" name="id" value="<?php echo $client->id; ?>" />
<table class="table table-condensed  table-striped table-bordered table-hover dataTable no-footer" id="myTable">
    <!--thead>
    <tr>
        <th>ID</th>
        <th>Product</th>

    </tr>
    </thead>
    <tbody-->
    <tr>
        <td><i class="fa fa-refresh"></i> <?= $strings["clients_enablerequalify"]; ?></td>
        <td><LABEL><input type="checkbox" <?php if(isset($client)&& $client->requalify=='1')echo "checked";?> name="requalify" value="1"> <?= $strings["dashboard_affirmative"]; ?></LABEL></td>
    </tr>

    <tr>
        <td><?= $strings["clients_requalifywhen"] ?></td>
        <td><LABEL><input type="radio" <?php if(isset($client)&& $client->requalify_re=='1')echo "checked";?> name="requalify_re" value="1" onclick="$('.r_date').hide();"/> <?= $strings["clients_anniversary"]; ?></LABEL>
            <br><?= $strings["clients_or"]; ?><br> <LABEL><input type="radio" <?php if(isset($client)&& $client->requalify_re=='0')echo "checked";?> name="requalify_re" value="0" onclick="$('.r_date').show();"><?= $strings["clients_selectadate"]; ?></LABEL>
            <input type="text" class="form-control date-picker r_date" style="width:50%;<?php echo (isset($client)&& $client->requalify_re==1)?"display:none":"display:block";?>;" name="requalify_date" value="<?php  if(isset($client)&& $client->requalify_date!="")echo $client->requalify_date; else echo date('Y-m-d');?>">
        </td>
    </tr>

    <tr>
        <td><?= $strings["forms_requalifyfrequency"];?></td>
        <td>
            <LABEL><input type="radio" <?php if(isset($client)&& $client->requalify_frequency=='1')echo "checked";?> value="1" name="requalify_frequency"> <?= $strings["forms_1month"];?></LABEL>
            <LABEL>&nbsp;&nbsp;<input type="radio" <?php if(isset($client)&& $client->requalify_frequency=='3')echo "checked";?> value="3" name="requalify_frequency"> <?= $strings["forms_3month"];?></LABEL>
            <LABEL>&nbsp;&nbsp;<input type="radio" <?php if(isset($client)&& $client->requalify_frequency=='6')echo "checked";?> value="6" name="requalify_frequency"> <?= $strings["forms_6month"];?></LABEL>
            <LABEL>&nbsp;&nbsp;<input type="radio" <?php if(isset($client)&& $client->requalify_frequency=='12')echo "checked";?> value="12" name="requalify_frequency"> <?= $strings["forms_12month"];?></LABEL>
        </td>
    </tr>

    <tr>
        <td><?= $strings["forms_includedproducts"];?></td>
        <td><?php
            function productname($products, $number, $language){
                $product = getIterator($products, "number", $number);
                $title = getFieldname("title", $language);
                $title = $product->$title;
                if ($language == "Debug"){ $title.= " [Trans]";}
                return $title . " #" . $number;
            }
            function printproducts($r, $products, $numbers, $language){
                $hasprinted=false;
                foreach($numbers as $number){
                    if($hasprinted) { echo "&nbsp;&nbsp;"; }
                    echo '<input type="checkbox" id="p' . $number . '"';
                    if(in_array($number,$r)) {echo " checked";}
                    echo ' name="requalify_product[]" value="' . $number . '">';
                    echo '<label for="p' . $number . '">' . productname($products, $number, $language) . "</label>";
                    $hasprinted=true;
                }
            }

            $r = explode(',',$client->requalify_product);
            printproducts($r, $products, array(1, 14, 72), $language);

        echo '</td></tr>';

        if($Manager->read("admin")){
            echo '<TR><TD>Run CRON when you click Save Changes <i class="m-icon-swapright m-icon-black"></TD><TD><LABEL><INPUT NAME="runcron" VALUE="TRUE" TYPE="checkbox">Yes</LABEL></TD></TR>';
        }
    ?>

</table>
 <div class="form-actions">
    <button type="button" class="btn btn-primary requalify_submit" >
        <?= $strings["forms_savechanges"];?> <i class="m-icon-swapright m-icon-white"></i>
    </button>
 </div>
 <div class="margin-top-10 alert alert-success display-hide requalify_flash"  style="display: none;">
    <button class="close" data-close="alert"></button>
     <?= $strings["forms_datasaved"];?>
</div>
<div class="clearfix"></div>
</form>
<!--div class="col-md-12">
    <table  class="table table-condensed  table-striped table-bordered table-hover dataTable no-footer">
        <tr>
            <td><?= $strings["forms_driverusername"];?></td>
            <td><?= $strings["forms_hireddate"];?></td>
            <td><?= $strings["clients_enablerequalify"];?></td>
            <td><?= $strings["forms_cronorders"];?></td>
        </tr>
        <?php
            /*
            $profiles = $this->requestAction('/rapid/getcronProfiles/'.$client->profile_id);
            foreach($profiles as $p) {//this line is erroring out
        ?>
            <tr>
                <td><?= $p->username;?></td>
                <td><?= $p->hired_date;?></td>
                <td><?= ($p->requalify=='1')? $strings["dashboard_affirmative"]: $strings["dashboard_negative"];?></td>
                <td><?php $crons = $this->requestAction('/rapid/cron_client/'.$p->id."/".$client->id);
                           $show ='';
                           $cron = explode(",",$crons);
                           foreach($cron as $cr){
                                $pr = explode('&',$cr);
                                $show .= $pr[0]." <a href='".$this->request->webroot."profiles/view/".$p->id."?getprofilescore=1' target='_blank'>" . $strings["dashboard_view"] . "</a>,";
                           }
                           echo $show = substr($show,0,strlen($show)-1);
                ?></td>
            </tr>
            <?php
            }
            */
        ?>
    </table>
</div>
<div class="clearfix"></div-->
<script>
$(function(){
    $('.requalify_submit').click(function(){
       $('.overlay-wrapper').show();
        var datas = $('.requalify_form').serialize();
        $.ajax({
            type:"post",
            data: datas,
            url:"<?php echo $this->request->webroot;?>clients/requalify/<?php echo $client->id;?>",
            success: function()
            {
                $('.requalify_flash').show();
                $('.overlay-wrapper').hide();
            }
        });

    });
})

</script>