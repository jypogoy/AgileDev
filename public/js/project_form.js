$(function() {
    
    $("#dataForm input:text, #dataForm textarea").first().focus();

    $('#dataForm')
        .form({
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
        });
        
        // .submit(function(evt) {
        //     evt.preventDefault();
        //     alert('Submitted');
        // });    

    // $('.field').textcounter({
    //     type: "character",
    //     max: 15,
    //     stopInputAtMaximum: true
    // });

});

function test() {
    alert();
}