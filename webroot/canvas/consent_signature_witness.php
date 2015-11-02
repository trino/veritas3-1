<?php
if ($this->request->session()->read('debug')) {
    echo "<span style ='color:red;'>subpages/canvas/consent_signature_witness.php #INC???</span>";
}
?>
<meta name="viewport" content="width=device-width;initial-scale=1.0;maximum-scale=1.0;user-scalable=0;"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
			
		
		<script src="<?php echo $this->request->webroot;?>canvas/jquery.jqscribble.js" type="text/javascript"></script>
		<script src="<?php echo $this->request->webroot;?>canvas/jqscribble.extrabrushes.js" type="text/javascript"></script>
		
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
	
		
        <div class="col-sm-12" id="sig3">
            <input type="hidden" name="signature_company_witness" id="signature_company_witness" value="<?php if(isset($consent_detail) && $consent_detail->signature_company_witness){echo  $consent_detail->signature_company_witness; }?>" />
            <input type="hidden" class="touched" value="0" />
            <input type="hidden" class="touched_edit3" value="<?php if(isset($consent_detail) && $consent_detail->signature_company_witness){?>1<?php }else{?>0<?php }?>" />
            <label class="control-label "><?= $strings2["consent_sigwitness"]; ?></label><br>
            <?php if($this->request->params['action']!= 'vieworder' && $this->request->params['action']!= 'view'){?>
                <div style="width:400px;">
                    <canvas id="test4" style="border: 1px solid silver;border-radius: 5px;"></canvas>
                </div>
            <?php }?>
    		<div class="links" style="margin-top: 5px;">
    			<strong style="display: none;">OPTIONS:</strong>
    			<a href="#" onclick='addImage();' style="display: none;">Add Image</a>
                <p class="no-print no-view" style="color: red;<?php if(isset($consent_detail) && $consent_detail->signature_company_witness){?>display:none;<?php }?>"><?= $strings["forms_signhere"]; ?></p>
    			<a href="javascript:void(0)" class="no-print" onclick='$("#test4").data("jqScribble").clear();$(this).parent().parent().find(".touched").val("0");'><?= $strings["forms_clear"]; ?></a>
                <a href="javascript:void(0)" class="no-print" onclick="$(this).parent().parent().find('.touched').val('1');save_signature('4');"><?= $strings["forms_save"]; ?></a>  <span class="saved4" style="color: green;"></span>
                <br />
                
                
    		</div>
        </div>
         
		<div class="col-sm-6">
               <?php if(isset($consent_detail) && $consent_detail->signature_company_witness){?><img src="<?php echo $this->request->webroot.'canvas/'.$consent_detail->signature_company_witness;?>" style="max-width: 100%;" /><?php 
               }else
                {
                    if(isset($consent_detail))
                    {
                        ?>
                        <p>&nbsp;</p><strong>No signature supplied</strong>
                        <?php
                    }
                }
               ?>
                                   
        </div>
		<div class="clearfix"></div>