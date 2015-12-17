var lastelement, oldcolor;

Element.prototype.hasClass = function(className) {
    return this.className && new RegExp("(^|\\s)" + className + "(\\s|$)").test(this.className);
};

function validate_data(Data, DataType){
    if(Data) {
        //alert("Testing: " + Data + " for " + DataType);
        switch (DataType.toLowerCase()) {
            case "email":
                var re = /\S+@\S+\.\S+/;
                return re.test(Data);
                break;
            case "postalzip":
                return validate_data(Data, "postalcode") || validate_data(Data, "zipcode");
                break;
            case "zipcode"://99577-0727
                Data = clean_data(Data, "number");
                return Data.length == 5 || Data.length == 9;
                break;
            case "postalcode":
                Data = Data.replace(/ /g, '').toUpperCase(); //Postal codes do not include the letters D, F, I, O, Q or U, and the first position also does not make use of the letters W or Z.
                var regex = new RegExp(/^[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ]?\d[ABCEGHJKLMNPRSTVWXYZ]\d$/i);
                return regex.test(Data);
                break;
            case "phone":
                return true;//skipping validation for now
                var phoneRe = /^[2-9]\d{2}[2-9]\d{2}\d{4}$/;
                var regex = /[^\d+]/;
                var Data2 = clean_data(Data, "number");
                return (Data2.match(phoneRe) !== null || Data2.length > 0);
                break;
            case "sin":
                Data = clean_data(Data, "number");
                return Data.length == 9;
                break;
            case "number":
                Data = clean_data(Data, "number");
                return Data && !isNaN(Data);
            default:
                alert("'" + DataType + "' is unhandled");
        }
    }
    return true;
}

function clean_data(Data, DataType){
    Data = Data.trim();
    if(Data) {
        switch (DataType.toLowerCase()) {
            case "alphabetic":
                Data = Data.replace( /[^a-zA-Z]/, "");
                break;
            case "alphanumeric":
                Data = Data.replace(/\W/g, '');
            break;
            case "number":
                Data = Data.replace(/\D/g, "");
                break;
            case "email":
                Data = Data.toLowerCase();
                break;
            case "postalzip":
                if (validate_data(Data, "postalcode")){Data = clean_data(Data, "postalcode");}
                if (validate_data(Data, "zipcode")){Data = clean_data(Data, "zipcode");}
                break;
            case "zipcode":
                Data = clean_data(Data, "number");
                if(Data.length == 9){Data = Data.substring(0,5) + "-" + Data.substring(5,9);}
                break;
            case "postalcode":
                Data = clean_data(replaceAll(" ", "", Data.toUpperCase()), "alphanumeric");
                Data = Data.substring(0,3) + " " + Data.substring(3);
                break;
            case "phone":
                var Data2 = clean_data(Data, "number");
                if(Data2.length == 10) {
                    Data = Data2.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3");
                } else {
                    Data = Data.replace(/[^0-9+]/g, "");
                }
                break;
            case "sin":
                Data = clean_data(Data, "number");
                Data = Data.substring(0,3) + "-" + Data.substring(3,6)  + "-" + Data.substring(6,9) ;
                break;
        }
    }
    return Data;
}

function hasClass(elem, className) {
    return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
}

function strip(html) {
    var tmp = document.createElement("DIV");
    tmp.innerHTML = html.trim();
    return tmp.textContent || tmp.innerText || "";
}

function checkalltags(TabID){
    deleteall("deleteme");
    var inputs = checktags(TabID, 'input');
    if (!inputs['Status']){return false;}
    inputs = checktags(TabID, 'select');
    if (!inputs['Status']){return false;}
    var inputs = validatespecialrules();
    if(!inputs){return true;}
    alert(inputs);
}

function validatespecialrules(){
    var element = document.getElementById("specialrule");
    if(element){
        var Rule = element.value;
        switch(Rule){
            case "meeattach"://hard-coded rule for mee_attach
                if(isvisible("form_tab15")){
                    var Forms = element.getAttribute("forms").split(",");
                    Rule = element.getAttribute("driverprovince");
                    if(Forms.indexOf("1603") > -1){
                        if (!getinputvalue("meeattach_id1") && !getinputvalue("meeattach_id2")){return MissingID;}
                    }
                    if ((Rule == "QC" && Forms.indexOf("1") > -1) || ((Rule == "BC" || Rule == "SK") && Forms.indexOf("14") >-1)){
                        if(isvisible("mee_attach_7") && !getinputvalue("mee_attach_7")){return MissingAbstract;}
                    }
                }
                break;
        }
    }
    return false;
}

function isvisible(elementName){
    var element = document.getElementById(elementName);
    if(element) {
        return element.offsetWidth > 0 || element.offsetHeight > 0;
    }
}

function radiovalue(Name){
    var radios = document.getElementsByName(Name);
    for (var i = 0, length = radios.length; i < length; i++) {
        if (radios[i].checked) {
            return radios[i].value;
            break;
        }
    }
    return "";
}

function isVisible (element) {
    return element.clientWidth !== 0 && element.clientHeight !== 0 && element.style.opacity !== 0 && element.style.visibility !== 'hidden';
}
function elementtype(element){
    return element.tagName.toLowerCase();
}
function elementtype2(element){
    var tagtype;
    if (element.hasAttribute("type")) {
        tagtype = element.getAttribute("type").toLowerCase().trim();
    } else {
        tagtype = elementtype(element);
    }
    if (element.hasAttribute("role")) {
        tagtype = element.getAttribute("role");
    }
    if(element.hasClass("datepicker") || element.hasClass("date-picker")){
        tagtype = "date";
    }
    return tagtype;
}

function setinputvalue(element,newvalue) {
    if(typeof element !== 'object'){
        element = document.getElementById(element);
        if(!element){return false;}
    }
    tagtype = elementtype2(element);
    switch(tagtype){
        case "radio":case "checkbox":
            element.checked = newvalue;
            element.parentElement.classList.add("checked");
            break;
        default:
            element.value = newvalue;
    }
}

function set_visible(element, status){
    if(typeof element !== 'object'){
        element = document.getElementById(element);
        if(!element){return false;}
    }
    if(status){
        element.style.visibility = 'visible';
    } else{
        element.style.visibility = 'hidden';
    }
}

function getinputvalue(element){
    if(typeof element !== 'object'){
        element = document.getElementById(element);
        if(!element){return false;}
    }
    var value = element.value;
    if (element.hasAttribute("type")) {
        tagtype = element.getAttribute("type").toLowerCase().trim();
    } else {
        tagtype = elementtype(element);
    }
    switch (tagtype){
        case "td":
            value = element.getAttribute("value");
            break;
        case "checkbox":
            if (!element.checked){value = "";}
            break;
        case "radio":
            value = radiovalue(name);
            break;
        case "select":
            value = element.options[element.selectedIndex].value;
            break;
    }
    return value;
}

function findindex(element){
    var Name = element.getAttribute("name");
    var elements = document.getElementsByName(Name);
    for(var i=0; i<elements.length; i++){
        if (elements[i] == element){
            return i;
        }
    }
    return -1;
}
function getindex(Name, Index){
    var elements = document.getElementsByName(Name);
    return elements[Index];
}

function checktags(TabID, tagtype){//use tagtype = "single" to get a single element with the ID = TabID
    var element, inputs, endDates = new Object();
    resetelement();
    if(TabID) {
        element = document.getElementById(TabID);
        if(tagtype == "single"){
            inputs = [element];
        } else {
            inputs = element.getElementsByTagName(tagtype);
        }
    } else {
        inputs = document.getElementsByTagName(tagtype);
    }
    var RET = new Array();
    RET['Status'] = true;
    if(!reasons){return RET;}
    for (index = 0; index < inputs.length; ++index) {
        element = inputs[index];
        if(isVisible(element)) {//ignores invisible elements
            isrequired = hasClass(element, "required") || element.hasAttribute("required");
            var value = getinputvalue(element);
            var name = element.getAttribute("name");
            if (element.hasAttribute("type")) {
                tagtype = element.getAttribute("type").toLowerCase().trim();
            }
            var isValid = true;
            var Reason = "";

            if (!value && isrequired) {
                Reason = "required";
                isValid = false;
            } else if (element.hasAttribute("role")) {
                Reason = element.getAttribute("role");
                isValid = validate_data(value, Reason);
            }

            if(name && isValid && value && (element.hasClass("datepicker") || element.hasClass("date-picker"))){
                var EndDate = Date.parse(value);
                if(name.indexOf("_end") > -1){//make sure end date is after start date
                    if(!endDates.hasOwnProperty(name)){
                        endDates[name] = 0;
                    } else {
                        endDates[name] = endDates[name] + 1;
                    }
                    var StartDate = name.replace("_end", "_start");
                    StartDate = document.getElementsByName(StartDate);
                    StartDate = StartDate[endDates[name]].value;
                    if(StartDate) {
                        StartDate = Date.parse(StartDate);
                        isValid = StartDate < EndDate;
                        if (!isValid) {Reason = "paradox";}
                    }
                } else if (name.indexOf("expiry") > -1){
                    var StartDate = Date.now();
                    isValid = StartDate < EndDate;
                    if (!isValid) {Reason = "expired";}
                }
            }

            if (isValid && Reason) {
                value = clean_data(value, Reason);
                element.value = value;
            } else if (!isValid) {
                //alert($('.tabber.active').attr('id'));
                RET['Name'] = name;
                RET['Type'] = tagtype;
                name = getName(element);
                RET['Status'] = false;
                RET['Element'] = name;
                RET['Reason'] = Reason;
                RET['Value'] = value;
                scrollto(RET, element);
                return RET;
            }
            
        }
    }
    return RET;
}

function resetelement(){
    if(lastelement){
        lastelement.style.borderColor = oldcolor;
    }
}

function alertfail(Reason){
     if(!Reason["Status"]){
         if (Reason['Reason'] == "required"){
            var text = reasons["required"];
         } else {
            var text = reasons['fail'];
         }
         text = replaceAll("%name%", Reason["Element"], text);
         text = replaceAll("%value%", Reason["Value"], text);
         text = replaceAll("%type%", reasons[Reason["Reason"]], text);
         return text;
         //alert(Reason["Element"]);
         //alert("Name: " + Reason["Element"] + "\r\n (" + Reason["Value"] + ") is not valid (" + Reason['Reason'] + ")");
     }
    return false;
}

function findLableForControl(element) {
    var idVal = element.id;
    labels = document.getElementsByTagName('label');
    for( var i = 0; i < labels.length; i++ ) {
        if (labels[i].htmlFor == idVal)
            return labels[i];
    }
}

function getElementsByClassName(oElm, strTagName, strClassName){
    var arrElements = (strTagName == "*" && oElm.all)? oElm.all :  oElm.getElementsByTagName(strTagName);
    var arrReturnElements = new Array();
    strClassName = strClassName.replace(/\-/g, "\\-");
    var oRegExp = new RegExp("(^|\\s)" + strClassName + "(\\s|$)");
    var oElement;
    for(var i=0; i<arrElements.length; i++){
        oElement = arrElements[i];
        if(oRegExp.test(oElement.className)){
            arrReturnElements.push(oElement);
        }
    }
    return (arrReturnElements);
}

function remove(elem) {
    return elem.parentNode.removeChild(elem);
}

function deleteall(Class) {
    var cusid_ele = document.getElementsByClassName(Class);
    for (var i = 0; i < cusid_ele.length; ++i) {
        remove(cusid_ele[i]);
    }
}

function scrollto(Reason, element){
    /*
    var value = element.value;
    var name = element.getAttribute("name");
    alert(name + " = " + value);
    */
    //element.scrollIntoView();
    //$(element).parent().find('span').remove();
    var rsn = alertfail(Reason);
    //alert(rsn);
    //if($(element).parent().find('.error'))
    //    $(element).parent().find('.error').text(rsn);
    //else
    $(element).parent().append('<span class="error deleteme" style="position:absolute; font-size:12px; background-color: white; z-index: 1;">'+rsn+'</span>');
    $('html,body').animate({ scrollTop: ($(element).offset().top)-80}, 'slow');
    
    //alert($(element).attr('name'));
    //if(Reason["Type"] == "checkbox"){element = findLableForControl(element);}
    oldcolor=element.style.borderColor;
    element.style.borderColor = "red";
    element.focus();
    lastelement = element;
}

function getName(element){
    var name;
    if (element.hasAttribute("placeholder")) {
        name = element.getAttribute("placeholder");
    } else {
        var ele = element.previousElementSibling;
        if (ele === null) {ele = element.parentElement.previousElementSibling;}
        if (ele === null) {ele = element.parentElement.parentElement;}
        name = ele.innerHTML;
        name = strip(name.replace(":", "")).trim();
    }
    return name.trim();
}

function replaceAll(find, replace, str) {
    return str.replace(new RegExp(find, 'g'), replace);
}

function radiovalue(Name){
    var genders = document.getElementsByName(Name);
    for(var i = 0; i < genders.length; i++) {
        if(genders[i].checked == true) {
            return genders[i].value;
        }
    }
}

function autofill2(Type){
    if(!Type){
        autofill2("input");
        autofill2("select");
        autofill2("textarea");
    } else {
        var inputs, index, element, value, name, temp;
        inputs = document.getElementsByTagName(Type);
        for (index = 0; index < inputs.length; ++index) {
            element = inputs[index];
            name = element.getAttribute("name");
            value = getinputvalue(element);

            if (element.hasAttribute("type")) {
                Type = element.getAttribute("type");
                if(Type == "radio"){
                    value = radiovalue(name);
                }
            }

            if(name && !value) {
                temp = name.indexOf("][");
                if(temp > -1){
                    name = name.substr(temp+2);
                    name = name.substr(0, name.length-1);
                }
                if (element.hasAttribute("role")) {
                    Type = element.getAttribute("role");
                }
                if(element.hasClass("datepicker") || element.hasClass("date-picker") || element.hasClass("dp")){
                    Type = "date";
                }

                switch(Type){
                    case "hidden":
                        break;
                    case "checkbox":case "radio":
                        if(Math.random() > 0.5){element.click();}
                        break;

                    case "file":
                        value="";
                        break;
                    case "text":case "textarea":
                        value=randomtext();
                        break;
                    case "phone":
                        value="905555" + getRandomInt(1000,9999);
                        break;
                    case "postalcode":
                        value = "L7P6V6";
                        break;
                    case "select":
                        while(!value) {
                            value = element.options[getRandomInt(0, element.options.length-1)].value;
                        }
                        break;
                    case "date":
                        var Now = new Date().getFullYear(); var StartYear = Now-25; var EndYear = Now;
                        if(name.indexOf("expiry")>-1){
                            StartYear = Now+1;
                            EndYear = Now+20;
                        } else if (name.indexOf("_end")>-1) {
                            temp = findindex(element);
                            name = name.replace("_end", "_start");
                            value = getindex(name, temp);
                            value = getinputvalue(value);
                            if(value.indexOf("/")>-1) {
                                StartYear = parseInt(value.substring(6, value.length)) + 1;
                            } else {
                                StartYear = parseInt(value.substring(0, 4)) + 1;
                            }
                            EndYear = StartYear + 25;
                        }
                        //value = "10/04/" + getRandomInt(StartYear,EndYear);
                        value = getRandomInt(StartYear,EndYear) + "-10-31";
                        break;
                    case "email":
                        value = randomemail(20);
                        break;
                    case "sin":
                        value = getRandomInt(100,999) + "-" + getRandomInt(100,999) + "-" + getRandomInt(100,999);
                        break;
                }
                if (value){
                    setinputvalue(element, value);
                } else {
                    //alert(name + " (" + Type + ") " + value);
                }
            }
        }
    }
}

function randomemail(Length){
    return "info+" + makeid(Length) + "@trinoweb.com";
}
function makeid(Length) {
    var text = "";
    var possible = "abcdefghijklmnopqrstuvwxyz0123456789";
    for( var i=0; i < Length; i++ ) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
}
function randomtext(){
    var randomtext = ["Lorem ipsum", "dolor sit", "amet, consectetur", "adipiscing elit", "sed do eiusmod", "tempor incididunt", "ut labore et", "dolore magna aliqua", "Ut enim ad", "minim veniam", "quis nostrud", "exercitation ullamco", "laboris nisi ut", "aliquip ex ea", "commodo consequat", "Duis aute irure", "dolor in", "reprehenderit in", "voluptate velit", "esse cillum", "dolore eu fugiat", "nulla pariatur", "Excepteur sint occaecat", "cupidatat non proident", "sunt in culpa", "qui officia deserunt", "mollit anim", "id est laborum."];
    return randomtext[Math.floor(Math.random() * randomtext.length)];
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function handlewebservice(msg, controller, action, status, webroot){
    if(msg.indexOf("<BR>error") > -1){
        if(msg.indexOf("<BR>error1") >-1){
            var error = "ins_id";
        } else if (msg.indexOf("<BR>error2") >-1){
            var error = "ebs_id";
        }
        //alert(controller + "/" + action + "=" + status + "/r/nAn error occured, ID " + error + "not found");
        window.location = webroot + 'orders/orderslist?draft';
    } else {
        //alert(controller + "/" + action + "=" + status + "/r/nMessage: " + msg);
        if (controller == "rapid" && action == "cron_user" && status) {
            alert('Cron ran successfully.');
        }
    }
}