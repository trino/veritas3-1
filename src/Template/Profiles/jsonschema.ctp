<?php
    $Action="";
    if(isset($_GET["action"])){$Action = $_GET["action"];}
    if(isset($_POST["action"])){$Action = $_POST["action"];}
    if(!isset($HTML)){$HTML="";}
    function makeaction($Action, $Title, $Name, $Data, $PrimaryKey, $ValueKey){
        echo '<FORM method="get"><INPUT TYPE="hidden" name="action" value="' . $Action . '">' . $Title . ": ";
        echo '<SELECT Name="' . $Name . '" ID="' . $Name . '">';
        foreach($Data as $DataPoint){
            echo '<OPTION VALUE="' . $DataPoint->$PrimaryKey . '">(' . $DataPoint->$PrimaryKey . ") " . $DataPoint->$ValueKey . '</OPTION>';
        }
        echo '</SELECT> <INPUT TYPE="submit"></FORM><P></P>';
    }
    function printoption($Name, $selected, $Value = ""){
        if (is_array($Name)){
            foreach($Name as $Key => $Value){
                printoption($Value, $selected, $Key);
            }
        } else {
            echo '<OPTION';
            if ($Value) {echo ' VALUE="' . $Value . '"';}
            if ($Name == $selected || $Value == $selected) {echo " SELECTED";}
            echo '>' . $Name . "</OPTION>";
        }
    }

    makeaction("order_to_json", "Convert order to JSON", "OrderID", $Manager->enum_orders(), "id", "title");
    makeaction("profile_to_json", "Convert profile to JSON", "ProfileID", $Manager->enum_profiles(), "id", "username");
?>


<FORM method="POST">
    Action:
    <SELECT NAME="action">
        <OPTION>Show JSON</OPTION>
        <OPTION value="json_to_html">Show JSON HTML</OPTION>
        <?php printoption(array("json_to_profile" => "JSON to Profile", "json_to_order" => "JSON to Order", "order_to_html" => "Order to HTML", "validate_all" => "Validate ALL"), $Action); ?>
    </SELECT>
    <INPUT TYPE="submit">
    <?php
        if(!$HTML){ echo '<TEXTAREA style="width: 100%; height: 500px;" name="JSON" id="JSON">';}
        if(isset($JSON)){echo '' . $JSON . '';}
        if(!$HTML){ echo '</TEXTAREA>';}
    ?>
</FORM>






