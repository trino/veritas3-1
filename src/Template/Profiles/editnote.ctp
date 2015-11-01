<div class=" light recruiter_notes">
    <script>
        function editnote(id, text) {
            $.ajax({
                url: '<?php echo $this->request->webroot;?>Profiles/changenote',
                data: 'key=' + id + "&value=" + text,
                type: 'get',
                success: function (res) {
                    $('#clientTable').html(res);
                }
            });
        }
    </script>
<?php

$userid=$this->request->session()->read('Profile.id');

$desirednote = -1;
if (isset($_GET["id"]) and isset($_GET["pid"])) {
$pid = $_GET["pid"];
$desirednote = $_GET["id"];
}
$noteuserid = -1;
$notes = $this->requestAction('profiles/getNotes/' . $pid);

function getnote($notes, $id){
    foreach ($notes as $n) {
        if ($id == $n->id) { return $n; }
    }
}

function canuseredit($notes, $noteid, $userid, $profile){
    if ($noteid==-1) {return false;}
    $thenote = getnote($notes, $noteid);
    if ($userid == $thenote->recruiter_id){ return true; } //* is the user who posted the note *//
    if ($profile->profile_type == 1) { return true;} //* is an admin *//
    return false;
}

function usertype($profile){
    $profiletype = ['','Admin','Recruiter','External','Safety','Driver','Contact','Owner Operator','Owner Driver', 'Employee', 'Guest', 'Partner'];
    return $profiletype[$profile->profile_type];
}


//This is the sub that needs to be moved to the controller //
use Cake\ORM\TableRegistry;
function changenote($noteid, $text){
    //$setting = $this->Settings->get_permission($userid);

    $q = TableRegistry::get('recruiter_notes');
    $note = $q->find()->where(['id'=>$noteid])->first();
    if (strlen($text) == 0) {//Delete note
        return false;
        $query2 = $q->query();
        $query2->delete($noteid)->first();
    } else { //edit note text
        $arr = array("description" => $text);
        $query2 = $q->query();
        $query2->update()->set($arr)->where(['id' => $noteid])->execute();
    }
}
//debug($profile);
?>


    <div class="row">
        <div class="col-md-12">
            <div class="portlet box yellow-casablanca ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-shopping-cart"></i>
                        Edit Note
                    </div>
                </div>
                <div class="portlet-body">

        <div class="notes" style="">
            <?php
            if (isset($_GET["action"])) {
                foreach ($notes as $n) {
                    if ($desirednote == $n->id) {
                        $noteuserid = $n->recruiter_id;
                    }
                }

                echo $_GET["action"] . " note id " . $desirednote . " (user's id: " . $noteuserid . ")";
                if (isset($_GET["text"])) { echo " to " . $_GET["text"]; }
                echo "<BR> and don't forget the permissions check for the current user id: " . $userid;
                echo "<BR>Can edit: " . canuseredit($notes, $desirednote, $userid, $profile);
                echo "<BR>" . ucfirst($settings->profile) . " type: " . $profile->profile_type . " (" . usertype($profile) . ")";

                //changenote($noteid, $text)
                if (canuseredit($notes, $desirednote, $userid, $profile)) {
                    if ($_GET["action"] == "edit") {
                        changenote($desirednote, $_GET["text"]);
                        echo 'Note has been changed to "' . $_GET["text"] . '"';
                    } else {
                        //changenote($desirednote, "");
                        echo "Note has NOT been deleted";
                    }
                } else {
                    echo "You cannot edit notes";
                }
                echo "<P><A href='edit/" . $userid . "'>Return</A></P>";

            } elseif ($notes) {
                foreach ($notes as $n) {
                    if ($desirednote == $n->id){
                    ?>
                    <div class="item">
                        <div class="item-head">
                            <div class="item-details">
                                <a href="editnote?id=<?php echo $n->id . "&pid=" . $pid; ?>">
                                    <span class="item-name primary-link">
                                        <?php
                                        $r_info = $this->requestAction('profiles/getRecruiterById/' . $n->recruiter_id);
                                        echo $r_info->fname . ' ' . $r_info->mname . ' ' . $r_info->lname ?>
                                    </span>
                                    <span class="item-label"><?php $arr_cr = explode(',', $n->created); echo $arr_cr[0]; ?></span>
                                </a>
                            </div>

                        </div>
                        <div class="item-body">
                            <?php
                                if ($desirednote == -1 or !canuseredit($notes, $desirednote, $userid, $profile)) {
                                    echo $n->description;
                                    if ($desirednote > -1) {echo "<BR>You do not have the required permission to edit notes";}
                                } else {
                                    echo '<form action="editnote" method="get">';
                                    echo '<input type="hidden" name="id" value="' . $n->id . '">';
                                    echo '<input type="hidden" name="pid" value="' . $pid . '">';
                                    echo '<input type="hidden" name="action" value="edit">';
                                    echo "<textarea name='text'>" . $n->description . "</textarea><BR>";
                                    echo '<button type="submit" class="btn btn-primary">Edit</button></form>';
                                    echo '<div class="delete"><a href="editnote?id=' . $n->id . "&pid=" . $pid . '&action=delete" class="btn red" id="delete">Delete</a></div>';
                                }
                        ?><br><br>
                        </div>

                    </div>
                <?php
                }}
            }
            ?>


        </div>
    </div>
            </div>
        </div>
    </div>
</div>
