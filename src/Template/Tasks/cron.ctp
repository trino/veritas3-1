<?php
    $CRLF = "\r\n";
    $Clients = $Manager->enum_all("clients");//, array("requalify" => 1));
    $ProfileTypes = $Manager->enum_all("profile_types");
    $products = $Manager->enum_all("order_products");
    $settings = $Manager->get_settings();
    $sidebar =$this->requestAction("settings/all_settings/".$this->Session->read('Profile.id')."/sidebar");
    include_once('subpages/api.php');
    $language = $this->request->session()->read('Profile.language');
    $strings = CacheTranslations($language, array($this->request->params['controller'] . "_%", "month_long%"),$settings);
    $Showname=isset($_GET["clientid"]);

    $Frequencies = array();
    foreach(array(1,3,6,12) as $Frequency){
        $Frequencies[$Frequency] = getpreviousdate($Frequency);
    }

    function profiletypes($ProfileTypes, $Name, $Type){
        echo '<SELECT NAME="' . $Name . '">';
        foreach($ProfileTypes as $ProfileType){
            echo '<OPTION VALUE="' . $ProfileType->id . '"';
            if ($Type==$ProfileType->id){ echo ' SELECTED';}
            echo '>' . checkmark($ProfileType->placesorders) . " " . $ProfileType->title . '</OPTION>';
        }
        echo '</SELECT>';
    }

    function checkmark($Status){
        if($Status){ return '&#9745;'; }
        return '&#9744;';
    }

    function getpreviousdate($frequency){
        $today = date('Y-m-d');//                              24 hours * 60 minutes * 60 seconds * 30 days
        return date('Y-m-d', strtotime($today)-($frequency*24*60*60*30));
    }

    function pluralize($Quantity, $Word, $Append = "s"){
        if($Quantity == 1){ return $Word;}
        return $Word . $Append;
    }

    function productname($products, $number, $language){
        if(strpos($number, ",") !== false){$number = explode(",", $number);}
        if(is_array($number)){
            foreach($number as $Key => $Num){
                $number[$Key] = productname($products, $Num, $language);
            }
            return implode(", ", $number);
        } else {
            $product = getIterator($products, "number", $number);
            $title = getFieldname("title", $language);
            $title = $product->$title;
            if ($language == "Debug") {$title .= " [Trans]";}
            return $title . " #" . $number;
        }
    }
    function printproducts($Client, $r, $products, $numbers, $language){
        $hasprinted=false;
        if(!is_array($r)){$r = explode(',',$r);}
        foreach($numbers as $number){
            if($hasprinted) { echo "&nbsp;&nbsp;"; }
            echo '<label><input type="checkbox" id="p' . $number . '"';
            if(in_array($number,$r)) {echo " checked";}
            echo ' name="requalify_product[' . $Client . '][' . $number. ']" value="1">';
            echo productname($products, $number, $language) . "</label>";
            $hasprinted=true;
        }
    }

    if($_POST){
        foreach($_POST["requalify_product"] as $Key => $Product){
            $_POST["requalify_product"][$Key] = implode(",", array_keys($Product));
        }
        foreach($_POST as $Key => $TheClients){
            foreach($TheClients as $ID => $Value){
                if (is_array($Value)){
                    foreach($Value as $Key2 => $Value2){
                        //echo "SET " . $Key . '.' . $ID . " to " . $Value2 . " WHERE id = " . $Key2 . '<BR>';
                        $Manager->update_database($Key, "id", $Key2, array($ID => $Value2));
                    }
                } else {//assume clients
                    //echo "SET " . $Key . " to " . $Value . " WHERE id = " . $ID . '<BR>';
                    $Manager->update_database("clients", "id", $ID, array($Key => $Value));
                }
            }
        }
        //debug($_POST);
    }
?>
<h3 class="page-title">
    CRON
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?= $this->request->webroot . '">' . $strings["dashboard_dashboard"] ?></a>
						<i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="">CRON</a>
        </li>
    </ul>
    <a href="javascript:window.print();" class="floatright btn btn-info"><?= $strings["dashboard_print"]; ?></a>
    <a class="floatright btn btn-warning btnspc" href="<?= $this->request->webroot; ?>profiles/cron/true">Run the CRON </a>
    <?php if($Showname){
        echo '<a class="floatright btn btn-danger btnspc" href="' . $this->request->webroot . 'tasks/cron">Go Back</a>';
    } ?>
</div>

<FORM METHOD="post">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box grey-salsa">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-globe"></i>
                        Clients
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-actions top chat-form" style="margin-bottom:0;" align="right">
                        <div class="btn-set pull-left">

                        </div>
                        <div class="btn-set pull-right">
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="form-body">
                        <div class="table-scrollable">
                            <table class="table table-condensed table-striped table-bordered table-hover dataTable no-footer">
                                <thead>
                                    <tr class="sorting">
                                        <th><?= $this->Paginator->sort('id', "ID"); ?></th>
                                        <th><?= $this->Paginator->sort('company_name', "Name"); ?></th>
                                        <th title="Is requalify enabled" style="width: 37px;"><?= $this->Paginator->sort('requalify', "On"); ?></th>
                                        <th><?= $this->Paginator->sort('requalify_frequency', "Frequency"); ?></th>
                                        <th>From when</th>
                                        <th>Products</th>
                                        <TH title="Number of profiles with requalify enabled / total number of profiles in this client">Profiles</TH>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($Clients as $Client){
                                            $Users = $Manager->enum_all("profiles", array('id IN('.$Client->profile_id.')', "requalify" => 1));
                                            $Profiles[$Client->id] = $Users;
                                            echo '<TR>' . $CRLF;
                                            echo '<TD>' . $Client->id . '</TD>' . $CRLF;
                                            echo '<TD><A HREF="' . $this->request->webroot . 'profiles/index?&filter_by_client=' . $Client->id . '" TARGET="_blank">' . $Client->company_name . '</A></TD>' . $CRLF;
                                            echo '<TD><INPUT TYPE="checkbox" name="requalify[' . $Client->id . ']" value="1" ONCHANGE="change();"';
                                            if($Client->requalify){echo ' CHECKED';}
                                            echo ' STYLE="width: 100%;"></TD>';
                                            echo '<TD><SELECT ID="freq' . $Client->id . '" NAME="requalify_frequency[' . $Client->id . ']" STYLE="width: 100%;">';
                                            foreach($Frequencies as $Frequency => $Date){
                                                echo '<OPTION VALUE="' . $Frequency . '"';
                                                if ($Frequency==$Client->requalify_frequency){ echo ' SELECTED';}
                                                echo '>' . $Frequency .  pluralize($Frequency, ' Month') . '</OPTION>';
                                            }
                                            echo '</SELECT></TD><TD><LABEL><INPUT TYPE="CHECKBOX" value="1" id="check_when' . $Client->id . '" ONCLICK="when(' . $Client->id . ');" NAME="requalify_re[' . $Client->id . ']"';
                                                echo ' TITLE="Click to toggle between the anniversary of their hired date or specify a date yourself"';
                                                if($Client->requalify_re){ echo " CHECKED";}
                                                echo '>&nbsp;<SPAN ID="span_when' . $Client->id . '"';
                                                if(!$Client->requalify_re){ echo ' STYLE="display: none;"';}
                                                if(!$Client->requalify_date){$Client->requalify_date = date("Y-m-d");}
                                                echo '>Anniversary</SPAN></LABEL><INPUT TYPE="TEXT" NAME="requalify_date[' . $Client->id . ']" ID="text_when' . $Client->id;
                                                echo '" class="datepicker date-picker" value="' .  $Client->requalify_date . '" ONCHANGE="change();" STYLE="width: 90%;';
                                                if($Client->requalify_re){ echo ' display: none;';}
                                            echo '"></TD><TD>';
                                            printproducts($Client->id, $Client->requalify_product, $products, array(1, 14, 72), $language);
                                            echo '</TD><TD align="RIGHT">';
                                            $Count = iterator_count($Users);
                                            if($Count) {
                                                echo '<A HREF="?clientid=' . $Client->id . '">' . $Count . '/' . count(explode(",", $Client->profile_id)) . '</A>';
                                            } else {
                                                echo "0/" . count(explode(",", $Client->profile_id));
                                            }
                                            echo '</TD></TR>';
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-actions" style="height:75px;">
                        <div class="row">
                            <div class="col-md-12" align="right">
                                <button type="submit" class="btn btn-primary" onclick="Changed = false;">
                                    Save Changes <i class="m-icon-swapright m-icon-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($Showname){
        echo '<div class="row">
        <div class="col-md-12">
            <div class="portlet box green-haze">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-user"></i>
        Profiles for ' . getIterator($Clients, "id", $_GET["clientid"])->company_name . ' with requalification enabled
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-actions top chat-form" style="margin-bottom:0;" align="right">
                        <div class="btn-set pull-left">

                        </div>
                        <div class="btn-set pull-right">
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="form-body">
                        <div class="table-scrollable">
                            <TD COLSPAN="7" style="padding-right: 10px;"><table class="table table-condensed table-striped table-bordered table-hover dataTable no-footer" style="margin-bottom: 5px; margin-left: 3px;"><thead><TR><TH>ID</TH><TH>Name</TH><TH>Profile Type</TH><TH title="Expiry date is not blank, and is after yesterday">Expiry Date >= ' . $Today . '</TH><TH title="Is hired">IH</TH><TH>Hired Date</TH><TH>Auto-Change</TH></TR><TBODY>';

        $Users = $Profiles[$_GET["clientid"]];
        foreach($Users as $Profile){
            //$ProfileType = getIterator($ProfileTypes, "id", $Profile->profile_type);
            echo '<TR><TD>' . $Profile->id . '</TD>' . $CRLF;
            echo '<TD><A HREF="' . $this->request->webroot . 'profiles/view/' . $Profile->id . '">' . formatname($Profile) . '</A></TD>' . $CRLF;
            echo '<TD>';
            profiletypes($ProfileTypes, "profiles[profile_type][" . $Profile->id . "]", $Profile->profile_type);
            echo '</TD>' . $CRLF;
            echo '<TD>' . checkmark($Profile->expiry_date && $Profile->expiry_date >= $Today);

            echo ' <INPUT ONCHANGE="change();" TYPE="TEXT" NAME="profiles[expiry_date][' . $Profile->id . ']" VALUE="';
            echo $Profile->expiry_date . '" class="datepicker date-picker">';

            echo '</TD>' . $CRLF;
            echo '<TD><INPUT ONCHANGE="change();" TYPE="CHECKBOX" NAME="profiles[is_hired][' . $Profile->id . ']" VALUE="1"  STYLE="width: 100%;"';
            if($Profile->is_hired){ echo " CHECKED";}
            echo '></TD>' . $CRLF;

            echo '<TD><INPUT ONCHANGE="change();" TYPE="TEXT" ID="hireddate' . $Profile->id . '" NAME="profiles[hired_date][' . $Profile->id . ']" VALUE="';
            echo $Profile->hired_date . '" class="datepicker date-picker"></TD><TD>';
            foreach($Frequencies as $Frequency => $Date){
                echo '<INPUT TYPE="BUTTON" CLASS="btn-xs btn btn-info btnspc" VALUE="-' . $Frequency . pluralize($Frequency, " Month") . '" ';
                echo 'ONCLICK="changehired(' . $Profile->id . ', ' . $Frequency . ", '" . $Date . "'" . ');">';
            }
            echo '</TD></TR>';
        }
        echo '</TBODY></TABLE></div></div></div></div>';
    } ?>
</FORM>
<SCRIPT>
    var Changed = false;

    window.onbeforeunload = function (e) {
        if (Changed) {
            var message = "You have not saved your changes yet", e = e || window.event;
            if (e) {e.returnValue = message;}// For IE and Firefox
            return message;// For Safari
        }
    };


    function change(){
        Changed = true;
    }

    function changehired(ProfileID, Months, Date){
        var element = document.getElementById("hireddate" + ProfileID);
        element.value = Date;
        Changed = true;
    }

    function when(ClientID){
        var checked = getinputvalue("check_when" + ClientID);
        visible("span_when" + ClientID, checked);
        visible("text_when" + ClientID, !checked);
        Changed = true;
    }

    function visible(ID, Status){
        var element = document.getElementById(ID);
        if(Status){
            element.style.display = '';
        } else {
            element.style.display = 'none';
        }
    }
</SCRIPT>
<?php
    include('subpages/profile/requalify.php');

    $Year = date("Y", strtotime($Today));
    $Month = date("m", strtotime($Today));
    $Duration = explode(" ", $Duration);
    $Duration[0] = str_replace("+", "", $Duration[0]);
    $Months = $Duration[0];
    switch($Duration[1]){
        case "years":
            $Months = $Months * 12;
            break;
    }
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box green-meadow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-calendar"></i>
                    The next <?= $Months; ?> months <?php
                        if($Showname){
                            echo " for " . getIterator($Clients, "id", $_GET["clientid"])->company_name;
                        }
                    ?>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-actions top chat-form" style="margin-bottom:0;" align="right">
                    <div class="btn-set pull-left">

                    </div>
                    <div class="btn-set pull-right">
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="form-body">
                    <div class="table-scrollable" align="center">
                        <TABLE width="100%"><TR><TD style="width: 1200px;">
                        <TABLE border="1"><TR>
                        <?php
                            $EventList = array();
                            for($Temp = 0; $Temp < $Months; $Temp++){
                                echo '<TD valign="top">';
                                makemonthtable($strings, $Year, $Month, $new_req, $Clients, $Profiles, $products, $language, $EventList);
                                echo '</TD>';

                                if(($Temp % 6) == 5) echo "</TR><tr>";

                                $Month++;
                                if($Month==13){
                                    $Month=1;
                                    $Year++;
                                }
                            }
                            echo '</TR></TABLE>';

                            function makemonthtable($strings, $Year, $Month, $Events, $Clients, $Profiles, $products, $language, &$EventList){
                                if($Month<10){$Month="0" . $Month;}
                                ?>
                                    <table width="200" border="0" cellpadding="2" cellspacing="2">
                                        <THEAD>
                                        <tr align="center">
                                            <td colspan="7"><?php echo $strings['month_long' . $Month].' '.$Year; ?></td>
                                        </tr>
                                        <tr>
                                            <TD align="right"><STRONG>S</STRONG></TD>
                                            <TD align="right"><STRONG>M</STRONG></TD>
                                            <TD align="right"><STRONG>T</STRONG></TD>
                                            <TD align="right"><STRONG>W</STRONG></TD>
                                            <TD align="right"><STRONG>T</STRONG></TD>
                                            <TD align="right"><STRONG>F</STRONG></TD>
                                            <TD align="right"><STRONG>S</STRONG></TD>
                                        </tr>
                                        </THEAD>
                                        <TBODY>
                                            <TR>
                                            <?php
                                            $timestamp = mktime(0,0,0,$Month,1,$Year);
                                            $maxday = date("t",$timestamp);
                                            $thismonth = getdate ($timestamp);
                                            $startday = $thismonth['wday'];
                                            $today = date('Y-m-d');
                                            for ($i=0; $i<($maxday+$startday); $i++) {
                                                $Style = "";
                                                $Day = $i - $startday + 1;
                                                if(($i % 7) == 0 ) echo "<tr>";
                                                $Title=array();
                                                if($i < $startday) {
                                                    echo "<td></td>";
                                                } else {
                                                    $Date = $Year . '-' . $Month . '-';
                                                    if($Day<10){
                                                        $Date .= "0" . $Day;
                                                    } else {
                                                        $Date .= $Day;
                                                    }

                                                    $Event = array();
                                                    echo "<td align='right' border='1' valign='middle' height='20px' style='";
                                                    if($today == $Date) {
                                                        echo 'border:1px solid #000;';
                                                        $Title[] = "Today";
                                                    }

                                                    foreach($Events as $CRON){
                                                        if(in_array($Date, $CRON["dates"])){
                                                            if(!isset($_GET["clientid"]) || (isset($_GET["clientid"]) && $_GET["clientid"] == $CRON["client_id"])) {
                                                                $CRON["client"] = getIterator($Clients, "id", $CRON["client_id"]);
                                                                $CRON["client"] = $CRON["client"]->company_name;
                                                                $Profile = getIterator($Profiles[$CRON["client_id"]], "id", $CRON["profile_id"]);
                                                                $CRON["profile"] = formatname($Profile);
                                                                $Style = "background-color: silver;";
                                                                $Products = productname($products, $CRON["forms"], $language);
                                                                $Title[] = $CRON["profile"] . " [" . $CRON["client"] . "] (" . $Products . ')';
                                                                $Event[$CRON["client"]][] = array("Profile" => $CRON["profile"], "Products" => $Products);
                                                            }
                                                        }
                                                    }

                                                    echo $Style . "' " . 'TITLE="' . implode("\r\n", $Title) . '">'. $Day . "</td>";
                                                    $Index = array_search ("Today", $Title);
                                                    if($Index > -1){unset($Title[$Index]);}
                                                    $Title = implode("\r\n", $Title);
                                                    if($Title) {
                                                        $EventList[$Date] = $Event;
                                                    }
                                                }
                                                if(($i % 7) == 6 ) echo "</tr>";
                                            }
                                echo '</TR></TBODY></TABLE>';
                            }
echo '</TD><TD><textarea disabled style="width:100%; height:100%; background-color: white; border: none; overflow-y: auto;">';
                                foreach($EventList as $Date => $Event){
                                    echo $Date . "\r\n";
                                    foreach($Event as $Client => $Data){
                                        if(!$Showname) {echo "Client:\t\t" . $Client . "\r\n";}
                                        foreach($Data as $EventData){
                                            echo "Profile:\t\t" . $EventData["Profile"];
                                        }
                                    }
                                    echo "\r\nProducts:\t" . $EventData["Products"] .  "\r\n\r\n";
                                }
                            ?></textarea>
                        </TD></TR></TABLE>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>