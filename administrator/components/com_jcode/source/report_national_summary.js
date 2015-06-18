var gMonthId;
var gYearId;
var dataY = new Array();
var dataColor = new Array();
var nationalSumProducts;
var endDate = new Date();
var chart;

var gCountryId;
var gItemGroupId;
var yearList;
var gFacilityCode;
var gFrequencyId=1;
var gStartYearId;
var gSartMonthId;
var $ = jQuery.noConflict();

function getReportGeneratePercentage() {
    $.ajax({
        type: "POST",
        url: baseUrl + "reporting_rate.php",
        data: {
            operation: 'getPercentage',
            CountryId: gCountryId,
            ItemGroupId: gItemGroupId,
            OwnerTypeId: $('#report-by').val(),
            Year: $('#year-list').val(),
            Month: $('#month-list').val(),
            lan: lan
        },
        success: function(response) {
            response = JSON.parse(response);

            $('#Total').html(response.Total + ' %');
            $('#Facility').html(response.HealthFaclilities + ' %');
            $('#District').html(response.DistrictWarehouse + ' %');
            $('#Region').html(response.RegionalWarhouse + ' %');
            $('#Central').html(response.CentralWarehouse + ' %');
        }
    });
}

function onBarChartReport() {
    $.ajax({
        type: "POST",
        url: baseUrl + "report_national_summary_server.php",
        data: {
            action: 'getSummaryChart',
            lan: lan,
            Year: $('#year-list').val(),
            Month: $('#month-list').val(),
            Country: $('#country-list').val(),
            ItemGroup: $('#item-group').val(),
            Reportby: $('#report-by').val()
        },
        success: function(response) {
            response = $.parseJSON(response);
            item_name = response.item_name;
            item_value = response.temp;
            barcolor = response.barcolor;
            name = response.name;
            cItemLength = 70 + 30 * (item_name.length);
            onSetBarChart(item_name, item_value, barcolor, name, cItemLength);
        }
    });
}

function onSetBarChart(item_name, item_value, barcolor, name, cItemLength) {
    chart = new Highcharts.Chart({
        chart: {
            type: 'bar',
            borderColor: '#C3DDEC',
            borderWidth: 1,
            plotBorderWidth: 1,
            margin: [50, 50, 50, 350],
            //spacingLeft: 50,      
            height: cItemLength,
            renderTo: 'bar-chart'
        },
        title: {
            text: TEXT['National Summary Report of '] + name
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: item_name,
            title: {
                text: null
            }
        },
        yAxis: {
            title: {
                text: TEXT['Month Of Supply (MOS)'],
                align: 'middle'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' month(s)'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        series: [{
                name: name,
                data: item_value,
                tooltip: {
                    valueDecimals: ' month(s)'
                },
                point: {
                    events: {
                        mouseOver: function() {
                            for (var i = 0; i < dataY.length; i++) {
                                if (dataY[i] == this.y)
                                    this.update({
                                        color: dataColor[i]
                                    });
                            }
                        }
                    }
                },
                pointRange: 1.75,
                events: {
                    mouseOver: function(i, point) {

                    },
                    mouseOut: function() {
                    }
                }
            }]
    });

    k = 0;
    $.each(chart.series[0].data, function(i, point) {
        value = point.y;
        point.graphic.attr({
            fill: barcolor[k]
        });
        dataY.push(point.y);
        dataColor.push(barcolor[k]);
        k++;
    });

    /************************HighChart Pdf****************************/
}

function getItemGroupFrequency() {

    if (gCountryId == 0 || gItemGroupId == 0) {
        gFrequencyId = 1;
        gSartMonthId = $('#month-list').val();
        gStartYearId = $('#year-list').val();
        getMonthByFrequencyId();
    }
    else
    {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: baseUrl + "combo_generic.php",
            data: {
                "operation": 'getItemGroupFrequency',
                "CountryId": gCountryId,
                "ItemGroupId": gItemGroupId
            },
            success: function(response) {
                gFrequencyId = response[0].FrequencyId;
                gSartMonthId = response[0].StartMonthId;
                gStartYearId = response[0].StartYearId;
                getMonthByFrequencyId();
            }
        });
    }
}

function getMonthByFrequencyId() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: baseUrl + "combo_generic.php",
        data: {
            "operation": 'getMonthByFrequencyId',
            "FrequencyId": gFrequencyId,
            "lan": lan
        },
        success: function(response) {
            $.each(response, function(i, obj) {
                $('#month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
            });

            var monthList = response || [];
            var html = $.map(monthList, function(obj) {
                return '<option value=' + obj.MonthId + '>' + obj.MonthName + '</option>';
            }).join('');

            $('#month-list').html(html);

            if (gFrequencyId == 1) {
                endDate.setMonth(objInit.svrLastMonth - 1);
                endDate.setFullYear(objInit.svrLastYear);
            }
            else if (gFrequencyId == 2) {
                endDate.setMonth(objInit.svrLastMonth - 1);
                endDate.setFullYear(objInit.svrLastYear);
                endDate.lastQuarter();
            }

            $("#month-list").val(endDate.getMonth() + 1);
            $("#year-list").val(endDate.getFullYear());

            gMonthId = $('#month-list').val() == null ? -99 : $('#month-list').val();
            onBarChartReport();
            nationalSumProducts.fnDraw();
            getReportGeneratePercentage();

        }
    });
}
$(function() {
    $.each(gMonthList, function(i, obj) {
        $('#month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
    });

//    endDate.setMonth(objInit.initialMonth - 1);
//    $("#month-list").val(objInit.initialMonth);
//
    $.each(gYearList, function(i, obj) {
        $('#year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
    });
    endDate.setMonth(objInit.svrLastMonth - 1);
	endDate.setFullYear(objInit.svrLastYear);
	$("#month-list").val(endDate.getMonth() + 1);
	$("#year-list").val(endDate.getFullYear());
	

    $.each(gCountryList, function(i, obj) {
        $('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
    });

    $('#country-list').val(gUserCountryId);

    $.each(gProductGroupList, function(i, obj) {
        $('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
    });
    $('#item-group').val(gUserItemGroupId);

    $.each(gReportByList, function(i, obj) {
        $('#report-by').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
    });
    $('#report-by').val(gDetaultOwnerTypeId);


    //	
    gMonthId = $('#month-list').val() == null ? -99 : $('#month-list').val();
    gYearId = $('#year-list').val();
    //alert(gYearId);

    gCountryId = $('#country-list').val();
    gItemGroupId = $('#item-group').val();


    $('#country-list').change(function() {
        gCountryId = $("#country-list").val();
        onBarChartReport();
        nationalSumProducts.fnDraw();
        getReportGeneratePercentage();
       // getItemGroupFrequency();
    });

    $('#item-group').change(function() {
        gItemGroupId = $("#item-group").val();
        onBarChartReport();
        nationalSumProducts.fnDraw();
        getReportGeneratePercentage();
        //getItemGroupFrequency();
    });

    $('#report-by').change(function() {
        onBarChartReport();
        nationalSumProducts.fnDraw();
        getReportGeneratePercentage();
    });

    $("#month-list").change(function() {
        gMonthId = $('#month-list').val();
        endDate.setMonth($("#month-list").val() - 1);
        onBarChartReport();
        nationalSumProducts.fnDraw();
        getReportGeneratePercentage();
    });

    $("#year-list").change(function() {
        gYearId = $('#year-list').val();
        endDate.setYear($("#year-list").val());
        endDate.setMonth($("#month-list").val() - 1);
        onBarChartReport();
        nationalSumProducts.fnDraw();
        getReportGeneratePercentage();
    });

    $("#left-arrow").click(function() {

        if (gFrequencyId == 1) {
            if (endDate.getMonth() == 0 && endDate.getFullYear() == gYearList[0].YearName)
                return;

            endDate.prevMonth();
        } else {
            if (endDate.getMonth() == 2 && endDate.getFullYear() == gYearList[0].YearName)
                return;
            endDate.prevMonths(3);
        }
        $("#month-list").val(endDate.getMonth() + 1);
        $("#year-list").val(endDate.getFullYear());
        onBarChartReport();
        nationalSumProducts.fnDraw();
        getReportGeneratePercentage();
    });

    $("#right-arrow").click(function() {

        if (gFrequencyId == 1) {
            if (endDate.getMonth() == 11 && endDate.getFullYear() == gYearList[gYearList.length - 1].YearName)
                return;
            endDate.nextMonth();
        } else {
            if (endDate.getMonth() == 11 && endDate.getFullYear() == gYearList[gYearList.length - 1].YearName)
                return;
            endDate.nextMonths(3);
        }
        $("#month-list").val(endDate.getMonth() + 1);
        $("#year-list").val(endDate.getFullYear());
        onBarChartReport();
        nationalSumProducts.fnDraw();
        getReportGeneratePercentage();
    });

    nationalSumProducts = $('#tbl-national-sum-products').dataTable({
        "bFilter": false,
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
        "iDisplayLength": 25,
        "sAjaxSource": baseUrl + "report_national_summary_server.php",
        "fnServerData": function(sSource, aoData, fnCallback) {
            aoData.push({
                "name": "action",
                "value": "getSummaryData"
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
                "name": "Year",
                "value": $('#year-list').val()
            });
            aoData.push({
                "name": "Month",
                "value": $('#month-list').val()
            });
            aoData.push({
                "name": "Country",
                "value": $('#country-list').val()
            });
            aoData.push({
                "name": "ItemGroup",
                "value": $('#item-group').val()
            });
            aoData.push({
                "name": "Reportby",
                "value": $('#report-by').val()
            });

            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": function(json) {
                    fnCallback(json);
                }
            });
        },
        "aoColumns": [{
                "bVisible": true,
                "bSortable": false,
                "sWidth": "5%"
            }, {
                "sWidth": "25%"
            }, {
                "sWidth": "14%",
                "sClass": "right-aln",
                "bVisible": false
            }, {
                "sClass": "right-aln",
                "sWidth": "14%",
                "bSortable": true
            }, {
                "sClass": "right-aln",
                "sWidth": "14%",
                "bSortable": true
            }, {
                "sClass": "right-aln",
                "sWidth": "14%",
                "bSortable": true
            }]
    });

	onBarChartReport();
	nationalSumProducts.fnDraw();
	getReportGeneratePercentage();
    //onBarChartReport();
    //getItemGroupFrequency();
});