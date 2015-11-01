<?php
$debug=$this->request->session()->read('debug');
if($debug) {
    echo "<span style ='color:red;'>subpages/import.php #INC???</span><P>";
}
?>

<form action="<?php echo $this->request->webroot."profiles/csv";?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  Choose your <FONT COLOR="RED">CSV</FONT> file: <P>
  <input name="csv" type="file" id="csv" REQUIRED/><P>
  <input type="submit" name="Submit" value="Submit" class="btn btn-success"/>
</form> 