'use strict';
let pip2Func = {
    handleFormationWishes: function (ck) {
        let div = $('.formation-wishes');
        if ($(ck).is(':checked')) {
            div.css('display', 'block');
            return;
        }
        div.css('display', 'none');
    },
    handleGeographicMobility: function (ck) {
        let div = $('.geographic-mobility');
        if ($(ck).is(':checked')) {
            div.css('display', 'block');
            return;
        }
        div.css('display', 'none');
    }
};

let pip2Listener = {
    onLoad: function () {
        pip2Listener.onChange();
    },
    onChange: function () {
        $('#interview_pro_p2_formationWishes').off('change').change(function () {
            pip2Func.handleFormationWishes(this);
        });
        $('#interview_pro_p2_geographicMobility').off('change').change(function () {
            pip2Func.handleGeographicMobility(this);
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        pip2Listener.onLoad();
    });
});