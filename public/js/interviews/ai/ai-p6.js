'use strict';
let aip6Func = {
    canvas: undefined,
    ctx: undefined,
    mousePressed: false,
    lastX: 0,
    lastY: 0,
    drawCanvas: function (x, y, isDown) {
        if (isDown) {
            aip6Func.ctx.beginPath();
            aip6Func.ctx.strokeStyle = 'black';
            aip6Func.ctx.lineWidth = 2;
            aip6Func.ctx.lineJoin = "round";
            aip6Func.ctx.moveTo(aip6Func.lastX, aip6Func.lastY);
            aip6Func.ctx.lineTo(x, y);
            aip6Func.ctx.closePath();
            aip6Func.ctx.stroke();
        }
        aip6Func.lastX = x;
        aip6Func.lastY = y;
    },
    eraseCanvas: function () {
        aip6Func.ctx.clearRect(0, 0, aip6Func.canvas.width(), aip6Func.canvas.height());
    },
    canvasIsBlank: function () {
        const pixelBuffer = new Uint32Array(
            aip6Func.ctx.getImageData(0, 0, aip6Func.canvas.width(), aip6Func.canvas.height()).data.buffer
        );
        return !pixelBuffer.some(color => color !== 0);
    },
    checkSignature: function () {
        if (aip6Func.canvasIsBlank()) {
            $('.error-field-txt').css('display', 'block');
            return;
        }
        $('.error-field-txt').css('display', 'none');
        aip6Func.openPopup();
    },
    openPopup: function () {
        $('#modal_validation_interview').modal('show');
    },
    validateAi: function () {
        let data = aip6Func.canvas[0].toDataURL('image/pnj', 1.0);
        let img = new Image();
        img.src = data;

        $('#interview_annual_p6_employeeSignature').val(data);

        $('form').submit();
    },
    fillCanvas: function () {
        let data = $('#interview_annual_p6_employeeSignature').val();
        if (!data) {
            return;
        }

        let img = new Image();
        img.onload = function () {
            aip6Func.ctx.drawImage(img, 0, 0);
        };
        img.src = data;
    }
};

let aip6Listener = {
    onLoad: function () {
        aip6Listener.canvas();
        aip6Listener.onClick();
    },
    canvas: function () {
        aip6Func.fillCanvas();
        aip6Func.canvas = $('canvas#canvas');
        aip6Func.ctx = aip6Func.canvas[0].getContext("2d");
        $('canvas#canvas').mousedown(function (e) {
            aip6Func.mousePressed = true;
            aip6Func.drawCanvas(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, false);
        });
        $('canvas#canvas').mousemove(function (e) {
            if (aip6Func.mousePressed) {
                aip6Func.drawCanvas(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, true);
            }
        });
        $('canvas#canvas').mouseup(function (e) {
            aip6Func.mousePressed = false;
        });
        $('canvas#canvas').mouseleave(function (e) {
            aip6Func.mousePressed = false;
        });
    },
    onClick: function () {
        $('#clear_canvas').click(function () {
            aip6Func.eraseCanvas();
        });
        $('#open_popup_ai').click(function () {
            aip6Func.checkSignature();
        });
        $('#validation_interview').click(function () {
            aip6Func.validateAi();
        });
    },
};

$(document).ready(function () {
    $(window).ready(function () {
        aip6Listener.onLoad();
    });
});