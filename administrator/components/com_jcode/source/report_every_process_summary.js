var gMonthId;
var gYearId;
var dataY = new Array();
var dataColor = new Array();
var stockStatusDifferentLevel;
var endDate = new Date();
var chart;
var gFrequencyId = 1;
var everyProcessSummary;
var $ = jQuery.noConflict();


/* var fixedColumns = [{"ColName":"SL#", "Align":"center", "Width":"5%"}
 ,{"ColName":"Total In", "Align":"right", "Width":"10%"}
 ,{"ColName":"Total Out", "Align":"right", "Width":"10%"}
 ,{"ColName":"Total Pending", "Align":"right", "Width":"10%"}];
 
 var dynamicColumns = ["Golam Mostofa (Pending)","Jamal Sardar (Pending)","Rahmat Ali (Pending)","Tanvir Alam (Pending)","Zakir Hussain (Pending)"];
 
 var html = createSingleColumnsHtml(fixedColumns, dynamicColumns, '#tbl-id');
 
 $('#tbl-pf').html(html); */

function createSingleColumnsHtml(fixedColumns, dynamicColumns, tableId) {
    var fxlength = fixedColumns.length;
    var dylength = dynamicColumns.length;

    if (fxlength == 0) {
        console.log('Fixed columns array length is zero');
    }

    if (dylength == 0) {
        console.log('Dynamic columns array length is zero');
    }

    var html = '';
    html = '<table class="table table-striped table-bordered display table-hover" cellspacing="0" id="' + tableId + '">';
    html += '<thead>';
    html += '<tr>';

    for (var i = 0; i < fxlength; i++) {
        html += '<th style="text-align:' + fixedColumns[i].Align + '; width:' + fixedColumns[i].Width + ';">' + fixedColumns[i].ColName + '</th>';
    }

    for (var i = 0; i < dylength; i++) {
        html += '<th style="text-align:right;">' + dynamicColumns[i] + '</th>';
    }

    html += '</tr>';
    html += '</thead>';
    html += '<tbody></tbody>';
    html += '</table>';
    return html;
}


function onEveryProcessSummaryTable() {
    //alert($('#month-list').val());
//    $('body').animate({
//        opacity: 1
//    }, 500, function () {

    $.ajax({
        type: "POST",
        url: baseUrl + "report_every_process_summary_server.php",
        data: {
            action: 'getEveryProcessData',
			ProcUnitId: ProcUnitId,
            dp1_start: dpStartDate,
            dp1_end: dpEndDate,
            "ProcessId": $('#process-list').val(),
            lan: lan
        },
        success: function (results) {
            results = $.parseJSON(results);
            var dynamicColumns = results.COLUMNS;

            $('#tbl-pf').html('');

            var fixedColumns = [
                {"ColName": "Total In", "Align": "right", "Width": "10%"}
                , {"ColName": "Total Out", "Align": "right", "Width": "10%"}
                , {"ColName": "Total Pending", "Align": "right", "Width": "10%"}];

            var html = createSingleColumnsHtml(fixedColumns, dynamicColumns, 'tbl-every-process-summary');

            $('#tbl-pf').html(html);

            dp1StartDate = moment().format('YYYY-MM-DD');
            dp1EndDate = moment().format('YYYY-MM-DD');

            everyProcessSummary = $('#tbl-every-process-summary').dataTable({
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
                "sAjaxSource": baseUrl + "report_every_process_summary_server.php",
                "fnDrawCallback": function (oSettings) {

                },
                "fnServerData": function (sSource, aoData, fnCallback) {
                    aoData.push({
                        "name": "action",
                        "value": 'getEveryProcessData'
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
                        "name": "ProcUnitId",
                        "value": ProcUnitId
                    });
                    aoData.push({
                        "name": "dp1_end",
                        "value": dpEndDate
                    });
                    aoData.push({
                        "name": "ProcessId",
                        "value": $('#process-list').val()
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
    //});
}


$(function () {

    $.each(gProcessList, function (i, obj) {
        $('#process-list').append($('<option></option>').val(obj.ProcessId).html(obj.ProcessName));
    });

    $defaultProcessId = 1;
    $('#process-list').val($defaultProcessId);

    dpStartDate = moment().format('YYYY-MM-DD');
    dpEndDate = moment().format('YYYY-MM-DD');


    onEveryProcessSummaryTable();

    $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
        dpStartDate = picker.startDate.format('YYYY-MM-DD');
        dpEndDate = picker.endDate.format('YYYY-MM-DD');

        onEveryProcessSummaryTable();

        processStatus.fnDraw();

    });
    $("#process-list").change(function () {
        onEveryProcessSummaryTable();
        processStatus.fnDraw();
    });

    var processStatus = $('#process-status').dataTable({
        "bDestroy": true,
        "bFilter": true,
        "bJQueryUI": false,
        "bSort": true,
        "bInfo": true,
        "bPaginate": true,
        "bSortClasses": false,
        "bProcessing": true,
        "bServerSide": true,
        "aaSorting": [[0, 'asc']],
        "sPaginationType": "full_numbers",
        "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
        "iDisplayLength": 25,
        "sAjaxSource": baseUrl + "report_every_process_summary_server.php",
        "fnServerData": function (sSource, aoData, fnCallback) {
            aoData.push({
                "name": "action",
                "value": 'getProcessStatus'
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
                "name": "ProcUnitId",
                "value": ProcUnitId
            });
            aoData.push({
                "name": "dp1_end",
                "value": dpEndDate
            });
            aoData.push({
                "name": "ProcessId",
                "value": $('#process-list').val()
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
                "sWidth": "20%",
                "sClass": "center-aln",
                "bSortable": true,
                "bSearchable": true
            }, {
                "sWidth": "25%",
                "sClass": "left-aln",
                "bVisible": true,
                "bSearchable": true
            }, {
                "sWidth": "25%",
                "sClass": "left-aln",
                "bSortable": true,
                "bSearchable": true
            }, {
                "sWidth": "25%",
                "sClass": "left-aln",
                "bSortable": true,
                "bSearchable": true
            }]
    });

});
