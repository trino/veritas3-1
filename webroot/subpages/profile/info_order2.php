<STYLE>
    .profile_client{
        margin-top: 8px !important;
    }
</STYLE>

<?php
    $debug=$this->request->session()->read('debug');
    if($debug) {//echo "<span style ='color:red;'>subpages/profile/info_order2.php #INC???</span>";
    }
    include_once('subpages/api.php');
    $settings = $Manager->get_settings();
    $language = $this->request->session()->read('Profile.language');
    $strings = CacheTranslations($language, array("infoorder_%", "forms_saving"),$settings);
    $GLOBALS["language"] = $language;
    if($debug && $language == "Debug"){ $Trans = " [Translated]"; } else {$Trans = "";}

    $intable = true;
    $cols = 10;
    $_this = $this;
    function getcheckboxes($name, $amount) {
        $tempstr = "";
        for ($temp = 0; $temp < $amount; $temp += 1) {
            if (strlen($tempstr) > 0) {
                $tempstr .= "+','";
            }
            $tempstr .= "+Number($('#" . $name . $temp . "').val())";
        }
        return $tempstr;
    }

    function alert($Text){
        echo "<SCRIPT>alert('$Text');</SCRIPT>";
    }

    $productcount=iterator_count($products);
    $tempstr = getcheckboxes("form", $productcount);

    $driver = 0;
    if (isset($_GET['driver'])) { $driver = $_GET['driver'];}

    $client = 0;
    if (isset($_GET['client'])) {$client = $_GET['client'];}

    $dr_cl = $doc_comp->getDriverClient($driver, $client);

    $drcl_c = $dr_cl['client'];
    $counting = iterator_count ($drcl_c);
    $clientID=0;
    if($counting == 1) {
        $GLOBALS['profiles'] = $this->requestAction('/profiles/getProfile/' . $clientID);
        foreach($dr_cl['client'] as $dr) {
            if($clientID==0){$clientID=$dr->id;}
        }
    }

    function GET($name, $default = ""){
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
        return $default;
    }

    $ordertype = substr(strtoupper(GET("ordertype")), 0, 3);

    //'<?php echo $this->request->webroot; //profiles/getAjaxProfile/' + clientID() + '/1',
    //$GLOBALS['contact']= $this->requestAction('/profiles/getContact');
    $GLOBALS['pType'] = $this->requestAction('/profiles/getProfileTypes');// ['','Admin','Recruiter','External','Safety','Driver','Contact'];
    $GLOBALS['settings'] = $Manager->get_settings();
    $GLOBALS['counting'] = $counting;
?>


<?php
    function makeBulk($strings, $Manager){
    //    $contact = $GLOBALS['contact'];
        $pType = $GLOBALS['pType'];
        $settings = $GLOBALS['settings'];
        $counting = $GLOBALS['counting'];
        if($counting==1){
            $profiles = $GLOBALS['profiles'];
        }


?>




    <div class="clearfix"></div>
    <div class="scrolldiv" style="margin-bottom: 15px; overflow-y: auto; overflow-x: hidden; width: 100%; height: auto;" ID="bulkform">
    <input type="text" id="searchProfile" onkeyup="searchProfile()" class="form-control" placeholder="<?= $strings["infoorder_searchprofiles"]; ?>"/>
    <table class="table table-striped table-bordered table-advance table-hover recruiters" style="max-width: 100%; table-layout:fixed; ">
        <thead>
        <!--tr>
            <th colspan="2"><?= $strings["infoorder_addprofile"]; ?></th>
        </tr-->
        </thead>
        <tbody id="profileTable">



        <?php

            $i = 0;
            if($counting==1) {
                $fulllist = "";
                foreach ($profiles as $profile) {
                    //echo $r->username;continue;
                    //if ($i % 2 == 0) {
                    if (isset($pType[$profile->profile_type])){
                        $profiletype = "(" . $pType[$profile->profile_type] . ")";
                    }else{
                        $profiletype = "";
                    }
                    if ($profiletype == "()") {
                        $profiletype = "(Draft)";
                    }
                    if ($fulllist) {
                        $fulllist .= "," . $profile->id;
                    } else {
                        $fulllist = $profile->id;
                    }
                    //}
                    print1profile($i, $profile, $profiletype, $Manager, $strings);

                    $i++;
                }
            }
            if($i>1){
                $fulllist="'" . $fulllist . "'";
                echo '<TR><TD><SPAN><INPUT TYPE="CHECKBOX" ID="selectall" ONCHANGE="selectall(' . $fulllist . ');"></SPAN> <SPAN><LABEL FOR="selectall">Select All</LABEL></SPAN></TD></TR>';
            }
            echo '</tbody></table></div>';
            //echo '<div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 59.6374045801527px; background: rgb(0, 0, 0);"></div>';
            //echo '<div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div><';
            return "";
            }

            function print1profile($index, $profile, $profiletype, $Manager, $strings){//$index = $i
                $Disabled = "";
                $ALERT="infoorder_complete";
                /*
                if(!$profile->is_complete){// || $Manager->requiredfields($profile, "profile2order")){
                    $Disabled = " DISABLED";
                    $ALERT="infoorder_incomplete";
                }
                */
                echo '<tr><td class="v-center"><span' . $Disabled . '><LABEL><input class="profile_client" type="checkbox" id="p_' . $index . '" name="p_' . $profile->id . '"' . $Disabled .
                ' onchange="addProfile(' . $profile->id . ');"
                value="' . $profile->id . '"/></span>
                <span>' . formatname($profile) . '</span> ';
                if($profile->profile_type){ echo $profiletype;}
                echo ' </span>&nbsp;
                <span class="msg_' . $profile->id . '"></span></label>
                </td>
                </tr>';
            }





            function makeform($ordertype, $cols, $color, $Title, $Description, $products, $Disabled, $counting, $settings, $client, $dr_cl, $driver, $_this, $Otype ="", $inforequired = false, $Blocked = "", $strings, $Manager){
                if (strlen($Otype)==0) { $Otype = $Title; }
                if (strlen($color)>0){ $color = "-" . $color;}
                $color=""; //color is disabled for now

                $offset = "";//' col-xs-offset-2';
                
                echo '<div class="col-xs-1"></div><div class="col-xs-' . $cols . $offset . '" style="margin:0 auto;">';

                echo '<div class="pricing' . $color . ' hover-effect">';
                echo '<div class="pricing' . $color . '-head pricing-head-active">';
                echo '<h3>' . $Title . '<span>' . $Description . '</span></h3>';
                echo '</div>';

                if($ordertype) {
                    printform($counting, $settings, $client, $dr_cl, $driver, true, $_this, $strings, $Manager);
                    echo '<ul class="pricing' . $color . '-content list-unstyled">';
                    productslist($ordertype, $products, "form", $Disabled, $Blocked);
                }

                $productcount=iterator_count($products);
                $tempstr = getcheckboxes("form", $productcount);

                echo '</ul><div class="pricing-footer"><p><hr/></p>';
                if($ordertype) {
                    printbutton($ordertype, $_this->request->webroot, 3, $tempstr, $_this, $Otype, $inforequired, $strings);
                }
                echo '</div></div></div>';
                return $ordertype;//$Otype;
            }

            function showproduct($ordertype, $product, $Blocked){
                $num = $product->number;//do not use the ID number or the name
                if($Blocked) {
                    if (is_array($Blocked)) {
                        return in_array($num, $Blocked);
                    }
                }
                return true;
            }

            function productslist($ordertype, $products, $ID, $Checked = false, $Blocked = ""){
                $field = getFieldname("title", $GLOBALS["language"]);
                if($GLOBALS["language"] == "Debug"){ $Trans = " [Translated]"; } else {$Trans = "";}
                if ($Checked) { $Checked = ' checked disabled';} else { $Checked = "";}
                $index=0;
                if($Blocked){$Blocked = explode(",", $Blocked);}
                echo '<DIV CLASS="PRODUCTLIST">';
                foreach ($products as $p) {
                    if(showproduct($ordertype, $p, $Blocked)) {
                        $name=$ID . $index ;
                        echo '<li id="product_' . $p->number . '"><div class="col-xs-10"><i class="fa fa-file-text-o"></i> <label for="' . $name . '">'. $p->$field . $Trans . '</label></div>';
                        echo '<div class="col-xs-2"><input type="checkbox" value="' . $p->number . '" id="' . $name . '"' . $Checked . '/></div>';
                        echo '<div class="clearfix"></div></li>';
                    }
                    $index++;
                }
                echo "</DIV>";
            }

            function printbutton($type, $webroot, $index, $tempstr = "",$_this, $o_type, $inforequired = true, $strings){
                if (strlen($type) > 0) {
                    switch ($index) {
                        case 3:
                            $index = 1;
                            break;
                        case 4:
                            $index = 5;
                            break;
                    }
                }
                switch ($index) {
                    case 1:
                        if (!$inforequired) {
                            echo '<a href="javascript:void(0);" id="qua_btn" class="btn btn-primary  btn-lg placenow">' . $strings["infoorder_continue"] . ' </a>';
                        }
                        break;
                    case 2: ?>
                        <a href="javascript:void(0);" class="btn btn-primary" onclick="$('.alacarte').show(200);$('.placenow').attr('disabled','');">A La Carte</a>
                        <?php
                        break;
                    case 5:
                        echo '<a class=" btn btn-primary btn-lg  button-next proceed" id="cart_btn" href="javascript:void(0)">';
                        echo $strings["infoorder_continue"] . ' <i class="m-icon-swapright m-icon-white"></i></a>';
                        break;
                }
            }

            function printform($counting, $settings, $client, $dr_cl, $driver, $intable = false,$_this, $strings, $Manager)
            {//pass the variables exactly as given, then specifiy if it's in a table or not
            echo '<input type="hidden" name="document_type" value="add_driver"/>';
            echo '<div class="form-group clientsel">';
            $dodiv = false;
            if ($intable) {
                echo '<div class="row" style="margin-top: 15px;">';
                $size = "large";
            } else {
                $size = "xlarge";
            }
            $size = "ignore";

            echo '<div class="col-xs-3 control-label" align="right" style="margin-top: 6px;">' . $strings["settings_client"] . '</div><div class="col-xs-6">';

            $dodiv = true;?>





        <script type="text/javascript">
            function reload(value) {
                var container = document.getElementById("selecting_driver");
                var was = container.value;
                container.value = value;  //THIS IS NOT WORKING!!!
                //this should set the select dropdown to "Create a Driver"
            }
        </script>



        <STYLE>
            body{
                overflow-x: hidden;
                overflow-y: scroll;
            }
        </STYLE>



        <?php
            if ($counting > 1) { ?>
                <select id="selecting_client" class="form-control input-<?= $size ?> select2me"
                onoldchange="reload(-1);"

                data-placeholder="Select <?php echo ucfirst($settings->client) . '" ';
                if ($client) { ?><?php } ?>>
                        <option><?= $strings["infoorder_selectclient"]; ?></option><?php
            } else { ?>

                    <select id="selecting_client" class="form-control input-<?= $size; ?> select2me"
                            data-placeholder="<?= $strings["infoorder_selectclient"]; ?>" disabled>
                        <?php
            }
            //debug( $dr_cl["query"]);
            foreach ($dr_cl['client'] as $dr) {
                $client_id = $dr->id;
                ?>
                <option value="<?php echo $dr->id; ?>"
                        <?php if ($dr->id == $client || $counting == 1){ ?>selected="selected"<?php } ?>><?php echo $dr->company_name; ?></option>
            <?php
            }
        ?>
        </select>

        <input class="selecting_client" type="hidden" value="<?php
            $printedclient="";
            if ($client) {$printedclient = $client;} else if ($counting == 1) {$printedclient = $client_id;}
            echo $printedclient . '"/></div></div>';

            if ($printedclient){
                //changelist("' . $_GET["ordertype"] . '", ' . $client_id . ');
                echo '<body onload="changelist(' . "'" . $_GET["ordertype"] . "', " .  $client_id . ');">';
            }

            if ($intable) {
                echo '</div>';
            }
        ?>

<div class="divisionsel form-group">
        <?php if ($counting == 1) $cl_count = 1; else {
            $cl_count = 0;
        } ?>
</div>




<?php if ($intable) {
    echo '<div class="row" style="margin-top: 15px;margin-bottom: 15px;">';
} ?>


<?php if(!isset($_GET['profiles']))
{
?>
<div class="form-group ">

    <?php
    echo '<div class="col-xs-3 control-label"  align="right" style="margin-top: 6px;">' . $strings["infoorder_driver"];
    if(isset($_GET["ordertype"]) && $_GET["ordertype"] == "BUL"){ echo '(s)';}
    echo '</div><div class="col-xs-6" ID="driverform">';
    if(isset($_GET["ordertype"]) && $_GET["ordertype"] == "BUL"){
        echo '<INPUT TYPE="HIDDEN" NAME="selecting_driver" id="selecting_driver" class="form-control input-' . $size . '" VALUE="">';
        echo '<textarea NAME="drivers" id="drivers" class="form-control input-' . $size . '" VALUE="" READONLY STYLE="resize:vertical;"></textarea>';
        if($_GET['ordertype']=="BUL"){makeBulk($strings, $Manager);}
        echo '</DIV></DIV>';
    } else {
        ?>

    <select class="form-control input-<?= $size ?> select2me"
            <?php if (!isset($_GET['ordertype']) || (isset($_GET['ordertype']) && $_GET['ordertype'] != "QUA")) { ?>data-placeholder="<?= $strings["infoorder_createdriver"]; ?>"<?php } ?>
            id="selecting_driver" <?php if ($driver) { echo 'disabled="disabled"'; } ?>>
        <?php

        echo '<option ';
        if ($driver == '0') { echo 'selected'; }
        echo '>' . $strings["infoorder_selectdriver"] . '</option>';

        $counting = 0;
        $drcl_d = $dr_cl['driver'];
        foreach ($drcl_d as $drcld) {
            $counting++;
        }

        foreach ($dr_cl['driver'] as $dr) {
            //don't forget about orders/getDriverByClient and profiles/get_ajax_profile.ctp and print1profile
            $ALERT= "infoorder_complete";
            $driver_id = $dr->id;
            echo '<option value="' . $dr->id . '"';
            /*
            if(!$dr->is_complete){// || $Manager->requiredfields($dr, "profile2order")){
                echo " DISABLED";
                $ALERT= "infoorder_incomplete";
            }
            */
            if ($dr->id == $driver || $counting == 1 && $driver != '0'){ echo 'selected="selected"'; }
            echo '>' . $strings[$ALERT] . " " . formatname($dr) .  '</option>';
        }
        ?>
    </select>

    <input class="selecting_driver" type="hidden" value="<?php if ($driver) {
            echo $driver;
        } ?>"/>
    </div>
    <?php

        if ($settings->profile_create == '1') echo "<div class='col-xs-3 ' style='margin-left: -20px;'>or&nbsp;&nbsp;<a href='" . $_this->request->webroot . "profiles/add' class='btn grey-steel '>Add Driver</a></div>"; ?>

    </div>
    <?php
    }
    if ($intable) {
        echo "</div>";
    }
}
    } ?>



<div class="row">
    <?php
        if (!is_object($product)){//error handlin for a bad redirect
            makeform("", $cols, '', "ERROR", "MISSING ORDER TYPE", $products, True, $counting, $settings, $client, $dr_cl, $driver, $_this, "", false, "", $strings, $Manager);
        } else {
            $name_field = getFieldname("Name", $language);
            $desc_field = getFieldname("Description", $language);

            $o_type = makeform($product->Acronym, $cols, '', $product->$name_field . $Trans, $product->$desc_field . $Trans, $products, $product->Checked == 1, $counting, $settings, $client, $dr_cl, $driver, $_this, $product->Alias, false, $product->Blocked, $strings, $Manager);
        }
    ?>
</div>




















<script>
    AtLeastOneProduct = '<?= addslashes($strings["infoorder_atleastone"]); ?>';

    function changelist(Ordertype, ClientID){
        //PRODUCTLIST
        $.ajax({
            url: "<?php echo $this->request->webroot;?>clients/quickcontact",
            type: "post",
            dataType: "HTML",
            data: "Type=generateHTML&ClientID=" + ClientID + "&Ordertype=" + Ordertype + "&Language=<?= $language; ?>",
            success: function (msg) {
                $('.PRODUCTLIST').html(msg);
            }
        })
    }

    function getcheckboxes(){
        var tempstr = '';
        $('.PRODUCTLIST input[type="checkbox"]').each(function () {
            if ($(this).is(':checked')){
                if (tempstr.length==0) { tempstr = $(this).val();} else {tempstr = tempstr + "," + $(this).val();}
            }
        });
        return tempstr;
    }

    function getdrivers(){
        return document.getElementById("selecting_driver").value;
    }

    function check_driver_abstract(driver) {

    }
    function check_cvor(driver) {

    }
    function check_div() {
        //alert('test');
        var checkerbox = 0;
        $('input[type="checkbox"]').each(function () {
            if ($(this).is(':checked'))
                checkerbox = 1;
        });
        if (checkerbox == 0) {
            alert(AtLeastOneProduct);
            return false;
        }
        var checker = 0;
        $('.divisionsel select').each(function () {
            checker++;
        });
        if (checker > 0) {
            if (!$('.divisionsel select').val()) {
                $('.divisionsel select').attr('style', 'border:1px solid red;');
                return false;
            }
        }
        return true;
    }
    $(function () {
        <?php if($driver) { ?>
        check_driver_abstract(<?php echo $driver;?>);
        check_cvor(<?php echo $driver;?>);
        <?php } ?>

        $('#qua_btn').click(function () {
            if (!check_div()){return false;}

            var div = $('#divisionsel').val();
            if (!isNaN(parseFloat(div)) && isFinite(div)) {
                var division = div;
            } else {
                var division = '0';
            }
            if ($('.selecting_client').val()) {
                <?php if(!isset($_GET['profiles'])){?>
                if(!getcheckboxes()){
                    alert(AtLeastOneProduct);
                    return false;
                }
                Driver = $('.selecting_driver').val();
                if(typeof Driver === "undefined"){
                    Driver = Drivers();
                    if (Driver.length == 0){
                        alert('<?= addslashes($strings["infoorder_alertselectdriver"]); ?>');
                        return;
                    } else {
                        $('.overlay-wrapper').show();
                        $('#qua_btn').html('<?= $strings["forms_saving"]; ?>');
                        $('#qua_btn').attr('disabled','disabled');
                    }
                    //window.location = '<?php echo $this->request->webroot; ?>orders/orderslist?flash=Bulk Order bypass';
                    $.ajax({
                        data:'forms='+getcheckboxes()+'&drivers='+getdrivers()+'&client='+$('#selecting_client').val()+'&division='+division,
                        url:'<?php echo $this->request->webroot;?>orders/webservice/BUL',
                        type:'post',
                        success:function(res) {
                         //   alert(res);
                            /*
                            var response = JSON.parse(res);
                            var driv = response['driver'];//.split(',');
                            //alert(response['order_id']);
                            var ord = response['order_id'];//.split(',');
                            var check = 0;

                            $.ajax({
                                    url:'<?php echo $this->request->webroot;?>orders/webservice/BUL/'+response['forms']+'/'+driv+'/'+ord
                                });

                            /*for(var k=0;k<driv.length;k++) {
                                //check = k;
                                $.ajax({
                                    url:'<?php echo $this->request->webroot;?>orders/webservice/BUL/'+response['forms']+'/'+driv[k]+'/'+ord[k]
                                });
                            }*/


                            //setTimeout(function(){
                              window.location = '<?php echo $this->request->webroot;?>';
                            //},10000);


                        }
                    });
                    return;
                }
                if ($('.selecting_driver').val() == '') {
                    alert('<?= addslashes($strings["infoorder_alertselectdriver"]);?>');
                    $('#s2id_selecting_driver .select2-choice').attr('style', 'border:1px solid red;');
                    $('html,body').animate({scrollTop: $('#s2id_selecting_driver .select2-choice').offset().top}, 'slow');
                    return false;
                } else {
                    var tempstr = getcheckboxes();
                    window.location = '<?php echo $this->request->webroot; ?>orders/addorder/' + $('.selecting_client').val() + '/?driver=' + Driver + '&division=' + division + '&order_type=<?php echo urlencode($o_type);?>&forms=' + tempstr;
                }
                <?php } else {?>
                var tempstr = getcheckboxes();
                window.location = '<?php echo $this->request->webroot; ?>orders/addorder/' + $('.selecting_client').val() + '/?driver=<?php echo $_GET['profiles'];?>&division=' + division + '&order_type=<?php echo urlencode($o_type);?>&forms=' + tempstr;
                <?php } ?>
            } else {
                alert("<?= $strings["infoorder_selectclient"]; ?>");
                $('#s2id_selecting_client .select2-choice').attr('style', 'border:1px solid red;');
                $('html,body').animate({scrollTop: $('#s2id_selecting_client .select2-choice').offset().top}, 'slow');
            }


        });

        $('#divisionsel').live('change', function () {
            $(this).removeAttr('style');
        });
        if ($('.selecting_client').val()) {
            var client = $('#selecting_client').val();
            if (!isNaN(parseFloat(client)) && isFinite(client)) {
                $('.selecting_client').val(client);
                //alert(client);
                $.ajax({
                    url: '<?php echo $this->request->webroot;?>clients/divisionDropDown/' + client,
                    data: {istable: '<?= $intable; ?>'},
                    success: function (response) {
                        $('.divisionsel').html(response);
                    }
                });
            }
        }
        $('#selecting_driver').change(function () {
            $('#s2id_selecting_driver .select2-chosen-2').removeAttr('style');
            var driver = $('#selecting_driver').val();
            //alert(driver);
            if (!isNaN(parseFloat(driver)) && isFinite(driver)) {
                $('.selecting_driver').val(driver);
                check_driver_abstract(driver);
                check_cvor(driver);
            }
            else {
                $('.selecting_driver').val('');
                return false;
            }
        });

        $('#selecting_client').change(function () {
            clearall();
            $('#profileTable').html("");
            $('s2id_selecting_client.select2-choice').removeAttr('style');
            <?php
                echo 'var ordertype = "' . $_GET['ordertype']. '";';
                if(!$_GET['driver']){
                    if(!isset($_GET['ordertype']) || (isset($_GET['ordertype']) && $_GET['ordertype']!='QUA')){
                        ?>
            $('#s2id_selecting_driver .select2-chosen').html('<?=$strings["infoorder_selectdriver"];?>');
            <?php }else { ?>
            $('#s2id_selecting_driver .select2-chosen').html('<?=$strings["infoorder_selectdriver"];?>');
            <?php
            }}
            ?>
            var client = $('#selecting_client').val();
            if (!isNaN(parseFloat(client)) && isFinite(client)) {
                $('.selecting_client').val(client);
                changelist(ordertype, client);
                <?php
                if(!$_GET['driver'])
                {
                ?>
                $.ajax({
                    url: '<?php echo $this->request->webroot;?>clients/divisionDropDown/' + client,
                    data: {istable: '<?= $intable; ?>'},
                    success: function (response) {
                        $('.divisionsel').html(response);
                    }
                });
                <?php }?>
            }
            else {
                $('.selecting_client').val('');
                return false;
            }

            <?php

        if(!$driver){
            ?>
            $.ajax({
                url: '<?php echo $this->request->webroot;?>orders/getDriverByClient/' + client + '?ordertype=<?php if(isset($_GET['ordertype']))echo $_GET['ordertype']?>',
                success: function (res) {
                    var div = $('#divisionsel').val();
                    if (!isNaN(parseFloat(div)) && isFinite(div)) {
                        var division = div;
                    } else {
                        var division = '0';
                    }
                    $('#selecting_driver').html(res);
                    $('.selecting_client').val($('#selecting_client').val());
                }
            });
            <?php
       }
       ?>
            $('#s2id_selecting_driver .select2-chosen-2').removeAttr('style');
        });
    });
</script>





<SCRIPT>
    var UpdatesEnabled = true;

    function moveelement(SRCelement, DESTelement){
        SRCelement = document.getElementById(SRCelement);
        document.getElementById(DESTelement).appendChild(SRCelement);
    }

    function clientID(){
        var client = document.getElementById("selecting_client").value;
        if(isNaN(client)) {return 0;} else {return client;}
    }

    function Drivers(){
        return document.getElementById("selecting_driver").value;
    }

    function searchProfile() {
        if (clientID() >0) {
            var key = $('#searchProfile').val();
            $('#profileTable').html('<tbody><tr><td><img src="<?php echo $this->request->webroot;?>assets/admin/layout/img/ajax-loading.gif"/></td></tr></tbody>');
            $.ajax({
                url: '<?php echo $this->request->webroot;?>profiles/getAjaxProfile/' + clientID() + '/1',
                data: 'key=' + key + '&selected=' +  document.getElementById("selecting_driver").value,
                type: 'get',
                success: function (res) {
                    $('#profileTable').html(res);
                }
            });
        } else {
            document.getElementById("drivers").value = "[No client selected]";
        }
    }

    function addProfile(ID){
        addID("selecting_driver", ID, true);
        updateNames();
    }

    function Check(ElementName, State, isID){
        if (isID){
            ElementName = "#" + ElementName;
        } else {
            ElementName= 'input[name=' + ElementName + ']';
        }
        if(State) {
            $(ElementName).parent().addClass('checked');
            $(ElementName).attr('checked', 'checked');
        } else {
            $(ElementName).parent().removeClass('checked')
            $(ElementName).removeAttr('checked');
        }
        //$(ElementName).click();
    }

    function selectall(IDs){
        var element = document.getElementById("selectall");
        var temp = 0;
        var ID = 0;
        IDs = IDs.split(",");
        UpdatesEnabled=false;//only needs to update the last one
        for(temp=0; temp<IDs.length; temp++){
            ID = IDs[temp];
            Check("p_" + ID, element.checked, false);
            if(element.checked) {
                addID("selecting_driver", ID, false);
            } else {
                removeID("selecting_driver", ID);
            }
        }
        UpdatesEnabled=true;
        if(!element.checked){
            clearall();
        } else {
            updateNames();
        }
    }

    function clearall(){
        if('<?= $_GET["ordertype"] ?>' == 'BUL'){
            document.getElementById("selecting_driver").value = "";
            document.getElementById("drivers").value = "[No drivers selected]";
            document.getElementById("searchProfile").value = "";
        }
    }

    function replaceAll(find, replace, str) {
        return str.replace(new RegExp(find, 'g'), replace);
    }

    function updateNames(){
        if (UpdatesEnabled) {
            if (document.getElementById("selecting_driver").value) {
                $.ajax({
                    url: '<?php echo $this->request->webroot;?>profiles/getProfileNames/' + document.getElementById("selecting_driver").value,
                    data: '',
                    type: 'get',
                    success: function (res) {
                        res=replaceAll(", ", "\n", res);
                        document.getElementById("drivers").value = res;
                    }
                });
            } else {
                clearall();
            }
        }
    }

    function addID(ElementName, ID, RemoveIfFound){
        var element = document.getElementById(ElementName);
        if(element.value){
            var values = element.value.split(",");
            var temp=0;
            for (temp = 0; temp< values.length; temp++){
                if(values[temp]== ID) {
                    if(RemoveIfFound) {removeID(ElementName, ID);}
                    return false;
                }
            }
            element.value = element.value + "," + ID;
        } else {
            element.value = ID;
        }
        return true;
    }

    function removeID(ElementName, ID){
        element = document.getElementById(ElementName);
        var newvalue = "";
        var temp=0;
        if(element.value.indexOf(",")==-1) {
            if (element.value != ID){
                newvalue = element.value;
            }
        } else {
            var values = element.value.split(",");
            for (temp = 0; temp < values.length; temp++) {
                if (values[temp] != ID) {
                    if (newvalue) {
                        newvalue = newvalue + "," + values[temp];
                    } else {
                        newvalue = values[temp];
                    }
                }
            }
        }
        element.value=newvalue;
    }

    clearall();
</SCRIPT>