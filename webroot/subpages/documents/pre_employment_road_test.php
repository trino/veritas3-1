<?php
 if($this->request->params['controller']!='ClientApplication'){
    if($this->request->session()->read('debug')){  echo "<span style ='color:red;'>subpages/documents/pre_employment_road_test.php #INC205</span>";}
 }
 if(isset($dx)){ echo '<h3>' . $dx->title . '</h3>'; }
?>
<form id="form_tab<?php echo $dx->id;?>" action="<?php echo $this->request->webroot;?>documents/pre_employment_road_test/<?php echo $cid .'/' .$did;?>" method="post">
        <input type="hidden" class="document_type" name="document_type" value="<?php echo $dx->title;?>"/>
        <input type="hidden" name="sub_doc_id" value="<?php echo $dx->id;?>" class="sub_docs_id" id="af" />
        <div class="clearfix"></div>
        <hr/>
        
        
        <div>
            <div class="col-md-4">
                    <label class="control-label col-md-4">Driver: </label>  
                    <div class="col-md-8">              
                        <input class="form-control" name="driver" value="<?php if(isset($pre_employment_road_test))echo $pre_employment_road_test->driver;?>"/>
                    </div>
            </div>

            <div class="col-md-4">
                    <label class="control-label col-md-4">Evaluator: </label>  
                    <div class="col-md-8">              
                        <input class="form-control" name="evaluator" value="<?php if(isset($pre_employment_road_test))echo $pre_employment_road_test->evaluator;?>"/>
                    </div>
            </div> 
            <div class="col-md-4">
                    <label class="control-label col-md-4">Date: </label>  
                    <div class="col-md-8">              
                        <input class="form-control" name="date" value="<?php if(isset($pre_employment_road_test))echo $pre_employment_road_test->date;?>"/>
                    </div>
            </div> 
        </div> 
        <div class="clearfix"></div>
        <p>&nbsp;</p>
        <div class="table-scrollable">
        <table class="table table-bordered">
        <tr>
            <td>
        <div class="col-md-12">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c1 =='1')
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
                    <input type="checkbox" name="c1" id="c1" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c1 =='1')echo "checked='checked'";?>/>
                    <?php
                }
             ?>
            <label for="c1"><strong>Pre Trip Inspection performed as per GFS policy</strong></label>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c2 =='1')
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
                    <input type="checkbox" name="c2" id="c2" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c2 =='1')echo "checked='checked'";?>/>
                    <?php
                }
             ?>
             <label for="c2"><strong>Applies 4 steps to proper coupling</strong></label>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c1 =='1')
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
                    <input type="checkbox" name="c3" id="c3" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c3 =='1')echo "checked='checked'";?>/>
                    <?php
                }
             ?>
             <label for="c3"><strong>Uses the 5 Keys to Defensive Driving</strong></label>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12">
            <div class="col-md-12">
                <table class="table table-bordered">
                        <tr>
                            <td>
                            
                                    <?php //repeating code needs factoring, should use <label for=
                                    if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                    {
                                        if(isset($pre_employment_road_test) && $pre_employment_road_test->c4 =='1')
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
                                        <input type="checkbox" name="c4" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c4 =='1')echo "checked='checked'";?>/> 
                                        <?php
                                    }
                                 ?>
                                     Aim High In Steering
                             </td>
                             <td>
                             Looking 15 seconds ahead
                             </td>
                         </tr>
                         <tr>
                             <td>
                                    <?php 
                                    if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                    {
                                        if(isset($pre_employment_road_test) && $pre_employment_road_test->c5 =='1')
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
                                        <input type="checkbox" name="c5" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c5 =='1')echo "checked='checked'";?>/> 
                                        <?php
                                    }
                                 ?>
                                     Get The Big Picture
                             </td>
                             <td>
                             Check mirrors every 5 to 8 seconds
                             </td>
                    </tr>
                    <tr>
                            <td>
                                <?php 
                                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                                {
                                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c6 =='1')
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
                                    <input type="checkbox" name="c6" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c6 =='1')echo "checked='checked'";?>/> 
                                    <?php
                                }
                             ?>
                                 Keep Your Eyes Moving
                         </td>
                         <td>
                            Every 2 seconds, avoid stares
                         </td>
                </tr>
                <tr>
                <td>
                
                <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c7 =='1')
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
                    <input type="checkbox" name="c7" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c7 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
                 Leave Yourself An Out
                 
                 </td>
                <td>
                Surround Yourself With Space
                </td>
                </tr>
                <tr>
                <td>
                
                <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c8 =='1')
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
                    <input type="checkbox" name="c8" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c8 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
                 Make Sure They See You
                 </td>
                 <td>
                 Eye Contact, Tap of the Horn
                 </td>
                </tr>
                </table>
                </div>
            </div>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c9 =='1')
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
                    <input type="checkbox" name="c9" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c9 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
             <strong>Keeps following distance of 1 sec per 10 ft of vehicle</strong>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c10 =='1')
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
                    <input type="checkbox" name="c10" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c10 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
             <strong>Uses brakes smoothly, start braking early to avoid hard stops</strong>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c11 =='1')
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
                    <input type="checkbox" name="c11" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c11 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
             <strong>Smooth shifting, using proper gear, and down shifts</strong>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c12 =='1')
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
                    <input type="checkbox" name="c12" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c12 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
             <strong>Operating in Traffic</strong>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12">
            <div class="col-md-12">
                <table class="table table-bordered">
                <tr>
                <td>
                
                <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c13 =='1')
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
                    <input type="checkbox" name="c13" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c13 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
                 Uses signals properly
                </td>
                </tr>
                <tr>
                <td>
                
                <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c14 =='14')
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
                    <input type="checkbox" name="c14" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c14 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
                 Obeys traffic sings and signals
                </td>
                </tr>
                <tr>
                <td>
                
                <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c15 =='1')
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
                    <input type="checkbox" name="c15" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c15 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
                 Intersections
                 <p>&nbsp;</p>
                
                <div class="col-md-12">
                <table class="table table-bordered">
                <tr>
                <td>
                
                <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c16 =='1')
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
                    <input type="checkbox" name="c16" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c16 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
                     Stops ahead of crosswalk
                    </td>
                </tr>  
                <tr>
                    <td>
                    <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c17 =='1')
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
                    <input type="checkbox" name="c17" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c17 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
                     Yields right of way
                    </td>                
                </tr> 
                <tr>
                    <td>
                    
                    <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c18 =='1')
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
                    <input type="checkbox" name="c18" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c18 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
                Looks left, right and left
                    </td>                
                </tr>             
                </table>
                </div>  
                </td>
                </tr>
                </table>              
            </div>
        </div>
        <p>&nbsp;</p>
        </td>
        
        <td>
        
        
        
        <div class="col-md-12">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c19 =='1')
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
                    <input type="checkbox" name="c19" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c19 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
             <strong>Passes only when safe to do so</strong>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c20 =='1')
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
                    <input type="checkbox" name="c20" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c20 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
             <strong>Uses right lane as a habit</strong>
        </div>
        <p>&nbsp;</p>
        <div class="col-md-12">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c21 =='1')
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
                    <input type="checkbox" name="c21" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c21 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
             <strong>Observes speed limit</strong>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c22 =='1')
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
                    <input type="checkbox" name="c22" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c22 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
             <strong>Courteous to other drivers and pedestrians</strong>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
            <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c23 =='1')
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
                    <input type="checkbox" name="c23" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c23 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
             <strong>Backing</strong>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
            <div class="col-md-12">
            <table class="table table-bordered">
            <tr>
            <td>
            
                <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c24 =='1')
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
                    <input type="checkbox" name="c24" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c24 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
                     Avoid blind side backing whenever possible
                    </td>
            </tr>
            <tr>
            <td>
            
                    <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c25 =='1')
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
                    <input type="checkbox" name="c25" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c25 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
                     Uses horn and hazards
                    </td>
            </tr>
            <tr>
            <td>
            
                    <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c26 =='1')
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
                    <input type="checkbox" name="c26" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c26 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
                     Keeps eye on both sides of truck
                    </td>
            </tr>
            <tr>
            <td>
            
                    <?php 
                if($this->request->params['action'] == 'vieworder'  || $this->request->params['action']== 'view')
                {
                    if(isset($pre_employment_road_test) && $pre_employment_road_test->c27 =='1')
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
                    <input type="checkbox" name="c27" value="1" <?php if(isset($pre_employment_road_test) && $pre_employment_road_test->c27 =='1')echo "checked='checked'";?>/> 
                    <?php
                }
             ?>
                Get out and look to ensure clearance 
                    </td>
            </tr>
            </table>
            </div>
        </div>
        
        <p>&nbsp;</p>
        <div class="col-md-12">
            <label class="col-md-12">Comments</label>
            <div class="col-md-12">
                <textarea name="comment" style="height: 120px;" class="form-control"><?php if(isset($pre_employment_road_test))echo $pre_employment_road_test->comment;?></textarea>
            </div>
        </div>
        <p>&nbsp;</p>
        </td>
        </tr>
        </table>
        
                    
        <?php if($this->request->params['controller']!='Documents' && $this->request->params['controller']!='ClientApplication'){?><div class="addattachment<?php echo $dx->id;?> form-group col-md-12"></div><?php }?>         
        <div class="clearfix"></div>
    

</form>
</div>
<script>
$(function(){
<?php
        if(isset($disabled))
        {
    ?>
           $('#form_tab17 input').attr('disabled','disabled');
           $('#form_tab17 textarea').attr('disabled','disabled');            
    <?php }
    ?>
    });
</script>