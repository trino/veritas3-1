<?php
    use Cake\ORM\TableRegistry;
    if ($this->request->session()->read('debug')) {
        echo "<BR><span style ='color:red;'>filelist.php #INC158</span>";
    }
    $GLOBALS['webroot'] = $webroot = $this->request->webroot;

    $language = $this->request->session()->read('Profile.language');
    $settings = $Manager->get_settings();
    include_once("api.php");
    $GLOBALS["strings"] = CacheTranslations($language, array("file_%", "orders_ordertype", "documents_submittedby", "documents_submittedfor", "settings_client", "dashboard_delete", "forms_attachedfiles"), $settings, False);//,$registry);//$registry = $this->requestAction('/settings/getRegistry');

//other values PATHINFO_DIRNAME (/mnt/files) | PATHINFO_BASENAME (??????.mp3) | PATHINFO_FILENAME (??????)

    function getattachments($OrderID) {
        $all_attachments = TableRegistry::get('doc_attachments');
        return $all_attachments->find()->where(['order_id' => $OrderID]);
    }

    function loadclient($ClientID, $table = "clients") {
        $table = TableRegistry::get($table);
        $results = $table->find('all', array('conditions' => array('id' => $ClientID)))->first();
        return $results;
    }

    function getdocumentinfo($ID, $isOrder = false) {
        if ($isOrder) {
            $data = loadclient($ID, "orders");
        } else {
            $data = loadclient($ID, "documents");
        }
        if (is_object($data)) {
            $data->submitter = loadclient($data->user_id, "profiles");
            $data->reciever = loadclient($data->uploaded_for, "profiles");
            $data->client = loadclient($data->client_id);
            return $data;
        }
    }

    function PrintProfile($Description, $Profile, $webroot) {
        echo '<TR><Th>' . $Description . '</Th>';
        if (is_object($Profile)) {
            echo '<TD width="1%" align="center">' . $Profile->id . '</TD><TD>';
            echo '<A class="nohide" HREF="' . $webroot . 'profiles/view/' . $Profile->id . '">';
            echo ucfirst($Profile->fname) . ' ' . ucfirst($Profile->lname) . ' (' . ucfirst($Profile->fname) . ')';
            echo '</A></TD></TR>';
        } else {
            echo '<TD colspan="2">' . $GLOBALS["strings"]["file_missingdata"] . '</TD></TR>';
        }
    }

    //gets all documents for a client
    function getdocuments($ClientID){
        $table = TableRegistry::get("documents");
        return $table->find('all', array('conditions' => array('client_id'=>$ClientID)));
    }
    //takes getdocuments's results and spits out the document IDs into an array
    function toconditionsarray($results, $key, $field){
        $conditions = array();
        foreach($results as $result){
            $conditions[] = $result->$field;
        }
        return $conditions;
    }
    //prints all attachments for a client
    function getclientattachments($ClientID){
        $documents = toconditionsarray(getdocuments($ClientID), "document_id", "id");
        $files = getfiles($documents);
        listfiles($files, "attachments/", "attachment", false, 3);
    }
    //gets all attachments for an array of document IDs
    function getfiles($DocID){
        $Files = array();
        $table = TableRegistry::get("doc_attachments");
        foreach($DocID as $ID){
            $results = $table->find('all', array('conditions' => array('document_id' => $ID)));
            foreach($results as $result){
                $Files[] = $result;
            }
        }
        return $Files;
    }

    function printdocumentinfo($ID, $isOrder = false, $linktoOrder = false) {
        $data = getdocumentinfo($ID, $isOrder);
        $webroot = $GLOBALS['webroot'];//   profile: http://localhost/veritas3/profiles/view/[ID]   client:  http://localhost/veritas3/clients/edit/[ID]?view
        if (is_object($data)) {
            echo '<table class="table-condensed table-striped table-bordered table-hover dataTable no-footer"><TR><TH colspan="3">';
            if ($isOrder) {
                echo $GLOBALS["strings"]["file_orderinfo"] . ' (ID: ' . $ID . ')';
            } else {
                echo $GLOBALS["strings"]["file_docinfo"] . ' (ID: ' . $ID . ')';
            }

            echo '</TH></TR><TR><Th width="25%">' . $GLOBALS["strings"]["file_createdon"] . '</Th><TD colspan="2">' . $data->created . '</TD></TR>';

            if ($isOrder) {
                echo '<TR><Th>' . $GLOBALS["strings"]["orders_ordertype"] . '</Th><TD COLSPAN="2">' . ucfirst($data->order_type) . '</TD></TR>';
            }

            PrintProfile($GLOBALS["strings"]["documents_submittedby"], $data->submitter, $webroot);
            PrintProfile($GLOBALS["strings"]["documents_submittedfor"], $data->reciever, $webroot);

            echo '<TR><Th>' . $GLOBALS["strings"]["settings_client"] . '</Th>';
            if (is_object($data->client)) {
                echo '<TD align="center">' . $data->client->id . '</TD><TD>' . ucfirst($data->client->company_name);
            } else {
                echo '<TD colspan="2">' . $GLOBALS["strings"]["file_missingdata"];
            }
            echo '</TD></TR>';
            echo '</table>';
            return $data;
        }
    }

    function printanattachment($Filename) {
        if ($Filename) {
            $ret = geticon($Filename);
            return "<i class='fa fa-" . $ret["icon"] . "'></i> " . $Filename;
        }
    }

    function geticon($extension, $language = "English") {
        $ret = array();
        if (strpos($extension, ".")) {
            $extension = getextension($extension);
        }
        switch (TRUE) {//file-excel-o,,
            case $extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'bmp' || $extension == 'gif';
                $ret["type"] = "Image";
                $ret["French"] = "Image";
                $ret["icon"] = 'file-image-o';
                break;
            case $extension == "pdf";
                $ret["type"] = "Portable Document Format";
                $ret["French"] = "Portable Document Format";
                $ret["icon"] = 'file-pdf-o';
                break;
            case $extension == 'zip' || $extension == 'rar';
                $ret["type"] = "File Archive";
                $ret["French"] = "Archive File";
                $ret["icon"] = 'file-archive-o';
                break;
            case $extension == 'wav' || $extension == 'mp3';
                $ret["type"] = "Audio Recording";
                $ret["French"] = "Enregistrement Audio";
                $ret["icon"] = 'file-audio-o';
                break;
            case $extension == 'docx' || $extension == 'doc';
                $ret["type"] = "Microsoft Word Document";
                $ret["French"] = "Document Microsoft Word";
                $ret["icon"] = 'file-word-o';
                break;
            case $extension == 'mp4' || $extension == 'avi';
                $ret["type"] = "Video";
                $ret["French"] = "Vidéo";
                $ret["icon"] = 'file-video-o';
                break;
            case $extension == 'php' || $extension == 'js' || $extension == 'ctp';
                $ret["type"] = "Code Script";
                $ret["French"] = "Script code";
                $ret["icon"] = 'file-code-o';
                break;
            case $extension == 'ppt' || $extension == 'pps';
                $ret["type"] = "Powerpoint Presentation";
                $ret["French"] = "Présentation Powerpoint";
                $ret["icon"] = 'file-powerpoint-o';
                break;
            default:
                $ret["type"] = "Unknown";
                $ret["French"] = "Inconnu";
                $ret["icon"] = 'paperclip';
        }
        if ($language!= "English"){
            if ($language == "Debug"){
                $ret["type"] .= " [Translated]";
            } elseif ( isset($ret[$language])) {
                $ret["type"] = $ret[$language];
            } else {
                $ret["type"] = $extension . " is missing the " . $language. " translation";
            }
        }
        return $ret;
    }

    function get($array, $key, $default){
        if (isset($array[$key])) { return $array[$key]; }
        return $default;
    }

    function pulltranslation($values){
        $ret = array();
        foreach($values as $value){
            $ret[$value] = $GLOBALS["strings"][$value];
        }
        return $ret;
    }

    function listfiles($client_docs, $dir, $field_name = 'client_doc', $delete, $method = 1, $ShowUser = False,$consent=false) {
        //return false;//warning: disabled
        if(!is_iterable($client_docs)){return false;}
        $webroot = $GLOBALS['webroot'];
        $strings = pulltranslation(array("forms_attachedfiles", "file_missing", "file_download", "dashboard_delete"));

        if ($method == 2) {
            echo '<div class="portlet box grey-salsa"><div class="portlet-title"><div class="caption"><i class="fa fa-paperclip"></i>Attachments</div>';
            echo '</div><div class="portlet-body form" align="left">';
            listfiles($client_docs, $dir, $field_name, $delete, 3);
            echo '</div></div>';
        } else if ($method == 3) {

            echo '<table class="table-condensed table-striped table-bordered table-hover dataTable no-footer">';
            $count = 0;
            foreach ($client_docs as $k => $cd) {

                $count += 1;
                if (isset($cd->attachment)) {
                    $file = $cd->attachment;
                } else if (isset($cd->file)) {
                    $file = $cd->file;
                }

                if ($file) {//id, client_id
                    if ($count == 1) {
                        echo '<TR><TH colspan="5">Attachments</TH></TR>';
                    }
                    $path = "/" . $dir . $file;
                    $extension = getextension($file);
                    $filename = getextension($file, PATHINFO_FILENAME);
                    echo "<TR><TD width='29' align='center'><i class='fa fa-";
                    $ret = geticon($extension,  $GLOBALS["language"]);
                    $type = $ret["type"];
                    echo $ret["icon"];

                    echo "' title='" . $type . "'></i></TD>";
                    if (file_exists(getcwd() . $path)) {
                        echo "<TD><A class='nohide' HREF='" . $webroot . $dir . $file . "'>" . $filename . "</A>";
                        if(!$consent)
                        echo "<input type='hidden' value='" . $file . "' name='attach_doc[]' />";
                        echo"</TD>";
                        echo "<TD>" . date('Y-m-d H:i:s', filemtime(getcwd() . $path)) . "</TD>";
                    } else {
                        echo "<TD>" . $filename;
                        if(!$consent)
                        echo "<input type='hidden' value='" . $file . "' name='attach_doc[]' />";
                        echo "</TD>";
                        echo "<TD>" . $strings["file_missing"] . "</TD>";//NEEDS TRANSLATION
                    }
                    switch (TRUE) {
                        case isset($cd->client_id):
                            echo "<TD>" . loadclient($cd->client_id)->company_name . "</TD>";
                            break;
                        case isset($cd->profile_id):
                            echo "<TD>" . loadclient($cd->profile_id, "profiles")->username . "</TD>";
                            break;
                        case isset($cd->document_id) && $ShowUser:
                            echo "<TD>";
                            $Document = loadclient($cd->document_id, "documents");
                            $User = loadclient($Document->user_id, "profiles");
                            if (is_object($User)){
                                echo '<A HREF="/profiles/view/' . $User->id .'" TITLE="' . $User->fname . " " . $User->lname . '">' . $User->username . "</A>";
                            }
                            echo "</TD>";
                            break;
                    }
                    echo "<TD width='1%'>" . $extension . "</TD></TR>";
                }
            }
            echo '</table>';
        } else {
            //old layout ?>
            <div class="form-group col-md-12">
                <label class="control-label" id="attach_label"><?php
                    echo $strings["forms_attachedfiles"];
                    ?>: </label>

                <div class="row">
                    <!-- <a href="#" class="btn btn-primary">Browse</a> -->
                    <?php
                        $count = 0;
                        //var_dump($client_docs);
                        if (isset($client_docs) && count($client_docs) > 0) {
                            $allowed = array('jpg', 'jpeg', 'png', 'bmp', 'gif');
                            foreach ($client_docs as $k => $cd):

                                $count += 1;
                                ?>
                                <div class="col-md-4" align="center">
                                    <?php
                                        if (isset($cd->attachment)) {
                                            $file = $cd->attachment;
                                        }//id, order_id, document_id, sub_id, attach_doc (null)
                                        if (isset($cd->file)) {
                                            $file = $cd->file;
                                        }
                                        $e = explode(".", $file);
                                        $ext = end($e);
                                        if (in_array($ext, $allowed)) {
                                            ?>
                                            <img src="<?php echo $webroot . $dir . $file; ?>"
                                                 style="max-width: 200px;max-height: 200px;"
                                                 title="<?php echo $cd->file; ?>"/>

                                        <?php
                                        } else
                                            echo "<a href='" . $webroot . $dir . $file . "' target='_blank' class='uploaded'>" . $file . "</a>";
                                    ?><BR><?php echo $file;?><BR>
                                    <a href="<?php echo $webroot . $dir . $file ?>" download="<?= $file ?>"
                                       class="btn btn-primary"><?= $strings["file_download"]; ?></a>
							<span <?php if (($delete)) echo "style='display:none;'";?>>
								<a href="javascript:void(0);" title="<?php echo $file?>&<?php echo $cd->id;?>"
                                   class="btn btn-danger img_delete"><?= $strings["dashboard_delete"]; ?></a>
                            </span>
                                    <input type="hidden" name="<?php echo $field_name;?>[]" value="<?php echo $file;?>"
                                           class="moredocs"/>
                                </div>
                            <?php
                            endforeach;
                        }
                        if ($count == 0) {
                            ?>
                        <?php
                        } ?>

                </div>
            </div>
        <?php }
    } ?>