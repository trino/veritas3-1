<script>
client_id = '<?=$cid?>';
    doc_id = '<?=$did?>';
    profile_id = '<?= isset($_GET["driver"])?$_GET['driver']:'' ?>';
 <?php if($did) { ?>
        showforms('company_pre_screen_question.php');
        showforms('driver_application.php');
        showforms('driver_evaluation_form.php');
        showforms('document_tab_3.php');
    <?php } ?>
    var readTOS = '<?= addslashes($strings["forms_pleaseconfirm"]); ?>';
    var giveSIG = '<?= addslashes($strings["forms_signplease"]); ?>';
    var fillALL = '<?= addslashes($strings["forms_fillall"]); ?>';
    
    function getJsonFields(driverid)
    {
        $.ajax({
           url:'<?php echo $this->request->webroot;?>profiles/getJsonFields/'+driverid,
           success:function(res)
           {
            res = JSON.parse(res);
            //alert(res['applicants_email']);
             $('#tab0 input,#tab0 textarea').each(function(){
                //alert($(this).attr('name');
                if(res[$(this).attr('name')])
                {
                    <?php if($this->request->action!='add'){?>if($(this).val() == '')<?php }?>
                    $(this).val(res[$(this).attr('name')]);
                }
             });
           } 
        });
    }
$(function(){
    <?php
    if($this->request->params['action'] == 'addorder')
    {
        ?>
        getJsonFields('<?php echo $_GET['driver']?>');
        <?php
    }
    ?>
    
    <?php
    if($this->request->params['action'] != 'view' && $this->request->params['action'] != 'vieworder')
    {
       
    }
    ?>
    <?php
    if($this->request->params['action']=='vieworder')
    {
        ?>
        $('input').each(function(){
           $(this).attr('disabled','disabled'); 
        });
        $('select').each(function(){
           $(this).attr('disabled','disabled'); 
        });
        $('textarea').each(function(){
           $(this).attr('disabled','disabled'); 
        });
        <?php
    } 
    ?>
    var did = '<?php if(isset($did))echo $did;else echo '0';?>';
    var checker = 0;
    
   function save_driver(par,webroot)
    {
    var driver_id = '';
    $('.overlay-wrapper').show();
    var fields = par.find('input').serialize();
    var fields = fields+'&'+par.find('select').serialize();
    $.ajax({
        url:webroot+'clientApplication/saveDriver/<?php if(isset($_GET['driver']))echo $_GET['driver'];?>',
        data:fields,
        type:'post',
        success:function(res){
            $('#user_id').val(res);
            
            $('.overlay-wrapper').hide();
            
            
        }
    });
    
    }
 
   $('.notonclient').each(function(){
    $(this).removeClass('required');
   })
    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1970:2030',
        dateFormat: 'mm/dd/yy'
    });

    $('.links a:nth-child(5), .links p').css({'display':'none'});
   $('.steps input').change(function(){
    $(this).parent().find('.error').html('');
   }); 
   $('.buttonprev').click(function(){
        var par = $(this).closest('.steps');
        var draft = 0;
        checker = 0;
        var ch = '';
        var doc_id = par.find('.sub_docs_id').val();
        var sid = doc_id;
        
        par.hide();
            par.removeClass('active');
            var id = par.find('.buttonprev').attr('id').replace('buttonprev','');
            var type = par.find('input[name="document_type"]').val();
            var cl = par.find('.sub_docs_id').val();
            
                  id = parseInt(id);
            $('#step'+id).show();
            $('#step'+id).addClass('active');
            
   });
   $('.buttons').click(function(){
    var par = $(this).closest('.steps');
    <?php
        if($this->request->params['action'] == 'vieworder'){
            ?>
            
                par.hide();
                
                par.removeClass('active');
                var id = par.find('.buttons').attr('id').replace('button','');
                id = parseInt(id)+1;
                $('#step'+id).show();
                $('#step'+id).addClass('active');
                    
                
            <?php
            }
            else
            {
       ?>
        
        var draft = 1;
        
        var redir = 0;
        if($(this).attr('id').replace('save','') != $(this).attr('id')){
        draft = 0;
        redir = 1;
        }
        else{
        draft = 1;
        if($(this).attr('id').replace('draft','') != $(this).attr('id'))
        redir=1;
        }
        <?php if($this->request->controller=='Documents'){?>
        if ($(this).attr('title') == 'draft') {
            draft = 1;
        } else {
            draft = 0;
        }

        <?php }?>

        checker = 0;
        var ch = '';
        var doc_id = par.find('.sub_docs_id').val();
        var sid = doc_id;
        var isvalid = checkalltags("tab0");
        
        if(!isvalid)
        {
            return false;
        }
        else
        {
            if(doc_id == 18) {
                if (par.find('#sig8 .touched').val() != '1' && par.find('#sig8 .touched_edit8').val() != '1') {
                    par.find('#sig8').append('<span class="error deleteme" style="position:absolute; font-size:12px; background-color: white; z-index: 1;">'+giveSIG+'</span>');
                    //alert(giveSIG);
                    $('html,body').animate({
                            scrollTop: $('#sig8').offset().top},
                        'slow');
                    $(this).removeAttr('disabled');
                    $('.overlay-wrapper').hide();
                    return false;
                }
            }
            
            if($('.subform4 #subtab_2_1').attr('class')=='tab-pane active' && $('.subform4').attr('style')!='display: none;'){
                //alert('tes');
                var er = 0;

                $('.required').each(function(){
                    if($(this).val()=='' && $(this).attr('name')!='' && $(this).attr('name')!='undefined'  && $(this).attr('name'))
                    {
                        $(this).addClass('myerror');
                        $(this).attr('style','border-color:red');
                        er = 1;
                    }

                });
                if($('#sig2 .touched').val()!='1' && $('#sig2 .touched_edit2').val()!='1') {
                    par.find('#sig2').append('<span class="error deleteme" style="position:absolute; font-size:12px; background-color: white; z-index: 1;">'+giveSIG+'</span>');
                    $('html,body').animate({
                            scrollTop: $('#sig2').offset().top},
                        'slow');
                    er = 2;
                }
                else
                if($('#sig4 .touched').val()!='1' && $('#sig4 .touched_edit4').val()!='1') {
                    par.find('#sig4').append('<span class="error deleteme" style="position:absolute; font-size:12px; background-color: white; z-index: 1;">'+giveSIG+'</span>');
                    $('html,body').animate({
                            scrollTop: $('#sig4').offset().top},
                        'slow');
                    er = 2;
                } else if($('#sig1 .touched').val()!='1' && $('#sig1 .touched_edit1').val()!='1') {
                    par.find('#sig1').append('<span class="error deleteme" style="position:absolute; font-size:12px; background-color: white; z-index: 1;">'+giveSIG+'</span>');
                    $('html,body').animate({
                            scrollTop: $('#sig1').offset().top},
                        'slow');
                    er = 2;
                } else if($('#sig3 .touched').val()!='1' && $('#sig3 .touched_edit3').val()!='1') {
                    par.find('#sig3').append('<span class="error deleteme" style="position:absolute; font-size:12px; background-color: white; z-index: 1;">'+giveSIG+'</span>');
                    $('html,body').animate({
                            scrollTop: $('#sig3').offset().top},
                        'slow');
                    er = 2;
                }

                $(this).removeClass('myerror');
                //$(this).removeAttr('style');

                if(er){
                    $('.cont').removeAttr('disabled');
                    if(er==1){
                        alert(fillALL);
                        $('html,body').animate({
                                scrollTop: $('.myerror').offset().top},
                            'slow');
                        $('.overlay-wrapper').hide();
                        return false;
                    }
                    else
                    if(er==2){
                        $('.overlay-wrapper').hide();
                        return false;
                    }

                }
                else
                {

                    $('.cont').removeAttr('disabled');
                }
            }   
            par.find(".required:not('label')").each(function(){
            //alert($(this).attr('class'));
            if($(this).val() == '')
            {
                checker = 1;
                $(this).parent().find('.error').html('This field is required');
                $(this).focus();
                $('html,body').animate({ scrollTop: $(this).offset().top}, 'slow');
                return false;
                
            }
            else{
                if($(this).attr('role')=='email' && $(this).val()!='')
                {
                    var em = $(this).val();
                    if(em.replace('@','') == em || em.replace('.','') == em)
                    {
                        checker = 1;
                        $(this).parent().find('.error').html('Invalid Email');
                        $(this).focus();
                        $('html,body').animate({ scrollTop: $(this).offset().top}, 'slow');
                        return false;
                    }
                }
            }
        });
        
       
            if(checker == 0){
                <?php if($this->request->controller!= "Documents"){?>
                par.hide();
                <?php }?>
                par.removeClass('active');
                var id = par.find('.buttons').attr('id').replace('button','');
                var type = par.find('input[name="document_type"]').val();
                var cl = par.find('.sub_docs_id').val();
                if(type=='driver_form')
                {
                     save_driver(par,'<?php echo $this->request->webroot;?>');
                      id = parseInt(id)+1;
                $('#step'+id).show();
                $('#step'+id).addClass('active');
                    
                }
                else
                {
                    
                   <?php if($this->request->controller=='Documents')
                        {?>
                          var uploaded_for1 = $('#selecting_driver').val();
                          var user_id = '<?php echo $this->request->session()->read('Profile.id');?>';  
                   <?php }else{?>
                            var uploaded_for1 = $('#user_id').val();
                            var user_id = uploaded_for1
                    <?php }?> 
                     var data = {
                        uploaded_for: uploaded_for1,
                        type: type,
                        sub_doc_id: sid,
                        user_id: user_id,
                        <?php
                        if($this->request->params['action']=='addorder')
                        {
                            ?>
                            division: $('#division').val()
                            <?php
                        }
                        ?>
                        //division: $('#division').val(),
                        //attach_doc: attach_docs
                    };
                    
                    $.ajax({
                        //data:'uploaded_for='+$('#uploaded_for').val(),
                        data: data,
                        type: 'post',
                        beforeSend: function(){$('.overlay-wrapper').show()},
                        url: '<?php echo $this->request->webroot;?>clientApplication/savedoc/<?php echo $cid;?>/'+did+'/<?php if($this->request->params['action']!='addorder'){?>?document=' + type + '&<?php }else echo "?";?>draft=' + draft+'&order_type=<?php if(isset($_GET['order_type']))echo $_GET['order_type'];?>&forms=<?php if(isset($_GET['forms']))echo $_GET['forms'];?>',
                        success: function (res) {
        
                            $('#did').val(res);
                            did = res;
                            
                            if (sid == "1") {
                                var forms = $(".tab-pane.active").prev('.tab-pane').find(':input'),
                                    url = '<?php echo $this->request->webroot;?>clientApplication/savePrescreening<?php if($this->request->params['action']!='addorder'){?>/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?><?php }?>',
                                    order_id = res,
                                    cid = '<?php echo $cid;?>';
                                savePrescreen(url, order_id, cid, draft,redir);
        
                            } else if (sid == "2") {
                                var order_id = res,
                                    cid = '<?php echo $cid;?>',
                                    url = '<?php echo $this->request->webroot;?>clientApplication/savedDriverApp/' + order_id + '/' + cid<?php if($this->request->params['action']!='addorder'){?> + '/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>'<?php }?>;
                                savedDriverApp(url, order_id, cid,draft,redir);
                            } else if (sid == "3") {
                                var order_id = res,
                                    cid = '<?php echo $cid;?>',
                                    url = '<?php echo $this->request->webroot;?>clientApplication/savedDriverEvaluation/' + order_id + '/' + cid<?php if($this->request->params['action']!='addorder'){?> + '/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>'<?php }?>;
                                savedDriverEvaluation(url, order_id, cid,draft,redir);
                            } else if (sid == "4") {
                                save_signature('3');
                                save_signature('4');
                                save_signature('5');
                                save_signature('6');
                                    var order_id = res,
                                    cid = '<?php echo $cid;?>',
                                    url = '<?php echo $this->request->webroot;?>clientApplication/savedMeeOrder/' + order_id + '/' + cid<?php if($this->request->params['action']!='addorder'){?> +'/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>'<?php } ?>;
                                    setTimeout(function(){
                                    savedMeeOrder(url, order_id, cid, type,draft,redir);}, 1000);
                               
        
                            }
                            else if (sid == "9") {
        
                                //alert(type);
                                var order_id = res,
                                    cid = '<?php echo $cid;?>',
                                    url = '<?php echo $this->request->webroot;?>clientApplication/saveEmployment/' + order_id + '/' + cid<?php if($this->request->params['action']!='addorder'){?>+'/?user_id='+user_id + '&document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>'<?php }?>;
                                saveEmployment(url, order_id, cid, type,draft,redir);
                            }
                            else if (sid == "10") {
        
                                //alert(type);
                                var order_id = res,
                                    cid = '<?php echo $cid;?>',
                                    url = '<?php echo $this->request->webroot;?>clientApplication/saveEducation/' + order_id + '/' + cid<?php if($this->request->params['action']!='addorder'){?>+'/?user_id='+user_id + '&document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>'<?php }?>;
                                saveEducation(url, order_id, cid, type,draft,redir);
                            }
                            else if (sid == "6") {
                                var order_id = res,
                                    cid = '<?php echo $cid;?>',
                                    url = '<?php echo $this->request->webroot;?>feedbacks/add/' + order_id + '/' + cid<?php if($this->request->params['action']!='addorder'){?> + '/?document=' + type + '&draft=' + draft<?php }?>;
                                var param = $('#form_tab6').serialize()<?php if($this->request->params['action'] == 'addorder'){?>+'&order_id='+order_id<?php }?>;
                                $.ajax({
                                    url: url,
                                    data: param,
                                    type: 'POST',
                                    <?php if($this->request->params['action']!='addorder'){?>
                                    success: function (res) {
                                        if (res == 'OK'){
                                           <?php if($this->request->controller=='Documents')
                                            {?>
                                                window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                                         <?php }else{
                                            
                                            ?>
                                                if(redir == 1 )
                                                {
                                                    window.location = '<?php echo $this->request->webroot;?>orders/orderslist?flash';
                                                }
                                                $('.overlay-wrapper').hide();
                                         <?php }?>
                                        }
                                    }
                                    <?php  }?>
                                });
        
                            }
                            else if (sid == "5") {
                                var order_id = res,
                                    cid = '<?php echo $cid;?>',
                                    url = '<?php echo $this->request->webroot;?>feedbacks/addsurvey/' + order_id + '/' + cid<?php if($this->request->params['action'] != 'addorder'){?> + '/?document=' + type + '&draft=' + draft<?php }?>;
                                var param = $('#form_tab5').serialize()<?php if($this->request->params['action'] == 'addorder'){?>+'&order_id='+order_id<?php }?>;
                                $.ajax({
                                    url: url,
                                    data: param,
                                    type: 'POST',
                                    <?php if($this->request->params['action']!='addorder'){?>
                                    success: function (res) {
                                        if (res == 'OK'){
                                           <?php if($this->request->controller=='Documents')
                                            {?>
                                                window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                                         <?php }else{?>
                                                if(redir == 1 )
                                                {
                                                    window.location = '<?php echo $this->request->webroot;?>orders/orderslist?flash';
                                                }
                                                $('.overlay-wrapper').hide();
                                         <?php }?>
                                        }
                                    }
                                    <?php }?>
                                });
        
                            }
                            else if (sid == "7") {
                                var act = $('#form_tab7').attr('action');
        
                                $('#form_tab7').attr('action', function (i, val) {
                                    return val + '?draft=' + draft;
                                });
                                $('#form_tab7').submit();
        
        
                            }
                            else if (sid == "8") {
                                var act = $('#form_tab8').attr('action');
        
                                $('#form_tab8').attr('action', function (i, val) {
                                    return val + '?draft=' + draft;
                                });
        
                                $('#form_tab8').submit();
        
        
                            }
                            else if(sid == '11')
                            {
                                var act = $('#form_tab11').attr('action');
        
                                $('#form_tab11').attr('action', function (i, val) {
                                    return val + '?draft=' + draft;
                                });
        
                                $('#form_tab11').submit();
        
                            }
                            else if (sid == "15") {
                                //alert('test');return;
                                var order_id = res,
                                    cid = '<?php echo $cid;?>',
                                    url = '<?php echo $this->request->webroot;?>clientApplication/mee_attach/' + order_id + '/' + cid<?php if($this->request->params['action'] != 'addorder'){?> + '/?document=' + type + '&draft=' + draft<?php if(isset($_GET['order_id'])){?>+'&order_id=<?php echo $_GET['order_id'];?>'<?php }}else{?> + '?draft='+draft<?php }?>;
                                var param = $('#form_tab15').serialize();
                                $.ajax({
                                    url: url,
                                    data: param,
                                    type: 'POST',
                                    
                                    success: function (res) {
                                        <?php if($this->request->params['action']!='addorder'){?>
                                        <?php if($this->request->controller=='Documents')
                                            {?>
                                                window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                                         <?php }else{?>
                                                $('.overlay-wrapper').hide();
                                         <?php }?>
                                          <?php }
                                         else{
                                            ?>
                                            if(redir == 1 )
                                                {
                                                    window.location = '<?php echo $this->request->webroot;?>orders/orderslist?flash';
                                                }
                                            $('.overlay-wrapper').hide();
                                            <?php
                                         }
                                         ?>
                                         }
                                        
        
        
                                });
        
                            }
                            else
                            if (sid == "18") {
                                if($('#test8').parent().parent().find('.touched').val()=='1'){
                                $.when(save_signature('8')).done(function(d1){
                                    $('#gfs_signature').val(d1);
                                    var order_id = res,
                                        cid = '<?php echo $cid;?>',
                                        url = '<?php echo $this->request->webroot;?>clientApplication/application_employment/'+ cid +'/'+ order_id + '/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>&user_id='+user_id+'&uploaded_for='+uploaded_for1;
                                    var param = $('#form_tab18').serialize();
                                     $.ajax({
                                        url: url,
                                        data: param,
                                        type: 'POST',
                                        success: function (res) {
                                             <?php if($this->request->controller=='Documents')
                                            {?>
                                                window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                                         <?php }else{?>
                                         if(redir == 1 )
                                                {
                                                    window.location = '<?php echo $this->request->webroot;?>orders/orderslist?flash';
                                                }
                                                $('.overlay-wrapper').hide();
                                         <?php }?>
                                             }
            
            
                                    });
                                });
                               }
                               else
                               {
                                    var order_id = res,
                                        cid = '<?php echo $cid;?>',
                                        url = '<?php echo $this->request->webroot;?>clientApplication/application_employment/'+ cid +'/'+ order_id + '/?document=' + type + '&draft=' + draft+'<?php if(isset($_GET['order_id'])){?>&order_id=<?php echo $_GET['order_id'];}?>&user_id='+user_id+'&uploaded_for='+uploaded_for1;
                                    var param = $('#form_tab18').serialize();
                                     $.ajax({
                                        url: url,
                                        data: param,
                                        type: 'POST',
                                        success: function (res) {
                                             <?php if($this->request->controller=='Documents')
                                            {?>
                                                window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                                         <?php }else{?>
                                         if(redir == 1 )
                                                {
                                                    window.location = '<?php echo $this->request->webroot;?>orders/orderslist?flash';
                                                }
                                                $('.overlay-wrapper').hide();
                                         <?php }?>
                                             }
            
            
                                    });
                               } 
        
                            }
                            else{
                               <?php 
                                 if(isset($doc))
                                    foreach($doc as $dx)
                                        {
                                            if($dx->id >11)
                                            {
                                            ?>
                                if(type == "<?php echo addslashes($dx->title);?>")
                                {
                                    var act = $('#form_tab<?php echo $dx->id;?>').attr('action');
        
                                    $('#form_tab<?php echo $dx->id;?>').attr('action', function (i, val) {
                                        return val + '?draft=' + draft;
                                    });
        
                                    $('#form_tab<?php echo $dx->id;?>').submit();
                                }
        
                                <?php       }
                                        }
                                ?>
        
                            }
                             id = parseInt(id)+1;
                $('#step'+id).show();
                $('#step'+id).addClass('active');
                        }
                    });
                }
               
               
                
            }
        }
        <?php }?>
   });
    
});
    function save_signature(numb) {
        var d = $.Deferred();
        $("#test"+numb).data("jqScribble").save(function(imageData)
        {
            //alert($('#signature_company_witness2').parent().find('.touched').val());
            //if((numb=='1' && $('#recruiter_signature').parent().find('.touched').val()==1) || (numb=='3' && $('#criminal_signature_applicant').parent().find('.touched').val()==1) || (numb=='4' && $('#signature_company_witness').parent().find('.touched').val()==1) || (numb=='5' && $('#criminal_signature_applicant2').parent().find('.touched').val()==1) || (numb=='6' && $('#signature_company_witness2').parent().find('.touched').val()==1) || (numb=='8' && $('#gfs_signature').parent().find('.touched').val()==1)){
                $.post('<?php echo $this->request->webroot; ?>canvas/image_save.php', {imagedata: imageData}, function(response) {
                    d.resolve(response);
                    if(numb=='1') {
                        $('#recruiter_signature').val(response);
                    }
                    if(numb=='3') {
                        $('#criminal_signature_applicant').val(response);
                    }
                    if(numb=='4') {
                        $('#signature_company_witness').val(response);
                    }
                    if(numb=='5') {
                        $('#criminal_signature_applicant2').val(response);
                    }
                    if(numb=='6') {
                        $('#signature_company_witness2').val(response);
                    }
                    if(numb=='8') {
                        $('#gfs_signature').val(response);
                        
                    }
                    $('.saved'+numb).html('Saved');
                });
            //}



        });
        return d.promise();
    }
function savePrescreen(url, order_id, cid,draft,redir=0) {

        inputs = $('#form_tab1').serialize();

        $('#form_tab1 :disabled[name]').each(function () {
            inputs = inputs + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        var param = {
            order_id: order_id,
            cid: cid,
            inputs: inputs
        };
        $.ajax({
            url: url,
            data: param,
            type: 'POST',
            success: function (res) {
                 <?php if($this->request->controller=='Documents')
                    {?>
                        window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                 <?php }else{?>
                        if(redir == 1 )
                                                {
                                                    window.location = '<?php echo $this->request->webroot;?>orders/orderslist?flash';
                                                }
                        $('.overlay-wrapper').hide();
                 <?php }?>
                    
            }
        });
    }

    function savedDriverApp(url, order_id, cid,draft,redir=0) {
        var param = $('#form_tab2').serialize();
        $('#form_tab2 :disabled[name]').each(function () {
            param = param + '&' + $(this).attr('name') + '=' + $(this).val();
        });

        $.ajax({
            url: url,
            data: param,
            type: 'POST',
            success: function (res) {
               <?php if($this->request->controller=='Documents')
                    {?>
                        window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                 <?php }else{?>
                 if(redir == 1 )
                                                {
                                                    window.location = '<?php echo $this->request->webroot;?>orders/orderslist?flash';
                                                }
                        $('.overlay-wrapper').hide();
                 <?php }?>
            }
        });
    }
    function savedDriverEvaluation(url, order_id, cid,draft,redir=0) {
        var param = $('#form_tab3').serialize();
        $('#form_tab3 :disabled[name]').each(function () {
            param = param + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        $.ajax({
            url: url,
            data: param,
            type: 'POST',
            success: function (res) {
                <?php if($this->request->controller=='Documents')
                    {?>
                        window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                 <?php }else{?>
                 if(redir == 1 )
                                                {
                                                    window.location = '<?php echo $this->request->webroot;?>orders/orderslist?flash';
                                                }
                        $('.overlay-wrapper').hide();
                 <?php }?>
            }
        });
    }

    function savedMeeOrder(url, order_id, cid, type,draft,redir=0) {
        var param = $('#form_consent').serialize();
        $('#form_consent :disabled[name]').each(function () {
            param = param + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        
        $.ajax({
            url: url,
            data: param,
            type: 'POST',
            success: function (res) {
                 <?php if($this->request->controller=='Documents')
                    {?>
                        window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                 <?php }else{
                    if($this->request->params['action']=='addorder'){?>
                        
                        
                        
                        
                        $.ajax({
                                    url: '<?php echo $this->request->webroot;?>orders/createPdf/' + $('#did').val(),
                                    success:function()
                                    {
                                        if(redir == 1 )
                                                {
                                                    window.location = '<?php echo $this->request->webroot;?>orders/orderslist?flash';
                                                }
                                        $('.overlay-wrapper').hide();
                                    }
                                });
                
                
                
                
                        <?php }else{?>
                        $('.overlay-wrapper').hide();
                 <?php }}?>
            }
        });
    }

    function saveEmployment(url, order_id, cid, type,draft,redir=0) {

        var fields = $('#form_employment').serialize();
        $(':disabled[name]', '#form_employment').each(function () {
            fields = fields + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        var param = fields
        $.ajax({
            url: url,
            data: param,
            type: 'POST',
            success: function (rea) {
                 <?php if($this->request->controller=='Documents')
                    {?>
                        window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                 <?php }else{
                    if($this->request->params['action'] == 'addorder')
                    {
                        ?>
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>orders/createPdfEmployment/' + $('#did').val(),
                            success: function () {
                                if(redir == 1 )
                                                {
                                                    window.location = '<?php echo $this->request->webroot;?>orders/orderslist?flash';
                                                }
                                 $('.overlay-wrapper').hide();
                            }
                        });
                        <?php
                    }
                    else{
                    ?>
                        $('.overlay-wrapper').hide();
                 <?php }}?>
                
            }
        });
    }

    function saveEducation(url, order_id, cid, type,draft,redir=0) {
        //alert('test2');
        //$('#loading5').show();
        var fields = $('#form_education').serialize();
        $(':disabled[name]', '#form_education').each(function () {
            fields = fields + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        var param = fields
        $.ajax({
            url: url,
            data: param,
            type: 'POST',
            success: function (res) {
                 <?php if($this->request->controller=='Documents')
                    {?>
                        window.location = '<?php echo $this->request->webroot?>documents/index?flash';
                 <?php }else{
                    if($this->request->params['action']=='addorder')
                    {
                        ?>
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>orders/createPdfEducation/' + $('#did').val(),
                            success: function () {
                                if(redir == 1 )
                                                {
                                                    window.location = '<?php echo $this->request->webroot;?>orders/orderslist?flash';
                                                }
                                $('.overlay-wrapper').hide();
                            }
                        });
                        <?php
                    }else{
                    ?>
                        $('.overlay-wrapper').hide();
                 <?php }}?>
            }
        });
    }
function fileUpload(ID) {
        // e.preventDefault();

        var $type = $(".active").find("input[name='document_type']").val(),
            param = {
                type: 'order',
                doc_type: $type,
                order_id: '0',
                cid: '<?php echo $cid;?>'
            };
        if ($type == "Consent Form") {
            //get sub content tab active
            var subContent = $(".active #form_tab4").find('.tab-content .tab-pane.active form').attr('id');
            // debugger;
            if (subContent == "form_consent") {
                param.subtype = 'Consent Form';
            } else if (subContent == "form_employment") {
                param.subtype = 'Employment';
            } else if (subContent == "form_education") {
                param.subtype = 'Education';
            }
        }

        var upload = new AjaxUpload("#" + ID, {
            action: "<?php echo $this->request->webroot;?>clientApplication/fileUpload",
            enctype: 'multipart/form-data',
            data: param,
            name: 'myfile',
            onSubmit: function (file, ext) {

            },
            onComplete: function (file, response) {
                if (response != 'error') {
                    $('#' + ID).parent().find('.uploaded').text(response);
                    $('.' + ID).val(response);
                } else {
                    alert('Invalid file type.');
                }
            }

        });
    }
    
    function showforms(form_type)
    {
        //alert(form_type);
        if (form_type != "") {
            //$('.subform').load('<?php echo $this->request->webroot;?>documents/subpages/' + form_type);

            var url = '<?php echo $this->request->webroot;?>orders/getOrderData/<?php echo $cid;?>/' + doc_id + '/' + profile_id,
                param = {form_type: form_type};
            $.getJSON(url, param, function (res) {
                if (form_type == "company_pre_screen_question.php") {

                    if (res) {
                        $('#form_tab1').form('load', res);


                        if (res.legal_eligible_work_cananda == 1) {
                            // debugger;
                            jQuery('#legal_eligible_work_cananda_1').closest('span').addClass('checked');
                            // document.getElementById("legal_eligible_work_cananda_1").checked = true;
                        } else if (res.legal_eligible_work_cananda == 0) {
                            $('#form_tab1').find('#legal_eligible_work_cananda_0').closest('span').addClass('checked')
                        }

                        if (res.hold_current_canadian_pp == 1) {
                            $('#form_tab1').find('#hold_current_canadian_pp_1').closest('span').addClass('checked')
                        } else if (res.hold_current_canadian_pp == 0) {
                            $('#form_tab1').find('#hold_current_canadian_pp_0').closest('span').addClass('checked')

                        }

                        if (res.have_pr_us_visa == 1) {
                            $('#form_tab1').find('#have_pr_us_visa_1').closest('span').addClass('checked')
                        } else if (res.have_pr_us_visa == 0) {
                            $('#form_tab1').find('#have_pr_us_visa_0').closest('span').addClass('checked')

                        }

                        if (res.fast_card == 1) {
                            $('#form_tab1').find('#fast_card_1').closest('span').addClass('checked')
                        } else if (res.fast_card == 0) {
                            $('#form_tab1').find('#fast_card_0').closest('span').addClass('checked')

                        }

                        if (res.criminal_offence_pardon_not_granted == 1) {
                            $('#form_tab1').find('#criminal_offence_pardon_not_granted_1').closest('span').addClass('checked')
                        } else if (res.criminal_offence_pardon_not_granted == 0) {
                            $('#form_tab1').find('#criminal_offence_pardon_not_granted_0').closest('span').addClass('checked')

                        }

                        if (res.reefer_load == 1) {
                            $('#form_tab1').find('#reefer_load_1').closest('span').addClass('checked')
                        } else if (res.reefer_load == 0) {
                            $('#form_tab1').find('#reefer_load_0').closest('span').addClass('checked')

                        }
                    }

                    var prof_id = $('#uploaded_for').val();
                    if (prof_id) {
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>profiles/getProfileById/' + prof_id + '/1',
                            success: function (res2) {

                                if (res2) {

                                    var response = JSON.parse(res2);
                                    //alert(res2);

                                    var app_name = res2.replace('{"applicant_phone_number":"', '');
                                    var app_name = app_name.replace('","aplicant_name":"', ',');
                                    var app_name = app_name.replace('","applicant_email":"', ',');
                                    var app_name = app_name.replace('"}', '');
                                    var app_name_arr = app_name.split(',');
                                    app_name = app_name_arr[1];
                                    //app_name = app_name.replace('","applicant_email":"ttt@ttt.com"}','');
                                    $('#conf_driver_name').val(app_name);
                                    $('#conf_driver_name').attr('disabled', 'disabled');
                                    $('#form_tab1').find(':input').each(function () {
                                        var name_attr = $(this).attr('name');

                                        //alert(name_attr);
                                        if (response[name_attr]) {

                                            $(this).val(response[name_attr]);

                                            $(this).attr('disabled', 'disabled');

                                        }
                                    });
                                }

                            }
                        });
                    }


                    //$('input[type="radio"]').buttonset("refresh");
                    // end pre screening
                } else if (form_type == "driver_application.php") {

                    if (res) {
                        $('#form_tab2').form('load', res);

                        if (res.worked_for_client == 1) {
                            jQuery('#form_tab2').find('#worked_for_client_1').closest('span').addClass('checked')
                        } else if (res.worked_for_client == 0) {
                            $('#form_tab2').find('#worked_for_client_0').closest('span').addClass('checked')
                        }

                        if (res.confirm_check == 1) {
                            jQuery('#form_tab2').find('#confirm_check').closest('span').addClass('checked')
                        }

                        if (res.is_employed == 1) {
                            jQuery('#form_tab2').find('#is_employed_1').closest('span').addClass('checked')
                        } else if (res.is_employed == 0) {
                            $('#form_tab2').find('#is_employed_0').closest('span').addClass('checked')
                        }

                        if (res.age_21 == 1) {
                            $('#form_tab2').find('#age_21_1').closest('span').addClass('checked')
                        } else if (res.age_21 == 0) {
                            $('#form_tab2').find('#age_21_0').closest('span').addClass('checked')

                        }

                        if (res.proof_of_age == 1) {
                            $('#form_tab2').find('#proof_of_age_1').closest('span').addClass('checked')
                        } else if (res.proof_of_age == 0) {
                            $('#form_tab2').find('#proof_of_age_0').closest('span').addClass('checked')

                        }

                        if (res.proof_of_age == 1) {
                            $('#form_tab2').find('#proof_of_age_1').closest('span').addClass('checked')
                        } else if (res.proof_of_age == 0) {
                            $('#form_tab2').find('#proof_of_age_0').closest('span').addClass('checked')

                        }

                        if (res.convicted_criminal == 1) {
                            $('#form_tab2').find('#convicted_criminal_1').closest('span').addClass('checked')
                        } else if (res.convicted_criminal == 0) {
                            $('#form_tab2').find('#convicted_criminal_0').closest('span').addClass('checked')

                        }

                        if (res.denied_entry_us == 1) {
                            $('#form_tab2').find('#denied_entry_us_1').closest('span').addClass('checked')
                        } else if (res.denied_entry_us == 0) {
                            $('#form_tab2').find('#denied_entry_us_0').closest('span').addClass('checked')

                        }

                        if (res.denied_entry_us == 1) {
                            $('#form_tab2').find('#denied_entry_us_1').closest('span').addClass('checked')
                        } else if (res.denied_entry_us == 0) {
                            $('#form_tab2').find('#denied_entry_us_0').closest('span').addClass('checked')

                        }

                        if (res.positive_controlled_substance == 1) {
                            $('#form_tab2').find('#positive_controlled_substance_1').closest('span').addClass('checked')
                        } else if (res.positive_controlled_substance == 0) {
                            $('#form_tab2').find('#positive_controlled_substance_0').closest('span').addClass('checked')

                        }

                        if (res.refuse_drug_test == 1) {
                            $('#form_tab2').find('#refuse_drug_test_1').closest('span').addClass('checked')
                        } else if (res.refuse_drug_test == 0) {
                            $('#form_tab2').find('#refuse_drug_test_0').closest('span').addClass('checked')

                        }

                        if (res.breath_alcohol == 1) {
                            $('#form_tab2').find('#breath_alcohol_1').closest('span').addClass('checked')
                        } else if (res.breath_alcohol == 0) {
                            $('#form_tab2').find('#breath_alcohol_0').closest('span').addClass('checked')

                        }

                        if (res.fast_card == 1) {
                            $('#form_tab2').find('#fast_card_1').closest('span').addClass('checked')
                        } else if (res.fast_card == 0) {
                            $('#form_tab2').find('#fast_card_0').closest('span').addClass('checked')

                        }

                        if (res.not_able_perform_function_position == 1) {
                            $('#form_tab2').find('#not_able_perform_function_position_1').closest('span').addClass('checked')
                        } else if (res.not_able_perform_function_position == 0) {
                            $('#form_tab2').find('#not_able_perform_function_position_0').closest('span').addClass('checked')

                        }

                        if (res.physical_capable_heavy_manual_work == 1) {
                            $('#form_tab2').find('#physical_capable_heavy_manual_work_1').closest('span').addClass('checked')
                        } else if (res.physical_capable_heavy_manual_work == 0) {
                            $('#form_tab2').find('#physical_capable_heavy_manual_work_0').closest('span').addClass('checked')

                        }

                        if (res.injured_on_job == 1) {
                            $('#form_tab2').find('#injured_on_job_1').closest('span').addClass('checked')
                        } else if (res.injured_on_job == 0) {
                            $('#form_tab2').find('#injured_on_job_0').closest('span').addClass('checked')

                        }

                        if (res.willing_physical_examination == 1) {
                            $('#form_tab2').find('#willing_physical_examination_1').closest('span').addClass('checked')
                        } else if (res.willing_physical_examination == 0) {
                            $('#form_tab2').find('#willing_physical_examination_0').closest('span').addClass('checked')

                        }
                        if (res.ever_been_denied == 1) {
                            $('#form_tab2').find('#ever_been_denied_1').closest('span').addClass('checked')
                        } else if (res.ever_been_denied == 0) {
                            $('#form_tab2').find('#ever_been_denied_0').closest('span').addClass('checked')

                        }
                        if (res.suspend_any_license == 1) {
                            $('#form_tab2').find('#suspend_any_license_1').closest('span').addClass('checked')
                        } else if (res.suspend_any_license == 0) {
                            $('#form_tab2').find('#suspend_any_license_0').closest('span').addClass('checked')

                        }
                    }

                    var prof_id = $('#uploaded_for').val();
                    if (prof_id) {
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>profiles/getProfileById/' + prof_id + '/2',
                            success: function (res2) {


                                if (res2) {

                                    var response = JSON.parse(res2);

                                    $('#form_tab2').find(':input').each(function () {
                                        var name_attr = $(this).attr('name');

                                        //alert(name_attr);
                                        if (response[name_attr]) {

                                            $(this).val(response[name_attr]);

                                            $(this).attr('disabled', 'disabled');
                                        }

                                    });
                                }}
                        });
                    }


                    // driver applicaton ends
                } else if (form_type == "driver_evaluation_form.php") {
                    if (res) {
                        $('#form_tab3').form('load', res);


                        if (res.transmission_manual_shift == 1) {
                            $('#form_tab3').find('#transmission_manual_shift_1').closest('span').addClass('checked')
                        }
                        if (res.transmission_auto_shift == 2) {
                            $('#form_tab3').find('#transmission_auto_shift_2').closest('span').addClass('checked')
                        }

                        if (res.pre_hire == 1) {
                            $('#form_tab3').find('input[name="pre_hire"]').closest('span').addClass('checked')
                        }
                        if (res.post_accident == 2) {
                            $('#form_tab3').find('input[name="post_accident"]').closest('span').addClass('checked')
                        }
                        if (res.post_injury == 1) {
                            $('#form_tab3').find('input[name="post_injury"]').closest('span').addClass('checked')
                        }
                        if (res.post_training == 2) {
                            $('#form_tab3').find('input[name="post_training"]').closest('span').addClass('checked')
                        }
                        if (res.annual == 1) {
                            $('#form_tab3').find('input[name="annual"]').closest('span').addClass('checked')
                        }
                        if (res.skill_verification == 2) {
                            $('#form_tab3').find('input[name="skill_verification"]').closest('span').addClass('checked')
                        }

                        if (res.fuel_tank == 1) {
                            $('#form_tab3').find('input[name="fuel_tank"]').closest('span').addClass('checked')
                        }
                        if (res.all_gauges == 1) {
                            $('#form_tab3').find('input[name="all_gauges"]').closest('span').addClass('checked')
                        }
                        if (res.audible_air == 1) {
                            $('#form_tab3').find('input[name="audible_air"]').closest('span').addClass('checked')
                        }
                        if (res.wheels_tires == 1) {
                            $('#form_tab3').find('input[name="wheels_tires"]').closest('span').addClass('checked')
                        }
                        if (res.trailer_brakes == 1) {
                            $('#form_tab3').find('input[name="trailer_brakes"]').closest('span').addClass('checked')
                        }
                        if (res.trailer_airlines == 1) {
                            $('#form_tab3').find('input[name="trailer_airlines"]').closest('span').addClass('checked')
                        }
                        if (res.inspect_5th_wheel == 1) {
                            $('#form_tab3').find('input[name="inspect_5th_wheel"]').closest('span').addClass('checked')
                        }
                        if (res.cold_check == 1) {
                            $('#form_tab3').find('input[name="cold_check"]').closest('span').addClass('checked')
                        }
                        if (res.seat_mirror == 1) {
                            $('#form_tab3').find('input[name="seat_mirror"]').closest('span').addClass('checked')
                        }
                        if (res.coupling == 1) {
                            $('#form_tab3').find('input[name="coupling"]').closest('span').addClass('checked')
                        }
                        if (res.paperwork == 1) {
                            $('#form_tab3').find('input[name="paperwork"]').closest('span').addClass('checked')
                        }
                        if (res.lights_abs_lamps == 1) {
                            $('#form_tab3').find('input[name="lights_abs_lamps"]').closest('span').addClass('checked')
                        }
                        if (res.annual_inspection_strickers == 1) {
                            $('#form_tab3').find('input[name="annual_inspection_strickers"]').closest('span').addClass('checked')
                        }
                        if (res.cab_air_brake_checked == 1) {
                            $('#form_tab3').find('input[name="cab_air_brake_checked"]').closest('span').addClass('checked')
                        }
                        if (res.landing_gear == 1) {
                            $('#form_tab3').find('input[name="landing_gear"]').closest('span').addClass('checked')
                        }
                        if (res.emergency_exit == 1) {
                            $('#form_tab3').find('input[name="emergency_exit"]').closest('span').addClass('checked')
                        }

                        if (res.driving_follows_too_closely == 1) {
                            $('#form_tab3').find('#driving_follows_too_closely_1').closest('span').addClass('checked')
                        } else if (res.driving_follows_too_closely == 2) {
                            $('#form_tab3').find('#driving_follows_too_closely_2').closest('span').addClass('checked')
                        } else if (res.driving_follows_too_closely == 3) {
                            $('#form_tab3').find('#driving_follows_too_closely_3').closest('span').addClass('checked')
                        } else if (res.driving_follows_too_closely == 4) {
                            $('#form_tab3').find('#driving_follows_too_closely_4').closest('span').addClass('checked')
                        }


                        if (res.driving_improper_choice_lane == 1) {
                            $('#form_tab3').find('#driving_improper_choice_lane_1').closest('span').addClass('checked')
                        } else if (res.driving_improper_choice_lane == 2) {
                            $('#form_tab3').find('#driving_improper_choice_lane_2').closest('span').addClass('checked')
                        } else if (res.driving_improper_choice_lane == 3) {
                            $('#form_tab3').find('#driving_improper_choice_lane_3').closest('span').addClass('checked')
                        } else if (res.driving_improper_choice_lane == 4) {
                            $('#form_tab3').find('#driving_improper_choice_lane_4').closest('span').addClass('checked')
                        }


                        if (res.driving_fails_use_mirror_properly == 1) {
                            $('#form_tab3').find('#driving_fails_use_mirror_properly_1').closest('span').addClass('checked')
                        } else if (res.driving_fails_use_mirror_properly == 2) {
                            $('#form_tab3').find('#driving_fails_use_mirror_properly_2').closest('span').addClass('checked')
                        } else if (res.driving_fails_use_mirror_properly == 3) {
                            $('#form_tab3').find('#driving_fails_use_mirror_properly_3').closest('span').addClass('checked')
                        } else if (res.driving_fails_use_mirror_properly == 4) {
                            $('#form_tab3').find('#driving_fails_use_mirror_properly_4').closest('span').addClass('checked')
                        }

                        if (res.driving_signal == 1) {
                            $('#form_tab3').find('#driving_signal_1').closest('span').addClass('checked')
                        } else if (res.driving_signal == 2) {
                            $('#form_tab3').find('#driving_signal_2').closest('span').addClass('checked')
                        } else if (res.driving_signal == 3) {
                            $('#form_tab3').find('#driving_signal_3').closest('span').addClass('checked')
                        } else if (res.driving_signal == 4) {
                            $('#form_tab3').find('#driving_signal_4').closest('span').addClass('checked')
                        }

                        if (res.driving_fail_use_caution_rr == 1) {
                            $('#form_tab3').find('#driving_fail_use_caution_rr_1').closest('span').addClass('checked')
                        } else if (res.driving_fail_use_caution_rr == 2) {
                            $('#form_tab3').find('#driving_fail_use_caution_rr_2').closest('span').addClass('checked')
                        } else if (res.driving_fail_use_caution_rr == 3) {
                            $('#form_tab3').find('#driving_fail_use_caution_rr_3').closest('span').addClass('checked')
                        } else if (res.driving_fail_use_caution_rr == 4) {
                            $('#form_tab3').find('#driving_fail_use_caution_rr_4').closest('span').addClass('checked')
                        }

                        if (res.driving_speed == 1) {
                            $('#form_tab3').find('#driving_speed_1').closest('span').addClass('checked')
                        } else if (res.driving_speed == 2) {
                            $('#form_tab3').find('#driving_speed_2').closest('span').addClass('checked')
                        } else if (res.driving_speed == 3) {
                            $('#form_tab3').find('#driving_speed_3').closest('span').addClass('checked')
                        } else if (res.driving_speed == 4) {
                            $('#form_tab3').find('#driving_speed_4').closest('span').addClass('checked')
                        }

                        if (res.driving_incorrect_use_clutch_brake == 1) {
                            $('#form_tab3').find('#driving_incorrect_use_clutch_brake_1').closest('span').addClass('checked')
                        } else if (res.driving_incorrect_use_clutch_brake == 2) {
                            $('#form_tab3').find('#driving_incorrect_use_clutch_brake_2').closest('span').addClass('checked')
                        } else if (res.driving_incorrect_use_clutch_brake == 3) {
                            $('#form_tab3').find('#driving_incorrect_use_clutch_brake_3').closest('span').addClass('checked')
                        } else if (res.driving_incorrect_use_clutch_brake == 4) {
                            $('#form_tab3').find('#driving_incorrect_use_clutch_brake_4').closest('span').addClass('checked')
                        }

                        if (res.driving_accelerator_gear_steer == 1) {
                            $('#form_tab3').find('#driving_accelerator_gear_steer_1').closest('span').addClass('checked')
                        } else if (res.driving_accelerator_gear_steer == 2) {
                            $('#form_tab3').find('#driving_accelerator_gear_steer_2').closest('span').addClass('checked')
                        } else if (res.driving_accelerator_gear_steer == 3) {
                            $('#form_tab3').find('#driving_accelerator_gear_steer_3').closest('span').addClass('checked')
                        } else if (res.driving_accelerator_gear_steer == 4) {
                            $('#form_tab3').find('#driving_accelerator_gear_steer_4').closest('span').addClass('checked')
                        }

                        if (res.driving_incorrect_observation_skills == 1) {
                            $('#form_tab3').find('#driving_incorrect_observation_skills_1').closest('span').addClass('checked')
                        } else if (res.driving_incorrect_observation_skills == 2) {
                            $('#form_tab3').find('#driving_incorrect_observation_skills_2').closest('span').addClass('checked')
                        } else if (res.driving_incorrect_observation_skills == 3) {
                            $('#form_tab3').find('#driving_incorrect_observation_skills_3').closest('span').addClass('checked')
                        } else if (res.driving_incorrect_observation_skills == 4) {
                            $('#form_tab3').find('#driving_incorrect_observation_skills_4').closest('span').addClass('checked')
                        }

                        if (res.driving_respond_instruction == 1) {
                            $('#form_tab3').find('#driving_respond_instruction_1').closest('span').addClass('checked')
                        } else if (res.driving_respond_instruction == 2) {
                            $('#form_tab3').find('#driving_respond_instruction_2').closest('span').addClass('checked')
                        } else if (res.driving_respond_instruction == 3) {
                            $('#form_tab3').find('#driving_respond_instruction_3').closest('span').addClass('checked')
                        } else if (res.driving_respond_instruction == 4) {
                            $('#form_tab3').find('#driving_respond_instruction_4').closest('span').addClass('checked')
                        }

                        if (res.cornering_signaling == 1) {
                            $('#form_tab3').find('#cornering_signaling_1').closest('span').addClass('checked')
                        } else if (res.cornering_signaling == 2) {
                            $('#form_tab3').find('#cornering_signaling_2').closest('span').addClass('checked')
                        } else if (res.cornering_signaling == 3) {
                            $('#form_tab3').find('#cornering_signaling_3').closest('span').addClass('checked')
                        } else if (res.cornering_signaling == 4) {
                            $('#form_tab3').find('#cornering_signaling_4').closest('span').addClass('checked')
                        }

                        if (res.cornering_speed == 1) {
                            $('#form_tab3').find('#cornering_speed_1').closest('span').addClass('checked')
                        } else if (res.cornering_speed == 2) {
                            $('#form_tab3').find('#cornering_speed_2').closest('span').addClass('checked')
                        } else if (res.cornering_speed == 3) {
                            $('#form_tab3').find('#cornering_speed_3').closest('span').addClass('checked')
                        } else if (res.cornering_speed == 4) {
                            $('#form_tab3').find('#cornering_speed_4').closest('span').addClass('checked')
                        }

                        if (res.cornering_fails == 1) {
                            $('#form_tab3').find('#cornering_fails_1').closest('span').addClass('checked')
                        } else if (res.cornering_fails == 2) {
                            $('#form_tab3').find('#cornering_fails_2').closest('span').addClass('checked')
                        } else if (res.cornering_fails == 3) {
                            $('#form_tab3').find('#cornering_fails_3').closest('span').addClass('checked')
                        } else if (res.cornering_fails == 4) {
                            $('#form_tab3').find('#cornering_fails_4').closest('span').addClass('checked')
                        }

                        if (res.cornering_proper_set_up_turn == 1) {
                            $('#form_tab3').find('#cornering_proper_set_up_turn_1').closest('span').addClass('checked')
                        } else if (res.cornering_proper_set_up_turn == 2) {
                            $('#form_tab3').find('#cornering_proper_set_up_turn_2').closest('span').addClass('checked')
                        } else if (res.cornering_proper_set_up_turn == 3) {
                            $('#form_tab3').find('#cornering_proper_set_up_turn_3').closest('span').addClass('checked')
                        } else if (res.cornering_proper_set_up_turn == 4) {
                            $('#form_tab3').find('#cornering_proper_set_up_turn_4').closest('span').addClass('checked')
                        }

                        if (res.cornering_turns == 1) {
                            $('#form_tab3').find('#cornering_turns_1').closest('span').addClass('checked')
                        } else if (res.cornering_turns == 2) {
                            $('#form_tab3').find('#cornering_turns_2').closest('span').addClass('checked')
                        } else if (res.cornering_turns == 3) {
                            $('#form_tab3').find('#cornering_turns_3').closest('span').addClass('checked')
                        } else if (res.cornering_turns == 4) {
                            $('#form_tab3').find('#cornering_turns_4').closest('span').addClass('checked')
                        }


                        if (res.cornering_wrong_lane_impede == 1) {
                            $('#form_tab3').find('#cornering_wrong_lane_impede_1').closest('span').addClass('checked')
                        } else if (res.cornering_wrong_lane_impede == 2) {
                            $('#form_tab3').find('#cornering_wrong_lane_impede_2').closest('span').addClass('checked')
                        } else if (res.cornering_wrong_lane_impede == 3) {
                            $('#form_tab3').find('#cornering_wrong_lane_impede_3').closest('span').addClass('checked')
                        } else if (res.cornering_wrong_lane_impede == 4) {
                            $('#form_tab3').find('#cornering_wrong_lane_impede_4').closest('span').addClass('checked')
                        }

                        if (res.shifting_smooth_take_off == 1) {
                            $('#form_tab3').find('#shifting_smooth_take_off_1').closest('span').addClass('checked')
                        } else if (res.shifting_smooth_take_off == 2) {
                            $('#form_tab3').find('#shifting_smooth_take_off_2').closest('span').addClass('checked')
                        } else if (res.shifting_smooth_take_off == 3) {
                            $('#form_tab3').find('#shifting_smooth_take_off_3').closest('span').addClass('checked')
                        } else if (res.shifting_smooth_take_off == 4) {
                            $('#form_tab3').find('#shifting_smooth_take_off_4').closest('span').addClass('checked')
                        }

                        if (res.shifting_proper_gear_selection == 1) {
                            $('#form_tab3').find('#shifting_proper_gear_selection_1').closest('span').addClass('checked')
                        } else if (res.shifting_proper_gear_selection == 2) {
                            $('#form_tab3').find('#shifting_proper_gear_selection_2').closest('span').addClass('checked')
                        } else if (res.shifting_proper_gear_selection == 3) {
                            $('#form_tab3').find('#shifting_proper_gear_selection_3').closest('span').addClass('checked')
                        } else if (res.shifting_proper_gear_selection == 4) {
                            $('#form_tab3').find('#shifting_proper_gear_selection_4').closest('span').addClass('checked')
                        }

                        if (res.shifting_proper_clutching == 1) {
                            $('#form_tab3').find('#shifting_proper_clutching_1').closest('span').addClass('checked')
                        } else if (res.shifting_proper_clutching == 2) {
                            $('#form_tab3').find('#shifting_proper_clutching_2').closest('span').addClass('checked')
                        } else if (res.shifting_proper_clutching == 3) {
                            $('#form_tab3').find('#shifting_proper_clutching_3').closest('span').addClass('checked')
                        } else if (res.shifting_proper_clutching == 4) {
                            $('#form_tab3').find('#shifting_proper_clutching_4').closest('span').addClass('checked')
                        }

                        if (res.shifting_gear_recovery == 1) {
                            $('#form_tab3').find('#shifting_gear_recovery_1').closest('span').addClass('checked')
                        } else if (res.shifting_gear_recovery == 2) {
                            $('#form_tab3').find('#shifting_gear_recovery_2').closest('span').addClass('checked')
                        } else if (res.shifting_gear_recovery == 3) {
                            $('#form_tab3').find('#shifting_gear_recovery_3').closest('span').addClass('checked')
                        } else if (res.shifting_gear_recovery == 4) {
                            $('#form_tab3').find('#shifting_gear_recovery_4').closest('span').addClass('checked')
                        }

                        if (res.shifting_up_down == 1) {
                            $('#form_tab3').find('#shifting_up_down_1').closest('span').addClass('checked')
                        } else if (res.shifting_up_down == 2) {
                            $('#form_tab3').find('#shifting_up_down_2').closest('span').addClass('checked')
                        } else if (res.shifting_up_down == 3) {
                            $('#form_tab3').find('#shifting_up_down_3').closest('span').addClass('checked')
                        } else if (res.shifting_up_down == 4) {
                            $('#form_tab3').find('#shifting_up_down_4').closest('span').addClass('checked')
                        }

                        if (res.backing_uses_proper_set_up == 1) {
                            $('#form_tab3').find('#backing_uses_proper_set_up_1').closest('span').addClass('checked')
                        }

                        if (res.backing_path_before_while_driving == 1) {
                            $('#form_tab3').find('#backing_path_before_while_driving_1').closest('span').addClass('checked')
                        } else if (res.backing_path_before_while_driving == 2) {
                            $('#form_tab3').find('#backing_path_before_while_driving_2').closest('span').addClass('checked')
                        }

                        if (res.backing_use_4way_flashers_city_horn == 1) {
                            $('#form_tab3').find('#backing_use_4way_flashers_city_horn_1').closest('span').addClass('checked')
                        } else if (res.backing_use_4way_flashers_city_horn == 2) {
                            $('#form_tab3').find('#backing_use_4way_flashers_city_horn_2').closest('span').addClass('checked')
                        }

                        if (res.backing_show_certainty_while_steering == 1) {
                            $('#form_tab3').find('#backing_show_certainty_while_steering_1').closest('span').addClass('checked')
                        } else if (res.backing_show_certainty_while_steering == 2) {
                            $('#form_tab3').find('#backing_show_certainty_while_steering_2').closest('span').addClass('checked')
                        }

                        if (res.backing_continually_uses_mirror == 1) {
                            $('#form_tab3').find('#backing_continually_uses_mirror_1').closest('span').addClass('checked')
                        } else if (res.backing_continually_uses_mirror == 2) {
                            $('#form_tab3').find('#backing_continually_uses_mirror_2').closest('span').addClass('checked')
                        }

                        if (res.backing_maintain_proper_seed == 1) {
                            $('#form_tab3').find('#backing_maintain_proper_seed_1').closest('span').addClass('checked')
                        }

                        if (res.backing_complete_reasonable_time_fashion == 1) {
                            $('#form_tab3').find('#backing_complete_reasonable_time_fashion_1').closest('span').addClass('checked')
                        }

                        if (res.recommended_for_hire == 1) {
                            $('#form_tab3').find('#recommended_for_hire_1').closest('span').addClass('checked')
                        } else if (res.recommended_for_hire == 2) {
                            $('#form_tab3').find('#recommended_for_hire_2').closest('span').addClass('checked')
                        }

                        if (res.recommended_full_trainee == 1) {
                            $('#form_tab3').find('#recommended_full_trainee_1').closest('span').addClass('checked')
                        } else if (res.recommended_full_trainee == 2) {
                            $('#form_tab3').find('#recommended_full_trainee_0').closest('span').addClass('checked')

                        }
                        if (res.recommended_fire_hire_trainee == 1) {
                            $('#form_tab3').find('#recommended_fire_hire_trainee_1').closest('span').addClass('checked')
                        } else if (res.recommended_fire_hire_trainee == 2) {
                            $('#form_tab3').find('#recommended_fire_hire_trainee_0').closest('span').addClass('checked')
                        }
                    }

                    var prof_id = $('#uploaded_for').val();
                    if (prof_id) {
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>profiles/getProfileById/' + prof_id + '/3',
                            success: function (res2) {

                                if (res2) {

                                    var response = JSON.parse(res2);
                                    $('#form_tab3').find(':input').each(function () {
                                        var name_attr = $(this).attr('name');

                                        //alert(name_attr);
                                        if (response[name_attr]) {

                                            $(this).val(response[name_attr]);
                                            $(this).attr('disabled', 'disabled');


                                        }

                                    });
                                }
                            }
                        });
                    }


                    // end road test
                } else if (form_type == "document_tab_3.php") {


                    if (res) {

                        $('#form_consent').find(':input').each(function () {
                            if($(this).attr('class')!='touched' && $(this).attr('class')!='touched_edit3' && $(this).attr('class')!='touched_edit1' && $(this).attr('class')!='touched_edit2' && $(this).attr('class')!='touched_edit4'){
                                var $name = $(this).attr('name');

                                //alert(doc_id + " " + $name + " " + res[$name]);

                                if ($name != 'offence[]' && $name != 'date_of_sentence[]' && $name != 'location[]' && $name != 'attach_doc[]') {
                                    if (doc_id && $(this).val() == "") {
                                        $(this).val(res[$name]);
                                    }
                                }
                            }
                        });
                    }
                    var prof_id = $('#uploaded_for').val();
                    if (prof_id) {
                        $.ajax({
                            url: '<?php echo $this->request->webroot;?>profiles/getProfileById/' + prof_id + '/4',
                            success: function (res2) {

                                if (res2) {

                                    var response = JSON.parse(res2);
                                    $('#form_consent').find(':input').each(function () {
                                        var name_attr = $(this).attr('name');

                                        //alert(name_attr);
                                        if (response[name_attr]) {

                                            $(this).val(response[name_attr]);
                                            $(this).attr('disabled', 'disabled');

                                        }

                                    });
                                }
                            }
                        });
                    }


                    // assignValue('form_tab4',res);
                    // mee order ends
                }
            });
        }
        else
            $('.subform').html("");
    }

</script>
