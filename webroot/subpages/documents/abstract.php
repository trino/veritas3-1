<div class="col-md-12">
<?php
 if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/documents/abstract.php #INC166</span>"; }
?>
<form id="form_tab<?php echo $dx->id;?>" enctype="multipart/form-data" action="<?php echo $this->request->webroot;?>documents/absract/<?php echo $cid .'/' .$did;?>" method="post">
    <input type="hidden" class="document_type" name="document_type" value="<?php echo $dx->title;?>"/>
    <input type="hidden" name="sub_doc_id" value="1" class="sub_docs_id" id="af" />
    <h2>Driver Licence Abstract Request</h2>
    <h3>Return abstract by</h3>
    <div class="col-md-12">
        <div class="col-md-12">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($abstract) && $abstract->mail=='1')
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
                    <input class="form-control"  type="checkbox" name="mail" value="1" <?php if(isset($abstract) && $abstract->mail=='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
         <label class="control-label">Mail</label> </div>
         <div class="col-md-2">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($abstract) && $abstract->fax=='1')
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
                    <input class="form-control"  type="checkbox" name="fax" value="1" <?php if(isset($abstract) && $abstract->fax=='1')echo "checked='checked'";?> /> 
                    <?php
                }
             ?>
             <label class="control-label">Fax</label></div>
         <div class="col-md-10">
         <input class="form-control"  type="text" name="fax_more" value="<?php if(isset($abstract))echo $abstract->fax_more;?>"/></div>
         <div class="col-md-2">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($abstract) && $abstract->email=='1')
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
                    <input class="form-control"  type="checkbox" name="email" value="1" <?php if(isset($abstract) && $abstract->email=='1')echo "checked='checked'";?> /> 
                    <?php
                }
             ?>
          <label class="control-label">Email</label></div>   
         <div class="col-md-10"><input class="form-control" role="email"  type="text" name="email_more" value="<?php if(isset($abstract))echo $abstract->email_more;?>"/></div>
    </div>
    <h3>Please type or print clearly, illegible information cannot be processed.</h3>
    
    <div class="col-md-6">
        <label class="control-label">Search fee enclosed</label>
        $<input class="form-control"  type="text" name="search_fee" value="<?php if(isset($abstract))echo $abstract->search_fee;?>"/>
    </div>
    
    <div class="col-md-6">
        <label class="control-label">OR Search fee account no:</label>
        <input class="form-control"  type="text" name="search_fee_acc" value="<?php if(isset($abstract))echo $abstract->search_fee_acc;?>"/>
    </div>
    <div class="col-md-12">
        <label class="control-label">NAME OF CONPANY</label>
        <input class="form-control"  type="text" name="company_name" value="<?php if(isset($abstract))echo $abstract->company_name;?>"/>
    </div>
    <div class="col-md-12">
        <label class="control-label">MAILING ADDRESS</label>
        <input class="form-control"  type="text" name="street" placeholder="STREET/PO BOX/RR#" value="<?php if(isset($abstract))echo $abstract->street;?>"/>
    </div>
    <div class="col-md-10">
        <label class="control-label">CITY/PROVINCE STATE</label>
        <input class="form-control"  type="text" name="city" value="<?php if(isset($abstract))echo $abstract->city;?>"/>
    </div>
    <div class="col-md-2">
        <label class="control-label">POSTAL CODE/ZIP</label>
        <input class="form-control" role="postalcode"  type="text" name="zip" value="<?php if(isset($abstract))echo $abstract->zip;?>"/>
    </div>
    
    <h3>If you wish to charge the Search Fee to Visa, MasterCard or American Exoress, please include the information below</h3>
    <div class="col-md-5">
        <label class="control-label">CREDIT CARD NUMBER</label>
        <input class="form-control"  type="text" name="cc_number" value="<?php if(isset($abstract))echo $abstract->cc_number;?>"/>
    </div>
    <div class="col-md-2">
        <label class="control-label">EXPIRY DATE</label>
        <input class="form-control"  type="text" name="cc_expiry_date" value="<?php if(isset($abstract))echo $abstract->cc_expiry_date;?>"/>
    </div>
    <div class="col-md-5">
        <label class="control-label">NAME AS IT APPEARS ON CREDIT CARD</label>
        <input class="form-control"  type="text" name="cc_name" value="<?php if(isset($abstract))echo $abstract->cc_name;?>"/>
    </div>
    <h3>Companies with access to driver abstract must be listed below before driver signs</h3>
    <div class="col-md-12">
        <label class="control-label">COMPANY NUMBER 1</label>
        <input class="form-control"  type="text" name="no1" value="<?php if(isset($abstract))echo $abstract->no1;?>"/>
    </div>
    <div class="col-md-12">
        <label class="control-label">COMPANY NUMBER 2</label>
        <input class="form-control"  type="text" name="no2" value="<?php if(isset($abstract))echo $abstract->no2;?>"/>
    </div>
    <div class="col-md-12">
        <label class="control-label">COMPANY NUMBER 3</label>
        <input class="form-control"  type="text" name="no3" value="<?php if(isset($abstract))echo $abstract->no3;?>"/>
    </div>
    <div class="col-md-12">
        <label class="control-label">COMPANY NUMBER 4</label>
        <input class="form-control"  type="text" name="no4" value="<?php if(isset($abstract))echo $abstract->no4;?>"/>
    </div>
    
    <h3>Driver Information</h3>
    <div class="col-md-12">
        <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($abstract) && $abstract->auth1=='1')
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
                    <input class="form-control"  type="checkbox" name="auth1" value="1" <?php if(isset($abstract) && $abstract->auth1=='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
         I authorize the above named company to obtain a copy of my driver's abstract form the Insurance Corporation of Britsh Columbia.
    </div>
    <div class="col-md-12">
        <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($abstract) && $abstract->auth2=='1')
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
                    <input class="form-control"  type="checkbox" name="auth2" value="1" <?php if(isset($abstract) && $abstract->auth2=='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
         I authorize the above named company to obtain a copy of my driver insurance history(or any insurance information) form the Insurance Corporation of Britsh Columbia.
    </div>
    <div class="col-md-12">
        <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($abstract) && $abstract->auth3=='1')
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
                    <input class="form-control"  type="checkbox" name="auth3" value="1" <?php if(isset($abstract) && $abstract->auth3=='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
         I authorize the above named company to obtain a copy of my cehicle claim history form the Insurance Corporation of Britsh Columbia.
    </div>
    
    <div class="col-md-2">
        <label class="control-label">Name of Driver</label>
    </div>   
    <div class="col-md-3">
        <input class="form-control"  type="text" name="l_name" placeholder="LAST" value="<?php if(isset($abstract))echo $abstract->l_name;?>"/>
    </div>   
    <div class="col-md-4">
        <input class="form-control"  type="text" name="f_name" placeholder="FIRST" value="<?php if(isset($abstract))echo $abstract->f_name;?>"/>
    </div>  
    <div class="col-md-3">
        <input class="form-control"  type="text" name="m_name" placeholder="MIDDLE" value="<?php if(isset($abstract))echo $abstract->m_name;?>"/>
    </div>
    <div class="col-md-2">
        <label class="control-label">Address</label> 
    </div>
    <div class="col-md-3">
    <input class="form-control"  type="text" placeholder="STREET/PO BOX/ RR#" name="d_street"  value="<?php if(isset($abstract))echo $abstract->d_street;?>"/>
    </div>
    <div class="col-md-4">
        <input class="form-control"  type="text" placeholder="CITY/PROVINCE/STATE" name="d_city" value="<?php if(isset($abstract))echo $abstract->d_city;?>"/>
    </div> 
    <div class="col-md-3">
        <input class="form-control" role="postalcode"  type="text" placeholder="POSTAL CODE/ZIP CODE" name="d_zip" value="<?php if(isset($abstract))echo $abstract->d_zip;?>"/>
    </div> 
    <div class="col-md-3">
        <label class="control-label">Date of Birth</label>
    </div>
    <div class="col-md-3">
        <input class="form-control"  type="text" name="dob" value="<?php if(isset($abstract))echo $abstract->dob;?>"/>
    </div>  
    <div class="col-md-3">
        <label class="control-label">Driver's Licence Number</label>
    </div>
    <div class="col-md-3">
        <input class="form-control"  type="text" name="lic_no" value="<?php if(isset($abstract))echo $abstract->lic_no;?>"/>
    </div>
    <div class="col-md-6">
        <input class="form-control"  type="text" name="signature"  value="<?php if(isset($abstract))echo $abstract->signature;?>"/>
        <label class="control-label">SIGNATURE OF DRIVER</label>
    </div>
    <div class="col-md-6">
        <input class="form-control"  type="text" name="dor"  value="<?php if(isset($abstract))echo $abstract->dor;?>"/>
        <label class="control-label">DATE OF REQUEST</label>
    </div>
      
   
<?php if($this->request->params['controller']!='Documents'){?><div class="addattachment<?php echo $dx->id;?> form-group col-md-12"></div><?php }?>
</form>
 </div>
 <div class="clearfix"></div>
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