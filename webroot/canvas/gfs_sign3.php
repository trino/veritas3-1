<?php
if (isset($consent_detail)){
	$Filename = getcwd() . "/canvas/" . $consent_detail->criminal_signature_applicant;
	if(!file_exists($Filename)){
		$consent_detail->criminal_signature_applicant = "";
	}
}

$_GET['num']=1003;?>
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
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
        <div class="col-sm-10" style="width: 700px;">
           <input type="hidden" name="criminal_signature_applicant" id="criminal_signature_applicant" />
            <input type="hidden" class="touched3" value="0" />
            <input type="hidden" class="touched_edit1" value="<?php if(isset($consent_detail) && $consent_detail->criminal_signature_applicant){?>1<?php }else{?>0<?php }?>" />
            <label class="control-label required"><?= $strings2["consent_sigapplica"]; ?></label><br>
            <?php if(!isset($_GET['form_id'])){?>
            <p style="margin-top:8px;"><?= $strings2["consent_withinborder"]; ?></p>
            <div style="border: 15px solid #dadada;border-radius: 5px;">
            <canvas id="test<?php echo $_GET['num'];?>" style=""></canvas>
            </div>
            <?php }?>
    		<div class="links" style="margin-top: 5px;">
    			<strong style="display: none;">OPTIONS:</strong>
    			<a href="#" onclick='addImage();' style="display: none;">Add Image</a>
                <?php if(!isset($_GET['form_id'])){?>
    			<a href="javascript:void(0)" onclick='$("#test<?php echo $_GET['num'];?>").data("jqScribble").clear(); $(".touched3").val(0);'>Clear</a>
                <a href="javascript:void(0)" onclick="$(this).parent().parent().find('.touched3').val('1');save_signature('1003');"><?= $strings["forms_save"]; ?></a>  	 <span class="saved1003" style="color: green;"></span>
                <br />
                <?php }?>
                <?php if(isset($consent_detail) && $consent_detail->criminal_signature_applicant){?><img src="<?php echo $webroot.'canvas/'.$consent_detail->criminal_signature_applicant;?>" style="max-width: 100%;" /><?php }
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
         $("#test1003").jqScribble();
         
      })
      
      </script>
		