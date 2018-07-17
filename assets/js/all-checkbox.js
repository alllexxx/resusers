var $ = require('jquery');

$(document).ready(function() {
    $("#check_all").click(function () {
        if (!$("#check_all").is(":checked"))
            $(".checkbox").removeAttr("checked");
        else
            $(".checkbox").attr("checked","checked");
    });
});
