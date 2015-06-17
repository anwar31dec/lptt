var oTblServiceIndicators;
var endDate = new Date();
var gMonthId;
var gYearId;
var gCountryId;
var gItemGroupId;
var gFrequencyId=1;
var gStartYearId;
var gSartMonthId;
var gRegionId = 0;
var gDistrictId = 0;
var $ = jQuery.noConflict();

function getReportGeneratePercentage() {
    $.ajax({
        type: "POST",
	    url : baseUrl + "reporting_rate.php",
		data : {
			 operation : 'getPercentage',
			 CountryId : gCountryId,
			 ItemGroupId : gItemGroupId,
			 OwnerTypeId : $("#OwnerType").val(),
			 Year : $('#year-list').val(),
			 Month : $('#month-list').val(),
             RegionId: gRegionId,
             DistrictId: gDistrictId,
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

function getFillRegion() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: baseUrl + "combo_generic.php",
        data: {
            "operation": 'getFillRegion',
            "CountryId": gCountryId,
            "UserId": userId,
            "lan": lan
        },
        success: function(response) {
            $.each(response, function(i, obj) {
                $('#Region-list').append($('<option></option>').val(obj.RegionId).html(obj.RegionName));
            });

            var RegionList = response || [];
            var html = $.map(RegionList, function(obj) {
                return '<option value=' + obj.RegionId + '>' + obj.RegionName + '</option>';
            }).join('');

            $('#Region-list').html(html);
            gRegionId = $('#Region-list').val() == null ? 0 : $('#Region-list').val();
			getFillDistrict();
        }
    });
}


function getFillDistrict() {
	$.ajax({
		type : "POST",
		dataType : "json",
		url : baseUrl + "combo_generic.php",
		data : {
			"operation" : 'getFillDistrict',
			"CountryId": gCountryId,
			"RegionId" : gRegionId,
			"lan" : lan
		},
		success : function(response) {	
			$.each(response, function(i, obj) {
				$('#District-list').append($('<option></option>').val(obj.DistrictId).html(obj.DistrictName));
			});
			
			var DistrictList = response || [];
			var html = $.map(DistrictList, function(obj) {
				return '<option value=' + obj.DistrictId + '>' + obj.DistrictName + '</option>';
			}).join('');

			$('#District-list').html(html);
			gDistrictId = $('#District-list').val() == null ? 0 : $('#District-list').val();
		}
	});
}


	

function callServer(){
    	oTblServiceIndicators.fnDraw();
        getReportGeneratePercentage();
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

    $.each(gCountryListFLevel, function(i, obj) {
		$('#country-list').append($('<option></option>').val(obj.CountryId).html(obj.CountryName));
	});
	
	$('#country-list').val(gUserCountryId);
    gCountryId = $("#country-list").val();

    
    $.each(gProductGroupList, function(i, obj) {
		$('#item-group-list').append($('<option></option>').val(obj.ItemGroupId).html(obj.GroupName));
	});
	
	$('#item-group-list').val(gUserItemGroupId);
	gItemGroupId = $('#item-group-list').val();
    
    
    getFillRegion();
	
    $.each(gOwnerTypeList, function(i, obj) {
		$('#OwnerType').append($('<option></option>').val(obj.OwnerTypeId).html(obj.OwnerTypeName));
	});    
    
	$('#OwnerType').val(gDetaultOwnerTypeId);
	gOwnerTypeId = $("#OwnerType").val();    
	
    
	$('#country-list').change(function() {
		gCountryId = $("#country-list").val();
		getFillRegion();
        gRegionId = 0;        
		gDistrictId = 0;
		callServer();
	});
    
	$('#item-group-list').change(function() {
		gItemGroupId = $("#item-group-list").val();
		callServer();
	});
    
    $("#Region-list").change(function() {
		gRegionId = $("#Region-list").val();
		getFillDistrict();
		gDistrictId = 0;
		callServer();
	});
    
    $("#District-list").change(function() {
		gDistrictId = $("#District-list").val();
		callServer();
	});
    
     $("#month-list").change(function() {
		endDate.setMonth($("#month-list").val() - 1);
	    callServer();
	});
	
	$("#year-list").change(function() {
		endDate.setYear($("#year-list").val());
		endDate.setMonth($("#month-list").val() - 1);        
	    callServer();
	});
    
    $("#OwnerType").change(function() {
		gOwnerTypeId = $("#OwnerType").val();
		callServer();
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
	    callServer();
	});
    
	

	oTblServiceIndicators = $('#stockoutstatustable').dataTable({
		"bFilter" : false,
		"bJQueryUI" : true,
		"bSort" : false,
		"bInfo" : false,
		"bPaginate" : false,
		"bSortClasses" : true,
		"bProcessing" : true,
		"bServerSide" : true,
		"aaSorting" : [[1, 'asc']],
		"sPaginationType" : "full_numbers",
		"aLengthMenu" : [[25, 50, 100], [25, 50, 100]],
		"iDisplayLength" : 25,
		"sAjaxSource" : baseUrl + "report_facility_stockout_percentage_server.php",
		"fnServerData" : function(sSource, aoData, fnCallback) {
			aoData.push({
				"name" : "action",
				"value" : "getstockoutstatustable"
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
				"name": "Month",
				"value": $('#month-list').val()
			});
            aoData.push({
				"name": "Year",
				"value": $('#year-list').val()
			});
            aoData.push({
				"name" : "Country",
				"value" : $('#country-list').val()
			});
            aoData.push({
				"name" : "ItemGroupId",
				"value" : $('#item-group-list').val()
			});
            aoData.push({
					"name": "OwnerType",
					"value": $('#OwnerType').val()
				});	
            aoData.push({
            	"name" : "RegionId",
            	"value" : gRegionId
            });
            aoData.push({
            	"name" : "DistrictId",
            	"value" : gDistrictId
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
		"aoColumns" : [{
            "sClass" : "left-aln",
			"sWidth" : "40%"
		}, {
            "sClass" : "left-aln",
			"sWidth" : "35%"
		}, {
		    "sClass" : "right-aln",
			"sWidth" : "25%"
		}, {
		    "sClass" : "right-aln",
			"sWidth" : "25%"
		}, {
		    "sClass" : "right-aln",
			"sWidth" : "25%"
		}, {
		    "sClass" : "right-aln",
			"sWidth" : "25%"
		}, {
		    "sClass" : "right-aln",
			"sWidth" : "25%"
		}]
	});  
    
	callServer();

		
});


