<strong>Past education</strong><br />
<table>
<?php
if($education)
{
    foreach($education as $edu)
    {
    ?>
    <tr><td><strong>School/College Name</strong> : <?php echo $edu->college_school_name;?></td>
    <td><strong>Address</strong> : <?php echo $edu->address;?></td></tr>
    <tr><td><strong>Supervisor's Name</strong> : <?php echo $edu->supervisior_name;?></td>
    <td><strong>Phone #</strong> : <?php echo $edu->supervisior_phone;?></td></tr>
    <tr><td><strong>Supervisor's Email</strong> : <?php echo $edu->supervisior_email;?></td>
    <td><strong>Secondary Email</strong> : <?php echo $edu->supervisior_secondary_email;?></td></tr>
    <tr><td><strong>Education Start Date</strong> : <?php echo $edu->education_start_date;?></td>
    <td><strong>Education End Date</strong> : <?php echo $edu->education_end_date;?></td></tr>
    <tr><td><strong>Claims with this Tutor</strong> : <?php echo $edu->claim_tutor;?></td>
    <td><strong>Date Claims Occured</strong> : <?php echo $edu->date_claims_occur;?></td></tr>
    <tr><td colspan="2"><strong>Education history confirmed by (Verifier Use Only)</strong> : <?php echo $edu->education_history_confirmed_by;?></td></tr>
    <tr><td><strong>Highest grade completed</strong> : <?php echo $edu->highest_grade_completed;?></td>
    <td><strong>Last School attended</strong> : <?php echo $edu->last_school_attended;?></td></tr>
    <tr><td><strong>College</strong> : <?php echo $edu->college;?></td>
    <td><strong>High School</strong> : <?php echo $edu->high_school;?></td></tr>
    <td><strong>Did the employee have any safety or performance issues?</strong> : <?php echo $edu->performance_issue;?></td></tr>
    <tr><td><strong>Signature</strong> : <?php echo $edu->signature;?></td>
    <td><strong>Date</strong> : <?php echo $edu->date_time;?></td></tr>
    <?php
    }
}
?>
    
</table>
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