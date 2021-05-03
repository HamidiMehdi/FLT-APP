'use strict';
let workingDecisionFunc = {
    showDescReject: function () {
        let divDescReject = $('#row_desc_working_reject');
        let divReport = $('#row_report');

        if ($('#working_request_answer_isAccepted_1').is(':checked')) {
            divDescReject.removeClass('hidden');
            divReport.addClass('hidden');

            return;
        }

        divDescReject.addClass('hidden');
        divReport.removeClass('hidden');
    },
    checkDescReject: function () {
        let descReject = $('#working_request_answer_descriptionWorkingReject');
        let labelDescReject = $('#error_desc_reject');

        if ($('#working_request_answer_isAccepted_1').is(':checked') && descReject.val().trim() === '') {
            descReject.addClass('error');
            labelDescReject.removeClass('hidden');
            return false;
        }

        descReject.removeClass('error');
        labelDescReject.addClass('hidden');
        descReject.val(descReject.val().trim());
        return true
    },
    showModalValidation: function () {
        if (!workingDecisionFunc.checkDescReject()) {
            return;
        }

        $('#modal_decision_working').modal('show');
    },
    submitForm: function () {
        if (!workingDecisionFunc.checkDescReject()) {
            $('#modal_decision_working').modal('hide');
            return;
        }

        $('form').submit();
    }
};

let workingDecisionListener = {
    onLoad: function () {
        workingDecisionListener.onClick();
        workingDecisionListener.onChange();
    },
    onClick: function () {
        $('button#popup_validation_choice').unbind().click(function () {
            workingDecisionFunc.showModalValidation();
        });
        $('button#decision_validated').unbind().click(function () {
            workingDecisionFunc.submitForm();
        });
    },
    onChange: function () {
        $('input[type="radio"][name="working_request_answer[isAccepted]"]').change(function() {
            workingDecisionFunc.showDescReject();
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        workingDecisionListener.onLoad();
    });
});