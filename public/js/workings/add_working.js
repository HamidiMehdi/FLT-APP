'use strict';
let addWorkingFunc = {
    start_at: undefined,
    end_at: undefined,
    ajax_allowed: true,
    openModalAddWorking: function () {
        if (!addWorkingFunc.formValidation() || !addWorkingFunc.checkDateInterval() || !addWorkingFunc.ajax_allowed) {
            return;
        }

        let spinner = $('#add_working i');
        spinner.css('display', 'inherit');

        addWorkingFunc.ajax_allowed = false
        $.ajax({
            url: $('#url_check_working').data('url'),
            type: 'GET',
            contentType: 'json',
            data: {
                start: addWorkingFunc.getStrDateFormat(addWorkingFunc.start_at),
                end: addWorkingFunc.getStrDateFormat(addWorkingFunc.end_at)
            }
        }).done(function (response) {
            console.log(response);
            if (response.hasOwnProperty("periodExist") && response.periodExist === false) {
                $('#modal_add_working').modal('show');
                spinner.css('display', 'none');
                addWorkingFunc.ajax_allowed = true;
                return;
            }

            toastr.options = {
                closeButton: true,
                debug: false,
                progressBar: true,
                preventDuplicates: false,
                positionClass: 'toast-top-right',
                onclick: null,
                showDuration: 400,
                hideDuration: 1000,
                timeOut: 4500,
                extendedTimeOut: 1000,
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut"
            };
            toastr.error('Il existe déjà une demande (en attente ou accepté) concernant une des dates que vous avez selectionée.', 'Erreur');
            spinner.css('display', 'none');
            addWorkingFunc.ajax_allowed = true;
        }).error(function () {
            spinner.css('display', 'none');
            addWorkingFunc.ajax_allowed = true;
        });
    },
    checkDateInterval: function () {
        let startAtField = $('#new_working_startAt');
        let startAtError = $('#error_date');

        let periodStartAt = $('#new_working_periodStartAt');
        let periodEndAt = $('#new_working_periodEndAt');

        if (addWorkingFunc.start_at > addWorkingFunc.end_at) {
            startAtField.addClass('error');
            startAtError.removeClass('hidden');
            return false;
        }

        if(addWorkingFunc.start_at.getTime() === addWorkingFunc.end_at.getTime() && periodStartAt.val() === 'PM' && periodEndAt.val() === 'AM') {
            startAtField.addClass('error');
            startAtError.removeClass('hidden');
            periodStartAt.addClass('error');
            return false;
        }

        startAtField.removeClass('error');
        startAtError.addClass('hidden');
        periodStartAt.removeClass('error');
        return true;
    },
    checkDateFormat: function (el) {
        let regExp = new RegExp(/\b\d{1,2}[\/-]\d{1,2}[\/-]\d{4}\b/);
        return regExp.test($(el).val().trim());
    },
    formValidation: function () {
        let startAt = $('#new_working_startAt');
        let endAt = $('#new_working_endAt');
        let full = true;

        if (!startAt.val().trim() || !addWorkingFunc.checkDateFormat(startAt)) {
            startAt.addClass('error');
            $('#error_start_at').removeClass('hidden');
            full = false;
        } else {
            startAt.removeClass('error');
            $('#error_start_at').addClass('hidden');
        }

        if (!endAt.val().trim() || !addWorkingFunc.checkDateFormat(endAt)) {
            endAt.addClass('error');
            $('#error_end_at').removeClass('hidden');
            full = false;
        } else {
            endAt.removeClass('error');
            $('#error_end_at').addClass('hidden');
        }

        return full;
    },
    formSubmit: function () {
        $('form').submit();
    },
    getStrDateFormat: function (date) {
        let day = date.getDate();
        let month = date.getMonth()+1;
        let year = date.getFullYear();

        day = day < 10 ? '0' + day : day;
        month = month < 10 ? '0' + month : month;

        return year + '/' + month + '/' + day;
    }
};

let addWorkingListener = {
    onLoad: function () {
        addWorkingListener.onClick();
        addWorkingListener.datepicker();
    },
    onClick: function () {
        $('button#add_working').unbind().click(function () {
            addWorkingFunc.openModalAddWorking()
        });
        $('button#add_working_validated').unbind().click(function () {
            addWorkingFunc.formSubmit();
        });
    },
    datepicker: function () {
        $('#new_working_startAt').datepicker({
            format: 'dd/mm/YYYY',
            minDate: new Date(),
            onSelect: function(dateText, date) {
                addWorkingFunc.start_at = date;
            },
            onRenderCell: function (date, cellType) {
                if (cellType === 'day') {
                    let day = date.getDay();
                    let isDisabled = [0, 6].indexOf(day) != -1;

                    return {
                        disabled: isDisabled
                    }
                }
            }
        });

        $('#new_working_endAt').datepicker({
            format: 'dd/mm/YYYY',
            minDate: new Date(),
            onSelect: function(dateText, date) {
                addWorkingFunc.end_at = date;
            },
            onRenderCell: function (date, cellType) {
                if (cellType === 'day') {
                    let day = date.getDay();
                    let isDisabled = [0, 6].indexOf(day) != -1;

                    return {
                        disabled: isDisabled
                    }
                }
            }
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        addWorkingListener.onLoad();
    });
});