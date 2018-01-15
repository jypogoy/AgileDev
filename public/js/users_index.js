$(function() {
    
    $(".close.icon").click(function(){
        $(this).parent().fadeOut();
    });
    
    $('.ui.message').delay(5000).fadeOut();
    
    // new PNotify({
    //     title : 'Success',
    //     text : 'New user profile was created successfully.',
    //     type : 'success',
    //     nonblock : true,
    //     delay : 3000,
    //     hide : true,
    //     nonblock : {
    //         nonblock : false
    //     }
    // });
});