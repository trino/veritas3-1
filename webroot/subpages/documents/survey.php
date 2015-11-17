<?php
    if($this->request->params['controller']!='ClientApplication'){
    if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/documents/survey.php #INC127</span>";}
    }
    $is_disabled = '';
    if(isset($disabled)){$is_disabled = 'disabled="disabled"';}
    if(isset($dx)){ echo '<p>' . $dx->title . '</p>'; }
?>
<form role="form" action="" method="post" id="form_tab5">
<div class="row"> <h4 class="col-md-12">Understanding Your Businesses Security Risks and Focus</h4></div>
			
    
    <input type="hidden" class="document_type" name="document_type" value="<?php echo $dx->title;?>"/>
    <input type="hidden" name="sub_doc_id" value="5" class="sub_docs_id" id="af" />
            <div class="form-body row">
                <div class="col-md-12">
                <div class="row"> 
                    <div class="col-md-12">
    				<label class="control-label"> 1) What is the biggest security risk facing your company today (industry)? </label>
                    </div>
    				<div class="col-md-12">
    					<textarea class="form-control" name="ques1" <?php echo $is_disabled;?>><?php if(isset($survey))echo $survey->ques1;?></textarea>
    				</div>
                </div>
                </div>
                
                <div class="col-md-12">
                <div class="row"> 
                    <div class="col-md-12">
    				<label class="control-label"> 2) What are the top 3 budget spends you have approved or are seeking approval for in the next 3 years? (in order by budget %) </label>
                    </div>
    				<div class="col-md-12">
    					<input type="text" class="form-control" name="ques2a" <?php echo $is_disabled;?> placeholder="a." value="<?php if(isset($survey))echo $survey->ques2a;?>"/>
    				</div>
                    <div class="col-md-12">
    					<input type="text" class="form-control" name="ques2b" <?php echo $is_disabled;?>placeholder="b." value="<?php if(isset($survey))echo $survey->ques2b;?>"/>
    				</div>
                    <div class="col-md-12">
    					<input type="text" class="form-control" name="ques2c" <?php echo $is_disabled;?> placeholder="c." value="<?php if(isset($survey))echo $survey->ques2c;?>"/>
    				</div>
                </div>
                </div>
                
                <div class="col-md-12">
                <div class="row"> 
                    <div class="col-md-12">
    				<label class="control-label"> 3) What region that you currently operate takes the majority of your time or you foresee the most extensive issues in the next 3 years? </label>
                    </div>
    				<div class="col-md-12">
    					<input type="text" class="form-control" name="ques3" <?php echo $is_disabled;?> placeholder="What is the region/country?" value="<?php if(isset($survey))echo $survey->ques3;?>"/>
    				</div>
                </div>
                </div>
                
                
               <div class="col-md-12">
    				<label class="control-label"> 4) Is there anything that you'd change about this VIP event experience?</label>
                </div>
    				<div class="col-md-12" id="yes_text" style="display: none; <?php if(isset($survey) && $survey->ques4=='0')echo "display: none;";?>">
    					<input type="text" <?php echo $is_disabled;?> class="form-control" name="ans4" placeholder="If yes, what?" value="<?php if(isset($survey))echo $survey->ans4;?>"/>
    				</div>
                    <div class="col-md-12 radio-list">
                        <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($survey) && $survey->ques4=='1')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input type="radio" <?php echo $is_disabled;?> class="form-control" name="ques4" value="1" <?php if(isset($survey) && $survey->ques4=='1')echo "checked='checked'";?>/> 
                            <?php
                        }
                         ?>
    					&nbsp;&nbsp;Yes
                        </label>
                        <label class="radio-inline">
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($survey) && $survey->ques4=='0')
                            {
                                ?>
                                &#10004;
                                <?php
                            }
                            else 
                            {
                                ?>
                                &#10006;
                                <?php
                            } 
                        }
                        else
                        {
                            ?>                                      
                            <input type="radio" <?php echo $is_disabled;?> class="form-control" name="ques4" value="0" <?php if(isset($survey) && $survey->ques4=='0')echo "checked='checked'";?>/> 
                            <?php
                        }
                         ?>
    					&nbsp;&nbsp;No
                        </label>
    				</div>
                    
                <div class="clearfix"></div>
                </div>
                

 <div class="addattachment5 col-md-12"></div>
  </form> 
 <div class="clearfix"></div>        
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