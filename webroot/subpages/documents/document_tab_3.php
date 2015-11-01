<?php
 if($this->request->session()->read('debug')) {
     echo "<span style ='color:red;'>subpages/documents/document_tab3.php #INC139</span>";
 }
 ?>
<div id="form_tab4">
<input class="document_type" type="hidden" name="document_type" value="<?php if(isset($dx))echo $dx->title;else echo "Consent";?>" />
<input type="hidden" class="sub_docs_id" name="sub_doc_id" value="4"  />
<!--<div>
                                                <ul class="nav nav-tabs consents">

                                                    <?php
                                                    if($this->request->params['action']!='add' && $this->request->params['action']!='view')
                                                    {
                                                    ?>
                                                    <li class="active">
        												<a href="#subtab_2_1" data-toggle="tab">Consent Form</a>
        											</li>
                                                    <li class="" style="display: block;">
                                                        <a href="#subtab_2_2" data-toggle="tab">Employment Verification</a>
                                                    </li>
                                                    <li style="display: block;">
                                                        <a href="#subtab_2_3" data-toggle="tab">Education Verification</a>
                                                    </li>
        											<?php }?> 


        										</ul>
                                                </div>-->
                                                <div class="tab-content">
                                                <div class="tab-pane active" id="subtab_2_1">
                                                    
                                						
                                							

                                							

                                						
                                							<!-- BEGIN FORM-->
                                							<?php include('consent_form.php');?>
                                							<!-- END FORM-->
                                						
                                				

                                                </div>
                                                <!--<div class="tab-pane <?php if(isset($_GET['doc']) && urldecode($_GET['doc'])=='Employment Verification'){?>active<?php }?>" id="subtab_2_2">
                                                    
                                                    
                                						<h2>Employment Verification (Employer Information for Last 3 Years)</h2>
                                                        <?php //include('employment_verification_form.php');?>
                                					
                                                                
                                						
                                                </div>

                                                <div class="tab-pane <?php if(isset($_GET['doc']) && urldecode($_GET['doc'])=='Education Verification'){?>active<?php }?>" id="subtab_2_3">
                                                    
                                						<h2>Education Verification (Education Information for Last 3 Years)</h2>
                                                        <?php //include('education_verification_form.php');?>
                                					

                                                </div>-->


											</div>
