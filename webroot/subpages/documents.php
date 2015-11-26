 <?php
     if($this->request->session()->read('debug')) {
         echo "<span style ='color:red;'>document.php #INC157</span>";
     }
 ?>

<div class="row">


	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

	<div class="portlet">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-shopping-cart"></i><?php echo $settings->document; ?>s
			</div>

		</div>

	</div>
	</div>


                <?php

                $class = array('blue-madison','red','yellow','purple','green', 'red-intense','yellow-saffron','grey-cascade','blue-steel','blue');


                $doc = $this->requestAction('/documents/getDocument');
                $i=0;
                if($doc){
                    //echo strtolower($document->document_type);
                    $form_type = "";
                    foreach($doc as $d)
                    {
                        //echo strtolower($d->title);
                        if(isset($document) && strtolower($d->title) == strtolower($document->document_type))
                             $form_type = $d->form;
                        //$prosubdoc = $this->requestAction('/profiles/getProSubDoc/'.$this->Session->read('Profile.id').'/'.$d->id);
                        $prosubdoc = $this->requestAction('/settings/all_settings/0/0/profile/'.$this->Session->read('Profile.id').'/'.$d->id);
                        if($i==11)
                            $i=0;
                        ?>
                        <?php if($prosubdoc['display'] > 1 && $d->display==1)
                        {?>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

    					<div class="dashboard-stat <?php echo $class[$i]; ?>">
                            <div class="whiteCorner"></div>

                            <div class="visual">
    							<i class="fa fa-shopping-cart"></i>
    						</div>
    						<div class="details">

    							<div class="desc">
    								 <?php echo ucfirst($d->title); ?>
    							</div>
    						</div>

    					       <a class="more" href="<?php echo $this->request->webroot;?>documents/type/<?php echo ($d->id);?>">
    						View more
    						</a>


    					</div>
                        <div class="dusk"></div>

                    </div>
                        <?php
                        }
                        $i++;
                    }

                }
                 ?>

			</div>