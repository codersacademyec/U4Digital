(function($) {
    console.log('hola hola');
    $('#confirmDelete').on('show.bs.modal', function(e) {
        console.log('holaa');
        var companyId = $(e.relatedTarget).data('company_id');
        var companyName = $(e.relatedTarget).data('company_name');
        console.log(companyName);
        $("#pName").html( companyName );
        $('#formConfirmDelete').attr('action','companies/delete/'+companyId);
        //$("#delForm").attr('action', 'put your action here with productId');//e.g. 'domainname/products/' + productId

        /*$('#confirmDeleteBtn').click(function() {
            var companyId = $(e.relatedTarget).data('company_id');
        });*/
    });
})(jQuery);