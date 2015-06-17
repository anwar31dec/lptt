var patientTrendTimeSeries;
var MonthNumber = 3;
var oTableFacility;
var oTableMonthlyStatus;
var gFacilityCode;
var gMonthId;
var gYearId;
var gCountryId;
var TableData = new Array();
var oProfileTable;
var stockoutPieColors = [];
var patientsLineChart;

var gItemGroupId;
var yearList;
var gFrequencyId;
var gSartMonthId;

var startDate = new Date();
var endDate = new Date();
var gStartMonthId;
var gEndMonthId;
var gStartYearId;
var gEndYearId;
var $ = jQuery.noConflict();


function threeMonth() {
	$('#0').addClass('active');
	$('#1').removeClass('active');
	$('#2').removeClass('active');
	$('#3').removeClass('active');
	$('#custom-panel').hide();
	MonthNumber = 3;
	calServer();
}

function sixMonth() {
	$('#0').removeClass('active');
	$('#1').addClass('active');
	$('#2').removeClass('active');
	$('#3').removeClass('active');
	$('#custom-panel').hide();
	MonthNumber = 6;
	calServer();
}

function oneYear() {
	$('#0').removeClass('active');
	$('#1').removeClass('active');
	$('#2').addClass('active');
	$('#3').removeClass('active');
	$('#custom-panel').hide();
	MonthNumber = 12;
	calServer();
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

	$("#start-year-list").val(startDate.getFullYear());

	$("#end-year-list").val(endDate.getFullYear());
	gStartYearId = $('#start-year-list').val();
	gEndYearId = $('#end-year-list').val();

calServer();
	//getItemGroupFrequency();
}
// 
// function getItemGroupFrequency() {
// 
	// if (gCountryId==0){
		// gFrequencyId = 2;
		// gSartMonthId = 3;
		// gStartYearId =2014;
		// getMonthByFrequencyId();
	// }
	// else
	// {
	// $.ajax({
		// type : "POST",
		// dataType : "json",
		// url : baseUrl + "combo_generic.php",
		// data : {
			// "operation" : 'getItemGroupFrequency',
			// "CountryId" : gCountryId,
			// "ItemGroupId" : gItemGroupId
		// },
		// success : function(response) {
			// gFrequencyId = response[0].FrequencyId;
			// gSartMonthId = response[0].StartMonthId;
			// gStartYearId = response[0].StartYearId;
			// getMonthByFrequencyId();
		// }
	// });
	// }
// }
// 
// function getMonthByFrequencyId() {
	// $.ajax({
		// type : "POST",
		// dataType : "json",
		// url : baseUrl + "combo_generic.php",
		// data : {
			// "operation" : 'getMonthByFrequencyId',
			// "FrequencyId" : gFrequencyId
		// },
		// success : function(response) {			
			// $.each(response, function(i, obj) {
				// $('#start-month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
			// });
// 
			// var monthList = response || [];
			// var html = $.map(monthList, function(obj) {
				// return '<option value=' + obj.MonthId + '>' + obj.MonthName + '</option>';
			// }).join('');
// 
			// $('#start-month-list').html(html);
// 
			// if (gFrequencyId == 1){
				// startDate.setMonth(objInit.svrLastMonth - 1);
				// startDate.setFullYear(objInit.svrLastYear);
			// }
			// else if (gFrequencyId == 2) {
				// startDate.setMonth(objInit.svrLastMonth - 1);
				// startDate.setFullYear(objInit.svrLastYear);
				// startDate.lastQuarter();
			// }
// 
			// $("#start-month-list").val(startDate.getMonth() + 1);
			// $("#start-year-list").val(startDate.getFullYear());
// 			
			// gMonthId = $('#start-month-list').val() == null ? -99 : $('#start-month-list').val();
// 
			// //nationalSumProducts.fnDraw();	
// 
		// }
	// });
// }
function calServer()
{
	// onLineChartReport();
	 //onPatientTrendTable();
	
	 getPatientTrendTimeSeriesLineChart();	
	  onPatientTrendTable();
}

function getPatientTrendTimeSeriesLineChart() {
	var patientPercentData = [];
	var sumOfRiskCount = 0;

	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'patient_trend_time_series_server.php',
		data : {
		action : 'getPatientTrendTimeSeriesLineChart',
			MonthNumber : MonthNumber,
			StartMonthId : gStartMonthId,
			EndMonthId : gEndMonthId,
			StartYearId : gStartYearId,
			EndYearId : gEndYearId,
			Country : $('#country-list').val(),
			ItemGroupId	: gItemGroupId,
			lan : lan	
		},
		success : function(response) {			
			$('#patients-line-chart1').highcharts({
				title : {
					//text : 'Last 12 months patients trend',
					text : TEXT['Patient Trend Time Series '] + response.range,
					x : -20 //center
				},
				// subtitle : {
					// text : 'Source: WorldClimate.com',
					// x : -20
				// },
				credits : {
					enabled : false
				},
				colors : response.Colors,
				xAxis : {
					categories : response.Categories
				},
				yAxis : {
					title : {
						text : TEXT['Patients']
					},
					plotLines : [{
						value : 0,
						width : 1,
						color : '#808080'
					}]
				},
				// tooltip : {
					// valueSuffix : '�C'
				// },
				legend : {
					layout : 'vertical',
					align : 'right',
					verticalAlign : 'middle',
					borderWidth : 1
				},
				series : response.Series
			});

		}
	});

}

// 
// function onLineChartReport() {
	// $.ajax({
		// type : "POST",
		// url : baseUrl + "patient_trend_time_series_server.php",
		// data : {
			// action : 'getPatientTrendTimeSeriesChart',
			// MonthNumber : MonthNumber,
			// Country : $('#country-list').val()
		// },
		// success : function(response) {
			// response = $.parseJSON(response);
			// month_name = response.month_name;
			// art_value = response.art;
			// rtk_value = response.rtk;
			// pmtct_value = response.pmtct;
			// name = response.name;
			// onSetLineChart(month_name, art_value, rtk_value, pmtct_value, name);
		// }
	// });
// }
// 
// function onSetLineChart(month_name, art_value, rtk_value, pmtct_value, name) {
	// chart = new Highcharts.Chart({
		// chart : {
			// type : 'spline',
			// borderColor : '#C3DDEC',
			// borderWidth : 1,
			// plotBorderWidth : 1,
			// margin : [50, 100, 50, 100],
			// spacingLeft : 100,
			// height : 500,
			// width : 1250,
			// renderTo : 'patients-line-chart'
		// },
		// colors : ['#9AD268', '#FFC588', '#50ABED'],
		// title : {
			// text : TEXT['Patient Trend Time Series '] + name
		// },
		// subtitle : {
			// text : ''
		// },
		// credits : {
			// enabled : false
		// },
		// xAxis : {
			// title : {
				// text : null
			// },
			// categories : month_name
		// },
		// yAxis : {
			// min : 0,
			// title : {
				// text : '',
				// align : 'high'
			// },
			// labels : {
				// overflow : 'justify'
			// }
		// },
		// tooltip : {
			// shared : true,
			// crosshairs : true
		// },
		// plotOptions : {
			// bar : {
				// dataLabels : {
					// enabled : true
				// }
			// }
		// },
		// legend : {
			// layout : 'vertical',
			// align : 'right',
			// verticalAlign : 'middle',
			// borderWidth : 0
		// },
		// series : [{
			// name : TEXT['ART'],
			// data : art_value
		// }, {
			// name : TEXT['RTK'],
			// data : rtk_value
		// }, {
			// name : TEXT['PMTCT'],
			// data : pmtct_value
		// }]
	// });
// }
// 
function onPatientTrendTable() {

	$('#tbl-pf').html('');
	html = '<table class="table table-striped table-bordered display table-hover" cellspacing="0" id="tbl-patient-trend-time-series">';
	html += '<thead></thead>';
	html += '<tbody></tbody>';
	html += '</table>';
	$('#tbl-pf').html(html);

	$('body').animate({
		opacity : 1
	}, 500, function() {

		$.ajax({
			type : "POST",
			url : baseUrl + "patient_trend_time_series_server.php",
			data : {
				action : 'getPatientTrendTimeSeriesTable',
				MonthNumber : MonthNumber,
				StartMonthId : gStartMonthId,
				EndMonthId : gEndMonthId,
				StartYearId : gStartYearId,
				EndYearId : gEndYearId,
				Country : $('#country-list').val(),
				ItemGroupId	: gItemGroupId,
				lan : lan
			},
			success : function(results) {
				results = $.parseJSON(results);
				patientTrendTimeSeries = $('#tbl-patient-trend-time-series').dataTable({
					"bFilter" : false,
					"bJQueryUI" : true,
					"bSort" : false,
					"bInfo" : false,
					"bPaginate" : false,
					"bSortClasses" : false,
					"bProcessing" : true,
					"bServerSide" : true,
					// 
					"sPaginationType" : "full_numbers",
					"sAjaxSource" : baseUrl + "patient_trend_time_series_server.php",
					"fnDrawCallback" : function(oSettings) {
					},
					"fnServerData" : function(sSource, aoData, fnCallback) {
						aoData.push({
							"name" : "action",
							"value" : 'getPatientTrendTimeSeriesTable'
						});
						aoData.push({
							"name" : "lan",
							"value" : lan
						});
						aoData.push({
							"name" : "baseUrl",
							"value" : baseUrl
						});
						aoData.push({
							"name" : "MonthNumber",
							"value" : MonthNumber
						});
						aoData.push({
							"name" : "Country",
							"value" : $('#country-list').val()
						});
						aoData.push({
							"name" : "ItemGroupId",
							"value" : gItemGroupId
						});
						aoData.push({
							"name" : "StartMonthId",
							"value" : gStartMonthId
						});
						aoData.push({
							"name" : "EndMonthId",
							"value" : gEndMonthId
						});
						aoData.push({
							"name" : "StartYearId",
							"value" : gStartYearId
						});
						aoData.push({
							"name" : "EndYearId",
							"value" : gEndYearId
						});
						$.ajax({
							"dataType" : 'json',
							"type" : "POST",
							"url" : sSource,
							"data" : aoData,
							"success" : function(json) {
								fnCallback(json);
							}
						});
						
						
						
					},
					"aoColumns" : results.COLUMNS
					//"aoColumns" : oColumns
				});
			}
		});
	});
}

$(function() {	
	$.each(gMonthList, function(i, obj) {
		$('#start-month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
		$('#end-month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
	});
	
	$.each(gYearList, function(i, obj) {
		$('#start-year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
		$('#end-year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
	});

	$.each(gCountryList, function(i, obj) {
		$('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
	});
	$('#country-list').val(gUserCountryId);
	
	
	 $.each(gItemGroupList, function(i, obj) {
		 $('#item-group-list').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
		 });
	$('#item-group-list').val(gUserItemGroupId);
	gItemGroupId = $('#item-group-list').val();

	$('#country-list').change(function() {
		gCountryId = $("#country-list").val();
		calServer();
	});
	$('#item-group-list').change(function() {
		gItemGroupId = $('#item-group-list').val();
		calServer();
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

		calServer();
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
		calServer();
	});

	$("#start-month-list").change(function() {
		startDate.setMonth($("#start-month-list").val() - 1);
		gStartMonthId = $('#start-month-list').val();
		gStartYearId = $('#start-year-list').val();
		calServer();
	});

	$("#start-year-list").change(function() {
		startDate.setYear($("#start-year-list").val());
		startDate.setMonth($("#start-month-list").val() - 1);
		gStartMonthId = $('#start-month-list').val();
		gStartYearId = $('#start-year-list').val();
		calServer();
	});

	$("#end-month-list").change(function() {
		endDate.setMonth($("#end-month-list").val() - 1);
		gEndMonthId = $('#end-month-list').val();
		gEndYearId = $('#end-year-list').val();
		calServer();
	});

	$("#end-year-list").change(function() {
		endDate.setYear($("#end-year-list").val());
		endDate.setMonth($("#end-month-list").val() - 1);
		gEndMonthId = $('#end-month-list').val();
		gEndYearId = $('#end-year-list').val();
		calServer();
	});

	////////////////////////////////////////////////////////////////////

	calServer();
});

function printfunction() {
	window.open("<?php echo $baseUrl; ?>report/printProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>&action=getPatientTrendTimeSeriesChart&MonthNumber=" + MonthNumber + "&Country=" + $('#country-list').val());
}

function printfunction1() {
	window.open("<?php echo $baseUrl; ?>report/ExcelProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>&action=getPatientTrendTimeSeriesChart&MonthNumber=3&Country=0");
}

