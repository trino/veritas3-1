<?php
/*if ($this->request->session()->read('debug')) {
    echo "<span style ='color:red;'>subpages/canvas/apply.php #INC???</span>";
}*/

	if (isset($application_for_employment_gfs)){
        $Filename = getcwd() . "/canvas/" . $application_for_employment_gfs->gfs_signature;
        if(!file_exists($Filename)){
            $application_for_employment_gfs->gfs_signature = "";
        }
    }

$_GET['num']=100;?>
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
        <div class="col-sm-10" style="width: 700px;">
            <input type="hidden" class="touched" value="0" />
            <input type="hidden" class="touched_edit" value="<?php if(isset($application_for_employment_gfs) && $application_for_employment_gfs->gfs_signature){?>1<?php }else{?>0<?php }?>" />
            <input type="hidden" name="gfs_signature" id="recruiter_signature"
               value="<?php if (isset($application_for_employment_gfs->recruiter_signature) && $application_for_employment_gfs->gfs_signature) echo $application_for_employment_gfs->gfs_signature; ?>"/>
            <?php if(!isset($_GET['form_id'])){?>
            <div style="border: 15px solid #dadada;border-radius: 5px;">
            <canvas id="test<?php echo $_GET['num'];?>" style=""></canvas>
            </div>
            <?php }?>
    		<div class="links" style="margin-top: 5px;">
    			<strong style="display: none;">OPTIONS:</strong>
    			<a href="#" onclick='addImage();' style="display: none;">Add Image</a>
                <?php if(!isset($_GET['form_id'])){?>
    			<a href="javascript:void(0)" onclick='$("#test<?php echo $_GET['num'];?>").data("jqScribble").clear(); $(".touched").val(0);'>Clear</a> 			
                <br />
                <?php }?>
                <?php if(isset($application_for_employment_gfs) && $application_for_employment_gfs->gfs_signature){?><img src="<?php echo $webroot.'canvas/'.$application_for_employment_gfs->gfs_signature;?>" style="max-width: 100%;" /><?php }
                else
                {
                    if(isset($application_for_employment_gfs))
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
         $("#test100").jqScribble();
         
      })
          function save_signature1(numb) {
        //alert('trd');
        numb = '100';
        $("#test" + numb).data("jqScribble").save(function (imageData) {
           
                $.post('<?php echo $webroot; ?>canvas/image_save.php', {imagedata: imageData}, function (response) {
                    if(response=='')
                    {
                        alert('There was problem saving the signatures, please go back and re-submit the consent form.');
                    }
                    if (numb == '100') {

                        $('#recruiter_signature').val(response);
                    }
                    
                    $('.saved'+numb).html('Saved');
                     $('#hiddensub').click();
                });
            
        });
    }
      </script>
		