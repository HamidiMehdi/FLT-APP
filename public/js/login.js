'use strict';
let loginFunc = {
    checkSubmit: function () {
        let username = $('#form_email');
        let errorLabel = $('#error_email');
        let good = true;

        if (username.val().trim() === "") {
            errorLabel.removeClass('hidden');
            username.addClass('error');
            good = false;
        } else {
            errorLabel.addClass('hidden');
            username.removeClass('error');
        }

        if (good === false) {
            return;
        }

        $('form.form').submit();
    }
};

let loginListener = {
    onLoad: function () {
        loginListener.onClick();
    },
    onClick: function () {
        $('button#form_submit').off('click').click(function (e) {
            e.preventDefault();
            loginFunc.checkSubmit();
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        loginListener.onLoad();
    });
});