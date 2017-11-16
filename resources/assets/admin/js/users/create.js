(function ($) {
    $('.select2').select2();

    $(document).ready(function() {
        /*if ($(".belong-switch")[0]) {
            var elems = Array.prototype.slice.call(document.querySelectorAll('.belong-switch'));
            elems.forEach(function (html) {
                var switchery = new Switchery(html);
            });
        }

        var changeCheckbox = document.querySelector('.belong-switch');

        changeCheckbox.onchange = function () {
            if(changeCheckbox.checked) {
                $('#divCompanies').removeClass('hidden')
                $('#roles').val(2).trigger('change');
                $('#roles option:not(:selected)').prop('disabled', true);
            }
            else {
                $('#divCompanies').addClass('hidden');
                $('#roles').val(0).trigger('change');
                $('#roles option:not(:selected)').prop('disabled', false);
            }
        };*/

        $('#companies').val([]);

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
