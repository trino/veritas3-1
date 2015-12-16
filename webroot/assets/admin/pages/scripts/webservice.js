function handlewebservice(msg, controller, action, status){
    if(msg.indexOf("<BR>error") > -1){
        if(msg.indexOf("<BR>error1") >-1){
            var error = "ins_id";
        } else if (msg.indexOf("<BR>error2") >-1){
            var error = "ebs_id";
        }
        alert(controller + "/" + action + "=" + status + "/r/nAn error occured, ID " + error + "not found");
    } else {
        //alert(controller + "/" + action + "=" + status + "/r/nMessage: " + msg);
        if (controller == "rapid" && action == "cron_user" && status) {
            alert('Cron ran successfully.');
        }
    }
}