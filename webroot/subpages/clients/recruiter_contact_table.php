<?php
     if($this->request->session()->read('debug')) {
         echo "<span style ='color:red;'>subpages/clients/recruiter_contact_table.php #INC149</span>";
     }
    $profiles = $this->requestAction('/profiles/getProfile');
    $contact = $this->requestAction('/profiles/getContact');
    //include("subpages/profileslisting.php");
    $viewmode = isset($_GET["view"]);
    $pType = $this->requestAction('/profiles/getProfileTypes/' . $language);// ['','Admin','Recruiter','External','Safety','Driver','Contact'];
    if (!$viewmode){
?>


<div class="col-md-12" style=" float: right;margin-bottom: 10px;padding-right: 0;">
    <!--LABEL CLASS="btnspc"><INPUT TYPE="checkbox" ID="visibleprofiles" ONCLICK="visibleprofiles();"> Visible to non-employees</LABEL-->
    <a href="<?php echo $this->request->webroot;?>profiles/add" class="btn btn-primary btnspc" style="float: right;"><?= $strings["infoorder_addprofile"]; ?></a>
</div>
<div class="clearfix"></div>
<div class="scrolldiv" style="margin-bottom: 15px;">

    <input type="text" id="searchProfile" onkeyup="searchProfile()" class="form-control" placeholder="<?= $strings["infoorder_searchprofiles"]; ?>"/>
    <table class="table table-striped table-bordered table-advance table-hover recruiters">
        <thead>
        <tr>
            <th colspan="2"><?= $strings["infoorder_addprofile"]; ?></th>
        </tr>
        </thead>
        <tbody id="profileTable">
        <?php
            $i = 0;
            foreach ($profiles as $r) {
                //echo $r->username;continue;
                //if ($i % 2 == 0) {
                    $username = "[NO NAME]";
                    if (strlen(trim($r->username))>0) {
                        $username = $r->username;
                    } elseif(strlen(trim($r->fname . $r->lname))>0) {
                        $username = $r->fname . " " . $r->lname;
                    }
                    $profiletype = "";
                    if(isset($pType[$r->profile_type])) {
                        $profiletype = " (" . $pType[$r->profile_type] . ")";
                    }
                    if ($profiletype == " ()") {$profiletype = "(Draft)"; }
                    ?>
                    <tr>

                <td>
                    <span><input class="profile_client" type="checkbox" id="p_<?= $i ?>"
                                 <?php if (in_array($r->id, $profile)){ ?>checked="checked"<?php }?>
                                 value="<?= $r->id; ?>"/></span>
                    <span><label for="p_<?= $i ?>"><?= $username; ?></label><?php if($r->profile_type!=""){ echo $profiletype;}?> </span>&nbsp;
                    <span class="msg_<?= $r->id; ?>"></span>
                </td>
                    </tr>
                <?php
                $i++;
            }
        ?>
        </tbody>
    </table>
</div>


<?php }

                    if (isset($_GET['view'])) {
                        ?>
                        <table class="table table-striped table-bordered table-advance table-hover recruiters">
                            <thead>
                            <tr>
                                <th colspan="2"><?= $strings["index_profiles"]; ?>:</th>
                            </tr>
                            </thead>
                            <tbody id="">
                            <?php
                                $types = array('Driver', 'Admin', 'Recruiter', 'External', 'Safety', 'Driver', 'Contact', 'Employee', 'Guest', 'Partner');
                                $counter = 0;
                                foreach ($getprofile as $p) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php
                                                echo '<a href="' . $this->request->webroot . 'profiles/view/' . $p->id . '">';
                                                if (strlen($p->username)>0) {
                                                    echo $p->username;
                                                } elseif(strlen($p->fname)>0 or strlen($p->lname)>0) {
                                                    echo $p->fname . " " . $p->lname;
                                                } else {
                                                    echo "[NO NAME]";
                                                }
                                                $profiletype = " (Draft)";
                                                if (strlen($p->profile_type)>0 ) {
                                                    if ($p->profile_type < count($types)) {
                                                        $profiletype = " (" . $types[$p->profile_type] . ")";
                                                    } else {
                                                        $profiletype = " (UNKNOWN)";
                                                    }
                                                }
                                                echo $profiletype;

                                                echo "</a>"
                                                //<a href="<?php echo $this->request->webroot;!>profiles/view/<?php echo $p->id; !>">
                                            //<?php echo $p->username; !> (<?php echo $types[$p->profile_type]; !>)&nbsp;&nbsp;(Profile)</a>

                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $counter++;
                                }
                                $c = $counter;
                                if ($counter == 0) {
                                    echo "<TR><TD>No <?php echo strtolower($settings->profile); ?>s</TD></TR>";
                                }
                            ?>
                            <?php
                                foreach ($getcontact as $cont) {
                                    ?>
                                             <tr><td>
												<a href="<?php echo $this->request->webroot;?>profiles/view/<?php echo $cont->id; ?>">
												    <?php echo $cont->username; ?> <?php //echo $types[$p->profile_type];
                                    ?>&nbsp;&nbsp;(Contact)
                                                </a>
											</tr></td>
                                                    <?php
                                }
                            ?>
                            </tbody>
                        </table>
                    <?php
                    }
                ?>


    <?php
        $i = 0;
        foreach ($contact as $r) {?>
            <tr>
                <td>
                    <span>
                        <input class="contact_client" type="checkbox"
                             <?php if (in_array($r->id, $contacts)){ echo 'checked="checked"'; }?>
                             value="<?= $r->id; ?>"/>
                    </span>
                    <span> <?= $r->username; ?> </span>
                </td>
            </tr>
            <?php
            $i++;
        }
    ?>
    </tbody>

</table-->
<script>
    function visibleprofiles(){
        var checked = document.getElementById("visibleprofiles").checked;
        $.ajax({
            url: '<?= $this->request->webroot;?>clients/quickcontact',
            data: 'Type=visibleprofiles&clientid=<?= $id; ?>&status=' + checked,
            type: 'get',
            success: function (res) {
                if(res) {alert(res);}
            }
        });
    }

    function searchProfile() {
        var key = $('#searchProfile').val();
        $('#profileTable').html('<tbody><tr><td><img src="<?php echo $this->request->webroot;?>assets/admin/layout/img/ajax-loading.gif"/></td></tr></tbody>');
        $.ajax({
            url: '<?= $this->request->webroot;?>profiles/getAjaxProfile/<?php if(isset($id) && $id)echo $id;else echo '0'?>',
            data: 'key=' + key,
            type: 'get',
            success: function (res) {
                
                $('#profileTable').html(res);
            }
        });
    }
    function searchContact() {
        var key = $('#searchContact').val();
        $('#contactTable').html('<tbody><tr><td><img src="<?php echo $this->request->webroot;?>assets/admin/layout/img/ajax-loading.gif"/></td></tr></tbody>');
        $.ajax({
            url: '<?php echo $this->request->webroot;?>profiles/getAjaxContact/<?php if(isset($id) && $id)echo $id;else echo '0'?>',
            data: 'key=' + key,
            type: 'get',
            success: function (res) {
                $('#contactTable').html(res);
            }
        });
    }
    $(function () {
        $('.contact_client').change(function () {
            if ($(this).is(':checked')) {
                var url = '<?php echo $this->request->webroot;?>clients/assignContact/' + $(this).val() + '/<?php if(isset($id) && $id)echo $id;else echo '0'?>/yes';
            }
            else {
                var url = '<?php echo $this->request->webroot;?>clients/assignContact/' + $(this).val() + '/<?php if(isset($id) && $id)echo $id;else echo '0'?>/no';
            }
            $.ajax({url: url});
        });
        $('.profile_client').live('change',function () {
            var msg = '';
            var nameId = 'msg_'+$(this).val();
            if ($(this).is(':checked')) {
                msg = '<span class="msg" style="color:#45B6AF"><?= addslashes($strings["forms_added"]); ?></span>';
                
                var url = '<?php echo $this->request->webroot;?>clients/assignProfile/' + $(this).val() + '/<?php if(isset($id) && $id)echo $id;else echo '0'?>/yes';
            }
            else {
                msg = '<span class="msg" style="color:red"><?= addslashes($strings["forms_removed"]); ?></span>';
                var url = '<?php echo $this->request->webroot;?>clients/assignProfile/' + $(this).val() + '/<?php if(isset($id) && $id)echo $id;else echo '0'?>/no';
            }
            
            $.ajax({url: url,success:function(){$('.'+nameId).html(msg);}});
        });
        $('.scrolldiv').slimScroll({
            height: '250px'
        });
    });
</script>
