'use strict';
let aip2Func = {
    addBilan: function () {
        let index = $('button#add_bilan');
        let prototype = index.data('prototype').replace(/__name__/g, index.data('index'));
        index.data('index', index.data('index') + 1);

        $('div#content_bilan').append(prototype);
        aip2Listener.onClick();
    },
    removeBilan: function (el) {
        let bilan = $(el).parents('div.macro-bilan');
        bilan.next().remove();
        bilan.remove();
    }
};

let aip2Listener = {
    onLoad: function () {
        aip2Listener.onClick();
    },
    onClick: function () {
        $('button#add_bilan').off('click').click(function () {
            aip2Func.addBilan();
        });
        $('button.remove-bilan').off('click').click(function () {
            aip2Func.removeBilan(this);
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        aip2Listener.onLoad();
    });
});