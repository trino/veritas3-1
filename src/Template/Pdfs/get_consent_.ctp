
<h3 class="col-md-12">Consent for the release of police information and disclosure of personal information</h3>

			<div class="form-body">
                <div class="form-group col-md-12">
    				<label class="control-label col-md-2">Surname : </label>
    				<div class="col-md-2">
    					<input type="text" class="form-control" name="last_name" value="<?php echo $detail['consent']->last_name;?>"/>
    				</div>
                    <label class="control-label col-md-2">First Name : </label>
                    <div class="col-md-2">
    					<input type="text" class="form-control" name="first_name" value="<?php echo $detail['consent']->first_name;?>"/>
    				</div>
                    <label class="control-label col-md-2">Middle Name : </label>
                    <div class="col-md-2">
    					<input type="text" class="form-control" name="mid_name" value="<?php echo $detail['consent']->mname;?>"/>
    				</div>
                </div>
                
                <div class="form-group col-md-12">
    				<label class="control-label col-md-4">Previous Surname(s) or Maiden Name(s) : </label>
    				<div class="col-md-2">
    					<input type="text" class="form-control" name="previous_last_name" value="<?php echo $detail['consent']->previous_last_name;?>"/>
    				</div>
                    <label class="control-label col-md-3">Place of Birth(Country) : </label>
                    <div class="col-md-3">
    					<input type="text" class="form-control" name="place_birth_country" value="<?php echo $detail['consent']->place_birth_country;?>"/>
    				</div>
                </div>
                
                 <div class="form-group col-md-12">
    				<label class="control-label col-md-2">Date of Birth : </label>
    				<div class="col-md-2">
    					<input type="text" class="form-control date-picker" placeholder="YYYY-MM-DD" name="birth_date" value="<?php echo $detail['consent']->birth_date;?>" />
    				</div>
                    <label class="control-label col-md-2">Sex : </label>
                    <div class="col-md-2">
    					<input type="text" class="form-control" name="sex" value="<?php echo $detail['consent']->sex;?>"/>
    				</div>
                    <label class="control-label col-md-2">Phone Number : </label>
                    <div class="col-md-2">
    					<input type="text" class="form-control" name="phone" value="<?php echo $detail['consent']->phone;?>"/>
    				</div>
                </div>
                
                <div class="form-group col-md-6">
    				<label class="control-label col-md-4">Current Address : </label>
                </div>
                <div class="form-group col-md-12">
    				<div class="col-md-3">
    					<input type="text" class="form-control" placeholder="Street and Number" name="current_street_address" value="<?php echo $detail['consent']->current_street_address;?>" />
    				</div>
                    <div class="col-md-2">
    					<input type="text" class="form-control" placeholder="Apt/Unit" name="current_apt_unit" value="<?php echo $detail['consent']->current_apt_unit;?>"/>
    				</div>
                    <div class="col-md-2">
    					<input type="text" class="form-control" placeholder="City" name="current_city" value="<?php echo $detail['consent']->current_city;?>"/>
    				</div>
                    <div class="col-md-2">
    					<input type="text" class="form-control" placeholder="Province" name="current_province" value="<?php echo $detail['consent']->current_province;?>"/>
    				</div>
                    <div class="col-md-3">
    					<input type="text" class="form-control" placeholder="Postal Code" name="current_postal_code" value="<?php echo $detail['consent']->current_postal_code;?>"/>
    				</div>
                </div>
                
                <div class="form-group col-md-12">
    				<label class="control-label col-md-7">Previous Address (if you have not lived at Current Address for more than 5 years): </label>
                </div>
                <div class="form-group col-md-12">
    				<div class="col-md-3">
    					<input type="text" class="form-control" placeholder="Street and Number" name="previous_street_address" value="<?php echo $detail['consent']->previous_street_address;?>"/>
    				</div>
                    <div class="col-md-2">
    					<input type="text" class="form-control" placeholder="Apt/Unit" name="previous_apt_unit" value="<?php echo $detail['consent']->previous_apt_unit;?>"/>
    				</div>
                    <div class="col-md-2">
    					<input type="text" class="form-control" placeholder="City" name="previous_city" value="<?php echo $detail['consent']->previous_city;?>"/>
    				</div>
                    <div class="col-md-2">
    					<input type="text" class="form-control" placeholder="Province" name="previous_province" value="<?php echo $detail['consent']->last_name;?>"/>
    				</div>
                    <div class="col-md-3">
    					<input type="text" class="form-control" placeholder="Postal Code" name="previous_postal_code" value="<?php echo $detail['consent']->previous_postal_code;?>"/>
    				</div>
                </div>

                <div class="form-group col-md-12">
                    <label class="control-label col-md-3">Aliases : </label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="aliases" value="<?php echo $detail['consent']->aliases;?>"/>
                    </div>
                    <label class="control-label col-md-3">Drivers License Number : </label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="driver_license_number" value="<?php echo $detail['consent']->driver_license_number;?>"/>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label class="control-label col-md-3">Province (Driver's License was issued) : </label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="driver_license_issued" value="<?php echo $detail['consent']->driver_license_issued;?>"/>
                    </div>
                    <label class="control-label col-md-3">Applicants Email : </label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="applicants_email" value="<?php echo $detail['consent']->email;?>"/>
                    </div>
                </div>


                <div class="col-md-12">
                    <p>I hereby consent to the search of the following:</p>
                    <ul>
                        <li>Driver Record/ Abstract - Please specify Province or State (Region where Driver's License Issued)</li>
                        <li>Insurance History - Please specify Province or State (Region where Driver's License Issued)</li>
                        <li>CVOR</li>
                        <li>Education Verification</li>
                        <li>TransClick (Aptitude Test)</li>
                        <li>Check DL</li>
                        <li>Employment Verification (Drug test information and Claims History)</li>
                        <li>Credit Check</li>

                    </ul>
                    <p>I hereby consent to a criminal record search (Adult) through both the: </p>
                    <ul>
                        <li>Local Police Records which includes Police Information Portal (PIP) Firearms Interest Person (FIP) and Niche RMS</li>
                        <li>RCMP National Repository of Criminal Records which will be conducted based on name(s), date of birth and declared criminal record (as per Section 9.6.4 of the CCRTIS Dissemination policy)</li>
                    </ul>
                </div>
                
                <div class="col-md-12">
                <h4>*Authorization to Release Clearance Report or Any Police Information</h4>
                <p>I certify that the information I have supplied is correct and true to the best of my knowledge. I consent to the release of a Criminal Record or any Criminal Information to ISB Canada and its partners, and to the Organization Requesting Search named below and its designated agents and/or partners. All data is subject to provincial, state, and federal privacy legislation.</p>
                <p>The criminal record search will be performed by a police service. I hereby release and forever discharge all members and employees of the Processing Police Service from any and all actions, claims and demands for damages, loss or injury howsoever arising which may hereafter be sustained by myself or as a result of the disclosure of information by the Processing Police Service to ISB Canada and its partners.</p>
                <p>*I hereby release and forever discharge all agents from any claims, actions demands for damages, injury or loss which may arise as a result of the disclosure of information by any of the information sources including but not limited to the Credit Bureau or Department of Motor Vehicles to the designated agents and/or their partners and representatives. </p>
                <p>*I am aware and I give consent that the records named above may be transmitted electronically or in hard copy within Canada and to the country from where the search was requested as indicated below. By signing this waiver, I acknowledge full understanding of the content on this consent form.</p>
                </div>
                
                <div class="form-group col-md-12">
    				<label class="control-label col-md-11">Applicant's Signature- by signing this form you agree and consent to the terms and release of information listed on this form : </label>
    				<div class="col-md-1">
    					<input type="text" class="form-control" name="applicant_signature_agree" value="<?php echo $detail['consent']->applicant_signature_agree;?>"/>
    				</div>
                </div>
                <div class="form-group col-md-12">
                    <label class="control-label col-md-4">Company Name Requesting Search : </label>
                    <div class="col-md-2">
    					<input type="text" class="form-control" name="company_name_requesting" value="<?php echo $detail['consent']->company_name_requesting;?>"/>
    				</div>
                    <label class="control-label col-md-4">Printed Name of Company Witness : </label>
                    <div class="col-md-2">
    					<input type="text" class="form-control" name="printed_name_company_witness" value="<?php echo $detail['consent']->printed_name_company_witness;?>"/>
    				</div>
                </div>
                
                <div class="form-group col-md-12">
                    <label class="control-label col-md-4">Company Location (Country): </label>
                    <div class="col-md-2">
    					<input type="text" class="form-control" name="company_location" value="<?php echo $detail['consent']->company_location;?>"/>
    				</div>
                    
                </div>
                
            <div class="clearfix"></div>
            </div>




<div class="clearfix"></div>
<hr />

		<h3 class="col-md-12">
			Declaration of Criminal Record
		</h3>
			<div class="form-body">
            <p>*When declaration is submitted, it must be accompanied by the Consent for the Release of Police Information form.</p>
            <h4>PART 1 - DECLARATION OF CRIMINAL RECORD (if applicable) - Completed by Applicant</h4>
            
                <div class="form-group col-md-12">
                    <label class="control-label col-md-3">Surname: </label>
                    <div class="col-md-3">
    					<input type="text" class="form-control" name="criminal_surname" value="<?php echo $detail['consent']->criminal_surname;?>"/>
    				</div>
                    <label class="control-label col-md-3">Given Name: </label>
                    <div class="col-md-3">
    					<input type="text" class="form-control" name="criminal_given_name" value="<?php echo $detail['consent']->criminal_given_name;?>"/>
    				</div>
                </div>
                
                <div class="form-group col-md-12">
                    <label class="control-label col-md-3">Sex: </label>
                    <div class="col-md-3">
    					<input type="text" class="form-control" name="criminal_sex" value="<?php echo $detail['consent']->criminal_sex;?>"/>
    				</div>
                    <label class="control-label col-md-3">Date of Birth : </label>
                    <div class="col-md-3">
    					<input type="text" class="form-control date-picker" placeholder="YYYY-MM-DD" name="criminal_date_birth" value="<?php echo $detail['consent']->criminal_date_birth;?>"/>
    				</div>
                </div>
                
                 <div class="form-group col-md-12">
                    <label class="control-label col-md-3">Current Address: </label>
                    <div class="col-md-3">
    					<input type="text" class="form-control" placeholder="Address" name="criminal_current_address" value="<?php echo $detail['consent']->criminal_current_address;?>"/>
    				</div>
                    <div class="col-md-3">
    					<input type="text" class="form-control" placeholder="Province" name="criminal_current_province" value="<?php echo $detail['consent']->criminal_current_province;?>"/>
    				</div>
                    <div class="col-md-3">
    					<input type="text" class="form-control" placeholder="Postal Code" name="criminal_current_postal_code" value="<?php echo $detail['consent']->criminal_current_postal_code;?>"/>
    				</div>
                </div>
                
                <div class="form-group col-md-12">
                    
                    <label class="control-label col-md-3">Date: </label>
                    <div class="col-md-3">
    					<input type="text" class="form-control date-picker" placeholder="YYYY-MM-DD" name="criminal_date" value="<?php echo $detail['consent']->criminal_date;?>"/>
    				</div>
                </div>
                
                <div class="col-md-12">
                <strong>DECLARATION OF CRIMINAL RECORD</strong>
                <ul>
                    <li>does not constitute a Certified Criminal Record by the RCMP</li>
                    <li>may not contain all criminal record convictions.</li>
                </ul>
                </div>
                
                <div class="col-md-12">
                <strong>DO NOT DECLARE THE FOLLOWING:</strong>
                <ul>
                    <li>Absolute discharges or Conditional discharges, pursuant to the Criminal Code, section 730.</li>
                    <li>Any charges for which you have received a Pardon, pursuant to the Criminal Records Act.</li>
                    <li>Any offences while you were a "young person" (twelve years old but less than eighteen years old), pursuant to the Youth Criminal Justice Act.</li>
                    <li>Any charges for which you were not convicted, for example, charges that were withdrawn, dismissed, etc.</li>
                    <li>Any provincial or municipal offences.</li>
                    <li>Any charges dealt with outside of Canada.</li>
                </ul>
                </div>
                
                <div class="col-md-12">
                <strong>NOTE:</strong>
                <p>A Certified Criminal Record can only be issued based on the submission of fingerprints to the RCMP National Repository of Criminal Records.</p>
                </div>
                
                <div class="table-responsive">
                    <table class="table">
                    <thead>
                        <tr>
                            <th>Offence</th>
                            <th>Date of Sentence</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <?php
                    $i=0;
                    if($cri)
                        {
                            foreach($cri as $con_cri)
                            {
                        $co[$i] = $con_cri->offence;
                        $cd[$i] = $con_cri->date_of_sentence;
                        $cl[$i] = $con_cri->location;
                        
                        $i++;
                    }
                    }
                    if($i<=7)
                    {
                        for($j=$i;$j<=7;$j++)
                        {
                            $co[$j] = '';
                        $cd[$j] = '';
                        $cl[$j] = '';
                        }
                    }
                    
                    ?>
                    <?php
                    for($k=0;$k<8;$k++)
                    {
                        ?>
                        <tr>
                        <td><input type="text" class="form-control" name="offence[]" value="<?php echo $co[$k];?>" /></td>
                        <td><input type="text" class="form-control date-picker" name="date_of_sentence[]" value="<?php echo $cd[$k];?>" /></td>
                        <td><input type="text" class="form-control" name="location[]" value="<?php echo $cl[$k];?>"/></td>
                    </tr>
                        <?php
                    }
                    ?>
                    
                    </table>
                </div>
                
            <div class="clearfix"></div>
            </div>
            
            <div class="clearfix"></div>
<hr />

		<h3 class="col-md-12">
			Mandatory use for all account holders
		</h3>
			
            <h4>Important Notice Regarding Background Reports From The PSP Online Service</h4>
            <div class="col-md-12">
                <p><div class="col-md-5">1.&nbsp;&nbsp;In connection with your application for employment with</div> <div class="col-md-3"><input type="text" class="form-control" value="<?php echo $detail['consent']->psp_employer;?>" name="psp_employer" /></div><div class="col-md-4">("Prospective Employer"), Prospective Employer,</div><br /><br /> its employees, agents or contractors may obtain one or more reports regarding your driving, and safety inspection history from the Federal Motor Carrier Safety Administration (FMCSA).</p>
                <p>When the application for employment is submitted in person, if the Prospective Employer uses any information it obtains from FMCSA in a decision to not hire you or to make any other adverse employment decision regarding you, the Prospective Employer will provide you with a copy of the report upon which its decision was based and a written summary of your rights under the Fair Credit Reporting Act before taking any final adverse action. If any final adverse action is taken against you based upon your driving history or safety report, the Prospective Employer will notify you that the action has been taken and that the action was based in part or in whole on this report.</p>
                <p>When the application for employment is submitted by mail, telephone, computer, or other similar means, if the Prospective Employer uses any information it obtains from FMCSA in a decision to not hire you or to make any other adverse employment decision regarding you, the Prospective Employer must provide you within three business days of taking adverse action oral, written or electronic notification: that adverse action has been taken based in whole or in part on information obtained  from FMCSA; the name, address, and the toll free telephone number of FMCSA; that the FMCSA did not make the decision to take the adverse action and is unable to provide you the specific reasons why the adverse action was taken; and that you may, upon providing proper identification, request a free copy of the report and may dispute with the FMCSA the accuracy or completeness of any information or report. If you request a copy of a driver record from the Prospective Employer who procured the report, then, within 3 business days of receiving your request, together with proper identification, the Prospective Employer must send or provide to you a copy of your report and a summary of your rights under the Fair Credit Reporting Act.</p>
                <p>The Prospective Employer cannot obtain background reports from FMCSA unless you consent in writing.</p>
                <p>If you agree that the Prospective Employer may obtain such background reports, please read the following and sign below:</p>
            </div>
            <div class="col-md-12">
                <p><div class="col-md-2">2.&nbsp;&nbsp;I authorize</div><div class="col-md-3"><input type="text" class="form-control" name="authorize_name_hereby" value="<?php echo $detail['consent']->authorize_name_hereby;?>" /></div><div class="col-md-7">("Prospective Employer") to access the FMCSA Pre-Employment Screening Program PSP</div></p><br /><br />
                <p>system to seek information regarding my commercial driving safety record and information regarding my safety inspection history. I understand that I am consenting to the release of safety performance information including crash data from the previous five (5) years and inspection history from the previous three (3) years. I understand and acknowledge that this release of information may assist the Prospective Employer to make a determination regarding my suitability as an employee.</p>
                <p>3.&nbsp;&nbsp;I further understand that neither the Prospective Employer nor the FMCSA contractor supplying the crash and safety information has the capability to correct any safety data that appears to be incorrect. I understand I may challenge the accuracy of the data by submitting a request to https://dataqs.fmcsa.dot.gov. If I am challenging crash or inspection information reported by a State, FMCSA cannot change or correct this data. I understand my request will be forwarded by the DataQs system to the appropriate State for adjudication.</p>
                <p>4.&nbsp;&nbsp;Please note: Any crash or inspection in which you were involved will display on your PSP report. Since the PSP report does not report, or assign, or imply fault, it will include all Commercial Motor Vehicle (CMV) crashes where you were a driver or co-driver and where those crashes were reported to FMCSA, regardless of fault. Similarly, all inspections, with or without violations, appear on the PSP report. State citations associated with FMCSR violations that have been adjudicated by a court of law will also appear, and remain, on a PSP report.</p>
                <p>I have read the above Notice Regarding Background Reports provided to me by Prospective Employer and I understand that if I sign this consent form, Prospective Employer may obtain a report of my crash and inspection history. I hereby authorize Prospective Employer and its employees, authorized agents, and/or affiliates to obtain the information authorized above.</p>
            </div>
            
            <div class="form-group col-md-12">
                    <label class="control-label col-md-3">Date: </label>
                    <div class="col-md-3">
    					<input type="text" class="form-control date-picker" name="authorize_date" value="<?php echo $detail['consent']->authorize_date;?>"/>
    				</div>
                    
                    <input type="hidden" class="form-control" name="authorize_signature"/>
                </div>
                <div class="form-group col-md-12">
                    <label class="control-label col-md-3">Name(Please Print): </label>
                    <div class="col-md-9">
    					<input type="text" class="form-control" name="authorize_name" value="<?php echo $detail['consent']->authorize_name;?>"/>
    				</div>
                </div>
                <div class="col-md-12">
                    <p>NOTICE: This form is made available to monthly account holders by NICT on behalf of the U.S. Department of Transportation, Federal Motor Carrier Safety Administration (FMCSA). Account holders are required by federal law to obtain an Applicant's written or electronic consent prior to accessing the Applicant's PSP report. Further, account holders are required by FMCSA to use the language provided in paragraphs 1-4 of this document to obtain an Applicant's consent. The language must be used in whole, exactly as provided. The language may be included with other consent forms or language at the discretion of the account holder, provided the four paragraphs remain intact and the language is unchanged.</p>
                    <p>LAST UPDATED 10/29/2012</p>
                </div>
                
                <br />
<strong>Attachments</strong>
<p>&nbsp;</p>
<?php
if($_SERVER['SERVER_NAME']=='localhost')
$initials = 'http://localhost';
else
$initials = 'http://isbmeereports.com';
if($att)
{
    foreach($att as $a)
    {
        ?>
        <img src="<?php echo $initials.$this->request->webroot;?>attachments/<?php echo $a->attach_doc;?>" /><br /><br />
        <?php
    }
}
?>
<p>
<strong>Signature of Driver</strong><br />
<?php if(isset($detail['consent']) && $detail['consent']->criminal_signature_applicant){?><img src="<?php echo $this->request->webroot.'canvas/'.$detail['consent']->criminal_signature_applicant;?>" style="max-width: 100%;" /><?php }?>
</p>
<p>
<strong>Signature of Company Witness</strong><br />
<?php if(isset($detail['consent']) && $detail['consent']->signature_company_witness){?><img src="<?php echo $this->request->webroot.'canvas/'.$detail['consent']->signature_company_witness;?>" style="max-width: 100%;" /><?php }?>
</p>