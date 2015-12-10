<div class="row">
    <div class="col-md-12">
        <div class="col-md-6 clients_select" style="margin: 10px 0;padding:0">

                    <select name="clients" class="form-control select2me" data-placeholder="Select Client" id="changeclient">
                        <option value="0">Select Client</option>
                        <?php
                        $profile_id = $this->request->session()->read('Profile.id');
                        foreach ($clients as $c){
                            $profiles = explode(",", $c->profile_id);

                            if(in_array($profile_id, $profiles)|| $this->request->session()->read('Profile.super'))
                            { ?>
                                <option value="<?php echo $c->id;?>" <?php if($cid ==$c->id)echo "selected='selected'";?>><?php echo $c->company_name;?></option>
                            <?php
                            }
                        }
                        ?>
                    </select>

                </div>
                <div class="clearfix"></div>
                <div class="col-md-6" style="margin: 10px 0;padding:0">

                    <?php $dr_cl = $doc_comp->getDriverClient(0, $cid);?>
                    <select class="form-control select2me" data-placeholder="No Driver"
                            id="selecting_driver" <?php if ($driver){ ?>disabled="disabled"<?php } ?>>
                        <option value="0">No Driver
                        </option>
                        <?php


                        foreach ($dr_cl['driver'] as $dr) {

                            $driver_id = $dr->id;
                            ?>
                            <option value="<?php echo $dr->id; ?>"
                                    <?php if ($dr->id == $driver){ ?>selected="selected"<?php } ?>><?php echo $dr->fname . ' ' . $dr->mname . ' ' . $dr->lname ?></option>
                        <?php
                        }
                        ?>
                    </select>

                    <input type="hidden" name="did" value="<?php echo $did; ?>" id="did"/>
                    <?php
                    if(isset($_GET['doc']))
                    {
                        $sid = 4;
                    }
                    ?>
                    <input type="hidden" name="sub_doc_id" value="<?php echo $sid; ?>" id="sub_id"/>

                </div>
    </div>
</div>