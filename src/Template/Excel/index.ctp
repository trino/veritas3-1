<?php
    $Inline= false;//$Manager->ScriptName() == "Veritas 3-0";
    $GLOBALS["inline"] = $Inline;
    if($Inline) {
        //won't work in intact
        //https://vitalets.github.io/x-editable/docs.html
        ?>
            <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
            <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
            <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
            <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.4.6/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
            <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.4.6/bootstrap-editable/js/bootstrap-editable.min.js"></script>
            <script>
                $(document).ready(function () {
                    $.fn.editable.defaults.mode = 'popup';
                    $.fn.editable.defaults.success = function (response, newValue) {
                        reload('');
                    };

                    $('.editable').editable();
                });
            </script>
        <?php
    }
    /*
        To do list:
        - fix references
        - clipboard actions (paste, insert)
        - export/import
    */

    $Controller = strtolower($this->request->params['controller']);
    $GLOBALS["Controller"] = $Controller;
    if(isset($_GET["embedded"])){$EmbeddedMode=true;$HTMLMode=true;}
    if(!isset($Table)){$Table="";}
    if(isset($EmbeddedMode)){
        if(!isset($_GET["table"])){
            $_GET["table"] = $Table;
        }
        if(!$Table){$Table = $_GET["table"];}
        $HTMLMode=true;
        if($Table) {printtable($this, $Manager, $Table, "", false, false, true, true, false, false, false, true);}
    } else {
        $EmbeddedMode=false;
    }

    function getID($ID){
        return substr($ID, 5, strpos($ID, "]") - 5);
    }

    function splitreference($Manager, $Table, $Reference, $PrimaryKey="", $Columns="", $IncludeDollarSigns = false, $Join = true, $Me = ""){
        $Data = explode(":", $Reference);
        $Data2 = array();
        foreach($Data as $Value){
            if(strtoupper($Value)=="ME"){$Value = $Me;}
            $Column = strtoupper($Manager->validate_data($Value, "alphabetic"));
            if($IncludeDollarSigns && $Column && strpos($Value, "$" . $Column) !== false){ $Column = "$" . $Column;}
            $Row = $Manager->validate_data($Value, "number");
            if($IncludeDollarSigns && $Row && strpos($Value, "$" . $Row) !== false){ $Row = "$" . $Row;}
            if(!$Column || !$Row){
                if ($Column) {
                    if(!$PrimaryKey){$PrimaryKey = $Manager->get_primary_key($Table);}
                    $Count = $Manager->get_last_entry($Table, $PrimaryKey);
                    $Data2[] = array($Column, 1);
                    $Data2[] = array($Column, $Count);
                } else if($Row) {
                    if(!$Columns) {$Columns = $Manager->getColumnNames($Table);}
                    $ColumnLetter = toaletter(count($Columns)-1);
                    $Data2[] = array("A", $Row);
                    if(count($Columns)) {
                        $Data2[] = array($ColumnLetter, $Row);
                    }
                }
            } else {
                $Data2[] = array($Column, $Row);
            }
        }
        if($Join){
            foreach($Data2 as $Key => $Value){
                $Data2[$Key] = implode("", $Value);
            }
            if(count($Data2)==1){return $Data2[0];}
            $Data2 = $Data2[0] . ":" . $Data2[count($Data2)-1];//only needs first and last
            //$Data2 = implode(":", $Data2);
        }
        return $Data2;
    }

    function getreferences($Manager, $Table, $Reference, $Me ="", $Letters = "", $PrimaryKey = "", $FilterBrackets=true, $RefsOnly = false, $ToString = false, $Blank=false){
        $Table = gettablereference($Reference,$Table);
        if(!$PrimaryKey){$PrimaryKey = $Manager->get_primary_key($Table);}
        $Columns = $Manager->getColumnNames($Table, "", false);
        if(!$Letters){
            $Letters = get_column_letters($PrimaryKey, $Columns);
        }
        $Reference = splitreference($Manager, $Table, $Reference, $PrimaryKey, $Columns, false, true, $Me);
        if(!ismultireference($Manager, $Reference)){
            if ($RefsOnly){return array($Reference);}
            $Data = getreference($Manager, $Table, $Reference, $Letters, $PrimaryKey, $FilterBrackets, false, true, $Blank);
            if($ToString){return $Data;}
            return array($Data);
        }

        $Reference1=ismultireference($Manager, $Reference,1, $Me);
        $Reference2=ismultireference($Manager, $Reference,2, $Me);

        $Column1 = strtoupper($Manager->validate_data($Reference1, "alphabetic"));
        $Row1 = $Manager->validate_data($Reference1, "number");
        $Column2 = strtoupper($Manager->validate_data($Reference2, "alphabetic"));
        $Row2 = $Manager->validate_data($Reference2, "number");
        $Column1 = letterToIndex($Column1);
        $Column2 = letterToIndex($Column2);
        if ($Column1 > $Column2){
            $Values = $Column1;
            $Column1 = $Column2;
            $Column2 = $Values;
        }
        if($Row1 > $Row2){
            $Values = $Row1;
            $Row1 = $Row2;
            $Row2 = $Values;
        }

        $Values = array();
        for($RowID = $Row1; $RowID <= $Row2; $RowID++) {
            for ($ColumnID = $Column1; $ColumnID <= $Column2; $ColumnID++) {
                $Column = getcolumnindex($Letters, $ColumnID, false);
                $Reference = $Column . $RowID;
                if ($Reference != $Me) {
                    if ($RefsOnly) {
                        $Values[] = $Reference;
                    } else {
                        $Data = getreference($Manager, $Table, $Reference, $Letters, $PrimaryKey, $FilterBrackets, false, true, $Blank);
                        if($Data){$Values[$Reference] = $Data;}
                    }
                }
            }
        }
        if($ToString){return json_encode($Values);}
        return $Values;
    }

    function sum($Data = false){
        $Total = 0;
        if(is_array($Data)) {
            foreach ($Data as $Value) {
                $Total += $Value;
            }
        }
        return $Total;
    }
    function average($Data = false){
        if(is_array($Data)) {
            return sum($Data) / count($Data);
        }
        return 0;
    }

    function toaletter($Index){
        $Index=$Index+1;
        $FirstLetter= floor ($Index/26);
        $SecondLetter = $Index % 26;
        return generateletters(array($FirstLetter,$SecondLetter), ord("A"));
    }
    function getcolumnindex($Letters, $Index, $RetName = false){
        //$Index=$Index+1;$FirstLetter= floor ($Index/26);$SecondLetter = $Index % 26;
        $Index = toaletter($Index);// generateletters(array($FirstLetter,$SecondLetter), ord("A"));
        if ($RetName && isset($Letters[$Index])){
            return $Letters[$Index];
        }
        return $Index;
    }

    function letterToIndex($Letter){
        $Number =0;
        $Int = 0;
        $Start = ord("A");
        for($Temp = strlen($Letter)-1; $Temp>=0; $Temp--){
            $Value = substr($Letter, $Temp, 1);
            $Value = ord($Value) - $Start ;
            if($Int){
                $Number += ($Value+1) * $Int;
                $Int=$Int*26;
            }else {
                $Number = $Value;
                $Int = 26;
            }
        }
        return $Number;
    }

    function gettablereference($Reference, $Table){
        $ExclamationMark = strpos($Reference, "!");
        if ($ExclamationMark) {return substr($Reference, 0, $ExclamationMark);}
        return $Table;
    }

    function getreference($Manager, $Table, $Reference, $Letters = "", $PrimaryKey = "", $FilterBrackets=true, $ReturnIfError=true, $Blank=false){
        $Reference = str_replace("$", "", $Reference);
        $ExclamationMark = strpos($Reference, "!");
        if ($ExclamationMark){//reference to another table
            $Table2 = substr($Reference, 0, $ExclamationMark);
            $Reference = substr($Reference, $ExclamationMark + 1, strlen($Reference) - $ExclamationMark - 1);
            if($Table2 != $Table) {
                $Table = $Table2;
                $Letters = "";
                $PrimaryKey = "";
            }
        }
        $Column = strtoupper($Manager->validate_data($Reference, "alphabetic"));
        $Row = $Manager->validate_data($Reference, "number");

        if(!$PrimaryKey){$PrimaryKey = $Manager->get_primary_key($Table);}
        if(!$Letters){
            $Columns = $Manager->getColumnNames($Table, "", false);
            $Letters = get_column_letters($PrimaryKey, $Columns);
        }

        if (!isset($Letters[$Column])){return '[ERROR: Column ' . $Column . ' not found in ' . $Table . ']';}
        $Column = $Letters[$Column];

        $Data = $Manager->get_entry($Table, $Row, $PrimaryKey);
        if(!$Data && $Blank){return $Blank;}
        if($Data){
            $Data = $Data->$Column;
            if($FilterBrackets){$Data = getTag($Data, false);}
            return $Data;
        }
        if($ReturnIfError) {return '[ERROR:' . $Table . "!" . $Reference . " not found]";}
    }

    function isareference($Manager, $Value, $AllowMissingType = false){
        if(strtoupper($Value) == "ME"){return true;}
        $Value=str_replace("$", "", $Value);
        $ExclamationMark = strpos($Value, "!");
        if ($ExclamationMark) {$Value = substr($Value, $ExclamationMark + 1, strlen($Value) - $ExclamationMark - 1);}
        $Column = strtoupper($Manager->validate_data($Value, "alphabetic"));
        $Row = $Manager->validate_data($Value, "number");
        if(strlen($Column) < 3) {
            if ($AllowMissingType && ($Column || $Row)){return true;}
            if ($Column && $Row) {return strpos($Value, $Row) > strpos($Value, $Column);}
        }
        return false;
    }


    function ismultireference($Manager, $Value, $Ret=0, $Me = ""){
        $SemiColon = strpos($Value, ":");
        $Reference1 = strtoupper(substr($Value, 0, $SemiColon));
        $Reference2 = strtoupper(substr($Value, $SemiColon + 1, strlen($Value) - $SemiColon - 1));
        if($Ret == 1 && $Reference1 == "ME"){return $Me;}
        if($Ret == 2 && $Reference2 == "ME"){return $Me;}
        if($Ret == 1){return $Reference1;}
        if($Ret == 2){return $Reference2;}
        return(isareference($Manager, $Reference1, true) && isareference($Manager, $Reference2, true));
        return false;
    }

    function commonjavascriptfunctions(){
        if (isset($GLOBALS["commonjavascriptfunctions"])){return false;}
        $GLOBALS["commonjavascriptfunctions"] = true;
        ?>
            function visible(ID, Status){
                var element = selectelement(ID);
                if(Status){
                    element.setAttribute("style", "");
                } else {
                    element.setAttribute("style", "display: none;");
                }
            }

            function deleteattribute(ID, Class){
                selectelement(ID).removeAttribute(Class);
            }

            function setvalue(ID, Value){
                selectelement(ID).value = Value;
            }

            function clearselect(ID){
                var element = selectelement(ID);
                var i;
                for(i=element.options.length-1;i>=0;i--) {
                element.remove(i);
                }
            }

            function setinnerhtml(ID, Value){
                var element = selectelement(ID);
                element.innerHTML = Value;
            }

        	function removearray(array, index){
                var newarray = new Array();
                for (var key in array) {
                    var value = array[key];
                    if (key != index){
                        newarray[key] = value;
                    }
                }
                return newarray;
            }

 	        function addoptions(ID, Values){
                 visible(ID, true);
                 for(i=0;i<Values.length;i++) {
                     addoption(ID, Values[i]);
                }
            }

            function addoption(ID, Value, Text){
                var element = selectelement(ID);
                var option = document.createElement("option");
                if(Text) {option.text = Text;} else {option.text = Value;}
                option.value = Value;
                element.add(option);
            }

            function selectlastitem(ID){
                var element = selectelement(ID);
                if(element.options.length >0){
                    element.selectedIndex = element.options.length-1;
                    return true;
                }
            }

            function selectitem(ID, value){
                var element = selectelement(ID);
                for(var i=0; i < element.options.length; i++){
                    if(element.options[i].value === value) {
                        element.selectedIndex = i;
                        break;
                    }
                }
            }

            var CurrentRow, CurrentCol, Mode;
            var opt = {
            autoOpen: false,
            modal: true,
                buttons: {
                    "Ok": function() {
                        var value = getinputvalue(Mode);
                        var element = selectelement(downID);
                        var msg = "";
                        if(element.hasAttribute("role")){
                            var Role = element.getAttribute("role");
                            var RET = validate_data(value, Role);
                            if(RET){
                                if (Role == "number"){
                                    if(element.hasAttribute("min")){
                                        if(value < element.getAttribute("min")){msg = value + " needs to be a minimum of " + element.getAttribute("min");}
                                    }
                                    if(element.hasAttribute("max")){
                                        if(value > element.getAttribute("max")){msg = value + " needs to be a maximum of " + element.getAttribute("max");}
                                    }
                                }
                            } else {
                                msg = value + " is not a valid " + Role;
                            }
                            if(msg){
                                alert(msg);
                                return false;
                            }
                        }
                        $(this).dialog("close");
                        element.setAttribute("value", value);
                        mychangeevent(downID, true);
                        save(false);
                    },
                    "Cancel": function() {
                        $(this).dialog("close");
                    }
                }
            };

            $(function() {
                $("#dialog-form").dialog(opt);
            });

            function focus(ID){
                selectelement(ID).focus();
            }
            function selectall(ID){
                focus(ID);
                selectelement(ID).select();
            }


            function showprompt(Title, Message){
                $("#dialog-form").dialog(opt);
                visible("prompt_select", false);
                visible("prompt_text", false);
                visible("dialog-form", true);
                deleteattribute("prompt_text", "role");
                setinnerhtml(".ui-dialog-title", Title);
                setinnerhtml("prompt_title", Message);
                clearselect("prompt_select");
                $("#dialog-form").dialog("open");
                setattribute("dialog-form", "style", "background-color: white;");
                //setstyle("ui-dialog", "background-color", "white");
            }

            function listprompt(Title, Choices){
                showprompt("Set Value", Title);
                Mode="prompt_select";
                addoptions("prompt_select", Choices);
            }

            function setstyle(ID, Style, Value){
                selectelement(ID).style[Style] = Value;
            }

            function selectelement(Element){
                var firstletter = Element.substring(0, 1);
                var Name = Element.substring(1, Element.length);
                switch(firstletter){
                    case ".":
                        return document.getElementsByClassName(Name)[0];
                        break;
                    default://ID
                        return document.getElementById(Element);
                }
            }

            function setattribute(ID, Class, Value){
                var element = selectelement(ID);
                element.setAttribute(Class, Value);
            }

            function textprompt(Title, Default){
                showprompt("Set Value", Title);
                Mode="prompt_text";
                setattribute(Mode, "type", "text");
                visible(Mode, true);
                setvalue(Mode, Default);
                selectall(Mode);
            }
            <?php
    }

    function editform($Manager, $Table, $Column, $ID=0){//doesn't support embedding yet...
        echo '<FORM METHOD="GET" ACTION="' . $Manager->webroot() . $GLOBALS["Controller"] . '">';
        echo '<INPUT TYPE="HIDDEN" NAME="action" VALUE="saveedit">';
        echo '<INPUT TYPE="HIDDEN" NAME="table" VALUE="' . $Table . '">';
        echo '<INPUT TYPE="HIDDEN" NAME="htmlmode" VALUE="html">';
        echo '<INPUT TYPE="HIDDEN" NAME="column" VALUE="' . $Column . '">';
        $ColumnNames = $Manager->getColumnNames($Table, "", false);
        if($ID){
            $PrimaryKey = $Manager->get_primary_key($Table);
            $Entry = $Manager->get_entry($Table, $ID, $PrimaryKey);
            $Value = $Entry->$Column;
            $Tag = getTag($Value, True);
            if (strpos($Tag, "[") === false){$Tag = "[" . $Tag . "]";}
            $Value = getTag($Value, False);
            echo '<INPUT TYPE="HIDDEN" NAME="id" VALUE="' . $ID . '">';
            echo '<P><LABEL>Value:</LABEL><INPUT TYPE="text" ID="text" NAME="text" VALUE="' . $Value . '" style="width: 100%;" placeholder="Text"></P>';
        } else {//is a column header
            $Tag = $ColumnNames[$Column]["comment"];
        }
        echo '<P><LABEL>Tags:</LABEL><INPUT TYPE="text" ID="tag" NAME="tag" VALUE="' . $Tag . '" readonly style="width: 100%;" placeholder="Tags"></P>';
        echo '<SCRIPT>';
        commonjavascriptfunctions();
        ?>
            var Values = new Array();
            var SelectedKey = "";
            function tagclick(){
                SelectedKey = getinputvalue("tags");
                SelectedValue = Values[SelectedKey];
                if (typeof(SelectedValue) == "undefined"){SelectedValue="";}
                clearselect("values");
                visible("values", false);
                visible("color", false);
                visible("textvalue", false);
                visible("removetag", true);
                switch(SelectedKey){
                    case "format":
                        addoptions("values", ["percent", "uppercase", "lowercase", "number", "currency"]);
                        break;
                    case "validate":
                        addoptions("values", ["number", "alphabetic", "alphanumeric", "number", "email", "postalzip", "zipcode", "postalcode", "phone", "sin"]);
                        break;
                    case "bgcolor": case "fontcolor":
                        visible("color", true);
                        setvalue("color", SelectedValue);
                        break;
                    case "align":
                        addoptions("values", ["left", "center", "right"]);
                        break;
                    case "fontsize":
                        addoptions("values", [1, 2, 3, 4, 5, 6, 7]);
                        break;
                    case "colspan":
                        addoptions("values", [2,3,4,5,6,7,8,9,10,"All"]);
                        break;
                    case "min": case "max":
                        visible("textvalue", true);
                        break;
                }
            }

            function valueclick(Input){
                var SelectedValue = getinputvalue(Input);
                Values[SelectedKey] = SelectedValue;
                generatevalues();
            }

            function removeatag(){
                var element = document.getElementById("tags").selectedIndex;
                removeoption("tags", -1);
                Values = removearray(Values,SelectedKey);
                generatevalues();

                visible("values", false);
                visible("removetag", false);
            }


            function generatevalues(){
                var tempstr = new Array();
                for (var key in Values) {
                    var value = Values[key];
                    if(key){
                        if(value){
                            tempstr.push(key + "=" + value);
                        } else {
                            tempstr.push(key);
                        }
                    }
                }
                tempstr = "[" + tempstr.join(",") + "]";
                setvalue("tag", tempstr);
            }

            function removeoption(ID, Index){
                var element = document.getElementById(ID);
                addoption("options", SelectedKey);
                if(Index==-1){Index = element.selectedIndex;}
                element.remove(Index);
                if (selectlastitem("tags")){
                    tagclick();
                }
            }

            function optionclick(){
                var element = document.getElementById("options");
                var Value = getinputvalue("options");
                Values[Value] = "";
                addoption("tags", Value);
                selectitem("tags", Value);
                tagclick();
                element.remove(element.selectedIndex);
                generatevalues();
            }


            function updatecolor(color){
                color = "#" + color;
                Values[SelectedKey] = color;
                generatevalues();
            }

        <?php
        if($Tag){
            $Tag = assocsplit(substr($Tag,1, strlen($Tag)-2), ",","=");
            foreach($Tag as $Key => $ValuePair){
                echo "\r\nValues['" . $Key . "'] = '" . $ValuePair . "';";
            }
        }
        echo '</SCRIPT>';

        echo '<P><LABEL>Used tags: </LABEL><BR><SELECT ID="tags" SIZE=10 onclick="tagclick();" style="width: 200px;">';
        if($Tag){
            foreach($Tag as $Key => $ValuePair){
                if($Key){
                    echo "<OPTION>" . $Key . "</OPTION>";
                }
            }
        }
        echo '</SELECT></P>';
        echo '<P><INPUT TYPE="button" value="Remove Tag" onclick="removeatag();" id="removetag" style="display: none"></P>';

        //input types
        echo '<P><script type="text/javascript" src="assets/global/plugins/jscolor/jscolor.js"></script><input class="color {onImmediateChange:' . "'updatecolor(this);'" . '}" ID="color" style="display: none"></P>';
        echo '<P><INPUT TYPE="text" ID="textvalue" style="display: none" onchange="valueclick(' . "'textvalue'" . ');"></P>';
        echo '<P><SELECT ID="values" size=10 style="display: none" onclick="valueclick(' . "'values'" . ');"></SELECT></P>';
        echo '<P><LABEL>Available tags: </LABEL><BR><SELECT ID="options" size=10 onclick="optionclick();" style="width: 200px;">';
        $options = array("bold", "italic", "underline", "format", "bgcolor", "align", "fontcolor", "fontsize", "readonly", "min", "max", "validate");
        if($ID){$options[] = "colspan";}
        foreach($options as $Key){
            if($Tag){$DoIt = !isset($Tag[$Key]);} else {$DoIt = true;}
            if($DoIt){echo '<OPTION>' . $Key . '</OPTION>';}
        }
        echo '</SELECT></P>';
        echo '<P><INPUT TYPE="SUBMIT" VALUE="Save"> <INPUT TYPE="button" VALUE="Cancel" onclick="window.history.back();"><FORM>';
    }

    if (isset($_GET["action"])){
        switch($_GET["action"]){
            case "clearcache":
                $Manager->clear_cache();
                break;
            case "edit":
                if (strpos($_GET["id"], "Data") !== false) {
                    $ID = explode("][", substr($_GET["id"], 5, strlen($_GET["id"]) - 6));//0=ID, 1=Column
                    editform($Manager, $_GET["table"], $ID[1], $ID[0]);
                } else {
                    editform($Manager, $_GET["table"], $_GET["id"]);
                }
                return;
                break;
            case "saveedit":
                if(isset($_GET["id"])){
                    $PrimaryKey = $Manager->get_primary_key($_GET["table"]);
                    $Value = $_GET["text"];
                    if(strlen($_GET["tag"])>2){$Value = $_GET["tag"] . $Value;}
                    $Manager->edit_database($_GET["table"], $PrimaryKey, $_GET["id"], array($_GET["column"] => $Value));
                } else {
                    $Manager->change_column_comment($_GET["table"], $_GET["column"], $_GET["tag"]);
                }
                break;
            case "truncate":
                $Manager->truncate_table($_GET["table"]);
                break;
            case "inlinesave":
                //$Manager->debugprint( print_r($_POST,true)  . "\r\n" . print_r($_GET,true));
                quickedit($Manager, $_GET["table"], $_POST["pk"], $_POST["name"], $_POST["value"]);
                die();
                break;
        }
    }

    function quickedit($Manager, $Table, $PrimaryKeyValue, $Column, $Value){
        $PrimaryKey = $Manager->get_primary_key($Table);
        $Data = $Manager->get_entry($Table, $PrimaryKeyValue, $PrimaryKey);
        if($Data) {
            $Tag = getTag($Data->$Column, true);
            $Manager->edit_database($Table, $PrimaryKey, $PrimaryKeyValue, array($Column => $Tag . $Value));
        }
    }

    if (isset($_POST["action"])){
        if(isset($_POST["id"])){$ID = getID($_POST["id"]);}
        switch($_POST["action"]){
            case "delete":
                if(!$_POST["key"]){ $_POST["key"] = $Manager->get_primary_key($_POST["table"]);}
                fixreferences($Manager, $_POST["table"], true, "A", 1, 0, -1);
                $Manager->delete_all($_POST["table"], array($_POST["key"] => $ID));
                echo "Deleted from " . $_POST["table"] . " where " . $_POST["key"] . " = " . $ID;
                break;
            case "save":
                if (isset($_POST["Data"])) {
                    if(!$_POST["key"]){ $_POST["key"] = $Manager->get_primary_key($_POST["table"]);}
                    foreach ($_POST["Data"] as $Key => $Data) {
                        if($Key == "new"){
                            $ID = $Manager->edit_database($_POST["table"], $_POST["key"], false, $Data);
                            //echo 'Added: ' . $_POST["key"] . " = " . $ID[$_POST["key"]];
                        } else {
                            if($_POST["htmlmode"]){
                                $Entry = $Manager->get_entry($_POST["table"], $Key, $_POST["key"]);
                                if($Entry){
                                    foreach($Data as $DataKey => $DataValue){
                                        $Text = "[" . getTag($Entry->$DataKey, true) . "]";
                                        if (strlen($Text)>2){
                                            $Data[$DataKey] = $Text . $DataValue;
                                        }
                                    }
                                }
                            }
                            $Manager->update_database($_POST["table"], $_POST["key"], $Key, $Data);
                        }
                    }
                    echo "All changed data was saved";
                } else {
                    echo "No data was saved";
                }
                break;
            case "deletetable":
                echo "I'm not deleting " . $_POST["table"] . "...";
                break;
            case "newtable":
                if($Manager->new_table($_POST["table"])){
                    echo "Table made: " . $_POST["table"];
                } else {
                    echo "Unable to create table";
                }
                break;
            case "newcolumn":
                if($_POST["type"] == "DECIMAL" && $_POST["length"] == 0){$_POST["length"] = "10,10";}
                if($_POST["type"] == "VARCHAR" && $_POST["length"] == 0){$_POST["type"] = "TEXT";}
                $Query = $Manager->create_column($_POST["table"], $_POST["name"], $_POST["type"],  $_POST["length"], "", false, false, "", "",  $_POST["position"]);
                if ($_POST["position"]== "FIRST"){
                    fixreferences($Manager, $_POST["table"], true, "A", 1, 1);
                } elseif($_POST["position"]) {
                    fixreferences($Manager, $_POST["table"],  false, $_POST["position"], 1, 1);
                }
                echo $_POST["name"] . " created in " . $_POST["table"] . ' (' . $Query . ")";
                break;
            case "deletecolumn":
                fixreferences($_POST["table"],  getletter($_POST["table"], $_POST["name"], '', '') . "1", -1);
                $Manager->delete_column($_POST["table"], $_POST["name"]);
                echo $_POST["name"] . " deleted from " . $_POST["table"];
                break;
            case "insertrows":
                if($_POST["where"] == 0){$_POST["where"] = $Manager->get_last_entry($_POST["table"])+1;}
                echo $Manager->insert_rows($_POST["table"], $_POST["number"], $_POST["where"]);
                break;
            case "copyreference":
                //($Manager, $Table, $Reference, $Me ="", $Letters = "", $PrimaryKey = "", $FilterBrackets=true, $RefsOnly = false, $ToString = false)
                echo getreferences($Manager, $_POST["table"], $_POST["range"], "", "", "", true, false, true);
                break;
            default:
                debug($_POST);
        }
        die();
    }

    $settings = $this->requestAction('settings/get_settings');
    include_once('subpages/api.php');
    $language = $this->request->session()->read('Profile.language');
    $controller =  $this->request->params['controller'];
    $strings = CacheTranslations($language, array("forms_%" , $controller  . "_%"),$settings);
    if($language == "Debug"){ $Trans = " [Translated]"; } else {$Trans = "";}

    function javascriptarray($Array){
        return '["' . implode('", "', $Array) . '"];';//var cars = ["Saab", "Volvo", "BMW"];
    }

    function printdialog(){
        if (isset($GLOBALS["dialogform"])) {return false;}
        $GLOBALS["dialogform"]=true;
        ?>
            <div id="dialog-form" style="display: none;">
                <form>
                    <label for="prompt" id="prompt_title">Name</label>
                    <SELECT name="prompt" id="prompt_select" class="text ui-widget-content ui-corner-all" />
                        <OPTION>TEST</OPTION>
                    </SELECT>
                    <INPUT TYPE="text" id="prompt_text" class="text ui-widget-content ui-corner-all" />
                </form>
            </div>
        <?php
    }

    function ucfirst2($Text, $DoUnderscore = false){
        if($DoUnderscore){$Text = str_replace("_", " ", $Text);}
        $Text = explode(" ", $Text);
        foreach($Text as $Key => $Value){
            $Text[$Key] = ucfirst($Value);
        }
        return implode(" ", $Text);
    }

    function getTag($Text, $GetTag){
        $Start = strpos($Text, "[");
        $End = strpos($Text, "]");
        if($GetTag){
            if($Start !== false && $End !== false){
                if($Start<$End) {return substr($Text, $Start+1, $End-$Start-1);}
            }
        } else {
            if($Start !== false && $End !== false) {
                return substr($Text, 0, $Start) . substr($Text, $End + 1, strlen($Text) - $End - 1);
            }
            return $Text;
        }
    }
    function assocsplit($Text, $PrimaryDelimeter, $SecondaryDelimeter){
        $PrimaryArray = explode($PrimaryDelimeter, $Text);
        $RET = array();
        foreach ($PrimaryArray as $Value) {
            if(strpos($Value, $SecondaryDelimeter) === false){
                $RET[$Value] = "";
            } else {
                $SecondaryArray = explode($SecondaryDelimeter, $Value);
                $RET[$SecondaryArray[0]] = $SecondaryArray[1];
            }
        }
        return $RET;
    }
    function get_column_letters($PrimaryKey, $Columns){
        $FirstLetter = 0;
        $SecondLetter = 1;
        $Letters = array();
        foreach ($Columns as $ColumnName => $ColumnData){
            //if($ColumnName != $PrimaryKey){
                $Letter = generateletters(array($FirstLetter,$SecondLetter), ord("A"));
                $Letters[$Letter] = $ColumnName;
                $Columns[$ColumnName]["letter"] = $Letter;
                $SecondLetter++;
                if($SecondLetter>26){
                    $SecondLetter=1;
                    $FirstLetter++;
                }
            //}
        }
        return $Letters;
    }
    function generateletters($Letters, $Start = 0){
        $Tempstr = "";
        //if(!is_array($Letters)){$Letters = str_split($Letters);}
        foreach($Letters as $Letter){
            if($Letter>0){
                $Letter=$Letter+$Start-1;
                $Tempstr .= chr($Letter);
            }
        }
        return $Tempstr;
    }
    function getletter($Letters, $ColumnName, $Start = '[', $Finish = '] '){
        $Key = array_search($ColumnName, $Letters);
        if($Key){
            return $Start . $Key . $Finish;
        }
    }

    $Columns="";
    $Table="";
    $PrimaryKey="";
    $Tables="";

    if (!$EmbeddedMode && $Manager->webroot(true) == "intact2"){
        echo '<div class="page-content"><div class="container"><div class="row"><div class="col-md-12"><div class="portlet light">';
    }

    if(isset($_GET["table"]) && $_GET["table"]){
        $HTMLMode=(isset($_GET["mode"]) && $_GET["mode"] == "html") || $EmbeddedMode || isset($_GET["htmlmode"]);
        $Table = $_GET["table"];
        $GLOBALS["Table"] = $Table;
        $PrimaryKey = $Manager->get_primary_key($Table);
        $Columns = $Manager->getColumnNames($Table, "", false);
        $Letters = get_column_letters($PrimaryKey, $Columns);
        $Conditions = "";
        if (isset($_GET["search"])){
            if (strpos($_GET["search"], "%") !== false){//is a pattern
                $Conditions[] = $_GET["column"] . " like '" . $_GET["search"] . "'";
            } else {
                $Conditions[$_GET["column"]] = $_GET["search"];
            }
        }
        $Data = $Manager->enum_all($Table,$Conditions);
        $Count = $Data->count();
        if(!$HTMLMode){$Data = $Manager->paginate($Data);}
        if(!$EmbeddedMode){ ?>
            <div class="form-actions" style="padding-top: 0px;padding-bottom: 0px;margin-bottom: 10px;margin-top: 0px;">
                <div class="row">
                    <div class="col-md-6" align="left">
                        <div id="sample_2_paginate" class="dataTables_paginate paging_simple_numbers" style="margin-top:-10px;">
                            <ul class="pagination sorting">
                                <LI><A HREF="<?= $this->request->webroot . $Controller; ?>">Back</A></LI>
                                <?php if(!$HTMLMode){ ?>
                                    <LI><A ONCLICK="return save(false);">Save</A></LI>
                                    <LI><A HREF="?table=<?= $Table; ?>&action=clearcache">Clear Cache</A></LI>
                                <?php } ?>
                                <LI><A HREF="?table=<?= $Table;?>&mode=<?php
                                    if($HTMLMode){
                                        echo 'sql">SQL';
                                    } else {
                                        echo 'html">HTML';
                                    }
                                    ?></A></LI>
                            </ul>
                        </div>
                    </DIV>
                    <?php if(!$HTMLMode){?>
                        <div class="col-md-6" align="right">
                            <div id="sample_2_paginate" class="dataTables_paginate paging_simple_numbers" style="margin-top:-10px;">
                                <ul class="pagination sorting">
                                    <?= $this->Paginator->prev('< ' . __($strings["dashboard_previous"])); ?>
                                    <?= $this->Paginator->numbers(); ?>
                                    <?= $this->Paginator->next(__($strings["dashboard_next"]) . ' >'); ?>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

                <?php
                printdialog();
                echo '<SCRIPT>';
                commonjavascriptfunctions();
                echo '</SCRIPT>';
        }

        if(!$HTMLMode){?>
            <SCRIPT>
                var lastsection;
                function showsection(Name){
                    if(Name != lastsection){
                        visible(Name,true);
                        if(lastsection){visible(lastsection, false);}
                        lastsection = Name;
                    }
                }
            </SCRIPT>
            <table class="table table-hover table-striped table-bordered table-hover dataTable no-footer">
                <TR>
                    <TD>
                        <?php
                            $Buttons = array("action_search" => "Search", "action_newcol" => "New Column", "action_insert" => "Insert Rows", "action_clear" => "Clear", "action_copy" => "Copy", "action_port" => "Import/Export", "Refresh");
                            foreach($Buttons as $Event => $Value){
                                if(is_numeric($Event)){
                                    switch(strtolower($Value)){
                                        case "floatright":
                                            $floatright = true;
                                            break;
                                        case "refresh":
                                            echo '<INPUT TYPE="BUTTON" onclick="reload(' . "''" . ');" Value="Refresh" style="float: right;">';
                                            break;
                                    }
                                } else {
                                    echo '<INPUT TYPE="button" onclick="showsection(' . "'" . $Event . "'" . ')" value="' . $Value . '" ';
                                    if(isset($floatright)){echo 'style="float:right;"';}
                                    echo '> ';
                                }
                            }
                        ?>
                    </TD>
                </TR>
                <TR ID="action_port" style="display: none">
                    <TD>
                        <A HREF="" class="btn">Export as CSV</A>
                        <A HREF="" class="btn">Export as mySQL</A>
                    </TD>
                </TR>
                <TR ID="action_search" style="display: none">
                    <TD>
                        <FORM method="get" action="<?= $this->request->webroot . $Controller; ?>">
                            <INPUT TYPE="hidden" name="table" value="<?= $Table; ?>">
                            <INPUT TYPE="text" name="search" placeholder="Search" value="<?php if(isset($_GET["search"])){echo $_GET["search"];} ?>">
                            <SELECT NAME="column" style="height:24px;">
                                <?php
                                foreach($Columns as $ColumnName => $ColumnData){
                                    echo '<OPTION value="' . $ColumnName . '"';
                                    if(isset($_GET["column"]) && $_GET["column"] == $ColumnName){echo ' SELECTED';}
                                    echo '>' . getletter($Letters, $ColumnName) . ucfirst2($ColumnName, true) . '</OPTION>';
                                }
                                ?>
                            </SELECT>
                            <input type="submit" value="Search">
                        </FORM>
                    </TD>
                </TR>
                <TR ID="action_clear" style="display: none">
                    <TD>
                        <FORM method="get" action="<?= $this->request->webroot . $Controller; ?>">
                            <INPUT TYPE="hidden" name="action" value="truncate">
                            <INPUT TYPE="hidden" name="table" value="<?= $Table; ?>">
                            <INPUT TYPE="submit" value="Empty Table" onclick="return confirm('Are you sure you want to erase <?= addslashes($Table); ?>?')">
                        </FORM>
                    </TD>
                </TR>
                <TR ID="action_newcol" style="display: none">
                    <TD>
                        <INPUT TYPE="text" name="name" placeholder="Name" id="newcol_name">
                        <SELECT id="newcol_type" style="height:24px;">
                            <OPTION value="INT">Number</OPTION>
                            <OPTION value="DECIMAL">Decimal</OPTION>
                            <OPTION value="TINYINT">Boolean</OPTION>
                            <OPTION value="VARCHAR" SELECTED>Text</OPTION>
                        </SELECT>
                        <LABEL>Length:</LABEL>
                        <INPUT TYPE="text" value="255" maxlength="4" size="4" id="newcol_length" title="I recommend a VARCHAR with a length of at least 255, to allow for equations">
                        <LABEL>Position:</LABEL>
                        <SELECT id="newcol_pos" style="height:24px;">
                            <OPTION value="FIRST">At the beginning</OPTION>
                            <?php
                                foreach($Columns as $ColumnName => $ColumnData){
                                    echo '<OPTION VALUE="' . $ColumnName . '">After: ' . getletter($Letters, $ColumnName) . ucfirst2($ColumnName, true) . '</OPTION>';
                                }
                            ?>
                            <OPTION SELECTED value="">At the end</OPTION>
                        </SELECT>
                        <input type="button" value="New Column" onclick="newcol();">
                    </TD>
                </TR>
                <TR ID="action_insert" style="display: none">
                    <TD>
                        <LABEL>Rows:</LABEL>
                        <INPUT TYPE="number" value="1" min="1" max="1000"  id="insert_num">
                        <LABEL>Before:</LABEL>
                        <INPUT TYPE="number" value="1" min="1" max="<?= $Manager->get_last_entry($Table,$PrimaryKey)+1; ?>"  id="insert_where">
                        <INPUT TYPE="button" value="Insert Rows" onclick="insertrows(-1, -1);">
                    </TD>
                </TR>
                <TR ID="action_copy" style="display: none" width="100%">
                    <TD class="nowrap" width="100%">
                        <INPUT TYPE="text" placeholder="Range, Row # or Column letter" id="copy_range">
                        <INPUT TYPE="button" value="Copy" onclick="copyreference();">
                        <INPUT TYPE="text" id="copy_paste" style="width:50%;">
                    </TD>
                </TR>
        </TABLE>
    <?php } ?>



    <?php printdialog();

    } else if(!$EmbeddedMode) {
        $Tables = $Manager->enum_tables();
    }
?>
<STYLE>
    .nowrap{
        white-space: nowrap;
    }
</STYLE>
<SCRIPT>
    <?php commonjavascriptfunctions(); ?>
    var MyURL = '<?= $Manager->webroot(); ?>excel';
    var Embedded = '<?= $EmbeddedMode; ?>';
    var CurrentTable = '';
    var CurrentPrimaryKey = '';

    window.onbeforeunload = function (e) {
        if (changed.length > 0) {
            var message = "Are you sure you want to quit without saving?", e = e || window.event;
            if (e) {e.returnValue = message;}// For IE and Firefox
            return message;// For Safari
        }
    };

    var columns = <?php if (is_array($Columns)) { echo javascriptarray(array_keys($Columns));} else { echo "false;"; } ?>
    var tables = <?php if (is_array($Tables)) { echo javascriptarray($Tables);} else { echo "false;"; } ?>
    var changed = new Array();
    var changedNew = false;

    function mychangeevent(ID, DoPush){
        var RET = checktags(ID, "single");
        if(DoPush) {
            var Index = changed.indexOf(ID);
            if (RET["Status"]) {
                if (Index == -1) {
                    changed.push(ID);
                }
            } else if (Index > -1) {
                changed.splice(Index, 1);
            }
        }
        return RET;
    }

    function removeelement(id) {
        return (elem=document.getElementById(id)).parentNode.removeChild(elem);
    }

    function save(DoAlert){
        var ID, Text = "";
        for (index = 0; index < changed.length; ++index) {
            ID = changed[index];
            if(Text){Text = Text + "&";}
            Text = Text + ID + "=" + encodeURIComponent(getinputvalue(ID));
        }
        $.ajax({
            url: MyURL,
            type: "post",
            dataType: "HTML",
            data: "action=save&key=&htmlmode=<?= $HTMLMode || $EmbeddedMode; ?>&table=" + CurrentTable + "&" + Text,
            success: function (msg) {
                if(DoAlert){alert(msg);}
                reload("");
            }
        })
        changed = new Array();
        return false;
    }

    function deleterow(ID){
        if (confirm("Are you sure you want to delete '" + ID + "'?")){
            $.ajax({
                url: MyURL,
                type: "post",
                dataType: "HTML",
                data: "action=delete&table=" + CurrentTable + "&key=&id=" + ID,
                success: function (msg) {
                    alert(msg);
                    removeelement(ID);
                }
            })
        }
        return false;
    }

    function getchildtag(element, tagname){
        var Header = element.getElementsByTagName(tagname);
        if(Header.length > 0){
            return '<' + tagname + '>' + Header[0].innerHTML.trim() + '</' + tagname + '>';
        }
        return "";
    }
    function reload(URL){
        if(URL){ URL = "&" + URL; }
        if(Embedded){
            var element = document.getElementById(Embedded);
            var Header = getchildtag(element, 'header');
            var Footer = getchildtag(element, 'footer');
            element.innerHTML = '<TABLE WIDTH="100%;" HEIGHT="100%"><tr><td valign="middle" align="center"><IMG SRC="<?= $this->request->webroot;?>webroot/assets/global/img/loading-spinner-blue.gif"></TD></TR></TABLE>';
             $.ajax({
                url: MyURL,
                type: "get",
                dataType: "HTML",
                data: "table=" + CurrentTable + "&embedded" + URL,
                success: function (msg) {
                    element.innerHTML = Header + msg + Footer;
                }
            })
        } else {
            window.open(MyURL + "?table=<?php
                echo $Table;
                if(isset($HTMLMode) && $HTMLMode) {echo "&mode=html";}
                if (isset($_GET["page"])){
                    echo "&page=" . $_GET["page"] . "&sort=" . $_GET["sort"] . "&direction=" . $_GET["direction"];
                }
                ?>" + URL,"_self");
        }
    }

    function deletecolumn(Name){
        if (confirm("Are you sure you want to delete '" + Name + "'?")){
            $.ajax({
                url: MyURL,
                type: "post",
                dataType: "HTML",
                data: "action=deletecolumn&table=" + CurrentTable + "&name=" + Name,
                success: function (msg) {
                    alert(msg);
                    reload("");
                }
            })
        }
        return false;
    }

    function deletetable(Name){
        if (confirm("Are you sure you want to delete '" + Name + "'?")){
            $.ajax({
                url: MyURL,
                type: "post",
                dataType: "HTML",
                data: "action=deletetable&table=" + Name,
                success: function (msg) {
                    alert(msg);
                    removeelement("table" + Name);
                }
            })
        }
        return false;
    }

    function newtable(){
        var Name =  prompt("Please enter a table name", "New Table").toLowerCase();
        if(Name) {
            if(tables.indexOf(Name) >-1){
                alert("That table exists already");
                return false;
            }
            $.ajax({
                url: MyURL,
                type: "post",
                dataType: "HTML",
                data: "action=newtable&table=" + Name,
                success: function (msg) {
                    tables.push(Name);
                    alert(msg);
                    window.open(MyURL + "?table=" + Name ,"_self");
                }
            })
        }
        return false;
    }

    function insertrows(Quantity, Where){
         if(Quantity < 1){Quantity = getinputvalue("insert_num");}
         if(Where == -1){Where = getinputvalue("insert_where");}
         $.ajax({
            url: MyURL,
            type: "post",
            dataType: "HTML",
            data: "action=insertrows&table=" + CurrentTable + "&number=" + Quantity + "&where=" + Where,
            success: function (msg) {
                if(msg){alert(msg);}
                reload("");
            }
        })
    }

    function newcol(){
        var Name = getinputvalue("newcol_name");
        if (columns.indexOf(Name) > -1){
            alert("'" + Name + "' exists already");
            return false;
        }

        $.ajax({
            url: MyURL,
            type: "post",
            dataType: "HTML",
            data: "action=newcolumn&table=" + CurrentTable + "&name=" + Name + "&type=" + getinputvalue("newcol_type") + "&length=" + getinputvalue("newcol_length") + "&position=" + getinputvalue("newcol_pos"),
            success: function (msg) {
                columns.push(Name);
                alert(msg);
                reload("");
            }
        })
    }

    function copyreference(){
        var Range = getinputvalue("copy_range");
        if(Range) {
            $.ajax({
                url: MyURL,
                type: "post",
                dataType: "HTML",
                data: "action=copyreference&table=" + CurrentTable + "&range=" + Range,
                success: function (msg) {
                    setvalue("copy_paste", msg);
                }
            })
        } else {
            setvalue("copy_paste", "");
        }
    }

    function loadtable(Table){
        if(Embedded){Embedded = "excel_" + Table;}
        CurrentTable = Table;
    }

    var downID = "";
    function handleevent(ID, eventtype, tablename){
        var element = document.getElementById(ID);
        var value = getinputvalue(ID);

        var name = ID.substr(5, ID.length-6).replace("][", ",").split(",");
        CurrentRow = name[0];
        CurrentCol="";
        if (name.length>1) {
            CurrentCol = name[1];
        }

        loadtable(tablename);
        switch(eventtype){
            case 0://oncontextmenu
                if(!Embedded){
                    reload("action=edit&id=" + ID);
                    return false;
                }
                break;
            case 1://onclick
                if(!element.hasAttribute("READONLY")) {
                    var Title = "What would you like the new value of row " + CurrentRow + " column " + CurrentCol + " in " + CurrentTable + " to be?";
                    if (element.hasAttribute("choices")) {
                        downID = ID;
                        listprompt(Title, element.getAttribute("choices").split("|"));
                    } else {
                        textprompt(Title, value);
                        var Textbox = document.getElementById("prompt_text")
                        if (element.hasAttribute("role")){
                            var role = element.getAttribute("role");
                            Textbox.setAttribute("role", role)
                            if(role == "number"){
                                setattribute(Mode, "type", "number");
                            }
                        }
                        /*
                        var newvalue = prompt(Title, value);
                        if (newvalue && value != newvalue) {
                            element.setAttribute("value", newvalue);
                            mychangeevent(ID, true);
                            save(false);
                        }
                        */
                    }
                }
                break;
            case 2://ondblclick

                break;
            case 3://onmousedown
                downID = ID;
                break;
            case 4://onmousemove

                break;
            case 5://mouseup
                if (downID != ID) {
                    alert("Select: " + downID + " to " + ID);
                    //setvalue("clip_range", downID + ":" + ID);
                }
                break;
        }
    }

    <?php loadreasons("edit", $strings); ?>
</SCRIPT>

<?php
    function printtableheader($EmbeddedMode, $Top, $Table = false){
        if($Top){
            printdialog();
            echo '<SCRIPT>loadtable("' . $Table . '");</SCRIPT>';
            echo '<DIV width="100%" height="100%" ';
            if(!$EmbeddedMode){ echo 'style="overflow: auto;"';}
            echo '><table class="table table-hover  table-striped table-bordered table-hover dataTable no-footer" style="margin:0px;"><THEAD><TR>';
        }  else {
            echo '</TBODY></table></DIV>';
        }
    }

    function checknumeric(&$Value){
        if(!$Value){$Value=0;}
        if (!$Value || is_numeric($Value)){return true;}
        echo '[ERROR:isNaN]';
    }

    function asDollars($value = 0) {
        $tempstr = '$' . number_format($value, 2);
        return str_replace(".", ".<SUP>", $tempstr) . "</SUP>";
    }

    if(!$EmbeddedMode) {
        if (isset($_GET["table"])) {
            if ($PrimaryKey) {
                printtable($this, $Manager, $_GET["table"], $PrimaryKey, $Columns, $Letters, $EmbeddedMode, $HTMLMode, $Data, $Count, $Conditions, true);
            } else {
                printtableheader($EmbeddedMode, true, $_GET["table"]);
                echo '<TH>This table has no primary key and cannot be edited</TH></TR></THEAD><TBODY>';
                printtableheader($EmbeddedMode, false);
            }
        } else {
            printtableheader($EmbeddedMode, true, $Table);
            echo '<TH>Table</TH></TR></THEAD><TBODY>';
            foreach ($Tables as $Table) {
                echo '<TR ID="table' . $Table . '"><TD><A onclick="return deletetable(' . "'" . $Table . "'" . ');"><i class="fa fa-times"></i></A> <A HREF="?table=' . $Table . '">' . $Table . '</A></TD></TR>';
            }
            echo '<TR><TD><A onclick="return newtable();"><i class="fa fa-floppy-o"></i> New Table</A>';
            printtableheader($EmbeddedMode, false);
        }
    }

    function printtable($_this, $Manager, $Table, $PrimaryKey = "", $Columns = false, $Letters = false, $EmbeddedMode = false, $HTMLMode = false, $Data = false, $Count = false, $Conditions = false, $AllowNew = false){
        printtableheader($EmbeddedMode, true, $Table);
        if(!$PrimaryKey) {$PrimaryKey = $Manager->get_primary_key($Table);}
        if(!$Columns) {$Columns = $Manager->getColumnNames($Table, "", false);}
        if(!$Letters) {$Letters = get_column_letters($PrimaryKey, $Columns);}
        if(!$Data) {
            $Data = $Manager->enum_all($Table, $Conditions);
            $Count = $Data->count();
            if (!$HTMLMode) {$Data = $Manager->paginate($Data);}
        }

        $events = array("oncontextmenu", "onclick", "ondblclick", "onmousedown", "onmousemove", "onmouseup");
        foreach ($Columns as $ColumnName => $ColumnData) {
            if(!($HTMLMode && $ColumnName == $PrimaryKey)) {
                $Me = $ColumnName;
                $Value = '="return handleevent(' . "'" . $Me . "', ";
                echo "\r\n" . '<TH class="nowrap" ' . $events[0] . $Value . "0,'" . $Table . "');" . '" title="' . $ColumnData["comment"] . '">';//handleevent(ID, eventtype, eventname
                if ($ColumnName == $PrimaryKey) {
                    echo '<i class="fa fa-key"></i>';
                }
                if(!$HTMLMode){
                    $ColumnName = getletter($Letters, $ColumnName) . $ColumnName;
                }
                if($EmbeddedMode){
                    echo ucfirst2($ColumnName, true) . '</TH>';
                } else {
                    echo $_this->Paginator->sort($ColumnName) . ' <A ONCLICK="return deletecolumn(' . "'" . $ColumnName . "'" . ');"><i class="fa fa-times"></i></A></TH>';
                }
            }
        }
        echo '</TR></THEAD><TBODY>';
        foreach ($Data as $Row) {
            $ID = "Data[" . $Row->$PrimaryKey . "]";
            echo "\r\n" . '<TR ID="' . $ID . '">';
            $First = true;
            $NullCols=0;
            $ColIndex=0;
            $HasPassedPkey =false;
            foreach ($Columns as $ColumnName => $ColumnData) {
                $Me = $ID . '[' . $ColumnName . ']';
                if($HTMLMode) {
                    if($NullCols || $ColumnName == $PrimaryKey){
                        if($NullCols) {$NullCols = $NullCols-1;}
                        if($ColumnName == $PrimaryKey){$HasPassedPkey= true;}
                    } else {
                        $Value = '="return handleevent(' . "'" . $Me . "', ";
                        echo '<TD ID="' . $Me . '" ';
                        foreach($events as $index => $event){
                            echo $event . $Value . $index . ",'" . $Table . "'" . ');" ';
                        }
                        $Value = $Row->$ColumnName;
                        $ColKeys = getTag($ColumnData["comment"],true);
                        $Keys = getTag($Value, true);
                        if($ColKeys){
                            if($Keys){$Keys = $ColKeys . "," . $Keys;} else {$Keys = $ColKeys;}
                        }

                        if($Keys){
                            $Keys = assocsplit($Keys, ",", "=");
                            $Value = getTag($Value, false);
                        }

                        $Start = "";
                        $Finish = "";

                        echo ' VALUE="' . $Value . '"';
                        if ($Value && substr($Value,0,1) == "="){
                            echo ' TITLE="' . $Value . '"';
                            $Value = substr($Value, 1, strlen($Value)-1);
                            $Reference = array_search($ColumnName, $Letters) . $Row->$PrimaryKey;
                            $Value = evaluate($Manager, $Table, $Value, $Reference, $Letters, $PrimaryKey);
                        }

                        if(is_array($Keys)) {
                            foreach ($Keys as $Key => $Data) {
                                $Key = strtolower(trim($Key));
                                $Data = trim($Data);

                                switch ($Key) {
                                    case "choices":
                                        echo 'choices="' . $Data . '"';
                                        break;
                                    case "readonly":
                                        echo "READONLY";
                                        break;
                                    case "colspan":
                                        if (strtolower($Data) == "all"){
                                            $Data = count($Columns) - $ColIndex;
                                            if(!$HasPassedPkey){$Data = $Data-1;}
                                        }
                                        echo ' COLSPAN="' . $Data . '"';
                                        $NullCols = $Data - 1;
                                        break;
                                    case "align":
                                        echo ' ALIGN="' . $Data . '"';
                                        break;
                                    case "bgcolor";
                                        echo ' BGCOLOR="' . $Data . '"';
                                        break;
                                    case "validate";
                                        if($Data) {
                                            if ($Value) {
                                                $Test = $Manager->validate_data($Value, $Data);
                                                if (!$Test) {$Value = '[ERROR: Not a valid ' . $Data . ']';}
                                            }
                                            echo ' role="' . $Data . '"';
                                        }
                                        break;
                                    case "format";
                                        switch (strtolower($Data)){
                                            case "uppercase":
                                                $Value = strtoupper($Value);
                                                break;
                                            case "lowercase":
                                                $Value = strtolower($Value);
                                                break;
                                            case "percent":
                                                if (checknumeric($Value)) {$Value = number_format($Value * 100, 2) . '%';}
                                                break;
                                            case "number":
                                                if (checknumeric($Value)) {$Value = number_format($Value, 2);}
                                                break;
                                            case "currency":
                                                if (checknumeric($Value)) {$Value = asDollars($Value);}
                                                break;
                                        }
                                        break;

                                    //Inside TD tags
                                    case "bold":
                                        $Start .= '<B>';
                                        $Finish = '</B>' . $Finish;
                                        break;
                                    case "italic":
                                        $Start .= '<I>';
                                        $Finish = '</I>' . $Finish;
                                        break;
                                    case "underline":
                                        $Start .= '<U>';
                                        $Finish = '</U>' . $Finish;
                                        break;
                                    case "fontcolor":
                                        $Start .= '<FONT COLOR="' . $Data . '">';
                                        $Finish = '</FONT>' . $Finish;
                                        break;
                                    case "fontsize":
                                        $Start .= '<FONT SIZE="' . $Data . '">';
                                        $Finish = '</FONT>' . $Finish;
                                        break;

                                    default:
                                        echo ' ' . $Key . '="' . $Data . '"';
                                }
                            }
                        }
                        $Inline=!isset($Keys["readonly"]) && $GLOBALS["inline"];
                        if($Inline){
                            $Start .= '<a href="javascript:;" data-name="' . $ColumnName . '" data-type="text" data-pk="' . $Row->$PrimaryKey . '" data-url="' . $Manager->webroot() . 'excel?action=inlinesave&table=' . $Table . '" data-original-title="Enter ' . ucfirst2($ColumnName, true) . '" class="editable">';
                            $Finish = '</A>' . $Finish;
                        }
                        echo '>' . $Start . $Value . $Finish . '</TD>';
                    }
                } else {
                    $Type = "text";
                    echo '<TD style="padding: 0;" align="center">';
                    if ($ColumnName == $PrimaryKey) {
                        echo '<A ONCLICK="return deleterow(' . "'" . $ID . "'" . ');"<i class="fa fa-times"></i>' . $Row->$PrimaryKey . '</A>';
                    } else {
                        echo '<INPUT NAME="' . $Me . '" ID="' . $Me . '" VALUE="' . $Row->$ColumnName . '" CLASS="textinput" onchange="mychangeevent(' . "'" . $Me . "'" . ', true);"';
                        echo ' PLACEHOLDER="' . $ColumnName . "." . $Row->$PrimaryKey . '" STYLE="width:100%;" ';
                        switch ($ColumnData["type"]) {
                            case "string":
                                break;
                            case "text":
                                break;
                            case "boolean":
                                $Type = "checkbox";
                                echo ' VALUE="True"';
                                if ($Row->$ColumnName) {
                                    echo ' CHECKED';
                                }
                            case "integer":
                                echo ' role="number"';
                                break;
                            case "decimal":
                                echo ' role="number"';
                                break;

                            default:
                                debug($ColumnData);
                                die();
                        }
                        echo 'TYPE="' . $Type . '">';
                    }
                    echo '</TD>';
                }
                $ColIndex++;
            }
            echo '</TR>';
        }

        if($HTMLMode) {
            if ($AllowNew){
                echo '<TR><TD COLSPAN="' . count($Columns) . '"><A ONCLICK="return insertrows(1,0);">Make new row</A></TD></TR>';
            }
        } else {
            echo '<TR>';
            foreach ($Columns as $ColumnName => $ColumnData) {
                echo '<TD style="padding: 0;" align="center" class="nowrap">';
                $ID = "Data[new]";
                if ($ColumnName == $PrimaryKey) {
                    echo '<A onclick="return save(true);"><i class="fa fa-floppy-o"></i>New</A>';
                } else {
                    $Me = $ID . '[' . $ColumnName . ']';
                    $Type = "text";
                    echo '<INPUT NAME="' . $Me . '" ID="' . $Me . '" " CLASS="textinput" onchange="mychangeevent(' . "'" . $Me . "'" . ', true);"';
                    echo ' PLACEHOLDER="' . $ColumnName . '.new" STYLE="width:100%;" ';
                    switch ($ColumnData["type"]) {
                        case "boolean":
                            $Type = "checkbox";
                            echo ' VALUE="True"';
                            break;
                        case "integer":
                            echo ' role="number"';
                            break;
                        case "decimal":
                            echo ' role="number"';
                            break;
                    }
                    echo 'TYPE="' . $Type . '">';
                }
                echo '</TD>';
            }
        }
        echo '</TR>';
        printtableheader($EmbeddedMode, false);
    }

    function evaluate($Manager, $Table, $Equation, $Me, $Letters, $PrimaryKey, $p="") {
        if(!$p) {$p = new ParensParser();}
        if (substr($Equation,0,1) == "=") {$Equation = substr($Equation, 1, strlen($Equation) - 1);}
        $DidRecurse=false;
        $Equation = $p->parse($Equation);
        $Equation = evaluatereferences($p, $Manager, $Table, $Equation, $Me, $Letters, $PrimaryKey, $DidRecurse);
        $Equation = $p->condense($Equation);
        $Equation = 'return ' . $Equation . ';';
        $Equation = eval($Equation);
        return $Equation;
    }

    function evaluatereferences($p, $Manager, $Table, $Equation, $Me, $Letters, $PrimaryKey){
        if(is_array($Equation)){
            foreach($Equation as $Key => $Cell){
                $Equation[$Key] = evaluatereferences($p, $Manager, $Table, $Cell, $Me, $Letters, $PrimaryKey);
            }
        } else {
            $Equation = $p->splitequation($Equation);
            foreach($Equation as $Key => $Cell) {
                if (isareference($Manager, $Cell)) {
                    $Cell = getreference($Manager, $Table, $Cell, $Me, $Letters, $PrimaryKey);
                    if ($Cell && substr($Cell,0,1) == "=") {
                        $Cell = substr($Cell, 1, strlen($Cell)-1);
                        $Cell = evaluate($Manager, $Table, $Cell, $Key, $Letters, $PrimaryKey, $p);
                    }
                } else if (ismultireference($Manager, $Cell)) {
                    $Data = getreferences($Manager, $Table, $Cell, $Me, $Letters, $PrimaryKey);
                    foreach($Data as $DataKey => $DataValue){
                        if ($DataValue && substr($DataValue,0,1) == "=") {
                            $Data[$DataKey] = evaluate($Manager, $Table, $DataValue, $DataKey, $Letters, $PrimaryKey, $p);
                        }
                    }
                    $Cell = '[' . implode(",", $Data) . ']';
                }
                $Equation[$Key]=$Cell;
            }
            $Equation = implode(" ", $Equation);
        }
        return $Equation;
    }

    function fixreferences($Manager, $Table, $IsLetter, $StartingCellColumn, $StartingCellRow, $OffsetColumn = 0, $OffsetRow = 0){
        echo "Fix: " . $Table . " starting at " . $StartingCellColumn . "(" . $IsLetter . ")," . $StartingCellRow . " Offset: " . $OffsetColumn . ',' . $OffsetRow;
        //$Columns, $Letters,
/*
        $p = new ParensParser();
        $Equation = $p->parse($Equation);

        $Equation = $p->condense($Equation);
        return $Equation;
*/
    }



class ParensParser {//https://gist.github.com/Xeoncross/4710324
    protected $stack = null;
    protected $current = null;
    protected $string = null;
    protected $position = null;
    protected $buffer_start = null;

    public function parse($string) {
        if (!$string) {return array();}
        if ($string[0] == '(') {$string = substr($string, 1, -1);}
        $this->current = array();
        $this->stack = array();
        $this->string = $string;
        $this->length = strlen($this->string);
        $haspushed = false;
        for ($this->position=0; $this->position < $this->length; $this->position++) {
            switch ($this->string[$this->position]) {
                case '(':
                    $this->push();
                    array_push($this->stack, $this->current);
                    $this->current = array();
                    $haspushed = $this->position;
                    break;
                case ')':
                    $this->push();
                    $t = $this->current;
                    $this->current = array_pop($this->stack);
                    $this->current[] = $t;
                    $haspushed = $this->position;
                    break;
                default:
                    if ($this->buffer_start === null) {$this->buffer_start = $this->position;}
            }
        }
        if($haspushed){
            $buffer = substr($string, $haspushed+1, strlen($string)-$haspushed-1);
            $this->current[] = trim($buffer);
        }
        return $this->current;
    }
    protected function push() {
        if ($this->buffer_start !== null) {
            $buffer = substr($this->string, $this->buffer_start, $this->position - $this->buffer_start);
            $this->buffer_start = null;
            $this->current[] = trim($buffer);
        }
    }

    public function condense($array, $IsFirst = true){
        if(is_array($array)){
            foreach($array as $key => $cell){
                $array[$key] = $this->condense($cell, False);
            }
            if($IsFirst){return implode("", $array);}
            $array = "(" . implode("", $array) . ")";
        }
        $array = str_replace("([])", "()", $array);
        return $array;
    }

    public function splitequation($string) {
        $expr = '/[^\d.]|[\d.]++/';
        preg_match_all( $expr, $string, $return );
        $return = $return[0];
        foreach($return as $Key => $Value){
            if(!trim($Value)){unset($return[$Key]);}
        }
        $return = $this->joinLetters($return);
        return $return;
    }

    protected function joinLetters($array){
        $return = array();//a-z,A-Z,!,:,0-9   \w
        $Current = "";
        $IsLetter = false;
        foreach($array as $Value){
            $Cletter = $this->isLetter($Value);
            if ($Current && $IsLetter != $Cletter){
                array_push($return, $Current);
                $Current="";
            }
            $Current.=$Value;
            $IsLetter=$Cletter;
        }
        if($Current) {array_push($return, $Current);}
        return $return;
    }

    protected function isLetter($Text){
        if ($Text == '$') { return true;}
        return preg_replace("/[^a-zA-Z0-9:!]/", "", $Text) == true;
    }

}


    if($EmbeddedMode){
        return;
    } else if ($Manager->webroot(true) == "intact2"){
        echo '</DIV></DIV></DIV></DIV></DIV>';
    }
?>