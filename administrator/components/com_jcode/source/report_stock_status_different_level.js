var gMonthId;
var gYearId;
var dataY = new Array();
var dataColor = new Array();
var stockStatusDifferentLevel;
var endDate = new Date();
var chart;
var gFrequencyId=1;
var patientTrendTimeSeries;
var $ = jQuery.noConflict();

function getReportGeneratePercentage() {

    $.ajax({
        type: "POST",
	    url : baseUrl + "reporting_rate.php",
		data : {
			 operation : 'getPercentage',
			 CountryId : gCountryId,
			 ItemGroupId : gItemGroupId,
			 OwnerTypeId : $('#report-by').val(),
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


function onBarChartReport() {
	$.ajax({
	   	type: "POST",
		url : baseUrl + "report_stock_status_different_level_server.php",
		data : {
			action : 'getFacilitySummaryChart',
            Year: $('#year-list').val(),
			Month: $('#month-list').val(),
            Country: $('#country-list').val(),
            ItemGroup: $('#item-group').val(),
			OwnnerTypeId : $('#report-by').val(),
            lan:lan
		},
		success : function(response) {		             
			response = $.parseJSON(response);
			item_name = response.item_name; 
			level_name = response.level_name;
			dataValue = response.dataValue;
            barcolor = response.barcolor;
			name = response.name;   
                  
        	var i;
            var seriesCounter = 0;
        	var seriesOptions = [];
            var barcol = [];
            
			for(i = 0; i < level_name.length; i++) {
                seriesOptions[i] = {
        			name: level_name[i],
        			data: dataValue[i]
		        };
                barcol[i] = barcolor[i];
                seriesCounter++;
            }
            
    		if (seriesCounter == level_name.length) {
	            cItemLength = 100+50*(item_name.length);  
    			onSetBarChart(item_name, seriesOptions, name, barcol, cItemLength);
    		}            			
		}
	});
}

function onSetBarChart(item_name, seriesOptions, name, barcol, cItemLength) {
    chart = new Highcharts.Chart({
		chart : {
			type : 'bar',
			borderColor : '#C3DDEC',
			borderWidth : 1,
			plotBorderWidth : 1,
			margin : [50, 50, 100, 380],
            spacingLeft: 100,      
			height : cItemLength,
			renderTo : 'bar-chart'
		},
		title : {
			text : TEXT['Stock Status at Different Level of '] + name
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
		    min: 0,
			title : {
				text : TEXT['Month Of Supply (MOS)'],
				align : 'middle'
			},
			labels : {
				overflow : 'justify'
			}
		},
        legend : {
			enabled: true,
			reversed: true
		},
		credits : {
			enabled : false
		},
		tooltip : {
			valueSuffix : ' month(s)'
		},
		plotOptions: {
			series: {
				stacking: 'normal',
				dataLabels: {
					enabled: true,
					color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
				}
			}
		},
        colors: barcol,
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


function onPatientTrendTable() {
	$('body').animate({
		opacity : 1
	}, 500, function() {

	$.ajax({
		type : "POST",
		url : baseUrl + "report_stock_status_different_level_server.php",
		data : {
			action : 'getDiffLevelTableData',
			Year: $('#year-list').val(),
			Month: $('#month-list').val(),
			Country: $('#country-list').val(),
			ItemGroup: $('#item-group').val(),
			OwnnerTypeId : $('#report-by').val(),
			lan:lan			
		},
		success : function(results) {
		results = $.parseJSON(results);
		var ColumnNo = results.COLUMNS.length;
	
	$('#tbl-pf').html('');
	html = '<table class="table table-striped table-bordered display table-hover" cellspacing="0" id="tbl-patient-trend-time-series">';
	html += '<thead>';
		
		
	if(ColumnNo == 0){
		html += '<tr><th style="text-align:center; width:5%;">SL#</th><th rowspan="2" style="text-align:center; width:10%;">Tracking#</th>';
		html += '<th  style="text-align:center; width:10%;">National Level AMC</th>';
		html += '<th style="text-align:center; width:10%;">National Level SOH</th>';	
		html += '<th style="text-align:center; width:10%;">National Level MOS</th></tr>'
	}
	else{		
		html += '<tr><th rowspan="2" style="text-align:center; width:5%;">SL#</th>';
		html += '<th rowspan="2" style="text-align:center; width:10%;">Tracking#</th>';
		
		var Header = '-1';
		for (var i = 0; i < ColumnNo; i++) {
			if(Header != results.COLUMNS[i]){
				html += '<th colspan="3" style="text-align:center;">'+results.COLUMNS[i]+'</th>';
				Header = results.COLUMNS[i];
				}
		}
		
		var index = 0;
		html += '<th rowspan="2" style="text-align:center; width:10%;">Total Time</th>';
		html += '</tr><tr>'
			for (var i = 0; i < ColumnNo; i++) {
				index++;
				if(index == 1)
					html += '<th  style="text-align:center; width:13%;">In Time</th>';
				else if(index == 2)
					html += '<th  style="text-align:center; width:13%;">Out Time</th>';
				else if(index == 3)
					html += '<th  style="text-align:center; width:13%;">Duration</th>';
				
				if(index == 3)
					index = 0;
				
		}		
		html += '</tr>'	
	}
	
	
	html += '</thead>';
	html += '<tbody></tbody>';
	html += '</table>';
	$('#tbl-pf').html(html);
				
patientTrendTimeSeries = $('#tbl-patient-trend-time-series').dataTable({
	"bFilter" : false,
	"bJQueryUI" : true,
	"bSort" : true,
	"bInfo" : false,
	"bPaginate" : false,
	"bSortClasses" : false,
	"bProcessing" : true,
	"bServerSide" : false,
	"bSortable": false,
	// scrollY:        "400px",
	// scrollX:        "100%",
	// scrollCollapse: true,
	// paging:         false,
	"aaSorting" : [[1, 'asc']],
	"sPaginationType" : "full_numbers",
	"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
	"iDisplayLength" : 25,
	"sAjaxSource": baseUrl + "report_stock_status_different_level_server.php",
	"fnDrawCallback": function(oSettings) {			
	
	},
	"fnServerData": function(sSource, aoData, fnCallback) {
		aoData.push({
			"name": "action",
			"value": 'getDiffLevelTableData'
		});
		aoData.push({
			"name": "lan",
			"value": lan
		});
		aoData.push({
			"name": "baseUrl",
			"value": baseUrl
		});
		/* aoData.push({
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
			"name": "OwnnerTypeId",
			"value": $('#report-by').val()
		});	 */
		
		
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
	 
	 
	  // patientTrendTimeSeries.settings({
			// scrollY: "400px",
		// });
	
	 // new $.fn.dataTable.FixedColumns(patientTrendTimeSeries, {
        // leftColumns: 1
    // } );
	
	 // patientTrendTimeSeries.fnUpdate();
	 
	 
	 }
	});
  });
}



function getItemGroupFrequency() {
	$.ajax({
		type : "POST",
		dataType : "json",
		url : baseUrl + "combo_generic.php",
		data : {
			"operation" : 'getItemGroupFrequency',
			"CountryId" : gCountryId,
			"ItemGroupId" : gItemGroupId
		},
		success : function(response) {
			gFrequencyId = response[0].FrequencyId;
			gSartMonthId = response[0].StartMonthId;
			gStartYearId = response[0].StartYearId;
			getMonthByFrequencyId();
		}
	});
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

			if (gFrequencyId == 1){
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
			onPatientTrendTable();
            getReportGeneratePercentage();
		}
	});
}
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
	
	$.each(gCountryListFLevel, function(i, obj) {
		$('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
	});
	
	$('#country-list').val(gUserCountryId);
    
   	$.each(gItemGroupList, function(i, obj) {
		$('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});
	
	$('#item-group').val(gUserItemGroupId);

	
	$.each(gReportByList, function(i, obj) {
		$('#report-by').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
	});
    $('#report-by').val(gDetaultOwnerTypeId);
    
    $('#report-by').change(function() {
        gReportByList = $('#report-by').val();
        onBarChartReport();
		onPatientTrendTable();
        getReportGeneratePercentage();
    });
	
	
	
	gMonthId = objInit.svrLastMonth;
	gYearId = objInit.svrLastYear;

	gItemGroupId = $("#item-group").val();
	endDate.setFullYear(objInit.svrLastYear);
	gCountryId = $("#country-list").val();
	
    
    $('#country-list').change(function() {
		gCountryId = $("#country-list").val();
		onBarChartReport();
		onPatientTrendTable();
		getReportGeneratePercentage();
    });
    
    $('#item-group').change(function() {
		gItemGroupId = $("#item-group").val();
        onBarChartReport();
		onPatientTrendTable();
		getReportGeneratePercentage();
    });
	
	
	
    $("#month-list").change(function() {
        gMonthId = $('#month-list').val();
		endDate.setMonth($("#month-list").val() - 1);
	    onBarChartReport();	   
		onPatientTrendTable();
        getReportGeneratePercentage();
	});
	
	$("#year-list").change(function() {
	   gYearId = $('#year-list').val();
		endDate.setYear($("#year-list").val());
		endDate.setMonth($("#month-list").val() - 1);        
	    onBarChartReport();
	    onPatientTrendTable();
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
		onBarChartReport();
	    onPatientTrendTable();
        getReportGeneratePercentage();
	});
	
	//onBarChartReport();
    //onPatientTrendTable();
	//getItemGroupFrequency();
	onBarChartReport();
	onPatientTrendTable();
	getReportGeneratePercentage();
	
	
	
});
