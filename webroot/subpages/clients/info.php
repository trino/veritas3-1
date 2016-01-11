<form role="form" action="" method="post" id="client_form" class="save_client_all">
    <input type="hidden" name="drafts" id="client_drafts" value="0"/>

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
            <label class="control-label required"> <?php echo ($settings->client_option == 0) ? $strings["forms_companyname"] : $strings["forms_eventname"]; ?>
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

            function printoptions($name, $valuearray, $selected, $optionarray) {
                echo '<SELECT name="' . $name . '" class="form-control member_type" >';
                for ($temp = 0; $temp < count($valuearray); $temp += 1) {
                    printoption($valuearray[$temp], $selected, $optionarray[$temp]);
                }
                echo '</SELECT>';
            }

            function printprovinces($name, $selected, $Language = "English") {
                $acronyms = getprovinces("Acronyms", True);
                $provinces = getprovinces($Language, True);
                printoptions($name, $provinces, $selected,  $acronyms);
            }

            printprovinces("province", $client->province, $language);
            ?>

        </div>
        <?php if ($settings->client_option == 0) { ?>
            <div class="form-group col-md-4">
                <label class="control-label"><?= $strings["forms_postalzip"]; ?>:</label>
                <input type="text" class="form-control"
                       role='postalzip'
                       name="postal" <?php if (isset($client->postal)) { ?> value="<?php echo $client->postal; ?>" <?php } ?>/>
            </div>
            <div class="form-group col-md-4">
                <label class="control-label"><?= $strings["forms_companyphone"]; ?>:</label>
                <input type="text" class="form-control"
                       id="company_phone"
                       name="company_phone"
                       role='phone' <?php if (isset($client->company_phone)) { ?> value="<?php echo $client->company_phone; ?>" <?php } ?>
                    />
            </div>
        <?php } ?>
        <div class="form-group col-md-4">
            <label class="control-label"><?= $strings["forms_website"]; ?>:</label>
            <input type="text" class="form-control"
                   name="site" <?php if (isset($client->site)) { ?> value="<?php echo $client->site; ?>" <?php } ?>/>
        </div>
        <?php if ($settings->client_option == 0) { ?>
            <div class="form-group col-md-4">
                <label class="control-label"><?= $strings["forms_divisions"]; ?>:</label>
                                                                    <textarea name="division" id="division"
                                                                              placeholder="<?= $strings["forms_oneperline"]; ?>"
                                                                              class="form-control"><?php if (isset($client->division)) echo $client->division; ?></textarea>
            </div>

            <div class="form-group col-md-4">
                <label class="control-label"><?= $strings["forms_signatoryfirstname"]; ?>:</label>
                <input type="text" class="form-control"
                       name="sig_fname" <?php if (isset($client->sig_fname)) { ?> value="<?php echo $client->sig_fname; ?>" <?php } ?>/>
            </div>
            <div class="form-group col-md-4">
                <label class="control-label"><?= $strings["forms_signatorylastname"]; ?>:</label>
                <input type="text" class="form-control"
                       name="sig_lname" <?php if (isset($client->sig_lname)) { ?> value="<?php echo $client->sig_lname; ?>" <?php } ?>/>
            </div>

            <div class="form-group col-md-4">
                <label class="control-label"><?= $strings["forms_signatoryemail"]; ?>:</label>
                <input type="email" id="sig_email"
                       class="form-control" role="email"
                       name="sig_email" <?php if (isset($client->sig_email)) { ?> value="<?php echo $client->sig_email; ?>" <?php } ?>/>
            </div>


            <div class="form-group col-md-4">
                <label class="control-label"><?= $strings["forms_startdate"]; ?>:</label>
                <input type="text" class="form-control date-picker"
                       name="date_start" <?php if (isset($client->date_start)) { ?> value="<?php echo $client->date_start; ?>" <?php } ?>/>
            </div>
            <div class="form-group col-md-4">
                <label class="control-label"><?= $strings["forms_enddate"]; ?>:</label>
                <input type="text" class="form-control date-picker"
                       name="date_end" <?php if (isset($client->date_end)) { ?> value="<?php echo $client->date_end; ?>" <?php } ?>/>
            </div>

            <!--div class="form-group col-md-4">
                                                                    <label class="control-label">Date</label>
                                                                    <input type="text" class="form-control date-picker"
                                                                           name="client_date" <?php if (isset($client->client_date)) { ?> value="<?php echo $client->client_date; ?>" <?php } ?>/>
                                                                </div-->


            <?php
            if ($settings->mee == "MEE") { ?>
                <div class="form-group col-md-4">
                    <label class="control-label"><?= $strings["forms_referredby"]; ?>:</label>
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
                        class="control-label"><?= $strings["forms_billingprovince"]; ?>
                        :</label>
                    <?php printprovinces("province", $client->billing_province, $language); ?>


                </div>
                <div class="form-group col-md-4">
                    <label class="control-label"><?= $strings["forms_billingpostalcode"]; ?>:</label>
                    <input type="text" class="form-control"
                           role="postalzip"
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
            <a href="javascript:void(0)" class="btn btn-primary"
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
                <!--button type="submit" class="btn btn-primary" onclick="$('#client_drafts').val('1',function(){$('#save_client_p1').click();});">Save As Draft</button-->
            </div>
        </div>


</form>