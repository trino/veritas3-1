<?php
 if($this->request->session()->read('debug')) {
     echo "<span style ='color:red;'>subpages/profile/profile_types.php #INC125</span>";
 }
 ?>
<div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-briefcase"></i>Profile Types
        </div>
    </div>
    <div class="portlet-body">
    <div style="float: right; margin-bottom: 5px;" class="col-md-6">
        
        <a href="javascript:;" class="btn btn-primary apt" style="float: right;" onclick="$(this).hide();$('.addptype').show();">Add Profile Type</a>
        <div class="addptype" style="display: none;">
            <span class="col-md-4"><input type="text" class="form-control"  placeholder="Title" id="titptype_0"/></span>
            <span class="col-md-4"><input type="text" class="form-control"  placeholder="Title" id="titptypeFrench_0"/></span>
            <span class="col-md-3"><a href="javascript:;" id="0" class="btn btn-primary saveptypes">Add</a></span>
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
                    <th>Can Order</th>
                    <th>Actions</th>

                </tr>
                </thead>
                <tbody class="allpt">
                <?php
                $i = 1;
                foreach($ptypes as $product)
                {?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td class="titleptype_<?= $product->id;?>"><?php echo $product->title;?></td>
                        <td class="titleptypeFrench_<?= $product->id;?>"><?php echo $product->titleFrench;?></td>
                        <td><input type="checkbox" <?php if($product->enable=='1'){echo "checked='checked'";}?> class="penable" id="pchk_<?= $product->id;?>" /><span class="span_<?= $product->id;?>"></span></td>
                        <!--php if($product->id != 1 && $product->id != 2 && $product->id != 5 && $product->id != 7 && $product->id != 8  && $product->id != 11) {?>-->
                        <td><input type="checkbox" <?php if($product->placesorders=='1'){echo "checked='checked'";}?> class="oenable" id="ochk_<?= $product->id;?>" /><span class="span2_<?= $product->id;?>"></span></td>
                        <td><a href="javascript:;" class="btn btn-info editptype" id="editptype_<?php echo $product->id;?>">Edit</a></td>
                    </tr>        
                <?php
                $i++;
                }
                ?>
        </tbody>
        </table>
        
    </div>
    </div>
</div>
<script>

$(function(){
    $('.editptype').live('click', function(){
        
        var id = $(this).attr('id').replace("editptype_","");
        var va = $('.titleptype_'+id).text();
        var vaFrench = $('.titleptypeFrench_'+id).text();

        $('.titleptype_'+id).html('<input type="text" value="'+va+'" class="form-control" id="titptype_'+id+'" /><a class="btn btn-primary saveptypes" id ="ptypesave_'+id+'" >save</a> ');
        $('.titleptypeFrench_'+id).html('<input type="text" value="'+vaFrench+'" class="form-control" id="titptypeFrench_'+id+'" /> ');

    });
    $('.saveptypes').live('click',function(){
        var id = $(this).attr('id').replace("ptypesave_","");
        var title = $('#titptype_'+id).val();
        var titleFrench = $('#titptypeFrench_'+id).val();
        $.ajax({
            url:"<?php echo $this->request->webroot;?>profiles/ptypes/"+id,
            type:"post",
            dataType:"HTML",
            data: "title=" + title + "&titleFrench=" + titleFrench,
            success:function(msg) {
                if(id!=0) {
                    $('.titleptype_' + id).html(msg);
                    $('.titleptypeFrench_' + id).html(titleFrench);
                }else {
                    $('.allpt').append(msg);
                    $('.addptype').hide();
                    $('.apt').show();
                    $('#titptype_0').val("");
                    $('#titptypeFrench_0').val("");
                }
            }
        })
    });
    $('.penable').live('click',function(){
        var enb = "0";
        var ids = $(this).attr('id');
        var id =  ids.replace("pchk_","");
        if($(this).is(":checked")) {enb = "1";}
        $.ajax({
            url:"<?php echo $this->request->webroot;?>profiles/ptypesenable/"+id,
            data:"enable="+enb,
            type:"post",
            success: function(msg){
                $('.span_'+id).html(msg);
                $('.span_'+id).show();
                $('.span_'+id).fadeOut(2000);
            }
         })
    });
    $('.oenable').live('click',function(){
        var enb = "0";
        var ids = $(this).attr('id');
        var id =  ids.replace("ochk_","");
        if($(this).is(":checked")) {enb = "1";}
        $.ajax({
            url:"<?php echo $this->request->webroot;?>profiles/ptypesenable/"+id + "/placesorders",
            data:"enable="+enb,
            type:"post",
            success: function(msg){
                $('.span2_'+id).html(msg);
                $('.span2_'+id).show();
                $('.span2_'+id).fadeOut(2000);
            }
        })
    });
})
</script>