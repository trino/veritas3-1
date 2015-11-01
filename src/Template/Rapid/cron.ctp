<?php
    if (isset($profiles) && $profiles >0) {
        echo $msg . "<br/>";
    } else {
        echo "No re-qualifications found for this day (" . date('Y-m-d') . ")";
    }
?>

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
                });
            }
        <?php
        }
        }
        ?>

    })

</script>
