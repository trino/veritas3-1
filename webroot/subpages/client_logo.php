<div class="portlet-body form">
    

    <div class="form-body">
        
          
            <div class="col-md-12">
                <h3>Default Logo</h3>
                <img src="<?php echo $this->request->webroot;?>img/clients/<?php echo $settings->client_img;?>" class="default_image img-responsive" width="200px"/>
                <div style="display:block; padding:15px 0;">
                    <a href="javascript:void(0)" id="client_default" class="btn btn-primary"><i class="fa fa-image"></i> Add/Edit Image</a>
                    
                </div>
                <div class="margin-top-10 alert alert-success display-hide flash" style="display: none;">
                    <button class="close" data-close="alert"></button>
                    Image saved successfully
                </div>
                <div class="margin-top-10 alert alert-error display-hide flash1" style="display: none;">
                    <button class="close" data-close="alert"></button>
                    Error, Image couldnot be saved.
                </div>
            </div>
          
    </div>
</div>
<div class="clearfix"></div>
