<?php
 if($this->request->session()->read('debug'))
        echo "<span style ='color:red;'>client_types.php #INC118</span>";
 ?>
<div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-briefcase"></i>Client Types
        </div>
    </div>
    <div class="portlet-body">
    <div style="float: right; margin-bottom: 5px;" class="col-md-6">
        
        <a href="javascript:;" class="btn btn-primary act" style="float: right;" onclick="$(this).hide();$('.addctype').show();">Add Client Type</a>
        <div class="addctype" style="display: none;">
            <span class="col-md-4"><input type="text" class="form-control"  placeholder="Title (English)" id="titctype_0"/></span>
            <span class="col-md-4"><input type="text" class="form-control"  placeholder="Title (French)" id="titctypeFrench_0"/></span>
            <span class="col-md-3"><a href="javascript:;" id="0" class="btn btn-primary savectypes">Add</a></span>
        </div>
    </div>
        <div class="table-scrollable">
        
            <table
                class="table table-condensed  table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr >
                    <th>Id</th>
                    <th>Title (English)</th>
                    <th>Title (French)</th>
                    <th>Enable</th>
                    <th>Actions</th>

                </tr>
                </thead>
                <tbody class="allct">
                <?php
                foreach($client_types as $product)
                {?>
                    <tr>
                        <td><?php echo $product->id;?></td>
                        <td class="titlectype_<?php echo $product->id;?>"><?php echo $product->title;?></td>
                        <td class="titlectypeFrench_<?php echo $product->id;?>"><?php echo $product->titleFrench;?></td>
                        <td><input type="checkbox" <?php if($product->enable=='1'){echo "checked='checked'";}?> class="cenable" id="cchk_<?php echo $product->id;?>" /><span class="span_<?php echo $product->id;?>"></span></td>
                        <td><a href="javascript:;" class="btn btn-primary editctype" id="editctype_<?php echo $product->id;?>">Edit</a></td>
                    </tr>        
                <?php
                }
                ?>
        </tbody>
        </table>
        
    </div>
    </div>
</div>
<script>

$(function(){
    $('.editctype').live('click', function(){
        var id = $(this).attr('id').replace("editctype_","");
        var va = $('.titlectype_'+id).text();
        var vaFrench = $('.titlectypeFrench_'+id).text();
        $('.titlectype_'+id).html('<input type="text" value="'+va+'" class="form-control" id="titctype_'+id+'" /><a class="btn btn-primary savectypes" id ="ctypesave_'+id+'" >save</a> ');
        $('.titlectypeFrench_'+id).html('<input type="text" value="'+vaFrench+'" class="form-control" id="titctypeFrench_'+id+'" /> ');
    });
    $('.savectypes').live('click',function(){
        var id = $(this).attr('id').replace("ctypesave_","");
        var title = $('#titctype_'+id).val();
        var titleFrench = $('#titctypeFrench_'+id).val();
        $.ajax({
            url:"<?php echo $this->request->webroot;?>profiles/ctypes/"+id,
            type:"post",
            dataType:"HTML",
            data: "title=" + title + "&titleFrench="+titleFrench,
            success:function(msg) {
                if(id!=0) {
                    $('.titlectype_' + id).html(msg);
                    $('.titlectypeFrench_' + id).html(titleFrench);
                } else {
                    $('.allct').append(msg);
                    $('.addctype').hide();
                    $('.act').show();
                    $('#titctype_0').val("");
                    $('#titctypeFrench_0').val("");
                }
            }
        })
    });
    $('.cenable').live('click',function(){
        var enb = "";
        var ids = $(this).attr('id');
        var id =  ids.replace("cchk_","");
       //alert(id);
       if($(this).is(":checked")) {
           enb = "1";
       }else {
           enb = "0";
       }

      $.ajax({
        url:"<?php echo $this->request->webroot;?>profiles/ctypesenable/"+id,
        data:"enable="+enb,
        type:"post",
        success: function(msg){
            $('.span_'+id).html(msg);
            $('.span_'+id).show();
            $('.span_'+id).fadeOut(2000);
        }
      })      
            
    });
})
</script>