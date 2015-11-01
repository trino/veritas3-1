
function ajaxpage(baseurl){
    var visitortime = new Date();
    var key = visitortime.getTimezoneOffset()/-60;
    $.ajax({
        url: baseurl,
        data: 'time=' + key,
        type: 'get',
        success: function (res) {
            //alert("success: " + baseurl + " " + key);
        },
        fail: function (res) {
            //alert("fail: " + baseurl + " " + key);
        }
    });
}