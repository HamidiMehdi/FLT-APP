'use strict';
var secondSignatureFunc = {
    canvas: undefined,
    ctx: undefined,
    mousePressed: false,
    lastX: 0,
    lastY: 0,
    drawCanvas: function (x, y, isDown) {
        if (isDown) {
            secondSignatureFunc.ctx.beginPath();
            secondSignatureFunc.ctx.strokeStyle = 'black';
            secondSignatureFunc.ctx.lineWidth = 2;
            secondSignatureFunc.ctx.lineJoin = "round";
            secondSignatureFunc.ctx.moveTo(secondSignatureFunc.lastX, secondSignatureFunc.lastY);
            secondSignatureFunc.ctx.lineTo(x, y);
            secondSignatureFunc.ctx.closePath();
            secondSignatureFunc.ctx.stroke();
        }
        secondSignatureFunc.lastX = x;
        secondSignatureFunc.lastY = y;
    },
    canvasIsBlank: function () {
        const pixelBuffer = new Uint32Array(
            secondSignatureFunc.ctx.getImageData(0, 0, secondSignatureFunc.canvas.width(), secondSignatureFunc.canvas.height()).data.buffer
        );
        return !pixelBuffer.some(color => color !== 0);
    },
};

let secondSignatureListener = {
    onLoad: function () {
        secondSignatureListener.canvas();
    },
    canvas: function () {
        secondSignatureFunc.canvas = $('canvas#second_canvas');
        secondSignatureFunc.ctx = secondSignatureFunc.canvas[0].getContext("2d");
        $('canvas#second_canvas').mousedown(function (e) {
            secondSignatureFunc.mousePressed = true;
            secondSignatureFunc.drawCanvas(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, false);
        });
        $('canvas#second_canvas').mousemove(function (e) {
            if (secondSignatureFunc.mousePressed) {
                secondSignatureFunc.drawCanvas(e.pageX - $(this).offset().left, e.pageY - $(this).offset().top, true);
            }
        });
        $('canvas#second_canvas').mouseup(function (e) {
            secondSignatureFunc.mousePressed = false;
        });
        $('canvas#second_canvas').mouseleave(function (e) {
            secondSignatureFunc.mousePressed = false;
        });
    }
};

$(document).ready(function () {
    $(window).ready(function () {
        secondSignatureListener.onLoad();
    });
});