<?php
    $settings = $Manager->get_settings();
    $sidebar = $Manager->loadpermissions($Me, "sidebar");
    include_once('subpages/api.php');

    function array_sort($array, $key, $Ascending=true){
        $new_array = array();
        $sortable_array = array();
        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $key) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            if($Ascending) {
                asort($sortable_array);
            } else {
                arsort($sortable_array);
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    function clean($data, $datatype=0){
        if (is_object($data)){
            switch($datatype) {
                case 0:
                    $data->Description = clean($data->Description);
                    $data->Name = clean($data->Name);
                    $data->Attachments = clean($data->Attachments);
                    $data->image = clean($data->image);
                    return $data;
                    break;
                case 1:
                    $data->Question = clean($data->Question);
                    break;
            }
        }
        if (substr($data,0,1)== '"' && substr($data,-1) == '"'){$data = substr($data,1, strlen($data)-2);}
        $data = str_replace("\\r\\n", "\r\n", (trim($data))) ;
        return $data;
    }

    $title = "Courses";
    if (isset($_GET["quizid"])) {
        $title = "Course Results";
        if(isset($_GET["clientid"])){
            $client = getIterator($clients, "id", $_GET["clientid"]);
            if($client){
                $title .= " for " . $client->company_name;
            }
        }
    }
    $cols=1;

    function makeurl($Key, $Value, $Sort=false){
        $GetCopy = $_GET;
        if(isset($GetCopy[$Key]) && $GetCopy[$Key] == $Value){
            $Sorting = "ASC";
            if(isset($GetCopy["order"])){
                $Sorting = $GetCopy["order"];
            }
            if($Sorting == "ASC"){
                $Sorting = "DESC";
            } else {
                $Sorting = "ASC";
            }
            $GetCopy["order"] = $Sorting;
        } else {
            $GetCopy[$Key] = $Value;
        }
        return '?' . http_build_query($GetCopy);
    }
?>


<h3 class="page-title">
Users
</h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="<?= $this->request->webroot; ?>">Dashboard</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="<?= $this->request->webroot; ?>training">Training</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <?php if (isset($_GET["quizid"])) { ?>
            <li>
                <a href="<?= $this->request->webroot; ?>training/edit?quizid=<?= $_GET["quizid"]?>">Edit Quiz</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <?php } ?>
            <li>
                <a href="">Users</a>
            </li>
        </ul>
        <a href="javascript:window.print();" class="floatright btn btn-primary">Print</a>
        <?php
            if(isset($_GET["quizid"]) && count($_GET) > 1) {
                echo '<A HREF="' . $this->request->webroot . 'training/users?quizid=' . $_GET["quizid"] . '" CLASS="floatright btn btn-primary btnspc">Clear Search</A>';
            }
        ?>
</div>

<?php if(isset($sitenames) && isset($_GET["quizid"])){ ?>
    <div class="form-actions top chat-form" style="margin-top: 0px;margin-bottom: 10px;padding-bottom: 5px;padding-top: 5px;">
        <div class="btn-set pull-right">
            <form action="<?= $this->request->webroot; ?>training/users" method="get">
                <?php
                    echo '<INPUT TYPE="HIDDEN" NAME="quizid" VALUE="' . $_GET["quizid"] . '">';
                    echo '<select class="form-control input-inline" style="" name="sitename"><OPTION VALUE="">Site Name</OPTION>';
                    foreach($sitenames as $sitename){
                        if($sitename){
                            echo '<OPTION';
                            if(isset($_GET["sitename"]) && $_GET["sitename"] == $sitename){ echo ' SELECTED';}
                            echo '>' . $sitename. '</OPTION>';
                        }
                    }
                    echo '</SELECT>';

                    echo '<select class="form-control input-inline" style="" name="asapdivision"><OPTION VALUE="">Division</OPTION>';
                    foreach($asapdivisions as $asapdivision){
                        if($asapdivision){
                            echo '<OPTION';
                            if(isset($_GET["asapdivision"]) && $_GET["asapdivision"] == $asapdivision){ echo ' SELECTED';}
                            echo '>' . $asapdivision. '</OPTION>';
                        }
                    }
                    echo '</SELECT>';
                ?>
                <button type="submit" class="btn btn-primary input-inline">Search</button>
            </form>
        </div>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-steel">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-graduation-cap"></i><?= $title?></div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="toast" style="color: rgb(255,0,0);"></div>
                        <div class="table-scrollable">
                            <table class="table <?= $TABLEMODE; ?> table-striped table-bordered table-hover dataTable no-footer">
                                <thead>
                                <?php if (isset($users)) { ?>
                                <tr>
                                    <th><A HREF="<?= makeurl("sortby", "id", true); ?>">ID</A></th>
                                    <th><A HREF="<?= makeurl("sortby", "fname", true); ?>">First</A>/<A HREF="<?= makeurl("sortby", "lname", true); ?>">Last</A> Name</th>
                                    <TH><A HREF="<?= makeurl("sortby", "username", true); ?>">Username</A></TH>
                                    <TH><?= $settings->client; ?>(s)</TH>
                                    <TH>Score</TH>
                                    <TH>Actions</TH>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                            function printuser($user, $webroot, $clients){
                                                if (!is_numeric($user->Profiles['id'])) {return false;}
                                                if(isset($_GET["clientid"])){
                                                    if(!$user->Clients || !in_array($_GET["clientid"], $user->Clients)){return false;}
                                                }

                                                echo '<TR><TD>' . $user->Profiles['id'] . '</TD><TD>' . ucfirst($user->Profiles['fname']) . ' ' . ucfirst($user->Profiles['lname']) . '</TD><TD>';
                                                echo '<A HREF="' . $webroot . 'profiles/edit/' . $user->Profiles['id'] . '">' . ucfirst($user->Profiles['username']) . '</A></TD><TD>';
                                                if($user->Clients) {
                                                    foreach ($user->Clients as $key => $client) {
                                                        $client = getIterator($clients, "id", $client);
                                                        if ($client) {
                                                            $user->Clients[$key] = '<A HREF="' . makeurl("clientid", $client->id) . '">' . $client->company_name . '</A>';
                                                        } else {
                                                            unset($user->Clients[$key]);
                                                        }
                                                    }
                                                    echo implode(", ", $user->Clients);
                                                }
                                                echo '</TD><TD>';
                                                return true;
                                            }

                                            $total=0;
                                            $usercount=0;
                                            $nottakenyet="[Not taken]";
                                            foreach ($users as $user) {//http://localhost/veritas3/profiles/edit/120
                                                foreach($users2 as $user2){
                                                    if ($user2->UserID == $user->UserID){
                                                        $user2->profile = true;
                                                    }
                                                }

                                                if (printuser($user, $this->request->webroot, $clients)) {
                                                    if (strlen($user->profile['questions']) == 0) {
                                                        echo $nottakenyet . '</TD><TD>';
                                                        echo '<A onclick="enroll(event, ' . $_GET["quizid"] . ', ' . $user->UserID . ');" class="' . btnclass("btn-primary", "yellow") . '">Unenroll</A>';
                                                    } else {
                                                        $usercount += 1;
                                                        $total += $user->profile['percent'];
                                                        $score = round($user->profile['percent'], 2);
                                                        echo $user->profile['correct'] . '/' . $user->profile['questions'] . ' (';
                                                        if ($score < $user->profile['pass']) {
                                                            echo "<font color='red'>";
                                                        } else {
                                                            echo '<font color="green">';
                                                        }
                                                        echo $score . '%</font>)' . '</TD><TD NOWRAP><A HREF="' . $this->request->webroot . 'training/quiz?quizid=' . $_GET['quizid'] . '&userid=';
                                                        echo $user->UserID . '" class="' . btnclass("primary", "blue") . '">View Answers</A>';
                                                        if ($score >= 80) {
                                                            echo '<a href="' . $this->request->webroot . 'training/certificate?quizid=' . $_GET['quizid'] . '&userid=' . $user->UserID . '" class="' . btnclass("danger", "yellow") . '">Certificate</A> ';
                                                        } else {
                                                            echo '<A HREF="' . $this->request->webroot . 'training/users?action=deleteanswers&quizid=' . $_GET['quizid'] . '&userid=';
                                                            echo $user->UserID . '" class="' . btnclass("danger", "red") . '" onclick="return confirm(' . "'Are you sure you want to delete " . ucfirst($user->Profiles['username']) . "\'s answers?'" . ');" >Delete Answers</A>';
                                                        }
                                                    }
                                                    echo '</TD></TR>';
                                                }
                                            }

                                            $ClientProfiles = explode(",", $ClientProfiles);
                                            foreach($users2 as $user) {
                                                if (!$user->profile && in_array($user->UserID, $ClientProfiles)) {
                                                    if (printuser($user, $this->request->webroot, $clients)) {
                                                        echo $nottakenyet . '</TD><TD>';
                                                        echo '<A onclick="enroll(event, ' . $_GET["quizid"] . ', ' . $user->UserID . ');" class="' . btnclass("btn-primary", "yellow") . '">Unenroll</A>';
                                                    }
                                                }
                                            }

                                            if ($usercount==0) {
                                                echo '<TR><TD colspan="' . (5+$cols) . '" align="center">No one has taken this course yet</TD></TR>';
                                            } else {
                                                echo '<TR><TD colspan="' . (3+$cols) . '" align="right">Average:</TD><TD>' . round($total/$usercount,2) . "%</TD><TD></TD></TR>";
                                            }
                                        } else {
                                    ?>
                                    <TR><TH width="20">ID</TH><TH width="50">Image</TH><TH>Name</TH><TH width="50">Applicants</TH></TR>
                                    </thead>
                                    <tbody>
                                    <?php
                                        if(isset($quizes)) {
                                            foreach ($quizes as $quiz) {
                                                //debug($quiz); Name image
                                                $quiz = clean($quiz);
                                                echo "<TR><TD align='center'>" . $quiz->ID . '</TD><TD align="center"><img style="max-height:50px;" src="../img/';
                                                if (strlen(trim($quiz->image)) == 0) {
                                                    echo "training.png";
                                                } else {
                                                    echo $quiz->image;
                                                }
                                                echo '"></TD><TD><A HREF="?quizid=' . $quiz->ID . '">' . $quiz->Name . "</A></TD><TD align='center'>" . $quiz->completed . '/' . $quiz->applicants . "</TD></TR>";
                                            }
                                        } else {
                                            echo '<TR><TD colspan="100" align="center">You can not view course results</TD></TR>';
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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

    function enroll(event, QuizID, UserID){
        var element = event.target;
        element.setAttribute("disabled", "true");
        var OriginalText = element.innerHTML;
        element.innerHTML='<IMG SRC="<?= $this->request->webroot;?>webroot/assets/global/img/loading-spinner-blue.gif">';
        $.ajax({
            url: "<?= $this->request->webroot;?>training/enroll",
            type: "get",
            dataType: "HTML",
            data: "myid=<?= $Me; ?>&userid=" + UserID + "&quizid=" + QuizID,
            success: function (msg) {
                toast(msg, true);
                if (OriginalText == "Enroll"){
                    element.innerHTML = "Unenroll";
                } else {
                    element.innerHTML = "Enroll";
                }
                element.removeAttribute("disabled");
            },
            error: function(msg){
                toast("An error occurred.", true);
                element.innerHTML = OriginalText;
                element.removeAttribute("disabled");
            }
        })
    }
</SCRIPT>