(function($) {
    'use strict';

    var companyId = null;
    var companyName = null;

    $('#confirmDelete').on('show.bs.modal', function(e) {
        $("#pName").html( companyName );
        $('#formConfirmDelete').attr('action','companies/delete/'+companyId);
    });

    $("a[name = 'btnCompanyDelete']").click(function(){
        companyId = $(this).attr('data-company_id');
        companyName = $(this).attr('data-company_name');
        $('#confirmDelete').modal('show');
    });
})(jQuery);