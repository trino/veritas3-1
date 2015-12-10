<h3>Confirmation</h3>
<?php
    if ($this->request->session()->read('debug')) {
        echo "<span style ='color:red;'>subpages/documents/confirmation.php #INC138</span>";
    }
    $forms = '';
    if (isset($_GET['forms'])) {
        $forms = $_GET['forms'];
    } else
        $forms = $this->requestAction('/orders/getProNum');

    $dri = $this->requestAction('/clientApplication/getfullname/' . $_GET['driver']);

    $allattachments = array();// new AppendIterator;
    if (isset($pre_at['attach_doc'])) {
        $allattachments = merge($allattachments, $pre_at['attach_doc']);
    }
    if (isset($sub['da_at'])) {
        $allattachments = merge($allattachments, $sub['da_at']);
    }
    if (isset($sub['de_at'])) {
        $allattachments = merge($allattachments, $sub['de_at']);
    }
    if (isset($sub2['con_at'])) {
        $allattachments = merge($allattachments, $sub2['con_at']);
    }
    if (isset($sub3['att'])) {
        $allattachments = merge($allattachments, $sub3['att']);
    }
    if (isset($sub4['att'])) {
        $allattachments = merge($allattachments, $sub4['att']);
    }

    function merge($dest, $src)
    {
        if (is_iterable($src)) {
            foreach ($src as $item) {
                $dest[] = $item;
            }
        }
        return $dest;
        //if (is_object($src)) { $dest->append($src); }
        //if (is_array($src)) { $dest = array_merge($dest, $src); }
        //return dest;
    }

    $forms_arr = explode(',', $forms);
    $p = $forms_arr;

    $strings2 = CacheTranslations($language, array("score_products", "confirm_%", "forms_signplease"), $settings, False);
    //confirm_confirm
?>
<!--div class="note note-success">
    <h3 class="block col-md-12" style="margin-bottom: 0;padding: 0;font-size: 20px;"><?= ProcessVariables($language, $strings2["confirm_confirm"], array("name" => getpost("order_type"))); ?></h3>

    <div class="clearfix"></div>
</div-->

<input type="hidden" id="confirmation" value="1"/>
<input class="document_type" type="hidden" name="document_type" value="Confirmation"/>
<input type="hidden" class="sub_docs_id" name="sub_doc_id" value="c1"/>

<div class="row">


    <div class="form-group">
        <label class="control-label col-md-12"><?= $strings["documents_submittedby"]; ?>: </label>

        <div class="col-md-6">
            <input disabled="disabled" type="text" class="form-control" name="conf_recruiter_name"
                   id="conf_recruiter_name"
                   value="<?php if (isset($modal->conf_recruiter_name)) echo $modal->conf_recruiter_name; else echo $this->request->session()->read('Profile.fname') . ' ' . $this->request->session()->read('Profile.lname'); ?>"/>
        </div>


        <label class="control-label col-md-12" style="margin-top: 5px;"><?= $strings["documents_submittedfor"]; ?>
            : </label>

        <div class="col-md-6">
            <input type="text" class="form-control" name="conf_driver_name" id="conf_driver_name"
                   value="<?php echo $dri; ?>" readonly=""/>
        </div>


        <label class="control-label col-md-12" style="margin-top: 5px;"><?= $strings["forms_datetime"]; ?>: </label>

        <div class="col-md-6">
            <input disabled="disabled" type="text" class="form-control date-picker" name="conf_date" id="conf_date"
                   value="<?php if (isset($modal->created)) echo $modal->created; else {
                       echo date('Y-m-d  H:i:s');
                   } ?>"/>
        </div>


    <div class="clearfix"></div>

        <div class="col-md-6 " style="margin-top: 5px;">

    <label><?= $strings2["score_products"]; ?>:</label>

    <div class="clearfix"></div>

    <?php
        $lineclass = "even";//set to "" for old list, even or odd to table

        if ($lineclass == "") {
        } else {
            echo '<table class="table" style="margin-bottom: 0px;"><tbody>';
        }

        function PrintLine($lineclass, $name, $id, $cnt)
        {
            if ($cnt) {
                $check = "<input ";
                if ($cnt) {
                    $check .= 'checked ';
                }
                $check .= 'disabled="disabled" type="checkbox" name="' . $id . '" value=""/>';

                if ($lineclass == "") {
                    echo '<li><div class="col-md-10"><i class="fa fa-file-text-o"></i> ' . $name . '</div><div class="col-md-2">';
                    echo $check . '</div><div class="clearfix"></div></li>';
                    return "";
                }

                echo '<tr class="' . $lineclass . '" role="row"><td width="45"><i class="fa fa-file-text-o"></i></td>';
                echo '<td>' . $name . '</td></tr>';
                if ($lineclass == "even") {
                    return "odd";
                } else {
                    return "even";
                }
            }
            return $lineclass;
        }

        if ($p) {
            $fieldname = getFieldname("title", $language);
            foreach ($p as $pp) {
                $title = $this->requestAction('/orders/getProductTitle/' . $pp);
                if (is_object($title)) {
                    $lineclass = PrintLine($lineclass, $title->$fieldname . $Trans, "prem_nat", $pp);
                }
            }
        }
        if ($lineclass == "") {
            // echo '</ul>';
        } else {
            echo '<TR><TD colspan="3"></TD></TR></tbody></table>';
        }

    ?>

</div>


    <p>&nbsp;</p>
</div>
</div>


<div class="row conf_block">
    <div class="form-group">

        <label class="control-label  col-md-6"><?= $strings2["forms_signplease"]; ?>:</label>
        <input type="hidden" name="recruiter_signature" id="recruiter_signature"
               value="<?php if (isset($modal->recruiter_signature) && $modal->recruiter_signature) echo $modal->recruiter_signature; ?>"/>

        <?php
            include('canvas/confirmation_signature.php');
        ?>

    </div>
    <div class="clearfix"></div>
    <?php if ($this->request->params['action'] == 'vieworder' || $this->request->params['action'] == 'view') {
        //no
    } else {
        ?>
        <div class="note note-success" style="display: none;">

            <label for="confirm_check" style="margin: 0;">
                <h4 style="line-height: 120%;">
                    <input type="checkbox" class="form-control" value="1" id="confirm_check1" name="confirm_check"
                           checked="checked"/>
                    I confirm that I have read and understand the <a
                        href="<?php echo $this->request->webroot; ?>pages/view/terms" target="_blank">Terms &
                        Conditions.</a></h4></label>
        </div>
        <?php
    } ?>
</div>


<div class="clearfix"></div>


<!-- DONT REMOVE / USED FOR WEBSERVICE .... DO NOT CHANGE THE STRUCTURE OF DIV INSIDE NOT EVEN AN ENTER-->
<div class="attachments_all" style="display: none;">
    <?php //THIS SHOULD BE USING FILELIST.PHP!!!!!

        function listattachments($name, $array)
        {
            echo '<div class="' . $name . '">';
            $c1 = 0;
            foreach ($array as $pat) {
                $c1++;
                if ($c1 == 1) {
                    echo $pat->attachment;
                } else {
                    echo ',' . $pat->attachment;
                }
            }
            echo '</div>';
        }

        if (isset($pre_at['attach_doc'])) {
            listattachments("pre", $pre_at['attach_doc']);
        }
        if (isset($sub['da_at'])) {
            listattachments("da", $sub['da_at']);
        }
        if (isset($sub['de_at'])) {
            listattachments("de", $sub['de_at']);
        }
        if (isset($sub2['con_at'])) {
            listattachments("con", $sub2['con_at']);
        }
        if (isset($sub3['att'])) {
            listattachments("emp", $sub3['att']);
        }
        if (isset($sub4['att'])) {
            listattachments("edu", $sub4['att']);
        }
    ?>
</div>


<script>
    $(function () {
        <?php if($this->request->params['action'] != 'vieworder'  && $this->request->params['action']!= 'view'){?>
        $("#test1").jqScribble();
        <?php }?>
    });

    function addImage() {
        var img = prompt("Enter the URL of the image.");
        if (img !== '')$("#test").data("jqScribble").update({backgroundImage: img});
    }
</script>