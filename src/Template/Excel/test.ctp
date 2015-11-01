<?php
    $Path = 'assets/global/plugins/';
?>
<DIV ID="exceltest" style="display: none; width: 400px; height: 400px; border: 1px solid black; overflow: auto; resize: both;">
    <HEADER>
        Header Test
    </HEADER>
    <?php
        $Table = "test";
        $EmbeddedMode="exceltest";
        $DIR = str_replace('\webroot/', '/', getcwd() . "/src/Template/Excel/index.ctp");
        include($DIR);
    ?>
    <FOOTER>
        Footer Test
    </FOOTER>
</DIV>


<link href="https://vitalets.github.io/x-editable/assets/jquery-ui-1.10.1.custom/css/redmond/jquery-ui-1.10.1.custom.css" rel="stylesheet">
<script src="https://vitalets.github.io/x-editable/assets/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.min.js"></script>

<!-- x-editable (jquery) -->
<link href="https://vitalets.github.io/x-editable/assets/x-editable/jqueryui-editable/css/jqueryui-editable.css" rel="stylesheet">
<script src="https://vitalets.github.io/x-editable/assets/x-editable/jqueryui-editable/js/jqueryui-editable.js"></script>


    <!-- main.js -->
    <script>
    $(document).ready(function() {
    //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'popup';

    $('.editable').editable();

});
</script>

<div class="container">

    <h1>X-editable starter template</h1>

    <div>
        <span>Username:</span>
        <a href="#" id="username" data-type="text" data-placement="right" data-title="Enter username" class="editable">superuser</a>
    </div>

    <div>
        <span>Status:</span>
        <a href="#" id="status" class="editable"></a>
    </div>


</div>