var startDate = new Date();
var endDate = new Date();
var FacilityStockoutTimeSeries;
var MonthNumber = 3;
var MonthName= '3 Months';
var gStartMonthId;
var gEndMonthId;
var gStartYearId;
var gEndYearId;
var reportTitle='';
var reportTitle1='Patient Overview Report';
var tableTitle='';
var gRegionId = 0;
var gDistrictId = 0;
var gCountryId;
var TABName='';
var $ = jQuery.noConflict();
var tbltitle='';

function threeMonth() {
	$('#0').addClass('active');
	$('#1').removeClass('active');
	$('#2').removeClass('active');
	$('#3').removeClass('active');
    $('#custom-panel').hide();
	MonthNumber = 3;
	//MonthName =' - '+$("#item-group option:selected").text()+' of Last ' + $('#mainTab ul li:nth-child(1)').text();
	MonthName = $("#item-group option:selected").text()+ ' ' +TEXT['Products'] + ' ' + ' of Last ' + $('#0').text();
	onLineChartReport();
	onStockoutTimeSeriesTable();
	TABName= $('#0').text();
	titleChange(TABName); 
}

function sixMonth() {
	$('#0').removeClass('active');
	$('#1').addClass('active');
	$('#2').removeClass('active');
    $('#3').removeClass('active');
    $('#custom-panel').hide();
	MonthNumber = 6;
	//MonthName ='-'+ $("#item-group option:selected").text()+ ' of Last ' + $('#mainTab ul li:nth-child(2)').text();
	MonthName = $("#item-group option:selected").text()+ ' ' +TEXT['Products'] + ' ' + ' of Last ' + $('#1').text();
	onLineChartReport();
	onStockoutTimeSeriesTable();
	
	TABName= $('#1').text();
	titleChange(TABName); 
}

function oneYear() {
	$('#0').removeClass('active');
	$('#1').removeClass('active');
	$('#2').addClass('active');
	$('#3').removeClass('active');
    $('#custom-panel').hide();
	MonthNumber = 12;
	//MonthName ='-'+ $("#item-group option:selected").text()+' of Last ' + $('#mainTab ul li:nth-child(3)').text();
    MonthName = $("#item-group option:selected").text()+ ' ' +TEXT['Products'] + ' ' + ' of Last ' + $('#2').text();
	onLineChartReport();
	onStockoutTimeSeriesTable();
	TABName= $('#2').text();
	titleChange(TABName); 
}

function custom() {
	$('#0').removeClass('active');
	$('#1').removeClass('active');
	$('#2').removeClass('active');
	$('#3').addClass('active');
	$('#custom-panel').show();
    if (MonthNumber==0) return;	
	MonthNumber = 0;
     
    startDate.setMonth(objInit.svrStartMonth - 1);
	startDate.setFullYear(objInit.svrStartYear);
	endDate.setMonth(objInit.svrLastMonth - 1);
	endDate.setFullYear(objInit.svrLastYear);
	
	$("#start-month-list").val(objInit.svrStartMonth); 
	$("#end-month-list").val(objInit.svrLastMonth);
	gStartMonthId = $('#start-month-list').val();
	gEndMonthId = $('#end-month-list').val();

	$.each(gYearList, function(i, obj) {
		$('#start-year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
		$('#end-year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
	});

	$("#start-year-list").val(startDate.getFullYear());
	$("#end-year-list").val(endDate.getFullYear());
    
	gStartYearId = $('#start-year-list').val();
	gEndYearId = $('#end-year-list').val();
    
   
	onLineChartReport();
	onStockoutTimeSeriesTable();
	
	
	MonthName =$("#item-group option:selected").text()+' '+TEXT['Products']+' '+  ' from '+$('#start-month-list option[value='+$('#start-month-list').val()+']').text()+' '+gStartYearId+' to '+$('#end-month-list option[value='+$('#end-month-list').val()+']').text()+' '+gEndYearId;
	//MonthName = $("#item-group option:selected").text()+ ' ' +TEXT['Products'] + ' ' + ' of Last ' + $('#0').text();
	
	var tbltitle=$("#item-group option:selected").text()+' '+TEXT['Products']+' '+ ' from '+ $("#start-month-list option:selected").text()+' '+$('#start-year-list option:selected').text()
	             +' to '+$('#end-month-list option:selected').text()+' '+$('#end-year-list option:selected').text();
	             
	TABName=$('#3').text();             
	titleChange(TABName); 
	
}

/*
function onComboRegionList() {
	$.getJSON(baseUrl + "t_combo.php", {
		action: 'getRegionList',
		CountryId: $('#country-list').val()
	}, function(response) {
		for (var i = 0; i < response.length; i++) {
			$('#region-list').append($('<option></option>').val(response[i].RegionId).html(response[i].RegionName));
		}
	});
}
*/


function getFillRegion() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: baseUrl + "combo_generic.php",
        data: {
            "operation": 'getFillRegion',
            "CountryId": $('#country-list').val(),
            "UserId": userId,
            "lan": lan
        },
        success: function(response) {
            $.each(response, function(i, obj) {
                $('#region-list').append($('<option></option>').val(obj.RegionId).html(obj.RegionName));
            });

            var RegionList = response || [];
            var html = $.map(RegionList, function(obj) {
                return '<option value=' + obj.RegionId + '>' + obj.RegionName + '</option>';
            }).join('');

            $('#region-list').html(html);
            gRegionId = $('#region-list').val() == null ? 0 : $('#region-list').val();
			getFillDistrict();
        }
    });
}

function getFillDistrict() {
	$.ajax({
		type : "POST",
		dataType : "json",
		url : baseUrl + "combo_generic.php",
		data : {
			"operation" : 'getFillDistrict',
			"CountryId": $('#country-list').val(),
			"RegionId" : gRegionId,
			"lan" : lan
		},
		success : function(response) {	
			$.each(response, function(i, obj) {
				$('#District-list').append($('<option></option>').val(obj.DistrictId).html(obj.DistrictName));
			});
			
			var DistrictList = response || [];
			var html = $.map(DistrictList, function(obj) {
				return '<option value=' + obj.DistrictId + '>' + obj.DistrictName + '</option>';
			}).join('');

			$('#District-list').html(html);
			gDistrictId = $('#District-list').val() == null ? 0 : $('#District-list').val();
		}
	});
}

function onLineChartReport() {
	$.ajax({
		type: "POST",
		url: baseUrl + "report_facility_stockout_time_series_server.php",
		data: {
			action: 'getFacilityStockoutTimeSeriesChart',
			MonthNumber: MonthNumber,
			Country: $('#country-list').val(),
            ItemGroupId: $('#item-group').val(),
            RegionId: gRegionId,
			DistrictId: gDistrictId,	
           	StartMonthId: gStartMonthId,
			EndMonthId: gEndMonthId,
			StartYearId: gStartYearId,
			EndYearId: gEndYearId,
			OwnerTypeId : $('#OwnerType').val()
		},
		success: function(response) {
			response = $.parseJSON(response);
			month_name = response.month_name;
			overview_name = response.overview_name;			
			datalist = response.datalist;
            
            var i;
            var seriesCounter = 0;
        	var seriesOptions = [];
            
			for(i = 0; i < overview_name.length; i++) {
                seriesOptions[i] = {
        			name: overview_name[i],
        			data: datalist[i]
		        };
                seriesCounter++;
            }            
    		if (seriesCounter == overview_name.length) {
			     onSetLineChart(month_name, seriesOptions);
    		}         
		}
	});
}

function onSetLineChart(month_name, seriesOptions) {
	chart = new Highcharts.Chart({
		chart: {
			type: 'spline',
			borderColor: '#C3DDEC',
			borderWidth: 1,
			plotBorderWidth: 1,
			margin: [50, 180, 50, 100],
			spacingLeft: 100,
			height: 500,
			renderTo: 'patients-line-chart'
		},
		title: {
			text: tableTitle +' '+ MonthName
		},
		subtitle: {
			text: ''
		},
		credits: {
			enabled: false
		},
		xAxis: {
			title: {
				text: null
			},
			categories: month_name
		},
		yAxis: {
			min: 0,
			max: 100,
			title: {
				text: '',
				align: 'high'
			},
			labels: {
				overflow: 'justify'
			}
		},
		tooltip: {
			shared: true,
			crosshairs: true,
			valueSuffix: '%'
		},
		plotOptions: {
			bar: {
				dataLabels: {
					enabled: true
				}
			}
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle',
			borderWidth: 0
		},
		series: []
	});
    
    $.each(seriesOptions, function(itemNo, item) {
        chart.addSeries({
        	name: item.name,
        	data: item.data
        }, false);   
    });
   	chart.redraw();
}

function onStockoutTimeSeriesTable() {

	$('#tbl-pf').html('');
	html = '<table class="table table-striped table-bordered display table-hover"  cellspacing="0" id="tbl-patient-trend-time-series">';
	html += '<thead></thead>';
	html += '<tbody></tbody>';
	html += '</table>';
	$('#tbl-pf').html(html);

	$('body').animate({
		opacity: 1
	}, 500, function() {

		$.ajax({
			type: "POST",
			url: baseUrl + "report_facility_stockout_time_series_server.php",
			data: {
				action: 'getFacilityStockoutTimeSeriesTable',
				MonthNumber: MonthNumber,
				Country: $('#country-list').val(),
                ItemGroupId: $('#item-group').val(),
                RegionId: gRegionId,
				DistrictId: gDistrictId ,
               	StartMonthId: gStartMonthId,
				EndMonthId: gEndMonthId,
				StartYearId: gStartYearId,
				EndYearId: gEndYearId,
				OwnerTypeId : $('#OwnerType').val(),
				lan:lan
			},
			success: function(results) {
				results = $.parseJSON(results);
				FacilityStockoutTimeSeries = $('#tbl-patient-trend-time-series').dataTable({
					"bFilter": false,
					"bJQueryUI": true,
					"bSort": false,
					"bInfo": false,
					"bPaginate": false,
					"bSortClasses": false,
					"bProcessing": true,
					"bServerSide": true,										
					"sPaginationType": "full_numbers",
					"sAjaxSource": baseUrl + "report_facility_stockout_time_series_server.php",
					"fnDrawCallback": function(oSettings) {},
					"destroy": true,
					"fnServerData": function(sSource, aoData, fnCallback) {
						aoData.push({
							"name": "action",
							"value": 'getFacilityStockoutTimeSeriesTable'
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
							"name": "MonthNumber",
							"value": MonthNumber
						});
     	                aoData.push({
							"name": "StartMonthId",
							"value": gStartMonthId
						});
						aoData.push({
							"name": "EndMonthId",
							"value": gEndMonthId
						});
						aoData.push({
							"name": "StartYearId",
							"value": gStartYearId
						});
						aoData.push({
							"name": "EndYearId",
							"value": gEndYearId
						});
						aoData.push({
							"name": "Country",
							"value": $('#country-list').val()
						});
                        aoData.push({
                            "name": "ItemGroupId",
                            "value": $('#item-group').val()
                        });
                        aoData.push({
                            "name": "RegionId",
                            "value": gRegionId
                        });
						aoData.push({
                            "name": "DistrictId",
                            "value": gDistrictId
                        });
						aoData.push({
                            "name": "OwnerTypeId",
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
					},
					"aoColumns": results.COLUMNS
				});
			}
		});
	});
}

function callServer() {
  MonthName =$("#item-group option:selected").text()+' '+TEXT['Products']+' '+ ' from '+$('#start-month-list option[value='+$('#start-month-list').val()+']').text()+' '+gStartYearId+' to '+$('#end-month-list option[value='+$('#end-month-list').val()+']').text()+' '+gEndYearId;
 
 
    titleChange(TABName);
	onLineChartReport();
	onStockoutTimeSeriesTable();
    
}

function titleChange(monthname){
	 
	 
	if(monthname==$('#3').text()) 
	{
		 MonthName =$("#item-group option:selected").text()+' '+TEXT['Products']+' '+ ' from '+$('#start-month-list option[value='+$('#start-month-list').val()+']').text()+' '+gStartYearId+' to '+$('#end-month-list option[value='+$('#end-month-list').val()+']').text()+' '+gEndYearId;
 
 
	     tbltitle=$("#item-group option:selected").text()+' '+TEXT['Products']+' '+ ' from '+$('#start-month-list option[value='+$('#start-month-list').val()+']').text()+' '+gStartYearId+' to '+$('#end-month-list option[value='+$('#end-month-list').val()+']').text()+' '+gEndYearId;
	   }
	else 
	{
		MonthName =$("#item-group option:selected").text()+ ' ' +TEXT['Products'] + ' ' + ' of Last '+ TABName;

		 tbltitle=$("#item-group option:selected").text()+ ' ' +TEXT['Products'] + ' ' + ' of Last '+ TABName;
	}
	 
	
	
	$('#tableTitle').html(reportTitle+tbltitle);
 
	$('#reportTitle').html(reportTitle + $("#item-group option:selected").text());
	 
	
}

$(function() {
	
	reportTitle=$("#reportTitle").text();
	//tableTitle=$("#tableTitle").text();
    tableTitle = TEXT['Percentage of Facilities Stocked Out with One or More'];//+ $("#item-group option:selected").text()+' '+TEXT['Products of Last 3 Months'];
	
   	$.each(gMonthList, function(i, obj) {
		$('#start-month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
		$('#end-month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
	});
    
	$("#left-arrow").click(function() {
		if (startDate.getMonth() == 0 && startDate.getFullYear() == gYearList[0].YearName)
			return;

		startDate.prevMonth();
		endDate.prevMonth();
		$("#start-month-list").val(startDate.getMonth() + 1);
		$("#start-year-list").val(startDate.getFullYear());
		gStartMonthId = $('#start-month-list').val();
		gStartYearId = $('#start-year-list').val();

		$("#end-month-list").val(endDate.getMonth() + 1);
		$("#end-year-list").val(endDate.getFullYear());
		gEndMonthId = $('#end-month-list').val();
		gEndYearId = $('#end-year-list').val();

		callServer();
	});

    $("#right-arrow").click(function() {
		if (endDate.getMonth() == 11 && endDate.getFullYear() == gYearList[gYearList.length - 1].YearName)
		return;

		startDate.nextMonth();
		endDate.nextMonth();
		$("#start-month-list").val(startDate.getMonth() + 1);
		$("#start-year-list").val(startDate.getFullYear());
		gStartMonthId = $('#start-month-list').val();
		gStartYearId = $('#start-year-list').val();

		$("#end-month-list").val(endDate.getMonth() + 1);
		$("#end-year-list").val(endDate.getFullYear());
		gEndMonthId = $('#end-month-list').val();
		gEndYearId = $('#end-year-list').val();
		callServer();
	});

	$("#start-month-list").change(function() {
		startDate.setMonth($("#start-month-list").val() - 1);
		gStartMonthId = $('#start-month-list').val();
		gStartYearId = $('#start-year-list').val();
		callServer();
	});

	$("#start-year-list").change(function() {
	    startDate.setYear($("#start-year-list").val());
		startDate.setMonth($("#start-month-list").val() - 1);
		gStartMonthId = $('#start-month-list').val();
		gStartYearId = $('#start-year-list').val();
		callServer();
	});

	$("#end-month-list").change(function() {
		endDate.setMonth($("#end-month-list").val() - 1);
		gEndMonthId = $('#end-month-list').val();
		gEndYearId = $('#end-year-list').val();
		callServer();
	});

	$("#end-year-list").change(function() {
		endDate.setYear($("#end-year-list").val());
		endDate.setMonth($("#end-month-list").val() - 1);
		gEndMonthId = $('#end-month-list').val();
		gEndYearId = $('#end-year-list').val();
		callServer();
	});

	$.each(gCountryList, function(i, obj) {
		$('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
	});
	$('#country-list').val(gUserCountryId);
	gCountryId = $("#country-list").val();
	
	$('#country-list').change(function() {
		gCountryId = $("#country-list").val();
		getFillRegion();
        gRegionId = 0;        
		gDistrictId = 0;
		onLineChartReport();
		onStockoutTimeSeriesTable();
	});
   
    $.each(gProductGroupList, function(i, obj) {
		$('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});
    $('#item-group').val(gUserItemGroupId);
	
    $('#item-group').change(function() {
	    onLineChartReport();
		onStockoutTimeSeriesTable();
		titleChange(TABName);
	});
	
	MonthName=$("#item-group option:selected").text()+MonthName;
	TABName= $('#0').text();
	titleChange(TABName);
  /* 
   	//onComboRegionList();
	$.each(gRegionList, function(i, obj) {
		$('#region-list').append($('<option></option>').val(obj.RegionId).html(obj.RegionName));
		getFillDistrict()
	});
*/
	// $.each(gDistrictList, function(i, obj) {
	//	$('#District-list').append($('<option></option>').val(obj.DistrictId).html(obj.DistrictName));
	//});
	getFillRegion();
	$.each(gOwnerTypeList, function(i, obj) {
	$('#OwnerType').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
	});
	$('#OwnerType').val(gDetaultOwnerTypeId);
	
     $('#OwnerType').change(function() {
	    onLineChartReport();
		onStockoutTimeSeriesTable();
	});
	
	$('#region-list').change(function() {
	gRegionId = $("#region-list").val();
	getFillDistrict();
	gDistrictId = 0;
    onLineChartReport();
    onStockoutTimeSeriesTable();
	});
	
	$('#District-list').change(function() {
		gDistrictId = $("#District-list").val();
		onLineChartReport();
		onStockoutTimeSeriesTable();
	});
	//onLineChartReport();
	//onStockoutTimeSeriesTable();
	callServer()
});



