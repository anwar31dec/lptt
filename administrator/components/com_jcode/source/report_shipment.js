var shipmentReportTable;
var MonthNumber = 3;
var MonthName= '3 Months';

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
	//MonthName = 'of Last ' + $('#mainTab ul li:nth-child(1)').text();
	shipmentReportTable.fnDraw();
}

function sixMonth() {
	$('#0').removeClass('active');
	$('#1').addClass('active');
	$('#2').removeClass('active');
	$('#3').removeClass('active');
	$('#custom-panel').hide();
	MonthNumber = 6;
	// MonthName = 'of Last ' + $('#mainTab ul li:nth-child(2)').text();
	shipmentReportTable.fnDraw();
}

function oneYear() {
	$('#0').removeClass('active');
	$('#1').removeClass('active');
	$('#2').addClass('active');
	$('#3').removeClass('active');
	$('#custom-panel').hide();
	MonthNumber = 12;
	//MonthName = 'of Last ' + $('#mainTab ul li:nth-child(3)').text();	
	shipmentReportTable.fnDraw();
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
	
	shipmentReportTable.fnDraw();
}

function onComboAgencyName() {
    $.getJSON(baseUrl + "t_combo.php", {
        action: 'getFundingSource',
        ItemGroup: $("#item-group").val()
    }, function(response) {
	 str = '<option value="">All Funding Source</option>';
        for (var i = 0; i < response.length; i++) {
			str += '<option value="' + response[i].FundingSourceId + '">' + response[i].FundingSourceName + '</option>';
        }
		$('#fundingSource-list').html(str);
    });
}

$(function() {

$.each(gMonthList, function(i, obj) {
		$('#start-month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
		$('#end-month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
	});
	
	
	$.each(gItemGroupList, function(i, obj) {
		$('#item-group').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});

	$('#item-group').val(gUserItemGroupId);

	$('#item-group').change(function() {
		onComboAgencyName();
		shipmentReportTable.fnDraw();
	});

	$.each(gCountryList, function(i, obj) {
		$('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
	});
	
	$('#country-list').val(gUserCountryId);

	$('#country-list').change(function() {
		shipmentReportTable.fnDraw();
	});

	onComboAgencyName();
	//$.each(gFundingSourceList, function(i, obj) {
	//	$('#fundingSource-list').append($('<option></option>').val(obj.FundingSourceId).html(obj.FundingSourceName));
	//});

	$('#fundingSource-list').change(function() {
		shipmentReportTable.fnDraw();
	});

	$.each(gShipmentStatusList, function(i, obj) {
		$('#status-list').append($('<option></option>').val(obj.ShipmentStatusId).html(obj.ShipmentStatusDesc));
	});
    
    $.each(gOwnerTypeList, function(i, obj) {
	$('#OwnerType').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
	});
    $('#OwnerType').val(gDetaultOwnerTypeId);
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

		shipmentReportTable.fnDraw();
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
		shipmentReportTable.fnDraw();
	});

	$("#start-month-list").change(function() {
		startDate.setMonth($("#start-month-list").val() - 1);
		gStartMonthId = $('#start-month-list').val();
		gStartYearId = $('#start-year-list').val();
		shipmentReportTable.fnDraw();
	});

	$("#start-year-list").change(function() {
		startDate.setYear($("#start-year-list").val());
		startDate.setMonth($("#start-month-list").val() - 1);
		gStartMonthId = $('#start-month-list').val();
		gStartYearId = $('#start-year-list').val();
		shipmentReportTable.fnDraw();
	});

	$("#end-month-list").change(function() {
		endDate.setMonth($("#end-month-list").val() - 1);
		gEndMonthId = $('#end-month-list').val();
		gEndYearId = $('#end-year-list').val();
		shipmentReportTable.fnDraw();
	});

	$("#end-year-list").change(function() {
		endDate.setYear($("#end-year-list").val());
		endDate.setMonth($("#end-month-list").val() - 1);
		gEndMonthId = $('#end-month-list').val();
		gEndYearId = $('#end-year-list').val();
		shipmentReportTable.fnDraw();
	});

	////////////////////////////////////////////////////////////////////

	$('#status-list').change(function() {
		shipmentReportTable.fnDraw();
	});
    
    $('#OwnerType').change(function() {
	shipmentReportTable.fnDraw(); 
    });

	shipmentReportTable = $('#shipmentReportTable').dataTable({
		"bFilter" : true,
		"bJQueryUI" : true,
		"bSort" : true,
		"bInfo" : true,
		"bPaginate" : true,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[6, 'asc'], [4, 'desc']],
		"sPaginationType" : "full_numbers",
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		// 
		"sAjaxSource" : baseUrl + "report_shipment_server.php",
		"fnDrawCallback" : function(oSettings) {
			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			var nTrs = $('#shipmentReportTable tbody tr');
			var iColspan = nTrs[0].getElementsByTagName('td').length;
			var sLastGroup = "";
			for (var i = 0; i < nTrs.length; i++) {
				var iDisplayIndex = i;
				var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[6];
				if (sGroup != sLastGroup) {
					var nGroup = document.createElement('tr');
					var nCell = document.createElement('td');
					nCell.colSpan = iColspan;
					nCell.className = "group";
					nCell.innerHTML = sGroup;
					nGroup.appendChild(nCell);
					nTrs[i].parentNode.insertBefore(nGroup, nTrs[i]);
					sLastGroup = sGroup;
				}
			}
		},
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : 'getShipmentReportData',
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
				"name" : "ACountryId",
				"value" : $('#country-list').val()
			});
			aoData.push({
				"name" : "AFundingSourceId",
				"value" : $('#fundingSource-list').val()
			});
			aoData.push({
				"name" : "ASStatusId",
				"value" : $('#status-list').val()
			});
			aoData.push({
				"name" : "ItemGroup",
				"value" : $('#item-group').val()
			});
            aoData.push({
    				"name" : "OwnerType",
    				"value" : $('#OwnerType').val()
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
				"success" : fnCallback
			});
		},
		"aoColumns" : [{
			"sClass" : "SL",
			"sWidth" : "10%",
			"bSortable" : false
		}, {
			"sClass" : "ItemName",
			"sWidth" : "20%",
			"bSortable" : false
		}, {
			"sClass" : "Agency",
			"sWidth" : "17%",
			"bSortable" : false
		}, {
			"sClass" : "Status",
			"sWidth" : "20%",
			"bSortable" : false
		}, {
			"sClass" : "Date",
			"sWidth" : "15%",
			"bSortable" : false
		}, {
			"sClass" : "Quantity",
			"sWidth" : "15%",
			"bSortable" : false
		}, {
			"sClass" : "Country",
			"bVisible" : false,
			"bSortable" : false
		}]
	});

}); 