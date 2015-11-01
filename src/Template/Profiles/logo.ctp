<div class="row ">
	<div class="col-md-12">
		<!-- BEGIN SAMPLE FORM PORTLET-->
		<div class="portlet box blue">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-user"></i> Logo Manager
				</div>
				
			</div>
			<div class="portlet-body">

            <form action="" method="post" class="form-inline" role="form" >
                <input type="radio" value="1.png" name="logo"/><img src="<?php echo $this->request->webroot;?>img/logos/1.png" width="86px" height="14px" />
                <input type="radio" value="2.jpg" name="logo"/><img src="<?php echo $this->request->webroot;?>img/logos/2.jpg" width="86px" height="14px"/>
                <input type="radio" value="3.png" name="logo"/><img src="<?php echo $this->request->webroot;?>img/logos/3.png" width="86px" height="14px"/>
                <input type="radio" value="4.png" name="logo"/><img src="<?php echo $this->request->webroot;?>img/logos/4.png" width="86px" height="14px"/>
                <input type="radio" value="5.jpg" name="logo"/><img src="<?php echo $this->request->webroot;?>img/logos/5.jpg" width="86px" height="14px"/>
                <input type="submit" value="Submit" name="submit"/>
            </form>