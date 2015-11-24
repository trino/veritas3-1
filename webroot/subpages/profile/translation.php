<?php
    if($this->request->session()->read('debug')) {
        echo "<span style ='color:red;'>subpages/profile/translation.php #INC???</span>";
    }
?>
<TABLE width="100%">
    <TR>
        <TD VALIGN="TOP" align="left" style="width: 150px;">
            <button class="btn btn-primary" onclick="newlanguage();" style="width: 150px;">New Language</button><BR>
            <select id="languages" name="languages" size="<?= count($languages); ?>" style="width: 150px;" onclick="search(0);">
                <?php
                    foreach($languages as $language) {
                        echo '<option value="' . $language . '"';
                        if($language == "English"){ echo " SELECTED";}
                        echo '>' . $language . '</option>';
                    }
                ?>
            </select><P>
            <button class="btn btn-danger" onclick="deletelanguage();" style="width: 150px;">Delete Language</button><P>
            <DIV ID="newfields">
                <INPUT ID="name" placeholder="Name"  style="width: 150px;"><br>
                <?php foreach($languages as $language) {
                    echo '<INPUT ID="newfield' . $language . '" placeholder="' . $language . '"  style="width: 150px;"><br id="br' . $language . '">';
                } ?>
            </DIV>
            <button class="btn btn-danger" onclick="savestring();" style="width: 150px;">Save String</button><P><P>
            <button class="btn btn-primary" onclick="sendtoroy();" style="width: 150px; <?php if(!$hascache){ echo 'display: none;'; }?>" id="sendtoroy">Send To Roy</button>
        </TD>
        <TD VALIGN="TOP" align="left">
            <INPUT TYPE="TEXT" ID="search" placeholder="Search" onkeyup="search(0);">
            <TABLE class="table table-light table-hover" ID="searchresults" width="100%">
            </TABLE>
        </TD>
    </TR>
</TABLE>
<SCRIPT>
    var languages = ['<?= implode("', '", $languages) ?>'];

    function test(Number){
        search(Number);
    }

    function sendtoroy(){
        visible('sendtoroy', false);
        AJAX('sendtoroy');
    }

    function visible(Element, Status){
        var Element =  document.getElementById(Element);
        if(Status){
            Element.style.display = 'block';
        } else {
            Element.style.display = 'none';
        }
    }

    function getvalue(Element){
        return document.getElementById(Element).value;
    }
    function getattribute(Element, Class){
        return document.getElementById(Element).getAttribute(Class);
    }

    function setValue(Element,newvalue) {
        var element = document.getElementById(Element);
        element.value = newvalue;
    }

    function itemclick(Name){
        setValue("name", Name);
        for(var Index = 0; Index < languages.length; Index++){
            var language = languages[Index];
            var Value = getattribute("item" + Name, language);
            setValue("newfield" + language, Value);
        }
    }

    function search(Start){
        var string = getvalue("search");
        var language = getvalue("languages");
        var Limit = 20;
        if(string == ""){string = "*";}
        if(string.length>2 || string == "*") {
            AJAX("searchstrings", "string=" + string + "&language=" + language + "&languages=" + languages.join() + "&start=" + Start + "&limit=" + Limit);
        } else {
            $('#searchresults').html("Search string must be longer than 2 digits. You have " + string.length);
        }
        return false;
    }

    function savestring(){
        var Data = "Name=" + getvalue("name") + "&languages=" + languages.join();
        var Language = "";
        if(getvalue("name").length == 0){return false;}
        for(var Index = 0; Index < languages.length; Index++){
            Language = languages[Index];
            Data = Data + "&" + Language + "=" + getvalue("newfield" + Language);
        }
        AJAX("newstring", Data);
        visible('sendtoroy', true);
    }

    function appendHTML(Element, HTML){
        var element = document.getElementById(Element);
        element.insertAdjacentHTML('beforeend', HTML);
    }

    function addselectoption(SelectID, Text, Value){
        var option = document.createElement("option");
        option.text = Text;
        option.value = Value;
        var select = document.getElementById(SelectID);
        select.appendChild(option);
    }
    function increasesize(SelectID){
        var select = document.getElementById(SelectID);
        var size = parseInt(select.getAttribute("size"));
        select.setAttribute("size", size+1);
    }

    function removeselectoption(SelectID, Index){
        var select = document.getElementById(SelectID);
        if (Index<0){Index = select.selectedIndex;}
        select.remove(Index);
    }

    function ucfirst(str) {
        str += '';
        var f = str.charAt(0)
            .toUpperCase();
        return f + str.substr(1);
    }

    function newlanguage(){
        var language = prompt("What would you like the new language to be called?");
        if(language){
            language = ucfirst(language);
            if (languages.indexOf(language) >-1){
                alert(language + " already exists");
            } else {
                languages.push(language);
                addselectoption("languages", language, language);
                increasesize("languages");
                appendHTML("newfields", '<INPUT ID="newfield' + language + '" placeholder="' + language + '"  style="width: 150px;"><br id="br' + language + '">');
                AJAX("newlanguage", "language=" + language);
            }
        }
    }

    function removeelement(Element) {
        var element = document.getElementById(Element);
        return (elem=element).parentNode.removeChild(elem);
    }

    function deletelanguage(){
        var element = document.getElementById("languages");
        if(element.value == "English" || element.value == "French"){
            alert(element.value + " can't be deleted");
        } else if (confirm("Are you sure you want to delete " + element.value + "?")) {
            AJAX("deletelanguage", "language=" + element.value);
            removeelement("newfield" + element.value);
            removeelement("br" + element.value);
            removeselectoption("languages", -1);
        }
    }

    function AJAX(type, Data){
        $.ajax({
            url: "<?php echo $this->request->webroot;?>profiles/products",
            type: "post",
            dataType: "HTML",
            data: "Type=" + type + "&" + Data,
            success: function (msg) {
                switch(type){
                    case "deletelanguage":
                        alert("Delete successful");
                        break;
                    case "newlanguage":
                        alert("Language created");
                        break;
                    case "searchstrings":
                        $('#searchresults').html(msg);
                        break;
                    case "newstring":
                        alert("Don't forget to click 'Send to Roy' when you're done!")
                        break;
                    default:
                        alert(msg);
                }
            },
        })
    }

    search(0);
</SCRIPT>
