let keepAliveFunc = {
    showPopupInterval: 900000, // 15 minutes
    logoutWaitingInterval: 180000, // 3 minute
    timeOutInactivity: undefined,
    timeOutLogout: undefined,
    startKeepAlive: function () {
        if (keepAliveFunc.timeOutInactivity !== null) {
            clearTimeout(keepAliveFunc.timeOutInactivity);
        }

        keepAliveFunc.timeOutInactivity = setTimeout(function () {
            keepAliveFunc.openPopup();
        }, keepAliveFunc.showPopupInterval);
    },
    resetPopup: function() {
        if (keepAliveFunc.timeOutLogout !== null) {
            clearTimeout(keepAliveFunc.timeOutLogout);
        }

        keepAliveFunc.startKeepAlive();
    },
    openPopup: function () {
        $('#modal_keep_alive').modal('show');
        keepAliveFunc.timeOutLogout = setTimeout(function () {
            keepAliveFunc.logout();
        }, keepAliveFunc.logoutWaitingInterval);
    },
    keepAliveAllTime: function () {
        $.ajax({
            url: $('#weblayout').data('keepalive'),
            type: 'GET',
            success: function (response) {
                if (response === 'logged out') {
                    keepAliveFunc.logout();
                }
                setTimeout(function () {
                    keepAliveFunc.keepAliveAllTime();
                }, 30000) // request call every 30 seconds
            }
        });
    },
    logout: function () {
        window.location.replace($('#weblayout').data('logout'));
    }
};

let keepAliveListener = {
    onLoad: function () {
        keepAliveFunc.keepAliveAllTime();
        keepAliveListener.onClick();
        keepAliveListener.onMouse();
    },
    onClick: function () {
        $('#logout_keep_alive').unbind().click(function () {
            keepAliveFunc.logout();
        });
        $("#modal_keep_alive").on("hidden.bs.modal", function () {
            keepAliveFunc.resetPopup();
        });
    },
    onMouse: function () {
        $(document).unbind().mousemove(function () {
            keepAliveFunc.startKeepAlive();
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        keepAliveListener.onLoad();
    });
});