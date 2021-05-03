'use strict';
let aip5Func = {
    addGoalAnnualInterview: function () {
        let index = $('button#add_goal_annual_interview');
        let prototype = index.data('prototype').replace(/__name__/g, index.data('index'));
        index.data('index', index.data('index') + 1);

        $('div#content_goal_annual_interview').append(prototype);
        aip5Listener.onClick();
    },
    removeGoalAnnualInterview: function (el) {
        let formationAnnualInterview = $(el).parents('div.macro-goal-annual-interview');
        formationAnnualInterview.next().remove();
        formationAnnualInterview.remove();
    }
};

let aip5Listener = {
    onLoad: function () {
        aip5Listener.onClick();
    },
    onClick: function () {
        $('button#add_goal_annual_interview').off('click').click(function () {
            aip5Func.addGoalAnnualInterview();
        });
        $('button.remove-goal-annual-interview').off('click').click(function () {
            aip5Func.removeGoalAnnualInterview(this);
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        aip5Listener.onLoad();
    });
});