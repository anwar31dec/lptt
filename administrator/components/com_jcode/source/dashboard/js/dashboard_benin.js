var yearList;
var oTableFacility;
var oTableMonthlyStatus;
var gFacilityCode;
var gMonthId;
var gYearId;
var TableData = new Array();

var endDate = new Date();

var oTableCProfileParams;

function changeDashboard_country(countryId){
    if(countryId == 0){
        var url = jbaseUrl + "index.php/dashboard";                   
        $(location).attr('href', url);
    }
}

$(function() {
    
	$.each(gMonthList, function(i, obj) {
		$('#month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
	});
	endDate.setMonth(objInit.initialMonth - 1);
	$("#month-list").val(objInit.initialMonth);

	$.each(gYearList, function(i, obj) {
		$('#year-list').append($('<option></option>').val(obj.YearName).html(obj.YearName));
	});
	
	$("#year-list").val(endDate.getFullYear());
	
	$.each(gCountryList, function(i, obj) {
		$('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
	});

	$("#country-list").val(1);
	
	$("#left-arrow").click(function() {

		if (endDate.getMonth() == 0 && endDate.getFullYear() == gYearList[gYearList.length - 1].YearId)
			return;

		endDate.prevMonth();

		$("#month-list").val(endDate.getMonth() + 1);
		$("#year-list").val(endDate.getFullYear());

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		//oTableMonthlyStatus.fnDraw();

	});
	//
	$("#right-arrow").click(function() {

		if (endDate.getMonth() == 11 && endDate.getFullYear() == objInit.initialYear)
			return;

		endDate.nextMonth();
		$("#month-list").val(endDate.getMonth() + 1);
		$("#year-list").val(endDate.getFullYear());
		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		//oTableMonthlyStatus.fnDraw();
	});

	$("#month-list").change(function() {
		endDate.setMonth($("#month-list").val() - 1);

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();
		//oTableMonthlyStatus.fnDraw();
	});
	//
	$("#year-list").change(function() {
		endDate.setYear($("#year-list").val());
		endDate.setMonth($("#month-list").val() - 1);

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		//oTableMonthlyStatus.fnDraw();
	});
	
	oTableCProfileParams = $('#cparams-table').dataTable({
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
		"iDisplayLength" : 100,
		"sScrollY": scrollY,
		"sAjaxSource" : baseUrl + "dashboard_server_benin.php",
		"fnServerData" : function(sSource, aoData, fnCallback) {
			//alert(iSo3);
			aoData.push({
				"name" : "operation",
				"value" : "getCountryProfileParams"
			});
			aoData.push({
				"name" : "ISO3",
				"value" : iSo3
			});
			// aoData.push({
				// "name" : "MonthId",
				// "value" : gMonthId
			// });
			// aoData.push({
				// "name" : "YearId",
				// "value" : gYearId
			// });
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
			"bVisible" : true,
			"bSortable" : true,
			"sWidth" : "75%"
		}, {
			"sClass" : "param-value",
			"sWidth" : "25%",
			"bSortable" : false,
		}]
	});

		//Morris Chart
	var patientsPieChart = Morris.Donut({
		element : 'patients-pie',
		data : [{
			label : "ART",
			value : 1050
		}, {
			label : "RTK",
			value : 852
		}, {
			label : "PMTCT",
			value : 917
		}],
		colors : ['#ffc545', '#9ad268', '#50ABED']
	});

	var patientsLineChart = Morris.Line({
		element : 'patients-line-chart',
		data : [{
			y : '2013-03',
			a : 1088,
			b : 521,
			c : 662
		}, {
			y : '2013-04',
			a : 820,
			b : 724,
			c : 660
		}, {
			y : '2013-05',
			a : 903,
			b : 380,
			c : 312
		}, {
			y : '2013-06',
			a : 905,
			b : 729,
			c : 732
		}, {
			y : '2013-07',
			a : 951,
			b : 522,
			c : 603
		}, {
			y : '2013-08',
			a : 887,
			b : 526,
			c : 626
		}, {
			y : '2013-09',
			a : 820,
			b : 934,
			c : 830
		}, {
			y : '2013-10',
			a : 817,
			b : 708,
			c : 872
		}, {
			y : '2013-11',
			a : 821,
			b : 381,
			c : 783
		}, {
			y : '2013-12',
			a : 780,
			b : 335,
			c : 883
		}, {
			y : '2014-01',
			a : 1527,
			b : 385,
			c : 748
		}, {
			y : '2014-02',
			a : 1050,
			b : 852,
			c : 917
		}],
		xkey : 'y',
		ykeys : ['a', 'b', 'c'],
		labels : ['ART', 'RTK', 'PMTCT'],
		lineColors : ['#FFC545', '#9AD268', '#50ABED'],
		gridTextColor : '#777777',
		resize: true
	});
	
			//Morris Chart
	var stockoutPieChart = Morris.Donut({
		element : 'stockout-pie-chart',
		data : [{
			label : "Very High Risk",
			value : 15
		}, {
			label : "High Risk",
			value : 25
		}, {
			label : "Medium Risk",
			value : 45
		}, {
			label : "Low Risk",
			value : 35
		}],
		colors : ['#d7191c', '#fe9929', '#f0f403', '#4dac26'],
		formatter: function (x) { return x + "%"}
	});
	
	
});
