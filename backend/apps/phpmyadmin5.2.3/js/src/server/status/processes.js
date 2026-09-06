/**
 * Server Status Processes
 *
 * @package PhpMyAdmin
 */
var processList = {
    autoRefresh: false,
    refreshRequest: null,
    refreshTimeout: null,
    refreshInterval: null,
    refreshUrl: null,

    /**
     * Handles killing of a process
     *
     * @return {void}
     */
    init: function () {
        processList.setRefreshLabel();
        if (processList.refreshUrl === null) {
            processList.refreshUrl =
                "index.php?route=/server/status/processes/refresh";
        }
        if (processList.refreshInterval === null) {
            processList.refreshInterval = $("#id_refreshRate").val();
        } else {
            $("#id_refreshRate").val(processList.refreshInterval);
        }
    },

    /**
     * Handles killing of a process
     *
     * @param {object} event the event object
     *
     * @return {void}
     */
    killProcessHandler: function (event) {
        event.preventDefault();
        var argSep = CommonParams.get("arg_separator");
        var params = $(this).getPostData();
        params +=
            argSep +
            "ajax_request=1" +
            argSep +
            "server=" +
            CommonParams.get("server");
        var $tr = $(this).closest("tr");
        $.post(
            $(this).attr("href"),
            params,
            function (data) {
                if (data.hasOwnProperty("success") && data.success) {
                    $tr.remove();
                    var $tableProcessListTr =
                        $("#tableprocesslist").find("> tbody > tr");
                    $tableProcessListTr.each(function (index) {
                        if (index >= 0 && index % 2 === 0) {
                            $(this).removeClass("odd").addClass("even");
                        } else if (index >= 0 && index % 2 !== 0) {
                            $(this).removeClass("even").addClass("odd");
                        }
                    });
                    Functions.ajaxShowMessage(data.message, false);
                } else {
                    Functions.ajaxShowMessage(data.error, false);
                }
            },
            "json",
        );
    },

    /**
     * Handles Auto Refreshing
     * @return {void}
     */
    refresh: function () {
        processList.abortRefresh();
        if (processList.autoRefresh) {
            processList.refreshUrl =
                "index.php?route=/server/status/processes/refresh";
            var interval = parseInt(processList.refreshInterval, 10) * 1000;
            var urlParams = processList.getUrlParams();
            processList.refreshRequest = $.post(
                processList.refreshUrl,
                urlParams,
                function (data) {
                    if (data.hasOwnProperty("success") && data.success) {
                        var $newTable = $(data.message);
                        $("#tableprocesslist").html($newTable.html());
                        Functions.highlightSql($("#tableprocesslist"));
                    }
                    processList.refreshTimeout = setTimeout(
                        processList.refresh,
                        interval,
                    );
                },
            );
        }
    },

    /**
     * Stop current request and clears timeout
     *
     * @return {void}
     */
    abortRefresh: function () {
        if (processList.refreshRequest !== null) {
            processList.refreshRequest.abort();
            processList.refreshRequest = null;
        }
        clearTimeout(processList.refreshTimeout);
    },

    /**
     * Set label of refresh button
     * change between play & pause
     *
     * @return {void}
     */
    setRefreshLabel: function () {
        var img = "play";
        var label = Messages.strStartRefresh;
        if (processList.autoRefresh) {
            img = "pause";
            label = Messages.strStopRefresh;
            processList.refresh();
        }
        $("a#toggleRefresh").html(
            Functions.getImage(img) + Functions.escapeHtml(label),
        );
    },

    /**
     * Return the Url Parameters
     * for autorefresh request,
     * includes showExecuting if the filter is checked
     *
     * @return {object} urlParams - url parameters with autoRefresh request
     */
    getUrlParams: function () {
        var urlParams = {
            server: CommonParams.get("server"),
            ajax_request: true,
            refresh: true,
            full: $('input[name="full"]').val(),
            order_by_field: $('input[name="order_by_field"]').val(),
            column_name: $('input[name="column_name"]').val(),
            sort_order: $('input[name="sort_order"]').val(),
        };
        if ($("#showExecuting").is(":checked")) {
            urlParams.showExecuting = true;
            return urlParams;
        }
        return urlParams;
    },
};

AJAX.registerOnload("server/status/processes.js", function () {
    processList.init();
    $("#tableprocesslist").on(
        "click",
        "a.kill_process",
        processList.killProcessHandler,
    );
    $("a#toggleRefresh").on("click", function (event) {
        event.preventDefault();
        processList.autoRefresh = !processList.autoRefresh;
        processList.setRefreshLabel();
    });
    $("#id_refreshRate").on("change", function () {
        processList.refreshInterval = $(this).val();
        processList.refresh();
    });
    $("#tableprocesslist").on("click", "thead a", function () {
        processList.refreshUrl = $(this).attr("href");
    });
});

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown("server/status/processes.js", function () {
    $("#tableprocesslist").off("click", "a.kill_process");
    $("a#toggleRefresh").off("click");
    $("#id_refreshRate").off("change");
    $("#tableprocesslist").off("click", "thead a");
    processList.abortRefresh();
});
