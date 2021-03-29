$(document).ready(function () {
    $("#depart_Search").click(function () {
        var form = $('form[name="depart"]');
        var url = '/user/'+$('#depart_department').val()+'/search';
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            //data: form.serialize(), // serializes the form's elements.
            success: function (data) {
                let oTblReport = $("#tblResults");
                oTblReport.DataTable ({
                    "data" :  data,
                    "columns" : [
                        { "data" : "id" },
                        { "data" : "email" }
                    ]
                });

            }
        });
    });

});