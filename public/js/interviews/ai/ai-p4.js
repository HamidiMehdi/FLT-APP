'use strict';
let aip4Func = {
    addFormationAnnualInterview: function () {
        let index = $('button#add_formation_annual_interview');
        let prototype = index.data('prototype').replace(/__name__/g, index.data('index'));
        index.data('index', index.data('index') + 1);

        $('div#content_formation_annual_interview').append(prototype);
        aip4Listener.onClick();
    },
    removeFormationAnnualInterview: function (el) {
        let formationAnnualInterview = $(el).parents('div.macro-formation-annual-interview');
        formationAnnualInterview.next().remove();
        formationAnnualInterview.remove();
    },
    addEvaluationFormationAnnualInterview: function () {
        let index = $('button#add_evaluation_formation_annual_interview');
        let prototype = index.data('prototype').replace(/__name__/g, index.data('index'));
        index.data('index', index.data('index') + 1);

        $('div#content_evaluation_formation_annual_interview').append(prototype);
        aip4Listener.onClick();
    },
    removeEvaluationFormationAnnualInterview: function (el) {
        let formationAnnualInterview = $(el).parents('div.macro-evaluation-formation-annual-interview');
        formationAnnualInterview.next().remove();
        formationAnnualInterview.remove();
    },
    addFormationDesiredAnnualInterview: function () {
        let index = $('button#add_formation_desired_annual_interview');
        let prototype = index.data('prototype').replace(/__name__/g, index.data('index'));
        index.data('index', index.data('index') + 1);

        $('div#content_formation_desired_annual_interview').append(prototype);
        aip4Listener.onClick();
    },
    removeFormationDesiredAnnualInterview: function (el) {
        let formationAnnualInterview = $(el).parents('div.macro-formation-desired-annual-interview');
        formationAnnualInterview.next().remove();
        formationAnnualInterview.remove();
    }
};

let aip4Listener = {
    onLoad: function () {
        aip4Listener.onClick();
    },
    onClick: function () {
        $('button#add_formation_annual_interview').off('click').click(function () {
            aip4Func.addFormationAnnualInterview();
        });
        $('button.remove-formation-annual-interview').off('click').click(function () {
            aip4Func.removeFormationAnnualInterview(this);
        });

        $('button#add_evaluation_formation_annual_interview').off('click').click(function () {
            aip4Func.addEvaluationFormationAnnualInterview();
        });
        $('button.remove-evaluation-formation-annual-interview').off('click').click(function () {
            aip4Func.removeEvaluationFormationAnnualInterview(this);
        });

        $('button#add_formation_desired_annual_interview').off('click').click(function () {
            aip4Func.addFormationDesiredAnnualInterview();
        });
        $('button.remove-formation-desired-annual-interview').off('click').click(function () {
            aip4Func.removeFormationDesiredAnnualInterview(this);
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        aip4Listener.onLoad();
    });
});