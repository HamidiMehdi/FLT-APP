'use strict';
let aiInProgressP6Func = {
    canvas: undefined,
    ctx: undefined,
    mousePressed: false,
    lastX: 0,
    lastY: 0,
    drawCanvas: function (x, y, isDown) {
        if (isDown) {
            aiInProgressP6Func.ctx.beginPath();
            aiInProgressP6Func.ctx.strokeStyle = 'black';
            aiInProgressP6Func.ctx.lineWidth = 2;
            aiInProgressP6Func.ctx.lineJoin = "round";
            aiInProgressP6Func.ctx.moveTo(aiInProgressP6Func.lastX, aiInProgressP6Func.lastY);
            aiInProgressP6Func.ctx.lineTo(x, y);
            aiInProgressP6Func.ctx.closePath();
            aiInProgressP6Func.ctx.stroke();
        }
        aiInProgressP6Func.lastX = x;
        aiInProgressP6Func.lastY = y;
    },
    eraseCanvas: function () {
        aiInProgressP6Func.ctx.clearRect(0, 0, aiInProgressP6Func.canvas.width(), aiInProgressP6Func.canvas.height());
    },
    canvasIsBlank: function () {
        const pixelBuffer = new Uint32Array(
            aiInProgressP6Func.ctx.getImageData(0, 0, aiInProgressP6Func.canvas.width(), aiInProgressP6Func.canvas.height()).data.buffer
        );
        return !pixelBuffer.some(color => color !== 0);
    },
    checkSignature: function () {
        if (aiInProgressP6Func.canvasIsBlank()) {
            $('.error-field-txt').css('display', 'block');
            return;
        }

        $('.error-field-txt').css('display', 'none');
        aiInProgressP6Func.openPopup();
    },
    openPopup: function () {
        $('#modal_validation_interview').modal('show');
    },
    validateAi: function () {
        let data = aiInProgressP6Func.canvas[0].toDataURL('image/pnj', 1.0);
        let img = new Image();
        img.src = data;

        $('#interview_annual_p6_managerSignature').val(data);

        $('form').submit();
    },
    fillCanvas: function () {
        let data = $('#interview_annual_p6_managerSignature').val();
        if (!data) {
            return;
        }

        let img = new Image();
        img.onload = function () {
            aiInProgressP6Func.ctx.drawImage(img, 0, 0);
        };
        img.src = data;
    },
    employeeRefuseSignature: function () {
        if ($('#interview_annual_p6_refuseSignature').is(':checked')) {
            $('#signature_employee').css('display', 'none');
            return;
        }

        $('#signature_employee').css('display', 'block');
    }
};

let aiInProgressP6Listener = {
    onLoad: function () {
        aiInProgressP6Listener.canvas();
        aiInProgressP6Listener.onClick();
        aiInProgressP6Listener.onChange();
    },
    canvas: function () {
        aiInProgressP6Func.fillCanvas();
        aiInProgressP6Func.canvas = $('canvas#canvas');
        aiInProgressP6Func.ctx = aiInProgressP6Func.canvas[0].getContext("2d");
        $('canvas#canvas').mousedown(function (e) {
            aiInProgressP6Func.mousePressed = true;
            aiInProgressP6Func.drawCanvas(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, false);
        });
        $('canvas#canvas').mousemove(function (e) {
            if (aiInProgressP6Func.mousePressed) {
                aiInProgressP6Func.drawCanvas(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, true);
            }
        });
        $('canvas#canvas').mouseup(function (e) {
            aiInProgressP6Func.mousePressed = false;
        });
        $('canvas#canvas').mouseleave(function (e) {
            aiInProgressP6Func.mousePressed = false;
        });
    },
    onClick: function () {
        $('#clear_canvas').click(function () {
            aiInProgressP6Func.eraseCanvas();
        });
        $('#open_modal_ai').click(function () {
            aiInProgressP6Func.checkSignature();
        });
        $('#validation_interview').click(function () {
            aiInProgressP6Func.validateAi();
        });
    },
    onChange: function () {
        $('#interview_annual_p6_refuseSignature').change(function () {
            aiInProgressP6Func.employeeRefuseSignature();
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        aiInProgressP6Listener.onLoad();
    });
});