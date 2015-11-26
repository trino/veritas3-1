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
                if(isset($subcl->sub_id)){
                    $ID = $subcl->sub_id;
                } else {
                    $ID = $subcl->id;
                }
                $u++;
                $sub = $this->requestAction('/clients/getFirstSub/' . $ID);

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
                if(isset($subcl->sub_id)){
                    $ID = $subcl->sub_id;
                } else {
                    $ID = $subcl->id;
                }
                $sub = $this->requestAction('/clients/getFirstSub/' . $ID);
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