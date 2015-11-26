<STYLE>
    .SPACER{
        width: 18px !important;
        margin-bottom: 0px;
        margin-right: 5px;
        text-align: right;
    }
</STYLE>
<h1>Submit an application</h1>
<div class="parent">
    <h3>Choose a client</h3>
    <?php
        $i = 0;
        foreach($client as $c) {
            $i++;
            if(!$c->slug){
                $c->slug = strtolower(str_replace(" ", "_", trim($c->company_name)));
                $Manager->update_database("clients", "id", $c->id, array("slug" => $c->slug));
            }
            echo '<a class="listclient" href="' . $this->request->webroot . 'clientApplication/apply/' . $c->slug . '" style="display: block;">';
            echo '<LABEL CLASS="SPACER">' . $i . '</LABEL>' . strtoupper($c->company_name) . '</a>';
        }
    ?>
</div>