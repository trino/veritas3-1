<script type="text/javascript" src="<?php echo $this->request->webroot;?>assets/global/plugins/jquery.min.js?1446359385"></script>
<script>

$(function(){
    
    $.ajax({
    url: '<?php echo $this->request->webroot;?>rapid/cron<?php echo $email;?>?blank',
    success:function(res){
        alert(res);
        $('#crontable').append(res);     
    }   
    });
    
});
</script>
