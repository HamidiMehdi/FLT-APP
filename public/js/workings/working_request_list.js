'use strict';
let workingRequestListFunc = {
    checkDateFormat: function () {
        let regExp = new RegExp(/\b\d{1,2}[\/-]\d{1,2}[\/-]\d{4}\b/);
        return regExp.test($('input#date_filter').val().trim());
    },
    search: {
        timer: undefined,
        startTimer: function (el) {
            if (this.timer !== null) {
                clearTimeout(this.timer);
            }
            this.timer = setTimeout(function () {
                workingRequestListFunc.search.search(el);
            }, 400);
        },
        search: function (el) {
            let search = el.val().trim() === '' ? null : el.val().trim();
            let dateFilter = $('input#date_filter').val().trim() ? $('input#date_filter').val().trim() : null;
            let status = $('select#status').val();
            workingRequestListFunc.loadAjax(1, search, dateFilter, status);
        }
    },
    dateFilter: {
        timer: undefined,
        startTimer: function () {
            let el = $('#date_filter');
            if (this.timer !== null) {
                clearTimeout(this.timer);
            }
            this.timer = setTimeout(function () {
                workingRequestListFunc.dateFilter.filter(el);
            }, 400);
        },
        filter: function (el) {
            let search = $('input#search').val().trim() === '' ? null : $('input#search').val().trim();
            let dateFilter = el.val().trim() ? el.val().trim() : null;
            let status = $('select#status').val();
            workingRequestListFunc.loadAjax(1, search, dateFilter, status);
        }
    },
    statusChange: function() {
        let search = $('input#search').val().trim() === '' ? null : $('input#search').val().trim();
        let dateFilter = $('input#date_filter').val().trim() ? $('input#date_filter').val().trim() : null;
        let status = $('select#status').val();
        workingRequestListFunc.loadAjax(1, search, dateFilter, status);
    },
    changePagePagination: function (el) {
        if (el.hasClass('cursor-not-allowed') || el.hasClass('disabled')) {
            return;
        }

        let search = $('input#search').val().trim() === '' ? null : $('input#search').val().trim();
        let dateFilter = $('input#date_filter').val().trim() ? $('input#date_filter').val().trim() : null;
        let status = $('select#status').val();
        workingRequestListFunc.loadAjax(el.data('page'), search, dateFilter, status);
    },
    loadAjax: function (page, search, dateFilter, status)
    {
        let dateFilterInput = $('#date_filter');
        let dateFilterError = $('#error_date_filter');
        if (dateFilter && !workingRequestListFunc.checkDateFormat()) {
            dateFilterInput.addClass('error');
            dateFilterError.removeClass('hidden');
            return;
        }
        dateFilterInput.removeClass('error');
        dateFilterError.addClass('hidden');

        $.ajax({
            url: $('#url_data_working_request').data('url'),
            type: 'GET',
            contentType: 'json',
            data: {
                page: page,
                search: search,
                date_filter: dateFilter,
                status: status
            }
        }).done(function (response) {
            $('#working_request_list').replaceWith(response.html);
            workingRequestListListener.eventPagination();
        });
    }
};

let workingRequestListListener = {
    onLoad: function () {
        workingRequestListListener.filterEvent();
        workingRequestListListener.eventPagination();
    },
    eventPagination: function () {
        $('.paginate_button').unbind().click(function () {
            workingRequestListFunc.changePagePagination($(this));
        });
    },
    filterEvent: function() {
        $('input#search').unbind().keyup(function () {
            workingRequestListFunc.search.startTimer($(this));
        });
        $('.flt-datepicker').datepicker({
            format: 'dd/mm/YYYY',
            onSelect: function() {
                workingRequestListFunc.dateFilter.startTimer();
            }
        });
        $('select#status').change(function () {
            workingRequestListFunc.statusChange();
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        workingRequestListListener.onLoad();
    });
});