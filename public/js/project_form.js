$(function() {
    
    $("#dataForm input:text, #dataForm textarea").first().focus();

    $('#dataForm').form(rules);

    $('#saveNewBtn').click(function() {
        $('#fieldSaveNew').val(true);
    });

    // $('.field').textcounter({
    //     type: "character",
    //     max: 15,
    //     stopInputAtMaximum: true
    // });

});

var rules = {

    fields : {
        username : {
            identifier : 'fieldName',
            rules : [
                {
                    type : 'empty',
                    prompt : 'Please enter a project name'
                }    
            ]
        }
    }
    
};
