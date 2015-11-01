<?php $settings = $this->requestAction('settings/get_settings');?>
<h3 class="page-title">
    <?php echo ucfirst($settings->document);?>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?php echo $this->request->webroot;?>">Dashboard</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href=""><?php echo ucfirst($settings->document);?>
            </a>
        </li>
    </ul>
</div>
<h4 class="col-md-12">Understanding Your Businesses Security Risks and Focus</h4>
			<div class="form-body">
                <div class="form-group col-md-12">
                <form action="" method="post">
                    <div class=" col-md-12">
    				<label class="control-label"> 1) What is the biggest security risk facing your company today (industry)? </label>
                    </div>
    				<div class="col-md-12">
    					<textarea class="form-control" name="ques1"></textarea>
    				</div>
                </div>
                
                    <div class="form-group col-md-12">
    				<label class="control-label"> 2) What are the top 3 budget spends you have approved or are seeking approval for in the next 3 years? (in order by budget %) </label>
                    
    				<div class="form-group col-md-12">
    					<input type="text" class="form-control" name="ques2a" placeholder="a."/>
    				</div>
                    <div class="form-group col-md-12">
    					<input type="text" class="form-control" name="ques2b" placeholder="b."/>
    				</div>
                    <div class="form-group col-md-12">
    					<input type="text" class="form-control" name="ques2c" placeholder="c."/>
    				</div>
                    </div>
                
                <div class="form-group col-md-12">
                    <div class=" col-md-12">
    				<label class="control-label"> 3) What region that you currently operate takes the majority of your time or you foresee the most extensive issues in the next 3 years? </label>
                    </div>
    				<div class="col-md-12">
    					<input type="text" class="form-control" name="ques3" placeholder="What is the region/country?"/>
    				</div>
                </div>
                
                
                <div class="form-group col-md-12">
    				<label class="control-label"> 4) Is there anything that you'd change about this VIP event experience?</label>
    				<div class="col-md-12" id="yes_text" style="display: none;">
    					<input type="text" class="form-control" name="ans4" placeholder="If yes, what?"/>
    				</div>
                </div>
                    <div class="form-group col-md-12">
    					<input type="radio" class="form-control" name="ques4" value="1"/>&nbsp;&nbsp;Yes
    					<input type="radio" class="form-control" name="ques4" value="0"/>&nbsp;&nbsp;No
    				</div>
                    <div class="margiv-top-10">
                    <a id="save_client_p1" class="btn btn-primary" href="javascript:void(0)">Save</a>
                    <a class="btn default" href=""> Cancel </a>
                    </div>
                    </form>
                <div class="clearfix"></div>
                </div>
                
<script>
$(function(){
   $("input[type='radio']").change(function() {
    if ($(this).val() == 1) {
        $('#yes_text').show();
    } else {
        $('#yes_text').hide();
    }
}); 
});
</script>