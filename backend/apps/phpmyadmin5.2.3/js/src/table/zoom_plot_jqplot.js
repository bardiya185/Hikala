/**
 * @fileoverview JavaScript functions used on /table/search
 *
 * @requires    jQuery
 * @requires    js/functions.js
 **/

// js/table/change.js

/**
 *  Display Help/Info
 * @return {false}
 **/
function displayHelp() {
    var modal = $("#helpModal");
    modal.modal("show");
    modal.find(".modal-body").first().html(Messages.strDisplayHelp);
    $("#helpModalLabel").first().html(Messages.strHelpTitle);
    return false;
}

/**
 * Extend the array object for max function
 * @param {number[]} array
 * @return {int}
 **/
Array.max = function (array) {
    return Math.max.apply(Math, array);
};

/**
 * Extend the array object for min function
 * @param {number[]} array
 * @return {int}
 **/
Array.min = function (array) {
    return Math.min.apply(Math, array);
};

/**
 * Checks if a string contains only numeric value
 * @param {string} n (to be checked)
 * @return {bool}
 **/
function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

/**
 ** Checks if an object is empty
 * @param {object} obj (to be checked)
 * @return {bool}
 **/
function isEmpty(obj) {
    var name;
    for (name in obj) {
        return false;
    }
    return true;
}

/**
 * Converts a date/time into timestamp
 * @param {string} val Date
 * @param {string} type Field type(datetime/timestamp/time/date)
 * @return {any} A value
 **/
function getTimeStamp(val, type) {
    if (
        type.toString().search(/datetime/i) !== -1 ||
        type.toString().search(/timestamp/i) !== -1
    ) {
        return $.datepicker.parseDateTime("yy-mm-dd", "HH:mm:ss", val);
    } else if (type.toString().search(/time/i) !== -1) {
        return $.datepicker.parseDateTime(
            "yy-mm-dd",
            "HH:mm:ss",
            "1970-01-01 " + val,
        );
    } else if (type.toString().search(/date/i) !== -1) {
        return $.datepicker.parseDate("yy-mm-dd", val);
    }
}

/**
 * Classifies the field type into numeric,timeseries or text
 * @param {object} field field type (as in database structure)
 * @return {'text'|'numeric'|'time'}
 **/
function getType(field) {
    if (
        field.toString().search(/int/i) !== -1 ||
        field.toString().search(/decimal/i) !== -1 ||
        field.toString().search(/year/i) !== -1
    ) {
        return "numeric";
    } else if (
        field.toString().search(/time/i) !== -1 ||
        field.toString().search(/date/i) !== -1
    ) {
        return "time";
    } else {
        return "text";
    }
}

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown("table/zoom_plot_jqplot.js", function () {
    $("#tableid_0").off("change");
    $("#tableid_1").off("change");
    $("#tableid_2").off("change");
    $("#tableid_3").off("change");
    $("#inputFormSubmitId").off("click");
    $("#togglesearchformlink").off("click");
    $(document).off("keydown", "#dataDisplay :input");
    $("button.button-reset").off("click");
    $("div#resizer").off("resizestop");
    $("div#querychart").off("jqplotDataClick");
});

AJAX.registerOnload("table/zoom_plot_jqplot.js", function () {
    var currentChart = null;
    var searchedDataKey = null;
    var xLabel = $("#tableid_0").val();
    var yLabel = $("#tableid_1").val();
    var xType = $("#types_0").val();
    var yType = $("#types_1").val();
    var dataLabel = $("#dataLabel").val();
    var searchedData;
    try {
        searchedData = JSON.parse($("#querydata").html());
    } catch (err) {
        searchedData = null;
    }
    var comparisonOperatorOnChange = function () {
        var tableRows = $("#inputSection select.column-operator");
        $.each(tableRows, function (index, item) {
            $(item).on("change", function () {
                changeValueFieldType(this, index);
                verifyAfterSearchFieldChange(index, "#zoom_search_form");
            });
        });
    };

    /**
     ** Input form submit on field change
     **/
    $("#tableid_0").on("change", function () {
        $.post(
            "index.php?route=/table/zoom-search",
            {
                ajax_request: true,
                change_tbl_info: true,
                server: CommonParams.get("server"),
                db: CommonParams.get("db"),
                table: CommonParams.get("table"),
                field: $("#tableid_0").val(),
                it: 0,
            },
            function (data) {
                $("#tableFieldsId")
                    .find("tr")
                    .eq(1)
                    .find("td")
                    .eq(0)
                    .html(data.field_type);
                $("#tableFieldsId")
                    .find("tr")
                    .eq(1)
                    .find("td")
                    .eq(1)
                    .html(data.field_collation);
                $("#tableFieldsId")
                    .find("tr")
                    .eq(1)
                    .find("td")
                    .eq(2)
                    .html(data.field_operators);
                $("#tableFieldsId")
                    .find("tr")
                    .eq(1)
                    .find("td")
                    .eq(3)
                    .html(data.field_value);
                xLabel = $("#tableid_0").val();
                $("#types_0").val(data.field_type);
                xType = data.field_type;
                $("#collations_0").val(data.field_collations);
                comparisonOperatorOnChange();
                Functions.addDateTimePicker();
            },
        );
    });
    $("#tableid_1").on("change", function () {
        $.post(
            "index.php?route=/table/zoom-search",
            {
                ajax_request: true,
                change_tbl_info: true,
                server: CommonParams.get("server"),
                db: CommonParams.get("db"),
                table: CommonParams.get("table"),
                field: $("#tableid_1").val(),
                it: 1,
            },
            function (data) {
                $("#tableFieldsId")
                    .find("tr")
                    .eq(2)
                    .find("td")
                    .eq(0)
                    .html(data.field_type);
                $("#tableFieldsId")
                    .find("tr")
                    .eq(2)
                    .find("td")
                    .eq(1)
                    .html(data.field_collation);
                $("#tableFieldsId")
                    .find("tr")
                    .eq(2)
                    .find("td")
                    .eq(2)
                    .html(data.field_operators);
                $("#tableFieldsId")
                    .find("tr")
                    .eq(2)
                    .find("td")
                    .eq(3)
                    .html(data.field_value);
                yLabel = $("#tableid_1").val();
                $("#types_1").val(data.field_type);
                yType = data.field_type;
                $("#collations_1").val(data.field_collations);
                comparisonOperatorOnChange();
                Functions.addDateTimePicker();
            },
        );
    });

    $("#tableid_2").on("change", function () {
        $.post(
            "index.php?route=/table/zoom-search",
            {
                ajax_request: true,
                change_tbl_info: true,
                server: CommonParams.get("server"),
                db: CommonParams.get("db"),
                table: CommonParams.get("table"),
                field: $("#tableid_2").val(),
                it: 2,
            },
            function (data) {
                $("#tableFieldsId")
                    .find("tr")
                    .eq(4)
                    .find("td")
                    .eq(0)
                    .html(data.field_type);
                $("#tableFieldsId")
                    .find("tr")
                    .eq(4)
                    .find("td")
                    .eq(1)
                    .html(data.field_collation);
                $("#tableFieldsId")
                    .find("tr")
                    .eq(4)
                    .find("td")
                    .eq(2)
                    .html(data.field_operators);
                $("#tableFieldsId")
                    .find("tr")
                    .eq(4)
                    .find("td")
                    .eq(3)
                    .html(data.field_value);
                $("#types_2").val(data.field_type);
                $("#collations_2").val(data.field_collations);
                comparisonOperatorOnChange();
                Functions.addDateTimePicker();
            },
        );
    });

    $("#tableid_3").on("change", function () {
        $.post(
            "index.php?route=/table/zoom-search",
            {
                ajax_request: true,
                change_tbl_info: true,
                server: CommonParams.get("server"),
                db: CommonParams.get("db"),
                table: CommonParams.get("table"),
                field: $("#tableid_3").val(),
                it: 3,
            },
            function (data) {
                $("#tableFieldsId")
                    .find("tr")
                    .eq(5)
                    .find("td")
                    .eq(0)
                    .html(data.field_type);
                $("#tableFieldsId")
                    .find("tr")
                    .eq(5)
                    .find("td")
                    .eq(1)
                    .html(data.field_collation);
                $("#tableFieldsId")
                    .find("tr")
                    .eq(5)
                    .find("td")
                    .eq(2)
                    .html(data.field_operators);
                $("#tableFieldsId")
                    .find("tr")
                    .eq(5)
                    .find("td")
                    .eq(3)
                    .html(data.field_value);
                $("#types_3").val(data.field_type);
                $("#collations_3").val(data.field_collations);
                comparisonOperatorOnChange();
                Functions.addDateTimePicker();
            },
        );
    });

    /**
     * Input form validation
     **/
    $("#inputFormSubmitId").on("click", function () {
        if (
            $("#tableid_0").get(0).selectedIndex === 0 ||
            $("#tableid_1").get(0).selectedIndex === 0
        ) {
            Functions.ajaxShowMessage(Messages.strInputNull);
        } else if (xLabel === yLabel) {
            Functions.ajaxShowMessage(Messages.strSameInputs);
        }
    });

    /**
     ** Prepare a div containing a link, otherwise it's incorrectly displayed
     ** after a couple of clicks
     **/
    $('<div id="togglesearchformdiv"><a id="togglesearchformlink"></a></div>')
        .insertAfter("#zoom_search_form")
        .hide();

    $("#togglesearchformlink")
        .html(Messages.strShowSearchCriteria)
        .on("click", function () {
            var $link = $(this);
            $("#zoom_search_form").slideToggle();
            if ($link.text() === Messages.strHideSearchCriteria) {
                $link.text(Messages.strShowSearchCriteria);
            } else {
                $link.text(Messages.strHideSearchCriteria);
            }
            return false;
        });

    /**
     * Handle saving of a row in the editor
     */
    var dataPointSave = function () {
        var newValues = {}; // Stores the values changed from original
        var sqlTypes = {};
        var it = 0;
        var xChange = false;
        var yChange = false;
        var key;
        var tempGetVal = function () {
            return $(this).val();
        };
        for (key in selectedRow) {
            var oldVal = selectedRow[key];
            var newVal = $("#edit_fields_null_id_" + it).prop("checked")
                ? null
                : $("#edit_fieldID_" + it).val();
            if (newVal instanceof Array) {
                // when the column is of type SET
                newVal = $("#edit_fieldID_" + it)
                    .map(tempGetVal)
                    .get()
                    .join(",");
            }
            if (oldVal !== newVal) {
                selectedRow[key] = newVal;
                newValues[key] = newVal;
                if (key === xLabel) {
                    xChange = true;
                    searchedData[searchedDataKey][xLabel] = newVal;
                } else if (key === yLabel) {
                    yChange = true;
                    searchedData[searchedDataKey][yLabel] = newVal;
                }
            }
            var $input = $("#edit_fieldID_" + it);
            if ($input.hasClass("bit")) {
                sqlTypes[key] = "bit";
            } else {
                sqlTypes[key] = null;
            }
            it++;
        } // End data update
        if (xChange || yChange) {
            if (xChange) {
                xCord[searchedDataKey] = selectedRow[xLabel];
                if (xType === "numeric") {
                    series[0][searchedDataKey][0] = selectedRow[xLabel];
                } else if (xType === "time") {
                    series[0][searchedDataKey][0] = getTimeStamp(
                        selectedRow[xLabel],
                        $("#types_0").val(),
                    );
                } else {
                    series[0][searchedDataKey][0] = "";
                }
                currentChart.series[0].data = series[0];
                currentChart.replot();
            }
            if (yChange) {
                yCord[searchedDataKey] = selectedRow[yLabel];
                if (yType === "numeric") {
                    series[0][searchedDataKey][1] = selectedRow[yLabel];
                } else if (yType === "time") {
                    series[0][searchedDataKey][1] = getTimeStamp(
                        selectedRow[yLabel],
                        $("#types_1").val(),
                    );
                } else {
                    series[0][searchedDataKey][1] = "";
                }
                currentChart.series[0].data = series[0];
                currentChart.replot();
            }
        } // End plot update
        if (!isEmpty(newValues)) {
            var sqlQuery = "UPDATE `" + CommonParams.get("table") + "` SET ";
            for (key in newValues) {
                sqlQuery += "`" + key + "`=";
                var value = newValues[key];
                if (value === null) {
                    sqlQuery += "NULL, ";
                } else if (value.trim() === "") {
                    sqlQuery += "'', ";
                } else {
                    if (sqlTypes[key] !== null) {
                        if (sqlTypes[key] === "bit") {
                            sqlQuery += "b'" + value + "', ";
                        }
                    } else {
                        if (!isNumeric(value)) {
                            sqlQuery += "'" + value + "', ";
                        } else {
                            sqlQuery += value + ", ";
                        }
                    }
                }
            }
            sqlQuery = sqlQuery.substring(0, sqlQuery.length - 2);
            sqlQuery +=
                " WHERE " +
                Sql.urlDecode(searchedData[searchedDataKey].where_clause);

            $.post(
                "index.php?route=/sql",
                {
                    server: CommonParams.get("server"),
                    db: CommonParams.get("db"),
                    ajax_request: true,
                    sql_query: sqlQuery,
                    inline_edit: false,
                },
                function (data) {
                    if (typeof data !== "undefined" && data.success === true) {
                        $("#sqlqueryresultsouter").html(data.sql_query);
                        Functions.highlightSql($("#sqlqueryresultsouter"));
                    } else {
                        Functions.ajaxShowMessage(data.error, false);
                    }
                },
            ); // End $.post
        } // End database update
    };

    $("#dataPointSaveButton").on("click", function () {
        dataPointSave();
    });

    $("#dataPointModalLabel").first().html(Messages.strDataPointContent);

    /**
     * Attach Ajax event handlers for input fields
     * in the dialog. Used to submit the Ajax
     * request when the ENTER key is pressed.
     */
    $(document).on("keydown", "#dataDisplay :input", function (e) {
        if (e.which === 13) {
            // 13 is the ENTER key
            e.preventDefault();
            if (typeof dataPointSave === "function") {
                dataPointSave();
            }
        }
    });

    /*
     * Generate plot using jqplot
     */

    if (searchedData !== null) {
        $("#zoom_search_form").slideToggle().hide();
        $("#togglesearchformlink").text(Messages.strShowSearchCriteria);
        $("#togglesearchformdiv").show();
        var selectedRow;
        var series = [];
        var xCord = [];
        var yCord = [];
        var xVal;
        var yVal;
        var format;

        var options = {
            series: [{ showLine: false }],
            grid: {
                drawBorder: false,
                shadow: false,
                background: "rgba(0,0,0,0)",
            },
            axes: {
                xaxis: {
                    label: $("#tableid_0").val(),
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                },
                yaxis: {
                    label: $("#tableid_1").val(),
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                },
            },
            highlighter: {
                show: true,
                tooltipAxes: "y",
                yvalues: 2,
                formatString: '<span class="hide">%s</span>%s',
            },
            cursor: {
                show: true,
                zoom: true,
                showTooltip: false,
            },
        };
        if (dataLabel === "") {
            options.highlighter.show = false;
        }
        xType = getType(xType);
        yType = getType(yType);
        series[0] = [];

        if (xType === "time") {
            var originalXType = $("#types_0").val();
            if (originalXType === "date") {
                format = "%Y-%m-%d";
            }
            $.extend(options.axes.xaxis, {
                renderer: $.jqplot.DateAxisRenderer,
                tickOptions: {
                    formatString: format,
                },
            });
        }
        if (yType === "time") {
            var originalYType = $("#types_1").val();
            if (originalYType === "date") {
                format = "%Y-%m-%d";
            }
            $.extend(options.axes.yaxis, {
                renderer: $.jqplot.DateAxisRenderer,
                tickOptions: {
                    formatString: format,
                },
            });
        }

        $.each(searchedData, function (key, value) {
            if (xType === "numeric") {
                xVal = parseFloat(value[xLabel]);
            }
            if (xType === "time") {
                xVal = getTimeStamp(value[xLabel], originalXType);
            }
            if (yType === "numeric") {
                yVal = parseFloat(value[yLabel]);
            }
            if (yType === "time") {
                yVal = getTimeStamp(value[yLabel], originalYType);
            }
            series[0].push([
                xVal,
                yVal,
                value[dataLabel], // for highlighter
                value.where_clause, // for click on point
                key, // key from searchedData
                value.where_clause_sign,
            ]);
        });
        currentChart = $.jqplot("querychart", series, options);
        currentChart.resetZoom();

        $("button.button-reset").on("click", function (event) {
            event.preventDefault();
            currentChart.resetZoom();
        });

        $("div#resizer").resizable();
        $("div#resizer").on("resizestop", function () {
            $("div#querychart").height($("div#resizer").height() * 0.96);
            $("div#querychart").width($("div#resizer").width() * 0.96);
            currentChart.replot({ resetAxes: true });
        });

        $("div#querychart").on(
            "jqplotDataClick",
            function (event, seriesIndex, pointIndex, data) {
                searchedDataKey = data[4]; // key from searchedData (global)
                var fieldId = 0;
                var postParams = {
                    ajax_request: true,
                    get_data_row: true,
                    server: CommonParams.get("server"),
                    db: CommonParams.get("db"),
                    table: CommonParams.get("table"),
                    where_clause: data[3],
                    where_clause_sign: data[5],
                };

                $.post(
                    "index.php?route=/table/zoom-search",
                    postParams,
                    function (data) {
                        var key;
                        for (key in data.row_info) {
                            var $field = $("#edit_fieldID_" + fieldId);
                            var $fieldNull = $(
                                "#edit_fields_null_id_" + fieldId,
                            );
                            if (data.row_info[key] === null) {
                                $fieldNull.prop("checked", true);
                                $field.val("");
                            } else {
                                $fieldNull.prop("checked", false);
                                if ($field.attr("multiple")) {
                                    // when the column is of type SET
                                    $field.val(data.row_info[key].split(","));
                                } else {
                                    $field.val(data.row_info[key]);
                                }
                            }
                            fieldId++;
                        }
                        selectedRow = data.row_info;
                    },
                );

                $("#dataPointModal").modal("show");
            },
        );
    }

    $("#help_dialog").on("click", function () {
        displayHelp();
    });
});
