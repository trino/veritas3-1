<tbody>
<?php
if ($clients){
	$Columns = 2;
	$Column = 0;
	foreach ($clients as $o) {
		$pro_ids = explode(",",$o->profile_id);
		if($Column == 0){
			echo '<TR>';
		}
		$Column++;
		?>
            <td width="5" align="center">
                <img height="32" src="<?php
                    echo $this->request->webroot;
                    $filename = 'img/logos/MEELogo.png';
                    if (strlen($o->image)>0) {
                        $testfile = 'img/jobs/' . $o->image;
                        if (file_exists($testfile)){
                            $filename = $testfile;
                        }
                    }
                    echo $filename;
                ?>">
            </TD>
			<TD>
				<input type="checkbox" name="client_idss[]" onclick="if($(this).is(':checked'))addclientz($(this).val(),1,<?= $id;?>);else addclientz($(this).val(),0,<?= $id;?>)" value="<?php echo $o->id; ?>" class="addclientz" <?php
				if(in_array($id,$pro_ids)){echo "checked";}
				echo '/>';
				echo $o->company_name;
				echo '</td>';
		if($Column == $Columns){
			$Column=0;
			echo '</TR>';
		}
	}
	if($Column == 1){
		echo '<TD COLSPAN="2"></TD>';
	}
}
?>
</tbody>
<script>
	function addclientz(client_id,addclient,id) {
			/*$.ajax({
				type: "post",
				data: "client_id="+client_id+"&add="+addclient+"&user_id="+id,
				url: "<?= $this->request->webroot;?>clients/addprofile",
				success: function(msg){
					//alert(msg);
				}
			}) */
	}
</script>