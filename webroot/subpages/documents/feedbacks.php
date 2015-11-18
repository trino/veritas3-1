<?php
    if($this->request->params['controller']!='ClientApplication'){
        if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/documents/feedbacks.php #INC130</span>"; }
    }
    $is_disabled = '';
    if(isset($disabled)){ $is_disabled = 'disabled="disabled"'; }
    if(isset($feeds)) {$feed = $feeds; }
    if(isset($dx)){ echo '<h3>' . $dx->title . '</h3>'; }
?>
<form role="form" action="" method="post" id="form_tab6">

 <input type="hidden" class="document_type" name="document_type" value="<?php echo $dx->title;?>"/>

    <input type="hidden" name="sub_doc_id" value="6" class="sub_docs_id" id="af" />
    <div class="form-group col-md-12">
            <label class="control-label col-md-6">Title: </label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="title" <?php echo $is_disabled;?> value="<?php if(isset($feed->title)){echo $feed->title;} ?>" />
            </div>
            <!--<div class="clearfix"></div>-->
    </div>
    <div class="form-group col-md-12">
            <label class="control-label col-md-6">Description: </label>
            <div class="col-md-6">
                <textarea class="form-control" name="description" <?php echo $is_disabled;?> ><?php if(isset($feed->description)) echo $feed->description; ?></textarea>
            </div>
            <div class="clearfix"></div>
    </div>
    
    <label class="control-label col-md-6">If you had a colleague who required our services, would you recommend ISB Canada? </label>
        <div class="col-md-6" align="center">
            <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($feed->scale)&& $feed->scale == 1)
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
                            <input type="radio" <?php echo $is_disabled;?> class="form-control" name="scale" value="1" <?php if(isset($feed->scale)&& $feed->scale == 1){?> checked="checked" <?php } ?> /> 
                            <?php
                        }
                         ?>
            &nbsp;&nbsp;1&nbsp;&nbsp;
            <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($feed->scale)&& $feed->scale == 2)
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
                            <input type="radio" <?php echo $is_disabled;?> class="form-control" name="scale" value="2" <?php if(isset($feed->scale)&& $feed->scale == 2){?> checked="checked" <?php } ?> /> 
                            <?php
                        }
                         ?>
            &nbsp;&nbsp;2&nbsp;&nbsp;
            <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($feed->scale)&& $feed->scale == 3)
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
                            <input type="radio" <?php echo $is_disabled;?> class="form-control" name="scale" value="3" <?php if(isset($feed->scale)&& $feed->scale == 3){?> checked="checked" <?php } ?> /> 
                            <?php
                        }
                         ?>
            &nbsp;&nbsp;3&nbsp;&nbsp;
            <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($feed->scale)&& $feed->scale == 4)
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
                            <input type="radio" <?php echo $is_disabled;?> class="form-control" name="scale" value="4" <?php if(isset($feed->scale)&& $feed->scale == 4){?> checked="checked" <?php } ?> /> 
                            <?php
                        }
                         ?>
            &nbsp;&nbsp;4&nbsp;&nbsp;
            <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($feed->scale)&& $feed->scale == 5)
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
                            <input type="radio" <?php echo $is_disabled;?> class="form-control" name="scale" value="5" <?php if(isset($feed->scale)&& $feed->scale == 5){?> checked="checked" <?php } ?> /> 
                            <?php
                        }
                         ?>
            &nbsp;&nbsp;5&nbsp;&nbsp;
            <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($feed->scale)&& $feed->scale == 6)
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
                            <input type="radio" <?php echo $is_disabled;?> class="form-control" name="scale" value="6" <?php if(isset($feed->scale)&& $feed->scale == 6){?> checked="checked" <?php } ?> /> 
                            <?php
                        }
                         ?>
            6&nbsp;&nbsp;&nbsp;&nbsp;
            <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($feed->scale)&& $feed->scale == 7)
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
                            <input type="radio" <?php echo $is_disabled;?> class="form-control" name="scale" value="7" <?php if(isset($feed->scale)&& $feed->scale == 7){?> checked="checked" <?php } ?> /> 
                            <?php
                        }
                         ?>
            &nbsp;&nbsp;7&nbsp;&nbsp;
            <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($feed->scale)&& $feed->scale == 8)
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
                            <input type="radio" <?php echo $is_disabled;?> class="form-control" name="scale" value="8" <?php if(isset($feed->scale)&& $feed->scale == 8){?> checked="checked" <?php } ?> /> 
                            <?php
                        }
                         ?>
            &nbsp;&nbsp;8&nbsp;&nbsp;
            <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($feed->scale)&& $feed->scale == 9)
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
                            <input type="radio" <?php echo $is_disabled;?> class="form-control" name="scale" value="9" <?php if(isset($feed->scale)&& $feed->scale == 9){?> checked="checked" <?php } ?> /> 
                            <?php
                        }
                         ?>
            &nbsp;&nbsp;9&nbsp;&nbsp;
            <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($feed->scale)&& $feed->scale == 10)
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
                            <input type="radio" <?php echo $is_disabled;?> class="form-control" name="scale" value="10" <?php if(isset($feed->scale)&& $feed->scale == 10){?> checked="checked" <?php } ?> /> 
                            <?php
                        }
                         ?>
            &nbsp;&nbsp;10&nbsp;&nbsp;
        </div>
        <div class="clearfix"></div>
        <div class="form-group"></div>
<div class="form-group col-md-12">
        <label class="control-label col-md-6">What is the reason for your score?</label>
        <div class="col-md-6">
            <textarea class="form-control" name="reason" <?php echo $is_disabled;?> ><?php if(isset($feed->reason)) echo $feed->reason; ?></textarea>
        </div>
        <div class="clearfix"></div>
</div>
<div class="form-group col-md-12">
        <label class="control-label col-md-6" >What could we do to improve the score? </label>
        <div class="col-md-6">
            <textarea class="form-control" name="suggestion" <?php echo $is_disabled;?> ><?php if(isset($feed->suggestion)) echo $feed->suggestion; ?></textarea>
        </div>
        <div class="clearfix"></div>
</div>
<div class="clearfix"></div>


<?php if($this->request->params['controller']!='Documents' && $this->request->params['controller']!='ClientApplication'){?>
 <div class="addattachment6 form-group col-md-12"></div>
 <?php }?>
 </form> 
<div class="clearfix"></div>
