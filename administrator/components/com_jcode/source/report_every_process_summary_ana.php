<?php
$user = JFactory::getUser();
$baseUrl = JURI::base() . 'administrator/components/com_jcode/source/';
$jBaseUrl = JURI::base();
$lang = JFactory::getLanguage();
$lan = $lang->getTag();
?>
<script>
    var baseUrl = '<?php echo $baseUrl; ?>';
    var lan = '<?php echo $lan; ?>';
</script>

<?php
include_once ('database_conn.php');
include_once ('init_month_year.php');
include_once ('function_lib.php');
include_once ('combo_script.php');
?>

<script type="text/javascript">
    var vLang = '<?php echo $vLang; ?>';
	var ProcUnitId = 2;
</script>



<div class="container">
    <div class="content_fullwidth lessmar">
        <div class="azp_col-md-12 one_full">
            <div class="row">
                <div class="col-md-4">
                    <div class="tbl-header1" id="itemTable_length1">
                        <label>Select Process: 
                            <select class="form-control" id="process-list">
                            </select>
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                </div>
                <div id="reportrange-container" class="col-md-4">
                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span></span> <b class="caret"></b>
                    </div>
                </div>
            </div>

            <div class="panel-heading clearfix">
                <?php echo 'Every Process Summary'; ?>
                <span class="pull-right">
                    <label>
                        <a id="PrintBTN" data-mce-href="#" class="but_print" href="javascript:void(0);" onclick="print_function('print')"><i data-mce-bootstrap="1" class="fa fa-print fa-lg">&nbsp;</i> <?php echo $TEXT['Print']; ?></a>
                        <a id="PrintBTN1" data-mce-href="#" class="but_excel" href="javascript:void(0);" onclick="print_function('excel')"><i data-mce-bootstrap="1" class="fa fa-file-excel-o fa-lg">&nbsp;</i> <?php echo $TEXT['Excel']; ?></a>
                        <a style="display:none;" id="PDFBTN" data-mce-href="#" class="but_pdf" href="javascript:void(0);" onclick="print_function('pdf')"><i data-mce-bootstrap="1" class="fa fa-file-pdf-o fa-lg">&nbsp;</i> <?php echo $TEXT['PDF']; ?></a>
                    </label>
                </span>
            </div>

            <div class="panel-body">
                <div style="overflow-x: scroll;" id="tbl-pf">
                </div>
            </div>          
		   <br/>
			<div class="panel-heading clearfix" style="padding-bottom:10px;">
				Job Status List
			</div>
			
            <table  id="process-status" class="table table-striped table-bordered display table-hover" cellspacing="0">
                <thead>
                    <tr>                        
                        <th style="text-align: center;">SL.</th>
                        <th>Tracking No.</th>
                        <th>Status</th>
                        <th>User Name</th>
                        <th>Time consumed</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            
        </div>

    </div>
</div>
</div>

<script>
    function print_function(type) {
        //console.log($('#tbl-every-process-summary').dataTable());
        var tableId = 'tbl-every-process-summary';
        var reportHeaderList = new Array();
        var dataAlignment = new Array();
        var chart = 0;
        var reportSaveName = 'Every_Process_Summary'; //Not allow any type of special character of cahrtName

        var reportHeaderName = 'Every Process Summary';
        reportHeaderList[0] = reportHeaderName;
        reportHeaderList[1] = $('#process-list option[value=' + $('#process-list').val() + ']').text();

        dataAlignment = ["center", "center"];
        //when column count and width array count are not same then last value repeat
        cellWidth = ["10", "30", "30"];

        reportHeaderList = JSON.stringify(reportHeaderList);

        //Get current date time
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();
        var hh = today.getHours();
        var min = today.getMinutes();
        var sec = today.getSeconds();
        today = dd + '_' + mm + '_' + yyyy + '_' + hh + '_' + min + '_' + sec;
        reportSaveName = reportSaveName + '_' + today;// reportHeaderList[0].str.replace(/ /g, '_')+'_'+today;

        $.ajax({
            type: "POST",
            url: baseUrl + "report/chart_generate_svg.php",
            async: false,
            datatype: "json",
            cache: true,
            data: {
                baseUrl: baseUrl,
                svgName: reportSaveName,
                html: $('#bar-chart svg').parent().html(),
                alavel: $('#barchartlegend').html(),
                htmlTable: '<table  class="table table-striped display" width="100%" border="0.5" style="margin:0 auto;"> ' + $("#" + tableId).html() + ' </table>',
                chart: chart
            },
            success: function (response) {

                if (type == 'print') {
                    window.open("<?php echo $baseUrl; ?>report/print_master_dynamic_column.php?jBaseUrl=<?php echo $jBaseUrl; ?>"
                                                + "&lan=<?php echo $lan; ?>"
                                                + "&reportSaveName=" + reportSaveName
                                                + "&reportHeaderList=" + reportHeaderList
                                                + "&chart=" + chart
                                                );
                                    }
                                    else if (type == 'excel') {
                                        var tableHeaderList = new Array();
                                        var dataList = new Array();

                                        var datatable = $('#' + tableId).dataTable();
                                        var rows = datatable.dataTableSettings[0].nTHead.rows;

                                        var tableHeaderList = [];
                                        var tableHeaderColSpanList = [];
                                        var tableHeaderRowSpanList = [];
                                        //var tableHeaderColWidthList = [];
                                        for (var r = 0; r < rows.length; r++) {
                                            //// Creates an empty line
                                            tableHeaderList.push([]);
                                            tableHeaderColSpanList.push([]);
                                            tableHeaderRowSpanList.push([]);
                                            //tableHeaderColWidthList.push([]);
                                            //// Adds cols to the empty line:
                                            //tableHeaderList[index].push( new Array(totalColumn));
                                            tableHeaderList[r].push(new Array(rows[r].length));
                                            tableHeaderColSpanList[r].push(new Array(rows[r].length));
                                            tableHeaderRowSpanList[r].push(new Array(rows[r].length));
                                            // tableHeaderColWidthList[r].push( new Array(rows[r].length));
                                            //}
                                            for (var c = 0; c < rows[r].cells.length; c++) {
                                                tableHeaderList[r][c] = rows[r].cells[c].textContent;//topTds[m].textContent;
                                                tableHeaderColSpanList[r][c] = rows[r].cells[c].colSpan;
                                                tableHeaderRowSpanList[r][c] = rows[r].cells[c].rowSpan
                                                //tableHeaderColWidthList[r][c] = rows[r].cells[c].clientWidth;
                                            }
                                        }

                                        tableHeaderList = JSON.stringify(tableHeaderList);
                                        tableHeaderColSpanList = JSON.stringify(tableHeaderColSpanList);
                                        tableHeaderRowSpanList = JSON.stringify(tableHeaderRowSpanList);
                                        tableHeaderColWidthList = JSON.stringify(cellWidth);
                                        //console.log(tableHeaderColWidthList);
                                        var datatable = $('#' + tableId).dataTable();
                                        var rows = datatable.dataTableSettings[0].nTBody.rows;

                                        var dataList = [];
                                        var dataColSpanList = [];
                                        for (var r = 0; r < rows.length; r++) {
                                            //// Creates an empty line
                                            dataList.push([]);
                                            dataColSpanList.push([]);
                                            //// Adds cols to the empty line:
                                            //dataList[index].push( new Array(totalColumn));
                                            dataList[r].push(new Array(rows[r].length));
                                            dataColSpanList[r].push(new Array(rows[r].length));
                                            //}
                                            for (var c = 0; c < rows[r].cells.length; c++) {
                                                dataList[r][c] = rows[r].cells[c].textContent;//topTds[m].textContent;
                                                dataColSpanList[r][c] = rows[r].cells[c].colSpan;
                                            }
                                        }

                                        dataList = JSON.stringify(dataList);
                                        dataColSpanList = JSON.stringify(dataColSpanList);

                                        //alert('kkkkk');

                                        $.ajax({
                                            url: baseUrl + 'report/excel_master_dynamic_column.php',
                                            type: 'post',
                                            data: {
                                                jBaseUrl: "<?php echo $jBaseUrl; ?>",
                                                lan: lan,
                                                reportSaveName: reportSaveName,
                                                reportHeaderList: reportHeaderList,
                                                tableHeaderList: tableHeaderList,
                                                tableHeaderColSpanList: tableHeaderColSpanList,
                                                tableHeaderRowSpanList: tableHeaderRowSpanList,
                                                tableHeaderColWidthList: tableHeaderColWidthList,
                                                dataList: dataList,
                                                dataColSpanList: dataColSpanList,
                                                chart: chart,
                                                dataAlignment: JSON.stringify(dataAlignment)
                                            },
                                            success: function (response) {
                                                //console.log(response);
                                                window.open(baseUrl + 'report/media/' + reportSaveName + '.xlsx');
                                            }
                                        });
                                        /**/
                                    }
                                    else if (type == 'pdf') {
                                        var datatable = $('#' + tableId).dataTable();
                                        var columns = datatable.dataTableSettings[0].aoColumns;
                                        var totalColumn = 0;
                                        //var totalWidth = datatable[0].clientWidth;//1192;
                                        var totalWidth = datatable[0].clientWidth + 20;//1192;
                                        $.each(columns, function (i, v) {
                                            if (v.bVisible) {
                                                totalColumn++;
                                            }
                                        });

                                        var htmlTable = '<table width="100%" border="0.5" style="margin:0 auto;"><thead>';
                                        var topThs = $("#" + tableId + " tHead tr");
                                        for (var m = 0; m < topThs.length; m++) {
                                            htmlTable += '<tr role="row">';
                                            for (var n = 0; n < topThs[m].children.length; n++) {
                                                var tmpThWidth = ((topThs[m].children[n].clientWidth * 100) / totalWidth).toFixed();
                                                //////Math.round((topThs[m].children[n].clientWidth*100)/totalWidth);
                                                htmlTable += '<th role="columnheader" colspan="' + topThs[m].children[n].colSpan
                                                        + '" rowspan="' + topThs[m].children[n].rowSpan
                                                        + '" class="' + topThs[m].children[n].className
                                                        + '" style=" width:' + tmpThWidth
                                                        + '%; text-align:' + topThs[m].children[n].style.textAlign
                                                        + ';">' + topThs[m].children[n].textContent + '</th>';
                                            }
                                            htmlTable += '</tr>';
                                        }
                                        htmlTable += '</thead><tbody>';

                                        var topTds = $("#" + tableId + " tr td");
                                        var i = 0;
                                        for (var m = 0; m < topTds.length; m++) {
                                            i++
                                            if (i == 1) {
                                                htmlTable += '<tr>';
                                            }
                                            var tmpWidth = Math.round((topTds[m].clientWidth * 100) / totalWidth);
                                            htmlTable += '<td style="text-align:' + dataAlignment[i - 1] + ';" width="' + tmpWidth + '%" class="' + topTds[m].className + '"' + '>' + topTds[m].textContent + '</td>';
                                            //console.log(topTds[m], "client: ", topTds[m].clientWidth, "offset ", topTds[m].offsetWidth, "scroll ", topTds[m].scrollWidth,topTds[m].textContent);
                                            //console.log(topTds[m].style);
                                            if (i == totalColumn) {
                                                htmlTable += '</tr>';
                                                i = 0;
                                            }
                                        }
                                        htmlTable += '</tbody></table>';

                                        $.ajax({
                                            url: baseUrl + 'report/pdf_master_dynamic_column.php',
                                            type: 'post',
                                            data: {
                                                jBaseUrl: "<?php echo $jBaseUrl; ?>",
                                                lan: lan,
                                                reportSaveName: reportSaveName,
                                                reportHeaderList: reportHeaderList,
                                                chart: chart,
                                                htmlTable: htmlTable
                                            }

                                            ,
                                            success: function (response) {
                                                window.open(baseUrl + 'report/pdfslice/' + response);
                                            }
                                        });

                                        /*
                                         var ajaxRequest;
                                         
                                         try{
                                         // Opera 8.0+, Firefox, Safari
                                         ajaxRequest = new XMLHttpRequest();
                                         } catch (e){
                                         // Internet Explorer Browsers
                                         try{
                                         ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                                         } catch (e) {
                                         try{
                                         ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                                         } catch (e){
                                         // Something went wrong
                                         alert("Browser Not Supported");
                                         return false;
                                         }
                                         }
                                         }
                                         ajaxRequest.open("POST",  baseUrl + "report/pdf_master_dynamic_column.php", true);
                                         ajaxRequest.send("jBaseUrl=localhost&lan="+lan+"&reportSaveName="+reportSaveName+"&reportHeaderList="+reportHeaderList+"&chart="+chart+"&htmlTable="+htmlTable);
                                         */

                                    }
                                }
                            });
                        }




</script>

<script type="text/javascript">
    var $ = jQuery.noConflict();
    var dp1StartDate;
    var dp1EndDate;
    $(document).ready(function () {
        var cb = function (start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
        };

        var optionSet1 = {
            startDate: moment(),
            endDate: moment(),
            minDate: '01/01/2015',
            maxDate: '12/31/2020',
            dateLimit: {days: 365},
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens: 'left',
            buttonClasses: ['btn btn-default'],
            applyClass: 'btn-sm btn-primary',
            cancelClass: 'btn-sm',
            format: 'MM/DD/YYYY',
            separator: ' to ',
            locale: {
                applyLabel: 'Submit',
                cancelLabel: 'Clear',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        };

        $('#reportrange span').html(moment().format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

        $('#reportrange').daterangepicker(optionSet1, cb);

        $('#reportrange').on('show.daterangepicker', function () {
            console.log("show event fired");
        });
        $('#reportrange').on('hide.daterangepicker', function () {
            console.log("hide event fired");
        });
        /* $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
         console.log("apply event fired, start/end dates are "
         + picker.startDate.format('YYYY-MM-DD')
         + " to "
         + picker.endDate.format('YYYY-MM-DD')
         );
         }); */
        $('#reportrange').on('cancel.daterangepicker', function (ev, picker) {
            console.log("cancel event fired");
        });
    });
</script>



<style>
    .SL{
        text-align: center !important;
    }
    .amc, .soh, .mos{
        text-align: right !important;
    }

    .nationalLevelAMC, .nationalLevelSOH, .nationalLevelMOS{

        text-align: right !important;

    }

    .left-aln{
        text-align : left !important;
    }
    .right-aln{
        text-align : right !important;
    }
    .center-aln{
        text-align : center !important;
    }

    .DataTables_sort_wrapper{
        padding-right: 0px !important;
    }

    #tbl-every-process-summary  tr  th {
        vertical-align: middle !important;
    }

    #tbl-every-process-summary td{
        border: 1px solid #e4e4e4 !important;
        text-align: right !important;
    }
    #tbl-every-process-summary th{
        border: 1px solid #e4e4e4 !important;

    }

    #tbl-every-process-summary td:nth-child(2) {
        text-align: right !important;
    }


    #tbl-every-process-summary td:nth-child(1) {
    }

    table.dataTable thead th .DataTables_sort_wrapper span.ui-icon-carat-2-n-s:before{
        content: '' !important ;
    }


    #tbl-every-process-summary th{
        border: 1px solid #e4e4e4 !important;

    }

</style>
<script src="<?php echo $baseUrl; ?>lib/fnc-lib.js" type="text/javascript"></script>

<link href="<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="<?php echo $baseUrl; ?>css/jquery.dataTables.custom.css" rel="stylesheet"/>
<script src='<?php echo $baseUrl; ?>media/datatable/js/jquery.dataTables.min.js'></script>
<script src='<?php echo $baseUrl; ?>media/datatable-bootstrap/dataTables.bootstrap.min.js'></script>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo $baseUrl; ?>lib/bootstrap-daterangepicker/daterangepicker-bs3.css" />
<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/bootstrap-daterangepicker/moment.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>lib/bootstrap-daterangepicker/daterangepicker.js"></script>

<script src='<?php echo $baseUrl; ?>report_every_process_summary_ana.js'></script>