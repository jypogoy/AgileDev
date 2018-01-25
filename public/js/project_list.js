function del(id, name) {

    $('.custom-text').html('<p>Are you sure you want to delete project <strong>' + name + '</strong>? Click OK to proceed.</p>');

    $('.ui.tiny.modal.delete')
    .modal({
        closable  : true,
        onDeny    : function(){
            // Do nothing
        },
        onApprove : function() {
            window.location = 'projects/delete/' + id;
        }
    })
    .modal('show')
    .modal('refresh');

}