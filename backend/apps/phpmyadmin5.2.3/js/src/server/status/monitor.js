/**
 * @fileoverview    Javascript functions used in server status monitor page
 * @name            Server Status Monitor
 *
 * @requires    jQuery
 * @requires    jQueryUI
 * @requires    js/functions.js
 */

// js/config.js
// js/functions.js
// templates/javascript/variables.twig
// templates/server/status/monitor/index.twig

var runtime = {};
var serverTimeDiff;
var serverOs;
var isSuperUser;
var serverDbIsLocal;
var chartSize;
var monitorSettings;

function serverResponseError() {
    var btns = {
        [Messages.strReloadPage]: {
            text: Messages.strReloadPage,
            class: "btn btn-primary",
            click: function () {
                window.location.reload();
            },
        },
    };
    $("#emptyDialog").dialog({
        classes: {
            "ui-dialog-titlebar-close": "btn-close",
        },
        title: Messages.strRefreshFailed,
    });
    $("#emptyDialog").html(
        Functions.getImage("s_attention") +
            Messages.strInvalidResponseExplanation,
    );
    $("#emptyDialog").dialog({
        classes: {
            "ui-dialog-titlebar-close": "btn-close",
        },
        buttons: btns,
    });
}

/**
 * Destroys all monitor related resources
 */
function destroyGrid() {
    if (runtime.charts) {
        $.each(runtime.charts, function (key, value) {
            try {
                value.chart.destroy();
            } catch (err) {}
        });
    }

    try {
        runtime.refreshRequest.abort();
    } catch (err) {}
    try {
        clearTimeout(runtime.refreshTimeout);
    } catch (err) {}
    $("#chartGrid").html("");
    runtime.charts = null;
    runtime.chartAI = 0;
    monitorSettings = null;
}

AJAX.registerOnload("server/status/monitor.js", function () {
    var $jsDataForm = $("#js_data");
    serverTimeDiff =
        new Date().getTime() -
        $jsDataForm.find("input[name=server_time]").val();
    serverOs = $jsDataForm.find("input[name=server_os]").val();
    isSuperUser = $jsDataForm.find("input[name=is_superuser]").val();
    serverDbIsLocal = $jsDataForm.find("input[name=server_db_isLocal]").val();
});

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown("server/status/monitor.js", function () {
    $("#emptyDialog").remove();
    $("a.popupLink").off("click");
    $("body").off("click");
});
/**
 * Popup behaviour
 */
AJAX.registerOnload("server/status/monitor.js", function () {
    $("<div></div>").attr("id", "emptyDialog").appendTo("#page_content");

    $("a.popupLink").on("click", function () {
        var $link = $(this);
        $("div." + $link.attr("href").substr(1))
            .show()
            .offset({
                top: $link.offset().top + $link.height() + 5,
                left: $link.offset().left,
            })
            .addClass("openedPopup");

        return false;
    });
    $("body").on("click", function (event) {
        $("div.openedPopup").each(function () {
            var $cnt = $(this);
            var pos = $cnt.offset();
            if (
                event.pageX > pos.left + $cnt.outerWidth() ||
                event.pageY > pos.top + $cnt.outerHeight()
            ) {
                $cnt.hide().removeClass("openedPopup");
            }
        });
    });
});

AJAX.registerTeardown("server/status/monitor.js", function () {
    $('a[href="#rearrangeCharts"], a[href="#endChartEditMode"]').off("click");
    $('div.popupContent select[name="chartColumns"]').off("change");
    $('div.popupContent select[name="gridChartRefresh"]').off("change");
    $('a[href="#addNewChart"]').off("click");
    $('a[href="#exportMonitorConfig"]').off("click");
    $('a[href="#importMonitorConfig"]').off("click");
    $('a[href="#clearMonitorConfig"]').off("click");
    $('a[href="#pauseCharts"]').off("click");
    $('a[href="#monitorInstructionsDialog"]').off("click");
    $('input[name="chartType"]').off("click");
    $('input[name="useDivisor"]').off("click");
    $('input[name="useUnit"]').off("click");
    $('select[name="varChartList"]').off("click");
    $('a[href="#kibDivisor"]').off("click");
    $('a[href="#mibDivisor"]').off("click");
    $('a[href="#submitClearSeries"]').off("click");
    $('a[href="#submitAddSeries"]').off("click");
    $("#chartPreset").off("click");
    $("#chartStatusVar").off("click");
    destroyGrid();
});

AJAX.registerOnload("server/status/monitor.js", function () {
    $("div.tabLinks").show();
    $("#loadingMonitorIcon").remove();
    if (!codeMirrorEditor) {
        var $elm = $("#sqlquery");
        if ($elm.length > 0 && typeof CodeMirror !== "undefined") {
            codeMirrorEditor = CodeMirror.fromTextArea($elm[0], {
                lineNumbers: true,
                matchBrackets: true,
                indentUnit: 4,
                mode: "text/x-mysql",
                lineWrapping: true,
            });
        }
    }
    $("#logAnalyseDialog")
        .find(".datetimefield")
        .each(function () {
            Functions.addDatepicker($(this));
        });

    var oldChartData = null;
    var newChart = null;
    var chartSpacing;
    var monitorProtocolVersion = "1.0";
    runtime = {
        charts: null,
        refreshTimeout: null,
        refreshRequest: null,
        chartAI: 0,
        redrawCharts: false,
        dataList: [],
        gridMaxPoints: 20,
        xmin: -1,
        xmax: -1,
    };
    monitorSettings = null;

    var defaultMonitorSettings = {
        columns: 3,
        chartSize: { width: 295, height: 250 },
        gridMaxPoints: "auto",

        gridRefresh: 5000,
    };
    var editMode = false;

    var presetCharts = {
        qce: {
            title: Messages.strQueryCacheEfficiency,
            series: [
                {
                    label: Messages.strQueryCacheEfficiency,
                },
            ],
            nodes: [
                {
                    dataPoints: [
                        { type: "statusvar", name: "Qcache_hits" },
                        { type: "statusvar", name: "Com_select" },
                    ],
                    transformFn: "qce",
                },
            ],
            maxYLabel: 0,
        },
        qcu: {
            title: Messages.strQueryCacheUsage,
            series: [
                {
                    label: Messages.strQueryCacheUsed,
                },
            ],
            nodes: [
                {
                    dataPoints: [
                        { type: "statusvar", name: "Qcache_free_memory" },
                        { type: "servervar", name: "query_cache_size" },
                    ],
                    transformFn: "qcu",
                },
            ],
            maxYLabel: 0,
        },
    };
    var selectionTimeDiff = [];
    var selectionStartX;
    var selectionStartY;
    var drawTimeSpan = false;

    switch (serverOs) {
        case "WINNT":
            $.extend(presetCharts, {
                cpu: {
                    title: Messages.strSystemCPUUsage,
                    series: [
                        {
                            label: Messages.strAverageLoad,
                        },
                    ],
                    nodes: [
                        {
                            dataPoints: [{ type: "cpu", name: "loadavg" }],
                        },
                    ],
                    maxYLabel: 100,
                },

                memory: {
                    title: Messages.strSystemMemory,
                    series: [
                        {
                            dataType: "memory",
                            label: Messages.strUsedMemory,
                            fill: true,
                        },
                        {
                            label: Messages.strFreeMemory,
                            fill: true,
                        },
                    ],
                    nodes: [
                        {
                            dataPoints: [{ type: "memory", name: "MemUsed" }],
                            valueDivisor: 1024,
                        },
                        {
                            dataPoints: [{ type: "memory", name: "MemFree" }],
                            valueDivisor: 1024,
                        },
                    ],
                    maxYLabel: 0,
                },

                swap: {
                    title: Messages.strSystemSwap,
                    series: [
                        {
                            label: Messages.strUsedSwap,
                            fill: true,
                        },
                        {
                            label: Messages.strFreeSwap,
                            fill: true,
                        },
                    ],
                    nodes: [
                        {
                            dataPoints: [{ type: "memory", name: "SwapUsed" }],
                            valueDivisor: 1024,
                        },
                        {
                            dataPoints: [{ type: "memory", name: "SwapFree" }],
                            valueDivisor: 1024,
                        },
                    ],
                    maxYLabel: 0,
                },
            });
            break;

        case "Linux":
            $.extend(presetCharts, {
                cpu: {
                    title: Messages.strSystemCPUUsage,
                    series: [
                        {
                            label: Messages.strAverageLoad,
                        },
                    ],
                    nodes: [
                        {
                            dataPoints: [{ type: "cpu", name: "irrelevant" }],
                            transformFn: "cpu-linux",
                        },
                    ],
                    maxYLabel: 0,
                },
                memory: {
                    title: Messages.strSystemMemory,
                    series: [
                        { label: Messages.strBufferedMemory, fill: true },
                        { label: Messages.strUsedMemory, fill: true },
                        { label: Messages.strCachedMemory, fill: true },
                        { label: Messages.strFreeMemory, fill: true },
                    ],
                    nodes: [
                        {
                            dataPoints: [{ type: "memory", name: "Buffers" }],
                            valueDivisor: 1024,
                        },
                        {
                            dataPoints: [{ type: "memory", name: "MemUsed" }],
                            valueDivisor: 1024,
                        },
                        {
                            dataPoints: [{ type: "memory", name: "Cached" }],
                            valueDivisor: 1024,
                        },
                        {
                            dataPoints: [{ type: "memory", name: "MemFree" }],
                            valueDivisor: 1024,
                        },
                    ],
                    maxYLabel: 0,
                },
                swap: {
                    title: Messages.strSystemSwap,
                    series: [
                        { label: Messages.strCachedSwap, fill: true },
                        { label: Messages.strUsedSwap, fill: true },
                        { label: Messages.strFreeSwap, fill: true },
                    ],
                    nodes: [
                        {
                            dataPoints: [
                                { type: "memory", name: "SwapCached" },
                            ],
                            valueDivisor: 1024,
                        },
                        {
                            dataPoints: [{ type: "memory", name: "SwapUsed" }],
                            valueDivisor: 1024,
                        },
                        {
                            dataPoints: [{ type: "memory", name: "SwapFree" }],
                            valueDivisor: 1024,
                        },
                    ],
                    maxYLabel: 0,
                },
            });
            break;

        case "SunOS":
            $.extend(presetCharts, {
                cpu: {
                    title: Messages.strSystemCPUUsage,
                    series: [
                        {
                            label: Messages.strAverageLoad,
                        },
                    ],
                    nodes: [
                        {
                            dataPoints: [{ type: "cpu", name: "loadavg" }],
                        },
                    ],
                    maxYLabel: 0,
                },
                memory: {
                    title: Messages.strSystemMemory,
                    series: [
                        { label: Messages.strUsedMemory, fill: true },
                        { label: Messages.strFreeMemory, fill: true },
                    ],
                    nodes: [
                        {
                            dataPoints: [{ type: "memory", name: "MemUsed" }],
                            valueDivisor: 1024,
                        },
                        {
                            dataPoints: [{ type: "memory", name: "MemFree" }],
                            valueDivisor: 1024,
                        },
                    ],
                    maxYLabel: 0,
                },
                swap: {
                    title: Messages.strSystemSwap,
                    series: [
                        { label: Messages.strUsedSwap, fill: true },
                        { label: Messages.strFreeSwap, fill: true },
                    ],
                    nodes: [
                        {
                            dataPoints: [{ type: "memory", name: "SwapUsed" }],
                            valueDivisor: 1024,
                        },
                        {
                            dataPoints: [{ type: "memory", name: "SwapFree" }],
                            valueDivisor: 1024,
                        },
                    ],
                    maxYLabel: 0,
                },
            });
            break;
    }
    var defaultChartGrid = {
        c0: {
            title: Messages.strQuestions,
            series: [{ label: Messages.strQuestions }],
            nodes: [
                {
                    dataPoints: [{ type: "statusvar", name: "Questions" }],
                    display: "differential",
                },
            ],
            maxYLabel: 0,
        },
        c1: {
            title: Messages.strChartConnectionsTitle,
            series: [
                { label: Messages.strConnections },
                { label: Messages.strProcesses },
            ],
            nodes: [
                {
                    dataPoints: [{ type: "statusvar", name: "Connections" }],
                    display: "differential",
                },
                { dataPoints: [{ type: "proc", name: "processes" }] },
            ],
            maxYLabel: 0,
        },
        c2: {
            title: Messages.strTraffic,
            series: [
                { label: Messages.strBytesSent },
                { label: Messages.strBytesReceived },
            ],
            nodes: [
                {
                    dataPoints: [{ type: "statusvar", name: "Bytes_sent" }],
                    display: "differential",
                    valueDivisor: 1024,
                },
                {
                    dataPoints: [{ type: "statusvar", name: "Bytes_received" }],
                    display: "differential",
                    valueDivisor: 1024,
                },
            ],
            maxYLabel: 0,
        },
    };
    if (serverDbIsLocal && typeof presetCharts.cpu !== "undefined") {
        defaultChartGrid.c3 = presetCharts.cpu;
        defaultChartGrid.c4 = presetCharts.memory;
        defaultChartGrid.c5 = presetCharts.swap;
    }

    $('a[href="#rearrangeCharts"], a[href="#endChartEditMode"]').on(
        "click",
        function (event) {
            event.preventDefault();
            editMode = !editMode;
            if ($(this).attr("href") === "#endChartEditMode") {
                editMode = false;
            }

            $('a[href="#endChartEditMode"]').toggle(editMode);

            if (editMode) {
                $("div.popupContent").hide().removeClass("openedPopup");

                $("#chartGrid").sortableTable({
                    ignoreRect: {
                        top: 8,
                        left: chartSize.width - 63,
                        width: 54,
                        height: 24,
                    },
                });
            } else {
                $("#chartGrid").sortableTable("destroy");
            }
            saveMonitor(); // Save settings
            return false;
        },
    );
    $('div.popupContent select[name="chartColumns"]').on("change", function () {
        monitorSettings.columns = parseInt(this.value, 10);

        calculateChartSize();
        $("#chartGrid")
            .find("tr td")
            .css("width", chartSize.width + "px");
        $("#chartGrid")
            .find(".monitorChart")
            .css({
                width: chartSize.width + "px",
                height: chartSize.height + "px",
            });

        var numColumns;
        var $tr = $("#chartGrid").find("tr").first();

        var tempManageCols = function () {
            if (numColumns > monitorSettings.columns) {
                if ($tr.next().length === 0) {
                    $tr.after("<tr></tr>");
                }
                $tr.next().prepend($(this));
            }
            numColumns++;
        };

        var tempAddCol = function () {
            if ($(this).next().length !== 0) {
                $(this).append($(this).next().find("td").first());
            }
        };

        while ($tr.length !== 0) {
            numColumns = 1;
            $tr.find("td").each(tempManageCols);
            if ($tr.next().length > 0) {
                var cnt = monitorSettings.columns - $tr.find("td").length;
                for (var i = 0; i < cnt; i++) {
                    $tr.append($tr.next().find("td").first());
                    $tr.nextAll().each(tempAddCol);
                }
            }

            $tr = $tr.next();
        }

        if (monitorSettings.gridMaxPoints === "auto") {
            runtime.gridMaxPoints = Math.round((chartSize.width - 40) / 12);
        }

        runtime.xmin =
            new Date().getTime() -
            serverTimeDiff -
            runtime.gridMaxPoints * monitorSettings.gridRefresh;
        runtime.xmax =
            new Date().getTime() - serverTimeDiff + monitorSettings.gridRefresh;

        if (editMode) {
            $("#chartGrid").sortableTable("refresh");
        }

        refreshChartGrid();
        saveMonitor(); // Save settings
    });

    $('div.popupContent select[name="gridChartRefresh"]').on(
        "change",
        function () {
            monitorSettings.gridRefresh = parseInt(this.value, 10) * 1000;
            clearTimeout(runtime.refreshTimeout);

            if (runtime.refreshRequest) {
                runtime.refreshRequest.abort();
            }

            runtime.xmin =
                new Date().getTime() -
                serverTimeDiff -
                runtime.gridMaxPoints * monitorSettings.gridRefresh;
            runtime.refreshTimeout = setTimeout(
                refreshChartGrid,
                monitorSettings.gridRefresh,
            );

            saveMonitor(); // Save settings
        },
    );

    $('a[href="#addNewChart"]').on("click", function (event) {
        event.preventDefault();

        $("#addChartButton").on("click", function () {
            var type = $('input[name="chartType"]:checked').val();

            if (type === "preset") {
                newChart =
                    presetCharts[
                        $("#addChartModal")
                            .find('select[name="presetCharts"]')
                            .prop("value")
                    ];
            } else {
                if (
                    !newChart ||
                    !newChart.nodes ||
                    newChart.nodes.length === 0
                ) {
                    alert(Messages.strAddOneSeriesWarning);
                    return;
                }
            }

            newChart.title = $('input[name="chartTitle"]').val();
            addChart($.extend(true, {}, newChart));

            newChart = null;

            saveMonitor(); // Save settings

            $("#closeModalButton").off("click");
            $("#addChartButton").off("click");
        });

        $("#closeModalButton").on("click", function () {
            newChart = null;
            $("span#clearSeriesLink").hide();
            $("#seriesPreview").html("");
            $("#closeModalButton").off("click");
            $("#addChartButton").off("click");
        });

        var $presetList = $("#addChartModal").find(
            'select[name="presetCharts"]',
        );
        if ($presetList.html().length === 0) {
            $.each(presetCharts, function (key, value) {
                $presetList.append(
                    '<option value="' + key + '">' + value.title + "</option>",
                );
            });
            $presetList.on("change", function () {
                $('input[name="chartTitle"]').val(
                    $presetList.find(":selected").text(),
                );
                $("#chartPreset").prop("checked", true);
            });
            $("#chartPreset").on("click", function () {
                $('input[name="chartTitle"]').val(
                    $presetList.find(":selected").text(),
                );
            });
            $("#chartStatusVar").on("click", function () {
                $('input[name="chartTitle"]').val(
                    $("#chartSeries")
                        .find(":selected")
                        .text()
                        .replace(/_/g, " "),
                );
            });
            $("#chartSeries").on("change", function () {
                $('input[name="chartTitle"]').val(
                    $("#chartSeries")
                        .find(":selected")
                        .text()
                        .replace(/_/g, " "),
                );
            });
        }

        $("#addChartModal").modal("show");

        $("#seriesPreview").html("<i>" + Messages.strNone + "</i>");

        return false;
    });

    $('a[href="#exportMonitorConfig"]').on("click", function (event) {
        event.preventDefault();
        var gridCopy = {};
        $.each(runtime.charts, function (key, elem) {
            gridCopy[key] = {};
            gridCopy[key].nodes = elem.nodes;
            gridCopy[key].series = elem.series;
            gridCopy[key].settings = elem.settings;
            gridCopy[key].title = elem.title;
            gridCopy[key].maxYLabel = elem.maxYLabel;
        });
        var exportData = {
            monitorCharts: gridCopy,
            monitorSettings: monitorSettings,
        };

        var blob = new Blob([JSON.stringify(exportData)], {
            type: "application/octet-stream",
        });
        var url = null;
        var fileName = "monitor-config.json";
        if (window.navigator && window.navigator.msSaveOrOpenBlob) {
            window.navigator.msSaveOrOpenBlob(blob, fileName);
        } else {
            url = URL.createObjectURL(blob);
            window.location.href = url;
        }
        setTimeout(function () {
            if (url !== null) {
                window.URL.revokeObjectURL(url);
            }
            url = undefined;
            blob = undefined;
        }, 100);
    });

    $('a[href="#importMonitorConfig"]').on("click", function (event) {
        event.preventDefault();
        $("#emptyDialog").dialog({
            classes: {
                "ui-dialog-titlebar-close": "btn-close",
            },
            title: Messages.strImportDialogTitle,
        });
        $("#emptyDialog").html(
            Messages.strImportDialogMessage +
                "<br><form>" +
                '<input type="file" name="file" id="import_file"> </form>',
        );

        var dlgBtns = {
            [Messages.strImport]: {
                text: Messages.strImport,
                class: "btn btn-primary",
            },
            [Messages.strCancel]: {
                text: Messages.strCancel,
                class: "btn btn-secondary",
            },
        };

        dlgBtns[Messages.strImport].click = function () {
            var input = $("#emptyDialog").find("#import_file")[0];
            var reader = new FileReader();

            reader.onerror = function (event) {
                alert(
                    Messages.strFailedParsingConfig +
                        "\n" +
                        event.target.error.code,
                );
            };
            reader.onload = function (e) {
                var data = e.target.result;
                var json = null;
                try {
                    json = JSON.parse(data);
                } catch (err) {
                    alert(Messages.strFailedParsingConfig);
                    $("#emptyDialog").dialog("close");
                    return;
                }
                if (!json || !json.monitorCharts || !json.monitorCharts) {
                    alert(Messages.strFailedParsingConfig);
                    $("#emptyDialog").dialog("close");
                    return;
                }
                try {
                    if (isStorageSupported("localStorage")) {
                        window.localStorage.monitorCharts = JSON.stringify(
                            json.monitorCharts,
                        );
                        window.localStorage.monitorSettings = JSON.stringify(
                            json.monitorSettings,
                        );
                    }
                    rebuildGrid();
                } catch (err) {
                    alert(Messages.strFailedBuildingGrid);
                    if (isStorageSupported("localStorage")) {
                        window.localStorage.removeItem("monitorCharts");
                        window.localStorage.removeItem("monitorSettings");
                    }
                    rebuildGrid();
                }

                $("#emptyDialog").dialog("close");
            };
            if (input.files[0]) {
                reader.readAsText(input.files[0]);
            }
        };

        dlgBtns[Messages.strCancel].click = function () {
            $(this).dialog("close");
        };

        $("#emptyDialog").dialog({
            classes: {
                "ui-dialog-titlebar-close": "btn-close",
            },
            width: "auto",
            height: "auto",
            buttons: dlgBtns,
        });
    });

    $('a[href="#clearMonitorConfig"]').on("click", function (event) {
        event.preventDefault();
        if (isStorageSupported("localStorage")) {
            window.localStorage.removeItem("monitorCharts");
            window.localStorage.removeItem("monitorSettings");
            window.localStorage.removeItem("monitorVersion");
        }
        $(this).hide();
        rebuildGrid();
    });

    $('a[href="#pauseCharts"]').on("click", function (event) {
        event.preventDefault();
        runtime.redrawCharts = !runtime.redrawCharts;
        if (!runtime.redrawCharts) {
            $(this).html(
                Functions.getImage("play") + Messages.strResumeMonitor,
            );
        } else {
            $(this).html(
                Functions.getImage("pause") + Messages.strPauseMonitor,
            );
            if (!runtime.charts) {
                initGrid();
                $('a[href="#settingsPopup"]').show();
            }
        }
        return false;
    });

    $('a[href="#monitorInstructionsDialog"]').on("click", function (event) {
        event.preventDefault();

        var $dialog = $("#monitorInstructionsDialog");
        var dlgBtns = {
            [Messages.strClose]: {
                text: Messages.strClose,
                class: "btn btn-primary",
                click: function () {
                    $(this).dialog("close");
                },
            },
        };
        $dialog
            .dialog({
                classes: {
                    "ui-dialog-titlebar-close": "btn-close",
                },
                width: "60%",
                height: "auto",
                buttons: dlgBtns,
            })
            .find("img.ajaxIcon")
            .show();

        var loadLogVars = function (getvars) {
            var vars = {
                ajax_request: true,
                server: CommonParams.get("server"),
            };
            if (getvars) {
                $.extend(vars, getvars);
            }

            $.post(
                "index.php?route=/server/status/monitor/log-vars",
                vars,
                function (data) {
                    var logVars;
                    if (typeof data !== "undefined" && data.success === true) {
                        logVars = data.message;
                    } else {
                        return serverResponseError();
                    }
                    var icon = Functions.getImage("s_success");
                    var msg = "";
                    var str = "";

                    if (logVars.general_log === "ON") {
                        if (logVars.slow_query_log === "ON") {
                            msg = Messages.strBothLogOn;
                        } else {
                            msg = Messages.strGenLogOn;
                        }
                    }

                    if (msg.length === 0 && logVars.slow_query_log === "ON") {
                        msg = Messages.strSlowLogOn;
                    }

                    if (msg.length === 0) {
                        icon = Functions.getImage("s_error");
                        msg = Messages.strBothLogOff;
                    }

                    str =
                        "<b>" +
                        Messages.strCurrentSettings +
                        '</b><br><div class="smallIndent">';
                    str += icon + msg + "<br>";

                    if (logVars.log_output !== "TABLE") {
                        str +=
                            Functions.getImage("s_error") +
                            " " +
                            Messages.strLogOutNotTable +
                            "<br>";
                    } else {
                        str +=
                            Functions.getImage("s_success") +
                            " " +
                            Messages.strLogOutIsTable +
                            "<br>";
                    }

                    if (logVars.slow_query_log === "ON") {
                        if (logVars.long_query_time > 2) {
                            str += Functions.getImage("s_attention") + " ";
                            str += Functions.sprintf(
                                Messages.strSmallerLongQueryTimeAdvice,
                                logVars.long_query_time,
                            );
                            str += "<br>";
                        }

                        if (logVars.long_query_time < 2) {
                            str += Functions.getImage("s_success") + " ";
                            str += Functions.sprintf(
                                Messages.strLongQueryTimeSet,
                                logVars.long_query_time,
                            );
                            str += "<br>";
                        }
                    }

                    str += "</div>";

                    if (isSuperUser) {
                        str +=
                            "<p></p><b>" + Messages.strChangeSettings + "</b>";
                        str += '<div class="smallIndent">';
                        str += Messages.strSettingsAppliedGlobal + "<br>";

                        var varValue = "TABLE";
                        if (logVars.log_output === "TABLE") {
                            varValue = "FILE";
                        }

                        str +=
                            '- <a class="set" href="#log_output-' +
                            varValue +
                            '">';
                        str += Functions.sprintf(
                            Messages.strSetLogOutput,
                            varValue,
                        );
                        str += " </a><br>";

                        if (logVars.general_log !== "ON") {
                            str += '- <a class="set" href="#general_log-ON">';
                            str += Functions.sprintf(
                                Messages.strEnableVar,
                                "general_log",
                            );
                            str += " </a><br>";
                        } else {
                            str += '- <a class="set" href="#general_log-OFF">';
                            str += Functions.sprintf(
                                Messages.strDisableVar,
                                "general_log",
                            );
                            str += " </a><br>";
                        }

                        if (logVars.slow_query_log !== "ON") {
                            str +=
                                '- <a class="set" href="#slow_query_log-ON">';
                            str += Functions.sprintf(
                                Messages.strEnableVar,
                                "slow_query_log",
                            );
                            str += " </a><br>";
                        } else {
                            str +=
                                '- <a class="set" href="#slow_query_log-OFF">';
                            str += Functions.sprintf(
                                Messages.strDisableVar,
                                "slow_query_log",
                            );
                            str += " </a><br>";
                        }

                        varValue = 5;
                        if (logVars.long_query_time > 2) {
                            varValue = 1;
                        }

                        str +=
                            '- <a class="set" href="#long_query_time-' +
                            varValue +
                            '">';
                        str += Functions.sprintf(
                            Messages.setSetLongQueryTime,
                            varValue,
                        );
                        str += " </a><br>";
                    } else {
                        str += Messages.strNoSuperUser + "<br>";
                    }

                    str += "</div>";

                    $dialog
                        .find("div.monitorUse")
                        .toggle(
                            logVars.log_output === "TABLE" &&
                                (logVars.slow_query_log === "ON" ||
                                    logVars.general_log === "ON"),
                        );

                    $dialog.find("div.ajaxContent").html(str);
                    $dialog.find("img.ajaxIcon").hide();
                    $dialog.find("a.set").on("click", function () {
                        var nameValue = $(this).attr("href").split("-");
                        loadLogVars({
                            varName: nameValue[0].substr(1),
                            varValue: nameValue[1],
                        });
                        $dialog.find("img.ajaxIcon").show();
                    });
                },
            );
        };

        loadLogVars();

        return false;
    });

    $('input[name="chartType"]').on("change", function () {
        $("#chartVariableSettings").toggle(
            this.checked && this.value === "variable",
        );
        var title = $('input[name="chartTitle"]').val();
        if (
            title === Messages.strChartTitle ||
            title ===
                $(
                    'label[for="' +
                        $('input[name="chartTitle"]').data("lastRadio") +
                        '"]',
                ).text()
        ) {
            $('input[name="chartTitle"]')
                .data("lastRadio", $(this).attr("id"))
                .val($('label[for="' + $(this).attr("id") + '"]').text());
        }
    });

    $('input[name="useDivisor"]').on("change", function () {
        $("span.divisorInput").toggle(this.checked);
    });

    $('input[name="useUnit"]').on("change", function () {
        $("span.unitInput").toggle(this.checked);
    });

    $('select[name="varChartList"]').on("change", function () {
        if (this.selectedIndex !== 0) {
            $("#variableInput").val(this.value);
        }
    });

    $('a[href="#kibDivisor"]').on("click", function (event) {
        event.preventDefault();
        $('input[name="valueDivisor"]').val(1024);
        $('input[name="valueUnit"]').val(Messages.strKiB);
        $("span.unitInput").toggle(true);
        $('input[name="useUnit"]').prop("checked", true);
        return false;
    });

    $('a[href="#mibDivisor"]').on("click", function (event) {
        event.preventDefault();
        $('input[name="valueDivisor"]').val(1024 * 1024);
        $('input[name="valueUnit"]').val(Messages.strMiB);
        $("span.unitInput").toggle(true);
        $('input[name="useUnit"]').prop("checked", true);
        return false;
    });

    $('a[href="#submitClearSeries"]').on("click", function (event) {
        event.preventDefault();
        $("#seriesPreview").html("<i>" + Messages.strNone + "</i>");
        newChart = null;
        $("#clearSeriesLink").hide();
    });

    $('a[href="#submitAddSeries"]').on("click", function (event) {
        event.preventDefault();
        if ($("#variableInput").val() === "") {
            return false;
        }

        if (newChart === null) {
            $("#seriesPreview").html("");

            newChart = {
                title: $('input[name="chartTitle"]').val(),
                nodes: [],
                series: [],
                maxYLabel: 0,
            };
        }

        var serie = {
            dataPoints: [
                { type: "statusvar", name: $("#variableInput").val() },
            ],
            display: $('input[name="differentialValue"]').prop("checked")
                ? "differential"
                : "",
        };

        if (serie.dataPoints[0].name === "Processes") {
            serie.dataPoints[0].type = "proc";
        }

        if ($('input[name="useDivisor"]').prop("checked")) {
            serie.valueDivisor = parseInt(
                $('input[name="valueDivisor"]').val(),
                10,
            );
        }

        if ($('input[name="useUnit"]').prop("checked")) {
            serie.unit = $('input[name="valueUnit"]').val();
        }

        var str =
            serie.display === "differential"
                ? ", " + Messages.strDifferential
                : "";
        str += serie.valueDivisor
            ? ", " +
              Functions.sprintf(Messages.strDividedBy, serie.valueDivisor)
            : "";
        str += serie.unit ? ", " + Messages.strUnit + ": " + serie.unit : "";

        var newSeries = {
            label: $("#variableInput").val().replace(/_/g, " "),
        };
        newChart.series.push(newSeries);
        $("#seriesPreview").append(
            "- " + Functions.escapeHtml(newSeries.label + str) + "<br>",
        );
        newChart.nodes.push(serie);
        $("#variableInput").val("");
        $('input[name="differentialValue"]').prop("checked", true);
        $('input[name="useDivisor"]').prop("checked", false);
        $('input[name="useUnit"]').prop("checked", false);
        $('input[name="useDivisor"]').trigger("change");
        $('input[name="useUnit"]').trigger("change");
        $('select[name="varChartList"]').get(0).selectedIndex = 0;

        $("#clearSeriesLink").show();

        return false;
    });

    $("#variableInput").autocomplete({
        source: variableNames,
    });

    function initGrid() {
        var i;

        if (isStorageSupported("localStorage")) {
            if (typeof window.localStorage.monitorCharts !== "undefined") {
                runtime.charts = JSON.parse(window.localStorage.monitorCharts);
            }
            if (typeof window.localStorage.monitorSettings !== "undefined") {
                monitorSettings = JSON.parse(
                    window.localStorage.monitorSettings,
                );
            }

            $('a[href="#clearMonitorConfig"]').toggle(runtime.charts !== null);

            if (
                runtime.charts !== null &&
                typeof window.localStorage.monitorVersion !== "undefined" &&
                monitorProtocolVersion !== window.localStorage.monitorVersion
            ) {
                $("#emptyDialog").dialog({
                    classes: {
                        "ui-dialog-titlebar-close": "btn-close",
                    },
                    title: Messages.strIncompatibleMonitorConfig,
                });
                $("#emptyDialog").html(
                    Messages.strIncompatibleMonitorConfigDescription,
                );

                var dlgBtns = {
                    [Messages.strClose]: {
                        text: Messages.strClose,
                        class: "btn btn-primary",
                        click: function () {
                            $(this).dialog("close");
                        },
                    },
                };

                $("#emptyDialog").dialog({
                    classes: {
                        "ui-dialog-titlebar-close": "btn-close",
                    },
                    width: 400,
                    buttons: dlgBtns,
                });
            }
        }

        if (runtime.charts === null) {
            runtime.charts = defaultChartGrid;
        }
        if (monitorSettings === null) {
            monitorSettings = defaultMonitorSettings;
        }

        $('select[name="gridChartRefresh"]').val(
            monitorSettings.gridRefresh / 1000,
        );
        $('select[name="chartColumns"]').val(monitorSettings.columns);

        if (monitorSettings.gridMaxPoints === "auto") {
            runtime.gridMaxPoints = Math.round(
                (monitorSettings.chartSize.width - 40) / 12,
            );
        } else {
            runtime.gridMaxPoints = monitorSettings.gridMaxPoints;
        }

        runtime.xmin =
            new Date().getTime() -
            serverTimeDiff -
            runtime.gridMaxPoints * monitorSettings.gridRefresh;
        runtime.xmax =
            new Date().getTime() - serverTimeDiff + monitorSettings.gridRefresh;

        $("#chartGrid").html(
            "<tr><td></td><td></td></tr><tr><td></td><td></td></tr>",
        );
        chartSpacing = {
            width:
                $("#chartGrid").find("td").eq(1).offset().left -
                $("#chartGrid").find("td").eq(0).offset().left,
            height:
                $("#chartGrid").find("tr").eq(1).find("td").eq(1).offset().top -
                $("#chartGrid").find("tr").eq(0).find("td").eq(0).offset().top,
        };
        $("#chartGrid").html("");

        var keys = [];
        $.each(runtime.charts, function (key) {
            keys.push(key);
        });
        keys.sort();
        for (i = 0; i < keys.length; i++) {
            addChart(runtime.charts[keys[i]], true);
        }

        var numCharts = $("#chartGrid").find(".monitorChart").length;
        var numMissingCells =
            (monitorSettings.columns - (numCharts % monitorSettings.columns)) %
            monitorSettings.columns;
        for (i = 0; i < numMissingCells; i++) {
            $("#chartGrid").find("tr").last().append("<td></td>");
        }
        calculateChartSize();
        $("#chartGrid")
            .find("tr td")
            .css("width", chartSize.width + "px");

        buildRequiredDataList();
        refreshChartGrid();
    }

    /* Calls destroyGrid() and initGrid(), but before doing so it saves the chart
     * data from each chart and restores it after the monitor is initialized again */
    function rebuildGrid() {
        var oldData = null;
        if (runtime.charts) {
            oldData = {};
            $.each(runtime.charts, function (key, chartObj) {
                for (var i = 0, l = chartObj.nodes.length; i < l; i++) {
                    oldData[chartObj.nodes[i].dataPoint] = [];
                    for (
                        var j = 0, ll = chartObj.chart.series[i].data.length;
                        j < ll;
                        j++
                    ) {
                        oldData[chartObj.nodes[i].dataPoint].push([
                            chartObj.chart.series[i].data[j].x,
                            chartObj.chart.series[i].data[j].y,
                        ]);
                    }
                }
            });
        }

        destroyGrid();
        initGrid();
    }

    function calculateChartSize() {
        var panelWidth;
        if ($("body").height() > $(window).height()) {
            // has vertical scroll bar
            panelWidth = $("#logTable").innerWidth();
        } else {
            panelWidth = $("#logTable").innerWidth() - 10; // leave some space for vertical scroll bar
        }

        var wdt = panelWidth;
        var windowWidth = $(window).width();

        if (windowWidth > 768) {
            wdt =
                (panelWidth -
                    monitorSettings.columns * Math.abs(chartSpacing.width)) /
                monitorSettings.columns;
        }

        chartSize = {
            width: Math.floor(wdt),
            height: Math.floor(0.55 * wdt),
        };
    }

    function addChart(chartObj, initialize) {
        var i;
        var settings = {
            title: Functions.escapeHtml(chartObj.title),
            grid: {
                drawBorder: false,
                shadow: false,
                background: "rgba(0,0,0,0)",
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.DateAxisRenderer,
                    tickOptions: {
                        formatString: "%H:%M:%S",
                        showGridline: false,
                    },
                    min: runtime.xmin,
                    max: runtime.xmax,
                },
                yaxis: {
                    min: 0,
                    max: 100,
                    tickInterval: 20,
                },
            },
            seriesDefaults: {
                rendererOptions: {
                    smooth: true,
                },
                showLine: true,
                lineWidth: 2,
                markerOptions: {
                    size: 6,
                },
            },
            highlighter: {
                show: true,
            },
        };

        if (
            settings.title === Messages.strSystemCPUUsage ||
            settings.title === Messages.strQueryCacheEfficiency
        ) {
            settings.axes.yaxis.tickOptions = {
                formatString: "%d %%",
            };
        } else if (
            settings.title === Messages.strSystemMemory ||
            settings.title === Messages.strSystemSwap
        ) {
            settings.stackSeries = true;
            settings.axes.yaxis.tickOptions = {
                formatter: $.jqplot.byteFormatter(2), // MiB
            };
        } else if (settings.title === Messages.strTraffic) {
            settings.axes.yaxis.tickOptions = {
                formatter: $.jqplot.byteFormatter(1), // KiB
            };
        } else if (
            settings.title === Messages.strQuestions ||
            settings.title === Messages.strConnections
        ) {
            settings.axes.yaxis.tickOptions = {
                formatter: function (format, val) {
                    if (Math.abs(val) >= 1000000) {
                        return $.jqplot.sprintf("%.3g M", val / 1000000);
                    } else if (Math.abs(val) >= 1000) {
                        return $.jqplot.sprintf("%.3g k", val / 1000);
                    } else {
                        return $.jqplot.sprintf("%d", val);
                    }
                },
            };
        }

        settings.series = chartObj.series;

        if ($("#" + "gridchart" + runtime.chartAI).length === 0) {
            var numCharts = $("#chartGrid").find(".monitorChart").length;

            if (numCharts === 0 || numCharts % monitorSettings.columns === 0) {
                $("#chartGrid").append("<tr></tr>");
            }

            if (!chartSize) {
                calculateChartSize();
            }
            $("#chartGrid")
                .find("tr")
                .last()
                .append(
                    '<td><div id="gridChartContainer' +
                        runtime.chartAI +
                        '" class="">' +
                        '<div class="ui-state-default monitorChart"' +
                        ' id="gridchart' +
                        runtime.chartAI +
                        '"' +
                        ' style="width:' +
                        chartSize.width +
                        "px; height:" +
                        chartSize.height +
                        'px;"></div>' +
                        "</div></td>",
                );
        }
        var series = [];
        for (i in chartObj.series) {
            series.push([[0, 0]]);
        }

        var tempTooltipContentEditor = function (
            str,
            seriesIndex,
            pointIndex,
            plot,
        ) {
            var j;
            var tooltipHtml = '<div id="tooltip_editor">';
            var timeValue = str.split(",")[0];
            var seriesValue;
            tooltipHtml += "Time: " + timeValue;
            tooltipHtml += '<span id="tooltip_font">';
            for (j in plot.series) {
                if (plot.series[j].data.length > pointIndex) {
                    seriesValue = plot.series[j].data[pointIndex][1];
                } else {
                    return;
                }
                var seriesLabel = plot.series[j].label;
                var seriesColor = plot.series[j].color;
                if (plot.series[0]._yaxis.tickOptions.formatter) {
                    // eslint-disable-line no-underscore-dangle
                    seriesValue = plot.series[0]._yaxis.tickOptions.formatter(
                        "%s",
                        seriesValue,
                    );
                } else if (plot.series[0]._yaxis.tickOptions.formatString) {
                    // eslint-disable-line no-underscore-dangle
                    seriesValue = Functions.sprintf(
                        plot.series[0]._yaxis.tickOptions.formatString,
                        seriesValue,
                    );
                }
                tooltipHtml +=
                    '<br><span style="color:' +
                    seriesColor +
                    '">' +
                    seriesLabel +
                    ": " +
                    seriesValue +
                    "</span>";
            }
            tooltipHtml += "</span></div>";
            return tooltipHtml;
        };
        for (i in settings.series) {
            settings.series[i].highlighter = {
                show: true,
                tooltipContentEditor: tempTooltipContentEditor,
            };
        }

        chartObj.chart = $.jqplot(
            "gridchart" + runtime.chartAI,
            series,
            settings,
        );
        for (i in chartObj.chart.series) {
            chartObj.chart.series[i].data.shift();
        }

        var $legend = $("<div></div>").css("padding", "0.5em");
        for (i in chartObj.chart.series) {
            $legend.append(
                $("<div></div>")
                    .append(
                        $("<div>")
                            .css({
                                width: "1em",
                                height: "1em",
                                background: chartObj.chart.seriesColors[i],
                            })
                            .addClass("float-start"),
                    )
                    .append(
                        $("<div>")
                            .text(chartObj.chart.series[i].label)
                            .addClass("float-start"),
                    )
                    .append($('<div class="clearfloat">'))
                    .addClass("float-start"),
            );
        }
        $("#gridchart" + runtime.chartAI)
            .parent()
            .append($legend);

        if (initialize !== true) {
            runtime.charts["c" + runtime.chartAI] = chartObj;
            buildRequiredDataList();
        }
        $("#gridchart" + runtime.chartAI).on(
            "jqplotMouseDown",
            function (ev, gridpos, datapos) {
                drawTimeSpan = true;
                selectionTimeDiff.push(datapos.xaxis);
                if ($("#selection_box").length) {
                    $("#selection_box").remove();
                }
                var selectionBox = $('<div id="selection_box" >');
                $(document.body).append(selectionBox);
                selectionStartX = ev.pageX;
                selectionStartY = ev.pageY;
                selectionBox
                    .attr({ id: "selection_box" })
                    .css({
                        top: selectionStartY - gridpos.y,
                        left: selectionStartX,
                    })
                    .fadeIn();
            },
        );

        $("#gridchart" + runtime.chartAI).on(
            "jqplotMouseUp",
            function (ev, gridpos, datapos) {
                if (!drawTimeSpan || editMode) {
                    return;
                }

                selectionTimeDiff.push(datapos.xaxis);

                if (selectionTimeDiff[1] <= selectionTimeDiff[0]) {
                    selectionTimeDiff = [];
                    return;
                }
                var min = new Date(Math.ceil(selectionTimeDiff[0]));
                var max = new Date(Math.ceil(selectionTimeDiff[1]));
                getLogAnalyseDialog(min, max);
                selectionTimeDiff = [];
                drawTimeSpan = false;
            },
        );

        $("#gridchart" + runtime.chartAI).on("jqplotMouseMove", function (ev) {
            if (!drawTimeSpan || editMode) {
                return;
            }
            if (selectionStartX !== undefined) {
                $("#selection_box")
                    .css({
                        width: Math.ceil(ev.pageX - selectionStartX),
                    })
                    .fadeIn();
            }
        });

        $("#gridchart" + runtime.chartAI).on("jqplotMouseLeave", function () {
            drawTimeSpan = false;
        });
        $(document.body).on("mouseup", function () {
            if ($("#selection_box").length) {
                $("#selection_box").remove();
            }
        });
        $("#chartGrid")
            .find("div svg")
            .find("*[zIndex=20], *[zIndex=21], *[zIndex=19]")
            .toggle(editMode);

        runtime.chartAI++;
    }

    function getLogAnalyseDialog(min, max) {
        var $logAnalyseDialog = $("#logAnalyseDialog");
        var $dateStart = $logAnalyseDialog.find('input[name="dateStart"]');
        var $dateEnd = $logAnalyseDialog.find('input[name="dateEnd"]');
        $dateStart.prop("readonly", true);
        $dateEnd.prop("readonly", true);

        var dlgBtns = {
            [Messages.strFromSlowLog]: {
                text: Messages.strFromSlowLog,
                class: "btn btn-secondary",
            },
            [Messages.strFromGeneralLog]: {
                text: Messages.strFromGeneralLog,
                class: "btn btn-secondary",
            },
        };

        dlgBtns[Messages.strFromSlowLog].click = function () {
            loadLog("slow", min, max);
            $(this).dialog("close");
        };

        dlgBtns[Messages.strFromGeneralLog].click = function () {
            loadLog("general", min, max);
            $(this).dialog("close");
        };

        $logAnalyseDialog.dialog({
            classes: {
                "ui-dialog-titlebar-close": "btn-close",
            },
            width: "auto",
            height: "auto",
            buttons: dlgBtns,
        });

        Functions.addDatepicker($dateStart, "datetime", {
            showMillisec: false,
            showMicrosec: false,
            timeFormat: "HH:mm:ss",
            firstDay: firstDayOfCalendar,
        });
        Functions.addDatepicker($dateEnd, "datetime", {
            showMillisec: false,
            showMicrosec: false,
            timeFormat: "HH:mm:ss",
            firstDay: firstDayOfCalendar,
        });
        $dateStart.datepicker("setDate", min);
        $dateEnd.datepicker("setDate", max);
    }

    function loadLog(type, min, max) {
        var dateStart =
            Date.parse(
                $("#logAnalyseDialog")
                    .find('input[name="dateStart"]')
                    .datepicker("getDate"),
            ) || min;
        var dateEnd =
            Date.parse(
                $("#logAnalyseDialog")
                    .find('input[name="dateEnd"]')
                    .datepicker("getDate"),
            ) || max;

        loadLogStatistics({
            src: type,
            start: dateStart,
            end: dateEnd,
            removeVariables: $("#removeVariables").prop("checked"),
            limitTypes: $("#limitTypes").prop("checked"),
        });
    }

    function refreshChartGrid() {
        runtime.refreshRequest = $.post(
            "index.php?route=/server/status/monitor/chart",
            {
                ajax_request: true,
                requiredData: JSON.stringify(runtime.dataList),
                server: CommonParams.get("server"),
            },
            function (data) {
                var chartData;
                if (typeof data !== "undefined" && data.success === true) {
                    chartData = data.message;
                } else {
                    return serverResponseError();
                }
                var value;
                var i = 0;
                var diff;
                var total;

                $.each(runtime.charts, function (orderKey, elem) {
                    var key = elem.chartID;
                    if (!chartData[key]) {
                        return;
                    }
                    total = 0;
                    for (var j = 0; j < elem.nodes.length; j++) {
                        if (i === 0 && j === 0) {
                            if (oldChartData === null) {
                                diff = chartData.x - runtime.xmax;
                            } else {
                                diff = parseInt(
                                    chartData.x - oldChartData.x,
                                    10,
                                );
                            }

                            runtime.xmin += diff;
                            runtime.xmax += diff;
                        }

                        if (elem.nodes[j].transformFn) {
                            value = chartValueTransform(
                                elem.nodes[j].transformFn,
                                chartData[key][j],
                                oldChartData === null ||
                                    oldChartData[key] === null ||
                                    oldChartData[key] === undefined
                                    ? null
                                    : oldChartData[key][j],
                            );
                        } else {
                            value = parseFloat(chartData[key][j][0].value);

                            if (elem.nodes[j].display === "differential") {
                                if (
                                    oldChartData === null ||
                                    oldChartData[key] === null ||
                                    oldChartData[key] === undefined
                                ) {
                                    continue;
                                }
                                value -= oldChartData[key][j][0].value;
                            }

                            if (elem.nodes[j].valueDivisor) {
                                value = value / elem.nodes[j].valueDivisor;
                            }
                        }
                        if (value !== undefined) {
                            elem.chart.series[j].data.push([
                                chartData.x,
                                value,
                            ]);
                            if (value > elem.maxYLabel) {
                                elem.maxYLabel = value;
                            } else if (elem.maxYLabel === 0) {
                                elem.maxYLabel = 0.5;
                            }
                            if (
                                elem.chart.series[j].data.length >
                                    runtime.gridMaxPoints &&
                                elem.chart.series[j].data[0][0] < runtime.xmin
                            ) {
                                if (
                                    elem.maxYLabel <=
                                    elem.chart.series[j].data[0][1]
                                ) {
                                    elem.chart.series[j].data.splice(
                                        0,
                                        elem.chart.series[j].data.length -
                                            runtime.gridMaxPoints,
                                    );
                                    elem.maxYLabel = getMaxYLabel(
                                        elem.chart.series[j].data,
                                    );
                                } else {
                                    elem.chart.series[j].data.splice(
                                        0,
                                        elem.chart.series[j].data.length -
                                            runtime.gridMaxPoints,
                                    );
                                }
                            }
                            if (
                                elem.title === Messages.strSystemMemory ||
                                elem.title === Messages.strSystemSwap
                            ) {
                                total += value;
                            }
                        }
                    }
                    var tickInterval = (runtime.xmax - runtime.xmin) / 5;
                    elem.chart.axes.xaxis.ticks = [
                        runtime.xmax - tickInterval * 4,
                        runtime.xmax - tickInterval * 3,
                        runtime.xmax - tickInterval * 2,
                        runtime.xmax - tickInterval,
                        runtime.xmax,
                    ];

                    if (
                        elem.title !== Messages.strSystemCPUUsage &&
                        elem.title !== Messages.strQueryCacheEfficiency &&
                        elem.title !== Messages.strSystemMemory &&
                        elem.title !== Messages.strSystemSwap
                    ) {
                        elem.chart.axes.yaxis.max = Math.ceil(
                            elem.maxYLabel * 1.1,
                        );
                        elem.chart.axes.yaxis.tickInterval = Math.ceil(
                            (elem.maxYLabel * 1.1) / 5,
                        );
                    } else if (
                        elem.title === Messages.strSystemMemory ||
                        elem.title === Messages.strSystemSwap
                    ) {
                        elem.chart.axes.yaxis.max =
                            Math.ceil((total * 1.1) / 100) * 100;
                        elem.chart.axes.yaxis.tickInterval = Math.ceil(
                            (total * 1.1) / 5,
                        );
                    }
                    i++;

                    if (runtime.redrawCharts) {
                        elem.chart.replot();
                    }
                });

                oldChartData = chartData;

                runtime.refreshTimeout = setTimeout(
                    refreshChartGrid,
                    monitorSettings.gridRefresh,
                );
            },
        );
    }

    /* Function to get highest plotted point's y label, to scale the chart,
     * TODO: make jqplot's autoscale:true work here
     */
    function getMaxYLabel(dataValues) {
        var maxY = dataValues[0][1];
        $.each(dataValues, function (k, v) {
            maxY = v[1] > maxY ? v[1] : maxY;
        });
        return maxY;
    }

    function chartValueTransform(name, cur, prev) {
        switch (name) {
            case "cpu-linux":
                if (prev === null) {
                    return undefined;
                }
                var newCur = cur[0];
                var newPrev = prev[0];

                var diffTotal =
                    newCur.busy + newCur.idle - (newPrev.busy + newPrev.idle);
                var diffIdle = newCur.idle - newPrev.idle;
                return (100 * (diffTotal - diffIdle)) / diffTotal;
            case "qce":
                if (prev === null) {
                    return undefined;
                }
                var diffQHits = cur[0].value - prev[0].value;
                if (cur[1].value - prev[1].value === 0) {
                    return 0;
                }

                return (
                    (diffQHits / (cur[1].value - prev[1].value + diffQHits)) *
                    100
                );
            case "qcu":
                if (cur[1].value === 0) {
                    return 0;
                }
                return 100 - (cur[0].value / cur[1].value) * 100;
        }
        return undefined;
    }

    /* Build list of nodes that need to be retrieved from server.
     * It creates something like a stripped down version of the runtime.charts object.
     */
    function buildRequiredDataList() {
        runtime.dataList = {};
        var chartID = 0;
        $.each(runtime.charts, function (key, chart) {
            runtime.dataList[chartID] = [];
            for (var i = 0, l = chart.nodes.length; i < l; i++) {
                runtime.dataList[chartID][i] = chart.nodes[i].dataPoints;
            }
            runtime.charts[key].chartID = chartID;
            chartID++;
        });
    }

    function loadLogStatistics(opts) {
        var logRequest = null;

        if (!opts.removeVariables) {
            opts.removeVariables = false;
        }
        if (!opts.limitTypes) {
            opts.limitTypes = false;
        }

        $("#emptyDialog").dialog({
            classes: {
                "ui-dialog-titlebar-close": "btn-close",
            },
            title: Messages.strAnalysingLogsTitle,
        });
        $("#emptyDialog").html(
            Messages.strAnalysingLogs +
                ' <img class="ajaxIcon" src="' +
                themeImagePath +
                'ajax_clock_small.gif" alt="">',
        );

        var dlgBtns = {
            [Messages.strCancelRequest]: {
                text: Messages.strCancelRequest,
                class: "btn btn-primary",
            },
        };

        dlgBtns[Messages.strCancelRequest].click = function () {
            if (logRequest !== null) {
                logRequest.abort();
            }

            $(this).dialog("close");
        };

        $("#emptyDialog").dialog({
            classes: {
                "ui-dialog-titlebar-close": "btn-close",
            },
            width: "auto",
            height: "auto",
            buttons: dlgBtns,
        });

        var url = "index.php?route=/server/status/monitor/slow-log";
        if (opts.src === "general") {
            url = "index.php?route=/server/status/monitor/general-log";
        }
        logRequest = $.post(
            url,
            {
                ajax_request: true,
                time_start: Math.round(opts.start / 1000),
                time_end: Math.round(opts.end / 1000),
                removeVariables: opts.removeVariables,
                limitTypes: opts.limitTypes,
                server: CommonParams.get("server"),
            },
            function (data) {
                var logData;
                var dlgBtns = {
                    [Messages.strClose]: {
                        text: Messages.strClose,
                        class: "btn btn-primary",
                    },
                };
                if (typeof data !== "undefined" && data.success === true) {
                    logData = data.message;
                } else {
                    return serverResponseError();
                }

                if (logData.rows.length === 0) {
                    $("#emptyDialog").dialog({
                        classes: {
                            "ui-dialog-titlebar-close": "btn-close",
                        },
                        title: Messages.strNoDataFoundTitle,
                    });
                    $("#emptyDialog").html(
                        "<p>" + Messages.strNoDataFound + "</p>",
                    );

                    dlgBtns[Messages.strClose].click = function () {
                        $(this).dialog("close");
                    };

                    $("#emptyDialog").dialog("option", "buttons", dlgBtns);
                    return;
                }

                runtime.logDataCols = buildLogTable(
                    logData,
                    opts.removeVariables,
                );

                $("#emptyDialog").dialog({
                    classes: {
                        "ui-dialog-titlebar-close": "btn-close",
                    },
                    title: Messages.strLoadingLogs,
                });
                $("#emptyDialog").html(
                    "<p>" + Messages.strLogDataLoaded + "</p>",
                );
                $.each(logData.sum, function (key, value) {
                    var newKey =
                        key.charAt(0).toUpperCase() +
                        key.slice(1).toLowerCase();
                    if (newKey === "Total") {
                        newKey = "<b>" + newKey + "</b>";
                    }
                    $("#emptyDialog").append(newKey + ": " + value + "<br>");
                });

                if (logData.numRows > 12) {
                    $("#logTable").prepend(
                        '<fieldset class="pma-fieldset" id="logDataFilter">' +
                            "    <legend>" +
                            Messages.strFiltersForLogTable +
                            "</legend>" +
                            '    <div class="formelement">' +
                            '        <label for="filterQueryText">' +
                            Messages.strFilterByWordRegexp +
                            "</label>" +
                            '        <input name="filterQueryText" type="text" id="filterQueryText">' +
                            "    </div>" +
                            (logData.numRows > 250
                                ? ' <div class="formelement"><button class="btn btn-secondary" name="startFilterQueryText" id="startFilterQueryText">' +
                                  Messages.strFilter +
                                  "</button></div>"
                                : "") +
                            '    <div class="formelement">' +
                            '       <input type="checkbox" id="noWHEREData" name="noWHEREData" value="1"> ' +
                            '       <label for="noWHEREData"> ' +
                            Messages.strIgnoreWhereAndGroup +
                            "</label>" +
                            "   </div" +
                            "</fieldset>",
                    );

                    $("#noWHEREData").on("change", function () {
                        filterQueries(true);
                    });

                    if (logData.numRows > 250) {
                        $("#startFilterQueryText").on("click", filterQueries);
                    } else {
                        $("#filterQueryText").on("keyup", filterQueries);
                    }
                }

                dlgBtns[Messages.strJumpToTable] = {
                    text: Messages.strJumpToTable,
                    class: "btn btn-secondary",
                    click: function () {
                        $(this).dialog("close");
                        $(document).scrollTop($("#logTable").offset().top);
                    },
                };

                $("#emptyDialog").dialog("option", "buttons", dlgBtns);
            },
        );

        /**
         * Handles the actions performed when the user uses any of the
         * log table filters which are the filter by name and grouping
         * with ignoring data in WHERE clauses
         *
         * @param {boolean} varFilterChange Should be true when the users enabled or disabled
         *                to group queries ignoring data in WHERE clauses
         */
        function filterQueries(varFilterChange) {
            var textFilter;
            var val = $("#filterQueryText").val();

            if (val.length === 0) {
                textFilter = null;
            } else {
                try {
                    textFilter = new RegExp(val, "i");
                    $("#filterQueryText").removeClass("error");
                } catch (e) {
                    if (e instanceof SyntaxError) {
                        $("#filterQueryText").addClass("error");
                        textFilter = null;
                    }
                }
            }

            var rowSum = 0;
            var totalSum = 0;
            var i = 0;
            var q;
            var noVars = $("#noWHEREData").prop("checked");
            var equalsFilter = /([^=]+)=(\d+|(('|"|).*?[^\\])\4((\s+)|$))/gi;
            var functionFilter = /([a-z0-9_]+)\(.+?\)/gi;
            var filteredQueries = {};
            var filteredQueriesLines = {};
            var hide = false;
            var rowData;
            var queryColumnName =
                runtime.logDataCols[runtime.logDataCols.length - 2];
            var sumColumnName =
                runtime.logDataCols[runtime.logDataCols.length - 1];
            var isSlowLog = opts.src === "slow";
            var columnSums = {};
            var countRow = function (query, row) {
                var cells = row.match(/<td>(.*?)<\/td>/gi);
                if (!columnSums[query]) {
                    columnSums[query] = [0, 0, 0, 0];
                }
                columnSums[query][0] += timeToSec(
                    cells[2].replace(/(<td>|<\/td>)/gi, ""),
                );
                columnSums[query][1] += timeToSec(
                    cells[3].replace(/(<td>|<\/td>)/gi, ""),
                );
                columnSums[query][2] += parseInt(
                    cells[4].replace(/(<td>|<\/td>)/gi, ""),
                    10,
                );
                columnSums[query][3] += parseInt(
                    cells[5].replace(/(<td>|<\/td>)/gi, ""),
                    10,
                );
            };
            $("#logTable")
                .find("table tbody tr td.queryCell")
                .each(function () {
                    var $t = $(this);
                    if (varFilterChange && $t.html().match(/^SELECT/i)) {
                        if (noVars) {
                            q = $t
                                .text()
                                .replace(equalsFilter, "$1=...$6")
                                .trim();
                            q = q.replace(functionFilter, " $1(...)");
                            if (filteredQueries[q]) {
                                filteredQueries[q] += parseInt(
                                    $t.next().text(),
                                    10,
                                );
                                totalSum += parseInt($t.next().text(), 10);
                                hide = true;
                            } else {
                                filteredQueries[q] = parseInt(
                                    $t.next().text(),
                                    10,
                                );
                                filteredQueriesLines[q] = i;
                                $t.text(q);
                            }
                            if (isSlowLog) {
                                countRow(q, $t.parent().html());
                            }
                        } else {
                            rowData = $t.parent().data("query");
                            $t.text(rowData[queryColumnName]);
                            $t.next().text(rowData[sumColumnName]);
                            if (isSlowLog) {
                                $t.parent()
                                    .children("td")
                                    .eq(2)
                                    .text(rowData.query_time);
                                $t.parent()
                                    .children("td")
                                    .eq(3)
                                    .text(rowData.lock_time);
                                $t.parent()
                                    .children("td")
                                    .eq(4)
                                    .text(rowData.rows_sent);
                                $t.parent()
                                    .children("td")
                                    .eq(5)
                                    .text(rowData.rows_examined);
                            }
                        }
                    }
                    if (
                        !hide &&
                        textFilter !== null &&
                        !textFilter.exec($t.text())
                    ) {
                        hide = true;
                    }
                    if (hide) {
                        $t.parent().css("display", "none");
                    } else {
                        totalSum += parseInt($t.next().text(), 10);
                        rowSum++;
                        $t.parent().css("display", "");
                    }

                    hide = false;
                    i++;
                });
            if (varFilterChange) {
                if (noVars) {
                    var numCol;
                    var row;
                    var $table = $("#logTable").find("table tbody");
                    $.each(filteredQueriesLines, function (key, value) {
                        if (filteredQueries[key] <= 1) {
                            return;
                        }

                        row = $table.children("tr").eq(value);
                        numCol = row
                            .children()
                            .eq(runtime.logDataCols.length - 1);
                        numCol.text(filteredQueries[key]);

                        if (isSlowLog) {
                            row.children("td")
                                .eq(2)
                                .text(secToTime(columnSums[key][0]));
                            row.children("td")
                                .eq(3)
                                .text(secToTime(columnSums[key][1]));
                            row.children("td").eq(4).text(columnSums[key][2]);
                            row.children("td").eq(5).text(columnSums[key][3]);
                        }
                    });
                }

                $("#logTable").find("table").trigger("update");
                setTimeout(function () {
                    $("#logTable")
                        .find("table")
                        .trigger("sorton", [
                            [[runtime.logDataCols.length - 1, 1]],
                        ]);
                }, 0);
            }
            $("#logTable")
                .find("table tfoot tr")
                .html(
                    '<th colspan="' +
                        (runtime.logDataCols.length - 1) +
                        '">' +
                        Messages.strSumRows +
                        " " +
                        rowSum +
                        '<span class="float-end">' +
                        Messages.strTotal +
                        '</span></th><th class="text-end">' +
                        totalSum +
                        "</th>",
                );
        }
    }

    function timeToSec(timeStr) {
        var time = timeStr.split(":");
        return (
            parseInt(time[0], 10) * 3600 +
            parseInt(time[1], 10) * 60 +
            parseInt(time[2], 10)
        );
    }

    function secToTime(timeInt) {
        var time = timeInt;
        var hours = Math.floor(time / 3600);
        time -= hours * 3600;
        var minutes = Math.floor(time / 60);
        time -= minutes * 60;

        if (hours < 10) {
            hours = "0" + hours;
        }
        if (minutes < 10) {
            minutes = "0" + minutes;
        }
        if (time < 10) {
            time = "0" + time;
        }

        return hours + ":" + minutes + ":" + time;
    }

    function buildLogTable(data, groupInserts) {
        var rows = data.rows;
        var cols = [];
        var $table = $(
            '<table class="table table-striped table-hover align-middle sortable"></table>',
        );
        var $tBody;
        var $tRow;
        var $tCell;

        $("#logTable").html($table);

        var tempPushKey = function (key) {
            cols.push(key);
        };

        var formatValue = function (name, value) {
            if (name === "user_host") {
                return value.replace(/(\[.*?\])+/g, "");
            }
            return Functions.escapeHtml(value);
        };

        for (var i = 0, l = rows.length; i < l; i++) {
            if (i === 0) {
                $.each(rows[0], tempPushKey);
                $table.append(
                    "<thead>" +
                        '<tr><th class="text-nowrap">' +
                        cols.join('</th><th class="text-nowrap">') +
                        "</th></tr>" +
                        "</thead>",
                );

                $table.append(($tBody = $("<tbody></tbody>")));
            }

            $tBody.append(($tRow = $('<tr class="noclick"></tr>')));
            for (var j = 0, ll = cols.length; j < ll; j++) {
                if (
                    j === cols.length - 2 &&
                    rows[i][cols[j]].match(/^SELECT/i)
                ) {
                    $tRow.append(
                        ($tCell = $(
                            '<td class="linkElem queryCell">' +
                                formatValue(cols[j], rows[i][cols[j]]) +
                                "</td>",
                        )),
                    );
                    $tCell.on("click", openQueryAnalyzer);
                } else {
                    $tRow.append(
                        "<td>" +
                            formatValue(cols[j], rows[i][cols[j]]) +
                            "</td>",
                    );
                }

                $tRow.data("query", rows[i]);
            }
        }

        $table.append(
            "<tfoot>" +
                '<tr><th colspan="' +
                (cols.length - 1) +
                '">' +
                Messages.strSumRows +
                " " +
                data.numRows +
                '<span class="float-end">' +
                Messages.strTotal +
                '</span></th><th class="text-end">' +
                data.sum.TOTAL +
                "</th></tr></tfoot>",
        );
        const amountColumn = $("#logTable")
            .find("tr")
            .first()
            .find("th")
            .last();
        if (amountColumn.text().indexOf("#") > -1) {
            amountColumn.append("&nbsp;" + Functions.getImage("b_help"));

            let tooltipContent = Messages.strCountColumnExplanation;
            if (groupInserts) {
                tooltipContent +=
                    "<br>" + Messages.strMoreCountColumnExplanation;
            }

            Functions.tooltip(amountColumn, "th", tooltipContent);
        }

        $("#logTable")
            .find("table")
            .tablesorter({
                sortList: [[cols.length - 1, 1]],
                widgets: ["fast-zebra"],
            });

        $("#logTable")
            .find("table thead th")
            .append('<div class="sorticon"></div>');

        return cols;
    }

    function openQueryAnalyzer() {
        var rowData = $(this).parent().data("query");
        var query = rowData.argument || rowData.sql_text;

        if (codeMirrorEditor) {
            codeMirrorEditor.setValue(query);
            setTimeout(function () {
                codeMirrorEditor.refresh();
            }, 50);
        } else {
            $("#sqlquery").val(query);
        }

        var profilingChart = null;
        var dlgBtns = {
            [Messages.strAnalyzeQuery]: {
                text: Messages.strAnalyzeQuery,
                class: "btn btn-primary",
            },
            [Messages.strClose]: {
                text: Messages.strClose,
                class: "btn btn-secondary",
            },
        };

        dlgBtns[Messages.strAnalyzeQuery].click = function () {
            profilingChart = loadQueryAnalysis(rowData);
        };
        dlgBtns[Messages.strClose].click = function () {
            $(this).dialog("close");
        };

        $("#queryAnalyzerDialog").dialog({
            classes: {
                "ui-dialog-titlebar-close": "btn-close",
            },
            width: "auto",
            height: "auto",
            resizable: false,
            buttons: dlgBtns,
            close: function () {
                if (profilingChart !== null) {
                    profilingChart.destroy();
                }
                $("#queryAnalyzerDialog").find("div.placeHolder").html("");
                if (codeMirrorEditor) {
                    codeMirrorEditor.setValue("");
                } else {
                    $("#sqlquery").val("");
                }
            },
        });
    }

    function loadQueryAnalysis(rowData) {
        var db = rowData.db || "";
        var profilingChart = null;

        $("#queryAnalyzerDialog")
            .find("div.placeHolder")
            .html(
                Messages.strAnalyzing +
                    ' <img class="ajaxIcon" src="' +
                    themeImagePath +
                    'ajax_clock_small.gif" alt="">',
            );

        $.post(
            "index.php?route=/server/status/monitor/query",
            {
                ajax_request: true,
                query: codeMirrorEditor
                    ? codeMirrorEditor.getValue()
                    : $("#sqlquery").val(),
                database: db,
                server: CommonParams.get("server"),
            },
            function (responseData) {
                var data = responseData;
                var i;
                var l;
                if (typeof data !== "undefined" && data.success === true) {
                    data = data.message;
                }
                if (data.error) {
                    if (
                        data.error.indexOf("1146") !== -1 ||
                        data.error.indexOf("1046") !== -1
                    ) {
                        data.error = Messages.strServerLogError;
                    }
                    $("#queryAnalyzerDialog")
                        .find("div.placeHolder")
                        .html(
                            '<div class="alert alert-danger" role="alert">' +
                                data.error +
                                "</div>",
                        );
                    return;
                }
                var totalTime = 0;
                $("#queryAnalyzerDialog")
                    .find("div.placeHolder")
                    .html(
                        '<table class="table table-borderless"><tr><td class="explain"></td><td class="chart"></td></tr></table>',
                    );

                var explain =
                    "<b>" +
                    Messages.strExplainOutput +
                    "</b> " +
                    $("#explain_docu").html();
                if (data.explain.length > 1) {
                    explain += " (";
                    for (i = 0; i < data.explain.length; i++) {
                        if (i > 0) {
                            explain += ", ";
                        }
                        explain +=
                            '<a href="#showExplain-' + i + '">' + i + "</a>";
                    }
                    explain += ")";
                }
                explain += "<p></p>";

                var tempExplain = function (key, value) {
                    var newValue =
                        value === null ? "null" : Functions.escapeHtml(value);

                    if (key === "type" && newValue.toLowerCase() === "all") {
                        newValue =
                            '<span class="text-danger">' + newValue + "</span>";
                    }
                    if (key === "Extra") {
                        newValue = newValue.replace(
                            /(using (temporary|filesort))/gi,
                            '<span class="text-danger">$1</span>',
                        );
                    }
                    explain += key + ": " + newValue + "<br>";
                };

                for (i = 0, l = data.explain.length; i < l; i++) {
                    explain +=
                        '<div class="explain-' +
                        i +
                        '"' +
                        (i > 0 ? 'style="display:none;"' : "") +
                        ">";
                    $.each(data.explain[i], tempExplain);
                    explain += "</div>";
                }

                explain +=
                    "<p><b>" +
                    Messages.strAffectedRows +
                    "</b> " +
                    data.affectedRows;

                $("#queryAnalyzerDialog")
                    .find("div.placeHolder td.explain")
                    .append(explain);

                $("#queryAnalyzerDialog")
                    .find('div.placeHolder a[href*="#showExplain"]')
                    .on("click", function () {
                        var id = $(this).attr("href").split("-")[1];
                        $(this).parent().find('div[class*="explain"]').hide();
                        $(this)
                            .parent()
                            .find('div[class*="explain-' + id + '"]')
                            .show();
                    });

                if (data.profiling) {
                    var chartData = [];
                    var numberTable =
                        '<table class="table table-sm table-striped table-hover w-auto queryNums"><thead><tr><th>' +
                        Messages.strStatus +
                        "</th><th>" +
                        Messages.strTime +
                        "</th></tr></thead><tbody>";
                    var duration;
                    var otherTime = 0;

                    for (i = 0, l = data.profiling.length; i < l; i++) {
                        duration = parseFloat(data.profiling[i].duration);

                        totalTime += duration;

                        numberTable +=
                            "<tr><td>" +
                            data.profiling[i].state +
                            " </td><td> " +
                            Functions.prettyProfilingNum(duration, 2) +
                            "</td></tr>";
                    }
                    for (i = 0, l = data.profiling.length; i < l; i++) {
                        duration = parseFloat(data.profiling[i].duration);

                        if (duration / totalTime > 0.02) {
                            chartData.push([
                                Functions.prettyProfilingNum(duration, 2) +
                                    " " +
                                    data.profiling[i].state,
                                duration,
                            ]);
                        } else {
                            otherTime += duration;
                        }
                    }

                    if (otherTime > 0) {
                        chartData.push([
                            Functions.prettyProfilingNum(otherTime, 2) +
                                " " +
                                Messages.strOther,
                            otherTime,
                        ]);
                    }

                    numberTable +=
                        "<tr><td><b>" +
                        Messages.strTotalTime +
                        "</b></td><td>" +
                        Functions.prettyProfilingNum(totalTime, 2) +
                        "</td></tr>";
                    numberTable += "</tbody></table>";

                    $("#queryAnalyzerDialog")
                        .find("div.placeHolder td.chart")
                        .append(
                            "<b>" +
                                Messages.strProfilingResults +
                                " " +
                                $("#profiling_docu").html() +
                                "</b> " +
                                '(<a href="#showNums">' +
                                Messages.strTable +
                                '</a>, <a href="#showChart">' +
                                Messages.strChart +
                                "</a>)<br>" +
                                numberTable +
                                ' <div id="queryProfiling"></div>',
                        );

                    $("#queryAnalyzerDialog")
                        .find('div.placeHolder a[href="#showNums"]')
                        .on("click", function () {
                            $("#queryAnalyzerDialog")
                                .find("#queryProfiling")
                                .hide();
                            $("#queryAnalyzerDialog")
                                .find("table.queryNums")
                                .show();
                            return false;
                        });

                    $("#queryAnalyzerDialog")
                        .find('div.placeHolder a[href="#showChart"]')
                        .on("click", function () {
                            $("#queryAnalyzerDialog")
                                .find("#queryProfiling")
                                .show();
                            $("#queryAnalyzerDialog")
                                .find("table.queryNums")
                                .hide();
                            return false;
                        });

                    profilingChart = Functions.createProfilingChart(
                        "queryProfiling",
                        chartData,
                    );
                }
            },
        );
        return profilingChart;
    }

    function saveMonitor() {
        var gridCopy = {};

        $.each(runtime.charts, function (key, elem) {
            gridCopy[key] = {};
            gridCopy[key].nodes = elem.nodes;
            gridCopy[key].settings = elem.settings;
            gridCopy[key].title = elem.title;
            gridCopy[key].series = elem.series;
            gridCopy[key].maxYLabel = elem.maxYLabel;
        });

        if (isStorageSupported("localStorage")) {
            window.localStorage.monitorCharts = JSON.stringify(gridCopy);
            window.localStorage.monitorSettings =
                JSON.stringify(monitorSettings);
            window.localStorage.monitorVersion = monitorProtocolVersion;
        }

        $('a[href="#clearMonitorConfig"]').show();
    }
});
AJAX.registerOnload("server/status/monitor.js", function () {
    $('a[href="#pauseCharts"]').trigger("click");
});
