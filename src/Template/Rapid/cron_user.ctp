<!--script src="../webroot/assets/admin/pages/scripts/webservice.js"></script-->
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
                        handlewebservice(msg, "rapid", "cron_user", true, '<?= $this->request->webroot;?>');
                        window.location="<?php echo $this->request->webroot;?>profiles/settings?all_cron";
                    },
                    error:function(){
                        handlewebservice(msg, "rapid", "cron_user", false, '<?= $this->request->webroot;?>');
                    }
                });
            }
        

    })

</script>