<link href="<?= $this->request->webroot;?>assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css"/>
<link href="<?= $this->request->webroot;?>assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet"/>
<link href="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css" rel="stylesheet"/>
<link href="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet"/>
<link href="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet"/>
<link href="<?= $this->request->webroot;?>assets/admin/pages/css/inbox.css" rel="stylesheet" type="text/css"/>

<script src="<?= $this->request->webroot;?>assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script>
<script src="<?= $this->request->webroot;?>assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
<script src="<?= $this->request->webroot;?>assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>


<script src="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/js/vendor/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/js/vendor/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js"></script>
<!-- blueimp Gallery script -->
<script src="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="<?= $this->request->webroot;?>assets/global/plugins/jquery-file-upload/js/jquery.fileupload-ui.js"></script>
<script src="<?= $this->request->webroot;?>assets/admin/pages/scripts/inbox.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {
    Inbox.init();
    });
</script>

<h3 class="page-title">
			Inbox <small>user inbox</small>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="index.html">Home</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="#">Pages</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="#">Inbox</a>
					</li>
				</ul>
				<div class="page-toolbar">
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
						Actions <i class="fa fa-angle-down"></i>
						</button>
						<ul class="dropdown-menu pull-right" role="menu">
							<li>
								<a href="#">Action</a>
							</li>
							<li>
								<a href="#">Another action</a>
							</li>
							<li>
								<a href="#">Something else here</a>
							</li>
							<li class="divider">
							</li>
							<li>
								<a href="#">Separated link</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<div class="row inbox">
				<div class="col-md-2">
					<ul class="inbox-nav margin-bottom-10">
						<li class="compose-btn">
							<a href="javascript:;" data-title="Compose" class="btn btn-primary">
							<i class="fa fa-edit"></i> Compose </a>
						</li>
						<li class="inbox active">
							<a href="javascript:;" class="btn" data-title="Inbox">
							Inbox (13) </a>
							<b></b>
						</li>
						<li class="sent">
							<a class="btn" href="javascript:;" data-title="Sent">
							Sent </a>
							<b></b>
						</li>
					</ul>
				</div>
				<div class="col-md-10">
					<div class="inbox-header">
						<h1 class="pull-left">Inbox</h1>
						<form class="form-inline pull-right" action="index.html">
							<div class="input-group input-medium">
								<input type="text" class="form-control" placeholder="Search">
								<span class="input-group-btn">
								<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
								</span>
							</div>
						</form>
					</div>
					<div class="inbox-loading">
						 Loading...
					</div>
					<div class="inbox-content">
					</div>
				</div>
			</div>
