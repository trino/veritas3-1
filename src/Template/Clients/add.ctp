<?php
    include_once('subpages/api.php');
    $settings = $this->requestAction('settings/get_settings');
    $language = $this->request->session()->read('Profile.language');
    $issuper = $this->request->session()->read('Profile.super');
    $strings = CacheTranslations($language, array("clients_%", "forms_%", "infoorder_%", "index_%", "documents_document", "application_process"), $settings);//,$registry);//$registry = $this->requestAction('/settings/getRegistry');
    if ($language == "Debug") {
        $Trans = " [Trans]";
    } else {
        $Trans = "";
    }

    include_once 'subpages/filelist.php';
    $delete = isset($disabled);
    $is_disabled = '';
    if (isset($disabled)) {
        $is_disabled = 'disabled="disabled"';
    }
    if (isset($client)) {
        $c = $client;
    }

    $sidebar = $this->requestAction("settings/all_settings/" . $this->request->session()->read('Profile.id') . "/sidebar");
    $getprofile = $this->requestAction('clients/getProfile/' . $id);
    $getcontact = $this->requestAction('clients/getContact/' . $id);
    $param = $this->request->params['action'];

    $action = ucfirst($param);
    if (isset($_GET["view"])) {
        $action = "View";
    }
    if ($action == "Add") {
        $action = "Create";
    }
    $title = $strings["clients_" . strtolower($action) . "client"];
    //includejavascript($strings);
    loadreasons($action, $strings, true);

    JSinclude($this, array("assets/global/plugins/fancybox/source/jquery.fancybox.css", "assets/admin/pages/css/portfolio.css" ), "PAGE LEVEL STYLES");
?>

<h3 class="page-title">
    <?= $title; ?>
</h3>

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="<?= $this->request->webroot . '">' . $strings["dashboard_dashboard"]; ?></a>
            <i class=" fa fa-angle-right"></i>
        </li>
        <li>
            <a href=""><?= $title; ?></a>
        </li>
    </ul>
    <!--a href="javascript:window.print();" class="floatright btn btn-info">Print</a-->
    <?php
        if (isset($disabled) || isset($_GET['view'])) { ?>
            <a href="javascript:window.print();" class="floatright btn btn-info"><?= $strings["dashboard_print"]; ?></a>
        <?php }

        if (isset($client) && $sidebar->client_delete == '1' && $param != 'add') { ?>
            <a href="<?= $this->request->webroot; ?>clients/delete/<?= $client->id; ?><?= (isset($_GET['draft'])) ? "?draft" : ""; ?>"
               onclick="return confirm('Are you sure you want to delete <?= h($client->company_name) ?>?');"
               class="floatright btn btn-danger btnspc"><?= $strings["dashboard_delete"]; ?></a>
        <?php }
        if (isset($client) && $sidebar->client_edit == '1' && isset($_GET['view'])) {
            echo $this->Html->link(__($strings["dashboard_edit"]), ['controller' => 'clients', 'action' => 'edit', $client->id], ['class' => 'floatright btn btn-primary btnspc']);
        } else if (isset($client) && $param == 'edit') {
            ?>
            <a href="<?= $this->request->webroot; ?>clients/edit/<?= $client->id; ?>?view"
               class='floatright btn btn-info btnspc'><?= $strings["dashboard_view"]; ?></a>
            <?php

        }

        echo "</div>";
    ?>

    <div class="row ">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->

            <div class="row profile-account">
                <div class="col-md-3" align="center">
                    <img class="img-responsive" id="clientpic" alt=""
                         src="<?= clientimage($this->request->webroot, $settings, $client); ?>"/>

                    <div class="form-group">
                        <label class="sr-only"
                               for="exampleInputEmail22"><?= $strings["clients_addeditimage"]; ?></label>

                        <div class="input-icon">
                            <a class="btn btn-xs btn-success" href="javascript:void(0)" id="clientimg">
                                <i class="fa fa-image"></i>
                                <?= $strings["clients_addeditimage"]; ?>
                            </a>

                        </div>
                    </div>

                    <!--php  if (isset($client_docs)) { listfiles($client_docs, "img/jobs/",'client_doc',$delete,  2); } ?-->

                </div>

                <div class="col-md-9">


                    <div class="clearfix"></div>

                    <div class="portlet box grey-salsa">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-briefcase"></i><?= $strings["clients_manager"]; ?>
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1_1" data-toggle="tab"><?= $strings["clients_info"]; ?></a>
                                </li>
                                <?php if ($this->request['action'] != "add" && !isset($_GET['view'])) {
                                    ?>

                                    <li>
                                        <a href="#tab_1_6" data-toggle="tab"><?= $strings["clients_requalify"] ?></a>
                                    </li>


                                    <li>
                                        <a href="#tab_1_4" data-toggle="tab"><?= $strings["clients_products"]; ?></a>
                                    </li>

                                    <li>
                                        <a href="#tab_1_2" data-toggle="tab"><?= $strings["documents_document"]; ?></a>
                                    </li>

                                    <!--<li>
                                        <a href="#tab_1_7" data-toggle="tab"><?= $strings["application_process"]; ?></a>
                                    </li>-->

                                    <li>
                                        <a href="#tab_1_3"
                                           data-toggle="tab"><?php echo (!isset($_GET['view'])) ? $strings["clients_assigntoprofile"] : $strings["clients_assignedto"]; ?></a>
                                    </li>


                                    <?php

                                } ?>


                            </UL>
                        </div>

                        <div class="portlet-body form">
                            <div class="form-body" style="padding-bottom: 0px;">
                                <div class="tab-content">
                                    <?php

                                        if (!isset($_GET['activedisplay']))
                                        {
                                    ?>
                                    <div class="tab-pane active" id="tab_1_1">
                                        <div id="tab_1_1" class="tab-pane active">
                                            <?php
                                                }
                                                else
                                                {
                                            ?>
                                            <div class="tab-pane" id="tab_1_1">
                                                <div id="tab_1_1" class="tab-pane">
                                                    <?php
                                                        }
                                                    ?>
                                                    <form role="form" action="" method="post" id="client_form"
                                                          class="save_client_all">
                                                        <input type="hidden" name="drafts" id="client_drafts"
                                                               value="0"/>


                                                        <div class="row">
                                                            <input type="hidden" name="image" id="client_img"/>
                                                            <?php if ($settings->client_option == 0 && $settings->mee != "AFIMAC SMI") { ?>
                                                                <div class="form-group col-md-4">
                                                                    <label
                                                                        class="control-label"><?= $strings["forms_customertype"]; ?>
                                                                        :</label>
                                                                    <select class="form-control" name="customer_type"
                                                                            id="customer_type">
                                                                        <option value="">Select</option>
                                                                        <?php
                                                                            $ctyp = $this->requestAction('profiles/gettypes/ctypes/' . $this->request->session()->read('Profile.id'));
                                                                            if ($ctyp != "") {
                                                                                $cts = explode(",", $ctyp);
                                                                            }
                                                                            $fieldname = getFieldname("title", $language);

                                                                            foreach ($client_types as $ct) {
                                                                                if (isset($cts)) {
                                                                                    if (in_array($ct->id, $cts)) {
                                                                                        ?>
                                                                                        <option
                                                                                            value="<?php echo $ct->id; ?>"
                                                                                            <?php if (isset($client->customer_type) && $client->customer_type == $ct->id) { ?>selected="selected"<?php } ?>>
                                                                                            <?php echo $ct->$fieldname; ?>
                                                                                        </option>
                                                                                        <?php
                                                                                    }
                                                                                } else {
                                                                                    ?>
                                                                                    <option
                                                                                        value="<?php echo $ct->id; ?>"
                                                                                        <?php if (isset($client->customer_type) && $client->customer_type == $ct->id) { ?>selected="selected"<?php } ?>>
                                                                                        <?php echo $ct->$fieldname; ?>
                                                                                    </option>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                        ?>

                                                                    </select>
                                                                </div>

                                                            <?php } ?>
                                                            <div class="form-group col-md-4">
                                                                <label
                                                                    class="control-label"> <?php echo ($settings->client_option == 0) ? $strings["forms_companyname"] : $strings["forms_eventname"]; ?>
                                                                </label>
                                                                <input required="required" type="text"
                                                                       class="form-control"
                                                                       name="company_name" <?php if (isset($client->company_name)) { ?> value="<?php echo $client->company_name; ?>" <?php } ?>/>
                                                            </div>

                                                            <?php if ($settings->client_option == 0) { ?>
                                                                <div class="form-group col-md-4">
                                                                    <label
                                                                        class="control-label"><?= $strings["forms_address"]; ?>
                                                                        :</label>
                                                                    <input type="text" class="form-control"
                                                                           name="company_address" <?php if (isset($client->billing_address)) { ?> value="<?php echo $client->billing_address; ?>" <?php } ?>/>
                                                                </div>
                                                            <?php } ?>
                                                            <div class="form-group col-md-4">
                                                                <label
                                                                    class="control-label"><?= $strings["forms_city"]; ?>
                                                                    :</label>
                                                                <input type="text" class="form-control"
                                                                       name="city" <?php if (isset($client->city)) { ?> value="<?php echo $client->city; ?>" <?php } ?>/>
                                                            </div>

                                                            <div class="form-group col-md-4">
                                                                <label
                                                                    class="control-label"><?= $strings["forms_provincestate"]; ?>
                                                                    :</label>
                                                                <?php
                                                                    function printoption($value, $selected, $option)
                                                                    {
                                                                        $tempstr = "";
                                                                        if ($option == $selected or $value == $selected) {
                                                                            $tempstr = " selected";
                                                                        }
                                                                        echo '<OPTION VALUE="' . $value . '"' . $tempstr . ">" . $option . "</OPTION>";
                                                                    }

                                                                    function printoptions($name, $valuearray, $selected, $optionarray)
                                                                    {
                                                                        echo '<SELECT name="' . $name . '" class="form-control member_type" >';
                                                                        for ($temp = 0; $temp < count($valuearray); $temp += 1) {
                                                                            printoption($valuearray[$temp], $selected, $optionarray[$temp]);
                                                                        }
                                                                        echo '</SELECT>';
                                                                    }

                                                                    function printprovinces($name, $selected, $Language = "English")
                                                                    {
                                                                        $acronyms = getprovinces("Acronyms", True);
                                                                        $provinces = getprovinces($Language, True);
                                                                        printoptions($name, $acronyms, $selected, $provinces);
                                                                    }

                                                                    printprovinces("province", $client->province, $language);
                                                                ?>

                                                            </div>
                                                            <?php if ($settings->client_option == 0) { ?>
                                                                <div class="form-group col-md-4">
                                                                    <label
                                                                        class="control-label"><?= $strings["forms_postalzip"]; ?>
                                                                        :</label>
                                                                    <input type="text" class="form-control"
                                                                           role='postalzip'
                                                                           name="postal" <?php if (isset($client->postal)) { ?> value="<?php echo $client->postal; ?>" <?php } ?>/>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label
                                                                        class="control-label"><?= $strings["forms_companyphone"]; ?>
                                                                        :</label>
                                                                    <input type="text" class="form-control"
                                                                           id="company_phone"
                                                                           name="company_phone"
                                                                           role='phone' <?php if (isset($client->company_phone)) { ?> value="<?php echo $client->company_phone; ?>" <?php } ?>
                                                                        />
                                                                </div>
                                                            <?php } ?>
                                                            <div class="form-group col-md-4">
                                                                <label
                                                                    class="control-label"><?= $strings["forms_website"]; ?>
                                                                    :</label>
                                                                <input type="text" class="form-control"
                                                                       name="site" <?php if (isset($client->site)) { ?> value="<?php echo $client->site; ?>" <?php } ?>/>
                                                            </div>
                                                            <?php if ($settings->client_option == 0) { ?>
                                                                <div class="form-group col-md-4">
                                                                    <label
                                                                        class="control-label"><?= $strings["forms_divisions"]; ?>
                                                                        :</label>
                                                                    <textarea name="division" id="division"
                                                                              placeholder="<?= $strings["forms_oneperline"]; ?>"
                                                                              class="form-control"><?php if (isset($client->division)) echo $client->division; ?></textarea>
                                                                </div>

                                                                <div class="form-group col-md-4">
                                                                    <label
                                                                        class="control-label"><?= $strings["forms_signatoryfirstname"]; ?>
                                                                        :</label>
                                                                    <input type="text" class="form-control"
                                                                           name="sig_fname" <?php if (isset($client->sig_fname)) { ?> value="<?php echo $client->sig_fname; ?>" <?php } ?>/>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label
                                                                        class="control-label"><?= $strings["forms_signatorylastname"]; ?>
                                                                        :</label>
                                                                    <input type="text" class="form-control"
                                                                           name="sig_lname" <?php if (isset($client->sig_lname)) { ?> value="<?php echo $client->sig_lname; ?>" <?php } ?>/>
                                                                </div>

                                                                <div class="form-group col-md-4">
                                                                    <label
                                                                        class="control-label"><?= $strings["forms_signatoryemail"]; ?>
                                                                        :</label>
                                                                    <input type="email" id="sig_email"
                                                                           class="form-control" role="email"
                                                                           name="sig_email" <?php if (isset($client->sig_email)) { ?> value="<?php echo $client->sig_email; ?>" <?php } ?>/>
                                                                </div>


                                                                <div class="form-group col-md-4">
                                                                    <label
                                                                        class="control-label"><?= $strings["forms_startdate"]; ?>
                                                                        :</label>
                                                                    <input type="text" class="form-control date-picker"
                                                                           name="date_start" <?php if (isset($client->date_start)) { ?> value="<?php echo $client->date_start; ?>" <?php } ?>/>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label
                                                                        class="control-label"><?= $strings["forms_enddate"]; ?>
                                                                        :</label>
                                                                    <input type="text" class="form-control date-picker"
                                                                           name="date_end" <?php if (isset($client->date_end)) { ?> value="<?php echo $client->date_end; ?>" <?php } ?>/>
                                                                </div>

                                                                <!--div class="form-group col-md-4">
                                                                    <label class="control-label">Date</label>
                                                                    <input type="text" class="form-control date-picker"
                                                                           name="client_date" <?php if (isset($client->client_date)) { ?> value="<?php echo $client->client_date; ?>" <?php } ?>/>
                                                                </div-->


                                                                <?php if ($settings->mee == "MEE") { ?>
                                                                    <div class="form-group col-md-4">
                                                                        <label
                                                                            class="control-label"><?= $strings["forms_referredby"]; ?>
                                                                            :</label>
                                                                        <select class="form-control" name="referred_by"
                                                                                id="referred_by">
                                                                            <option
                                                                                value=""><?= $strings["forms_select"]; ?></option>
                                                                            <option
                                                                                value="Transrep" <?php if (isset($client->referred_by) && $client->referred_by == "Transrep") { ?> selected="selected" <?php } ?> >
                                                                                Transrep
                                                                            </option>
                                                                            <option
                                                                                value="ISB" <?php if (isset($client->referred_by) && $client->referred_by == "ISB") { ?> selected="selected" <?php } ?> >
                                                                                ISB
                                                                            </option>
                                                                            <option
                                                                                value="AFIMAC" <?php if (isset($client->referred_by) && $client->referred_by == "AFIMAC") { ?> selected="selected" <?php } ?>>
                                                                                AFIMAC
                                                                            </option>
                                                                            <option
                                                                                value="Broker" <?php if (isset($client->referred_by) && $client->referred_by == "Broker") { ?> selected="selected" <?php } ?>>
                                                                                Broker
                                                                            </option>
                                                                            <option
                                                                                value="Online" <?php if (isset($client->referred_by) && $client->referred_by == "Online") { ?> selected="selected" <?php } ?>>
                                                                                Online
                                                                            </option>
                                                                            <option
                                                                                value="Tradeshow" <?php if (isset($client->referred_by) && $client->referred_by == "Tradeshow") { ?> selected="selected" <?php } ?>>
                                                                                Tradeshow
                                                                            </option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group col-md-4">
                                                                        <label
                                                                            class="control-label"><?= $strings["forms_arisagreement"]; ?>
                                                                            :</label>
                                                                        <input type="text" class="form-control"
                                                                               name="agreement_number" <?php if (isset($client->agreement_number)) { ?> value="<?php echo $client->agreement_number; ?>" <?php } ?>/>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label
                                                                            class="control-label"><?= $strings["forms_arisreverification"]; ?>
                                                                            :</label>
                                                                        <input type="text"
                                                                               class="form-control form-control-inline date-picker"
                                                                               name="reverification" <?php if (isset($client->reverification)) { ?> value="<?php echo $client->reverification; ?>" <?php } ?>/>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label
                                                                            class="control-label"><?= $strings["forms_sacc"]; ?>
                                                                            :</label>
                                                                        <input type="text" class="form-control"
                                                                               name="sacc_number" <?php if (isset($client->sacc_number)) { ?> value="<?php echo $client->sacc_number; ?>" <?php } ?>/>
                                                                    </div>


                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <h3 class="block"><?= $strings["forms_billing"]; ?></h3>
                                                                        </div>
                                                                    </div>


                                                                    <div class="form-group col-md-4">
                                                                        <label
                                                                            class="control-label"><?= $strings["forms_billingcontact"]; ?>
                                                                            :</label>
                                                                        <input type="text" class="form-control"
                                                                               name="billing_contact" <?php if (isset($client->billing_contact)) { ?> value="<?= $client->billing_contact; ?>" <?php } ?>/>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label
                                                                            class="control-label"><?= $strings["forms_billingaddress"]; ?>
                                                                            :</label>
                                                                        <input type="text" class="form-control"
                                                                               name="billing_address" <?php if (isset($client->billing_address)) { ?> value="<?= $client->billing_address; ?>" <?php } ?>/>
                                                                    </div>

                                                                    <div class="form-group col-md-4">
                                                                        <label
                                                                            class="control-label"><?= $strings["forms_billingcity"]; ?>
                                                                            :</label>
                                                                        <input type="text" class="form-control"
                                                                               name="billing_city"
                                                                               value="<?php echo isset($client->billing_city) ? $client->billing_city : '' ?>"/>
                                                                    </div>

                                                                    <div class="form-group col-md-4">
                                                                        <label
                                                                            class="control-label"><?= $strings["forms_billingcustomertype"]; ?>
                                                                            :</label>
                                                                        <?php printprovinces("province", $client->billing_province, $language); ?>


                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label
                                                                            class="control-label"><?= $strings["forms_billingpostalcode"]; ?>
                                                                            :</label>
                                                                        <input type="text" class="form-control"
                                                                               role='postalcode'
                                                                               name="billing_postal_code"
                                                                               value="<?php echo isset($client->billing_postal_code) ? $client->billing_postal_code : '' ?>"/>
                                                                    </div>


                                                                    <div class="form-group col-md-4">
                                                                        <label
                                                                            class="control-label"><?= $strings["forms_invoiceterms"]; ?>
                                                                            :</label>
                                                                        <select class="form-control"
                                                                                name="invoice_terms" id="invoice_terms">
                                                                            <option
                                                                                value=""><?= $strings["forms_select"]; ?></option>
                                                                            <option
                                                                                value="weekly" <?php if (isset($client->invoice_terms) && $client->invoice_terms == 'weekly') { ?> selected="selected" <?php } ?>>
                                                                                <?= $strings["forms_weekly"]; ?>
                                                                            </option>
                                                                            <option
                                                                                value="biweekly" <?php if (isset($client->invoice_terms) && $client->invoice_terms == 'biweekly') { ?> selected="selected" <?php } ?>>
                                                                                <?= $strings["forms_biweekly"]; ?>
                                                                            </option>
                                                                            <option
                                                                                value="monthly" <?php if (isset($client->invoice_terms) && $client->invoice_terms == 'monthly') { ?> selected="selected" <?php } ?>>
                                                                                <?= $strings["forms_monthly"]; ?>
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <?php if ($issuper) { ?>
                                                                        <div class="form-group col-md-4">
                                                                            <label
                                                                                class="control-label"><?= $strings["forms_forceemail"]; ?>
                                                                                :</label>
                                                                            <input type="text" class="form-control"
                                                                                   name="forceemail" role="email"
                                                                                   value="<?php echo isset($client->forceemail) ? $client->forceemail : '' ?>"/>
                                                                        </div>
                                                                    <?php } ?>

                                                                    <div class="form-group col-md-12">
                                                                        <label
                                                                            class="control-label"><?= $strings["forms_billinginstructions"]; ?>
                                                                            :</label>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <input type="radio"
                                                                               name="billing_instructions" <?php if (isset($client->billing_instructions) && $client->billing_instructions == "individual") { ?> checked="checked" <?php } ?>
                                                                               value="individual"/> <?= $strings["forms_individual"]; ?>
                                                                        &nbsp;&nbsp;
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <input type="radio"
                                                                               name="billing_instructions" <?php if (isset($client->billing_instructions) && $client->billing_instructions == "centralized") { ?> checked="checked" <?php } ?>
                                                                               value="centralized"/> <?= $strings["forms_centralized"]; ?>
                                                                        &nbsp;&nbsp;
                                                                    </div>


                                                                    <div class="form-group col-md-12">
                                                                        <label class="control-label">Billing Customer
                                                                            Type:</label>
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <input type="radio"
                                                                               name="billing_instructions" <?php if (isset($client->billing_instructions) && $client->billing_instructions == "individual") { ?> checked="checked" <?php } ?>
                                                                               value="individual"/> Direct&nbsp;&nbsp;
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <input type="radio"
                                                                               name="billing_instructions" <?php if (isset($client->billing_instructions) && $client->billing_instructions == "centralized") { ?> checked="checked" <?php } ?>
                                                                               value="centralized"/> Non-direct&nbsp;&nbsp;
                                                                    </div>


                                                                <?php } ?>
                                                                <div class="form-group col-md-12">

                                                                    <label
                                                                        class="control-label"><?= $strings["forms_description"]; ?>
                                                                        :</label>
                                                        <textarea id="description" name="description"
                                                                  class="form-control"><?php if (isset($client->description)) {
                                                                echo $client->description;
                                                            } ?></textarea>

                                                                </div>

                                                            <?php }
                                                                $delete = isset($disabled);
                                                                if (isset($client_docs)) {
                                                                    listfiles($client_docs, "img/jobs/", 'client_doc', $delete);
                                                                }
                                                            ?>

                                                            <div class="form-group row">
                                                                <div class="docMore col-md-12" data-count="1">
                                                                    <div style="display:block;" class="col-md-12">
                                                                        <?php
                                                                            if ($action == "create") {
                                                                                echo $strings["forms_attachdocs"] . "<br>";
                                                                            }
                                                                        ?>
                                                                        <a href="javascript:void(0)" id="addMore1"
                                                                           class="btn btn-primary clearfix"><?= $strings["forms_browse"]; ?></a>
                                                                        <input type="hidden" name="client_doc[]"
                                                                               value="" class="addMore1_doc moredocs"/>
                                                                        <span></span>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="form-group col-md-12">
                                                                <a href="javascript:void(0)" class="btn btn-info"
                                                                   id="addMoredoc">
                                                                    <?= $strings["forms_addmore"]; ?>
                                                                </a>
                                                            </div>
                                                            <div class="form-group col-md-12" align="right">
                                                                <!--<center>-->
                                                                <div
                                                                    class="margin-top-10 alert alert-success display-hide flash1"
                                                                    style="display: none;">
                                                                    <button class="close" data-close="alert"></button>
                                                                    <?= $strings["forms_datasaved"]; ?>
                                                                </div>
                                                            </div>

                                                            <div class="clearfix"></div>


                                                            <div class="form-actions top chat-form"
                                                                 style="height:75px; margin-bottom:-1px;padding-right: 30px;margin-right: 5px;margin-left: 5px;"
                                                                 align="right">
                                                                <div class="row">
                                                                    <button type="submit" class="btn btn-primary"
                                                                            id="save_client_p1"><?= $strings["forms_savechanges"]; ?>
                                                                    </button>
                                                                    <!--button type="submit" class="btn btn-info" onclick="$('#client_drafts').val('1',function(){$('#save_client_p1').click();});">Save As Draft</button-->
                                                                </div>
                                                            </div>


                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                        <?php
                                            echo '<div';
                                            if (isset($_GET["products"])) {
                                                echo " active";
                                            }
                                            echo ' class="tab-pane" id="tab_1_4">';
                                            include('subpages/clients/products.php');
                                            echo "</DIV>";

                                            if ($this->request['action'] != "add" && !isset($_GET['view']))
                                            {
                                            if (isset($_GET['activedisplay']))
                                            {
                                        ?>
                                        <div class="tab-pane active" id="tab_1_2">
                                            <?php
                                                }
                                                else
                                                {
                                            ?>
                                            <div class="tab-pane" id="tab_1_2">
                                                <?php } ?>
                                                <h4 class="col-md-6">
                                                    <?= $strings["clients_enabledocs"]; ?></h4>

                                                <div class="clearfix"></div>

                                                <form action="" id="displayform1" method="post">
                                                    <table class="table table-light table-hover sortable">
                                                        <tr class="myclass">
                                                            <th></th>
                                                            <!--<th class="">System</th>-->
                                                            <th class=""><?= $strings["documents_document"]; ?> </th>

                                                            <? if ($settings->mee != "Events Audit") {
                                                                ?>
                                                                <th class=""><?= $strings["index_orders"]; ?></th>
                                                                <th class=""><?= $strings["application_process"]; ?></th>
                                                                <th class=""><?= $strings["clients_displayorder"]; ?></th>
                                                            <? } ?>
                                                        </tr>
                                                        <?php
                                                            //$subdoc = $this->requestAction('/clients/getSub');
                                                            $subdoccli = $this->requestAction('/clients/getSubCli/' . $id);
                                                            $u = 0;

                                                            if ($settings->mee == "Events Audit") {

                                                                foreach ($subdoccli as $subcl) {

                                                                    $u++;
                                                                    $sub = $this->requestAction('/clients/getFirstSub/' . $subcl->sub_id);

                                                                    if (strtolower($sub['title']) == "audit" || strtolower($sub['title']) == "attachment") {

                                                                        ?>
                                                                        <tr id="subd_<?php echo $sub->id; ?>"
                                                                            class="sublisting">
                                                                            <td>

                            <span
                                id="sub_<?php echo $sub['id']; ?>"><?php echo ucfirst($sub['title']); ?></span>
                                                                            </td>

                                                                            <?php
                                                                                $csubdoc = $this->requestAction('/settings/all_settings/0/0/client/' . $id . '/' . $sub->id);
                                                                            ?>
                                                                            <td class="">
                                                                                <label class="uniform-inline">
                                                                                    <input <?php echo $is_disabled ?>
                                                                                        type="radio"
                                                                                        name="clientC[<?php echo $sub->id; ?>]"
                                                                                        value="1" <?php if ($csubdoc['display'] == 1) { ?> checked="checked" <?php } ?> />
                                                                                    <?= $strings["dashboard_affirmative"]; ?>
                                                                                </label>
                                                                                <label class="uniform-inline">
                                                                                    <input <?php echo $is_disabled ?>
                                                                                        type="radio"
                                                                                        name="clientC[<?php echo $sub->id; ?>]"
                                                                                        value="0" <?php if ($csubdoc['display'] == 0) { ?> checked="checked" <?php } ?> />
                                                                                    <?= $strings["dashboard_negative"]; ?>
                                                                                </label>
                                                                            </td>

                                                                        </tr>

                                                                        <?php
                                                                    }
                                                                }
                                                            } else {

                                                                foreach ($subdoccli as $subcl) {
                                                                    $u++;
                                                                    $sub = $this->requestAction('/clients/getFirstSub/' . $subcl->sub_id);
                                                                    if ($sub) {
                                                                        ?>
                                                                        <tr id="subd_<?php echo $sub->id; ?>"
                                                                            class="sublisting">
                                                                            <td>

                            <span
                                id="sub_<?php echo $sub['id']; ?>"><?= ucfirst($sub[getFieldname('title', $language)]) . $Trans; ?></span>
                                                                            </td>

                                                                            <?php
                                                                                $csubdoc = $this->requestAction('/settings/all_settings/0/0/client/' . $id . '/' . $sub->id);
                                                                            ?>
                                                                            <td class="">

                                                                                <label class="uniform-inline">
                                                                                    <input <?php echo $is_disabled ?>
                                                                                        type="radio"
                                                                                        name="clientC[<?php echo $sub->id; ?>]"
                                                                                        value="1" <?php if ($csubdoc['display'] == 1) { ?> checked="checked" <?php } ?> />
                                                                                    <?= $strings["dashboard_affirmative"]; ?>
                                                                                </label>
                                                                                <label class="uniform-inline">
                                                                                    <input <?php echo $is_disabled ?>
                                                                                        type="radio"
                                                                                        name="clientC[<?php echo $sub->id; ?>]"
                                                                                        value="0" <?php if ($csubdoc['display'] == 0) { ?> checked="checked" <?php } ?> />
                                                                                    <?= $strings["dashboard_negative"]; ?>
                                                                                </label>
                                                                            </td>
                                                                            <td>
                                                                                <label>
                                                                                <input class="ordercheck" <?php if ($csubdoc['display_order'] == 1) { ?> checked="checked" <?php } ?>
                                                                                    type="checkbox" id="check<?= $u ?>"
                                                                                    onclick="if($(this).is(':checked')){$(this).closest('td').find('.fororder').val('1');}else {$(this).closest('td').find('.fororder').val('0');}"/>
                                                                                <? $strings["clients_show"]; ?></label>

                                                                                <input class="fororder" type="hidden"
                                                                                       value="<?php if ($csubdoc['display_order'] == 1) {
                                                                                           echo '1';
                                                                                       } else { ?>0<?php } ?>"
                                                                                       name="clientO[<?php echo $sub->id; ?>]"/>
                                                                            </td>
                                                                            <td>
                                                                                <input <?php if ($csubdoc['display_application'] == 1) { ?> checked="checked" <?php } ?>
                                                                                    type="checkbox" class="inputapp" id="check<?= $u ?>"
                                                                                    onclick="if($(this).is(':checked')){$(this).closest('td').find('.forapp').val('1');}else {$(this).closest('td').find('.forapp').val('0');}"/>
                                                                                

                                                                                <input class="forapp" type="hidden"
                                                                                       value="<?php if ($csubdoc['display_application'] == 1) {
                                                                                           echo '1';
                                                                                       } else { ?>0<?php } ?>"
                                                                                       name="clientO[<?php echo $sub->id; ?>]"/>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $u; ?>
                                                                            </td>
                                                                        </tr>

                                                                        <?php
                                                                    }
                                                                }
                                                            }

                                                        ?>

                                                    </table>

                                                    <!--end profile-settings-->

                                                    <?php
                                                        if (!isset($disabled)) {
                                                            ?>

                                                            <div
                                                                class="margin-top-10 alert alert-success display-hide flash"
                                                                style="display: none;">
                                                                <button class="close" data-close="alert"></button>
                                                                <?= $strings["forms_datasaved"]; ?>
                                                            </div>

                                                            <div class="form-actions top chat-form"
                                                                 style="height:75px; margin-bottom:-1px;padding-right: 30px;margin-right: -10px;margin-left: -10px;"
                                                                 align="right">
                                                                <div class="row">
                                                                    <a href="javascript:void(0)" id="save_display1"
                                                                       class="btn btn-primary" <?php echo $is_disabled ?>>
                                                                        <?= $strings["forms_savechanges"]; ?> </a>

                                                                </div>
                                                            </div>


                                                            <?php
                                                        }
                                                    ?>

                                                </form>
                                            </div>
                                            <?php } ?>
                                            <div class="tab-pane" id="tab_1_3" style="min-height: 300px;">
                                                <?php
                                                    include('subpages/clients/recruiter_contact_table.php');
                                                ?>
                                            </div>
                                            <div class="tab-pane" id="tab_1_6" style="">
                                                <?php
                                                    if ($action != "Create") {
                                                        include('subpages/clients/requalify.php');
                                                    }
                                                ?>
                                            </div>

                                            <!--<div class="tab-pane" id="tab_1_7" style="">
                                                <?php
                                                    //include('subpages/clients/application_process.php');
                                                ?>
                                            </div>-->


                                            <!-- END SAMPLE FORM PORTLET-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(function () {
                    var tosend = '';
                    $('.sortable tbody').sortable({
                        items: "tr:not(.myclass)",
                        update: function (event, ui) {

                            $('.sublisting').each(function () {
                                var id = $(this).attr('id');
                                id = id.replace('subd_', '');
                                if (tosend == '') {
                                    tosend = id;
                                }
                                else
                                    tosend = tosend + ',' + id;
                            });
                            $.ajax({
                                url: '<?php echo $this->request->webroot;?>clients/updateOrder/<?php if(isset($id))echo $id;?>',
                                data: 'tosend=' + tosend,
                                type: 'post'
                            });
                            $.ajax({
                                url: '<?php echo $this->request->webroot;?>clients/updateOrderApplication/<?php if(isset($id))echo $id;?>',
                                data: 'tosend=' + tosend,
                                type: 'post'
                            });
                            tosend = '';


                        }
                    });

                   /* var tosend2 = '';
                    $('.sortable2 tbody').sortable({
                        items: "tr:not(.myclass2)",
                        update: function (event, ui) {

                            $('.sublisting2').each(function () {
                                var id = $(this).attr('id');
                                id = id.replace('subd_', '');
                                if (tosend2 == '') {
                                    tosend2 = id;
                                }
                                else
                                    tosend2 = tosend2 + ',' + id;
                            });
                            $.ajax({
                                url: '<?php echo $this->request->webroot;?>clients/updateOrderApplication/<?php if(isset($id))echo $id;?>',
                                data: 'tosend=' + tosend2,
                                type: 'post'
                            });
                            tosend = '';


                        }
                    });*/
                    <?php
                    if(isset($_GET['view']))
                    {
                        ?>
                    $('#client_form input').each(function () {
                        $(this).attr("disabled", 'disabled');
                    });
                    $('#client_form a').hide();
                    $('.uploaded').show();
                    $('#clientimg').hide();
                    $('#client_form textarea').each(function () {
                        $(this).attr("disabled", 'disabled');
                    });

                    $('#client_form select').each(function () {
                        $(this).attr("disabled", 'disabled');
                    });

                    $('.recruiters input').each(function () {
                        $(this).attr("disabled", 'disabled');
                    });
                    $('#searchProfile').hide();
                    $('#save_client_p1').hide();
                    $('#attach_label').hide();
                    <?php }?>
                    $('input [type="email"]').keyup(function () {
                        $(this).removeAttr('style');
                    });
                    initiate_ajax_upload('clientimg', 'asdas');
                    initiate_ajax_upload('addMore1', 'doc');
                    <?php
                    if(isset($id))
                    {
                    ?>

                    $('#save_display1').click(function () {
                        $('#save_display1').text('Saving..');
                        var str = $('#displayform1 input.fororder').serialize();
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>clients/displaySubdocs/<?php echo $id;?>',
                            data: str,
                            type: 'post',
                            success: function (res) {
                                $('.flash').show();
                                //$('#save_display1').text('<?= $strings["forms_savechanges"]; ?>');
                            }
                        });
                        
                        var str2 = $('#displayform1 input.forapp').serialize();
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>clients/displaySubdocsApplication/<?php echo $id;?>',
                            data: str2,
                            type: 'post',
                            success: function (res) {
                                $('.flash').show();
                                $('#save_display1').text('<?= $strings["forms_savechanges"]; ?>');
                            }
                        })
                    });

                    /*$('#save_display7').click(function () {
                        $('#save_display7').text('Saving..');
                        var str = $('#displayform7 input').serialize();
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>clients/displaySubdocsApplication/<?php echo $id;?>',
                            data: str,
                            type: 'post',
                            success: function (res) {
                                $('.flash').show();
                                $('#save_display1').text('<?= $strings["forms_savechanges"]; ?>');
                            }
                        })
                    });*/
                    <?php
                    }
                    ?>
                    $('.save_client_all').submit(function (event) {
                        event.preventDefault();
                        if (!checkalltags("")) {
                            return false;
                        }

                        $('.overlay-wrapper').show();
                        $('#save_client_p1').text('<?= $strings["forms_saving"];?>');
                        var str = '';
                        $('.moredocs').each(function () {
                            if ($(this).val() != "") {
                                if (str == '')
                                    str = 'client_doc[]=' + $(this).val();
                                else
                                    str = str + '&client_doc[]=' + $(this).val();
                            }

                        });
                        if (str == '') {
                            str = $('#tab_1_1 input').serialize();
                        }
                        else {
                            str = str + '&' + $('#tab_1_1 input').serialize();
                        }
                        if (str == '') {
                            str = $('#tab_1_1 select').serialize();
                        }
                        else {
                            str = str + '&' + $('#tab_1_1 select').serialize();
                        }
                        str = str + '&customer_type=' + $('#customer_type').val();
                        str = str + '&division=' + $('#division').val();
                        str = str + '&referred_by=' + $('#referred_by').val();
                        str = str + '&invoice_terms=' + $('#invoice_terms').val();
                        str = str + '&description=' + $('#description').val();

//alert(str);           

                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>clients/saveClients/<?php echo $id?>',
                            data: str,
                            type: 'post',
                            success: function (res) {

                                if (res != 'e' && res != 'email' && res != 'Invalid Email') {
                                    window.location = '<?php echo $this->request->webroot;?>clients/edit/' + res + '?flash';
                                }
                                else if (res == 'email') {
                                    alert('<?= addslashes($strings["dashboard_emailexists"]); ?>');
                                }
                                else if (res == 'Invalid Email') {
                                    $('#tab_1_1 input[type="email"]').focus();
                                    $('#tab_1_1 input[type="email"]').attr('style', 'border-color:red');
                                    $('html,body').animate({
                                            scrollTop: $('#tab_1_1').offset().top
                                        },
                                        'slow');
                                }

                                else {
                                    alert('<?= addslashes($strings["clients_notsaved"]); ?>');
                                }
                                $('#save_client_p1').text('<?= addslashes($strings["forms_save"]);?>');
                            }
                        })
                    });
                });

                $('#addMoredoc').click(function () {
                    var total_count = $('.docMore').data('count');
                    $('.docMore').data('count', parseInt(total_count) + 1);
                    total_count = $('.docMore').data('count');
                    var input_field = '<div  class="form-group"><div class="col-md-12" style="margin-top:10px;"><a href="javascript:void(0);" id="addMore' + total_count + '" class="btn btn-primary"><?= addslashes($strings["forms_browse"]); ?></a><input type="hidden" name="client_doc[]" value="" class="addMore' + total_count + '_doc moredocs" /><a href="javascript:void(0);" class = "btn btn-danger img_delete" id="delete_addMore' + total_count + '" title =""><?= $strings["dashboard_delete"];?></a><span></span></div></div>';
                    $('.docMore').append(input_field);
                    initiate_ajax_upload('addMore' + total_count, 'doc');

                });
                //delete image
                $('.img_delete').live('click', function () {
                    var file = $(this).attr('title');
                    if (file == file.replace("&", " ")) {
                        var id = 0;
                    }
                    else {
                        var f = file.split("&");
                        file = f[0];
                        var id = f[1];
                    }

                    var con = confirm('Are you sure you want to delete "' + file + '"?');
                    if (con == true) {
                        $.ajax({
                            type: "post",
                            data: 'id=' + id,
                            url: "<?php echo $this->request->webroot;?>clients/removefiles/" + file,
                            success: function (msg) {

                            }
                        });
                        $(this).parent().parent().remove();

                    }
                    else
                        return false;
                });
                var removeLink = 0;// this variable is for showing and removing links in a add document
                function addMore(e, obj) {
                    e.preventDefault();

                    var total_count = $('.docMore').data('count');
                    $('.docMore').data('count', parseInt(total_count) + 1);
                    total_count = $('.docMore').data('count');
                    var input_field = '<div style="display:block;margin:5px;"><a href="javascript;void(0);" id="addMore' + total_count + '" class="btn btn-primary"><?= addslashes($strings["forms_browse"]); ?></a><span></span><input type="hidden" name="client_doc[]" value="" class="addMore' + total_count + '_doc moredocs" /></div>';
                    $('.docMore').append(input_field);
                    if (parseInt(total_count) > 1 && removeLink == 0) {
                        removeLink = 1;
                        $('#addMoredoc').after('<a href="#" id="removeMore" class="btn btn-danger" onclick="removeMore(event,this)"><?= addslashes($strings["forms_removelast"]);?></a>');
                        initiate_ajax_upload('addMore' + total_count, 'doc');
                    }
                }

                function removeMore(e, obj) {
                    e.preventDefault();
                    var total_count = $('.docMore').data('count');
//$('.docMore input[type="file"]:last').remove();
                    $('.docMore div:last-child').remove();
                    $('.docMore').data('count', parseInt(total_count) - 1);
                    total_count = $('.docMore').data('count');
                    if (parseInt(total_count) == 1) {
                        $('#removeMore').remove();
                        removeLink = 0;
                    }
                }
            </script>


            <script>
                function initiate_ajax_upload(button_id, doc) {
                    var button = $('#' + button_id), interval;
                    if (doc == 'doc') {
                        var act = "<?php echo $this->request->webroot;?>clients/upload_all/<?php if(isset($id))echo $id;?>";
                    } else {
                        var act = "<?php echo $this->request->webroot;?>clients/upload_img/<?php if(isset($id))echo $id;?>";
                    }
                    new AjaxUpload(button, {
                        action: act,
                        name: 'myfile',
                        onSubmit: function (file, ext) {
                            button.text('<?= addslashes($strings["forms_uploading"]); ?>');
                            this.disable();
                            interval = window.setInterval(function () {
                                var text = button.text();
                                if (text.length < 13) {
                                    button.text(text + '.');
                                } else {
                                    button.text('<?= addslashes($strings["forms_uploading"]); ?>');
                                }
                            }, 200);
                        },
                        onComplete: function (file, response) {
                            if (doc == "doc") {
                                button.html('<?= addslashes($strings["forms_browse"]);?>');
                            } else {
                                button.html('<i class="fa fa-image"></i> <?= addslashes($strings["clients_addeditimage"]); ?>');
                            }

                            window.clearInterval(interval);
                            this.enable();
                            if (doc == "doc") {
                                $('#' + button_id).parent().find('span').text(" " + response);
                                $('.' + button_id + "_doc").val(response);
                                $('#delete_' + button_id).attr('title', response);
                            }
                            else {
                                $("#clientpic").attr("src", '<?php echo $this->request->webroot;?>img/jobs/' + response);
                                $('#client_img').val(response);
                            }
//$('.flashimg').show();
                        }
                    });
                }
            </script>