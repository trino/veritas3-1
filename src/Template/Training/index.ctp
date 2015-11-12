<style>
    @media print {
        .page-header {
            display: none;
        }

        .page-footer, .chat-form, .nav-tabs, .page-title, .page-bar, .theme-panel, .page-sidebar-wrapper, .more {
            display: none !important;
        }

        .portlet-body, .portlet-title {
            border-top: 1px solid #578EBE;
        }

        .tabbable-line {
            border: none !important;
        }

        a:link:after,
        a:visited:after {
            content: "" !important;
        }

        .actions {
            display: none
        }

        .paging_simple_numbers {
            display: none;
        }
    }
</style>

<?php
    $profileID = $this->Session->read('Profile.id');
    $sidebar = $this->requestAction("settings/all_settings/" . $profileID . "/sidebar");
    if ($sidebar->training == 0) {
        echo '<div class="alert alert-danger"><strong>Error!</strong> You don' . "'t have permission to view training</div>";
        return;
    }

    $settings = $Manager->get_settings();
    $sidebar = $this->requestAction("settings/get_side/" . $this->Session->read('Profile.id'));

    $QuizID = -1;
    if (!isset($_GET["action"]) AND isset($_GET["quizid"])) {
        $QuizID = $_GET["quizid"];
    }

    function clean($data) {
        if (is_object($data)) {
            $data->Description = clean($data->Description);
            $data->Name = clean($data->Name);
            $data->Attachments = clean($data->Attachments);
            $data->image = clean($data->image);
            return $data;
        }
        if (substr($data, 0, 1) == '"' && substr($data, -1) == '"') {
            $data = substr($data, 1, strlen($data) - 2);
        }
        $data = str_replace("\\r\\n", "<P>", htmlspecialchars(trim($data)));
        return $data;
    }

    function quizheader($QuizID, $id, $name, $image){
        if (($id == $QuizID) or ($QuizID == -1)){
        if (strlen($image) == 0) {
            $image = "training.png";
        }
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-steel">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-graduation-cap"></i>
                    <?php echo $name; ?>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-2" align="center">
                        <img style="max-height: 114px; max-width: 100%;" src="img/<?php if (strlen(trim($image)) == 0) {
                            echo "training.png";
                        } else {
                            echo $image;
                        } ?>">
                    </div>
                    <div class="col-md-10">

                        <?php
                            return true;
                            }
                            return false;
                            }

                            function quizmiddle($QuizID, $id) {
                                echo '</div></div><div class="row"><div class="col-md-2"></div>';
                                return $id == $QuizID;
                            }

                            function quizend($QuizID, $id, $canedit) {
                                if ($id != $QuizID) {
                                    printeditbuttons($id, $canedit);
                                }
                                echo '</div></div></div></div></div>';
                            }

                        ?>

                        <h3 class="page-title">
                            Training
                        </h3>

                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <i class="fa fa-home"></i>
                                    <a href="<?php echo $this->request->webroot; ?>">Dashboard</a>
                                    <i class="fa fa-angle-right"></i>
                                </li>
                                <li>
                                    <a href="training">Training</a>
                                </li>
                            </ul>
                            <div class="page-toolbar">

                            </div>
                            <a href="javascript:window.print();" class="floatright btn btn-info">Print</a>
                            <?php if ($canedit) {
                                echo '<a href="training/edit" class="floatright btn btn-primary btnspc">Create</a>';
                            } ?>
                        </div>


                        <?
                            function PrintResults($results, $user) {
                                if ($results['total'] > 0 && $results['missing'] < $results['total']) {//http://localhost/veritas3/img/profile/172647_974786.jpg
                                    //debug($user); <label class="control-label">Profile Type : </label>
                                    //echo '<div class="row"><div class="col-md-12"><div class="portlet box yellow"><div class="portlet-title">';
                                    // echo '<div class="caption"><i class="fa fa-graduation-cap"></i>Results for: ' . ucfirst($user->fname) . " " . ucfirst($user->lname) . " (" . ucfirst($user->username) . ") on ";
                                    // echo $results['datetaken'] . '</div></div><div class="portlet-body"><div class="row">';
                                    //echo '<div class="col-md-2"><img src="img/profile/' . $user->image . '" style="max-height: 100px; max-width: 100px;"></div>';
                                    echo '<div class="col-md-12" style="border:1px solid #888888;padding: 10px;margin-bottom:10px;">';


                                    PrintResult("Incorrect", $results['incorrect']);
                                    PrintResult("Missing", $results['missing']);
                                    PrintResult("Correct", $results['correct']);
                                    $score = $results['correct'] / $results['total'] * 100;
                                    PrintResult("Score", round($score, 2) . "%");
                                    if ($score >= 80) {
                                        PrintResult("Grade", "<font color='green'>Pass</A>");
                                    } else {
                                        PrintResult("Grade", "<font color='red'>Fail</A>");
                                    }
                                    echo '</font>';
                                    if ($score >= 80 && $results["hascert"]) {
                                        $Path = 'training/certificate?quizid=' . $_GET['quizid'] . '&userid=' . $user->id;
                                        echo '<CENTER><a class=" btn btn-danger" href="' . $Path . '">Click here to view the certificate</A></CENTER>';
                                    }
                                    //echo '</div></div>';
                                    echo "</div>";
                                }
                            }

                            function PrintResult($name, $number) {
                                echo '<div class="col-md-2"><label class="control-label">' . $name . ': </label><BR><DIV align="center"><H2>' . $number . '</H2></div></div>';
                            }


                            function isenrolled($enrolledquizzes, $canedit, $QuizID) {
                                if ($canedit) {
                                    return true;
                                }
                                foreach ($enrolledquizzes as $Quiz) {
                                    if ($Quiz->QuizID == $QuizID) {
                                        return true;
                                    }
                                }
                            }

                            $Q = "'";
                            $totalquizzes = 0;
                            foreach ($quizes as $quiz) {
                                $quiz = clean($quiz);
                                if (isenrolled($enrolledquizzes, $canedit, $quiz->ID)) {
                                    if (quizheader($QuizID, $quiz->ID, $quiz->Name, $quiz->image)) {
                                        $totalquizzes += 1;
                                        echo str_replace("\r\n", "<P>", $quiz->Description);
                                        if (isset($results)) {
                                            PrintResults($results, $user);
                                        }
                                        $attachments = "";
                                        if (quizmiddle($QuizID, $quiz->ID)) {
                                            if (!$hasusertakenquiz && strlen($quiz->Attachments) > 0 && $QuizID == $quiz->ID) {
                                                echo '<div class="col-md-10" align="left">';
                                                echo '<strong>Please go through each attachment in sequential order to view the quiz:</strong>';
                                                echo '</div><div class="col-md-2"></div>';
                                            }
                                            echo '<div class="col-md-5" align="left">';
                                            $attachments = explode(",", $quiz->Attachments);
                                            $attachmentJS = "";
                                            $id = 0;
                                            $checked = "";
                                            if ($hasusertakenquiz) {
                                                $checked = " checked";
                                            }
                                            foreach ($attachments as $attachment) {
                                                $attachment = trim($attachment);
                                                if (strlen($attachment) > 0) {
                                                    if (strlen($attachmentJS) > 0) {
                                                        $attachmentJS .= " && ";
                                                    }
                                                    $attachmentJS .= "document.getElementById('chk" . $id . "').checked";
                                                    $download = '" target="_blank"';
                                                    $name = strtolower(pathinfo($attachment, PATHINFO_EXTENSION));
                                                    $Names = array("mp4" => "Video", "pdf" => "Handout", "docx" => "Handout");
                                                    if (isset($Names[$name])) {
                                                        $name = $Names[$name];
                                                    }else{
                                                        $name = "Attachment";
                                                    }

                                                    if (!strpos($attachment, "/")) {
                                                        $attachment = "webroot/assets/global/" . $attachment;
                                                        $download .= ' download="' . basename($attachment) . '" TITLE="Internet Explorer users need to right-click, then click Save Target As"';
                                                    }

                                                    echo '<input type="checkbox" name="chk' . $id . '" id="chk' . $id . '" disabled' . $checked . '></input>' . ($id + 1) . ' <a href="' . $attachment . $download . ' class="btn btn-xs btn-warning chk' . $id . '" onclick="return check(';
                                                    echo "'chk" . $id . "', '" . $attachment . $Q . ');" title="Please follow these steps in sequential order before you can take the quiz"' . $checked . '>' . $name . '</a>';
                                                    $id += 1;
                                                }
                                            }
                                            echo '<input type="checkbox" id="quiz" disabled' . $checked . '><a class="btn btn-xs btn-info" href="training/quiz?quizid=' . $quiz->ID . '" onclick="return checkboxes();">Quiz</a></input>';
                                            echo '</div>';
                                            if ($canedit) {
                                                printeditbuttons($quiz->ID, $canedit);
                                            }
                                            //echo '<div class="col-md-2"></DIV><div class="col-md-10" align="left">Please follow these steps in sequential order before you can take the quiz</div>';
                                        } else {
                                            echo '<div class="col-md-5"></div>';
                                        }
                                        quizend($QuizID, $quiz->ID, $canedit);
                                    }
                                }
                            }

                            function printeditbuttons($QuizID, $canedit) {
                                $cols=12;
                                if (isset($_GET["quizid"])) { $cols = 5; }
                                echo '<div class="col-md-' . $cols . '" align="right">';
                                //echo '<a href="training/enroll?quizid=' . $quiz->ID . '" class="btn btn-warning btnspc"">Enroll</a>';
                                //echo '<a class="btn btn-info btnspc" href="training/quiz?quizid=' . $quiz->ID . '">View</a>';
                                echo '<a class="btn btn-info btn-xs btnspc" href="training?quizid=' . $QuizID . '">View</a>';
                                if ($canedit) {
                                    echo '<a class="btn btn-info btn-xs btnspc" href="training/quiz?quizid=' . $QuizID . '">Preview Quiz</a>';
                                    echo '<a href="training/enroll?quizid=' . $QuizID . '" class="btn btnspc btn-xs btn-warning">Enroll</a>';
                                    echo '<A href="training/users?quizid=' . $QuizID . '" class="btn btnspc btn-xs btn-info">Results</A>';
                                    echo '<a href="training/edit?quizid=' . $QuizID . '" class="btn btn-primary btn-xs btnspc">Edit</a>';
                                    echo '<a href="training?action=delete&quizid=' . $QuizID . '" onclick="return confirm(' . "'Are you sure you want to delete this quiz?'" . ');" class="btn btn-xs btn-danger">Delete</a>';
                                }
                                echo '</div>';
                            }

                            if ($totalquizzes == 0) {
                                echo "<h2>You are not enrolled in any courses</h2>";
                            }

                        ?>

                        <script language="JavaScript">
                            var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
                            var is_IE = ((navigator.appName == 'Microsoft Internet Explorer') || ((navigator.appName == 'Netscape') && (new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})").exec(navigator.userAgent) != null)));

                            function simulateClick(name) {
                                var evt = document.createEvent("MouseEvents");
                                evt.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
                                var cb = document.getElementById(name);
                                var canceled = !cb.dispatchEvent(evt);
                            }

                            function ffclick(name){
                                var cb = $('#' + name);
                                cb.prop('checked', true);
                            }

                            function check(name, filename) {
                                var element = document.getElementById(name);
                                element.disabled = false;
                                if (is_firefox){
                                    //element.setAttribute('checked', 'checked');
                                    ffclick(name);
                                } else {
                                    simulateClick(name);
                                    //element.click();
                                    //element.checked = true;
                                 }
                                element.disabled = true;
                                return true;//if it's IE, stop the link from working
                            }

                            function checkboxes() {
                                var Value = <?= $attachmentJS; ?>;
                                if(!Value){alert("Please go through each attachment in sequential order to view the quiz");}
                                return Value;
                            }

                            function checkboxesold(name1, name2) {
                                return document.getElementById(name1).checked && document.getElementById(name2).checked;
                            }
                        </script>