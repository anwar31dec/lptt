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
var gFrequencyId=1;
var gStartYearId;
var gSartMonthId;

var dataY = new Array();
var dataColor = new Array();
var nationalSumProducts;
var chart;
var value;
var bPatientInfo;
var tableTitle='';
var $ = jQuery.noConflict();
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

function getReportGeneratePercentage() {
    $.ajax({
        type: "POST",
	    url : baseUrl + "reporting_rate.php",
		data : {
			 operation : 'getPercentage',
			 CountryId : gCountryId,
			 ItemGroupId : gItemGroupId,
			 OwnerTypeId : gReportById,
			 Year : gYearId,
			 Month : gMonthId,
			 lan : lan
     
	 
		},
		success: function(response) {
		response = JSON.parse(response);

		 $('#Total').html(response.Total+' %'); 
		 $('#Facility').html(response.HealthFaclilities+' %'); 
		 $('#District').html(response.DistrictWarehouse+' %'); 
		 $('#Region').html(response.RegionalWarhouse+' %'); 
		 $('#Central').html(response.CentralWarehouse+' %'); 
		}
	});
}

function getMosTypeProductCount() {
	
	$('#stockoutpiecharthead').html($('#item-group option:selected').text()+' '+TEXT['Product Status'] +' '+TEXT['on']+' '+$('#month-list option:selected').text()+' '+$('#year-list option:selected').text());

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
            "Reportby" : $('#report-by').val(),
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
            "Reportby" : $('#report-by').val(),
			"lan" : lan
		},
		success : function(response) {
		      
            $.each(response, function(i, obj) {
				sumOfRiskCount += parseInt(obj.TotalPatient);
               
			});
            
			$.each(response, function(i, obj) {
			 
			 if(sumOfRiskCount > 0){
			     patientPercentData[i] = {
					"label" : obj.RegimenName,
					"value" : (obj.TotalPatient * 100 / sumOfRiskCount).toFixed(1)
				};
			 }
             else{
                patientPercentData[i] = {
					"label" : obj.RegimenName,
					"value" : 0
				};
             }
				
			});

			var patientsPieChart = Morris.Donut({
				element : 'patients-pie',
				data : patientPercentData,
				colors : ['#7d8a2e', '#e87f00', '#a946c8', '#bd8d46'],
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
            "Reportby" : $('#report-by').val(),
			"lan" : lan
		},
		success : function(response) {

			  $.each(response, function(i, obj) {
				sumOfRiskCount += parseInt(obj.TotalPatient);
			});
            $.each(response, function(i, obj) {
                
                if (sumOfRiskCount > 0) {
					patientPercentData[i] = {
					"label" : obj.RegimenName,
					"value" : (obj.TotalPatient * 100 / sumOfRiskCount).toFixed(1)
				};
				} else {
					patientPercentData[i] = {
					"label" : obj.RegimenName,
					"value" : 0
				};
				}
			});

			var patientsPieChart = Morris.Donut({
				element : 'severe-pie',
				data : patientPercentData,
				colors : ['#7d8a2e', '#e87f00', '#a946c8', '#bd8d46'],
				formatter : function(x) {
					return x + "%";
				}
			});

			// $("#art-value-id").text(addCommas(patientPercentData[0].value));
			// $("#rtk-value-id").text(addCommas(patientPercentData[1].value));
			// $("#pmtct-value-id").text(addCommas(patientPercentData[2].value));s
		}
	});
}


function getPatientTrendTimeSeries() {
	$.ajax({
		type: "POST",
		url: baseUrl + "dashboard_server.php",
		data: {
			operation: 'getFacilityStockoutTimeSeriesChart',
			Country: gCountryId,
            ItemGroupId: gItemGroupId,
            RegionId: 0,
			DistrictId: 0,	
			EndMonthId: gMonthId,
			EndYearId: gYearId,
			OwnerTypeId : $('#report-by').val()
		},
		success: function(response) {
			//console.log(response);
			response = $.parseJSON(response);
			month_name = response.month_name;
			overview_name = response.overview_name;			
			datalist = response.datalist;
            //alert(overview_name);
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
			//margin: [50, 180, 50, 100],
			//spacingLeft: 100,
			//height: 500,
			renderTo: 'patients-line-chart'
		},
		title: {			
			text: TEXT['Percentage of Facilities Stocked Out with One or More']+ $("#item-group option:selected").text()+' '+TEXT['Products of Last 3 Months']
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
		/*legend: {
			layout: 'vertical',
			align: 'bottom',
			verticalAlign: 'middle',
			borderWidth: 0
		},*/
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


function getstockoutpercenttable() {
	$.ajax({
		type: "POST",
		url: baseUrl + "dashboard_server.php",
		data: {
			operation: 'getstockoutpercenttable',
			MonthId : gMonthId,
			YearId : gYearId,
			CountryId : gCountryId,
			ItemGroupId : gItemGroupId,
            Reportby : $('#report-by').val(),
			lan : lan
		},
		success: function(response) {
			
			//console.log(response);
			$('#stockoutpercenttable').html(response);    
		}
		
	});
}

/*
function getPatientTrendTimeSeriesback() {
	var patientPercentData = [];
	var sumOfRiskCount = 0;

	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'dashboard_server.php',
		data : {
			//"operation" : 'getPatientTrendTimeSeries',
			"operation" : 'getFacilityStockoutTimeSeriesChart',
			"MonthId" : gMonthId,
			"YearId" : gYearId,
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId,
			"Reportby" : $('#report-by').val(),
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
*/
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
                "name" : "Reportby",
                "value" : $('#report-by').val()
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
                "name" : "Reportby",
                "value" : $('#report-by').val()
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
                "name" : "Reportby",
                "value" : $('#report-by').val()
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
                "name" : "Reportby",
                "value" : $('#report-by').val()
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
            "Reportby" : $('#report-by').val(),
			"lan" : lan,
		},
		success : function(response) {
			 //console.log(response);
			 //alert(response[0].TotalPatient);
           /*if(response == '' ){
                $('#rty').hide();
            }
			
            else{*/
                $('#total-patients').text(response[0].TotalPatient);
				$('#totalcase').text(TEXT['Total']+' '+$('#item-group option:selected').text()+' '+TEXT['Patients']);
              /*  $('#rty').show();
             
            }*/
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
            "Reportby" : $('#report-by').val(),
			"lan" : lan
		},
		success : function(response) { 
		  
         if(response == ''){
            
            var $td = $('#simple-vs-severe-div table tr td');
			$('#simple-vs-severe-div table tr').each(function(index, element) {
			
				if(index == 0){	
					$('td:eq(0)', this).each(function() {
						$('p:eq(0)', this).text("0%");
					});
					$('td:eq(1)', this).each(function() {						
						$('p:eq(0)', this).text("0%");
					});
				}
				
				if(index == 1){			
					$('td:eq(0)', this).attr({
						"width" : "0%"
                        
					});
					$('td:eq(1)', this).attr({
						"width" : "0%"
					});
				}
				
				if(index == 2){	
					$('td:eq(0)', this).each(function() {
						$('p:eq(0)', this).text("");
					});
					$('td:eq(1)', this).each(function() {				
						$('p:eq(0)', this).text("");
					});
				}

			});
         }
         else {
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
             //$('#simple-vs-severe-div').show();
                     
            }
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
            "Reportby" : $('#report-by').val(),
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
    
    $('#ItemGroupId1').attr('href', 'index.php/national-level-reports/national-stock-summary?ItemGroupId=1&MonthId=' + gMonthId + '&YearId=' + gYearId +'&CountryId='+ gCountryId);
	$('#ItemGroupId2').attr('href', 'index.php/national-level-reports/national-stock-summary?ItemGroupId=2&MonthId=' + gMonthId + '&YearId=' + gYearId +'&CountryId='+ gCountryId);
	$('#ItemGroupId3').attr('href', 'index.php/national-level-reports/national-stock-summary?ItemGroupId=3&MonthId=' + gMonthId + '&YearId=' + gYearId +'&CountryId='+ gCountryId);
	
    
    $('#servicetypeid1').attr('href', 'index.php/reports/facility-service-indicators?ServiceTypeId=1&MonthId=' + gMonthId + '&YearId=' + gYearId + '&CountryId=' + gCountryId);
	}

function callServer() {
	getTotalPatients();
	//getMaleFemale();
	getSimpleVsSevere1();
	//oCurrentPatients.fnDraw();
	//oPopulationRisk.fnDraw();
	oProductStockStatus.fnDraw();
        
}

function getItemGroupFrequency() {
getMonthByFrequencyId();
/*
	if (gCountryId == 0 || gItemGroupId == 0) {
		gFrequencyId = 1;
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
	}*/
}

function getMonthByFrequencyId() {
/*	$.ajax({
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
				//endDate.setMonth(ghp.MonthId - 1);				
				endDate.setFullYear(objInit.svrLastYear);
			} else if (gFrequencyId == 2) {
				endDate.setMonth(objInit.svrLastMonth - 1);
				//endDate.setMonth(ghp.MonthId - 1);
				endDate.setFullYear(objInit.svrLastYear);
				endDate.lastQuarter();
			}

			$("#month-list").val(endDate.getMonth() + 1);
			$("#year-list").val(endDate.getFullYear());

			gMonthId = $('#month-list').val() == null ? -99 : $('#month-list').val();
*/


			getMosTypeProductCount();
			getCurrentPatients();
    		getSeverePatients();
    		oCurrentPatients.fnDraw();
    		//oSeverePatients.fnDraw();
			oProductStockStatus.fnDraw();
			setInventoryLink();

			getTotalPatients();
            getSimpleVsSevere1();
			//getMaleFemale();
            onBarChartReport();            
            onCaseBarChartReport();
			getstockoutpercenttable();
            getPatientTrendTimeSeries();
			
            gItemGroupId = $("#item-group").val();           
            var select = document.getElementById('item-group');  
            var opt = $(select.options[select.selectedIndex]);
             var bPatientInfo = opt.attr('bPatientInfo'); //alert(bPatientInfo);
             var value = opt.attr('value');
            
            if( bPatientInfo == 1){
                $('#simple-vs-severe-div').show();
                $('#rty').show();
                $('#patientpie').show();
                $('#severepie').show();
                $('#case-chart').show();
            }
            else{
                $('#simple-vs-severe-div').hide();
                $('#rty').hide();
                $('#patientpie').hide();
                $('#severepie').hide();
                $('#case-chart').hide();
            }
            getReportGeneratePercentage();
		/*}
	});*/
}

function onBarChartReport() {
	$.ajax({
	   	type: "POST",
		url : baseUrl + "dashboard_server.php",
		data : {
	        operation : 'getNationalSummaryChart',
			MonthId : gMonthId,
			YearId : gYearId,
			CountryId : gCountryId,
			ItemGroupId : gItemGroupId,
            Reportby : $('#report-by').val(),
			lan : lan
		},
		success : function(response) {		             
			response = $.parseJSON(response);
			item_name = response.item_name;  
			item_value = response.temp;
			barcolor = response.barcolor;
			name = response.name;
            cItemLength = 50 +17 *(item_name.length);  
			onSetBarChart(item_name, item_value, barcolor, name, cItemLength);
		}
	});
}

function onSetBarChart(item_name, item_value, barcolor, name, cItemLength) {
	chart = new Highcharts.Chart({
		chart : {
			type : 'bar',
			//borderColor : '#C3DDEC',
			borderWidth : 1,
			plotBorderWidth : 1,
			//margin : [35, 50, 50, 100],
			//spacingLeft: 5,
			height : 500,			
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
			},
			labels:{
					formatter: function () {
					    var text = this.value,
						    formatted = text.length > 30 ? text.substring(0, 30) + '...' : text;

                        return '<div class="js-ellipse" style="width:150px; overflow:hidden" title="' + text + '">' + formatted + '</div>';
				    },
				    style: {
					    width: '150px'
				    },
				    useHTML: true				
				// style: {
                  //  fontSize: '8px'//,
                  //  //fontFamily: 'Verdana, sans-serif'
                //}
			}
		},
		yAxis : {
			title : {
				text : TEXT['Month Of Supply (MOS)'],
				align : 'middle'
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
	/**/

}

function onCaseBarChartReport() {
	$.ajax({
	   	type: "POST",
		url : baseUrl + "dashboard_server.php",
		data : {
	        operation : 'getCaseSummaryChart',
			MonthId : gMonthId,
			YearId : gYearId,
			CountryId : gCountryId,
			ItemGroupId : gItemGroupId,
            Reportby : $('#report-by').val(),
			lan : lan
		},
		success : function(response) {		             
			response = $.parseJSON(response);
			item_name = response.item_name;  
			item_value = response.temp;
			barcolor = response.barcolor;
			name = response.name;
            cItemLength = 50 +17 *(item_name.length);  
			onCaseSetBarChart(item_name, item_value, barcolor, name, cItemLength);
		}
	});
}

function onCaseSetBarChart(item_name, item_value, barcolor, name, cItemLength) {
	chart = new Highcharts.Chart({
		chart : {
			type : 'bar',
			borderColor : '#C3DDEC',
			borderWidth : 1,
			plotBorderWidth : 1,
			//margin : [35, 50, 50, 100],
			//spacingLeft: 61,
			height : 220,
			renderTo : 'case-bar-chart'
		},
		title : {
			text : $('#item-group option:selected').text()+' '+TEXT['Case Report of'] + name
		},
		subtitle : {
			text : ''
		},
		xAxis : {
			categories : item_name,
			title : {
				text : null
			},
			labels:{
				
				    formatter: function () {
					    var text = this.value,
						    formatted = text.length > 30 ? text.substring(0, 30) + '...' : text;

                        return '<div class="js-ellipse" style="width:150px; overflow:hidden" title="' + text + '">' + formatted + '</div>';
				    },
				    style: {
					    width: '150px'
				    },
				    useHTML: true
				// style: {
                   // fontSize: '10px'//,
                   // //fontFamily: 'Verdana, sans-serif'
                //}
			}//,offset:100
		},
		yAxis : {
			title : {
				text : TEXT['Patients'],
				align : 'middle'
			},
			labels : {
				overflow : 'justify'
			}
		},
		tooltip : {
			valueSuffix : ' '
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
				valueDecimals : ' '
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
	

}

$(function() {

	//endDate.setMonth(objInit.svrLastMonth - 1);
	
	//alert(ghp.MonthId);
	
	//endDate.setMonth(ghp.MonthId - 1);
	
	//$("#month-list").val(objInit.svrLastMonth);
	//$("#month-list").val(2);
	//alert(objInit.svrLastMonth);
	//$("#month-list").val(ghp.MonthId);
	$.each(gMonthList, function(i, obj) {
		$('#month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
	});
	endDate.setMonth(objInit.svrLastMonth - 1);
	$("#month-list").val(endDate.getMonth() + 1);

	gMonthId = $('#month-list').val() == null ? -99 : $('#month-list').val();

	
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

    $.each(gProductGroupList, function(i, obj) {
		$('#item-group').append($('<option value="'+obj.ItemGroupId+'" bPatientInfo="'+obj.bPatientInfo+'"> '+obj.GroupName+' </option>'));
//$('#item-group').append($('<option value="100"></option>').val(obj.ItemGroupId).html(obj.GroupName));     
     
	});	

	gItemGroupId = $('#item-group').val();
     $('#report-by').val(gDetaultOwnerTypeId);

    $.each(gReportByList, function(i, obj) {
		$('#report-by').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
	});
    gReportById = $('#report-by').val();
    $('#report-by').val(gDetaultOwnerTypeId);
	
	
	
	
	
    
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
        onBarChartReport();
        onCaseBarChartReport();
		getstockoutpercenttable();		
		getCurrentPatients();
		getSeverePatients();
		oCurrentPatients.fnDraw();
		//oSeverePatients.fnDraw();
		oProductStockStatus.fnDraw();
		setInventoryLink();
		getTotalPatients();
		getPatientTrendTimeSeries();
		//getMaleFemale();
		getSimpleVsSevere1();
        hProvider.submitState([{"name":"MonthId", "value": gMonthId}]);
        hProvider.submitState([{"name":"Year", "value": gYearId}]);
        getReportGeneratePercentage();
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
        onBarChartReport();
        onCaseBarChartReport();
		getstockoutpercenttable();
		getCurrentPatients();
		getSeverePatients();
		oCurrentPatients.fnDraw();
		//oSeverePatients.fnDraw();
		oProductStockStatus.fnDraw();
		getTotalPatients();
		getPatientTrendTimeSeries();
		//getMaleFemale();
		getSimpleVsSevere1();
        hProvider.submitState([{"name":"MonthId", "value": gMonthId}]);
        hProvider.submitState([{"name":"Year", "value": gYearId}]);
        getReportGeneratePercentage();
	});

	$("#month-list").change(function() {
		endDate.setMonth($("#month-list").val() - 1);

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		getMosTypeProductCount();
        onBarChartReport();
        onCaseBarChartReport();
		getstockoutpercenttable();
		getCurrentPatients();
		getSeverePatients();
		oCurrentPatients.fnDraw();
		//oSeverePatients.fnDraw();
		oProductStockStatus.fnDraw();
	//	setInventoryLink();
		getTotalPatients();
		getPatientTrendTimeSeries();
		//getMaleFemale();
		getSimpleVsSevere1();
		
		hProvider.submitState([{"name":"MonthId", "value": gMonthId}]);
        getReportGeneratePercentage();
	});

	$("#year-list").change(function() {
		
		endDate.setYear($("#year-list").val());
		endDate.setMonth($("#month-list").val() - 1);

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		getMosTypeProductCount();
        onBarChartReport();
        onCaseBarChartReport();
		getstockoutpercenttable();
		getCurrentPatients();
		getSeverePatients();
		oCurrentPatients.fnDraw();
		//oPopulationRisk.fnDraw();
		oProductStockStatus.fnDraw();
        //oSeverePatients.fnDraw();
	//	setInventoryLink();
		getTotalPatients();
		getPatientTrendTimeSeries();
		//getMaleFemale();
		getSimpleVsSevere1();
        
        hProvider.submitState([{"name":"Year", "value": gYearId}]);
        getReportGeneratePercentage();
		getstockoutpercenttable();
	});

	gCountryId = $("#country-list").val();

	$("#country-list").change(function() {
		gCountryId = $("#country-list").val();
		getSelectZoomCountry($("#country-list").val());
	//	oProfileTable.fnDraw();
	//	getMosTypeProductCount();

		//getCurrentPatients();
		//getPatientTrendTimeSeries();
		////oPopulationRisk.fnDraw();
		//setInventoryLink();
        hProvider.submitState([{"name":"CountryId", "value": gCountryId}]);
        
		getItemGroupFrequency();

	});

	$('#item-group').change(function() {
		gItemGroupId = $("#item-group").val();
        hProvider.submitState([{"name":"ItemGroupId", "value": gItemGroupId}]);
       
        var opt = $(this.options[this.selectedIndex]); //console.log(opt);
        //var name = opt.attr('data-name');
        var bPatientInfo = opt.attr('bPatientInfo');
        var value = opt.attr('value');        
       // alert('value  '+value+' bPatientInfo '+bPatientInfo);
       if( bPatientInfo == 1){
            $('#simple-vs-severe-div').show();
            $('#rty').show();
            $('#patientpie').show();
            $('#severepie').show();
            $('#case-chart').show();
       }
       else{
            $('#simple-vs-severe-div').hide();
            $('#rty').hide();
            $('#patientpie').hide();
            $('#severepie').hide();
            $('#case-chart').hide();
       }
		getItemGroupFrequency();
        
        if(gItemGroupId == 0){
            $('#reporting-rate').hide();
        }
        else{
            $('#reporting-rate').show();
        }
	});
    
    $('#report-by').change(function() {
		gReportById = $('#report-by').val();
        hProvider.submitState([{"name":"Reportby", "value": gReportById}]);
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

	getItemGroupFrequency();
    getCurrentPatients();
	oCurrentPatients.fnDraw();
	getSeverePatients();
	//oSeverePatients.fnDraw();
	
   // callServer();
	setTimeout(function() {	
		getSelectZoomCountry($("#country-list").val());
	}, 3000);	
	
});

