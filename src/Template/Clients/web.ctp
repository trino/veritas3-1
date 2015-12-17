<!--script src="../webroot/assets/admin/pages/scripts/webservice.js"></script-->
<script>
    $(function(){
         $.ajax({
            url:'<?= $this->request->webroot;?>orders/webservice/BUL/<?php echo $forms;?>/<?php echo $driverid;?>/<?php echo $orderid;?>,
             success:function(msg){
                 handlewebservice(msg, "clients", "web", true, '<?= $this->request->webroot;?>');
             },
             error:function(msg){
                 handlewebservice(msg, "clients", "web", false, '<?= $this->request->webroot;?>');
             }
        });
    })
</script>