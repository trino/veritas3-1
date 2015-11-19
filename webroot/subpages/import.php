<?php
    $debug=$this->request->session()->read('debug');
    if($debug) {
        echo "<span style ='color:red;'>subpages/import.php #INC???</span><P>";
    }

/*
    $pro = (['profile_type' =>  $data[0],
        'driver'            =>  $data[1],
        'username'          =>  $data[2],
        'title'             =>  $data[3] . ".",
        'fname'             =>  $data[4],
        'mname'             =>  $data[5],
        'lname'             =>  $data[6],
        'phone'             =>  $data[7],
        'gender'            =>  $data[8],
        'placeofbirth'      =>  $data[9],
        'dob'               =>  date('Y-m-d',strtotime($data[10])),
        'street'            =>  $data[11],
        'city'              =>  $data[12],
        'province'          =>  strtoupper($data[13]),
        'postal'            =>  $data[14],
        'country'           =>  "Canada",
        'driver_license_no' =>  $data[16],
        'driver_province'   =>  $data[17],
        'expiry_date'       =>  date("Y-m-d",strtotime($data[18])),
        'email'             =>  $data[19],
        'hired_date'       =>  date("Y-m-d",strtotime($data[21]))]
    );

    $Fields = array ("fname" => "forms_firstname", "email" => "forms_email", "lname" => "forms_lastname", "profile_type" => "profiles_profiletype", "gender" => "forms_gender",  "driver_province" => "forms_provinceissued", "title" => "forms_title", "placeofbirth" => "forms_placeofbirth", "sin" => "forms_sin", "phone" => "forms_phone", "street" => "forms_address", "city" => "forms_city", "province" => "forms_provincestate", "postal" => "forms_postalcode", "country" => "forms_country", "dob" => "forms_dateofbirth", "driver_license_no" => "forms_driverslicense", "expiry_date" => "forms_expirydate");
*/

function makesimpleselect($Manager, $Table, $Field){
    echo '<SELECT>';
    $ProfileTypes = $Manager->enum_table($Table);
    foreach($ProfileTypes as $ProfileType){
        echo '<OPTION>' . $ProfileType->id . ' = ' . $ProfileType->$Field . '</OPTION>';
    }
    echo '</SELECT>';
}
?>
<form action="<?php echo $this->request->webroot."profiles/csv";?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  Choose your <FONT COLOR="RED">CSV</FONT> file: <P>
  <input name="csv" type="file" id="csv" REQUIRED/><P>
  <input type="submit" name="Submit" value="Submit" class="btn btn-success"/>
</form>
<TABLE CLASS="table table-condensed  table-striped table-bordered table-hover dataTable">
    <THEAD>
        <TH>#</TH>
        <TH>Name</TH>
        <TH>Type</TH>
        <TH>Notes</TH>
    </THEAD>
    <TBODY>
        <TR>
            <TD>1</TD>
            <TD>Profile Type<SPAN CLASS="required"></SPAN></TD>
            <TD>Number</TD>
            <TD><?php makesimpleselect($Manager, "profile_types", "title"); ?></TD>
        </TR>
        <TR>
            <TD>2</TD>
            <TD>Driver</TD>
            <TD>Number</TD>
            <TD>I don't know what this is for</TD>
        </TR>
        <TR>
            <TD>3</TD>
            <TD>Username</TD>
            <TD>Text</TD>
            <TD>Must be unique</TD>
        </TR>
        <TR>
            <TD>4</TD>
            <TD>Title<SPAN CLASS="required"></SPAN></TD>
            <TD>Option</TD>
            <TD>Must be either: "Mr", "Ms" or "Mrs"</TD>
        </TR>
        <TR>
            <TD>5</TD>
            <TD>First Name<SPAN CLASS="required"></SPAN></TD>
            <TD>Text</TD>
            <TD></TD>
        </TR>
        <TR>
            <TD>6</TD>
            <TD>Middle Name</TD>
            <TD>Text</TD>
            <TD></TD>
        </TR>
        <TR>
            <TD>7</TD>
            <TD>Last Name<SPAN CLASS="required"></SPAN></TD>
            <TD>Text</TD>
            <TD></TD>
        </TR>
        <TR>
            <TD>8</TD>
            <TD>Phone Number<SPAN CLASS="required"></SPAN></TD>
            <TD>Text</TD>
            <TD></TD>
        </TR>
        <TR>
            <TD>9</TD>
            <TD>Gender<SPAN CLASS="required"></SPAN></TD>
            <TD>Option</TD>
            <TD>Must be either: "Male" or "Female"</TD>
        </TR>
        <TR>
            <TD>10</TD>
            <TD>Place of Birth<SPAN CLASS="required"></SPAN></TD>
            <TD>Text</TD>
            <TD></TD>
        </TR>
        <TR>
            <TD>11</TD>
            <TD>Date of Birth<SPAN CLASS="required"></SPAN></TD>
            <TD>Date</TD>
            <TD>YYYY-MM-DD</TD>
        </TR>
        <TR>
            <TD>12</TD>
            <TD>Street<SPAN CLASS="required"></SPAN></TD>
            <TD>Text</TD>
            <TD></TD>
        </TR>
        <TR>
            <TD>13</TD>
            <TD>City<SPAN CLASS="required"></SPAN></TD>
            <TD>Text</TD>
            <TD></TD>
        </TR>
        <TR>
            <TD>14</TD>
            <TD>Province<SPAN CLASS="required"></SPAN></TD>
            <TD>Option</TD>
            <TD><?php provinces("","","", "Acronyms"); ?></TD>
        </TR>
        <TR>
            <TD>15</TD>
            <TD>Postal Code<SPAN CLASS="required"></SPAN></TD>
            <TD>Text</TD>
            <TD>LETTER Number LETTER Space Number LETTER Number</TD>
        </TR>
        <TR>
            <TD>16</TD>
            <TD>Country<SPAN CLASS="required"></SPAN></TD>
            <TD>Ignored</TD>
            <TD>Will always be Canada</TD>
        </TR>
        <TR>
            <TD>17</TD>
            <TD>Driver's license number<SPAN CLASS="required"></SPAN></TD>
            <TD>Text</TD>
            <TD></TD>
        </TR>
        <TR>
            <TD>18</TD>
            <TD>Province driver's license was issued<SPAN CLASS="required"></SPAN></TD>
            <TD>Option</TD>
            <TD><?php provinces("","","", "Acronyms"); ?></TD>
        </TR>
        <TR>
            <TD>19</TD>
            <TD>Driver's license Expiry Date<SPAN CLASS="required"></SPAN></TD>
            <TD>Date</TD>
            <TD>YYYY-MM-DD</TD>
        </TR>
        <TR>
            <TD>20</TD>
            <TD>Email Address<SPAN CLASS="required"></SPAN></TD>
            <TD>Text</TD>
            <TD>Must be unique</TD>
        </TR>
        <TR>
            <TD>21</TD>
            <TD>Client ID</TD>
            <TD>Number</TD>
            <TD><?php makesimpleselect($Manager, "clients", "company_name"); ?></TD></TD>
        </TR>
        <TR>
            <TD>22</TD>
            <TD>Hired Date</TD>
            <TD>Date</TD>
            <TD>YYYY-MM-DD</TD>
        </TR>
        <TR>
            <TD>23</TD>
            <TD>Social Insurance Number<SPAN CLASS="required"></SPAN></TD>
            <TD>Text</TD>
            <TD>###-###-###</TD>
        </TR>
    </TBODY>
    <TFOOT>
        <TR>
            <TD COLSPAN="4" ALIGN="CENTER">This is the order in which the data must be on each line</TD>
        </TR>
        <TR>
            <TD COLSPAN="4" ALIGN="CENTER"><SPAN CLASS="required"></SPAN> denotes a field required to place an order</TD>
        </TR>
    </TFOOT>
</TABLE>
sin