<style>div {
        border: 0px solid green;
    }</style>
<?php
    if ($this->request->session()->read('debug')) {
        echo "<span style ='color:red;'>subpages/profile/info_order.php #INC152</span>";
    }

    $getProfileType = $this->requestAction('profiles/getProfileType/' . $this->Session->read('Profile.id'));
    $profiletypes = $this->requestAction('profiles/getProfileTypes/' . $language);
    $sidebar = $Manager->loadpermissions($Me, "sidebar");
    $strings2 = CacheTranslations($language, array("info_%", "profiles_profiletype"), $settings, False);

    function printoption($option, $selected, $value = "")
    {
        $tempstr = "";
        if ($option == $selected || $value == $selected) {
            $tempstr = " selected";
        }
        if (strlen($value) > 0) {
            $value = " value='" . $value . "'";
        }
        echo '<option' . $value . $tempstr . ">" . $option . "</option>";
    }

    function printoption2($value, $selected = "", $option)
    {
        $tempstr = "";
        if ($option == $selected or $value == $selected) {
            $tempstr = " selected";
        }
        echo '<OPTION VALUE="' . $value . '"' . $tempstr . ">" . $option . "</OPTION>";
    }

    function printoptions($name, $valuearray, $selected = "", $optionarray, $isdisabled = "")
    {
        echo '<SELECT ' . $isdisabled . ' name="' . $name . '" class="form-control member_type" >';
        for ($temp = 0; $temp < count($valuearray); $temp += 1) {
            printoption2($valuearray[$temp], $selected, $optionarray[$temp]);
        }
        echo '</SELECT>';
    }

    function printprovinces($name, $selected = "", $isdisabled = "disabled='disabled'")
    {
        printoptions($name, getprovinces("Acronyms"), $selected, getprovinces(""), $isdisabled);
    }

?>

<div>

    <div class="portlet-body">
        <div class="createDriver">
            <div class="portlet box form">
                <input type="hidden" name="document_type" value="add_driver"/>

                <form role="form" action="" method="post" id="createDriver">

                    <input type="hidden" name="client_ids" value="<?php echo $cid; ?>" class="client_profile_id"/>
                    <input type="hidden" name="id" value="<?php if (isset($p->id)) echo $p->id; else echo 0; ?>"
                           class="driver_id"/>

                    <div class="row">
                        <div class="col-md-3">

                            <div style="display:inline-block;border-radius:30px;"><img id="clientpic"
                                                                                       class="img-responsive"
                                                                                       style="height: auto;width: 150px;margin-left:15px;"
                                                                                       alt=""
                                                                                       src="<?php echo $this->request->webroot; ?>img/profile/default.png"/>
                            </div>
                        </div>
                        <div class="col-md-9">

                            <div class="clearfix"></div>
                            <input type="hidden" name="created_by"
                                   value="<?= $this->request->session()->read('Profile.id') ?>"/>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"><?= $strings2["profiles_profiletype"]; ?>:</label>

                                    <select name="profile_type" class="form-control member_type required"
                                            disabled="disabled">
                                        <?php
                                            foreach ($profiletypes as $Key => $Value) {
                                                if (!strpos($Key, ".")) {
                                                    printoption($Value, $p->profile_type, $Key);
                                                }
                                                //

                                            }
                                        ?>
                                    </select>

                                </div>
                            </div>

                            <?php if ($sidebar->client_option == 0 && $p->profile_type != 11 && $this->requestAction('clients/assignedTo/17/' . $this->request->session()->read('Profile.id'))/*&& (isset($p) && $p->profile_type == 5)*/) { ?>
                                <?php if (isset($p) && ($p->profile_type == 5 || $p->profile_type == 7 || $p->profile_type == 8)) { ?>
                                    <div class="col-md-4" id="driver_div"
                                         style="">
                                        <div class="form-group">
                                            <label class="control-label"><?= $strings["forms_drivertype"]; ?>:</label>
                                            <select name="driver" class="form-control select_driver">
                                                <option value=""><?= $strings["forms_selectdrivertype"]; ?></option>
                                                <option
                                                    value="1" <?php if (isset($p) && $p->driver == 1) echo "selected='selected'"; ?>
                                                    >BC - BC FTL AB/BC
                                                </option>
                                                <option value="2"
                                                    <?php if (isset($p) && $p->driver == 2) echo "selected='selected'"; ?>>
                                                    BCI5 - BC FTL I5
                                                </option>
                                                <option value="3"
                                                    <?php if (isset($p) && $p->driver == 3) echo "selected='selected'"; ?>>
                                                    BULK
                                                </option>
                                                <option value="4"
                                                    <?php if (isset($p) && $p->driver == 4) echo "selected='selected'"; ?>>
                                                    CLIMATE
                                                </option>
                                                <option value="5"
                                                    <?php if (isset($p) && $p->driver == 5) echo "selected='selected'"; ?>>
                                                    FTL - SINGLE DIVISION
                                                </option>
                                                <option value="6"
                                                    <?php if (isset($p) && $p->driver == 6) echo "selected='selected'"; ?>>
                                                    FTL - TOYOTA SINGLE HRLY
                                                </option>
                                                <option value="7"
                                                    <?php if (isset($p) && $p->driver == 7) echo "selected='selected'"; ?>>
                                                    FTL - TOYOTA SINGLE HWY
                                                </option>
                                                <option value="8"
                                                    <?php if (isset($p) && $p->driver == 8) echo "selected='selected'"; ?>>
                                                    LCV - LCV UNITS
                                                </option>
                                                <option value="9"
                                                    <?php if (isset($p) && $p->driver == 9) echo "selected='selected'"; ?>>
                                                    LOC - LOCAL
                                                </option>
                                                <option value="10"
                                                    <?php if (isset($p) && $p->driver == 10) echo "selected='selected'"; ?>>
                                                    OWNER - OPERATOR
                                                </option>
                                                <option value="11"
                                                    <?php if (isset($p) && $p->driver == 11) echo "selected='selected'"; ?>>
                                                    OWNER - DRIVER
                                                </option>
                                                <option value="12"
                                                    <?php if (isset($p) && $p->driver == 12) echo "selected='selected'"; ?>>
                                                    SCD - SPECIAL COMMODITIES
                                                </option>
                                                <option value="13"
                                                    <?php if (isset($p) && $p->driver == 13) echo "selected='selected'"; ?>>
                                                    SST-SANDRK- OPEN FUEL
                                                </option>
                                                <option value="14"
                                                    <?php if (isset($p) && $p->driver == 14) echo "selected='selected'"; ?>>
                                                    SWD-SANDRK
                                                </option>
                                                <option value="15"
                                                    <?php if (isset($p) && $p->driver == 15) echo "selected='selected'"; ?>>
                                                    TBL-TRANSBORDER
                                                </option>
                                                <option value="16"
                                                    <?php if (isset($p) && $p->driver == 16) echo "selected='selected'"; ?>>
                                                    TEM - TEAM DIVISION
                                                </option>
                                                <option value="17"
                                                    <?php if (isset($p) && $p->driver == 17) echo "selected='selected'"; ?>>
                                                    TEM - TOYOTA TEAM
                                                </option>
                                                <option value="18"
                                                    <?php if (isset($p) && $p->driver == 18) echo "selected='selected'"; ?>>
                                                    WD - Wind
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                <?php }
                            } ?>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"><?= $strings["forms_email"]; ?>:</label>
                                    <input <?php echo $is_disabled ?> name="email" id="driverEm" type="email"
                                                                      placeholder="eg. test@domain.com" role="email"
                                                                      class="form-control un email required" <?php if (isset($p->email)) { ?> value="<?php echo $p->email; ?>" <?php } ?>/>
                            <span class="error passerror flashEmail"
                                  style="display: none;"><?= $strings["dashboard_emailexists"]; ?></span>
                                </div>
                            </div>
                            <div class="clearfix flashEmail" style="display: none;">
                            </div>


                            <div class="clearfix">
                            </div>
                            <?php if ($sidebar->client_option == 0) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?php
                                        $title = "";
                                        if (isset($p->title)) {
                                            $title = $p->title;
                                        }
                                        selecttitle($language, $strings, "title", $title, $is_disabled);//$language, $strings, $name, $title, $is_disabled
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">

                                    <label class="control-label"><?= $strings["forms_firstname"]; ?>:</label>
                                    <input <?php echo $is_disabled ?> name="fname" type="text"
                                                                      placeholder="eg. John"
                                                                      class="form-control req_driver required" <?php if (isset($p->fname)) { ?> value="<?php echo $p->fname; ?>" <?php } ?>/>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">

                                    <label class="control-label"><?= $strings["forms_middlename"]; ?>:</label>
                                    <input <?php echo $is_disabled ?> name="mname" type="text"
                                                                      placeholder=""
                                                                      class="form-control" <?php if (isset($p->mname)) { ?> value="<?php echo $p->mname; ?>" <?php } ?>/>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"><?= $strings["forms_lastname"]; ?>:</label>
                                    <input <?php echo $is_disabled ?> name="lname" type="text"
                                                                      placeholder="eg. Doe"
                                                                      class="form-control req_driver required" <?php if (isset($p->lname)) { ?> value="<?php echo $p->lname; ?>" <?php } ?>/>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">

                                    <label class="control-label"><?= $strings["forms_phone"]; ?>:</label>
                                    <input <?php echo $is_disabled ?> name="phone" type="text" role="phone"
                                                                      placeholder="eg. (646)580-6284"
                                                                      class="form-control req_driver required" <?php if (isset($p->phone)) { ?> value="<?php echo $p->phone; ?>" <?php } ?>/>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">

                                    <label class="control-label"><?= $strings["forms_gender"]; ?>:</label>
                                    <SELECT <?php echo $is_disabled ?> name="gender" class="form-control "><?php
                                            $gender = "";
                                            if (isset($p->gender)) {
                                                $gender = $p->gender;
                                            }
                                            printoption($strings["forms_selectgender"], "");
                                            printoption($strings["forms_male"], $gender, "Male");
                                            printoption($strings["forms_female"], $gender, "Female");
                                        ?></SELECT>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">

                                    <label class="control-label"><?= $strings["forms_placeofbirth"]; ?>:</label>
                                    <input <?php echo $is_disabled ?> name="placeofbirth" type="text"
                                                                      placeholder=""
                                                                      class="form-control" <?php if (isset($p->placeofbirth)) { ?> value="<?php echo $p->placeofbirth; ?>" <?php } ?>/>
                                </div>
                            </div>

                            <div class="col-md-8">

                                <div class="form-group">
                                    <label class="control-label"><?= $strings["forms_dateofbirth"]; ?>:
                                        (<?= $strings["forms_dateformat"]; ?>)</label><BR>

                                    <div class="row">


                                        <div class="col-md-4 no-margin">
                                            <?php

                                                $currentyear = "0000";
                                                $currentmonth = 0;
                                                $currentday = 0;

                                                if (isset($p->dob)) {
                                                    $currentyear = substr($p->dob, 0, 4);
                                                    $currentmonth = substr($p->dob, 5, 2);
                                                    $currentday = substr($p->dob, -2);
                                                }

                                                echo '<select class="form-control req_driver required" NAME="doby" ' . $is_disabled . '>';

                                                $now = date("Y");
                                                for ($temp = $now; $temp > 1899; $temp -= 1) {
                                                    printoption($temp, $currentyear, $temp);
                                                }
                                                echo '</select></div><div class="col-md-4">';

                                                echo '<select  class="form-control req_driver required" NAME="dobm" ' . $is_disabled . '>';
                                                $monthnames = array("Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec");
                                                for ($temp = 1; $temp < 13; $temp += 1) {
                                                    if ($temp < 10)
                                                        $temp = "0" . $temp;
                                                    printoption($temp, $currentmonth, $temp);
                                                }
                                                echo '</select></div><div class="col-md-4">';

                                                echo '<select class="form-control req_driver required" name="dobd" ' . $is_disabled . '>';
                                                for ($temp = 1; $temp < 32; $temp++) {
                                                    if ($temp < 10)
                                                        $temp = "0" . $temp;
                                                    printoption($temp, $currentday, $temp);
                                                }

                                                echo '</select></div>';
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h3 class="block"><?= $strings["forms_address"]; ?></h3>
                                    </div>
                                </div>


                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input <?php echo $is_disabled ?> name="street" type="text"
                                                                          placeholder="<?= $strings["forms_address"]; ?>"
                                                                          class="form-control req_driver required" <?php if (isset($p->street)) { ?> value="<?php echo $p->street; ?>" <?php } ?>/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input <?php echo $is_disabled ?> name="city" type="text"
                                                                          placeholder="<?= $strings["forms_city"]; ?>"
                                                                          class="form-control req_driver required" <?php if (isset($p->city)) { ?> value="<?php echo $p->city; ?>" <?php } ?>/>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php
                                            if (isset($p->province))
                                                printprovinces("province", $p->province, $is_disabled);
                                            else
                                                printprovinces("province", "", $is_disabled);
                                        ?>


                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input <?php echo $is_disabled ?> type="text"
                                                                          placeholder="<?= $strings["forms_postalcode"]; ?>"
                                                                          class="form-control req_driver required"
                                                                          role="postalcode"
                                                                          name="postal" <?php if (isset($p->postal)) { ?> value="<?php echo $p->postal; ?>" <?php } ?>/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input <?php echo $is_disabled ?> type="text"
                                                                          placeholder="<?= $strings["forms_country"]; ?>"
                                                                          class="form-control req_driver required"
                                                                          name="country"
                                                                          value="<?php if (isset($p->country)) {
                                                                              echo $p->country;
                                                                          } else {
                                                                              echo 'Canada';
                                                                          } ?>"/>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h3 class="block"><?= $strings["forms_driverslicense"]; ?></h3></div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label"><?= $strings["forms_driverslicense"]; ?> #:</label>
                                        <input <?php echo $is_disabled ?> name="driver_license_no" type="text"
                                                                          class="form-control req_driver" <?php if (isset($p->driver_license_no)) { ?> value="<?php echo $p->driver_license_no; ?>" <?php } ?>
                                                                          disabled="disabled"/>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label"><?= $strings["forms_provinceissued"]; ?>:</label>

                                        <?php
                                            if (isset($p->driver_province)) {
                                                printprovinces("driver_province", $p->driver_province, $is_disabled);
                                            } else {
                                                printprovinces("driver_province", "", $is_disabled);
                                            }
                                        ?>


                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label"><?= $strings["forms_expirydate"]; ?>:</label>
                                        <input <?php echo $is_disabled ?> name="expiry_date" type="text"
                                                                          class="form-control date-picker" <?php if (isset($p->expiry_date)) { ?> value="<?php echo $p->expiry_date; ?>" <?php } ?>
                                                                          disabled="disabled"/>

                                    </div>
                                </div>
                                <?php }
                                    else {
                                        ?>
                                        <input type="hidden" name="doby" value="0000"/>
                                        <input type="hidden" name="dobm" value="00"/>
                                        <input type="hidden" name="dobd" value="00"/>
                                        <?php
                                    }
                                ?>


                </form>

                <div class="clearfix"></div>


            </div>
        </div>


    </div>


</div>


</div>
</div>

<script>

    $(function () {

        $('#addmore_id').click(function () {
            $('#more_id_div').append('<div id="append_id"><div class="pad_bot"><a href="" class="btn btn-primary">Browse</a> <a href="javascript:void(0);" id="delete_id_div" class="btn btn-danger">Delete</a></div></div>')
        });

        $('#delete_id_div').live('click', function () {
            $(this).closest('#append_id').remove();
        })

        $('#addmore_trans').click(function () {
            $('#more_trans_div').append('<div id="append_trans"><div class="pad_bot"><a href="" class="btn btn-primary">Browse</a> <a href="javascript:void(0);" id="delete_trans_div" class="btn btn-danger">Delete</a></div></div>')
        });

        $('#delete_trans_div').live('click', function () {
            $(this).closest('#append_trans').remove();
        })

        $('.member_type').change(function () {
            if ($(this).val() == '5' || $(this).val() == '7' || $(this).val() == '8') {
                $('.nav-tabs li:not(.active)').each(function () {
                    $(this).hide();
                });
                $('#driver_div').show();
                $('#driver_div select').addClass('required');
                $('.un').removeProp('required');
                $('#password').removeProp('required');
                $('#retype_password').removeProp('required');
                $('.req_rec').removeProp('required');
                $('.req_driver').prop('required', "required");
            }
            else {
                $('#driver_div select').removeClass('required');
                $('.nav-tabs li:not(.active)').each(function () {
                    $(this).show();
                });
                $('#driver_div').hide();
                $('.req_driver').removeProp('required');
                $('.req_rec').removeProp('required');
                $('.un').prop('required', "required");
            }

            if ($(this).val() == '2') {
                $('.req_driver').removeProp('required');
                $('.un').removeProp('required');
                $('.req_rec').prop('required', "required");
            }

        });
    });
</script>


<!-- END PORTLET-->