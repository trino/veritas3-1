 <?php

$initials = W_ROOT;
ob_start();
error_reporting(1);
require_once('tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('ISBMEE');
$pdf->SetAuthor($this->request->session()->read('Profile.username'));
$pdf->SetTitle('Consent form');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
//echo PDF_FONT_MONOSPACED;die();
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Consent form '.$detail['consent']->order_id, 'by '.$this->request->session()->read('Profile.username'), array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 10, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print


//$html = file_get_contents($initials.$this->request->webroot.'pdfs/getConsent/'.$oid);
// Print text using writeHTMLCell()
//$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$pdf->Cell(80, 5, 'Surname:');
$pdf->TextField('last_name', 50, 5,array(),array('v'=>$detail['consent']->last_name, 'dv'=>$detail['consent']->last_name));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'First name:');
$pdf->TextField('first_name', 50, 5,array(),array('v'=>$detail['consent']->first_name, 'dv'=>$detail['consent']->first_name));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Middle name:');
$pdf->TextField('mid_name', 50, 5,array(),array('v'=>$detail['consent']->mid_name, 'dv'=>$detail['consent']->mid_name));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Previous surname(s) or maiden name(s)');
$pdf->TextField('previous_last_name', 50, 5,array(),array('v'=>$detail['consent']->previous_last_name, 'dv'=>$detail['consent']->previous_last_name));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Place of birth');
$pdf->TextField('place_birth_country', 50, 5,array(),array('v'=>$detail['consent']->place_birth_country, 'dv'=>$detail['consent']->place_birth_country));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Date of birth');
$pdf->TextField('birth_date', 50, 5,array(),array('v'=>$detail['consent']->birth_date, 'dv'=>$detail['consent']->birth_date));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Sex');
$pdf->TextField('sex', 50, 5,array(),array('v'=>$detail['consent']->sex, 'dv'=>$detail['consent']->sex));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Phone number');
$pdf->TextField('phone', 50, 5,array(),array('v'=>$detail['consent']->phone, 'dv'=>$detail['consent']->phone));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Current address (street and number)');
$pdf->TextField('current_street_address', 50, 5,array(),array('v'=>$detail['consent']->current_street_address, 'dv'=>$detail['consent']->current_street_address));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Current address (APT/Unit)');
$pdf->TextField('current_apt_unit', 50, 5,array(),array('v'=>$detail['consent']->current_apt_unit, 'dv'=>$detail['consent']->current_apt_unit));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Current address (City)');
$pdf->TextField('current_city', 50, 5,array(),array('v'=>$detail['consent']->current_city, 'dv'=>$detail['consent']->current_city));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Current address (Province)');
$pdf->TextField('current_province', 50, 5,array(),array('v'=>$detail['consent']->current_province, 'dv'=>$detail['consent']->current_province));
$pdf->Ln(6);


$pdf->Cell(80, 5, 'Current address (Postal Code)');
$pdf->TextField('current_postal_code', 50, 5,array(),array('v'=>$detail['consent']->current_postal_code, 'dv'=>$detail['consent']->current_postal_code));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Previous address (street and number)');
$pdf->TextField('previous_street_address', 50, 5,array(),array('v'=>$detail['consent']->previous_street_address, 'dv'=>$detail['consent']->previous_street_address));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Previous address (APT/Unit)');
$pdf->TextField('previous_apt_unit', 50, 5,array(),array('v'=>$detail['consent']->previous_apt_unit, 'dv'=>$detail['consent']->previous_apt_unit));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Previous address (City)');
$pdf->TextField('previous_city', 50, 5,array(),array('v'=>$detail['consent']->previous_city, 'dv'=>$detail['consent']->previous_city));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Previous address (Province)');
$pdf->TextField('previous_province', 50, 5,array(),array('v'=>$detail['consent']->previous_province, 'dv'=>$detail['consent']->previous_province));
$pdf->Ln(6);


$pdf->Cell(80, 5, 'Previous address (Postal Code)');
$pdf->TextField('previous_postal_code', 50, 5,array(),array('v'=>$detail['consent']->previous_postal_code, 'dv'=>$detail['consent']->previous_postal_code));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Aliases');
$pdf->TextField('aliases', 50, 5,array(),array('v'=>$detail['consent']->aliases, 'dv'=>$detail['consent']->aliases));
$pdf->Ln(6);

$pdf->Cell(80, 5, 'Drivers License Number');
$pdf->TextField('driver_license_number', 50, 5,array(),array('v'=>$detail['consent']->driver_license_number, 'dv'=>$detail['consent']->driver_license_number));
$pdf->Ln(6);


$pdf->Cell(80, 5, 'Province (Driver\'s License was issued)');
$pdf->TextField('driver_license_issued', 50, 5,array(),array('v'=>$detail['consent']->driver_license_issued, 'dv'=>$detail['consent']->driver_license_issued));
$pdf->Ln(6);


$pdf->Cell(80, 5, 'Applicants Email)');
$pdf->TextField('applicants_email', 50, 5,array(),array('v'=>$detail['consent']->applicants_email, 'dv'=>$detail['consent']->applicants_email));
$pdf->Ln(6);

$hereby = "<p><br/>I hereby consent to the search of the following:</p>
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
        
        
     
     
        
            <h4>*Authorization to Release Clearance Report or Any Police Information</h4>
            <p>I certify that the information I have supplied is correct and true to the best of my knowledge. I consent to the release of a Criminal Record or any Criminal Information to ISB Canada and its partners, and to the Organization Requesting Search named below and its designated agents and/or partners. All data is subject to provincial, state, and federal privacy legislation.</p>
            <p>The criminal record search will be performed by a police service. I hereby release and forever discharge all members and employees of the Processing Police Service from any and all actions, claims and demands for damages, loss or injury howsoever arising which may hereafter be sustained by myself or as a result of the disclosure of information by the Processing Police Service to ISB Canada and its partners.</p>
            <p>*I hereby release and forever discharge all agents from any claims, actions demands for damages, injury or loss which may arise as a result of the disclosure of information by any of the information sources including but not limited to the Credit Bureau or Department of Motor Vehicles to the designated agents and/or their partners and representatives. </p>
            <p>*I am aware and I give consent that the records named above may be transmitted electronically or in hard copy within Canada and to the country from where the search was requested as indicated below. By signing this waiver, I acknowledge full understanding of the content on this consent form.</p>
        
        
     
     
        
            Applicant's Signature- by signing this form you agree and consent to the terms and release of information listed on this form: ";
            $hereby = $hereby."<br/><br/><p>
                            <strong>Signature of Driver</strong><br />";
                if(isset($detail['consent']) && $detail['consent']->criminal_signature_applicant2){
                    $hereby = $hereby."<img src=\"".$initials.$this->request->webroot.'canvas/'.$detail['consent']->criminal_signature_applicant2."\" style=\"max-width: 100%;\" />";
                    }
                $hereby = $hereby."<p>
                            <strong>Signature of Company Witness</strong><br />";
                if(isset($detail['consent']) && $detail['consent']->signature_company_witness2){
                    $hereby = $hereby."<img src=\"".$initials.$this->request->webroot.'canvas/'.$detail['consent']->signature_company_witness2."\" style=\"max-width: 100%;\" /><br/>";
                      }      
                
   // $pdf->writeHTMLCell(0, 0, '', '', $attach, 0, 1, 0, true, '', true);
            $pdf->writeHTMLCell(0, 0, '', '', $hereby, 0, 1, 0, true, '', true);
   
    $pdf->Cell(80, 5, 'Company Name Requesting Search)');
    $pdf->TextField('company_name_requesting', 50, 5,array(),array('v'=>$detail['consent']->company_name_requesting, 'dv'=>$detail['consent']->company_name_requesting));
    $pdf->Ln(6); 
    
    $pdf->Cell(80, 5, 'Printed Name of Company Witness)');
    $pdf->TextField('printed_name_company_witness', 50, 5,array(),array('v'=>$detail['consent']->printed_name_company_witness, 'dv'=>$detail['consent']->printed_name_company_witness));
    $pdf->Ln(6);
    
    $pdf->Cell(80, 5, 'Company Location (Country)');
    $pdf->TextField('company_location', 50, 5,array(),array('v'=>$detail['consent']->company_location, 'dv'=>$detail['consent']->company_location));
    $pdf->Ln(6);
    
    $certify = "<br/><br/><b>Declaration of Criminal Record</b>
                <p>*When declaration is submitted, it must be accompanied by the Consent for the Release of Police Information form.</p>
                <p/><strong>PART 1 - DECLARATION OF CRIMINAL RECORD (if applicable) - Completed by Applicant</strong></p>";
    
    $pdf->writeHTMLCell(0, 0, '', '', $certify, 0, 1, 0, true, '', true);
    
    $pdf->Cell(80, 5, 'Surname');
    $pdf->TextField('criminal_surname', 50, 5,array(),array('v'=>$detail['consent']->criminal_surname, 'dv'=>$detail['consent']->criminal_surname));
    $pdf->Ln(6);
    
    $pdf->Cell(80, 5, 'Given name');
    $pdf->TextField('criminal_given_name', 50, 5,array(),array('v'=>$detail['consent']->criminal_given_name, 'dv'=>$detail['consent']->criminal_given_name));
    $pdf->Ln(6);
    
    $pdf->Cell(80, 5, 'Sex');
    $pdf->TextField('criminal_sex', 50, 5,array(),array('v'=>$detail['consent']->criminal_sex, 'dv'=>$detail['consent']->criminal_sex));
    $pdf->Ln(6);
    
    $pdf->Cell(80, 5, 'Date of Birth');
    $pdf->TextField('criminal_date_birth', 50, 5,array(),array('v'=>$detail['consent']->criminal_date_birth, 'dv'=>$detail['consent']->criminal_date_birth));
    $pdf->Ln(6);
    
    $pdf->Cell(80, 5, 'Current address');
    $pdf->TextField('criminal_current_address', 50, 5,array(),array('v'=>$detail['consent']->criminal_current_address, 'dv'=>$detail['consent']->criminal_current_address));
    $pdf->Ln(6);
    
    $pdf->Cell(80, 5, 'Current Province');
    $pdf->TextField('criminal_current_province', 50, 5,array(),array('v'=>$detail['consent']->criminal_current_province, 'dv'=>$detail['consent']->criminal_current_province));
    $pdf->Ln(6);
    
    $pdf->Cell(80, 5, 'Current postal code');
    $pdf->TextField('criminal_current_postal_code', 50, 5,array(),array('v'=>$detail['consent']->criminal_current_postal_code, 'dv'=>$detail['consent']->criminal_current_postal_code));
    $pdf->Ln(6);
    
    $pdf->Cell(80, 5, 'Date');
    $pdf->TextField('criminal_date', 50, 5,array(),array('v'=>$detail['consent']->criminal_date, 'dv'=>$detail['consent']->criminal_date));
    $pdf->Ln(6);
    
    $declare= "<br/><br/><strong>DECLARATION OF CRIMINAL RECORD</strong>
                <ul>
                    <li>does not constitute a Certified Criminal Record by the RCMP</li>
                    <li>may not contain all criminal record convictions.</li>
                </ul>
                <strong>DO NOT DECLARE THE FOLLOWING:</strong>
                <ul>
                    <li>Absolute discharges or Conditional discharges, pursuant to the Criminal Code, section 730.</li>
                    <li>Any charges for which you have received a Pardon, pursuant to the Criminal Records Act.</li>
                    <li>Any offences while you were a \"young person\" (twelve years old but less than eighteen years old), pursuant to the Youth Criminal Justice Act.</li>
                    <li>Any charges for which you were not convicted, for example, charges that were withdrawn, dismissed, etc.</li>
                    <li>Any provincial or municipal offences.</li>
                    <li>Any charges dealt with outside of Canada.</li>
                </ul>
                <strong>NOTE:</strong>
                <p>A Certified Criminal Record can only be issued based on the submission of fingerprints to the RCMP National Repository of Criminal Records.</p>
                <br/>
                <table style=\"width: 100%;\">
                <tr><td><strong>Offence</strong></td><td><strong>Date of Sentence</strong></td><td><strong>Location</strong></td></tr>
                ";
                
                //var_dump($cri);die();
                
                if($cri)
                {
                    foreach($cri as $criminal)
                    {
                        
                        $declare = $declare.'<tr><td>'.$criminal->offence.'</td><td>'.$criminal->date_of_sentence.'</td><td>'.$criminal->location.'</td></tr>';
                       
                    }                    
                }
                
                $declare = $declare.'</table>';
                $declare = $declare."<p>
                <strong>Mandatory use for all account holders</strong>
                <br/>
                <strong>Important Notice Regarding Background Reports From The PSP Online Service</strong>
                </p>
                <p>1.&nbsp;&nbsp;In connection with your application for employment with <strong>".$detail['consent']->psp_employer."</strong> (\"Prospective Employer\"), Prospective Employer,</div><br /><br /> its employees, agents or contractors may obtain one or more reports regarding your driving, and safety inspection history from the Federal Motor Carrier Safety Administration (FMCSA).</p>
                <p>When the application for employment is submitted in person, if the Prospective Employer uses any information it obtains from FMCSA in a decision to not hire you or to make any other adverse employment decision regarding you, the Prospective Employer will provide you with a copy of the report upon which its decision was based and a written summary of your rights under the Fair Credit Reporting Act before taking any final adverse action. If any final adverse action is taken against you based upon your driving history or safety report, the Prospective Employer will notify you that the action has been taken and that the action was based in part or in whole on this report.</p>
                <p>When the application for employment is submitted by mail, telephone, computer, or other similar means, if the Prospective Employer uses any information it obtains from FMCSA in a decision to not hire you or to make any other adverse employment decision regarding you, the Prospective Employer must provide you within three business days of taking adverse action oral, written or electronic notification: that adverse action has been taken based in whole or in part on information obtained  from FMCSA; the name, address, and the toll free telephone number of FMCSA; that the FMCSA did not make the decision to take the adverse action and is unable to provide you the specific reasons why the adverse action was taken; and that you may, upon providing proper identification, request a free copy of the report and may dispute with the FMCSA the accuracy or completeness of any information or report. If you request a copy of a driver record from the Prospective Employer who procured the report, then, within 3 business days of receiving your request, together with proper identification, the Prospective Employer must send or provide to you a copy of your report and a summary of your rights under the Fair Credit Reporting Act.</p>
                <p>The Prospective Employer cannot obtain background reports from FMCSA unless you consent in writing.</p>
                <p>If you agree that the Prospective Employer may obtain such background reports, please read the following and sign below:</p>
                
                <br/>
                <p>2.&nbsp;&nbsp;I authorize <strong>".$detail['consent']->authorize_name_hereby."</strong> (\"Prospective Employer\") to access the FMCSA Pre-Employment Screening Program PSP</div></p><br /><br />
                <p>system to seek information regarding my commercial driving safety record and information regarding my safety inspection history. I understand that I am consenting to the release of safety performance information including crash data from the previous five (5) years and inspection history from the previous three (3) years. I understand and acknowledge that this release of information may assist the Prospective Employer to make a determination regarding my suitability as an employee.</p>
                <p>3.&nbsp;&nbsp;I further understand that neither the Prospective Employer nor the FMCSA contractor supplying the crash and safety information has the capability to correct any safety data that appears to be incorrect. I understand I may challenge the accuracy of the data by submitting a request to https://dataqs.fmcsa.dot.gov. If I am challenging crash or inspection information reported by a State, FMCSA cannot change or correct this data. I understand my request will be forwarded by the DataQs system to the appropriate State for adjudication.</p>
                <p>4.&nbsp;&nbsp;Please note: Any crash or inspection in which you were involved will display on your PSP report. Since the PSP report does not report, or assign, or imply fault, it will include all Commercial Motor Vehicle (CMV) crashes where you were a driver or co-driver and where those crashes were reported to FMCSA, regardless of fault. Similarly, all inspections, with or without violations, appear on the PSP report. State citations associated with FMCSR violations that have been adjudicated by a court of law will also appear, and remain, on a PSP report.</p>
                <p>I have read the above Notice Regarding Background Reports provided to me by Prospective Employer and I understand that if I sign this consent form, Prospective Employer may obtain a report of my crash and inspection history. I hereby authorize Prospective Employer and its employees, authorized agents, and/or affiliates to obtain the information authorized above.
                <br /><br/>";
                $pdf->writeHTMLCell(0, 0, '', '', $declare, 0, 1, 0, true, '', true);
                
                $pdf->Cell(80, 5, 'Date');
                $pdf->TextField('authorize_date', 50, 5,array(),array('v'=>$detail['consent']->authorize_date, 'dv'=>$detail['consent']->authorize_date));
                $pdf->Ln(6);
                
                $pdf->Cell(80, 5, 'Name(Please Print)');
                $pdf->TextField('authorize_name', 50, 5,array(),array('v'=>$detail['consent']->authorize_name, 'dv'=>$detail['consent']->authorize_name));
                $pdf->Ln(6);
                
                $attach = "
                <p><br/>NOTICE: This form is made available to monthly account holders by NICT on behalf of the U.S. Department of Transportation, Federal Motor Carrier Safety Administration (FMCSA). Account holders are required by federal law to obtain an Applicant's written or electronic consent prior to accessing the Applicant's PSP report. Further, account holders are required by FMCSA to use the language provided in paragraphs 1-4 of this document to obtain an Applicant's consent. The language must be used in whole, exactly as provided. The language may be included with other consent forms or language at the discretion of the account holder, provided the four paragraphs remain intact and the language is unchanged.</p>
                    <p>LAST UPDATED 10/29/2012</p>
                <br/><br/><strong>Attachments</strong>
                <br/>
                ";
                
                
                //$initials = $this->requestAction('/pages/getBase');
                
                
                
                if($att)
                {
                   $doc_ext = array('pdf',
                    'doc',
                    'docx',
                    'txt',
                    'xlsx',
                    'xls',
                    'csv');
                   $vid_ext = array('mp4');
                   
                   
                
                    foreach($att as $a)
                    {
                        $ext_arr = explode('.', $a->attachment);
                                            $ext = end($ext_arr);
                                            $ext = strtolower($ext);
                                            if (!in_array($ext, $doc_ext) && !in_array($ext,$vid_ext) && file_exists(APP."../webroot/attachments/".$a->attachment)) {
                    
                        $attach = $attach."<p><img src=\"".$initials.$this->request->webroot."attachments/".$a->attachment."\" /><br /></p>";
                        }
                        
                    }
                    
                }
                 
                $attach = $attach."<br/><br/><p>
                            <strong>Signature of Driver</strong><br />";
                if(isset($detail['consent']) && $detail['consent']->criminal_signature_applicant){
                    $attach = $attach."<img src=\"".$initials.$this->request->webroot.'canvas/'.$detail['consent']->criminal_signature_applicant."\" style=\"max-width: 100%;\" />";
                    }
                $attach = $attach."<p>
                            <strong>Signature of Company Witness</strong><br />";
                if(isset($detail['consent']) && $detail['consent']->signature_company_witness){
                    $attach = $attach."<img src=\"".$initials.$this->request->webroot.'canvas/'.$detail['consent']->signature_company_witness."\" style=\"max-width: 100%;\" /><br/>";
                      }      
                
    $pdf->writeHTMLCell(0, 0, '', '', $attach, 0, 1, 0, true, '', true);

    ob_end_clean();
    $pdf->Output('Consent_Form.pdf', 'F',$oid);
 ?>