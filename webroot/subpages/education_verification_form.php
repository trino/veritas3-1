<div class="portlet box blue ">
						<div class="portlet-title">
							<div class="caption">
								Education History
							</div>
						</div>
                        <div class="portlet-body form">
								<div class="form-body">
<h4><strong>Education Information for Last 3 Years</strong></h4>

<div class="table-scrollable">
    <table class="table table-striped">

                <tr><td colspan="2">Name<input type="text" class="form-control" /></td></tr>
                <tr><td>ID #:<input type="text" class="form-control"/></td><td>Date of Birth:<input type="text" class="form-control" placeholder="MM/DD/YYYY"/></td></tr>
                <tr><td>Total Claims in Past 3 Years:<input type="text" class="form-control"/></td><td>Current Education:<input type="text" class="form-control"/></td></tr>
     </table>
</div>

<div class="table-scrollable">
    <table class="table table-striped">
                <tr><th colspan="2">Past Education</th></tr>
                <tr><td colspan="2">School/College Name<input type="text" class="form-control" /></td></tr>
                <tr><td colspan="2">Address<input type="text" class="form-control" /></td></tr>
                <tr><td>Supervisor's Name:<input type="text" class="form-control"/></td><td>Phone #:<input type="text" class="form-control"/></td></tr>
                <tr><td>Supervisor's Email:<input type="text" class="form-control"/></td><td>Secondary Email:<input type="text" class="form-control"/></td></tr>
                <tr><td>Education Start Date:<input type="text" class="form-control"/></td><td>Education End Date:<input type="text" class="form-control"/></td></tr>
                <tr><td>Claims with this Tutor:&nbsp;&nbsp;<input type="radio"/>&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"/>&nbsp;&nbsp;&nbsp;&nbsp;No</td><td>Date Claims Occured:<input type="text" class="form-control"/></td></tr>
                <tr><td colspan="2">Education history confirmed by (Verifier Use Only):<input type="text" class="form-control"/></td></tr>
                <tr><td colspan="2">


                        <div class="form-group col-md-12">

                            <label class="col-md-6 control-label">Highest grade completed : </label>
                            <div class="col-md-6">
                                <input type="radio"/>&nbsp;&nbsp;1&nbsp;&nbsp;
                                <input type="radio"/>&nbsp;&nbsp;2&nbsp;&nbsp;
                                <input type="radio"/>&nbsp;&nbsp;3&nbsp;&nbsp;
                                <input type="radio"/>&nbsp;&nbsp;4&nbsp;&nbsp;
                                <input type="radio"/>&nbsp;&nbsp;5&nbsp;&nbsp;
                                <input type="radio"/>&nbsp;&nbsp;6&nbsp;&nbsp;
                                <input type="radio"/>&nbsp;&nbsp;7&nbsp;&nbsp;
                                <input type="radio"/>&nbsp;&nbsp;8&nbsp;&nbsp;
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">High School : </label>
                            <div class="col-md-6">
                                <input type="radio"/>&nbsp;&nbsp;1&nbsp;&nbsp;
                                <input type="radio"/>&nbsp;&nbsp;2&nbsp;&nbsp;
                                <input type="radio"/>&nbsp;&nbsp;3&nbsp;&nbsp;
                                <input type="radio"/>&nbsp;&nbsp;4&nbsp;&nbsp;
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">College : </label>
                            <div class="col-md-6">
                                <input type="radio"/>&nbsp;&nbsp;1&nbsp;&nbsp;
                                <input type="radio"/>&nbsp;&nbsp;2&nbsp;&nbsp;
                                <input type="radio"/>&nbsp;&nbsp;3&nbsp;&nbsp;
                                <input type="radio"/>&nbsp;&nbsp;4&nbsp;&nbsp;
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">Last School attended : </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" />
                            </div>
                        </div>




                    </td></tr>
                <tr><td>Signature:<input type="text" class="form-control"/></td><td>Date/Time:<input type="text" class="form-control" /></td></tr>

    </table>
</div>
<div id="more_edu"></div>
<div id="add_more_edu">
    <p>&nbsp;</p>
    <a href="javascript:void(0);" class="btn btn-primary add_more_edu">Add More</a>
</div>
<div class="form-group col-md-12">
    <label class="control-label col-md-3">Attach Files : </label>
    <div class="col-md-9">
    <a href="javascript:void(0);" class="btn btn-primary">Browse</a>
    </div>
    </div>

    <div class="form-group col-md-12">
    <div id="more_edu_doc">
    </div>
    </div>

    <div class="form-group col-md-12">
    <div class="col-md-3">
    </div>
    <div class="col-md-9">
        <a href="javascript:void(0);" class="btn btn-primary" id="add_more_edu_doc">Add More</a>
    </div>
    </div>

    <div class="clearfix"></div>
</div>
</div>
</div>

<script>
$(function(){
  $(".add_more_edu").click(function(){
    $.ajax({
       url:"<?php echo $this->request->webroot;?>subpages/past_education.php",
       success:function(res){
        $("#more_edu").append(res);
       }
    });
  });
  $("#delete").live("click",function(){
    $(this).parent().parent().remove();
  });

  $('#add_more_edu_doc').click(function(){
        $('#more_edu_doc').append('<div class="del_append_edu"><label class="control-label col-md-3">Attach File: </label><div class="col-md-6 pad_bot"><a href="javascript:void(0);" class="btn btn-primary">Browse</a><a  href="javascript:void(0);" class="btn btn-danger" id="delete_edu_doc">Delete</a></div></div><div class="clearfix"></div>')
       });

       $('#delete_edu_doc').live('click',function(){
            $(this).closest('.del_append_edu').remove();
       });

 });
</script>