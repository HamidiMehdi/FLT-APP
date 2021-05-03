$(document).ready(function () {
    $(window).ready(function () {
        $('.flt-datepicker').datepicker({
            format: 'dd/mm/YYYY',
            maxDate: new Date()
        })
    });
});