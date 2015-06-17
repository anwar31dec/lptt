var gMonthId;
var gYearId;
var gCountryId;
var gCountryName = '';
var gItemGroupId;
var NumberPatientsProduct;
var endDate = new Date();
var yearList;
var gFacilityCode;
var gFrequencyId;
var gStartYearId;
var gSartMonthId;



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
			"FrequencyId" : gFrequencyId
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

			NumberPatientsProduct.fnDraw();

		}
	});
}


$(function() {

	//$.each(gMonthList, function(i, obj) {
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

		NumberPatientsProduct.fnDraw();

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

		NumberPatientsProduct.fnDraw();
	});

	$("#month-list").change(function() {
		endDate.setMonth($("#month-list").val() - 1);

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		NumberPatientsProduct.fnDraw();
	});

	$("#year-list").change(function() {
		endDate.setYear($("#year-list").val());
		endDate.setMonth($("#month-list").val() - 1);

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		NumberPatientsProduct.fnDraw();
	});

	gCountryId = $("#country-list").val();

	$("#country-list").change(function() {
		gCountryId = $("#country-list").val();
		getItemGroupFrequency();
	});

	$("#item-group-list").change(function() {
		gItemGroupId = $("#item-group-list").val();
		getItemGroupFrequency();

	});

	NumberPatientsProduct = $('#numberpatientTable').dataTable({
		"bFilter" : true,
		"bJQueryUI" : true,
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[1, 'asc']],		
		"sPaginationType" : "full_numbers",
		"sAjaxSource" : baseUrl + "number_patients_product_server.php",
		"fnDrawCallback" : function(oSettings) {
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getNumberPatientsProduct'
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
				"name" : "MonthId",
				"value" : $('#month-list').val()
			});
			aoData.push({
				"name" : "CountryId",
				"value" : $('#country-list').val()
			});
			aoData.push({
				"name" : "YearId",
				"value" : $('#year-list').val()
			});
			aoData.push({
				"name" : "ItemGroupId",
				"value" : $('#item-group-list').val()
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
		"aoColumns" : [{
			"sClass" : "SL",
			"sWidth" : "5%",
			"bSortable" : false
		}, {
			"sClass" : "ItemName",
			"bSortable" : false,
            "sWidth" : "25%"
		}, {
			"sClass" : "TotalPatients",
			"bSortable" : false,
            "sWidth" : "10%"
		}, {
			"sClass" : "AMC",
			"bSortable" : false,
            "sWidth" : "10%"
		}, {
			"sClass" : "ClStock",
			"bSortable" : false,
            "sWidth" : "10%"
		}, {
			"sClass" : "StockOnOrder",
			"bSortable" : false,
            "sWidth" : "10%"
		}, {
			"sClass" : "StockOnOrderMOS",
			"bSortable" : false,
            "sWidth" : "10%"
		}, {
			"sClass" : "TotalMOS",
			"bSortable" : false,
            "sWidth" : "10%"
		}, {
			"sClass" : "ProjectedDate",
			"bSortable" : false,
            "sWidth" : "10%"
		}]
	});
	getItemGroupFrequency();
});
