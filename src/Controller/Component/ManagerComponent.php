<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Cache\Cache;
use Cake\Datasource\ConnectionManager;
use DateTime;

class ManagerComponent extends Component {
    function init($Controller){
        if($Controller->request->params['controller']!='ClientApplication'){
            $this->Controller = $Controller;
            $Controller->set("Manager", $this);
            $this->Me = $this->read("id");
            $Controller->set("Me", $this->Me);

            if(isset($_GET["action"])){
                switch (strtolower($_GET["action"])){
                    case "testemail":
                        $this->handleevent("test", array("email" => "roy@trinoweb.com"));
                        break;
                }
            }

            $Controller->loadComponent("Settings");
            $Controller->Settings->verifylogin($Controller,$Controller->name);
        }
    }

    function permissions($Permissions, $Sidebar = false, $Blocks = false, $UserID = false){
        if(!$UserID){$UserID = $this->read("id");}
        if (!$Sidebar && isset($Permissions["sidebar"])){$Sidebar = $this->loadpermissions($UserID, "sidebar");}
        if (!$Blocks && isset($Permissions["blocks"])){$Blocks = $this->loadpermissions($UserID, "blocks");}
        $Permissions["sidebar_actual"] = $Sidebar;
        $Permissions["blocks_actual"] = $Blocks;
        $this->set("permissions", $Permissions);
    }

    //////////////////////////profile API//////////////////////////////////////////
    function read($Key){
        return $this->Controller->request->session()->read('Profile.' . $Key);
    }
    function set($Key, $Value){
        $this->Controller->set($Key, $Value);
    }

    public function get_profile($UserID = false){
        if(!$UserID){$UserID=$this->read("id");}
        return $this->get_entry("profiles", $UserID, "id");
    }

    function getfirstsuper(){
        return TableRegistry::get('profiles')->find()->where(['super'=>1])->first();
    }

    function profile_to_array($ID, $JSON = false, $Pretty = false){
        $Profile = $this->get_entry("profiles", $ID, "id");
        //if(!strpos($Profile->otherinfo, ":")) {$Profile->otherinfo = $this->AppName() . ":" . $ID;}
        $Sidebar = $this->loadpermissions($ID, "sidebar");
        $Blocks = $this->loadpermissions($ID, "blocks");
        $Type = $this->get_entry("profile_types", $Profile->profile_type, "id");
        $Data = array("Datatype" => "Profile", "Profile" => $this->properties_to_array($Profile), "Sidebar" => $this->properties_to_array($Sidebar), "Blocks" => $this->properties_to_array($Blocks), "Type" => $this->properties_to_array($Type));
        if ($JSON) {
            $JSON=false;
            if($Pretty){$JSON=JSON_PRETTY_PRINT;}
            return json_encode($Data, $JSON);
        }
        return $Data;
    }

    function enum_profiles(){
        return $this->enum_all("profiles");
    }

    function json_to_profile($Data){
        if(!is_array($Data)) {$Data = json_decode($Data, true);}
        if($Data["Datatype"] != "Profile"){return false;}

        $Profile = $Data["Profile"];
        if($Profile["email"]) {
            $Profile2 = $this->get_entry("profiles", $Profile["email"], "email");
        }
        if ($Profile2) {return $Profile2->id;}//is a local profile that exists
        //is a remote profile that does not exist, create it
        unset($Profile["id"]);
        $Type = $Data["Type"];
        $Profile["profile_type"] = $this->json_to_profile_type($Type);
        $NewID = $this->new_entry("profiles", "id", $Profile);
        $Blocks = $Data["Blocks"];
        $Blocks["user_id"] = $NewID;//substitute with new id
        $Sidebar = $Data["Sidebar"];
        $Sidebar["user_id"] = $NewID;//substitute with new id
        $this->new_entry("blocks", "id", $Blocks);
        $this->new_entry("sidebar", "id", $Sidebar);
        return $NewID;
    }
    function json_to_profile_type($Data){
        if(!is_array($Data)) {$Data = json_decode($Data, true);}
        $Ptype = $this->get_entry("profile_types", $Data["title"], "title");
        if($Ptype){return $Ptype->id;}//profile type exists, use it
        unset($Data["id"]);
        $Data = $this->new_entry("profile_types", "id", $Data);
        return $Data["id"];
    }

    public function find_client($UserID="", $LimitToOne = true){
        
        if(!$UserID){$UserID = $this->read("id");}
        if(!$UserID){return 0;}
        $clients = TableRegistry::get("clients")->find()->select('id')->where(['profile_id LIKE "'.$UserID.',%" OR profile_id LIKE "%,'.$UserID.',%" OR profile_id LIKE "%,'.$UserID.'" OR profile_id ="'.$UserID.'"']);
        if (iterator_count($clients) == 1 || $LimitToOne) {
            $clients = $clients->first();
            if($clients) {
                return $clients->id;
            }
        } else if (iterator_count($clients) > 1) {
            $Data = array();
            foreach($clients as $client){
                $Data[] = $client->id;
            }
            return $Data;
        }
        else
            return 0;
    }



    //////////////////////////////////profile type API/////////////////////////////////
    function enum_profile_types(){
        return $this->enum_table("profile_types");
    }


    /////////////////////////////////document/order API////////////////////////////////
    function load_document($ID, $ReturnSubDoc = false){
        $Doc = $this->get_entry("documents", $ID, "id");
        if($ReturnSubDoc && $Doc){
            $Table = $this->load_subdoc_type($Doc->sub_doc_id)->table_name;
            if($Doc->order_id){
                $Subdoc = $this->get_entry($Table, $Doc->order_id, "order_id");
            } else {
                $Subdoc = $this->get_entry($Table, $ID, "document_id");
            }
            return $Subdoc;
        }
        return $Doc;
    }

    function get_document_id($OrderID, $SubDoc){
        $Temp = $this->enum_all("documents", array("order_id" => $OrderID, "sub_doc_id" => $SubDoc))->first();
        if(!$Temp){return false;}
        return $Temp->id;
    }

    function get_order_id($DocumentID){
        return $this->get_entry("documents", $DocumentID, "id")->order_id;
    }

    function load_subdoc_type($ID){
        //title, display, form, table_name, orders, color_id, titleFrench, ProductID, icon
        if (is_numeric($ID)) {$Key = "id";} else {$Key = "table_name";}
        return $this->get_entry("subdocuments", $ID, $Key);
    }

    function remove_empties($Data){
        foreach($Data as $Key => $Value){
            if(!$Value){
                unset($Data[$Key]);
            } else if(is_array($Value)){
                $Data[$Key] = $this->remove_empties($Value);
            }
        }
        return $Data;
    }


    function Signatures($sub_doc_id){
        switch ($sub_doc_id) {
            case 4: //consent form
                return array("signature_company_witness" => "canvas", "criminal_signature_applicant" => "canvas", "criminal_signature_applicant2" => "canvas", "signature_company_witness2" => "canvas");
                break;
            case 15://upload id/attachements
                return array("id_piece1" => "attachments", "id_piece2" => "attachments", "driver_record_abstract" => "attachments", "cvor" => "attachments", "resume" => "attachments", "certification" => "attachments");
                break;
        }
    }

    function enum_orders(){
        return $this->enum_all("orders");
    }

    function base64_to_html($JSON, $End = '"'){
        return $JSON;

        if (strpos($JSON, 'data:image\/')) {
            $JSON = str_replace('data:image\/', '<IMG SRC="data:image/', $JSON);
        } else {
            $JSON = str_replace('data:image', '<IMG SRC="data:image', $JSON);
        }

        $pos2=0;
        while($pos = strpos($JSON, "data:image", $pos2)){
            $pos2 = strpos($JSON, $End, $pos);
            $JSON = $this->left($JSON, $pos2) . '">' . $this->right($JSON, strlen($JSON) - $pos2);
        }
        return $JSON;
    }
    function json_to_html($JSON){
        return "<PRE>" . $JSON . "</PRE>";
        //return "<PRE>" . $this->base64_to_html($JSON) . "</PRE>";
    }

    function order_to_json($ID, $OnlyIfForms="", $Pretty = true){
        $Order = $this->load_order($ID, true, true, $OnlyIfForms);
        if ($Pretty) {return json_encode($Order, JSON_PRETTY_PRINT);}
        return json_encode($Order);
    }

    function make_order_path($Order){
        if(!is_object($Order)){
            $Order = $this->get_entry("orders", $Order, "id");
        }
        //http://localhost/veritas3-0/orders/vieworder/CLIENT_ID/ORDER_ID?order_type=TYPE&forms=FORMS
        $EDITURL = $this->Controller->request->webroot . "orders/addorder/" . $Order->client_id . "/" . $Order->id;
        if ($Order->order_type) {
            $EDITURL.= '?order_type=' . urlencode($Order->order_type);
            if ($Order->forms) { $EDITURL.= '&forms=' . $Order->forms; }
            if ($Order->division) { $EDITURL.= '&division=' . $Order->division; }
        }
        return $EDITURL;
    }

    function underscore2Camelcase($str) {
        $words = explode('_', strtolower($str));
        foreach ($words as $Key => $word) {
            $words[$Key] = ucfirst(trim($word));
        }
        return implode(" ", $words);
    }

    function key_implode(&$array, $glueLine, $glueKVP, $FormatKeys = false, $RemoveIfContains = "",$RemoveClientID=false) {
        $result = array();
        foreach ($array as $key => $value) {
            $DOIT = !($RemoveClientID && strtolower($key) == 'client_id');
            if ($DOIT && $FormatKeys) {$key = $this->underscore2Camelcase($key);}
            if ($DOIT && $RemoveIfContains) {$DOIT = strpos($value, $RemoveIfContains) === false;}
            if ($DOIT) {$result[] = $key . $glueKVP . $value;}
        }
        return implode($glueLine, $result);
    }


    function sendorder($OrderID, $EmailAddress = ""){
        $order_info = $this->get_entry("orders", $OrderID);
        $JSON = $this->order_to_email($OrderID);
        if($EmailAddress) {
            $Data = array("email" => $EmailAddress, "username" => "Test supreme", "profile_type" => "ADMIN", "company_name" => "Trinoweb", "for" => "test", "html" => $JSON, 'path' => LOGIN . 'profiles/view/' . $order_info->uploaded_for);
            $this->handleevent("ordercompleted", $Data);//$order_info
        }
        return $JSON;
    }

    function handleevent($EventName, $Data, $Email=""){
        $this->Controller->loadComponent("Mailer");
        $this->Controller->Mailer->handleevent($EventName, $Data, $Email);
    }

    function order_to_email($OrderID, $IDs){
        $Order = $this->load_order($OrderID, true, true);
        $Details = array();

        //HEADER
        $Header = array();
        $Header["Created by"] =  $Order->Header["user_id"]["Profile"]["fname"] . " " . $Order->Header["user_id"]["Profile"]["lname"];
        $Header["Created by ID"] =  $Order->Header["user_id"]["Profile"]["id"];
        $Header["Uploaded for"] =  $Order->Header["uploaded_for"]["Profile"]["fname"] . " " . $Order->Header["uploaded_for"]["Profile"]["mname"] . " " . $Order->Header["uploaded_for"]["Profile"]["lname"];
        $Header["Uploaded for ID"] =  $Order->Header["uploaded_for"]["Profile"]["id"];
        $Header["Company Name"] =  $Order->Header["client_id"]["Client"]["company_name"];
        $Header["Date"] = $Order->Header["created"];
        $Header["Link"] = '<A HREF="' . LOGIN . 'profiles/view/' . $Order->Header["uploaded_for"]["Profile"]["id"] . '?getprofilescore=1">Click here to view the scorecard</A>';
        $Details["Header:"] = $Header;

        //PRODUCTS
        $Header = array();
        $Forms = explode(",", $Order->Header["forms"]);
        $Products = $this->enum_all("order_products");
        //$arr_return_no = array(1 => 'ins_1', 14 => 'ins_14', 32 => 'ins_32', 72 => 'ins_72', 77 => 'ins_77', 78 => 'ins_78', 1603 => 'ebs_1603', 1627 => 'ebs_1627', 1650 => 'ebs_1650');
        foreach($Forms as $ProductNumber){
            $Product = $this->getIterator($Products, "number", $ProductNumber);
            $ID = '<FONT COLOR="RED">[ID NOT FOUND]</FONT>';
            /*if (isset($Order->Header[ $arr_return_no[$ProductNumber] ])){
                $ID = $Order->Header[ $arr_return_no[$ProductNumber] ];
            }*/
            if (isset($IDs[$ProductNumber])){
                $ID = $IDs[$ProductNumber];
            }
            $Header[$Product->title] = $ID;
        }
        $Details["Products:"] = $Header;

        //FORMS
        $ID = 1;
        foreach($Order->Forms as $Form){
            unset($Form->Data["id"]);
            unset($Form->Data["order_id"]);
            unset($Form->Data["user_id"]);
            if(count($Form->Data)) {
                $Details["Product Details " . $ID. ":<BR>(" . $Form->Header["document_type"] . ")"] = $Form->Data;
                $ID++;
            }
        }

        //convert array to HTML
        $HTML = '<TABLE border=1 cellspacing=0>';
        foreach($Details as $Name => $Values){
            $HTML .= '<TR><TD valign="top" rowspan="' . count($Values) . '">' . $Name . '</TD>';
            $TR = "<TD>";
            foreach($Values as $Key => $Value){
                if(is_numeric($Key)){$Key = $Value; $Value = "";}
                $HTML .= $TR . $this->underscore2Camelcase($Key) . '</TD><TD>' . $Value . '</TD></TR>';
                $TR = "<TR><TD>";
            }
        }
        return $HTML . '</TABLE>';
    }

    function toLink($Filename){
        if($Filename) {
            $type = strtolower(pathinfo($Filename, PATHINFO_EXTENSION));
            $name = pathinfo($Filename, PATHINFO_BASENAME);
            switch ($type) {
                /*
                case "jpg": case "jpeg": case "gif": case "png": case "bmp":
                    return '<IMG SRC="' . $Filename . '" ALT="' . $name . '">';
                    break;
                */
                default:
                    return '<A HREF="' . $Filename . '">' . $name . '</A>';
            }
        } else {
            return "[NO DATA]";
        }
    }

    function load_order($ID, $GetFiles = false, $RemoveEmpties = true, $forms = "", $AsBase64 = false){
        //loads an order in to a single variable, includes the documents, profiles, profile types, client, divisions
        //creating each of which if they do not exist already, except for document types which cannot be soft-created
        $Header = $this->get_entry("orders", $ID, "id");
        if(!$Header){return false;}
        $Header = $this->getProtectedValue($Header, "_properties");
        if($forms){
            if (!is_array($forms)){$forms = explode(",", $forms);}
            $FormsToCheck = explode(",", $Header["forms"]);
            $DoIt = false;
            foreach($forms as $form){
                if (in_array($form, $FormsToCheck)){
                    $DoIt = true;
                    break;
                }
            }
            if(!$DoIt){return false;}
        }
        if($GetFiles) {
            if($AsBase64){
                $Header["recruiter_signature"] = $this->base_64_file("webroot/canvas/" . $Header["recruiter_signature"]);
            } else {
                $Header["recruiter_signature"] = $this->toLink(LOGIN . "canvas/" . $Header["recruiter_signature"]);
            }
        }

        //profile type
        $Header["user_id"] = $this->profile_to_array($Header["user_id"]);
        $Header["uploaded_for"] = $this->profile_to_array($Header["uploaded_for"]);
        $Header["division"] = $this->get_division($Header["division"]);
        if($Header["division"]) {$Header["division"] = $Header["division"]->title;}
        $Header["client_id"] = $this->client_to_array($Header["client_id"]);
        if($RemoveEmpties){$Header=$this->remove_empties($Header);}

        $Order = (object) array("Datatype" => "Order", "Header" => $Header);
        $Forms = $this->enum_all("documents", array("order_id" => $ID));
        $Order->Forms = array();
        foreach($Forms as $Form){
            $Table = $this->load_subdoc_type($Form->sub_doc_id);
            if($Table) {
                $Form = (array) $Form;
                $Form = $this->getProtectedValue($Form, "_properties");
                $theForms = $this->enum_all($Table->table_name, array("order_id" => $ID));
                foreach($theForms as $Data) {
                    //$Data = $this->get_entry($Table->table_name, $ID, "order_id");
                    $Data = $this->getProtectedValue($Data, "_properties");
                    if ($RemoveEmpties) {
                        $Form = $this->remove_empties($Form);
                        $Data = $this->remove_empties($Data);
                    }
                    if ($GetFiles) {
                        $theFiles = $this->Signatures($Form["sub_doc_id"]);
                        if (is_array($theFiles)) {
                            foreach ($theFiles as $File => $Dir) {
                                if(isset($Data[$File])) {
                                    if ($AsBase64) {
                                        $Data[$File] = $this->base_64_file("webroot/" . $Dir . "/" . $Data[$File]);
                                    } else {
                                        $Data[$File] = $this->toLink(LOGIN . $Dir . "/" . $Data[$File]);
                                    }
                                }
                            }
                        }
                    }
                    $Form["sub_doc_id"] = $this->load_subdoc_type($Form["sub_doc_id"])->table_name;
                    $Data = (object) array("Header" => $Form, "Data" => $Data);
                    $Order->Forms[] = $Data;
                    $Form["Duplicate"] = true;
                }
            }
        }
        return $Order;
    }

    function webroot($removeslashes = false){
        $webroot = $this->Controller->request->webroot;
        if($removeslashes){$webroot = str_replace('/', '', $webroot);}
        return $webroot;
    }

    function json_to_order($Data, $ReturnAll=false){
        if (is_object($Data)) {
            $Data = (array) $Data;
        }else{
            $Data = json_decode($Data, true);
        }

        if ($Data["Datatype"] != "Order"){ return false;}

        $Header = $Data["Header"];
        $Forms = $Data["Forms"];
        $Dir = "webroot/canvas";

        $User_ID = $this->json_to_profile($Header["user_id"]);
        $Header["user_id"] = $User_ID;

        $Uploaded_for = $this->json_to_profile($Header["uploaded_for"]);
        $Header["uploaded_for"] = $Uploaded_for;

        $Client_ID = $this->json_to_client($Header["client_id"]);
        $Header["client_id"] = $Client_ID;

        $Header["division"] = $this->auto_division($Header["client_id"], $Header["division"]);
        if (isset($Header["recruiter_signature"])){$Header["recruiter_signature"] = $this->unbase_64_file($Header["recruiter_signature"], $Dir);}
        $Order_ID = $this->construct_order($Header);

        $Data = array("OrderID" => $Order_ID);
        $DocumentID=0;
        foreach($Forms as $Form){
            $DocumentID = $this->construct_document($Form, $Order_ID, $User_ID, $Client_ID, $Uploaded_for, $Dir, $DocumentID);
            $DocumentID = $this->construct_document($Form, $Order_ID, $User_ID, $Client_ID, $Uploaded_for, $Dir, $DocumentID);
            $Data[] = $DocumentID;
        }
        if($ReturnAll){return $Data;}
        return $Order_ID;
    }

    function construct_order($Header){
        unset($Header["id"]);
        return $this->new_entry("orders", "id", $Header)["id"];
    }
    function construct_document($Form, $Order_ID, $User_ID, $Client_ID, $Uploaded_for, $Dir, $OldDocumentID=0){
        $Header =  $Form["Header"];
        $Data = $Form["Data"];

        unset($Header["id"]);
        $Header["order_id"] = $Order_ID;
        $Header["user_id"] = $User_ID;
        $Header["client_id"] = $Client_ID;
        $Header["uploaded_for"] = $Uploaded_for;
        $sub_doc_type = $this->load_subdoc_type($Header["sub_doc_id"]);
        $Header["sub_doc_id"] = $sub_doc_type->id;

        $Signatures = $this->Signatures($Header["sub_doc_id"]);
        if (is_array($Signatures)){
            foreach($Signatures as $File){
                if (isset($Data[$File])){
                    $Data[$File] = $this->unbase_64_file($Data[$File], $Dir);
                }
            }
        }

        unset($Data["id"]);
        $Data["order_id"] = $Order_ID;
        $Data["client_id"] = $Client_ID;
        $Data["user_id"] = $User_ID;

        $DocumentID = $this->new_entry("documents", "id", $Header);
        $this->new_entry($sub_doc_type->table_name, "id", $Data);
        return $DocumentID;
    }

    function randomtext($Length=8) {
        $alphabet = "0123456789";
        $pass = "";
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $Length; $i++) {
            $n = rand(0, $alphaLength);
            $pass .= $alphabet[$n];
        }
        return $pass;
    }
    function unbase_64_file($Data, $Path, $Filename = ""){
        $Comma = strpos($Data, ",");//chop off "data:image/EXT;base64,"
        if($Comma) {
            $Header = $this->left($Data, $Comma);
            $Data = base64_decode($this->right($Data, strlen($Data) - $Comma - 1));
            $Path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . $this->Controller->request->webroot . $Path);
            if (!$Filename) {
                $Type = str_replace(";base64", "", str_replace("data:image/", "", $Header));
                $Filename = $this->randomtext(10) . "_" . $this->randomtext(10) . "." . $Type;
                while (file_exists($Path . "/" . $Filename)) {
                    $Filename = $this->randomtext(10) . "_" . $this->randomtext(10) . "." . $Type;
                }
            }

            $Path = str_replace("//", "/", $Path . "/" . $Filename);
            file_put_contents($Path, $Data);
            return $Filename;
        }
        return $Data;
    }
    function base_64_file($path){
        $type = pathinfo($path, PATHINFO_EXTENSION);
        if($type == "jpeg"){$type = "jpg";}
        $path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . $this->Controller->request->webroot . $path);
        if (file_exists($path) && !is_dir($path)) {
            $data = file_get_contents($path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        return "";
    }


    /////////////////////////////////////client API///////////////////////////
    function get_division($DivisionID){
        return $this->get_entry("client_divison", $DivisionID, "id");
    }
    function get_client_division($ClientID, $DivisionName){
        return $this->enum_all("client_divison", array("client_id" => $ClientID, "title" => $DivisionName))->first();
    }
    function get_divisions($ClientID){
        return $this->table_to_array("client_divison", array("client_id" => $ClientID), "id", "title");
    }
    function client_to_array($ClientID){
        $Client = $this->properties_to_array($this->get_entry("clients", $ClientID));
        $Products = $this->table_to_array("client_products", array("ClientID" => $ClientID), "ID", "ProductNumber");
        return array("Client" => $Client, "Products" => $Products);
    }
    function json_to_client($Data){
        if(!is_array($Data)) {$Data = json_decode($Data, true);}
        $Client = $Data["Client"];
        $Products = $Data["Products"];

        $Client2 = $this->get_entry("clients", $Client["company_name"], "company_name");
        if($Client2){return $Client2->id;}//is a local company, return the ID

        //is not a local company, create it from the data
        $Client= $this->unset_multi($Client, array("id", "profile_id", "image", "requalify_product"));
        $ID = $this->new_entry("clients", "id", $Client);
        $Divisions = explode("\r\n", $Client["division"]);
        $this->new_division($ID, $Divisions, false);
        foreach($Products as $Product){
            $this->new_entry("client_products", "ID", array("ClientID" => $ID, "ProductNumber" => $Product));
        }
        return $ID;
    }

    function unset_multi($Array, $Unsets){
        foreach($Unsets as $Name){
            unset($Array[$Name]);
        }
        return $Array;
    }
    function auto_division($ClientID, $DivisionName){
        $Division = $this->get_client_division($ClientID, $DivisionName);
        if($Division){return $Division->id;}
        return $this->new_division($ClientID, $DivisionName);
    }
    function new_division($ClientID, $DivisionName, $AppendToClient = true){
        if(is_array($DivisionName)){
            foreach($DivisionName as $Division){
                $this->new_division($ClientID, $Division, $AppendToClient);
            }
        } else if ($DivisionName) {
            if($AppendToClient) {
                $Client = $this->get_client($ClientID);
                $Divisions = $this->appendstring($Client->division, $DivisionName, "\r\n");
                $this->edit_database("clients", "id", $ClientID, array("division" => $Divisions));
            }
            return $this->new_entry("client_divison", "id", array("client_id" => $ClientID, "title" => $DivisionName));
        }
    }
    function get_client($ClientID){
        return $this->get_entry("clients", $ClientID, "id");
    }

    function assign_profile_to_client($ProfileID, $ClientID){
        $Client = $this->get_entry('clients', $ClientID, "id");
        $Profiles = $this->appendstring($Client->profile_id, $ProfileID);
        $this->update_database("clients", "id", $ClientID, array("profile_id" => $Profiles));
    }


    ///////////////////////////////////////JSON API//////////////////////////////
    function table_to_array($Table, $Conditions, $KeyColumn, $ValueColumn){
        return $this->iterator_to_array($this->enum_all($Table, $Conditions), $KeyColumn, $ValueColumn);
    }

    function table_to_json($table, $conditions, $Pretty = false){
        $columns = $this->getColumnNames($table, "", false);
        $Data = $this->enum_all($table, $conditions)->first();
        if($Data) {
            foreach ($columns as $Name => $Value) {
                $columns[$Name] = $Data->$Name;
            }
        }
        if ($Pretty) {return json_encode($columns, JSON_PRETTY_PRINT);}
        return json_encode($columns);
    }

    function table_to_schema($table, $Pretty = false){
        $dataCols = array();
        $dataCols['title'] = $_GET["table"];
        $dataCols['description'] = "Automated table schema";
        $dataCols['type'] = "object";
        $columns = $this->getColumnNames($table, "", false);
        $dataProps = array();
        $required_fields = $this->required_fields($table);
        foreach($columns as $Name => $Data){
            $dataProps[$Name] = $Data;
        }
        $dataCols['items'] = $dataProps;
        if($required_fields){$dataCols['required'] = $required_fields;}
        if ($Pretty) {return json_encode($dataCols, JSON_PRETTY_PRINT);}
        return json_encode($dataCols);
    }

    function required_fields($table){
        $required = "";
        switch($table){
            case "profiles":
                $required = array("username");
        }
        return $required;
    }

    function object_to_array($object){
        return (array) $object;
    }
    function properties_to_array($object){
        return $this->getProtectedValue($object, "_properties");
    }

    function verify_data($Schema, $Data, $TestMode = false){
        $Schema = json_decode($Schema);
        $Data = json_decode($Data);
        $required_fields = "";
        if (isset($Schema->required)) {$required_fields = $Schema->required;}
        $items = (array) $Schema->items;
        foreach ($items as $ColName => $ColData){
            $required=false;
            if($required_fields) {$required = in_array($ColName, $required_fields);}
            $Value = $Data->$ColName;
            if($required && !$Value){
                if($TestMode) {return $ColName . " failed required field";}
                return false;
            }
            switch ($ColData->type){
                case "integer":
                    if(!is_numeric($Value)){$Fail = true;}
                    break;
                case "text":
                    if(!is_string($Value)){$Fail = true;}
                    break;
                case "boolean":
                    if (!is_bool($Value)) {$Fail = true;}
                    break;
                case "string":
                    if(!is_string($Value)){$Fail = true;}
                    if(!strlen($Value) > $ColData->length) {$Fail = true;}
                    break;
            }
            if (isset($Fail)){
                if($TestMode) {return $ColName . " failed " . $ColData->type . " (" . $Value . ")";}
                return false;
            }
        }
        return true;
    }


    /////////////////////////////////DATABASE API///////////////////////////////////
    function now(){
        return date('Y-m-d H:i:s');
    }

    function paginate($Data){
        return $this->Controller->paginate($Data);
    }

    function enum_tables(){
        //$tables = ConnectionManager::getDataSource('default')->listSources();//cake 2
        $tables = ConnectionManager::get('default')->schemaCollection()->listTables();//cake 3
        return $tables;
    }

    function cacheprofiles($Profiles, $Table = false, $ForceMethod2 = false){
        if (is_array($Profiles)) {
            $Profiles = array_keys($Profiles);
        } else {
            $Profiles = explode(",", $Profiles);
        }
        if(!$Table){$Table = "profiles";}
        $Profiles = implode(",", $Profiles);
        if($Profiles) {
            $Profiles = $this->enum_all($Table, "id IN (" . $Profiles . ")");
        }
        return $Profiles;
    }

    function delete_all($Table, $conditions){
        TableRegistry::get($Table)->deleteAll($conditions, false);
    }
    function enum_table($Table, $SortBy = false, $Direction = "ASC"){
        if($SortBy){
            return TableRegistry::get($Table)->find('all')->order([$SortBy => $Direction]);
        }
        return TableRegistry::get($Table)->find('all');
    }
    function enum_all($Table, $conditions = "", $SortBy = false, $Direction = "ASC"){
        if($conditions && !is_array($conditions)){$conditions = array($conditions);}
        if (is_array($conditions)) {
            if($SortBy){
                return TableRegistry::get($Table)->find('all')->where($conditions)->order([$SortBy => $Direction]);
            }
            return TableRegistry::get($Table)->find('all')->where($conditions);
        }
        return $this->enum_table($Table, $SortBy, $Direction);
    }

    function iterator_to_array($entries, $PrimaryKey="", $Key="", $GetProperties=false, $Reverse = false){
        $data = array();
        foreach($entries as $item){
            if($Key) {
                //if (is_object($item)){
                    $data[$item->$PrimaryKey] = $item->$Key;
                //} else if (is_array($item)){
                //    $data[$item->$PrimaryKey] = $item[$Key];
                //}
            } else {
                if($GetProperties){
                    $value = $this->getProtectedValue($item, "_properties");
                } else {
                    $value = $item;
                }
                if($PrimaryKey){
                    $ID = $value[$PrimaryKey];
                    unset($value[$PrimaryKey]);
                    $data[$ID] = $value;
                } else {
                    $data[] = $value;
                }
            }
        }
        if($PrimaryKey){$PrimaryKey=true;}
        if($Reverse){return array_reverse($data, $PrimaryKey);}
        return $data;
    }

    function enum_anything($Table, $Key, $Value){
        return TableRegistry::get($Table)->find('all')->where([$Key=>$Value]);
    }
    function new_anything($Table, $Name, $PrimaryKey = "ID"){
        $Name = $this->new_entry($Table, $PrimaryKey, array("Name" => $Name));
        return $Name[$PrimaryKey];
    }

    function get_entry($Table, $Value, $PrimaryKey = "id"){
        $table = TableRegistry::get($Table);
        return $table->find()->where([$PrimaryKey=>$Value])->first();
    }

    //only use when you know the primary key value exists
    function update_database($Table, $PrimaryKey, $Value, $Data = false, $CheckColumns = false){
        if($CheckColumns){$Data = $this->matchcolumns($Table, $Data);}
        if(is_array($PrimaryKey)){
            TableRegistry::get($Table)->query()->update()->set($Value)->where($PrimaryKey)->execute();
        } else {
            TableRegistry::get($Table)->query()->update()->set($Data)->where([$PrimaryKey => $Value])->execute();
            $Data[$PrimaryKey] = $Value;
        }
        return $Data;
    }

    function get_row_count($Table, $Conditions = ""){
        $Table = TableRegistry::get($Table);
        if($Conditions) {
            return $Table->find('all')->where($Conditions)->count();
        } else {
            return $Table->find('all')->count();
        }
    }

    function edit_database($Table, $PrimaryKey, $Value, $Data){
        $table = TableRegistry::get($Table);
        $entry = false;
        if($PrimaryKey && $Value) {
            $entry = $table->find()->where([$PrimaryKey => $Value])->first();
        }
        if($entry){
            $table->query()->update()->set($Data)->where([$PrimaryKey => $Value])->execute();
            $Data[$PrimaryKey] = $Value;
        } else {
            $Data2 = $table->newEntity($this->remove_empties($Data));
            $table->save($Data2);
            if($PrimaryKey){
                $Data[$PrimaryKey] = $Data2->$PrimaryKey;
            }
        }
        return $Data;
    }

    function new_entry($Table, $PrimaryKey, $Data){
        return $this->edit_database($Table, $PrimaryKey, "", $Data);
    }

    function lastQuery(){
        $dbo = $this->getDatasource();
        $logs = $dbo->_queriesLog;
        // return the first element of the last array (i.e. the last query)
        return current(end($logs));
    }

    function getColumnNames($Table, $ignore = "", $keysonly = true){
        $Columns = TableRegistry::get($Table)->schema();
        $Data = $this->getProtectedValue($Columns, "_columns");
        if ($Data) {
            if (is_array($ignore)) {
                foreach ($ignore as $value) {
                    unset($Data[$value]);
                }
            } elseif ($ignore) {
                unset($Data[$ignore]);
            }
            if ($keysonly){
                $Data = array_keys($Data);
            }
            return $Data;
        }
    }

    function appendstring($Current, $Append, $delimeter = ","){
        if($Current){return $Current . $delimeter . $Append;}
        return $Append;
    }

    function getProtectedValue($obj,$name) {
        $array = (array)$obj;
        $prefix = chr(0).'*'.chr(0);
        if (isset($array[$prefix.$name])) {
            return $array[$prefix . $name];
        }
    }

    function debugall($iteratable){
        //debug($iteratable);
        foreach($iteratable as $item){
            debug($item);
        }
    }

    function debugprint($text){
        $path = "royslog.txt";
        $dashes = "----------------------------------------------------------------------------------------------\r\n";
        if(is_array($text)){$text = print_r($text,true);}
        file_put_contents($path, $dashes . str_replace("%dashes%", $dashes, str_replace("<BR>", "\r\n" , $text)) . "\r\n", FILE_APPEND);
    }

    function kill_non_numeric($text, $allowmore = ""){
        return preg_replace("/[^0-9" . $allowmore . "]/", "", $text);
    }
    function left($text, $length){
        return substr($text,0,$length);
    }
    function right($text, $length){
        return substr($text, -$length);
    }
    function mid($text, $start, $length){
        return substr($text,$start, $length);
    }

    function getside($text, $delimeter, $Left = true){
        $text = explode($delimeter, $text);
        if ($Left) { return $text[0];}
        return $text[1];
    }

    function getIterator($Objects, $Fieldname, $Value){
        foreach($Objects as $Object){
            if ($Object->$Fieldname == $Value){
                return $Object;
            }
        }
        return false;
    }

    function array_to_object($Array){
        $object = (object) $Array;
        return $object;
    }

    //accepts a table name, or the pure (false) column names array
    function get_primary_key($Table){
        if (is_string($Table)){
            $Table = $this->getColumnNames($Table, "", false);
        }
        if (is_array($Table)){
            foreach($Table as $Key => $Value){
                if(isset($Value['autoIncrement'])){
                    return $Key;
                }
            }
        }
    }

    function get($Key, $Default = ""){
        if (isset($_POST[$Key])){ return $_POST[$Key]; }
        if (isset($_GET[$Key])){ return $_GET[$Key]; }
        return $Default;
    }


    ////////////////////////////////////////////COMMS//////////////////////////////////////////////////
    function AppName(){
        return  $_SERVER["SERVER_NAME"];
    }
    function ScriptName(){
        return "Veritas 3-0";
    }


    function isJson($string) {
        if($string && !is_array($string)){
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        }
    }

    function cURL($URL, $data = "", $datatype = "application/x-www-form-urlencoded;charset=UTF-8", $username = "", $password = ""){
        $session = curl_init($URL);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);//not in post production
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_POST, true);
        if($data) { curl_setopt ($session, CURLOPT_POSTFIELDS, $data);}
        if($this->isJson($data)){$datatype  = "application/json";}
        $header = array('Content-type: ' . $datatype, "User-Agent: " . $this->AppName());
        if ($username && $password){
            $header[] =	"Authorization: Basic " . base64_encode($username . ":" . $password);
        } else if ($username) {
            $header[] =	"Authorization: Bearer " .  $username;
            $header[] =	"Accept-Encoding: gzip";
        } else if ($password) {
            $header[] =	"Authorization: AccessKey " .  $password;
        }
        curl_setopt($session, CURLOPT_HTTPHEADER, $header);

        $response = curl_exec($session);
        if(curl_errno($session)){
            $response = "Error: " . curl_error($session);
        }
        curl_close($session);
        return $response;
    }

    function is_a_date($Date, $Formats = "m/d/Y"){
        if(!is_array($Formats)){$Formats = array($Formats);}
        foreach ($Formats as $Format) {
            $date = DateTime::createFromFormat($Format, $Date);
            if($date == False){return false;}
        }
        return true;
    }

    function validate_data($Data, $DataType){
        if(is_array($Data) && is_array($DataType)){
            foreach($DataType as $Key => $Type){
                if(isset($Data[$Key]) && $Data[$Key] && !is_array($Data[$Key])){
                    $Value = $this->validate_data($Data[$Key], $Type);
                    if($Value){
                        $Data[$Key] = $Value;//cleaned value
                    } else {
                        return $Key . " (" . $Data[$Key] . ") is not a valid " . $Type;
                    }
                }
            }
            return $Data;
        } else if (is_array($DataType)){
            return in_array($Data, $DataType);
        }
        switch(strtolower($DataType)) {
            case "number":
                return preg_replace("/[^0-9,.]/", "", $Data);
                break;
            case "alphabetic":
                return preg_replace("/[^a-zA-Z]/" ,"", $Data);
                break;
            case "alphanumeric":
                return preg_replace("/[^[:alnum:][:space:]]/ui" ,"", $Data);
                break;

            //http://php.net/manual/en/filter.filters.validate.php FILTER_VALIDATE_BOOLEAN FILTER_VALIDATE_FLOAT FILTER_VALIDATE_INT FILTER_VALIDATE_REGEXP
            case "ip":
                if (filter_var($Data, FILTER_VALIDATE_IP)) {return $Data;}
                break;
            case "mac":
                if (filter_var($Data, FILTER_VALIDATE_MAC)) {return $Data;}
                break;
            case "url":
                if (filter_var($Data, FILTER_VALIDATE_URL)) {return $Data;}
                break;
            case "email":
                if (filter_var($Data, FILTER_VALIDATE_EMAIL)){return strtolower(trim($Data));}
                break;
            case "date":
                if($this->is_a_date($Data)){return $Data;}
                break;
            case "required":
                return $Data;
                break;

            case "province":
                if (in_array(strtoupper($Data), ["AB", "BC", "MB", "NB", "NL", "NT", "NS", "NU", "ON", "PE", "QC", "SK", "YT"])){return strtoupper($Data);}
                break;
            case "bool":
                if ($Data == 0 || $Data == 1){return $Data;}
                break;
            case "base64file":
                if( strpos($Data, "data:image/") !== false && strpos($Data, ";base64,") !== false){return $Data;}
                break;
            case "csv":
                $EXP = explode(",", $Data);
                foreach($EXP as $Cell){
                    if(!is_numeric($Cell)){return "";}
                }
                return $Data;
                break;
            case "md5":
                if($this->isValidMd5($Data)){return $Data;}
                break;

            case "postalcode":
                if ($this->validate_postal_code($Data)) {return $this->clean_postalcode($Data);}
                break;
            case "phone":
                $Data = preg_replace('/[^\d+]/', '', $Data);
                if (strlen($Data) > 6 && strlen($Data) < 12){return $this->format_phone($Data);}
                return $Data;
                break;
            case "sin":
                $Data = $this->validate_data($Data, "number");
                if (strlen($Data) == 9){return $this->left($Data,3) . "-" . $this->mid($Data,3,3) . "-" . $this->right($Data, 3);}
                break;
            case "zipcode":
                $Data = $this->validate_data($Data, "number");
                if (strlen($Data) == 5){return $Data;}
                if (strlen($Data) == 9){return $this->left($Data,5) . "-" . $this->right($Data,4);}
                break;
            case "postalzip":
                $Code = $this->validate_data($Data, "postalcode");
                if($Code){return $Code;}
                $Code = $this->validate_data($Data, "zipcode");
                if($Code){return $Code;}
                break;
            default:
                return $DataType . ' not supported';
        }
        return "";
    }

    function isValidMd5($md5 ='') {
        return preg_match('/^[a-f0-9]{32}$/', $md5);
    }

    function clean_postalcode($PostalCode){
        $PostalCode = strtoupper($this->validate_data($PostalCode, "alphanumeric"));
        if($this->validate_postal_code($PostalCode)){
            $delimeter = " ";
            return $this->left($PostalCode, 3) . $delimeter . $this->right($PostalCode, 3);
        }
    }

    function validate_postal_code($PostalCode)  {//function by Roshan Bhattara(http://roshanbh.com.np)
        return preg_match("/^([a-ceghj-npr-tv-z]){1}[0-9]{1}[a-ceghj-npr-tv-z]{1}[0-9]{1}[a-ceghj-npr-tv-z]{1}[0-9]{1}$/i", str_replace(" ", "", $PostalCode));
    }

    function requiredfields($Data, $Fields = ""){
        if(!is_array($Fields)){
            switch($Fields) {
                case "profile2order":
                    $Fields = array ("fname" => "forms_firstname", "email" => "forms_email", "lname" => "forms_lastname", "profile_type" => "profiles_profiletype", "gender" => "forms_gender",  "driver_province" => "forms_provinceissued", "title" => "forms_title", "placeofbirth" => "forms_placeofbirth", "sin" => "forms_sin", "phone" => "forms_phone", "street" => "forms_address", "city" => "forms_city", "province" => "forms_provincestate", "postal" => "forms_postalcode", "country" => "forms_country", "dob" => "forms_dateofbirth", "driver_license_no" => "forms_driverslicense", "expiry_date" => "forms_expirydate");
                    //if(DATABASE == "ttsao"){ unset($Fields["sin"]);}
                    break;
            }
            if(!is_object($Data)){return $Fields;}
            $Fields= array_keys($Fields);
        }
        if(is_array($Fields)){
            foreach($Fields as $Key){
                if(!isset($Data->$Key) || !$Data->$Key){
                    return $Key;
                }
            }
        }
        return false;
    }

    function format_phone($phone) {
        $phone = $this->validate_data($phone, "number");
        if(!isset($phone{3})) { return ''; }// note: making sure we have something
        $phone = preg_replace("/[^0-9]/", "", $phone);// note: strip out everything but numbers
        $length = strlen($phone);
        switch($length) {
            case 7:
                return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
                break;
            case 8://europe
                return preg_replace('/([0-9]{3})([0-9]{2})([0-9]{3})/', '$1 - $2 $3', $phone);
                break;
            case 9://europe
                return preg_replace('/([0-9]{3})([0-9]{2})([0-9]{2})([0-9]{2})/', '$1 - $2 $3 $4', $phone);
                break;
            case 10:
                return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
                break;
            case 11:
                return preg_replace("/([0-9]{1}) ([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $phone);
                break;
            default:
                return $phone;
                break;
        }
    }

    function copyitems($From, $Items){
        $To = array();
        foreach($Items as $Key => $Value){
            if(is_numeric($Key)) {
                $Item = $Value;
                $Default = "";
            } else {
                $Item = $Key;
                $Default = $Key;
            }
            if (isset($From[$Item])) {
                $To[$Item] = $From[$Item];
            } else if($Default) {
                $To[$Item] = $Default;
            }
        }
        return $To;
    }

    function change_column_comment($Table, $Column, $Comment){
        if(!$Comment){$Comment = "clear";}
        $this->create_column($Table, $Column, "", "", "", false, "", $Comment, $Column);
    }
    function create_column($Table, $Column, $Type, $Length="", $Default ="", $AutoIncrement=false, $Null = false, $Comment = "", $OldColumn = "", $Position = ""){
        $Column= str_replace(" ", "_", $Column);//AFTER `commodity`     FIRST
        $Type=strtoupper($Type);//types can be varchar with a length, INT
        //ALTER TABLE `test` CHANGE `commodity` `commodity` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'test';
        //ALTER TABLE test CHANGE commodity commodity string(255) NOT NULL COMMENT 'test comment'
        if($OldColumn){
            $Columns = $this->getColumnNames($Table, "", false);
            if (!isset($Columns[$OldColumn])){ return false;}
            $Columns = $Columns[$OldColumn];
            if(!$Type)          {$Type = $Columns["type"];}
            if(!$Column)        {$Column = $OldColumn;}
            if($Length == "")   {
                $Length = $Columns["length"];
                if(isset($Columns["precision"])){$Length .= "," . $Columns["precision"];}
            } else if($Length=="clear") {
                $Length == "";
            }
            if($Null == "")     {$Null = $Columns["null"];}         else if($Null=="clear")     {$Null == false;}
            if($Default == "")  {$Null = $Columns["default"];}      else if($Default=="clear")  {$Default == false;}
            $AutoIncrement =    isset($Columns["autoIncrement"]) || $AutoIncrement;//can not be set to false once it has been true
            if($Type == "string" && $Length){$Type = "VARCHAR";}
            $query = "ALTER TABLE " . $Table . " CHANGE " . $OldColumn . " " . $Column . " " . $Type;
        } else {
            $query = "ALTER TABLE " . $Table . " ADD " . $Column . " " . $Type;
        }
        if($Length){$query.="(" . $Length .")";}
        if(!$Null){$query.=" NOT NULL";}
        if($AutoIncrement){$query.=" AUTO_INCREMENT";}
        if($Default){
            $query.=" DEFAULT";
            if (is_numeric($Default)){
                $query.=$Default;
            }else{
                $query.= "'" . $Default . "'";
            }
        }
        if($Comment){$query.= " COMMENT '" . $Comment . "'";}
        if($Position){
            if(strtoupper(trim($Position)) == "FIRST"){
                $query .= " FIRST";
            } else {
                $query .= " AFTER " . $Position;
            }
        }
        $Value =  $this->query($query);
        $this->clear_cache();
        return $Value;
    }

    function query($Query, $CleanCache = false){
        $Query = ConnectionManager::get('default')->query($Query);
        if($CleanCache){$this->clear_cache();}
        return $Query;
    }

    function insert_rows($Table, $Quantity, $AtID, $PrimaryKey=""){
        if(!$PrimaryKey){$PrimaryKey = $this->get_primary_key($Table);}
        if($AtID == 0 || $AtID > $this->get_last_entry($Table, $PrimaryKey)){
            $this->insert_empty_rows($Table, $Quantity);
        } else {
            $Data = $this->enum_all($Table);
            $Count = $Data->count();
            if ($Count < $Quantity) {
                $this->insert_empty_rows($Table, $Quantity - $Count);
                foreach ($Data as $Row) {
                    $this->copy_row($Table, $Row->$PrimaryKey, $PrimaryKey, true, $Row);
                }
            } else {
                $Cells = $this->iterator_to_array($Data, false, false, true, true);
                $NewCells = array_reverse($this->insert_empty_rows($Table, $Quantity));
                foreach ($NewCells as $Index => $ID) {
                    $Cell = $Cells[$Index];
                    $CellID = $Cell[$PrimaryKey];
                    if ($CellID >= $AtID) {
                        unset($Cell[$PrimaryKey]);
                        $this->copy_row($Table, $CellID, $PrimaryKey, true, $Cell, $ID);
                    }
                }
            }
        }
    }

    function copy_table($oldtable, $newtable){
        if ($this->table_exists($oldtable) && !$this->table_exists($newtable)) {
            $this->query("CREATE TABLE " . $newtable . " LIKE " . $oldtable . ";");
            $this->query("INSERT " . $newtable . " SELECT * FROM " . $oldtable . ";");
        }
    }

    function table_exists($Table){
        $Tables = $this->enum_tables();
        return in_array($Table,$Tables);
    }

    function copy_row($Table, $ID, $PrimaryKey="", $BlankOriginal = false, $Data=false, $Into=false){
        if(!$PrimaryKey){$PrimaryKey = $this->get_primary_key($Table);}
        if(!$Data) {$Data = $this->get_entry($Table, $ID, $PrimaryKey);}
        if(isset($Data->$PrimaryKey)) {unset($Data->$PrimaryKey);}
        if (is_object($Data)) {
            $Columns = $this->getProtectedValue($Data, "_properties");
        } else if (is_array($Data)){
            $Columns = $Data;
        }
        if($Into){
            $this->update_database($Table, $PrimaryKey, $Into, $Columns);
        } else {
            $this->new_entry($Table, $PrimaryKey, $Columns);
        }
        if($BlankOriginal){
            foreach($Columns as $ColumnName => $ColumnData){
                $Columns[$ColumnName] = "";
            }
            $this->update_database($Table, $PrimaryKey, $ID, $Columns);
        }
    }

    function get_last_entry($Table, $PrimaryKey=""){
        if(!$PrimaryKey){$PrimaryKey = $this->get_primary_key($Table);}
        return TableRegistry::get($Table)->find('all')->order([$PrimaryKey => "DESC"])->first()->$PrimaryKey;
    }

    function truncate_table($Table){
        $this->query("TRUNCATE TABLE " . $Table);
    }

    function insert_empty_rows($Table, $Quantity=1, $PrimaryKey=""){
        if(!$PrimaryKey){$PrimaryKey = $this->get_primary_key($Table);}
        $StartsAt = $this->get_last_entry($Table,$PrimaryKey);
        $AnyColumn = $this->getColumnNames($Table);
        foreach($AnyColumn as $ColumnName){
            if($ColumnName != $PrimaryKey){
                $AnyColumn=$ColumnName;
                break;
            }
        }//INSERT INTO table_name (column1,column2,column3,...) VALUES (value1,value2,value3,...);
        $Values = array("");
        $Query = "INSERT INTO " . $Table . " (" . $AnyColumn . ") VALUES ";
        for($Temp = 0; $Temp < $Quantity; $Temp++) {
            //$Table->query()->insert(array($AnyColumn))->values($Values)->execute();
            $Values[] = '("")';
        }
        $Query = str_replace(" , ", " ", $Query . implode(", ", $Values) . ';');
        $this->query($Query);

        $Entries = $this->enum_all($Table, $PrimaryKey . ">" . $StartsAt);
        $Data = array();
        foreach($Entries as $Entry){
            $Data[] = $Entry->$PrimaryKey;
        }
    }

    function new_table($Table){
        if(!$this->table_exists($Table)) {
            $this->query("CREATE TABLE " . $Table . " (id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))");
            return true;
        }
    }
    function delete_column($Table, $Column){
        $this->query("ALTER TABLE " . $Table . " DROP COLUMN " . $Column . ";", true);
    }
    function delete_table($Table){
        $this->query("TRUNCATE TABLE " . $Table, true);
    }
    public function clear_cache() {
        Cache::clear();
        $files = array();
        $files = array_merge($files, glob(CACHE . '*')); // remove cached css
        $files = array_merge($files, glob(CACHE . 'css' . DS . '*')); // remove cached css
        $files = array_merge($files, glob(CACHE . 'js' . DS . '*'));  // remove cached js
        $files = array_merge($files, glob(CACHE . 'models' . DS . '*'));  // remove cached models
        $files = array_merge($files, glob(CACHE . 'persistent' . DS . '*'));  // remove cached persistent

        foreach ($files as $f) {
            $this->delete_file($f);
        }

        if(function_exists('apc_clear_cache')) {
            apc_clear_cache();
            apc_clear_cache('user');
        }
    }
    function delete_file($Filename){
        if (is_file($Filename)) {
            @unlink($Filename);
        }
    }

    function test($Data= ""){
        debug($Data);
        die();
    }

    function callsub($Controller, $Function, $Paramaters=""){
        if(is_array($Paramaters)){$Paramaters = implode("/", $Paramaters);}
        if($_SERVER['SERVER_NAME']  == "localhost"){
            $Path = "http://" . $_SERVER['SERVER_NAME'] . $this->Controller->request->webroot . $Controller . '/' . $Function . '/' . $Paramaters;
        } else if($_SERVER['SERVER_NAME']  == "isbmee.ca"){
            $Path = "http://" . $_SERVER['SERVER_NAME'] . '/mee/' . $Controller . '/' . $Function . '/' . $Paramaters;
        } else {
            $Path = "http://" . $_SERVER['SERVER_NAME'] . '/' . $Controller . '/' . $Function . '/' . $Paramaters;
        }
        return file_get_contents($Path);
    }

    function matchcolumns($Table, $Data){
        $Table = $this->getColumnNames($Table);
        foreach($Data as $key => $value){
            if(!in_array($key,$Table)){
                unset($Data[$key]);
            }
        }
        return $Data;
    }

    function loadpermissions($UserID = false, $Table = "settings", $AsArray = false){//$Table should be sidebar or blocks
        if($UserID==-1){$UserID = $this->Me;}
        if($UserID == $this->Me && isset($this->$Table)){
            $Data = $this->$Table;
        } else {
            $Data = $this->get_entry($Table, $UserID, "user_id");
            if($UserID == $this->Me){
                $this->$Table = $Data;
            }
        }
        if($AsArray){$Data = $this->getProtectedValue($Data, "_properties");}
        return $Data;
    }

    function makepermissions($UserID, $Table, $ProfileType = false){
        //$this->debugprint("Make profile: " . $UserID . " " . $Table);
        if(!$ProfileType){$ProfileType = $this->get_profile($UserID)->profile_type;}
        $Master = $this->enum_all("profiles", array("master" => 1, "profile_type" => $ProfileType))->first();
        if($Master){
            $Values = $this->loadpermissions($Master->id, $Table, true);
            unset($Values["id"]);
        }
        $Values['user_id'] = $UserID;
        TableRegistry::get($Table)->query()->insert(array_keys($Values))->values($Values)->execute();
        return $Master;
    }

    function debug_string_backtrace($Args = false) {
        $BACK = debug_backtrace(0);
        $BACK[2]["line"] = $BACK[1]["line"];
        if(!$Args){unset( $BACK[2]["args"]);}
        return $BACK[2];
    }
    function getmaster($UserID, $ProfileType = false){
        if(!$ProfileType){$ProfileType = $this->get_profile($UserID)->profile_type;}
        $Master = $this->enum_all("profiles", array("master" => 1, "profile_type" => $ProfileType))->first();
        if($Master){return $Master->id;}
        return $UserID;
    }

    function get_settings(){
        return $this->enum_table('Settings')->first();
    }
}
?>