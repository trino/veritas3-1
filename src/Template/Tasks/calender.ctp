<script type="text/javascript" src="<?= $this->request->webroot;?>js/datetime.js"></script>
<body onLoad="ajaxpage('timezone');">
<?php
if ($this->request->session()->read('timediff')) {
    handletimezone($events,$this->request->session()->read('timediff'));
}

$settings = $Manager->get_settings();
include_once('subpages/api.php');
$language = $this->request->session()->read('Profile.language');
$strings = CacheTranslations($language, $this->request->params['controller'] . "_%",$settings);
echo "<SCRIPT LANGUAGE='JavaScript'>var Language = '" . $language . "';</SCRIPT>";
?>


<h3 class="page-title">
    <?= $strings["index_calendar"];?>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="index.html"><?= $strings["dashboard_dashboard"];?></a>
						<i class="fa fa-angle-right"></i>
					</li>
				
					<li>
						<a href="#"><?= $strings["index_calendar"];?></a>
					</li>
				</ul>
                <a href="javascript:window.print();" class="floatright btn btn-info"><?= $strings["dashboard_print"];?></a>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box green-meadow calendar">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-calendar"></i><?= $strings["index_tasks"];?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-md-3 col-sm-12">
									<!-- BEGIN DRAGGABLE EVENTS PORTLET-->
									<!--<h3 class="event-form-title">Draggable Events</h3>-->
									<!--<div id="external-events">
										<form class="inline-form">
											<input type="text" value="" class="form-control" placeholder="Event Title..." id="event_title"/><br/>
											<a href="javascript:;" id="event_add" class="btn default">
											Add Event </a>
										</form>
										<hr/>
										<div id="event_box">
										</div>
										<label for="drop-remove">
										<input type="checkbox" id="drop-remove"/>remove after drop </label>
										<hr class="visible-xs"/>
									</div>-->
                                    <a href="<?php echo $this->request->webroot;?>tasks/add" id="event_add" type="button" class="btn red">
											<?= $strings["tasks_addtask"]; ?></a>

<?php
if ($this->request->session()->read('Profile.super') && $this->request->session()->read('debug')) {
       echo '<br><a style="margin-top: 10px;" class="btn btn-warning" href="' . $this->request->webroot . 'profiles/cron/true">Run the CRON ' . $this->request->session()->read('timediff') . '</A>';
       echo '<br><a style="margin-top: 10px;" class="btn btn-warning" href="' . $this->request->webroot . 'profiles/cron?testemail">Send test email</A>';
}
?>



                                            <div class="portlet box green-meadow main_desc" style="display: none; margin-top:20px;">
                        						<div class="portlet-title">
                        							<div class="caption">
                        								Description
                        							</div>
                        						</div>
                                                
                        						<div class="portlet-body descprition">


                                                </div>
                                                 <div style="background: #fff; padding:10px;"><a href="" class="btn btn-danger delUrl" onclick="return confirm('<?= $strings["tasks_confirmdelete"]; ?>');">Delete</a></div>
                                                </div>
									<!-- END DRAGGABLE EVENTS PORTLET-->
								</div>
								<div class="col-md-9 col-sm-12">
									<div id="calendar" class="has-toolbar">
									</div>
								</div>
							</div>
							<!-- END CALENDAR PORTLET-->
						</div>
					</div>
				</div>
			</div>
<script src="<?= $this->request->webroot;?>assets/global/plugins/moment.min.js"></script>

<!-- calendar END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->

<?php
//<script src="<?= $this->request->webroot; assets/global/plugins/fullcalendar/fullcalendar.min.js"></script>
//<script src="<?php echo $this->request->webroot; assets/admin/pages/scripts/calendar.js"></script>

JSinclude($this, "assets/global/plugins/fullcalendar/fullcalendar.min.js");
JSinclude($this, "assets/admin/pages/scripts/calendar.js");

//load fullcalendar language
//echo "User time: " . $_SESSION['time']  . " Server timezone offset " . date("Z")/3600;
$lang = "";
switch ($language) {
    case "French":
        $lang = "fr";
        break;
    case "Debug":
        $lang="debug";
        break;
}
if($lang){
    echo '<script src="' .$this->request->webroot . 'assets/global/plugins/fullcalendar/lang/' . $lang . '.js"></script>';
}
?>

<script>
jQuery(document).ready(function() {       
  $('#calendar').fullCalendar({
    eventLimit: true,
    events: [
            <?php 
            foreach($events as $event){

            $dat = explode(" ",$event->date);
            $date = $dat[0];
            ?>
        {
            title: '<?= $event->title;?>',
            desc: '<?= $event->description;?>',
            delUrl: '<?= $this->request->webroot."tasks/delete/".$event->id;?>',
            start: '<?= str_replace(" ","T",$event->date);?>',
            url: '<?= $this->request->webroot;?>tasks/edit/<?php echo $event->id;?>',
            time: <?= $_SESSION['Config']['time'] ?>
        },
    <?php }?>
   
    
        // other events here
    ],
    eventClick: function(event) {
        if (event.url) {
            window.open(event.url);
            return false;
        }
    },
    eventMouseover: function(calEvent, jsEvent, view) {
            
        $('.main_desc').show();
        $('.descprition').html(calEvent.desc);
       
        $('.delUrl').attr('href',calEvent.delUrl);
        //alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
        //alert('View: ' + view.name);

        // change the border color just for fun
        $(this).css('border-color', 'red');

    },
    eventMouseout: function(calEvent, jsEvent, view) {
            
        //$('.main_desc').hide();
        

    },
    dayClick: function(date, jsEvent, view) {
            var d = date.format();
            window.location.href = "<?php echo $this->request->webroot;?>tasks/date/"+d;
            $(this).css('background-color', 'red');

    }
});

   //Calendar.init();
   
});
</script></body>
<?php
//$_SESSION['timediff'] ; // this is the timezone offset

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

function handletimezone($events, $offset){
    if ($offset <> 0) {
        foreach ($events as $event) {
            $event->date = offsettime($event->date, $offset);
        }
    }
}
?>