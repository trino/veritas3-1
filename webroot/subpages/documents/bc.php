<?php
 if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/documents/bc.php #INC166</span>"; }
?>
<form id="form_tab<?php echo $dx->id;?>" enctype="multipart/form-data" action="<?php echo $this->request->webroot;?>documents/bc/<?php echo $cid .'/' .$did;?>" method="post">
<input type="hidden" class="document_type" name="document_type" value="<?php echo $dx->title;?>"/>
        <input type="hidden" name="sub_doc_id" value="<?php echo $dx->id;?>" class="sub_docs_id" id="af" />
<div class="col-md-12">
    <div class="col-md-4" style="float: right;">
            <input type="text" class="form-control" name="claim_number" value="<?php if(isset($bc_forms)) echo $bc_forms->claim_number;?>" />
            <label class="control-label"> CLAIM NUMBER </label>
     </div>            
    
    <div class="clearfix"></div>
    <p>Please type or print clearly, illegible information cannot be processed.</p>
    
    <div class="col-md-4">
        <label class="contol-label"> LAST NAME </label>
        <input type="text" class="form-control" name="last_name" value="<?php if(isset($bc_forms)) echo $bc_forms->last_name;?>"/>
    </div>
    <div class="col-md-4">
        <label class="contol-label"> FIRST NAME </label>
        <input type="text" class="form-control" name="first_name" value="<?php if(isset($bc_forms)) echo $bc_forms->first_name;?>"/>
    </div>
    <div class="col-md-4">
        <label class="contol-label"> SECOND NAME </label>
        <input type="text" class="form-control" name="second_name" value="<?php if(isset($bc_forms)) echo $bc_forms->second_name;?>"/>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-6">
        <label class="contol-label"> DRIVER'S LICENCE NUMBER </label>
        <input type="text" class="form-control" name="licence_no" value="<?php if(isset($bc_forms)) echo $bc_forms->licence_no;?>"/>
    </div>
    <div class="col-md-3">
        <label class="contol-label"> DATE OF BIRTH </label>
        <input type="text" class="form-control" name="dob" value="<?php if(isset($bc_forms)) echo $bc_forms->dob;?>"/>
    </div>
    <div class="col-md-3">
        <label class="contol-label"> TELEPHONE NUMBER </label>
        <input type="text" class="form-control" role="phone" name="telephone_no" value="<?php if(isset($bc_forms)) echo $bc_forms->telephone_no;?>"/>
    </div>
    
    <div class="col-md-6">
            <input type="text" class="form-control" name="signature" value="<?php if(isset($bc_forms)) echo $bc_forms->signature;?>"/>
            <label class="control-label"> SIGNATURE OF DRIVER <br />
            (REQUEST WILL NOT BE PROCESSED IF SIGNATURE MISSING)</label>
     </div> 
    <div class="col-md-6">
            <input type="text" class="form-control" name="date" value="<?php if(isset($bc_forms)) echo $bc_forms->date;?>"/>
            <label class="control-label"> DATE </label>
     </div>
     <div class="clearfix"></div>
    
    <h2> Return abstract by:</h2>
    
    <div class="col-md-12">
        <div class="col-md-1">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($bc_forms)&& $bc_forms->mail=='1')
                    {
                        ?>
                        &#9745;
                        <?php
                    }
                    else 
                    {
                        ?>
                        &#9744;
                        <?php
                    } 
                }
                else
                {
                    ?>                                      
                    <input type="checkbox" name="mail" <?php if(isset($bc_forms)&& $bc_forms->mail=='1')echo "checked='checked'";?> value="1"/> 
                    <?php
                }
             ?>
            
            <label class="control-label"> Mail </label>
        </div>
        
        <div class="col-md-11">
            
                <div class="col-md-3">
                <label class="control-label"> TO MY MAILING ADDRESS </label>
                <input type="text" class="form-control" name="mailing_add" value="<?php if(isset($bc_forms)) echo $bc_forms->mailing_add;?>"/>
                </div>
                <div class="col-md-3">
                <label class="control-label"> CITY </label>
                <input type="text" class="form-control" name="city" value="<?php if(isset($bc_forms)) echo $bc_forms->city;?>"/>
                </div>
                <div class="col-md-3">
                <label class="control-label"> PROVINCE/STATE </label>
                <input type="text" class="form-control" name="province" value="<?php if(isset($bc_forms)) echo $bc_forms->province;?>"/>
                </div>
                <div class="col-md-3">
                <label class="control-label"> POSTAL/ZIP CODE </label>
                <input type="text" class="form-control" role="postalcode" name="postal" value="<?php if(isset($bc_forms)) echo $bc_forms->postal;?>"/>
                </div>
                
            <div class="col-md-12">
            <p> OR    </p>
                <label class="control-label"> TO NAME OF CARRIER OR COMPANY </label>
                <input type="text" class="form-control" name="carrier_or_company" value="<?php if(isset($bc_forms)) echo $bc_forms->carrier_or_company;?>"/>
            </div>
            
                <div class="col-md-3">
                <label class="control-label"> MAILING ADDRESS </label>
                <input type="text" class="form-control" name="mailing_add1" value="<?php if(isset($bc_forms)) echo $bc_forms->mailing_add1;?>"/>
                </div>
                <div class="col-md-3">
                <label class="control-label"> CITY </label>
                <input type="text" class="form-control" name="city1" value="<?php if(isset($bc_forms)) echo $bc_forms->city1;?>"/>
                </div>
                <div class="col-md-3">
                <label class="control-label"> PROVINCE/STATE </label>
                <input type="text" class="form-control" name="province1" value="<?php if(isset($bc_forms)) echo $bc_forms->province1;?>"/>
                </div>
                <div class="col-md-3">
                <label class="control-label"> POSTAL/ZIP CODE </label>
                <input type="text" class="form-control" role="postalcode" name="postal1" value="<?php if(isset($bc_forms)) echo $bc_forms->postal1;?>"/>
                </div>
                <div class="clearfix"></div>
                   
                
            
        </div>
    </div>
    
        
         <div class="col-md-12">
            <div class="col-md-1">
                <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($bc_forms)&& $bc_forms->fax=='1')
                    {
                        ?>
                        &#9745;
                        <?php
                    }
                    else 
                    {
                        ?>
                        &#9744;
                        <?php
                    } 
                }
                else
                {
                    ?>                                      
                    <input type="checkbox" name="fax" value="1" <?php if(isset($bc_forms)&& $bc_forms->fax=='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
                <label class="control-label"> Fax </label>
            </div>
        
            <div class="col-md-11">
                <div class="col-md-12">
                
                    <label class="control-label"> TO MY FAX NUMBER </label>
                    <input type="text" class="form-control" name="fax_no" value="<?php if(isset($bc_forms)) echo $bc_forms->fax_no;?>"/>
                </div>
               
            
                
                
                <div class="col-md-12">
                <p> OR    </p>
                <label class="control-label"> TO NAME OF CARRIER OR COMPANY </label>
                <input type="text" class="form-control" name="carrier_or_company1" value="<?php if(isset($bc_forms)) echo $bc_forms->carrier_or_company1;?>"/>
                </div>
                <div class="col-md-12">
                <label class="control-label"> FAX NUMBER </label>
                <input type="text" class="form-control" name="fax_no1" value="<?php if(isset($bc_forms)) echo $bc_forms->fax_no1;?>"/>
            </div>
          </div> 
          </div> 
                <div class="clearfix"></div>
            
            <div class="col-md-12">
            
                <div class="col-md-1">
                    <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($bc_forms)&& $bc_forms->email=='1')
                    {
                        ?>
                        &#9745;
                        <?php
                    }
                    else 
                    {
                        ?>
                        &#9744;
                        <?php
                    } 
                }
                else
                {
                    ?>                                      
                    <input type="checkbox" role="email" name="email" value="1" <?php if(isset($bc_forms)&& $bc_forms->email=='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
                    <label class="control-label"> Email </label>
                </div>
                <div class="col-md-11">
                    <div class="col-md-12">
                    <label class="control-label"> TO MY EMAIL ADDRESS </label>
                    <input type="text" class="form-control" role="email" name="email_add" value="<?php if(isset($bc_forms)) echo $bc_forms->email_add;?>"/>
                    </div>
                    
                    <div class="col-md-12">
                    <p> OR    </p>
                    <label class="control-label"> TO NAME OF CARRIER OR COMPANY </label>
                    <input type="text" class="form-control" name="carrier_or_company2" value="<?php if(isset($bc_forms)) echo $bc_forms->carrier_or_company2;?>"/>
                    </div>
                    <div class="col-md-12">
                    <label class="control-label"> EMAIL ADDRESS </label>
                    <input type="text" class="form-control" role="email" name="email_add1" value="<?php if(isset($bc_forms)) echo $bc_forms->email_add1;?>"/>
                    </div>
            
                    <div class="clearfix"></div>
                 
                </div>
            </div>
            
</div>
<?php if($this->request->params['controller']!='Documents'){?>
<div class="addattachment<?php echo $dx->id;?> form-group col-md-12"></div> 
<?php }?>
    <div class="clearfix"></div>
</form>
<script>
 $(function(){
    <?php
        if(isset($disabled))
        {
    ?>
           $('#form_tab<?php echo $dx->id;?> input').attr('disabled','disabled');         
    <?php }
    ?>

 })
 
 </script>