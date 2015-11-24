<!-- BEGIN PORTLET-->
<?php
 if($this->request->session()->read('debug')) {echo "<span style ='color:red;'>display.php #INC120</span>";}
 ?>
<div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-briefcase"></i>Display
        </div>
    </div>
    <div class="portlet-body">

        <?php
 //echo $this->Session->read('Profile.admin');
if($this->Session->read('Profile.admin') && $this->Session->read('Profile.id')== $this->request['pass'][0]  )
    $is_disabled1 = '';
else
    $is_disabled1 = 'disabled="disabled"';
    

?>
<form action="<?php echo $this->request->webroot; ?>settings/change_text" role="form" method="post" id="displayformz">
        <div class="form-group" id="notli">
                                        <div class="row">

                                            <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label">Client</label>
                                                <input type="text" name="client" class="form-control"
                                                       value="<?php echo $settings->client; ?>"/>
                                            </div>
                                            </div>


                                            <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label">Document</label>
                                                <input type="text" name="document" class="form-control"
                                                       value="<?php echo $settings->document; ?>"/>
                                            </div>
                                            </div>
                                            </div>
                                            <div class="row">

                                            <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label">Profile</label>
                                                <input type="text" name="profile" class="form-control"
                                                       value="<?php echo $settings->profile; ?>"/>
                                            </div>
                                            </div>


                                            <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label">MEE</label>
                                                <input type="text" name="mee" class="form-control"
                                                       value="<?php echo $settings->mee; ?>"/>
                                            </div>
                                            </div>
                                            </div>



                                            <!--<select class="form-control" onchange="change_text(this.value)">
                                                <option value="">Select User/Profile</option>
                                                <option value="1">Profile/Client</option>
                                                <option value="2">User/Job</option>
                                            </select>-->

                                        </div>
                                        <div class="margin-top-10">
                                            <a id="save_displayz" class="btn btn-primary" >Submit</a>
                                            <a href="#" class="btn default">
                                                Cancel </a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="margin-top-10 alert alert-success display-hide flash" style="display: none;">
                                            <button class="close" data-close="alert"></button>
                                            Display saved successfully
                                        </div>
                                    </form>

                                    
                                    <p>&nbsp;</p>

        <script>
            $(function(){
                $('#save_displayz').click(function(){
                    $('#save_displayz').text('Saving..');
                    var str = $('#displayformz').serialize();
                    $.ajax({
                        url:'<?php echo $this->request->webroot;?>settings/change_text',
                        data:str,
                        type:'post',
                        success:function(res)
                        {
                            $('.flash').show();
                            $('.flash').fadeOut(3500);
                            $('#save_displayz').text(' Submit ');
                        }
                    })
                });
            });
        </script>

    </div>
</div>
<!-- END PORTLET-->