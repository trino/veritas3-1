  <?php
 if($this->request->session()->read('debug'))
        echo "<span style ='color:red;'>recruiter_contact_table.php #INC161</span>";
 ?>
<?php
$profiles = $this->requestAction('Profiles/getProfile');
$contact =  $this->requestAction('Profiles/getContact');
//include("subpages/profileslisting.php");
?>
<div class="scrolldiv" style="margin-bottom: 15px;">
<input type="text" id="searchProfile" onkeyup="searchProfile()" class="form-control" placeholder="Search <?php echo ucfirst($settings->profile); ?> s" />
<table class="table table-striped table-bordered table-advance table-hover recruiters">
                                                <thead><tr><th colspan="2">Assign Profiles</th></tr></thead>
                                                <tbody id="profileTable">
                                                <?php
                                                $i=0;
                                                foreach($profiles as $r)
                                                {
                                                    //echo $r->username;continue;
                                                    if($i%2==0)
                                                    {
                                                        ?>
                                                        <tr>
                                                        <?php
                                                    }
                                                    ?>

                                                    <td>
                                                        <span><input type="checkbox" name="profile_id[]" <?php if(in_array($r->id,$profile)){?>checked="checked"<?php }?> value="<?php echo $r->id; ?>"/></span>
                                                        <span><?php echo $r->username; ?> </span>
                                                    </td>
                                                <?php

                                                 if(($i+1)%2==0)
                                                {
                                                 ?>
                                                 </tr>
                                                 <?php
                                                }

                                                $i++;
                                                }
                                                if(($i+1)%2!=0)
                                                {
                                                    echo "</td></tr>";
                                                }
                                                ?>
                                                </tbody>
                                            </table>
    </div>
<p>&nbsp;</p>
<table class="table table-striped table-bordered table-advance table-hover contacts">
                                                <thead><tr><th colspan="2">Add Contacts</th></tr></thead>
                                                <?php
                                                $i=0;
                                                foreach($contact as $r)
                                                {
                                                    if($i%2==0)
                                                    {
                                                        ?>
                                                        <tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    <td>
                                                        <span><input type="checkbox" name="contact_id[]" <?php if(in_array($r->id,$contacts)){?>checked="checked"<?php }?> value="<?php echo $r->id; ?>"/></span>
                                                        <span> <?php echo $r->username; ?> </span>
                                                    </td>

                                                <?php

                                                 if(($i+1)%2==0)
                                                {
                                                 ?>
                                                 </tr>
                                                 <?php
                                                }

                                                $i++;
                                                }
                                                if(($i+1)%2!=0)
                                                {
                                                    echo "</td></tr>";
                                                }
                                                ?>

                                            </table>
<script>
function searchProfile()
{
    var key = $('#searchProfile').val();
    $('#profileTable').html('<tbody><tr><td><img src="<?php echo $this->request->webroot;?>assets/admin/layout/img/ajax-loading.gif"/></td></tr></tbody>');
    $.ajax({
        url:'<?php echo $this->request->webroot;?>profiles/getAjaxProfile/<?php if(isset($id) && $id)echo $id;else echo '0'?>',
        data:'key='+key,
        type:'get',
        success:function(res){
            $('#profileTable').html(res);
        }
    });
}
$(function(){
    $('.scrolldiv').slimScroll({
        height: '250px'
    });
});
</script>
