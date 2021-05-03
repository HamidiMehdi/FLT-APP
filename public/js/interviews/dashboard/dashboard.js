'use strict';
let dashboardFunc = {
    currentUserDeleted: null,
    loadDataGraph: function () {
        $.ajax({
            url: $('#data_analytics').data('url'),
            type: 'GET'
        }).done(function (response) {
            dashboardFunc.graphBarProInterview.build(response.graphBarProInterview);
            dashboardFunc.graphBarAnnualInterview.build(response.graphBarAnnualInterview);
            dashboardFunc.graphPieAnnualInterview.build(response.graphPieAnnualInterview);
        });
    },
    graphBarProInterview: {
        build: function (dataGraph) {
            let context = $('#chart_pro_interview')[0].getContext('2d');
            new Chart(context, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Collaborateurs',
                        data: dataGraph.data,
                        backgroundColor: ["#b5b8cf", "#dedede", "#a3e1d4"],
                        borderColor: ["#b5b8cf", "#dedede", "#a3e1d4"]
                    }],
                    labels: ['Entretien à créer', 'Entretien à plannifier ', 'Entretien valider'],
                },
                options: dashboardFunc.graphOption(dataGraph.max)
            });
        }
    },
    graphBarAnnualInterview: {
        build: function (dataGraph) {
            let context = $('#chart_annual_interview')[0].getContext('2d');
            new Chart(context, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Evolution',
                        data: dataGraph.data,
                        backgroundColor: ["#b5b8cf", "#dedede", "#a3e1d4"],
                        borderColor: ["#b5b8cf", "#dedede", "#a3e1d4"]
                    }],
                    labels: ['Entretien à créer', 'Entretien à plannifier', 'Entretien valider'],
                },
                options: dashboardFunc.graphOption(dataGraph.max)
            });
        }
    },
    graphPieAnnualInterview: {
        build: function (dataGraph) {
            let context = $('#chart_pie_annual_interview')[0].getContext('2d');
            new Chart(context, {
                type: 'pie',
                data: {
                    labels: ["Pas la compétence", "Notions", "Autonome", "Confirmé", "Au-delà des attentes"],
                    datasets: [{
                        backgroundColor: [
                            "#b5b8cf",
                            "#dedede",
                            "#a3e1d4",
                            "#3498db",
                            "#34495e"
                        ],
                        data: [dataGraph[1], dataGraph[2], dataGraph[3], dataGraph[4], dataGraph[5]]
                    }]
                },
                options: {
                    responsive: true,
                    tooltips: {
                        callbacks: {
                            label: function (data, tooltip) {
                                let label = tooltip.labels[data.index];
                                let value = tooltip.datasets[data.datasetIndex].data[data.index];
                                if (parseInt(value) < 2) {
                                    return label + ': ' + value + ' collaborateur';
                                }
                                return label + ': ' + value + ' collaborateurs';
                            }
                        }
                    }
                }
            });
        }
    },
    graphOption: function (userCounter) {
        return {
            legend: {
                display: false
            },
            responsive: true,
            scales: {
                yAxes: [{
                    display: true,
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1,
                        max: userCounter
                    }
                }]
            },
            elements: {
                point:{
                    radius: 0
                }
            },
            tooltips: {
                callbacks: {
                    label: function (data, tooltip) {
                        let label = tooltip.labels[data.index];
                        let value = tooltip.datasets[data.datasetIndex].data[data.index];
                        if (data.datasetIndex === 0) {
                            if (parseInt(value) < 2) {
                                return label + ': ' + value + ' collaborateur';
                            }
                            return label + ': ' + value + ' collaborateurs';
                        }
                    }
                }
            }
        }
    },
    search: {
        timer: undefined,
        startTimer: function (el) {
            if (this.timer !== null) {
                clearTimeout(this.timer);
            }
            this.timer = setTimeout(function () {
                dashboardFunc.search.search(el);
            }, 500);
        },
        search: function (el) {
            let search = el.val().trim() === '' ? null : el.val().trim();
            dashboardFunc.loadAjax(1, search);
        }
    },
    changePagePagination: function (el) {
        if (el.hasClass('cursor-not-allowed') || el.hasClass('disabled')) {
            return;
        }

        let search = $('input.search-user').val().trim();
        search = search === '' ? null : search;
        dashboardFunc.loadAjax(el.data('page'), search);
    },
    loadAjax: function (page, search) {
        $.ajax({
            url: $('#url_users_data').data('url'),
            type: 'GET',
            contentType: 'json',
            data: {
                page: page,
                search: search
            }
        }).done(function (response) {
            $('#pagination_array_user').replaceWith(response.html);
            dashboardListener.eventPagination();
            dashboardListener.onClick();
        });
    },
    openPopup: function (el) {
        dashboardFunc.currentUserDeleted = el.data('user');
        $('#name_popup').html(
            $('.last_name_user_' + el.data('user')).text() + ' ' +
            $('.first_name_user_' + el.data('user')).text()
        );

        $('#modal_delete_user').modal('show');
    },
    deleteUser: function () {
        let url = $('#url_delete_user').data('url').replace('__id__', dashboardFunc.currentUserDeleted);
        window.location.href = url;
    },
    openModalRelaunch: function (el) {
        $('#interview_relaunch').val($(el).data('interview'));
        $('#modal_relaunch_user').modal('show');
    },
    relaunch: function () {
        if ($('.fa-spin').is(":visible")) {
            return;
        }
        $('.fa-spin').css('display', 'inherit');

        $.ajax({
            url: $('#url_relaunch').data('url'),
            type: 'GET',
            contentType: 'json',
            data: {
                interview: $('#interview_relaunch').val(),
                user: $('#user_relaunch').val()
            }
        }).done(function (response) {
            $('#modal_relaunch_user').modal('hide');
            $('.fa-spin').css('display', 'none');

            if (!response.hasOwnProperty('history')) {
                return;
            }

            if ($('div#no_historical').length) {
                $('div#no_historical').remove();
            }

            let history = $('<li></li>');
            history.addClass('margin-top-6');
            let txt = response.history.date + ' : Relance effectuée par ' + response.history.author + ' aux ';
            txt += (response.history.user_type === 'manager' ? 'managers' : 'collaborateurs') + ' concernant les entretiens ';
            txt += (response.history.interview_type === 'annual' ? 'annuels' : 'professionnels') + ' (' + response.relaunch_counter + ' ';
            txt += (response.relaunch_counter > 1 ? 'courriels envoyés)' : 'courriel envoyé)');

            history.text(txt);
            $('#histories').prepend(history);

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
            toastr.success('Votre relance a bien été effectuée', 'Succès');
        }).error(function () {
            $('.fa-spin').css('display', 'none');
        });
    }
};

let dashboardListener = {
    onLoad: function () {
        dashboardFunc.loadDataGraph();
        dashboardListener.onClick();
        dashboardListener.eventPagination();
    },
    onClick: function () {
        $('.btn-delete-user').unbind().click(function () {
           dashboardFunc.openPopup($(this));
        });
        $('#delete_user').unbind().click(function () {
            dashboardFunc.deleteUser();
        });
        $('.relaunch-users').unbind().click(function () {
            dashboardFunc.openModalRelaunch(this);
        });
        $('#relaunch_validated').unbind().click(function () {
           dashboardFunc.relaunch(); 
        });
    },
    eventPagination: function () {
        $('input.search-user').unbind().keyup(function () {
            dashboardFunc.search.startTimer($(this));
        });
        $('li.paginate_button').unbind().click(function () {
            dashboardFunc.changePagePagination($(this));
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        dashboardListener.onLoad();
    });
});