/**
 * @fileoverview JavaScript functions used on /table/search
 *
 * @requires    jQuery
 * @requires    js/functions.js
 */

// js/table/change.js
// js/gis_data_editor.js

var TableSelect = {};

/**
 * Checks if given data-type is numeric or date.
 *
 * @param {string} dataType Column data-type
 *
 * @return {boolean | string}
 */
TableSelect.checkIfDataTypeNumericOrDate = function (dataType) {
    var numericRegExp = new RegExp(
        "TINYINT|SMALLINT|MEDIUMINT|INT|BIGINT|DECIMAL|FLOAT|DOUBLE|REAL",
        "i",
    );
    var dateRegExp = new RegExp("DATETIME|DATE|TIMESTAMP|TIME|YEAR", "i");
    if (numericRegExp.test(dataType)) {
        return numericRegExp.exec(dataType)[0];
    }

    if (dateRegExp.test(dataType)) {
        return dateRegExp.exec(dataType)[0];
    }

    return false;
};

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown("table/select.js", function () {
    $("#togglesearchformlink").off("click");
    $(document).off("submit", "#tbl_search_form.ajax");
    $("select.geom_func").off("change");
    $(document).off("click", "span.open_search_gis_editor");
    $("body").off("change", 'select[name*="criteriaColumnOperators"]'); // Fix for bug #13778, changed 'click' to 'change'
});

AJAX.registerOnload("table/select.js", function () {
    /**
     * Prepare a div containing a link, otherwise it's incorrectly displayed
     * after a couple of clicks
     */
    $('<div id="togglesearchformdiv"><a id="togglesearchformlink"></a></div>')
        .insertAfter("#tbl_search_form")
        .hide();

    $("#togglesearchformlink")
        .html(Messages.strShowSearchCriteria)
        .on("click", function () {
            var $link = $(this);
            $("#tbl_search_form").slideToggle();
            if ($link.text() === Messages.strHideSearchCriteria) {
                $link.text(Messages.strShowSearchCriteria);
            } else {
                $link.text(Messages.strHideSearchCriteria);
            }
            return false;
        });

    var tableRows = $("#fieldset_table_qbe select.column-operator");
    $.each(tableRows, function (index, item) {
        $(item).on("change", function () {
            changeValueFieldType(this, index);
            verifyAfterSearchFieldChange(index, "#tbl_search_form");
        });
    });

    /**
     * Ajax event handler for Table search
     */
    $(document).on("submit", "#tbl_search_form.ajax", function (event) {
        var unaryFunctions = ["IS NULL", "IS NOT NULL", "= ''", "!= ''"];

        var geomUnaryFunctions = ["IsEmpty", "IsSimple", "IsRing", "IsClosed"];
        var $searchForm = $(this);
        event.preventDefault();
        $("#sqlqueryresultsouter").empty();
        var $msgbox = Functions.ajaxShowMessage(Messages.strSearching, false);

        Functions.prepareForAjaxRequest($searchForm);

        var values = {};
        $searchForm.find(":input").each(function () {
            var $input = $(this);
            if (
                $input.attr("type") === "checkbox" ||
                $input.attr("type") === "radio"
            ) {
                if ($input.is(":checked")) {
                    values[this.name] = $input.val();
                }
            } else {
                values[this.name] = $input.val();
            }
        });
        var columnCount = $('select[name="columnsToDisplay[]"] option').length;
        for (var a = 0; a < columnCount; a++) {
            if (
                $.inArray(
                    values["criteriaColumnOperators[" + a + "]"],
                    unaryFunctions,
                ) >= 0
            ) {
                continue;
            }

            if (
                values["geom_func[" + a + "]"] &&
                $.inArray(values["geom_func[" + a + "]"], geomUnaryFunctions) >=
                    0
            ) {
                continue;
            }

            if (
                values["criteriaValues[" + a + "]"] === "" ||
                values["criteriaValues[" + a + "]"] === null
            ) {
                delete values["criteriaValues[" + a + "]"];
                delete values["criteriaColumnOperators[" + a + "]"];
                delete values["criteriaColumnNames[" + a + "]"];
                delete values["criteriaColumnTypes[" + a + "]"];
                delete values["criteriaColumnCollations[" + a + "]"];
            }
        }
        if (values["columnsToDisplay[]"] !== null) {
            if (values["columnsToDisplay[]"].length === columnCount) {
                delete values["columnsToDisplay[]"];
                values.displayAllColumns = true;
            }
        } else {
            values.displayAllColumns = true;
        }

        $.post($searchForm.attr("action"), values, function (data) {
            Functions.ajaxRemoveMessage($msgbox);
            if (typeof data !== "undefined" && data.success === true) {
                if (typeof data.sql_query !== "undefined") {
                    // zero rows
                    $("#sqlqueryresultsouter").html(data.sql_query);
                } else {
                    // results found
                    $("#sqlqueryresultsouter").html(data.message);
                    $(".sqlqueryresults").trigger("makeGrid");
                }
                $("#tbl_search_form").slideToggle().hide();
                $("#togglesearchformlink").text(Messages.strShowSearchCriteria);
                $("#togglesearchformdiv").show();
                $("html, body").animate({ scrollTop: 0 }, "fast");
            } else {
                $("#sqlqueryresultsouter").html(data.error);
            }
            Functions.highlightSql($("#sqlqueryresultsouter"));
        }); // end $.post()
    });
    $("span.open_search_gis_editor").hide();

    $("select.geom_func").on("change", function () {
        var $geomFuncSelector = $(this);

        var binaryFunctions = [
            "Contains",
            "Crosses",
            "Disjoint",
            "Equals",
            "Intersects",
            "Overlaps",
            "Touches",
            "Within",
            "MBRContains",
            "MBRDisjoint",
            "MBREquals",
            "MBRIntersects",
            "MBROverlaps",
            "MBRTouches",
            "MBRWithin",
            "ST_Contains",
            "ST_Crosses",
            "ST_Disjoint",
            "ST_Equals",
            "ST_Intersects",
            "ST_Overlaps",
            "ST_Touches",
            "ST_Within",
        ];

        var tempArray = [
            "Envelope",
            "EndPoint",
            "StartPoint",
            "ExteriorRing",
            "Centroid",
            "PointOnSurface",
        ];
        var outputGeomFunctions = binaryFunctions.concat(tempArray);
        var $operator = $geomFuncSelector
            .parents("tr")
            .find("td")
            .eq(4)
            .find("select");
        if ($.inArray($geomFuncSelector.val(), binaryFunctions) >= 0) {
            $operator.prop("readonly", true);
        } else {
            $operator.prop("readonly", false);
        }
        var $editorSpan = $geomFuncSelector
            .parents("tr")
            .find("span.open_search_gis_editor");
        if ($.inArray($geomFuncSelector.val(), outputGeomFunctions) >= 0) {
            $editorSpan.show();
        } else {
            $editorSpan.hide();
        }
    });

    $(document).on("click", "span.open_search_gis_editor", function (event) {
        event.preventDefault();

        var $span = $(this);
        var value = $span.parent("td").children("input[type='text']").val();
        var field = "Parameter";
        var geomFunc = $span.parents("tr").find(".geom_func").val();
        var type = "GEOMETRY";
        if (!value) {
            if (geomFunc === "Envelope") {
                value = "POLYGON()";
            } else if (geomFunc === "ExteriorRing") {
                value = "LINESTRING()";
            } else {
                value = "POINT()";
            }
        }
        var inputName = $span
            .parent("td")
            .children("input[type='text']")
            .attr("name");

        openGISEditor();
        if (!gisEditorLoaded) {
            loadJSAndGISEditor(value, field, type, inputName);
        } else {
            loadGISEditor(value, field, type, inputName);
        }
    });

    /**
     * Ajax event handler for Range-Search.
     */
    $("body").on(
        "change",
        'select[name*="criteriaColumnOperators"]',
        function () {
            // Fix for bug #13778, changed 'click' to 'change'
            var $sourceSelect = $(this);
            var columnName = $(this).closest("tr").find("th").first().text();
            var dataType = $(this)
                .closest("tr")
                .find("td[data-type]")
                .attr("data-type");
            dataType = TableSelect.checkIfDataTypeNumericOrDate(dataType);
            var operator = $(this).val();

            if (
                (operator === "BETWEEN" || operator === "NOT BETWEEN") &&
                dataType
            ) {
                var $msgbox = Functions.ajaxShowMessage();
                $.ajax({
                    url: "index.php?route=/table/search",
                    type: "POST",
                    data: {
                        server: CommonParams.get("server"),
                        ajax_request: 1,
                        db: $('input[name="db"]').val(),
                        table: $('input[name="table"]').val(),
                        column: columnName,
                        range_search: 1,
                    },
                    success: function (response) {
                        Functions.ajaxRemoveMessage($msgbox);
                        if (response.success) {
                            var min = response.column_data.min
                                ? "(" +
                                  Messages.strColumnMin +
                                  " " +
                                  response.column_data.min +
                                  ")"
                                : "";
                            var max = response.column_data.max
                                ? "(" +
                                  Messages.strColumnMax +
                                  " " +
                                  response.column_data.max +
                                  ")"
                                : "";
                            $("#rangeSearchModal").modal("show");
                            $("#rangeSearchLegend").first().html(operator);
                            $("#rangeSearchMin").first().text(min);
                            $("#rangeSearchMax").first().text(max);
                            $("#min_value").first().val("");
                            $("#max_value").first().val("");
                            Functions.addDatepicker($("#min_value"), dataType);
                            Functions.addDatepicker($("#max_value"), dataType);
                            $("#rangeSearchModalGo").on("click", function () {
                                var minValue = $("#min_value").val();
                                var maxValue = $("#max_value").val();
                                var finalValue = "";
                                if (minValue.length && maxValue.length) {
                                    finalValue = minValue + ", " + maxValue;
                                }
                                var $targetField = $sourceSelect
                                    .closest("tr")
                                    .find('[name*="criteriaValues"]');
                                if ($targetField.is("select")) {
                                    $targetField.val(finalValue);
                                    var $options = $targetField.find("option");
                                    var $closestMin = null;
                                    var $closestMax = null;
                                    $options.each(function () {
                                        if (
                                            $closestMin === null ||
                                            Math.abs($(this).val() - minValue) <
                                                Math.abs(
                                                    $closestMin.val() -
                                                        minValue,
                                                )
                                        ) {
                                            $closestMin = $(this);
                                        }

                                        if (
                                            $closestMax === null ||
                                            Math.abs($(this).val() - maxValue) <
                                                Math.abs(
                                                    $closestMax.val() -
                                                        maxValue,
                                                )
                                        ) {
                                            $closestMax = $(this);
                                        }
                                    });

                                    $closestMin.attr("selected", "selected");
                                    $closestMax.attr("selected", "selected");
                                } else {
                                    $targetField.val(finalValue);
                                }
                                $("#rangeSearchModal").modal("hide");
                                $(this).off("click");
                            });
                        } else {
                            Functions.ajaxShowMessage(response.error);
                        }
                    },
                    error: function () {
                        Functions.ajaxShowMessage(
                            Messages.strErrorProcessingRequest,
                        );
                    },
                });
            }
        },
    );
    var windowWidth = $(window).width();
    $(".jsresponsive").css("max-width", windowWidth - 69 + "px");
});
