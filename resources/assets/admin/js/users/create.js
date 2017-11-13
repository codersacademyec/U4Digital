(function ($) {
    $('.select2').select2();

    $(document).ready(function() {
        if ($(".belong-switch")[0]) {
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
        }
    });
})(jQuery);
