<?php
    $driverinfo2 = $driverinfo;

    function save_webservice_ids($Manager, $orderid, $ins_id, $ebs_id) {
        $arr = array('ins_id' => $ins_id, 'ebs_id' => $ebs_id);
        $Manager->update_database('orders', "id", $orderid, $arr);
    }

    function save_pdi($Manager, $orderid, $id, $pdi) {
        if (in_array($pdi, array("ins_79", "ins_1", "ins_14", "ins_77", "ins_78", "ebs_1603", "ebs_1627", "ebs_1650", "ins_72", "ins_31", "ins_32"))) {
            $arr = array($pdi => $id);
            $Manager->update_database('orders', "id", $orderid, $arr);
        }
    }

    function writing_complete($Manager, $orderid = false) {
        if (!$orderid) {
            $orderid = $Manager->enum_table("orders", "id", "DESC")->first()->id;//just get the latest order
        }
        $Manager->update_database('orders', "id", $orderid, array('complete_writing' => 1));
    }

    if (false) {//this blocks the entire webservice!

        $user_id234 = $this->Session->read('Profile.isb_id');
        if (isset($user_id234) && $user_id234 != "") {
            $user_id234 = $this->Session->read('Profile.isb_id');
        } else {
            $user_id234 = '22552';
        }
        if ($_SERVER['SERVER_NAME'] == "localhost") {
            $user_id234 = '22552';
        }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        include_once('subpages/api.php');

        $proxyhost = 'https://infosearchsite.ca/MEEWS/ISBService.svc?wsdl';
        $client = new nusoap_client($proxyhost, true, $proxyhost, $proxyport = null, $proxyusername = null, $proxypassword = null);
        $client->useHTTPPersistentConnection();
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $DataIneed = array();

        $soap_header = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
<soap:Body><ProductDetails xmlns="http://tempuri.org/">';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $startorder1 = true;
        $driver_order_79 = false; //only for full mee order (driver order)

        if (isset($bulk)) {
            $uploadbinaryconsent_1603 = false;
            $uploadbinaryemployment_1627 = false;
            $uploadbinaryeducation_1650 = false;
        } else {
            $uploadbinaryconsent_1603 = true;
            $uploadbinaryemployment_1627 = true;
            $uploadbinaryeducation_1650 = true;
        }

        $premium_national_ebs_1603 = false;                     //1  1603 Premium check EBS
        $mvr_driversrecordabstract_ins_1 = false;               //2  1    MVR Driver's Record Abstract INS
        $cvor_ins_14 = false;                                   //3  14   CVOR INS
        $preemploymentscreening_ins_77 = false;                 //4  77   Pre-employment Screening Program Report INS
        $transclick_ins_78 = false;                             //5  78   Transclick INS
        $education_certification_ebs_1650 = false;              //6  1650 Certification (Education) EBS
        $loe_employment_ebs_1627 = false;                       //7  1627 LOE (Employment) EBS
        $checkdl_ins_72 = false;                                //8  72   checkdl INS
        $sms_ins_32 = false;                                    //9  32   social media search
        $creditcheck_ins_31 = false;                            //10 31   Credit Check


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $myArray = explode(',', $forms);
        foreach ($myArray as $splitArray) {
            switch ($splitArray) {
                case 1603:
                    $premium_national_ebs_1603 = true;
                    break;
                case 1:
                    $mvr_driversrecordabstract_ins_1 = true;
                    break;
                case 14:
                    $cvor_ins_14 = true;
                    break;
                case 77:
                    $preemploymentscreening_ins_77 = true;
                    break;
                case 78:
                    $transclick_ins_78 = true;
                    break;
                case 1650:
                    $education_certification_ebs_1650 = true;
                    break;
                case 1627:
                    $loe_employment_ebs_1627 = true;
                    break;
                case 72:
                    $checkdl_ins_72 = true;
                    break;
                case 32:
                    $sms_ins_32 = true;
                    break;
                case 31:
                    $creditcheck_ins_31 = true;
                    break;
            }
        }

        foreach ($driverinfo2 as $driverinfo) {

            echo "<br>The time is " . date("h:i:sa");

            if ($driverinfo->order_type == "MEE") {
                $driver_order_79 = true; // only TRUE if complete mee orders  - DONT CHANGE
            }
            if (!(isset($driverinfo->driver_license_no) && $driverinfo->driver_license_no != "")) {
                $driverinfo->driver_license_no = "NA";
            }
            if (!(isset($driverinfo->driver_province) && $driverinfo->driver_province != "")) {
                $driverinfo->driver_province = "ON";
            }
            if (!(isset($driverinfo->email) && $driverinfo->email != "")) {
                $driverinfo->email = "test@doesntexist.com";
            }
            if (!(isset($driverinfo->gender) && $driverinfo->gender != "")) {
                $driverinfo->gender = "NA";
            }
            if (!(isset($driverinfo->mname) && $driverinfo->mname != "")) {
                $driverinfo->mname = "NA";
            }
            if (!(isset($driverinfo->dob) && $driverinfo->dob != "")) {
                $driverinfo->dob = "1900-01-01";
            }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($startorder1 == true) {

                $soap_xml = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
<soap:Body><StartOrder xmlns="http://tempuri.org/"><IntPackage>' .
                    '&lt;ProductData&gt;&lt;isb_FN&gt;' . $driverinfo->fname . '&lt;/isb_FN&gt;&lt;isb_LN&gt;' . $driverinfo->lname . '&lt;/isb_LN&gt;&lt;isb_Ref&gt;MEE&lt;/isb_Ref&gt;&lt;isb_DOL&gt;' . date("Y-m-d") . '&lt;/isb_DOL&gt;&lt;isb_Prov&gt;' . $driverinfo->driver_province . '&lt;/isb_Prov&gt;&lt;isb_UserID&gt;' . $user_id234 . '&lt;/isb_UserID&gt;&lt;/ProductData&gt;' .
                    '</IntPackage><tp>' . $driverinfo->order_type . '</tp><prod>true</prod></StartOrder></soap:Body></soap:Envelope>';

                $result = $client->call('StartOrder', $soap_xml);
                // var_dump($result);
                $myArray = explode(',', $result['StartOrderResult']);

                $ins_id = substr($myArray[0], 4);
                $ebs_id = substr($myArray[1], 4);

                if (!isset($ins_id) || !$ins_id) {
                    echo "error1";die();
                    $ins_id = 0;
                }
                if (!isset($ebs_id) || !$ebs_id) {
                    echo "error2";die();
                    $ebs_id = 0;
                }

                $ins_id = substr($ins_id, 0, 36);
                $ebs_id = substr($ebs_id, 0, 36);

                save_webservice_ids($Manager, $driverinfo->order_id, $ins_id, $ebs_id);
            }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($driver_order_79 == true) {            //this product only goes with FULL mee order, (bright planet)

                $soap_xml = $soap_header .
                    '<UID>' . $ins_id . '</UID><productdetails>&lt;ProductData&gt;&lt;dupe_date&gt;' . date("Y-m-d H:i") . '&lt;/dupe_date&gt;&lt;isb_FirstName&gt;' . $driverinfo->fname . '&lt;/isb_FirstName&gt;&lt;isb_LastName&gt;' . $driverinfo->lname . '&lt;/isb_LastName&gt;&lt;isb_DriverLicence&gt;' . $driverinfo->driver_license_no . '&lt;/isb_DriverLicence&gt;&lt;isb_USDOT_MC&gt;11&lt;/isb_USDOT_MC&gt;&lt;/ProductData&gt;' .
                    '</productdetails><productID>79</productID><tp>INS</tp><prod>true</prod></ProductDetails></soap:Body></soap:Envelope>';

                $result = $client->call('ProductDetails', $soap_xml);
                $r = explode('[', $result['ProductDetailsResult']);
                if (isset($r[1])) {
                    $r = explode(']', $r[1]);
                }
                $pdi_79 = $r[0];
                save_pdi($Manager, $driverinfo->order_id, $pdi_79, 'ins_79');
                $DataIneed[79] = $pdi_79;
            }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($sms_ins_32 == true) {

                $soap_xml = $soap_header .
                    '<UID>' . $ins_id . '</UID><productdetails>&lt;ProductData&gt;&lt;dupe_date&gt;' . date("Y-m-d H:i") . '&lt;/dupe_date&gt;&lt;isb_firstNameIfDiff&gt;' . $driverinfo->fname . '&lt;/isb_firstNameIfDiff&gt;&lt;isb_lastNameIfDiff&gt;' . $driverinfo->lname . '&lt;/isb_lastNameIfDiff&gt;&lt;isb_DOB&gt;' . $driverinfo->dob . '&lt;/isb_DOB&gt;&lt;isb_Gender&gt;' . $driverinfo->gender . '&lt;/isb_Gender&gt;&lt;isb_AppAddress_32&gt;' . $driverinfo->street . '&lt;/isb_AppAddress_32&gt;&lt;isb_AppCity_32&gt;' . $driverinfo->city . '&lt;/isb_AppCity_32&gt;&lt;isb_AppStateProv_32&gt;' . $driverinfo->province . '&lt;/isb_AppStateProv_32&gt;&lt;isb_USDOT_MC&gt;11&lt;/isb_USDOT_MC&gt;&lt;/ProductData&gt;' .
                    '</productdetails><productID>32</productID><tp>INS</tp><prod>true</prod></ProductDetails></soap:Body></soap:Envelope>';

                $result = $client->call('ProductDetails', $soap_xml);
                $r = explode('[', $result['ProductDetailsResult']);
                if (isset($r[1])) {
                    $r = explode(']', $r[1]);
                }
                $pdi_32 = $r[0];
                save_pdi($Manager, $driverinfo->order_id, $pdi_32, 'ins_32');
                $DataIneed[32] = $pdi_32;
            }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($creditcheck_ins_31 == true) {

                $soap_xml = $soap_header .
                    '<UID>' . $ins_id . '</UID><productdetails>&lt;ProductData&gt;&lt;dupe_date&gt;' . date("Y-m-d H:i") . '&lt;/dupe_date&gt;&lt;isb_firstNameNotInsured&gt;' . $driverinfo->fname . '&lt;/isb_firstNameNotInsured&gt;&lt;isb_lastNameNotInsured&gt;' . $driverinfo->lname . '&lt;/isb_lastNameNotInsured&gt;&lt;isb_Gender&gt;' . $driverinfo->gender . '&lt;/isb_Gender&gt;&lt;isb_Address&gt;' . $driverinfo->street . '&lt;/isb_Address&gt;&lt;isb_City&gt;' . $driverinfo->city . '&lt;/isb_City&gt;&lt;isb_provToSearch&gt;' . $driverinfo->province . '&lt;/isb_provToSearch&gt;&lt;isb_PostalCode&gt;' . $driverinfo->postal . '&lt;/isb_PostalCode&gt;&lt;isb_USDOT_MC&gt;11&lt;/isb_USDOT_MC&gt;&lt;/ProductData&gt;' .
                    '</productdetails><productID>31</productID><tp>INS</tp><prod>true</prod></ProductDetails></soap:Body></soap:Envelope>';

                $result = $client->call('ProductDetails', $soap_xml);
                $r = explode('[', $result['ProductDetailsResult']);
                if (isset($r[1])) {
                    $r = explode(']', $r[1]);
                }
                $pdi_31 = $r[0];
                save_pdi($Manager, $driverinfo->order_id, $pdi_31, 'ins_31');
                $DataIneed[31] = $pdi_31;
            }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($mvr_driversrecordabstract_ins_1 == true) {//MVR Driver\'s Record Abstract

                $soap_xml = $soap_header .
                    '<UID>' . $ins_id . '</UID><productdetails>&lt;ProductData&gt;&lt;dupe_date&gt;' . date("Y-m-d H:i") . '&lt;/dupe_date&gt;&lt;isb_aucodes&gt;AU10&lt;/isb_aucodes&gt;&lt;isb_FirstName&gt;' . $driverinfo->fname . '&lt;/isb_FirstName&gt;&lt;isb_LastName&gt;' . $driverinfo->lname . '&lt;/isb_LastName&gt;&lt;isb_DOB&gt;' . $driverinfo->dob . '&lt;/isb_DOB&gt;&lt;isb_DriverLicence&gt;' . $driverinfo->driver_license_no . '&lt;/isb_DriverLicence&gt;&lt;isb_provToSearch&gt;' . $driverinfo->driver_province . '&lt;/isb_provToSearch&gt;&lt;/ProductData&gt;' .
                    '</productdetails><productID>1</productID><tp>INS</tp><prod>true</prod></ProductDetails></soap:Body></soap:Envelope>';

                $result = $client->call('ProductDetails', $soap_xml);
                $r = explode('[', $result['ProductDetailsResult']);
                if (isset($r[1])) {
                    $r = explode(']', $r[1]);
                }
                $pdi_1 = $r[0];
                save_pdi($Manager, $driverinfo->order_id, $pdi_1, 'ins_1');
                $DataIneed[1] = $pdi_1;
            }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($cvor_ins_14 == true) {

                $soap_xml = $soap_header .
                    '<UID>' . $ins_id . '</UID><productdetails>&lt;ProductData&gt;&lt;dupe_date&gt;' . date("Y-m-d H:i") . '&lt;/dupe_date&gt;&lt;isb_FirstName&gt;' . $driverinfo->fname . '&lt;/isb_FirstName&gt;&lt;isb_LastName&gt;' . $driverinfo->lname . '&lt;/isb_LastName&gt;&lt;isb_DOB&gt;' . $driverinfo->dob . '&lt;/isb_DOB&gt;&lt;isb_aucodes14&gt;AU10&lt;/isb_aucodes14&gt;&lt;isb_CVORType&gt;Commercial Vehicle Operator Record Driver Abstract (on drivers)&lt;/isb_CVORType&gt;&lt;isb_DriverLicence&gt;' . $driverinfo->driver_license_no . '&lt;/isb_DriverLicence&gt;&lt;isb_provToSearch&gt;' . $driverinfo->driver_province . '&lt;/isb_provToSearch&gt;&lt;/ProductData&gt;' .
                    '</productdetails><productID>14</productID><tp>INS</tp><prod>true</prod></ProductDetails></soap:Body></soap:Envelope>';

                $result = $client->call('ProductDetails', $soap_xml);
                $r = explode('[', $result['ProductDetailsResult']);
                if (isset($r[1])) {
                    $r = explode(']', $r[1]);
                }
                $pdi_14 = $r[0];
                save_pdi($Manager, $driverinfo->order_id, $pdi_14, 'ins_14');
                $DataIneed[14] = $pdi_14;
            }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($checkdl_ins_72 == true) {

                $soap_xml = $soap_header .
                    '<UID>' . $ins_id . '</UID><productdetails>&lt;ProductData&gt;&lt;dupe_date&gt;' . date("Y-m-d H:i") . '&lt;/dupe_date&gt;&lt;isb_typeOfOrder&gt;Single Order&lt;/isb_typeOfOrder&gt;&lt;isb_provToSearch&gt;' . $driverinfo->driver_province . '&lt;/isb_provToSearch&gt;&lt;isb_DriverLicence&gt;' . $driverinfo->driver_license_no . '&lt;/isb_DriverLicence&gt;&lt;isb_DOB&gt;' . $driverinfo->dob . '&lt;/isb_DOB&gt;&lt;isb_CheckDLBulk&gt;a&lt;/isb_CheckDLBulk&gt;&lt;isb_uploadBulk&gt;a&lt;/isb_uploadBulk&gt;&lt;isb_CheckDLrbl&gt;a&lt;/isb_CheckDLrbl&gt;&lt;isb_rblHaveSig&gt;I confirm that I have signed consent from the drivers licence holder to verify its status&lt;/isb_rblHaveSig&gt;&lt;isb_specialInstructions&gt;&lt;/isb_specialInstructions&gt;&lt;/ProductData&gt;' .
                    '</productdetails><productID>72</productID><tp>INS</tp><prod>true</prod></ProductDetails></soap:Body></soap:Envelope>';

                $result = $client->call('ProductDetails', $soap_xml);
                $r = explode('[', $result['ProductDetailsResult']);
                if (isset($r[1])) {
                    $r = explode(']', $r[1]);
                }
                $pdi_72 = $r[0];
                save_pdi($Manager, $driverinfo->order_id, $pdi_72 . 'ins_72');
                $DataIneed[72] = $pdi_72;
            }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($preemploymentscreening_ins_77 == true) {

                $soap_xml = $soap_header .
                    '<UID>' . $ins_id . '</UID><productdetails>&lt;ProductData&gt;&lt;dupe_date&gt;' . date("Y-m-d H:i") . '&lt;/dupe_date&gt;&lt;isb_FirstName&gt;' . $driverinfo->fname . '&lt;/isb_FirstName&gt;&lt;isb_LastName&gt;' . $driverinfo->lname . '&lt;/isb_LastName&gt;&lt;isb_DOB&gt;' . $driverinfo->dob . '&lt;/isb_DOB&gt;&lt;isb_DriverLicence&gt;' . $driverinfo->driver_license_no . '&lt;/isb_DriverLicence&gt;&lt;isb_provToSearch&gt;' . $driverinfo->driver_province . '&lt;/isb_provToSearch&gt;&lt;/ProductData&gt;' .
                    '</productdetails><productID>77</productID><tp>INS</tp><prod>true</prod></ProductDetails></soap:Body></soap:Envelope>';

                $result = $client->call('ProductDetails', $soap_xml);
                $r = explode('[', $result['ProductDetailsResult']);
                if (isset($r[1])) {
                    $r = explode(']', $r[1]);
                }
                $pdi_77 = $r[0];
                save_pdi($Manager, $driverinfo->order_id, $pdi_77, 'ins_77');
                $DataIneed[77] = $pdi_77;
            }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($transclick_ins_78 == true) {

                $soap_xml = $soap_header .
                    '<UID>' . $ins_id . '</UID><productdetails>&lt;ProductData&gt;&lt;dupe_date&gt;' . date("Y-m-d H:i") . '&lt;/dupe_date&gt;&lt;isb_FirstName&gt;' . $driverinfo->fname . '&lt;/isb_FirstName&gt;&lt;isb_LastName&gt;' . $driverinfo->lname . '&lt;/isb_LastName&gt;&lt;isb_provToSearch&gt;' . $driverinfo->driver_province . '&lt;/isb_provToSearch&gt;&lt;isb_Email&gt;' . $driverinfo->email . '&lt;/isb_Email&gt;&lt;/ProductData&gt;' .
                    '</productdetails><productID>78</productID><tp>INS</tp><prod>true</prod></ProductDetails></soap:Body></soap:Envelope>';

                $result = $client->call('ProductDetails', $soap_xml);
                $r = explode('[', $result['ProductDetailsResult']);
                if (isset($r[1])) {
                    $r = explode(']', $r[1]);
                }
                $pdi_78 = $r[0];
                save_pdi($Manager, $driverinfo->order_id, $pdi_78, 'ins_78');
                $DataIneed[78] = $pdi_78;
            }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($education_certification_ebs_1650 == true) {

                $soap_xml = $soap_header .

                    '<UID>' . $ebs_id . '</UID><productdetails>&lt;ProductData&gt;&lt;dupe_date&gt;' . date("Y-m-d H:i") . '&lt;/dupe_date&gt;&lt;isb_appfirstname&gt;' . $driverinfo->fname . '&lt;/isb_appfirstname&gt;&lt;isb_appsurname&gt;' . $driverinfo->lname . '&lt;/isb_appsurname&gt;&lt;isb_provToSearch&gt;' . $driverinfo->driver_province . '&lt;/isb_provToSearch&gt;&lt;isb_DOB&gt;' . $driverinfo->dob . '&lt;/isb_DOB&gt;&lt;/ProductData&gt;' .
                    '</productdetails><productID>1650</productID><tp>EBS</tp><prod>true</prod></ProductDetails></soap:Body></soap:Envelope>';

                $result = $client->call('ProductDetails', $soap_xml);
                $r = explode('[', $result['ProductDetailsResult']);
                if (isset($r[1])) {
                    $r = explode(']', $r[1]);
                }
                $pdi_1650 = $r[0];
                save_pdi($Manager, $driverinfo->order_id , $pdi_1650 , 'ebs_1650');
                $DataIneed[1650] = $pdi_1650;
            }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($loe_employment_ebs_1627 == true) {
                $soap_xml = $soap_header .
                    '<UID>' . $ebs_id . '</UID><productdetails>&lt;ProductData&gt;&lt;dupe_date&gt;' . date("Y-m-d H:i") . '&lt;/dupe_date&gt;&lt;isb_appfirstname&gt;' . $driverinfo->fname . '&lt;/isb_appfirstname&gt;&lt;isb_appsurname&gt;' . $driverinfo->lname . '&lt;/isb_appsurname&gt;&lt;isb_provToSearch&gt;' . $driverinfo->driver_province . '&lt;/isb_provToSearch&gt;&lt;isb_DOB&gt;' . $driverinfo->dob . '&lt;/isb_DOB&gt;&lt;/ProductData&gt;' .
                    '</productdetails><productID>1627</productID><tp>EBS</tp><prod>true</prod></ProductDetails></soap:Body></soap:Envelope>';

                $result = $client->call('ProductDetails', $soap_xml);
                $r = explode('[', $result['ProductDetailsResult']);
                if (isset($r[1])) {
                    $r = explode(']', $r[1]);
                }
                $pdi_1627 = $r[0];
                save_pdi($Manager, $driverinfo->order_id , $pdi_1627 , 'ebs_1627');
                $DataIneed[1627] = $pdi_1627;
            }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($premium_national_ebs_1603 == true) {

                $soap_xml = $soap_header .
                    '<UID>' . $ebs_id . '</UID><productdetails>&lt;ProductData&gt;&lt;dupe_date&gt;' . date("Y-m-d H:i") . '&lt;/dupe_date&gt;&lt;isb_appothername&gt;' . $driverinfo->mname . '&lt;/isb_appothername &gt;&lt;isb_DOB&gt;' . $driverinfo->dob . '&lt;/isb_DOB&gt;&lt;isb_Sex&gt;' . $driverinfo->gender . '&lt;/isb_Sex&gt;&lt;/ProductData&gt;' .
                    '</productdetails><productID>1603</productID><tp>EBS</tp><prod>true</prod></ProductDetails></soap:Body></soap:Envelope>';

                $result = $client->call('ProductDetails', $soap_xml);
                $r = explode('[', $result['ProductDetailsResult']);
                if (isset($r[1])) {
                    $r = explode(']', $r[1]);
                }
                $pdi_1603 = $r[0];
                save_pdi($Manager, $driverinfo->order_id, $pdi_1603 , 'ebs_1603');
                $DataIneed[1603] = $pdi_1603;
            }

//UPLOAD PDFS
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($uploadbinaryconsent_1603 == true) {
                //   $pdf_content = '';
                //   $pdf_decoded = base64_decode($pdf_content); //if exist
                $pdf = file_get_contents('orders/order_' . $driverinfo->order_id . '/Consent_Form.pdf');
                if ($pdf) {
                    $body = base64_encode($pdf);
                    $soap_xml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><UploadBinaryFile xmlns="http://tempuri.org/">' . '<UID>' . $ebs_id . '</UID><PDI>' . $pdi_1603 . '</PDI><FileData>' . $body . '</FileData><productID>1603</productID><Filename>Consent_Form.pdf</Filename><FileType>ConsentForm</FileType><tp>EBS</tp><prod>true</prod></UploadBinaryFile></soap:Body></soap:Envelope>';
                    $result = $client->call('UploadBinaryFile', $soap_xml);
                }
            }

            if ($uploadbinaryemployment_1627 == true) {
                //  $pdf_content = '';
                //  $pdf_decoded = base64_decode($pdf_content); //if exist
                $pdf = file_get_contents('orders/order_' . $driverinfo->order_id . '/Employment_Form.pdf');
                if ($pdf) {
                    $body = base64_encode($pdf);
                    $soap_xml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><UploadBinaryFile xmlns="http://tempuri.org/">' . '<UID>' . $ebs_id . '</UID><PDI>' . $pdi_1627 . '</PDI><FileData>' . $body . '</FileData><productID>1627</productID><Filename>Employment_Form.pdf</Filename><FileType>ConsentForm</FileType><tp>EBS</tp><prod>true</prod></UploadBinaryFile></soap:Body></soap:Envelope>';
                    $result = $client->call('UploadBinaryFile', $soap_xml);
                    $DataIneed[1627] = $pdi_1627;
                }
            }

            if ($uploadbinaryeducation_1650 == true) {
                //  $pdf_content = '';
                //  $pdf_decoded = base64_decode($pdf_content); //if exist
                $pdf = file_get_contents('orders/order_' . $driverinfo->order_id . '/Education_Form.pdf');
                if ($pdf) {
                    $body = base64_encode($pdf);
                    $soap_xml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><UploadBinaryFile xmlns="http://tempuri.org/">' . '<UID>' . $ebs_id . '</UID><PDI>' . $pdi_1650 . '</PDI><FileData>' . $body . '</FileData><productID>1650</productID><Filename>Education_Form.pdf</Filename><FileType>ConsentForm</FileType><tp>EBS</tp><prod>true</prod></UploadBinaryFile></soap:Body></soap:Envelope>';
                    $result = $client->call('UploadBinaryFile', $soap_xml);
                    $DataIneed[1650] = $pdi_1650;
                }
            }
            writing_complete($driverinfo->order_id);

        }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        die();//this shouldn't be!
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if (true) {//this shouldn't be!


            $JSON = $Manager->order_to_email($orderid, $DataIneed);
            $servicearr["html"] = $JSON;
            $servicearr["email"] = array();

            $Order = $Manager->get_entry("orders", $orderid);
            if($Order->client_id){
                $servicearr["email"] = $Manager->enum_profiles_permission($client_id, "email_orders", "email");
            }
            $servicearr["email"][] = "super";
            $servicearr["email"][] = 'hsidhu@isbc.ca';
            $servicearr["email"][] = 'pclement@isbc.ca';
            $mailer->handleevent("ordercompleted",$servicearr);


//Premium check
            if (isset($attachments1->id_piece1) && $attachments1->id_piece1 != '') {
                if ($premium_national_ebs_1603 == '1') {
                    $sendit = file_get_contents('attachments/' . $attachments1->id_piece1);
                    $body = base64_encode($sendit);
                    $soap_xml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><UploadBinaryFile xmlns="http://tempuri.org/">' . '<UID>' . $ebs_id . '</UID><PDI>' . $pdi_1603 . '</PDI><FileData>' . $body . '</FileData><productID>1603</productID><Filename>Consent_' . $attachments1->id_piece1 . '</Filename><FileType>Attachment</FileType><tp>EBS</tp><prod>true</prod></UploadBinaryFile></soap:Body></soap:Envelope>';
                    $result = $client->call('UploadBinaryFile', $soap_xml);
                    $DataIneed[1603] = $pdi_1603;
                }
            }
            if (isset($attachments1->id_piece2) && $attachments1->id_piece2 != '') {
                if ($premium_national_ebs_1603 == '1') {
                    $sendit = file_get_contents('attachments/' . $attachments1->id_piece2);
                    $body = base64_encode($sendit);
                    $soap_xml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><UploadBinaryFile xmlns="http://tempuri.org/">' . '<UID>' . $ebs_id . '</UID><PDI>' . $pdi_1603 . '</PDI><FileData>' . $body . '</FileData><productID>1603</productID><Filename>Consent_' . $attachments1->id_piece2 . '</Filename><FileType>Attachment</FileType><tp>EBS</tp><prod>true</prod></UploadBinaryFile></soap:Body></soap:Envelope>';
                    $result = $client->call('UploadBinaryFile', $soap_xml);
                    $DataIneed[1603] = $pdi_1603;
                }
            }
            if (isset($attachments1->driver_record_abstract) && $attachments1->driver_record_abstract != '') {
                if ($mvr_driversrecordabstract_ins_1 == '1') {
                    $sendit = file_get_contents('attachments/' . $attachments1->driver_record_abstract);
                    $body = base64_encode($sendit);
                    $soap_xml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><UploadBinaryFile xmlns="http://tempuri.org/">' . '<UID>' . $ins_id . '</UID><PDI>' . $pdi_1 . '</PDI><FileData>' . $body . '</FileData><productID>1</productID><Filename>Consent_' . $attachments1->driver_record_abstract . '</Filename><FileType>Attachment</FileType><tp>INS</tp><prod>true</prod></UploadBinaryFile></soap:Body></soap:Envelope>';
                    $result = $client->call('UploadBinaryFile', $soap_xml);
                    $DataIneed[1] = $pdi_1;
                }
            }
            if (isset($attachments1->cvor) && $attachments1->cvor != '') {
                if ($cvor_ins_14 == '1') {
                    $sendit = file_get_contents('attachments/' . $attachments1->cvor);
                    $body = base64_encode($sendit);
                    $soap_xml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><UploadBinaryFile xmlns="http://tempuri.org/">' . '<UID>' . $ins_id . '</UID><PDI>' . $pdi_14 . '</PDI><FileData>' . $body . '</FileData><productID>14</productID><Filename>Consent_' . $attachments1->cvor . '</Filename><FileType>Attachment</FileType><tp>INS</tp><prod>true</prod></UploadBinaryFile></soap:Body></soap:Envelope>';
                    $result = $client->call('UploadBinaryFile', $soap_xml);
                    $DataIneed[14] = $pdi_14;
                }
            }
            if (isset($attachments1->resume) && $attachments1->resume != '') {
                if ($education_certification_ebs_1650 == '1') {
                    $sendit = file_get_contents('attachments/' . $attachments1->resume);
                    $body = base64_encode($sendit);
                    $soap_xml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><UploadBinaryFile xmlns="http://tempuri.org/">' . '<UID>' . $ebs_id . '</UID><PDI>' . $pdi_1650 . '</PDI><FileData>' . $body . '</FileData><productID>1650</productID><Filename>Consent_' . $attachments1->resume . '</Filename><FileType>Attachment</FileType><tp>EBS</tp><prod>true</prod></UploadBinaryFile></soap:Body></soap:Envelope>';
                    $result = $client->call('UploadBinaryFile', $soap_xml);
                    $DataIneed[1650] = $pdi_1650;
                }
            }
            if (isset($attachments1->certification) && $attachments1->certification != '') {
                if ($loe_employment_ebs_1627 == '1') {
                    $sendit = file_get_contents('attachments/' . $attachments1->certification);
                    $body = base64_encode($sendit);
                    $soap_xml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><UploadBinaryFile xmlns="http://tempuri.org/">' . '<UID>' . $ebs_id . '</UID><PDI>' . $pdi_1627 . '</PDI><FileData>' . $body . '</FileData><productID>1627</productID><Filename>Consent_' . $attachments1->certification . '</Filename><FileType>Attachment</FileType><tp>EBS</tp><prod>true</prod></UploadBinaryFile></soap:Body></soap:Envelope>';
                    $result = $client->call('UploadBinaryFile', $soap_xml);
                    $DataIneed[1627] = $pdi_1627;
                }
            }
        }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    }

?>