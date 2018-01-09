$(function () {
    
    $('.modal').on('shown.bs.modal', function () {
        $('#fieldUsername').focus();
    });
    
    $('form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type     : "POST",
            cache    : false,
            url      : $(this).attr('action'),
            data     : $(this).serialize(),
            success: function (data) {
                alert(data);
            },
            error: function (request, status, error) {
                alert(error);
            }
        })
        .done(function (data) {
            $('.modal').modal('toggle');
            new PNotify({
                title: 'Success',
                text: 'New user was created successfully.',
                type: 'success',
                nonblock: true,
                delay: 3000,
                hide: true
            });
        })
        .fail(function (error) {
            alert(JSON.stringify(error));
            new PNotify({
                title: 'Ooops!',
                text: 'Sorry. An error encountered while creating new record.',
                type: 'error',
                nonblock: true,
                delay: 5000,
                hide: true
            });
        });
    });

  });