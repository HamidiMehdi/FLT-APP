'use strict';
let workingListFunc = {
    checkDateFormat: function () {
        let regExp = new RegExp(/\b\d{1,2}[\/-]\d{1,2}[\/-]\d{4}\b/);
        return regExp.test($('input#date_filter').val().trim());
    },
    dateFilter: {
        timer: undefined,
        startTimer: function () {
            let el = $('#date_filter');
            if (this.timer !== null) {
                clearTimeout(this.timer);
            }
            this.timer = setTimeout(function () {
                workingListFunc.dateFilter.filter(el);
            }, 400);
        },
        filter: function (el) {
            let dateFilter = el.val().trim() ? el.val().trim() : null;
            let status = $('select#status').val();
            workingListFunc.loadAjax(1, dateFilter, status);
        }
    },
    statusChange: function() {
        let dateFilter = $('input#date_filter').val().trim() ? $('input#date_filter').val().trim() : null;
        let status = $('select#status').val();
        workingListFunc.loadAjax(1, dateFilter, status);
    },
    changePagePagination: function (el) {
        if (el.hasClass('cursor-not-allowed') || el.hasClass('disabled')) {
            return;
        }

        let dateFilter = $('input#date_filter').val().trim() ? $('input#date_filter').val().trim() : null;
        let status = $('select#status').val();
        workingListFunc.loadAjax(el.data('page'), dateFilter, status);
    },
    loadAjax: function (page, dateFilter, status)
    {
        let dateFilterInput = $('#date_filter');
        let dateFilterError = $('#error_date_filter');
        if (dateFilter && !workingListFunc.checkDateFormat()) {
            dateFilterInput.addClass('error');
            dateFilterError.removeClass('hidden');
            return;
        }
        dateFilterInput.removeClass('error');
        dateFilterError.addClass('hidden');

        $.ajax({
            url: $('#url_data_working').data('url'),
            type: 'GET',
            contentType: 'json',
            data: {
                page: page,
                date_filter: dateFilter,
                status: status
            }
        }).done(function (response) {
            $('#working_list').replaceWith(response.html);
            workingListListener.eventPagination();
        });
    }
};

let workingListListener = {
    onLoad: function () {
        workingListListener.filterEvent();
        workingListListener.eventPagination();
    },
    eventPagination: function () {
        $('.paginate_button').unbind().click(function () {
            workingListFunc.changePagePagination($(this));
        });
    },
    filterEvent: function() {
        $('#date_filter').datepicker({
            format: 'dd/mm/YYYY',
            onSelect: function() {
                workingListFunc.dateFilter.startTimer();
            }
        });
        $('select#status').change(function () {
            workingListFunc.statusChange();
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        workingListListener.onLoad();
    });
});