$(function () {
    
    if ($('#fieldUsername').is(':visible')) {
        $('#fieldUsername').focus();
    } else {
        $('#fieldFirstname').focus();    
    }    

    $('#dataForm')
            .form({
                fields : {
                    username : {
                        identifier : 'fieldUsername',
                        rules : [
                            {
                                type : 'empty',
                                prompt : 'Please enter your user ID'
                            }    
                        ]
                    },
                    password : {
                        identifier : 'fieldPassword',
                        rules : [
                            {
                                type : 'empty',
                                prompt : 'Please provide a password'
                            },
                            {
                                type   : 'minLength[6]',
                                prompt : 'Your password must be at least {ruleValue} characters long'
                            }
                        ]
                    },
                    passwordConfirm : {
                        identifier : 'fieldConfirmPassword',
                        rules : [
                            {
                                type : 'empty',
                                prompt : 'Please provide a password'
                            },
                            {
                                type   : 'minLength[6]',
                                prompt : 'Your password must be at least {ruleValue} characters long'
                            },
                            {
                                type : 'match[password]',
                                prompt : 'Password mismatched'
                            }
                        ]
                    }
                }          
            }, {
                onSuccess : function (e) {
                    e.preventDefault();
                }
            })
        ;
    
    // $('#dataForm').on('submit', function (e) {
    //     //e.preventDefault();    
    //     alert(this.action);
    // });
});