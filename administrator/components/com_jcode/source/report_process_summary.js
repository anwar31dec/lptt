var gMonthId;
var gYearId;
var dataY = new Array();
var dataColor = new Array();
var stockStatusDifferentLevel;
var endDate = new Date();
var chart;
var gFrequencyId = 1;
var patientTrendTimeSeries;
var tblProcessCount;
var $ = jQuery.noConflict();


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

    dp1StartDate = moment().format('YYYY-MM-DD');
    dp1EndDate = moment().format('YYYY-MM-DD');

    tblProcessCount = $('#tblProcessCount').dataTable({
        "bFilter": false,
        "bJQueryUI": false,
        "bSort": false,
        "bInfo": false,
        "bPaginate": false,
        "bSortClasses": false,
        "bProcessing": true,
        "bServerSide": true,
        "aaSorting": [[1, 'asc']],
        "sPaginationType": "full_numbers",
        "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
        "iDisplayLength": 25,
        "sAjaxSource": baseUrl + "report_process_summary_server.php",
        "fnServerData": function (sSource, aoData, fnCallback) {
            aoData.push({
                "name": "action",
                "value": "getProcessCount"
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
                "name": "dp1-start",
                "value": dp1StartDate
            });
            aoData.push({
                "name": "dp1-end",
                "value": dp1EndDate
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
        },
        "aoColumns": [{
                "bVisible": true,
                "bSortable": false,
                "sWidth": "10%"
            }, {
                "sWidth": "30%",
                "sClass": "left-aln"
            }, {
                "sWidth": "20%",
                "sClass": "right-aln",
                "bVisible": true
            }, {
                "sWidth": "20%",
                "sClass": "right-aln",
                "bVisible": true
            }, {
                "sWidth": "20%",
                "sClass": "right-aln",
                "bVisible": true
            }]
    });

    $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
        dp1StartDate = picker.startDate.format('YYYY-MM-DD');
        dp1EndDate = picker.endDate.format('YYYY-MM-DD');

        tblProcessCount.fnDraw();
        tblTotalInOutCount.fnDraw();

        //alert(startDate);

    });

});
