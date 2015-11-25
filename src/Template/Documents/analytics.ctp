<?php
$settings = $Manager->get_settings();
$sidebar = $Manager->loadpermissions($Me, "sidebar");
$company_name="";
if (isset($client)){$company_name = " (" . $client->company_name . ")";}

//* Date format= 2015-02-05  "Y-m-d" http://www.flotcharts.org/flot/examples/
include_once('subpages/api.php');
$language = $this->request->session()->read('Profile.language');
$GLOBALS["language"] = $language;
//$registry = $this->requestAction('/settings/getRegistry');
$strings = CacheTranslations($language, array("analytics_%","month_%") ,$settings);//,$registry);
//print_r($strings);

function left($text, $length){
	return substr($text,0,$length)	;
}
function right($text, $length){
	return substr($text, -$length);
}
function extractdate($text){
    if(strpos ($text, " ")) { return trim(left($text, strpos($text, " ")));}
    return $text;
}

function sortdates($data, $draft = ""){
	$dates = array();
	foreach ($data as $order) {
	 	$doit = true;
		if (strlen($draft)>0){
			$thedraft = $order->draft;
			$thedraft2 = $order->drafts;
			if (strlen($thedraft)>0) { $doit = ($draft == $thedraft); }
			if (strlen($thedraft2)>0) { $doit = ($draft == $thedraft2); }
			//echo $thedraft . " " . $thedraft2 . " " . $doit  . "<BR>";
		}
		if ($doit) {
			$thedate = extractdate($order->created);
			if (array_key_exists($thedate, $dates)) {
				$dates[$thedate] += 1;
			} else {
				$dates[$thedate] = 1;
			}
		}
	}
	return $dates;
}

function total($array){
	$total=0;
	foreach($array as $value){
		$total+=$value;
	}
	return $total;
}

$days = 14;
$decimals = 2;
$startdate = -1;

if (isset($_GET["days"])) {$days = $_GET["days"]; }
if (isset($_GET["to"]) AND isset($_GET["from"])) {
	$startdate = $_GET["to"];
	$enddate = $_GET["from"];
	if (getdatestamp($_GET["to"]) < getdatestamp($_GET["from"])) { 
		$startdate = $_GET["from"];
		$enddate = $_GET["to"];
	}
	$days = date_diff(date_create($startdate), date_create($enddate), true)->days+1;
}
if ($days < 1) { $days = 1; }

if ($startdate == -1) { $startdate = date("Y-m-d"); }
if (!isset($enddate)){ $enddate = substr(add_date($startdate, -$days+1,0,0), 0, 10); }

$isdraft = "0";
if (isset($_GET["drafts"])) {$isdraft = 1;}

$orderdates= sortdates($orders, $isdraft);
$ordercount= total($orderdates);
$ordavg = round ($ordercount / $days,$decimals);

$docdates= sortdates($documents, $isdraft);
$doccount= total($docdates);
$docavg = round ($doccount / $days,$decimals);

$profiledates = sortdates($profiles, $isdraft);
$profilecount=total($profiledates);
$profileavg= round ($profilecount / $days,$decimals);

$clientdates = sortdates($clients, $isdraft);
$clientcount=total($clientdates);
$clientavg= round ($clientcount / $days,$decimals);

foreach($answers as $answer){
    $answer->Quizname = getquiz($courses, $answer->QuizID)->Name;
    //debug($answer);
}

$quizdates = sortdates($answers, $isdraft);
$quizcount = total($quizdates);
$quizavg = round($quizcount / $days, $decimals);

function getquiz($courses, $id){
    foreach($courses as $course){
        if ($course->ID == $id) { return $course; }
    }
}



function add_date($givendate,$day=0,$mth=0,$yr=0) {
	$cd = strtotime($givendate);
	$newdate = date('Y-m-d h:i:s', mktime(date('h',$cd), date('i',$cd), date('s',$cd), date('m',$cd)+$mth, date('d',$cd)+$day, date('Y',$cd)+$yr));
	return $newdate;
}

function enumdata($variable, $daysbackwards, $date = -1, $strings){ //* [10, 1], [17, -14], [30, 5] *// strtotime('-1 day', $dateto)
	$tempstr= "";
	$delimeter="";
	if ($date ==-1) { $date= date("Y-m-d"); }
	for ($temp=0; $temp<$daysbackwards; $temp+=1){
		$newdate =add_date($date, -$temp,0,0);
		$thedate = extractdate($newdate);
		//getdatestamp($newdate); //right($thedate,2);
		$day = '"' . todate($newdate, $strings) . '"' ; //if ($temp==0) { $day = '"' . "End". '"' ; } else {
		$quantity = 0;
		if (array_key_exists($thedate,$variable)){$quantity  = $variable[$thedate];}
		$tempstr = "[" . $day . ',' . $quantity . "]" . $delimeter . $tempstr;
		if ($temp==0) { $delimeter = ", " ;}
	}

	return $tempstr;
}

$Dir = "assets/global/plugins/flot/jquery.flot.";
JSinclude($this, array($Dir . "min.js", $Dir . "resize.min.js", $Dir . "pie.min.js", $Dir . "stack.min.js", $Dir . "crosshair.min.js", $Dir . "categories.min.js", "assets/admin/pages/scripts/charts-flotcharts.js"));//time.js
?>

<h3 class="page-title">
			<?= $strings["analytics_title"] ?> <small><?= $strings["analytics_description"];?></small>
			</h3>

			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo $this->request->webroot;?>"><?= $strings["dashboard_dashboard"];?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="<?php echo $this->request->webroot;?>documents/index"><?= $strings["index_documents"] ?></a>
                        <i class="fa fa-angle-right"></i>
					</li>
                    <li>
						<a href="" onclick="return false;"><?= $strings["analytics_title"] ?></a>
                        
					</li>
                    
				</ul>
                <a href="javascript:window.print();" class="floatright btn btn-primary"><?= $strings["dashboard_print"]; ?></a>
			</div>

<div class="row" style="display:none;">
    <div class="col-md-12">
        <div class="portlet box red">
        	<div class="portlet-title">
        		<div class="caption">
        			<i class="fa fa-gift"></i><?php echo ucfirst($settings->document);?>s Chart
        		</div>
 
        	</div>
        	<div class="portlet-body">
        		<div id="chart_2" class="chart">
        		</div>
        	</div>
        </div>
    </div>
</div>

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->

<script>
jQuery(document).ready(function() {

	ChartsFlotcharts.init();
	ChartsFlotcharts.initCharts();
	ChartsFlotcharts.initPieCharts();
	ChartsFlotcharts.initBarCharts();


	var data = [{
		data: [<?php echo enumdata($docdates, $days, $startdate, $strings); ?>]
	}];
	var data2 = [{
		data: [<?php echo enumdata($orderdates, $days, $startdate, $strings); ?>]
	}];
	var data3 = [{
		data: [<?php echo enumdata($profiledates, $days, $startdate, $strings); ?>]
	}];
	var data4 = [{
		data: [<?php echo enumdata($clientdates, $days, $startdate, $strings); ?>]
	}];
    var data5 = [{
        data: [<?php echo enumdata($quizdates, $days, $startdate, $strings); ?>]
    }];

	var options = marking(<?php echo $docavg; ?>, 'red');

	function marking(average, thecolor) {
		return {
			series: {
				lines: {
					show: true
				},
				points: {
					show: true
				}
			},
			legend: {
				noColumns: 2
			},
			xaxis: {
				tickDecimals: 0,
				mode: "categories"
			},
			yaxis: {
				tickDecimals: 0,
				min: 0
			},
			selection: {
				mode: "x"
			},
			grid: {
				markings: [
					{color: thecolor, lineWidth: 1, yaxis: {from: average, to: average}}
				]
			},
			colors: [thecolor]
		};
	}


	function bind(name, data, average, color) {
		options = marking(average, color);
		var placeholder = $(name);

		placeholder.bind("plotselected", function (event, ranges) {

			$("#selection").text(ranges.xaxis.from.toFixed(1) + " to " + ranges.xaxis.to.toFixed(1));

			var zoom = $("#zoom").prop("checked");

			if (zoom) {
				$.each(plot.getXAxes(), function (_, axis) {
					var opts = axis.options;
					opts.min = ranges.xaxis.from;
					opts.max = ranges.xaxis.to;
				});
				plot.setupGrid();
				plot.draw();
				plot.clearSelection();
			}
		});
		var plot = $.plot(placeholder, data, options);
	}


	bind("#documents", data, <?php echo $docavg; ?>, "#F2784B");
	bind("#orders", data2, <?php echo $ordavg; ?>, "#FFB848");
	bind("#profiles", data3, <?php echo $profileavg; ?>, "#44B6AE");
	bind("#clients", data4, <?php echo $clientavg; ?>, "#ACB5C3");
    bind("#courses", data5, <?php echo $quizavg; ?>, "#578EBE");

});
</script>
									<div class="chat-form"> <form action="<?php echo $this->request->webroot; ?>documents/analytics" method="get">
										<div class="row">
											<div class="col-md-11" align="right" style="margin-right:0;padding-right:0">
												<div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="yyyy-mm-dd">
													<span class="input-group-addon"><?= $strings["analytics_start"]; ?></span>
													<input type="text" class="form-control" name="from" value="<?php echo $enddate; ?>" style="min-width: 100px;">
													<span class="input-group-addon"><?= $strings["analytics_finish"]; ?></span>
													<input type="text" class="form-control" name="to" title="<?= $strings["analytics_leaveblank"] ?>" value="<?php echo get2("to", date("Y-m-d")); ?>" style="min-width: 100px;">
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
									</form></div>
									
<?php 
function get2($name, $default ="" ){
		if (isset($_GET[$name])) { return $_GET[$name]; }
		return $default;
	}
	
function todate($date, $strings){
    return $strings["month_short" . date("m", getdatestamp($date))] . date(" d", getdatestamp($date));
	//return date("M d", getdatestamp($date));
}

function datecheck($date, $start, $end){
	$datestamp = getdatestamp($date);
	$startstamp = getdatestamp($start);
	$endstamp = getdatestamp($end);
	//echo "<DATE " . getdatestamp($date) . " " . getdatestamp($start) . " " . getdatestamp($end) . " " . (getdatestamp($date) >= getdatestamp($start) ) . " " . ( getdatestamp($date) <= getdatestamp($end))  . ">";
	return ( ($datestamp <= $startstamp AND $datestamp >= $endstamp)  or ($datestamp >= $startstamp AND $datestamp <= $endstamp)) ;
}

function pluralize($text, $quantity){
    if (substr($text,-1) == "s") { $text = substr($text, 0, strlen($text)-1);}
    if ($quantity == 1 || substr($text, -1)=="d") { return $text; }
    return $text . "s";
}

function newchart($color, $icon, $title, $chartid, $dates, $data, $start,$end, $isdraft, $profiletypes, $clienttypes, $strings){
	echo '<P><div class="row"><div class="col-md-12">';
		echo '<div class="portlet box ' . $color . '">';
			echo '<div class="portlet-title">';
				echo '<div class="caption">';
					echo '<i class="' . $icon . '"></i>' . $title;
					if($isdraft == 1){ echo " Drafts"; }
				echo '</div></div>';
			echo '<div class="portlet-body">';
				echo '<div class="row"><div class="col-md-8">';
				echo '<div id="' . $chartid . '" class="chart"> </div>';


				$didit=false;
				if (count($dates) > 0) {
					$rawdata = '<textarea disabled style="width:100%; height:300px; background-color: white; border: none; overflow-y: auto;">';
					foreach($dates as $key => $value){
						if (datecheck($key,$start,$end)){
							$didit=true;
							$rawdata.=todate($key, $strings) . ":\t" . $value . " " . pluralize(left(strtolower($title), strlen($title)-1), $value) . "\r\n";
							$alldocs = enumsubdocs($data, $key, $chartid, $isdraft, $profiletypes, $clienttypes, $strings);
							foreach($alldocs as $key => $value){
								$rawdata.="\t" . $value . ' ' . pluralize($key, $value) . "\r\n";
							}
						}	
					}

					$rawdata.='</textarea>';
				} 
				if (!$didit) {
					$rawdata = $strings["dashboard_negative"] . " " .  strtolower($title);
				}
				
				echo '</DIV><div class="col-md-4">' . $rawdata  . '</div>';
				echo '</div></div></div></div></div>';
}

if($sidebar->client_list==1) {//clients
    newchart("grey-salsa", "icon-globe", $strings["index_clients"], "clients", $clientdates, $clients, $startdate, $enddate, $isdraft, $profiletypes, $clienttypes, $strings);//new clients
}

if($sidebar->profile_list==1) {//profiles
    newchart("green-haze", "icon-user", $strings["index_profiles"], "profiles", $profiledates, $profiles, $startdate, $enddate, $isdraft, $profiletypes, $clienttypes, $strings);//new users
}

if($sidebar->document_list==1) {//documents
    newchart("yellow-casablanca", "icon-doc", $strings["index_documents"] . $company_name, "documents", $docdates, $documents, $startdate, $enddate, $isdraft, $subdocuments, $clienttypes, $strings);//new documents
}

if($sidebar->orders_list==1) {//orders
    newchart("yellow", "icon-docs", $strings["index_orders"] . $company_name, "orders", $orderdates, $orders, $startdate, $enddate, $isdraft, $profiletypes, $clienttypes, $strings);//new orders
}

if($sidebar->training==1) {//cpurses/training
    newchart("blue-steel", "fa fa-graduation-cap", $strings["index_courses"], "courses", $quizdates, $answers, $startdate, $enddate, $isdraft, $profiletypes, $clienttypes, $strings);//new quiz completions
}

function FindIterator1($ObjectArray, $FieldName, $FieldValue){
    foreach($ObjectArray as $Object){
        if ($Object->$FieldName == $FieldValue){return $Object;}
    }
    return false;
}

function enumsubdocs($thedocs, $date, $chartid, $isdraft, $profiletypes, $clienttypes, $strings){
	$alldocs = array();
	$unknown= $strings["analytics_notspecified"];
    $language= $GLOBALS["language"];
    $title = getFieldname("title",$language);
    if($language == "Debug"){ $Trans = " [Translated]"; } else {$Trans = "";}

	foreach($thedocs as $adoc){
		if(left($adoc->created, 10) == $date){
			$doit = true;

			if (strlen($isdraft)>0){
				$thedraft1 = $adoc->draft;
				$thedraft2 = $adoc->drafts;
				if (strlen($thedraft1)>0) { $doit = ($isdraft == $thedraft1); }
				if (strlen($thedraft2)>0) { $doit = ($isdraft == $thedraft2); }
				echo "<TEST '" . $thedraft1 . "' '" . $thedraft2 . "' (" . $doit  . ") >";
			}

			if ($doit) {
				$doctype = $adoc->document_type;
                if($doctype AND $language != "English"){//documents
                    $subdoc = FindIterator1($profiletypes, "id", $adoc->sub_doc_id);//subdocuments passed into profiletypes
                    $doctype = $subdoc->$title . $Trans;
                }

				if ($chartid == "profiles") {//PROFILE TYPES
					$doctype = $adoc->profile_type;
					if (is_numeric($doctype)) {
                        $doctype = FindIterator1($profiletypes, "id", $doctype)->$title . $Trans;
					} else {
						$doctype = $unknown;
					}
				}
				if ($chartid == "orders") {
					$doctype = $adoc->is_hired;
					if ($doctype) {
						$doctype = $strings["analytics_hired"];
					} else {
						$doctype = $strings["analytics_nothired"];
					}
				}
				if ($chartid == "clients") {
					$doctype = $adoc->customer_type;
					if (is_numeric($doctype)) {
						//$profiletypes = ['', 'Insurance', 'Fleet', 'Non Fleet'];
						//$doctype = $profiletypes[$doctype];
                        $doctype = FindIterator1($clienttypes, "id", $doctype)->$title . $Trans;
					} else {
						$doctype = $unknown;
					}
				}
                if ($chartid == "courses") {
                    $doctype = $adoc->Quizname;
                }

				//if (strlen($doctype )==0){$doctype = $adoc->customer_type; }
				if (strlen($doctype) > 0) {
                    if (substr($doctype,0,1)=='"') {$doctype = substr($doctype,1, strlen($doctype)-1);}
                    if (substr($doctype,-1)=='"') {$doctype = substr($doctype,0, strlen($doctype)-1);}
                    $doctype = ucfirst($doctype);
					$quantity = 0;
					if (array_key_exists($doctype, $alldocs)) {
						$quantity = $alldocs[$doctype];
					}
					$alldocs[$doctype] = $quantity + 1;
				}
			}
		}
	}
	return $alldocs;
}

?>