var endDate = new Date();
var MonthNumber = 3;
var stockoutTrendTable;
var MonthName= '3 Months';
var startDate = new Date();
var endDate = new Date();
var gStartMonthId;
var gEndMonthId;
var gStartYearId;
var gEndYearId;
var gItemGroupId;
var gProductGroupList;
var reportTitle='';
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
}

function calServer()
{
		
	 stockoutTrend();	
	 onStockOutTrendTable();
}

function stockoutTrend() {
	var patientPercentData = [];
	var sumOfRiskCount = 0;
	$.ajax({
		type: "POST",
		url: baseUrl + "report_stockouttrend_server.php",
		data: {
			action: 'getStockoutTrendChart',
			MonthNumber: MonthNumber,
			StartMonthId : gStartMonthId,
			EndMonthId : gEndMonthId,
			StartYearId : gStartYearId,
			EndYearId : gEndYearId,
			Country: $('#country-list').val(),
			ItemGroup: $('#item-group').val(),
			lan : lan,
			OwnerTypeId: $('#report-by').val()
			
		},
		success: function(response) {
			vhr = new Array();
			hr = new Array();
			mr = new Array();
			lr = new Array();
			nr = new Array();
			response = JSON.parse(response);
			if (response.categories) {
				name = response.name;
				categories = response.categories;
				VeryHighRisk = response.VeryHighRisk;
				HighRisk = response.HighRisk;
				MediumRisk = response.MediumRisk;
				LowRisk = response.LowRisk;
				NoRisk = response.NoRisk;
				barcolor = response.barcolor;
				areaname = response.areaName;
				var i;
				for (i = 0; i < VeryHighRisk.length; i++) {
					var sumOfRiskCount = (parseInt(VeryHighRisk[i]) + parseInt(HighRisk[i]) + parseInt(MediumRisk[i]) + parseInt(LowRisk[i]) + parseInt(NoRisk[i]));
					var newPercentVHR = (parseFloat(VeryHighRisk[i]) * 100 / sumOfRiskCount).toFixed(1);
					var newPercentHR = (parseFloat(HighRisk[i]) * 100 / sumOfRiskCount).toFixed(1);
					var newPercentMR = (parseFloat(MediumRisk[i]) * 100 / sumOfRiskCount).toFixed(1);
					var newPercentLR = (parseFloat(LowRisk[i]) * 100 / sumOfRiskCount).toFixed(1);
					var newPercentNR = (parseFloat(NoRisk[i]) * 100 / sumOfRiskCount).toFixed(1);

					vhr.push(parseFloat(newPercentVHR));
					hr.push(parseFloat(newPercentHR));
					mr.push(parseFloat(newPercentMR));
					lr.push(parseFloat(newPercentLR));
					nr.push(parseFloat(newPercentNR));
				}
				StockoutTrendArea(name, categories, vhr, hr, mr, lr, nr, barcolor, areaname);
			}
		}
	});
}

function StockoutTrendArea(name, categories, vhr, hr, mr, lr, nr, barcolor, areaname) {
	var chart = new Highcharts.Chart({
		chart: {
			type: 'area',
			height: 500,
			//width: 1280,
			borderColor: '#C3DDEC',
			borderWidth: 1,
			plotBorderWidth: 1,
			margin: [50, 50, 100, 50],
			renderTo: 'area-stockout-trend'
		},
		title: {
			text: reportTitle + ' ' + name
		},
		subtitle: {
			text: ''
		},
		colors: barcolor,
		xAxis: {
			categories: categories,
			tickmarkPlacement: 'on',
			title: {
				enabled: false
			}
		},
		yAxis: {
			title: {
				text: 'Percent'
			}
		},
		tooltip: {
			pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.percentage:.1f}%</b><br/>',
			shared: true
		},
		credits: {
			enabled: false
		},
		plotOptions: {
			area: {
				stacking: 'percent',
				lineColor: '#ffffff',
				lineWidth: 1,
				marker: {
					lineWidth: 1,
					lineColor: '#ffffff'
				}
			}
		},
		series: [{
			name: areaname[0],
			data: vhr
		},
		{
			name: areaname[1],
			data: hr
		},
		{
			name: areaname[2],
			data: mr
		},
		{
			name: areaname[3],
			data: lr
		},
		{
			name: areaname[4],
			data: nr
		}]
	});
}

function onStockOutTrendTable() {

	$('#tbl-pf').html('');
	html = '<table class="table table-hover table-striped" id="tbl-stockout-trend">';
	html += '<thead></thead>';
	html += '<tbody></tbody>';
	html += '</table>';
	$('#tbl-pf').html(html);

	$('body').animate({
		opacity: 1
	}, 500, function() {

		$.ajax({
			type: "POST",
			url: baseUrl + "report_stockouttrend_server.php",
			data: {
				action: 'getStockoutTrendTable',
				MonthNumber: MonthNumber,
				StartMonthId : gStartMonthId,
				EndMonthId : gEndMonthId,
				StartYearId : gStartYearId,
				EndYearId : gEndYearId,
				Country: $('#country-list').val(),
                //OwnerType	:$('#OwnerType').val(),
				ItemGroup	:gProductGroupList,
				lan : lan,
				OwnerTypeId: $('#report-by').val()
			},
			success: function(results) {
				results = $.parseJSON(results);
				stockoutTrendTable = $('#tbl-stockout-trend').dataTable({
					"bFilter": false,
					"bJQueryUI": true,
					"bSort": false,
					"bInfo": false,
					"bPaginate": false,
					"bSortClasses": false,
					"bProcessing": true,
					"bServerSide": true,
					"bDestroy": true,
					"sPaginationType": "full_numbers",
					"sAjaxSource": baseUrl + "report_stockouttrend_server.php",
					"fnDrawCallback": function(oSettings) {},
					"fnServerData": function(sSource, aoData, fnCallback) {
						aoData.push({
							"name": "action",
							"value": 'getStockoutTrendTable'
						});
						
						aoData.push({
							"name" : "baseUrl",
							"value" : baseUrl
						});
						aoData.push({
							"name": "MonthNumber",
							"value": MonthNumber
						});
						aoData.push({
							"name": "Country",
							"value": $('#country-list').val()
						});
						aoData.push({
							"name": "OwnerTypeId",
							"value": $('#report-by').val()
						});
						// aoData.push({
							// "name" : "AFundingSourceId",
							// "value" : $('#fundingSource-list').val()
						// });
						// aoData.push({
							// "name" : "ASStatusId",
							// "value" : $('#status-list').val()
						// });

					/*	aoData.push({
							"name" : "ItemGroupId",
							"value" : gProductGroupList
						});*/
			          /*  aoData.push({
		    				"name" : "OwnerType",
		    				"value" : $('#OwnerType').val()
		    			});*/
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
                        aoData.push({
							"name" : "ItemGroup",
							"value" : gProductGroupList
						});	
						aoData.push({
							"name": "lan",
							"value": lan
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

$(function() {
	
	reportTitle=$("#reportTitle").text();
	
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
	
	$('#country-list').change(function() {
		calServer();
	});
	
 
    
    $.each(gReportByList, function(i, obj) {
		$('#report-by').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
	});
	$('#report-by').val(gDetaultOwnerTypeId);
  
    
    $.each(gProductGroupList, function(i, obj) {
		$('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});
    gProductGroupList = $('#item-group').val();
    
	$('#item-group').val(gUserItemGroupId);

	 $('#item-group').change(function() {	 
		gProductGroupList = $("#item-group").val();
		calServer();
    });
	
	$('#report-by').change(function() {
		calServer();
    });
	// $.each(gFundingSourceList, function(i, obj) {
		// $('#fundingSource-list').append($('<option></option>').val(obj.FundingSourceId).html(obj.FundingSourceName));
	// });
// 
	// $('#fundingSource-list').change(function() {
		// stockoutTrend();
		// onStockOutTrendTable();
	// });


	// $.each(gShipmentStatusList, function(i, obj) {
		// $('#status-list').append($('<option></option>').val(obj.ShipmentStatusId).html(obj.ShipmentStatusDesc));
	// });

    /*  $('#report-by').change(function() {
        gReportByList = $('#report-by').val();
        calServer();
    });
	 /*	$('#OwnerType').change(function() {
    	   calServer();
        });*/
    /*$.each(gItemGroupList, function(i, obj) {
		$('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});*/
	
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

	// $('#status-list').change(function() {
		// stockoutTrend();
	    // onStockOutTrendTable();
	// });
//     

   
	calServer();
});