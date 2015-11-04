<?php
$settings = $this->requestAction('settings/get_settings');
$sidebar = $this->requestAction("settings/get_side/" . $this->Session->read('Profile.id'));
$pageit=false;
include_once('subpages/api.php');

function trunc($text, $digits, $append = ""){
    if (strlen($text)<$digits) { return $text; }
    return substr($text,0,$digits) . $append;
}
$QuizID="";

function getextension($path, $value=PATHINFO_EXTENSION){
    return strtolower(pathinfo($path, $value));
}
function printoption($value, $selected="", $option = "", $dir="") {
    $tempstr = "";
    if(!$option){$option=$value;}
    if ($option == $selected or $value == $selected) {$tempstr = " selected";}
    if($dir){$tempstr.=' class="selectbg" style="background-image: url(' .$dir . "/" . $value . ');"';}
    echo '<OPTION VALUE="' . $value . '"' . $tempstr . ">" . $option . "</OPTION>";
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
if (isset($quiz)){
    $quiz=clean($quiz);
}
?>
<STYLE>
    .selectbg{
        background-size: 80px 100%;
        background-position: right top;
        background-repeat: no-repeat;
    }

    .truncate {
        white-space: nowrap; text-overflow:ellipsis; overflow: hidden; max-width:1px;
    }
</STYLE>

    <h3 class="page-title">
        Edit Quiz
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
                <a href="">Edit Quiz</a>
            </li>
        </ul>
        <a href="javascript:window.print();" class="floatright btn btn-info">Print</a>
        <?php if ($canedit && isset($quiz)) {
            if (!isset($_GET["export"])){ echo '<a href="' . $this->request->webroot . 'training/edit?export&quizid=' . $quiz->ID . '" class="floatright btn btn-primary btnspc">Export</a>';}
            echo '<a href="' . $this->request->webroot . 'training?action=delete&quizid=' . $quiz->ID . '" onclick="return confirm(' . "'Are you sure you want to delete this quiz?'" . ');" class="floatright btn btn-danger btnspc">Delete</a>';
            $QuizID="&quizid=" . $_GET['quizid'];
        }?>
    </div>

<?php if($canedit){ ?>

<form action="<?= $this->request->webroot; ?>training/edit?action=save<?= $QuizID ?>" method="post">

<div class="col-md-6">
    <div class="form-group">
        <label class="control-label">Quiz Name:</label>
            <?php if (isset($_GET["quizid"])){ echo '<input name="ID" type="hidden" value="' . $_GET["quizid"] . '">'; } ?>
            <input name="Name" class="form-control" required value="<?php if (isset($quiz)) { echo $quiz->Name; } ?>" />
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label class="control-label" TITLE="webroot\img">Image:</label>
        <SELECT NAME="image" ID="image" class="form-control" SELECTED="<?= $quiz->image; ?>">
        <?php
            //<!--input name="image" id="image" class="form-control" value="<?php if (isset($quiz)) { echo $quiz->image; } else {echo "training.png";} " /-->
            $dir = getcwd() . "/img";
            $images = scandir($dir);
            foreach($images as $image){
                $ext = getextension($image);
                if($ext=="gif" || $ext == "png") {
                    printoption($image, $quiz->image, "", $this->request->webroot . "img");
                }
            }
        ?>
        </SELECT>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label class="control-label" TITLE="webroot\assets\global">Attachments:</label><BR>
        <small>Separate your attachments with a comma</small>
        <textarea name="Attachments" class="form-control" rows="9"><?php if (isset($quiz)) { echo $quiz->Attachments; } ?></textarea>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label class="control-label">Description:</label>
        <textarea name="Description" class="form-control" rows="10" required><?php if (isset($quiz)) { echo $quiz->Description; } ?></textarea>
    </div>
</div>


<div class="col-md-2">
    <div class="form-group">
        <label class="control-label">Pass:</label>
        <input type="number" min="10" max="100" name="pass" size="3" value="<?php if (isset($quiz)) { echo $quiz->pass; } else { echo 80;} ?>">
    </div>
</div>

<div class="col-md-2">
    <div class="form-group">
        <label class="control-label">Certificate:</label>
        <LABEL><input type="radio" name="hascert" value="1" <?php if (isset($quiz) && $quiz->hascert) { echo "CHECKED"; } ?>>Yes </LABEL>
        <LABEL><input type="radio" name="hascert" value="0" <?php if (isset($quiz) && !$quiz->hascert) { echo "CHECKED"; } ?>>No </LABEL>
    </div>
</div>

<div class="col-md-2">
    <div class="form-group">
        <button type="submit" class="btn blue"><i class="fa fa-check"></i> Save Changes</button>
    </div>
</div>

    <?php if (isset($_GET["quizid"])){ ?>
    <div class="col-md-6" align="right">
        <div class="form-group">
            <A href="<?= $this->request->webroot ?>training/users?quizid=<?= $_GET["quizid"] ?>" class="btn btn-info">Results</A>
            <A href="<?= $this->request->webroot ?>training/enroll?quizid=<?= $_GET["quizid"] ?>" class="btn btn-warning">Enroll</A>
            <a href="<?= $this->request->webroot ?>training/quiz?quizid=<?= $_GET["quizid"] ?>" class="btn btn-info">Preview</a>
            <a href="<?= $this->request->webroot ?>training/quiz?quizid=<?= $_GET["quizid"] ?>&debug" class="btn btn-danger">Preview with answers</a>
        </div>
    </div>
    <?php } ?>
</form>
<?php if (isset($questions)) { ?>
<div class="col-md-12">
    <div class="form-group">
        <label class="control-label">Save before editing any questions</label>
    </DIV>
</DIV>


<?php if ($pageit){ ?>
    <div class="row">
    <div class="col-md-12">
    <div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-graduation-cap"></i>
            Questions
        </div>
    </div>


    <div class="portlet-body form">
        <div class="form-body">
<div class="table-scrollable">
    <?php } ?>

            <table class="table table-condensed  table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr>
                    <th style="width: 10px;">QID</th>
                    <th style="width: 10px;">Index</th>
                    <th>Question</th>
                    <TH style="width: 110px;">Actions</TH>
                    <!--TH>Answer 1</TH>
                    <TH>Answer 2</TH>
                    <TH>Answer 3</TH>
                    <TH>Answer 4</TH-->
                </tr>
                </thead>
                <tbody>
                    <?php
                    function newQuestion($ID){
                        echo '<TR><TD>New</TD><TD>' . $ID . '</TD><TD></TD><TD><a href="editquestion?QuestionID=' . $ID . '&new=true&quizid=' . $_GET["quizid"] . '" class="' . btnclass("EDIT") . '">Create</a></TD></TR>';
                    }
                    function answer($correctanswer, $id, $answer){
                        echo '<TD>';
                        if ($correctanswer==$id){ echo '<B>';}
                        echo trunc($answer, 25, "...");
                        if ($correctanswer==$id){ echo '</B>';}
                        echo '</TD>';
                    }

                    $index=-1;
                    foreach($questions as $question) {
                        clean($question, 1);
                        if ($question->QuizID == $_GET["quizid"]) {
                            for ($temp = $index + 1; $temp < $question->QuestionID; $temp += 1) {
                                newQuestion($temp);
                            }
                            //if ($question->QuestionID > $index+1){ newQuestion($index+1); }

                            echo '<TR><TD>' . $question->ID . '</TD>';
                            echo '<TD>' . $question->QuestionID . '</TD>';
                            echo '<TD class="truncate">' . $question->Question . '</TD>';// trunc($question->Question, 75, "...")

                            if (isset($_GET["answers"])){
                                echo "<TD>" . $question->Answer . "</TD>";
                            } else {
                                echo '<TD><a href="editquestion?QuestionID=' . $question->QuestionID . '&new=false&quizid=' . $_GET["quizid"] . '" class="' . btnclass("EDIT") . '">Edit</a>';
                                echo '<a href="edit?action=delete&quizid=' . $_GET["quizid"] . '&QuestionID=' . $question->QuestionID . '" class="' . btnclass("DELETE") . '"  onclick="return confirm(' . "'Are you sure you want to delete question" . $question->QuestionID . " ?'" . ');">Delete</a></TD>';
                                //answer($question->Answer, 0, $question->Choice0);
                                //answer($question->Answer, 1, $question->Choice1);
                                //answer($question->Answer, 2, $question->Choice2);
                                //answer($question->Answer, 3, $question->Choice3);
                            }
                            echo '</TR>';
                            $index = $question->QuestionID;
                        }
                    }
                    newQuestion($index+1);
                    ?>
                </tbody>
            </table>

    <?php if ($pageit){//don't pageit ?>
    <div class="form-actions" style="height:75px;">
                <div class="row">
                    <div class="col-md-12" align="right">
                        <div id="sample_2_paginate" class="dataTables_paginate paging_simple_numbers" align="right"
                             style="margin-top:-10px;">
                            <ul class="pagination sorting">
                                <?= $this->Paginator->prev('< ' . __('previous')); ?>
                                <?= $this->Paginator->numbers(); ?>
                                <?= $this->Paginator->next(__('next') . ' >'); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    </div>
    </div>
    </div>
<?php }}} else {
    echo "You do not have permission to edit courses";
}
if (isset($_GET["export"])){
    function a($text){
        return addslashes($text);
    }
    $ID = $_GET["quizid"];
    if($_GET["export"]){$ID = $_GET["export"];}

    echo "SQL:<BR><TEXTAREA STYLE='WIDTH: 100%; HEIGHT: 500px;'>";
        echo "INSERT INTO `training_list` (`ID`, `Name`, `Description`, `Attachments`, `image`) VALUES ('" . $ID . "', '" . addslashes($quiz->Name) . "', '" . addslashes($quiz->Description) . "', '" . addslashes($quiz->Attachments) . "', '" . addslashes($quiz->image) . "');";
        echo "\r\nINSERT INTO `training_quiz` (`QuizID`, `QuestionID`, `Answer`, `Choice0`, `Choice1`, `Choice2`, `Choice3`, `Picture`, `Question`, `Choice4`, `Choice5`) VALUES ";
        $index=0;
        $count = iterator_count($questions);
        foreach($questions as $q) {
            $index++;
            echo "('" . $ID . "', '" . a($q->QuestionID) . "', '" . a($q->Answer) . "', '" . a($q->Choice0) . "', '" . a($q->Choice1) . "', '" . a($q->Choice2) . "', '" . a($q->Choice3) . "', '" . a($q->Picture) . "', '" . a($q->Question) . "', '" . a($q->Choice4) . "', '" . a($q->Choice5) . "')";
            if ($index < $count) { echo ",";}
        }
        echo ";";
    echo "</TEXTAREA>";
}
?>