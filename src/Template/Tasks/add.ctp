<?php
$debug=$this->request->session()->read('debug');
include_once('subpages/api.php');
$settings = $Manager->get_settings();
$language = $this->request->session()->read('Profile.language');
$controller =  $this->request->params['controller'];
$strings = CacheTranslations($language, array($controller  . "_%", "month_long%", "forms_%"),$settings);
//if($debug && $language == "Debug"){ $Trans = " [Translated]"; } else {$Trans = "";}
?>

<script type="text/javascript" src="<?= $this->request->webroot;?>js/datetime.js"></script>
<body onLoad="ajaxpage('timezone');">

<?php
function offsettime2($date, $minutes, $strings){
    //if ($minutes == 0){ return $date;}
    $newdate= date_create($date);
    if ($minutes > 0) {$newdate->modify("+" . $minutes . " minutes"); }
    $month = $strings["month_long" . date_format($newdate, 'm')];
    return str_replace(".", $month, $newdate->format('d . Y - H:i'));
}

function offsettime($date, $offset){
    if ($offset == 0){ return $date;}
    $newdate= date_create($date);
    $hours = floor($offset);
    $minutes = ($offset-$hours)*60;
    $interval = 'PT' . abs($hours) . "H";
    if ($minutes > 0) {$interval .= $minutes . "M";}
    if ($hours>0) {
        $newdate->add(new DateInterval($interval));
    } else {
        $newdate->sub(new DateInterval($interval));
    }
    return $newdate->format('Y-m-d H:i:s');
}

$offset=0;

if ($this->request->session()->read('timediff') && isset($event)) {
    $offset=$this->request->session()->read('timediff');//$_SESSION['timediff'];
    $event->date = offsettime($event->date, $offset);
}
 $disabled = "";
if(isset($isdisabled)) {$disabled = "disabled='disabled'";}

?>
<h3 class="page-title">
			<?=$strings["tasks_pagetitle"];?></small>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?= $this->request->webroot . '">' . $strings["dashboard_dashboard"] ?></a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="<?php echo $this->request->webroot;?>tasks/calender"><?= $strings["tasks_tasks"]; ?></a>
                        <i class="fa fa-angle-right"></i>
					</li>
                    <li>
						<?php
                            if(isset($isdisabled)) {
                                echo $strings["dashboard_view"];
                            } elseif(isset($event)){
                                echo $strings["dashboard_edit"];
                            }else{
                                echo $strings["dashboard_add"];
                            }
                        ?>
					</li>
				</ul>
                <?php
                if (isset($event)) {
                    echo '<a href="javascript:window.print();" class="floatright btn btn-info">' . $strings["dashboard_print"] . '</a>';
                }?>

			</div>

<div class="row">
<div class="col-md-10">
<div data-always-visible="0" data-rail-visible="0" data-handle-color="#dae3e7">
	<form action="" class="form-horizontal" method="post">
		<!-- TASK HEAD -->
		<div class="form">
            <div class="form-group">
				<div class="col-md-12">
					<div class="input-icon">
						<i class="fa fa-calendar"></i>
                        <input type="hidden" name="offset" value="<?= $offset ?>">
						<input type="text" name="date" <?php echo $disabled;?> class="form-control todo-taskbody-due date form_datetime" placeholder="Due Date..." value="<?php
                        if(isset($event)) {
                            echo offsettime2(date('d F Y H:i',strtotime($event->date)), 0, $strings);
                        } else {
                            $minutes = ceil(date("i") / 5) * 5 - date("i");
                            if ($minutes==0){$minutes=5;}
                            if (isset($_GET["date"])) {
                                echo offsettime2(date('Y-m-d ', strtotime($_GET["date"])) . date("H:i"), $minutes, $strings);
                            } else {
                                echo offsettime2(date('Y-m-d H:i'), $minutes, $strings);
                            }
                            //echo date('d F Y - ', strtotime($_GET["date"])) . date("H:i", time() + $minutes * 60000) . " " . $minutes;
                        }?>"/>
					</div>
				</div>
			</div>
			<!-- TASK TITLE -->
			<div class="form-group">
				<div class="col-md-12">
					<input type="text" required <?php echo $disabled;?> name="title" class="form-control todo-taskbody-tasktitle" placeholder="<?=$strings["tasks_title"];?>..." value="<?php if(isset($event))echo $event->title;?>" />
				</div>
			</div>
			<!-- TASK DESC -->
			<div class="form-group">
				<div class="col-md-12">
					<textarea class="form-control todo-taskbody-taskdesc" required <?php echo $disabled;?> name="description" rows="8" placeholder="<?=$strings["tasks_description"];?>..."><?php if(isset($event))echo $event->description;?></textarea>
				</div>
			</div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="checkbox" id="email_self" name="email_self" value="1" <?php if(isset($event) && $event->email_self=='1')echo "checked='checked'";?> <?php echo $disabled;?> /><label for="email_self"><?=$strings["tasks_2yourself"];?></label>
                </div>
				<div class="col-md-12">
					<textarea class="form-control todo-taskbody-taskdesc" <?php echo $disabled;?> name="others_email" rows="2" placeholder="<?=$strings["tasks_2others"];?>"><?php if(isset($event))echo $event->others_email;?></textarea>
                    <input type="hidden" name="timezoneoffset" value="<?= $this->request->session()->read('time') ?>">
				</div>
			</div>
			<!-- END TASK DESC -->
			<!-- TASK DUE DATE -->
			
			<?php if(!isset($isdisabled)){?>
			<div class="form-actions right todo-form-actions">
                <?php if (isset($event)){
                    echo '<a href="../delete/' . $event->id . '" class="btn btn-sm btn-danger delUrl" onclick="return confirm(' . "'" . $strings["tasks_confirmdelete"] . "');" . '">' . $strings["dashboard_delete"] . '</a>';
                } ?>
				<button class="btn btn-sm green-haze" type="submit" name="submit"><?= $strings["forms_savechanges"]; ?></button>
			</div>
            <?php }?>
		</div>
		</div>
	</form>
</div>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo $this->request->webroot;?>css/date.css"/>
<style>
    .table-condensed td:hover{cursor:pointer; }
</style>
