<?php
 if($this->request->session()->read('debug'))
        echo "<span style ='color:red;'>ptype.php #INC126</span>";
 ?>
<div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-briefcase"></i>Profile Types
        </div>
    </div>
    <div class="portlet-body">
   
        <div class="table-scrollable">
            <form action="" class="ptypeform">
            <table
                class="table table-condensed  table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr >
                    <th>Id</th>
                    <th>Title</th>
                    <th>Enable</th>
                    

                </tr>
                </thead>
                <tbody class="allpt">
                <?php
                $pt = explode(",",$profile->ptypes);
                foreach($ptypes as $product)
                {
                    ?>
                    <tr>
                        <td><?php echo $product->id;?></td>
                        <td class="titleptype_<?php echo $product->id;?>"><?php echo $product->title;?></td>
                        <td><input name="ptypes[]" type="checkbox" <?php  if(in_array($product->id,$pt)){echo "checked='checked'";}?> class="cenable" id="cchk_<?php echo $product->id;?>" value="<?php echo $product->id;?>" /></td>
                        
                    </tr>        
                <?php
                }
                ?>
                 <tr>
                    <td></td>
                    <td></td>
                    <td><a href="javascript:;" class="btn btn-primary" id="saveptype" >Submit</a></td>
                </tr>
        </tbody>
        </table>
        </form>
        <div class="margin-top-10 alert alert-success display-hide ptype"
                                                     style="display: none;">Data Saved
                                                    <button class="close" data-close="alert"></button>
                                                   
                                                </div>
    </div>
    </div>
</div>
<script>

$(function(){
    $('#saveptype').live('click',function(){
        $(this).text("Saving");
        var cids =$('.ptypeform input[type="checkbox"]').serialize();
        var id = <?php echo $id;?>;
        $.ajax({
            url:"<?php echo $this->request->webroot;?>profiles/ptypesenb/"+id,
            type:"post",
            dataType:"HTML",
            data: cids,
            success:function(msg)
            {
                $('.ptype').show();
                $('.ptype').fadeOut(7000);
                $('#saveptype').text('Submit');
            }
        })
    });
    
});
</script>