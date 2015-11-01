<?php
if($this->request->params['controller']!='ClientApplication'){
 if($this->request->session()->read('debug')){  echo "<span style ='color:red;'>subpages/documents/past_employer_survey.php #INC204</span>";}
 }
 ?>
<form id="form_tab<?php echo $dx->id;?>" action="<?php echo $this->request->webroot;?>documents/addpastemployer/<?php echo $cid .'/' .$did;?>" method="post">
        <input type="hidden" class="document_type" name="document_type" value="<?php echo $dx->title;?>"/>
        <input type="hidden" name="sub_doc_id" value="<?php echo $dx->id;?>" class="sub_docs_id" id="af" />
        <div class="clearfix"></div>
        <hr/>
        <div class="col-md-12">
        <div class="row"> 
            <div class="col-md-6"><img src="<?php echo $this->request->webroot;?>img/gfs.png" style="width: 120px;" /></div>
            <div class="clearfix"></div>
        </div>
        </div>
        <div class="clearfix"></div>
        <p>&nbsp;</p>
        <div>
            <div class="col-md-6">
            <div class="row"> 
                    <label class="control-label col-md-4">Applicant's Name: </label>  
                    <div class="col-md-8">              
                        <input class="form-control" name="applicant_name" value="<?php if(isset($past_employment_survey))echo $past_employment_survey->applicant_name;?>" />
                    </div>
            </div>
            </div>
            <div class="col-md-6">
            <div class="row"> 
                    <label class="control-label col-md-4">Date: </label>  
                    <div class="col-md-8">              
                        <input class="form-control" name="date" value="<?php if(isset($past_employment_survey))echo $past_employment_survey->date;?>" />
                    </div>
                    </div>
            </div> 
        </div> 
        <div class="clearfix"></div>
        
        <div class="col-md-12">
            <p>&nbsp;</p>
            <div class="row"> 
            <em class="col-md-12">In order to better understand your interests and needs you believe should be present
    
            in the company you work for, please answer the following questions about a previous 
            
            employer. This is not a test, there are no right or wrong answers. The best answer 
            
            is always your honest opinion. Please respond as candidly as possible. Choose the 
            
            response which best reflects your opinion about each item:</em>
            <p>&nbsp;</p>
        <div class="clearfix"></div>
        </div>
        </div>
        
        <div class="col-md-12">
        <div class="row"> 
            <label class="control-label col-md-4">Past Employer (Company): </label>  
            <div class="col-md-8">              
                <input class="form-control" name="past_employer" value="<?php if(isset($past_employment_survey))echo $past_employment_survey->past_employer;?>" />
            </div>
        </div>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12">
        <div class="table-scrollable">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Statements</th>
                        <th>Strongly Disagree</th>
                        <th>Disagree</th>
                        <th>Neither agree nor disagree</th>
                        <th>Agree</th>
                        <th>Strongly Agree</th>
                    </tr>
                </thead>
                
                <tbody>
                    <tr>
                        <td>The working conditions were OK</td>
                        <td>
                            <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($past_employment_survey)&&$past_employment_survey->c1 == '1' )
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
                            <input type="radio" name="c1" value="1" <?php if(isset($past_employment_survey)&&$past_employment_survey->c1 == '1' )echo "checked='checked'";?> /> 
                            <?php
                        }
                         ?>
                        </td>
                        <td>
                         <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($past_employment_survey)&&$past_employment_survey->c1 == '2' )
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
                            <input type="radio" name="c1" value="2" <?php if(isset($past_employment_survey)&&$past_employment_survey->c1 == '2' )echo "checked='checked'";?>/> 
                            <?php
                        }
                         ?>
                        </td>
                        <td>
                        <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($past_employment_survey)&&$past_employment_survey->c1 == '3' )
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
                            <input type="radio" name="c1" value="3" <?php if(isset($past_employment_survey)&&$past_employment_survey->c1 == '3' )echo "checked='checked'";?>/> 
                            <?php
                        }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c1 == '2' )
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
                                <input type="radio" name="c1" value="4" <?php if(isset($past_employment_survey)&&$past_employment_survey->c1 == '4' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                        if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                        {
                            if(isset($past_employment_survey)&&$past_employment_survey->c1 == '5' )
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
                            <input type="radio" name="c1" value="5" <?php if(isset($past_employment_survey)&&$past_employment_survey->c1 == '5' )echo "checked='checked'";?>/> 
                            <?php
                        }
                         ?>
                        </td>
                    </tr>
                    <tr>
                        <td>The people I worked with got along well together</td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c2 == '1' )
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
                                <input type="radio" name="c2" value="1" <?php if(isset($past_employment_survey)&&$past_employment_survey->c2 == '1' )echo "checked='checked'";?> /> 
                                <?php
                            }
                         ?>  
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c2 == '2' )
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
                                <input type="radio" name="c2" value="2" <?php if(isset($past_employment_survey)&&$past_employment_survey->c2 == '2' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                            </td>
                        <td>
                             <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c2 == '3' )
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
                                <input type="radio" name="c2" value="3" <?php if(isset($past_employment_survey)&&$past_employment_survey->c2 == '3' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                             <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c2 == '4' )
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
                                <input type="radio" name="c2" value="4" <?php if(isset($past_employment_survey)&&$past_employment_survey->c2 == '4' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                             <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c2 == '5' )
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
                                <input type="radio" name="c2" value="5" <?php if(isset($past_employment_survey)&&$past_employment_survey->c2 == '5' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                                         
                    </tr>
                    <tr>
                        <td>My supervisor was concerned about my ideas and my suggestions</td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c3 == '1' )
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
                                <input type="radio" name="c3" value="1" <?php if(isset($past_employment_survey)&&$past_employment_survey->c3 == '1' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c3 == '2' )
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
                                <input type="radio" name="c3" value="2" <?php if(isset($past_employment_survey)&&$past_employment_survey->c3 == '2' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c3 == '3' )
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
                                <input type="radio" name="c3" value="3" <?php if(isset($past_employment_survey)&&$past_employment_survey->c3 == '3' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c3 == '4' )
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
                                <input type="radio" name="c3" value="4" <?php if(isset($past_employment_survey)&&$past_employment_survey->c3 == '4' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c3 == '5' )
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
                                <input type="radio" name="c3" value="5" <?php if(isset($past_employment_survey)&&$past_employment_survey->c3 == '5' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td> 
                    </tr>
                    <tr>
                        <td>I frequently was concerned about losing my job</td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c4 == '1' )
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
                                <input type="radio" name="c4" value="1" <?php if(isset($past_employment_survey)&&$past_employment_survey->c4 == '1' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c4 == '2' )
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
                                <input type="radio" name="c4" value="2" <?php if(isset($past_employment_survey)&&$past_employment_survey->c4 == '2' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c4 == '3' )
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
                                <input type="radio" name="c4" value="3" <?php if(isset($past_employment_survey)&&$past_employment_survey->c4 == '3' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c4 == '4' )
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
                                <input type="radio" name="c4" value="4" <?php if(isset($past_employment_survey)&&$past_employment_survey->c4 == '4' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c4 == '5' )
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
                                <input type="radio" name="c4" value="5" <?php if(isset($past_employment_survey)&&$past_employment_survey->c4 == '5' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>                     
                    </tr>
                    <tr>
                        <td>I made a good choice working for the above company</td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c5 == '1' )
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
                                <input type="radio" name="c5" value="1" <?php if(isset($past_employment_survey)&&$past_employment_survey->c5 == '1' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                        <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c5 == '2' )
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
                                <input type="radio" name="c5" value="2" <?php if(isset($past_employment_survey)&&$past_employment_survey->c5 == '2' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                            </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c5 == '3' )
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
                                <input type="radio" name="c5" value="3" <?php if(isset($past_employment_survey)&&$past_employment_survey->c5 == '3' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c5 == '4' )
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
                                <input type="radio" name="c5" value="4" <?php if(isset($past_employment_survey)&&$past_employment_survey->c5 == '4' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c5 == '5' )
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
                                <input type="radio" name="c5" value="5" <?php if(isset($past_employment_survey)&&$past_employment_survey->c5 == '5' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Management was not responsive to employees' problems, or complaints</td> 
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c6 == '1' )
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
                                <input type="radio" name="c6" value="1" <?php if(isset($past_employment_survey)&&$past_employment_survey->c6 == '1' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c6 == '2' )
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
                                <input type="radio" name="c6" value="2" <?php if(isset($past_employment_survey)&&$past_employment_survey->c6 == '2' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c6 == '3' )
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
                                <input type="radio" name="c6" value="3" <?php if(isset($past_employment_survey)&&$past_employment_survey->c6 == '3' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c6 == '4' )
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
                                <input type="radio" name="c6" value="4" <?php if(isset($past_employment_survey)&&$past_employment_survey->c6 == '4' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c6 == '5' )
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
                                <input type="radio" name="c6" value="5" <?php if(isset($past_employment_survey)&&$past_employment_survey->c6 == '5' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                    </tr>
                    <tr>
                        <td>I liked my job - the kind of work I did</td> 
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c7 == '1' )
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
                                <input type="radio" name="c7" value="1" <?php if(isset($past_employment_survey)&&$past_employment_survey->c7 == '1' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c7 == '2' )
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
                                <input type="radio" name="c7" value="2" <?php if(isset($past_employment_survey)&&$past_employment_survey->c7 == '2' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c7 == '3' )
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
                                <input type="radio" name="c7" value="3" <?php if(isset($past_employment_survey)&&$past_employment_survey->c7 == '3' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c7 == '4' )
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
                                <input type="radio" name="c7" value="4" <?php if(isset($past_employment_survey)&&$past_employment_survey->c7 == '4' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c7 == '5' )
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
                                <input type="radio" name="c7" value="5" <?php if(isset($past_employment_survey)&&$past_employment_survey->c7 == '5' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                    </tr>
                    <tr>
                        <td>The pay I received was fair to the type of work I did</td> 
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c8 == '1' )
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
                                <input type="radio" name="c8" value="1" <?php if(isset($past_employment_survey)&&$past_employment_survey->c8 == '1' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c8 == '2' )
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
                                <input type="radio" name="c8" value="2" <?php if(isset($past_employment_survey)&&$past_employment_survey->c8 == '2' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c8 == '3' )
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
                                <input type="radio" name="c8" value="3" <?php if(isset($past_employment_survey)&&$past_employment_survey->c8 == '3' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c8 == '4' )
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
                                <input type="radio" name="c8" value="4" <?php if(isset($past_employment_survey)&&$past_employment_survey->c8 == '4' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c8 == '5' )
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
                                <input type="radio" name="c8" value="5" <?php if(isset($past_employment_survey)&&$past_employment_survey->c8 == '5' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                    </tr>
                    <tr>
                        <td>I received adequate recognition when I did a good job</td> 
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c9 == '1' )
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
                                <input type="radio" name="c9" value="1" <?php if(isset($past_employment_survey)&&$past_employment_survey->c9 == '1' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c9 == '2' )
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
                                <input type="radio" name="c9" value="2" <?php if(isset($past_employment_survey)&&$past_employment_survey->c9 == '2' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c9 == '3' )
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
                                <input type="radio" name="c9" value="3" <?php if(isset($past_employment_survey)&&$past_employment_survey->c9 == '3' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c9 == '4' )
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
                                <input type="radio" name="c9" value="4" <?php if(isset($past_employment_survey)&&$past_employment_survey->c9 == '4' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c9 == '5' )
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
                                <input type="radio" name="c9" value="5" <?php if(isset($past_employment_survey)&&$past_employment_survey->c9 == '5' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                    </tr>
                    <tr>
                        <td>I was satisfied with the benefits I received from the above company</td> 
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c10 == '1' )
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
                                <input type="radio" name="c10" value="1" <?php if(isset($past_employment_survey)&&$past_employment_survey->c10 == '1' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c10 == '2' )
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
                                <input type="radio" name="c10" value="2" <?php if(isset($past_employment_survey)&&$past_employment_survey->c10 == '2' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c10 == '3' )
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
                                <input type="radio" name="c10" value="3" <?php if(isset($past_employment_survey)&&$past_employment_survey->c10 == '3' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c10 == '4' )
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
                                <input type="radio" name="c10" value="4" <?php if(isset($past_employment_survey)&&$past_employment_survey->c10 == '4' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c10 == '5' )
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
                                <input type="radio" name="c10" value="5" <?php if(isset($past_employment_survey)&&$past_employment_survey->c10 == '5' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                    </tr>
                    <tr>
                        <td>My supervisor was not fair in dealing with me</td> 
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c11 == '1' )
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
                                <input type="radio" name="c11" value="1" <?php if(isset($past_employment_survey)&&$past_employment_survey->c11 == '1' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c11 == '2' )
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
                                <input type="radio" name="c11" value="2" <?php if(isset($past_employment_survey)&&$past_employment_survey->c11 == '2' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c11 == '3' )
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
                                <input type="radio" name="c11" value="3" <?php if(isset($past_employment_survey)&&$past_employment_survey->c11 == '3' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c11 == '4' )
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
                                <input type="radio" name="c11" value="4" <?php if(isset($past_employment_survey)&&$past_employment_survey->c11 == '4' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c11 == '5' )
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
                                <input type="radio" name="c11" value="5" <?php if(isset($past_employment_survey)&&$past_employment_survey->c11 == '5' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Management lived up to their promises</td> 
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c12 == '1' )
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
                                <input type="radio" name="c12" value="1" <?php if(isset($past_employment_survey)&&$past_employment_survey->c12 == '1' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c12 == '2' )
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
                                <input type="radio" name="c12" value="2" <?php if(isset($past_employment_survey)&&$past_employment_survey->c12 == '2' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c12 == '3' )
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
                                <input type="radio" name="c12" value="3" <?php if(isset($past_employment_survey)&&$past_employment_survey->c12 == '3' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c12 == '4' )
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
                                <input type="radio" name="c12" value="4" <?php if(isset($past_employment_survey)&&$past_employment_survey->c12 == '4' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                        <td>
                            <?php 
                            if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                            {
                                if(isset($past_employment_survey)&&$past_employment_survey->c12 == '5' )
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
                                <input type="radio" name="c12" value="5" <?php if(isset($past_employment_survey)&&$past_employment_survey->c12 == '5' )echo "checked='checked'";?>/> 
                                <?php
                            }
                         ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
        <?php if($this->request->params['controller']!='Documents' && $this->request->params['controller']!='ClientApplication' ){?>
           <div class="addattachment<?php echo $dx->id;?> form-group col-md-12"></div> 
           <?php }?>
      
                
        <div class="clearfix"></div>
    <p>&nbsp;</p>

</form>
<script>
 $(function(){
    <?php
        if(isset($disabled))
        {
    ?>
           $('#form_tab16 input').attr('disabled','disabled');
           $('#form_tab16 textarea').attr('disabled','disabled');             
    <?php }
    ?>

 })
 
 </script>
