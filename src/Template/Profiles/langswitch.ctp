<?php
    include_once('subpages/api.php');
    $language = $this->requestAction('documents/translate');
    echo Translate("langswitched", $language);
?>
<SCRIPT>
    window.setTimeout(function(){
        // Move to a new location or you can do something else
        var currentUrl = window.location.href;
        window.history.go(-1);
        setTimeout(function(){
            // if location was not changed in 100 ms, then there is no history back
            if(currentUrl === window.location.href){
                // redirect to site root
                window.location.href = '..';
            }
        }, 3000);
    }, 2000);
</SCRIPT>