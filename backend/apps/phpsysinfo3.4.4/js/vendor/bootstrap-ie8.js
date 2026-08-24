if (!window.Node) {
    var Node = {
        ELEMENT_NODE: 1,
        ATTRIBUTE_NODE: 2,
        TEXT_NODE: 3,
        CDATA_SECTION_NODE: 4,
        ENTITY_REFERENCE_NODE: 5,
        ENTITY_NODE: 6,
        PROCESSING_INSTRUCTION_NODE: 7,
        COMMENT_NODE: 8,
        DOCUMENT_NODE: 9,
        DOCUMENT_TYPE_NODE: 10,
        DOCUMENT_FRAGMENT_NODE: 11,
        NOTATION_NODE: 12,
    };
}

(function () {
    if (!Object.keys) {
        Object.keys = function (obj) {
            if (obj !== Object(obj)) {
                throw new TypeError("Object.keys called on a non-object");
            }

            var keys = [];

            for (var i in obj) {
                if (Object.prototype.hasOwnProperty.call(obj, i)) {
                    keys.push(i);
                }
            }

            return keys;
        };
    }
})();

(function () {
    if (!Object.create) {
        Object.create = function (proto, props) {
            if (typeof props !== "undefined") {
                throw "The multiple-argument version of Object.create is not provided by this browser and cannot be shimmed.";
            }
            function ctor() {}
            ctor.prototype = proto;

            return new ctor();
        };
    }
})();

(function () {
    if (!Array.prototype.forEach) {
        Array.prototype.forEach = function (fn, scope) {
            for (var i = 0, len = this.length; i < len; ++i) {
                fn.call(scope, this[i], i, this);
            }
        };
    }
})();
(function () {
    if (
        !Object.defineProperty ||
        !(function () {
            try {
                Object.defineProperty({}, "x", {});
                return true;
            } catch (e) {
                return false;
            }
        })()
    ) {
        var orig = Object.defineProperty;
        Object.defineProperty = function (o, prop, desc) {
            if (orig) {
                try {
                    return orig(o, prop, desc);
                } catch (e) {}
            }

            if (o !== Object(o)) {
                throw TypeError("Object.defineProperty called on non-object");
            }
            if (Object.prototype.__defineGetter__ && "get" in desc) {
                Object.prototype.__defineGetter__.call(o, prop, desc.get);
            }
            if (Object.prototype.__defineSetter__ && "set" in desc) {
                Object.prototype.__defineSetter__.call(o, prop, desc.set);
            }
            if ("value" in desc) {
                o[prop] = desc.value;
            }

            return o;
        };
    }
})();

(function () {
    if (!Function.prototype.bind) {
        Function.prototype.bind = function (oThis) {
            if (typeof this !== "function") {
                throw new TypeError(
                    "Function.prototype.bind - what is trying to be bound is not callable",
                );
            }

            var aArgs = Array.prototype.slice.call(arguments, 1),
                fToBind = this,
                fNOP = function () {},
                fBound = function () {
                    return fToBind.apply(
                        this instanceof fNOP && oThis ? this : oThis,
                        aArgs.concat(Array.prototype.slice.call(arguments)),
                    );
                };

            fNOP.prototype = this.prototype;
            fBound.prototype = new fNOP();

            return fBound;
        };
    }
})();

(function () {
    if (!Array.prototype.indexOf) {
        Array.prototype.indexOf = function (elt) {
            var len = this.length >>> 0;

            var from = Number(arguments[1]) || 0;
            from = from < 0 ? Math.ceil(from) : Math.floor(from);
            if (from < 0) {
                from += len;
            }
            for (; from < len; from++) {
                if (from in this && this[from] === elt) {
                    return from;
                }
            }

            return -1;
        };
    }
})();

(function () {
    var _slice = Array.prototype.slice;
    Array.prototype.slice = function () {
        if (this instanceof Array) {
            return _slice.apply(this, arguments);
        } else {
            var result = [];
            var start = arguments.length >= 1 ? arguments[0] : 0;
            var end = arguments.length >= 2 ? arguments[1] : this.length;
            for (var i = start; i < end; i++) {
                result.push(this[i]);
            }
            return result;
        }
    };
})();
(function () {
    Object.defineProperty(Element.prototype, "classList", {
        get: function () {
            var element = this,
                domTokenList = (element.getAttribute("class") || "")
                    .replace(/^\s+|\s$/g, "")
                    .split(/\s+/g);
            if (domTokenList[0] === "") domTokenList.splice(0, 1);
            function setClass() {
                if (domTokenList.length > 0)
                    element.setAttribute("class", domTokenList.join(" "));
                else element.removeAttribute("class");
            }
            domTokenList.toggle = function (className, force) {
                if (force !== undefined) {
                    if (force) domTokenList.add(className);
                    else domTokenList.remove(className);
                } else {
                    if (domTokenList.indexOf(className) !== -1)
                        domTokenList.splice(domTokenList.indexOf(className), 1);
                    else domTokenList.push(className);
                }
                setClass();
            };
            domTokenList.add = function () {
                var args = [].slice.call(arguments);
                for (var i = 0, l = args.length; i < l; i++) {
                    if (domTokenList.indexOf(args[i]) === -1)
                        domTokenList.push(args[i]);
                }
                setClass();
            };
            domTokenList.remove = function () {
                var args = [].slice.call(arguments);
                for (var i = 0, l = args.length; i < l; i++) {
                    if (domTokenList.indexOf(args[i]) !== -1)
                        domTokenList.splice(domTokenList.indexOf(args[i]), 1);
                }
                setClass();
            };
            domTokenList.item = function (i) {
                return domTokenList[i];
            };
            domTokenList.contains = function (className) {
                return domTokenList.indexOf(className) !== -1;
            };
            domTokenList.replace = function (oldClass, newClass) {
                if (domTokenList.indexOf(oldClass) !== -1)
                    domTokenList.splice(
                        domTokenList.indexOf(oldClass),
                        1,
                        newClass,
                    );
                setClass();
            };
            domTokenList.value = element.getAttribute("class") || "";
            return domTokenList;
        },
    });
})();

/**
 * Modified code based on remPolyfill.js (c) Nicolas Bouvrette https://github.com/nbouvrette/remPolyfill
 *
 * Customizations:
 *
 * 1) Added new method `addCallBackWhenReady` to perform callbacks once the polyfill has been applied (especially useful for
 *    onload scrolling events.
 * 2) Added REM support.
 *
 **/

/**
 * For browsers that do not support REM units, fallback to pixels.
 */
window.remPolyfill = {
    /** @property Number|null - The body's font size.
     *  @private */
    bodyFontSize: null,

    /**
     * Get the body font size.
     *
     * @returns {Number} - The body font size in pixel (number only).
     */
    getBodyFontSize: function () {
        if (!this.bodyFontSize) {
            if (!document.body) {
                var bodyElement = document.createElement("body");
                document.documentElement.appendChild(bodyElement);
                this.bodyFontSize = parseFloat(
                    this.getStyle(document.body, "fontSize"),
                );
                document.documentElement.removeChild(bodyElement);
                bodyElement = null;
            } else {
                this.bodyFontSize = parseFloat(
                    this.getStyle(document.body, "fontSize"),
                );
            }
        }
        return this.bodyFontSize;
    },

    /**
     * Get the style of an element for a given property.
     *
     * @private
     *
     * @param {HTMLElement} element - The HTML element.
     * @param {string} property     - The property of the style to get.
     */
    getStyle: function (element, property) {
        if (typeof window.getComputedStyle !== "undefined") {
            return window
                .getComputedStyle(element, null)
                .getPropertyValue(property);
        } else {
            return element.currentStyle[property];
        }
    },

    /**
     * Implement this script on a given element.
     *
     * @private
     *
     * @param {string} cssText              - The CSS text of the link element.
     */
    replaceCSS: function (cssText) {
        if (cssText) {
            return cssText
                .replace(
                    /([\d]+\.[\d]+|\.[\d]+|[\d]+)rem/g,
                    function (fullMatch, groupMatch) {
                        return (
                            Math.round(
                                parseFloat(
                                    groupMatch * remPolyfill.getBodyFontSize(),
                                ),
                            ) + "px"
                        );
                    },
                )
                .replace(
                    /calc\s*\(\s*\(\s*([\d]+)\s*px\s*([\+-])\s*([\d]+)\s*px\s*\)\s*\*\s*(-?[\d]+)\s*\)/g,
                    function (
                        fullMatch,
                        MatchArg1,
                        MatchSign,
                        MatchArg2,
                        MatchArg3,
                    ) {
                        return (
                            (parseInt(MatchArg1) +
                                (MatchSign == "-" ? -1 : 1) *
                                    parseInt(MatchArg2)) *
                                parseInt(MatchArg3) +
                            "px"
                        );
                    },
                )
                .replace(
                    /calc\s*\(\s*([\d]+)\s*px\s*([\+-])\s*([\d]+)\s*px\s*\)/g,
                    function (fullMatch, MatchArg1, MatchSign, MatchArg2) {
                        return (
                            parseInt(MatchArg1) +
                            (MatchSign == "-" ? -1 : 1) * parseInt(MatchArg2) +
                            "px"
                        );
                    },
                )
                .replace(/::/g, ":")
                .replace(/:disabled/g, "._disabled")
                .replace(/:invalid/g, "._invalid")
                .replace(/:valid/g, "._valid")
                .replace(
                    /background-color\s*:\s*rgba\s*\(\s*([\d]+)\s*,\s*([\d]+)\s*,\s*([\d]+)\s*,\s*([\d\.]+)\s*\)/g,
                    function (fullMatch, MatchR, MatchG, MatchB, MatchA) {
                        var ARGBhex = (
                            4294967296 +
                            16777216 * Math.round(parseFloat(MatchA) * 255) +
                            65536 * parseInt(MatchR) +
                            256 * parseInt(MatchG) +
                            parseInt(MatchB)
                        )
                            .toString(16)
                            .substr(1);
                        return (
                            "filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#" +
                            ARGBhex +
                            ", endColorstr=#" +
                            ARGBhex +
                            ")"
                        );
                    },
                )
                .replace(
                    /rgba\s*\(\s*([\d]+)\s*,\s*([\d]+)\s*,\s*([\d]+)\s*,\s*([\d\.]+)\s*\)/g,
                    function (fullMatch, MatchR, MatchG, MatchB, MatchA) {
                        var MR = parseInt(MatchR),
                            MG = parseInt(MatchG),
                            MB = parseInt(MatchB),
                            MA = parseFloat(MatchA);
                        if (MR == 255 && MG == 255 && MB == 255) {
                            //dark background
                            return (
                                "rgb(" +
                                Math.round(MA * 255) +
                                ", " +
                                Math.round(MA * 255) +
                                ", " +
                                Math.round(MA * 255) +
                                ")"
                            );
                        } else {
                            //else
                            return (
                                "rgb(" +
                                Math.round((1 - MA) * 255 + MA * MR) +
                                ", " +
                                Math.round((1 - MA) * 255 + MA * MG) +
                                ", " +
                                Math.round((1 - MA) * 255 + MA * MB) +
                                ")"
                            );
                        }
                    },
                )
                .replace(
                    /opacity\s*:\s*([\d]+\.[\d]+|\.[\d]+|[\d]+)/g,
                    function (fullMatch, groupMatch) {
                        return (
                            "filter:alpha(opacity=" +
                            Math.round(parseFloat(groupMatch * 100)) +
                            ")"
                        );
                    },
                );
        }
    },

    /**
     * Implement this script on a given element.
     *
     * @param {HTMLLinkElement} linkElement - The link element to polyfill.
     */
    implement: function (linkElement) {
        if (!linkElement.href) {
            return;
        }

        var request = null;

        if (window.XMLHttpRequest) {
            request = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            try {
                request = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (exception) {
                try {
                    request = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (exception) {
                    request = null;
                }
            }
        }

        if (!request) {
            return;
        }

        request.open("GET", linkElement.href, true);
        request.onreadystatechange = function () {
            if (request.readyState === 4) {
                linkElement.styleSheet.cssText = remPolyfill.replaceCSS(
                    request.responseText,
                );
            }
        };

        request.send(null);
    },
};

var linkElements = document.querySelectorAll("link[rel=stylesheet]");
for (var linkElementId in linkElements) {
    if (Object.prototype.hasOwnProperty.call(linkElements, linkElementId)) {
        remPolyfill.implement(linkElements[linkElementId]);
    }
}

(function (w) {
    "use strict";
    w.matchMedia =
        w.matchMedia ||
        (function (doc, undefined) {
            var bool,
                docElem = doc.documentElement,
                refNode = docElem.firstElementChild || docElem.firstChild,
                fakeBody = doc.createElement("body"),
                div = doc.createElement("div");
            div.id = "mq-test-1";
            div.style.cssText = "position:absolute;top:-100em";
            fakeBody.style.background = "none";
            fakeBody.appendChild(div);
            return function (q) {
                div.innerHTML =
                    '&shy;<style media="' +
                    q +
                    '"> #mq-test-1 { width: 42px; }</style>';
                docElem.insertBefore(fakeBody, refNode);
                bool = div.offsetWidth === 42;
                docElem.removeChild(fakeBody);
                return {
                    matches: bool,
                    media: q,
                };
            };
        })(w.document);
})(this);

(function (w) {
    "use strict";
    var respond = {};
    w.respond = respond;
    respond.update = function () {};
    var requestQueue = [],
        xmlHttp = (function () {
            var xmlhttpmethod = false;
            try {
                xmlhttpmethod = new w.XMLHttpRequest();
            } catch (e) {
                xmlhttpmethod = new w.ActiveXObject("Microsoft.XMLHTTP");
            }
            return function () {
                return xmlhttpmethod;
            };
        })(),
        ajax = function (url, callback) {
            var req = xmlHttp();
            if (!req) {
                return;
            }
            try {
                req.open("GET", url, true);
                req.onreadystatechange = function () {
                    if (
                        req.readyState !== 4 ||
                        (req.status !== 200 && req.status !== 304)
                    ) {
                        return;
                    }
                    callback(remPolyfill.replaceCSS(req.responseText));
                };
                if (req.readyState === 4) {
                    return;
                }
                req.send(null);
            } catch (e) {}
        },
        isUnsupportedMediaQuery = function (query) {
            return query
                .replace(respond.regex.minmaxwh, "")
                .match(respond.regex.other);
        };
    respond.ajax = ajax;
    respond.queue = requestQueue;
    respond.unsupportedmq = isUnsupportedMediaQuery;
    respond.regex = {
        media: /@media[^\{]+\{([^\{\}]*\{[^\}\{]*\})+/gi,
        keyframes:
            /@(?:\-(?:o|moz|webkit)\-)?keyframes[^\{]+\{(?:[^\{\}]*\{[^\}\{]*\})+[^\}]*\}/gi,
        comments: /\/\*[^*]*\*+([^/][^*]*\*+)*\//gi,
        urls: /(url\()['"]?([^\/\)'"][^:\)'"]+)['"]?(\))/g,
        findStyles: /@media *([^\{]+)\{([\S\s]+?)$/,
        only: /(only\s+)?([a-zA-Z]+)\s?/,
        minw: /\(\s*min\-width\s*:\s*(\s*[0-9\.]+)(px|em)\s*\)/,
        maxw: /\(\s*max\-width\s*:\s*(\s*[0-9\.]+)(px|em)\s*\)/,
        minmaxwh:
            /\(\s*m(in|ax)\-(height|width)\s*:\s*(\s*[0-9\.]+)(px|em)\s*\)/gi,
        other: /\([^\)]*\)/g,
    };
    respond.mediaQueriesSupported =
        w.matchMedia &&
        w.matchMedia("only all") !== null &&
        w.matchMedia("only all").matches;
    if (respond.mediaQueriesSupported) {
        return;
    }
    respond.callbackQueue = [];
    respond.addCallBackWhenReady = function (callback) {
        respond.callbackQueue.push(callback);
    };
    respond.callback = function () {
        if (respond.callbackQueue.length) {
            for (var callback in respond.callbackQueue) {
                respond.callbackQueue[callback]();
            }
        }
    };
    var doc = w.document,
        docElem = doc.documentElement,
        mediastyles = [],
        rules = [],
        appendedEls = [],
        parsedSheets = {},
        resizeThrottle = 30,
        head = doc.getElementsByTagName("head")[0] || docElem,
        base = doc.getElementsByTagName("base")[0],
        links = head.getElementsByTagName("link"),
        lastCall,
        resizeDefer,
        eminpx,
        getEmValue = function () {
            var ret,
                div = doc.createElement("div"),
                body = doc.body,
                originalHTMLFontSize = docElem.style.fontSize,
                originalBodyFontSize = body && body.style.fontSize,
                fakeUsed = false;

            div.style.cssText = "position:absolute;font-size:1em;width:1em";
            if (!body) {
                body = fakeUsed = doc.createElement("body");
                body.style.background = "none";
            }
            docElem.style.fontSize = "100%";
            body.style.fontSize = "100%";
            body.appendChild(div);
            if (fakeUsed) {
                docElem.insertBefore(body, docElem.firstChild);
            }
            ret = div.offsetWidth;
            if (fakeUsed) {
                docElem.removeChild(body);
            } else {
                body.removeChild(div);
            }
            docElem.style.fontSize = originalHTMLFontSize;
            if (originalBodyFontSize) {
                body.style.fontSize = originalBodyFontSize;
            }
            ret = eminpx = parseFloat(ret);
            return ret;
        },
        applyMedia = function (fromResize) {
            var name = "clientWidth",
                docElemProp = docElem[name],
                currWidth =
                    (doc.compatMode === "CSS1Compat" && docElemProp) ||
                    doc.body[name] ||
                    docElemProp,
                styleBlocks = {},
                lastLink = links[links.length - 1],
                now = new Date().getTime();

            if (fromResize && lastCall && now - lastCall < resizeThrottle) {
                w.clearTimeout(resizeDefer);
                resizeDefer = w.setTimeout(applyMedia, resizeThrottle);
                return;
            } else {
                lastCall = now;
            }
            for (var i in mediastyles) {
                if (mediastyles.hasOwnProperty(i)) {
                    var thisstyle = mediastyles[i],
                        min = thisstyle.minw,
                        max = thisstyle.maxw,
                        minnull = min === null,
                        maxnull = max === null,
                        em = "em";
                    if (!!min) {
                        min =
                            parseFloat(min) *
                            (min.indexOf(em) > -1 ? eminpx || getEmValue() : 1);
                    }
                    if (!!max) {
                        max =
                            parseFloat(max) *
                            (max.indexOf(em) > -1 ? eminpx || getEmValue() : 1);
                    }
                    if (
                        !thisstyle.hasquery ||
                        ((!minnull || !maxnull) &&
                            (minnull || currWidth >= min) &&
                            (maxnull || currWidth <= max))
                    ) {
                        if (!styleBlocks[thisstyle.media]) {
                            styleBlocks[thisstyle.media] = [];
                        }
                        styleBlocks[thisstyle.media].push(
                            rules[thisstyle.rules],
                        );
                    }
                }
            }
            for (var j in appendedEls) {
                if (appendedEls.hasOwnProperty(j)) {
                    if (appendedEls[j] && appendedEls[j].parentNode === head) {
                        head.removeChild(appendedEls[j]);
                    }
                }
            }
            appendedEls.length = 0;
            for (var k in styleBlocks) {
                if (styleBlocks.hasOwnProperty(k)) {
                    var ss = doc.createElement("style"),
                        css = styleBlocks[k].join("\n");
                    ss.type = "text/css";
                    ss.media = k;
                    head.insertBefore(ss, lastLink.nextSibling);
                    if (ss.styleSheet) {
                        ss.styleSheet.cssText = css;
                    } else {
                        ss.appendChild(doc.createTextNode(css));
                    }
                    appendedEls.push(ss);
                }
            }
        },
        translate = function (styles, href, media) {
            var qs = styles
                    .replace(respond.regex.comments, "")
                    .replace(respond.regex.keyframes, "")
                    .match(respond.regex.media),
                ql = (qs && qs.length) || 0;
            href = href.substring(0, href.lastIndexOf("/"));
            var repUrls = function (css) {
                    return css.replace(
                        respond.regex.urls,
                        "$1" + href + "$2$3",
                    );
                },
                useMedia = !ql && media;
            if (href.length) {
                href += "/";
            }
            if (useMedia) {
                ql = 1;
            }
            for (var i = 0; i < ql; i++) {
                var fullq, thisq, eachq, eql;
                if (useMedia) {
                    fullq = media;
                    rules.push(repUrls(styles));
                } else {
                    fullq = qs[i].match(respond.regex.findStyles) && RegExp.$1;
                    rules.push(RegExp.$2 && repUrls(RegExp.$2));
                }
                eachq = fullq.split(",");
                eql = eachq.length;
                for (var j = 0; j < eql; j++) {
                    thisq = eachq[j];
                    if (isUnsupportedMediaQuery(thisq)) {
                        continue;
                    }
                    mediastyles.push({
                        media:
                            (thisq.split("(")[0].match(respond.regex.only) &&
                                RegExp.$2) ||
                            "all",
                        rules: rules.length - 1,
                        hasquery: thisq.indexOf("(") > -1,
                        minw:
                            thisq.match(respond.regex.minw) &&
                            parseFloat(RegExp.$1) + (RegExp.$2 || ""),
                        maxw:
                            thisq.match(respond.regex.maxw) &&
                            parseFloat(RegExp.$1) + (RegExp.$2 || ""),
                    });
                }
            }
            applyMedia();
        },
        makeRequests = function () {
            if (requestQueue.length) {
                var thisRequest = requestQueue.shift();
                ajax(thisRequest.href, function (styles) {
                    translate(styles, thisRequest.href, thisRequest.media);
                    parsedSheets[thisRequest.href] = true;
                    w.setTimeout(function () {
                        makeRequests();
                    }, 0);
                });
            } else {
                respond.callback();
            }
        },
        ripCSS = function () {
            for (var i = 0; i < links.length; i++) {
                var sheet = links[i],
                    href = sheet.href,
                    media = sheet.media,
                    isCSS =
                        sheet.rel && sheet.rel.toLowerCase() === "stylesheet";
                if (!!href && isCSS && !parsedSheets[href]) {
                    if (sheet.styleSheet && sheet.styleSheet.rawCssText) {
                        translate(sheet.styleSheet.rawCssText, href, media);
                        parsedSheets[href] = true;
                    } else {
                        if (
                            (!/^([a-zA-Z:]*\/\/)/.test(href) && !base) ||
                            href.replace(RegExp.$1, "").split("/")[0] ===
                                w.location.host
                        ) {
                            if (href.substring(0, 2) === "//") {
                                href = w.location.protocol + href;
                            }
                            requestQueue.push({
                                href: href,
                                media: media,
                            });
                        }
                    }
                }
            }
            makeRequests();
        };
    ripCSS();
    respond.update = ripCSS;
    respond.getEmValue = getEmValue;
    function callMedia() {
        applyMedia(true);
    }

    if (w.addEventListener) {
        w.addEventListener("resize", callMedia, false);
    } else if (w.attachEvent) {
        w.attachEvent("onresize", callMedia);
    }
})(this);
