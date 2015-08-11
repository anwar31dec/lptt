var gMonthId;
var gYearId;
var dataY = new Array();
var dataColor = new Array();
var stockStatusDifferentLevel;
var endDate = new Date();
var chart;
var gFrequencyId = 1;
var patientTrendTimeSeries;
var $ = jQuery.noConflict();


function onPatientTrendTable() {
    //alert($('#month-list').val());
    $('body').animate({
        opacity: 1
    }, 500, function () {

        $.ajax({
            type: "POST",
            url: baseUrl + "report_job_total_time_duration_server.php",
            data: {
                action: 'getDiffLevelTableData',
                dp1_start: dpStartDate,
                dp1_end: dpEndDate,
                lan: lan
            },
            success: function (results) {
                results = $.parseJSON(results);
                var ColumnNo = results.COLUMNS.length;

                $('#tbl-pf').html('');
                html = '<table class="table table-striped table-bordered display table-hover" cellspacing="0" id="tbl-patient-trend-time-series">';
                html += '<thead>';


                if (ColumnNo == 0) {
                    html += '<tr><th style="text-align:center; width:5%;">SL#</th><th rowspan="2" style="text-align:center; width:10%;">Tracking#</th>';
                    html += '<th  style="text-align:center; width:10%;">In Time</th>';
                    html += '<th style="text-align:center; width:10%;">Out Time</th>';
                    html += '<th style="text-align:center; width:10%;">Duration</th></tr>'
                }
                else {
                    html += '<tr><th rowspan="2" style="text-align:center; width:5%;">SL#</th>';
                    html += '<th rowspan="2" style="text-align:center; width:10%;">Tracking#</th>';

                    var Header = '-1';
                    for (var i = 0; i < ColumnNo; i++) {
                        if (Header != results.COLUMNS[i]) {
                            html += '<th colspan="3" style="text-align:center;">' + results.COLUMNS[i] + '</th>';
                            Header = results.COLUMNS[i];
                        }
                    }

                    var index = 0;
                    html += '<th rowspan="2" style="text-align:center;">Total Time</th>';
                    html += '</tr><tr>'
                    for (var i = 0; i < ColumnNo; i++) {
                        index++;
                        if (index == 1)
                            html += '<th  style="text-align:center; width:13%;">In Time</th>';
                        else if (index == 2)
                            html += '<th  style="text-align:center; width:13%;">Out Time</th>';
                        else if (index == 3)
                            html += '<th  style="text-align:center; width:13%;">Duration</th>';

                        if (index == 3)
                            index = 0;

                    }
                    html += '</tr>'
                }


                html += '</thead>';
                html += '<tbody></tbody>';
                html += '</table>';
                $('#tbl-pf').html(html);

                patientTrendTimeSeries = $('#tbl-patient-trend-time-series').dataTable({
                    "bFilter": false,
                    "bJQueryUI": true,
                    "bSort": true,
                    "bInfo": false,
                    "bPaginate": false,
                    "bSortClasses": false,
                    "bProcessing": true,
                    "bServerSide": false,
                    "bSortable": false,
                    // scrollY:        "400px",
                    // scrollX:        "100%",
                    // scrollCollapse: true,
                    // paging:         false,
                    "aaSorting": [[1, 'asc']],
                    "sPaginationType": "full_numbers",
                    "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
                    "iDisplayLength": 25,
                    "sAjaxSource": baseUrl + "report_job_total_time_duration_server.php",
                    "fnDrawCallback": function (oSettings) {

                    },
                    "fnServerData": function (sSource, aoData, fnCallback) {
                        aoData.push({
                            "name": "action",
                            "value": 'getDiffLevelTableData'
                        });
                        aoData.push({
                            "name": "lan",
                            "value": lan
                        });
                        aoData.push({
                            "name": "baseUrl",
                            "value": baseUrl
                        });
                        aoData.push({
                            "name": "dp1_start",
                            "value": dpStartDate
                        });
                        aoData.push({
                            "name": "dp1_end",
                            "value": dpEndDate
                        });


                        $.ajax({
                            "dataType": 'json',
                            "type": "POST",
                            "url": sSource,
                            "data": aoData,
                            "success": function (json) {
                                fnCallback(json);
                            }
                        });
                    }
                });

            }
        });
    });
}


$(function () {
    $.each(gMonthList, function (i, obj) {
        $('#month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
    });

    $.each(gYearList, function (i, obj) {
        $('#year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
    });

    endDate.setMonth(objInit.svrLastMonth - 1);
    endDate.setFullYear(objInit.svrLastYear);
    $("#month-list").val(endDate.getMonth() + 1);
    $("#year-list").val(endDate.getFullYear());

    $("#month-list").change(function () {
        gMonthId = $('#month-list').val();
        endDate.setMonth($("#month-list").val() - 1);
        //onBarChartReport();
        //nationalSumProducts.fnDraw();
        //getReportGeneratePercentage();
        onPatientTrendTable();
    });

    $("#year-list").change(function () {
        gYearId = $('#year-list').val();
        endDate.setYear($("#year-list").val());
        endDate.setMonth($("#month-list").val() - 1);
        //onBarChartReport();
        //nationalSumProducts.fnDraw();
        //getReportGeneratePercentage();
        onPatientTrendTable();
    });

    $("#left-arrow").click(function () {

        //if (gFrequencyId == 1) {
        if (endDate.getMonth() == 0 && endDate.getFullYear() == gYearList[0].YearName)
            return;

        endDate.prevMonth();
        // } else {
        // if (endDate.getMonth() == 2 && endDate.getFullYear() == gYearList[0].YearName)
        // return;
        // endDate.prevMonths(3);
        // }
        $("#month-list").val(endDate.getMonth() + 1);
        $("#year-list").val(endDate.getFullYear());
        //onBarChartReport();
        //nationalSumProducts.fnDraw();
        //getReportGeneratePercentage();
        onPatientTrendTable();
    });

    $("#right-arrow").click(function () {

        //if (gFrequencyId == 1) {
        if (endDate.getMonth() == 11 && endDate.getFullYear() == gYearList[gYearList.length - 1].YearName)
            return;
        endDate.nextMonth();
        //} else {
        //  if (endDate.getMonth() == 11 && endDate.getFullYear() == gYearList[gYearList.length - 1].YearName)
        //    return;
        //endDate.nextMonths(3);
        //}
        $("#month-list").val(endDate.getMonth() + 1);
        $("#year-list").val(endDate.getFullYear());
        //onBarChartReport();
        //nationalSumProducts.fnDraw();
        //getReportGeneratePercentage();
        onPatientTrendTable();
    });

    dpStartDate = moment().startOf('month').format('YYYY-MM-DD');
    dpEndDate = moment().endOf('month').format('YYYY-MM-DD');
    
    onPatientTrendTable();

    $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
        dpStartDate = picker.startDate.format('YYYY-MM-DD');
        dpEndDate = picker.endDate.format('YYYY-MM-DD');

        onPatientTrendTable();

    });

});
