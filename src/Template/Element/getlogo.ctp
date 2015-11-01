<?php
    $logo = $this->requestAction('Logos/getlogo');
    foreach($logo as $l) {
        echo $l->logo;
        break;
    }
?>
