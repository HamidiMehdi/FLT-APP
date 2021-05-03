'use strict';
let aip3Func = {
    addKnowHow: function () {
        let index = $('button#add_know_how');
        let prototype = index.data('prototype').replace(/__name__/g, index.data('index'));
        index.data('index', index.data('index') + 1);

        $('div#content_know_how').append(prototype);
        aip3Listener.onClick();
    },
    removeKnowHow: function (el) {
        let knowhow = $(el).parents('div.macro-know-how');
        knowhow.next().remove();
        knowhow.remove();
    },
    addKnowMake: function () {
        let index = $('button#add_know_make');
        let prototype = index.data('prototype').replace(/__name__/g, index.data('index'));
        index.data('index', index.data('index') + 1);

        $('div#content_know_make').append(prototype);
        aip3Listener.onClick();
    },
    removeKnowMake: function (el) {
        let knowmake = $(el).parents('div.macro-know-make');
        knowmake.next().remove();
        knowmake.remove();
    }
};

let aip3Listener = {
    onLoad: function () {
        aip3Listener.onClick();
    },
    onClick: function () {
        $('button#add_know_how').off('click').click(function () {
            aip3Func.addKnowHow();
        });
        $('button.remove-kow-how').off('click').click(function () {
            aip3Func.removeKnowHow(this);
        });
        $('button#add_know_make').off('click').click(function () {
            aip3Func.addKnowMake();
        });
        $('button.remove-kow-make').off('click').click(function () {
            aip3Func.removeKnowMake(this);
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        aip3Listener.onLoad();
    });
});