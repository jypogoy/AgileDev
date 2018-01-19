$(function() {
    
    $("#dataForm input:text, #dataForm textarea").first().focus();


    $('#dataForm').on('submit', function (e) {
        //e.preventDefault();    
        alert(this.action);
    });

    // $('.field').textcounter({
    //     type: "character",
    //     max: 15,
    //     stopInputAtMaximum: true
    // });

});