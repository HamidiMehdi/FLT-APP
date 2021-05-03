'use strict';
let interviewProgressFunc = {
    search: {
        timer: undefined,
        startTimer: function (el) {
            if (this.timer !== null) {
                clearTimeout(this.timer);
            }
            this.timer = setTimeout(function () {
                interviewProgressFunc.search.search(el);
            }, 400);
        },
        search: function (el) {
            let nav = el.data('nav');
            let search = el.val().trim() === '' ? null : el.val().trim();
            interviewProgressFunc.loadAjax(1, search, nav);
        }
    },
    changePagePagination: function (el) {
        if (el.hasClass('cursor-not-allowed') || el.hasClass('disabled')) {
            return;
        }

        let nav = el.parents('.pagination-array').data('nav');
        let search = $('input.search-array[data-nav="' + nav + '"]').val().trim();
        search = search === '' ? null : search;
        interviewProgressFunc.loadAjax(el.data('page'), search, nav);
    },
    loadAjax: function (page, search, nav) {
        $.ajax({
            url: $('#url_data_interview').data('url'),
            type: 'GET',
            contentType: 'json',
            data: {
                page: page,
                search: search,
                nav: nav
            }
        }).done(function (response) {
            $('.pagination-array[data-nav="' + nav + '"]').replaceWith(response.html);
            interviewProgressListener.eventPagination();
        });
    },
    handlerArrayNav: function (el) {
        $('ul.nav-tab-interview li').removeClass('active');
        $(el).addClass('active');

        $('.tab-pane').removeClass('active');
        $('.tab-pane[data-nav="' + $(el).data('nav') + '"]').addClass('active');
    }
};

let interviewProgressListener = {
    onLoad: function () {
        interviewProgressListener.eventPagination();
        interviewProgressListener.onClick();
    },
    eventPagination: function () {
        $('input.search-array').unbind().keyup(function () {
            interviewProgressFunc.search.startTimer($(this));
        });
        $('.paginate_button').unbind().click(function () {
            interviewProgressFunc.changePagePagination($(this));
        });
    },
    onClick: function () {
        $('ul.nav-tab-interview li').unbind().click(function () {
            interviewProgressFunc.handlerArrayNav($(this));
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        interviewProgressListener.onLoad();
    });
});