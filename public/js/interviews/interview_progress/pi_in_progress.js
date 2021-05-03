'use strict';
let piInProgressP3Func = {
    canvas: undefined,
    ctx: undefined,
    mousePressed: false,
    lastX: 0,
    lastY: 0,
    drawCanvas: function (x, y, isDown) {
        if (isDown) {
            piInProgressP3Func.ctx.beginPath();
            piInProgressP3Func.ctx.strokeStyle = 'black';
            piInProgressP3Func.ctx.lineWidth = 2;
            piInProgressP3Func.ctx.lineJoin = "round";
            piInProgressP3Func.ctx.moveTo(piInProgressP3Func.lastX, piInProgressP3Func.lastY);
            piInProgressP3Func.ctx.lineTo(x, y);
            piInProgressP3Func.ctx.closePath();
            piInProgressP3Func.ctx.stroke();
        }
        piInProgressP3Func.lastX = x;
        piInProgressP3Func.lastY = y;
    },
    eraseCanvas: function () {
        piInProgressP3Func.ctx.clearRect(0, 0, piInProgressP3Func.canvas.width(), piInProgressP3Func.canvas.height());
    },
    eraseSecondCanvas: function() {
        secondSignatureFunc.ctx.clearRect(0, 0, secondSignatureFunc.canvas.width(), secondSignatureFunc.canvas.height());
    },
    canvasIsBlank: function () {
        const pixelBuffer = new Uint32Array(
            piInProgressP3Func.ctx.getImageData(0, 0, piInProgressP3Func.canvas.width(), piInProgressP3Func.canvas.height()).data.buffer
        );
        return !pixelBuffer.some(color => color !== 0);
    },
    checkSignature: function () {
        if (piInProgressP3Func.canvasIsBlank()) {
            $('#error_first_canvas').css('display', 'block');
            return;
        }
        $('#error_first_canvas').css('display', 'none');

        if ($('#interview_pro_p3_acceptSecondManager').is(':checked') &&  secondSignatureFunc.canvasIsBlank()) {
            $('#error_second_canvas').css('display', 'block');
            return;
        }
        $('#error_second_canvas').css('display', 'none');

        piInProgressP3Func.openPopup();
    },
    openPopup: function () {
        $('#popup_validate_pi').modal('show');
    },
    validatePi: function () {
        let data = piInProgressP3Func.canvas[0].toDataURL('image/pnj', 1.0);
        let secondData = secondSignatureFunc.canvas[0].toDataURL('image/pnj', 1.0);

        $('#interview_pro_p3_managerSignature').val(data);
        $('#interview_pro_p3_secondManagerSignature').val(secondData);

        $('form').submit();
    },
    fillCanvas: function () {
        let data = $('#interview_pro_p3_managerSignature').val();
        if (!data) {
            return;
        }

        let img = new Image();
        img.onload = function () {
            piInProgressP3Func.ctx.drawImage(img, 0, 0);
        };
        img.src = data;
    },
    fillSecondCanvas: function () {
        let data = $('#interview_pro_p3_secondManagerSignature').val();
        if (!data) {
            return;
        }

        let img = new Image();
        img.onload = function () {
            secondSignatureFunc.ctx.drawImage(img, 0, 0);
        };
        img.src = data;
    },
    handleShowSecondManager: function () {
        if ($('#interview_pro_p3_acceptSecondManager').is(':checked')) {
            $('#display_second_manager').css('display', 'block');
            return;
        }

        $('#display_second_manager').css('display', 'none');
    }
};

let piInProgressP3Listenersp3Func = {
    onLoad: function () {
        piInProgressP3Listenersp3Func.canvas();
        piInProgressP3Listenersp3Func.onClick();
        piInProgressP3Listenersp3Func.onChange();
    },
    canvas: function () {
        piInProgressP3Func.fillCanvas();
        piInProgressP3Func.canvas = $('canvas#canvas');
        piInProgressP3Func.ctx = piInProgressP3Func.canvas[0].getContext("2d");
        $('canvas#canvas').mousedown(function (e) {
            piInProgressP3Func.mousePressed = true;
            piInProgressP3Func.drawCanvas(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, false);
        });
        $('canvas#canvas').mousemove(function (e) {
            if (piInProgressP3Func.mousePressed) {
                piInProgressP3Func.drawCanvas(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, true);
            }
        });
        $('canvas#canvas').mouseup(function (e) {
            piInProgressP3Func.mousePressed = false;
        });
        $('canvas#canvas').mouseleave(function (e) {
            piInProgressP3Func.mousePressed = false;
        });
    },
    onClick: function () {
        $('#clear_canvas').click(function () {
            piInProgressP3Func.eraseCanvas();
        });
        $('#clear_second_canvas').click(function () {
            piInProgressP3Func.eraseSecondCanvas();
        });

        $('#open_popup_pi').click(function () {
            piInProgressP3Func.checkSignature();
        });
        $('#validate_pi').click(function () {
            piInProgressP3Func.validatePi();
        });
    },
    onChange: function () {
        $('#interview_pro_p3_acceptSecondManager').change(function () {
            piInProgressP3Func.handleShowSecondManager();
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        piInProgressP3Listenersp3Func.onLoad();
    });
});