'use strict';
let reportWorkingFunc = {
    checkReport: function () {
        let report = $('#form_report');
        let errorReport = $('#error_report');

        if (report.val().trim() === '') {
            report.addClass('error');
            errorReport.removeClass('hidden');
            return false;
        }

        report.removeClass('error');
        errorReport.addClass('hidden');
        return true;
    },
    popupValidation: function () {
        if (!reportWorkingFunc.checkReport()) {
            return;
        }

        $('#modal_report_validation').modal('show');
    }
};

let reportWorkingListener = {
    onLoad: function () {
        reportWorkingListener.onClick();
    },
    onClick: function () {
        $('button#report_validation').unbind().click(function () {
            reportWorkingFunc.popupValidation();
        });
        $('button#report_validated').unbind().click(function () {
            $('form').submit();
        })
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        reportWorkingListener.onLoad();
    });
});