var gMonthId;
var gYearId;
var dataY = new Array();
var dataColor = new Array();
var stockStatusDifferentLevel;
var endDate = new Date();
var chart;
var gFrequencyId = 1;
var $ = jQuery.noConflict();

$(function () {

    dpStartDate = moment().startOf('month').format('YYYY-MM-DD');
    dpEndDate = moment().endOf('month').format('YYYY-MM-DD');
    $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
        dpStartDate = picker.startDate.format('YYYY-MM-DD');
        dpEndDate = picker.endDate.format('YYYY-MM-DD');

        processStatus.fnDraw();

    });


    var processStatus = $('#process-status').dataTable({
        "bDestroy": true,
        "bFilter": true,
        "bJQueryUI": false,
        "bSort": false,
        "bInfo": false,
        "bPaginate": false,
        "bSortClasses": false,
        "bProcessing": true,
        "bServerSide": true,
        "aaSorting": [[0, 'asc']],
        "sPaginationType": "full_numbers",
        "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
        "iDisplayLength": 50,
        "sAjaxSource": baseUrl + "report_search_job_tex_server.php",
        "fnServerData": function (sSource, aoData, fnCallback) {
            aoData.push({
                "name": "action",
                "value": 'getJobListForSearch'
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
        },
        "aoColumns": [{
                "sWidth": "5%",
                "sClass": "center-aln",
                 "bSortable": false,
                "bSearchable": false,
            }, {
                "sWidth": "10%",
                "sClass": "center-aln",
                "bSortable": false,
                "bSearchable": true
            }, {
                "sWidth": "15%",
                "sClass": "left-aln",
                "bSortable": false,
                "bSearchable": true
            }, {
                "sWidth": "14%",
                "sClass": "left-aln",
                "bVisible": true,
                "bSortable": false,
                "bSearchable": true
            }, {
                "sWidth": "14%",
                "sClass": "left-aln",
                "bVisible": true,
                "bSortable": false,
                "bSearchable": true
            }, {
                "sWidth": "8%",
                "sClass": "left-aln",
                "bVisible": true,
                "bSortable": false,
                "bSearchable": true
            }, {
                "sWidth": "20%",
                "sClass": "left-aln",
               "bSortable": false,
                "bSearchable": true
            }, {
                "sWidth": "14%",
                "sClass": "left-aln",
                "bSortable": false,
                "bSearchable": true
            }]
    });

});
