<?php if(isset($dx)){ echo '<h3>' . $dx->title . '</h3>'; }?>
<div class="allattach">
<?php
    echo '<div>';
    if ($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/documents/attach_doc.php #INC132</span>"; }

    $delete = isset($disabled);
    //var_dump($attachments);
        if (isset($attachments)) {
            include_once 'subpages/filelist.php';
            listfiles($attachments, "attachments/", 'attach_doc', $delete, 3);
        }
        if (!isset($disabled)) {

            $upload_max_size = ini_get('upload_max_filesize');
            echo "<h3>Attachments</h3><br/>The largest file you can upload is " . $upload_max_size;
            ?>
            <div class="form-group col-md-12">

                <div class="docMore <?php echo $addmoreid;?>" data-count="1">
                    <div class="col-md-12">
                        <div style="display:block; padding:15px 0;">
                            <a href="javascript:void(0)" id="<?php echo $addmoreid;?>" class="btn btn-primary">Browse</a>
                            <input type="hidden" name="attach_doc[]" value="" class="<?php echo $addmoreid;?>_doc moredocs"/>
                            <a href="javascript:void(0);" class="btn btn-danger img_delete" id="delete_<?php echo $addmoreid;?>"
                               title="" style="display: none;">Delete</a>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12">
                <div class="col-md-12">
                    <a href="javascript:void(0)" class="btn btn-info addMoredoc" onclick="addmoredoc('<?php echo $addmoreid;?>');">
                        Add More
                    </a>
                </div>
            </div>
        <?php }
    echo '</div>';
?>
</div>