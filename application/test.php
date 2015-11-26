<?php
    $webroot = $_SERVER["REQUEST_URI"];
    $start = strpos($webroot, "/", 1) + 1;
    $webroot = substr($webroot, 0, $start) . "webroot/";
    if ( $_SERVER["SERVER_NAME"] != "localhost"){$webroot = str_replace("application/", "", $webroot);}

/*
<body onload="switchdatepickers()">
<SCRIPT>
	function switchdatepickers(){
		jQuery(".wpcf7-date").addClass("datepicker");
		jQuery(".wpcf7-date").removeClass("wpcf7-date");
		jQuery(".datepicker").removeClass("hasDatepicker");
	}

	language = 'English';
	jQuery(function () {
		jQuery(".datepicker").datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: '1980:2020',
			dateFormat: 'mm-dd-yy'
		});
	});
</SCRIPT>
 */
?>

    <script src="http://code.jquery.com/jquery-2.1.4.min.js" type="text/javascript"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>

<INPUT TYPE="TEXT" CLASS="wpcf7-date">

<SCRIPT>
    function switchdatepickers(){
        jQuery(".wpcf7-date").addClass("datepicker");
    }

    switchdatepickers();

    language = 'English';
    $(function () {
    $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '1980:2020',
            dateFormat: 'yy-mm-dd'
        });
    });
</SCRIPT>
