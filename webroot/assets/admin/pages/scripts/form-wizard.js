var path =  document.URL; //window.location.pathname;

function webroot(){
    var txt =  document.URL;
    var position = txt.indexOf("/", 8);
    var test = txt.indexOf("localhost");
    if( test>0 ) {
        position = txt.indexOf("/", position + 1);
    } else {
        return "/";
    }
    return txt.substr(0,position) + "/";
}

function GetParam(Name){
    return (location.search.split(Name + '=')[1]||'').split('&')[0]
}

var base_url = webroot();

var table=0;
var draft = 0;
$(function(){
    $(document.body).on('click', '.skip', function () {
        draft=1;
        //alert(draft);
    });
    $(document.body).on('click', '.button-next', function () {
        draft=0;
        //alert(draft);
    });
    if($('#tablename').val()=='pre_screening') {
        table = 0;
    }else if($('#tablename').val()=='driver_application') {
        table = 1;
    } else if($('#tablename').val()=='road_test') {
        table = 2;
    } else if($('#tablename').val()=='consent_form') {
        table = 3;
    }
});



var FormWizard = function () {
    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().bootstrapWizard) {
                return;
            }

            function format(state) {
                if (!state.id) return state.text; // optgroup
                return "<img class='flag' src='../../assets/global/img/flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
            }

            $("#country_list").select2({
                placeholder: 'Select',
                allowClear: true,
                formatResult: format,
                formatSelection: format,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            var form = $('#submit_form');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    //account
                    username: {
                        minlength: 5,
                        required: true
                    },
                    password: {
                        minlength: 5,
                        required: true
                    },
                    rpassword: {
                        minlength: 5,
                        required: true,
                        equalTo: "#submit_form_password"
                    },
                    //profile
                    fullname: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true
                    },
                    gender: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    city: {
                        required: true
                    },
                    country: {
                        required: true
                    },
                    //payment
                    card_name: {
                        required: true
                    },
                    card_number: {
                        minlength: 16,
                        maxlength: 16,
                        required: true
                    },
                    card_cvc: {
                        digits: true,
                        required: true,
                        minlength: 3,
                        maxlength: 4
                    },
                    card_expiry_date: {
                        required: true
                    },
                    'payment[]': {
                        required: true,
                        minlength: 1
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    'payment[]': {
                        required: 'SelectOne',
                        minlength: jQuery.validator.format('SelectOne')
                    }
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    if (element.attr("name") == "gender") { // for uniform radio buttons, insert the after the given container
                        error.insertAfter("#form_gender_error");
                    } else if (element.attr("name") == "payment[]") { // for uniform checkboxes, insert the after the given container
                        error.insertAfter("#form_payment_error");
                    } else {
                        error.insertAfter(element); // for other inputs, just perform default behavior
                    }
                },
                invalidHandler: function (event, validator) { //display error alert on form submit
                    success.hide();
                    error.show();
                    Metronic.scrollTo(error, -200);
                },
                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
                },
                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },
                success: function (label) {
                    if (label.attr("for") == "gender" || label.attr("for") == "payment[]") { // for checkboxes and radio buttons, no need to show OK icon
                        label
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                        label.remove(); // remove error label here
                    } else { // display success icon for other inputs
                        label
                            .addClass('valid') // mark the current input as valid and display OK icon
                            .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                    }
                },
                submitHandler: function (form) {
                    success.show();
                    error.hide();
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                }
            });
            var displayConfirm = function() {
                $('#tab4 .form-control-static', form).each(function(){
                    var input = $('[name="'+$(this).attr("data-display")+'"]', form);
                    if (input.is(":radio")) {
                        input = $('[name="'+$(this).attr("data-display")+'"]:checked', form);
                    }
                    if (input.is(":text") || input.is("textarea")) {
                        $(this).html(input.val());
                    } else if (input.is("select")) {
                        $(this).html(input.find('option:selected').text());
                    } else if (input.is(":radio") && input.is(":checked")) {
                        $(this).html(input.attr("data-title"));
                    } else if ($(this).attr("data-display") == 'payment[]') {
                        var payment = [];
                        $('[name="payment[]"]:checked', form).each(function(){
                            payment.push($(this).attr('data-title'));
                        });
                        $(this).html(payment.join("<br>"));
                    }
                });
            }
            var handleTitle = function(tab, navigation, index) {
                //return false;
                
                var total = navigation.find('li').length;
                if(table)
                index = table;
                var current = index + 1;
                //else
                

                // set wizard title
                $('.step-title', $('#form_wizard_1')).text('Step ' + (index + 1) + ' of ' + total);
                // set done steps
                jQuery('li', $('#form_wizard_1')).removeClass("done");
                var li_list = navigation.find('li');
                for (var i = 0; i < index; i++) {
                    jQuery(li_list[i]).addClass("done");
                }
                
                if (current == 1) {
                    $('#divison').removeAttr('disabled');
                    $('#form_wizard_1').find('.button-previous').hide();
                } else {
                    $('#divison').attr('disabled','disabled');
                    $('#form_wizard_1').find('.button-previous').show();
                }
                
                if(current == (total-1)) {
                    $('.cont').html(Submit);
                    $('.cont').attr('onclick','return false;');
                    $('.skip').html(SaveAsDraft);
                    
                    $('.skip').removeClass('button-next');
                    $('.nextview').each(function(){
                       $(this).attr('style','visibility: hidden;'); 
                    });
                    if($('#dr').val()=='0') {
                        $('.skip').attr('disabled','disabled');
                    }
                    
                    



                    // $('.cont').attr('id','');
                } else{
                    $('.skip').html(SaveAsDraft);
                    //$('.skip').removeClass('button-next');
                    //$('.skip').removeClass('save_as_draft');
                    $('.cont').not('.skip').each(function(){
                       if($(this).attr('id')!='submit_dra') {
                        $(this).html(SaveAndContinue + ' <i class="m-icon-swapright m-icon-white"></i>');
                        $(this).attr('id','draft');
                       } 
                    });
                    
                    $('.nextview').removeAttr('style');
                    //$('.skip').html('Skip <i class="m-icon-swapdown m-icon-white"></i>');
                    $('.skip').removeAttr('disabled');
                    $('.cont').removeAttr('onclick');
                    //$('.skip').addClass('button-next');

                }
                if(current==total) {
                    
                    $('.cont').attr('id','submit_ord');
                    $('.skip').attr('id','submit_dra');
                    $('.skip').hide();

                    $('#select_division').hide();
                    $('.button-previous').hide();
                    $('#loading5').show();

                    /*
                     if(draft==1)
                     window.location = base_url+'?orderflash';
                     else
                     window.location = base_url+'?orderflash';
                    

                //   if($('#tab6 .touched').val()=='1' || $('#tab6 .touched_edit').val()=='1'){
                    setTimeout(
                  function()
                  {

                 window.location = base_url+'orders/orderslist?draft&flash';
                  }, 5500);
              //    }*/

                } else {
                  $('.skip').attr('id','submit_dra');  
                }

                if (current >= total) {
                    $('#form_wizard_1').find('.button-next').hide();
                    $('#form_wizard_1').find('.button-submit').show();
                    $('.uploaded_for').hide();

                    var count = 10;
                    //alert($('#did').val()+'/'+$('#uploaded_for').val());
                    save_signature('1');


                    displayConfirm();
                } else {
                    $('.uploaded_for').show();
                    $('#form_wizard_1').find('.button-next').show();
                    $('#form_wizard_1').find('.button-submit').hide();
                }
                Metronic.scrollTo($('.page-title'));
            }



            // default form wizard
            $('#form_wizard_1').bootstrapWizard({
                'nextSelector': '.button-next',
                'previousSelector': '.button-previous',
                onTabClick: function (tab, navigation, index, clickedIndex) {
                    //alert($('#viewingorder').val());
                    if($('#viewingorder').val()=='0')
                    return false;
                },
                onNext: function (tab, navigation, index) {
                    var ActiveTab = $('.tabber.active').attr('id');

                    var Reason = checktags(ActiveTab, "input");
                    if(Reason["Status"]){Reason = checktags(ActiveTab, "select");}

                    if(!Reason["Status"]) {return false;}

                    if ($('.tabber.active').attr('class').replace('confirmation') != $('.tabber.active').attr('class') || $('.tabber.active').attr('id') == 'tab19') {
                        //alert($('.tabber.active .touched_edit').val());
                        if($('.tabber.active').attr('class').replace('confirmation') != $('.tabber.active').attr('class')){
                            if ($('.tabber.active .touched').val() != '1' && $('.tabber.active .touched_edit').val() != '1') {
                                alert(SignPlease);
                                return false;
                            }
                        } else if ($('.tabber.active .touched').val() != '1' && $('.tabber.active .touched_edit8').val() != '1') {
                            alert(SignPlease);
                            $('html,body').animate({ scrollTop: $('#sig8').offset().top}, 'slow');
                            return false;
                        }
                    }
                    //alert(tab);

                    success.hide();
                    error.hide();

                    //required form elements
                    var saving_draft = false;
                    if (typeof Title === 'undefined'){/*add code to addorder.ctp myClickListener(); */} else {
                        saving_draft=Title.indexOf("draft") > -1;//chrome and firefox tested
                    }
                    var viewing = $('.button-next').attr('id') =='nextview';

                    if($('.tabber.active').attr('id') == 'tab16' && !saving_draft && !viewing){//Mee attachments, not saving as draft
                        var Forms =  GetParam("forms").split(",");
                        var MissingData = ""; //use DriverProvince
                        for(var i = 0; i < Forms.length; i++){//loop through product numbers
                            if(Forms[i] == 1603) {//Premium National Criminal Record Check
                                if ($('.mee_att_1').val().length == 0 && $('.mee_att_2').val().length == 0) {//pieces of ID
                                    MissingData = MissingID;
                                }
                            }

                            if($('#mee_att_7').length>0){
                                if ($('.mee_att_7').val().length == 0) {//abstract form
                                    MissingData = MissingAbstract;
                                }
                            }

                        }
                        if(MissingData.length>0) {
                            alert(MissingData);
                            return false;
                        }
                    }

                    if($('.tabber.active').attr('id') == 'tab3'){
                        
                        
                        //Challenger Driver Application
                        if(!$('#confirm_check').is(':checked') && $('.button-next').attr('id')!='nextview') {
                            alert(readTOS);
                            $('#confirm_check').focus();
                            $('html,body').animate({scrollTop: $('#confirm_check').offset().top},'slow');
                            return false;
                        } else{
                            handleTitle(tab, navigation, index);
                        }
                    } else if($('.tabber.active').attr('id') == 'tab100000x'){//Challenger Driver Application
                        if(!$('#confirm_check1').is(':checked') ) {
                            alert(readTOS);
                            $('#confirm_check1').focus();
                            $('html,body').animate({ scrollTop: $('#confirm_check1').offset().top}, 'slow');
                            return false;
                        } else{
                            handleTitle(tab, navigation, index);
                        }
                    } else if($('#tab5').attr('class') == 'tabber tab-pane active' || $('#tab1').attr('class') == 'tabber tab-pane active') {
                        if($('#tab5').attr('class') == 'tabber tab-pane active'){
                            var curr = $('#tab5');
                            var form_name = 'consent';
                        } else{
                            var curr = $('#tab1');
                            var form_name = 'other';
                        }
                        
                        var er = 0;
                        
                        curr.find('.required').each(function(){
                            if($(this).val()=='' && $(this).attr('name')!='' && $(this).attr('name')!='undefined'  && $(this).attr('name')) {
                                $(this).attr('style','border-color:red');
                                $(this).addClass('myerror');
                                er = 1;
                            } else {
                                $(this).removeClass('myerror');
                                $(this).removeAttr('style');
                                
                            }
                        });
                        
                        if($('#check_div').val()=='1' && $('#divison').val()=='') {
                            er = 1;
                            $('#divison').addClass('myerror');
                            $('#divison').addClass('required');
                            $('#divison').attr('style','border-color:red');
                        } else {
                            $('#divison').removeClass('myerror');
                            $('#divison').removeAttr('style');
                        }
                        if(er){
                            alert(FillAll);
                            $('html,body').animate({scrollTop: $('.myerror').offset().top}, 'slow');
                            return false;
                        } else{
                            if(form_name == 'consent') {
                                if($('#sig2 .touched').val()!='1' && $('#sig2 .touched_edit2').val()!='1') {
                                    alert(SaveSig);
                                    $('html,body').animate({scrollTop: $('#sig2').offset().top},
                                    'slow');
                                    return false;
                                } else if($('#sig4 .touched').val()!='1' && $('#sig4 .touched_edit4').val()!='1') {
                                    alert(SaveSig);
                                    $('html,body').animate({scrollTop: $('#sig4').offset().top}, 'slow');
                                    return false;
                                }
                                else
                                if($('#sig1 .touched').val()!='1' && $('#sig1 .touched_edit1').val()!='1') {
                                    alert(SaveSig);
                                    $('html,body').animate({scrollTop: $('#sig1').offset().top},'slow');
                                    return false;
                                } else if($('#sig3 .touched').val()!='1' && $('#sig3 .touched_edit3').val()!='1') {
                                    alert(SaveSig);
                                    $('html,body').animate({scrollTop: $('#sig3').offset().top},  'slow');
                                    return false;
                                } else {
                                    handleTitle(tab, navigation, index);
                                }
                            } else {
                                handleTitle(tab, navigation, index);
                            }
                        }
                    } else{
                        //alert('test');
                        handleTitle(tab, navigation, index);
                    }
                },
                onPrevious: function (tab, navigation, index) {
                    success.hide();
                    error.hide();

                    handleTitle(tab, navigation, index);
                },
                onTabShow: function (tab, navigation, index) {
                    var total = navigation.find('li').length;
                    if(table){
                        index = table;
                        $('.form-wizard .tab-pane').removeClass('active');
                        $('.form-wizard .changeactive').attr('class','tab-pane active');
                        $('#subtab_2_1').addClass('active');
                        table = 0;
                    }
                    //alert(table);
                    var current = index + 1;
                    var $percent = (current / total) * 100;
                    $('#form_wizard_1').find('.progress-bar').css({
                        width: $percent + '%'
                    });

                }
            });
            if(table==0)
            $('#form_wizard_1').find('.button-previous').hide();
            $('#form_wizard_1 .button-submit').click(function () {
                alert(Success);
            }).hide();
        }

    };
}();
