<?php
if(isset($disabled)) {
    $is_disabled = 'disabled="disabled"';
} else {
    $is_disabled = '';
    if(isset($feeds)) {
        $feed = $feeds;
    }
}
?>
<h3 class="page-title">
			<?php //echo ucfirst($settings->client);?>Feedbacks <!--<small>Add/Edit <?php //echo ucfirst($settings->client);?></small>-->
			</h3>

			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo $this->request->webroot;?>">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="">Add/Edit Feedbacks</a>
					</li>
				</ul>
			</div>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                            <i class="fa fa-comment"></i>
                            Feedbacks
                            </div>
                        </div>
                        <div class="portlet-body">
                            <form role="form" action="" method="post">
                                                    <input type="hidden" name="document_type" value="feedbacks" />
                                                    <input type="hidden" name="user_id" value="<?php echo $this->request->session()->read('Profile.id');?>" />


                                                    <div class="form-group">
                                                            <label class="control-label col-md-6">Title: </label>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="title" value="<?php if(isset($feed->title)){echo $feed->title;} ?>" />
                                                            </div>
                                                            <div class="clearfix"></div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label col-md-6">Description</label>
                                                            <div class="col-md-6">
                                                                <textarea class="form-control" name="description" ><?php if(isset($feed->description)) echo $feed->description; ?></textarea>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                    </div>
                                                    <div class="form-group">
                                                    <div class=" col-md-12"><label class="control-label">If you had a colleague who required our services, would you recommend ISB Canada ?(Scale 1-10) </label>
                                                    </div>
                                                            <div class="col-md-12">
                                                                <input type="radio" class="form-control" name="scale" value="1" <?php if(isset($feed->scale)&& $feed->scale = 1){?> selected="selected" <?php } ?> />&nbsp;&nbsp;1&nbsp;&nbsp;
                                                                <input type="radio" class="form-control" name="scale" value="2" <?php if(isset($feed->scale)&& $feed->scale = 2){?> selected="selected" <?php } ?> />&nbsp;&nbsp;2&nbsp;&nbsp;
                                                                <input type="radio" class="form-control" name="scale" value="3" <?php if(isset($feed->scale)&& $feed->scale = 3){?> selected="selected" <?php } ?> />&nbsp;&nbsp;3&nbsp;&nbsp;
                                                                <input type="radio" class="form-control" name="scale" value="4" <?php if(isset($feed->scale)&& $feed->scale = 4){?> selected="selected" <?php } ?> />&nbsp;&nbsp;4&nbsp;&nbsp;
                                                                <input type="radio" class="form-control" name="scale" value="5" <?php if(isset($feed->scale)&& $feed->scale = 5){?> selected="selected" <?php } ?> />&nbsp;&nbsp;5&nbsp;&nbsp;
                                                                <input type="radio" class="form-control" name="scale" value="6" <?php if(isset($feed->scale)&& $feed->scale = 6){?> selected="selected" <?php } ?> />&nbsp;&nbsp;6&nbsp;&nbsp;
                                                                <input type="radio" class="form-control" name="scale" value="7" <?php if(isset($feed->scale)&& $feed->scale = 7){?> selected="selected" <?php } ?> />&nbsp;&nbsp;7&nbsp;&nbsp;
                                                                <input type="radio" class="form-control" name="scale" value="8" <?php if(isset($feed->scale)&& $feed->scale = 8){?> selected="selected" <?php } ?> />&nbsp;&nbsp;8&nbsp;&nbsp;
                                                                <input type="radio" class="form-control" name="scale" value="9" <?php if(isset($feed->scale)&& $feed->scale = 9){?> selected="selected" <?php } ?> />&nbsp;&nbsp;9&nbsp;&nbsp;
                                                                <input type="radio" class="form-control" name="scale" value="10" <?php if(isset($feed->scale)&& $feed->scale = 10){?> selected="selected" <?php } ?> />&nbsp;&nbsp;10&nbsp;&nbsp;
                                                            </div>
                                                            <div class="clearfix"></div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label col-md-6">What is the reason for your score in question #1?</label>
                                                            <div class="col-md-6">
                                                                <textarea class="form-control" name="reason" ><?php if(isset($feed->reason)) echo $feed->reason; ?></textarea>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="control-label col-md-6">What could we do to improve the score you gave in question #1? </label>
                                                            <div class="col-md-6">
                                                                <textarea class="form-control" name="suggestion" ><?php if(isset($feed->suggestion)) echo $feed->suggestion; ?></textarea>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                    </div>
                                                    <div class="clearfix"></div>

                                                        <?php
                                                        if (!isset($disabled)) {
                                                            ?>
                                                            <div class="margiv-top-10">
                                                                <button type="submit" class="btn btn-primary">
                                                                    Send </button>
                                                                
                                                            </div>
                                                        <?php } ?>
                             </form>
                        </div>
                    </div>
                </div>
            </div>
            