/**
 * jqPlot
 * Pure JavaScript plotting plugin using jQuery
 *
 * Version: 1.0.9
 * Revision: dff2f04
 *
 * Copyright (c) 2009-2016 Chris Leonello
 * jqPlot is currently available for use in all personal or commercial projects
 * under both the MIT (http://www.opensource.org/licenses/mit-license.php) and GPL
 * version 2.0 (http://www.gnu.org/licenses/gpl-2.0.html) licenses. This means that you can
 * choose the license that best suits your project and use it accordingly.
 *
 * Although not required, the author would appreciate an email letting him
 * know of any substantial use of jqPlot.  You can reach the author at:
 * chris at jqplot dot com or see http://www.jqplot.com/info.php .
 *
 * If you are feeling kind and generous, consider supporting the project by
 * making a donation at: http://www.jqplot.com/donate.php .
 *
 * sprintf functions contained in jqplot.sprintf.js by Ash Searle:
 *
 *     version 2007.04.27
 *     author Ash Searle
 *     http://hexmen.com/blog/2007/03/printf-sprintf/
 *     http://hexmen.com/js/sprintf.js
 *     The author (Ash Searle) has placed this code in the public domain:
 *     "This code is unrestricted: you are free to use it however you like."
 *
 */
(function ($) {
    /**
     * Class: $.jqplot.CanvasAxisLabelRenderer
     * Renderer to draw axis labels with a canvas element to support advanced
     * featrues such as rotated text.  This renderer uses a separate rendering engine
     * to draw the text on the canvas.  Two modes of rendering the text are available.
     * If the browser has native font support for canvas fonts (currently Mozila 3.5
     * and Safari 4), you can enable text rendering with the canvas fillText method.
     * You do so by setting the "enableFontSupport" option to true.
     *
     * Browsers lacking native font support will have the text drawn on the canvas
     * using the Hershey font metrics.  Even if the "enableFontSupport" option is true
     * non-supporting browsers will still render with the Hershey font.
     *
     */
    $.jqplot.CanvasAxisLabelRenderer = function (options) {
        this.angle = 0;
        this.axis;
        this.show = true;
        this.showLabel = true;
        this.label = "";
        this.fontFamily = '"Trebuchet MS", Arial, Helvetica, sans-serif';
        this.fontSize = "11pt";
        this.fontWeight = "normal";
        this.fontStretch = 1.0;
        this.textColor = "#666666";
        this.enableFontSupport = true;
        this.pt2px = null;

        this._elem;
        this._ctx;
        this._plotWidth;
        this._plotHeight;
        this._plotDimensions = { height: null, width: null };

        $.extend(true, this, options);

        if (
            options.angle == null &&
            this.axis != "xaxis" &&
            this.axis != "x2axis"
        ) {
            this.angle = -90;
        }

        var ropts = {
            fontSize: this.fontSize,
            fontWeight: this.fontWeight,
            fontStretch: this.fontStretch,
            fillStyle: this.textColor,
            angle: this.getAngleRad(),
            fontFamily: this.fontFamily,
        };
        if (this.pt2px) {
            ropts.pt2px = this.pt2px;
        }

        if (this.enableFontSupport) {
            if ($.jqplot.support_canvas_text()) {
                this._textRenderer = new $.jqplot.CanvasFontRenderer(ropts);
            } else {
                this._textRenderer = new $.jqplot.CanvasTextRenderer(ropts);
            }
        } else {
            this._textRenderer = new $.jqplot.CanvasTextRenderer(ropts);
        }
    };

    $.jqplot.CanvasAxisLabelRenderer.prototype.init = function (options) {
        $.extend(true, this, options);
        this._textRenderer.init({
            fontSize: this.fontSize,
            fontWeight: this.fontWeight,
            fontStretch: this.fontStretch,
            fillStyle: this.textColor,
            angle: this.getAngleRad(),
            fontFamily: this.fontFamily,
        });
    };
    $.jqplot.CanvasAxisLabelRenderer.prototype.getWidth = function (ctx) {
        if (this._elem) {
            return this._elem.outerWidth(true);
        } else {
            var tr = this._textRenderer;
            var l = tr.getWidth(ctx);
            var h = tr.getHeight(ctx);
            var w =
                Math.abs(Math.sin(tr.angle) * h) +
                Math.abs(Math.cos(tr.angle) * l);
            return w;
        }
    };
    $.jqplot.CanvasAxisLabelRenderer.prototype.getHeight = function (ctx) {
        if (this._elem) {
            return this._elem.outerHeight(true);
        } else {
            var tr = this._textRenderer;
            var l = tr.getWidth(ctx);
            var h = tr.getHeight(ctx);
            var w =
                Math.abs(Math.cos(tr.angle) * h) +
                Math.abs(Math.sin(tr.angle) * l);
            return w;
        }
    };

    $.jqplot.CanvasAxisLabelRenderer.prototype.getAngleRad = function () {
        var a = (this.angle * Math.PI) / 180;
        return a;
    };

    $.jqplot.CanvasAxisLabelRenderer.prototype.draw = function (ctx, plot) {
        if (this._elem) {
            if (
                $.jqplot.use_excanvas &&
                window.G_vmlCanvasManager.uninitElement !== undefined
            ) {
                window.G_vmlCanvasManager.uninitElement(this._elem.get(0));
            }

            this._elem.emptyForce();
            this._elem = null;
        }
        var elem = plot.canvasManager.getCanvas();

        this._textRenderer.setText(this.label, ctx);
        var w = this.getWidth(ctx);
        var h = this.getHeight(ctx);
        elem.width = w;
        elem.height = h;
        elem.style.width = w;
        elem.style.height = h;

        elem = plot.canvasManager.initCanvas(elem);

        this._elem = $(elem);
        this._elem.css({ position: "absolute" });
        this._elem.addClass("jqplot-" + this.axis + "-label");

        elem = null;
        return this._elem;
    };

    $.jqplot.CanvasAxisLabelRenderer.prototype.pack = function () {
        this._textRenderer.draw(this._elem.get(0).getContext("2d"), this.label);
    };
})(jQuery);
