<h1>Submit an application</h1>
<div class="parent">
    <h3>Choose a client</h3>
    <?php
    $i = 0;
    foreach($client as $c)
    {
        $i++;
        ?>
        <a class="listclient" href="<?php echo $this->request->webroot;?>clientApplication/apply/<?php echo $c->slug;?>" style="display: block;">
            
            <?php 
            echo $i.' '.strtoupper($c->company_name);
            ?>
        </a>
        <?php
    }
    ?>
    
</div>