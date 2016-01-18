<?php
function getSubject($subjects, $keyname, $value) {
    foreach ($subjects as $subject) {
        if ($subject->$keyname == $value) {
            return $subject;
        }
    }
}

$debug = $this->request->session()->read('debug');
//print_r($conditions);

include_once('subpages/api.php');
$settings = $this->requestAction('settings/get_settings');
$language = $this->request->session()->read('Profile.language');
$controller = $this->request->params['controller'];
$strings = CacheTranslations($language, array("invoice_%", "documents_%", "analytics_%", "month_long%", "month_short%", "forms_%", "infoorder_nonefound", "forms_dateformat", "orders_completed", "tasks_date"), $settings);
if ($debug && $language == "Debug") {
    $Trans = " [Translated]";
} else {
    $Trans = "";
}
function asDollars($value = 0, $SMI = false) {
    if ($value == -1) {
        return "TBD";
    }
    $tempstr = '$' . number_format($value, 2);
    if ($SMI) {
        $tempstr .= "*";
    }
    $tempstr = str_replace(".", ".<SUP>", $tempstr) . "</SUP>";

    return '<DIV CLASS="nowrap">' . $tempstr . "</DIV>";
}

function asdate($strings, $date) {//YYYY-MM-DD HH:MM:SS
    $date = explode(" ", $date);
    $date = explode("-", $date[0]);
    return $strings["month_short" . $date[1]] . " " . $date[2] . ", " . $date[0];
    //return $date[0];
}

$total = 0;
$doquantity = false;
$ordertype = "Other";
$currency = "USD";
$country = "";
$showEmailAndPhone = false;
if (isset($_GET['client_id'])) {
    if (!$_GET['client_id']) {
        unset($_GET['client_id']);
    }
}

if (isset($_GET['client_id'])) {
    $client = $this->requestAction("/clients/getClient/" . $_GET['client_id']);
    $country = $client->country;
    if ($country == "CAN") {
        $currency = "CAD";
    }
    if ($country == "CAN" || $client->country == "USA") {
        $ordertype = $country;
    } else {
        $latinamerica = array("ARG", "BOL", "BRA", "CHL", "COL", "COS", "CUB", "DOM", "ECU", "SLV", "GUF", "GLP", "GTM", "HTI", "HND", "MTQ", "MEX", "NIC", "PAN", "PRY", "PER", "PRI", "BLM", "MAF", "URY", "VEN");
        if (in_array($country, $latinamerica)) {
            $ordertype = "LA";
        }
    }
}

function getname($profile) {
    if ($profile->fname && $profile->lname) {
        return ucfirst($profile->fname) . " " . ucfirst($profile->lname);
    } elseif ($profile->username) {
        return ucfirst($profile->username);
    }
}

function makeoption($GET, $Value, $Text = false){
	if(is_array($Value)){
		foreach($Value as $VAL => $Text){
			makeoption($GET, $VAL, $Text);
		}
	} else {
		echo '<OPTION VALUE="' . $Value . '"';
		if (isset($_GET[$GET]) && $_GET[$GET] == $Value) {
			echo ' SELECTED';
		}
		echo '>' . $Text . '</OPTION>';
	}
}

$COLS = 7;
?>
<STYLE>
    @media print {
        @page {size: landscape}

        a[href]:after {
            content: none !important;
        }

        .page-title {
            display: none;
        }

        .fullpage{
            font-size: 10px;
        }
        .smalltext{
            font-size: 10px;
        }

        .nowrap{
            white-space: nowrap !important;
        }

		.noprint{
			display:none;
		}
    }
</STYLE>
<link href="<?php echo $this->request->webroot; ?>assets/admin/pages/css/invoice.css" rel="stylesheet" type="text/css"/>
<h3 class="page-title">
    <?= $strings["index_invoice"] ?>
    <small></small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="index.html"><?= $strings["dashboard_dashboard"] ?></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="#"><?= $strings["index_orders"] ?></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="#"><?= $strings["index_invoice"] ?></a>
        </li>
    </ul>
    <!--<div class="page-toolbar">
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
            Actions <i class="fa fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">
                <li>
                    <a href="#">Action</a>
                </li>
                <li>
                    <a href="#">Another action</a>
                </li>
                <li>
                    <a href="#">Something else here</a>
                </li>
                <li class="divider">
                </li>
                <li>
                    <a href="#">Separated link</a>
                </li>
            </ul>
        </div>
    </div>-->

    <div class="clearfix"></div>
    <div class="chat-form">
        <form action="" method="get">
            <div class="row">
                <div class="col-md-10" align="right" style="margin-right:0;padding-right:0">
                    <div style="float:right;">
                        <select id="client_id" name="client_id" class="form-control showprodivision input-inline" style="margin-left: 5px;" onchange="updateURL();">
                            <option value=""><?= $strings["invoice_company"]; ?></option>
                            <?php
                            $clients=$clients->order(["company_name" => "ASC"]);
                            foreach ($clients as $c) {
                                echo '<option value="' . $c->id . '"';
                                if (isset($_GET['client_id']) && $_GET['client_id'] == $c->id) {
                                    echo " selected='selected'";
                                }
                                echo '>' . $c->company_name . '</option>';
                            }

                            $datetype = "completed";
                            if (isset($_GET['datetype']) && $_GET['datetype']) {
                                $datetype = $_GET['datetype'];
                            }
                            ?>
                        </select>
                    </div>

                    <?php if ($this->Session->read('Profile.super') || true) { ?>
                        <div class="input-group input-medium" style="float:right; margin-left: 8px;">
                            <span class="input-group-addon"><?= $strings["tasks_date"]; ?></span>
                            <SELECT name="datetype" class="form-control">
                                <OPTION value="created" <?php if ($datetype == "created") {
                                    echo "SELECTED";
                                } ?>><?= $strings["documents_created"]; ?>
                                </OPTION>
                                <OPTION value="completed" <?php if ($datetype == "completed") {
                                    echo "SELECTED";
                                } ?>><?= $strings["orders_completed"]; ?>
                                </OPTION>
                            </SELECT>
                            <!--LABEL>
                                <input type="checkbox" name="csv" value="invoice">CSV
                            </LABEL-->
                        </div>
                    <?php } ?>

                    <div class="input-group input-medium" style="float:right; margin-left: 8px;">
                        <span class="input-group-addon"><?= $strings["invoice_invoice"]; ?></span>
                        <input type="text" class="form-control" name="salesrep" value="<?php
                        if (isset($_GET["salesrep"])){
                            echo $_GET["salesrep"];
                        }
                        ?>">
                    </div>

                    <div class="input-group input-medium" style="float:right; margin-left: 8px;">
                        <span class="input-group-addon"><?= $strings["index_invoice"]; ?> #</span>
                        <input type="text" class="form-control" name="invoicenum" value="<?php
                        if (isset($_GET["invoicenum"])){
                            echo $_GET["invoicenum"];
                        }
                        ?>">
                    </div>

                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy-mm-dd">
                        <span class="input-group-addon"> <?= $strings["analytics_start"]; ?> </span>
                        <input onchange="updateURL();" id="from" type="text" class="form-control" name="from"
                               title="Leave blank to search all orders"
                               value="<?php if (isset($_GET['from'])) echo $_GET['from']; ?>"
                               style="min-width: 100px;"/>
                        <span class="input-group-addon"> <?= $strings["analytics_finish"]; ?> </span>
                        <input onchange="updateURL();" id="to" type="text" class="form-control" name="to"
                               title="Leave blank to end at today"
                               value="<?php if (isset($_GET['to'])) echo $_GET['to']; ?>" style="min-width: 100px;">
                        <span class="input-group-addon" style="display: none"> <A href="" onclick="return reset();"><?= $strings["invoice_reset"]; ?></A> </span>
                        <!--button type="submit" class="btn btn-primary" style="float">Search</button-->
                    </div>

					<div class="input-group input-medium" style="float:right; margin-left: 8px;">
						<span class="input-group-addon"> <?= $strings["invoice_invoiced"] ?>:</span>
						<SELECT NAME="invoiced" CLASS="form-control">
							<?php makeoption("invoiced", array("-1" => $strings["invoice_either"], "1" => $strings["dashboard_affirmative"], "0" => $strings["dashboard_negative"])); ?>
						</SELECT>
					</div>
					<div class="input-group input-medium" style="float:right; margin-left: 8px; height: 34px !important; width: 34px !important;">
						<span class="input-group-addon"><LABEL FOR="selectall"><?= $strings["dashboard_selectall"] ?>:</LABEL></span>
						<span class="input-group-addon">
							<INPUT TYPE="checkbox" onclick="doselectall(event);" id="selectall" class="form-control input-group-addon" style="height: 100%; width: 100%;">
						</SPAN>
					</DIV>
                </div>
                <!--div class="col-md-4" style="position: relative;  top: 50%;  transform: translateY(+20%);">
    				<input type="checkbox" name="drafts" value="1" <?php if ($isdraft) {
                    echo "checked";
                } ?> ><label class="control-label" for="drafts">Drafts</label>
    			</div-->
                <div class="col-md-2" align="right" style="padding-left:0;margin-left:0">
                    <?php if ($this->Session->read('Profile.super')) { ?>
                        <A HREF="?csv=clients" class="btn btn-info" download="clients.csv"><?= $strings["invoice_clientscsv"]; ?></A>
                    <?php } ?>
                    <A href="invoice" class="btn btn-warning"><?= $strings["invoice_reset"]; ?></A>
                    <A HREF="invoice?csv=invoice" class="btn btn-info" id="myLink" download="invoice.csv"
                       style="display: none"><?= $strings["dashboard_search"]; ?> (CSV)</A>
                    <button type="submit" class="btn btn-primary"><?= $strings["dashboard_search"]; ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END PAGE HEADER-->
<div id="toast" class="noprint" style="color: rgb(255,0,0);"></div>
<script>
    function updateURL() {
        var URL = "invoice?csv=invoice";

        //client ID
        element = document.getElementById("client_id");
        URL = URL + "&client_id=" + element.options[element.selectedIndex].value;

        //from date
        element = document.getElementById("from");
        URL = URL + "&from=" + element.value;

        //to date
        element = document.getElementById("to");
        URL = URL + "&to=" + element.value;

        //update URL
        element = document.getElementById("myLink");
        element.href = URL;
    }

    function reset() {
        document.getElementById("from").value = "";
        document.getElementById("to").value = "";
        updateURL();
        return false;
    }

    updateURL();
</script>

<!-- BEGIN PAGE CONTENT-->

<div class="invoice">

    <div class="row invoice-logo">
        <div class="col-xs-6 invoice-logo-space">
            <?php if (isset($_GET['client_id'])){ ?>

            <img class="img-responsive" style="max-width:180px;" id="clientpic" alt=""
                 src="<?php if (isset($client->image) && $client->image) {
                     echo $this->request->webroot; ?>img/jobs/<?php echo $client->image . '"';
                 } else {
                     echo $this->request->webroot; ?>img/clients/<?php echo $settings->client_img . '"';
                 }
                 echo '/>';
                 } ?>
		</div>
		<div class="col-xs-12">
            <?php
            if (isset($_GET["salesrep"]) && $_GET["salesrep"]){
                echo '<p style="white-space:nowrap;">' . $strings["invoice_invoice"] . ': ' . $_GET["salesrep"] . '</P>';
            }
            if (isset($_GET["invoicenum"]) && $_GET["invoicenum"]){
                echo '<p style="white-space:nowrap;">' . $strings["index_invoice"] . ': ' . $_GET["invoicenum"] . '</P>';
            }
            ?>
            <STYLE>
                @media print {
                    .myInput {
                        text-align: right;
                        border: none !important;
                        box-shadow: none !important;
                        outline: none !important;
                    }
                }
            </STYLE>
            <P>
                <?= $strings["month_long" . date('m')] . date(' d, Y'); ?>
                <span class="muted"></span>
            </p>
        </div>
    </div>
    <hr/>
    <div class="row">
        <?php
        $client_id = 0;
        if (isset($_GET['client_id'])){
        $client_id = $_GET['client_id'];?>

        <div class="col-xs-6">
            <h3><?= $strings["invoice_company"]; ?>:</h3>
            <ul class="list-unstyled">
                <li>
                    <?php echo $client->title;?>
                </li>
                <li>
                    <?php echo $client->company_name;?>
                </li>
                <li>
                    <?php echo $client->address;?>
                </li>

            </ul>
        </div>
        <!--div class="col-xs-4"></div>
		<div class="col-xs-4" style="display: none;">
			<h3>About:</h3>
			<ul class="list-unstyled">
				<li>
					 <?php echo $client->description;?>
				</li>
			</ul>
		</div-->
        <div class="col-xs-6 invoice-payment">
            <h3><?= $strings["invoice_paymentdetails"]; ?>:</h3>
            <TABLE class="list-unstyled">
                <tr>
                    <TD><strong><?= $strings["forms_companyname"]; ?>:</strong></TD>
                    <TD style="width: 5px;"></TD>
                    <TD>
                        <A HREF="<?php echo $this->request->webroot . "clients/edit/" . $client->id . '?view">' . $client->company_name;?></A></TD>
                </tr>
                <tr>
                    <TD><strong><?= $strings["forms_address"]; ?>:</strong></TD>
                    <TD></TD>
                    <TD><?= $client->company_address; ?></TD>

                </tr>
                <tr>
                    <TD><strong><?= $strings["forms_city"]; ?>:</strong></TD>
                    <TD></TD>
                    <TD><?= $client->city; ?></TD>

                </tr>
                <tr>
                    <TD><strong><?= $strings["forms_postalcode"]; ?>:</strong></TD>
                    <TD></TD>
                    <TD><?= $client->postal; ?></TD>
                </tr>
                <tr>
                    <TD><strong>Country:</strong></TD>
                    <TD></TD>
                    <TD><?= $client->country; ?></TD>
                </tr>
                <tr>

                    <TD><strong>Contact Name:</strong></TD>
                    <TD></TD>
                    <TD><?= $client->sig_fname . " " . $client->sig_lname; ?></TD>
                </tr>
                <tr>
                    <TD><strong>Email:</strong></TD>
                    <TD></TD>
                    <TD><?= $client->sig_email; ?></TD>
                </tr>
                <tr>
                    <TD><strong>Phone:</strong></TD>
                    <TD></TD>
                    <TD><?= $client->company_phone; ?></TD>
                </tr>
            </TABLE>
		</div>
        <?php } ?>
	</div>
	<div class=" row">

                        <div class="col-xs-12">
                            <table class="table table-striped table-hover fullpage">
                                <thead>
                                <tr>
                                    <th class="smalltext">
                                        ID
                                    </th>
                                    <th class="smalltext">
                                        <?= $strings["invoice_item"]; ?>
                                    </th>
                                    <TH OLDTITLE="<?= $strings["forms_dateformat"]; ?>" class="smalltext">
                                        <?= $strings["invoice_datecreated"]; ?>
                                    </TH>
                                    <TH OLDTITLE="<?= $strings["forms_dateformat"]; ?>" class="smalltext">
                                        <?= $strings["invoice_datecompleted"]; ?>
                                    </TH>
                                    <th class="hidden-480 smalltext">
                                        <?= $strings["invoice_description"]; ?>
                                    </th>
                                    <?php
                                    $includeusers = true;
                                    if ($includeusers) {
                                        echo '<th class="hidden-480 smalltext">' . $strings["documents_submittedby"] . '</th>';
                                        if ($showEmailAndPhone) {
                                            echo '<th class="hidden-480 smalltext">' . $strings["forms_phone"] . '</th>';
                                            echo '<th class="hidden-480 smalltext">' . $strings["forms_email"] . '</th>';
                                        }
                                        echo '<th class="hidden-480 smalltext">' . $strings["documents_submittedfor"] . '</th>';
                                    }
                                    if (!$client_id) {
                                        echo '<th class="hidden-480 smalltext">' . $strings["settings_client"] . '</th>';
                                    }
                                    if ($doquantity) {
                                        echo '<th class="hidden-480 smalltext">' . $strings["invoice_quantity"] . '</th>';
                                        echo '<th class="hidden-480 smalltext">' . $strings["invoice_unitcost"] . '</th>';
                                    } ?>
                                    <th class="smalltext"><?= $strings["forms_country"]; ?></th>
                                    <th class="smalltext">
                                        <?= $strings["invoice_total"]; ?>
                                    </th>
									<TH CLASS="smalltext noprint">
										<?= $strings["invoice_invoiced"] ?>
									</TH>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $taxlesstotal = 0;
                                if (count($orders) == 0) {
                                    echo "<TR><TD COLSPAN='" . $COLS . "' ALIGN='CENTER'><strong>" . $strings["infoorder_nonefound"] . "</strong></TD></TR>";
									if($debug){
										echo "<TR><TD COLSPAN='" . $COLS . "' ALIGN='CENTER'><strong>" . print_r($conditions, true) . "</strong></TD></TR>";
									}
                                } else {
                                    foreach ($orders as $order) {
                                        foreach ($order->documents as $productype) {
                                            $SMI = false;
                                            $quantity = 1;
                                            $Fieldname = getFieldname("Name", $language);
                                            //$productype = $order->order_type;
                                            //if(!$productype){$productype = "PSA";}
                                            $productype = FindIterator($products, "Acronym", $productype);
                                            $Price=0;
                                            if ($productype->Price > 0) {
                                                $Price = $Manager->getprice("CAN", $products, $productype, $order->user_id);
                                                $Price = $Price["price"];
                                                $HasURL = ($productype->Acronym == "SPF" && strrpos($order->status, "http") !== false) || ($productype->Acronym == "SMS" && strrpos($order->status1, "http") !== false);
                                                if ($order->user_id == 81) {//hard coded value! BAD! LAZY!
                                                    $taxlesstotal += $quantity * $Price;
                                                    $SMI = true;
                                                } else if ($HasURL) {
                                                    $total += $quantity * $Price;
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td>
                                                    <?= $order->id; ?>
                                                </td>
                                                <td>
                                                    <?= $order->title; ?>
                                                </td>
                                                <TD OLDTITLE="<?= $strings["forms_dateformat"]; ?>">
                                                    <?= asdate($strings, $order->created); ?>
                                                </TD>
                                                <TD OLDTITLE="<?= $strings["forms_dateformat"]; ?>">
                                                    <?php
													if(isset($order->date_completed)) {
														if ($order->date_completed) {
															echo asdate($strings, $order->date_completed);
														} elseif ($order->date_completed2) {
															echo asdate($strings, $order->date_completed2);
														} elseif ($order->date_completed3) {
															echo asdate($strings, $order->date_completed3);
														}
													}
                                                    ?>
                                                </TD>
                                                <td class="hidden-480" title="<?= $order->order_type; ?>">
                                                    <?= $productype->$Fieldname . $Trans; ?>
                                                </td>
                                                <?php
                                                if ($includeusers) {
                                                    $profile = getIterator($profiles, "id", $order->user_id);
                                                    echo '<td class="hidden-480">' . getname($profile) . '</td>';
                                                    if ($showEmailAndPhone) {
                                                        echo '<td class="hidden-480">' . $profile->phone . '</td>';
                                                        echo '<td class="hidden-480">' . $profile->email . '</td>';
                                                    }
                                                    echo '<td class="hidden-480">';
                                                    //$subject = getSubject($subjects, "order_id", $order->id);
                                                    $subject = $this->requestAction('/orders/getSubject/' . $order->id);
                                                    if($subject) { echo $subject; }
                                                    echo '</td>';
                                                }
                                                if (!$client_id) {
                                                    $client = getIterator($clients, "id", $order->client_id);
                                                    /* if($client == 49 && $productype->Acronym == "SPF"){
                                                        $Price=52;
                                                    } */
                                                    echo '<td class="hidden-480">' . $client->company_name . '</td>';
                                                }
                                                if ($doquantity) {
                                                    echo '<td class="hidden-480">' . $quantity . '</td>';
                                                    echo '<td class="hidden-480">' . asDollars($Price) . '</td>';
                                                }
												echo '<td>' . $client->country . '</td>';

												echo '<TD>';
													if ($productype->Acronym == "PSA") {
														echo "TBD";
													} else if ($HasURL) {
														echo asDollars($quantity * $Price, $SMI);
													} else {
														echo asDollars();
													}
												echo '</td>';

												echo '<TD class="noprint">';
													echo '<INPUT TYPE="checkbox" ID="order_' . $order->id . '" class="form-control invoiced"';
													if($order->invoiced){echo ' CHECKED';}
													echo ' ONCLICK="invoiced(event, ' . $order->id . ');">'
												?>
												</TD>
                                            </tr>
                                        <?php }
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <div class="well">
                    <?php
                    if (!isset($_GET['client_id']) || $cou == 'can') {
                        ?>

                        <address>
                            <strong>AFIMACSMI </strong><br/>
                            8160 Parkhill Drive<br/>
                            Milton, Ontario, L9T 5V7<br/>
                            <abbr
                                title="<?= $strings["forms_phone"]; ?>"><?= substr($strings["forms_phone"], 0, 1); ?>
                                :</abbr> 1-800-313-9170 / 905-693-0746
                        </address>
                        <?php
                    } else {
                        ?>
                        <address>
                            <strong>AFIMAC #SMI </strong><br/>
                            703 Waterford Way, Suite 520<br/>
                            Miami, FL 33126<br/>
                            <abbr
                                title="<?= $strings["forms_phone"]; ?>"><?= substr($strings["forms_phone"], 0, 1); ?>
                                :</abbr> (800) 554-4622.
                        </address>
                        <?php
                    }
                    ?>
                    <address>
                        <strong><?= $strings["forms_email"]; ?></strong><br/>
                        <a href="mailto:info@afimacsmi.com?subject=<?= $strings["index_invoice"]; ?>">info@afimacsmi.com</a>
                    </address>
                </div>
            </div>
            <div class="col-xs-8 invoice-block">
                <TABLE class="list-unstyled amounts" style="float: right">
                    <TR>
                        <TD><strong><?= $strings["invoice_subtotal"]; ?>:</strong></TD>
                        <TD style="width: 5px;"></TD>
                        <TD><?= asDollars($total + $taxlesstotal); ?></TD>
                        <TD style="width: 5px;"></TD>
                        <TD><?= $currency; ?></TD>
                    </TR>
                    <TR>

                        <TD>
                            <?php
                            if ($taxlesstotal > 0) {
                                echo "<STRONG>Tax exempt*:</STRONG></TD><TD></TD><TD>" . asDollars($taxlesstotal) . "</TD></TR><TR><TD>";
                            }

                            $tax = getIterator($taxes, "country", $ordertype)->tax; //var_dump($client);
                            $SMItax = getIterator($taxes, "country", "SMI")->tax;
                            $total = ($total * (1 + $tax * 0.01)) + ($taxlesstotal * (1 + $SMItax * 0.01));
                            if (!$country) {
                                $country = "None specified";
                            }
                            ?>

                            <strong
                                title="Order type: <?= $ordertype . " (Country: " . $country; ?>)"><?= $strings["forms_taxes"]; ?>
                                :</strong></TD>
                        <TD></TD>
                        <TD><?= $tax; ?></TD>
                        <TD></TD>
                        <TD align="left">%</TD>
                    </TR>
                    <TR>
                        <TD><strong><?= $strings["invoice_grandtotal"]; ?>:</strong></TD>
                        <TD></TD>
                        <TD><?= asDollars($total); ?></TD>
                        <TD></TD>
                        <TD><?= $currency; ?></TD>
                    </TR>
                </TABLE>
            </DIV>
            <div class="col-xs-8 invoice-block">
                <a class="btn btn-lg blue hidden-print margin-bottom-5" onclick="javascript:window.print();">
                    <?= $strings["dashboard_print"]; ?> <i class="fa fa-print"></i>
                </a>
                <A HREF="invoice?csv=invoice&<?= $_SERVER['QUERY_STRING']; ?>"
                   class="btn btn-lg btn-info hidden-print margin-bottom-5" download="invoice.csv"><?= $strings["invoice_export"]; ?> <i
                        class="fa fa-file-text-o"></i></A>

                <a class="btn btn-lg green hidden-print margin-bottom-5" style="display: none">
                    Submit Your Invoice <i class="fa fa-check"></i>
                </a>
            </div>
        </div>
    </div>
	<SCRIPT>
		function toast(Text, FadeOut){
			$('#toast').stop();
			$('#toast').hide();
			if (FadeOut) {$('.toast').fadeIn(1);}
			$('#toast').html(Text);
			$('#toast').show();
			if (FadeOut) {$('.toast').fadeOut(5000);}
		}

		function doselectall(event){
			var inputs = document.getElementsByTagName("input"); //or document.forms[0].elements;
			var IDs = new Array;
			var ID;
			for (var i = 0; i < inputs.length; i++) {
				if (inputs[i].type == "checkbox" && inputs[i].id != "selectall") {
					ID = inputs[i].id;
					IDs.push( ID.replace("order_", "") );
					var Parent = inputs[i].parentElement;
					if(event.target.checked){
						Parent.className = Parent.className + " checked";
						inputs[i].setAttribute("checked", "checked");
					} else {
						Parent.className = Parent.className.replace(" checked", "");
						inputs[i].removeAttribute("checked");
					}
				}
			}
			IDs=IDs.toString();
			invoiced(event, IDs);
		}

		function invoiced(event, ID){
			var value = 0;
			if(event.target.checked){value=1;}
			$.ajax({
				url: "<?= $this->request->webroot; ?>orders/invoice",
				type: "post",
				dataType: "HTML",
				data: "action=invoiced&id=" + ID + "&status=" + value,
				success: function (msg) {
					//if(msg) {toast(msg, true);}
				}
			})
		}
	</SCRIPT>