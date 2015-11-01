<?php
 if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/documents/recruiter_notes.php #INC129</span>";}
 ?>
<div class=" light recruiter_notes">
    <?php
    $desirednote = -1;
    if ($this->request->params['action'] == 'viewReport') {
        $pid = $order->profile->id;
    } else {
        $pid = $p->id;
    }
    if (isset($_GET["id"]) and isset($_GET["pid"])) {
        $pid = $_GET["pid"];
        $desirednote = $_GET["id"];
    }

    $notes = $this->requestAction('profiles/getNotes/' . $pid);
    ?>

    <div class="">
        <input type="hidden" id="rid" value="0"  />
        <textarea id="recruiter_notes" placeholder="<?= $strings["forms_addnotes"]; ?>..." rows="2" class="form-control"></textarea>
        <a href="javascript:void(0);" class="btn btn-success" id="add_recruiter" style="float:right;margin:5px 0;" ><?= $strings["forms_submit"]; ?></a>

    </div>
    <div class="">
        <div class="notes" style="">
            <?php
                if ($notes) {
                    foreach ($notes as $n) { ?>
                        <div class="item">
                            <div class="item-head">
                                <div class="item-details">
                                    
                                    <span class="item-name primary-link">
                                        <?php
                                            $r_info = $this->requestAction('profiles/getRecruiterById/' . $n->recruiter_id);
                                            echo $r_info->fname . ' ' . $r_info->mname . ' ' . $r_info->lname ?>
                                    </span>
                                    <span class="item-label"><?php $arr_cr = explode(',', $n->created); echo $arr_cr[0]; ?></span>
                                    
                                </div>

                            </div>
                            <div class="item-body">
                                <span id="desc<?php echo $n->id;?>"><?php echo $n->description; ?></span><br />
                                <a href="javascript:void(0);" class="btn btn-small btn-primary editnote" style="padding: 0 8px;" id="note_<?php echo $n->id;?>"><?= $strings["dashboard_edit"]; ?></a>
                                <a href="javascript:void(0);" class="btn btn-small btn-danger deletenote" style="padding: 0 8px;" id="dnote_<?= $n->id;?>" onclick="return deletenote(<?= $n->id . ", '" . ProcessVariables($language, $strings["dashboard_confirmdelete"], array("name" => ucfirst(h($n->description))));?>');" ><?= $strings["dashboard_delete"]; ?></a>
                                <br><br>
                            </div>

                        </div>
                    <?php
                    }
                }
            ?>


        </div>
    </div>
</div>

<script>
    function deletenote(ID, Message){
        if (confirm(Message)){
            $.ajax({
                url: '<?php echo $this->request->webroot;?>profiles/deleteNote/'+ID,
                success: function (response) {
                    $('#dnote_'+ID).parent().parent().remove();
                    alert('<?= addslashes($strings["forms_notedeleted"]); ?>');
                }
            });
        }
    }

    $(function () {
        $('.editnote').live('click',function(){
            var id_note = $(this).attr('id');
            id_note = id_note.replace('note_','');
            $('#rid').val(id_note);
            $('#recruiter_notes').val($('#desc'+id_note).html());
            
        });

        $('.recruiter_notes').slimScroll({
            height: '200px'
        });
        $('#add_recruiter').click(function () {
            if ($('#recruiter_notes').val() == '') {
                alert('<?= addslashes($strings["forms_notenotsaved"]); ?>');
                $('#recruiter_notes').focus();
            }
            else {
                $.ajax({
                    url: '<?php echo $this->request->webroot;?>profiles/saveNote/<?php echo $pid;?>/'+$('#rid').val() + '/<?= $language ?>',
                    data: 'description=' + $('#recruiter_notes').val(),
                    type: 'post',
                    success: function (response) {
                        if (response != 'error') {
                            if($('#rid').val()=='0'){
                                $('.notes').prepend(response);
                            } else {
                                $('#desc'+$('#rid').val()).html($('#recruiter_notes').val());
                                alert('<?= addslashes($strings["forms_notesaved"]); ?>');
                            }
                            $('#rid').val('0');
                            $('#recruiter_notes').val('');
                        }
                    }
                });
            }
        })
    });
</script>