<?php
    $debug=$this->request->session()->read('debug');
    include_once('subpages/api.php');

    $settings = $this->requestAction('settings/get_settings');
    $language = $this->request->session()->read('Profile.language');
    $controller =  $this->request->params['controller'];
    $strings = CacheTranslations($language, array("invoice_%", "analytics_%", "month_long%", "forms_%", "infoorder_nonefound"),$settings);
    if($debug && $language == "Debug"){ $Trans = " [Translated]"; } else {$Trans = "";}

    function asDollars($value) {
        return '$' . number_format($value, 2);
    }
    $total = 0;
?>
<STYLE>
    @media print {
        a[href]:after {
            content: none !important;
        }
    }
</STYLE>
<link href="<?php echo $this->request->webroot;?>assets/admin/pages/css/invoice.css" rel="stylesheet" type="text/css"/>
<h3 class="page-title">
    <?= $strings["index_invoice"] ?> <small></small>
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

    <div class="clearfix"></div>
    <div class="chat-form"> 
        <form action="" method="get">
    		<div class="row">
    			<div class="col-md-11" align="right" style="margin-right:0;padding-right:0">
                    <div style="float:right;">
                    
                        <select name="client_id" class="form-control showprodivision input-inline">
                        <option value=""><?= $strings["settings_client"]; ?></option>
                        <?php 
                            foreach($clients as $c)
                            {?>
                             <option value="<?php echo $c->id;?>" <?php if(isset($_GET['client_id']) &&$_GET['client_id']==$c->id)echo "selected='selected'";?>><?php echo $c->company_name;?></option>       
                        <?php
                             }
                        ?>
                        </select>
                    </div>
    				<div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy-mm-dd">
    					<span class="input-group-addon"> <?= $strings["analytics_start"]; ?> </span>
    					<input type="text" class="form-control" name="from" value="<?php if(isset($_GET['from'])) echo $_GET['from'];?>" style="min-width: 100px;"/>
    					<span class="input-group-addon"> <?= $strings["analytics_finish"]; ?> </span>
    					<input type="text" class="form-control" name="to" title="<?= $strings["analytics_leaveblank"]; ?>" value="<?php if(isset($_GET['to'])) echo $_GET['to'];?>" style="min-width: 100px;">
                        <!--button type="submit" class="btn btn-primary" style="float">Search</button-->
    
    				</div>
    			</div>
    			<!--div class="col-md-4" style="position: relative;  top: 50%;  transform: translateY(+20%);">
    				<input type="checkbox" name="drafts" value="1" <?php if($isdraft){ echo "checked";}?> ><label class="control-label" for="drafts">Drafts</label>
    			</div-->
    			<div class="col-md-1" align="right" style="padding-left:0;margin-left:0">
                    <button type="submit" class="btn btn-primary"><?= $strings["dashboard_search"]; ?></button>
    			</div>
    		</div>
    	</form>
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->

    	
<div class="invoice">

	<div class="row invoice-logo">
		<div class="col-xs-6 invoice-logo-space">
         <?php if(isset($_GET['client_id'])){
                $client = $this->requestAction("/clients/getClient/".$_GET['client_id'])?>
            <img class="img-responsive" style="max-width:180px;" id="clientpic" alt=""
             src="<?php if (isset($client->image) && $client->image)
                 {
                     echo $this->request->webroot; ?>img/jobs/<?php echo $client->image . '"';
                 }
                 else
                 {
                    echo $this->request->webroot;?>img/clients/<?php echo $settings->client_img;?>"
            <?php
                }
                
            ?> />
			<?php }?>
		</div>
		<div class="col-xs-6">
			<p>
				<?php echo $strings["month_long" . date('m')] . date(' d, Y');?>
                <span class="muted"></span>
			</p>
		</div>
	</div>
	<hr/>
	<div class="row">
    <?php if(isset($_GET['client_id'])){?>
        
        <div class="col-xs-4">
			<h3><?= $strings["settings_client"]; ?>:</h3>
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
        <div class="col-xs-4"></div>
		<div class="col-xs-4" style="display: none;">
			<h3>About:</h3>
			<ul class="list-unstyled">
				<li>
					 <?php echo $client->description;?>
				</li>
			</ul>
		</div>
		<div class="col-xs-4 invoice-payment">
			<h3><?= $strings["invoice_paymentdetails"] ;?>:</h3>
			<TABLE class="list-unstyled">
				<tr>
					<TD><strong><?= $strings["forms_companyname"];?></strong>:</TD><TD style="width: 5px;"></TD><TD><?php echo $client->company_name;?></TD>
				</tr>
				<tr>
                    <TD><strong><?= $strings["forms_address"];?></strong>:</TD><TD></TD><TD><?php echo $client->company_address;?></TD>
				</tr>
				<tr>
                    <TD><strong><?= $strings["forms_city"];?></strong>:</TD><TD></TD><TD><?php echo $client->city;?></TD>
				</tr>
				<tr>
                    <TD><strong><?= $strings["forms_postalcode"];?></strong>: </TD><TD></TD><TD><?php echo $client->postal;?></TD>
				</tr>
			</TABLE>
		</div>
        <?php }?>
	</div>
	<div class="row">

		<div class="col-xs-12">
			<table class="table table-striped table-hover">
			<thead>
			<tr>
				<th>
					 ID
				</th>
				<th>
                    <?=$strings["invoice_item"];?>
				</th>
				<th class="hidden-480">
                    <?=$strings["invoice_description"];?>
				</th>
				<th class="hidden-480">
                    <?=$strings["invoice_quantity"];?>
				</th>
				<th class="hidden-480">
                    <?=$strings["invoice_unitcost"];?>
				</th>
				<th>
					 <?=$strings["invoice_total"];?>
				</th>
			</tr>
			</thead>
			<tbody>

            <?php
            if(count($orders)==0) {
                echo "<TR><TD COLSPAN='6' ALIGN='CENTER'><strong>" . $strings["infoorder_nonefound"] . "</strong></TD></TR>";
            } else {
                foreach ($orders as $order) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $order->id;?>
                        </td>
                        <td>
                            <?php echo $order->title;?>
                        </td>
                        <td class="hidden-480">
                            <?php
                            $Fieldname = getFieldname("Name", $language);
                            $productype = $order->order_type;
                            $productype = FindIterator($products, "Acronym", $productype);
                            echo $productype->$Fieldname . $Trans;

                            $quantity = 1;
                            $total += $quantity * $productype->Price;
                            ?>
                        </td>
                        <td class="hidden-480">
                            <?= $quantity ?>
                        </td>
                        <td class="hidden-480">
                            <?= asDollars($productype->Price); ?>
                        </td>
                        <td>
                            <?= asDollars($quantity * $productype->Price); ?>
                        </td>

                    </tr>
                <?php }
            }
            ?>
			</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4">
			<div class="well">
				<address>
				    <strong>AFIMACSMI </strong><br/>
                    8160 Parkhill Drive<br/>
                    Milton, Ontario, L9T 5V7<br/>
				    <abbr title="<?= $strings["forms_phone"] ;?>"><?= substr($strings["forms_phone"],0,1); ?>:</abbr> 1-800-313-9170 / 905-693-0746
                </address>
				<address>
				    <strong><?= $strings["forms_email"] ;?></strong><br/>
				    <a href="mailto:info@afimacsmi.com?subject=<?= $strings["index_invoice"];?>">info@afimacsmi.com</a>
				</address>
			</div>
		</div>
		<div class="col-xs-8 invoice-block">
			<TABLE class="list-unstyled amounts" style="float: right">
				<TR>
                    <TD><strong><?=$strings["invoice_subtotal"];?>:</strong></TD><TD style="width: 5px;"></TD><TD><?= asDollars($total);?></TD>
                </TR>
                <TR>
                    <TD><strong><?=$strings["forms_taxes"];?>:</strong></TD><TD></TD><TD><?= $taxes*100; ?>%</TD>
                </TR>
                <TR>
					<TD><strong><?=$strings["invoice_grandtotal"];?>:</strong></TD><TD></TD><TD><?= asDollars($total * (1+$taxes));?></TD>
                </TR>
            </TABLE>
        </DIV>
        <div class="col-xs-8 invoice-block">
			<a class="btn btn-lg blue hidden-print margin-bottom-5" onclick="javascript:window.print();">
			<?= $strings["dashboard_print"];?> <i class="fa fa-print"></i>
			</a>
			<a class="btn btn-lg green hidden-print margin-bottom-5" style="display: none">
			Submit Your Invoice <i class="fa fa-check"></i>
			</a>
		</div>
	</div>
</div>