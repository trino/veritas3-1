<?php
    if ($_SERVER['SERVER_NAME'] == 'localhost') { echo "<span style ='color:red;'>profiles/products.ctp #INC not assigned</span>";}
    $language = $this->request->session()->read('Profile.language');
?>
<style>
    th.rotate {
        height: 140px;
        white-space: nowrap;
    }
    th.rotate > div {
        transform:
        translate(20px, -5px)
        rotate(315deg);
        width: 30px;
    }
    th.rotate > div > span {
        border-bottom: 1px solid #ccc;
        padding: 5px 10px;
    }

    .toast{
        background-color: white;
    }
</style>
<script>
    var Language = '<?= $language ?>';
    var OldIndex =-1;
    function enableproduct(Index){
        var element = document.getElementById("chk" + Index);
        $.ajax({
            url: "<?php echo $this->request->webroot;?>profiles/products",
            type: "post",
            dataType: "HTML",
            data: "Type=enabledocument&DocID=" + Index + "&Value=" + element.checked,
            success: function (msg) {
                if(element.checked){ word="enabled"; } else { word = "disabled";}
                msg = "<FONT COLOR=BLACK>You have " + word + " '" + selectedname() + "'</FONT>";
                Toast(msg, true);
            }
        })
    }

    function addproduct(){
        var Number =  $('#newnum').val();
        var Name =  $('#newname').val();
        var NameFrench =  $('#newnameFrench').val();
        if(isNaN(Number)) {
            Toast("'" + Number + "' is not a number", true);
        } else if (Name.length ==0 || NameFrench.length ==0) {
            Toast("No name or french name were specified", true);
        } else {
            $.ajax({
                url: "<?php echo $this->request->webroot;?>profiles/products",
                type: "post",
                dataType: "HTML",
                data: "Type=createdocument&DocID=" + Number + "&Name=" + Name + "&NameFrench=" + NameFrench,
                success: function (msg) {
                    Toast(msg, true);
                    if(msg.indexOf("green") >-1){
                        switch (Language){
                            case "French":
                                Name = NameFrench;
                                break;
                        }
                        $('#myTable > tbody:last').append('<TR ID="PTR' + Number + '" onclick="selectproduct(' + Number + ');"><TD><INPUT TYPE="RADIO" ID="rad' + Number + '">' + Number + '</LABEL></TD><TD><DIV ID="pn' + Number + '">' + Name + '</div></TD><TD><INPUT TYPE="checkbox" ID="chk' + Number + '" ONCLICK="enableproduct(' + Number  + ');"></TD></TR>');

                        $('#newnum').val("");
                        $('#newname').val("");
                        $('#newnameFrench').val("");
                    }
                }
            })
        }
    }

    function setprov(DocID, Province){
        element = document.getElementById(DocID + "." + Province);
        $.ajax({
            url: "<?php echo $this->request->webroot;?>profiles/products",
            type: "post",
            dataType: "HTML",
            data: "Type=selectdocument&DocID=" + DocID + "&Province=" + Province + "&Product=" + OldIndex + "&Value=" + element.checked,
            success: function (msg) {
                if(element.checked){ word="enabled"; } else { word = "disabled";}
                if(Province == "ALL") {Province = "all provinces"; }
                msg = "You have " + word + " '" + documentname(DocID) + "' in " + Province + " for '" + selectedname() + "'";
                Toast(msg, true);
            }
        })
    }

    function Toast(Text, FadeOut){
        $('.toast').stop();
        if (FadeOut) {$('.toast').fadeIn(1);}
        $('.toast').html(Text);
        $('.toast').show();
        if (FadeOut) {$('.toast').fadeOut(5000);}
    }

    function clearproduct(Index){
        $.ajax({
            url: "<?php echo $this->request->webroot;?>profiles/products",
            type: "post",
            dataType: "HTML",
            data: "Type=cleardocument&DocID=" + OldIndex + "&Language=" + Language,
            success: function (msg) {
                $('.tablespot').html(msg);
                Toast("<FONT COLOR='red'>'" + selectedname() + "' was cleared</FONT>", true);
            }
        })
    }

    function selectproduct(Index){
        if (Index ==OldIndex) {return;}
        if(OldIndex>-1){$("#rad" + OldIndex).prop("checked", false);}
        $("#rad" + Index).prop("checked", true);
        OldIndex=Index;
        Toast("<FONT COLOR='BLACK'>You have selected '" + selectedname() + "'</FONT>", true);
        $('.tablespot').html('<DIV ALIGN="CENTER"><IMG SRC="<?= $this->request->webroot;?>webroot/assets/global/img/loading-spinner-blue.gif"><BR>Loading...</DIV>');
        $('.actions').show();
        $.ajax({
            url: "<?= $this->request->webroot;?>profiles/products",
            type: "post",
            dataType: "HTML",
            data: "Type=selectproduct&DocID=" + Index + "&Language=" + Language,
            success: function (msg) {
                $('.tablespot').html(msg);
            }
        })
    }

    function selectedname(){
        return $('#pn' + OldIndex).text() ;
    }
    function documentname(DocID){
        return $('#dn' + DocID).text() ;
    }

    function deleteproduct(){
        if (confirm("Are you sure you want to delete '" + selectedname() + "'?")){
            $.ajax({
                url: "<?php echo $this->request->webroot;?>profiles/products",
                type: "post",
                dataType: "HTML",
                data: "Type=deletedocument&DocID=" + OldIndex,
                success: function (msg) {
                    Toast("'" + selectedname() + "' was deleted", true);
                    document.getElementById("PTR" + OldIndex).remove();
                    OldIndex=-1;
                    OldRow=-1;
                    $('.actions').hide();
                    $('.tablespot').html("");
                }
            })
        }
    }
    function editproduct(){
        var person = prompt("Please enter a new " + Language + " name for: '" + selectedname() + "'", selectedname());
        if(person !== null && person.length>0) {
            $.ajax({
                url: "<?php echo $this->request->webroot;?>profiles/products",
                type: "post",
                dataType: "HTML",
                data: "Type=rename&DocID=" + OldIndex + "&newname=" + person + "&Language=" + Language,
                success: function (msg) {
                    Toast("'" + selectedname() + "' was renamed to '" + person + "'", true);
                    $('#pn' + OldIndex).text(person);
                }
            })
        }
    }

    function simulateClick(name) {
        //if (skip) { skip=false; return; }
        var evt = document.createEvent("MouseEvents");
        evt.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        var cb = document.getElementById(name);
        var canceled = !cb.dispatchEvent(evt);
    }
</script>

<div class="portlet box green-haze">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-briefcase"></i>Packages
        </div>
    </div>
    <div class="portlet-body">

<TABLE WIDTH="100%" HEIGHT="100%"><TR><TD COLSPAN="2"><TABLE WIDTH="100%" HEIGHT="100%"><TR><TD><FONT COLOR="white">[</FONT></TD><TD width="99%"><div class="toast" style="color: rgb(255,0,0);"></div></TD></TR></TABLE>
</TD></TR>
<TR><TD WIDTH="25%" VALIGN="TOP">
<?php
$provincelist = array("AB" => "Alberta", "BC" => "British Columbia", "MB" => "Manitoba", "NB" => "New Brunswick", "NL" => "Newfoundland and Labrador", "NT" => "Northwest Territories", "NS" => "Nova Scotia", "NU" => "Nunavut", "ON" => "Ontario", "PE" => "Prince Edward Island", "QC" => "Quebec", "SK" => "Saskatchewan", "YT" => "Yukon Territories");

function getDefault($Default, $Value){
    if($Value){return $Value;}
    return "[" . $Default . "]";
}

echo "<table class='table table-condensed  table-striped table-bordered table-hover dataTable no-footer'  ID='myTable'>";
echo "<THEAD><TH>ID</TH><TH>Package</TH><TH TITLE='Enabled'>EN</TH></THEAD><TBODY>";

$fieldname = "title";
if(($language!="English")){$fieldname.=$language;}
foreach($products as $product){
    $checked="";
    if ($product->enable) { $checked= " checked";}
    echo '<TR ID="PTR' . $product->number . '" onclick="selectproduct(' . $product->number . ');"><TD><INPUT TYPE="RADIO" ID="rad' . $product->number . '">' . $product->number . '</LABEL></TD><TD><DIV ID="pn' . $product->number . '">' . getDefault($product->title, $product->$fieldname) . '</div></TD><TD><INPUT TYPE="checkbox" ID="chk' . $product->number . '" ONCLICK="enableproduct(' . $product->number  . ');"' . $checked . '></TD></TR>';
}
?></TBODY><TFOOT>
    <TR><TH COLSPAN="3">Actions:</TH></TR><TR class="actions" style="display: none;"><TD COLSPAN="3">
                    <a class="btn btn-xs btn-primary" id="delete" onclick="editproduct();">Rename</a>
                    <a class="btn btn-xs btn-primary" id="delete" onclick="clearproduct();">Clear</a>
                    <a class="btn btn-xs btn-danger" id="delete" onclick="deleteproduct();">Delete</a>
                </TD></TR>
    <TR><TH COLSPAN="3">Add Package:</TH></TR>
        <TR><TD>
                <input type="text" id="newnum" style="width: 50px" placeholder="ID#"><BR>
                <a class="btn btn-xs btn-primary" id="add" onclick="addproduct();" style="float: right; width: 50px; margin-top: 3px;">Add</a>
            </TD>
            <TD COLSPAN="2">
                <input type="text" id="newname" style="width: 100%" placeholder="English">
                <input type="text" id="newnameFrench" style="width: 100%" placeholder="French">
                </TD>
        </TR>
</table>
            </TD>
            <TD valign="top"><DIV CLASS="tablespot">
                <H2>Instructions:</H2>
                    Select a package on the left, then choose which documents are displayed based on the province the driver's license was issued in.<BR>
                    By enabling "ALL" for a province, the form will be shown for all provinces<BR>
                    Instead of enabling all documents one at a time, you can enable the "All Documents" row<BR>
                    Any text inside these [brackets] is missing a translation for <?= $language ?>
            </DIV></TD>
        </TR></TFOOT></TABLE>
    </div>
</div>