(function ($) {
    $('.select2').select2();
    $('#companies').select2();

    $(document).ready(function() {

        var companies = jQuery.parseJSON($('#companies').attr('data-companies_user'));
        var rol = $('#companies').attr('data-role');
        var companiesSelected = [];
        for(var i=0;i<companies.length;i++) {
            companiesSelected.push(companies[i].company_id);
        }

        console.log(rol);
        if(rol == 'community_manager') {
            $('#companies').select2({multiple: true});
        }
        else {
            $('#companies').select2({multiple: false});
        }

        $('#companies').val(companiesSelected);
        $('#companies').trigger('change');

        // Bind an event
        $('.select2').on('select2:select', function (e) {
            var role = e.params.data.text;

            switch (role) {
                case 'system_admin':
                    $('#divCompanies').addClass('hidden');
                    break;
                case 'community_manager':
                    $('#divCompanies').removeClass('hidden');
                    $('#companies').select2({multiple:true, placeholder: 'Select companies'});
                    $('#companies').val([]);
                    break;
                case 'company_admin':
                    $('#divCompanies').removeClass('hidden');
                    $('#companies').select2({multiple:false, placeholder: 'Select one company'});
                    $('#companies').val([]);
                    break;
                case 'company_user':
                    break;
                default:
                    $('#divCompanies').addClass('hidden');
                    break;
            }
        });
    });
})(jQuery);