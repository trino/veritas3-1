<?php
  if($this->request->session()->read('debug')){ echo "<span style ='color:red;'>subpages/documents/footprint.php #INC500</span>"; }
  $is_disabled = '';//there is no place for attachments
  if(isset($disabled)) { $is_disabled = 'disabled="disabled"'; }
  if(isset($dx)){ echo '<h3>' . $dx->title . '</h3>'; }
?>
<form role="form" action="<?php echo $this->request->webroot;?>documents/footprint/<?php echo $cid .'/' .$did;?>" method="post" id="form_tab<?php echo $dx->id;?>">

    <input type="hidden" class="document_type" name="document_type" value="<?php echo $dx->title;?>"/>

    <input type="hidden" name="sub_doc_id" value="<?php echo $dx->id;?>" class="sub_docs_id" id="af" />
    <div class="col-md-6">
      <label for="fullname" class="control-label">Full name (required) </label>
      <input type="text" class="form-control" name="fullname" value="<?php if(isset($footprint))echo $footprint->fullname;?>" >
    </div>

    <div class="col-md-6"><label for="Maidenname" class="control-label">Maiden name: </label>
      <input type="text" name="maidenname" class="form-control" value="<?php if(isset($footprint))echo $footprint->maidenname;?>" >
    </div>
    <div class="clearfix"></div>
    <div class="col-md-6"><label for="gender" class="control-label">
      <span>Gender</span></label>
      <select id="gender" name="gender" class="form-control">
        <option value="">-</option>
        <option value="1" <?php if(isset($footprint)&& $footprint->gender=='1')echo "selected='selected'";?>>Male</option>
        <option value="2" <?php if(isset($footprint)&& $footprint->gender=='2')echo "selected='selected'";?>>Female</option>
        <option value="3" <?php if(isset($footprint)&& $footprint->gender=='3')echo "selected='selected'";?>>Not Specified</option>
      </select>
    
  </div>

  <div class="col-md-6"><label for="DateOfBirth" class="control-label">Date of Birth (yyyy-mm-dd) </label>
    <input type="date" name="dateofbirth" class="form-control" value="<?php if(isset($footprint))echo $footprint->dateofbirth;?>" >
  </div>
  <div class="clearfix"></div>
  
  <div class="col-md-6">
    <label for="email" class="control-label">Email </label>
    <input type="email" name="email" role="email" class="form-control" value="<?php if(isset($footprint))echo $footprint->email;?>" >
  </div>

  <div class="col-md-6">
    <label for="Alternateemail" class="control-label">Alternate Email </label>
    <input type="email" name="email1" role="email" class="form-control" value="<?php if(isset($footprint))echo $footprint->email1;?>" >
  </div>
  <div class="clearfix"></div>

  <div class="col-md-12" style="padding: 0px;">
    <p class="col-md-12">Address</p>
    <div class="col-md-4"><label for="Streetnumber" class="control-label"> Street No: </label>
      <input type="text" name="street_num" class="form-control" value="<?php if(isset($footprint))echo $footprint->street_num;?>" >
    </div>
    <div class="col-md-8"><label for="street" class="control-label"> Street Name (& Apartment #): </label>
      <input type="date" name="street" class="form-control" value="<?php if(isset($footprint))echo $footprint->street;?>" >
    </div>
    <div class="clearfix"></div>
  </div>


  <div class="col-md-12" style="padding: 0px;">
   <div class="col-md-4"><label for="City" class="control-label"> City: </label>
    <input type="text" name="city" class="form-control" value="<?php if(isset($footprint))echo $footprint->city;?>" >
  </div>
  <div class="col-md-4"><label for="State" class="control-label"> Province/State: </label>
    <input type="text" name="state" class="form-control" value="<?php if(isset($footprint))echo $footprint->state;?>" >
  </div>
  <div class="col-md-4"><label for="Postal" class="control-label"> Postal Code: </label>
    <input type="text" name="postal" role="postalzip" class="form-control" value="<?php if(isset($footprint))echo $footprint->postal;?>" >
  </div>
  <div class="clearfix"></div>
</div>

<div class="col-md-6"><label for="Country" class="control-label">
  <span>Country:</span></label>
  <select id="country" name="country" class="form-control"> 
   <option value="">&mdash;</option><option value="Afghanistan">Afghanistan</option><option value="Albania">Albania</option><option value="Algeria">Algeria</option><option value="American Samoa">American Samoa</option><option value="Andorra">Andorra</option><option value="Angola">Angola</option><option value="Anguilla">Anguilla</option><option value="Antarctica">Antarctica</option><option value="Antigua and Barbuda">Antigua and Barbuda</option><option value="Argentina">Argentina</option><option value="Armenia">Armenia</option><option value="Arctic Ocean">Arctic Ocean</option><option value="Aruba">Aruba</option><option value="Ashmore and Cartier Islands">Ashmore and Cartier Islands</option><option value="Atlantic Ocean">Atlantic Ocean</option><option value="Australia">Australia</option><option value="Austria">Austria</option><option value="Azerbaijan">Azerbaijan</option><option value="Bahamas">Bahamas</option><option value="Bahrain">Bahrain</option><option value="Baker Island">Baker Island</option><option value="Bangladesh">Bangladesh</option><option value="Barbados">Barbados</option><option value="Bassas da India">Bassas da India</option><option value="Belarus">Belarus</option><option value="Belgium">Belgium</option><option value="Belize">Belize</option><option value="Benin">Benin</option><option value="Bermuda">Bermuda</option><option value="Bhutan">Bhutan</option><option value="Bolivia">Bolivia</option><option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option><option value="Botswana">Botswana</option><option value="Bouvet Island">Bouvet Island</option><option value="Brazil">Brazil</option><option value="British Virgin Islands">British Virgin Islands</option><option value="Brunei">Brunei</option><option value="Bulgaria">Bulgaria</option><option value="Burkina Faso">Burkina Faso</option><option value="Burundi">Burundi</option><option value="Cambodia">Cambodia</option><option value="Cameroon">Cameroon</option><option value="Canada">Canada</option><option value="Cape Verde">Cape Verde</option><option value="Cayman Islands">Cayman Islands</option><option value="Central African Republic">Central African Republic</option><option value="Chad">Chad</option><option value="Chile">Chile</option><option value="China">China</option><option value="Christmas Island">Christmas Island</option><option value="Clipperton Island">Clipperton Island</option><option value="Cocos Islands">Cocos Islands</option><option value="Colombia">Colombia</option><option value="Comoros">Comoros</option><option value="Cook Islands">Cook Islands</option><option value="Coral Sea Islands">Coral Sea Islands</option><option value="Costa Rica">Costa Rica</option><option value="Cote d'Ivoire">Cote d'Ivoire</option><option value="Croatia">Croatia</option><option value="Cuba">Cuba</option><option value="Cyprus">Cyprus</option><option value="Czech Republic">Czech Republic</option><option value="Denmark">Denmark</option><option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option><option value="Djibouti">Djibouti</option><option value="Dominica">Dominica</option><option value="Dominican Republic">Dominican Republic</option><option value="East Timor">East Timor</option><option value="Ecuador">Ecuador</option><option value="Egypt">Egypt</option><option value="El Salvador">El Salvador</option><option value="Equatorial Guinea">Equatorial Guinea</option><option value="Eritrea">Eritrea</option><option value="Estonia">Estonia</option><option value="Ethiopia">Ethiopia</option><option value="Europa Island">Europa Island</option><option value="Falkland Islands (Islas Malvinas)">Falkland Islands (Islas Malvinas)</option><option value="Faroe Islands">Faroe Islands</option><option value="Fiji">Fiji</option><option value="Finland">Finland</option><option value="France">France</option><option value="French Guiana">French Guiana</option><option value="French Polynesia">French Polynesia</option><option value="French Southern and Antarctic Lands">French Southern and Antarctic Lands</option><option value="Gabon">Gabon</option><option value="Gambia">Gambia</option><option value="Gaza Strip">Gaza Strip</option><option value="Georgia">Georgia</option><option value="Germany">Germany</option><option value="Ghana">Ghana</option><option value="Gibraltar">Gibraltar</option><option value="Glorioso Islands">Glorioso Islands</option><option value="Greece">Greece</option><option value="Greenland">Greenland</option><option value="Grenada">Grenada</option><option value="Guadeloupe">Guadeloupe</option><option value="Guam">Guam</option><option value="Guatemala">Guatemala</option><option value="Guernsey">Guernsey</option><option value="Guinea">Guinea</option><option value="Guinea-Bissau">Guinea-Bissau</option><option value="Guyana">Guyana</option><option value="Haiti">Haiti</option><option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option><option value="Honduras">Honduras</option><option value="Hong Kong">Hong Kong</option><option value="Howland Island">Howland Island</option><option value="Hungary">Hungary</option><option value="Iceland">Iceland</option><option value="India">India</option><option value="Indian Ocean">Indian Ocean</option><option value="Indonesia">Indonesia</option><option value="Iran">Iran</option><option value="Iraq">Iraq</option><option value="Ireland">Ireland</option><option value="Isle of Man">Isle of Man</option><option value="Israel">Israel</option><option value="Italy">Italy</option><option value="Jamaica">Jamaica</option><option value="Jan Mayen">Jan Mayen</option><option value="Japan">Japan</option><option value="Jarvis Island">Jarvis Island</option><option value="Jersey">Jersey</option><option value="Johnston Atoll">Johnston Atoll</option><option value="Jordan">Jordan</option><option value="Juan de Nova Island">Juan de Nova Island</option><option value="Kazakhstan">Kazakhstan</option><option value="Kenya">Kenya</option><option value="Kingman Reef">Kingman Reef</option><option value="Kiribati">Kiribati</option><option value="Kerguelen Archipelago">Kerguelen Archipelago</option><option value="Kosovo">Kosovo</option><option value="Kuwait">Kuwait</option><option value="Kyrgyzstan">Kyrgyzstan</option><option value="Laos">Laos</option><option value="Latvia">Latvia</option><option value="Lebanon">Lebanon</option><option value="Lesotho">Lesotho</option><option value="Liberia">Liberia</option><option value="Libya">Libya</option><option value="Liechtenstein">Liechtenstein</option><option value="Lithuania">Lithuania</option><option value="Luxembourg">Luxembourg</option><option value="Macau">Macau</option><option value="Macedonia">Macedonia</option><option value="Madagascar">Madagascar</option><option value="Malawi">Malawi</option><option value="Malaysia">Malaysia</option><option value="Maldives">Maldives</option><option value="Mali">Mali</option><option value="Malta">Malta</option><option value="Marshall Islands">Marshall Islands</option><option value="Martinique">Martinique</option><option value="Mauritania">Mauritania</option><option value="Mauritius">Mauritius</option><option value="Mayotte">Mayotte</option><option value="Mexico">Mexico</option><option value="Micronesia">Micronesia</option><option value="Midway Islands">Midway Islands</option><option value="Moldova">Moldova</option><option value="Monaco">Monaco</option><option value="Mongolia">Mongolia</option><option value="Montenegro">Montenegro</option><option value="Montserrat">Montserrat</option><option value="Morocco">Morocco</option><option value="Mozambique">Mozambique</option><option value="Myanmar">Myanmar</option><option value="Namibia">Namibia</option><option value="Nauru">Nauru</option><option value="Navassa Island">Navassa Island</option><option value="Nepal">Nepal</option><option value="Netherlands">Netherlands</option><option value="Netherlands Antilles">Netherlands Antilles</option><option value="New Caledonia">New Caledonia</option><option value="New Zealand">New Zealand</option><option value="Nicaragua">Nicaragua</option><option value="Niger">Niger</option><option value="Nigeria">Nigeria</option><option value="Niue">Niue</option><option value="Norfolk Island">Norfolk Island</option><option value="North Korea">North Korea</option><option value="North Sea">North Sea</option><option value="Northern Mariana Islands">Northern Mariana Islands</option><option value="Norway">Norway</option><option value="Oman">Oman</option><option value="Pacific Ocean">Pacific Ocean</option><option value="Pakistan">Pakistan</option><option value="Palau">Palau</option><option value="Palmyra Atoll">Palmyra Atoll</option><option value="Panama">Panama</option><option value="Papua New Guinea">Papua New Guinea</option><option value="Paracel Islands">Paracel Islands</option><option value="Paraguay">Paraguay</option><option value="Peru">Peru</option><option value="Philippines">Philippines</option><option value="Pitcairn Islands">Pitcairn Islands</option><option value="Poland">Poland</option><option value="Portugal">Portugal</option><option value="Puerto Rico">Puerto Rico</option><option value="Qatar">Qatar</option><option value="Reunion">Reunion</option><option value="Republic of the Congo">Republic of the Congo</option><option value="Romania">Romania</option><option value="Russia">Russia</option><option value="Rwanda">Rwanda</option><option value="Saint Helena">Saint Helena</option><option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option><option value="Saint Lucia">Saint Lucia</option><option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option><option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option><option value="Samoa">Samoa</option><option value="San Marino">San Marino</option><option value="Sao Tome and Principe">Sao Tome and Principe</option><option value="Saudi Arabia">Saudi Arabia</option><option value="Senegal">Senegal</option><option value="Serbia">Serbia</option><option value="Seychelles">Seychelles</option><option value="Sierra Leone">Sierra Leone</option><option value="Singapore">Singapore</option><option value="Slovakia">Slovakia</option><option value="Slovenia">Slovenia</option><option value="Solomon Islands">Solomon Islands</option><option value="Somalia">Somalia</option><option value="South Africa">South Africa</option><option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option><option value="South Korea">South Korea</option><option value="Spain">Spain</option><option value="Spratly Islands">Spratly Islands</option><option value="Sri Lanka">Sri Lanka</option><option value="Sudan">Sudan</option><option value="Suriname">Suriname</option><option value="Svalbard">Svalbard</option><option value="Swaziland">Swaziland</option><option value="Sweden">Sweden</option><option value="Switzerland">Switzerland</option><option value="Syria">Syria</option><option value="Taiwan">Taiwan</option><option value="Tajikistan">Tajikistan</option><option value="Tanzania">Tanzania</option><option value="Thailand">Thailand</option><option value="Togo">Togo</option><option value="Tokelau">Tokelau</option><option value="Tonga">Tonga</option><option value="Trinidad and Tobago">Trinidad and Tobago</option><option value="Tromelin Island">Tromelin Island</option><option value="Tunisia">Tunisia</option><option value="Turkey">Turkey</option><option value="Turkmenistan">Turkmenistan</option><option value="Turks and Caicos Islands">Turks and Caicos Islands</option><option value="Tuvalu">Tuvalu</option><option value="Uganda">Uganda</option><option value="Ukraine">Ukraine</option><option value="United Arab Emirates">United Arab Emirates</option><option value="United Kingdom">United Kingdom</option><option value="USA">USA</option><option value="Uruguay">Uruguay</option><option value="Uzbekistan">Uzbekistan</option><option value="Vanuatu">Vanuatu</option><option value="Venezuela">Venezuela</option><option value="Viet Nam">Viet Nam</option><option value="Virgin Islands">Virgin Islands</option><option value="Wake Island">Wake Island</option><option value="Wallis and Futuna">Wallis and Futuna</option><option value="West Bank">West Bank</option><option value="Western Sahara">Western Sahara</option><option value="Yemen">Yemen</option><option value="Yugoslavia">Yugoslavia</option><option value="Zambia">Zambia</option><option value="Zimbabwe">Zimbabwe</option>
 </select>

</div>

<div class="clearfix"></div>

<div class="col-md-12"><label for="Previous" class="control-label"> Previous Address </label>
  <input type="text" name="previous" placeholder="Apartment, suite, unit, building, floor, etc." class="form-control" value="<?php if(isset($footprint))echo $footprint->previous;?>" >
</div>

<div class="col-md-12" style="padding: 0px;">
  <div class="col-md-6"><label for="Workphone" class="control-label">  Work Phone: </label>
    <input type="tel" name="work_phone" class="form-control" value="<?php if(isset($footprint))echo $footprint->work_phone;?>" >
  </div>
  <div class="col-md-6"><label for="Homephone" class="control-label">  Home Phone: </label>
    <input type="tel" name="home_phone" role="phone" class="form-control" value="<?php if(isset($footprint))echo $footprint->home_phone;?>" >
  </div>
  <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<div class=" col-md-12"> <label class="control-label">Known Social Media Information (Twitter, Facebook, Blog, LinkedIn, etc.)</label></div>

<div class="col-md-12" style="padding: 0px;">
  <div class="col-md-4"><label for="Twitter" class="control-label">  Twitter: </label>
    <input type="url" name="twitter" class="form-control" value="<?php if(isset($footprint))echo $footprint->twitter;?>" >
  </div>
  <div class="col-md-4"><label for="Facebook" class="control-label">  Facebook: </label>
    <input type="url" name="facebook" class="form-control" value="<?php if(isset($footprint))echo $footprint->facebook;?>" >
  </div>
  <div class="col-md-4"><label for="Linkedin" class="control-label">   Linkedin: </label>
    <input type="url" name="linkedin" class="form-control" value="<?php if(isset($footprint))echo $footprint->linkedin;?>" >
  </div>
  <div class="clearfix"></div>
</div>

<div class="col-md-12" style="padding: 0px;">
  <div class="col-md-6"><label for="Blog" class="control-label">  Blog: </label>
    <input type="url" name="blog" class="form-control" value="<?php if(isset($footprint))echo $footprint->blog;?>" >
  </div>
  <div class="col-md-6"><label for="Other" class="control-label">  Other: </label>
    <input type="url" name="other" class="form-control" value="<?php if(isset($footprint))echo $footprint->other;?>" >
  </div>
  <div class="clearfix"></div>
</div>

<div class="col-md-12"><label for="license" class="control-label"> Driver's License Number </label>
  <input type="text" name="license" class="form-control" value="<?php if(isset($footprint))echo $footprint->license;?>" >
</div>

<div class="col-md-12" style="padding: 0px;">
  <div class="col-md-4"><label for="workplace" class="control-label">  Workplace Name </label>
    <input type="text"  name="workplace_name" class="form-control" value="<?php if(isset($footprint))echo $footprint->workplace_name;?>" >
  </div>
  <div class="col-md-8"><label for="WorkplaceAddress" class="control-label">   Workplace Address </label>
    <input type="text" name="workplaceaddress"  placeholder="Apartment, suite, unit, building, floor, etc." class="form-control" value="<?php if(isset($footprint))echo $footprint->fullname;?>" >
  </div>
  <div class="clearfix"></div>
</div>  

<div class="col-md-12"><label for="education" class="control-label"> Educational Institution(s) </label>
  <textarea id="education" name="education" class="form-control"><?php if(isset($footprint))echo $footprint->street_value;?>
  </textarea>
</div>

<div class="col-md-12"><label for="" class="control-label">  Names of relations (Friends, spouse/boyfriend/girlfriend/siblings/criminal relations/company relations) </label>
  <textarea id="relations" name="relations" class="form-control"><?php if(isset($footprint))echo $footprint->street_value;?>
  </textarea>
</div>


<div class="col-md-12"><label for="" class="control-label">Locations and/or establishments frequented (bars/restaurants/gyms/nightclubs/recreational activities/medical facilities) </label>
  <textarea id="locations" name="locations" class="form-control"><?php if(isset($footprint))echo $footprint->street_value;?>
  </textarea>
</div> 


<div class="col-md-12"><label for="" class="control-label"> Vehicle(s) </label>
  <textarea id="vechiles" name="vechiles" class="form-control"><?php if(isset($footprint))echo $footprint->street_value;?>
  </textarea>
</div>  

<div class="col-md-5">
<label for="whysearch" class="control-label">
  <span>Why Search</span>
</label>
  <select id="whysearch" name="whysearch" class="form-control">
    <option value="">&mdash;</option>
    <option value="1" <?php if(isset($footprint)&& $footprint->whysearch=='1')echo "selected='selected'";?>>Theft, Fraud and/or Counterfeiting</option>
    <option value="2" <?php if(isset($footprint)&& $footprint->whysearch=='2')echo "selected='selected'";?>>Drugs</option>
    <option value="3" <?php if(isset($footprint)&& $footprint->whysearch=='3')echo "selected='selected'";?>>Bullying</option>
    <option value="4" <?php if(isset($footprint)&& $footprint->whysearch=='4')echo "selected='selected'";?>>Workplace Violence and/or Weapons</option>
    <option value="5" <?php if(isset($footprint)&& $footprint->whysearch=='5')echo "selected='selected'";?>>Insurance</option>
  </select>

</div>
<div class="clearfix"></div>
<div style="display: none;" id="hire">
  <div class="col-md-12"><label class="control-label">List the product name(s) and/or item number(s)</label>
    <textarea name="productname" class="form-control"><?php if(isset($footprint))echo $footprint->street_value;?></textarea></div>
    <div class="col-md-12"><label class="control-label">Keyword Descriptors (separated by commas)</label>
      <textarea name="keyword" class="form-control"><?php if(isset($footprint))echo $footprint->street_value;?></textarea></div>
      <div class="col-md-12"><label class="control-label">Market Value Price</label>
        <input type="text" name="market_value" class="form-control" value="<?php if(isset($footprint))echo $footprint->market_value;?>" ></div>
      </div>


      <div style="display: none;" id="question">
        <div class="col-md-12">
          <label class="control-label">Include street value</label>
          <input type="text" name="street_value" class="form-control" value="<?php if(isset($footprint))echo $footprint->street_value;?>" >
        </div>
        <div class="col-md-12">
          <label class="control-label">Keywords for suspected narcotic (separated by commas)</label>
          <textarea name="narcotic" class="form-control"><?php if(isset($footprint))echo $footprint->street_value;?></textarea></div>
        </div>


        <div style="display: none;" id="hello">
          <div class="col-md-12">
            <label class="control-label">Names of co-workers: supervisors, managers, co-workers, suppliers:</label>
            <textarea name="coworkers" class="form-control"><?php if(isset($footprint))echo $footprint->street_value;?></textarea></div>
            <div class="col-md-12">
              <label class="control-label">Workplace associations or events: union, associations (plumbers, millwrights, electricians, etc), social events (golf tournament, picnic, holiday party)</label>
              <textarea name="workplace-events" class="form-control"><?php if(isset($footprint))echo $footprint->street_value;?></textarea></div>
              <div class="col-md-12">
                <label class="control-label">Names of equipment or areas utilized: forklift, machinery, vehicles, cafeteria, kitchen, boardroom, bathroom, smoker area</label>
                <textarea name="equipment" class="form-control"><?php if(isset($footprint))echo $footprint->street_value;?></textarea>
              </div>
            </div>

            <div style="" id="welcome">
              <div class="col-md-12">
                <label class="control-label">
                  Locations: intersections, road names, city, town, vacation destinations, airlines, hotels</label>
                  <textarea name="intersections" class="form-control"><?php if(isset($footprint))echo $footprint->street_value;?></textarea>
                </div>
              </div>
            <div class="clearfix"></div>


            <?php if($this->request->params['controller']!='Documents'){?>
            <div class="addattachment<?php echo $dx->id;?> form-group col-md-12"></div>
            <?php }?>
            <div class="clearfix"></div>
    </form>
    <script type="text/javascript">
      $(function(){
        <?php
        if(isset($footprint)){?>
            var con = '<?php echo $footprint->country;?>';
           
            $("#country option").each(function() {
               
                if(this.value==con)
                $(this).attr('selected','selected');
                
            });
            
            var val=$('#whysearch').val();
          if(val=='1')
            $('#hire').show();
          else 
            $('#hire').hide();

          if(val=='2')
            $('#question').show();
          else 
            $('#question').hide();

          if(val=='4')
            $('#hello').show();
          else 
            $('#hello').hide();
            
        <?php
        }?>
        $('#whysearch').change(function(){
          var val=$(this).val();
          if(val=='1')
            $('#hire').show();
          else 
            $('#hire').hide();

          if(val=='2')
            $('#question').show();
          else 
            $('#question').hide();

          if(val=='4')
            $('#hello').show();
          else 
            $('#hello').hide();
        })
      })
    </script>

   