<?php
 if($this->request->session()->read('debug'))
        echo "<span style ='color:red;'>ctypes.php #INC119</span>";
 ?>
<div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-briefcase"></i>Client Types
        </div>
    </div>
    <div class="portlet-body">
    
        <div class="table-scrollable">
            <form action="" class="ctypeform">
            <table
                class="table table-condensed  table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr >
                    <th>Id</th>
                    <th>Title</th>
                    <th>Enable</th>
                   

                </tr>
                </thead>
                <tbody class="allct">
                
                <?php
                $ct = explode(",",$profile->ctypes);
                foreach($client_types as $product)
                {
                    ?>
                    <tr>
                        <td><?php echo $product->id;?></td>
                        <td class="titlectype_<?php echo $product->id;?>"><?php echo $product->title;?></td>
                        <td><input name="ctypes[]" type="checkbox" <?php if(in_array($product->id,$ct)){echo "checked='checked'";}?> class="cenable" id="cchk_<?php echo $product->id;?>" value="<?php echo $product->id;?>" /></td>
                        
                    </tr>        
                <?php
                }
                ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td><a href="javascript:;" class="btn btn-primary" id="savectype" >Submit</a></td>
                </tr>
               
        </tbody>
        </table>
         </form>
         <div class="margin-top-10 alert alert-success display-hide ctype"
                                                     style="display: none;">
                                                     Data Saved
                                                    <button class="close" data-close="alert"></button>
                                                   
                                                </div>
    </div>
    </div>
</div>
<script>

$(function(){
    $('#savectype').live('click',function(){
        $(this).text("Saving");
        var cids =$('.ctypeform input[type="checkbox"]').serialize();
        var id = <?php echo $id;?>;
        $.ajax({
            url:"<?php echo $this->request->webroot;?>profiles/ctypesenb/"+id,
            type:"post",
            dataType:"HTML",
            data: cids,
            success:function(msg)
            {
                $('.ctype').show();
                $('.ctype').fadeOut(7000);
                $('#savectype').text('Submit');
            }
        })
    });
    
});
</script>