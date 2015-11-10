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
    return true;
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

function setinputvalue(element,newvalue) {
    if(typeof element !== 'object'){
        element = document.getElementById(element);
        if(!element){return false;}
    }
    tagtype = elementtype(element);
    switch(tagtype){

        default:
            element.value = newvalue;
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

function checktags(TabID, tagtype){//use tagtype = "single" to get a single element with the ID = TabID
    var element, inputs;
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
         //alert(text);
         text = text.replace('all','');
         text = text.replace('the','this');
         text = text.replace('fields','field');
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