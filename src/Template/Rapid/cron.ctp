<?php
    if (isset($profiles) && $profiles >0) {
        echo $msg . "<br/>";
    } else {
        echo "No re-qualifications found for this day (" . date('Y-m-d') . ")";
    }
?>
<script src="../webroot/assets/admin/pages/scripts/webservice.js"></script>
<script>
    $(function () {

        <?php  if(isset($profiles) && $profiles> 0){

            foreach($arrs as $arr){
            ?>
            var forms = '<?php echo $arr['forms'];?>';
            var driver = '<?php echo $arr['driver'];?>';
            //var clients = '<?php echo $arr['client_id'];?>';
            var orders = '<?php echo $arr['order_id'];?>';
            var driv = driver.split(',');
            var ord = orders.split(',');

            for (var k = 0; k < driv.length; k++) {
                $.ajax({
                    url: '<?php echo $this->request->webroot;?>orders/webservice/REQ/' + forms + '/' + driv[k] + '/' + ord[k],
                    success:function(msg){
                        handlewebservice(msg, "rapid", "cron", true);
                    },
                    error:function(msg){
                        handlewebservice(msg, "rapid", "cron", false);
                    }
                });
            }
        <?php
        }
        }
        ?>

    })

</script>
