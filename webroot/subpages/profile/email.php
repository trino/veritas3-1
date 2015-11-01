<?php
    if ($this->request->session()->read('debug')) {
        echo "<span style ='color:red;'>subpages/profile/email.php #INC???</span>";
    }
?>

<form role="form" method="post">
    <INPUT TYPE="hidden" name="action" value="sendmessage">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group required">
                <label class="control-label required">Message:</label>
                <textarea name="message" class="form-control required" style="height: 400px;" required></textarea>
            </div>
        </div>

        <div class="col-md-12" align="right">
            <INPUT TYPE="submit" Value="Send Message" class="btn btn-primary">
        </div>

        <div class="clearfix"></div>

    </div>
</form>