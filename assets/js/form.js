require('moment');
require('../dist/bootstrap/bootstrap-datepicker/js/bootstrap-datepicker.min');

$(document).ready(function() {
    $("#form_term").datepicker({format: "dd-mm-yyyy", startView: 1});
});
