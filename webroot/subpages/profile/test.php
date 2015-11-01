<!-- BEGIN FORM-->
<div class="tab-pane active" id="subtab_1_1">
    <div class="portlet box">
        <div class="portlet-body form">
            <?php $cms = $this->requestAction("/pages/get_content/product_example");?>
            <form action="<?php echo $this->request->webroot;?>pages/edit/product_example" method="post" class="form-horizontal form-bordered" id="product_example">
                <div class="form-body">
                    <div class="form-group last">
                        <label class="control-label col-md-2">Page Title<BR><small>(Product Example)</small></label>

                        <div class="col-md-4">
                            <input class="form-control" name="title" id="title-product_example"
                                   value="<?php echo $cms->title;?>"/>
                        </div>
                    </div>
                </div>
                <div class="form-body">
                    <div class="form-group last">
                        <label class="control-label col-md-2">Description</label>

                        <div class="col-md-9">
                                                                    <textarea class="ckeditor form-control"
                                                                              name="editor1" rows="6" id="descproduct_example"><?php echo $cms->desc;?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-9">
                            <button type="submit"   class="btn blue" onclick="savepage('product_example');"><i
                                    class="fa fa-check"></i> Submit
                            </button>
                            <button type="button" class="btn default">Cancel
                            </button>
                        </div>
                    </div>

                </div>
            </form></div></div></div>
<!-- END FORM-->