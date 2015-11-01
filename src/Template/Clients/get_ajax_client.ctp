<tbody>
<?php
if ($clients){
	foreach ($clients as $o)
	{
		$pro_ids = explode(",",$o->profile_id);
		?>

		<tr>
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
            </TD><TD>
				<input type="checkbox" name="client_idss[]" onclick="if($(this).is(':checked'))addclientz($(this).val(),1,<?php echo $id;?>);else addclientz($(this).val(),0,<?php echo $id;?>)" value="<?php echo $o->id; ?>" class="addclientz" <?php if(in_array($id,$pro_ids)){echo "checked";}?> /> <?php echo $o->company_name; ?></td>
		</tr>

	<?php
	}}
?>
</tbody>
<script>
function addclientz(client_id,addclient,id)
{

		
			
		/*$.ajax({
			type: "post",
			data: "client_id="+client_id+"&add="+addclient+"&user_id="+id,
			url: "<?php echo $this->request->webroot;?>clients/addprofile",
			success: function(msg){
				//alert(msg);
			}
		}) */
}


</script>