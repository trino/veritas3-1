 <?php
 if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/company_pre_screen_question.php #INC155</span>";}
 ?>
 <?php if(isset($dx)){ echo '<h3>' . $dx->title . '</h3>'; }?>
<div class="portlet box blue ">
						<div class="portlet-title">
							<div class="caption">
								Driver Pre-Screen Questions
							</div>
						</div>
						<div class="portlet-body form">
								<div class="form-body">

                                    <div class="form-group col-md-6">
                                        <label class="col-md-6 control-label">Driver</label>
                                        <div class="col-md-6">
                                            <select class="form-control member_type">
                                                <option value="John">John Smith</option>
                                                <option value="Member">Sam Jones</option>
                                                <option value="Contact">Bob Rob</option>
                                                <option value="Contact">Jack Smith</option>
                                                <option value="Contact">Jack Smith</option>
                                                <option value="Contact">Jack Smith</option>
                                            </select>
                                        </div>
                                    </div>


									<div class="form-group col-md-6">
										<label class="control-label">Recruiter's Name : </label>
										<div class="col-md-6">
											<input type="text" class="form-control">
										</div>
									</div>
									<div class="form-group col-md-6">
										<label class="col-md-6 control-label">Applicant's Phone Number : </label>
										<div class="col-md-6">
											<input type="text" class="form-control">
										</div>
									</div>
                                    <div class="form-group col-md-6">
										<label class="col-md-6 control-label">Applicant's Name : </label>
										<div class="col-md-6">
											<input type="text" class="form-control">
										</div>
									</div>
                                    <div class="form-group col-md-6">
										<label class="col-md-6 control-label">Applicant's Email : </label>
										<div class="col-md-6">
											<input type="text" class="form-control">
										</div>
									</div>
                                    <div class="form-group col-md-6">
										<label class="col-md-6 control-label">Date : </label>
										<div class="col-md-6">
											<input type="text" class="form-control" placeholder="(YYYY-MM-DD)">
										</div>
									</div>
                                    <div class="form-group col-md-6">
										<label class="col-md-6 control-label">Position : </label>
										<div class="col-md-6">
											<input type="text" class="form-control" placeholder="S,T">
										</div>
									</div>
                                    <div class="clearfix"></div>
								</div>
						</div>
					</div>
                    <div class="clearfix"></div>
                    
                    
                        <div class="table-scrollable">
                        <table class="table table-striped">
                                    <thead>
                                    <tr>
										<th class="center" style="width: 50%;">Screen Questions</th>
                            
										<th class="center">Answers</th>
                                    </tr>
                                    </thead>
                                    
                                        <tr>
                                            <th class="center" colspan="2">Intro</th>
                                        </tr>
                                    
                                    <tr><td>What made you decide to call Challenger today?</td><td><textarea class="form-control"></textarea></tr>
                                    <tr><td>What type of job are you interested in?</td><td><textarea class="form-control"></textarea></td></tr>
                                    <tr><td>How did you hear about this opportunity? (make sure we confirm which web site they saw our posting)</td>
                                        <td><textarea class="form-control"></textarea></td></tr>
                                        <tr>
                                            <th class="center" colspan="2">Basic Requirements</th>
                                        </tr>
                                    <tr>
                                        <td>Are you legally eligible to work in Canada?</td><td><input type="radio"/>&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"/>&nbsp;&nbsp;&nbsp;&nbsp;No</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Do you currently hold a valid Canadian passport?</td><td><input type="radio"/>&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"/>&nbsp;&nbsp;No</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>(If they do not have a valid Canadian passport)Do you have a Permanent Residency card and US Visa?</td><td><input type="radio"/>&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"/>&nbsp;&nbsp;No</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Do you have a FAST card?</td><td><input type="radio"/>&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"/>&nbsp;&nbsp;No </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Have you ever been convicted of a criminal offence for which a pardon has not been granted or, which could cause you to not cross the border?</td><td><input type="radio"/>&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"/>&nbsp;&nbsp;No</td>
                                    </tr>
                                    
                                    <tr><td>Where do you live?</td><td><textarea class="form-control"></textarea></tr>
                                    
                                    <tr><td>How do you feel about running team? (Do they have a partner?)</td><td><textarea class="form-control"></textarea></tr>
                                    
                                        <tr>
                                            <th class="center" colspan="2">Discovery</th>
                                        </tr>
                                    <tr>
                                        <td>When did you get your AZ License and have you been commercially driving consistently since you got your license?</td><td><input type="text" class="form-control" placeholder="MM/YYYY" /></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Are you currently driving for another carrier?If yes, who and for how long?</td><td><textarea class="form-control"></textarea></td>
                                    </tr>
                                    
                                        <tr><td colspan="2"><strong>Tell me about the work you are doing?</strong></td></tr>
                                    <tr>
                                        <td colspan="2">
                                        <div class="form-group col-md-12">
                                            <label class="control-label col-md-3"> Miles : </label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" />
                                            </div>
                                            <label class="control-label col-md-3"> Time out/home : </label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="control-label col-md-3"> Locations : </label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" />
                                            </div>
                                            <label class="control-label col-md-3"> Border Cross : </label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="control-label col-md-3"> Type of equipment : </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>What did you like most about the job?</td><td><textarea class="form-control"></textarea></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>What did you like least about the job?</td><td><textarea class="form-control"></textarea></td>
                                    </tr> 
                                    
                                    <tr>
                                        <td>What is your reason for leaving?</td><td><textarea class="form-control"></textarea></td>
                                    </tr>  
                                    
                                    <tr>
                                        <td>What other tractor trailer experience do you have?  How recent is this experience?For BC: Any mountain experience?</td><td><textarea class="form-control"></textarea></td>
                                    </tr>  
                                    
                                    <tr>
                                        <td>What type of equipment have you Operated? (Standard or automatic).</td><td><textarea class="form-control"></textarea></td>
                                    </tr> 
                                    
                                    <tr>
                                        <td>Reefer:  Y or N   How many loads</td><td><input type="radio"/>&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"/>&nbsp;&nbsp;No </td>
                                    </tr> 
                                    
                                    <tr>
                                        <td>Do you have a clean driving abstract? (and for ON drivers, their CVOR?) </td><td><textarea class="form-control"></textarea></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Do you have any violations in the USA against your CSA?</td><td><textarea class="form-control"></textarea></td>
                                    </tr> 
                                    
                                    <tr>
                                        <td>Do you have any demerit points? If any points for what?</td><td><textarea class="form-control"></textarea></td>
                                    </tr> 
                                    
                                    <tr>
                                        <td>As part of our screening process we check previous employment references. Have you had any incidents that may not be on your CVOR, but that your previous employer has recorded?</td><td><textarea class="form-control"></textarea></td>
                                    </tr> 
                                    
                                    <tr>
                                        <td>Driving at night is a requirement for the job. Do you see any reason why you would not be able to drive at night?</td><td><textarea class="form-control"></textarea></td>
                                    </tr> 
                                    
                                    <tr>
                                        <td>Company policy is that tractors are to be parked at the driver's home terminal. Do you have access to get to the terminal?(tell them where the terminal is)</td><td><textarea class="form-control"></textarea></td>
                                    </tr>  
                                    
                                    <tr>
                                            <th class="center" colspan="2">Expectations</th>
                                        </tr>
                                    <tr>
                                        <td>As we are a long haul company, driving to the USA is a requirement for most runs. Are you willing cross the border? Have you ever crossed the border with a load?</td><td><input type="text" class="form-control" placeholder="MM/YYYY" /></td>
                                    </tr> 
                                    
                                    <tr>
                                        <td>Tell me what kind of time out and time home you are looking for?</td><td><textarea class="form-control"></textarea></td>
                                    </tr> 
                                    
                                    <tr>
                                        <td>How many miles are you hoping to run in a week?</td><td><textarea class="form-control"></textarea></td>
                                    </tr> 
                                    
                                    <tr>
                                        <td>Explain the areas they will be running. Discuss/document suitable runs available, giving details.  Are they willing to drive in these areas?</td><td><textarea class="form-control"></textarea></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>What is your current salary? Gross or net?</td><td><textarea class="form-control"></textarea></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>What are you looking for in your next employer?</td><td><textarea class="form-control"></textarea></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>What's important to you in your next opportunity?</td><td><textarea class="form-control"></textarea></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>What is the reason you have applied/contacted Challenger?</td><td><textarea class="form-control"></textarea></td>
                                    </tr>  
                                    
                                    <tr>
                                        <th class="center" colspan="2">Closing</th>
                                    </tr>
                                    
                                    <tr>
                                        <td>If you were to accept a position with Challenger, how soon could you start working?</td><td><textarea class="form-control"></textarea></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Are you interviewing with other companies?</td><td><textarea class="form-control"></textarea></td>
                                    </tr> 
                                    
                                     <tr><td colspan="2"><strong>Explain the next steps:</strong></td></tr>
                                    <tr>
                                        <td colspan="2">
                                        <div class="form-group col-md-12">
                                            <label class="control-label col-md-6"> Request the completed application </label>
                                            <div class="col-md-6">
                                                <textarea class="form-control"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <label class="control-label col-md-6">Schedule for a road test</label>
                                            <div class="col-md-6">
                                                <textarea class="form-control"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <label class="control-label col-md-6">Criminal Search</label>
                                            <div class="col-md-6">
                                                <textarea class="form-control"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group col-md-12">
                                            <label class="control-label col-md-6">Med/Drug screen</label>
                                            <div class="col-md-6">
                                                <textarea class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                            <th class="center" colspan="2">Questions for Trainees</th>
                                        </tr> 
                                        
                                        <tr>
                                        <td>What school did you attend?</td><td><textarea class="form-control"></textarea></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>How many total hours was your program?</td><td><textarea class="form-control"></textarea></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>What did you learn in class?
                                        <br/>Answers you are looking for<br />
                                        <ul class="no-bullet">
                                            <li>Hours of service</li>
                                            <li>Transportation of dangerous goods</li>
                                            <li>Trip planning</li>
                                            <li>Logs</li>
                                            <li>Weight & dimensions</li>
                                            <li>Load Securement</li>
                                        </ul>
                                        </td>
                                        <td><textarea class="form-control"></textarea></td>
                                    </tr> 
                                    
                                    <tr>
                                        <td>When did you take your MTO road test?</td><td><textarea class="form-control"></textarea></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Did you get your license on the 1st try:If no, how many attempts?</td><td><textarea class="form-control"></textarea></td>
                                    </tr>
                                    
                                    <tr><td colspan="2"><strong>Have you driven since getting your AZ?  If so, please provide details:</strong></td></tr>
                                    <tr>
                                        <td colspan="2">
                                        <div class="form-group col-md-12">
                                            <label class="control-label col-md-3"> Miles : </label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" />
                                            </div>
                                            <label class="control-label col-md-3"> Time out/home : </label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="control-label col-md-3"> Locations : </label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" />
                                            </div>
                                            <label class="control-label col-md-3"> Border Cross : </label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="control-label col-md-3"> Type of equipment : </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </td>
                                    </tr>   
                                    
                                    <tr>
                                        <td>Recruiters comments and recommendations (note what day they are booked to come in for a road test and interview):</td><td><textarea class="form-control"></textarea></td>
                                    </tr>                      
                                    
                            </table>
                                        <div class="form-group col-md-12">
                                        <label class="control-label col-md-3">Attach File : </label>
                                        <div class="col-md-9">
                                        <a href="javascript:void(0);" class="btn btn-primary">Browse</a>
                                        </div>
                                       </div>
                                      
                                      <div class="form-group col-md-12">
                                        <div class="attach_more">
                                        </div>
                                      </div>
                                      
                                      <div class="form-group col-md-12">
                                        <div class="col-md-3">
                                        </div>
                                        <div class="col-md-9">
                                            <a href="javascript:void(0);" class="add_attach btn btn-primary">Add More</a>
                                        </div>
                                      </div>
                            <div class="clearfix"></div>
                            <?php //include('canvas/example.php');?>
                            <?php //include('canvas/example2.php');?>
                            <div class="clearfix"></div>
            

                        </div>
                        
<script>
    $(function(){
        $('.add_attach').click(function(){
           $('.attach_more').append('<div class="pad_bot" id="del_pre"><label class="control-label col-md-3">Attach File: </label><div class="col-md-6 pad_bot"><a href="javascript:void(0);" class="btn btn-primary">Browse</a><a  href="javascript:void(0);" class="btn btn-danger delete_attach">Delete</a></div></div></div><div class="clearfix"></div>')
        });
        
        $('.delete_attach').live('click',function(){
            $(this).closest('#del_pre').remove();
            
        });
        //$("#test1").jqScribble();
        //$("#test2").jqScribble();
    });

       
		function save(numb)
		{
		  alert('rest');return;
			$("#test"+numb).data("jqScribble").save(function(imageData)
			{
				$.post('image_save.php', {imagedata: imageData}, function(response)
					{
						
                        $.ajax({
                            url:'<?php echo $this->request->webroot;?>document/image_sess/'+numb+'/'+response
                        });
					});	
				
			});
		}
		function addImage()
		{
			var img = prompt("Enter the URL of the image.");
			if(img !== '')$("#test").data("jqScribble").update({backgroundImage: img});
		}
		
		</script>