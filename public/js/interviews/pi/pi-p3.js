'use strict';
let pip3Func = {
    canvas: undefined,
    ctx: undefined,
    mousePressed: false,
    lastX: 0,
    lastY: 0,
    drawCanvas: function (x, y, isDown) {
        if (isDown) {
            pip3Func.ctx.beginPath();
            pip3Func.ctx.strokeStyle = 'black';
            pip3Func.ctx.lineWidth = 2;
            pip3Func.ctx.lineJoin = "round";
            pip3Func.ctx.moveTo(pip3Func.lastX, pip3Func.lastY);
            pip3Func.ctx.lineTo(x, y);
            pip3Func.ctx.closePath();
            pip3Func.ctx.stroke();
        }
        pip3Func.lastX = x;
        pip3Func.lastY = y;
    },
    eraseCanvas: function () {
        pip3Func.ctx.clearRect(0, 0, pip3Func.canvas.width(), pip3Func.canvas.height());
    },
    canvasIsBlank: function () {
        const pixelBuffer = new Uint32Array(
            pip3Func.ctx.getImageData(0, 0, pip3Func.canvas.width(), pip3Func.canvas.height()).data.buffer
        );
        return !pixelBuffer.some(color => color !== 0);
    },
    checkSignature: function () {
        if (pip3Func.canvasIsBlank()) {
            $('.error-field-txt').css('display', 'block');
            return;
        }

        $('.error-field-txt').css('display', 'none');
        pip3Func.openPopup();
    },
    openPopup: function () {
        $('#modal_validation_interview').modal('show');
    },
    validatePi: function () {
        let data = pip3Func.canvas[0].toDataURL('image/pnj', 1.0);
        let img = new Image();
        img.src = data;

        $('#interview_pro_p3_employeeSignature').val(data);

        $('form').submit();
    },
    fillCanvas: function () {
        let data = $('#interview_pro_p3_employeeSignature').val();
        if (!data) {
            return;
        }

        let img = new Image();
        img.onload = function () {
            pip3Func.ctx.drawImage(img, 0, 0);
        };
        img.src = data;
    }
};

let pip3Listener = {
    onLoad: function () {
        pip3Listener.canvas();
        pip3Listener.onClick();
    },
    canvas: function () {
        pip3Func.fillCanvas();
        pip3Func.canvas = $('canvas#canvas');
        pip3Func.ctx = pip3Func.canvas[0].getContext("2d");
        $('canvas#canvas').mousedown(function (e) {
            pip3Func.mousePressed = true;
            pip3Func.drawCanvas(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, false);
        });
        $('canvas#canvas').mousemove(function (e) {
            if (pip3Func.mousePressed) {
                pip3Func.drawCanvas(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, true);
            }
        });
        $('canvas#canvas').mouseup(function (e) {
            pip3Func.mousePressed = false;
        });
        $('canvas#canvas').mouseleave(function (e) {
            pip3Func.mousePressed = false;
        });
    },
    onClick: function () {
        $('#clear_canvas').click(function () {
            pip3Func.eraseCanvas();
        });
        $('#open_popup_pi').click(function () {
            pip3Func.checkSignature();
        });
        $('#validation_interview').click(function () {
            pip3Func.validatePi();
        });
    },
};

$(document).ready(function () {
    $(window).ready(function () {
        pip3Listener.onLoad();
    });
});