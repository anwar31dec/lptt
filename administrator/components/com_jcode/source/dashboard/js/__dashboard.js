var oTableFacility;
var oTableMonthlyStatus;
var gFacilityCode;
var gMonthId;
var gYearId;
var gCountryId;
var TableData = new Array();
var endDate = new Date();
var oProfileTable;
var stockoutPieColors = [];
var patientsLineChart;

var gItemGroupId;
var yearList;
var gFrequencyId;
var gStartYearId;
var gSartMonthId;

var dataY = new Array();
var dataColor = new Array();
var nationalSumProducts;
var chart;

function addCommas(nStr) {
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function getMosTypeProductCount() {
	var stockoutPercentData = [];
	var sumOfRiskCount = 0;

	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'dashboard_server.php',
		data : {
			"operation" : 'getMosTypeProductCount',
			"MonthId" : gMonthId,
			"YearId" : gYearId,
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId,
			"lan" : lan
		},
		success : function(response) {

			$.each(response, function(i, obj) {
				sumOfRiskCount += parseInt(obj.RiskCount);
			});

			$.each(response, function(i, obj) {

				stockoutPieColors[i] = obj.ColorCode;

				if (sumOfRiskCount > 0) {
					stockoutPercentData[i] = {
						"label" : obj.MosTypeName,
						"value" : (obj.RiskCount * 100 / sumOfRiskCount).toFixed(1)
					};
				} else {
					stockoutPercentData[i] = {
						"label" : obj.MosTypeName,
						"value" : 0
					};
				}
			});

			var stockoutPieChart = Morris.Donut({
				element : 'stockout-pie-chart',
				data : stockoutPercentData,
				colors : stockoutPieColors,
				formatter : function(x) {					
					return x + "%";
				}
			});

			// $(".very-highrisk .value").text(stockoutPercentData[0].value + '%');
			// $(".highrisk .value").text(stockoutPercentData[1].value + '%');
			// $(".mediumrisk .value").text(stockoutPercentData[2].value + '%');
			// $(".lowrisk .value").text(stockoutPercentData[3].value + '%');
			// $(".norisk .value").text(stockoutPercentData[4].value + '%');
		}
	});
}

function getCurrentPatients() {
	var patientPercentData = [];
	var sumOfRiskCount = 0;

	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'dashboard_server.php',
		data : {
			"operation" : 'getCurrentPatients',
			"MonthId" : gMonthId,
			"YearId" : gYearId,
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId,
			"lan" : lan
		},
		success : function(response) {

			$.each(response, function(i, obj) {
				patientPercentData[i] = {
					"label" : obj.ServiceTypeName,
					"value" : obj.TotalPatient
				};
			});

			var patientsPieChart = Morris.Donut({
				element : 'patients-pie',
				data : patientPercentData,
				colors : ['#D7191C', '#FE9929', '#F0F403', '#50ABED'],
				formatter : function(x) {
					return x + "%";
				}
			});

			// $("#art-value-id").text(addCommas(patientPercentData[0].value));
			// $("#rtk-value-id").text(addCommas(patientPercentData[1].value));
			// $("#pmtct-value-id").text(addCommas(patientPercentData[2].value));
		}
	});
}

function getSeverePatients() {
	var patientPercentData = [];
	var sumOfRiskCount = 0;

	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'dashboard_server.php',
		data : {
			"operation" : 'getSeverePatients',
			"MonthId" : gMonthId,
			"YearId" : gYearId,
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId,
			"lan" : lan
		},
		success : function(response) {

			$.each(response, function(i, obj) {
				patientPercentData[i] = {
					"label" : obj.ServiceTypeName,
					"value" : obj.TotalPatient
				};
			});

			var patientsPieChart = Morris.Donut({
				element : 'severe-pie',
				data : patientPercentData,
				colors : ['#D7191C', '#FE9929', '#F0F403', '#50ABED'],
				formatter : function(x) {
					return x + "%";
				}
			});

			// $("#art-value-id").text(addCommas(patientPercentData[0].value));
			// $("#rtk-value-id").text(addCommas(patientPercentData[1].value));
			// $("#pmtct-value-id").text(addCommas(patientPercentData[2].value));
		}
	});
}


function getPatientTrendTimeSeries() {
	var patientPercentData = [];
	var sumOfRiskCount = 0;

	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'dashboard_server.php',
		data : {
			"operation" : 'getPatientTrendTimeSeries',
			"MonthId" : gMonthId,
			"YearId" : gYearId,
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId,
			"FrequencyId" : gFrequencyId,
			"lan" : lan
		},
		success : function(response) {

			//console.log(response);

			//patientsLineChart.

			//$("#patients-line-chart").remove();

			//$("#wrap-line-chart").add("div");

			//$("#wrap-line-chart").append('<div id ="patients-line-chart" style="height: 250px; position: relative;"></div>');

			//$( "em" ).attr( "title" );

			//wrap-line-chart
			//$( "#patients-line-chart" ).remove();

			//patientsLineChart.setData(response.data);

			// patientsLineChart = new Morris.Line({
			// element : 'patients-line-chart',
			// data : response.data,
			// // xkey : response.xkey,
			// // ykeys : response.ykeys,
			// // labels : response.labels,
			// // lineColors : response.lineColors,
			// xkey : 'YearMonth',
			// ykeys : ['ART', 'RTK', 'PMTCT'],
			// labels : ['ART', 'RTK', 'PMTCT'],
			// lineColors : ['#FFC545', '#9AD268', '#50ABED'],
			// gridTextColor : '#777777',
			// resize : true
			// });

			// var chart = new Highcharts.Chart({
			// chart : {
			// type : 'spline',
			// borderColor : '#C3DDEC',
			// borderWidth : 1,
			// plotBorderWidth : 1,
			//
			// renderTo : 'patients-line-chart'
			// },
			// colors : response.Colors,
			// title : {
			// text : response.MonthYear
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
			// categories : response.Categories
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
			// series : response.Series
			// });

			$('#patients-line-chart').highcharts({
				chart : {
					backgroundColor : '#2e9be2',
					spacingTop : 5,
					spacingBottom : 5,
					spacingLeft : 5,
					spacingRight : 5,
				},
				title : {
					text : 'Patient Trend',
					style : {
						"color" : "#fff"
					}

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
					lineColor : '#ffffff',
					startOnTick : true,
					categories : response.Categories
				},
				yAxis : {
					title : {
						text : ''
					},
					gridLineColor : '#ffffff',
					gridLineWidth : .5,

					plotLines : [{
						value : 0,
						width : 1,
						color : '#808080'
					}]
				},
				// tooltip : {
				// valueSuffix : '°C'
				// },
				legend : {
					layout : 'vertical',
					align : 'right',
					verticalAlign : 'middle',
					borderWidth : 0,
					enabled : false
				},
				series : response.Series
			});

		}
	});

	// $('#patients-line-chart').highcharts({
	// title : {
	// text : 'Monthly Average Temperature',
	// x : -20 //center
	// },
	// subtitle : {
	// text : 'Source: WorldClimate.com',
	// x : -20
	// },
	// xAxis : {
	// categories : response.Categories
	// },
	// yAxis : {
	// title : {
	// text : 'Temperature (°C)'
	// },
	// plotLines : [{
	// value : 0,
	// width : 1,
	// color : '#808080'
	// }]
	// },
	// tooltip : {
	// valueSuffix : '°C'
	// },
	// legend : {
	// layout : 'vertical',
	// align : 'right',
	// verticalAlign : 'middle',
	// borderWidth : 0
	// },
	// series : [{
	// name : 'Tokyo',
	// data : [7.0, 6.9]
	// }, {
	// name : 'New York',
	// data : [-0.2, 0.8]
	// }, {
	// name : 'Berlin',
	// data : [-0.9, 0.6]
	// }, {
	// name : 'London',
	// data : [3.9, 4.2]
	// }]
	// });
}

function getPatientAtRisk() {
	var patientPercentData = [];
	var sumOfRiskCount = 0;

	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'dashboard_server.php',
		data : {
			"operation" : 'getPatientAtRisk',
			"MonthId" : gMonthId,
			"YearId" : gYearId,
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId,
			"lan" : lan
		},
		success : function(response) {
			//patientsLineChart.
			//console.log(response.data);
		}
	});
}

function formatNumber(n) {
	return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

var oProductStockStatus = $('#product-stock-status').dataTable({
	"bFilter" : false,
	"bJQueryUI" : false,
	"bSort" : false,
	"bInfo" : false,
	"bPaginate" : false,
	"bSortClasses" : false,
	"bProcessing" : false,
	"bServerSide" : true,
	"aaSorting" : [[0, 'asc']],
	"sPaginationType" : "full_numbers",
	"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
	"iDisplayLength" : 15,
	"sAjaxSource" : baseUrl + "dashboard_server.php",
	"fnServerData" : function(sSource, aoData, fnCallback) {
		aoData.push({
			"name" : "operation",
			"value" : "getMosTypeProductBullet"
		});
		aoData.push({
			"name" : "BaseUrl",
			"value" : baseUrl
		});
		aoData.push({
			"name" : "MonthId",
			"value" : gMonthId
		});
		aoData.push({
			"name" : "Year",
			"value" : gYearId
		});
		aoData.push({
			"name" : "CountryId",
			"value" : gCountryId
		});
		aoData.push({
			"name" : "ItemGroupId",
			"value" : gItemGroupId
		});
		aoData.push({
			"name" : "lan",
			"value" : lan
		});

		$.ajax({
			"dataType" : 'json',
			"type" : "GET",
			"url" : sSource,
			"data" : aoData,
			"success" : function(json) {
				fnCallback(json);
			}
		});
	},
	"aoColumns" : [{
		"sClass" : "left-aln",
		"bVisible" : true,
		"bSortable" : false,
		"sWidth" : "100%",
		"bSearchable" : false
	}]
});

var oSeverePatients = $('#severe-patients').dataTable({
	"bFilter" : false,
	"bJQueryUI" : false,
	"bSort" : false,
	"bInfo" : false,
	"bPaginate" : false,
	"bSortClasses" : false,
	"bProcessing" : false,
	"bServerSide" : true,
	"aaSorting" : [[0, 'asc']],
	"sPaginationType" : "full_numbers",
	"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
	"iDisplayLength" : 15,
	"sAjaxSource" : baseUrl + "dashboard_server.php",
	"fnServerData" : function(sSource, aoData, fnCallback) {
		aoData.push({
			"name" : "operation",
			"value" : "getSeverePatientsTable"
		});
		aoData.push({
			"name" : "BaseUrl",
			"value" : baseUrl
		});
		aoData.push({
			"name" : "MonthId",
			"value" : gMonthId
		});
		aoData.push({
			"name" : "Year",
			"value" : gYearId
		});
		aoData.push({
			"name" : "CountryId",
			"value" : gCountryId
		});

		aoData.push({
			"name" : "ItemGroupId",
			"value" : gItemGroupId
		});
		aoData.push({
			"name" : "lan",
			"value" : lan
		});
		
		$.ajax({
			"dataType" : 'json',
			"type" : "GET",
			"url" : sSource,
			"data" : aoData,
			"success" : function(json) {
				fnCallback(json);
			}
		});
	},
	"aoColumns" : [{
		"sClass" : "left-aln",
		"bVisible" : true,
		"bSortable" : false,
		"sWidth" : "100%",
		"bSearchable" : false
	}

	// , {
	// "sClass" : "center-aln",
	// "sWidth" : "80%",
	// "bSortable" : false,
	// "bSearchable" : true
	//
	// }, {
	// "sClass" : "right-aln",
	// "sWidth" : "10%",
	// "bSortable" : false,
	// "bSearchable" : true
	// }

	]
});

var oCurrentPatients = $('#current-patients').dataTable({
	"bFilter" : false,
	"bJQueryUI" : false,
	"bSort" : false,
	"bInfo" : false,
	"bPaginate" : false,
	"bSortClasses" : false,
	"bProcessing" : false,
	"bServerSide" : true,
	"aaSorting" : [[0, 'asc']],
	"sPaginationType" : "full_numbers",
	"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
	"iDisplayLength" : 15,
	"sAjaxSource" : baseUrl + "dashboard_server.php",
	"fnServerData" : function(sSource, aoData, fnCallback) {
		aoData.push({
			"name" : "operation",
			"value" : "getCurrentPatientsTable"
		});
		aoData.push({
			"name" : "BaseUrl",
			"value" : baseUrl
		});
		aoData.push({
			"name" : "MonthId",
			"value" : gMonthId
		});
		aoData.push({
			"name" : "Year",
			"value" : gYearId
		});
		aoData.push({
			"name" : "CountryId",
			"value" : gCountryId
		});

		aoData.push({
			"name" : "ItemGroupId",
			"value" : gItemGroupId
		});
aoData.push({
			"name" : "lan",
			"value" : lan
		});
		$.ajax({
			"dataType" : 'json',
			"type" : "GET",
			"url" : sSource,
			"data" : aoData,
			"success" : function(json) {
				fnCallback(json);
			}
		});
	},
	"aoColumns" : [{
		"sClass" : "left-aln",
		"bVisible" : true,
		"bSortable" : false,
		"sWidth" : "100%",
		"bSearchable" : false
	}

	// , {
	// "sClass" : "center-aln",
	// "sWidth" : "80%",
	// "bSortable" : false,
	// "bSearchable" : true
	//
	// }, {
	// "sClass" : "right-aln",
	// "sWidth" : "10%",
	// "bSortable" : false,
	// "bSearchable" : true
	// }

	]
});

var oSimpleVsSevere = $('#simple-vs-severe').dataTable({
	"bFilter" : false,
	"bJQueryUI" : false,
	"bSort" : false,
	"bInfo" : false,
	"bPaginate" : false,
	"bSortClasses" : false,
	"bProcessing" : true,
	"bServerSide" : true,
	"aaSorting" : [[0, 'asc']],
	"sPaginationType" : "full_numbers",
	"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
	"iDisplayLength" : 15,
	"sAjaxSource" : baseUrl + "dashboard_server.php",
	"fnServerData" : function(sSource, aoData, fnCallback) {
		aoData.push({
			"name" : "operation",
			"value" : "getSimpleVsSevere"
		});
		aoData.push({
			"name" : "BaseUrl",
			"value" : baseUrl
		});
		aoData.push({
			"name" : "MonthId",
			"value" : gMonthId
		});
		aoData.push({
			"name" : "Year",
			"value" : gYearId
		});
		aoData.push({
			"name" : "CountryId",
			"value" : gCountryId
		});
		aoData.push({
			"name" : "ItemGroupId",
			"value" : gItemGroupId
		});
aoData.push({
			"name" : "lan",
			"value" : lan
		});
		$.ajax({
			"dataType" : 'json',
			"type" : "GET",
			"url" : sSource,
			"data" : aoData,
			"success" : function(json) {
				fnCallback(json);
			}
		});
	},
	"aoColumns" : [{
		"sClass" : "center-aln",
		"bVisible" : true,
		"bSortable" : false,
		"sWidth" : "100%",
		"bSearchable" : false
	}

	// , {
	// "sClass" : "center-aln",
	// "sWidth" : "80%",
	// "bSortable" : false,
	// "bSearchable" : true
	//
	// }, {
	// "sClass" : "right-aln",
	// "sWidth" : "10%",
	// "bSortable" : false,
	// "bSearchable" : true
	// }

	]
});

function getTotalPatients() {
	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'dashboard_server.php',
		data : {
			"operation" : 'getTotalPatients',
			"MonthId" : gMonthId,
			"YearId" : gYearId,
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId,
			"lan" : lan
		},
		success : function(response) {
			// console.log(response[0]);
			// alert(response[0].TotalPatient);
			$('#total-patients').text(response[0].TotalPatient);
		}
	});
}

function getMaleFemale() {
	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'dashboard_server.php',
		data : {
			"operation" : 'getMaleFemale',
			"MonthId" : gMonthId,
			"YearId" : gYearId,
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId,
			"lan" : lan
		},
		success : function(response) {
			// console.log(response[0]);
			// alert(response[0].TotalPatient);
			$('#male-id').text(response[0].TotalPerc + '%');
			$('#female-id').text(response[1].TotalPerc + '%');
		}
	});
}

function getSimpleVsSevere1() {
	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'dashboard_server.php',
		data : {
			"operation" : 'getSimpleVsSevere1',
			"MonthId" : gMonthId,
			"YearId" : gYearId,
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId,
			"lan" : lan
		},
		success : function(response) {
			// console.log(response[0]);
			// alert(response[0].TotalPatient);

			var $td = $('#simple-vs-severe-div table tr td');

			$('#simple-vs-severe-div table tr').each(function(index, element) {
				
				if(index == 0){	
					$('td:eq(0)', this).each(function() {
						$('p:eq(0)', this).text(response[0].TotalPerc + "%");
					});
					$('td:eq(1)', this).each(function() {						
						$('p:eq(0)', this).text(response[1].TotalPerc + "%");
					});
				}
				
				if(index == 1){			
					$('td:eq(0)', this).attr({
						"width" : response[0].TotalPerc + "%"
					});
					$('td:eq(1)', this).attr({
						"width" : response[1].TotalPerc + "%"
					});
				}
				
				if(index == 2){	
					$('td:eq(0)', this).each(function() {
						$('p:eq(0)', this).text(response[0].FormulationName);
					});
					$('td:eq(1)', this).each(function() {				
						$('p:eq(0)', this).text(response[1].FormulationName);
					});
				}

			});

			//$td.eq(1).width(95);
			//$td.eq(2).width(5);

		}
	});
}

function getMaleFemale() {
	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'dashboard_server.php',
		data : {
			"operation" : 'getMaleFemale',
			"MonthId" : gMonthId,
			"YearId" : gYearId,
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId,
			"lan" : lan
		},
		success : function(response) {
			// console.log(response[0]);
			// alert(response[0].TotalPatient);
			$('#male-id').text(response[0].TotalPerc + '%');
			$('#female-id').text(response[1].TotalPerc + '%');
		}
	});
}

function RenderDecimalNumber(oObj) {
	var num = new NumberFormat();
	num.setInputDecimal('.');
	num.setNumber(oObj.aData[oObj.iDataColumn]);
	num.setPlaces(this.oCustomInfo.decimalPlaces, true);
	num.setCurrency(false);
	num.setNegativeFormat(num.LEFT_DASH);
	num.setSeparators(true, this.oCustomInfo.decimalSeparator, this.oCustomInfo.thousandSeparator);

	return num.toFormatted();
}

function setInventoryLink() {
	$('#mostypeid1').attr('href', 'index.php/national-level-reports/national-inventory-control?MosTypeId=1&MonthId=' + gMonthId + '&YearId=' + gYearId + '&CountryId=' + gCountryId);
	$('#mostypeid2').attr('href', 'index.php/national-level-reports/national-inventory-control?MosTypeId=2&MonthId=' + gMonthId + '&YearId=' + gYearId + '&CountryId=' + gCountryId);
	$('#mostypeid3').attr('href', 'index.php/national-level-reports/national-inventory-control?MosTypeId=3&MonthId=' + gMonthId + '&YearId=' + gYearId + '&CountryId=' + gCountryId);
	$('#mostypeid4').attr('href', 'index.php/national-level-reports/national-inventory-control?MosTypeId=4&MonthId=' + gMonthId + '&YearId=' + gYearId + '&CountryId=' + gCountryId);
	$('#mostypeid5').attr('href', 'index.php/national-level-reports/national-inventory-control?MosTypeId=5&MonthId=' + gMonthId + '&YearId=' + gYearId + '&CountryId=' + gCountryId);
}

function callServer() {
	getTotalPatients();
	//getMaleFemale();
	getSimpleVsSevere1();
	oCurrentPatients.fnDraw();
	//oPopulationRisk.fnDraw();
	oProductStockStatus.fnDraw();
}

function getItemGroupFrequency() {

	if (gCountryId == 0) {
		gFrequencyId = 2;
		gSartMonthId = 3;
		gStartYearId = 2014;
		getMonthByFrequencyId();
	} else {
		$.ajax({
			type : "POST",
			dataType : "json",
			url : baseUrl + "combo_generic.php",
			data : {
				"operation" : 'getItemGroupFrequency',
				"CountryId" : gCountryId,
				"ItemGroupId" : gItemGroupId,
				"lan" : lan
			},
			success : function(response) {
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
		type : "POST",
		dataType : "json",
		url : baseUrl + "combo_generic.php",
		data : {
			"operation" : 'getMonthByFrequencyId',
			"FrequencyId" : gFrequencyId,
			"lan" : lan
		},
		success : function(response) {
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
			} else if (gFrequencyId == 2) {
				endDate.setMonth(objInit.svrLastMonth - 1);
				endDate.setFullYear(objInit.svrLastYear);
				endDate.lastQuarter();
			}

			$("#month-list").val(endDate.getMonth() + 1);
			$("#year-list").val(endDate.getFullYear());

			gMonthId = $('#month-list').val() == null ? -99 : $('#month-list').val();

			getMosTypeProductCount();
			getCurrentPatients();
			//getPatientTrendTimeSeries();
			oCurrentPatients.fnDraw();
			//oPopulationRisk.fnDraw();
			oProductStockStatus.fnDraw();
			setInventoryLink();

			getTotalPatients();
			//getMaleFemale();
			getSimpleVsSevere1();
		}
	});
}

function onBarChartReport() {
	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'report_national_summary_server.php',
		data : {
			action : 'getSummaryChart',
			lan : 'engb',
			Year : '2014',
			Month : '5',
			Country : '3',
			ItemGroup : '1',
			"lan" : lan
		},
		success : function(response) {
			// item_name = response.item_name;
			// var item_value = response.temp;
			// var barcolor = response.barcolor;
			// var name = response.name;
			// var cItemLength = 70 +30 *50;
			// onSetBarChart(item_name, item_value, barcolor, name, cItemLength);

			$('#stock-bar-chart').highcharts({
				chart : {
					type : 'bar'
				},
				title : {
					text : TEXT['National Summary Report of July, 2014']
				},
				// subtitle: {
				// text: 'Source: Wikipedia.org'
				// },
				xAxis : {
					categories : ['AL 1x6', 'AL 2x6', 'AL 3x6', 'AL 4x6'],
					title : {
						text : null
					}
				},
				yAxis : {
					min : 0,
					title : {
						text : TEXT['MOS'],
						align : 'high'
					},
					labels : {
						overflow : 'justify'
					}
				},
				tooltip : {
					valueSuffix : TEXT['MOS']
				},
				plotOptions : {
					bar : {
						dataLabels : {
							enabled : true
						}
					}
				},
				legend : {
					layout : 'vertical',
					align : 'right',
					verticalAlign : 'top',
					x : -40,
					y : 100,
					floating : true,
					borderWidth : 1,
					// backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					shadow : true
				},
				credits : {
					enabled : false
				},

				plotOptions : {
					column : {
						colorByPoint : true
					}
				},
				series : [{
					name : TEXT['July 2014'],
					data : [{
						y : 17.1,
						color : '#4DAC26'
					}, {
						y : 12.5,
						color : '#4DAC26'
					}, {
						y : 7.8,
						color : '#FE9929'
					}, {
						y : 20.9,
						color : '#50ABED'
					}]
				}]
			});

		}
	});
}

function onSetBarChart(item_name, item_value, barcolor, name, cItemLength) {
	chart = new Highcharts.Chart({
		chart : {
			type : 'bar',
			borderColor : '#C3DDEC',
			borderWidth : 1,
			plotBorderWidth : 1,
			margin : [50, 50, 50, 350],
			//spacingLeft: 50,
			height : cItemLength,
			renderTo : 'stock-bar-chart'
		},
		title : {
			text : TEXT['National Summary Report of '] + name
		},
		subtitle : {
			text : ''
		},
		xAxis : {
			categories : item_name,
			title : {
				text : null
			}
		},
		yAxis : {
			title : {
				text : '',
				align : 'high'
			},
			labels : {
				overflow : 'justify'
			}
		},
		tooltip : {
			valueSuffix : ' month(s)'
		},
		plotOptions : {
			bar : {
				dataLabels : {
					enabled : true
				}
			}
		},
		legend : {
			enabled : false
		},
		credits : {
			enabled : false
		},
		series : [{
			name : name,
			data : item_value,
			tooltip : {
				valueDecimals : ' month(s)'
			},
			point : {
				events : {
					mouseOver : function() {
						for (var i = 0; i < dataY.length; i++) {
							if (dataY[i] == this.y)
								this.update({
									color : dataColor[i]
								});
						}
					}
				}
			},
			pointRange : 1.75,
			events : {
				mouseOver : function(i, point) {

				},
				mouseOut : function() {
				}
			}
		}]
	});

	k = 0;
	$.each(chart.series[0].data, function(i, point) {
		value = point.y;
		point.graphic.attr({
			fill : barcolor[k]
		});
		dataY.push(point.y);
		dataColor.push(barcolor[k]);
		k++;
	});

	/************************HighChart Pdf****************************/

}

$(function() {

	//	$.each(gMonthListbydashboard, function(i, obj) {
	//		$('#month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
	//	});

	endDate.setMonth(objInit.svrLastMonth - 1);
	$("#month-list").val(objInit.svrLastMonth);
	gMonthId = $('#month-list').val();

	$.each(gYearList, function(i, obj) {
		$('#year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
	});

	$("#year-list").val(endDate.getFullYear());

	gYearId = $('#year-list').val();

	$.each(gCountryList, function(i, obj) {
		$('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
	});

	$('#country-list').val(gUserCountryId);

	gCountryId = $('#country-list').val();

	////-
	$.each(gItemGroupList, function(i, obj) {
		$('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});

	$('#item-group').val(gUserItemGroupId);

	gItemGroupId = $('#item-group').val();
	///

	$("#left-arrow").click(function() {

		//if (endDate.getMonth() == 0 && endDate.getFullYear() == gYearList[0].YearName) return;

		//endDate.prevMonth();
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

		getMosTypeProductCount();
		getCurrentPatients();
		//getPatientTrendTimeSeries();
		oCurrentPatients.fnDraw();
		//oPopulationRisk.fnDraw();
		oProductStockStatus.fnDraw();
		setInventoryLink();
		getTotalPatients();
		//getMaleFemale();
		getSimpleVsSevere1();

	});

	$("#right-arrow").click(function() {

		//if (endDate.getMonth() == 11 && endDate.getFullYear() == gYearList[gYearList.length - 1].YearName) return;

		//endDate.nextMonth();
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

		getMosTypeProductCount();
		getCurrentPatients();
		//getPatientTrendTimeSeries();
		oCurrentPatients.fnDraw();
		//oPopulationRisk.fnDraw();
		oProductStockStatus.fnDraw();
		getTotalPatients();
		//getMaleFemale();
		getSimpleVsSevere1();
	});

	$("#month-list").change(function() {
		endDate.setMonth($("#month-list").val() - 1);

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		getMosTypeProductCount();
		getCurrentPatients();
		//getPatientTrendTimeSeries();
		oCurrentPatients.fnDraw();
		//oPopulationRisk.fnDraw();
		oProductStockStatus.fnDraw();
		setInventoryLink();
		getTotalPatients();
		//getMaleFemale();
		getSimpleVsSevere1();
		
		hProvider.submitState([{"name":"MonthId", "value": gMonthId}]);
	});

	$("#year-list").change(function() {
		endDate.setYear($("#year-list").val());
		endDate.setMonth($("#month-list").val() - 1);

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		getMosTypeProductCount();
		getCurrentPatients();
		//getPatientTrendTimeSeries();
		oCurrentPatients.fnDraw();
		//oPopulationRisk.fnDraw();
		oProductStockStatus.fnDraw();
		setInventoryLink();
		getTotalPatients();
		//getMaleFemale();
		getSimpleVsSevere1();
	});

	gCountryId = $("#country-list").val();

	$("#country-list").change(function() {
		gCountryId = $("#country-list").val();
		getSelectZoomCountry($("#country-list").val());
		oProfileTable.fnDraw();
		getMosTypeProductCount();

		//getCurrentPatients();
		////getPatientTrendTimeSeries();
		////oPopulationRisk.fnDraw();
		//setInventoryLink();

		getItemGroupFrequency();

	});

	$('#item-group').change(function() {
		gItemGroupId = $("#item-group").val();
		getItemGroupFrequency();
	});

	oProfileTable = $('#cparams-table').dataTable({
		"bFilter" : false,
		"bJQueryUI" : false,
		"bSort" : true,
		"bInfo" : false,
		"bPaginate" : false,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"sAjaxSource" : baseUrl + "dashboard_server.php",
		"aaSorting" : [[0, 'asc']],
		"iDisplayLength" : 100,
		"sPaginationType" : "full_numbers",
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "operation",
				"value" : 'getCountryProfileParams'
			});
			aoData.push({
				"name" : "YearId",
				"value" : gYearId
			});
			aoData.push({
				"name" : "CountryId",
				"value" : gCountryId
			});
			aoData.push({
				"name" : "lan",
				"value" : lan
			});

			$.ajax({
				"dataType" : 'json',
				"type" : "post",
				"url" : sSource,
				"data" : aoData,
				"success" : function(json) {
					fnCallback(json);
				}
			});
		},
		"aoColumns" : [{
			"sClass" : "center-aln",
			"sWidth" : "15%",
			"bSortable" : false,
			"bSearchable" : false,
			"bVisible" : false
		}, {
			"sClass" : "left-aln",
			"bSortable" : false,
			"sWidth" : "70%"
		}, {
			"sClass" : "right-aln",
			"sWidth" : "15%",
			"bSortable" : false,
			fnRender : function(oDt) {
				return formatNumber(oDt.aData[2]);

			}
		}]
	});

	//getMosTypeProductCount();
	//getCurrentPatients();
	////getPatientTrendTimeSeries();
	////////getPatientAtRisk();
	////oPopulationRisk.fnDraw();
	getItemGroupFrequency();

	onBarChartReport();

	getSeverePatients();
	oSeverePatients.fnDraw();

	setTimeout(function() {	
		getSelectZoomCountry($("#country-list").val());
	}, 3000);	
	
});

