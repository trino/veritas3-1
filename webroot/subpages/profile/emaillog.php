<STYLE>
    .btntop {
        display: inline-block;
        position: fixed;
        top: 50px;
        z-index: 10001;
        right: 10px;
    }
</STYLE>
<?php
    if ($this->request->session()->read('debug')) {
        echo "<span style ='color:red;'>subpages/profile/emaillog.php #INC???</span>";
    }
    if(file_exists("royslog.txt")) {
        if (isset($_GET["delete"])) {
            unlink("royslog.txt");
        }
        echo '<a HREF="' . $this->request->webroot . 'profiles/settings?includeonly=profile/emaillog.php&delete" onclick="return confirm(';
        echo "'Are you sure you want to delete the log file?'";
        echo ');" class="btn btn-danger btntop">Delete</a>';
    }
    echo '<PRE>';
    if (file_exists("royslog.txt")) {
        readfile("royslog.txt");
    } else {
        echo 'The log file is empty';
    }
    echo '</PRE>';
?>