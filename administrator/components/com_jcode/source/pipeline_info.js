var gMonthId;
var gYearId;
var gCountryId;
var gCountryName = '';
var gItemGroupId;
var dataY = new Array();
var dataColor = new Array();
var stockPipeline;
var endDate = new Date();
var chart;
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
            OwnerTypeId: $('#OwnerType').val(),
            Year: gYearId,
            Month: gMonthId,
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


function getNationalStockPiplineInfo() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: baseUrl + "pipeline_info_server.php",
        data: {
            "operation": 'getNationalStockPiplineInfoChart',
            "MonthId": gMonthId,
            "YearId": gYearId,
            "CountryId": gCountryId,
            "ItemGroupId": gItemGroupId,
            "lan": lan,
            "Reportby": $('#OwnerType').val()
        },
        success: function(response) {

            // chart.xAxis[0].setCategories([2, 4, 5, 6, 7], false);
            //
            // chart.addSeries({
            // name : "acx",
            // data : [4, 5, 6, 7, 8]
            // }, false);

            //chart.xAxis[0].setCategories(response.categories, false);

            //chart.series = response.series;

            //chart.series[0].setData = response.series;

            //chart.colors = response.colors;

            //chart.addSeries({4,5,6,2,2,2,2,2,2,2}, false);

            //chart.series=[{name:'a',data:[12,34]}, {name:'b',data:[56,22]}];
            //chart.xAxis[0].setCategories(['pizza', 'french fries'], false);

            cItemLength = 70 + 45 * (response.Height);

            if (chart)
                chart.destroy();
            chart = new Highcharts.Chart({
                chart: {
                    type: 'bar',
                    borderColor: '#C3DDEC',
                    borderWidth: 1,
                    plotBorderWidth: 1,
                    margin: [50, 50, 100, 380],
                    spacingLeft: 100,
                    height: cItemLength,
                    renderTo: 'bar-chart'
                },
                title: {
                    text: 'Pipeline information of ' + response.MonthYear
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: response.Categories,
                    title: {
                        text: null
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: TEXT['Month Of Supply (MOS)'],
                        align: 'middle'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                legend: {
                    enabled: true,
                    reversed: true
                },
                credits: {
                    enabled: false
                },
                tooltip: {
                    //formatter: function() {
                    //	return this.x + '<br>' + this.series.name+':' + Highcharts.numberFormat(this.y, 2);
                    //}

                    valueSuffix: ' month(s)'
                },
                plotOptions: {
                    series: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true,
                            //format: "{point.y:.1f}",
                            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                        }
                    }

                },
                colors: response.Colors,
                series: response.Series
            });

            //chart.redraw();
        }
    });
}

function getItemGroupFrequency() {

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


            getNationalStockPiplineInfo();
            onPatientTrendTable();
            getReportGeneratePercentage();
        }
    });

}
///////////////
$(function() {


    $.each(gMonthList, function(i, obj) {
		$('#month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
    });

    $.each(gYearList, function(i, obj) {
        $('#year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
    });

	endDate.setMonth(objInit.svrLastMonth - 1);
	endDate.setFullYear(objInit.svrLastYear);
	$("#month-list").val(endDate.getMonth() + 1);
	$("#year-list").val(endDate.getFullYear());

	gMonthId = $('#month-list').val() == null ? -99 : $('#month-list').val();

    gYearId = $('#year-list').val();

    $.each(gCountryListFLevel, function(i, obj) {
        $('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
    });

    $('#country-list').val(gUserCountryId);

    gCountryId = $('#country-list').val();

    $.each(gItemGroupList, function(i, obj) {
        $('#item-group-list').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
    });

    $('#item-group-list').val(gUserItemGroupId);
    gItemGroupId = $('#item-group-list').val();

    $.each(gOwnerTypeList, function(i, obj) {
        $('#OwnerType').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
    });
    $('#OwnerType').val(gDetaultOwnerTypeId);

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

        gMonthId = $("#month-list").val();
        gYearId = $("#year-list").val();

        getNationalStockPiplineInfo();
        onPatientTrendTable();
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
        gMonthId = $("#month-list").val();
        gYearId = $("#year-list").val();

        getNationalStockPiplineInfo();
        onPatientTrendTable();
        getReportGeneratePercentage();
    });

    $("#month-list").change(function() {
        endDate.setMonth($("#month-list").val() - 1);

        gMonthId = $("#month-list").val();
        gYearId = $("#year-list").val();

        getNationalStockPiplineInfo();
        onPatientTrendTable();
        getReportGeneratePercentage();
    });

    $("#year-list").change(function() {
        endDate.setYear($("#year-list").val());
        endDate.setMonth($("#month-list").val() - 1);

        gMonthId = $("#month-list").val();
        gYearId = $("#year-list").val();

        getNationalStockPiplineInfo();
        //stockPipeline.fnDraw();
        onPatientTrendTable();
        getReportGeneratePercentage();
    });
    gCountryId = $("#country-list").val();

    $("#country-list").change(function() {
        gCountryId = $("#country-list").val();
        getNationalStockPiplineInfo();
		onPatientTrendTable();
		getReportGeneratePercentage();
    });

    $("#item-group-list").change(function() {
        gItemGroupId = $("#item-group-list").val();
        getNationalStockPiplineInfo();
		onPatientTrendTable();
		getReportGeneratePercentage();
    });

    $("#OwnerType").change(function() {
        getNationalStockPiplineInfo();
        onPatientTrendTable();
        getReportGeneratePercentage();
    });

	getNationalStockPiplineInfo();
	onPatientTrendTable();
	getReportGeneratePercentage();


    /*$('body').animate({
     opacity: 1
     }, 500, function() {
     
     });
     
     */
});


function onPatientTrendTable() {
    $('body').animate({
        opacity: 1
    }, 500, function() {

        $.ajax({
            type: "POST",
            url: baseUrl + "pipeline_info_server.php",
            data: {
                operation: 'getNationalStockPiplineInfoTable',
                lan: lan,
                MonthId: $('#month-list').val(),
                CountryId: $('#country-list').val(),
                YearId: $('#year-list').val(),
                ItemGroupId: $('#item-group-list').val(),
                Reportby: $('#OwnerType').val()

            },
            success: function(results) {
                results = $.parseJSON(results);

                var shipmentColumnNo = results.COLUMNS.length;
                var shipmentColumnNoQtyMos = shipmentColumnNo * 2;
                $('#tbl-pf').html('');
                html = '<table class="table table-striped table-bordered display table-hover" cellspacing="0" id="tbl-facility-sum-list">';
                html += '<thead>';


                if (shipmentColumnNo == 0) {
                    html += '<tr><th style="text-align:center; width:5%;">SL.</th><th class="productname" style="text-align:center;">' + TEXT['Products'] + '</th><th  style="text-align:center; width:10%;">' + TEXT['AMC'] + '</th>';
                    html += '<th style="text-align:center;">' + TEXT['Available Stock'] + '</th><th style="text-align:center; width:10%;">' + TEXT['MOS(Available)'] + '</th>';
                    html += '<th style="text-align:center;">' + TEXT['Total MOS'] + '</th></tr>'
                }
                else {
                    html += '<tr><th rowspan="3" style="text-align:center;" >SL.</th><th rowspan="3" class="productname" style="text-align:center;">' + TEXT['Products'] + '</th><th rowspan="3" style="text-align:center;">' + TEXT['AMC'] + '</th>';
                    html += '<th rowspan="3" style="text-align:center;">' + TEXT['Available Stock'] + '</th><th rowspan="3" style="text-align:center;">' + TEXT['MOS(Available)'] + '</th>';
                    html += '<th colspan="' + shipmentColumnNoQtyMos + '" style="text-align:center;">' + TEXT['Shipment Qty'] + '</th>';
                    html += '<th rowspan="3" style="text-align:center;">' + TEXT['Total MOS'] + '</th></tr>'

                    html += '<tr>'
                    for (var i = 0; i < shipmentColumnNo; i++) {
                        html += '<th colspan="2" style="text-align:center;">' + results.COLUMNS[i] + '</th>';
                    }
                    html += '</tr>'

                    html += '<tr>'
                    for (var i = 0; i < shipmentColumnNo; i++) {
                        html += '<th style="text-align:center;">' + TEXT['Qty'] + '</th>';
                        html += '<th style="text-align:center;">' + TEXT['MOS'] + '</th>';
                    }
                    html += '</tr>'

                }


                html += '</thead>';
                html += '<tbody></tbody>';
                html += '</table>';
                $('#tbl-pf').html(html);

                stockPipeline = $('#tbl-facility-sum-list').dataTable({
                    "bFilter": false,
                    "bJQueryUI": true,
                    "bSort": true,
                    "bInfo": false,
                    "bPaginate": false,
                    "bSortClasses": false,
                    "bProcessing": true,
                    "bServerSide": false,
                    "aaSorting": [[1, 'asc']],
                    "sPaginationType": "full_numbers",
                    "aLengthMenu": [[25, 50, 100], [25, 50, 100]],
                    "iDisplayLength": 25,
                    "sAjaxSource": baseUrl + "pipeline_info_server.php",
                    "fnDrawCallback": function(oSettings) {


                    },
                    "fnServerData": function(sSource, aoData, fnCallback) {
                        aoData.push({
                            "name": "operation",
                            "value": 'getNationalStockPiplineInfoTable'
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
                            "name": "MonthId",
                            "value": $('#month-list').val()
                        });
                        aoData.push({
                            "name": "CountryId",
                            "value": $('#country-list').val()
                        });
                        aoData.push({
                            "name": "YearId",
                            "value": $('#year-list').val()
                        });
                        aoData.push({
                            "name": "ItemGroupId",
                            "value": $('#item-group-list').val()
                        });
                        aoData.push({
                            "name": "Reportby",
                            "value": $('#OwnerType').val()
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
                    }
                });
            }
        });
    });
}
