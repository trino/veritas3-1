<?php
    $is_disabled1 = 'disabled="disabled"';
    $languages = array("English", "French");

    if($this->Session->read('Profile.super')) {//&& $this->Session->read('Profile.id')== $this->request['pass'][0] PASS 0 returned unknown offset
        $is_disabled1 = '';
    }

    if($this->request->session()->read('debug')) {
      echo "<span style ='color:red;'>subpages/profile/other_display.php #INC122</span>";
    }

    $col = false;
    function splitcamelcase($text){
        return implode(" ", preg_split('/(?=[A-Z])/',$text));
    }

    function printsetting($col, $settings, $KeyName){
        if(!$col){ echo '<div class="row">';}
        echo '<div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">' . ucfirst(splitcamelcase($KeyName)) . '</label>
                        <input type="text" name="' . $KeyName . '" class="form-control" value="' . $settings->$KeyName . '"/>
                    </div>
                </div>';
        if($col){echo '</DIV>';}
        return !$col;
    }
 ?>

<form action="<?php echo $this->request->webroot; ?>settings/change_text" role="form" method="post" id="displayformz">
        <div class="form-group" id="notli">
            <?php

                echo '<INPUT TYPE="HIDDEN" NAME="languages" VALUE="' . implode(",", $languages) . '">';
                foreach($languages as $language) {
                    if ($language == "English") {$language = "";}
                    $col = printsetting($col, $settings, "client" . $language);
                    $col = printsetting($col, $settings, "document" . $language);
                    $col = printsetting($col, $settings, "profile" . $language);
                }

                $col = printsetting($col, $settings, "mee");
                //$col = printsetting($col, $settings, "forceemail");

                if($col) {echo "</DIV>";}
            ?>
                <!--<select class="form-control" onchange="change_text(this.value)">
                    <option value="">Select User/Profile</option>
                    <option value="1">Profile/Client</option>
                    <option value="2">User/Job</option>
                </select>-->

            </div>
            <div align="right">
                <a id="save_displayz" class="btn btn-primary" >Submit</a>
                <a href="#" class="btn btn-primary">
                    Cancel </a>
            </div>
            <div class="clearfix"></div>
            <div class="margin-top-10 alert alert-success display-hide flash" style="display: none;">
                <button class="close" data-close="alert"></button>
                Display saved successfully
            </div>
        </form>

     
        
<script>
    $(function(){
       $('#save_displayz').click(function(){
        $('#save_displayz').text('Saving..');
            var str = $('#displayformz').serialize();
            $.ajax({
               url:'<?php echo $this->request->webroot;?>settings/change_text',
               data:str,
               type:'post',
               success:function(res) {
                $('.flash').show();
                $('.flash').fadeOut(3500);
                $('#save_displayz').text(' Submit ');
               }
            })
       });
    });
</script>