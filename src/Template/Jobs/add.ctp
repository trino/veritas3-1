<?php
    $is_disabled = '';
    if(isset($disabled)) {$is_disabled = 'disabled="disabled"';}
?>
<h3 class="page-title">
    <?php echo ucfirst($settings->client);?>s
			</h3>

			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo $this->request->webroot;?>">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="">Add/Edit <?php echo ucfirst($settings->client);?></a>
					</li>
				</ul>

			</div>
<div class="row ">
				<div class="col-md-12">
					<!-- BEGIN SAMPLE FORM PORTLET-->
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-gloabe"></i> Add/Edit <?php echo ucfirst($settings->client);?>
							</div>

						</div>
						<div class="portlet-body">
                        <form class="" role="form" action="" method="post">
							<h4>Client Detail</h4>
								<div class="form-group col-md-6">




											<input <?php echo $is_disabled;?> type="text" name="phone" class="form-control form-control placeholder-no-fix" id="phone" placeholder="Title">



								</div>

								<div class="form-group col-md-6">


										<div class="input-icon right">

											<textarea <?php echo $is_disabled;?>  class="form-control" name="address" id="address" placeholder="Description"></textarea>


									</div>

                                </div>

                                <div class="clearfix"></div>


							          <div class="form-group col-md-12">
                    <input class="form-control placeholder-no-fix"  <?php echo $is_disabled;?> type="text" name="fullname" placeholder="Enter name of your company">
                </div>
                <div class="form-group col-md-6">
                    <input class="form-control placeholder-no-fix"  <?php echo $is_disabled;?> type="text" name="" placeholder="Signatory's first name">
                </div>
                <div class="form-group col-md-6">
                    <input class="form-control placeholder-no-fix"  <?php echo $is_disabled;?> type="text" name="" placeholder="Signatory's last name">
                </div>
                <div class="form-group col-md-6">
                    <input class="form-control placeholder-no-fix"  <?php echo $is_disabled;?> type="text" name="" placeholder="Signatory's phone number">
                </div>
                <div class="form-group col-md-6">
                    <input class="form-control placeholder-no-fix"  <?php echo $is_disabled;?> type="text" name="" placeholder="Signatory's email address">
                </div>
                <div class="form-group col-md-12">
                    <input class="form-control placeholder-no-fix" <?php echo $is_disabled;?>type="text" name="" placeholder="Billing Address Street Name and Number">
                </div>
                <div class="form-group col-md-6">
                    <input class="form-control placeholder-no-fix" <?php echo $is_disabled;?> type="text" name="" placeholder="City">
                </div>
                <div class="form-group col-md-6">
                    <input class="form-control placeholder-no-fix" <?php echo $is_disabled;?> type="text" name="" placeholder="Postal Code">
                </div>
                <div class="form-group col-md-6">
                    <input class="form-control placeholder-no-fix" <?php echo $is_disabled;?> type="text" name="" placeholder="Province/State">
                </div>
                <div class="form-group col-md-6">
                <select name="country" <?php echo $is_disabled;?> class="form-control">
                <option value="">Country</option>
                <option value="AF">Afghanistan</option>
                <option value="AL">Albania</option>
                <option value="DZ">Algeria</option>
                <option value="AS">American Samoa</option>
                <option value="AD">Andorra</option>
                <option value="AO">Angola</option>
                <option value="AI">Anguilla</option>
                <option value="AQ">Antarctica</option>
                <option value="AR">Argentina</option>
                <option value="AM">Armenia</option>
                <option value="AW">Aruba</option>
                <option value="AU">Australia</option>
                <option value="AT">Austria</option>
                <option value="AZ">Azerbaijan</option>
                <option value="BS">Bahamas</option>
                <option value="BH">Bahrain</option>
                <option value="BD">Bangladesh</option>
                <option value="BB">Barbados</option>
                <option value="BY">Belarus</option>
                <option value="BE">Belgium</option>
                <option value="BZ">Belize</option>
                <option value="BJ">Benin</option>
                <option value="BM">Bermuda</option>
                <option value="BT">Bhutan</option>
                <option value="BO">Bolivia</option>
                <option value="BA">Bosnia and Herzegowina</option>
                <option value="BW">Botswana</option>
                <option value="BV">Bouvet Island</option>
                <option value="BR">Brazil</option>
                <option value="IO">British Indian Ocean Territory</option>
                <option value="BN">Brunei Darussalam</option>
                <option value="BG">Bulgaria</option>
                <option value="BF">Burkina Faso</option>
                <option value="BI">Burundi</option>
                <option value="KH">Cambodia</option>
                <option value="CM">Cameroon</option>
                <option value="CA">Canada</option>
                <option value="CV">Cape Verde</option>
                <option value="KY">Cayman Islands</option>
                <option value="CF">Central African Republic</option>
                <option value="TD">Chad</option>
                <option value="CL">Chile</option>
                <option value="CN">China</option>
                <option value="CX">Christmas Island</option>
                <option value="CC">Cocos (Keeling) Islands</option>
                <option value="CO">Colombia</option>
                <option value="KM">Comoros</option>
                <option value="CG">Congo</option>
                <option value="CD">Congo, the Democratic Republic of the</option>
                <option value="CK">Cook Islands</option>
                <option value="CR">Costa Rica</option>
                <option value="CI">Cote d'Ivoire</option>
                <option value="HR">Croatia (Hrvatska)</option>
                <option value="CU">Cuba</option>
                <option value="CY">Cyprus</option>
                <option value="CZ">Czech Republic</option>
                <option value="DK">Denmark</option>
                <option value="DJ">Djibouti</option>
                <option value="DM">Dominica</option>
                <option value="DO">Dominican Republic</option>
                <option value="EC">Ecuador</option>
                <option value="EG">Egypt</option>
                <option value="SV">El Salvador</option>
                <option value="GQ">Equatorial Guinea</option>
                <option value="ER">Eritrea</option>
                <option value="EE">Estonia</option>
                <option value="ET">Ethiopia</option>
                <option value="FK">Falkland Islands (Malvinas)</option>
                <option value="FO">Faroe Islands</option>
                <option value="FJ">Fiji</option>
                <option value="FI">Finland</option>
                <option value="FR">France</option>
                <option value="GF">French Guiana</option>
                <option value="PF">French Polynesia</option>
                <option value="TF">French Southern Territories</option>
                <option value="GA">Gabon</option>
                <option value="GM">Gambia</option>
                <option value="GE">Georgia</option>
                <option value="DE">Germany</option>
                <option value="GH">Ghana</option>
                <option value="GI">Gibraltar</option>
                <option value="GR">Greece</option>
                <option value="GL">Greenland</option>
                <option value="GD">Grenada</option>
                <option value="GP">Guadeloupe</option>
                <option value="GU">Guam</option>
                <option value="GT">Guatemala</option>
                <option value="GN">Guinea</option>
                <option value="GW">Guinea-Bissau</option>
                <option value="GY">Guyana</option>
                <option value="HT">Haiti</option>
                <option value="HM">Heard and Mc Donald Islands</option>
                <option value="VA">Holy See (Vatican City State)</option>
                <option value="HN">Honduras</option>
                <option value="HK">Hong Kong</option>
                <option value="HU">Hungary</option>
                <option value="IS">Iceland</option>
                <option value="IN">India</option>
                <option value="ID">Indonesia</option>
                <option value="IR">Iran (Islamic Republic of)</option>
                <option value="IQ">Iraq</option>
                <option value="IE">Ireland</option>
                <option value="IL">Israel</option>
                <option value="IT">Italy</option>
                <option value="JM">Jamaica</option>
                <option value="JP">Japan</option>
                <option value="JO">Jordan</option>
                <option value="KZ">Kazakhstan</option>
                <option value="KE">Kenya</option>
                <option value="KI">Kiribati</option>
                <option value="KP">Korea, Democratic People's Republic of</option>
                <option value="KR">Korea, Republic of</option>
                <option value="KW">Kuwait</option>
                <option value="KG">Kyrgyzstan</option>
                <option value="LA">Lao People's Democratic Republic</option>
                <option value="LV">Latvia</option>
                <option value="LB">Lebanon</option>
                <option value="LS">Lesotho</option>
                <option value="LR">Liberia</option>
                <option value="LY">Libyan Arab Jamahiriya</option>
                <option value="LI">Liechtenstein</option>
                <option value="LT">Lithuania</option>
                <option value="LU">Luxembourg</option>
                <option value="MO">Macau</option>
                <option value="MK">Macedonia, The Former Yugoslav Republic of</option>
                <option value="MG">Madagascar</option>
                <option value="MW">Malawi</option>
                <option value="MY">Malaysia</option>
                <option value="MV">Maldives</option>
                <option value="ML">Mali</option>
                <option value="MT">Malta</option>
                <option value="MH">Marshall Islands</option>
                <option value="MQ">Martinique</option>
                <option value="MR">Mauritania</option>
                <option value="MU">Mauritius</option>
                <option value="YT">Mayotte</option>
                <option value="MX">Mexico</option>
                <option value="FM">Micronesia, Federated States of</option>
                <option value="MD">Moldova, Republic of</option>
                <option value="MC">Monaco</option>
                <option value="MN">Mongolia</option>
                <option value="MS">Montserrat</option>
                <option value="MA">Morocco</option>
                <option value="MZ">Mozambique</option>
                <option value="MM">Myanmar</option>
                <option value="NA">Namibia</option>
                <option value="NR">Nauru</option>
                <option value="NP">Nepal</option>
                <option value="NL">Netherlands</option>
                <option value="AN">Netherlands Antilles</option>
                <option value="NC">New Caledonia</option>
                <option value="NZ">New Zealand</option>
                <option value="NI">Nicaragua</option>
                <option value="NE">Niger</option>
                <option value="NG">Nigeria</option>
                <option value="NU">Niue</option>
                <option value="NF">Norfolk Island</option>
                <option value="MP">Northern Mariana Islands</option>
                <option value="NO">Norway</option>
                <option value="OM">Oman</option>
                <option value="PK">Pakistan</option>
                <option value="PW">Palau</option>
                <option value="PA">Panama</option>
                <option value="PG">Papua New Guinea</option>
                <option value="PY">Paraguay</option>
                <option value="PE">Peru</option>
                <option value="PH">Philippines</option>
                <option value="PN">Pitcairn</option>
                <option value="PL">Poland</option>
                <option value="PT">Portugal</option>
                <option value="PR">Puerto Rico</option>
                <option value="QA">Qatar</option>
                <option value="RE">Reunion</option>
                <option value="RO">Romania</option>
                <option value="RU">Russian Federation</option>
                <option value="RW">Rwanda</option>
                <option value="KN">Saint Kitts and Nevis</option>
                <option value="LC">Saint LUCIA</option>
                <option value="VC">Saint Vincent and the Grenadines</option>
                <option value="WS">Samoa</option>
                <option value="SM">San Marino</option>
                <option value="ST">Sao Tome and Principe</option>
                <option value="SA">Saudi Arabia</option>
                <option value="SN">Senegal</option>
                <option value="SC">Seychelles</option>
                <option value="SL">Sierra Leone</option>
                <option value="SG">Singapore</option>
                <option value="SK">Slovakia (Slovak Republic)</option>
                <option value="SI">Slovenia</option>
                <option value="SB">Solomon Islands</option>
                <option value="SO">Somalia</option>
                <option value="ZA">South Africa</option>
                <option value="GS">South Georgia and the South Sandwich Islands</option>
                <option value="ES">Spain</option>
                <option value="LK">Sri Lanka</option>
                <option value="SH">St. Helena</option>
                <option value="PM">St. Pierre and Miquelon</option>
                <option value="SD">Sudan</option>
                <option value="SR">Suriname</option>
                <option value="SJ">Svalbard and Jan Mayen Islands</option>
                <option value="SZ">Swaziland</option>
                <option value="SE">Sweden</option>
                <option value="CH">Switzerland</option>
                <option value="SY">Syrian Arab Republic</option>
                <option value="TW">Taiwan, Province of China</option>
                <option value="TJ">Tajikistan</option>
                <option value="TZ">Tanzania, United Republic of</option>
                <option value="TH">Thailand</option>
                <option value="TG">Togo</option>
                <option value="TK">Tokelau</option>
                <option value="TO">Tonga</option>
                <option value="TT">Trinidad and Tobago</option>
                <option value="TN">Tunisia</option>
                <option value="TR">Turkey</option>
                <option value="TM">Turkmenistan</option>
                <option value="TC">Turks and Caicos Islands</option>
                <option value="TV">Tuvalu</option>
                <option value="UG">Uganda</option>
                <option value="UA">Ukraine</option>
                <option value="AE">United Arab Emirates</option>
                <option value="GB">United Kingdom</option>
                <option value="US">United States</option>
                <option value="UM">United States Minor Outlying Islands</option>
                <option value="UY">Uruguay</option>
                <option value="UZ">Uzbekistan</option>
                <option value="VU">Vanuatu</option>
                <option value="VE">Venezuela</option>
                <option value="VN">Viet Nam</option>
                <option value="VG">Virgin Islands (British)</option>
                <option value="VI">Virgin Islands (U.S.)</option>
                <option value="WF">Wallis and Futuna Islands</option>
                <option value="EH">Western Sahara</option>
                <option value="YE">Yemen</option>
                <option value="ZM">Zambia</option>
                <option value="ZW">Zimbabwe</option>
                </select>
                </div>
                <div class="form-group col-md-6">
                    <input class="form-control placeholder-no-fix" <?php echo $is_disabled;?> type="text" name="" placeholder="User's first name">
                </div>
                <div class="form-group col-md-6">
                    <input class="form-control placeholder-no-fix" <?php echo $is_disabled;?> type="text" name="" placeholder="User's last name">
                </div>
                <div class="form-group col-md-6">
                    <input class="form-control placeholder-no-fix" <?php echo $is_disabled;?> type="text" name="" placeholder="User's email">
                </div>
                <div class="form-group col-md-6">
                    <input class="form-control placeholder-no-fix" <?php echo $is_disabled;?> type="text" name="" placeholder="User's phone number">
                </div>


 <div class="clearfix"></div>

							<hr>
                            <h4>Add Image</h4>


                            <div class="form-group">
									<label class="sr-only" for="exampleInputEmail22">Add Image</label>
									<div class="input-icon">

										<a href="javascript:void(0)" <?php echo $is_disabled;?> class="btn btn-success"><i class="fa fa-envelope"></i> &nbsp; Add Profile image</a>
									</div>
							</div>

                            <hr>
                            <h4>Site/Date</h4>
                            <div class="form-group col-md-12">
									<label for="phone" class="col-md-2 control-label nopad">Site</label>
									<div class="col-md-10 nopad">
										<div class="input-icon">
											<i class="fa fa-envelope"></i>
											<input <?php echo $is_disabled;?> type="text" name="phone" class="form-control" id="phone" placeholder="Site">
										</div>
									</div>
								</div>
                                <p>&nbsp;</p>
                            <div class="form-group col-md-12">
									<label for="phone" class="col-md-2 control-label nopad">Start Date</label>
									<div class="col-md-10 nopad">
										<div class="input-icon">
											<i class="fa fa-envelope"></i>
											<input <?php echo $is_disabled;?> type="text" name="phone" class="form-control" id="phone" placeholder="Start Date">
										</div>
									</div>
								</div>
                                <p>&nbsp;</p>
                            <div class="form-group col-md-12">
									<label for="phone" class="col-md-2 control-label nopad">End Date</label>
									<div class="col-md-10 nopad">
										<div class="input-icon">
											<i class="fa fa-envelope"></i>
											<input <?php echo $is_disabled;?> type="text" name="phone" class="form-control" id="phone" placeholder="End Date">
										</div>
									</div>
								</div>
                                <p>&nbsp;</p>
                            <hr />
                            <h4>Add Members</h4>

                            <div class="form-group">


                                <span>     John </span>
                                <input <?php echo $is_disabled;?> type="checkbox" name="canView_contracts">
                                <span>      Falcon </span>
                                <input <?php echo $is_disabled;?> type="checkbox" onclick="loadmore('evidence',$(this));" name="canView_evidence">
                                <span>      Maroni </span>
                                <input <?php echo $is_disabled;?> type="checkbox" name="canView_templates">
                                <span>      Oswalt </span>
                                <input <?php echo $is_disabled;?> type="checkbox" name="canView_client_memo" onclick="loadmore('report',$(this));">
                                <span>      Sasha </span>
                                <input <?php echo $is_disabled;?> type="checkbox" name="canView_siteOrder" onclick="loadmore('siteorder',$(this));">
                                <span>      Bruce </span>
                                <input <?php echo $is_disabled;?> type="checkbox" name="canView_training">
                                <span>      Heather </span>
                                <input <?php echo $is_disabled;?> type="checkbox" name="canView_employee" onclick="loadmore('employee',$(this));">
                                <span>      Ronny </span>
                                <input <?php echo $is_disabled;?> type="checkbox" name="canView_KPIAudits">
                                <span>      Mooney </span>
                                <input <?php echo $is_disabled;?> type="checkbox" name="canView_orders">
                                <span>      Alferd </span>
                                <input <?php echo $is_disabled;?> type="checkbox" name="canView_deployment_rate" onchange="if($(this).is(':checked')){$('.deploy_more').show();}else{$('.deploy_more').hide();if($('.is_client').is(':checked')){$('.is_client').click();}}">

                            </div>
                            <hr />
                            <h4>Add Contacts</h4>
                            <div class="form-group">

                            <span>     Tyrion </span>
                            <input <?php echo $is_disabled;?> type="checkbox" checked="checked" name="Email_contracts">
                            <span>     Sansha </span>
                            <input <?php echo $is_disabled;?> type="checkbox" checked="checked" name="Email_evidence">
                            <span>     Jon </span>
                            <input <?php echo $is_disabled;?> type="checkbox" checked="checked" name="Email_templates">
                            <span>     Araya </span>
                            <input <?php echo $is_disabled;?> type="checkbox" checked="checked" name="Email_client_memo">
                            <span>      Joffery </span>
                            <input <?php echo $is_disabled;?> type="checkbox" checked="checked" name="Email_siteOrder">
                            <span>      Jimmy </span>
                            <input <?php echo $is_disabled;?> type="checkbox" checked="checked" name="Email_training">
                            <span>      Cersei </span>
                            <input <?php echo $is_disabled;?> type="checkbox" checked="checked" name="Email_employee">
                            <span>      Robb </span>
                            <input <?php echo $is_disabled;?> type="checkbox" name="Email_KPIAudits">
                            <span>      Catelyin </span>
                            <input <?php echo $is_disabled;?> type="checkbox" name="Email_orders">
                            <span>      Daenerys </span>
                            <input <?php echo $is_disabled;?> type="checkbox" name="Email_deployment">

                            </div>


                            <hr/>
                            <div class="form-group">


										<?php if(!$is_disabled){?>
										<input type="submit" value="Submit" class="form-control btn btn-primary"/>
									   <?php }?>
							</div>
                            </form>
                            </div>
						</div>
					</div>
					<!-- END SAMPLE FORM PORTLET-->












