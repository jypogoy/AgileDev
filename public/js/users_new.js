$(function () {
    
    $('#fieldUsername').focus();

    $('#newForm')
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

});