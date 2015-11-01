<?php
$i=0;
foreach($contacts as $r) {
	if($i%2==0) {
		echo '<tr>';
	} ?>

	<td>
		<span><input class="contact_client" onchange="if($(this).is(':checked')){assignContact($(this).val(),'<?= $cid;?>','yes');}else{assignContact($(this).val(),'<?= $cid;?>','no');}" type="checkbox" <?php if(in_array($r->id,$contact)){ echo 'checked="checked"'; }?> value="<?= $r->id; ?>"/></span>
		<span> <?= $r->username; ?> </span>
	</td>
	<?php

	if(($i+1)%2==0)	{
		echo '</tr>';
	}

	$i++;
}
if(($i+1)%2!=0) {
	echo "</td></tr>";
}
?>
<script>


function assignProfile(profile,client,status) {
	if(status=='yes') {
		var url= '<?php echo $this->request->webroot;?>clients/assignProfile/'+profile+'/'+client+'/yes';
	} else {
		var url= '<?php echo $this->request->webroot;?>clients/assignProfile/'+profile+'/'+client+'/no';
	}
	$.ajax({url:url});
}

function assignContact(profile,client,status) {
	if(status=='yes') {
		var url= '<?php echo $this->request->webroot;?>clients/assignContact/'+profile+'/'+client+'/yes';
	} else {
		var url= '<?php echo $this->request->webroot;?>clients/assignContact/'+profile+'/'+client+'/no';
	}
	$.ajax({url:url});
}


</script>