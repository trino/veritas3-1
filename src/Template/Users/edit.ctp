
<?php
if(isset($disabled))
$is_disabled = 'disabled="disabled"';
else
$is_disabled = '';
?>
<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Modal title</h4>
						</div>
						<div class="modal-body">
							 Widget settings form goes here
						</div>
						<div class="modal-footer">
							<button type="button" class="btn blue">Save changes</button>
							<button type="button" class="btn default" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN STYLE CUSTOMIZER -->
			<div class="theme-panel hidden-xs hidden-sm">
				<div class="toggler">
				</div>
				<div class="toggler-close">
				</div>
				<div class="theme-options">
					<div class="theme-option theme-colors clearfix">
						<span>
						THEME COLOR </span>
						<ul>
							<li class="color-default current tooltips" data-style="default" onclick="change_layout('default');" data-container="body" data-original-title="Default">
							</li>
							<li class="color-darkblue tooltips" data-style="darkblue" onclick="change_layout('darkblue');" data-container="body" data-original-title="Dark Blue">
							</li>
							<li class="color-blue tooltips" data-style="blue" onclick="change_layout('blue');" data-container="body" data-original-title="Blue">
							</li>
							<li class="color-grey tooltips" data-style="grey" onclick="change_layout('grey');" data-container="body" data-original-title="Grey">
							</li>
							<li class="color-light tooltips" data-style="light" onclick="change_layout('light');" data-container="body" data-original-title="Light">
							</li>
							<li class="color-light2 tooltips" data-style="light2" onclick="change_layout('light2');" data-container="body" data-html="true" data-original-title="Light 2">
							</li>
						</ul>
					</div>
					<div class="theme-option">
						<span>
						Theme Style </span>
						<select class="layout-style-option form-control input-sm" >
							<option value="square" selected="selected">Square corners</option>
							<option value="rounded">Rounded corners</option>
						</select>
					</div>
					<div class="theme-option">
						<span>
						Layout </span>
						<select class="layout-option form-control input-sm" onchange="change_body();">
							<option value="fluid" selected="selected">Fluid</option>
							<option value="boxed">Boxed</option>
						</select>
					</div>
					<div class="theme-option">
						<span>
						Header </span>
						<select class="page-header-option form-control input-sm" onchange="change_body();">
							<option value="fixed" selected="selected">Fixed</option>
							<option value="default">Default</option>
						</select>
					</div>
					<div class="theme-option">
						<span>
						Top Menu Dropdown</span>
						<select class="page-header-top-dropdown-style-option form-control input-sm" onchange="change_body();">
							<option value="light" selected="selected">Light</option>
							<option value="dark">Dark</option>
						</select>
					</div>
					<div class="theme-option">
						<span>
						Sidebar Mode</span>
						<select class="sidebar-option form-control input-sm" onchange="change_body();">
							<option value="fixed">Fixed</option>
							<option value="default" selected="selected">Default</option>
						</select>
					</div>
					<div class="theme-option">
						<span>
						Sidebar Menu </span>
						<select class="sidebar-menu-option form-control input-sm" onchange="change_body();">
							<option value="accordion" selected="selected">Accordion</option>
							<option value="hover">Hover</option>
						</select>
					</div>
					<div class="theme-option">
						<span>
						Sidebar Style </span>
						<select class="sidebar-style-option form-control input-sm" onchange="change_body();">
							<option value="default" selected="selected">Default</option>
							<option value="light">Light</option>
						</select>
					</div>
					<div class="theme-option">
						<span>
						Sidebar Position </span>
						<select class="sidebar-pos-option form-control input-sm" onchange="change_body();">
							<option value="left" selected="selected">Left</option>
							<option value="right">Right</option>
						</select>
					</div>
					<div class="theme-option">
						<span>
						Footer </span>
						<select class="page-footer-option form-control input-sm" onchange="change_body();">
							<option value="fixed">Fixed</option>
							<option value="default" selected="selected">Default</option>
						</select>
					</div>
				</div>
			</div>
            <div class="clearfix"></div>

			<!-- END STYLE CUSTOMIZER -->
			<!-- BEGIN PAGE HEADER-->
            <h3 class="page-title">
			User Manager
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo $this->request->webroot;?>">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="">Profile</a>
					</li>
				</ul>

			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row margin-top-20">
				<div class="col-md-12">
					<!-- BEGIN PROFILE SIDEBAR -->
					<div class="profile-sidebar">
						<!-- PORTLET MAIN -->
						<div class="portlet light profile-sidebar-portlet">
							<!-- SIDEBAR USERPIC -->
							<div class="profile-userpic">
								<img src="<?php echo $this->request->webroot;?>img/profile/male.png" class="img-responsive" alt="">
							</div>
							<!-- END SIDEBAR USERPIC -->
							<!-- SIDEBAR USER TITLE -->
							<div class="profile-usertitle">
								<div class="profile-usertitle-name">
									 Marcus Doe
								</div>
								<div class="profile-usertitle-job">
									 Reference Number: 1
								</div>
							</div>

						</div>
						<!-- END PORTLET MAIN -->
						<!-- PORTLET MAIN -->
						<div class="portlet box blue">
                            <div class="portlet-title">
                                <div class="caption">Client</div>
                            </div>
                            <div class="portlet-body">
								<div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample client 1</a></div>
                                <div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample client 1</a></div>
                                <div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample client 1</a></div>
                                <div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample client 1</a></div>
                                <div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample client 1</a></div>
                                <div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample client 1</a></div>
                                <div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample client 1</a></div>
                                <div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample client 1</a></div>
								<div class="clearfix"></div>
							</div>
                        </div>
                            <hr />
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <div class="caption">Orders</div>
                            </div>
                            <div class="portlet-body">
								<div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample Order 1</a></div>
                                <div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample Order 1</a></div>
                                <div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample Order 1</a></div>
                                <div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample Order 1</a></div>
                                <div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample Order 1</a></div>
                                <div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample Order 1</a></div>
                                <div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample Order 1</a></div>
                                <div class="profile-desc-text col-sm-6 nopad"><a href="#">Sample Order 1</a></div>

                                <a href="<?php echo $this->request->webroot;?>documents/add" class="btn btn-warning margin-top-10">
                                    Submit Order </a>
                                <div class="clearfix"></div>

                            </div>
                        </div>
						<!-- END PORTLET MAIN -->
					</div>
					<!-- END BEGIN PROFILE SIDEBAR -->
					<!-- BEGIN PROFILE CONTENT -->
					<div class="profile-content">
						<div class="row">
							<div class="col-md-12">
								<div class="portlet light">
									<div class="portlet-title tabbable-line">
										<div class="caption caption-md">
											<i class="icon-globe theme-font hide"></i>
											<span class="caption-subject font-blue-madison bold">Profile</span>
										</div>
										<ul class="nav nav-tabs">
											<li class="active">
												<a href="#tab_1_1" data-toggle="tab">Info</a>
											</li>
                                            <?php
                                            if(!isset($disabled))
                                            {
                                                ?>

											<li>
												<a href="#tab_1_2" data-toggle="tab">Avatar</a>
											</li>
											<li>
												<a href="#tab_1_3" data-toggle="tab">Password</a>
											</li>
                                            <?php
                                            }
                                            ?>
											<li>
												<a href="#tab_1_4" data-toggle="tab">Permissions</a>
											</li>
                                            <li>
												<a href="#tab_1_5" data-toggle="tab">Logos</a>
											</li>

                                            <li>
												<a href="#tab_1_6" data-toggle="tab">Pages</a>
											</li>
										</ul>
									</div>
									<div class="portlet-body">
										<div class="tab-content">
											<!-- PERSONAL INFO TAB -->
											<div class="tab-pane active" id="tab_1_1">
												<form role="form" action="#">
                                                    <div class="form-group">
														<label class="control-label"><?php echo ucfirst($settings->profile); ?> Type</label>
														<select <?php echo $is_disabled?> class="form-control member_type">
                                                            <option value="Admin">Admin</option>
                                                            <option value="Member">Member</option>
                                                            <option value="Contact">Contact</option>
                                                        </select>
													</div>
                                                    <div class="form-group">
														<label class="control-label">Title</label>
														<input <?php echo $is_disabled?> type="text" placeholder="eg. Mr" class="form-control"/>
													</div>
													<div class="form-group">
														<label class="control-label">First Name</label>
														<input <?php echo $is_disabled?> type="text" placeholder="eg. John" class="form-control"/>
													</div>
													<div class="form-group">
														<label class="control-label">Last Name</label>
														<input <?php echo $is_disabled?> type="text" placeholder="eg. Doe" class="form-control"/>
													</div>
                                                    <div class="form-group">
														<label class="control-label">Email</label>
														<input <?php echo $is_disabled?> type="text" placeholder="eg. test@domain.com" class="form-control"/>
													</div>
													<div class="form-group">
														<label class="control-label">Phone Number</label>
														<input <?php echo $is_disabled?> type="text" placeholder="eg. +1 646 580 6284" class="form-control"/>
													</div>
													<div class="form-group">
														<label class="control-label">Address</label>
														<input <?php echo $is_disabled?> type="text" placeholder="eg. Street, City, Province, Country" class="form-control"/>
													</div>
                                                    <div class="form-group">
														<label class="control-label">Make quick contact</label>
														<input <?php echo $is_disabled?> type="checkbox" onchange="if($(this).is(':checked'))$('#tab_1_3 input').attr('disabled','disabled');else{$('#tab_1_3 input').removeAttr('disabled');}" class="form-control"/>
													</div>
													<?php
                                                    if(!isset($disabled))
                                                    {
                                                        ?>
													<div class="margiv-top-10">
														<a href="#" class="btn btn-primary">
														Save Changes </a>
														<a href="#" class="btn default">
														Cancel </a>
													</div>
                                                    <?php }?>
												</form>
											</div>
											<!-- END PERSONAL INFO TAB -->
											<!-- CHANGE AVATAR TAB -->
											<div class="tab-pane" id="tab_1_2">
												<p>
													 Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
												</p>
												<form action="#" role="form">
													<div class="form-group">
														<div class="fileinput fileinput-new" data-provides="fileinput">
															<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
																<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""/>
															</div>
															<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
															</div>
															<div>
																<span class="btn default btn-file">
																<span class="fileinput-new">
																Select image </span>
																<span class="fileinput-exists">
																Change </span>
																<input type="file" name="...">
																</span>
																<a href="#" class="btn default fileinput-exists" data-dismiss="fileinput">
																Remove </a>
															</div>
														</div>

													</div>
													<div class="margin-top-10">
														<a href="#" class="btn btn-primary">
														Submit </a>
														<a href="#" class="btn default">
														Cancel </a>
													</div>
												</form>
											</div>
											<!-- END CHANGE AVATAR TAB -->
											<!-- CHANGE PASSWORD TAB -->
											<div class="tab-pane" id="tab_1_3">
												<form action="#">
													<div class="form-group">
														<label class="control-label">Current Password</label>
														<input type="password" class="form-control"/>
													</div>
													<div class="form-group">
														<label class="control-label">New Password</label>
														<input type="password" class="form-control"/>
													</div>
													<div class="form-group">
														<label class="control-label">Re-type New Password</label>
														<input type="password" class="form-control"/>
													</div>
													<div class="margin-top-10">
														<a href="#" class="btn btn-primary">
														Change Password </a>
														<a href="#" class="btn default">
														Cancel </a>
													</div>
												</form>
											</div>
											<!-- END CHANGE PASSWORD TAB -->
											<!-- PRIVACY SETTINGS TAB -->
											<div class="tab-pane" id="tab_1_4">
												<form action="#">
													<table class="table table-light table-hover">
													<tr>
														<td>
															 Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus..
														</td>
														<td>
															<label class="uniform-inline">
															<input <?php echo $is_disabled?> type="radio" name="optionsRadios1" value="option1"/>
															Yes </label>
															<label class="uniform-inline">
															<input <?php echo $is_disabled?> type="radio" name="optionsRadios1" value="option2" checked/>
															No </label>
														</td>
													</tr>
													<tr>
														<td>
															 Enim eiusmod high life accusamus terry richardson ad squid wolf moon
														</td>
														<td>
															<label class="uniform-inline">
															<input <?php echo $is_disabled?> type="checkbox" value=""/> Yes </label>
														</td>
													</tr>
													<tr>
														<td>
															 Enim eiusmod high life accusamus terry richardson ad squid wolf moon
														</td>
														<td>
															<label class="uniform-inline">
															<input <?php echo $is_disabled?> type="checkbox" value=""/> Yes </label>
														</td>
													</tr>
													<tr>
														<td>
															 Enim eiusmod high life accusamus terry richardson ad squid wolf moon
														</td>
														<td>
															<label class="uniform-inline">
															<input <?php echo $is_disabled?> type="checkbox" value=""/> Yes </label>
														</td>
													</tr>
													</table>
													<!--end profile-settings-->
                                                    <?php
                                                    if(!isset($disabled))
                                                    {
                                                        ?>

													<div class="margin-top-10">
														<a href="#" class="btn btn-primary">
														Save Changes </a>

													</div>
                                                    <?php
                                                    }
                                                    ?>
												</form>
											</div>
                                            <div class="tab-pane" id="tab_1_5">
                                                <div>
                                                <ul class="nav nav-tabs">
        											<li class="active">
        												<a href="#subtab_1_1" data-toggle="tab">Primary Logo</a>
        											</li>
        											<li>
        												<a href="#subtab_1_2" data-toggle="tab">Secondary Logo</a>
        											</li>

        										</ul>
                                                </div>
                                                <div class="tab-content">
                                                <div class="tab-pane active" id="subtab_1_1">
                                                    <div class="portlet ">
                                                			<div class="portlet-title">
                                                				<div class="caption">
                                                					<i class="fa fa-user"></i>Choose A Primary Logo
                                                				</div>

                                                			</div>
                                                			<div class="portlet-body">

                                                            <form action="<?php echo $this->request->webroot;?>logos" method="post" class="form-inline" role="form" >
                                                            <?php foreach ($logos as $logo){ ?>
                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-1">
                                                                        <input type="radio" value="<?php echo $logo->id;?>" name="logo" <?php echo ($logo->active == '1')?"checked='checked'":"" ;?>/>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <img src="<?php echo $this->request->webroot;?>img/logos/<?php echo $logo->logo;?>" width="86px" height="14px" />
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <hr />

                                                            <?php }?>
                                                            <input type="submit" class="btn btn-success" value="submit" name="submit" />
                                                            </form>

                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="tab-pane" id="subtab_1_2">
                                                    <div class="portlet ">
                                                			<div class="portlet-title">
                                                				<div class="caption">
                                                					<i class="fa fa-user"></i>Choose A Secondary Logo
                                                				</div>

                                                			</div>
                                                			<div class="portlet-body">

                                                            <form action="<?php echo $this->request->webroot;?>logos/secondary" method="post" class="form-inline" role="form" >
                                                            <?php foreach ($logos1 as $logo){ ?>
                                                                <div class="form-group col-md-12">
                                                                    <div class="col-md-1">
                                                                        <input type="radio" value="<?php echo $logo->id;?>" name="logo" <?php echo ($logo->active == '1')?"checked='checked'":"" ;?>/>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <img src="<?php echo $this->request->webroot;?>img/logos/<?php echo $logo->logo;?>" width="86px" height="14px" />
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <hr />

                                                            <?php }?>
                                                            <input type="submit" class="btn btn-success" value="submit" name="submit" />
                                                            </form>


                                                </div>
                                                </div>
                                                </div>

											</div>
                                            </div>


                                            <div class="tab-pane" id="tab_1_6">
                                                <div>
                                                <ul class="nav nav-tabs">


                                                    <li class="active">
        												<a href="#subtab_1_5" data-toggle="tab">Product Example</a>
        											</li>
                                                    <li class="">
                                                        <a href="#subtab_1_6" data-toggle="tab">Help</a>
                                                    </li>
                                                    <li>
                                                        <a href="#subtab_1_4" data-toggle="tab">Privacy Code</a>
                                                    </li>
        											<li>
        												<a href="#subtab_1_6" data-toggle="tab">Terms</a>
        											</li>
                                                    <li class="">
        												<a href="#subtab_1_7" data-toggle="tab">FAQ</a>
        											</li>

													<li class="">
														<a href="#subtab_1_8" data-toggle="tab">Version Log</a>
													</li>
        										</ul>
                                                </div>
                                                <div class="tab-content">
                                                <div class="tab-pane active" id="subtab_1_3">
                                                    <div class="portlet box blue">
                                						<div class="portlet-title">
                                							<div class="caption">
                                								<i class="fa fa-gift"></i>Page Manager - Help
                                							</div>

                                						</div>

                                						<div class="portlet-body form">
                                							<!-- BEGIN FORM-->
                                							<form action="#" class="form-horizontal form-bordered">
                                                                <div class="form-body">
                                									<div class="form-group last">
                                										<label class="control-label col-md-3">Page Title</label>
                                										<div class="col-md-4">
                                											<input class="form-control" name="title" value="Help" />
                                										</div>
                                									</div>
                                								</div>
                                								<div class="form-body">
                                									<div class="form-group last">
                                										<label class="control-label col-md-3">Description</label>
                                										<div class="col-md-9">
                                											<textarea class="ckeditor form-control" name="editor1" rows="6"></textarea>
                                										</div>
                                									</div>
                                								</div>
                                								<div class="form-actions">
                                									<div class="row">
                                										<div class="col-md-offset-3 col-md-9">
                                											<button type="submit" class="btn blue"><i class="fa fa-check"></i> Submit</button>
                                											<button type="button" class="btn default">Cancel</button>
                                										</div>
                                									</div>
                                								</div>
                                							</form>
                                							<!-- END FORM-->
                                						</div>
                                					</div>

                                                </div>
                                                <div class="tab-pane" id="subtab_1_4">
                                                    <div class="portlet box blue">
                                						<div class="portlet-title">
                                							<div class="caption">
                                								<i class="fa fa-gift"></i>Page Manager - Privacy Code
                                							</div>

                                						</div>

                                						<div class="portlet-body form">
                                							<!-- BEGIN FORM-->
                                							<form action="#" class="form-horizontal form-bordered">
                                                                <div class="form-body">
                                									<div class="form-group last">
                                										<label class="control-label col-md-3">Page Title</label>
                                										<div class="col-md-4">
                                											<input class="form-control" name="title" value="Privacy code" />
                                										</div>
                                									</div>
                                								</div>
                                								<div class="form-body">
                                									<div class="form-group last">
                                										<label class="control-label col-md-3">Description</label>
                                										<div class="col-md-9">
                                											<textarea class="ckeditor form-control" name="editor1" rows="6"></textarea>
                                										</div>
                                									</div>
                                								</div>
                                								<div class="form-actions">
                                									<div class="row">
                                										<div class="col-md-offset-3 col-md-9">
                                											<button type="submit" class="btn blue"><i class="fa fa-check"></i> Submit</button>
                                											<button type="button" class="btn default">Cancel</button>
                                										</div>
                                									</div>
                                								</div>
                                							</form>
                                							<!-- END FORM-->
                                						</div>
                                					</div>

                                                </div>

                                                <div class="tab-pane" id="subtab_1_5">
                                                    <div class="portlet box blue">
                                						<div class="portlet-title">
                                							<div class="caption">
                                								<i class="fa fa-gift"></i>Page Manager - Product Example
                                							</div>

                                						</div>

                                						<div class="portlet-body form">
                                							<!-- BEGIN FORM-->
                                							<form action="#" class="form-horizontal form-bordered">
                                                                <div class="form-body">
                                									<div class="form-group last">
                                										<label class="control-label col-md-3">Page Title</label>
                                										<div class="col-md-4">
                                											<input class="form-control" name="title" value="Product example" />
                                										</div>
                                									</div>
                                								</div>
                                								<div class="form-body">
                                									<div class="form-group last">
                                										<label class="control-label col-md-3">Description</label>
                                										<div class="col-md-9">
                                											<textarea class="ckeditor form-control" name="editor1" rows="6"></textarea>
                                										</div>
                                									</div>
                                								</div>
                                								<div class="form-actions">
                                									<div class="row">
                                										<div class="col-md-offset-3 col-md-9">
                                											<button type="submit" class="btn blue"><i class="fa fa-check"></i> Submit</button>
                                											<button type="button" class="btn default">Cancel</button>
                                										</div>
                                									</div>
                                								</div>
                                							</form>
                                							<!-- END FORM-->
                                						</div>
                                					</div>

                                                </div>

                                                <div class="tab-pane" id="subtab_1_6">
                                                    <div class="portlet box blue">
                                						<div class="portlet-title">
                                							<div class="caption">
                                								<i class="fa fa-gift"></i>Page Manager - Terms
                                							</div>

                                						</div>

                                						<div class="portlet-body form">
                                							<!-- BEGIN FORM-->
                                							<form action="#" class="form-horizontal form-bordered">
                                                                <div class="form-body">
                                									<div class="form-group last">
                                										<label class="control-label col-md-3">Page Title</label>
                                										<div class="col-md-4">
                                											<input class="form-control" name="title" value="Terms" />
                                										</div>
                                									</div>
                                								</div>
                                								<div class="form-body">
                                									<div class="form-group last">
                                										<label class="control-label col-md-3">Description</label>
                                										<div class="col-md-9">
                                											<textarea class="ckeditor form-control" name="editor1" rows="6"></textarea>
                                										</div>
                                									</div>
                                								</div>
                                								<div class="form-actions">
                                									<div class="row">
                                										<div class="col-md-offset-3 col-md-9">
                                											<button type="submit" class="btn blue"><i class="fa fa-check"></i> Submit</button>
                                											<button type="button" class="btn default">Cancel</button>
                                										</div>
                                									</div>
                                								</div>
                                							</form>
                                							<!-- END FORM-->
                                						</div>
                                					</div>

                                                </div>

                                                <div class="tab-pane" id="subtab_1_7">
                                                    <div class="portlet box blue">
                                						<div class="portlet-title">
                                							<div class="caption">
                                								<i class="fa fa-gift"></i>Page Manager - FAQ
                                							</div>

                                						</div>

                                						<div class="portlet-body form">
                                							<!-- BEGIN FORM-->
                                							<form action="#" class="form-horizontal form-bordered">
                                                                <div class="form-body">
                                									<div class="form-group last">
                                										<label class="control-label col-md-3">Page Title</label>
                                										<div class="col-md-4">
                                											<input class="form-control" name="title" value="FAQ" />
                                										</div>
                                									</div>
                                								</div>
                                								<div class="form-body">
                                									<div class="form-group last">
                                										<label class="control-label col-md-3">Description</label>
                                										<div class="col-md-9">
                                											<textarea class="ckeditor form-control" name="editor1" rows="6"></textarea>
                                										</div>
                                									</div>
                                								</div>
                                								<div class="form-actions">
                                									<div class="row">
                                										<div class="col-md-offset-3 col-md-9">
                                											<button type="submit" class="btn blue"><i class="fa fa-check"></i> Submit</button>
                                											<button type="button" class="btn default">Cancel</button>
                                										</div>
                                									</div>
                                								</div>
                                							</form>
                                							<!-- END FORM-->
                                						</div>
                                					</div>

                                                </div>





													<div class="tab-pane" id="subtab_1_8">
														<div class="portlet box blue">
															<div class="portlet-title">
																<div class="caption">
																	<i class="fa fa-gift"></i>Page Manager - FAQ
																</div>

															</div>

															<div class="portlet-body form">
																<!-- BEGIN FORM-->
																<form action="#" class="form-horizontal form-bordered">
																	<div class="form-body">
																		<div class="form-group last">
																			<label class="control-label col-md-3">Page Title</label>
																			<div class="col-md-4">
																				<input class="form-control" name="title" value="FAQ" />
																			</div>
																		</div>
																	</div>
																	<div class="form-body">
																		<div class="form-group last">
																			<label class="control-label col-md-3">Description</label>
																			<div class="col-md-9">
																				<textarea class="ckeditor form-control" name="editor1" rows="6"></textarea>
																			</div>
																		</div>
																	</div>
																	<div class="form-actions">
																		<div class="row">
																			<div class="col-md-offset-3 col-md-9">
																				<button type="submit" class="btn blue"><i class="fa fa-check"></i> Submit</button>
																				<button type="button" class="btn default">Cancel</button>
																			</div>
																		</div>
																	</div>
																</form>
																<!-- END FORM-->
															</div>
														</div>

													</div>
											</div>
                                            </div>
											<!-- END PRIVACY SETTINGS TAB -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END PROFILE CONTENT -->
				</div>
			</div>


<script>
$(function(){
    $('.member_type').change(function(){
       if($(this).val()=='Contact')
       {
         $('.nav-tabs li:not(.active)').each(function(){
            $(this).hide();
         });
       }
       else{
        $('.nav-tabs li:not(.active)').each(function(){
            $(this).show();
         });
       }
    });
})
</script>