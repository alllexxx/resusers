var $ = require('jquery');

$(document).ready(function() {
    $("select").change(function(){
        $( "select option:selected" ).each(function() {
            if($(this).val()=='file') {
                //alert($(this).val());
                document.getElementById('form_annotation').value="Файл";
                document.getElementById('form_annotation').readOnly = true;
                document.getElementById('form_docFile_file').style.visibility='visible';

            } else {
                document.getElementById('form_docFile_file').style.visibility='hidden';
                document.getElementById('form_annotation').readOnly = false;
                document.getElementById('form_annotation').value="";
            }

        });
    });
}).change();
;