<?php $settings = $Manager->get_settings(); ?>
<?php $sidebar = $Manager->loadpermissions($Me, "sidebar"); ?>
<h3 class="page-title">
    Quiz
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo $this->request->webroot; ?>">Dashboard</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="<?php echo $this->request->webroot; ?>training">Training</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="">Quiz</a>
        </li>
    </ul>
    <div class="page-toolbar">
        <!--div id="dashboard-report-range" style="padding-bottom: 6px;" class="pull-right tooltips btn btn-fit-height grey-salt" data-placement="top" data-original-title="Change dashboard date range">
            <i class="icon-calendar"></i>&nbsp;
            <span class="thin uppercase visible-lg-inline-block">&nbsp;</span>&nbsp;
            <i class="fa fa-angle-down"></i>
        </div-->
    </div>
    <a href="javascript:window.print();" class="floatright btn btn-primary">Print</a>
</div>

<?php
    $question = 0;
    $QuizID = $_GET["quizid"];
    function clean($data, $datatype = 0) {
        if (is_object($data)) {
            switch ($datatype) {
                case 0:
                    $data->Description = clean($data->Description);
                    $data->Name = clean($data->Name);
                    $data->Attachments = clean($data->Attachments);
                    $data->image = clean($data->image);
                    return $data;
                    break;
                case 1:
                    $data->Question = clean($data->Question);
                    $data->Picture = clean($data->Picture);
                    $data->Choice0 = clean($data->Choice0);
                    $data->Choice1 = clean($data->Choice1);
                    $data->Choice2 = clean($data->Choice2);
                    $data->Choice3 = clean($data->Choice3);
                    $data->Choice4 = clean($data->Choice4);
                    $data->Choice5 = clean($data->Choice5);
                    return $data;
                    break;
            }
        }
        if (substr($data, 0, 1) == '"' && substr($data, -1) == '"') {
            $data = substr($data, 1, strlen($data) - 2);
        }
        $data = str_replace("\\r\\n", "\r\n", trim($data));
        return $data;
    }

    function question($section) {
        global $question;
        switch ($section) {
            case "0":
                echo '<div class="row"><div class="col-md-12"><div class="portlet box blue-steel"><div class="portlet-title">';
                echo '<div class="caption"><i class="fa fa-graduation-cap"></i>Question ' . $question . '</div></div><div class="portlet-body">';
                echo '<div class="row"><div class="col-md-2">';
                break;
            case "1":
                echo '</div><div class="col-md-10">';
                break;
            case "2":
                echo '</div></div></div></div></div></div>';
                break;
        }
    }

    function answers($QuizID, $QuestionID, $text, $answers, $Index = 0, $usersanswer, $correctanswer, $GiveAnswer) {
        $disabled = "";
        $selected = -1;
        $iscorrect = false;
        if (is_object($usersanswer)) {
            $disabled = " disabled";
            $selected = $usersanswer->Answer;
        }
        if($GiveAnswer){
            $selected = $correctanswer;
        }
        $Qold = $QuestionID;
        $QuestionID = $QuizID . ':' . $Index;
        echo '<input type="hidden" name="' . $QuestionID . ':sequencecheck" value="' . $Qold . '" />';
        echo '<div class="qtext"><p>' . $text . '</p></div>';
        echo '<div class="ablock"><div class="prompt">Select one:';
        if ($correctanswer == -1) {
            echo " <font color='red'><B>Incomplete</B></font>";
        }
        echo '</div><div class="answer"><TABLE>';
        for ($temp = 0; $temp < count($answers); $temp += 1) {
            if (strlen(trim($answers[$temp])) > 0) {
                echo '<TR><TD valign="top"><div class="r' . $temp . '" nowrapstyle="white-space: nowrap;">';
                echo '<input type="radio" name="' . $QuestionID . '_answer" value="' . $temp . '" id="' . $QuestionID . ":" . $temp . '" required' . $disabled;
                if ($selected == $temp) {
                    echo " checked";
                }
                echo '/></TD><TD><label for="' . $QuestionID . ":" . $temp . '">' . chr($temp + ord("a")) . ". " . $answers[$temp];
                if (is_object($usersanswer) && $selected == $temp) {
                    if ($correctanswer == $temp) {
                        echo " <font color='green'><B>Correct!</B></font>";
                        $iscorrect = true;
                    } else {
                        echo " <font color='red'><B>Incorrect</B></font>";
                    }
                }
                echo '</label></div></TD></TR>';
            }
        }
        echo '</TABLE></DIV></DIV>';
        return $iscorrect;
    }

    function questionheader($QuizID, $QuestionID, $markedOutOf, $Index = 0, $usersanswer, $picture, $webroot) {
        $flagged = "";
        $answered = "Not yet answered";
        if (is_object($usersanswer)) {
            if ($usersanswer->flagged) {
                $flagged = " checked";
            }
            $flagged .= " disabled";
            $answered = "Answered";
        }
        $QuestionID = $QuizID . ':' . $Index;
        echo '<div class="state">' . $answered . '</div><div class="grade">Marked out of ' . $markedOutOf . '</div>';
        if($picture){
            echo '<IMG SRC="' . $webroot . 'img/training/' . $picture . '" style="max-width: 138px;">';
        }
    }

    function preprocess($usersanswer, $correctanswer) {
        $correct = "missing";
        if (is_object($usersanswer)) {
            $correct = "incorrect";
            if ($usersanswer->Answer == -1) {
                $correct = "missing";
            } elseif ($correctanswer == $usersanswer->Answer) {
                $correct = "correct";
            }
        }
        return $correct;
    }

    function FullQuestion($QuizID, $text, $answers, $index = 0, $markedOutOf = "1.00", $usersanswer, $correctanswer, $picture, $webroot, $GiveAnswer) {
        global $question;
        $question += 1;
        $correct = "incorrect";
        if (is_object($usersanswer)) {
            if ($usersanswer->Answer == -1) {
                $correct == "missing";
            }
        }
        question(0);
        questionheader($QuizID, $question, $markedOutOf, $index, $usersanswer, $picture, $webroot);
        question(1);
        if (answers($QuizID, $question, $text, $answers, $index, $usersanswer, $correctanswer, $GiveAnswer)) {
            $correct = "correct";
        }
        question(2);
        return $correct;
    }

    if (is_object($useranswers)) {
        if ($results["missing"] < $results["total"]) {
            PrintResults($results, $user);
        }
    }

    function PrintResults($results, $user) {
        if ($results['total'] > 0) {//http://localhost/veritas3/img/profile/172647_974786.jpg
            //debug($user); <label class="control-label">Profile Type : </label>
            echo '<div class="row"><div class="col-md-12"><div class="portlet box yellow"><div class="portlet-title">';
            echo '<div class="caption"><i class="fa fa-graduation-cap"></i>Results for: ' . ucfirst($user->fname) . " " . ucfirst($user->lname) . " (" . ucfirst($user->username) . ") on ";
            echo $results['datetaken'] . '</div></div><div class="portlet-body"><div class="row">';
            echo '<div class="col-md-2"><img src="../img/profile/' . $user->image . '" style="max-height: 100px; max-width: 100px;"></div>';
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
            echo '</font></div>';
            if ($score >= 80 && $results["hascert"]) {
                //     echo $this->request->webroot;
                //   echo $this->request->webroot; die();
          //      $link232 = $this->request->webroot . 'training/certificate?quizid=' . $_GET['quizid'] . '&userid=' . $user->id;
                $Path = 'certificate?quizid=' . $_GET['quizid'] . '&userid=' . $user->id;
                echo '<CENTER><a class=" btn btn-primary" href="' . $Path . '">Click here to view the certificate</A></CENTER>';

            }
            echo '</div></div>';
        }
    }

    function usersanswer($useranswers, $questionid){
        if (isset($useranswers)) {
            foreach ($useranswers as $answers) {
                if ($answers->QuestionID == $questionid) {return $answers;}
            }
        }
    }

    function PrintResult($name, $number){
        echo '<div class="col-md-2"><label class="control-label">' . $name . ': </label><BR><DIV align="center"><H2>' . $number . '</H2></div></div>';
    }

    $results = array("incorrect" => 0, "missing" => 0, "correct" => 0, "total" => 0);
    echo '<form action="quiz?quizid=' . $_GET["quizid"] . '" method="post" enctype="multipart/form-data" accept-charset="utf-8" id="responseform">';

    $GiveAnswer = $canedit && isset($_GET["debug"]);
    foreach ($questions as $question) {
        $question = clean($question, 1);
        $answer = usersanswer($useranswers, $question->QuestionID);
        $result = FullQuestion($QuizID, $question->Question, array($question->Choice0, $question->Choice1, $question->Choice2, $question->Choice3, $question->Choice4, $question->Choice5), $question->QuestionID, "1.00", $answer, $question->Answer, $question->Picture, $this->request->webroot, $GiveAnswer);
        //$results[$result] += 1;
        //$results["total"] += 1;
    }
    if (is_object($answer)) {
        //PrintResults($results, $user);
    } else {
        echo '<DIV align="center"><button type="submit" class="btn btn-primary" style="margin-bottom: 15px;" onclick="return confirm(' . "'Are you sure you are done?'" . ');"><i class="fa fa-check"></i> Save</button></DIV>';
    }
    echo "</form>";
    //}
?>

</div></div></div></div></div>