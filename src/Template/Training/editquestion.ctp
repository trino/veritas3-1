<?php
$settings = $Manager->get_settings();
$sidebar = $Manager->loadpermissions($Me, "sidebar");
include_once('subpages/api.php');

//debug($question);
//print_r($_POST);
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
    if (substr($data,0,1)== '"' && substr($data,-1) == '"'){$data = substr($data,1, strlen($data)-2);}
    $data = str_replace("\\r\\n", "\r\n", htmlspecialchars(trim($data))) ;
    return $data;
}

function getextension($path, $value=PATHINFO_EXTENSION){
    return strtolower(pathinfo($path, $value));
}
function printoption2($value, $selected="", $option = "", $dir="") {
    $tempstr = "";
    if(!$option){$option=$value;}
    if ($option == $selected or $value == $selected) {$tempstr = " selected";}
    if($dir){$tempstr.=' class="selectbg" style="background-image: url(' .$dir . "/" . $value . ');"';}
    echo '<OPTION VALUE="' . $value . '"' . $tempstr . ">" . $option . "</OPTION>";
}

if (isset($question)) {$question = clean($question,1);}

?>

    <h3 class="page-title">
        Edit Question <?= $_GET["QuestionID"] ?>
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
                <a href="<?php echo $this->request->webroot; ?>training/edit?quizid=<?= $_GET["quizid"]; ?>">Edit Quiz</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="">Edit Question</a>
            </li>
        </ul>
        <a href="javascript:window.print();" class="floatright btn btn-primary">Print</a>
        <?php if ($canedit && isset($question)) {
            echo '<a href="' . $this->request->webroot . 'training/editquestion?new=true&action=delete&QuestionID=' . $_GET["QuestionID"] . '&quizid=' . $_GET["quizid"] . '" onclick="return confirm(' . "'Are you sure you want to delete this question?'" . ');" class="floatright btn btn-danger btnspc">Delete</a>';
            $QuizID="&quizid=" . isset($quiz);
        }?>
        <a href="<?php echo $this->request->webroot; ?>training/edit?quizid=<?= $_GET["quizid"]; ?>" class="floatright btn btnspc btn-primary" onclick="return areyousure();">Back</a>
    </div>

    <form action="<?= $this->request->webroot; ?>training/editquestion?action=save&quizid=<?= $_GET["quizid"] ?>&new=false&QuestionID=<?= $_GET["QuestionID"] ?>" method="post">

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">Question :</label>
                <?= '<input name="QuizID" type="hidden" value="' . $_GET["quizid"] . '"><input name="QuestionID" type="hidden" value="' . $_GET["QuestionID"] . '">'; ?>
                <?= '<input name="new" type="hidden" value="' . $_GET["new"] . '">'; ?>
                <input name="Question" onchange="changed=true;" class="form-control required" value="<?php if (isset($question)) { echo $question->Question; } ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label">Image :</label>
                <SELECT NAME="Picture" ID="image" onchange="changed=true;" class="form-control" SELECTED="<?php if (isset($question)) { echo $question->Picture; } ?>">
                    <OPTION VALUE="">No image</OPTION>
                    <?php
                    //<!--input name="image" id="image" class="form-control" value="<?php if (isset($quiz)) { echo $quiz->image; } else {echo "training.png";} " /-->
                    $dir = getcwd() . "/img/training";
                    if(is_dir($dir)) {
                        $images = scandir($dir);
                        foreach ($images as $image) {
                            $ext = getextension($image);
                            if ($ext == "gif" || $ext == "png") {
                                printoption2($image, $question->Picture, "", $this->request->webroot . "img/training");
                            }
                        }
                    }
                    ?>
                </SELECT>
                <!--input name="Picture" onchange="changed=true;" class="form-control required" value="<?php if (isset($question)) { echo $question->Picture; } ?>" /-->
            </div>
        </div>

        <?php function printanswer($index, $value = "", $correctanswer=0){
            echo '<div class="col-md-6">';
            echo '<div class="form-group">';
                echo '<label class="control-label uniform-inline">Answer ' . chr(ord("a") + $index) . ':<BR>';
                echo '<span>';
                    if ($correctanswer == $index){ $checked = " checked";} else {$checked= " ANSWER="  . $correctanswer ;}
                    echo '<input onchange="changed=true;" type="radio" name="answer" value="' . $index . '"' . $checked . '>';
                    echo '<input onchange="changed=true;" type="text" id="Choice' . $index . '" name="Choice' . $index . '" value="' . $value;
                     //if (isset($question)) { echo $question->$answer; }
                echo '"></span></label></div></div>';
            }

        if (isset($question)) {
            printanswer(0, $question->Choice0, $question->Answer);
            printanswer(1, $question->Choice1, $question->Answer);
            printanswer(2, $question->Choice2, $question->Answer);
            printanswer(3, $question->Choice3, $question->Answer);
            printanswer(4, $question->Choice4, $question->Answer);
            printanswer(5, $question->Choice5, $question->Answer);
        } else {
            for ($temp=0; $temp<6; $temp+=1) {
                printanswer($temp);
            }
        }

?>

        <div class="col-md-2">
            <div class="form-group">
                <button type="submit" class="btn blue"><i class="fa fa-check"></i> Save Changes</button>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <A href="#" class="btn btn-primary" onclick="truefalse();">True/False</A>
            </div>
        </div>
    </form>
<script>
    var changed = false;
    function areyousure(){
        if (changed) { return confirm('Are you sure you want to exit without saving your changes?');}
        return true;
    }

    function truefalse(){
        changed=true;
        document.getElementById("Choice0").value="True";
        document.getElementById("Choice1").value="False";
    }
</script>