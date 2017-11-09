(function($) {
    $('#confirmDelete').on('show.bs.modal', function(e) {
        var companyId = $(e.relatedTarget).data('company_id');
        var companyName = $(e.relatedTarget).data('company_name');
        $("#pName").html( companyName );
        $('#formConfirmDelete').attr('action','companies/delete/'+companyId);
    });
})(jQuery);