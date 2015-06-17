var yearList;

var gFacilityCode;
var gMonthId;
var gYearId;

var endDate = new Date();

var oTableRiskProducts;

$(function() {

	yearList = getYearList();

	$.each(monthList, function(i, obj) {
		$('#month-list').append($('<option></option>').val(obj.MonthId).html(obj.MonthName));
	});
	endDate.setMonth(objInit.initialMonth - 1);
	$("#month-list").val(objInit.initialMonth);

	$.each(yearList, function(i, obj) {
		$('#year-list').append($('<option></option>').val(obj.YearId).html(obj.YearName));
	});
	$("#year-list").val(endDate.getFullYear());

	$("#left-arrow").click(function() {

		if (endDate.getMonth() == 0 && endDate.getFullYear() == yearList[yearList.length - 1].YearId)
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

	oTableRiskProducts = $('#tbl-risk-products').dataTable({
		"bFilter" : false,
		"bJQueryUI" : false,
		"bSort" : true,
		"bInfo" : false,
		"bPaginate" : false,
		"bSortClasses" : false,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[3, 'asc']],
		"sPaginationType" : "full_numbers",
		"aLengthMenu" : [[50, 100, 200], [50, 100, 200]],
		"iDisplayLength" : 50,
		"sAjaxSource" : baseUrl + "risk_products_server.php",
		"fnDrawCallback" : function(oSettings) {
			if (oSettings.aiDisplay.length == 0) {
				return;
			}
			var nTrs = $('#tbl-risk-products tbody tr');
			var iColspan = nTrs[0].getElementsByTagName('td').length;
			var sLastGroup = "";
			for (var i = 0; i < nTrs.length; i++) {
				var iDisplayIndex = i;
				var sGroup = oSettings.aoData[oSettings.aiDisplay[iDisplayIndex]]._aData[5];
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
				"name" : "operation",
				"value" : 'getRiskProducts'
			});
			aoData.push({
				"name" : "CountryId",
				"value" : $('#CountryName').val()
			});
			aoData.push({
				"name" : "Year",
				"value" : $('#year-list option[value=' + $('#year-list').val() + ']').text()
			});
			$.ajax({
				"dataType" : 'json',
				"type" : "GET",
				"url" : sSource,
				"data" : aoData,
				"success" : fnCallback
			});
		},
		"aoColumns" : [{
			"sClass" : "center-aln",
			"bSortable" : false,
			"sWidth" : "5%"
		}, {
			"sClass" : "left-aln",
			"bSortable" : false,
			"sWidth" : "50%"
		}, {
			"sClass" : "right-aln",
			"bSortable" : false,
			"sWidth" : "15%"
			// ,
			// fnRender : function(oDt) {
				// return formatNumber(oDt.aData[2]);
			// }
		}, {
			"sClass" : "right-aln",
			"bSortable" : true,
			"bVisible" : true,
			"sWidth" : "15%"
			
		}, {
			"sClass" : "right-aln",
			"bSortable" : true,
			"bVisible" : true,
			"sWidth" : "15%"
		}, {
			"sClass" : "left-aln",
			"bSortable" : true,
			"bVisible" : false,
		}]
	});

});
