function save_driver(par,webroot)
{
    var driver_id = '';
    $('.overlay-wrapper').show();
    var fields = par.find('input').serialize();
    var fields = fields+'&'+par.find('select').serialize();
    $.ajax({
        url:webroot+'clientApplication/saveDriver',
        data:fields,
        type:'post',
        success:function(res){
            $('#user_id').val(res);
            $('.overlay-wrapper').hide();
            
        }
    })
    
}
