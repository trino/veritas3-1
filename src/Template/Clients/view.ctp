<?php
if(isset($disabled))
$is_disabled = 'disabled="disabled"';
else
$is_disabled = '';

$profile = $this->requestAction('clients/getProfile/'.$id);
$contact = $this->requestAction('clients/getContact/'.$id);
?>
<?php $settings = $this->requestAction('settings/get_settings');?>

<h3 class="page-title"><?php echo $client->company_name; ?>
			</h3>

			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo $this->request->webroot;?>">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="<?php echo $this->request->webroot;?>clients"><?php echo ucfirst($settings->client);?>s</a>
					</li>
					<li>

					</li>
				</ul>
                <?php
                //if(isset($disabled))
//                { ?>
                <a href="javascript:window.print();" class="floatright btn btn-info">Print</a>
                <?php //} ?>

			</div>
<div class="row profile">
				<div class="col-md-12">
					<!--BEGIN TABS-->

							<div class="tab-pane active" id="tab_1_1">
								<div class="row">
									<div class="col-md-3">

										<ul class="list-unstyled profile-nav">
											<li>
                                                <img class="img-responsive" id="clientpic" alt="" src="<?=
                                                     clientimage($this->request->webroot, $settings, $client->image);
												?>">



												<!--a href="#" class="profile-edit">Edit </a>
                                                <br /-->
                                                <h3>Assigned to:</h3>
											</li>
                                            <?php
                                            $types = array('Driver','Admin','Recruiter','External','Safety','Driver','Contact');
                                            $counter = 0;
                                             foreach($profile as $p) {
                                                    echo '<LI><a href="' . $this->request->webroot . "profiles/view/" .  $p->id . '">';
                                                     if (strlen(trim($p->username)>0)) {
                                                         echo $p->username;
                                                     } elseif(strlen(trim($p->fname . $p->lname))>0) {
                                                         echo $p->fname . " " . $p->lname;
                                                     } else {
                                                         echo "[NO NAME]";
                                                     }
                                                     if (strlen($p->profile_type)>0) {
                                                         echo "(" . $types[$p->profile_type] . ")";
                                                     } else {
                                                         echo "(Draft)";
                                                     }
                                                    echo "</A></LI>";
                                                    $counter++;
                                                }
                                                $c = $counter;


                                             ?>
                                             <li>
                                             <h3>Contacts:</h3>
                                             </li>
                                             <li>
                                             <?php
                                             foreach($contact as $c)
                                                {
                                                    ?>
                                             <li>
												<a href="<?php echo $this->request->webroot;?>profiles/view/<?php echo $c->id; ?>">
												    <?php echo $c->username; ?> <?php //echo $types[$p->profile_type]; ?>
                                                </a>
											</li>
                                                    <?php
                                                }    
                                             ?>
                                             <li>
                                             </li>
											
										</ul>
									</div>
									<div class="col-md-9">
										<div class="row">
											<div class="col-md-8 profile-info">

												<p>
												<?php echo $client->description;
                                                $doc_count = $this->requestAction("clients/getDocCount/".$id);
                                                                $dc = '';
                                                                foreach($doc_count as $d)
                                                                {
                                                                    $dc++;
                                                                }
                                                 ?>	 
												</p>
												



												<a href="<?php echo $this->request->webroot;?>documents/" class="btn btn-lg default yellow-stripe">
													<?php echo $dc; ?> <?=$settings->document?>s Submitted </a><a href="<?php echo $this->request->webroot;?>documents/" class="btn btn-lg yellow">
													View All <i class="fa fa-search"></i>
												</a>

											</div>
											<!--end col-md-8-->
											<div class="col-md-4">
												<div class="portlet sale-summary">
													<div class="portlet-title">
														<div class="caption">
															 <?php echo ucfirst($settings->client);?> Summary
														</div>
														<div class="tools">
															<a class="reload" href="javascript:;">
															</a>
														</div>
													</div>
													<div class="portlet-body">
												<ul class="list-unstyled">

															<li>
																<span class="sale-info">
																<?php echo ucfirst($settings->profile);?>s <i class="fa fa-img-down"></i>
																</span>
																<span class="sale-num">
																<?php echo $counter; ?> </span>
															</li>

                                                            <?php 
                                                                
                                                                $order_count = $this->requestAction("clients/getOrderCount/".$id);
                                                                $oc = 0;
                                                                foreach($order_count as $d)
                                                                {
                                                                    $oc++;
                                                                }
                                                                $upload = $dc+$oc;
                                                             ?>
															<li>
																<span class="sale-info">
																Total Uploads <i class="fa fa-img-up"></i>
																</span>
																<span class="sale-num">
																N/A </span>
															</li>

															<li>
																<span class="sale-info">
																Created on </span>
																<span class="sale-num">
																<i class="fa fa-calendar"></i> N/A </span>
															</li>
                                                            <li>
																<span class="sale-info">
																Ends on </span>
																<span class="sale-num">
																<i class="fa fa-calendar"></i> N/A </span>
															</li>

														</ul>
													</div>
												</div>
											</div>
											<!--end col-md-4-->
										</div>
										<!--end row-->

                                        <div class="home_blocks">
                                        <?php include('subpages/home_blocks_client.php');?>
                                        </div>
                            			<div class="clearfix"></div>

                                        <table class="table table-striped">
                                            <tr><td>Signatory's name</td><td><?php if($client->sig_fname) echo $client->sig_fname."&nbsp"; else echo "Not Available";  if($client->sig_lname) echo $client->sig_lname; ?></td></tr>
                                            <tr><td>Signatory's phone number</td><td><?php if($client->company_phone) echo $client->company_phone; else echo "Not Available"; ?></td></tr>
                                            <tr><td>Signatory's email</td><td><?php if($client->sig_email) echo $client->sig_email; else echo "Not Available"; ?></td></tr>
                                            <tr><td>Address</td><td><?php if($client->billing_address) echo $client->billing_address; else echo "Not Available"; ?></td></tr>
                                            <tr><td>City</td><td><?php if($client->city) echo $client->city; else echo "Not Available"; ?></td></tr>
                                            <tr><td>Province</td><td><?php if($client->province) echo $client->province; else echo "Not Available"; ?></td></tr>
											<tr><td>Postal/Zip Code</td><td><?php if($client->postal) echo $client->postal; else echo "Not Available"; ?></td></tr>

                                        </table>

									</div>
								</div>
							</div>
							<!--tab_1_2-->



							<!--end tab-pane-->

				</div>
			</div>

<style>
@media print {
    .page-header{display:none;}
    .page-footer,.nav-tabs,.page-title,.page-bar,.theme-panel,.page-sidebar-wrapper,.more{display:none!important;}
    .portlet-body,.portlet-title{border-top:1px solid #578EBE;}
    .tabbable-line{border:none!important;}
     a:link:after,
    a:visited:after {
        content: "" !important;
    }
    }
</style>