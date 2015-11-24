<?php
if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/employment_verification_form.php #INC???</span>"; }
$strings2 = CacheTranslations($language, array("verifs_%"), $settings, False);
die("Don't use this one.");
?>

<div class="portlet box blue ">
						<div class="portlet-title">
							<div class="caption">
								Employment History
							</div>
						</div>
                        <div class="portlet-body form">
								<div class="form-body">
<h4><strong>Employer Information for Last 3 Years</strong></h4>

<div class="table-scrollable">
    <table class="table table-striped">
                
                <tr><td colspan="2">Name<input type="text" class="form-control" /></td></tr>
                <tr><td>Driver's License #:<input type="text" class="form-control"/></td><td>Date of Birth:<input type="text" class="form-control" placeholder="MM/DD/YYYY"/></td></tr>
                <tr><td>Total Claims in Past 3 Years:<input type="text" class="form-control"/></td><td>Current Employer:<input type="text" class="form-control"/></td></tr>
     </table>
</div>

<div class="table-scrollable">
    <table class="table table-striped">
                <tr><th colspan="2">Past Employer</th></tr>
                <tr><td colspan="2">Company Name<input type="text" class="form-control" /></td></tr>
                <tr><td colspan="2">Address<input type="text" class="form-control" /></td></tr>
                <tr><td>Supervisor's Name:<input type="text" class="form-control"/></td><td>Phone #:<input type="text" class="form-control"/></td></tr>
                <tr><td>Supervisor's Email:<input type="text" class="form-control"/></td><td>Secondary Email:<input type="text" class="form-control"/></td></tr>
                <tr><td>Employment Start Date:<i class="fa fa-calendar"></i><input type="text" class="form-control todo-taskbody-due date form_datetime"/></td><td>Employment End Date:<input type="text" class="form-control"/></td></tr>
                <tr><td>Claims with this Employer:&nbsp;&nbsp;<input type="radio"/>&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"/>&nbsp;&nbsp;&nbsp;&nbsp;No</td><td>Date Claims Occured:<input type="text" class="form-control"/></td></tr>
                <tr><td colspan="2">Employment history confirmed by (Verifier Use Only):<input type="text" class="form-control"/></td></tr>
                <tr><td>
                        Signature:<input type="text" class="form-control"/>

                    </td><td>

                        Date/Time:<input type="text" class="form-control" />


                    </td></tr>
                <tr><td colspan="2" >

                            <label class="control-label col-md-3">Equipment Operated : </label>
                                <input type="checkbox"/>&nbsp;Vans&nbsp;
                                <input type="checkbox"/>&nbsp;Reefers&nbsp;
                                <input type="checkbox"/>&nbsp;Decks&nbsp;
                                <input type="checkbox"/>&nbsp;Super B's&nbsp;
                                <input type="checkbox"/>&nbsp;Straight Truck&nbsp;
                                <input type="checkbox"/>&nbsp;Others:





                    </td></tr>



        <tr><td colspan="2">




                <label class="control-label col-md-3">Driving Experience : </label>
                    <input type="checkbox"/>&nbsp;Local&nbsp;
                    <input type="checkbox"/>&nbsp;Canada&nbsp;
                    <input type="checkbox"/>&nbsp;Canada : Rocky Mountains&nbsp;
                    <input type="checkbox"/>&nbsp;USA&nbsp;

            </td></tr>



    </table>
</div>
<div id="more_div"></div>
<div id="add_more_div">
    <p>&nbsp;</p>
    <a href="javascript:void(0);" class="btn btn-primary" id="add_more">Add More</a>
</div>

<div class="form-group col-md-12">
    <label class="control-label col-md-3">Attach File : </label>
    <div class="col-md-9">
    <a href="javascript:void(0);" class="btn btn-primary">Browse</a>
    </div>
   </div>
   
  <div class="form-group col-md-12">
    <div id="more_employ_doc">
    </div>
  </div>
  
  <div class="form-group col-md-12">
    <div class="col-md-3">
    </div>
    <div class="col-md-9">
        <a href="javascript:void(0);" class="btn btn-success" id="add_more_employ_doc">Add More</a>
    </div>
  </div>
  <div class="clearfix"></div>
</div>
</div>
</div>

<script>
    <?php loadstringsJS(array_merge($strings, $strings2)); ?>
$(function(){
  $("#add_more").click(function(){
    $.ajax({
       url:"<?php echo $this->request->webroot;?>subpages/past_employer.php?language=" + language + "&debug=<?= $this->request->session()->read('debug'); ?>",
       success:function(res){
        $("#more_div").append(res);
       }
    });
  });
  $("#delete").live("click",function(){
    $(this).parent().parent().remove(); 
  }); 
  
  
  $('#add_more_employ_doc').click(function(){
        $('#more_employ_doc').append('<div class="del_append_employ"><label class="control-label col-md-3">Attach File: </label><div class="col-md-6 pad_bot"><a href="javascript:void(0);" class="btn btn-primary">Browse</a><a  href="javascript:void(0);" class="btn btn-danger" id="delete_employ_doc">Delete</a></div></div><div class="clearfix"></div>')
       }); 
       
       $('#delete_employ_doc').live('click',function(){
            $(this).closest('.del_append_employ').remove();
       });
 }); 
</script>