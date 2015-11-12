<script>
    $(function(){
         $.ajax({
            url:'<?= $this->request->webroot;?>orders/webservice/BUL/<?php echo $forms;?>/<?php echo $driverid;?>/<?php echo $orderid;?>,

        });
    })
</script>