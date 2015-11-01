<?php
    $ID = $GLOBALS["doc_id"];
    $dx = $subdoc->find()->where(['id'=>$_GET['type']])->first();
    echo '<div class="subform' . $ID . '">';
    if($controller == 'documents' ) {
        $colr = $this->requestAction('/documents/getColorId/' . $ID);
        //if(!$colr){$colr = $class[$ID-1];}
        makeportlet($did, $colr, docname($ID, $subdoccli, $language));
    }
    include($GLOBALS["doc_filename"]);
    if($controller == 'documents' ) {
        echo '</div></div></div></div></div></div>' ;
    }
    echo "</div>";
?>