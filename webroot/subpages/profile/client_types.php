<?php
     if($this->request->session()->read('debug')) {
         echo "<span style ='color:red;'>subpages/profiles/client_types.php #INC118</span>";
     }
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
            <?php
                foreach($languages as $language){
                    $Title = getFieldname("titctype", $language);
                    echo '<span class="col-md-4"><input type="text" class="form-control"  placeholder="Title (' . $language . ')" id="' . $Title . '_0"/></span>';
                }
            ?>
            <span class="col-md-3"><a href="javascript:;" id="0" class="btn btn-primary savectypes">Add</a></span>
        </div>
    </div>
        <div class="table-scrollable">
        
            <table
                class="table table-condensed  table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr >
                    <th>Id</th>
                    <?php
                        foreach($languages as $language) {
                            echo '<th>Title (' . $language . ')</th>';
                        }
                    ?>
                    <th>Enable</th>
                    <th>Actions</th>

                </tr>
                </thead>
                <tbody class="allct">
                <?php
                foreach($client_types as $product) {
                    echo '<tr><td>' . $product->id . '</td>';
                    foreach($languages as $language){
                        $Title = getFieldname("titlectype", $language);
                        echo '<td class="' . $Title . '_' . $product->id . '">';
                        $Title = getFieldname("title", $language);
                        echo $product->$Title . '</td>';
                    }
                    ?>
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
        <?= getlanguages($languages, "titlectype", 4); ?>
    });
    $('.savectypes').live('click',function(){
        var id = $(this).attr('id').replace("ctypesave_","");
        <?= getlanguages($languages, "titlectype", 1); ?>
        $.ajax({
            url:"<?php echo $this->request->webroot;?>profiles/ctypes/"+id,
            type:"post",
            dataType:"HTML",
            data: <?= getlanguages($languages); ?>,
            success:function(msg) {
                if(id!=0) {
                    <?= getlanguages($languages, "titlectype", 2); ?>
                } else {
                    $('.allct').append(msg);
                    $('.addctype').hide();
                    $('.act').show();
                    <?= getlanguages($languages, "titlectype", 3); ?>
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