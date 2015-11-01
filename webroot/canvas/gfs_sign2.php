<?php

$_GET['num']=1002;?>
		<meta name="viewport" content="width=device-width;initial-scale=1.0;maximum-scale=1.0;user-scalable=0;"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
			
		
		<script src="<?php echo $webroot;?>canvas/jquery.jqscribble.js" type="text/javascript"></script>
		<script src="<?php echo $webroot;?>canvas/jqscribble.extrabrushes.js" type="text/javascript"></script>
		
		<style>
			.links a {
				padding: 3px 10px;
                background:#007ECC; 
                display: inline-block;
                border-radius:4px;
				text-decoration: none;
				color: #FFF;
                
			}
			.column-left {
				display: inline; 
				float: left;
			}
			.column-right {
				display: inline; 
				float: right;
			}
		</style>
	
		<div style="overflow: hidden; margin-bottom: 5px;display:none" class="">
			<div class="column-left links" style="display: none;">
				<strong>BRUSHES:</strong>
				<a href="#" onclick='$("#test").data("jqScribble").update({brush: BasicBrush});'>Basic</a>
				<a href="#" onclick='$("#test").data("jqScribble").update({brush: LineBrush});'>Line</a>
				<a href="#" onclick='$("#test").data("jqScribble").update({brush: CrossBrush});'>Cross</a>
			</div>
			<div class="column-right links" style="display: none;">
				<strong>COLORS:</strong>
				<a href="#" onclick='$("#test").data("jqScribble").update({brushColor: "rgb(0,0,0)"});'>Black</a>
				<a href="#" onclick='$("#test").data("jqScribble").update({brushColor: "rgb(255,0,0)"});'>Red</a>
				<a href="#" onclick='$("#test").data("jqScribble").update({brushColor: "rgb(0,255,0)"});'>Green</a>
				<a href="#" onclick='$("#test").data("jqScribble").update({brushColor: "rgb(0,0,255)"});'>Blue</a>
			</div>
		</div>
        <div class="col-sm-10" style="width: 500px;">
           <input type="hidden" name="signature_company_witness2" id="signature_company_witness2"/>
    <input type="hidden" class="touched2" value="0"/>
    <input type="hidden" class="touched_edit4"
           value="<?php if (isset($consent_detail) && $consent_detail->signature_company_witness2) { ?>1<?php } else { ?>0<?php } ?>"/>
    <label class="control-label"><?= $strings2["consent_sigwitness"]; ?></label><br>
    <?php if(!isset($_GET['form_id'])){?>
            <canvas id="test<?php echo $_GET['num'];?>" style="border: 1px solid silver;border-radius: 5px; width: 400px;"></canvas>
            <?php }?>
    		<div class="links" style="margin-top: 5px;">
    			<strong style="display: none;">OPTIONS:</strong>
    			<a href="#" onclick='addImage();' style="display: none;">Add Image</a>
                <?php if(!isset($_GET['form_id'])){?>
    			<a href="javascript:void(0)" onclick='$("#test<?php echo $_GET['num'];?>").data("jqScribble").clear(); $(".touched2").val(0);'>Clear</a>
                <a href="javascript:void(0)" onclick="$(this).parent().parent().find('.touched2').val('1');save_signature('1002');"><?= $strings["forms_save"]; ?></a>  	 <span class="saved1002" style="color: green;"></span>
                <br />
                <?php }?>
                <?php if(isset($consent_detail) && $consent_detail->signature_company_witness2){?><img src="<?php echo $webroot.'canvas/'.$consent_detail->signature_company_witness2;?>" style="max-width: 100%;" /><?php }
                else
                {
                    if(isset($consent_detail))
                    {
                        ?>
                        <strong>No signature supplied</strong>
                        <?php
                    }
                }
                ?>
                <br />
                
    		</div>
        </div>
      <script>
      
      $(function(){
         $("#test1002").jqScribble();
         
      })
      
    
      </script>
		