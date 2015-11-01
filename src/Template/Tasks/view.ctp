<?php $settings = $this->requestAction('settings/get_settings');?>
<h3 class="page-title">
			Schedules <small>(Reminders)</small>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo $this->request->webroot;?>">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="">Schedules
                        </a>
					</li>
				</ul>
                

			</div>
<div class="row">
				<div class="col-md-12">
					<!-- BEGIN TODO SIDEBAR -->
					<div class="todo-ui">
						<div class="todo-sidebar">
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption" data-toggle="collapse" data-target=".todo-project-list-content">
										<span class="caption-subject font-green-sharp bold uppercase">Jobs </span>
										<span class="caption-helper visible-sm-inline-block visible-xs-inline-block">click to view project list</span>
									</div>
									<div class="actions">
										<div class="btn-group">
											<a class="btn green-haze btn-circle btn-sm todo-projects-config" href="#" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
											<i class="icon-settings"></i> &nbsp; <i class="fa fa-angle-down"></i>
											</a>
											<ul class="dropdown-menu pull-right">
												<li>
													<a href="#">
													<i class="i"></i> New Project </a>
												</li>
												<li class="divider">
												</li>
												<li>
													<a href="#">
													Pending <span class="badge badge-danger">
													4 </span>
													</a>
												</li>
												<li>
													<a href="#">
													Completed <span class="badge badge-success">
													12 </span>
													</a>
												</li>
												<li>
													<a href="#">
													Overdue <span class="badge badge-warning">
													9 </span>
													</a>
												</li>
												<li class="divider">
												</li>
												<li>
													<a href="#">
													<i class="i"></i> Archived Jobs </a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="portlet-body todo-project-list-content">
									<div class="todo-project-list">
										<ul class="nav nav-pills nav-stacked">
											<li>
												<a href="#">
												<span class="badge badge-success"> 6 </span> Lorem Ipsum </a>
											</li>
											<li>
												<a href="#">
												<span class="badge badge-success"> 2 </span>  Lorem Ipsum</a>
											</li>
											<li class="active">
												<a href="#">
												<span class="badge badge-success badge-active"> 3 </span>  Lorem Ipsum </a>
											</li>
											<li>
												<a href="#">
												<span class="badge badge-default"> 14 </span> Lorem Ipsum </a>
											</li>
											<li>
												<a href="#">
												<span class="badge badge-success"> 6 </span>  Lorem Ipsum</a>
											</li>
											<li>
												<a href="#">
												<span class="badge badge-success"> 2 </span> Lorem Ipsum </a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<!-- END TODO SIDEBAR -->
						<!-- BEGIN TODO CONTENT -->
						<div class="todo-content">
							<div class="portlet light">
								<!-- PROJECT HEAD -->
								<div class="portlet-title">
									<div class="caption">
										<i class="icon-bar-chart font-green-sharp hide"></i>
										<span class="caption-helper">Job:</span> &nbsp; <span class="caption-subject font-green-sharp bold uppercase"> Lorem Ipsum</span>
									</div>
									<div class="actions">
										<div class="btn-group">
											<a class="btn green-haze btn-circle btn-sm" href="#" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
											MANAGE <i class="fa fa-angle-down"></i>
											</a>
											<ul class="dropdown-menu pull-right">
												<li>
													<a href="#">
													<i class="i"></i> New Task </a>
												</li>
												<li class="divider">
												</li>
												<li>
													<a href="#">
													Pending <span class="badge badge-danger">
													4 </span>
													</a>
												</li>
												<li>
													<a href="#">
													Completed <span class="badge badge-success">
													12 </span>
													</a>
												</li>
												<li>
													<a href="#">
													Overdue <span class="badge badge-warning">
													9 </span>
													</a>
												</li>
												<li class="divider">
												</li>
												<li>
													<a href="#">
													<i class="i"></i> Delete Job </a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<!-- end PROJECT HEAD -->
								<div class="portlet-body">
									<div class="row">
										
										<div class="">
											<div class="scroller" style="max-height: 600px;" data-always-visible="0" data-rail-visible="0" data-handle-color="#dae3e7">
												<form action="#" class="form-horizontal">
													<!-- TASK HEAD -->
													<div class="form">
														<div class="form-group">
															<div class="col-md-8 col-sm-8">
																<div class="todo-taskbody-user">
																	<img class="todo-userpic pull-left" src="<?php echo $this->request->webroot;?>img/profile/male.png" width="50px" height="50px">
																	<span class="todo-username pull-left">Vanessa Bond</span>
																	<button type="button" class="todo-username-btn btn btn-circle btn-default btn-xs">&nbsp;edit&nbsp;</button>
																</div>
															</div>
															<div class="col-md-4 col-sm-4">
																<div class="todo-taskbody-date pull-right">
																	<button type="button" class="todo-username-btn btn btn-circle btn-default btn-xs">&nbsp; Complete &nbsp;</button>
																</div>
															</div>
														</div>
														<!-- END TASK HEAD -->
														<!-- TASK TITLE -->
														<div class="form-group">
															<div class="col-md-12">
																<input type="text" class="form-control todo-taskbody-tasktitle" placeholder="Task Title...">
															</div>
														</div>
														<!-- TASK DESC -->
														<div class="form-group">
															<div class="col-md-12">
																<textarea class="form-control todo-taskbody-taskdesc" rows="8" placeholder="Task Description..."></textarea>
															</div>
														</div>
														<!-- END TASK DESC -->
														<!-- TASK DUE DATE -->
														<div class="form-group">
															<div class="col-md-12">
																<div class="input-icon">
																	<i class="fa fa-calendar"></i>
																	<input type="text" class="form-control todo-taskbody-due date form_datetime" placeholder="Due Date...">
																</div>
															</div>
														</div>
														<!-- TASK TAGS -->
														
                                                        <div class="form-group">
															<div class="col-md-12">
																<label >Follow up wih <?php echo ($settings->profile);?></label>
                                                                <select class="form-control">
                                                                    <option value="">Select <?php echo ucfirst($settings->profile);?></option>
                                                                    <option value="4">Nick Smith</option>
                                                                    <option value="5">James Blont</option>
                                                                    <option value="6">Mark Henry</option>
                                                                    <option value="7">John Lenon</option>
                                                                    <option value="8">Elvis Moore</option>
                                                                    <option value="9">Peter Brown</option>
                                                                    <option value="10">Jimmy Green</option>
                                                                    <option value="11">Robert Black</option>
                                                                    
                                                                </select>
															</div>
														</div>
                                                        <div class="form-group">
															<div class="col-md-12">
																<input type="checkbox" class="form-control todo-taskbody-tags"/>Remind by Email
															</div>
														</div>
														<!-- TASK TAGS -->
														<div class="form-actions right todo-form-actions">
															<button class="btn btn-sm green-haze">Save Changes</button>
															<button class="btn  btn-sm btn-default">Cancel</button>
														</div>
													</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- END TODO CONTENT -->
					</div>
				</div>
				<!-- END PAGE CONTENT-->
			</div>
		</div>
	</div>