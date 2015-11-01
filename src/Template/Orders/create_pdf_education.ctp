 <?php
 
ob_start();
require_once('tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('ISBMEE');
$pdf->SetAuthor($this->request->session()->read('Profile.username'));
$pdf->SetTitle('Education verification form');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
//echo PDF_FONT_MONOSPACED;die();
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Education verification form '.$oid, 'by '.$this->request->session()->read('Profile.username'), array(0,64,255), array(0,64,128));
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
$initials = W_ROOT;
$html = '<strong>Past education</strong><br />';
// Print text using writeHTMLCell()
//$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

if($education)
{
    foreach($education as $k=>$edu)
    {
        $pdf->writeHTMLCell(100, 8,'','', '<strong>Past Education '.($k+1).'</strong><hr/>',0, 1, 0, true, '', true);
$pdf->Cell(120, 5, 'School/College Name');
$pdf->TextField('college_school_name'.$k, 50, 5,array(),array('v'=>$edu->college_school_name, 'dv'=>$edu->college_school_name));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'Address');
$pdf->TextField('address'.$k, 50, 5,array(),array('v'=>$edu->address, 'dv'=>$edu->address));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'Supervisor\'s Name');
$pdf->TextField('supervisior_name'.$k, 50, 5,array(),array('v'=>$edu->supervisior_name, 'dv'=>$edu->supervisior_name));
$pdf->Ln(6);


$pdf->Cell(120, 5, 'Phone #');
$pdf->TextField('supervisior_phone'.$k, 50, 5,array(),array('v'=>$edu->supervisior_phone, 'dv'=>$edu->supervisior_phone));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'Supervisor\'s Email');
$pdf->TextField('supervisior_email'.$k, 50, 5,array(),array('v'=>$edu->supervisior_email, 'dv'=>$edu->supervisior_email));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'Secondary Email');
$pdf->TextField('supervisior_secondary_email'.$k, 50, 5,array(),array('v'=>$edu->supervisior_secondary_email, 'dv'=>$edu->supervisior_secondary_email));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'Education Start Date');
$pdf->TextField('education_start_date'.$k, 50, 5,array(),array('v'=>$edu->education_start_date, 'dv'=>$edu->education_start_date));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'Education End Date');
$pdf->TextField('education_end_date'.$k, 50, 5,array(),array('v'=>$edu->education_end_date, 'dv'=>$edu->education_end_date));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'Claims with this Tutor');
$pdf->TextField('claim_tutor'.$k, 50, 5,array(),array('v'=>$edu->claim_tutor, 'dv'=>$edu->claim_tutor));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'Date Claims Occured');
$pdf->TextField('date_claims_occur'.$k, 50, 5,array(),array('v'=>$edu->date_claims_occur, 'dv'=>$edu->date_claims_occur));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'Education history confirmed by (Verifier Use Only)');
$pdf->TextField('education_history_confirmed_by'.$k, 50, 5,array(),array('v'=>$edu->education_history_confirmed_by, 'dv'=>$edu->education_history_confirmed_by));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'Highest grade completed');
$pdf->TextField('highest_grade_completed'.$k, 50, 5,array(),array('v'=>$edu->highest_grade_completed, 'dv'=>$edu->highest_grade_completed));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'Last School attended');
$pdf->TextField('last_school_attended'.$k, 50, 5,array(),array('v'=>$edu->last_school_attended, 'dv'=>$edu->last_school_attended));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'College');
$pdf->TextField('college'.$k, 50, 5,array(),array('v'=>$edu->college, 'dv'=>$edu->college));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'High School');
$pdf->TextField('high_school'.$k, 50, 5,array(),array('v'=>$edu->high_school, 'dv'=>$edu->high_school));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'Did the employee have any safety or performance issues?');
$pdf->TextField('performance_issue'.$k, 50, 5,array(),array('v'=>$edu->performance_issue, 'dv'=>$edu->performance_issue));
$pdf->Ln(6);

$pdf->Cell(120, 5, 'Date');
$pdf->TextField('date_time'.$k, 50, 5,array(),array('v'=>$edu->date_time, 'dv'=>$edu->date_time));
$pdf->Ln(6);
}
}
if(count($att)>0){
$attach = "<br/><br/><strong></strong>
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
                    'csv',);
                    $vid_ext = array('mp4');
                    foreach($att as $a)
                    {
                        $ext_arr = explode('.', $a->attachment);
                                            $ext = end($ext_arr);
                                            $ext = strtolower($ext);
                                            if (!in_array($ext, $doc_ext) && !in_array($ext, $vid_ext) && file_exists(APP."../webroot/attachments/".$a->attachment)) {
                    
                        $attach = $attach."<p><img src=\"".$initials.$this->request->webroot."attachments/".$a->attachment."\" /><br /></p>";
                        }
                        
                    }
                    
                }
$attach = $attach."<p><img src=\"".$initials.$this->request->webroot."img/copy.jpg\" /><br /></p>";                
$pdf->writeHTMLCell(0, 0, '', '', $attach, 0, 1, 0, true, '', true);
}

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
ob_end_clean();
$pdf->Output('Education_Form.pdf', 'F',$oid);

//============================================================+
// END OF FILE
//============================================================+
