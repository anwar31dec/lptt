var gItemGroupId;
var oInventoryControl;
var endDate = new Date();
var ccLat = 0;
var ccLong = 0;
var yearList;
var gMonthId;
var gYearId;
var gCountryId;
var gFrequencyId=1;
var gStartYearId;
var gSartMonthId;
var gMosTypeId = 0;
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

function getLegendMos() {
	$.ajax({
		type : "POST",
		dataType : "json",
		url : baseUrl + "national_inventory_control_server.php",
		data : {
			"operation" : 'getLegendMos',
			"CountryId" : gCountryId,
			"lan" : lan
		},
		success : function(response) {
			var legendTable = '';

			$('#barchartlegend').html('');

			var x = "<table><tr>";
			var y = "</tr><tr>";
			var z = "</tr><tr>";

			var mos='';
			if(lan=='en-GB')
				mos='MOS';
			else
				mos='MSD';

			for (var i = 0; i < response.length; i++) {
				x += "<td width='110px' height='24px' style='background-color:"+response[i].ColorCode+"'>&nbsp</td>";
				y += "<td>" + response[i].MosTypeName + "</td>";
				z += "<td>"+mos+": " +  response[i].MosLabel + "</td>";

			};

			legendTable = x + y + z + "</tr></table>";

			$('#barchartlegend').html(legendTable);
		}
	});
}


function getMostypeNameForBtn() {
	$.ajax({
		type : "POST",
		dataType : "json",
		url : baseUrl + "national_inventory_control_server.php",
		data : {
			"operation" : 'getLegendMos',
			"CountryId" : 1,
			"lan" : lan
		},
		success : function(response) {

			var x1 = '<div class="btn-group pull-left">' + '<button id="0" class="btn btn-default active" type="button">All</button>';
			for (var i = 0; i < response.length; i++) {
				x1 += '<button id = "' + response[i].MosTypeId + '" class="btn btn-default" type="button">' + response[i].MosTypeName + '</button>';
			}

			x1 = x1 + "</div>";
			
			$('#fic-group-button').html('');

			$('#fic-group-button').html(x1);
			
			$('.btn-default').removeClass('active');
			$('#' + gMosTypeId).addClass('active');

			$(".btn-default").click(function() {
				gMosTypeId = this.id;
				//alert(gMosTypeId);
				$('.btn-default').removeClass('active');
				$('#' + this.id).addClass('active');
				callServer();
			});
		}
	});
}

function getMosTypeProduct() {

	$('#tbl-fic').html('');
	html = '<table class="table table-striped table-bordered display table-hover" cellspacing="0" id="tbl-facility-inventory-control">';
	html += '<thead></thead>';
	html += '<tbody></tbody>';
	html += '</table>';
	$('#tbl-fic').html(html);

	$('body').animate({
		opacity : 1
	}, 1000, function() {

		$.ajax({
			type : "POST",
			dataType : "json",
			url : baseUrl + "national_inventory_control_server.php",
			data : {
				"operation" : 'getMosType',
				"MosTypeId" : gMosTypeId,
				"CountryId" : gCountryId,
				"lan" : lan,
				"Reportby" : $('#report-by').val()
			},
			success : function(oColumns) {
				
		getLegendMos();

		oInventoryControl = $('#tbl-facility-inventory-control').dataTable({
					"bFilter" : false,
					"bJQueryUI" : true,
					"bSort" : false,
					"bInfo" : false,
					"bPaginate" : false,
					"bSortClasses" : false,
					"bProcessing" : true,
					"bServerSide" : true,
					"bDestroy":  true,
					"sPaginationType" : "full_numbers",
					"sAjaxSource" : baseUrl + "national_inventory_control_server.php",
					"fnDrawCallback" : function(oSettings) {
					},
					"fnServerData" : function(sSource, aoData, fnCallback) {
						aoData.push({
							"name" : "operation",
							"value" : 'getMosTypeProduct'
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
							"value" : gMonthId
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
							"name" : "ItemGroupId",
							"value" : gItemGroupId
						});
						aoData.push({
							"name" : "MosTypeId",
							"value" : gMosTypeId
						});						
						aoData.push({
							"name" : "lan",
							"value" : lan
						});
						aoData.push({
							"name": "Reportby",
							"value": $('#report-by').val()
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
					"aoColumns" : oColumns
				});
			}
		});
	});
}


function callServer() {
	getMosTypeProduct();
    getReportGeneratePercentage();
}



function getItemGroupFrequency() {
    if(gItemGroupId==0){
        gFrequencyId = 1;
        gSartMonthId = 1;
        gStartYearId = '2014';
        getMonthByFrequencyId();

    }else{
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
            }
            else if (gFrequencyId == 2) {
                endDate.setMonth(objInit.svrLastMonth - 1);
                endDate.setFullYear(objInit.svrLastYear);
                endDate.lastQuarter();
            }
			
			/*
			if (gFrequencyId == 1){
				endDate.setMonth(ghp.MonthId - 1);
				endDate.setFullYear(objInit.svrLastYear);
			}
			else if (gFrequencyId == 2) {
				endDate.setMonth(ghp.MonthId - 1);
				endDate.setFullYear(objInit.svrLastYear);
				endDate.lastQuarter();
			}
			*/
			

			$("#month-list").val(endDate.getMonth() + 1);
			$("#year-list").val(endDate.getFullYear());
			
			//rubel
			gMonthId = $('#month-list').val() == null ? -99 : $('#month-list').val();

			callServer();

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
	gYearId = $('#year-list').val();

	

	
	$.each(gReportByList, function(i, obj) {
		$('#report-by').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
	});
	$('#report-by').val(gDetaultOwnerTypeId);
  //  $("#report-by").val(ghp.Reportby);
    gReportByList = $('#report-by').val();

	
	// if (gMonthId == 0) {
		// endDate.setMonth(objInit.svrLastMonth - 1);
		// $("#month-list").val(objInit.svrLastMonth);
	// } else {
		// endDate.setMonth(gMonthId);
		// $("#month-list").val(gMonthId);
	// }
	/*
	if (gMonthId == 0) {
		endDate.setMonth(ghp.MonthId - 1);
		$("#month-list").val(ghp.MonthId);
	} else {
		endDate.setMonth(ghp.MonthId);
		$("#month-list").val(ghp.MonthId);
	}

	gMonthId = $('#month-list').val();
*/




	$.each(gCountryList, function(i, obj) {
		$('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
	});
	
	$('#country-list').val(gUserCountryId);
	//$("#country-list").val(ghp.CountryId);
	
	
	if(gCountryId>0){
		$("#country-list").val(gCountryId);
	}
	else
		gCountryId = $('#country-list').val();

	$.each(gProductGroupList, function(i, obj) {
		$('#item-group-list').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});
	
	$('#item-group-list').val(gUserItemGroupId);
   // $("#item-group-list").val(ghp.ItemGroupId);
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

		callServer();

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

		callServer();
	});

	$("#month-list").change(function() {
		endDate.setMonth($("#month-list").val() - 1);

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		callServer();

	});

	$("#year-list").change(function() {
		endDate.setYear($("#year-list").val());
		endDate.setMonth($("#month-list").val() - 1);

		gMonthId = $("#month-list").val();
		gYearId = $("#year-list").val();

		callServer();
	});

	gCountryId = $("#country-list").val();

	$("#country-list").change(function() {
		gCountryId = $("#country-list").val();	
		callServer();
	});

	$("#item-group-list").change(function() {
		gItemGroupId = $("#item-group-list").val();
		callServer();
	});
	$("#report-by").change(function() {
	   gReportByList = $('#report-by').val();
		callServer();
	});
	
	getMostypeNameForBtn();		
	callServer();

});
