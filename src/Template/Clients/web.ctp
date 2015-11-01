<script>
    $(function(){
         $.ajax({
            url:'<?php echo $this->request->webroot;?>orders/webservice/BUL/<?php echo $forms;?>/<?php echo $driverid;?>/<?php echo $orderid;?>,

        });
    })
</script>