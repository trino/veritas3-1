<tbody>
<?php
if ($clients){
	$Columns = 2;
	$Column = 0;
	$Index=0;
	foreach ($clients as $o) {
		$pro_ids = explode(",",$o->profile_id);
		if($Column == 0){
			echo '<TR>';
		}
		$Column++;
		?>
			<TD width="5" align="center">
				<input type="checkbox" ID="c_<?= $Index; ?>" name="client_idss[]" onclick="clientclick(<?= $Index; ?>);" value="<?php echo $o->id; ?>" class="addclientz" <?php
					if(in_array($id,$pro_ids)){echo "checked";}
					echo '/>';
				?>
			</TD>
            <TD width="5" align="center"><LABEL FOR="c_<?= $Index; ?>">
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
                	echo '"></LABEL></TD><TD><LABEL FOR="c_';
				echo $Index . '">' . $o->company_name . '</LABEL></TD>';
				if($Column == $Columns){
					$Column=0;
					echo '</TR>';
				}
			$Index++;
	}
	if($Column == 1){
		echo '<TD COLSPAN="3"></TD>';
	}
}
?>
</tbody>
<!--script>
	//if($(this).is(':checked'))addclientz($(this).val(),1,<?= $id;?>);else addclientz($(this).val(),0,<?= $id;?>); // code from onclick event
	function addclientz(client_id,addclient,id) {
		$.ajax({
			type: "post",
			data: "client_id="+client_id+"&add="+addclient+"&user_id="+id,
			url: "<?= $this->request->webroot;?>clients/addprofile",
			success: function(msg){
				//alert(msg);
			}
		})
	}
</script-->