<?php
 if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/documents/quebec.php #INC166<BR> dx is not defined!!!</span>"; }
 if(isset($dx)){ echo '<p>Document - ' . $dx->title . '</p>'; }
?>
<form id="form_tab<?php echo $dx->id;?>" enctype="multipart/form-data" action="<?php echo $this->request->webroot;?>documents/quebec/<?php echo $cid .'/' .$did;?>" method="post">
    <input type="hidden" class="document_type" name="document_type" value="<?php echo $dx->title;?>"/>
        <input type="hidden" name="sub_doc_id" value="<?php echo $dx->id;?>" class="sub_docs_id" id="af" />
    <div class="col-md-12">
        <div class="col-md-4" style="float: right;">
            <label class="control-label"> Claim number </label>
            <input type="text" class="form-control" name="claim_number" value="<?php if(isset($quebec_forms)) echo $quebec_forms->claim_number;?>"/> 
        </div>
        <div class="clearfix"></div>
        <h2>INFORMATION ON THE APPLICANT</h2>
        <div class="col-md-12">
            <label class="control-label"> Company, agency or other (in block letters) </label>
            <input type="text" class="form-control" name="company" value="<?php if(isset($quebec_forms)) echo $quebec_forms->company;?>"/> 
        </div>
        <div class="col-md-12">
            <label class="control-label"> Name of the person authorized to act on behalf of applicant (in block letters) </label>
            <input type="text" class="form-control" name="on_behalf" value="<?php if(isset($quebec_forms)) echo $quebec_forms->on_behalf;?>"/> 
        </div>
        <div class="col-md-12">
            <label class="control-label"> Address (Number, street, apt.) </label>
            <input type="text" class="form-control" name="address" value="<?php if(isset($quebec_forms)) echo $quebec_forms->address;?>"/> 
        </div>
        <div class="col-md-12">
            <label class="control-label"> Company, agency or other (in block letters) </label>
            <input type="text" class="form-control" name="company1" value="<?php if(isset($quebec_forms)) echo $quebec_forms->company1;?>"/> 
        </div>
         
            <div class="col-md-5">
                <label class="control-label"> Municipality/ Province </label>
                <input type="text" class="form-control" name="municipality" value="<?php if(isset($quebec_forms)) echo $quebec_forms->municipality;?>"/>
            </div>
            <div class="col-md-2">
                <label class="control-label"> Postal code </label>
                <input type="text" role="postalcode" class="form-control" name="postal_code" value="<?php if(isset($quebec_forms)) echo $quebec_forms->postal_code;?>"/>
            </div>
            <div class="col-md-2">
                <label class="control-label"> Area code </label>
                <input type="text" class="form-control" name="area_code" value="<?php if(isset($quebec_forms)) echo $quebec_forms->area_code;?>"/>
            </div>
            <div class="col-md-2">
                <label class="control-label"> Telephone </label>
                <input type="text" class="form-control" role="phone" name="telephone" value="<?php if(isset($quebec_forms)) echo $quebec_forms->telephone;?>"/>
            </div>
            <div class="col-md-1">
                <label class="control-label"> Extension </label>
                <input type="text" class="form-control" name="extension" value="<?php if(isset($quebec_forms)) echo $quebec_forms->extension;?>"/>
            </div>
          <div class="clearfix"></div>
          <h2> INFORMATION ON THE REPRESENTATIVE </h2>
          <div class="col-md-12">
            <label class="control-label"> Name of representative (in block letters) </label>
            <input type="text" class="form-control" name="representative" value="<?php if(isset($quebec_forms)) echo $quebec_forms->representative;?>"/> 
        </div>
        <div class="col-md-12">
            <label class="control-label"> Name of the authorized person (in block letters)  </label>
            <input type="text" class="form-control" name="authorized" value="<?php if(isset($quebec_forms)) echo $quebec_forms->authorized;?>"/> 
        </div>
        <div class="col-md-12">
            <label class="control-label"> Address (Number, street, apt.) </label>
            <input type="text" class="form-control" name="address1" value="<?php if(isset($quebec_forms)) echo $quebec_forms->address1;?>"/> 
        </div>
        
        <div class="col-md-5">
                <label class="control-label"> Municipality/ Province </label>
                <input type="text" class="form-control" name="municipality1" value="<?php if(isset($quebec_forms)) echo $quebec_forms->municipality1;?>"/>
            </div>
            <div class="col-md-2">
                <label class="control-label"> Postal code </label>
                <input type="text" class="form-control" role="postalcode" name="postal_code1" value="<?php if(isset($quebec_forms)) echo $quebec_forms->postal_code1;?>"/>
            </div>
            <div class="col-md-2">
                <label class="control-label"> Area code </label>
                <input type="text" class="form-control" name="area_code1" value="<?php if(isset($quebec_forms)) echo $quebec_forms->area_code1;?>"/>
            </div>
            <div class="col-md-2">
                <label class="control-label"> Telephone </label>
                <input type="text" class="form-control" role="phone" name="telephone1" value="<?php if(isset($quebec_forms)) echo $quebec_forms->telephone1;?>"/>
            </div>
            <div class="col-md-1">
                <label class="control-label"> Extension </label>
                <input type="text" class="form-control" name="extension1" value="<?php if(isset($quebec_forms)) echo $quebec_forms->extension1;?>"/>
            </div>
           <div class="clearfix"></div>
          <p> Note: The representative undertakes to use the information only to convey it to the applicant.</p>
          
          <h2> LICENSE HOLDER'S AUTHORIZATION </h2>
          
          <div class="col-md-12">
                <div class="col-md-4">
                <label class="control-label"> Driver's license number </label>
                <input type="text" class="form-control" name="license_no" value="<?php if(isset($quebec_forms)) echo $quebec_forms->license_no;?>"/>
                <p>Enter 13 characters. </p>
                </div>
                <div class="clearfix">
                </div>
                <div class="col-md-8">
                <label class="control-label"> Name of driver's license holder </label>
                <input type="text" class="form-control" name="license_holder" value="<?php if(isset($quebec_forms)) echo $quebec_forms->license_holder;?>"/>
                </div>
                <div class="clearfix"></div>
                
                <div class="col-md-2">
                <label class="control-label"> Date of birth </label>
                <input type="text" class="form-control" name="dob" value="<?php if(isset($quebec_forms)) echo $quebec_forms->dob;?>"/>
                </div>
                <div class="col-md-3">
                <label class="control-label"> Telephone (home) </label>
                <input type="text" class="form-control" name="tel_home" value="<?php if(isset($quebec_forms)) echo $quebec_forms->tel_home;?>"/>
                </div>
                <div class="col-md-3">
                <label class="control-label"> Telephone (work) </label>
                <input type="text" class="form-control" role="phone" name="tel_work" value="<?php if(isset($quebec_forms)) echo $quebec_forms->tel_work;?>"/>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                <p>I, the undersigned, authorized de Societe de I'assurance 
                automobile due Quebec to disclose the content of my driving
                 record, including <br /> in particular suspensions, revocations, 
                 demirit points and heavy vehicle driving-related offences
                 or accidents in which I was involved,<br /> if any, to the above-named
                 applicant. This consent is valid for twelve (12) months from
                 the date of signature.</p>
                 </div>
                
                <div class="col-md-4">
                <input type="text" class="form-control" name="date2" value="<?php if(isset($quebec_forms)) echo $quebec_forms->date2;?>"/>
                <label class="control-label"> Date </label>
                </div>
                <div class="col-md-6">
                <input type="text" class="form-control" name="signature" value="<?php if(isset($quebec_forms)) echo $quebec_forms->signature;?>"/>
                <label class="control-label"> License holder's signature </label>
                </div>
                
            </div>
            <div class="col-md-12">
            <p> <strong>Protection of personal information</strong> </p>
            <p>All information gathered by authorized SAAQ personnel is
             handled confidentially. The SAAQ needs such personal information
            to apply the Automobile <br /> Insurance Act and the Highway Safety Code.
            Under the Act respecting access to documents held by public bodies
            and the protection of personal <br /> information, it may be conveyed to
            the Government department or agencies, or used for statistical,
            survey, study, audit or investigative purposes. <br /> Failure to provide
            information can result in a refusal of service on the SAAQ's part.
            Individual may consult or correct any personal information <br /> concerning
            them held in SAAQ records. For the information, contact the SAAQ's call
            call centers or consult the Policy on Privacy on the SAAQ website <br /> at
            www.saaq.gouv.qc.ca. </p>
            </div>
            <p><strong>Societe de I'assurance automobile du Quebec</strong></p>
            
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
