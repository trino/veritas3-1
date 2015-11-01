 <div class="col-md-12" style="text-align: right;">
                                <a href="#" class="btn btn-success"  style="margin:10px 0;" onclick="$('#sub_add').toggle(150);">Add New SubDocument</a>
                                <div class="col-md-12" id="sub_add" style="display: none;margin:10px 0;padding:0">
                                    <div class="col-md-10" style="text-align: right;padding:0;">
                                        <?php foreach($languages as $language){
                                            if($language == "English"){$language2 = "";} else {$language2 = $language;}
                                            echo '<input type="text" placeholder="Sub-Document ' . $language . ' title" class="form-control subdocname' . $language2 . '" />';
                                        } ?>
                                        <span class="error passerror flashSubDoc"
                                          style="display: none;">Subdocument name already exists</span>
                                        <span class="error passerror flashSubDoc1"
                                          style="display: none;">Please enter a subdocument name.</span>
                                    </div>
                                    <div class="col-md-2" style="text-align: right;padding:0;">
                                        <a class="btn btn-primary addsubdoc" href="javascript:void(0)">Add</a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                                        <div class="clearfix"></div>
                    <?php $subdoc = $this->requestAction('/clients/getSub'); ?>
                        <table class="table table-light table-hover sortable">
                            <tr class="myclass">
                                <?php foreach($languages as $language) {
                                    echo '<th>' . $language . ' Title</th>';
                                }

                                echo '<th class="" colspan="2">Action</th></tr>';

                                foreach($subdoc as $sub) {
                                    echo '<TR>';
                                    foreach($languages as $language){
                                        if($language == "English"){$language = "";}
                                        echo '<TD><span>' . ucfirst($sub['title' . $language]) . '</span></TD>';
                                    }
                                ?>
                                <td>
                                    <a href="javascript:void(0)" class="btn-xs btn-success"
                                       onclick="$('#edit_sub_<?php echo $sub['id']; ?>').toggle(150);$('.msg').hide();">Edit</a>
                                </td>
                                <td>
                                    <div class="col-md-12" id="edit_sub_<?php echo $sub['id']; ?>"
                                         style="display: none;margin:10px 0;padding:0">
                                        <div class="col-md-12" style="text-align: right;padding:0;">
                                    <?php
                                    foreach($languages as $language){
                                        if($language == "English"){$language2 = "";} else { $language2 = $language;}

                                            echo '<input type="text" id="editsubdocname' . $language2 . '_' . $sub['id'] . '"
                                                   value="' . ucfirst($sub['title' . $language2]) . '"
                                                   placeholder="Sub-Document ' . $language . ' title"
                                                   class="form-control editsubdocname"/>';
                                   } ?>
                                            <span class="error" id="flasheditSub_<?php echo $sub['id']; ?>"
                                                  style="display: none;">Subdocument name already exists</span>
                                            <span class="error" id="flasheditSub1_<?php echo $sub['id']; ?>"
                                                  style="display: none;">Please enter a subdocument name.</span>
                                        </div>
                                        <br/><br/>

                                        <div class="col-md-12" style="text-align: right;padding:0;">
                                            <select class="form-control" id="select_color_<?php echo $sub['id']; ?>">
                                                <option value="">Select a color class</option>
                                                <?php
                                                $color = $this->requestAction('clients/getColorClass');
                                                if ($color) {
                                                    foreach ($color as $c) {
                                                        ?>
                                                        <option
                                                            value="<?php echo $c->id; ?>" <?php if (isset($sub['color_id']) && $sub['color_id'] == $c->id) { ?> selected="selected"<?php } ?>
                                                            style="background: <?php echo $c->rgb; ?>;"><?php echo $c->color; ?></option>
                                                    <?php
                                                    }
                                                }
                                                makeselect();

                                                makeselect($is_disabled, "select_icon_" . $sub['id']);
                                                makedropdownoption("", "Default", $sub["icon"]);
                                                makedropdownoption("fa icon-footprint", "Footprint", $sub["icon"]);
                                                makedropdownoption("fa icon-surveillance", "Surveillance", $sub["icon"]);
                                                makedropdownoption("fa icon-physical", "Physical", $sub["icon"]);
                                                makeselect();

                                                makeselect($is_disabled, "select_product_" . $sub['id']);
                                                foreach ($products as $product){
                                                    makedropdownoption($product->number, $product->title, $sub["ProductID"]);
                                                }
                                            makeselect();
                                             ?>




                                            <span class="error" id="flashSelectColor_<?php echo $sub['id']; ?>"
                                              style="display: none; width: auto;">Please  select a color.</span>
                                          </div> <br /> <br />
                                        <div class="col-md-12" style="text-align: right;padding:0;">
                                            <a class="btn-xs btn-primary editsubdoc" id="subbtn<?php echo $sub['id']; ?>" href="javascript:void(0)">Save</a>
                                            <a href="?DeleteDoc=<?php echo $sub['id']; ?>" class="btn-xs btn-danger deletesubdoc" id="delsubbtn" onclick="return confirm('Are you sure you want to delete <?= $sub['title'] ?>?');">Delete</a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <span id="msg_<?php echo $sub['id']; ?>"></span>
                                <!--</div>-->
                            </td>
                            </tr>
                                    <?php
                                }