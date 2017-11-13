(function($) {
    'use strict';

    var userId = null;
    var userName = null;

    $('#confirmUserDelete').on('show.bs.modal', function(e) {
        $("#pName").html( userName );
        $('#formConfirmDelete').attr('action','users/delete/'+userId);
    });

    $("a[name = 'btnUserDelete']").click(function(){
        userId = $(this).attr('data-user_id');
        userName = $(this).attr('data-user_name');
        $('#confirmUserDelete').modal('show');
    });
})(jQuery);
