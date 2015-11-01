<?php //var_dump($arr);die();?>

<script>
    $(function () {

     
            var forms = '<?php echo $arr['forms'];?>';
            var driver = '<?php echo $arr['driver'];?>';
            //var clients = '<?php echo $arr['client_id'];?>';
            var orders = '<?php echo $arr['order_id'];?>';
            var driv = driver.split(',');
            var ord = orders.split(',');

            for (var k = 0; k < driv.length; k++) {
                $.ajax({
                    url: '<?php echo $this->request->webroot;?>orders/webservice/REQ/' + forms + '/' + driv[k] + '/' + ord[k],
                    success:function(){
                        alert('Cron ran successfully.')
                        window.location="<?php echo $this->request->webroot;?>profiles/settings";
                    }
                });
            }
        

    })

</script>