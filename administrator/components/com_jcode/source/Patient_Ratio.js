var patientRatioTable2;
var gMonthId;
var gYearId;
var gCountryId;
var TableData = new Array();
var oProfileTable;
var gItemGroupId;
var yearList;
var endDate = new Date();
var series1SelectedsType = '';
var series1SelectedsTyperpt = '';
var $ = jQuery.noConflict();
$(function (){
	
	$.each(gCountryList, function(i, obj) {
		$('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
	});
	
	$('#country-list').val(gUserCountryId);
    
   	$.each(gItemGroupList, function(i, obj) {
		$('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});
	
	$('#item-group').val(gUserItemGroupId);
	gItemGroupId = $('#item-group').val();
	
	$.each(gYearList, function(i, obj) {
		$('#year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
	});
	
	$.each(gMonthList, function(i, obj) {
			$('#month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
	});
	
	endDate.setMonth(objInit.svrLastMonth - 1);
	endDate.setFullYear(objInit.svrLastYear);
	$("#month-list").val(endDate.getMonth() + 1);
	$("#year-list").val(endDate.getFullYear());	
	
	gMonthId = $('#month-list').val() == null ? -99 : $('#month-list').val();
	gYearId = $('#year-list').val() == null ? -99 : $('#year-list').val();	
	
	patientRatioTable = $('#patientRatioTable').dataTable({
			"bFilter": false,
			"bJQueryUI": true,
			"bSort": false,
			"bInfo": false,
			"bPaginate": false,
			"bSortClasses": false,
			"bProcessing": true,
			"bServerSide": true,
			"aaSorting": [
				[1, 'asc'],
				[2, 'desc']
			],
			"sPaginationType": "full_numbers",
			"aLengthMenu": [
				[25, 50, 100],
				[25, 50, 100]
			],
			"iDisplayLength": 25,
			"sAjaxSource": baseUrl + "Patient_Ratio_Server.php",
			"fnDrawCallback": function(oSettings) {

				// if (oSettings.aiDisplay.length == 0) {
					// return;
				// }
				// var nTrs = $('#patientRatioTable tbody tr');
				// var iColspan = nTrs[0].getElementsByTagName('td').length;
				// var sLastGroup = "";
				// for (var i = 0; i < nTrs.length; i++) {
					// var iDisplayIndex = i;
					// var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[2];
					// if (sGroup != sLastGroup) {
						// var nGroup = document.createElement('tr');
						// var nCell = document.createElement('td');
						// nCell.colSpan = iColspan;
						// nCell.className = "group";
						// nCell.innerHTML = sGroup;
						// nGroup.appendChild(nCell);
						// nTrs[i].parentNode.insertBefore(nGroup, nTrs[i]);
						// sLastGroup = sGroup;
					// }
				// }				
			},
			"fnServerData": function(sSource, aoData, fnCallback) {
				aoData.push({
					"name": "action",
					"value": 'getPatientRatioPieChartTable',
				});
				aoData.push({
					"name": "YearId",
					"value": gYearId
				});
				aoData.push({
					"name": "MonthId",
					"value": gMonthId
				});
				aoData.push({
					"name": "Country",
					"value": $('#country-list').val()
				});
				aoData.push({
					"name": "serviceType",
					"value": series1SelectedsType
				});
				aoData.push({
					"name": "ItemGroupId",
					"value": gItemGroupId
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
					"success": fnCallback
				});
			},
			"aoColumns": [{
    			"bVisible": false
    		},{
                "sClass" : "SL",		  
    			"sWidth" : "8%",
    			"bSortable": false
    		},{
    			"sWidth" : "40%",
    			"bSearchble": true,
    			"bSortable": false
    		},{
				"sClass" : "PatientTotal",
    			"sWidth" : "25%",
    			"bSearchble": true,
    			"bSortable": false
    		},{
				"sClass" : "PatientTotal",
    			"bSearchble": true,
    			"bSortable": false
    		}]
		});
	
	
	$('#country-list').change(function() {
		gCountryId = $("#country-list").val();
		calServer();
	});
	
	$('#item-group').change(function() {
		gItemGroupId = $("#item-group").val();
		calServer();
	});
	
	
	$('#year-list').change(function() {
		endDate.setYear($("#year-list").val());
		endDate.setMonth($("#month-list").val() - 1); 
		gYearId = $("#year-list").val();
		calServer();
	});
	
	$('#month-list').change(function() {
		endDate.setMonth($("#month-list").val() - 1);
		gMonthId = $("#month-list").val();
		calServer();
	});
	
	$("#left-arrow").click(function() {
		if (endDate.getMonth() == 0 && endDate.getFullYear() == gYearList[0].YearName)
				return;

		endDate.prevMonth();			
		//gCountryId = $("#country-list").val();
		
		$("#month-list").val(endDate.getMonth() + 1);
		$("#year-list").val(endDate.getFullYear());
		gYearId = $("#year-list").val();
		gMonthId = $("#month-list").val();
		
		calServer();
	});

	$("#right-arrow").click(function() {
		if (endDate.getMonth() == 11 && endDate.getFullYear() == gYearList[gYearList.length - 1].YearName)
				return;
				
		endDate.nextMonth();		
		//gCountryId = $("#country-list").val();		
		$("#month-list").val(endDate.getMonth() + 1);
		$("#year-list").val(endDate.getFullYear());	
		
		gYearId = $("#year-list").val();
		gMonthId = $("#month-list").val();
	
		calServer();
	});
	
calServer();
});

function calServer(){
	series1SelectedsType = '';
	series1SelectedsTyperpt = '';
	patientRatioPieChart();	
	patientRatioTable.fnDraw();
	//
	// Apply the theme
	Highcharts.setOptions(Highcharts.theme);
}

function patientRatioPieChart(){
$('#caseratiodata').html(TEXT['Patient Ratio of'] + $('#month-list option:selected').text()+' '+ $('#year-list').val());
	$.ajax({
		type : 'post',
		dataType : "json",
		url : baseUrl + 'Patient_Ratio_Server.php',
		data : {
		action : 'getPatientRatioPieChart',
			YearId : gYearId,
			MonthId : gMonthId,
			Country : $('#country-list').val(),
			ItemGroupId : gItemGroupId,
			lan : lan
		},
		success : function(response) {	

		var chart = new Highcharts.Chart({
        chart: {
            type: 'pie',
            renderTo: 'patients-line-chart1',
            events: {
				
				drilldown: function(e) {
                    chart.setTitle({ text: TEXT['Case Ratio'] +' - '+ series1SelectedsType +' '+TEXT['of']+' '+ $('#month-list option:selected').text()+' '+ $('#year-list').val()});
					$('#caseratiodata').html(TEXT['Case Ratio'] +' - '+ series1SelectedsType +' '+TEXT['of']+' '+ $('#month-list option:selected').text()+' '+ $('#year-list').val());
					series1SelectedsTyperpt = series1SelectedsType;
                    patientRatioTable.fnDraw();
				
                },
                drillup: function(e) {
                    chart.setTitle({ text: TEXT['Patient Ratio of'] + $('#month-list option:selected').text()+' '+ $('#year-list').val() });
					$('#caseratiodata').html(TEXT['Patient Ratio of'] + $('#month-list option:selected').text()+' '+ $('#year-list').val());
					series1SelectedsType = '';
				    series1SelectedsTyperpt = '';
				    patientRatioTable.fnDraw();
                }
            }
        },
		title: {
            text: TEXT['Patient Ratio of'] + $('#month-list option:selected').text()+' '+ $('#year-list').val()
			
        },
		credits: {
            enabled: false
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: false,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                },
				showInLegend: true,			
                point: {
                     events: {
                              mouseOver: function (e) {
							  series1SelectedsType = this.name;					  
                    }}}
            }				
        },
       series: [{
            name: 'Patient',
            colorByPoint: true,
			colors : response.Series1Color,
            data: JSON.parse(response.Series1)
			,events: {
					click: function(e)
					{
						//patientRatioTable.fnDraw();	
					}
					}
        }],
        drilldown: {
			name: 'Patient',
            colorByPoint: true,
            series: JSON.parse(response.Series2)
		
        }
    })
	
	
	
	
	
	
	
	
	
	
	
	
	
		
		
		
		
		
		
		
	}
	});
	
}

Highcharts.theme = {

	// colors: ["#8085e9", "#8d4654", "#aaeeee", "#ff0066", "#eeaaee","#f45b5b", "#7798BF", "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
	chart: {
		backgroundColor: null,
		style: {
			fontFamily: "open sans, open sans"
		}
	},
	title: {
		style: {
			color: 'black',
			fontSize: '16px',
			fontWeight: 'bold'
		}
	},
	subtitle: {
		style: {
			color: 'black'
		}
	},
	tooltip: {
		borderWidth: 0
	},
	legend: {
		itemStyle: {
			fontWeight: 'bold',
			fontSize: '13px'
		}
	},
	xAxis: {
		labels: {
			style: {
				color: '#6e6e70'
			}
		}
	},
	yAxis: {
		labels: {
			style: {
				color: '#6e6e70'
			}
		}
	},
	plotOptions: {
		series: {
			shadow: true
		},
		candlestick: {
			lineColor: '#404048'
		},
		map: {
			shadow: false
		}
	},

	// Highstock specific
	navigator: {
		xAxis: {
			gridLineColor: '#D0D0D8'
		}
	},
	rangeSelector: {
		buttonTheme: {
			fill: 'white',
			stroke: '#C0C0C8',
			'stroke-width': 1,
			states: {
				select: {
					fill: '#D0D0D8'
				}
			}
		}
	},
	scrollbar: {
		trackBorderColor: '#C0C0C8'
	},

	// General
	background2: '#E0E0E8'
	
};


function printfunction() {
	window.open("<?php echo $baseUrl; ?>report/printProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>&action=getPatientTrendTimeSeriesChart&MonthNumber=" + MonthNumber + "&Country=" + $('#country-list').val());
}

function printfunction1() {
	window.open("<?php echo $baseUrl; ?>report/ExcelProcessing.php?jBaseUrl=<?php echo $jBaseUrl; ?>&lan=<?php echo $lan;?>&action=getPatientTrendTimeSeriesChart&MonthNumber=3&Country=0");
}

