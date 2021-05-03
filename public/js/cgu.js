'use strict';
let cguFunc = {
    loadData: function () {
        $.ajax({
            url: $('#weblayout').data('cgu'),
            type: 'GET'
        }).done(function (response) {
            if (response.cguAccepted === false) {
                cguFunc.showCgu(response.cguTemplate);
            }
        });
    },
    showCgu: function (template) {
        $('#footer').before(template);
        cguListener.onChange();
        cguListener.onClick();
        $('#modal_cgu').modal({backdrop: 'static', keyboard: false});
    },
    cguCheckox: function () {
        if ($('#cgu_checkbox').is(':checked')) {
            $('#cgu_validated').css('opacity', '1');
            return;
        }
        $('#cgu_validated').css('opacity', '0.5');
    },
    accepteCgu: function () {
        if ($('#cgu_checkbox').is(':checked') === false) {
            return;
        }

        $('#cgu_validated i').css('display', 'inherit');

        $.ajax({
            url: $('#weblayout').data('cguAccepted'),
            type: 'GET'
        }).done(function () {
            $('#modal_cgu').modal('hide');
            setTimeout(function () {
                $('#modal_cgu').remove();
            }, 1500);
        });
    }
};

let cguListener = {
    onLoad: function () {
        cguFunc.loadData();
    },
    onChange: function () {
        $('#cgu_checkbox').change(function () {
            cguFunc.cguCheckox();
        });
    },
    onClick: function () {
        $('#cgu_validated').click(function () {
            cguFunc.accepteCgu();
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        cguListener.onLoad();
    });
});