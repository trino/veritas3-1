<script>
var readTOS = '<?= addslashes($strings["forms_pleaseconfirm"]); ?>';
    var giveSIG = '<?= addslashes($strings["forms_signplease"]); ?>';
    var fillALL = '<?= addslashes($strings["forms_fillall"]); ?>';
$(function(){
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
                            //alert(type);return false;
                            //alert(type);return false;
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

</script>
