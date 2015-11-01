 <?php
 if($this->request->session()->read('debug'))
        echo "<span style ='color:red;'>confirmation.php #INC156</span>";
 ?>
<div class="note note-success">
    <h4 class="block">Confirmation</h4>
    <p>
        Please sign below to confirm your submission.
    </p>
    <div class="clearfix"></div>
    <?php include('canvas/example.php');?>
    <div class="clearfix"></div>
</div>

<script>
$(function(){
   $("#test1").jqScribble(); 
});
function save(numb) {
		  alert('rest');return;
			$("#test"+numb).data("jqScribble").save(function(imageData)
			{
				$.post('image_save.php', {imagedata: imageData}, function(response)
					{
                        $.ajax({
                            url:'<?php echo $this->request->webroot;?>document/image_sess/'+numb+'/'+response
                        });
					});	
				
			});
		}
		function addImage() {
			var img = prompt("Enter the URL of the image.");
			if(img !== '')$("#test").data("jqScribble").update({backgroundImage: img});
		}
</script>