'use strict';
let workingDashboardFunc = {
    currentMonth: undefined,
    currentDay: undefined,
    descDay: {
        timer: undefined,
        startTimer: function (date) {
            if (this.timer !== null) {
                clearTimeout(this.timer);
            }
            this.timer = setTimeout(function () {
                workingDashboardFunc.descDay.day(date);
            }, 400);
        },
        day: function (date) {
            let spinner = $('#spinner_information');
            spinner.removeClass('hidden');

            $.ajax({
                url: $('#url_calendar').data('urlDay'),
                type: 'GET',
                contentType: 'json',
                data: {
                    date: date
                }
            }).done(function (response) {
                $('#description_day').replaceWith(response.html);
                spinner.addClass('hidden');
            }).error(function () {
                spinner.addClass('hidden');
            });
        }
    },
    buildCalendar: function () {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next',
                center: 'title',
                right: ''
            },
            eventLimit: true,
            eventLimitText: 'plus',
            firstDay: 1,
            weekends: false,
            displayEventTime: false,
            dayNames: workingDashboardFunc.getDayNames(),
            dayNamesShort: workingDashboardFunc.getDayNamesShort(),
            monthNames: workingDashboardFunc.getMonthNames(),
            disableDragging: true,
            viewRender: function (view, element) {
                workingDashboardFunc.loadDataMonthCalendar.startTimer();
            },
            dayClick: function(date, jsEvent, view) {
                let dayOfWeekNotAllowed = [5, 6];
                if (dayOfWeekNotAllowed.includes(date.weekday()) ||
                    date.format('MM') !== workingDashboardFunc.currentMonth ||
                    date.format('DD/MM/YYYY') === workingDashboardFunc.currentDay
                ) {
                    return ;
                }

                workingDashboardFunc.currentDay = date.format('DD/MM/YYYY');
                workingDashboardFunc.descDay.startTimer(date.format());
            }
        });
    },
    loadDataMonthCalendar: {
        timer: undefined,
        startTimer: function () {
            if (this.timer !== null) {
                clearTimeout(this.timer);
            }
            this.timer = setTimeout(function () {
                workingDashboardFunc.loadDataMonthCalendar.loadData();
            }, 400);
        },
        loadData: function () {
            let calendar = $('#calendar');
            let dateCalendar = calendar.fullCalendar('getDate');
            workingDashboardFunc.currentMonth = dateCalendar.format('MM');

            let spinner = $('#spinner_agenda');
            spinner.removeClass('hidden');

            calendar.fullCalendar('removeEventSources');
            $.ajax({
                url: $('#url_calendar').data('urlCalendar'),
                type: 'GET',
                contentType: 'json',
                data: {
                    month: dateCalendar.format('MM'),
                    year: dateCalendar.format('YYYY')
                }
            }).done(function (response) {
                if (!response.hasOwnProperty('workings')) {
                    spinner.addClass('hidden');
                    return;
                }

                for (let i = 0; i < response.workings.length; i++) {
                    let working = response.workings[i];
                    if (!working.hasOwnProperty('startAt') || !working.hasOwnProperty('endAt')) {
                        spinner.addClass('hidden');
                        return;
                    }

                    let startAt = moment(working.startAt);
                    let endAt = moment(working.endAt);
                    let access = true;

                    do {
                        if (startAt.valueOf() > endAt.valueOf()) {
                            access = false;
                            continue;
                        }

                        // skip week days
                        let dayOfWeekNotAllowed = [0, 6];
                        if (dayOfWeekNotAllowed.includes(startAt.weekday())) {
                            startAt.add(1, 'd');
                            continue;
                        }

                        $('#calendar').fullCalendar('renderEvent', {
                            title: working.fullname,
                            start: new Date(startAt.format('YYYY-MM-DD'))
                        });

                        startAt.add(1, 'd');
                    } while (access);
                }

                spinner.addClass('hidden');
            }).error(function () {
                spinner.addClass('hidden');
            });
        }
    },
    getMonthNames: function () {
        return [
          'Janvier',
          'Février',
          'Mars',
          'Avril',
          'Mai',
          'Juin',
          'Juillet',
          'Août',
          'Septembre',
          'Octobre',
          'Novembre',
          'Décembre'
        ];
    },
    getDayNamesShort: function () {
        return [
            'Dim',
            'Lun',
            'Mar',
            'Mer',
            'Jeu',
            'Ven',
            'Sam'
        ];
    },
    getDayNames: function () {
        return [
            "Dimanche",
            "Lundi",
            "Mardi",
            "Mercredi",
            "Jeudi",
            "Vendredi",
            "Samedi"
        ];
    },
    chartFilterHandler: {
        timer: undefined,
        startTimer: function () {
            if (this.timer !== null) {
                clearTimeout(this.timer);
            }
            this.timer = setTimeout(function () {
                workingDashboardFunc.chartFilterHandler.filter();
            }, 400);
        },
        filter: function () {
            workingDashboardFunc.loadDataGraph();
        }
    },
    loadDataGraph: function () {
        let user = $('#working_filter_employees').val();
        let month = $('#working_filter_months').val();
        let location = $('#working_filter_locations').val();

        $.ajax({
            url: $('#url_calendar').data('urlGraph'),
            type: 'GET',
            data: {
                user: user,
                month: month,
                location: location
            }
        }).done(function (response) {
            if (!response.hasOwnProperty('data') || !response.hasOwnProperty('axisX')) {
                return;
            }

            workingDashboardFunc.graphBarWorking.build(
                response.data,
                response.axisX
            );
        });
    },
    graphBarWorking: {
        currentChart: undefined,
        build: function (data, axis) {

            if (this.currentChart) {
                this.currentChart.destroy();
            }

            let datasets = [];
            let index = 0;
            for (let [key, value] of Object.entries(data)) {
                datasets.push({
                    label: key,
                    data: Object.values(value),
                    backgroundColor: workingDashboardFunc.graphBarWorking.colorBar(index),
                    borderColor: workingDashboardFunc.graphBarWorking.colorBar(index)
                });
                index++;
            }

            console.log(datasets);

            let context = $('#working_graph')[0].getContext('2d');
            this.currentChart = new Chart(context, {
                type: 'bar',
                data: {
                    dataset: [
                        {
                            label: 'retette',
                            data: [1, 2, 5,3,1],
                            backgroundColor: workingDashboardFunc.graphBarWorking.colorBar(index),
                            borderColor: workingDashboardFunc.graphBarWorking.colorBar(index)
                        },
                        {
                            label: 'ffff',
                            data: [1, 2, 15,3,1],
                            backgroundColor: workingDashboardFunc.graphBarWorking.colorBar(index),
                            borderColor: workingDashboardFunc.graphBarWorking.colorBar(index)
                        }
                    ],
                    labels: axis
                },
                options: {
                    responsive: true,
                    scales: {yAxes: [{display: true, ticks: {beginAtZero: true, stepSize: 1}}]},
                    elements: {point:{radius: 0}},
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
            });
        },
        colorBar: function (index) {
            let colors = [
                "#b5b8cf",
                "#dedede",
                "#a3e1d4",
                "#3498db",
                "#34495e"
            ];

            return colors[index];
        }
    },
};

let workingDashboardListener = {
    onLoad: function () {
        $('.chosen-select').chosen({width: "100%"});
        workingDashboardListener.chartFilterEvent();
        workingDashboardFunc.loadDataGraph();

        workingDashboardFunc.buildCalendar();
    },
    chartFilterEvent: function() {
        $('#working_filter_employees').unbind().change(function () {
            workingDashboardFunc.chartFilterHandler.startTimer();
        });
        $('#working_filter_months').unbind().change(function () {
            workingDashboardFunc.chartFilterHandler.startTimer();
        });
        $('#working_filter_locations').unbind().change(function () {
            workingDashboardFunc.chartFilterHandler.startTimer();
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        workingDashboardListener.onLoad();
    });
});