<?php
    $debug=$this->request->session()->read('debug');
    if($debug) {
        echo "<span style ='color:red;'>home_topblocks.php #INC112</span>";
    }

    if(!isset($userid))     {$userid    = $this->Session->read('Profile.id');}
    if(!isset($block))      {$block     = $this->requestAction("settings/all_settings/".$userid."/blocks");}
    if(!isset($sidebar))    {$sidebar   = $this->requestAction("settings/all_settings/".$userid."/sidebar");}
    //$order_url = $this->requestAction("settings/getclienturl/".$this->Session->read('Profile.id')."/order");
    $order_url = 'orders/productSelection?driver=0';
    $document_url = $this->requestAction("settings/getclienturl/".$userid."/document");
    $lastcolor = "";
    $DoAll = $language == "Debug";

    function randomcolor(){
        global $lastcolor;
        $colors = array("bg-green-meadow", "bg-red-sunglo", "bg-yellow-saffron", "bg-purple-studio", "bg-green", "bg-blue");
        $newcolor = $colors[rand(0, count($colors)-1)];
        while($newcolor == $lastcolor){
            $newcolor = $colors[rand(0, count($colors)-1)];
        }
        $lastcolor = $newcolor;
        echo $newcolor;
        srand();
    }

    function makeblock($debug, $URL, $Name, $Icon = "icon-docs", $Color= "bg-blue"){//tile
        //   if(!$debug){$Color= "bg-blue";}
        if(!$Icon){$Icon = "icon-docs";}
        echo '<a href="' .  $URL . '" class="tile ' . $Color;
        echo '" style="display: block;"><div class="tile-body"><i class="' . $Icon . '"></i></div><div class="tile-object">';
        echo '<div class="name">' . $Name . '</div><div class="number"></div></div></a>';
    }
?>

<div class="tiles">
    <?php if ($DoAll || ($sidebar->client_list ==1 && $block->list_client =='1')) { ?>
        <a href="<?php echo $this->request->webroot; ?>clients" class="tile bg-blue" style="display: block;">
            <div class="tile-body">
                <i class="fa fa-search"></i>
            </div>
            <div class="tile-object">
                <div class="name"><?= $strings["index_listclients"]; ?></div>
                <div class="number"></div>
            </div>
        </a>
    <?php }

    if ($DoAll || ($sidebar->client_create ==1 && $block->add_client =='1')) { ?>
        <a class="tile bg-blue" href="<?php echo $this->request->webroot; ?>clients/add" style="display: block;">
            <div class="tile-body">
                <i class="icon-globe"></i>
            </div>
            <div class="tile-object">
                <div class="name"><?= $strings["index_createclients"]; ?></div>
                <div class="number"></div>
            </div>
        </a>
    <?php }

    if ($sidebar->client_list ==1 && $block->draft_client =='1' && false) { ?>
        <a href="<?php echo $this->request->webroot; ?>clients?draft" class="tile bg-blue" style="display: block;">
            <div class="tile-body">
                <i class="fa fa-pencil"></i>
            </div>
            <div class="tile-object">
                <div class="name"> <?=$settings->client;?> Drafts</div>
                <div class="number"></div>
            </div>
        </a>
    <?php }

    if ($DoAll || ($sidebar->profile_list ==1 && $block->list_profile =='1')) { ?>
        <a href="<?php echo $this->request->webroot; ?>profiles" class="tile bg-blue" style="display: block;">
            <div class="tile-body">
                <i class="fa fa-search"></i>
            </div>
            <div class="tile-object">
                <div class="name"><?=$strings["index_listprofiles"];?></div>
                <div class="number"></div>
            </div>
        </a>
    <?php }

    if ($DoAll || ($sidebar->profile_create ==1 && $block->addadriver =='1')) { ?>
        <a class="tile bg-blue" href="<?php echo $this->request->webroot; ?>profiles/add" style="display: block;">
            <div class="tile-body">
                <i class="icon-user"></i>
            </div>
            <div class="tile-object">
                <div class="name"><?= $strings["index_createprofile"];?></div>
                <div class="number"></div>
            </div>
        </a>
    <?php }

    if ($sidebar->profile_list ==1 && $block->draft_profile =='1' && false) { //abandonded ?>
		<a href="<?php echo $this->request->webroot; ?>profiles?draft" class="tile bg-blue" style="display: block;">
            <div class="tile-body">
                <i class="fa fa-pencil"></i>
            </div>
            <div class="tile-object">
                <div class="name"><?=$settings->profile;?> Drafts</div>
                <div class="number"></div>
            </div>
        </a>
    <?php }

    if ($DoAll || ($sidebar->document_list ==1 && $block->list_document =='1')) { ?>
        <a href="<?php echo $this->request->webroot; ?>documents" class="tile bg-blue" style="display: block;">
            <div class="tile-body">
                <i class="fa fa-search"></i>
            </div>
            <div class="tile-object">
                <div class="name"><?= $strings["index_listdocuments"];?></div>
                <div class="number"></div>
            </div>
        </a>
    <?php }

    if ($DoAll || ($sidebar->orders_list ==1 && $block->list_order =='1')) { ?>
        <a href="<?php echo $this->request->webroot; ?>orders/orderslist" style="display: block;" class="tile bg-blue">
            <div class="tile-body">
                <i class="fa fa-search"></i>
            </div>
            <div class="tile-object">
                <div class="name"><?= $strings["index_listorders"] ?></div>
                <div class="number"></div>
            </div>
        </a>
    <?php }

    if ($sidebar->order_intact ==1 && $block->orders_intact =='1') {//abandoned ?>
        <a class="tile bg-blue-ebonyclay" href="<?php echo $this->request->webroot; ?>orders/intact" style="display: block;">
            <div class="tile-body">
                <i class="fa fa-pencil"></i>
            </div>
            <div class="tile-object">
                <div class="name">Intact Orders</div>
                <div class="number"></div>
            </div>
        </a>
    <?php }

    if ($DoAll || $block->training){
        makeblock($debug, "training", $strings["index_training"], "fa fa-graduation-cap", "bg-blue");
    }

    if ($DoAll || $sidebar->orders_create ==1) { ?>
        <STYLE>
            .icon-footprint:before { content: url('assets/global/img/footprint.png'); }
            .icon-surveillance:before { content: url('assets/global/img/surveillance.png'); }
            .icon-physical:before { content: url('assets/global/img/physical.png'); }
        </STYLE>
    <?php
        $formlist="";
        foreach($forms as $form){
            if (Strlen($formlist)>0){ $formlist.=",";}
            $formlist.=$form->number;
        }

        $AssignedClient = GetAssignedClients($userid, $clients, true);
        if($AssignedClient){$AssignedClient= $AssignedClient->id;} else {$AssignedClient="";}

        $MEEname="";
        if($AssignedClient) {
            $Name = getFieldname("Name", $language);
            foreach($products as $product){
                if ($product->Blocks_Alias) {
                    $color="bg-blue";
                    $sidebaralias = $product->Sidebar_Alias;
                    $blockalias = $product->Blocks_Alias;
                    if ($product->Acronym == "MEE"){$MEEname = $product->Name;}
                    if ($DoAll || ($blockalias && $block->$blockalias =='1' && $product->Visible==1)) {
                        $URL="orders/productSelection?driver=0&ordertype=" . $product->Acronym;
                        $color= "bg-" . str_replace("bg-", "", $product->Block_Color);
                        if($product->Bypass==1) {//change to ->Bypass When the parameter exists
                            $URL = "orders/addorder/" . $AssignedClient . "/?driver=0&order_type=" . $product->Acronym . "&forms=" . $product->doc_ids;
                            if(substr($color, 0,3)!= "bg-"){$color = "bg-" . $color;}
                        }
                        makeblock($debug, $URL, $product->$Name . $Trans, "fa fa-shopping-cart", $color);
                    }
                }
            }
        }

        $Name = getFieldname("title", $language);
        foreach($theproductlist as $product){
            if($DoAll || ($product->enable == 1 && $product->TopBlock == 1)) {
                $URL="documents/add/1?type=" . $product->number;
                makeblock($debug, $this->request->webroot . $URL, $product->$Name . $Trans, "icon-docs", "bg-yellow");
            }
        }
    }

    function GetAssignedClients($UserID, $clients, $First = false){
        $clientlist = array();
        if ($clients) {
            foreach ($clients as $client) {
                $pro_ids = explode(",", $client->profile_id);
                if (in_array($UserID, $pro_ids)) {
                    if ($First){return $client;}
                    $clientlist[] = $client;
                }
            }
        }
        if(count($clientlist)>0){return $clientlist;}
    }

    if ($DoAll || ($sidebar->orders_list ==1 && $block->document_draft =='1')) { ?>
        <a class="tile bg-blue" href="<?php echo $this->request->webroot; ?>orders/orderslist?draft" style="display: block;">
            <div class="tile-body">
                <i class="fa fa-pencil"></i>
            </div>
            <div class="tile-object">
                <div class="name"><?= $strings["index_orderdrafts"];?></div>
                <div class="number"></div>
            </div>
        </a>
    <?php }

    if ($DoAll || ($sidebar->document_create ==1 && $block->submit_document =='1')) { ?>
        <a href="<?php echo $this->request->webroot.$document_url;?>" class="tile bg-blue" style="display: block;">
            <div class="tile-body">
                <i class="icon-doc"></i>
            </div>
            <div class="tile-object">
                <div class="name"><?= $strings["index_createdocument"];?></div>
                <div class="number"></div>
            </div>
        </a>
    <?php }

    if ($DoAll || ($sidebar->document_list ==1 && $block->document_draft =='1')) { ?>
        <a class="tile bg-blue" href="<?php echo $this->request->webroot; ?>documents?draft" style="display: block;">
            <div class="tile-body">
                <i class="fa fa-pencil"></i>
            </div>
            <div class="tile-object">
                <div class="name"><?= $strings["index_documentdrafts"];?></div>
                <div class="number"></div>
            </div>
        </a>
    <?php }

    if ($sidebar->messages ==1 && $block->message =='1' && false) { ?>
        <a class="tile bg-green" href="<?php echo $this->request->webroot; ?>messages" style="display: block;">
            <div class="tile-body">
                <i class="fa icon-envelope"></i>
            </div>
            <div class="tile-object">
                <div class="name">Messages</div>
                <div class="number"></div>
            </div>
        </a>
    <?php }

     if ($DoAll || ($sidebar->schedule ==1 && $block->schedule =='1')) { ?>
    <!--<div class="input-group input-medium date date-picker" data-date-start-date="+0d" data-date-format="dd-mm-yyyy">-->
        <a  href="<?php echo $this->request->webroot;?>tasks/calender" class="tile bg-blue" style="display: block;">
            <div class="tile-body">
                <i class="fa fa-calendar"></i>
            </div>
            <div class="tile-object">
                <div class="name"><?= $strings["index_tasks"]; ?></div>
                <div class="number"></div>
            </div>
         </a>
    <?php }

    if ($DoAll || ($sidebar->schedule_add ==1 && $block->schedule_add =='1')) { ?>
    <!--<div class="input-group input-medium date date-picker" data-date-start-date="+0d" data-date-format="dd-mm-yyyy">-->
        <a  href="<?php echo $this->request->webroot;?>tasks/add" class="tile bg-blue" style="display: block;">
            <div class="tile-body">
                <i class="fa fa-calendar"></i>
            </div>
            <div class="tile-object">
                <div class="name"><?= $strings["index_addtasks"]; ?></div>
                <div class="number"></div>
            </div>
         </a>
    <?php }

     if ($DoAll || ($sidebar->feedback == 1 && $block->feedback =='1')) { ?>
        <a href="<?php echo $this->request->webroot.$document_url;?>" class="tile bg-blue">
            <div class="tile-body">
                <i class="fa fa-comments"></i>
            </div>
            <div class="tile-object">
                <div class="name"><?= $strings["index_feedback"];?></div>
                <div class="number"></div>
            </div>
    </a>
    <?php }

    if ($DoAll || ($sidebar->analytics ==1 && $block->analytics =='1')) { ?>
        <a href="<?php echo $this->request->webroot;?>documents/analytics" class="tile bg-blue" style="display: block;">
            <div class="tile-body">
                <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="tile-object">
                <div class="name"><?= $strings["index_analytics"];?></div>
                <div class="number"></div>
            </div>
        </a>
    <?php } ?>
    </div>
<script>
    $(function(){

        $('.date-picker1').datepicker({

        })
        //Listen for the change even on the input
        .change(dateChanged)
        .on('changeDate', dateChanged);
    });
    function dateChanged(ev) {
        datez = (ev.date.valueOf())/1000;
        //alert(ev.date.valueOf());
        $(this).datepicker('hide');
        window.location.href="<?php echo $this->request->webroot;?>todo/date/"+datez;
    }
</script>
